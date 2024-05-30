<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class H3_md_report_monitor_plafon_model extends Honda_Model {

    public $excel;

    public function __construct(){
        parent::__construct();
        
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $this->excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Settingan awal fil excel
        $this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle('Report Monitor Plafon');
    }

    public function generate($data){
        $this->make_excel($data);

        $write = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
        ob_end_clean();
        
		$filename = 'Report Plafon';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $write->save('php://output');
    }

    private function make_excel($data){
        $header_style = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ];

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(27);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

        $this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'Laporan Plafon & Piutang');
        $this->excel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($header_style);
        $this->excel->getActiveSheet()->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'size' => 16,
            ],
        ]);
        $this->excel->getActiveSheet()->mergeCells('A1:J1');

        $this->excel->setActiveSheetIndex(0)->setCellValue('A2', sprintf('Customer : %s - %s', $data['dealer']['kode_dealer_md'], $data['dealer']['nama_dealer']));
        $this->excel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($header_style);
        $this->excel->getActiveSheet()->getStyle('A2:J2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);
        $this->excel->getActiveSheet()->mergeCells('A2:J2');

        $this->buat_kop_table();

        $this->excel->setActiveSheetIndex(0)->setCellValue('A5', 'Plafon Awal');
        $this->excel->setActiveSheetIndex(0)->setCellValue('J5', $data['dealer']['plafon']);
        $this->excel->getActiveSheet()->getStyle('J5')->getNumberFormat()->setFormatCode('#,##0');

        $this->excel->setActiveSheetIndex(0)->setCellValue('A6', 'Total DO Pending');
        $this->excel->setActiveSheetIndex(0)->setCellValue('H6', $data['plafon_booking']);
        $this->excel->getActiveSheet()->getStyle('H6')->getNumberFormat()->setFormatCode('#,##0');
        $this->excel->setActiveSheetIndex(0)->setCellValue('J6', $data['dealer']['plafon'] - $data['plafon_booking']);
        $this->excel->getActiveSheet()->getStyle('J6')->getNumberFormat()->setFormatCode('#,##0');

        $row = 7;
        $sisa_plafon = $data['dealer']['plafon'] - $data['plafon_booking'];
        $total_piutang = 0;
        $total_amount = 0;
        $total_sudah_dibayar = 0;
        foreach ($data['faktur'] as $index => $faktur) {
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $row), $index + 1);
            $this->excel->getActiveSheet()->getStyle(sprintf('A%s', $row))->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ]);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $row), $faktur['referensi']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $row), $faktur['produk']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $row), $faktur['tanggal_transaksi']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $row), $faktur['tanggal_jatuh_tempo']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $row), $faktur['total_amount']);
            $this->excel->getActiveSheet()->getStyle(sprintf('F%s', $row))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $row), $faktur['sudah_dibayar']);
            $this->excel->getActiveSheet()->getStyle(sprintf('G%s', $row))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $row), $faktur['sisa_piutang']);
            $this->excel->getActiveSheet()->getStyle(sprintf('H%s', $row))->getNumberFormat()->setFormatCode('#,##0');
            $sisa_plafon -= $faktur['sisa_piutang'];

            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $row), $sisa_plafon);
            $this->excel->getActiveSheet()->getStyle(sprintf('J%s', $row))->getNumberFormat()->setFormatCode('#,##0');
            $total_piutang += (double) $faktur['sisa_piutang'];
            $total_amount += (double) $faktur['total_amount'];
            $total_sudah_dibayar += (double) $faktur['sudah_dibayar'];

            if(count($faktur['bg']) > 0){
                foreach ($faktur['bg'] as $bg) {
                    $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $row), sprintf('%s - %s', $bg['nomor_bg'], $bg['tanggal_jatuh_tempo_bg']));
                    $row++;
                }
            }else{
                $row++;
            }
            
        }

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $row), 'Total Piutang');
        $this->excel->getActiveSheet()->mergeCells(sprintf('A%s:E%s', $row, $row));
        $this->excel->getActiveSheet()->getStyle(sprintf('A%s:E%s', $row, $row))->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $row), $total_amount);
        $this->excel->getActiveSheet()->getStyle(sprintf('F%s', $row))->getNumberFormat()->setFormatCode('#,##0');
        $this->excel->getActiveSheet()->getStyle(sprintf('F%s', $row))->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle'  => Border::BORDER_THIN
                ],
            ]
        ]);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $row), $total_sudah_dibayar);
        $this->excel->getActiveSheet()->getStyle(sprintf('G%s', $row))->getNumberFormat()->setFormatCode('#,##0');
        $this->excel->getActiveSheet()->getStyle(sprintf('G%s', $row))->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle'  => Border::BORDER_THIN
                ],
            ]
        ]);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $row), $total_piutang + $data['plafon_booking']);
        $this->excel->getActiveSheet()->getStyle(sprintf('H%s', $row))->getNumberFormat()->setFormatCode('#,##0');
        $this->excel->getActiveSheet()->getStyle(sprintf('H%s', $row))->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle'  => Border::BORDER_THIN
                ],
            ]
        ]);
    }

    private function buat_kop_table(){
        $kop_table = [
            'No.', 'No. Faktur', 'Jenis Pembelian', 'Tgl Faktur', 'Jatuh Tempo', 'Amount', 'Pembayaran', 'Piutang', 'Keterangan', 'Sisa Plafon'
        ];

        foreach ($kop_table as $index => $value) {
            $letter_number = Coordinate::stringFromColumnIndex($index + 1);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('%s4', $letter_number), $value);
            $this->excel->getActiveSheet()->getStyle(sprintf('%s4', $letter_number))->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'top' => [
                        'borderStyle'  => Border::BORDER_THIN
                    ],
                    'bottom' => [
                        'borderStyle'  => Border::BORDER_THIN
                    ],
                ]
            ]);
        }
    }
}
