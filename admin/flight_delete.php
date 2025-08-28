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

// ✅ รับค่าจาก URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $flight_number = $_GET['id'];
} else {
    header("Location: flights.php");
    exit();
}

// ✅ ดึงข้อมูลเที่ยวบินจาก field Number
if ($stmt = mysqli_prepare($conn, "SELECT * FROM flight1 WHERE Number = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $flight_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $flight = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$flight) {
        $error = "Flight not found.";
    }
} else {
    $error = "SQL prepare error: " . mysqli_error($conn);
}

// ✅ ถ้ามีการยืนยันลบ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'Yes') {
        $delete_sql = "DELETE FROM flight1 WHERE Number = ?";
        $stmt_del = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($stmt_del, "s", $flight_number);
        if (mysqli_stmt_execute($stmt_del)) {
            mysqli_stmt_close($stmt_del);
            header("Location: flights.php?success=" . urlencode("Flight deleted successfully."));
            exit();
        } else {
            $error = "Error deleting flight: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_del);
    } else {
        // ยกเลิกการลบ
        header("Location: flights.php");
        exit();
    }
}

// ถ้ามีการยืนยันลบ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'Yes') {
        $delete_sql = "DELETE FROM Flight1 WHERE Id_plane = ?";
        $stmt_del = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($stmt_del, "s", $id_plane);
        if (mysqli_stmt_execute($stmt_del)) {
            mysqli_stmt_close($stmt_del);
            header("Location: flights.php?success=" . urlencode("Flight deleted successfully."));
            exit();
        } else {
            $error = "Error deleting flight: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_del);
    } else {
        // ถ้ากดยกเลิก
        header("Location: flights.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Flight - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    <h2 class="mb-4">ລົບຖ້ຽວບິນ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($flight && empty($error)): ?>
        <p>ທ່ານແນ່ໃຈບໍ່ທີ່ຈະລົບຖ້ຽວບິນ</p>
        <ul>
            <li><strong>ລະຫັດຍົນ:</strong> <?= htmlspecialchars($flight['Id_plane']) ?></li>
            <li><strong>ຕົ້ນທາງ:</strong> <?= htmlspecialchars($flight['Fromm']) ?></li>
            <li><strong>ປາຍທາງ:</strong> <?= htmlspecialchars($flight['Too']) ?></li>
            <li><strong>ເວລາອອກເດີນທາງ:</strong> <?= htmlspecialchars($flight['Depart_time']) ?></li>
            <li><strong>ເວລາອອກຂຶ້ນຍົນ:</strong> <?= htmlspecialchars($flight['Boarding_time']) ?></li>
            <li><strong>ສະຖານະ:</strong> <?= htmlspecialchars($flight['Flight_status']) ?></li>
            <li><strong>ມື້ບິນ:</strong> <?= htmlspecialchars($flight['Days_of_week']) ?></li>
             <li><strong>ຈຳນວນບ່ອນນັ່ງ:</strong> <?= htmlspecialchars($flight['Max_seat']) ?></li>
           
        </ul>

        <form method="post" style="max-width: 400px;">
            <button type="submit" name="confirm" value="Yes" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> ລົບ
            </button>
            <a href="flights.php" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left"></i> ຍົກເລີກ
            </a>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
