<?php include 'session_login.php';


// memasukan file session login,  header, navbar, db.php,
include 'header.php';
include 'navbar.php';
include 'db.php';
include 'sanitasi.php';

 
 $nomor_faktur = $_GET['no_faktur'];
 $kode_pelanggan = $_GET['kode_pelanggan'];
 $nama_gudang = $_GET['nama_gudang'];
 $kode_gudang = $_GET['kode_gudang'];



    $query_pelanggan = $db->query("SELECT nama_pelanggan FROM pelanggan WHERE kode_pelanggan = '$kode_pelanggan'");
    $data_pelanggan = mysqli_fetch_array($query_pelanggan);
    $nama_pelanggan = $data_pelanggan['nama_pelanggan'];

    $perintah = $db->query("SELECT tanggal FROM penjualan WHERE no_faktur = '$nomor_faktur'");
    $ambil_tanggal = mysqli_fetch_array($perintah);

    $jumlah_bayar_piutang = $db->query("SELECT SUM(jumlah_bayar) AS jumlah_bayar FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$nomor_faktur'");
    $ambil_jumlah = mysqli_fetch_array($jumlah_bayar_piutang);
    $jumlah_bayar = $ambil_jumlah['jumlah_bayar'];

    $potongan_piutang0 = $db->query("SELECT SUM(potongan) AS potongan FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$nomor_faktur'");
    $ambil_potongan = mysqli_fetch_array($potongan_piutang0);
    $potongan_piutang = $ambil_potongan['potongan'];

    $jumlah_bayar_lama = $jumlah_bayar + $potongan_piutang;

    //Untuk Memutuskan Koneksi Ke Database

    $data_tax = $db->query("SELECT tax FROM penjualan WHERE no_faktur = '$nomor_faktur'");
    $ambil_tax = mysqli_fetch_array($data_tax);
    $pajak_exclude = $ambil_tax['tax'];

    $data_potongan = $db->query("SELECT potongan, tax, ppn, total,potongan_persen,sales,tunai FROM penjualan WHERE no_faktur = '$nomor_faktur'");
    $ambil_potongan = mysqli_fetch_array($data_potongan);
    $potongan = $ambil_potongan['potongan'];
    $ppn = $ambil_potongan['ppn'];
    $tax = $ambil_potongan['tax'];
    $total_akhir = $ambil_potongan['total'];
    $sales = $ambil_potongan['sales'];
    $tunai = $ambil_potongan['tunai'];


    $data_potongan_persen = $db->query("SELECT SUM(subtotal) AS subtotal FROM detail_penjualan WHERE no_faktur = '$nomor_faktur'");
    $ambil_potongan_persen = mysqli_fetch_array($data_potongan_persen);
    $subtotal_persen = $ambil_potongan_persen['subtotal'];

    $potongan_persen = $potongan / $subtotal_persen * 100;
    $hasil_persen = intval($potongan_persen);

    $subtotal_tax = $subtotal_persen - $potongan;
    $hasil_sub = intval($subtotal_tax);

    $potongan_tax = $tax / $hasil_sub * 100;
    $hasil_tax = intval($potongan_tax);

 ?>



 <style type="text/css">
  .disabled {
    opacity: 0.6;
    cursor: not-allowed;
    disabled: true;
}
</style>


<script>
  $(function() {
    $( ".tanggal" ).datepicker({dateFormat: "yy-mm-dd"});
  });

  </script>

  <!-- js untuk tombol shortcut -->
 <script src="shortcut.js"></script>
<!-- js untuk tombol shortcut -->

<!--untuk membuat agar tampilan form terlihat rapih dalam satu tempat -->
 <div style="padding-left: 5%; padding-right: 5%">

  <!--membuat teks dengan ukuran h3-->      
  <h3>EDIT PENJUALAN : <?php echo $nomor_faktur;?> </h3><br>


<!--membuat agar tabel berada dalam baris tertentu-->
 <div class="row">
<div class="col-sm-8">
  


 <div class="row">

<!-- membuat form menjadi beberpa bagian -->
  <form enctype="multipart/form-data" role="form" action="formpenjualan.php" method="post ">
        




<div class="form-group col-sm-2">
  <label> Kode Pelanggan </label>
  <select type="text" name="kode_pelanggan" id="kd_pelanggan" class="form-control chosen"  required="" autofocus="">
  <option value="<?php echo $kode_pelanggan; ?>"><?php echo $kode_pelanggan; ?> - <?php echo $nama_pelanggan; ?> </option>
          
  <?php 
    
    //untuk menampilkan semua data pada tabel pelanggan dalam DB
    $query = $db->query("SELECT kode_pelanggan,nama_pelanggan,level_harga FROM pelanggan");

    //untuk menyimpan data sementara yang ada pada $query
    while($data = mysqli_fetch_array($query))
    {
      if($kode_pelanggan == $data['kode_pelanggan']){
        echo "<option selected value='".$kode_pelanggan ."' class='opt-pelanggan-".$kode_pelanggan."' data-level='".$data['level_harga'] ."'>".$kode_pelanggan." - ".$nama_pelanggan."</option>";
      }
      else{
       echo "<option value='".$data['kode_pelanggan'] ."' class='opt-pelanggan-".$data['kode_pelanggan']."' data-level='".$data['level_harga'] ."'>".$data['kode_pelanggan'] ." - ".$data['nama_pelanggan'] ."</option>";
      }
    
    }
    
    
    ?>
  </select>
  </div>

  <div class="form-group  col-sm-2">
      <label> Gudang </label><br>
        <select name="kode_gudang" id="kode_gudang" class="form-control chosen" required="" >
          <option value=<?php echo $kode_gudang; ?>><?php echo $nama_gudang; ?></option>
          <?php 
        
          // menampilkan seluruh data yang ada pada tabel suplier
          $query = $db->query("SELECT kode_gudang,nama_gudang FROM gudang");
          while($data = mysqli_fetch_array($query)){
            echo "<option value='".$data['kode_gudang'] ."'>".$data['nama_gudang'] ."</option>";
          }
          
          ?>
        </select>
</div>

<div class="form-group col-sm-2">
  <label> Level Harga </label><br>
  <select type="text" name="level_harga" id="level_harga" class="form-control" required="" >
  <option>Level 1</option>
  <option>Level 2</option>
  <option>Level 3</option>

    </select>
  </div>



<div class="form-group col-sm-2">
  <label>Sales</label>
    <select name="sales" id="sales" class="form-control" required="">
      <?php 
      //untuk menampilkan semua data pada tabel pelanggan dalam DB
      $query_sales = $db->query("SELECT nama FROM user WHERE status_sales = 'Iya'");

      while($data_sales = mysqli_fetch_array($query_sales)){
        if($sales == $data_sales['nama']){
          echo "<option selected value='".$sales."'>".$sales ."</option>";
        }
        else{
          echo "<option value='".$data_sales['nama'] ."'>".$data_sales['nama'] ."</option>";
        }
      }
      ?>
    </select>
</div>

<div class="form-group col-sm-2">
          <label>PPN</label>
          <select name="ppn" id="ppn" class="form-control">
            <option value="Non">Non</option>  
            <option value="Include">Include</option>  
            <option value="Exclude">Exclude</option>
          </select>
</div>


<div class="form-group  col-sm-2">
      <label> Tanggal </label><br>
      <input type="text" name="tanggal" id="tanggal"  value="<?php echo $ambil_tanggal['tanggal']; ?>" class="form-control tanggal" >
</div>


      <input type="hidden" name="nomor_faktur_penjualan" id="nomor_faktur_penjualan"  value="<?php echo $nomor_faktur; ?>" class="form-control tanggal" >


</div>

        
</form><!--tag penutup form-->


<!--tampilan modal-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- isi modal-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data Barang</h4>
      </div>
      <div class="modal-body">

      <div class="table-resposive">
