<?php
session_start();
include 'db.php';

if (!isset($_SESSION['booking_data'])) {
    // หากไม่มีข้อมูลการจองใน session กลับไปหน้าจอง
    header('Location: index.php');
    exit();
}

$booking = $_SESSION['booking_data'];

// ดึงค่าที่เก็บไว้
$trip_type = $booking['trip_type'];
$fromm = $booking['fromm'];
$too = $booking['too'];

$return_date = $booking['return_date'] ?? null;
$adult = $booking['adult'];
$child = $booking['child'];
$infant = $booking['infant'];
$cabin = $booking['cabin'];
$currency = $booking['currency'];
$fullname = $booking['fullname'];
$email = $booking['email'];
$tel = $booking['tel'];

// เตรียมข้อมูลเพื่อบันทึกลงฐานข้อมูล
// สมมติตาราง booking1 มีฟิลด์ตามนี้ (ปรับให้ตรงฐานข้อมูลจริงด้วย):
// Booking_id (auto_increment), Trip_type, Fromm, Too, Depart_date, Return_date, Adult, Child, Infant, Cabin, Currency, Fullname, Email, Tel, Booking_time

$now = date('Y-m-d H:i:s');

$sql = "INSERT INTO booking1 (Trip_type, Fromm, Too, Return_date, Adult, Child, Infant, Cabin, Currency, Fullname, Email, Tel, Booking_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters ตามลำดับ
$stmt->bind_param(
    "sssssiisssssss",
    $trip_type,
    $fromm,
    $too,
    $depart_date,
    $return_date,
    $adult,
    $child,
    $infant,
    $cabin,
    $currency,
    $fullname,
    $email,
    $tel,
    $now
);

$success = $stmt->execute();
if ($success) {
    $booking_id = $stmt->insert_id;

    // เตรียมข้อความอีเมล
    $to = $email;
    $subject = "ຢືນຢັນການຈອງຕົ໋ວ LANXANG AIRWAY";
    $message = "
        ສະບາຍດີ $fullname,\n\n
        ການຈອງຂອງທ່ານຖືກບັນທຶກສຳເລັດແລ້ວ.\n
        ເລກທີການຈອງ: $booking_id\n
        ເດືອນທາງ: $fromm → $too\n
        ວັນທີ່ເດີນທາງ: $depart_date\n" .
        ($trip_type === 'round' ? "ວັນທີ່ກັບ: $return_date\n" : "") .
        "ຈຳນວນຜູ້ໂດຍສານ: ຜູ້ໃຫຍ່ $adult, ເດັກ $child, ແຮກເກີດ $infant\n
        ປະເພດຫ້ອງຜູ້ໂດຍສານ: $cabin\n
        ສະກຸນເງິນ: $currency\n
        \n
        ຂອບໃຈທີ່ໃຊ້ບໍລິການ LANXANG AIRWAY.
    ";

    $headers = "From: booking@lanxangairway.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // ส่งอีเมล
    mail($to, $subject, $message, $headers);

    // ล้าง session
    unset($_SESSION['booking_data']);
}


if ($success) {
    $booking_id = $stmt->insert_id;
    // ล้าง session เพื่อไม่ให้บันทึกซ้ำ
    unset($_SESSION['booking_data']);
} else {
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ຢືນຢັນການຈອງສຳເລັດ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background-color: #f5f8ff;
            padding: 40px;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0073e6;
        }
        p {
            font-size: 18px;
            margin-top: 15px;
        }
        a.btn {
            display: inline-block;
            margin-top: 30px;
            background-color: #002d74;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ຂອບໃຈທີ່ໄດ້ໃຊ້ບໍລິການ</h1>
        <p>ການຈອງຂອງທ່ານໄດ້ຮັບການຢືນຢັນແລ້ວ</p>
        <p><strong>ເລກທີການຈອງ:</strong> <?= htmlspecialchars($booking_id) ?></p>
        <p>ຊື່: <?= htmlspecialchars($fullname) ?></p>
        <p>ອີເມວ: <?= htmlspecialchars($email) ?></p>
        <p>ເບີໂທ: <?= htmlspecialchars($tel) ?></p>

        <a href="index.php" class="btn">ກັບໄປໜ້າຫຼັກ</a>
    </div>
</body>
</html>
