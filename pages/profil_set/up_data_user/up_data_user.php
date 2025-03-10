<?php
// Include file konfigurasi database
include 'conf/conf.php';

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap nilai dari form
    $nama_profil = htmlspecialchars($_POST['n_profil']);
    $password_baru = htmlspecialchars($_POST['pw_baru']);
    $konf_password_baru = htmlspecialchars($_POST['konf_pw_baru']);

    // Validasi input
    if (empty($nama_profil) || empty($password_baru) || $password_baru !== $konf_password_baru) {
        echo "<script>alert('Pastikan semua kolom diisi dan password baru cocok dengan konfirmasi password baru');</script>";
        // Redirect kembali ke halaman sebelumnya
        echo "<script>window.location.href = document.referrer;</script>";
        exit(); // Berhenti dan keluar dari skrip
    }

    // Hash password baru sebelum disimpan
    $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);

    // Mendapatkan id_pengguna dari sesi atau dari data yang diterima dari pengguna yang sedang login
    $id_pengguna = $_SESSION['user_id']; // Anda perlu menyimpan $id_pengguna dengan benar sesuai dengan kebutuhan aplikasi Anda

    // Proses perubahan username dan password
    $sql_update_user = "UPDATE users SET username = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update_user);
    $stmt->bind_param("ssi", $nama_profil, $hashed_password, $id_pengguna);
    $result = $stmt->execute();

    // Periksa apakah proses berhasil
    if ($result) {
        echo "<script>alert('Perubahan berhasil disimpan');</script>";
        // Redirect ke halaman profil setelah berhasil disimpan
        echo "<script>window.location.href = '?q=profil_set';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan dalam menyimpan perubahan');</script>";
        // Redirect kembali ke halaman sebelumnya setelah gagal
        echo "<script>window.location.href = '?q=profil_set';</script>";
    }

    // Tutup statement
    $stmt->close();
}
