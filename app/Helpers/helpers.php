<?php

use App\Models\Configuracion;
use Illuminate\Support\Facades\Cache;

if (!function_exists('configuracion')) {
   
    function configuracion($clave, $default = null)
    {
    
        $ajustes = Cache::remember('ajustes_globales', 60 * 60 * 24, function () {
            return Configuracion::pluck('valor', 'clave')->toArray();
        });

        // Devuelve el valor exacto que se pide o el valor por defecto si no lo encuentra.
        return $ajustes[$clave] ?? $default;
    }
}


