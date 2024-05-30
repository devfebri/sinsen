<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_fdo extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_fdo";
	protected $title  = "AHM FILE .FDO";

	public function __construct()
	{
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
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" OR $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('H3_md_fdo_model', 'fdo');
		$this->load->model('H3_md_fdo_parts_model', 'fdo_parts');
		$this->load->model('H3_md_fdo_ps_model', 'fdo_ps');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['fdo'] = $this->fdo->all();

		$this->template($data);
	}

	public function upload()
	{
		$data['set']     = "upload";
		$this->template($data);
	}

	public function inject()
	{
		$upload_path = "./uploads/AHM";
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = '*';
		$config['overwrite'] = true;

		$this->load->library('upload');
		$this->upload->initialize($config);

		if ($this->upload->do_upload('file')) {
			$data = $this->upload->data();
			$path = "$upload_path/{$data['file_name']}";
			$this->fdo->upload($path);

			send_json([
				'redirect_url' => base_url(sprintf('h3/%s', $this->page))
			]);
		}else{
			send_json([
				'message' => $this->upload->display_errors('', '')
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['fdo'] = $this->fdo->get($this->input->get(['invoice_number']), true);
		$data['fdo'] = $this->db
		->select('fdo.*')
		->select('date_format(fdo.dpp_due_date, "%d-%m-%Y") as dpp_due_date')
		->select('date_format(fdo.ppn_due_date, "%d-%m-%Y") as ppn_due_date')
		->from('tr_h3_md_fdo as fdo')
		->where('fdo.invoice_number', $this->input->get('invoice_number'))
		->get()->row();

		$data['parts'] = $this->db
		->select('fdop.nomor_packing_sheet')
		->select('fdop.id_part')
		->select('p.nama_part')
		->select('fdop.quantity')
		->select('fdop.price')
		->select('fdop.disc_campaign')
		->select('fdop.disc_insentif')
		->select('fdop.dpp')
		->select('fdop.ppn')
		->from('tr_h3_md_fdo_parts as fdop')
		->join('ms_part as p', 'p.id_part = fdop.id_part', 'left')
		->where($this->input->get(['invoice_number']))
		->order_by('fdop.invoice_sequence', 'asc')
		->get()->result();

		$this->template($data);
	}

	public function approve(){
		$this->db->trans_start();
		$this->fdo->update([
			'status' => 'Approved'
		], [
			'invoice_number' => $this->input->get('invoice_number')
		]);
		$this->fdo->create_ap($this->input->get('invoice_number'));
		$this->db->trans_complete();
		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Faktur berhasil di approve.');
			$this->session->set_flashdata('tipe', 'info');
			send_json(
				$this->fdo->find($this->input->get('invoice_number'), 'invoice_number')
			);
		}else{
			$this->session->set_flashdata('pesan', 'Faktur tidak berhasil di approve.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}		
	}
}
