<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $number = mysqli_real_escape_string($conn, $_POST['number']);
    $id_plane = mysqli_real_escape_string($conn, $_POST['id_plane']);
    $fromm = mysqli_real_escape_string($conn, $_POST['fromm']);
    $too = mysqli_real_escape_string($conn, $_POST['too']);
    $depart_time = mysqli_real_escape_string($conn, $_POST['depart_date']);
    $boarding_time = mysqli_real_escape_string($conn, $_POST['boarding_time']);
    $flight_status = mysqli_real_escape_string($conn, $_POST['flight_status']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $days_of_week = mysqli_real_escape_string($conn, $_POST['days_of_week']);
    $max_seat = mysqli_real_escape_string($conn, $_POST['max_seat']);

    $insert_sql = "INSERT INTO flight1 ( Id_plane, Fromm, Too, Depart_date, Boarding_time, Flight_status, Price, Days_of_week, Max_seat) 
                   VALUES ('$id_plane', '$fromm', '$too', '$depart_time', '$boarding_time','$flight_status', '$price', '$days_of_week', '$max_seat')";

    if (mysqli_query($conn, $insert_sql)) {
        header("Location: flights.php");
        exit();
    } else {
        $error = "Error adding flight: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Flight - Admin</title>
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
    <h2 class="mb-4">ເພີ່ມຖ້ຽວບິນ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plane"></i> ເພີ່ມຂໍ້ມູນ</h5>
        </div>
       


       

        <div class="card-body">
            <form method="POST" action="flight_add.php"> 
               <div class="row mb-3">
    <!-- <div class="col-md-6">
        <label for="no" class="form-label">ລຳດັບ</label>
        <input 
            type="number" 
            class="form-control" 
            id="no" 
            name="no" 
            required 
            min="1"> -->
    <!-- </div> -->
    <div class="col-md-6">
        <!-- <label for="id_plane" class="form-label">ລະຫັດຍົນ</label>
        <input 
            type="text" 
            class="form-control" 
            id="id_plane" 
            name="id_plane" 
            required 
            maxlength="11" 
            onkeypress="return isNumberKey(event)"> -->
          
        <label for="id_plane" class="form-label">ລະຫັດຍົນ</label>
        <input type="text" name="id_plane" id="id_plane" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="fromm" class="form-label">ຕົ້ນທາງ</label>
        <input type="text" name="fromm" id="fromm" class="form-control" required>
    </div>
    
    
</div>

               

    

<div class="mb-3 row">
    <div class="col-md-6">
        <label for="too" class="form-label">ປາຍທາງ</label>
        <input type="text" name="too" id="too" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="depart_date" class="form-label">ເວລາອອກເດີນທາງ</label>
        <input type="time" name="depart_date" id="depart_date" class="form-control" required>
    </div>
</div>

    <div class="mb-3 row">
     <div class="col-md-6">
        <label for="boarding_time" class="form-label">ເວລາຂຶ້ນຍົນ</label>
        <input type="time" name="boarding_time" id="boarding_time" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="flight_status" class="form-label">ສະຖານະ</label>
        <input type="text" name="flight_status" id="flight_status" class="form-control" required>
    </div>
    
</div>

<div class="mb-3 row">
   
    <div class="col-md-6">
        <label for="price" class="form-label">ລາຄາ</label>
        <input type="number" step="0.01" name="price" id="price" class="form-control" required>
    </div>
</div>
<div class="mb-3 row">
    <div class="col-md-6">
        <label for="days_of_week" class="form-label">ມື້ບິນ</label>
        <input type="text" name="days_of_week" id="days_of_week" class="form-control" required>
    </div>

     <div class="col-md-6">
        <label for="max_seat" class="form-label">ຈຳນວນບ່ອນນັ່ງ</label>
        <input type="number" name="max_seat" id="max_seat" class="form-control" required>
    </div>
    
    </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> ບັນທຶກ</button>
                    <a href="flights.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ຍົກເລີກ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
