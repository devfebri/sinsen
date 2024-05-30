<?php

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class H3_md_laporan_pembayaran_ke_vendor_model extends Honda_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Mcarbon');

		// ini_set('memory_limit', '-1');
		// ini_set('max_execution_time', '0');
	}

	public function download($tanggal_entry_start, $tanggal_entry_end, $tanggal_transaksi_start, $tanggal_transaksi_end, $tanggal_pembayaran_start, $tanggal_pembayaran_end)
	{
		$tanggal_entry_start = $this->input->get('tanggal_entry_start');
		$tanggal_entry_end = $this->input->get('tanggal_entry_end');
		$tanggal_transaksi_start = $this->input->get('tanggal_transaksi_start');
		$tanggal_transaksi_end = $this->input->get('tanggal_transaksi_end');
		$tanggal_pembayaran_start = $this->input->get('tanggal_pembayaran_start');
		$tanggal_pembayaran_end = $this->input->get('tanggal_pembayaran_end');
		$data = $this->data($tanggal_entry_start, $tanggal_entry_end, $tanggal_transaksi_start, $tanggal_transaksi_end, $tanggal_pembayaran_start, $tanggal_pembayaran_end);

		$total_jumlah_terutang = array_map(function ($row) {
			return floatval($row['jumlah_terutang']);
		}, $data);
		$total_jumlah_terutang = array_sum($total_jumlah_terutang);

		$total_nominal = array_map(function ($row) {
			return floatval($row['nominal']);
		}, $data);
		$total_nominal = array_sum($total_nominal);

		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("assets/template/report_pembayaran_ke_vendor_template.xlsx");
		$sheet = $spreadsheet->getActiveSheet();

		$data_start_row = 4;
		$borders = [
			'borders' => array(
				'outline' => array(
					'borderStyle' => Border::BORDER_THIN
				),
			)
		];
		$index = 1;
		foreach ($data as $row) {
			$sheet->setCellValue(sprintf('A%s', $data_start_row), $index);
			$sheet->getStyle(sprintf('A%s', $data_start_row))->applyFromArray($borders);

			$sheet->setCellValue(sprintf('B%s', $data_start_row), $row['nama_vendor'] != null ? $row['nama_vendor'] : '-');
			$sheet->getStyle(sprintf('B%s', $data_start_row))->applyFromArray($borders);

			$sheet->setCellValue(sprintf('C%s', $data_start_row), Mcarbon::parse($row['approved_at'])->format('d/m/Y'));
			$sheet->getStyle(sprintf('C%s', $data_start_row))->applyFromArray($borders);

			$sheet->setCellValue(sprintf('D%s', $data_start_row), $row['jumlah_terutang']);
			$sheet->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($borders);
			$sheet->getStyle(sprintf('D%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

			$sheet->setCellValue(sprintf('E%s', $data_start_row), $row['nominal']);
			$sheet->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($borders);
			$sheet->getStyle(sprintf('E%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

			$sheet->setCellValue(sprintf('F%s', $data_start_row), $row['deskripsi']);
			$sheet->getStyle(sprintf('F%s', $data_start_row))->applyFromArray($borders);
			$sheet->getStyle(sprintf('F%s', $data_start_row))->getAlignment()->setWrapText(true);

			$data_start_row++;
			$index++;
		}

		$sheet->setCellValue(sprintf('A%s', $data_start_row), 'Total');
		$sheet->mergeCells(sprintf('A%s:C%s', $data_start_row, $data_start_row));
		$sheet->getStyle(sprintf('A%s:C%s', $data_start_row, $data_start_row))->applyFromArray($borders);
		$sheet->getStyle(sprintf('A%s:C%s', $data_start_row, $data_start_row))->applyFromArray([
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER
			]
		]);

		$sheet->setCellValue(sprintf('D%s', $data_start_row), $total_jumlah_terutang);
		$sheet->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($borders);
		$sheet->getStyle(sprintf('D%s', $data_start_row))->applyFromArray([
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_RIGHT
			]
		]);
		$sheet->getStyle(sprintf('D%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		$sheet->setCellValue(sprintf('E%s', $data_start_row), $total_nominal);
		$sheet->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($borders);
		$sheet->getStyle(sprintf('E%s', $data_start_row))->applyFromArray([
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_RIGHT
			]
		]);
		$sheet->getStyle(sprintf('E%s', $data_start_row))->getNumberFormat()->setFormatCode('Rp #,##0');

		$data_start_row++;

		$filename = 'Report Pembayaran ke vendor';

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output'); // download file 
	}

	public function data($tanggal_entry_start, $tanggal_entry_end, $tanggal_transaksi_start, $tanggal_transaksi_end, $tanggal_pembayaran_start, $tanggal_pembayaran_end)
	{
		$this->db
			->select('
            case
                when vp.tipe_penerima = "Dealer" then d.nama_dealer
            end as nama_vendor
        ', false)
			->select('epb.approved_at')
			->select('vpi.jumlah_terutang')
			->select('vpi.nominal')
			->select('vp.deskripsi')
			->from('tr_h3_md_voucher_pengeluaran as vp')
			->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_voucher_pengeluaran = vp.id_voucher_pengeluaran')
			->join('tr_h3_md_entry_pengeluaran_bank as epb', 'epb.id_voucher_pengeluaran_int = vp.id', 'left')
			->join('ms_dealer as d', 'd.id_dealer = vp.id_account', 'left')
			->join('ms_vendor as v', 'v.id_vendor = vp.id_account', 'left')
			->order_by('vp.created_at', 'desc');

		if ($tanggal_entry_start != null and $tanggal_entry_end != null) {
			$this->db->group_start();
			$this->db->where("vp.created_at between '{$tanggal_entry_start}' AND '{$tanggal_entry_end}'", null, false);
			$this->db->group_end();
		}

		if ($tanggal_transaksi_start != null and $tanggal_transaksi_end != null) {
			$this->db->group_start();
			$this->db->where("vp.tanggal_transaksi between '{$tanggal_transaksi_start}' AND '{$tanggal_transaksi_end}'", null, false);
			$this->db->group_end();
		}

		if ($tanggal_pembayaran_start != null and $tanggal_pembayaran_end != null) {
			$this->db->group_start();
			$this->db->where("epb.approved_at between '{$tanggal_pembayaran_start}  00:00:01' AND '{$tanggal_pembayaran_end} 23:59:59'", null, false);
			$this->db->group_end();
		}

		return $this->db->get()->result_array();
	}
}
