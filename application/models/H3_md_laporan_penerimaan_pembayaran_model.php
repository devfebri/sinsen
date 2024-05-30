<?php

use PhpOffice\PhpSpreadsheet\Style\Border;

class H3_md_laporan_penerimaan_pembayaran_model extends CI_Model
{
    public function __construct(){
        parent::__construct();

        $this->load->library('Mcarbon');

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
    }

    public function generateExcel($periode_awal = null, $periode_akhir = null){
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

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('H%s', $startRow), $item['nominal_transfer']);
				$this->excel->getActiveSheet()->getStyle(sprintf('H%s', $startRow))->applyFromArray($sideBorder);

				$this->excel->setActiveSheetIndex(0)->setCellValue(sprintf('I%s', $startRow), $item['nominal_bg']);
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

	public function generatePdf($periode_awal = null, $periode_akhir = null){
		$data =  $this->data($periode_awal, $periode_akhir);

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_laporan_penerimaan_pembayaran', [
			'data' => $data,
			'periode_awal' => $periode_awal,
			'periode_akhir' => $periode_akhir,
		], true);
        $mpdf->WriteHTML($html);

		$filename = 'Report Penerimaan Pembayaran';
		if($periode_awal != null AND $periode_akhir != null){
			$filename .= sprintf(' %s s.d %s', Mcarbon::parse($periode_awal)->format('d/m/Y'), Mcarbon::parse($periode_akhir)->format('d/m/Y'));
		}
		$mpdf->SetFooter('Halaman {PAGENO} dari {nb}');
		// $mpdf->SetFooter([
		// 	'odd' => [
		// 		'content' => 'Halaman {PAGENO} dari {nb}',
		// 		'font-size' => 10,
		// 		'font-style' => 'B',
		// 		'font-family' => 'Arial',
		// 	],
		// 	'even' => null,
		// 	'default' => null,
		// 	'first' => null,
		// ]);
        // Output a PDF file directly to the browser
        $mpdf->Output("{$filename}.pdf", "I");
	}

    public function data($periode_awal, $periode_akhir){
		if($periode_awal == null && $periode_akhir == null){
			throw new Exception('Filter periode tidak tersedia');
		}

        $this->db
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('pb.id_penerimaan_pembayaran')
		->select('pb.jenis_pembayaran')
		->select('pb.created_at')
		->select('pb.nominal_cash')
		->select('pb.nominal_bg')
		->select('pb.nominal_transfer')
		->select('
			case
				when pb.jenis_pembayaran = "Cash" then pb.nominal_cash
				when pb.jenis_pembayaran = "BG" then pb.nominal_bg
				when pb.jenis_pembayaran = "Transfer" then pb.nominal_transfer
				else null
			end as jumlah_pembayaran
		', false)
		->select('concat(pb.nomor_bg, "-", pb.nama_bank_bg) as nomor_bg', false)
		->select('
			case
				when pb.jenis_pembayaran = "BG" then pb.tanggal_jatuh_tempo_bg
				when pb.jenis_pembayaran = "Transfer" then pb.tanggal_transfer
				else null
			end as tgl_transfer_atau_bg
		', false)
		->select('
			case
				when pb.jenis_pembayaran = "BG" then rek_bg.bank
				when pb.jenis_pembayaran = "Transfer" then rek_transfer.bank
				else null
			end as bank_tujuan
		', false)
		->select('
			case
				when pb.jenis_pembayaran = "BG" then pb.keterangan_bg
				else null
			end as keterangan
		', false)
		->from('tr_h3_md_penerimaan_pembayaran as pb')
		->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
		->join('ms_rek_md as rek_bg', 'rek_bg.id_rek_md = pb.id_rekening_md_bg', 'left')
		->join('ms_rek_md as rek_transfer', 'rek_transfer.id_rek_md = pb.id_rekening_md_transfer', 'left')
		;

		$this->db->group_start();
		$this->db->where("left(pb.created_at,10) >= '{$periode_awal}' AND left(pb.created_at,10) <= '{$periode_akhir}'", null, false);
		$this->db->group_end();

		$penerimaan_pembayaran = array_map(function($row){
			$this->db
			->select('pbi.referensi')
			->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
			->join('tr_h3_md_ar_part as ar', 'ar.referensi = pbi.referensi')
			->where('pbi.id_penerimaan_pembayaran', $row['id_penerimaan_pembayaran']);

			if($row['jenis_pembayaran'] == 'Cash'){
				$this->db->select('pbi.jumlah_pembayaran as nominal_cash');
				$this->db->select('0 as nominal_bg');
				$this->db->select('0 as nominal_transfer');
			}else if($row['jenis_pembayaran'] == 'BG'){
				$this->db->select('0 as nominal_cash');
				$this->db->select('pbi.jumlah_pembayaran as nominal_bg');
				$this->db->select('0 as nominal_transfer');
			}else if($row['jenis_pembayaran'] == 'Transfer'){
				$this->db->select('0 as nominal_cash');
				$this->db->select('0 as nominal_bg');
				$this->db->select('pbi.jumlah_pembayaran as nominal_transfer');
			}

			$row['items'] = $this->db->get()->result_array();
			
			return $row;
		}, $this->db->get()->result_array());

		return $penerimaan_pembayaran;
    }
}
