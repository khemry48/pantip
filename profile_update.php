<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id  = $_POST['user_id'];
$fullname = trim($_POST['fullname']);

// 🔒 กันแก้ของคนอื่น
if ($_SESSION['user_id'] != $user_id) {
    die("ไม่มีสิทธิ์แก้ไขข้อมูลนี้");
}

// ------------------
// จัดการรูปโปรไฟล์
// ------------------
$avatarName = null;

if (!empty($_FILES['avatar']['name'])) {

    // ดึงนามสกุลไฟล์
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

    // อนุญาตเฉพาะรูป
    $allowExt = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowExt)) {
        die("รองรับเฉพาะไฟล์รูป");
    }

    // 🔥 ชื่อไฟล์ตายตัว (เขียนทับ)
    $avatarName = 'avatar_' . $user_id . '.' . $ext;

    $uploadPath = __DIR__ . '/uploads/' . $avatarName;

    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath);
}

// ------------------
// UPDATE DATABASE
// ------------------
if ($avatarName) {
    $stmt = $pdo->prepare("
        UPDATE users
        SET fullname = ?, avatar = ?
        WHERE id = ?
    ");
    $stmt->execute([$fullname, $avatarName, $user_id]);
} else {
    $stmt = $pdo->prepare("
        UPDATE users
        SET fullname = ?
        WHERE id = ?
    ");
    $stmt->execute([$fullname, $user_id]);
}

header("Location: profile.php?user_id=" . $user_id);
exit();
?>