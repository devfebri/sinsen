<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_serah_terima_sj extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_serah_terima_sj";
	protected $title  = "Serah Terima SJ";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_penerimaan_pembayaran_model', 'penerimaan_pembayaran');
		$this->load->model('H3_md_serah_terima_sj_model', 'serah_terima_sj');
		$this->load->model('H3_md_serah_terima_sj_item_model', 'serah_terima_sj_item');

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

	public function get_items()
	{
		$this->db
			->select('sji.id_packing_sheet_int')
			->from('tr_h3_md_serah_terima_sj_item as sji')
			->join('tr_h3_md_serah_terima_sj as sj', 'sj.id = sji.id_serah_terima_sj_int')
			->where('sji.checklist_h3', 1)
			->where('sji.checklist_finance', 1)
			->where('sj.status', 'Processed');
		$surat_jalan_telah_diterima = array_column($this->db->get()->result_array(), 'id_packing_sheet_int');

		$this->db
			->select('sji.id_packing_sheet_int')
			->from('tr_h3_md_serah_terima_sj_item as sji')
			->join('tr_h3_md_serah_terima_sj as sj', 'sj.id = sji.id_serah_terima_sj_int')
			->where('sj.status', 'Open');
		$surat_jalan_gantung = array_column($this->db->get()->result_array(), 'id_packing_sheet_int');

		$keterangan_terakhir = $this->db
			->select('sti.keterangan')
			->from('tr_h3_md_serah_terima_sj_item as sti')
			->join('tr_h3_md_serah_terima_sj as st', 'st.id = sti.id_serah_terima_sj_int')
			->where('sti.id_packing_sheet_int = ps.id', null, false)
			->limit(1)
			->order_by('st.created_at', 'desc')
			->get_compiled_select();

		$this->db
			->select('date_format(ps.tgl_packing_sheet, "%d/%m/%Y") as tgl_packing_sheet')
			->select('ps.id as id_packing_sheet_int')
			->select('ps.id_packing_sheet')
			->select('so.id_sales_order')
			->select('do.id_do_sales_order')
			->select('ps.no_faktur')
			->select('date_format(ps.tgl_faktur, "%d/%m/%Y") as tgl_faktur')
			->select('d.nama_dealer')
			->select('0 as checklist_h3')
			->select('0 as checklist_finance')
			->select('"" as keterangan')
			->select(sprintf('(%s) as keterangan', $keterangan_terakhir), false)
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
			->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('ps.id_packing_sheet !=', null)
			->order_by('ps.no_faktur', 'asc');

		if (count($surat_jalan_telah_diterima) > 0) $this->db->where_not_in('ps.id', $surat_jalan_telah_diterima);
		if (count($surat_jalan_gantung) > 0) $this->db->where_not_in('ps.id', $surat_jalan_gantung);

		send_json($this->db->get()->result_array());
	}

	public function save()
	{
		$this->db->trans_start();
		$data = [
			'id_serah_terima_sj' => $this->serah_terima_sj->generate_id(),
		];
		$this->serah_terima_sj->insert($data);
		$id = $this->db->insert_id();

		$items = $this->getOnly(['id_packing_sheet', 'id_packing_sheet_int', 'checklist_h3', 'checklist_finance', 'keterangan'], $this->input->post('items'), [
			'id_serah_terima_sj' => $data['id_serah_terima_sj'],
			'id_serah_terima_sj_int' => $id,
		]);
		$this->serah_terima_sj_item->insert_batch($items);
		$this->db->trans_complete();

		$serah_terima_sj = (array) $this->serah_terima_sj->find($data['id_serah_terima_sj'], 'id_serah_terima_sj');
		if ($this->db->trans_status() and $serah_terima_sj != null) {
			send_json([
				'message' => 'Surat terima SJ berhasil ditambahkan',
				'payload' => $serah_terima_sj,
				'redirect_url' => base_url(sprintf('h3/%s/detail?id=%s', $this->page, $serah_terima_sj['id']))
			]);
		} else {
			send_json([
				'message' => 'Surat terima SJ tidak berhasil ditambahkan'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$serah_terima_sj = $this->db
			->select('st.id')
			->select('st.id_serah_terima_sj')
			->select('date_format(st.created_at, "%d/%m/%Y") as created_at', false)
			->select('
            case
                when st.proses_at is not null then date_format(st.proses_at, "%d/%m/%Y")
                else "-"
            end as proses_at
		', false)
			->select('st.status')
			->from('tr_h3_md_serah_terima_sj as st')
			->where('st.id', $this->input->get('id'))
			->get()->row_array();

		$data['serah_terima_sj'] = $serah_terima_sj;

		$this->db
			->select('sti.id')
			->select('date_format(ps.tgl_packing_sheet, "%d/%m/%Y") as tgl_packing_sheet')
			->select('sti.id_packing_sheet')
			->select('ps.id_packing_sheet')
			->select('so.id_sales_order')
			->select('do.id_do_sales_order')
			->select('ps.no_faktur')
			->select('date_format(ps.tgl_faktur, "%d/%m/%Y") as tgl_faktur')
			->select('d.nama_dealer')
			->select('sti.checklist_h3')
			->select('sti.checklist_finance')
			->select('sti.keterangan')
			->from('tr_h3_md_serah_terima_sj_item as sti')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = sti.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('sti.id_serah_terima_sj_int', $this->input->get('id'))
			->order_by('ps.no_faktur', 'asc');

		if($serah_terima_sj['status'] == 'Processed'){
			$this->db->where('sti.checklist_h3', 1);
			$this->db->where('sti.checklist_finance', 1);
		}

		$data['items'] = $this->db->get()->result_array();

		$this->template($data);
	}

	public function proses()
	{
		$id = $this->input->post('id');
		$items = $this->input->post('items');

		$this->db->trans_begin();
		try {
			$this->serah_terima_sj->set_proses($id);
			foreach ($items as $item) {
				$data = [
					'checklist_finance' => $item['checklist_finance'],
					'keterangan' => $item['keterangan'],
				];
				$this->serah_terima_sj_item->update($data, ['id' => $item['id']]);
			}
			$this->db->trans_commit();

			$this->session->set_userdata('pesan', 'Serah Terima SJ berhasil diproses.');
			$this->session->set_userdata('tipe', 'info');
		} catch (Exception $e) {
			$this->db->trans_rollback();

			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'danger');
		}

		send_json([
			'redirect_url' => base_url("h3/{$this->page}/detail?id={$id}")
		]);
	}

	public function reject()
	{
		$id = $this->input->post('id');
		$items = $this->input->post('items');

		$this->db->trans_begin();
		try {
			$this->serah_terima_sj->set_reject($id);
			foreach ($items as $item) {
				$data = [
					'checklist_finance' => $item['checklist_finance'],
					'keterangan' => $item['keterangan'],
				];
				$this->serah_terima_sj_item->update($data, ['id' => $item['id']]);
			}
			$this->db->trans_commit();

			$this->session->set_userdata('pesan', 'Serah Terima SJ berhasil diproses.');
			$this->session->set_userdata('tipe', 'info');
		} catch (Exception $e) {
			$this->db->trans_rollback();

			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'danger');
		}

		send_json([
			'redirect_url' => base_url("h3/{$this->page}/detail?id={$id}")
		]);
	}
}
