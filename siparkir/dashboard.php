<?php
session_start();
require 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

// Query untuk mengambil kendaraan pengguna dari database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$kendaraan = $result->fetch_all(MYSQLI_ASSOC);

// Debugging: print kendaraan
echo '<script>console.log("Kendaraan: ' . json_encode($kendaraan) . '");</script>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiParkir</title>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #a020f0, #4169e1);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
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
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .vehicle-selection {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .vehicle-selection div {
        border: 2px solid #fff;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        border-radius: 10px;
        transition: background 0.3s;
        background: white;
        width: 100px;
    }

    .vehicle-selection div.selected {
        background: #4169e1;
        color: white;
    }

    .vehicle-selection img {
        width: 50px;
        height: 50px;
    }

    .input-container {
        margin-bottom: 20px;
    }

    .input-container input {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 200px;
    }

    .map {
        margin-bottom: 5px;
        border-radius: 5px;
        overflow: hidden;
        border: 10px solid #4169e1;
    }

    .continue-btn {
        text-align: center;
    }

    .continue-btn button {
        padding: 10px 40px;
        background: #4CAF50;
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .footer {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        text-align: center;
        padding: 15px;
        font-weight: 300;
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            text-align: center;
        }

        .header nav a {
            margin: 10px 0;
        }

        .container {
            padding: 10px;
        }

        .vehicle-selection {
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .vehicle-selection div {
            width: 80px;
            padding: 10px;
        }

        .vehicle-selection img {
            width: 40px;
            height: 40px;
        }

        .input-container input {
            width: 100%;
        }

        .map iframe {
            width: 100%;
            height: 300px;
        }
    }
    </style>
    <script>
    function selectVehicle(type) {
        var vehicles = document.querySelectorAll('.vehicle-selection div');
        vehicles.forEach(vehicle => {
            vehicle.classList.remove('selected');
        });
        document.getElementById(type).classList.add('selected');
        document.getElementById('selectedVehicle').value = type;
        document.getElementById('continueBtn').disabled = false;
    }

    function validateForm() {
        var selectedVehicle = document.getElementById('selectedVehicle').value;
        var locationInput = document.getElementById('locationInput').value;

        if (selectedVehicle === "") {
            alert("Pilih Tipe Kendaraan Anda");
            return false;
        }

        if (locationInput.trim() === "") {
            alert("Masukkan Plat Nomor Kendaraan Anda");
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
        <div class="vehicle-selection">
            <div id="car" onclick="selectVehicle('car')">
                <img src="img/car.png" alt="Car">
                <p>Car</p>
            </div>
            <div id="motorcycle" onclick="selectVehicle('motorcycle')">
                <img src="img/motorcycle.png" alt="Motorcycle">
                <p>Motorcycle</p>
            </div>
            <div id="bicycle" onclick="selectVehicle('bicycle')">
                <img src="img/bicycle.png" alt="Bicycle">
                <p>Bicycle</p>
            </div>
        </div>
        <div class="input-container">
            <form onsubmit="return validateForm()" method="POST" action="parking_slot.php">
                <input type="text" id="locationInput" name="plate_number"
                    placeholder="Masukkan Plat Nomor Kendaraan Anda">
        </div>
        <div class="map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3169.9385143564123!2d-122.0842496846924!3d37.42199997982554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fb737baf4af47%3A0x658d7f5afde26a3e!2sGoogleplex!5e0!3m2!1sen!2sus!4v1592320002000!5m2!1sen!2sus"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <div class="continue-btn">
            <input type="hidden" id="selectedVehicle" name="selectedVehicle">
            <button type="submit" id="continueBtn" disabled>Continue</button>
            </form>
        </div>
    </div>
    <div class="footer">
        Vikalpa Vikasa | MPTI
    </div>
</body>

</html>