<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "db_absen_jumat";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    foreach ($_POST["status"] as $nis => $status) {
        $sql = "UPDATE rekap SET status = ? WHERE nis = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("si", $status, $nis);
        $stmt->execute();
    }

    $conn->close();

    $_SESSION['success_update_message'] = "Absen berhasil dirubah!";
    header("Location: dashboard.php");
    exit();
}




