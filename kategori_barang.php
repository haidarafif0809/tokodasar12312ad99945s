<?php include 'session_login.php';

include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';

?>



<div class="container">

<h3><b>DATA KATEGORI</b></h3> <hr>

<?php 
include 'db.php';

$pilih_akses_kategori_tambah = $db->query("SELECT kategori_tambah FROM otoritas_master_data WHERE id_otoritas = '$_SESSION[otoritas_id]' AND kategori_tambah = '1'");
$kategori_tambah = mysqli_num_rows($pilih_akses_kategori_tambah);

if ($kategori_tambah > 0){
// Trigger the modal with a button -->
echo '<button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"> </i> KATEGORI</button>';

}

?>
<br>
<br>



<div class="container">
<!-- Modal tambah data -->



<!-- Modal tambah data -->
<div id="myModal" class="modal fade" role="dialog">
  	<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Data kategori</h4>
	      </div>
		    <div class="modal-body">
				<form role="form">
									<div class="form-group">
									<label> Nama kategori </label><br>
									<input type="text" name="nama_kategori" id="nama_kategori" class="form-control" autocomplete="off" required="" >
									<button type="submit" id="submit_tambah" class="btn btn-success">Submit</button>
									</div> 		
				</form>
								
				<div class="alert alert-success" style="display:none">
				 	<strong>Berhasil!</strong> Data berhasil Di Tambah
				</div>

		 	</div><!-- end of modal body -->

			<div class ="modal-footer">
				<button type ="button"  class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
	</div>
	</div>

</div><!-- end of modal buat data  -->


<!-- Modal Hapus data -->
<div id="modal_hapus" class="modal fade" role="dialog">
  <div class="modal-dialog">



    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Konfirmasi Hapus kategori</h4>
      </div>

      <div class="modal-body">
   
   <p>Apakah Anda yakin Ingin Menghapus Data ini ?</p>
   <form >
    <div class="form-group">
    <label> Nama kategori :</label>
     <input type="text" id="data_kategori" class="form-control" readonly=""> 
     <input type="hidden" id="id_hapus" class="form-control" > 
    </div>
   
   </form>
   
  <div class="alert alert-success" style="display:none">
   <strong>Berhasil!</strong> Data berhasil Di Hapus
  </div>
 

     </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="btn_jadi_hapus"> <span class='glyphicon glyphicon-ok-sign'> </span>Ya</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal"><span class='glyphicon glyphicon-remove-sign'> </span>Batal</button>
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
        <h4 class="modal-title">Edit Data kategori</h4>
      </div>
      <div class="modal-body">
  <form role="form">
   <div class="form-group">
    <label for="email">Nama kategori:</label>
     <input type="text" class="form-control" id="nama_edit" autocomplete="off"> 
     <input type="hidden" class="form-control" id="id_edit">
  	 <button type="submit" id="submit_edit" class="btn btn-default">Submit</button>    
   </div> 
   
  </form>
  <div class="alert alert-success" style="display:none">
   <strong>Berhasil!</strong> Data Berhasil Di Edit
  </div>
 

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><!-- end of modal edit data  -->

<div class="table-responsive">
<span id="table-baru">
<table id="tabel_kategori" class="table table-bordered table-sm">
    <thead>
      <th> Nama Kategori </th>  
      <th> Hapus </th>
      <th> Edit </th>   
    </thead>
  </table>
</span>
</div>
</div>

 <script type="text/javascript">
  // ajax table kategori
    $(document).ready(function(){

        $("#tabel_kategori").DataTable().destroy();
          var dataTable = $('#tabel_kategori').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"tabel-kategori.php", // json datasource 
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("# ").append('<tbody class="employee-grid-error"><tr><th colspan="3">Data Tidak Ditemukan.. !!</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
              
            }
          },
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).attr('class','tr-id-'+aData[3]+'');
            },

      }); 
  });
</script>

<script>
    $(document).ready(function(){
//fungsi untuk menambahkan data
    $("#submit_tambah").click(function(){
    var nama_kategori = $("#nama_kategori").val(); 

		if (nama_kategori == ""){
			alert("Nama Kategori Harus Di isi");
		} 

    else{

    $.post('proses_tambah_kategori.php',{nama_kategori:nama_kategori},function(data){

    if (data != '') {
    $("#nama_kategori").val('');
    $(".alert").show('fast');

     var tabel_kategori = $('#tabel_kategori').DataTable();
        tabel_kategori.draw();

    setTimeout(tutupalert, 2000);
    $(".modal").modal("hide");
    }

   
    
    });
    }
    
    
    function tutupmodal() {
    
    }
    });

// end fungsi tambah data


  
//fungsi hapus data 
$(document).on('click', '.btn-hapus', function (e) {
    var nama_kategori = $(this).attr("data-kategori");
    var id = $(this).attr("data-id");
    $("#data_kategori").val(nama_kategori);
    $("#id_hapus").val(id);
    $("#modal_hapus").modal('show');
    
    
    });


$(document).on('click', '#btn_jadi_hapus', function (e) {
    
    var id = $("#id_hapus").val();
    $.post("hapus_kategori.php",{id:id},function(data){
    if (data != "") {
    
    $("#modal_hapus").modal('hide');
    $(".tr-id-"+id+"").remove(); 
    var tabel_kategori = $('#tabel_kategori').DataTable();
        tabel_kategori.draw();
    }

    
    });
    
    });
// end fungsi hapus data

//fungsi edit data 
    $(document).on('click', '.btn-edit', function (e) {
    
    $("#modal_edit").modal('show');
    var nama_kategori = $(this).attr("data-kategori");  
    var id  = $(this).attr("data-id");
    $("#nama_edit").val(nama_kategori); 
    $("#id_edit").val(id); 
    
    });
    
    $("#submit_edit").click(function(){
    var nama_kategori = $("#nama_edit").val(); 
    var id = $("#id_edit").val();

		if (nama_kategori == ""){
			alert("Nama Harus Diisi");
		} 
		else { 
					$.post("update_kategori.php",{id:id,nama_kategori:nama_kategori},function(data){

			if (data == 1) {
			$(".alert").show('fast');
      var tabel_kategori = $('#tabel_kategori').DataTable();
        tabel_kategori.draw();

			setTimeout(tutupalert, 2000);
			$(".modal").modal("hide");
			}
		   
		
		});
		} 
    });
    


//end function edit data

    $('form').submit(function(){
    
    return false;
    });
    
    });
    
    
    
    
    function tutupalert() {
    $(".alert").hide("fast");

    }
    


</script>


<?php include 'footer.php'; ?>

