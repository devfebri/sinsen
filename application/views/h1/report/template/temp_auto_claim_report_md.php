<?php 
$no = $start_periode."-".$end_periode;
$query_set_tipe_concate =$tipe_kendaraan ;
// header("Content-type: application/octet-stream");
// header("Content-Disposition: attachment; filename=Autoclaim - Main Dealer - ".$sales_program->jenis_program." |".$sales_program->id_program_md.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");
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
		<td align="center"  rowspan="6"  class="title"  width="10%" >No</td> 
		<td align="center"  rowspan="6" style="width:500"  class="title">Dealer</td> 
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
		<td align="center"  colspan="2"  style="background-color: #f8cbad;"  class="title"> <?=$sales_program->tipe_ahm?> | <?=$query_set_tipe_concate->tipe_kendaraan?></td> 
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


	<?php
		$no = 1;
		$array_ssu_total     = [];
		$array_nilai_claim_d_to_md = [];
		$array_nilai_claim_d   	   = [];

		$array_ssu_credit  	  						= [];
		$array_claim_dealer_credit   	   			= [];
		$array_claim_by_dealer_credit  	  			= [];
		$array_approve_ahm_credit   	   			= [];
		$array_approve_by_dealer_credit   	   		= [];
		$array_ssu_reject_to_dealer_credit   	   	= [];

		$array_nilai_claim_approve_dealer_credit   	= [];

		$array_ssu_cash	  						= [];
		$array_claim_dealer_cash 	   			= [];
		$array_claim_by_dealer_cash	  			= [];
		$array_approve_ahm_cash 	   			= [];
		$array_approve_by_dealer_cash 	   		= [];
		$array_ssu_reject_to_dealer_cash 	   	= [];
		$array_nilai_claim_approve_dealer_cash 	= [];

		$nilai_claim_approve_rupiah_cash_kredit = [];
		$nilai_claim_approve_rupiah_cash        = [];
		$nilai_claim_approve_rupiah_kredit      = [];
		$array_total_unit_claim_md = [];

		$data['temp_data'] = array();


		foreach ($auto_claim_md as $field) {
			$query_syarat_kredit = $this->db->query("SELECT cd.id_dealer,cd.id_sales_order,
			sum(case when cs.checklist_reject_md = 0 then 0 else 1 end) as status_reject,
			ar.alasan_reject,
			sps.syarat_ketentuan  from tr_claim_dealer cd 
			left join tr_sales_order so on so.id_sales_order = cd.id_sales_order 
			join tr_spk spk on spk.no_spk = so.no_spk
			left join tr_claim_dealer_syarat cs on cs.id_claim = cd.id_claim 
			join tr_sales_program_syarat sps on sps.id=cs.id_syarat_ketentuan 
			left join ms_alasan_reject ar on ar.id_alasan_reject = cs.alasan_reject 
			WHERE cd.status ='rejected'
			AND cd.id_program_md ='$sales_program->id_program_md'
			AND cd.id_dealer ='$sql->id_dealer'
			AND spk.jenis_beli ='kredit'
			GROUP by cd.id_dealer, 
			cs.id_syarat_ketentuan 
			ORDER by cd.id_sales_order,sps.syarat_ketentuan asc limit $check ")->result();

			$query_syarat_cash = $this->db->query("SELECT cd.id_dealer,cd.id_sales_order,
			sum(case when cs.checklist_reject_md = 0 then 0 else 1 end) as status_reject,
			ar.alasan_reject,
			sps.syarat_ketentuan  from tr_claim_dealer cd 
			left join tr_sales_order so on so.id_sales_order = cd.id_sales_order 
			join tr_spk spk on spk.no_spk = so.no_spk
			left join tr_claim_dealer_syarat cs on cs.id_claim = cd.id_claim 
			join tr_sales_program_syarat sps on sps.id=cs.id_syarat_ketentuan 
			left join ms_alasan_reject ar on ar.id_alasan_reject = cs.alasan_reject 
			WHERE cd.status ='rejected'
			AND cd.id_program_md ='$sales_program->id_program_md'
			AND cd.id_dealer ='$sql->id_dealer'
			AND spk.jenis_beli ='cash'
			GROUP by cd.id_dealer, 
			cs.id_syarat_ketentuan 
			ORDER by cd.id_sales_order,sps.syarat_ketentuan asc limit $check ")->result();


		 if (isset($field->wilayah_urut)){ 

			$obj = new stdclass();
			$obj->wilayah    =  $field->wilayah_urut;
			$obj->ssu_cash   = NULL;
			$obj->ssu_kredit = NULL;
			
			$obj->cash_claim		   = NULL;
			$obj->cash_approve_dealer  = NULL;
			$obj->cash_approve_ahm     = NULL;
			$obj->cash_rejected_dealer = NULL;

			$obj->kredit_claim		     = NULL;
			$obj->kredit_approve_dealer  = NULL;
			$obj->kredit_approve_ahm     = NULL;
			$obj->kredit_rejected_dealer = NULL;
			$data['temp_data'][$field->wilayah_urut] = $obj;
			?>

			<!-- TOTAL KUNING -->

	    	<tr style='background-color: red;'>
			<td  colspan="2" ><b><?=$field->wilayah_urut ?></b></td>
			<td></td>
			<td><?=((int) $field->ssu_total_wilayah)  ?></td>
			<td><?= (int) $field->nilai_claim_total ?></td>
			<td><?= (int) $field->ssu_total_kredit_approve_to_dealer_wilayah +  (int) $field->ssu_total_cash_approve_to_dealer_wilayah?> </td>
			<td><?= (int) $field->ssu_total_kredit_wilayah?></td>
			<td><?= (int) $field->kredit_claim_by_dealer_wilayah ?></td>
			<td><?= (int) $field->ssu_total_kredit_approve_to_by_ahm_wilayah?></td>
			<td><?= (int) $field->ssu_total_kredit_approve_to_dealer_wilayah ?></td>
			<td><?= (int) $field->ssu_total_kredit_reject_to_dealer_wilayah ?></td>
			<td></td>
			<td><?= (int) $field->nilai_claim_kredit ?></td>

			<? 
			foreach ($field->syarat_reject_credit as $value_reject) { ?>
				<td  align="center"  style="background-color: blue;" class="rotate"><?=$value_reject->status_reject?></td>
			<?} 
			
			?>
			<td><?= (int)  $field->ssu_total_cash_wilayah ?></td>
			<td><?= (int)  $field->cash_claim_by_dealer_wilayah ?></td>
			<td><?PHP // (int)  $field->$ssu_total_cash_approve_to_by_ahm?></td>
			<td><?= (int)  $field->ssu_total_cash_approve_to_dealer_wilayah ?></td>
			<td><?= (int)  $field->ssu_cash_kredit_reject_to_dealer_wilayah ?></td>
			<td></td>
			<td><?= (int) $field->nilai_claim_cash ?></td>

			<? 
			foreach ($field->syarat_reject_cash as $value_reject) { ?>
				<td  align="center"  style="background-color: yellow;" class="rotate"><?=$value_reject->status_reject?></td>
			<?}?>

			
			</tr>

		<?}else {
				
			$sql = $this->db->query("select nama_dealer,id_dealer from ms_dealer WHERE kode_dealer_ahm ='$field->kode_dealer_ahm'")->row();

			$sql->id_dealer='46';

			$query_syarat_kredit = $this->db->query("SELECT cd.id_dealer,cd.id_sales_order,
			sum(case when cs.checklist_reject_md = 0 then 0 else 1 end) as status_reject,
			ar.alasan_reject,
			sps.syarat_ketentuan  from tr_claim_dealer cd 
			left join tr_sales_order so on so.id_sales_order = cd.id_sales_order 
			join tr_spk spk on spk.no_spk = so.no_spk
			left join tr_claim_dealer_syarat cs on cs.id_claim = cd.id_claim 
			join tr_sales_program_syarat sps on sps.id=cs.id_syarat_ketentuan 
			left join ms_alasan_reject ar on ar.id_alasan_reject = cs.alasan_reject 
			WHERE cd.status ='rejected'
			AND cd.id_program_md ='$sales_program->id_program_md'
			AND cd.id_dealer ='$sql->id_dealer'
			AND spk.jenis_beli ='kredit'
			GROUP by cd.id_dealer, 
			cs.id_syarat_ketentuan 
			ORDER by cd.id_sales_order,sps.syarat_ketentuan asc limit $check ")->result();


			$query_syarat_cash = $this->db->query("SELECT cd.id_dealer,cd.id_sales_order,
			sum(case when cs.checklist_reject_md = 0 then 0 else 1 end) as status_reject,
			ar.alasan_reject,
			sps.syarat_ketentuan  from tr_claim_dealer cd 
			left join tr_sales_order so on so.id_sales_order = cd.id_sales_order 
			join tr_spk spk on spk.no_spk = so.no_spk
			left join tr_claim_dealer_syarat cs on cs.id_claim = cd.id_claim 
			join tr_sales_program_syarat sps on sps.id=cs.id_syarat_ketentuan 
			left join ms_alasan_reject ar on ar.id_alasan_reject = cs.alasan_reject 
			WHERE cd.status ='rejected'
			AND cd.id_program_md ='$sales_program->id_program_md'
			AND cd.id_dealer ='$sql->id_dealer'
			AND spk.jenis_beli ='cash'
			GROUP by cd.id_dealer, 
			cs.id_syarat_ketentuan 
			ORDER by cd.id_sales_order,sps.syarat_ketentuan asc limit $check ")->result();

			?>
		

			<tr >
			<td><?=$no++?></td>
			<td> <?= $field->nama_dealer?>&nbsp;</td>
			<td align="center" > <?php echo "".$field->kode_dealer_ahm?> &nbsp;</td>
			<!-- SSU -->
			<td  style="background-color: #d9d9d9;" ><?= $array_ssu_total [] = (int) $field->ssu_total ?></td>
			<!-- SALES PROGRAM -->
			<td style="background-color: #a6a6a6;"><?= $nilai_claim_approve_rupiah_cash_kredit[] = $field->nilai_claim_kredit_dealer + $field->nilai_claim_cash_dealer ?></td>
			<td><?=  $array_total_unit_claim_md[] = (int) $field->ssu_total_kredit_approve_to_dealer + (int) $field->ssu_total_cash_approve_to_dealer  ?></td>
			
			<!-- CREDIT -->
			<!-- SSU -->

			<td><?= $array_ssu_credit[] 			     = (int) $field->ssu_total_kredit ?></td>
			<!-- CLAIM BY DEALER -->
			<td><?= $array_claim_dealer_credit[] 	     = $field->kredit_claim_by_dealer?></td>
			<!-- APPROVE BY AHM -->
			<td><?= $array_approve_ahm_credit[] 	     = $field->ssu_total_kredit_approve_to_by_ahm?></td>
				<!-- APPROVE BY DEALER -->
			<td><?= $array_approve_by_dealer_credit[] 	 = $field->ssu_total_kredit_approve_to_dealer?></td>
			<!-- REJECT DELAER -->
			<td><?= $array_ssu_reject_to_dealer_credit[] = $field->ssu_total_kredit_reject_to_dealer?></td>

			<!-- MANUAL -->
			<!-- Syarat yang di Reject : -->
			<td></td>
			<!-- NILAI KLAIM YANG APPROVE --> 
			<td  style="background-color: #d9d9d9;" ><?=$array_nilai_claim_approve_dealer_kredit[] = $field->nilai_claim_kredit_dealer ?></td>


			<?
			if (count($query_syarat_kredit) > 1){
				foreach ($query_syarat_kredit as $field) { 
					$reject_set_kredit=$field->status_reject;?>
					<td><?= $reject_set_kredit ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td>-</td>
				<?}
			}
			?>

			<!-- CASH -->
			<!-- SSU -->
			<td><?= $array_ssu_cash[] 					= (int) $field->ssu_total_cash  ?></td>
			<!-- CLAIM BY DEALER -->
			<td><?= $array_claim_dealer_cash[] 			= (int)$field->cash_claim_by_dealer?></td>
			<!-- APPROVE BY AHM -->
			<td><?= $array_approve_ahm_cash [] 			= (int)$field->ssu_total_cash_approve_to_by_ahm?></td>
				<!-- APPROVE BY DEALER -->
			<td><?= $array_approve_by_dealer_cash[] 	= (int)$field->ssu_total_cash_approve_to_dealer?></td>
			<!-- REJECT DELAER -->
			<td><?= $array_ssu_reject_to_dealer_cash[]	= (int)$field->ssu_cash_reject_to_dealer ?></td>
			<!-- MANUAL -->
			<td></td>
			<!-- NILAI KLAIM YANG APPROVE -->
			<!-- Syarat yang di Reject : -->
			<td  style="background-color: #d9d9d9;" ><?= $array_nilai_claim_approve_dealer_cash[] =$field->nilai_claim_cash_dealer ?></td>

			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set_cash=$field->status_reject;
					?>
					<td  align="center"  style="background-color: blue;"><?=  $reject_set_cash ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"  style="background-color: red;" class="rotate">-</td>
				<?}
			}?>
		</tr>


		<?php }} ?>

<!-- buttom -->

	<tr  style="background-color: #d8e0f1;">
			<td></td>
			<!-- total_seluruh -->
			<td class="title" >TOTAL</td>
			<td></td>
			<td><?=array_sum($array_ssu_total);?></td>
			<td><?=array_sum($nilai_claim_approve_rupiah_cash_kredit);?></td>
			<td><?=array_sum($array_total_unit_claim_md);?></td>
			<td><?=array_sum($array_ssu_credit);?></td>
			<td><?=array_sum($array_claim_by_dealer_credit);?></td>
			<td><?=array_sum($array_approve_ahm_credit);?></td>
			<td><?=array_sum($array_approve_by_dealer_credit);?></td>
			<td><?=array_sum($array_ssu_reject_to_dealer_credit);?></td>
			<td></td>
			<td><?=array_sum($array_nilai_claim_approve_dealer_kredit);?></td>


			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td ><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"  class="rotate">0</td>
				<?}
			}?>

			<td><?=array_sum($array_ssu_cash);?></td>
			<td><?=array_sum($array_claim_dealer_cash);?></td>
			<td><?=array_sum($array_approve_ahm_cash);?></td>
			<td><?=array_sum($array_approve_by_dealer_cash);?></td>
			<td><?=array_sum($array_ssu_reject_to_dealer_cash);?></td>
			<td></td>
			<td><?=array_sum($array_nilai_claim_approve_dealer_cash);?></td>

			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td class="rotate"><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"   class="rotate">0</td>
				<?}
			}?>
		</tr>
	
	<tr>
			<td></td>
			<td  style="background-color: yellow;">Total Approval dari AHM (based on approval MD di PIM)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td  class="rotate"><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"   class="rotate">0</td>
				<?}
			}?>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
					
			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td  class="rotate"><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"   class="rotate">0</td>
				<?}
			}?>

			
		</tr>
		
		<tr>
			<td></td>
			<td  style="background-color: yellow;">Surplus / (minus)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
					
			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td style="background-color: red;" class="rotate"><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"  style="background-color: orange;" class="rotate">0</td>
				<?}
			}?>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
					
			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td class="rotate"><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"  class="rotate">0</td>
				<?}
			}?>
		</tr>

		
		<tr>
			<td></td>
			<td  style="background-color: yellow;">Total Approve to Dealer</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td style="background-color: red;" class="rotate"><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"  style="background-color: orange;" class="rotate">0</td>
				<?}
			}?>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
					
			<?
			if (count($query_syarat_cash) > 1){
				foreach ($query_syarat_cash as $field) { 
					$reject_set=$field->status_reject;
					?>
					<td style="background-color: red;" class="rotate"><?= $reject_set ?></td>
				<?}
			}else{
				foreach ($sales_program_syarat as $kei) {?>
					<td  align="center"  style="background-color: orange;" class="rotate">0</td>
				<?}
			}?>
		</tr>

</table>



</html>



