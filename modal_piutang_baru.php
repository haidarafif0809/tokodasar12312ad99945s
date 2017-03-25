<?php 

include 'sanitasi.php';
include 'db.php';
?>

<div class="table-responsive">
<table id="tableuser" class="table table-bordered">
    <thead> <!-- untuk memberikan nama pada kolom tabel -->
      
       <th> Nomor Faktur </th>
       <th> Kode Pelanggan</th>  
       <th> Tanggal </th>
       <th> Jam </th>
       <th> Tanggal Jatuh Tempo </th>     
       <th> User </th>
       <th> Total </th>
       <th>Sisa Piutang </th>
      
    </thead> <!-- tag penutup tabel -->
    
    <tbody> <!-- tag pembuka tbody, yang digunakan untuk menampilkan data yang ada di database --> 
    <?php

    $kode_pelanggan = $_POST['kode_pelanggan'];
    
    $perintah = $db->query("SELECT no_faktur,kode_pelanggan,tanggal,jam,tanggal_jt,user,total, penjualan.nilai_kredit - IFNULL((SELECT SUM(jumlah_bayar) + SUM(potongan) AS jumlah_bayar FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = penjualan.no_faktur),0) AS sisa_kredit FROM penjualan WHERE kode_pelanggan = '$kode_pelanggan' HAVING sisa_kredit > 0 ");

    //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah))
      {
          $query00 = $db->query("SELECT COUNT(*) AS jumlah_data FROM tbs_pembayaran_piutang WHERE no_faktur_penjualan = '$data1[no_faktur]'");
          $data00 = mysqli_fetch_array($query00);

          //jika sudah ada di tbs maka tidak di munculkan lagi

          if ($data00['jumlah_data'] > 0) {

            
          }

        else{


 
       echo "<tr class='pilih' no-faktur='". $data1['no_faktur'] ."' kredit='". $data1['sisa_kredit'] ."' total='". $data1['total'] ."' tanggal_jt='". $data1['tanggal_jt'] ."' >
      
          <td>". $data1['no_faktur'] ."</td>
          <td>". $data1['kode_pelanggan'] ."</td>     
          <td>". $data1['tanggal'] ."</td>
           <td>". $data1['jam'] ."</td>
          <td>". $data1['tanggal_jt'] ."</td>   
          <td>". $data1['user'] ."</td>
          <td>". rp($data1['total']) ."</td>
          <td>". rp($data1['sisa_kredit']) ."</td>
          </tr>";
        }

      
       }
//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
    ?>
    </tbody> <!--tag penutup tbody-->

  </table> <!-- tag penutup table-->
  </div>
<script type="text/javascript">
  $(function () {
  $("#tableuser").dataTable();
  });
</script>