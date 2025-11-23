<?php
session_start();
require 'connect.php';

if (!isset($_GET['id'])) {
    die("ไม่พบโพสต์ที่ต้องการ");
}

$post_id = $_GET['id'];

// ---------------------------
// ดึงข้อมูลโพสต์ + ผู้เขียน
// ---------------------------
$stmt = $pdo->prepare("
    SELECT posts.*, users.username
    FROM posts
    INNER JOIN users ON posts.user_id = users.id
    WHERE posts.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// ถ้าไม่เจอโพสต์
if (!$post) {
    die("โพสต์นี้ไม่มีอยู่ในระบบ");
}

// ---------------------------
// ดึงความคิดเห็นทั้งหมดของโพสต์นี้
// ---------------------------
$stmt2 = $pdo->prepare("
    SELECT comments.*, users.username
    FROM comments
    INNER JOIN users ON comments.user_id = users.id
    WHERE comments.post_id = ?
    ORDER BY comments.created_at ASC
");
$stmt2->execute([$post_id]);
$comments = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// ป้องกัน error ถ้าไม่มีความคิดเห็นเลย
if (!$comments) {
    $comments = [];
}
$commentCount = count($comments);

$currentUserId = $_SESSION['user_id'];

$stmtUser = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmtUser->execute([$currentUserId]);
$currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
</head>

<body class="bg-[#3c3963] text-gray-200 w-full h-full">
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

    <div class="relative w-full h-[200px] mt-0 fixed top-0 left-0 mt-[52px]"
        title="น้อมรำลึกในพระมหากรุณาธิคุณตราบนิจนิรันดร์ สมเด็จพระนางเจ้าสิริกิติ์ พระบรมราชินีนาถ พระบรมราชชนนีพันปีหลวง"
        style="background:url(https://ptcdn.info/doodle/2025/68fc1835caac0a3a4b2f8e34_xk96e10awt.png), url(https://ptcdn.info/doodle/2025/68fc1835caac0a3a4b2f8e34_mx8epq41h7.png);background-size:auto, cover;background-position:top, bottom;background-repeat:no-repeat, repeat">
    </div>

    <div class="bg-[#193366] max-w-[950px] mx-auto p-6 mt-[50px] border border-[#8e8ba7] shadow-lg mb-10">

        <h1 class="text-2xl text-[#ffcd05] mb-4">
            <?= htmlspecialchars($post['title']) ?>
        </h1>

        <?php if (!empty($post['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($post['image']) ?>" class="rounded mb-4 max-w-full">
        <?php endif; ?>

        <div class="text-[#c8c3d4] leading-relaxed">
            <?= nl2br(strip_tags($post['content'], '<p><br><b><i><ul><li><strong><em>')) ?>
        </div>

        <p class="text-sm text-[#909db2] mt-4">
            <a href="profile.php?user_id=<?= $post['user_id'] ?>" class="text-[#909db2] hover:text-white hover:underline">
                สมาชิกหมายเลข <?= htmlspecialchars($post['username']) ?>
            </a>

            • <?= $post['created_at'] ?>
        </p>

    </div>

    <div class="flex items-center my-4">
        <div class="flex-grow border-t border-purple-300/40"></div>
        <span class="px-3 text-purple-100 text-sm"><?= $commentCount ?> ความคิดเห็น</span>
        <div class="flex-grow border-t border-purple-300/40"></div>
    </div>

    <div class="max-w-[940px] mx-auto h-full mt-10 mb-10">
        <?php $count = 1; ?>
        <?php foreach ($comments as $comment): ?>
            <div class="bg-[#2d2a49] px-11 py-5 mb-4 rounded border border-[#8e8ba7] break-words">
                <p class="text-xs text-[#736d6b] hover:text-[#d3c69a] mb-1">
                    ความคิดเห็นที่ <?= $count ?>
                </p>
                <p class="text-[#c0c0c0]">
                    <?= strip_tags($comment['content'], '<p><br><b><strong><i><u><span>') ?>
                </p>
                <a href="profile.php?user_id=<?= $comment['user_id'] ?>"
                    class="text-[#909db2] hover:text-white hover:underline text-sm">
                    สมาชิกหมายเลข <?= htmlspecialchars($comment['username']) ?>
                </a>
                <p class="text-[#909db2] text-sm">
                    <?= $comment['created_at'] ?>
                </p>
            </div>
            <?php $count++; ?>
        <?php endforeach; ?>
    </div>


    <div class="flex items-center my-4">
        <div class="flex-grow border-t border-purple-300/40"></div>
        <span class="px-3 text-purple-100 text-sm">แสดงความคิดเห็น</span>
        <div class="flex-grow border-t border-purple-300/40"></div>
    </div>

    <form action="comment_save.php" method="POST">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

        <div class="flex justify-center">
            <div class="editor-container bg-[#093a43] w-[1045px] h-[380px] shadow p-3.5 border border-[#33608a] text-black">

                <textarea name="content" id="editor" class="w-full h-[250px] p-2"></textarea>

                <p class="text-[12px] text-[#a09dcc] mt-2">* พิมพ์ข้อความได้ไม่เกิน 10,000 ตัวอักษร</p>

                <div class="flex mr-[220px] items-center space-x-5 mt-2">
                    <button type="submit" class="button letdo-butt p-0.5 pb-1 pt-1 border border-gray-500">
                        <span class="p-1 px-2 text-sm bg-gradient-to-b from-[#608e30] to-[#466e2c] hover:from-[#608e30] hover:to-[#668646]">
                            <em class="text-white">ส่งข้อความ</em>
                        </span>
                    </button>
                    <div class="flex items-center mr-[500px]">
                        <a href="profile.php?user_id=<?= $currentUserId ?>">
                            <p class="flex items-center space-x-2 text-sm text-[#90a8ae] hover:text-white hover:underline">
                                <img src="./asset/winter.jpg" class="h-[25px] w-[25px] rounded-3xl">
                                <span>สมาชิกหมายเลข <?= htmlspecialchars($currentUser['username']) ?></span>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>



    <script>
        let editorInstance;

        ClassicEditor.create(document.querySelector('#editor'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

        document.getElementById('sendBtn').addEventListener('click', function() {
            // ดึงค่าจาก CKEditor
            const content = editorInstance.getData();

            // แจ้งเตือน SweetAlert
            Swal.fire({
                title: 'ยืนยันส่งกระทู้?',
                text: "คุณต้องการส่งเนื้อหานี้หรือไม่",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#43A72A',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ส่งเลย!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ใส่ค่าลงฟอร์มซ่อนแล้ว submit
                    document.getElementById('hiddenContent').value = content;
                    document.getElementById('hiddenForm').submit();
                }
            });
        });
    </script>
</body>

</html>