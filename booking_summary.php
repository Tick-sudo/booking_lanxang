<?php
// รับข้อมูล
$id_plane = $_POST['id_plane'] ?? '';
$fromm = $_POST['fromm'] ?? '';
$too = $_POST['too'] ?? '';
$depart_date = $_POST['depart_date'] ?? '';
$adult = intval($_POST['adult'] ?? 0);
$child = intval($_POST['child'] ?? 0);

// ผู้โดยสาร
$passenger_name = $_POST['passenger_name'] ?? [];
$passenger_lname = $_POST['passenger_lname'] ?? [];
$passenger_gender = $_POST['passenger_gender'] ?? [];
$passenger_dob = $_POST['passenger_dob'] ?? [];
$passenger_type = $_POST['passenger_type'] ?? []; // <-- สำคัญ!

// ตรวจสอบ
$count = min(
    count($passenger_name),
    count($passenger_lname),
    count($passenger_gender),
    count($passenger_dob),
    count($passenger_type)
);
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ສະຫຼຸບການຈອງ - LANXANG AIRWAY</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Noto Sans Lao', sans-serif;
            background: url('images/bg-plane.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .summary-container {
            max-width: 800px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        h2, h3 {
            color: #003366;
        }

        ul {
            padding-left: 20px;
            font-size: 17px;
        }

        button {
            background-color: #003366;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #00509e;
        }
    </style>
</head>
<body>
<div class="summary-container">
    <h2>ສະຫຼຸບການຈອງຕົ້ນທາງ</h2>
    <p><strong>ເລກທີ່ບິນ:</strong> <?= htmlspecialchars($id_plane) ?></p>
    <p><strong>ເສັ້ນທາງ:</strong> <?= htmlspecialchars($fromm) ?> → <?= htmlspecialchars($too) ?></p>
    <p><strong>ວັນທີ່ເດີນທາງ:</strong> <?= htmlspecialchars($depart_date) ?></p>
    <p><strong>ຈຳນວນຜູ້ໂດຍສານ:</strong> ຜູ້ໃຫຍ່ <?= $adult ?> ຄົນ, ເດັກນ້ອຍ <?= $child ?> ຄົນ</p>

    <h3>ລາຍຊື່ຜູ້ໂດຍສານ</h3>
    <ul>
        <?php 
        for ($i = 0; $i < $count; $i++) {
            echo "<li><strong>ຊື່:</strong> " . htmlspecialchars($passenger_name[$i]) .
                 " " . htmlspecialchars($passenger_lname[$i]) .
                 " (" . htmlspecialchars($passenger_gender[$i]) .
                 ", " . htmlspecialchars($passenger_dob[$i]) .
                 ", " . ($passenger_type[$i] === "Child" ? "ເດັກນ້ອຍ" : "ຜູ້ໃຫຍ່") . ")</li>";
        }
        ?>
    </ul>

    <form action="booking_confirm.php" method="post">
        <input type="hidden" name="id_plane" value="<?= htmlspecialchars($id_plane) ?>">
        <input type="hidden" name="fromm" value="<?= htmlspecialchars($fromm) ?>">
        <input type="hidden" name="too" value="<?= htmlspecialchars($too) ?>">
        <input type="hidden" name="depart_date" value="<?= htmlspecialchars($depart_date) ?>">
        <input type="hidden" name="adult" value="<?= $adult ?>">
        <input type="hidden" name="child" value="<?= $child ?>">

        <?php
        for ($i = 0; $i < $count; $i++) {
            echo "<input type='hidden' name='passenger_name[]' value='" . htmlspecialchars($passenger_name[$i]) . "'>";
            echo "<input type='hidden' name='passenger_lname[]' value='" . htmlspecialchars($passenger_lname[$i]) . "'>";
            echo "<input type='hidden' name='passenger_gender[]' value='" . htmlspecialchars($passenger_gender[$i]) . "'>";
            echo "<input type='hidden' name='passenger_dob[]' value='" . htmlspecialchars($passenger_dob[$i]) . "'>";
            echo "<input type='hidden' name='passenger_type[]' value='" . htmlspecialchars($passenger_type[$i]) . "'>";
        }
        ?>
        <button type="submit" name="confirm">ຢືນຢັນການຈອງ</button>
    </form>
</div>
</body>
</html>
