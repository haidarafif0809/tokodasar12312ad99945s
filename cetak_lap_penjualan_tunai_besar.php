<?php session_start();


include 'header.php';
include 'sanitasi.php';
include 'db.php';


  $no_faktur = stringdoang($_GET['no_faktur']);

    $select_penjualan = $db->query("SELECT p.no_faktur,p.total,p.kode_pelanggan,p.tanggal,p.potongan,p.potongan_persen, pl.nama_pelanggan,pl.wilayah,da.nama_daftar_akun FROM penjualan p INNER JOIN pelanggan pl ON p.kode_pelanggan = pl.kode_pelanggan INNER JOIN daftar_akun da ON p.cara_bayar = da.kode_daftar_akun  WHERE p.no_faktur = '$no_faktur' ORDER BY p.id DESC");
    $data0 = mysqli_fetch_array($select_penjualan);
    
    $select_perusahaan = $db->query("SELECT foto,nama_perusahaan,alamat_perusahaan,no_telp FROM perusahaan ");
    $data_perusahaan = mysqli_fetch_array($select_perusahaan);

    $select_sum = $db->query("SELECT  SUM(subtotal) as sub_total FROM detail_penjualan WHERE no_faktur = '$no_faktur'");
    $data_sum = mysqli_fetch_array($select_sum);
    
    $t_subtotal = $data_sum['sub_total'];

    $potongan_persen = $data0['potongan_persen'];

   

    $jml_dibayar = $t_subtotal - $data0['potongan'];

    $ambil_footer = $db->query("SELECT keterangan, petugas FROM setting_footer_cetak");
    $data_footer = mysqli_fetch_array($ambil_footer);

    $ubah_tanggal = $data0['tanggal'];
    $tanggal = date('d F Y', strtotime($ubah_tanggal));


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

            <tr><td><font class="satu"><br>No Invoice</font></td> <td> <br>:</td> <td><font class="satu"> <br> <?php echo $no_faktur; ?></font></td></tr>
            <tr><td><font class="satu"> Tanggal</td> <td> :&nbsp;&nbsp;</td> <td><?php echo $tanggal; ?></td></tr>
                  

        </tbody>
      </table>

    </div><!-- / <div class="col-sm-6"> -->

    <div class="col-sm-6">
      <table>
        <tbody>
          <tr><td width="5%"><font class="satu"> Alamat</font></td> <td> :&nbsp;&nbsp;</td> <td><?php echo $data0['wilayah'];?></td></tr>
        </tbody>
      </table>
    </div> <!--end col-sm-2-->


   </div> <!--end row-->  



<!--STYLE UNTUK TABLE -->
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
            <th class="table1" style="width: 20%"> <center> Satuan </center> </th>
            <th class="table1" style="width: 10%"> <center> Harga Satuan </center> </th>
            <th class="table1" style="width: 10%"> <center> Harga Jual </center> </th>        
            
        </thead>

        <tbody>
        <?php

        $no_urut = 0;

            $query5 = $db->query("SELECT nama_barang, jumlah_barang, harga, kode_barang, satuan AS id_satuan, asal_satuan, subtotal,satuan.nama AS satuan FROM detail_penjualan INNER JOIN satuan ON detail_penjualan.satuan = satuan.id WHERE no_faktur = '$no_faktur' ");
            //menyimpan data sementara yang ada pada $perintah
            while ($data5 = mysqli_fetch_array($query5))
            {
 $pilih_konversi = $db->query("SELECT $data5[jumlah_barang] / sk.konversi AS jumlah_konversi, sk.harga_pokok / sk.konversi AS harga_konversi, sk.id_satuan, b.satuan,sk.konversi FROM satuan_konversi sk INNER JOIN barang b ON sk.id_produk = b.id  WHERE sk.id_satuan = '$data5[id_satuan]' AND sk.kode_produk = '$data5[kode_barang]'");
                $data_konversi = mysqli_fetch_array($pilih_konversi);

          $query900 = $db->query("SELECT nama FROM satuan WHERE id = '$data_konversi[satuan]'");
           $cek011 = mysqli_fetch_array($query900);


                if ($data_konversi['harga_konversi'] != 0 || $data_konversi['harga_konversi'] != "") 
                {             
                   $jumlah_barang = $data_konversi['jumlah_konversi'];
                   $konver = $jumlah_barang * $data_konversi['konversi'];
                }
                else{
                  $jumlah_barang = $data5['jumlah_barang'];
                  $konver = "";
                }


              $no_urut ++;

            echo "<tr>
            <td class='table1' align='center'>".$no_urut."</td>
            <td class='table1'>". $data5['nama_barang'] ."</td>
            <td class='table1' align='right'>". rp($jumlah_barang) ."</td>";

            if ($data_konversi['harga_konversi'] != 0 || $data_konversi['harga_konversi'] != "") {                
            echo "<td class='table1' align='right'>". $data5['satuan'] ." ( ".$konver." ".$cek011['nama']." ) </td>";
            }
            else{
              echo "<td class='table1' align='right'>". $data5['satuan'] ."</td>";
            }
            echo "<td class='table1' align='right'>". rp($data5['harga']) ."</td>
            <td class='table1' align='right'>". rp($data5['subtotal']) ."</td>
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
        <?php 
         if (strpos($potongan_persen, '+') == true) {
            $subtotal_sebelum_diskon = $t_subtotal;
            $pecahan_diskon_bertingkat = explode("+",$potongan_persen);
            $no_urut_potongan = 0;
            foreach ($pecahan_diskon_bertingkat as $diskon_persen ) { 
                #code... 
            $no_urut_potongan++;
            $diskon_nominal = $subtotal_sebelum_diskon * $diskon_persen /100;
            $subtotal_sebelum_diskon -=  $diskon_nominal;
                echo "<tr>
            <td class='table1'></td>";
            if ($no_urut_potongan == 1 ) {
                # code...
                 echo "<td class='table1'>Dikurangi Potongan Harga </td>";
            
            }
            else {
                 echo "<td class='table1'>Tambahan Diskon </td>";
            }
           
            echo "<td class='table1' align='right'></td>
            <td class='table1'>Disc</td>
            <td class='table1' align='right'>". persen($diskon_persen)."</td>
            <td class='table1' align='right'>". rp($diskon_nominal). "</td>
            </tr>";

            }

         }
         ?>
       

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