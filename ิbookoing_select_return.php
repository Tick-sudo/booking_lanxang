<?php
session_start();
include 'db.php';

// รับค่าจาก booking_select.php (เที่ยวไป) พร้อมข้อมูล return flight
$fromm = $_GET['fromm'] ?? '';
$too = $_GET['too'] ?? '';
$depart_date = $_GET['depart_date'] ?? '';
$return_date = $_GET['return_date'] ?? '';
$trip_type = $_GET['trip_type'] ?? 'roundtrip';

$adult = isset($_GET['adult']) ? intval($_GET['adult']) : 1;
$child = isset($_GET['child']) ? intval($_GET['child']) : 0;
$infant = isset($_GET['infant']) ? intval($_GET['infant']) : 0;

$id_plane_depart = $_GET['id_plane_depart'] ?? ''; // เที่ยวบินขาไปที่เลือกมาแล้ว

if (!$fromm || !$too || !$depart_date || !$return_date || !$id_plane_depart) {
    die("❌ ຂໍ້ມູນບໍ່ຄົບ");
}

// ดึงข้อมูลเที่ยวบินขากลับ (Too -> Fromm) วันที่ตรงกับ $return_date
$sql = "SELECT * FROM flight1 WHERE Fromm = ? AND Too = ? AND Depart_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $too, $fromm, $return_date);
$stmt->execute();
$result = $stmt->get_result();

function formatDateTime($date, $time) {
    $dt = date_create_from_format('Y-m-d H:i:s', $date . ' ' . $time);
    if ($dt === false) return htmlspecialchars($date . ' ' . $time);
    return date_format($dt, 'd/m/Y H:i');
}

?>
<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ເລືອກເທື່ອບິນຂາກັບ - ສາຍການບິນລ້ານຊ້າງ</title>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Noto Sans Lao', sans-serif;
      background: url('images/fn1.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #002d74;
      min-height: 100vh;
    }
    .container {
      max-width: 960px;
      margin: 80px auto;
      background: rgba(255,255,255,0.95);
      padding: 35px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 30px rgb(0 45 116 / 0.4);
    }
    h2 {
      text-align: center;
      font-size: 28px;
      color: #002d74;
      margin-bottom: 30px;
      font-weight: 800;
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
      border-radius: 8px;
      box-shadow: 0 3px 9px rgb(0 45 116 / 0.1);
      transition: background-color 0.3s ease;
    }
    tbody tr:hover {
      background-color: #ffe9e9;
    }
    tbody td {
      text-align: center;
      padding: 14px 10px;
      font-weight: 600;
      color: #002d74;
    }
    .btn {
      background-color: #d21e1e;
      color: white;
      font-weight: 700;
      border-radius: 30px;
      padding: 10px 25px;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 6px 16px rgb(210 30 30 / 0.6);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .btn:hover {
      background-color: #a91717;
      box-shadow: 0 8px 20px rgb(169 23 23 / 0.9);
    }
    @media (max-width: 600px) {
      table, thead, tbody, tr, th, td {
        display: block;
        width: 100%;
      }
      thead tr {
        display: none;
      }
      tbody tr {
        margin-bottom: 20px;
      }
      tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
      }
      tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        top: 14px;
        font-weight: 700;
        color: #002d74;
        white-space: nowrap;
      }
    }
  </style>
</head>
<body>
  <div class="container" role="main">
    <h2>ເລືອກເທື່ອບິນຂາກັບ</h2>
    <div class="passenger-info" aria-live="polite" aria-atomic="true">
      ຜູ້ໃຫຍ່: <?= $adult ?> ຄົນ | ເດັກນ້ອຍ: <?= $child ?> ຄົນ | ແອນ້ອຍ: <?= $infant ?> ຄົນ
    </div>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ເລກທີ່ບິນ</th>
            <th>ຈາກ</th>
            <th>ໄປຫາ</th>
            <th>ເວລາອອກ</th>
            <th>ຜູ້ໃຫຍ່</th>
            <th>ເດັກ</th>
            <th>ແອນ້ອຍ</th>
            <th>ເລືອກ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()):
            $price_adult = $row['Price'];
            $price_child = round($price_adult * 0.5);
            $price_infant = round($price_adult * 0.2);
            $depart_time_formatted = formatDateTime($row['Depart_date'], $row['Depart_time']);
          ?>
          <tr>
            <td data-label="ເລກທີ່ບິນ"><?= htmlspecialchars($row['Id_plane']) ?></td>
            <td data-label="ຈາກ"><?= htmlspecialchars($row['Fromm']) ?></td>
            <td data-label="ໄປຫາ"><?= htmlspecialchars($row['Too']) ?></td>
            <td data-label="ເວລາອອກ"><?= $depart_time_formatted ?></td>
            <td data-label="ຜູ້ໃຫຍ່"><?= number_format($price_adult) ?> ກີບ</td>
            <td data-label="ເດັກ">
              <?php if ($child > 0): ?>
                <?= number_format($price_child) ?> ກີບ
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
            <td data-label="ແອນ້ອຍ">
              <?php if ($infant > 0): ?>
                <?= number_format($price_infant) ?> ກີບ
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
            <td data-label="ເລືອກ">
              <?php
              // ลิงก์ไปหน้า booking_customer.php พร้อมส่ง id_plane_depart และ id_plane_return
              $link = "booking_customer.php?"
                  . "id_plane_depart=" . urlencode($id_plane_depart)
                  . "&id_plane_return=" . urlencode($row['Id_plane'])
                  . "&fromm=" . urlencode($fromm)
                  . "&too=" . urlencode($too)
                  . "&depart_date=" . urlencode($depart_date)
                  . "&return_date=" . urlencode($return_date)
                  . "&adult=" . $adult
                  . "&child=" . $child
                  . "&infant=" . $infant;
              ?>
              <a class="btn" href="<?= $link ?>">ເລືອກ</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="text-align:center; color:#d21e1e; font-weight:700;">❌ ບໍ່ພົບເທື່ອບິນຂາກັບ</p>
    <?php endif; ?>
  </div>
</body>
</html>
