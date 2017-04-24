<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_retur_penjualan_detail.xls");

include 'db.php';
include 'sanitasi.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);


//menampilkan seluruh data yang ada pada tabel penjualan
$perintah = $db->query("SELECT s.nama AS nama_satuan,drp.no_faktur_retur,drp.tanggal,drp.kode_barang,drp.nama_barang,drp.jumlah_retur,drp.satuan,drp.harga,drp.potongan,drp.subtotal FROM detail_retur_penjualan drp INNER JOIN satuan s ON drp.satuan = s.id WHERE drp.tanggal >= '$dari_tanggal' AND drp.tanggal <= '$sampai_tanggal'");

?>

<div class="container">
<center><h3><b>Data Laporan Retur Penjualan Detail</b></h3></center>
<table id="tableuser" class="table table-bordered">
          <thead>

          <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
          <th style="background-color: #4CAF50; color: white;"> Tanggal </th>         
          <th style="background-color: #4CAF50; color: white;"> Kode Barang </th>
          <th style="background-color: #4CAF50; color: white;"> Nama Barang </th>
          <th style="background-color: #4CAF50; color: white;"> Jumlah Retur </th>
          <th style="background-color: #4CAF50; color: white;"> Satuan </th>
          <th style="background-color: #4CAF50; color: white;"> Harga </th>
          <th style="background-color: #4CAF50; color: white;"> Potongan </th>
          <th style="background-color: #4CAF50; color: white;"> Subtotal </th>

          </thead>


          <tbody>

          <?php
          
          //menyimpan data sementara yang ada pada $perintah
          while ($data1 = mysqli_fetch_array($perintah))
          {


          //menampilkan data
          echo "<tr>
          <td>". $data1['no_faktur_retur'] ."</td>
          <td>". $data1['tanggal'] ."</td>
          <td>". $data1['kode_barang'] ."</td>
          <td>". $data1['nama_barang'] ."</td>
          <td>". $data1['jumlah_retur'] ."</td>
          <td>". $data1['nama_satuan'] ."</td>
          <td>". $data1['harga'] ."</td>
          <td>". $data1['potongan'] ."</td>
          <td>". $data1['subtotal'] ."</td>
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
