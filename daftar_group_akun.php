<?php include_once 'session_login.php';
 

// memasukan file session login,  header, navbar, db.php,
include 'header.php';
include 'navbar.php';
include 'db.php';
include 'sanitasi.php';

 
// menampilkan seluruh data yang ada pada tabel penjualan yang terdapt pada DB
 //$perintah = $db->query("SELECT * FROM grup_akun ORDER BY id DESC");

 ?>

<style>
tr:nth-child(even){background-color: #f2f2f2}
</style>

<div class="container">

<h3><b>DATA GROUP AKUN</b></h3> <hr>

<?php 
include 'db.php';

$pilih_akses_satuan_tambah = $db->query("SELECT grup_akun_tambah FROM otoritas_master_data WHERE id_otoritas = '$_SESSION[otoritas_id]' AND grup_akun_tambah = '1'");
$satuan_tambah = mysqli_num_rows($pilih_akses_satuan_tambah);


    if ($satuan_tambah > 0){
// Trigger the modal with a button -->
echo '<a href="form_tambah_grup_akun.php" class="btn btn-info"><i class="fa fa-plus"> </i> GROUP AKUN</a>';

}

?>

<!-- Modal Hapus data -->
<div id="modal_hapus" class="modal fade" role="dialog">
  <div class="modal-dialog">



    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Konfirmasi Hapus Data Group Akun</h4>
      </div>

      <div class="modal-body">
   
   <p>Apakah Anda yakin Ingin Menghapus Data ini ?</p>
   <form >
    <div class="form-group">
    <label> Nama Akun :</label>
     <input type="text" id="nama_group" class="form-control" readonly=""> 
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

<div id="modal_detail" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detail Group Akun</h4>
      </div>

      <div class="modal-body">
      <div class="table-responsive">
      <span id="modal-detail"> </span>
      </div>

     </div>

      <div class="modal-footer">
        
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<br><br>


<div class="table-responsive">
<span id="table-baru">
<table id="tableuser" class="table table-bordered">
		<thead>
			<th style="background-color: #4CAF50; color:white"> Kode Group Akun </th>
			<th style="background-color: #4CAF50; color:white"> Nama Group Akun </th>
			<th style="background-color: #4CAF50; color:white"> Dari Sub </th>
			<th style="background-color: #4CAF50; color:white"> Kategori Akun</th>
			<th style="background-color: #4CAF50; color:white"> Tipe Akun </th>
			<th style="background-color: #4CAF50; color:white"> User Buat</th>
			<th style="background-color: #4CAF50; color:white"> User Edit </th>
			<th style="background-color: #4CAF50; color:white"> Waktu </th>
			<th style="background-color: #4CAF50; color:white">Detail</th>

<?php 
include 'db.php';

$pilih_akses_satuan_hapus = $db->query("SELECT grup_akun_hapus FROM otoritas_master_data WHERE id_otoritas = '$_SESSION[otoritas_id]' AND grup_akun_hapus = '1'");
$satuan_hapus = mysqli_num_rows($pilih_akses_satuan_hapus);


    if ($satuan_hapus > 0){
			echo "<th style='background-color: #4CAF50; color:white'> Hapus </th>";

		}
?>
			
		</thead>
		

		<tbody>
		
		</tbody>


	</table>
</span>
</div>

</div>


<script type="text/javascript" language="javascript" >
      $(document).ready(function() {
        var dataTable = $('#tableuser').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"show_data_group_akun.php", // json datasource
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody").html("");

             $("#tableuser").append('<tbody class="tbody"><tr><th colspan="3">Tidak Ada Data Yang Ditemukan</th></tr></tbody>');

              $("#tableuser_processing").css("display","none");
              
            }
          },
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).attr('class','tr-id-'+aData[10]+'');

         
            },

        } );
      } );

    </script>

<script type="text/javascript">
    $(document).ready(function(){
	//fungsi hapus data 
		$(".btn-hapus").click(function(){
		var nama_group = $(this).attr("data-satuan");
		var id = $(this).attr("data-id");
		$("#nama_group").val(nama_group);
		$("#id_hapus").val(id);
		$("#modal_hapus").modal('show');
		
		
		});


		$("#btn_jadi_hapus").click(function(){
		
		var id = $("#id_hapus").val();
		$.post("hapus_group_akun.php",{id:id},function(data){
		if (data != "") {
		
		$(".tr-id-"+id+"").remove();
		$("#modal_hapus").modal('hide');
		
		}

		
		});
		
		});
		});


	$("form").submit(function(){
    return false;
    
    });
// end fungsi hapus data
</script>


