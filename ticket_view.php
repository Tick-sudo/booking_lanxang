<?php
include 'db.php';

$ticket_id = $_GET['Ticket_id'] ?? '';

if (!$ticket_id) {
    die("❌ ບໍ່ພົບຕັ້ວດ້ວຍ Ticket_id ທີ່ຮ້ອງຂໍ");
}

$sql = "SELECT * FROM ticket1 WHERE Ticket_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("❌ ບໍ່ພົບຕັ້ວດ້ວຍ Ticket_id ນີ້");
}

$ticket = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8" />
<title>ຕັ້ວເທື່ອບິນ - LANXANG AIRWAY</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<style>
  body {
    font-family: 'Noto Sans Lao', sans-serif;
    background: #f5f5f5;
    padding: 20px;
  }
  .ticket-container {
    max-width: 600px;
    margin: auto;
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 0 10px #ccc;
  }
  h2 {
    color: #0b3c5d;
    text-align: center;
  }
  .info {
    margin-top: 15px;
    font-size: 18px;
  }
  .label {
    font-weight: bold;
    color: #0b3c5d;
  }
  button {
    display: block;
    margin: 30px auto 0;
    background: #0b3c5d;
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
  }
</style>
</head>
<body>
<div class="ticket-container" id="ticketContent">
  <h2>ຕັ້ວເທື່ອບິນ LANXANG AIRWAY</h2>
  <div class="info"><span class="label">ຊື່ຜູ້ໂດຍສານ:</span> <?= htmlspecialchars($ticket['Name'] . ' ' . $ticket['Lname']) ?></div>
  <div class="info"><span class="label">ຈາກ:</span> <?= htmlspecialchars($ticket['Fromm']) ?></div>
  <div class="info"><span class="label">ໄປ:</span> <?= htmlspecialchars($ticket['Too']) ?></div>
  <div class="info"><span class="label">ເທື່ອບິນ:</span> <?= htmlspecialchars($ticket['Flight']) ?></div>
  <div class="info"><span class="label">ເບີບ່ອນນັ່ງ:</span> <?= htmlspecialchars($ticket['Seat']) ?></div>
  <div class="info"><span class="label">ເວລາຂຶ້ນເທື່ອ:</span> <?= htmlspecialchars($ticket['Boarding_time']) ?></div>
</div>

<button id="downloadBtn">ດາວໂຫລດ PDF</button>

<script>
  const { jsPDF } = window.jspdf;
  document.getElementById('downloadBtn').addEventListener('click', () => {
    const doc = new jsPDF();

    const ticket = document.getElementById('ticketContent');

    doc.setFontSize(22);
    doc.setTextColor(11, 60, 93);
    doc.text("ຕັ້ວເທື່ອບິນ LANXANG AIRWAY", 20, 20);

    doc.setFontSize(16);
    const lines = [
      "ຊື່ຜູ້ໂດຍສານ: " + "<?= addslashes($ticket['Name'] . ' ' . $ticket['Lname']) ?>",
      "ຈາກ: " + "<?= addslashes($ticket['Fromm']) ?>",
      "ໄປ: " + "<?= addslashes($ticket['Too']) ?>",
      "ເທື່ອບິນ: " + "<?= addslashes($ticket['Flight']) ?>",
      "ເບີບ່ອນນັ່ງ: " + "<?= addslashes($ticket['Seat']) ?>",
      "ເວລາຂຶ້ນເທື່ອ: " + "<?= addslashes($ticket['Boarding_time']) ?>"
    ];

    let y = 40;
    lines.forEach(line => {
      doc.text(line, 20, y);
      y += 10;
    });

    doc.save('ticket_<?= $ticket_id ?>.pdf');
  });
</script>
</body>
</html>
