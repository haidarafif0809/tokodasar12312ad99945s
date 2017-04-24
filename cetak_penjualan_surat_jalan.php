<?php session_start();


include 'header.php';
include 'sanitasi.php';
include 'db.php';


  $no_faktur = stringdoang($_GET['no_faktur']);

    $query0 = $db->query("SELECT s.nama,p.id,p.no_faktur,p.total,p.kode_pelanggan,p.keterangan,p.cara_bayar,p.tanggal,p.tanggal_jt,p.jam,p.user,p.sales,p.kode_meja,p.status,p.potongan,p.tax,p.sisa,p.kredit,p.kode_gudang,p.tunai,pl.nama_pelanggan,pl.wilayah,dp.satuan,dp.jumlah_barang,dp.subtotal,dp.nama_barang,dp.harga, dp.asal_satuan, dp.kode_barang, da.nama_daftar_akun FROM penjualan p INNER JOIN detail_penjualan dp ON p.no_faktur = dp.no_faktur INNER JOIN pelanggan pl ON p.kode_pelanggan = pl.kode_pelanggan INNER JOIN daftar_akun da ON p.cara_bayar = da.kode_daftar_akun INNER JOIN satuan s ON dp.satuan = s.id WHERE p.no_faktur = '$no_faktur' ORDER BY p.id DESC");
     $data_inner = mysqli_fetch_array($query0);



    $query1 = $db->query("SELECT * FROM perusahaan ");
    $data1 = mysqli_fetch_array($query1);

    $query3 = $db->query("SELECT SUM(jumlah_barang) as total_item, SUM(subtotal) as t_subtotal FROM detail_penjualan WHERE no_faktur = '$no_faktur'");
    $data3 = mysqli_fetch_array($query3);
    $total_item = $data3['total_item'];
    $t_subtotal = $data3['t_subtotal'];

    $ubah_tanggal = $data_inner['tanggal'];
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
                <img src='save_picture/<?php echo $data1['foto']; ?>' class='img-rounded' alt='Cinque Terre' width='80' height='80`'> 
        </div><!--penutup colsm2-->

        <div class="col-sm-8">
                 <center> <h4> <b> <?php echo $data1['nama_perusahaan']; ?> </b> </h4> 
                 <p> <?php echo $data1['alamat_perusahaan']; ?><br>
                  No.Telp:<?php echo $data1['no_telp']; ?> </p> </center>
                 
        </div><!--penutup colsm5-->
        
    </div><!--penutup row1-->



    <center> <h4> <b> SURAT JALAN </b> </h4> </center><hr>

<div class="row">
<div class="col-sm-8">
 <table>
  <tbody>

      <tr><td><font class="satu">No Invoice</font></td> <td>:</td> <td><font class="satu"><?php echo $data_inner['no_faktur']; ?></font> </tr>
      <tr><td ><font class="satu">Dikirim ke</font></td> <td>:</td> <td><font class="satu"> <?php echo $data_inner['wilayah']; ?> </font></td></tr>      
      <tr><td><font class="satu"> <br> Kepada Yth</td> <td> <br> :&nbsp;&nbsp;</td> <td> <br> <?php echo $data_inner['nama_pelanggan']; ?> </td></tr> 
      

  </tbody>
</table>
</div>

<div class="col-sm-4">
 <table>
  <tbody>
    <tr><td><font class="satu"> Tanggal</td> <td> :&nbsp;&nbsp;</td> <td><?php echo $tanggal; ?></font> </td></tr>
    <tr><td><font class="satu"> Tanggal Terima</td> <td> :&nbsp;&nbsp;</td> <td></font> </td></tr>
  </tbody>
</table>
</div> <!--end col-sm-2-->

  
</div> <!--end row-->  

<table>
  <tbody>
    <tr><td><font class="satu">Agar Diterima Barang Dengan Spesifikasi Dibawah Ini :</font></td></tr>
  </tbody>
</table>


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


<br>
<table id="tableuser" class="table1">
        <thead>
            <th class="table1" style="width: 5%"> <center> No. </center> </th>
            <th class="table1" style="width: 65%"> <center> Nama Barang </center> </th>
            <th class="table1" style="width: 5%"> <center> Jumlah </center> </th>
            <th class="table1" style="width: 10%"> <center> Satuan </center> </th>
            <th class="table1" style="width: 10%"> <center> Keterangan </center> </th>
                    
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
            echo "<td class='table1'> </td>
            <tr>";

            }

//Untuk Memutuskan Koneksi Ke Database

mysqli_close($db); 

        ?>
        </tbody>

    </table>


</style>

<div class="col-sm-6">
  <table>
    <tbody>
      <tr> <td><center> <b> Kepala Gudang </b></center> <br> <br> <br> <br></td>  </tr>
      <tr> <td><center>..............................................</center> </td></tr>
    </tbody>
  </table>
</div>

<div class="col-sm-3">
  <table>
    <tbody>
      <tr> <td><center> <b> Sopir </b></center> <br> <br> <br> <br></td>  </tr>
      <tr> <td> <center>......................</center> </td></tr>
    </tbody>
  </table>
</div>

<div class="col-sm-3">
  <table>
    <tbody>
      <tr> <td><center> <b> Penerima </b></center> <br> <br> <br> <br></td>  </tr>
      <tr> <td><center>...............................</center> </td></tr>
    </tbody>
  </table>
</div>





</div> <!--/container-->


 <script>
$(document).ready(function(){
  window.print();
});
</script>



<?php include 'footer.php'; ?>