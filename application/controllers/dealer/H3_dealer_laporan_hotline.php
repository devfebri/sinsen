<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_hotline extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_hotline";
	var $title  = "Laporan Hotline";

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
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}


	public function generate(){
		$purchase_hotlines = $this->db
		->select('date_format(po.tanggal_order, "%d/%m/%Y") as tanggal_order')
		->select('po.po_id as nomor_po')
		->select('c.nama_customer')
		->select('c.alamat')
		->select('c.no_hp')
		->select('c.no_rangka')
		->select('c.no_mesin')
		->from('tr_h3_dealer_purchase_order as po')
		->join('tr_h3_dealer_request_document as rd', 'po.id_booking = rd.id_booking')
		->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
		->where('po.po_type', 'HLO')
		->group_start()
		->where('po.tanggal_order <=', $this->input->get('end_date'))
		->where('po.tanggal_order >=', $this->input->get('start_date'))
		->group_end()
		->where('po.id_dealer', $this->m_admin->cari_dealer())
		->group_start()
		->where('po.order_to ', 0)
		->or_where('po.order_to ', null)
		->group_end()
		->order_by('po.tanggal_order', 'asc')
		->get()->result_array();

		$final_data = [];
		foreach ($purchase_hotlines as $purchase_hotline) {
			$purchase_parts =  $this->db
			->select('pop.id_part')
			->select('p.nama_part')
			->select('pop.kuantitas')
			->select('IFNULL(of.qty_fulfillment, 0) as qty_supply', FALSE)
			->select('(pop.kuantitas - IFNULL(of.qty_fulfillment, 0)) as back_order', FALSE)
			->select('IFNULL( 
				DATE_FORMAT(pop.eta_terlama, "%d/%m/%Y"),
				"-"
			) as eta_awal', false)
			->select('IFNULL( 
				DATE_FORMAT(rdp.eta_revisi, "%d/%m/%Y"),
				"-"
			) as eta_revisi', false)
			->select('IFNULL(ps.tgl_packing_sheet, "-") as tgl_packing_sheet', false)
			->select('IFNULL(ps.id_packing_sheet, "-") as id_packing_sheet', false)
			->select('IFNULL(
				DATE_FORMAT(of.created_at, "%d/%m/%Y"),
				"-"
			) as tanggal_terima_dari_md', false)
			->select('IFNULL(
				DATE_FORMAT(nsc.tgl_nsc, "%d/%m/%Y"),
				"-"
			) as tanggal_terima_konsumen', false)
			->select('IFNULL(
				DATEDIFF(nsc.tgl_nsc, of.created_at), 
				"-"
			) as lead_time', false)
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
			->join('tr_h3_dealer_request_document as rd', 'po.id_booking = rd.id_booking')
			->join('tr_h3_dealer_request_document_parts as rdp', '(rdp.id_booking = rd.id_booking and rdp.id_part = pop.id_part)')
			->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
			->join('tr_h3_dealer_order_fulfillment as of', '(of.po_id = po.po_id and of.id_part = pop.id_part)', 'left')
			->join('tr_h3_dealer_good_receipt as gr', 'gr.id_good_receipt = of.id_referensi')
			->join('tr_h3_dealer_good_receipt_parts as grp', '(grp.id_good_receipt = gr.id_good_receipt and grp.id_part = of.id_part)')
			->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = gr.id_reference', 'left')
			->join('tr_h3_dealer_sales_order as so_dealer', 'so_dealer.booking_id_reference = po.id_booking', 'left')
			->join('tr_h23_nsc as nsc', 'nsc.id_referensi = so_dealer.nomor_so', 'left')
			->where('pop.po_id', $purchase_hotline['nomor_po'])
			->order_by('pop.id_part', 'asc')
			->get()->result_array();

			if(count($purchase_parts) > 0){
				$purchase_hotline['parts'] = $purchase_parts;
				$final_data['purchase_hotlines'][] = $purchase_hotline;
			}
		}

		$dealer = $this->db
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('d.pemilik')
		->select('"" as pic_parts')
		->from('ms_dealer as d')
		->where('d.id_dealer', $this->m_admin->cari_dealer())
		->get()->row();

		$final_data['dealer'] = $dealer;

		if($this->input->get('type') == 'Excel'){
			$this->excel($final_data);
		}else if($this->input->get('type') == 'Pdf'){
			$this->pdf($final_data);
		}
	}

	public function pdf($data)
	{
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$html = $this->load->view('dealer/h3_dealer_laporan_hotline_pdf', $data, true);
		// echo $html; die;
		// render the view into HTML
		$mpdf->addPage('L');
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel($data)
	{		
        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Hotline Order");

		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'FORM MONITORING HOTLINE ORDER DEALER');
		$this->excel->getActiveSheet()->getStyle("A1")->applyFromArray([
			'font' => [
				'size'  => 14,
				'name'  => 'Tahoma'
			],
		]);
		// $this->excel->getActiveSheet()->mergeCells("A1:K1");
		$monthStart = date('F', strtotime($this->input->get('start_date')));
		$monthEnd = date('F Y', strtotime($this->input->get('end_date')));
		$this->excel->setActiveSheetIndex(0)->setCellValue('A2', "Periode : {$monthEnd}");
		$this->excel->getActiveSheet()->getStyle("A2")->applyFromArray([
			'font' => [
				'size'  => 14,
				'name'  => 'Tahoma'
			],
		]);

		$dealer = $data['dealer'];
		$this->excel->setActiveSheetIndex(0)->setCellValue('A4', "Nama Dealer");
		$this->excel->getActiveSheet()->getStyle("A4")->applyFromArray([
			'font' => [
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);
		$this->excel->getActiveSheet()->mergeCells("A4:B4");
		$this->excel->setActiveSheetIndex(0)->setCellValue('C4', ": {$dealer->nama_dealer}");
		$this->excel->getActiveSheet()->getStyle("C4")->applyFromArray([
			'font' => [
				'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE,
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);
		

		$this->excel->setActiveSheetIndex(0)->setCellValue('A5', "Alamat");
		$this->excel->getActiveSheet()->mergeCells("A5:B5");
		$this->excel->getActiveSheet()->getStyle("A5")->applyFromArray([
			'font' => [
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);
		$this->excel->setActiveSheetIndex(0)->setCellValue('C5', ": {$dealer->alamat}");
		$this->excel->getActiveSheet()->getStyle("C5")->applyFromArray([
			'font' => [
				'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE,
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);
		
		$this->excel->setActiveSheetIndex(0)->setCellValue('A6', "Nama PIC Dealer");
		$this->excel->getActiveSheet()->mergeCells("A6:B6");
		$this->excel->getActiveSheet()->getStyle("A6")->applyFromArray([
			'font' => [
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);
		$this->excel->setActiveSheetIndex(0)->setCellValue('C6', ": {$dealer->pemilik}");
		$this->excel->getActiveSheet()->getStyle("C6")->applyFromArray([
			'font' => [
				'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE,
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);

		$this->excel->setActiveSheetIndex(0)->setCellValue('A7', "Nama PIC Parts");
		$this->excel->getActiveSheet()->mergeCells("A7:B7");
		$this->excel->getActiveSheet()->getStyle("A7")->applyFromArray([
			'font' => [
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);
		$this->excel->setActiveSheetIndex(0)->setCellValue('C7', ": {$dealer->pic_parts}");
		$this->excel->getActiveSheet()->getStyle("C7")->applyFromArray([
			'font' => [
				'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE,
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		]);

		$columnsWidths = [
			'A' => 5,
			'B' => 13,
			'C' => 23,
			'D' => 17,
			'E' => 36,
			'F' => 17,
			'G' => 22,
			'H' => 18,
			'I' => 5,
			'J' => 25,
			'K' => 27,
			'L' => 7,
			'M' => 7,
			'N' => 7,
			'O' => 12,
			'P' => 12,
			'Q' => 15,
			'R' => 22,
			'S' => 15,
			'T' => 15,
			'U' => 14
		];
		foreach ($columnsWidths as $key => $value) :
			$this->excel->getActiveSheet()->getStyle($key)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getColumnDimension($key)->setWidth($value);
		endforeach;

		$this->headerExcel();

		$startIndex = 11;
		$loop_index = 1;
		foreach ($data['purchase_hotlines'] as $purchase_hotline) :
			$rowspan = count($purchase_hotline['parts']);
			$mergeEnd = $rowspan + $startIndex - 1;


			$this->excel->setActiveSheetIndex(0)->setCellValue("A" . $startIndex, $loop_index);
			$this->excel->getActiveSheet()->getStyle("A{$startIndex}:A{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				),
			]);
			$this->excel->getActiveSheet()->mergeCells("A{$startIndex}:A{$mergeEnd}");

			$this->excel->setActiveSheetIndex(0)->setCellValue("B" . $startIndex, $purchase_hotline['tanggal_order'] );
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}:B{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->mergeCells("B{$startIndex}:B{$mergeEnd}");

			$this->excel->setActiveSheetIndex(0)->setCellValue("C" . $startIndex, $purchase_hotline['nomor_po'] );
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}:C{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->mergeCells("C{$startIndex}:C{$mergeEnd}");

			$this->excel->setActiveSheetIndex(0)->setCellValue("D" . $startIndex, $purchase_hotline['nama_customer'] );
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}:D{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->mergeCells("D{$startIndex}:D{$mergeEnd}");

			$this->excel->setActiveSheetIndex(0)->setCellValue("E" . $startIndex, $purchase_hotline['alamat'] );
			$this->excel->getActiveSheet()->getStyle("E{$startIndex}:E{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->mergeCells("E{$startIndex}:E{$mergeEnd}");

			$this->excel->setActiveSheetIndex(0)->setCellValue("F" . $startIndex, $purchase_hotline['no_hp'] );
			$this->excel->getActiveSheet()->getStyle("F{$startIndex}:F{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->mergeCells("F{$startIndex}:F{$mergeEnd}");

			$this->excel->setActiveSheetIndex(0)->setCellValue("G" . $startIndex, $purchase_hotline['no_rangka'] );
			$this->excel->getActiveSheet()->getStyle("G{$startIndex}:G{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->mergeCells("G{$startIndex}:G{$mergeEnd}");

			$this->excel->setActiveSheetIndex(0)->setCellValue("H" . $startIndex, $purchase_hotline['no_mesin'] );
			$this->excel->getActiveSheet()->getStyle("H{$startIndex}:H{$mergeEnd}")->applyFromArray([
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->mergeCells("H{$startIndex}:H{$mergeEnd}");

			$index_parts = 1;
			$itemsStyle = [
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				),
				'font' => array(
					'size'  => 10,
					'name'  => 'Tahoma'
				)
			];
			foreach ($purchase_hotline['parts'] as $part) :
				$this->excel->setActiveSheetIndex(0)->setCellValue("I" . ($startIndex + $index_parts - 1), $index_parts);
				$this->excel->getActiveSheet()->getStyle("I" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("J" . ($startIndex + $index_parts - 1), $part['id_part']);
				$this->excel->getActiveSheet()->getStyle("J" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("K" . ($startIndex + $index_parts - 1), $part['nama_part']);
				$this->excel->getActiveSheet()->getStyle("K" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("L" . ($startIndex + $index_parts - 1), $part['kuantitas']);
				$this->excel->getActiveSheet()->getStyle("L" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("M" . ($startIndex + $index_parts - 1), $part['qty_supply']);
				$this->excel->getActiveSheet()->getStyle("M" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("N" . ($startIndex + $index_parts - 1), $part['back_order']);
				$this->excel->getActiveSheet()->getStyle("N" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("O" . ($startIndex + $index_parts - 1), $part['eta_awal']);
				$this->excel->getActiveSheet()->getStyle("O" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("P" . ($startIndex + $index_parts - 1), $part['eta_revisi']);
				$this->excel->getActiveSheet()->getStyle("P" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("Q" . ($startIndex + $index_parts - 1), $part['tgl_packing_sheet']);
				$this->excel->getActiveSheet()->getStyle("Q" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("R" . ($startIndex + $index_parts - 1), $part['id_packing_sheet']);
				$this->excel->getActiveSheet()->getStyle("R" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("S" . ($startIndex + $index_parts - 1), $part['tanggal_terima_dari_md']);
				$this->excel->getActiveSheet()->getStyle("S" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("T" . ($startIndex + $index_parts - 1), $part['tanggal_terima_konsumen']);
				$this->excel->getActiveSheet()->getStyle("T" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$this->excel->setActiveSheetIndex(0)->setCellValue("U" . ($startIndex + $index_parts - 1), $part['lead_time']);
				$this->excel->getActiveSheet()->getStyle("U" . ($startIndex + $index_parts - 1))->applyFromArray($itemsStyle);
				$index_parts++;
			endforeach;
			$loop_index++;
			$startIndex = $mergeEnd + 1;
		endforeach;

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Laporan Hotline Order");
		$this->excel->setActiveSheetIndex(0);
		
        $this->download();
	}

	public function headerExcel(){
		$this->excel->setActiveSheetIndex(0)->setCellValue("A9", "NO");
		$this->excel->getActiveSheet()->mergeCells("A9:A10");
		$this->excel->getActiveSheet()->getStyle("A9:A10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("B9", "Tgl PO Dealer");
		$this->excel->getActiveSheet()->mergeCells("B9:B10");
		$this->excel->getActiveSheet()->getStyle("B9:B10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("C9", "NO. PO HTL Dealer");
		$this->excel->getActiveSheet()->mergeCells("C9:C10");
		$this->excel->getActiveSheet()->getStyle("C9:C10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("D9", "Nama Konsumen");
		$this->excel->getActiveSheet()->mergeCells("D9:D10");
		$this->excel->getActiveSheet()->getStyle("D9:D10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("E9", "Alamat Konsumen");
		$this->excel->getActiveSheet()->mergeCells("E9:E10");
		$this->excel->getActiveSheet()->getStyle("E9:E10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("F9", "No. Telp Konsumen");
		$this->excel->getActiveSheet()->mergeCells("F9:F10");
		$this->excel->getActiveSheet()->getStyle("F9:F10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("G9", "No. Rangka");
		$this->excel->getActiveSheet()->mergeCells("G9:G10");
		$this->excel->getActiveSheet()->getStyle("G9:G10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("H9", "No. Mesin");
		$this->excel->getActiveSheet()->mergeCells("H9:H10");
		$this->excel->getActiveSheet()->getStyle("H9:H10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("I9", "Item No.");
		$this->excel->getActiveSheet()->mergeCells("I9:I10");
		$this->excel->getActiveSheet()->getStyle("I9:I10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("J9", "Part Number");
		$this->excel->getActiveSheet()->mergeCells("J9:J10");
		$this->excel->getActiveSheet()->getStyle("J9:J10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("K9", "Deskripsi");
		$this->excel->getActiveSheet()->mergeCells("K9:K10");
		$this->excel->getActiveSheet()->getStyle("K9:K10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("L9", "Order");
		$this->excel->getActiveSheet()->mergeCells("L9:L10");
		$this->excel->getActiveSheet()->getStyle("L9:L10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("M9", "Supply");
		$this->excel->getActiveSheet()->mergeCells("M9:M10");
		$this->excel->getActiveSheet()->getStyle("M9:M10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("N9", "BO");
		$this->excel->getActiveSheet()->mergeCells("N9:N10");
		$this->excel->getActiveSheet()->getStyle("N9:N10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("O9", "ETA AWAL");
		$this->excel->getActiveSheet()->mergeCells("O9:O10");
		$this->excel->getActiveSheet()->getStyle("O9:O10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("P9", "ETA REVISI");
		$this->excel->getActiveSheet()->mergeCells("P9:P10");
		$this->excel->getActiveSheet()->getStyle("P9:P10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("Q9", "Penerimaan Sparepart dari MD");
		$this->excel->getActiveSheet()->mergeCells("Q9:S9");
		$this->excel->getActiveSheet()->getStyle("Q9:S9")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("Q10", "Tgl PS");
		$this->excel->getActiveSheet()->getStyle("Q10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("R10", "No. PS");
		$this->excel->getActiveSheet()->getStyle("R10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("S10", "Tgl Terima");
		$this->excel->getActiveSheet()->getStyle("S10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("T9", "Konsumen Terima Barang");
		$this->excel->getActiveSheet()->mergeCells("T9:U9");
		$this->excel->getActiveSheet()->getStyle("T9:U9")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("T10", "Tgl. Terima");
		$this->excel->getActiveSheet()->getStyle("T10")->applyFromArray($this->headerStyle());

		$this->excel->setActiveSheetIndex(0)->setCellValue("U10", "Lead Time");
		$this->excel->getActiveSheet()->getStyle("U10")->applyFromArray($this->headerStyle());
	}

	public function headerStyle(){
		return [
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff8a24')
			),
			'font' => array(
				'bold' => true,
				'size'  => 10,
				'name'  => 'Tahoma'
			)
		];
	}

	public function download(){
		ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Hotline Order.xlsx"'); // Set nama file excel nya
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
}