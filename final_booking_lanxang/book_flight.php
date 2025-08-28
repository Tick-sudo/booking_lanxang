<!-- book_flight.php -->
<?php
$conn = new mysqli("localhost", "root", "", "booking_lx");
$flights = $conn->query("SELECT * FROM flight1");
?>
<!DOCTYPE html>
<html>
<head>
    <title>จองตั๋วเครื่องบิน</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>ค้นหาเที่ยวบิน</h2>
    <form action="customer_info.php" method="GET">
        <div class="form-group">
            <label>ต้นทาง:</label>
            <select name="fromm" class="form-control" required>
                <?php while($row = $flights->fetch_assoc()): ?>
                    <option value="<?= $row['Fromm'] ?>"><?= $row['Fromm'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>ปลายทาง:</label>
            <select name="too" class="form-control" required>
                <?php
                $flights->data_seek(0); // กลับไปแถวแรกใหม่
                while($row = $flights->fetch_assoc()): ?>
                    <option value="<?= $row['Too'] ?>"><?= $row['Too'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>วันที่เดินทาง:</label>
            <input type="date" name="departure_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>จำนวนผู้ใหญ่:</label>
            <input type="number" name="adults" class="form-control" min="1" value="1">
        </div>
        <div class="form-group">
            <label>จำนวนเด็ก:</label>
            <input type="number" name="children" class="form-control" min="0" value="0">
        </div>
        <button type="submit" class="btn btn-primary">ถัดไป</button>
    </form>
</div>
</body>
</html>
