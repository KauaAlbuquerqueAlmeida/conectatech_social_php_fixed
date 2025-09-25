<?php
require_once dirname(__DIR__) . '/src/AuthController.php';
logout_user();
header('Location: ./index.php');
exit;
