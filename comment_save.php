<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    die("กรุณาเข้าสู่ระบบ");
}

$post_id   = $_POST['post_id'] ?? null;
$content   = trim($_POST['content'] ?? '');
$parent_id = $_POST['parent_id'] ?? null;

if ($content === '') {
    die("กรุณากรอกข้อความ");
}

$stmt = $pdo->prepare("
    INSERT INTO comments (post_id, user_id, content, parent_id, created_at)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->execute([
    $post_id,
    $_SESSION['user_id'],
    $content,
    $parent_id ?: null
]);

header("Location: post.php?id=" . $post_id);
exit();
?>