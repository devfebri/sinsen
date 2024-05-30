<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_diskon_part_tertentu extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_diskon_part_tertentu";
    protected $title  = "Diskon Part Tertentu";

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
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$this->load->model('h3_md_diskon_part_tertentu_items_model', 'diskon_part_tertentu_items');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save(){
		$this->validate();

		$diskon_part_tertentu = $this->input->post(['id_part_int', 'id_part', 'active', 'tipe_diskon', 
		'diskon_fixed', 'diskon_reguler', 'diskon_hotline', 'diskon_urgent', 'diskon_other']);

		$this->db->trans_start();
		$this->diskon_part_tertentu->insert($diskon_part_tertentu);

		$id_diskon_part_tertentu = $this->db->insert_id();

		if (count($this->input->post('items')) > 0) {
			$diskon_part_tertentu_items_data = $this->getOnly(true, $this->input->post('items'), [
				'id_diskon_part_tertentu' => $id_diskon_part_tertentu
			]);
			$this->diskon_part_tertentu_items->insert_batch($diskon_part_tertentu_items_data);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json(
				$this->diskon_part_tertentu->find($id_diskon_part_tertentu, 'id')
			);
		}else{
			send_json(['message' => 'Tidak berhasil simpan data'], 422);
		}
	}

	public function detail(){

		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['diskon_part_tertentu'] = $this->db
		->select('dpt.*')
		->select('p.nama_part')
		->select('p.harga_dealer_user')
		->select('p.kelompok_part')
		->from('ms_h3_md_diskon_part_tertentu as dpt')
		->join('ms_part as p', 'p.id_part = dpt.id_part')
		->where('dpt.id', $this->input->get('id'))
		->get()->row();

		$data['items'] = $this->db
		->select('dpti.*, d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_diskon_part_tertentu_items as dpti')
		->join('ms_dealer as d', 'd.id_dealer = dpti.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('dpti.id_diskon_part_tertentu', $this->input->get('id'))
		->get()->result();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['diskon_part_tertentu'] = $this->db
		->select('dpt.*')
		->select('p.nama_part')
		->select('p.harga_dealer_user')
		->select('p.kelompok_part')
		->from('ms_h3_md_diskon_part_tertentu as dpt')
		->join('ms_part as p', 'p.id_part = dpt.id_part')
		->where('dpt.id', $this->input->get('id'))
		->get()->row();

		$data['items'] = $this->db
		->select('dpti.*, d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_diskon_part_tertentu_items as dpti')
		->join('ms_dealer as d', 'd.id_dealer = dpti.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('dpti.id_diskon_part_tertentu', $this->input->get('id'))
		->get()->result();

		$this->template($data);
	}

	public function update(){
		$this->validate();
		
		$this->db->trans_start();
		$diskon_part_tertentu = $this->input->post([
			'id_part_int','id_part', 'active', 'tipe_diskon', 
			'diskon_fixed', 'diskon_reguler', 'diskon_hotline', 
			'diskon_urgent', 'diskon_other'
		]);
		$this->diskon_part_tertentu->update($diskon_part_tertentu, [
			'id' => $this->input->post('id')
		]);

		$diskon_part_tertentu_items_data = $this->getOnly([
			'id_dealer', 'tipe_diskon', 'diskon_fixed', 'diskon_reguler',
			'diskon_hotline', 'diskon_urgent', 'diskon_other',
		], $this->input->post('items'), [
			'id_diskon_part_tertentu' => $this->input->post('id')
		]);
		$this->diskon_part_tertentu_items->update_batch($diskon_part_tertentu_items_data, [
			'id_diskon_part_tertentu' => $this->input->post('id')
		]);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json(
				$this->diskon_part_tertentu->find($this->input->post('id'))
			);
		}else{
			send_json(['message' => 'Tidak berhasil update data'], 422);
		}
	}

	public function delete(){
		$this->db->trans_start();
		$this->diskon_part_tertentu->delete($this->input->get('id'));
		$this->diskon_part_tertentu_items->delete($this->input->get('id'), 'id_diskon_part_tertentu');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Diskon Part Tertentu berhasil dihapus');
			$this->session->set_flashdata('tipe', 'info');			
		}else{
			$this->session->set_flashdata('pesan', 'Diskon Part Tertentu tidak berhasil dihapus');
			$this->session->set_flashdata('tipe', 'danger');
		}
		redirect(base_url('h3/h3_md_diskon_part_tertentu'), 'refresh');
	}

	public function get_parts_diskon(){
		$list_diskon = [];
		foreach ($this->input->get('id_part') as $part) {
			$list_diskon[] = $this->diskon_part_tertentu->get_diskon($part, $this->input->get('id_dealer'), $this->input->get('po_type'), $this->input->get('produk'));
		}

		send_json($list_diskon);
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_part', 'Part', 'required');
        $this->form_validation->set_rules('tipe_diskon', 'Tipe Diskon', 'required');
        $this->form_validation->set_rules('diskon_fixed', 'Diskon Fixed', 'required');
        $this->form_validation->set_rules('diskon_reguler', 'Diskon Reguler', 'required');
        $this->form_validation->set_rules('diskon_hotline', 'Diskon Hotline', 'required');
        $this->form_validation->set_rules('diskon_urgent', 'Diskon Urgent', 'required');
        $this->form_validation->set_rules('diskon_other', 'Diskon Other', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
    }

	public function set_id_part_int(){
		$this->db
		->select('dpt.id_part')
		->select('p.id_part_int')
		->from('ms_h3_md_diskon_part_tertentu as dpt')
		->join('ms_part as p', 'p.id_part = dpt.id_part')
		->where('dpt.id_part_int', null);

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
			->set('id_part_int', $row['id_part_int'])
			->where('id_part', $row['id_part'])
			->update('ms_h3_md_diskon_part_tertentu');
		}
	}
}