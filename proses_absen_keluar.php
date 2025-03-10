<?php
include 'conf/conf.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data lokasi dari POST request
    $nama = $_POST["nama"];
    $tanggalAbsen = $_POST["tanggal_absen"];
    $waktuAbsenKeluar = $_POST["waktu_absen_keluar"];

    // Konversi format tanggal dari "DD/MM/YYYY" menjadi "YYYY-MM-DD"
    $tanggalAbsenMySQL = date('Y-m-d', strtotime(str_replace('/', '-', $tanggalAbsen)));

    // Simpan gambar wajah
    $gambarWajah = $_FILES["wajah"];
    $targetDirectory = "uploads/"; // Direktori penyimpanan gambar

    // Dapatkan nama file yang diunggah
    $fileName = basename($gambarWajah["name"]);

    // Tentukan path lengkap untuk file yang diunggah
    $targetFile = $targetDirectory . $fileName;

    // Pindahkan file yang diunggah ke direktori target
    if (move_uploaded_file($gambarWajah["tmp_name"], $targetFile)) {
        echo "File berhasil diunggah.";
    } else {
        echo "Terjadi kesalahan saat mengunggah file.";
    }

    // Koneksi ke database MySQL
    $mysqli = $conn;

    if ($mysqli->connect_error) {
        die("Koneksi database gagal: " . $mysqli->connect_error);
    }

    // Periksa apakah waktu absen masuk sudah terisi sebelumnya
    $sqlCheck = "SELECT waktu_absen_masuk FROM absen WHERE nama='$nama' AND tanggal_absen='$tanggalAbsenMySQL'";
    $resultCheck = $mysqli->query($sqlCheck);
    if ($resultCheck->num_rows > 0) {
        // Absen masuk sudah terisi, lakukan update untuk waktu absen keluar
        $sqlUpdate = "UPDATE absen SET waktu_absen_keluar='$waktuAbsenKeluar' WHERE nama='$nama' AND tanggal_absen='$tanggalAbsenMySQL'";
        if ($mysqli->query($sqlUpdate) === true) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Error: " . $sqlUpdate . "<br>" . $mysqli->error;
        }
    } else {
        // Absen masuk belum terisi, beri pesan kesalahan
        echo "Waktu absen masuk belum terisi.";
    }

    $mysqli->close();
}
