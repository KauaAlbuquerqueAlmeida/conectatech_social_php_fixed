<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once dirname(__DIR__) . '/config/db_init.php';

function create_post($user_id, $content, $file){
    $pdo = getDB();
    $imageName = null;
    if ($file && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif'];
        if (!isset($allowed[$mime])) return ['error'=>'Tipo de imagem não permitido'];
        if ($file['size'] > 5 * 1024 * 1024) return ['error'=>'Arquivo maior que 5MB'];
        $ext = $allowed[$mime];
        $imageName = bin2hex(random_bytes(8)) . '.' . $ext;
        $destDir = dirname(__DIR__) . '/public/uploads';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        if (!move_uploaded_file($file['tmp_name'], $destDir . '/' . $imageName)){
            return ['error'=>'Falha ao mover arquivo'];
        }
    }
    $stmt = $pdo->prepare('INSERT INTO posts (user_id, content, image, created_at) VALUES (?,?,?,NOW())');
    $stmt->execute([$user_id, $content, $imageName]);
    return ['success'=>true, 'id'=>$pdo->lastInsertId()];
}

function fetch_feed($limit = 50, $offset = 0){
    $pdo = getDB();
    $stmt = $pdo->prepare('SELECT p.*, u.name FROM posts p LEFT JOIN users u ON u.id = p.user_id ORDER BY p.created_at DESC LIMIT ? OFFSET ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_user_by_id($id){
    $pdo = getDB();
    $stmt = $pdo->prepare('SELECT id, name, avatar FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

