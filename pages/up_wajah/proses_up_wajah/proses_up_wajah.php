<?php
// Cek apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_label = $_POST['nama_label'];

    // Tentukan direktori tujuan
    $target_dir = "labels/" . basename($nama_label) . "/";

    // Jika direktori belum ada, buat folder
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Hitung file ke-n berdasarkan jumlah file yang ada
    $file_count = count(glob($target_dir . "*.jpg")) + 1;

    // Tentukan nama file tujuan
    $target_file = $target_dir . $file_count . ".jpg";

    // Validasi file upload
    if ($_FILES['file']['type'] !== 'image/jpeg') {
        die("Hanya file JPG yang diperbolehkan.");
    }

    // Proses upload file
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        echo "<script>alert('File berhasil diupload ke $target_file'); window.location.href='?q=up_wajah';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengupload file.'); window.location.href='?q=up_wajah';</script>";
    }
}