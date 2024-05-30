<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_stok_versi_kelompok extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_stok_versi_kelompok";
	var $title  = "Laporan Stok Versi Kelompok";

	protected $excel;
	protected $monthLastIndex = 2;

	public function __construct(){		 
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name=="") echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$this->excel = new PHPExcel();

		$this->load->database();
		$this->load->model('m_admin');

		$this->load->library('Mcarbon');
		$this->load->model('h3_dealer_stock_model', 'stock');
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

	private function get_sales_in($kelompok_part, $start_date, $end_date){
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
		->where('p.kelompok_part', $kelompok_part)
		->where('po.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array();
	}

	private function get_sales_out($kelompok_part, $start_date, $end_date){
		return $this->db
		->select('IFNULL(SUM(sop.kuantitas), 0) as kuantitas')
		->select('IFNULL(SUM(sop.kuantitas * sop.harga_saat_dibeli), 0) as amount')
		->from('ms_kelompok_part as mkp')
		->join('ms_part as p', 'p.kelompok_part = mkp.kelompok_part')
		->join('tr_h3_dealer_sales_order_parts as sop', 'sop.id_part_int = p.id_part_int')
		->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = sop.nomor_so')
		->where('mkp.kelompok_part', $kelompok_part)
		->where("so.tanggal_so BETWEEN '{$start_date}' AND '{$end_date}'", null, false)
		->where('so.status', 'Closed')
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array();
	}

	private function get_sales_out_3_month($kelompok_part, $start_date, $end_date){
		return $this->db
		->select('IFNULL( AVG(sop.kuantitas), 0) as average')
		->from('ms_kelompok_part as mkp')
		->join('ms_part as p', 'p.kelompok_part = mkp.kelompok_part')
		->join('tr_h3_dealer_sales_order_parts as sop', 'sop.id_part_int = p.id_part_int')
		->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = sop.nomor_so')
		->where('mkp.kelompok_part', $kelompok_part)
		->where("so.tanggal_so BETWEEN '{$start_date}' AND '{$end_date}'", null, false)
		->where('so.status', 'Closed')
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array();
	}

	public function get_stock($kelompok_part, $end_date){
		$transaksi_stock_terakhir = $this->db
		->select('ts.stok_akhir as kuantitas', false)
		->from('ms_h3_dealer_transaksi_stok as ts')
		->where('ts.created_at <', $end_date)
		->where('ts.id_dealer', $this->m_admin->cari_dealer())
		->where('ts.id_part = p.id_part', null, false)
		->order_by('ts.created_at', 'desc')
		->limit(1)
		->get_compiled_select();

		$stock_onhand = $this->stock->qty_on_hand($this->m_admin->cari_dealer(), 'p.id_part', null, null, true);

		$this->db
		->select('p.id_part')
		// ->select("IFNULL(({$transaksi_stock_terakhir}), 0) as kuantitas", false)
		->select("IFNULL(({$stock_onhand}), 0) as kuantitas", false)
		->select('p.harga_dealer_user')
		->from('ms_kelompok_part as kp')
		->join('ms_part as p', 'p.kelompok_part = kp.id_kelompok_part')
		->where('kp.kelompok_part', $kelompok_part);

		$kuantitas = 0;
		$amount = 0;
		foreach ($this->db->get()->result_array() as $row) {
			$kuantitas += intval($row['kuantitas']);
			$amount += intval($row['kuantitas']) * floatval($row['harga_dealer_user']);
		}

		return ([
			'kuantitas' => $kuantitas,
			'amount' => $amount
		]);
	}

	public function pdf(){
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$this->db
		->select('kp.kelompok_part')
		->from('ms_kelompok_part as kp')
		->order_by('kp.kelompok_part', 'asc');

		if($this->input->get('filter_kelompok_part') != null and count($this->input->get('filter_kelompok_part')) > 0){
			$this->db->where_in('kp.kelompok_part', $this->input->get('filter_kelompok_part'));
		}else{
			$this->db->where('kp.kelompok_part', null);
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
	
					$payload_per_month['sales_in'] = $this->get_sales_in($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
					$payload_per_month['sales_out'] = $this->get_sales_out($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
					$payload_per_month['sales_out_3_month'] = $this->get_sales_out_3_month($kelompok_part['kelompok_part'], $payload_per_month['three_months_before'], $payload_per_month['last_day_of_month']);
					$payload_per_month['stock'] = $this->get_stock($kelompok_part['kelompok_part'], $payload_per_month['last_day_of_month']);
					
					$kelompok_part['payloads'][] = $payload_per_month;
				}
				$month_per_chunk[] = $kelompok_part;
			}
			
			$data[] = $month_per_chunk;
		}

		$html = $this->load->view('dealer/h3_dealer_laporan_stok_versi_kelompok_pdf', [
			'data' => $data
		], true);
		
		// render the view into HTML
		$mpdf->addPage('L');
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel(){		
		$this->db
		->select('kp.kelompok_part')
		->from('ms_kelompok_part as kp')
		->order_by('kp.kelompok_part', 'asc');

		if($this->input->get('filter_kelompok_part') != null and count($this->input->get('filter_kelompok_part')) > 0){
			$this->db->where_in('kp.kelompok_part', $this->input->get('filter_kelompok_part'));
		}else{
			$this->db->where('kp.kelompok_part', null);
		}

		$kelompok_parts = $this->db->get()->result_array();

		$start_date = Mcarbon::parse($this->input->get('start_date'));
        $end_date = Mcarbon::parse($this->input->get('end_date'));
		$month_diff = $end_date->diffInMonths($start_date);
		$monthRange = range(0, $month_diff);

		$data = [];
		
		foreach ($kelompok_parts as $kelompok_part) {
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

				$payload_per_month['sales_in'] = $this->get_sales_in($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
				$payload_per_month['sales_out'] = $this->get_sales_out($kelompok_part['kelompok_part'], $payload_per_month['first_day_of_month'], $payload_per_month['last_day_of_month']);
				$payload_per_month['sales_out_3_month'] = $this->get_sales_out_3_month($kelompok_part['kelompok_part'], $payload_per_month['three_months_before'], $payload_per_month['last_day_of_month']);
				$payload_per_month['stock'] = $this->get_stock($kelompok_part['kelompok_part'], $payload_per_month['last_day_of_month']);
				
				$kelompok_part['payloads'][] = $payload_per_month;
			}
			$data[] = $kelompok_part;
		}

        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Stok versi Kelompok");

		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'NO');
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->mergeCells("A1:A3");
		$this->excel->getActiveSheet()->getStyle("A1")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("A1:A3")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("A1")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		
		$this->excel->setActiveSheetIndex(0)->setCellValue('B1', 'PRODUK');
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->mergeCells("B1:B3");
		$this->excel->getActiveSheet()->getStyle("B1")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("B1:B3")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("B1")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$index = 2;
		$index_payload = 0;
		foreach($data[0]['payloads'] as $payload){
			$label_column_label_month_sales_in_start = $this->getColumnLetter($index + $index_payload);
			$label_column_label_month_sales_in_end = $this->getColumnLetter($index + $index_payload + 6);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$label_column_label_month_sales_in_start}1", $payload['label_month']);
			$this->excel->getActiveSheet()->mergeCells("{$label_column_label_month_sales_in_start}1:{$label_column_label_month_sales_in_end}1");
			$this->excel->getActiveSheet()->getStyle("{$label_column_label_month_sales_in_start}1:{$label_column_label_month_sales_in_end}1")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_label_month_sales_in_start}1")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_label_month_sales_in_start}1")->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_label_month_sales_in_start}1")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);


			$label_column_sales_in_start = $this->getColumnLetter($index + $index_payload);
			$label_column_sales_in_end = $this->getColumnLetter($index + $index_payload + 1);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$label_column_sales_in_start}2", 'Sales In');
			$this->excel->getActiveSheet()->mergeCells("{$label_column_sales_in_start}2:{$label_column_sales_in_end}2");
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_in_start}2:{$label_column_sales_in_end}2")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_in_start}2")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_in_start}2")->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_in_start}2")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);
			$qtyValueSalesInColumn = $this->getColumnLetter($index + $index_payload);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueSalesInColumn}" . 3, 'Qty');
			$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesInColumn}" . 3)->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesInColumn}" . 3)->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesInColumn}" . 3)->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);
			$amountValueSalesInColumn = $this->getColumnLetter($index + 1 + $index_payload);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueSalesInColumn}" . 3, 'Amount');
			$this->excel->getActiveSheet()->getStyle("{$amountValueSalesInColumn}" . 3)->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$amountValueSalesInColumn}" . 3)->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$amountValueSalesInColumn}" . 3)->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$label_column_sales_out_start = $this->getColumnLetter($index + $index_payload + 2);
			$label_column_sales_out_end = $this->getColumnLetter($index + $index_payload + 3);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$label_column_sales_out_start}2", 'Sales Out');
			$this->excel->getActiveSheet()->mergeCells("{$label_column_sales_out_start}2:{$label_column_sales_out_end}2");
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_out_start}2:{$label_column_sales_out_end}2")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_out_start}2")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_out_start}2")->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_out_start}2")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);
			$qtyValueSalesOutColumn = $this->getColumnLetter($index + 2 + $index_payload);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueSalesOutColumn}" . 3, 'Qty');
			$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . 3)->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . 3)->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . 3)->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$amountValueSalesOutColumn = $this->getColumnLetter($index + 3 + $index_payload);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueSalesOutColumn}" . 3, 'Amount');
			$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . 3)->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . 3)->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . 3)->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$label_column_sales_stock_start = $this->getColumnLetter($index + $index_payload + 4);
			$label_column_sales_stock_end = $this->getColumnLetter($index + $index_payload + 5);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$label_column_sales_stock_start}2", 'Stock');
			$this->excel->getActiveSheet()->mergeCells("{$label_column_sales_stock_start}2:{$label_column_sales_stock_end}2");
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_stock_start}2:{$label_column_sales_stock_end}2")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_stock_start}2")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_stock_start}2")->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_sales_stock_start}2")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$qtyValueStokColumn = $this->getColumnLetter($index + 4 + $index_payload);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueStokColumn}" . 3, 'Qty');
			$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . 3)->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . 3)->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . 3)->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$amountValueStokColumn = $this->getColumnLetter($index + 5 + $index_payload);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueStokColumn}" . 3, 'Amount');
			$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . 3)->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . 3)->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . 3)->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$label_column_service_rate = $this->getColumnLetter($index + $index_payload + 6);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$label_column_service_rate}2", 'S/L');
			$this->excel->getActiveSheet()->mergeCells("{$label_column_service_rate}2:{$label_column_service_rate}3");
			$this->excel->getActiveSheet()->getStyle("{$label_column_service_rate}2:{$label_column_service_rate}3")->applyFromArray($this->borderThinStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_service_rate}2:{$label_column_service_rate}3")->applyFromArray($this->headerStyle());
			$this->excel->getActiveSheet()->getStyle("{$label_column_service_rate}2:{$label_column_service_rate}3")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
			]);

			$index_payload += 6;
			$index++;
		}

		$startIndex = 4;
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

			$index = 2;
			$index_payload = 0;
			foreach ($row['payloads'] as $payload) {
				$qtyValueSalesInColumn = $this->getColumnLetter($index + $index_payload);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueSalesInColumn}" . $startIndex, $payload['sales_in']['kuantitas']);
				$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesInColumn}" . $startIndex)->getNumberFormat()->setFormatCode('#,##0.00');
				$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesInColumn}" . $startIndex)->applyFromArray($this->borderThinStyle());
				$amountValueSalesInColumn = $this->getColumnLetter($index + 1 + $index_payload);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueSalesInColumn}" . $startIndex, $payload['sales_in']['amount']);
				$this->excel->getActiveSheet()->getStyle("{$amountValueSalesInColumn}" . $startIndex)->getNumberFormat()->setFormatCode('#,##0.00');
				$this->excel->getActiveSheet()->getStyle("{$amountValueSalesInColumn}" . $startIndex)->applyFromArray($this->borderThinStyle());

				$qtyValueSalesOutColumn = $this->getColumnLetter($index + 2 + $index_payload);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueSalesOutColumn}" . $startIndex, $payload['sales_out']['kuantitas']);
				$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . $startIndex)->getNumberFormat()->setFormatCode('#,##0.00');
				$this->excel->getActiveSheet()->getStyle("{$qtyValueSalesOutColumn}" . $startIndex)->applyFromArray($this->borderThinStyle());

				$amountValueSalesOutColumn = $this->getColumnLetter($index + 3 + $index_payload);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueSalesOutColumn}" . $startIndex, $payload['sales_out']['amount']);
				$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . $startIndex)->getNumberFormat()->setFormatCode('#,##0.00');
				$this->excel->getActiveSheet()->getStyle("{$amountValueSalesOutColumn}" . $startIndex)->applyFromArray($this->borderThinStyle());

				$qtyValueStokColumn = $this->getColumnLetter($index + 4 + $index_payload);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$qtyValueStokColumn}" . $startIndex, $payload['stock']['kuantitas']);
				$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . $startIndex)->getNumberFormat()->setFormatCode('#,##0.00');
				$this->excel->getActiveSheet()->getStyle("{$qtyValueStokColumn}" . $startIndex)->applyFromArray($this->borderThinStyle());

				$amountValueStokColumn = $this->getColumnLetter($index + 5 + $index_payload);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountValueStokColumn}" . $startIndex, $payload['stock']['amount']);
				$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . $startIndex)->getNumberFormat()->setFormatCode('#,##0.00');
				$this->excel->getActiveSheet()->getStyle("{$amountValueStokColumn}" . $startIndex)->applyFromArray($this->borderThinStyle());

				if($payload['sales_out_3_month']['average'] > 0){
					$slValue = $payload['stock']['kuantitas'] / $payload['sales_out_3_month']['average'];
				}else{
					$slValue = 0;
				}
				$slColumn = $this->getColumnLetter($index + 6 + $index_payload);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$slColumn}" . $startIndex, $slValue);
				$this->excel->getActiveSheet()->getStyle("{$slColumn}" . $startIndex)->applyFromArray($this->borderThinStyle());

				$index_payload += 7;
			}
			$startIndex++;
			$loop_index++;
		endforeach;

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Laporan Stok versi Kelompok");
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
        header('Content-Disposition: attachment; filename="Laporan Stock Versi Kelompok Part.xlsx"'); // Set nama file excel nya
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