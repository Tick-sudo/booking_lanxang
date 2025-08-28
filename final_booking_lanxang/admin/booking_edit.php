<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];

include '../db.php';
date_default_timezone_set('Asia/Vientiane'); // ตั้ง timezone เป็นลาว

$error = "";
$booking = null;

if (isset($_GET['Booking_id']) && !empty($_GET['Booking_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['Booking_id']);

    $sql = "SELECT * FROM booking1 WHERE Booking_id = '$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $booking = mysqli_fetch_assoc($result);

        // แปลงค่าวันที่และเวลาให้เป็นฟอร์แมต datetime-local ของ HTML
        $booking_datetime_value = date('Y-m-d\TH:i', strtotime($booking['Booking_date']));

    } else {
        $error = "Booking not found!";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['Name']);
        $lname = mysqli_real_escape_string($conn, $_POST['Lname']);
        $id_plane = mysqli_real_escape_string($conn, $_POST['Id_plane']);
        $fromm = mysqli_real_escape_string($conn, $_POST['Fromm']);
        $too = mysqli_real_escape_string($conn, $_POST['Too']);
        $booking_date_raw = mysqli_real_escape_string($conn, $_POST['Booking_date']);
        $booking_status = mysqli_real_escape_string($conn, $_POST['Booking_status']);
        $tel = mysqli_real_escape_string($conn, $_POST['Tel']);

        // แปลงฟอร์แมต datetime-local (2025-06-17T09:30) เป็น format ที่เก็บในฐานข้อมูล (Y-m-d H:i:s)
        $booking_date = date('Y-m-d H:i:s', strtotime($booking_date_raw));

        $update_sql = "UPDATE booking1 SET 
                        Name = '$name', 
                        Lname = '$lname', 
                        Id_Plane = '$id_plane', 
                        Fromm = '$fromm', 
                        Too = '$too', 
                        Booking_date = '$booking_date', 
                        Booking_status = '$booking_status', 
                        Tel = '$tel' 
                        WHERE Booking_id = '$id'";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: bookings.php");
            exit();
        } else {
            $error = "Error updating booking data: " . mysqli_error($conn);
        }
    }

} else {
    $error = "No booking ID provided!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Booking - Admin</title>
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
            color:rgb(10, 14, 27);
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
    <h2 class="mb-4">ແກ້ໄຂການຈອງ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($booking): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-check"></i> ແກ້ໄຂຂໍ້ມູນ</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="booking_edit.php?Booking_id=<?= htmlspecialchars($id) ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($booking['Booking_id']) ?>" />

                    <div class="mb-3">
                        <label for="Name" class="form-label">ຊື່</label>
                        <input type="text" id="Name" name="Name" class="form-control" value="<?= htmlspecialchars($booking['Name']) ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                        <input type="text" id="Lname" name="Lname" class="form-control" value="<?= htmlspecialchars($booking['Lname']) ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="Id_plane" class="form-label">ລະຫັດຍົນ</label>
                        <input type="text" id="Id_plane" name="Id_plane" class="form-control" value="<?= htmlspecialchars($booking['Id_plane']) ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="Fromm" class="form-label">ຕົ້ນທາງ</label>
                        <input type="text" id="Fromm" name="Fromm" class="form-control" value="<?= htmlspecialchars($booking['Fromm']) ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="Too" class="form-label">ປາຍທາງ</label>
                        <input type="text" id="Too" name="Too" class="form-control" value="<?= htmlspecialchars($booking['Too']) ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="Booking_date" class="form-label">ວັນ/ເດືອນ/ປີ ແລະ ເວລາ ການຈອງ</label>
                        <input type="datetime-local" id="Booking_date" name="Booking_date" class="form-control"
                               value="<?= htmlspecialchars($booking_datetime_value) ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="Booking_status" class="form-label">ສະຖານະການຈອງ</label>
                        <select id="Booking_status" name="Booking_status" class="form-select" required>
                            <option value="Confirmed" <?= ($booking['Booking_status'] == 'Confirmed') ? 'selected' : '' ?>>ສຳເລັດ</option>
                            <option value="Pending" <?= ($booking['Booking_status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="Cancelled" <?= ($booking['Booking_status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="Tel" class="form-label">ເບີໂທລະສັບ</label>
                        <input type="text" id="Tel" name="Tel" class="form-control" value="<?= htmlspecialchars($booking['Tel']) ?>" required />
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> ປັບປຸງຂໍ້ມູນ
                    </button>
                    <a href="bookings.php" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left"></i> ກັບຄືນ
                    </a>
                </form>
            </div>
        </div>
    <?php else: ?>
        <p>Booking not found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
