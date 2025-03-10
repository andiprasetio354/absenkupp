<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <?php
            include 'conf/conf.php';

            // Mengecek apakah ada permintaan POST untuk menghapus pengguna
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'])) {
                $userId = $_POST['userId']; // Ambil ID pengguna dari formulir

                // Query untuk menghapus pengguna berdasarkan ID yang dipilih
                $sql = "DELETE FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);

                // Periksa apakah penghapusan berhasil
                if ($stmt->execute()) {
                    echo "<script>
                Swal.fire({
                    title: 'Success',
                    text: 'Pengguna berhasil dihapus.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?q=users';
                    }
                });
            </script>";
                } else {
                    echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error: " . $conn->error . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?q=users';
                    }
                });
            </script>";
                }

                $stmt->close(); // Tutup statement
            }

            ?>
        </div>
    </div>
</div>