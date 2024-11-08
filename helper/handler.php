<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Вызов Python скрипта для форматирования номеров
    $command = escapeshellcmd('python3 ../main.py ' . escapeshellarg(json_encode($input)));
    $output = shell_exec($command);

    echo $output;
}
?>
