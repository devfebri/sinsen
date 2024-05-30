<?php

defined('BASEPATH') or exit('No direct script access allowed');


class H3_md_ps extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_ps";
	protected $title  = "AHM FILE .PS";

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

		$this->load->model('H3_md_ps_model', 'ps');
		$this->load->model('H3_md_ps_parts_model', 'ps_parts');
		$this->load->model('ms_part_model', 'part');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['ps'] = $this->ps->all();

		$this->template($data);
	}

	public function upload()
	{
		$data['set'] = 'upload_ps';
		$this->template($data);
	}

	public function inject()
	{
		$lines = $this->upload_dan_baca();
		$parsedData = $this->proses_file($lines);

		$this->db->trans_begin();
		$list_packing_sheet_berhasil_diupload = [];
		$ada_part_yang_tidak_terdaftar = false;
		$packing_sheet_baru_dibuat = 0;
		$packing_sheet_pernah_upload = 0;

		$list_packing_sheet_dengan_part_tidak_terdaftar = [];
		foreach ($parsedData as $each) {
			$packing_sheet_dengan_part_tidak_terdaftar = [];
			$part_not_found = $this->check_part_terdaftar($each['parts']);
			if (count($part_not_found) > 0) {
				$ada_part_yang_tidak_terdaftar = true;
				$packing_sheet_dengan_part_tidak_terdaftar['packing_sheet_number'] = $each['master']['packing_sheet_number'];
				$packing_sheet_dengan_part_tidak_terdaftar['parts_tidak_terdaftar'] = $part_not_found;
				$list_packing_sheet_dengan_part_tidak_terdaftar[] = $packing_sheet_dengan_part_tidak_terdaftar;
			}

			$condition = [
				'packing_sheet_number' => $each['master']['packing_sheet_number']
			];

			$packing_sheet = $this->ps->find($each['master']['packing_sheet_number'], 'packing_sheet_number');

			if ($packing_sheet == null) {
				$packing_sheet_baru_dibuat++;
			} else {
				$packing_sheet_pernah_upload++;
			}

			$packing_sheet_record = $this->db
				->from('tr_h3_md_ps as ps')
				->where($condition)
				->limit(1)
				->get()->row_array();

			if ($packing_sheet_record == null) {
				$this->ps->insert($each['master']);
			} else {
				$this->ps->update($each['master'], $condition);
			}
			$this->ps_parts->insert_or_update_batch($each['parts'], $condition);

			$packing_sheet_data = (array) $this->ps->get($condition, true);
			$this->ps->set_jumlah_karton($packing_sheet_data['id']);

			$parts2 = $each['parts'];
			foreach ($parts2 as $part) {
				$part_int = $this->db->select('id_part_int')
					->from('ms_part')
					->where('id_part', $part['id_part'])
					->get()->row_array();
				$this->db->set('qty_intransit', "qty_intransit + {$part['packing_sheet_quantity']}", FALSE);
				$this->db->where('id_part_int', $part_int['id_part_int']);
				$this->db->update('tr_stok_part_summary');

				//Tambah Tgl PS di tabel History Estimasi Waktu 
				$this->db->set('tgl_ps', $each['master']['packing_sheet_date']);
				$this->db->where('id_purchase_order', $part['no_po']);
				$this->db->where('id_part', $part['id_part']);
				$this->db->update('tr_h3_md_history_estimasi_waktu_hotline');

				//Cek apakah kelompok EVBT atau EVCH 
				$cek_part = $this->db
						->select('kelompok_part')
						->from('ms_part as mp')
						->where('mp.id_part', $part['id_part'])
						->get()->row_array();

				if($cek_part['kelompok_part']=='EVBT' || $cek_part['kelompok_part']=='EVCH'){
					$this->db->set('is_ev', 1);
					$this->db->where('packing_sheet_number', $part['packing_sheet_number']);
					$this->db->where('no_po', $part['no_po']);
					$this->db->where('no_doos', $part['no_doos']);
					$this->db->where('id_part', $part['id_part']);
					$this->db->update('tr_h3_md_ps_parts');
				}	
			}
		}

		if ($ada_part_yang_tidak_terdaftar) {
			$this->db->trans_rollback();

			send_json([
				'error_type' => 'part_tidak_terdaftar',
				'errors_payload' => $list_packing_sheet_dengan_part_tidak_terdaftar,
				'message' => 'Data tidak valid : Terdapat Part yang tidak terdaftar di sistem.'
			], 422);
		} else {
			$this->db->trans_commit();
			// send_json([
			// 	'packing_sheet_baru_dibuat' => $packing_sheet_baru_dibuat,
			// 	'packing_sheet_pernah_upload' => $packing_sheet_pernah_upload,
			// ]);
			$this->session->set_userdata('pesan', ".PS File berhasil di upload. {$packing_sheet_baru_dibuat} Packing Sheet Baru dan {$packing_sheet_pernah_upload} yang diperbarui");
			$this->session->set_userdata('tipe', 'info');

			send_json([
				'message' => 'Berhasil upload PS',
				'redirect_url' => base_url('h3/h3_md_ps')
			]);
		}

		// if ($this->db->trans_status()) {
		// 	$this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
		// 	$this->session->set_userdata('tipe', 'info');
		// 	echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page/'>";
		// } else {
		// 	$this->session->set_userdata('pesan', 'Data not found !');
		// 	$this->session->set_userdata('tipe', 'danger');
		// 	echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page'>";
		// }
	}

	public function check_part_terdaftar($data)
	{
		$part_not_found = [];
		foreach ($data as $each_part) {
			$part = $this->part->find($each_part['id_part'], 'id_part');

			if ($part == null) {
				$part_not_found[] = $each_part['id_part'];
			}
		}

		return $part_not_found;
	}

	public function upload_dan_baca()
	{
		$upload_path = "./uploads/AHM";
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = '*';
		$config['overwrite'] = true;

		$this->load->library('upload');
		$this->upload->initialize($config);

		if ($this->upload->do_upload('file')) {
			$data = $this->upload->data();
			$myfile = fopen("$upload_path/{$data['file_name']}", "r");

			$lines = [];
			while ($line = fgets($myfile)) {
				$lines[] = $line;
			}
			return $lines;
		}
	}

	public function proses_file($fdo)
	{
		$registedKey = [];
		$finalData = [];

		$masterKeys = [
			'kode_produk', 'kode_md', 'packing_sheet_date', 'packing_sheet_number', 'no_urut', 'customer_code'
		];
		$partKeys = [
			'packing_sheet_number', 'id_part', 'packing_sheet_quantity', 'qty_order', 'qty_back_order', 'no_doos', 'no_po', 'jenis_po', 'tanggal_po',
		];
		foreach ($fdo as $line) {
			// Lakukan pemecahan berdasarkan panjang karakter yang telah ditentukan.
			$column = $this->parsing($line);

			if (!in_array($column['packing_sheet_number'], $registedKey)) {
				// Hapus key pada array untuk invoice sesuai dengan yang diperlukan saja.
				$master = [];
				foreach ($masterKeys as $value) {
					$master[$value] = trim($column[$value]);
				}
				// Lakukan perulangan untuk mendapatkan parts yang sesuai dengan nomor invoice.
				$parts = [];
				foreach ($fdo as $lineForPart) {
					$columnForPart = $this->parsing($lineForPart);

					if ($master['packing_sheet_number'] == $columnForPart['packing_sheet_number']) {
						$part = [];
						foreach ($partKeys as $value) {
							$part[$value] = trim($columnForPart[$value]);
						}
						$parts[] = $part;
					}
				}

				$registedKey[] = $column['packing_sheet_number'];

				$finalData[] = [
					'master' => $master,
					'parts' => $parts,
				];
			}
		}

		return $finalData;
	}

	public function parsing($line)
	{
		$fieldLength = [
			'kode_produk' => 1,
			'kode_md' => 5,
			'packing_sheet_date' => 8,
			'packing_sheet_number' => 15,
			'no_po' => 30,
			'jenis_po' => 3,
			'tanggal_po' => 8,
			'no_urut' => 5,
			'no_doos' => 15,
			'id_part' => 25,
			'part_deskripsi' => 36,
			'packing_sheet_quantity' => 4,
			'qty_order' => 10,
			'qty_back_order' => 10,
			'customer_code' => 8,
		];

		$column = [];
		$startIndex = 0;
		foreach ($fieldLength as $key => $value) {
			$data = trim(substr($line, $startIndex, $value));
			if (in_array($key, ['packing_sheet_date', 'tanggal_po'])) {
				$date = DateTime::createFromFormat('dmY', $data);
				$column[$key] = $date->format('Y-m-d');
			} else {
				$column[$key] = $data;
			}
			$startIndex += $value;
		}
		return $column;
	}

	public function detail()
	{
		$packing_sheet_number = $this->input->get('packing_sheet_number');

		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['ps'] = $this->db
			->select('ps.*')
			->select('fdo.id as invoice_number_int')
			->select('fdo.invoice_number')
			->select('fdo.invoice_date')
			->from('tr_h3_md_ps as ps')
			->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left')
			->limit(1)
			->where('ps.packing_sheet_number', $packing_sheet_number)
			->get()->row_array();

		$data['parts'] = $this->db
			->select('psp.*')
			->select('psp.id_part')
			->select('p.nama_part')
			->select('psp.no_doos')
			->select('psp.no_po')
			->select('psp.jenis_po')
			->select('date_format(psp.tanggal_po, "%d/%m/%Y") as tanggal_po')
			->select('psp.packing_sheet_quantity')
			->select('psp.qty_order')
			->select('psp.qty_back_order')
			->select('pb.no_penerimaan_barang')
			->select('pb.status as status_penerimaan_barang')
			->from('tr_h3_md_ps_parts as psp')
			->join('ms_part as p', 'p.id_part = psp.id_part', 'left')
			->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.packing_sheet_number_int = psp.packing_sheet_number_int and pbi.id_part_int = psp.id_part_int and pbi.nomor_karton_int = psp.no_doos_int and pbi.no_po = psp.no_po)', 'left')
			->join('tr_h3_md_penerimaan_barang as pb', 'pb.id = pbi.no_penerimaan_barang_int', 'left')
			->where('psp.packing_sheet_number', $packing_sheet_number)
			->get()->result_array();

		//Check jumlah karton di tabel ps_part 
		$data['jumlah_karton_ps'] = $this->db->select('count(psp.no_doos) as no_doos')
			->from('tr_h3_md_ps_parts as psp')
			->join('tr_h3_md_nomor_karton as nk', 'psp.no_doos_int=nk.id')
			->where('psp.packing_sheet_number', $packing_sheet_number)
			->get()->row_array();

		//List No Karton di Packing Sheet 
		$list_no_karton = $this->db->select('psp.no_doos')
			->from('tr_h3_md_ps_parts as psp')
			->where('psp.packing_sheet_number', $packing_sheet_number)
			->get()->result_array();

		$output_list_karton = array_column($list_no_karton, 'no_doos');


		//Check jumlah item karton yang diupload
		$data['jumlah_item_karton'] = $this->db->select('sum(jumlah_item) as jumlah_item')
			->from('tr_h3_md_nomor_karton as nk')
			->where_in('nk.nomor_karton', $output_list_karton)
			->get()->row_array();

		if ($data['jumlah_karton_ps']['no_doos'] != $data['jumlah_item_karton']['jumlah_item']) {
			$notFound = [];
			//Check per item 
			$data['item_karton'] = $this->db->select('nomor_karton')
				->from('tr_h3_md_nomor_karton as nk')
				->where_in('nk.nomor_karton', $output_list_karton)
				->get()->result_array();

			foreach ($data['item_karton'] as $item) {
				$jumlah_karton_ps_per_karton = $this->db->select('count(psp.no_doos) as no_doos')
					->from('tr_h3_md_ps_parts as psp')
					->join('tr_h3_md_nomor_karton as nk', 'psp.no_doos_int=nk.id')
					->where('psp.no_doos', $item['nomor_karton'])
					->get()->row_array();

				$jumlah_item_karton_per_karton = $this->db->select('sum(jumlah_item) as nomor_karton')
					->from('tr_h3_md_nomor_karton as nk')
					->where('nk.nomor_karton', $item['nomor_karton'])
					->get()->row_array();

				if ($jumlah_karton_ps_per_karton['no_doos'] != $jumlah_item_karton_per_karton['nomor_karton']) {
					$notFound[] = $item['nomor_karton'];
				}
			}
			$data['notFound'] = $notFound;
		}
		$this->template($data);
	}
}
