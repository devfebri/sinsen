<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitor_plafon extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_monitor_plafon";
	protected $title  = "Monitor Plafon";

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

        $this->load->model('H3_md_ms_plafon_model', 'plafon');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function get_plafon_dealer(){
		$dealer = $this->db
		->select('d.tipe_plafon_h3')
		->from('ms_dealer as d')
		->where('d.id_dealer', $this->input->get('id_dealer'))
		->get()->row_array();

		$gimmick = $dealer['tipe_plafon_h3'] == 'gimmick' ? 1 : 0;
        $kategori_po = $dealer['tipe_plafon_h3'] == 'kpb' ?'KPB' : null;

		if($dealer != null){
			send_json([
				'plafon' => $this->plafon->get_plafon($this->input->get('id_dealer'), $gimmick, $kategori_po)
			]);
		}else{
			send_json([
				'plafon' => 0
			]);
		}
	}

	public function get_plafon_booking(){
		$dealer = $this->db
		->select('d.tipe_plafon_h3')
		->from('ms_dealer as d')
		->where('d.id_dealer', $this->input->get('id_dealer'))
		->get()->row_array();

		$gimmick = $dealer['tipe_plafon_h3'] == 'gimmick' ? 1 : 0;
        $kategori_po = $dealer['tipe_plafon_h3'] == 'kpb' ?'KPB' : null;

		$plafon = $this->plafon->get_plafon($this->input->get('id_dealer'), $gimmick, $kategori_po);

		send_json([
			'plafon_booking' => $this->plafon->get_plafon_booking($this->input->get('id_dealer'), $gimmick, $kategori_po),
			'plafon' => $plafon
		]);
	}

	public function report(){
		$this->db
        ->select('so.produk')
		->select('ar.referensi')
		->select('ar.tanggal_transaksi')
		->select('ar.tanggal_jatuh_tempo')
		->select('ar.total_amount')
		->select('ar.sudah_dibayar')
        ->select("(ar.total_amount - ar.sudah_dibayar) as sisa_piutang", false)
		->from('tr_h3_md_ar_part as ar')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ar.referensi')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->where('ar.tipe_referensi', 'faktur_penjualan')
		->where('ar.lunas', 0)
		->where('ar.id_dealer', $this->input->get('id_dealer'));

		$data['faktur'] = array_map(function($row){
			$row['bg'] = $this->plafon->get_rincian_pembayaran($row['referensi']);
			return $row;
		}, $this->db->get()->result_array());

		$data['dealer'] = $this->db
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.plafon_h3 as plafon')
		->from('ms_dealer as d')
		->where('d.id_dealer', $this->input->get('id_dealer'))
		->get()->row_array();

		$data['plafon_booking'] = $this->plafon->get_plafon_booking($this->input->get('id_dealer'));

		$this->load->model('H3_md_report_monitor_plafon_model', 'report_monitor_plafon');

		$this->report_monitor_plafon->generate($data);
	}
}
