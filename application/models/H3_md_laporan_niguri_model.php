<?php

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class H3_md_laporan_niguri_model extends CI_Model
{
	private $excel; 
	private $title; 

    public function __construct(){
        parent::__construct();

        $this->load->library('Mcarbon');

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
    }

    public function generateExcel($id){
		$this->excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		
		$data = $this->data($id);
		$niguri_header = $data['niguri_header'];
		$parts = $data['parts'];

		$this->title = 'Niguri Periode ' . date('m/Y', strtotime($niguri_header['tanggal_generate']));

		$this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle($this->title);

		$style_kolom_tabel = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
			],
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			)
        ];

		$this->excel->setActiveSheetIndex(0)->setCellValue('B2', 'Periode :');
		$this->excel->setActiveSheetIndex(0)->setCellValue('C2', Mcarbon::parse($niguri_header['tanggal_generate'])->format('F Y'));

		$this->excel->setActiveSheetIndex(0)->setCellValue('A4', 'NIGURI STOK HGP');
		$this->excel->getActiveSheet()->mergeCells("A4:AC4");
        $this->excel->getActiveSheet()->getStyle("A4:AC4")->applyFromArray([
			'fill' => array(
				'fillType' => Fill::FILL_SOLID,
				'color' => array('rgb' => '002060')
			),
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			),
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FFFFFF'),
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER
			],
		]);

		$this->excel->getActiveSheet()->mergeCells("A5:AC5");
        $this->excel->getActiveSheet()->getStyle("A5:AC5")->applyFromArray([
			'fill' => array(
				'fillType' => Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff0000')
			),
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			)
		]);

		$this->excel->setActiveSheetIndex(0)->setCellValue('G6', 'Sales 6 Bulan Terakhir (Qty Sales)');
		$this->excel->getActiveSheet()->mergeCells("G6:L6");
        $this->excel->getActiveSheet()->getStyle("G6:L6")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('X6', 'Fore Cast');
		$this->excel->getActiveSheet()->mergeCells("X6:AC6");
        $this->excel->getActiveSheet()->getStyle("X6:AC6")->applyFromArray($style_kolom_tabel);

		$this->excel->setActiveSheetIndex(0)->setCellValue('A6', 'No.');
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		$this->excel->getActiveSheet()->mergeCells("A6:A7");
        $this->excel->getActiveSheet()->getStyle('A6:A7')->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('B6', 'Part Number');
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("B6:B7");
        $this->excel->getActiveSheet()->getStyle("B6:B7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('C6', 'Description');
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
		$this->excel->getActiveSheet()->mergeCells("C6:C7");
        $this->excel->getActiveSheet()->getStyle("C6:C7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('D6', 'Kelompok Part');
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("D6:D7");
        $this->excel->getActiveSheet()->getStyle("D6:D7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('E6', 'HET');
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->excel->getActiveSheet()->mergeCells("E6:E7");
        $this->excel->getActiveSheet()->getStyle("E6:E7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('F6', 'HPP');
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->excel->getActiveSheet()->mergeCells("F6:F7");
        $this->excel->getActiveSheet()->getStyle("F6:F7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('G7', lang('short_month_' . Mcarbon::parse($niguri_header['tanggal_generate'])->subDays(6 * 30)->format('n') ) );
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle('G7')->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('H7', lang('short_month_' . Mcarbon::parse($niguri_header['tanggal_generate'])->subDays(5 * 30)->format('n') ) );
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle('H7')->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('I7', lang('short_month_' . Mcarbon::parse($niguri_header['tanggal_generate'])->subDays(4 * 30)->format('n') ) );
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle('I7')->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('J7', lang('short_month_' . Mcarbon::parse($niguri_header['tanggal_generate'])->subDays(3 * 30)->format('n') ) );
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle('J7')->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('K7', lang('short_month_' . Mcarbon::parse($niguri_header['tanggal_generate'])->subDays(2 * 30)->format('n') ) );
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle('K7')->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('L7', lang('short_month_' . Mcarbon::parse($niguri_header['tanggal_generate'])->subDays(1 * 30)->format('n') ) );
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle('L7')->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('M6', 'AVG');
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
		$this->excel->getActiveSheet()->mergeCells("M6:M7");
        $this->excel->getActiveSheet()->getStyle("M6:M7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('N6', 'S/L');
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
		$this->excel->getActiveSheet()->mergeCells("N6:N7");
        $this->excel->getActiveSheet()->getStyle("N6:N7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('O6', 'Qty Suggest');
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("O6:O7");
        $this->excel->getActiveSheet()->getStyle("O6:O7")->applyFromArray($style_kolom_tabel);

		$tanggal_generate = Mcarbon::parse($niguri_header['tanggal_generate'])->startOfMonth();
		$this->excel->setActiveSheetIndex(0)->setCellValue('P6', lang(sprintf('month_%s', $tanggal_generate->copy()->format('n'))));
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("P6:P7");
        $this->excel->getActiveSheet()->getStyle("P6:P7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('Q6', 'Amount ' . lang(sprintf('month_%s', $tanggal_generate->copy()->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
		$this->excel->getActiveSheet()->mergeCells("Q6:Q7");
        $this->excel->getActiveSheet()->getStyle("Q6:Q7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('R6', 'Qty Int');
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("R6:R7");
        $this->excel->getActiveSheet()->getStyle("R6:R7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('S6', 'Qty Avs');
        $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("S6:S7");
        $this->excel->getActiveSheet()->getStyle("S6:S7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('T6', lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(1)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("T6:T7");
        $this->excel->getActiveSheet()->getStyle("T6:T7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('U6', 'Amount ' . lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(1)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
		$this->excel->getActiveSheet()->mergeCells("U6:U7");
        $this->excel->getActiveSheet()->getStyle("U6:U7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('V6', lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(2)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
		$this->excel->getActiveSheet()->mergeCells("V6:V7");
        $this->excel->getActiveSheet()->getStyle("V6:V7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('W6', 'Amount ' . lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(2)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
		$this->excel->getActiveSheet()->mergeCells("W6:W7");
        $this->excel->getActiveSheet()->getStyle("W6:W7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('X7', lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(3)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
        $this->excel->getActiveSheet()->getStyle("X7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('Y7', 'Amount ' . lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(3)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("Y7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('Z7', lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(4)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
        $this->excel->getActiveSheet()->getStyle("Z7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('AA7', 'Amount ' . lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(4)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("AA7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('AB7', lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(5)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
        $this->excel->getActiveSheet()->getStyle("AB7")->applyFromArray($style_kolom_tabel);
		$this->excel->setActiveSheetIndex(0)->setCellValue('AC7', 'Amount ' . lang(sprintf('month_%s', $tanggal_generate->copy()->addMonths(5)->format('n'))));
		$this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("AC7")->applyFromArray($style_kolom_tabel);

		$starting_point_row_for_parts = 8;
		$starting_point_columns_for_parts = 1;
		$index = 1;

		$style_row_tabel = [
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
			],
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			)
        ];
		foreach ($parts as $part) {
			$this->excel->setActiveSheetIndex(0)->setCellValue('A' . $starting_point_row_for_parts, $index);
			$this->excel->getActiveSheet()->getStyle("A" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->setActiveSheetIndex(0)->setCellValue('B' . $starting_point_row_for_parts, $part['id_part']);
			$this->excel->getActiveSheet()->getStyle("B" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->setActiveSheetIndex(0)->setCellValue('C' . $starting_point_row_for_parts, $part['nama_part']);
			$this->excel->getActiveSheet()->getStyle("C" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->setActiveSheetIndex(0)->setCellValue('D' . $starting_point_row_for_parts, $part['kelompok_part']);
			$this->excel->getActiveSheet()->getStyle("D" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->setActiveSheetIndex(0)->setCellValue('E' . $starting_point_row_for_parts, $part['het']);
			$this->excel->getActiveSheet()->getStyle("E" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
            $this->excel->getActiveSheet()->getStyle("E" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->setActiveSheetIndex(0)->setCellValue('F' . $starting_point_row_for_parts, $part['hpp']);
			$this->excel->getActiveSheet()->getStyle("F" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
            $this->excel->getActiveSheet()->getStyle("F" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->setActiveSheetIndex(0)->setCellValue('G' . $starting_point_row_for_parts, $part['keenam']);
			$this->excel->getActiveSheet()->getStyle("G" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("G" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('H' . $starting_point_row_for_parts, $part['kelima']);
			$this->excel->getActiveSheet()->getStyle("H" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("H" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('I' . $starting_point_row_for_parts, $part['keempat']);
			$this->excel->getActiveSheet()->getStyle("I" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("I" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('J' . $starting_point_row_for_parts, $part['ketiga']);
			$this->excel->getActiveSheet()->getStyle("J" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("J" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('K' . $starting_point_row_for_parts, $part['kedua']);
			$this->excel->getActiveSheet()->getStyle("K" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("K" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('L' . $starting_point_row_for_parts, $part['pertama']);
			$this->excel->getActiveSheet()->getStyle("L" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("L" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('M' . $starting_point_row_for_parts, $part['average']);
			$this->excel->getActiveSheet()->getStyle("M" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("M" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('N' . $starting_point_row_for_parts, $part['s_l']);
			$this->excel->getActiveSheet()->getStyle("N" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("N" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('O' . $starting_point_row_for_parts, $part['qty_suggest']);
			$this->excel->getActiveSheet()->getStyle("O" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("O" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('P' . $starting_point_row_for_parts, $part['fix_order_n']);
			$this->excel->getActiveSheet()->getStyle("P" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("P" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('Q' . $starting_point_row_for_parts, $part['amount_fix_order_n']);
			$this->excel->getActiveSheet()->getStyle("Q" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
            $this->excel->getActiveSheet()->getStyle("Q" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->setActiveSheetIndex(0)->setCellValue('R' . $starting_point_row_for_parts, $part['qty_intransit']);
			$this->excel->getActiveSheet()->getStyle("R" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("R" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('S' . $starting_point_row_for_parts, $part['qty_avs']);
			$this->excel->getActiveSheet()->getStyle("S" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("S" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('T' . $starting_point_row_for_parts, $part['fix_order_n_1']);
			$this->excel->getActiveSheet()->getStyle("T" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("T" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('U' . $starting_point_row_for_parts, $part['amount_fix_order_n_1']);
			$this->excel->getActiveSheet()->getStyle("U" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
            $this->excel->getActiveSheet()->getStyle("U" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->setActiveSheetIndex(0)->setCellValue('V' . $starting_point_row_for_parts, $part['fix_order_n_2']);
			$this->excel->getActiveSheet()->getStyle("V" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("V" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('W' . $starting_point_row_for_parts, $part['amount_fix_order_n_2']);
			$this->excel->getActiveSheet()->getStyle("W" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("W" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->setActiveSheetIndex(0)->setCellValue('X' . $starting_point_row_for_parts, $part['fix_order_n_3']);
			$this->excel->getActiveSheet()->getStyle("X" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("X" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('Y' . $starting_point_row_for_parts, $part['amount_fix_order_n_3']);
			$this->excel->getActiveSheet()->getStyle("Y" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("Y" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->setActiveSheetIndex(0)->setCellValue('Z' . $starting_point_row_for_parts, $part['fix_order_n_4']);
			$this->excel->getActiveSheet()->getStyle("Z" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("Z" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('AA' . $starting_point_row_for_parts, $part['amount_fix_order_n_4']);
			$this->excel->getActiveSheet()->getStyle("AA" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("AA" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$this->excel->setActiveSheetIndex(0)->setCellValue('AB' . $starting_point_row_for_parts, $part['fix_order_n_5']);
			$this->excel->getActiveSheet()->getStyle("AB" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("AB" . $starting_point_row_for_parts)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]);
			$this->excel->setActiveSheetIndex(0)->setCellValue('AC' . $starting_point_row_for_parts, $part['amount_fix_order_n_5']);
			$this->excel->getActiveSheet()->getStyle("AC" . $starting_point_row_for_parts)->applyFromArray($style_row_tabel);
			$this->excel->getActiveSheet()->getStyle("AC" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
			$starting_point_row_for_parts++;
			$index++;
		}

		$row_total = $starting_point_row_for_parts;
		$this->excel->setActiveSheetIndex(0)->setCellValue("A{$row_total}", 'Total');
		$this->excel->getActiveSheet()->mergeCells("A{$row_total}:O{$row_total}");
        $this->excel->getActiveSheet()->getStyle("A{$row_total}:O{$row_total}")->applyFromArray([
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			),
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_RIGHT,
				'vertical' => Alignment::VERTICAL_CENTER
			],
		]);

		$style_qty_fix_order = [
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			),
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER
			],
		];
		
		$style_amount_fix_order = [
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			),
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_RIGHT,
				'vertical' => Alignment::VERTICAL_CENTER
			],
		];

		$starting_row = 8;
		$this->excel->setActiveSheetIndex(0)->setCellValue("P{$row_total}", '=SUM(P' . $starting_row . ':P' . (count($parts) + $starting_row - 1) . ')');
        $this->excel->getActiveSheet()->getStyle("P{$row_total}")->applyFromArray($style_qty_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("Q{$row_total}", '=SUM(Q' . $starting_row . ':Q' . (count($parts) + $starting_row - 1) . ')');
		$this->excel->getActiveSheet()->getStyle("Q" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
		$this->excel->getActiveSheet()->getStyle("Q{$row_total}")->applyFromArray($style_amount_fix_order);
		
        $this->excel->getActiveSheet()->getStyle("R{$row_total}")->applyFromArray([
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			),
		]);
		$this->excel->getActiveSheet()->getStyle("S{$row_total}")->applyFromArray([
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				)
			),
		]);


		$this->excel->setActiveSheetIndex(0)->setCellValue("T{$row_total}", '=SUM(T' . $starting_row . ':T' . (count($parts) + $starting_row - 1) . ')');
        $this->excel->getActiveSheet()->getStyle("T{$row_total}")->applyFromArray($style_qty_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("U{$row_total}", '=SUM(U' . $starting_row . ':U' . (count($parts) + $starting_row - 1) . ')');
		$this->excel->getActiveSheet()->getStyle("U" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
        $this->excel->getActiveSheet()->getStyle("U{$row_total}")->applyFromArray($style_amount_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("V{$row_total}", '=SUM(V' . $starting_row . ':V' . (count($parts) + $starting_row - 1) . ')');
        $this->excel->getActiveSheet()->getStyle("V{$row_total}")->applyFromArray($style_qty_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("W{$row_total}", '=SUM(W' . $starting_row . ':W' . (count($parts) + $starting_row - 1) . ')');
		$this->excel->getActiveSheet()->getStyle("W" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
        $this->excel->getActiveSheet()->getStyle("W{$row_total}")->applyFromArray($style_amount_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("X{$row_total}", '=SUM(X' . $starting_row . ':X' . (count($parts) + $starting_row - 1) . ')');
        $this->excel->getActiveSheet()->getStyle("X{$row_total}")->applyFromArray($style_qty_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("Y{$row_total}", '=SUM(Y' . $starting_row . ':Y' . (count($parts) + $starting_row - 1) . ')');
		$this->excel->getActiveSheet()->getStyle("Y" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
        $this->excel->getActiveSheet()->getStyle("Y{$row_total}")->applyFromArray($style_amount_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("Z{$row_total}", '=SUM(Z' . $starting_row . ':Z' . (count($parts) + $starting_row - 1) . ')');
        $this->excel->getActiveSheet()->getStyle("Z{$row_total}")->applyFromArray($style_qty_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("AA{$row_total}", '=SUM(AA' . $starting_row . ':AA' . (count($parts) + $starting_row - 1) . ')');
		$this->excel->getActiveSheet()->getStyle("AA" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
        $this->excel->getActiveSheet()->getStyle("AA{$row_total}")->applyFromArray($style_amount_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("AB{$row_total}", '=SUM(AB' . $starting_row . ':AB' . (count($parts) + $starting_row - 1) . ')');
        $this->excel->getActiveSheet()->getStyle("AB{$row_total}")->applyFromArray($style_qty_fix_order);
		$this->excel->setActiveSheetIndex(0)->setCellValue("AC{$row_total}", '=SUM(AC' . $starting_row . ':AC' . (count($parts) + $starting_row - 1) . ')');
		$this->excel->getActiveSheet()->getStyle("AC" . $starting_point_row_for_parts)->getNumberFormat()->setFormatCode('Rp #,##0');
        $this->excel->getActiveSheet()->getStyle("AC{$row_total}")->applyFromArray($style_amount_fix_order);

		$this->excel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$this->downloadExcel();
    }

    private function downloadExcel(){
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
        ob_end_clean();
		$filename = $this->title;
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
    }

    public function data($id){
        $niguri_header = (array) $this->niguri_header->find($this->input->get('id'));
		$parts = $this->db
		->select('n.id_part')
		->select('p.nama_part')
		->select('p.kelompok_part')
		->select('n.het')
		->select('n.hpp')
		->select('n.pertama')
		->select('n.kedua')
		->select('n.ketiga')
		->select('n.keempat')
		->select('n.kelima')
		->select('n.keenam')
		->select('n.average')
		->select('n.s_l')
		->select('n.qty_suggest')
		->select('n.qty_avs')
		->select('n.fix_order_n')
		->select('(n.fix_order_n * n.hpp) as amount_fix_order_n')
		->select('n.qty_intransit')
        ->select('n.fix_order_n_1')
        ->select('(n.fix_order_n_1 * n.hpp) as amount_fix_order_n_1')
        ->select('n.fix_order_n_2')
        ->select('(n.fix_order_n_2 * n.hpp) as amount_fix_order_n_2')
        ->select('n.fix_order_n_3')
        ->select('(n.fix_order_n_3 * n.hpp) as amount_fix_order_n_3')
        ->select('n.fix_order_n_4')
        ->select('(n.fix_order_n_4 * n.hpp) as amount_fix_order_n_4')
        ->select('n.fix_order_n_5')
        ->select('(n.fix_order_n_5 * n.hpp) as amount_fix_order_n_5')
		->from('tr_h3_md_niguri as n')
		->join('ms_part as p', 'p.id_part = n.id_part')
		->where('n.id_niguri_header', $id)
		->get()->result_array();

		return [
			'niguri_header' => $niguri_header,
			'parts' => $parts
		];
    }
}
