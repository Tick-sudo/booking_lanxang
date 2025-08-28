<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $fromm = mysqli_real_escape_string($conn, $_POST['fromm']);
    $too = mysqli_real_escape_string($conn, $_POST['too']);
    $flight = mysqli_real_escape_string($conn, $_POST['flight']);
    $seat = mysqli_real_escape_string($conn, $_POST['seat']);
    
    // รับค่าจาก input datetime-local แล้วแปลงรูปแบบ
    $boarding_time_raw = $_POST['boarding_time']; // เช่น "2025-06-17T09:00"
    $boarding_time = str_replace('T', ' ', $boarding_time_raw) . ':00'; // เป็น "2025-06-17 09:00:00"

    if (empty($ticket_id)) {
        $error = "Please enter Ticket ID.";
    } else {
        // ตรวจสอบ ticket_id ซ้ำ
        $check_sql = "SELECT * FROM ticket1 WHERE Ticket_id = '$ticket_id'";
        $check_res = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_res) > 0) {
            $error = "Ticket ID already exists. Please use a different ID.";
        } else {
            $insert_sql = "INSERT INTO ticket1 (Ticket_id, Name, Lname, Fromm, Too, Flight, Seat, Boarding_time) 
                            VALUES ('$ticket_id', '$name', '$lname', '$fromm', '$too', '$flight', '$seat', '$boarding_time')";

            if (mysqli_query($conn, $insert_sql)) {
                $success = "Ticket has been added successfully.";
                header("Location: tickets.php?success_message=" . urlencode($success));
                exit();
            } else {
                $error = "Error adding ticket: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Ticket - Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 30px 40px;
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

<!-- Main Content -->
<div class="content">
    <h2 class="mb-4">ເພີ່ມປີ້ຍົນ</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-ticket-alt"></i> ເພີ່ມຂໍ້ມູນ</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="ticket_add.php" autocomplete="off">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="ticket_id" class="form-label">ລະຫັດປີ້ຍົນ</label>
                        <input type="text" id="ticket_id" name="ticket_id" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">ຊື່</label>
                        <input type="text" id="name" name="name" class="form-control" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="lname" class="form-label">ນາມສະກຸນ</label>
                        <input type="text" id="lname" name="lname" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="fromm" class="form-label">ຕົ້ນທາງ</label>
                        <input type="text" id="fromm" name="fromm" class="form-control" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="too" class="form-label">ປາຍທາງ</label>
                        <input type="text" id="too" name="too" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="flight" class="form-label">ຖ້ຽວບິນ</label>
                        <input type="text" id="flight" name="flight" class="form-control" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="seat" class="form-label">ບ່ອນນັ່ງ</label>
                        <input type="text" id="seat" name="seat" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="boarding_time" class="form-label">ວັນ-ເວລາເລີ່ມຂຶ້ນຍົນ</label>
                        <input type="datetime-local" id="boarding_time" name="boarding_time" class="form-control" required />
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> ບັນທຶກ</button>
                    <a href="tickets.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ຍົກເລີກ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
