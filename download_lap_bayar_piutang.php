<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_bayar_piutang.xls");

include 'db.php';
include 'sanitasi.php';

//menampilkan seluruh data yang ada pada tabel Pembelian
$perintah = $db->query("SELECT p.nama_pelanggan,da.nama_daftar_akun,pp.no_faktur_pembayaran,pp.tanggal,pp.nama_suplier,pp.dari_kas,pp.total FROM pembayaran_piutang pp INNER JOIN daftar_akun da ON pp.dari_kas = da.kode_daftar_akun INNER JOIN pelanggan p ON pp.nama_suplier = p.kode_pelanggan ");

?>

<div class="container">
<center><h3><b>Data Laporan Pembayaran Piutang</b></h3></center>
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
      <td>". $data1['nama_suplier'] ." ". $data1['nama_pelanggan'] ."</td>
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
