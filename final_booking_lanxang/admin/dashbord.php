<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

// ตรวจสอบว่ามีพารามิเตอร์ id มาหรือไม่
$old_id = $_GET['id'] ?? '';
if ($old_id == '') {
    header("Location: employees.php");
    exit();
}

include 'header.php';  // รวมส่วนของ header, sidebar และ topbar
include 'sidebar.php';
include 'topbar.php';

$alert = "";

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
        // ตรวจสอบ ID ใหม่ซ้ำในระบบหรือไม่
        $check_sql = "SELECT ID FROM employees WHERE ID = '$new_id'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $alert = '<div class="alert alert-danger">This ID already exists.</div>';
        } else {
            $sql = "UPDATE employees SET 
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
            if (mysqli_query($conn, $sql)) {
                header("Location: employees.php?success=1");
                exit();
            } else {
                $alert = '<div class="alert alert-danger">Update failed: ' . mysqli_error($conn) . '</div>';
            }
        }
    } else {
        $sql = "UPDATE employees SET 
            Name='$name',
            Lname='$lname',
            Gender='$gender',
            Date_of_birth='$dob',
            Address='$address',
            Education_background='$education',
            Email='$email',
            Tel='$tel'
            WHERE ID='$old_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: employees.php?success=1");
            exit();
        } else {
            $alert = '<div class="alert alert-danger">Update failed: ' . mysqli_error($conn) . '</div>';
        }
    }
}

// ดึงข้อมูลพนักงานปัจจุบัน
$sql = "SELECT * FROM employees WHERE ID = '$old_id'";
$result = mysqli_query($conn, $sql);
$employee = mysqli_fetch_assoc($result);

if (!$employee) {
    echo "<script>alert('Employee not found.'); window.location='employees.php';</script>";
    exit();
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Employee</h1>

    <?= $alert ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Employee Information</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="ID">ID</label>
                    <input type="text" class="form-control" id="ID" name="ID" value="<?= htmlspecialchars($employee['ID']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="Name">Name</label>
                    <input type="text" class="form-control" id="Name" name="Name" value="<?= htmlspecialchars($employee['Name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="Lname">Last Name</label>
                    <input type="text" class="form-control" id="Lname" name="Lname" value="<?= htmlspecialchars($employee['Lname']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="Gender">Gender</label>
                    <select class="form-control" id="Gender" name="Gender" required>
                        <option value="Male" <?= $employee['Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $employee['Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Date_of_birth">Date of Birth</label>
                    <input type="date" class="form-control" id="Date_of_birth" name="Date_of_birth" value="<?= htmlspecialchars($employee['Date_of_birth']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="Address">Address</label>
                    <textarea class="form-control" id="Address" name="Address" rows="3" required><?= htmlspecialchars($employee['Address']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="Education_background">Education Background</label>
                    <input type="text" class="form-control" id="Education_background" name="Education_background" value="<?= htmlspecialchars($employee['Education_background']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control" id="Email" name="Email" value="<?= htmlspecialchars($employee['Email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="Tel">Tel</label>
                    <input type="tel" class="form-control" id="Tel" name="Tel" value="<?= htmlspecialchars($employee['Tel']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Update Employee</button>
                <a href="employees.php" class="btn btn-secondary btn-sm">Cancel</a>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php include 'footer.php'; ?>
