<?php
session_start();
require 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Path ke folder gambar
$avatar_path = 'img/';

// Cek apakah file gambar profil ada
$avatar_url = file_exists($avatar_path . $user['avatar']) ? $avatar_path . $user['avatar'] : $avatar_path . 'ujan.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - SiParkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 40px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
    }

    .profile-card img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin-bottom: 20px;
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-card h2 {
        color: #4169e1;
        margin-bottom: 10px;
    }

    .profile-info {
        margin-bottom: 15px;
        color: #333;
    }

    .profile-info strong {
        color: #a020f0;
        display: block;
        margin-bottom: 5px;
    }

    .edit-btn {
        background: linear-gradient(45deg, #a020f0, #4169e1);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        margin-top: 20px;
    }

    .edit-btn:hover {
        background: linear-gradient(45deg, #4169e1, #a020f0);
        box-shadow: 0 5px 15px rgba(65, 105, 225, 0.4);
    }

    .footer {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        text-align: center;
        padding: 15px;
        font-weight: 300;
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
        <div class="profile-card">
            <img src="<?php echo $avatar_url; ?>" alt="Profile Picture">
            <h2><?php echo htmlspecialchars($user['nama']); ?></h2>
            <div class="profile-info">
                <strong>Email</strong>
                <?php echo htmlspecialchars($user['email']); ?>
            </div>
            <div class="profile-info">
                <strong>NIS</strong>
                <?php echo htmlspecialchars($user['nis']); ?>
            </div>
            <div class="profile-info">
                <strong>Alamat</strong>
                <?php echo htmlspecialchars($user['alamat']); ?>
            </div>
        </div>
    </div>
    <div class="footer">
        Vikalpa Vikasa | MPTI
    </div>
</body>

</html>