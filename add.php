<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost"   ;
    $username = "root";
    $password = "";
    $database = "db_absen_jumat";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    foreach ($_POST["status"] as $nis => $status) {
        $nama = $_POST["nama"][$nis];
        $rombel = $_POST["rombel"][$nis];
        $rayon = $_POST["rayon"][$nis];
        $tanggal = date("Y-m-d");

        $sql = "INSERT INTO rekap (nama, nis, rombel, rayon, status, tanggal)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sissss", $nama, $nis, $rombel, $rayon, $status, $tanggal);
        $stmt->execute();
    }

    $conn->close();

    $_SESSION['success_add_message'] = "Tambah data berhasil!";
    header("Location: dashboard.php");
    exit();
}

