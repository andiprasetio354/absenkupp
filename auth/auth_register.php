<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>

    <?php
    include '../conf/conf.php';

    // Cek apakah form telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mengambil nilai yang disubmit dari form
        $username = $_POST["username"];
        $password = $_POST["password"];
        $konfirmasi_password = $_POST["konfirmasi_password"];

        // Melakukan validasi (opsional)
        $errors = array(); // Inisialisasi array untuk menyimpan pesan kesalahan

        // Memastikan bahwa input tidak kosong
        if (empty($username)) {
            $errors[] = "Username harus diisi.";
        }
        if (empty($password)) {
            $errors[] = "Password harus diisi.";
        }
        if (empty($konfirmasi_password)) {
            $errors[] = "Konfirmasi password harus diisi.";
        }

        // Memeriksa kesamaan password dan konfirmasi password
        if ($password !== $konfirmasi_password) {
            $errors[] = "Password dan konfirmasi password tidak sesuai.";
        }

        // Memeriksa apakah terdapat kesalahan
        if (empty($errors)) {
            // Hash password sebelum disimpan ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Tentukan peran pengguna secara default (misalnya, 'karyawan')
            $role = 'karyawan';

            // Query untuk menambahkan user baru ke dalam database
            $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";

            if (mysqli_query($conn, $query)) {
                // Registrasi berhasil
                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi berhasil!',
                    text: 'Silakan login.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = '../'; // Redirect ke halaman login
                });
            </script>";
                exit(); // Menghentikan eksekusi skrip setelah notifikasi dan redirect
            } else {
                // Gagal menambahkan user baru
                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Registrasi gagal!',
                    text: 'Error: " . mysqli_error($conn) . "'
                }).then(() => {
                    window.location.href = '../register'; // Redirect ke halaman registrasi
                });
            </script>";
            }

            // Tutup koneksi
            mysqli_close($conn);
        } else {
            // Menampilkan pesan kesalahan
            foreach ($errors as $error) {
                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '$error'
                }).then(() => {
                    window.location.href = '../register'; // Redirect ke halaman registrasi
                });
            </script>";
            }
        }
    }

    ?>

</body>

</html>