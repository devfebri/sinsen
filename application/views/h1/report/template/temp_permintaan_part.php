<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PermintaanPart_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Kode Part</td>
    <td align="center">Nama Part</td>
 		<td align="center">Deskripsi</td>
    <td align="center">Qty</td>         
    <td align="center">No Rekap</td>        
    <td align="center">Kode</td>        
    <td align="center">Nama Ekspedisi</td>        
    <td align="center">No SO</td>         
 		<td align="center">No SJ Part</td> 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT * FROM tr_po_checker INNER JOIN tr_po_checker_detail ON tr_po_checker.no_po = tr_po_checker_detail.no_po     
    LEFT JOIN ms_part ON tr_po_checker_detail.id_part = ms_part.id_part    
    WHERE tr_po_checker.tgl_checker BETWEEN '$tgl1' AND '$tgl2'");
 	foreach ($sql->result() as $isi) {    
    // $cek = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
    //     INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit
    //     LEFT JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi = ms_vendor.id_vendor
    //     WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'");
    // $id_vendor = ($cek->num_rows() > 0) ? $cek->row()->ekspedisi : "" ;
    // $vendor_name = ($cek->num_rows() > 0) ? $cek->row()->vendor_name : "" ;
 		echo "
 		<tr>
      <td>$no</td>            
      <td>$isi->id_part</td>                  
      <td>$isi->nama_part</td>                  
      <td></td>                  
      <td>$isi->qty_pemenuhan</td>                  
      <td></td>                   
      <td></td>                   
      <td>$isi->no_po</td>                  
      <td>$isi->no_sj</td>                  
 			<td></td> 			            
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
