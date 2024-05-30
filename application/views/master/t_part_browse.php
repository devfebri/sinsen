<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th width="5%">No</th>
      <th>Kode Part</th>
      <th>Nama Part</th>                                    
      <th>Satuan</th>                                               
      <th width="1%"></th>
    </tr>
  </thead>
  <tbody>
  <?php
  $no = 1; 
  foreach ($dt_part->result() as $ve2) {
    echo "
    <tr>
      <td>$no</td>
      <td>$ve2->id_part</td>
      <td>$ve2->nama_part</td>
      <td>$ve2->satuan</td>";
      ?>
      <td class="center">
        <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->id_part; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
      </td>           
    </tr>
    <?php
    $no++;
  }
  ?>
  </tbody>
  </table>