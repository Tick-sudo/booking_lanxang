<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

$error = "";
$success = "";

// ตรวจสอบว่ามี Booking_id ส่งมาหรือไม่
if (!isset($_GET['Booking_id']) || empty($_GET['Booking_id'])) {
    header("Location: bookings.php");
    exit();
}

$id = $_GET['Booking_id'];

// ดึงข้อมูล booking เพื่อแสดงก่อนลบ
$sql = "SELECT * FROM booking1 WHERE Booking_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$booking) {
    header("Location: bookings.php");
    exit();
}

// ถ้ามีการกดยืนยันลบ (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'Yes') {
    $sql_delete = "DELETE FROM booking1 WHERE Booking_id = ?";
    $stmt = mysqli_prepare($conn, $sql_delete);
    mysqli_stmt_bind_param($stmt, "s", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        $success = "Booking ID $id has been deleted successfully.";
        header("Location: bookings.php?success=" . urlencode($success));
        exit();
    } else {
        $error = "Error deleting booking: " . mysqli_error($conn);
        mysqli_stmt_close($stmt);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // กรณีไม่ confirm ให้กลับไปหน้า bookings.php
    header("Location: bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Booking - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fc;
        }

        .sidebar {
            width: 224px;
            background: linear-gradient(180deg, rgb(68, 105, 216));
            color: white;
            position: fixed;
            height: 100%;
            padding-top: 20px;
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            line-height: 1.2;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover, .sidebar .active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .management-title {
            font-size: 12px;
            font-weight: bold;
            padding: 8px 20px;
            color: #cbd5e1;
        }

        .topbar {
            margin-left: 224px;
            background-color: #f8f9fc;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid #e3e6f0;
        }

        .content {
            margin-left: 224px;
            padding: 30px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fas fa-plane-departure"></i> LANXANG AIRWAY<br>ADMIN</h4>

    <a href="indexadmin.php"><i class="fas fa-tachometer-alt"></i> ໜ້າຫຼັກ</a>
    <div class="management-title">ຈັດການ</div>
    <a href="employees.php"><i class="fas fa-users"></i> ພະນັກງານ</a>
    <a href="customers.php"><i class="fas fa-user"></i> ລູກຄ້າ</a>
    <a href="bookings.php"><i class="fas fa-calendar-check"></i> ການຈອງ</a>
    <a href="tickets.php"><i class="fas fa-ticket-alt"></i> ປີ້ຍົນ</a>
    <a href="flights.php"><i class="fas fa-plane"></i> ຖ້ຽວບິນ</a>
    
    <hr style="border-color: rgba(255,255,255,0.2);">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> ອອກລະບົບ</a>
</div>

<!-- Topbar -->
<div class="topbar">
    <div><?= htmlspecialchars($employee_name) ?></div>
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4">ລົບການຈອງ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <p>ທ່ານແນ່ໃຈບໍ່ທີ່ຈະລົບການຈອງ <strong><?= htmlspecialchars($booking['Name'] . ' ' . $booking['Lname']) ?></strong>?</p>
    <ul>
    <li><strong>ລະຫັດການຈອງ:</strong> <?= htmlspecialchars($booking['Booking_id']) ?></li>
    <li><strong>ລະຫັດລູກຄ້າ:</strong> <?= htmlspecialchars($booking['Customer_id']) ?></li>
    <li><strong>ຊື່:</strong> <?= htmlspecialchars($booking['Name']) ?></li>
    <li><strong>ນາມສະກຸນ:</strong> <?= htmlspecialchars($booking['Lname']) ?></li>
    <li><strong>ເລກບັດປະຈຳຕົວ:</strong> <?= htmlspecialchars($booking['Id_card']) ?></li>
    <li><strong>ເລກໜັງສືເດີນທາງ:</strong> <?= htmlspecialchars($booking['Id_passport']) ?></li>
    <li><strong>ເລກສຳມະໂນຄົວ:</strong> <?= htmlspecialchars($booking['Id_home']) ?></li>
    <li><strong>ລະຫັດຍົນ:</strong> <?= htmlspecialchars($booking['Id_plane']) ?></li>
    <li><strong>ຕົ້ນທາງ:</strong> <?= htmlspecialchars($booking['Fromm']) ?></li>
    <li><strong>ປາຍທາງ:</strong> <?= htmlspecialchars($booking['Too']) ?></li>
    <li><strong>ວັນ/ເດືອນ/ປີ ເດີນທາງ:</strong> <?= htmlspecialchars($booking['Depart_date']) ?></li>
    <li><strong>ປະເພດຜູ້ໂດຍສານ:</strong> <?= htmlspecialchars($booking['User_type']) ?></li>
    <li><strong>ລາຄາຜູ້ໃຫຍ່:</strong> <?= htmlspecialchars($booking['Price']) ?></li>
    <li><strong>ລາຄາເດັກນ້ອຍ:</strong> <?= htmlspecialchars($booking['Price_child']) ?></li>
    <li><strong>ລາຄາແອນ້ອຍ:</strong> <?= htmlspecialchars($booking['Price_infant']) ?></li>
    <li><strong>ວັນ/ເດືອນ/ປີ ການຈອງ:</strong> <?= htmlspecialchars($booking['Booking_date']) ?></li>
    <?php if (!empty($booking['Depart_time'])): ?>
        <li><strong>ສະຖານະການຈອງ:</strong> <?= htmlspecialchars($booking['Booking_status']) ?></li>
    <?php endif; ?>
    <?php if (!empty($booking['Booking_time'])): ?>
        <li><strong>ເບີໂທລະສັບ:</strong> <?= htmlspecialchars($booking['Tel']) ?></li>
    <?php endif; ?>
</ul>


    <form method="post" style="max-width: 400px;">
        <button type="submit" name="confirm" value="Yes" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> ລົບ
        </button>
        <a href="bookings.php" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left"></i> ຍົກເລີກ
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
