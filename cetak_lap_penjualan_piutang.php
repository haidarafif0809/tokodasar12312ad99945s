<?php session_start();


include 'header.php';
include 'sanitasi.php';
include 'db.php';


  $no_faktur = $_GET['no_faktur'];

    $select_penjualan = $db->query("SELECT p.id,p.no_faktur,p.total,p.kode_pelanggan,p.keterangan,p.cara_bayar,p.tanggal,p.tanggal_jt,p.jam,p.user,p.sales,p.kode_meja,p.status,p.potongan,p.tax,p.sisa,p.kredit,p.kode_gudang,p.tunai,pl.nama_pelanggan,pl.wilayah,dp.satuan,dp.jumlah_barang,dp.subtotal,dp.nama_barang,dp.harga, da.nama_daftar_akun, s.nama AS nama_satuan FROM penjualan p INNER JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur INNER JOIN pelanggan pl ON p.kode_pelanggan = pl.kode_pelanggan INNER JOIN daftar_akun da ON p.cara_bayar = da.kode_daftar_akun INNER JOIN satuan s ON dp.satuan = s.id WHERE p.no_faktur = '$no_faktur' ORDER BY p.id DESC");
     $data0 = mysqli_fetch_array($select_penjualan);

     $potongan = $data0['potongan'];


    $select_perusahaan = $db->query("SELECT * FROM perusahaan ");
    $data_perusahaan = mysqli_fetch_array($select_perusahaan);

    $select_sum = $db->query("SELECT SUM(jumlah_barang) as total_item, SUM(subtotal) as sub_total FROM detail_penjualan WHERE no_faktur = '$no_faktur'");
    $data_sum = mysqli_fetch_array($select_sum);
    $total_item = $data_sum['total_item'];
    $t_subtotal = $data_sum['sub_total'];

    $potongan_persen = $potongan / $t_subtotal * 100;

    $jml_dibayar = $t_subtotal - $data0['potongan'] - $data0['tunai'];

    $ambil_footer = $db->query("SELECT keterangan, petugas FROM setting_footer_cetak");
    $data_footer = mysqli_fetch_array($ambil_footer);

    $ubah_tanggal = $data0['tanggal'];
    $tanggal = date('d F Y', strtotime($ubah_tanggal));

    $ubah_tanggal =$data0['tanggal_jt'];
    $tanggal_jt = date('d F Y', strtotime($ubah_tanggal));


 ?>
<style type="text/css">
/*unTUK mengatur ukuran font*/
   .satu {
   font-size: 15px;
   font: verdana;
   }
</style>


<div class="container">
    
    <div class="row"><!--row1-->
        <div class="col-sm-2">
                <img src='save_picture/<?php echo $data_perusahaan['foto']; ?>' class='img-rounded' alt='Cinque Terre' width='80' height='80`'> 
        </div><!--penutup colsm2-->

        <div class="col-sm-8">
            <center> 
              <h4> <b> <?php echo $data_perusahaan['nama_perusahaan']; ?> </b> </h4> 
              <p> <?php echo $data_perusahaan['alamat_perusahaan']; ?><br>
                  No.Telp:<?php echo $data_perusahaan['no_telp']; ?> </p> 
            </center>
                 
        </div><!--penutup colsm5-->
        
    </div><!--penutup row1-->



    <center> <h4> <b> Faktur Penjualan <hr></b> </h4> </center>



  <div class="row">
    <div class="col-sm-6">
        

       <table>
        <tbody>
            
            <tr><td><font class="satu">  Kepada Yth</td> <td>  :&nbsp;&nbsp;</td> <td>  <?php echo $data0['nama_pelanggan']; ?> </td></tr> 

            <tr><td><font class="satu"><br>No Invoice</font></td> <td> <br>:</td> <td><font class="satu"> <br> <?php echo $no_faktur; ?></font> </tr>
            <tr><td><font class="satu"> Tanggal</td> <td> :&nbsp;&nbsp;</td> <td><?php echo $tanggal; ?></font> </td></tr>
                  

        </tbody>
      </table>


    </div>


    <div class="col-sm-6">
           <table>
            <tbody>

                 <tr> <td width="5%"><font class="satu"> Alamat</td> <td> :&nbsp;&nbsp;</td> </font> </td><td><?php echo $data0['wilayah'];?> </td></tr>
                 <tr><td width="19%"><font class="satu"> Tanggal Jatuh Tempo</td> <td> :&nbsp;&nbsp;</td> <td><?php echo $tanggal_jt;?></font> </td></tr>

            </tbody>
          </table>

    </div> <!--end col-sm-2-->

   </div> <!--end row-->  


