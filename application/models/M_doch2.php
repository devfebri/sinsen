<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_doch extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getData($tgl1, $tgl2)
	{
		$id_dealer = $this->m_admin->cari_dealer();
		if ($this->session->userdata('jenis_user') == 'Dealer') {
			$where_dealer = "AND a.id_dealer='$id_dealer'";
		} else {
			$where_dealer = "";
		}

		$sql = "
		SELECT
			a.kode_dealer_md,
			a.nama_dealer,
			i.sales,
			ifnull( b.total_dist_stnk, 0 ) AS total_dist_stnk,
			ifNull( e.total_terima_stnk, 0 ) AS total_terima_stnk,
			ifNull( h.total_serah_stnk, 0 ) AS total_serah_stnk,
			ifNull( c.total_dist_bpkb, 0 ) AS total_dist_bpkb,
			ifNull( f.total_terima_bpkb, 0 ) AS total_terima_bpkb,
			ifNull( h.total_serah_bpkb, 0 ) AS total_serah_bpkb,
			ifNull( d.total_dist_plat, 0 ) AS total_dist_plat,
			ifNull( g.total_terima_plat, 0 ) AS total_terima_plat,
			ifNull( h.total_serah_plat, 0 ) AS total_serah_plat,
			ifNull( h.total_serah_stnk, 0 ) + ifNull( h.total_serah_bpkb, 0 ) + ifNull( h.total_serah_plat, 0 ) AS total 
		FROM
			ms_dealer a
			LEFT JOIN (
			SELECT
				id_dealer,
				COUNT( 1 ) AS total_dist_stnk 
			FROM
				tr_penyerahan_stnk a
				JOIN tr_penyerahan_stnk_detail b ON a.no_serah_stnk = b.no_serah_stnk 
			WHERE
				a.tgl_serah_terima BETWEEN '$tgl1' 
				AND '$tgl2' 
			GROUP BY
				id_dealer 
			) b ON a.id_dealer = b.id_dealer
			LEFT JOIN (
			SELECT
				id_dealer,
				COUNT( 1 ) AS total_dist_bpkb 
			FROM
				tr_penyerahan_bpkb a
				JOIN tr_penyerahan_bpkb_detail b ON a.no_serah_bpkb = b.no_serah_bpkb 
			WHERE
				a.tgl_serah_terima BETWEEN '$tgl1' 
				AND '$tgl2' 
			GROUP BY
				id_dealer 
			) c ON a.id_dealer = c.id_dealer
			LEFT JOIN (
			SELECT
				id_dealer,
				COUNT( 1 ) AS total_dist_plat 
			FROM
				tr_penyerahan_plat a
				JOIN tr_penyerahan_plat_detail b ON a.no_serah_plat = b.no_serah_plat 
			WHERE
				a.tgl_serah_terima BETWEEN '$tgl1' 
				AND '$tgl2' 
			GROUP BY
				id_dealer 
			) d ON a.id_dealer = d.id_dealer
			LEFT JOIN (
			SELECT
				id_dealer,
				COUNT( 1 ) AS total_terima_stnk 
			FROM
				tr_penyerahan_stnk a
				JOIN tr_penyerahan_stnk_detail b ON a.no_serah_stnk = b.no_serah_stnk 
			WHERE
				a.tgl_serah_terima BETWEEN '$tgl1' 
				AND '$tgl2' 
				AND b.status_nosin = 'terima' 
			GROUP BY
				id_dealer 
			) e ON a.id_dealer = e.id_dealer
			LEFT JOIN (
			SELECT
				id_dealer,
				COUNT( 1 ) AS total_terima_bpkb 
			FROM
				tr_penyerahan_bpkb a
				JOIN tr_penyerahan_bpkb_detail b ON a.no_serah_bpkb = b.no_serah_bpkb 
			WHERE
				a.tgl_serah_terima BETWEEN '$tgl1' 
				AND '$tgl2' 
				AND b.status_nosin = 'terima' 
			GROUP BY
				id_dealer 
			) f ON a.id_dealer = f.id_dealer
			LEFT JOIN (
			SELECT
				id_dealer,
				COUNT( 1 ) AS total_terima_plat 
			FROM
				tr_penyerahan_plat a
				JOIN tr_penyerahan_plat_detail b ON a.no_serah_plat = b.no_serah_plat 
			WHERE
				a.tgl_serah_terima BETWEEN '$tgl1' 
				AND '$tgl2' 
				AND b.status_nosin = 'terima' 
			GROUP BY
				id_dealer 
			) g ON a.id_dealer = g.id_dealer
			LEFT JOIN (
			SELECT
				a.id_dealer,
				sum((
					CASE
							
							WHEN tgl_terima_stnk IS NOT NULL 
							AND jenis_cetak = 'stnk' THEN
								1 ELSE 0 
							END 
							)) AS total_serah_stnk,
						sum((
							CASE
									
									WHEN tgl_terima_bpkb IS NOT NULL 
									AND jenis_cetak = 'bpkb' THEN
										1 ELSE 0 
									END 
									)) AS total_serah_bpkb,
								sum((
									CASE
											
											WHEN tgl_terima_plat IS NOT NULL 
											AND jenis_cetak = 'plat' THEN
												1 ELSE 0 
											END 
											)) AS total_serah_plat 
									FROM
										tr_tandaterima_stnk_konsumen a 
									join tr_tandaterima_stnk_konsumen_detail b on a.kd_stnk_konsumen =b.kd_stnk_konsumen
									WHERE
										cast( a.created_at AS date ) BETWEEN '$tgl1' 
										AND '$tgl2' 
									GROUP BY
										a.id_dealer 
										) h ON a.id_dealer = h.id_dealer
									LEFT JOIN ( SELECT id_dealer, count( 1 ) AS sales FROM tr_sales_order WHERE tgl_cetak_invoice BETWEEN '$tgl1' AND '$tgl2' GROUP BY id_dealer ) i ON a.id_dealer = i.id_dealer 
								WHERE
									a.h1 = 1 
									AND a.active = 1 
									$where_dealer
							ORDER BY
			total DESC
		";
		$query = $this->db->query($sql);
		return $query;
	}

	public function getDataDetailDoch($tgl1, $tgl2)
	{
		$id_dealer = $this->m_admin->cari_dealer();
		if ($this->session->userdata('jenis_user') == 'Dealer') {
			$where_dealer = "AND h.id_dealer='$id_dealer'";
		} else {
			$where_dealer = "";
		}
		
		$sql ="
			select h.kode_dealer_md , '' as nama_konsumen,  h.nama_dealer, jualan.*, s.tipe_ahm, i.kode_warna, j.no_pol, j.no_stnk, j.no_bpkb, k.tgl_serah_terima_stnk, k.penerima_stnk, k.biro_jasa, l.created_at, l.tgl_serah_terima_bpkb, l.penerima_bpkb , (case when penerima_stnk is not null then j.nama_konsumen end) as nama_penerima, m.tgl_serah_terima_plat, m.penerima_plat
			from (
				select b.tgl_spk, a.tgl_cetak_invoice as tgl_ssu, b.nama_konsumen, a.delivery_document_id as id_unit_delivery, b.no_ktp as id_customer, a.id_sales_order , d.id_flp_md as honda_id, b.id_tipe_kendaraan , concat('MH1', a.no_rangka) as no_rangka, a.no_mesin as no_mesin , a.id_dealer 
				from tr_sales_order a  
				join tr_spk b on a.no_spk =b.no_spk
				join tr_prospek c on b.id_customer = c.id_customer 
				join ms_karyawan_dealer d on c.id_karyawan_dealer  = d.id_karyawan_dealer 
				where a.tgl_cetak_invoice >='$tgl1' and a.tgl_cetak_invoice <='$tgl2'
				union
				select c.tgl_spk_gc, a.tgl_cetak_invoice, c.nama_npwp as nama_konsumen, b.delivery_document_id, c.no_npwp as id_customer , a.id_sales_order_gc , e.id_flp_md , f.tipe_motor , CONCAT('MH1', f.no_rangka) as no_rangka, f.no_mesin as no_mesin , a.id_dealer 
				from tr_sales_order_gc a
				join tr_sales_order_gc_nosin b on a.id_sales_order_gc = b.id_sales_order_gc 
				join tr_spk_gc c on a.no_spk_gc  = c.no_spk_gc 
				join tr_prospek_gc d on d.id_prospek_gc = c.id_prospek_gc 
				join ms_karyawan_dealer e on d.id_karyawan_dealer  = e.id_karyawan_dealer 
				join tr_scan_barcode f on f.no_mesin = b.no_mesin 
				where a.tgl_cetak_invoice >='$tgl1' and a.tgl_cetak_invoice <= '$tgl2'
			) as jualan
			join ms_dealer h on h.id_dealer = jualan.id_dealer
			join tr_fkb i on i.no_mesin_spasi = jualan.no_mesin
			join ms_tipe_kendaraan s on s.id_tipe_kendaraan = jualan.id_tipe_kendaraan
			left join tr_entry_stnk j on jualan.no_mesin = j.no_mesin 
			left join (
				select date_format(a.created_at,\"%d-%m-%Y\") as tgl_serah_terima_stnk, a.diterima as penerima_stnk, 'CV. Karya Mandiri' as biro_jasa, b.no_mesin 
				from tr_tandaterima_stnk_konsumen a
				join tr_tandaterima_stnk_konsumen_detail b on a.kd_stnk_konsumen = b.kd_stnk_konsumen 
				where a.jenis_cetak ='stnk'
				group by date_format(a.created_at,\"%d-%m-%Y\"), b.no_mesin 
			) k on j.no_mesin = k.no_mesin
			left join (
				select date_format(a.created_at,\"%d-%m-%Y\") as tgl_serah_terima_bpkb, a.diterima as penerima_bpkb, 'CV. Karya Mandiri' as biro_jasa, b.no_mesin , a.created_at
				from tr_tandaterima_stnk_konsumen a
				join tr_tandaterima_stnk_konsumen_detail b on a.kd_stnk_konsumen = b.kd_stnk_konsumen 
				where a.jenis_cetak ='bpkb'
				group by date_format(a.created_at,\"%d-%m-%Y\"), b.no_mesin 
			) l on j.no_mesin = l.no_mesin 
			left join (
				select date_format(a.created_at,\"%d-%m-%Y\") as tgl_serah_terima_plat, a.diterima as penerima_plat, 'CV. Karya Mandiri' as biro_jasa, b.no_mesin , a.created_at
				from tr_tandaterima_stnk_konsumen a
				join tr_tandaterima_stnk_konsumen_detail b on a.kd_stnk_konsumen = b.kd_stnk_konsumen 
				where a.jenis_cetak ='plat'
				group by date_format(a.created_at,\"%d-%m-%Y\"), b.no_mesin 
			) m on j.no_mesin = m.no_mesin 
			where 1=1 $where_dealer
			order by jualan.tgl_ssu asc
		";

		$query = $this->db->query($sql);
		return $query;
	}


}