<?php

class H3_md_laporan_voucher_pengeluaran_model extends CI_Model
{
    public function __construct(){
        parent::__construct();

        $this->load->library('Mcarbon');
		$this->load->helper('terbilang');
    }

	public function generatePdf($id_voucher_pengeluaran){
		$data =  $this->data($id_voucher_pengeluaran);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/laporan_voucher_pengeluaran_pdf', $data, true);
        $mpdf->WriteHTML($html);

		$filename = 'Voucher Pengeluaran';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
        // Output a PDF file directly to the browser
        $mpdf->Output("{$filename}.pdf", "I");
	}

    public function data($id_voucher_pengeluaran){
        $header = $this->db
		->select('vp.tanggal_transaksi')
		->select('vp.nama_account')
		->select('vp.no_rekening_account')
		->select('vp.no_giro')
		->select('
			case
				when vp.via_bayar = "Transfer" then vp.tanggal_transfer
				when vp.via_bayar = "Giro" then vp.tanggal_giro
			end as tanggal_bayar
		', false)
		->select('vp.nama_penerima_dibayarkan_kepada')
		->select('vp.no_rekening_tujuan')
		->select('vp.bank_tujuan')
		->select('vp.atas_nama_tujuan')
		->select('vp.total_amount')
		->select('
			case
				when vp.via_bayar = "Transfer" then vp.nominal_transfer
				when vp.via_bayar = "Giro" then vp.nominal_giro
			end as nominal_giro_transfer
		', false)
		->select('vp.deskripsi')
		->from('tr_h3_md_voucher_pengeluaran as vp')
		->join('ms_rek_md as rek', 'rek.id_rek_md = vp.id_account')
        ->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
		->where('vp.id_voucher_pengeluaran', $id_voucher_pengeluaran)
		->limit(1)
		->get()->row_array();

		$items = $this->db
		->select('ap.referensi')
		->select('vpi.nominal')
		->select('vpi.keterangan')
		->from('tr_h3_md_voucher_pengeluaran_items as vpi')
		->join('tr_h3_md_ap_part as ap', 'ap.id = vpi.id_referensi', 'left')
		->where('vpi.id_voucher_pengeluaran', $id_voucher_pengeluaran)
		->get()->result_array();

		$coa = $this->db
		->select('vpi.nomor_account')
		->select('coa.coa')
		->select('SUM(vpi.nominal) as nominal')
		->from('tr_h3_md_voucher_pengeluaran_items as vpi')
		->join('ms_coa as coa', 'coa.kode_coa = vpi.nomor_account')
		->where('vpi.id_voucher_pengeluaran', $id_voucher_pengeluaran)
		->group_by('vpi.nomor_account')
		->get()->result_array();

		$data = [];
		$data['header'] = $header;
		$data['items'] = $items;
		$data['coa'] = $coa;

		return $data;
    }
}
