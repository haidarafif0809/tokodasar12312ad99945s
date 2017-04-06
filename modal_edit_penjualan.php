<?php include 'session_login.php';
/* Database connection start */
include 'sanitasi.php';
include 'db.php';
include 'persediaan.function.php';

/* Database connection end */

$no_faktur = stringdoang($_POST['no_faktur']);


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;



$columns = array( 
// datatable column index  => database column name

    0=>'kode_barang', 
    1=>'nama_barang',
    2=>'harga_jual',
    3=>'harga_jual2',
    4=>'harga_jual3',
    5=>'jumlah_barang',
    6=>'nama', 
    7=>'kategori',
    8=>'status',
    9=>'suplier',
    10=>'limit_stok', 
    11=>'berkaitan_dgn_stok',
    12=>'satuan',
    13=>'id',


);

// getting total number records without any search
$sql = "SELECT b.status,s.nama AS nama_satuan ,b.id ,b.limit_stok ,b.kategori ,b.berkaitan_dgn_stok ,b.kode_barang ,b.nama_barang ,b.harga_beli ,b.harga_jual ,b.harga_jual2 ,b.harga_jual3 ,b.satuan ,b.suplier ";
$sql.=" FROM barang b INNER JOIN satuan s ON b.satuan = s.id ";
$sql.=" ";

$query = mysqli_query($conn, $sql) or die("eror 1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
$sql = "SELECT b.status,s.nama AS nama_satuan ,b.id ,b.limit_stok ,b.kategori ,b.berkaitan_dgn_stok ,b.kode_barang ,b.nama_barang ,b.harga_beli ,b.harga_jual ,b.harga_jual2 ,b.harga_jual3 ,b.satuan ,b.suplier ";
$sql.=" FROM barang b INNER JOIN satuan s ON b.satuan = s.id ";
$sql.=" WHERE 1=1 ";



    $sql.=" AND ( b.kode_barang LIKE '".$requestData['search']['value']."%'";  
    $sql.=" OR b.nama_barang LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR b.berkaitan_dgn_stok LIKE '".$requestData['search']['value']."%'";   
    $sql.=" OR b.satuan LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR b.suplier LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR b.limit_stok LIKE '".$requestData['search']['value']."%' ) ";   

}

$query=mysqli_query($conn, $sql) or die("eror 2");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        
$sql.=" ORDER BY b.kode_barang ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */    
$query=mysqli_query($conn, $sql) or die("eror 3");


$data = array();


while( $row=mysqli_fetch_array($query) ) {

//pencarian stok dari persediaan function dan dikurangi stok yang di tbs sekarang
            // select detail penjualan dan tbs_penjualan
            $query_tbs_penjualan = $db->query("SELECT dp.jumlah_barang AS jumlah_detail ,tp.jumlah_barang AS jumlah_tbs, dp.satuan  FROM detail_penjualan dp LEFT JOIN tbs_penjualan tp ON dp.no_faktur = tp.no_faktur WHERE dp.kode_barang = '$row[kode_barang]' AND dp.no_faktur = '$no_faktur' ");
            $data_tbs_penjualan = mysqli_fetch_array($query_tbs_penjualan);
            // select detail penjualan dan tbs_penjualan

                //data konversi
                 $query_satuan_konversi = $db->query("SELECT konversi FROM satuan_konversi WHERE id_satuan = '$data_tbs_penjualan[satuan]' AND kode_produk = '$row[kode_barang]'");
                $data_satuan_konversi = mysqli_fetch_array($query_satuan_konversi);
                //data konversi

                $jumlah_tbs = $data_tbs_penjualan['jumlah_tbs'] * $data_satuan_konversi['konversi'];

                $stok_barang = cekStokHpp($row["kode_barang"]);
                $sisa_barang = ($stok_barang + $data_tbs_penjualan['jumlah_detail']) - $jumlah_tbs;

   

            $harga1 = $row['harga_jual'];
            if ($harga1 == '') {
                $harga1 =0;
            }
            $harga2 = $row['harga_jual2'];
            if ($harga2 == '') {
                $harga2 =0;
            }
            $harga3 = $row['harga_jual3'];
            if ($harga3 == '') {
                $harga3 =0;
            }

    $nestedData=array(); 

    $nestedData[] = $row["kode_barang"];
    $nestedData[] = $row["nama_barang"];
    $nestedData[] = $row["harga_jual"];
    $nestedData[] = $row["harga_jual2"];
    $nestedData[] = $row["harga_jual3"];

    if ($row["berkaitan_dgn_stok"] == "Jasa") {
        $nestedData[] = "0";
        }
    else{
        $nestedData[] = $sisa_barang;
        }

    $nestedData[] = $row["nama_satuan"];
    $nestedData[] = $row["kategori"];
    $nestedData[] = $row["suplier"];
    $nestedData[] = $row["limit_stok"];
    $nestedData[] = $row["berkaitan_dgn_stok"];
    $nestedData[] = $row["status"];
    $nestedData[] = $row["satuan"];
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

