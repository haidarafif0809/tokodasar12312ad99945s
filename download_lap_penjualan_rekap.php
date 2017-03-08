<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_penjualan_rekap.xls");

include 'db.php';
include 'sanitasi.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);

//menampilkan seluruh data yang ada pada tabel Pembelian
$perintah = $db->query("SELECT pel.nama_pelanggan,dp.tanggal,dp.no_faktur,dp.kode_pelanggan,dp.total,dp.jam,dp.user,dp.status,dp.potongan,dp.tax,dp.sisa,dp.kredit FROM penjualan dp INNER JOIN pelanggan pel ON dp.kode_pelanggan = pel.kode_pelanggan WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal'");

?>

<div class="container">
<center><h3><b>Data Laporan Penjualan Rekap</b></h3></center>
<table id="tableuser" class="table table-bordered">
            <thead>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
    <th style="background-color: #4CAF50; color: white;"> Kode Pelanggan</th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
      <th style="background-color: #4CAF50; color: white;"> Jam </th>
      <th style="background-color: #4CAF50; color: white;"> User </th>
      <th style="background-color: #4CAF50; color: white;"> Status </th>
      <th style="background-color: #4CAF50; color: white;"> Potongan </th>
      <th style="background-color: #4CAF50; color: white;"> Tax </th>
      <th style="background-color: #4CAF50; color: white;"> Kembalian </th>
      <th style="background-color: #4CAF50; color: white;"> Kredit </th>
            
    </thead>
    
    <tbody>
    <?php

      //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah))

      {
        //menampilkan data
      echo "<tr>
      <td>". $data1['tanggal'] ."</td>
      <td>". $data1['no_faktur'] ."</td>
      <td>". $data1['kode_pelanggan'] ." ". $data1['nama_pelanggan'] ."</td>
      <td>". $data1['total'] ."</td>
      <td>". $data1['jam'] ."</td>
      <td>". $data1['user'] ."</td>
      <td>". $data1['status'] ."</td>
      <td>". $data1['potongan'] ."</td>
      <td>". $data1['tax'] ."</td>
      <td>". $data1['sisa'] ."</td>
      <td>". $data1['kredit'] ."</td>
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
