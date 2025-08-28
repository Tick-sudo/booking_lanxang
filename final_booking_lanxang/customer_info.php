<?php
// รับข้อมูลจากหน้า book_flight.php
$from = $_GET['fromm'] ?? '';
$to = $_GET['too'] ?? '';
$departure_date = $_GET['departure_date'] ?? '';
$adults = $_GET['adults'] ?? 1;
$children = $_GET['children'] ?? 0;
$total_passengers = $adults + $children;
?>

<!DOCTYPE html>
<html>
<head>
    <title>กรอกข้อมูลผู้โดยสาร</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>กรอกข้อมูลผู้โดยสาร</h2>
    <form action="insert_booking.php" method="POST">
        <input type="hidden" name="fromm" value="<?= htmlspecialchars($from) ?>">
        <input type="hidden" name="too" value="<?= htmlspecialchars($to) ?>">
        <input type="hidden" name="departure_date" value="<?= htmlspecialchars($departure_date) ?>">
        <input type="hidden" name="adults" value="<?= $adults ?>">
        <input type="hidden" name="children" value="<?= $children ?>">
        <input type="hidden" name="total_passengers" value="<?= $total_passengers ?>">

        <?php for ($i = 1; $i <= $total_passengers; $i++): ?>
            <h5 class="mt-4">ผู้โดยสารที่ <?= $i ?></h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>ชื่อ:</label>
                    <input type="text" name="name[]" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label>นามสกุล:</label>
                    <input type="text" name="lname[]" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>เพศ:</label>
                <select name="gender[]" class="form-control" required>
                    <option value="ชาย">ชาย</option>
                    <option value="หญิง">หญิง</option>
                </select>
            </div>
            <div class="form-group">
                <label>วันเกิด:</label>
                <input type="date" name="dob[]" class="form-control" required>
            </div>
            <div class="form-group">
                <label>ที่อยู่:</label>
                <textarea name="address[]" class="form-control" required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>อีเมล:</label>
                    <input type="email" name="email[]" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label>เบอร์โทร:</label>
                    <input type="text" name="tel[]" class="form-control" required>
                </div>
            </div>
            <hr>
        <?php endfor; ?>

        <button type="submit" class="btn btn-success">ยืนยันการจอง</button>
    </form>
</div>
</body>
</html>
