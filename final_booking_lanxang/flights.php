<?php
include 'db.php';

$fromm = $_GET['fromm'] ?? '';
$too = $_GET['too'] ?? '';
$depart_date = $_GET['depart_date'] ?? '';

if (empty($fromm) || empty($too) || empty($depart_date)) {
    die("ກະລຸນາໃສ່ຂໍ້ມູນຕົ້ນທາງ ແລະ ວັນທີ່ໄປໃຫ້ຄົບ");
}

// ดึงเที่ยวบินจากฐานข้อมูล
$sql = "SELECT * FROM flight1 WHERE Fromm = ? AND Too = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $fromm, $too);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ຜົນການຄົ້ນຫາທີ່ບິນ</title>
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background-color: #f4f8fb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .flight {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            background: #e9f0f8;
        }
        .flight h3 {
            margin: 0 0 10px 0;
            color: #002d74;
        }
        .btn-select {
            background: #002d74;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-select:hover {
            background: #001b4d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>ຜົນການຄົ້ນຫາເທື່ອບິນ</h2>
    <p>ຈາກ: <strong><?= htmlspecialchars($fromm) ?></strong> ໄປ: <strong><?= htmlspecialchars($too) ?></strong> ໃນວັນທີ່: <strong><?= htmlspecialchars($depart_date) ?></strong></p>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="flight">
                <h3>ເວລາອອກບິນ: <?= htmlspecialchars($row['Depart_time']) ?></h3>
                <p>ເຮືອບິນ: <?= htmlspecialchars($row['Id_plane']) ?> | ລາຄາ: <?= htmlspecialchars($row['Price']) ?> USD</p>
                <p>ເດືອນ/ວັນ/ປີ ກັບເວລາຈະເປັນ: <strong><?= htmlspecialchars($depart_date . ' ' . $row['Depart_time']) ?></strong></p>
                <a class="btn-select" href="customer_form.php?flight_id=<?= urlencode($row['Id_plane']) ?>&date=<?= urlencode($depart_date) ?>&time=<?= urlencode($row['Depart_time']) ?>">ເລືອກເທື່ອບິນນີ້</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>ບໍ່ມີເທື່ອບິນທີ່ຄົ້ນພົບ</p>
    <?php endif; ?>
</div>

</body>
</html>
