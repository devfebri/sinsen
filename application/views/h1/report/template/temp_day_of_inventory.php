<?php 
$no = date('d-m-Y_His');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=SSP_DOI_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");

function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Kode Tipe</td>
 		<td align="center">Deskripsi</td>
 		<td align="center">No Mesin</td>
 		<td align="center">No Rangka</td>
 		<td align="center">Tgl Shipping AHM</td>
 		<td align="center">Tgl Penerimaan MD</td>
 		<td align="center">Tgl Surat Jalan</td>
 		<td align="center">Tgl Penerimaan Dealer</td>
 		<td align="center">Nama Dealer</td>
 		<td align="center">Tgl SSU</td>
 		<td align="center">Tgl BASTK</td>
 		<td align="center">Lead Time AHM-MD</td>		
 		<td align="center">MD Hold Time</td>		
 		<td align="center">Lead Time MD-D</td>		
 		<td align="center">Dealer Hold Time</td>		
 		<td align="center">Lead Time D-C</td>		
 		<td align="center">Total DOI (days)</td>	
 		<td align="center">Status Unit</td>	
 	</tr>
 	<?php 
	$today = date("Y-m-d");
	// $today = '2023-11-22';

	$get_sales = $this->db->query("
		select sales.no_mesin, sales.tgl_cetak_invoice, sales.jam_cetak_invoice , sales.tgl_bastk, sales.no_bastk , b.no_rangka, b.tipe_motor, 
		c.tipe_ahm, b.tgl_penerimaan, d.tgl_sl, g.tgl_surat, f.tgl_penerimaan as tgl_penerimaan_dealer, h.nama_dealer
		from (
			select no_mesin, tgl_cetak_invoice, jam_cetak_invoice , tgl_bastk, no_bastk 
			from tr_sales_order 
			where tgl_cetak_invoice ='$today'
			union
			select a.no_mesin , b.tgl_cetak_invoice, b.jam_cetak_invoice, b.tgl_bastk , b.no_bastk 
			from tr_sales_order_gc_nosin a
			join tr_sales_order_gc b on a.id_sales_order_gc = b.id_sales_order_gc  and a.no_spk_gc = b.no_spk_gc 
			where b.tgl_cetak_invoice ='$today'
		) as sales
		join tr_scan_barcode b on sales.no_mesin = b.no_mesin
		join ms_tipe_kendaraan c on b.tipe_motor = c.id_tipe_kendaraan
		join tr_shipping_list d on d.no_mesin = b.no_mesin
		join tr_penerimaan_unit_dealer_detail e on e.no_mesin = b.no_mesin and e.retur =0
		join tr_penerimaan_unit_dealer f on e.id_penerimaan_unit_dealer = f.id_penerimaan_unit_dealer
		join tr_surat_jalan g on f.no_surat_jalan = g.no_surat_jalan
		join ms_dealer h on h.id_dealer = f.id_dealer
		order by b.status desc, c.tipe_ahm asc
	");

	$no = 1;
	foreach($get_sales->result() as $row) {   
		if(strlen($row->tgl_sl) == 8 ){
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,2);
		}else{
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,1);
		}

		$tgl_bastk=null;
		if($row->tgl_bastk!='' && $row->tgl_bastk!=null){
			$tgl_bastk = date_format(date_create($row->tgl_bastk),"Y-m-d");
		}

		$lead_ahm_md = date_diff(date_create($tgl_shipping),date_create($row->tgl_penerimaan));
		$lead_ahm_md =  $lead_ahm_md->format('%a days');
		
		$hold_md = date_diff(date_create($row->tgl_penerimaan),date_create($row->tgl_surat));
		$hold_md = $hold_md->format('%a days');

		$lead_md_d = date_diff(date_create($row->tgl_surat),date_create($row->tgl_penerimaan_dealer));
		$lead_md_d = $lead_md_d->format('%a days');

		$hold_d = date_diff(date_create($row->tgl_penerimaan_dealer),date_create($row->tgl_cetak_invoice));
		$hold_d = $hold_d->format('%a days');

		$lead_d_c = date_diff(date_create($row->tgl_cetak_invoice),date_create($tgl_bastk));
		$lead_d_c = $lead_d_c->format('%a days');

		$lead_ahm_c = date_diff(date_create($tgl_shipping),date_create($tgl_bastk));
		$lead_ahm_c = $lead_ahm_c->format('%a');
		
		echo "<tr>
			<td>$no</td>
			<td>$row->tipe_motor</td>
			<td>$row->tipe_ahm</td>
			<td>$row->no_mesin</td>
			<td>$row->no_rangka</td>
			<td>$tgl_shipping</td>
			<td>$row->tgl_penerimaan</td>
			<td>$row->tgl_surat</td>
			<td>$row->tgl_penerimaan_dealer</td>
			<td>$row->nama_dealer</td>
			<td>$row->tgl_cetak_invoice</td>
			<td>$tgl_bastk</td>
			<td align='center'>$lead_ahm_md</td>
			<td align='center'>$hold_md</td>
			<td align='center'>$lead_md_d</td>
			<td align='center'>$hold_d</td>
			<td align='center'>$lead_d_c</td>
			<td align='center'>$lead_ahm_c</td>
			<td align='center'>Sales Unit</td>
			</tr>	
		";
		$no++;
	}

	$get_stock_dealer = $this->db->query("
		select b.no_mesin , b.no_rangka, b.tipe_motor, c.tipe_ahm, b.tgl_penerimaan, d.tgl_sl, g.tgl_surat, f.tgl_penerimaan as tgl_penerimaan_dealer, h.nama_dealer
		from tr_scan_barcode b
		join ms_tipe_kendaraan c on b.tipe_motor = c.id_tipe_kendaraan
		join tr_shipping_list d on d.no_mesin = b.no_mesin
		join tr_penerimaan_unit_dealer_detail e on e.no_mesin = b.no_mesin and e.retur =0
		join tr_penerimaan_unit_dealer f on e.id_penerimaan_unit_dealer = f.id_penerimaan_unit_dealer
		join tr_surat_jalan g on f.no_surat_jalan = g.no_surat_jalan
		join ms_dealer h on h.id_dealer = f.id_dealer
		where b.status = 4
		order by b.status desc, c.tipe_ahm asc
	");

	foreach($get_stock_dealer->result() as $row) {   
		if(strlen($row->tgl_sl) == 8 ){
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,2);
		}else{
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,1);
		}

		$tgl_bastk=null;
		if($row->tgl_bastk!='' || $row->tgl_bastk!=null){
			$tgl_bastk = date_format(date_create($row->tgl_bastk),"Y-m-d");
		}

		$lead_ahm_md = date_diff(date_create($tgl_shipping),date_create($row->tgl_penerimaan));
		$lead_ahm_md =  $lead_ahm_md->format('%a days');
		
		$hold_md = date_diff(date_create($row->tgl_penerimaan),date_create($row->tgl_surat));
		$hold_md = $hold_md->format('%a days');

		$lead_md_d = date_diff(date_create($row->tgl_surat),date_create($row->tgl_penerimaan_dealer));
		$lead_md_d = $lead_md_d->format('%a days');

		$hold_d = date_diff(date_create($row->tgl_penerimaan_dealer),date_create(null));
		$hold_d = $hold_d->format('%a days');

		$lead_d_c = '';

		$lead_ahm_c = date_diff(date_create($tgl_shipping),date_create($tgl_penerimaan_dealer));
		$lead_ahm_c = $lead_ahm_c->format('%a');
		
		echo "<tr>
			<td>$no</td>
			<td>$row->tipe_motor</td>
			<td>$row->tipe_ahm</td>
			<td>$row->no_mesin</td>
			<td>$row->no_rangka</td>
			<td>$tgl_shipping</td>
			<td>$row->tgl_penerimaan</td>
			<td>$row->tgl_surat</td>
			<td>$row->tgl_penerimaan_dealer</td>
			<td>$row->nama_dealer</td>
			<td>$row->tgl_cetak_invoice</td>
			<td>$tgl_bastk</td>
			<td align='center'>$lead_ahm_md</td>
			<td align='center'>$hold_md</td>
			<td align='center'>$lead_md_d</td>
			<td align='center'>$hold_d</td>
			<td align='center'>$lead_d_c</td>
			<td align='center'>$lead_ahm_c</td>
			<td align='center'>Stok Dealer</td>
			</tr>	
		";
		$no++;
	}

	
	// get stok intransit md-d
	$get_stock_intransit = $this->db->query("
		select b.no_mesin , b.no_rangka, b.tipe_motor, c.tipe_ahm, b.tgl_penerimaan, d.tgl_sl, f.tgl_surat, g.nama_dealer
		from tr_scan_barcode b
		join ms_tipe_kendaraan c on b.tipe_motor = c.id_tipe_kendaraan
		join tr_shipping_list d on d.no_mesin = b.no_mesin
		join tr_surat_jalan_detail e on e.no_mesin = b.no_mesin and e.retur = 0
		join tr_surat_jalan f on e.no_surat_jalan = f.no_surat_jalan
		join ms_dealer g on g.id_dealer = f.id_dealer
		where b.status =3 and b.no_mesin in (
			select k.no_mesin
			from tr_scan_barcode k
			join tr_surat_jalan_detail l on l.no_mesin = k.no_mesin and l.retur = 0
			join tr_surat_jalan m on l.no_surat_jalan = m.no_surat_jalan
			where k.status = 3 and m.status = 'proses'
		)
		order by b.status desc, c.tipe_ahm asc
	");

	foreach($get_stock_intransit->result() as $row) {   
		if(strlen($row->tgl_sl) == 8 ){
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,2);
		}else{
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,1);
		}

		$tgl_bastk=null;
		if($row->tgl_bastk!='' || $row->tgl_bastk!=null){
			$tgl_bastk = date_format(date_create($row->tgl_bastk),"Y-m-d");
		}

		$lead_ahm_md = date_diff(date_create($tgl_shipping),date_create($row->tgl_penerimaan));
		$lead_ahm_md =  $lead_ahm_md->format('%a days');
		
		$hold_md = date_diff(date_create($row->tgl_penerimaan),date_create($row->tgl_surat));
		$hold_md = $hold_md->format('%a days');

		$lead_md_d = date_diff(date_create($row->tgl_surat),date_create(null));
		$lead_md_d = $lead_md_d->format('%a days');

		$hold_d = '';
		$lead_d_c = '';

		$lead_ahm_c = date_diff(date_create($tgl_shipping),date_create($row->tgl_surat));
		$lead_ahm_c = $lead_ahm_c->format('%a');
		
		echo "<tr>
			<td>$no</td>
			<td>$row->tipe_motor</td>
			<td>$row->tipe_ahm</td>
			<td>$row->no_mesin</td>
			<td>$row->no_rangka</td>
			<td>$tgl_shipping</td>
			<td>$row->tgl_penerimaan</td>
			<td>$row->tgl_surat</td>
			<td></td>
			<td>$row->nama_dealer</td>
			<td></td>
			<td>$tgl_bastk</td>
			<td align='center'>$lead_ahm_md</td>
			<td align='center'>$hold_md</td>
			<td align='center'>$lead_md_d</td>
			<td align='center'>$hold_d</td>
			<td align='center'>$lead_d_c</td>
			<td align='center'>$lead_ahm_c</td>
			<td align='center'>Intransit MD-D</td>
			</tr>	
		";
		$no++;
	}

	// get stok md
	$get_stock_md = $this->db->query("
		select b.no_mesin , b.no_rangka, b.tipe_motor, c.tipe_ahm, b.tgl_penerimaan, d.tgl_sl
		from tr_scan_barcode b
		join ms_tipe_kendaraan c on b.tipe_motor = c.id_tipe_kendaraan
		join tr_shipping_list d on d.no_mesin = b.no_mesin
		where b.status <=3 and b.no_mesin not in (
			select k.no_mesin
			from tr_scan_barcode k
			join tr_surat_jalan_detail l on l.no_mesin = k.no_mesin and l.retur = 0
			join tr_surat_jalan m on l.no_surat_jalan = m.no_surat_jalan
			where k.status = 3 and m.status = 'proses'
		)
		order by b.status desc, c.tipe_ahm asc
	");

	foreach($get_stock_md->result() as $row) {   
		if(strlen($row->tgl_sl) == 8 ){
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,2);
		}else{
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,1);
		}

		$tgl_bastk=null;
		if($row->tgl_bastk!='' || $row->tgl_bastk!=null){
			$tgl_bastk = date_format(date_create($row->tgl_bastk),"Y-m-d");
		}

		$lead_ahm_md = date_diff(date_create($tgl_shipping),date_create($row->tgl_penerimaan));
		$lead_ahm_md =  $lead_ahm_md->format('%a days');
		
		$hold_md = date_diff(date_create($row->tgl_penerimaan),date_create(null));
		$hold_md = $hold_md->format('%a days');

		$lead_md_d = '';
		$hold_d = '';
		$lead_d_c = '';

		$lead_ahm_c = date_diff(date_create($tgl_shipping),date_create($tgl_penerimaan_dealer));
		$lead_ahm_c = $lead_ahm_c->format('%a');
		
		echo "<tr>
			<td>$no</td>
			<td>$row->tipe_motor</td>
			<td>$row->tipe_ahm</td>
			<td>$row->no_mesin</td>
			<td>$row->no_rangka</td>
			<td>$tgl_shipping</td>
			<td>$row->tgl_penerimaan</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>$tgl_bastk</td>
			<td align='center'>$lead_ahm_md</td>
			<td align='center'>$hold_md</td>
			<td align='center'>$lead_md_d</td>
			<td align='center'>$hold_d</td>
			<td align='center'>$lead_d_c</td>
			<td align='center'>$lead_ahm_c</td>
			<td align='center'>Stok MD</td>
			</tr>	
		";
		$no++;
	}

	// get intransit AHM
	$get_intransit_ahm = $this->db->query("
		select a.no_mesin , a.no_rangka , a.id_modell , b.tipe_ahm, a.tgl_sl
		from tr_shipping_list a 
		join ms_tipe_kendaraan b on a.id_modell = b.id_tipe_kendaraan 
		where a.no_mesin not in (
			select no_mesin from tr_scan_barcode
		)
		order by b.tipe_ahm asc
	");

	foreach($get_intransit_ahm->result() as $row) {   
		if(strlen($row->tgl_sl) == 8 ){
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,2);
		}else{
			$tgl_shipping = substr($row->tgl_sl,4,4).'-'.substr($row->tgl_sl,2,2).'-'.substr($row->tgl_sl,0,1);
		}

		$tgl_bastk=null;
		if($row->tgl_bastk!='' || $row->tgl_bastk!=null){
			$tgl_bastk = date_format(date_create($row->tgl_bastk),"Y-m-d");
		}

		$lead_ahm_md = date_diff(date_create($tgl_shipping),date_create(null));
		$lead_ahm_md =  $lead_ahm_md->format('%a days');
		
		$hold_md = '';
		$lead_md_d = '';
		$hold_d = '';
		$lead_d_c = '';

		$lead_ahm_c = date_diff(date_create($tgl_shipping),date_create(null));
		$lead_ahm_c = $lead_ahm_c->format('%a');
		
		echo "<tr>
			<td>$no</td>
			<td>$row->id_modell</td>
			<td>$row->tipe_ahm</td>
			<td>$row->no_mesin</td>
			<td>$row->no_rangka</td>
			<td>$tgl_shipping</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>$tgl_bastk</td>
			<td align='center'>$lead_ahm_md</td>
			<td align='center'>$hold_md</td>
			<td align='center'>$lead_md_d</td>
			<td align='center'>$hold_d</td>
			<td align='center'>$lead_d_c</td>
			<td align='center'>$lead_ahm_c</td>
			<td align='center'>Intransit AHM</td>
			</tr>	
		";
		$no++;
	}

 	?>
</table>