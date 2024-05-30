

<?php if (isset($mode)){ ?>
<?php if ($mode=='detail'){
    $hide=1;
  }else{
    $hide=0;
  } ?>
<?php }else {
  $hide=0;
} ?>
<table class="table table-bordered table-condensed table-stripped">
   <thead>
     <th width="5">No.</th>
     <?php if ($mode=='detail'): ?>
      <th  width="6px"></th>
     <?php endif ?>
     <th>ID KSU</th>
     <th>Nama KSU</th>
     <?php if ($hide==0): ?>
       <th>Aksi</th>
     <?php endif ?>
   </thead>
   <tbody>
     <?php $x=0; $no=1; foreach ($ksu as $ks): ?>
       <tr>
         <td align="center"><?php echo $no ?></td>
           <?php 
           if ($mode=='detail'){
              $checklist = $this->db->query("SELECT id_ksu from tr_sales_order_ksu WHERE id_sales_order ='$konsumen' and id_ksu ='$ks->id_ksu'")->row();
            if($checklist) {
                echo '   <td><input class="form-check-input" type="checkbox" value=""  checked readonly disabled style="background-color: green;"> </td>';
            }else{
              //  echo ' <input class="form-check-input" type="checkbox" value=""   readonly>';
            }
          }
          ?>
        
         <td><?php echo $ks->id_ksu ?></td>
         <td><?php echo $ks->ksu ?></td>
          <?php if ($hide==0): ?>
            <td>
          <input type="checkbox" name="check_<?= $x ?>" value="<?php echo $ks->id_ksu ?>">
          <input type="hidden" name="id_koneksi_ksu" value="<?php echo $ks->id_koneksi_ksu ?>">
          <input type="hidden" name="id_ksu[]" value="<?php echo $ks->id_ksu ?>"> 
        </td>
          <?php endif ?>
       </tr>
     <?php $no++; $x++; endforeach ?>
   </tbody>
 </table>
