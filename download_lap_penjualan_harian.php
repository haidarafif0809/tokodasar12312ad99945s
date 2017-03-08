<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_penjualan_harian.xls");

include 'db.php';
include 'sanitasi.php';

//menampilkan seluruh data yang ada pada tabel Pembelian
$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);


//menampilkan seluruh data yang ada pada tabel penjualan
$perintah = $db->query("SELECT tanggal FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' GROUP BY tanggal");



?>

<div class="container">
<center><h3><b>Data Laporan Penjualan Harian</b></h3></center>
<table id="tableuser" class="table table-bordered">
          <thead>
          <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
          <th style="background-color: #4CAF50; color: white;"> Jumlah Transaksi </th>
          <th style="background-color: #4CAF50; color: white;"> Total Transaksi </th>
          <th style="background-color: #4CAF50; color: white;"> Jumlah Bayar Tunai </th>
          <th style="background-color: #4CAF50; color: white;"> Jumlah Bayar Kredit </th>

          
          </thead>
          
          <tbody>
          <?php
          
          //menyimpan data sementara yang ada pada $perintah
          while ($data = mysqli_fetch_array($perintah))
          {
          //menampilkan data
            $perintah1 = $db->query("SELECT * FROM penjualan WHERE tanggal = '$data[tanggal]'");
            $data1 = mysqli_num_rows($perintah1);

            $perintah2 = $db->query("SELECT SUM(total) AS t_total FROM penjualan WHERE tanggal = '$data[tanggal]'");
            $data2 = mysqli_fetch_array($perintah2);
            $t_total = $data2['t_total'];

            $perintah21 = $db->query("SELECT SUM(nilai_kredit) AS t_kredit FROM penjualan WHERE tanggal = '$data[tanggal]'");
            $data21 = mysqli_fetch_array($perintah21);
            $t_kredit = $data21['t_kredit'];

            $t_bayar = $t_total - $t_kredit;

          echo "<tr>
          <td>". $data['tanggal'] ."</td>
          <td>". $data1."</td>
          <td>". $t_total ."</td>
          <td>". $t_bayar ."</td>
          <td>". $t_kredit ."</td>


          </tr>";
          }

          //Untuk Memutuskan Koneksi Ke Database
          mysqli_close($db);   
          ?>
          </tbody>
          
          </table>
<br>

    
<hr>
 <div class="row">
     
     <div class="col-sm-3"></div>
     <div class="col-sm-3"></div>
     <div class="col-sm-3"></div>
        
 <table>
  <tbody>

    
            
  </tbody>
  </table>


   

     <div class="col-sm-3">

 <b>&nbsp;&nbsp;&nbsp;&nbsp;Petugas<br><br><br><br>( ................... )</b>

    </div>


</div>
        

</div> <!--end container-->
