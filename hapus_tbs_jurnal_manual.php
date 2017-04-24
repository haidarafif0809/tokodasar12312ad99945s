<?php session_start();

// memasukan file db.php
include 'db.php';
include 'sanitasi.php';


$id = stringdoang($_POST['id']);
$user = $_SESSION['user_name'];

$hapus = $db->query("DELETE FROM tbs_jurnal WHERE id = '$id'");


if ($insert_jurnal_trans == TRUE)
{

	echo "sukses";

}

else
{
	
	echo "gagal";
	}	
//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
?>
