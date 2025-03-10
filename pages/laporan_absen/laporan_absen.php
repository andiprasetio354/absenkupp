<?php
include 'conf/conf.php';

// Inisialisasi variabel untuk filter tanggal
$tanggalFilter = "";

// Jika tanggal pencarian diset dan tidak kosong, atur variabel filter
if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
    $tanggalFilter = $_GET['tanggal'];
}

// Mendapatkan tanggal awal dan akhir bulan berdasarkan tanggal sekarang
$firstDayOfMonth = date('Y-m-01');
$lastDayOfMonth = date('Y-m-t');

// Query untuk mengambil data absen dari database berdasarkan filter tanggal
$sql = "SELECT * FROM absen";
if (!empty($tanggalFilter)) {
    $sql .= " WHERE DATE_FORMAT(tanggal_absen, '%Y-%m-%d') = '$tanggalFilter'";
}
$result = $conn->query($sql);
?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Content /</span> Laporan Absensi</h4>
        <div class="card mt-4">
            <h5 class="card-header">Filter Tanggal</h5>
            <div class="card-body">
                <form method="GET" action="my">
                    <input type="hidden" name="q" value="laporan_absen">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="mb-2" for="tanggal">Cari Tanggal</label>
                            <!-- Menggunakan input type="date" untuk filter tanggal -->
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="<?php echo isset($tanggalFilter) ? $tanggalFilter : ''; ?>">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Cari Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Data Absen</h5>
                </div>
                <div class="text-end">
                    <?php
                    // Menampilkan tombol cetak data selama satu bulan
                    $currentMonth = date('m');
                    $currentYear = date('Y');
                    $printStartDate = date('Y-m-d', strtotime("$currentYear-$currentMonth-01"));
                    $printEndDate = date('Y-m-t', strtotime("$currentYear-$currentMonth-01"));
                    ?>
                    <a href="?q=print_data&start_date=<?= $printStartDate ?>&end_date=<?= $printEndDate ?>"
                        class="btn btn-info mx-2">Cetak Data</a>
                    <a href="?q=export_excel" class="btn btn-success" id="exportExcelButton">Export to Excel</a>
                    <a href="?q=export_csv" class="btn btn-warning mx-2" id="exportCSVButton">Export to CSV</a>
                    <a href="?q=d_all" class="btn btn-danger" id="deleteAllButton">Delete All</a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Absen</th>
                            <th>Jam Absen Masuk</th>
                            <th>Jam Absen Keluar</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 text-center">
                        <?php if ($result->num_rows > 0) :
                            $no = 1;
                            while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['tanggal_absen'] ?></td>
                            <td><?= $row['waktu_absen_masuk'] ?></td>
                            <td><?= $row['waktu_absen_keluar'] ?></td>
                            <td><img src="./<?= $row['wajah'] ?>" width="100" height="100"></td>
                        </tr>
                        <?php endwhile;
                        else : ?>
                        <tr>
                            <td colspan="5">Tidak ada data absen</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>