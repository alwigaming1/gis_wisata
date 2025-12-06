<?php
$conf = [
    "app_name"  => "WebGIS Pariwisata", 
    "app_ver"   => "V.1.0",
    "author"    => "SkripsiCode",
    "color"     => "sky", // TEMA BIRU LANGIT
    "db_host"   => "localhost",
    "db_user"   => "root",
    "db_pass"   => "",
    "db_name"   => "db_gis"
];
$koneksi = mysqli_connect($conf['db_host'], $conf['db_user'], $conf['db_pass'], $conf['db_name']);
if (!$koneksi) { die("Koneksi Gagal: " . mysqli_connect_error()); }
session_start();
function cek_login(){ if(empty($_SESSION['status'])){ header("location:login.php"); exit; } }
?>