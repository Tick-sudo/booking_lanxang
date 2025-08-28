<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

date_default_timezone_set('Asia/Vientiane'); // ตั้งเวลาเป็นเวลาลาว

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = mysqli_real_escape_string($conn, $_POST['Booking_id']);
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $lname = mysqli_real_escape_string($conn, $_POST['Lname']);
    $id_plane = mysqli_real_escape_string($conn, $_POST['Id_Plane']);
    $fromm = mysqli_real_escape_string($conn, $_POST['Fromm']);
    $too = mysqli_real_escape_string($conn, $_POST['Too']);
    $booking_date_raw = $_POST['Booking_date'] ?? '';
    $booking_status = mysqli_real_escape_string($conn, $_POST['Booking_status']);
    $tel = mysqli_real_escape_string($conn, $_POST['Tel']);

    // ตรวจสอบฟิลด์ที่จำเป็น
    if (empty($booking_id)) {
        $error = "Please enter Booking ID.";
    } elseif (empty($booking_date_raw)) {
        $error = "Please enter Booking Date and Time.";
    } else {
        // แปลงรูปแบบ datetime-local เป็น datetime MySQL
        $booking_date = date('Y-m-d H:i:s', strtotime($booking_date_raw));

        // ตรวจสอบ Booking_id ซ้ำ
        $check_sql = "SELECT * FROM booking1 WHERE Booking_id = '$booking_id'";
        $check_res = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_res) > 0) {
            $error = "Booking ID already exists. Please use a different ID.";
        } else {
            $sql = "INSERT INTO booking1 
                (Booking_id, Name, Lname, Id_Plane, Fromm, Too, Booking_date, Booking_status, Tel)
                VALUES
                ('$booking_id', '$name', '$lname', '$id_plane', '$fromm', '$too', '$booking_date', '$booking_status', '$tel')";
            if (mysqli_query($conn, $sql)) {
                $success = "Booking added successfully!";
                header("Location: bookings.php?success_message=" . urlencode($success));
                exit();
            } else {
                $error = "Error adding booking: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Booking - Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f4f9;
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
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .active {
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

        .form-label {
            font-weight: 500;
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

<!-- Main Content -->
<div class="content">
    <h2 class="mb-4">ເພີ່ມການຈອງ</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-plus"></i> ເພີ່ມຂໍ້ມູນ</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="booking_add.php" autocomplete="off">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Booking_id" class="form-label">ລະຫັດການຈອງ</label>
                        <input type="text" id="Booking_id" name="Booking_id" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="Name" class="form-label">ຊື່</label>
                        <input type="text" id="Name" name="Name" class="form-control" required />
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                        <input type="text" id="Lname" name="Lname" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="Id_Plane" class="form-label">ລະຫັດຍົນ</label>
                        <input type="text" id="Id_Plane" name="Id_Plane" class="form-control" required />
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Fromm" class="form-label">ຕົ້ນທາງ</label>
                        <input type="text" id="Fromm" name="Fromm" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="Too" class="form-label">ປາຍທາງ</label>
                        <input type="text" id="Too" name="Too" class="form-control" required />
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Booking_date" class="form-label">ວັນ/ເດືອນ/ປີ ແລະ ເວລາ ການຈອງ</label>
                        <input type="datetime-local" id="Booking_date" name="Booking_date" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="Booking_status" class="form-label">ສະຖານະການຈອງ</label>
                        <input type="text" id="Booking_status" name="Booking_status" class="form-control" required />
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="Tel" class="form-label">ເບີໂທລະສັບ</label>
                    <input type="tel" id="Tel" name="Tel" class="form-control" required />
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> ບັນທຶກ</button>
                    <a href="bookings.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ຍົກເລີກ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
