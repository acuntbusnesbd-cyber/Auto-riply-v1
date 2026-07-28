<?php
// Response header set
header('Content-Type: application/json; charset=utf-8');

// AutoResponder App theke pathano prompt capture
$message = isset($_GET['prompt']) ? $_GET['prompt'] : '';

if (empty($message)) {
    echo json_encode([
        "replies" => [
            ["message" => "Ki bolte chan, ektu sposto kore likhun! 😊"]
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Target API Credentials
$apiKey = "ak_b97fa90ad3629a3ed554b4651e5ac7641bce9d9d8d75ab66aa92673b9dda1cfe";
$apiUrl = "https://api.innocent-ai.top/gemini3-5flash.php?key=" . $apiKey . "&prompt=" . urlencode($message);

// cURL Request setup
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

// User-Agent added to bypass 403 Forbidden blocks
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Output processing
$ai_reply = "Dukhito, kono somosya hoyeche. Ektu por abar chesta korun.";

if ($response !== false && !empty($response)) {
    $json_data = json_decode($response, true);
    
    if (is_array($json_data)) {
        if (isset($json_data['reply'])) {
            $ai_reply = $json_data['reply'];
        } elseif (isset($json_data['message'])) {
            $ai_reply = $json_data['message'];
        } elseif (isset($json_data['response'])) {
            $ai_reply = $json_data['response'];
        }
    } else {
        $ai_reply = $response;
    }
}

// Final output in AutoResponder JSON format
echo json_encode([
    "replies" => [
        [
            "message" => trim($ai_reply)
        ]
    ]
], JSON_UNESCAPED_UNICODE);
?>

