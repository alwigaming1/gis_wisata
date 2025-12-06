<?php 
include 'config.php'; 

// LOGIKA PENCARIAN
$cari = isset($_GET['q']) ? $_GET['q'] : '';
$where = "";
if($cari){
    $where = "WHERE nama_wisata LIKE '%$cari%' OR kategori LIKE '%$cari%' OR alamat LIKE '%$cari%'";
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <title>Peta Wisata - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style> 
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; } 
        #map { width: 100%; height: 100%; border-radius: 1.5rem; z-index: 1; }
        /* Tinggi Peta menyesuaikan layar */
        .map-container { position: relative; width: 100%; height: 400px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        @media (min-width: 1024px) { .map-container { height: 550px; } }
    </style>
</head>
<body class="text-slate-800">

    <nav class="fixed w-full z-50 top-0 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all">
        <div class="container mx-auto px-4 md:px-8 h-20 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2.5 hover:opacity-80 transition">
                <div class="w-10 h-10 bg-sky-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-sky-200">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <span class="font-bold text-xl tracking-tight text-slate-800 hidden md:block">Geo<span class="text-sky-600">Wisata</span></span>
            </a>
            
            <div class="flex items-center gap-3">
                <a href="#list" class="hidden md:block text-sm font-semibold text-slate-500 hover:text-sky-600 transition px-3">Destinasi</a>
                <a href="login.php" class="bg-slate-900 text-white px-5 py-2.5 rounded-full font-bold text-xs md:text-sm hover:bg-slate-800 transition shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-user-lock"></i> Admin
                </a>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-12 px-4 md:px-8 relative bg-white">
        <div class="container mx-auto max-w-6xl">
            
            <div class="text-center max-w-3xl mx-auto mb-10">
                <span class="inline-block py-1 px-3 rounded-full bg-sky-50 text-sky-700 text-xs font-bold uppercase tracking-wider mb-4 border border-sky-100">
                    🗺️ Jelajahi Kota
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Temukan Destinasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-indigo-600">Impianmu</span>
                </h1>
                
                <form action="index.php#explore" method="GET" class="relative max-w-lg mx-auto mb-6 group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-sky-400 to-indigo-400 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-300"></div>
                    <div class="relative bg-white rounded-full p-2 shadow-xl flex items-center border border-gray-100">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 ml-4"></i>
                        <input type="text" name="q" value="<?= $cari ?>" placeholder="Cari pantai, gunung, museum..." class="w-full py-3 px-4 bg-transparent outline-none text-slate-700 font-medium">
                        <button type="submit" class="bg-slate-900 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-sky-600 transition shadow-lg">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </form>

                <div class="flex flex-wrap justify-center gap-2 text-sm font-bold text-slate-500">
                    <a href="?q=Alam" class="bg-gray-100 hover:bg-sky-100 hover:text-sky-600 px-3 py-1 rounded-full transition">Alam</a>
                    <a href="?q=Kuliner" class="bg-gray-100 hover:bg-sky-100 hover:text-sky-600 px-3 py-1 rounded-full transition">Kuliner</a>
                    <a href="?q=Sejarah" class="bg-gray-100 hover:bg-sky-100 hover:text-sky-600 px-3 py-1 rounded-full transition">Sejarah</a>
                </div>
            </div>

            <div class="map-container bg-white p-2 rounded-[2rem] border-4 border-white">
                <div id="map"></div>
                <div class="absolute bottom-6 left-6 z-[400] bg-white/90 backdrop-blur px-4 py-3 rounded-xl shadow-lg border border-white/50 hidden md:flex items-center gap-3">
                    <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center text-sky-600"><i class="fa-solid fa-map-pin"></i></div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase">Total Lokasi</p>
                        <p class="text-lg font-extrabold text-slate-800"><?= mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM wisata")) ?> Destinasi</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="list" class="py-16 md:py-24 bg-slate-50 border-t border-slate-200">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Daftar Wisata</h2>
                <?php if($cari): ?>
                    <a href="index.php" class="text-red-500 font-bold text-sm bg-red-100 px-4 py-2 rounded-lg hover:bg-red-200 transition"><i class="fa-solid fa-times mr-1"></i> Reset</a>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php 
                $sql = mysqli_query($koneksi, "SELECT * FROM wisata $where ORDER BY id DESC");
                if(mysqli_num_rows($sql) > 0){
                    while($r = mysqli_fetch_array($sql)){
                        $img_url = (!empty($r['gambar']) && file_exists($r['gambar'])) ? $r['gambar'] : "https://placehold.co/600x400/sky/white?text=".urlencode($r['nama_wisata']);
                ?>
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-2 transition duration-300 group cursor-pointer" onclick="flyTo(<?= $r['lat'] ?>, <?= $r['lng'] ?>)">
                    <div class="relative h-48 overflow-hidden">
                        <img src="<?= $img_url ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-slate-800 shadow-sm">
                            <?= $r['kategori'] ?>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-slate-800 mb-1 truncate"><?= $r['nama_wisata'] ?></h3>
                        <p class="text-slate-400 text-xs mb-4 line-clamp-1"><i class="fa-solid fa-location-dot text-sky-500 mr-1"></i> <?= $r['alamat'] ?></p>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded"><?= $r['harga_tiket'] ?></span>
                            <button class="text-sky-600 text-xs font-bold hover:underline flex items-center gap-1">Lihat Peta <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<div class='col-span-full py-10 text-center text-slate-400'>Lokasi tidak ditemukan.</div>";
                }
                ?>
            </div>
        </div>
    </section>

    <footer class="bg-white py-8 border-t border-slate-200 text-center text-sm text-slate-400">
        © <?= date('Y') ?> <?= $conf['app_name'] ?>. All Rights Reserved.
    </footer>

    <script>
        var map = L.map('map', { scrollWheelZoom: false }).setView([-6.175392, 106.827153], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' }).addTo(map);

        var customIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#0284c7; width: 15px; height: 15px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);'></div>",
            iconSize: [20, 20], iconAnchor: [10, 10]
        });

        <?php 
        mysqli_data_seek($sql, 0); 
        while($d = mysqli_fetch_array($sql)){
            $popupImg = (!empty($d['gambar']) && file_exists($d['gambar'])) ? $d['gambar'] : "https://placehold.co/100x70/sky/white";
        ?>
            var content = `
                <div style="text-align:center; min-width:150px">
                    <img src="<?= $popupImg ?>" style="width:100%; height:90px; object-fit:cover; border-radius:8px; margin-bottom:5px">
                    <b style="font-size:14px; color:#0f172a"><?= $d['nama_wisata'] ?></b><br>
                    <span style="font-size:11px; color:#64748b"><?= substr($d['alamat'],0,30) ?>...</span><br>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $d['lat'] ?>,<?= $d['lng'] ?>" target="_blank" style="display:block; margin-top:5px; background:#0f172a; color:white; padding:4px; font-size:10px; border-radius:4px; text-decoration:none">Rute Google Maps</a>
                </div>
            `;
            L.marker([<?= $d['lat'] ?>, <?= $d['lng'] ?>], {icon: customIcon}).addTo(map).bindPopup(content);
        <?php } ?>

        function flyTo(lat, lng){
            map.flyTo([lat, lng], 16, { animate: true, duration: 1.5 });
            document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
        }
    </script>

</body>
</html>