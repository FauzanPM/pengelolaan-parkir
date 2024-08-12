<?php
header('Content-Type: application/json');

require '../db.php';

// Query untuk mengambil data vehicles lengkap
$stmt = $conn->prepare("SELECT * FROM vehicles");
$stmt->execute();
$result = $stmt->get_result();
$vehicles = $result->fetch_all(MYSQLI_ASSOC);

// Tutup koneksi
$stmt->close();
$conn->close();

// Tampilkan data dalam format JSON
echo json_encode($vehicles);