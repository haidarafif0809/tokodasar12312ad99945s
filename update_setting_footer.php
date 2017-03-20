<?php
	// memasukan file db.php
    include 'sanitasi.php';
    include 'db.php';

    // mengrim data dengan menggunakan metode POST
    $id = angkadoang($_POST['id']);
    $jenis = stringdoang($_POST['jenis']);

if ($jenis == 'transfer') {  
    $input_transfer = stringdoang($_POST['input_transfer']);

       $query =$db->prepare("UPDATE setting_footer_cetak SET via_transfer = ?  WHERE id = ?");

       $query->bind_param("si",
        $input_transfer, $id);

        $query->execute();

}

if ($jenis == 'bilyet') {
    $input_bilyet = stringdoang($_POST['input_bilyet']);

       $query =$db->prepare("UPDATE setting_footer_cetak SET via_bilyet = ?  WHERE id = ?");

       $query->bind_param("si",
        $input_bilyet, $id);

        $query->execute();
}

if (!$query) 
{
 die('Query Error : '.$db->errno.
 ' - '.$db->error);
}
else 
{

}


?>