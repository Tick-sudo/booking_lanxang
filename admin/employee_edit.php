<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
$employee_name = $_SESSION['employee_name'];

include '../db.php';

// รับ id พนักงานจาก GET
$old_id = $_GET['id'] ?? '';
if ($old_id == '') {
    header("Location: employees.php");
    exit();
}

$error = "";
$employee = null;

// ดึงข้อมูลพนักงานจากฐานข้อมูล
$sql = "SELECT * FROM employees1 WHERE ID = '$old_id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 1) {
    $employee = mysqli_fetch_assoc($result);
} else {
    $error = "Employee not found.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_id = mysqli_real_escape_string($conn, $_POST['ID']);
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $lname = mysqli_real_escape_string($conn, $_POST['Lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['Gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['Date_of_birth']);
    $address = mysqli_real_escape_string($conn, $_POST['Address']);
    $education = mysqli_real_escape_string($conn, $_POST['Education_background']);
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $tel = mysqli_real_escape_string($conn, $_POST['Tel']);

    if ($new_id !== $old_id) {
        // ตรวจสอบ ID ใหม่ซ้ำหรือไม่
        $check_sql = "SELECT ID FROM employees1 WHERE ID = '$new_id'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $error = "This ID already exists.";
        } else {
            $sql_update = "UPDATE employees1 SET 
                ID='$new_id', 
                Name='$name', 
                Lname='$lname', 
                Gender='$gender', 
                Date_of_birth='$dob', 
                Address='$address', 
                Education_background='$education', 
                Email='$email', 
                Tel='$tel'
                WHERE ID='$old_id'";
            if (mysqli_query($conn, $sql_update)) {
                header("Location: employees.php?success=1");
                exit();
            } else {
                $error = "Update failed: ".mysqli_error($conn);
            }
        }
    } else {
        $sql_update = "UPDATE employees1 SET 
            Name='$name', 
            Lname='$lname', 
            Gender='$gender', 
            Date_of_birth='$dob', 
            Address='$address', 
            Education_background='$education', 
            Email='$email', 
            Tel='$tel'
            WHERE ID='$old_id'";
        if (mysqli_query($conn, $sql_update)) {
            header("Location: employees.php?success=1");
            exit();
        } else {
            $error = "Update failed: ".mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Employee - Admin</title>
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


<!-- Content -->
<div class="content">
    <h2 class="mb-4">ແກ້ໄຂຂໍ້ມູນພະນັກງານ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($employee): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> ແກ້ໄຂຂໍ້ມູນ</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ID" class="form-label">ລະຫັດ</label>
                            <input type="text" id="ID" name="ID" class="form-control" 
                                value="<?= htmlspecialchars($employee['ID']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="Name" class="form-label">ຊື່</label>
                            <input type="text" id="Name" name="Name" class="form-control" required
                                oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')"
                                maxlength="50"
                                value="<?= htmlspecialchars($employee['Name']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                            <input type="text" id="Lname" name="Lname" class="form-control" required
                                oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')"
                                maxlength="50"
                                value="<?= htmlspecialchars($employee['Lname']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="Gender" class="form-label">ເພດ</label>
                            <select id="Gender" name="Gender" class="form-select" required>
                                <option value="ຜູ້ຊາຍ" <?= $employee['Gender'] === 'ຜູ້ຊາຍ' ? 'selected' : '' ?>>ຜູ້ຊາຍ</option>
                                <option value="ຜູ້ຍິງ" <?= $employee['Gender'] === 'ຜູ້ຍິງ' ? 'selected' : '' ?>>ຜູ້ຍິງ</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="Date_of_birth" class="form-label">ວັນ/ເດືອນ/ປີ ເກີດ</label>
                            <input type="date" id="Date_of_birth" name="Date_of_birth" class="form-control" required
                                value="<?= htmlspecialchars($employee['Date_of_birth']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="Tel" class="form-label">ເບີໂທລະສັບ</label>
                            <input type="text" id="Tel" name="Tel" class="form-control" required maxlength="20"
                                value="<?= htmlspecialchars($employee['Tel']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="Address" class="form-label">ບ້ານ/ເມືອງ/ແຂວງ</label>
                            <textarea id="Address" name="Address" class="form-control" rows="3" required maxlength="150"><?= htmlspecialchars($employee['Address']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="Education_background" class="form-label">ລະດັບການສຶກສາ</label>
                            <input type="text" id="Education_background" name="Education_background" class="form-control" required
                                oninput="this.value = this.value.replace(/[^A-Za-zກ-ໝก-๙]/g, '')"
                                maxlength="50"
                                value="<?= htmlspecialchars($employee['Education_background']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="Email" class="form-label">ອີເມວ</label>
                            <input type="email" id="Email" name="Email" class="form-control" required maxlength="100"
                                value="<?= htmlspecialchars($employee['Email']) ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> ປັບປຸງຂໍ້ມູນ
                        </button>
                        <a href="employees.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> ກັບຄືນ
                        </a>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No employee data to display.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap 5 form validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})();
</script>
</body>
</html>
