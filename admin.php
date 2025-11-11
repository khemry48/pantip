<?php
session_start();
// ✅ ป้องกัน cache หน้าเก่า
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// ปรับค่าตามจริงของคุณ
$host = "localhost";
$dbname = "pantip";
$dbuser = "root";
$dbpass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB connect error: " . $e->getMessage());
}

// ดึงข้อมูลผู้ใช้
$stmt = $pdo->query("SELECT id, username, fullname, password, email FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ฟังก์ชันเช็กว่า password น่าจะเป็น hash หรือไม่ (bcrypt /argon /ทั่วไป)
function looks_like_hash($pwd)
{
    if ($pwd === null || $pwd === '') return false;
    // bcrypt typical starts with $2y$ / $2a$ and length ~60
    if (preg_match('/^\$2[ayb]\$.{56}$/', $pwd)) return true;
    // argon2 starts with $argon2i$ or $argon2id$
    if (strpos($pwd, '$argon2') === 0) return true;
    // ถ้าความยาวยาวมาก (เช่น >60) ให้ถือว่าเป็น hash
    if (strlen($pwd) > 60) return true;
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Admin</title>
</head>

<body class="bg-white">
    <nav class="bg-gray-200 shadow-lg w-full top-0 left-0">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="./asset/aespa.jpg" class="h-10 rounded-3xl" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Admin</span>
            </a>
        </div>
    </nav>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-3/4 mx-auto mt-24 mb-10 bg-gray-200 p-4">
        <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
            <div>
                <!-- Dropdown menu -->
                <div id="dropdownRadio" class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(522.5px, 3847.5px, 0px);">
                    <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioButton">
                        <li>
                            <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-example-1" type="radio" value="" name="filter-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-example-1" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">Last day</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input checked="" id="filter-radio-example-2" type="radio" value="" name="filter-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-example-2" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">Last 7 days</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-example-3" type="radio" value="" name="filter-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-example-3" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">Last 30 days</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-example-4" type="radio" value="" name="filter-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-example-4" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">Last month</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-example-5" type="radio" value="" name="filter-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-example-5" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">Last year</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        users
                    </th>
                    <th scope="col" class="px-6 py-3">
                        fullname
                    </th>
                    <th scope="col" class="px-6 py-3">
                        password
                    </th>
                    <th scope="col" class="px-6 py-3">
                        email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)) : ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">ไม่มีข้อมูลผู้ใช้</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user):
                        $is_hash = looks_like_hash($user['password']);
                    ?>
                        <tr class="border-t">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?= htmlspecialchars($user['username']) ?>
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?= htmlspecialchars($user['fullname']) ?>
                            </td>

                            <td class="px-6 py-4">
                                <?php if ($is_hash): ?>
                                    <em>hashed (ไม่สามารถแสดง plaintext)</em>
                                <?php else: ?>
                                    <!-- ถ้ารหัสเป็น plaintext: ซ่อนเป็นมาสก์ก่อน และมีปุ่ม Show -->
                                    <span class="pw-mask" id="mask-<?= $user['id'] ?>">
                                        <?= str_repeat('•', max(6, min(12, strlen($user['password'])))) ?>
                                    </span>
                                    <span class="pw-plain hidden" id="plain-<?= $user['id'] ?>">
                                        <?= htmlspecialchars($user['password']) ?>
                                    </span>

                                    <button
                                        class="ml-3 px-2 py-1 border rounded text-sm show-btn"
                                        data-id="<?= $user['id'] ?>">
                                        Show
                                    </button>

                                    <button
                                        class="ml-2 px-2 py-1 border rounded text-sm hide-btn hidden"
                                        data-id="<?= $user['id'] ?>">
                                        Hide
                                    </button>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>

                            <td class="px-6 py-4">
                                <a href="edit.php?id=<?= urlencode($user['id']) ?>" class="text-blue-600 hover:underline mr-3">Edit</a>

                                <?php if ($is_hash): ?>
                                    <!-- ให้รีเซ็ตรหัสแทนการเปิด plaintext -->
                                    <a href="reset_password.php?id=<?= urlencode($user['id']) ?>" class="text-orange-600 hover:underline"
                                        onclick="return confirm('จะส่งคำสั่งรีเซ็ตรหัสให้ผู้ใช้ (หรือเซ็ตรหัสใหม่) แน่ใจหรือไม่?');">
                                        Reset Password
                                    </a>
                                <?php else: ?>
                                    <a href="delete.php?id=<?= urlencode($user['id']) ?>" class="text-red-600 hover:underline"
                                        onclick="return confirm('คุณแน่ใจว่าจะลบผู้ใช้คนนี้หรือไม่?');">
                                        Delete
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Event delegation for show/hide buttons
        document.addEventListener('click', function(e) {
            // Show button
            if (e.target.matches('.show-btn')) {
                const id = e.target.dataset.id;
                // confirm before revealing
                // if (!confirm('คุณแน่ใจว่าจะดูรหัสผ่านของผู้ใช้คนนี้? การกระทำนี้อาจเป็นความเสี่ยง')) return;

                // swap elements
                const mask = document.getElementById('mask-' + id);
                const plain = document.getElementById('plain-' + id);
                const hideBtn = document.querySelector('.hide-btn[data-id="' + id + '"]');

                if (mask && plain) {
                    mask.classList.add('hidden');
                    plain.classList.remove('hidden');
                    e.target.classList.add('hidden'); // hide show btn
                    if (hideBtn) hideBtn.classList.remove('hidden');
                }
            }

            // Hide button
            if (e.target.matches('.hide-btn')) {
                const id = e.target.dataset.id;
                const mask = document.getElementById('mask-' + id);
                const plain = document.getElementById('plain-' + id);
                const showBtn = document.querySelector('.show-btn[data-id="' + id + '"]');

                if (mask && plain) {
                    plain.classList.add('hidden');
                    mask.classList.remove('hidden');
                    e.target.classList.add('hidden'); // hide hide btn
                    if (showBtn) showBtn.classList.remove('hidden');
                }
            }
        });
    </script>

</body>

</html>