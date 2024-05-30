<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Alert Growth ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Nama Dealer".$tanda;
	$content .= "M-1".$tanda;
	$content .= "M".$tanda;
	$content .= "%M vs M-1".$tanda;
	$content .= "YTD \n";

	foreach ($ps_alert_growth->result() as $isi) {
		if($isi->Msebelum != 0){
			$persentase = number_format((($isi->M-$isi->Msebelum)/$isi->Msebelum)*100,2,",",".");
		}else{
			$persentase = number_format(0,2,",",".");
		}

		$content .= $isi->nama_dealer . $tanda;
		$content .= number_format($isi->Msebelum,2,",","."). $tanda;
		$content .= number_format($isi->M,2,",",".") . $tanda;
		$content .= $persentase.'%' . $tanda;
		$content .= number_format($isi->ytd,2,",",".");
		$content .= "\r\n";
	}
		
	echo $content;
?>