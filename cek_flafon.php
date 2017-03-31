<?php session_start();
// memasukan file db.php
include 'db.php';
include 'sanitasi.php';
include 'piutang.function.php';

// mengirim data no faktur menggunakan metode POST
 $total_akhir = angkadoang($_POST['kredit']);
 $kode_pelanggan = angkadoang($_POST['kode_pelanggan']);


$query_plafon  = $db->query("SELECT flafon FROM pelanggan WHERE kode_pelanggan = '$kode_pelanggan'");
$data_plafon = mysqli_fetch_array($select);
$plafon = $data_plafon['flafon'];

if ($plafon == 0 OR $plafon == '') {
	# code...
	echo 0 ;

}

else {


 $plafon = hitungSisaPlafon($kode_pelanggan);

 $hitung = $plafon - $total_akhir;

	if($hitung < 0)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}



//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db); 

?>