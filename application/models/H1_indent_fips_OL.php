<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class H1_indent_fips extends CI_Model {

	public function get_data($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$cari = "";

		if ($search !='') {
			$cari = "
				AND 
				(
					tgl_spk LIKE '%$search%'
					OR no_spk LIKE '%$search%'
					OR tanda_jadi LIKE '%$search%'
					OR nama_dealer LIKE '%$search%'
					OR nama_konsumen LIKE '%$search%'
					OR tipe_ahm LIKE '%$search%'
					OR id_tipe_kendaraan LIKE '%$search%'
					OR id_warna LIKE '%$search%'
					OR jenis_beli LIKE '%$search%'
					OR finance_company LIKE '%$search%'
					OR tgl_pembuatan_po LIKE '%$search%'
					OR po_dari_finco LIKE '%$search%'
					OR status LIKE '%$search%'
					OR selisih_hari LIKE '%$search%'
				)
			";
		}

		$sql = "
			SELECT
				* 
			FROM
				(
				SELECT
					b.tgl_spk,
					b.no_spk,
					d.nama_dealer,
					b.nama_konsumen,
					e.tipe_ahm,
					b.id_tipe_kendaraan,
					b.id_warna,
					x.amount as tanda_jadi,
					c.finance_company,
					a.tgl_pembuatan_po,
					a.po_dari_finco,
					CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
					datediff(current_date(), b.tgl_spk) as selisih_hari,
					b.jenis_beli
				FROM
					tr_spk AS b
					LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
					LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
					LEFT JOIN tr_sales_order AS z ON b.no_spk = z.no_spk
					INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
					INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
					INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
					INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
				WHERE
					( tr_po_dealer_indent.STATUS = 'requested' OR tr_po_dealer_indent.STATUS = 'proses' ) 
					AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
					AND x.id_kwitansi IS NOT NULL 
					AND z.id_sales_order IS NULL 
					AND b.tanda_jadi > 0 
				ORDER BY
					b.tgl_spk DESC 
				) AS table1 
			WHERE
				( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' ) 
				OR table1.jenis_beli = 'Cash'

				$cari
			ORDER BY $order_field $order_ascdesc
			LIMIT $start, $limit
		";

		return $this->db->query($sql);
	}

	public function total_data($search)
	{
		$cari = "";

		if ($search !='') {
			$cari = "
				AND 
				(
					tgl_spk LIKE '%$search%'
					OR no_spk LIKE '%$search%'
					OR tanda_jadi LIKE '%$search%'
					OR nama_dealer LIKE '%$search%'
					OR nama_konsumen LIKE '%$search%'
					OR tipe_ahm LIKE '%$search%'
					OR id_tipe_kendaraan LIKE '%$search%'
					OR id_warna LIKE '%$search%'
					OR jenis_beli LIKE '%$search%'
					OR finance_company LIKE '%$search%'
					OR tgl_pembuatan_po LIKE '%$search%'
					OR po_dari_finco LIKE '%$search%'
					OR status LIKE '%$search%'
					OR selisih_hari LIKE '%$search%'
				)
			";
		}

		$sql = "
			SELECT
				COUNT(no_spk) as total
			FROM
				(
				SELECT
					b.tgl_spk,
					b.no_spk,
					d.nama_dealer,
					b.nama_konsumen,
					e.tipe_ahm,
					b.id_tipe_kendaraan,
					b.id_warna,
					b.tanda_jadi,
					c.finance_company,
					a.tgl_pembuatan_po,
					a.po_dari_finco,
					CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
					datediff(current_date(), b.tgl_spk) as selisih_hari,
					b.jenis_beli
				FROM
					tr_spk AS b
					LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
					LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
					LEFT JOIN tr_sales_order AS z ON b.no_spk = z.no_spk
					INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
					INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
					INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
					INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
				WHERE
					( tr_po_dealer_indent.STATUS = 'requested' OR tr_po_dealer_indent.STATUS = 'proses' ) 
					AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
					AND x.id_kwitansi IS NOT NULL 
					AND z.id_sales_order IS NULL 
					AND b.tanda_jadi > 0 
				ORDER BY
					b.tgl_spk DESC 
				) AS table1 
			WHERE
				( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' ) 
				OR table1.jenis_beli = 'Cash'

				$cari
		";

		return $this->db->query($sql)->row()->total;
	}

	public function download_excel()
	{
		$sql = "
		SELECT
			* 
		FROM
			(
			SELECT
				b.tgl_spk,
				b.no_spk,
				d.nama_dealer,
				b.nama_konsumen,
				e.tipe_ahm,
				b.id_tipe_kendaraan,
				b.id_warna,
				b.tanda_jadi,
				c.finance_company,
				a.tgl_pembuatan_po,
				a.po_dari_finco,
				CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
				datediff(current_date(), b.tgl_spk) as selisih_hari,
				b.jenis_beli
			FROM
				tr_spk AS b
				LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
				LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
				LEFT JOIN tr_sales_order AS z ON b.no_spk = z.no_spk
				INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
				INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
				INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
				INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
			WHERE
				( tr_po_dealer_indent.STATUS = 'requested' OR tr_po_dealer_indent.STATUS = 'proses' ) 
				AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
				AND x.id_kwitansi IS NOT NULL 
				AND z.id_sales_order IS NULL 
				AND b.tanda_jadi > 0 
			ORDER BY
				b.tgl_spk DESC 
			) AS table1 
		WHERE
			( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' ) 
			OR table1.jenis_beli = 'Cash'
		";

		return $this->db->query($sql);
	}


}

/* End of file H1_indent_fips.php */