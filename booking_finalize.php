<?php
session_start();
include 'db.php';

// เช็คว่าเข้ามาจาก POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('ການເຂົ້າເຖິງໜ້ານີ້ບໍ່ຖືກຕ້ອງ'); window.location='booking_customer.php';</script>";
    exit;
}

// จำนวนผู้โดยสาร (Adult + Child) ไม่นับ Infant เพราะนั่งอุ้ม
$adult = $_SESSION['adult'] ?? 0;
$child = $_SESSION['child'] ?? 0;
$infant = $_SESSION['infant'] ?? 0;

$total = $adult + $child;  // จำนวนผู้ที่ต้องการที่นั่งจริงๆ

if ($total <= 0) {
    echo "<script>alert('ບໍ່ມີຜູ້ໂດຍສານ (ບໍ່ນັບທີ່ນັ່ງອຸ້ມ)'); window.location='booking_customer.php';</script>";
    exit;
}

// รับข้อมูลเที่ยวบินและวันที่เดินทางจากฟอร์ม
$id_plane = $_POST['id_plane'] ?? '';
$fromm = $_POST['fromm'] ?? '';
$too = $_POST['too'] ?? '';
$flight_name = $_POST['flight_name'] ?? '';
$date = $_POST['depart_date'] ?? '';  // วันที่เดินทาง

// กำหนดสถานะการจอง
$booking_status = "ລໍຖ້າການຊຳລະເງິນ";

// ดึงข้อมูลเที่ยวบิน (เวลาการเดินทาง Boarding time, Max seats)
// ดึงข้อมูลเที่ยวบิน (เวลาการเดินทาง Boarding time, Max seats)
$sql = "SELECT Depart_time, Boarding_time, Max_seat FROM flight1 
        WHERE Id_plane = ?  AND Fromm = ? AND Too = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("sss", $id_plane, $fromm, $too);
$stmt->execute();
$stmt->bind_result($depart_time_from_db, $boarding_time, $max_seats);
$stmt->fetch();
$stmt->close();


if (!$max_seats) {
    $max_seats = 80; // กำหนด default
}

// ดึงที่นั่งที่ถูกจองไปแล้วในเที่ยวบินนี้
$sql_booked = "SELECT Seat FROM ticket1 WHERE Flight = ? AND Depart_date = ?";
$stmt2 = $conn->prepare($sql_booked);
if (!$stmt2) {
    die("Prepare failed: " . $conn->error);
}
$stmt2->bind_param("ss", $id_plane, $date);
$stmt2->execute();
$result = $stmt2->get_result();

$booked_seats = [];
while ($row = $result->fetch_assoc()) {
    $booked_seats[] = $row['Seat'];
}
$stmt2->close();

// สร้าง list ที่นั่งทั้งหมด สมมติ 4 ที่นั่งต่อแถว
$rows = ceil($max_seats / 4);
$cols = ['A', 'B', 'C', 'D'];
$seats = [];
for ($r = 1; $r <= $rows; $r++) {
    foreach ($cols as $c) {
        $seat = $r . $c;
        $seats[] = $seat;
        if (count($seats) >= $max_seats) break 2;
    }
}

// หา list ที่นั่งว่าง
$available_seats = array_values(array_diff($seats, $booked_seats));

// เช็คว่าที่นั่งว่างพอไหม
if (count($available_seats) < $total) {
    echo "<script>alert('ທີ່ນັ່ງເຕັມແລ້ວ ກະລຸນາເລືອກຖ້ຽວບິນອື່ນ'); window.location='booking_customer.php';</script>";
    exit;
}


// เริ่ม transaction
$conn->begin_transaction();