<!-- EDIT --><!-- EDIT --><!-- EDIT --><!-- EDIT --><!-- EDIT --><!-- EDIT --><!-- EDIT -->

                             <script type="text/javascript">
                       $(document).on('dblclick', '.edit-nama', function (e) {
            
                                    var id = $(this).attr("data-id");

                                    $("#text-nama-"+id+"").hide();

                                    $("#input-nama-"+id+"").attr("type", "text");

                                 });

                       $(document).on('blur', '.input_nama', function (e) {

                                    var id = $(this).attr("data-id");
                                    var input_nama = $(this).val();


                                    $.post("update_grup_akun.php",{id:id, input_nama:input_nama,jenis_edit:"nama_grup_akun"},function(data){

                                    $("#text-nama-"+id+"").show();
                                    $("#text-nama-"+id+"").text(input_nama);

                                    $("#input-nama-"+id+"").attr("type", "hidden");           

                                    });
                                 });

                             </script>


                             <script type="text/javascript">
                       $(document).on('dblclick', '.edit-parent', function (e) {
                       

                                    var id = $(this).attr("data-id");

                                    $("#text-parent-"+id+"").hide();

                                    $("#select-parent-"+id+"").show();

                                 });

                       $(document).on('blur', '.select-parent', function (e) {

                                    var id = $(this).attr("data-id");

                                    var select_parent = $(this).val();


                                    $.post("update_grup_akun.php",{id:id, select_parent:select_parent,jenis_select:"parent"},function(data){

                                    $("#text-parent-"+id+"").show();
                                    $("#text-parent-"+id+"").text(select_parent);

                                    $("#select-parent-"+id+"").hide();           

                                    });
                                 });

                             </script>


                             <script type="text/javascript">
           $(document).on('dblclick', '.edit-kategori', function (e) {
                     
                                    var id = $(this).attr("data-id");

                                    $("#text-kategori-"+id+"").hide();

                                    $("#select-kategori-"+id+"").show();

                                 });

           $(document).on('blur', '.select-kategori', function (e) {

                                    var id = $(this).attr("data-id");

                                    var select_kategori = $(this).val();


                                    $.post("update_grup_akun.php",{id:id, select_kategori:select_kategori,jenis_select:"kategori_akun"},function(data){

                                    $("#text-kategori-"+id+"").show();
                                    $("#text-kategori-"+id+"").text(select_kategori);

                                    $("#select-kategori-"+id+"").hide(); 


         $('#tableuser').DataTable().destroy();
        var dataTable = $('#tableuser').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"show_data_group_akun.php", // json datasource
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody").html("");

             $("#tableuser").append('<tbody class="tbody"><tr><th colspan="3">Tidak Ada Data Yang Ditemukan</th></tr></tbody>');

              $("#tableuser_processing").css("display","none");
              
            }
          },
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).attr('class','tr-id-'+aData[11]+'');

         
            },

          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('class','tr-id-'+aData[11]+'');

        $('td:eq(1)', nRow).attr( 'class','edit-nama');
        $('td:eq(1)', nRow).html( '<span id='+aData[1]+'</span>');

        $('td:eq(2)', nRow).attr( 'class','edit-parent');
        $('td:eq(2)', nRow).html( '<span id='+aData[2]+'</span>');

            },

        });   


                                    });
                                 });

                             </script>

                             <script type="text/javascript">
                     $(document).on('dblclick', '.edit-tipe', function (e) {
                       
                                    var id = $(this).attr("data-id");

                                    $("#text-tipe-"+id+"").hide();

                                    $("#select-tipe-"+id+"").show();

                                 });

                     $(document).on('blur', '.select-tipe', function (e) {

                                    var id = $(this).attr("data-id");

                                    var select_tipe = $(this).val();


                                    $.post("update_grup_akun.php",{id:id, select_tipe:select_tipe,jenis_select:"tipe_akun"},function(data){

                                    $("#text-tipe-"+id+"").show();
                                    $("#text-tipe-"+id+"").text(select_tipe);

                                    $("#select-tipe-"+id+"").hide();           

                                    });
                                 });

                             </script>


		<script type="text/javascript">
$(document).on('click', '.detail', function (e) {
		
		var kode_grup_akun = $(this).attr('kode_grup_akun');
		
		
		$("#modal_detail").modal('show');
		
		$.post('proses_detail_grup_akun.php',{kode_grup_akun:kode_grup_akun},function(info) {
		
		$("#modal-detail").html(info);
		
		
		});
		
		});
		
		</script>

<?php 
include 'footer.php';
 ?>