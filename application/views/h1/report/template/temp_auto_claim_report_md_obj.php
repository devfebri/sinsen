<?php 
$no = $start_periode."-".$end_periode;
$query_set_tipe_concate = $tipe_kendaraan ;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Autoclaim - Main Dealer - ".$sales_program->jenis_program." |".$sales_program->id_program_md.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>

<style>

td {
	font-family: "Calibri", Times, serif;
}

.title{
	font-weight: bold;
	text-align: center; 
    vertical-align: middle;
}

</style>
</head>

<table>  

	<tr>
		<td colspan="2" ><b>Claim Report Main Dealer</b></td>
		<td ></td>
	</tr>
	<tr>
		<td colspan="2" >Periode </td>
		<td ><?=$sales_program->periode_awal." - ".$sales_program->periode_akhir?></td>
	</tr>
	<tr>
		<td colspan="2" >Type </td>
		<td > Cub / AT / Sport</td>
	</tr>

	<tr>
		<td colspan="2" >No Juklak</td>
		<td ><?=$sales_program->no_juklak_md?></td>
	</tr>

	<tr>
		<td colspan="2" >Jenis</td>
		<td ><?=$sales_program->jenis_program?> | <?=$sales_program->id_jenis_sales_program?>   </td>
	</tr>
</table>
<br><br>
<?php 		
		$var = 8;
		$check=count($sales_program_syarat);
		$kontribusi_credit=6 + $check;
		$header = 17+($check*2);
		?>

 <table border="1">  
 	<tr style="background-color: #f8cbad;"> 		 		
		<td align="center"  rowspan="6"  class="title"  width="20px" >No</td> 
		<td align="center"  rowspan="6" style="width:30%"  class="title">Dealer</td> 
		<td align="center"  rowspan="6" class="title"> Kode Dealer</td> 
		<td align="center"  rowspan="1"  colspan="<?=$header?>" class="title"   style="height:50">Sales Program : <?=$sales_program->id_program_md?> <?=$query_set_tipe_concate->tipe_kendaraan?> </td> 
 	</tr>
	 <tr> 		 		
		<td align="center"  rowspan="4" style="width:50" style="background-color: #f8cbad;"  width="15%"  class="title">SSU</td> 
		<td align="center"  rowspan="4"  style="background-color: #fce4d6;"  class="title">Total Nilai Klaim Dealer kepada MD based on klaim yg di approve</td> 
		<td align="center"  rowspan="4"  style="background-color: #fce4d6;"  class="title">Total Unit Klaim D yang di Approve</td> 
		<td align="center"  style="background-color: #f8cbad;" ></td> 

		<td align="center"  colspan="<?=$kontribusi_credit?>"   style="background-color: #ffd966;" class="title" >Kontribusi Credit (inc PPN)</td> 	
		<td align="center"  colspan="<?=1 +$kontribusi_credit?>"   style="background-color: #ffd966;"  class="title">Kontribusi Cash (inc PPN) :</td> 	
 	</tr>
	<tr>
		<td align="center"  rowspan="3" style="width:50"  style="background-color: #f8cbad;"  class="title" width="15%">SSU</td> 
		<td  style="background-color: #d8e0f1;" >AHM :</td> 
		
		<!-- cash RP. 001  -->
		<td  colspan="2"  style="background-color: #d8e0f1;"  ><?=$sales_program->ahm_kredit?></td> 
		
		<td align="center"   rowspan="2"   colspan="2" style="background-color: #305496;   color: white;"  class="title">AHM+MD : </td> 

		<!-- cash - AHM+MD : -->
		<!-- variable jumlah -->
		<td  rowspan="2" style="background-color: #305496;" ><?= $variable_ahm_plus_md_credit = ($sales_program->md_kredit)+($sales_program->ahm_kredit)?> </td> 

		<td align="center"   rowspan="3" colspan="<?=$check?>"  style="background-color: #a9d08e;"  class="title">Syarat yang di Reject :</td> 
		<td align="center"   rowspan="3"  style="background-color: #f8cbad;"  class="title" width="15%"> SSU</td>
		<!-- cash -->
		<td  style="background-color: #d8e0f1;">AHM</td>
		<td  colspan="2"  style="background-color: #d9e1f2;"><?=$sales_program->md_cash?></td>
		<td  align="center"   rowspan="2" colspan="2"  style="background-color: #305496;  color: white;"  class="title" >AHM+MD :</td>

		<!-- cash = AHM + MD-->
		<!-- variable -->
		<td  rowspan="2" style="background-color: #305496;"><?=  $variable_ahm_plus_md_cash = ($sales_program->md_cash)+($sales_program->ahm_cash)?></td>
		<td  rowspan="3" colspan="<?=$check?>"  style="background-color: #a9d08e;"  class="title">Syarat yang di Reject :	</td>
		<tr>
			<td  style="background-color: #b4c6e7;">MD :</td>
				<!-- cash RP. 001  -->
			<td  colspan="2"  colspan="2" style="background-color: #b4c6e7;" ><?=$sales_program->md_kredit?></td> 
			<td  style="background-color: #b4c6e7;">MD :</td>
			<td  colspan="2"  style="background-color: #b4c6e7;"><?=$sales_program->md_cash?></td>
		</tr>
		<tr>
				<!-- dealer Kredit -->
			<td  style="background-color: #8ea9db;">D :</td>
				<!-- cash RP. 003  -->
			<td colspan="2"  style="background-color: #8ea9db;" ><?=$sales_program->dealer_kredit?></td> 
			<td align="center"   colspan="2"   style="background-color: #f7caac;" class="title">AHM+MD+D :</td> 
			<td   style="background-color: #f8cbad;" ><?=($sales_program->md_kredit)+($sales_program->dealer_kredit)+($sales_program->ahm_kredit)?></td> 
			<!-- ddealer Cash -->

			<td  style="background-color: #8ea9db;">D :</td>
			<td  colspan="2"   style="background-color: #8ea9db;"><?=$sales_program->dealer_cash?></td>
			<td  align="center" colspan="2" style="background-color: #f7caac;" class="title">AHM+MD+D :</td>
			<td    style="background-color: #f8cbad;" ><?=($sales_program->md_cash)+($sales_program->dealer_cash)+($sales_program->ahm_cash)?></td>

		</tr>
		<td align="center"  rowspan="1" style="background-color: #f8cbad; " height="100"  class="title">Total</td> 
		<td align="center"  colspan="2"  style="background-color: #f8cbad;"  class="title"> <?=$sales_program->tipe_ahm?> | <?=$tipe_kendaraan?></td> 
		<td align="center"  rowspan="1" style="background-color: #f8cbad;" class="title">Credit</td> 
		<td align="center"  rowspan="1" style="background-color: #f8cbad;" class="title">Claim by Dealer</td> 
		<td align="center"  rowspan="1" style="background-color: #f8cbad;" class="title">Approved by AHM</td> 
		<td align="center"  rowspan="1" style="background-color: #f8cbad;" class="title">Approved to Dlr</td> 
		<td align="center"  rowspan="1" style="background-color: #f8cbad;  color: red;" class="title">Reject to Dlr</td>
		<td align="center"  rowspan="1" style="background-color: #f8cbad;" class="title">Reason for Reject / selisih klaim dg penj</td> 
		<td align="center"  rowspan="1"  style="background-color: #ffd966;" class="title" >Nilai Klaim yang di Approve to Dlr utk Penj Cr</td> 
		<? foreach ($sales_program_syarat as $field) {?>
			<td  align="center"  style="background-color: #a9d08e;" class="rotate"><?=$field->syarat_ketentuan?></td>
		<?}?>
		<td   style="background-color: #f8cbad;" class="title">Cash</td>
		<td   style="background-color: #f8cbad;" class="title">Claim by Dealer</td>
		<td   style="background-color: #f8cbad;" class="title">Approved by AHM</td>
		<td   style="background-color: #f8cbad;" class="title">Approved to Dlr</td>
		<td   style="background-color: #f8cbad; color: red;" class="title">Reject to Dlr</td>
		<td   style="background-color: #f8cbad;" class="title">Reason for Reject / selisih klaim dg penj</td>
		<td   style="background-color: #ffd966;" class="title" >Nilai Klaim yang di Approve to Dlr utk Penj Cs</td>
		<?foreach ($sales_program_syarat as $field) {?>
			<td  align="center"  style="background-color: #a9d08e;" class="rotate"><?=$field->syarat_ketentuan?></td>
		<?}?>
		</tr>
		<tbody>
		<? 
		$no_h = 0;
		foreach ($value[$no_h]['header'] as $field =>$key) {
		$num = 0;
		foreach ($value[$no_h]['header'] as $set =>$key) {
		?>
			<tr>
			<td><?=$num+1?></td>
				<td><?=$key->dealer?></td>
				<td><?=$key->kode_dealer_md?></td>
				<td><?=$key->ssu_total_full?></td>
				<td><?=$key->nilai_claim_d_kepada_md?></td>
				<td><?=$key->total_claim_d_di_approve?></td>
				<td><?=$key->ssu_total_credit?></td>
				<td><?=$key->ssu_total_claim_by_dealer_credit?></td>
				<td><?=$key->ssu_total_approve_by_ahm_credit?></td>
				<td><?=$key->ssu_total_approve_by_dealer_credit?></td>
				<td><?=$key->ssu_total_reject_reason_credit?></td>
				<td></td>
				<td><?=$key->ssu_total_nilai_claim_credit?></td>
				
				<? 
				if (isset($key->syarat_yang_direject_kredit)) {
				foreach ($key->syarat_yang_direject_kredit as $syarat) { ?>
				<td><?= $syarat->jumlah === NULL ? 0 : $syarat->jumlah ?></td>
  			 	<?} }
				 ?>
					<td><?=$key->ssu_total_cash?></td>
					<td><?=$key->ssu_total_claim_by_dealer_cash?></td>
					<td><?=$key->ssu_total_approve_by_ahm_cash?></td>
					<td><?=$key->ssu_total_approve_by_dealer_cash?></td>
					<td><?=$key->ssu_total_reject_by_dealer_cash?></td>
					<td></td>
					<td><?=$key->ssu_total_reject_reason_cash?></td>
				<?

				if (isset($key->syarat_yang_direject_cash)) {
				foreach ($key->syarat_yang_direject_cash as $syarat) { ?>
				<td><?= $syarat->jumlah === NULL ? 0 : $syarat->jumlah ?></td>
  			 	<? 
				} 
				}?>
			</tr>
			<? 
			$num++;
			}

			if ($footers[$no_h]->kabupaten !== NULL ){
			?>
				<tr style="background-color: #ffd966;">
				<td colspan="3"><?=$footers[$no_h]->kabupaten?></td>
				<td><?=$footers[$no_h]->ssu_total_full?></td>
				<td><?=$footers[$no_h]->nilai_claim_d_kepada_md?></td>
				<td><?=$footers[$no_h]->total_claim_d_di_approve?></td>
				<td><?=$footers[$no_h]->ssu_total_credit?></td>
				<td><?=$footers[$no_h]->ssu_total_claim_by_dealer_credit?></td>
				<td><?=$footers[$no_h]->ssu_total_approve_by_ahm_credit?></td>
				<td><?=$footers[$no_h]->ssu_total_approve_by_dealer_credit?></td>
				<td><?=$footers[$no_h]->ssu_total_reject_by_dealer_credit?></td>
				<td><?=$footers[$no_h]->ssu_total_reject_reason_credit?></td>
				<td><?=$footers[$no_h]->ssu_total_nilai_claim_credit?></td>
				<?
				foreach ($footers[$no_h]->syarat_kredit_footer as $kredit) { ?>
					<td><?= $kredit->jumlah = ($kredit->jumlah === null) ? 0 : $kredit->jumlah;?></td>
				<?}?>
				<td><?=$footers[$no_h]->ssu_total_cash?></td>
				<td><?=$footers[$no_h]->ssu_total_claim_by_dealer_cash?></td>
				<td><?=$footers[$no_h]->ssu_total_approve_by_ahm_cash?></td>
				<td><?=$footers[$no_h]->ssu_total_approve_by_dealer_cash?></td>
				<td><?=$footers[$no_h]->ssu_total_reject_by_dealer_cash?></td>
				<td><?=$footers[$no_h]->ssu_total_reject_reason_cash?></td>
				<td><?=$footers[$no_h]->ssu_total_nilai_claim_cash?></td>
				<?
				foreach ($footers[$no_h]->syarat_cash_footer as $cash) { ?>
					<td><?= $cash->jumlah = ($cash->jumlah === null) ? 0 : $cash->jumlah;?></td>
				<?}?>
				<?
				}
				$no_h++; 
				?>
			<?} ?>
		</tbody>


	<tr  style="background-color: #d8e0f1;">
			<td></td>
			<td class="title" >TOTAL</td>
			<td></td>
			<td><?=$total['set_total_ssu_total_full']?></td>
			<td><?=$total['set_total_nilai_claim_d_kepada_md']?></td>
			<td><?=$total['set_total_total_claim_d_di_approve']?></td>
			<td><?=$total['set_total_ssu_total_credit']?></td>
			<td><?=$total['set_total_ssu_total_claim_by_dealer_credit']?></td>
			<td><?=$total['set_total_ssu_total_approve_by_ahm_credit']?></td>
			<td><?=$total['set_total_ssu_total_approve_by_dealer_credit']?></td>
			<td><?=$total['set_total_ssu_total_reject_by_dealer_credit']?></td>
			<td></td>
			<td><?=$total['set_total_ssu_total_nilai_claim_credit']?></td>
			<?
			foreach($total['set_total_syarat_kredit_footer'] as  $tot){
				?>
				<td><?=$tot->jumlah?></td>
			<?}
			?>
			<td><?=$total['set_total_ssu_total_cash']?></td>
			<td><?=$total['set_total_ssu_total_claim_by_dealer']?></td>
			<td><?=$total['set_total_ssu_total_approve_by_ahm_cash']?></td>
			<td><?=$total['set_total_ssu_total_approve_by_dealer_cash']?></td>
			<td><?=$total['set_total_ssu_total_reject_by_dealer_cash']?></td>
			<td><?=$total['set_total_ssu_total_reject_reason_cash']?></td>
			<td><?=$total['set_total_ssu_total_nilai_claim_cash']?></td>
			<?
			foreach($total['set_total_syarat_cash_footer'] as  $tot){
				?>
				<td><?=$tot->jumlah?></td>
			<?} ?>
	</tr>
	<?
	$approve_ahm = $total['set_total_ssu_total_approve_by_dealer_credit'] + $total['set_total_ssu_total_approve_by_dealer_cash'];
	$approve_md  = $total['set_total_ssu_total_approve_by_ahm_credit'] + $total['set_total_ssu_total_approve_by_ahm_cash'];
	$surplus = $approve_md -$approve_ahm ;


	?>
	<tr  style="background-color: #ffd966;">
		<td></td>
		<td>Total Approval dari AHM (based on approval MD di PIM)</td>
		<td ></td>
		<td ></td>
		<td ></td>
		<td ><?=$approve_ahm?></td>
	</tr>

	<tr  style="background-color: #ffd966;">
		<td></td>
		<td>Surplus / (minus)</td>
		<td ></td>
		<td ></td>
		<td ></td>
		<td ><?=$surplus ?></td>
	
	</tr>

	<tr  style="background-color: #ffd966;">
		<td></td>
		<td>Total Approve to Dealer</td>
		<td ></td>
		<td ></td>
		<td ></td>
		<td ><?=$approve_md?></td>
	</tr>

</table>
</html>



