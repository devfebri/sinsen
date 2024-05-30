<?php


class H3_md_report_pencairan_poin_model extends Honda_Model {

    public $excel;

    public function __construct(){
        parent::__construct();
        
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $this->excel = new PHPExcel();
        // Settingan awal fil excel
        $this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle('Report Pencairan Poin Sales Campaign');
    }

    public function generate($id_sales_campaign){
        $this->dealer_non_group_sinsen($id_sales_campaign);

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="Report Pencairan Poin.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');

        $write->save('php://output');

        ob_end_clean();
    }

    private function dealer_non_group_sinsen($id_sales_campaign){
        $header_style = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
        ];

        $style_column = array(
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ],
                'right' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ],
                'bottom' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ],
                'left' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        );

        $style_row = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ],
                'right' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ], 
                'bottom' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ],
                'left' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $style_row_horizontal = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ],
                'right' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ], 
                'bottom' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ],
                'left' => [
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $periode_sales = $this->db
        ->select('sc.nama')
        ->select('
            case
                when sc.start_date_poin is not null then sc.start_date_poin
                else sc.start_date
            end as periode_awal
        ', false)
        ->select('
            case
                when sc.end_date_poin is not null then sc.end_date_poin
                else sc.end_date
            end as periode_akhir
        ', false)
        ->select('
            case
                when sc.start_date is not null and sc.end_date is not null then timestampdiff(MONTH, sc.start_date, sc.end_date)
                else timestampdiff(MONTH, sc.start_date_poin, sc.end_date_poin)
            end as date_diff
        ', false)
        ->from('ms_h3_md_sales_campaign as sc')
        ->where('sc.id', $id_sales_campaign)
        ->where('sc.jenis_reward_poin', 1)
        ->get()->row_array();

        $this->excel->setActiveSheetIndex(0)->setCellValue('B2', 'REKAPAN PENCAIRAN VOUCHER PROGRAM CAMPAIGN');
        $this->excel->getActiveSheet()->getStyle("B2")->applyFromArray($header_style);
        $this->excel->setActiveSheetIndex(0)->setCellValue('B3', '" ' . $periode_sales['nama'] . ' "');
        $this->excel->getActiveSheet()->getStyle("B3")->applyFromArray($header_style);

        $periode_awal_untuk_kop = new DateTime($periode_sales['periode_awal']);
        $periode_akhir_untuk_kop = new DateTime($periode_sales['periode_akhir']);
        $this->excel->setActiveSheetIndex(0)->setCellValue('B4', "PERIODE {$periode_awal_untuk_kop->format('F')} - {$periode_akhir_untuk_kop->format('F Y')}");
        $this->excel->getActiveSheet()->getStyle("B4")->applyFromArray($header_style);
        $this->excel->setActiveSheetIndex(0)->setCellValue('B5', '( DARI MD KE DEALER / AHASS / TOKO )');
        $this->excel->getActiveSheet()->getStyle("B5")->applyFromArray($header_style);
    
        $this->excel->setActiveSheetIndex(0)->setCellValue('B7', 'NO');
        $this->excel->getActiveSheet()->getStyle("B7:B8")->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells("B7:B8");
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);

        $this->excel->setActiveSheetIndex(0)->setCellValue('C7', 'DEALER / AHASS / OUTLET / TOKO');
        $this->excel->getActiveSheet()->getStyle("C7:C8")->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells("C7:C8");
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(31);

        $dealer_yang_ada_poin = $this->db
        ->select('DISTINCT(ppsc.id_dealer)')
        ->from('tr_h3_md_pencatatan_poin_sales_campaign as ppsc')
        ->get_compiled_select();
        
        $dealers = $this->db
        ->select('d.id_dealer')
        ->select('d.nama_dealer')
        ->from('ms_dealer as d')
        // ->where("d.id_dealer in ({$dealer_yang_ada_poin})", null, false)
        ->order_by('d.nama_dealer', 'asc')
        ->get()->result_array();

        $nomor_urut = 1;
        $row = 9;

        $this->excel->setActiveSheetIndex(0)->setCellValue('D7', 'TOTAL PEMBELIAN PER BULAN');
        $this->excel->getActiveSheet()->getStyle("D7:{$this->get_letter($periode_sales['date_diff'] + 3)}7")->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells("D7:{$this->get_letter($periode_sales['date_diff'] + 3)}7");

        $increment_month = 0;
        $kolom_terpakai_untuk_periode_pembelian = 0;
        for ($i=0; $i <= $periode_sales['date_diff']; $i++) { 
            $letter_number = $this->get_letter($i + 3);

            $periode_awal = new DateTime($periode_sales['periode_awal']);
            $periode_awal->add(new DateInterval("P{$increment_month}M"));

            $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number . ($row - 1), $periode_awal->format('M'));
            $this->excel->getActiveSheet()->getStyle($letter_number . ($row - 1))->applyFromArray($style_column);
            $this->excel->getActiveSheet()->getColumnDimension($letter_number)->setWidth(12);

            $increment_month++;
            $kolom_terpakai_untuk_periode_pembelian++;
        }

        $this->excel->setActiveSheetIndex(0)->setCellValue("{$this->get_letter($periode_sales['date_diff'] + 4)}7", 'TOTAL');
        $this->excel->getActiveSheet()->getStyle("{$this->get_letter($periode_sales['date_diff'] + 4)}7:{$this->get_letter($periode_sales['date_diff'] + 4)}8")->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells("{$this->get_letter($periode_sales['date_diff'] + 4)}7:{$this->get_letter($periode_sales['date_diff'] + 4)}8");
        $this->excel->getActiveSheet()->getColumnDimension($this->get_letter($periode_sales['date_diff'] + 4))->setWidth(14);

        $rewards = $this->db
        ->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
        ->where('scdh.id_campaign', $id_sales_campaign)
        ->where('voucher_rupiah', 1)
        ->order_by('scdh.jumlah_poin', 'asc')
        ->get()->result_array();

        $column_number = $periode_sales['date_diff'] + 5;
        $kolom_terpakai_untuk_rewards = 0;
        foreach ($rewards as $reward) {
            $letter_number = $this->get_letter($column_number);
            $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number . ($row - 2), $reward['nama_paket']);
            $this->excel->getActiveSheet()->getStyle($letter_number . ($row - 2))->applyFromArray($style_column);
            $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number . ($row - 1), $reward['jumlah_poin']);
            $this->excel->getActiveSheet()->getStyle($letter_number . ($row - 1))->applyFromArray($style_column);
            $this->excel->getActiveSheet()->getColumnDimension($letter_number)->setWidth(12);


            $column_number++;
            $kolom_terpakai_untuk_rewards++;
        }

        $letter_number_sisa_poin = $this->get_letter($periode_sales['date_diff'] + 5 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_sisa_poin . ($row - 2), 'SISA POIN');
        $this->excel->getActiveSheet()->getStyle($letter_number_sisa_poin . ($row - 2) . ":" . $letter_number_sisa_poin . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_sisa_poin . ($row - 2) . ":" . $letter_number_sisa_poin . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_sisa_poin)->setWidth(12);

        $letter_number_total_hadiah = $this->get_letter($periode_sales['date_diff'] + 6 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_total_hadiah . ($row - 2), 'TOTAL HADIAH');
        $this->excel->getActiveSheet()->getStyle($letter_number_total_hadiah . ($row - 2) . ":" . $letter_number_total_hadiah . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_total_hadiah . ($row - 2) . ":" . $letter_number_total_hadiah . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_total_hadiah)->setWidth(12);

        $letter_number_ppn = $this->get_letter($periode_sales['date_diff'] + 7 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_ppn . ($row - 2), 'PPN');
        $this->excel->getActiveSheet()->getStyle($letter_number_ppn . ($row - 2) . ":" . $letter_number_ppn . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_ppn . ($row - 2) . ":" . $letter_number_ppn . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_ppn)->setWidth(12);

        $letter_number_pph23 = $this->get_letter($periode_sales['date_diff'] + 8 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_pph23 . ($row - 2), 'PPH 23');
        $this->excel->getActiveSheet()->getStyle($letter_number_pph23 . ($row - 2) . ":" . $letter_number_pph23 . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_pph23 . ($row - 2) . ":" . $letter_number_pph23 . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_pph23)->setWidth(12);

        $letter_number_pph21 = $this->get_letter($periode_sales['date_diff'] + 9 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_pph21 . ($row - 2), 'PPH 21');
        $this->excel->getActiveSheet()->getStyle($letter_number_pph21 . ($row - 2) . ":" . $letter_number_pph21 . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_pph21 . ($row - 2) . ":" . $letter_number_pph21 . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_pph21)->setWidth(12);

        $letter_number_total_bayar = $this->get_letter($periode_sales['date_diff'] + 10 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_total_bayar . ($row - 2), 'TOTAL BAYAR');
        $this->excel->getActiveSheet()->getStyle($letter_number_total_bayar . ($row - 2) . ":" . $letter_number_total_bayar . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_total_bayar . ($row - 2) . ":" . $letter_number_total_bayar . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_total_bayar)->setWidth(12);

        $letter_number_nama_bank = $this->get_letter($periode_sales['date_diff'] + 11 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_nama_bank . ($row - 2), 'NAMA BANK');
        $this->excel->getActiveSheet()->getStyle($letter_number_nama_bank . ($row - 2) . ":" . $letter_number_nama_bank . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_nama_bank . ($row - 2) . ":" . $letter_number_nama_bank . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_nama_bank)->setWidth(12);

        $letter_number_atas_nama = $this->get_letter($periode_sales['date_diff'] + 12 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_atas_nama . ($row - 2), 'ATAS NAMA');
        $this->excel->getActiveSheet()->getStyle($letter_number_atas_nama . ($row - 2) . ":" . $letter_number_atas_nama . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_atas_nama . ($row - 2) . ":" . $letter_number_atas_nama . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_atas_nama)->setWidth(12);

        $letter_number_no_rekening = $this->get_letter($periode_sales['date_diff'] + 13 + count($rewards));
        $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number_no_rekening . ($row - 2), 'NO REKENING');
        $this->excel->getActiveSheet()->getStyle($letter_number_no_rekening . ($row - 2) . ":" . $letter_number_no_rekening . ($row - 1))->applyFromArray($style_column);
        $this->excel->getActiveSheet()->mergeCells($letter_number_no_rekening . ($row - 2) . ":" . $letter_number_no_rekening . ($row - 1));
        $this->excel->getActiveSheet()->getColumnDimension($letter_number_no_rekening)->setWidth(13);

        foreach ($dealers as $dealer) {
            $this->excel->setActiveSheetIndex(0)->setCellValue('B' . $row, $nomor_urut);
            $this->excel->getActiveSheet()->getStyle('B' . $row)->applyFromArray($style_row_horizontal);
            $this->excel->setActiveSheetIndex(0)->setCellValue('C' . $row, $dealer['nama_dealer']);
            $this->excel->getActiveSheet()->getStyle('C' . $row)->applyFromArray($style_row);

            $increment_month = 0;
            $total_poin_per_dealer = 0;
            for ($i=0; $i <= $periode_sales['date_diff']; $i++) {
                $letter_number = $this->get_letter($i + 3);

                $periode_awal = new DateTime($periode_sales['periode_awal']);
                $periode_awal->add(new DateInterval("P{$increment_month}M"));
                $periode_akhir = new DateTime($periode_awal->format('Y-m-01'));
                
                $poin_yang_didapatkan = $this->db
                ->select('IFNULL(sum(ppsc.poin), 0) as poin')
                ->from('tr_h3_md_pencatatan_poin_sales_campaign as ppsc')
                ->where('ppsc.id_campaign', $id_sales_campaign)
                ->where('ppsc.id_dealer', $dealer['id_dealer'])
                ->where("ppsc.created_at between '{$periode_awal->format('Y-m-01')}' and '{$periode_akhir->format('Y-m-t')}'")
                ->get()->row_array()
                ;

                $poin_yang_didapatkan = $poin_yang_didapatkan != null ? $poin_yang_didapatkan['poin'] : 0;
                $this->excel->setActiveSheetIndex(0)->setCellValue($letter_number . $row, $poin_yang_didapatkan);
                $this->excel->getActiveSheet()->getStyle($letter_number . $row)->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle($letter_number . $row)->applyFromArray($style_row_horizontal);

                $total_poin_per_dealer += $poin_yang_didapatkan;
                $increment_month++;
            }

            $this->excel->setActiveSheetIndex(0)->setCellValue("{$this->get_letter($periode_sales['date_diff'] + 4)}{$row}", $total_poin_per_dealer);
            $this->excel->getActiveSheet()->getStyle("{$this->get_letter($periode_sales['date_diff'] + 4)}{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle("{$this->get_letter($periode_sales['date_diff'] + 4)}{$row}")->applyFromArray($style_row_horizontal);

            $increment_column = count($rewards);
            $total_hadiah = 0;
            foreach (array_reverse($rewards) as $reward) {
                $letter_number = $this->get_letter($periode_sales['date_diff'] + 4 + $increment_column);

                $count_reward = 0;
                while($total_poin_per_dealer >= $reward['jumlah_poin']){
                    $count_reward++;
                    $total_poin_per_dealer -= $reward['jumlah_poin'];
                }

                $total_hadiah += $count_reward * $reward['nama_hadiah'];
                $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number}{$row}", $count_reward);
                $this->excel->getActiveSheet()->getStyle("{$letter_number}{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle("{$letter_number}{$row}")->applyFromArray($style_row_horizontal);

                $increment_column--;
            }

            $letter_number_sisa_poin = $this->get_letter($periode_sales['date_diff'] + 5 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_sisa_poin}{$row}", $total_poin_per_dealer);
            $this->excel->getActiveSheet()->getStyle("{$letter_number_sisa_poin}{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_sisa_poin}{$row}")->applyFromArray($style_row_horizontal);

            $letter_number_total_hadiah = $this->get_letter($periode_sales['date_diff'] + 6 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_total_hadiah}{$row}", $total_hadiah);
            $this->excel->getActiveSheet()->getStyle("{$letter_number_total_hadiah}{$row}")->getNumberFormat()->setFormatCode('Rp #,##0');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_total_hadiah}{$row}")->applyFromArray($style_row);

            $letter_number_ppn = $this->get_letter($periode_sales['date_diff'] + 7 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_ppn}{$row}", 0);
            $this->excel->getActiveSheet()->getStyle("{$letter_number_ppn}{$row}")->getNumberFormat()->setFormatCode('Rp #,##0');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_ppn}{$row}")->applyFromArray($style_row);

            $letter_number_pph23 = $this->get_letter($periode_sales['date_diff'] + 8 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_pph23}{$row}", 0);
            $this->excel->getActiveSheet()->getStyle("{$letter_number_pph23}{$row}")->getNumberFormat()->setFormatCode('Rp #,##0');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_pph23}{$row}")->applyFromArray($style_row);

            $letter_number_pph21 = $this->get_letter($periode_sales['date_diff'] + 9 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_pph21}{$row}", 0);
            $this->excel->getActiveSheet()->getStyle("{$letter_number_pph21}{$row}")->getNumberFormat()->setFormatCode('Rp #,##0');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_pph21}{$row}")->applyFromArray($style_row);

            $letter_number_total_bayar = $this->get_letter($periode_sales['date_diff'] + 10 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_total_bayar}{$row}", 0);
            $this->excel->getActiveSheet()->getStyle("{$letter_number_total_bayar}{$row}")->getNumberFormat()->setFormatCode('Rp #,##0');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_total_bayar}{$row}")->applyFromArray($style_row);

            $letter_number_nama_bank = $this->get_letter($periode_sales['date_diff'] + 11 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_nama_bank}{$row}", '-');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_nama_bank}{$row}")->applyFromArray($style_row);

            $letter_number_atas_nama = $this->get_letter($periode_sales['date_diff'] + 12 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_atas_nama}{$row}", '-');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_atas_nama}{$row}")->applyFromArray($style_row);

            $letter_number_no_rekening = $this->get_letter($periode_sales['date_diff'] + 13 + count($rewards));
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$letter_number_no_rekening}{$row}", '-');
            $this->excel->getActiveSheet()->getStyle("{$letter_number_no_rekening}{$row}")->applyFromArray($style_row);

            $row++;
            $nomor_urut++;
        }

        $this->excel->setActiveSheetIndex(0)->setCellValue("B{$row}", 'TOTAL');
        $this->excel->getActiveSheet()->getStyle("B{$row}:C{$row}")->applyFromArray($style_row_horizontal);
        $this->excel->getActiveSheet()->mergeCells("B{$row}:C{$row}");

        $row_terakhir = 8 + count($dealers);
        $kolom_terakhir = $kolom_terpakai_untuk_periode_pembelian + 1 + $kolom_terpakai_untuk_rewards + 8;
        for ($i=3; $i <=$kolom_terakhir ; $i++) { 
            $column_letter = $this->get_letter($i);
            $this->excel->setActiveSheetIndex(0)->setCellValue("{$column_letter}{$row}", "=SUM({$column_letter}9:{$column_letter}{$row_terakhir})");
            if($i >= 11){
                $this->excel->getActiveSheet()->getStyle("{$column_letter}{$row}")->getNumberFormat()->setFormatCode('Rp #,##0');
                $this->excel->getActiveSheet()->getStyle("{$column_letter}{$row}")->applyFromArray($style_row);
            }else{
                $this->excel->getActiveSheet()->getStyle("{$column_letter}{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle("{$column_letter}{$row}")->applyFromArray($style_row_horizontal);
            }
        }

        $start_column_letter = $this->get_letter($kolom_terakhir + 1);
        $end_column_letter = $this->get_letter($kolom_terakhir + 3);
        $this->excel->getActiveSheet()->getStyle("{$start_column_letter}{$row}:{$end_column_letter}{$row}")->applyFromArray($style_row);
        $this->excel->getActiveSheet()->mergeCells("{$start_column_letter}{$row}:{$end_column_letter}{$row}");

        $end_kop_merge = $this->get_letter($periode_sales['date_diff'] + 12 + count($rewards));
        $this->excel->getActiveSheet()->mergeCells("B2:{$end_kop_merge}2");
        $this->excel->getActiveSheet()->mergeCells("B3:{$end_kop_merge}3");
        $this->excel->getActiveSheet()->mergeCells("B4:{$end_kop_merge}4");
        $this->excel->getActiveSheet()->mergeCells("B5:{$end_kop_merge}5");    
    }
    
    private function get_letter($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return get_letter($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

}
