<table id="example4" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th width="5%">No</th>
      <th>No Mesin</th>            
      <th>No Rangka</th>            
      <th>Tipe</th>
      <th>Warna</th>
      <th width="1%"></th>
    </tr>
  </thead>
  <tbody>
  <?php
  $no = 1;         
  foreach ($dt_pu->result() as $ve2) {            
    echo "
    <tr>
      <td>$no</td>
      <td>$ve2->no_mesin</td>
      <td>$ve2->no_rangka</td>
      <td>$ve2->id_modell</td>
      <td>$ve2->id_warna</td>";
      ?>
      <td class="center">
        <button title="Choose" data-dismiss="modal" onclick="choose_rangka('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
      </td>           
    </tr>
    <?php
    $no++;
  }
  ?>
  </tbody>
</table>
<script type="text/javascript">
$(function () {
  $('#example4').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "scrollX":true,
    fixedHeader:true,
    "lengthMenu": [[10, 25, 50,75,100, -1], [10, 25, 50,75,100, "All"]],
    "autoWidth": true
  });
});
</script>