<?php include 'session_login.php';


//memasukkan file session login, header, navbar, db.php
include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';


 ?>

 
<style>

tr:nth-child(even){background-color: #f2f2f2}

</style>

     <script>
    $(function() {
    $( "#dari_tanggal" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>


    <script>
    $(function() {
    $( "#sampai_tanggal" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>

 <div class="container">

<h3>LAPORAN PENJUALAN DETAIL </h3><hr>
<form class="form-inline" role="form">
				
  					<div class="form-group"> 

  				 <select type="text" name="kategori" id="kategori" class="form-control chosen" required="">
     			  <option value="semua"> Semua Kategori </option>
				<?php 

				$ambil_kategori = $db->query("SELECT nama_kategori FROM kategori");
  				  while($data_kategori = mysqli_fetch_array($ambil_kategori))
  				  {  
    			echo "<option value='".$data_kategori['nama_kategori'] ."' >".$data_kategori['nama_kategori'] ."</option>";
   					 }

 				?>
                 </select> 
                  </div>

				  <div class="form-group"> 

                  <input type="text" name="dari_tanggal" id="dari_tanggal" class="form-control" placeholder="Dari Tanggal" required="">
                  </div>

                  <div class="form-group"> 

                  <input type="text" name="sampai_tanggal" id="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" required="">
                  </div>

                  <button type="submit" name="submit" id="submit" class="btn btn-primary" ><i class="fa fa-eye"> </i> Tampil </button>

</form>

<span id="ss"></span>

 <br>
 <div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<span id="result">
<table id="table_lap_penjualan_detail" class="table table-bordered table-sm">
					<thead>
					<th style="background-color: #4CAF50; color: white;"> Nomor Faktur </th>
					<th style="background-color: #4CAF50; color: white;"> Kode Barang </th>
					<th style="background-color: #4CAF50; color: white;"> Nama Barang </th>
					<th style="background-color: #4CAF50; color: white;"> Jumlah Barang </th>
					<th style="background-color: #4CAF50; color: white;"> Satuan </th>
					<th style="background-color: #4CAF50; color: white;"> Harga </th>
					<th style="background-color: #4CAF50; color: white;"> Subtotal </th>
					<th style="background-color: #4CAF50; color: white;"> Potongan </th>
					<th style="background-color: #4CAF50; color: white;"> Tax </th>
          <th style="background-color: #4CAF50; color: white;"> Total </th>


					</thead>
					
					<tbody>

					</tbody>
					
					</table>
</span>
</div> <!--/ responsive-->

       <a href='cetak_laporan_penjualan_piutang.php' style="display: none" class='btn btn-success'  id="cetak_non" target='blank'><i class='fa fa-print'> </i> Cetak Penjualan Detail</a>  

       <a href='download_lap_penjualan_piutang.php' style="display: none" type='submit' target="blank" id="btn-download-non" class='btn btn-purple'><i class="fa fa-download"> </i> Download Excel Penjualan Detail</a>


</div> <!--/ container-->

		<!--script>
		
		$(document).ready(function(){
		$('#tableuser').DataTable();
		});
		</script>

		
		<script type="text/javascript">
		$("#submit").click(function(){
		
		var dari_tanggal = $("#dari_tanggal").val();
		var sampai_tanggal = $("#sampai_tanggal").val();
		var kategori = $("#kategori").val();

		
		$.post("proses_lap_penjualan_detail.php", {dari_tanggal:dari_tanggal,sampai_tanggal:sampai_tanggal,kategori:kategori},function(info){
		
		$("#result").html(info);

		
		});
		
		
		});      
		$("form").submit(function(){
		
		return false;
		
		});
		
		</script>-->

      <script type="text/javascript">     
      $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});      
      </script>

      <script type="text/javascript">
		$(document).on('click','#submit',function(e){

			$('#table_lap_penjualan_detail').DataTable().destroy();
			var kategori = $("#kategori").val();
			var dari_tanggal = $("#dari_tanggal").val();
      		var sampai_tanggal = $("#sampai_tanggal").val();

      		if (kategori == '') {
            alert("Silakan Pilih kategori terlebih dahulu.");
            $("#kategori").focus();
          }

      	else if (dari_tanggal == '') {
            alert("Silakan dari tanggal diisi terlebih dahulu.");
            $("#dari_tanggal").focus();
          }
          else if (sampai_tanggal == '') {
            alert("Silakan sampai tanggal diisi terlebih dahulu.");
            $("#sampai_tanggal").focus();
          }
            else{
          var dataTable = $('#table_lap_penjualan_detail').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_lap_penjualan_detail.php", // json datasource
           	"data": function ( d ) {
                      d.kategori = $("#kategori").val();
                      d.dari_tanggal = $("#dari_tanggal").val();
                      d.sampai_tanggal = $("#sampai_tanggal").val();
                      // d.custom = $('#myInput').val();
                      // etc
                  },
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_lap_penjualan_detail").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
    });


    $("#cetak_non").show();
    $("#btn-download-non").show();
$("#cetak_non").attr("href", "cetak_lap_penjualan_detail.php?dari_tanggal="+dari_tanggal+"&sampai_tanggal="+sampai_tanggal+"&kategori="+kategori+"");
$("#btn-download-non").attr("href", "download_lap_penjualan_detail.php?dari_tanggal="+dari_tanggal+"&sampai_tanggal="+sampai_tanggal+"&kategori="+kategori+"");


        }//end else
        $("form").submit(function(){
        return false;
        });
		
		});
		
		</script>



<?php 
include 'footer.php';
 ?>