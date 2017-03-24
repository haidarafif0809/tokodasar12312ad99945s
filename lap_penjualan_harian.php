<?php include 'session_login.php';


//memasukkan file session login, header, navbar, db.php
include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';


 ?>

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

<style>

tr:nth-child(even){background-color: #f2f2f2}

</style>

 <div class="container">

<h3>LAPORAN PENJUALAN HARIAN </h3><hr>
<form class="form-inline" role="form">
				
				  <div class="form-group"> 

                  <input type="text" name="dari_tanggal" id="dari_tanggal" class="form-control" placeholder="Dari Tanggal" required="">
                  </div>

                  <div class="form-group"> 

                  <input type="text" name="sampai_tanggal" id="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" value="<?php echo date("Y-m-d"); ?>" required="">
                  </div>

                  <button type="submit" name="submit" id="submit" class="btn btn-primary" > <i class="fa fa-eye"> </i> Tampil </button>

</form><br>

<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
	<span id="result">

			<table id="table_lap_penjualan_harian" class="table table-bordered table-sm">
				<thead>

					<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
					<th style="background-color: #4CAF50; color: white;"> Jumlah Transaksi </th>
					<th style="background-color: #4CAF50; color: white;"> Total Transaksi </th>
					<th style="background-color: #4CAF50; color: white;"> Jumlah Bayar Tunai </th>
					<th style="background-color: #4CAF50; color: white;"> Jumlah Bayar Kredit </th>
								
				</thead>
			</table>

	</span>
</div> <!--/ responsive-->

<span id="cetak" style="display: none;">
	<br><a href='cetak_lap_penjualan_harian.php' target="blank" id="cetak_lap" class='btn btn-success'><i class='fa fa-print'> </i> Cetak Penjualan </a>
</span>
<span id="download" style="display: none;">
	<a href='download_lap_penjualan_harian.php' type='submit' id="download_lap" target="blank" id="btn-download" class='btn btn-purple'><i class="fa fa-download"> </i> Download Excel</a>
</span>

</div> <!--/ container-->


<script type="text/javascript">
		$(document).on('click','#submit',function(e){
			$('#table_lap_penjualan_harian').DataTable().destroy();
			var dari_tanggal = $("#dari_tanggal").val();
      		var sampai_tanggal = $("#sampai_tanggal").val();
      		if (dari_tanggal == '') {
            alert("Silakan dari tanggal diisi terlebih dahulu.");
            $("#dari_tanggal").focus();
          }

          else if (sampai_tanggal == '') {
            alert("Silakan sampai tanggal diisi terlebih dahulu.");
            $("#sampai_tanggal").focus();
          }
          
          else{


          var dataTable = $('#table_lap_penjualan_harian').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"proses_lap_penjualan_harian.php", // json datasource
           	"data": function ( d ) {
           		d.dari_tanggal = $("#dari_tanggal").val();
           		d.sampai_tanggal = $("#sampai_tanggal").val();
                      // d.custom = $('#myInput').val();
                      // etc
                  },
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_lap_penjualan_harian").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
            
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('class','tr-id-'+aData[11]+'');
            },

        });

        $("#cetak").show();
    	$("#cetak_lap").attr("href", "cetak_lap_penjualan_harian.php?&dari_tanggal="+dari_tanggal+"&sampai_tanggal="+sampai_tanggal+"");
    	$("#download").show();
    	$("#download_lap").attr("href", "download_lap_penjualan_harian.php?&dari_tanggal="+dari_tanggal+"&sampai_tanggal="+sampai_tanggal+"");
        }//end else
        $("form").submit(function(){
        return false;
        });
		
		});
		
		</script>


<?php 
include 'footer.php';
 ?>