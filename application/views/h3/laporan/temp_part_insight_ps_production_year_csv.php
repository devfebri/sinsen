<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption by Production Year ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Tahun Produksi".$tanda;
	$content .= "Kuantitas \n";

	foreach ($ps_production_year->result() as $isi) {

		$content .= $isi->tahun_produksi . $tanda;
		$content .= $isi->qty;
		$content .= "\r\n";
	}
		
	echo $content;
?>