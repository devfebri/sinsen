<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_rekap_insentif_part extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_rekap_insentif_part";
	protected $title  = "Rekap Insentif Part";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('h3_md_penagihan_pihak_kedua_model', 'penagihan_pihak_kedua');
		$this->load->model('h3_md_penagihan_pihak_kedua_tujuan_model', 'penagihan_pihak_kedua_tujuan');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function download_excel()
	{
		$jumlah_faktur = $this->db
			->select('COUNT(bapi.no_faktur) as no_faktur', false)
			->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi')
			->where('bapi.no_bap = bap.no_bap', null, false)
			->get_compiled_select();

		$jumlah_faktur_dikembalikan = $this->db
			->select('COUNT(bapi.no_faktur) as no_faktur', false)
			->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi')
			->where('bapi.no_bap = bap.no_bap', null, false)
			->where('bapi.dikembalikan', 1)
			->get_compiled_select();

		$nominal_dikembalikan = $this->db
			->select('SUM( IFNULL(bapi.cash, 0) + IFNULL(bapi.transfer, 0) + IFNULL(bapi.amount_bg, 0) ) as nominal_dikembalikan', false)
			->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi')
			->where('bapi.no_bap = bap.no_bap', null, false)
			->where('bapi.dikembalikan', 1)
			->get_compiled_select();

		$data = $this->db
			->select('bap.created_at')
			->select("IFNULL(({$jumlah_faktur}), 0) AS jumlah_faktur")
			->select("IFNULL(({$jumlah_faktur_dikembalikan}), 0) AS jumlah_faktur_dikembalikan")
			->select("IFNULL(({$nominal_dikembalikan}), 0) AS nominal_dikembalikan")
			->from('tr_h3_md_berita_acara_penyerahan_faktur as bap')
			->group_start()
			->where(
				sprintf('bap.created_at between "%s" AND "%s"', $this->input->get('periode_filter_start'), $this->input->get('periode_filter_end')),
				null,
				false
			)
			->group_end()
			->where('bap.id_debt_collector', $this->input->get('id_collector'))
			->get()->result_array();

		$path = 'assets/template/rekap_insentif_part_template.xlsx';
		$excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

		$excel->setActiveSheetIndex(0)->setCellValue('C3', 'Collector');

		$data_start_row = 5;
		$border_all = [
			'borders' => array(
				'outline' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			)
		];
		$total_jumlah_faktur = 0;
		$total_realisasi_faktur = 0;
		$total_nominal_rupiah = 0;
		foreach ($data as $row) {
			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $data_start_row), date('d/m/y', strtotime($row['created_at'])));
			$excel->getActiveSheet()->getStyle(sprintf('A%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $data_start_row), $row['jumlah_faktur']);
			$excel->setActiveSheetIndex(0)->mergeCells(sprintf('B%s:C%s', $data_start_row, $data_start_row));
			$excel->getActiveSheet()->getStyle(sprintf('B%s:C%s', $data_start_row, $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $data_start_row), $row['jumlah_faktur_dikembalikan']);
			$excel->getActiveSheet()->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($border_all);

			$excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $data_start_row), $row['nominal_dikembalikan']);
			$excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($border_all);

			$excel->getActiveSheet()->getStyle(sprintf('F%s', $data_start_row))->applyFromArray($border_all);
			$excel->getActiveSheet()->getStyle(sprintf('G%s', $data_start_row))->applyFromArray($border_all);

			$total_jumlah_faktur += intval($row['jumlah_faktur']);
			$total_realisasi_faktur += intval($row['jumlah_faktur_dikembalikan']);
			$total_nominal_rupiah += floatval($row['nominal_dikembalikan']);
			$data_start_row++;
		}
		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $data_start_row), 'Total');
		$excel->getActiveSheet()->getStyle(sprintf('A%s', $data_start_row))->applyFromArray($border_all);

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('B%s', $data_start_row), $total_jumlah_faktur);
		$excel->setActiveSheetIndex(0)->mergeCells(sprintf('B%s:C%s', $data_start_row, $data_start_row));
		$excel->getActiveSheet()->getStyle(sprintf('B%s:C%s', $data_start_row, $data_start_row))->applyFromArray($border_all);

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('D%s', $data_start_row), $total_realisasi_faktur);
		$excel->getActiveSheet()->getStyle(sprintf('D%s', $data_start_row))->applyFromArray($border_all);

		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('E%s', $data_start_row), $total_nominal_rupiah);
		$excel->getActiveSheet()->getStyle(sprintf('E%s', $data_start_row))->applyFromArray($border_all);

		$label_ttd_row = $data_start_row + 2;
		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $label_ttd_row), 'Disetujui Oleh');
		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $label_ttd_row), 'Yang Menyusun');

		$nama_ttd_row = $label_ttd_row + 4;
		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('A%s', $nama_ttd_row), 'Pimpinan');
		$excel->setActiveSheetIndex(0)->setCellValue(sprintf('F%s', $nama_ttd_row), 'SH Sparepart & Oli');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
		ob_end_clean();
		$filename = 'Rekap insentif part';

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output'); // download file 
	}
}
