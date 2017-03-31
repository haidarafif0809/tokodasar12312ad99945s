<?php session_start();
// memasukan file db.php
include 'db.php';
include 'sanitasi.php';

// mengirim data no faktur menggunakan metode POST
 $total_akhir = angkadoang($_POST['total']);
 $kode_pelanggan = angkadoang($_POST['kode_pelanggan']);

//cek jumlah flafon di pelanggan
$select = $db->query("SELECT flafon FROM pelanggan WHERE kode_pelanggan = '$kode_pelanggan'");
$out = mysqli_fetch_array($select);
$flafon = $out['flafon'];

//cek jumlah total piutang yang sudah di lakukan
$query = $db->query("SELECT SUM(kredit) AS jumlah_piutang FROM penjualan WHERE kode_pelanggan = '$kode_pelanggan' AND status = 'Piutang'");
$data = mysqli_fetch_array($query);
$total_piutang = $data['jumlah_piutang'];

$hitungan_total_piutang = $total_akhir + $total_piutang; 
$hitung = $flafon - $hitungan_total_piutang;

if($hitung < 0)
{
	echo 1;
}
else
{
	echo 0;
}
//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db); 

?>