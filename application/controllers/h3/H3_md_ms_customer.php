<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_customer extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_customer";
    protected $title  = "Master Customer";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('dealer_model', 'dealer');		
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
			'h1', 'h2', 'h3', 'pic', 'pimpinan', 'email',
            'kode_dealer_md','nama_dealer','no_telp','alamat','id_kelurahan',
            'top_part','top_oli','npwp','pemilik', 'pkp', 'tanggal_kerjasama',
            'tipe_diskon','diskon_fixed_order','diskon_reguler','diskon_hotline','diskon_urgent','kode_dealer_ahm','status_dealer','kode_dealer_ahm_link',
            'jumlah_ruko','active', 'status_bangunan', 'luas_bangunan', 'tipe_plafon_h3', 'nama_bank_h3', 'atas_nama_bank_h3', 'no_rekening_h3', 'jenis_dealer', 'grouping_dealer'
		]);
		$data = $this->clean_data($data);

		$config['upload_path'] = './assets/panel/images/';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('foto_ruko')){
			$errors = [
				'foto_ruko' => $this->upload->display_errors()
			];
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			], 422);
        }
        else{
            $data['foto_ruko'] = $this->upload->data()['file_name'];
		}
		
		if (!$this->upload->do_upload('foto_pemilik')){
			$errors = [
				'foto_pemilik' => $this->upload->display_errors()
			];
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			], 422);
        }
        else{
            $data['foto_pemilik'] = $this->upload->data()['file_name'];
		}
		
		$this->dealer->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		$dealer = (array) $this->dealer->find($id, 'id_dealer');
		if($this->db->trans_status() AND $dealer != null){
			send_json([
				'message' => 'Berhasil membuat customer',
				'payload' => $dealer,
				'redirect_url' => base_url('h3/h3_md_ms_customer/detail?id_dealer=' . $dealer['id_dealer'])
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil membuat customer',
			], 422);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['customer'] = $this->db
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.kode_dealer_ahm')
		->select('d.kode_dealer_ahm_link')
		->select('d.no_telp')
		->select('d.alamat')
		->select('d.top_part')
		->select('d.top_oli')
		->select('d.npwp')
		->select('d.pemilik')
		->select('d.pic')
		->select('d.pkp')
		->select('d.pimpinan')
		->select('d.email')
		->select('d.tipe_diskon')
		->select('d.diskon_fixed_order')
		->select('d.diskon_reguler')
		->select('d.diskon_hotline')
		->select('d.diskon_urgent')
		->select('d.status_dealer')
		->select('d.status_bangunan')
		->select('d.luas_bangunan')
		->select('d.jumlah_ruko')
		->select('d.active')
		->select('d.jenis_dealer')
		->select('d.grouping_dealer')
		->select('d.tipe_plafon_h3')
		->select('d.tanggal_kerjasama')
		->select('d.foto_ruko as uploaded_foto_ruko')
		->select('d.foto_pemilik as uploaded_foto_pemilik')
		->select('kelurahan.kelurahan')
        ->select('kelurahan.id_kelurahan')
        ->select('kecamatan.kecamatan')
        ->select('kecamatan.id_kecamatan')
        ->select('kabupaten.kabupaten')
        ->select('kabupaten.id_kabupaten')
        ->select('provinsi.provinsi')
        ->select('provinsi.id_provinsi')
        ->select('d.nama_bank_h3')
        ->select('d.atas_nama_bank_h3')
        ->select('d.no_rekening_h3')
		->from('ms_dealer as d')
        ->join('ms_kelurahan as kelurahan', 'kelurahan.id_kelurahan = d.id_kelurahan')
        ->join('ms_kecamatan as kecamatan', 'kecamatan.id_kecamatan = kelurahan.id_kecamatan')
        ->join('ms_kabupaten as kabupaten', 'kabupaten.id_kabupaten = kecamatan.id_kabupaten')
        ->join('ms_provinsi as provinsi', 'provinsi.id_provinsi = kabupaten.id_provinsi')
		->where('d.id_dealer', $this->input->get('id_dealer'))
		->get()->row();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['customer'] = $this->db
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.kode_dealer_ahm')
		->select('d.kode_dealer_ahm_link')
		->select('d.no_telp')
		->select('d.alamat')
		->select('d.top_part')
		->select('d.top_oli')
		->select('d.npwp')
		->select('d.pemilik')
		->select('d.pic')
		->select('d.pkp')
		->select('d.pimpinan')
		->select('d.email')
		->select('d.tipe_diskon')
		->select('d.diskon_fixed_order')
		->select('d.diskon_reguler')
		->select('d.diskon_hotline')
		->select('d.diskon_urgent')
		->select('d.status_dealer')
		->select('d.status_bangunan')
		->select('d.luas_bangunan')
		->select('d.jumlah_ruko')
		->select('d.active')
		->select('d.jenis_dealer')
		->select('d.grouping_dealer')
		->select('d.tipe_plafon_h3')
		->select('d.tanggal_kerjasama')
		->select('d.foto_ruko as uploaded_foto_ruko')
		->select('d.foto_pemilik as uploaded_foto_pemilik')
		->select('kelurahan.kelurahan')
        ->select('kelurahan.id_kelurahan')
        ->select('kecamatan.kecamatan')
        ->select('kecamatan.id_kecamatan')
        ->select('kabupaten.kabupaten')
        ->select('kabupaten.id_kabupaten')
        ->select('provinsi.provinsi')
        ->select('provinsi.id_provinsi')
		->select('d.nama_bank_h3')
        ->select('d.atas_nama_bank_h3')
        ->select('d.no_rekening_h3')
		->from('ms_dealer as d')
        ->join('ms_kelurahan as kelurahan', 'kelurahan.id_kelurahan = d.id_kelurahan')
        ->join('ms_kecamatan as kecamatan', 'kecamatan.id_kecamatan = kelurahan.id_kecamatan')
        ->join('ms_kabupaten as kabupaten', 'kabupaten.id_kabupaten = kecamatan.id_kabupaten')
        ->join('ms_provinsi as provinsi', 'provinsi.id_provinsi = kabupaten.id_provinsi')
		->where('d.id_dealer', $this->input->get('id_dealer'))
		->get()->row();

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'h1', 'h2', 'h3', 'pic', 'pimpinan', 'email',
            'kode_dealer_md','nama_dealer','no_telp','alamat','id_kelurahan',
            'top_part','top_oli','npwp','pemilik','pkp', 'tanggal_kerjasama',
            'tipe_diskon','diskon_fixed_order','diskon_reguler','diskon_hotline','diskon_urgent','kode_dealer_ahm','status_dealer','kode_dealer_ahm_link',
            'jumlah_ruko','active', 'status_bangunan', 'luas_bangunan', 'tipe_plafon_h3', 'nama_bank_h3', 'atas_nama_bank_h3', 'no_rekening_h3', 'jenis_dealer', 'grouping_dealer'
		]);

		$config['upload_path'] = './assets/panel/images/';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if(!empty($_FILES['foto_ruko'])){
			if (!$this->upload->do_upload('foto_ruko')){
				$errors = [
					'foto_ruko' => $this->upload->display_errors()
				];
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $errors
				], 422);
			}
			else{
				$data['foto_ruko'] = $this->upload->data()['file_name'];
			}
		}

		if(!empty($_FILES['foto_pemilik'])){
			if (!$this->upload->do_upload('foto_pemilik')){
				$errors = [
					'foto_pemilik' => $this->upload->display_errors()
				];
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $errors
				], 422);
			}
			else{
				$data['foto_pemilik'] = $this->upload->data()['file_name'];
			}
		}

		$this->dealer->update($data, $this->input->post(['id_dealer']));
		$this->db->trans_complete();

		$dealer = (array) $this->dealer->get($this->input->post(['id_dealer']), true);
		if($this->db->trans_status() AND $dealer != null){
			send_json([
				'message' => 'Berhasil memperbarui customer',
				'payload' => $dealer,
				'redirect_url' => base_url('h3/h3_md_ms_customer/detail?id_dealer=' . $dealer['id_dealer'])
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil memperbarui customer'
			], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('pemilik', 'Nama Pemilik', 'required');
		$this->form_validation->set_rules('pimpinan', 'Nama Pimpinan', 'required');
		$this->form_validation->set_rules('pic', 'Nama PIC', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		// $this->form_validation->set_rules('nama_dealer', 'Nama Dealer', 'required');
		$this->form_validation->set_rules('no_telp', 'No Telepon', 'required|numeric|max_length[13]');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('id_kelurahan', 'Kelurahan', 'required|numeric');
		$this->form_validation->set_rules('top_part', 'TOP Part', 'required|numeric');
		$this->form_validation->set_rules('top_oli', 'TOP Oli', 'required|numeric');
		$this->form_validation->set_rules('npwp', 'NPWP', 'required');
		$this->form_validation->set_rules('tipe_diskon', 'Tipe Diskon', 'required');
		$this->form_validation->set_rules('diskon_fixed_order', 'Diskon Fix Order', 'required|numeric');
		$this->form_validation->set_rules('diskon_reguler', 'Diskon Reguler', 'required|numeric');
		$this->form_validation->set_rules('diskon_hotline', 'Diskon Hotline', 'required|numeric');
		$this->form_validation->set_rules('diskon_urgent', 'Diskon Urgent', 'required|numeric');
		$this->form_validation->set_rules('jumlah_ruko', 'Jumlah Ruko', 'required|numeric');
		$this->form_validation->set_rules('status_dealer', 'Status Dealer', 'required');
		$this->form_validation->set_rules('status_bangunan', 'Status Bangunan', 'required');
		$this->form_validation->set_rules('luas_bangunan', 'Luas Bangunan', 'required');
		$this->form_validation->set_rules('pkp', 'PKP', 'required');
		$this->form_validation->set_rules('tanggal_kerjasama', 'Tanggal Kerjasama', 'required');

		$this->form_validation->set_rules(
			'tipe_plafon_h3', 'Tipe Plafon H3',
			array(
					'required',
					array(
							'tipe_plafon_h3_callable',
							function($data)
							{
								$dealer = $this->db
								->select('d.id_dealer')
								->select('d.nama_dealer')
								->select('d.kode_dealer_md')
								->select('d.tipe_plafon_h3')
								->from('ms_dealer as d')
								->where('d.tipe_plafon_h3', $data)
								->get()->row_array();

								if($dealer != null){
									if($dealer['tipe_plafon_h3'] != 'reguler'){
										if($dealer['id_dealer'] != $this->input->post('id_dealer')){
											$this->form_validation->set_message('tipe_plafon_h3_callable', 
											"Dealer {$dealer['nama_dealer']} ({$dealer['kode_dealer_md']}) telah digunakan sebagai dealer plafon {$dealer['tipe_plafon_h3']}. Jika ingin menetapkan dealer ini sebagai dealer plafon {$dealer['tipe_plafon_h3']}, harap lakukan perubahan terlebih dahulu didealer sebelumnya.");
											return false;
										}
									}
								}
								return true;
							}
					)
			)
		);

		
		// $this->form_validation->set_rules(
		// 	'nama_dealer', 'Nama Dealer',
		// 	array(
		// 			'required',
		// 			array(
		// 					'nama_dealer_callable',
		// 					function($data)
		// 					{

		// 						$nama = trim($data);
		// 						$nama = strtolower($nama);

		// 						$nama_dealer = $this->db->get_where('ms_dealer', array('LOWER(nama_dealer)' => $nama));
		// 												// ->get()->row_array();
		// 						if ($nama_dealer->num_rows() > 0) {
		// 							$this->form_validation->set_message('nama_dealer_callable', 'Nama sudah ada di database.');
		// 							return FALSE;
		// 						} else {
		// 							return TRUE;
		// 						}

		// 					}
		// 			)
		// 	)
		// );


		if($this->uri->segment(3) == 'update'){
			$dealer = $this->dealer->get($this->input->post(['id_dealer']), true);
			$nama_dealer = $this->dealer->get($this->input->post(['nama_dealer']), true);
			
			if(
				!($dealer->kode_dealer_md == $this->input->post('kode_dealer_md'))
			){
				$this->form_validation->set_rules('kode_dealer_md', 'Kode Dealer MD', 'required|is_unique[ms_dealer.kode_dealer_md]');
			}
			if(
				!($nama_dealer->nama_dealer == $this->input->post('nama_dealer'))
			){
				$this->form_validation->set_rules('nama_dealer', 'Nama Dealer', 'required');
			}
		}else{
			$this->form_validation->set_rules('kode_dealer_md', 'Kode Dealer MD', 'required|is_unique[ms_dealer.kode_dealer_md]');
			$this->form_validation->set_rules(
				'nama_dealer', 'Nama Dealer',
				array(
						'required',
						array(
								'nama_dealer_callable',
								function($data)
								{
	
									$nama = trim($data);
									$nama = strtolower($nama);
	
									$nama_dealer = $this->db->get_where('ms_dealer', array('LOWER(nama_dealer)' => $nama));
															// ->get()->row_array();
									if ($nama_dealer->num_rows() > 0) {
										$this->form_validation->set_message('nama_dealer_callable', 'Nama sudah ada di database.');
										return FALSE;
									} else {
										return TRUE;
									}
	
								}
						)
				)
			);
	
		}

		$errors = [];
        if (!$this->form_validation->run())
        {
			$errors = array_merge($errors, $this->form_validation->error_array());
		}

		if($this->uri->segment(3) != 'update'){
			if(empty($_FILES['foto_ruko'])){
				$errors['foto_ruko'] = str_replace(
					'{field}',
					'Foto Ruko',
					lang('form_validation_required')
				);
			}
	
			if(empty($_FILES['foto_pemilik'])){
				$errors['foto_pemilik'] = str_replace(
					'{field}',
					'Foto Pemilik',
					lang('form_validation_required')
				);
			}
		}

		if(count($errors) > 0){
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			], 422);
		}
    }
}