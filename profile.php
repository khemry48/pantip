<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
    SELECT id, username, fullname, email, phone, date, avatar
    FROM users
    WHERE id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("ไม่พบข้อมูลผู้ใช้");
}

$avatarPath = './asset/default-avatar.png';

if (!empty($user['avatar']) && file_exists(__DIR__ . '/uploads/' . $user['avatar'])) {
    $avatarPath = 'uploads/' . $user['avatar'];
}

$isOwner = ($_SESSION['user_id'] == $user['id']);

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

// ✅ ดึงกระทู้ที่ผู้ใช้ *เคยไปแสดงความคิดเห็น*
$stmt = $pdo->prepare("
    SELECT DISTINCT posts.id, posts.title, posts.created_at
    FROM comments
    INNER JOIN posts ON comments.post_id = posts.id
    WHERE comments.user_id = ?
    ORDER BY posts.created_at DESC
");
$stmt->execute([$user_id]);
$repliedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ ดึงกระทู้ที่อ่านแล้ว
$stmt = $pdo->prepare("
    SELECT DISTINCT posts.id, posts.title, posts.created_at
    FROM post_views
    INNER JOIN posts ON post_views.post_id = posts.id
    WHERE post_views.user_id = ?
    ORDER BY post_views.viewed_at DESC
");
$stmt->execute([$user_id]);
$viewedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($user);
// echo '</pre>';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>สมาชิกหมายเลข <?= htmlspecialchars($user['username'] ?: $user['id']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-[#3c3963] text-gray-200 w-full main-content pb-[100px]">

    <nav class="bg-[#2d2a49] border-b border-black dark:bg-gray-900 z-1000 w-full top-0 left-0 shadow-lg">
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
                                <img class="w-[35px] h-[35px] rounded-3xl mt-1" src="<?= htmlspecialchars($avatarPath) ?>?v=<?= time() ?>" alt="">
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

    <div class="flex justify-center mt-4 ml-[30px]">
        <div class="flex items-center w-full mx-4 p-6 max-w-3xl gap-5">
            <img
                src="<?= htmlspecialchars($avatarPath) ?>?v=<?= time() ?>" alt="avatar"
                class="w-[120px] h-[120px] rounded-full border-4 border-[#2d2a49] -mt-4 mb-4 object-cover">
            <div>
                <h1 class="text-2xl text-[#d2cde1] mb-2">
                    สมาชิกหมายเลข <?= htmlspecialchars($user['username'] ?: $user['id']) ?>
                </h1>

                <!-- <h1 class="text-xl text-[#d2cde1] mb-2">
                    <?= htmlspecialchars($user['fullname'] ?: $user['id']) ?>
                </h1> -->

                <p class="text-gray-400 text-sm mb-4">
                    เข้าร่วมเมื่อ: <?= $user['date'] ?>
                </p>
            </div>

            <?php if ($isOwner): ?>
                <div class="ml-[230px] bg-[#44416f] border border-[#565380] hover:bg-[#565380]">
                    <button onclick="openEditProfile()" class="p-1.5">
                        แก้ไขโปรไฟล์
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-[#2d2a49] border-b border-black" id="menu">
        <div class="gap-[10px] flex justify-center mr-[300px]">
            <a class="item p-3 active text-[#979ab1] hover:text-white" data-target="overview">ภาพรวม</a>
            <a class="item p-3 text-[#979ab1] hover:text-white" data-target="created">กระทู้ที่ตั้ง</a>
            <a class="item p-3 text-[#979ab1] hover:text-white" data-target="replied">กระทู้ที่ตอบ</a>
            <a class="item p-3 text-[#979ab1] hover:text-white" data-target="viewed">กระทู้ที่เคยอ่าน</a>
        </div>
    </div>

    <div id="overview" class="content-section">
        <!-- กระทู้ที่ตั้ง -->
        <div class="bg-[#1f1d33] w-[700px] mx-auto mt-8">
            <p class="p-3 border border-b-0 border-[#7976a0] text-[#fbc02d]">กระทู้ที่ตั้ง</p>
            <div class="mx-auto w-[700px] bg-[#2d2a49] border border-[#7976a0]">
                <?php if (empty($posts)): ?>
                    <p class="text-[#9e9aa0] text-xl text-center mt-[100px]">ไม่พบกระทู้ที่ตั้ง</p>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="border-b border-gray-700 py-4">
                            <a href="post.php?id=<?= $post['id'] ?>" class="text-[#dab27a] ml-4" target="_blank">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                            <div class="flex ml-4 mt-1 text-[#9d9ac0] text-[12px] gap-1">
                                <a href="profile.php?user_id=<?= htmlspecialchars($post['user_id']) ?>" class="hover:underline">
                                    สมาชิกหมายเลข <?= htmlspecialchars($user['username'] ?: $user['id']) ?>
                                </a>
                                <p>-</p>
                                <p class="ml-1"><?= $post['created_at'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- กระทู้ที่ตอบ -->
        <div class="bg-[#1f1d33] w-[700px] mx-auto mt-8">
            <p class="p-3 border border-b-0 border-[#7976a0] text-[#fbc02d]">กระทู้ที่ตอบ</p>
            <div class="mx-auto w-[700px] bg-[#2d2a49] border border-[#7976a0]">
                <?php if (empty($repliedPosts)): ?>
                    <p class="text-[#9e9aa0] text-xl text-center py-10">ยังไม่เคยแสดงความคิดเห็นในกระทู้ใด</p>
                <?php else: ?>
                    <?php foreach ($repliedPosts as $post): ?>
                        <div class="border-b border-gray-700 py-4">
                            <a href="post.php?id=<?= $post['id'] ?>" class="text-[#dab27a] ml-4" target="_blank">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                            <div class="flex ml-4 mt-1 text-[#9d9ac0] text-[12px] gap-1">
                                <p><?= $post['created_at'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- กระทู้ที่เคยอ่าน -->
        <div class="bg-[#1f1d33] w-[700px] mx-auto mt-8">
            <p class="p-3 border border-b-0 border-[#7976a0] text-[#fbc02d]">กระทู้ที่เคยอ่าน</p>
            <div class="mx-auto w-[700px] bg-[#2d2a49] border border-[#7976a0]">
                <?php if (empty($viewedPosts)): ?>
                    <p class="text-[#9e9aa0] text-xl text-center py-10">ยังไม่เคยอ่านกระทู้ใด</p>
                <?php else: ?>
                    <?php foreach ($viewedPosts as $post): ?>
                        <div class="border-b border-gray-700 py-4">
                            <a href="post.php?id=<?= $post['id'] ?>" class="text-[#dab27a] ml-4" target="_blank">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                            <div class="flex ml-4 mt-1 text-[#9d9ac0] text-[12px] gap-1">
                                <p><?= $post['created_at'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bg-[#1f1d33] w-[700px] mx-auto mt-8 content-section hidden" id="created">
        <p class="p-3 border border-b-0 border-[#7976a0] text-[#fbc02d]">กระทู้ที่ตั้ง</p>
        <div class="mx-auto w-[700px] bg-[#2d2a49] border border-[#7976a0]">
            <?php if (empty($posts)): ?>
                <p class="text-[#9e9aa0] text-xl text-center mt-[100px]">ไม่พบกระทู้ที่ตั้ง</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="border-b border-gray-700 py-4">
                        <a href="post.php?id=<?= $post['id'] ?>" class="text-[#dab27a] ml-4" target="_blank">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                        <div class="flex ml-4 mt-1 text-[#9d9ac0] text-[12px] gap-1">
                            <a href="profile.php?user_id=<?= htmlspecialchars($post['user_id']) ?>" class="hover:underline">
                                สมาชิกหมายเลข <?= htmlspecialchars($user['username'] ?: $user['id']) ?>
                            </a>
                            <p>-</p>
                            <p class="ml-1"><?= $post['created_at'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-[#1f1d33] w-[700px] mx-auto mt-8 content-section hidden" id="replied">
        <p class="p-3 border border-b-0 border-[#7976a0] text-[#fbc02d]">กระทู้ที่ตอบ</p>
        <div class="mx-auto w-[700px] bg-[#2d2a49] border border-[#7976a0]">
            <?php if (empty($repliedPosts)): ?>
                <p class="text-[#9e9aa0] text-xl text-center py-10">ยังไม่เคยแสดงความคิดเห็นในกระทู้ใด</p>
            <?php else: ?>
                <?php foreach ($repliedPosts as $post): ?>
                    <div class="border-b border-gray-700 py-4">
                        <a href="post.php?id=<?= $post['id'] ?>" class="text-[#dab27a] ml-4" target="_blank">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                        <div class="flex ml-4 mt-1 text-[#9d9ac0] text-[12px] gap-1">
                            <p><?= $post['created_at'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-[#1f1d33] w-[700px] mx-auto mt-8 content-section hidden" id="viewed">
        <p class="p-3 border border-b-0 border-[#7976a0] text-[#fbc02d]">กระทู้ที่เคยอ่าน</p>
        <div class="mx-auto w-[700px] bg-[#2d2a49] border border-[#7976a0]">
            <?php if (empty($viewedPosts)): ?>
                <p class="text-[#9e9aa0] text-xl text-center py-10">ยังไม่เคยอ่านกระทู้ใด</p>
            <?php else: ?>
                <?php foreach ($viewedPosts as $post): ?>
                    <div class="border-b border-gray-700 py-4">
                        <a href="post.php?id=<?= $post['id'] ?>" class="text-[#dab27a] ml-4" target="_blank">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                        <div class="flex ml-4 mt-1 text-[#9d9ac0] text-[12px] gap-1">
                            <p><?= $post['created_at'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal แก้ไขโปรไฟล์ -->
    <div id="editProfileModal" class="fixed inset-0 bg-black/60 hidden justify-center items-center z-50">
        <div class="bg-[#2d2a49] p-6 w-[400px] rounded shadow-lg relative">

            <h3 class="text-lg text-yellow-400 mb-4">แก้ไขโปรไฟล์</h3>

            <form action="profile_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                <label>ชื่อที่แสดง</label>
                <input type="text" name="fullname"
                    value="<?= htmlspecialchars($user['fullname']) ?>"
                    class="w-full p-2 text-white rounded bg-[#3c3963] mb-2">

                <label class="mt-2 block">รูปโปรไฟล์</label>
                <input type="file" name="avatar" class="w-full bg-[#3c3963] rounded p-2 mb-3">

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditProfile()"
                        class="px-3 py-1 bg-gray-500 rounded">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="px-3 py-1 bg-green-600 text-white rounded">
                        บันทึก
                    </button>
                </div>
            </form>

        </div>
    </div>


    <script>
        function openEditProfile() {
            document.getElementById('editProfileModal').classList.remove('hidden');
            document.getElementById('editProfileModal').classList.add('flex');
        }

        function closeEditProfile() {
            document.getElementById('editProfileModal').classList.add('hidden');
            document.getElementById('editProfileModal').classList.remove('flex');
        }
    </script>

    <script>
        const items = document.querySelectorAll('#menu .item');
        const sections = document.querySelectorAll('.content-section');

        // ตั้งค่าเริ่ม: ภาพรวม active
        items[0].classList.remove('text-[#979ab1]');
        items[0].classList.add('text-[#fbc02d]');

        items.forEach(item => {
            item.addEventListener('click', () => {

                // รีเซ็ตสีเมนูทั้งหมด
                items.forEach(i => {
                    i.classList.remove('text-[#fbc02d]');
                    i.classList.add('text-[#979ab1]');
                });

                // กดอันไหน → active อันนั้น
                item.classList.remove('text-[#979ab1]');
                item.classList.add('text-[#fbc02d]');

                // ซ่อนทุก content ก่อน
                sections.forEach(sec => sec.classList.add('hidden'));

                // แสดง content เป้าหมาย
                const target = item.getAttribute('data-target');
                document.getElementById(target).classList.remove('hidden');
            });
        });
    </script>


</body>

</html>