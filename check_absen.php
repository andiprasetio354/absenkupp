<?php
include 'conf/conf.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];

    $mysqli = $conn;

    if ($mysqli->connect_error) {
        die("Koneksi database gagal: " . $mysqli->connect_error);
    }

    $sqlquery = "SELECT * FROM absen WHERE nama='$nama'";
    $result = $mysqli->query($sqlquery);

    if ($result->num_rows > 0) {
        echo json_encode(array("status" => "sudah_absen"));
    } else {
        echo json_encode(array("status" => "belum_absen"));
    }

    $mysqli->close();
}
