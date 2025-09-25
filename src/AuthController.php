<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once dirname(__DIR__) . '/config/db_init.php';

function register_user($name, $email, $password){
    $pdo = getDB();
    $name = trim($name);
    $email = trim($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['error' => 'Email inválido'];
    if (strlen($password) < 6) return ['error' => 'A senha precisa ter ao menos 6 caracteres'];
    // verificar se já existe
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) return ['error' => 'Email já cadastrado'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password,created_at) VALUES (?,?,?,NOW())');
    $stmt->execute([$name, $email, $hash]);
    return ['success' => true, 'id' => $pdo->lastInsertId()];
}

function login_user($email, $password){
    $pdo = getDB();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([trim($email)]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        return ['success' => true];
    }
    return ['error' => 'Credenciais inválidas'];
}

function logout_user(){
    if (session_status() == PHP_SESSION_NONE) session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function require_auth(){
    if (session_status() == PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user_id'])){
        header('Location: /login.php');
        exit;
    }
}
