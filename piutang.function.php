<?php 


function hitungSisaPiutang($no_faktur){
	include 'db.php';

	$query_penjualan = $db->query("SELECT nilai_kredit FROM penjualan WHERE no_faktur = '$no_faktur'");
	$data_penjualan = mysqli_fetch_array($query_penjualan);

	$nilai_kredit_awal = $data_penjualan['nilai_kredit'];

	$query_pembayaran_piutang = $db->query("SELECT SUM(jumlah_bayar) AS jumlah_bayar  FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$no_faktur' ");

	$data_pembayaran_piutang = mysqli_fetch_array($query_pembayaran_piutang);

	$sisa_piutang = $nilai_kredit_awal - $data_pembayaran_piutang;

	return $sisa_piutang;


}


function hitungSisaPlafon($kode_pelanggan){

		include 'db.php';

		//cek jumlah flafon di pelanggan
		$select = $db->query("SELECT flafon FROM pelanggan WHERE kode_pelanggan = '$kode_pelanggan'");
		$out = mysqli_fetch_array($select);
		$flafon = $out['flafon'];

		//cek jumlah total piutang yang sudah di lakukan
		$query = $db->query("SELECT SUM(nilai_kredit) AS jumlah_piutang FROM penjualan WHERE kode_pelanggan = '$kode_pelanggan' AND status = 'Piutang'");
		$data = mysqli_fetch_array($query);
		$total_piutang = $data['jumlah_piutang'];

		$query_pembayaran_piutang = $db->query("SELECT SUM(dp.jumlah_bayar) + SUM(dp.potongan) AS pembayaran  FROM detail_pembayaran_piutang dp INNER JOIN penjualan p ON dp.no_faktur_penjualan = p.no_faktur WHERE p.kode_pelanggan = '$kode_pelanggan' ");

		$data_pembayaran_piutang = mysqli_fetch_array($query_pembayaran_piutang);

		$sisa_plafon = $flafon - $total_piutang + $data_pembayaran_piutang['pembayaran'];


		if ($sisa_plafon < 0 ) {
			# code...
			$sisa_plafon = 0;
		}

		return $sisa_plafon;

}

 ?>