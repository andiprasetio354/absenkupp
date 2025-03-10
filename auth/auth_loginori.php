<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>
    <?php
    include '../conf/conf.php';

    // Mulai session
    session_start();

    // Periksa apakah pengguna sudah login
    if (isset($_SESSION['user_id'])) {
        // Jika sudah, arahkan pengguna ke halaman index
        header("Location: ../my");
        exit(); // Menghentikan eksekusi skrip setelah pengalihan header
    }

    // Cek apakah form telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mengambil nilai yang disubmit dari form
        $username = $_POST["username"];
        $password = $_POST["password"];
        $remember_me = isset($_POST["remember-me"]); // Periksa apakah checkbox "Remember Me" dicentang

        // Melakukan validasi (opsional)
        $errors = array(); // Inisialisasi array untuk menyimpan pesan kesalahan

        // Memastikan bahwa input tidak kosong
        if (empty($username)) {
            echo "<script>
       Swal.fire({
           icon: 'error',
           title: 'Oops...',
           text: 'Username harus diisi.'
       }).then(() => {
           window.location.href = '../';
       });
     </script>";
            exit();
        }
        if (empty($password)) {
            echo "<script>
       Swal.fire({
           icon: 'error',
           title: 'Oops...',
           text: 'Password harus diisi.'
       }).then(() => {
           window.location.href = '../';
       });
     </script>";
            exit();
        }

        // Memeriksa apakah terdapat kesalahan
        if (empty($errors)) {
            // Melakukan query untuk mencari user berdasarkan username
            $query = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 1) {
                // Mengambil data user dari hasil query
                $user = mysqli_fetch_assoc($result);

                // Memeriksa apakah password cocok
                if (password_verify($password, $user['password'])) {
                    // Password cocok, lakukan login
                    $_SESSION['user_id'] = $user['id']; // Set session user_id
                    $_SESSION['username'] = $user['username']; // Set session username

                    // Jika checkbox "Remember Me" dicentang, simpan username dalam cookie
                    if ($remember_me) {
                        setcookie("remembered_username", $username, time() + (86400 * 30), "/"); // Cookie akan berakhir dalam 30 hari
                    }

                    // Tampilkan pesan SweetAlert dengan nama pengguna
                    echo "<script>
               let username = '" . $user['username'] . "';
               Swal.fire({
                   icon: 'success',
                   title: 'Login berhasil!',
                   text: 'Hai, Selamat Datang ' + username + '!',
                   showConfirmButton: false,
                   timer: 1500
               }).then(() => {
                   window.location.href = '../my';
               });
             </script>";
                    exit(); // Menghentikan eksekusi skrip setelah menampilkan SweetAlert dan redirect
                } else {
                    // Password tidak cocok
                    $errors[] = "Username atau password salah.";
                }
            } else {
                // User tidak ditemukan
                $errors[] = "Username atau password salah.";
            }
        }

        // Menampilkan pesan kesalahan dengan SweetAlert
        if (!empty($errors)) {
            $error_message = implode("<br>", $errors); // Gabungkan pesan kesalahan menjadi satu string dengan pemisah baris baru
            echo "<script>
           Swal.fire({
               icon: 'error',
               title: 'Oops...',
               html: '$error_message'
           }).then(() => {
              window.location.href = '../my' 
        });
         </script>";
        }
    }

    // Cek apakah ada cookie yang menyimpan nilai username
    if (isset($_COOKIE['remembered_username'])) {
        // Isi nilai username dari cookie ke dalam input form username
        $remembered_username = $_COOKIE['remembered_username'];
        echo "<script>document.getElementById('username').value = '$remembered_username';</script>";
    }
    ?>

</body>

</html>