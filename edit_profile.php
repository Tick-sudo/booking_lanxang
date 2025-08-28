<?php
session_start();
include 'db.php';

// เช็คล็อกอิน
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$error = '';
$success = '';

// ดึงข้อมูลลูกค้าปัจจุบันเพื่อแสดงในฟอร์ม
$stmt = $conn->prepare("SELECT Name, Lname, Gender, Date_of_birth, Email, Tel, Id_card, Address, Nationality FROM customer1 WHERE ID = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "❌ ไม่พบข้อมูลลูกค้า";
    exit;
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name = trim($_POST['name']);
    $lname = trim($_POST['lname']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $id_card = trim($_POST['id_card']);
    $address = trim($_POST['address']);
    $nationality = trim($_POST['nationality']);

    // ตรวจสอบว่ามี email ซ้ำไหม (ยกเว้นตัวเอง)
    $stmt_check = $conn->prepare("SELECT ID FROM customer1 WHERE Email = ? AND ID != ?");
    $stmt_check->bind_param("si", $email, $customer_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $error = "อีเมลนี้ถูกใช้งานแล้ว";
    } else {
        // อัพเดตข้อมูลลูกค้า
        $stmt_update = $conn->prepare("UPDATE customer1 SET Name=?, Lname=?, Gender=?, Date_of_birth=?, Email=?, Tel=?, Id_card=?, Address=?, Nationality=? WHERE ID=?");
        $stmt_update->bind_param("sssssssssi", $name, $lname, $gender, $dob, $email, $tel, $id_card, $address, $nationality, $customer_id);

        if ($stmt_update->execute()) {
            $success = "แก้ไขข้อมูลสำเร็จ";
            $_SESSION['customer_name'] = $name; // อัปเดตชื่อใน session
            // โหลดข้อมูลใหม่แสดงบนฟอร์ม
            $user = [
                'Name' => $name,
                'Lname' => $lname,
                'Gender' => $gender,
                'Date_of_birth' => $dob,
                'Email' => $email,
                'Tel' => $tel,
                'Id_card' => $id_card,
                'Address' => $address,
                'Nationality' => $nationality,
            ];
        } else {
            $error = "เกิดข้อผิดพลาดในการอัพเดตข้อมูล";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ແກ້ໄຂຂໍ້ມູນສ່ວນຕົວ - LANXANG AIRWAY</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background: url('img/bg_airway.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0; padding: 0;
        }
        .edit-box {
            background: rgba(255, 255, 255, 0.95);
            max-width: 450px;
            margin: 60px auto;
            padding: 30px 35px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 25px;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0 14px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            width: 100%;
            background-color: #003366;
            color: white;
            padding: 12px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .error {
            color: #c0392b;
            text-align: center;
            margin-bottom: 15px;
        }
        .success {
            color: #27ae60;
            text-align: center;
            margin-bottom: 15px;
        }
        label {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="edit-box">
        <h2>ແກ້ໄຂຂໍ້ມູນສ່ວນຕົວ</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" action="edit_profile.php" novalidate>
            <label>ຊື່</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['Name']) ?>" required />

            <label>ນາມສະກຸນ</label>
            <input type="text" name="lname" value="<?= htmlspecialchars($user['Lname']) ?>" required />

            <label>ເພດ</label>
            <select name="gender" required>
                <option value="">-- ເລືອກເພດ --</option>
                <option value="ຊາຍ" <?= $user['Gender'] == 'ຊາຍ' ? 'selected' : '' ?>>ຊາຍ</option>
                <option value="ຍິງ" <?= $user['Gender'] == 'ຍິງ' ? 'selected' : '' ?>>ຍິງ</option>
                <option value="ອື່ນໆ" <?= $user['Gender'] == 'ອື່ນໆ' ? 'selected' : '' ?>>ອື່ນໆ</option>
            </select>

            <label>ວັນເດືອນປີເກີດ</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($user['Date_of_birth']) ?>" required />

            <label>ອີເມວ</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" required />

            <label>ເບີໂທ</label>
            <input type="text" name="tel" value="<?= htmlspecialchars($user['Tel']) ?>" required />

            <label>ເລກບັດປະຈໍາຕົວ / Passport</label>
            <input type="text" name="id_card" value="<?= htmlspecialchars($user['Id_card']) ?>" required />

            <label>ທີ່ຢູ່</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['Address']) ?>" required />

            <label>ສັນຊາດ</label>
            <input type="text" name="nationality" value="<?= htmlspecialchars($user['Nationality']) ?>" required />

            <button type="submit">ບັນທຶກການແກ້ໄຂ</button>
        </form>
    </div>
</body>
</html>
