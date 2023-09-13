<?php
session_start();

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'db_absen_jumat';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE nama = '$nama'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['nama'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password tidak valid";
        }
    } else {
        $error = "Nama tidak valid";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<div class="box">
    <div class="container">
        <div class="top-header">
            <header>Login</header>
        </div>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form action="" method="post">
            <div class="input-field">
                <input type="text" name="nama" class="input" placeholder="Username" required>
                <i class="bx bx-user"></i>
            </div>
            <div class="input-field">
                <input type="password" name="password" class="input" placeholder="Password" required>
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-field1">
                <button type="submit" name="login" class="submit">Login!</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
