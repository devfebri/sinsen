<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th width="5%">No</th>
      <th>No Shipping List</th>            
      <th>Jumlah Unit</th>            
      <th width="1%"></th>
    </tr>
  </thead>
  <tbody>
  <?php
  $no = 1; 
  foreach ($dt_item->result() as $ve2) {
    $r = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_shipping_list WHERE no_shipping_list = '$ve2->no_shipping_list'")->row();
    echo "
    <tr>
      <td>$no</td>
      <td>$ve2->no_shipping_list</td>
      <td>$r->jum</td>";
      ?>
      <td class="center">
        <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_shipping_list; ?>','<?php echo $r->jum; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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