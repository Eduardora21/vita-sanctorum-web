<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Zona horaria local
date_default_timezone_set('America/Costa_Rica');

$jsonPath = __DIR__ . '/../data/evangelios.json';

if (!file_exists($jsonPath)) {
    http_response_code(404);
    echo json_encode(['error' => 'Archivo de datos no encontrado']);
    exit;
}

$jsonData = json_decode(file_get_contents($jsonPath), true);
$claveHoy = date('m-d'); // Ej: "10-23"

// Buscar el evangelio de la fecha actual; si no existe, usar el predeterminado
$evangelio = $jsonData[$claveHoy] ?? $jsonData['default'];

$respuesta = [
    'fecha' => date('Y-m-d'),
    'cita' => $evangelio['cita'],
    'texto' => $evangelio['texto'],
    'aclamacion' => $evangelio['aclamacion']
];

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);