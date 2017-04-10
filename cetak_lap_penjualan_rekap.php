<?php 
include 'header.php';
include 'sanitasi.php';
include 'db.php';


$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);
$kategori = stringdoang($_GET['kategori']);

    $query1 = $db->query("SELECT foto,nama_perusahaan,alamat_perusahaan,no_telp FROM perusahaan ");
    $data1 = mysqli_fetch_array($query1);

    if ($kategori == "Semua Kategori") {
      # JIKA SEMUA KATEGORI
      
      $query_sum_jumlah_nilai_detail_jual = $db->query("SELECT SUM(subtotal) AS total_subtotal , SUM(jumlah_barang) AS total_barang FROM detail_penjualan 
      WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' ");

      $perintah_tampil = $db->query("SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit 
      FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang
      WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' GROUP BY p.no_faktur ");

    }
    else
    {

      $query_sum_jumlah_nilai_detail_jual = $db->query("SELECT SUM(dp.subtotal) AS total_subtotal , SUM(dp.jumlah_barang) AS total_barang FROM detail_penjualan dp 
      LEFT JOIN barang b ON dp.kode_barang = b.kode_barang  WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' 
      AND b.kategori = '$kategori'  ");
      
      $perintah_tampil = $db->query("SELECT b.kategori,pel.nama_pelanggan,pel.kode_pelanggan AS code_card,p.tunai,p.id,p.tanggal,p.no_faktur,p.kode_pelanggan,p.total,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit 
      FROM penjualan p LEFT JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan LEFT JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur LEFT JOIN barang b ON dp.kode_barang = b.kode_barang
      WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND b.kategori = '$kategori' GROUP BY p.no_faktur ");
    }

    $data_sum_jumlah_nilai_detail_jual = mysqli_fetch_array($query_sum_jumlah_nilai_detail_jual);

      $total_potongan = 0;
      $total_tax = 0;
      $total_akhir = 0;
      $total_kredit = 0;

    $t_subtotal = $data_sum_jumlah_nilai_detail_jual['total_subtotal'];
    $t_barang = $data_sum_jumlah_nilai_detail_jual['total_barang'];

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
                  <th> Nomor Faktur </th>                  
                  <th> Tanggal </th>
                  <th> Kategori </th>
                  <th> Kode Pelanggan</th>
                  <th> Jumlah Barang </th>
                  <th> Subtotal </th>
                  <th> Potongan </th>
                  <th> Pajak </th>
                  <th> Total Akhir </th>
                  <th> Bayar Tunai </th>
                  <th> Bayar Kredit </th>
                                    
            </thead>
            
            <tbody>
            <?php

                  while ($data11 = mysqli_fetch_array($perintah_tampil))

                  {
                        //menampilkan data
                        $query0 = $db->query("SELECT SUM(jumlah_barang) AS total_barang FROM detail_penjualan WHERE no_faktur = '$data11[no_faktur]'");
                        $cek0 = mysqli_fetch_array($query0);
                        $total_barang = $cek0['total_barang'];
                        
                        
                        $query10 = $db->query("SELECT SUM(subtotal) AS total_subtotal FROM detail_penjualan WHERE no_faktur = '$data11[no_faktur]'");
                        $cek10 = mysqli_fetch_array($query10);
                        $total_subtotal = $cek10['total_subtotal'];

                        // hitung total kredit
                        $total_kredit = $total_kredit + $data11['kredit'];
                        #hitung total potongan
                        $total_potongan =$total_potongan + $data11['potongan'];

                        #hitung total pajak
                        $total_tax = $total_tax + $data11['tax'];

                        #hitung total akhir
                        $total_akhir = $total_akhir + $data11['total'];
                        
                  echo "<tr>
                  <td>". $data11['no_faktur'] ."</td>
                  <td>". $data11['tanggal'] ."</td>
                  <td>". $data11['kategori'] ."</td>
                  <td>". $data11['kode_pelanggan'] ." ". $data11['nama_pelanggan'] ."</td>
                  <td align='right'>". $total_barang ."</td>
                  <td align='right'>". rp($total_subtotal) ."</td>
                  <td align='right'>". rp($data11['potongan']) ."</td>
                  <td align='right'>". rp($data11['tax']) ."</td>
                  <td align='right'>". rp($data11['total']) ."</td>
                  <td align='right'>". rp($data11['tunai']) ."</td>
                  <td align='right'>". rp($data11['kredit']) ."</td>
                  </tr>";


                  }

                          //Untuk Memutuskan Koneksi Ke Database
                          
                          mysqli_close($db); 
        
        
            ?>
            </tbody>

      </table>
      <hr>
</div>
</div>
<br>

<div class="col-sm-6">
</div>


<div class="col-sm-2">
<h4><b>Total Keseluruhan :</b></h4>
</div>


<div class="col-sm-2">
        
 <table>
  <tbody>

      <tr><td width="70%">Jumlah Item</td> <td> :&nbsp; </td> <td> <?php echo $t_barang; ?> </td></tr>
      <tr><td  width="70%">Total Subtotal</td> <td> :&nbsp; </td> <td> <?php echo rp($t_subtotal); ?> </td>
      </tr>
      <tr><td  width="70%">Total Potongan</td> <td> :&nbsp; </td> <td> <?php echo rp($total_potongan); ?></td></tr>
      <tr><td width="70%">Total Pajak</td> <td> :&nbsp; </td> <td> <?php echo rp($total_tax); ?> </td></tr>
      <tr><td  width="70%">Total Akhir</td> <td> :&nbsp; </td> <td> <?php echo rp($total_akhir); ?> </td>
      </tr>
      <tr><td  width="70%">Total Kredit</td> <td> :&nbsp; </td> <td> <?php echo rp($total_kredit); ?></td></tr>
            
  </tbody>
  </table>


     </div>

 <script>
$(document).ready(function(){
  window.print();
});
</script>

<?php include 'footer.php'; ?>