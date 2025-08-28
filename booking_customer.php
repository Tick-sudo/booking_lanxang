<?php
session_start();
include 'db.php';

$adult = isset($_GET['adult']) ? intval($_GET['adult']) : ($_SESSION['adult'] ?? 1);
$child = isset($_GET['child']) ? intval($_GET['child']) : ($_SESSION['child'] ?? 0);
$infant = isset($_GET['infant']) ? intval($_GET['infant']) : ($_SESSION['infant'] ?? 0);
$depart_time = $_GET['depart_time'] ?? '';
$boarding_time = $_GET['boarding_time'] ?? '';
// ดึงค่าจาก selected_outbound หรือ selected_return มาเก็บเป็น id_plane
$id_plane = $_GET['selected_outbound'] ?? $_GET['selected_return'] ?? '';

$_SESSION['adult'] = $adult;
$_SESSION['child'] = $child;
$_SESSION['infant'] = $infant;

$total = $adult + $child + $infant;

// Function สำหรับสร้างฟอร์มผู้โดยสาร



function passengerForm($num, $type) {
  $prefix = "p{$num}_";
  $html = "<div class='passenger-block'>";
  $html .= "<h3>ຜູ້ໂດຍສານຄົນທີ {$num} ({$type})</h3>";
  $html .= "<input type='hidden' name='{$prefix}type' value='{$type}'>";

  // ชื่อ - นามสกุล
  $html .= "<div class='row'>";
  $html .= "<div class='col-50'><label>ຊື່ <span class='required'>*</span></label>
            <input type='text' name='{$prefix}name' required maxlength='50' 
            oninput=\"this.value=this.value.replace(/[^a-zA-Z\u0E80-\u0EFF ]/g,'')\"></div>";
  $html .= "<div class='col-50'><label>ນາມສະກຸນ <span class='required'>*</span></label>
            <input type='text' name='{$prefix}lname' required maxlength='50'
            oninput=\"this.value=this.value.replace(/[^a-zA-Z\u0E80-\u0EFF ]/g,'')\"></div>";
  $html .= "</div>";

  // เพศ - วันเกิด
  $html .= "<div class='row'>";
  $html .= "<div class='col-50'><label>ເພດ <span class='required'>*</span></label>
            <select name='{$prefix}gender' required>
              <option value='Male'>ຊາຍ</option>
              <option value='Female'>ຍິງ</option>
            </select></div>";
  $html .= "<div class='col-50'><label>ວັນ/ເດືອນ/ປີເກີດ <span class='required'>*</span></label>
            <input type='date' name='{$prefix}dob' required></div>";
  $html .= "</div>";

  // สัญชาติ - ประเทศ
  $html .= "<div class='row'>";
  $html .= "<div class='col-50'><label>ສັນຊາດ<span class='required'>*</span></label>
            <input type='text' name='{$prefix}nationality' maxlength='50'
            oninput=\"this.value=this.value.replace(/[^a-zA-Z\u0E80-\u0EFF ]/g,'')\"></div>";
  $html .= "<div class='col-50'><label>ປະເທດ<span class='required'>*</span></label>
            <input type='text' name='{$prefix}country' maxlength='70'
            oninput=\"this.value=this.value.replace(/[^a-zA-Z\u0E80-\u0EFF ]/g,'')\"></div>";
  $html .= "</div>";

  // ที่อยู่ - Passport
  $html .= "<div class='row'>";
  $html .= "<div class='col-50'><label>ບ້ານ/ເມືອງ/ແຂວງ<span class='required'>*</span></label>
            <input type='text' name='{$prefix}address' maxlength='150'></div>";
  $html .= "<div class='col-50'><label>ເລກໜັງສືເດີນທາງ<span class='required'>*</span></label>
            <input type='text' name='{$prefix}passport' maxlength='30'
            oninput=\"this.value=this.value.replace(/[^a-zA-Z0-9]/g,'')\"></div>";
  $html .= "</div>";

  // ID Card - ID Home
  $html .= "<div class='row'>";
  $html .= "<div class='col-50'><label>ເລກບັດປະຈຳຕົວ<span class='required'>*</span></label>
            <input type='text' name='{$prefix}idcard' maxlength='30'
            oninput=\"this.value=this.value.replace(/[^0-9\-]/g,'')\"></div>";
  $html .= "<div class='col-50'><label>ເລກສຳມະໂນຄົວ<span class='required'>*</span></label>
            <input type='text' name='{$prefix}idhome' maxlength='20'
            oninput=\"this.value=this.value.replace(/[^0-9]/g,'')\"></div>";
  $html .= "</div>";

  // Email - Phone
  $html .= "<div class='row'>";
  $html .= "<div class='col-50'><label>ອີເມວ <span class='required'>*</span></label>
            <input type='email' name='{$prefix}email' required maxlength='30'></div>";
  $html .= "<div class='col-50'><label>ເບີໂທລະສັບ <span class='required'>*</span></label>
            <input type='text' name='{$prefix}tel' required maxlength='20'
            oninput=\"this.value=this.value.replace(/[^0-9+]/g,'')\"></div>";
  $html .= "</div>";

  $html .= "</div>"; // ปิด passenger-block
  return $html;
}

