<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class H3_md_memo_plafon_model extends Honda_Model {

    public $excel;
    public $periode_awal;
    public $periode_akhir;

    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $this->excel = \PhpOffice\PhpSpreadsheet\IOFactory::load('assets/template/memo_plafon_template.xlsx');
        // Settingan awal fil excel
        $this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle('Memo Plafon MD');
    }

    public function generate($ids, $fileType = null){
        $data = $this->data($ids);

        if($fileType == 'excel'){
            $this->generateExcel($data);
            $this->downloadExcel();
        }elseif($fileType == 'pdf'){
            $this->generatePdf($data);
        }
    }

    public function generateExcel($data){
        $this->excel->setActiveSheetIndex(0)->setCellValue('E6', sprintf(': %s', Mcarbon::now()->format('d F Y')));

        $underline = [
            'font' => [
                'underline' => true,
            ],
        ];

        $alignment_middle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $alignment_left = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ];

        $borders = [
			'borders' => array(
                'allBorders' => array(
					'borderStyle' => Border::BORDER_THIN
				),
			)
		];
        $start_row = 15;
        foreach($data as $index => $row){
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $start_row), $index + 1);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $start_row), $row['nama_dealer']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $start_row), $row['kode_dealer_md']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $start_row), $row['alamat']);

            $status_toko = sprintf('%s RUKO %s', $row['jumlah_ruko'], $row['status_bangunan']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $start_row), $status_toko);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $start_row), $row['plafon_awal']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $start_row), $row['sisa_plafon']);

            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('M%s', $start_row), $row['nilai_po_part']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('N%s', $start_row), $row['nilai_po_oli']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('O%s', $start_row), $row['nilai_penambahan_plafon']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('P%s', $start_row), $row['nilai_penambahan_plafon_finance']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('Q%s', $start_row), $row['keterangan_pengajuan']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('R%s', $start_row), $row['nilai_penambahan_plafon_pimpinan']);

            $merge_per_row_start = $start_row;
            $merge_per_row_end = $start_row;
            if(count($row['faktur']) > 0){
                foreach($row['faktur'] as $faktur){
                    $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $start_row), $faktur['tgl_jatuh_tempo']);
                    $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $start_row), $faktur['nilai_faktur']);

                    if(count($faktur['rincian_pembayaran']) > 0){
                        $this->excel->getActiveSheet()->mergeCells(sprintf('J%s:J%s', $start_row, ($start_row + count($faktur['rincian_pembayaran']) - 1 )));
                        $this->excel->getActiveSheet()->getStyle(sprintf('J%s:J%s', $start_row, ($start_row + count($faktur['rincian_pembayaran']) - 1 )))->applyFromArray($alignment_middle);
                        $this->excel->getActiveSheet()->getStyle(sprintf('J%s:J%s', $start_row, ($start_row + count($faktur['rincian_pembayaran']) - 1 )))->applyFromArray($borders);
                        
                        $this->excel->getActiveSheet()->mergeCells(sprintf('K%s:K%s', $start_row, ($start_row + count($faktur['rincian_pembayaran']) - 1 )));
                        $this->excel->getActiveSheet()->getStyle(sprintf('K%s:K%s', $start_row, ($start_row + count($faktur['rincian_pembayaran']) - 1 )))->applyFromArray($alignment_middle);
                        $this->excel->getActiveSheet()->getStyle(sprintf('K%s:K%s', $start_row, ($start_row + count($faktur['rincian_pembayaran']) - 1 )))->applyFromArray($borders);

                        foreach ($faktur['rincian_pembayaran'] as $rincian_pembayaran) {
                            $keterangan_bg = sprintf('No. BG : %s ', $rincian_pembayaran['nomor_bg']);
                            $keterangan_bg .= sprintf('Tgl Cair : %s ', $rincian_pembayaran['tanggal_jatuh_tempo_bg']);
                            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $start_row), $keterangan_bg);
                            $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($alignment_middle);
                            $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($borders);
                            $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->getAlignment()->setWrapText(true);
                            $start_row++; $merge_per_row_end++;
                        }
                    }else{
                        $this->excel->getActiveSheet()->getStyle(sprintf('J%s', $start_row))->applyFromArray($alignment_middle);
                        $this->excel->getActiveSheet()->getStyle(sprintf('J%s', $start_row))->applyFromArray($borders);
                        
                        $this->excel->getActiveSheet()->getStyle(sprintf('K%s', $start_row))->applyFromArray($alignment_middle);
                        $this->excel->getActiveSheet()->getStyle(sprintf('K%s', $start_row))->applyFromArray($borders);

                        $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($alignment_middle);
                        $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($borders);
                        $start_row++; $merge_per_row_end++;
                    }
                }
            }else{
                $this->excel->getActiveSheet()->getStyle(sprintf('J%s', $start_row))->applyFromArray($alignment_middle);
                $this->excel->getActiveSheet()->getStyle(sprintf('J%s', $start_row))->applyFromArray($borders);
                
                $this->excel->getActiveSheet()->getStyle(sprintf('K%s', $start_row))->applyFromArray($alignment_middle);
                $this->excel->getActiveSheet()->getStyle(sprintf('K%s', $start_row))->applyFromArray($borders);

                $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($alignment_middle);
                $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($borders);
                $start_row++; $merge_per_row_end++;
            }

            $this->excel->getActiveSheet()->mergeCells(sprintf('C%s:C%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('C%s:C%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('C%s:C%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);
            
            $this->excel->getActiveSheet()->mergeCells(sprintf('D%s:D%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('D%s:D%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('D%s:D%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('E%s:E%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('E%s:E%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('E%s:E%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('F%s:F%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('F%s:F%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('F%s:F%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('G%s:G%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('G%s:G%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('G%s:G%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('H%s:H%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('H%s:H%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('H%s:H%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('I%s:I%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('I%s:I%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('I%s:I%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('M%s:M%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('M%s:M%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('M%s:M%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('N%s:N%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('N%s:N%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('N%s:N%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('O%s:O%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('O%s:O%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('O%s:O%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('P%s:P%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('P%s:P%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('P%s:P%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('Q%s:Q%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('Q%s:Q%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('Q%s:Q%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

            $this->excel->getActiveSheet()->mergeCells(sprintf('R%s:R%s', $merge_per_row_start, $merge_per_row_end - 1));
            $this->excel->getActiveSheet()->getStyle(sprintf('R%s:R%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($alignment_middle);
            $this->excel->getActiveSheet()->getStyle(sprintf('R%s:R%s', $merge_per_row_start, $merge_per_row_end - 1))->applyFromArray($borders);

        }

        $start_row++;
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $start_row), 'Dibuat Oleh,');
        $this->excel->getActiveSheet()->mergeCells(sprintf('C%s:D%s', $start_row, $start_row ));
        $this->excel->getActiveSheet()->getStyle(sprintf('C%s:D%s', $start_row, $start_row ))->applyFromArray($alignment_left);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $start_row), 'Pemohon,');
        $this->excel->getActiveSheet()->mergeCells(sprintf('F%s:G%s', $start_row, $start_row ));
        $this->excel->getActiveSheet()->getStyle(sprintf('F%s:G%s', $start_row, $start_row ))->applyFromArray($alignment_left);
        
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $start_row), 'Diketahui Oleh,');
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('Q%s', $start_row), 'Disetujui Oleh,');

        $start_row += 5;
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $start_row), $this->input->get('admin'));
        $this->excel->getActiveSheet()->mergeCells(sprintf('C%s:D%s', $start_row, $start_row ));
        $this->excel->getActiveSheet()->getStyle(sprintf('C%s:D%s', $start_row, $start_row ))->applyFromArray($alignment_left);
        $this->excel->getActiveSheet()->getStyle(sprintf('C%s:D%s', $start_row, $start_row ))->applyFromArray($underline);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $start_row), $this->input->get('marketing'));
        $this->excel->getActiveSheet()->mergeCells(sprintf('F%s:G%s', $start_row, $start_row ));
        $this->excel->getActiveSheet()->getStyle(sprintf('F%s:G%s', $start_row, $start_row ))->applyFromArray($alignment_left);
        $this->excel->getActiveSheet()->getStyle(sprintf('F%s:G%s', $start_row, $start_row ))->applyFromArray($underline);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $start_row), $this->input->get('part_manager'));
        $this->excel->getActiveSheet()->getStyle(sprintf('J%s', $start_row))->applyFromArray($underline);
        
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $start_row), $this->input->get('finance_head'));
        $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($underline);
        
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('Q%s', $start_row), $this->input->get('pimpinan'));
        $this->excel->getActiveSheet()->getStyle(sprintf('Q%s', $start_row))->applyFromArray($underline);

        $start_row++;
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $start_row), 'Admin');
        $this->excel->getActiveSheet()->mergeCells(sprintf('C%s:D%s', $start_row, $start_row ));
        $this->excel->getActiveSheet()->getStyle(sprintf('C%s:D%s', $start_row, $start_row ))->applyFromArray($alignment_left);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $start_row), 'Marketing');
        $this->excel->getActiveSheet()->mergeCells(sprintf('F%s:G%s', $start_row, $start_row ));
        $this->excel->getActiveSheet()->getStyle(sprintf('F%s:G%s', $start_row, $start_row ))->applyFromArray($alignment_left);

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $start_row), 'Part Manager');
        
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $start_row), 'Finance Head');
        
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('Q%s', $start_row), 'Pimpinan');
    }

    public function downloadExcel(){
        $writer = new Xlsx($this->excel);
        ob_end_clean();
		$filename = 'Memo Plafon MD';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
    }

    public function generatePdf($data){
        require_once APPPATH .'third_party/mpdf/mpdf.php';
        $mpdf = new Mpdf();
        
        $html = $this->load->view('h3/h3_md_memo_plafon_md_pdf', [
            'data' => $data
        ], true);
        $mpdf->WriteHTML($html);

		$filename = 'Memo Plafon';
        $mpdf->Output("{$filename}.pdf", "I");
	}

    private function data($ids){
        $this->db
        ->select('plafon.id')
        ->select('d.status_bangunan')
        ->select('d.id_dealer')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.alamat')
        ->select('
        case
            when d.gudang_sendiri is not null then (d.gudang_sendiri = "Ya")
            else 0
        end as gudang_sendiri', false)
        ->select('IFNULL(d.jumlah_ruko, 0) AS jumlah_ruko')
        ->select('plafon.plafon_awal')
        ->select('plafon.sisa_plafon')
        ->select('plafon.nilai_po_part')
        ->select('plafon.nilai_po_oli')
        ->select('
            case
                when plafon.nilai_penambahan_plafon != 0 then plafon.nilai_penambahan_plafon
                when plafon.nilai_penambahan_sementara != 0 then plafon.nilai_penambahan_sementara
                else 0
            end as nilai_penambahan_plafon
        ', false)
        ->select('
            case
                when plafon.nilai_penambahan_plafon_finance != 0 then plafon.nilai_penambahan_plafon_finance
                when plafon.nilai_penambahan_sementara_finance != 0 then plafon.nilai_penambahan_sementara_finance
                else 0
            end as nilai_penambahan_plafon_finance
        ', false)
        ->select('
            case
                when plafon.nilai_penambahan_plafon_pimpinan != 0 then plafon.nilai_penambahan_plafon_pimpinan
                when plafon.nilai_penambahan_sementara_pimpinan != 0 then plafon.nilai_penambahan_sementara_pimpinan
                else 0
            end as nilai_penambahan_plafon_pimpinan
        ', false)
        ->select('
            case
                WHEN plafon.nilai_penambahan_sementara_finance != 0 then "Sementara"
                WHEN plafon.nilai_penambahan_sementara != 0 then "Sementara"
                WHEN plafon.nilai_penambahan_plafon != 0 then "Tetap"
                ELSE 0
            end as keterangan_pengajuan
        ', false)
        ->from('ms_h3_md_plafon as plafon')
        ->join('ms_dealer as d', 'd.id_dealer = plafon.id_dealer')
        ->where_in('plafon.id', $ids)
        ;

        $data = array_map(function($row){
            $row['faktur'] = array_map(function($faktur){
                $faktur['rowspan_rincian_pembayaran'] = count($faktur['rincian_pembayaran']) > 0 ? count($faktur['rincian_pembayaran']) : 1;
                return $faktur;
            }, $this->plafon->get_faktur($row['id_dealer'], true, true));
            $row['rowspan_faktur'] = count($row['faktur']);

            return $row;
        }, $this->db->get()->result_array());

        return $data;
    }
}
