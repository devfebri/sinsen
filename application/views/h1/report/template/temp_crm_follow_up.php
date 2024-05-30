<?php 
$no = $bulan_awal."-".$bulan_akhir;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=CRM-Prospek_followup ".$no." ".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1">  
 	<tr> 		 		
	 <td align="center">Leads ID</td> 
	 <td align="center">Nama</td> 
	 <td align="center">No. HP</td> 
	 <td align="center">Platform</td> 
	 <td align="center">Deskripsi Event</td> 
	 <td align="center">Source Data</td> 
	 <td align="center">CMS Source</td> 
	 <td align="center">Customer Action Date</td> 
	 <td align="center">Status RO</td> 
	 <td align="center">Main Dealer</td> 
	 <td align="center">Assigned Dealer</td> 
	 <td align="center">Tanggal Assign</td> 
	 <td align="center">Pernah Assign</td> 
	 <td align="center">Pernah Terhubung</td> 
	 <td align="center">Jumlah Follow UP MD</td> 
	 <td align="center">Jumlah Follow UP Dealer</td> 
	 <td align="center">Last Follow UP By</td> 
	 <td align="center">Tanggal Follow UP</td> 
	 <td align="center">Status Kontak Follow UP</td> 
	 <td align="center">Hasil Status Follow UP</td> 
	 <td align="center">Tanggal Next Follow UP</td> 
	 <td align="center">Status Prospect</td> 
	 <td align="center">Tipe Motor Deal</td> 
	 <td align="center">On Time SLA 1</td> 
	 <td align="center">On Time SLA 2</td> 
	 <td align="center">Alasan Tidak Deal</td> 
	 <td align="center">Frame No</td> 
	 <td align="center">Sales Date AHM</td> 
 	</tr>

<?

var_dump($temp_data );
die();

  foreach ($temp_data as $isi) { ?>
 		<tr>
		 <td align="center"><?= $isi->leads_id ?></td> 
		<td align="center"><?= $isi->nama?></td> 
		<td align="center"><?= $isi->noHP?></td> 
		<td align="center"><?= $isi->platformData?></td> 
		<td align="center"><?= $isi->deskripsiEvent?></td> 
		<td align="center"><?= $isi->sourceData?></td> 
		<td align="center"<?= $isi->cmsSource?></td> 
		<td align="center"><?= $isi->customerActionDate?></td> 
		<td align="center">Status RO</td> 
		<td align="center">E20</td> 
		<td align="center"><?= $isi->assignedDealer?></td> 
		<td align="center"><?= $isi->tanggalAssignDealer?></td> 
		<td align="center">Pernah Assign</td> 
		<td align="center">Pernah Terhubung</td> 
		<td align="center">Jumlah Follow UP MD</td> 
		<td align="center"><?= $isi->JumlahFollowupDealer?></td> 
		<td align="center"><?= $isi->lastFollowup?></td> 
		<td align="center"><?= $isi->lastFollowup?></td> 
		<td align="center">Status Kontak Follow UP</td> 
		<td align="center"> <?= $isi->status_prospek?></td> 
		<td align="center"><?= $isi->tanggalNextFoll?></td> 
		<td align="center"><?= $isi->statusProspek?></td> 
		<td align="center"><?= $isi->kodeTypeUnitDeal?></td> 
		<td align="center"><?= $isi->leads_sla?></td> 
		<td align="center"><?= $isi->ontimeSLA2?></td> 
		<td align="center"><?= $isi->kodeAlasanNotProspectNotDeal?></td> 
		<td align="center"><?= $isi->frameNo?></td> 
		<td align="center"></td> 
 		</tr>
 	<?php }
 	?>
</table>
