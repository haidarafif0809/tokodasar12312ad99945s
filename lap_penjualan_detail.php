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
<table id="tableuser" class="table table-bordered table-sm">
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
      <?php 
             if ($_SESSION['otoritas'] == 'Pimpinan')
             {
             
             
             echo "<th style='background-color: #4CAF50; color: white;'> Hpp </th>";
             }
      ?>

					
					<th style="background-color: #4CAF50; color: white;"> Sisa Barang </th>
					
					
					</thead>
					
					<tbody>

					</tbody>
					
					</table>
</span>
</div> <!--/ responsive-->
</div> <!--/ container-->

		<script>
		
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
		
		</script>

      <script type="text/javascript">     
      $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});      
      </script>



<?php 
include 'footer.php';
 ?>