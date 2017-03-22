<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_pembelian_hutang.xls");

include 'db.php';
include 'sanitasi.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);

//menampilkan seluruh data yang ada pada tabel Pembelian

$perintah = $db->query("SELECT p.id,p.no_faktur,p.total,p.suplier,p.tanggal,p.tanggal_jt,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit,p.nilai_kredit,s.nama,g.nama_gudang FROM pembelian p INNER JOIN suplier s ON p.suplier = s.id INNER JOIN gudang g ON p.kode_gudang = g.kode_gudang WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND kredit != 0 ORDER BY p.id DESC");




$query02 = $db->query("SELECT SUM(kredit) AS total_hutang FROM pembelian WHERE  kredit != 0 AND tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
$cek02 = mysqli_fetch_array($query02);
$total_hutang = $cek02['total_hutang'];

$perintah0 = $db->query("SELECT * FROM detail_pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
$data0 = mysqli_fetch_array($perintah0);



/*$query01 = $db->query("SELECT SUM(potongan) AS total_potongan,sum(total) as total_akhir FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
$cek01 = mysqli_fetch_array($query01);
$total_potongan = $cek01['total_potongan'];
$total_akhir = $cek01['total_akhir'];*/

$query20 = $db->query("SELECT SUM(tax) AS total_tax,sum(tunai) as total_bayar,sum(sisa) as total_sisa,SUM(potongan) AS total_potongan,sum(total) as total_akhir, sum(tunai) as total_tunai  FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
$cek20 = mysqli_fetch_array($query20);
$total_tax = $cek20['total_tax'];
$total_bayar = $cek20['total_bayar'];
$total_tunai = $cek20['total_tunai'];
$total_sisa = $cek20['total_sisa'];
$total_akhir = $cek20['total_akhir'];
$total_potongan = $cek20['total_potongan'];

$query02 = $db->query("SELECT SUM(total) AS total_akhir FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
$cek02 = mysqli_fetch_array($query02);
$total_akhir = $cek02['total_akhir'];


$query30 = $db->query("SELECT SUM(kredit) AS total_kredit FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
$cek30 = mysqli_fetch_array($query30);
$total_kredit = $cek30['total_kredit'];

$query300 = $db->query("SELECT SUM(nilai_kredit) AS total_nilai_kredit FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
$cek300 = mysqli_fetch_array($query300);
$total_nilai_kredit = $cek300['total_nilai_kredit'];

$query15 = $db->query("SELECT SUM(dp.subtotal) AS total_subtotal FROM 
detail_pembelian dp INNER JOIN pembelian p ON dp.no_faktur = p.no_faktur WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND p.kredit != 0");
$cek15 = mysqli_fetch_array($query15);
$t_subtotal = $cek15['total_subtotal'];

$query011 = $db->query("SELECT SUM(dp.jumlah_barang) AS total_barang FROM
detail_pembelian dp INNER JOIN pembelian p ON dp.no_faktur = p.no_faktur WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND p.kredit != 0");
$cek011 = mysqli_fetch_array($query011);
$t_barang = $cek011['total_barang'];

?>

<div class="container">
<center><h3><b>Data Laporan Pembelian Hutang</b></h3></center>
<table id="tableuser" class="table table-bordered">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Suplier </th>
      <th style="background-color: #4CAF50; color: white;"> Jumlah Barang </th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
      <th style="background-color: #4CAF50; color: white;"> Petugas </th>
      <th style="background-color: #4CAF50; color: white;"> Status </th>
      <th style="background-color: #4CAF50; color: white;"> Potongan </th>
      <th style="background-color: #4CAF50; color: white;"> Tax </th>
      <th style="background-color: #4CAF50; color: white;"> Tunai </th>
      <th style="background-color: #4CAF50; color: white;"> Kembalian</th>
      <th style="background-color: #4CAF50; color: white;"> Sisa Kredit </th>
      <th style="background-color: #4CAF50; color: white;"> Nilai Kredit </th>
      <th style="background-color: #4CAF50; color: white;"> Tanggal Jatuh Tempo </th>
      
    </thead>
    
    <tbody>
    <?php

      //menyimpan data sementara yang ada pada $perintah
      while ($data1 = mysqli_fetch_array($perintah))

      {
        $query0 = $db->query("SELECT SUM(jumlah_barang) AS total_barang FROM detail_pembelian WHERE no_faktur = '$data1[no_faktur]'");
                        $cek0 = mysqli_fetch_array($query0);
                        $total_barang = $cek0['total_barang'];
        //menampilkan data
      echo "<tr>
      <td>". $data1['tanggal'] ." ". $data1['jam'] ."</td>
      <td>". $data1['no_faktur'] ."</td>
      <td>". $data1['nama'] ."</td>
      <td>".$total_barang."</td>
      <td>". $data1['total'] ."</td>
      <td>". $data1['user'] ."</td>
      <td>". $data1['status'] ."</td>
      <td>". $data1['potongan'] ."</td>
      <td>". $data1['tax'] ."</td>
      <td>". $data1['tunai'] ."</td>
      <td>". $data1['sisa'] ."</td>
      <td>". $data1['kredit'] ."</td>
      <td>". $data1['nilai_kredit'] ."</td>
      <td>". $data1['tanggal_jt'] ."</td>
      </tr>";
      }

      echo"<td><p style='color:red'><b>Jumlah Total</b></p></td>
      <td></td>
      <td></td>
      <td><p style='color:red'><b>".$t_barang."</b></p></td>
      <td><p style='color:red'><b>".$total_akhir."</b></td>
      <td></td>
      <td></td>
      <td><p style='color:red'><b>".$total_potongan."</b></p></td>
      <td><p style='color:red'><b>".$total_tax."</b></p></td>
      <td><p style='color:red'><b>".$total_tunai."</b></p></td>
      <td><p style='color:red'><b>".$total_sisa."</b></p></td>
      <td><p style='color:red'><b>".$total_kredit."</b></p></td>
      <td><p style='color:red'><b>".$total_nilai_kredit."</b></p></td>
      <td></td>";
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
        
 <!--table>
  <tbody>

    <tr><td width="70%">Jumlah Item</td> <td> :&nbsp; </td> <td> <?php echo $t_barang; ?> </td></tr>
      <tr><td  width="70%">Total Subtotal</td> <td> :&nbsp; Rp.</td> <td> <?php echo $t_subtotal; ?> </td>
      </tr>
      <tr><td  width="70%">Total Potongan</td> <td> :&nbsp; Rp. </td> <td> <?php echo $total_potongan; ?></td></tr>
      <tr><td width="70%">Total Pajak</td> <td> :&nbsp; Rp. </td> <td> <?php echo $total_tax; ?> </td></tr>
      <tr><td  width="70%">Total Akhir</td> <td> :&nbsp; Rp. </td> <td> <?php echo $total_akhir; ?> </td>
      </tr>
      <tr><td  width="70%">Total Sisa Kredit</td> <td> :&nbsp; Rp. </td> <td> <?php echo $total_kredit; ?></td></tr>
      <tr><td  width="70%">Total Nilai Kredit</td> <td> :&nbsp; Rp. </td> <td> <?php echo $total_nilai_kredit; ?></td></tr>
            
  </tbody>
  </table-->


   

     <div class="col-sm-3">

 <b>&nbsp;&nbsp;&nbsp;&nbsp;Petugas<br><br><br><br>( ................... )</b>

    </div>


</div>
        

</div> <!--end container-->
