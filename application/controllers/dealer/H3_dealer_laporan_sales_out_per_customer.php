<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_sales_out_per_customer extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_sales_out_per_customer";
	var $title  = "Laporan Sales Out per Customer";

	protected $excel;
	protected $monthLastIndex = 2;

	public function __construct()
	{		 
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name=="") echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$this->excel = new PHPExcel();

		$this->load->database();
		$this->load->model('m_admin');
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}


	public function generate(){
		// $tanggal = date("Y-m-d");
		// // $tanggal = '2023-07-13';
		// // if($tanggal <='2023-07-10' || $tanggal >='2023-07-15'){
		// // if(1){

		// if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){
		// 	$where = '';
		// }else{
		// 	$where = "and p.kelompok_part !='FED OIL'";
		// }

		$where = '';
		if($this->config->item('ahm_d_only')){
		  $where = "and p.kelompok_part !='FED OIL'";
		}
		
		
		// $sales = $this->db
		// ->select('so.nomor_so')
		// ->select('so.id_work_order')
		// ->select('so.id_customer_int')
		// ->select('date_format(so.tanggal_so, "%d/%m/%Y") as tanggal_so')
		// ->select('IFNULL(c.nama_customer, "-") as nama_customer')
		// ->select('IFNULL(c.alamat, "-") as alamat')
		// ->select('IFNULL(kab.kabupaten, "-") as kabupaten')
		// ->select('IFNULL(tk.tipe_ahm, "-") as tipe_kendaraan')
        // ->select('IFNULL(tk.deskripsi_ahm, "-") as deskripsi_unit')
        // ->select('IFNULL(c.no_polisi, "-") as no_polisi')
        // ->select('IFNULL(c.tahun_produksi, "-") as tahun_produksi')
        // ->select('IFNULL(kategori.kategori, "-") as kategori')
		// ->select('IFNULL(nsc.no_nsc, "-") as no_nsc', false)
		// ->select('sop.id_part')
		// ->select('sop.id_part_int')
		// ->select('p.nama_part')
		// ->select('sop.kuantitas')
		// ->select('concat("Rp ", format(p.harga_dealer_user, 0, "ID_id")) as het_formatted')
		// ->select('p.harga_dealer_user as het')
		// ->select('sop.tipe_diskon')
		// ->select('sop.diskon_value')
		// ->select("
		// 	case 
		// 		when sop.tipe_diskon = 'Percentage' then concat( format(sop.diskon_value, 0, 'ID_id'), '%')
		// 		when sop.tipe_diskon = 'Value' then concat( 'Rp ', format(sop.diskon_value, 0, 'ID_id'))
		// 		when sop.diskon_value is null then '-'
		// 		else sop.diskon_value
		// 	end as diskon_formatted
		// ", false)
		// ->select('p.kelompok_part')
		// ->select('ifnull(promo.tipe_promo, "-") as jenis_promo')
		// ->from('tr_h3_dealer_sales_order as so')
		// ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
		// ->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
		// ->join('ms_part as p', 'p.id_part_int = sop.id_part_int '.$where, 'left')
		// ->join('tr_h23_nsc as nsc', '(nsc.id_referensi = so.nomor_so or nsc.id_referensi = so.id_work_order)', 'left')
		// ->join('ms_h3_promo_dealer as promo', 'promo.id_promo = sop.id_promo', 'left')
		// ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
		// ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
		// ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        // ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
		// ->join('ms_kategori as kategori', 'kategori.id_kategori = tk.id_kategori', 'left')
		// ->group_start()
		// ->where("so.tanggal_so BETWEEN '{$this->input->get('start_date')}' AND '{$this->input->get('end_date')}'", null, false)
		// ->group_end()
		// ->where('so.id_dealer', $this->m_admin->cari_dealer())
		// ->where('so.status', 'Closed')
		// ->order_by('so.nomor_so', 'asc')
		// ->order_by('so.tanggal_so', 'asc')
		// ->get()->result_array()
		// ->get_compiled_select()
		// ;

		$sales = "
		SELECT nomor_so, id_work_order, id_customer_int, tanggal_so, nama_customer, alamat, kabupaten, tipe_kendaraan, deskripsi_unit, no_polisi, tahun_produksi, kategori, no_nsc, id_part, id_part_int, nama_part, kuantitas, het_formatted, het, tipe_diskon, diskon_value, diskon_formatted, kelompok_part, jenis_promo
		FROM (
			(SELECT so.nomor_so, so.id_work_order, so.id_customer_int, DATE_FORMAT(so.tanggal_so, '%d/%m/%Y') as tanggal_so, IFNULL(c.nama_customer, '-') as nama_customer, IFNULL(c.alamat, '-') as alamat, IFNULL(kab.kabupaten, '-') as kabupaten, IFNULL(tk.tipe_ahm, '-') as tipe_kendaraan, IFNULL(tk.deskripsi_ahm, '-') as deskripsi_unit, IFNULL(c.no_polisi, '-') as no_polisi, IFNULL(c.tahun_produksi, '-') as tahun_produksi, IFNULL(kategori.kategori, '-') as kategori, IFNULL(nsc.no_nsc, '-') as no_nsc, sop.id_part, sop.id_part_int, p.nama_part, sop.kuantitas, CONCAT('Rp ', FORMAT(p.harga_dealer_user, 0, 'ID_id')) as het_formatted, p.harga_dealer_user as het, sop.tipe_diskon, sop.diskon_value, CASE WHEN sop.tipe_diskon = 'Percentage' THEN CONCAT(FORMAT(sop.diskon_value, 0, 'ID_id'), '%') WHEN sop.tipe_diskon = 'Value' THEN CONCAT('Rp ', FORMAT(sop.diskon_value, 0, 'ID_id')) WHEN sop.diskon_value IS NULL THEN '-' ELSE sop.diskon_value END as diskon_formatted, p.kelompok_part, IFNULL(promo.tipe_promo, '-') as jenis_promo FROM tr_h3_dealer_sales_order as so INNER JOIN tr_h3_dealer_sales_order_parts as sop ON sop.nomor_so_int = so.id INNER JOIN ms_customer_h23 as c ON c.id_customer_int = so.id_customer_int INNER JOIN ms_part as p ON p.id_part_int = sop.id_part_int INNER JOIN tr_h23_nsc as nsc ON nsc.id_referensi = so.nomor_so LEFT JOIN ms_h3_promo_dealer as promo ON promo.id_promo = sop.id_promo LEFT JOIN ms_kelurahan as kel ON kel.id_kelurahan = c.id_kelurahan LEFT JOIN ms_kecamatan as kec ON kec.id_kecamatan = kel.id_kecamatan LEFT JOIN ms_kabupaten as kab ON kab.id_kabupaten = kec.id_kabupaten LEFT JOIN ms_tipe_kendaraan as tk ON tk.id_tipe_kendaraan = c.id_tipe_kendaraan LEFT JOIN ms_kategori as kategori ON kategori.id_kategori = tk.id_kategori WHERE so.tanggal_so BETWEEN '{$this->input->get('start_date')}' AND '{$this->input->get('end_date')}' AND so.id_dealer = '{$this->m_admin->cari_dealer()}' AND so.status = 'Closed')
			UNION
			(SELECT so.nomor_so, so.id_work_order, so.id_customer_int, DATE_FORMAT(so.tanggal_so, '%d/%m/%Y') as tanggal_so, IFNULL(c.nama_customer, '-') as nama_customer, IFNULL(c.alamat, '-') as alamat, IFNULL(kab.kabupaten, '-') as kabupaten, IFNULL(tk.tipe_ahm, '-') as tipe_kendaraan, IFNULL(tk.deskripsi_ahm, '-') as deskripsi_unit, IFNULL(c.no_polisi, '-') as no_polisi, IFNULL(c.tahun_produksi, '-') as tahun_produksi, IFNULL(kategori.kategori, '-') as kategori, IFNULL(nsc.no_nsc, '-') as no_nsc, sop.id_part, sop.id_part_int, p.nama_part, sop.kuantitas, CONCAT('Rp ', FORMAT(p.harga_dealer_user, 0, 'ID_id')) as het_formatted, p.harga_dealer_user as het, sop.tipe_diskon, sop.diskon_value, CASE WHEN sop.tipe_diskon = 'Percentage' THEN CONCAT(FORMAT(sop.diskon_value, 0, 'ID_id'), '%') WHEN sop.tipe_diskon = 'Value' THEN CONCAT('Rp ', FORMAT(sop.diskon_value, 0, 'ID_id')) WHEN sop.diskon_value IS NULL THEN '-' ELSE sop.diskon_value END as diskon_formatted, p.kelompok_part, IFNULL(promo.tipe_promo, '-') as jenis_promo FROM tr_h3_dealer_sales_order as so INNER JOIN tr_h3_dealer_sales_order_parts as sop ON sop.nomor_so_int = so.id INNER JOIN ms_customer_h23 as c ON c.id_customer_int = so.id_customer_int INNER JOIN ms_part as p ON p.id_part_int = sop.id_part_int INNER JOIN tr_h23_nsc as nsc ON nsc.id_referensi = so.id_work_order LEFT JOIN ms_h3_promo_dealer as promo ON promo.id_promo = sop.id_promo LEFT JOIN ms_kelurahan as kel ON kel.id_kelurahan = c.id_kelurahan LEFT JOIN ms_kecamatan as kec ON kec.id_kecamatan = kel.id_kecamatan LEFT JOIN ms_kabupaten as kab ON kab.id_kabupaten = kec.id_kabupaten LEFT JOIN ms_tipe_kendaraan as tk ON tk.id_tipe_kendaraan = c.id_tipe_kendaraan LEFT JOIN ms_kategori as kategori ON kategori.id_kategori = tk.id_kategori WHERE so.tanggal_so BETWEEN '{$this->input->get('start_date')}' AND '{$this->input->get('end_date')}' AND so.id_dealer = '{$this->m_admin->cari_dealer()}' AND so.status = 'Closed')
		) AS final_query";

		$sales = $this->db->query($sales)->result_array();

		// send_json($sales);

		if($this->input->get('type') == 'Excel'){
			$this->excel($sales);
		}else if($this->input->get('type') == 'Pdf'){
			$this->pdf($sales);
		}
	}

	public function pdf($sales)
	{
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;

		$data = [
			'sales' => $sales
		];

		$html = $this->load->view('dealer/h3_dealer_laporan_sales_out_per_customer_pdf', $data, true);
		
		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}

	public function excel($sales)
	{		
        $this->excel->getProperties()->setCreator('SSP')
		->setLastModifiedBy('SSP')
		->setTitle("Laporan Sales Out per Customer");

		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'Laporan Sales Out per Customer');
		$this->excel->getActiveSheet()->mergeCells("A1:F1");
		$this->excel->getActiveSheet()->getStyle("A1")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
		]);

		$startDate = date('d/m/Y', strtotime($this->input->get('start_date')));
		$endDate = date('d/m/Y', strtotime($this->input->get('end_date')));
		$this->excel->setActiveSheetIndex(0)->setCellValue('G1', "{$startDate} - {$endDate}");
		$this->excel->getActiveSheet()->mergeCells("G1:K1");
		$this->excel->getActiveSheet()->getStyle("G1")->applyFromArray([
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
		]);

		$headerColumns = [
			'A' => 5,
			'B' => 14,
			'C' => 25,
			'D' => 25,
			'E' => 25,
			'F' => 25,
			'G' => 13,
			'H' => 16,
			'I' => 11,
			'J' => 25,
			'K' => 26,
			'L' => 43,
			'M' => 14,
			'N' => 10,
			'O' => 12,
			'P' => 18,
			'Q' => 14
		];

		foreach ($headerColumns as $key => $value) {
			$this->excel->getActiveSheet()->getStyle($key . 3)->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$this->excel->getActiveSheet()->getColumnDimension($key)->setWidth($value);
		}

		$this->excel->setActiveSheetIndex(0)->setCellValue("A3", 'No');
		$this->excel->setActiveSheetIndex(0)->setCellValue("B3", 'Tanggal');
		$this->excel->setActiveSheetIndex(0)->setCellValue("C3", 'Nama Konsumen');
		$this->excel->setActiveSheetIndex(0)->setCellValue("D3", 'Alamat');
		$this->excel->setActiveSheetIndex(0)->setCellValue("E3", 'Kota/Kab');
		$this->excel->setActiveSheetIndex(0)->setCellValue("F3", 'Jenis Motor');
		$this->excel->setActiveSheetIndex(0)->setCellValue("G3", 'No. Polisi');
		$this->excel->setActiveSheetIndex(0)->setCellValue("H3", 'Tahun Produksi');
		$this->excel->setActiveSheetIndex(0)->setCellValue("I3", 'Tipe Motor');
		$this->excel->setActiveSheetIndex(0)->setCellValue("J3", 'No. NSC');
		$this->excel->setActiveSheetIndex(0)->setCellValue("K3", 'Nomor Part');
		$this->excel->setActiveSheetIndex(0)->setCellValue("L3", 'Description Parts');
		$this->excel->setActiveSheetIndex(0)->setCellValue("M3", 'HET');
		$this->excel->setActiveSheetIndex(0)->setCellValue("N3", 'Disc');
		$this->excel->setActiveSheetIndex(0)->setCellValue("O3", 'Total');
		$this->excel->setActiveSheetIndex(0)->setCellValue("P3", 'Kel. Produk');
		$this->excel->setActiveSheetIndex(0)->setCellValue("Q3", 'Jenis Promo');

		$startIndex = 4;
		$loop_index = 1;
		foreach ($sales as $each):
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
			$this->excel->setActiveSheetIndex(0)->setCellValue("B{$startIndex}", $each['tanggal_so']);
			$this->excel->getActiveSheet()->getStyle("B{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("C{$startIndex}", $each['nama_customer']);
			$this->excel->getActiveSheet()->getStyle("C{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("D{$startIndex}", $each['alamat']);
			$this->excel->getActiveSheet()->getStyle("D{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("E{$startIndex}", $each['kabupaten']);
			$this->excel->getActiveSheet()->getStyle("E{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("F{$startIndex}", $each['tipe_kendaraan']);
			$this->excel->getActiveSheet()->getStyle("F{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("G{$startIndex}", $each['no_polisi']);
			$this->excel->getActiveSheet()->getStyle("G{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValueExplicit("H{$startIndex}", $each['tahun_produksi'], PHPExcel_Cell_DataType::TYPE_STRING);
			$this->excel->getActiveSheet()->getStyle("H{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("I{$startIndex}", $each['kategori']);
			$this->excel->getActiveSheet()->getStyle("I{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			
			$this->excel->setActiveSheetIndex(0)->setCellValue("J{$startIndex}", $each['no_nsc']);
			$this->excel->getActiveSheet()->getStyle("J{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValueExplicit("K{$startIndex}", $each['id_part'], PHPExcel_Cell_DataType::TYPE_STRING);
			$this->excel->getActiveSheet()->getStyle("K{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("L{$startIndex}", $each['nama_part']);
			$this->excel->getActiveSheet()->getStyle("L{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("M{$startIndex}", $each['het_formatted']);
			$this->excel->getActiveSheet()->getStyle("M{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				),
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValueExplicit("N{$startIndex}", $each['diskon_formatted'], PHPExcel_Cell_DataType::TYPE_STRING);
			$this->excel->getActiveSheet()->getStyle("N{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				)
			]);

			$total = 0;

			if($each['tipe_diskon'] == 'Percentage'){
				$potongan_harga = ($each['diskon_value']/100) * $each['het'];
				$total = $each['kuantitas'] * ($each['het'] - $potongan_harga);
			}elseif($each['tipe_diskon'] == 'Value'){
				$total = $each['kuantitas'] * ($each['het'] - $each['diskon_value']);
			}else{
				$total = $each['kuantitas'] * $each['het'];
			}

			$this->excel->setActiveSheetIndex(0)->setCellValue("O{$startIndex}", "Rp " . number_format($total, 0, ",", "."));
			$this->excel->getActiveSheet()->getStyle("O{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("P{$startIndex}", $each['kelompok_part']);
			$this->excel->getActiveSheet()->getStyle("P{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);

			$this->excel->setActiveSheetIndex(0)->setCellValue("Q{$startIndex}", $each['jenis_promo']);
			$this->excel->getActiveSheet()->getStyle("Q{$startIndex}")->applyFromArray([
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					)
				)
			]);
			$startIndex++;
			$loop_index++;
		endforeach;

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet(0)->setTitle("Laporan Sales Out per Customer");
		$this->excel->setActiveSheetIndex(0);
		
        $this->download();
	}

	public function download(){
		ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan sales Out per Customer.xlsx"'); // Set nama file excel nya
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

	public function getMonthName($month){
		$dateObj = DateTime::createFromFormat('!m', $month);
		return $dateObj->format('F');
	}
}