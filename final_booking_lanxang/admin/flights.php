<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
include '../db.php';

$success = "";
$error = "";

$sql = "SELECT * FROM Flight1";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flights - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
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
    <div><?= htmlspecialchars($employee_name) ?></div>
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4">ຖ້ຽວບິນ</h2>

    <!-- Toast Notifications -->
    <div class="toast-container">
        <?php if (!empty($success)): ?>
            <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><?= $success ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><?= $error ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Flight Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-plane"></i> ຂໍ້ມູນຖ້ຽວບິນ</h5>
            <a href="flight_add.php" class="btn btn-light btn-sm"><i class="fas fa-plus"></i> ເພີ່ມຖ້ຽວບິນ</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="flightTable" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ລະຫັດຍົນ</th>
                            <th>ຕົ້ນທາງ</th>
                            <th>ປາຍທາງ</th>
                          
                            <th>ເວລາ</th>
                            <th>ລາຄາ</th>
                            <th>ຕົວເລືອກ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($flight = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($flight['Id_plane']) ?></td>
                                <td><?= htmlspecialchars($flight['Fromm']) ?></td>
                                <td><?= htmlspecialchars($flight['Too']) ?></td>
                                
                                  <td><?= htmlspecialchars($flight['Depart_time']) ?></td>
                                   <td><?= htmlspecialchars($flight['Price']) ?></td>
                                <td>
                                <a href="flight_edit.php?id=<?= urlencode($flight['Id_plane']) ?>" class="btn btn-primary btn-sm">
    <i class="fas fa-edit"></i>
</a>
<a href="flight_delete.php?id=<?= urlencode($flight['Id_plane']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this flight?');">
    <i class="fas fa-trash-alt"></i>
</a>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#flightTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search..."
            }
        });
    });
</script> -->
</body>
</html>
