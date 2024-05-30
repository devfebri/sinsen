<?php

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class H3_md_report_laporan_penerimaan_by_packing_sheet_model extends Honda_Model {

    public $excel;
    public $periode_awal;
    public $periode_akhir;

    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $this->excel = \PhpOffice\PhpSpreadsheet\IOFactory::load('assets/template/report_laporan_penerimaan_barang_by_packing_sheet.xlsx');
        // Settingan awal fil excel
        $this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle('Report Laporan Penerimaan by Packing Sheet');
    }

    public function generate($periode_awal = null, $periode_akhir = null, $no_penerimaan_barang = null){
        $this->excel->setActiveSheetIndex(0)->setCellValue('C1', ': PT Sinar Sentosa Primatama');
        $this->excel->setActiveSheetIndex(0)->setCellValue('H1', sprintf('Tanggal cetakan : %s', Mcarbon::now()->format('d/m/Y')));
        $this->excel->setActiveSheetIndex(0)->setCellValue('J1', sprintf('Jam Cetakan : %s ', Mcarbon::now()->format('H:i:s')));

        if($periode_awal != null AND $periode_akhir != null){
            $this->excel->setActiveSheetIndex(0)->setCellValue('A4', 
                sprintf('Periode : %s s/d %s', Mcarbon::parse($periode_awal)->format('d-m-Y'), Mcarbon::parse($periode_akhir)->format('d-m-Y'))
            );
        }

        $data = $this->get_data($periode_awal, $periode_akhir, $no_penerimaan_barang);

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
        foreach($data as $row){
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $start_row), $index);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $start_row), Mcarbon::parse($row['tanggal_penerimaan'])->format('d-m-Y'));
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $start_row), $row['no_penerimaan_barang']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $start_row), Mcarbon::parse($row['packing_sheet_date'])->format('d-m-Y'));
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $start_row), $row['packing_sheet_number']);
            foreach($row['items'] as $item){
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $start_row), $item['nomor_karton']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $start_row), $item['no_po']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $start_row), $item['id_part']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $start_row), $item['nama_part']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $start_row), $item['qty_diterima']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $start_row), $item['kode_lokasi_rak']);
                $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $start_row), $item['kelompok_part']);
                $total_qty += intval($item['qty_diterima']);
                $start_row++;
            }
            $index++;
        }

        $this->excel->getActiveSheet()->getStyle(sprintf('A%s', $start_row))->applyFromArray($y_border);
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $start_row), 'TOTAL');
        $this->excel->getActiveSheet()->mergeCells(sprintf('B%s:I%s', $start_row, $start_row));
        $this->excel->getActiveSheet()->getStyle(sprintf('B%s:I%s', $start_row, $start_row))->applyFromArray($y_border);
        
        $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $start_row), $total_qty);
        $this->excel->getActiveSheet()->getStyle(sprintf('J%s', $start_row))->applyFromArray($y_border);

        $this->excel->getActiveSheet()->getStyle(sprintf('K%s', $start_row))->applyFromArray($y_border);

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

    public function generatePdf($periode_awal = null, $periode_akhir = null, $no_penerimaan_barang = null){
		$data =  $this->get_data($periode_awal, $periode_akhir, $no_penerimaan_barang);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_laporan_penerimaan_by_packing_sheet', [
			'data' => $data,
			'periode_awal' => $periode_awal,
			'periode_akhir' => $periode_akhir,
		], true);
        $mpdf->WriteHTML($html);

		$filename = 'Report Laporan Penerimaan Barang by Packing Sheet';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
        
        $mpdf->Output("{$filename}.pdf", "I");
	}

    private function get_data($periode_awal = null, $periode_akhir = null, $no_penerimaan_barang = null){
        $this->db
        ->select('pb.no_penerimaan_barang')
        ->select('pb.tanggal_penerimaan')
        ->select('ps.packing_sheet_date')
        ->select('ps.packing_sheet_number')
        ->from('tr_h3_md_penerimaan_barang as pb')
        ->join('tr_h3_md_penerimaan_barang_items as pbi', 'pbi.no_penerimaan_barang = pb.no_penerimaan_barang')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = pbi.packing_sheet_number')
        ->order_by('pb.tanggal_penerimaan', 'asc')
        ->order_by('pb.created_at', 'asc')
        ->order_by('pbi.packing_sheet_number', 'asc')
        ->group_by('pbi.no_penerimaan_barang')
        ->group_by('pbi.packing_sheet_number')
        ;

        if($no_penerimaan_barang != null){
            $this->db->where('pb.no_penerimaan_barang', $no_penerimaan_barang);
        }else{
            if($periode_awal != null AND $periode_akhir != null){
                $this->db->group_start();
                $this->db->where("pb.tanggal_penerimaan between '{$periode_awal}' AND '{$periode_akhir}'", null, false);
                $this->db->group_end();
            }
        }

        $data = array_map(function($row){
            $row['items'] = $this->db
            ->select('pbi.nomor_karton')
            ->select('pbi.no_po')
            ->select('pbi.id_part')
            ->select('pbi.serial_number')
            ->select('p.nama_part')
            ->select('pbi.qty_diterima')
            ->select('lr.kode_lokasi_rak')
            ->select('p.kelompok_part')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->join('ms_part as p', 'p.id_part = pbi.id_part')
            ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = pbi.id_lokasi_rak')
            ->where('pbi.no_penerimaan_barang', $row['no_penerimaan_barang'])
            ->where('pbi.packing_sheet_number', $row['packing_sheet_number'])
            ->where('pbi.tersimpan', 1)
            ->order_by('pbi.nomor_karton')
            ->order_by('pbi.id_part')
            ->get()->result_array();

            return $row;
        }, $this->db->get()->result_array());

        return $data;
    }
}
