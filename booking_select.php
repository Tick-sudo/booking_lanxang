<?php
session_start();
include 'db.php';

// รับข้อมูลพื้นฐาน
$trip_type = $_GET['trip_type'] ?? 'oneway';
$adult = isset($_GET['adult']) ? intval($_GET['adult']) : 1;
$child = isset($_GET['child']) ? intval($_GET['child']) : 0;
$infant = isset($_GET['infant']) ? intval($_GET['infant']) : 0;
$depart_date = $_GET['depart_date'] ?? '';
$depart_time = $_GET['depart_time'] ?? '';

$return_date = $_GET['return_date'] ?? '';
$fromm = $_GET['fromm'] ?? '';
$too = $_GET['too'] ?? '';
$fromm_return = $_GET['fromm_return'] ?? ($too ?: '');
$too_return = $_GET['too_return'] ?? ($fromm ?: '');
$selected_outbound = $_GET['selected_outbound'] ?? '';
$selected_return = $_GET['selected_return'] ?? '';

function formatPrice($price) {
    return number_format($price, 0) . " ກີບ";
}


// ฟังก์ชัน getFlights() วางไว้ที่นี่เลย
function getFlights($conn, $from, $to, $departTime = null) {
    if ($departTime) {
        $sql = "SELECT Id_plane, Fromm, Too, Depart_time, Price, Price_child, Price_infant FROM flight1 WHERE Fromm = ? AND Too = ? AND Depart_time = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $from, $to, $departTime);
    } else {
        $sql = "SELECT Id_plane, Fromm, Too, Depart_time, Price, Price_child, Price_infant FROM flight1 WHERE Fromm = ? AND Too = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $from, $to);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $flights = [];
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
    return $flights;
}





// ตรวจสอบข้อมูลจำเป็นเบื้องต้น
if (!$fromm || !$too || !$depart_date) {
    die("❌ ຂໍ້ມູນບໍ່ຄົບ (ຕົ້ນທາງ, ປາຍທາງ, ວັນທີ່ໄປ)");
}

if ($trip_type === 'round' && !$return_date) {
    die("❌ ກະລຸນາໃສ່ວັນທີ່ກັບ");
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ເລືອກຖ້ຽວບິນ - ສາຍການບິນລ້ານຊ້າງ</title>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Noto Sans Lao', sans-serif;
      background: url('images/fn1.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #002d74;
    }
    .container {
      max-width: 900px;
      margin: 80px auto 60px auto;
      background: rgba(255 255 255 / 0.95);
      padding: 35px 40px 50px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 30px rgb(0 45 116 / 0.4);
    }
    h2 {
      text-align: center;
      font-weight: 800;
      font-size: 28px;
      color: #002d74;
      margin-bottom: 30px;
      letter-spacing: 1.3px;
    }
    .passenger-info {
      font-size: 16px;
      margin-bottom: 25px;
      text-align: center;
      font-weight: 600;
      color: #d21e1e;
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 14px;
      font-size: 15px;
    }
    thead th {
      background-color: #002d74;
      color: #fff;
      padding: 15px 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      border-radius: 8px 8px 0 0;
    }
    tbody tr {
      background: #f9fbfd;
      box-shadow: 0 3px 9px rgb(0 45 116 / 0.1);
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }
    tbody tr:hover {
      background-color: #ffe9e9;
    }
    tbody td {
      text-align: center;
      padding: 14px 10px;
      vertical-align: middle;
      font-weight: 600;
      color: #002d74;
      border: none;
    }
    .btn {
      background-color: #d21e1e;
      color: white;
      font-weight: 700;
      border-radius: 30px;
      padding: 10px 30px;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 6px 16px rgb(210 30 30 / 0.6);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .btn:hover {
      background-color: #a91717;
      box-shadow: 0 8px 20px rgb(169 23 23 / 0.9);
    }
    @media (max-width: 768px) {
      .container {
        margin: 40px 15px 30px 15px;
        padding: 30px 20px 30px 20px;
      }
      table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
      }
      thead tr { display: none; }
      tbody tr {
        margin-bottom: 20px;
        background: #f9fbfd;
        box-shadow: none;
        border-radius: 10px;
        padding: 15px;
      }
      tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        font-weight: 600;
        border-bottom: 1px solid #ddd;
        margin-bottom: 8px;
      }
      tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 700;
        color: #d21e1e;
        white-space: nowrap;
      }
      .btn {
        width: 100%;
        text-align: center;
        font-size: 17px;
        padding: 14px 0;
        box-shadow: none;
        margin-top: 8px;
      }
    }
  </style>
