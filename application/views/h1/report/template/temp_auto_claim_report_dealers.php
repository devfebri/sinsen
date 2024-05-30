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
		<td rowspan="2" width="">Deskripsi Program</td>
		<td rowspan="2"  width="300">Series Type</td>
		<!-- <td rowspan="2"  width="300">Tipe Kendaraan</td> -->
		<td colspan="4" >Total</td>
		<td colspan="4" >Credit</td>
		<td colspan="4" >Cash</td>
	</tr>

	<tr  class="title-header">
		<td style="width: 220px"  height="20">SSU</td>
		<td style="width: 220px" >Claim by Dealer</td>
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

foreach ($auto_claim_dealer as $field) {
	
	?>
    <tr>
		<td style="width: 50px;"><?= $no++ ?></td>
        <td style="width: 100px; white-space:nowrap;" ><?= $field->id_program_md ?></td>
        <td style="width: 1500px;"><?= $field->judul_kegiatan ?></td>
        <td style="width: 200px;"><?= $field->series ?></td>
		<td style="width: 100px;"><?= $arraySsu[] 	 = (int)$field->tot_ssu_credit + (int)$field->tot_ssu_cash ?></td>
        <td><?= $arrayClaim[]    = (int)$field->claim_credit + (int)$field->claim_cash ?></td>
        <td><?= $arrayApproved[] = (int)$field->approve_kredit + (int)$field->approve_cash   ?></td>
        <td><?= $arrayRejected[] = (int)$field->rejected_kredit + (int)$field->rejected_cash ?></td>
		<td style="width: 100;"><?= $arraySsuCredit[]     = (int)$field->tot_ssu_credit?></td>
        <td><?= $arrayClaimCredit[]   = (int)$field->claim_credit ?></td>
        <td><?= $arrayApproveCredit[] = (int)$field->approve_kredit ?></td>
        <td><?= $arrayRejectCredit[]  = (int)$field->rejected_kredit ?></td>
        <td style="width: 100;"><?= $arraySsuCash[]     = (int)$field->tot_ssu_cash  ?></td>
        <td><?= $arrayClaimCash[]   = (int)$field->claim_cash ?></td>
        <td><?= $arrayApproveCash[] = (int)$field->approve_cash ?></td>
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

