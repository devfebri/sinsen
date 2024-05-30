<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_etd extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_etd";
	protected $title  = "Estimated Time Delivery (ETD)";

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
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
		$this->load->model('h3_md_etd_model', 'etd');
		$this->load->model('h3_md_etd_items_model', 'etd_items');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$data['etd'] = $this->etd->all();

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
		$this->validate();

		$etd_data = $this->input->post(['ahm_md', 'proses_md', 'md_d','min_md_d', 'max_md_d', 'proses_d', 'lc', 'ln', 'ic', 'in', 'rc', 'rn']);
		$this->db->trans_start();
		$this->etd->insert($etd_data);
		$id_etd = $this->db->insert_id();
		$etd_items_data = $this->getOnly(true, $this->input->post('dealers'), [
			'id_etd' => $id_etd
		]);
		$this->etd_items->insert_batch($etd_items_data);
		$this->db->trans_complete();

		$etd = $this->etd->find($id_etd, 'id');

		if ($this->db->trans_status() and $etd != null) {
			send_json([
				'redirect_url' => base_url(sprintf('h3/h3_md_etd/detail?id=%s', $etd->id))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan ETD',
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";

		$etd = $this->etd->get($this->input->get(['id']), true);

		$data['etd'] = $etd;
		$data['dealers'] = $this->db
			->select('d.*')
			->select('kabupaten.kabupaten')
			->from('ms_h3_md_estimated_time_delivery_items as etdi')
			->join('ms_dealer as d', 'd.id_dealer = etdi.id_dealer')
			->join('ms_kelurahan as kelurahan', 'kelurahan.id_kelurahan = d.id_kelurahan')
			->join('ms_kecamatan as kecamatan', 'kecamatan.id_kecamatan = kelurahan.id_kecamatan')
			->join('ms_kabupaten as kabupaten', 'kabupaten.id_kabupaten = kecamatan.id_kabupaten')
			->where('etdi.id_etd', $etd->id)
			->get()->result();

		$this->template($data);
	}

	public function table_eta()
	{
		$data['mode']    = 'table_eta';
		$data['set']     = "table_eta";
		$data['tabel_eta'] = $this->etd->tabel_eta();
		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$etd = $this->etd->get($this->input->get(['id']), true);

		$data['etd'] = $etd;
		$data['dealers'] = $this->db
			->select('d.*')
			->select('kabupaten.kabupaten')
			->from('ms_h3_md_estimated_time_delivery_items as etdi')
			->join('ms_dealer as d', 'd.id_dealer = etdi.id_dealer')
			->join('ms_kelurahan as kelurahan', 'kelurahan.id_kelurahan = d.id_kelurahan')
			->join('ms_kecamatan as kecamatan', 'kecamatan.id_kecamatan = kelurahan.id_kecamatan')
			->join('ms_kabupaten as kabupaten', 'kabupaten.id_kabupaten = kecamatan.id_kabupaten')
			->where('etdi.id_etd', $etd->id)
			->get()->result();

		$this->template($data);
	}

	public function update()
	{
		$etd_data = $this->input->post(['ahm_md', 'proses_md', 'md_d','min_md_d', 'max_md_d', 'proses_d' , 'lc', 'ln', 'ic', 'in', 'rc', 'rn']);
		$this->db->trans_start();
		$this->etd->update($etd_data, $this->input->post(['id']));

		$etd_items_data = $this->getOnly(true, $this->input->post('dealers'), [
			'id_etd' => $this->input->post('id')
		]);

		$this->etd_items->update_batch($etd_items_data, [
			'id_etd' => $this->input->post('id')
		]);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json([
				'redirect_url' => base_url(sprintf('h3/h3_md_etd/detail?id=%s', $this->input->post('id')))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan ETD',
			], 422);
		}
	}

	public function delete()
	{
		$this->db->trans_start();
		$this->etd->delete($this->input->get('id'));
		$this->etd_items->delete($this->input->get('id'), 'id_etd');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page'>";
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('ahm_md', 'AHM-MD', 'required|numeric');
		$this->form_validation->set_rules('proses_md', 'Waktu Proses MD', 'required|numeric');
		$this->form_validation->set_rules('md_d', 'MD-D', 'required|numeric');
		$this->form_validation->set_rules('min_md_d', 'Min HO Lead Time MD-D', 'required|numeric');
		$this->form_validation->set_rules('max_md_d', 'Max HO Lead Time MD-D', 'required|numeric');
		$this->form_validation->set_rules('proses_d', 'Waktu Proses D', 'required|numeric');
		$this->form_validation->set_rules('lc', 'Local Parts, Current', 'required|numeric');
		$this->form_validation->set_rules('ln', 'Local Parts, Non-Current', 'required|numeric');
		$this->form_validation->set_rules('ic', 'Import Parts, Current', 'required|numeric');
		$this->form_validation->set_rules('in', 'Import Parts, Non-Current', 'required|numeric');
		$this->form_validation->set_rules('rc', 'Re-numbering Claim', 'required|numeric');
		$this->form_validation->set_rules('rn', 'Re-numbering Non-claim', 'required|numeric');
		$this->form_validation->set_rules('dealers', 'Dealer',  array(
			array(
				'check_dealers',
				function ($value) {
					$valid = count($this->input->post('dealers')) > 0;

					$dealers = $this->input->post('dealers');

					foreach ($dealers as $dealer) {
						$record = $this->db
							->select('d.*')
							->from('ms_h3_md_estimated_time_delivery_items as etdi')
							->join('ms_dealer as d', 'd.id_dealer = etdi.id_dealer')
							->where('etdi.id_dealer', $dealer['id_dealer'])
							->get()->row();

						if ($record != null) {
							$this->form_validation->set_message('check_dealers', $record->id_dealer);
							return false;
						}
					}
					return true;
				}
			)
		));

		if (!$this->form_validation->run()) {
			$keys = ['ahm_md', 'proses_md', 'md_d', 'min_md_d', 'max_md_d', 'proses_d' ,'lc', 'ln', 'ic', 'in', 'rc', 'rn', 'dealers'];
			$data = [];

			foreach ($keys as $key) {
				$data[$key] = form_error($key) == '' ? null : form_error($key);
			}

			send_json($data, 422);
		}
	}

	public function upload_etd_revisi()
	{
		$data['isi'] = $this->page;
		$data['title'] = $this->title;
		$data['set'] = "upload_etd_revisi";
		$this->template($data);
	}

	public function upload_etd_revisi_ahm()
	{
		$data['isi'] = $this->page;
		$data['title'] = $this->title;
		$data['set'] = "upload_etd_revisi_ahm";
		$this->template($data);
	}

	public function store_upload_etd_revisi()
	{
		ini_set('memory_limit', '-1');

		$this->load->model('H3_md_etd_upload_model', 'etd_upload');
		$this->db->trans_begin();

		try {
			$this->etd_upload->upload_excel('file');
			$this->db->trans_commit();
		} catch (\Exception $exception) {
			log_message('error', $exception);
			$this->db->trans_rollback();

			send_json([
				'message' => $exception->getMessage()
			], 422);
		}

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Upload ETA Revisi berhasil');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'redirect_url' => base_url('h3/h3_md_etd')
			]);
		} else {
			send_json([
				'message' => 'Upload ETA Revisi tidak berhasil'
			], 422);
		}
	}

	public function store_upload_etd_revisi_ahm()
	{
		$filename = $_FILES['file_csv']['name'];
		$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1);

		if($ext==".PETD"||$ext==".petd"){
			if (!empty($_FILES['file_csv']['name'])) {
				$separtor =';';
				// Open uploaded CSV file with read-only mode
				$csvFile = fopen($_FILES['file_csv']['tmp_name'], 'r');
	
				// Skip the first line
				// fgetcsv($csvFile);
	
				// Parse data from CSV file line by line
				$num = 0;
				$notFound = '-';
				$successNum = 0;
				while (($getData = fgetcsv($csvFile, null, $separtor)) !== FALSE) {
					// Get row data
					$id = $getData[1];
					$kode_part = $getData[4];
					$tgl_eta_awal = $getData[8];
					$tgl = $getData[9];

					if($tgl_eta_awal==" "){
						$fix_tgl_eta_awal=' ';
					}else{
						$date_array_eta_awal = str_split($tgl_eta_awal);
						$fix_tgl_eta_awal = $date_array_eta_awal[4].$date_array_eta_awal[5].$date_array_eta_awal[6].$date_array_eta_awal[7].'-'.$date_array_eta_awal[2].$date_array_eta_awal[3].'-'.$date_array_eta_awal[0].$date_array_eta_awal[1];
					}

					if($tgl==" "){
						$fix_tgl=' ';
					}else{
						$date_array = str_split($tgl);
						$fix_tgl = $date_array[4].$date_array[5].$date_array[6].$date_array[7].'-'.$date_array[2].$date_array[3].'-'.$date_array[0].$date_array[1];
					}
					// var_dump($text);
					// die();
					$id_part_int = $this->db->select('id_part_int')
											->from('ms_part')
											->where('id_part',$kode_part)
											->get()->row();

					$md_po = $this->db->query("SELECT referensi_po_hotline FROM tr_h3_md_purchase_order WHERE id_purchase_order='$id'")->row();

					$cekId = $this->db->get_where('tr_h3_md_purchase_order_parts', ['id_purchase_order' => $id, 'id_part' => $kode_part])->num_rows();
					if ($cekId > 0) {
						
						
						$this->db->trans_begin();
	
						$this->db->set('eta_revisi', $fix_tgl);
						$this->db->where('id_purchase_order', $id);
						$this->db->where('id_part', $kode_part);
						$this->db->update('tr_h3_md_purchase_order_parts');

						$this->db->set('eta_revisi', $fix_tgl);
						$this->db->where('po_id', $md_po->referensi_po_hotline);
						$this->db->where('id_part', $kode_part);
						$this->db->update('tr_h3_dealer_purchase_order_parts');

						//Insert atau Update Data 
						//Check apakah po dealer ada di tabel history po hotline 
						$check_po_history = $this->db->query("SELECT po_id, id_part, id_purchase_order FROM tr_h3_md_history_estimasi_waktu_hotline thmhewh WHERE po_id = '$md_po->referensi_po_hotline' and id_purchase_order = '$id' and id_part='$kode_part'")->num_rows(); 

						if($check_po_history > 0){
							$this->db->set('eta_revisi', $fix_tgl);
							$this->db->set('etd', $fix_tgl_eta_awal);
							$this->db->set('source', 'AHM');
							$this->db->set('updated_at',  date('Y-m-d H:i:s', time()));
							$this->db->set('updated_by', $this->session->userdata('id_user'));
							$this->db->where('po_id', $md_po->referensi_po_hotline);
							$this->db->where('id_purchase_order', $id);
							$this->db->where('id_part', $kode_part);
							$this->db->update('tr_h3_md_history_estimasi_waktu_hotline');
						}else{
							$data_htl = array(
								'id_purchase_order' => $id,
								'po_id' => $md_po->referensi_po_hotline,
								'id_part' => $kode_part,
								'eta' => "",
								'etd' => $fix_tgl_eta_awal,
								'eta_revisi' => $fix_tgl,
								'source' => "AHM",
								'created_at' => date('Y-m-d H:i:s', time()),
								'created_by' => $this->session->userdata('id_user'),
								'id_part_int' => $id_part_int->id_part_int
							);
	
							$this->db->insert('tr_h3_md_history_estimasi_waktu_hotline',$data_htl);
						}
						// if($fix_tgl!=' '){
						// 	$data_htl = array(
						// 		'id_purchase_order' => $id,
						// 		'po_id' => $md_po->referensi_po_hotline,
						// 		'id_part' => $kode_part,
						// 		'eta' => $fix_tgl_eta_awal,
						// 		'etd' => "",
						// 		'eta_revisi' => $fix_tgl,
						// 		'source' => "AHM",
						// 		'created_at' => date('Y-m-d H:i:s', time()),
						// 		'created_by' => $this->session->userdata('id_user'),
						// 		'id_part_int' => $id_part_int->id_part_int
						// 	);
	
						// 	$this->db->insert('tr_h3_md_history_estimasi_waktu_hotline',$data_htl);
						// }
	
						if ($this->db->trans_status() == TRUE) {
							$this->db->trans_commit();
							$successNum++;
						} else {
							$this->db->trans_rollback();
						}
					} else {
						$notFound .= $id.'='.$kode_part . '</br>';
					}
				}
	
				// Close opened CSV file
				fclose($csvFile);
				// $this->session->set_flashdata("success", "Berhasil update $successNum produk dan gagal update : $notFound");
				$_SESSION['pesan'] 	= "Berhasil update $successNum File dan gagal update : </br> $notFound";
				$_SESSION['tipe'] 	= "success";
				// echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/h2_dealer_customer_list'>";
			} else {
				// $this->session->set_flashdata("success", "File Tidak ditemukan");
				$_SESSION['pesan'] 	= "File Tidak ditemukan";
				$_SESSION['tipe'] 	= "danger";
			}
		}else{
			// $this->session->set_flashdata("success", "Format Data Salah");
			$_SESSION['pesan'] 	= "Format Data Salah";
			$_SESSION['tipe'] 	= "danger";
		}
		// Validate whether selected file is a CSV file
		
		redirect('h3/h3_md_etd/');
	}

	public function getDataTable()
	{
		$list = $this->etd->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama_dealer;
            $row[] = '<a id="'.$field->id.'" href="h3/h3_md_etd/detail?id='.$field->id.'" type="button" class="btn btn-primary btn-xs">Detail</a> | <a id="'.$field->id.'" href="h3/h3_md_etd/table_eta?id='.$field->id.'" type="button" class="btn btn-success btn-xs">View LT</a>';
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->etd->count_all(),
            "recordsFiltered" => $this->etd->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}
}
