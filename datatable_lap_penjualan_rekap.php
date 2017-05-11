<?php include 'session_login.php';
/* Database connection start */
include 'db.php';
/* Database connection end */
include 'sanitasi.php';

$dari_tanggal = stringdoang($_POST['dari_tanggal']);
$sampai_tanggal = stringdoang($_POST['sampai_tanggal']);
$kategori = stringdoang($_POST['kategori']);

$total_akhir_kotor = 0;
$total_potongan = 0;
$total_tax = 0;
$total_jual = 0;
$total_tunai = 0;
$total_sisa  = 0;
$total_kredit = 0;

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'nama_pelanggan', 
	1 => 'tanggal',
	2 => 'no_faktur',
	3=> 'kode_pelanggan',
	4=> 'total',
	5=> 'jam',
	6=> 'user',
	7=> 'status',
	8=> 'potongan',
	9=> 'tax',
	10=> 'sisa',
	11=> 'kredit',
	12=> 'id'
);


// getting total number records without any search

if ($kategori == "Semua Kategori") {
	# JIKA SEMUA KATEGORI
	$sql = " SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit ";
	$sql.="FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang ";
	$sql.=" WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal'";
}
else{
	$sql = " SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit ";
	$sql.="FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang ";
	$sql.=" WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND b.kategori = '$kategori'";
}



$query=mysqli_query($conn, $sql) or die("eror 1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if ($kategori == "Semua Kategori") {
	# JIKA SEMUA KATEGORI
	
	$sql = " SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit ";
	$sql.="FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang ";
	$sql.=" WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND 1=1";
}
else
{

	$sql = " SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit ";
	$sql.="FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang ";
	$sql.=" WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND b.kategori = '$kategori' AND 1=1";
}



if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter

	$sql.=" AND ( pel.nama_pelanggan LIKE '".$requestData['search']['value']."%' "; 
	$sql.=" OR p.tanggal LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR p.no_faktur LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR pel.kode_pelanggan LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR p.jam LIKE '".$requestData['search']['value']."%' ) ";

}
$query=mysqli_query($conn, $sql) or die("eror 2");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 


$sql.= " GROUP BY p.no_faktur ORDER BY p.no_faktur DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("eror 3");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$total_kotor = $row['total'] + $row['potongan'];

	$total_akhir_kotor = $total_akhir_kotor + $total_kotor;

	$total_potongan = $total_potongan + $row['potongan'];

	$total_tax = $total_tax + $row['tax'];

	$total_jual = $total_jual + $row['total'];

	$total_tunai = $total_tunai + $row['tunai'];

	$total_sisa = $total_sisa + $row['sisa'];

	$total_kredit = $total_kredit + $row['kredit'];



				//menampilkan data
				$nestedData[] = $row['no_faktur'];
				$nestedData[] = $row['tanggal'];
				$nestedData[] = $row['jam'];
				$nestedData[] = $row['kategori'];
				$nestedData[] = $row['code_card'] ." - ". $row['nama_pelanggan'];
				$nestedData[] = $row['user'];
				$nestedData[] = $row['status'];
				$nestedData[] = "<p align='right'>".rp($total_kotor)."</p>";
				$nestedData[] = "<p align='right'>".rp($row['potongan'])."</p>";
				$nestedData[] = "<p align='right'>".rp($row['tax'])."</p>";
				$nestedData[] = "<p align='right'>".rp($row['total'])."</p>";
				$nestedData[] = "<p align='right'>".rp($row['tunai'])."</p>";
				$nestedData[] = "<p align='right'>".rp($row['sisa'])."</p>";
				$nestedData[] = "<p align='right'>".rp($row['kredit'])."</p>";
				$nestedData[] = $row["id"];
				$data[] = $nestedData;
			}

$nestedData=array();      

      $nestedData[] = "<p style='color:red'> TOTAL </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($total_akhir_kotor )." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($total_potongan)." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($total_tax)." </p>"; 
      $nestedData[] = "<p style='color:red' align='right'> ".rp($total_jual)." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($total_tunai)." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($total_sisa)." </p>";
      $nestedData[] = "<p style='color:red' align='right'> ".rp($total_kredit)." </p>"; 

  $data[] = $nestedData;



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

