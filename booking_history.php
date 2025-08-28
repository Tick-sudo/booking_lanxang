<?php
session_start();
if (!isset($_SESSION['customer_email'])) {
    echo "<script>alert('ກະລຸນາລ໋ອກອິນກ່ອນ'); window.location='login.php';</script>";
    exit;
}

include 'db.php';

$customer_email = $_SESSION['customer_email'];

// SQL ดึงข้อมูลการจองของลูกค้าโดยใช้ email เชื่อมตาราง customer1 และ booking1 กับ flight1
$sql = "SELECT b.Booking_id, b.Name, b.Lname, b.Booking_date, 
               f.Id_plane, f.Fromm, f.Too, f.Depart_time, f.Boarding_time, f.Price
        FROM booking1 b
        JOIN flight1 f ON b.Id_plane = f.Id_plane
        JOIN customer1 c ON b.Customer_id = c.ID
        WHERE c.Email = ?
        ORDER BY b.Booking_id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ປະຫວັດການຈອງ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">ປະຫວັດການຈອງຂອງທ່ານ</h2>

    <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>ເລກການຈອງ</th>
                <th>ຊື່ຜູ້ໂດຍສານ</th>
                <th>ເລກໄຟລ໌</th>
                <th>ເສັ້ນທາງ</th>
                <th>ເວລາເດີນທາງ</th>
                <th>ວັນທີຈອງ</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['Booking_id']) ?></td>
                <td><?= htmlspecialchars($row['Name'] . ' ' . $row['Lname']) ?></td>
                <td><?= htmlspecialchars($row['Id_plane']) ?></td>
                <td><?= htmlspecialchars($row['Fromm']) ?> → <?= htmlspecialchars($row['Too']) ?></td>
                <td><?= htmlspecialchars($row['Depart_time']) ?></td>
                <td><?= htmlspecialchars($row['Booking_date']) ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">ບໍ່ພົບຂໍ້ມູນການຈອງ</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary">ກັບໄປໜ້າຫຼັກ</a>
</div>

</body>
</html>
