<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id) {
    header("Location: tickets.php");
    exit();
}

$success = "";
$error = "";

// โหลดข้อมูลตั๋วตาม id
$sql = "SELECT * FROM ticket1 WHERE Ticket_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $ticket_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($result);

if (!$ticket) {
    header("Location: tickets.php");
    exit();
}

// เมื่อกด submit เพื่อแก้ไขข้อมูล
if (isset($_POST['submit'])) {
    $name          = mysqli_real_escape_string($conn, $_POST['name']);
    $lname         = mysqli_real_escape_string($conn, $_POST['lname']);
    $fromm         = mysqli_real_escape_string($conn, $_POST['fromm']);
    $too           = mysqli_real_escape_string($conn, $_POST['too']);
    $flight        = mysqli_real_escape_string($conn, $_POST['flight']);
    $seat          = mysqli_real_escape_string($conn, $_POST['seat']);
    $depart_date   = mysqli_real_escape_string($conn, $_POST['depart_date']);
    $boarding_time = mysqli_real_escape_string($conn, $_POST['boarding_time']);
    $depart_time   = mysqli_real_escape_string($conn, $_POST['depart_time']);

    $update_sql = "UPDATE ticket1 
                   SET Name=?, Lname=?, Fromm=?, Too=?, Flight=?, Seat=?, Depart_date=?, Boarding_time=?, Depart_time=? 
                   WHERE Ticket_id=?";
    $stmt_update = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt_update, 'ssssssssss', $name, $lname, $fromm, $too, $flight, $seat, $depart_date, $boarding_time, $depart_time, $ticket_id);

    if (mysqli_stmt_execute($stmt_update)) {
        header("Location: tickets.php?msg=updated");
        exit();
    } else {
        $error = "Error updating ticket: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Ticket - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
    <a href="tickets.php" class="active"><i class="fas fa-ticket-alt"></i> ປີ້ຍົນ</a>
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
    <h2 class="mb-4">ແກ້ໄຂປີ້ຍົນ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($ticket): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-ticket-alt"></i> ແກ້ໄຂຂໍ້ມູນ</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">ຊື່</label>
                            <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($ticket['Name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label">ນາມສະກຸນ</label>
                            <input type="text" name="lname" id="lname" class="form-control" required value="<?= htmlspecialchars($ticket['Lname']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fromm" class="form-label">ຕົ້ນທາງ</label>
                            <input type="text" name="fromm" id="fromm" class="form-control" required value="<?= htmlspecialchars($ticket['Fromm']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="too" class="form-label">ປາຍທາງ</label>
                            <input type="text" name="too" id="too" class="form-control" required value="<?= htmlspecialchars($ticket['Too']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="flight" class="form-label">ຖ້ຽວບິນ</label>
                            <input type="text" name="flight" id="flight" class="form-control" required value="<?= htmlspecialchars($ticket['Flight']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="seat" class="form-label">ບ່ອນນັ່ງ</label>
                            <input type="text" name="seat" id="seat" class="form-control" required value="<?= htmlspecialchars($ticket['Seat']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="depart_date" class="form-label">ວັນ-ເດືອນ-ປີ</label>
                            <input type="date" name="depart_date" id="depart_date" class="form-control" required value="<?= htmlspecialchars($ticket['Depart_date']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="boarding_time" class="form-label">ເວລາຂຶ້ນຍົນ</label>
                            <input type="time" name="boarding_time" id="boarding_time" class="form-control" required value="<?= htmlspecialchars($ticket['Boarding_time']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="depart_time" class="form-label">ເວລາອອກເດີນທາງ</label>
                            <input type="time" name="depart_time" id="depart_time" class="form-control" required value="<?= htmlspecialchars($ticket['Depart_time']) ?>">
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> ບັນທຶກ
                    </button>
                    <a href="tickets.php" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left"></i> ກັບຄືນ
                    </a>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
