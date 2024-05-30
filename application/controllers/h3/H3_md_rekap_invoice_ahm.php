<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_rekap_invoice_ahm extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_rekap_invoice_ahm";
	protected $title  = "Rekap Invoice AHM";

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

		$this->load->model('H3_md_rekap_invoice_ahm_model', 'rekap_invoice');
        $this->load->model('H3_md_rekap_invoice_ahm_items_model', 'rekap_invoice_items');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function add(){
		$data['mode'] = 'insert';
		$data['set'] = 'form';

		$this->template($data);
	}

	public function proses_faktur(){
		$invoice_sudah_terekap = $this->db
        ->select('riai.invoice_number')
		->from('tr_h3_rekap_invoice_ahm_items as riai')
		->join('tr_h3_rekap_invoice_ahm as ria', 'ria.id_rekap_invoice = riai.id_rekap_invoice')
		->where('ria.tgl_jatuh_tempo', $this->input->get('tgl_jatuh_tempo'))
        ->get_compiled_select();

		$invoice_belum_terekap = $this->db
		->select('date_format(fdo.invoice_date, "%d/%m/%Y") as invoice_date')
		->select('fdo.invoice_number')
		->select("
			case
				when fdo.dpp_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then date_format(fdo.dpp_due_date, '%d/%m/%Y')
				else '-'
			end as dpp_due_date_formatted
		", false)
		->select('fdo.dpp_due_date')
		->select("
			case
				when fdo.ppn_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then date_format(fdo.ppn_due_date, '%d/%m/%Y')
				else '-'
			end as ppn_due_date_formatted
		", false)
		->select('fdo.ppn_due_date')
		->select("
			case
				when fdo.dpp_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then fdo.total_dpp
				else 0
			end as total_dpp
		", false)
		->select("
			case
				when fdo.ppn_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then fdo.total_ppn
				else 0
			end as total_ppn
		", false)
		->select('"" as no_giro')
		->select('0 as amount_giro')
		->from('tr_h3_md_fdo as fdo')
		->group_start()
		->where('fdo.ppn_due_date', $this->input->get('tgl_jatuh_tempo'))
		->or_where('fdo.dpp_due_date', $this->input->get('tgl_jatuh_tempo'))
		->group_end()
		->where("fdo.invoice_number not in ({$invoice_sudah_terekap})")
		->where('fdo.status', 'Approved')
		->order_by('fdo.invoice_date', 'asc')
		->order_by('fdo.invoice_number', 'asc')
		->get()->result_array()
		;
		
		send_json($invoice_belum_terekap);
	}

	public function save(){
		$this->db->trans_start();
		$this->validate();

		$data = array_merge($this->input->post([
			'tgl_jatuh_tempo', 'total_dpp', 'total_ppn'
		]), [
			'id_rekap_invoice' => date('dmY', strtotime($this->input->post('tgl_jatuh_tempo')))
		]);

		$this->rekap_invoice->insert($data);
		$items = $this->getOnly([
			'invoice_number'
		], $this->input->post('items'), [
			'id_rekap_invoice' => $data['id_rekap_invoice']
		]);
		$this->rekap_invoice_items->insert_batch($items);

		$this->rekap_invoice->create_ap($data['id_rekap_invoice']);
		
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_userdata('pesan', 'Berhasil melakukan rekap invoice AHM.');
			$this->session->set_userdata('tipe', 'success');

			$rekap_invoice = $this->rekap_invoice->find($data['id_rekap_invoice'], 'id_rekap_invoice');
			send_json($rekap_invoice);
		}else{
			send_json([
				'message' => 'Tidak berhasil melakukan rekap invoice AHM'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['rekap_invoice'] = $this->db
		->select('ria.id_rekap_invoice')
		->select('ria.tgl_jatuh_tempo')
		->from('tr_h3_rekap_invoice_ahm as ria')
		->where('ria.id_rekap_invoice', $this->input->get('id_rekap_invoice'))
		->get()->row();

		$data['items'] = $this->db
		->select('date_format(fdo.invoice_date, "%d-%m-%Y") as invoice_date')
		->select('riai.invoice_number')
		->select("
			case
				when fdo.dpp_due_date = '{$data['rekap_invoice']->tgl_jatuh_tempo}' then date_format(fdo.dpp_due_date, '%d/%m/%Y')
				else '-'
			end as dpp_due_date_formatted
		", false)
		->select('fdo.dpp_due_date')
		->select("
			case
				when fdo.ppn_due_date = '{$data['rekap_invoice']->tgl_jatuh_tempo}' then date_format(fdo.ppn_due_date, '%d/%m/%Y')
				else '-'
			end as ppn_due_date_formatted
		", false)
		->select('fdo.ppn_due_date')
		->select("
			case
				when fdo.dpp_due_date = '{$data['rekap_invoice']->tgl_jatuh_tempo}' then fdo.total_dpp
				else 0
			end as total_dpp
		", false)
		->select("
			case
				when fdo.ppn_due_date = '{$data['rekap_invoice']->tgl_jatuh_tempo}' then fdo.total_ppn
				else 0
			end as total_ppn
		", false)
		->select('0 as no_giro')
		->select('0 as amount_giro')
		->from('tr_h3_rekap_invoice_ahm_items as riai')
		->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = riai.invoice_number')
		->where('riai.id_rekap_invoice', $this->input->get('id_rekap_invoice'))
		->order_by('fdo.invoice_date', 'asc')
		->order_by('fdo.invoice_number', 'asc')
		->get()->result();

		$this->template($data);
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('tgl_jatuh_tempo', 'Tgl Jatuh Tempo', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }

	public function test(){
		$this->db->trans_start();
		$this->db
		->from('tr_h3_rekap_invoice_ahm');

		foreach($this->db->get()->result_array() as $row){
			$this->rekap_invoice->create_ap($row['id_rekap_invoice']);
		}
		$this->db->trans_complete();
	}
}
