<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class H3_md_report_laporan_penerimaan_by_packing_sheet_with_amount_model_spreadsheet extends Honda_Model {

    public $excel;
    public $periode_awal;
    public $periode_akhir;

    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $this->excel = \PhpOffice\PhpSpreadsheet\IOFactory::load('assets/template/report_laporan_penerimaan_barang_by_packing_sheet_with_amount.xlsx');
        // Settingan awal fil excel
        $this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle('Report Laporan Penerimaan by Packing Sheet With Amount');
    }

    public function generate($periode_awal = null, $periode_akhir = null){
        $this->excel->setActiveSheetIndex(0)->setCellValue('C4', ': PT Sinar Sentosa Primatama');

        if($periode_awal != null AND $periode_akhir != null){
            $this->excel->setActiveSheetIndex(0)->setCellValue('B1', 
                sprintf('Periode : %s s/d %s', Mcarbon::parse($periode_awal)->format('d-m-Y'), Mcarbon::parse($periode_akhir)->format('d-m-Y'))
            );
        }

        $data = $this->get_data($periode_awal, $periode_akhir);

        $y_border = [
			'borders' => array(
                'bottom' => array(
					'style' => Border::BORDER_THIN
				),
				'top' => array(
					'style' => Border::BORDER_THIN
				),
			)
		];
        $start_row = 7;
        $index = 1;
        $total_qty = 0;
        $total_jumlah_harga = 0;
        foreach($data as $row){
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $start_row), $index);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $start_row), Mcarbon::parse($row['tanggal_penerimaan'])->format('d-m-Y'));
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $start_row), $row['no_penerimaan_barang']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $start_row), Mcarbon::parse($row['packing_sheet_date'])->format('d-m-Y'));
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $start_row), $row['packing_sheet_number']);
            foreach($row['items'] as $item){
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $start_row), $item['nomor_karton']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $start_row), $item['no_po']);
                if($item['invoice_date'] != null){
                    $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $start_row), Mcarbon::parse($item['invoice_date'])->format('d-m-Y'));
                }
                if($item['invoice_number'] != null) $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $start_row), $item['invoice_number']);

                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $start_row), $item['id_part']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $start_row), $item['nama_part']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $start_row), $item['serial_number']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('M%s', $start_row), $item['qty_diterima']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('N%s', $start_row), $item['het']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('O%s', $start_row), $item['hpp']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('P%s', $start_row), $item['jumlah_harga']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('Q%s', $start_row), $item['kelompok_part']);
                $total_qty += intval($item['qty_diterima']);
                $total_jumlah_harga += floatval($item['jumlah_harga']);
                $start_row++;
            }
            $index++;
        }

        $this->excel->getActiveSheet()->getStyle(sprintf('A%s', $start_row))->applyFromArray($y_border);
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $start_row), 'TOTAL');
        $this->excel->getActiveSheet()->mergeCells(sprintf('B%s:K%s', $start_row, $start_row));
        $this->excel->getActiveSheet()->getStyle(sprintf('B%s:K%s', $start_row, $start_row))->applyFromArray($y_border);
        
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $start_row), $total_qty);
        $this->excel->getActiveSheet()->getStyle(sprintf('L%s', $start_row))->applyFromArray($y_border);

        $this->excel->getActiveSheet()->getStyle(sprintf('M%s', $start_row))->applyFromArray($y_border);
        $this->excel->getActiveSheet()->getStyle(sprintf('N%s', $start_row))->applyFromArray($y_border);


        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('O%s', $start_row), $total_jumlah_harga);
        $this->excel->getActiveSheet()->getStyle(sprintf('O%s', $start_row))->applyFromArray($y_border);

        $this->excel->getActiveSheet()->getStyle(sprintf('P%s', $start_row))->applyFromArray($y_border);

        $start_row += 2;

        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $start_row), sprintf('Tanggal cetakan : %s', Mcarbon::now()->format('d/m/Y')));
        $this->excel->setActiveSheetIndex(0)->mergeCells(sprintf('A%s:C%s', $start_row, $start_row));
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $start_row), sprintf('Jam Cetakan : %s ', Mcarbon::now()->format('H:i:s')));
        $this->excel->setActiveSheetIndex(0)->mergeCells(sprintf('D%s:F%s', $start_row, $start_row));

        $this->download();
    }

    public function download(){
        $writer = new Xlsx($this->excel);
        ob_end_clean();
		$filename = 'Report Laporan Penerimaan Barang by Packing Sheet';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
    }

    private function get_data($periode_awal = null, $periode_akhir = null){
        $this->db
        ->select('pb.no_penerimaan_barang')
        ->select('pb.tanggal_penerimaan')
        ->select('ps.packing_sheet_date')
        ->select('ps.packing_sheet_number')
        ->from('tr_h3_md_penerimaan_barang as pb')
        ->join('tr_h3_md_penerimaan_barang_items as pbi', 'pbi.no_penerimaan_barang = pb.no_penerimaan_barang')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = pbi.packing_sheet_number')
        ->order_by('pb.tanggal_penerimaan', 'asc')
        ;

        if($periode_awal != null AND $periode_akhir != null){
            $this->db->group_start();
            $this->db->where("pb.tanggal_penerimaan between '{$periode_awal}' AND '{$periode_akhir}'", null, false);
            $this->db->group_end();
        }

        $data = array_map(function($row){
            $row['items'] = $this->db
            ->select('pbi.nomor_karton')
            ->select('pbi.no_po')
            ->select('pbi.id_part')
            ->select('pbi.serial_number')
            ->select('p.nama_part')
            ->select('IFNULL(pbi.qty_diterima, 0) AS qty_diterima')
            ->select('lr.kode_lokasi_rak')
            ->select('fdo.invoice_number')
            ->select('fdo.invoice_date')
            ->select('IFNULL(fdo_parts.price, 0) as hpp')
            ->select('p.harga_dealer_user as het')
            ->select('(pbi.qty_diterima * IFNULL(fdo_parts.price, 0)) as jumlah_harga', false)
            ->select('p.kelompok_part')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->join('tr_h3_md_fdo_ps as fdo_ps', 'fdo_ps.packing_sheet_number = pbi.packing_sheet_number', 'left')
            ->join('tr_h3_md_fdo_parts as fdo_parts', '(fdo_parts.invoice_number = fdo_ps.invoice_number AND fdo_parts.id_part = pbi.id_part AND fdo_parts.nomor_packing_sheet = pbi.packing_sheet_number)', 'left')
            ->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = fdo_ps.invoice_number', 'left')
            ->join('ms_part as p', 'p.id_part = pbi.id_part')
            ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = pbi.id_lokasi_rak')
            ->where('pbi.no_penerimaan_barang', $row['no_penerimaan_barang'])
            ->where('pbi.packing_sheet_number', $row['packing_sheet_number'])
            ->where('pbi.tersimpan', 1)
            ->get()->result_array();

            return $row;
        }, $this->db->get()->result_array());

        return $data;
    }
}
