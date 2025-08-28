<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
$employee_name = $_SESSION['employee_name'];
include '../db.php';

// เมื่อกดปุ่มบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Name         = mysqli_real_escape_string($conn, $_POST['Name']);
    $Lname        = mysqli_real_escape_string($conn, $_POST['Lname']);
    $Fromm        = mysqli_real_escape_string($conn, $_POST['Fromm']);
    $Too          = mysqli_real_escape_string($conn, $_POST['Too']);
    $Flight       = mysqli_real_escape_string($conn, $_POST['Flight']);
    $Seat         = mysqli_real_escape_string($conn, $_POST['Seat']);
    $Depart_date  = mysqli_real_escape_string($conn, $_POST['Depart_date']);
    $Boarding_time= mysqli_real_escape_string($conn, $_POST['Boarding_time']);
    $Depart_time  = mysqli_real_escape_string($conn, $_POST['Depart_time']);

    $sql = "INSERT INTO ticket1 
            (Name, Lname, Fromm, Too, Flight, Seat, Depart_date, Boarding_time, Depart_time) 
            VALUES 
            ('$Name','$Lname','$Fromm','$Too','$Flight','$Seat','$Depart_date','$Boarding_time','$Depart_time')";
    if (mysqli_query($conn, $sql)) {
        header("Location: tickets.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    <div><?php echo htmlspecialchars($employee_name); ?></div>
</div>

<!-- Content -->
<div class="content">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-ticket-alt"></i> ເພີ່ມປີ້ຍົນ</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ຊື່</label>
                        <input type="text" name="Name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ນາມສະກຸນ</label>
                        <input type="text" name="Lname" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ຕົ້ນທາງ</label>
                        <input type="text" name="Fromm" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ປາຍທາງ</label>
                        <input type="text" name="Too" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">ຖ້ຽວບິນ</label>
                        <input type="text" name="Flight" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ບ່ອນນັ່ງ</label>
                        <input type="text" name="Seat" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ວັນ-ເດືອນ-ປີ</label>
                        <input type="date" name="Depart_date" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ເວລາຂຶ້ນຍົນ</label>
                        <input type="time" name="Boarding_time" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ເວລາອອກເດີນທາງ</label>
                        <input type="time" name="Depart_time" class="form-control" required>
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

</body>
</html>
