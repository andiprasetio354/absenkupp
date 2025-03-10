

// Ambil elemen video dari DOM
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const absenMessage = document.getElementById('absen-message');
const ambilAbsenButton = document.getElementById('ambilAbsenButton');

// Variabel untuk status deteksi wajah
let isFaceDetectionRunning = false;

// Inisialisasi model FaceAPI
Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
    faceapi.nets.tinyFaceDetector.loadFromUri('models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('models'),
]).then(startWebcam);

// Fungsi untuk memulai webcam
function startWebcam() {
    navigator.mediaDevices
        .getUserMedia({
            video: true,
            audio: false,
        })
        .then((stream) => {
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                // Setelah video dimuat sepenuhnya, dapatkan dimensinya
                video.width = video.videoWidth;
                video.height = video.videoHeight;
            };
        })
        .catch((error) => {
            console.error(error);
        });
}

// Event listener untuk video saat dimulai
video.addEventListener('play', () => {
    // Gunakan interval untuk terus mendeteksi wajah
    setInterval(async () => {
        const detections = await faceapi
            .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks();

        // Bersihkan canvas sebelum menggambar
        context.clearRect(0, 0, canvas.width, canvas.height);

        // Gambar deteksi wajah ke canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        faceapi.draw.drawDetections(canvas, detections);
        faceapi.draw.drawFaceLandmarks(canvas, detections);

        // Debugging: Cetak deteksi wajah ke konsol
        console.log(detections);
    }, 100);
});

// Fungsi untuk mendapatkan deskripsi wajah berlabel
async function getLabeledFaceDescriptions(labels) {
    return Promise.all(
        labels.map(async (label) => {
            const descriptions = [];
            for (let i = 1; i <= 2; i++) {
                const img = await faceapi.fetchImage(`./labels/${label}/${i}.jpg`);
                const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                descriptions.push(detections.descriptor);
            }
            return new faceapi.LabeledFaceDescriptors(label, descriptions);
        })
    );
}

// Event listener untuk tombol "Ambil Absen"
ambilAbsenButton.addEventListener('click', async () => {
   // Ambil nama dari folder label
let labels = [];

async function fetchLabels() {
    try {
        const response = await fetch('get_labels.php'); // Ambil data folder dari server
        if (response.ok) {
            labels = await response.json(); // Simpan nama folder ke dalam array labels
            console.log("Labels yang diambil:", labels); // Debugging
        } else {
            console.error("Gagal mengambil label dari server.");
        }
    } catch (error) {
        console.error("Error mengambil label:", error);
    }
}

// Panggil fungsi untuk mengambil labels sebelum tombol "Ambil Absen" digunakan
await fetchLabels();


    // Ambil tanggal sekarang
    const currentDate = new Date().toLocaleDateString();

    // Ambil jam sekarang untuk jam absen masuk dan keluar
    const currentTime = new Date();
    const currentHours = currentTime.getHours().toString().padStart(2, '0');
    const currentMinutes = currentTime.getMinutes().toString().padStart(2, '0');
    const currentTimeString = `${currentHours}:${currentMinutes}`;

    // Isi nilai jam absen masuk dan keluar dengan waktu sekarang
    document.getElementById('waktuAbsenMasukInput').value = currentTimeString;
    document.getElementById('waktuAbsenKeluarInput').value = currentTimeString;

    // Dapatkan deskripsi wajah berlabel
    const labeledFaceDescriptors = await getLabeledFaceDescriptions(labels);
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

    // Lakukan deteksi wajah pada video
const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();

// Validasi jika tidak ada wajah yang terdeteksi
if (!detections.length) {
    alert("Wajah tidak terdeteksi. Silakan posisikan wajah Anda di depan kamera dan ulangi.");
    return;
}

// Perkecil deteksi sesuai dengan dimensi video
const resizedDetections = faceapi.resizeResults(detections, {
    width: video.width,
    height: video.height,
});

// Validasi jika deskripsi wajah kosong
if (!resizedDetections[0] || !resizedDetections[0].descriptor) {
    alert("Gagal mendapatkan deskripsi wajah. Silakan ulangi.");
    return;
}

// Ambil nama dari hasil pencocokan
const result = faceMatcher.findBestMatch(resizedDetections[0].descriptor);
const nama = result.label;

    // Kirim data ke server untuk memeriksa apakah pengguna sudah absen atau belum
    const response = await fetch('check_absen.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'nama=' + encodeURIComponent(nama),
    });

    if (response.ok) {
        const responseData = await response.json();

            // Masukkan nama ke dalam field nama
            const namaInput = document.getElementById('namaInput');
            namaInput.value = nama;

            // Masukkan tanggal sekarang ke dalam field tanggal absen
            const tanggalAbsenInput = document.getElementById('tanggalAbsenInput');
            tanggalAbsenInput.value = currentDate;

            // Kirim data absen ke server
            await sendAbsenDataToServer(nama, currentDate, currentTimeString, currentTimeString, canvas.toDataURL('image/jpeg'));
        // }
    } else {
        console.error('Terjadi kesalahan saat memeriksa status absen.');
    }
});


async function sendAbsenDataToServer(nama, tanggalAbsen, waktuAbsenMasuk, waktuAbsenKeluar, imageDataURL) {
    const formData = new FormData();
    formData.append('nama', nama);
    formData.append('tanggal_absen', tanggalAbsen);
    formData.append('waktu_absen_masuk', waktuAbsenMasuk);
    formData.append('waktu_absen_keluar', waktuAbsenKeluar);

    // Mengambil data dari data URL dan mengonversi menjadi Blob
    const imageBlob = await fetch(imageDataURL).then((res) => res.blob());

    // Membuat nama file unik dengan menambahkan penanda waktu
    const timestamp = new Date().getTime(); // Waktu saat ini dalam milidetik
    const fileName = `wajah_${timestamp}.jpg`; // Nama file unik dengan penanda waktu

    // Simpan blob dengan nama file yang unik
    formData.append('wajah', imageBlob, fileName);

    try {
        // Kirim data ke server menggunakan Fetch API
        const response = await fetch('proses_absen.php', {
            method: 'POST',
            body: formData,
        });
    
        // Periksa status respons dari server
        if (response.status === 200) {
            // Jika berhasil, tampilkan swal sukses
            Swal.fire({
                icon: 'success',
                title: 'Anda telah berhasil melakukan absen',
                text: 'Data absen telah disimpan.',
                confirmButtonText: 'Ok',
            });
            console.log('Data berhasil disimpan.');
        } else {
            // Jika gagal, tampilkan swal error
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan',
                text: 'Terjadi kesalahan saat menyimpan data absen.',
                confirmButtonText: 'Coba Lagi',
            });
            console.error('Terjadi kesalahan saat menyimpan gambar wajah.');
        }
    } catch (error) {
        // Jika terjadi kesalahan jaringan, tampilkan swal error
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Terjadi kesalahan jaringan. Mohon coba lagi.',
            confirmButtonText: 'Ok',
        });
        console.error('Kesalahan jaringan:', error);
    }
    
}




