<?php

// Fungsi untuk membuat folder jika belum ada
function createFolderIfNotExists($dir)
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_label_baru = trim($_POST['nama_label_baru']);

    if (!empty($nama_label_baru)) {
        // Simpan label ke database jika belum ada
        $stmt = $conn->prepare("INSERT IGNORE INTO tbl_labels (nama_label) VALUES (?)");
        $stmt->bind_param("s", $nama_label_baru);
        $stmt->execute();
        $stmt->close();

        // Buat folder untuk label baru
        $target_dir = "labels/" . basename($nama_label_baru) . "/";
        createFolderIfNotExists($target_dir);

        echo "<script>alert('Label baru berhasil ditambahkan dan folder dibuat: $target_dir'); window.location.href='?q=up_wajah';</script>";
    } else {
        echo "<script>alert('Nama label tidak boleh kosong!'); window.location.href='?q=up_wajah';</script>";
    }
}