</head>
<body>
  <div class="container">

    <h2>
      <?php
      if ($trip_type === 'round') {
          echo $selected_outbound ? "ເລືອກຖ້ຽວບິນຂາກັບ" : "ເລືອກຖ້ຽວບິນຂາໄປ";
      } else {
          echo "ເລືອກຖ້ຽວບິນໄປຢ່າງດຽວ";
      }
      ?>
    </h2>

    <div class="passenger-info">
      ຜູ້ໃຫຍ່: <?= $adult ?> ຄົນ &nbsp;&nbsp; | &nbsp;&nbsp;
      ເດັກນ້ອຍ: <?= $child ?> ຄົນ &nbsp;&nbsp; | &nbsp;&nbsp;
      ແອນ້ອຍ: <?= $infant ?> ຄົນ
    </div>

    <?php
  if ($trip_type === 'round' && $selected_outbound) {
    $result = getFlights($conn, $fromm_return, $too_return, $depart_time);
} else {
    $result = getFlights($conn, $fromm, $too, $depart_time);
}
?>

    


   <?php if (count($result) > 0): ?>
<table>
  <thead>
    <tr>
      <th>ເລກທີ່ບິນ</th>
      <th>ຈາກ</th>
      <th>ໄປຫາ</th>
      <th>ເວລາອອກ</th>
      <th>ລາຄາຜູ້ໃຫຍ່</th>
      <?php if ($child > 0): ?><th>ລາຄາເດັກນ້ອຍ (50%)</th><?php endif; ?>
      <?php if ($infant > 0): ?><th>ລາຄາແອນ້ອຍ (30%)</th><?php endif; ?>
      <th>ເລືອກ</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($result as $row):
     $price_adult = $row['Price'];
    $price_child = $child > 0 ? $row['Price_child'] : null;
    $price_infant = $infant > 0 ? $row['Price_infant'] : null;

      $max_seats = isset($row['max_seats']) ? (int)$row['max_seats'] : 40;
      $booked_seats = isset($row['booked_seats']) ? (int)$row['booked_seats'] : 0;
      $depart_date_for_link = $depart_date;  // แก้ชื่อไม่ให้ซ้ำกับตัวแปรอื่น
    ?>
    <tr>
      <td data-label="ເລກທີ່ບິນ"><?= htmlspecialchars($row['Id_plane']) ?></td>
      <td data-label="ຈາກ"><?= htmlspecialchars($row['Fromm']) ?></td>
      <td data-label="ໄປຫາ"><?= htmlspecialchars($row['Too']) ?></td>
      <td data-label="ເວລາອອກ"><?= htmlspecialchars(date('H:i', strtotime($row['Depart_time']))) ?></td>
      <td data-label="ລາຄາຜູ້ໃຫຍ່"><?= formatPrice($price_adult) ?></td>
      <?php if ($child > 0): ?><td data-label="ລາຄາເດັກນ້ອຍ (50%)"><?= formatPrice($price_child) ?></td><?php endif; ?>
      <?php if ($infant > 0): ?><td data-label="ລາຄາແອນ້ອຍ (30%)"><?= formatPrice($price_infant) ?></td><?php endif; ?>
      <td data-label="ເລືອກ">
  <?php if ($booked_seats >= $max_seats): ?>
    <span style="color:red; font-weight:bold;">ໄຟລທ໌ນີ້ເຕັມແລ້ວ</span>
  <?php else: ?>
    <?php
      // เก็บพารามิเตอร์ทั้งหมดไว้
    $params = [
      'trip_type' => $trip_type,
      'adult' => $adult,
      'child' => $child,
      'infant' => $infant,
      'depart_date' => $depart_date_for_link,
      'fromm' => $fromm,
      'too' => $too,
      'selected_outbound' => $selected_outbound ?: $row['Id_plane'],
      'selected_return' => $selected_outbound ? $row['Id_plane'] : '',
      'depart_time' => $row['Depart_time'],
      'boarding_time' => $row['Boarding_time'] ?? '',
      ];

      // ถ้ายังไม่ล็อกอิน
      if (!isset($_SESSION['customer_id'])) {
          $link = 'login.php?redirect=' . urlencode('booking_customer.php?' . http_build_query($params));
      } else {
          $link = 'booking_customer.php?' . http_build_query($params);
      }
    ?>
    <a class="btn" href="<?= $link ?>">ເລືອກ</a>
  <?php endif; ?>
</td>

    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <p style="text-align:center; color:#d21e1e; font-weight:700; margin-top: 40px;">
    ❌ ບໍ່ພົບເທື່ອບິນທີ່ຕ້ອງການ
  </p>
<?php endif; ?>

  </div>
</body>
</html>
