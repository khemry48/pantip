<?php
session_start();
require 'connect.php'; // ไฟล์เชื่อมต่อ PDO

if (!isset($_GET['id'])) {
    die("ไม่พบโพสต์");
}

$id = $_GET['id'];

// เตรียมคำสั่ง SELECT
$stmt = $pdo->prepare("SELECT posts.*, users.username 
                      FROM posts
                      JOIN users ON posts.user_id = users.id
                      WHERE posts.id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("โพสต์นี้ไม่มีอยู่จริง หรือถูกลบไปแล้ว");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-[#3c3963] text-gray-200">
    <nav class="bg-[#2d2a49] border-b border-black dark:bg-gray-900 z-1000 fixed w-full top-0 left-0 shadow-lg">
        <div class="flex flex-wrap items-center justify-between">
            <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
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

    <div class="w-full h-[200px] mt-0 fixed top-0 left-0 mt-[52px]"
        title="น้อมรำลึกในพระมหากรุณาธิคุณตราบนิจนิรันดร์ สมเด็จพระนางเจ้าสิริกิติ์ พระบรมราชินีนาถ พระบรมราชชนนีพันปีหลวง"
        style="background:url(https://ptcdn.info/doodle/2025/68fc1835caac0a3a4b2f8e34_xk96e10awt.png), url(https://ptcdn.info/doodle/2025/68fc1835caac0a3a4b2f8e34_mx8epq41h7.png);background-size:auto, cover;background-position:top, bottom;background-repeat:no-repeat, repeat">
    </div>

    <div class="bg-[#193366] max-w-[900px] mx-auto p-6 mt-[280px] border border-[#8e8ba7] shadow-lg mb-10">

        <h1 class="text-2xl text-[#ffcd05] mb-4">
            <?= htmlspecialchars($post['title']) ?>
        </h1>

        <?php if (!empty($post['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($post['image']) ?>" class="rounded mb-4 max-w-full">
        <?php endif; ?>

        <div class="text-[#c8c3d4] leading-relaxed">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>

        <p class="text-sm text-[#909db2] mt-4">
            <a href="profile.php?user_id=<?= $post['user_id'] ?>" class="text-[#909db2] hover:text-white hover:underline">
                สมาชิกหมายเลข <?= htmlspecialchars($post['username']) ?>
            </a>

             • <?= $post['created_at'] ?>
        </p>

    </div>

    <hr class="border-t border-[#686595] my-4">


</body>

</html>