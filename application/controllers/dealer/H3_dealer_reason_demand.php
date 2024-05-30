<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_reason_demand extends Honda_Controller {

	var $folder = "dealer";
	var $page   = "h3_dealer_reason_demand";
	var $title  = "Reason and parts demand";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('H3_dealer_record_reasons_and_parts_demand_model', 'reason_demand');
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";
		$data['reason_demand'] = $this->reason_demand->get([
			'id_dealer' => $this->m_admin->cari_dealer(),
		]);

		$this->template($data);	
	}
	
	public function report(){
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if($start_date == '' and $end_date == ''){
			$start_date = date('Y-m-d', time());
			$end_date = date('Y-m-d', time());
		}

		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;
		$data = [];
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['parts'] = $this->db
		->select('r.id_part')
		->select('p.nama_part')
		->select('p.harga_dealer_user as het')
		->select('sum(r.qty) as qty')
		->select('(p.harga_dealer_user * sum(r.qty)) as total')
		->from('tr_h3_dealer_record_reasons_and_parts_demand as r')
		->join('ms_part as p', 'p.id_part = r.id_part')
		->where('r.id_dealer', $this->m_admin->cari_dealer())
		->where("DATE_FORMAT(r.created_at, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'")
		->group_by('r.id_part')
		->get()->result();

		$html = $this->load->view('dealer/h3_dealer_report_reason_demand', $data, true);
		
		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "Laporan Part Lost Sales.pdf";
		$mpdf->Output($output, 'I');
	}
}