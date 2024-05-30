<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_proses_barang_bagi extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_proses_barang_bagi";
    protected $title  = "Proses Barang Bagi";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_proses_barang_bagi_model', 'proses_barang_bagi');		
		$this->load->model('H3_md_proses_barang_bagi_items_model', 'proses_barang_bagi_items');		
		$this->load->model('H3_md_proses_barang_bagi_kelompok_parts_model', 'proses_barang_bagi_kelompok_parts');		
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		//===== Load Library =====
		$this->load->library('form_validation');
		$this->load->library('upload');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

	}

	public function index()
	{				
		$data['set']	= "index";
		$this->template($data);	
	}

	public function add(){				
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$data['kelompok_parts'] = $this->db
		->select('kp.id_kelompok_part')
		->select('1 as checked')
		->from('ms_kelompok_part as kp')
		->where('kp.kelompok_part !=','FED OIL')
		->get()->result();

		$data['setting_persentase'] = $this->get_persentase_pembagian();

		$this->template($data);	
	}

	public function generate_so(){
		$sales_order_sudah_terbuat_do = $this->db
		->select('dso.id_sales_order')
		->from('tr_h3_md_do_sales_order as dso')
		->get_compiled_select();

		$this->db
		->select('so.id_sales_order')
		->select('(d.h1 = 0 and d.h2 = 0 and d.h3 = 1) as toko')
		->select('!(d.h1 = 0 and d.h2 = 0 and d.h3 = 1) as dealer')
		->select('so.back_order')
		->select('so.po_type')
		->select('so.kategori_po')
		->select('so.id_dealer')
		->select('so.tanggal_order')
		->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_order_formatted')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('kab.id_kabupaten')
		->select('kab.kabupaten')
		->select('
			concat(
				"Rp ",
				format(so.total_amount, 0, "ID_id")
			) as total_amount
		')
		->select('1 as check')
		->from('tr_h3_md_sales_order as so')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
		->group_start()
		->where('so.po_type', 'FIX')
		->or_where('so.po_type', 'REG')
		->group_end()
		->where('so.status', 'New SO')
		;

		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		if($start_date != null AND $end_date != null){
			$this->db->where("so.tanggal_order BETWEEN '{$start_date}' AND '{$end_date}'", null, false);
		}

		if($this->input->get('kategori') != null){
			$this->db->where('so.kategori_po', $this->input->get('kategori'));
		}

		$sales_orders = array_map(function($sales_order){
			$sales_order['parts'] = $this->db
			->select('p.id_part_int')
			->select('sop.id_part')
			->select('kp.kelompok_part')
			->select('sop.qty_pemenuhan as qty')
			->select('kp.keep_stock_toko')
			->select('kp.keep_stock_dealer')
			->select('kp.keep_stock_dealer_fix')
			->from('tr_h3_md_sales_order_parts as sop')
			->join('ms_part as p', 'p.id_part = sop.id_part')
			->join('ms_kelompok_part as kp', 'kp.kelompok_part = p.kelompok_part')
			->where('sop.id_sales_order', $sales_order['id_sales_order']);

			$parts = [];
			foreach($this->db->get()->result_array() as $part){
				$qty_avs = $this->stock_int->qty_avs($part['id_part_int']);
				if($sales_order['toko'] == 1){
					$keep_stock_toko = intval($part['keep_stock_toko']);
					if($keep_stock_toko != 0){
						$kuantitas = ($keep_stock_toko/100) * $qty_avs;
						$part['qty_avs'] = $kuantitas;
					}else{
						$part['qty_avs'] = 0;
					}
				}else if($sales_order['dealer'] == 1 AND $sales_order['po_type'] != 'FIX'){
					$keep_stock_dealer = intval($part['keep_stock_dealer']);
					if($keep_stock_dealer != 0){
						$kuantitas = ($keep_stock_dealer/100) * $qty_avs;
						$part['qty_avs'] = $kuantitas;
					}else{
						$part['qty_avs'] = 0;
					}
				}else if($sales_order['dealer'] == 1 AND $sales_order['po_type'] == 'FIX'){
					$keep_stock_dealer_fix = intval($part['keep_stock_dealer_fix']);
					if($keep_stock_dealer_fix != 0){
						$kuantitas = ($keep_stock_dealer_fix/100) * $qty_avs;
						$part['qty_avs'] = $kuantitas;
					}else{
						$part['qty_avs'] = 0;
					}
				}
				$parts[] = $part;
			}

			$sales_order['parts'] = $parts;

			return $sales_order;
		}, $this->db->get()->result_array());

		send_json($sales_orders);
	}

	public function get_kebutuhan_parts(){
		$id_sales_order = $this->input->post('id_sales_order');

		if(count($id_sales_order) < 1) return [];

		$this->db
		->select('sop.id_part')
		->select('SUM(sop.qty_pemenuhan) as kuantitas', false)
		->from('tr_h3_md_sales_order_parts as sop')
		->where_in('sop.id_sales_order', $id_sales_order)
		->group_by('sop.id_part')
		;

		$parts = array_map(function($row){
			$row['qty_avs'] = $this->stock->qty_avs($row['id_part']);
			return $row;
		}, $this->db->get()->result_array());

		send_json($parts);
	}

	public function save(){		
		$this->db->trans_begin();

		try {
			$this->validate();
			$data = array_merge($this->input->post([
				'kategori','start_date','end_date','fix',
				'reguler','hotline','urgent','umum',
			]), [
				'status' => 'Draft',
				'id_proses_barang_bagi' => $this->proses_barang_bagi->generateID()
			]);
			$this->proses_barang_bagi->insert($data);
			$id = $this->db->insert_id();

			$kelompok_parts = $this->getOnly(['id_kelompok_part'], $this->input->post('kelompok_parts'), [
				'id_proses_barang_bagi' => $id
			]);
			$this->proses_barang_bagi_kelompok_parts->insert_batch($kelompok_parts);

			$items = $this->getOnly(['id_sales_order'], $this->input->post('items'), [
				'id_proses_barang_bagi' => $id
			]);
			$this->proses_barang_bagi_items->insert_batch($items);
			$this->proses($id);
			$this->proses($id, true);

			if ($this->input->post('simpan_persentase_pembagian') == 1) {
				$this->set_persentase_pembagian();
			}

			$this->db->trans_commit();

			$message = 'Berhasil menyimpan proses barang bagi';
			$this->session->set_userdata('pesan', $message);
			$this->session->set_userdata('tipe', 'info');

			$proses_barang_bagi = $this->proses_barang_bagi->find($id);
			send_json([
				'message' => $message,
				'payload' => $proses_barang_bagi,
				'redirect_url' => base_url(sprintf('h3/h3_md_proses_barang_bagi/detail?id=%s', $id))
			]);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('debug', $e);

			send_json([
				'message' => 'Tidak berhasil menyimpan proses barang bagi'
			], 422);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['proses_barang_bagi'] = $this->db
		->from('tr_h3_proses_barang_bagi as pbb')
		->where('pbb.id', $this->input->get('id'))
		->get()->row();

		$data['sales_orders'] = $this->db
		->select('so.id_sales_order')
		->select('so.back_order')
		->select('so.po_type')
		->select('
			concat(
				"Rp ",
				format(so.total_amount, 0, "ID_id")
			) as total_amount
		')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_order_formatted')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('kab.id_kabupaten')
		->select('kab.kabupaten')
		->from('tr_h3_proses_barang_bagi_items as pbbi')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = pbbi.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
		->where('pbbi.id_proses_barang_bagi', $this->input->get('id'))
		->get()->result();

		$data['kelompok_parts'] = $this->db
		->from('tr_h3_proses_barang_bagi_kelompok_parts as pbbkp')
		->where('pbbkp.id_proses_barang_bagi', $this->input->get('id'))
		->get()->result();

		$this->template($data);	
	}

	public function proses($id, $toko = false){
		$persentase_pembagian = $this->db
		->select('pbb.fix')
		->select('pbb.reguler')
		->select('pbb.urgent')
		->select('pbb.hotline')
		->from('tr_h3_proses_barang_bagi as pbb')
		->where('pbb.id', $id)
		->get()->row_array();

		$kelompok_parts = $this->db
		->select('pbbkp.id_kelompok_part')
		->from('tr_h3_proses_barang_bagi_kelompok_parts as pbbkp')
		->where('pbbkp.id_proses_barang_bagi', $id)
		->get()->result_array();
		$kelompok_parts = array_column($kelompok_parts, 'id_kelompok_part');

		$this->db
		->select('pbbi.id_sales_order')
		->select('d.h1')
		->select('d.h2')
		->select('d.h3')
		->from('tr_h3_proses_barang_bagi_items as pbbi')
		->join('tr_h3_md_sales_order as so', 'pbbi.id_sales_order = so.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('pbbi.id_proses_barang_bagi', $id);

		if($toko){
			$this->db->where('(d.h1 = 0 and d.h2 = 0 and d.h3 = 1)', null, false);
		}else{
			$this->db->where('!(d.h1 = 0 and d.h2 = 0 and d.h3 = 1)', null, false);
		}
		$id_sales_orders = array_column($this->db->get()->result_array(), 'id_sales_order');

		foreach ($persentase_pembagian as $key_persentase_pembagian => $value_persentase_pembagian) {
			if($key_persentase_pembagian == 'reguler'){
				$key_persentase_pembagian = 'REG';
			}else if($key_persentase_pembagian == 'urgent'){
				$key_persentase_pembagian = 'URG';
			}else if($key_persentase_pembagian == 'hotline'){
				$key_persentase_pembagian = 'HLO';
			}else if($key_persentase_pembagian == 'fix'){
				$key_persentase_pembagian = 'FIX';
			}

			log_message('debug', sprintf('Melakukan proses bagi tipe %s [%s]', $key_persentase_pembagian, $id));

			foreach ($id_sales_orders as $id_sales_order) {
				log_message('debug', sprintf('Mencari parts untuk DO %s [%s]', $id_sales_order, $id));

				$part_sudah_di_do =  $this->db
				->select('sum(dop.qty_supply)')
				->from('tr_h3_md_do_sales_order as do')
				->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
				->where('do.id_sales_order = sop.id_sales_order')
				->where('dop.id_part = sop.id_part')
				->get_compiled_select();

				$this->db
				->select('sop.id_sales_order')
				->select('sop.id_part')
				->select('sop.id_part_int')
				->select('so.po_type')
				// ->select('p.id_part_int')
				->select('p.kelompok_part')
				->select('(d.h1 = 0 and d.h2 = 0 and d.h3 = 1) as toko')
				->select('!(d.h1 = 0 and d.h2 = 0 and d.h3 = 1) as dealer')
				->select("sop.qty_pemenuhan - IFNULL(({$part_sudah_di_do}), 0) as qty")
				->select('kp.keep_stock_toko')
				->select('kp.keep_stock_dealer')
				->select('kp.keep_stock_dealer_fix')
				->from('tr_h3_md_sales_order_parts as sop')
				->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
				->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
				->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
				->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
				->where('sop.id_sales_order', $id_sales_order)
				->where_in('p.kelompok_part', $kelompok_parts)
				->where('so.po_type', $key_persentase_pembagian)
				->where('kp.proses_barang_bagi', 1);

				foreach ($this->db->get()->result_array() as $sales_order_part) {
					log_message('debug', print_r($sales_order_part, true));

					$qty_avs = $this->stock_int->qty_avs($sales_order_part['id_part_int']);
					log_message('debug', sprintf('Kuantitas AVS untuk awal kode part %s adalah %s', $sales_order_part['id_part'], $qty_avs));
					
					//Check terlebih dahulu apakah qty avs melebihi qty total permintaan 
					$total_request_part_yang_sama_awal = $this->db
						->select('sum(sop.qty_pemenuhan) as qty_pemenuhan')
						->from('tr_h3_md_sales_order_parts as sop')
						->where_in('sop.id_sales_order', $id_sales_orders)
						->where('sop.id_part_int', $sales_order_part['id_part_int'])
						->get()->row_array();

					if ($qty_avs >= $total_request_part_yang_sama_awal['qty_pemenuhan']) {
						$pembagian_stock_sales_order_parts = $this->db
							->select('sop.id_sales_order')
							->select('sop.id_part')
							->select('sop.id_part_int')
							->select('SUM(sop.qty_pemenuhan) as qty')
							->from('tr_h3_md_sales_order_parts as sop')
							->where_in('sop.id_sales_order', $id_sales_orders)
							->where('sop.id_part_int', $sales_order_part['id_part_int'])
							->group_by('sop.id_part')
							->group_by('sop.id_sales_order')
							->get()->result_array();

						log_message('debug', 'Pembagian stock sales order parts');
						log_message('debug', print_r($pembagian_stock_sales_order_parts, true));

						foreach ($pembagian_stock_sales_order_parts as $row) {
							$this->db
								->set('sop.qty_suggest', $row['qty'])
								->where('sop.id_part_int', $row['id_part_int'])
								->where('sop.id_sales_order', $row['id_sales_order'])
								->update('tr_h3_md_sales_order_parts as sop');

							log_message('debug', sprintf('Proses barang bagi pada nomor so %s untuk kode part %s dengan kuantitas so %s didapatkan suggest pemenuhan sebesar %s', $row['id_sales_order'], $row['id_part_int'], $row['qty'], $row['qty']));
						}
					}else{
						if($sales_order_part['toko'] == 1){
							$keep_stock_toko = intval($sales_order_part['keep_stock_toko']);
							if($keep_stock_toko != 0){
								$kuantitas = ($keep_stock_toko/100) * $qty_avs;
								$qty_avs = $kuantitas;
							}else{
								// $qty_avs = 0;
								$qty_avs = $qty_avs;
							}
						}else if($sales_order_part['dealer'] == 1 AND $sales_order_part['po_type'] != 'FIX'){
							$keep_stock_dealer = intval($sales_order_part['keep_stock_dealer']);
							if($keep_stock_dealer != 0){
								$kuantitas = ($keep_stock_dealer/100) * $qty_avs;
								$qty_avs = $kuantitas;
							}else{
								// $qty_avs = 0;
								$qty_avs = $qty_avs;
							}
						}else if($sales_order_part['dealer'] == 1 AND $sales_order_part['po_type'] == 'FIX'){
							$keep_stock_dealer_fix = intval($sales_order_part['keep_stock_dealer_fix']);
							if($keep_stock_dealer_fix != 0){
								$kuantitas = ($keep_stock_dealer_fix/100) * $qty_avs;
								$qty_avs = $kuantitas;
							}else{
								// $qty_avs = 0;
								$qty_avs = $qty_avs;
							}
						}
	
						$qty_avs = round($qty_avs);
	
						log_message('debug', sprintf('Kuantitas AVS untuk kode part %s adalah %s', $sales_order_part['id_sales_order'], $qty_avs));
						// $stock_proses_bagi = floor( ($value_persentase_pembagian / 100) * $qty_avs );
	
						if($value_persentase_pembagian != 0){
							// $stock_proses_bagi = floor( ($value_persentase_pembagian / 100) * $qty_avs );
							$stock_proses_bagi = round( ($value_persentase_pembagian / 100) * $qty_avs );
						}else{
							$stock_proses_bagi = $qty_avs;
						}
	
						log_message('debug', sprintf('Stock proses barang bagi untuk kode part %s adalah %s [%s persen dari %s]', $sales_order_part['id_part'], $stock_proses_bagi, $value_persentase_pembagian, $qty_avs));
	
						$total_request_part_yang_sama = $this->db
							->select('sum(sop.qty_pemenuhan)')
							->from('tr_h3_md_sales_order_parts as sop')
							->where_in('sop.id_sales_order', $id_sales_orders)
							->where('sop.id_part_int', $sales_order_part['id_part_int'])
							->get_compiled_select();
	
						$pembagian_stock_sales_order_parts = $this->db
							->select('sop.id_sales_order')
							->select('sop.id_part')
							->select('sop.id_part_int')
							->select('SUM(sop.qty_pemenuhan) as qty')
							->select("
							round
							(
								(
									(SUM(sop.qty_pemenuhan) / IFNULL(({$total_request_part_yang_sama}), 0)) * ({$stock_proses_bagi}) 
								)
							)
							as qty_barang_bagi")
							->from('tr_h3_md_sales_order_parts as sop')
							->where_in('sop.id_sales_order', $id_sales_orders)
							->where('sop.id_part_int', $sales_order_part['id_part_int'])
							->group_by('sop.id_part')
							->group_by('sop.id_sales_order')
							->get()->result_array();
	
						log_message('debug', 'Pembagian stock sales order parts');
						log_message('debug', print_r($pembagian_stock_sales_order_parts, true));
	
						foreach ($pembagian_stock_sales_order_parts as $row) {
							$this->db
							->set('sop.qty_suggest', $row['qty_barang_bagi'])
							->where('sop.id_part_int', $row['id_part_int'])
							->where('sop.id_sales_order', $row['id_sales_order'])
							->update('tr_h3_md_sales_order_parts as sop');
	
							log_message('debug', sprintf('Proses barang bagi pada nomor so %s untuk kode part %s dengan kuantitas so %s didapatkan suggest pemenuhan sebesar %s', $row['id_sales_order'], $row['id_part'], $row['qty'], $row['qty_barang_bagi']));
						}
					}
				}

				$this->sales_order->update(['status' => 'Barang Bagi'], [
					'id_sales_order' => $id_sales_order
				]);
			}
		}
	}

	public function get_persentase_pembagian(){
		$setting = $this->db
		->from('setting_md_h3_persentase_proses_barang_bagi as sp')
		->limit(1)
		->get()->row_array();

		if($setting != null){
			return $setting;
		}

		return [
			'fix' => 0,
			'reguler' => 0,
			'hotline' => 0,
			'urgent' => 0,
			'umum' => 0
		];
	}

	public function set_persentase_pembagian(){
		$setting = $this->db->limit(1)->get('setting_md_h3_persentase_proses_barang_bagi')->row();

		if($setting == null){
			$this->db->insert('setting_md_h3_persentase_proses_barang_bagi', $this->input->post(['fix','reguler','hotline','urgent','umum',]));
		}else{
			$this->db->update('setting_md_h3_persentase_proses_barang_bagi', $this->input->post(['fix','reguler','hotline','urgent','umum',]));
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('start_date', 'Periode', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			]);
		}
    }
}