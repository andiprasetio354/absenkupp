<?php
// Mulai session
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak, arahkan pengguna ke halaman login
    header("Location: login");
    exit(); // Menghentikan eksekusi skrip setelah pengalihan header
}

// Menghubungkan ke database
include 'conf/conf.php'; // Sesuaikan dengan nama file koneksi Anda

// Ambil username pengguna yang sedang login dari sesi
$username = $_SESSION['username'];

// Query untuk mengambil data pengguna berdasarkan username
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil dieksekusi
if ($result) {
    // Ambil data pengguna
    $user_data = mysqli_fetch_assoc($result);
} else {
    // Jika query gagal, tampilkan pesan kesalahan
    echo "Error: " . mysqli_error($conn);
}

// Ambil lokasi foto profil dari basis data sesuai dengan user yang sedang login
$query = "SELECT profile_picture FROM users WHERE id = {$_SESSION['user_id']}";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profile_picture_path = $row['profile_picture'];
}

// Periksa peran pengguna setelah login
if ($user_data) {
    // Ambil peran pengguna dari data pengguna yang ditemukan
    $user_role = $user_data['role'];

    // Tindakan berdasarkan peran pengguna
    if ($user_role === 'karyawan') {
        // Tampilkan menu atau lakukan tindakan lain untuk karyawan
    } else if ($user_role === 'admin') {
        // Tampilkan menu atau lakukan tindakan lain untuk admin
    } else {
        // Tindakan default jika peran pengguna tidak sesuai
    }
} else {
    // Tindakan jika data pengguna tidak ditemukan atau query gagal
    echo "Error: Data pengguna tidak ditemukan.";
}
?>


