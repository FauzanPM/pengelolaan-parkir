<?php
session_start();
require 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

// Dapatkan data pengguna dari session
$user_id = $_SESSION['user_id'] ?? 0;
$nama = $_SESSION['nama'] ?? 'John Doe';
$nis = $_SESSION['nis'] ?? '1234567890';

// Tangani penghapusan data kendaraan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Tangani penambahan data kendaraan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['plate_number']) && isset($_POST['selectedSlot']) && isset($_POST['selectedVehicle'])) {
    $selectedSlot = $_POST['selectedSlot'];
    $selectedVehicle = $_POST['selectedVehicle'];
    $plate_number = $_POST['plate_number'];
    $enterTime = date("Y-m-d H:i:s");

    // Simpan data kendaraan ke database
    $stmt = $conn->prepare("INSERT INTO vehicles (user_id, type, plate_number, entry_time, slot) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $selectedVehicle, $plate_number, $enterTime, $selectedSlot);
    $stmt->execute();
    $stmt->close();
}

// Ambil semua karcis parkir yang dimiliki user dari database
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to bottom, #a020f0, #4169e1);
        color: white;
        text-align: center;
        padding: 0;
        margin: 0;
    }

    .header {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .header img {
        height: 40px;
        margin-right: 15px;
    }

    .header span {
        font-size: 18px;
        font-weight: 600;
    }

    .header nav a {
        color: white;
        margin-left: 20px;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .header nav a:hover {
        color: #ffd700;
    }

    .container {
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-wrap: wrap;
    }

    .receipt {
        background: white;
        color: black;
        border-radius: 10px;
        padding: 20px;
        width: 300px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 10px;
    }

    .footer {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        text-align: center;
        padding: 15px;
        font-weight: 300;
    }

    .receipt .delete-btn {
        background: red;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            text-align: center;
        }

        .header nav a {
            margin: 10px 0;
        }

        .receipt {
            width: 80%;
            padding: 15px;
        }

        .receipt .delete-btn {
            padding: 8px 16px;
            font-size: 14px;
        }
    }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo-container">
            <img src="img/smk.png" alt="Logo">
            <span>SMK KARTIKA NUSANTARA</span>
        </div>
        <nav>
            <a href="dashboard.php">HOME</a>
            <a href="profile.php">PROFIL</a>
            <a href="logout.php">LOGOUT</a>
            <a href="confirm.php">KARCIS</a>
        </nav>
    </div>
    <div class="container">
        <?php if (count($vehicles) > 0): ?>
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="receipt">
                    <h2>Struk Anda</h2>
                    <p><strong>Parking Slot</strong></p>
                    <h1><?php echo htmlspecialchars($vehicle['slot']); ?></h1>
                    <p><strong>Enter Time</strong></p>
                    <p><?php echo date("h:i A", strtotime($vehicle['entry_time'])); ?></p>
                    <p><strong>Enter Date</strong></p>
                    <p><?php echo date("D, M j", strtotime($vehicle['entry_time'])); ?></p>
                    <p><strong>NAMA</strong></p>
                    <p><?php echo htmlspecialchars($nama); ?></p>
                    <p><strong>NIS</strong></p>
                    <p><?php echo htmlspecialchars($nis); ?></p>
                    <p><strong>PLAT NOMOR</strong></p>
                    <p><?php echo htmlspecialchars($vehicle['plate_number']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="delete_id" value="<?php echo $vehicle['id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Anda Belum Memasukkan Data Parkir.</p>
        <?php endif; ?>
    </div>
    <div class="footer">
        Vikalpa Vikasa | MPTI
    </div>
</body>

</html>
