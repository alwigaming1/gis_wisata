<?php
include 'config.php';
cek_login();

$id = "";
$nama = ""; $kategori = ""; $lat = ""; $lng = ""; $alamat = ""; $tiket = ""; $gambar_lama = "";
$tombol = "Simpan Lokasi";

// JIKA MODUS EDIT
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = mysqli_query($koneksi, "SELECT * FROM wisata WHERE id='$id'");
    $d = mysqli_fetch_array($sql);
    
    $nama = $d['nama_wisata'];
    $kategori = $d['kategori'];
    $lat = $d['lat'];
    $lng = $d['lng'];
    $alamat = $d['alamat'];
    $tiket = $d['harga_tiket'];
    $gambar_lama = $d['gambar'];
    
    $tombol = "Update Lokasi";
}

// PROSES SIMPAN
if(isset($_POST['simpan'])){
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $alamat = $_POST['alamat'];
    $tiket = $_POST['tiket'];

    // --- PERBAIKAN LOGIC UPLOAD GAMBAR ---
    $nama_gambar = $_FILES['foto']['name'];
    $tmp_gambar = $_FILES['foto']['tmp_name'];
    $error_gambar = $_FILES['foto']['error'];
    
    if($nama_gambar != "" && $error_gambar == 0){
        // 1. Cek Folder, kalau belum ada kita buatkan
        $folder_tujuan = "assets/images/";
        if (!file_exists($folder_tujuan)) {
            mkdir($folder_tujuan, 0777, true);
        }

        // 2. Buat nama file unik
        $ext = pathinfo($nama_gambar, PATHINFO_EXTENSION);
        $file_baru = "wisata_" . time() . "." . $ext;
        
        // 3. Upload
        if(move_uploaded_file($tmp_gambar, $folder_tujuan . $file_baru)){
            $gambar_final = $folder_tujuan . $file_baru;
        } else {
            echo "<script>alert('Gagal upload gambar! Periksa permission folder.');</script>";
            $gambar_final = $_POST['gambar_lama']; // Fallback
        }
    } else {
        // Pakai Gambar Lama jika tidak upload baru
        $gambar_final = $_POST['gambar_lama'];
    }

    if($id != ""){
        $q = "UPDATE wisata SET nama_wisata='$nama', kategori='$kategori', lat='$lat', lng='$lng', alamat='$alamat', harga_tiket='$tiket', gambar='$gambar_final' WHERE id='$id'";
    } else {
        $q = "INSERT INTO wisata VALUES (NULL, '$nama', '$kategori', '$lat', '$lng', '$alamat', '$tiket', '$gambar_final')";
    }

    if(mysqli_query($koneksi, $q)){
        echo "<script>alert('Berhasil disimpan!'); window.location='data_wisata.php';</script>";
    } else {
        echo "Error Database: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Form Wisata</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-slate-900 px-8 py-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white"><?= $tombol ?></h2>
                <a href="data_wisata.php" class="text-gray-400 hover:text-white transition"><i class="fa-solid fa-times text-xl"></i></a>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Wisata</label>
                            <input type="text" name="nama" value="<?= $nama ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                            <select name="kategori" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                <option <?= $kategori == 'Alam' ? 'selected' : '' ?>>Alam</option>
                                <option <?= $kategori == 'Sejarah' ? 'selected' : '' ?>>Sejarah</option>
                                <option <?= $kategori == 'Kuliner' ? 'selected' : '' ?>>Kuliner</option>
                                <option <?= $kategori == 'Religi' ? 'selected' : '' ?>>Religi</option>
                                <option <?= $kategori == 'Hiburan' ? 'selected' : '' ?>>Hiburan</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Latitude</label>
                                <input type="text" name="lat" value="<?= $lat ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500" placeholder="-6.xxxx" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Longitude</label>
                                <input type="text" name="lng" value="<?= $lng ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500" placeholder="106.xxxx" required>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga Tiket</label>
                            <input type="text" name="tiket" value="<?= $tiket ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"><?= $alamat ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Foto Lokasi</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-gray-50">
                                <?php if($gambar_lama && file_exists($gambar_lama)): ?>
                                    <img src="<?= $gambar_lama ?>" class="h-32 w-full object-cover rounded mb-3 mx-auto shadow-sm">
                                    <input type="hidden" name="gambar_lama" value="<?= $gambar_lama ?>">
                                    <p class="text-xs text-green-600">Gambar Terpasang</p>
                                <?php else: ?>
                                    <div class="h-32 w-full bg-gray-200 rounded mb-3 flex items-center justify-center text-gray-400 text-xs">Belum ada gambar</div>
                                    <input type="hidden" name="gambar_lama" value="">
                                <?php endif; ?>
                                <input type="file" name="foto" class="text-sm w-full mt-2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="data_wisata.php" class="px-6 py-3 rounded-lg font-bold text-gray-500 hover:bg-gray-100 transition">Batal</a>
                    <button type="submit" name="simpan" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>