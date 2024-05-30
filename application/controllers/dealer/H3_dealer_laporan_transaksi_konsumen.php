<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_transaksi_konsumen extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_transaksi_konsumen";
	var $title  = "Laporan Transaksi Konsumen";

	protected $excel;
	protected $monthLastIndex = 9;

	public function __construct()
	{		 
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name=="") echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$this->excel = new PHPExcel();

		$this->load->database();
		$this->load->model('m_admin');
		$this->load->library('Mcarbon');
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}


	public function generate(){
		if($this->input->get('type') == 'Excel'){
			$this->excel();
		}else if($this->input->get('type') == 'Pdf'){
			$this->pdf();
		}
	}

	private function get_customer(){
		$transaksi_pertama = $this->db
		->select('sa.tgl_servis')
		->from('tr_h2_sa_form as sa')
		->where('sa.id_customer = c.id_customer')
		->order_by('sa.created_at', 'desc')
		->limit(1)
		->where('sa.status_form', 'closed')
		->get_compiled_select();

		$transaksi_pertama = $this->db
		->select('so.tanggal_so')
		->from('tr_h3_dealer_sales_order as so')
		->where('so.id_customer = c.id_customer', null, false)
		->where('so.status', 'Closed')
		->order_by('so.created_at', 'desc')
		->limit(1)
		->get_compiled_select();

		$customer_terdapat_transaksi = $this->db
		->select('so.id_customer_int')
		->from('tr_h3_dealer_sales_order as so')
		->where("so.tanggal_so BETWEEN '{$this->input->get('start_date')}' AND '{$this->input->get('end_date')}'", null, false)
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		->where('so.status', 'Closed')
		->get_compiled_select();

		$customers = $this->db
		->select('c.id_customer_int')
		->select('c.id_customer')
		->select('c.nama_customer')
		->select('IFNULL(c.alamat, "-") as alamat', false)
		->select('IFNULL(c.no_hp, "-") as no_hp', false)
		->select('IFNULL(
			CONCAT(kab.kabupaten, " - ", prov.provinsi),
			"-"
		) as kab_kota', false)
		->select('IFNULL(tk.tipe_ahm, "-") as tipe_motor')
		->select('IFNULL(tk.deskripsi_ahm, "-") as nama_tipe_motor')
		->select('IFNULL(c.no_polisi, "-") as no_polisi', false)
		->select("IFNULL(
			DATE_FORMAT( ({$transaksi_pertama}), '%d/%m/%Y'), 
			'-'
		) as transaksi_pertama", false)
		->from('ms_customer_h23 as c')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
		->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
		->where('c.is_dealer', 0)
		->where("c.id_customer_int IN ({$customer_terdapat_transaksi})", null, false)
		// ->where('c.id_customer_int', 16105)
		->get()->result_array();

		return $customers;
	}

	private function get_transaksi($id_customer, $start_date, $end_date){
		$this->db->start_cache();
		$this->db
		->select('IFNULL(
			SUM(sop.kuantitas * sop.harga_saat_dibeli),
			0
		) as amount', false)
		// ->select('skp.produk')
		// ->select('so.nomor_so')
		// ->select('sop.kuantitas')
		// ->select('sop.harga_saat_dibeli')
		->from('tr_h3_dealer_sales_order_parts as sop')
		->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = sop.nomor_so')
		->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
		->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
		->group_start()
		->where("so.created_at BETWEEN '{$start_date}' AND '{$end_date}'", null, false)
		->group_end()
		->where('so.status', 'Closed')
		->where('so.id_customer_int', $id_customer)
		->where('so.id_dealer', $this->m_admin->cari_dealer())
		;
		$this->db->stop_cache();
	}

	public function pdf(){
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$customers = $this->get_customer();

		$start_date = Mcarbon::parse($this->input->get('start_date'));
		$end_date = Mcarbon::parse($this->input->get('end_date'));
		$diffInMonths = $end_date->diffInMonths($start_date);
		$month_range = range(0, $diffInMonths);
		$chunkMonthRange = array_chunk($month_range, 2);

		$final_data = [];
		foreach ($chunkMonthRange as $chunk) {
			$per_chunk = [];
			foreach ($customers as $customer) {
				$penjualan_per_month = [];
				foreach ($chunk as $month) {
					$dateObj = Mcarbon::parse($this->input->get('start_date'));
					$dateObj = $dateObj->addMonths($month);
					$firstDayOfMonth = $dateObj->startOfMonth()->format('Y-m-d 00:00:01');
					$lastDayOfMonth = $dateObj->endOfMonth()->format('Y-m-d 23:59:59');
	
					$data = [];
					$data['label_month'] = $dateObj->format('F Y');
					
					$this->get_transaksi($customer['id_customer_int'], $firstDayOfMonth, $lastDayOfMonth);
					$data['oli'] = $this->db->where('skp.produk', 'Oil')->get()->row_array();
					$data['part'] = $this->db->where('skp.produk', 'Parts')->get()->row_array();
					$this->db->flush_cache();

					$penjualan_per_month[] = $data;
				}
				$customer['data_penjualan'] = $penjualan_per_month;
				$per_chunk[] = $customer;
			}
			$final_data[] = $per_chunk;
		}

		$html = $this->load->view('dealer/h3_dealer_laporan_transaksi_konsumen_pdf', [
			'data' => $final_data
		], true);
		
		// render the view into HTML
		$mpdf->addPage('L');
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel(){	
        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Kelompok Barang Per Part Number");

		$start_date = Mcarbon::parse($this->input->get('start_date'));
		$end_date = Mcarbon::parse($this->input->get('end_date'));

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'Laporan Transaksi Konsumen');
		$this->excel->setActiveSheetIndex(0)->setCellValue('A3', "{$start_date->format('d/m/Y')} - {$end_date->format('d/m/Y')}");

		$this->excel->setActiveSheetIndex(0)->setCellValue('A5', 'No');
		$this->excel->getActiveSheet()->mergeCells("A5:A6");
		$this->excel->getActiveSheet()->getStyle("A5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("A5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->excel->setActiveSheetIndex(0)->setCellValue('B5', 'Nama Konsumen');
		$this->excel->getActiveSheet()->mergeCells("B5:B6");
		$this->excel->getActiveSheet()->getStyle("B5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("B5:B6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("B5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$this->excel->setActiveSheetIndex(0)->setCellValue('C5', 'Alamat');
		$this->excel->getActiveSheet()->mergeCells("C5:C6");
		$this->excel->getActiveSheet()->getStyle("C5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("C5:C6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("C5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
		$this->excel->setActiveSheetIndex(0)->setCellValue('D5', 'No. Handphone');
		$this->excel->getActiveSheet()->mergeCells("D5:D6");
		$this->excel->getActiveSheet()->getStyle("D5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("D5:D6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("D5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
		$this->excel->setActiveSheetIndex(0)->setCellValue('E5', 'Kota/Kab');
		$this->excel->getActiveSheet()->mergeCells("E5:E6");
		$this->excel->getActiveSheet()->getStyle("E5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("E5:E6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("E5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
		$this->excel->setActiveSheetIndex(0)->setCellValue('F5', 'Tipe Motor');
		$this->excel->getActiveSheet()->mergeCells("F5:F6");
		$this->excel->getActiveSheet()->getStyle("F5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("F5:F6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("F5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
		$this->excel->setActiveSheetIndex(0)->setCellValue('G5', 'Nama Tipe Motor');
		$this->excel->getActiveSheet()->mergeCells("G5:G6");
		$this->excel->getActiveSheet()->getStyle("G5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("G5:G6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("G5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(9);
		$this->excel->setActiveSheetIndex(0)->setCellValue('H5', 'No. Polisi');
		$this->excel->getActiveSheet()->mergeCells("H5:H6");
		$this->excel->getActiveSheet()->getStyle("H5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("H5:H6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("H5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(9);
		$this->excel->setActiveSheetIndex(0)->setCellValue('I5', 'Tgl. Pertama Transaksi di AHASS');
		$this->excel->getActiveSheet()->mergeCells("I5:I6");
		$this->excel->getActiveSheet()->getStyle("I5")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("I5:I6")->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("I5")->applyFromArray([
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		]);

		$customers = $this->get_customer();

		$diffInMonths = $end_date->diffInMonths($start_date);
		$month_range = range(0, $diffInMonths);

		$final_data = [];
		foreach ($customers as $customer) {
			$penjualan_per_month = [];
			foreach ($month_range as $month) {
				$dateObj = Mcarbon::parse($this->input->get('start_date'));
				$dateObj = $dateObj->addMonths($month);
				$firstDayOfMonth = $dateObj->startOfMonth()->format('Y-m-d 00:00:01');
				$lastDayOfMonth = $dateObj->endOfMonth()->format('Y-m-d 23:59:59');

				$data = [];
				$data['label_month'] = $dateObj->format('F Y');
				
				$this->get_transaksi($customer['id_customer_int'], $firstDayOfMonth, $lastDayOfMonth);
				$data['oli'] = $this->db->where('skp.produk', 'Oil')->get()->row_array();
				$data['part'] = $this->db->where('skp.produk', 'Parts')->get()->row_array();
				$this->db->flush_cache();

				$penjualan_per_month[] = $data;
			}
			$customer['data_penjualan'] = $penjualan_per_month;
			$final_data[] = $customer;
		}

		$startIndex = 7;
		$loop_index = 1;
		foreach ($final_data as $customer):
			$this->excel->setActiveSheetIndex(0)->setCellValue("A{$startIndex}", $loop_index);
			$this->excel->getActiveSheet()->getStyle("A{$startIndex}")->applyFromArray([
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("A{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("A")->setWidth(5);

			$this->excel->setActiveSheetIndex(0)->setCellValue("B{$startIndex}", $customer['nama_customer']);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("B")->setWidth(18);

			$this->excel->setActiveSheetIndex(0)->setCellValue("C{$startIndex}", $customer['alamat']);
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("C")->setWidth(30);

			$this->excel->setActiveSheetIndex(0)->setCellValue("D{$startIndex}", $customer['no_hp']);
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("D")->setWidth(15);

			$this->excel->setActiveSheetIndex(0)->setCellValue("E{$startIndex}", $customer['kab_kota']);
			$this->excel->getActiveSheet()->getStyle("E{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("E{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("E")->setWidth(15);

			$this->excel->setActiveSheetIndex(0)->setCellValue("F{$startIndex}", $customer['tipe_motor']);
			$this->excel->getActiveSheet()->getStyle("F{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("F{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("F")->setWidth(15);

			$this->excel->setActiveSheetIndex(0)->setCellValue("G{$startIndex}", $customer['nama_tipe_motor']);
			$this->excel->getActiveSheet()->getStyle("G{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("G{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("G")->setWidth(15);

			$this->excel->setActiveSheetIndex(0)->setCellValue("H{$startIndex}", $customer['no_polisi']);
			$this->excel->getActiveSheet()->getStyle("H{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("H{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("H")->setWidth(15);

			$this->excel->setActiveSheetIndex(0)->setCellValue("I{$startIndex}", $customer['transaksi_pertama']);
			$this->excel->getActiveSheet()->getStyle("I{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getStyle("I{$startIndex}")->applyFromArray($this->fontStyle());
			$this->excel->getActiveSheet()->getColumnDimension("I")->setWidth(15);

			foreach ($customer['data_penjualan'] as $data_penjualan) {
				$this->monthSection($loop_index, $data_penjualan);
			}
			$this->monthLastIndex = 9;
			$startIndex++;
			$loop_index++;
		endforeach;

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Laporan Transaksi Konsumen");
		$this->excel->setActiveSheetIndex(0);
		
        $this->download();
	}

	public function monthSection($loop_index, $data_penjualan){
		$monthColumnStart = $this->getColumnLetter($this->monthLastIndex);
		$monthLastIndex = ($this->monthLastIndex + 2 - 1);
		$monthColumnEnd = $this->getColumnLetter($monthLastIndex);

		$this->excel->setActiveSheetIndex(0)->setCellValue($monthColumnStart . 5, $data_penjualan['label_month']);
		$this->excel->getActiveSheet()->mergeCells("{$monthColumnStart}5:{$monthColumnEnd}5");
		$this->excel->getActiveSheet()->getStyle($monthColumnStart . 5)->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$monthColumnStart}5:{$monthColumnEnd}5")->applyFromArray($this->borderThinStyle());

		$oliColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$oliColumn}6", "Oli");
		$this->excel->getActiveSheet()->getStyle("{$oliColumn}6")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$oliColumn}6")->applyFromArray($this->borderThinStyle());

		$partColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$partColumn}6", "Part");
		$this->excel->getActiveSheet()->getStyle("{$partColumn}6")->applyFromArray($this->headerStyle());
		$this->excel->getActiveSheet()->getStyle("{$partColumn}6")->applyFromArray($this->borderThinStyle());

		$amountPembelianOliColumn = $this->getColumnLetter($this->monthLastIndex);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountPembelianOliColumn}" . (7 + $loop_index - 1), $data_penjualan['oli']['amount']);
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianOliColumn}" . (7 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianOliColumn}" . (7 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianOliColumn}" . (7 + $loop_index - 1))->getNumberFormat()->setFormatCode('Rp #,##0');
		$this->excel->getActiveSheet()->getColumnDimension($amountPembelianOliColumn)->setWidth(15);

		$amountPembelianPartColumn = $this->getColumnLetter($this->monthLastIndex + 1);
		$this->excel->setActiveSheetIndex(0)->setCellValue("{$amountPembelianPartColumn}" . (7 + $loop_index - 1), $data_penjualan['part']['amount']);
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianPartColumn}" . (7 + $loop_index - 1))->applyFromArray($this->borderThinStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianPartColumn}" . (7 + $loop_index - 1))->applyFromArray($this->fontStyle());
		$this->excel->getActiveSheet()->getStyle("{$amountPembelianPartColumn}" . (7 + $loop_index - 1))->getNumberFormat()->setFormatCode('Rp #,##0');
		$this->excel->getActiveSheet()->getColumnDimension($amountPembelianPartColumn)->setWidth(15);

		$this->monthLastIndex = $monthLastIndex + 1;
	}

	public function download(){
		ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Transaksi Konsumen.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $write->save('php://output');
        ob_end_clean();
	}

	function getColumnLetter( $number ){
		$prefix = '';
		$suffix = '';
		$prefNum = intval( $number/26 );
		if( $number > 25 ){
			$prefix = $this->getColumnLetter( $prefNum - 1 );
		}
		$suffix = chr( fmod( $number, 26 )+65 );
		return $prefix.$suffix;
	}
	
	public function headerStyle(){
		return  [
			'font' => [
				// 'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE,
				'size'  => 10,
				'name'  => 'Tahoma'
			],
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f9fc3d')
			),
		];
	}

	public function borderThinStyle(){
		return  [
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				)
			)
		];
	}

	public function fontStyle(){
		return  [
			'font' => [
				'size'  => 10,
				'name'  => 'Tahoma'
			],
		];
	}

	public function getMonthName($month){
		$dateObj = DateTime::createFromFormat('!m', $month);
		return $dateObj->format('F');
	}
}