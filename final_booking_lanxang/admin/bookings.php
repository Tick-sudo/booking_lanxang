<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
$employee_name = $_SESSION['employee_name'];
include '../db.php';

$sql = "SELECT * FROM booking1";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookings - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f4f9;
        }

        .sidebar {
            width: 224px;
            background: linear-gradient(180deg, rgb(68, 105, 216));
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

        .management-title {
            font-size: 12px;
            font-weight: bold;
            padding: 8px 20px;
            color: #cbd5e1;
        }

        .topbar {
            margin-left: 224px;
            background-color: #f8f9fc;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid #e3e6f0;
        }

        .content {
            margin-left: 224px;
            padding: 30px;
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fas fa-plane-departure"></i> LANXANG AIRWAY<br>ADMIN</h4>

    <a href="indexadmin.php"><i class="fas fa-tachometer-alt"></i> ໜ້າຫຼັກ</a>
    <div class="management-title">ຈັດການ</div>
    <a href="employees.php"><i class="fas fa-users"></i> ພະນັກງານ</a>
    <a href="customers.php"><i class="fas fa-user"></i> ລູກຄ້າ</a>
    <a href="bookings.php"><i class="fas fa-calendar-check"></i> ການຈອງ</a>
    <a href="tickets.php"><i class="fas fa-ticket-alt"></i> ປີ້ຍົນ</a>
    <a href="flights.php"><i class="fas fa-plane"></i> ຖ້ຽວບິນ</a>
    
    <hr style="border-color: rgba(255,255,255,0.2);">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> ອອກລະບົບ</a>
</div>

<!-- Topbar -->
<div class="topbar">
    <div><?php echo htmlspecialchars($employee_name); ?></div>
</div>

<!-- Main Content -->
<div class="content">
    <h2 class="mb-4">ການຈອງ</h2>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-check"></i> ຂໍ້ມູນການຈອງ</h5>
            <a href="booking_add.php" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> ເພີ່ມການຈອງ
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="bookingTable" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ລະຫັດການຈອງ</th>
                            <th>ຊື່</th>
                            <th>ນາມສະກຸນ</th>
                            <th>ລະຫັດຍົນ</th>
                            <th>ຕົ້ນທາງ</th>
                            <th>ປາຍທາງ</th>
                            <th>ວັນ/ເດືອນ/ປີ ການຈອງ</th>
                            <th>ສະຖານະການຈອງ</th>
                            <th>ເບີໂທລະສັບ</th>
                            <th>ຕົວເລືອກ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Booking_id']) ?></td>
                                <td><?= htmlspecialchars($row['Name']) ?></td>
                                <td><?= htmlspecialchars($row['Lname']) ?></td>
                                <td><?= htmlspecialchars($row['Id_plane']) ?></td>
                                <td><?= htmlspecialchars($row['Fromm']) ?></td>
                                <td><?= htmlspecialchars($row['Too']) ?></td>
                                <td><?= htmlspecialchars($row['Booking_date']) ?></td>
                                <td><?= htmlspecialchars($row['Booking_status']) ?></td>
                                <td><?= htmlspecialchars($row['Tel']) ?></td>
                                <td>
                                    <a href="booking_edit.php?Booking_id=<?= urlencode($row['Booking_id']) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="booking_delete.php?Booking_id=<?= urlencode($row['Booking_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#bookingTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search..."
            }
        });
    });
</script> -->
</body>
</html>
