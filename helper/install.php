<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$domain = getenv('BITRIX_DOMAIN');
$token = getenv('BITRIX_TOKEN');
$eventUrl = 'https://your-domain.com/path/to/helper/handler.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$domain/rest/event.bind.json");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'auth' => $token,
    'event' => 'OnContactAdd',
    'handler' => $eventUrl
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    echo "Приложение успешно установлено!";
} else {
    echo "Ошибка установки приложения.";
}
?>
