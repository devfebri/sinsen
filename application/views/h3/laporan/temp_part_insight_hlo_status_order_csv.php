<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_berdasarkan Status Order ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	// $content .= "Fulfilled".$tanda;
	// $content .= "Unfulfilled \n";

	$unfilfilled = $hlo_status_order_all->row()->total-$hlo_status_order_fulfilled->row()->fulfilled;

	$content .= "Status".$tanda;
	$content .= "Total \n";

	$content .= "Fulfilled" . $tanda;
	$content .= $hlo_status_order_fulfilled->row()->fulfilled;
	$content .= "\r\n";

	$content .= "Unfulfilled" . $tanda;
	$content .= $unfilfilled;
	$content .= "\r\n";

	// $content .= $hlo_status_order_fulfilled->row()->fulfilled . $tanda;
	// $content .= $unfilfilled;
	// $content .= "\r\n";

		
	echo $content;
?>