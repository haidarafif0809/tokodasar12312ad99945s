<?php include 'session_login.php';
include 'header.php';
include 'navbar.php';
include 'db.php';




 ?>


<div class="container">
<h3>SETTING FOOTER CETAK</h3> <hr>
<!-- Trigger the modal with a button -->

  <button id="tambah" type="submit" class="btn btn-primary" data-toggle="collapse"  accesskey="r" ><i class='fa fa-plus'> </i>&nbsp;Edit</button>

<button style="display:none" data-toggle="collapse tooltip" accesskey="k" id="kembali" class="btn btn-primary" data-placement='top' title='Klik untuk kembali ke utama.'><i class="fa fa-reply"></i> <u>K</u>embali </button>

<br>
<br>


<div id="demo" class="collapse">
<form role="form" method="POST" action="tambah_footer_cetak.php">
    <div class="form-group">


          <label> Keterang Footer </label><br>
          <textarea name="keterangan_footer" id="keterangan_footer" style="height:250px" class="form-control"  placeholder="Pesan Alert Promo" required=""></textarea>  


          <button type="submit" id="submit_tambah" class="btn btn-success">Submit</button>

    </div>
</form>

        
          <div class="alert alert-success" style="display:none">
          <strong>Berhasil!</strong> Data Berhasil Di Tambah
          </div>
</div>


<style>

tr:nth-child(even){background-color: #f2f2f2}


</style>


<div class="table-responsive">
<span id="table_baru">
<table id="table_footer_cetak" class="table table-bordered table-sm">
    <thead>
      <th style='background-color: #4CAF50; color: white'> No.</th>
      <th style='background-color: #4CAF50; color: white'> Keterangan Footer</th>
      <th style='background-color: #4CAF50; color: white'> Petugas</th>
    </thead>
    
  </table>
</span>
</div>


<!--script disable hubungan pasien-->
<script type="text/javascript">
$(document).ready(function(){

  $("#tambah").click(function(){
  $("#demo").show();
  $("#kembali").show();
   $("#tambah").hide();
  });

  $("#kembali").click(function(){
  $("#demo").hide();
  $("#tambah").show();
  $("#kembali").hide();

  });
});
</script>

  
<!--DATA TABLE MENGGUNAKAN AJAX-->
<script type="text/javascript" language="javascript" >
      $(document).ready(function() {

          var dataTable = $('#table_footer_cetak').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_footer_cetak.php", // json datasource
           
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_footer_cetak").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
            
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('class','tr-id-'+aData[5]+'');
            },
        });

        $("#form").submit(function(){
        return false;
        });
        

      } );
    </script>
<!--/DATA TABLE MENGGUNAKAN AJAX-->

<!-- PERINTAH U/ EDIT PETUGAS DEFAULT DI CETAK PENJUALAN -->
<script type="text/javascript">
$(document).on("dblclick",".edit-nama",function(){

  var id = $(this).attr("data-id");

  $("#text-nama-"+id+"").hide();
  $("#input-nama-"+id+"").attr("type", "text");
});


$(document).on("blur",".input_nama",function(){

  var id = $(this).attr("data-id");
  var input_nama = $(this).val();


  $.post("update_default_petugas_cetak.php",{id:id, input_nama:input_nama},function(data){

    $("#text-nama-"+id+"").show();
    $("#text-nama-"+id+"").text(input_nama);
    $("#input-nama-"+id+"").attr("type", "hidden");           

  });
});

</script>

<!-- PERINTAH U/ EDIT PETUGAS DEFAULT DI CETAK PENJUALAN -->
    
           <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'keterangan_footer' );
            </script>




<?php
 include 'footer.php'; ?>
