<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Name = trim(mysqli_real_escape_string($conn, $_POST['Name']));
    $Lname = trim(mysqli_real_escape_string($conn, $_POST['Lname']));
    $Gender = trim(mysqli_real_escape_string($conn, $_POST['Gender']));
    $Date_of_birth = trim(mysqli_real_escape_string($conn, $_POST['Date_of_birth']));
    $Nationality = trim(mysqli_real_escape_string($conn, $_POST['Nationality']));
    $Country = trim(mysqli_real_escape_string($conn, $_POST['Country']));
    $Address = trim(mysqli_real_escape_string($conn, $_POST['Address']));
    $Id_passport = trim(mysqli_real_escape_string($conn, $_POST['Id_passport']));
    $Id_card = trim(mysqli_real_escape_string($conn, $_POST['Id_card']));
    $Email = trim(mysqli_real_escape_string($conn, $_POST['Email']));
    $Tel = trim(mysqli_real_escape_string($conn, $_POST['Tel']));

    // ตรวจสอบข้อมูลครบทุกช่องหรือไม่
    if ($Name === '' || $Lname === '' || $Gender === '' || $Date_of_birth === '' || $Nationality === '' || 
        $Country === '' || $Address === '' || $Id_passport === '' || $Id_card === '' || $Email === '' || $Tel === '') {
        $error = "ກະລຸນາປ້ອນຂໍ້ມູນທຸກຊ່ອງໃຫ້ຄົບຖືກຕ້ອງກ່ອນການລົງທະບຽນ";
    } else {
        // ถ้าครบทุกช่อง บันทึกข้อมูลได้เลย
        $sql = "INSERT INTO customer1 
            (Name, Lname, Gender, Date_of_birth, Nationality, Country, Address, Id_passport, Id_card, Email, Tel) 
            VALUES 
            ('$Name', '$Lname', '$Gender', '$Date_of_birth', '$Nationality', '$Country', '$Address', '$Id_passport', '$Id_card', '$Email', '$Tel')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['customer_registered'] = true;
             header("Location: flights.php");
            $success = "ທ່ານໄດ້ລົງທະບຽນສຳເລັດແລ້ວ!";
        } else {
            $error = "ມີຂໍ້ຜິດພາດໃນການລົງທະບຽນ: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ຟອມລົງທະບຽນລູກຄ້າ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f0f8ff;
        }
        .register-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            border-top: 5px solid #007bff;
        }
        .register-title {
            color: #007bff;
            margin-bottom: 25px;
            font-weight: 700;
            text-align: center;
            font-size: 1.8rem;
        }
        label {
            font-weight: 600;
            color: #333;
        }
        .btn-submit {
            background-color: #007bff;
            border: none;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .alert {
            max-width: 700px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<div class="register-container shadow-sm">
    <h2 class="register-title">ຟອມລົງທະບຽນລູກຄ້າ</h2>

    <?php if ($error) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success) : ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="Name" class="form-label">ຊື່</label>
                <input type="text" class="form-control" id="Name" name="Name" required />
            </div>
            <div class="col-md-6">
                <label for="Lname" class="form-label">ນາມສະກຸນ</label>
                <input type="text" class="form-control" id="Lname" name="Lname" required />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="Gender" class="form-label">ເພດ</label>
                <select class="form-select" id="Gender" name="Gender" required>
                    <option value="" selected>--ເລືອກ--</option>
                    <option value="ຊາຍ">ຊາຍ</option>
                    <option value="ຍິງ">ຍິງ</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="Date_of_birth" class="form-label">ວັນເກີດ</label>
                <input type="date" class="form-control" id="Date_of_birth" name="Date_of_birth" required />
            </div>
            <div class="col-md-4">
                <label for="Nationality" class="form-label">ສັນຊາດ</label>
                <input type="text" class="form-control" id="Nationality" name="Nationality" required />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="Country" class="form-label">ປະເທດ</label>
                <input type="text" class="form-control" id="Country" name="Country" required />
            </div>
            <div class="col-md-6">
                <label for="Address" class="form-label">ທີ່ຢູ່</label>
                <input type="text" class="form-control" id="Address" name="Address" required />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="Id_passport" class="form-label">ເລກທະບຽນພາສະປອດ</label>
                <input type="text" class="form-control" id="Id_passport" name="Id_passport" required />
            </div>
            <div class="col-md-6">
                <label for="Id_card" class="form-label">ເລກບັດປະຈໍາຕົວ</label>
                <input type="text" class="form-control" id="Id_card" name="Id_card" required />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="Email" class="form-label">ອີເມວ</label>
                <input type="email" class="form-control" id="Email" name="Email" required />
            </div>
            
        </div>

        <div class="mb-3">
            <label for="Tel" class="form-label">ເບີໂທ</label>
            <input type="tel" class="form-control" id="Tel" name="Tel" required />
        </div>

        <button type="submit" class="btn btn-submit w-100 py-2">ລົງທະບຽນ</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
