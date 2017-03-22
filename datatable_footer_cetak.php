<?php include 'session_login.php';
/* Database connection start */
include 'db.php';
/* Database connection end */
include 'sanitasi.php';
$no_urut = 1;

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'id',
	1 =>'keterangan'


);


// getting total number records without any search
$sql = "SELECT id,keterangan,petugas ";
$sql.="FROM setting_footer_cetak ";
$query=mysqli_query($conn, $sql) or die("datatabsetting: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.



$sql = "SELECT id,keterangan,petugas ";
$sql.="FROM setting_footer_cetak WHERE 1=1";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter


	$sql.=" AND ( keterangan LIKE '".$requestData['search']['value']."%' )";

}
$query=mysqli_query($conn, $sql) or die("datatable_setting: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 


$sql.= " ORDER BY id ASC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
		$nestedData[] = $no_urut ++;
		$nestedData[] = $row['keterangan'];

		$nestedData[] = "<p class='edit-nama' data-id='".$row['id']."'><span id='text-nama-". $row['id'] ."'>". $row['petugas'] ."</span>
			<input type='hidden' id='input-nama-".$row['id']."' value='".$row['petugas']."' class='input_nama' data-id='".$row['id']."' autofocus=''></p>";

		$nestedData[] = $row["id"];
	$data[] = $nestedData;
	}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

