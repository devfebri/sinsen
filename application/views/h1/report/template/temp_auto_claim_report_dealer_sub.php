<?php 
$sql = $this->db->query("select nama_dealer from ms_dealer WHERE id_dealer ='$id_dealer'")->row(); 
$nama_dealer =  $sql->nama_dealer; 
$no = $start_periode." - ".$end_periode;
header("Content-type: application/octet-stream");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename= Auto Claim - Report Dealer " .$nama_dealer." - ". $no.".xls");
?>

<!DOCTYPE html>
<html lang="en">

<style>
	th {
		font-weight: normal;
	}

	#width {
		width: 75px;
	}

	.width_dealer{
		width: 280px;
	}

	.title-bg-color{
		background-color: #fce4d6; 
		width: 980px;
	}


.title-header{
	font-weight: bold;
	text-align: center; 
    vertical-align: middle;
	background-color: #f8cbad;
	
}


.title-buttom{
	font-weight: bold;
	text-align: center; 
    vertical-align: middle;
	background-color: yellow;
}

.title-reject{
	color: red;
}


.width_dealer{
		width: 480px;
	}
</style>


<body>
<table>  
	<tr>
		<td colspan="2"> <b>Monitoring Approval Claim Dealer</b></td>
	</tr>

	<tr>
		<td colspan="2" >Periode </td>
		<td ><?=$start_periode." - ".$end_periode?></td>
	</tr>
	
	<tr>
		<td colspan="2" >Dealer </td>
		<td colspan="3" ><?=$nama_dealer?></td>
	</tr>
</table>

 <table border="1">  
	<tr class="title-header">
		<td rowspan="2" >No</td>
		<td rowspan="2" >Sales Program</td>
		<td rowspan="2" width="300">Deskripsi Program</td>
		<td rowspan="2"  width="300">Series Type</td>
		<!-- <td rowspan="2"  width="300">Tipe Kendaraan</td> -->
		<td colspan="4" >Total</td>
		<td colspan="4" >Credit</td>
		<td colspan="4" >Cash</td>
	</tr>
	<tr  class="title-header">
		<td style="width: 220px"  height="20">SSU</td>
		<td >Claim by Dealer</td>
		<td>Approved to Dlr</td>
		<td>Reject to Dlr</td>
		<td  width="100" >SSU</td>
		<td>Claim by Dealer</td>
		<td>Approved to Dlr</td>
		<td>Reject to Dlr</td>
		<td width="100">SSU</td>
		<td>Claim by Dealer</td>
		<td>Approved to Dlr</td>
		<td>Reject to Dlr</td>
	</tr>
	
<?php
$no = 1;

$Claim = new stdclass();
$Approved = new stdclass();
$Rejected = new stdclass();

$SsuCash = new stdclass();
$ClaimCash = new stdclass();
$ApproveCash = new stdclass();
$RejectCash = new stdclass();

$SsuCredit = new stdclass();
$ClaimCredit = new stdclass();
$ApproveCredit = new stdclass();
$RejectCredit = new stdclass();

$arraySsu = [];
$arrayClaim = [];
$arrayApproved = [];
$arrayRejected = [];

$arraySsuCash = [];
$arrayClaimCash = [];
$arrayApproveCash = [];
$arrayRejectCash = [];

$arraySsuCredit = [];
$arrayClaimCredit = [];
$arrayApproveCredit = [];
$arrayRejectCredit = [];


