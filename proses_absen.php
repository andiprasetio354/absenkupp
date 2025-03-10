<?php
include 'conf/conf.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data lokasi dari POST request
    $nama = $_POST["nama"];
    $tanggalAbsen = $_POST["tanggal_absen"];
    $waktuAbsenMasuk = $_POST["waktu_absen_masuk"];
    // $waktuAbsenKeluar = $_POST["waktu_absen_keluar"];

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

    // Dapatkan path lengkap ke gambar yang diunggah
    $targetFilePath = realpath($targetFile);

    // Koneksi ke database MySQL
    $mysqli = $conn;

    if ($mysqli->connect_error) {
        die("Koneksi database gagal: " . $mysqli->connect_error);
    }

    // Ambil data waktu absen masuk dan keluar dari POST request
    $waktuAbsenMasuk = $_POST["waktu_absen_masuk"];
    $waktuAbsenKeluar = $_POST["waktu_absen_keluar"];

    // Simpan data lokasi, presensi, dan path gambar ke tabel presensi
    // $sqlquery = "INSERT INTO absen (nama, tanggal_absen, waktu_absen_masuk, waktu_absen_keluar, wajah) VALUES ('$nama', '$tanggalAbsenMySQL', '$waktuAbsenMasuk', '$waktuAbsenKeluar', '$targetFile')";
    $sqlquery = "INSERT INTO absen (nama, tanggal_absen, waktu_absen_masuk, waktu_absen_keluar, wajah) VALUES ('$nama', '$tanggalAbsenMySQL', '$waktuAbsenMasuk', '', '$targetFile')";

    if ($mysqli->query($sqlquery) === true) {
        echo "Data berhasil disimpan.";
    } else {
        echo "Error: " . $sqlquery . "<br>" . $mysqli->error;
    }

    $mysqli->close();
}
