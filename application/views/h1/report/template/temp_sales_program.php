<?php 
//$no = $tgl1."-".$tgl2;
$no = date("d_m_y");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=SalesProgram_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Kode Main Dealer</td>
 		<td align="center">Kode Dealer</td>
 		<td align="center">Nama Dealer</td>
 		<td align="center">Sales Program ID AHM</td>
 		<td align="center">Sales Program ID MD</td> 		
 		<td align="center">No Invoice Dealer</td> 		
 		<td align="center">Tanggal Invoice</td> 		
 		<td align="center">No PO Finance Company</td> 		
 		<td align="center">Tgl PO Finance Company</td> 		
 		<td align="center">No Rangka</td> 		
 		<td align="center">No Mesin</td> 		
 		<td align="center">Kode Tipe Motor</td> 		
 		<td align="center">Nama Tipe Motor</td> 		
 		<td align="center">Kode Warna</td> 		
 		<td align="center">Deskripsi Warna</td> 		
 		<td align="center">Cash/Kredit</td> 		
 		<td align="center">ID Fincoy</td> 		
 		<td align="center">Finance Company</td> 		
 		<td align="center">Tgl Penjualan Unit</td> 		
 		<td align="center">Tgl BASTK</td> 		
 		<td align="center">Nama</td> 		
 		<td align="center">Alamat</td> 		
 		<td align="center">Kota</td> 		
 		<td align="center">Tgl Entry Claim</td> 		
 		<td align="center">Status MD</td> 		
 		<td align="center">Tgl Verifikasi MD</td> 		
 		<td align="center">Alasan MD</td> 		
 	</tr>
 	<?php
 	$no=1; 
 	$where = "and 1=1";
 	if($id_dealer!="") $where = "and tr_sales_order.id_dealer = '$id_dealer'";

	/*
	select a.id_sales_order , d.kode_dealer_ahm , d.nama_dealer ,  h.id_program_ahm , h.id_program_md , a.no_invoice ,a.tgl_cetak_invoice, c.jenis_beli , c.id_finance_company, e.finance_company , a.no_po_leasing , a.tgl_po_leasing , a.no_mesin , a.no_rangka , f.deskripsi_ahm , c.id_tipe_kendaraan , c.id_warna , f.tipe_ahm , g.warna , a.tgl_bastk , c.nama_konsumen , c.alamat , c.id_kabupaten , b.tgl_ajukan_claim as tgl_entry_claim, 
	b.status , b.tgl_approve_reject_md , b.alasan_reject, h.id_jenis_sales_program , h.judul_kegiatan , h.periode_awal , h.periode_akhir 
	from tr_sales_order a
	join tr_claim_dealer b on a.id_sales_order = b.id_sales_order 
	join tr_spk c on a.no_spk =c.no_spk
	join ms_dealer d on d.id_dealer  = a.id_dealer 
	join ms_tipe_kendaraan f on f.id_tipe_kendaraan = c.id_tipe_kendaraan 
	join ms_warna g on g.id_warna = c.id_warna 
	join tr_sales_program h on h.id_program_md = b.id_program_md
	left join ms_finance_company e on e.id_finance_company = c.id_finance_company 
	where a.tgl_cetak_invoice >'2022-01-01' and a.no_mesin ='JBK1E1818120'
	*/
	

 	$sql = $this->db->query("SELECT ms_dealer.kode_dealer_ahm, ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_sales_program.id_program_ahm,tr_sales_program.id_program_md,
 		tr_sales_order.no_invoice as no_bastd, tr_sales_order.tgl_cetak_invoice as tgl_bastd, tr_sales_order.tgl_bastk ,tr_sales_order.no_po_leasing, concat('MH1',tr_fkb.no_rangka) as no_rangka,
 		ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna,tr_sales_order.no_mesin,
		(case when tr_spk.jenis_beli = 'Kredit' then 'Credit' else 'Cash' end ) as jenis_beli, 
		(case when tr_spk.jenis_beli = 'Kredit' then tr_sales_order.tgl_po_leasing else '' end ) as tgl_po_leasing, 
 		ms_finance_company.id_finance_company,ms_finance_company.finance_company,tr_sales_order.tgl_cetak_invoice,tr_spk.nama_konsumen,tr_spk.alamat,
 		ms_kabupaten.kabupaten, tr_claim_dealer.tgl_ajukan_claim, tr_claim_dealer.status, tr_claim_dealer.tgl_approve_reject_md, tr_claim_dealer.alasan_reject
 		FROM tr_sales_order 
 		INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
		inner join tr_claim_dealer on tr_sales_order.id_sales_order = tr_claim_dealer.id_sales_order 
 		LEFT JOIN tr_sales_program ON tr_claim_dealer.id_program_md = tr_sales_program.id_program_md
 		LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
 		LEFT JOIN tr_fkb ON tr_sales_order.no_mesin = tr_fkb.no_mesin_spasi
 		LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 		LEFT JOIN ms_warna ON tr_fkb.kode_warna = ms_warna.id_warna
 		LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
 		LEFT JOIN ms_kabupaten ON tr_spk.id_kabupaten = ms_kabupaten.id_kabupaten
 		WHERE tr_claim_dealer.id_program_md= '$id_program_md' AND tr_sales_program.id_program_ahm = '$id_program_ahm'
 		$where
		order by tr_sales_order.tgl_cetak_invoice asc, ms_dealer.nama_dealer asc, tr_spk.nama_konsumen asc
	");
 	foreach ($sql->result() as $isi) {
 		/*
		$cek = $this->db->query("SELECT * FROM tr_claim_sales_program INNER JOIn tr_claim_sales_program_detail ON tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp
 			WHERE tr_claim_sales_program.id_program_md = '$isi->id_program_md'");
 		$tgl_entry = ($cek->num_rows() > 0) ? $cek->row()->created_at : "" ;
 		$status = ($cek->num_rows() > 0) ? $cek->row()->status : "" ;
 		$alasan = ($cek->num_rows() > 0) ? $cek->row()->keterangan : "" ;
		*/
		if($isi->status!='ajukan'){
			$tgl_verifikasi = $isi->tgl_approve_reject_md;
		}else{
			$tgl_verifikasi = '';
		}

 		echo "
 		<tr>
 			<td>$no</td> 			
 			<td>E20</td> 			
 			<td>$isi->kode_dealer_md &nbsp;</td> 			
 			<td>$isi->nama_dealer</td> 			
 			<td>$isi->id_program_ahm</td> 			
 			<td>$isi->id_program_md</td> 			
 			<td>$isi->no_bastd</td> 			
 			<td>$isi->tgl_bastd</td> 			
 			<td>$isi->no_po_leasing &nbsp;</td> 			
 			<td>$isi->tgl_po_leasing</td> 			
 			<td>$isi->no_rangka</td> 			
 			<td>$isi->no_mesin</td> 			
 			<td>$isi->id_tipe_kendaraan</td> 			
 			<td>$isi->tipe_ahm</td> 			
 			<td>$isi->id_warna</td> 			
 			<td>$isi->warna</td> 			
 			<td>$isi->jenis_beli</td> 			
 			<td>$isi->id_finance_company</td> 			
 			<td>$isi->finance_company</td> 			
 			<td>$isi->tgl_cetak_invoice</td> 		
 			<td>$isi->tgl_bastk</td> 			
 			<td>$isi->nama_konsumen</td> 			
 			<td>$isi->alamat</td> 			
 			<td>$isi->kabupaten</td> 			
 			<td>$isi->tgl_ajukan_claim</td> 			
 			<td>$isi->status</td> 			
 			<td>$tgl_verifikasi</td> 			
 			<td>$isi->alasan_reject</td> 			
 		</tr>
 		";
 		$no++;
 	}
 	?>
</table>
