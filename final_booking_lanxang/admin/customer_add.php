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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่า POST อย่างปลอดภัย
    $id = mysqli_real_escape_string($conn, $_POST['ID']);
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $lname = mysqli_real_escape_string($conn, $_POST['Lname']);
    $gender = isset($_POST['Gender']) ? mysqli_real_escape_string($conn, $_POST['Gender']) : '';
    $dob = mysqli_real_escape_string($conn, $_POST['Date_of_birth']);
    $nationality = mysqli_real_escape_string($conn, $_POST['Nationality']);
    $country = mysqli_real_escape_string($conn, $_POST['Country']);
    $address = mysqli_real_escape_string($conn, $_POST['Address']);
    $id_passport = mysqli_real_escape_string($conn, $_POST['Id_passport']);
    $id_card = mysqli_real_escape_string($conn, $_POST['Id_card']);
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $type_ticket = mysqli_real_escape_string($conn, $_POST['Type_of_ticket']);
    $tel = mysqli_real_escape_string($conn, $_POST['Tel']);

    if (empty($id)) {
        $error = "Please enter the Customer ID.";
    } elseif (empty($id_passport) && empty($id_card)) {
        $error = "Please enter either Passport ID or Card ID.";
    } else {
        // ตรวจสอบว่ามี ID ซ้ำในฐานข้อมูลหรือไม่
        $check_sql = "SELECT * FROM customer1 WHERE ID = '$id'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $error = "This Customer ID already exists. Please use a different ID.";
        } else {
            $sql = "INSERT INTO customer1 
                (ID, Name, Lname, Gender, Date_of_birth,  Nationality, Country, Address, Id_passport, Id_card, Email, Type_of_ticket, Tel)
                VALUES 
                ('$id', '$name', '$lname', '$gender', '$dob',  '$nationality','$country', '$address', '$id_passport', '$id_card', '$email', '$type_ticket', '$tel')";

            if (mysqli_query($conn, $sql)) {
                $success = "Customer added successfully!";
                header("Location: customers.php?success_message=" . urlencode($success));
                exit();
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Customer - Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
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
            padding: 30px;
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
    <a href="tickets.php"><i class="fas fa-ticket-alt"></i> ປີ້ຍົນ</a>
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
    <h2 class="mb-4">ເພີ່ມລູກຄ້າ</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> ເພີ່ມຂໍ້ມູນ</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="customer_add.php" autocomplete="off">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="ID" class="form-label">ລະຫັດ</label>
                        <input type="text" id="ID" name="ID" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="Name" class="form-label">ຊື່</label>
                      <input 
            type="text" 
            id="Name" 
            name="Name" 
            class="form-control" 
            required 
            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')" 
            maxlength="50"
        >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                         <input 
            type="text" 
            id="Lname" 
            name="Lname" 
            class="form-control" 
            required 
            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')" 
            maxlength="50"
        >
                    </div>
                    <div class="col-md-6">
                        <label for="Gender" class="form-label">ເພດ</label>
                        <select id="Gender" name="Gender" class="form-control" required>
                           
                            <option value="ຜູ້ຊາຍ">ຜູ້ຊາຍ</option>
                            <option value="ຜູ້ຍິງ">ຜູ້ຍິງ</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Date_of_birth" class="form-label">ວັນ/ເດືອນ/ປີ ເກີດ</label>
                        <input type="date" id="Date_of_birth" name="Date_of_birth" class="form-control" required />
                    </div>


                

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Nationality" class="form-label">ສັນຊາດ</label>
                        <input 
            type="text" 
            id="Nationality" 
            name="Nationality" 
            class="form-control" 
            required 
            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')" 
            maxlength="50"
        >
                    </div>

                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Country" class="form-label"> ປະເທດ</label>
                        <input 
            type="text" 
            id="Country" 
            name="Country" 
            class="form-control" 
            required 
            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')" 
            maxlength="50"
        >
                    </div>


                    <div class="col-md-6">
                        <label for="Address" class="form-label">ທີ່ຢູ່</label>
                        
                          <input type="text" id="Address" name="Address" class="form-control" require maxlength="150"/>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Id_passport" class="form-label">ລະຫັດ ໜັງສືເດີນທາງ</label>
                        <input type="text" id="Id_passport" name="Id_passport" class="form-control" require maxlength="30"/>
                    </div>
                    <div class="col-md-6">
                        <label for="Id_card" class="form-label">ລະຫັດ ບັດປະຈຳຕົວ</label>
                        <input type="text" id="Id_card" name="Id_card" class="form-control"require maxlength="30"/>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Email" class="form-label">ອີເມວ</label>
                        <input type="email" id="Email" name="Email" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="Type_of_ticket" class="form-label">ປະເພດຂອງປີ້</label>
                       <input 
            type="text" 
            id="Type_of_ticket" 
            name="Type_of_ticket" 
            class="form-control" 
            required 
            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')" 
            maxlength="20"
        >
                </div>

                <div class="mb-3">
                    <label for="Tel" class="form-label">ເບີໂທລະສັບ</label>
                    <input type="tel" id="Tel" name="Tel" class="form-control" />
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> ບັນທຶກ</button>
                    <a href="customers.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ຍົກເລີກ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
