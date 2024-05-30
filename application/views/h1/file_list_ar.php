<?php 
$no = date("his");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=List_AR_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
function mata_uang2($a){      
	if(is_numeric($a) AND $a != 0 AND $a != ""){
		return number_format($a, 0, ',', '.');
	}else{
		return $a;
	}
}
?>
<table border="1" class="table table-bordered table-hover">
  <thead>
    <tr>              
      <th>No Transaksi</th>                           
      <th>Tgl Transaksi</th>              
      <th>Vendor</th>                                          
      <th>Total</th>
    </tr>
  </thead>
  <tbody>            
  <?php           
  $g_total=0;            
  foreach($dt_invoice->result() as $row) {                                                         
    $total_bayar = $this->m_admin->get_detail_inv_dealer($row->no_do);    
    $cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar['total_bayar']);
    if ($cek>0) {
       echo "          
      <tr>               
        <td>$row->no_faktur</td>                            
        <td>$row->tgl_faktur</td>                            
        <td>$row->nama_dealer</td>
        <td align='right'>".mata_uang2($cek)."</td>             
      </tr>";
      $g_total += $cek;                                         
    }       
  }

  
   foreach($dt_rekap->result() as $row) {                                         
    //$cek = $this->m_admin->cekPembayaran($row->no_bastd,$row->total);
    //  if ($cek>0){
      echo "          
      <tr>                                                 
        <td>$row->no_bastd</td>                            
        <td>$row->tgl_rekap</td>
        <td>$row->nama_dealer</td>
        <td align='right'>".mata_uang2($cek)."</td>    
      </tr>                                      
        ";   
      $g_total += $cek;                                          
    //  }
    }
    
  ?>
  </tbody>
  <tfoot>
  	<tr>
  		<td colspan="3">Grand Total</td>
  		<td align="right"><?php echo mata_uang2($g_total) ?></td>
  	</tr>
  </tfoot>
</table>
      