<?php
require_once dirname(__DIR__) . '/src/AuthController.php';
if (session_status() == PHP_SESSION_NONE) session_start();
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $res = register_user($name, $email, $password);
    if (isset($res['error'])) $error = $res['error'];
    else {
        $_SESSION['flash_success'] = 'Registrado com sucesso. Faça login.';
        header('Location: /login.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Registrar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link href="/style.css" rel="stylesheet"></head>
<body>
<div class="container mt-5">
  <div class="card p-4" style="max-width:480px;margin:0 auto;">
    <h4>Registrar</h4>
    <?php if ($error): ?><div class="flash flash-error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
      <label class="form-label">Nome</label>
      <input name="name" class="form-control" required>
      <label class="form-label mt-2">Email</label>
      <input name="email" type="email" class="form-control" required>
      <label class="form-label mt-2">Senha</label>
      <input name="password" type="password" class="form-control" required>
      <div class="mt-3 d-flex justify-content-between">
        <a href="/login.php">Já tem conta?</a>
        <button class="btn btn-primary">Registrar</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
