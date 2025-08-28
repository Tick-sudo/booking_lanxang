<?php
session_start();
include 'db.php';

// ตรวจสอบว่าล็อกอินอยู่
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_register.php");
    exit();
}

// รับ flight_id ผ่าน GET
$flight_id = isset($_GET['flight_id']) ? intval($_GET['flight_id']) : 0;
$customer_id = $_SESSION['customer_id'];

if ($flight_id <= 0) {
    echo "ບໍ່ມີເທີບິນເລືອກ";
    exit();
}

// ดึงข้อมูลเที่ยวบินจากฐานข้อมูล
$sql = "SELECT * FROM flight1 WHERE Id_plane = $flight_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$flight = mysqli_fetch_assoc($result);

if (!$flight) {
    echo "ບໍ່ພົບເທີບິນ";
    exit();
}

// ถ้ากดปุ่มยืนยัน
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // เตรียมข้อมูลบันทึก
    $booking_date = date('Y-m-d H:i:s');
    // สมมติมีตาราง booking1 ที่เก็บ customer_id, flight_id, booking_date, status ฯลฯ
    $sql_insert = "INSERT INTO booking1 (Customer_id, Flight_id, Booking_date, Status) VALUES (
        '".mysqli_real_escape_string($conn, $customer_id)."',
        '".mysqli_real_escape_string($conn, $flight_id)."',
        '".mysqli_real_escape_string($conn, $booking_date)."',
        'Confirmed'
    )";

    if (mysqli_query($conn, $sql_insert)) {
        echo "<p>ການຈອງສຳເລັດແລ້ວ!</p>";
        echo '<a href="flights.php">ກັບໄປເລືອກເທີບິນອື່ນ</a>';
        exit();
    } else {
        echo "ບໍ່ສາມາດບັນທຶກການຈອງໄດ້: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ຢືນຢັນການຈອງ</title>
    <link rel="stylesheet" href="style.css"> <!-- เชื่อมสไตล์ถ้ามี -->
</head>
<body>
    <h2>ຢືນຢັນການຈອງ</h2>

    <p><strong>ຊື່ລູກຄ້າ:</strong> <?= htmlspecialchars($_SESSION['customer_name']) ?></p>
    <p><strong>ເທີບິນ:</strong> <?= htmlspecialchars($flight['Fromm']) ?> → <?= htmlspecialchars($flight['Too']) ?></p>
    <p><strong>ວັນທີ່ອອກ:</strong> <?= htmlspecialchars($flight['Depart_date']) ?></p>
    <p><strong>ເວລາອອກ:</strong> <?= htmlspecialchars($flight['Depart_time']) ?></p>
    <p><strong>ລາຄາ:</strong> <?= number_format($flight['Price'], 0) ?> ກີບ</p>

    <form method="post" action="">
        <button type="submit">ຢືນຢັນການຈອງ</button>
    </form>

    <a href="flights.php">ຍ້ອນກັບໜ້າເລືອກເທີບິນ</a>
</body>
</html>
