<?php
session_start();

// ล้างทุกค่าใน session
$_SESSION = array();

// ถ้ามี cookie ของ session ให้ลบทิ้งด้วย
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ทำลาย session
session_destroy();

// กลับไปหน้า login
header("Location: login.php");
exit();
?>
