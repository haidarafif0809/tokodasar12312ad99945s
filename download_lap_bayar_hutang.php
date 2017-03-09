<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_bayar_hutang.xls");

include 'db.php';
include 'sanitasi.php';

//menampilkan seluruh data yang ada pada tabel Pembelian
$perintah = $db->query("SELECT da.nama_daftar_akun,p.no_faktur_pembayaran,p.tanggal,p.nama_suplier,p.dari_kas,p.total,s.nama FROM pembayaran_hutang p INNER JOIN suplier s ON p.nama_suplier = s.id INNER JOIN daftar_akun da ON p.dari_kas = da.kode_daftar_akun  ORDER BY p.id DESC");

?>

<div class="container">
<center><h3><b>Data Laporan Pembayaran Hutang</b></h3></center>
<table id="tableuser" class="table table-bordered">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Nama Suplier </th>
      <th style="background-color: #4CAF50; color: white;"> Cara Bayar </th>
      <th style="background-color: #4CAF50; color: white;"> Potongan </th>
      <th style="background-color: #4CAF50; color: white;"> Jumlah Bayar </th>

      
      
    </thead>
    
    <tbody>
    <?php

      //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah))
      {
        $perintah0 = $db->query("SELECT * FROM detail_pembayaran_hutang WHERE no_faktur_pembayaran = '$data1[no_faktur_pembayaran]'");
        $cek = mysqli_fetch_array($perintah0);
      echo "<tr>
      <td>". $data1['no_faktur_pembayaran'] ."</td>
      <td>". $data1['tanggal'] ."</td>
      <td>". $data1['nama'] ."</td>
      <td>". $data1['nama_daftar_akun'] ."</td>
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
