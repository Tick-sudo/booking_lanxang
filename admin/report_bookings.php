<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
$employee_name = $_SESSION['employee_name'];
include '../db.php';

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$sql = "SELECT * FROM booking1 WHERE 1";

// เงื่อนไขช่วงวันที่
if (!empty($from) && !empty($to)) {
    $sql .= " AND Booking_date BETWEEN '$from' AND '$to'";
} elseif (!empty($from)) {
    $sql .= " AND Booking_date >= '$from'";
} elseif (!empty($to)) {
    $sql .= " AND Booking_date <= '$to'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>ລາຍງານການຈອງ (ຊ່ວງວັນທີ)</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            width: 224px;
            background: linear-gradient(180deg,rgb(68, 105, 216));
            color: white;
            position: fixed;
            height: 100%;
            padding-top: 20px;
        }
        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .topbar {
            margin-left: 224px;
            background-color: #f8f9fc;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e3e6f0;
        }
        .content {
            margin-left: 224px;
            padding: 30px;
            background-color: #f1f4f9;
            min-height: 100vh;
        }
        @media print {
            .sidebar, .topbar, .no-print {
                display: none !important;
            }
            .content {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fas fa-plane-departure"></i> LANXANG AIRWAY <br>ADMIN</h4>
    
    <a href="booking_report.php" class="active"><i class="fas fa-calendar-check"></i> ລາຍງານການຈອງ</a>
    <hr style="border-color: rgba(255,255,255,0.2);">
    
</div>

<!-- Topbar -->
<div class="topbar">
    <div><?= htmlspecialchars($employee_name) ?></div>
</div>

<!-- Content -->
<div class="content">
    <h3>ລາຍງານການຈອງ</h3>

    <form method="get" class="row g-2 align-items-center mb-4 no-print">
        <div class="col-auto">
            <label for="from" class="form-label mb-0">ຈາກວັນທີ:</label>
            <input type="date" id="from" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
        </div>
        <div class="col-auto">
            <label for="to" class="form-label mb-0">ເຖິງວັນທີ:</label>
            <input type="date" id="to" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mt-4">ຄົ້ນຫາ</button>
        </div>
        <div class="col-auto">
            
            <button  type="button" onclick="window.print()"class="btn btn-dark mt-4">ພິມລາຍງານ</button>

        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ລະຫັດ</th>
                    <th>ຊື່</th>
                    <th>ນາມສະກຸນ</th>
                    <th>ລະຫັດຍົນ</th>
                    <th>ປາຍທາງ</th>
                    <th>ຕົ້ນທາງ</th>
                    <th>ວັນ/ເດືອນ/ປີ ເດີນທາງ</th>
                    <th>ວັນ/ເດືອນ/ປີ ການຈອງ</th>
                    <th>ປະເພດປີ້</th>
                    <th>ສະຖານະການຈອງ</th>
                    <th>ເບີໂທລະສັບ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Booking_id']) ?></td>
                        <td><?= htmlspecialchars($row['Name']) ?></td>
                        <td><?= htmlspecialchars($row['Lname']) ?></td>
                        <td><?= htmlspecialchars($row['Id_plane']) ?></td>
                        <td><?= htmlspecialchars($row['Fromm']) ?></td>
                        <td><?= htmlspecialchars($row['Too']) ?></td>
                        <td><?= htmlspecialchars($row['Depart_date']) ?></td>
                        <td><?= htmlspecialchars($row['Booking_date']) ?></td>
                        <td><?= htmlspecialchars($row['User_type']) ?></td>
                        <td><?= htmlspecialchars($row['Booking_status']) ?></td>
                        <td><?= htmlspecialchars($row['Tel']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-danger">ບໍ່ພົບຂໍ້ມູນ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-end mt-4 no-print">
            <a href="indexadmin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ກັບຄືນ</a>
        </div>
    </div>
</div>

</body>
</html>
