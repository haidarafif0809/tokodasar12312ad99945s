<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_seluruh_komisi_per_petugas.xls");

include 'db.php';
include 'sanitasi.php';

//menampilkan seluruh data yang ada pada tabel Pembelian

$nama_petugas = stringdoang($_GET['nama_petugas']);
$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);


//menampilkan seluruh data yang ada pada tabel penjualan
$perintah = $db->query("SELECT * FROM laporan_fee_produk WHERE nama_petugas = '$nama_petugas' AND tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");

$perintah1 = $db->query("SELECT * FROM laporan_fee_faktur WHERE nama_petugas = '$nama_petugas' AND tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");

$query01 = $db->query("SELECT SUM(jumlah_fee) AS total_fee FROM laporan_fee_produk WHERE nama_petugas = '$nama_petugas' AND tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
$cek01 = mysqli_fetch_array($query01);
$total_fee1 = $cek01['total_fee'];

$query0 = $db->query("SELECT SUM(jumlah_fee) AS total_fee FROM laporan_fee_faktur WHERE nama_petugas = '$nama_petugas' AND tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
$cek0 = mysqli_fetch_array($query0);
$total_fee2 = $cek0['total_fee'];

$total_komisi = $total_fee1 + $total_fee2;

?>

<div class="container">
<center><h3><b>Data Laporan Seluruh Komisi Per Petugas</b></h3></center>

<h3><b>Komisi Produk / Petugas</b></h3><br>
<table id="tableuser" class="table table-bordered">
            <thead>
                  <th style="background-color: #4CAF50; color: white;"> Nama Petugas </th>
                  <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
                  <th style="background-color: #4CAF50; color: white;"> Kode Produk </th>
                  <th style="background-color: #4CAF50; color: white;"> Nama Produk </th>
                  <th style="background-color: #4CAF50; color: white;"> Jumlah Komisi </th>
                  <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
                  <th style="background-color: #4CAF50; color: white;"> Jam </th>


                  <?php 

                  
                  ?>
                  
            </thead>
            
            <tbody>
            <?php

                  //menyimpan data sementara yang ada pada $perintah
                  while ($data1 = mysqli_fetch_array($perintah))

                  {
                  
                  echo "<tr class='pilih' data-petugas='". $data1['nama_petugas'] ."'>
                  <td>". $data1['nama_petugas'] ."</td>
                  <td>". $data1['no_faktur'] ."</td>
                  <td>". $data1['kode_produk'] ."</td>
                  <td>". $data1['nama_produk'] ."</td>
                  <td>". $data1['jumlah_fee'] ."</td>
                  <td>". tanggal($data1['tanggal']) ."</td>
                  <td>". $data1['jam'] ."</td>
                  </tr>";
                  }
            ?>
            </tbody>

      </table>
<br>
<h3><b>Komisi Faktur / Petugas</b></h3><br>
<table id="tableuser" class="table table-bordered">
            <thead>
                  <th style="background-color: #4CAF50; color: white;"> Nama Petugas </th>
                  <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
                  <th style="background-color: #4CAF50; color: white;"> Jumlah Komisi </th>
                  <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
                  <th style="background-color: #4CAF50; color: white;"> Jam </th>
                  
            </thead>
            
            <tbody>
            <?php

                  //menyimpan data sementara yang ada pada $perintah
                  while ($data0 = mysqli_fetch_array($perintah1))

                  {
                  
                  echo "<tr class='pilih' data-petugas='". $data1['nama_petugas'] ."'>
                  <td>". $data0['nama_petugas'] ."</td>
                  <td>". $data0['no_faktur'] ."</td>
                  <td>". $data0['jumlah_fee'] ."</td>
                  <td>". tanggal($data0['tanggal']) ."</td>
                  <td>". $data0['jam'] ."</td>
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

   <h4> Total Komisi / Produk Dari <?php echo tanggal($dari_tanggal); ?> s/d <?php echo tanggal($sampai_tanggal); ?> : <b><?php echo rp($total_fee1); ?></b></h4>
   
 <h4> Total Komisi / Faktur Dari <?php echo tanggal($dari_tanggal); ?> s/d <?php echo tanggal($sampai_tanggal); ?> : <b><?php echo rp($total_fee2); ?></b></h4><br>

      <h3 style="color: red"><b> Total Komisi Dari <?php echo tanggal($dari_tanggal); ?> s/d <?php echo tanggal($sampai_tanggal); ?> : <?php echo rp($total_komisi); ?> </b></h3>

          
    
            
  </tbody>
  </table>


   

     <div class="col-sm-3">

 <b>&nbsp;&nbsp;&nbsp;&nbsp;Petugas<br><br><br><br>( ................... )</b>

    </div>


</div>
        

</div> <!--end container-->
