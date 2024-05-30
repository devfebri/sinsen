<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Monitoring Hotline Order Dealer_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table border="1">
	<?php if ($id_dealer != 'all') { ?>
		<caption><b>Hotline Order Monitoring Report
				<br> <?php echo $laporan_hlo_dealer->row()->nama_dealer ?> </b></caption>
	<?php } else { ?>
		<caption><b>Hotline Order Monitoring Report
				<br> Periode <?php echo $start_date_2 . " s/d " . $end_date_2 ?>
			</b><br><br></caption>
	<?php } ?>



	<tr>
		<td style="vertical-align : middle;text-align:center;"><b>MD</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tgl PO Dealer </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tanggal Submit Dealer to MD</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tanggal PO MD to AHM</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tgl Packing Sheet AHM</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tgl Parts Diterima MD dari AHM</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tgl MD Terbit Shipping</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tgl Dealer Terima Barang</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tgl Dealer Terbit NSC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>No NSC AHASS</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Leadtime Supply</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>No PO HTL MD</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Nama Dealer </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Alamat Dealer</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>No Telp Dealer</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>No PO HTL Dealer</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tgl PO Dealer</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Nama Konsumen</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Alamat Konsumen </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>No Telp Konsumen</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Email Konsumen</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>No Rangka </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>No Mesin</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Item No</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Part Number</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Deskripsi</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Order</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Supply</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>BO</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ETD AHM To MD</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ETA Dealer</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ETA Konsumen</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Revisi ETA Konsumen</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tanggal kirim info ETA ke Jaringan</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Tanggal kirim info Revisi ETA ke Jaringan</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Status</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Kelompok Part</b></td>
	</tr>


	<?php
	$nom = 1;
	$itemPart = [];
	if ($laporan_hlo_dealer->num_rows() > 0) {
		foreach ($laporan_hlo_dealer->result() as $key => $row) {
			$data_po2 = isset($data_po[$key]) ? $data_po[$key] : (object) array('tgl_packing_sheet_ahm' => '', 'tgl_parts_diterima_md' => '', 'no_po_md' => '', 'tgl_info_eta_revisi_ke_jaringan' => '');
			$data_gr2 = isset($data_gr[$key]) ? $data_gr[$key] : (object) array('id_referensi' => '', 'tgl_dealer_terima_barang' => '', 'qty_fulfillment' => '');

			// No NSC
			$nsc = $this->db->query("SELECT DATE_FORMAT(nsc.created_at,'%d/%m/%Y') as tgl_terbit_nsc, nsc.no_nsc
			FROM tr_h3_dealer_sales_order dso 
			JOIN tr_h23_nsc nsc on nsc.id_referensi=dso.nomor_so 
			JOIN tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc and nscp.id_part_int= $row->id_part_int
			WHERE dso.booking_id_reference_int = $row->id_booking_int");

			if ($nsc->num_rows()>0) {
				$no_nsc = $nsc->row()->no_nsc;
				$tgl_terbit_nsc = $nsc->row()->tgl_terbit_nsc;
			}else{
				$no_nsc = '';
				$tgl_terbit_nsc = '';
			}

			//Pemenuhan PO oleh MD 
			// $pemenuhan_po = $this->db->query("SELECT qty_supply from tr_h3_md_pemenuhan_po_dari_dealer thmppdd where po_id='$row->no_po_dealer' and id_part_int='$row->id_part_int'")->row();

			$qty_bo = $row->kuantitas - $data_gr2->qty_fulfillment;

			// Selisih Hari
			$datetime_awal = DateTime::createFromFormat('d/m/Y', $row->tgl_po_dealer);
			if($row->tgl_dealer_terima_barang ==''){
				$sekarang = date('d/m/Y');
				$datetime_akhir = DateTime::createFromFormat('d/m/Y', $sekarang);
			}else{
				$datetime_akhir = DateTime::createFromFormat('d/m/Y', $row->tgl_dealer_terima_barang);
			}

			if ($datetime_awal && $datetime_akhir) {
				$interval = $datetime_awal->diff($datetime_akhir);
				$selisih_hari = $interval->days;
			} else {
				$selisih_hari = '';
			}

			// Menghitung PO Item per No PO 
			$po_id = $row->no_po_dealer;
			if (!isset($itemPart[$po_id])) {
				$itemPart[$po_id] = 1;
			}

			$row->itemPart = $itemPart[$po_id];
			$item = $row->itemPart;

			//Status 
			if($qty_bo > 0){
				$status = 'Pending';
			}else{
				$status = 'Selesai';
			}

			echo "
				<tr>
					<td>E20</td>
					<td>$row->tgl_po_dealer</td>
					<td>$row->tgl_submit_dealer_to_md</td>
					<td>$row->tgl_po_md_to_ahm</td>
					<td>$data_po2->tgl_packing_sheet_ahm</td>
					<td>$data_po2->tgl_parts_diterima_md</td>
					<td>$row->tgl_shipping_md</td>
					<td>$data_gr2->tgl_dealer_terima_barang</td>
					<td>$tgl_terbit_nsc</td>
					<td>$no_nsc</td>
					<td>$selisih_hari</td>
					<td>$data_po2->no_po_md</td>
					<td>$row->nama_dealer</td>
					<td>$row->alamat</td>
					<td>'$row->no_telp</td>
					<td>$row->no_po_dealer</td>
					<td>$row->tgl_po_dealer</td>
					<td>$row->nama_customer</td>
					<td>$row->alamat_konsumen</td>
					<td>'$row->no_hp</td>
					<td>$row->email</td>
					<td>$row->no_rangka</td>
					<td>$row->no_mesin</td>
					<td align='center'>$item</td>
					<td>'$row->id_part</td>
					<td>$row->nama_part</td>
					<td align='center'>$row->kuantitas</td>
					<td align='center'>$data_gr2->qty_fulfillment</td>
					<td align='center'>$qty_bo</td>
					<td>$row->etd_ahm_to_md</td>
					<td>$row->eta_dealer</td>
					<td>$row->eta_dealer</td>
					<td>$row->eta_revisi</td>
					<td>$row->tgl_po_dealer</td>
					<td>$data_po2->tgl_info_eta_revisi_ke_jaringan</td>
					<td>$status</td>
					<td>$row->kelompok_part</td>
					
			";
			$nom++;
			$itemPart[$po_id]++;
		}
	} else {
		echo "<td colspan='37' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>