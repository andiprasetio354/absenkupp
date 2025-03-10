<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <?php
        include 'conf/conf.php';

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
                Anda belum mempunyai foto profil. Silahkan ganti foto profilmu di menu "Profil Saya"
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
        }
        ?>
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Profil</h4>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                            Profil</a>
                    </li>
                </ul>
                <div class="card mb-4">
                    <h5 class="card-header">Detail Profil</h5>
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="<?php echo $profile_picture_path; ?>" alt="user-avatar" class="d-block rounded"
                                height="100">
                            <form action="?q=up_photo" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="userId" value="<?php echo $_SESSION['user_id']; ?>">
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Unggah Foto Baru</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" name="file" class="account-file-input" hidden
                                            accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="submit" class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Ubah</span>
                                    </button>
                                    <p class="text-muted mb-0"><span class="t_penting">*</span> Pastikan fotomu
                                        berukuran 200x200 pixels</p>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <form method="POST" action="?q=up_data_user">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="n_profil" class="form-label">Nama Profil</label>
                                    <input class="form-control" type="text" id="n_profil" name="n_profil"
                                        value="<?php echo $_SESSION['username']; ?>" autofocus />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <div class="form-password-toggle">
                                        <label class="form-label" for="pw_baru">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="pw_baru" name="pw_baru"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="basic-default-password2" />
                                            <span class="input-group-text cursor-pointer"><i
                                                    class="bx bx-hide"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <div class="form-password-toggle">
                                        <label class="form-label" for="konf_pw_baru">Konfirmasi Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="konf_pw_baru"
                                                name="konf_pw_baru"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="basic-default-password2" />
                                            <span class="input-group-text cursor-pointer"><i
                                                    class="bx bx-hide"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                                <button type="reset" class="btn btn-outline-secondary">Batal</button>
                            </div>
                        </form>

                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->