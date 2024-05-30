<?php 
$no = $bulan."-".$tahun;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LeadTimeEkspedisi_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
  <tr>
    <td colspan="2 ">Laporan Form Monitoring Lead Time AHM-MD EKspedisi Per Bulan</td>    
  </tr>
  <tr>
    <td>Month</td>
    <td>: <?php 
    $bln = sprintf("%'.02d",$bulan);
    echo $bln."-".$tahun ?></td>
  </tr>
</table>
<table border="1">  
 	<tr> 		 		
    <td  align="center">Ekspedisi</td>    
    <td  align="center">No Polisi</td>    
    <td  align="center">No Shipping List</td>    
    <td  align="center">Qty</td>    
    <td  align="center">Tgl Surat Angkutan</td>    
    <td  align="center">Tgl Penerimaan</td>    
    <td align="center">Lead Time</td>    
 	</tr>
  <?php   
  $bln = sprintf("%'.02d",$bulan);
  $tgl = $tahun."-".$bln;
  $sql = $this->db->query("SELECT *,count(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit 
    INNER JOIN tr_penerimaan_unit_detail ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
    INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_detail.no_shipping_list = tr_scan_barcode.no_shipping_list
    WHERE LEFT(tr_penerimaan_unit.tgl_penerimaan,7) = '$tgl' AND tr_penerimaan_unit.status = 'close'
    GROUP BY tr_scan_barcode.no_shipping_list");
  foreach ($sql->result() as $isi) {    
    $tgl1 = strtotime($isi->tgl_surat_jalan); 
    $tgl2 = strtotime($isi->tgl_penerimaan);     
    $diff = $tgl2 - $tgl1; 
    $us = floor($diff / (60 * 60 * 24));            

    echo "
    <tr>
      <td>$isi->ekspedisi</td>
      <td>$isi->no_polisi</td>
      <td>$isi->no_shipping_list</td>
      <td>$isi->jum</td>
      <td>$isi->tgl_surat_jalan</td>
      <td>$isi->tgl_penerimaan</td>
      <td>$us</td>
    </tr>
    ";
  }
  ?>
</table>