<span class="modal_baru">
      <!-- membuat agar ada garis pada tabel, disetiap kolom-->
      <table id="tabel_cari" class="table table-bordered table-sm">
        <thead> <!-- untuk memberikan nama pada kolom tabel -->
        
            <th> Kode Barang </th>
            <th> Nama Barang </th>
            <th> Harga Jual Level 1</th>
            <th> Harga Jual Level 2</th>
            <th> Harga Jual Level 3</th>
            <th> Jumlah Barang </th>
            <th> Satuan </th>
            <th> Kategori </th>
            <th> Suplier </th>

          </thead> <!-- tag penutup tabel -->
        </table> <!-- tag penutup table-->
  </span>
</div>
</div> <!-- tag penutup modal-body-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><!-- end of modal data barang  -->


<!-- Modal Hapus data -->
<div id="modal_hapus" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Konfirmsi Hapus Data Edit Pembelian</h4>
      </div>
      <div class="modal-body">
   <p>Apakah Anda yakin Ingin Menghapus Data ini ?</p>
   <form >
    <div class="form-group">
     <input type="text" id="nama-barang" class="form-control" readonly=""> 
     <input type="text" id="kode-barang" class="form-control" readonly=""> 
     <input type="hidden" id="id_hapus" class="form-control" >

    </div>
   
   </form>
   
  <div class="alert alert-success" style="display:none">
   <strong>Berhasil!</strong> Data berhasil Di Hapus
  </div>
 

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="btn_jadi_hapus">Ya</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      </div>
    </div>

  </div>
</div><!-- end of modal hapus data  -->               



<!-- Modal edit data -->
<div id="modal_edit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Data Pembelian Barang</h4>
      </div>
      <div class="modal-body">
  <form role="form">
   <div class="form-group">
    <label for="email">Jumlah Baru:</label>
     <input type="text" class="form-control" autocomplete="off" id="barang_edit"><br>
    <label for="email">Jumlah Lama:</label>
     <input type="text" class="form-control" id="barang_lama" readonly="">
     <input type="hidden" class="form-control" id="harga_edit" readonly="">
     <input type="hidden" class="form-control" id="faktur_edit" readonly="">
     <input type="hidden" class="form-control" id="kode_edit">     
     <input type="hidden" class="form-control" id="potongan_edit" readonly="">
     <input type="hidden" class="form-control" id="tax_edit" readonly="">
     <input type="hidden" class="form-control" id="id_edit">
    
   </div>
   
   
   <button type="submit" id="submit_edit" class="btn btn-default">Submit</button>
  </form>
  <span id="alert"> </span>
  <div class="alert-edit alert-success" style="display:none">
   <strong>Berhasil!</strong> Data berhasil Di Edit
  </div>
 

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><!-- end of modal edit data  -->


<div id="modal_alert" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 style="color:orange" class="modal-title"><span class="glyphicon glyphicon-info-sign">Info!</span></h3>
        <h4>Maaf No Transaksi <strong><?php echo $nomor_faktur; ?></strong> tidak dapat dihapus atau di edit, karena telah terdapat Transaksi Pembayaran Piutang atau Retur Penjualan. Dengan daftar sebagai berikut :</h4>
      </div>

      <div class="modal-body">
      <span id="modal-alert">
       </span>


     </div>

      <div class="modal-footer">
        <h6 style="text-align: left"><i> * jika ingin menghapus atau mengedit data,<br>
        silahkan hapus terlebih dahulu Transaksi Pembayaran Piutang atau Retur Penjualan</i></h6>
        <button type="button" class="btn btn-warning btn-close" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- membuat form prosestbspenjual -->
<form class="form" action="proses_tambah_edit_penjualan.php" role="form" id="formtambahproduk">
<br>

<div class="form-group">
    <button type="button" id="cari_produk_penjualan" class="btn btn-info" data-toggle="modal" data-target="#myModal"> <i class='fa  fa-search'> </i> Cari (F1)</button>
</div>


<div class="row">


<input type="hidden" name="ppn_input" id="ppn_input" value="<?php echo $ppn; ?>" class="form-control" placeholder="ppn input">  



<div class="col-sm-3">
    <input type="text" style="height:15px;"  class="form-control" name="kode_barang" id="kode_barang" autocomplete="off" placeholder="Kode Produk">
</div>

      <input type="hidden" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" readonly="">

<div class="col-sm-2">
    <input style="height:15px" type="text" class="form-control" name="jumlah_barang" autocomplete="off" id="jumlah_barang" placeholder="Jumlah Barang" required="">
</div>

<div class="col-sm-1">
    <select type="text" name="satuan_konversi" id="satuan_konversi" class="form-control"  required="" style="font-size:15px; height:35px" >
          <?php 
          $query = $db->query("SELECT id, nama  FROM satuan");
          while($data = mysqli_fetch_array($query))
          {
          
          echo "<option value='".$data['id']."'>".$data['nama'] ."</option>";
          }
                      
          ?>
          
        </select>
  </div>
  
<div class="col-sm-1">
    <input type="text" style="height:15px;" class="form-control" name="potongan" autocomplete="off" id="potongan1" pdata-toggle="tooltip" data-placement="top" title="Jika Ingin Potongan Dalam Bentuk Persen (%), input : 10%" placeholder="Disc."  >
  </div>


<div class="col-sm-1">
      <input type="text" style="height:15px;" class="form-control" name="tax" autocomplete="off" id="tax1"  placeholder="Tax (%)" >
      </div>
  
<div class="col-sm-2">
  <button type="submit" id="submit_produk" class="btn btn-success" style="font-size:15px" > <i class='fa fa-plus'> </i> Tambah</button>
</div>

</div>

<!--form hidden form tambah produk--> 
  <input type="hidden" class="form-control" name="jumlah_barang_tbs" id="jumlah_barang_tbs">
  <input type="hidden" class="form-control" name="limit_stok" id="limit_stok">
  <input type="hidden" placeholder="Stok" class="form-control" name="jumlahbarang" id="jumlahbarang">
  <input type="hidden" class="form-control" name="ber_stok" id="ber_stok" placeholder="Ber Stok" >
  <input type="hidden" class="form-control" name="harga_lama" id="harga_lama">
  <input type="hidden" class="form-control" name="harga_baru" id="harga_baru">
  <input type="hidden" id="satuan_produk" name="satuan" class="form-control" value="" required="">
  <input type="hidden" id="harga_produk" placeholder="Harga / Level" name="harga" class="form-control" value="" required="">
  <input type="hidden" id="satuan_produk" name="satuan" class="form-control" value="" required="">
  <input type="hidden" id="id_produk" name="id_produk" placeholder="id_produk" class="form-control" value="" required="">  
  <input type="hidden" name="no_faktur" id="no_faktur0" class="form-control" value="<?php echo $nomor_faktur; ?>" required="" >
<!--form hidden form tambah produk--> 

  <br>

</form> <!-- tag penutup form -->


<!--untuk mendefinisikan sebuah bagian dalam dokumen-->   
                <div class="table-responsive"> <!--tag untuk membuat garis pada tabel-->  
                <span id="table-baru">  
                <table id="tabel_tbs_penjualan" class="table table-bordered table-sm">
                <thead>
                <th> Kode Barang </th>
                <th> Nama Barang </th>
                <th> Jumlah Barang </th>
                <th> Satuan </th>
                <th> Harga </th>
                <th> Potongan </th>
                <th> Pajak </th>
                <th> Subtotal </th>
                <th> Hapus </th>
                
                </thead>
                
              
                
                </table>
                </span>
                </div>


</div>



<div class="col-sm-4">
 
 <form action="proses_bayar_edit_jual.php" id="form_jual" method="POST" >
    
    <style type="text/css">
    .disabled {
    opacity: 0.6;
    cursor: not-allowed;
    disabled: false;
    }
    </style>

          <br>

<div class="card card-block">
<div class="row">
          <div class="col-sm-6">
         <label> Subtotal </label><br>
         <input type="text" name="total" id="total2" class="form-control" style="height:15px;font-size:15px" placeholder="Total" readonly="" >
         </div>
           <div class="form-group col-sm-6">


          <label> Diskon ( Rp )</label><br>
          <input type="text" name="potongan" id="potongan_penjualan" value="<?php echo intval($potongan); ?>" style="height:15px;font-size:15px" class="form-control" placeholder="" autocomplete="off"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">

          </div>
         </div>


