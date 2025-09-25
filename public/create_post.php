<?php
require_once dirname(__DIR__) . '/src/PostController.php';
require_once dirname(__DIR__) . '/src/AuthController.php';
if (session_status() == PHP_SESSION_NONE) session_start();
require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';
    $res = create_post($_SESSION['user_id'], $content, $_FILES['image'] ?? null);
    if (isset($res['error'])) $_SESSION['flash_error'] = $res['error'];
    else $_SESSION['flash_success'] = 'Post criado com sucesso.';
}
header('Location: ./index.php');
exit;
