<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_pembelian.xls");

include 'db.php';
include 'sanitasi.php';

//menampilkan seluruh data yang ada pada tabel Pembelian
$perintah = $db->query("SELECT p.id,p.no_faktur,p.tunai,p.total,p.suplier,p.tanggal,p.tanggal_jt,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit,s.nama,g.nama_gudang FROM pembelian p INNER JOIN suplier s ON p.suplier = s.id INNER JOIN gudang g ON p.kode_gudang = g.kode_gudang ORDER BY p.id DESC");

?>

<div class="container">
<center><h3><b>Data Laporan Pembelian</b></h3></center>
<table id="tableuser" class="table table-bordered">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Nama Suplier </th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Jam </th>
      <th style="background-color: #4CAF50; color: white;"> User </th>
      <th style="background-color: #4CAF50; color: white;"> Status </th>
      <th style="background-color: #4CAF50; color: white;"> Potongan </th>
      <th style="background-color: #4CAF50; color: white;"> Tax </th>
      <th style="background-color: #4CAF50; color: white;"> Tunai </th>
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
      <td>". $data1['no_faktur'] ."</td>
      <td>". $data1['nama'] ."</td>
      <td>". $data1['total'] ."</td>
      <td>". $data1['tanggal'] ."</td>
      <td>". $data1['jam'] ."</td>
      <td>". $data1['user'] ."</td>
      <td>". $data1['status'] ."</td>
      <td>". $data1['potongan'] ."</td>
      <td>". $data1['tax'] ."</td>
      <td>". $data1['tunai'] ."</td>
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
