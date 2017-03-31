<?php include 'session_login.php';
/* Database connection start */
include 'db.php';
/* Database connection end */
include 'sanitasi.php';

$dari_tanggal = stringdoang($_POST['dari_tanggal']);
$sampai_tanggal = stringdoang($_POST['sampai_tanggal']);


$sum_penjualan = $db->query("SELECT SUM(total) AS t_total, SUM(nilai_kredit) AS t_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
$data_sum = mysqli_fetch_array($sum_penjualan);
$total_total = $data_sum['t_total'];
$total_kredit = $data_sum['t_kredit'];

$total_bayar = $total_total - $total_kredit;

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'tanggal',
	1=> 'id'
);

// getting total number records without any search
$sql =" SELECT tanggal ";
$sql.=" FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' GROUP BY tanggal ";


$query=mysqli_query($conn, $sql) or die("datatable_lap_penjualan.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.




if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter


$sql =" SELECT tanggal ";
$sql.=" FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND tanggal LIKE '".$requestData['search']['value']."%' GROUP BY tanggal ";

}

$query=mysqli_query($conn, $sql) or die("datatable_lap_penjualan.phpppp: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 


$sql.= " ORDER BY tanggal DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

						$perintah1 = $db->query("SELECT no_faktur FROM penjualan WHERE tanggal = '$row[tanggal]'");
						$data1 = mysqli_num_rows($perintah1);
						
						$perintah2 = $db->query("SELECT SUM(total) AS t_total, SUM(nilai_kredit) AS t_kredit FROM penjualan WHERE tanggal = '$row[tanggal]'");
						$data2 = mysqli_fetch_array($perintah2);
						$t_total = $data2['t_total'];
						$t_kredit = $data2['t_kredit'];
						
						$t_bayar = $t_total - $t_kredit;

				//menampilkan data
				$nestedData[] = $row['tanggal'];
				$nestedData[] = $data1;
				$nestedData[] = rp($t_total);
				$nestedData[] = rp($t_bayar);
				$nestedData[] = rp($t_kredit);

				$data[] = $nestedData;
			}

$nestedData=array();

				$nestedData[] = "<p style='color:red'> TOTAL </p>";
				$nestedData[] = "<p style='color:red'></p>";
				$nestedData[] = "<p style='color:red'>".rp($total_total)."</p>";
				$nestedData[] = "<p style='color:red'>".rp($total_bayar)."</p>";
				$nestedData[] = "<p style='color:red'>".rp($total_kredit)."</p>";

$data[] = $nestedData;

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

