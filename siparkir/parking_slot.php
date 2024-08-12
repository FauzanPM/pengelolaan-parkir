<?php
session_start();
require 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

$plate_number = $_POST['plate_number'];

// Dapatkan jenis kendaraan dari form
$selectedVehicle = $_POST['selectedVehicle'] ?? '';

if (empty($selectedVehicle)) {
    header("Location: dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT slot FROM vehicles WHERE exit_time IS NULL");
$stmt->execute();
$result = $stmt->get_result();
$occupied_slots = $result->fetch_all(MYSQLI_ASSOC);

// Buat array dari slot yang terisi
$occupied_slots = array_column($occupied_slots, 'slot');

// List slot parkir
$slots = [
    "A1", "A2", "A3", "A4", "A5", "A6",
    "A7", "A8", "A9", "A10", "A11", "A12",
    "A13", "A14", "A15", "A16", "A17", "A18",
    "A19", "A20", "A21", "A22", "A23", "A24",
    "A25", "A26", "A27", "A28", "A29", "A30",
    "A31", "A32", "A33", "A34", "A35", "A36",
    "A37", "A38", "A39", "A40", "A41", "A42"
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Parkir</title>
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

    .slots {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .slot {
        width: 60px;
        height: 60px;
        line-height: 60px;
        text-align: center;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .slot.available {
        background: lightgreen;
    }

    .slot.occupied {
        background: lightcoral;
        cursor: not-allowed;
    }

    .slot.selected {
        background: gold;
    }

    .footer {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        text-align: center;
        padding: 15px;
        font-weight: 300;
    }

    .continue-btn {
        background: #4CAF50;
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        padding: 10px 40px;
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

        .slots {
            gap: 5px;
        }

        .slot {
            width: 40px;
            height: 40px;
            line-height: 40px;
            font-size: 14px;
        }

        .continue-btn {
            padding: 10px 20px;
        }
    }
    </style>
    <script>
    function selectSlot(slot) {
        if (slot.classList.contains('occupied') || slot.classList.contains('unavailable')) {
            return;
        }
        var slots = document.querySelectorAll('.slot');
        slots.forEach(s => s.classList.remove('selected'));
        slot.classList.add('selected');
        document.getElementById('selectedSlot').value = slot.textContent;
        document.getElementById('continueBtn').disabled = false;
    }

    function validateForm() {
        var selectedSlot = document.getElementById('selectedSlot').value;
        var selectedSlotElement = document.querySelector('.slot.selected');
        if (selectedSlot === "" || selectedSlotElement.classList.contains('occupied') || selectedSlotElement.classList
            .contains('unavailable')) {
            alert("Silahkan Pilih Slot Parkir Yang Tersedia");
            return false;
        }
        return true;
    }
    </script>
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
        <h2>Halaman Parkir</h2>
        <p>Choose Your Parking Slot!</p>
        <div class="slots">
            <?php foreach ($slots as $slot) : ?>
            <?php if (in_array($slot, $occupied_slots)) : ?>
            <div class="slot occupied" onclick="return false;"><?php echo $slot; ?></div>
            <?php else : ?>
            <div class="slot available" onclick="selectSlot(this)"><?php echo $slot; ?></div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <form onsubmit="return validateForm()" method="POST" action="confirm.php">
            <input type="hidden" name="plate_number" value="<?= $plate_number; ?>">
            <input type="hidden" id="selectedSlot" name="selectedSlot">
            <input type="hidden" name="selectedVehicle" value="<?php echo htmlspecialchars($selectedVehicle); ?>">
            <button type="submit" class="continue-btn" id="continueBtn" disabled>Continue</button>
        </form>
    </div>
    <div class="footer">
        Vikalpa Vikasa | MPTI
    </div>
</body>

</html>