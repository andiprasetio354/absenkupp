<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Profil</h4>
        <?php
        include './conf/conf.php';

        // Lokasi penyimpanan foto profil
        $uploadDirectory = 'assets/img/profiles/';

        if ($_FILES['file']) {
            $userId = $_POST['userId'];
            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileError = $_FILES['file']['error'];

            if ($fileError === 0) {
                // Generate nama file baru untuk mencegah konflik nama
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = uniqid('', true) . '.' . $fileExtension;
                $fileDestination = $uploadDirectory . $newFileName;

                // Pindahkan file yang diunggah ke lokasi penyimpanan
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Update lokasi foto profil ke dalam database
                    $query = "UPDATE users SET profile_picture = '$fileDestination' WHERE id = $userId";

                    if (mysqli_query($conn, $query)) {
                        // Jika query berhasil dieksekusi
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: 'Selamat! Foto Profilmu Berhasil di Ganti',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = '?q=profil_set';
                        });
                    </script>";
                    } else {
                        // Jika terjadi kesalahan saat menjalankan query
                        echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat menyimpan lokasi foto profil!',
                        }).then(() => {
                            window.location.href = '?q=profil_set';
                        });
                    </script>";
                    }
                } else {
                    // Terjadi kesalahan saat mengunggah foto
                    echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengunggah foto!',
                    }).then(() => {
                        window.location.href = '?q=profil_set';
                    });
                </script>";
                }
            } else {
                // Tidak ada file yang dipilih
                echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Tidak ada file yang dipilih!',
                }).then(() => {
                    window.location.href = '?q=profil_set';
                });
            </script>";
            }
        }
        ?>

    </div>