   <!-- Content wrapper -->
   <div class="content-wrapper">
       <div class="container-xxl flex-grow-1 container-p-y">
           <?php
            // Ambil lokasi foto profil dari basis data sesuai dengan user yang sedang login
            $query = "SELECT profile_picture FROM users WHERE id = {$_SESSION['user_id']}";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $profile_picture_path = $row['profile_picture'];

                // Periksa apakah foto profil sudah diunggah
                if (empty($profile_picture_path)) {
                    // Alert
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            Anda belum mempunyai foto profil. Silahkan ganti foto profilmu di menu "Profil Saya" | <strong>Tanda Hijau Dikanan Atas</strong> <i class="fa-solid fa-arrow-up-right-dots"></i>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
            }
            // Ambil username pengguna yang sedang login dari sesi
            $username = $_SESSION['username'];

            // Query untuk mengambil data pengguna berdasarkan username
            $query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $query);
            ?>
           <div class="row">
               <div class="col-lg-8 mb-4 order-0">
                   <div class="card">
                       <div class="d-flex align-items-end row">
                           <div class="col-sm-7">
                               <div class="card-body">
                                   <h5 class="card-title text-primary">Hai, <?= $username ?> Apa kabarmu hari ini?</h5>
                                   <p>Selamat Datang di</p>
                                   <p class="mb-4 typed-text"></p>
                               </div>
                           </div>
                           <div class="col-sm-5 text-center text-sm-left">
                               <div class="card-body pb-0 px-0 px-md-4">
                                   <img src="assets/img/illustrations/man-with-laptop-light.png" height="140"
                                       alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                       data-app-light-img="illustrations/man-with-laptop-light.png" />
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-lg-4 col-md-4 order-1">
                   <div class="row">
                       <div class="col-lg-6 col-md-12 col-6 mb-4">
                           <div class="card">
                               <div class="card-body">
                                   <div class="card-title d-flex align-items-start justify-content-between">
                                       <div class="avatar flex-shrink-0">
                                           <img src="assets/img/icons/unicons/chart-success.png" alt="chart success"
                                               class="rounded" />
                                       </div>
                                       <div class="dropdown">
                                           <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false">
                                               <i class="bx bx-dots-vertical-rounded"></i>
                                           </button>
                                           <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                               <a class="dropdown-item" href="javascript:void(0);">View
                                                   More</a>
                                           </div>
                                       </div>
                                   </div>
                                   <?php
                                    // Ambil jumlah total pengguna dari tabel users
                                    $sqlTotalUsers = "SELECT COUNT(*) as total_users FROM users";
                                    $resultTotalUsers = mysqli_query($conn, $sqlTotalUsers);
                                    $rowTotalUsers = mysqli_fetch_assoc($resultTotalUsers);
                                    $totalUsers = $rowTotalUsers['total_users'];
                                    ?>
                                   <span class="fw-semibold d-block mb-1">Jumlah User</span>
                                   <h3 class="card-title mb-2"><?php echo $totalUsers; ?></h3>
                               </div>
                           </div>
                       </div>
                       <div class="col-lg-6 col-md-12 col-6 mb-4">
                           <div class="card">
                               <div class="card-body">
                                   <div class="card-title d-flex align-items-start justify-content-between">
                                       <div class="avatar flex-shrink-0">
                                           <img src="assets/img/icons/unicons/wallet-info.png" alt="Credit Card"
                                               class="rounded" />
                                       </div>
                                       <div class="dropdown">
                                           <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false">
                                               <i class="bx bx-dots-vertical-rounded"></i>
                                           </button>
                                           <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                               <a class="dropdown-item" href="javascript:void(0);">View
                                                   More</a>
                                           </div>
                                       </div>
                                   </div>
                                   <?php
                                    // Ambil jumlah total pengguna dari tabel users
                                    $sqlDataAbsensi = "SELECT COUNT(*) as data_absensi FROM absen";
                                    $resultDataAbsensi = mysqli_query($conn, $sqlDataAbsensi);
                                    $rowDataAbsensi = mysqli_fetch_assoc($resultDataAbsensi);
                                    $dataAbsensi = $rowDataAbsensi['data_absensi'];
                                    ?>
                                   <span class="fw-semibold d-block mb-1">Data Absensi</span>
                                   <h3 class="card-title text-nowrap mb-2"><?php echo $dataAbsensi; ?></h3>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-12 col-lg-7 order-2 order-md-3 order-lg-2 mb-4">
                   <div class="card">
                       <div class="row">
                           <div class="col-md-12">
                               <h5 class="card-header text-primary m-0 ">Judul 1</h5>
                               <div class="card-body">
                                   <p class="card-text">
                                       Sistem inovatif yang digunakan dalam manajemen kehadiran di Dinas Penyelenggara
                                       Pelabuhan Kelas II Amurang
                                   </p>
                               </div>
                           </div>
                           <div class="col-md-12">
                               <h5 class="card-header text-primary m-0">Judul 2</h5>
                               <div class="card-body">
                                   <p class="card-text">
                                       Sistem ini didesain untuk memanfaatkan teknologi pengenalan wajah yang cerdas dan
                                       canggih, yang dikembangkan melalui pendekatan Deep Transfer Learning.
                                   </p>
                               </div>
                           </div>
                           <div class="col-md-12">
                               <h5 class="card-header text-primary m-0">Judul 2</h5>
                               <div class="card-body">
                                   <p class="card-text">
                                       Sistem absensi cerdas ini bertujuan untuk meningkatkan efisiensi dalam proses
                                       pencatatan kehadiran para pegawai di lingkungan pelabuhan.
                                   </p>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-6 col-lg-5 order-2 order-md-3 order-lg-2 mb-4">
                   <div class="card">
                       <div class="row">
                           <div class="col-md-12 video-container-beranda">
                               <video width="100%" height="auto" controls autoplay muted loop>
                                   <source src="assets/video/3.mp4" type="video/mp4">
                               </video>
                           </div>
                       </div>
                   </div>
               </div>
           </div>


       </div>
   </div>


   <!-- Bagian Mid-Bawah -->