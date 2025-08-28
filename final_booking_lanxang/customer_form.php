<?php
// รับข้อมูลเที่ยวบินจาก URL
$flight_id = $_GET['flight_id'] ?? '';
$depart_date = $_GET['date'] ?? '';
$depart_time = $_GET['time'] ?? '';

if (!$flight_id || !$depart_date || !$depart_time) {
    die("ຂໍ້ມູນເທື່ອບິນບໍ່ຄົບ");
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ຟອມການຈອງບິນ</title>
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background-color: #eef3f7;
            padding: 30px;
        }
        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        input, select {
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        label {
            font-weight: bold;
        }
        .btn {
            background-color: #002d74;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #001a4d;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">ຟອມການກອກຂໍ້ມູນຜູ້ໂດຍສານ</h2>

<form id="bookingForm" action="booking_finalize.php" method="post" onsubmit="return validateForm()">
    <input type="hidden" name="flight_id" value="<?= htmlspecialchars($flight_id) ?>">
    <input type="hidden" name="depart_date" value="<?= htmlspecialchars($depart_date) ?>">
    <input type="hidden" name="depart_time" value="<?= htmlspecialchars($depart_time) ?>">

    <label>ຊື່</label>
    <input type="text" name="Name" required>

    <label>ນາມສະກຸນ</label>
    <input type="text" name="Lname" required>

    <label>ເພດ</label>
    <select name="Gender" required>
        <option value="">-- ເລືອກເພດ --</option>
        <option value="ຊາຍ">ຊາຍ</option>
        <option value="ຍິງ">ຍິງ</option>
    </select>

    <label>ວັນເກີດ</label>
    <input type="date" name="Date_of_birth" required>

    <label>ສັນຊາດ</label>
    <input type="text" name="Nationality" required>

    <label>ປະເທດ</label>
    <input type="text" name="Country" required>

    <label>ທີ່ຢູ່</label>
    <input type="text" name="Address" required>

    <label>ເລກ Passport</label>
    <input type="text" name="Id_passport">

    <label>ເລກບັດປະຈຳຕົວ</label>
    <input type="text" name="Id_card">

    <label>ອີເມວ</label>
    <input type="email" name="Email" required>

    <label>ປະເພດບັດໂດຍສານ</label>
    <select name="Type_of_ticket" required>
        <option value="">-- ເລືອກປະເພດ --</option>
        <option value="ຜູ້ໃຫຍ່">ຜູ້ໃຫຍ່</option>
        <option value="ເດັກ">ເດັກ</option>
    </select>

    <label>ເບີໂທ</label>
    <input type="text" name="Tel" required>

    <button type="submit" class="btn">ດຳເນີນການຈອງ</button>
</form>

<script>
function validateForm() {
    const passport = document.querySelector('input[name="Id_passport"]').value.trim();
    const idCard = document.querySelector('input[name="Id_card"]').value.trim();

    if (!passport && !idCard) {
        alert("ກະລຸນາປ້ອນເລກ Passport ຫຼື ເລກບັດປະຈຳຕົວ ຢ່າງໃດຢ່າງໜຶ່ງ");
        return false;
    }
    return true;
}
</script>

</body>
</html>
