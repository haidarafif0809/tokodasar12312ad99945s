<?php
include 'sanitasi.php';
include 'db.php';


$id = angkadoang($_POST['id']);
$nama = stringdoang($_POST['nama']);
$alamat = stringdoang($_POST['alamat']);
$no_telp = angkadoang($_POST['no_telp']);



$query = $db->prepare("UPDATE suplier SET  nama = ?, alamat = ?, no_telp = ? WHERE id = ? ");

$query->bind_param("sssi", $nama, $alamat, $no_telp, $id);

$query->execute();


    if (!$query) 
    {
    die('Query Error : '.$db->errno.
    ' - '.$db->error);
    }
    else 
    {
    echo "sukses".$id."" ;
    }

    //Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
?>