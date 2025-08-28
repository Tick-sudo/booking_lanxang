<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $lname = trim($_POST['lname']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $tel = trim($_POST['tel']);
    $id_card = trim($_POST['id_card']);
    $id_passport = trim($_POST['id_passport']);
    $id_home = trim($_POST['id_home']);
    $address = trim($_POST['address']);
    $nationality = trim($_POST['nationality']);

    $identity_number = $id_card ?: ($id_passport ?: $id_home);

    if (empty($identity_number)) {
        $error = "ກະລຸນາປ້ອນ ບັດປະຈໍາຕົວ, Passport ຫຼື ເລກສຳມະໂນຄົວ ຢ່າງໃດຢ່າງໜຶ່ງ";
    } elseif ($password !== $password_confirm) {
        $error = "ລະຫັດຜ່ານບໍ່ຕົງກັນ";
    } else {
        $stmt = $conn->prepare("SELECT id FROM customer1 WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "ອີເມວນີ້ເຄີຍລົງທະບຽນແລ້ວ";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO customer1 (Name, Lname, Gender, Date_of_birth, Email, Password, Tel, Id_card, Address, Nationality) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $name, $lname, $gender, $dob, $email, $password_hash, $tel, $identity_number, $address, $nationality);

            if ($stmt->execute()) {
                $success = "ລົງທະບຽນສຳເລັດ!";
            } else {
                $error = "ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ລົງທະບຽນ - LANXANG AIRWAY</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background: url('images/fn1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #002d74;
        }
        .register-box {
            background: rgba(255, 255, 255, 0.95);
            max-width: 750px;
            margin: 60px auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 25px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
        }
        .form-col {
            flex: 1;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;

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
            margin-top: 10px;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .error {
            color: #c0392b;
        }
        .success {
            color: #27ae60;
        }
        a {
            text-decoration: none;
            color: #003366;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>ລົງທະບຽນສະມາຊິກ</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <p style="text-align:center;"><a href="login.php">ໄປຫາໜ້າເຂົ້າລະບົບ</a></p>
        <?php else: ?>
        <form method="post" action="register.php" novalidate>

            <div class="form-row">
                <div class="form-col">
                    <label>ຊື່</label>
                    <input type="text" name="name" required />
                </div>
                <div class="form-col">
                    <label>ນາມສະກຸນ</label>
                    <input type="text" name="lname" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label>ເພດ</label>
                    <select name="gender" required>
                        <option value="">-- ເລືອກເພດ --</option>
                        <option value="ຊາຍ">ຊາຍ</option>
                        <option value="ຍິງ">ຍິງ</option>
                    </select>
                </div>
                <div class="form-col">
                    <label>ວັນເດືອນປີເກີດ</label>
                    <input type="date" name="dob" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label>ອີເມວ</label>
                    <input type="email" name="email" required />
                </div>
                <div class="form-col">
                    <label>ເບີໂທ</label>
                    <input type="text" name="tel" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label>ລະຫັດຜ່ານ</label>
                    <input type="password" name="password" required />
                </div>
                <div class="form-col">
                    <label>ຢືນຢັນລະຫັດຜ່ານ</label>
                    <input type="password" name="password_confirm" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label>ເລກບັດປະຈໍາຕົວ</label>
                    <input type="text" name="id_card" />
                </div>
                <div class="form-col">
                    <label>Passport</label>
                    <input type="text" name="id_passport" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label>ເລກສຳມະໂນຄົວ</label>
                    <input type="text" name="id_home" />
                </div>
                <div class="form-col">
                    <label>ທີ່ຢູ່</label>
                    <input type="text" name="address" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label>ສັນຊາດ</label>
                    <input type="text" name="nationality" required />
                </div>
                <div class="form-col"></div>
            </div>

            <button type="submit">ລົງທະບຽນ</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
