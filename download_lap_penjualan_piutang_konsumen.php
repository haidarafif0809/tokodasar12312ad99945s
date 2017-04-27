<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_penjualan_piutang.xls");

include 'db.php';
include 'sanitasi.php';


$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);
$konsumen = stringdoang($_GET['konsumen']);
$sales = stringdoang($_GET['sales']);

 $query1 = $db->query("SELECT * FROM perusahaan ");
    $data1 = mysqli_fetch_array($query1);

// LOGIKA UNTUK AMBIL BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)
if ($konsumen == 'semua' AND $sales == 'semua'){

  $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 ");
  $data_sum_dari_penjualan = mysqli_fetch_array($query_sum_dari_penjualan);
  
}
else if ($konsumen != 'semua' AND $sales == 'semua'){

  $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND kode_pelanggan = '$konsumen' ");
  $data_sum_dari_penjualan = mysqli_fetch_array($query_sum_dari_penjualan);

}
else if ($konsumen == 'semua' AND $sales != 'semua'){

   $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND  sales = '$sales' ");
  $data_sum_dari_penjualan = mysqli_fetch_array($query_sum_dari_penjualan);

}
else{

  $query_sum_dari_penjualan = $db->query("SELECT no_faktur,SUM(tunai) AS tunai_penjualan,SUM(total) AS total_akhir, SUM(kredit) AS total_kredit FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0 AND kode_pelanggan = '$konsumen' AND sales = '$sales' ");
  $data_sum_dari_penjualan = mysqli_fetch_array($query_sum_dari_penjualan);

}

  $query_sum_dari_detail_pembayaran_piutang = $db->query("SELECT SUM(jumlah_bayar) + SUM(potongan) AS ambil_total_bayar FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$data_sum_dari_penjualan[no_faktur]' ");
  $data_sum_dari_detail_pembayaran_piutang = mysqli_fetch_array($query_sum_dari_detail_pembayaran_piutang);


// LOGIKA UNTUK  UNTUK AMBIL  BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)
$total_akhir = $data_sum_dari_penjualan['total_akhir'];
$total_kredit = $data_sum_dari_penjualan['total_kredit'];
$total_bayar = $data_sum_dari_penjualan['tunai_penjualan'] +  $data_sum_dari_detail_pembayaran_piutang['ambil_total_bayar'];




if ($konsumen == 'semua')
{
$nama_pelanggan = "Semua Konsumen";
}
else
{
  $nama_pelanggan =  $cek02['nama_pelanggan'];
}

if ($sales == 'semua')
{
$sales_ganti = "Semua Sales";
}
else
{
  $sales_ganti =  $cek02['sales'];
}




 ?>
<div class="container">
 <div class="row"><!--row1-->
        <div class="col-sm-6">
                 <h3> <b> LAPORAN PIUTANG KONSUMEN & SALES </b></h3>
                 <h4> <b> <?php echo $data1['nama_perusahaan']; ?> </b> </h4> 
                 <p> <?php echo $data1['alamat_perusahaan']; ?> </p> 
                 <p> No.Telp:<?php echo $data1['no_telp']; ?> </p> 
                 
        </div><!--penutup colsm4-->

        <div class="col-sm-4">
         <br><br>                 
<table>
  <tbody>

      <tr><td  width="20%">PERIODE</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo tanggal($dari_tanggal); ?> s/d <?php echo tanggal($sampai_tanggal); ?></td>
      </tr>
       <tr><td  width="20%">KONSUMEN</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo $nama_pelanggan; ?></td>
      </tr>
       <tr><td  width="20%">SALES</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo $sales_ganti; ?></td>
      </tr>
            
  </tbody>