<div class="row">

          <div class="form-group col-sm-6">      
          <label> Diskon ( % )</label><br>
          <input type="text" name="potongan_persen" id="potongan_persen" value="<?php echo $ambil_potongan['potongan_persen']; ?>" style="height:15px;font-size:15px" class="form-control" placeholder="" autocomplete="off" >
          </div>

          <div class="form-group col-sm-6">
          <label> Pajak </label><br>
          <input type="text" name="tax" id="tax" value="<?php echo $hasil_tax; ?>" style="height:15px;font-size:15px" class="form-control"  autocomplete="off" >
          </div>

</div>
          

          
<div class="row">
  


          <div class="form-group col-sm-6">
          <label> Tanggal Jatuh Tempo </label><br>
          <input type="text" name="tanggal_jt" id="tanggal_jt" style="height:15px;font-size:15px"  value="" class="form-control tanggal" >
          </div>

           <div class="col-sm-6">

      <label> Cara Bayar </label><br>
          <select type="text" name="cara_bayar" id="carabayar1" class="form-control" required=""  style="font-size: 16px" >
          <option value=""> Silahkan Pilih </option>
             <?php 
             
             $sett_akun = $db->query("SELECT sa.kas, da.nama_daftar_akun FROM setting_akun sa INNER JOIN daftar_akun da ON sa.kas = da.kode_daftar_akun");
             $data_sett = mysqli_fetch_array($sett_akun);
             
             echo "<option selected value='".$data_sett['kas']."'>".$data_sett['nama_daftar_akun'] ."</option>";
             
             $query = $db->query("SELECT nama_daftar_akun, kode_daftar_akun FROM daftar_akun WHERE tipe_akun = 'Kas & Bank'");
             while($data = mysqli_fetch_array($query))
             {
             
             echo "<option value='".$data['kode_daftar_akun']."'>".$data['nama_daftar_akun'] ."</option>";
             
             }
             ?>
          
          </select>
          </div>

</div>

          <input type="hidden" name="tax_rp" id="tax_rp" class="form-control"  autocomplete="off" >

           <label style="display: none"> Adm Bank  (%)</label>
          <input type="hidden" name="adm_bank" id="adm_bank"  value="" class="form-control" >
          
          

<div class="row">
          

<div class="col-sm-6">
          <label style="font-size:15px"> Total Akhir</label><br>
          <b><input type="text" name="total" id="total1" class="form-control" value="<?php echo $total_akhir; ?>" style="height: 50px; width:90%; font-size:25px;" placeholder="Total" readonly="" ></b>
          </div>

          <div class="col-sm-6">
             <label> Pembayaran </label><br>
          <b><input type="text" name="pembayaran" id="pembayaran_penjualan" value="<?php echo $tunai ?>" style="height: 50px; width:90%; font-size:25px;" autocomplete="off" class="form-control"   style="font-size: 20px"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"></b>
          </div>
                   
</div>

       


<div class="row">
          
          <div class="col-sm-6">
          <label> Kembalian </label><br>
          <b><input type="text" name="sisa_pembayaran" id="sisa_pembayaran_penjualan" style="height:15px;font-size:15px" class="form-control"  readonly="" required=""  style="font-size: 20px" ></b>
          </div>
          
          <div class="col-sm-6">
          <label> Kredit </label><br>
          <b><input type="text" name="kredit" id="kredit" class="form-control" style="height:15px;font-size:15px"  readonly="" required="" ></b>
          </div>
</div>
          
          


         
          

          <label> Keterangan </label><br>
          <textarea type="text" name="keterangan" id="keterangan" class="form-control"> 
          </textarea>

          <b><input type="hidden" name="zxzx" id="zxzx" class="form-control" style="height: 50px; width:90%; font-size:25px;"  readonly="" required="" ></b>


          <b><input type="hidden" name="jumlah_bayar_lama" id="jumlah_bayar_lama" value="<?php echo $jumlah_bayar_lama; ?>" class="form-control" style="height: 50px; width:90%; font-size:25px;"  readonly=""></b>

<?php 

if ($_SESSION['otoritas'] == 'Pimpinan') {
 echo '<label style="display:none"> Total Hpp </label><br>
          <input type="hidden" name="total_hpp" id="total_hpp" style="height: 50px; width:90%; font-size:25px;" class="form-control" placeholder="" readonly="" required="">';
}

         mysqli_close($db); 


 ?>

          <input type="hidden" name="jumlah" id="jumlah1" class="form-control" placeholder="jumlah">   <br> 
          
          
          <!-- memasukan teks pada kolom kode pelanggan, dan nomor faktur penjualan namun disembunyikan -->
          <input type="hidden" name="no_faktur" id="nofaktur" class="form-control" value="<?php echo $nomor_faktur; ?>" required="" >
          
          <input type="hidden" name="kode_pelanggan" id="k_pelanggan" class="form-control" required="" >
</div><!-- end div card block-->




    <button type="submit" id="penjualan" class="btn btn-info" style="font-size:15px" data-faktur='<?php echo $nomor_faktur ?>'>Bayar (F8) </button>

  <button type="submit" id="piutang" class="btn btn-warning" style="font-size:15px" data-faktur='<?php echo $nomor_faktur; ?>'>Piutang (F9) </button>





          

    <a href="penjualan.php?status=semua" id="transaksi_baru" class="btn btn-primary" style="font-size:15px;display: none;">Transaksi Baru</a>

    <a href='cetak_penjualan_tunai.php?no_faktur=<?php echo $nomor_faktur; ?>' id="cetak_tunai"  style="font-size:15px;display: none;" class="btn btn-success" target="blank">Cetak Tunai </a>

    <a href='cetak_penjualan_tunai_besar.php?no_faktur=<?php echo $nomor_faktur; ?>' id="cetak_tunai_besar" style="font-size:15px;display: none;"  class="btn btn-info" target="blank">Cetak Tunai Besar</a>

   
    <a href='cetak_penjualan_piutang.php?no_faktur=<?php echo $nomor_faktur ?>' id="cetak_piutang"  style="font-size:15px;display: none;" class="btn btn-warning" target="blank"> <span class="  glyphicon glyphicon-print"> </span> Cetak Piutang </a>
    

              <div class="alert alert-success" id="alert_berhasil" style="display:none">
          <strong>Success!</strong> Pembayaran Berhasil
          </div>

</div> <!--<div class="col-sm-4">-->

   <br>


</form>

</div>
 
</div><!-- end of row -->   
          


    

    </div><!-- end of container -->

<script type="text/javascript">
   $(document).on('ready', function (e) {                
// START DATATABLE AJAX START TBS PENJUALAN
      $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_edit_penjualan.php", // json datasource
                              "data": function ( d ) {
                                d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                             
               type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#tabel_tbs_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            },
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

            $(nRow).attr('class','tr-id-'+aData[9]+'');         

            }   

      });

});
 </script>

    
<script>
//untuk menampilkan data tabel
$(document).ready(function(){
    $("#kd_pelanggan").focus();
});

</script>


<script type="text/javascript" language="javascript" >
   $(document).ready(function() {

        var dataTable = $('#tabel_cari').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"modal_edit_penjualan.php", // json datasource
           "data": function ( d ) {
                d.no_faktur = $("#no_faktur0").val();
                // d.custom = $('#myInput').val();
                // etc
            },
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#tabel_cari").append('<tbody class="employee-grid-error"><tr><th colspan="3">Data Tidak Ditemukan.. !!</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
              
            }
          },

          "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).attr('class', "pilih");
              $(nRow).attr('data-kode', aData[0]+" ("+aData[1]+")");
              $(nRow).attr('nama-barang', aData[1]);
              $(nRow).attr('harga', aData[2]);
              $(nRow).attr('harga_level_2', aData[3]);
              $(nRow).attr('harga_level_3', aData[4]);
              $(nRow).attr('jumlah-barang', aData[5]);
              $(nRow).attr('satuan', aData[12]);
              $(nRow).attr('limit_stok', aData[9]);
              $(nRow).attr('ber-stok', aData[10]);
              $(nRow).attr('id-barang', aData[13]);





          }

        });    
     
  });
 
 </script>


