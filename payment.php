<!-- <?php
session_start();
include 'db.php';

if (!isset($_SESSION['booking_data'])) {
    header('Location: index.php');
    exit();
}

$data = $_SESSION['booking_data'];

// ตัวอย่างคำนวณราคาง่ายๆ (สมมติราคาเที่ยวบิน 1 เที่ยว = 100 USD)
$base_price = 0;

// ดึงราคาของเที่ยวบินไปและกลับ
$sql = "SELECT Price FROM flight1 WHERE Id_plane = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $data['depart_flight_id']);
$stmt->execute();
$result = $stmt->get_result();
$depart_price = $result->fetch_assoc()['Price'] ?? 0;

$return_price = 0;
if ($data['trip_type'] === 'round' && $data['return_flight_id']) {
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param('s', $data['return_flight_id']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $return_price = $result2->fetch_assoc()['Price'] ?? 0;
}

$base_price = $depart_price + $return_price;

// คำนวณราคาสุทธิ (สมมติ adult=100%, child=50%, infant=10%)
$total_price_usd = $base_price * ($data['adult'] + $data['child'] * 0.5 + $data['infant'] * 0.1);

// แปลงค่าเงินถ้าเลือก LAK
if ($data['currency'] === 'lak') {
    $total_price = $total_price_usd * 10000; // 1 USD = 10,000 LAK
} else {
    $total_price = $total_price_usd;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    -->
