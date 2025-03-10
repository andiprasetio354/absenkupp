<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-7 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Silahkan Lakukan Absen</h5>
                                <!-- Video container -->
                                <div id="video-container" class="video-container-absensi">
                                    <video id="video" width="100%" height="auto" autoplay muted playsinline></video>
                                    <canvas id="canvas"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Data Diri Kamu</h5>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="namaInput" />
                                </div>
                                <div>
                                    <label for="tanggal_absen" class="form-label">Tanggal Absen</label>
                                    <input type="text" class="form-control" id="tanggalAbsenInput" />
                                </div>
                                <div style="display: none;">
                                    <label for="waktu_absen_masuk" class="form-label">Jam Absen
                                        Masuk</label>
                                    <input type="time" class="form-control" id="waktuAbsenMasukInput" />
                                </div>

                                <div>
                                    <label for="waktu_absen_keluar" class="form-label">Jam Absen
                                        Keluar</label>
                                    <input type="time" class="form-control" id="waktuAbsenKeluarInput" />
                                </div>
                                <div id="absen-message"></div>
                                <button id="ambilAbsenButton" class="btn btn-primary mt-3">Ambil
                                    Absen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ambil parameter dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const qParam = urlParams.get('q');

        // Periksa apakah parameter 'q' adalah 'absen'
        if (qParam === 'absen') {
            // Panggil fungsi untuk memulai webcam
            startWebcam();
        }

        function startWebcam() {
            navigator.mediaDevices
                .getUserMedia({
                    video: true,
                    audio: false,
                })
                .then((stream) => {
                    const video = document.getElementById('video');
                    video.srcObject = stream;
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    </script>