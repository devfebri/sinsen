<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_online_stock_part_dealer_offline extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_online_stock_part_dealer_offline";
    protected $title  = "Online Stock Part Dealer (Offline)";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('H3_md_online_stok_dealer_offline_model', 'online_stok_dealer_offline');
		$this->load->model('H3_md_online_stok_dealer_offline_sales_model', 'online_stok_dealer_offline_sales');
		$this->load->model('H3_md_file_transfer_model', 'file_transfer');
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

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function upload_excel_stok(){
		$data['set'] = 'upload_excel_stok';
		$data['mode'] = 'insert';

		$this->template($data);
	}

	public function store_upload_stok(){
		$config['upload_path'] = './uploads/online_stok_dealer_offline_template_stok/';
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
			$data = $this->read_excel_stok($this->upload->data()['file_name']);
			$all_valid = true;
			$validation_error = [];
			$index = 1;
			foreach ($data as $row) {
				$this->form_validation->set_data($row);
				$this->validate_online_stok_dealer_offline();
				if (!$this->form_validation->run()){
					$all_valid = false;
					$validation_error[] = [
						'message' => "Terdapat data yang tidak lengkap di kode part baris ke {$index}",
						'errors' =>$this->form_validation->error_array(),
					];
				}
				$this->form_validation->reset_validation();
				$index ++;
			}

			if(!$all_valid){
				send_json([
					'error_type' => 'validation_error',
					'payload' => $validation_error
				], 422);
			}

			$this->db->trans_start();
			foreach ($data as $row) {
				$record = $this->online_stok_dealer_offline->get([
					'id_dealer' => $row['id_dealer'],
					'id_part' => $row['id_part']
				], true);
				
				if($record != null){
					$update_data = $this->get_in_array(['stok_onhand', 'stok_avs', 'fast_slow', 'rank'], $row);
					$this->online_stok_dealer_offline->update($update_data, [
						'id_dealer' => $row['id_dealer'],
						'id_part' => $row['id_part']
					]);
				}else{
					$this->online_stok_dealer_offline->insert($row);
				}
			}
			if ($this->input->post('id_dealer') != null) {
				$data = $this->file_transfer->find($this->input->post('id_dealer'), 'id_dealer');
				if($data != null){
					$this->file_transfer->update([
						'tanggal_upload_stok' => date('Y-m-d H:i:s', time())
					], [
						'id_dealer' => $this->input->post('id_dealer')
					]);
				}else{
					$this->file_transfer->insert([
						'id_dealer' => $this->input->post('id_dealer'),
						'tanggal_upload_stok' => date('Y-m-d H:i:s', time())
					]);
				}
			}
			$this->db->trans_complete();

			if($this->db->trans_status()){
				$this->session->set_userdata('pesan', 'File template excel stok berhasil diupload.');
				$this->session->set_userdata('tipe', 'success');
			}else {
				$this->output->set_status_header(500);
				$this->session->set_userdata('pesan', 'File template excel stok tidak berhasil diupload.');
				$this->session->set_userdata('tipe', 'danger');
			}
		}
	}

	public function validate_online_stok_dealer_offline(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_part', 'Part', 'required');
        $this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
        $this->form_validation->set_rules('stok_onhand', 'Stok Onhand', 'required');
        $this->form_validation->set_rules('stok_avs', 'Stok AVS', 'required');
        $this->form_validation->set_rules('fast_slow', 'Fast / Slow', 'required');
        $this->form_validation->set_rules('rank', 'Rank', 'required');
    }

	public function read_excel_stok($filename){
        //  Include PHPExcel_IOFactory
        include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

        $filepath = "./uploads/online_stok_dealer_offline_template_stok/{$filename}";

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
		$result = [];
        for ($row = 2; $row <= $highestRow; $row++){ 
            //  Read a row of data into an array
			$rowData = $sheet->rangeToArray('B' . $row . ':G' . $row, NULL, TRUE, FALSE)[0];

			if($rowData[0] == null || $rowData[0] == '') break;

			$result[] = [
				'id_part' => $rowData[0],
				'id_dealer' => $this->input->post('id_dealer'),
				'stok_onhand' => $rowData[2],
				'stok_avs' => $rowData[3],
				'fast_slow' => $rowData[4],
				'rank' => $rowData[5],
			];
		}
		return $result;
	}

	public function download_template_stok(){
		$this->load->helper('download');
		force_download('assets/template/online_stok_dealer_offline_template_stok.xlsx', NULL);
	}

	public function upload_excel_sales(){
		$data['set'] = 'upload_excel_sales';
		$data['mode'] = 'insert';

		$this->template($data);
	}

	public function store_upload_sales(){
		$config['upload_path'] = './uploads/online_stok_dealer_offline_template_sales/';
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
			$data = $this->read_excel_sales($this->upload->data()['file_name']);
			$all_valid = true;
			$validation_error = [];
			$index = 1;
			foreach ($data as $row) {
				$this->form_validation->set_data($row);
				$this->validate_online_stok_dealer_offline_sales();
				if (!$this->form_validation->run()){
					$all_valid = false;
					$validation_error[] = [
						'message' => "Terdapat data yang tidak lengkap pada kode part {$row['id_part']} di bulan {$row['bulan']}",
						'errors' =>$this->form_validation->error_array(),
					];
				}
				$this->form_validation->reset_validation();
				$index ++;
			}

			if(!$all_valid){
				send_json([
					'error_type' => 'validation_error',
					'payload' => $validation_error
				], 422);
			}

			$this->db->trans_start();
			foreach ($data as $row) {
				$condition = [
					'id_dealer' => $row['id_dealer'],
					'id_part' => $row['id_part'],
					'bulan' => $row['bulan'],
				];

				$record = $this->online_stok_dealer_offline_sales->get($condition, true);
				
				if($record != null){
					$update_data = $this->get_in_array(['qty_penjualan'], $row);
					$this->online_stok_dealer_offline_sales->update($update_data, $condition);
				}else{
					$this->online_stok_dealer_offline_sales->insert($row);
				}
			}

			if ($this->input->post('id_dealer') != null) {
				$data = $this->file_transfer->find($this->input->post('id_dealer'), 'id_dealer');
				if($data != null){
					$this->file_transfer->update([
						'tanggal_upload_sales' => date('Y-m-d H:i:s', time())
					], [
						'id_dealer' => $this->input->post('id_dealer')
					]);
				}else{
					$this->file_transfer->insert([
						'id_dealer' => $this->input->post('id_dealer'),
						'tanggal_upload_sales' => date('Y-m-d H:i:s', time())
					]);
				}
			}
			$this->db->trans_complete();

			if($this->db->trans_status()){
				$this->session->set_userdata('pesan', 'File template excel sales berhasil diupload.');
				$this->session->set_userdata('tipe', 'success');
			}else {
				$this->output->set_status_header(500);
				$this->session->set_userdata('pesan', 'File template excel sales tidak berhasil diupload.');
				$this->session->set_userdata('tipe', 'danger');
			}
		}
	}

	public function validate_online_stok_dealer_offline_sales(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_part', 'Part', 'required');
        $this->form_validation->set_rules('id_dealer', 'Dealer', 'required|numeric');
        $this->form_validation->set_rules('qty_penjualan', 'Qty Penjualan', 'required|numeric');
        $this->form_validation->set_rules('bulan', 'Bulan', 'required');
    }

	public function read_excel_sales($filename){
        //  Include PHPExcel_IOFactory
        include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

        $filepath = "./uploads/online_stok_dealer_offline_template_sales/{$filename}";

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
		$result = [];
        for ($row = 3; $row <= $highestRow; $row++){ 
			//  Read a row of data into an array
			$sales_data = $sheet->rangeToArray('D' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
			
			$sales_data_per_part = [];
			$index = 1;
			foreach ($sales_data as $value) {
				if($value == '') continue;
				$data = [];
				$data['id_part'] = $sheet->getCell('B' . $row)->getValue();
				if ($this->input->post('id_dealer') != null) {
					$data['id_dealer'] = $this->input->post('id_dealer');
				}
				$data['qty_penjualan'] = $value;
				$data['bulan'] = $index;
				$sales_data_per_part[] = $data;

				$index++;
			}

			$result = array_merge($result, $sales_data_per_part);
		}
		return $result;
	}

	public function download_template_sales(){
		$this->load->helper('download');
		force_download('assets/template/online_stok_dealer_offline_template_excel_sales.xlsx', NULL);
	}
}