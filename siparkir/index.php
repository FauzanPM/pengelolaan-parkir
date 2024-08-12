<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiParkir - Login</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #a13388, #10356c);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        width: 800px;
        max-width: 100%;
    }

    .left {
        background: linear-gradient(to right, #a13388, #10356c);
        padding: 40px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .left h1 {
        margin: 0;
        font-size: 36px;
    }

    .left p {
        margin-top: 20px;
        text-align: center;
    }

    .right {
        padding: 40px;
        flex: 1;
    }

    .right h2 {
        margin: 0;
        font-size: 24px;
        color: #333;
    }

    .form {
        margin-top: 20px;
    }

    .form input {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .form button {
        width: 100%;
        padding: 10px;
        background: #a13388;
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .form a {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #333;
    }

    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            width: 90%;
        }

        .left,
        .right {
            padding: 20px;
            text-align: center;
        }

        .left h1 {
            font-size: 28px;
        }

        .right h2 {
            font-size: 20px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <h1>Welcome To</h1>
            <img src="img/smk.png" alt="Logo" style="width: 100px; height: 100px;">
            <h1>SiParkir</h1>
            <p>Aplikasi yang Memudahkan Anda untuk Mencari Tempat Parkir dengan Mudah dan Efisien</p>
        </div>
        <div class="right">
            <h2>Sign In</h2>
            <?php
            if (isset($_GET['error'])) {
                echo '<p style="color: red;">' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>
            <form class="form" action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="nis" placeholder="NIS" required>
                <button type="submit">LOGIN</button>
            </form>
        </div>
    </div>
</body>

</html>