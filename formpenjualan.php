  <?php include_once 'session_login.php';
 

// memasukan file session login,  header, navbar, db.php,
include 'header.php';
include 'navbar.php';
include 'db.php';
include 'sanitasi.php';

 


$pilih_akses_kolom = $db->query("SELECT harga_produk_penjualan FROM otoritas_penjualan WHERE id_otoritas = '$_SESSION[otoritas_id]' ");
$otoritas_kolom = mysqli_fetch_array($pilih_akses_kolom);


$session_id = session_id();
 ?>

<!-- js untuk tombol shortcut -->
 <script src="shortcut.js"></script>
<!-- js untuk tombol shortcut -->


 <style type="text/css">
  .disabled {
    opacity: 0.6;
    cursor: not-allowed;
    disabled: true;
}
</style>


<script>
  $(function() {
    $( "#tanggal_jt" ).datepicker({dateFormat: "yy-mm-dd"});
  });
  </script>

<!--untuk membuat agar tampilan form terlihat rapih dalam satu tempat -->
<div style="padding-left: 5%; padding-right: 2%">
  <h3> FORM PENJUALAN </h3>
<div class="row">

<div class="col-sm-8">


 <!-- membuat form menjadi beberpa bagian -->
  <form enctype="multipart/form-data" role="form" action="formpenjualan.php" method="post ">
        
  <!--membuat teks dengan ukuran h3-->      

        <div class="form-group">
        <input type="hidden" name="session_id" id="session_id" class="form-control" value="<?php echo session_id(); ?>" readonly="">
        </div>

<div class="row">

