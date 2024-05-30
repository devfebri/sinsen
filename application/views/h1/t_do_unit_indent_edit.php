<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="10%">ID SPK</th>
      <th width="10%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="15%">Nama Konsumen</th>      
      <th width="10%">Qty on Hand</th>      
      <th width="10%">Qty RFS</th>        
      <th width="10%">Qty DO</th>  
      <!-- <th width="10%">Harga</th>  
      <th width="10%">Total</th>   -->
      <th width="10%">Action</th>                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $isi_po=1;$jum=0;$gt=0;
  $hal = $dt_do_ind->num_rows() + 1;
  foreach($dt_do_ind->result() as $row) {       
    $t = $this->db->query("SELECT * FROM tr_po_dealer_indent INNER JOIN tr_do_indent_detail ON tr_po_dealer_indent.id_indent=tr_do_indent_detail.id_indent 
          WHERE tr_do_indent_detail.no_do = '$row->no_do'")->row();    
    
    echo "
    <tr>
      <td width='10%'>$t->id_spk</td>
      <td width='10%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='15%'>$t->nama_konsumen</td>  
      <td width='10%'>$row->qty_on_hand unit</td>
      <td width='10%'>$row->qty_rfs unit</td>
      <td width='10%'>$row->qty_do unit</td>
      "; ?>
     
      <td width='10%'>
        <button type="button" onclick="hapus_indent(<?php echo $t->id_indent ?>,<?php echo $t->id_do_indent_detail ?>)" class="btn btn-xs bg-maroon btn-flat"><i class="fa fa-trash-o"></i> Delete</button>
      </td>      
    </tr>
    <?php    
    }
  ?>   
</table>

<!-- <td width='10%' align='right'>".mata_uang3($row->harga)."</td>
      <td width='10%' align='right'>".mata_uang3($row->qty_do * $row->harga)."</td> -->
