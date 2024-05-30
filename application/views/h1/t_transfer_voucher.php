
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
      foreach($dt_transfer->result() as $row) {    
        $total +=$row->nominal_transfer;
        if($tipe == "penerimaan_bank"){
          $id = $row->id_penerimaan_bank_transfer;
        }else{
          $id = $row->id_voucher_bank_transfer;
        }

      echo "          
        <tr>
          <td>$no</td>          
          <td>$row->tgl_transfer</td>
          <td>$row->nominal_transfer</td>                
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_transfer('<?php echo $id; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>
    <tr>
      <td colspan="2">Total</td>
      <td align='right'><?= $total ?></td>
    </tr>
    </tbody>
    <input type="hidden" id="total_bayar" value="<?= $total ?>">
</table>
