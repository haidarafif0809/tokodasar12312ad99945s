<?php 
//memasukkan file db.php
include 'db.php';
include 'sanitasi.php';

	//mengirim data sesuai dengan variabel dengan metode POST

// menambah data yang ada pada tabel satuan berdasarka id dan nama
$perintah = $db->prepare("UPDATE setting_footer_cetak SET keterangan = ?");

$perintah->bind_param("s",$keterangan_footer);


	$keterangan_footer = $_POST['keterangan_footer'];
$perintah->execute();

if (!$perintah) 
{
 die('Query Error : '.$db->errno.
 ' - '.$db->error);
}
else 
{
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=footer_cetak_penjualan_besar.php">';

}



 ?>