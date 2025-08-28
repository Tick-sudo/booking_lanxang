<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
$employee_name = $_SESSION['employee_name'];
include '../db.php';

$error = "";
$flight = null;

if (!isset($_GET['id'])) {
    header("Location: flights.php");
    exit();
}

$number = intval($_GET['id']);
$sql = "SELECT * FROM flight1 WHERE Number = $number";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 1) {
    $flight = mysqli_fetch_assoc($result);
} else {
    $error = "Flight not found.";
}

// ฟังก์ชันตรวจสอบไอดีเครื่องบินให้เป็นตัวอักษรและตัวเลขกับขีดเท่านั้น
function isValidPlaneId($id) {
    return preg_match('/^[A-Za-z0-9\-]+$/', $id);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_plane_new = trim(mysqli_real_escape_string($conn, $_POST['id_plane']));
    $fromm = trim(mysqli_real_escape_string($conn, $_POST['fromm']));
    $too = trim(mysqli_real_escape_string($conn, $_POST['too']));
    $depart_time = trim(mysqli_real_escape_string($conn, $_POST['depart_date']));
    $boarding_time = trim(mysqli_real_escape_string($conn, $_POST['boarding_time']));
    $flight_status = trim(mysqli_real_escape_string($conn, $_POST['flight_status']));
    $price = trim(mysqli_real_escape_string($conn, $_POST['price']));
    
    $days_of_week = mysqli_real_escape_string($conn, $_POST['days_of_week']);
    $max_seat = mysqli_real_escape_string($conn, $_POST['max_seat']);
    // ตรวจสอบรูปแบบ ID plane
    if (!isValidPlaneId($id_plane_new)) {
        $error = "ລະຫັດຍົນຕ້ອງປະກອບດ້ວຍຕົວເລກ ແລະ ຕົວອັກສອນພາຍໃນ (A-Z, 0-9, -)";
    } else {
        // เช็คซ้ำเฉพาะกรณีเปลี่ยน ID_plane
        if ($id_plane_new !== $id_plane_old) {
            $check_sql = "SELECT * FROM flight1 WHERE Id_plane = '$id_plane_new'";
            $check_result = mysqli_query($conn, $check_sql);
            if (mysqli_num_rows($check_result) > 0) {
                $error = "This ID Plane '$id_plane_new' is already in use.";
            }
        }
    }

    if (empty($error)) {
        $update_sql = "UPDATE flight1 SET Id_plane='$id_plane_new', Fromm='$fromm', Too='$too', Depart_date='$depart_time', Boarding_time='$boarding_time', Flight_status='$flight_status', Price='$price', Days_of_week='$days_of_week', Max_seat='$max_seat' WHERE Id_plane = '$id_plane_old'";
        if (mysqli_query($conn, $update_sql)) {
            header("Location: flights.php");
            exit();
        } else {
            $error = "Error updating flight: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>Edit Flight - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
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
            font-weight: 600;
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
    <a href="flights.php" class="active"><i class="fas fa-plane"></i> ຖ້ຽວບິນ</a>
    <hr style="border-color: rgba(255,255,255,0.2);">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> ອອກລະບົບ</a>
</div>

<!-- Topbar -->
<div class="topbar">
    <div><?= htmlspecialchars($employee_name) ?></div>
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4">ແກ້ໄຂຖ້ຽວບິນ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($flight): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plane"></i> ແກ້ໄຂຂໍ້ມູນ</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ລະຫັດຍົນ</label>
                        <input type="text" name="id_plane" class="form-control" 
                               value="<?= htmlspecialchars($flight['Id_plane']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ຕົ້ນທາງ</label>
                        <input type="text" name="fromm" class="form-control" 
                               value="<?= htmlspecialchars($flight['Fromm']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ປາຍທາງ</label>
                        <input type="text" name="too" class="form-control" 
                               value="<?= htmlspecialchars($flight['Too']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ເວລາອອກເດີນທາງ</label>
                        <input type="time" name="depart_time" class="form-control" 
                               value="<?= htmlspecialchars($flight['Depart_time']) ?>" required>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-6 mb-3">
                        <label class="form-label">ເວລາຂຶ້ນຍົນ</label>
                        <input type="time" name="boarding_tim" class="form-control" 
                               value="<?= htmlspecialchars($flight['Boarding_time']) ?>" required>
                    </div>
            

                <div class="col-md-6 mb-3">
                        <label class="form-label">ມື້ບິນ</label>
                        <input type="text" name="days_of_week" class="form-control" 
                               value="<?= htmlspecialchars($flight['Days_of_week']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ລາຄາ</label>
                        <input type="number" name="price" class="form-control" 
                               value="<?= htmlspecialchars($flight['Price']) ?>" required>
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ສະຖານະ</label>
                        <input type="text" name="flight_status" id="flight_status" class="form-control" 
                               value="<?= htmlspecialchars($flight['Flight_status']) ?>" required>
                    </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label">ຈຳນວນບ່ອນນັ່ງ</label>
                        <input type="number" name="max_seat" class="form-control" 
                               value="<?= htmlspecialchars($flight['Max_seat']) ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> ບັນທຶກ</button>
                <a href="flights.php" class="btn btn-secondary ms-2"><i class="fas fa-arrow-left"></i> ກັບຄືນ</a>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
