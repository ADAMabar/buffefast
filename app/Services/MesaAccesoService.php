<?php

namespace App\Services;

use App\Models\Sesion;
use App\Models\Cliente;
use App\Models\Mesa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * MesaAccesoService
 *
 * Se encarga de TODA la lógica de validación y creación del cliente
 * cuando alguien intenta unirse a una mesa.
 *
 * Tu controlador (ClienteAuthController@store) actualmente hace dos cosas:
 *   1. Busca la sesión fuera de la transacción
 *   2. Crea el cliente dentro de una transacción SIN bloqueo
 *
 * El problema es que la transacción actual NO bloquea nada.
 * Dos personas pueden pasar la validación de capacidad al mismo tiempo
 * porque ambas leen el mismo conteo antes de que ninguna haya insertado.
 *
 * Este service lo corrige moviendo TODO dentro de la transacción con bloqueo.
 *
 * ─── Cómo usarlo en tu ClienteAuthController@store ───────────────────────
 *
 *  // 1. Inyecta el service en el constructor del controlador:
 *  public function __construct(private MesaAccesoService $mesaAccesoService) {}
 *
 *  // 2. En store(), reemplaza toda la lógica actual por:
 *  try {
 *      $cliente = $this->mesaAccesoService->unirse($request->codigo, $request->nombre);
 *
 *      session(['sesion_id' => $cliente->sesion_id, 'cliente_id' => $cliente->id]);
 *      return redirect()->route('cliente.carta')->with('success', "¡Bienvenido, {$cliente->nombre}!");
 *
 *  } catch (\Exception $e) {
 *      return back()->withErrors(['codigo' => $e->getMessage()])->onlyInput('codigo', 'nombre');
 *  }
 *
 *  // El controlador queda limpio: solo recibe, llama al service y redirige.
 *  // Toda la lógica dura vive aquí.
 */