?>


<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ປ້ອນຂໍ້ມູນຜູ້ໂດຍສານ - LANXANG AIRWAY</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">

<style>
  /* === CSS ของคุณ === */
  body {
    margin: 0;
    font-family: 'Noto Sans Lao', sans-serif;
    background: url('images/fn1.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #002d74;
  }
  .container {
    max-width: 900px;
    margin: 80px auto 60px auto;
    background: rgba(255 255 255 / 0.95);
    padding: 35px 40px 50px 40px;
    border-radius: 12px;
    box-shadow: 0 6px 30px rgb(0 45 116 / 0.4);
  }
  h2 {
    text-align: center;
    font-weight: 800;
    font-size: 28px;
    color: #002d74;
    margin-bottom: 30px;
    letter-spacing: 1.3px;
  }
   input{
    box-sizing: border-box;
    width: 100%;
   }
  .passenger-block {
    margin-bottom: 40px;
    padding: 20px 25px;
    border-radius: 15px;
    background-color: #f9fbfd;
    box-shadow: 0 6px 18px rgb(0 45 116 / 0.1);
  }
  .passenger-block h3 {
    color: #d21e1e;
    margin-bottom: 20px;
    font-weight: 700;
    border-bottom: 2px solid #d21e1e;
    padding-bottom: 8px;
  }
  form .row {
    display: flex;
    gap: 50px;
    flex-wrap: wrap;
  }
  form .col-50 {
    flex: 1 1 calc(50% - 50px);
    min-width: 220px;
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
  }
  label {
    font-weight: 700;
    margin-bottom: 6px;
    color: #002d74;
    max-width: 70%;     
  }
  .required {
    color: #d21e1e;
  }
  input[type="text"],
  input[type="date"],
  input[type="email"],
  select {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1.5px solid #a0a9c6;
    background-color: rgba(255, 255, 255, 0.95);
    font-size: 16px;
    color: #002d74;
    box-shadow: inset 0 1px 3px rgba(160, 169, 198, 0.3);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }
  input[type="text"]:focus,
  input[type="date"]:focus,
  input[type="email"]:focus,
  select:focus {
    border-color: #ffdd00;
    box-shadow: 0 0 8px rgba(255, 221, 0, 0.7);
    outline: none;
  }
  button {
    background-color: #004080;
    color: white;
    padding: 14px 45px;
    border: none;
    border-radius: 12px;
    font-size: 19px;
    cursor: pointer;
    display: block;
    margin: 35px auto 0 auto;
    font-weight: 700;
    box-shadow: 0 5px 14px rgba(0, 64, 128, 0.4);
    transition: background-color 0.3s ease, box-shadow 0.3s ease, color 0.3s ease;
    outline: none;
  }
  button:hover,
  button:focus,
  button:active {
    background-color: #ffdd00;
    color: #004080;
    box-shadow: 0 0 14px #ffdd00;
    outline: none;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .container {
      margin: 40px 15px 30px 15px;
      padding: 30px 20px 30px 20px;
    }
    form .row {
      flex-direction: column;
    }
    form .col-50 {
      flex: 1 1 100%;
      min-width: auto;
    }
  }
</style>

<script>
function validateForm() {
  const total = <?= $total ?>;
  const today = new Date().toISOString().split('T')[0]; // yyyy-mm-dd
  
  for(let i=1; i<=total; i++) {
    const prefix = `p${i}_`;

    const name = document.getElementsByName(prefix + "name")[0].value.trim();
    const lname = document.getElementsByName(prefix + "lname")[0].value.trim();
    const dob = document.getElementsByName(prefix + "dob")[0].value.trim();
    const email = document.getElementsByName(prefix + "email")[0].value.trim();
    const tel = document.getElementsByName(prefix + "tel")[0].value.trim();

    // เช็คชื่อ
    if(!name) {
      alert(`ກະລຸນາປ້ອນຊື່ ຜູ້ໂດຍສານຄົນທີ ${i}`);
      return false;
    }
    // เช็คนามสกุล
    if(!lname) {
      alert(`ກະລຸນາປ້ອນນາມສະກຸນ ຜູ້ໂດຍສານຄົນທີ ${i}`);
      return false;
    }
    // เช็ควันเกิด
    if(!dob) {
      alert(`ກະລຸນາເລືອກວັນເກີດ ຜູ້ໂດຍສານຄົນທີ ${i}`);
      return false;
    } else if(dob >= today) {
      alert(`ວັນເກີດຂອງຜູ້ໂດຍສານຄົນທີ ${i} ຕ້ອງນ້ອຍກວ່າວັນປັດຈຸບັນ`);
      return false;
    }
    // เช็คอีเมลแบบง่าย
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!email) {
      alert(`ກະລຸນາປ້ອນອີເມວ ຜູ້ໂດຍສານຄົນທີ ${i}`);
      return false;
    } else if(!emailPattern.test(email)) {
      alert(`ອີເມວ ຜູ້ໂດຍສານຄົນທີ ${i} ບໍ່ຖືກຕ້ອງ`);
      return false;
    }
    // เช็คเบอร์โทรศัพท์แบบง่าย: ต้องเป็น + และตัวเลข 0-9 เท่านั้น
    const telPattern = /^[+0-9]+$/;
    if(!tel) {
      alert(`ກະລຸນາປ້ອນເບີໂທລະສັບ ຜູ້ໂດຍສານຄົນທີ ${i}`);
      return false;
    } else if(!telPattern.test(tel)) {
      alert(`ເບີໂທລະສັບ ຜູ້ໂດຍສານຄົນທີ ${i} ບໍ່ຖືກຕ້ອງ (ຕ້ອງເປັນແຕ່ + ແລະໂຕເລກ)`);
      return false;
    }
  }
  return true;
}

