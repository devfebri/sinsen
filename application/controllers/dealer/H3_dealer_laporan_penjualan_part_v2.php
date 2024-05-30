<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_laporan_penjualan_part_v2 extends Honda_Controller
{
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_penjualan_part_v2";
	var $title  = "Laporan Penjualan Part V2";

	public function __construct()
	{
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name == "") echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";

		$this->load->database();
		$this->load->model('m_admin');
		$this->load->library('Mcarbon');
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$this->template($data);
	}

	public function test()
	{
		$data = $this->db
			->select('nsc.no_nsc')
			->select('c.nama_customer')
			->select('c.no_polisi')
			->select('
				case
					when jasa.id_type = "ASS1" then "KPB 1"
					when (jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then "Claim"
					else null
				end as keterangan
			', false)
			->select('sop.id_part')
			->select('p.nama_part')
			->select('
				case
					when sop.id_promo is null then sop.tipe_diskon
					else ""
				end as tipe_diskon
			', false)
			->select('
				case
					when sop.id_promo is null then ifnull(sop.diskon_value, 0)
					else 0
				end as diskon_value
			', false)
			->select('
				case
					when sop.id_promo is not null then sop.tipe_diskon
					else ""
				end as tipe_diskon_promo
			', false)
			->select('
				case
					when sop.id_promo is not null then ifnull(sop.diskon_value, 0)
					else 0
				end as diskon_value_promo
			', false)
			->select('(sop.kuantitas - sop.kuantitas_return) as qty')
			->select('sop.harga_saat_dibeli')
			->select('
				case
					when (jasa.id_type != "ASS1" AND jasa.id_type != "C1" AND jasa.id_type != "C2" AND jasa.id_type != "C3") then nsc.tot_nsc
					else 0
				end as total_nsc
			')
			->select('
				case
					when (jasa.id_type != "ASS1" AND jasa.id_type != "C1" AND jasa.id_type != "C2" AND jasa.id_type != "C3") then wo.total_jasa
					else 0
				end as total_njb
			')
			->select('
				case
					when (jasa.id_type = "ASS1" OR jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then nsc.tot_nsc
					else 0
				end as total_nsc_khusus
			')
			->select('
				case
					when (jasa.id_type = "ASS1" OR jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then wo.total_jasa
					else 0
				end as total_njb_khusus
			')
			->from('tr_h3_dealer_sales_order as so')
			->join('tr_h3_dealer_sales_order_parts as sop', 'so.nomor_so = sop.nomor_so')
			->join('ms_part as p', 'p.id_part_int = sop.id_part_int', 'left')
			->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
			->join('tr_h23_nsc as nsc', 'nsc.id_referensi = so.nomor_so')
			->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = so.id_work_order', 'left')
			->join('tr_h2_wo_dealer_parts as wop', '(wop.id_work_order = wo.id_work_order and wop.id_part = sop.id_part)', 'left')
			->join('ms_h2_jasa as jasa', 'jasa.id_jasa = wop.id_jasa', 'left')
			->get()->result_array();

		send_json($data);
	}

	public function download_excel()
	{
		include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
		$excel = PHPExcel_IOFactory::load("assets/template/laporan_penjualan_part_dealer_template.xlsx");

		$dealer = $this->db
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.alamat')
			->select('d.no_telp')
			->from('ms_dealer as d')
			->where('d.id_dealer', $this->m_admin->cari_dealer())
			->get()->row_array();

		$periode_start = Mcarbon::parse($this->input->get('periode_filter_start'));
		$periode_end = Mcarbon::parse($this->input->get('periode_filter_end'));
		$excel->setActiveSheetIndex(0)->setCellValue('B7',  $periode_start->format('d/m/Y'));
		$excel->setActiveSheetIndex(0)->setCellValue('B8',  $periode_end->format('d/m/Y'));

		$this->db
		->select('nsc.created_at as tanggal')
		->select('wo.no_njb')
		->select('nsc.no_nsc')
		->select('c.nama_customer')
		->select('c.no_polisi')
		->select('so.nomor_so')
		->from('tr_h3_dealer_sales_order as so')
		->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = so.id_work_order', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
		->join('tr_h23_nsc as nsc', 'nsc.id_referensi = so.nomor_so')
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		->where("so.tanggal_so between '{$periode_start}' AND '{$periode_end}'", null, false)
		->order_by('nsc.created_at', 'desc');

		$data = array_map(function($row){
			$row['parts'] = $this->db
			->select('nsc.no_nsc')
			->select('c.nama_customer')
			->select('c.no_polisi')
			->select('
				case
					when jasa.id_type = "ASS1" then "KPB 1"
					when (jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then "Claim"
					else null
				end as keterangan
			', false)
			->select('sop.id_part')
			->select('p.nama_part')
			->select('
				case
					when sop.id_promo is null then sop.tipe_diskon
					else ""
				end as tipe_diskon
			', false)
			->select('
				case
					when sop.id_promo is null then ifnull(sop.diskon_value, 0)
					else 0
				end as diskon_value
			', false)
			->select('
				case
					when sop.id_promo is not null then sop.tipe_diskon
					else ""
				end as tipe_diskon_promo
			', false)
			->select('
				case
					when sop.id_promo is not null then ifnull(sop.diskon_value, 0)
					else 0
				end as diskon_value_promo
			', false)
			->select('(sop.kuantitas - sop.kuantitas_return) as qty')
			->select('sop.harga_saat_dibeli')
			->select('
				case
					when (jasa.id_type != "ASS1" AND jasa.id_type != "C1" AND jasa.id_type != "C2" AND jasa.id_type != "C3") then nsc.tot_nsc
					else 0
				end as total_nsc
			')
			->select('
				case
					when (jasa.id_type != "ASS1" AND jasa.id_type != "C1" AND jasa.id_type != "C2" AND jasa.id_type != "C3") then wo.total_jasa
					else 0
				end as total_njb
			')
			->select('
				case
					when (jasa.id_type = "ASS1" OR jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then nsc.tot_nsc
					else 0
				end as total_nsc_khusus
			')
			->select('
				case
					when (jasa.id_type = "ASS1" OR jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then wo.total_jasa
					else 0
				end as total_njb_khusus
			')
			->from('tr_h3_dealer_sales_order as so')
			->join('tr_h3_dealer_sales_order_parts as sop', 'so.nomor_so = sop.nomor_so')
			->join('ms_part as p', 'p.id_part_int = sop.id_part_int', 'left')
			->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
			->join('tr_h23_nsc as nsc', 'nsc.id_referensi = so.nomor_so')
			->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = so.id_work_order', 'left')
			->join('tr_h2_wo_dealer_parts as wop', '(wop.id_work_order = wo.id_work_order and wop.id_part = sop.id_part)', 'left')
			->join('ms_h2_jasa as jasa', 'jasa.id_jasa = wop.id_jasa', 'left')
            ->where('so.id_dealer', $this->m_admin->cari_dealer())
			->where('so.nomor_so', $row['nomor_so'])
			->get()->result_array();

			$row['total'] = array_sum(
				array_map(function($each){
					return floatval($each['total_nsc']) + floatval($each['total_njb']);
				}, $row['parts'])
			);

			$row['total_khusus'] = array_sum(
				array_map(function($each){
					return floatval($each['total_nsc_khusus']) + floatval($each['total_njb_khusus']);
				}, $row['parts'])
			);

			return $row;
		}, $this->db->get()->result_array());

		$excel->setActiveSheetIndex(0)->setCellValue('B1', $dealer['kode_dealer_md']);
		$excel->setActiveSheetIndex(0)->setCellValue('B2', $dealer['nama_dealer']);
		$excel->setActiveSheetIndex(0)->setCellValue('B3', $dealer['alamat']);
		$excel->setActiveSheetIndex(0)->setCellValue('B4', $dealer['no_telp']);


		$allborders = [
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				),
			)
		];
		$start_row = 11;
		$grand_total = [];
		$grand_total_khusus = [];
		foreach ($data as $row) {
			$excel->setActiveSheetIndex(0)->setCellValue("A{$start_row}", Mcarbon::parse($row['tanggal'])->format('d/m/Y'));
			$excel->setActiveSheetIndex(0)->setCellValue("B{$start_row}", $row['no_njb']);
			$excel->setActiveSheetIndex(0)->setCellValue("C{$start_row}", $row['no_nsc']);
			$excel->setActiveSheetIndex(0)->setCellValue("D{$start_row}", $row['nama_customer']);
			$excel->setActiveSheetIndex(0)->setCellValue("E{$start_row}", $row['no_polisi']);

			foreach($row['parts'] as $part){
				$excel->setActiveSheetIndex(0)->setCellValue("F{$start_row}", $part['keterangan']);
				$excel->setActiveSheetIndex(0)->setCellValue("G{$start_row}", $part['id_part']);
				$excel->setActiveSheetIndex(0)->setCellValue("H{$start_row}", $part['nama_part']);
				$excel->setActiveSheetIndex(0)->setCellValue("I{$start_row}", $part['harga_saat_dibeli']);
				$excel->getActiveSheet()->getStyle("I{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');

				if($part['tipe_diskon'] == 'Percentage'){
					$excel->setActiveSheetIndex(0)->setCellValue("J{$start_row}", $part['diskon_value'] . '%');
				}else if($part['tipe_diskon'] == 'Value'){
					$excel->setActiveSheetIndex(0)->setCellValue("J{$start_row}", 'Rp ' . $part['diskon_value']);
				}else{
					$excel->setActiveSheetIndex(0)->setCellValue("J{$start_row}", $part['diskon_value']);
				}

				if($part['tipe_diskon_promo'] == 'Percentage'){
					$excel->setActiveSheetIndex(0)->setCellValue("K{$start_row}", $part['diskon_value_promo'] . '%');
				}else if($part['tipe_diskon_promo'] == 'Value'){
					$excel->setActiveSheetIndex(0)->setCellValue("K{$start_row}", 'Rp ' . $part['diskon_value_promo']);
				}else{
					$excel->setActiveSheetIndex(0)->setCellValue("K{$start_row}", $part['diskon_value_promo']);
				}

				$excel->setActiveSheetIndex(0)->setCellValue("L{$start_row}", $part['qty']);
				$excel->setActiveSheetIndex(0)->setCellValue("M{$start_row}", $part['total_nsc']);
				$excel->getActiveSheet()->getStyle("M{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');
				$excel->setActiveSheetIndex(0)->setCellValue("N{$start_row}", $part['total_njb']);
				$excel->getActiveSheet()->getStyle("N{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');
				$excel->setActiveSheetIndex(0)->setCellValue("O{$start_row}", $part['total_nsc_khusus']);
				$excel->getActiveSheet()->getStyle("O{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');
				$excel->setActiveSheetIndex(0)->setCellValue("P{$start_row}", $part['total_njb_khusus']);
				$excel->getActiveSheet()->getStyle("P{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');

				$start_row++;
			}

			$grand_total[] = "M{$start_row}";
			$excel->setActiveSheetIndex(0)->setCellValue("M{$start_row}", $row['total']);
			$excel->getActiveSheet()->getStyle("M{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');
			$excel->setActiveSheetIndex(0)->mergeCells("M{$start_row}:N{$start_row}");
			$excel->getActiveSheet()->getStyle("M{$start_row}:N{$start_row}")->applyFromArray([
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				),
				'borders' => array(
					'top' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					),
				)
			]);

			$grand_total_khusus[] = "O{$start_row}";
			$excel->setActiveSheetIndex(0)->setCellValue("O{$start_row}", $row['total_khusus']);
			$excel->getActiveSheet()->getStyle("O{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');
			$excel->setActiveSheetIndex(0)->mergeCells("O{$start_row}:P{$start_row}");
			$excel->getActiveSheet()->getStyle("O{$start_row}:P{$start_row}")->applyFromArray([
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				),
				'borders' => array(
					'top' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					),
				)
			]);
			$start_row++;
		}

		$excel->setActiveSheetIndex(0)->setCellValue("K{$start_row}", "Grand Total");
		$excel->setActiveSheetIndex(0)->mergeCells("K{$start_row}:L{$start_row}");
		$excel->getActiveSheet()->getStyle("K{$start_row}:L{$start_row}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
		]);

		$rumus = '';
		foreach($grand_total as $row){
			$rumus .= '+' . $row;
		}
		$rumus = substr($rumus, 1);
		$excel->setActiveSheetIndex(0)->setCellValue("M{$start_row}", "=SUM({$rumus})");
		$excel->getActiveSheet()->getStyle("M{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');
		$excel->setActiveSheetIndex(0)->mergeCells("M{$start_row}:N{$start_row}");
		$excel->getActiveSheet()->getStyle("M{$start_row}:N{$start_row}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				),
			)
		]);

		$rumus = '';
		foreach($grand_total_khusus as $row){
			$rumus .= '+' . $row;
		}
		$excel->setActiveSheetIndex(0)->setCellValue("O{$start_row}", "=SUM({$rumus})");
		$excel->getActiveSheet()->getStyle("O{$start_row}")->getNumberFormat()->setFormatCode('Rp #,##0');
		$excel->setActiveSheetIndex(0)->mergeCells("O{$start_row}:P{$start_row}");
		$excel->getActiveSheet()->getStyle("O{$start_row}:P{$start_row}")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				),
			)
		]);

		ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=Laporan Penjualan Part {$periode_start->format('d-m-Y')} s.d {$periode_end->format('d-m-Y')}.xlsx"); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
		ob_end_clean();
	}
}
