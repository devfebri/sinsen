<?php

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class H3_md_laporan_retur_pembelian_claim_model extends Honda_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Mcarbon');
	}

	private function getData($periode_mulai, $periode_berakhir)
	{
		$data = $this->db
			->select('rpc.no_retur')
			->select('DATE_FORMAT(rpc.tanggal, "%d/%m/%Y") as tanggal')
			->select('rpc.id_claim')
			->select('DATE_FORMAT(cmda.created_at, "%d/%m/%Y") as tanggal_claim')
			->select('rpci.id_part')
			->select('kc.kode_claim')
			->select('kc.nama_claim')
			->select('p.nama_part')
			->select('rpci.qty')
			->select('fdo_parts.price')
			->select('rpci.nominal')
			->select('DATE_FORMAT(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
			->select('ps.packing_sheet_number')
			->select('DATE_FORMAT(fdo.invoice_date, "%d/%m/%Y") as invoice_date')
			->select('fdo.invoice_number')
			->from('tr_h3_md_retur_pembelian_claim as rpc')
			->join('tr_h3_md_retur_pembelian_claim_items as rpci', 'rpci.no_retur = rpc.no_retur')
			->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = rpc.id_claim')
			->join('ms_kategori_claim_c3 as kc', 'kc.id = rpci.id_kode_claim')
			->join('ms_part as p', 'p.id_part = rpci.id_part')
			->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cmda.packing_sheet_number')
			->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = cmda.invoice_number', 'left')
			->join('tr_h3_md_fdo_parts as fdo_parts', '(fdo_parts.id_part = rpci.id_part and fdo_parts.nomor_packing_sheet = ps.packing_sheet_number and fdo_parts.invoice_number = fdo.invoice_number)', 'left')
			->group_start()
			->where('rpc.tanggal >=', $periode_mulai)
			->where('rpc.tanggal <=', $periode_berakhir)
			->group_end()
			->get()->result_array();

		return $data;
	}

	public function generateExcel($periode_mulai, $periode_berakhir)
	{
		$data = $this->getData($periode_mulai, $periode_berakhir);

		$this->load->helper('get_letter_for_excel');
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');

		$filename = 'Laporan retur pembelian';

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$spreadsheet->getProperties()
			->setCreator('SSP')
			->setLastModifiedBy('SSP')
			->setTitle($filename);

		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(14);
		$sheet->getColumnDimension('C')->setWidth(14);
		$sheet->getColumnDimension('D')->setWidth(14);
		$sheet->getColumnDimension('E')->setWidth(23);
		$sheet->getColumnDimension('F')->setWidth(31);
		$sheet->getColumnDimension('G')->setWidth(18);
		$sheet->getColumnDimension('H')->setWidth(30);
		$sheet->getColumnDimension('I')->setWidth(8);
		$sheet->getColumnDimension('J')->setWidth(14);
		$sheet->getColumnDimension('K')->setWidth(14);
		$sheet->getColumnDimension('L')->setWidth(14);
		$sheet->getColumnDimension('M')->setWidth(18);
		$sheet->getColumnDimension('N')->setWidth(14);
		$sheet->getColumnDimension('O')->setWidth(18);

		$sheet->getRowDimension('7')->setRowHeight(30);

		$sheet->setCellValue('A1', 'Laporan Retur Pembelian');
		$sheet->mergeCells("A1:O1");
		$sheet->getStyle("A1:O1")->applyFromArray([
			'font' => [
				'bold' => true,
				'name'  => 'Tahoma',
				'size'  => 16
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER
			],
		]);

		$sheet->setCellValue('B3', 'Periode');
		$sheet->setCellValue('C3', ': ' . date('d/m/Y', strtotime($periode_mulai)) . ' - ' . date('d/m/Y', strtotime($periode_berakhir)));

		$sheet->setCellValue('J4', 'Tgl & Wkt');
		$sheet->setCellValue('K4', ': ' . date('d/m/Y H:i', time()));

		$style_kolom_tabel = [
			'font' => [
				'bold' => true,
				'name'  => 'Tahoma',
				'size'  => 10
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER
			],
			'borders' => array(
				'top' => array(
					'style' => Border::BORDER_MEDIUM
				),
				'bottom' => array(
					'style' => Border::BORDER_MEDIUM
				)
			)
		];

		$sheet->setCellValue('A7', 'No.');
		$sheet->getStyle('A7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('B7', 'Tgl. Retur');
		$sheet->getStyle('B7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('C7', 'No. Retur');
		$sheet->getStyle('C7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('D7', 'Tgl. Claim MD');
		$sheet->getStyle('D7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('E7', 'No. Claim MD');
		$sheet->getStyle('E7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('F7', 'Kode & Deskripsi Claim');
		$sheet->getStyle('F7')->applyFromArray($style_kolom_tabel);
		$sheet->getStyle('F7')->getAlignment()->setWrapText(true);
		$sheet->setCellValue('G7', 'Kode Part');
		$sheet->getStyle('G7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('H7', 'Deksripsi Part');
		$sheet->getStyle('H7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('I7', 'Qty');
		$sheet->getStyle('I7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('J7', 'Harga');
		$sheet->getStyle('J7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('K7', 'Total');
		$sheet->getStyle('K7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('L7', 'Tgl PS');
		$sheet->getStyle('L7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('M7', 'No. PS');
		$sheet->getStyle('M7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('N7', 'Tgl. Faktur');
		$sheet->getStyle('N7')->applyFromArray($style_kolom_tabel);
		$sheet->setCellValue('O7', 'No. Faktur');
		$sheet->getStyle('O7')->applyFromArray($style_kolom_tabel);

		$index = 1;
		$starting_point_for_row = 8;

		foreach ($data as $row) {
			$sheet->setCellValue('A' . $starting_point_for_row, $index);
			$sheet->getStyle('A' . $starting_point_for_row)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_LEFT,
				],
			]);
			$sheet->setCellValue('B' . $starting_point_for_row, $row['tanggal']);
			$sheet->setCellValue('C' . $starting_point_for_row, $row['no_retur']);
			$sheet->setCellValue('D' . $starting_point_for_row, $row['tanggal_claim']);
			$sheet->setCellValue('E' . $starting_point_for_row, $row['id_claim']);
			$sheet->setCellValue('F' . $starting_point_for_row, $row['kode_claim'] . ' - ' . $row['nama_claim']);
			$sheet->setCellValue('G' . $starting_point_for_row, $row['id_part']);
			$sheet->setCellValue('H' . $starting_point_for_row, $row['nama_part']);
			$sheet->setCellValue('I' . $starting_point_for_row, $row['qty']);
			$sheet->setCellValue('J' . $starting_point_for_row, $row['price']);
			$sheet->getStyle('J' . $starting_point_for_row)->getNumberFormat()->setFormatCode('Rp #,##0');
			$sheet->setCellValue('K' . $starting_point_for_row, $row['nominal']);
			$sheet->getStyle('K' . $starting_point_for_row)->getNumberFormat()->setFormatCode('Rp #,##0');
			$sheet->setCellValue('L' . $starting_point_for_row, $row['packing_sheet_date']);
			$sheet->setCellValue('M' . $starting_point_for_row, $row['packing_sheet_number']);
			$sheet->setCellValue('N' . $starting_point_for_row, $row['invoice_date']);
			$sheet->setCellValue('O' . $starting_point_for_row, $row['invoice_number']);
			$index++;
			$starting_point_for_row++;
		}

		$style_column_bottom = [
			'borders' => array(
				'top' => array(
					'style' => Border::BORDER_MEDIUM
				),
				'bottom' => array(
					'style' => Border::BORDER_MEDIUM
				)
			)
		];
		$sheet->mergeCells("A{$starting_point_for_row}:H{$starting_point_for_row}");
		$sheet->getStyle("A{$starting_point_for_row}:H{$starting_point_for_row}")->applyFromArray($style_column_bottom);
		$sheet->getStyle("I{$starting_point_for_row}")->applyFromArray($style_column_bottom);
		$sheet->setCellValue("I{$starting_point_for_row}", '=SUM(I' . ($starting_point_for_row - count($data)) . ':I' . ($starting_point_for_row - 1) . ')');

		$sheet->getStyle("J{$starting_point_for_row}")->applyFromArray($style_column_bottom);

		$sheet->getStyle("K{$starting_point_for_row}")->applyFromArray($style_column_bottom);
		$sheet->setCellValue("K{$starting_point_for_row}", '=SUM(K' . ($starting_point_for_row - count($data)) . ':K' . ($starting_point_for_row - 1) . ')');
		$sheet->getStyle('K' . $starting_point_for_row)->getNumberFormat()->setFormatCode('Rp #,##0');
		$sheet->mergeCells("L{$starting_point_for_row}:O{$starting_point_for_row}");
		$sheet->getStyle("L{$starting_point_for_row}:O{$starting_point_for_row}")->applyFromArray($style_column_bottom);


		$sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
	}
}
