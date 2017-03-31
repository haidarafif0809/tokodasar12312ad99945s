<?php
include 'sanitasi.php';
include 'db.php';


$id = stringdoang($_POST['id']);
$flafon = stringdoang($_POST['flafon']);
$flafon_usia = stringdoang($_POST['flafon_usia']);
$kode_pelanggan = stringdoang($_POST['kode_pelanggan']);
$nama_pelanggan = stringdoang($_POST['nama_pelanggan']);
$level_harga = stringdoang($_POST['level_harga']);
$tgl_lahir = stringdoang($_POST['tgl_lahir']);
$no_telp = stringdoang($_POST['no_telp']);
$e_mail = stringdoang($_POST['e_mail']);
$wilayah = stringdoang($_POST['wilayah']);



$query = $db->prepare("UPDATE pelanggan SET kode_pelanggan = ?, nama_pelanggan = ?, no_telp = ?, e_mail = ?, tgl_lahir = ?, wilayah = ? , level_harga = ?, flafon = ? ,flafon_usia = ? WHERE id = ?");

$query->bind_param("sssssssiis",
	$kode_pelanggan, $nama_pelanggan, $no_telp, $e_mail, $tgl_lahir, $wilayah, $level_harga, $flafon,$flafon_usia, $id);

$query->execute();

    if (!$query) 
    {
    die('Query Error : '.$db->errno.
    ' - '.$db->error);
    }
    else 
    {
    echo "sukses";
    }

    //Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
?>