<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_sim_part extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_sim_part";
    protected $title  = "Master SIM Part";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_sim_part_model', 'sim_part');
		$this->load->model('H3_md_ms_sim_part_item_model', 'sim_part_item');
		$this->load->model('H3_md_ms_sim_part_dealer_model', 'sim_part_dealer');
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

	public function get_dealers(){
		$data = $this->db
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('d.id_dealer')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->select('jp.jumlah_pit')
		->from('ms_h3_md_jumlah_pit as jp')
		->join('ms_dealer as d', 'd.id_dealer = jp.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
		->where('jp.jumlah_pit >=', $this->input->get('batas_bawah_jumlah_pit'))
		->where('jp.jumlah_pit <=', $this->input->get('batas_atas_jumlah_pit'))
		->get()->result();

		send_json($data);
	}

	public function save()
	{		
		$this->db->trans_start();
		$this->validate();
		
		if($this->input->post('kategori_sim_part')=='ue'){
			$this->form_validation->set_rules('batas_bawah_jumlah_ue', 'Batas Bawah Jumlah UE', 'required|numeric');
			$this->form_validation->set_rules('batas_atas_jumlah_ue', 'Batas Atas Jumlah UE', 'required|numeric|greater_than[0]');		

			if (!$this->form_validation->run()) {
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $this->form_validation->error_array()
				], 422);
			}
		}elseif($this->input->post('kategori_sim_part')=='pit'){
			$this->form_validation->set_rules('batas_bawah_jumlah_pit', 'Batas Bawah Jumlah Pit', 'required|numeric');
			$this->form_validation->set_rules('batas_atas_jumlah_pit', 'Batas Atas Jumlah Pit', 'required|numeric');		

			if (!$this->form_validation->run()) {
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $this->form_validation->error_array()
				], 422);
			}
		}
		$data = array_merge($this->input->post([
            'tanggal_mulai_berlaku', 'batas_bawah_jumlah_pit', 'batas_atas_jumlah_pit', 'active', 'batas_bawah_jumlah_ue', 'batas_atas_jumlah_ue', 'kategori_sim_part'
		]), [
			// 'id_sim_part' => $this->sim_part->generate_id(
			// 	$this->input->post('batas_bawah_jumlah_pit'), 
			// 	$this->input->post('batas_atas_jumlah_pit')
			// )
			'id_sim_part' => $this->sim_part->generate_id_new()
		]);
		
		$this->sim_part->insert($data);
		$parts = $this->getOnly([
			'id_part', 'qty_sim_part'
		], $this->input->post('parts'), [
			'id_sim_part' => $data['id_sim_part']
		]);
		if(count($parts) > 0){
			$this->sim_part_item->insert_batch($parts);
		}
		$dealers = $this->getOnly([
			'id_dealer','jumlah_ue','target_ue'
		], $this->input->post('dealers'), [
			'id_sim_part' => $data['id_sim_part']
		]);
		if(count($dealers) > 0){
			$this->sim_part_dealer->insert_batch($dealers);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$sim_part = $this->sim_part->find($data['id_sim_part'], 'id_sim_part');
			send_json($sim_part);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['sim_part'] = $sim_part= $this->sim_part->find($this->input->get('id_sim_part'), 'id_sim_part');

		$kategori_sim_part = $sim_part->kategori_sim_part;
		$batas_atas_jumlah_pit = $sim_part->batas_atas_jumlah_pit;
		$batas_bawah_jumlah_pit = $sim_part->batas_bawah_jumlah_pit;
		if($kategori_sim_part=='ue'){
			$data['dealers'] = $this->db
			->select('md.nama_dealer')
			->select('md.kode_dealer_md')
			->select('md.alamat')
			->select('mkab.kabupaten as kabupaten')
			->select('spd.jumlah_ue')
			->select('spd.target_ue')
			->select('md.id_dealer')
			->from('ms_h3_md_sim_part_dealer as spd')
			->join('ms_dealer md','md.id_dealer=spd.id_dealer')
			->join('ms_kelurahan mk','md.id_kelurahan=mk.id_kelurahan')
			->join('ms_kecamatan mkc','mkc.id_kecamatan=mk.id_kecamatan')
			->join('ms_kabupaten mkab','mkab.id_kabupaten=mkc.id_kabupaten')
			->where('spd.id_sim_part', $this->input->get('id_sim_part'))
			->get()->result();
		}else{
			$data['dealers'] = $this->db
					->select('d.kode_dealer_md')
					->select('d.nama_dealer')
					->select('d.alamat')
					->select('kab.kabupaten')
					->select('kab.id_kabupaten')
					->select('jp.jumlah_pit')
					->from('ms_h3_md_jumlah_pit as jp')
					->join('ms_dealer as d', 'd.id_dealer = jp.id_dealer')
					->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
					->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
					->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
					->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
					->where('jp.jumlah_pit >=', $batas_bawah_jumlah_pit)
					->where('jp.jumlah_pit <=', $batas_atas_jumlah_pit)
					->get()->result();

		}

		$data['parts'] = $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.kelompok_part')
		->select('spi.qty_sim_part')
		->select('
            concat(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            ) as het
        ')
        ->select('p.status')
		->from('ms_h3_md_sim_part_item as spi')
		->join('ms_part as p', 'p.id_part = spi.id_part')
		->where('spi.id_sim_part', $this->input->get('id_sim_part'))
		->get()->result();
		

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['sim_part'] = $sim_part= $this->sim_part->find($this->input->get('id_sim_part'), 'id_sim_part');
		$kategori_sim_part = $sim_part->kategori_sim_part;
		$batas_atas_jumlah_pit = $sim_part->batas_atas_jumlah_pit;
		$batas_bawah_jumlah_pit = $sim_part->batas_bawah_jumlah_pit;
		if($kategori_sim_part=='ue'){
			$data['dealers'] = $this->db
			->select('md.nama_dealer')
			->select('md.kode_dealer_md')
			->select('md.alamat')
			->select('"-" as kabupaten')
			->select('spd.jumlah_ue')
			->select('spd.target_ue')
			->select('md.id_dealer')
			->from('ms_h3_md_sim_part_dealer as spd')
			->join('ms_dealer md','md.id_dealer=spd.id_dealer')
			->join('ms_kelurahan mk','md.id_kelurahan=mk.id_kelurahan')
			->join('ms_kecamatan mkc','mkc.id_kecamatan=mk.id_kecamatan')
			->join('ms_kabupaten mkab','mkab.id_kabupaten=mkc.id_kabupaten')
			->where('spd.id_sim_part', $this->input->get('id_sim_part'))
			->get()->result();
		}else{
			$data['dealers'] = $this->db
					->select('d.kode_dealer_md')
					->select('d.nama_dealer')
					->select('d.alamat')
					->select('kab.kabupaten')
					->select('kab.id_kabupaten')
					->select('jp.jumlah_pit')
					->from('ms_h3_md_jumlah_pit as jp')
					->join('ms_dealer as d', 'd.id_dealer = jp.id_dealer')
					->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
					->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
					->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
					->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
					->where('jp.jumlah_pit >=', $batas_bawah_jumlah_pit)
					->where('jp.jumlah_pit <=', $batas_atas_jumlah_pit)
					->get()->result();

		}
		$data['parts'] = $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.kelompok_part')
		->select('spi.qty_sim_part')
		->select('
            concat(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            ) as het
        ')
        ->select('p.status')
		->from('ms_h3_md_sim_part_item as spi')
		->join('ms_part as p', 'p.id_part = spi.id_part')
		->where('spi.id_sim_part', $this->input->get('id_sim_part'))
		->get()->result();

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		if($this->input->post('kategori_sim_part')=='ue'){
			$this->form_validation->set_rules('batas_bawah_jumlah_ue', 'Batas Bawah Jumlah UE', 'required|numeric');
			$this->form_validation->set_rules('batas_atas_jumlah_ue', 'Batas Atas Jumlah UE', 'required|numeric|greater_than[0]');		

			if (!$this->form_validation->run()) {
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $this->form_validation->error_array()
				], 422);
			}
		}elseif($this->input->post('kategori_sim_part')=='pit'){
			$this->form_validation->set_rules('batas_bawah_jumlah_pit', 'Batas Bawah Jumlah Pit', 'required|numeric');
			$this->form_validation->set_rules('batas_atas_jumlah_pit', 'Batas Atas Jumlah Pit', 'required|numeric');		

			if (!$this->form_validation->run()) {
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $this->form_validation->error_array()
				], 422);
			}
		}
		$data = $this->input->post([
            'tanggal_mulai_berlaku', 'kategori_sim_part', 'batas_bawah_jumlah_pit', 'batas_atas_jumlah_pit', 'active', 'batas_bawah_jumlah_ue', 'batas_atas_jumlah_ue'
		]);
		
		$this->sim_part->update($data, $this->input->post(['id_sim_part']));
		$parts = $this->getOnly([
			'id_part', 'qty_sim_part'
		], $this->input->post('parts'), $this->input->post(['id_sim_part']));
		$this->sim_part_item->delete($this->input->post('id_sim_part'), 'id_sim_part');
		if(count($parts) > 0){
			$this->sim_part_item->insert_batch($parts);
		}
		$this->sim_part_dealer->delete($this->input->post('id_sim_part'), 'id_sim_part');
		$dealers = $this->getOnly([
			'id_dealer','jumlah_ue','target_ue'
		], $this->input->post('dealers'), $this->input->post(['id_sim_part']));
		// if(count($dealers) > 0){
			$this->sim_part_dealer->insert_batch($dealers);
		// }
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$sim_part = $this->sim_part->find($this->input->post('id_sim_part'), 'id_sim_part');
			send_json($sim_part);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		// $this->form_validation->set_rules('batas_bawah_jumlah_pit', 'Batas Bawah Jumlah Pit', 'required|numeric');
		// $this->form_validation->set_rules('batas_atas_jumlah_pit', 'Batas Atas Jumlah Pit', 'required|numeric');
		// $this->form_validation->set_rules('batas_bawah_jumlah_ue', 'Batas Bawah Jumlah UE', 'required|numeric');
		// $this->form_validation->set_rules('batas_atas_jumlah_ue', 'Batas Atas Jumlah UE', 'required|numeric');
		
		$this->form_validation->set_rules('kategori_sim_part', 'Kategori SIM Part', 'required');
		$this->form_validation->set_rules('tanggal_mulai_berlaku', 'Tanggal Mulai Berlaku', 'required');

        if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }
}