<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_pendapatan_ahass extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_pendapatan_ahass";
	var $title  = "Laporan Pendapatan AHASS";

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
		$start_date = Mcarbon::parse($this->input->get('start_date'));
		$end_date = Mcarbon::parse($this->input->get('end_date'));

		$diffInMonths = $end_date->diffInMonths($start_date);
		$monthRange = range(0, $diffInMonths);

		$pendapatan = [];
		foreach ($monthRange as $month) {
			$dateObj = Mcarbon::parse($this->input->get('start_date'));
			$dateObj = $dateObj->addMonths($month);
			$firstDayOfMonth = $dateObj->startOfMonth()->format('Y-m-d 00:00:01');
			$lastDayOfMonth = $dateObj->endOfMonth()->format('Y-m-d 23:59:59');
			$data = [];

			$data['label_month'] = $dateObj->format('F Y');

			$data['ue'] = $this->db
			->select('COUNT(wo.id_work_order) as count')
			->from('tr_h2_wo_dealer as wo')
			->group_start()
			->where("wo.created_at BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}'", null, false)
			->group_end()
			->where('wo.id_dealer', $this->m_admin->cari_dealer())
			->where('wo.status', 'closed')
			->get()->row()->count;

			$this->db->start_cache();
			$this->db
			->select('
				IFNULL(
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
				) AS total
			', false)
			// ->select('so.nomor_so')
			// ->select('date_format(so.tanggal_so, "%d/%m/%Y") as tanggal_so')
			// ->select('sop.id_part')
			// ->select('p.kelompok_part')
			// ->select('sop.harga_saat_dibeli')
			// ->select('sop.kuantitas')
			// ->select('so.status')
			->from('tr_h3_dealer_sales_order_parts as sop')
			->join('tr_h3_dealer_sales_order as so', 'sop.nomor_so = so.nomor_so')
			->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
			->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
			->group_start()
			->where("so.tanggal_so BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}'", null, false)
			->group_end()
			->where('so.id_dealer', $this->m_admin->cari_dealer())
			->where('so.status', 'Closed');
			$this->db->stop_cache();

			$this->db->where('skp.produk', 'Parts');
			$data['parts'] = $this->db->get()->row()->total;

			$this->db->where('skp.produk', 'Oil');
			$data['oil'] = $this->db->get()->row()->total;

			$this->db->flush_cache();

			$data['jasa'] = $this->db
			->select('
				IFNULL(
					SUM(wodp.harga), 
					0
				) AS total
			')
			->from('tr_h2_wo_dealer_pekerjaan as wodp')
			->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = wodp.id_work_order')
			->group_start()
			->where("wo.created_at BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}'", null, false)
			->group_end()
			->where('wo.id_dealer', $this->m_admin->cari_dealer())
			->where('wo.status', 'closed')
			->get()->row()->total;
			$pendapatan[] = $data;
		}

		if($this->input->get('type') == 'Excel'){
			$this->excel($pendapatan);
		}else if($this->input->get('type') == 'Pdf'){
			$this->pdf($pendapatan);
		}
	}

	public function pdf($pendapatan)
	{
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$data = [
			'pendapatan' => $pendapatan
		];

		$html = $this->load->view('dealer/h3_dealer_laporan_pendapatan_ahass_pdf', $data, true);
		
		// render the view into HTML
		$mpdf->addPage('L');
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel($pendapatan)
	{		
        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Pendapatan AHASS");

		$monthStart = date('d/m/Y', strtotime($this->input->get('start_date')));
		$monthEnd = date('d/m/Y', strtotime($this->input->get('end_date')));
		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', "Periode : {$monthStart} - {$monthEnd}");
		$this->excel->getActiveSheet()->mergeCells("A1:F1");
		$this->excel->getActiveSheet()->getStyle("A1")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
		]);

		
		$this->excel->setActiveSheetIndex(0)->setCellValue('A2', 'Laporan Pendapatan AHASS');
		$this->excel->getActiveSheet()->mergeCells("A2:F2");
		$this->excel->getActiveSheet()->getStyle("A2")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
		]);


		$this->excel->setActiveSheetIndex(0)->setCellValue("A4", 'No');
		$this->excel->getActiveSheet()->mergeCells("A4:A5");
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->setActiveSheetIndex(0)->setCellValue("B4", 'Bulan');
		$this->excel->getActiveSheet()->mergeCells("B4:B5");
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(27);

		$this->excel->setActiveSheetIndex(0)->setCellValue("C4", 'Pendapatan AHASS');
		$this->excel->getActiveSheet()->mergeCells("C4:F4");

		$this->excel->setActiveSheetIndex(0)->setCellValue("C5", 'UE');
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
		$this->excel->setActiveSheetIndex(0)->setCellValue("D5", 'Parts');
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
		$this->excel->setActiveSheetIndex(0)->setCellValue("E5", 'Oli');
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(11);
		$this->excel->setActiveSheetIndex(0)->setCellValue("F5", 'Jasa');
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
		

		$headers = [
			"A4:A5","B4:B5", "C4:F4","C5","D5",
			"E5","F5",
		];

		foreach ($headers as $header) {
			$this->excel->getActiveSheet()->getStyle($header)->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$this->excel->getActiveSheet()->getStyle($header)->getFont()->setBold( true );
		}

		$startIndex = 6;
		$loop_index = 1;
		foreach ($pendapatan as $key => $value):
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
			$this->excel->setActiveSheetIndex(0)->setCellValue("B{$startIndex}", $key);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("C{$startIndex}", $value['ue']);
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			
			$this->excel->setActiveSheetIndex(0)->setCellValue("D{$startIndex}", $value['parts']);
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->getNumberFormat()->setFormatCode('Rp #,##0');

			$this->excel->setActiveSheetIndex(0)->setCellValue("E{$startIndex}", $value['oli']);
			$this->excel->getActiveSheet()->getStyle("E{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("E{$startIndex}")->getNumberFormat()->setFormatCode('Rp #,##0');


			$this->excel->setActiveSheetIndex(0)->setCellValue("F{$startIndex}", $value['jasa']);
			$this->excel->getActiveSheet()->getStyle("F{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("F{$startIndex}")->getNumberFormat()->setFormatCode('Rp #,##0');
			$startIndex++;
			$loop_index++;
		endforeach;

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Laporan Pendapatan AHASS");
		$this->excel->setActiveSheetIndex(0);
		
        $this->download();
	}

	public function monthSection($month, $loop_index, $sales_in, $sales_out, $stock, $sales_out_3_month){
		$monthColumnStart = $this->getColumnLetter($this->monthLastIndex);
		$monthLastIndex = ($this->monthLastIndex + 6);
		$monthColumnEnd = $this->getColumnLetter($monthLastIndex);

		$this->excel->setActiveSheetIndex(0)->setCellValue($monthColumnStart . 1, $this->getMonthName($month));
		$this->excel->getActiveSheet()->mergeCells("{$monthColumnStart}1:{$monthColumnEnd}1");
		$this->excel->getActiveSheet()->getStyle($monthColumnStart . 1)->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$monthColumnStart}1:{$monthColumnEnd}1")->applyFromArray($this->borderThinStyle());

		$salesInColumnStart = $this->getColumnLetter($this->monthLastIndex);
		$salesInColumnEnd = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$salesInColumnStart}2", "Sales In");
		$this->excel->getActiveSheet()->mergeCells("{$salesInColumnStart}2:{$salesInColumnEnd}2");
		$this->excel->getActiveSheet()->getStyle("{$salesInColumnStart}2")->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$salesInColumnStart}2:{$salesInColumnEnd}2")->applyFromArray($this->borderThinStyle());

		$salesOutColumnStart = $this->getColumnLetter($this->monthLastIndex + 2);
		$salesOutColumnEnd = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$salesOutColumnStart}2", "Sales Out");
		$this->excel->getActiveSheet()->mergeCells("{$salesOutColumnStart}2:{$salesOutColumnEnd}2");
		$this->excel->getActiveSheet()->getStyle("{$salesOutColumnStart}2")->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$salesOutColumnStart}2:{$salesOutColumnEnd}2")->applyFromArray($this->borderThinStyle());


		$stokColumnStart = $this->getColumnLetter($this->monthLastIndex + 4);
		$stokColumnEnd = $this->getColumnLetter($this->monthLastIndex + 5);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$stokColumnStart}2", "Stok");
		$this->excel->getActiveSheet()->mergeCells("{$stokColumnStart}2:{$stokColumnEnd}2");
		$this->excel->getActiveSheet()->getStyle("{$stokColumnStart}2")->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$stokColumnStart}2:{$stokColumnEnd}2")->applyFromArray($this->borderThinStyle());

		$slColumnStart = $this->getColumnLetter($this->monthLastIndex + 6);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$slColumnStart}2", "S/L");
		$this->excel->getActiveSheet()->getStyle("{$slColumnStart}2")->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$slColumnStart}2:{$slColumnStart}3")->applyFromArray($this->borderThinStyle());

		$this->excel->getActiveSheet()->mergeCells("{$slColumnStart}2:{$slColumnStart}3");

		$this->excel->getActiveSheet()->getStyle("{$slColumnStart}2")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);


		$qtySalesInColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtySalesInColumn}3", "Qty");
		$this->excel->getActiveSheet()->getStyle("{$qtySalesInColumn}3")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtySalesInColumn}3")->applyFromArray($this->borderThinStyle());
		$amountSalesInColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountSalesInColumn}3", "Amount");
		$this->excel->getActiveSheet()->getStyle("{$amountSalesInColumn}3")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountSalesInColumn}3")->applyFromArray($this->borderThinStyle());

		$qtySalesOutColumn = $this->getColumnLetter($this->monthLastIndex + 2);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtySalesOutColumn}3", "Qty");
		$this->excel->getActiveSheet()->getStyle("{$qtySalesOutColumn}3")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtySalesOutColumn}3")->applyFromArray($this->borderThinStyle());

		$amountSalesOutColumn = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountSalesOutColumn}3", "Amount");
		$this->excel->getActiveSheet()->getStyle("{$amountSalesOutColumn}3")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountSalesOutColumn}3")->applyFromArray($this->borderThinStyle());

		$qtyStokColumn = $this->getColumnLetter($this->monthLastIndex + 4);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyStokColumn}3", "Qty");
		$this->excel->getActiveSheet()->getStyle("{$qtyStokColumn}3")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyStokColumn}3")->applyFromArray($this->borderThinStyle());

		$amountStokColumn = $this->getColumnLetter($this->monthLastIndex + 5);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountStokColumn}3", "Amount");
		$this->excel->getActiveSheet()->getStyle("{$amountStokColumn}3")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountStokColumn}3")->applyFromArray($this->borderThinStyle());

		$qtyValueSalesInColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueSalesInColumn}" . (4 + $loop_index - 1), ($sales_in != null ? $sales_in->kuantitas : 0));
		$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesInColumn}" . (4 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$amountValueSalesInColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueSalesInColumn}" . (4 + $loop_index - 1), ($sales_in != null ? $sales_in->amount : 0));
		$this->excel->getActiveSheet()->getStyle("{$amountValueSalesInColumn}" . (4 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$qtyValueSalesOutColumn = $this->getColumnLetter($this->monthLastIndex + 2);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueSalesOutColumn}" . (4 + $loop_index - 1), ($sales_out != null ? $sales_out->kuantitas : 0));
		$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . (4 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$amountValueSalesOutColumn = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueSalesOutColumn}" . (4 + $loop_index - 1), ($sales_out != null ? $sales_out->amount : 0));
		$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . (4 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$qtyValueStokColumn = $this->getColumnLetter($this->monthLastIndex + 4);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueStokColumn}" . (4 + $loop_index - 1), ($stock != null ? $stock->kuantitas : 0));
		$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . (4 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$amountValueStokColumn = $this->getColumnLetter($this->monthLastIndex + 5);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueStokColumn}" . (4 + $loop_index - 1), ($stock != null ? $stock->amount : 0));
		$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . (4 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		if($stock != null and $sales_out_3_month != null){
			$slValue = $stock->stock / $sales_out_3_month->average;
		}else{
			$slValue = 0;
		}
		$slColumn = $this->getColumnLetter($this->monthLastIndex + 6);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$slColumn}" . (4 + $loop_index - 1), $slValue);
		$this->excel->getActiveSheet()->getStyle("{$slColumn}" . (4 + $loop_index - 1))->applyFromArray($this->borderThinStyle());

		$this->monthLastIndex = $monthLastIndex + 1;
	}

	public function download(){
		ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan stock_versi all.xlsx"'); // Set nama file excel nya
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