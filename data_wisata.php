<?php include 'config.php'; cek_login(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Wisata - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Outfit', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <?php include 'sidebar.php'; ?>

    <div class="md:ml-64 p-6 md:p-8 min-h-screen transition-all duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Kelola Lokasi</h1>
                <p class="text-slate-500 text-sm mt-1">Manajemen titik koordinat pariwisata.</p>
            </div>
            <a href="form_wisata.php" class="bg-sky-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-sky-700 transition shadow-lg shadow-sky-200 flex items-center gap-2">
                <i class="fa-solid fa-map-pin"></i> 
                <span>Tambah Lokasi</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-5">Destinasi</th>
                            <th class="p-5">Kategori</th>
                            <th class="p-5">Koordinat</th>
                            <th class="p-5">Tiket</th>
                            <th class="p-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        <?php 
                        $sql = mysqli_query($koneksi, "SELECT * FROM wisata ORDER BY id DESC");
                        if(mysqli_num_rows($sql) > 0){
                            while($row = mysqli_fetch_array($sql)){
                                // Cek gambar
                                $img = (!empty($row['gambar']) && file_exists($row['gambar'])) ? $row['gambar'] : "https://placehold.co/100x100/e2e8f0/cbd5e1?text=No+Img";
                        ?>
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-slate-200 border border-slate-200">
                                        <img src="<?= $img ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-base group-hover:text-sky-600 transition"><?= $row['nama_wisata'] ?></div>
                                        <div class="text-xs text-slate-400 truncate max-w-[200px]"><?= $row['alamat'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-600 border border-sky-100">
                                    <?= $row['kategori'] ?>
                                </span>
                            </td>
                            <td class="p-5 font-mono text-xs text-slate-500">
                                <div class="flex flex-col gap-1">
                                    <span>Lat: <span class="font-bold text-slate-700"><?= $row['lat'] ?></span></span>
                                    <span>Lng: <span class="font-bold text-slate-700"><?= $row['lng'] ?></span></span>
                                </div>
                            </td>
                            <td class="p-5 font-medium text-slate-700"><?= $row['harga_tiket'] ?></td>
                            <td class="p-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="form_wisata.php?id=<?= $row['id'] ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="hapus_wisata.php?id=<?= $row['id'] ?>" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='p-8 text-center text-slate-400'>Belum ada data lokasi. Silakan tambah data baru.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>