<?php
include('db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fromm = $_POST['fromm'];
    $too = $_POST['too'];
    $departure_date = $_POST['departure_date'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $total_passengers = $_POST['total_passengers'];

    // ข้อมูลลูกค้า (เป็น array)
    $names = $_POST['name'];
    $lnames = $_POST['lname'];
    $genders = $_POST['gender'];
    $dobs = $_POST['dob'];
    $addresses = $_POST['address'];
    $emails = $_POST['email'];
    $tels = $_POST['tel'];

    for ($i = 0; $i < $total_passengers; $i++) {
        $name = $names[$i];
        $lname = $lnames[$i];
        $gender = $genders[$i];
        $dob = $dobs[$i];
        $address = $addresses[$i];
        $email = $emails[$i];
        $tel = $tels[$i];

        // 🔍 ตรวจสอบว่าลูกค้าคนนี้มีอยู่แล้วหรือไม่ (อิงจากชื่อ+อีเมล)
        $check_sql = "SELECT * FROM customer1 WHERE Name=? AND Email=?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // มีลูกค้าอยู่แล้ว
            $row = $result->fetch_assoc();
            $customer_id = $row['Customer_ID'];
        } else {
            // เพิ่มลูกค้าใหม่
            $insert_customer = "INSERT INTO customer1 (Name, Lname, Gender, Date_of_birth, Address, Email, Tel)
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_customer);
            $stmt->bind_param("sssssss", $name, $lname, $gender, $dob, $address, $email, $tel);
            $stmt->execute();
            $customer_id = $stmt->insert_id;
        }

        // ✅ เพิ่มข้อมูลการจอง
        $status = "รอยืนยัน";
        $insert_booking = "INSERT INTO booking1 (Customer_ID, Fromm, Too, Date, Status)
                           VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_booking);
        $stmt->bind_param("issss", $customer_id, $fromm, $too, $departure_date, $status);
        $stmt->execute();
        $booking_id = $stmt->insert_id;

        // 💡 จำลองการออกตั๋ว (Flight_ID สมมติเป็น 1, ที่นั่งสุ่ม, Boarding_time สมมุติ)
        $flight_id = 1; // ควรดึงจริงจากตาราง flight1 ถ้ามีหลายเที่ยว
        $seat = 'A' . rand(1, 60);
        $boarding_time = date("H:i", strtotime("08:00") + rand(0, 3600));

        // ✈️ บันทึกตั๋ว
        $insert_ticket = "INSERT INTO ticket1 (Booking_ID, Flight_ID, Seat, Boarding_time)
                          VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_ticket);
        $stmt->bind_param("iiss", $booking_id, $flight_id, $seat, $boarding_time);
        $stmt->execute();
    }

    echo "<script>alert('จองตั๋วสำเร็จแล้ว!'); window.location.href='booking_success.php';</script>";
} else {
    echo "ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง";
}
?>
