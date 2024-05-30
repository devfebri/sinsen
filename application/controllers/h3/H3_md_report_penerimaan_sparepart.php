<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_report_penerimaan_sparepart extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_report_penerimaan_sparepart";
	protected $title  = "Report Penerimaan Sparepart";

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
		if ($name == "" OR $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('h3_md_penagihan_pihak_kedua_model', 'penagihan_pihak_kedua');
		$this->load->model('h3_md_penagihan_pihak_kedua_tujuan_model', 'penagihan_pihak_kedua_tujuan');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function download_excel(){
		ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

		$tanggal_faktur_start = $this->input->get('tanggal_faktur_start');
		$tanggal_faktur_end = $this->input->get('tanggal_faktur_end');
		$tanggal_jatuh_tempo_start = $this->input->get('tanggal_jatuh_tempo_start');
		$tanggal_jatuh_tempo_end = $this->input->get('tanggal_jatuh_tempo_end');
		$data = $this->get_data_for_export($tanggal_faktur_start, $tanggal_faktur_end, $tanggal_jatuh_tempo_start, $tanggal_jatuh_tempo_end);

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excel = PHPExcel_IOFactory::load("assets/template/report_penerimaan_sparepart_template.xlsx");

		$data_start_row = 4;
		$border_all = [
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				),
			)
		];
		$index = 1;
		foreach($data as $row){
			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $data_start_row), $index);
			$excel->getActiveSheet()->getStyle(sprintf('A%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $data_start_row), $row['invoice_number']);
			$excel->getActiveSheet()->getStyle(sprintf('B%s', $data_start_row))->applyFromArray($border_all);
			
			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $data_start_row), $row['invoice_date']);
			$excel->getActiveSheet()->getStyle(sprintf('C%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $data_start_row), $row['tanggal_jatuh_tempo']);
			$excel->getActiveSheet()->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $data_start_row), $row['id_part']);
			$excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $data_start_row), $row['nama_part']);
			$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $data_start_row), $row['quantity']);
			$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $data_start_row), $row['price']);
			$excel->getActiveSheet()->getStyle(sprintf('H%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $data_start_row), $row['diskon']);
			$excel->getActiveSheet()->getStyle(sprintf('I%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $data_start_row), $row['ppn']);
			$excel->getActiveSheet()->getStyle(sprintf('J%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $data_start_row), $row['total_harga']);
			$excel->getActiveSheet()->getStyle(sprintf('K%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $data_start_row), $row['no_penerimaan_barang']);
			$excel->getActiveSheet()->getStyle(sprintf('L%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('M%s', $data_start_row), $row['tanggal_penerimaan']);
			$excel->getActiveSheet()->getStyle(sprintf('M%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('N%s', $data_start_row), $row['no_plat']);
			$excel->getActiveSheet()->getStyle(sprintf('N%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('O%s', $data_start_row), $row['nama_ekspedisi']);
			$excel->getActiveSheet()->getStyle(sprintf('O%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('P%s', $data_start_row), $row['no_surat_jalan_ekspedisi']);
			$excel->getActiveSheet()->getStyle(sprintf('P%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('Q%s', $data_start_row), $row['tgl_surat_jalan_ekspedisi']);
			$excel->getActiveSheet()->getStyle(sprintf('Q%s', $data_start_row))->applyFromArray($border_all);

			$data_start_row++;
			$index++;
		}

		// $excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		ob_end_clean();
		$filename = 'Report Penerimaan Sparepart';
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename={$filename}.xlsx"); // Set nama file excel nya

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
		ob_end_clean();
	}

	public function get_data_for_export($tanggal_faktur_start, $tanggal_faktur_end, $tanggal_jatuh_tempo_start, $tanggal_jatuh_tempo_end){
		$this->db
        ->select('fdo.invoice_number')
        ->select('fdo.invoice_date')
        ->select('fdo.dpp_due_date as tanggal_jatuh_tempo')
        ->select('fdo_parts.id_part')
        ->select('p.nama_part')
        ->select('fdo_parts.price')
        ->select('pbi.qty_diterima as quantity')
        ->select('( ROUND((fdo_parts.disc_campaign/fdo_parts.quantity), 2) + ROUND((fdo_parts.disc_insentif/fdo_parts.quantity), 2)) as diskon')
        ->select('ROUND( (fdo_parts.ppn/fdo_parts.quantity),  2) as ppn')
        ->select('( ROUND((fdo_parts.dpp/fdo_parts.quantity), 2) + ROUND((fdo_parts.ppn/fdo_parts.quantity), 2)) as total_harga')
        ->select('pbi.no_penerimaan_barang')
        ->select('pb.tanggal_penerimaan')
        ->select('pb.no_plat')
        ->select('e.nama_ekspedisi')
        ->select('pb.no_surat_jalan_ekspedisi')
        ->select('pb.tgl_surat_jalan_ekspedisi')
        ->from('tr_h3_md_fdo_parts as fdo_parts')
        ->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = fdo_parts.invoice_number')
        ->join('ms_part as p', 'p.id_part = fdo_parts.id_part')
        ->from('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part = fdo_parts.id_part AND pbi.packing_sheet_number = fdo_parts.nomor_packing_sheet)')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = pbi.no_penerimaan_barang')
        ->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor')
        ->where('pbi.tersimpan', 1)
        ->where('pb.status', 'Closed')
		->order_by('fdo.invoice_number', 'asc')
		->order_by('fdo.created_at', 'desc')
        ;

		if($tanggal_faktur_start != null AND $tanggal_faktur_end != null){
            $this->db->group_start();
            $this->db->where("fdo.invoice_date between '{$tanggal_faktur_start}' AND '{$tanggal_faktur_end}'", null, false);
            $this->db->group_end();
        }

		return $this->db->get()->result_array();
	}
}
