<?php 
$no = date('d-m-Y_His');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=SSP_OEM_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");

function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Part Number</td>
 		<td align="center">Deskripsi</td>
 		<td align="center">Serial Number</td>
 		<td align="center">No Shipping</td>
 		<td align="center">Tgl Shipping AHM</td>
 		<td align="center">Tgl Penerimaan MD</td>
 		<td align="center">Kode Dealer</td>
 		<td align="center">Tgl Surat Jalan</td>
 		<td align="center">Tgl Penerimaan Dealer</td>
		<td align="center">Status</td>
 	</tr>
 	<?php 

	$get_stok = $this->db->query("
	select a.part_id , a.part_desc, a.serial_number , a.no_shipping_list , a.tgl_shipping_list , a.status_scan , 
	b.tanggal_terima_md as mdReceiveDate, c.kode_dealer_md as dealerCode , b.tgl_surat_jalan mdSLDate, b.tanggal_terima_dealer as dealerReceiveDate  
	from tr_shipping_list_ev_accoem a 
	join tr_stock_battery b on a.serial_number  =b.serial_number 
	left join ms_dealer c on b.id_dealer = c.id_dealer
	where b.acc_status <5
	");

	$no = 1;
	if($get_stok->num_rows() >0){
		foreach($get_stok->result() as $row) {
			$dealer = '';
			if($row->status_scan == 1){
				if($row->mdSLDate !='' && $row->dealerReceiveDate !=''){
					$dealer = "'".$row->dealerCode;
					$status = 'Stok Dealer';
				}else if($row->mdSLDate !='' && $row->dealerReceiveDate ==''){
					$dealer = "'".$row->dealerCode;
					$status = 'Intransit Dealer';
				}else if($row->dealerCode !='' && $row->mdSLDate ==''){
					$dealer = "'".$row->dealerCode;
					$status = 'Unfill Dealer';
				}else{
					$status = 'Stok MD';
				}
			}else{
				$status = 'Intransit AHM';
			} 
			echo "<tr>
				<td>$no</td>
				<td>$row->part_id</td>
				<td>$row->part_desc</td>
				<td>'$row->serial_number</td>
				<td>$row->no_shipping_list</td>
				<td>$row->tgl_shipping_list</td>
				<td>$row->mdReceiveDate</td>
				<td>$dealer</td>
				<td>$row->mdSLDate</td>
				<td>$row->dealerReceiveDate</td>
				<td>$status</td>
				</tr>	
			";
			$no++;
		}
	}

	$get_intransit = $this->db->query("
	select a.part_id , a.part_desc, a.serial_number , a.no_shipping_list , a.tgl_shipping_list , a.status_scan 
	from tr_shipping_list_ev_accoem a 
	where status_scan =0
	");

	if($get_intransit->num_rows() >0){
		foreach($get_intransit->result() as $row) {
			$status = 'Intransit AHM';
			echo "<tr>
				<td>$no</td>
				<td>$row->part_id</td>
				<td>$row->part_desc</td>
				<td>'$row->serial_number</td>
				<td>$row->no_shipping_list</td>
				<td>$row->tgl_shipping_list</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>$status</td>
				</tr>	
			";
			$no++;
		}
	}
 	?>
</table>