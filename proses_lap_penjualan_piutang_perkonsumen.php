<?php
include 'sanitasi.php';
include 'db.php';


$dari_tanggal = stringdoang($_POST['dari_tanggal']);
$sampai_tanggal = stringdoang($_POST['sampai_tanggal']);
$konsumen = stringdoang($_POST['konsumen']);
$sales = stringdoang($_POST['sales']);


$data_sum_dari_detail_pembayaran = 0;


// LOGIKA UNTUK AMBIL BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)
if ($konsumen == 'semua' AND $sales == 'semua'){
  $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 ");

  $query_no_faktur_penjualan = $db->query("SELECT no_faktur FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0  ");

}
else if ($konsumen != 'semua' AND $sales == 'semua'){

  $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND kode_pelanggan = '$konsumen' ");

  $query_no_faktur_penjualan = $db->query("SELECT no_faktur FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND kode_pelanggan = '$konsumen' ");
 
}
else if ($konsumen == 'semua' AND $sales != 'semua'){

   $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND  sales = '$sales' ");

   $query_no_faktur_penjualan = $db->query("SELECT no_faktur FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND  sales = '$sales' ");

}
else{

  $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND kode_pelanggan = '$konsumen' AND sales = '$sales' ");


  $query_no_faktur_penjualan = $db->query("SELECT no_faktur FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0  AND kode_pelanggan = '$konsumen' AND sales = '$sales'  ");

}


while($data_no_faktur_penjualan = mysqli_fetch_array($query_no_faktur_penjualan)){

  $query_sum_dari_detail_pembayaran_piutang = $db->query("SELECT SUM(jumlah_bayar) + SUM(potongan) AS ambil_total_bayar FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$data_no_faktur_penjualan[no_faktur]' ");
  $data_sum_dari_detail_pembayaran_piutang = mysqli_fetch_array($query_sum_dari_detail_pembayaran_piutang);
$data_sum_dari_detail_pembayaran = $data_sum_dari_detail_pembayaran + $data_sum_dari_detail_pembayaran_piutang['ambil_total_bayar'];

}

$data_sum_dari_penjualan_lain = mysqli_fetch_array($query_sum_dari_penjualan);
$total_akhir = $data_sum_dari_penjualan_lain['total_akhir'];
$total_kredit = $data_sum_dari_penjualan_lain['total_kredit'];
$total_bayar = $data_sum_dari_penjualan_lain['tunai_penjualan'] + $data_sum_dari_detail_pembayaran;

// LOGIKA UNTUK  UNTUK AMBIL  BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name

  0 => 'Tanggal', 
  1 => 'Nomor',
  2 => 'nama_kostumer',
  3 => 'sales',
  4 => 'nilai_faktur', 
  5 => 'dibayar',
  6 => 'piutang',

);

// LOGIKA UNTUK FILTER BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)
if ($konsumen == 'semua' AND $sales == 'semua')
{
// getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(DATE(NOW()), dp.tanggal) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0  ";
}
else if ($konsumen != 'semua' AND $sales == 'semua')
{
  // getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(DATE(NOW()), dp.tanggal) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.kode_pelanggan = '$konsumen'  ";
}
else if ($konsumen == 'semua' AND $sales != 'semua')
{
  // getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(DATE(NOW()), dp.tanggal) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.sales = '$sales' ";
}
else
{
  // getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(DATE(NOW()), dp.tanggal) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.kode_pelanggan = '$konsumen' AND dp.sales = '$sales' ";
}
// LOGIKA UNTUK FILTER BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)


$query=mysqli_query($conn, $sql) or die("eror 1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

// LOGIKA UNTUK FILTER BERDASARKAN KONSUMEN DAN SALES (QUERY PENCARIAN DATATABLE)
if ($konsumen == 'semua' AND $sales == 'semua')
{
// getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND 1=1  ";
}
else if ($konsumen != 'semua' AND $sales == 'semua')
{
  // getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.kode_pelanggan = '$konsumen'  AND 1=1 ";
}
else if ($konsumen == 'semua' AND $sales != 'semua')

{
  // getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.sales = '$sales' AND 1=1 ";
}
else
{
  // getting total number records without any search
$sql =" SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit ";
$sql.=" FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.kode_pelanggan = '$konsumen' AND dp.sales = '$sales'  AND 1=1 ";
}
// LOGIKA UNTUK FILTER BERDASARKAN KONSUMEN DAN SALES (QUERY PENCARIAN DATATABLE)


if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
$sql.=" AND ( dp.no_faktur LIKE '".$requestData['search']['value']."%' ";
$sql.=" OR dp.tanggal LIKE '".$requestData['search']['value']."%' ";
$sql.=" OR dp.sales LIKE '".$requestData['search']['value']."%' ";
$sql.=" OR pel.nama_pelanggan LIKE '".$requestData['search']['value']."%' )";
	
}

$query=mysqli_query($conn, $sql) or die("eror 2");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.="ORDER BY dp.tanggal DESC  LIMIT ".$requestData['start']." ,".$requestData['length']." ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */  
$query=mysqli_query($conn, $sql) or die("eror 3");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
  $nestedData=array(); 


$query0232 = $db->query("SELECT SUM(jumlah_bayar) + SUM(potongan) AS total_bayar FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$row[no_faktur]' ");
$kel_bayar = mysqli_fetch_array($query0232);

$sum_dp = $db->query("SELECT SUM(tunai) AS tunai_penjualan FROM penjualan WHERE no_faktur = '$row[no_faktur]' ");
$data_sum = mysqli_fetch_array($sum_dp);
$Dp = $data_sum['tunai_penjualan'];

$num_rows = mysqli_num_rows($query0232);

$tot_bayar = $kel_bayar['total_bayar'] + $Dp;

      $nestedData[] = $row['no_faktur'];
      $nestedData[] = $row['nama_pelanggan'];
      $nestedData[] = $row['sales'];
      $nestedData[] = $row['tanggal'];
      $nestedData[] = $row['tanggal_jt'];
      $nestedData[] =  "<p align='right'>".rp($row['usia_piutang'])." Hari</p>";
      $nestedData[] =  "<p align='right'>".rp($row['total'])."</p>";

      if ($num_rows > 0 )
      {
      	$nestedData[] =  "<p align='right'>".rp($tot_bayar)."</p>";
      }
      else
      {
      	$nestedData[] = "0";

      }

      $nestedData[] =  "<p align='right'>".rp($row['kredit'])."</p>";

  $data[] = $nestedData;
}



$nestedData=array();      

      $nestedData[] = "<p style='color:red'> TOTAL </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red' align='right' > - </p>";
      $nestedData[] = "<p style='color:red' align='right' > ".rp($total_akhir)." </p>";
      $nestedData[] = "<p style='color:red' align='right' > ".rp($total_bayar)." </p>";
      $nestedData[] = "<p style='color:red' align='right' > ".rp($total_kredit)." </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
      $nestedData[] = "<p style='color:red'> - </p>";
  
  $data[] = $nestedData;

  
$json_data = array(
      "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
      "recordsTotal"    => intval( $totalData ),  // total number of records
      "recordsFiltered" => intval( $totalData ), // total number of records after searching, if there is no searching then totalFiltered = totalData
      "data"            => $data   // total data array
      );

echo json_encode($json_data);  // send data as json format

?>