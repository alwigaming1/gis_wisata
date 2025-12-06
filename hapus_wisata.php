<?php
include 'config.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($koneksi, "DELETE FROM wisata WHERE id='$id'");
}
header("Location: data_wisata.php");
?>