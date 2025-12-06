<aside class="fixed left-0 top-0 w-64 h-full bg-white border-r border-slate-200 z-50 hidden md:flex flex-col shadow-sm">
    <div class="h-20 flex items-center px-8 border-b border-slate-100">
        <div class="w-8 h-8 bg-sky-600 rounded-lg flex items-center justify-center text-white mr-3">
            <i class="fa-solid fa-map-location-dot"></i>
        </div>
        <span class="font-bold text-xl text-slate-800">Geo<span class="text-sky-600">Wisata</span></span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Main Menu</p>
        
        <a href="dashboard.php" class="flex items-center px-4 py-3 rounded-xl transition font-medium 
           <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-sky-50 text-sky-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>">
            <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
        </a>
        
        <a href="data_wisata.php" class="flex items-center px-4 py-3 rounded-xl transition font-medium 
           <?= basename($_SERVER['PHP_SELF']) == 'data_wisata.php' || basename($_SERVER['PHP_SELF']) == 'form_wisata.php' ? 'bg-sky-50 text-sky-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>">
            <i class="fa-solid fa-map-pin w-6"></i> Data Wisata
        </a>

        <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mt-6 mb-2">Settings</p>
        
        <a href="profil.php" class="flex items-center px-4 py-3 rounded-xl transition font-medium 
           <?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'bg-sky-50 text-sky-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>">
            <i class="fa-solid fa-user-gear w-6"></i> Akun Admin
        </a>
        
        <a href="index.php" target="_blank" class="flex items-center px-4 py-3 rounded-xl transition font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-900">
            <i class="fa-solid fa-arrow-up-right-from-square w-6"></i> Lihat Website
        </a>
    </nav>

    <div class="p-4 border-t border-slate-100">
        <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" class="flex items-center justify-center w-full px-4 py-3 text-sm font-bold text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition">
            <i class="fa-solid fa-power-off mr-2"></i> Logout
        </a>
    </div>
</aside>

<div class="md:hidden fixed top-0 left-0 w-full h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-40">
    <span class="font-bold text-lg">GeoWisata Admin</span>
    <button class="text-slate-600"><i class="fa-solid fa-bars text-xl"></i></button>
</div>