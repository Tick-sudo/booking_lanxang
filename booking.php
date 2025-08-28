<?php
// ເປີດ session ເພື່ອເກັບຂໍ້ມູນຜູ້ໃຊ້ (ເຊັ່ນ ອີເມວ ແລະ ຕຳແໜ່ງການເຂົ້າລະບົບ)
session_start();  
include 'db.php';

// ດຶງຂໍ້ມູນຖ້ຽວບິນຈາກຕາລາງ flight1 ໂດຍລວມຊ່ອງ Fromm ແລະ Too ແລ້ວຈັດລຳດັບຕາມອັກສອນ
$sql = "SELECT Fromm AS airport FROM flight1 UNION SELECT Too AS airport FROM flight1 ORDER BY airport ASC";
$result = $conn->query($sql);
$airports = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $airports[] = $row['airport'];
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <title>ຈອງຕົ໋ວລ່ວງໜ້າ - ສາຍການບິນລ້ານຊ້າງ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
    <style>
         /* Reset ຄ່າຕ່າງໆ ເພື່ອຄວບຄຸມການສະແດງ */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Noto Sans Lao', sans-serif;
            background: url('images/fn1.jpg') no-repeat center center fixed;
            background-size: cover;/* ປັບຂະໜາດພາບໃຫ້ຄົງຮູບ */
            color: #222;/* ສີຂໍ້ຄວາມ */
            min-height: 100vh;
        }
        /* Topbar */
        .topbar {
            background-color: #002d74;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between; /* ຈັດແຖວໃຫ້ແບ່ງຊ່ວງ */
            align-items: center; /* ຈັດແຖວກັນໃຫ້ກົງກັນ */
            position: fixed; /* ເຄື່ອນຢູ່ບໍ່ໄປຂ້າງຫນ້າ */
            top: 0; left: 0; right: 0;
            z-index: 1000; /* ກຳນົດລຳດັບການປະຕິເສດໃຫ້ສູງ */
            box-shadow: 0 2px 10px rgba(0,0,0,0.5); /* ເພີ່ມເນື້ອງເປັນເນື້ອງງານ */
            align-items: center;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .topbar .logo {
            font-size: 24px;
            font-weight: 700;
            user-select: none;/* ບໍ່ໃຫ້ເລືອກຂໍ້ຄວາມ */
        }
        .topbar nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none; /* ບໍ່ໃຫ້ມີແຖວເສັ້ນລຸ່ມ */
            font-weight: 600;
            transition: color 0.3s ease; /* ເຮັດໃຫ້ສີປ່ຽນໄດ້ແບບຈຳນວນນ້ອຍ */
            user-select: none;
        }
        .topbar nav a:hover {
            color: #f7b733;
        }
        .lang-switcher {
            background: white;
            color: #002d74;
            border: none;
            padding: 5px 12px;
            border-radius: 5px;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.3s ease;
        }
        .lang-switcher:hover {
            background-color: #f7b733;
            color: #002d74;
        }
        /* Form container */
        .form-container {
            background: rgba(255,255,255,0.95);
            padding: 30px 35px;
            max-width: 1000px;
            margin: 100px auto 60px auto;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
        }
        .form-container h2 {
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 28px;
            color: #002d74;
            user-select: none;
        }
        /* Form rows */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        .form-row select,
        .form-row input[type="date"],
        .form-row input[type="text"] {
            flex: 1 1 200px;
            padding: 12px 14px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s ease;
            user-select: text;
        }
        .form-row select:focus,
        .form-row input[type="date"]:focus,
        .form-row input[type="text"]:focus {
            border-color: #f7b733;
            outline: none;
        }
        /* Submit button */
        .submit-btn {
            background-color: #002d74;
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
            user-select: none;
        }
        .submit-btn:hover {
            background-color: #f7b733;
            color: #002d74;
        }
        /* Hidden */
        .hidden {
            display: none !important;
        }
        /* Counter style */
        .counter {
            display: flex;
            align-items: center;
            gap: 8px;
            user-select: none;
        }
        .counter label {
            min-width: 80px;
            font-weight: 600;
            color: #002d74;
        }
        .counter button {
            background-color: #002d74;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .counter button:hover {
            background-color: #f7b733;
            color: #002d74;
        }
        .counter input {
            width: 48px;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 6px;
            pointer-events: none;
            background-color: #f9f9f9;
            font-weight: 700;
            font-size: 16px;
        }
        /* Responsive */
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
            }
            .form-row select,
            .form-row input[type="date"],
            .form-row input[type="text"] {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
<?php
session_start(); // ต้องมีบรรทัดนี้เสมอเพื่ออ่านค่า session
?>

<div class="topbar">
    <div class="logo">ສາຍການບິນລ້ານຊ້າງ</div>
    <nav>
        <a href="#">ໜ້າຫຼັກ</a>
        
        <a href="booking_history.php" class="btn btn-primary">ປະຫວັດການຈອງ</a>
    

        <!-- เมนูโปรไฟล์ -->
        <div class="dropdown" style="display:inline-block; position:relative;">
            <a href="#" class="profile-icon" onclick="toggleProfileMenu(event)">
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" 
                     alt="Profile" 
                     style="width:30px; height:30px; border-radius:50%; vertical-align:middle;">
            </a>
            <div class="dropdown-menu" id="profileMenu" 
                 style="display:none; position:absolute; right:0; top:40px; background:#fff; border:1px solid #ccc; border-radius:5px; min-width:200px; box-shadow:0 2px 6px rgba(0,0,0,0.2); z-index:100;">
                 
               <?php if (isset($_SESSION['customer_email'])): ?>
    <div style="padding:10px; border-bottom:1px solid #eee; font-size:14px;">
        <strong>ກຳລັງລ໋ອກອິນ:</strong><br>
        <span style="color: black;">
            <?php echo htmlspecialchars($_SESSION['customer_email']); ?>
        </span>
                    <a href="logout.php" style="display:block; padding:10px; text-decoration:none; color:#333;">
                        ອອກຈາກລະບົບ
                    </a>
                <?php else: ?>
                    <a href="login.php" style="display:block; padding:10px; text-decoration:none; color:#333;">
                        ເຂົ້າສູ່ລະບົບ
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>

<script>
function toggleProfileMenu(event) {
    event.preventDefault();
    var menu = document.getElementById('profileMenu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}
</script>

    <main class="form-container" role="main" aria-labelledby="formTitle">
        <h2 id="formTitle">✈ ຖ້ຽວບິນ</h2>
        <form action="booking_select.php" method="GET" onsubmit="return validateForm()">
            
            <div class="form-row">
                <select name="trip_type" id="tripType" required aria-label="ເລືອກປະເພດການເດີນທາງ">
                    <option value="round">ໄປ-ກັບ</option>
                    <option value="oneway">ໄປຢ່າງດຽວ</option>
                </select>
            </div>

            <!-- ขาไป -->
            <div class="form-row" id="goingRoute">
                <select name="fromm" id="fromm" required aria-label="ຕົ້ນທາງ (ຂາໄປ)">
                    <option value="">-- ຕົ້ນທາງ (ຂາໄປ) --</option>
                    <?php foreach ($airports as $airport): ?>
                        <option value="<?= htmlspecialchars($airport, ENT_QUOTES) ?>"><?= htmlspecialchars($airport) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="too" id="too" required aria-label="ປາຍທາງ (ຂາໄປ)">
                    <option value="">-- ປາຍທາງ (ຂາໄປ) --</option>
                    <?php foreach ($airports as $airport): ?>
                        <option value="<?= htmlspecialchars($airport, ENT_QUOTES) ?>"><?= htmlspecialchars($airport) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- วันที่ขาไป และ วันที่ขากลับ -->
            <div class="form-row">
                <input type="date" name="depart_date" id="departDate" required aria-label="ວັນທີ່ໄປ">
                  
                <div id="returnDateWrapper">
                    <input type="date" name="return_date" id="returnDate" aria-label="ວັນທີ່ກັບ">
                </div>
            </div>

            <!-- ขากลับ -->
            <div class="form-row hidden" id="returnRoute">
                <select name="fromm_return" id="fromm_return" aria-label="ຕົ້ນທາງ (ຂາກັບ)">
                    <option value="">-- ຕົ້ນທາງ (ຂາກັບ) --</option>
                    <?php foreach ($airports as $airport): ?>
                        <option value="<?= htmlspecialchars($airport, ENT_QUOTES) ?>"><?= htmlspecialchars($airport) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="too_return" id="too_return" aria-label="ປາຍທາງ (ຂາກັບ)">
                    <option value="">-- ປາຍທາງ (ຂາກັບ) --</option>
                    <?php foreach ($airports as $airport): ?>
                        <option value="<?= htmlspecialchars($airport, ENT_QUOTES) ?>"><?= htmlspecialchars($airport) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="counter" role="group" aria-labelledby="adultLabel">
                    <label id="adultLabel">ຜູ້ໃຫຍ່</label>
                    <button type="button" aria-label="ລົດຈຳນວນຜູ້ໃຫຍ່" onclick="changeValue('adultCount', -1)">-</button>
                    <input type="text" name="adult" id="adultCount" value="1" readonly aria-live="polite" aria-atomic="true" />
                    <button type="button" aria-label="ເພີ່ມຈຳນວນຜູ້ໃຫຍ່" onclick="changeValue('adultCount', 1)">+</button>
                </div>

                <div class="counter" role="group" aria-labelledby="childLabel">
                    <label id="childLabel">ເດັກນ້ອຍ</label>
                    <button type="button" aria-label="ລົດຈຳນວນເດັກນ້ອຍ" onclick="changeValue('childCount', -1)">-</button>
                    <input type="text" name="child" id="childCount" value="0" readonly aria-live="polite" aria-atomic="true" />
                    <button type="button" aria-label="ເພີ່ມຈຳນວນເດັກນ້ອຍ" onclick="changeValue('childCount', 1)">+</button>
                </div>

                <div class="counter" role="group" aria-labelledby="infantLabel">
                    <label id="infantLabel">ແອນ້ອຍ</label>
                    <button type="button" aria-label="ລົດຈຳນວນແອນ້ອຍ" onclick="changeValue('infantCount', -1)">-</button>
                    <input type="text" name="infant" id="infantCount" value="0" readonly aria-live="polite" aria-atomic="true" />
                    <button type="button" aria-label="ເພີ່ມຈຳນວນແອນ້ອຍ" onclick="changeValue('infantCount', 1)">+</button>
                </div>
            </div>

           

            <button type="submit" class="submit-btn" aria-label="ຄົ້ນຫາເຖິງບິນ">ຄົ້ນຫາ</button>

        </form>
    </main>

<script>
    function toggleReturnDate() {
        const tripType = document.getElementById('tripType').value;
        const returnDateWrapper = document.getElementById('returnDateWrapper');
        if (tripType === 'oneway') {
            returnDateWrapper.classList.add('hidden');
            document.getElementById('returnDate').value = '';
        } else {
            returnDateWrapper.classList.remove('hidden');
        }
    }

    function toggleReturnRoute() {
        const tripType = document.getElementById('tripType').value;
        const returnRoute = document.getElementById('returnRoute');

        if (tripType === 'round') {
            returnRoute.classList.remove('hidden');
        } else {
            returnRoute.classList.add('hidden');
            // เคลียร์ค่าเมื่อซ่อน
            document.getElementById('fromm_return').value = '';
            document.getElementById('too_return').value = '';
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
        const tripType = document.getElementById('tripType').value;

        // เช็คขาไป
        const fromm = document.getElementById('fromm').value;
        const too = document.getElementById('too').value;
        if (fromm === too) {
            alert('ຕົ້ນທາງ (ຂາໄປ) ແລະ ປາຍທາງ (ຂາໄປ) ຕ້ອງບໍ່ເທົ່າກັນ');
            return false;
        }

        if (!fromm || !too) {
            alert('ກະລຸນາເລືອກຕົ້ນທາງ ແລະ ປາຍທາງ ຂາໄປ');
            return false;
        }

        // เช็ควันที่เดินทาง
        const departDate = document.getElementById('departDate').value;
        if (!departDate) {
            alert('ກະລຸນາເລືອກວັນທີ່ໄປ');
            return false;
        }

        if (tripType === 'round') {
            // เช็ควันที่ขากลับ
            const returnDate = document.getElementById('returnDate').value;
            if (!returnDate) {
                alert('ກະລຸນາເລືອກວັນທີ່ກັບ');
                return false;
            }
            if (returnDate < departDate) {
                alert('ວັນທີ່ກັບຕ້ອງບໍ່ນ້ອຍກວ່າວັນທີ່ໄປ');
                return false;
            }

            // เช็คต้นทาง-ปลายทางขากลับ
            const frommReturn = document.getElementById('fromm_return').value;
            const tooReturn = document.getElementById('too_return').value;
            if (frommReturn === tooReturn) {
                alert('ຕົ້ນທາງ (ຂາກັບ) ແລະ ປາຍທາງ (ຂາກັບ) ຕ້ອງບໍ່ເທົ່າກັນ');
                return false;
            }
            if (!frommReturn || !tooReturn) {
                alert('ກະລຸນາເລືອກຕົ້ນທາງ ແລະ ປາຍທາງ ຂາກັບ');
                return false;
            }
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('tripType').addEventListener('change', () => {
            toggleReturnDate();
            toggleReturnRoute();
        });
        toggleReturnDate();
        toggleReturnRoute();

        // ตั้งค่า min วันที่เดินทาง และวันที่กลับ
        const today = new Date().toISOString().split('T')[0];
        const departDate = document.getElementById('departDate');
        const returnDate = document.getElementById('returnDate');

        departDate.setAttribute('min', today);

        departDate.addEventListener('change', () => {
            if (departDate.value) {
                returnDate.setAttribute('min', departDate.value);
                if (returnDate.value < departDate.value) {
                    returnDate.value = '';
                }
            }
        });

        // ป้องกันเลือกต้นทางกับปลายทางซ้ำกันแบบ real-time ขาไป
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
        document.getElementById('fromm').addEventListener('change', () => {
    const fromm = document.getElementById('fromm').value;
    const too = document.getElementById('too').value;
    // เซ็ตขากลับ: fromm_return = too
    document.getElementById('fromm_return').value = too;
         });
         document.getElementById('too').addEventListener('change', () => {
    const fromm = document.getElementById('fromm').value;
    const too = document.getElementById('too').value;
    // เซ็ตขากลับ: too_return = fromm
    document.getElementById('too_return').value = fromm;
});

        // ป้องกันเลือกต้นทางกับปลายทางซ้ำกันแบบ real-time ขากลับ
        document.getElementById('fromm_return').addEventListener('change', () => {
            const frommR = document.getElementById('fromm_return').value;
            const tooR = document.getElementById('too_return');
            if (frommR === tooR.value) {
                tooR.value = '';
            }
        });
        document.getElementById('too_return').addEventListener('change', () => {
            const tooR = document.getElementById('too_return').value;
            const frommR = document.getElementById('fromm_return');
            if (tooR === frommR.value) {
                frommR.value = '';
            }
        });
    });

   
</script>

</body>
</html>