</table>           
                 
        </div><!--penutup colsm4-->

    </div><!--penutup row1-->
    <br>
    <br>
    <br>


 <table id="tableuser" class="table table-bordered table-sm">
            <thead>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Nama Konsumen</th>
      <th style="background-color: #4CAF50; color: white;"> Sales </th>
      <th style="background-color: #4CAF50; color: white;"> Tgl. Transaksi </th>
      <th style="background-color: #4CAF50; color: white;"> Tgl. Jatuh Tempo </th>
      <th style="background-color: #4CAF50; color: white;"> Usia Piutang </th>
      <th style="background-color: #4CAF50; color: white;"> Nilai Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Dibayar </th>
      <th style="background-color: #4CAF50; color: white;"> Piutang </th>
                                    
            </thead>
            
            <tbody>
            <?php

// LOGIKA UNTUK FILTER BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)
if ($konsumen == 'semua' AND $sales == 'semua')
{
          $perintah009 = $db->query("SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 ORDER BY dp.tanggal DESC ");
 }

else if ($konsumen != 'semua' AND $sales == 'semua')
 {
          $perintah009 = $db->query("SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.kode_pelanggan = '$konsumen' ORDER BY dp.tanggal DESC ");

 }  
else if ($konsumen == 'semua' AND $sales != 'semua')
{
         $perintah009 = $db->query("SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.sales = '$sales' ORDER BY dp.tanggal DESC ");
}
else
{
        $perintah009 = $db->query("SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.tanggal_jt, DATEDIFF(dp.tanggal_jt,DATE(NOW())) AS usia_piutang ,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 AND dp.kode_pelanggan = '$konsumen' AND dp.sales = '$sales' ORDER BY dp.tanggal DESC ");

}
// LOGIKA UNTUK FILTER BERDASARKAN KONSUMEN DAN SALES (QUERY TAMPIL AWAL)

  while ($data11 = mysqli_fetch_array($perintah009))
        {


// MENCARI TOTAL PEMBAYARAN PIUTANG  
$query0232 = $db->query("SELECT SUM(jumlah_bayar) + SUM(potongan) AS total_bayar FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$data11[no_faktur]' ");
$kel_bayar = mysqli_fetch_array($query0232);
$num_rows = mysqli_num_rows($query0232);


$sum_dp = $db->query("SELECT SUM(tunai) AS tunai_penjualan FROM penjualan WHERE no_faktur = '$data11[no_faktur]' ");
$data_sum = mysqli_fetch_array($sum_dp);
$Dp = $data_sum['tunai_penjualan']; 


$tot_bayar = $kel_bayar['total_bayar'] + $Dp;
// MENCARI TOTAL PEMBAYARAN PIUTANG  


                  echo "<tr>
                  <td>". $data11['no_faktur'] ."</td>
                  <td>". $data11['nama_pelanggan'] ."</td>
                  <td>". $data11['sales'] ."</td>
                  <td>". $data11['tanggal'] ."</td>
                  <td>". $data11['tanggal_jt'] ."</td>
                  <td align='right'>". rp($data11['usia_piutang']) ." Hari</td>
                  <td align='right'>". rp($data11['total']) ."</td>";
                  if ($num_rows > 0)
                  {
                      echo "<td align='right'>". rp($tot_bayar) ."</td>";
                  }
                  else
                  {
                    echo 0;
                  }
                  echo "<td align='right'>". rp($data11['kredit']) ."</td>
                  </tr>";


                  }



    echo "<td><p style='color:red'> TOTAL </p></td>
      <td><p style='color:red'> - </p></td>
      <td><p style='color:red'> - </p></td>
      <td><p style='color:red'> - </p></td>
      <td><p style='color:red'> - </p></td>
      <td><p style='color:red' align='right'> - </p></td>
      <td><p style='color:red' align='right'> ".rp($total_akhir)." </p></td>
      <td><p style='color:red' align='right'> ".rp($total_bayar)." </p></td>
      <td><p style='color:red' align='right'> ".rp($total_kredit)." </p></td>";  

//Untuk Memutuskan Koneksi Ke Database

mysqli_close($db); 

            ?>
            </tbody>

      </table>
      <hr>
</div>
</div>
<br>


     </div>
