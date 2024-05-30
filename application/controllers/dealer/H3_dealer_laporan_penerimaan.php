<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_penerimaan extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_penerimaan";
	var $title  = "Laporan Penerimaan";

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
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}

	public function export(){
		$this->load->library('mpdf_l');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;
		$mpdf->AddPage('L');

		$this->db
		->select('pb.id_penerimaan_barang as nomor_penerimaan')
		->select('ps.id_packing_sheet as nomor_packing_sheet')
		->select('pbi.no_dus as nomor_karton')
		->select('pbi.id_part')
		->select('p.nama_part')
		->from('tr_h3_dealer_penerimaan_barang_items as pbi')
		->join('ms_part as p', 'p.id_part = pbi.id_part')
		->join('tr_h3_dealer_penerimaan_barang as pb', 'pbi.id_penerimaan_barang = pb.id_penerimaan_barang')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
		->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list_int = pl.id')
		->where('pb.id_dealer', $this->m_admin->cari_dealer())
		->group_start()
		->where('pb.tanggal >=', $this->input->get('start_date'))
		->where('pb.tanggal <=', $this->input->get('end_date'))
		->group_end()
		->order_by('pb.created_at', 'asc')
		->order_by('pbi.id_part', 'asc')
		;

		if($this->input->get('type') == 'Good'){
			$this->db->select('pbi.qty_good as qty');
			$this->db->select('pbi.id_gudang_good as id_gudang');
			$this->db->select('pbi.id_rak_good as id_rak');
		}else if($this->input->get('type') == 'Bad'){
			$this->db->join('ms_kategori_claim_c3 as c', 'pbi.id_claim_bad = c.id', 'left');
			$this->db->where('pbi.id_claim_bad !=', null);
			$this->db->select('pbi.qty_bad as qty');
			$this->db->select('pbi.id_gudang_bad as id_gudang');
			$this->db->select('pbi.id_rak_bad as id_rak');
			$this->db->select('c.nama_claim as keterangan');
		}

		$data['penerimaan'] = $this->db->get()->result();
		$data['start_date'] = date('d-m-Y', strtotime($this->input->get('start_date')));
		$data['end_date'] = date('d-m-Y', strtotime($this->input->get('end_date')));

		$html = $this->load->view('dealer/h3_dealer_cetakan_laporan_penerimaan', $data, true);
		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		
		$output = "Laporan Penerimaan.pdf";
		$mpdf->Output($output, 'I');
    }
}