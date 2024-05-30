<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitoring_jatuh_tempo_ahm extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_monitoring_jatuh_tempo_ahm";
	protected $title  = "Monitoring Jatuh Tempo AHM";

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
		$periode_filter_start = $this->input->get('periode_filter_start');
		$periode_filter_end = $this->input->get('periode_filter_end');
		$data = $this->get_data_for_export($periode_filter_start, $periode_filter_end);

		$total_dpp = array_map(function($row){
            if($row['top_dpp_filtered'] == 1){
                return floatval($row['total_dpp']);
            }
            return 0;
        }, $data);
        $total_dpp = array_sum($total_dpp);

        $total_ppn = array_map(function($row){
            if($row['top_ppn_filtered'] == 1){
                return floatval($row['total_ppn']);
            }
            return 0;
        }, $data);
        $total_ppn = array_sum($total_ppn);

        $grand_total = $total_dpp + $total_ppn;

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excel = PHPExcel_IOFactory::load("assets/template/report_jatuh_tempo_ahm_template.xlsx");

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

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $data_start_row), $row['invoice_date']);
			$excel->getActiveSheet()->getStyle(sprintf('B%s', $data_start_row))->applyFromArray($border_all);
			
			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $data_start_row), $row['invoice_number']);
			$excel->getActiveSheet()->getStyle(sprintf('C%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $data_start_row), $row['dpp_due_date']);
			$excel->getActiveSheet()->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $data_start_row), $row['ppn_due_date']);
			$excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $data_start_row), $row['total_dpp']);
			$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->applyFromArray($border_all);
			$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $data_start_row), $row['total_ppn']);
			$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->applyFromArray($border_all);
			$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $data_start_row), $row['kode_giro'] != null ? $row['kode_giro'] : '');
			$excel->getActiveSheet()->getStyle(sprintf('H%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $data_start_row), $row['total_amount']);
			$excel->getActiveSheet()->getStyle(sprintf('I%s', $data_start_row))->applyFromArray($border_all);
			$excel->getActiveSheet()->getStyle(sprintf('I%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

			$data_start_row++;
			$index++;
		}

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $data_start_row), 'Total');
		$excel->setActiveSheetIndex(0)->mergeCells(sprintf('A%s:E%s', $data_start_row, $data_start_row));
		$excel->getActiveSheet()->getStyle(sprintf('A%s:E%s', $data_start_row, $data_start_row))->applyFromArray($border_all);
		$excel->getActiveSheet()->getStyle(sprintf('A%s:E%s', $data_start_row, $data_start_row))->applyFromArray([
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			]
		]);

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $data_start_row), $total_dpp);
		$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->applyFromArray($border_all);
		$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $data_start_row), $total_dpp);
		$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->applyFromArray($border_all);
		$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		$data_start_row++;

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $data_start_row), 'Grand Total');
		$excel->setActiveSheetIndex(0)->mergeCells(sprintf('A%s:E%s', $data_start_row, $data_start_row));
		$excel->getActiveSheet()->getStyle(sprintf('A%s:E%s', $data_start_row, $data_start_row))->applyFromArray($border_all);
		$excel->getActiveSheet()->getStyle(sprintf('A%s:E%s', $data_start_row, $data_start_row))->applyFromArray([
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			]
		]);

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $data_start_row), $grand_total);
		$excel->setActiveSheetIndex(0)->mergeCells(sprintf('F%s:G%s', $data_start_row, $data_start_row));
		$excel->getActiveSheet()->getStyle(sprintf('F%s:G%s', $data_start_row, $data_start_row))->applyFromArray($border_all);
		$excel->getActiveSheet()->getStyle(sprintf('F%s:G%s', $data_start_row, $data_start_row))->applyFromArray([
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			]
		]);
		$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		ob_end_clean();
		$filename = 'Report Jatuh Tempo AHM';
		if($periode_filter_start != null AND $periode_filter_end != null){
			$periode_filter_start_formatted = date('d-m-Y', strtotime($periode_filter_start));
			$periode_filter_end_formatted = date('d-m-Y', strtotime($periode_filter_end));
			$filename .= " {$periode_filter_start_formatted} s.d {$periode_filter_end_formatted}";
		}
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename={$filename}.xlsx"); // Set nama file excel nya

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
		ob_end_clean();
	}

	public function get_data_for_export($periode_filter_start, $periode_filter_end){
		$this->db
        ->select('fdo.invoice_number')
        ->select('fdo.invoice_date')
        ->select('fdo.dpp_due_date')
        ->select('fdo.ppn_due_date')
        ->select('fdo.total_dpp')
        ->select('fdo.total_ppn')
        ->select('cg.kode_giro')
        ->select('IFNULL(vp.total_amount, 0) as total_amount', false)
        ->from('tr_h3_md_fdo as fdo')
        ->join('tr_h3_md_voucher_pengeluaran_items as vpi', '(vpi.id_referensi = fdo.invoice_number)', 'left')
        ->join('tr_h3_md_voucher_pengeluaran as vp', '(vp.id_voucher_pengeluaran = vpi.id_voucher_pengeluaran AND vp.via_bayar = "Giro")', 'left')
        ->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
        ;

        if($periode_filter_start != null AND $periode_filter_end != null){
            $this->db->select("fdo.dpp_due_date between '{$periode_filter_start}' AND '{$periode_filter_end}' as top_dpp_filtered", null, false);
            $this->db->select("fdo.ppn_due_date between '{$periode_filter_start}' AND '{$periode_filter_end}' as top_ppn_filtered", null, false);
        }else{
            $this->db->select('0 as top_dpp_filtered');
            $this->db->select('0 as top_ppn_filtered');
        }

		if($periode_filter_start != null AND $periode_filter_end != null){
            $this->db->group_start();
                $this->db->group_start();
                $this->db->where("fdo.dpp_due_date between '{$periode_filter_start}' AND '{$periode_filter_end}'", null, false);
                $this->db->group_end();

                $this->db->or_group_start();
                $this->db->where("fdo.ppn_due_date between '{$periode_filter_start}' AND '{$periode_filter_end}'", null, false);
                $this->db->group_end();
            $this->db->group_end();
        }

		return $this->db->get()->result_array();
	}
}
