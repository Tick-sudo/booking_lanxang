<?php
session_start();
include 'db.php';

// รับค่าต้นทางและปลายทางจาก SESSION ของขาไป
$fromm_go = $_SESSION['Fromm']; // ขาไป: ต้นทาง
$too_go = $_SESSION['Too'];     // ขาไป: ปลายทาง

// สำหรับขากลับ เราต้องสลับตำแหน่ง
$fromm = $too_go;   // ขากลับ: ต้นทาง = ปลายทางของขาไป
$too = $fromm_go;   // ขากลับ: ปลายทาง = ต้นทางของขาไป

// รับวันเดินทางขากลับ
$return_date = $_SESSION['return_date'] ?? date('Y-m-d');

// คำสั่ง SQL เพื่อค้นหาเที่ยวบินที่กลับ พร้อมวันที่เดินทางตรงกับ return_date
$sql = "SELECT * FROM flight1 
        WHERE Fromm = ? AND Too = ? AND Depart_date = ?
        ORDER BY Depart_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fromm, $too, $return_date);
$stmt->execute();
$result = $stmt->get_result();

// สมมติ adult, child เก็บไว้ใน session หรือส่งมาจากหน้าก่อนหน้า
$adult = $_SESSION['adult'] ?? 1;
$child = $_SESSION['child'] ?? 0;
?>

<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title>ເລືອກຖ້ຽວບິນຂາກັບ - LANXANG AIRWAY</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>ເລືອກຖ້ຽວບິນຂາກັບ: <?= htmlspecialchars($fromm) ?> ➝ <?= htmlspecialchars($too) ?></h2>
  <p>ວັນທີດິນທາງ: <?= htmlspecialchars($return_date) ?></p>

  <?php if ($result->num_rows > 0): ?>
    <form action="booking_customer.php" method="get">
      <table border="1">
        <tr>
          <th>ເລກທີບິນ</th>
          <th>ຈາກ</th>
          <th>ໄປຫາ</th>
          <th>ວັນທີ່ອອກ</th>
          <th>ເວລາອອກ</th>
          <th>ເລືອກ</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['Id_plane']) ?></td>
            <td><?= htmlspecialchars($row['Fromm']) ?></td>
            <td><?= htmlspecialchars($row['Too']) ?></td>
            <td><?= htmlspecialchars($row['Depart_date']) ?></td>
            <td><?= htmlspecialchars($row['Depart_time']) ?></td>
            <td>
              <input type="radio" name="Id_plane" value="<?= htmlspecialchars($row['Id_plane']) ?>" required>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>

      <!-- ส่งค่าที่ต้องใช้ต่อไปด้วย เช่น วันที่เดินทาง, เวลาขึ้นเครื่อง, จำนวนผู้โดยสาร -->
      <input type="hidden" name="depart_date" value="<?= htmlspecialchars($return_date) ?>">
      <input type="hidden" name="adult" value="<?= htmlspecialchars($adult) ?>">
      <input type="hidden" name="child" value="<?= htmlspecialchars($child) ?>">

      <button type="submit">ຖັດໄປ</button>
    </form>
  <?php else: ?>
    <p style="color:red">❌ ບໍ່ພົບເທື່ອບິນຂາກັບທີ່ກົງກັບເງື່ອນໄຂ</p>
  <?php endif; ?>

</body>
</html>
