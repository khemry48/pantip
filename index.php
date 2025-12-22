<?php
session_start();

// ❌ ถ้ายังไม่ได้ login → ห้ามเข้าหน้า index → เด้งไป login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ป้องกัน cache หน้าเก่า
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require 'connect.php';

// ✅ ดึงข้อมูล user ที่ login อยู่
$loggedInUserId = $_SESSION['user_id'];

$stmtUser = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmtUser->execute([$loggedInUserId]);
$currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
$currentUsername = $currentUser['username'] ?? 'ไม่ทราบชื่อ';

// ✅ ดึงโพสต์ทั้งหมด
$stmt = $pdo->query("
    SELECT 
        posts.*,
        users.username,
        COUNT(comments.id) AS comment_count
    FROM posts
    LEFT JOIN users ON posts.user_id = users.id
    LEFT JOIN comments ON comments.post_id = posts.id
    GROUP BY posts.id
    ORDER BY posts.created_at DESC
");

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ถ้าต้องการ users ทั้งหมด
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>pantip</title>
</head>

<body class="bg-[#3c3963]">

    <nav class="bg-[#2d2a49] border-b border-black dark:bg-gray-900 z-1000 fixed w-full top-0 left-0 shadow-lg">
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
                            <a href="profile.php?user_id=<?= htmlspecialchars($loggedInUserId) ?>">
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

    <div class="bg-[#353156] mt-[250px] border-b border-black">
        <div class="text-sm ml-[300px] text-gray-400 p-3">
            หน้าแรกพันทิป
        </div>
    </div>

    <div class="w-[1200px] mt-[20px] border-[0.1rem] border-[#7976a0] mx-auto">
        <div class="bg-[#1F1D33] p-3">
            <P class="text-[#FBC02D]">Pantip Realtime</P>
            <P class="text-[#9895A8] text-sm">กระทู้ที่มีคนเปิดอ่านมากในขณะนี้ อัปเดตทุกนาที</P>
        </div>
    </div>


    <div class="w-[1200px] mx-auto">
        <div class="grid grid-cols-2">
            <?php foreach ($posts as $post): ?>
                <div class="flex p-4 gap-2 border border-gray-500 mb-2">
                    <?php if (!empty($post['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image']) ?>" class="mt-2 max-w-[400px] rounded">
                    <?php endif; ?>
                    <p class="">✍</p>
                    <div class="flex flex-col justify-between w-full">
                        <a href="post.php?id=<?= $post['id'] ?>" class="text-[#d2cde1] text-[17px] hover:text-white" target="_blank">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                        <!-- <div class="text-[#8072a5] text-sm">
                            <?= $post['content'] ?>
                        </div> -->
                        <div class="flex justify-between items-center text-gray-400 text-xs mt-2">

                            <!-- ซ้าย -->
                            <div class="flex items-center gap-2">
                                <a href="profile.php?user_id=<?= htmlspecialchars($post['user_id']) ?>">
                                    <p class="hover:text-white hover:underline">สมาชิกหมายเลข <?= $post['username'] ?> </p>
                                </a>
                                <p>-</p>
                                <p><?= $post['created_at'] ?></p>
                            </div>

                            <!-- ขวา -->
                            <div class="flex items-center gap-2 text-[#9d9ac0] text-sm">
                                <p>
                                    <i class="fa-regular fa-comment"></i>
                                    <?= (int)$post['comment_count'] ?>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <p class="text-red-400"><?php echo $_SESSION['error']; ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("logoutBtn").addEventListener("click", function(event) {
                event.preventDefault(); // ป้องกันลิงก์ทำงานทันที

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการออกจากระบบใช่หรือไม่",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ออกจากระบบ',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // เมื่อกด "ใช่" ให้ไปที่ logout.php
                        window.location.href = "logout.php";
                    }
                });
            });
        });
    </script>


</body>

</html>