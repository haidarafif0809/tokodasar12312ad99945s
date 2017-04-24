<?php include 'session_login.php';
/* Database connection start */
include 'db.php';
/* Database connection end */
include 'sanitasi.php';

$kategori = stringdoang($_POST['kategori']);
$dari_tanggal = stringdoang($_POST['dari_tanggal']);
$sampai_tanggal = stringdoang($_POST['sampai_tanggal']);


if ($kategori == 'semua')
{
  $query02 = $db->query("SELECT SUM(dp.jumlah_barang) as tot_jumlah ,SUM(dp.harga * dp.jumlah_barang) as tot_subtotal ,SUM(dp.potongan) as tot_potongan ,SUM(dp.tax) as tot_tax, SUM(dp.subtotal + dp.tax) as tot_akhir FROM detail_penjualan dp WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' ");

}
else
{

	  $query02 = $db->query("SELECT SUM(dp.jumlah_barang) as tot_jumlah ,SUM(dp.harga * dp.jumlah_barang) as tot_subtotal ,SUM(dp.potongan) as tot_potongan ,SUM(dp.tax) as tot_tax, SUM(dp.subtotal + dp.tax) as tot_akhir FROM detail_penjualan dp  LEFT JOIN barang br ON dp.kode_barang = br.kode_barang WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal'  AND br.kategori = '$kategori'");
}

$cek02 = mysqli_fetch_array($query02);
$tot_jumlah = $cek02['tot_jumlah'];
$tot_subtotal = $cek02['tot_subtotal'];
$tot_potongan = $cek02['tot_potongan'];
$tot_tax = $cek02['tot_tax'];
$tot_akhir = $cek02['tot_akhir'];





// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'nama', 
	1 => 'no_faktur',
	2 => 'kode_barang',
	3=> 'nama_barang',
	4=> 'jumlah_barang',
	5=> 'satuan',
	6=> 'harga',
	7=> 'subtotal',
	8=> 'potongan',
	9=> 'tax',
	12=> 'total',
	13=> 'id'
);

if ($kategori == 'semua')
{
	// getting total number records without any search
	$sql ="SELECT s.nama,dp.id,dp.no_faktur,dp.kode_barang,dp.nama_barang,dp.jumlah_barang,dp.satuan,dp.harga,dp.subtotal,dp.potongan,dp.tax,dp.hpp,dp.sisa ";
	$sql.="FROM detail_penjualan dp LEFT JOIN satuan s ON dp.satuan = s.id WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' ";
	
}
else
{

	$sql="SELECT s.nama,dp.id,dp.no_faktur,dp.kode_barang,dp.nama_barang,dp.jumlah_barang,dp.satuan,dp.harga,dp.subtotal,dp.potongan,dp.tax,dp.hpp,dp.sisa ";
	$sql.=" FROM detail_penjualan dp LEFT JOIN satuan s ON dp.satuan = s.id LEFT JOIN barang br ON dp.kode_barang = br.kode_barang WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal'  AND br.kategori = '$kategori'";

}

$query=mysqli_query($conn, $sql) or die("datatable_lap_penjualan.php: get employees");
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if ($kategori == 'semua')
{
	// getting total number records without any search
	$sql ="SELECT s.nama,dp.id,dp.no_faktur,dp.kode_barang,dp.nama_barang,dp.jumlah_barang,dp.satuan,dp.harga,dp.subtotal,dp.potongan,dp.tax,dp.hpp,dp.sisa ";
	$sql.="FROM detail_penjualan dp LEFT JOIN satuan s ON dp.satuan = s.id WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND 1=1 ";
	
}
else
{

	$sql="SELECT s.nama,dp.id,dp.no_faktur,dp.kode_barang,dp.nama_barang,dp.jumlah_barang,dp.satuan,dp.harga,dp.subtotal,dp.potongan,dp.tax,dp.hpp,dp.sisa ";
	$sql.=" FROM detail_penjualan dp LEFT JOIN satuan s ON dp.satuan = s.id LEFT JOIN barang br ON dp.kode_barang = br.kode_barang WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal'  AND br.kategori = '$kategori' AND 1=1";

}

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
$sql.=" AND ( dp.no_faktur LIKE '".$requestData['search']['value']."%' ";
$sql.=" OR dp.nama_barang LIKE '".$requestData['search']['value']."%' ";
$sql.=" OR dp.kode_barang LIKE '".$requestData['search']['value']."%' )";
	
}


$query=mysqli_query($conn, $sql) or die("datatable_lap_penjualan.phpppp: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.= " ORDER BY dp.no_faktur DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 


		$pilih_konversi = $db->query("SELECT $row[jumlah_barang] / sk.konversi AS jumlah_konversi, sk.harga_pokok / sk.konversi AS harga_konversi, sk.id_satuan, b.satuan,sk.konversi FROM satuan_konversi sk INNER JOIN barang b ON sk.id_produk = b.id  WHERE sk.id_satuan = '$row[satuan]' AND sk.kode_produk = '$row[kode_barang]'");
					      $data_konversi = mysqli_fetch_array($pilih_konversi);

     		 	$query900 = $db->query("SELECT nama FROM satuan WHERE id = '$data_konversi[satuan]'");
     			 $cek011 = mysqli_fetch_array($query900);


					      if ($data_konversi['harga_konversi'] != 0 || $data_konversi['harga_konversi'] != "") 
					      {			        
					         $jumlah_barang = $data_konversi['jumlah_konversi'];
					         $konver = $jumlah_barang * $data_konversi['konversi'];
					      }
					      else{
					        $jumlah_barang = $row['jumlah_barang'];
					        $konver = "";
					      }


					$subtotal = $row['harga'] * $row['jumlah_barang'];

					//menampilkan data
					$nestedData[] = $row['no_faktur'];
					$nestedData[] = $row['kode_barang'];
					$nestedData[] = $row['nama_barang'];				
					$nestedData[] = "<p align='right'>".$jumlah_barang." </p>";						
					if ($data_konversi['harga_konversi'] != 0 || $data_konversi['harga_konversi'] != "") 
					     {	
								$nestedData[] = $row['nama']." ( ".$konver." ".$cek011['nama']." )";
						}
					else
						{
								$nestedData[] = $row['nama'];
						}

					$nestedData[] = "<p align='right'>".rp($row['harga'])." </p>";
					$nestedData[] = "<p align='right'>".rp($subtotal)." </p>";
					$nestedData[] = "<p align='right'>".rp($row['potongan'])."</p>";
					$nestedData[] = "<p align='right'>".rp($row['tax'])."</p>";
					$nestedData[] = "<p align='right'>".rp($row['subtotal'] + $row['tax'])."</p>";
					$nestedData[] = "<p align='right'>".$row["id"]."</p>";
				$data[] = $nestedData;
			}

$nestedData=array();      

      $nestedData[] = "<p style='color:red'> TOTAL </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($tot_jumlah)." </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($tot_subtotal)." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($tot_potongan)." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($tot_tax)." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($tot_akhir)." </p>"; 
  $data[] = $nestedData;

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

