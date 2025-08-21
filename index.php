<?php
// เรียกไฟล์ connect.php มาใช้งาน
require 'connect.php';

// ดึงข้อมูลทั้งหมดจากตาราง users
$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>pantip</title>
</head>

<body class="bg-[#3c3963]">

    <nav class="bg-[#2d2a49] border-gray-200 dark:bg-gray-900">
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
                                <img class="w-[35px] h-[35px] rounded-3xl mt-1" src="./asste/winter.jpg" alt="">
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
    <div class="mt-[160px] m-10 border-[0.1rem] border-gray-300 text-white">
        <div class="bg-[#1F1D33] p-3">
            <P class="text-[#FBC02D]">Pantip Realtime</P>
            <P class="text-[#9895A8] text-sm">กระทู้ที่มีคนเปิดอ่านมากในขณะนี้ อัปเดตทุกนาที</P>
        </div>
        <div class="grid grid-cols-2">
            <div class="flex p-4 gap-2 border border-gray-500">
                <img src="https://f.ptcdn.info/381/088/000/mc4gl0rdi24o17X474L-o.png" class="w-20 h-20" alt="">
                <div class="flex flex-col justify-between">
                    <p class="text-[#FBC02D] text-lg">มัดรวมบริการลงโฆษณา Pantip.com แบนเนอร์ รีวิว กิจกรรม ตอบโจทย์ลูกค้า ในงบประมาณที่คุ้มค่า</p>
                    <div class="flex justify-between">
                        <p>สมาชิกหมายเลข 1091554 11ชั่วโมง</p>
                        <div class="flex gap-2">
                            <p><i class="fa-regular fa-comment"></i> 20</p>
                            <p><i class="fa-regular fa-square-plus"></i> 0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex p-4 gap-2 border border-gray-500">
                <div class="flex flex-col justify-between">
                    <p class="text-[#FBC02D] text-lg">หัวเจาะอุโมงค์ใต้ดิน หากเจาะไปเจอของแข็งๆขนาดใหญ่ใต้ดิน เช่นปืนใหญ่ทองเหลืองโบราณที่อยู่ใต้ดิน จะเกิดอะไรขึ้น</p>
                    <div class="flex justify-between">
                        <p>สมาชิกหมายเลข 1091554 11ชั่วโมง</p>

                        <div class="flex gap-2">
                            <p><i class="fa-regular fa-comment"></i> 20</p>
                            <p><i class="fa-regular fa-square-plus"></i> 0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex p-4 gap-2 border border-gray-500">
                <img src="https://f.ptcdn.info/897/088/000/meavvkkvh6sXQ3pb5s6-o.jpg" class="w-20 h-20" alt="">
                <div class="flex flex-col justify-between">
                    <p class="text-[#FBC02D] text-lg">คุ้มค่าคุ้มราคา... สื่อนอกเผย “สิทธิประโยชน์” ที่ไทยจะได้รับชุดใหญ่ จากดีล “กริพเพน” กับสวีเดน</p>
                    <div class="flex justify-between">
                        <p>สมาชิกหมายเลข 1091554 11ชั่วโมง</p>

                        <div class="flex gap-2">
                            <p><i class="fa-regular fa-comment"></i> 20</p>
                            <p><i class="fa-regular fa-square-plus"></i> 0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex p-4 gap-2 border border-gray-500">
                <img src="https://f.ptcdn.info/681/087/000/m9g3dsptiCCWQFxf445-o.png" class="w-20 h-20" alt="">
                <div class="flex flex-col justify-between">
                    <p class="text-[#FBC02D] text-lg">[รีวิว] ฮาลาบาลา ป่าจิตหลุด - งานจิตหลุดตามสไตล์เอกสิทธิ์ ที่ดันหลุดเรื่องบทจนมึนแทบจิตหลุด</p>
                    <div class="flex justify-between">
                        <p>สมาชิกหมายเลข 1091554 11ชั่วโมง</p>

                        <div class="flex gap-2">
                            <p><i class="fa-regular fa-comment"></i> 20</p>
                            <p><i class="fa-regular fa-square-plus"></i> 0</p>
                        </div>
                    </div>
                </div>
            </div>
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