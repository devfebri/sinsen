<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Part extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "part";
    protected $title  = "Master Part";

	private $harus_update_harga;

	public function __construct()
	{		
		parent::__construct();
		ini_set('display_errors', 1);
		ini_set('memory_limit', '-1');
        ini_set('error_reporting', E_ALL);
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', 0);
		ini_set('upload_max_filesize', '128M');

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_part');		
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

		$this->load->model('ms_part_model', 'part');
		$this->load->model('H3_md_ms_kelompok_vendor_model', 'kelompok_vendor');
		$this->load->model('kelompok_part_model', 'kelompok_part');		
		$this->load->model('H3_md_ms_sim_part_model', 'sim_part');
		$this->load->model('H3_md_ms_sim_part_item_model', 'sim_part_item');

		$this->check_harus_update_harga();
	}

	private function check_harus_update_harga(){
		$this->db
		->group_start()
		->where('suh.update_po_dealer', 0)
		->or_where('suh.update_po_md', 0)
		->or_where('suh.update_so', 0)
		->or_where('suh.update_do', 0)
		->or_where('suh.update_niguri', 0)
		->or_where('suh.update_do_revisi', 0)
		->group_end()
		->limit(1)
		->from('tr_h3_md_status_update_harga as suh');

		if($this->db->get()->num_rows() > 0){
			$this->harus_update_harga = true;
		}
	}

	public function index()
	{				
		$data['set']	= "index";
		$data['harus_update_harga'] = $this->harus_update_harga;
		$this->template($data);	
	}

	public function add()
	{				
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$data['satuan'] = $this->db->from('ms_satuan')->get()->result();
		$data['kelompok_vendor'] = $this->db->from('ms_kelompok_vendor')->get()->result();
		$data['kelompok_part'] = $this->db->from('ms_kelompok_part')->order_by('kelompok_part', 'asc')->order_by('created_manually', 'desc')->get()->result();
		$this->template($data);	
	}

	public function upload_pmp()
	{				
		$data['mode']    = 'insert';
		$data['set']     = "upload_pmp";
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

		if (!$this->upload->do_upload('file')) {
			send_json([
				'message' => 'File tidak berhasil di upload',
			], 422);
		}

		$data = $this->upload->data();
		$filename = $data['file_name'];
		$this->part->upload($upload_path, $filename);
	}

	public function validate_upload_pmp(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_part', 'Kode Part', 'required');
        $this->form_validation->set_rules('nama_part', 'Nama Part', 'required');
        $this->form_validation->set_rules('kelompok_vendor', 'Kelompok Vendor', 'required');
        $this->form_validation->set_rules('harga_dealer_user', 'HET', 'required|numeric');
        $this->form_validation->set_rules('harga_md_dealer', 'Harga Pokok', 'required|numeric');
        $this->form_validation->set_rules('kelompok_part', 'Kelompok Part', 'required');
        // $this->form_validation->set_rules('part_reference', 'Part Reference', 'required');
		$this->form_validation->set_rules('kelompok_part', 'Kelompok Part', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        // $this->form_validation->set_rules('min_order_dealer_besar', 'Minimum Order Dealer Besar', 'required');
        // $this->form_validation->set_rules('min_order_dealer_menengah', 'Minimum Order Dealer Menengah', 'required');
		// $this->form_validation->set_rules('min_order_dealer_kecil', 'Minimum Order Dealer Kecil', 'required');
		// $this->form_validation->set_rules('pnt', 'PNT', 'required');
        // $this->form_validation->set_rules('fast_slow', 'Fast/Slow', 'required');
        // $this->form_validation->set_rules('import_lokal', 'Import/Lokal', 'required');
        // $this->form_validation->set_rules('rank', 'Rank', 'required');
        // $this->form_validation->set_rules('current', 'Current/Non-Current', 'required');
		// $this->form_validation->set_rules('important', 'Important/Safety/Additional', 'required');
    }

	public function upload_baca_pmp()
	{
		$upload_path = "./uploads/AHM";
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = '*';
		$config['overwrite'] = true;

		$this->load->library('upload');
		$this->upload->initialize($config);

		if ($this->upload->do_upload('file')) {
			$data = $this->upload->data();
			$myfile = fopen("$upload_path/{$data['file_name']}", "r");

			$lines = [];
			while ($line = fgets($myfile)) {
				$lines[] = $line;
			}
			return $lines;
		}
	}

	public function proses_pmp($pmp)
	{
		$final_data = [];
		$line_number = 1;
		foreach ($pmp as $each_line) {
			$line = trim($each_line);
			$exploded_line = explode(';', $line);

			$columns = [
				'id_part', 'nama_part', 'harga_dealer_user', 'harga_md_dealer', 'kelompok_vendor',
				'kelompok_part', 'part_reference', 'status', 'superseed', 'min_order_dealer_kecil',
				'min_order_dealer_menengah', 'min_order_dealer_besar', 'pnt', 'fast_slow', 'import_lokal', 
				'rank', 'current', 'important', 'long', 'engine', 
			];

			if(count($columns) > count($exploded_line)){
				send_json([
					'error_type' => 'format_error',
					'message' => "File .PMP tidak sesuai format pada baris {$line_number}.",
				], 422);
			}

			$data = [];
			for ($i=0; $i < count($columns); $i++) {
				$value = trim( $exploded_line[$i] );
				if($columns[$i] == 'status'){
					if($value == 1){
						$value = 'A';
					}elseif($value == 0){
						$value = 'D';
					}
				}
				$data[$columns[$i]] = $value;
			}

			$final_data[] = $data;
			$line_number++;
		}

		return $final_data;
	}

	public function save()
	{		
		$this->validate();

		$data = array_merge($this->input->post([
			'id_part', 'nama_part', 'kelompok_vendor', 'id_satuan', 'min_stok', 'maks_stok', 'minimal_order',
            'safety_stok', 'min_sales', 'kelompok_part', 'harga_md_dealer', 'harga_dealer_user',
            'sim_part', 'fix', 'reguler', 'pnt', 'fast_slow', 'import_lokal', 'rank', 'current',
            'important', 'long', 'engine', 'recommend_part', 'part_oli', 'qty_dus', 'superseed',
            'status', 'active'
		]), [
			'created_at' => date('Y-m-d H:i:s', time()),
			'created_by' => $this->session->userdata('id_user')
		]);	
			

		if(!empty($_FILES['gambar'])){
			$config['upload_path'] = './assets/panel/images/';
			$config['allowed_types'] = 'jpg|png|jpeg';
			$config['max_size'] = '100';
			$config['overwrite'] = true;
			$config['encrypt_name'] = true;
			$this->upload->initialize($config);
	
			if (!$this->upload->do_upload('gambar')){
				$errors = [
					'gambar' => $this->upload->display_errors()
				];
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $errors
				], 422);
			}
			else{
				$data['gambar'] = $this->upload->data()['file_name'];
			}
		}

		$this->db->trans_start();
		$this->part->insert($data);
		$this->db->trans_complete();

		$part = (array) $this->part->get($this->input->post(['id_part']), true);
		if($this->db->trans_status() AND $part != null){
			send_json([
				'message' => 'Berhasil menyimpan part',
				'payload' => $part,
				'redirect_url' => base_url(sprintf('h3/part/detail?id_part=%s', $part['id_part']))
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil menyimpan part'
			], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		if($this->uri->segment(3) == 'save'){
			$this->form_validation->set_rules('id_part', 'ID Part', 'required|is_unique[ms_part.id_part]');
		}
        $this->form_validation->set_rules('nama_part', 'Nama Part', 'required');
        $this->form_validation->set_rules('kelompok_vendor', 'Kelompok Vendor', 'required');
        $this->form_validation->set_rules('id_satuan', 'Satuan', 'required');
        // $this->form_validation->set_rules('min_stok', 'Min Stok', 'required');
        // $this->form_validation->set_rules('maks_stok', 'Maks Stok', 'required');
        // $this->form_validation->set_rules('safety_stok', 'Safety Stok', 'required');
        // $this->form_validation->set_rules('min_sales', 'Min Sales', 'required');
        $this->form_validation->set_rules('kelompok_part', 'Kelompok Part', 'required');
        $this->form_validation->set_rules('harga_md_dealer', 'Harga MD-Dealer', 'required');
        $this->form_validation->set_rules('harga_dealer_user', 'Harga Dealer-End User', 'required');
        // $this->form_validation->set_rules('pnt', 'PNT', 'required');
        // $this->form_validation->set_rules('fast_slow', 'Fast/Slow', 'required');
        // $this->form_validation->set_rules('import_lokal', 'Import/Lokal', 'required');
        // $this->form_validation->set_rules('rank', 'Rank', 'required');
        // $this->form_validation->set_rules('current', 'Current/Non-Current', 'required');
        // $this->form_validation->set_rules('important', 'Important/Safety/Additional', 'required');
        // $this->form_validation->set_rules('long', 'Long/Short/Others', 'required');
        // $this->form_validation->set_rules('engine', 'Engine/Frame/Electrical', 'required');
        // $this->form_validation->set_rules('recommend_part', 'Recommend Part', 'required');
        // $this->form_validation->set_rules('qty_dus', 'Qty per Dus', 'required');
        // $this->form_validation->set_rules('status', 'Status', 'required');
        // $this->form_validation->set_rules('minimal_order', 'Minimal Order', 'required|numeric');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
    }

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['part'] = $this->db
		->select('p.*')
		->select('s.satuan')
		->from('ms_part as p')
		->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
		->where('p.id_part', $this->input->get('id_part'))
		->get()->row_array();

		$data['ptm'] = $this->db
		->select('ptm.tipe_marketing')
		->select('ptm.deskripsi')
		->from('ms_pvtm as pvtm')
		->join('ms_ptm as ptm', 'ptm.tipe_produksi = pvtm.tipe_marketing')
		->where('pvtm.no_part', $this->input->get('id_part'))
		->get()->result();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['part'] = $this->db
		->select('p.*')
		->select('s.satuan')
		->from('ms_part as p')
		->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
		->where('p.id_part', $this->input->get('id_part'))
		->get()->row_array();
		
		$data['ptm'] = $this->db
		->select('ptm.tipe_produksi')
		->select('ptm.deskripsi')
		->from('ms_pvtm as pvtm')
		->join('ms_ptm as ptm', 'ptm.tipe_produksi = pvtm.tipe_marketing')
		->where('pvtm.no_part', $this->input->get('id_part'))
		->get()->result();
		$this->template($data);									
	}

	public function update()
	{		
		$id_part = $this->input->post('id_part');

		$part = $this->db
		->from('ms_part')
		->where('id_part', $id_part)
		->limit(1)
		->get()->row_array();
		if($part == null) throw new Exception('Part tidak ditemukan');

		$this->validate();
		$data = $this->input->post([
			'id_part', 'nama_part', 'kelompok_vendor', 'id_satuan', 'min_stok', 'maks_stok', 'minimal_order',
            'safety_stok', 'min_sales', 'kelompok_part', 'harga_md_dealer', 'harga_dealer_user',
            'sim_part', 'fix', 'reguler', 'pnt', 'fast_slow', 'import_lokal', 'rank', 'current',
            'important', 'long', 'engine', 'recommend_part', 'part_oli', 'qty_dus', 'superseed',
            'status', 'active'
		]);

		if($data['kelompok_vendor'] == 'AHM'){
			unset($data['harga_md_dealer']);
			unset($data['harga_dealer_user']);
		}

		if(!empty($_FILES['gambar'])){
			$config['upload_path'] = './assets/panel/images/';
			$config['allowed_types'] = 'jpg|png|jpeg';
			$config['max_size'] = '100';
			$config['overwrite'] = true;
			$config['encrypt_name'] = true;
			$this->upload->initialize($config);
	
			if (!$this->upload->do_upload('gambar')){
				$errors = [
					'gambar' => $this->upload->display_errors()
				];
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $errors
				], 422);
			}
			else{
				$data['gambar'] = $this->upload->data()['file_name'];
			}
		}

		$this->db->trans_start();
		$this->part->update($data, ['id_part_int' => $part['id_part_int']]);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Berhasil memperbarui part',
				'payload' => $part,
				'redirect_url' => base_url(sprintf('h3/part/detail?id_part=%s', $part['id_part']))
			]);
		}else{
			  send_json([
				'message' => 'Tidak berhasil memperbarui part'
			], 422);
		}
	}

	public function upload()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$data['dt_ptm'] = $this->m_admin->getAll("ms_pvtm");													
		$data['set']		= "upload";		
		$this->template($data);		
	}

	public function download_minimal_order_template(){
		$this->load->helper('download');
		force_download('assets/template/master_part_minimal_order_template.xlsx', NULL);
	}

	public function upload_minimal_order()
	{				
		$data['isi'] = $this->page;		
		$data['title'] = $this->title;				
		$data['set'] = "upload_minimal_order";		
		$this->template($data);		
	}

	public function store_upload_minimal_order(){
		$config['upload_path'] = './uploads/master_part_minimal_order/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')){
			$errors = [
				'file' => $this->upload->display_errors('', '')
			];
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			], 422);
        }else{
			$this->read_excel_minimal_order($this->upload->data()['file_name']);
		}
	}

	public function read_excel_minimal_order($filename){
        //  Include PHPExcel_IOFactory
        include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

        $filepath = "./uploads/master_part_minimal_order/{$filename}";

        //  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($filepath,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$this->db->trans_start();
        for ($row = 2; $row <= $highestRow; $row++){ 
            //  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

			if($rowData[0] == null || $rowData[0] == '') break;

			$part = $this->db
			->from('ms_part as p')
			->where("p.id_part = '{$rowData[0]}'", null, false)
			->get()->row();

			if($part != null){
				$this->db
				->set('minimal_order', $rowData[2])
				->where("id_part = '{$rowData[0]}'", null, false)
				->update('ms_part');
			}
		}
		$this->db->trans_complete();
	
		if(!$this->db->trans_status()){
			$this->output->set_status_header(500);
		}
	}

	public function download_simpart_template(){
		$this->load->helper('download');
		force_download('assets/template/master_part_simpart_template.xlsx', NULL);
	}
	
	public function upload_simpart()
	{				
		$data['isi'] = $this->page;		
		$data['title'] = $this->title;				
		$data['set'] = "upload_simpart";		
		$this->template($data);		
	}

	public function store_upload_simpart(){
		$config['upload_path'] = './uploads/master_part_simpart/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')){
			$errors = [
				'file' => $this->upload->display_errors('', '')
			];
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			], 422);
			return;
        }

		$this->db->trans_begin();
		try {
			$this->sim_part->upload_excel($this->upload->data()['file_name']);
			$this->db->trans_commit();

			send_json([
				'redirect_url' => base_url('h3/part')
			]);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', $e);

			send_json([
				'message' => $e->getMessage()
			], 422);
		}
	}

	public function download_fix_part_template(){
		$this->load->helper('download');
		force_download('assets/template/master_part_fix_part_template.xlsx', NULL);
	}
	
	public function upload_fix_part()
	{				
		$data['isi'] = $this->page;		
		$data['title'] = $this->title;				
		$data['set'] = "upload_fix_part";		
		$this->template($data);		
	}

	public function store_upload_fix_part(){
		$config['upload_path'] = './uploads/master_part_fix_part/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')){
			$errors = [
				'file' => $this->upload->display_errors('', '')
			];
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			], 422);
        }else{
			$data = $this->read_excel_fix_part($this->upload->data()['file_name']);

			if(count($data) == 0){
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Tidak ada data yang diupload.',
				], 422);
			}

			$this->db
			->select('DISTINCT(pop.id_part) as id_part', false)
			->from('tr_h3_md_purchase_order as po')
			->join('tr_h3_md_purchase_order_parts as pop', 'pop.id_purchase_order = po.id_purchase_order')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('p.fix', 1)
			;

			$kode_part_purchase_order = array_map(function($row){
				return $row['id_part'];
			}, $this->db->get()->result_array());

			$part_purchase_order_yang_harus_fix = array_diff($kode_part_purchase_order, $data);
			if(count($data) < count($part_purchase_order_yang_harus_fix) AND count($part_purchase_order_yang_harus_fix) > 0 AND $this->input->post('force') == 0){
				$label_kode_part = '';
				$loop = 1;
				foreach($part_purchase_order_yang_harus_fix as $kode_part){
					if($loop == 50){
						break;
					}
					$label_kode_part .= ', ' . $kode_part;
					$loop++;
				}
				$sisa_label_kode_part = count($part_purchase_order_yang_harus_fix) - $loop;
				$label_kode_part = substr($label_kode_part, 2);

				$message = sprintf('Terdapat %s part yang harus di buatkan fix karena sudah dibuatkan PO MD. Berikut adalah list kode part, antara lain: %s', count($part_purchase_order_yang_harus_fix), $label_kode_part);
				if($sisa_label_kode_part > 0){
					$message .= " dan {$sisa_label_kode_part} lainnya.";
				}
				
				send_json([
					'error_type' => 'part_purchase_order_yang_harus_fix_validation_error',
					'message' => $message,
					'filename' => $this->upload->data()['file_name']
				], 422);
			}

			$this->db
			->select('DISTINCT(n.id_part) as id_part', false)
			->from('tr_h3_md_niguri_header as nh')
			->join('tr_h3_md_niguri as n', 'n.id_niguri_header = nh.id')
			->join('ms_part as p', 'p.id_part = n.id_part')
			->where('p.fix', 1)
			;

			$kode_part_niguri = array_map(function($row){
				return $row['id_part'];
			}, $this->db->get()->result_array());

			$part_niguri_yang_harus_fix = array_diff($kode_part_niguri, $data);
			if(count($data) < count($part_niguri_yang_harus_fix) AND count($part_niguri_yang_harus_fix) > 0 AND $this->input->post('force') == 0){
				$label_kode_part = '';
				$loop = 1;
				foreach($part_niguri_yang_harus_fix as $kode_part){
					if($loop == 50){
						break;
					}
					$label_kode_part .= ', ' . $kode_part;
					$loop++;
				}
				$sisa_label_kode_part = count($part_niguri_yang_harus_fix) - $loop;
				$label_kode_part = substr($label_kode_part, 2);
				
				$message = sprintf('Terdapat %s part yang harus di buatkan fix karena sudah dibuatkan Niguri MD. Berikut adalah list kode part, antara lain: %s', count($part_niguri_yang_harus_fix), $label_kode_part);
				if($sisa_label_kode_part > 0){
					$message .= " dan {$sisa_label_kode_part} lainnya.";
				}

				send_json([
					'error_type' => 'part_niguri_yang_harus_fix_validation_error',
					'message' => $message,
					'filename' => $this->upload->data()['file_name']
				], 422);
			}

			$this->db->trans_start();
			$this->db->set('p.fix', 0)->update('ms_part as p');
			if(count($data) > 0){
				foreach($data as $id_part){
					$this->db->set('p.fix', 1)->where('p.id_part', "{$id_part}")->update('ms_part as p');
				}
			}
			$this->db->trans_complete();

			if(!$this->db->trans_status()){
				send_json([
					'message' => 'Gagal upload update fix part.',
				], 422);
			}
		}
	}

	public function read_excel_fix_part($filename){
        //  Include PHPExcel_IOFactory
        include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

        $filepath = "./uploads/master_part_fix_part/{$filename}";

        //  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($filepath,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$data = [];
        for ($row = 2; $row <= $highestRow; $row++){ 
            //  Read a row of data into an array
			$rowData = $sheet->rangeToArray("B$row", NULL, TRUE, FALSE)[0];

			if($rowData[0] == null || $rowData[0] == '') break;

			$data[] = $rowData[0];
		}
		return $data;
	}

	function numberToColumnName($number){
		$abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$abc_len = strlen($abc);
	
		$result_len = 1; // how much characters the column's name will have
		$pow = 0;
		while( ( $pow += pow($abc_len, $result_len) ) < $number ){
			$result_len++;
		}
	
		$result = "";
		$next = false;
		// add each character to the result...
		for($i = 1; $i<=$result_len; $i++){
			$index = ($number % $abc_len) - 1; // calculate the module
	
			// sometimes the index should be decreased by 1
			if( $next || $next = false ){
				$index--;
			}
	
			// this is the point that will be calculated in the next iteration
			$number = floor($number / strlen($abc));
	
			// if the index is negative, convert it to positive
			if( $next = ($index < 0) ) {
				$index = $abc_len + $index;
			}
	
			$result = $abc[$index].$result; // concatenate the letter
		}
		return $result;
	}

	public function download_bahasa_part_template(){
		$this->load->helper('download');
		force_download('assets/template/master_part_deskripsi_bahasa_part_template.xlsx', NULL);
	}

	public function upload_deskripsi_bahasa_part()
	{				
		$data['isi'] = $this->page;		
		$data['title'] = $this->title;				
		$data['set'] = "upload_deskripsi_bahasa_part";		
		$this->template($data);		
	}

	public function store_upload_bahasa_part(){
		
		$config['upload_path'] = './uploads/master_deskripsi_bahasa_part/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')){
			$errors = [
				'file' => $this->upload->display_errors('', '')
			];
			$this->output->set_status_header(400);
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			]);
			
        }else{
			// var_dump("TEEST");
			// die();
			$this->read_excel_deskripsi_bahasa_part($this->upload->data()['file_name']);
		}
	}

	public function read_excel_deskripsi_bahasa_part($filename){
        //  Include PHPExcel_IOFactory
        include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

        $filepath = "./uploads/master_deskripsi_bahasa_part/{$filename}";

        //  Read your Excel workbook
		try {
            $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($filepath,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

		// var_dump("TEST");
		// die();

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$this->db->trans_start();
        for ($row = 2; $row <= $highestRow; $row++){ 
            //  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

			if($rowData[0] == null || $rowData[0] == '') break;

			$part = $this->db
			->select('id_part')
			->from('ms_part as p')
			->where("p.id_part = '{$rowData[0]}'", null, false)
			->get()->row();

			if($part != null){
				$this->db
				->set('nama_part_bahasa', $rowData[1])
				->where("id_part = '{$rowData[0]}'", null, false)
				->update('ms_part');
			}
		}
		$this->db->trans_complete();
	
		if(!$this->db->trans_status()){
			$this->output->set_status_header(500);
		}
	}
}