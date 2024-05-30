<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_Outstanding ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Waktu Outstanding HLO Order".$tanda;
	$content .= "Total \n";

	$content .= "Kurang dari 1 Minggu" . $tanda;
	$content .= $hlo_outstanding_belum_dipenuhi->row()->kurang_dr_1 + $hlo_outstanding_belum_dipenuhi_semua->row()->kurang_dr_1;
	$content .= "\r\n";

	$content .= "1-2 Minggu" . $tanda;
	$content .= $hlo_outstanding_belum_dipenuhi->row()->minggu_1_2 + $hlo_outstanding_belum_dipenuhi_semua->row()->minggu_1_2;
	$content .= "\r\n";

	$content .= "3-4 Minggu" . $tanda;
	$content .= $hlo_outstanding_belum_dipenuhi->row()->minggu_3_4 + $hlo_outstanding_belum_dipenuhi_semua->row()->minggu_3_4;
	$content .= "\r\n";

	$content .= "5-6 Minggu" . $tanda;
	$content .= $hlo_outstanding_belum_dipenuhi->row()->minggu_5_6 + $hlo_outstanding_belum_dipenuhi_semua->row()->minggu_5_6;
	$content .= "\r\n";

	$content .= "Lebih dari 8 Minggu" . $tanda;
	$content .= $hlo_outstanding_belum_dipenuhi->row()->lebih_dr_8 + $hlo_outstanding_belum_dipenuhi_semua->row()->lebih_dr_8;
	$content .= "\r\n";
	
		
	echo $content;
?>