<?php 
include 'header.php';
include 'sanitasi.php';
include 'db.php';


$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);

    $query1 = $db->query("SELECT * FROM perusahaan ");
    $data1 = mysqli_fetch_array($query1);

//menampilkan seluruh data yang ada pada tabel pembelian
//$perintah = $db->query("SELECT * FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");


//menampilkan seluruh data yang ada pada tabel pembelian
//$perintah0 = $db->query("SELECT * FROM detail_pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");
//$data0 = mysqli_fetch_array($perintah0);



//$query01 = $db->query("SELECT SUM(potongan) AS total_potongan FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
//$cek01 = mysqli_fetch_array($query01);
//$total_potongan = $cek01['total_potongan'];

$query20 = $db->query("SELECT SUM(tax) AS total_tax,sum(tunai) as total_bayar,sum(sisa) as total_sisa,SUM(potongan) AS total_potongan,sum(total) as total_akhir, sum(tunai) as total_tunai,SUM(kredit) AS total_kredit  FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
$cek20 = mysqli_fetch_array($query20);
$total_tax = $cek20['total_tax'];
$total_bayar = $cek20['total_bayar'];
$total_tunai = $cek20['total_tunai'];
$total_sisa = $cek20['total_sisa'];
$total_akhir = $cek20['total_akhir'];
$total_potongan = $cek20['total_potongan'];
$total_kredit = $cek20['total_kredit'];
//$query02 = $db->query("SELECT SUM(total) AS total_akhir FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
//$cek02 = mysqli_fetch_array($query02);
//$total_akhir = $cek02['total_akhir'];


//$query30 = $db->query("SELECT SUM(kredit) AS total_kredit FROM pembelian WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal' AND kredit != 0");
//$cek30 = mysqli_fetch_array($query30);
//$total_kredit = $cek30['total_kredit'];

//$query15 = $db->query("SELECT SUM(dp.subtotal) AS total_subtotal FROM detail_pembelian dp INNER JOIN pembelian p ON dp.no_faktur = p.no_faktur WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND p.kredit != 0");
//$cek15 = mysqli_fetch_array($query15);
//$t_subtotal = $cek15['total_subtotal'];

//$query011 = $db->query("SELECT SUM(dp.jumlah_barang) AS total_barang FROM detail_pembelian dp INNER JOIN pembelian p ON dp.no_faktur = p.no_faktur WHERE dp.tanggal >= '$dari_tanggal' AND dp.tanggal <= '$sampai_tanggal' AND p.kredit != 0");
//$cek011 = mysqli_fetch_array($query011);
//$t_barang = $cek011['total_barang'];





 ?>
<div class="container">
<center><h3> <b> LAPORAN HUTANG BEREDAR</b></h3><hr></center>
 <div class="row"><!--row1-->
        <div class="col-sm-3">
        <br><br>
                <img src='save_picture/<?php echo $data1['foto']; ?>' class='img-rounded' alt='Cinque Terre' width='160' height='140`'> 
        </div><!--penutup colsm2-->

        <div class="col-sm-6">
                 <h4> <b> <?php echo $data1['nama_perusahaan']; ?> </b> </h4> 
                 <p> <?php echo $data1['alamat_perusahaan']; ?> </p> 
                 <p> No.Telp:<?php echo $data1['no_telp']; ?> </p> 
                 
        </div><!--penutup colsm4-->

        <div class="col-sm-3">
         <br><br>                 
<table>
  <tbody>

      <tr><td  width="20%">PERIODE</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo tanggal($dari_tanggal); ?> s/d <?php echo tanggal($sampai_tanggal); ?></td>
      </tr>
            
  </tbody>
</table>           
                 
        </div><!--penutup colsm4-->


        
    </div><!--penutup row1-->
    <br>

 <table id="tableuser" class="table table-bordered table-sm">
            <thead>
                  <th> Nomor Faktur </th>                  
                  <th> Tanggal </th>
                  <th> Suplier </th>
                  <th> Nilai Faktur </th>
                  <th> Potongan </th>
                  <th> Dibayar </th>
                  <th> Nilai Hutang </th>
                                    
            </thead>
            
            <tbody>
            <?php

                  $perintah009 = $db->query("SELECT p.id,p.tunai,p.no_faktur,p.total,p.suplier,p.tanggal,p.tanggal_jt,p.jam,p.user,p.status,p.potongan,p.tax,p.sisa,p.kredit,p.nilai_kredit,s.nama,g.nama_gudang FROM pembelian p INNER JOIN suplier s ON p.suplier = s.id INNER JOIN gudang g ON p.kode_gudang = g.kode_gudang WHERE p.tanggal >= '$dari_tanggal' AND p.tanggal <= '$sampai_tanggal' AND kredit != 0 ORDER BY p.id");
                  while ($data11 = mysqli_fetch_array($perintah009))

                  {
                        //menampilkan data

                        //$tes = $db->query("SELECT p.kode_barang,p.nama_barang,p.jumlah_barang,p.satuan,p.harga,p.id,p.no_faktur,p.subtotal,p.tanggal,p.status,p.potongan,p.tax,p.sisa,s.nama,pe.suplier FROM detail_pembelian p INNER JOIN pembelian pe ON p.no_faktur = pe.no_faktur INNER JOIN suplier s ON pe.suplier = s.id WHERE p.no_faktur = '$data11[no_faktur]' ORDER BY p.id DESC");
                        
                        //$sup = mysqli_fetch_array($tes);
                  echo "<tr>
                  <td>". $data11['no_faktur'] ."</td>
                  <td>". $data11['tanggal'] ."</td>
                  <td>". $data11['nama'] ."</td>
                  <td align='right'>". rp($data11['total']) ."</td>
                  <td align='right'>". rp($data11['potongan']) ."</td>
                  <td align='right'>". rp($data11['tunai']) ."</td>
                  <td align='right'>". rp($data11['kredit']) ."</td>

                  </tr>";


                  }
                   echo"<td><p style='color:red'><b>Jumlah Total</b></p></td>
                  <td></td>
                  <td></td>
                  <td align='right'><p style='color:red'><b>".rp($total_akhir)."</b></p></td>
                  <td align='right'><p style='color:red'><b>".rp($total_potongan)."</b></p></td>
                  <td align='right'><p style='color:red'><b>".rp($total_bayar)."</b></p></td>
                  <td align='right'><p style='color:red'><b>".rp($total_kredit)."</b></p>";

//Untuk Memutuskan Koneksi Ke Database

mysqli_close($db); 
 
            ?>
            </tbody>

      </table>
      <hr>
</div>
</div>
<br>

<div class="col-sm-7">
</div>



 <script>
$(document).ready(function(){
  window.print();
});
</script>

<?php include 'footer.php'; ?>