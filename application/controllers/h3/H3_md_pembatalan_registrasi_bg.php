<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_pembatalan_registrasi_bg extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_pembatalan_registrasi_bg";
    protected $title  = "Pembatalan Registrasi BG";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
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
		$data['pembatalan_registrasi_bg'] = $this->db
        ->select('d.nama_dealer')
        ->select('"Part" as jenis_pembayaran')
        ->select('pb.id_penerimaan_pembayaran')
        ->select('pb.nama_bank_bg')
        ->select('pb.nomor_bg')
        ->select('date_format(pb.tanggal_jatuh_tempo_bg, "%d/%m/%Y") as tanggal_jatuh_tempo_bg')
		->select('pb.nominal_bg')
        ->select('rek.bank as nama_bank_tujuan')
		->select('rek.no_rekening as no_rekening_tujuan')
		->select('pb.keterangan_bg')
		->select('pb.cancel_bg')
        ->from('tr_h3_md_penerimaan_pembayaran as pb')
        ->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
        ->join('ms_rek_md as rek', 'rek.id_rek_md = pb.id_rekening_md_bg')
		->where('pb.jenis_pembayaran', 'BG')
		->where('pb.id_penerimaan_pembayaran', $this->input->get('id_penerimaan_pembayaran'))
		->get()->row_array();

		$this->template($data);	
	}

	public function edit(){
		$data['mode'] = 'edit';
		$data['set'] = "form";
		$data['pembatalan_registrasi_bg'] = $this->db
        ->select('d.nama_dealer')
        ->select('"Part" as jenis_pembayaran')
        ->select('pb.id_penerimaan_pembayaran')
        ->select('pb.nama_bank_bg')
        ->select('pb.nomor_bg')
        ->select('date_format(pb.tanggal_jatuh_tempo_bg, "%d/%m/%Y") as tanggal_jatuh_tempo_bg')
		->select('pb.nominal_bg')
        ->select('rek.bank as nama_bank_tujuan')
		->select('rek.no_rekening as no_rekening_tujuan')
		->select('pb.keterangan_bg')
		->select('pb.cancel_bg')
        ->from('tr_h3_md_penerimaan_pembayaran as pb')
        ->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
        ->join('ms_rek_md as rek', 'rek.id_rek_md = pb.id_rekening_md_bg')
		->where('pb.jenis_pembayaran', 'BG')
		->where('pb.id_penerimaan_pembayaran', $this->input->get('id_penerimaan_pembayaran'))
		->get()->row_array();

		$this->template($data);	
	}

	public function update(){
		$data = $this->input->post([
			'nama_bank_bg', 'nomor_bg', 'keterangan_bg'
		]);

		$this->db->trans_start();
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

	public function cancel(){
		$this->db->trans_start();
		$this->penerimaan_pembayaran->update([
			'canceled_bg_at' => date('Y-m-d H:i:s', time()),
			'canceled_bg_by' => $this->session->userdata('id_user'),
			'cancel_bg' => 1
		], $this->input->get(['id_penerimaan_pembayaran']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_userdata('pesan', 'Giro berhasil dibatalkan.');
			$this->session->set_userdata('tipe', 'info');
		}else{
			$this->session->set_userdata('pesan', 'Giro tidak berhasil dibatalkan');
			$this->session->set_userdata('tipe', 'danger');
		}
		redirect(
			base_url("h3/{$this->page}/detail?id_penerimaan_pembayaran={$this->input->get('id_penerimaan_pembayaran')}")
		);
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('nama_bank_bg', 'Nama Bank BG', 'required');
		$this->form_validation->set_rules('nomor_bg', 'Nomor BG', 'required');
		$this->form_validation->set_rules('keterangan_bg', 'Keterangan BG', 'required');

        if (!$this->form_validation->run())
        {
			$this->output->set_status_header(400);
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			]);
		}
    }
}