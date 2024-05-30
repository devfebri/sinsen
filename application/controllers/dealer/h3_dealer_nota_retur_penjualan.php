<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_nota_retur_penjualan extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_nota_retur_penjualan";
	var $title  = "Nota Retur Penjualan";

	public function __construct()
	{		 
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name=="") echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		$this->load->database();
		$this->load->model('m_admin');
		$this->load->library('Mcarbon');
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}

	public function download_excel(){
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excel = PHPExcel_IOFactory::load("assets/template/nota_retur_penjualan_template.xlsx");

		$dealer = $this->db
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('d.no_telp')
		->from('ms_dealer as d')
		->where('d.id_dealer', $this->m_admin->cari_dealer())
		->get()->row_array();

		$periode_start = Mcarbon::parse($this->input->get('periode_filter_start'));
		$periode_end = Mcarbon::parse($this->input->get('periode_filter_end'));
		$excel->setActiveSheetIndex(0)->setCellValue('A6', "{$periode_start->format('d/m/Y')} - {$periode_end->format('d/m/Y')}");

		// $tanggal = date("Y-m-d");
		// if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){
		// 	$where = '';
		// }else{
		// 	$where = "and p.kelompok_part !='FED OIL'";
		// }
		
		$where = '';
		if($this->config->item('ahm_d_only')){
			$where = "and p.kelompok_part !='FED OIL'";
		}

		$data = $this->db
		->select('ps.nomor_ps')
		->select('so.nomor_so')
		->select('so.id_work_order')
		->select('wo.created_at as tgl_wo')
		->select('wo.no_nsc')
		->select('c.nama_customer')
		->select('c.no_polisi')
		->select('sop.id_part')
		->select('p.nama_part')
		->select('sop.id_rak')
		->select('sop.kuantitas')
		->select('sop.kuantitas_return')
		->select('(sop.kuantitas - sop.kuantitas_return) as kuantitas_terpakai', false)
		->from('tr_h3_dealer_sales_order as so')
		->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->join('tr_h3_dealer_picking_slip as ps', 'ps.nomor_so = so.nomor_so')
		->join('ms_part as p', 'p.id_part_int = sop.id_part_int '.$where)
		->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = so.id_work_order', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
		->where('sop.kuantitas_return > ', 0)
        ->where('ps.id_dealer', $this->m_admin->cari_dealer())
        ->where("ps.tanggal_ps between '{$periode_start}' AND '{$periode_end}'", null, false)
		->get()->result_array();

		$excel->setActiveSheetIndex(0)->setCellValue('B1', $dealer['kode_dealer_md']);
		$excel->setActiveSheetIndex(0)->setCellValue('B2', $dealer['nama_dealer']);
		$excel->setActiveSheetIndex(0)->setCellValue('B3', $dealer['alamat']);
		$excel->setActiveSheetIndex(0)->setCellValue('B4', $dealer['no_telp']);

		$allborders = [
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				),
			)
		];
		$start_row = 9;
		foreach ($data as $row) {
			$excel->setActiveSheetIndex(0)->setCellValue("A{$start_row}", $row['nomor_ps']);
			$excel->getActiveSheet()->getStyle("A{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("B{$start_row}", $row['nomor_so']);
			$excel->getActiveSheet()->getStyle("B{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("C{$start_row}", $row['id_work_order']);
			$excel->getActiveSheet()->getStyle("C{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("D{$start_row}", $row['tgl_wo'] != null ? date('d/m/Y', strtotime($row['tgl_wo'])) : '-' );
			$excel->getActiveSheet()->getStyle("D{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("E{$start_row}", $row['no_nsc']);
			$excel->getActiveSheet()->getStyle("E{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("F{$start_row}", $row['nama_customer']);
			$excel->getActiveSheet()->getStyle("F{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("G{$start_row}", $row['no_polisi']);
			$excel->getActiveSheet()->getStyle("G{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("H{$start_row}", $row['id_part']);
			$excel->getActiveSheet()->getStyle("H{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("I{$start_row}", $row['nama_part']);
			$excel->getActiveSheet()->getStyle("I{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("J{$start_row}", $row['id_rak']);
			$excel->getActiveSheet()->getStyle("J{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("K{$start_row}", $row['kuantitas']);
			$excel->getActiveSheet()->getStyle("K{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("L{$start_row}", $row['kuantitas_return']);
			$excel->getActiveSheet()->getStyle("L{$start_row}")->applyFromArray($allborders);

			$excel->setActiveSheetIndex(0)->setCellValue("M{$start_row}", $row['kuantitas_terpakai']);
			$excel->getActiveSheet()->getStyle("M{$start_row}")->applyFromArray($allborders);
			$start_row++;
		}

		ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=Nota Retur Penjualan {$periode_start->format('d-m-Y')} s.d {$periode_end->format('d-m-Y')}.xlsx"); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
        ob_end_clean();
	}
}