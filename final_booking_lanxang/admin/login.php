<?php
session_start();
if (isset($_SESSION['employee_Email'])) {
    header("Location: indexadmin.php");
    exit();
}

$error_msg = "";
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error_msg = "Invalid email or phone. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login - AIRLINE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f0f4ff; /* สีฟ้าจาง ๆ เหมือนธีม dashboard */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      width: 400px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      border-radius: 1rem;
      background: white;
      padding: 40px 30px 30px 30px;
      position: relative;
      text-align: center;
    }
    .login-logo {
      position: absolute;
      top: -55px;
      left: 50%;
      transform: translateX(-50%);
      background: #4469d8; /* สีฟ้าธีม */
      width: 110px;
      height: 110px;
      border-radius: 50%;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4rem;
      box-shadow: 0 5px 20px rgba(68,105,216,0.6);
      border: 4px solid #2c4caa;
    }
    .login-title {
      margin-top: 70px;
      margin-bottom: 30px;
      font-weight: 700;
      font-size: 1.8rem;
      color: #2e3a59;
      letter-spacing: 1.2px;
    }
    label {
      font-weight: 600;
      color: #4e5d78;
    }
    .btn-primary {
      background-color: #4469d8;
      border: none;
      font-weight: 600;
      padding: 10px;
      font-size: 1.1rem;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #3351b1;
    }
    .alert-danger {
      font-weight: 600;
      font-size: 0.95rem;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-logo">
      <i class="fas fa-plane"></i>
    </div>

    <div class="login-title">LANXANG AIRWAY ADMIN LOGIN</div>

    <?php if ($error_msg): ?>
      <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error_msg) ?>
      </div>
    <?php endif; ?>

  <form method="POST" action="login_process.php" autocomplete="off" novalidate>
  <div class="mb-4 text-start">
    <label for="email">Email</label>
    <input 
      type="email" id="email" name="email" class="form-control" 
      placeholder="ກະລຸນາປ້ອນອີເມວ" required autofocus
      autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
      value="" />
  </div>
  <div class="mb-4 text-start">
    <label for="tel">Password</label>
    <input 
      type="password" id="tel" name="tel" class="form-control" 
      placeholder="ກະລຸນາປ້ອນລະຫັດຜ່ານ" required
      autocomplete="new-password" autocorrect="off" autocapitalize="off" spellcheck="false"
      value="" />
  </div>
  <button type="submit" class="btn btn-primary w-100">ເຂົ້າສູ່ລະບົບ</button>
</form>

  </div>
</body>
</html>
