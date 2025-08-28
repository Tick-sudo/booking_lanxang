<?php
include 'db.php';
date_default_timezone_set('Asia/Vientiane');

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจาก booking.php
    $id_plane = $_POST['id_plane'];
    $fromm = $_POST['fromm'];
    $too = $_POST['too'];
    $travel_date = $_POST['travel_date'];
    $depart_time = $_POST['depart_time'];
    $price = $_POST['price'];

    // ถ้ากดส่งฟอร์มลูกค้า
    if (isset($_POST['submit_customer'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $tel = mysqli_real_escape_string($conn, $_POST['tel']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // 1. บันทึกลง customer1
        $sql1 = "INSERT INTO customer1 (Name, Lname, Gender, Date_of_birth, Tel, Email) 
                 VALUES ('$name', '$lname', '$gender', '$dob', '$tel', '$email')";
        if (mysqli_query($conn, $sql1)) {
            $customer_id = mysqli_insert_id($conn); // รับ ID ลูกค้าคนล่าสุด

            // 2. บันทึกลง booking1
            $sql2 = "INSERT INTO booking1 (Customer_id, Id_plane, Fromm, Too, Depart_date, Depart_time, Price)
                     VALUES ('$customer_id', '$id_plane', '$fromm', '$too', '$travel_date', '$depart_time', '$price')";
            if (mysqli_query($conn, $sql2)) {
                header("Location: booking_confirm.php?success=1");
                exit();
            } else {
                $error = "Error booking: " . mysqli_error($conn);
            }
        } else {
            $error = "Error customer: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ລົງທະບຽນລູກຄ້າ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-center">ຂໍ້ມູນການຈອງປີ້</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- แสดงรายละเอียดเที่ยวบิน -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">ຂໍ້ມູນເທື່ອບິນ</div>
        <div class="card-body">
            <p><strong>ລະຫັດຍົນ:</strong> <?= htmlspecialchars($id_plane) ?></p>
            <p><strong>ເສັ້ນທາງ:</strong> <?= htmlspecialchars($fromm) ?> → <?= htmlspecialchars($too) ?></p>
            <p><strong>ວັນທີ່ເດີນທາງ:</strong> <?= date('d/m/Y', strtotime($travel_date)) ?></p>
            <p><strong>ເວລາອອກເດີນທາງ:</strong> <?= $depart_time ?></p>
            <p><strong>ລາຄາ:</strong> <?= number_format($price) ?> ກີບ</p>
        </div>
    </div>

    <!-- ฟอร์มกรอกข้อมูลลูกค้า -->
    <form method="POST" class="card p-4 shadow-sm">
        <h5 class="mb-3">ກະລຸນາກອກຂໍ້ມູນຂອງທ່ານ</h5>
        <input type="hidden" name="id_plane" value="<?= $id_plane ?>">
        <input type="hidden" name="fromm" value="<?= $fromm ?>">
        <input type="hidden" name="too" value="<?= $too ?>">
        <input type="hidden" name="travel_date" value="<?= $travel_date ?>">
        <input type="hidden" name="depart_time" value="<?= $depart_time ?>">
        <input type="hidden" name="price" value="<?= $price ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ຊື່</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">ນາມສະກຸນ</label>
                <input type="text" name="lname" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">ເພດ</label>
                <select name="gender" class="form-select" required>
                    <option value="">-- ເລືອກເພດ --</option>
                    <option value="ຊາຍ">ຊາຍ</option>
                    <option value="ຍິງ">ຍິງ</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">ວັນເກີດ</label>
                <input type="date" name="dob" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">ເບີໂທ</label>
                <input type="text" name="tel" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>

        <button type="submit" name="submit_customer" class="btn btn-success">ລົງທະບຽນ</button>
    </form>
</div>
</body>
</html>
