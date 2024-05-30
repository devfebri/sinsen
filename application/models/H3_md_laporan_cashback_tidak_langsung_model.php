<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class H3_md_laporan_cashback_tidak_langsung_model extends Honda_Model
{

	private $excel;
	private $excelData;

	public function __construct()
	{
		parent::__construct();

		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');

		$this->load->library('Mcarbon');
		$this->load->helper('language');
	}

	public function excel($id)
	{
		$this->excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$sales_campaign = $this->db
			->select('sc.nama')
			->select('
			case
				when sc.start_date_cashback is not null then sc.start_date_cashback
				else sc.start_date
			end as start_date
		', false)
			->select('
			case
				when sc.end_date_cashback is not null then sc.end_date_cashback
				else sc.end_date
			end as end_date
		', false)
			->select('sc.satuan_rekapan_cashback')
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $id)
			->where('sc.jenis_reward_cashback', 1)
			->where('sc.reward_cashback', 'Tidak Langsung')
			->limit(1)
			->get()->row_array();

		$cashbacks = $this->db
			->from('ms_h3_md_sales_campaign_detail_cashback_global as scdg')
			->where('scdg.id_campaign', $id)
			->get()->result_array();

		$this->db
		->select('scd.id_dealer')
		->from('ms_h3_md_sales_campaign_dealers as scd')
		->where('scd.id_campaign', $id);
		
		$dealers = array_map(function($row){
			return $row['id_dealer'];
		}, $this->db->get()->result_array());

		$this->db
			->select('perolehan.*')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_bank_h3')
			->select('d.atas_nama_bank_h3')
			->select('d.no_rekening_h3')
			->from('tr_h3_perolehan_sales_campaign_cashback_tidak_langsung as perolehan')
			->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
			->where('perolehan.total_bayar >', 0)
			->where('perolehan.id_campaign', $id);
		
		if(count($dealers) > 0){
			$this->db->where_in('perolehan.id_dealer', $dealers);
		}

		$perolehan = $this->db->get()->result_array();

		$start_date = Mcarbon::parse($sales_campaign['start_date'])->startOfMonth();
		$end_date = Mcarbon::parse($sales_campaign['end_date'])->startOfMonth();

		$diffInMonths = $start_date->diffInMonths($end_date) + 1;
		if ($diffInMonths == 0) $diffInMonths = 1;

		$this->excelData = [
			[
				'cell' => 'A5',
				'merge_cell' => 'A5:A7',
				'value' => 'NO.',
				'style' => [
					'fill' => [
						'fillType' => Fill::FILL_SOLID,
						'color' => array('rgb' => 'da9694')
					],
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
				]
			],
			[
				'cell' => 'B5',
				'merge_cell' => 'B5:B7',
				'value' => 'DEALER / AHASS / OUTLET / TOKO',
				'style' => [
					'fill' => [
						'fillType' => Fill::FILL_SOLID,
						'color' => array('rgb' => 'da9694')
					],
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
				]
			],
		];

		$index = 1;
		foreach ($perolehan as $row_perolehan) {
			$row = $index + 7;
			$nomor = [
				'cell' => "A{$row}",
				'value' => "{$index}.",
				'style' => [
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
				],
			];
			$this->excelData[] = $nomor;

			$dealer = [
				'cell' => "B{$row}",
				'value' => "{$row_perolehan['nama_dealer']}",
				'width' => 45,
				'style' => [
					'alignment' => [
						'vertical' => Alignment::VERTICAL_CENTER,
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
				],
			];
			$this->excelData[] = $dealer;

			$index++;
		}

		$column_letter_start_index = 3;
		$bulan_start_cell = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$bulan_end_cell = Coordinate::stringFromColumnIndex(
			($column_letter_start_index + $diffInMonths - 1)
		);
		$bulan = [
			'cell' => "{$bulan_start_cell}5",
			'merge_cell' => "{$bulan_start_cell}5:{$bulan_end_cell}5",
			'value' => 'BULAN',
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $bulan;

		for ($indexBulan = 0; $indexBulan < $diffInMonths; $indexBulan++) {
			$localMonthObject = $start_date->copy()->addMonths($indexBulan);

			$column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index + $indexBulan);

			$array = [];
			$array['cell'] = "{$column_letter}6";
			$array['merge_cell'] = "{$column_letter}6:{$column_letter}7";
			$array['value'] = "{$localMonthObject->format('F')} {$localMonthObject->format('Y')}";
			$array['style'] = [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			];

			$this->excelData[] = $array;

			$index = 1;
			$total_perolehan_perbulan_dealer = 0;
			foreach ($perolehan as $row_perolehan) {
				$row = $index + 7;

				$perolehan_perbulan_dealer = $this->db
					->from('tr_h3_perolehan_sales_campaign_cashback_tl_perbulan as perolehan_perbulan')
					->where('perolehan_perbulan.id_perolehan', $row_perolehan['id'])
					->where('perolehan_perbulan.bulan', $localMonthObject->format('m'))
					->where('perolehan_perbulan.tahun', $localMonthObject->format('Y'))
					->limit(1)
					->get()->row_array();

				$perolehan_perbulan = [];
				$perolehan_perbulan['cell'] = "{$column_letter}{$row}";
				$total_perolehan_perbulan_dealer += $perolehan_perbulan['value'] = $perolehan_perbulan_dealer['total_penjualan_per_bulan'];
				$perolehan_perbulan['width'] = 12;
				$perolehan_perbulan['style'] = [
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
				];
				$this->excelData[] = $perolehan_perbulan;

				$index++;
			}

			$total_footer_penjualan_perbulan_index = count($perolehan) + 7 + 1;
			$total_footer_penjualan_perbulan = [];
			$total_footer_penjualan_perbulan['cell'] = "{$column_letter}{$total_footer_penjualan_perbulan_index}";
			$total_footer_penjualan_perbulan['value'] = $total_perolehan_perbulan_dealer;
			$total_footer_penjualan_perbulan['width'] = 12;
			$total_footer_penjualan_perbulan['style'] = [
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
			];

			$this->excelData[] = $total_footer_penjualan_perbulan;

		}

		$column_letter_start_index += $diffInMonths - 1;

		$column_letter_start_index++;
		$total_poin_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$total_poin = [
			'cell' => "{$total_poin_column_letter}5",
			'merge_cell' => "{$total_poin_column_letter}5:{$total_poin_column_letter}7",
			'value' => 'TOTAL POIN',
			'width' => 15,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $total_poin;

		$index = 1;
		$total_poin_all = 0;
		foreach ($perolehan as $row_perolehan) {
			$row_index = $index + 7;
			$total_poin_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
			$total_poin = [
				'cell' => "{$total_poin_column_letter}{$row_index}",
				'value' => $sales_campaign['satuan_rekapan_cashback'] == 'Dus' ? $row_perolehan['total_dus_penjualan_per_dealer'] : $row_perolehan['total_penjualan_per_dealer'],
				'style' => [
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
				],
			];

			$index++;

			$total_poin_all += $sales_campaign['satuan_rekapan_cashback'] == 'Dus' ? $row_perolehan['total_dus_penjualan_per_dealer'] : $row_perolehan['total_penjualan_per_dealer'];

			$this->excelData[] = $total_poin;
		}

		$total_poin_all_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$total_poin_column_letter}{$total_poin_all_row}",
			'value' => $total_poin_all,
			'style' => [
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
			],
		];

		$column_letter_start_index++;
		$poin_dan_hadiah_start_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);

		$countCashback = count($cashbacks);
		$poin_dan_hadiah_end_column_letter = Coordinate::stringFromColumnIndex(($column_letter_start_index + $countCashback - 1));

		$poin_dan_hadiah = [
			'cell' => "{$poin_dan_hadiah_start_column_letter}5",
			'merge_cell' => "{$poin_dan_hadiah_start_column_letter}5:{$poin_dan_hadiah_end_column_letter}5",
			'value' => 'POIN DAN HADIAH',
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $poin_dan_hadiah;

		$column_index = 1;

		foreach ($cashbacks as $cashback) {
			$cashback_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index + $column_index - 1);
			$qty_cashback = [
				'cell' => "{$cashback_column_letter}6",
				'value' => $cashback['qty'],
				'style' => [
					'fill' => [
						'fillType' => Fill::FILL_SOLID,
						'color' => array('rgb' => 'da9694')
					],
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
				]
			];
			$this->excelData[] = $qty_cashback;

			$nilai_cashback = [
				'cell' => "{$cashback_column_letter}7",
				'value' => $cashback['cashback'],
				'style' => [
					'fill' => [
						'fillType' => Fill::FILL_SOLID,
						'color' => array('rgb' => 'da9694')
					],
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
				]
			];
			$this->excelData[] = $nilai_cashback;

			$row_index = 8;
			$total_perolehan_hadiah_cashback = 0;
			foreach ($perolehan as $row_perolehan) {
				$perolehan_hadiah = $this->db
					->select('perolehan_cashback.count_cashback')
					->from('tr_h3_perolehan_sales_campaign_cashback_tl_global as perolehan_cashback')
					->where('perolehan_cashback.id_perolehan', $row_perolehan['id'])
					->where('perolehan_cashback.id_campaign', $id)
					->where('perolehan_cashback.id_global', $cashback['id'])
					->get()->row_array();

				$perolehan_hadiah_data = [
					'cell' => "{$cashback_column_letter}{$row_index}",
					'value' => $perolehan_hadiah['count_cashback'],
					'width' => 12,
					'style' => [
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
					]
				];
				$this->excelData[] = $perolehan_hadiah_data;
				
				$total_perolehan_hadiah_cashback += $perolehan_hadiah['count_cashback'];
				
				$row_index++;
			}

			$total_perolehan_hadiah_cashback_row = count($perolehan) + 7 + 1;
			$this->excelData[] = [
				'cell' => "{$cashback_column_letter}{$total_perolehan_hadiah_cashback_row}",
				'value' => $total_perolehan_hadiah_cashback,
				'width' => 12,
				'style' => [
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
				]
			];
			
			$column_index++;
		}
		$column_letter_start_index += count($cashbacks);

		$sisa_poin_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$sisa_poin = [
			'cell' => "{$sisa_poin_column_letter}5",
			'merge_cell' => "{$sisa_poin_column_letter}5:{$sisa_poin_column_letter}7",
			'value' => 'Sisa Poin',
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $sisa_poin;

		$row_index = 8;
		$total_sisa_poin_all = 0;
		foreach ($perolehan as $row_perolehan) {
			$sisa_poin = [
				'cell' => "{$sisa_poin_column_letter}{$row_index}",
				'value' => $sales_campaign['satuan_rekapan_cashback'] == 'Dus' ? $row_perolehan['sisa_total_dus_penjualan_per_dealer'] : $row_perolehan['sisa_total_penjualan_per_dealer'],
				'width' => 12,
				'style' => [
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
				]
			];

			$this->excelData[] = $sisa_poin;
			$total_sisa_poin_all += $sales_campaign['satuan_rekapan_cashback'] == 'Dus' ? $row_perolehan['sisa_total_dus_penjualan_per_dealer'] : $row_perolehan['sisa_total_penjualan_per_dealer'];
			$row_index++;
		}

		$total_sisa_poin_all_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$sisa_poin_column_letter}{$total_sisa_poin_all_row}",
			'value' => $total_sisa_poin_all,
			'width' => 12,
			'style' => [
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
			]
		];

		$column_letter_start_index++;

		$total_hadiah_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$total_hadiah = [
			'cell' => "{$total_hadiah_column_letter}5",
			'merge_cell' => "{$total_hadiah_column_letter}5:{$total_hadiah_column_letter}7",
			'value' => 'Total Hadiah',
			'wrap' => true,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $total_hadiah;

		$row_index = 8;
		$total_all_hadiah = 0;
		foreach ($perolehan as $row_perolehan) {
			$total_hadiah = [
				'cell' => "{$total_hadiah_column_letter}{$row_index}",
				'value' => $row_perolehan['total_insentif'],
				'style' => [
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
				]
			];

			$this->excelData[] = $total_hadiah;
			$total_all_hadiah += $row_perolehan['total_insentif'];
			$row_index++;
		}

		$total_all_hadiah_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$total_hadiah_column_letter}{$total_all_hadiah_row}",
			'value' => $total_all_hadiah,
			'style' => [
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
			]
		];

		$column_letter_start_index++;

		$ppn_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$ppn = [
			'cell' => "{$ppn_column_letter}5",
			'merge_cell' => "{$ppn_column_letter}5:{$ppn_column_letter}7",
			'value' => 'PPN',
			'width' => 15,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $ppn;

		$row_index = 8;
		$total_all_ppn = 0;
		foreach ($perolehan as $row_perolehan) {
			$ppn = [
				'cell' => "{$ppn_column_letter}{$row_index}",
				'value' => $row_perolehan['ppn'],
				'style' => [
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
				]
			];

			$this->excelData[] = $ppn;
			$total_all_ppn += $row_perolehan['ppn'];
			$row_index++;
		}

		$total_all_ppn_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$ppn_column_letter}{$total_all_ppn_row}",
			'value' => $total_all_ppn,
			'style' => [
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
			]
		];

		$column_letter_start_index++;

		$nilai_kw_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$nilai_kw = [
			'cell' => "{$nilai_kw_column_letter}5",
			'merge_cell' => "{$nilai_kw_column_letter}5:{$nilai_kw_column_letter}7",
			'value' => 'Nilai KW',
			'width' => 15,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $nilai_kw;

		$row_index = 8;
		$total_all_nilai_kw = 0;
		foreach ($perolehan as $row_perolehan) {
			$nilai_kw = [
				'cell' => "{$nilai_kw_column_letter}{$row_index}",
				'value' => $row_perolehan['nilai_kw'],
				'style' => [
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
				]
			];

			$this->excelData[] = $nilai_kw;
			$total_all_nilai_kw += $row_perolehan['nilai_kw'];
			$row_index++;
		}

		$total_all_nilai_kw_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$nilai_kw_column_letter}{$total_all_nilai_kw_row}",
			'value' => $total_all_nilai_kw,
			'style' => [
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
			]
		];

		$column_letter_start_index++;

		$pph_23_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$pph_23 = [
			'cell' => "{$pph_23_column_letter}5",
			'merge_cell' => "{$pph_23_column_letter}5:{$pph_23_column_letter}7",
			'value' => 'PPH 23',
			'width' => 15,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $pph_23;

		$row_index = 8;
		$total_all_pph_23 = 0;
		foreach ($perolehan as $row_perolehan) {
			$pph_23 = [
				'cell' => "{$pph_23_column_letter}{$row_index}",
				'value' => $row_perolehan['pph_23'],
				'style' => [
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
				]
			];

			$this->excelData[] = $pph_23;
			$total_all_pph_23 += $row_perolehan['pph_23'];
			$row_index++;
		}

		$total_all_pph_23_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$pph_23_column_letter}{$total_all_pph_23_row}",
			'value' => $total_all_pph_23,
			'style' => [
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
			]
		];

		$column_letter_start_index++;

		$pph_21_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$pph_21 = [
			'cell' => "{$pph_21_column_letter}5",
			'merge_cell' => "{$pph_21_column_letter}5:{$pph_21_column_letter}7",
			'value' => 'PPH 21',
			'width' => 15,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];

		$this->excelData[] = $pph_21;

		$row_index = 8;
		$total_all_pph21 = 0;
		foreach ($perolehan as $row_perolehan) {
			$pph_21 = [
				'cell' => "{$pph_21_column_letter}{$row_index}",
				'value' => $row_perolehan['pph_21'],
				'style' => [
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
				]
			];

			$this->excelData[] = $pph_21;
			$total_all_pph21 += $row_perolehan['pph_21'];
			$row_index++;
		}

		$total_all_pph21_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$pph_21_column_letter}{$total_all_pph21_row}",
			'value' => $total_all_pph21,
			'style' => [
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
			]
		];

		$column_letter_start_index++;

		$total_bayar_potong_pph_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$total_bayar_potong_pph = [
			'cell' => "{$total_bayar_potong_pph_column_letter}5",
			'merge_cell' => "{$total_bayar_potong_pph_column_letter}5:{$total_bayar_potong_pph_column_letter}7",
			'value' => 'Total Bayar (Potong PPH)',
			'width' => 15,
			'wrap' => true,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $total_bayar_potong_pph;

		$row_index = 8;
		$total_all_bayar_potong_pph = 0;
		foreach ($perolehan as $row_perolehan) {
			$total_bayar_potong_pph = [
				'cell' => "{$total_bayar_potong_pph_column_letter}{$row_index}",
				'value' => $row_perolehan['total_bayar'],
				'style' => [
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
				]
			];

			$this->excelData[] = $total_bayar_potong_pph;
			$total_all_bayar_potong_pph += $row_perolehan['total_bayar'];
			$row_index++;
		}

		$total_all_bayar_potong_pph_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$total_bayar_potong_pph_column_letter}{$total_all_bayar_potong_pph_row}",
			'value' => $total_all_bayar_potong_pph,
			'style' => [
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
			]
		];

		$column_letter_start_index++;

		$nama_bank_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$nama_bank = [
			'cell' => "{$nama_bank_column_letter}5",
			'merge_cell' => "{$nama_bank_column_letter}5:{$nama_bank_column_letter}7",
			'value' => 'Nama Bank',
			'width' => 25,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $nama_bank;

		$row_index = 8;
		foreach ($perolehan as $row_perolehan) {
			$nama_bank = [
				'cell' => "{$nama_bank_column_letter}{$row_index}",
				'value' => $row_perolehan['nama_bank_h3'],
				'style' => [
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
				]
			];

			$this->excelData[] = $nama_bank;
			$row_index++;
		}

		$column_letter_start_index++;

		$atas_nama_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$atas_nama = [
			'cell' => "{$atas_nama_column_letter}5",
			'merge_cell' => "{$atas_nama_column_letter}5:{$atas_nama_column_letter}7",
			'value' => 'Atas Nama',
			'width' => 25,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $atas_nama;

		$row_index = 8;
		foreach ($perolehan as $row_perolehan) {
			$atas_nama = [
				'cell' => "{$atas_nama_column_letter}{$row_index}",
				'value' => $row_perolehan['atas_nama_bank_h3'],
				'style' => [
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
				]
			];

			$this->excelData[] = $atas_nama;
			$row_index++;
		}

		$column_letter_start_index++;

		$no_rekening_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$no_rekening = [
			'cell' => "{$no_rekening_column_letter}5",
			'merge_cell' => "{$no_rekening_column_letter}5:{$no_rekening_column_letter}7",
			'value' => 'No. Rekening',
			'width' => 25,
			'style' => [
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'da9694')
				],
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
			]
		];
		$this->excelData[] = $no_rekening;

		$row_index = 8;
		foreach ($perolehan as $row_perolehan) {
			$no_rekening = [
				'cell' => "{$no_rekening_column_letter}{$row_index}",
				'value' => $row_perolehan['no_rekening_h3'],
				'style' => [
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
				]
			];

			$this->excelData[] = $no_rekening;
			$row_index++;
		}

		$keterangan_bank_row = count($perolehan) + 7 + 1;
		$this->excelData[] = [
			'cell' => "{$nama_bank_column_letter}{$keterangan_bank_row}",
			'merge_cell' => "{$nama_bank_column_letter}{$keterangan_bank_row}:{$no_rekening_column_letter}{$keterangan_bank_row}",
			'value' => null,
			'width' => 25,
			'style' => [
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
			]
		];

		$this->excelData[] = [
			'cell' => "A{$keterangan_bank_row}",
			'merge_cell' => "A{$keterangan_bank_row}:B{$keterangan_bank_row}",
			'value' => 'TOTAL',
			'width' => 25,
			'style' => [
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
			]
		];

		$end_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$this->excelData[] = [
			'cell' => "A1",
			'merge_cell' => "A1:{$end_column_letter}1",
			'value' => 'REKAPAN PENCAIRAN DANA INSENTIVE AHM OIL',
			'style' => [
				'font' => [
					'size' => 12
				],
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]
		];

		$end_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$this->excelData[] = [
			'cell' => "A2",
			'merge_cell' => "A2:{$end_column_letter}2",
			'value' => $sales_campaign['nama'],
			'style' => [
				'font' => [
					'size' => 12
				],
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]
		];

		$end_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$this->excelData[] = [
			'cell' => "A3",
			'merge_cell' => "A3:{$end_column_letter}3",
			'value' => '( DARI MD KE DEALER / AHASS /TOKO )',
			'style' => [
				'font' => [
					'size' => 12
				],
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]
		];

		$tanggal_row = 8 + count($perolehan) + 2;
		$this->excelData[] = [
			'cell' => "B{$tanggal_row}",
			'value' => sprintf('Jambi, %s %s %s', Mcarbon::now()->format('d'), lang('month_' . Mcarbon::now()->format('n')), Mcarbon::now()->format('Y')),
			'style' => [
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
				],
			]
		];

		$label_ttd_row = $tanggal_row + 2;
		$this->excelData[] = [
			'cell' => "B{$label_ttd_row}",
			'value' => 'Dibuat oleh,',
			'style' => [
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
				],
			]
		];

		$this->excelData[] = [
			'cell' => sprintf('B%s', ($label_ttd_row + 4)),
			'value' => 'Admin data',
			'style' => [
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
				],
			]
		];

		$disetujui_oleh_column = Coordinate::stringFromColumnIndex($column_letter_start_index - 2);
		$label_ttd_row = $tanggal_row + 2;
		$this->excelData[] = [
			'cell' => "{$disetujui_oleh_column}{$label_ttd_row}",
			'value' => 'Disetujui Oleh,',
			'style' => [
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]
		];

		$this->excelData[] = [
			'cell' => sprintf('%s%s', $disetujui_oleh_column, ($label_ttd_row + 4)),
			'value' => 'Pimpinan',
			'style' => [
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]
		];

		$diketahui_column_row = Coordinate::stringFromColumnIndex($column_letter_start_index - 10);
		$diketahui_column_row_start = Coordinate::stringFromColumnIndex($column_letter_start_index - 11);
		$diketahui_column_row_end = Coordinate::stringFromColumnIndex($column_letter_start_index - 9);
		$label_ttd_row = $tanggal_row + 2;
		$this->excelData[] = [
			'cell' => "{$diketahui_column_row}{$label_ttd_row}",
			'value' => 'Diketahui Oleh,',
			'style' => [
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
				],
			]
		];

		$this->excelData[] = [
			'cell' => sprintf('%s%s', $diketahui_column_row_start, ($label_ttd_row + 4)),
			'merge_cell' => sprintf('%s%s:%s%s', $diketahui_column_row_start, ($label_ttd_row + 4), $diketahui_column_row_end, ($label_ttd_row + 4)),
			'value' => 'Manager Sparepart & AHM Oil',
			'style' => [
				'alignment' => [
					'vertical' => Alignment::VERTICAL_CENTER,
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
			]
		];

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

	public function createExcel()
	{
		foreach ($this->excelData as $row) {
			$this->excel->setActiveSheetIndex(0)->setCellValue($row['cell'], $row['value']);
			if (isset($row['merge_cell'])) {
				$this->excel->getActiveSheet()->mergeCells($row['merge_cell']);
			}


			if (isset($row['width'])) {
				$this->excel->getActiveSheet()->getColumnDimension($row['cell'][0])->setWidth($row['width']);
			}

			if (isset($row['style'])) {
				if (isset($row['merge_cell'])) {
					$this->excel->getActiveSheet()->getStyle($row['merge_cell'])->applyFromArray($row['style']);
				} else {
					$this->excel->getActiveSheet()->getStyle($row['cell'])->applyFromArray($row['style']);
				}
			}

			if (isset($row['wrap'])) {
				$this->excel->getActiveSheet()->getStyle($row['cell'])->getAlignment()->setWrapText($row['wrap']);
			}
		}
	}

	public function downloadExcel()
	{
		$this->createExcel();

		$sales_campaign = $this->db
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.id', $id)
		->get()->row_array();

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
		ob_end_clean();
		$filename = sprintf('%s_LAPORAN INSENTIF CASHBACK SALES CAMPAIGN %s', Mcarbon::now()->timestamp, $sales_campaign['nama']);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}
