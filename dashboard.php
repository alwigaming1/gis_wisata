<?php 
include 'config.php'; 
cek_login(); 

// 1. HITUNG TOTAL DATA
$total_wisata = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM wisata"));
$total_kategori = mysqli_num_rows(mysqli_query($koneksi, "SELECT DISTINCT kategori FROM wisata"));

// 2. HITUNG PER KATEGORI (Contoh: Alam)
$total_alam = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM wisata WHERE kategori='Alam'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> body { font-family: 'Outfit', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <?php include 'sidebar.php'; ?>

    <div class="md:ml-64 p-6 md:p-8 min-h-screen transition-all">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Ringkasan Sistem</h1>
                <p class="text-slate-500 text-sm mt-1">Halo, <span class="font-bold text-sky-600"><?= $_SESSION['admin_nama'] ?? 'Administrator' ?></span>! Berikut statistik GIS hari ini.</p>
            </div>
            <div class="flex gap-3">
                <a href="index.php" target="_blank" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-50 transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-globe"></i> Lihat Website
                </a>
                <a href="form_wisata.php" class="bg-sky-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-sky-700 transition shadow-lg shadow-sky-200 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Input Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Lokasi</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $total_wisata ?> <span class="text-xs font-normal text-slate-400">Titik</span></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-tree"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Wisata Alam</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $total_alam ?> <span class="text-xs font-normal text-slate-400">Titik</span></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kategori</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $total_kategori ?> <span class="text-xs font-normal text-slate-400">Jenis</span></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengunjung</p>
                    <h3 class="text-2xl font-bold text-slate-800">1.2k <span class="text-xs font-normal text-green-500">▲ 12%</span></h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-slate-800">Statistik Pengunjung Website</h3>
                    <select class="text-xs bg-slate-50 border border-slate-200 rounded px-2 py-1 outline-none">
                        <option>Minggu Ini</option>
                        <option>Bulan Ini</option>
                    </select>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Baru Ditambahkan</h3>
                <div class="space-y-4">
                    <?php 
                    $recent = mysqli_query($koneksi, "SELECT * FROM wisata ORDER BY id DESC LIMIT 4");
                    while($r = mysqli_fetch_array($recent)){
                        $img_t = (!empty($r['gambar']) && file_exists($r['gambar'])) ? $r['gambar'] : "https://placehold.co/100x100/e2e8f0/cbd5e1";
                    ?>
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-50 last:border-0 last:pb-0">
                        <img src="<?= $img_t ?>" class="w-12 h-12 rounded-lg object-cover bg-slate-100">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-800 truncate"><?= $r['nama_wisata'] ?></h4>
                            <p class="text-xs text-slate-400"><?= $r['kategori'] ?> • <?= $r['harga_tiket'] ?></p>
                        </div>
                        <a href="form_wisata.php?id=<?= $r['id'] ?>" class="text-slate-300 hover:text-sky-600 transition"><i class="fa-solid fa-pen-to-square"></i></a>
                    </div>
                    <?php } ?>
                </div>
                <a href="data_wisata.php" class="block mt-6 text-center text-xs font-bold text-sky-600 hover:underline">Lihat Semua Data →</a>
            </div>

        </div>
    </div>

    <script>
        const ctx = document.getElementById('visitorChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Visitor',
                    data: [120, 190, 150, 250, 220, 300, 380],
                    borderColor: '#0284c7', // Sky-600
                    backgroundColor: 'rgba(2, 132, 199, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0284c7',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>