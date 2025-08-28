<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

$error = "";
$customer = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "SELECT * FROM customer1 WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $customer = mysqli_fetch_assoc($result);
    } else {
        $error = "Customer not found!";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['Name']);
        $lname = mysqli_real_escape_string($conn, $_POST['Lname']);
        $gender = mysqli_real_escape_string($conn, $_POST['Gender']);
        $dob = mysqli_real_escape_string($conn, $_POST['Date_of_birth']);
        $country = mysqli_real_escape_string($conn, $_POST['Country']);
        $nationality = mysqli_real_escape_string($conn, $_POST['Nationality']);
        $address = mysqli_real_escape_string($conn, $_POST['Address']);
        $id_passport = !empty($_POST['Id_passport']) ? mysqli_real_escape_string($conn, $_POST['Id_passport']) : NULL;
        $id_card = !empty($_POST['Id_card']) ? mysqli_real_escape_string($conn, $_POST['Id_card']) : NULL;
        $email = mysqli_real_escape_string($conn, $_POST['Email']);
        $type_of_ticket = mysqli_real_escape_string($conn, $_POST['Type_of_ticket']);
        $tel = mysqli_real_escape_string($conn, $_POST['Tel']);

        $update_sql = "UPDATE customer1 SET 
                        Name = '$name', 
                        Lname = '$lname', 
                        Gender = '$gender', 
                        Date_of_birth = '$dob', 
                        Country = '$country', 
                        Nationality = '$nationality', 
                        Address = '$address', 
                        Email = '$email', 
                        Type_of_ticket = '$type_of_ticket', 
                        Tel = '$tel'";

        if ($id_passport) {
            $update_sql .= ", Id_passport = '$id_passport'";
        } elseif ($id_card) {
            $update_sql .= ", Id_card = '$id_card'";
        }

        $update_sql .= " WHERE id = '$id'";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: customers.php");
            exit();
        } else {
            $error = "Error updating customer data: " . mysqli_error($conn);
        }
    }

} else {
    $error = "No customer ID provided!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Customer - Admin</title>
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
    <h2 class="mb-4">ແກ້ໄຂລູກຄ້າ</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($customer): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user"></i> ແກ້ໄຂຂໍ້ມູນ</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="customer_edit.php?id=<?= htmlspecialchars($id) ?>">
                <div class="mb-3">
                    <label for="Name" class="form-label">ຊື່</label>
                    <input type="text" id="Name" name="Name" class="form-control" value="<?= htmlspecialchars($customer['Name']) ?>" required />
                </div>

                <div class="mb-3">
                    <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                    <input type="text" id="Lname" name="Lname" class="form-control" value="<?= htmlspecialchars($customer['Lname']) ?>" required />
                </div>

                <div class="mb-3">
                    <label for="Gender" class="form-label">ເພດ</label>
                    <select id="Gender" name="Gender" class="form-select" required>
                        <option value="ຜູ້ຊາຍ" <?= ($customer['Gender'] == 'Male') ? 'selected' : '' ?>>ຜູ້ຊາຍ</option>
                        <option value="ຜູ້ຍິງ" <?= ($customer['Gender'] == 'Female') ? 'selected' : '' ?>>ຜູ້ຍິງ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="Date_of_birth" class="form-label">ວັນ/ເດືອນ/ປີ ເກີດ</label>
                    <input type="date" id="Date_of_birth" name="Date_of_birth" class="form-control" value="<?= htmlspecialchars($customer['Date_of_birth']) ?>" required />
                </div>

              


                <div class="mb-3">
                    <label for="Nationality" class="form-label">ສັນຊາດ</label>
                    <input type="text" id="Nationality" name="Nationality" class="form-control" value="<?= htmlspecialchars($customer['Nationality']) ?>" required />
                </div>

                <div class="mb-3">
                    <label for="Country" class="form-label">ປະເທດ</label>
                    <input type="text" id="Country" name="Country" class="form-control" value="<?= htmlspecialchars($customer['Country']) ?>" required />
                </div>

                <div class="mb-3">
                    <label for="Address" class="form-label">ທີ່ຢູ່</label>
                    <textarea id="Address" name="Address" class="form-control" rows="3" required><?= htmlspecialchars($customer['Address']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="Id_passport" class="form-label">ລະຫັດໜັງສືເດີນທາງ</label>
                    <input type="text" id="Id_passport" name="Id_passport" class="form-control" value="<?= htmlspecialchars($customer['Id_passport']) ?>" />
                </div>

                <div class="mb-3">
                    <label for="Id_card" class="form-label">ລະຫັດບັດປະຈຳຕົວ</label>
                    <input type="text" id="Id_card" name="Id_card" class="form-control" value="<?= htmlspecialchars($customer['Id_card']) ?>" />
                </div>

                <div class="mb-3">
                    <label for="Email" class="form-label">ອີເມວ</label>
                    <input type="email" id="Email" name="Email" class="form-control" value="<?= htmlspecialchars($customer['Email']) ?>" required />
                </div>

                <div class="mb-3">
                    <label for="Type_of_ticket" class="form-label">ປະເພດປີ້</label>
                    <input type="text" id="Type_of_ticket" name="Type_of_ticket" class="form-control" value="<?= htmlspecialchars($customer['Type_of_ticket']) ?>" required />
                </div>

                <div class="mb-3">
                    <label for="Tel" class="form-label">ເບີໂທລະສັບ</label>
                    <input type="tel" id="Tel" name="Tel" class="form-control" value="<?= htmlspecialchars($customer['Tel']) ?>" required />
                </div>
   <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> ປັບປຸງຂໍ້ມູນ
                    </button>
                    <a href="customers.php" class="btn btn-secondary ms-2">
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
