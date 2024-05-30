<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_rekap_po_bundling extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_rekap_po_bundling";
	protected $title  = "Rekap PO Bundling";

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
		if ($name == "" OR $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('H3_md_rekap_po_bundling_model', 'rekap_po_bundling');
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
		// $this->load->model('H3_md_rekap_purchase_order_dealer_item_model', 'rekap_purchase_order_dealer_item');
		// $this->load->model('H3_md_rekap_purchase_order_dealer_parts_model', 'rekap_purchase_order_dealer_parts');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add()
	{
		$data['mode'] = 'insert';
		$data['set'] = "form";

		$this->template($data);
	}

	public function id_no_po_aksesoris(){		
		$tgl 						= date("Y-m");
		$th 						= date("Y");
		$bln 						= date("m");		

		$get_data  = $this->db->query("SELECT * FROM tr_po_aksesoris WHERE LEFT(created_at,7) = '$tgl' ORDER BY no_po_aksesoris DESC LIMIT 0,1");

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $id_po = substr($row->id_po, -5);
            $new_kode   = $th."-".$bln.'/POPP/'. sprintf("%'.05d", $id_po + 1);
            $i = 0;
            while ($i < 1) {
                $cek = $this->db->get_where('tr_po_aksesoris', ['no_po_aksesoris' => $new_kode])->num_rows();
                if ($cek > 0) {
                    $gen_number    = substr($new_kode, -5);
                    $new_kode =$th."-".$bln.'/POPP/'. sprintf("%'.05d", $gen_number + 1);
                    $i = 0;
                } else {
                    $i++;
                }
            }
        } else {
            $new_kode   = $th."-".$bln.'/POPP/'. '00001';
        }
        return strtoupper($new_kode);
	}

	public function save2(){
		// $this->validate();

		$this->db->trans_begin();
		// $data = $this->input->post(['id_dealer', 'tipe_po']);
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		// $this->rekap_po_bundling->insert($data);

		$this->db->insert('tr_h3_md_rekap_po_bundling', $data);
		$id_rekap = $this->db->insert_id();
		$items = $this->getOnly(['id_referensi'], $this->input->post('items'));

		$parts = $this->input->post('parts');

	
		foreach($items as $item){
			$data_detail = array(
				'id_rekap' => $id_rekap,
				'id_referensi' => $item['id_referensi']
			);
			
			$this->db->insert('tr_h3_md_rekap_po_bundling_detail', $data_detail);

		}

		$total = 0;
		foreach($parts as $part){
			$id_part_int = $this->db->select('id_part_int')
									->from('ms_part')
									->where('id_part',$part['id_part'])
									->get()->row_array();

			//Cek harga setelah diskon pada tabel PO Aksesoris Bundling
			$harga = $this->db->select('pod.harga')
							  ->from('tr_po_aksesoris_detail as pod')
							  ->where('no_po_aksesoris',$part['no_po_aksesoris'])
							  ->where('id_part', $part['id_part'])
							  ->get()->row_array();

			$diskon = $this->diskon_part_tertentu->get_diskon($part['id_part'], 707, 'REG');
			$harga_setelah_diskon = $harga['harga'];
			$part['tipe_diskon'] = '';
			$part['diskon'] = 0;

			if ($diskon != null) {
			  $part['tipe_diskon'] = $diskon['tipe_diskon'];
			  $part['diskon_value'] = $diskon['diskon_value'];

			  if($part['tipe_diskon'] == 'Rupiah'){
				$harga_setelah_diskon = $harga['harga'] - $part['diskon_value'];
			  }else if($part['tipe_diskon'] == 'Persen'){ 
				$diskon_2 = ($part['diskon_value'] / 100)* $harga['harga'];
				$harga_setelah_diskon = $harga['harga'] - $diskon_2;
			  }

			  if($harga_setelah_diskon < 0){
				$harga_setelah_diskon = 0;
			  }
			}

			$harga_setelah_diskon = round($harga_setelah_diskon);

			$sub_total = $harga_setelah_diskon*$part['kuantitas'];

			$data_part = array(
				'id_rekap' => $id_rekap,
				'id_referensi' => $part['no_po_aksesoris'],
				'id_part' => $part['id_part'],
				'id_part_int' => $id_part_int['id_part_int'],
				'kuantitas' => $part['kuantitas'],
				'harga' => $harga['harga'],
				'harga_setelah_diskon' => $harga_setelah_diskon,
				'tipe_diskon' => $part['tipe_diskon'],
				'diskon_value' => $part['diskon_value'],
				'subtotal' => $sub_total
			);
			
			$this->db->insert('tr_h3_md_rekap_po_bundling_part', $data_part);

			$total += $sub_total;
		}

		$this->db->set('total', $total);
		$this->db->where('id', $id_rekap);
		$this->db->update('tr_h3_md_rekap_po_bundling');


		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE){
			send_json([
				'message' => 'Berhasil simpan rekap PO Bundling',
				'payload' => $id_rekap,
				'redirect_url' => base_url('h3/h3_md_rekap_po_bundling/detail?id=' . $id_rekap)
			]);
		}else{
			$this->db->trans_rollback();
		  	// $this->output->set_status_header(500);
			  send_json([
				'error_type' => 'validation_error',
				'message' => 'Tidak berhasil simpan rekap PO Bundling'
			], 422);
		}
	}

	public function save(){
	 $this->db->trans_begin();
		$purchase_order = array(
			'po_id' => $this->purchase_order->generatePONumber('REG', 707),
			'id_dealer' => 707,
			'kategori_po' => 'Bundling H1',
			'id_salesman' => '',
			'po_type' => 'REG',
			'produk' => 'Acc',
			'gimmick' => 0,
			'gimmick_tidak_langsung' => 0,
			'tanggal_order' => date('Y-m-d'),
			'created_at' => date('Y-m-d H:i:s', time()),
			'approve_at' => date('Y-m-d H:i:s', time()),
			'approve_by' => $this->session->userdata('id_user'),
			'submit_at' => date('Y-m-d H:i:s', time()),
			'submit_by' => $this->session->userdata('id_user'),
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user'),
			'created_by_md' => 1,
			'po_md' => 1,
			'status' => 'Processed by MD'
		);

		$this->purchase_order->insert($purchase_order);

		$po_id_int = $this->db->select('id')
							  ->from('tr_h3_dealer_purchase_order')
							  ->where('po_id', $purchase_order['po_id'])
							  ->get()
							  ->row_array();

		$parts = $this->input->post('parts');
		$total = 0;
		foreach($parts as $part){
			//Cek apakah sebelumnya part sudah ada di no PO tersebut 
			$cek_part = $this->db->select('id_part')
								->select('kuantitas')
								->select('harga_setelah_diskon')
								->from('tr_h3_dealer_purchase_order_parts')
								->where('po_id',$purchase_order['po_id'])
								->where('id_part', $part['id_part'])
								->get()->row_array();
			

			if(isset($cek_part)){

				$sub_total = $cek_part['harga_setelah_diskon']*$part['kuantitas'];

				$this->db->set('kuantitas', $cek_part['kuantitas']+$part['kuantitas']);
				$this->db->set('tot_harga_part', $sub_total+$cek_part['harga_setelah_diskon']);
				$this->db->where('po_id', $purchase_order['po_id']);
				$this->db->where('id_part', $part['id_part']);
				$this->db->update('tr_h3_dealer_purchase_order_parts');
			}else{
				$id_part_int = $this->db->select('id_part_int')
				->from('ms_part')
				->where('id_part',$part['id_part'])
				->get()->row_array();

				//Cek harga setelah diskon pada tabel PO Aksesoris Bundling
				$harga = $this->db->select('pod.harga')
						->from('tr_po_aksesoris_detail as pod')
						->where('no_po_aksesoris',$part['no_po_aksesoris'])
						->where('id_part', $part['id_part'])
						->get()->row_array();

				$diskon = $this->diskon_part_tertentu->get_diskon($part['id_part'], 707, 'REG');
				$harga_setelah_diskon = $harga['harga'];
				$part['tipe_diskon'] = '';
				$part['diskon'] = 0;

				if ($diskon != null) {
					$part['tipe_diskon'] = $diskon['tipe_diskon'];
					$part['diskon_value'] = $diskon['diskon_value'];

					if($part['tipe_diskon'] == 'Rupiah'){
						$harga_setelah_diskon = $harga['harga'] - $part['diskon_value'];
					}else if($part['tipe_diskon'] == 'Persen'){ 
						$diskon_2 = ($part['diskon_value'] / 100)* $harga['harga'];
						$harga_setelah_diskon = $harga['harga'] - $diskon_2;
					}

					if($harga_setelah_diskon < 0){
						$harga_setelah_diskon = 0;
					}
				}

				$harga_setelah_diskon = round($harga_setelah_diskon);

				$sub_total = $harga_setelah_diskon*$part['kuantitas'];

				$purchase_order_parts = array(
					'po_id_int' => $po_id_int['id'],
					'po_id' => $purchase_order['po_id'],
					'id_part_int' => $id_part_int['id_part_int'],
					'id_part' => $part['id_part'],
					'kuantitas' => $part['kuantitas'],
					'harga_saat_dibeli' => $harga['harga'],
					'harga_setelah_diskon' => $harga_setelah_diskon,
					'tipe_diskon' => $part['tipe_diskon'],
					'diskon_value' => $part['diskon_value'],
					'tot_harga_part' => $sub_total
				);

				$this->db->insert('tr_h3_dealer_purchase_order_parts', $purchase_order_parts);

				// Insert ke Order Part Tracking
				$order_part_tracking = array(
					'po_id' => $purchase_order['po_id'],
					'po_id_int' => $po_id_int['id'],
					'id_part_int' => $id_part_int['id_part_int'],
					'id_part' => $part['id_part'],
					'created_at' => date('Y-m-d H:i:s', time()),
					'created_by' => $this->session->userdata('id_user')
				);

				$this->db->insert('tr_h3_dealer_order_parts_tracking', $order_part_tracking);
			}
			$total += $sub_total;
		}

		$this->db->set('total_amount', $total);
		$this->db->where('id', $po_id_int['id']);
		$this->db->update('tr_h3_dealer_purchase_order');

		//update data rekap dan no po di tabel tr_po_aksesoris
		$items = $this->getOnly(['id_referensi'], $this->input->post('items'));
		foreach($items as $item){
			$data_po_aksesoris = array(
				'is_rekap' => 1,
				'po_id_h3' => $purchase_order['po_id']
			);
		
			$this->db->where('no_po_aksesoris', $item['id_referensi']);
			$this->db->update('tr_po_aksesoris', $data_po_aksesoris);
		}

		
		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE){
			send_json([
				'message' => 'Berhasil simpan rekap PO Bundling',
				'payload' => $purchase_order['po_id'],
				'redirect_url' => base_url('h3/h3_md_rekap_po_bundling/detail?id=' .$purchase_order['po_id'])
			]);
		}else{
			$this->db->trans_rollback();
		  	// $this->output->set_status_header(500);
			  send_json([
				'error_type' => 'validation_error',
				'message' => 'Tidak berhasil simpan rekap PO Bundling'
			], 422);
		}
	}

	public function get_parts(){
		$this->db
		->select('pod.no_po_aksesoris')
		->select('pod.id_part')
		->select('p.nama_part')
		->select('(pod.qty-ifnull(pod.pemenuhan,0)) as kuantitas')
		->from('tr_po_aksesoris_detail as pod')
		->join('ms_part as p', 'p.id_part = pod.id_part')
		->where('pod.qty != ifnull(pod.pemenuhan,0)')
		;

		if($this->input->post('items') != null && count($this->input->post('items')) > 0){
			$this->db->where_in('pod.no_po_aksesoris', $this->input->post('items'));
		}else{
			send_json([]);
		}

		send_json($this->db->get()->result_array());
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['rekap'] = $this->db
		->select('tpa.po_id_h3 ,GROUP_CONCAT(tpa.no_po_aksesoris SEPARATOR ",") as no_po_aksesoris, (CASE WHEN so.id_sales_order is null then "" else so.id_sales_order end) as id_sales_order, (CASE WHEN so.status = "Canceled" then "Canceled" WHEN so.status is null then "" ELSE "On Process" END) as status_so')
		->from('tr_po_aksesoris tpa')
		->join('tr_h3_md_sales_order so','so.id_ref=tpa.po_id_h3','left')
		->where('tpa.po_id_h3', $this->input->get('id'))
		->group_by('tpa.po_id_h3')
		// ->limit(1)
		->get()->row();

		$data['items'] = $this->db
		->select('tpa.po_id_h3')
		->select('tpa.no_po_aksesoris as id_referensi')
		->select('tpa.no_po_aksesoris')
		->select('tpa.qty_paket')
		->select('tpa.keterangan')
		->from('tr_po_aksesoris tpa')
		->where('tpa.po_id_h3', $this->input->get('id'))
		->get()->result_array();

		$data['parts'] = $this->db
		// ->select('tpa.no_po_aksesoris as id_referensi')
		->select('dop.id_part')
		->select('p.nama_part')
		->select('dop.kuantitas')
		// ->from('tr_po_aksesoris tpa')
		// ->join('tr_po_aksesoris_detail as tpd', 'tpa.no_po_aksesoris = tpd.no_po_aksesoris')
		// ->join('tr_h3_dealer_purchase_order_parts as dop', 'dop.po_id = tpa.po_id_h3')
		->from('tr_h3_dealer_purchase_order_parts as dop')
		->join('ms_part as p', 'dop.id_part_int = p.id_part_int')
		->where('dop.po_id', $this->input->get('id'))
		// ->group_by('p.id_part')
		->get()->result_array();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		
		$data['rekap'] = $this->db
		->select('tpa.po_id_h3 ,GROUP_CONCAT(tpa.no_po_aksesoris SEPARATOR ",") as no_po_aksesoris')
		->from('tr_po_aksesoris tpa')
		->where('tpa.po_id_h3', $this->input->get('id'))
		->group_by('tpa.po_id_h3')
		// ->limit(1)
		->get()->row();

		$data['items'] = $this->db
		->select('tpa.po_id_h3')
		->select('tpa.no_po_aksesoris as id_referensi')
		->select('tpa.no_po_aksesoris')
		->select('tpa.qty_paket')
		->select('tpa.keterangan')
		->from('tr_po_aksesoris tpa')
		->where('tpa.po_id_h3', $this->input->get('id'))
		->get()->result_array();

		$data['parts'] = $this->db
		// ->select('tpa.no_po_aksesoris as id_referensi')
		->select('dop.id_part')
		->select('p.nama_part')
		->select('dop.kuantitas')
		// ->from('tr_po_aksesoris tpa')
		// ->join('tr_po_aksesoris_detail as tpd', 'tpa.no_po_aksesoris = tpd.no_po_aksesoris')
		// ->join('tr_h3_dealer_purchase_order_parts as dop', 'dop.po_id = tpa.po_id_h3')
		->from('tr_h3_dealer_purchase_order_parts as dop')
		->join('ms_part as p', 'dop.id_part_int = p.id_part_int')
		->where('dop.po_id', $this->input->get('id'))
		// ->group_by('p.id_part')
		->get()->result_array();


		$this->template($data);
	}

	public function update2()
	{
		// $this->validate();
		$id_rekap =$this->input->post('id');
		$this->db->trans_start();
		
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');
		$this->db->where('id', $id_rekap);
		$this->db->update('tr_h3_md_rekap_po_bundling', $data);

		$items = $this->getOnly(['id_referensi'], $this->input->post('items'));

		$parts = $this->input->post('parts');
		
		$this->db->where('id_rekap', $id_rekap);
		$this->db->delete('tr_h3_md_rekap_po_bundling_detail');

		foreach($items as $item){
			$data_detail = array(
				'id_rekap' => $id_rekap,
				'id_referensi' => $item['id_referensi']
			);
			
			$this->db->insert('tr_h3_md_rekap_po_bundling_detail', $data_detail);

		}


		$this->db->where('id_rekap', $id_rekap);
		$this->db->delete('tr_h3_md_rekap_po_bundling_part');

		$total = 0;
		foreach($parts as $part){

			$id_part_int = $this->db->select('id_part_int')
										->from('ms_part')
										->where('id_part',$part['id_part'])
										->get()->row_array();

			//Cek harga setelah diskon pada tabel PO Aksesoris Bundling
			$harga = $this->db->select('pod.harga')
							  ->from('tr_po_aksesoris_detail as pod')
							  ->where('no_po_aksesoris',$part['no_po_aksesoris'])
							  ->where('id_part', $part['id_part'])
							  ->get()->row_array();

			$diskon = $this->diskon_part_tertentu->get_diskon($part['id_part'], 707, 'REG');
			$harga_setelah_diskon = $harga['harga'];
			$part['tipe_diskon'] = '';
			$part['diskon'] = 0;

			if ($diskon != null) {
			  $part['tipe_diskon'] = $diskon['tipe_diskon'];
			  $part['diskon_value'] = $diskon['diskon_value'];

			  if($part['tipe_diskon'] == 'Rupiah'){
				$harga_setelah_diskon = $harga['harga'] - $part['diskon_value'];
			  }else if($part['tipe_diskon'] == 'Persen'){ 
				$diskon_2 = ($part['diskon_value'] / 100)* $harga['harga'];
				$harga_setelah_diskon = $harga['harga'] - $diskon_2;
			  }

			  if($harga_setelah_diskon < 0){
				$harga_setelah_diskon = 0;
			  }
			}

			$harga_setelah_diskon = round($harga_setelah_diskon);
			$sub_total = $harga_setelah_diskon*$part['kuantitas'];

			$data_part = array(
				'id_rekap' => $id_rekap,
				'id_referensi' => $part['no_po_aksesoris'],
				'id_part' => $part['id_part'],
				'id_part_int' => $id_part_int['id_part_int'],
				'kuantitas' => $part['kuantitas'],
				'harga' => $harga['harga'],
				'harga_setelah_diskon' => $harga_setelah_diskon,
				'tipe_diskon' => $part['tipe_diskon'],
				'diskon_value' => $part['diskon_value'],
				'subtotal' => $sub_total
			);

				$this->db->insert('tr_h3_md_rekap_po_bundling_part', $data_part);

				$total += $sub_total;
		}


		$this->db->set('total', $total);
		$this->db->where('id', $id_rekap);
		$this->db->update('tr_h3_md_rekap_po_bundling');

		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE){
			send_json([
				'message' => 'Berhasil update rekap PO Bundling',
				'payload' => $id_rekap,
				'redirect_url' => base_url('h3/h3_md_rekap_po_bundling/detail?id=' . $id_rekap)
			]);
		}else{
			$this->db->trans_rollback();
		  	// $this->output->set_status_header(500);
			  send_json([
				'error_type' => 'validation_error',
				'message' => 'Tidak berhasil update rekap PO Bundling'
			], 422);
		}
	}

	public function update(){
		$id_rekap =$this->input->post('po_id_h3');
		$this->db->trans_start();

		$po_id_int = $this->db->select('id')
							  ->from('tr_h3_dealer_purchase_order')
							  ->where('po_id', $id_rekap)
							  ->get()
							  ->row_array();

		$parts = $this->input->post('parts');
		$total = 0;
	
		$this->db->where('po_id', $id_rekap);
		$this->db->delete('tr_h3_dealer_purchase_order_parts');

		$this->db->where('po_id', $id_rekap);
		$this->db->delete('tr_h3_dealer_order_parts_tracking');

		foreach($parts as $part){
			//Cek apakah sebelumnya part sudah ada di no PO tersebut 
			$cek_part = $this->db->select('id_part')
				->select('kuantitas')
				->select('harga_setelah_diskon')
				->from('tr_h3_dealer_purchase_order_parts')
				->where('po_id',$id_rekap)
				->where('id_part', $part['id_part'])
				->get()->row_array();

			if(isset($cek_part)){
				$sub_total = $cek_part['harga_setelah_diskon']*$part['kuantitas'];

				$this->db->set('kuantitas', $cek_part['kuantitas']+$part['kuantitas']);
				$this->db->set('tot_harga_part', $sub_total+$cek_part['harga_setelah_diskon']);
				$this->db->where('po_id', $id_rekap);
				$this->db->where('id_part', $part['id_part']);
				$this->db->update('tr_h3_dealer_purchase_order_parts');
			}else{
				$id_part_int = $this->db->select('id_part_int')
										->from('ms_part')
										->where('id_part',$part['id_part'])
										->get()->row_array();

				//Cek harga setelah diskon pada tabel PO Aksesoris Bundling
				$harga = $this->db->select('pod.harga')
						->from('tr_po_aksesoris_detail as pod')
						->where('no_po_aksesoris',$part['no_po_aksesoris'])
						->where('id_part', $part['id_part'])
						->get()->row_array();

				$diskon = $this->diskon_part_tertentu->get_diskon($part['id_part'], 707, 'REG');
				$harga_setelah_diskon = $harga['harga'];
				$part['tipe_diskon'] = '';
				$part['diskon'] = 0;

				if ($diskon != null) {
					$part['tipe_diskon'] = $diskon['tipe_diskon'];
					$part['diskon_value'] = $diskon['diskon_value'];

					if($part['tipe_diskon'] == 'Rupiah'){
						$harga_setelah_diskon = $harga['harga'] - $part['diskon_value'];
					}else if($part['tipe_diskon'] == 'Persen'){ 
						$diskon_2 = ($part['diskon_value'] / 100)* $harga['harga'];
						$harga_setelah_diskon = $harga['harga'] - $diskon_2;
					}

					if($harga_setelah_diskon < 0){
						$harga_setelah_diskon = 0;	
					}
				}

				$harga_setelah_diskon = round($harga_setelah_diskon);

				$sub_total = $harga_setelah_diskon*$part['kuantitas'];

				$purchase_order_parts = array(
					'po_id_int' => $po_id_int['id'],
					'po_id' => $id_rekap,
					'id_part_int' => $id_part_int['id_part_int'],
					'id_part' => $part['id_part'],
					'kuantitas' => $part['kuantitas'],
					'harga_saat_dibeli' => $harga['harga'],
					'harga_setelah_diskon' => $harga_setelah_diskon,
					'tipe_diskon' => $part['tipe_diskon'],
					'diskon_value' => $part['diskon_value'],
					'tot_harga_part' => $sub_total
				);

				$this->db->insert('tr_h3_dealer_purchase_order_parts', $purchase_order_parts);

				// Insert ke Order Part Tracking
				$order_part_tracking = array(
					'po_id' => $id_rekap,
					'po_id_int' => $po_id_int['id'],
					'id_part_int' => $id_part_int['id_part_int'],
					'id_part' => $part['id_part'],
					'created_at' => date('Y-m-d H:i:s', time()),
					'created_by' => $this->session->userdata('id_user')
				);

				$this->db->insert('tr_h3_dealer_order_parts_tracking', $order_part_tracking);
			}
			$total += $sub_total;
		}

		$this->db->set('total_amount', $total);
		$this->db->where('id', $po_id_int['id']);
		$this->db->update('tr_h3_dealer_purchase_order');

		//update data rekap dan no po di tabel tr_po_aksesoris
		$data_po_aksesoris_1 = array(
			'is_rekap' => 0,
			'po_id_h3' => ''
		);
		$this->db->where('po_id_h3', $id_rekap);
		$this->db->update('tr_po_aksesoris', $data_po_aksesoris_1);

		$items = $this->getOnly(['id_referensi'], $this->input->post('items'));
		foreach($items as $item){
			$data_po_aksesoris = array(
				'is_rekap' => 1,
				'po_id_h3' => $id_rekap
			);
		
			$this->db->where('no_po_aksesoris', $item['id_referensi']);
			$this->db->update('tr_po_aksesoris', $data_po_aksesoris);
		}

		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE){
			send_json([
				'message' => 'Berhasil update rekap PO Bundling',
				'payload' => $id_rekap,
				'redirect_url' => base_url('h3/h3_md_rekap_po_bundling/detail?id=' . $id_rekap)
			]);
		}else{
			$this->db->trans_rollback();
		  	// $this->output->set_status_header(500);
			  send_json([
				'error_type' => 'validation_error',
				'message' => 'Tidak berhasil simpan rekap PO Bundling'
			], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		// $this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		// $this->form_validation->set_rules('tipe_po', 'Tipe PO', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function create_so2(){
		$this->db->trans_start();
			
		$id = $this->input->post('id');

			//Cek total amount di tabel rekap PO 
			$po_aksesoris = $this->db->select('total')
									->from('tr_h3_md_rekap_po_bundling')
									->where('id',$id)
									->get()->row_array();


			$purchase_order = array(
				'po_id' => $this->purchase_order->generatePONumber('REG', 707),
				'id_dealer' => 707,
				'kategori_po' => 'Bundling H1',
				'id_salesman' => '',
				'po_type' => 'REG',
				'produk' => 'Acc',
				'gimmick' => 0,
				'gimmick_tidak_langsung' => 0,
				'tanggal_order' => date('Y-m-d'),
				'created_at' => date('Y-m-d H:i:s', time()),
				// 'created_by' => $this->session->userdata('id_user'),
				'approve_at' => date('Y-m-d H:i:s', time()),
				'approve_by' => $this->session->userdata('id_user'),
				'submit_at' => date('Y-m-d H:i:s', time()),
				'submit_by' => $this->session->userdata('id_user'),
				'proses_at' => date('Y-m-d H:i:s', time()),
				'proses_by' => $this->session->userdata('id_user'),
				'created_by_md' => 1,
				'po_md' => 1,
				'status' => 'Processed by MD',
				'total_amount' => $po_aksesoris['total']
			);

			$this->purchase_order->insert($purchase_order);

			$po_id_int = $this->db->select('id')
								  ->from('tr_h3_dealer_purchase_order')
								  ->where('po_id', $purchase_order['po_id'])
								  ->get()
								  ->row_array();

			//Get Data PO aksesoris part 
			$po_aksesoris_part = $this->db->select('id_part')
										  ->select('id_part_int')
										  ->select('sum(kuantitas) as kuantitas')
										  ->select('harga')
										  ->select('harga_setelah_diskon')
										  ->select('tipe_diskon')
										  ->select('diskon_value')
										  ->select('subtotal')
										  ->from('tr_h3_md_rekap_po_bundling_part')
										  ->where('id_rekap',$id)
										  ->group_by('id_part')
										  ->get()
										  ->result_array();
										  

			foreach($po_aksesoris_part as $po_part){
				$purchase_order_parts = array(
					'po_id_int' => $po_id_int['id'],
					'po_id' => $purchase_order['po_id'],
					'id_part_int' => $po_part['id_part_int'],
					'id_part' => $po_part['id_part'],
					'kuantitas' => $po_part['kuantitas'],
					'harga_saat_dibeli' => $po_part['harga'],
					'harga_setelah_diskon' => $po_part['harga_setelah_diskon'],
					'tipe_diskon' => $po_part['tipe_diskon'],
					'diskon_value' => $po_part['diskon_value'],
					'tot_harga_part' => $po_part['harga_setelah_diskon']*$po_part['kuantitas']
				);

				$this->db->insert('tr_h3_dealer_purchase_order_parts', $purchase_order_parts);

				// Insert ke Order Part Tracking
				$order_part_tracking = array(
					'po_id' => $purchase_order['po_id'],
					'po_id_int' => $po_id_int['id'],
					'id_part_int' => $po_part['id_part_int'],
					'id_part' => $po_part['id_part'],
					'created_at' => date('Y-m-d H:i:s', time()),
        			'created_by' => $this->session->userdata('id_user')
				);
				
				$this->db->insert('tr_h3_dealer_order_parts_tracking', $order_part_tracking);
			}
		

		$sales_order = array(
			'id_sales_order' => $this->sales_order->generateID('REG', 707, null, 0),
			'id_dealer' => 707,
			'tipe_source' => 'Dealer',
			'jenis_pembayaran' => 'Credit',
			'id_ref' => $purchase_order['po_id'],
			'po_type' => 'REG',
			'kategori_po' => 'Bundling H1',
			'produk' => 'Acc',
			'gimmick' => 0,
			'gimmick_tidak_langsung' => 0,
			'type_ref' => 'purchase_order_dealer',
			'tanggal_order' => date('Y-m-d'),
			'total_amount' => $po_aksesoris['total'],
			'created_at' => date('Y-m-d H:i:s', time()),
			'created_by' => $this->session->userdata('id_user'),
			'status' => 'New SO',
			'status_do' => 'New',
			'created_by_md' => 1,
			'referensi_id_rekap_po_bundling' => $id
		);

		$this->sales_order->insert($sales_order);

		$id_sales_order_int = $this->db->select('id')
								  ->from('tr_h3_md_sales_order')
								  ->where('id_sales_order', $sales_order['id_sales_order'])
								  ->get()
								  ->row_array();

		foreach($po_aksesoris_part as $po_part){
			
			//Cek harga hpp 
			$hpp = $this->db->select('harga_md_dealer')
							->from('ms_part')
							->where('id_part_int',$po_part['id_part_int'])
							->get()->row_array();

			if($po_part['tipe_diskon'] == 'Persen' || $po_part['tipe_diskon'] == 'Rupiah'){
				$diskon = $po_part['harga'] - $po_part['harga_setelah_diskon'];
			}else{
				$diskon = 0;
			}

			$sales_order_parts = array(
				'id_sales_order' => $sales_order['id_sales_order'],
				'id_sales_order_int' => $id_sales_order_int['id'],
				'id_part_int' => $po_part['id_part_int'],
				'id_part' => $po_part['id_part'],
				'qty_order' => $po_part['kuantitas'],
				'qty_on_hand' => 0,
				'qty_pemenuhan' => $po_part['kuantitas'],
				'hpp' => $hpp['harga_md_dealer'],
				'harga' => $po_part['harga'],
				'harga_setelah_diskon' => $po_part['harga_setelah_diskon'],
				'tipe_diskon' => $po_part['tipe_diskon'],
				'diskon_value' => $po_part['diskon_value'],
				'diskon' => $diskon
			);
			$this->db->insert('tr_h3_md_sales_order_parts', $sales_order_parts);
		}


		$this->db->trans_complete();

		$sales_order = (array) $this->sales_order->find($sales_order['id_sales_order'], 'id_sales_order');
		if ($this->db->trans_status() and $sales_order != null) {
			send_json([
				'message' => 'Berhasil simpan sales order MD dengan no SO : ' . $sales_order['id_sales_order'],
				'payload' => $sales_order,
				'redirect_url' => base_url('h3/h3_md_rekap_po_bundling/detail?id=' .$id)
			]);
		} else {
			$this->db->trans_rollback();
			send_json([
				'message' => 'Tidak berhasil simpan sales order MD'
			], 422);
		}
	}

	public function create_so(){
		$this->db->trans_start();
			
		$id_rekap = $this->input->post('po_id_h3');

		$po_id_int = $this->db->select('id')
							  ->select('total_amount')
							  ->from('tr_h3_dealer_purchase_order')
							  ->where('po_id', $id_rekap)
							  ->get()
							  ->row_array();

		$sales_order = array(
			'id_sales_order' => $this->sales_order->generateID('REG', 707, null, 0),
			'id_dealer' => 707,
			'tipe_source' => 'Dealer',
			'jenis_pembayaran' => 'Credit',
			'id_ref' => $id_rekap,
			'po_type' => 'REG',
			'kategori_po' => 'Bundling H1',
			'produk' => 'Acc',
			'gimmick' => 0,
			'gimmick_tidak_langsung' => 0,
			'type_ref' => 'purchase_order_dealer',
			'tanggal_order' => date('Y-m-d'),
			'total_amount' => $po_id_int['total_amount'],
			'created_at' => date('Y-m-d H:i:s', time()),
			'created_by' => $this->session->userdata('id_user'),
			'status' => 'New SO',
			'status_do' => 'New',
			'created_by_md' => 1,
			'is_rekap_po_bundling' => 1
		);

		$this->sales_order->insert($sales_order);

		$id_sales_order_int = $this->db->select('id')
								  ->from('tr_h3_md_sales_order')
								  ->where('id_sales_order', $sales_order['id_sales_order'])
								  ->get()
								  ->row_array();

		$po_aksesoris_part = $this->db->select('id_part')
								  ->select('id_part_int')
								  ->select('kuantitas')
								  ->select('harga_saat_dibeli as harga')
								  ->select('harga_setelah_diskon')
								  ->select('tipe_diskon')
								  ->select('diskon_value')
								  ->select('tot_harga_part')
								  ->from('tr_h3_dealer_purchase_order_parts')
								  ->where('po_id', $id_rekap)
								  ->get()
								  ->result_array();

		foreach($po_aksesoris_part as $po_part){
			
			//Cek harga hpp 
			$hpp = $this->db->select('harga_md_dealer')
							->from('ms_part')
							->where('id_part_int',$po_part['id_part_int'])
							->get()->row_array();

			if($po_part['tipe_diskon'] == 'Persen' || $po_part['tipe_diskon'] == 'Rupiah'){
				$diskon = $po_part['harga'] - $po_part['harga_setelah_diskon'];
			}else{
				$diskon = 0;
			}

			$sales_order_parts = array(
				'id_sales_order' => $sales_order['id_sales_order'],
				'id_sales_order_int' => $id_sales_order_int['id'],
				'id_part_int' => $po_part['id_part_int'],
				'id_part' => $po_part['id_part'],
				'qty_order' => $po_part['kuantitas'],
				'qty_on_hand' => 0,
				'qty_pemenuhan' => $po_part['kuantitas'],
				'hpp' => $hpp['harga_md_dealer'],
				'harga' => $po_part['harga'],
				'harga_setelah_diskon' => $po_part['harga_setelah_diskon'],
				'tipe_diskon' => $po_part['tipe_diskon'],
				'diskon_value' => $po_part['diskon_value'],
				'diskon' => $diskon
			);
			$this->db->insert('tr_h3_md_sales_order_parts', $sales_order_parts);
		}


		$this->db->trans_complete();

		$sales_order = (array) $this->sales_order->find($sales_order['id_sales_order'], 'id_sales_order');
		if ($this->db->trans_status() and $sales_order != null) {
			send_json([
				'message' => 'Berhasil simpan sales order MD dengan no SO : ' . $sales_order['id_sales_order'],
				'payload' => $id_rekap,
				'redirect_url' => base_url('h3/h3_md_rekap_po_bundling/detail?id=' .$id_rekap)
			]);
		} else {
			$this->db->trans_rollback();
			send_json([
				'message' => 'Tidak berhasil simpan sales order MD'
			], 422);
		}
	}
}
