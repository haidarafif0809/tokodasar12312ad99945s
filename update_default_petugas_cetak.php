<?php
	// memasukan file db.php
    include 'sanitasi.php';
    include 'db.php';
    include 'cache_folder/cache.class.php';

// Mengirim data dengan menggunakan metode POST
    $id = angkadoang($_POST['id']);
    $input_nama = stringdoang($_POST['input_nama']);

       $query_update =$db->prepare("UPDATE setting_footer_cetak SET petugas = ?  WHERE id = ?");

       $query_update->bind_param("si",
        $input_nama, $id);

//Eksekusi Query Update
       $query_update->execute();

if (!$query_update) 
{
 die('Query Error : '.$db->errno.
 ' - '.$db->error);
}
else 
{

}
  //Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);

?>