<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard</title>

    <meta name="description" content="" />

    <script defer src="face-api.min.js"></script>
    <script defer src="scriptterbaru1.js"></script>
    <script defer src="scriptandikeluar1.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/fav.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/css/wajah9.css" />
    <link rel="stylesheet" href="assets/css/baru.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <script src="assets/js/config.js"></script>

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
    var username = '<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>';
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="my" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="assets/img/logo2.png" alt="" class="img-fluid" width="210">
                        </span>
                    </a>
                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Main-->
                    <!-- Dashboard -->
                    <li
                        class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'my') !== false && !isset($_GET['q'])) ? 'active' : ''; ?>">
                        <a href="my" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>
                    <!-- End Main -->

                    <!-- Content-->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Isi</span></li>
                    <li class="menu-item <?php echo (isset($_GET['q']) && $_GET['q'] == 'absen') ? 'active' : ''; ?>">
                        <a href="?q=absen" class="menu-link">
                            <i class="menu-icon fa-solid fa-person-chalkboard"></i>
                            <div data-i18n="Basic">Absensi Masuk</div>
                        </a>
                    </li>
                    <li
                        class="menu-item <?php echo (isset($_GET['q']) && $_GET['q'] == 'up_wajah') ? 'active' : ''; ?>">
                        <a href="?q=up_wajah" class="menu-link">
                            <i class="menu-icon fa-solid fa-person-chalkboard"></i>
                            <div data-i18n="Basic">Upload Wajah</div>
                        </a>
                    </li>
                    <li
                        class="menu-item <?php echo (isset($_GET['q']) && $_GET['q'] == 'absen_keluar') ? 'active' : ''; ?>">
                        <a href="absen_keluar" class="menu-link">
                            <i class="menu-icon fa-solid fa-person-walking-dashed-line-arrow-right"></i>
                            <div data-i18n="Basic">Absensi Keluar</div>
                        </a>
                    </li>
                    <?php
                    // Periksa peran pengguna sebelum menampilkan menu "Laporan Absensi"
                    if ($user_data['role'] === 'admin') {
                    ?>
                    <li
                        class="menu-item <?php echo (isset($_GET['q']) && $_GET['q'] == 'laporan_absen') ? 'active' : ''; ?>">
                        <a href="?q=laporan_absen" class="menu-link">
                            <i class="menu-icon fa-solid fa-clipboard"></i>
                            <div data-i18n="Basic">Laporan Absensi</div>
                        </a>
                    </li>
                    <?php
                    }
                    ?>
                    <!-- End Content -->

                    <!-- Content-->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengaturan</span></li>
                    <li
                        class="menu-item <?php echo (isset($_GET['q']) && $_GET['q'] == 'profil_saya') ? 'active' : ''; ?>">
                        <a href="?q=profil_saya" class="menu-link">
                            <i class="menu-icon fa-solid fa-user"></i>
                            <div data-i18n="Basic">Profil Saya</div>
                        </a>
                    </li>

                    <?php
                    // Periksa peran pengguna sebelum menampilkan menu "Users"
                    if ($user_data['role'] === 'admin') {
                    ?>
                    <li class="menu-item <?php echo (isset($_GET['q']) && $_GET['q'] == 'users') ? 'active' : ''; ?>">
                        <a href="?q=users" class="menu-link">
                            <i class="menu-icon fa-solid fa-users"></i>
                            <div data-i18n="Basic">Users</div>
                        </a>
                    </li>
                    <?php
                    }
                    ?>
                    <!-- End Content -->
                </ul>


            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center" id="dateTimeContainer">
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?php echo $profile_picture_path; ?>" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?php echo $profile_picture_path; ?>" alt
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block"><?= $username ?></span>
                                                    <small class="text-muted">Selamat Datang, Bro!</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="?q=profil_set">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Profil Saya</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Pengaturan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="logout.php">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Keluar</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->


                <?php
                include 'link.php';
                ?>


                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            ©
                            <script>
                            document.write(new Date().getFullYear());
                            </script>
                            , made with ❤️ by
                            <a href="#" target="_blank" class="footer-link fw-bolder">Andi</a>
                        </div>
                        <div>
                            <a href="#" class="footer-link me-4" target="_blank">Love
                                by Andi</a>

                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Memulai Typed.js untuk menampilkan teks
        var typed = new Typed('.typed-text', {
            strings: [
                "<span class='fw-bold'>SISTEM ABSENSI CERDAS PENGENALAN WAJAH MENGGUNAKAN DEEP TRANSFER LEARNING PADA DINAS PENYELENGGARA PELABUHAN KELAS II AMURANG</span>"
            ],
            typeSpeed: 50,
            loop: true,
            loopCount: Infinity,
            showCursor: false,
            backDelay: 3000,
            contentType: 'html'
        });
    });
    </script>

    <script>
    // Fungsi untuk menampilkan modal dan mengatur nilai ID pengguna sebelumnya
    function deleteUser(userId) {
        // Set nilai ID pengguna ke elemen input tersembunyi dalam formulir
        document.getElementById('userIdToDelete').value = userId;

        // Lakukan AJAX request untuk mengambil detail pengguna
        $.ajax({
            url: 'modal_detail_user.php', // Ganti dengan URL yang sesuai untuk mengambil detail pengguna
            method: 'POST',
            data: {
                userId: userId
            },
            success: function(response) {
                // Tampilkan informasi detail pengguna di dalam modal
                document.getElementById('userDetailsText').innerHTML = response;
                // Tampilkan modal
                $('#deleteModal').modal('show');
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil detail pengguna.');
            }
        });
    }
    </script>

    <script>
    // Fungsi untuk mendapatkan tanggal dan waktu saat ini dalam format yang sesuai
    function getCurrentDateTime() {
        const now = new Date();
        const dateOptions = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const timeOptions = {
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: false
        };
        const dateString = now.toLocaleDateString('id-ID', dateOptions);
        const timeString = now.toLocaleTimeString('id-ID', timeOptions);
        return dateString + ' ' + timeString;
    }

    // Fungsi untuk memperbarui konten dengan tanggal dan waktu saat ini
    function updateDateTime() {
        const dateTimeContainer = document.getElementById('dateTimeContainer');
        if (dateTimeContainer) {
            dateTimeContainer.textContent = getCurrentDateTime();
        }
    }

    // Memanggil fungsi updateDateTime setiap detik untuk memperbarui tanggal dan waktu
    setInterval(updateDateTime, 1000);
    </script>

</body>

</html>