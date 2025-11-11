<?php
session_start();
require 'connect.php';

if (!isset($_GET['user_id'])) {
    die("ไม่พบผู้ใช้");
}

$user_id = $_GET['user_id'];

// ดึงข้อมูลผู้ใช้
$stmt = $pdo->prepare("SELECT id, username, fullname, email, phone, date FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("ไม่พบข้อมูลผู้ใช้");
}

// ดึงโพสต์ของ user
$stmt = $pdo->prepare("SELECT id, title, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>โปรไฟล์ของ สมาชิกหมายเลข <?= htmlspecialchars($user['username']) ?></title>
<link rel="stylesheet" href="tailwind.css">
</head>
<body class="bg-[#0f0f15] text-gray-200">

<div class="max-w-[900px] mx-auto p-4">

    <h1 class="text-2xl text-[#d2cde1] mb-2">
        สมาชิกหมายเลข <?= htmlspecialchars($user['username']) ?>
    </h1>

    <p class="text-gray-400 text-sm mb-4">
        เข้าร่วมเมื่อ: <?= $user['date'] ?>
    </p>

    <p class="text-gray-300 mb-6">ชื่อ: <?= htmlspecialchars($user['fullname']) ?></p>
    <p class="text-gray-300 mb-6">อีเมล: <?= htmlspecialchars($user['email']) ?></p>

    <h2 class="text-xl mb-3 text-[#c3b9e8]">โพสต์ทั้งหมด</h2>

    <?php if (empty($posts)): ?>
        <p class="text-gray-500">ยังไม่มีโพสต์</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="border-b border-gray-700 py-2">
                <a href="post.php?id=<?= $post['id'] ?>" class="text-[#c3b9e8] hover:text-white">
                    <?= htmlspecialchars($post['title']) ?>
                </a>
                <p class="text-xs text-gray-500"><?= $post['created_at'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
