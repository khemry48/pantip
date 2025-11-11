<?php
session_start();
require 'connect.php'; // ได้ตัวแปร $pdo

// ถ้าไม่ได้ล็อกอิน → เด้งกลับไป login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ถ้ากดเข้าหน้านี้โดยไม่ใช่การ submit form → กลับไปหน้า index
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// รับค่าจากฟอร์ม
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

// ตรวจสอบว่ากรอกข้อมูลครบ
if ($title === '' || $content === '') {
    $_SESSION['error'] = "กรุณากรอกหัวข้อและรายละเอียดก่อนส่ง";
    header("Location: index.php");
    exit();
}

// ดึง user_id จาก session
$user_id = $_SESSION['user_id'];

// บันทึกลงฐานข้อมูลพร้อม user_id
$imageName = null;

if (!empty($_FILES['image']['name'])) {
    $imageName = time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], "asset/pantip" . $imageName);
}

$stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, image) VALUES (:title, :content, :user_id, :image)");
$stmt->execute([
    ':title' => $title,
    ':content' => $content,
    ':user_id' => $user_id,
    ':image' => $imageName
]);


// กลับไปหน้า index
header("Location: index.php");
exit();
