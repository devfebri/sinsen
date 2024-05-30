<?php

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_online_stock_part_md extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_online_stock_part_md";
	protected $title  = "Online Stock Part MD";

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

		$this->load->model('h3_md_mutasi_gudang_model', 'mutasi_gudang');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('ms_gudang_model', 'ms_gudang');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');

		ini_set('memory_limit', '-1');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function get_keep_stock()
	{
		$keep_stock_default = $this->db
			->select('kpi.qty_keep_stock')
			->from('ms_kelompok_part_item as kpi')
			->where('kpi.id_kelompok = kp.id')
			->where('kpi.id_part = sp.id_part')
			->get_compiled_select();

		$data = $this->db
			->select('
			floor(
				(IFNULL(kp.keep_stock_toko, 0) / 100) * sum(sp.qty)
			) as keep_stock_toko
		', false)
			->select('
			floor(
				(IFNULL(kp.keep_stock_dealer, 0) / 100) * sum(sp.qty)
			) as keep_stock_dealer
		', false)
			->select('
			floor(
				(IFNULL(kp.keep_stock_hotline, 0) / 100) * sum(sp.qty)
			) as keep_stock_hotline
		', false)
			->select("IFNULL(({$keep_stock_default}), 0) as keep_stock_default", false)
			->from("tr_stok_part as sp")
			->join('ms_part as p', 'p.id_part = sp.id_part')
			->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
			->where('sp.id_part', $this->input->get('id_part'))
			->group_by('sp.id_part')
			->get()->row_array();

		$result = [];
		foreach ($data as $key => $value) {
			$sub_arr = [];
			if ($key == 'keep_stock_toko') {
				$sub_arr['name'] = 'Keep Stock Toko';
			} else if ($key == 'keep_stock_dealer') {
				$sub_arr['name'] = 'Keep Stock Dealer';
			} else if ($key == 'keep_stock_hotline') {
				$sub_arr['name'] = 'Keep Stock Hotline';
			} else if ($key == 'keep_stock_default') {
				$sub_arr['name'] = 'Keep Stock Default';
			}
			$sub_arr['value'] = $value;

			$result[] = $sub_arr;
		}

		send_json($result);
	}

	public function get_rak_lokasi()
	{
		$data = $this->db
			->select('lr.kode_lokasi_rak')
			->select('lr.deskripsi')
			->select('sp.qty as qty_on_hand')
			->from('tr_stok_part as sp')
			->join('ms_part as p', 'p.id_part_int = sp.id_part_int')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = sp.id_lokasi_rak')
			->where('sp.id_part', $this->input->get('id_part'))
			->get()->result_array();

		send_json($data);
	}

	public function get_tipe_motor()
	{
		$data = $this->db
			->select('pt.tipe_produksi')
			->select('pt.tipe_marketing')
			->select('pt.deskripsi')
			->from('ms_pvtm as pv')
			->join('ms_ptm as pt', 'pt.tipe_produksi = pv.tipe_marketing')
			->where('pv.no_part', $this->input->get('id_part'))
			->get()->result_array();

		send_json($data);
	}

	public function report()
	{
		$excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		// Settingan awal fil excel
		$excel->getProperties()
			->setCreator('SSP')
			->setLastModifiedBy('SSP')
			->setTitle('Report Stock');

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(34);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

		$header_style = [
			'font' => [
				'bold' => true
			],
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color' => array('rgb' => '7AF229')
			),
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER
			],
			'borders' => array(
				'allBorders' => array(
					'style' => Border::BORDER_THIN
				),
			)
		];
		$excel->setActiveSheetIndex(0)->setCellValue('A1', 'No');
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('B1', 'Kode Part');
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('C1', 'Deskripsi Part');
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('D1', 'HET');
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('E1', 'Harga Beli');
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('F1', 'Kel. Barang');
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('G1', 'Status');
		$excel->getActiveSheet()->getStyle('G1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('H1', 'Stok');
		$excel->getActiveSheet()->getStyle('H1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('I1', 'Rak Default');
		$excel->getActiveSheet()->getStyle('I1')->applyFromArray($header_style);

		$parts = $this->db
			->select('sp.id_part')
			->select('p.nama_part')
			->select('p.harga_dealer_user')
			->select('p.harga_md_dealer')
			->select('p.kelompok_part')
			->select('p.status')
			->select('sp.qty')
			->select('lr.kode_lokasi_rak')
			// ->from('tr_stok_part as sp')
			->from('ms_part as p')
			// ->join('ms_part as p', 'p.id_part_int = sp.id_part_int', 'left')
			// ->join('ms_part as p', 'p.id_part_int = sp.id_part_int')
			->join('tr_stok_part as sp', 'p.id_part_int = sp.id_part_int')
			// ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = sp.id_lokasi_rak', 'left')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = sp.id_lokasi_rak')
			// ->limit(10000)
			->get()->result_array();

		$start_index = 2;
		$allborders = [
			'borders' => array(
				'allBorders' => array(
					'style' => Border::BORDER_THIN
				),
			)
		];
		$no_index = 1;
		foreach ($parts as $part) {
			$excel->setActiveSheetIndex(0)->setCellValue('A' . $start_index, $no_index);
			$excel->getActiveSheet()->getStyle('A' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValueExplicit('B' . $start_index, $part['id_part'], DataType::TYPE_STRING);
			$excel->getActiveSheet()->getStyle('B' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('C' . $start_index, $part['nama_part']);
			$excel->getActiveSheet()->getStyle('C' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('D' . $start_index, $part['harga_dealer_user']);
			$excel->getActiveSheet()->getStyle('D' . $start_index)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$excel->getActiveSheet()->getStyle('D' . $start_index)->getNumberFormat()->setFormatCode('Rp #,##0');
			$excel->getActiveSheet()->getStyle('D' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('E' . $start_index, $part['harga_md_dealer']);
			$excel->getActiveSheet()->getStyle('E' . $start_index)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$excel->getActiveSheet()->getStyle('E' . $start_index)->getNumberFormat()->setFormatCode('Rp #,##0');
			$excel->getActiveSheet()->getStyle('E' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('F' . $start_index, $part['kelompok_part']);
			$excel->getActiveSheet()->getStyle('F' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('G' . $start_index, $part['status']);
			$excel->getActiveSheet()->getStyle('G' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('H' . $start_index, $part['qty']);
			$excel->getActiveSheet()->getStyle('H' . $start_index)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$excel->getActiveSheet()->getStyle('H' . $start_index)->getNumberFormat()->setFormatCode('#,##0');
			$excel->getActiveSheet()->getStyle('H' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('I' . $start_index, $part['kode_lokasi_rak'], DataType::TYPE_STRING);
			$excel->getActiveSheet()->getStyle('I' . $start_index)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$excel->getActiveSheet()->getStyle('I' . $start_index)->applyFromArray($allborders);

			$start_index++;
			$no_index++;
		}

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
		ob_end_clean();
		$filename = sprintf('Report stock main dealer %s', Mcarbon::now()->format('d_m_Y'));

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function report_new()
	{
		$data['report'] = $this->db->query("SELECT sp.id_part, p.nama_part, p.harga_dealer_user, p.harga_md_dealer, p.kelompok_part, p.status, sp.qty, lr.kode_lokasi_rak from tr_stok_part as sp JOIN ms_part as p ON p.id_part_int = sp.id_part_int join ms_h3_md_lokasi_rak as lr ON lr.id = sp.id_lokasi_rak ORDER BY id_part ASC");
		$this->load->view("h3/laporan/temp_laporan_stok_md_new", $data); 
	}

	public function report_csv()
	{
		set_time_limit(500);
		ini_set('memory_limit', '5000M');
		ini_set('max_execution_time', 1000000000000);

		$parts = $this->db
			->select('sp.id_part')
			->select('p.nama_part')
			->select('p.harga_dealer_user as het')
			->select('p.harga_md_dealer as hpp')
			->select('p.kelompok_part')
			->select('p.status')
			->select('sp.qty')
			->select('lr.kode_lokasi_rak')
			->from('tr_stok_part as sp')
			// ->join('ms_part as p', 'p.id_part_int = sp.id_part_int', 'left')
			->join('ms_part as p', 'p.id_part_int = sp.id_part_int')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = sp.id_lokasi_rak')
			->get()->result_array();

		$delimiter = ";";
		$filename = "online_stock_md_" . date('Y-m-d') . ".csv";

		//create a file pointer
		$f = fopen('php://memory', 'w');

		//set column headers
		$fields = array_keys($parts[0]);
		fputcsv($f, $fields, $delimiter);

		//output each row of the data, format line as csv and write to file pointer
		foreach ($parts as $part) {
			$lineData = array("'" . $part['id_part'], $part['nama_part'], $part['het'], $part['hpp'], $part['kelompok_part'], $part['status'], $part['qty'], $part['kode_lokasi_rak']);
			fputcsv($f, $lineData, $delimiter);
		}

		//move back to beginning of file
		fseek($f, 0);

		//set headers to download file rather than displayed
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '";');

		//output all remaining data on a file pointer
		fpassthru($f);
	}

	public function report_tanpa_lokasi()
	{
		$excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		// Settingan awal fil excel
		$excel->getProperties()
			->setCreator('SSP')
			->setLastModifiedBy('SSP')
			->setTitle('Report Stock');

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(34);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

		$header_style = [
			'font' => [
				'bold' => true
			],
			'fill' => array(
				'fillType' => Fill::FILL_SOLID,
				'color' => array('rgb' => '7AF229')
			),
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER
			],
			'borders' => array(
				'allBorders' => array(
					'style' => Border::BORDER_THIN
				),
			)
		];
		$excel->setActiveSheetIndex(0)->setCellValue('A1', 'No');
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('B1', 'Kode Part');
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('C1', 'Deskripsi Part');
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('D1', 'HET');
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('E1', 'Harga Beli');
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('F1', 'Kel. Barang');
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('G1', 'Status');
		$excel->getActiveSheet()->getStyle('G1')->applyFromArray($header_style);
		$excel->setActiveSheetIndex(0)->setCellValue('H1', 'Stok');
		$excel->getActiveSheet()->getStyle('H1')->applyFromArray($header_style);

		$this->load->model('H3_md_stock_int_model', 'stock_int');

		// $stock = $this->stock_int->qty_on_hand('p.id_part', null, true);
		$parts = $this->db
			->select('p.id_part')
			->select('p.nama_part')
			->select('p.harga_dealer_user')
			->select('p.harga_md_dealer')
			->select('p.kelompok_part')
			->select('p.status')
			->select('sp.qty')
			// ->select("IFNULL(({$stock}), 0) as qty", false)
			->from('tr_stok_part as sp')
			->join('ms_part as p', 'p.id_part_int = sp.id_part_int')
			// ->limit(100)
			->get()->result_array();

		$start_index = 2;
		$allborders = [
			'borders' => array(
				'allBorders' => array(
					'style' => Border::BORDER_THIN
				),
			)
		];
		$no_index = 1;
		foreach ($parts as $part) {
			$excel->setActiveSheetIndex(0)->setCellValue('A' . $start_index, $no_index);
			$excel->getActiveSheet()->getStyle('A' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValueExplicit('B' . $start_index, $part['id_part'], DataType::TYPE_STRING);
			$excel->getActiveSheet()->getStyle('B' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('C' . $start_index, $part['nama_part']);
			$excel->getActiveSheet()->getStyle('C' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('D' . $start_index, $part['harga_dealer_user']);
			$excel->getActiveSheet()->getStyle('D' . $start_index)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$excel->getActiveSheet()->getStyle('D' . $start_index)->getNumberFormat()->setFormatCode('Rp #,##0');
			$excel->getActiveSheet()->getStyle('D' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('E' . $start_index, $part['harga_md_dealer']);
			$excel->getActiveSheet()->getStyle('E' . $start_index)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$excel->getActiveSheet()->getStyle('E' . $start_index)->getNumberFormat()->setFormatCode('Rp #,##0');
			$excel->getActiveSheet()->getStyle('E' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('F' . $start_index, $part['kelompok_part']);
			$excel->getActiveSheet()->getStyle('F' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('G' . $start_index, $part['status']);
			$excel->getActiveSheet()->getStyle('G' . $start_index)->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue('H' . $start_index, $part['qty']);
			$excel->getActiveSheet()->getStyle('H' . $start_index)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$excel->getActiveSheet()->getStyle('H' . $start_index)->getNumberFormat()->setFormatCode('#,##0');
			$excel->getActiveSheet()->getStyle('H' . $start_index)->applyFromArray($allborders);
			$start_index++;
			$no_index++;
		}

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
		ob_end_clean();
		$filename = sprintf('Report stock main dealer %s', Mcarbon::now()->format('d_m_Y'));

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function report_tanpa_lokasi_csv()
	{
		// $stock = $this->stock_int->qty_on_hand('p.id_part_int', null, true);
		$parts = $this->db
			->select('p.id_part')
			->select('p.nama_part')
			->select('p.harga_dealer_user as het')
			->select('p.harga_md_dealer as hpp')
			->select('p.kelompok_part')
			->select('p.status')
			// ->select("IFNULL(({$stock}), 0) as qty", false)
			->select('sp.qty')
			->from('tr_stok_part as sp')
			->join('ms_part as p', 'p.id_part_int = sp.id_part_int')
			// ->from('ms_part as p')
			->get()->result_array()
			;

		$delimiter = ";";
		$filename = "online_stock_md_" . date('Y-m-d') . ".csv";

		//create a file pointer
		$f = fopen('php://memory', 'w');

		//set column headers
		$fields = array_keys($parts[0]);
		fputcsv($f, $fields, $delimiter);

		//output each row of the data, format line as csv and write to file pointer
		foreach ($parts as $part) {
			$lineData = array("'" . $part['id_part'], $part['nama_part'], $part['het'], $part['hpp'], $part['kelompok_part'], $part['status'], $part['qty']);
			fputcsv($f, $lineData, $delimiter);
		}

		//move back to beginning of file
		fseek($f, 0);

		//set headers to download file rather than displayed
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '";');

		//output all remaining data on a file pointer
		fpassthru($f);
	}

	public function import()
	{
		$data['mode']    = "upload";
		// $data['set']     = "form_upload";
		$this->template($data);
	}

	//Untuk upload file rak 
	public function inject()
	{
		$lines = $this->upload_dan_baca_stock();
		$processed_data = $this->proses_stock($lines);

		$this->db->trans_begin();

		$all_valid = true;
		$validation_error = [];
		foreach ($processed_data as $data) {
			$this->form_validation->set_data($data);
			
			//Check apakah part ada atau tidak
			$check_master_part = $this->db->select('id_part_int')
										->from('ms_part')
										->where ('id_part',$data['id_part'])
										->get()->row_array();

			if($check_master_part['id_part_int'] != '' || $check_master_part['id_part_int'] != NULL){
				//Check apakah rak tersebut ada atau tidak di db dan ambil kode_lokasi_rak_int nya 
				$check_master_rak = $this->db->select('id')
									->from('ms_h3_md_lokasi_rak')
									->where ('kode_lokasi_rak',$data['kode_lokasi_rak'])
									->get()->row_array();

				//Hapus lokasi rak part sebelumnya 
				$this->db->where('id_part_int', $check_master_part['id_part_int']);
				$this->db->delete('tr_stok_part');


				//Check apakah ada part tersebut dilokasi rak tersebut
				// $check_kesesuaian_rak = $this->db->select(1)
				// 						->from('tr_stok_part')
				// 						->where('id_part_int',$check_master_part['id_part_int'])
				// 						->where('id_lokasi_rak',$check_master_rak['id'])
				// 						->get()->row_array();
				
			
				// if($check_kesesuaian_rak != '' || $check_kesesuaian_rak != NULL){
				// 	$this->db->set('qty', $data['qty']);
				// 	$this->db->where('id_part_int', $check_master_part['id_part_int']);
				// 	$this->db->where('id_lokasi_rak',$check_master_rak['id']);
				// 	$this->db->update('tr_stok_part');
				// 	var_dump($check_master_part['id_part_int']);
				// 	die();
				// }else{
				// 	$this->db->where('id_part_int', $check_master_part['id_part_int']);
				// 	$this->db->delete('tr_stok_part');

				// 	$data_stock = array(
				// 		'id_lokasi_rak' => $check_master_rak['id'],
				// 		'id_part' => $data['id_part'],
				// 		'id_part_int' => $check_master_part['id_part_int'],
				// 		'qty' => $data['qty'],
				// 		'created_at' => date('Y-m-d H:i:s')
				// 	);
				// 	$this->db->insert('tr_stok_part', $data_stock);
				// }

				if($check_master_rak['id'] != '' || $check_master_rak['id'] != NULL){
					$data_stock = array(
						'id_lokasi_rak' => $check_master_rak['id'],
						'id_part' => $data['id_part'],
						'id_part_int' => $check_master_part['id_part_int'],
						'qty' => $data['qty'],
						'created_at' => date('Y-m-d H:i:s')
					);
					$this->db->insert('tr_stok_part', $data_stock);
				}else{
					$data_stock = array(
						'id_lokasi_rak' => '452346',
						'id_part' => $data['id_part'],
						'id_part_int' => $check_master_part['id_part_int'],
						'qty' => $data['qty'],
						'created_at' => date('Y-m-d H:i:s')
					);
					$this->db->insert('tr_stok_part', $data_stock);
				}
				//Check apakah ada atau tidak kode part di stok_stok_part_summary
				$check_master_part_summary = $this->db->select(1)
											->from('tr_stok_part_summary')
											->where('id_part_int',$check_master_part['id_part_int'])
											->get()->row_array();

				if($check_master_part_summary!= '' || $check_master_part_summary != NULL){
					$this->db->set('qty', $data['qty']);
					$this->db->where('id_part_int', $check_master_part['id_part_int']);
					$this->db->update('tr_stok_part_summary');
				}else{
					$data_stock = array(
						'id_part' => $data['id_part'],
						'id_part_int' => $check_master_part['id_part_int'],
						'qty' => $data['qty']
					);
					$this->db->insert('tr_stok_part_summary', $data_stock);
				}
			}
		}

		if ($this->db->trans_status()) {
			$this->db->trans_commit();
			$this->session->set_userdata('pesan', 'File Lokasi Rak berhasil diupload.');
			$this->session->set_userdata('tipe', 'success');
			echo '<script>Berhasil update Data</script>';
		} else {
			$this->db->trans_rollback();
			$this->session->set_userdata('pesan', 'File Lokasi Rak tidak berhasil diupload.');
			$this->session->set_userdata('tipe', 'danger');
			echo '<script>Tidak Berhasil update Data</script>';
		}
	}

	public function upload_dan_baca_stock()
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

	public function proses_stock($stock)
	{
		$registedInvoiceNumber = [];
		$finalData = [];

		$keys = [
			'id_part', 'kode_lokasi_rak','qty'		
		];

		foreach ($stock as $line) {
			// Lakukan pemecahan berdasarkan panjang karakter yang telah ditentukan.
			$column = $this->parsing_stock($line);

			$index = 0;
			$subArr = [];
			foreach ($keys as $index => $key) {
				$subArr[$key] = trim($column[$index]);				
			}
			$finalData[] = $subArr;
		}

		return $finalData;
	}

	public function parsing_stock($line)
	{
		$blocks = explode(';', $line);
		array_pop($blocks);
		return $blocks;
	}

	public function get_serial_number()
	{
		$data = $this->db
			->select('et.fifo')
			->select('et.serial_number')
			->from('tr_h3_serial_ev_tracking as et')
			->where('et.accStatus', 2)
			->group_start()
				->where('et.no_penerimaan_barang_md !=', null)
				->or_where('et.no_penerimaan_barang_md !=', '')
			->group_end()
			->group_start()
				->where('et.id_do_sales_order', null)
				->or_where('et.id_do_sales_order', '')
			->group_end()
			->where('et.id_part', $this->input->get('id_part'))
			->get()->result_array();

		send_json($data);
	}
}
