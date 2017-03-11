<?php


    $select_waktu_jatuh_tempo = $db->query("SELECT waktu FROM setting_waktu_reminder ");
    $data_jatuh_tempo = mysqli_fetch_array($select_waktu_jatuh_tempo);
    $jatuh_tempo = $data_jatuh_tempo['waktu'];
    $satu_menit = 60 * 1000;
    $waktu_jatuh_tempo = $satu_menit * $jatuh_tempo;

    $tanggal_sekarang = date('Y-m-d');

    $ambil_jatuh_tempo = $db->query("SELECT p.tanggal_jt, p.suplier, p.kredit, s.nama AS nama_suplier FROM pembelian p INNER JOIN suplier s ON p.suplier = s.id WHERE p.tanggal_jt = '$tanggal_sekarang'");
    $row_tanggal_jt = mysqli_num_rows($ambil_jatuh_tempo);

?>


<input type="hidden" id="waktu_jatuh_tempo" value="<?php echo $waktu_jatuh_tempo; ?>"/>
<input type="hidden" id="row_tanggal_jt" value="<?php echo $row_tanggal_jt; ?>"/>

<input type="button" style="display: none" id="btn-tampil-modal" value="Click Me" onclick="waktuReminder()"/>


<!-- Modal Tampilkan Produk yang promo -->
<div id="modal_reminder" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Hutang Jatuh Tempo </h4>
      </div>
      <div class="modal-body">
      
      <table id="table-hutang-jt" class="table table-hover table-sm">
      <thead>
            <th style='background-color: #4CAF50; color:white'"> Suplier</th>
            <th style='background-color: #4CAF50; color:white'"> Total Hutang </th>
      </thead>

      <tbody>

        <?php
          while ($tanggal_jt = mysqli_fetch_array($ambil_jatuh_tempo))
          {
          echo "<tr>
          <td>". $tanggal_jt['nama_suplier'] ."</td>
          <td>". $tanggal_jt['kredit'] ."</td>
          </tr>";
          }


        ?>
    
    </tbody>
</table>

      </div>
      <div class ="modal-footer">
        <button type ="button" id="btn-iya" class="btn btn-sm btn-warning" value="Tampil Lagi">Yes</button>
        <button type ="button" id="btn-tidak" class="btn btn-sm btn-default" value="Tidak Tampil Lagi" >Close</button>
      </div>
  </div>

  </div>
</div><!-- end of modal buat data  -->



<script type="text/javascript">
    $(document).ready(function(){
      <?php if ($_SESSION['printer'] == 1): ?>
          btnReminder = setInterval("$('#btn-tampil-modal').click()",1000); 
      <?php endif ?>
        
    });
</script>

<script type="text/javascript">
var waktu_jatuh_tempo = $("#waktu_jatuh_tempo").val();

  function waktuReminder(){
      reminderId = setInterval("$('#modal_reminder').show();",waktu_jatuh_tempo);

  }

</script>

<script type="text/javascript">

$(document).ready(function(){
  $(document).on('click','#btn-iya',function(){
        $("#modal_reminder").hide();

    });
});

</script>

<script type="text/javascript">

$(document).ready(function(){
  $(document).on('click','#btn-tidak',function(){
        $("#modal_reminder").hide();
        clearInterval(reminderId);
        clearInterval(btnReminder);
      $.get("destroy_session_printer.php",function(data){

      });
    });
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#table-hutang-jt').DataTable();
});
</script>