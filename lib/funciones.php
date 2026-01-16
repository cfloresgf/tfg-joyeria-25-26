<?php

function e($s){
    return htmlspecialchars($s, ENT_QUOTES);
}

function formatearFecha($fechaISO){ // 2025-10-26
    // Devolver fecha en formato 26/10/2025

    $d = DateTime::createFromFormat('Y-m-d', $fechaISO);   
    return $d->format('d/m/Y');

}

function formatearFechaLarga($fechaISO){ // 2025-10-26
    // Devolver fecha en formato 26 octubre 2025

    $dt = DateTime::createFromFormat("Y-m-d", $fechaISO);
    return IntlDateFormatter::formatObject($dt, "d MMMM yyyy", 'es-ES');

}

function formatearFechaHora($fechaISO){ // 2025-10-26 14:50:30
    // Devolver fecha en formato 26/10/2025 14:50

    $d = DateTime::createFromFormat('Y-m-d H:i:s', $fechaISO);
    return $d->format('d/m/Y H:i');

}

function formatearFechaHoraLarga($fechaISO){ // 2025-10-26 14:50:30
    // Devolver fecha en formato 26 octubre 2025, 14:50

    $dt = DateTime::createFromFormat('Y-m-d H:i:s',$fechaISO);
    return IntlDateFormatter::formatObject($dt, 'd/MM/yyyy (H:mm)', 'es-ES');

}
