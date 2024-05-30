<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_berita_acara_penyerahan_faktur extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_berita_acara_penyerahan_faktur";
	protected $title  = "Berita Penyerahan Faktur";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_berita_acara_penyerahan_faktur_model', 'berita_acara_penyerahan_faktur');
		$this->load->model('H3_md_berita_acara_penyerahan_faktur_item_model', 'berita_acara_penyerahan_faktur_item');
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
		$data['mode'] = 'insert';
		$data['set'] = "form";
		$data['logged_user'] = $this->db
			->select('u.id_user')
			->select('ifnull(k.nama_lengkap, "") as nama_lengkap')
			->from('ms_user as u')
			->join('ms_karyawan as k', 'k.id_karyawan = u.id_karyawan_dealer', 'left')
			->where('u.id_user', $this->session->userdata('id_user'))
			->limit(1)
			->get()->row();

		$this->template($data);
	}

	public function proses_faktur()
	{
		$faktur_sudah_terbuat_tanda_terima_faktur = $this->db
			->select('ttfi.no_faktur')
			->from('tr_h3_md_tanda_terima_faktur_item as ttfi')
			->get_compiled_select();

		$dealer_yang_terdapat_dalam_wilayah_penagihan = $this->db
			->select('wpi.id_dealer')
			->from('ms_h3_md_wilayah_penagihan_item as wpi')
			->where('wpi.id_wilayah_penagihan', $this->input->get('id_wilayah_penagihan'))
			->get_compiled_select();

		$jumlah_faktur_dalam_tanda_terima_faktur = $this->db
			->select('count(sq_ttfi.no_faktur)')
			->from('tr_h3_md_tanda_terima_faktur as sq_ttf')
			->join('tr_h3_md_tanda_terima_faktur_item as sq_ttfi', 'sq_ttf.id = sq_ttfi.id_tanda_terima_faktur')
			->group_by('sq_ttfi.id_tanda_terima_faktur')
			->where('sq_ttfi.id = ttf.id')
			->get_compiled_select();

		$pembayaran_faktur_sebelumnya = $this->db
			->select('sum(pbi.jumlah_pembayaran)')
			->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
			->where('pbi.referensi = ps.no_faktur')
			->get_compiled_select();

		$faktur_belum_dikembalikan = $this->db
			->select('bapi.no_faktur')
			->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi')
			->where('bapi.dikembalikan', 0)
			->get_compiled_select();

		$data = $this->db
			->select('ps.no_faktur')
			->select('d.nama_dealer')
			->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
			->select('date_format(ps.tgl_jatuh_tempo, "%d-%m-%Y") as tgl_jatuh_tempo')
			->select("dso.total - ifnull( ({$pembayaran_faktur_sebelumnya}), 0 ) as total")
			->select('ttf.no_tanda_terima_faktur')
			->select("({$jumlah_faktur_dalam_tanda_terima_faktur}) as jumlah_faktur")
			->select('"" as keterangan')
			->select('1 as checked')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->join('tr_h3_md_tanda_terima_faktur_item as ttfi', 'ttfi.no_faktur = ps.no_faktur', 'left')
			->join('tr_h3_md_tanda_terima_faktur as ttf', 'ttf.id = ttfi.id_tanda_terima_faktur', 'left')
			->where("pl.id_dealer in ({$dealer_yang_terdapat_dalam_wilayah_penagihan})")
			->where('ps.tgl_jatuh_tempo <=', $this->input->get('end_date'))
			->where('ps.faktur_lunas', 0)
			->where("ps.no_faktur not in ({$faktur_belum_dikembalikan})")
			// ->where("ps.no_faktur not in ({$faktur_sudah_terbuat_tanda_terima_faktur})")
			->order_by('d.nama_dealer', 'asc')
			->order_by('ps.no_faktur', 'asc')
			->order_by('ps.tgl_jatuh_tempo', 'asc')
			->get()->result();

		send_json($data);
	}

	public function save()
	{
		$this->db->trans_start();

		$this->validate();
		$data = $this->input->post([
			'id_wilayah_penagihan', 'end_date',
			'id_debt_collector', 'id_diketahui', 'id_yang_menerima',
			'id_yang_menyerahkan', 'total'
		]);
		$data = array_merge($data, [
			'no_bap' => $this->berita_acara_penyerahan_faktur->generate_id()
		]);
		$this->berita_acara_penyerahan_faktur->insert($data);

		$items = $this->getOnly(['no_faktur', 'keterangan'], $this->input->post('items'), [
			'no_bap' => $data['no_bap']
		]);
		$this->berita_acara_penyerahan_faktur_item->insert_batch($items);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$berita_acara_penyerahan_faktur = $this->berita_acara_penyerahan_faktur->find($data['no_bap'], 'no_bap');
			send_json($berita_acara_penyerahan_faktur);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan berita acara',
			], 422);
		}
	}

	public function pengembalian()
	{
		$data['mode'] = 'pengembalian';
		$data['set'] = "form";
		$data['berita_acara_penyerahan_faktur'] = $this->db
			->select('bapf.no_bap')
			->select('bapf.dikembalikan')
			->select('date_format(bapf.created_at, "%d-%m-%Y") as created_at')
			->select('wp.nama as nama_wilayah_penagihan')
			->select('wp.kode_wilayah as kode_wilayah_penagihan')
			->select('dc.nama_lengkap as nama_debt_collector')
			->from('tr_h3_md_berita_acara_penyerahan_faktur as bapf')
			->join('ms_h3_md_wilayah_penagihan as wp', 'wp.id = bapf.id_wilayah_penagihan')
			->join('ms_karyawan as dc', 'dc.id_karyawan = bapf.id_debt_collector')
			->join('ms_karyawan as diketahui', 'diketahui.id_karyawan = bapf.id_diketahui')
			->join('ms_karyawan as yang_menerima', 'yang_menerima.id_karyawan = bapf.id_yang_menerima')
			->join('ms_user as u', 'u.id_user = bapf.id_yang_menyerahkan')
			->join('ms_karyawan as yang_menyerahkan', 'yang_menyerahkan.id_karyawan = u.id_karyawan_dealer', 'left')
			->where('bapf.no_bap', $this->input->get('no_bap'))
			->get()->row();

		$jumlah_faktur_dalam_tanda_terima_faktur = $this->db
			->select('count(sq_ttfi.no_faktur)')
			->from('tr_h3_md_tanda_terima_faktur as sq_ttf')
			->join('tr_h3_md_tanda_terima_faktur_item as sq_ttfi', 'sq_ttf.id = sq_ttfi.id_tanda_terima_faktur')
			->group_by('sq_ttfi.id_tanda_terima_faktur')
			->where('sq_ttfi.id = ttf.id')
			->get_compiled_select();

		$cash = $this->db
			->select('SUM(pbi.jumlah_pembayaran)')
			->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
			->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
			->where('pbi.referensi = bapfi.no_faktur')
			->where('pb.tanggal_bap = date_format(bapf.created_at, "%Y-%m-%d")')
			->where('pb.jenis_pembayaran', 'Cash')
			->get_compiled_select();

		$bg = $this->db
			->select('SUM(pbi.jumlah_pembayaran)')
			->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
			->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
			->where('pbi.referensi = bapfi.no_faktur')
			->where('pb.tanggal_bap = date_format(bapf.created_at, "%Y-%m-%d")')
			->where('pb.jenis_pembayaran', 'BG')
			->get_compiled_select();

		$transfer = $this->db
			->select('SUM(pbi.jumlah_pembayaran)')
			->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
			->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
			->where('pbi.referensi = bapfi.no_faktur')
			->where('pb.tanggal_bap = date_format(bapf.created_at, "%Y-%m-%d")')
			->where('pb.jenis_pembayaran', 'Transfer')
			->get_compiled_select();

		$data['items'] = $this->db
			->select('ps.no_faktur')
			->select('ps.faktur_lunas')
			->select('d.nama_dealer')
			->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
			->select('date_format(ps.tgl_jatuh_tempo, "%d-%m-%Y") as tgl_jatuh_tempo')
			->select('dso.total')
			->select('ttf.no_tanda_terima_faktur')
			->select("({$jumlah_faktur_dalam_tanda_terima_faktur}) as jumlah_faktur")
			->select('bapfi.dikembalikan as dikembalikan')
			->select('1 as checked')
			->select("IFNULL(({$cash}), 0) as cash")
			->select("IFNULL(({$bg}), 0) as amount_bg")
			->select("IFNULL(({$transfer}), 0) as transfer")
			->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapfi')
			->join('tr_h3_md_berita_acara_penyerahan_faktur as bapf', 'bapf.no_bap = bapfi.no_bap')
			->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = bapfi.no_faktur')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->join('tr_h3_md_tanda_terima_faktur_item as ttfi', 'ttfi.no_faktur = ps.no_faktur', 'left')
			->join('tr_h3_md_tanda_terima_faktur as ttf', 'ttf.id = ttfi.id_tanda_terima_faktur', 'left')
			->where('bapfi.no_bap', $this->input->get('no_bap'))
			->get()->result_array();

		$this->template($data);
	}

	public function simpan_pengembalian()
	{
		$this->db->trans_start();
		$this->db
			->where('bap.no_bap', $this->input->post('no_bap'))
			->where('bap.dikembalikan', 0)
			->set('bap.dikembalikan', 1)
			->update('tr_h3_md_berita_acara_penyerahan_faktur as bap');

		foreach ($this->input->post('items') as $item) {
			$data = $this->get_in_array(['cash', 'transfer', 'amount_bg', 'no_bg', 'dikembalikan'], $item);

			$this->berita_acara_penyerahan_faktur_item->update($data, [
				'no_faktur' => $item['no_faktur'],
				'no_bap' => $this->input->post('no_bap')
			]);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json(
				$this->berita_acara_penyerahan_faktur->get($this->input->post(['no_bap']), true)
			);
		} else {
			send_json([
				'message' => 'Tidak berhasil melakukan pengembalian faktur',
			], 422);
		}
	}

	public function cetak_bap()
	{
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
		$mpdf->autoLangToFont           = true;

		$data = [];
		$data['berita_acara_penyerahan_faktur'] = $this->db
			->select('bapf.no_bap')
			->select('diketahui.nama_lengkap as nama_diketahui')
			->select('jabatan_diketahui.jabatan as jabatan_diketahui')
			->select('yang_menerima.nama_lengkap as nama_yang_menerima')
			->select('jabatan_yang_menerima.jabatan as jabatan_yang_menerima')
			->select('debt_collector.nama_lengkap as nama_debt_collector')
			->select('jabatan_debt_collector.jabatan as jabatan_debt_collector')
			->select('yang_menyerahkan.nama_lengkap as nama_yang_menyerahkan')
			->select('jabatan_yang_menyerahkan.jabatan as jabatan_yang_menyerahkan')
			->from('tr_h3_md_berita_acara_penyerahan_faktur as bapf')
			->join('ms_karyawan as diketahui', 'diketahui.id_karyawan = bapf.id_diketahui')
			->join('ms_jabatan as jabatan_diketahui', 'jabatan_diketahui.id_jabatan = diketahui.id_jabatan')
			->join('ms_karyawan as yang_menerima', 'yang_menerima.id_karyawan = bapf.id_yang_menerima')
			->join('ms_jabatan as jabatan_yang_menerima', 'jabatan_yang_menerima.id_jabatan = yang_menerima.id_jabatan')
			->join('ms_karyawan as debt_collector', 'debt_collector.id_karyawan = bapf.id_debt_collector')
			->join('ms_jabatan as jabatan_debt_collector', 'jabatan_debt_collector.id_jabatan = debt_collector.id_jabatan')
			->join('ms_user as u', 'u.id_user = bapf.id_yang_menyerahkan', 'left')
			->join('ms_karyawan as yang_menyerahkan', 'yang_menyerahkan.id_karyawan = u.id_karyawan_dealer', 'left')
			->join('ms_jabatan as jabatan_yang_menyerahkan', 'jabatan_yang_menyerahkan.id_jabatan = yang_menyerahkan.id_jabatan', 'left')
			->where('bapf.no_bap', $this->input->get('no_bap'))
			->get()->row();

		$jumlah_faktur_dalam_tanda_terima_faktur = $this->db
			->select('count(sq_ttfi.no_faktur)')
			->from('tr_h3_md_tanda_terima_faktur as sq_ttf')
			->join('tr_h3_md_tanda_terima_faktur_item as sq_ttfi', 'sq_ttf.id = sq_ttfi.id_tanda_terima_faktur')
			->group_by('sq_ttfi.id_tanda_terima_faktur')
			->where('sq_ttfi.id = ttf.id')
			->get_compiled_select();

		$data['items'] = $this->db
			->select('left(ps.no_faktur, 6) as no_faktur')
			->select('d.nama_dealer')
			->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
			->select('date_format(ps.tgl_jatuh_tempo, "%d-%m-%Y") as tgl_jatuh_tempo')
			->select('dso.total')
			->select('ttf.no_tanda_terima_faktur')
			->select("({$jumlah_faktur_dalam_tanda_terima_faktur}) as jumlah_faktur")
			->select('bapfi.keterangan')
			->select('1 as checked')
			->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapfi')
			->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = bapfi.no_faktur')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->join('tr_h3_md_tanda_terima_faktur_item as ttfi', 'ttfi.no_faktur = ps.no_faktur', 'left')
			->join('tr_h3_md_tanda_terima_faktur as ttf', 'ttf.id = ttfi.id_tanda_terima_faktur', 'left')
			->where('bapfi.no_bap', $this->input->get('no_bap'))
			->order_by('d.nama_dealer', 'asc')
			->order_by('ps.created_at', 'asc')
			->get()->result();

		$html = $this->load->view('h3/h3_md_cetakan_berita_acara_penyerahan_faktur', $data, true);

		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "Berita penyerahan faktur.pdf";
		$mpdf->Output($output, 'I');
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_wilayah_penagihan', 'Wilayah Penagihan', 'required');
		$this->form_validation->set_rules('end_date', 'Periode Faktur', 'required');
		$this->form_validation->set_rules('id_debt_collector', 'Debt Collector', 'required');
		$this->form_validation->set_rules('id_diketahui', 'Diketahui Oleh', 'required');
		$this->form_validation->set_rules('id_yang_menerima', 'Yang Menerima', 'required');
		$this->form_validation->set_rules('id_yang_menyerahkan', 'Yang Menyerahkan', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}
}
