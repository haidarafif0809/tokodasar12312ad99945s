<?php session_start();
// memasukan file db.php
include 'db.php';
include 'sanitasi.php';
include 'piutang.function.php';

// mengirim data no faktur menggunakan metode POST
 $total_akhir = angkadoang($_GET['kredit']);
 $kode_pelanggan = angkadoang($_GET['kode_pelanggan']);


$query_plafon  = $db->query("SELECT flafon,flafon_usia FROM pelanggan WHERE kode_pelanggan = '$kode_pelanggan'");
$data_plafon = mysqli_fetch_array($query_plafon);
$plafon = $data_plafon['flafon'];
$plafon_usia = $data_plafon['flafon_usia'];

  

 $status_boleh_jual = 0;



 	// untuk menentukan apakah pelanggan ini memakai sistem plafon nominal atau tidak
	if ($plafon == '' OR $plafon == 0) {
		# code...
		$hitung = 0;
	}
	else {

		$plafon = hitungSisaPlafon($kode_pelanggan);
		$hitung = $plafon - $total_akhir;
	}

	//untuk menentukan apakah pelanggan ini memakai sistem plafon usia atau tidak
	if ($plafon_usia == '' OR $plafon_usia == 0) {
		# code...
		$jumlah_piutang_lewat_usia_plafon = 0;
	}
	else {

		$query_piutang = $db->query("SELECT no_faktur,total,kredit,tanggal,tanggal_jt FROM `penjualan` WHERE status = 'Piutang' AND kode_pelanggan = '$kode_pelanggan' HAVING DATEDIFF(DATE(NOW()),tanggal_jt) > '$plafon_usia'  ");

		 $jumlah_piutang_lewat_usia_plafon = mysqli_num_rows($query_piutang);

	}
 

	// sisa plafon nominal sudah habis dan ada penjualan yang melewati plafon usia
	if($hitung < 0 AND $jumlah_piutang_lewat_usia_plafon > 0)
	{
		$status_boleh_jual = 2;
	}
	// sisa plafon nominal masih mencukupi dan ada penjualan yang melewati plafon usia
	elseif ($hitung >= 0 AND $jumlah_piutang_lewat_usia_plafon > 0) {
	 	# code...

	 	$status_boleh_jual = 2;

	 } 
	 // sisa plafon nominal sudah habis dan tidak ada penjualan yang melewati plafon usia
	 elseif ($hitung < 0 AND $jumlah_piutang_lewat_usia_plafon == 0) {
	 	# code...
	 	$status_boleh_jual = 1;
	 }
	else
	{
		$status_boleh_jual = 0;
	}

	   $arr = array();
	if ($jumlah_piutang_lewat_usia_plafon > 0 ) {
		# code...

			
			while ($data_piutang_lewat_usia_plafon = mysqli_fetch_array($query_piutang)) {
				# code...

					$temp = array(
					"no_faktur" => $data_piutang_lewat_usia_plafon['no_faktur'],
					"total" => $data_piutang_lewat_usia_plafon['total'],
					"kredit" => $data_piutang_lewat_usia_plafon['kredit'],
					"tanggal" => $data_piutang_lewat_usia_plafon['tanggal'],
					"tanggal_jt" => $data_piutang_lewat_usia_plafon['tanggal_jt']
					);

					array_push($arr, $temp);
			
				} //endwhile

				

	}

$data = json_encode($arr);

echo '{ "status": "'.$status_boleh_jual.'" ,"data_penjualan":'.$data.'}';



//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db); 

?>