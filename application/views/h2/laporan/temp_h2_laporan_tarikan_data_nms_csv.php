<?php
$no = $tgl1." sd ".$tgl2;
header("Content-Disposition: attachment; filename=Laporan Tarikan Data NMS ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = 'Laporan Tarikan Data NMS dari '. $tgl1." s/d ".$tgl2;
$content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "No".$tanda;
	$content .= "No AHASS".$tanda;
	$content .= "Tanggal WO/PKB/Service".$tanda;
	$content .= "No WO/PKB".$tanda;
	$content .= "No Rangka".$tanda;
	$content .= "No Mesin".$tanda;
	$content .= "Jenis Pekerjaan".$tanda;
	$content .= "Part Number".$tanda;
	$content .= "Deskripsi Part".$tanda;
	$content .= "Total Biaya".$tanda;
	$content .= "Biaya Jasa".$tanda;
	$content .= "Biaya Parts".$tanda;
	$content .= "Tanggal Pembelian Sepeda Motor \n";
	$content .= "Status WO \n";

	$filter_dealer = '';
          if ($id_dealer!='all') {
            $filter_dealer = "AND b.id_dealer='$id_dealer'";
          }

	$tgl2 = date_format(date_add(date_create($tgl2),date_interval_create_from_date_string("1 days")),"Y-m-d");

	$sql = $this->db->query("
				SELECT a.kode_dealer_ahm, a.kode_dealer_md, b.id_work_order, b.created_at, b.start_at, h.harga as harga_jasa, c.harga as harga_part, (ifnull(h.harga,0) + ifnull(c.harga,0)) as grand_total, e.nama_part, e.id_part, d.deskripsi, upper(replace((case when i.no_mesin is not null then i.no_mesin else g.no_mesin end),' ','')) as no_mesin,
upper((case when i.no_rangka is not null then i.no_rangka else g.no_rangka end)) as no_rangka,
(case when i.tgl_cetak_invoice is not null then i.tgl_cetak_invoice else g.tgl_pembelian end) as tgl_pembelian, 
(case when b.start_at is not null and b.status = 'open' then 'start' else b.status end) as status			 	
				FROM tr_h2_wo_dealer AS b
				JOIN ms_dealer AS a ON a.id_dealer = b.id_dealer
				JOIN tr_h2_wo_dealer_pekerjaan AS h ON b.id_work_order = h.id_work_order and h.pekerjaan_batal = 0
				JOIN ms_h2_jasa AS d ON d.id_jasa  = h.id_jasa 
				LEFT JOIN tr_h2_wo_dealer_parts AS c ON h.id_work_order = c.id_work_order and h.id_jasa = c.id_jasa
				LEFT JOIN ms_part AS e ON e.id_part  = c.id_part and e.kelompok_vendor ='AHM'
				JOIN tr_h2_sa_form AS f ON f.id_sa_form = b.id_sa_form 
				JOIN ms_customer_h23 AS g ON g.id_customer = f.id_customer
				LEFT JOIN tr_sales_order AS i ON i.no_mesin = g.no_mesin				
				WHERE b.created_at >= '$tgl1' AND b.created_at <= '$tgl2' and b.status <> 'cancel' $filter_dealer order by b.created_at asc, d.deskripsi asc, c.id_part asc");	 	
	foreach ($sql->result() as $isi) {

			$urut++;
			$content .= $urut . $tanda;
			$content .= $isi->kode_dealer_ahm . $tanda;
			$content .= $isi->start_at. $tanda;
			$content .= $isi->id_work_order . $tanda;
			$content .= $isi->no_rangka . $tanda;
			$content .= $isi->no_mesin . $tanda;
			$content .= $isi->deskripsi . $tanda;
			$content .= $isi->id_part . $tanda;
			$content .= $isi->nama_part . $tanda;
			$content .= $isi->grand_total . $tanda;
			$content .= $isi->harga_jasa . $tanda;
			$content .= $isi->harga_part . $tanda;
			$content .= $isi->tgl_pembelian . $tanda;
			$content .= $isi->status . $tanda;
			$content .= "\r\n";
		}
	echo $content;
?>