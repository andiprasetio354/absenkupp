<?php
// Sertakan file konfigurasi database
include 'conf/conf.php';

// Periksa apakah permintaan POST berisi ID pengguna
if (isset($_POST['userId'])) {
    // Tangkap ID pengguna dari permintaan POST
    $userId = $_POST['userId'];

    // Query untuk mendapatkan detail pengguna berdasarkan ID
    $query = "SELECT username, profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    // Periksa apakah pengguna dengan ID yang diberikan ada
    if ($stmt->num_rows > 0) {
        // Bind hasil query ke variabel
        $stmt->bind_result($username, $profile_picture);
        $stmt->fetch();

        // Tampilkan informasi detail pengguna dalam bentuk HTML
        echo '<p>Anda yakin ingin menghapus pengguna <strong>' . $username . '</strong>?</p>';
        if (!empty($profile_picture)) {
            echo '<img src="' . $profile_picture . '" alt="Foto Profil" class="img-fluid mb-2" style="max-width: 100px;">';
        }
    } else {
        // Jika pengguna tidak ditemukan, kembalikan pesan kesalahan
        echo 'error';
    }

    // Tutup statement
    $stmt->close();
} else {
    // Jika tidak ada ID pengguna dalam permintaan POST, kembalikan pesan kesalahan
    echo 'error';
}

// Tutup koneksi database
$conn->close();
