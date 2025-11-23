<?php
session_start();
require 'connect.php';

// ตรวจสอบว่ามี user_id มั้ย
if (!isset($_GET['user_id'])) {
    die("ไม่พบผู้ใช้");
}

$user_id = $_GET['user_id'];

/* -------------------------
   1) ดึงข้อมูลผู้ใช้
-------------------------- */
$stmt = $pdo->prepare("
    SELECT id, username, fullname, email, phone, date
    FROM users
    WHERE id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("ไม่พบข้อมูลผู้ใช้");
}

/* -------------------------
   2) ดึงโพสต์ทั้งหมดของ user นี้ (JOIN)
-------------------------- */
$stmt = $pdo->prepare("
    SELECT posts.id, posts.title, posts.created_at, users.username, users.id AS user_id
    FROM posts
    INNER JOIN users ON posts.user_id = users.id
    WHERE users.id = ?
    ORDER BY posts.created_at DESC
");
$stmt->execute([$user_id]);

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>สมาชิกหมายเลข <?= htmlspecialchars($user['username']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-[#3c3963] text-gray-200">

    <nav class="bg-[#2d2a49] border-b border-black dark:bg-gray-900 z-1000 w-full top-0 left-0 shadow-lg">
        <div class="flex flex-wrap items-center justify-between">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-xl font-semibold whitespace-nowrap text-white ml-[100px]">pantip</span>
            </a>
            <div class="flex md:order-2 mr-6">
                <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search" aria-expanded="false" class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 me-1">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
                    <div class="relative mt-3 md:hidden">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search...">
                    </div>
                    <ul class="flex flex-col p-1 mt-4 font-medium border border-gray-100 rounded-lg bg-[#2d2a49] md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0">
                        <li>
                            <a href="newtopic.php" class="block py-2 px-3 mt-1 text-white rounded-sm md:bg-transparent md:text-white md:dark:text-white hover:bg-[#44416f]" aria-current="page" target="_blank">ตั้งกระทู้</a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-3 mt-1 text-white rounded-sm hover:bg-[#44416f]">คอมมูนิตี้</a>
                        </li>
                        <li>
                            <a href="#" id="logoutBtn" class="block py-2 px-3 mt-1 text-white rounded-sm hover:bg-[#44416f]">logout</a>
                        </li>
                        <li>
                            <a href="#" class="block">
                                <img class="w-[35px] h-[35px] rounded-3xl mt-1" src="./asset/winter.jpg" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="relative hidden md:block">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search icon</span>
                </div>
                <input type="text" id="search-navbar" class="block w-full p-1 ps-10 text-sm text-white border border-gray-300 rounded-sm bg-[#44416f] focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
            </div>
        </div>
    </nav>

    <div class="relative w-full h-[200px] mt-0 top-0 left-0 shadow-lg"
        title="pantip.com"
        style="background:url(https://ptcdn.info/images/cover/1140x240-default-member-profile.png), url(https://ptcdn.info/images/cover/background-default-member-profile.png);background-size:auto, cover;background-position:top, bottom;background-repeat:no-repeat, repeat">
    </div>

    <div class="flex justify-center mt-2 ml-[30px]">
        <div class="w-full mx-4 p-6 max-w-3xl">
            <h1 class="text-2xl text-[#d2cde1] mb-2">
                สมาชิกหมายเลข <?= htmlspecialchars($user['username']) ?>
            </h1>

            <p class="text-gray-400 text-sm mb-4">
                เข้าร่วมเมื่อ: <?= $user['date'] ?>
            </p>

            <!-- <p class="text-gray-300 mb-6">ชื่อ: <?= htmlspecialchars($user['fullname']) ?></p>
            <p class="text-gray-300 mb-6">อีเมล: <?= htmlspecialchars($user['email']) ?></p> -->

        </div>
    </div>

    <div class="bg-[#2d2a49] border-b border-black">
        <div class="gap-[10px] flex justify-center mr-[300px]">
            <a href="#" class="p-3 text-[#979ab1] hover:text-white">
                ภาพรวม
            </a>
            <a href="#" class="p-3 text-[#979ab1] hover:text-white">
                กระทู้ที่ตั้ง
            </a>
            <a href="#" class="p-3 text-[#979ab1] hover:text-white">
                กระทู้ที่ตอบ
            </a>
            <a href="#" class="p-3 text-[#979ab1] hover:text-white">
                กระทู้ที่เคยอ่าน
            </a>
        </div>
    </div>

    <div class="bg-[#1f1d33] w-[700px] mx-auto mt-5">
        <p class="p-3 border border-b-0 border-[#7976a0] text-[#fbc02d]">กระทู้ที่ตั้ง</p>
    </div>
    <div class="mx-auto w-[700px] h-[250px] bg-[#2d2a49] border border-[#7976a0]">
        <?php if (empty($posts)): ?>
            <p class="text-[#9e9aa0] text-xl text-center mt-[100px]">ไม่พบกระทู้ที่ตั้ง</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="border-b border-gray-700 py-4">
                    <a href="post.php?id=<?= $post['id'] ?>" class="text-[#e5b26a] ml-4">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                    <div class="flex ml-4 mt-1 text-[#9d9ac0] text-[12px] gap-1">
                        <a href="profile.php?user_id=<?= htmlspecialchars($post['user_id']) ?>" class="hover:underline">
                            สมาชิกหมายเลข <?= htmlspecialchars($user['username']) ?>
                        </a>
                        <p>-</p>
                        <p class="ml-1"><?= $post['created_at'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

</html>