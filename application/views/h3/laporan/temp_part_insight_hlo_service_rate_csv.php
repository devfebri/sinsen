<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_Service Rate ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Periode (Bulan-Tahun)".$tanda;
	$content .= "Qty Order Hotline".$tanda;
	$content .= "Qty Fulfilled Order".$tanda;
	$content .= "Service Rate \n";

	foreach ($hlo_service_rate->result() as $isi) {
			$filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $sl_dealer = $this->db->query("SELECT date_format(po.tanggal_order ,'%m-%Y') as bulantahun, SUM(dof.qty_fulfillment) as fulfilled_order
            from tr_h3_dealer_purchase_order po  
            left join tr_h3_dealer_order_fulfillment dof on po.po_id=dof.po_id 
            where po.po_type='HLO' and po.tanggal_order >= DATE_SUB('$end_date', interval 5 month) and po.tanggal_order <='$end_date' $filter_dealer and date_format(po.tanggal_order ,'%m-%Y')='$isi->bulantahun'
            ")->row();
			$service_rate = number_format(($sl_dealer->fulfilled_order/$isi->qty_order)*100);
		$content .= $isi->bulantahun . $tanda;
		$content .= $isi->qty_order . $tanda;
		$content .= $sl_dealer->fulfilled_order . $tanda;
		$content .= $service_rate;
		$content .= "\r\n";
	}
		
	echo $content;
?>