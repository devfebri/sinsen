<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_back_order_ahm extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_back_order_ahm";
	protected $title  = "Back Order";

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
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_purchase_order_model', 'purchase_order');
		$this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_back_order_ahm_upload_model', 'back_order_ahm_upload');
		$this->load->model('H3_md_back_order_ahm_item_upload_model', 'back_order_ahm_item_upload');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}


	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->select('po.id_purchase_order')
		->select('po.jenis_po')
		->select('date_format(po.tanggal_po, "%d/%m/%Y") as tanggal_po')
		->select('po.id_purchase_order as no_bo_ahm')
		->select('po.sudah_back_order')
		->from('tr_h3_md_purchase_order as po')
		->join('tr_h3_md_back_order_ahm_upload as bo', 'bo.no_po = po.id_purchase_order', 'left')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->row();

		$qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_penerimaan_barang_items as pbi')
		->where('pbi.no_po = pop.id_purchase_order', null, false)
		->where('pbi.id_part = pop.id_part', null, false)
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('pop.qty_order')
		->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan", false)
		->select("(pop.qty_order - IFNULL(({$qty_penerimaan}), 0)) as qty_back_order", false)
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$penerimaan = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('psp.no_doos')
		->select('psp.packing_sheet_quantity as qty_sl')
		->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as tanggal_sl')
		->select('IFNULL(pbi.qty_diterima, 0) as qty_terima')
		->select('date_format(pb.created_at, "%d/%m/%Y") as tanggal_terima')
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->join('tr_h3_md_ps_parts as psp', '(psp.id_part = pop.id_part and psp.no_po = pop.id_purchase_order)')
		->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = psp.packing_sheet_number')
		->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part = psp.id_part and pbi.packing_sheet_number = psp.packing_sheet_number and pbi.no_po = pop.id_purchase_order)', 'left')
		->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = pbi.no_penerimaan_barang', 'left')
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$data['penerimaan'] = $penerimaan;

		$this->template($data);
	}

	public function upload(){
		$data['mode']    = 'upload';
		$data['set']     = "form";

		$this->template($data);
	}

	public function store_upload(){
		$upload_dir = './uploads/bo_ahm_upload/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		$config['upload_path'] = $upload_dir;
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
			$data = $this->read_excel($this->upload->data()['file_name']);
			$header = $data['header'];
			$parts = $data['parts'];

			$back_order_ahm_upload = $this->back_order_ahm_upload->find($header['no_bo_ahm'], 'no_bo_ahm');

			if($back_order_ahm_upload != null){
				$condition = [
					'no_bo_ahm' => $header['no_bo_ahm']
				];

				$this->back_order_ahm_upload->update([
					'tgl_bo' => $header['tgl_bo'],
					'no_po' => $header['no_po'],
					'tipe_po' => $header['tipe_po'],
					'bulan_po' => $header['bulan_po'],
					'updated_at' => date('Y-m-d H:i:s', time()),
					'updated_by' => $this->session->userdata('id_user')
				], $condition);
				$this->back_order_ahm_item_upload->update_batch($data['parts'], $condition);
			}else{
				$this->back_order_ahm_upload->insert($data['header']);
				$this->back_order_ahm_item_upload->insert_batch($data['parts']);
			}
		}
	}

	public function read_excel($filename){
        include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        $filepath = "./uploads/bo_ahm_upload/{$filename}";
        try {
            $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($filepath,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$header = [
			'no_bo_ahm' => $sheet->getCell('C1')->getValue(),
			'tgl_bo' => $sheet->getCell('C2')->getValue(),
			'no_po' => $sheet->getCell('C3')->getValue(),
			'tipe_po' => $sheet->getCell('C4')->getValue(),
			'bulan_po' => $sheet->getCell('C5')->getValue(),
		];
		$tgl_bo = $sheet->getCell('C2');

		if (PHPExcel_Shared_Date::isDateTime($tgl_bo) && $tgl_bo->getValue() != null) {
			$unixTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($tgl_bo->getValue());
			$tgl_bo = date('Y-m-d', $unixTimeStamp);
		}else{
			$tgl_bo = null;
		}
		$header['tgl_bo'] = $tgl_bo;

		$parts = [];
        for ($row = 9; $row <= $highestRow; $row++){ 
			$row_data = [];
			$no = $sheet->getCell("A{$row}")->getValue();
			if($no == null || $no == '') continue;
			$row_data['id_part'] = $sheet->getCell("C{$row}")->getValue();
			$row_data['qty_po'] = $sheet->getCell("E{$row}")->getValue();
			$row_data['qty_supply'] = $sheet->getCell("F{$row}")->getValue();
			$row_data['qty_bo'] = $sheet->getCell("G{$row}")->getValue();
			$row_data['no_bo_ahm'] = $header['no_bo_ahm'];

			$parts[] = $row_data;
		}

		return [
			'header' => $header,
			'parts' => $parts,
		];
	}

	public function po_expired(){
		$data['mode']    = 'po_expired';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->select('po.id_purchase_order')
		->select('po.jenis_po')
		->select('date_format(po.tanggal_po, "%d/%m/%Y") as tanggal_po')
		->select('"-" as no_bo_ahm')
		->from('tr_h3_md_purchase_order as po')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->limit(1)
		->get()->row();

		$qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_penerimaan_barang_items as pbi')
		->where('pbi.no_po = pop.id_purchase_order', null, false)
		->where('pbi.id_part = pop.id_part', null, false)
		->get_compiled_select();

		$parts = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('p.kelompok_part')
		->select('pop.qty_order')
		->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan", false)
		->select("(pop.qty_order - IFNULL(({$qty_penerimaan}), 0)) as qty_back_order", false)
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function download_bo_template(){
		$this->load->helper('download');
		force_download('assets/template/bo_ahm_template.xlsx', NULL);
	}

	public function create_new_po(){
		$purchase = $this->db
		->select('po.*')
		->select('po.id_purchase_order as id_purchase_order_lama')
		->from('tr_h3_md_purchase_order as po')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->limit(1)
		->get()->row_array();
		unset($purchase['id']);
		$purchase['tanggal_po'] = date('Y-m-d', time());
		$purchase['status'] = 'Open';
		$purchase['approved_at'] = null;
		$purchase['approved_by'] = null;


		$qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_penerimaan_barang_items as pbi')
		->where('pbi.no_po = pop.id_purchase_order', null, false)
		->where('pbi.id_part = pop.id_part', null, false)
		->get_compiled_select();

		$parts = $this->db
		->select('pop.*')
		->select("(pop.qty_order - IFNULL(({$qty_penerimaan}), 0)) as qty_order", false)
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$purchase['id_purchase_order'] = $this->purchase_order->generateID($purchase['jenis_po']);
		$parts = array_map(function($row) use ($purchase){
			unset($row['id']);
			$row['id_purchase_order'] = $purchase['id_purchase_order'];
			return $row;
		}, $parts);

		$purchase['total_amount'] = array_sum(
			array_map(function($row){
				return floatval($row['qty_order']) * floatval($row['harga']);
			}, $parts)
		);

		$this->db->trans_start();
		$this->db
		->set('po.sudah_back_order', 1)
		->set('po.status', 'Closed')
		->set('po.closed_at', date('Y-m-d H:i:s', time()))
		->set('po.closed_by', $this->session->userdata('id_user'))
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->update('tr_h3_md_purchase_order as po');
		$this->purchase_order->insert($purchase);
		$this->purchase_order_parts->insert_batch($parts);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$purchase = $this->purchase_order->find($purchase['id_purchase_order'], 'id_purchase_order');
			send_json($purchase);
		}else{
			send_json([
				'validation_error' => 'not_created',
				'message' => 'Gagal membuat PO baru.'
			], 422);
		}
	}
}