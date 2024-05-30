<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_target_salesman extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_target_salesman";
    protected $title  = "Master Target Salesman";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_target_salesman_model', 'target_salesman');		
		$this->load->model('H3_md_target_salesman_parts_model', 'target_salesman_parts');		
		$this->load->model('H3_md_target_salesman_parts_items_model', 'target_salesman_parts_items');		
		$this->load->model('H3_md_target_salesman_oil_model', 'target_salesman_oil');		
		$this->load->model('H3_md_target_salesman_acc_model', 'target_salesman_acc');		
		$this->load->model('H3_md_target_salesman_apparel_model', 'target_salesman_apparel');		
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

	public function save()
	{		
		$this->db->trans_start();
		$this->validate();

		$data = $this->input->post([
			'id_salesman', 'start_date', 'end_date', 'jenis_target_salesman', 'target_salesman_global',
			'target_salesman_dus_global', 'target_salesman_channel','target_salesman_dus_channel'
		]);
		$this->target_salesman->insert(
			$this->clean_data($data)
		);
		$id_target_salesman = $this->db->insert_id();
		
		$target_salesman_parts = $this->input->post('target_salesman_parts');
		if(count($target_salesman_parts) > 0){
			foreach ($target_salesman_parts as $each) {
				$data = [
					'id_target_salesman' => $id_target_salesman,
					'id_dealer' => $each['id_dealer'],
					'target_part' => $each['target_part'],
					'global' => $each['global'],
				];

				$this->target_salesman_parts->insert($data);
				$id_target_salesman_parts = $this->db->insert_id();

				if(isset($each['items'])){
					foreach ($each['items'] as $item) {
						$item['id_target_salesman_parts'] = $id_target_salesman_parts;
						$this->target_salesman_parts_items->insert($item);
					}
				}
			}
		}

		$target_salesman_oils = $this->input->post('target_salesman_oils');
		if(count($target_salesman_oils) > 0){
			$data = $this->getOnly([
				'id_dealer', 'amount_engine_oil', 'botol_engine_oil', 
				'amount_gear_oil', 'botol_gear_oil', 'total_amount', 'total_botol'
			], $target_salesman_oils, [
				'id_target_salesman' => $id_target_salesman
			]);

			$this->target_salesman_oil->insert_batch($data);
		}

		$target_salesman_acc = $this->input->post('target_salesman_acc');
		if(count($target_salesman_acc) > 0){
			$data = $this->getOnly([
				'id_dealer', 'target_acc'
			], $target_salesman_acc, [
				'id_target_salesman' => $id_target_salesman
			]);
			$this->target_salesman_acc->insert_batch($data);
		}

		$target_salesman_apparel = $this->input->post('target_salesman_apparel');
		if(count($target_salesman_apparel) > 0){
			$data = $this->getOnly([
				'id_dealer', 'target_apparel'
			], $target_salesman_apparel, [
				'id_target_salesman' => $id_target_salesman
			]);
			$this->target_salesman_apparel->insert_batch($data);
		}

		$this->db->trans_complete();

		if($this->db->trans_status()){
			$result = $this->target_salesman->find($id_target_salesman);
			send_json($result);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['target_salesman'] = $this->db
		->select('ts.*')
		->select('k.nama_lengkap as nama_salesman')
		->select('k.npk as nik_salesman')
		->from('ms_h3_md_target_salesman as ts')
		->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman')
		->where('ts.id', $this->input->get('id'))
		->get()->row();

		$target_salesman_parts = $this->db
		->select('tsp.id')
		->select('tsp.id_dealer')
		->select('tsp.target_part')
		->select('tsp.global')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_parts as tsp')
		->join('ms_dealer as d', 'd.id_dealer = tsp.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tsp.id_target_salesman', $this->input->get('id'))
		->get()->result_array();

		$data['target_salesman_parts'] = [];
		foreach ($target_salesman_parts as $each) {
			$each['items'] = $this->db
			->select('tspi.id_kelompok_part')
			->select('tspi.target_part_items')
			->from('ms_h3_md_target_salesman_parts_items as tspi')
			->where('tspi.id_target_salesman_parts',$each['id'])
			->get()->result_array();

			$data['target_salesman_parts'][] = $each;
		}

		$data['target_salesman_oils'] = $this->db
		->select('tso.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_oil as tso')
		->join('ms_dealer as d', 'd.id_dealer = tso.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tso.id_target_salesman', $this->input->get('id'))
		->get()->result();

		$data['target_salesman_acc'] = $this->db
		->select('tsa.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_acc as tsa')
		->join('ms_dealer as d', 'd.id_dealer = tsa.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tsa.id_target_salesman', $this->input->get('id'))
		->get()->result_array();

		$data['target_salesman_apparel'] = $this->db
		->select('tsa.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_apparel as tsa')
		->join('ms_dealer as d', 'd.id_dealer = tsa.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tsa.id_target_salesman', $this->input->get('id'))
		->get()->result_array();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['target_salesman'] = $this->db
		->select('ts.*')
		->select('k.nama_lengkap as nama_salesman')
		->select('k.npk as nik_salesman')
		->from('ms_h3_md_target_salesman as ts')
		->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman')
		->where('ts.id', $this->input->get('id'))
		->get()->row();

		$target_salesman_parts = $this->db
		->select('tsp.id')
		->select('tsp.id_dealer')
		->select('tsp.global')
		->select('tsp.target_part')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_parts as tsp')
		->join('ms_dealer as d', 'd.id_dealer = tsp.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tsp.id_target_salesman', $this->input->get('id'))
		->get()->result_array();

		$data['target_salesman_parts'] = [];
		foreach ($target_salesman_parts as $each) {
			$each['items'] = $this->db
			->select('tspi.id_kelompok_part')
			->select('tspi.target_part_items')
			->from('ms_h3_md_target_salesman_parts_items as tspi')
			->where('tspi.id_target_salesman_parts',$each['id'])
			->get()->result_array();

			$data['target_salesman_parts'][] = $each;
		}

		$data['target_salesman_oils'] = $this->db
		->select('tso.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_oil as tso')
		->join('ms_dealer as d', 'd.id_dealer = tso.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tso.id_target_salesman', $this->input->get('id'))
		->get()->result();

		$data['target_salesman_acc'] = $this->db
		->select('tsa.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_acc as tsa')
		->join('ms_dealer as d', 'd.id_dealer = tsa.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tsa.id_target_salesman', $this->input->get('id'))
		->get()->result_array();

		$data['target_salesman_apparel'] = $this->db
		->select('tsa.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('kab.kabupaten')
		->select('kab.id_kabupaten')
		->from('ms_h3_md_target_salesman_apparel as tsa')
		->join('ms_dealer as d', 'd.id_dealer = tsa.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
		->where('tsa.id_target_salesman', $this->input->get('id'))
		->get()->result_array();

		$this->template($data);									
	}

	public function update()
	{		
		$this->validate();

		$this->db->trans_start();
		$data = $this->input->post([
			'id_salesman', 'start_date', 'end_date', 'jenis_target_salesman', 'target_salesman_global',
			'target_salesman_dus_global', 'target_salesman_channel','target_salesman_dus_channel'
		]);
		$data = $this->clean_data($data);
		$this->target_salesman->update($data, $this->input->post(['id']));
		$id_target_salesman = $this->input->post('id');
		
		$ids_target_salesman_parts = $this->db
		->select('tsp.id')
		->from('ms_h3_md_target_salesman_parts as tsp')
		->where('tsp.id_target_salesman', $this->input->post('id'))
		->get()->result_array();
		$ids_target_salesman_parts = array_map(function($data){
			return $data['id'];
		}, $ids_target_salesman_parts);

		if(count($ids_target_salesman_parts) > 0){
			$this->db->where_in('id_target_salesman_parts', $ids_target_salesman_parts)->delete('ms_h3_md_target_salesman_parts_items');
		}
		$this->target_salesman_parts->delete($id_target_salesman, 'id_target_salesman');
		$target_salesman_parts = $this->input->post('target_salesman_parts');
		if(count($target_salesman_parts) > 0){
			foreach ($target_salesman_parts as $each) {
				$data = [
					'id_target_salesman' => $id_target_salesman,
					'id_dealer' => $each['id_dealer'],
					'target_part' => $each['target_part'],
					'global' => $each['global'],
				];

				$this->target_salesman_parts->insert($data);
				$id_target_salesman_parts = $this->db->insert_id();

				$this->target_salesman_parts_items->delete($id_target_salesman_parts, 'id_target_salesman_parts');
				if(isset($each['items'])){
					foreach ($each['items'] as $item) {
						$item['id_target_salesman_parts'] = $id_target_salesman_parts;
						$this->target_salesman_parts_items->insert($item);
					}
				}
			}
		}

		$this->target_salesman_oil->delete($id_target_salesman, 'id_target_salesman');
		$target_salesman_oils = $this->input->post('target_salesman_oils');
		if(count($target_salesman_oils) > 0){
			$data = $this->getOnly([
				'id_dealer', 'amount_engine_oil', 'botol_engine_oil', 
				'amount_gear_oil', 'botol_gear_oil', 'total_amount', 'total_botol'
			], $target_salesman_oils, [
				'id_target_salesman' => $id_target_salesman
			]);

			$this->target_salesman_oil->insert_batch($data);
		}

		$this->target_salesman_acc->delete($id_target_salesman, 'id_target_salesman');
		$target_salesman_acc = $this->input->post('target_salesman_acc');
		if(count($target_salesman_acc) > 0){
			$data = $this->getOnly([
				'id_dealer', 'target_acc'
			], $target_salesman_acc, [
				'id_target_salesman' => $id_target_salesman
			]);
			$this->target_salesman_acc->insert_batch($data);
		}

		$this->target_salesman_apparel->delete($id_target_salesman, 'id_target_salesman');
		$target_salesman_apparel = $this->input->post('target_salesman_apparel');
		if(count($target_salesman_apparel) > 0){
			$data = $this->getOnly([
				'id_dealer', 'target_apparel'
			], $target_salesman_apparel, [
				'id_target_salesman' => $id_target_salesman
			]);
			$this->target_salesman_apparel->insert_batch($data);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$result = $this->target_salesman->find($this->input->post('id'));
			send_json($result);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_salesman', 'Salesman', 'required');
		$this->form_validation->set_rules('start_date', 'Periode', 'required');
		$this->form_validation->set_rules('jenis_target_salesman', 'Jenis Target Salesman', 'required');

		$data = $this->input->post([
			'id_salesman', 'start_date', 'end_date', 'jenis_target_salesman', 'target_salesman_global',
			'target_salesman_dus_global', 'target_salesman_channel','target_salesman_dus_channel'
		]);

		if($this->uri->segment(3) == 'save'){
			//Tambah Validasi Jika salesman, target dan periode yang sama pernah atau tidak diinput
			$cek_salesman = $this->db->select('ts.id, k.nama_lengkap')
					->from('ms_h3_md_target_salesman as ts')
					->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman')
					->where('ts.jenis_target_salesman', $data['jenis_target_salesman'])
					->where('ts.start_date', $data['start_date'])
					->where('ts.end_date', $data['end_date'])
					->where('ts.id_salesman', $data['id_salesman'])
					->get()->row_array();
			if(!empty($cek_salesman)){
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Salesman '. $cek_salesman['nama_lengkap'] . ' telah terdaftar pada periode yang sama '
				], 422);
			}
		}
		//Cek apakah dealer telah ada data target nya
		if($data['jenis_target_salesman'] =='Parts'){
			$target_salesman_parts = $this->input->post('target_salesman_parts');
			foreach ($target_salesman_parts as $each) {
				$cek_data_salesman = $this->db->select('tsp.id, md.nama_dealer, k.nama_lengkap')
				->from('ms_h3_md_target_salesman as ts')
				->join('ms_h3_md_target_salesman_parts as tsp','ts.id=tsp.id_target_salesman')
				->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman')
				->join('ms_dealer as md', 'md.id_dealer =tsp.id_dealer')
				->where('ts.jenis_target_salesman', $data['jenis_target_salesman'])
				->where('ts.start_date', $data['start_date'])
				->where('ts.end_date', $data['end_date'])
				->where('tsp.id_dealer', $each['id_dealer'])
				->where_not_in('ts.id_salesman', $data['id_salesman'])
				->get()->row_array();
			}
			if(!empty($cek_data_salesman)){
				send_json([
                    'error_type' => 'validation_error',
                    'message' => 'Dealer '. $cek_data_salesman['nama_dealer'] . ' telah terdaftar di target salesman : '. $cek_data_salesman['nama_lengkap']
                ], 422);
			}
		}elseif($data['jenis_target_salesman'] =='Oil'){
			$target_salesman_oils = $this->input->post('target_salesman_oils');
			foreach ($target_salesman_oils as $each) {
			$cek_data_salesman = $this->db->select('tso.id, md.nama_dealer, k.nama_lengkap')
				->from('ms_h3_md_target_salesman as ts')
				->join('ms_h3_md_target_salesman_oil as tso','ts.id=tso.id_target_salesman')
				->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman')
				->join('ms_dealer as md', 'md.id_dealer =tso.id_dealer')
				->where('ts.jenis_target_salesman', $data['jenis_target_salesman'])
				->where('ts.start_date', $data['start_date'])
				->where('ts.end_date', $data['end_date'])
				->where('tso.id_dealer', $each['id_dealer'])
				->where_not_in('ts.id_salesman', $data['id_salesman'])
				->get()->row_array();
			}
			if(!empty($cek_data_salesman)){
				send_json([
                    'error_type' => 'validation_error',
                    'message' => 'Dealer '. $cek_data_salesman['nama_dealer'] . ' telah terdaftar di target salesman : '. $cek_data_salesman['nama_lengkap']
                ], 422);
			}
		}elseif($data['jenis_target_salesman'] =='Acc'){
			$target_salesman_acc = $this->input->post('target_salesman_acc');
			foreach ($target_salesman_acc as $each) {
			$cek_data_salesman = $this->db->select('tca.id, md.nama_dealer, k.nama_lengkap')
				->from('ms_h3_md_target_salesman as ts')
				->join('ms_h3_md_target_salesman_acc as tca','ts.id=tca.id_target_salesman')
				->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman')
				->join('ms_dealer as md', 'md.id_dealer =tca.id_dealer')
				->where('ts.jenis_target_salesman', $data['jenis_target_salesman'])
				->where('ts.start_date', $data['start_date'])
				->where('ts.end_date', $data['end_date'])
				->where('tca.id_dealer', $each['id_dealer'])
				->where_not_in('ts.id_salesman', $data['id_salesman'])
				->get()->row_array();
			}
			if(!empty($cek_data_salesman)){
				send_json([
                    'error_type' => 'validation_error',
                    'message' => 'Dealer '. $cek_data_salesman['nama_dealer'] . ' telah terdaftar di target salesman : '. $cek_data_salesman['nama_lengkap']
                ], 422);
			}
		}elseif($data['jenis_target_salesman'] =='Apparel'){
			$target_salesman_apparel = $this->input->post('target_salesman_apparel');
			foreach ($target_salesman_apparel as $each) {
			$cek_data_salesman = $this->db->select('tsa.id, md.nama_dealer, k.nama_lengkap')
				->from('ms_h3_md_target_salesman as ts')
				->join('ms_h3_md_target_salesman_apparel as tsa','ts.id=tsa.id_target_salesman')
				->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman')
				->join('ms_dealer as md', 'md.id_dealer =tsa.id_dealer')
				->where('ts.jenis_target_salesman', $data['jenis_target_salesman'])
				->where('ts.start_date', $data['start_date'])
				->where('ts.end_date', $data['end_date'])
				->where('tsa.id_dealer', $each['id_dealer'])
				->where_not_in('ts.id_salesman', $data['id_salesman'])
				->get()->row_array();
			}
			if(!empty($cek_data_salesman)){
				send_json([
                    'error_type' => 'validation_error',
                    'message' => 'Dealer '. $cek_data_salesman['nama_dealer'] . ' telah terdaftar di target salesman : '. $cek_data_salesman['nama_lengkap']
                ], 422);
			}
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