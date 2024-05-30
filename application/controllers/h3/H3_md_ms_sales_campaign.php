<?php

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_ms_sales_campaign extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_ms_sales_campaign";
	protected $title  = "Master Sales Campaign";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
		$this->load->model('H3_md_sales_campaign_dealers_model', 'sales_campaign_dealers');
		$this->load->model('H3_md_sales_campaign_detail_poin_model', 'sales_campaign_detail_poin');
		$this->load->model('H3_md_sales_campaign_detail_diskon_model', 'sales_campaign_detail_diskon');
		$this->load->model('H3_md_sales_campaign_detail_diskon_item_model', 'sales_campaign_detail_diskon_item');
		$this->load->model('H3_md_sales_campaign_detail_diskon_global_model', 'sales_campaign_detail_diskon_global');
		$this->load->model('H3_md_sales_campaign_detail_hadiah_model', 'sales_campaign_detail_hadiah');
		$this->load->model('H3_md_sales_campaign_detail_cashback_model', 'sales_campaign_detail_cashback');
		$this->load->model('H3_md_sales_campaign_detail_cashback_global_model', 'sales_campaign_detail_cashback_global');
		$this->load->model('H3_md_sales_campaign_detail_cashback_item_model', 'sales_campaign_detail_cashback_item');
		$this->load->model('H3_md_sales_campaign_detail_gimmick_model', 'sales_campaign_detail_gimmick');
		$this->load->model('H3_md_sales_campaign_detail_gimmick_global_model', 'sales_campaign_detail_gimmick_global');
		$this->load->model('H3_md_sales_campaign_detail_gimmick_item_model', 'sales_campaign_detail_gimmick_item');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->library('Mcarbon');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}

	public function index()
	{
		$data['set']	= "index";
		$this->template($data);
	}

	public function add()
	{
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save()
	{
		$this->validate();
		$this->db->trans_start();
		$post = $this->input->post([
			'kode_campaign', 'nama', 'start_date', 'end_date', 'kontribusi', 'jenis_reward_poin',
			'jenis_item_poin', 'reward_poin', 'produk_program_poin', 'start_date_poin', 'end_date_poin', 'satuan_rekapan_poin', 'jenis_reward_diskon',
			'jenis_item_diskon', 'jenis_diskon_campaign', 'produk_program_diskon', 'start_date_diskon', 'end_date_diskon', 'jenis_reward_cashback',
			'jenis_item_cashback', 'reward_cashback', 'produk_program_cashback', 'start_date_cashback', 'end_date_cashback', 'satuan_rekapan_cashback',
			'jenis_item_gimmick', 'jenis_reward_gimmick', 'reward_gimmick', 'produk_program_gimmick', 'start_date_gimmick', 'end_date_gimmick', 'satuan_rekapan_gimmick',
			'kelipatan_gimmick', 'nama_produk_campaign', 'mekanisme_program', 'kategori', 'active',
		]);

		$sales_campaign = $this->clean_data($post);

		$this->sales_campaign->insert($sales_campaign);
		$id = $this->db->insert_id();

		if (count($this->input->post('sales_campaign_dealers')) > 0) {
			$sales_campaign_dealers = $this->getOnly([
				'id_dealer',
			], $this->input->post('sales_campaign_dealers'), [
				'id_campaign' => $id
			]);
			$this->sales_campaign_dealers->insert_batch($sales_campaign_dealers);
		}

		if (count($this->input->post('sales_campaign_detail_poin')) > 0) {
			$sales_campaign_detail_poin = $this->getOnly([
				'id_part', 'poin', 'id_kelompok_part', 'satuan'
			], $this->input->post('sales_campaign_detail_poin'), [
				'id_campaign' => $id
			]);
			$this->sales_campaign_detail_poin->insert_batch($sales_campaign_detail_poin);
		}

		if (count($this->input->post('sales_campaign_detail_hadiah')) > 0) {
			$sales_campaign_detail_hadiah = $this->getOnly([
				'jumlah_poin', 'nama_paket', 'nama_hadiah', 'voucher_rupiah'
			], $this->input->post('sales_campaign_detail_hadiah'), [
				'id_campaign' => $id
			]);
			$this->sales_campaign_detail_hadiah->insert_batch($sales_campaign_detail_hadiah);
		}

		if (count($this->input->post('sales_campaign_detail_diskon')) > 0) {
			foreach ($this->input->post('sales_campaign_detail_diskon') as $each_diskon) {
				$data = $this->get_in_array([
					'id_part', 'id_kelompok_part', 'tipe_diskon'
				], $each_diskon, [
					'id_campaign' => $id
				]);
				$this->sales_campaign_detail_diskon->insert($data);
				$id_detail_diskon = $this->db->insert_id();
				if (isset($each_diskon['diskon_bertingkat'])) {
					foreach ($each_diskon['diskon_bertingkat'] as $each_diskon_bertingkat) {
						$data = [
							'qty' => $each_diskon_bertingkat['qty'],
							'satuan' => $each_diskon_bertingkat['satuan'],
							'diskon_value' => $each_diskon_bertingkat['diskon_value'],
							'id_detail_diskon' => $id_detail_diskon,
						];
						$this->sales_campaign_detail_diskon_item->insert($data);
					}
				}
			}
		}

		if (count($this->input->post('sales_campaign_detail_diskon_global')) > 0) {
			$data = $this->getOnly([
				'nama_paket', 'qty', 'tipe_diskon', 'diskon_value', 'satuan'
			], $this->input->post('sales_campaign_detail_diskon_global'), [
				'id_campaign' => $id
			]);
			$this->sales_campaign_detail_diskon_global->insert_batch($data);
		}

		if (count($this->input->post('sales_campaign_detail_cashback')) > 0) {
			foreach ($this->input->post('sales_campaign_detail_cashback') as $detail_cashback) {
				$data = $this->get_in_array([
					'id_part', 'id_kelompok_part'
				], $detail_cashback, [
					'id_campaign' => $id
				]);
				$this->sales_campaign_detail_cashback->insert($data);
				$id_detail_cashback = $this->db->insert_id();
				if (isset($detail_cashback['detail_cashback_item'])) {
					foreach ($detail_cashback['detail_cashback_item'] as $item) {
						$data = [
							'id_detail_cashback' => $id_detail_cashback,
							'qty' => $item['qty'],
							'satuan' => $item['satuan'],
							'cashback' => $item['cashback'],
						];
						$this->sales_campaign_detail_cashback_item->insert($data);
					}
				}
			}
		}

		if (count($this->input->post('sales_campaign_detail_cashback_global')) > 0) {
			$sales_campaign_detail_cashback_global = $this->getOnly([
				'nama_paket', 'qty', 'cashback', 'satuan'
			], $this->input->post('sales_campaign_detail_cashback_global'), [
				'id_campaign' => $id
			]);
			$this->sales_campaign_detail_cashback_global->insert_batch($sales_campaign_detail_cashback_global);
		}

		if (count($this->input->post('sales_campaign_detail_gimmick')) > 0) {
			foreach ($this->input->post('sales_campaign_detail_gimmick') as $gimmick) {
				$data = $this->get_in_array([
					'id_part', 'id_kelompok_part', 'kelipatan_gimmick'
				], $gimmick, [
					'id_campaign' => $id
				]);
				$this->sales_campaign_detail_gimmick->insert($data);
				$id_detail_gimmick = $this->db->insert_id();
				if (isset($gimmick['detail_gimmick_item'])) {
					foreach ($gimmick['detail_gimmick_item'] as $item) {
						$data = [
							'id_detail_gimmick' => $id_detail_gimmick,
							'qty' => $item['qty'],
							'satuan' => $item['satuan'],
							'hadiah_part' => $item['hadiah_part'],
							'id_part' => $item['id_part'],
							'nama_hadiah' => $item['nama_hadiah'],
							'qty_hadiah' => $item['qty_hadiah'],
							'satuan_hadiah' => $item['satuan_hadiah'],
						];
						$this->sales_campaign_detail_gimmick_item->insert($data);
					}
				}
			}
		}

		if (count($this->input->post('sales_campaign_detail_gimmick_global')) > 0) {
			$sales_campaign_detail_gimmick_global = $this->getOnly([
				'nama_paket', 'qty', 'hadiah_part', 'id_part', 'nama_hadiah', 'qty_hadiah', 'satuan', 'satuan_hadiah'
			], $this->input->post('sales_campaign_detail_gimmick_global'), [
				'id_campaign' => $id
			]);
			$this->sales_campaign_detail_gimmick_global->insert_batch($sales_campaign_detail_gimmick_global);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$sales_campaign = $this->sales_campaign->find($id);
			send_json($sales_campaign);
		} else {
			$this->output->set_status_header(400);
		}
	}

	public function detail()
	{
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['sales_campaign'] = $this->db
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $this->input->get('id'))
			->get()->row();

		$data['sales_campaign_dealers'] = $this->db
			->select('scd.*')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('k.nama_lengkap')
			->from('ms_h3_md_sales_campaign_dealers as scd')
			->join('ms_dealer as d', 'd.id_dealer = scd.id_dealer')
			->join('ms_user as u', 'u.id_user = scd.actor_diskualifikasi', 'left')
			->join('ms_karyawan as k', 'k.id_karyawan = u.id_karyawan_dealer', 'left')
			->where('scd.id_campaign', $this->input->get('id'))
			->get()->result_array();

		$data['sales_campaign_detail_poin'] = $this->db
			->select('scdp.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->from('ms_h3_md_sales_campaign_detail_poin as scdp')
			->join('ms_part as p', 'p.id_part = scdp.id_part', 'left')
			->where('scdp.id_campaign', $this->input->get('id'))
			->order_by('scdp.poin', 'asc')
			->get()->result();


		$data['sales_campaign_detail_hadiah'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
			->where('scdh.id_campaign', $this->input->get('id'))
			->order_by('scdh.jumlah_poin', 'asc')
			->get()->result();

		$data['sales_campaign_detail_diskon'] = [];
		$detail_diskon = $this->db
			->select('scdd.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->from('ms_h3_md_sales_campaign_detail_diskon as scdd')
			->join('ms_part as p', 'p.id_part = scdd.id_part', 'left')
			->where('scdd.id_campaign', $this->input->get('id'))
			->get()->result_array();

		foreach ($detail_diskon as $each) {
			$each['diskon_bertingkat'] = $this->db
				->from('ms_h3_md_sales_campaign_detail_diskon_item as scddi')
				->where('scddi.id_detail_diskon', $each['id'])
				->get()->result_array();

			$data['sales_campaign_detail_diskon'][] = $each;
		}

		$data['sales_campaign_detail_diskon_global'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_diskon_global as scddg')
			->where('scddg.id_campaign', $this->input->get('id'))
			->get()->result();

		$sales_campaign_detail_cashback = $this->db
			->select('scdc.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->where('scdc.id_campaign', $this->input->get('id'))
			->from('ms_h3_md_sales_campaign_detail_cashback as scdc')
			->join('ms_part as p', 'p.id_part = scdc.id_part', 'left')
			->get()->result_array();
		$data['sales_campaign_detail_cashback'] = [];
		foreach ($sales_campaign_detail_cashback as $each_sales_campaign_detail_cashback) {
			$each_sales_campaign_detail_cashback['detail_cashback_item'] = $this->db
				->from('ms_h3_md_sales_campaign_detail_cashback_item as scdci')
				->where('scdci.id_detail_cashback', $each_sales_campaign_detail_cashback['id'])
				->get()->result_array();

			$data['sales_campaign_detail_cashback'][] = $each_sales_campaign_detail_cashback;
		}

		$data['sales_campaign_detail_cashback_global'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_cashback_global as scdg')
			->where('scdg.id_campaign', $this->input->get('id'))
			->order_by('scdg.qty', 'asc')
			->get()->result();

		$data['sales_campaign_detail_gimmick'] = [];
		$detail_gimmick = $this->db
			->select('scdg.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->from('ms_h3_md_sales_campaign_detail_gimmick as scdg')
			->join('ms_part as p', 'p.id_part = scdg.id_part', 'left')
			->where('scdg.id_campaign', $this->input->get('id'))
			->get()->result_array();

		foreach ($detail_gimmick as $each) {
			$each['detail_gimmick_item'] = $this->db
				->from('ms_h3_md_sales_campaign_detail_gimmick_item as scdgi')
				->where('scdgi.id_detail_gimmick', $each['id'])
				->get()->result_array();

			$data['sales_campaign_detail_gimmick'][] = $each;
		}


		$data['sales_campaign_detail_gimmick_global'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_gimmick_global as scdgg')
			->where('scdgg.id_campaign', $this->input->get('id'))
			->get()->result();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['sales_campaign'] = $this->db
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $this->input->get('id'))
			->get()->row();

		$data['sales_campaign_dealers'] = $this->db
			->select('scd.*')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('k.nama_lengkap')
			->from('ms_h3_md_sales_campaign_dealers as scd')
			->join('ms_dealer as d', 'd.id_dealer = scd.id_dealer')
			->join('ms_user as u', 'u.id_user = scd.actor_diskualifikasi', 'left')
			->join('ms_karyawan as k', 'k.id_karyawan = u.id_karyawan_dealer', 'left')
			->where('scd.id_campaign', $this->input->get('id'))
			->get()->result_array();

		$data['sales_campaign_detail_poin'] = $this->db
			->select('scdp.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->from('ms_h3_md_sales_campaign_detail_poin as scdp')
			->join('ms_part as p', 'p.id_part = scdp.id_part', 'left')
			->where('scdp.id_campaign', $this->input->get('id'))
			->get()->result();

		$data['sales_campaign_detail_hadiah'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
			->where('scdh.id_campaign', $this->input->get('id'))
			->get()->result();

		$data['sales_campaign_detail_diskon'] = [];
		$detail_diskon = $this->db
			->select('scdd.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->from('ms_h3_md_sales_campaign_detail_diskon as scdd')
			->join('ms_part as p', 'p.id_part = scdd.id_part', 'left')
			->where('scdd.id_campaign', $this->input->get('id'))
			->get()->result_array();

		foreach ($detail_diskon as $each) {
			$each['diskon_bertingkat'] = $this->db
				->from('ms_h3_md_sales_campaign_detail_diskon_item as scddi')
				->where('scddi.id_detail_diskon', $each['id'])
				->get()->result_array();

			$data['sales_campaign_detail_diskon'][] = $each;
		}


		$data['sales_campaign_detail_diskon_global'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_diskon_global as scddg')
			->where('scddg.id_campaign', $this->input->get('id'))
			->get()->result();

		$sales_campaign_detail_cashback = $this->db
			->select('scdc.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->from('ms_h3_md_sales_campaign_detail_cashback as scdc')
			->join('ms_part as p', 'p.id_part = scdc.id_part')
			->where('scdc.id_campaign', $this->input->get('id'))
			->get()->result_array();
		$data['sales_campaign_detail_cashback'] = [];
		foreach ($sales_campaign_detail_cashback as $each_sales_campaign_detail_cashback) {
			$each_sales_campaign_detail_cashback['detail_cashback_item'] = $this->db
				->from('ms_h3_md_sales_campaign_detail_cashback_item as scdci')
				->where('scdci.id_detail_cashback', $each_sales_campaign_detail_cashback['id'])
				->get()->result_array();
			$data['sales_campaign_detail_cashback'][] = $each_sales_campaign_detail_cashback;
		}

		$data['sales_campaign_detail_cashback_global'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_cashback_global as scdg')
			->where('scdg.id_campaign', $this->input->get('id'))
			->order_by('scdg.qty', 'asc')
			->get()->result();

		$data['sales_campaign_detail_gimmick'] = [];
		$detail_gimmick = $this->db
			->select('scdg.*')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('p.status')
			->select('
			concat(
				"Rp ",
				format(p.harga_dealer_user, 0, "ID_id")
			) as het
		', false)
			->from('ms_h3_md_sales_campaign_detail_gimmick as scdg')
			->join('ms_part as p', 'p.id_part = scdg.id_part', 'left')
			->where('scdg.id_campaign', $this->input->get('id'))
			->get()->result_array();

		foreach ($detail_gimmick as $each) {
			$each['detail_gimmick_item'] = $this->db
				->from('ms_h3_md_sales_campaign_detail_gimmick_item as scdgi')
				->where('scdgi.id_detail_gimmick', $each['id'])
				->get()->result_array();

			$data['sales_campaign_detail_gimmick'][] = $each;
		}


		$data['sales_campaign_detail_gimmick_global'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_gimmick_global as scdgg')
			->where('scdgg.id_campaign', $this->input->get('id'))
			->get()->result();

		$this->template($data);
	}

	public function update()
	{
		$this->validate();
		$this->db->trans_start();
		$post = $this->input->post([
			'kode_campaign', 'nama', 'start_date', 'end_date', 'kontribusi', 'jenis_reward_poin',
			'jenis_item_poin', 'reward_poin', 'produk_program_poin', 'start_date_poin', 'end_date_poin', 'satuan_rekapan_poin', 'jenis_reward_diskon',
			'jenis_item_diskon', 'jenis_diskon_campaign', 'produk_program_diskon', 'start_date_diskon', 'end_date_diskon', 'jenis_reward_cashback',
			'jenis_item_cashback', 'reward_cashback', 'produk_program_cashback', 'start_date_cashback', 'end_date_cashback', 'satuan_rekapan_cashback',
			'jenis_item_gimmick', 'jenis_reward_gimmick', 'reward_gimmick', 'produk_program_gimmick', 'start_date_gimmick', 'end_date_gimmick', 'satuan_rekapan_gimmick',
			'kelipatan_gimmick', 'nama_produk_campaign', 'mekanisme_program', 'kategori', 'active',
		]);

		$sales_campaign = $this->clean_data($post);

		$this->sales_campaign->update($sales_campaign, $this->input->post(['id']));

		$sc_dealers_in_database = $this->db
			->select('scd.id')
			->select('scd.id_dealer')
			->from('ms_h3_md_sales_campaign_dealers as scd')
			->where('scd.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sales_campaign_dealers = $this->input->post('sales_campaign_dealers');
		if (count($sales_campaign_dealers) > 0) {
			foreach ($sales_campaign_dealers as $sales_campaign_dealer) {
				foreach ($sc_dealers_in_database as $index => $row) {
					if ($row['id_dealer'] == $sales_campaign_dealer['id_dealer']) unset($sc_dealers_in_database[$index]);
				}

				$data = $this->get_in_array(['id_dealer'], $sales_campaign_dealer, [
					'id_campaign' => $this->input->post('id')
				]);

				if (!isset($sales_campaign_dealer['id'])) {
					$this->sales_campaign_dealers->insert($data);
				}
			}
		}

		foreach ($sc_dealers_in_database as $row) {
			$this->db
				->where('id', $row['id'])
				->delete('ms_h3_md_sales_campaign_dealers');
		}

		$detail_poin_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_poin as scdp')
			->where('scdp.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sales_campaign_detail_poin = $this->input->post('sales_campaign_detail_poin');
		if (count($sales_campaign_detail_poin) > 0) {
			foreach ($sales_campaign_detail_poin as $row) {
				foreach ($detail_poin_in_database as $index => $detail_poin_row) {
					if (
						$detail_poin_row['id_part'] == $row['id_part'] &&
						$detail_poin_row['id_kelompok_part'] == $row['id_kelompok_part']
					) {
						unset($detail_poin_in_database[$index]);
					}
				}

				$data = $this->get_in_array([
					'id_part', 'poin', 'id_kelompok_part', 'satuan'
				], $row);

				if (isset($row['id'])) {
					$this->sales_campaign_detail_poin->update($data, [
						'id' => $row['id']
					]);
				} else {
					$data['id_campaign'] = $this->input->post('id');
					$this->sales_campaign_detail_poin->insert($data);
				}
			}
		}

		foreach ($detail_poin_in_database as $row) {
			$this->sales_campaign_detail_poin->delete($row['id'], 'id');
		}

		$detail_hadiah_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
			->where('scdh.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sales_campaign_detail_hadiah = $this->input->post('sales_campaign_detail_hadiah');
		if (count($sales_campaign_detail_hadiah) > 0) {
			foreach ($sales_campaign_detail_hadiah as $detail_hadiah_row) {
				$found = false;
				foreach ($detail_hadiah_in_database as $index => $row) {
					if (
						$row['nama_paket'] == $detail_hadiah_row['nama_paket'] &&
						$row['jumlah_poin'] == $detail_hadiah_row['jumlah_poin']
					) {
						$found = true;
						unset($detail_hadiah_in_database[$index]);
						break;
					}
				}

				$data = $this->get_in_array(['jumlah_poin', 'nama_paket', 'nama_hadiah', 'voucher_rupiah'], $detail_hadiah_row);
				if (isset($detail_hadiah_row['id']) and $found) {
					$this->sales_campaign_detail_hadiah->update($data, [
						'id' => $detail_hadiah_row['id']
					]);
				} else {
					$data['id_campaign'] = $this->input->post('id');
					$this->sales_campaign_detail_hadiah->insert($data);
				}
			}
		}

		foreach ($detail_hadiah_in_database as $row) {
			$this->sales_campaign_detail_hadiah->delete($row['id']);
		}

		$detail_diskon_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_diskon as scdd')
			->where('scdd.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sales_campaign_detail_diskon = $this->input->post('sales_campaign_detail_diskon');
		if (count($sales_campaign_detail_diskon) > 0) {
			foreach ($sales_campaign_detail_diskon as $row_diskon) {
				foreach ($detail_diskon_in_database as $index => $row) {
					if (
						($row['id_part'] == $row_diskon['id_part']) or
						($row['id_kelompok_part']) == $row_diskon['id_kelompok_part']
					) {
						unset($detail_diskon_in_database[$index]);
						break;
					}
				}

				$data = $this->get_in_array(['id_part', 'id_kelompok_part', 'tipe_diskon'], $row_diskon, [
					'id_campaign' => $this->input->post('id')
				]);

				if (isset($row_diskon['id'])) {
					$this->sales_campaign_detail_diskon->update($data, [
						'id' => $row_diskon['id']
					]);
					$id_detail_diskon = $row_diskon['id'];
				} else {
					$this->sales_campaign_detail_diskon->insert($data);
					$id_detail_diskon = $this->db->insert_id();
				}

				if (isset($row_diskon['diskon_bertingkat'])) {
					$diskon_bertingkat_in_database = $this->db
						->from('ms_h3_md_sales_campaign_detail_diskon_item as scddi')
						->where('scddi.id_detail_diskon', $id_detail_diskon)
						->get()->result_array();

					foreach ($row_diskon['diskon_bertingkat'] as $row_diskon_bertingkat) {
						$found = false;
						foreach ($diskon_bertingkat_in_database as $index => $row) {
							if (
								($row['qty'] == $row_diskon_bertingkat['qty']) and
								($row['satuan'] == $row_diskon_bertingkat['satuan'])
							) {
								$found = true;
								unset($diskon_bertingkat_in_database[$index]);
								break;
							}
						}

						$data = [
							'qty' => $row_diskon_bertingkat['qty'],
							'satuan' => $row_diskon_bertingkat['satuan'],
							'diskon_value' => $row_diskon_bertingkat['diskon_value'],
						];
						if (isset($row_diskon_bertingkat['id']) and $found) {
							$this->sales_campaign_detail_diskon_item->update($data, [
								'id' => $row_diskon_bertingkat['id']
							]);
						} else {
							$data['id_detail_diskon'] = $id_detail_diskon;
							$this->sales_campaign_detail_diskon_item->insert($data);
						}
					}

					foreach ($diskon_bertingkat_in_database as $row) {
						$this->db
							->where('id', $row['id'])
							->delete('ms_h3_md_sales_campaign_detail_diskon_item');
					}
				}
			}
		}

		foreach ($detail_diskon_in_database as $row) {
			$this->db
				->where('id_detail_diskon', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_diskon_item');

			$this->db
				->where('id', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_diskon');
		}

		$diskon_global_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_diskon_global as scddg')
			->where('scddg.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sales_campaign_detail_diskon_global = $this->input->post('sales_campaign_detail_diskon_global');
		if (count($sales_campaign_detail_diskon_global) > 0) {
			foreach ($sales_campaign_detail_diskon_global as $row_global) {
				foreach ($diskon_global_in_database as $index => $row) {
					if (
						($row['nama_paket'] == $row_global['nama_paket'])
					) {
						unset($diskon_global_in_database[$index]);
					}
				}

				$data = $this->get_in_array(['nama_paket', 'qty', 'tipe_diskon', 'diskon_value', 'satuan'], $row_global);
				if (isset($row_global['id'])) {
					$this->sales_campaign_detail_diskon_global->update($data, [
						'id' => $row_global['id'],
					]);
				} else {
					$data['id_campaign'] = $this->input->post('id');
					$this->sales_campaign_detail_diskon_global->insert($data);
				}
			}
		}

		foreach ($diskon_global_in_database as $row) {
			$this->db
				->where('id', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_diskon_global');
		}

		$detail_cashback_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_cashback as scdc')
			->where('scdc.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sales_campaign_detail_cashback = $this->input->post('sales_campaign_detail_cashback');
		if (count($sales_campaign_detail_cashback) > 0) {
			foreach ($sales_campaign_detail_cashback as $detail_cashback) {
				foreach ($detail_cashback_in_database as $index => $row) {
					if (
						($row['id_part'] == $detail_cashback['id_part']) or
						($row['id_kelompok_part'] == $detail_cashback['id_kelompok_part'])
					) {
						unset($detail_cashback_in_database[$index]);
					}
				}
				$data = $this->get_in_array(['id_part', 'id_kelompok_part',], $detail_cashback, [
					'id_campaign' => $this->input->post('id')
				]);

				if (isset($detail_cashback['id'])) {
					$this->sales_campaign_detail_cashback->update($data, [
						'id' => $detail_cashback['id']
					]);
					$id_detail_cashback = $detail_cashback['id'];
				} else {
					$this->sales_campaign_detail_cashback->insert($data);
					$id_detail_cashback = $this->db->insert_id();
				}

				if (isset($detail_cashback['detail_cashback_item'])) {
					$detail_cashback_item_in_database = $this->db
						->from('ms_h3_md_sales_campaign_detail_cashback_item as scdci')
						->where('scdci.id_detail_cashback', $id_detail_cashback)
						->get()->result_array();

					foreach ($detail_cashback['detail_cashback_item'] as $item) {
						$found = false;
						foreach ($detail_cashback_item_in_database as $index => $row) {
							if (
								($row['qty'] == $item['qty']) and
								($row['satuan'] == $item['satuan'])
							) {
								$found = true;
								unset($detail_cashback_item_in_database[$index]);
								break;
							}
						}

						$data = $this->get_in_array(['qty', 'satuan', 'cashback'], $item);
						if (isset($item['id']) and $found) {
							$this->sales_campaign_detail_cashback_item->update($data, [
								'id' => $item['id']
							]);
						} else {
							$data['id_detail_cashback'] = $id_detail_cashback;
							$this->sales_campaign_detail_cashback_item->insert($data);
						}
					}

					foreach ($detail_cashback_item_in_database as $row) {
						$this->db
							->where('id', $row['id'])
							->delete('ms_h3_md_sales_campaign_detail_cashback_item');
					}
				}
			}
		}

		foreach ($detail_cashback_in_database as $row) {
			$this->db
				->where('id_detail_cashback', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_cashback_item');

			$this->db
				->where('id', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_cashback');
		}

		$cashback_global_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_cashback_global as scdcg')
			->where('scdcg.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sales_campaign_detail_cashback_global = $this->input->post('sales_campaign_detail_cashback_global');
		if (count($sales_campaign_detail_cashback_global) > 0) {
			foreach ($sales_campaign_detail_cashback_global as $row_global) {
				foreach ($cashback_global_in_database as $index => $row) {
					if (
						($row['nama_paket'] == $row_global['nama_paket'])
					) {
						unset($cashback_global_in_database[$index]);
						break;
					}
				}

				$data = $this->get_in_array(['nama_paket', 'qty', 'cashback', 'satuan'], $row_global);
				if (isset($row_global['id'])) {
					$this->sales_campaign_detail_cashback_global->update($data, [
						'id' => $row_global['id']
					]);
				} else {
					$data['id_campaign'] = $this->input->post('id');
					$this->sales_campaign_detail_cashback_global->insert($data);
				}
			}
		}

		foreach ($cashback_global_in_database as $row) {
			$this->db
				->where('id', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_cashback_global');
		}

		// Ambil data detail gimmick yang ada di database, untuk keperluan sync data
		$sc_detail_gimmick_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_gimmick as scdg')
			->where('scdg.id_campaign', $this->input->post('id'))
			->get()->result_array();

		if (count($this->input->post('sales_campaign_detail_gimmick')) > 0) {
			foreach ($this->input->post('sales_campaign_detail_gimmick') as $gimmick) {
				// Untuk data yang ditemukan di data post, dihapus dari list data yang akan dihapus (sync).
				foreach ($sc_detail_gimmick_in_database as $index => $row) {
					if (
						($row['id_part'] != null && $row['id_part'] == $gimmick['id_part']) ||
						($row['id_kelompok_part'] != null && $row['id_kelompok_part'] == $gimmick['id_kelompok_part'])
					) {
						unset($sc_detail_gimmick_in_database[$index]);
					}
				}

				$data = $this->get_in_array(['id_part', 'id_kelompok_part', 'kelipatan_gimmick'], $gimmick, ['id_campaign' => $this->input->post('id')]);

				if (isset($gimmick['id'])) {
					$this->sales_campaign_detail_gimmick->update($data, [
						'id' => $gimmick['id']
					]);
				} else {
					$this->sales_campaign_detail_gimmick->insert($data);
				}

				$detail_gimmick = $this->db
					->from('ms_h3_md_sales_campaign_detail_gimmick as scdg')
					->group_start()
					->where('scdg.id_part', $gimmick['id_part'])
					->or_where('scdg.id_kelompok_part', $gimmick['id_kelompok_part'])
					->group_end()
					->where('scdg.id_campaign', $this->input->post('id'))
					->limit(1)
					->get()->row_array();

				if (isset($gimmick['detail_gimmick_item'])) {
					$sc_detail_gimmick_item_in_database = $this->db
						->from('ms_h3_md_sales_campaign_detail_gimmick_item as scdgi')
						->where('scdgi.id_detail_gimmick', $detail_gimmick['id'])
						->get()->result_array();

					foreach ($gimmick['detail_gimmick_item'] as $item) {
						foreach ($sc_detail_gimmick_item_in_database as $index => $row) {
							if (
								$row['id_detail_gimmick'] == $detail_gimmick['id'] &&
								$row['qty'] == $item['qty'] &&
								$row['satuan'] == $item['satuan'] &&
								$row['qty_hadiah'] == $item['qty_hadiah'] &&
								$row['satuan_hadiah'] == $item['satuan_hadiah']
							) {
								unset($sc_detail_gimmick_item_in_database[$index]);
							}
						}

						if (isset($item['id'])) {
							$data = [
								'id_detail_gimmick' => $detail_gimmick['id'],
								'qty' => $item['qty'],
								'hadiah_part' => $item['hadiah_part'],
								'satuan' => $item['satuan'],
								'id_part' => $item['id_part'],
								'nama_hadiah' => $item['nama_hadiah'],
								'qty_hadiah' => $item['qty_hadiah'],
								'satuan_hadiah' => $item['satuan_hadiah'],
							];
							$this->sales_campaign_detail_gimmick_item->update($data, [
								'id' => $item['id']
							]);
						} else {
							$data = [
								'id_detail_gimmick' => $detail_gimmick['id'],
								'qty' => $item['qty'],
								'hadiah_part' => $item['hadiah_part'],
								'satuan' => $item['satuan'],
								'id_part' => $item['id_part'],
								'nama_hadiah' => $item['nama_hadiah'],
								'qty_hadiah' => $item['qty_hadiah'],
								'satuan_hadiah' => $item['satuan_hadiah'],
							];
							$this->sales_campaign_detail_gimmick_item->insert($data);
						}
					}

					foreach ($sc_detail_gimmick_item_in_database as $row) {
						$this->db
							->where('id', $row['id'])
							->delete('ms_h3_md_sales_campaign_detail_gimmick_item');
					}
				}
			}
		}

		foreach ($sc_detail_gimmick_in_database as $row) {
			$this->db
				->where('id_detail_gimmick', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_gimmick_item');

			$this->db
				->where('id', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_gimmick');
		}

		$sc_gimmick_global_in_database = $this->db
			->from('ms_h3_md_sales_campaign_detail_gimmick_global as scdgg')
			->where('scdgg.id_campaign', $this->input->post('id'))
			->get()->result_array();

		$sc_detail_gimmick_globals = $this->input->post('sales_campaign_detail_gimmick_global');
		if (count($sc_detail_gimmick_globals) > 0) {
			foreach ($sc_detail_gimmick_globals as $sc_detail_gimmick_global) {
				foreach ($sc_gimmick_global_in_database as $index => $row) {
					if (
						($row['nama_paket'] == $sc_detail_gimmick_global['nama_paket']) &&
						($row['nama_hadiah'] == $sc_detail_gimmick_global['nama_hadiah'])
					) {
						unset($sc_gimmick_global_in_database[$index]);
					}
				}

				$data = $this->get_in_array([
					'nama_paket', 'qty', 'hadiah_part', 'id_part',
					'nama_hadiah', 'qty_hadiah', 'satuan', 'satuan_hadiah'
				], $sc_detail_gimmick_global);
				if (isset($sc_detail_gimmick_global['id'])) {
					$this->sales_campaign_detail_gimmick_global->update($data, ['id' => $sc_detail_gimmick_global['id']]);
				} else {
					$data['id_campaign'] = $this->input->post('id');
					$this->sales_campaign_detail_gimmick_global->insert($data);
				}
			}
		}

		foreach ($sc_gimmick_global_in_database as $row) {
			$this->db
				->where('id', $row['id'])
				->delete('ms_h3_md_sales_campaign_detail_gimmick_global');
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$sales_campaign = $this->sales_campaign->find($this->input->post('id'));
			send_json($sales_campaign);
		} else {
			$this->output->set_status_header(400);
		}
	}

	public function generate_perolehan_poin_tidak_langsung()
	{
		$this->load->model('H3_md_data_perolehan_poin_tidak_langsung_model', 'data_perolehan_poin');
		$this->load->model('H3_md_perolehan_sales_campaign_poin_tidak_langsung_model', 'perolehan_sales_campaign_poin');
		$this->load->model('H3_md_perolehan_sales_campaign_poin_tidak_langsung_penjualan_perbulan_model', 'perolehan_sales_campaign_poin_perbulan');
		$this->load->model('H3_md_perolehan_sales_campaign_poin_tidak_langsung_detail_model', 'perolehan_sales_campaign_poin_detail');
		$this->load->model('H3_md_perolehan_sales_campaign_poin_tidak_langsung_hadiah_model', 'perolehan_sales_campaign_poin_hadiah');

		$id_campaign = $this->input->get('id');
		$sales_campaign = $this->db
			->select('sc.id')
			->select('sc.reward_poin')
			->select('sc.jenis_item_poin')
			->select('sc.start_date')
			->select('sc.end_date')
			->select('sc.start_date_poin')
			->select('sc.end_date_poin')
			->select('sc.produk_program_poin')
			->from('ms_h3_md_sales_campaign as sc ')
			->where('sc.id', $id_campaign)
			->where('sc.jenis_reward_poin', 1)
			->where('sc.reward_poin', 'Tidak Langsung')
			->get()->row_array();

		if ($sales_campaign == null) {
			send_json([
				'message' => 'Sales Campaign Tidak ditemukan.'
			], 422);
		}

		$this->db->trans_start();

		$dealers = $this->data_perolehan_poin->global_get($id_campaign);

		foreach ($dealers as $dealer) {
			$condition = [
				'id_dealer' => $dealer['id_dealer'],
				'id_campaign' => $id_campaign
			];

			$perolehan = (array) $this->perolehan_sales_campaign_poin->get($condition, true);

			if ($perolehan != null) {
				$this->perolehan_sales_campaign_poin->update([
					'total_penjualan_per_dealer' => $dealer['total_penjualan_per_dealer'],
					'total_poin_penjualan_per_dealer' => $dealer['total_poin_penjualan_per_dealer'],
					'total_insentif' => $dealer['total_insentif'],
					'total_bayar' => $dealer['total_bayar'],
					'ppn' => $dealer['ppn'],
					'nilai_kw' => $dealer['nilai_kw'],
					'pph_23' => $dealer['pph_23'],
					'pph_21' => $dealer['pph_21'],
					'sisa_poin' => $dealer['sisa_poin'],
				], $condition);
				$id_perolehan = $perolehan['id'];
			} else {
				$condition['total_penjualan_per_dealer'] = $dealer['total_penjualan_per_dealer'];
				$condition['total_poin_penjualan_per_dealer'] = $dealer['total_poin_penjualan_per_dealer'];
				$condition['total_insentif'] = $dealer['total_insentif'];
				$condition['total_bayar'] = $dealer['total_bayar'];
				$condition['ppn'] = $dealer['ppn'];
				$condition['nilai_kw'] = $dealer['nilai_kw'];
				$condition['pph_23'] = $dealer['pph_23'];
				$condition['pph_21'] = $dealer['pph_21'];
				$condition['sisa_poin'] = $dealer['sisa_poin'];
				$this->perolehan_sales_campaign_poin->insert($condition);
				$id_perolehan = $this->db->insert_id();
			}

			foreach ($dealer['hadiah'] as $row_hadiah) {
				$condition = [
					'id_perolehan' => $id_perolehan,
					'id_campaign' => $id_campaign,
					'id_hadiah' => $row_hadiah['id']
				];

				$hadiah = (array) $this->perolehan_sales_campaign_poin_hadiah->get($condition, true);

				if ($hadiah != null) {
					$this->perolehan_sales_campaign_poin_hadiah->update([
						'count_hadiah' => $row_hadiah['count_hadiah'],
					], $condition);
				} else {
					$condition['count_hadiah'] = $row_hadiah['count_hadiah'];
					$this->perolehan_sales_campaign_poin_hadiah->insert($condition);
				}
			}

			foreach ($dealer['months'] as $month) {
				$date = Mcarbon::parse($month['start_date']);
				$condition = [
					'bulan' => $date->format('m'),
					'tahun' => $date->format('Y'),
					'id_perolehan' => $id_perolehan
				];
				$perbulan = (array) $this->perolehan_sales_campaign_poin_perbulan->get($condition, true);

				if ($perbulan != null) {
					$this->perolehan_sales_campaign_poin_perbulan->update([
						'total_penjualan_per_bulan' => $month['total_penjualan_per_bulan'],
						'total_poin_penjualan_per_bulan' => $month['total_poin_penjualan_per_bulan']
					], $condition);
					$id_perbulan = $perolehan['id'];
				} else {
					$condition['total_penjualan_per_bulan'] = $month['total_penjualan_per_bulan'];
					$condition['total_poin_penjualan_per_bulan'] = $month['total_poin_penjualan_per_bulan'];
					$this->perolehan_sales_campaign_poin_perbulan->insert($condition);
					$id_perbulan = $this->db->insert_id();
				}

				foreach ($month['sales_campaign_details'] as $sales_campaign_detail) {
					$condition = [
						'id_perolehan' => $id_perolehan,
						'id_perbulan' => $id_perbulan,
						'id_campaign' => $id_campaign,
						'id_detail' => $sales_campaign_detail['id_detail'],
					];
					$detail = (array) $this->perolehan_sales_campaign_poin_detail->get($condition, true);

					if ($detail != null) {
						$this->perolehan_sales_campaign_poin_detail->update([
							'total_kuantitas_penjualan' => $sales_campaign_detail['total_kuantitas_penjualan']
						], $condition);
						$id_perbulan = $perolehan['id'];
					} else {
						$condition['total_kuantitas_penjualan'] = $sales_campaign_detail['total_kuantitas_penjualan'];
						$this->perolehan_sales_campaign_poin_detail->insert($condition);
						$id_perbulan = $this->db->insert_id();
					}
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Poin berhasil dihitung dan digenerate.');
			$this->session->set_userdata('tipe', 'info');
		} else {
			$this->session->set_userdata('pesan', 'Poin tidak berhasil dihitung dan digenerate.');
			$this->session->set_userdata('tipe', 'danger');
		}
		redirect(
			base_url('h3/h3_md_ms_sales_campaign/insentif_poin?id=' . $id_campaign)
		);
	}

	public function insentif_poin()
	{
		$data['set'] = "insentif_poin";

		$data['sales_campaign'] = $this->db
			->select('sc.id')
			->select('sc.nama')
			->select('
			CASE
				WHEN sc.start_date_gimmick IS NOT NULL THEN sc.start_date_gimmick
				ELSE sc.start_date
			END AS start_date
		', false)
			->select('
			CASE
				WHEN sc.end_date_gimmick IS NOT NULL THEN sc.end_date_gimmick
				ELSE sc.end_date
			END AS end_date
		', false)
			->select('sc.produk_program_poin')
			->select('sc.sudah_proses_insentif')
			->select('sc.proses_ke_finance')
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $this->input->get('id'))
			->get()->row_array();

		$data['sales_campaign_hadiah'] = $this->db
			->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
			->where('scdh.id_campaign', $this->input->get('id'))
			->order_by('scdh.jumlah_poin', 'asc')
			->get()->result_array();

		$this->template($data);
	}

	public function download_insentif_poin(){
		$this->load->model('H3_md_laporan_poin_sales_campaign_model', 'laporan_poin_sales_campaign');
		
		$id_campaign = $this->input->get('id');

		try {
			$this->laporan_poin_sales_campaign->laporan($id_campaign);
		} catch (Exception $e) {
			log_message('error', $e);

			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'warning');

			redirect(
				base_url("h3/h3_md_ms_sales_campaign/insentif_poin?id={$id_campaign}")
			);
		}
	}

	public function proses_ke_finance_cashback($id){
		$this->db->trans_begin();
		try {
			$this->sales_campaign->find($id);
			$this->sales_campaign->update([ 'proses_ke_finance' => 1 ], ['id' => $id]);

			$this->db->trans_commit();

			$this->session->set_userdata('pesan', 'Berhasil proses ke finance');
			$this->session->set_userdata('tipe', 'warning');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', $e);
			
			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'warning');
		}

		redirect(
			base_url('h3/h3_md_ms_sales_campaign/insentif_cashback?id=' . $id)
		);
	}

	public function proses_ke_finance_poin($id){
		$this->db->trans_begin();
		try {
			$this->sales_campaign->find($id);
			$this->sales_campaign->update([ 'proses_ke_finance' => 1 ], ['id' => $id]);

			$this->db->trans_commit();

			$this->session->set_userdata('pesan', 'Berhasil proses ke finance');
			$this->session->set_userdata('tipe', 'warning');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', $e);
			
			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'warning');
		}

		redirect(
			base_url('h3/h3_md_ms_sales_campaign/insentif_poin?id=' . $id)
		);
	}

	public function update_pph21_poin(){
		$parts = $this->input->post('parts');

		$this->db->trans_start();
		if(count($parts) > 0){
			foreach($parts as $part){
				$this->db
				->set('pscp.pph_21', $part['pph_21'])
				->set('pscp.total_bayar', '(pscp.nilai_kw - pscp.pph_23 - pscp.pph_21)', false)
				->where('pscp.id', $part['id'])
				->update('tr_h3_md_perolehan_sales_campaign_poin_tidak_langsung as pscp');
			}
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Berhasil memperbarui PPH 21'
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil memperbarui PPH 21'
			], 422);
		}
	}

	public function update_pph21_cashback(){
		$parts = $this->input->post('parts');

		$this->db->trans_start();
		if(count($parts) > 0){
			foreach($parts as $part){
				$this->db
				->set('pscc.pph_21', $part['pph_21'])
				->set('pscc.total_bayar', '(pscc.nilai_kw - pscc.pph_23 - pscc.pph_21)', false)
				->where('pscc.id', $part['id'])
				->update('tr_h3_perolehan_sales_campaign_cashback_tidak_langsung as pscc');
			}
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Berhasil memperbarui PPH 21'
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil memperbarui PPH 21'
			], 422);
		}
	}

	public function proses_insentif_poin()
	{
		$this->load->model('H3_md_create_ap_part_sales_campaign_insentif_poin_model', 'create_ap_part_sales_campaign_insentif_poin');

		$id_campaign = $this->input->get('id');
		$rekap = $this->input->get('rekap') != null;

		$this->db->trans_begin();

		try {
			$this->create_ap_part_sales_campaign_insentif_poin->proses($id_campaign, $rekap);
			
			$this->db->trans_commit();

			$this->session->set_userdata('pesan', 'Sales Campaign berhasil di proses insentif nya.');
			$this->session->set_userdata('tipe', 'info');
		} catch (Exception $e) {
			log_message('error', $e);
			$this->db->trans_rollback();

			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'warning');
		}

		redirect(
			base_url("h3/h3_md_ms_sales_campaign/insentif_poin?id={$id_campaign}")
		);
	}

	public function proses_insentif_cashback()
	{
		$this->load->model('H3_md_create_ap_part_sales_campaign_insentif_cashback_model', 'create_ap_part_sales_campaign_insentif_cashback');

		$id_campaign = $this->input->get('id');
		$rekap = $this->input->get('rekap') != null;

		$this->db->trans_begin();

		try {
			$this->create_ap_part_sales_campaign_insentif_cashback->proses($id_campaign, $rekap);
			
			$this->db->trans_commit();

			$this->session->set_userdata('pesan', 'Sales Campaign berhasil di proses insentif nya.');
			$this->session->set_userdata('tipe', 'info');
		} catch (Exception $e) {
			log_message('error', $e);
			$this->db->trans_rollback();

			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'warning');
		}

		redirect(
			base_url("h3/h3_md_ms_sales_campaign/insentif_cashback?id={$id_campaign}")
		);
	}

	public function generate_hadiah()
	{
		$this->load->model('H3_md_pencatatan_hadiah_sales_campaign_model', 'pencatatan_hadiah_sales_campaign');

		$sales_campaign_sudah_generate_hadiah = $this->db
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $this->input->get('id'))
			->where('sc.sudah_generate_hadiah', 1)
			->get()->row_array();

		if ($sales_campaign_sudah_generate_hadiah != null) {
			$this->session->set_flashdata('pesan', "Sales Campaign ini sudah pernah generate hadiah poin.");
			$this->session->set_flashdata('tipe', 'warning');
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page/detail?id={$this->input->get('id')}'>";
			die();
		}

		$this->db
			->set('sc.sudah_generate_hadiah', 1)
			->where('sc.id', $this->input->get('id'))
			->update('ms_h3_md_sales_campaign as sc');

		$this->pencatatan_hadiah_sales_campaign->generate_pencatatan_hadiah($this->input->get('id'));

		$this->session->set_flashdata('pesan', "Sales Campaign berhasil generate hadiah poin.");
		$this->session->set_flashdata('tipe', 'info');
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page/detail?id={$this->input->get('id')}'>";
		die();
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($this->uri->segment(3) == 'update') {
			$sales_campaign = $this->sales_campaign->find($this->input->post('id'));

			if (
				!($sales_campaign->kode_campaign == $this->input->post('kode_campaign'))
			) {
				$this->form_validation->set_rules('kode_campaign', 'Kode Campaign', 'required|is_unique[ms_h3_md_sales_campaign.kode_campaign]');
			}
		} else {
			$this->form_validation->set_rules('kode_campaign', 'Kode Campaign', 'required|is_unique[ms_h3_md_sales_campaign.kode_campaign]');
		}
		$this->form_validation->set_rules('start_date', 'Periode', 'required');
		$this->form_validation->set_rules('nama', 'Nama Campaign', 'required|max_length[60]');
		$this->form_validation->set_rules('kontribusi', 'Kontribusi', 'required');
		$this->form_validation->set_rules('mekanisme_program', 'Mekanisme Program', 'required');
		$this->form_validation->set_rules('kategori', 'Kategori', 'required');
		$this->form_validation->set_rules('nama_produk_campaign', 'Nama Produk Campaign', 'required|max_length[60]');

		if ($this->input->post('jenis_reward_poin') == 1) {
			$this->form_validation->set_rules('reward_poin', 'Reward', 'required');
			$this->form_validation->set_rules('produk_program_poin', 'Produk Program', 'required');
			$this->form_validation->set_rules('satuan_rekapan_poin', 'Satuan Rekapan', 'required');
			// if($this->input->post('start_date') == null){
			// 	$this->form_validation->set_rules('start_date_poin', 'Periode', 'required');
			// }
		}

		if ($this->input->post('jenis_reward_diskon') == 1) {
			$this->form_validation->set_rules('jenis_diskon_campaign', 'Jenis Diskon Campaign', 'required');
			$this->form_validation->set_rules('produk_program_diskon', 'Produk Program', 'required');
			// if($this->input->post('start_date') == null){
			// 	$this->form_validation->set_rules('start_date_diskon', 'Periode', 'required');
			// }

		}

		if ($this->input->post('jenis_reward_cashback') == 1) {
			$this->form_validation->set_rules('reward_cashback', 'Reward', 'required');
			$this->form_validation->set_rules('produk_program_cashback', 'Produk Program', 'required');
			$this->form_validation->set_rules('satuan_rekapan_cashback', 'Satuan Rekapan', 'required');
			// if($this->input->post('start_date') == null){
			// 	$this->form_validation->set_rules('start_date_cashback', 'Periode', 'required');
			// }

		}

		if ($this->input->post('jenis_reward_gimmick') == 1) {
			$this->form_validation->set_rules('reward_gimmick', 'Reward', 'required');
			$this->form_validation->set_rules('produk_program_gimmick', 'Produk Program', 'required');
			// if($this->input->post('start_date') == null){
			// 	$this->form_validation->set_rules('start_date_gimmick', 'Periode', 'required');
			// }

		}

		if (!$this->form_validation->run()) {
			$this->output->set_status_header(400);
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			]);
		}
	}

	public function report_hadiah()
	{
		$this->load->model('H3_md_report_pencairan_poin_model', 'report_pencairan_poin');

		$this->report_pencairan_poin->generate(
			$this->input->get('id')
		);
	}

	public function generate_laporan_gimmick_tidak_langsung()
	{
		$this->load->model('H3_md_perolehan_sales_campaign_gimmick_tidak_langsung_dealer_model', 'perolehan_sales_campaign_gimmick_tidak_langsung_dealer');
		$this->load->model('H3_md_perolehan_sales_campaign_gimmick_tidak_langsung_details_model', 'perolehan_sales_campaign_gimmick_tidak_langsung_details');
		$this->load->model('H3_md_perolehan_sales_campaign_gimmick_tidak_langsung_global_model', 'perolehan_sales_campaign_gimmick_tidak_langsung_global');
		$this->load->model('H3_md_perolehan_sales_campaign_gimmick_tidak_langsung_per_item_model', 'perolehan_sales_campaign_gimmick_tidak_langsung_per_item');

		$id_campaign = $this->input->get('id');
		$sales_campaign = $this->db
			->select('sc.id')
			->select('sc.reward_gimmick')
			->select('sc.jenis_item_gimmick')
			->select('sc.start_date')
			->select('sc.end_date')
			->select('sc.start_date_gimmick')
			->select('sc.end_date_gimmick')
			->select('sc.produk_program_gimmick')
			->select('sc.kelipatan_gimmick')
			->from('ms_h3_md_sales_campaign as sc ')
			->where('sc.id', $id_campaign)
			->where('sc.reward_gimmick', 'Tidak Langsung')
			->get()->row_array();

		if ($sales_campaign == null) {
			$this->session->set_userdata('pesan', 'Sales campaign gimmick tidak ditemukan');
			$this->session->set_userdata('tipe', 'danger');
			
			redirect(
				base_url('h3/h3_md_ms_sales_campaign/perolehan_gimmick?id=' . $id_campaign)
			);
		}

		$dealers = $this->get_gimmick_data($id_campaign);

		$this->db->trans_start();

		if ($sales_campaign['produk_program_gimmick'] == 'Global') :
			foreach ($dealers as $dealer) {
				$condition = [
					'id_dealer' => $dealer['id_dealer'],
					'id_campaign' => $dealer['id_campaign'],
				];
				$perolehan_dealer = (array) $this->perolehan_sales_campaign_gimmick_tidak_langsung_dealer->get($condition, true);

				if ($perolehan_dealer != null) {
					$this->perolehan_sales_campaign_gimmick_tidak_langsung_dealer->update([
						'total_pembelian' => $dealer['total_pembelian'],
						'total_pembelian_dus' => $dealer['total_pembelian_dus'],
						'total_pembelian_sisa' => $dealer['total_pembelian_sisa'],
						'total_pembelian_dus_sisa' => $dealer['total_pembelian_dus_sisa'],
					], [
						'id' => $perolehan_dealer['id']
					]);
					$id_perolehan = $perolehan_dealer['id'];
				} else {
					$this->perolehan_sales_campaign_gimmick_tidak_langsung_dealer->insert([
						'id_dealer' => $dealer['id_dealer'],
						'id_campaign' => $dealer['id_campaign'],
						'total_pembelian' => $dealer['total_pembelian'],
						'total_pembelian_dus' => $dealer['total_pembelian_dus'],
						'total_pembelian_sisa' => $dealer['total_pembelian_sisa'],
						'total_pembelian_dus_sisa' => $dealer['total_pembelian_dus_sisa'],
					]);
					$id_perolehan = $this->db->insert_id();
				}

				foreach ($dealer['sales_campaign_details'] as $sales_campaign_detail) {
					$condition = [
						'id_perolehan' => $id_perolehan,
						'id_campaign' => $dealer['id_campaign'],
						'id_detail' => $sales_campaign_detail['id_detail'],
					];
					$perolehan_detail = (array) $this->perolehan_sales_campaign_gimmick_tidak_langsung_details->get($condition, true);

					if ($perolehan_detail != null) {
						$this->perolehan_sales_campaign_gimmick_tidak_langsung_details->update([
							'id_part' => $sales_campaign_detail['id_part'],
							'id_kelompok_part' => $sales_campaign_detail['id_kelompok_part'],
							'jumlah_kuantitas_yang_tercapai' => $sales_campaign_detail['jumlah_kuantitas_yang_tercapai'],
							'jumlah_dus_yang_tercapai' => $sales_campaign_detail['jumlah_dus_yang_tercapai'],
						], [
							'id' => $perolehan_detail['id']
						]);
					} else {
						$this->perolehan_sales_campaign_gimmick_tidak_langsung_details->insert([
							'id_perolehan' => $id_perolehan,
							'id_campaign' => $dealer['id_campaign'],
							'id_detail' => $sales_campaign_detail['id_detail'],
							'id_part' => $sales_campaign_detail['id_part'],
							'id_kelompok_part' => $sales_campaign_detail['id_kelompok_part'],
							'jumlah_kuantitas_yang_tercapai' => $sales_campaign_detail['jumlah_kuantitas_yang_tercapai'],
							'jumlah_dus_yang_tercapai' => $sales_campaign_detail['jumlah_dus_yang_tercapai'],
						]);
					}
				}

				foreach ($dealer['sales_campaign_gimmick_globals'] as $sales_campaign_gimmick_global) {
					$condition = [
						'id_perolehan' => $id_perolehan,
						'id_campaign' => $dealer['id_campaign'],
						'id_gimmick_global' => $sales_campaign_gimmick_global['id_gimmick_global'],
					];
					$perolehan_global = (array) $this->perolehan_sales_campaign_gimmick_tidak_langsung_global->get($condition, true);

					if ($perolehan_global != null) {
						$this->perolehan_sales_campaign_gimmick_tidak_langsung_global->update([
							'count_gimmick' => $sales_campaign_gimmick_global['count_gimmick'],
						], [
							'id' => $perolehan_global['id']
						]);
					} else {
						$this->perolehan_sales_campaign_gimmick_tidak_langsung_global->insert([
							'id_perolehan' => $id_perolehan,
							'id_campaign' => $dealer['id_campaign'],
							'id_gimmick_global' => $sales_campaign_gimmick_global['id_gimmick_global'],
							'count_gimmick' => $sales_campaign_gimmick_global['count_gimmick'],
						]);
					}
				}
			}

		elseif ($sales_campaign['produk_program_gimmick'] == 'Per Item') :
			foreach ($dealers as $dealer) {
				$condition = [
					'id_dealer' => $dealer['id_dealer'],
					'id_campaign' => $dealer['id_campaign'],
				];
				$perolehan_dealer = (array) $this->perolehan_sales_campaign_gimmick_tidak_langsung_dealer->get($condition, true);

				if ($perolehan_dealer != null) {
					$id_perolehan = $perolehan_dealer['id'];
				} else {
					$this->perolehan_sales_campaign_gimmick_tidak_langsung_dealer->insert($condition);

					$id_perolehan = $this->db->insert_id();
				}

				foreach ($dealer['sales_campaign_details'] as $sales_campaign_detail) {
					$condition = [
						'id_perolehan' => $id_perolehan,
						'id_campaign' => $dealer['id_campaign'],
						'id_detail' => $sales_campaign_detail['id_detail'],
					];
					$perolehan_detail = (array) $this->perolehan_sales_campaign_gimmick_tidak_langsung_details->get($condition, true);

					if ($perolehan_detail != null) {
						$this->perolehan_sales_campaign_gimmick_tidak_langsung_details->update([
							'id_part' => $sales_campaign_detail['id_part'],
							'id_kelompok_part' => $sales_campaign_detail['id_kelompok_part'],
							'jumlah_kuantitas_yang_tercapai' => $sales_campaign_detail['jumlah_kuantitas_yang_tercapai'],
							'jumlah_dus_yang_tercapai' => $sales_campaign_detail['jumlah_dus_yang_tercapai'],
							'jumlah_kuantitas_yang_tercapai_sisa' => $sales_campaign_detail['jumlah_kuantitas_yang_tercapai_sisa'],
							'jumlah_dus_yang_tercapai_sisa' => $sales_campaign_detail['jumlah_dus_yang_tercapai_sisa'],
						], [
							'id' => $perolehan_detail['id']
						]);
					} else {
						$this->perolehan_sales_campaign_gimmick_tidak_langsung_details->insert([
							'id_perolehan' => $id_perolehan,
							'id_campaign' => $dealer['id_campaign'],
							'id_detail' => $sales_campaign_detail['id_detail'],
							'id_part' => $sales_campaign_detail['id_part'],
							'id_kelompok_part' => $sales_campaign_detail['id_kelompok_part'],
							'jumlah_kuantitas_yang_tercapai' => $sales_campaign_detail['jumlah_kuantitas_yang_tercapai'],
							'jumlah_dus_yang_tercapai' => $sales_campaign_detail['jumlah_dus_yang_tercapai'],
							'jumlah_kuantitas_yang_tercapai_sisa' => $sales_campaign_detail['jumlah_kuantitas_yang_tercapai_sisa'],
							'jumlah_dus_yang_tercapai_sisa' => $sales_campaign_detail['jumlah_dus_yang_tercapai_sisa'],
						]);
					}

					foreach ($sales_campaign_detail['sc_gimmick_items'] as $sc_gimmick_item) {
						$condition = [
							'id_perolehan' => $id_perolehan,
							'id_campaign' => $dealer['id_campaign'],
							'id_detail' => $sales_campaign_detail['id_detail'],
							'id_gimmick_item' => $sc_gimmick_item['id_gimmick_item'],
						];
						$perolehan_item = (array) $this->perolehan_sales_campaign_gimmick_tidak_langsung_per_item->get($condition, true);

						if ($perolehan_item != null) {
							$this->perolehan_sales_campaign_gimmick_tidak_langsung_per_item->update([
								'count_gimmick' => $sc_gimmick_item['count_gimmick'],
							], [
								'id' => $perolehan_item['id']
							]);
						} else {
							$this->perolehan_sales_campaign_gimmick_tidak_langsung_per_item->insert([
								'id_perolehan' => $id_perolehan,
								'id_campaign' => $dealer['id_campaign'],
								'id_detail' => $sales_campaign_detail['id_detail'],
								'id_gimmick_item' => $sc_gimmick_item['id_gimmick_item'],
								'count_gimmick' => $sc_gimmick_item['count_gimmick'],
							]);
						}
					}
				}
			}
		endif;
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Gimmick berhasil dihitung dan digenerate.');
			$this->session->set_userdata('tipe', 'info');
		} else {
			$this->session->set_userdata('pesan', 'Gimmick tidak berhasil dihitung dan digenerate.');
			$this->session->set_userdata('tipe', 'danger');
		}

		redirect(
			base_url("h3/h3_md_ms_sales_campaign/perolehan_gimmick?id={$this->input->get('id')}")
		);
	}

	public function get_gimmick_data($id_campaign)
	{
		$this->load->model('H3_md_data_perolehan_gimmick_tidak_langsung_model', 'data_perolehan_gimmick_tidak_langsung');

		$sales_campaign = $this->db
			->select('sc.id')
			->select('sc.reward_gimmick')
			->select('sc.jenis_item_gimmick')
			->select('sc.start_date')
			->select('sc.end_date')
			->select('sc.start_date_gimmick')
			->select('sc.end_date_gimmick')
			->select('sc.produk_program_gimmick')
			->from('ms_h3_md_sales_campaign as sc ')
			->where('sc.id', $id_campaign)
			->where('sc.reward_gimmick', 'Tidak Langsung')
			->get()->row_array();

		$data = [];
		if ($sales_campaign['produk_program_gimmick'] == 'Global') {
			$data = $this->data_perolehan_gimmick_tidak_langsung->global_get($id_campaign);
		} else if ($sales_campaign['produk_program_gimmick'] == 'Per Item') {
			$data = $this->data_perolehan_gimmick_tidak_langsung->per_item_get($id_campaign);
		}

		return $data;
	}

	public function perolehan_gimmick()
	{
		$data['set'] = "perolehan_gimmick";

		$data['sales_campaign'] = $this->db
			->select('sc.id')
			->select('sc.nama')
			->select('
			CASE
				WHEN sc.start_date_gimmick IS NOT NULL THEN sc.start_date_gimmick
				ELSE sc.start_date
			END AS start_date
		', false)
			->select('
			CASE
				WHEN sc.end_date_gimmick IS NOT NULL THEN sc.end_date_gimmick
				ELSE sc.end_date
			END AS end_date
		', false)
			->select('sc.produk_program_gimmick')
			->select('sc.satuan_rekapan_gimmick')
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $this->input->get('id'))
			->get()->row_array();

		$sales_campaign_details = $this->db
			->select('sc_detail.*')
			->select('p.nama_part')
			->select('sc.jenis_item_gimmick')
			->from('ms_h3_md_sales_campaign_detail_gimmick as sc_detail')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = sc_detail.id_campaign')
			->join('ms_part as p', 'p.id_part = sc_detail.id_part', 'left')
			->where('sc_detail.id_campaign', $this->input->get('id'))
			->get()->result_array();

		$sales_campaign_details = array_map(function ($row) {
			$row['sales_campaign_gimmick_items'] = $this->db
				->select('sc_gimmick_item.*')
				->from('ms_h3_md_sales_campaign_detail_gimmick_item as sc_gimmick_item')
				->join('ms_h3_md_sales_campaign_detail_gimmick as sc_detail', 'sc_detail.id = sc_gimmick_item.id_detail_gimmick')
				->where('sc_detail.id_campaign', $this->input->get('id'))
				->where('sc_gimmick_item.id_detail_gimmick', $row['id'])
				->order_by('sc_gimmick_item.id_detail_gimmick', 'asc')
				->get()->result_array();
			return $row;
		}, $sales_campaign_details);

		$data['sales_campaign_details'] = $sales_campaign_details;

		$data['sales_campaign_gimmick_globals'] = $this->db
			->select('CONCAT(scdgg.id, "_global") as label_key', false)
			->select('scdgg.nama_paket')
			->select('scdgg.nama_hadiah')
			->select('scdgg.satuan_hadiah')
			->select('scdgg.qty_hadiah')
			->from('ms_h3_md_sales_campaign_detail_gimmick_global as scdgg')
			->where('scdgg.id_campaign', $this->input->get('id'))
			->get()->result_array();

		$this->template($data);
	}

	public function download_perolehan_gimmick_global()
	{
		$this->load->model('H3_md_laporan_gimmick_global_sales_campaign_model', 'laporan_gimmick_global');

		$id = $this->input->get('id');

		try {
			$this->laporan_gimmick_global->laporan($id);
		} catch (Exception $e) {
			log_message('error', $e);

			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'danger');

			redirect(
				base_url("h3/h3_md_ms_sales_campaign/perolehan_gimmick?id={$id}")
			);
		}
	}

	public function download_perolehan_gimmick_item()
	{
		$this->load->model('H3_md_laporan_gimmick_item_sales_campaign_model', 'laporan_gimmick_item');

		$id = $this->input->get('id');

		try {
			$this->laporan_gimmick_item->laporan($id);
		} catch (Exception $e) {
			log_message('error', $e);

			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'danger');

			redirect(
				base_url("h3/h3_md_ms_sales_campaign/perolehan_gimmick?id={$id}")
			);
		}
	}

	private function get_letter($num)
	{
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0) {
			return get_letter($num2 - 1) . $letter;
		} else {
			return $letter;
		}
	}

	public function close()
	{
		$this->db->trans_start();
		$this->db
			->set('status', 'Closed')
			->set('closed_at', date('Y-m-d H:i:s'))
			->set('closed_by', $this->session->userdata('id_user'))
			->where('id', $this->input->get('id'))
			->update('ms_h3_md_sales_campaign');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Sales Campaign berhasil diclose.');
			$this->session->set_userdata('tipe', 'info');
		} else {
			$this->session->set_userdata('pesan', 'Sales Campaign tidak berhasil diclose.');
			$this->session->set_userdata('tipe', 'danger');
		}

		redirect(
			base_url("h3/h3_md_ms_sales_campaign/detail?id={$this->input->get('id')}")
		);
	}

	public function diskualifikasi_dealer()
	{
		$data = [
			'keterangan_diskualifikasi' => $this->input->post('keterangan_diskualifikasi'),
			'tanggal_diskualifikasi' => date('Y-m-d H:i:s', time()),
			'actor_diskualifikasi' => $this->session->userdata('id_user'),
			'diskualifikasi' => 1
		];

		$this->db->trans_start();
		$this->sales_campaign_dealers->update($data, $this->input->post(['id_dealer', 'id_campaign']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$sales_campaign_dealer = $this->db
				->select('scd.*')
				->select('d.kode_dealer_md')
				->select('d.nama_dealer')
				->select('k.nama_lengkap')
				->from('ms_h3_md_sales_campaign_dealers as scd')
				->join('ms_dealer as d', 'd.id_dealer = scd.id_dealer')
				->join('ms_user as u', 'u.id_user = scd.actor_diskualifikasi', 'left')
				->join('ms_karyawan as k', 'k.id_karyawan = u.id_karyawan_dealer', 'left')
				->where('scd.id_dealer', $this->input->post('id_dealer'))
				->where('scd.id_campaign', $this->input->post('id_campaign'))
				->limit(1)
				->get()->row_array();

			send_json($sales_campaign_dealer);
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function hitung_cashback_tidak_langsung_global()
	{
		$this->load->model('H3_md_perolehan_sales_campaign_cashback_tidak_langsung_model', 'perolehan_cashback');
		$this->load->model('H3_md_perolehan_sales_campaign_cashback_tl_detail_model', 'perolehan_cashback_detail');
		$this->load->model('H3_md_perolehan_sales_campaign_cashback_tl_perbulan_model', 'perolehan_cashback_perbulan');
		$this->load->model('H3_md_perolehan_sales_campaign_cashback_tl_global_model', 'perolehan_cashback_global');
		$this->load->model('H3_md_data_perolehan_cashback_tidak_langsung_model', 'data_perolehan_cashback');

		$id_campaign = $this->input->get('id');

		$dealers = $this->data_perolehan_cashback->global_get($id_campaign);

		$this->db->trans_start();

		foreach ($dealers as $dealer) {
			$condition = [
				'id_campaign' => $id_campaign,
				'id_dealer' => $dealer['id_dealer'],
			];

			$perolehan_cashback = (array) $this->perolehan_cashback->get($condition, true);

			if ($perolehan_cashback != null) {
				$this->perolehan_cashback->update([
					'total_penjualan_per_dealer' => $dealer['total_penjualan_per_dealer'],
					'total_dus_penjualan_per_dealer' => $dealer['total_dus_penjualan_per_dealer'],
					'sisa_total_penjualan_per_dealer' => $dealer['sisa_total_penjualan_per_dealer'],
					'sisa_total_dus_penjualan_per_dealer' => $dealer['sisa_total_dus_penjualan_per_dealer'],
					'total_insentif' => $dealer['total_insentif'],
					'ppn' => $dealer['ppn'],
					'nilai_kw' => $dealer['nilai_kw'],
					'pph_23' => $dealer['pph_23'],
					'pph_21' => $dealer['pph_21'],
					'total_bayar' => $dealer['total_bayar'],
				], $condition);
				$id_perolehan = $perolehan_cashback['id'];
			} else {
				$condition['total_penjualan_per_dealer'] = $dealer['total_penjualan_per_dealer'];
				$condition['total_dus_penjualan_per_dealer'] = $dealer['total_dus_penjualan_per_dealer'];
				$condition['sisa_total_penjualan_per_dealer'] = $dealer['sisa_total_penjualan_per_dealer'];
				$condition['sisa_total_dus_penjualan_per_dealer'] = $dealer['sisa_total_dus_penjualan_per_dealer'];
				$condition['total_insentif'] = $dealer['total_insentif'];
				$condition['ppn'] = $dealer['ppn'];
				$condition['nilai_kw'] = $dealer['nilai_kw'];
				$condition['pph_23'] = $dealer['pph_23'];
				$condition['pph_21'] = $dealer['pph_21'];
				$condition['total_bayar'] = $dealer['total_bayar'];
				$this->perolehan_cashback->insert($condition);

				$id_perolehan = $this->db->insert_id();
			}

			foreach ($dealer['months'] as $month) {
				$date = Mcarbon::parse($month['start_date']);
				$condition = [
					'bulan' => $date->format('m'),
					'tahun' => $date->format('Y'),
					'id_perolehan' => $id_perolehan,
					'id_campaign' => $id_campaign,
				];

				$perbulan = (array) $this->perolehan_cashback_perbulan->get($condition, true);

				if ($perbulan != null) {
					$this->perolehan_cashback_perbulan->update([
						'total_penjualan_per_bulan' => $month['total_penjualan_per_bulan'],
						'total_dus_penjualan_per_bulan' => $month['total_dus_penjualan_per_bulan'],
					], $condition);

					$id_perbulan = $perbulan['id'];
				} else {
					$condition['total_penjualan_per_bulan'] = $month['total_penjualan_per_bulan'];
					$condition['total_dus_penjualan_per_bulan'] = $month['total_dus_penjualan_per_bulan'];
					$this->perolehan_cashback_perbulan->insert($condition);
					$id_perbulan = $this->db->insert_id();
				}

				foreach ($month['sales_campaign_details'] as $sales_campaign_detail) {
					$condition = [
						'id_detail' => $sales_campaign_detail['id_detail'],
						'id_perbulan' => $id_perbulan,
						'id_campaign' => $sales_campaign_detail['id_campaign'],
					];

					$detail = (array) $this->perolehan_cashback_detail->get($condition, true);

					if ($detail != null) {
						$this->perolehan_cashback_detail->update([
							'total_kuantitas_penjualan' => $sales_campaign_detail['total_kuantitas_penjualan'],
							'total_dus_penjualan' => $sales_campaign_detail['total_dus_penjualan'],
						], $condition);
					} else {
						$condition['total_kuantitas_penjualan'] = $sales_campaign_detail['total_kuantitas_penjualan'];
						$condition['total_dus_penjualan'] = $sales_campaign_detail['total_dus_penjualan'];
						$this->perolehan_cashback_detail->insert($condition);
					}
				}
			}

			foreach ($dealer['global'] as $global) {
				$condition = [
					'id_global' => $global['id_global'],
					'id_campaign' => $global['id_campaign'],
					'id_perolehan' => $id_perolehan,
				];

				$perolehan_global = (array) $this->perolehan_cashback_global->get($condition, true);

				if ($perolehan_global != null) {
					$this->perolehan_cashback_global->update([
						'count_cashback' => $global['count_cashback']
					], $condition);
				} else {
					$condition['count_cashback'] = $global['count_cashback'];
					$this->perolehan_cashback_global->insert($condition);
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Cashback berhasil dihitung dan digenerate.');
			$this->session->set_userdata('tipe', 'info');
		} else {
			$this->session->set_userdata('pesan', 'Cashback tidak berhasil dihitung dan digenerate.');
			$this->session->set_userdata('tipe', 'danger');
		}

		redirect(
			base_url('h3/h3_md_ms_sales_campaign/insentif_cashback?id=' . $id_campaign)
		);
	}

	public function insentif_cashback()
	{
		$data['set'] = "insentif_cashback";

		$data['sales_campaign'] = $this->db
			->select('sc.id')
			->select('sc.nama')
			->select('
			CASE
				WHEN sc.start_date_cashback IS NOT NULL THEN sc.start_date_cashback
				ELSE sc.start_date
			END AS start_date
		', false)
			->select('
			CASE
				WHEN sc.end_date_cashback IS NOT NULL THEN sc.end_date_cashback
				ELSE sc.end_date
			END AS end_date
		', false)
		->select('sc.produk_program_cashback')
		->select('sc.sudah_proses_insentif')
		->select('sc.proses_ke_finance')
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.id', $this->input->get('id'))
		->get()->row_array();

		$data['sales_campaign_global'] = $this->db
		->from('ms_h3_md_sales_campaign_detail_cashback_global as scdcg')
		->where('scdcg.id_campaign', $this->input->get('id'))
		->order_by('scdcg.qty', 'asc')
		->get()->result_array();

		$this->template($data);
	}

	public function download_laporan_excel_cashback_tidak_langsung()
	{
		$id = $this->input->get('id');
		$sales_campaign = $this->db
			->select('sc.nama')
			->select('
				case
					when sc.start_date_cashback is not null then sc.start_date_cashback
					else sc.start_date
				end as start_date
			', false)
			->select('
				case
					when sc.end_date_cashback is not null then sc.end_date_cashback
					else sc.end_date
				end as end_date
			', false)
			->select('sc.satuan_rekapan_cashback')
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $id)
			->where('sc.jenis_reward_cashback', 1)
			->where('sc.reward_cashback', 'Tidak Langsung')
			->limit(1)
			->get()->row_array();

		if ($sales_campaign == null) {
			$this->session->set_userdata('pesan', 'Sales campaign tidak berjenis cashback dan bertipe tidak langsung.');
			$this->session->set_userdata('tipe', 'warning');

			redirect(
				base_url("h3/h3_md_ms_sales_campaign/insentif_poin?id={$id}")
			);
		}

		$this->load->model('H3_md_laporan_cashback_tidak_langsung_model', 'laporan_cashback_tidak_langsung');

		try{
			$this->laporan_cashback_tidak_langsung->excel($id);
		}catch(\Exception $e){
			log_message('error', $e->getMessage());

			$this->session->set_userdata('pesan', 'Tidak berhasil mendowload laporan');
			$this->session->set_userdata('tipe', 'danger');

			redirect(
				base_url("h3/h3_md_ms_sales_campaign/insentif_poin?id={$id}")
			);
		}
	}
}
