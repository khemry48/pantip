<?php
$host = "localhost";
$dbname = "pantip"; // ชื่อฐานข้อมูล
// กำหนดชื่อผู้ใช้และรหัสผ่านสำหรับเชื่อมต่อฐานข้อมูล
$username = "root"; 
$password = ""; 

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "เชื่อมต่อฐานข้อมูลสำเร็จ!";
} catch (PDOException $e) {
    echo "เชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
    exit();
}
?>