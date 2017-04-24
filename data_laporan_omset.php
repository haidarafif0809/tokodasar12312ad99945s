<?php include 'session_login.php';
include 'db.php';
include 'sanitasi.php';

/* Database connection end */

$dari_tanggal = stringdoang($_POST['dari_tanggal']);
$sampai_tanggal = stringdoang($_POST['sampai_tanggal']);
$kode_pelanggan = stringdoang($_POST['kode_pelanggan']);
$sales = stringdoang($_POST['sales']);



if ($kode_pelanggan == 'semua' AND $sales == 'semua') {
	$sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
	$data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}
else if ($kode_pelanggan == 'semua' AND $sales != 'semua') {
	$sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND sales = '$sales'");
	$data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}
else if ($kode_pelanggan != 'semua' AND $sales == 'semua') {
	$sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kode_pelanggan = '$kode_pelanggan'");
	$data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}
else{
	$sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND sales = '$sales' AND kode_pelanggan = '$kode_pelanggan'");
	$data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'no_faktur',
	1 => 'tanggal',
	2 => 'kode_pelanggan',
	3 => 'nama_pelanggan',
	4 => 'total',
	5 => 'tunai',
	6 => 'sisa'

);

if ($kode_pelanggan == 'semua' AND $sales == 'semua') {
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' ";
}
else if ($kode_pelanggan == 'semua' AND $sales != 'semua') {
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.sales = '$sales' ";	
}
else if ($kode_pelanggan != 'semua' AND $sales == 'semua') {
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.kode_pelanggan = '$kode_pelanggan' ";	
}
else{
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.kode_pelanggan = '$kode_pelanggan' AND p.sales = '$sales' ";
}

$query=mysqli_query($conn, $sql) or die("show_data_omset_penjualan1.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if ($kode_pelanggan == 'semua' AND $sales == 'semua') {
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' ";
}
else if ($kode_pelanggan == 'semua' AND $sales != 'semua') {
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.sales = '$sales' ";	
}
else if ($kode_pelanggan != 'semua' AND $sales == 'semua') {
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.kode_pelanggan = '$kode_pelanggan' ";	
}
else{
	// getting total number records without any search
	$sql = "SELECT p.id, p.no_faktur, p.tanggal, p.jam, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa ";
	$sql.="FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.kode_pelanggan = '$kode_pelanggan' AND p.sales = '$sales' ";
}


if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( p.tanggal LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR p.no_faktur LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR pel.nama_pelanggan LIKE '".$requestData['search']['value']."%' )"; 
}

$query=mysqli_query($conn, $sql) or die("show_data_omset_penjualan2.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

$sql.=" ORDER BY id DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("show_data_omset_penjualan3.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
		//menampilkan data
		$sum_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE no_faktur = '$row[no_faktur]' AND kode_pelanggan = '$row[kode_pelanggan]'");
		$data_sum = mysqli_fetch_array($sum_penjualan);

		$nestedData[] = $row['tanggal'];
		$nestedData[] = $row['no_faktur'];
		$nestedData[] = $row['nama_pelanggan'];
		$nestedData[] = $row['sales'];
		$nestedData[] = "<p align='right'>". rp($data_sum['total_penjualan']) ."</p>";
		$nestedData[] = "<p align='right'>". rp($data_sum['total_kas']) ."</p>";
	$data[] = $nestedData;
}

	$nestedData=array(); 

		$nestedData[] = "<p style='color:red'>TOTAL </p>";
		$nestedData[] = "<p style='color:red'></p>";
		$nestedData[] = "<p style='color:red'></p>";
		$nestedData[] = "<p style='color:red'></p>";
		$nestedData[] = "<p style='color:red' align='right'>". rp($data_sum_omset['total_penjualan']) ."</p>";
		$nestedData[] = "<p style='color:red' align='right'>". rp($data_sum_omset['total_kas']) ."</p>";
	$data[] = $nestedData;

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
