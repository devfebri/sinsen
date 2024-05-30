
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>                        
            <th>Tgl.Transfer</th>                                                                                                                                
            <th>Nominal </th>                                                                                
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody>     
    <?php 
      $no=1; 
      $total =0;
      if($item_tf = $this->item_tf->get_content()) {
        foreach ($item_tf as $row){     
          $total +=$row['nominal_transfer'];
          // if($tipe == "penerimaan_bank"){
          //   $id = $row->id_penerimaan_bank_transfer;
          // }else{
          //   $id = $row->id_voucher_bank_transfer;
          // }

      echo "          
        <tr>
          <td>$no</td>          
          <td>$row[tgl_transfer]</td>
          <td>$row[nominal_transfer]</td>                
          <td>"; ?>            
            <input type="hidden" id="rowid_<?=$row['id']?>" value="<?= $row['rowid'] ?>"> 
            <button data-toggle="tooltip" title="Delete" class="btn btn-danger btn-flat btn-xs" type="button" onclick="hapus_transfer('<?= $row['rowid']?>')"><i class="fa fa-trash" ></i></button>                  
          </td>            
        </tr>
        <?php
        $no++;
        }
      }
    ?>
    <tr>
      <td colspan="2">Total</td>
      <td align='right'><?= $total ?></td>
    </tr>
    </tbody>
    <input type="hidden" id="total_bayar" value="<?= $total ?>">
</table>