<!--UNTUK STYLE TABEL -->

<style type="text/css">
  th,td{
    padding: 1px;
  }

.table1, .th, .td {
    border: 1px solid black;
    font-size: 15px;
    font: verdana;
}


</style>

<table id="tableuser" class="table1">
        <thead>
            <th class="table1" style="width: 5%"> <center> No. </center> </th>
            <th class="table1" style="width: 65%"> <center> Nama Barang </center> </th>
            <th class="table1" style="width: 5%"> <center> Jumlah </center> </th>
            <th class="table1" style="width: 5%"> <center> Satuan </center> </th>
            <th class="table1" style="width: 10%"> <center> Harga Satuan </center> </th>
            <th class="table1" style="width: 10%"> <center> Harga Jual </center> </th>
        
            
        </thead>
        <tbody>
        <?php

        $no_urut = 0;

            $query5 = $db->query("SELECT * FROM detail_penjualan WHERE no_faktur = '$no_faktur' ");
            //menyimpan data sementara yang ada pada $perintah
            while ($data5 = mysqli_fetch_array($query5))
            {

              $no_urut ++;

            echo "<tr>
            <td class='table1' align='center'>".$no_urut."</td>
            <td class='table1'>". $data5['nama_barang'] ."</td>
            <td class='table1' align='center'>". rp($data5['jumlah_barang']) ."</td>
            <td class='table1' align='center'>". $data0['nama_satuan'] ."</td>
            <td class='table1' align='center'>". rp($data5['harga']) ."</td>
            <td class='table1' align='center'>". rp($data5['subtotal']) ."</td>
            <tr>";

            }

//Untuk Memutuskan Koneksi Ke Database

mysqli_close($db); 

        ?>

        <tr>
            <td class='table1'></td>
            <td class='table1'></td>
            <td class='table1' align='right'></td>
            <td class='table1'></td>
            <td class='table1' align='right'></td>
            <td class='table1' align='right'></td>
        </tr>

        <tr>
            <td class='table1'></td>
            <td class='table1'>Jumlah Harga Jual</td>
            <td class='table1' align='right'></td>
            <td class='table1'></td>
            <td class='table1' align='right'></td>
            <td class='table1' align='right'><?php echo rp($t_subtotal); ?></td>
        </tr>

        <tr>
            <td class='table1'></td>
            <td class='table1'>Dikurangi Potongan Harga</td>
            <td class='table1' align='right'></td>
            <td class='table1'>Disc</td>
            <td class='table1' align='right'><?php echo persen(round($potongan_persen)); ?></td>
            <td class='table1' align='right'><?php echo rp($data0['potongan']); ?></td>
        </tr>

        <tr>
            <td class='table1'></td>
            <td class='table1'>Jumlah Potongan Harga</td>
            <td class='table1' align='right'></td>
            <td class='table1'></td>
            <td class='table1' align='right'></td>
            <td class='table1' align='right'><?php echo rp($data0['potongan']); ?></td>
        </tr>

        <tr>
            <td class='table1'></td>
            <td class='table1'>Jumlah Pembayaran</td>
            <td class='table1' align='right'></td>
            <td class='table1'></td>
            <td class='table1' align='right'></td>
            <td class='table1' align='right'><b><?php echo rp($data0['tunai']); ?></b></td>
        </tr>

        <tr>
            <td class='table1'></td>
            <td class='table1'>Jumlah Yang Harus Dibayar</td>
            <td class='table1' align='right'></td>
            <td class='table1'></td>
            <td class='table1' align='right'></td>
            <td class='table1' align='right'><b><?php echo rp($jml_dibayar); ?></b></td>
        </tr>


      </tbody>
    </table>


 <div class="col-sm-9">
   <font class="satu">
   <?php echo $data_footer['keterangan'] ?>
   </font>
 </div>

 <div class="col-sm-3">
    
    <font class="satu"><b> <center>Hormat Kami,</center> <br><br><br> <font class="satu"> <center>(<?php echo $data_footer['petugas']; ?>)</center></font></b></font>

</div>




</div> <!--/container-->


 <script>
$(document).ready(function(){
  window.print();
});
</script>



<?php include 'footer.php'; ?>