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

    0=>'kode_barang', 
    1=>'nama_barang',
    2=>'jumlah_barang',
    3=>'satuan',
    4=>'harga',
    5=>'subtotal',
    6=>'potongan',
    7=>'tax',
    8=>'id'   

);

// getting total number records without any search
$sql =" SELECT bb.berkaitan_dgn_stok,tp.id,tp.no_faktur,tp.kode_barang,tp.satuan,tp.nama_barang,tp.jumlah_barang,tp.harga,tp.subtotal,tp.potongan,tp.tax,s.nama ";
$sql.=" FROM tbs_penjualan tp INNER JOIN satuan s ON tp.satuan = s.id INNER JOIN barang bb ON tp.kode_barang = bb.kode_barang ";
$sql.=" WHERE no_faktur = '$no_faktur' ";

$query = mysqli_query($conn, $sql) or die("eror 1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
$sql ="SELECT bb.berkaitan_dgn_stok,tp.id,tp.no_faktur,tp.kode_barang,tp.satuan,tp.nama_barang,tp.jumlah_barang,tp.harga,tp.subtotal,tp.potongan,tp.tax,s.nama ";
$sql.=" FROM tbs_penjualan tp INNER JOIN satuan s ON tp.satuan = s.id INNER JOIN barang bb ON tp.kode_barang = bb.kode_barang ";
$sql.="  WHERE no_faktur = '$no_faktur' AND 1=1 ";

    $sql.=" AND (tp.kode_barang LIKE '".$requestData['search']['value']."%'";  
    $sql.=" OR tp.nama_barang LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR s.nama LIKE '".$requestData['search']['value']."%' )";

}


$query=mysqli_query($conn, $sql) or die("eror 2");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        
$sql.=" ORDER BY kode_barang ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */    
$query=mysqli_query($conn, $sql) or die("eror 3");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
  $nestedData=array(); 

      $nestedData[] = $row["kode_barang"];
      $nestedData[] = $row["nama_barang"];

$pilih = $db->query("SELECT no_faktur_penjualan FROM detail_retur_penjualan WHERE no_faktur_penjualan = '$row[no_faktur]' AND kode_barang = '$row[kode_barang]'");
$row_retur = mysqli_num_rows($pilih);

$pilih = $db->query("SELECT no_faktur_penjualan FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$row[no_faktur]'");
$row_piutang = mysqli_num_rows($pilih);

      if ($row_retur > 0 || $row_piutang > 0) {


      $nestedData[] = "<p class='edit-jumlah-alert' data-id='".$row['id']."' data-faktur='".$row['no_faktur']."' data-kode='".$row['kode_barang']."'><span id='text-jumlah-".$row['id']."'>". $row['jumlah_barang'] ."</span> <input type='hidden' id='input-jumlah-".$row['id']."' value='".$row['jumlah_barang']."' class='input_jumlah' data-id='".$row['id']."' autofocus='' data-berstok = '".$row['berkaitan_dgn_stok']."' data-kode='".$row['kode_barang']."' data-satuan='".$row['satuan']."' data-harga='".$row['harga']."' > </p>";
      }
      else {

      $nestedData[] = "<p class='edit-jumlah' data-id='".$row['id']."' data-faktur='".$row['no_faktur']."' data-kode='".$row['kode_barang']."'><span id='text-jumlah-".$row['id']."'>". $row['jumlah_barang'] ."</span> <input type='hidden' id='input-jumlah-".$row['id']."' value='".$row['jumlah_barang']."' class='input_jumlah' data-id='".$row['id']."' autofocus='' data-kode='".$row['kode_barang']."' data-satuan='".$row['satuan']."' data-berstok = '".$row['berkaitan_dgn_stok']."' data-harga='".$row['harga']."' > </p>";

      }

      $nestedData[] = $row["nama"];


      $nestedData[] = "<p  align='right'>".$row["harga"]."</p>";
      $nestedData[] = "<p style='font-size:15px' align='right'><span id='text-potongan-".$row['id']."'> ".$row["potongan"]." </span> </p>";
      $nestedData[] = "<p style='font-size:15px' align='right'><span id='text-tax-".$row['id']."'> ".$row["tax"]." </span> </p>";
      $nestedData[] = "<p style='font-size:15px' align='right'><span id='text-subtotal-".$row['id']."'> ".$row["subtotal"]." </span> </p>";

      if ($row_retur > 0 || $row_piutang > 0) {


      $nestedData[] = "<button class='btn btn-danger btn-alert-hapus' data-id='".$row['id']."' data-subtotal='".$row['subtotal']."' data-faktur='".$row['no_faktur']."' data-kode='".$row['kode_barang']."'><span class='glyphicon glyphicon-trash'></span> Hapus </button>";
    }
    else{
      $nestedData[] = "<button class='btn btn-danger btn-hapus-tbs' data-id='". $row['id'] ."' data-subtotal='".$row['subtotal']."' data-kode-barang='". $row['kode_barang'] ."' data-barang='". $row['nama_barang'] ."'><span class='glyphicon glyphicon-trash'> </span> Hapus </button>";
    }



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