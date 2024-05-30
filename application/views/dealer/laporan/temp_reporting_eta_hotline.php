<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting ETA Hotline_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption><b>Reporting ETA Hotline <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB
<br> <?php echo $report->row()->nama_dealer?> </b></caption>
 	<tr> 		
        <td align="center">Nama Konsumen</td>
 		<td align="center">No Telp Konsumen</td>
        <td align="center">Type Motor</td>
 		<td align="center">Nomor WO/No.Pesanan</td>
 		<td align="center">Tanggal WO/Tanggal Pesanan</td>
 		<td align="center">Part Number</td>
        <td align="center">Qty Pesan Hotline</td>
        <td align="center">ETA Awal</td>
		<td align="center">ETA Revised</td>
		<td align="center">Tanggal Saat Informasi ETA Revised dari MD ke D</td>
        <td align="center">Nomor PO Dealer ke MD</td>
		<td align="center">Tanggal Pesan ke MD</td>
        <td align="center">Part Number</td>
        <td align="center">Qty</td>
        <td align="center">Nomor Dokumen Shipping</td>
		<td align="center">Nomor PO D ke MD sebagai reference Shipping</td>
		<td align="center">Tanggal Receiving di Dealer</td>
		<td align="center">Part Number yang diterima Dealer</td>
		<td align="center">Qty yang diterima oleh Dealer</td>
		<td align="center">Tanggal Kontak ke Konsumen</td>
        <td align="center">Part Number yang ready diambil</td>
		<td align="center">Qty Part Number yang ready</td>
        <td align="center">No NSC</td>
		<td align="center">Tanggal NSC</td>
		<td align="center">Nomor WO Reference/Pesanan Hotline</td>
		<td align="center">Part Number</td>
		<td align="center">Qty</td>
 	</tr>

