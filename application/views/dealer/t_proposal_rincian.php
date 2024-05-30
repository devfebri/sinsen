<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>                                            
      <th>Item</th>              
      <th width="10%">Qty</th>              
      <th>Harga</th>              
      <th>PPN</th>
      <th>Keterangan</th>              
      <th>Action</th>              
    </tr>
  </thead>
  <tbody>
  <?php 
  if($mode!='add'){
    $sql = $this->db->query("SELECT * FROM tr_proposal_dealer_rincian WHERE id_proposal = '$mode'");
    foreach ($sql->result() as $isi) {
      echo "
        <tr>
          <td>$isi->item</td>
          <td>$isi->qty</td>
          <td>$isi->harga</td>
          <td>$isi->ppn</td>
          <td>$isi->keterangan</td>
          <td align='center'><button type='button' onclick='delRincian($isi->id_rincian)' class='btn btn-xs btn-flat btn-danger'>Del</button></td>
        </tr>
      ";
    }
  } ?>
  <?php if ($show_rincian->num_rows() > 0): ?>
  	<?php foreach ($show_rincian->result() as $res ): ?>
  		<tr>
  			<td><?php echo $res->item ?></td>
  			<td><?php echo $res->qty ?></td>
  			<td><?php echo $res->harga ?></td>
  			<td>
  				<?php if ($res->ppn==1){ ?>
  					Ya
  				<?php }else{echo "Tidak"; } ?>
  			</td>
  			<td><?php echo $res->keterangan ?></td>
  			<td align='center'><button type='button' onclick='delRincian(<?php echo $res->id_rincian ?>)' class='btn btn-xs btn-flat btn-danger'>Del</button></td>
  		</tr>
  	<?php endforeach ?>
  <?php endif ?>
  <tr>
    <td width="20%"><input class="form-control" id="item"></td>
    <td><input class="form-control" id="qty"></td>
    <td><input class="form-control" id="harga"></td>
    <td>
      <select class="form-control" id="ppn">
        <option value="1">Ya</option>
        <option value="0">Tidak</option>
      </select>
    </td>
    <td><input class="form-control" id="keterangan"></td>
    <td align="center">
      <button class="btn btn-xs btn-flat btn-primary" type="button" onclick="saveRincian()"> Add</button>      
    </td>
  </tr>         
</table> 