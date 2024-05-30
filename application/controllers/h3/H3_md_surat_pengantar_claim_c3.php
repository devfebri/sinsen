<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_surat_pengantar_claim_c3 extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_surat_pengantar_claim_c3";
    protected $title  = "Surat Pengantar Claim C3";

	public function __construct(){		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('H3_md_surat_pengantar_claim_c3_model', 'surat_pengantar_claim_c3');
		$this->load->model('H3_md_surat_pengantar_claim_c3_item_model', 'surat_pengantar_claim_c3_item');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['surat_pengantar'] = $this->db
        ->select('spcd.id_surat_pengantar')
        ->select('date_format(spcd.tanggal, "%d/%m/%Y") as tanggal')
        ->select('spcd.id_jawaban_claim_dealer')
        ->from('tr_h3_md_surat_pengantar_claim_c3_dealer as spcd')
        // ->join('tr_h3_md_jawaban_claim_dealer as jcd', 'jcd.id_jawaban_claim_dealer = spcd.id_jawaban_claim_dealer')
        ->join('ms_dealer as d', 'd.id_dealer = spcd.id_dealer')
		->where('spcd.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('spcd.id_part')
		->select('p.nama_part')
		->select('spcd.id_claim_dealer')
		->select('spcd.id_kategori_claim_c3')
		->select('spcd.qty_ganti_barang')
		->select('spcd.no_faktur')
		->select('spcd.no_dus')
		->select('
			case
				when jcdp.barang_checklist then "Ganti Barang"
				when jcdp.uang_checklist then "Ganti Uang"
				when jcdp.tolak_checklist then "Ditolak"
			end as keterangan
		', false)
		->from('tr_h3_md_surat_pengantar_claim_c3_dealer_item as spcd')
		->join('tr_h3_md_surat_pengantar_claim_c3_dealer as spc', 'spc.id_surat_pengantar = spcd.id_surat_pengantar')
		->join('ms_part as p', 'p.id_part = spcd.id_part')
		->join('tr_h3_md_jawaban_claim_dealer_parts as jcdp', '(jcdp.id_claim_dealer = spcd.id_claim_dealer and jcdp.id_kategori_claim_c3 = spcd.id_kategori_claim_c3 and spcd.id_part = jcdp.id_part and spc.id_jawaban_claim_dealer = jcdp.id_jawaban_claim_dealer)')
		->where('spcd.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
		->get()->result_array();

		$this->template($data);
	}

	public function cetak(){
		$this->surat_pengantar_claim_c3->update([
			'sudah_cetak' => 1,
			'cetak_at' => date('Y-m-d H:i:s', time()),
			'cetak_by' => $this->session->userdata('id_user')
		], [
			'id_surat_pengantar' => $this->input->get('id_surat_pengantar'),
			'sudah_cetak' => 0,
		]);

		$data = [];
		$data['surat_pengantar'] = $this->db
        ->select('spcd.id_surat_pengantar')
        ->select('date_format(spcd.tanggal, "%d/%m/%Y") as tanggal')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('d.pic')
        ->from('tr_h3_md_surat_pengantar_claim_c3_dealer as spcd')
        // ->join('tr_h3_md_jawaban_claim_dealer as jcd', 'jcd.id_jawaban_claim_dealer = spcd.id_jawaban_claim_dealer')
        ->join('ms_dealer as d', 'd.id_dealer = spcd.id_dealer')
		->where('spcd.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('spcd.id_part')
		->select('p.nama_part')
		->select('spcd.id_claim_dealer')
		->select('spcd.id_kategori_claim_c3')
		->select('spcd.qty_ganti_barang')
		->select('spcd.no_faktur')
		->select('spcd.no_dus')
		->select('
			case
				when jcdp.barang_checklist then "Ganti Barang"
				when jcdp.uang_checklist then "Ganti Uang"
				when jcdp.tolak_checklist then "Ditolak"
			end as keterangan
		', false)
		->from('tr_h3_md_surat_pengantar_claim_c3_dealer_item as spcd')
		->join('tr_h3_md_surat_pengantar_claim_c3_dealer as spc', 'spc.id_surat_pengantar = spcd.id_surat_pengantar')
		->join('ms_part as p', 'p.id_part = spcd.id_part')
		->join('tr_h3_md_jawaban_claim_dealer_parts as jcdp', '(jcdp.id_claim_dealer = spcd.id_claim_dealer and jcdp.id_kategori_claim_c3 = spcd.id_kategori_claim_c3 and spcd.id_part = jcdp.id_part and spc.id_jawaban_claim_dealer = jcdp.id_jawaban_claim_dealer)')
		->where('spcd.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
		->get()->result_array();

        // $this->load->library('mpdf_l');
        require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_cetak_surat_pengantar_claim_c3', $data, true);
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output("{$data['packing_sheet']->no_surat_jalan}.pdf", "I");
	}
}