$not_in_array_tipe_kendaraan = [];
foreach ($auto_claim_dealer as $field) {

	$query_tipe = $this->db->query("select id_tipe_kendaraan from tr_sales_program_tipe WHERE id_program_md ='$field->id_program_md'")->result();

	$array_row=[];

	if (!empty($query_tipe)){ 
		  $where_not_in ="AND 1=1";

		  $hasil_array_not_in = $not_in_array_tipe_kendaraan;
		  $string_not_in = "'" . implode("','", $hasil_array_not_in) . "'";
							
			foreach ($query_tipe as $element ) {
				$array_row[] = $element->id_tipe_kendaraan;
				$not_in_array_tipe_kendaraan [] = $element->id_tipe_kendaraan;
			}

			$hasil_array = $array_row;
			$string = "'" . implode("','", $hasil_array) . "'";

		  	$set_query_tipe = $this->db->query("SELECT
		  	CONCAT($string) as tipe_set,
			SUM(CASE when so.no_spk is not null then 1 else null end) as 'total_ssu',
			SUM(CASE when spk.jenis_beli ='cash'    then 1 else null end) as 'cash',
			SUM(CASE when spk.jenis_beli ='kredit'  then 1 else null end) as 'kredit'
		 	from tr_spk spk 
			join tr_sales_order so on so.no_spk = spk.no_spk
			WHERE 
			spk.id_dealer = '$id_dealer'
			AND so.tgl_cetak_invoice  BETWEEN  '$start_periode' AND '$end_periode'
			AND spk.id_tipe_kendaraan in ($string)
			 ")->row();

			$ssu_cash_sub   =0;
			$ssu_kredit_sub =0;
			$ssu_total =0;

			if ($set_query_tipe->total_ssu !== null) {
				$ssu_total = (int) $set_query_tipe->total_ssu;
			}		
			
			if ($set_query_tipe->cash !== null) {
				$ssu_cash_sub = (int) $set_query_tipe->cash;
			}

			if ($set_query_tipe->kredit !== null) {
				$ssu_kredit_sub = (int)  $set_query_tipe->kredit;
			}
	}

	
	?>
    <tr>
        <td><?= $no++ ?></td>
        <td style="white-space:nowrap;" ><?= $field->id_program_md ?></td>
        <td><?= $field->judul_kegiatan ?></td>
        <td><?= $field->series_motor ?></td>
		<td><?= $arraySsu[] 	 = (int)$ssu_total ?></td>

        <td><?= $arrayClaim[]    = (int)$field->claim_kredit +  (int)$field->claim_cash   ?></td>
        <td><?= $arrayApproved[] = (int)$field->approved_kredit +  (int)$field->approved_cash ?></td>
        <td><?= $arrayRejected[] = (int)$field->rejected_kredit  +  (int)$field->rejected_cash ?></td>

		<td><?= $arraySsuCredit[]     = (int)$ssu_kredit_sub ?></td>
        <td><?= $arrayClaimCredit[]   = (int)$field->claim_kredit ?></td>
        <td><?= $arrayApproveCredit[] = (int)$field->approved_kredit ?></td>
        <td><?= $arrayRejectCredit[]  = (int)$field->rejected_kredit ?></td>

        <td><?= $arraySsuCash[]     = (int)$ssu_cash_sub?></td>
        <td><?= $arrayClaimCash[]   = (int)$field->claim_cash ?></td>
        <td><?= $arrayApproveCash[] = (int)$field->approved_cash ?></td>
        <td><?= $arrayRejectCash[]  = (int)$field->rejected_cash ?></td>
    </tr>

<?php
}


$SsuSum = array_sum($arraySsu);
$ClaimSum = array_sum($arrayClaim);
$ApprovedSum = array_sum($arrayApproved);
$RejectedSum = array_sum($arrayRejected);

$SsuCashSum = array_sum($arraySsuCash);
$ClaimCashSum = array_sum($arrayClaimCash);
$ApproveCashSum = array_sum($arrayApproveCash);
$RejectCashSum = array_sum($arrayRejectCash);

$SsuCreditSum = array_sum($arraySsuCredit);
$ClaimCreditSum = array_sum($arrayClaimCredit);
$ApproveCreditSum = array_sum($arrayApproveCredit);
$RejectCreditSum = array_sum($arrayRejectCredit);
?>

<tr class="title-buttom">
    <td colspan="3">Total</td>
	<td></td>
    <td><?= $SsuSum ?></td>
    <td><?= $ClaimSum ?></td>
    <td><?= $ApprovedSum ?></td>
    <td><?= $RejectedSum ?></td>

	<td><?= $SsuCreditSum ?></td>
    <td><?= $ClaimCreditSum ?></td>
    <td><?= $ApproveCreditSum ?></td>
    <td><?= $RejectCreditSum ?></td>
	
    <td><?= $SsuCashSum ?></td>
    <td><?= $ClaimCashSum ?></td>
    <td><?= $ApproveCashSum ?></td>
    <td><?= $RejectCashSum ?></td>

</tr>

</body>

