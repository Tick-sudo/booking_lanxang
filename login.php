<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT ID, Name, Password, Email FROM customer1 WHERE TRIM(Email) = ?");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['Password'])) {
            $_SESSION['customer_id'] = $user['ID'];
            $_SESSION['customer_name'] = $user['Name'];
            $_SESSION['customer_email'] = $user['Email']; // เก็บอีเมลด้วย

            header("Location: booking.php");
            exit;
        } else {
            $error = "❌ ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ";
        }
    } else {
        $error = "❌ ບໍ່ພົບອີເມວນີ້ໃນລະບົບ";
    }
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ເຂົ້າລະບົບ - LANXANG AIRWAY</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Noto Sans Lao', sans-serif;
            background: url('images/fn1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #002d74;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            max-width: 400px;
            margin: 100px auto;
            padding: 30px 35px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
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
            margin-bottom: 15px;
            text-align: center;
        }
        p.register-link {
            text-align: center;
            margin-top: 15px;
        }
        p.register-link a {
            color: #003366;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>ເຂົ້າສູ່ລະບົບ</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="login.php" novalidate>
            <input type="email" name="email" placeholder="ອີເມວ" required />
            <input type="password" name="password" placeholder="ລະຫັດຜ່ານ" required />
            <button type="submit">ເຂົ້າສູ່ລະບົບ</button>
        </form>
        <p class="register-link">ບໍ່ມີບັນຊີ? <a href="register.php">ລົງທະບຽນທີນີ້</a></p>
    </div>
</body>
</html>
