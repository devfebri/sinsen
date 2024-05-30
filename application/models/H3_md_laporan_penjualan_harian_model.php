<?php

class H3_md_laporan_penjualan_harian_model extends Honda_Model {

    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');

        // ini_set('memory_limit', '-1');
        // ini_set('max_execution_time', '0');
    }

    private function generateExcel($periode_awal = null, $periode_akhir = null){
		$this->excel = \PhpOffice\PhpSpreadsheet\IOFactory::load('assets/template/report_penerimaan_pembayaran_template.xlsx');
        $this->excel->getProperties()
            ->setCreator('SSP')
            ->setLastModifiedBy('SSP')
            ->setTitle('Report Laporan Penerimaan by Packing Sheet');

        $data = $this->data($periode_awal, $periode_akhir);

        $borders = [
			'borders' => array(
				'allborders' => array(
					'borderStyle' => Border::BORDER_THIN
				),
			)
		];

        $sideBorder = [
			'borders' => array(
				'left' => array(
					'borderStyle' => Border::BORDER_THIN
				),
				'right' => array(
					'borderStyle' => Border::BORDER_THIN
				),
			)
		];

        if($periode_awal != null and $periode_akhir != null){
			$this->excel->setActiveSheetIndex(0)->setCellValue('A2', sprintf('Periode %s s.d %s', Mcarbon::parse($periode_awal)->format('d-m-Y'), Mcarbon::parse($periode_akhir)->format('d-m-Y')));
		}

		$index = 1;
		$startRow = 5;
		foreach($data as $row){
			$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $startRow), $index);
			$this->excel->getActiveSheet()->getStyle(sprintf('A%s', $startRow))->applyFromArray($sideBorder);

			$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $startRow), sprintf('%s - %s', $row['kode_dealer_md'], $row['nama_dealer']));
			$this->excel->getActiveSheet()->getStyle(sprintf('B%s', $startRow))->applyFromArray($sideBorder);
			
			$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('C%s', $startRow), $row['id_penerimaan_pembayaran']);
			$this->excel->getActiveSheet()->getStyle(sprintf('C%s', $startRow))->applyFromArray($sideBorder);


			foreach($row['items'] as $item){
				$this->excel->getActiveSheet()->getStyle(sprintf('A%s', $startRow))->applyFromArray($sideBorder);
				$this->excel->getActiveSheet()->getStyle(sprintf('B%s', $startRow))->applyFromArray($sideBorder);
				$this->excel->getActiveSheet()->getStyle(sprintf('C%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $startRow), Mcarbon::parse($row['created_at'])->format('d-m-Y'));
				$this->excel->getActiveSheet()->getStyle(sprintf('D%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $startRow), $item['referensi']);
				$this->excel->getActiveSheet()->getStyle(sprintf('E%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $startRow), $row['jumlah_pembayaran']);
				$this->excel->getActiveSheet()->getStyle(sprintf('F%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('G%s', $startRow), $item['nominal_cash']);
				$this->excel->getActiveSheet()->getStyle(sprintf('G%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $startRow), $item['nominal_bg']);
				$this->excel->getActiveSheet()->getStyle(sprintf('H%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $startRow), $item['nominal_transfer']);
				$this->excel->getActiveSheet()->getStyle(sprintf('I%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('J%s', $startRow), $row['nomor_bg']);
				$this->excel->getActiveSheet()->getStyle(sprintf('J%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('K%s', $startRow), $row['tgl_transfer_atau_bg']);
				$this->excel->getActiveSheet()->getStyle(sprintf('K%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('L%s', $startRow), $row['bank_tujuan']);
				$this->excel->getActiveSheet()->getStyle(sprintf('L%s', $startRow))->applyFromArray($sideBorder);
				
				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('M%s', $startRow), $row['keterangan']);
				$this->excel->getActiveSheet()->getStyle(sprintf('M%s', $startRow))->applyFromArray($sideBorder);

				$startRow++;
			}

			$index++;
		}

        $this->downloadExcel();
    }

    private function downloadExcel(){
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
        ob_end_clean();
		$filename = 'Report Penerimaan Pembayaran';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
    }

	public function generatePdf($periode_awal = null, $periode_akhir = null, $group = null){
		if($group == null){
			$this->laporanPenjualanHarianH3($periode_awal, $periode_akhir);
		}elseif($group == 'tanggal'){
			$this->laporanPenjualanHarianGroupTanggal($periode_awal, $periode_akhir);
		}elseif($group == 'customer'){
			$this->laporanPenjualanHarianGroupCustomer($periode_awal, $periode_akhir);
		}elseif($group == 'salesman'){
			$this->laporanPenjualanHarianGroupSalesman($periode_awal, $periode_akhir);
		}elseif($group == 'kelompok_barang'){
			$this->laporanPenjualanHarianGroupKelompokBarang($periode_awal, $periode_akhir);
		}
	}

	private function laporanPenjualanHarianH3($periode_awal, $periode_akhir){
		$data =  $this->laporanPenjualanHarianH3Data($periode_awal, $periode_akhir);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_laporan_penjualan_harian_pdf', [
			'data' => $data,
			'periode_awal' => $periode_awal,
			'periode_akhir' => $periode_akhir,
		], true);
        $mpdf->WriteHTML($html);

		$filename = 'Report Penjualan Harian';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
		
		$mpdf->SetFooter('Halaman {PAGENO} dari {nb}');
        // Output a PDF file directly to the browser
        $mpdf->Output("{$filename}.pdf", "I");
	}

    private function laporanPenjualanHarianH3Data($periode_awal, $periode_akhir){
		$periode_awal = Mcarbon::parse($periode_awal)->startOfDay();
		$periode_akhir = Mcarbon::parse($periode_akhir)->endOfDay();
        $this->db
        ->select('ps.tgl_faktur')
        ->select('ps.no_faktur')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('dop.id_part')
        ->select('p.nama_part')
        ->select('dop.qty_supply as qty_do')
        ->select('p.harga_md_dealer as hpp')
        ->select('sop.harga as het')
        ->select('(sop.diskon + sop.diskon_campaign) as diskon')
        ->select('(dop.qty_supply * p.harga_md_dealer) as cost')
        ->select('(dop.qty_supply * (sop.harga - (sop.diskon + sop.diskon_campaign))) as sub_total')
        ->select('p.kelompok_part')
        ->select('salesman.nama_lengkap as nama_salesman')
        ->select('d.h1')
        ->select('d.h2')
        ->select('d.h3')
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_sales_order_parts as sop', 'sop.id_sales_order = so.id_sales_order')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = sop.id_part)')
        ->join('ms_part as p', 'p.id_part = dop.id_part')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
        ->join('ms_karyawan as salesman', 'salesman.id_karyawan = so.id_salesman', 'left')
		->where('dop.qty_supply >', 0)
		->order_by('so.created_at', 'asc')
		->order_by('sop.id_sales_order', 'asc')
		->order_by('sop.id_part', 'asc')
        ;

		if($periode_awal != null AND $periode_akhir != null){
			$this->db->group_start();
			$this->db->where("ps.tgl_faktur between '{$periode_awal->toDateTimeString()}' AND '{$periode_akhir->toDateTimeString()}'", null, false);
			$this->db->group_end();
		}

		return $this->db->get()->result_array();    
    }

	private function laporanPenjualanHarianGroupTanggal($periode_awal, $periode_akhir){
		$data =  $this->laporanPenjualanHarianGroupTanggalData($periode_awal, $periode_akhir);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        $mpdf = new Mpdf();

        $html = $this->load->view('h3/h3_md_laporan_penjualan_harian_group_tanggal_pdf', [
			'data' => $data,
			'periode_awal' => $periode_awal,
			'periode_akhir' => $periode_akhir,
		], true);
        $mpdf->WriteHTML($html);

		$filename = 'Report Penjualan Harian';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
		$mpdf->SetFooter('Halaman {PAGENO} dari {nb}');
        $mpdf->Output("{$filename}.pdf", "I");
	}

    private function laporanPenjualanHarianGroupTanggalData($periode_awal, $periode_akhir){
        $dates = \Carbon\CarbonPeriod::create($periode_awal, $periode_akhir)->toArray();

		$data = [];
		foreach($dates as $carbon){
			$row = [];
			$row['tanggal'] = $carbon->toDateString();
			$row['penjualan'] = $this->db
			->select('ps.no_faktur')
			->select('d.nama_dealer')
			->select('do.id_do_sales_order')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->where("ps.tgl_faktur between '{$carbon->startOfDay()->toDateTimeString()}' and '{$carbon->endOfDay()->toDateTimeString()}'", null, false)
			->order_by('ps.tgl_faktur', 'asc')
			;

			$row['penjualan'] = array_map(function($row){
				$row['parts'] = $this->db
				->select('dop.id_part')
				->select('p.nama_part')
				->select('dop.qty_supply as qty')
				->select('dop.harga_jual as het')
				->select('(dop.harga_jual - dop.harga_setelah_diskon) as diskon', false)
				->select('(dop.qty_supply * dop.harga_setelah_diskon) as total_harga', false)
				->from('tr_h3_md_do_sales_order_parts as dop')
				->join('ms_part as p', 'p.id_part = dop.id_part')
				->where('dop.id_do_sales_order', $row['id_do_sales_order'])
				->where('dop.qty_supply >', 0)
				->order_by('dop.id_part', 'asc')
				->get()->result_array();

				$row['total_per_faktur'] = array_sum(
					array_map(function($item){
						return $item['total_harga'];
					}, $row['parts'])
				);

				return $row;
			}, $this->db->get()->result_array());

			$row['index_needed'] = array_sum(
				array_map(function($penjualan){
					return count($penjualan['parts']) + 1;
				}, $row['penjualan'])
			);

			$row['total_per_tanggal'] = array_sum(
				array_map(function($row){
					return $row['total_per_faktur'];
				}, $row['penjualan'])
			);

			if(count($row['penjualan']) > 0) $data[] = $row;
		}

		return $data;
    }

	private function laporanPenjualanHarianGroupCustomer($periode_awal, $periode_akhir){
		$data =  $this->laporanPenjualanHarianGroupCustomerData($periode_awal, $periode_akhir);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        $mpdf = new Mpdf();

        $html = $this->load->view('h3/h3_md_laporan_penjualan_harian_group_customer_pdf', [
			'data' => $data,
			'periode_awal' => $periode_awal,
			'periode_akhir' => $periode_akhir,
		], true);
        $mpdf->WriteHTML($html);

		$filename = 'Report Penjualan Harian';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
		$mpdf->SetFooter('Halaman {PAGENO} dari {nb}');
        $mpdf->Output("{$filename}.pdf", "I");
	}

    private function laporanPenjualanHarianGroupCustomerData($periode_awal, $periode_akhir){
		$periode_awal = Mcarbon::parse($periode_awal);
		$periode_akhir = Mcarbon::parse($periode_akhir);

		$this->db
		->select('pl.id_dealer')
		->select('d.nama_dealer')
		->from('tr_h3_md_packing_sheet as ps')
		->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
		->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->where("ps.tgl_faktur between '{$periode_awal->startOfDay()->toDateTimeString()}' and '{$periode_akhir->endOfDay()->toDateTimeString()}'", null, false)
		->group_by('pl.id_dealer')
		;

		$customers = [];
		foreach($this->db->get()->result_array() as $customer){
			$this->db
			->select('ps.no_faktur')
			->select('ps.tgl_faktur')
			->select('do.id_do_sales_order')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
			->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->where('pl.id_dealer', $customer['id_dealer'])
			->where("ps.tgl_faktur between '{$periode_awal->startOfDay()->toDateTimeString()}' and '{$periode_akhir->endOfDay()->toDateTimeString()}'", null, false)
			->order_by('ps.tgl_faktur', 'asc')
			->order_by('ps.no_faktur', 'asc')
			;

			$customer['penjualan'] = array_map(function($row){
				$row['parts'] = $this->db
				->select('dop.id_part')
				->select('p.nama_part')
				->select('dop.qty_supply as qty')
				->select('dop.harga_jual as het')
				->select('(dop.harga_jual - dop.harga_setelah_diskon) as diskon', false)
				->select('(dop.qty_supply * dop.harga_setelah_diskon) as total_harga', false)
				->from('tr_h3_md_do_sales_order_parts as dop')
				->join('ms_part as p', 'p.id_part_int = dop.id_part_int')
				->where('dop.id_do_sales_order', $row['id_do_sales_order'])
				->where('dop.qty_supply >', 0)
				->order_by('dop.id_part', 'asc')
				->get()->result_array();

				$row['total_per_faktur'] = array_sum(
					array_map(function($item){
						return $item['total_harga'];
					}, $row['parts'])
				);

				unset($row['id_do_sales_order']);

				return $row;
			}, $this->db->get()->result_array());

			$customer['total_per_customer'] = array_sum(
				array_map(function($row){
					return $row['total_per_faktur'];
				}, $customer['penjualan'])
			);

			if(count($customer['penjualan']) > 0) $customers[] = $customer;
		}

		return $customers;
    }

	private function laporanPenjualanHarianGroupSalesman($periode_awal, $periode_akhir){
		$data =  $this->laporanPenjualanHarianGroupSalesmanData($periode_awal, $periode_akhir);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        $mpdf = new Mpdf();

        $html = $this->load->view('h3/h3_md_laporan_penjualan_harian_group_salesman_pdf', [
			'data' => $data,
			'periode_awal' => $periode_awal,
			'periode_akhir' => $periode_akhir,
		], true);
        $mpdf->WriteHTML($html);

		$filename = 'Report Penjualan Harian';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
		$mpdf->SetFooter('Halaman {PAGENO} dari {nb}');
        $mpdf->Output("{$filename}.pdf", "I");
	}

    private function laporanPenjualanHarianGroupSalesmanData($periode_awal, $periode_akhir){
		$periode_awal = Mcarbon::parse($periode_awal);
		$periode_akhir = Mcarbon::parse($periode_akhir);

		$this->db
		->select('so.id_salesman')
		->select('k.nama_lengkap as nama_salesman')
		->from('tr_h3_md_packing_sheet as ps')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman')
		->where("ps.tgl_faktur between '{$periode_awal->startOfDay()->toDateTimeString()}' and '{$periode_akhir->endOfDay()->toDateTimeString()}'", null, false)
		->where('so.id_salesman is not null', null, false)
		->group_by('so.id_salesman')
		;

		$salesmans = [];
		foreach($this->db->get()->result_array() as $salesman){
			$this->db
			->select('ps.no_faktur')
			->select('d.nama_dealer')
			->select('do.id_do_sales_order')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->where('so.id_salesman', $salesman['id_salesman'])
			->where("ps.tgl_faktur between '{$periode_awal->startOfDay()->toDateTimeString()}' and '{$periode_akhir->endOfDay()->toDateTimeString()}'", null, false)
			->order_by('ps.tgl_faktur', 'asc')
			->order_by('ps.no_faktur', 'asc')
			;

			$salesman['penjualan'] = array_map(function($row){
				$row['parts'] = $this->db
				->select('dop.id_part')
				->select('p.nama_part')
				->select('dop.qty_supply as qty')
				->select('dop.harga_jual as het')
				->select('(dop.harga_jual - dop.harga_setelah_diskon) as diskon', false)
				->select('(dop.qty_supply * dop.harga_setelah_diskon) as total_harga', false)
				->from('tr_h3_md_do_sales_order_parts as dop')
				->join('ms_part as p', 'p.id_part = dop.id_part')
				->where('dop.id_do_sales_order', $row['id_do_sales_order'])
				->where('dop.qty_supply >', 0)
				->order_by('dop.id_part', 'asc')
				->get()->result_array();

				$row['total_per_faktur'] = array_sum(
					array_map(function($item){
						return $item['total_harga'];
					}, $row['parts'])
				);

				unset($row['id_do_sales_order']);

				return $row;
			}, $this->db->get()->result_array());

			$salesman['total_per_salesman'] = array_sum(
				array_map(function($row){
					return $row['total_per_faktur'];
				}, $salesman['penjualan'])
			);

			if(count($salesman['penjualan']) > 0) $salesmans[] = $salesman;
		}
		
		return $salesmans;
    }

	private function laporanPenjualanHarianGroupKelompokBarang($periode_awal, $periode_akhir){
		$data =  $this->laporanPenjualanHarianGroupKelompokBarangData($periode_awal, $periode_akhir);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        $mpdf = new Mpdf();

        $html = $this->load->view('h3/h3_md_laporan_penjualan_harian_group_kelompok_barang_pdf', [
			'data' => $data,
			'periode_awal' => $periode_awal,
			'periode_akhir' => $periode_akhir,
		], true);
        $mpdf->WriteHTML($html);

		$filename = 'Report Penjualan Harian';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
		$mpdf->SetFooter('Halaman {PAGENO} dari {nb}');
        $mpdf->Output("{$filename}.pdf", "I");
	}

    private function laporanPenjualanHarianGroupKelompokBarangData($periode_awal, $periode_akhir){
		$periode_awal = Mcarbon::parse($periode_awal);
		$periode_akhir = Mcarbon::parse($periode_akhir);

		$this->db
		->select('p.kelompok_part')
		->from('tr_h3_md_packing_sheet as ps')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
		->join('ms_part as p', 'p.id_part = dop.id_part')
		->where("ps.tgl_faktur between '{$periode_awal->startOfDay()->toDateTimeString()}' and '{$periode_akhir->endOfDay()->toDateTimeString()}'", null, false)
		->group_by('p.kelompok_part')
		;

		$kelompok_barangs = [];
		foreach($this->db->get()->result_array() as $kelompok_barang){
			$kelompok_barang['penjualan'] = $this->db
			->select('dop.id_part')
			->select('p.nama_part')
			->select('dop.qty_supply as qty')
			->select('dop.harga_beli as hpp')
			->select('dop.harga_jual as het')
			->select('(dop.harga_jual - dop.harga_setelah_diskon) as diskon', false)
			->select('(dop.qty_supply * dop.harga_setelah_diskon) as total_jual', false)
			->select('(dop.qty_supply * dop.harga_beli) as total_pokok', false)
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->join('ms_part as p', 'p.id_part = dop.id_part')
			->where('p.kelompok_part', $kelompok_barang['kelompok_part'])
			->where('dop.qty_supply >', 0)
			->where("ps.tgl_faktur between '{$periode_awal->startOfDay()->toDateTimeString()}' and '{$periode_akhir->endOfDay()->toDateTimeString()}'", null, false)
			->order_by('dop.id_part', 'asc')
			->order_by('ps.tgl_faktur', 'asc')
			->order_by('ps.no_faktur', 'asc')
			->get()->result_array();

			$kelompok_barang['total_jual_per_kelompok_barang'] = array_sum(
				array_map(function($row){
					return $row['total_jual'];
				}, $kelompok_barang['penjualan'])
			);

			$kelompok_barang['total_pokok_per_kelompok_barang'] = array_sum(
				array_map(function($row){
					return $row['total_pokok'];
				}, $kelompok_barang['penjualan'])
			);

			if(count($kelompok_barang['penjualan']) > 0) $kelompok_barangs[] = $kelompok_barang;
		}

		return $kelompok_barangs;
    }
}
