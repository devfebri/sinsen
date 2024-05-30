<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption Details_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption Details <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>Channel</b></td>
		<td align="center"><b>Nomor Parts</b></td>
 		<td align="center"><b>Deskripsi Parts</b></td>
 		<td align="center"><b>Deskripsi Unit</b></td>
		<td align="center"><b>Grup Parts</b></td>
 		<td align="center"><b>Jenis Kelompok</b></td>
 		<td align="center"><b>9 Segment</b></td>
		<td align="center"><b>Kuantitas</b></td>
 		<td align="center"><b>Harga</b></td>
		<td align="center"><b>No.ID</b></td>
 	</tr>
	 <?php 
	if($ps_details->num_rows()>0){
		foreach ($ps_details->result() as $row) { 
			$desk_unit = $this->db->query("SELECT mtk.tipe_ahm as tipe_ahm, ms.segment as segment
			from tr_h23_nsc C 
			join tr_h2_wo_dealer wo on C.id_referensi=wo.id_work_order 
			join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form
			join ms_customer_h23 cust on cust.id_customer = sa.id_customer 
			left join ms_tipe_kendaraan mtk on mtk.id_tipe_kendaraan = cust.id_tipe_kendaraan
			join ms_segment ms on mtk.id_segment=ms.id_segment  
			WHERE C.no_nsc='$row->no_nsc'")->row_array();
		echo "
 			<tr>
 				<td>$row->nama_dealer</td>
				<td>$row->channel_h123</td>
				<td>$row->id_part</td>
				<td>$row->nama_part</td>";?>
			<?php 
				if($desk_unit['tipe_ahm'] != NULL){
			?>
				<td><?php echo $desk_unit['tipe_ahm']?></td>
			<?php  
				}else{
			?>
				<td> - </td>
			<?php }?>	
			<td><?php echo $row->kelompok_part ?></td>
			<td><?php echo$row->klp_part?></td>
			<?php
				  if($row->referensi == 'work_order'){
			?>
					<td><?php echo $desk_unit['segment']?></td>
			<?php 
				}else{
			?>
					<td>Direct Part Sales</td>
			<?php
				}
		echo   "<td>$row->qty</td>
			  	<td>$row->harga_beli</td>
			  	<td>$row->no_nsc</td>
 			</tr>
	 	";	
 		}
	}else{
		echo "<td colspan='11' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>