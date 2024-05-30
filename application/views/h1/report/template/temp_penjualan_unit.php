<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=penjualan_unit_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No Invoice</td>
 		<td align="center">Tgl Invoice</td>
 		<td align="center">No DO</td>
 		<td align="center">Tgl DO</td>
 		<td align="center">No Surat Jalan</td>
 		<td align="center">Tgl Surat Jalan</td>
 		<td align="center">Dealer</td>
 		<td align="center">Keterangan</td>
 		<td align="center">Ekspedisi</td>
 		<td align="center">No Polisi</td>
 		<td align="center">Kode Item</td>
 		<td align="center">Kode Tipe Kendaraan</td>
 		<td align="center">Nama Item Kendaraan</td>
 		<td align="center">No Mesin</td>
 		<td align="center">No Rangka</td>
 		<td align="center">Tahun Produksi</td>
 		<td align="center">Status PDI</td>
 	</tr>
 	<?php 
 	$no=1; 	
 	$where = "";
 	if($id_dealer!=''){
		$where .= "AND tr_do_po.id_dealer = '$id_dealer'";
	}
	if($id_item!=''){
		$where .= "AND tr_picking_list_detail.id_item = '$id_item'";
	}
	if($id_tipe_kendaraan!=''){
		$where .= "AND ms_item.id_tipe_kendaraan = '$id_tipe_kendaraan'";
	}
 
 	// $sql = $this->db->query("SELECT tr_picking_list_view.*,tr_picking_list.*,tr_picking_list_detail.*,tr_do_po.*,tr_invoice_dealer.*,ms_dealer.*,tr_surat_jalan.no_surat_jalan,tgl_surat 
 	// 			FROM tr_picking_list_view
	// 			JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list				
	// 			JOIN tr_picking_list_detail ON tr_picking_list_detail.no_picking_list = tr_picking_list.no_picking_list
	// 			JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do				
	// 			JOIN tr_invoice_dealer ON tr_invoice_dealer.no_do = tr_picking_list.no_do
	// 			LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
	// 			LEFT JOIN tr_surat_jalan ON tr_surat_jalan.no_picking_list=tr_picking_list.no_picking_list
 	// 		WHERE tr_invoice_dealer.tgl_faktur BETWEEN '$tgl1' AND '$tgl2' $where
 	// 		GROUP BY tr_picking_list_view.no_mesin, tr_picking_list.no_picking_list 
 	// 		ORDER BY tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur ASC"); 

	 $sql = $this->db->query("
	 	select 'rep_penjualan_unit' as menu, tr_invoice_dealer.no_faktur , tr_invoice_dealer.tgl_faktur , tr_invoice_dealer.no_do , tr_do_po.tgl_do , tr_surat_jalan.tgl_surat , tr_surat_jalan_detail.no_surat_jalan, ms_dealer.nama_dealer , tr_picking_list_view.no_mesin 
		FROM tr_picking_list_view
			JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list				
			JOIN tr_picking_list_detail ON tr_picking_list_detail.no_picking_list = tr_picking_list.no_picking_list
			JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do				
			JOIN tr_invoice_dealer ON tr_invoice_dealer.no_do = tr_picking_list.no_do
			LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
			LEFT JOIN tr_surat_jalan_detail ON tr_surat_jalan_detail.no_mesin=tr_picking_list_view.no_mesin 
			left join tr_surat_jalan on tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
		WHERE tr_invoice_dealer.tgl_faktur BETWEEN '$tgl1' AND '$tgl2' $where
		GROUP BY tr_picking_list_view.no_mesin, tr_picking_list.no_picking_list 
		ORDER BY tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur, tr_surat_jalan.no_surat_jalan ASC
	 ");
 		 	
 	foreach ($sql->result() as $row) {
		// SELECT tr_scan_barcode.*,tr_penerimaan_unit.*,ms_warna.warna, ms_vendor.vendor_name,ms_tipe_kendaraan.tipe_ahm
 		$eks = $this->db->query("select 'rep_penjualan_unit' as menu, tr_scan_barcode.no_mesin , tr_scan_barcode.no_rangka , tr_scan_barcode.tipe_motor , tr_scan_barcode.id_item , ms_warna.warna , tr_penerimaan_unit.no_polisi , ms_vendor.vendor_name
			FROM tr_scan_barcode 
 			LEFT JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
 			LEFT jOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit 
 			LEFT JOIN ms_vendor ON tr_scan_barcode.nama_ekspedisi = ms_vendor.id_vendor
 			LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
 			LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
 			WHERE tr_scan_barcode.no_mesin = '$row->no_mesin'");
 		$vendor_name="";$no_polisi="";$tipe_motor="";$id_item="";$warna="";$no_rangka="";
 		if($eks->num_rows() > 0){
 			$vendor_name = $eks->row()->vendor_name;
 			$no_polisi = $eks->row()->no_polisi;
 			$tipe_motor = $eks->row()->tipe_motor;
 			$id_item = $eks->row()->id_item;
 			$warna = $eks->row()->warna;
 			$no_rangka = $eks->row()->no_rangka;
 		}

 		$fkb = $this->db->query("SELECT tahun_produksi FROM tr_fkb WHERE no_mesin_spasi = '$row->no_mesin'");
 		$tahun_produksi = ($fkb->num_rows()>0)?$fkb->row()->tahun_produksi:"";

 		$pdi = $this->db->query("SELECT b.pdi FROM tr_picking_list a join tr_picking_list_view b on a.no_picking_list = b.no_picking_list WHERE a.no_do = '$row->no_do' and b.no_mesin = '$row->no_mesin'");
 		$pdi_isi = ($fkb->num_rows()>0)?$pdi->row()->pdi:"";
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->no_faktur</td>
 				<td>$row->tgl_faktur</td>
 				<td>$row->no_do</td>
 				<td>$row->tgl_do</td>
 				<td>$row->no_surat_jalan</td>
 				<td>$row->tgl_surat</td>
 				<td>$row->nama_dealer</td>
 				<td>SHOWROOM</td>
 				<td>$vendor_name</td>
 				<td>$no_polisi</td>
 				<td>$tipe_motor</td>
 				<td>$id_item</td>
 				<td>$tipe_motor - $warna</td>
 				<td>$row->no_mesin</td>
 				<td>$no_rangka</td>
 				<td>$tahun_produksi</td>
 				<td>$pdi_isi</td>
 			</tr>
 		";
 		$no++;
 	}
 	?>
</table>


