<?php
require 'connect.php';
session_start();

// ถ้ามีการล็อกอินแล้ว (มี user_id อยู่ใน session)
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO users (fullname, password, email, phone)
            VALUES (:fullname, :password, :email, :phone)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);

    if ($stmt->execute()) {
        $success = true;
        $user_id = $conn->lastInsertId();

        // สมัครเสร็จให้ล็อกอินอัตโนมัติ
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $fullname;
    } else {
        $success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>register</title>
</head>

<body class="bg-[#3c3963]">

    <div>
        <div class="text-3xl text-white text-center mt-10">
            Pantip Registration
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto p-4 mt-10">
        <form class="mx-auto bg-[#2d2a49] p-6 rounded-lg shadow-md h-full w-[600px]" method="POST">
            <div class="mb-5">
                <label for="email" class="block mb-5 text-sm font-medium text-white dark:text-white">Your email</label>
                <input type="email" name="email" class="bg-[#37355b] border border-[#6d6a8a] text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
            </div>
            <div class="mb-5">
                <label for="fullname" class="block mb-2 text-sm font-medium text-white dark:text-white">Your fullname</label>
                <input type="fullname" name="fullname" class="bg-[#37355b] border border-[#6d6a8a] text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="mb-5">
                <label for="phone" class="block mb-2 text-sm font-medium text-white dark:text-white">Phone number</label>
                <input type="phone" name="phone" class="bg-[#37355b] border border-[#6d6a8a] text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-white">Your password</label>
                <input type="password" id="password" name="password"
                    class="bg-[#37355b] border border-[#6d6a8a] text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>

            <div class="mb-5">
                <label for="confirm_password" class="block mb-2 text-sm font-medium text-white">Confirm password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    class="bg-[#37355b] border border-[#6d6a8a] text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="flex justify-center mt-12">
                <button type="submit" class="text-white bg-[#7459c8] hover:bg-[#664bba] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-[255px] py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("confirm_password");

            form.addEventListener("submit", function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault(); // หยุดการส่งฟอร์ม
                    Swal.fire({
                        icon: 'error',
                        title: 'รหัสผ่านไม่ตรงกัน',
                        text: 'กรุณากรอกรหัสผ่านให้ตรงกัน'
                    });
                }
            });

            <?php if (!empty($success) && $success === true): ?>
                Swal.fire({
                    title: "สมัครสำเร็จ!",
                    text: "บัญชีของคุณถูกสร้างเรียบร้อยแล้ว",
                    icon: "success",
                    confirmButtonText: "ตกลง"
                }).then(() => {
                    window.location.href = "index.php";
                });
            <?php endif; ?>
        });
    </script>



</body>

</html>