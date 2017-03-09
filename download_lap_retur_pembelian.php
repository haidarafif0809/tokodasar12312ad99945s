<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_retur_pembelian.xls");

include 'db.php';
include 'sanitasi.php';

//menampilkan seluruh data yang ada pada tabel Pembelian
$perintah = $db->query("SELECT p.id,p.no_faktur_retur,p.total,p.nama_suplier,p.tunai,p.tanggal,p.jam,p.user_buat,p.potongan,p.tax,p.sisa,s.nama FROM retur_pembelian p INNER JOIN suplier s ON p.nama_suplier = s.id ORDER BY p.id DESC");
?>

<div class="container">
<center><h3><b>Data Laporan Retur Pembelian</b></h3></center>
<table id="tableuser" class="table table-bordered">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur Retur </th>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Nama Suplier </th>
      <th style="background-color: #4CAF50; color: white;"> Jumlah Retur </th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
      <th style="background-color: #4CAF50; color: white;"> Potongan </th>
      <th style="background-color: #4CAF50; color: white;"> Tax </th>
      <th style="background-color: #4CAF50; color: white;"> Tunai </th>

    </thead>
    
    <tbody>
    <?php

      //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah))
      {
        $perintah1 = $db->query("SELECT jumlah_retur FROM detail_retur_pembelian WHERE no_faktur_retur = '$data1[no_faktur_retur]'");
        $cek = mysqli_fetch_array($perintah1);
        $jumlah_retur = $cek['jumlah_retur'];
        //menampilkan data
      echo "<tr>
      <td>". $data1['no_faktur_retur'] ."</td>
      <td>". $data1['tanggal'] ."</td>
      <td>". $data1['nama'] ."</td>
      <td>". $jumlah_retur ."</td>
      <td>". $data1['total'] ."</td>
      <td>". $data1['potongan'] ."</td>
      <td>". $data1['tax'] ."</td>
      <td>". $data1['tunai'] ."</td>

      
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
