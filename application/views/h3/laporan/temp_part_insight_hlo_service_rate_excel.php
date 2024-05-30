<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=HLO_Service Rate_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : HLO_Service Rate <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Periode (Bulan-Tahun)</b></td>
		<td align="center"><b>Qty Order Hotline</b></td>
 		<td align="center"><b>Qty Fulfilled Order</b></td>
		 <td align="center"><b>Service Rate</b></td>
 	</tr>
	 <?php 
	if($hlo_service_rate->num_rows()>0){
		foreach ($hlo_service_rate->result() as $row) { 
			$filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $sl_dealer = $this->db->query("SELECT date_format(po.tanggal_order ,'%m-%Y') as bulantahun, SUM(dof.qty_fulfillment) as fulfilled_order
            from tr_h3_dealer_purchase_order po  
            left join tr_h3_dealer_order_fulfillment dof on po.po_id=dof.po_id 
            where po.po_type='HLO' and po.tanggal_order >= DATE_SUB('$end_date', interval 5 month) and po.tanggal_order <='$end_date' $filter_dealer and date_format(po.tanggal_order ,'%m-%Y')='$row->bulantahun'
            ")->row();

			$service_rate = ROUND(($sl_dealer->fulfilled_order/$row->qty_order)*100);
		echo "
 			<tr>
				<td>$row->bulantahun</td>
				<td>$row->qty_order</td>
				<td>$sl_dealer->fulfilled_order</td>
				<td>$service_rate % </td>
 			</tr>
	 	";	
 		}
	}else{
		echo "<td colspan='3' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>