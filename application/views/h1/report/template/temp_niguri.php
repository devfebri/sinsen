<?php 
$bln = sprintf("%'.02d",$bulan);
$no = $bln."-".$tahun;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=ReportNiguri_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
function bln($a){
  $bulan=$bl=$month=$a;
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}

$a1 = $bulan - 2;
$a2 = $bulan - 1;
$a3 = $bulan;
$a4 = $bulan + 1;
$a5 = $bulan + 2;
if($a1 == "-1"){
  $a1 = "11";
  $tahun = $tahun-1;
}elseif($a1 == "0"){
  $a1 = "12";
}
if($a2 == "0"){
  $a2 = "12";
}
if($a5 == "14"){
  $a5 = "2";
}elseif($a5 == "13"){
  $a5 = "1";
  $tahun = $tahun+1;
}
if($a4 == "13"){
  $a4 = "1";
  $tahun = $tahun+1;
}
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Type 3 Digit</td> 		 		
 		<td align="center">Nama Komersil</td>
 		<td align="center">Description</td> 		 		
 		<td align="center"><?php echo bln($a1) ?></td> 		 		 		
 		<td align="center"><?php echo bln($a2) ?></td> 		 		 		
 		<td align="center"><?php echo bln($bulan) ?></td> 		 		 		
 		<td align="center"><?php echo bln($a4) ?></td> 		 		 		
 		<td align="center"><?php echo bln($a5) ?></td> 		 		 		
 	</tr>
 	<?php 
 	$no=1; 
 	$bulan_tahun_am1 = $tahun."-".sprintf("%'.02d",$a1);
 	$bulan_tahun_am = $tahun."-".sprintf("%'.02d",$a2);
 	$bulan_tahun_fix = $tahun."-".$bln;
 	$bulan_tahun_t1 = $tahun."-".sprintf("%'.02d",$a4);
 	$bulan_tahun_t2 = $tahun."-".sprintf("%'.02d",$a5);
 	$sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan, ms_tipe_kendaraan.tipe_customer,
 		sum(tr_niguri_detail.a_m1) AS a_m1,sum(tr_niguri_detail.a_m) AS a_m,sum(tr_niguri_detail.a_fix) AS a_fix, sum(tr_niguri_detail.a_t1) AS a_t1,sum(tr_niguri_detail.a_t2) AS a_t2,
 		sum(tr_niguri_detail.b_m1) AS b_m1,sum(tr_niguri_detail.b_m) AS b_m,sum(tr_niguri_detail.b_fix) AS b_fix, sum(tr_niguri_detail.b_t1) AS b_t1,sum(tr_niguri_detail.a_t2) AS b_t2
 		FROM tr_niguri INNER JOIN tr_niguri_detail ON tr_niguri.id_niguri = tr_niguri_detail.id_niguri
 		INNER JOIN ms_item ON tr_niguri_detail.id_item = ms_item.id_item
 		INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 		WHERE tr_niguri.bulan = '$bulan' AND tr_niguri.tahun = '$tahun'
 		GROUP BY ms_item.id_tipe_kendaraan");
 	foreach ($sql->result() as $isi) {
 		
 		///cari po reguler
 		$cari_po_am1 = $this->db->query("SELECT 
 			sum(tr_po_detail.qty_po_t1) AS qty_po_t1,sum(tr_po_detail.qty_po_fix) AS qty_po_fix,sum(tr_po_detail.qty_po_t2) AS qty_po_t2
 			FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a1' AND tr_po.tahun = '$tahun' AND jenis_po = 'PO Reguler'");
 		$cari_po_am = $this->db->query("SELECT 
 			sum(tr_po_detail.qty_po_t1) AS qty_po_t1,sum(tr_po_detail.qty_po_fix) AS qty_po_fix,sum(tr_po_detail.qty_po_t2) AS qty_po_t2 
 			FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a2' AND tr_po.tahun = '$tahun' AND jenis_po = 'PO Reguler'");
 		$cari_po_fix = $this->db->query("SELECT 
 			sum(tr_po_detail.qty_po_t1) AS qty_po_t1,sum(tr_po_detail.qty_po_fix) AS qty_po_fix,sum(tr_po_detail.qty_po_t2) AS qty_po_t2  
 			FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$bulan' AND tr_po.tahun = '$tahun' AND jenis_po = 'PO Reguler'");
 		$cari_po_t1 = $this->db->query("SELECT 
 			sum(tr_po_detail.qty_po_t1) AS qty_po_t1,sum(tr_po_detail.qty_po_fix) AS qty_po_fix,sum(tr_po_detail.qty_po_t2) AS qty_po_t2 
 		 	FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a4' AND tr_po.tahun = '$tahun' AND jenis_po = 'PO Reguler'");
 		$cari_po_t2 = $this->db->query("SELECT 
 			sum(tr_po_detail.qty_po_t1) AS qty_po_t1,sum(tr_po_detail.qty_po_fix) AS qty_po_fix,sum(tr_po_detail.qty_po_t2) AS qty_po_t2 
 		 	FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a5' AND tr_po.tahun = '$tahun' AND jenis_po = 'PO Reguler'");
 		$po_fix_am1 = ($cari_po_am1->num_rows() > 0) ? $cari_po_am1->row()->qty_po_fix + $cari_po_am1->row()->qty_po_t1 + $cari_po_am1->row()->qty_po_t2 : 0 ;
 		$po_fix_am = ($cari_po_am->num_rows() > 0) ? $cari_po_am->row()->qty_po_fix + $cari_po_am->row()->qty_po_t1 + $cari_po_am->row()->qty_po_t2 : 0 ;
 		$po_fix_fix = ($cari_po_fix->num_rows() > 0) ? $cari_po_fix->row()->qty_po_fix + $cari_po_fix->row()->qty_po_t1 + $cari_po_fix->row()->qty_po_t2 : 0 ;
 		$po_fix_t1 = ($cari_po_t1->num_rows() > 0) ? $cari_po_t1->row()->qty_po_fix + $cari_po_t1->row()->qty_po_t1 + $cari_po_t1->row()->qty_po_t2 : 0 ;
 		$po_fix_t2 = ($cari_po_t2->num_rows() > 0) ? $cari_po_t2->row()->qty_po_fix + $cari_po_t2->row()->qty_po_t1 + $cari_po_t2->row()->qty_po_t2 : 0 ;

 		///cari po add
 		$cari_add_am1 = $this->db->query("SELECT SUM(tr_po_detail.qty_order) AS qty_order FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a1' AND tr_po.tahun = '$tahun' AND tr_po.jenis_po = 'PO Additional'");
 		$cari_add_am = $this->db->query("SELECT SUM(tr_po_detail.qty_order) AS qty_order FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a2' AND tr_po.tahun = '$tahun' AND tr_po.jenis_po = 'PO Additional'");
 		$cari_add_fix = $this->db->query("SELECT SUM(tr_po_detail.qty_order) AS qty_order FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$bulan' AND tr_po.tahun = '$tahun' AND tr_po.jenis_po = 'PO Additional'");
 		$cari_add_t1 = $this->db->query("SELECT SUM(tr_po_detail.qty_order) AS qty_order FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a4' AND tr_po.tahun = '$tahun' AND tr_po.jenis_po = 'PO Additional'");
 		$cari_add_t2 = $this->db->query("SELECT SUM(tr_po_detail.qty_order) AS qty_order FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po
 			INNER JOIN ms_item ON tr_po_detail.id_item = ms_item.id_item
 			WHERE ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
 			AND tr_po.bulan = '$a5' AND tr_po.tahun = '$tahun' AND tr_po.jenis_po = 'PO Additional'");
 		$po_add_am1 = ($cari_add_am1->num_rows() == 0 OR is_null($cari_add_am1->row()->qty_order)) ? 0 : $cari_add_am1->row()->qty_order ;
 		$po_add_am = ($cari_add_am->num_rows() == 0 OR is_null($cari_add_am->row()->qty_order)) ? 0 : $cari_add_am->row()->qty_order ;
 		$po_add_fix = ($cari_add_fix->num_rows() == 0 OR is_null($cari_add_fix->row()->qty_order)) ? 0 : $cari_add_fix->row()->qty_order ;
 		$po_add_t1 = ($cari_add_t1->num_rows() == 0 OR is_null($cari_add_t1->row()->qty_order)) ? 0 : $cari_add_t1->row()->qty_order ;
 		$po_add_t2 = ($cari_add_t2->num_rows() == 0 OR is_null($cari_add_t2->row()->qty_order)) ? 0 : $cari_add_t2->row()->qty_order ;


 		///cari po total
 		$total_am1 = $po_fix_am1 + $po_add_am1;
 		$total_am = $po_fix_am + $po_add_am;
 		$total_fix = $po_fix_fix + $po_add_fix;
 		$total_t1 = $po_fix_t1 + $po_add_t1;
 		$total_t2 = $po_fix_t2 + $po_add_t2;

 		///cari DO Unfilled
 		$cek_unfill_am1 = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
				LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
				INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
				LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
				AND ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_do_po.tgl_do,7) = '$bulan_tahun_am1'")->row();
 		$cek_unfill_am = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
				LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
				INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
				LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
				AND ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_do_po.tgl_do,7) = '$bulan_tahun_am'")->row();
 		$cek_unfill_fix = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
				LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
				INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
				LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
				AND ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_do_po.tgl_do,7) = '$bulan_tahun_fix'")->row();
 		$cek_unfill_t1 = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
				LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
				INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
				LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
				AND ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_do_po.tgl_do,7) = '$bulan_tahun_t1'")->row();
 		$cek_unfill_t2 = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
				LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
				INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
				LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
				AND ms_item.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_do_po.tgl_do,7) = '$bulan_tahun_t2'")->row();
    $unfill_am1 = (isset($cek_unfill_am1->jum)) ? $cek_unfill_am1->jum : 0 ;
    $unfill_am = (isset($cek_unfill_am->jum)) ? $cek_unfill_am->jum : 0 ;
    $unfill_fix = (isset($cek_unfill_fix->jum)) ? $cek_unfill_fix->jum : 0 ;
    $unfill_t1 = (isset($cek_unfill_t1->jum)) ? $cek_unfill_t1->jum : 0 ;
    $unfill_t2 = (isset($cek_unfill_t2->jum)) ? $cek_unfill_t2->jum : 0 ;
    

    //cari md dist to d
    $cek_md_dist_m1 = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan
				LEFT JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
				LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_surat_jalan.tgl_surat,7) = '$bulan_tahun_am1'")->row();
    $cek_md_dist_m = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan
				LEFT JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
				LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_surat_jalan.tgl_surat,7) = '$bulan_tahun_am'")->row();
    $cek_md_dist_fix = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan
				LEFT JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
				LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_surat_jalan.tgl_surat,7) = '$bulan_tahun_fix'")->row();
    $cek_md_dist_t1 = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan
				LEFT JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
				LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_surat_jalan.tgl_surat,7) = '$bulan_tahun_t1'")->row();
    $cek_md_dist_t2 = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan
				LEFT JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
				LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'
				AND LEFT(tr_surat_jalan.tgl_surat,7) = '$bulan_tahun_t2'")->row();
    $md_dist_m1 = (isset($cek_md_dist_m1->jum)) ? $cek_md_dist_m1->jum : 0 ;
    $md_dist_m = (isset($cek_md_dist_m->jum)) ? $cek_md_dist_m->jum : 0 ;
    $md_dist_fix = (isset($cek_md_dist_fix->jum)) ? $cek_md_dist_fix->jum : 0 ;
    $md_dist_t1 = (isset($cek_md_dist_t1->jum)) ? $cek_md_dist_t1->jum : 0 ;
    $md_dist_t2 = (isset($cek_md_dist_t2->jum)) ? $cek_md_dist_t2->jum : 0 ;

    ///cari daily sales
    $cek_daily_am1_1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order				
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan_tahun_am1'")->row();
    $cek_daily_am1_2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin				
				LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan_tahun_am1'")->row();    
    $daily_am1 = floor(($cek_daily_am1_1->jum + $cek_daily_am1_2->jum) /31);

    $cek_daily_am_1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order				
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan_tahun_am'")->row();
    $cek_daily_am_2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin				
				LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan_tahun_am'")->row();    
    $daily_am = floor(($cek_daily_am_1->jum + $cek_daily_am_2->jum) /31);

    $cek_daily_fix_1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order				
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan_tahun_fix'")->row();
    $cek_daily_fix_2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin				
				LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan_tahun_fix'")->row();    
    $daily_fix = floor(($cek_daily_fix_1->jum + $cek_daily_fix_2->jum) /31);

    $cek_daily_t1_1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order				
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan_tahun_t1'")->row();
    $cek_daily_t1_2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin				
				LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan_tahun_t1'")->row();    
    $daily_t1 = floor(($cek_daily_t1_1->jum + $cek_daily_t1_2->jum) /31);

    $cek_daily_t2_1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order				
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan_tahun_t2'")->row();
    $cek_daily_t2_2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin				
				LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan_tahun_t2'")->row();    
    $daily_t2 = floor(($cek_daily_t2_1->jum + $cek_daily_t2_2->jum) /31);

    ///cari stok dealer
    $cek_qty_am1 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
      WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.status = '4'
      AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) = '$bulan_tahun_am1'")->row();                   
    $cek_qty_am = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
      WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.status = '4'
      AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) = '$bulan_tahun_am'")->row();                   
    $cek_qty_fix = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
      WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.status = '4'
      AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) = '$bulan_tahun_fix'")->row();                   
    $cek_qty_t1 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
      WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.status = '4'
      AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) = '$bulan_tahun_t1'")->row();                   
    $cek_qty_t2 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
      WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.status = '4'
      AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) = '$bulan_tahun_t2'")->row();                   

    ///cek stok md
    $cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND status = '1'")->row();
    $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND status = '2'")->row();
    $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND status = '3'")->row();
		$cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND tipe = 'NRFS' AND status < 4")->row();
    $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND tipe = 'PINJAMAN' AND status < 4")->row();
    $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;

    //cari market stok ready
    $m_am1 = $cek_qty_am1->jum + $total;
    $m_am = $cek_qty_am->jum + $total;
    $m_fix = $cek_qty_fix->jum + $total;
    $m_t1 = $cek_qty_t1->jum + $total;
    $m_t2 = $cek_qty_t2->jum + $total;
 		echo "
 		<tr>
 			<td valign='top' rowspan='13'>$no</td>
 			<td valign='top' rowspan='13'>$isi->id_tipe_kendaraan</td>
 			<td valign='top' rowspan='13'>$isi->tipe_customer</td>
 			<td colspan='6'><font color='white'>-</font></td>
 		</tr>
 		<tr>
 			<td>PO Fix & Tentative</td>
 			<td align='right'>$po_fix_am1</td>
 			<td align='right'>$po_fix_am</td>
 			<td align='right'>$po_fix_fix</td>
 			<td align='right'>$po_fix_t1</td>
 			<td align='right'>$po_fix_t2</td>
 		</tr>
 		<tr>
 			<td>PO Additional</td>
 			<td align='right'>$po_add_am1</td>
 			<td align='right'>$po_add_am</td>
 			<td align='right'>$po_add_fix</td>
 			<td align='right'>$po_add_t1</td>
 			<td align='right'>$po_add_t2</td>
 		</tr>
 		<tr>
 			<td>Total PO</td>
 			<td align='right'>$total_am1</td>
 			<td align='right'>$total_am</td>
 			<td align='right'>$total_fix</td>
 			<td align='right'>$total_t1</td>
 			<td align='right'>$total_t2</td>
 		</tr>
 		<tr>
 			<td>Do Unfilled</td>
 			<td align='right'>$unfill_am1</td>
 			<td align='right'>$unfill_am</td>
 			<td align='right'>$unfill_fix</td>
 			<td align='right'>$unfill_t1</td>
 			<td align='right'>$unfill_t2</td>
 		</tr>
 		<tr>
 			<td>AHM Dist to MD</td>
 			<td align='right'>$isi->a_m1</td>
 			<td align='right'>$isi->a_m</td>
 			<td align='right'>$isi->a_fix</td>
 			<td align='right'>$isi->a_t1</td>
 			<td align='right'>$isi->a_t2</td>
 		</tr>
 		<tr>
 			<td>MD Dist to D</td>
 			<td align='right'>$md_dist_m1</td>
 			<td align='right'>$md_dist_m</td>
 			<td align='right'>$md_dist_fix</td>
 			<td align='right'>$md_dist_t1</td>
 			<td align='right'>$md_dist_t2</td>
 		</tr>
 		<tr>
 			<td>Retail Sales</td>
 			<td align='right'>$isi->b_m1</td>
 			<td align='right'>$isi->b_m</td>
 			<td align='right'>$isi->b_fix</td>
 			<td align='right'>$isi->b_t1</td>
 			<td align='right'>$isi->b_t2</td>
 		</tr>
 		<tr>
 			<td>Daily Sales</td>
 			<td align='right'>$daily_am1</td>
 			<td align='right'>$daily_am</td>
 			<td align='right'>$daily_fix</td>
 			<td align='right'>$daily_t1</td>
 			<td align='right'>$daily_t2</td>
 		</tr>
 		<tr>
 			<td>Dealer Stock</td>
 			<td align='right'>$cek_qty_am1->jum</td>
 			<td align='right'>$cek_qty_am->jum</td>
 			<td align='right'>$cek_qty_fix->jum</td>
 			<td align='right'>$cek_qty_t1->jum</td>
 			<td align='right'>$cek_qty_t2->jum</td>
 		</tr>
 		<tr>
 			<td>MD Stock</td>
 			<td align='right'>$total</td>
 			<td align='right'>$total</td>
 			<td align='right'>$total</td>
 			<td align='right'>$total</td>
 			<td align='right'>$total</td>
 		</tr>
 		<tr>
 			<td>Market Stock Day</td>
 			<td align='right'>$m_am1</td>
 			<td align='right'>$m_am</td>
 			<td align='right'>$m_fix</td>
 			<td align='right'>$m_t1</td>
 			<td align='right'>$m_t2</td>
 		</tr>
 		<tr>
 			<td>Total Stock Day</td>
 			<td align='right'>0</td>
 			<td align='right'>0</td>
 			<td align='right'>0</td>
 			<td align='right'>0</td>
 			<td align='right'>0</td>
 		</tr>
 		";
 		$no++;
 	}
 	?>
</table>
