<?php session_start();
// memasukan file db.php
include 'db.php';
include 'sanitasi.php';
include 'piutang.function.php';

// mengirim data no faktur menggunakan metode POST
 $kode_pelanggan = stringdoang($_GET['kode_pelanggan']);


echo rp(hitungSisaPlafon($kode_pelanggan));
//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db); 

?>