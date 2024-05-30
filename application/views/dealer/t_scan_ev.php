<table id="example4" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th width="5%">No</th>
      <th>No. Shipping List</th>
      <th>Serial Number</th>
      <th>Part Number </th>
      <th>Nama Part </th>
      <th width="1%">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $no = 1;         
  foreach ($dt_shipping_list->result() as $item) {     ?>        
    <tr>
      <td><?=$no++?></td>
      <td><?=$item->no_shipping_list?></td>
      <td><?=$item->serial_number?></td>
      <td><?=$item->part_id?></td>
      <td><?=$item->part_desc?></td>
      <td class="center">
        <button title="Choose" data-dismiss="modal" onclick="choose_serial_number('<?php echo $item->serial_number; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
      </td>  
    </tr>
    <?php
  }
  ?>
  </tbody>
</table>
