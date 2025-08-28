<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];

include '../db.php';

$error = "";

// รับค่า id
$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id) {
    header("Location: tickets.php");
    exit();
}

// โหลดข้อมูลตั๋ว
$sql = "SELECT * FROM ticket1 WHERE Ticket_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $ticket_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$ticket) {
    header("Location: tickets.php");
    exit();
}

// เมื่อกดปุ่มยืนยันลบ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        $delete_sql = "DELETE FROM ticket1 WHERE Ticket_id = ?";
        $stmt_delete = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($stmt_delete, "s", $ticket_id);

        if (mysqli_stmt_execute($stmt_delete)) {
            mysqli_stmt_close($stmt_delete);
            header("Location: tickets.php?success=" . urlencode("Ticket deleted successfully."));
            exit();
        } else {
            $error = "Error deleting ticket: " . mysqli_error($conn);
            mysqli_stmt_close($stmt_delete);
        }
    } else {
        header("Location: tickets.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Ticket - Admin</title>
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
    <a href="customers.php" class="active"><i class="fas fa-user"></i> ລູກຄ້າ</a>
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
   <h2 class="mb-4 text-dark">ລົບປີ້ຍົນ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <p>ທ່ານແນ່ໃຈບໍ່ທີ່ຈະລົບປີ້ຍົນ</p>
    <ul>
        <li><strong>ລະຫັດປີ້ຍົນ:</strong> <?= htmlspecialchars($ticket['Ticket_id']) ?></li>
        <li><strong>ຊື່:</strong> <?= htmlspecialchars($ticket['Name']) ?> <?= htmlspecialchars($ticket['Lname']) ?></li>
        <li><strong>ຕົ້ນທາງ:</strong> <?= htmlspecialchars($ticket['Fromm']) ?></li>
        <li><strong>ປາຍທາງ:</strong> <?= htmlspecialchars($ticket['Too']) ?></li>
        <li><strong>ຖ້ຽວບິນ:</strong> <?= htmlspecialchars($ticket['Flight']) ?></li>
        <li><strong>ວັນ/ເດືອນ/ປີ ເດີນທາງ:</strong> <?= htmlspecialchars($ticket['Depart_date']) ?></li>
        <li><strong>ບ່ອນນັ່ງ:</strong> <?= htmlspecialchars($ticket['Seat']) ?></li>
        <li><strong>ເວລາຂຶ້ນຍົນ:</strong> <?= htmlspecialchars($ticket['Boarding_time']) ?></li>
        <li><strong>ເວລາອອກເດີນທາງ:</strong> <?= htmlspecialchars($ticket['Depart_time']) ?></li>
    </ul>

    <form method="post" style="max-width: 400px;">
        <button type="submit" name="confirm_delete" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> ລົບ
        </button>
        <a href="tickets.php" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left"></i> ຍົກເລີກ
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
