<?php 
include 'header.php';
include 'sanitasi.php';
include 'db.php';


$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);

    $query1 = $db->query("SELECT * FROM perusahaan ");
    $data1 = mysqli_fetch_array($query1);


$query02 = $db->query("SELECT SUM(pen.tunai) AS tunai_penjualan,SUM(pen.total) AS total_akhir, SUM(pen.kredit) AS total_kredit,SUM(dpp.jumlah_bayar) + SUM(dpp.potongan) AS ambil_total_bayar FROM penjualan pen LEFT JOIN detail_pembayaran_piutang dpp ON pen.no_faktur = dpp.no_faktur_penjualan WHERE pen.tanggal >= '$dari_tanggal' AND pen.tanggal <= '$sampai_tanggal' AND pen.kredit != 0 ");
$cek02 = mysqli_fetch_array($query02);
$total_akhir = $cek02['total_akhir'];
$total_kredit = $cek02['total_kredit'];

$total_bayar = $cek02['tunai_penjualan'] +  $cek02['ambil_total_bayar'];




 ?>
<div class="container">
 <div class="row"><!--row1-->
        <div class="col-sm-2">
        <br><br>
                <img src='save_picture/<?php echo $data1['foto']; ?>' class='img-rounded' alt='Cinque Terre' width='160' height='140`'> 
        </div><!--penutup colsm2-->

        <div class="col-sm-6">
                 <h3> <b> LAPORAN PIUTANG SEMUA KONSUMEN / SALES </b></h3>
                 <hr>
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
            
  </tbody>
</table>           
                 
        </div><!--penutup colsm4-->


        
    </div><!--penutup row1-->
    <br>
    <br>
    <br>


 <table id="tableuser" class="table table-bordered table-sm">
            <thead>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Nama Costumer</th>
      <th style="background-color: #4CAF50; color: white;"> Sales </th>
      <th style="background-color: #4CAF50; color: white;"> Nilai Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Dibayar </th>
      <th style="background-color: #4CAF50; color: white;"> Piutang </th>
                                    
            </thead>
            
            <tbody>
            <?php

          $perintah009 = $db->query("SELECT dp.id,pel.nama_pelanggan,dp.tanggal,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.sales,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit FROM penjualan dp LEFT JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND dp.kredit != 0 ORDER BY dp.tanggal DESC ");
                  while ($data11 = mysqli_fetch_array($perintah009))

                  {

$query0232 = $db->query("SELECT SUM(jumlah_bayar) + SUM(potongan) AS total_bayar FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$data11[no_faktur]' ");
$kel_bayar = mysqli_fetch_array($query0232);
$num_rows = mysqli_num_rows($query0232);

$sum_dp = $db->query("SELECT SUM(tunai) AS tunai_penjualan FROM penjualan WHERE no_faktur = '$data11[no_faktur]' ");
$data_sum = mysqli_fetch_array($sum_dp);
$Dp = $data_sum['tunai_penjualan'];


$tot_bayar = $kel_bayar['total_bayar'] + $Dp;



                  echo "<tr>
                  <td>". $data11['tanggal'] ."</td>
                  <td>". $data11['no_faktur'] ."</td>
                  <td>". $data11['nama_pelanggan'] ."</td>
                  <td>". $data11['sales'] ."</td>
                  <td>". rp($data11['total']) ."</td>";
                  if ($num_rows > 0)
                  {
                      echo "<td>". rp($tot_bayar) ."</td>";
                  }
                  else
                  {
                    echo 0;
                  }
                  echo "<td>". rp($data11['kredit']) ."</td>
                  </tr>";


                  }


    echo "<td><p style='color:red'> TOTAL </p></td>
      <td><p style='color:red'> - </p></td>
      <td><p style='color:red'> - </p></td>
      <td><p style='color:red'> - </p></td>
      <td><p style='color:red'> ".rp($total_akhir)." </p></td>
      <td><p style='color:red'> ".rp($total_bayar)." </p></td>
      <td><p style='color:red'> ".rp($total_kredit)." </p></td>";              


//Untuk Memutuskan Koneksi Ke Database

mysqli_close($db); 

            ?>
            </tbody>

      </table>
      <hr>
</div>
</div>
<br>

<div class="col-sm-6">
</div>




     </div>

 <script>
$(document).ready(function(){
  window.print();
});
</script>

<?php include 'footer.php'; ?>