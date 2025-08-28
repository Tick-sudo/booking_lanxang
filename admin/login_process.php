<?php
session_start();
include '../db.php'; // Your DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $tel = $_POST['Password'];

    $sql = "SELECT * FROM employees1 WHERE Email = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $tel);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $employee = $result->fetch_assoc();
        $_SESSION['employee_id'] = $employee['ID'];
        $_SESSION['employee_name'] = $employee['Name'];
        header("Location: indexadmin.php");
        exit();
    } else {
        // ส่งกลับไปหน้า login พร้อมพารามิเตอร์ error
        header("Location: login.php?error=1");
        exit();
    }
}
?>