try {
    for ($i = 1; $i <= $total; $i++){
        $prefix = "p{$i}_";

        $type = $_POST[$prefix . 'type'] ?? '';
        $name = $_POST[$prefix . 'name'] ?? '';
        $lname = $_POST[$prefix . 'lname'] ?? '';
        $dob = $_POST[$prefix . 'dob'] ?? '';
        $passport = $_POST[$prefix . 'passport'] ?? '';
        $idcard = $_POST[$prefix . 'idcard'] ?? '';
        $idhome = $_POST[$prefix . 'idhome'] ?? '';
        $email = $_POST[$prefix . 'email'] ?? '';
        $tel = $_POST[$prefix . 'tel'] ?? '';
        $gender = $_POST[$prefix . 'gender'] ?? '';
        $nationality = $_POST[$prefix . 'nationality'] ?? '';
        $country = $_POST[$prefix . 'country'] ?? '';
        $address = $_POST[$prefix . 'address'] ?? '';

        // เลือกที่นั่งสุ่ม
        $rand_index = array_rand($available_seats);
        $seat = $available_seats[$rand_index];
        unset($available_seats[$rand_index]);
        $available_seats = array_values($available_seats);

        // เช็คลูกค้าในระบบ (ชื่อนามสกุล)
        $sql_check = "SELECT ID, Id_passport, Id_card, Id_home FROM customer1 WHERE Name = ? AND Lname = ?";
        $stmt_check = $conn->prepare($sql_check);
        if (!$stmt_check) {
            throw new Exception("Prepare check customer failed: " . $conn->error);
        }
        $stmt_check->bind_param("ss", $name, $lname);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        $found_customer = false;
        $customer_id = null;

        while ($row = $result_check->fetch_assoc()) {
            if (
                ($row['Id_passport'] && $row['Id_passport'] === $passport) ||
                ($row['Id_card'] && $row['Id_card'] === $idcard) ||
                ($row['Id_home'] && $row['Id_home'] === $idhome)
            ) {
                $found_customer = true;
                $customer_id = $row['ID'];
                break;
            }
        }
        $stmt_check->close();

        // ถ้าไม่เจอข้อมูลลูกค้า ให้ insert ใหม่
        if (!$found_customer) {
            $sql_customer = "INSERT INTO customer1 
                (Name, Lname, Gender, Date_of_birth, Nationality, Country, Address, Id_passport, Id_card, Id_home, Email, Type_of_ticket, Tel, Password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '')";

            $stmt_cus = $conn->prepare($sql_customer);
            if (!$stmt_cus) {
                throw new Exception("Prepare customer failed: " . $conn->error);
            }
            $stmt_cus->bind_param("sssssssssssss", $name, $lname, $gender, $dob, $nationality, $country, $address, $passport, $idcard, $idhome, $email, $type, $tel);
            if (!$stmt_cus->execute()) {
                $stmt_cus->close();
                throw new Exception("Execute customer failed: " . $stmt_cus->error);
            }
            $customer_id = $stmt_cus->insert_id;
            $stmt_cus->close();
        }

        // เช็คว่าลูกค้าคนนี้จองเที่ยวบินนี้ในวันที่นี้ไปแล้วไหม
// เช็คว่าลูกค้าคนนี้จองเที่ยวบินนี้ในวันที่นี้ไปแล้วไหม
// เช็คว่าลูกค้าคนนี้จองเที่ยวบินนี้ในวันที่นี้ไปแล้วไหม (เช็คเฉพาะ Id_passport กับ Id_card)
$sql_check_booking = "
   
    SELECT b.Booking_id, b.Id_card, b.Id_passport
    FROM booking1 b
    JOIN ticket1 t 
        ON b.Name = t.Name
        AND b.Lname = t.Lname
        AND b.Id_plane = t.Flight
    WHERE t.Depart_date = ?
      AND b.Id_plane = ?
      AND (
        (b.Id_passport != '' AND b.Id_passport = ?)
        OR (b.Id_card != '' AND b.Id_card = ?)
      )
";


$stmt_check_booking = $conn->prepare($sql_check_booking);
if (!$stmt_check_booking) {
    throw new Exception("Prepare check booking failed: " . $conn->error);
}

// bind param ตามลำดับ ? ใน SQL ด้านบน
$stmt_check_booking->bind_param(
    "ssss",
    $date,       // Depart_date
    $id_plane,   // Id_plane (เที่ยวบิน)
    $passport,   // Id_passport ที่กรอกมา
    $idcard      // Id_card ที่กรอกมา
);

$stmt_check_booking->execute();
$result_check_booking = $stmt_check_booking->get_result();

if ($result_check_booking->num_rows > 0) {
    // เช็คว่าซ้ำที่ฟิลด์ไหนบ้าง
    $row = $result_check_booking->fetch_assoc();

    if ($row['Id_card'] === $idcard && $idcard !== '') {
        throw new Exception("ຂໍ້ມູນເລກບັດປະຈຳຕົວຜູ້ໂດຍສານບໍ່ຖືກຕ້ອງ");
    }
    if ($row['Id_passport'] === $passport && $passport !== '') {
        throw new Exception("ຂໍ້ມູນໜັງສືເດີນທາງຜູ້ໂດຍສານບໍ່ຖືກຕ້ອງ ");
    }
    
    // กรณีอื่น ๆ
    throw new Exception("ຜູ້ໂດຍສານນີ້ໄດ້ຈອງໄປແລ້ວໃນວັນນີ້");
}


$stmt_check_booking->close();




         // ดึงราคา Price จาก flight1 ตาม id_plane (เที่ยวบิน)
$user_type = $type;

$sql_price = "SELECT Price, Price_child, Price_infant FROM flight1 WHERE Id_plane = ?";
$stmt_price = $conn->prepare($sql_price);
if (!$stmt_price) {
    throw new Exception("Prepare price failed: " . $conn->error);
}
$stmt_price->bind_param("s", $id_plane);
$stmt_price->execute();
$stmt_price->bind_result($price, $price_child, $price_infant);
$stmt_price->fetch();
$stmt_price->close();



        // บันทึก booking
        
        $sql_booking = "INSERT INTO booking1 
            (Customer_id, Name, Lname, Id_card, Id_passport, Id_home, Id_plane, Fromm, Too, Depart_date, User_type, Price, Price_child, Price_infant, Booking_status, Tel) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_booking = $conn->prepare($sql_booking);
        if (!$stmt_booking) {
            throw new Exception("Prepare booking failed: " . $conn->error);
        }
       $stmt_booking->bind_param("isssssssssssssss", $customer_id, $name, $lname, $idcard, $passport, $idhome, $id_plane, $fromm, $too, $date, $user_type, $price, $price_child,  $price_infant, $booking_status, $tel);

        if (!$stmt_booking->execute()) {
            $stmt_booking->close();
        
            throw new Exception("Execute booking failed: " . $stmt_booking->error);
        }
        $stmt_booking->close();

        // บันทึก ticket
       $sql = "INSERT INTO ticket1 (Name, Lname, Fromm, Too, Flight, Seat, Depart_date, Depart_time, Boarding_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_ticket = $conn->prepare($sql);
if (!$stmt_ticket) {
    throw new Exception("Prepare ticket failed: " . $conn->error);
}
$stmt_ticket->bind_param("sssssssss", $name, $lname, $fromm, $too, $id_plane, $seat, $date, $depart_time_from_db, $boarding_time);


if (!$stmt_ticket->execute()) {
    throw new Exception("Execute ticket failed: " . $stmt_ticket->error);
}
$stmt_ticket->close();

    }

    $conn->commit();

    // แสดงหน้า success
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="lo">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ຈອງສຳເລັດ - LANXANG AIRWAY</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <style>
      body {
        margin: 0;
        font-family: 'Noto Sans Lao', sans-serif;
        background: url('images/fn1.jpg') no-repeat center center fixed;
        background-size: cover;
        color: #002d74;
      }
      .container {
        max-width: 700px;
        margin: 100px auto;
        background: rgba(255 255 255 / 0.95);
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 6px 30px rgb(0 45 116 / 0.4);
        text-align: center;
      }
      h2 {
        color: #004080;
        margin-bottom: 20px;
        font-weight: 800;
        letter-spacing: 1.3px;
      }
      p {
        font-size: 18px;
        line-height: 1.5;
        margin-bottom: 40px;
      }
      a {
        display: inline-block;
        background-color: #004080;
        color: white;
        font-weight: 700;
        padding: 12px 36px;
        border-radius: 12px;
        text-decoration: none;
        box-shadow: 0 5px 14px rgba(0, 64, 128, 0.4);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
      }
      a:hover,
      a:focus {
        background-color: #ffdd00;
        color: #004080;
        box-shadow: 0 0 14px #ffdd00;
        outline: none;
      }
    </style>
    </head>
    <body>
    <div class="container">
      <h2>ຈອງສຳເລັດ!</h2>
      <p>ຂອບໃຈທີ່ໃຊ້ບໍລິການສາຍການບິນລ້ານຊ້າງ</p>
       <p>ກະລຸນາຊຳລະເງິນຢູ່ເຄົາເຕີ້ກ່ອນປິດແຈ້ງປີ້ 1 ຊົ່ວໂມງ</p>
      <a href="booking.php">ກັບໄປຫຼັກໜ້າ</a>
    </div>
    </body>
    </html>
    HTML;

} catch (Exception $e) {
    $conn->rollback();
    $conn->close();

    $error_message = addslashes($e->getMessage());
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="lo">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ຜິດພາດໃນການຈອງ - LANXANG AIRWAY</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <style>
      body {
        margin: 0; font-family: 'Noto Sans Lao', sans-serif;
        background: url('images/fn1.jpg') no-repeat center center fixed;
        background-size: cover;
        color: #002d74;
      }
      .container {
        max-width: 700px;
        margin: 100px auto;
        background: rgba(255 255 255 / 0.95);
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 6px 30px rgb(0 45 116 / 0.4);
        text-align: center;
      }
      h2 {
        color: #d21e1e;
        margin-bottom: 20px;
      }
      a {
        color: #004080;
        font-weight: 700;
        text-decoration: none;
        border: 2px solid #004080;
        padding: 10px 18px;
        border-radius: 10px;
        transition: background-color 0.3s ease;
      }
      a:hover {
        background-color: #004080;
        color: white;
      }
    </style>
    </head>
    <body>
      <div class="container">
        <h2>ການຈອງຜິດພາດ</h2>
        <p>ມີບັນຫາເກີດຂຶ້ນໃນການຈອງ: <br><strong>$error_message</strong></p>
        <p><a href="booking_customer.php">ກັບໄປປ້ອນຂໍ້ມູນໃໝ່</a></p>
      </div>
    </body>
    </html>
    HTML;
    exit;
}
?>
