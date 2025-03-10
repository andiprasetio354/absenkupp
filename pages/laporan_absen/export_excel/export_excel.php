<?php
include './conf/conf.php';
require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['q']) && $_GET['q'] == 'export_excel') {
    // Buat objek Spreadsheet
    $spreadsheet = new Spreadsheet();

    // Set aktifkan sheet pertama
    $sheet = $spreadsheet->getActiveSheet();

    // Buat penanda di baris pertama untuk judul kolom
    $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Nama')
        ->setCellValue('C1', 'Tanggal Absen')
        ->setCellValue('D1', 'Photo');

    // Query untuk mengambil data absen dari database
    $sql = "SELECT * FROM absen";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $rowNumber = 2;
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowNumber, $row['id'])
                ->setCellValue('B' . $rowNumber, $row['nama'])
                ->setCellValue('C' . $rowNumber, $row['tanggal_absen'])
                ->setCellValue('D' . $rowNumber, $row['wajah']);
            $rowNumber++;
        }
    }

    // Nama file dengan timestamp untuk memastikan keunikan
    $fileName = 'laporan_absen_' . date('YmdHis') . '.xlsx';

    // Simpan file Excel ke folder publik dengan nama yang dinamis
    $publicFolderPath = 'exceldownload/';
    $tempFileName = $publicFolderPath . $fileName;
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFileName);

    // Tautkan langsung ke file CSV untuk memicu unduhan dengan nama yang dinamis
    echo "<div class='container-xxl'>
     <div class='authentication-wrapper authentication-basic container-p-y'>
         <div class='authentication-inner py-4'>
             <!-- Forgot Password -->
             <div class='card'>
                 <div class='card-body'>
                     <h4 class='mb-2'>Silahkan unduh file .xlxs dengan menekan tombol <strong>Download Excel</strong></h4>
                     <p class='mb-4'>Harap gunakan dengan baik file yang anda unduh! Terima Kasih</p>
                     <a class='btn btn-success' href='exceldownload/$fileName' download>Download Excel</a>
                     <div class='text-center'>
                         <a href='?q=laporan_absen' class='d-flex align-items-center justify-content-center'>
                             <i class='bx bx-chevron-left scaleX-n1-rtl bx-sm'></i>
                             Kembali
                         </a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
  ";

    // Berhenti eksekusi skrip
    exit;
}
