<?php

namespace App\Services;

use App\Models\Sesion;
use App\Models\Cliente;
use App\Models\Mesa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class MesaAccesoService
{
    /**
     * @throws \Exception
     */
    public function unirse(string $codigo, string $nombre): Cliente
    {
    
        $sesionPrevia = Sesion::where('codigo', trim($codigo))->whereIn('estado', ['activa', 'solicitando_cuenta'])->first();

        if ($sesionPrevia == null) {
            throw new \Exception('Código inválido o sesión finalizada. Consulta con el camarero.');
        }

        return DB::transaction(function () use ($codigo, $nombre) {

            $sesion = Sesion::where('codigo', trim($codigo))->lockForUpdate()->first();
            if ($sesion == null) {
                throw new \Exception('La sesión ya no está disponible.');
            }

        
            if ($sesion->estado == "cerrada") {
                throw new \Exception('Esta mesa ya ha cerrado. Consulta con el camarero.');
            } elseif ($sesion->estado != "activa" && $sesion->estado != "solicitando_cuenta") {
                // Si no es ni activa ni solicitando_cuenta (por ejemplo, si te inventaste un estado)
                throw new \Exception('Dato inesperado, rechaza por seguridad');
            }
          
            $mesa = Mesa::where('id', $sesion->mesa_id)->lockForUpdate()->first();

            if ($mesa == null) {
                throw new \Exception('No se puede acceder a esta mesa ahora. Consulta con el camarero.');
            }
           
            $clientesActuales = $sesion->clientes()->count();


           
            if ($clientesActuales >= $mesa->capacidad) {

                throw new \Exception('Mesa completa, la capacidad de esta mesa es de ' . $mesa->capacidad . ' personas');

            }

          
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