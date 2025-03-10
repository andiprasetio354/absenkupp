<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div id="custom-alert" class="alert alert-warning alert-dismissible fade show" role="alert">
            Anda tidak bisa menghapus akunmu sendiri!!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Users</h4>
        <div class="card mt-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Data Users</h5>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Foto</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 text-center">
                        <?php
                        include 'conf/conf.php';

                        $currentUserId = $_SESSION['user_id']; // Ambil ID pengguna yang sedang login

                        $query = "SELECT id, username, profile_picture FROM users";
                        $result = mysqli_query($conn, $query);
                        $no = 1;
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $userId = $row['id'];
                                $disabled = ($currentUserId == $userId) ? "disabled" : ""; // Check apakah pengguna saat ini adalah pengguna dengan ID yang sedang di-loop

                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['username'] ?></td>
                                    <td>
                                        <?php if (!empty($row['profile_picture'])) : ?>
                                            <img src="<?= $row['profile_picture'] ?>" width="50" height="50" alt="Foto Profil">
                                        <?php else : ?>
                                            <span class="text-muted">Tidak ada foto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Memanggil fungsi deleteUser() saat tombol Delete ditekan -->
                                        <button type="button" class="btn btn-danger" onclick="deleteUser(<?= $userId ?>)" <?= $disabled ?>>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada data pengguna</td></tr>";
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk penghapusan pengguna -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="userDetailsText"></p>
                <!-- Form untuk mengirimkan permintaan penghapusan pengguna -->
                <form method="POST" action="?q=d_users" id="deleteForm">
                    <!-- Input tersembunyi untuk menyimpan ID pengguna -->
                    <input type="hidden" id="userIdToDelete" name="userId" value="">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <!-- Tombol "Delete" di dalam form -->
                <button type="submit" form="deleteForm" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk menampilkan alert dan mengatur waktu tampilnya
    document.addEventListener('DOMContentLoaded', function() {
        var alertElement = document.getElementById('custom-alert');
        // Set timeout untuk menghapus alert setelah 10 detik
        setTimeout(function() {
            alertElement.remove();
        }, 10000); // 10 detik

    });
</script>