<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_stok_versi_all extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_stok_versi_all";
	var $title  = "Laporan Stok Versi All";

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
		$this->load->model('h3_dealer_stock_model', 'stock');
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}


	public function generate(){
		$transaksi_stock = $this->db
		->select('ts.stok_akhir')
		->from('ms_h3_dealer_transaksi_stok as ts')
		->where('ts.id_part = ds.id_part', null, false)
		->where('ts.id_gudang = ds.id_gudang', null, false)
		->where('ts.id_rak = ds.id_rak', null, false)
		->where('ts.id_dealer', $this->m_admin->cari_dealer())
		->where('ts.created_at <=', date('Y-m-d 23:59:59', strtotime($this->input->get('end_date'))))
		->limit(1)
		->order_by('ts.created_at', 'desc')
		->get_compiled_select();

		$dealer_stock = $this->db
		->select("SUM(
			IFNULL(({$transaksi_stock}), 0)
		) as kuantitas", false)
		->from('ms_h3_dealer_stock as ds')
		->where('ds.id_dealer', $this->m_admin->cari_dealer())
		->where('ds.id_part = p.id_part', null, false)
		->get_compiled_select();

		$this->db
		->select('p.id_part')
		->select('p.nama_part')
		->select("IFNULL(({$dealer_stock}), 0) as kuantitas")
		->select('p.harga_md_dealer as harga_beli')
		->select('p.harga_dealer_user as harga_jual')
		->select('p.kelompok_part')
		->select('IFNULL(ar.rank, "-") AS rank', false)
		->select('IFNULL(ar.status, "-") AS status', false)
		->from('ms_part as p')
		->join('ms_h3_analisis_ranking as ar', "(ar.id_part = p.id_part and ar.id_dealer = {$this->m_admin->cari_dealer()})", 'left')
		->limit(10)
		;

		if($this->input->get('filter_part') != null and count($this->input->get('filter_part')) > 0){
			$this->db->where_in('p.id_part', $this->input->get('filter_part'));
		}else{
			$this->db->where('0 = 1', null, false);
		}

		$stock = $this->db->get()->result_array();

		if($this->input->get('type') == 'Excel'){
			$this->excel($stock);
		}else if($this->input->get('type') == 'Pdf'){
			$this->pdf($stock);
		}
	}

	public function pdf($stock)
	{
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$data = [
			'stock' => $stock
		];

		$html = $this->load->view('dealer/h3_dealer_laporan_stok_versi_all_pdf', $data, true);
		
		// render the view into HTML
		$mpdf->addPage('L');
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel($stock)
	{		
        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Stok versi All");

		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'Laporan Stok All');
		$this->excel->getActiveSheet()->mergeCells("A1:K1");
		$this->excel->getActiveSheet()->getStyle("A1")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
		]);
		$monthStart = date('F', strtotime($this->input->get('start_date')));
		$monthEnd = date('F', strtotime($this->input->get('end_date')));
		$this->excel->setActiveSheetIndex(0)->setCellValue('A2', "Periode : {$monthStart} - {$monthEnd}");
		$this->excel->getActiveSheet()->mergeCells("A2:F2");
		$this->excel->getActiveSheet()->getStyle("A2")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
		]);

		$startDate = date('d-m-Y', strtotime($this->input->get('start_date')));
		$endDate = date('d-m-Y', strtotime($this->input->get('end_date')));
		$this->excel->setActiveSheetIndex(0)->setCellValue('G2', "{$startDate} - {$endDate}");
		$this->excel->getActiveSheet()->mergeCells("G2:K2");
		$this->excel->getActiveSheet()->getStyle("G2")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
		]);

		$this->excel->setActiveSheetIndex(0)->setCellValue("A4", 'No');
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4.57);
		$this->excel->setActiveSheetIndex(0)->setCellValue("B4", 'Part Number');
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.71);
		$this->excel->setActiveSheetIndex(0)->setCellValue("C4", 'Description');
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
		$this->excel->setActiveSheetIndex(0)->setCellValue("D4", 'Qty');
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$this->excel->setActiveSheetIndex(0)->setCellValue("E4", 'Harga Beli');
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$this->excel->setActiveSheetIndex(0)->setCellValue("F4", 'Jumlah');
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$this->excel->setActiveSheetIndex(0)->setCellValue("G4", 'Harga Jual');
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$this->excel->setActiveSheetIndex(0)->setCellValue("H4", 'Jumlah');
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
		$this->excel->setActiveSheetIndex(0)->setCellValue("I4", 'Kel. Produk');
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
		$this->excel->setActiveSheetIndex(0)->setCellValue("J4", 'Rank');
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
		$this->excel->setActiveSheetIndex(0)->setCellValue("K4", 'Status');
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(13);

		$headers = [
			"A4","B4","C4","D4",
			"E4","F4","G4","H4",
			"I4","J4","K4",
		];

		foreach ($headers as $header) {
			$this->excel->getActiveSheet()->getStyle($header)->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->getActiveSheet()->getStyle($header)->getFont()->setBold( true );
		}

		$startIndex = 5;
		$loop_index = 1;
		$total_harga_beli = $total_harga_jual = 0;
		foreach ($stock as $each_stock):
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
			$this->excel->setActiveSheetIndex(0)->setCellValue("B{$startIndex}", $each_stock['id_part']);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("C{$startIndex}", $each_stock['nama_part']);
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			
			$this->excel->setActiveSheetIndex(0)->setCellValue("D{$startIndex}", number_format($each_stock['kuantitas'], 0, ',', '.'));
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("E{$startIndex}", "Rp " . number_format($each_stock['harga_beli'], 0, ',', '.'));
			$this->excel->getActiveSheet()->getStyle("E{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$amount_harga_beli = $each_stock['harga_beli'] * $each_stock['kuantitas'];
			$total_harga_beli += $amount_harga_beli;
			$this->excel->setActiveSheetIndex(0)->setCellValue("F{$startIndex}", "Rp " . number_format($amount_harga_beli, 0, ',', '.'));
			$this->excel->getActiveSheet()->getStyle("F{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("G{$startIndex}", "Rp " . number_format($each_stock['harga_jual'], 0, ',', '.'));
			$this->excel->getActiveSheet()->getStyle("G{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$amount_harga_jual = $each_stock['harga_jual'] * $each_stock['kuantitas'];
			$total_harga_jual += $amount_harga_jual;
			$this->excel->setActiveSheetIndex(0)->setCellValue("H{$startIndex}", "Rp " . number_format($total_harga_jual, 0, ',', '.'));
			$this->excel->getActiveSheet()->getStyle("H{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("I{$startIndex}", $each_stock['kelompok_part']);
			$this->excel->getActiveSheet()->getStyle("I{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("J{$startIndex}", $each_stock['rank']);
			$this->excel->getActiveSheet()->getStyle("J{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("K{$startIndex}", $each_stock['status']);
			$this->excel->getActiveSheet()->getStyle("K{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$startIndex++;
			$loop_index++;
		endforeach;

		$this->excel->setActiveSheetIndex(0)->setCellValue("A{$startIndex}", "Total");
		$this->excel->getActiveSheet()->mergeCells("A{$startIndex}:E{$startIndex}");
		$this->excel->getActiveSheet()->getStyle("A{$startIndex}:E{$startIndex}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				)
			)
		]);

		$this->excel->setActiveSheetIndex(0)->setCellValue("F{$startIndex}", "Rp " . $total_harga_beli);
		$this->excel->getActiveSheet()->getStyle("F{$startIndex}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				)
			)
		]);

		$this->excel->setActiveSheetIndex(0)->setCellValue("G{$startIndex}", "");
		$this->excel->getActiveSheet()->getStyle("G{$startIndex}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				)
			)
		]);

		$this->excel->setActiveSheetIndex(0)->setCellValue("H{$startIndex}", "Rp " . $total_harga_jual);
		$this->excel->getActiveSheet()->getStyle("H{$startIndex}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				)
			)
		]);
		
		$this->excel->setActiveSheetIndex(0)->setCellValue("I{$startIndex}", "");
		$this->excel->getActiveSheet()->mergeCells("I{$startIndex}:K{$startIndex}");
		$this->excel->getActiveSheet()->getStyle("I{$startIndex}:K{$startIndex}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				)
			)
		]);

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Laporan Stok versi all");
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