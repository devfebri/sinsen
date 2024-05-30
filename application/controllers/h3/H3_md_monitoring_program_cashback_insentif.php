<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitoring_program_cashback_insentif extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_monitoring_program_cashback_insentif";
	protected $title  = "Monitoring Program Cashback dan Insentif";

	public function __construct()
	{
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('Mcarbon');
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

	public function download_excel(){
		ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

		$data = $this->get_data_for_export();

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excel = PHPExcel_IOFactory::load("assets/template/report_monitoring_program_cashback_insentif.xlsx");

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

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $data_start_row), Mcarbon::parse($row['tanggal_transaksi'])->format('d-m-Y'));
			$excel->getActiveSheet()->getStyle(sprintf('B%s', $data_start_row))->applyFromArray($border_all);
			
			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $data_start_row), $row['nama_program']);
			$excel->getActiveSheet()->getStyle(sprintf('C%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $data_start_row), sprintf('%s s.d %s', Mcarbon::parse($row['start_date'])->format('d-m-Y'), Mcarbon::parse($row['end_date'])->format('d-m-Y')));
			$excel->getActiveSheet()->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $data_start_row), $row['nama_dealer']);
			$excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $data_start_row), $row['total_bayar']);
			$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $data_start_row), $row['no_faktur']);
			$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $data_start_row), $row['nilai_claim']);
			$excel->getActiveSheet()->getStyle(sprintf('H%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $data_start_row), $row['kode_giro']);
			$excel->getActiveSheet()->getStyle(sprintf('I%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $data_start_row), Mcarbon::parse($row['tanggal_giro'])->format('d-m-Y'));
			$excel->getActiveSheet()->getStyle(sprintf('J%s', $data_start_row))->applyFromArray($border_all);

			$data_start_row++;
			$index++;
		}

		// $excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		ob_end_clean();
		$filename = 'Report Monitoring Program Cashback dan Insentif';
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename={$filename}.xlsx"); // Set nama file excel nya

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
		ob_end_clean();
	}

	private function get_data_for_export(){
		$this->db
		->select('do.tanggal as tanggal_transaksi')
		->select('sc.nama as nama_program')
		->select('
			case
				when sc.jenis_reward_poin = 1 then
					case
						when sc.start_date_poin is not null then start_date_poin
						else sc.start_date
					end
			end as start_date
		', false)
		->select('
			case
				when sc.jenis_reward_poin = 1 then
					case
						when sc.end_date_poin is not null then end_date_poin
						else sc.end_date
					end
			end as end_date
		', false)
		->select('d.nama_dealer')
		->select('ap.total_bayar')
		->select('ciscp.id_do_sales_order')
		->select('ps.no_faktur')
		->select('ciscp.nilai_claim')
		->select('vp.id_voucher_pengeluaran')
		->select('cg.kode_giro')
		->select('vp.tanggal_giro')
		->from('tr_h3_md_claim_insentif_sales_campaign_poin as ciscp')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = ciscp.id_do_sales_order')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order', 'left')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
		->join('tr_h3_md_ap_part as ap', 'ap.id = ciscp.id_ap_part')
		->join('ms_dealer as d', 'd.id_dealer = ap.id_dealer')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = ap.id_campaign')
		->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_referensi = ap.id', 'left')
		->join('tr_h3_md_voucher_pengeluaran as vp', '(vp.id_voucher_pengeluaran = vpi.id_voucher_pengeluaran AND vp.via_bayar = "Giro")', 'left')
		->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
		->order_by('ciscp.created_at', 'desc')
        ;

		return $this->db->get()->result_array();
	}
}
