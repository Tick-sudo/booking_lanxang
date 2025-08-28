<?php
session_start();
include '../db.php';  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// กำหนดตัวแปรชื่อพนักงานสำหรับแสดงบน topbar
$employee_name = $_SESSION['employee_name'] ?? 'Admin';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์มและ sanitize
    $name = mysqli_real_escape_string($conn, $_POST['Name'] ?? '');
    $lname = mysqli_real_escape_string($conn, $_POST['Lname'] ?? '');
    $id_card = mysqli_real_escape_string($conn, $_POST['Id_card'] ?? '');
    $id_passport = mysqli_real_escape_string($conn, $_POST['Id_passport'] ?? '');
    $id_home = mysqli_real_escape_string($conn, $_POST['Id_home'] ?? '');
    $id_plane = mysqli_real_escape_string($conn, $_POST['Id_plane'] ?? '');
    $fromm = mysqli_real_escape_string($conn, $_POST['Fromm'] ?? '');
    $too = mysqli_real_escape_string($conn, $_POST['Too'] ?? '');
    $depart_date_raw = $_POST['Depart_date'] ?? '';
    $user_type = mysqli_real_escape_string($conn, $_POST['User_type'] ?? '');
    $price = mysqli_real_escape_string($conn, $_POST['Price'] ?? '');
    $price_child = mysqli_real_escape_string($conn, $_POST['Price_child'] ?? '');
    $price_infant = mysqli_real_escape_string($conn, $_POST['Price_infant'] ?? '');
    $booking_date_raw = $_POST['Booking_date'] ?? '';
    $booking_status = mysqli_real_escape_string($conn, $_POST['Booking_status'] ?? '');
    $tel = mysqli_real_escape_string($conn, $_POST['Tel'] ?? '');

    // แปลงวันที่เป็น format ฐานข้อมูล
    $depart_date = $depart_date_raw ? date('Y-m-d H:i:s', strtotime($depart_date_raw)) : null;
    $booking_date = $booking_date_raw ? date('Y-m-d H:i:s', strtotime($booking_date_raw)) : null;

    // ตรวจสอบข้อมูลจำเป็น
    if (empty($name) || empty($lname) || empty($id_plane) || empty($fromm) || empty($too) || empty($depart_date_raw) || empty($booking_date_raw) || empty($booking_status) || empty($tel)) {
        $error = "ກະລຸນາຕື່ມຂໍ້ມູນທີ່ຈຳເປັນທັງໝົດ";
    } else {
        // Insert ข้อมูล
        $insert_sql = "INSERT INTO booking1 
            (Name, Lname, Id_card, Id_passport, Id_home, Id_plane, Fromm, Too, Depart_date, User_type, Price, Price_child, Price_infant, Booking_date, Booking_status, Tel)
            VALUES 
            ('$name', '$lname', '$id_card', '$id_passport', '$id_home', '$id_plane', '$fromm', '$too', '$depart_date', '$user_type', '$price', '$price_child', '$price_infant', '$booking_date', '$booking_status', '$tel')";

        if (mysqli_query($conn, $insert_sql)) {
            // ถ้าบันทึกสำเร็จ ให้ redirect ไปหน้ารายการ booking ทันที
            header("Location: bookings.php");
            exit();
        } else {
            $error = "ຜິດພາດໃນການເພີ່ມຂໍ້ມູນ: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ເພີ່ມການຈອງ - Admin</title>
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
            user-select: none;
        }
        .topbar {
            margin-left: 224px;
            background-color: #f8f9fc;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid #e3e6f0;
            height: 56px;
            font-weight: 600;
            color: rgb(10, 14, 27);
        }
        .content {
            margin-left: 224px;
            padding: 30px;
            min-height: 100vh;
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
    <a href="bookings.php" class="active"><i class="fas fa-calendar-check"></i> ການຈອງ</a>
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
    <h2 class="mb-4">ເພີ່ມການຈອງ</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> ເພີ່ມຂໍ້ມູນ</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="booking_add.php" novalidate>
                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="Name" class="form-label">ຊື່</label>
                        <input type="text" id="Name" name="Name" class="form-control" value="<?= htmlspecialchars($_POST['Name'] ?? '') ?>" required />
                    </div>
                    <div class="col-md-6"> 
                        <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                        <input type="text" id="Lname" name="Lname" class="form-control" value="<?= htmlspecialchars($_POST['Lname'] ?? '') ?>" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="Id_card" class="form-label">ເລກບັດປະຈຳຕົວ</label>
                        <input type="text" id="Id_card" name="Id_card" class="form-control" value="<?= htmlspecialchars($_POST['Id_card'] ?? '') ?>" />
                    </div>
                    <div class="col-md-6"> 
                        <label for="Id_passport" class="form-label">ເລກໜັງສືເດີນທາງ</label>
                        <input type="text" id="Id_passport" name="Id_passport" class="form-control" value="<?= htmlspecialchars($_POST['Id_passport'] ?? '') ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="Id_home" class="form-label">ເລກສຳມະໂນຄົວ</label>
                        <input type="text" id="Id_home" name="Id_home" class="form-control" value="<?= htmlspecialchars($_POST['Id_home'] ?? '') ?>" />
                    </div>
                    <div class="col-md-6"> 
                        <label for="Id_plane" class="form-label">ລະຫັດຍົນ</label>
                        <input type="text" id="Id_plane" name="Id_plane" class="form-control" value="<?= htmlspecialchars($_POST['Id_plane'] ?? '') ?>" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="Fromm" class="form-label">ຕົ້ນທາງ</label>
                        <input type="text" id="Fromm" name="Fromm" class="form-control" value="<?= htmlspecialchars($_POST['Fromm'] ?? '') ?>" required />
                    </div>

                    <div class="col-md-6"> 
                        <label for="Too" class="form-label">ປາຍທາງ</label>
                        <input type="text" id="Too" name="Too" class="form-control" value="<?= htmlspecialchars($_POST['Too'] ?? '') ?>" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="Depart_date" class="form-label">ວັນ/ເດືອນ/ປີ ເດີນທາງ</label>
                        <input type="datetime-local" id="Depart_date" name="Depart_date" class="form-control" value="<?= htmlspecialchars($_POST['Depart_date'] ?? '') ?>" required />
                    </div>
                    
                    <div class="col-md-6"> 
                        <label for="Booking_date" class="form-label">ວັນ/ເດືອນ/ປີ ແລະ ເວລາ ການຈອງ</label>
                        <input type="datetime-local" id="Booking_date" name="Booking_date" class="form-control" value="<?= htmlspecialchars($_POST['Booking_date'] ?? '') ?>" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="User_type" class="form-label">ປະເພດຜູ້ໂດຍສານ</label>
                        <input type="text" id="User_type" name="User_type" class="form-control" value="<?= htmlspecialchars($_POST['User_type'] ?? '') ?>" />
                    </div>
                    <div class="col-md-6"> 
                        <label for="Price" class="form-label">ລາຄາຜູ້ໃຫຍ່</label>
                        <input type="number" step="0.01" id="Price" name="Price" class="form-control" value="<?= htmlspecialchars($_POST['Price'] ?? '') ?>" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="Price_child" class="form-label">ລາຄາເດັກນ້ອຍ</label>
                        <input type="number" step="0.01" id="Price_child" name="Price_child" class="form-control" value="<?= htmlspecialchars($_POST['Price_child'] ?? '') ?>" required />
                    </div>

                    <div class="col-md-6"> 
                        <label for="Price_infant" class="form-label">ລາຄາເດັກແອນ້ອຍ</label>
                        <input type="number" step="0.01" id="Price_infant" name="Price_infant" class="form-control" value="<?= htmlspecialchars($_POST['Price_infant'] ?? '') ?>" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"> 
                        <label for="Booking_status" class="form-label">ສະຖານະການຈອງ</label>
                        <select id="Booking_status" name="Booking_status" class="form-select" required>
                            <option value="">-- ເລືອກ --</option>
                            <option value="ລໍຖ້າການຊຳລະເງິນ" <?= (($_POST['Booking_status'] ?? '') == 'ລໍຖ້າການຊຳລະເງິນ') ? 'selected' : '' ?>>ລໍຖ້າການຊຳລະເງິນ</option>
                            <option value="ຊຳລະເງິນແລ້ວ" <?= (($_POST['Booking_status'] ?? '') == 'ຊຳລະເງິນແລ້ວ') ? 'selected' : '' ?>>ຊຳລະເງິນແລ້ວ</option>
                        </select>
                    </div>

                    <div class="col-md-6"> 
                        <label for="Tel" class="form-label">ເບີໂທລະສັບ</label>
                        <input type="text" id="Tel" name="Tel" class="form-control" value="<?= htmlspecialchars($_POST['Tel'] ?? '') ?>" required />
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> ບັນທຶກ</button>
                    <a href="bookings.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ຍົກເລີກ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
