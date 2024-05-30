<?php

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class H3_md_laporan_purchase_order_md_model extends Honda_Model {

    public $excel;

    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $this->excel = \PhpOffice\PhpSpreadsheet\IOFactory::load('assets/template/laporan_purchase_order_md_template.xlsx');
        // Settingan awal fil excel
        $this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle('Report Laporan Purchase Order MD');
    }

    public function generate($id_purchase_order){
        $purchase_order = $this->db
        ->select('po.produk')
        ->from('tr_h3_md_purchase_order as po')
        ->where('po.id_purchase_order', $id_purchase_order)
        ->get()->row_array();
        $data = $this->get_data($id_purchase_order);

        $sheetName = 'Purchase Order';
        $produk = $purchase_order['produk'];
        if($produk == 'Acc'){
            $produk = 'Accesories';
        }
        $sheetName .= " {$produk}";
        $this->excel->getActiveSheet()->setTitle($sheetName);

        $start_row = 2;
        $index = 1;
        foreach($data as $row){
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $start_row), $row['sold_to_party']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $start_row), Mcarbon::parse($row['approved_at'])->format('d-m-Y'));
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $start_row), $row['jenis_po']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $start_row), $row['id_purchase_order']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $start_row), $index);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $start_row), $row['id_part']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $start_row), $row['qty_order']);
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $start_row), Mcarbon::parse($row['approved_at'])->format('d.m.Y'));
            $this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $start_row), $row['qq_code']);
            if($row['produk'] == 'Oil'){
                $this->excel->setActiveSheetIndex(0)->getCell(sprintf('T%s', $start_row))->setValueExplicit('01', DataType::TYPE_STRING);
            }

            
            $style = $this->excel->getActiveSheet()->getStyle(sprintf('B%s', $start_row));
            
            $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            
            $style2 = $this->excel->getActiveSheet()->getStyle(sprintf('E%s', $start_row));
            
            $style2->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $index++;
            $start_row++;
        }

        $this->download($id_purchase_order);
    }

    public function download($id_purchase_order){
        $writer = new Xlsx($this->excel);
        ob_end_clean();
		$filename = 'Report Laporan Purchase Order MD ' . $id_purchase_order;
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
    }

    private function get_data($id_purchase_order){
        $this->db
        ->select('"E20" as sold_to_party', false)
        ->select('po.tanggal_po')
        ->select('po.jenis_po')
        ->select('po.produk')
        ->select('po.id_purchase_order')
        ->select('pop.id_part')
        ->select('pop.qty_order')
        ->select('po.bulan')
        ->select('po.tahun')
        ->select('"E20" as qq_code')
        ->select('po.approved_at')
        ->from('tr_h3_md_purchase_order as po')
        ->join('tr_h3_md_purchase_order_parts as pop', 'pop.id_purchase_order = po.id_purchase_order')
        ->where('po.jenis_po', 'REG')
        ->where('po.id_purchase_order', $id_purchase_order);

        return $this->db->get()->result_array();
    }
}
