<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Amount by Grouping Parts ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Kelompok Part".$tanda;
	$content .= "Total Sales \n";

	foreach ($ps_grouping_part->result() as $isi) {

		$content .= $isi->kelompok_part . $tanda;
		$content .= number_format($isi->pendapatan,2,",",".");
		$content .= "\r\n";
	}
		
	echo $content;
?>