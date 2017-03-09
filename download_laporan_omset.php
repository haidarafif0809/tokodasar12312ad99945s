<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_omset.xls");

include 'header.php';
include 'sanitasi.php';
include 'db.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);
$kode_pelanggan = stringdoang($_GET['kode_pelanggan']);
$sales = stringdoang($_GET['sales']);

$tanggal_dari = date('d F Y', strtotime($dari_tanggal));
$tanggal_sampai = date('d F Y', strtotime($sampai_tanggal));

$sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE sales = '$sales' AND kode_pelanggan = '$kode_pelanggan'");
$data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
 ?>


<div class="container">
 <div class="row"><!--row1-->
  <h3> <b> LAPORAN OMSET </b></h3>
                 
                 <h4> <b> <?php echo $data1['nama_perusahaan']; ?> </b> </h4> 
                 <p> <?php echo $data1['alamat_perusahaan']; ?> </p> 
                 <p> No.Telp:<?php echo $data1['no_telp']; ?> </p> 
                 <p> PERIODE : <?php echo $tanggal_dari; ?> s/d <?php echo $tanggal_sampai; ?> </p>

</div><!--penutup row1-->


 <table id="tableuser" class="table table-bordered table-sm">
            <thead>
                <th style="background-color: #4CAF50; color: white;"> Tanggal</th>
                <th style="background-color: #4CAF50; color: white;"> No. Faktur</th>
                <th style="background-color: #4CAF50; color: white;"> Nama Pelanggan</th>
                <th style="background-color: #4CAF50; color: white;"> Sales</th>
                <th style="background-color: #4CAF50; color: white;"> Total Penjualan </th>
                <th style="background-color: #4CAF50; color: white;"> Total Omset </th>
                                                     
            </thead>
            
            <tbody>
            <?php
          
        $select = $db->query("SELECT p.no_faktur, p.tanggal, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.kode_pelanggan = '$kode_pelanggan' AND p.sales = '$sales'");

          while ($data = mysqli_fetch_array($select))
          {

            $sum_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE no_faktur = '$data[no_faktur]' AND kode_pelanggan = '$data[kode_pelanggan]'");
            $data_sum = mysqli_fetch_array($sum_penjualan);

          echo "<tr>

            <td>". $data['tanggal'] ."</td>
            <td>". $data['no_faktur'] ."</td>
            <td>". $data['nama_pelanggan'] ."</td>
            <td>". $data['sales'] ."</td>
            <td>". $data_sum['total_penjualan'] ."</td>
            <td>". $data_sum['total_kas'] ."</td>
          

          </tr>";
          }


                  //Untuk Memutuskan Koneksi Ke Database
                  
                  mysqli_close($db); 
        
        
          ?>
          
            </tbody>

      </table>
      <br>



 <div class="col-sm-5">
</div>


<div class="col-sm-3">
<h4><b>Total Keseluruhan :</b></h4>
</div>


<div class="col-sm-4">
        
  <table>
  <tbody>

      <tr><td width="50%" style="font-size: 130%">Total Penjualan</td> <td> : </td> <td style="font-size: 130%"> Rp. <?php echo rp($data_sum_omset['total_penjualan']);?> </tr>
      <tr><td  width="50%" style="font-size: 130%">Total Omset</td> <td> : </td> <td style="font-size: 130%">  Rp. <?php echo rp($data_sum_omset['total_kas']);?> </td></tr>   
            
  </tbody>
  </table>

</div>

</div>