<script>
//untuk menampilkan data tabel
$(document).ready(function(){
    $('#tableuser').dataTable();
});

</script>


<!--untuk memasukkan perintah java script-->
<script type="text/javascript">

// jika dipilih, nim akan masuk ke input dan modal di tutup
  $(document).on('click', '.pilih', function (e) {
  document.getElementById("kode_barang").value = $(this).attr('data-kode');
  document.getElementById("nama_barang").value = $(this).attr('nama-barang');
  document.getElementById("limit_stok").value = $(this).attr('limit_stok');
  document.getElementById("satuan_produk").value = $(this).attr('satuan');
  document.getElementById("ber_stok").value = $(this).attr('ber-stok');
  document.getElementById("harga_lama").value = $(this).attr('harga');
  document.getElementById("harga_baru").value = $(this).attr('harga');
  document.getElementById("satuan_konversi").value = $(this).attr('satuan');
  document.getElementById("id_produk").value = $(this).attr('id-barang');
  document.getElementById("jumlahbarang").value = $(this).attr('jumlah-barang');

    var no_faktur = $("#nomor_faktur_penjualan").val();
    var kode_barang = $("#kode_barang").val();
   var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));


 $.post('cek_kode_barang_edit_tbs_penjualan.php',{kode_barang:kode_barang,no_faktur:no_faktur}, function(data){
  
      if(data == 1){
      alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
      $("#kode_barang").val('');
      $("#nama_barang").val('');
    }//penutup if

      });////penutup function(data)
 



var level_harga = $("#level_harga").val();

var harga_level_1 = $(this).attr('harga');
var harga_level_2 = $(this).attr('harga_level_2');  
var harga_level_3 = $(this).attr('harga_level_3');

if (level_harga == "Level 1") {
    $("#harga_produk").val(harga_level_1);
    $("#harga_lama").val(harga_level_1);
    $("#harga_baru").val(harga_level_1);

}


else if (level_harga == "Level 2") {
  $("#harga_produk").val(harga_level_2);
  $("#harga_baru").val(harga_level_2);
  $("#harga_lama").val(harga_level_2);
}


else if (level_harga == "Level 3") {
  $("#harga_produk").val(harga_level_3);
  $("#harga_lama").val(harga_level_3);
  $("#harga_baru").val(harga_level_3);

}




            var no_faktur = $("#nomor_faktur_penjualan").val();
            var kode_barang = $(this).attr('data-kode');
            
            $.post("cek_jumlah_tbs.php",
            {
            no_faktur:no_faktur,kode_barang:kode_barang
            },
            function(data){
            $("#jumlah_barang_tbs").val(data);
            });



  $('#myModal').modal('hide');
  });
   
  </script>


  <script type="text/javascript">
$(document).ready(function(){
  //end cek level harga
  $("#level_harga").change(function(){
  
  var level_harga = $("#level_harga").val();
  var kode_barang = $("#kode_barang").val();
  var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
  var satuan_konversi = $("#satuan_konversi").val();
  var jumlah_barang = $("#jumlah_barang").val();
  var id_produk = $("#id_produk").val();

$.post("cek_level_harga_barang.php",
        {level_harga:level_harga, kode_barang:kode_barang,jumlah_barang:jumlah_barang,id_produk:id_produk,satuan_konversi:satuan_konversi},function(data){

          $("#harga_produk").val(data);
          $("#harga_baru").val(data);
        });
    });
});
//end cek level harga
</script>

<!-- cek stok satuan konversi keyup-->
<script type="text/javascript">
  $(document).ready(function(){  
    $("#jumlah_barang").keyup(function(){
      
      var jumlah_barang = $("#jumlah_barang").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var kode_barang = $("#kode_barang").val();
      var ber_stok = $("#ber_stok").val();
      var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
      var id_produk = $("#id_produk").val();
      var no_faktur = $("#no_faktur0").val();
      var prev = $("#satuan_produk").val();

      if (ber_stok != 'Jasa'){
      $.post("cek_stok_konversi_edit_penjualan.php",
        {jumlah_barang:jumlah_barang,satuan_konversi:satuan_konversi,kode_barang:kode_barang,id_produk:id_produk,no_faktur:no_faktur},function(data){

          if (data < 0) {
            alert("Jumlah Melebihi Stok");
            $("#jumlah_barang").val('');
          $("#satuan_konversi").val(prev);
          }

      });
  }//if (ber_stok != 'Jasa'){

    });
  });
</script>
<!-- cek stok satuan konversi keyup-->

<!-- cek stok satuan konversi change-->
<script type="text/javascript">
  $(document).ready(function(){
    $("#satuan_konversi").change(function(){
      var jumlah_barang = $("#jumlah_barang").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var kode_barang = $("#kode_barang").val();
      var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
      var ber_stok = $("#ber_stok").val();
      var no_faktur = $("#no_faktur0").val();
      var id_produk = $("#id_produk").val();
      var prev = $("#satuan_produk").val();

      if (ber_stok != 'Jasa'){
      $.post("cek_stok_konversi_edit_penjualan.php",
        {jumlah_barang:jumlah_barang,satuan_konversi:satuan_konversi,kode_barang:kode_barang,id_produk:id_produk,no_faktur:no_faktur},function(data){

          if (data < 0) {
            alert("Jumlah Melebihi Stok");
            $("#jumlah_barang").val('');
          $("#satuan_konversi").val(prev);

          }

      });
  }//if (ber_stok != 'Jasa'){
    });
  });
</script>
<!-- end cek stok satuan konversi change-->


<script>
$(document).ready(function(){
    $("#satuan_konversi").change(function(){

      var prev = $("#satuan_produk").val();
      var harga_lama = $("#harga_lama").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var id_produk = $("#id_produk").val();
      var harga_produk = $("#harga_lama").val();
      var jumlah_barang = $("#jumlah_barang").val();
      var kode_barang = $("#kode_barang").val();
        var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));

      

      $.getJSON("cek_konversi_penjualan.php",{kode_barang:kode_barang,satuan_konversi:satuan_konversi, id_produk:id_produk,harga_produk:harga_produk,jumlah_barang:jumlah_barang},function(info){

          if (satuan_konversi == prev) {
      
          $("#harga_produk").val(harga_lama);
          $("#harga_baru").val(harga_lama);

        }

        else if (info.jumlah_total == 0) {
          alert('Satuan Yang Anda Pilih Tidak Tersedia Untuk Produk Ini !');
          $("#satuan_konversi").val(prev);
          $("#harga_produk").val(harga_lama);
          $("#harga_baru").val(harga_lama);

        }

        else{
          $("#harga_produk").val(info.harga_pokok);
          $("#harga_baru").val(info.harga_pokok);
        }

      });

        
    });

});
</script>




