<?php include_once 'session_login.php';
include 'db.php';
include 'sanitasi.php';

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'kode_grup_akun', 
	1 => 'nama_grup_akun',
	2 => 'parent',
	3 => 'kategori_akun',
	4 => 'tipe_akun',
	5 => 'user_buat',
	6 => 'user_edit',
	7 => 'waktu',
	8 => 'detail',
	9 => 'hapus',
	10=> 'id'
);

// getting total number records without any search
$sql = "SELECT * ";
$sql.=" FROM grup_akun ORDER BY id DESC";
$query=mysqli_query($conn, $sql) or die("1.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT * ";
$sql.=" FROM grup_akun ";
$sql.=" WHERE 1=1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( kode_grup_akun LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR nama_grup_akun LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR kategori_akun LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($conn, $sql) or die("2.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

 $sql.=" ORDER BY id ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("3.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row['kode_grup_akun'];
 
	$nestedData[]=  "
	<td class='edit-nama' data-id='".$row['id']."'>
	<span id='text-nama-". $row['id'] ."'>". $row['nama_grup_akun'] ."</span>
	<input type='hidden' id='input-nama-".$row['id']."' value='".$row['nama_grup_akun']."' class='input_nama' row-id='".$row['id']."' autofocus=''>
	</td>";



$nestedData[]=  $row["parent"];
$nestedData[]=  $row["kategori_akun"];
$nestedData[]=  $row["tipe_akun"];
$nestedData[] = $row["user_buat"];
$nestedData[] = $row["user_edit"];
$nestedData[] = $row["waktu"];

	$nestedData[]= "<button class='btn btn-info detail' kode_grup_akun='". $row['kode_grup_akun'] ."' ><span class='glyphicon glyphicon-th-list'></span> Detail </button> ";

	$nestedData[]= "<button class='btn btn-danger btn-hapus' data-id='". $row['id'] ."' data-satuan='". $row['nama_grup_akun'] ."'> <span class='glyphicon glyphicon-trash'> </span> Hapus </button>";
		
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
