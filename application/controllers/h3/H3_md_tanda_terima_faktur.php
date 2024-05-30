<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_tanda_terima_faktur extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_tanda_terima_faktur";
    protected $title  = "Tanda Terima Faktur";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_tanda_terima_faktur_model', 'tanda_terima_faktur');	
		$this->load->model('H3_md_tanda_terima_faktur_item_model', 'tanda_terima_faktur_item');	
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

	public function proses_faktur(){
		$this->validate_proses_faktur();
		
		$faktur_sudah_terbuat_tanda_terima_faktur = $this->db
		->select('ttfi.no_faktur')
		->from('tr_h3_md_tanda_terima_faktur_item as ttfi')
		->get_compiled_select();

		$faktur_yang_tidak_dikembalikan = $this->db
		->select('bapfi_sq.no_faktur')
		->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapfi_sq')
		->from('tr_h3_md_berita_acara_penyerahan_faktur as bapf_sq', 'bapf_sq.no_bap = bapfi_sq.no_bap')
		->where('bapfi_sq.dikembalikan', 0)
		->where('bapf_sq.dikembalikan', 1)
		->get_compiled_select();

		$data = $this->db
		->select('ar.referensi as no_faktur')
		->select('date_format(ar.tanggal_transaksi, "%d-%m-%Y") as tgl_faktur')
		->select('date_format(ar.tanggal_jatuh_tempo, "%d-%m-%Y") as tgl_jatuh_tempo')
		->select('ar.total_amount as total')
		->select('1 as checked')
		->from('tr_h3_md_ar_part as ar')
		->where('ar.id_dealer', $this->input->get('id_dealer'))
		->group_start()
		->where("ar.tanggal_jatuh_tempo between '{$this->input->get('start_date')}' AND '{$this->input->get('end_date')}'", null, false)
		->group_end()
		->where("ar.referensi not in ({$faktur_sudah_terbuat_tanda_terima_faktur})", null, false)
		->where("ar.referensi not in ({$faktur_yang_tidak_dikembalikan})", null, false)
		->where('ar.lunas', $this->input->get('status_faktur'));

		send_json($this->db->get()->result_array());
	}

	public function validate_proses_faktur(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_data($this->input->get());
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		$this->form_validation->set_rules('start_date', 'Periode Faktur', 'required');
		$this->form_validation->set_rules('status_faktur', 'Status Faktur', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }

	public function save()
	{		
		$this->db->trans_start();

		$this->validate();
		$data = $this->input->post([
			'id_dealer', 'id_wilayah_penagihan',
            'status_faktur', 'start_date', 'end_date',
            'id_yang_menyerahkan', 'id_yang_menerima',
            'id_yang_menyetujui', 'total', 'id_bank'
		]);
		$data = array_merge($data, [
			'no_tanda_terima_faktur' => $this->tanda_terima_faktur->generate_id()
		]);
		$this->tanda_terima_faktur->insert($data);
		$id_tanda_terima_faktur = $this->db->insert_id();

		$items = $this->getOnly(['no_faktur'], $this->input->post('items'), [
			'id_tanda_terima_faktur' => $id_tanda_terima_faktur
		]);
		$this->tanda_terima_faktur_item->insert_batch($items);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$tanda_terima_faktur = $this->tanda_terima_faktur->find($id_tanda_terima_faktur);
			send_json($tanda_terima_faktur);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['tanda_terima_faktur'] = $this->db
		->select('ttf.id')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('ttf.id_wilayah_penagihan')
		->select('wp.nama as nama_wilayah_penagihan')
		->select('ttf.status_faktur')
		->select('ttf.start_date')
		->select('ttf.end_date')
		->select('ttf.id_yang_menyerahkan')
		->select('yang_menyerahkan.nama_lengkap as nama_yang_menyerahkan')
		->select('ttf.id_yang_menerima')
		->select('yang_menerima.nama_lengkap as nama_yang_menerima')
		->select('ttf.id_yang_menyetujui')
		->select('yang_menyetujui.nama_lengkap as nama_yang_menyetujui')
		->select('ttf.id_bank')
		->select('rek.bank as nama_bank')
		->select('rek.no_rekening')
		->from('tr_h3_md_tanda_terima_faktur as ttf')
		->join('ms_dealer as d', 'd.id_dealer = ttf.id_dealer')
		->join('ms_h3_md_wilayah_penagihan as wp', 'wp.id = ttf.id_wilayah_penagihan')
		->join('ms_karyawan as yang_menyerahkan', 'yang_menyerahkan.id_karyawan = ttf.id_yang_menyerahkan')
		->join('ms_karyawan as yang_menerima', 'yang_menerima.id_karyawan = ttf.id_yang_menerima')
		->join('ms_karyawan as yang_menyetujui', 'yang_menyetujui.id_karyawan = ttf.id_yang_menyetujui')
		->join('ms_rek_md as rek', 'rek.id_rek_md = ttf.id_bank')
		->where('ttf.id', $this->input->get('id'))
		->get()->row();

		$data['items'] = $this->db
		->select('ps.no_faktur')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('date_format(ps.tgl_jatuh_tempo, "%d-%m-%Y") as tgl_jatuh_tempo')
		->select('dso.total')
		->select('1 as checked')
		->from('tr_h3_md_tanda_terima_faktur_item as ttfi')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ttfi.no_faktur')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
		->where('ttfi.id_tanda_terima_faktur', $this->input->get('id'))
		->order_by('ps.no_faktur', 'asc')
		->order_by('ps.tgl_jatuh_tempo', 'asc')
		->get()->result();

		$this->template($data);	
	}

	public function cetakan_tanda_terima_faktur()
	{
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
		$mpdf->autoLangToFont           = true;

		$data = [];
		$data['tanda_terima_faktur'] = $this->db
		->select('ttf.id')
		->select('ttf.no_tanda_terima_faktur')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('ttf.id_wilayah_penagihan')
		->select('wp.nama as nama_wilayah_penagihan')
		->select('ttf.status_faktur')
		->select('ttf.start_date')
		->select('ttf.end_date')
		->select('ttf.id_yang_menyerahkan')
		->select('yang_menyerahkan.nama_lengkap as nama_yang_menyerahkan')
		->select('ttf.id_yang_menerima')
		->select('yang_menerima.nama_lengkap as nama_yang_menerima')
		->select('ttf.id_yang_menyetujui')
		->select('yang_menyetujui.nama_lengkap as nama_yang_menyetujui')
		->select('rek.atas_nama')
		->select('rek.bank as nama_bank')
		->select('rek.no_rekening')
		->from('tr_h3_md_tanda_terima_faktur as ttf')
		->join('ms_dealer as d', 'd.id_dealer = ttf.id_dealer')
		->join('ms_h3_md_wilayah_penagihan as wp', 'wp.id = ttf.id_wilayah_penagihan')
		->join('ms_karyawan as yang_menyerahkan', 'yang_menyerahkan.id_karyawan = ttf.id_yang_menyerahkan')
		->join('ms_karyawan as yang_menerima', 'yang_menerima.id_karyawan = ttf.id_yang_menerima')
		->join('ms_karyawan as yang_menyetujui', 'yang_menyetujui.id_karyawan = ttf.id_yang_menyetujui')
		->join('ms_rek_md as rek', 'rek.id_rek_md = ttf.id_bank')
		->where('ttf.id', $this->input->get('id'))
		->get()->row();

		$data['items'] = $this->db
		->select('ps.no_faktur')
		->select('d.nama_dealer')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('date_format(ps.tgl_jatuh_tempo, "%d-%m-%Y") as tgl_jatuh_tempo')
		->select('dso.total')
		->select('so.produk')
		->select('ps.faktur_lunas')
		->from('tr_h3_md_tanda_terima_faktur_item as ttfi')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ttfi.no_faktur')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->where('ttfi.id_tanda_terima_faktur', $this->input->get('id'))
		->order_by('ps.no_faktur', 'asc')
		->order_by('ps.tgl_jatuh_tempo', 'asc')
		->get()->result();
		$html = $this->load->view('h3/h3_md_cetakan_tanda_terima_faktur', $data, true);
		
		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "Tanda terima faktur.pdf";
		$mpdf->Output($output, 'I');
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		$this->form_validation->set_rules('id_wilayah_penagihan', 'Wilayah Penagihan', 'required');
		$this->form_validation->set_rules('start_date', 'Periode Faktur', 'required');
		$this->form_validation->set_rules('id_yang_menyerahkan', 'Yang Menyerahkan', 'required');
		$this->form_validation->set_rules('id_yang_menerima', 'Yang Menerima', 'required');
		$this->form_validation->set_rules('id_yang_menyetujui', 'Disetujui Oleh', 'required');
		$this->form_validation->set_rules('id_bank', 'Bank', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }
}