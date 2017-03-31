<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laporan_penjualan_detail.xls");

include 'db.php';
include 'sanitasi.php';


$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);
$kategori = stringdoang($_GET['kategori']);


//menampilkan seluruh data yang ada pada tabel penjualan

if ($kategori == 'semua')
{
  $query02 = $db->query("SELECT SUM(dp.jumlah_barang) as tot_jumlah ,SUM(dp.harga * dp.jumlah_barang) as tot_subtotal ,SUM(dp.potongan) as tot_potongan ,SUM(dp.tax) as tot_tax, SUM(dp.subtotal + dp.tax) as tot_akhir FROM detail_penjualan dp WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' ");

}
else
{

    $query02 = $db->query("SELECT SUM(dp.jumlah_barang) as tot_jumlah ,SUM(dp.harga * dp.jumlah_barang) as tot_subtotal ,SUM(dp.potongan) as tot_potongan ,SUM(dp.tax) as tot_tax, SUM(dp.subtotal + dp.tax) as tot_akhir FROM detail_penjualan dp  LEFT JOIN barang br ON dp.kode_barang = br.kode_barang WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal'  AND br.kategori = '$kategori'");
}

$cek02 = mysqli_fetch_array($query02);
$tot_jumlah = $cek02['tot_jumlah'];
$tot_subtotal = $cek02['tot_subtotal'];
$tot_potongan = $cek02['tot_potongan'];
$tot_tax = $cek02['tot_tax'];
$tot_akhir = $cek02['tot_akhir'];

?>

<div class="container">
                 <h3> <b> LAPORAN PENJUALAN DETAIL </b></h3>
<table id="table_lap_penjualan_detail" class="table table-bordered table-sm">
          <thead>
          <th > Nomor Faktur </th>
          <th > Kode Barang </th>
          <th > Nama Barang </th>
          <th > Jumlah Barang </th>
          <th > Satuan </th>
          <th > Harga </th>
          <th > Subtotal </th>
          <th > Potongan </th>
          <th > Tax </th>
          <th > Total Akhir</th>


          </thead>
          
          <tbody>
           <?php

if ($kategori == 'semua')
{
  $perintah1 = $db->query("SELECT dp.tanggal,s.nama,dp.no_faktur,dp.kode_barang,dp.nama_barang,dp.jumlah_barang,dp.satuan,dp.harga,dp.subtotal,dp.potongan,dp.tax,dp.hpp,dp.sisa FROM detail_penjualan dp LEFT JOIN satuan s ON dp.satuan = s.id WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' ORDER BY dp.no_faktur DESC ");
}
else
{

  $perintah1 = $db->query("SELECT dp.tanggal,s.nama,dp.no_faktur,dp.kode_barang,dp.nama_barang,dp.jumlah_barang,dp.satuan,dp.harga,dp.subtotal,dp.potongan,dp.tax,dp.hpp,dp.sisa FROM detail_penjualan dp LEFT JOIN satuan s ON dp.satuan = s.id LEFT JOIN barang br ON dp.kode_barang = br.kode_barang WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal'  AND br.kategori = '$kategori' ORDER BY dp.no_faktur DESC ");

}
              while( $row=mysqli_fetch_array($perintah1) ) {  // preparing an array

    $pilih_konversi = $db->query("SELECT $row[jumlah_barang] / sk.konversi AS jumlah_konversi, sk.harga_pokok / sk.konversi AS harga_konversi, sk.id_satuan, b.satuan,sk.konversi FROM satuan_konversi sk INNER JOIN barang b ON sk.id_produk = b.id  WHERE sk.id_satuan = '$row[satuan]' AND sk.kode_produk = '$row[kode_barang]'");
                $data_konversi = mysqli_fetch_array($pilih_konversi);

          $query900 = $db->query("SELECT nama FROM satuan WHERE id = '$data_konversi[satuan]'");
           $cek011 = mysqli_fetch_array($query900);


                if ($data_konversi['harga_konversi'] != 0 || $data_konversi['harga_konversi'] != "") 
                {             
                   $jumlah_barang = $data_konversi['jumlah_konversi'];
                   $konver = $jumlah_barang * $data_konversi['konversi'];
                }
                else{
                  $jumlah_barang = $row['jumlah_barang'];
                  $konver = "";
                }


          $subtotal = $row['harga'] * $row['jumlah_barang'];

   echo "<tr>       
    <td>".$row['no_faktur']."</td>
    <td>". $row['kode_barang']."</td>
    <td>". $row['nama_barang']."</td>
    <td align='right'>".$jumlah_barang."</td>";
          
if ($data_konversi['harga_konversi'] != 0 || $data_konversi['harga_konversi'] != "") 
      {  
          echo "<td>".$row['nama']." ( ".$konver." ".$cek011['nama']." ) </td>";
      }
else
     {
          echo "<td>".$row['nama']."</td>";
     }
  echo "
  <td>". $row['harga']."</td>
  <td>". $subtotal."</td>
  <td>". $row['potongan']."</td>
  <td>". $row['tax']."</td>
  <td>". $row['subtotal'] + $row['tax']."</td>
  </tr>";

      }

echo "<tr>"; 
      echo " <td style='color:red'> TOTAL </td>";
      echo " <td style='color:red'> - </td>";
      echo " <td style='color:red'> - </td>";
      echo " <td style='color:red'> ".$tot_jumlah." </td>";
      echo " <td style='color:red'> - </td>";
      echo " <td style='color:red'> - </td>";
      echo " <td style='color:red'> ".$tot_subtotal." </td>";
      echo " <td style='color:red'> ".$tot_potongan." </td>";
      echo " <td style='color:red'> ".$tot_tax." </td>";
      echo " <td style='color:red'> ".$tot_akhir." </td>


      </tr>"; 

                          //Untuk Memutuskan Koneksi Ke Database
                          
                          mysqli_close($db); 
        
        
            ?>
            </tbody>

      </table>

</div>
</div>

     </div>
