<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_packing_sheet extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_packing_sheet";
	protected $title  = "Packing Sheet";

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

		$this->load->model('h3_md_so_other_model', 'so_other');
		$this->load->model('h3_md_so_other_parts_model', 'so_other_parts');
		$this->load->model('h3_md_do_other_model', 'do_other');
		$this->load->model('h3_md_do_other_parts_model', 'do_other_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_scan_picking_list_parts_model', 'scan_picking_list_parts');
		$this->load->model('h3_md_packing_sheet_model', 'packing_sheet');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('karyawan_md_model', 'karyawan_md');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function cetak()
	{
		$this->db->trans_start();		
		$packing_sheet = $this->get_packing_sheet_for_print($this->input->get('id'));

		if ($packing_sheet->sudah_print == 0) {
			$do_sales_order = $this->db
				->select('do.id_do_sales_order')
				->from('tr_h3_md_do_sales_order as do')
				->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
				->where('pl.id_picking_list', $packing_sheet->id_picking_list)
				->get()->row_array();

			$this->picking_list->update([
				'status' => 'Packing Sheet',
			], [
				'id_picking_list' => $packing_sheet->id_picking_list,
				'status' => 'Create Faktur'
			]);

			$this->do_sales_order->update([
				'status' => 'Packing Sheet'
			], [
				'id_do_sales_order' => $do_sales_order['id_do_sales_order'],
				'status' => 'Create Faktur'
			]);

			$this->packing_sheet->update([
				'tgl_packing_sheet' => date('Y-m-d H:i:s', time()),
				'id_packing_sheet' => $this->packing_sheet->generateSuratJalan($packing_sheet->po_type, $packing_sheet->id_dealer, $packing_sheet->gimmick),
			], [
				'id' => $this->input->get('id'),
				'tgl_packing_sheet' => null,
				'id_packing_sheet' => null,
			]);
		}

		$packing_sheet = $this->get_packing_sheet_for_print($this->input->get('id'));
		$jumlah_koli_parts = $this->get_jumlah_koli($packing_sheet->id_picking_list, 'Parts');
		$jumlah_koli_oil = $this->get_jumlah_koli($packing_sheet->id_picking_list, 'Oil');
		$jumlah_koli_tire = $this->get_jumlah_koli($packing_sheet->id_picking_list, 'Ban');
		$jumlah_koli = [
			'Parts' => $jumlah_koli_parts,
			'Oil' => $jumlah_koli_oil,
			'Ban' => $jumlah_koli_tire,
		];

		$data['packing_sheet'] = $packing_sheet;
		$data['jumlah_koli'] = $jumlah_koli;

		$data['parts'] = $this->db
			->select('plp.id_part')
			->select('plp.id_part_int')
			->select('p.nama_part')
			->select('plp.serial_number')
			->select('sum(plp.qty_scan) as qty_scan')
			->select('(CASE WHEN p.kelompok_part = "EVBT" THEN "B" WHEN p.kelompok_part = "EVCH" THEN "C" ELSE "" END) AS acc_type')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_scan_picking_list_parts as plp', 'ps.id_picking_list = plp.id_picking_list')
			->join('ms_part as p', 'plp.id_part = p.id_part')
			->where('ps.id', $this->input->get('id'))
			->group_by('plp.id_part')
			->group_by('plp.serial_number')
			->order_by('plp.id_part', 'asc')
			->get()->result();

		// Cek apakah EV atau tidak 
		foreach($data['parts'] as $part){
			$kelompok_part = $this->db->select('kelompok_part')
				->from('ms_part')
				->where('id_part', $part->id_part)
				->get()->row_array();
				

			if($kelompok_part['kelompok_part']=='EVBT' ||$kelompok_part['kelompok_part']=='EVCH'){
				//Cek id packing sheet, tgl ps, dan created by ps
				$check_ps = $this->db->select('id_packing_sheet')
											->select('tgl_packing_sheet')
											->select('created_by')
											->select('id_picking_list_int')
											->from('tr_h3_md_packing_sheet')
											->where('id',$this->input->get('id'))
											->get()->row_array();			

				//Update tgl dan no packing sheet
				$this->db->set('id_packing_sheet_int', $this->input->get('id'))
						->set('id_packing_sheet', $check_ps['id_packing_sheet'])
						->set('created_at_packing_sheet', $check_ps['tgl_packing_sheet'])
						->set('created_by_packing_sheet', $check_ps['created_by'])
						->where('id_part_int',  $part->id_part_int)	
						->where('serial_number', $part->serial_number)
						->update('tr_h3_serial_ev_tracking');	
			}
		}

		$this->db->trans_complete();

		// $this->load->library('mpdf_l');
		require_once APPPATH . 'third_party/mpdf/mpdf.php';
		// Require composer autoload
		$mpdf = new Mpdf();
		// Write some HTML code:
		$html = $this->load->view('h3/h3_md_cetak_packing_sheet', $data, true);
		$mpdf->WriteHTML($html);

		// Output a PDF file directly to the browser
		$mpdf->Output("{$data['packing_sheet']->no_surat_jalan}.pdf", "I");
	}

	private function get_jumlah_koli($id_picking_list, $tipe_koli)
	{
		$data = $this->db
			->select('count(distinct(splp.no_dus)) as jumlah_koli')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_scan_picking_list_parts as splp', 'splp.id_picking_list = ps.id_picking_list')
			->where('splp.produk', $tipe_koli)
			->where('ps.id_picking_list', $id_picking_list)
			->group_by('splp.produk')
			->get()->row_array();

		return $data != null ? $data['jumlah_koli'] : 0;
	}

	private function get_packing_sheet_for_print($id)
	{
		return $this->db
			->select('date_format(pl.tanggal, "%d-%m-%Y") as tanggal_picking')
			->select('pl.id_picking_list')
			->select('ps.no_faktur')
			->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tanggal_faktur')
			->select('d.id_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.alamat')
			->select('d.pemilik')
			->select('
            case
                when ps.tgl_packing_sheet is not null then date_format(ps.tgl_packing_sheet, "%d-%m-%Y")
                else "-"
            end tgl_packing_sheet
        ', false)
			->select('
            case
                when ps.id_packing_sheet is not null then ps.id_packing_sheet
                else "-"
            end id_packing_sheet
		', false)
			->select('do.id_do_sales_order')
			->select('do.sudah_revisi')
			->select('so.id_sales_order')
			->select('so.jenis_pembayaran')
			->select('so.po_type')
			->select('so.gimmick')
			->select('(ps.id_packing_sheet IS NOT NULL) as sudah_print', false)
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->where('ps.id', $id)
			->get()->row();
	}
}
