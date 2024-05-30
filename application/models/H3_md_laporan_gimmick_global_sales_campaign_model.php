<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class H3_md_laporan_gimmick_global_sales_campaign_model extends Honda_Model
{

	private $excel;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('Mcarbon');
		$this->load->helper('language');
	}

	private function validation($id){
		$sales_campaign = $this->db
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.id', $id)
		->get()->row_array();

		if($sales_campaign == null){
			throw new Exception('Sales campaign tidak ditemukan');
		}

		if($sales_campaign['jenis_reward_gimmick'] != 1 AND $sales_campaign['produk_program_gimmick'] != 'Global'){
			throw new Exception(sprintf('Sales campaign %s - %s tidak berjenis gimmick dan tidak bertipe global [%s]', $sales_campaign['kode_campaign'], $sales_campaign['nama'], $sales_campaign['id']));
		}
	}

	public function laporan($id){
		$this->validation($id);
		$this->createExcel($id);
	}

	private function createExcel($id){
		$this->excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$sales_campaign = $this->db
			->select('sc.nama')
			->select('
			CASE
				WHEN sc.start_date_gimmick IS NOT NULL THEN sc.start_date_gimmick
				ELSE sc.start_date
			END AS start_date
		', false)
			->select('
			CASE
				WHEN sc.end_date_gimmick IS NOT NULL THEN sc.end_date_gimmick
				ELSE sc.end_date
			END AS end_date
		', false)
			->select('sc.produk_program_gimmick')
			->select('sc.satuan_rekapan_gimmick')
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $id)
			->get()->row_array();

		$sales_campaign_details = $this->db
			->select('sc_detail.*')
			->select('p.nama_part')
			->select('sc.jenis_item_gimmick')
			->from('ms_h3_md_sales_campaign_detail_gimmick as sc_detail')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = sc_detail.id_campaign')
			->join('ms_part as p', 'p.id_part = sc_detail.id_part', 'left')
			->where('sc_detail.id_campaign', $id)
			->get()->result_array();

		$query_perolehan = [];
		foreach ($sales_campaign_details as $sales_campaign_detail) {
			$key = $sales_campaign_detail['id'] . '_detail';

			$this->db
				->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_details as perolehan_detail')
				->where('perolehan_detail.id_campaign', $this->input->get('id'))
				->where('perolehan_detail.id_detail', $sales_campaign_detail['id'])
				->where('perolehan_detail.id_perolehan = perolehan.id', null, false);

			if ($sales_campaign['satuan_rekapan_gimmick'] == 'Satuan') {
				$this->db->select('perolehan_detail.jumlah_kuantitas_yang_tercapai');
			} else if ($sales_campaign['satuan_rekapan_gimmick'] == 'Dus') {
				$this->db->select('perolehan_detail.jumlah_dus_yang_tercapai');
			}

			$query = $this->db->get_compiled_select();

			$query_perolehan[$key] = $query;
		}

		$sales_campaign_globals = $this->db
			->select('sc_global.*')
			->select('p.nama_part')
			->select('CONCAT(sc_global.id, "_global") as label_key', false)
			->from('ms_h3_md_sales_campaign_detail_gimmick_global as sc_global')
			->join('ms_part as p', 'p.id_part = sc_global.id_part', 'left')
			->where('sc_global.id_campaign', $this->input->get('id'))
			->get()->result_array();

		$query_perolehan_hadiah = [];
		foreach ($sales_campaign_globals as $sales_campaign_global) {
			$query = $this->db
				->select('perolehan_global.count_gimmick')
				->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_global as perolehan_global')
				->where('perolehan_global.id_campaign', $this->input->get('id'))
				->where('perolehan_global.id_gimmick_global', $sales_campaign_global['id'])
				->where('perolehan_global.id_perolehan = perolehan.id', null, false)
				->get_compiled_select();

			$query_perolehan_hadiah[$sales_campaign_global['label_key']] = $query;
		}

		$count_total_hadiah = $this->db
		->select('SUM(pscgg.count_gimmick) as count_gimmick', false)
		->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_global as pscgg')
		->where('pscgg.id_perolehan = perolehan.id', null, false)
		->get_compiled_select();

		$this->db
			->select('perolehan.id')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('perolehan.total_pembelian')
			->select('perolehan.total_pembelian_dus')
			->select('perolehan.total_pembelian_sisa')
			->select('perolehan.total_pembelian_dus_sisa')
			->select('perolehan.sudah_create_so')
			->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan')
			->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
			->where('perolehan.id_campaign', $this->input->get('id'))
			->where(sprintf('IFNULL((%s), 0) > 0', $count_total_hadiah), null, false)
			->order_by('d.nama_dealer', 'asc');

		foreach ($query_perolehan_hadiah as $key => $row_query) {
			$this->db->select("IFNULL(({$row_query}), 0) as {$key}", false);
		}

		foreach ($query_perolehan as $key => $row_query) {
			$this->db->select("IFNULL(({$row_query}), 0) as {$key}", false);
		}

		$perolehan = array_map(function($row){
			$this->db
			->select('SUM(count_gimmick) as count_gimmick', false)
			->select('scdgg.nama_hadiah')
			->select('scdgg.qty_hadiah')
			->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_global as pscgg')
			->join('ms_h3_md_sales_campaign_detail_gimmick_global as scdgg',' scdgg.id_campaign = pscgg.id_gimmick_global')
			->where('pscgg.id_perolehan', $row['id'])
			->where('pscgg.count_gimmick > 0', null, false)
			->group_by('pscgg.count_gimmick');

			$gimmick_globals = array_map(function($gimmick_global){
				return sprintf('%s %s', (intval($gimmick_global['count_gimmick']) * intval($gimmick_global['qty_hadiah'])), $gimmick_global['nama_hadiah']);
			}, $this->db->get()->result_array());

			$row['total_hadiah'] = implode(',', $gimmick_globals);

			$row['gimmick_globals'] = $gimmick_globals;
			
			return $row;
		}, $this->db->get()->result_array());

		$this->excel->getDefaultStyle()
			->applyFromArray([
				'font'  => [
					'size'  => 10,
					'name'  => 'Trebuchet MS'
				]
			]);

		$kop_style = [
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
		];

		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'FORM REKAPAN PENCAIRAN HADIAH');
		$this->excel->getActiveSheet()->getStyle("A1")->applyFromArray($kop_style);
		$headerMergeColumn = 3 + count($sales_campaign_details) + 1 + count($sales_campaign_globals) + 2;
		$headerMergeColumnLetter = Coordinate::stringFromColumnIndex($headerMergeColumn);
		$this->excel->getActiveSheet()->mergeCells("A1:{$headerMergeColumnLetter}1");

		$this->excel->setActiveSheetIndex(0)->setCellValue('A2', $sales_campaign['nama']);
		$this->excel->getActiveSheet()->mergeCells("A2:{$headerMergeColumnLetter}2");
		$this->excel->getActiveSheet()->getStyle("A2")->applyFromArray($kop_style);

		$start_date = Mcarbon::parse($sales_campaign['start_date']);
		$end_date = Mcarbon::parse($sales_campaign['end_date']);
		$perbedaan_bulan = $start_date->diffInMonths($end_date);

		$this->excel->setActiveSheetIndex(0)->setCellValue('A3', "{$start_date->format('d/m/Y')} - {$end_date->format('d/m/Y')}");
		$this->excel->getActiveSheet()->mergeCells("A3:{$headerMergeColumnLetter}3");
		$this->excel->getActiveSheet()->getStyle("A3")->applyFromArray($kop_style);

		$labelPeriodePembelianColumnNumber = 3 + count($sales_campaign_details) + 1;
		$labelPeriodePembelianColumnLetter = Coordinate::stringFromColumnIndex($labelPeriodePembelianColumnNumber);
		$start_date_month = lang('month_' . $start_date->format('n'));
		$end_date_month = lang('month_' . $end_date->format('n'));
		if ($perbedaan_bulan == 0) {
			$this->excel->setActiveSheetIndex(0)->setCellValue("A5", "Rincian pembelian bulan {$start_date_month} {$start_date->format('Y')}");
		} else {
			$this->excel->setActiveSheetIndex(0)->setCellValue("A5", "Rincian pembelian bulan {$start_date_month} {$start_date->format('Y')} - {$end_date_month} {$end_date->format('Y')}");
		}

		$this->excel->getActiveSheet()->mergeCells("A5:{$labelPeriodePembelianColumnLetter}5");
		$this->excel->getActiveSheet()->getStyle("A5:{$labelPeriodePembelianColumnLetter}5")->applyFromArray([
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		]);

		$this->excel->getActiveSheet()->getRowDimension('6')->setRowHeight(90);

		$this->excel->setActiveSheetIndex(0)->setCellValue("A6", 'No');
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
		$this->excel->getActiveSheet()->getStyle("A6")->applyFromArray([
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		]);
		$this->excel->setActiveSheetIndex(0)->setCellValue("B6", 'Kode Dealer');
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->excel->getActiveSheet()->getStyle("B6")->applyFromArray([
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		]);
		$this->excel->setActiveSheetIndex(0)->setCellValue("C6", 'Nama Dealer');
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
		$this->excel->getActiveSheet()->getStyle("C6")->applyFromArray([
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		]);

		$letterNumberForCampaignDetail = 4;
		foreach ($sales_campaign_details as $sales_campaign_detail) {
			$letterForCampaignDetail = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
			$satuan = $sales_campaign['satuan_rekapan_gimmick'] == 'Dus' ? '(DUS)' : '';
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}6", "{$sales_campaign_detail['nama_part']} ({$sales_campaign_detail['id_part']}) {$satuan}");
			$this->excel->getActiveSheet()->getColumnDimension($letterForCampaignDetail)->setWidth(18);
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}6")->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}6")->applyFromArray([
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'top' => [
						'borderStyle'  => Border::BORDER_THIN
					],
					'right' => [
						'borderStyle'  => Border::BORDER_THIN
					],
					'bottom' => [
						'borderStyle'  => Border::BORDER_THIN
					],
					'left' => [
						'borderStyle'  => Border::BORDER_THIN
					]
				]
			]);
			$letterNumberForCampaignDetail++;
		}

		$letterForTotalPembelian = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForTotalPembelian}6", "Total Pembelian {$satuan}");
		$this->excel->getActiveSheet()->getColumnDimension($letterForTotalPembelian)->setWidth(10);
		$this->excel->getActiveSheet()->getStyle("{$letterForTotalPembelian}6")->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->getStyle("{$letterForTotalPembelian}6")->applyFromArray([
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		]);
		$letterNumberForCampaignDetail++;

		foreach ($sales_campaign_globals as $sales_campaign_global) {
			$letterForCampaignDetail = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}5", "Jumlah Bonus {$sales_campaign_global['nama_part']} ({$sales_campaign_global['satuan_hadiah']})");
			$this->excel->getActiveSheet()->mergeCells("{$letterForCampaignDetail}5:{$letterForCampaignDetail}6");
			$this->excel->getActiveSheet()->getColumnDimension($letterForCampaignDetail)->setWidth(18);
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}5:{$letterForCampaignDetail}6")->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}5:{$letterForCampaignDetail}6")->applyFromArray([
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'top' => [
						'borderStyle'  => Border::BORDER_THIN
					],
					'right' => [
						'borderStyle'  => Border::BORDER_THIN
					],
					'bottom' => [
						'borderStyle'  => Border::BORDER_THIN
					],
					'left' => [
						'borderStyle'  => Border::BORDER_THIN
					]
				]
			]);
			$letterNumberForCampaignDetail++;
		}

		$letterForSisaTotalPembelian = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForSisaTotalPembelian}5", "Sisa Pembelian Tidak Dihitung {$satuan}");
		$this->excel->getActiveSheet()->mergeCells("{$letterForSisaTotalPembelian}5:{$letterForSisaTotalPembelian}6");
		$this->excel->getActiveSheet()->getColumnDimension($letterForSisaTotalPembelian)->setWidth(18);
		$this->excel->getActiveSheet()->getStyle("{$letterForSisaTotalPembelian}5:{$letterForSisaTotalPembelian}6")->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->getStyle("{$letterForSisaTotalPembelian}5:{$letterForSisaTotalPembelian}6")->applyFromArray([
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		]);

		$letterForTotalHadiah = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForTotalHadiah}5", 'Total Hadiah');
		$this->excel->getActiveSheet()->mergeCells("{$letterForTotalHadiah}5:{$letterForTotalHadiah}6");
		$this->excel->getActiveSheet()->getColumnDimension($letterForTotalHadiah)->setWidth(30);
		$this->excel->getActiveSheet()->getStyle("{$letterForTotalHadiah}5:{$letterForTotalHadiah}6")->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->getStyle("{$letterForTotalHadiah}5:{$letterForTotalHadiah}6")->applyFromArray([
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER,
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		]);

		$loopStart = 7;
		$all_borders = [
			'borders' => [
				'top' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle'  => Border::BORDER_THIN
				],
				'left' => [
					'borderStyle'  => Border::BORDER_THIN
				]
			]
		];
		
		foreach ($perolehan as $row) {
			$this->excel->setActiveSheetIndex(0)->setCellValue("A{$loopStart}", $loopStart - 6);
			$this->excel->getActiveSheet()->getStyle("A{$loopStart}")->applyFromArray($all_borders);
			$this->excel->setActiveSheetIndex(0)->setCellValueExplicit("B{$loopStart}", $row['kode_dealer_md'], DataType::TYPE_STRING);
			$this->excel->getActiveSheet()->getStyle("B{$loopStart}")->applyFromArray($all_borders);
			$this->excel->setActiveSheetIndex(0)->setCellValue("C{$loopStart}", $row['nama_dealer']);
			$this->excel->getActiveSheet()->getStyle("C{$loopStart}")->applyFromArray($all_borders);

			$letterNumberForCampaignDetail = 4;
			foreach ($sales_campaign_details as $sales_campaign_detail) {
				$letterForCampaignDetail = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}{$loopStart}", $row[$sales_campaign_detail['id'] . '_detail']);
				$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}{$loopStart}")->applyFromArray($all_borders);
				$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}{$loopStart}")->getNumberFormat()->setFormatCode('#,##0');
				$letterNumberForCampaignDetail++;
			}

			$letterForTotalPembelian = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
			if ($sales_campaign['satuan_rekapan_gimmick'] == 'Satuan') {
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForTotalPembelian}{$loopStart}", $row['total_pembelian']);
			} else if ($sales_campaign['satuan_rekapan_gimmick'] == 'Dus') {
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForTotalPembelian}{$loopStart}", $row['total_pembelian_dus']);
			}
			$this->excel->getActiveSheet()->getStyle("{$letterForTotalPembelian}{$loopStart}")->applyFromArray($all_borders);
			$this->excel->getActiveSheet()->getStyle("{$letterForTotalPembelian}{$loopStart}")->getNumberFormat()->setFormatCode('#,##0');
			$letterNumberForCampaignDetail++;

			foreach ($sales_campaign_globals as $sales_campaign_global) {
				$letterForCampaignDetail = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}{$loopStart}", $row[$sales_campaign_global['id'] . '_global']);
				$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}{$loopStart}")->applyFromArray($all_borders);
				$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}{$loopStart}")->getNumberFormat()->setFormatCode('#,##0');
				$letterNumberForCampaignDetail++;
			}

			$letterForSisaTotalPembelian = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
			if ($sales_campaign['satuan_rekapan_gimmick'] == 'Satuan') {
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForSisaTotalPembelian}{$loopStart}", $row['total_pembelian_sisa']);
			} else if ($sales_campaign['satuan_rekapan_gimmick'] == 'Dus') {
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForSisaTotalPembelian}{$loopStart}", $row['total_pembelian_dus_sisa']);
			}
			$this->excel->getActiveSheet()->getStyle("{$letterForSisaTotalPembelian}{$loopStart}")->applyFromArray($all_borders);
			$this->excel->getActiveSheet()->getStyle("{$letterForSisaTotalPembelian}{$loopStart}")->getNumberFormat()->setFormatCode('#,##0');

			$letterForTotalHadiah = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail + 1);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForTotalHadiah}{$loopStart}", $row['total_hadiah']);
			$this->excel->getActiveSheet()->getStyle("{$letterForTotalHadiah}{$loopStart}")->applyFromArray($all_borders);

			$loopStart++;
		}

		// Settingan awal fil excel
		$this->excel->getProperties()
			->setCreator('SSP')
			->setLastModifiedBy('SSP')
			->setTitle('Report Pencairan Poin Sales Campaign Tidak Langsung');

		$this->excel->getDefaultStyle()
			->applyFromArray([
				'font'  => [
					'size'  => 10,
					'name'  => 'Trebuchet MS'
				]
			]);

		$this->downloadExcel($id);
	}

	public function downloadExcel($id)
	{
		$sales_campaign = $this->db
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.id', $id)
		->get()->row_array();

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
		ob_end_clean();
		$filename = sprintf('%s_LAPORAN GIMMICK SALES CAMPAIGN %s', Mcarbon::now()->timestamp, $sales_campaign['nama']);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}
