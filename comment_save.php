<?php
session_start();
require 'connect.php';

// ต้องล็อกอินก่อน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_POST['post_id'];
$content = trim($_POST['content']);
$user_id = $_SESSION['user_id'];

// ตรวจสอบข้อมูล
if ($content === '') {
    $_SESSION['error'] = "กรุณากรอกความคิดเห็นก่อนส่ง";
    header("Location: post.php?id=" . $post_id);
    exit();
}

// บันทึกลงฐานข้อมูล
$stmt = $pdo->prepare("
    INSERT INTO comments (post_id, user_id, content)
    VALUES (?, ?, ?)
");
$stmt->execute([$post_id, $user_id, $content]);

// กลับไปหน้ากระทู้
header("Location: post.php?id=" . $post_id);
exit();
?>