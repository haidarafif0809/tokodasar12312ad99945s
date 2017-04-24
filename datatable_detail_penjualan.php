<?php include 'session_login.php';
/* Database connection start */
include 'sanitasi.php';
include 'db.php';

/* Database connection end */

$no_faktur = stringdoang($_POST['no_faktur']);
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;



$columns = array( 
// datatable column index  => database column name

    0=>'id', 
    1=>'no_faktur',
    2=>'kode_barang',
    3=>'nama_barang',
    4=>'jumlah_barang', 
    5=>'jumlah_barang',
    6=>'satuan',
    7=>'harga',
    8=>'potongan', 
    9=>'subtotal',
    10=>'tax',
    11=>'sisa',
    12=>'nama',
    13=>'nama'

);

// getting total number records without any search
$sql =" SELECT kode_barang, nama_barang ";
$sql.=" FROM detail_penjualan WHERE no_faktur = '$no_faktur'";

$query = mysqli_query($conn, $sql) or die("eror 1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
$sql =" SELECT kode_barang, nama_barang";
$sql.=" FROM detail_penjualan WHERE no_faktur = '$no_faktur'";

    $sql.=" AND (kode_barang LIKE '".$requestData['search']['value']."%'";  
    $sql.=" OR nama_barang LIKE '".$requestData['search']['value']."%' )";

}


$query=mysqli_query($conn, $sql) or die("eror 2");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        
$sql.=" ORDER BY kode_barang ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */    
$query=mysqli_query($conn, $sql) or die("eror 3");


$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
  $nestedData=array();

$inner_join_detail = $db->query("SELECT dp.id, dp.no_faktur, dp.kode_barang, dp.nama_barang, dp.jumlah_barang / sk.konversi AS jumlah_produk, dp.jumlah_barang, dp.satuan, dp.harga, dp.potongan, dp.subtotal, dp.tax, dp.sisa, sk.id_satuan, s.nama, sa.nama AS satuan_asal, SUM(hk.sisa_barang) AS sisa_barang 
  FROM detail_penjualan dp LEFT JOIN satuan_konversi sk ON dp.kode_barang = sk.kode_produk  AND dp.satuan = sk.id_satuan
  LEFT JOIN satuan s ON dp.satuan = s.id LEFT JOIN satuan sa ON dp.asal_satuan = sa.id 
  LEFT JOIN hpp_keluar hk ON dp.no_faktur = hk.no_faktur AND dp.kode_barang = hk.kode_barang 
  LEFT JOIN penjualan p ON dp.no_faktur = p.no_faktur WHERE dp.no_faktur = '$no_faktur' AND dp.kode_barang = '$row[kode_barang]' ");

$data_inner = mysqli_fetch_array($inner_join_detail);


          $nestedData[] = $data_inner['no_faktur'];
          $nestedData[] = $data_inner['kode_barang'];
          $nestedData[] = $data_inner['nama_barang'];

          if ($data_inner['jumlah_produk'] < 1) {
            $nestedData[] = $data_inner['jumlah_barang'];
          }
          else{
            $nestedData[] = $data_inner['jumlah_produk'];
          }

          $nestedData[] = $data_inner['nama'];
          $nestedData[] = rp($data_inner['harga']);
          $nestedData[] = rp($data_inner['subtotal']);
          $nestedData[] = rp($data_inner['potongan']);
          $nestedData[] = rp($data_inner['tax']);

        if ($_SESSION['otoritas'] == 'Pimpinan'){

                $nestedData[] = rp($data_inner['hpp']);
        }

          $nestedData[] = $data_inner['sisa_barang'] ." ".$data_inner['satuan_asal'];

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