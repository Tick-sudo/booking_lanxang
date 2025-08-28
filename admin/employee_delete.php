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

// ตรวจสอบว่ามี id ส่งมาหรือไม่
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: employees.php");
    exit();
}

$id = $_GET['id'];

// ถ้ามีการกดยืนยันลบ (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'Yes') {
        $sql_delete = "DELETE FROM employees1 WHERE ID = ?";
        $stmt = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Employee deleted successfully.";
            mysqli_stmt_close($stmt);
            header("Location: employees.php?success=" . urlencode($success));
            exit();
        } else {
            $error = "Error deleting employee: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        // ถ้ากด No หรือไม่ยืนยัน กลับหน้า employees.php
        header("Location: employees.php");
        exit();
    }
}

// ดึงข้อมูลพนักงานที่ต้องการลบมาแสดง (เพื่อแสดงชื่อ)
$sql = "SELECT * FROM employees1 WHERE ID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$employee = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$employee) {
    header("Location: employees.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Employee - Admin</title>
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
<!-- <div class="topbar">
    <div><?= htmlspecialchars($employee_name) ?></div>
</div> -->

<!-- Content -->
<div class="content">
    <h2 class="mb-4">ລົບພະນັກງານ</h2>
    <p>ທ່ານແນ່ໃຈບໍ່ທີ່ຈະລົບພະນັກງານ <strong><?= htmlspecialchars($employee['Name'] . ' ' . $employee['Lname']) ?></strong>?</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" style="max-width: 400px;">
        <button type="submit" name="confirm" value="Yes" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> ລົບ
        </button>
        <a href="employees.php" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left"></i> ຍົກເລີກ
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
