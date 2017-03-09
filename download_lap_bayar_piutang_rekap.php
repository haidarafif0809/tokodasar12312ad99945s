<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_bayar_piutang_rekap.xls");

include 'db.php';
include 'sanitasi.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);


//menampilkan seluruh data yang ada pada tabel penjualan
$perintah = $db->query("SELECT p.nama_pelanggan,pp.nama_suplier,pp.no_faktur_pembayaran,pp.tanggal,pp.dari_kas,pp.total FROM pembayaran_piutang pp LEFT JOIN pelanggan p ON pp.nama_suplier = p.kode_pelanggan WHERE pp.tanggal >= '$dari_tanggal' AND pp.tanggal <= '$sampai_tanggal'");
?>

<div class="container">
<center><h3><b>Data Laporan Pembayaran Piutang Rekap</b></h3></center>
<table id="tableuser" class="table table-bordered">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Kode Pelanggan </th>
      <th style="background-color: #4CAF50; color: white;"> Cara Bayar </th>
      <th style="background-color: #4CAF50; color: white;"> Potongan </th>
      <th style="background-color: #4CAF50; color: white;"> Jumlah Bayar </th>
      
      
    </thead>
    
    <tbody>
    <?php

      //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah))
      {
        $perintah0 = $db->query("SELECT * FROM detail_pembayaran_piutang WHERE no_faktur_pembayaran = '$data1[no_faktur_pembayaran]'");
        $cek = mysqli_fetch_array($perintah0);
      echo "<tr>
      <td>". $data1['no_faktur_pembayaran'] ."</td>
      <td>". $data1['tanggal'] ."</td>
      <td> ". $data1['nama_suplier'] ." ". $data1['nama_pelanggan'] ."</td>
      <td>". $data1['dari_kas'] ."</td>
      <td>". $cek['potongan'] ."</td>
      <td>". $data1['total'] ."</td>

      
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
