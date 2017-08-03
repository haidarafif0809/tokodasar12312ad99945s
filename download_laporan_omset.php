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

if ($kode_pelanggan == 'semua' AND $sales == 'semua') {
  $sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
  $data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}
else if ($kode_pelanggan == 'semua' AND $sales != 'semua') {
  $sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND sales = '$sales'");
  $data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}
else if ($kode_pelanggan != 'semua' AND $sales == 'semua') {
  $sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kode_pelanggan = '$kode_pelanggan'");
  $data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}
else{
  $sum_omset_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND sales = '$sales' AND kode_pelanggan = '$kode_pelanggan'");
  $data_sum_omset = mysqli_fetch_array($sum_omset_penjualan);
}

 ?>


 <div class="container">
 <center>
 <div class="row"><!--row1-->

        <div class="col-sm-10">
        <h3> <b> LAPORAN OMSET </b></h3> <hr>
        </div>

        <div class="col-sm-6">
                 
                 <h4> <b> <?php echo $data1['nama_perusahaan']; ?> </b> </h4> 
                 <p> <?php echo $data1['alamat_perusahaan']; ?> </p> 
                 <p> No.Telp:<?php echo $data1['no_telp']; ?> </p> 
                 
        </div><!--penutup colsm4-->

        <div class="col-sm-4">             
          <table>
            <tbody>

                <tr><td  width="20%">PERIODE</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo $tanggal_dari; ?> s/d <?php echo $tanggal_sampai; ?></td></tr>
                      
            </tbody>
          </table>           
                 
        </div><!--penutup colsm4-->


        
    </div><!--penutup row1-->


 <table id="tableuser" class="table table-bordered table-sm">
            <thead>
                <th style="background-color: #4CAF50; color: white;"> Tanggal</th>
                <th style="background-color: #4CAF50; color: white;"> No. Faktur</th>
                <th style="background-color: #4CAF50; color: white;"> Nama Pelanggan</th>
                <th style="background-color: #4CAF50; color: white;"> Sales</th>
                <th style="background-color: #4CAF50; color: white;"> Total Omset </th>
                <th style="background-color: #4CAF50; color: white;"> Terbayar  </th>
                                                     
            </thead>
            
            <tbody>
            <?php          
        

        if ($kode_pelanggan == 'semua' AND $sales == 'semua') {
          $select = $db->query("SELECT p.no_faktur, p.tanggal, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' ORDER BY pel.id DESC");
        }
        else if ($kode_pelanggan == 'semua' AND $sales != 'semua') {
          $select = $db->query("SELECT p.no_faktur, p.tanggal, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.sales = '$sales' ORDER BY pel.id DESC");
        }
        else if ($kode_pelanggan != 'semua' AND $sales == 'semua') {
          $select = $db->query("SELECT p.no_faktur, p.tanggal, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.kode_pelanggan = '$kode_pelanggan' ORDER BY pel.id DESC");
        }
        else{
          $select = $db->query("SELECT p.no_faktur, p.tanggal, p.kode_pelanggan, pel.nama_pelanggan, p.total, p.tunai, p.sales, p.sisa FROM penjualan p INNER JOIN pelanggan pel ON p.kode_pelanggan = pel.kode_pelanggan WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND p.kode_pelanggan = '$kode_pelanggan' AND p.sales = '$sales' ORDER BY pel.id DESC");
        }

          while ($data = mysqli_fetch_array($select))
          {

            $sum_penjualan = $db->query("SELECT SUM(total) AS total_penjualan, SUM(total) - SUM(kredit) AS total_kas FROM penjualan WHERE no_faktur = '$data[no_faktur]' AND kode_pelanggan = '$data[kode_pelanggan]'");
            $data_sum = mysqli_fetch_array($sum_penjualan);

          echo "<tr>

            <td>". $data['tanggal'] ."</td>
            <td>". $data['no_faktur'] ."</td>
            <td>". $data['nama_pelanggan'] ."</td>
            <td>". $data['sales'] ."</td>
            <td>". rp($data_sum['total_penjualan']) ."</td>
            <td>". rp($data_sum['total_kas']) ."</td>
          

          </tr>";
          }

          echo "<tr>

            <td style='color:red'>TOTAL</td>
            <td style='color:red'></td>
            <td style='color:red'></td>
            <td style='color:red'></td>
            <td style='color:red'>". rp($data_sum_omset['total_penjualan']) ."</td>
            <td style='color:red'>". rp($data_sum_omset['total_kas']) ."</td>
          

          </tr>";


                  //Untuk Memutuskan Koneksi Ke Database
                  
                  mysqli_close($db); 
        
        
          ?>
          
            </tbody>

      </table>
</center>
</div>