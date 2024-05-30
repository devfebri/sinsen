<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_penjualan_per_part_number extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_penjualan_per_part_number";
	var $title  = "Laporan Penjualan Per Part Number";

	protected $excel;
	protected $monthLastIndex = 4;

	public function __construct()
	{		 
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name=="") echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$this->excel = new PHPExcel();

		$this->load->database();
		$this->load->model('m_admin');
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}

	private function get_sales_in($id_part, $start_date, $end_date){
		return $this->db
		->select('IFNULL(
			SUM(
				(pop.harga_saat_dibeli * of.qty_fulfillment)
			), 
			0
		) as amount', false)
		->select('IFNULL(
			SUM(of.qty_fulfillment), 0
		) as kuantitas', false)
		->from('tr_h3_dealer_order_fulfillment as of')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = of.po_id')
		->join('tr_h3_dealer_purchase_order_parts as pop', '(pop.id_part = of.id_part and pop.po_id = of.po_id)')
		->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		->where("of.created_at BETWEEN '{$start_date}' AND '{$end_date}'", null, false)
		->where('p.id_part', $id_part)
		->where('po.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array();
	}

	private function get_sales_out($id_part, $start_date, $end_date){
		return $this->db
		->select('IFNULL(SUM(sop.kuantitas), 0) as kuantitas')
		->select('IFNULL(SUM(sop.kuantitas * sop.harga_saat_dibeli), 0) as amount')
		->from('ms_kelompok_part as mkp')
		->join('ms_part as p', 'p.kelompok_part = mkp.kelompok_part')
		->join('tr_h3_dealer_sales_order_parts as sop', 'sop.id_part_int = p.id_part_int')
		->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = sop.nomor_so')
		->where('sop.id_part', $id_part)
		->where("so.tanggal_so BETWEEN '{$start_date}' AND '{$end_date}'", null, false)
		->where('so.status', 'Closed')
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array();
	}

	private function get_sales_out_3_month($id_part, $start_date, $end_date){
		return $this->db
		->select('IFNULL( AVG(sop.kuantitas), 0) as average')
		->from('ms_kelompok_part as mkp')
		->join('ms_part as p', 'p.kelompok_part = mkp.kelompok_part')
		->join('tr_h3_dealer_sales_order_parts as sop', 'sop.id_part_int = p.id_part_int')
		->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = sop.nomor_so')
		->where('sop.id_part', $id_part)
		->where("so.tanggal_so BETWEEN '{$start_date}' AND '{$end_date}'", null, false)
		->where('so.status', 'Closed')
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array();
	}

	public function get_stock($id_part, $end_date){
		return $this->db
		->select('IFNULL( SUM(ts.stok_akhir), 0) as kuantitas', false)
		->select('IFNULL( SUM(ts.stok_akhir * p.harga_dealer_user), 0) as amount', false)
		->from('ms_h3_dealer_transaksi_stok as ts')
		->join('ms_part as p', 'p.id_part = ts.id_part')
		->join('ms_kelompok_part as mkp', 'mkp.kelompok_part = p.kelompok_part')
		->where('ts.created_at <', $end_date)
		->where('p.id_part', $id_part)
		->where('ts.id_dealer', $this->m_admin->cari_dealer())
		->order_by('ts.created_at', 'desc')
		->get()->row_array();
	}

	public function generate(){
		if($this->input->get('type') == 'Excel'){
			$this->excel();
		}else if($this->input->get('type') == 'Pdf'){
			$this->pdf();
		}
	}

	public function pdf(){
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		$this->db
		->select('p.id_part')
		->select('p.nama_part')
		->select('p.harga_dealer_user')
		->from('ms_part as p')
		->order_by('p.id_part', 'asc');

		if($this->input->get('filter_kelompok_part') != null AND count($this->input->get('filter_kelompok_part')) > 0){
			$this->db->where_in('p.kelompok_part', $this->input->get('filter_kelompok_part'));
		}else{
			$this->db->where('1=0', null, false);
		}
	
		$parts = $this->db->get()->result_array();

		$start_date = new DateTime($this->input->get('start_date'));
        $end_date = new DateTime($this->input->get('end_date'));
        $diff = $end_date->diff($start_date);
		$month_diff = (($diff->format('%y') * 12) + $diff->format('%m') + ($diff->format('%d') / 30));
		$month_diff = ceil($month_diff);
        $monthRange = range(0, $month_diff);
		$chunkMonthRange = array_chunk($monthRange, 2);

		$data = [];
		foreach ($chunkMonthRange as $chunk_month) {
			$month_per_chunk = [];
			foreach ($parts as $part) {
				foreach ($chunk_month as $month) {
					$payload_per_month = [];
					$thisMonth = new DateTime($this->input->get('start_date')); 
					$one_month = new DateInterval('P' . ($month) . 'M');
					$thisMonth = $thisMonth->add($one_month);
	
					$payload_per_month['label_month'] = $thisMonth->format('F Y');
					$payload_per_month['first_day_of_month'] = $thisMonth->format('Y-m-01 00:00:01');
					$payload_per_month['last_day_of_month'] = $thisMonth->format('Y-m-t 23:59:59');
	
					$three_months_before = new DateTime($thisMonth->format('Y-m-t 23:59:59'));
					$three_months_before->modify('-3 month');
					$payload_per_month['three_months_before'] = $three_months_before->format('Y-m-01 23:59:59');
	
					$payload_per_month['sales_in'] = $this->get_sales_in($part['id_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
					$payload_per_month['sales_out'] = $this->get_sales_out($part['id_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
					$payload_per_month['sales_out_3_month'] = $this->get_sales_out_3_month($part['id_part'], $payload_per_month['three_months_before'], $payload_per_month['last_day_of_month']);
					$payload_per_month['stock'] = $this->get_stock($part['id_part'], $payload_per_month['last_day_of_month']);
					
					$part['payloads'][] = $payload_per_month;
				}
				$month_per_chunk[] = $part;
			}
			
			$data[] = $month_per_chunk;
		}

		$html = $this->load->view('dealer/h3_dealer_laporan_penjualan_per_part_number_pdf', [ 'data' => $data ], true);
		
		// render the view into HTML
		$mpdf->addPage('L');
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel(){		
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		$this->db
		->select('p.id_part')
		->select('p.nama_part')
		->select('p.harga_dealer_user')
		->from('ms_part as p')
		->order_by('p.id_part', 'asc');

		if($this->input->get('filter_kelompok_part') != null AND count($this->input->get('filter_kelompok_part')) > 0){
			$this->db->where_in('p.kelompok_part', $this->input->get('filter_kelompok_part'));
		}else{
			$this->db->where('1=0', null, false);
		}
	
		$parts = $this->db->get()->result_array();

		$start_date = new DateTime($this->input->get('start_date'));
        $end_date = new DateTime($this->input->get('end_date'));
        $diff = $end_date->diff($start_date);
		$month_diff = (($diff->format('%y') * 12) + $diff->format('%m') + ($diff->format('%d') / 30));
		$month_diff = ceil($month_diff);
        $monthRange = range(0, $month_diff);

		$data = [];
		foreach ($parts as $part) {
			$payloads = [];
			foreach ($monthRange as $month) {
				$payload_per_month = [];
				$thisMonth = new DateTime($this->input->get('start_date')); 
				$one_month = new DateInterval('P' . ($month) . 'M');
				$thisMonth = $thisMonth->add($one_month);

				$payload_per_month['label_month'] = $thisMonth->format('F Y');
				$payload_per_month['first_day_of_month'] = $thisMonth->format('Y-m-01 00:00:01');
				$payload_per_month['last_day_of_month'] = $thisMonth->format('Y-m-t 23:59:59');

				$three_months_before = new DateTime($thisMonth->format('Y-m-t 23:59:59'));
				$three_months_before->modify('-3 month');
				$payload_per_month['three_months_before'] = $three_months_before->format('Y-m-01 23:59:59');

				$payload_per_month['sales_in'] = $this->get_sales_in($part['id_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
				$payload_per_month['sales_out'] = $this->get_sales_out($part['id_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
				$payload_per_month['sales_out_3_month'] = $this->get_sales_out_3_month($part['id_part'], $payload_per_month['three_months_before'], $payload_per_month['last_day_of_month']);
				$payload_per_month['stock'] = $this->get_stock($part['id_part'], $payload_per_month['last_day_of_month']);
				
				$payloads[] = $payload_per_month;
			}
			$part['payloads'] = $payloads;
			$data[] = $part;
		}

        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Kelompok Barang Per Part Number");

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'Laporan Kelompok Barang Per Part Number');
		$this->excel->setActiveSheetIndex(0)->setCellValue('A3', date('d/m/Y', strtotime($this->input->get('start_date'))) . ' - ' . date('d/m/Y', strtotime($this->input->get('end_date'))));

		$this->excel->setActiveSheetIndex(0)->setCellValue('A5', 'No');
		$this->excel->getActiveSheet()->mergeCells("A5:A7");
		$this->excel->getActiveSheet()->getStyle("A5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("A5:A7")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("A5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->excel->setActiveSheetIndex(0)->setCellValue('B5', 'Nomor Part');
		$this->excel->getActiveSheet()->mergeCells("B5:B7");
		$this->excel->getActiveSheet()->getStyle("B5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("B5:B7")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("B5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$this->excel->setActiveSheetIndex(0)->setCellValue('C5', 'Deskripsi');
		$this->excel->getActiveSheet()->mergeCells("C5:C7");
		$this->excel->getActiveSheet()->getStyle("C5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("C5:C7")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("C5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
		$this->excel->setActiveSheetIndex(0)->setCellValue('D5', 'HET');
		$this->excel->getActiveSheet()->mergeCells("D5:D7");
		$this->excel->getActiveSheet()->getStyle("D5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("D5:D7")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("D5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$startIndex = 8;
		$loop_index = 1;
		foreach ($data as $part):
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
			$this->excel->getActiveSheet()->getStyle("A{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("A")->setWidth(5);

			$this->excel->setActiveSheetIndex(0)->setCellValue("B{$startIndex}", $part['id_part']);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("B")->setWidth(18);

			$this->excel->setActiveSheetIndex(0)->setCellValue("C{$startIndex}", $part['nama_part']);
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("C")->setWidth(30);

			$this->excel->setActiveSheetIndex(0)->setCellValue("D{$startIndex}", $part['harga_dealer_user']);
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->getActiveSheet()->getColumnDimension("D")->setWidth(15);

			foreach ($part['payloads'] as $payload) {
				$this->monthSection($loop_index, $payload);
			}

			$this->monthLastIndex = 4;
			$startIndex++;
			$loop_index++;
		endforeach;

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Lap Penj Per Part Number");
		$this->excel->setActiveSheetIndex(0);
		
        $this->download();
	}

	public function monthSection($loop_index, $payload){
		$monthColumnStart = $this->getColumnLetter($this->monthLastIndex);
		$monthLastIndex = ($this->monthLastIndex + 5);
		$monthColumnEnd = $this->getColumnLetter($monthLastIndex);

		$this->excel->setActiveSheetIndex(0)->setCellValue($monthColumnStart . 5, $payload['label_month']);
		$this->excel->getActiveSheet()->mergeCells("{$monthColumnStart}5:{$monthColumnEnd}5");
		$this->excel->getActiveSheet()->getStyle($monthColumnStart . 5)->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$monthColumnStart}5:{$monthColumnEnd}5")->applyFromArray($this->borderThinStyle());

		$pembelianColumnStart = $this->getColumnLetter($this->monthLastIndex);
		$pembelianColumnEnd = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$pembelianColumnStart}6", "Pembelian");
		$this->excel->getActiveSheet()->mergeCells("{$pembelianColumnStart}6:{$pembelianColumnEnd}6");
		$this->excel->getActiveSheet()->getStyle("{$pembelianColumnStart}6")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$pembelianColumnStart}6:{$pembelianColumnEnd}6")->applyFromArray($this->borderThinStyle());

		$salesOutColumnStart = $this->getColumnLetter($this->monthLastIndex + 2);
		$salesOutColumnEnd = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$salesOutColumnStart}6", "Penjualan");
		$this->excel->getActiveSheet()->mergeCells("{$salesOutColumnStart}6:{$salesOutColumnEnd}6");
		$this->excel->getActiveSheet()->getStyle("{$salesOutColumnStart}6")->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$salesOutColumnStart}6:{$salesOutColumnEnd}6")->applyFromArray($this->borderThinStyle());


		$stokColumnStart = $this->getColumnLetter($this->monthLastIndex + 4);
		$stokColumnEnd = $this->getColumnLetter($this->monthLastIndex + 5);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$stokColumnStart}6", "Stok");
		$this->excel->getActiveSheet()->mergeCells("{$stokColumnStart}6:{$stokColumnEnd}6");
		$this->excel->getActiveSheet()->getStyle("{$stokColumnStart}6")->applyFromArray($this->headerStyle());

		$this->excel->getActiveSheet()->getStyle("{$stokColumnStart}6:{$stokColumnEnd}6")->applyFromArray($this->borderThinStyle());

		$qtyPembelianColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyPembelianColumn}7", "Qty");
		$this->excel->getActiveSheet()->getStyle("{$qtyPembelianColumn}7")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyPembelianColumn}7")->applyFromArray($this->borderThinStyle());
		$amountPembelianColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountPembelianColumn}7", "Amount");
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianColumn}7")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianColumn}7")->applyFromArray($this->borderThinStyle());

		$qtyPenjualanColumn = $this->getColumnLetter($this->monthLastIndex + 2);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyPenjualanColumn}7", "Qty");
		$this->excel->getActiveSheet()->getStyle("{$qtyPenjualanColumn}7")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyPenjualanColumn}7")->applyFromArray($this->borderThinStyle());

		$amountPenjualanColumn = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountPenjualanColumn}7", "Amount");
		$this->excel->getActiveSheet()->getStyle("{$amountPenjualanColumn}7")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountPenjualanColumn}7")->applyFromArray($this->borderThinStyle());

		$qtyStokColumn = $this->getColumnLetter($this->monthLastIndex + 4);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyStokColumn}7", "Qty");
		$this->excel->getActiveSheet()->getStyle("{$qtyStokColumn}7")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyStokColumn}7")->applyFromArray($this->borderThinStyle());

		$amountStokColumn = $this->getColumnLetter($this->monthLastIndex + 5);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountStokColumn}7", "Amount");
		$this->excel->getActiveSheet()->getStyle("{$amountStokColumn}7")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountStokColumn}7")->applyFromArray($this->borderThinStyle());

		$qtyValuePembelianColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValuePembelianColumn}" . (8 + $loop_index - 1), $payload['sales_in']['kuantitas']);
		$this->excel->getActiveSheet()->getStyle("{$qtyValuePembelianColumn}" . (8 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyValuePembelianColumn}" . (8 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyValuePembelianColumn}" . (8 + $loop_index - 1))->getNumberFormat()->setFormatCode('#,##0');
		$this->excel->getActiveSheet()->getColumnDimension($qtyValuePembelianColumn)->setWidth(15);

		$amountValuePembelianColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValuePembelianColumn}" . (8 + $loop_index - 1), $payload['sales_in']['amount']);
		$this->excel->getActiveSheet()->getStyle("{$amountValuePembelianColumn}" . (8 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountValuePembelianColumn}" . (8 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountValuePembelianColumn}" . (8 + $loop_index - 1))->getNumberFormat()->setFormatCode('Rp #,##0');
		$this->excel->getActiveSheet()->getColumnDimension($amountValuePembelianColumn)->setWidth(15);

		$qtyValueSalesOutColumn = $this->getColumnLetter($this->monthLastIndex + 2);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueSalesOutColumn}" . (8 + $loop_index - 1), $payload['sales_out']['kuantitas']);
		$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . (8 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . (8 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . (8 + $loop_index - 1))->getNumberFormat()->setFormatCode('#,##0');
		$this->excel->getActiveSheet()->getColumnDimension($qtyValueSalesOutColumn)->setWidth(15);

		$amountValueSalesOutColumn = $this->getColumnLetter($this->monthLastIndex + 3);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueSalesOutColumn}" . (8 + $loop_index - 1), $payload['sales_out']['amount']);
		$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . (8 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . (8 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . (8 + $loop_index - 1))->getNumberFormat()->setFormatCode('Rp #,##0');
		$this->excel->getActiveSheet()->getColumnDimension($amountValueSalesOutColumn)->setWidth(15);

		$qtyValueStokColumn = $this->getColumnLetter($this->monthLastIndex + 4);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueStokColumn}" . (8 + $loop_index - 1), $payload['stock']['kuantitas']);
		$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . (8 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . (8 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . (8 + $loop_index - 1))->getNumberFormat()->setFormatCode('#,##0');
		$this->excel->getActiveSheet()->getColumnDimension($qtyValueStokColumn)->setWidth(15);

		$amountValueStokColumn = $this->getColumnLetter($this->monthLastIndex + 5);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueStokColumn}" . (8 + $loop_index - 1), $payload['stock']['amount']);
		$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . (8 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . (8 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . (8 + $loop_index - 1))->getNumberFormat()->setFormatCode('Rp #,##0');
		$this->excel->getActiveSheet()->getColumnDimension($amountValueStokColumn)->setWidth(15);

		$this->monthLastIndex = $monthLastIndex + 1;
	}

	public function download(){
		ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan penjualan per part number ' . date('d-m-Y', strtotime($this->input->get('start_date'))) . ' s.d ' . date('d-m-Y', strtotime($this->input->get('end_date'))) . '.xlsx"'); // Set nama file excel nya
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

	public function fontStyle(){
		return  [
			'font' => [
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		];
	}

	public function getMonthName($month){
		$dateObj = DateTime::createFromFormat('!m', $month);
		return $dateObj->format('F');
	}
}