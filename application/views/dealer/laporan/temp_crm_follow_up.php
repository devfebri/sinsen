<?php 
$no = $bulan_awal."-".$bulan_akhir;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=CRM-Prospek_followup  ".$no.".xls");
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
	 <td align="center">Created At</td> 
	 <!-- <td align="center">Status RO</td>  -->
	 <!-- <td align="center">Main Dealer</td>  -->
	 <td align="center">Assigned Dealer</td> 
	 <td align="center">Tanggal Assign</td> 
	 <td align="center">Pernah Assign</td> 
	 <td align="center">Pernah Terhubung</td> 
	 <td align="center">Jumlah Follow UP Dealer</td> 
	 <td align="center">Last Follow UP By</td> 
	 <td align="center">Tanggal Follow UP</td> 
	 <!-- <td align="center">Status Kontak Follow UP</td>  -->
	 <td align="center">Hasil Status Follow UP</td> 
	 <td align="center">Tanggal Next Follow UP</td> 
	 <td align="center">Status Prospect</td> 
	 <td align="center">Tipe Motor Deal</td> 
	 <td align="center">Alasan Tidak Deal</td> 
	 <!-- <td align="center">Frame No</td>  -->
	 <td align="center">Sales Date AHM</td> 
 	</tr>
<?
  foreach ($prospek_crm as $isi) { ?>
 		<tr>
			
		<td align="center"><?=$isi->leads_id ?></td> 
		<td align="center"><?=$isi->nama_konsumen?></td> 
		<td align="center">'<?=$isi->no_hp?></td> 
		<td align="center"><?=$isi->platformData?></td> 
		<td align="center"><?=$isi->deskripsiEvent?></td> 
		<td align="center"><?=$isi->id_cdb?></td> 
		<td align="center">5</td> 
		<td align="center"><?=$isi->tgl_assign?></td> 
		<!-- <td align="center">Status RO</td>  -->
		<!-- <td align="center">E20</td>  -->
		<td align="center">'<?=$isi->assignedDealer?></td> 
		<td align="center"><?=$isi->tgl_prospek?></td> 
		<!-- <td align="center">Pernah Assign</td>  -->
		<td align="center">1</td> 
		<!-- <td align="center">Pernah Terhubung</td>  -->
		<td align="center">
		<?=($isi->JumlahFollowupDealer !== 0) ? "Ya" : "-";?>
		<td align="center"><?=$isi->JumlahFollowupDealer?></td> 
		<td align="center"><?=$isi->karyawan_last_fol?></td> 
		<td align="center"><?=$isi->last_fol?></td> 
		<!-- <td align="center">Status Kontak Follow UP</td>  -->
		<!-- <td align="center"></td>  -->
		<td align="center"> <?=$isi->status_prospek?></td> 
		<!-- <td align="center"></td>  -->
		<td align="center"><?=$isi->next_fol?></td> 
		<td align="center">
		<?=($isi->status_prospek !== NULL) ? "Prospek" : "-";?>
		</td> 
		<td align="center"><?=$isi->kodeTypeUnitDeal?></td> 
		<td align="center"><?=$isi->kodeAlasanNotProspectNotDeal?></td> 
		<!-- <td align="center"><? //$isi->frameNo?></td>  -->
		<td align="center"></td> 
 		</tr>
 	<?php }
 	?>
</table>
