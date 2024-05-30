<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_sales_kelompok_part extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_sales_kelompok_part";
	var $title  = "Laporan Sales Kelompok Part";

	protected $excel;
	protected $monthLastIndex = 2;

	public function __construct()
	{		 
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name=="") echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$this->excel = new PHPExcel();

		$this->load->database();
		$this->load->model('m_admin');
		$this->load->library('Mcarbon');
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}


	public function generate(){
		if($this->input->get('type') == 'Excel'){
			$this->excel();
		}else if($this->input->get('type') == 'Pdf'){
			$this->pdf();
		}
	}

	private function get_unit_entry($kelompok_part, $firstDayOfMonth, $lastDayOfMonth){
		return $this->db
		->select('COUNT(wo.id_work_order) as kuantitas')
		->from('tr_h2_wo_dealer as wo')
		->group_start()
		->where("wo.created_at BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}'", null, false)
		->group_end()
		->where('wo.id_dealer', $this->m_admin->cari_dealer())
		->where('wo.status', 'closed')
		->get()->row_array();
	}

	private function get_sales_out($kelompok_part, $firstDayOfMonth, $lastDayOfMonth){
		return $this->db
		->select('IFNULL(
			SUM(sop.kuantitas),
			0
		) as kuantitas')
		->select('IFNULL(
			SUM(
				CASE
					WHEN sop.tipe_diskon = "Percentage" THEN (
						sop.kuantitas * (
							sop.harga_saat_dibeli - (
								(sop.diskon_value/100) * sop.harga_saat_dibeli
							)
						)
					)
					WHEN sop.tipe_diskon = "Value" THEN (
						sop.kuantitas * (
							sop.harga_saat_dibeli - sop.diskon_value
						)
					)
					ELSE (
						sop.kuantitas * sop.harga_saat_dibeli
					)
				END
			),
			0
		) as amount')
		->from('ms_kelompok_part as mkp')
		->join('ms_part as p', 'p.kelompok_part = mkp.kelompok_part')
		->join('tr_h3_dealer_sales_order_parts as sop', 'sop.id_part_int = p.id_part_int')
		->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = sop.nomor_so')
		->where('mkp.kelompok_part', $kelompok_part)
		->where("so.tanggal_so BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}'", null, false)
		->where('so.status', 'Closed')
		// ->where('so.id_work_order !=', null)
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array()
		// ->get_compiled_select()
		;
	}

	public function pdf()
	{
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$this->db
		->select('kp.kelompok_part')
		->from('ms_kelompok_part as kp')
		->order_by('kp.id_kelompok_part', 'asc');

		if($this->input->get('filter_kelompok_part') != null and count($this->input->get('filter_kelompok_part')) > 0){
			$this->db->where_in('kp.id_kelompok_part', $this->input->get('filter_kelompok_part'));
		}else{
			$this->db->where('1 = 0', null, false);
		}

		$kelompok_parts = $this->db->get()->result_array();

		$start_date = Mcarbon::parse($this->input->get('start_date'));
        $end_date = Mcarbon::parse($this->input->get('end_date'));
		$month_diff = $end_date->diffInMonths($start_date);
		$monthRange = range(0, $month_diff);
		$chunkMonthRange = array_chunk($monthRange, 2);

		$data = [];
		foreach ($chunkMonthRange as $chunk) {
			$month_per_chunk = [];
			foreach ($kelompok_parts as $kelompok_part) {
				foreach ($chunk as $month) {
					$payload_per_month = [];
					$thisMonth = Mcarbon::parse($this->input->get('start_date'));
					$thisMonth = $thisMonth->addMonths($month);
	
					$payload_per_month['label_month'] = $thisMonth->format('F Y');
					$payload_per_month['first_day_of_month'] = $thisMonth->startOfMonth()->format('Y-m-d 00:00:01');
					$payload_per_month['last_day_of_month'] = $thisMonth->endOfMonth()->format('Y-m-d 23:59:59');

					$three_months_before = Mcarbon::parse($payload_per_month['first_day_of_month']);
					$three_months_before = $three_months_before->subMonths(3);
					$payload_per_month['three_months_before'] = $three_months_before->format('Y-m-d 23:59:59');
	
					$payload_per_month['unit_entry'] = $this->get_unit_entry($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
					$payload_per_month['sales_out'] = $this->get_sales_out($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);

					if($payload_per_month['unit_entry']['kuantitas'] == 0){
						$payload_per_month['ach'] = 0;
					}else{
						$payload_per_month['ach'] = $payload_per_month['sales_out']['kuantitas'] / $payload_per_month['unit_entry']['kuantitas'];
					}
					
					$kelompok_part['payloads'][] = $payload_per_month;
				}
				$month_per_chunk[] = $kelompok_part;
			}
			
			$data[] = $month_per_chunk;
		}

		$html = $this->load->view('dealer/h3_dealer_laporan_sales_kelompok_part_pdf', [
			'data' => $data
		], true);
		
		// render the view into HTML
		$mpdf->addPage('L');
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel()
	{		
		$this->db
		->select('kp.kelompok_part')
		->from('ms_kelompok_part as kp')
		->order_by('kp.id_kelompok_part', 'asc');

		if($this->input->get('filter_kelompok_part') != null and count($this->input->get('filter_kelompok_part')) > 0){
			$this->db->where_in('kp.id_kelompok_part', $this->input->get('filter_kelompok_part'));
		}else{
			$this->db->where('1 = 0', null, false);
		}

		$kelompok_parts = $this->db->get()->result_array();

		$start_date = Mcarbon::parse($this->input->get('start_date'));
        $end_date = Mcarbon::parse($this->input->get('end_date'));
		$month_diff = $end_date->diffInMonths($start_date);
		$monthRange = range(0, $month_diff);

		$data = [];
		foreach ($kelompok_parts as $kelompok_part) {
			$payloads = [];
			foreach ($monthRange as $month) {
				$payload_per_month = [];
				$thisMonth = Mcarbon::parse($this->input->get('start_date'));
				$thisMonth = $thisMonth->addMonths($month);

				$payload_per_month['label_month'] = $thisMonth->format('F Y');
				$payload_per_month['first_day_of_month'] = $thisMonth->startOfMonth()->format('Y-m-d 00:00:01');
				$payload_per_month['last_day_of_month'] = $thisMonth->endOfMonth()->format('Y-m-d 23:59:59');

				$three_months_before = Mcarbon::parse($payload_per_month['first_day_of_month']);
				$three_months_before = $three_months_before->subMonths(3);
				$payload_per_month['three_months_before'] = $three_months_before->format('Y-m-d 23:59:59');

				$payload_per_month['unit_entry'] = $this->get_unit_entry($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
				$payload_per_month['sales_out'] = $this->get_sales_out($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);

				if($payload_per_month['unit_entry']['kuantitas'] == 0){
					$payload_per_month['ach'] = 0;
				}else{
					$payload_per_month['ach'] = $payload_per_month['sales_out']['kuantitas'] / $payload_per_month['unit_entry']['kuantitas'];
				}
				
				$payloads[] = $payload_per_month;
			}
			$kelompok_part['payloads'] = $payloads;
			$data[] = $kelompok_part;
		}

        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Stok versi Kelompok");

		$start_date = date('d/m/Y', strtotime($this->input->get('start_date')));
		$end_date = date('d/m/Y', strtotime($this->input->get('end_date')));
		$this->excel->setActiveSheetIndex(0)->setCellValue('A2', 'Laporan Sales Kelompok Parts');
		$this->excel->setActiveSheetIndex(0)->setCellValue('A4', "{$start_date} - {$end_date}");

		$this->excel->setActiveSheetIndex(0)->setCellValue('A5', 'NO');
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->mergeCells("A5:A6");
		$this->excel->getActiveSheet()->getStyle("A5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("A5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		
		$this->excel->setActiveSheetIndex(0)->setCellValue('B5', 'Kode Kelompok');
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->mergeCells("B5:B6");
		$this->excel->getActiveSheet()->getStyle("B5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("B5:B6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("B5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$startIndex = 7;
		$loop_index = 1;
		foreach ($data as $row):
			$this->excel->setActiveSheetIndex(0)->setCellValue("A{$startIndex}", $loop_index);
			$this->excel->getActiveSheet()->getStyle("A{$startIndex}")->applyFromArray([
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue("B{$startIndex}", $row['kelompok_part']);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			foreach ($row['payloads'] as $payload) {
				$this->monthSection($payload, $loop_index);
			}
			$this->monthLastIndex = 2;
			$startIndex++;
			$loop_index++;
		endforeach;

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Laporan Stok versi Kelompok");
		$this->excel->setActiveSheetIndex(0);
		
        $this->download();
	}

	public function monthSection($payload, $loop_index){
		$monthColumnStart = $this->getColumnLetter($this->monthLastIndex);
		$monthLastIndex = $this->monthLastIndex + 3;
		$monthColumnEnd = $this->getColumnLetter($monthLastIndex);

		$this->excel->setActiveSheetIndex(0)->setCellValue($monthColumnStart . 5, $payload['label_month']);
		$this->excel->getActiveSheet()->mergeCells("{$monthColumnStart}5:{$monthColumnEnd}5");
		$this->excel->getActiveSheet()->getStyle($monthColumnStart . 5)->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$monthColumnStart}5:{$monthColumnEnd}5")->applyFromArray($this->borderThinStyle());
		
		$unitEntryColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$unitEntryColumn}6", "UE");
		$this->excel->getActiveSheet()->getStyle("{$unitEntryColumn}6")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$unitEntryColumn}6")->applyFromArray($this->borderThinStyle());

		$qtySalesColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtySalesColumn}6", "Qty");
		$this->excel->getActiveSheet()->getStyle("{$qtySalesColumn}6")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtySalesColumn}6")->applyFromArray($this->borderThinStyle());

		$amountSalesColumn = $this->getColumnLetter($this->monthLastIndex + 2);
		$this->excel->getActiveSheet()->getColumnDimension($amountSalesColumn)->setWidth(14);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountSalesColumn}6", "Amount Sales");
		$this->excel->getActiveSheet()->getStyle("{$amountSalesColumn}6")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountSalesColumn}6")->applyFromArray($this->borderThinStyle());

		$achColumn = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$achColumn}6", "% ACH");
		$this->excel->getActiveSheet()->getStyle("{$achColumn}6")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$achColumn}6")->applyFromArray($this->borderThinStyle());


		$unitEntryValueColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$unitEntryValueColumn}" . (7 + $loop_index - 1), $payload['unit_entry']['kuantitas']);
		$this->excel->getActiveSheet()->getStyle("{$unitEntryValueColumn}" . (7 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$qtyValueColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueColumn}" . (7 + $loop_index - 1), $payload['sales_out']['kuantitas']);
		$this->excel->getActiveSheet()->getStyle("{$qtyValueColumn}" . (7 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$amountSalesValueColumn = $this->getColumnLetter($this->monthLastIndex + 2);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountSalesValueColumn}" . (7 + $loop_index - 1), $payload['sales_out']['amount']);
		$this->excel->getActiveSheet()->getStyle("{$amountSalesValueColumn}" . (7 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$achValueColumn = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$achValueColumn}" . (7 + $loop_index - 1), $payload['ach']);
		$this->excel->getActiveSheet()->getStyle("{$achValueColumn}" . (7 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$this->monthLastIndex = $monthLastIndex + 1;
	}

	public function download(){
		ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Sales Kelompok Parts.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $write->save('php://output');
        ob_end_clean();
	}

	function getColumnLetter( $number ){
		$prefix = '';
		$suffix = '';
		$prefNum = intval( $number/26 );
		if( $number > 25 ){
			$prefix = $this->getColumnLetter( $prefNum - 1 );
		}
		$suffix = chr( fmod( $number, 26 )+65 );
		return $prefix.$suffix;
	}
	
	public function headerStyle(){
		return  [
			'font' => [
				// 'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE,
				'size'  => 10,
				'name'  => 'Tahoma'
			],
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f9fc3d')
			),
		];
	}

	public function borderThinStyle(){
		return  [
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				)
			)
		];
	}

	public function getMonthName($month){
		$dateObj = DateTime::createFromFormat('!m', $month);
		return $dateObj->format('F');
	}
}