<script>
   //untuk menampilkan data yang diambil pada form tbs penjualan berdasarkan id=formtambahproduk
  $(document).on('click', '#submit_produk', function (e) {

    var no_faktur = $("#nomor_faktur_penjualan").val();
    var kode_pelanggan = $("#kd_pelanggan").val();
    var kode_barang = $("#kode_barang").val();
    var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
    var nama_barang = $("#nama_barang").val();
    var jumlah_barang = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlah_barang").val()))));
    var harga = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#harga_produk").val()))));
    var harga_baru = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#harga_baru").val()))));
    var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan1").val()))));
        if (potongan == '') {
      potongan = 0;

    }

    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax1").val()))));
    var jumlahbarang = $("#jumlahbarang").val();
    var satuan = $("#satuan_konversi").val();
    var level_harga = $("#level_harga").val();
    var ber_stok = $("#ber_stok").val();
    var sales = $("#sales").val();
    var ppn = $("#ppn").val();
    var stok = jumlahbarang - jumlah_barang;

   var subtotal = parseInt(jumlah_barang, 10) *  parseInt(harga, 10) - parseInt(potongan, 10);

console.log(ber_stok);
   //end data produk
   // data per faktur 
    var potongan_persen = $("#potongan_persen").val();
    var status_bertingkat = potongan_persen.indexOf("+");
    var tax_faktur = $("#tax").val();
        if (tax_faktur == "") {
        tax_faktur = 0;
        }   


   var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
    if (total == '') 
    {
          total = 0;
    }

    var subtotal_penjualan = parseInt(total,10) + parseInt(subtotal,10);
    total =  subtotal_penjualan;

    $("#total2").val(tandaPemisahTitik(subtotal_penjualan));


  // perhitungan diskon bertingkat 
   if (status_bertingkat > 0) {
            var diskon_bertingkat = potongan_persen.split("+");
            var potongan_nominal = 0;
            var index;
            var total_kurang_potongan = total;
            var total_potongan_nominal = 0;
            for (index = 0; index < diskon_bertingkat.length; ++index) {
               
                var diskon_persen = diskon_bertingkat[index];

                if (diskon_persen != '' || diskon_persen != 0) {
                 total_potongan_nominal = Math.round(total_potongan_nominal) + Math.round(((total_kurang_potongan * diskon_persen) / 100));
                 potongan_nominal =  Math.round((total_kurang_potongan * diskon_persen) / 100);
                var total_kurang_potongan = total_kurang_potongan - parseInt(potongan_nominal,10);
                }
              
            }

            var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax_faktur,10)) / 100);
            var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);

            $("#total1").val(tandaPemisahTitik(parseInt(total_akhir)));
        } 
        else {

          var total_potongan_nominal =  Math.round(((total * potongan_persen) / 100));
          var total_kurang_potongan = total - total_potongan_nominal;
          var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax_faktur,10)) / 100);

          var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);
              if (potongan_persen > 100) {
                alert ("Potongan %, Tidak Boleh Lebih Dari 100%");
                $("#potongan_persen").val('100');
              }
              else {
  
                }

              $("#total1").val(tandaPemisahTitik(parseInt(total_akhir)));

            
             } // end diskon bertingkat
     

  if (jumlah_barang == ''){
  alert("Jumlah Barang Harus Diisi");
  }
  else if (kode_pelanggan == ''){
  alert("Kode Pelanggan Harus Dipilih");
  }


  else if (ber_stok == 'Jasa'){

    $(".tr-kode-"+kode_barang+"").remove();
    $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal)));

    $.post("proses_tambah_edit_penjualan.php",{no_faktur:no_faktur,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga:harga,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,sales:sales,level_harga:level_harga},function(data){
     
     $("#kode_barang").focus();
      $("#ppn").attr("disabled", true);
     
     $("#kode_barang").val('');
     $("#nama_barang").val('');
     $("#jumlah_barang").val('');
     $("#potongan1").val('');
     $("#tax1").val('');
     $("#pembayaran_penjualan").val('');

//pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_edit_penjualan.php", // json datasource
                              "data": function ( d ) {
                                d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                             
               type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#tabel_tbs_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            },
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

            $(nRow).attr('class','tr-id-'+aData[9]+'');         

            }   

      });
//pembaruan datatable

     
     });

  }

    else if (stok < 0 ){
  alert("Jumlah Barang Melebihi Stok");
  }
  
  else{

    $(".tr-kode-"+kode_barang+"").remove();
    $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal)));

    $.post("proses_tambah_edit_penjualan.php",{no_faktur:no_faktur,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga:harga,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,sales:sales,level_harga:level_harga},function(data){
     
     $("#kode_barang").focus();
      $("#ppn").attr("disabled", true);
     $("#kode_barang").val('');
     $("#nama_barang").val('');
     $("#jumlah_barang").val('');
     $("#potongan1").val('');
     $("#tax1").val('');
     $("#pembayaran_penjualan").val('');

//pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_edit_penjualan.php", // json datasource
                              "data": function ( d ) {
                                d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                             
               type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#tabel_tbs_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            },
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

            $(nRow).attr('class','tr-id-'+aData[9]+'');         

            }   

      });
//pembaruan datatable

     
     });
}
    

      
  });

  $("#formtambahproduk").submit(function(){
    return false;

});
</script>


<script>
  $("#penjualan").click(function(){

        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var harga = $("#harga_produk").val();
        var sales = $("#sales").val();
        var tanggal = $("#tanggal").val();
        var kode_gudang = $("#kode_gudang").val();
        var keterangan = $("#keterangan").val();
        var jumlah_bayar_lama = $("#jumlah_bayar_lama").val();
        var ppn_input = $("#ppn_input").val();
        var ppn = $("#ppn").val();
        var total2 = $("#total2").val();
        var sisa = pembayaran - total;
        var sisa_kredit = total - pembayaran;

        var jumlah_kredit_baru = parseInt(kredit,10) - parseInt(jumlah_bayar_lama,10);
       var x = parseInt(jumlah_bayar_lama,10) + parseInt(pembayaran,10);
       $("#zxzx").val(x);

     $("#total1").val('');
     $("#pembayaran_penjualan").val('');
     $("#sisa_pembayaran_penjualan").val('');
     $("#kredit").val('');

     $("#kd_pelanggan").val('');

 
 if (sisa_pembayaran < 0)
 {

  alert("Jumlah Pembayaran Tidak Mencukupi");

 }


 else if (kode_pelanggan == "") 
 {

alert("Kode Pelanggan Harus Di Isi");

 }
else if (pembayaran == "") 
 {

alert("Pembayaran Harus Di Isi");

 }

 else if (jumlah_bayar_lama == 0)

 {



  $("#penjualan").hide();
  $("#piutang").hide();
  $("#transaksi_baru").show();  

 $.post("proses_bayar_edit_jual.php",{total2:total2,kode_gudang:kode_gudang,tanggal:tanggal,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,harga:harga,sales:sales,keterangan:keterangan,jumlah_kredit_baru:jumlah_kredit_baru,x:x,ppn_input:ppn_input},function(info) {

     $("#alert_berhasil").show();
     $("#pembayaran_penjualan").val('');
     $("#sisa_pembayaran_penjualan").val('');
     $("#kredit").val('');
     $("#potongan_penjualan").val('');
     $("#potongan_persen").val('');
     $("#kode_meja").val('');
     $("#cetak_tunai").show();
     $("#cetak_tunai_besar").show();
       
     //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_edit_penjualan.php", // json datasource
                              "data": function ( d ) {
                                d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                             
               type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#tabel_tbs_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            },
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

            $(nRow).attr('class','tr-id-'+aData[9]+'');         

            }   

      });
    //pembaruan datatable

   });

  }


