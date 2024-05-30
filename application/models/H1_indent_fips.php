<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class H1_indent_fips extends CI_Model {

	public function get_data($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer=null, $data_array=null)
	{
		$where = "";
		$cari = "";
		$cari_other = "";

		$column_order = array('','tgl_spk','no_spk','nama_dealer','nama_konsumen','no_hp','tipe_ahm','id_tipe_kendaraan','id_warna','tanda_jadi','finance_company','tgl_pembuatan_po','po_dari_finco','status','selisih_hari','');
    
		if ($id_dealer != null) {
			$where = "and table1.id_dealer = $id_dealer";
		}

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

		if ($data_array != null) {

			$cari_other = "

				AND 
				(
					nama_dealer LIKE '%".$data_array['nm_dealer']."%'
					AND id_tipe_kendaraan LIKE '%".$data_array['tipe']."%'
					AND kode_dealer_md LIKE '%".$data_array['kd_dealer']."%'
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
					d.kode_dealer_md,
					d.nama_dealer,
					b.nama_konsumen,
					b.no_hp,
					e.tipe_ahm,
					b.id_tipe_kendaraan,
					b.id_warna,
					b.tanda_jadi,
					c.finance_company,
					a.tgl_pembuatan_po,
					a.po_dari_finco,
					tr_po_dealer_indent.updated_at,
					CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
					datediff(current_date(), b.tgl_spk) as selisih_hari,
					b.jenis_beli,
					b.id_dealer,
					tr_po_dealer_indent.send_ahm,
					tr_po_dealer_indent.id_indent
				FROM
					tr_spk AS b
					LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
					LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
					INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
					INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
					INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
					INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
				WHERE
					tr_po_dealer_indent.STATUS = 'requested'
					AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
					AND x.id_kwitansi IS NOT NULL 
					AND b.status_spk in ('approved','booking')
					AND b.tanda_jadi > 0 
				ORDER BY
					b.tgl_spk DESC 
				) AS table1 
			WHERE
				( ( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' ) 
				OR table1.jenis_beli = 'Cash' )
				$where
				$cari
				$cari_other
			ORDER BY $column_order[$order_field] $order_ascdesc
			LIMIT $start, $limit
		";
		return $this->db->query($sql);
	}

	public function total_data($search, $id_dealer=null, $data_array=null)
	{
		$where = "";
		if ($id_dealer != null) {
			$where = "and table1.id_dealer = $id_dealer";
		}
		$cari = "";
		$cari_other = "";

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

		if ($data_array != null) {

			$cari_other = "

				AND 
				(
					nama_dealer LIKE '%".$data_array['nm_dealer']."%'
					AND id_tipe_kendaraan LIKE '%".$data_array['tipe']."%'
					AND kode_dealer_md LIKE '%".$data_array['kd_dealer']."%'
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
				d.kode_dealer_md,
				d.nama_dealer,
				b.nama_konsumen,
				b.no_hp,
				e.tipe_ahm,
				b.id_tipe_kendaraan,
				b.id_warna,
				b.tanda_jadi,
				c.finance_company,
				a.tgl_pembuatan_po,
				a.po_dari_finco,
				tr_po_dealer_indent.updated_at,
				CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
				datediff(current_date(), b.tgl_spk) as selisih_hari,
				b.jenis_beli,
				b.id_dealer
			FROM
					tr_spk AS b
					LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
					LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
					INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
					INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
					INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
					INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
				WHERE
					tr_po_dealer_indent.STATUS = 'requested'
					AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
					AND x.id_kwitansi IS NOT NULL 
					AND b.status_spk in ('approved','booking')
					AND b.tanda_jadi > 0 
				ORDER BY
					b.tgl_spk DESC 
				) AS table1 
			WHERE
				( ( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' )  OR table1.jenis_beli = 'Cash' )
				$where
				$cari
				$cari_other
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
				b.no_ktp,
				b.no_spk,
				d.nama_dealer,
				b.nama_konsumen,
				b.no_hp,
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
				INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
				INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
				INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
				INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
			WHERE
				tr_po_dealer_indent.STATUS = 'requested'
				AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
				AND x.id_kwitansi IS NOT NULL 
				AND b.status_spk in ('approved','booking')
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

	public function download_history($id_dealer=null)
	{
		$where = "";
		if ($id_dealer != null) {
			$where = "and table1.id_dealer = $id_dealer";
		}

		$sql = "
		SELECT
			* 
		FROM
			(
			SELECT
				b.tgl_spk,
				b.no_ktp,
				b.no_spk,
				d.nama_dealer,
				b.nama_konsumen,
				b.no_hp,
				e.tipe_ahm,
				b.id_tipe_kendaraan,
				b.id_warna,
				b.tanda_jadi,
				c.finance_company,
				a.tgl_pembuatan_po,
				a.po_dari_finco,
				CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
				datediff(current_date(), b.tgl_spk) as selisih_hari,
				b.jenis_beli,
				b.id_dealer,
				tr_po_dealer_indent.updated_at as tgl_pemenuhan
			FROM
				tr_spk AS b
				LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
				LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
				INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
				INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
				INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
				INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
			WHERE
				tr_po_dealer_indent.STATUS = 'proses'
				AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
				AND x.id_kwitansi IS NOT NULL 
				AND b.status_spk in ('approved','booking')
				AND b.tanda_jadi > 0 
			ORDER BY
				b.tgl_spk DESC 
			) AS table1 
		WHERE
			( ( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' ) 
				OR table1.jenis_beli = 'Cash' )
				$where
		";

		return $this->db->query($sql);
	}

	public function download_excel_laporan($id_dealer=null)
	{
		$where = "";
		if ($id_dealer != null) 
		{
			$where = "and table1.id_dealer = $id_dealer";
		}

		$sql = "
		SELECT
			* 
		FROM
			(
			SELECT
				b.tgl_spk,
				b.no_ktp,
				b.no_spk,
				d.nama_dealer,
				b.nama_konsumen,
				b.no_hp,
				e.tipe_ahm,
				b.id_tipe_kendaraan,
				b.id_warna,
				b.tanda_jadi,
				c.finance_company,
				a.tgl_pembuatan_po,
				a.po_dari_finco,
				CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
				datediff(current_date(), b.tgl_spk) as selisih_hari,
				b.jenis_beli,
				b.id_dealer
			FROM
				tr_spk AS b
				LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
				LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
				INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
				INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
				INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
				INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
			WHERE
				tr_po_dealer_indent.STATUS = 'requested'
				AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
				AND x.id_kwitansi IS NOT NULL 
				AND b.status_spk in ('approved','booking')
				AND b.tanda_jadi > 0 
			ORDER BY
				b.tgl_spk DESC
			) AS table1 
		WHERE
			( ( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' ) 
				OR table1.jenis_beli = 'Cash' )
				$where";

		return $this->db->query($sql);
	}

	public function cek_all_indent($id_dealer=null)
	{
		$where = "";
		if ($id_dealer != null) {
			$where = "and table1.id_dealer = $id_dealer";
		}

		$sql = "
		SELECT
			* 
		FROM
			(
			SELECT
				b.tgl_spk,
				b.no_ktp,
				b.no_spk,
				d.nama_dealer,
				b.nama_konsumen,
				b.no_hp,
				e.tipe_ahm,
				b.id_tipe_kendaraan,
				b.id_warna,
				x.amount,
				c.finance_company,
				a.tgl_pembuatan_po,
				a.po_dari_finco,
				CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
				datediff(current_date(), b.tgl_spk) as selisih_hari,
				b.jenis_beli,
				b.id_dealer,
				tr_po_dealer_indent.updated_at as tgl_pemenuhan,
				x.id_kwitansi

			FROM
				tr_spk AS b
				LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
				LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
				INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
				INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
				INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
				INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
			WHERE
				tr_po_dealer_indent.STATUS = 'requested'
				AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
				AND b.status_spk in ('approved','booking')
				AND b.tanda_jadi > 0 
			ORDER BY
				b.tgl_spk DESC 
			) AS table1 
		WHERE
			( table1.jenis_beli = 'Kredit' OR table1.jenis_beli = 'Cash' )
				$where
		";

		return $this->db->query($sql);
	}

	public function sla_finco($id_dealer=null)
	{
		$where = "";
		if ($id_dealer != null) {
			$where = "and table1.id_dealer = $id_dealer";
		}

		$sql = "
		SELECT
			* 
		FROM
			(
			SELECT
				b.tgl_spk,
				b.no_spk,
				d.kode_dealer_md,
				d.nama_dealer,
				b.nama_konsumen,
				b.no_hp,
				e.tipe_ahm,
				b.id_tipe_kendaraan,
				b.id_warna,
				b.tanda_jadi,
				c.finance_company,
				a.created_at,
				b.created_at as tanggal_spk,
				a.po_dari_finco,
				CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
				datediff(a.created_at, b.created_at) as selisih_hari,
				b.jenis_beli,
				b.id_dealer,
				tsb.no_mesin,
				tsb.no_rangka
			FROM
				tr_spk AS b
				LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
				LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
				LEFT JOIN tr_scan_barcode AS tsb ON b.no_mesin_spk= tsb.no_mesin
				INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
				INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
				INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
				INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
			WHERE ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
				AND x.id_kwitansi IS NOT NULL 
				AND b.status_spk in ('approved','booking')
				AND b.tanda_jadi > 0 
			ORDER BY
				b.tgl_spk DESC 
			) AS table1 
		WHERE
			table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != ''
			$where
		";

		return $this->db->query($sql);
	}
	
	public function searchData($search)
	{
		$query = $this->db->query("SELECT a.nama_konsumen,b.id_tipe_kendaraan, b.id_warna, c.tipe_ahm, d.finance_company, e.po_dari_finco, e.tgl_pembuatan_po, a.jenis_beli, a.no_mesin_spk, a.status_spk, a.tanda_jadi, b.id_indent, b.id_reasons, a.id_dealer,
		(CASE WHEN b.id_tipe_kendaraan!='null' THEN b.id_tipe_kendaraan
			  ELSE a.id_tipe_kendaraan END) as id_tipe_kendaraan,
		(CASE WHEN b.id_warna!='null' THEN b.id_warna
			  ELSE a.id_warna END) as id_warna,  
		(CASE when f.id_kwitansi is null then 'Belum Input Pembayaran'
						WHEN b.status='requested' THEN 'Belum Dipenuhi'
					  	WHEN b.status='proses' THEN 'Sudah Dipenuhi'
						WHEN b.status='canceled' THEN 'Dealer Batal SPK'
						WHEN b.status='cancel' THEN 'Dealer Batal SPK'
						WHEN b.status='completed' THEN 'SPK Sudah SSU'
						WHEN b.status is null THEN 'Belum Indent' END) as status_indent, f.id_kwitansi 
									FROM tr_spk a 
									LEFT JOIN tr_po_dealer_indent b ON b.id_spk = a.no_spk 
									JOIN ms_tipe_kendaraan AS c ON c.id_tipe_kendaraan = a.id_tipe_kendaraan
									LEFT JOIN ms_finance_company AS d ON a.id_finance_company = d.id_finance_company
									LEFT JOIN tr_entry_po_leasing AS e ON e.no_spk = a.no_spk
									left join tr_h1_dealer_invoice_receipt f on f.no_spk = a.no_spk and f.jenis_invoice ='tjs'
									WHERE a.no_spk LIKE '$search'  order by b.created_at asc");
		return $query->result();
	}

	public function searchDataDistinct($search)
	{
		$query = $this->db->query("SELECT DISTINCT a.nama_konsumen, a.no_mesin_spk, a.status_spk, a.tanda_jadi, a.jenis_beli, a.id_dealer
									FROM tr_spk a 
									LEFT JOIN tr_po_dealer_indent b ON b.id_spk = a.no_spk 
									JOIN ms_tipe_kendaraan AS c ON c.id_tipe_kendaraan = a.id_tipe_kendaraan
									LEFT JOIN ms_finance_company AS d ON a.id_finance_company = d.id_finance_company
									WHERE a.no_spk LIKE '$search'");
		return $query->result();
	}

}

/* End of file H1_indent_fips.php */