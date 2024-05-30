<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class H3_md_laporan_poin_sales_campaign_model extends Honda_Model
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

	private function validation($id){
		$sales_campaign = $this->db
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.id', $id)
		->get()->row_array();

		if($sales_campaign == null){
			throw new Exception('Sales campaign tidak ditemukan');
		}

		if($sales_campaign['jenis_reward_poin'] != 1 AND $sales_campaign['reward_poin'] != 'Tidak Langsung'){
			throw new Exception(sprintf('Sales campaign %s - %s tidak berjenis poin dan tidak bertipe tidak langsung [%s]', $sales_campaign['kode_campaign'], $sales_campaign['nama'], $sales_campaign['id']));
		}
	}

	public function laporan($id){

		$this->validation($id);
		
		$this->excel($id);
	}

	private function excel($id){
		$this->excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$sales_campaign = $this->db
			->select('sc.nama')
			->select('
			case
				when sc.start_date_poin is not null then sc.start_date_poin
				else sc.start_date
			end as start_date
		', false)
			->select('
			case
				when sc.end_date_poin is not null then sc.end_date_poin
				else sc.end_date
			end as end_date
		', false)
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $id)
			->limit(1)
			->get()->row_array();

		$hadiah = $this->db
			->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
			->where('scdh.id_campaign', $id)
			->get()->result_array();

		$perolehan = $this->db
			->select('perolehan.*')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_bank_h3')
			->select('d.atas_nama_bank_h3')
			->select('d.no_rekening_h3')
			->select('
				case
					when d.nama_bank_h3 IS NULL AND d.atas_nama_bank_h3 IS NULL AND d.no_rekening_h3 IS NULL then true
					else false
				end cair_cash
			', false)
			->from('tr_h3_md_perolehan_sales_campaign_poin_tidak_langsung as perolehan')
			->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
			->where('perolehan.id_campaign', $id)
			->where('perolehan.total_bayar > ', 0)
			->get()->result_array();

		$start_date = Mcarbon::parse($sales_campaign['start_date']);
		$end_date = Mcarbon::parse($sales_campaign['end_date']);

		$diffInMonths = $start_date->diffInMonths($end_date);
		if ($diffInMonths == 0) $diffInMonths = 1;

		$row_total = count($perolehan) + 8 + 1;

		$this->excelData = [
			[
				'cell' => 'A6',
				'merge_cell' => 'A6:A8',
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
				'cell' => 'B6',
				'merge_cell' => 'B6:B8',
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
			$row = $index + 8;
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

		$this->excelData[] = [
			'cell' => "A{$row_total}",
			'merge_cell' => "A{$row_total}:B{$row_total}",
			'value' => "TOTAL",
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

		$column_letter_start_index = 2;

		$column_letter_start_index++;
		$bulan_start_cell = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$bulan_end_cell = Coordinate::stringFromColumnIndex(
			($column_letter_start_index + $diffInMonths - 1)
		);
		$bulan = [
			'cell' => "{$bulan_start_cell}6",
			'merge_cell' => "{$bulan_start_cell}6:{$bulan_end_cell}6",
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
			$array['cell'] = "{$column_letter}7";
			$array['merge_cell'] = "{$column_letter}7:{$column_letter}8";
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
			$total_perolehan_perbulan = 0;
			foreach ($perolehan as $row_perolehan) {
				$row = $index + 8;

				$perolehan_perbulan_dealer = $this->db
					->from('tr_h3_md_perolehan_sales_campaign_poin_tl_perbulan as perolehan_perbulan')
					->where('perolehan_perbulan.id_perolehan', $row_perolehan['id'])
					->where('perolehan_perbulan.bulan', $localMonthObject->format('m'))
					->where('perolehan_perbulan.tahun', $localMonthObject->format('Y'))
					->limit(1)
					->get()->row_array();

				$perolehan_perbulan = [];
				$perolehan_perbulan['cell'] = "{$column_letter}{$row}";
				$perolehan_perbulan['value'] = $perolehan_perbulan_dealer['total_penjualan_per_bulan'];
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
				$total_perolehan_perbulan += $perolehan_perbulan_dealer['total_penjualan_per_bulan'];
				$index++;
			}

			$this->excelData[] = [
				'cell' => "{$column_letter}{$row_total}",
				'value' => $total_perolehan_perbulan,
				'width' => 15,
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
		}

		$column_letter_start_index += $diffInMonths - 1;

		$column_letter_start_index++;
		$total_poin_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$total_poin = [
			'cell' => "{$total_poin_column_letter}6",
			'merge_cell' => "{$total_poin_column_letter}6:{$total_poin_column_letter}8",
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
		$total_all_poin = 0;
		foreach ($perolehan as $row_perolehan) {
			$row_index = $index + 8;
			$total_poin_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
			$total_poin = [
				'cell' => "{$total_poin_column_letter}{$row_index}",
				'value' => $row_perolehan['total_poin_penjualan_per_dealer'],
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
			$total_all_poin += $row_perolehan['total_poin_penjualan_per_dealer'];
			$this->excelData[] = $total_poin;
		}

		$this->excelData[] = [
			'cell' => "{$total_poin_column_letter}{$row_total}",
			'value' => $total_all_poin,
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
	
		$column_index = 1;
		foreach ($hadiah as $row_hadiah) {
			$label_hadiah_letter = Coordinate::stringFromColumnIndex($column_letter_start_index + $column_index - 1);
			$label_hadiah = [
				'cell' => "{$label_hadiah_letter}6",
				'value' => $row_hadiah['nama_paket'],
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
			$this->excelData[] = $label_hadiah;
			
			$hadiah_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index + $column_index - 1);
			$poin_hadiah = [
				'cell' => "{$hadiah_column_letter}7",
				'value' => $row_hadiah['jumlah_poin'] . " poin",
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
			$this->excelData[] = $poin_hadiah;

			$voucher_hadiah = [
				'cell' => "{$hadiah_column_letter}8",
				'value' => $row_hadiah['nama_hadiah'],
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
			$this->excelData[] = $voucher_hadiah;

			$row_index = 9;
			$total_all_hadiah = 0;
			foreach ($perolehan as $row_perolehan) {
				$perolehan_hadiah = $this->db
					->select('perolehan_hadiah.count_hadiah')
					->from('tr_h3_md_perolehan_sales_campaign_poin_tl_hadiah as perolehan_hadiah')
					->where('perolehan_hadiah.id_perolehan', $row_perolehan['id'])
					->where('perolehan_hadiah.id_campaign', $this->input->get('id'))
					->where('perolehan_hadiah.id_hadiah', $row_hadiah['id'])
					->get()->row_array();

				$perolehan_hadiah_data = [
					'cell' => "{$hadiah_column_letter}{$row_index}",
					'value' => $perolehan_hadiah['count_hadiah'],
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
				$total_all_hadiah += $perolehan_hadiah['count_hadiah'];
				$row_index++;
			}

			$this->excelData[] = [
				'cell' => "{$hadiah_column_letter}{$row_total}",
				'value' => $total_all_hadiah,
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
		$column_letter_start_index += count($hadiah);

		$sisa_poin_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$sisa_poin = [
			'cell' => "{$sisa_poin_column_letter}6",
			'merge_cell' => "{$sisa_poin_column_letter}6:{$sisa_poin_column_letter}8",
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

		$row_index = 9;
		$total_all_sisa_poin = 0;
		foreach ($perolehan as $row_perolehan) {
			$sisa_poin = [
				'cell' => "{$sisa_poin_column_letter}{$row_index}",
				'value' => $row_perolehan['sisa_poin'],
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
			$total_all_sisa_poin += $row_perolehan['sisa_poin'];
			$row_index++;
		}

		$this->excelData[] = [
			'cell' => "{$sisa_poin_column_letter}{$row_total}",
			'value' => $total_all_sisa_poin,
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
			'cell' => "{$total_hadiah_column_letter}6",
			'merge_cell' => "{$total_hadiah_column_letter}6:{$total_hadiah_column_letter}8",
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

		$row_index = 9;
		$total_all_insentif = 0;
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
			$total_all_insentif += $row_perolehan['total_insentif'];
			$row_index++;
		}

		$this->excelData[] = [
			'cell' => "{$total_hadiah_column_letter}{$row_total}",
			'value' => $total_all_insentif,
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
			'cell' => "{$ppn_column_letter}6",
			'merge_cell' => "{$ppn_column_letter}6:{$ppn_column_letter}8",
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

		$row_index = 9;
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
			$total_all_poin += $row_perolehan['ppn'];
			$row_index++;
		}

		$this->excelData[] = [
			'cell' => "{$ppn_column_letter}{$row_total}",
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
			'cell' => "{$nilai_kw_column_letter}6",
			'merge_cell' => "{$nilai_kw_column_letter}6:{$nilai_kw_column_letter}8",
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

		$row_index = 9;
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

		$this->excelData[] = [
			'cell' => "{$nilai_kw_column_letter}{$row_total}",
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
			'cell' => "{$pph_23_column_letter}6",
			'merge_cell' => "{$pph_23_column_letter}6:{$pph_23_column_letter}8",
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

		$row_index = 9;
		$total_all_pph23 = 0;
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
			$total_all_pph23 += $row_perolehan['pph_23'];
			$row_index++;
		}

		$this->excelData[] = [
			'cell' => "{$pph_23_column_letter}{$row_total}",
			'value' => $total_all_pph23,
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
			'cell' => "{$pph_21_column_letter}6",
			'merge_cell' => "{$pph_21_column_letter}6:{$pph_21_column_letter}8",
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

		$row_index = 9;
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

		$this->excelData[] = [
			'cell' => "{$pph_21_column_letter}{$row_total}",
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
			'cell' => "{$total_bayar_potong_pph_column_letter}6",
			'merge_cell' => "{$total_bayar_potong_pph_column_letter}6:{$total_bayar_potong_pph_column_letter}8",
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

		$row_index = 9;
		$total_bayar_all = 0;
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
			$total_bayar_all += $row_perolehan['total_bayar'];
			$row_index++;
		}

		$this->excelData[] = [
			'cell' => "{$total_bayar_potong_pph_column_letter}{$row_total}",
			'value' => $total_bayar_all,
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
			'cell' => "{$nama_bank_column_letter}6",
			'merge_cell' => "{$nama_bank_column_letter}6:{$nama_bank_column_letter}8",
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

		$row_index = 9;
		foreach ($perolehan as $row_perolehan) {
			if($row_perolehan['cair_cash'] == 0){
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
			}
			
			$row_index++;
		}

		$column_letter_start_index++;

		$atas_nama_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$atas_nama = [
			'cell' => "{$atas_nama_column_letter}6",
			'merge_cell' => "{$atas_nama_column_letter}6:{$atas_nama_column_letter}8",
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

		$row_index = 9;
		foreach ($perolehan as $row_perolehan) {
			if($row_perolehan['cair_cash'] == 0){
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
			}
			
			$row_index++;
		}

		$column_letter_start_index++;

		$no_rekening_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$no_rekening = [
			'cell' => "{$no_rekening_column_letter}6",
			'merge_cell' => "{$no_rekening_column_letter}6:{$no_rekening_column_letter}8",
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

		$row_index = 9;
		foreach ($perolehan as $row_perolehan) {
			
			if($row_perolehan['cair_cash'] == 0){
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
			}
			
			$row_index++;
		}

		$row_index = 9;
		foreach ($perolehan as $row_perolehan) {
			if($row_perolehan['cair_cash'] == 1){
				$atas_nama = [
					'cell' => "{$nama_bank_column_letter}{$row_index}",
					'merge_cell' => "{$nama_bank_column_letter}{$row_index}:{$no_rekening_column_letter}{$row_index}",
					'value' => 'Dicairkan secara Cash (Tidak ada no. rekening)',
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
			}
			
			$row_index++;
		}

		$this->excelData[] = [
			'cell' => "{$nama_bank_column_letter}{$row_total}",
			'merge_cell' => "{$nama_bank_column_letter}{$row_total}:{$no_rekening_column_letter}{$row_total}",
			'value' => null,
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
			'value' => 'REKAPAN PENCAIRAN VOUCHER PROGRAM CAMPAIGN',
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

		$start_date = Mcarbon::parse($sales_campaign['start_date']);
		$end_date = Mcarbon::parse($sales_campaign['end_date']);
		
		$periode = null;
		if($start_date->diffInMonths($end_date) == 0){
			$periode = sprintf('Periode %s %s', lang('month_' . $start_date->format('n')), $start_date->format('Y'));
		}else{
			$periode = sprintf('Periode %s %s - %s %s', lang('month_' . $start_date->format('n')), $start_date->format('Y'), lang('month_' . $end_date->format('n')), $end_date->format('Y'));
		}

		$end_column_letter = Coordinate::stringFromColumnIndex($column_letter_start_index);
		$this->excelData[] = [
			'cell' => "A3",
			'merge_cell' => "A3:{$end_column_letter}3",
			'value' => $periode,
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
			'cell' => "A4",
			'merge_cell' => "A4:{$end_column_letter}4",
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

		$tanggal_row = $row_total + 2;
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

	public function downloadExcel($id)
	{
		$this->createExcel();
		
		$sales_campaign = $this->db
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.id', $id)
		->get()->row_array();

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
		ob_end_clean();
		$filename = sprintf('%s_LAPORAN INSENTIF POIN SALES CAMPAIGN %s', Mcarbon::now()->timestamp, $sales_campaign['nama']);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}
