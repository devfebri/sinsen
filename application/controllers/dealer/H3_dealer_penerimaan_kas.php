<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_penerimaan_kas extends Honda_Controller {

	var $folder = "dealer";
	var $page   = "h3_dealer_penerimaan_kas";
	var $title  = "Penerimaan Kas (Receipt)";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('h3_dealer_penerimaan_kas_model', 'penerimaan_kas');
		$this->load->model('h3_dealer_penerimaan_kas_items_model', 'penerimaan_kas_items');
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";

		$this->template($data);	
	}

	public function add(){
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);	
	}

	public function save(){
		$this->validate();

		$master = array_merge($this->input->post([
			'kode_coa'
		]), [
			'id_penerimaan_kas' => $this->penerimaan_kas->generateID(),
			'id_dealer' => $this->m_admin->cari_dealer()
		]);

		$items = $this->getOnly(true, $this->input->post('items'), [
			'id_penerimaan_kas' => $this->penerimaan_kas->generateID()
		]);

		$this->db->trans_start();
		$this->penerimaan_kas->insert($master);
		$this->penerimaan_kas_items->insert_batch($items);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'payload' => $this->penerimaan_kas->find($master['id_penerimaan_kas'], 'id_penerimaan_kas')
			]);
		}else{
			$this->output->set_status_header(400);
			send_json([
				'error_type' => 'submit_error',
				'message' => 'Request Gagal.'
			]);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('kode_coa', 'COA', 'required');

        if (!$this->form_validation->run())
        {
            $this->output->set_status_header(400);
            send_json([
				'error_type' => 'validation_error',
				'payload' => $this->form_validation->error_array()
			]);
        }
	}
	
	public function detail(){
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['penerimaan_kas'] = $this->db
		->select('pk.*')
		->select('date_format(pk.created_at, "%d-%m-%Y") as created_at')
		->select('c.coa')
		->from('tr_h3_dealer_penerimaan_kas as pk')
		->join('ms_coa_dealer as c', 'c.kode_coa = pk.kode_coa')
		->where('pk.id_penerimaan_kas', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['items'] = $this->db
		->select('pki.*')
		->select('c.coa')
		->from('tr_h3_dealer_penerimaan_kas_items as pki')
		->join('ms_coa_dealer as c', 'c.kode_coa = pki.kode_coa')
		->where('pki.id_penerimaan_kas', $this->input->get('id'))
		->get()->result();

		$this->template($data);	
	}

	public function edit(){
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['penerimaan_kas'] = $this->db
		->select('pk.*')
		->select('date_format(pk.created_at, "%d-%m-%Y") as created_at')
		->select('c.coa')
		->from('tr_h3_dealer_penerimaan_kas as pk')
		->join('ms_coa_dealer as c', 'c.kode_coa = pk.kode_coa')
		->where('pk.id_penerimaan_kas', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['items'] = $this->db
		->select('pki.*')
		->select('c.coa')
		->from('tr_h3_dealer_penerimaan_kas_items as pki')
		->join('ms_coa_dealer as c', 'c.kode_coa = pki.kode_coa')
		->where('pki.id_penerimaan_kas', $this->input->get('id'))
		->get()->result();

		$this->template($data);	
	}

	public function update(){
		$this->validate();

		$master = $this->input->post(['kode_coa']);

		$items = $this->getOnly(true, $this->input->post('items'), $this->input->post(['id_penerimaan_kas']));

		$this->db->trans_start();
		$this->penerimaan_kas->update($master, $this->input->post(['id_penerimaan_kas']));
		$this->penerimaan_kas_items->update_batch($items, $this->input->post(['id_penerimaan_kas']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'payload' => $this->penerimaan_kas->get($this->input->post(['id_penerimaan_kas']))
			]);
		}else{
			$this->output->set_status_header(400);
			send_json([
				'error_type' => 'submit_error',
				'message' => 'Request Gagal.'
			]);
		}
	}
}