<?php
include 'db.php';

// ดึงข้อมูลสนามบินจากตาราง flight1 (Fromm, Too)
$airports = [];
$sql = "SELECT DISTINCT Fromm FROM flight1 UNION SELECT DISTINCT Too FROM flight1";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $airports[] = $row['Fromm'];
    }
    $airports = array_unique($airports);
    sort($airports);
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ຈອງຕົ໋ວລ່ວງໜ້າ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Noto Sans Lao', sans-serif;
            background: url('images/fn1.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .topbar {
            background-color: #002d74;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .topbar nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }
        .lang-switcher {
            background: white;
            color: #002d74;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .form-container {
            background: white;
            padding: 25px;
            max-width: 1000px;
            margin: 80px auto;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
        }
        .form-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .form-row select, .form-row input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            background: #002d74;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
        .counter {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .counter label {
            min-width: 70px;
        }
        .counter button {
            background-color: #002d74;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        .counter input {
            width: 40px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            pointer-events: none; /* readonly */
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">ສາຍການບິນລ້ານຊ້າງ</div>
    <nav>
        <a href="#">ໜ້າຫຼັກ</a>
        <a href="#">ຂໍ້ມູນການບິນ</a>
        <a href="#">ຈອງຕົ໋ວ</a>
        <a href="#">ກ່ຽວກັບພວກເຮົາ</a>
        <button class="lang-switcher" onclick="switchLang()">EN / ລາວ</button>
    </nav>
</div>

<div class="form-container">
    <h2>✈ ຈອງຕົ໋ວຂອງທ່ານ</h2>
    <form action="flights.php" method="GET" onsubmit="return validateForm()">
        <div class="form-row">
            <select name="trip_type" id="tripType" required>
                <option value="round">ໄປ-ກັບ</option>
                <option value="oneway">ໄປຢ່າງດຽວ</option>
            </select>

            <select name="fromm" id="fromm" required>
                <option value="">-- ຕົ້ນທາງ --</option>
                <?php foreach ($airports as $airport): ?>
                    <option value="<?= htmlspecialchars($airport) ?>"><?= htmlspecialchars($airport) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="too" id="too" required>
                <option value="">-- ປາຍທາງ --</option>
                <?php foreach ($airports as $airport): ?>
                    <option value="<?= htmlspecialchars($airport) ?>"><?= htmlspecialchars($airport) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <input type="date" name="depart_date" id="departDate" required>
            <input type="date" name="return_date" id="returnDate">
        </div>

        <div class="form-row">
            <div class="counter">
                <label>ຜູ້ໃຫຍ່</label>
                <button type="button" onclick="changeValue('adultCount', -1)">-</button>
                <input type="text" name="adult" id="adultCount" value="1" readonly>
                <button type="button" onclick="changeValue('adultCount', 1)">+</button>
            </div>

            <div class="counter">
                <label>ເດັກນ້ອຍ</label>
                <button type="button" onclick="changeValue('childCount', -1)">-</button>
                <input type="text" name="child" id="childCount" value="0" readonly>
                <button type="button" onclick="changeValue('childCount', 1)">+</button>
            </div>

            <div class="counter">
                <label>ແຮກເກີດ</label>
                <button type="button" onclick="changeValue('infantCount', -1)">-</button>
                <input type="text" name="infant" id="infantCount" value="0" readonly>
                <button type="button" onclick="changeValue('infantCount', 1)">+</button>
            </div>
        </div>

        <div class="form-row">
            <select name="cabin" required>
                <option value="economy">ທຸລະກິດ / ປະຫວັດ</option>
            </select>
            <select name="currency" required>
                <option value="usd">USD</option>
                <option value="lak">LAK</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">ຄົ້ນຫາ</button>
    </form>
</div>

<script>
    function toggleReturnDate() {
        const tripType = document.getElementById('tripType').value;
        const returnDate = document.getElementById('returnDate');
        if (tripType === 'oneway') {
            returnDate.classList.add('hidden');
            returnDate.value = '';
        } else {
            returnDate.classList.remove('hidden');
        }
    }

    function changeValue(id, delta) {
        const input = document.getElementById(id);
        let value = parseInt(input.value) || 0;
        value += delta;

        if (id === 'adultCount') {
            if (value < 1) value = 1;  // ผู้ใหญ่ขั้นต่ำ 1 คน
        } else {
            if (value < 0) value = 0;
        }

        input.value = value;
    }

    function validateForm() {
        const fromm = document.getElementById('fromm').value;
        const too = document.getElementById('too').value;
        if (fromm === too) {
            alert('ຕົ້ນທາງ ແລະ ປາຍທາງຕ້ອງບໍ່ເທົ່າກັນ');
            return false;
        }

        const tripType = document.getElementById('tripType').value;
        const departDate = document.getElementById('departDate').value;
        const returnDate = document.getElementById('returnDate').value;

        if (!departDate) {
            alert('ກະລຸນາເລືອກວັນທີ່ໄປ');
            return false;
        }

        if (tripType === 'round') {
            if (!returnDate) {
                alert('ກະລຸນາເລືອກວັນທີ່ກັບ');
                return false;
            }
            if (returnDate < departDate) {
                alert('ວັນທີ່ກັບຕ້ອງບໍ່ນ້ອຍກວ່າວັນທີ່ໄປ');
                return false;
            }
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('tripType').addEventListener('change', toggleReturnDate);
        toggleReturnDate();

        // ป้องกันเลือกต้นทางกับปลายทางซ้ำกันแบบ real-time
        document.getElementById('fromm').addEventListener('change', () => {
            const fromm = document.getElementById('fromm').value;
            const too = document.getElementById('too');
            if (fromm === too.value) {
                too.value = '';
            }
        });
        document.getElementById('too').addEventListener('change', () => {
            const too = document.getElementById('too').value;
            const fromm = document.getElementById('fromm');
            if (too === fromm.value) {
                fromm.value = '';
            }
        });
    });

    function switchLang() {
        alert('ຟັງຊັນປ່ຽນພາສາຈະຖືກເພີ່ມໃນອະນາຄົດ');
    }
</script>

</body>
</html>