</script>

</head>
<body>

<div class="container">
  <h2>ປ້ອນຂໍ້ມູນຜູ້ໂດຍສານ</h2>
  <form action="booking_finalize.php" method="post" onsubmit="return validateForm();">

    <!-- Input ซ่อนค่าต่างๆ -->
    <input type="hidden" name="id_plane" value="<?= htmlspecialchars($id_plane) ?>">
    <input type="hidden" name="fromm" value="<?= htmlspecialchars($_GET['fromm'] ?? '') ?>">
    <input type="hidden" name="too" value="<?= htmlspecialchars($_GET['too'] ?? '') ?>">
    <input type="hidden" name="flight_name" value="<?= htmlspecialchars($_GET['flight_name'] ?? '') ?>">
    <input type="hidden" name="id_plane_go" value="<?= htmlspecialchars($_GET['id_plane_go'] ?? '') ?>">
    <input type="hidden" name="id_plane_return" value="<?= htmlspecialchars($_GET['id_plane_return'] ?? '') ?>">
    <input type="hidden" name="depart_date" value="<?= htmlspecialchars($_GET['depart_date'] ?? '') ?>">
    <input type="hidden" name="depart_time" value="<?= htmlspecialchars($_GET['depart_time'] ?? '') ?>">
    <input type="hidden" name="boarding_time" value="<?= htmlspecialchars($_GET['boarding_time'] ?? '') ?>">
    

 <?php
$count = 1;
for ($i = 0; $i < $adult; $i++, $count++) {
  echo passengerForm($count, 'Adult');
}
for ($i = 0; $i < $child; $i++, $count++) {
  echo passengerForm($count, 'Child');
}
for ($i = 0; $i < $infant; $i++, $count++) {
  echo passengerForm($count, 'Infant');
}
?>

<!-- ✅ ฟิลด์วันที่เดินทาง (อยู่นอก PHP แล้ว) -->
<div class="row">
  <div class="col-5">
    
  </div>
</div>


    <button type="submit">ຢືນຢັນຂໍ້ມູນ</button>
  </form>
</div>

</body>
</html>
