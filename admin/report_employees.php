<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
$employee_name = $_SESSION['employee_name'];
include '../db.php';

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM employees1 
        WHERE ID LIKE '%$search%' 
           OR Name LIKE '%$search%' 
           OR Lname LIKE '%$search%' ";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>ລາຍງານພະນັກງານ</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    
    <a href="report_employees.php" class="active"><i class="fas fa-users"></i> ລາຍງານພະນັກງານ</a>
    

    <hr style="border-color: rgba(255,255,255,0.2);">
   
</div>

<!-- Topbar -->
<div class="topbar">
    <div><?= htmlspecialchars($employee_name) ?></div>
</div>

<!-- Content -->
<div class="content">
    <h3>ລາຍງານພະນັກງານ</h3>

    <form method="get" class="row g-2 align-items-center mb-4 no-print">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" style="max-width: 300px;" placeholder="ຄົ້ນຫາຊື່ / ID" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">ຄົ້ນຫາ</button>
    </div>
    <div class="col-auto">
        
      <button  type="button" onclick="window.print()"class="btn btn-dark">ພິມລາຍງານ</button>
      
    </div>
</form>


    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>ຊື່</th>
                    <th>ນາມສະກຸນ</th>
                    <th>ເພດ</th>
                    <th>ວັນ/ເດືອນ/ປີ ເກີດ</th>
                    <th>ບ້ານ/ເມືອງ/ແຂວງ</th>
                    <th>ລະດັບການສຶກສາ</th>
                    <th>ອີເມວ</th>
                    <th>ເບີ</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['ID'] ?></td>
                    <td><?= $row['Name'] ?></td>
                    <td><?= $row['Lname'] ?></td>
                    <td><?= $row['Gender'] ?></td>
                    <td><?= $row['Date_of_birth'] ?></td>
                    <td><?= $row['Address'] ?></td>
                    <td><?= $row['Education_background'] ?></td>
                    <td><?= $row['Email'] ?></td>
                    <td><?= $row['Tel'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-end mt-4 no-print">
         <a href="indexadmin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>ກັບຄືນ</a>
      
    </a>
</div>

    </div>
</div>

</body>
</html>