else{

    if (x > total) {

    var no_faktur = $(this).attr("data-faktur");

    $.post('alert_piutang_penjualan.php',{no_faktur:no_faktur},function(data){
    
    
    $("#modal_alert").modal('show');
    $("#modal-alert").html(data);

  });

  }

}



 $("form").submit(function(){
    return false;
});

  });

      
  </script>


  
     <script>
       //perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
       $("#piutang").click(function(){

        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var harga = $("#harga_produk").val();
        var sales = $("#sales").val();
        var tanggal = $("#tanggal").val();
        var kode_gudang = $("#kode_gudang").val();
        var keterangan = $("#keterangan").val();
        var jumlah_bayar_lama = $("#jumlah_bayar_lama").val();
        var ppn_input = $("#ppn_input").val();
        var total2 = $("#total2").val();
        var sisa =  pembayaran - total;
        var sisa_kredit = total - pembayaran;

        var jumlah_kredit_baru = parseInt(kredit,10) - parseInt(jumlah_bayar_lama,10);
        var x = parseInt(jumlah_bayar_lama,10) + parseInt(pembayaran,10);
        $("#zxzx").val(x);
        

      $("#total1").val('');
       $("#pembayaran_penjualan").val('');
       $("#sisa_pembayaran_penjualan").val('');
       $("#kredit").val('');
       $("#tanggal_jt").val('');

       
      if (sisa_pembayaran == "" )
      {

        alert ("Jumlah Pembayaran Tidak Mencukupi");
      }

       else if (kode_pelanggan == "") 
       {
       
       alert("Kode Pelanggan Harus Di Isi");
       
       }
       else if (tanggal_jt == "")
       {

        alert ("Tanggal Jatuh Tempo Harus Di Isi");

       }


      else if (jumlah_bayar_lama == 0 || x <= total )
      {


  //Cek Flafon sesuai dengan kode pelanggan / ID Pelanggannya
   $.post("cek_flafon.php",{kredit:kredit,kode_pelanggan:kode_pelanggan},function(data) {
    if(data == 1)
    {
      alert("Anda Tidak Bisa Melakukan Transaksi Piutang, Cek Jumlah Maximum Piutang");
    }

else
{


        $("#penjualan").hide();
        $("#piutang").hide();
        $("#transaksi_baru").show(); 
        
        $.post("proses_bayar_edit_jual.php",{total2:total2,kode_gudang:kode_gudang,tanggal:tanggal,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,harga:harga,sales:sales,keterangan:keterangan,jumlah_kredit_baru:jumlah_kredit_baru,x:x,ppn_input:ppn_input},function(info) {
        
        $("#alert_berhasil").show();
        $("#pembayaran_penjualan").val('');
        $("#sisa_pembayaran_penjualan").val('');
        $("#kredit").val('');
        $("#potongan_penjualan").val('');
        $("#potongan_persen").val('');
        $("#tanggal_jt").val('');
        $("#cetak_piutang").show();
        
        //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_edit_penjualan.php", // json datasource
                              "data": function ( d ) {
                                d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                             
               type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#tabel_tbs_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            },
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

            $(nRow).attr('class','tr-id-'+aData[9]+'');         

            }   

      });
//pembaruan datatable

        
        });


}// end else untuk cek plafon


});// proses cek flafon 


}// end else if(jumlah_bayar_lama == 0 || x <= total )



      else
      {
             if (x > total)

             {
              var no_faktur = $(this).attr("data-faktur");
              
              $.post('alert_piutang_penjualan.php',{no_faktur:no_faktur},function(data){
              
              
              $("#modal_alert").modal('show');
              $("#modal-alert").html(data);
              
              });

            }
       

       
      }  

  });

 $("form").submit(function(){
       return false;
       });





  </script>   


<script type="text/javascript">
    $(document).ready(function(){

        var no_faktur = $("#nomor_faktur_penjualan").val();
        
        $.post("cek_total_edit_penjualan.php",
        {
        no_faktur:no_faktur
        },
        function(data){

        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(data))));
              $("#total2").val(total);

    var potongan_persen = $("#potongan_persen").val();
    var status_bertingkat = potongan_persen.indexOf("+");
    var tax_faktur = $("#tax").val();
        if (tax_faktur == "") {
        tax_faktur = 0;
        }   

 // perhitungan diskon bertingkat 
   if (status_bertingkat > 0) {
            var diskon_bertingkat = potongan_persen.split("+");
            var potongan_nominal = 0;
            var index;
            var total_kurang_potongan = total;
            var total_potongan_nominal = 0;
            for (index = 0; index < diskon_bertingkat.length; ++index) {
               
                var diskon_persen = diskon_bertingkat[index];

                if (diskon_persen != '' || diskon_persen != 0) {
                 total_potongan_nominal = Math.round(total_potongan_nominal) + Math.round(((total_kurang_potongan * diskon_persen) / 100));
                 potongan_nominal =  Math.round((total_kurang_potongan * diskon_persen) / 100);
                var total_kurang_potongan = total_kurang_potongan - parseInt(potongan_nominal,10);
                }
              
            }

            var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax_faktur,10)) / 100);
             var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);


            $("#total1").val(tandaPemisahTitik(parseInt(total_akhir)));
        } 
        else {

          var total_potongan_nominal =  Math.round(((total * potongan_persen) / 100));
          var total_kurang_potongan = total - total_potongan_nominal;
          var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax_faktur,10)) / 100);

          var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);
              if (potongan_persen > 100) {
                alert ("Potongan %, Tidak Boleh Lebih Dari 100%");
                $("#potongan_persen").val('100');
              }
              else {
  
                }

             $("#total1").val(tandaPemisahTitik(parseInt(total_akhir)));

            
             } // end diskon bertingkat
     
  $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal)));


        });
      });
</script>
 
        <script>
        
        //untuk menampilkan sisa penjualan secara otomatis
        $(document).ready(function(){
        $("#pembayaran_penjualan").keyup(function(){
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() ))));
        var sisa = pembayaran - total;
        var sisa_kredit = total - pembayaran; 
        
        if (sisa < 0 )
        {
        $("#kredit").val( tandaPemisahTitik(sisa_kredit));
        $("#sisa_pembayaran_penjualan").val('0');
        $("#tanggal_jt").attr("disabled", false);
        
        }
        
        else  
        {
        
        
        
        $("#sisa_pembayaran_penjualan").val(tandaPemisahTitik(sisa));
        $("#kredit").val('0');
        $("#tanggal_jt").attr("disabled", true);
        
        } 
        
        
        });
        
        
        });
        </script>

<script>
$(document).ready(function(){

  //id kode pelanggan dari kode_pelanggan
    $("#kd_pelanggan").change(function(){
      var kode_pelanggan = $("#kd_pelanggan").val();

      //id yang di hidden
      $("#k_pelanggan").val(kode_pelanggan);
        
    });
});
</script>


        <script type="text/javascript">
$(document).ready(function(){
    $("#kd_pelanggan").change(function(){
      var kode_pelanggan = $("#kd_pelanggan").val();

      var level_harga = $(".opt-pelanggan-"+kode_pelanggan+"").attr("data-level");



    $("#level_harga").val(level_harga);


    });

      });

          
        </script>

<script>

// BELUM KELAR !!!!!!
$(document).ready(function(){
    $("#carabayar1").change(function(){
      var cara_bayar = $("#carabayar1").val();

      //metode POST untuk mengirim dari file cek_jumlah_kas.php ke dalam variabel "dari akun"
      $.post('cek_jumlah_kas1.php', {cara_bayar : cara_bayar}, function(data) {
        /*optional stuff to do after success */

      $("#jumlah1").val(data);
      });

            var kode_pelanggan = $("#kd_pelanggan").val();

            
            if (kode_pelanggan != ""){
            $("#kd_pelanggan").attr("readonly", true);
            }
            



        
    });
});
</script>

<script>

// untuk memunculkan jumlah kas secara otomatis
  $(document).ready(function(){
    

    $("#pembayaran_penjualan").keyup(function(){
      var jumlah = $("#pembayaran_penjualan").val();
      var carabayar1 = $("#carabayar1").val();

      if (jumlah < 0 || carabayar1 == "") 

      {
          $("#submit").hide();
        alert("Kolom Cara Bayar Masih Kosong");

      }
      else {
        $("#submit").show();
      }


    });

  });
</script>



