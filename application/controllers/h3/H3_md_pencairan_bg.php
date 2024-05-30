<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_pencairan_bg extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_pencairan_bg";
    protected $title  = "Pencairan BG";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_tanda_terima_faktur_model', 'tanda_terima_faktur');	
		$this->load->model('H3_md_tanda_terima_faktur_item_model', 'tanda_terima_faktur_item');	
		$this->load->model('H3_md_penerimaan_pembayaran_model', 'penerimaan_pembayaran');	

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

	}

	public function index()
	{				
		$data['set']	= "index";
		$this->template($data);	
	}

	public function add()
	{				
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);	
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['pencairan_bg'] = $this->db
        ->select('d.nama_dealer')
        ->select('"Part" as jenis_pembayaran')
        ->select('pb.id_penerimaan_pembayaran')
        ->select('pb.nama_bank_bg')
        ->select('pb.nomor_bg')
        ->select('date_format(pb.tanggal_jatuh_tempo_bg, "%d/%m/%Y") as tanggal_jatuh_tempo_bg')
		->select('pb.nominal_bg')
        ->select('rek.bank as nama_bank_tujuan')
		->select('rek.no_rekening as no_rekening_tujuan')
		->select('ifnull(pb.status_bg, "") as status_bg')
		->select('pb.tanggal_cair_bg')
		->select('pb.alasan_penolakan_bg')
		->select('pb.proses_bg')
        ->from('tr_h3_md_penerimaan_pembayaran as pb')
        ->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
        ->join('ms_rek_md as rek', 'rek.id_rek_md = pb.id_rekening_md_bg')
		->where('pb.jenis_pembayaran', 'BG')
		->where('pb.id_penerimaan_pembayaran', $this->input->get('id_penerimaan_pembayaran'))
		->get()->row_array();

		$this->template($data);	
	}

	public function proses(){
		$this->validate_proses();

		$this->db->trans_start();
		$data = $this->input->post(['status_bg']);

		if($this->input->post('status_bg') != null && $this->input->post('status_bg') == 'Cair'){
			$data['tanggal_cair_bg'] = $this->input->post('tanggal_cair_bg');
			
			$this->pencairan_bg($this->input->post('id_penerimaan_pembayaran'));
		}

		if ($this->input->post('status_bg') != null && $this->input->post('status_bg') == 'Tolak') {
			$data['alasan_penolakan_bg'] = $this->input->post('alasan_penolakan_bg');
		}

		$data['proses_bg_at'] = date('Y-m-d H:i:s', time());
		$data['proses_bg_by'] = $this->session->userdata('id_user');
		$data['proses_bg'] = 1;
		$this->penerimaan_pembayaran->update($data, $this->input->post(['id_penerimaan_pembayaran']));

		
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->penerimaan_pembayaran->get($this->input->post(['id_penerimaan_pembayaran']), true)
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function pencairan_bg($id_penerimaan_pembayaran){
		$this->load->model('H3_md_ar_part_model', 'ar_part');

		$items = $this->db
		->select('pbi.referensi')
		->select('pbi.jumlah_pembayaran')
		->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
		->where('pbi.id_penerimaan_pembayaran', $id_penerimaan_pembayaran)
		->get()->result_array();

		foreach ($items as $item) {
			$this->db
			->set('ar.sudah_dibayar', "ar.sudah_dibayar + {$item['jumlah_pembayaran']}", false)
			->where('ar.referensi', $item['referensi'])
			->update('tr_h3_md_ar_part as ar');

			$this->ar_part->dilunaskan($item['referensi']);
		}
	}

	public function validate_proses(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('status_bg', 'Status BG', 'required');

		if($this->input->post('status_bg') != null && $this->input->post('status_bg') == 'Cair'){
			$this->form_validation->set_rules('tanggal_cair_bg', 'Tanggal Cair', 'required');
		}

		if ($this->input->post('status_bg') != null && $this->input->post('status_bg') == 'Tolak') {
			$this->form_validation->set_rules('alasan_penolakan_bg', 'Alasan Penolakan', 'required');
		}

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }
}