// Ambil elemen video dari DOM
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const absenMessage = document.getElementById('absen-message');
const ambilAbsenButton = document.getElementById('ambilAbsenButton');

// Variabel untuk status deteksi wajah
let isFaceDetectionRunning = false;
let hasCheckedIn = false; // Cegah pengulangan absen
let faceMatcher; // Variabel global untuk FaceMatcher
let labels = []; // Simpan label nama pengguna dari server

// Inisialisasi model FaceAPI
Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
    faceapi.nets.tinyFaceDetector.loadFromUri('models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('models'),
]).then(() => {
    console.log("FaceAPI models loaded.");
    startWebcam();
    initializeFaceMatcher(); // Pastikan FaceMatcher dibuat setelah model siap
});

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
                video.width = video.videoWidth;
                video.height = video.videoHeight;
            };
        })
        .catch((error) => {
            console.error("Error accessing webcam:", error);
        });
}

// Fungsi untuk mendapatkan label wajah dari server
async function fetchLabels() {
    try {
        const response = await fetch('get_labels.php'); // Ambil data dari server
        if (response.ok) {
            labels = await response.json();
            console.log("Labels yang diambil:", labels);
        } else {
            console.error("Gagal mengambil label dari server.");
        }
    } catch (error) {
        console.error("Error mengambil label:", error);
    }
}

// Inisialisasi FaceMatcher setelah mendapatkan deskripsi wajah
async function initializeFaceMatcher() {
    await fetchLabels(); // Ambil nama label dari server
    const labeledFaceDescriptors = await getLabeledFaceDescriptions(labels);
    faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);
    console.log("FaceMatcher berhasil diinisialisasi!");
}

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

// Event listener untuk video saat dimulai (deteksi wajah otomatis)
video.addEventListener('play', () => {
    setInterval(async () => {
        if (!faceMatcher || hasCheckedIn) return; // Tunggu FaceMatcher siap, cegah spam absen

        const detections = await faceapi
            .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptors();

        if (!detections.length) {
            console.log("Wajah tidak terdeteksi.");
            return;
        }

        // Perkecil deteksi sesuai dimensi video
        const resizedDetections = faceapi.resizeResults(detections, {
            width: video.width,
            height: video.height,
        });

        if (!resizedDetections[0] || !resizedDetections[0].descriptor) {
            console.log("Deskripsi wajah tidak ditemukan.");
            return;
        }

        // Ambil nama dari hasil pencocokan
        const result = faceMatcher.findBestMatch(resizedDetections[0].descriptor);
        const nama = result.label;

        // Pastikan hanya absen jika wajah dikenali
        if (nama !== "unknown") {
            hasCheckedIn = true; // Tandai absen sudah dilakukan
            await autoAbsen(nama);
        }
    }, 1000); // Periksa setiap 1 detik
});

// Fungsi otomatis melakukan absen
async function autoAbsen(nama) {
    // Ambil tanggal dan waktu sekarang
    const currentDate = new Date().toLocaleDateString();
    const currentTime = new Date();
    const currentHours = currentTime.getHours().toString().padStart(2, '0');
    const currentMinutes = currentTime.getMinutes().toString().padStart(2, '0');
    const currentTimeString = `${currentHours}:${currentMinutes}`;

    // Isi input nama dan tanggal
    document.getElementById('namaInput').value = nama;
    document.getElementById('tanggalAbsenInput').value = currentDate;
    document.getElementById('waktuAbsenMasukInput').value = currentTimeString;
    document.getElementById('waktuAbsenKeluarInput').value = currentTimeString;

    // Kirim data ke server
    await sendAbsenDataToServer(nama, currentDate, currentTimeString, currentTimeString, canvas.toDataURL('image/jpeg'));

    // Tampilkan pesan sukses
    Swal.fire({
        icon: 'success',
        title: 'Absen Berhasil',
        text: 'Anda telah berhasil melakukan absen.',
        confirmButtonText: 'Ok',
    });

    console.log('Absen otomatis berhasil:', nama);
}

// Fungsi untuk mengirim data ke server
async function sendAbsenDataToServer(nama, tanggalAbsen, waktuAbsenMasuk, waktuAbsenKeluar, imageDataURL) {
    const formData = new FormData();
    formData.append('nama', nama);
    formData.append('tanggal_absen', tanggalAbsen);
    formData.append('waktu_absen_masuk', waktuAbsenMasuk);
    formData.append('waktu_absen_keluar', waktuAbsenKeluar);

    // Mengambil data dari data URL dan mengonversi menjadi Blob
    const imageBlob = await fetch(imageDataURL).then((res) => res.blob());

    // Membuat nama file unik dengan menambahkan penanda waktu
    const timestamp = new Date().getTime();
    const fileName = `wajah_${timestamp}.jpg`;

    // Simpan blob dengan nama file yang unik
    formData.append('wajah', imageBlob, fileName);

    try {
        // Kirim data ke server menggunakan Fetch API
        const response = await fetch('proses_absen.php', {
            method: 'POST',
            body: formData,
        });

        if (response.status === 200) {
            console.log('Data absen berhasil disimpan.');
        } else {
            console.error('Terjadi kesalahan saat menyimpan data absen.');
        }
    } catch (error) {
        console.error('Kesalahan jaringan:', error);
    }
}
