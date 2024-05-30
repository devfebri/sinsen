<?php 
$no = $tgl1." sd ".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Tarikan Data NMS ".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Laporan Tarikan Data dari <?php echo $tgl1." s/d ".$tgl2?></caption>
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No AHASS</td>
 		<td align="center">Nama AHASS</td>
 		<td align="center">Tanggal WO/PKB/Service</td>
 		<td align="center">No WO/PKB</td>
		<td align="center">No Rangka</td>
		<td align="center">No Mesin</td>
		<td align="center">Jenis Pekerjaan</td>
		<td align="center">Part Number</td>
		<td align="center">Deskripsi Part</td>
		<td align="center">Total Biaya</td>
		<td align="center">Biaya Jasa</td>
		<td align="center">Biaya Parts</td>
 		<td align="center">Discount</td>
		<td align="center">Tanggal Pembelian Sepeda Motor</td>
 		<td align="center">Nama Tipe Kedatangan</td>
 		<td align="center">Kecamatan</td>
 		<td align="center">Kabupaten</td>
 		<td align="center">ETA Service</td>
 		<td align="center">Actual Service</td>
 		<td align="center">Tipe Motor</td>
		<td align="center">Status WO</td>
 	</tr>
	<?php 
 	$no=1;
	$filter_dealer = '';
          if ($id_dealer!='all') {
            $filter_dealer = "AND b.id_dealer='$id_dealer'";
          }


	$tgl2 = date_format(date_add(date_create($tgl2),date_interval_create_from_date_string("1 days")),"Y-m-d");

	$sql = $this->db->query("
			SELECT a.kode_dealer_ahm, a.nama_dealer, b.id_work_order, b.created_at, b.start_at, h.harga as harga_jasa, c.harga as harga_part, (ifnull(h.harga,0) + ifnull(c.harga,0)) as grand_total, e.nama_part, e.id_part, d.deskripsi, upper(replace((case when i.no_mesin is not null then i.no_mesin else g.no_mesin end),' ','')) as no_mesin,
			upper((case when i.no_rangka is not null then i.no_rangka else g.no_rangka end)) as no_rangka,
			(case when i.tgl_cetak_invoice is not null then i.tgl_cetak_invoice else g.tgl_pembelian end) as tgl_pembelian, 
			(case when b.start_at is not null and b.status = 'open' then 'start' else b.status end) as status, l.name as activity_promotion, (ifnull(h.diskon_value,0) + ifnull(((c.qty *c.harga) - c.subtotal),0)) as disc,
			TIMESTAMPDIFF(SECOND , b.start_at, b.closed_at) as actual_service, (h.waktu *60) as eta_service, 
			m.tipe_ahm as tipe_motor, o.kecamatan , p.kabupaten
			FROM tr_h2_wo_dealer AS b
			JOIN ms_dealer AS a ON a.id_dealer = b.id_dealer
			JOIN tr_h2_wo_dealer_pekerjaan AS h ON b.id_work_order = h.id_work_order and h.pekerjaan_batal = 0
			JOIN ms_h2_jasa AS d ON d.id_jasa  = h.id_jasa 
			LEFT JOIN tr_h2_wo_dealer_parts AS c ON h.id_work_order = c.id_work_order and h.id_jasa = c.id_jasa
			LEFT JOIN ms_part AS e ON e.id_part_int  = c.id_part_int and e.kelompok_vendor ='AHM'
			JOIN tr_h2_sa_form AS f ON f.id_sa_form = b.id_sa_form 
			JOIN ms_customer_h23 AS g ON g.id_customer = f.id_customer
			LEFT JOIN tr_sales_order AS i ON i.no_mesin = g.no_mesin	
			LEFT JOIN dms_ms_activity_promotion l on l.id = f.activity_promotion_id		
			join ms_tipe_kendaraan m on m.id_tipe_kendaraan = g.id_tipe_kendaraan 
			join ms_kelurahan n on n.id_kelurahan = g.id_kelurahan
			join ms_kecamatan o on o.id_kecamatan = n.id_kecamatan
			join ms_kabupaten p on p.id_kabupaten = o.id_kabupaten		
			WHERE b.created_at >= '$tgl1' AND b.created_at <= '$tgl2' and b.status <> 'cancel' AND e.kelompok_part != 'FED OIL' $filter_dealer 
			order by b.created_at asc, d.deskripsi asc, c.id_part asc");	 	
 	foreach ($sql->result() as $row) { 		
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>'$row->kode_dealer_ahm</td>
 				<td>$row->nama_dealer</td>
 				<td>$row->created_at</td>
 				<td>$row->id_work_order</td>
				<td>$row->no_rangka</td>
				<td>$row->no_mesin</td>
				<td>$row->deskripsi</td>
				<td>$row->id_part</td>
				<td>$row->nama_part</td>
				<td>$row->grand_total</td>
				<td>$row->harga_jasa</td>
				<td>$row->harga_part</td>
				<td>$row->disc</td>
				<td>$row->tgl_pembelian</td>
				<td>$row->activity_promotion</td>
				<td>$row->kecamatan</td>
				<td>$row->kabupaten</td>
				<td>".gmdate("H:i:s",$row->eta_service)."</td>
				<td>".gmdate("H:i:s",$row->actual_service)."</td>
				<td>$row->tipe_motor</td>
				<td>$row->status</td>
 			</tr>
	 	";
 		$no++;
 	}
	?>
</table>