class MesaAccesoService
{
    /**
     * Intenta unir a un cliente a la mesa identificada por $codigo.
     *
     * Devuelve el Cliente recién creado si todo va bien.
     * Lanza \Exception con mensaje legible si algo falla.
     *
     * @throws \Exception
     */
    public function unirse(string $codigo, string $nombre): Cliente
    {
        // ----------------------------------------------------------------
        // PASO 1 — Búsqueda previa SIN transacción (lectura barata)
        // ----------------------------------------------------------------
        // Hacemos esta búsqueda FUERA de la transacción a propósito.
        //
        // Razón: si el código no existe, no tiene sentido abrir una
        // transacción, pedir bloqueos a la BBDD y consumir recursos.
        // Descartamos el caso más común (código incorrecto) aquí, gratis.
        //
        // Mantenemos tu lógica original de admitir 'solicitando_cuenta'
        // para no romper el comportamiento que ya tenías.
        //
        // TODO: Busca la sesión:
        //       Sesion::where('codigo', trim($codigo))
        //              ->whereIn('estado', ['activa', 'solicitando_cuenta'])
        //              ->first()
        //
        //       Si devuelve null → lanza new \Exception con el mismo mensaje
        //       que tenías: 'Código inválido o sesión finalizada. Consulta con el camarero.'
        //       (así no cambias nada en el comportamiento visible para el usuario)

        $sesionPrevia = Sesion::where('codigo', trim($codigo))->whereIn('estado', ['activa', 'solicitando_cuenta'])->first();

        if ($sesionPrevia == null) {
            throw new \Exception('Código inválido o sesión finalizada. Consulta con el camarero.');
        }


        // ----------------------------------------------------------------
        // PASO 2 — Transacción con bloqueo exclusivo (lockForUpdate)
        // ----------------------------------------------------------------
        // A partir de aquí TODO ocurre dentro de DB::transaction().
        //
        // Laravel hace COMMIT automático si el closure termina sin problemas.
        // Laravel hace ROLLBACK automático si cualquier línea lanza excepción.
        //
        // Dentro volvemos a buscar la sesión con lockForUpdate().
        //
        // ¿Por qué buscamos la sesión DOS veces?
        //
        //   Primera búsqueda (Paso 1): rápida, sin bloqueo, elimina el caso
        //   más frecuente (código malo) antes de abrir transacción.
        //
        //   Segunda búsqueda (aquí, con lockForUpdate): con bloqueo real.
        //   Entre la primera y la segunda búsqueda pueden haber pasado cosas:
        //   la mesa se cerró, alguien ocupó el último hueco, etc.
        //   Necesitamos los datos MÁS FRESCOS y BLOQUEADOS para decidir.
        //
        // ¿Qué hace lockForUpdate() exactamente?
        //   Añade FOR UPDATE al SQL → MySQL bloquea esa fila hasta que
        //   la transacción termine. Nadie más puede leerla con lockForUpdate()
        //   hasta que tú hagas COMMIT o ROLLBACK.
        //
        // Sin lockForUpdate() (tu código actual):
        //   Mesa con capacidad 4, hay 3 clientes. Entran A y B a la vez.
        //   A lee: 3 clientes → pasa validación
        //   B lee: 3 clientes → pasa validación  (no vio el INSERT de A aún)
        //   A inserta → 4 clientes
        //   B inserta → 5 clientes con capacidad 4 ❌
        //
        // Con lockForUpdate():
        //   A bloquea la fila → lee 3 → pasa → inserta → COMMIT → desbloquea
        //   B esperaba bloqueada → ahora lee 4 → 4 >= 4 → excepción ✅


        return DB::transaction(function () use ($codigo, $nombre) {

            // ------------------------------------------------------------
            // PASO 2A — Segunda lectura de la sesión CON lockForUpdate()
            // ------------------------------------------------------------
            // Esta es la lectura "oficial" con bloqueo.
            // Leemos de nuevo (no reutilizamos el objeto del Paso 1) para
            // asegurarnos de tener el estado más actualizado en el momento
            // exacto en que tenemos el bloqueo exclusivo.
            //
            // TODO: $sesion = Sesion::where('codigo', trim($codigo))
            //                       ->lockForUpdate()
            //                       ->first();
            //
            //       Si es null (borrada en el microsegundo entre paso 1 y aquí,
            //       muy raro pero posible en sistemas con mucha concurrencia):
            //       → throw new \Exception('La sesión ya no está disponible.')
            $sesion = Sesion::where('codigo', trim($codigo))->lockForUpdate()->first();
            if ($sesion == null) {
                throw new \Exception('La sesión ya no está disponible.');
            }

            // ------------------------------------------------------------
            // PASO 2B — Revalidar el estado DENTRO de la transacción
            // ------------------------------------------------------------
            // El estado que leímos en el Paso 1 puede haber cambiado.
            // El admin pudo cerrar la mesa justo mientras esperábamos el bloqueo.
            // Comprobamos el estado del objeto recién bloqueado, no del anterior.
            //
            // Casuísticas:
            //   'activa'             → OK, continúa
            //   'solicitando_cuenta' → Lo permites según tu lógica, continúa
            //   'cerrada'            → La mesa se cerró mientras esperabas
            //   otro valor           → Dato inesperado, rechaza por seguridad
            //
            // TODO: Haz un match o if/elseif sobre $sesion->estado
            //       Solo deja pasar 'activa' y 'solicitando_cuenta'
            //       Para 'cerrada' → throw new \Exception('Esta mesa ya ha cerrado. Consulta con el camarero.')
            //       Para cualquier otro → throw new \Exception genérica
            if ($sesion->estado == "cerrada") {
                throw new \Exception('Esta mesa ya ha cerrado. Consulta con el camarero.');
            } elseif ($sesion->estado != "activa" && $sesion->estado != "solicitando_cuenta") {
                // Si no es ni activa ni solicitando_cuenta (por ejemplo, si te inventaste un estado)
                throw new \Exception('Dato inesperado, rechaza por seguridad');
            }
            // ------------------------------------------------------------
            // PASO 2C — Cargar la mesa y verificar que tiene capacidad definida
            // ------------------------------------------------------------
            // La capacidad máxima está en la tabla 'mesas', no en 'sesiones'.
            // Necesitamos cargar la mesa relacionada, también con lockForUpdate()
            // porque la capacidad vive en esa fila.
            //
            // Si no bloqueamos la mesa, dos transacciones podrían leer
            // la misma capacidad en paralelo y ambas pensar que hay hueco.
            //
            // TODO: $mesa = Mesa::where('id', $sesion->mesa_id)
            //                   ->lockForUpdate()
            //                   ->first();
            //
            //       Si $mesa es null o $mesa->capacidad es null:
            //       → Log::warning("Mesa sin capacidad definida. sesion_id: {$sesion->id}")
            //       → throw new \Exception('No se puede acceder a esta mesa ahora. Consulta con el camarero.')
            //         (mensaje genérico al usuario, no expongas el problema interno)
            $mesa = Mesa::where('id', $sesion->mesa_id)->lockForUpdate()->first();

            if ($mesa == null) {
                throw new \Exception('No se puede acceder a esta mesa ahora. Consulta con el camarero.');
            }
            // ------------------------------------------------------------
            // PASO 2D — Contar clientes actuales de la sesión
            // ------------------------------------------------------------
            // Este COUNT ocurre dentro de la transacción con la fila bloqueada,
            // así que es el número REAL del momento exacto en que tenemos el lock.
            //
            // Usa ->clientes()->count() con paréntesis después de clientes.
            // Esto ejecuta COUNT(*) en SQL directamente.
            //
            // NO uses ->clientes->count() sin paréntesis: eso cargaría todos
            // los objetos Cliente en memoria PHP y luego contaría el array.
            // Funciona igual pero es más lento y consume más memoria.
            //
            // TODO: $clientesActuales = $sesion->clientes()->count();
            $clientesActuales = $sesion->clientes()->count();


            // ------------------------------------------------------------
            // PASO 2E — Validar capacidad (el check más importante)
            // ------------------------------------------------------------
            // La comparación que previene el overbooking.
            // Gracias al lockForUpdate() del Paso 2A, si dos personas
            // llegaron aquí a la vez, la segunda esperó y ahora ve el
            // conteo ya actualizado por la primera.
            //
            // Ejemplo con capacidad = 4, clientesActuales = 3:
            //   Persona A: lock → cuenta 3 → 3 < 4 → pasa → INSERT → COMMIT
            //   Persona B: esperaba → lock → cuenta 4 → 4 >= 4 → excepción ✅
            //
            // TODO: if ($clientesActuales >= $mesa->capacidad) {
            //           throw new \Exception(
            //               "Mesa completa ({$mesa->capacidad}/{$mesa->capacidad}). " .
            //               "No quedan plazas disponibles."
            //           );
            //       }
            if ($clientesActuales >= $mesa->capacidad) {

                throw new \Exception('Mesa completa, la capacidad de esta mesa es de ' . $mesa->capacidad . ' personas');

            }

            // ------------------------------------------------------------
            // PASO 2G — Crear el cliente (la única escritura en BBDD)
            // ------------------------------------------------------------
            // Si llegamos aquí todas las validaciones pasaron.
            // El INSERT ocurre dentro de la transacción bloqueada.
            //
            // Nota: siempre guarda trim($nombre) para evitar que
            // "Carlos" y "Carlos " sean tratados como personas distintas.
            //
            // TODO: $cliente = Cliente::create([
            //           'nombre'    => trim($nombre),
            //           'sesion_id' => $sesion->id,
            //       ]);
            //
            //       Log::info(
            //           "Cliente '{$cliente->nombre}' unido a mesa {$mesa->numero}. " .
            //           "Ocupación: " . ($clientesActuales + 1) . "/{$mesa->capacidad}"
            //       );
            //
            //       return $cliente;
            //       DB::transaction devuelve lo que devuelva el closure,
            //       así que el return aquí llega hasta el método unirse()
            $cliente = Cliente::create([
                'nombre' => trim($nombre),
                'sesion_id' => $sesion->id,
            ]);
            Log::info(
                "Cliente '{$cliente->nombre}' unido a mesa {$mesa->numero}. " .
                "Ocupación: " . ($clientesActuales + 1) . "/{$mesa->capacidad}"
            );
            return $cliente;
        });
    }
}