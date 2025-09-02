<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $content = $_POST['content']; // ข้อมูล HTML จาก editor
  // บันทึกลงฐานข้อมูลได้เลย
  // ตัวอย่าง PDO
  require 'connect.php';
  $stmt = $conn->prepare("INSERT INTO posts(content) VALUES(:content)");
  $stmt->execute(['content' => $content]);
  echo "บันทึกเรียบร้อย!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
  <title>newtopic</title>


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

  <div class="bg-[#38355c] p-1.5 border border-black">
    <div class="text-sm text-gray-300 flex justify-center w-full pr-[700px]">
      <div class="w-[11px] h-[20px] mr-4 pt-[2px]">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="text-gray-400" fill="currentColor">
          <path d="M0 188.6C0 84.4 86 0 192 0S384 84.4 384 188.6c0 119.3-120.2 262.3-170.4 316.8-11.8 12.8-31.5 12.8-43.3 0-50.2-54.5-170.4-197.5-170.4-316.8zM192 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128z" />
        </svg>
      </div>
      <a href="#" onclick="alert('ไม่สามารถกลับได้!')" class="mr-1 hover:text-white hover:underline">ตั้งกระทู้</a>»
      <a href="#" onclick="alert('ไม่สามารถกลับได้!')" class="ml-1 hover:text-white hover:underline">เลือกประเภทกระทู้</a>

      <a href="#" class="ml-1 hidden message" data-type="question">» ใส่รายละเอียดกระทู้คำถาม</a>
      <a href="#" class="ml-1 hidden message" data-type="chat">» ใส่รายละเอียดกระทู้สนทนา</a>
      <a href="#" class="ml-1 hidden message" data-type="poll">» ใส่รายละเอียดกระทู้โพล</a>
      <a href="#" class="ml-1 hidden message" data-type="review">» ใส่รายละเอียดกระทู้รีวิว</a>
      <a href="#" class="ml-1 hidden message" data-type="news">» ใส่รายละเอียดกระทู้ข่าว</a>
      <a href="#" class="ml-1 hidden message" data-type="shop">» ใส่รายละเอียดกระทู้ขายของ</a>

    </div>
  </div>

  <div class="flex justify-center">

    <div class="flex justify-center mt-[150px]" id="topic">
      <div class="bg-[#4c4973] p-2.5 w-[1045px] h-[570px] grid grid-cols-3 gap-2">

        <a href="javascript:void(0)" class="openTopic" data-type="question">
          <div class="bg-[#302e51] w-[329px] h-[267px] hover:bg-[#26223e]">
            <div class="flex justify-center items-center">
              <img class="mt-12" src="./asste/pantip/icon-que.png" alt="">
            </div>
            <div class="flex justify-center">
              <h3 class="text-[25px] font-bold text-[#ffe87a]">กระทู้คำถาม</h3>
            </div>
            <p class="text-gray-300 text-xs flex justify-center">ฉันมีคำถามหรือปัญหาที่ต้องการ</p>
            <p class="text-gray-300 text-xs flex justify-center">คำตอบหรือความช่วยเหลือจากเพื่อนๆ</p>
          </div>
        </a>
        <a href="javascript:void(0)" class="openTopic" data-type="chat">
          <div class="bg-[#302e51] w-[329px] h-[267px] hover:bg-[#26223e]">
            <div class="flex justify-center items-center">
              <img class="mt-12" src="./asste/pantip/icon-smilechat.png" alt="">
            </div>
            <div class="flex justify-center">
              <h3 class="text-[25px] font-bold text-[#ffe87a]">กระทู้สนทนา</h3>
            </div>
            <p class="text-gray-300 text-xs flex justify-center">ฉันมีเรื่องราวน่าสนใจที่อยากแบ่งปันและ</p>
            <p class="text-gray-300 text-xs flex justify-center">พูดคุยแลกเปลี่ยนความคิดเห็นกับเพื่อนๆ</p>
          </div>
        </a>
        <a href="javascript:void(0)" class="openTopic" data-type="poll">
          <div class="bg-[#302e51] w-[329px] h-[267px] hover:bg-[#26223e]">
            <div class="flex justify-center items-center">
              <img class="mt-12" src="./asste/pantip/icon-poll.png" alt="">
            </div>
            <div class="flex justify-center">
              <h3 class="text-[25px] font-bold text-[#ffe87a]">กระทู้โพล</h3>
            </div>
            <p class="text-gray-300 text-xs flex justify-center">ฉันอยากสำรวจความคิดเห็นจากเพื่อนๆ</p>
            <p class="text-gray-300 text-xs flex justify-center">โดยใช้แบบสอบถามที่แสดงผลเป็นกราฟ</p>
          </div>
        </a>
        <a href="javascript:void(0)" class="openTopic" data-type="review">
          <div class="bg-[#302e51] w-[329px] h-[267px] hover:bg-[#26223e]">
            <div class="flex justify-center items-center mb-2">
              <img class="mt-10" src="./asste/pantip/icon-review.png" alt="">
            </div>
            <div class="flex justify-center">
              <h3 class="text-[25px] font-bold text-[#ffe87a]">กระทู้รีวิว</h3>
            </div>
            <p class="text-gray-300 text-xs flex justify-center">ฉันใช้สินค้าหรือบริการบางอย่างมาและ</p>
            <p class="text-gray-300 text-xs flex justify-center">ต้องการแบ่งปันประสบการณ์กับเพื่อนๆ</p>
          </div>
        </a>
        <a href="javascript:void(0)" class="openTopic" data-type="news">
          <div class="bg-[#302e51] w-[329px] h-[267px] hover:bg-[#26223e]">
            <div class="flex justify-center items-center">
              <img class="mt-12" src="./asste/pantip/icon-news.png" alt="">
            </div>
            <div class="flex justify-center">
              <h3 class="text-[25px] font-bold text-[#ffe87a]">กระทู้ข่าว</h3>
            </div>
            <p class="text-gray-300 text-xs flex justify-center">ฉันมีข่าวที่น่าสนใจจากสื่อต่างๆ เช่น หนังสือพิมพ์</p>
            <p class="text-gray-300 text-xs flex justify-center">มาแบ่งปันเพื่อแลกเปลี่ยนความคิดเห็นกับเพื่อนๆ</p>
          </div>
        </a>
        <a href="javascript:void(0)" class="openTopic" data-type="shop">
          <div class="bg-[#302e51] w-[329px] h-[267px] hover:bg-[#26223e]">
            <div class="flex justify-center items-center">
              <img class="mt-12" src="./asste/pantip/icon-shop.png" alt="">
            </div>
            <div class="flex justify-center">
              <h3 class="text-[25px] font-bold text-[#ffe87a]">กระทู้ขายของ</h3>
            </div>
            <p class="text-gray-300 text-xs flex justify-center">ฉันมีสินค้าทั้งมือหนึ่งและมือสอง</p>
            <p class="text-gray-300 text-xs flex justify-center">ที่อยากเสนอขายให้กับเพื่อนๆ</p>
          </div>
        </a>
      </div>
    </div>

    <div id="newtopic" class="flex justify-center mt-[50px] hidden">
      <div>
        <div class="bg-[#193366] border border-[#33608a] p-4">
          <p class="text-gray-300 text-sm">1. ระบุคำถามของคุณ เช่น เว็บ Pantip.com ก่อตั้งขึ้นตั้งแต่เมื่อไหร่ ใครพอทราบบ้าง?</p>
          <input type="text" class="text-[#e5c700] text-xl p-0 placeholder:text-[#627575] placeholder:text-lg border border-[#5b79b4] bg-[#335087] w-full mb-2 mt-2" placeholder="หัวข้อคำถาม">
        </div>
        <div class="editor-container bg-[#193366] w-[1045px] h-[580px] shadow p-3.5 border border-[#33608a]">
          <div class="mb-3">
            <p class="text-gray-300 text-sm">2. เขียนรายละเอียดของคำถาม</p>
          </div>
          <div>
            <textarea name="content" id="editor"></textarea>
          </div>
          <!-- <div class="h-[120px]">
            <div class="p-3 bg-[#335087] border border-[#5b79b4] focus:outline-none focus:ring focus:ring-none w-full h-full">
            </div>
            <div class="editor-placeholder text-gray-400 p-4 "></div>
          </div> -->
        </div>
        <div class="bg-[#193366] p-4 border border-[#33608a] w-ful h-[550px]">
          <div class=" flex items-center space-x-20">
            <p class="text-gray-300 text-sm">3. เลือกแท็กที่เกี่ยวข้องกับกระทู้ โดย Auto Tag จะคาดเดา Tag ที่เกี่ยวข้องกับเนื้อหาในกระทู้นี้ กรุณาเลือก Tag ที่เกี่ยวข้องกับเนื้อหากระทู้ของท่านค่ะ</p>
            <a href="javascript:void(0)" class="text-[#e5c710] text-xs hover:text-[#d2b8ff] hover:underline pl-4">แท็กคืออะไร?</a>
          </div>
          <div class="mt-7">
            <img src="https://ptcdn.info/images/auto_tag2.png" class="w-[170px] h-[80px]">
          </div>
          <div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="mb-30">
    <div class="flex justify-center ml-[1005px] mt-3 -mb-4">
      <a href="" id="cancel" class="text-sm text-[#e6ba82] hover:text-[#d2b8ff] hover:underline hidden">ยกเลิก</a>
    </div>

    <div class="flex justify-center mr-[188px] hidden flex items-center space-x-5" id="topic_save">
      <a class="button letdo-butt p-0.5 pb-1 pt-1 border border-gray-500" href="javascript:void(0);" id="sendBtn">
        <span class="p-1 px-2 text-sm bg-gradient-to-b from-[#608e30] to-[#466e2c] hover:from-[#608e30] hover:to-[#668646]">
          <em class="text-white">ส่งกระทู้</em>
        </span>
      </a>
      <div class="items-center mr-[500px]">
        <p class="flex items-center space-x-2 text-sm text-gray-300">
          <span>ตั้งกระทู้โดย:</span>
          <img src="./asste/winter.jpg" class="h-[25px] w-[25px] rounded-3xl">
          <span>สมาชิกหมายเลข 9001433</span>
        </p>
      </div>
    </div>
  </div>

  <form id="hiddenForm" action="index.php" method="POST" style="display:none;">
    <textarea name="content" id="hiddenContent"></textarea>
  </form>

  <footer class="flex bottom-0 left-0 z-20 w-full p-4 bg-[#3c3963] border-t border-[#413e6b] shadow-sm md:flex md:items-center md:justify-between gap-x-4 md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
      <a href="" class="hover:underline text-[#8a86bf] hover:text-[#e5c710] dark:text-[#8a86bf]">BlogGang |
      </a>
      <a href="" class="hover:underline text-[#8a86bf] hover:text-[#e5c710] dark:text-[#8a86bf]">PantipMarket |
      </a>
      <a href="" class="hover:underline text-[#8a86bf] hover:text-[#e5c710] dark:text-[#8a86bf]">Pantown |
      </a>
      <a href="" class="hover:underline text-[#8a86bf] hover:text-[#e5c710] dark:text-[#8a86bf]">Maggang
      </a>
    </span>
    <ul class="flex flex-wrap items-center mt-3 text-sm font-medium sm:mt-0">
      <li>
        <a href="#" class="hover:underline me-4 md:me-6 text-[#8a86bf] hover:text-[#e5c710] dark:text-[#8a86bf]">ติดต่อทีมงานพันทิป</a>
      </li>
      <li>
        <a href="#" class="hover:underline me-4 md:me-6 text-[#8a86bf] hover:text-[#e5c710] dark:text-[#8a86bf]">ติดต่อลงโฆษณา</a>
      </li>
    </ul>
  </footer>


  <!-- โหลด SweetAlert2 (แจ้งเตือนสวยๆ) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // ดักตอนกดหัวข้อ
    document.querySelectorAll('.openTopic').forEach(function(item) {
      item.addEventListener('click', function() {
        const type = this.dataset.type; // ดูว่ากดหัวข้ออะไร
        localStorage.setItem('openNewTopic', type); // เก็บ type ไว้แล้ว reload
        location.reload();
      });
    });

    // ตอนโหลดหน้า เช็คว่าต้องเปิด newtopic หรือไม่
    document.addEventListener('DOMContentLoaded', function() {
      const type = localStorage.getItem('openNewTopic');
      if (type) {
        // เปิด newtopic
        document.getElementById('topic').classList.add('hidden');
        document.getElementById('newtopic').classList.remove('hidden');
        document.getElementById('cancel').classList.remove('hidden');
        document.getElementById('topic_save').classList.remove('hidden');

        // ซ่อนข้อความทั้งหมดก่อน
        document.querySelectorAll('.message').forEach(msg => msg.classList.add('hidden'));
        // แสดงเฉพาะข้อความที่ตรง type
        const activeMsg = document.querySelector(`.message[data-type="${type}"]`);
        if (activeMsg) {
          activeMsg.classList.remove('hidden');
        }
      }
    });

    // ปุ่มยกเลิก
    document.getElementById('cancel').addEventListener('click', function(e) {
      e.preventDefault();
      localStorage.removeItem('openNewTopic');
      location.reload();
    });
  </script>

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