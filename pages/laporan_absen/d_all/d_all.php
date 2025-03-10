<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <?php
            include 'conf/conf.php';

            $sql = "DELETE FROM absen";
            if ($conn->query($sql) === true) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Success',
                        text: 'Semua data absen berhasil dihapus.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '?q=laporan_absen';
                        }
                    });
                </script>
            <?php
            } else {
            ?>
                <script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Error: <?= $sql . "<br>" . $conn->error ?>',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '?q=laporan_absen';
                        }
                    });
                </script>
            <?php
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>