<script type="text/javascript">
        $(document).ready(function(){
        
        $("#potongan_persen").keyup(function(){

            var potongan_persen = $("#potongan_persen").val();
              var status_bertingkat = potongan_persen.indexOf("+");
              var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total2").val() ))));
           
              var tax = $("#tax").val();
              if (tax == "") {
              tax = 0;
              }   

              if (status_bertingkat > 0) {
                  var diskon_bertingkat = potongan_persen.split("+");
                  var potongan_nominal = 0;
                  var index;
                  var total_kurang_potongan = total;
                  var total_potongan_nominal = 0;
                  for (index = 0; index < diskon_bertingkat.length; ++index) {
                     
                      var diskon_persen = diskon_bertingkat[index];

                      if (diskon_persen != '' || diskon_persen != 0) {
                       total_potongan_nominal = Math.round(total_potongan_nominal) + Math.round(((total_kurang_potongan * diskon_persen) / 100));
                       potongan_nominal = Math.round((total_kurang_potongan * diskon_persen) / 100);
                      var total_kurang_potongan = total_kurang_potongan - parseInt(potongan_nominal,10);
                      }
                    
                      console.log(potongan_nominal);

                  }

                  var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax,10)) / 100);
                  var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);

                  $("#total1").val(tandaPemisahTitik(parseInt(total_akhir)));
                  $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal)));
              } 
              else {

                var potongan_nominal = ((total * potongan_persen) / 100);
                var total_kurang_potongan = total - potongan_nominal;
                var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax,10)) / 100);

                var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);

                    if (potongan_persen > 100) {
                      alert ("Potongan %, Tidak Boleh Lebih Dari 100%");
                      $("#potongan_persen").val('100');
                    }
                    else {

                        $("#total1").val(tandaPemisahTitik(parseInt(total_akhir)));
                        $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(potongan_nominal)));
                    }
                  
              }
      });
        
        $("#tax").keyup(function(){

        var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val() ))));
        var potongan_persen = $("#potongan_persen").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val() ))));
       
              var cara_bayar = $("#carabayar1").val();
              var tax = $("#tax").val();
              var t_total = total - potongan;

              if (tax == "") {
                tax = 0;
              }
              else if (cara_bayar == "") {
                alert ("Kolom Cara Bayar Masih Kosong");
                 $("#tax").val('');
                 $("#potongan_penjualan").val('');
                 $("#potongan_persen").val('');
              }
              
              var t_tax = ((parseInt(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(t_total,10))))) * parseInt(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(tax,10)))))) / 100);

              var total_akhir = parseInt(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(t_total,10))))) + Math.round(parseInt(t_tax,10));
              
              
              $("#total1").val(tandaPemisahTitik(total_akhir));

              if (tax > 100) {
                alert ('Jumlah Tax Tidak Boleh Lebih Dari 100%');
                 $("#tax").val('');

              }
        

        $("#tax_rp").val(parseInt(t_tax));


        });
        });
        
        </script>



      <script type="text/javascript">
      
      $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});  
      
      </script>

<!-- KEMAREN SAMPAI EDIT PENJUALAN < DISINI -->


    <script type="text/javascript">
    $(document).ready(function(){
      
//fungsi hapus data 
$(document).on('click','.btn-hapus-tbs',function(e){

    
    var nama_barang = $(this).attr("data-barang");
    var id = $(this).attr("data-id");
    var kode_barang = $(this).attr("data-kode-barang");
    var subtotal_tbs = $(this).attr("data-subtotal");
    var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
    
    if (total == '') {
          total = 0;
        }

      var potongan_persen = $("#potongan_persen").val();
      var status_bertingkat = potongan_persen.indexOf("+");
      var tax = $("#tax").val();
      if (tax == "") {
        tax = 0;
      }   
      var total_akhir = parseInt(total,10) - parseInt(subtotal_tbs,10);
      total = total_akhir;

      // perhitungan diskon bertingkat 
      if (status_bertingkat > 0) {
            var diskon_bertingkat = potongan_persen.split("+");
            var potongan_nominal = 0;
            var index;
            var total_kurang_potongan = total;
            var total_potongan_nominal = 0;
            for (index = 0; index < diskon_bertingkat.length; ++index) {
               
                var diskon_persen = diskon_bertingkat[index];

                if (diskon_persen != '' || diskon_persen != 0) {
                 total_potongan_nominal =  Math.round(total_potongan_nominal) +  Math.round(((total_kurang_potongan * diskon_persen) / 100));
                 potongan_nominal =  Math.round((total_kurang_potongan * diskon_persen) / 100);
                var total_kurang_potongan = total_kurang_potongan - parseInt(potongan_nominal,10);
                }
              
                console.log( parseInt(potongan_nominal,10));

            }

            var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax,10)) / 100);
            var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);

           
        } 
        else {

          var total_potongan_nominal =  Math.round((total * potongan_persen) / 100);
          var total_kurang_potongan = total - total_potongan_nominal;
          var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax,10)) / 100);

          var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);

              if (potongan_persen > 100) {
                alert ("Potongan %, Tidak Boleh Lebih Dari 100%");
                $("#potongan_persen").val('100');
              }
              else {

                  
              }
            
        }//end diskon bertingkat

    var konfirmasi_hapus = confirm("Apakah Anda yakin ingin Menghapus "+nama_barang);

      if (konfirmasi_hapus == true) {
      $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(potongan_nominal)));
      $("#total1").val(tandaPemisahTitik(total_akhir));
      $("#total2").val(tandaPemisahTitik(total));

    $.post("hapus_edit_tbs_penjualan.php",{id:id,kode_barang:kode_barang},function(data){
    if (data == 'sukses') {


    $(".tr-id-"+id+"").remove();
    $("#pembayaran_penjualan").val('');
    
    }
    });
  }// if (konfirmasi_hapus == true) {



});


$('form').submit(function(){
 return false;
});

});
//end fungsi hapus data
</script>



<script type="text/javascript">
 
$(".btn-alert-hapus").click(function(){
     var no_faktur = $(this).attr("data-faktur");
    var kode_barang = $(this).attr("data-kode");

    $.post('alert_edit_penjualan.php',{no_faktur:no_faktur, kode_barang:kode_barang},function(data){
    
 
    $("#modal_alert").modal('show');
    $("#modal-alert").html(data); 

});

  });
</script>

<!-- AUTOCOMPLETE -->

<script>
$(function() {
    $( "#kode_barang" ).autocomplete({
        source: 'kode_barang_autocomplete.php'
    });
});
</script>

<!-- AUTOCOMPLETE -->



<!-- KEMAREN SAMPAI EDIT PENJUALAN < DISINI -->
<script type="text/javascript">
  
        $(document).ready(function(){
        $("#kode_barang").blur(function(){

          var no_faktur = $("#nomor_faktur_penjualan").val();
          var kode_barang = $(this).val();
          var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));

          var level_harga = $("#level_harga").val();


          $.post("cek_barang_penjualan.php",
          {
          kode_barang:kode_barang
          },
          function(data){
          $("#jumlahbarang").val(data);
          });



            $.post("cek_jumlah_tbs.php",
            {
            no_faktur:no_faktur,kode_barang:kode_barang
            },
            function(data){
            $("#jumlah_barang_tbs").val(data);
            });


          

          $.post('cek_kode_barang_edit_tbs_penjualan.php',{kode_barang:kode_barang,no_faktur:no_faktur}, function(data){
          
          if(data == 1){
          alert("Barang Yang Anda Masukan Sudah Ada");
          $("#kode_barang").val('');
          $("#nama_barang").val('');
          }//penutup if
          
          });////penutup function(data)

      $.getJSON('lihat_nama_barang.php',{kode_barang:kode_barang}, function(json){
      
      if (json == null)
      {
        
        $('#nama_barang').val('');
        $('#harga_produk').val('');
        $('#limit_stok').val('');
        $('#satuan_produk').val('');
       

      }

      else 
      {

        if (level_harga == "Level 1") {

        $('#harga_produk').val(json.harga_jual);
        }
        else if (level_harga == "Level 2") {

        $('#harga_produk').val(json.harga_jual2);
        }
        else if (level_harga == "Level 3") {

        $('#harga_produk').val(json.harga_jual3);
        }

        $('#nama_barang').val(json.nama_barang);
        $('#ber_stok').val(json.berkaitan_dgn_stok);
        $('#limit_stok').val(json.limit_stok);
        $('#satuan_produk').val(json.satuan);
       
      }
                                              
        });
        
        });
        });

      
      
</script>

<script type="text/javascript">
  
   $(document).ready(function(){

      var kode_pelanggan = $("#kd_pelanggan").val();

      var level_harga = $(".opt-pelanggan-"+kode_pelanggan+"").attr("data-level");

        
        if(kode_pelanggan == 'Umum')
        {
        $("#level_harga").val('Level 1');
        }

        else 
        {
        $("#level_harga").val(level_harga);
        }

   });


