<?php
// Carrega variáveis do .env (simples, sem dependências externas)
function load_env($path){
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!getenv($name)) putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

load_env(dirname(__DIR__) . '/.env');

function getDB(){
    static $pdo = null;
    if ($pdo) return $pdo;
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? '127.0.0.1';
    $db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'conectatech';
    $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $opts);
    } catch (PDOException $e) {
        // Em ambiente de desenvolvimento, mostrar a mensagem; em produção, logar e mostrar genérico.
        die('Erro de conexão com o banco: ' . $e->getMessage());
    }
    return $pdo;
}
