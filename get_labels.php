<?php
header('Content-Type: application/json');

// Direktori target
$target_dir = 'labels/';

if (is_dir($target_dir)) {
    // Ambil semua folder dari direktori labels
    $folders = array_filter(glob($target_dir . '*'), 'is_dir');
    $labels = array_map('basename', $folders); // Hanya ambil nama folder
    echo json_encode($labels); // Kirim data dalam format JSON
} else {
    echo json_encode([]); // Jika tidak ada folder, kembalikan array kosong
}
