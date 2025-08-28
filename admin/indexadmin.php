<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
$employee_name = $_SESSION['employee_name'];
include '../db.php';

// ดึงข้อมูลจำนวนรายการ
$employee_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees1"))['total'];
$customer_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM customer1"))['total'];
$booking_count  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking1"))['total'];
$flight_count   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM flight1"))['total'];
$ticket_count   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM ticket1"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 224px;
            background: linear-gradient(180deg,rgb(68, 105, 216));
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
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e3e6f0;
        }

        .topbar input[type="text"] {
            padding: 6px 10px;
            width: 250px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .content {
            margin-left: 224px;
            padding: 30px;
            background-color: #f1f4f9;
            min-height: 100vh;
        }

        .card-summary {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.2s;
        }

        .card-summary:hover {
            transform: scale(1.02);
        }

        .card-summary i {
            font-size: 28px;
            margin-bottom: 10px;
            color: #4e73df;
        }

        .card-summary h5 {
            margin: 0;
            font-size: 18px;
            color: #6c757d;
        }

        .card-summary p {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0 0;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fas fa-plane-departure"></i> LANXANG AIRWAY <br>ADMIN</h4>

    <a href="indexadmin.php" class="active"><i class="fas fa-tachometer-alt"></i> ໜ້າຫຼັກ</a>
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
    <div><?php echo htmlspecialchars($employee_name); ?></div>
</div>

<!-- Page Content -->
<div class="content">
    <h2>ຍິນດີຕ້ອນຮັບສູ່ Lanxang Airway Admin </h2>
    <p class="text-muted">ລາຍງານລະບົບ:</p>

    <style>
.card-link {
    text-decoration: none;
    color: inherit;
}
.card-summary {
    background:rgb(243, 244, 245);
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: 0.3s;
    cursor: pointer;
}
.card-summary:hover {
    background:rgb(248, 248, 248);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.card-summary i {
    font-size: 30px;
    color: #007bff;
}
</style>

<div class="row g-4 mt-3">
    <div class="col-md-4">
        <a href="report_employees.php" class="card-link">
            <div class="card-summary">
                <i class="fas fa-users"></i>
                <h5> ລາຍງານພະນັກງານ</h5>
                <p><?= $employee_count ?></p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="report_customers.php" class="card-link">
            <div class="card-summary">
                <i class="fas fa-user"></i>
                <h5>ລາຍງານລູກຄ້າ</h5>
                <p><?= $customer_count ?></p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="report_bookings.php" class="card-link">
            <div class="card-summary">
                <i class="fas fa-calendar-check"></i>
                <h5>ລາຍງານການຈອງ</h5>
                <p><?= $booking_count ?></p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="report_flights.php" class="card-link">
            <div class="card-summary">
                <i class="fas fa-plane"></i>
                <h5>ລາຍງານຖ້ຽວບິນ</h5>
                <p><?= $flight_count ?></p>
            </div>
        </a>
    </div>
  <div class="col-md-4">
    <a href="report_tickets.php" class="card-link disabled-link">
        <div class="card-summary">
            <i class="fas fa-ticket-alt"></i>
            <h5>ລາຍງານປີ້ຍົນ</h5>
            <p><?= $ticket_count ?></p>
        </div>
    </a>
</div>

<!-- <style>
    .disabled-link {
        pointer-events: none; /* ปิดการคลิก */
        color: gray;          /* สีเทา */
        text-decoration: none;
        cursor: default;
    }
</style> -->

</div>

        </div>
    </div>
</div>

</body>
</html>