<?php 
 	$nom=1;	
	if($report->num_rows()>0){
		foreach ($report->result() as $row) { 
        
        $purchase_order_part = $this->db->query("SELECT id_part, kuantitas FROM tr_h3_dealer_purchase_order_parts WHERE po_id = '$row->po_id' and id_part='$row->id_part'")->row();

		// var_dump($purchase_order_part->id_part);
		// die();

        // $order_dealer_fulfillment = $this->db->query("SELECT po_id,created_at,id_part,qty_fulfillment FROM tr_h3_dealer_order_fulfillment WHERE po_id='$row->po_id'")->row();

		$history_hotline = $this->db->query("SELECT po_id, DATE_FORMAT(eta,'%d-%m-%Y') as eta_terlama, DATE_FORMAT(eta_revisi,'%d-%m-%Y') as eta_revisi, DATE_FORMAT(created_at,'%d-%m-%Y')  as info_eta_revisi from tr_h3_md_history_estimasi_waktu_hotline where po_id = '$row->po_id' and id_part='$row->id_part' 
		and created_at = (SELECT max(created_at) from tr_h3_md_history_estimasi_waktu_hotline 
						where po_id = '$row->po_id' and id_part='$row->id_part') LIMIT 1")->row_array();

		if($history_hotline== ''){
			$eta_terlama = "-";
			$eta_revisi = "-";
			$info_eta_revisi = "-";
		}else{
			$eta_terlama = $history_hotline['eta_terlama'];
			$eta_revisi = $history_hotline['eta_revisi'];
			$info_eta_revisi = $history_hotline['info_eta_revisi'];
		}

		$good_receipt = $this->db->query("SELECT gr.tanggal_receipt as tgl_receiving, grp.id_part as pn_diterima, SUM(grp.qty) as qty_diterima_d
		from tr_h3_dealer_good_receipt gr
		join tr_h3_dealer_good_receipt_parts grp on grp.id_good_receipt=gr.id_good_receipt
		where grp.id_part_int = '$row->id_part_int' and  gr.nomor_po = '$row->po_id'
		")->row();

		$kontak_customer = $this->db->query("SELECT DATE_FORMAT(tgl_kontak_customer,'%d-%m-%Y') as kontak_customer FROM tr_h3_history_kontak_customer_hotline WHERE po_id = '$row->po_id'")->row();
        
		if($kontak_customer== ''){
			$kontak_customer = "-";
		}else{
			$kontak_customer = $kontak_customer['kontak_customer'];
		}

		// Data WO 
		$wo = $this->db->query("SELECT nsc.no_nsc, DATE_FORMAT(nsc.tgl_nsc,'%d-%m-%Y') as tgl_nsc, nsc.id_referensi as nsc_referensi, nscp.id_part as nscp_pn, nscp.qty as nscp_qty
		from tr_h2_wo_dealer_parts wops
		join tr_h23_nsc nsc on nsc.id_referensi=wops.id_work_order 
		join tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc and nscp.id_part_int=wops.id_part_int 
		where wops.id_booking = '$row->referensi' and wops.id_part_int = '$row->id_part_int'")->row();

		// Direct Sales
		$so = $this->db->query("SELECT nsc.no_nsc, DATE_FORMAT(nsc.tgl_nsc,'%d-%m-%Y') as tgl_nsc, nsc.id_referensi as nsc_referensi, nscp.id_part as nscp_pn, nscp.qty as nscp_qty
		from tr_h3_dealer_sales_order so 
		join tr_h3_dealer_sales_order_parts sop on so.nomor_so=sop.nomor_so 
		join tr_h23_nsc nsc on nsc.id_referensi=so.nomor_so 
		join tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc and nscp.id_part_int=sop.id_part_int 
		where so.booking_id_reference = '$row->referensi' and sop.id_part_int = '$row->id_part_int'")->row();

		if($wo != ''){
			$no_nsc = $wo->no_nsc;
			$tgl_nsc = $wo->tgl_nsc;
			$nsc_referensi = $wo->nsc_referensi;
			$nscp_pn = $wo->nscp_pn;
			$nscp_qty = $wo->nscp_qty;
		}elseif($wo == '' and $so !=''){
			$no_nsc = $so->no_nsc;
			$tgl_nsc = $so->tgl_nsc;
			$nsc_referensi = $so->nsc_referensi;
			$nscp_pn = $so->nscp_pn;
			$nscp_qty = $so->nscp_qty;
		}else{
			$no_nsc = "-";
			$tgl_nsc = "-";
			$nsc_referensi = "-";
			$nscp_pn = "-";
			$nscp_qty = "-";
		}
		
 		echo "
 			<tr>
                <td>$row->nama_customer</td>
 				<td>'$row->no_hp</td>
				<td>$row->deskripsi</td>
 				<td>$row->referensi</td>
 				<td>$row->tgl_pesan</td>
 				<td>'$row->id_part</td>
				<td>$row->qty</td>
 				<td>$eta_terlama</td>
				<td>$eta_revisi</td>
 				<td>$info_eta_revisi</td>
				<td>$row->po_id</td>
				<td>$row->pesan_ke_md</td>
				<td>'$row->pn_po</td>
				<td>$row->qty_po</td>
 				<td>$row->no_shipping</td>
				<td>$row->ref_shipping</td>
				<td>$good_receipt->tgl_receiving</td>
				<td>'$good_receipt->pn_diterima</td>
				<td>$good_receipt->qty_diterima_d</td>
				<td>$kontak_customer</td>
                <td>$good_receipt->pn_diterima</td>
				<td>$good_receipt->qty_diterima_d</td>
				<td>$no_nsc</td>
			   	<td>$tgl_nsc</td>
				<td>$nsc_referensi</td>
			   	<td>'$nscp_pn</td>
			   	<td>$nscp_qty</td>
 			</tr>
	 	";
 		// $nom++;
 		}
	}else{
		echo "<td colspan='27' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