<div class="col-sm-4">
    <label> Kode Pelanggan </label><br>
  <select name="kode_pelanggan" id="kd_pelanggan" class="form-control chosen" required="" autofocus="">
 
          
  <?php 
    
    //untuk menampilkan semua data pada tabel pelanggan dalam DB
    $query_pelanggan = $db->query("SELECT default_pelanggan,
kode_pelanggan,
level_harga,
nama_pelanggan FROM pelanggan");

    //untuk menyimpan data sementara yang ada pada $query
    while($data_pelanggan = mysqli_fetch_array($query_pelanggan))
    {
            if ($data_pelanggan['default_pelanggan'] == '1') {

    echo "<option selected value='".$data_pelanggan['kode_pelanggan'] ."' class='opt-pelanggan-".$data_pelanggan['kode_pelanggan']."' data_pelanggan-level='".$data_pelanggan['level_harga'] ."'>".$data_pelanggan['kode_pelanggan'] ." - ".$data_pelanggan['nama_pelanggan'] ."</option>";
              
            }

            else{

    echo "<option value='".$data_pelanggan['kode_pelanggan'] ."' class='opt-pelanggan-".$data_pelanggan['kode_pelanggan']."' data_pelanggan-level='".$data_pelanggan['level_harga'] ."'>".$data_pelanggan['kode_pelanggan'] ." - ".$data_pelanggan['nama_pelanggan'] ."</option>";

            }
    }
    
    
    ?>
    </select><br>
<label>Sisa Plafon </label>
    <input type="text" name="sisa_plafon"  id="sisa_plafon" class="form-control">
</div>
    

<div class="col-sm-2">
          <label class="gg" > Gudang </label><br>
          
          <select style="font-size:15px; height:35px" name="kode_gudang" id="kode_gudang" class="form-control gg" required="" >
          <?php 
          
      
          $query_gudang = $db->query("SELECT default_sett,
kode_gudang,
nama_gudang FROM gudang");
          

          while($data_gudang = mysqli_fetch_array($query_gudang))
          {

            if ($data_gudang['default_sett'] == '1') {

                echo "<option selected value='".$data_gudang['kode_gudang'] ."'>".$data_gudang['nama_gudang'] ."</option>";
              
            }

            else{

                echo "<option value='".$data_gudang['kode_gudang'] ."'>".$data_gudang['nama_gudang'] ."</option>";

            }
          
          }
          
          
          ?>
          </select>
</div>

<div class="col-sm-2">
    <label> Level Harga </label><br>
  <select style="font-size:15px; height:35px" type="text" name="level_harga" id="level_harga" class="form-control" required="" >
  <option>Level 1</option>
  <option>Level 2</option>
  <option>Level 3</option>

    </select>
    </div>


<div class="col-sm-2">
<label class="gg" >Sales</label>
<select style="font-size:15px; height:35px" name="sales" id="sales" class="form-control gg" required="">

  <?php 
    
    //untuk menampilkan semua data pada tabel pelanggan dalam DB
    $query01 = $db->query("SELECT nama,default_sales FROM user WHERE status_sales = 'Iya'");

    //untuk menyimpan data sementara yang ada pada $query
    while($data01 = mysqli_fetch_array($query01))
    {
    
    if ($data01['default_sales'] == '1') {

    echo "<option selected value='".$data01['nama'] ."'>".$data01['nama'] ."</option>";
      
    }
    else{

    echo "<option value='".$data01['nama'] ."'>".$data01['nama'] ."</option>";

    }
    }
    
    
    ?>

</select>
</div>

<div class="col-sm-2">
          <label class="gg">PPN</label>
          <select type="hidden" style="font-size:15px; height:35px" name="ppn" id="ppn" class="form-control gg">
             <option value="Non">Non</option>    
            <option value="Include">Include</option>  
            <option value="Exclude">Exclude</option>
                 
          </select>
</div>

</div>  <!-- END ROW dari kode pelanggan - ppn -->


  </form><!--tag penutup form-->
  
  

<button type="button" id="cari_produk_penjualan" class="btn btn-info " data-toggle="modal" data-target="#myModal"><i class='fa  fa-search'> Cari (F1)</i>  </button> 


<!--tampilan modal-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog ">

    <!-- isi modal-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data Barang</h4>
      </div>
      <div class="modal-body">

      <div class="table-resposive">
<div class="table-responsive">

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
  </table>

</div>
      </div>
    </div> <!-- tag penutup modal-body-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><!-- end of modal data barang  -->



<!-- Modal Hapus data -->
<div id="modal_usia_plafon" class="modal " role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Penjualan Yang Sudah Melewati Batas Usia Plafon</h4>
      </div>
      <div class="modal-body">
            <table class="table table-bordered" id="table-jatuh-tempo">
              <thead>
                <th>Tanggal</th>
                <th>No Faktur</th>
                <th>Total</th>
                <th>Sisa Piutang</th>
                <th>Jatuh Tempo</th>
              </thead>
              <tbody id="tbody-jatuh-tempo"> 
                
              </tbody>
            </table>

 

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal"> <span class='glyphicon glyphicon-remove-sign'> </span>Tutup</button>
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
        <h4 class="modal-title">Edit Data Penjualan Barang</h4>
      </div>
      <div class="modal-body">
  <form role="form">
   <div class="form-group">
    <label for="email">Jumlah Baru:</label>
     <input type="text" class="form-control" autocomplete="off" id="barang_edit"><br>
     <label for="email">Jumlah Lama:</label>
     <input type="text" class="form-control" id="barang_lama" readonly="">
     <input type="hidden" class="form-control" id="harga_edit" readonly="">
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
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><!-- end of modal edit data  -->

<style>

  tr:nth-child(even){background-color: #f2f2f2}

</style>

<!-- MODAL PRODUK STOK HABIS -->
<div id="modal_barang_tidak_bisa_dijual" class="modal " role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Produk Yang Tidak Bisa Di Jual</h4>
      </div>
      <div class="modal-body">
            <center>
            <table class="table table-bordered table-sm">
                  <thead> <!-- untuk memberikan nama pada kolom tabel -->

                      <th style='background-color: #4CAF50; color: white;'>Kode Produk</th>
                      <th style='background-color: #4CAF50; color: white;'>Nama Produk</th>
                      <th style='background-color: #4CAF50; color: white;'>Jumlah Yang Akan Di Jual</th>
                      <th style='background-color: #4CAF50; color: white;'>Stok Saat Ini</th>
                  
                  
                  </thead> <!-- tag penutup tabel -->
                  <tbody id="tbody-barang-jual">
                    
                  </tbody>
            </table>
            </center>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><!-- END OF MODAL PRODUK STOK HABIS  -->


<!-- membuat form prosestbspenjual -->

<form id="form_barcode" class="form-inline">
  <br>
    <div class="form-group">
        <input type="text" style="height:15px" name="kode_barcode" id="kode_barcode" class="form-control" placeholder="Kode Barcode">
    </div>
        
    <button type="submit" id="submit_barcode" class="btn btn-primary" style="font-size:15px" ><i class="fa fa-barcode"></i> Submit Barcode</button>
        
    
        
  </form>

          <div class="alert alert-danger" id="alert_stok" style="display:none">
          <strong>Perhatian!</strong> Persediaan Barang Tidak Cukup!
          </div>

  
<form class="form"  role="form" id="formtambahproduk">

<div class="row">

  <div class="col-sm-3">

    <input type="text" style="height:15px" class="form-control" name="kode_barang" autocomplete="off" id="kode_barang" placeholder="Kode Barang" >

  </div>


    <input type="hidden" class="form-control" name="nama_barang" autocomplete="off" id="nama_barang" placeholder="nama" >

  <div class="col-sm-2">
    <input style="height:15px;" type="text" class="form-control" name="jumlah_barang" autocomplete="off" id="jumlah_barang" placeholder="Jumlah" >
  </div>

  <div class="col-sm-1">
          
          <select style="font-size:15px; height:35px" type="text" name="satuan_konversi" id="satuan_konversi" class="form-control"  required="">
          
          <?php 
          
          
          $query = $db->query("SELECT id, nama  FROM satuan");
          while($data = mysqli_fetch_array($query))
          {
          
          echo "<option value='".$data['id']."'>".$data['nama'] ."</option>";
          }
                      
          ?>
          
          </select>
  </div>

<?php if ($otoritas_kolom['harga_produk_penjualan'] > 0): ?>
  <div class="col-sm-2">
    <input style="height:15px;" type="text" class="form-control" name="harga" autocomplete="off" id="harga_baru" placeholder="Harga Produk">
  </div>

<?php else: ?>

    <input style="height:15px;" type="hidden" class="form-control" name="harga" autocomplete="off" id="harga_baru" placeholder="Harga Produk">


<?php endif ?>


   <div class="col-sm-1">
    <input style="height:15px;" type="text" class="form-control" name="potongan" autocomplete="off" id="potongan1" data-toggle="tooltip" data-placement="top" title="Jika Ingin Potongan Dalam Bentuk Persen (%), input : 10%" placeholder="Disc.">
  </div>

   <div class="col-sm-1">
    <input style="height:15px;" type="text" class="form-control" name="tax" autocomplete="off" id="tax1" placeholder="Tax%" >
  </div>

  <div class="col-sm-2">
  <button type="submit" id="submit_produk" class="btn btn-success" style="font-size:15px" > <i class="fa fa-plus"></i>Submit (F3)</button>
  </div>

</div>

  <input type="hidden" class="form-control" name="limit_stok" autocomplete="off" id="limit_stok" placeholder="Limit Stok" >
    <input type="hidden" class="form-control" name="ber_stok" id="ber_stok" placeholder="Ber Stok" >
    <input type="hidden" class="form-control" name="harga_lama" id="harga_lama">
    <input type="hidden" class="form-control" name="harga_produk" id="harga_produk">
    <input type="hidden" class="form-control" name="jumlahbarang" id="jumlahbarang">
    <input type="hidden" id="satuan_produk" name="satuan" class="form-control" value="" required="">
    <input type="hidden" id="id_produk" name="id_produk" class="form-control" value="" required="">        

</form> <!-- tag penutup form -->


                <!--untuk mendefinisikan sebuah bagian dalam dokumen-->  
                <span id='tes'></span>            
                
                <div class="table-responsive"> <!--tag untuk membuat garis pada tabel-->  
                <span id="table-baru">  
                <table id="tabel_tbs_penjualan" class="table table-sm">
                <thead>
                <th> Kode  </th>
                <th style="width:1000%"> Nama </th>
                <th> Jumlah </th>
                <th> Satuan </th>
                <th align="right"> Harga </th>
                <th align="right"> Potongan</th>
                <th align="right"> Pajak </th>
                <th align="right"> Subtotal </th>
                <th> Hapus </th>
                
                </thead>
                
                </table>
                </span>
                </div>
                <h6 style="text-align: left ; color: red"><i> * Klik 2x pada kolom jumlah barang jika ingin mengedit.</i></h6>
                <h6 style="text-align: left ;"><i><b> * Short Key (F2) untuk mencari Kode Produk atau Nama Produk.</b></i></h6>
<?php 
$hud = $db->query("SELECT setting_tampil FROM setting_antrian");
$my = mysqli_fetch_array($hud);

if ($my['setting_tampil'] == 'Tampil')
{
?>
<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class='fa fa-list-ol'> </i>
Antrian  </button>
</p>

<style>


tr:nth-child(even){background-color: #f2f2f2}


</style>

<?php
}
?>

  




</div> <!-- / END COL SM 6 (1)-->


<div class="col-sm-4">

<form action="proses_bayar_jual.php" id="form_jual" method="POST" >
    
    <style type="text/css">
    .disabled {
    opacity: 0.6;
    cursor: not-allowed;
    disabled: false;
    }
    </style>

  <div class="form-group">
    <div class="card card-block">
      

      <div class="row">
        <div class="col-sm-6">
          
           <label style="font-size:15px"> <b> Subtotal </b></label><br>
      <input style="height:10px;font-size:15px" type="text" name="total" id="total2" class="form-control" placeholder="Total" readonly="" >

        </div>

                  <?php
                  $ambil_diskon_tax = $db->query("SELECT diskon_nominal,diskon_persen,tax FROM setting_diskon_tax");
                  $data_diskon = mysqli_fetch_array($ambil_diskon_tax);

                  ?>

         <div class="col-sm-6">


          <label> Diskon ( Rp )</label><br>
          <input type="text" name="potongan" style="height:10px;font-size:15px" id="potongan_penjualan" value="<?php echo $data_diskon['diskon_nominal']; ?>" class="form-control" placeholder="" autocomplete="off"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
            
          </div>


      </div>
      

          
          <div class="row">

          <div class="col-sm-6">
            <label> Diskon ( % )</label><br>
          <input type="text" name="potongan_persen" style="height:10px;font-size:15px" id="potongan_persen" value="<?php echo $data_diskon['diskon_persen']; ?>" class="form-control" placeholder="" autocomplete="off" >
          </div>

            <div class="col-sm-6">


           <label> Pajak (%)</label>
           <input type="text" name="tax" id="tax" style="height:10px;font-size:15px" value="<?php echo $data_diskon['tax']; ?>" style="height:10px;font-size:15px" class="form-control" autocomplete="off" >

           </div>

          </div>
          

          <div class="row">

           <input type="hidden" name="tax_rp" id="tax_rp" class="form-control"  autocomplete="off" >
           
           <label style="display: none"> Adm Bank  (%)</label>
           <input type="hidden" name="adm_bank" id="adm_bank"  value="" class="form-control" >
           
           <div class="col-sm-6">
             
           <label> Tanggal</label>
           <input type="text" name="tanggal_jt" id="tanggal_jt"  value="" style="height:10px;font-size:15px" placeholder="Tanggal JT" class="form-control" >

           </div>


        <div class="col-sm-6">
            <label style="font-size:15px"> <b> Cara Bayar (F4) </b> </label><br>
                      <select type="text" name="cara_bayar" id="carabayar1" class="form-control" required=""  style="font-size: 15px" >
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

    
           
           
      <div class="form-group">
      <div class="row">
       
        <div class="col-sm-6">

           <label style="font-size:15px"> <b> Total Akhir </b></label><br>
           <b><input type="text" name="total" id="total1" class="form-control" style="height: 25px; width:90%; font-size:20px;" placeholder="Total" readonly="" ></b>
          
        </div>
 
            <div class="col-sm-6">
              
           <label style="font-size:15px">  <b> Pembayaran (F7)</b> </label><br>
           <b><input type="text" name="pembayaran" id="pembayaran_penjualan" style="height: 20px; width:90%; font-size:20px;" autocomplete="off" class="form-control"   style="font-size: 20px"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"></b>

            </div>
      </div>
           
           
          <div class="row">
            <div class="col-sm-6">
              
           <label> Kembalian </label><br>
           <b><input type="text" name="sisa_pembayaran"  id="sisa_pembayaran_penjualan"  style="height:10px;font-size:15px" class="form-control"  readonly="" required=""></b>
            </div>

            <div class="col-sm-6">
              
          <label> Kredit </label><br>
          <b><input type="text" name="kredit" id="kredit" class="form-control"  style="height:10px;font-size:15px"  readonly="" required="" ></b>
            </div>
          </div> 
          


           
           <label> Keterangan </label><br>
           <textarea style="height:40px;font-size:15px" type="text" name="keterangan" id="keterangan" class="form-control"> 
           </textarea>
 
          <?php 
          
          if ($_SESSION['otoritas'] == 'Pimpinan') {
          echo '<label style="display:none"> Total Hpp </label><br>
          <input type="hidden" name="total_hpp" id="total_hpp" style="height: 50px; width:90%; font-size:25px;" class="form-control" placeholder="" readonly="" required="">';
          }
          
          
          //Untuk Memutuskan Koneksi Ke Database
          mysqli_close($db);   
          ?>



      </div><!-- END card-block -->

       </div>

          
          
          <input style="height:15px" type="hidden" name="jumlah" id="jumlah1" class="form-control" placeholder="jumlah">
          
          
          <!-- memasukan teks pada kolom kode pelanggan, dan nomor faktur penjualan namun disembunyikan -->

          
          <input type="hidden" name="kode_pelanggan" id="k_pelanggan" class="form-control" required="" >
          <input type="hidden" name="ppn_input" id="ppn_input" value="Include" class="form-control" placeholder="ppn input">  
      

          <div class="row">
 
            
          <button type="submit" id="penjualan" class="btn btn-info" style="font-size:15px">Bayar (F8)</button>
          <a class="btn btn-info" href="formpenjualan.php" id="transaksi_baru" style="display: none">  Transaksi Baru </a>
          
        

          
            
          <button type="submit" id="piutang" class="btn btn-warning" style="font-size:15px">Piutang (F9)</button>

          <a href='cetak_penjualan_piutang.php' id="cetak_piutang" style="display: none;" class="btn btn-success" target="blank">Cetak Piutang  </a>

     

            
          <button type="submit" id="simpan_sementara" class="btn btn-primary" style="font-size:15px">  Simpan (F10)</button>
          <a href='cetak_penjualan_tunai.php' id="cetak_tunai" style="display: none;" class="btn btn-primary" target="blank"> Cetak Tunai  </a>

          <button type="submit" id="cetak_langsung" target="blank" class="btn btn-success" style="font-size:15px"> Bayar / Cetak (Ctrl + K) </button>

          <a href='cetak_penjualan_tunai_besar.php' id="cetak_tunai_besar" style="display: none;" class="btn btn-warning" target="blank"> Cetak Tunai Besar </a>

          <a href='cetak_penjualan_surat_jalan.php' id="cetak_surat_jalan" style="display: none;" class="btn btn-danger" target="blank"> Cetak Surat Jalan </a>
          
     
    
          <br>
          </div> <!--row 3-->
          
          <div class="alert alert-success" id="alert_berhasil" style="display:none">
          <strong>Success!</strong> Pembayaran Berhasil
          </div>
     

    </form>


</div><!-- / END COL SM 6 (2)-->


</div><!-- end of row -->

</div><!-- end of container -->

<script type="text/javascript">
 (function(seconds) {
    var refresh,       
        intvrefresh = function() {
            clearInterval(refresh);
            refresh = setTimeout(function() {
               location.href ="penjualan.php?status=semua";
            }, seconds * 1000);
        };

    $(document).on('keypress click', function() { intvrefresh() });
    intvrefresh();

}(300)); // define here seconds

</script>

    
<script>
//untuk menampilkan data tabel
$(document).ready(function(){
    $("#kode_barang").focus();

});

</script>




<script type="text/javascript" language="javascript" >
   $(document).ready(function() {

        var dataTable = $('#tabel_cari').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"modal_jual_baru.php", // json datasource
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
              url :"datatable_tbs_penjualan.php", // json datasource
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


<!--untuk memasukkan perintah java script-->
<script type="text/javascript">

// jika dipilih, nim akan masuk ke input dan modal di tutup
  $(document).on('click', '.pilih', function (e) {


  document.getElementById("kode_barang").value = $(this).attr('data-kode');
  document.getElementById("nama_barang").value = $(this).attr('nama-barang');
  document.getElementById("limit_stok").value = $(this).attr('limit_stok');
  document.getElementById("satuan_produk").value = $(this).attr('satuan');
  document.getElementById("ber_stok").value = $(this).attr('ber-stok');
  document.getElementById("satuan_konversi").value = $(this).attr('satuan');
  document.getElementById("id_produk").value = $(this).attr('id-barang');



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

  document.getElementById("jumlahbarang").value = $(this).attr('jumlah-barang');


  $('#myModal').modal('hide'); 
  $("#jumlah_barang").focus();


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

if (kode_barang != '')
{
  $.post("cek_level_harga_barang.php",
        {level_harga:level_harga, kode_barang:kode_barang,jumlah_barang:jumlah_barang,id_produk:id_produk,satuan_konversi:satuan_konversi},function(data){

          $("#harga_produk").val(data);
          $("#harga_baru").val(data);
        });
}


    });
});
//end cek level harga
</script>



<!-- cek stok satuan konversi change-->
<script type="text/javascript">
  $(document).ready(function(){
    $("#satuan_konversi").change(function(){
      var jumlah_barang = $("#jumlah_barang").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var kode_barang = $("#kode_barang").val();
      var ber_stok = $("#ber_stok").val();
      var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
      var id_produk = $("#id_produk").val();
      var prev = $("#satuan_produk").val();
      
      
        if (ber_stok != 'Jasa'){
      $.post("cek_stok_konversi_penjualan.php", {jumlah_barang:jumlah_barang,satuan_konversi:satuan_konversi,kode_barang:kode_barang,id_produk:id_produk},function(data){


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
      var prev = $("#satuan_produk").val();

      if (ber_stok != 'Jasa'){
      $.post("cek_stok_konversi_penjualan.php",
        {jumlah_barang:jumlah_barang,satuan_konversi:satuan_konversi,kode_barang:kode_barang,
        id_produk:id_produk},function(data){


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

      

      $.getJSON("cek_konversi_penjualan.php",{kode_barang:kode_barang,satuan_konversi:satuan_konversi,id_produk:id_produk,harga_produk:harga_produk,jumlah_barang:jumlah_barang},function(info){



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




      <script type="text/javascript">
      
      $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});  
      
      </script>



    <script>
   
   //untuk menampilkan data yang diambil pada form tbs penjualan berdasarkan id=formtambahproduk
  $("#submit_barcode").click(function(){

    var kode_barang = $("#kode_barcode").val();
    var level_harga = $("#level_harga").val();
    var sales = $("#sales").val();


   $("#jumlah_barang").val('');
   $("#kode_barcode").val('');
   $("#potongan1").val('');
   $("#tax1").val('');

$.get("cek_barang.php",{kode_barang:kode_barang},function(data){
if (data != 1) {

alert("Barang Yang Anda Pesan Tidak Tersedia !!")

}

else{
$("#kode_barcode").focus();
$.post("barcode.php",{kode_barang:kode_barang,sales:sales,level_harga:level_harga},function(data){

        $(".tr-kode-"+kode_barang+"").remove();
        $("#ppn").attr("disabled", true);
        $("#kode_barang").val('');
        $("#nama_barang").val('');
        $("#harga_produk").val('');
        $("#ber_stok").val('');
        $("#jumlah_barang").val('');
        $("#potongan1").val('');
        $("#tax1").val('');
      
  //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_tbs_penjualan.php", // json datasource
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

        
        $.get("cek_total_seluruh.php",
        function(data){
        $("#total2").val(data);
        $("#total1").val(data);

        });

});


     
     });
     
     $("#form_barcode").submit(function(){
    return false;
    
    });

 </script>  

   <script>
   //untuk menampilkan data yang diambil pada form tbs penjualan berdasarkan id=formtambahproduk
  $(document).on('click', '#submit_produk', function (e) {
    // data untuk produk
    var no_faktur = $("#nomor_faktur_penjualan").val();
    var kode_pelanggan = $("#kd_pelanggan").val();
    var kode_barang = $("#kode_barang").val();
    var n = kode_barang.indexOf("(");
    if (n > 0)
    {
    var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
    }
    var nama_barang = $("#nama_barang").val();
    var jumlah_barang = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlah_barang").val()))));
    var level_harga = $("#level_harga").val();
    var harga = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#harga_produk").val()))));
    var harga_baru = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#harga_baru").val()))));
    var potongan = $("#potongan1").val();
    if (potongan == '') {
      potongan = 0;

    }
    var tax = $("#tax1").val();
    var jumlahbarang = $("#jumlahbarang").val();
    var satuan = $("#satuan_konversi").val();
    var sales = $("#sales").val();
    var a = $(".tr-kode-"+kode_barang+"").attr("data-kode-barang");    
    var ber_stok = $("#ber_stok").val();
    var ppn = $("#ppn").val();
    var stok = parseInt(jumlahbarang,10) - parseInt(jumlah_barang,10);

   var subtotal = parseInt(jumlah_barang, 10) *  parseInt(harga_baru, 10) - parseInt(potongan, 10);

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
              
                console.log(potongan_nominal);

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
          console.log(total_akhir);
              if (potongan_persen > 100) {
                alert ("Potongan %, Tidak Boleh Lebih Dari 100%");
                $("#potongan_persen").val('100');
              }
              else {

                 
                  
              }
            
    } // end diskon bertingkat
    
    

    
     $("#jumlah_barang").val('');
     $("#potongan1").val('');
     $("#tax1").val('');



  if (a > 0){
  alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
  }


  else if (jumlah_barang == ''){
  alert("Jumlah Barang Harus Diisi");
       $("#jumlah_barang").focus();


  }
  else if (kode_pelanggan == ''){
  alert("Kode Pelanggan Harus Dipilih");
         $("#kd_pelanggan").focus();

  }
  else if (ber_stok == 'Jasa' ){



$("#kode_barang").focus();

   $("#total1").val(tandaPemisahTitik(total_akhir));
    $("#total2").val(tandaPemisahTitik(subtotal_penjualan));
  $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal)));
 $.post("prosestbspenjualan.php",{no_faktur:no_faktur,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga:harga,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,sales:sales, level_harga:level_harga,ber_stok:ber_stok},function(data){
     
  //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_tbs_penjualan.php", // json datasource
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

     $("#ppn").attr("disabled", true);
     $("#kode_barang").val('');
     $("#nama_barang").val('');
     $("#harga_produk").val('');
     $("#ber_stok").val('');
     $("#jumlah_barang").val('');
     $("#potongan1").val('');
     $("#tax1").val('');
     
     });


  
  } 
  else if (stok < 0) {

    alert ("Jumlah Melebihi Stok Barang !");

  }

  else{

    $("#kode_barang").focus();

    $("#total1").val(tandaPemisahTitik(total_akhir));
    $("#total2").val(tandaPemisahTitik(subtotal_penjualan));
    $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal)));

    $.post("prosestbspenjualan.php",{no_faktur:no_faktur,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga:harga,harga_baru:harga_baru,level_harga:level_harga,potongan:potongan,tax:tax,satuan:satuan,sales:sales,ber_stok:ber_stok},function(data){
     

      $("#ppn").attr("disabled", true);
     $("#kode_barang").val('');
     $("#nama_barang").val('');
     $("#harga_produk").val('');
     $("#ber_stok").val('');
     $("#jumlah_barang").val('');
     $("#potongan1").val('');
     $("#tax1").val('');


  //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_tbs_penjualan.php", // json datasource
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
    

        var session_id = $("#session_id").val();
        
     
       
    
      
  });

    $("#formtambahproduk").submit(function(){
    return false;
    
    });



//menampilkan no urut faktur setelah tombol click di pilih
      $("#cari_produk_penjualan").click(function() {

      
 
      //menyembunyikan notif berhasil
      $("#alert_berhasil").hide();
      

      });
</script>



<script type="text/javascript">




//menampilkan no urut faktur setelah tombol click di pilih
      $("#cari_produk_penjualan").click(function() {


      $("#cetak_tunai").hide('');
      $("#cetak_tunai_besar").hide('');
      $("#cetak_surat_jalan").hide('');
      $("#cetak_piutang").hide('');

      });

   </script>


<script>
   //perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
  $("#penjualan").click(function(){

        var session_id = $("#session_id").val();
        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var total2 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total2").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var harga = $("#harga_produk").val();
        var kode_gudang = $("#kode_gudang").val();
        var sales = $("#sales").val();
        var keterangan = $("#keterangan").val();   
        var ber_stok = $("#ber_stok").val();   
        var ppn_input = $("#ppn_input").val();
        var ppn = $("#ppn").val();
        
        
        var sisa = pembayaran - total;
        
        var sisa_kredit = total - pembayaran;


     $("#pembayaran_penjualan").val('');
     $("#sisa_pembayaran_penjualan").val('');
     $("#kredit").val('');


 
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

   else if (kode_gudang == "")
 {

alert(" Kode Gudang Harus Diisi ");

 }
 
 else if ( sisa < 0) 
 {

alert("Silakan Bayar Piutang");

 }
                else if (total ==  0 || total == "") 
        {
        
        alert("Anda Belum Melakukan Pemesanan");
        
        }

 else

 {

  $("#penjualan").hide();
  $("#cetak_langsung").hide();
  $("#simpan_sementara").hide();
  $("#batal_penjualan").hide();
  $("#piutang").hide();
  $("#transaksi_baru").show();

 $.post("cek_subtotal_penjualan.php",{total:total,session_id:session_id,potongan:potongan,tax:tax,},function(data) {

  if (data == 1) {

//PERINTAH UNUTK MEN CEK APAKAH STOK PROUDK MASIH ADA ATAU TIDAK MENCUKUPI
    $.getJSON("cek_status_stok_penjualan.php?session_id="+session_id, function(result){
      if (result.status == 0) {

         $.post("proses_bayar_jual.php",{total2:total2,session_id:session_id,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,harga:harga,sales:sales,kode_gudang:kode_gudang,keterangan:keterangan,ber_stok:ber_stok,ppn_input:ppn_input},function(info) {


             var no_faktur = info;
             $("#cetak_tunai").attr('href', 'cetak_penjualan_tunai.php?no_faktur='+no_faktur+'');
             $("#cetak_tunai_besar").attr('href', 'cetak_penjualan_tunai_besar.php?no_faktur='+no_faktur+'');
             $("#cetak_surat_jalan").attr('href', 'cetak_penjualan_surat_jalan.php?no_faktur='+no_faktur+'');
             $("#alert_berhasil").show();
             $("#cetak_tunai").show();
             $("#cetak_tunai_besar").show('');
             $("#cetak_surat_jalan").show('');
             $("#pembayaran_penjualan").val('');
             $("#sisa_pembayaran_penjualan").val('');
             $("#kredit").val('');
            
              //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_tbs_penjualan.php", // json datasource
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

             $("#penjualan").show();
             $("#simpan_sementara").show();
             $("#cetak_langsung").show();
             $("#batal_penjualan").show(); 
             $("#piutang").show();
             $("#transaksi_baru").hide();

              alert("Tidak Bisa Melakukan Penjualan, Ada Stok Produk Yang Habis");

              $("#tbody-barang-jual").find("tr").remove();

                $.each(result.barang, function(i, item) {

                  var tr_barang = "<tr><td>"+ result.barang[i].kode_barang+"</td><td>"+ result.barang[i].nama_barang+"</td><td>"+ result.barang[i].jumlah_jual+"</td><td>"+ result.barang[i].stok+"</td></tr>"
                    
                  $("#tbody-barang-jual").prepend(tr_barang);

             });

             $("#modal_barang_tidak_bisa_dijual").modal('show');

      }

    }); // END cek_status_stok_penjualan.php


  }// END if cek_subtotal_penjualan.php
  else{
    alert("Maaf Subtotal Penjualan Tidak Sesuai, Silakan Tunggu Sebentar!");       
        window.location.href="formpenjualan.php";
  }

 });


 }

 $("form").submit(function(){
    return false;
 
});

});

               $("#penjualan").mouseleave(function(){
          
               var kode_pelanggan = $("#kd_pelanggan").val();
               if (kode_pelanggan == ""){
               $("#kd_pelanggan").attr("disabled", false);
               }
               
               });
      
  </script>


<script>
   //perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
  $("#cetak_langsung").click(function(){

        var session_id = $("#session_id").val();
        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var total2 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total2").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var harga = $("#harga_produk").val();
        var kode_gudang = $("#kode_gudang").val();
        var sales = $("#sales").val();
        var keterangan = $("#keterangan").val();   
        var ber_stok = $("#ber_stok").val();   
        var ppn_input = $("#ppn_input").val();
        var ppn = $("#ppn").val();
        
        
        var sisa = pembayaran - total;
        
        var sisa_kredit = total - pembayaran;


     $("#pembayaran_penjualan").val('');
     $("#sisa_pembayaran_penjualan").val('');
     $("#kredit").val('');


 
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

   else if (kode_gudang == "")
 {

alert(" Kode Gudang Harus Diisi ");

 }
 
 else if ( sisa < 0) 
 {

alert("Silakan Bayar Piutang");

 }
                else if (total ==  0 || total == "") 
        {
        
        alert("Anda Belum Melakukan Pemesanan");
        
        }

 else

 {

  $("#penjualan").hide();
  $("#simpan_sementara").hide();
  $("#cetak_langsung").hide();
  $("#batal_penjualan").hide();
  $("#piutang").hide();
  $("#transaksi_baru").show();

 $.post("cek_subtotal_penjualan.php",{total:total,session_id:session_id,potongan:potongan,tax:tax,},function(data) {

  if (data == 1) {

//PERINTAH UNUTK MEN CEK APAKAH STOK PROUDK MASIH ADA ATAU TIDAK MENCUKUPI
      $.getJSON("cek_status_stok_penjualan.php?session_id="+session_id, function(result){

        if (result.status == 0) {
              
              $.post("proses_bayar_tunai_cetak_langsung.php",{total2:total2,session_id:session_id,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,harga:harga,sales:sales,kode_gudang:kode_gudang,keterangan:keterangan,ber_stok:ber_stok,ppn_input:ppn_input},function(info) {
              
                 var no_fak = info;
                 $("#cetak_surat_jalan").attr('href', 'cetak_penjualan_surat_jalan.php?no_faktur='+no_fak+'');
                 
                 $("#alert_berhasil").show();
                 $("#cetak_surat_jalan").show();
                 $("#pembayaran_penjualan").val('');
                 $("#sisa_pembayaran_penjualan").val('');
                 $("#kredit").val('');

    //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_tbs_penjualan.php", // json datasource
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
                
                var win = window.open('cetak_penjualan_tunai.php?no_faktur='+no_fak+'');
                 if (win) { 
                
                win.focus(); 
              } else { 
                
                alert('Mohon Izinkan PopUps Pada Website Ini !'); }    
                
                   
               });
        }
        else{

               $("#penjualan").show();
               $("#simpan_sementara").show();
               $("#cetak_langsung").show();
               $("#batal_penjualan").show(); 
               $("#piutang").show();
               $("#transaksi_baru").hide();

                alert("Tidak Bisa Melakukan Penjualan, Ada Stok Produk Yang Habis");

                $("#tbody-barang-jual").find("tr").remove();

                  $.each(result.barang, function(i, item) {

                    var tr_barang = "<tr><td>"+ result.barang[i].kode_barang+"</td><td>"+ result.barang[i].nama_barang+"</td><td>"+ result.barang[i].jumlah_jual+"</td><td>"+ result.barang[i].stok+"</td></tr>"
                      
                    $("#tbody-barang-jual").prepend(tr_barang);

               });

               $("#modal_barang_tidak_bisa_dijual").modal('show');

        } // END IF cek_status_stok_penjualan.php

      });
  

  }
  else{
    alert("Maaf Subtotal Penjualan Tidak Sesuai, Silakan Tunggu Sebentar!");       
        window.location.href="formpenjualan.php";
  }

 });



 }

 $("form").submit(function(){
    return false;
 
});

});

               $("#penjualan").mouseleave(function(){
               
              
               var kode_pelanggan = $("#kd_pelanggan").val();
               if (kode_pelanggan == ""){
               $("#kd_pelanggan").attr("disabled", false);
               }
               
               });
      
  </script>

  
     <script>
       //perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
       $("#piutang").click(function(){
       
        var session_id = $("#session_id").val();
        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var total2 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total2").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var kode_gudang = $("#kode_gudang").val();
        var sales = $("#sales").val();
        var keterangan = $("#keterangan").val();   
        var ber_stok = $("#ber_stok").val();
        var ppn_input = $("#ppn_input").val();
        var sisa_piutang = $("#sisa_plafon").val();
       
       var sisa =  pembayaran - total; 

       var sisa_kredit = total - pembayaran;


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
        $("#tanggal_jt").focus();
         
       }
         else if ( total == "") 
         {
         
         alert("Anda Belum Melakukan Pesanan");
         
         }
         
       else
       {


     

 $.post("cek_subtotal_penjualan.php",{total:total,session_id:session_id,potongan:potongan,tax:tax,},function(data) {


  if (data == 1) {

//PERINTAH UNUTK MEN CEK APAKAH STOK PROUDK MASIH ADA ATAU TIDAK MENCUKUPI
    $.getJSON("cek_status_stok_penjualan.php?session_id="+session_id, function(result){

      if (result.status == 0) {

          //Cek Flafon sesuai dengan kode pelanggan / ID Pelanggannya
          $.getJSON("cek_flafon.php",{kredit:kredit,kode_pelanggan:kode_pelanggan},function(data) {

            if(data.status == 2)
            {
              alert("Anda Tidak Bisa Melakukan Transaksi Piutang, sisa plafon : "+sisa_piutang+" dan Ada penjualan yang sudah melewat batas usia plafon");

              $("#tbody-jatuh-tempo").find("tr").remove();

               $.each(data.data_penjualan, function(i, item) {

             
                var tr_penjualan_lewat_usia_plafon = "<tr><td>"+ data.data_penjualan[i].tanggal+"</td><td>"+ data.data_penjualan[i].no_faktur+"</td><td>"+ data.data_penjualan[i].total+"</td><td>"+ data.data_penjualan[i].kredit+"</td><td>"+ data.data_penjualan[i].tanggal_jt+"</td></tr>"

                 $("#tbody-jatuh-tempo").prepend(tr_penjualan_lewat_usia_plafon);

               });

               $("#table-jatuh-tempo").DataTable();

              $("#modal_usia_plafon").modal('show');

            }
            else if(data.status == 1){
              alert("Anda Tidak Bisa Melakukan Transaksi Piutang, sisa plafon : "+sisa_piutang+" ");
            }
            else
            {
              //END BREAK Cek Flafon sesuai dengan kode pelanggan / ID Pelanggannya
          
               $.post("proses_bayar_jual.php",{total2:total2,session_id:session_id,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,sales:sales,kode_gudang:kode_gudang,keterangan:keterangan,ber_stok:ber_stok,ppn_input:ppn_input},function(info) {

                     var no_faktur = info;
                     $("#cetak_piutang").attr('href', 'cetak_penjualan_piutang.php?no_faktur='+no_faktur+'');
                     $("#cetak_surat_jalan").attr('href', 'cetak_penjualan_surat_jalan.php?no_faktur='+no_faktur+'');
                     $("#cetak_surat_jalan").attr('href', 'cetak_penjualan_surat_jalan.php?no_faktur='+no_faktur+'');
                     $("#alert_berhasil").show();
                     $("#pembayaran_penjualan").val('');
                     $("#sisa_pembayaran_penjualan").val('');
                     $("#kredit").val('');
                     $("#potongan_penjualan").val('');
                     $("#potongan_persen").val('');
                     $("#tanggal_jt").val('');
                     $("#cetak_piutang").show();
                     $("#cetak_surat_jalan").show();
                     $("#tax").val('');
                     
                     
                     $("#total1").val('');
                     $("#pembayaran_penjualan").val('');
                     $("#sisa_pembayaran_penjualan").val('');
                     $("#kredit").val('');
                     $("#tanggal_jt").val('');
                     $("#piutang").hide();
                     $("#cetak_langsung").hide();
                     $("#simpan_sementara").hide();
                     $("#batal_penjualan").hide();
                     $("#penjualan").hide();
                     $("#transaksi_baru").show();


     //pembaruan datatable
     $('#tabel_tbs_penjualan').DataTable().destroy();

            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_tbs_penjualan.php", // json datasource
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
                 } // else dari cek flafon
         }); // end fungsi cek flafon

      }
      else{

             $("#penjualan").show();
             $("#simpan_sementara").show();
             $("#cetak_langsung").show();
             $("#batal_penjualan").show(); 
             $("#piutang").show();
             $("#transaksi_baru").hide();

              alert("Tidak Bisa Melakukan Penjualan, Ada Stok Produk Yang Habis");

              $("#tbody-barang-jual").find("tr").remove();

                $.each(result.barang, function(i, item) {

                  var tr_barang = "<tr><td>"+ result.barang[i].kode_barang+"</td><td>"+ result.barang[i].nama_barang+"</td><td>"+ result.barang[i].jumlah_jual+"</td><td>"+ result.barang[i].stok+"</td></tr>"
                    
                  $("#tbody-barang-jual").prepend(tr_barang);

             });

             $("#modal_barang_tidak_bisa_dijual").modal('show');

      } // END IF cek_status_stok_penjualan.php

    });

  }

  else{
    alert("Maaf Subtotal Penjualan Tidak Sesuai, Silakan Tunggu Sebentar!");       
        window.location.href="formpenjualan.php";
  
    }



});

       
       }  
       //mengambil no_faktur pembelian agar berurutan

       });
 $("form").submit(function(){
       return false;
       });

              $("#piutang").mouseleave(function(){
      
               var kode_pelanggan = $("#kd_pelanggan").val();
               if (kode_pelanggan == ""){
               $("#kd_pelanggan").attr("disabled", false);
               }
               
               });
  </script>   


  <script type="text/javascript">
//berfunsi untuk mencekal username ganda
 $(document).ready(function(){
  $(document).on('click', '.pilih', function (e) {
    var session_id = $("#session_id").val();
    var kode_barang = $("#kode_barang").val();
    var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
 $.post('cek_kode_barang_tbs_penjualan.php',{kode_barang:kode_barang,session_id:session_id}, function(data){
  
  if(data == 1){
    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
    $("#kode_barang").val('');
    $("#nama_barang").val('');
   }//penutup if

    });////penutup function(data)

    });//penutup click(function()
  });//penutup ready(function()
</script>

<script type="text/javascript">
$(document).ready(function(){
$("#cari_produk_penjualan").click(function(){
  var session_id = $("#session_id").val();

  $.post("cek_tbs_penjualan.php",{session_id: "<?php echo $session_id; ?>"},function(data){
        if (data != "1") {


             $("#ppn").attr("disabled", false);

        }
    });

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

            $("#total1").val(tandaPemisahTitik(Math.round(total_akhir)));
            $("#potongan_penjualan").val(tandaPemisahTitik(Math.round(total_potongan_nominal)));
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

                  $("#total1").val(tandaPemisahTitik(Math.round(total_akhir)));
                  $("#potongan_penjualan").val(tandaPemisahTitik(Math.round(potongan_nominal)));
              }
            
        }
          
      }); // end  $("#potongan_persen").keyup(function(){

        $("#potongan_penjualan").keyup(function(){

        var potongan_penjualan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
        var potongan_persen = ((potongan_penjualan / total) * 100);
        var tax = $("#tax").val();

        if (tax == "") {
        tax = 0;
      }


        var sisa_potongan = total - potongan_penjualan;
        
             var t_tax = ((parseInt(sisa_potongan,10) * parseInt(tax,10)) / 100);
             var hasil_akhir = parseInt(sisa_potongan, 10) + parseInt(t_tax,10);

        
        $("#total1").val(tandaPemisahTitik(Math.round(hasil_akhir)));
        $("#potongan_persen").val(Math.round(potongan_persen));

      }); // end  $("#potongan_penjualan").keyup(function(){
        
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
              
              
              $("#total1").val(tandaPemisahTitik(Math.round(total_akhir)));

              if (tax > 100) {
                alert ('Jumlah Tax Tidak Boleh Lebih Dari 100%');
                 $("#tax").val('');

              }
        

        $("#tax_rp").val(tandaPemisahTitik(Math.round(t_tax)));


        });
        });
        
        </script>


<script>

//untuk menampilkan sisa penjualan secara otomatis
  $(document).ready(function(){

  $("#jumlah_barang").keyup(function(){
     var jumlah_barang = $("#jumlah_barang").val();
     var jumlahbarang = $("#jumlahbarang").val();
     var limit_stok = $("#limit_stok").val();
     var ber_stok = $("#ber_stok").val();
     var stok = jumlahbarang - jumlah_barang;



if (stok < 0 )

  {

       if (ber_stok == 'Jasa') {
       
       }
       
       else{
       alert ("Jumlah Melebihi Stok!");
       $("#jumlah_barang").val('');
       }


    }

    else if( limit_stok > stok  ){

      alert ("Persediaan Barang Ini Sudah Mencapai Batas Limit Stok, Segera Lakukan Pembelian !");
    }
  });
})

</script>



  <script type="text/javascript">
  $(document).ready(function() {

        var session_id = $("#session_id").val();
        var kode_pelanggan =$("#kd_pelanggan").val();

        $.get("hitung_sisa_plafon.php?kode_pelanggan="+kode_pelanggan,function(data){
          $("#sisa_plafon").val(data);
        });

        
        $.get("cek_total_seluruh.php",
        function(data){
        $("#total2").val(data);

        $("#total1").val(data);
        });
                
        
        
      



  });
  

        
  </script>

<script>

// BELUM KELAR !!!!!!
$(document).ready(function(){

        var cara_bayar = $("#carabayar1").val();
        
        //metode POST untuk mengirim dari file cek_jumlah_kas.php ke dalam variabel "dari akun"
        $.post('cek_jumlah_kas1.php', {cara_bayar : cara_bayar}, function(data) {
        /*optional stuff to do after success */
        
        $("#jumlah1").val(data);
        });


    $("#carabayar1").change(function(){
      var cara_bayar = $("#carabayar1").val();

      //metode POST untuk mengirim dari file cek_jumlah_kas.php ke dalam variabel "dari akun"
      $.post('cek_jumlah_kas1.php', {cara_bayar : cara_bayar}, function(data) {
        /*optional stuff to do after success */

      $("#jumlah1").val(data);
      });

  var session_id = $("#session_id").val();

            $.post("cek_total_hpp.php",
            {
            session_id: session_id
            },
            function(data){
            $("#total_hpp"). val(data);
            });
        
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

<script type="text/javascript">


          $(document).ready(function(){
        $("#potongan_penjualan").keyup(function(){
             var potongan_penjualan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
             var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
             var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_penjualan").val()))));
             var tax = $("#tax").val();

             var potongan_persen = ((potongan_penjualan / total) * 100);
             var sisa_potongan = total - potongan_penjualan;
             var kredit = parseInt(sisa_potongan, 10) - parseInt(pembayaran,10);
             var kembalian = parseInt(pembayaran,10) - parseInt(sisa_potongan, 10);
             var t_tax = ((parseInt(sisa_potongan,10) * parseInt(tax,10)) / 100);
             var hasil_akhir = parseInt(sisa_potongan, 10) + parseInt(t_tax,10);
      
             
      if (kembalian < 0) {
      $("#kredit").val(kredit);
      $("#sisa_pembayaran_penjualan").val('0');
      }
      if (kredit < 0) {
      $("#kredit").val('0');
      $("#sisa_pembayaran_penjualan").val(kembalian);
      }


        
        $("#total1").val(tandaPemisahTitik(parseInt(hasil_akhir)));
        $("#potongan_persen").val(parseInt(potongan_persen));
        });
        });
</script>



<script type="text/javascript">
      
      $(document).ready(function(){


      $("#tax").keyup(function(){


      });
    });
      
</script>







    <script type="text/javascript">
    $(document).ready(function(){
      
//fungsi hapus data 
$(document).on('click','.btn-hapus-tbs',function(e){

    
      var nama_barang = $(this).attr("data-barang");
      var id = $(this).attr("data-id");
      var kode_barang = $(this).attr("data-kode-barang");
      var subtotal_tbs = $(this).attr("data-subtotal");
      var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
       if (total == '') 
        {
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
            
        }

      var konfirmasi_hapus = confirm("Apakah Anda yakin ingin Menghapus "+nama_barang);

      if (konfirmasi_hapus == true) {
      $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(potongan_nominal)));
      $("#total1").val(tandaPemisahTitik(total_akhir));
      $("#total2").val(tandaPemisahTitik(total));


        $.post("hapustbs_penjualan.php",{id:id,kode_barang:kode_barang},function(data){
        if (data == 'sukses') {


        $(".tr-id-"+id+"").remove();
        $("#pembayaran_penjualan").val('');
        
        }
        });
      }
      

});
                  $('form').submit(function(){
              
              return false;
              });


    });
  
//end fungsi hapus data
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



<script type="text/javascript">
  
        $(document).ready(function(){
        $("#kode_barang").blur(function(){

          var kode_barang = $(this).val();
          var level_harga = $("#level_harga").val();
          var session_id = $("#session_id").val();
          var kode_barang = kode_barang.substr(0, kode_barang.indexOf('('));
          
          if (kode_barang != '')
          {

       
       
          $.post("cek_barang_penjualan.php",{kode_barang:kode_barang}, function(data){
          $("#jumlahbarang").val(data);
          });

          $.post('cek_kode_barang_tbs_penjualan.php',{kode_barang:kode_barang,session_id:session_id}, function(data){
          
          if(data == 1){
          alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");

          $("#kode_barang").val('');
          $("#nama_barang").val('');
          }//penutup if
          
          });////penutup function(data)

      $.getJSON('lihat_nama_barang.php',{kode_barang:kode_barang}, function(json){
      
      if (json == null)
      {
        
        $('#nama_barang').val('');
        $('#limit_stok').val('');
        $('#harga_produk').val('');
        $('#harga_lama').val('');
        $('#harga_baru').val('');
        $('#satuan_produk').val('');
        $('#satuan_konversi').val('');
        $('#id_produk').val('');
        $('#ber_stok').val('');

      }

      else 
      {
        if (level_harga == "Level 1") {

        $('#harga_produk').val(json.harga_jual);
        $('#harga_baru').val(json.harga_jual);
        $('#harga_lama').val(json.harga_jual);
        }
        else if (level_harga == "Level 2") {

        $('#harga_produk').val(json.harga_jual2);
        $('#harga_baru').val(json.harga_jual2);
        $('#harga_lama').val(json.harga_jual2);
        }
        else if (level_harga == "Level 3") {

        $('#harga_produk').val(json.harga_jual3);
        $('#harga_baru').val(json.harga_jual3);
        $('#harga_lama').val(json.harga_jual3);
        }

        $('#nama_barang').val(json.nama_barang);
        $('#limit_stok').val(json.limit_stok);
        $('#satuan_produk').val(json.satuan);
        $('#satuan_konversi').val(json.satuan);
        $('#id_produk').val(json.id);
        $('#ber_stok').val(json.berkaitan_dgn_stok);
      }
                                              
        });
        
}

        });
        });

      
      
</script>




<script>
/* Membuat Tombol Shortcut */

function myFunction(event) {
    var x = event.which || event.keyCode;

    if(x == 112){


     $("#myModal").modal();

    }

    else if(x == 113){


     $("#pembayaran_penjualan").focus();

    }

   else if(x == 115){


     $("#penjualan").focus();

    }
  }
</script>

<script type="text/javascript">
          $(document).ready(function(){
          var session_id = $("#session_id").val();
        
        $.get("cek_total_seluruh.php",
        function(data){
        $("#total2").val(data);

        $("#total1").val(data);
        });

      });


</script>


     <script>
       //perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
       $("#simpan_sementara").click(function(){
       
        var session_id = $("#session_id").val();
        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var total2 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total2").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var kode_gudang = $("#kode_gudang").val();
        var sales = $("#sales").val();
        var keterangan = $("#keterangan").val();   
        var ber_stok = $("#ber_stok").val();
        var ppn_input = $("#ppn_input").val();
       
       var sisa =  pembayaran - total; 

       var sisa_kredit = total - pembayaran;


       
  if (kode_pelanggan == "") 
       {
       
       alert("Kode Pelanggan Harus Di Isi");
       
       }

         else if ( total == "") 
         {
         
         alert("Anda Belum Melakukan Pesanan");
         
         }
         
       else
       {

        $("#pembayaran_penjualan").val('');
        $("#sisa_pembayaran_penjualan").val('');
        $("#kredit").val('');
        $("#piutang").hide();
        $("#batal_penjualan").hide();
        $("#penjualan").hide();
        $("#total1").val('');

 $.post("cek_subtotal_penjualan.php",{total:total,session_id:session_id,potongan:potongan,tax:tax,},function(data) {

  if (data == 1) {

//PERINTAH UNUTK MEN CEK APAKAH STOK PROUDK MASIH ADA ATAU TIDAK MENCUKUPI
      $.getJSON("cek_status_stok_penjualan.php?session_id="+session_id, function(result){

        if (result.status == 0) {

            $.post("proses_simpan_barang.php",{total2:total2,session_id:session_id,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,sales:sales,kode_gudang:kode_gudang,keterangan:keterangan,ber_stok:ber_stok,ppn_input:ppn_input},function(info) {

            
                $("#table-baru").html(info);
                var no_faktur = info;
                $("#cetak_surat_jalan").attr('href', 'cetak_penjualan_surat_jalan.php?no_faktur='+no_faktur+'');
                $("#alert_berhasil").show(); 
                $("#transaksi_baru").show();           
                $("#cetak_surat_jalan").show();
                $("#pembayaran_penjualan").val('');
                $("#sisa_pembayaran_penjualan").val('');
                $("#kredit").val('');
                $("#potongan_penjualan").val('');
                $("#potongan_persen").val('');
                $("#tanggal_jt").val('');
                $("#tax").val('');
                
           
           
           });
            
        }
        else{

               $("#penjualan").show();
               $("#simpan_sementara").show();
               $("#cetak_langsung").show();
               $("#batal_penjualan").show(); 
               $("#piutang").show();
               $("#transaksi_baru").hide();

                alert("Tidak Bisa Melakukan Penjualan, Ada Stok Produk Yang Habis");

                $("#tbody-barang-jual").find("tr").remove();

                  $.each(result.barang, function(i, item) {

                    var tr_barang = "<tr><td>"+ result.barang[i].kode_barang+"</td><td>"+ result.barang[i].nama_barang+"</td><td>"+ result.barang[i].jumlah_jual+"</td><td>"+ result.barang[i].stok+"</td></tr>"
                      
                    $("#tbody-barang-jual").prepend(tr_barang);

               });

               $("#modal_barang_tidak_bisa_dijual").modal('show');

        } // END IF cek_status_stok_penjualan.php

      });


  }
  else{
    alert("Maaf Subtotal Penjualan Tidak Sesuai, Silakan Tunggu Sebentar!");       
        window.location.href="formpenjualan.php";
  }

 });


       
       }  
       //mengambil no_faktur pembelian agar berurutan

       });
 $("form").submit(function(){
       return false;
       });

              $("#simpan_sementara").mouseleave(function(){
               
             
               var kode_pelanggan = $("#kd_pelanggan").val();
               if (kode_pelanggan == ""){
               $("#kd_pelanggan").attr("disabled", false);
               }
               
               });
  </script>    



        <script type="text/javascript">

$(document).ready(function(){

    $("#kd_pelanggan").change(function(){
        var kode_pelanggan = $("#kd_pelanggan").val();
        
        var level_harga = $(".opt-pelanggan-"+kode_pelanggan+"").attr("data_pelanggan-level");
        

        $.get("hitung_sisa_plafon.php?kode_pelanggan="+kode_pelanggan,function(data){
          $("#sisa_plafon").val(data);
        });

        if(kode_pelanggan == 'Umum')
        {
           $("#level_harga").val('Level 1');
        }
        else 
        {
           $("#level_harga").val(level_harga);
        
        }
        
        
    });
});

          
        </script>

                            <script type="text/javascript">

                $(document).on('dblclick','.edit-jumlah',function(e){        
                                

                                    var id = $(this).attr("data-id");

                                    $("#text-jumlah-"+id+"").hide();

                                    $("#input-jumlah-"+id+"").attr("type", "text");

                 });

                $(document).on('blur','.input_jumlah',function(e){
                               

                  var id         = $(this).attr("data-id");
                  var jumlah_baru= $(this).val();
                  var kode_barang    = $(this).attr("data-kode");
                  var harga          = $(this).attr("data-harga");
                  var jumlah_lama    = $("#text-jumlah-"+id+"").text();
                  var satuan_konversi= $(this).attr("data-satuan");
                  var ber_stok       = $(this).attr("data-berstok");

                  var subtotal_lama= bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-subtotal-"+id+"").text()))));
                  var potongan          = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-potongan-"+id+"").text()))));
                  var tax_fak           = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));

                  var tax               = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-tax-"+id+"").text()))));
                  //subtotal barang baru                 
                  var subtotal          = harga * jumlah_baru - potongan;
                  var tax_tbs    = tax / subtotal_lama * 100;
                  var jumlah_tax    = Math.round(tax_tbs) * subtotal / 100;


                  var subtotal_penjualan= bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));

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

                           $("#text-jumlah-"+id+"").show();
                           $("#text-jumlah-"+id+"").text(jumlah_baru);
                           $("#btn-hapus-"+id+"").attr('data-subtotal', subtotal);
                           $("#text-subtotal-"+id+"").text(tandaPemisahTitik(subtotal));
                           $("#text-tax-"+id+"").text(Math.round(jumlah_tax));
                           $("#input-jumlah-"+id+"").attr("type", "hidden");
                            $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal))); 
                           $("#total2").val(tandaPemisahTitik(subtotal_penjualan));       
                           $("#total1").val(tandaPemisahTitik(total_akhir)); 

                          $.post("update_pesanan_barang.php",{jumlah_lama:jumlah_lama,tax:tax,id:id,jumlah_baru:jumlah_baru,kode_barang:kode_barang,potongan:potongan,harga:harga,jumlah_tax:jumlah_tax,subtotal:subtotal},function(info){ });        
                        
                        }

                        else {

                          $.post("cek_stok_pesanan_barang.php",{kode_barang:kode_barang, jumlah_baru:jumlah_baru,satuan_konversi:satuan_konversi},function(data){
                            if (data < 0) {

                             alert ("Jumlah Yang Di Masukan Melebihi Stok !");

                            $("#input-jumlah-"+id+"").val(jumlah_lama);
                            $("#text-jumlah-"+id+"").text(jumlah_lama);
                            $("#text-jumlah-"+id+"").show();
                            $("#input-jumlah-"+id+"").attr("type", "hidden");
                                    
                            }

                            else{

                              $("#text-jumlah-"+id+"").show();
                              $("#text-jumlah-"+id+"").text(jumlah_baru);
                              $("#btn-hapus-"+id+"").attr('data-subtotal', subtotal);
                              $("#text-subtotal-"+id+"").text(tandaPemisahTitik(subtotal));
                              $("#text-tax-"+id+"").text(Math.round(jumlah_tax));
                              $("#input-jumlah-"+id+"").attr("type", "hidden"); 
                              $("#potongan_penjualan").val(tandaPemisahTitik(parseInt(total_potongan_nominal))); 
                               $("#total2").val(tandaPemisahTitik(subtotal_penjualan));       
                                $("#total1").val(tandaPemisahTitik(total_akhir));     


                                 $.post("update_pesanan_barang.php",{jumlah_lama:jumlah_lama,tax:tax,id:id,jumlah_baru:jumlah_baru,kode_barang:kode_barang,potongan:potongan,harga:harga,jumlah_tax:jumlah_tax,subtotal:subtotal},function(info){ });

                            } 



                                 }); // end   $.post("cek_stok_pesanan_barang.php"

                            }// end  if (ber_stok == 'Jasa') {
       
                                    $("#kode_barang").focus();
                                    

                  });

             </script>

<script type="text/javascript">
    $(document).ready(function(){

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

<script type="text/javascript">
$(document).ready(function(){
  $("#batal_penjualan").click(function(){
    var session_id = $("#session_id").val()
        window.location.href="batal_penjualan.php?session_id="+session_id+"";

  })
  });
</script>

<!-- SHORTCUT -->

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

<!-- SHORTCUT -->



<!-- memasukan file footer.php -->
<?php include 'footer.php'; ?>