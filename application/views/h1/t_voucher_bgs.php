<?php function mata_uang2($a){
  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);      
    if(is_numeric($a) AND $a != 0 AND $a != ""){
      return number_format($a, 0, ',', '.');
    }else{
      return $a;
    }
} ?>


<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>No BG </th>
            <th>Tgl.Jatuh Tempo </th>                                                                                                                                
            <th>Nominal </th>                                                                                
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      $total=0;
      foreach($dt_bg->result() as $row) {     
        $total +=$row->nominal_bg;
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->no_bg</td>
          <td>$row->tgl_bg</td>
          <td align='right'>".mata_uang2($row->nominal_bg)."</td>                
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_bg('<?php echo $row->id_voucher_bank_bg; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    <tr>
      <td colspan="3"><b>Total</b></td>
      <td align="right"><span><b><?= mata_uang2($total) ?></b></span></td>
      <td></td>
    </tr>
    <input type="hidden" id='total_bayar' value="<?= $total ?>">
    </tbody>
</table>
