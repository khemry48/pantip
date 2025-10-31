<?php
session_start();
require 'connect.php'; // ได้ตัวแปร $pdo

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
    // ถ้าข้อมูลไม่ครบ ให้ redirect กลับพร้อมข้อความแจ้งเตือน
    $_SESSION['error'] = "กรุณากรอกหัวข้อและรายละเอียดก่อนส่ง";
    header("Location: index.php");
    exit();
}

// บันทึกลงฐานข้อมูล
$stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (:title, :content)");
$stmt->execute([
    ':title' => $title,
    ':content' => $content
]);

// กลับไปหน้า index
header("Location: index.php");
exit();
