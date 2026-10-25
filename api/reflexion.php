<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

require_once __DIR__ . '/../config/ai_config.php';

$input = json_decode(file_get_contents('php://input'), true);
$nombreSanto = trim($input['santo'] ?? '');
$tituloSanto = trim($input['titulo'] ?? '');

if (empty($nombreSanto)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'No se indicó el santo']);
    exit;
}

$apiKey = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';
if (empty($apiKey)) {
    echo json_encode(['ok' => false, 'error' => 'Falta GEMINI_API_KEY en config/ai_config.php']);
    exit;
}

$prompt = "Eres un guía espiritual para Vita Sanctorum. Escribe una reflexión piadosa, concisa y enriquecedora (de 2 a 3 párrafos breves) sobre las virtudes y el ejemplo de: {$nombreSanto} ({$tituloSanto}), explicando cómo aplicar su enseñanza moral a la vida cotidiana de hoy. Mantén un tono respetuoso y tradicional.";

$payload = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 600
    ]
];

// Modelos activos y compatibles con tu API key
$modelos = [
    'gemini-3.5-flash-lite',
    'gemini-3.8-flash'
];

$textoGenerado = null;
$ultimoError = '';

foreach ($modelos as $modelo) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelo}:generateContent?key=" . urlencode($apiKey);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        $ultimoError = 'Error de cURL: ' . $curlError;
        continue;
    }

    $data = json_decode($response, true);

    if ($httpCode === 200 && isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $textoGenerado = $data['candidates'][0]['content']['parts'][0]['text'];
        break;
    } else {
        $ultimoError = $data['error']['message'] ?? "Error HTTP {$httpCode}";
    }
}

if ($textoGenerado) {
    echo json_encode(['ok' => true, 'reflexion' => $textoGenerado]);
} else {
    echo json_encode(['ok' => false, 'error' => 'No fue posible generar la reflexión. ' . $ultimoError]);
}