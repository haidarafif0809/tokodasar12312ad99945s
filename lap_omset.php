<?php include 'session_login.php';


//memasukkan file session login, header, navbar, db.php
include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';


 ?>



    <script>
	$(function() {
	$( "#dari_tanggal" ).pickadate({ selectYears: 80, format: 'yyyy-mm-dd'});
	$( "#sampai_tanggal" ).pickadate({ selectYears: 80, format: 'yyyy-mm-dd'});
	});
	</script>



<style>

tr:nth-child(even){background-color: #f2f2f2}

</style>

 <div class="container">

<h3>LAPORAN OMSET </h3><hr>

<div class="row">
<form class="form" role="form">
				
	<div class="form-group col-sm-2"> 
		<input type="text" name="dari_tanggal" id="dari_tanggal" class="form-control" placeholder="Dari Tanggal" required="">
	</div>

	<div class="form-group col-sm-2"> 
		<input type="text" name="sampai_tanggal" id="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" value="<?php echo date("Y-m-d"); ?>" required="">
	</div>

	<div class="col-sm-3">
    	<label> Sales </label><br>
  			<select name="sales" id="sales" class="form-control chosen" required="" autofocus="">
				<?php 
					$query = $db->query("SELECT nama FROM user WHERE status_sales = 'Iya' ");
					while($data = mysqli_fetch_array($query)){
					
						echo "<option value='".$data['nama'] ."'>".$data['nama'] ."</option>";
					}
				?>
			</select>
	</div>

	<div class="col-sm-3">
    	<label> Pelanggan </label><br>
  			<select name="kode_pelanggan" id="kode_pelanggan" class="form-control chosen" required="" autofocus="">
				<?php 
					$query = $db->query("SELECT kode_pelanggan, nama_pelanggan FROM pelanggan");
					while($data = mysqli_fetch_array($query)){
					
						echo "<option value='".$data['kode_pelanggan'] ."'>".$data['kode_pelanggan'] ." - ".$data['nama_pelanggan'] ."</option>";
					}
				?>
			</select>
	</div>

	<div class="form-group col-sm-2"> 
		<label><br><br></label>
		<button type="submit" name="submit" id="submit" class="btn btn-primary" > <i class="fa fa-eye"> </i> Tampil </button>
	</div>

</form>

</div>
			<div class="card card-block">
				<span id="span_omset">          
                 
                  <div class="table-responsive">
                    <table id="tabel_omset" class="table table-bordered table-sm">
                          <thead> <!-- untuk memberikan nama pada kolom tabel -->                              
								<th style="background-color: #4CAF50; color: white;"> Tanggal</th>
								<th style="background-color: #4CAF50; color: white;"> No. Faktur</th>
								<th style="background-color: #4CAF50; color: white;"> Nama Pelanggan</th>
								<th style="background-color: #4CAF50; color: white;"> Sales</th>
								<th style="background-color: #4CAF50; color: white;"> <center>Total Penjualan</center> </th>
								<th style="background-color: #4CAF50; color: white;"> <center>Total Omset</center> </th>
                          
                          </thead> <!-- tag penutup tabel -->
                    </table>
                  </div>

                </span> 

			</div>


				<span id="cetak" style="display: none;">
					<a href='cetak_laporan_omset.php' target="blank" id="cetak_omset" class='btn btn-success'><i class='fa fa-print'> </i> Cetak Laporan</a>
					<a href='download_laporan_omset.php' target="blank" id="download_omset" class='btn btn-primary'><i class='fa fa-download'> </i> Download Laporan</a>
				</span>
</div> <!--/ container-->


		
		<script type="text/javascript">
		$("#submit").click(function(){
		
                        $('#tabel_omset').DataTable().destroy();

                          var dataTable = $('#tabel_omset').DataTable( {
                            "processing": true,
                            "serverSide": true,
                            "info":     true,
                            "language": { "emptyTable":     "Tidak Ada Data Di Tabel Ini" },
                            "ajax":{
                              url :"data_laporan_omset.php", // json datasource
                               "data": function ( d ) {
                                  d.dari_tanggal = $("#dari_tanggal").val();
                                  d.sampai_tanggal = $("#sampai_tanggal").val();
                                  d.kode_pelanggan = $("#kode_pelanggan").val();
                                  d.sales = $("#sales").val();
                                  // d.custom = $('#myInput').val();
                                  // etc
                              },
                               
                                type: "post",  // method  , by default get
                              error: function(){  // error handling
                                $(".employee-grid-error").html("");
                                $("#tabel_omset").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display","none");
                                }
                            }
                          });

              

              var dari_tanggal = $("#dari_tanggal").val();
			  var sampai_tanggal = $("#sampai_tanggal").val();
			  var kode_pelanggan = $("#kode_pelanggan").val();
			  var sales = $("#sales").val();

			  $("#cetak").show();
			  $("#cetak_omset").attr("href", "cetak_laporan_omset.php?dari_tanggal="+dari_tanggal+"&sampai_tanggal="+sampai_tanggal+"&kode_pelanggan="+kode_pelanggan+"&sales="+sales+"");
			  $("#download_omset").attr("href", "download_laporan_omset.php?dari_tanggal="+dari_tanggal+"&sampai_tanggal="+sampai_tanggal+"&kode_pelanggan="+kode_pelanggan+"&sales="+sales+"");
		
		
		});      
		$("form").submit(function(){
		
		return false;
		
		});
		
		</script>


<script type="text/javascript">	
    $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true}); 
</script>


<?php 
include 'footer.php';
 ?>