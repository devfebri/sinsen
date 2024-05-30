<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_report_wilayah_penagihan_collector extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_report_wilayah_penagihan_collector";
	protected $title  = "Report Wilayah Penagihan Collector";

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
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	private function download_excel(){
		ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

		$tanggal_faktur_start = $this->input->get('tanggal_faktur_start');
		$tanggal_faktur_end = $this->input->get('tanggal_faktur_end');
		$tanggal_jatuh_tempo_start = $this->input->get('tanggal_jatuh_tempo_start');
		$tanggal_jatuh_tempo_end = $this->input->get('tanggal_jatuh_tempo_end');
		$data = $this->get_data_for_export($tanggal_faktur_start, $tanggal_faktur_end, $tanggal_jatuh_tempo_start, $tanggal_jatuh_tempo_end);

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excel = PHPExcel_IOFactory::load("assets/template/report_penerimaan_ekspedisi_template.xlsx");

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

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $data_start_row), $row['tanggal_penerimaan']);
			$excel->getActiveSheet()->getStyle(sprintf('B%s', $data_start_row))->applyFromArray($border_all);
			
			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $data_start_row), $row['no_penerimaan_barang']);
			$excel->getActiveSheet()->getStyle(sprintf('C%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $data_start_row), $row['nama_ekspedisi']);
			$excel->getActiveSheet()->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $data_start_row), $row['type_mobil']);
			$excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $data_start_row), $row['tgl_surat_jalan_ekspedisi']);
			$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $data_start_row), $row['no_surat_jalan_ekspedisi']);
			$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $data_start_row), $row['no_plat']);
			$excel->getActiveSheet()->getStyle(sprintf('H%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $data_start_row), $row['invoice_number']);
			$excel->getActiveSheet()->getStyle(sprintf('I%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $data_start_row), $row['surat_jalan_ahm']);
			$excel->getActiveSheet()->getStyle(sprintf('J%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $data_start_row), $row['packing_sheet_date']);
			$excel->getActiveSheet()->getStyle(sprintf('K%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $data_start_row), $row['packing_sheet_number']);
			$excel->getActiveSheet()->getStyle(sprintf('L%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('M%s', $data_start_row), $row['id_part']);
			$excel->getActiveSheet()->getStyle(sprintf('M%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('N%s', $data_start_row), $row['nama_part']);
			$excel->getActiveSheet()->getStyle(sprintf('N%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('O%s', $data_start_row), $row['qty_diterima']);
			$excel->getActiveSheet()->getStyle(sprintf('O%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('P%s', $data_start_row), $row['jumlah_koli']);
			$excel->getActiveSheet()->getStyle(sprintf('P%s', $data_start_row))->applyFromArray($border_all);

			$data_start_row++;
			$index++;
		}

		// $excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		ob_end_clean();
		$filename = 'Report Penerimaan Ekspedisi';
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename={$filename}.xlsx"); // Set nama file excel nya

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
		ob_end_clean();
	}

	private function get_data_for_export($tanggal_faktur_start, $tanggal_faktur_end, $tanggal_jatuh_tempo_start, $tanggal_jatuh_tempo_end){
		$this->db
        ->select('pb.tanggal_penerimaan')
        ->select('pb.no_penerimaan_barang')
        ->select('e.nama_ekspedisi')
        ->select('pb.type_mobil')
        ->select('pb.tgl_surat_jalan_ekspedisi')
        ->select('pb.no_surat_jalan_ekspedisi')
        ->select('pb.no_plat')
        ->select('pb.no_plat')
        ->select('fdo_ps.invoice_number')
        ->select('psli.surat_jalan_ahm')
        ->select('ps.packing_sheet_date')
        ->select('ps.packing_sheet_number')
        ->select('pbi.id_part')
        ->select('p.nama_part')
        ->select('pbi.qty_diterima')
        ->select('(pbi.qty_diterima/IFNULL(p.qty_dus, 1)) as jumlah_koli')
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = pbi.no_penerimaan_barang')
        ->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor')
        ->join('tr_h3_md_fdo_ps as fdo_ps', 'fdo_ps.packing_sheet_number = pbi.packing_sheet_number')
        ->join('tr_h3_md_ps as ps', 'pbi.packing_sheet_number = ps.packing_sheet_number')
        ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number = ps.packing_sheet_number')
        ->join('ms_part as p', 'p.id_part = pbi.id_part')
        ->where('pb.status', 'Closed')
        ->where('pbi.tersimpan', 1)
		->order_by('pb.created_at', 'desc')
        ;

		if($this->input->get('id_ekspedisi') != null){
            $this->db->where('pb.id_vendor', $this->input->get('id_ekspedisi'));
        }

		return $this->db->get()->result_array();
	}
}
