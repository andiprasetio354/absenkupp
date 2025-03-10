<?php
@$page = $_GET['q'];
if (!empty($page)) {
    switch ($page) {


        case 'beranda':
            include './pages/beranda/beranda.php';
            break;

        case 'absen':
            include './pages/absen/absen.php';
            break;

        case 'print_data':
            include './pages/print_data/print_data.php';
            break;

        case 'absen_keluar':
            include './pages/absen_keluar/absen_keluar.php';
            break;

        case 'profil_set':
            include './pages/profil_set/profil_set.php';
            break;

        case 'profil_saya':
            include './pages/profil_saya/profil_saya.php';
            break;

        case 'users':
            include './pages/users/users.php';
            break;

        case 'd_users':
            include './pages/users/d_users/d_users.php';
            break;

        case 'up_data_user':
            include './pages/profil_set/up_data_user/up_data_user.php';
            break;

        case 'up_photo':
            include './pages/profil_set/up_photo/up_photo.php';
            break;

        case 'laporan_absen':
            include './pages/laporan_absen/laporan_absen.php';
            break;

        case 'd_all':
            include './pages/laporan_absen/d_all/d_all.php';
            break;

        case 'export_excel':
            include './pages/laporan_absen/export_excel/export_excel.php';
            break;

        case 'export_csv':
            include './pages/laporan_absen/export_csv/export_csv.php';
            break;


        case 'up_wajah':
            include './pages/up_wajah/up_wajah.php';
            break;

        case 'proses_up_label':
            include './pages/up_wajah/proses_up_label/proses_up_label.php';
            break;

        case 'proses_up_wajah':
            include './pages/up_wajah/proses_up_wajah/proses_up_wajah.php';
            break;
    }
} else {
    include './pages/beranda/beranda.php';
}