</script>


                            <script type="text/javascript">
                                 $(document).on('dblclick','.edit-jumlah',function(e){

                                    var id = $(this).attr("data-id");

                                    $("#text-jumlah-"+id+"").hide();

                                    $("#input-jumlah-"+id+"").attr("type", "text");

                                 });

                                 $(document).on('blur','.input_jumlah',function(e){

                                    var id = $(this).attr("data-id");
                                    var jumlah_baru = $(this).val();
                                    var kode_barang = $(this).attr("data-kode");
                                    var harga = $(this).attr("data-harga");
                                    var jumlah_lama = $("#text-jumlah-"+id+"").text();
                                    var potongan_persen = $("#potongan_persen").val();
                                     var satuan_konversi = $(this).attr("data-satuan");
                                     var no_faktur = $("#no_faktur0").val();
                                   var ber_stok       = $(this).attr("data-berstok");


                                    var subtotal_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-subtotal-"+id+"").text()))));
                                   
                                    var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-potongan-"+id+"").text()))));

                                    var tax_fak   = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));

                                    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-tax-"+id+"").text()))));
                                   

                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
                                    
                                    var subtotal = harga * jumlah_baru - potongan;
                                    var tax_tbs = tax / subtotal_lama * 100;
                                    var jumlah_tax = tax_tbs * subtotal / 100;

                                     var potongan_persen  = $("#potongan_persen").val();
                                     var status_bertingkat = potongan_persen.indexOf("+");

                                  // subtotal penjualan baru
                           subtotal_penjualan= subtotal_penjualan - subtotal_lama + subtotal;
                           var total = subtotal_penjualan;
                          //perhitungan diskon bertingkat 
                   if (status_bertingkat > 0) {
                              var diskon_bertingkat = potongan_persen.split("+");
                              var potongan_nominal = 0;
                              var index;
                              var total_kurang_potongan = total;
                              var total_potongan_nominal = 0;
                              for (index = 0; index < diskon_bertingkat.length; ++index) {
                   
                            var diskon_persen = diskon_bertingkat[index];

                           if (diskon_persen != '' || diskon_persen != 0) {
                           total_potongan_nominal =  Math.round(total_potongan_nominal) +  Math.round(((total_kurang_potongan * diskon_persen) / 100));
                             potongan_nominal =  Math.round((total_kurang_potongan * diskon_persen) / 100);
                          var total_kurang_potongan = total_kurang_potongan - parseInt(potongan_nominal,10);
                          }
                  
                          console.log(potongan_nominal);
                        }

                          var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax,10)) / 100);
                          var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);
                  } 
                  else {

                        var total_potongan_nominal =  Math.round((total * potongan_persen) / 100);
                        var total_kurang_potongan = total - total_potongan_nominal;
                        var t_tax = ((parseInt(total_kurang_potongan,10) * parseInt(tax,10)) / 100);

                        var total_akhir = parseInt(total_kurang_potongan, 10) + parseInt(t_tax,10);

                      if (potongan_persen > 100) {
                        alert ("Potongan %, Tidak Boleh Lebih Dari 100%");
                          $("#potongan_persen").val('100');
                      }
                       else {

                        }  
                    }
                                  if (ber_stok == 'Jasa') {

                                     $.post("update_pesanan_barang.php",{jumlah_lama:jumlah_lama,tax:tax,id:id,jumlah_baru:jumlah_baru,kode_barang:kode_barang,potongan:potongan,harga:harga,jumlah_tax:jumlah_tax,subtotal:subtotal},function(info){
      
                                    $("#text-jumlah-"+id+"").show();
                                    $("#text-jumlah-"+id+"").text(jumlah_baru);
                                    $("#text-subtotal-"+id+"").text(tandaPemisahTitik(subtotal));
                                    $("#text-tax-"+id+"").text(jumlah_tax);
                                    $("#input-jumlah-"+id+"").attr("type", "hidden"); 
                                    $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal))); 
                                    $("#total2").val(tandaPemisahTitik(subtotal_penjualan));
                                    $("#total1").val(tandaPemisahTitik(total_akhir));         

                                    });

                                   }

                                   else{


                                    $.post("cek_stok_edit_penjualan.php",{kode_barang:kode_barang, jumlah_baru:jumlah_baru,satuan_konversi:satuan_konversi,no_faktur:no_faktur},function(data){

                                       if (data < 0) {

                                       alert ("Jumlah Yang Di Masukan Melebihi Stok !");

                                       $("#input-jumlah-"+id+"").val(jumlah_lama);
                                       $("#text-jumlah-"+id+"").text(jumlah_lama);
                                       $("#text-jumlah-"+id+"").show();
                                       $("#input-jumlah-"+id+"").attr("type", "hidden");

                                     }

                                      else{

                                     $.post("update_pesanan_barang.php",{jumlah_lama:jumlah_lama,tax:tax,id:id,jumlah_baru:jumlah_baru,kode_barang:kode_barang,potongan:potongan,harga:harga,jumlah_tax:jumlah_tax,subtotal:subtotal},function(info){
      
                                    $("#text-jumlah-"+id+"").show();
                                    $("#text-jumlah-"+id+"").text(jumlah_baru);
                                    $("#text-subtotal-"+id+"").text(tandaPemisahTitik(subtotal));
                                    $("#text-tax-"+id+"").text(jumlah_tax);
                                    $("#input-jumlah-"+id+"").attr("type", "hidden"); 
                                    $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal))); 
                                    $("#total2").val(tandaPemisahTitik(subtotal_penjualan));
                                    $("#total1").val(tandaPemisahTitik(total_akhir));         

                                    });

                                   }

                                 });//END cek_stok_edit_penjualan

                                }
       
                                    $("#kode_barang").focus();
                                    

                                 });
                             </script>


<script type="text/javascript">
  
                                      $(".edit-jumlah-alert").dblclick(function(){

                                      var no_faktur = $(this).attr("data-faktur");
                                      var kode_barang = $(this).attr("data-kode");
                                      
                                      $.post('alert_edit_penjualan.php',{no_faktur:no_faktur, kode_barang:kode_barang},function(data){
                                      
                                        $("#modal_alert").modal('show');
                                        $("#modal-alert").html(data);
              
                                      });
                                    });
</script>

<script type="text/javascript">
    $(document).ready(function(){

    $("#tax1").attr("disabled", true);
      $("#tax").attr("disabled", true);

    $("#ppn").change(function(){

    var ppn = $("#ppn").val();
    $("#ppn_input").val(ppn);

  if (ppn == "Include"){

      $("#tax").attr("disabled", true);
      $("#tax1").attr("disabled", false);
  }

  else if (ppn == "Exclude") {
    $("#tax1").attr("disabled", true);
      $("#tax").attr("disabled", false);
  }
  else{

    $("#tax1").attr("disabled", true);
      $("#tax").attr("disabled", true);
  }


  });
  });
</script>



<script> 
    shortcut.add("f2", function() {
        // Do something

        $("#kode_barang").focus();

    });

    
    shortcut.add("f1", function() {
        // Do something

        $("#cari_produk_penjualan").click();

    }); 

    
    shortcut.add("f3", function() {
        // Do something

        $("#submit_produk").click();

    }); 

    
    shortcut.add("f4", function() {
        // Do something

        $("#carabayar1").focus();

    }); 

    
    shortcut.add("f7", function() {
        // Do something

        $("#pembayaran_penjualan").focus();

    }); 

    
    shortcut.add("f8", function() {
        // Do something

        $("#penjualan").click();

    }); 

    
    shortcut.add("f9", function() {
        // Do something

        $("#piutang").click();

    }); 

    
    shortcut.add("f10", function() {
        // Do something

        $("#simpan_sementara").click();

    }); 

    
    shortcut.add("ctrl+b", function() {
        // Do something

    var session_id = $("#session_id").val()

        window.location.href="batal_penjualan.php?session_id="+session_id+"";


    }); 

     shortcut.add("ctrl+k", function() {
        // Do something

        $("#cetak_langsung").click();


    }); 
</script>



<script type="text/javascript">
  $(document).ready(function(){
    var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
    var total =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() ))));
    var sisa = pembayaran - total;
    var sisa_kredit = total - pembayaran; 
        
      if (sisa < 0 ){
        $("#kredit").val( tandaPemisahTitik(sisa_kredit));
        $("#sisa_pembayaran_penjualan").val('0');
        $("#tanggal_jt").attr("disabled", false);
        
      }
      else{
        $("#sisa_pembayaran_penjualan").val(tandaPemisahTitik(sisa));
        $("#kredit").val('0');
        $("#tanggal_jt").attr("disabled", true);
        
      } 
        
        
  });     
</script>

<!-- memasukan file footer.php -->
<?php include 'footer.php'; ?>