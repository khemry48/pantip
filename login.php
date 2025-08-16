<?php
require 'connect.php';
session_start();

// ถ้ามีการล็อกอินแล้ว (มี user_id อยู่ใน session)
if (isset($_SESSION['user_id'])) {
    // ส่งไปหน้าหลักแทน
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];

    // ค้นหาผู้ใช้
    $sql = "SELECT * FROM users WHERE fullname = :fullname AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':password', $password); // ถ้าใช้ password hash ต้องเปลี่ยนวิธีตรวจสอบ
    $stmt->execute();

    if ($stmt->execute()) {
        $success = true; // ✅ บันทึกสำเร็จ
    } else {
        $success = false;
    }
    
    if ($user) {
        // ผู้ใช้มีอยู่จริง
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['fullname']; // หรือ username ถ้ามี
        header("Location: index.php"); // ส่งไปหน้า index
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>เข้าสู่ระบบ - pantip</title>
</head>

<body>

    <nav class="bg-[#2d2a49] border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center mx-auto p-2">
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
                <div class="relative mt-3 md:hidden">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
                </div>
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-[#2d2a49] dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="#" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-white md:p-0 md:dark:text-white" aria-current="page">หน้าแรก</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-white md:p-0 md:dark:hover:text-white dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">คอมมูนิตี้</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">กิจกรรม</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">แลกพอยต์</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">อื่นๆ</a>
                    </li>
                </ul>
            </div>
            <!-- search และเมนูด้านขวา -->
            <div class="flex items-center gap-4 md:order-2 mx-auto">
                <!-- ปุ่มค้นหา -->
                <div class="relative hidden md:block">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search..."
                        class="block w-full p-1 ps-10 text-sm text-gray-200 border border-gray-300  bg-[#44416f]" />
                </div>

                <!-- เมนู ตั้งกระทู้ / เข้าสู่ระบบ -->
                <ul class="flex items-center gap-4 ml-4">
                    <li><a class="text-white" href="#">ตั้งกระทู้</a></li>
                    <li><a class="text-white" href="#">เข้าสู่ระบบ/สมัครสมาชิก</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="flex flex-col h-screen bg-[#3c3963]">
        <div class="flex flex-col items-center mt-[40px]">
            <div class="text-2xl text-white ">ลงชื่อเข้าใช้</div>
            <div class="text-gray-300 mt-[30px]">ระบบจะจดจำข้อมูลการเข้าสู่ระบบของคุณแบบ "การลงชื่อเข้าใช้ถาวร"</div>
        </div>
        <div class="flex grid grid-cols-3 gap-2 mt-[50px] items-center">
            <form method="POST">
                <input type="text" placeholder="ชื่อผู้ใช้ / อีเมล" name="fullname" required
                    class="block w-full p-2 mt-4 mx-auto max-w-md text-md text-gray-400 border border-gray-500 rounded-sm bg-[#37355b] focus:ring-2 focus:ring-[#7459c8] focus:border-[#7459c8] dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-[#7459c8] dark:focus:border-[#7459c8]" />
                <input type="password" placeholder="รหัสผ่าน" name="password" required
                    class="block w-full p-2 mt-4 mx-auto max-w-md text-md text-gray-400 border border-gray-500 rounded-sm bg-[#37355b] focus:ring-2 focus:ring-[#7459c8] focus:border-[#7459c8] dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-[#7459c8] dark:focus:border-[#7459c8]" />
                <div class="flex justify-end mx-auto max-w-md">
                    <a class="flex mt-4 text-[#b39ddb] hover:underline underline-gray-200 hover:text-[#d1c4e9]" href="#">ลืมรหัสผ่าน</a>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-[#7459c8] mt-4 w-[450px] p-2 text-white mx-auto rounded-sm">เข้าสู่ระบบ</button>
                </div>
            </form>
            <div class="text-gray-300 mt-[10px] text-center">
                หรือ
            </div>
            <div>
                <div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-gray-300">
                                    <span>Facebook</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-gray-300">
                                    <span>Goole</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-gray-300">
                                    <span>Line</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-gray-300">
                                    <span>Apple</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-white mt-[50px]">
                    ยังไม่เป็นสมาชิก?<a href="register.php" class="text-[#b39ddb] ml-2 hover:underline underline-gray-200 hover:text-[#d1c4e9]">สมัครสมาชิกด้วยอีเมล</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($success) && $success === true): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("เข้าสู่ระบบสำเร็จ!", "ยินดีต้อนรับสู่ pantip", "success").then(() => {
                    window.location.href = "index.php"; // ✅ เปลี่ยนไปหน้าอื่นได้
                });
            });
        </script>
    <?php endif; ?>

</body>

</html>