<?php
// Ambil label dari database
$result = $conn->query("SELECT nama_label FROM tbl_labels");
$labels = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['nama_label'];
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h2>Tambah Label Baru</h2>
                                <form action="?q=proses_up_label" method="POST">
                                    <!-- Input untuk Nama Label Baru -->
                                    <div class="mb-3">
                                        <label for="nama_label_baru" class="form-label">Nama Label Baru:</label>
                                        <input type="text" name="nama_label_baru" id="nama_label_baru"
                                            class="form-control" placeholder="Masukkan nama label" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Tambah Label</button>
                                </form>
                                <hr>

                                <h2>Upload ke Label yang Sudah Ada</h2>
                                <form action="?q=proses_up_wajah" method="POST" enctype="multipart/form-data">
                                    <!-- Pilih Label yang Sudah Ada -->
                                    <div class="mb-3">
                                        <label for="nama_label" class="form-label">Pilih Label:</label>
                                        <select name="nama_label" id="nama_label" class="form-control" required>
                                            <option value="" disabled selected>Pilih Label</option>
                                            <?php foreach ($labels as $label): ?>
                                                <option value="<?= htmlspecialchars($label) ?>">
                                                    <?= htmlspecialchars($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Input untuk Upload File -->
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Pilih File (JPG):</label>
                                        <input type="file" name="file" id="file" class="form-control"
                                            accept="image/jpeg" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>