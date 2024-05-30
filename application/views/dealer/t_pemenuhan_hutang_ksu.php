<table class="table table-bordered table-condensed table-stripped">
  <thead>                    
   <th width='10%'>ID KSU</th>
   <th>Nama KSU</th>
   <th width='5%'>Aksi</th>
  </thead>
  <tbody>
    <?php
    $no = 1;
    foreach ($dt_data->result() as $row){ 
      $jum = $dt_data->num_rows();
      $sql = $this->m_admin->getByID("ms_ksu","id_ksu",$row->id_ksu)->row();
    ?>
     <tr>                         
      <td><?php echo $row->id_ksu ?></td>
      <td><?php echo $sql->ksu ?></td>
      <td align="center">
        <input type="checkbox" name="cek_<?php echo $no ?>" value="<?php echo $row->id_ksu ?>">      
        <input type="hidden" name="id_ksu_<?php echo $no ?>" value="<?php echo $row->id_ksu ?>">
        <input type="hidden" name="jum" value="<?php echo $jum ?>">
      </td>
     </tr>
    <?php 
        $no++;
    } ?>
  </tbody>
</table>