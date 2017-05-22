<?php 
include 'header.php';
include 'sanitasi.php';
include 'db.php';


$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);
$kategori = stringdoang($_GET['kategori']);

    $query1 = $db->query("SELECT foto,nama_perusahaan,alamat_perusahaan,no_telp FROM perusahaan ");
    $data1 = mysqli_fetch_array($query1);


$total_akhir_kotor = 0;
$total_potongan = 0;
$total_tax = 0;
$total_jual = 0;
$total_tunai = 0;
$total_sisa  = 0;
$total_kredit = 0;


if ($kategori == "Semua Kategori") {
  # JIKA SEMUA KATEGORI
  $perintah_tampil = $db->query(" SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang  WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal'");
}
else{
  $perintah_tampil = $db->query(" SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang  WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND b.kategori = '$kategori'");
}
 ?>
<div class="container">
 <div class="row"><!--row1-->
        <div class="col-sm-2">
        <br><br>
                <img src='save_picture/<?php echo $data1['foto']; ?>' class='img-rounded' alt='Cinque Terre' width='160' height='140`'> 
        </div><!--penutup colsm2-->

        <div class="col-sm-6">
                 <h3> <b> LAPORAN PENJUALAN REKAP </b></h3>
                 <hr>
                 <h4> <b> <?php echo $data1['nama_perusahaan']; ?> </b> </h4> 
                 <p> <?php echo $data1['alamat_perusahaan']; ?> </p> 
                 <p> No.Telp:<?php echo $data1['no_telp']; ?> </p> 
                 
        </div><!--penutup colsm4-->

        <div class="col-sm-4">
         <br><br>                 
<table>
  <tbody>

      <tr><td  width="20%">PERIODE</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo tanggal($dari_tanggal); ?> s/d <?php echo tanggal($sampai_tanggal); ?></td>
      <tr><td  width="20%">KATEGORI</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo $kategori; ?> </td>
      </tr>
            
  </tbody>
</table>           
                 
        </div><!--penutup colsm4-->


        
    </div><!--penutup row1-->
    <br>
    <br>
    <br>


 <table id="tableuser" class="table table-bordered table-sm">
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

                  while ($data11 = mysqli_fetch_array($perintah_tampil))

                  {

        // untuk perhitungan jumlah total
        $total_kotor = $data11['total'] + $data11['potongan'];
        
        $total_akhir_kotor = $total_akhir_kotor + $total_kotor;
        
        $total_potongan = $total_potongan + $data11['potongan'];
        
        $total_tax = $total_tax + $data11['tax'];
        
        $total_jual = $total_jual + $data11['total'];
        
        $total_tunai = $total_tunai + $data11['tunai'];
        
        $total_sisa = $total_sisa + $data11['sisa'];
        
        $total_kredit = $total_kredit + $data11['kredit']; 
        // untuk perhitungan jumlah total



                    $total_kotor_jual = $data11['total'] + $data11['potongan'];
          
                  echo "<tr>
                  <td>". $data11['no_faktur'] ."</td>
                  <td>". $data11['tanggal'] ."</td>
                  <td>". $data11['jam'] ."</td>
                  <td>". $data11['kategori'] ."</td>
                  <td>". $data11['code_card'] ." - ". $data11['nama_pelanggan'] ."</td>
                  <td>". $data11['user'] ."</td>
                  <td>". $data11['status'] ."</td>
                  <td align='right'>". rp($total_kotor_jual) ."</td>
                  <td align='right'>". rp($data11['potongan']) ."</td>
                  <td align='right'>". rp($data11['tax']) ."</td>
                  <td align='right'>". rp($data11['total']) ."</td>
                  <td align='right'>". rp($data11['tunai']) ."</td>
                  <td align='right'>". rp($data11['sisa']) ."</td>
                  <td align='right'>". rp($data11['kredit']) ."</td>
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
      <td style='color:red' align='right'> <?php echo rp($total_akhir_kotor);?> </td>
      <td style='color:red' align='right'> <?php echo rp($total_potongan);?> </td>
      <td style='color:red' align='right'> <?php echo rp($total_tax);?> </td> 
      <td style='color:red' align='right'> <?php echo rp($total_jual);?> </td>
      <td style='color:red' align='right'> <?php echo rp($total_tunai);?> </td>
      <td style='color:red' align='right'> <?php echo rp($total_sisa);?> </td>
      <td style='color:red' align='right'> <?php echo rp($total_kredit);?> </td> 


            </tbody>

      </table>
      <hr>
</div>
</div>
<br>


 <script>
$(document).ready(function(){
  window.print();
});
</script>

<?php include 'footer.php'; ?>