<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Amount by 9 Segment ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


$content .= "Segment".$tanda;
$content .= "Total Pendapatan \n";

$content .= "AT High" . $tanda;
$content .= number_format($ps_9_segment->row()->at_high,2,",",".");
$content .= "\r\n";

$content .= "AT Low" . $tanda;
$content .= number_format($ps_9_segment->row()->at_low,2,",",".");
$content .= "\r\n";
		
$content .= "AT Medium" . $tanda;
$content .= number_format($ps_9_segment->row()->at_medium,2,",",".");
$content .= "\r\n";

$content .= "Cub High" . $tanda;
$content .= number_format($ps_9_segment->row()->cub_high,2,",",".");
$content .= "\r\n";

$content .= "Cub Low" . $tanda;
$content .= number_format($ps_9_segment->row()->cub_low,2,",",".");
$content .= "\r\n";
		
$content .= "Cub Medium" . $tanda;
$content .= number_format($ps_9_segment->row()->cub_medium,2,",",".");
$content .= "\r\n";

$content .= "Sport High" . $tanda;
$content .= number_format($ps_9_segment->row()->sport_high,2,",",".");
$content .= "\r\n";

$content .= "Sport Low" . $tanda;
$content .= number_format($ps_9_segment->row()->sport_low,2,",",".");
$content .= "\r\n";
		
$content .= "Sport Medium" . $tanda;
$content .= number_format($ps_9_segment->row()->sport_medium,2,",",".");
$content .= "\r\n";

	echo $content;
?>