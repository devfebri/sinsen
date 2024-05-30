<?php

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class H3_md_laporan_list_ar_model extends Honda_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Mcarbon');

		// ini_set('memory_limit', '-1');
		// ini_set('max_execution_time', '0');
	}

	public function download($filters)
	{
		$customers = $this->getCustomerData($filters);
		$data = $this->getData($customers, $filters);

		$this->generateExcel($data, $filters);
	}

	private function getCustomerData($filters)
	{
		if (isset($filters['id_customer_filter'])) {
			$dealer = $this->db
				->select('d.id_dealer')
				->select('d.tipe_plafon_h3')
				->from('ms_dealer as d')
				->where('d.id_dealer', $filters['id_customer_filter'])
				->get()->row_array();

			if ($dealer == null) throw new Exception('Dealer tidak ditemukan');
		}

		$this->db
			->select('ar.id_dealer')
			->from('tr_h3_md_ar_part as ar')
			->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ar.referensi', 'left')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list', 'left')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref', 'left')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order', 'left')
			->join('ms_dealer as d', 'd.id_dealer = ar.id_dealer', 'left');

		if (isset($filters['history']) and $filters['history'] == 1) {
			$this->db->where('ar.lunas', 1);
		} else {
			$this->db->where('ar.lunas', 0);
		}

		if (isset($filters['no_referensi_filter'])) $this->db->like('ar.referensi', $filters['no_referensi_filter']);
		if (isset($filters['jenis_transaksi_filter'])) $this->db->like('so.produk', $filters['jenis_transaksi_filter']);

		if (isset($filters['tanggal_jatuh_tempo_filter_start']) and isset($filters['tanggal_jatuh_tempo_filter_end'])) {
			$this->db->group_start();
			$this->db->where('ar.tanggal_jatuh_tempo >=', $filters['tanggal_jatuh_tempo_filter_start']);
			$this->db->where('ar.tanggal_jatuh_tempo <=', $filters['tanggal_jatuh_tempo_filter_end']);
			$this->db->group_end();
		}

		if (isset($filters['id_customer_filter'])) {
			if ($dealer != null) {
				if ($dealer['tipe_plafon_h3'] == 'gimmick') {
					$this->db->where('ar.gimmick', 1);
				} else if ($dealer['tipe_plafon_h3'] == 'kpb') {
					$this->db->where('ar.kpb', 1);
				} else {
					$this->db->where('ar.id_dealer', $filters['id_customer_filter']);
					$this->db->where('ar.gimmick', 0);
					$this->db->where('ar.kpb', 0);
				}
			}
		}

		if (isset($filters['tanggal_batas_akhir_referensi'])) $this->db->where('ar.tanggal_jatuh_tempo <=', $filters['tanggal_batas_akhir_referensi']);

		$data = array_map(function ($row) {
			return $row['id_dealer'];
		}, $this->db->get()->result_array());

		return array_unique($data);
	}

	public function getData($customers, $filters)
	{
		$dealers = $this->db
			->select('d.id_dealer')
			->select('d.nama_dealer')
			->select('d.tipe_plafon_h3')
			->from('ms_dealer as d')
			->where_in('d.id_dealer', $customers)
			->order_by('nama_dealer')
			->get()->result_array();

		$dealers = array_map(function ($dealer) use ($filters) {
			$this->db
				->select('ar.referensi')
				->select('ar.tanggal_jatuh_tempo')
				->select('ar.total_amount')
				->select('(ar.total_amount - ar.sudah_dibayar) as sisa_piutang', false)
				->from('tr_h3_md_ar_part as ar')
				->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ar.referensi', 'left')
				->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list', 'left')
				->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref', 'left')
				->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order', 'left')
				->join('ms_dealer as d', 'd.id_dealer = ar.id_dealer', 'left')
				->order_by('ar.tanggal_jatuh_tempo', 'asc');

			if (isset($filters['history']) and $filters['history'] == 1) {
				$this->db->where('ar.lunas', 1);
			} else {
				$this->db->where('ar.lunas', 0);
			}

			if (isset($filters['no_referensi_filter'])) $this->db->like('ar.referensi', $filters['no_referensi_filter']);
			if (isset($filters['jenis_transaksi_filter'])) $this->db->like('so.produk', $filters['jenis_transaksi_filter']);

			if (isset($filters['tanggal_jatuh_tempo_filter_start']) and isset($filters['tanggal_jatuh_tempo_filter_end'])) {
				$this->db->group_start();
				$this->db->where('ar.tanggal_jatuh_tempo >=', $filters['tanggal_jatuh_tempo_filter_start']);
				$this->db->where('ar.tanggal_jatuh_tempo <=', $filters['tanggal_jatuh_tempo_filter_end']);
				$this->db->group_end();
			}

			if ($dealer['tipe_plafon_h3'] == 'gimmick') {
				$this->db->where('ar.gimmick', 1);
			} else if ($dealer['tipe_plafon_h3'] == 'kpb') {
				$this->db->where('ar.kpb', 1);
			} else {
				$this->db->where('ar.id_dealer', $dealer['id_dealer']);
				$this->db->where('ar.gimmick', 0);
				$this->db->where('ar.kpb', 0);
			}

			if (isset($filters['tanggal_batas_akhir_referensi'])) $this->db->where('ar.tanggal_jatuh_tempo <=', $filters['tanggal_batas_akhir_referensi']);

			$dealer['list_ar'] = $this->db->get()->result_array();
			$dealer['total'] = array_sum(
				array_map(function ($row) {
					return floatval($row['sisa_piutang']);
				}, $dealer['list_ar'])
			);

			return $dealer;
		}, $dealers);

		return $dealers;
	}

	private function generateExcel($data, $filters)
	{
		$filename = 'Laporan list AR';

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$spreadsheet->getProperties()
			->setCreator('SSP')
			->setLastModifiedBy('SSP')
			->setTitle($filename);

		$sheet = $spreadsheet->getActiveSheet();




		$tableHeaderBorders = [
			'borders' => array(
				'outline' => array(
					'borderStyle' => Border::BORDER_THIN
				),
			)
		];

		$sideBorders = [
			'borders' => array(
				'left' => array(
					'borderStyle' => Border::BORDER_THIN
				),
				'right' => array(
					'borderStyle' => Border::BORDER_THIN
				),
			)
		];

		$topBorders = [
			'borders' => array(
				'top' => array(
					'borderStyle' => Border::BORDER_THIN
				),
			)
		];

		$sheet->setCellValue('A1', 'Piutang Jatuh Tempo');

		if (isset($filters['tanggal_batas_akhir_referensi'])) {
			$tanggal = Mcarbon::parse($filters['tanggal_batas_akhir_referensi'])->format('d');
			$bulan = lang('month_' . Mcarbon::parse($filters['tanggal_batas_akhir_referensi'])->format('n'));
			$tahun = Mcarbon::parse($filters['tanggal_batas_akhir_referensi'])->format('Y');

			$sheet->setCellValue('A2', sprintf('Per Tgl. %s %s %s', $tanggal, $bulan, $tahun));
		}


		$sheet->getRowDimension('1')->setRowHeight(30);

		$sheet->getStyle('A1')->applyFromArray([
			'font' => [
				'bold' => true,
				'size' => 16
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],
		]);

		$sheet->getStyle('A2')->applyFromArray([
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],
		]);

		$sheet->mergeCells('A1:F1');
		$sheet->mergeCells('A2:F2');

		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(13);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue('A4', 'No.');
		$sheet->setCellValue('B4', 'Nama Customer');
		$sheet->setCellValue('C4', 'No. Faktur');
		$sheet->setCellValue('D4', 'Jatuh Tempo');
		$sheet->setCellValue('E4', 'Nom. Faktur');
		$sheet->setCellValue('F4', 'Sisa Piutang');
		$sheet->getStyle('A4')->applyFromArray($tableHeaderBorders);
		$sheet->getStyle('B4')->applyFromArray($tableHeaderBorders);
		$sheet->getStyle('C4')->applyFromArray($tableHeaderBorders);
		$sheet->getStyle('D4')->applyFromArray($tableHeaderBorders);
		$sheet->getStyle('E4')->applyFromArray($tableHeaderBorders);
		$sheet->getStyle('F4')->applyFromArray($tableHeaderBorders);

		$loopStart = 5;
		$totalKeseluruhan = 0;
		foreach ($data as $index => $dealer) {
			$sheet->setCellValue('A' . $loopStart, $index + 1);
			$sheet->setCellValue('B' . $loopStart, $dealer['nama_dealer']);

			foreach ($dealer['list_ar'] as $ar_part) {
				$sheet->getStyle('A' . $loopStart)->applyFromArray($sideBorders);
				$sheet->getStyle('B' . $loopStart)->applyFromArray($sideBorders);
				$sheet->getStyle('C' . $loopStart)->applyFromArray($sideBorders);
				$sheet->getStyle('D' . $loopStart)->applyFromArray($sideBorders);
				$sheet->getStyle('E' . $loopStart)->applyFromArray($sideBorders);
				$sheet->getStyle('F' . $loopStart)->applyFromArray($sideBorders);

				$sheet->setCellValue('C' . $loopStart, $ar_part['referensi']);
				$sheet->setCellValue('D' . $loopStart, Mcarbon::parse($ar_part['tanggal_jatuh_tempo'])->format('d-m-Y'));
				$sheet->setCellValue('E' . $loopStart, $ar_part['total_amount']);
				$sheet->getStyle('E' . $loopStart)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->setCellValue('F' . $loopStart, $ar_part['sisa_piutang']);
				$sheet->getStyle('F' . $loopStart)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

				$totalKeseluruhan += floatval($ar_part['sisa_piutang']);
				$loopStart++;
			}
			$sheet->getStyle('A' . $loopStart)->applyFromArray($sideBorders);
			$sheet->getStyle('B' . $loopStart)->applyFromArray($sideBorders);
			$sheet->getStyle('C' . $loopStart)->applyFromArray($sideBorders);
			$sheet->getStyle('D' . $loopStart)->applyFromArray($sideBorders);
			$sheet->getStyle('E' . $loopStart)->applyFromArray($sideBorders);
			$sheet->getStyle('F' . $loopStart)->applyFromArray($sideBorders);
			$sheet->getStyle('F' . $loopStart)->applyFromArray($topBorders);

			$sheet->setCellValue('F' . $loopStart, $dealer['total']);
			$sheet->getStyle('F' . $loopStart)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
			$loopStart++;
		}

		$sheet->setCellValue('A' . $loopStart, 'Total');
		$sheet->mergeCells(sprintf('A%s:E%s', $loopStart, $loopStart));
		$sheet->getStyle(sprintf('A%s:E%s', $loopStart, $loopStart))->applyFromArray($tableHeaderBorders);
		$sheet->setCellValue('F' . $loopStart,  $totalKeseluruhan);
		$sheet->getStyle('F' . $loopStart)->applyFromArray($tableHeaderBorders);
		$sheet->getStyle('F' . $loopStart)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output'); // download file 
	}
}
