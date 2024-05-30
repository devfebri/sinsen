<div id='general-content'>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>No Plat</th>
      <th>Nama Konsumen</th>
      <th>Tipe</th>
      <th>Tahun Produksi</th>                    
      <th><input type="checkbox" class="select_all"></th>                                     
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    $no = 1;
    foreach($dt_plat->result() as $isi) {       
      // $cek = $this->db->query("SELECT * FROM tr_sales_order WHERE no_mesin = '$isi->no_mesin'");        
      // if($cek->num_rows() > 0){      
      //   $r = $cek->row();
      //   $biaya_biro = $r->biaya_biro;
      // }else{
      //   $biaya_biro = 0;
      // }
      $jum = $dt_plat->num_rows();
      if ($isi->no_plat!=null) {
        echo "
      <tr>                     
        <td>$isi->no_mesin</td> 
        <td>$isi->no_rangka</td> 
        <td>$isi->no_plat</td> 
        <td>$isi->nama_konsumen</td> 
        <td>$isi->tipe_ahm</td>       
        <td>$isi->tahun</td>                 
        <td align='center'>
          <input type='hidden' value='$jum' name='jum'>        
          <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>        
          <input type='checkbox' name='cek_nosin_$no' id='cek_nosin[]'>
        </td>              
      </tr>";
      $no++;
      }
      
      }
    ?>
  </tbody>
</table>     
</div>
<br>  
<div id="general">
  <i>  
    <span style="font-size:20px;" id="counter" class="counter badge badge-secondary"></span>
  </i>
</div>
<script type="text/javascript">
  $('#general i .counter').text(' ');

var fnUpdateCount = function() {
  var generallen = $("#general-content input[id='cek_nosin[]']:checked").length;
    console.log(generallen,$("#general i .counter") )
  if (generallen > 0) {
    $("#general i .counter").text('Total Checklist : ' + generallen + ' data');
  } else {
    $("#general i .counter").text(' ');
  }
};

$("#general-content input:checkbox").on("change", function() {
      fnUpdateCount();
    });

$('.select_all').change(function() {
      var checkthis = $(this);
      var checkboxes = $("#general-content input:checkbox");

      if (checkthis.is(':checked')) {
        checkboxes.prop('checked', true);
      } else {
        checkboxes.prop('checked', false);
      }
            fnUpdateCount();
    });
</script>