<?php
include 'conf/conf.php';

// Menerima parameter start_date dan end_date dari URL
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

// Mendapatkan bulan dan tahun dari tanggal awal dan akhir
$start_month = date('m', strtotime($start_date));
$start_year = date('Y', strtotime($start_date));
$end_month = date('m', strtotime($end_date));
$end_year = date('Y', strtotime($end_date));

?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Content /</span> Laporan Absensi</h4>

        <?php
        // Perulangan untuk setiap bulan dan tahun dalam rentang tanggal
        for ($year = $start_year; $year <= $end_year; $year++) {
            for ($month = ($year == $start_year ? $start_month : 1); $month <= ($year == $end_year ? $end_month : 12); $month++) {
                // Mendapatkan tanggal awal dan akhir untuk bulan saat ini
                $first_day = date('Y-m-01', strtotime("$year-$month-01"));
                $last_day = date('Y-m-t', strtotime("$year-$month-01"));
        ?>

        <div class="card mt-4">
            <div class="table-responsive text-nowrap">
                <p><strong>Periode:</strong> <?= date('F Y', strtotime($first_day)) ?></p>
                <?php
                        // Query untuk mengambil data absen dari database berdasarkan rentang tanggal dengan parameterized query
                        $sql = "SELECT * FROM absen WHERE tanggal_absen BETWEEN ? AND ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $first_day, $last_day);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Jika ada data absen untuk bulan ini, tampilkan tabel
                        if ($result->num_rows > 0) :
                        ?>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Absen</th>
                            <th>Jam Absen Masuk</th>
                            <th>Jam Absen Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['tanggal_absen'] ?></td>
                            <td><?= $row['waktu_absen_masuk'] ?></td>
                            <td><?= $row['waktu_absen_keluar'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else : ?>
                <p>Tidak ada data absen untuk bulan ini.</p>
                <?php endif; ?>

                <?php
                        // Pastikan untuk menutup statement setelah penggunaan selesai
                        $stmt->close();
                        ?>
            </div>
            <div class="text-end mt-3">
                <button onclick="window.print()" class="btn btn-primary">Cetak Laporan Bulan
                    <?= date('F Y', strtotime($first_day)) ?></button>
            </div>
        </div>

        <?php }
        } ?>
    </div>
</div>