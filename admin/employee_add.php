<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $lname = mysqli_real_escape_string($conn, $_POST['Lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['Gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['Date_of_birth']);
    $address = mysqli_real_escape_string($conn, $_POST['Address']);
    $education = mysqli_real_escape_string($conn, $_POST['Education_background']);
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $tel = mysqli_real_escape_string($conn, $_POST['Tel']);

    $sql = "INSERT INTO employees1 ( Name, Lname, Gender, Date_of_birth, Address, Education_background, Email, Tel)
            VALUES ('$name', '$lname', '$gender', '$dob', '$address', '$education', '$email', '$tel')";

    if (mysqli_query($conn, $sql)) {
        header("Location: employees.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee - Admin</title>
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
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid #e3e6f0;
        }

        .content {
            margin-left: 224px;
            padding: 30px;
        }

        /* ปรับ label ชิดซ้าย และจัดฟอร์มแบบ flex */
        .form-label {
            display: inline-block;
            width: 140px;
            text-align: left;
            margin-right: 10px;
            font-weight: 600;
            line-height: 2.4;
            padding-left: 0;
            color: #333;
        }

        .form-group-flex {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
            gap: 8px;
        }

        .form-group-flex input,
        .form-group-flex select,
        .form-group-flex textarea {
            flex: 1;
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 6px 12px;
            font-size: 1rem;
            transition: border-color 0.15s ease-in-out;
        }

        .form-group-flex input:focus,
        .form-group-flex select:focus,
        .form-group-flex textarea:focus {
            border-color: #4e73df;
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fas fa-plane-departure"></i> LANXANG AIRWAY<br>ADMIN</h4>

    <a href="indexadmin.php"><i class="fas fa-tachometer-alt"></i> ໜ້າຫຼັກ</a>
    <div class="management-title">ຈັດການ</div>
    <a href="employees.php" class="active"><i class="fas fa-users"></i> ພະນັກງານ</a>
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

<!-- Main Content -->
<div class="content">
    <h2 class="mb-4">ເພີ່ມພະນັກງານ</h2>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> ເພີ່ມຂໍ້ມູນ</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="employee_add.php">

                <!-- ຊື່ - ນາມສະກຸນ -->
                <div class="row">
                    <div class="col-50 mb-3">
                        <label for="Name" class="form-label">ຊື່</label>
                        <input 
                            type="text" 
                            id="Name" 
                            name="Name" 
                            class="form-control" 
                            required 
                            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝກ-๙]/g, '')" 
                            maxlength="50"
                        >
                    </div>
                    <div class="col-50 mb-3">
                        <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                        <input 
                            type="text" 
                            id="Lname" 
                            name="Lname" 
                            class="form-control" 
                            required 
                            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝກ-๙]/g, '')" 
                            maxlength="50"
                        >
                    </div>
                </div>

                <!-- ເພດ - ວັນເກີດ -->
                <div class="row">
                    <div class="col-50 mb-3">
                        <label for="Gender" class="form-label">ເພດ</label>
                        <select class="form-control" id="Gender" name="Gender" required>
                            <option value="ຜູ້ຊາຍ">-- ເລືອກ --</option>
                            <option value="ຜູ້ຊາຍ">ຜູ້ຊາຍ</option>
                            <option value="ຜູ້ຍິງ">ຜູ້ຍິງ</option>
                        </select>
                    </div>
                    <div class="col-50 mb-3">
                        <label for="Date_of_birth" class="form-label">ວັນ/ເດືອນ/ປີ ເກິດ</label>
                        <input type="date" class="form-control" id="Date_of_birth" name="Date_of_birth" required>
                    </div>
                </div>

                <!-- ເບີໂທ - ບ້ານ/ເມືອງ/ແຂວງ -->
                <div class="row">
                    <div class="col-50 mb-3">
                        <label for="Tel" class="form-label">ເບີໂທລະສັບ</label>
                        <input type="tel" class="form-control" id="Tel" name="Tel" required maxlength="20">
                    </div>
                    <div class="col-50 mb-3">
                        <label for="Address" class="form-label">ບ້ານ/ເມືອງ/ແຂວງ</label>
                        <textarea class="form-control" id="Address" name="Address" rows="1" required maxlength="150"></textarea>
                    </div>
                </div>

                <!-- ການສຶກສາ - ອີເມວ -->
                <div class="row">
                    <div class="col-50 mb-3">
                        <label for="Education_background" class="form-label">ລະດັບການສຶກສາ</label>
                        <input 
                            type="text" 
                            id="Education_background" 
                            name="Education_background" 
                            class="form-control" 
                            required 
                            oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝກ-๙]/g, '')" 
                            maxlength="50"
                        >
                    </div>
                    <div class="col-50 mb-3">
                        <label for="Email" class="form-label">ອີເມວ</label>
                        <input type="email" class="form-control" id="Email" name="Email" required maxlength="100">
                    </div>
                </div>

                <!-- ปุ่ม -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> ບັນທຶກ</button>
                    <a href="employees.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ຍົກເລີກ</a>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
/* ให้ col-50 จัดครึ่งหน้าจอ */
.col-50 {
    width: 50%;
    float: left;
    padding: 0 10px;
}
.row::after {
    content: "";
    display: table;
    clear: both;
}
</style>

</form>

<script>
    // จำกัดให้พิมพ์ได้เฉพาะตัวเลข
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>

</body>
</html>
