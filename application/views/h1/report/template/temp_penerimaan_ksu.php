<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PenerimaanKSU_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">No Penerimaan Aksesoris</td>
    <td align="center">Tgl Penerimaan</td>
 		<td align="center">Ekspedisi</td>
    <td align="center">Kode KSU</td>         
    <td align="center">KSU</td>        
    <td align="center">Qty</td>         
    <td align="center">Lokasi</td>            
 	</tr>
 	<?php  	
 	$no=1;
  $where = "";
  if($id_ksu!="") $where .= "AND tr_surat_jalan_ksu.id_ksu = '$id_ksu'";
  if($id_vendor!="") $where .= "AND tr_penerimaan_unit.ekspedisi = '$id_vendor'";

  $sql = $this->db->query("SELECT * FROM tr_penerimaan_unit 
    INNER JOIN tr_penerimaan_ksu ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_ksu.id_penerimaan_unit
    LEFT JOIN ms_ksu ON tr_penerimaan_ksu.id_ksu = ms_ksu.id_ksu
    LEFT JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi = ms_vendor.id_vendor
    WHERE tr_penerimaan_unit.tgl_penerimaan BETWEEN '$tgl1' AND '$tgl2' AND tr_penerimaan_unit.status = 'close' $where");
  foreach ($sql->result() as $row) {
    echo "
    <tr>
      <td>$no</td>
      <td>$row->id_penerimaan_unit</td>
      <td>$row->tgl_penerimaan</td>
      <td>$row->vendor_name</td>
      <td>$row->id_ksu</td>
      <td>$row->ksu</td>
      <td>$row->qty</td>
      <td></td>      
    </tr>
    ";
    $no++;
  }
 	?>
</table>
