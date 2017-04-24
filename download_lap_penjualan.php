<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

include 'db.php';
include 'sanitasi.php';

//menampilkan seluruh data yang ada pada tabel Pembelian
$perintah = $db->query("SELECT pel.nama_pelanggan ,p.total, p.no_faktur ,p.kode_pelanggan ,p.tanggal ,p.jam ,p.user ,p.status ,p.potongan ,p.tax ,p.sisa FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan ORDER BY p.no_faktur DESC");


$jumlah_total_bersih = $db->query("SELECT SUM(total) AS total_bersih FROM penjualan");
$ambil = mysqli_fetch_array($jumlah_total_bersih);

$sub_total_bersih = $ambil['total_bersih'];


$jumlah_total_kotor = $db->query("SELECT SUM(subtotal) AS total_kotor FROM detail_penjualan");
$ambil_kotor = mysqli_fetch_array($jumlah_total_kotor);

$sub_total_kotor = $ambil_kotor['total_kotor'];

$jumlah_potongan = $db->query("SELECT SUM(potongan) AS total_potongan FROM penjualan");
$ambil_potongan = mysqli_fetch_array($jumlah_potongan);

$sub_total_potongan = $ambil_potongan['total_potongan'];

$jumlah_total_tax = $db->query("SELECT SUM(tax) AS total_tax FROM penjualan");
$ambil_tax = mysqli_fetch_array($jumlah_total_tax);

$sub_total_tax = $ambil_tax['total_tax'];
?>

<div class="container">
<center><h3><b>Data Laporan Penjualan</b></h3></center>
<table id="tableuser" class="table table-bordered">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Kode Pelanggan</th>
      <th style="background-color: #4CAF50; color: white;"> Total Kotor </th>
      <th style="background-color: #4CAF50; color: white;"> Total Bersih </th>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Jam </th>
      <th style="background-color: #4CAF50; color: white;"> User </th>
      <th style="background-color: #4CAF50; color: white;"> Status </th>
      <th style="background-color: #4CAF50; color: white;"> Potongan </th>
      <th style="background-color: #4CAF50; color: white;"> Tax </th>
      <th style="background-color: #4CAF50; color: white;"> Kembalian </th>
            
    </thead>
    
    <tbody>
    <?php

      //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah))

      {

        $sum_subtotal = $db->query("SELECT SUM(subtotal) AS total_kotor FROM detail_penjualan WHERE no_faktur = '$data1[no_faktur]' ");

        $ambil_sum_subtotal = mysqli_fetch_array($sum_subtotal);
        $total_kotor = $ambil_sum_subtotal['total_kotor'];


        //menampilkan data
      echo "<tr>
      <td>". $data1['no_faktur'] ."</td>
      <td>". $data1['kode_pelanggan'] ." ". $data1['nama_pelanggan'] ."</td>
      <td>". $total_kotor ."</td>
      <td>". $data1['total'] ."</td>
      <td>". $data1['tanggal'] ."</td>
      <td>". $data1['jam'] ."</td>
      <td>". $data1['user'] ."</td>
      <td>". $data1['status'] ."</td>
      <td>". $data1['potongan'] ."</td>
      <td>". $data1['tax'] ."</td>
      <td>". $data1['sisa'] ."</td>
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

    <h3><i> Sub. Total Bersih : <b>Rp. <?php echo $sub_total_bersih; ?></b> --- Sub. Total Kotor : <b>Rp. <?php echo $sub_total_kotor; ?></b></i></h3> 
<h3><i> Total Potongan : <b>Rp. <?php echo $sub_total_potongan; ?></b> --- Total Pajak : <b>Rp. <?php echo $sub_total_tax; ?></b></i></h3> 
            
  </tbody>
  </table>


   

     <div class="col-sm-3">

 <b>&nbsp;&nbsp;&nbsp;&nbsp;Petugas<br><br><br><br>( ................... )</b>

    </div>


</div>
        

</div> <!--end container-->
