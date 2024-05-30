<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_jawaban_claim_dealer extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_jawaban_claim_dealer";
    protected $title  = "Jawaban Claim Dealer";

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
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_claim_dealer_model', 'claim_dealer');
		$this->load->model('h3_md_claim_dealer_parts_model', 'claim_dealer_parts');
		$this->load->model('h3_md_claim_part_ahass_model', 'claim_part_ahass');
		$this->load->model('h3_md_claim_part_ahass_parts_model', 'claim_part_ahass_parts');
		$this->load->model('h3_md_jawaban_claim_dealer_model', 'jawaban_claim_dealer');
		$this->load->model('h3_md_jawaban_claim_dealer_parts_model', 'jawaban_claim_dealer_parts');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";

		$this->template($data);
	}

	public function get_claim_dealer_parts(){
		$qty_sudah_terjawab = $this->db
		->select('
			SUM(
				case
					when jcdp.barang_checklist = 1 then jcdp.qty_barang
					when jcdp.uang_checklist = 1 then jcdp.qty_uang
					when jcdp.tolak_checklist = 1 then jcdp.qty_tolak
				end
			) as qty
		')
		->from('tr_h3_md_jawaban_claim_dealer_parts as jcdp')
		->where('jcdp.id_claim_dealer = cpap.id_claim_dealer', null, false)
		->where('jcdp.id_part = cpap.id_part', null, false)
		->where('jcdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3', null, false)
		->get_compiled_select();

		$parts = $this->db
		->select('cpap.id_claim_dealer')
		->select('cpap.id_part')
		->select('cpap.id_kategori_claim_c3')
		->select('cdp.qty_part_dikirim_ke_md')
		->select('p.nama_part')
		->select('d.nama_dealer')
		->select('0 as barang_checklist')
		->select('0 as uang_checklist')
		->select('0 as tolak_checklist')
		->select('0 as qty_barang')
		->select('0 as qty_uang')
		->select('p.harga_md_dealer as nominal_uang')
		->select('0 as qty_tolak')
		->select('"" as alasan_ditolak')
		->select('0 as pending')
		->select('0 as qty_pending')
		->select('"" as alasan_pending')
		->select("IFNULL(({$qty_sudah_terjawab}), 0) as qty_sudah_terjawab", false)
		->select("(cdp.qty_part_dikirim_ke_md - IFNULL(({$qty_sudah_terjawab}), 0)) as qty_belum_terjawab", false)
		->from('tr_h3_md_claim_part_ahass as cpa')
		->join('tr_h3_md_claim_part_ahass_parts as cpap', 'cpa.id_claim_part_ahass = cpap.id_claim_part_ahass')
		->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = cpap.id_claim_dealer')
		->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_part = cpap.id_part and cdp.id_claim_dealer = cpap.id_claim_dealer and cdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3)')
		->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
		->join('ms_part as p', 'p.id_part = cpap.id_part')
		->where('cpa.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'))
		->having('qty_belum_terjawab > 0')
		->get()->result();

		send_json($parts);
	}

	public function save(){
		$this->validate();
		$jawaban_claim_dealer = array_merge($this->input->post([
			'id_claim_part_ahass', 'no_surat_jalan_ahm'
		]), [
			'id_jawaban_claim_dealer' => $this->jawaban_claim_dealer->generateID()
		]);

		$claim_dealer_parts = $this->getOnly([
			'id_part', 'id_claim_dealer', 'id_kategori_claim_c3', 'barang_checklist', 
			'uang_checklist', 'tolak_checklist', 'qty_barang', 
			'qty_uang', 'nominal_uang', 'qty_tolak', 'alasan_ditolak', 'pending', 'qty_pending', 'alasan_pending'
		], $this->input->post('claim_dealer_parts'), [
			'id_jawaban_claim_dealer' => $jawaban_claim_dealer['id_jawaban_claim_dealer']
		]);

		$this->db->trans_start();
		$this->jawaban_claim_dealer->insert($jawaban_claim_dealer);
		if(count($claim_dealer_parts) > 0){
			$this->jawaban_claim_dealer_parts->insert_batch($claim_dealer_parts);
		}
		$this->db->trans_complete();

		$jawaban_claim_dealer = (array) $this->jawaban_claim_dealer->find($jawaban_claim_dealer['id_jawaban_claim_dealer'], 'id_jawaban_claim_dealer');
		if ($this->db->trans_status() AND $jawaban_claim_dealer != null) {
			$message = 'Jawaban claim dealer berhasil dibuat.';

			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'message' => $message,
				'payload' => $jawaban_claim_dealer,
				'redirect_url' => base_url(sprintf('h3/h3_md_jawaban_claim_dealer/detail?id_jawaban_claim_dealer=%s', $jawaban_claim_dealer['id_jawaban_claim_dealer']))
			]);
		}else{
			send_json([
				'message' => 'Jawaban claim dealer tidak berhasil dibuat.'
			], 422);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['jawaban_claim_dealer'] = $this->db
		->select('date_format(jcd.created_at, "%d/%m/%Y") as created_at')
        ->select('jcd.id_jawaban_claim_dealer')
        ->select('jcd.id_claim_part_ahass')
        ->select('jcd.no_surat_jalan_ahm')
        ->select('jcd.status')
		->from('tr_h3_md_jawaban_claim_dealer as jcd')
		->where('jcd.id_jawaban_claim_dealer', $this->input->get('id_jawaban_claim_dealer'))
		->get()->row();

		$data['claim_dealer_parts'] = $this->db
		->select('date_format(jcd.created_at, "%d/%m/%Y") as tanggal_terima_part')
		->select('d.nama_dealer')
		->select('jcdp.id_part')
		->select('p.nama_part')
		->select('cdp.qty_part_dikirim_ke_md')
		->select('
			case
				when jcdp.barang_checklist = 1 then jcdp.qty_barang
				when jcdp.uang_checklist = 1 then jcdp.qty_uang
				when jcdp.tolak_checklist = 1 then jcdp.qty_tolak
				else 0
			end as qty_pergantian
		', false)
		->select('
			case
				when jcdp.barang_checklist = 1 then "Ganti Barang"
				when jcdp.uang_checklist = 1 then "Ganti Uang"
				when jcdp.tolak_checklist = 1 then "Ditolak"
				else "-"
			end as tipe_pergantian
		', false)
		->select('
			case
				when jcdp.pending = 1 then "Pending"
				when jcdp.pending = 0 then "Close"
			end as status
		', false)
		->select('
			case
				when jcdp.barang_checklist = 1 then jcdp.proses_ganti_barang
				when jcdp.uang_checklist = 1 then jcdp.proses_ganti_uang
				when jcdp.tolak_checklist = 1 then jcdp.proses_tolak
				else 0
			end as sudah_proses
		', false)
		->from('tr_h3_md_jawaban_claim_dealer_parts as jcdp')
		->join('tr_h3_md_jawaban_claim_dealer as jcd', 'jcd.id_jawaban_claim_dealer = jcdp.id_jawaban_claim_dealer')
		->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = jcdp.id_claim_dealer')
		->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_part = jcdp.id_part and cdp.id_claim_dealer = jcdp.id_claim_dealer and cdp.id_kategori_claim_c3 = jcdp.id_kategori_claim_c3)')
		->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
		->join('ms_part as p', 'p.id_part = jcdp.id_part')
		->where('jcdp.id_jawaban_claim_dealer', $this->input->get('id_jawaban_claim_dealer'))
		->get()->result();

		$this->template($data);
	}

	public function proses(){
		$data['mode'] = 'proses';
		$data['set'] = "form";
		$data['jawaban_claim_dealer'] = $this->db
		->select('date_format(jcd.created_at, "%d-%m-%Y") as created_at')
        ->select('jcd.id_jawaban_claim_dealer')
        ->select('jcd.id_claim_part_ahass')
        ->select('jcd.no_surat_jalan_ahm')
        ->select('jcd.status')
		->from('tr_h3_md_jawaban_claim_dealer as jcd')
		->where('jcd.id_jawaban_claim_dealer', $this->input->get('id_jawaban_claim_dealer'))
		->get()->row();


		$data['claim_dealer_parts'] = $this->db
		->select('date_format(jcd.created_at, "%d-%m-%Y") as tanggal_terima_part')
		->select('d.nama_dealer')
		->select('jcdp.id_part')
		->select('p.nama_part')
		->select('cdp.qty_part_diclaim')
		->select('
			case
				when jcdp.barang_checklist = 1 then jcdp.qty_barang
				when jcdp.uang_checklist = 1 then jcdp.qty_uang
				else 0
			end as qty_pergantian
		', false)
		->select('
			case
				when jcdp.barang_checklist = 1 then "Ganti Barang"
				when jcdp.uang_checklist = 1 then "Ganti Uang"
				else "-"
			end as tipe_pergantian
		', false)
		->select('
			case
				when jcdp.pending = 1 then "Pending"
				when jcdp.pending = 0 then "Close"
			end as status
		', false)
		->from('tr_h3_md_jawaban_claim_dealer_parts as jcdp')
		->join('tr_h3_md_jawaban_claim_dealer as jcd', 'jcd.id_jawaban_claim_dealer = jcdp.id_jawaban_claim_dealer')
		->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = jcdp.id_claim_dealer')
		->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_part = jcdp.id_part and cdp.id_claim_dealer = jcdp.id_claim_dealer)')
		->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
		->join('ms_part as p', 'p.id_part = jcdp.id_part')
		->where('jcdp.id_jawaban_claim_dealer', $this->input->get('id_jawaban_claim_dealer'))
		->get()->result();

		$this->template($data);
	}

	public function save_proses(){
		$this->validate_proses();
		
		$this->db->trans_start();
		foreach ($this->input->post('parts') as $part) {
			if($this->input->post('jenis_penggantian') == 'Ganti Barang'){
				$this->jawaban_claim_dealer_parts->update([
					'proses_ganti_barang' => 1
				], [
					'id_claim_dealer' => $part['id_claim_dealer'],
					'id_part' => $part['id_part'],
					'id_kategori_claim_c3' => $part['id_kategori_claim_c3'],
				]);
			}else if($this->input->post('jenis_penggantian') == 'Ganti Uang'){
				$this->jawaban_claim_dealer_parts->update([
					'proses_ganti_uang' => 1
				], [
					'id_claim_dealer' => $part['id_claim_dealer'],
					'id_part' => $part['id_part'],
					'id_kategori_claim_c3' => $part['id_kategori_claim_c3'],
				]);
			}else if($this->input->post('jenis_penggantian') == 'Tolak'){
				$this->jawaban_claim_dealer_parts->update([
					'proses_tolak' => 1
				], [
					'id_claim_dealer' => $part['id_claim_dealer'],
					'id_part' => $part['id_part'],
					'id_kategori_claim_c3' => $part['id_kategori_claim_c3'],
				]);
			}
		}

		$this->jawaban_claim_dealer->update([
			'status' => 'Processed',
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user')
		], [
			'status' => 'Open',
			'id_jawaban_claim_dealer' => $this->input->post('id_jawaban_claim_dealer')
		]);

		if($this->input->post('jenis_penggantian') == 'Ganti Barang'){
			$this->load->model('H3_md_surat_pengantar_claim_c3_model', 'surat_pengantar_claim_c3');
			$this->load->model('H3_md_surat_pengantar_claim_c3_item_model', 'surat_pengantar_claim_c3_item');
			$surat_pengantar = [
				'id_surat_pengantar' => $this->surat_pengantar_claim_c3->generateID($this->input->post('id_dealer')),
				'id_dealer' => $this->input->post('id_dealer'),
				'id_jawaban_claim_dealer' => $this->input->post('id_jawaban_claim_dealer'),
				'tanggal' => date('Y-m-d', time())
			];
	
			$parts_surat_pengantar = $this->getOnly([
				'id_part', 'id_claim_dealer', 'id_kategori_claim_c3',
				'no_faktur', 'qty'
			], $this->input->post('parts'), [
				'id_surat_pengantar' => $surat_pengantar['id_surat_pengantar']
			]);
			$parts_surat_pengantar = array_map(function($row){
				$row['qty_ganti_barang'] = $row['qty'];
				unset($row['qty']);
				return $row;
			}, $parts_surat_pengantar);

			$this->surat_pengantar_claim_c3->insert($surat_pengantar);
			$this->surat_pengantar_claim_c3_item->insert_batch($parts_surat_pengantar);
		}else if($this->input->post('jenis_penggantian') == 'Ganti Uang'){
			$this->load->model('H3_md_ar_part_model', 'ar_part');
			$this->load->model('H3_md_ap_part_model', 'ap_part');

			$id_jawaban_claim_dealer = $this->input->post('id_jawaban_claim_dealer');

			$jawaban_claim_dealer = $this->db
			->select('jcd.id as id_jawaban_claim_dealer_int')
			->select('jcd.id_jawaban_claim_dealer')
			->select('cpa.id as id_claim_part_ahass_int')
			->select('cpa.id_claim_part_ahass')
			->select('cpap.id_claim_part_ahass')
			->select('cd.id as id_claim_dealer_int')
			->select('cd.id_claim_dealer')
			->select('ps.id as id_packing_sheet_int')
			->select('ps.id_packing_sheet')
			->select('pl.id as id_picking_list_int')
			->select('pl.id_picking_list')
			->select('do.id as id_do_sales_order_int')
			->select('do.id_do_sales_order')
			->select('so.id as id_sales_order_int')
			->select('so.id_sales_order')
			->select('so.produk')
			->from('tr_h3_md_jawaban_claim_dealer as jcd')
			->join('tr_h3_md_claim_part_ahass as cpa', '(cpa.id_claim_part_ahass = jcd.id_claim_part_ahass and cpa.status = "Processed")')
			->join('tr_h3_md_claim_part_ahass_parts as cpap', '(cpap.id_claim_part_ahass_int = cpa.id)')
			->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer and cpap.id_claim_dealer')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
			->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
			->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
			->where('jcd.id_jawaban_claim_dealer', $id_jawaban_claim_dealer)
			->limit(1)
			->get()->row_array();

			if($jawaban_claim_dealer == null) throw new Exception('Jawaban claim dealer tidak ditemukan');

			$ar_part = [
				'referensi' => $this->input->post('id_jawaban_claim_dealer'),
				'nama_customer' => 'ASTRA HONDA MOTOR (AHM)',
				'jenis_transaksi' => strtolower($jawaban_claim_dealer['produk']),
				'tipe_referensi' => 'jawaban_claim_dealer',
				'tanggal_transaksi' => date('Y-m-d', time()),
				'tanggal_jatuh_tempo' => date('Y-m-d', time()),
				'total_amount' => $this->input->post('total_amount')
			];

			$this->ar_part->insert($ar_part);

			$ap_part = [
				'id_referensi_table' => 'AHM',
				'referensi_table' => 'ms_vendor',
				'referensi' => $id_jawaban_claim_dealer,
				'jenis_transaksi' => 'jawaban_claim_dealer',
				'id_dealer' => $this->input->post('id_dealer'),
				'tanggal_transaksi' => date('Y-m-d', time()),
				'tanggal_jatuh_tempo' => date('Y-m-d', time()),
				'nama_vendor' => 'AHM',
				'total_bayar' => $this->input->post('total_amount')
			];

			$this->ap_part->insert($ap_part);
		}elseif($this->input->post('jenis_penggantian') == 'Tolak'){
			$this->load->model('H3_md_surat_pengantar_claim_c3_model', 'surat_pengantar_claim_c3');
			$this->load->model('H3_md_surat_pengantar_claim_c3_item_model', 'surat_pengantar_claim_c3_item');
			$surat_pengantar = [
				'id_surat_pengantar' => $this->surat_pengantar_claim_c3->generateID($this->input->post('id_dealer')),
				'id_dealer' => $this->input->post('id_dealer'),
				'id_jawaban_claim_dealer' => $this->input->post('id_jawaban_claim_dealer'),
				'tanggal' => date('Y-m-d', time())
			];
	
			$parts_surat_pengantar = $this->getOnly([
				'id_part', 'id_claim_dealer', 'id_kategori_claim_c3',
				'no_faktur', 'qty'
			], $this->input->post('parts'), [
				'id_surat_pengantar' => $surat_pengantar['id_surat_pengantar']
			]);
			$parts_surat_pengantar = array_map(function($row){
				$row['qty_ganti_barang'] = $row['qty'];
				unset($row['qty']);
				return $row;
			}, $parts_surat_pengantar);

			$this->surat_pengantar_claim_c3->insert($surat_pengantar);
			$this->surat_pengantar_claim_c3_item->insert_batch($parts_surat_pengantar);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Berhasil Proses',
			]);
		}else{
			  send_json([
				'message' => 'Gagal Proses',
			], 422);
		}
	}

	public function validate_proses(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');

        if (!$this->form_validation->run()){
            send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
    }

	public function get_parts(){
		$data = $this->db
		->select('cd.id_claim_dealer')
		->select('date_format(cd.tanggal, "%d/%m/%Y") as tgl_claim_dealer')
		->select('jcdp.id_part')
		->select('p.nama_part')
		->select('jcdp.id_kategori_claim_c3')
		->select('ps.no_faktur')
		->select("
			case
				when '{$this->input->get('jenis_penggantian')}' = 'Ganti Barang' then jcdp.qty_barang
				when '{$this->input->get('jenis_penggantian')}' = 'Ganti Uang' then jcdp.qty_uang
				when '{$this->input->get('jenis_penggantian')}' = 'Tolak' then jcdp.qty_tolak
			end as qty
		", false)
		->select('p.harga_dealer_user as het')
		->select('jcdp.nominal_uang')
		->from('tr_h3_md_jawaban_claim_dealer_parts as jcdp')
		->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = jcdp.id_claim_dealer')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
		->join('ms_part as p', 'p.id_part = jcdp.id_part')
		->where('jcdp.id_jawaban_claim_dealer', $this->input->get('id_jawaban_claim_dealer'))
		->where('cd.id_dealer', $this->input->get('id_dealer'))
		->where('jcdp.pending', 0)
		->where("
			case
				when '{$this->input->get('jenis_penggantian')}' = 'Ganti Barang' then jcdp.qty_barang > 0
				when '{$this->input->get('jenis_penggantian')}' = 'Ganti Uang' then jcdp.qty_uang > 0
				when '{$this->input->get('jenis_penggantian')}' = 'Tolak' then jcdp.qty_tolak > 0
			end
		", null, false)
		->where("
			case
				when '{$this->input->get('jenis_penggantian')}' = 'Ganti Barang' then proses_ganti_barang = 0
				when '{$this->input->get('jenis_penggantian')}' = 'Ganti Uang' then proses_ganti_uang = 0
				when '{$this->input->get('jenis_penggantian')}' = 'Tolak' then proses_tolak = 0
			end
		", null, false)
		->get()->result_array()
		;

		$data = array_map(function($row){
			if($this->input->get('jenis_penggantian') == 'Ganti Barang'){
				$row['amount'] = intval($row['qty']) * floatval($row['het']);
			}else{
				$row['amount'] = $row['nominal_uang'];
			}
			unset($row['nominal_uang']);
			return $row;
		}, $data);

		send_json($data);
	}

	public function close(){
		$this->db->trans_start();
		$this->jawaban_claim_dealer->update([
			'close_at' => date('Y-m-d H:i:s', time()),
			'close_by' => $this->session->userdata('id_user'),
			'status' => 'Closed'
		], $this->input->get(['id_jawaban_claim_dealer']));
		$this->db->trans_complete();
		
		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Jawaban claim dealer berhasil diclose.');
			$this->session->set_flashdata('tipe', 'info');
			$jawaban_claim_dealer = $this->jawaban_claim_dealer->get($this->input->get(['id_jawaban_claim_dealer']), true);
			send_json($jawaban_claim_dealer);
		}else{
			$this->session->set_flashdata('pesan', 'Jawaban claim dealer tidak berhasil diclose.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_claim_part_ahass', 'Claim Part AHASS', 'required');
		$this->form_validation->set_rules('no_surat_jalan_ahm', 'No Surat Jalan AHM', 'required');

        if (!$this->form_validation->run()){
            send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
    }
}