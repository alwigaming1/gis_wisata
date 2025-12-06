<?php
include 'config.php';

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = md5($_POST['password']); // Pastikan di database password admin juga MD5

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    if(mysqli_num_rows($cek) > 0){
        $d = mysqli_fetch_array($cek);
        $_SESSION['status'] = true;
        $_SESSION['admin_nama'] = $d['nama_lengkap'];
        header("Location: dashboard.php");
    } else {
        $msg = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .bg-travel {
            background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-travel h-screen flex items-center justify-center p-6 relative">
    
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

    <div class="relative bg-white/90 backdrop-blur-md p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-sm border border-white/50">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-sky-600 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg shadow-sky-600/30">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Admin GeoWisata</h1>
            <p class="text-slate-500 text-sm">Masuk untuk mengelola peta.</p>
        </div>

        <?php if(isset($msg)): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-xl text-center text-sm mb-6 font-bold border border-red-200">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <div class="bg-sky-50 text-sky-700 px-4 py-3 rounded-xl text-xs font-medium mb-6 flex items-center gap-3 border border-sky-100">
            <i class="fa-solid fa-circle-info text-lg"></i>
            <div>
                <p>Username: <strong>admin</strong></p>
                <p>Password: <strong>admin</strong></p>
            </div>
        </div>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Username</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-3.5 text-slate-400"></i>
                    <input type="text" name="username" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500 transition shadow-sm" placeholder="Masukkan username" required>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-3.5 text-slate-400"></i>
                    <input type="password" name="password" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500 transition shadow-sm" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" name="login" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-sky-600/30 transform hover:-translate-y-1">
                MASUK DASHBOARD
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-sky-600 transition">Kembali ke Peta Utama</a>
        </div>
    </div>

</body>
</html>