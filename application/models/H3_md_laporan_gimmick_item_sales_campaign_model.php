<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class H3_md_laporan_gimmick_item_sales_campaign_model extends Honda_Model
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
			->select('sc.jenis_item_gimmick')
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

		$sales_campaign_details = array_map(function ($row) {
			$row['items'] = $this->db
				->select('sc_gimmick_item.*')
				->from('ms_h3_md_sales_campaign_detail_gimmick_item as sc_gimmick_item')
				->join('ms_h3_md_sales_campaign_detail_gimmick as sc_detail', 'sc_detail.id = sc_gimmick_item.id_detail_gimmick')
				->where('sc_detail.id_campaign', $row['id_campaign'])
				->where('sc_gimmick_item.id_detail_gimmick', $row['id'])
				->order_by('sc_gimmick_item.id_detail_gimmick', 'asc')
				->get()->result_array();

			return $row;
		}, $sales_campaign_details);


		$list_query_perolehan = [];
		$list_query_sisa = [];
		foreach ($sales_campaign_details as $sales_campaign_detail) {
			$this->db
				->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_details as perolehan_detail')
				->where('perolehan_detail.id_campaign', $id)
				->where('perolehan_detail.id_detail', $sales_campaign_detail['id'])
				->where('perolehan_detail.id_perolehan = perolehan.id', null, false);

			if ($sales_campaign['satuan_rekapan_gimmick'] == 'Satuan') {
				$this->db->select('perolehan_detail.jumlah_kuantitas_yang_tercapai');
			} else if ($sales_campaign['satuan_rekapan_gimmick'] == 'Dus') {
				$this->db->select('perolehan_detail.jumlah_dus_yang_tercapai');
			}

			$query_perolehan = $this->db->get_compiled_select();

			$list_query_perolehan["{$sales_campaign_detail['id']}_detail"] = $query_perolehan;

			$this->db
				->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_details as perolehan_detail')
				->where('perolehan_detail.id_campaign', $id)
				->where('perolehan_detail.id_detail', $sales_campaign_detail['id'])
				->where('perolehan_detail.id_perolehan = perolehan.id', null, false);

			if ($sales_campaign['satuan_rekapan_gimmick'] == 'Satuan') {
				$this->db->select('perolehan_detail.jumlah_kuantitas_yang_tercapai_sisa');
			} else if ($sales_campaign['satuan_rekapan_gimmick'] == 'Dus') {
				$this->db->select('perolehan_detail.jumlah_dus_yang_tercapai_sisa');
			}

			$query_sisa = $this->db->get_compiled_select();

			$list_query_sisa["{$sales_campaign_detail['id']}_sisa"] = $query_sisa;
		}

		$sales_campaign_gimmick_items = $this->db
			->select('sc_gimmick_item.id')
			->from('ms_h3_md_sales_campaign_detail_gimmick_item as sc_gimmick_item')
			->join('ms_h3_md_sales_campaign_detail_gimmick as sc_detail', 'sc_detail.id = sc_gimmick_item.id_detail_gimmick')
			->where('sc_detail.id_campaign', $id)
			->order_by('sc_gimmick_item.id_detail_gimmick', 'asc')
			->get()->result_array();
		$list_query_item = [];
		foreach ($sales_campaign_gimmick_items as $sales_campaign_gimmick_item) {
			$query_item = $this->db
				->select('SUM(perolehan_item.count_gimmick) as count_gimmick', false)
				->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_item as perolehan_item')
				->where('perolehan_item.id_campaign', $id)
				->where('perolehan_item.id_gimmick_item', $sales_campaign_gimmick_item['id'])
				->where('perolehan_item.id_perolehan = perolehan.id', null, false)
				->get_compiled_select();

			$list_query_item["{$sales_campaign_gimmick_item['id']}_item"] = $query_item;
		}

		$count_total_hadiah = $this->db
		->select('SUM(pscgti.count_gimmick) as count_gimmick')
		->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_item as pscgti')
		->where('pscgti.id_perolehan = perolehan.id', null, false)
		->where('pscgti.count_gimmick >', 0)
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
			->where('perolehan.id_campaign', $id)
			->where("IFNULL(({$count_total_hadiah}), 0) > 0", null, false)
			->order_by('d.nama_dealer', 'asc');

		foreach ($list_query_perolehan as $key => $row_query) {
			$this->db->select("IFNULL(({$row_query}), 0) as `{$key}`", false);
		}

		foreach ($list_query_item as $key => $row_query) {
			$this->db->select("IFNULL(({$row_query}), 0) as `{$key}`", false);
		}

		foreach ($list_query_sisa as $key => $row_query) {
			$this->db->select("IFNULL(({$row_query}), 0) as `{$key}`", false);
		}

		$perolehan = array_map(function($row){
			$campaign_gimmick_items = $this->db
			->select('pscgti.count_gimmick')
			->select('scdgi.nama_hadiah')
			->select('scdgi.qty_hadiah')
			->select('scdgi.satuan_hadiah')
			->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_item as pscgti')
			->join('ms_h3_md_sales_campaign_detail_gimmick_item as scdgi', 'scdgi.id = pscgti.id_gimmick_item')
			->where('pscgti.id_perolehan', $row['id'])
			->where('pscgti.count_gimmick >', 0)
			->get()->result_array();

			$campaign_gimmick_items = array_map(function($campaign_gimmick_item){
				$total_hadiah = intval($campaign_gimmick_item['count_gimmick']) * intval($campaign_gimmick_item['qty_hadiah']);
				return sprintf('%s %s %s', $total_hadiah, $campaign_gimmick_item['satuan_hadiah'], $campaign_gimmick_item['nama_hadiah']);
			}, $campaign_gimmick_items);

			$row['total_hadiah'] = implode(', ', $campaign_gimmick_items);

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
		$jumlah_item = 0;
		foreach ($sales_campaign_details as $sales_campaign_detail) {
			foreach ($sales_campaign_detail['items'] as $item) {
				$jumlah_item++;
			}
		}
		$headerMergeColumn = 3 + (count($sales_campaign_details) * 2) + $jumlah_item + 2;
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

		$this->excel->setActiveSheetIndex(0)->setCellValue("A5", 'No');
		$this->excel->getActiveSheet()->mergeCells("A5:A6");
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
		$this->excel->getActiveSheet()->getStyle("A5:A6")->applyFromArray([
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
		$this->excel->setActiveSheetIndex(0)->setCellValue("B5", 'Kode Dealer');
		$this->excel->getActiveSheet()->mergeCells("B5:B6");
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->excel->getActiveSheet()->getStyle("B5:B6")->applyFromArray([
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
		$this->excel->setActiveSheetIndex(0)->setCellValue("C5", 'Nama Dealer');
		$this->excel->getActiveSheet()->mergeCells("C5:C6");
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
		$this->excel->getActiveSheet()->getStyle("C5:C6")->applyFromArray([
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
			$satuan = $sales_campaign['satuan_rekapan_gimmick'] == 'Dus' ? '(DUS)' : '';

			$letterForCampaignDetail = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
			if($sales_campaign['jenis_item_gimmick'] == 'Per Kelompok Part'){
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}5", "{$sales_campaign_detail['id_kelompok_part']} {$satuan}");
			}else{
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}5", "{$sales_campaign_detail['nama_part']} ({$sales_campaign_detail['id_part']}) {$satuan}");
			}
			$this->excel->getActiveSheet()->getColumnDimension($letterForCampaignDetail)->setWidth(18);
			$this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(50);
			$this->excel->getActiveSheet()->mergeCells("{$letterForCampaignDetail}5:{$letterForCampaignDetail}6");
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}5")->getAlignment()->setWrapText(true);
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

		$letterNumberForCampaignGimmickItem = $letterNumberForCampaignDetail;
		foreach ($sales_campaign_details as $sales_campaign_detail) {
			foreach ($sales_campaign_detail['items'] as $item) {
				$letterNumberForCampaignGimmickItemColumn = Coordinate::stringFromColumnIndex($letterNumberForCampaignGimmickItem);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterNumberForCampaignGimmickItemColumn}6", "Jml Bonus {$item['qty_hadiah']} {$item['satuan_hadiah']} {$item['id_part']}");
				$this->excel->getActiveSheet()->getColumnDimension($letterNumberForCampaignGimmickItemColumn)->setWidth(25);
				$this->excel->getActiveSheet()->getStyle("{$letterNumberForCampaignGimmickItemColumn}6")->applyFromArray([
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
				$letterNumberForCampaignGimmickItem++;
			}

			$letterForCampaignDetail = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
			$letterNumberForCampaignDetailEnd = $letterNumberForCampaignDetail + count($sales_campaign_detail['items']) - 1;
			$letterForCampaignDetailEnd = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetailEnd);
			if($sales_campaign['jenis_item_gimmick'] == 'Per Kelompok Part'){
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}5", "Hadiah Pembelian {$sales_campaign_detail['id_kelompok_part']}");
			}else{
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}5", "Hadiah Pembelian {$sales_campaign_detail['id_part']}");
			}
			$this->excel->getActiveSheet()->mergeCells("{$letterForCampaignDetail}5:{$letterForCampaignDetailEnd}5");
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}5")->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}5:{$letterForCampaignDetailEnd}5")->applyFromArray([
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
			$letterNumberForCampaignDetail += count($sales_campaign_detail['items']);
		}

		foreach ($sales_campaign_details as $sales_campaign_detail) {
			$letterForCampaignDetail = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForCampaignDetail}5", "Sisa pembelian {$sales_campaign_detail['id_part']} {$satuan}");
			$this->excel->getActiveSheet()->getColumnDimension($letterForCampaignDetail)->setWidth(18);
			$this->excel->getActiveSheet()->mergeCells("{$letterForCampaignDetail}5:{$letterForCampaignDetail}6");
			$this->excel->getActiveSheet()->getStyle("{$letterForCampaignDetail}5")->getAlignment()->setWrapText(true);
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

		$letterForTotalHadiah = Coordinate::stringFromColumnIndex($letterNumberForCampaignDetail);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$letterForTotalHadiah}5", "Total Hadiah");
		$this->excel->getActiveSheet()->getColumnDimension($letterForTotalHadiah)->setWidth(18);
		$this->excel->getActiveSheet()->mergeCells("{$letterForTotalHadiah}5:{$letterForTotalHadiah}6");
		$this->excel->getActiveSheet()->getStyle("{$letterForTotalHadiah}5")->getAlignment()->setWrapText(true);
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
		foreach ($perolehan as $row) {
			$this->excel->setActiveSheetIndex(0)->setCellValue("A{$loopStart}", $loopStart - 6);
			$this->excel->getActiveSheet()->getStyle("A{$loopStart}")->applyFromArray([
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
			$this->excel->setActiveSheetIndex(0)->setCellValueExplicit("B{$loopStart}", $row['kode_dealer_md'], DataType::TYPE_STRING);
			$this->excel->getActiveSheet()->getStyle("B{$loopStart}")->applyFromArray([
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
			$this->excel->setActiveSheetIndex(0)->setCellValue("C{$loopStart}", $row['nama_dealer']);
			$this->excel->getActiveSheet()->getStyle("C{$loopStart}")->applyFromArray([
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

			$detailCampaignStart = 4;
			foreach ($sales_campaign_details as $sales_campaign_detail) {
				$detailCampaignStartLetter = Coordinate::stringFromColumnIndex($detailCampaignStart);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$detailCampaignStartLetter}{$loopStart}", $row[$sales_campaign_detail['id'] . '_detail']);
				$this->excel->getActiveSheet()->getStyle("{$detailCampaignStartLetter}{$loopStart}")->applyFromArray([
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
				$detailCampaignStart++;
			}

			foreach ($sales_campaign_details as $sales_campaign_detail) {
				foreach ($sales_campaign_detail['items'] as $item) {
					$detailCampaignStartLetter = Coordinate::stringFromColumnIndex($detailCampaignStart);
					$this->excel->setActiveSheetIndex(0)->setCellValue("{$detailCampaignStartLetter}{$loopStart}", $row[$item['id'] . '_item']);
					$this->excel->getActiveSheet()->getStyle("{$detailCampaignStartLetter}{$loopStart}")->applyFromArray([
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
					$detailCampaignStart++;
				}
			}

			foreach ($sales_campaign_details as $sales_campaign_detail) {
				$detailCampaignStartLetter = Coordinate::stringFromColumnIndex($detailCampaignStart);
				$this->excel->setActiveSheetIndex(0)->setCellValue("{$detailCampaignStartLetter}{$loopStart}", $row[$sales_campaign_detail['id'] . '_sisa']);
				$this->excel->getActiveSheet()->getStyle("{$detailCampaignStartLetter}{$loopStart}")->applyFromArray([
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
				$detailCampaignStart++;
			}

			$detailCampaignStartLetter = Coordinate::stringFromColumnIndex($detailCampaignStart);
			$this->excel->setActiveSheetIndex(0)->setCellValue("{$detailCampaignStartLetter}{$loopStart}", $row['total_hadiah']);
			$this->excel->getActiveSheet()->getStyle("{$detailCampaignStartLetter}{$loopStart}")->applyFromArray([
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

			$loopStart++;
		}

		// Settingan awal fil excel
		$this->excel->getProperties()
			->setCreator('SSP')
			->setLastModifiedBy('SSP')
			->setTitle('Report Pencairan Poin Sales Campaign Tidak Langsung');

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
