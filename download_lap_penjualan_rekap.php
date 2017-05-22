<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_penjualan_rekap.xls");

include 'db.php';
include 'sanitasi.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);
$kategori = stringdoang($_GET['kategori']);


$total_akhir_kotor = 0;
$total_potongan = 0;
$total_tax = 0;
$total_jual = 0;
$total_tunai = 0;
$total_sisa  = 0;
$total_kredit = 0;


if ($kategori == "Semua Kategori") {
  # JIKA SEMUA KATEGORI
  $perintah_tampil = $db->query(" SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang  WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal'  GROUP BY p.no_faktur");
}
else{
  $perintah_tampil = $db->query(" SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang  WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND b.kategori = '$kategori' GROUP BY p.no_faktur");
}


?>

<div class="container">
<center><h3><b>Data Laporan Penjualan Rekap</b></h3></center>
<table id="tableuser" class="table table-bordered">
            <thead>
                <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
                <th style="background-color: #4CAF50; color: white;"> Tanggal </th> 
                <th style="background-color: #4CAF50; color: white;"> Jam </th>
                <th style="background-color: #4CAF50; color: white;"> Kategori </th>
                <th style="background-color: #4CAF50; color: white;"> Kode Pelanggan</th>
                <th style="background-color: #4CAF50; color: white;"> User </th>
                <th style="background-color: #4CAF50; color: white;"> Status </th>
                <th style="background-color: #4CAF50; color: white;"> Total Kotor</th>
                <th style="background-color: #4CAF50; color: white;"> Potongan </th>
                <th style="background-color: #4CAF50; color: white;"> Tax </th>
                <th style="background-color: #4CAF50; color: white;"> Total Bersih</th>
                <th style="background-color: #4CAF50; color: white;"> Tunai </th>
                <th style="background-color: #4CAF50; color: white;"> Kembalian </th>
                <th style="background-color: #4CAF50; color: white;"> Kredit </th>     
            
    </thead>
    
    <tbody>
    <?php

      //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah_tampil))

      {

       
        // untuk perhitungan jumlah total
        $total_kotor = $data1['total'] + $data1['potongan'];
        
        $total_akhir_kotor = $total_akhir_kotor + $total_kotor;
        
        $total_potongan = $total_potongan + $data1['potongan'];
        
        $total_tax = $total_tax + $data1['tax'];
        
        $total_jual = $total_jual + $data1['total'];
        
        $total_tunai = $total_tunai + $data1['tunai'];
        
        $total_sisa = $total_sisa + $data1['sisa'];
        
        $total_kredit = $total_kredit + $data1['kredit']; 
        // untuk perhitungan jumlah total



                    $total_kotor_jual = $data1['total'] + $data1['potongan'];
          
                  echo "<tr>
                  <td>". $data1['no_faktur'] ."</td>
                  <td>". $data1['tanggal'] ."</td>
                  <td>". $data1['jam'] ."</td>
                  <td>". $data1['kategori'] ."</td>
                  <td>". $data1['code_card'] ." - ". $data1['nama_pelanggan'] ."</td>
                  <td>". $data1['user'] ."</td>
                  <td>". $data1['status'] ."</td>
                  <td align='right'>". $total_kotor_jual ."</td>
                  <td align='right'>". $data1['potongan'] ."</td>
                  <td align='right'>". $data1['tax'] ."</td>
                  <td align='right'>". $data1['total'] ."</td>
                  <td align='right'>". $data1['tunai'] ."</td>
                  <td align='right'>". $data1['sisa'] ."</td>
                  <td align='right'>". $data1['kredit'] ."</td>
                  </tr>";
        }          
      //Untuk Memutuskan Koneksi Ke Database
      mysqli_close($db);   

    ?>

      <td style='color:red'> TOTAL </td>
      <td style='color:red'> - </td>
      <td style='color:red'> - </td>
      <td style='color:red'> - </td>
      <td style='color:red'> - </td>
      <td style='color:red'> - </td>
      <td style='color:red'> - </td>
      <td style='color:red' align='right'> <?php echo $total_akhir_kotor;?> </td>
      <td style='color:red' align='right'> <?php echo $total_potongan;?> </td>
      <td style='color:red' align='right'> <?php echo $total_tax;?> </td> 
      <td style='color:red' align='right'> <?php echo $total_jual;?> </td>
      <td style='color:red' align='right'> <?php echo $total_tunai;?> </td>
      <td style='color:red' align='right'> <?php echo $total_sisa;?> </td>
      <td style='color:red' align='right'> <?php echo $total_kredit;?> </td> 



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
