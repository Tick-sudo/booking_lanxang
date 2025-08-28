<?php
session_start();
include 'db.php';

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST มาหรือไม่ (จากหน้าก่อนหน้า เช่น form กรอกข้อมูลลูกค้า)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); // ถ้าเข้าหน้านี้แบบตรงๆ ให้กลับไปหน้าแรก
    exit();
}

// ดึงข้อมูลที่ส่งมาจากฟอร์ม
$trip_type = $_POST['trip_type'] ?? '';
$fromm = $_POST['fromm'] ?? '';
$too = $_POST['too'] ?? '';
$depart_date = $_POST['depart_date'] ?? '';
$return_date = $_POST['return_date'] ?? '';
$adult = intval($_POST['adult'] ?? 0);
$child = intval($_POST['child'] ?? 0);
$infant = intval($_POST['infant'] ?? 0);
$cabin = $_POST['cabin'] ?? '';
$currency = $_POST['currency'] ?? '';

// ข้อมูลลูกค้า (ตัวอย่าง)
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$tel = $_POST['tel'] ?? '';

// ตรวจสอบข้อมูลเบื้องต้น
$errors = [];
if (!$fromm || !$too || $fromm === $too) {
    $errors[] = "ต้นทางและปลายทางต้องเลือกและต้องไม่เหมือนกัน";
}
if (!$depart_date) {
    $errors[] = "กรุณาเลือกวันเดินทาง";
}
if ($trip_type === 'round') {
    if (!$return_date) {
        $errors[] = "กรุณาเลือกวันเดินทางกลับ";
    } elseif ($return_date < $depart_date) {
        $errors[] = "วันเดินทางกลับต้องไม่ต่ำว่าวันเดินทางไป";
    }
}
if ($adult < 1) {
    $errors[] = "ผู้ใหญ่ต้องไม่น้อยกว่า 1 คน";
}
if (!$fullname) {
    $errors[] = "กรุณากรอกชื่อ-นามสกุล";
}
if (!$email) {
    $errors[] = "กรุณากรอกอีเมล";
}
if (!$tel) {
    $errors[] = "กรุณากรอกเบอร์โทรศัพท์";
}

if (!empty($errors)) {
    echo "<h3>พบข้อผิดพลาด:</h3><ul>";
    foreach ($errors as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    echo "</ul><a href='javascript:history.back()'>กลับไปแก้ไข</a>";
    exit();
}

// สร้าง session เก็บข้อมูลการจอง (หรือจะเก็บไว้ใน hidden form ส่งไปหน้าสุดท้ายก็ได้)
$_SESSION['booking_data'] = [
    'trip_type' => $trip_type,
    'fromm' => $fromm,
    'too' => $too,
    'depart_date' => $depart_date,
    'return_date' => $return_date,
    'adult' => $adult,
    'child' => $child,
    'infant' => $infant,
    'cabin' => $cabin,
    'currency' => $currency,
    'fullname' => $fullname,
    'email' => $email,
    'tel' => $tel,
];

?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ຢືນຢັນການຈອງຕົ໋ວ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background-color: #f5f8ff;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #002d74;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        td.label {
            font-weight: bold;
            color: #333;
            width: 40%;
        }
        .btn {
            background-color: #002d74;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 15px;
            display: inline-block;
        }
        .btn.cancel {
            background-color: #888;
        }
        .btn-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ຢືນຢັນການຈອງຕົ໋ວ</h1>
        <table>
            <tr>
                <td class="label">ເປົ້າໝາຍການດຳເນີນງານ</td>
                <td><?= htmlspecialchars($trip_type == 'round' ? 'ໄປ-ກັບ' : 'ໄປຢ່າງດຽວ') ?></td>
            </tr>
            <tr>
                <td class="label">ຕົ້ນທາງ</td>
                <td><?= htmlspecialchars($fromm) ?></td>
            </tr>
            <tr>
                <td class="label">ປາຍທາງ</td>
                <td><?= htmlspecialchars($too) ?></td>
            </tr>
            <tr>
                <td class="label">ວັນທີ່ໄປ</td>
                <td><?= htmlspecialchars($depart_date) ?></td>
            </tr>
            <?php if ($trip_type === 'round'): ?>
            <tr>
                <td class="label">ວັນທີ່ກັບ</td>
                <td><?= htmlspecialchars($return_date) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="label">ຜູ້ໃຫຍ່</td>
                <td><?= $adult ?></td>
            </tr>
            <tr>
                <td class="label">ເດັກນ້ອຍ</td>
                <td><?= $child ?></td>
            </tr>
            <tr>
                <td class="label">ແຮກເກີດ</td>
                <td><?= $infant ?></td>
            </tr>
            <tr>
                <td class="label">ປະເພດບ່ອນນັ່ງ</td>
                <td><?= htmlspecialchars($cabin) ?></td>
            </tr>
            <tr>
                <td class="label">ສະກຸນເງິນ</td>
                <td><?= strtoupper(htmlspecialchars($currency)) ?></td>
            </tr>
            <tr>
                <td class="label">ຊື່-ນາມສະກຸນ</td>
                <td><?= htmlspecialchars($fullname) ?></td>
            </tr>
            <tr>
                <td class="label">ອີເມວ</td>
                <td><?= htmlspecialchars($email) ?></td>
            </tr>
            <tr>
                <td class="label">ເບີໂທລະສັບ</td>
                <td><?= htmlspecialchars($tel) ?></td>
            </tr>
        </table>

        <div class="btn-container">
            <form action="booking_finalize.php" method="post" style="display:inline;">
                <button type="submit" class="btn">ຢືນຢັນການຈອງ</button>
            </form>
            <button onclick="history.back()" class="btn cancel">ແກ້ໄຂຂໍ້ມູນ</button>
        </div>
    </div>
</body>
</html>
