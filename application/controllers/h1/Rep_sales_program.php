<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_sales_program extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_sales_program";	
	var $isi		=		"laporan_4";	
	var $title  =   "Sales Program Marketing";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_auto_claim_payment');		

		//===== Load Library =====		
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');
		$this->load->library('cfpdf');
		$this->load->library('mpdf_l');
		$this->load->library('pdf');		

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
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function download_exel_md($filter)
	{


		$filter['id_program_ahm'] = $data['id_program_ahm']   = $this->input->post('id_program_ahm');
		$program                  = $filter['id_program_md']  = $this->input->post('id_program_md');				
		$filter['id_dealer'] 	  = $data['id_dealer'] 	      = $this->input->post('id_dealer');
		$filter['dealer_group']   = $data['dealer_group']     = $this->input->post('dealer_group');

		$data['sales_program']		 = $set_sales_program =  $this->m_auto_claim_payment->get_sales_program($program)->row();
		$data['sales_program_syarat']= $this->m_auto_claim_payment->get_sales_program($program,1)->result();

		$array_of_ids_to_exclude = array(1501, 1572);
		$this->db->select('*');
		$this->db->from('ms_kabupaten');
		$this->db->where('id_provinsi', '1500');
		$this->db->where_not_in('id_kabupaten', $array_of_ids_to_exclude);
		$this->db->order_by('kode_samsat', 'ASC');
		$query = $this->db->get();
		$data['kabupaten'] = $query->result();
		$this->db->select('kab.kabupaten, COUNT(1) as jumlah');
		$this->db->from('ms_provinsi pro');
		$this->db->join('ms_kabupaten kab', 'kab.id_provinsi = pro.id_provinsi', 'left');
		$this->db->join('ms_kecamatan kec', 'kec.id_kabupaten = kab.id_kabupaten', 'left');
		$this->db->join('ms_kelurahan kel', 'kel.id_kecamatan = kec.id_kecamatan', 'left');
		$this->db->join('ms_dealer md', 'md.id_kelurahan = kel.id_kelurahan', 'left');
		$this->db->where('md.h1', '1');
		$this->db->where('kab.id_provinsi', '1500');
		$this->db->group_by('kab.id_kabupaten');
		$this->db->order_by('kab.kode_samsat', 'asc');
		$query = $this->db->get();
		$data['get_kabupaten'] = $query->result();
		$data['tipe_kendaraan'] = $tipe = $this->m_auto_claim_payment->get_tipe_kendaraan($program);

		$temp = array();
		$temp_footer = array();

		$set_total_ssu_total_full = array();
		$set_total_ssu_total_credit = array();
		$set_total_ssu_total_claim_by_dealer_credit =  array();
		$set_total_ssu_total_approve_by_ahm_credit =  array();
		$set_total_ssu_total_approve_by_dealer_credit  =  array();
		$set_total_ssu_total_reject_by_dealer_credit  =  array();
		$set_total_ssu_total_reject_reason_credit  =  array();
		$set_total_ssu_total_nilai_claim_credit  =  array();
		$set_total_ssu_total_cash  =  array();
		$set_total_ssu_total_claim_by_dealer  =  array();
		$set_total_ssu_total_approve_by_ahm_cash  =  array();
		$set_total_ssu_total_approve_by_dealer_cash  =  array();
		$set_total_ssu_total_reject_by_dealer_cash  =  array();
		$set_total_ssu_total_reject_reason_cash  =  array(	);
		$set_total_ssu_total_nilai_claim_cash  =  array();
		$set_total_nilai_claim_d_kepada_md   =  array();
		$set_total_total_claim_d_di_approve   =  array();


		$syarat_kredit_footer =  array();
		$syarat_cash_footer =  array();

		foreach ($data['kabupaten'] as $item) {
			$dealer_area  = $this->m_auto_claim_payment->dealer_kode_area($item->kode_samsat)->result();
			$temp_dealer = array();
			$footer_set_nilai_claim_d_kepada_md_array  = array();
			$footer_set_total_claim_d_di_approve_array = array();
			$footer_set_ssu_total_nilai_claim_credit  =  array();
			$footer_set_ssu_total_nilai_claim_cash    =  array();
			$footer_set_syarat_kredit  =  array();
			$footer_set_syarat_cash    =  array();

			foreach ($dealer_area as $row) {
			if ($row->id_dealer !==NULL){

				if ($row->id_dealer !== NULL){
				$filter_header = array(
					'id_program_md'  => $program,
					'start_periode'  =>  $set_sales_program->periode_awal,
					'end_periode'    =>  $set_sales_program->periode_akhir,
					'tipe_kendaraan' => $tipe,
					'id_dealer'      => $row->id_dealer,
				);

				$header_regular   = $this->m_auto_claim_payment->get_dealer($filter_header)->row();
				$header_gc        = $this->m_auto_claim_payment->get_dealer_gc($filter_header)->row();

				$datas['header'] = array();
				$items = new stdclass();

				$dealer_cash   	 = $set_sales_program->dealer_cash;
				$md_cash 	     = $set_sales_program->md_cash;
				$ahm_cash        = $set_sales_program->ahm_cash;
				
				$dealer_kredit   = $set_sales_program->dealer_kredit;
				$md_kredit       = $set_sales_program->md_kredit;
				$ahm_kredit      = $set_sales_program->ahm_kredit;

				$ahm_plus_md_cash =(int) $ahm_cash +  (int)$md_cash ;
				$ahm_plus_md_plus_d_cash = (int)$ahm_cash +  (int)$md_cash + (int)$dealer_cash ;

				$ahm_plus_md_kredit =(int) $ahm_kredit + (int)$md_kredit;
				$ahm_plus_md_plus_d_kredit =(int)$ahm_kredit + (int)$md_kredit + (int)$dealer_kredit ;

				if ($set_sales_program->jenis_program == 'DG'){
					$nilai_claim_kredit = $dealer_kredit  ;
					$nilai_claim_cash   = $dealer_cash ;

				}else if ($set_sales_program->jenis_program == 'SCP'){
					$nilai_claim_kredit = $ahm_plus_md_kredit ;
					$nilai_claim_cash = $ahm_plus_md_cash ;
				}

				 $set_jumlah_nilai_kredit = $header_regular->tot_approved_kredit !== null ? $header_regular->tot_approved_kredit : 0;
				 $set_nilai_claim_kredit = (int)$set_jumlah_nilai_kredit * (int)$nilai_claim_kredit;

				 $set_jumlah_nilai_cash = $header_regular->tot_approved_cash !== null ? $header_regular->tot_approved_cash : 0;
				 $set_nilai_claim_cash   = (int)$set_jumlah_nilai_cash * (int)  $nilai_claim_cash;

				 $nilai_claim_d_kepada_md  =  $set_nilai_claim_kredit;
				 $total_claim_d_di_approve = $header_regular->tot_approved_kredit + $header_regular->tot_approved_cash ;

				 $items->dealer       				          			   = $row->nama_dealer; 
				 $items->kode_dealer_md       				   			   = $row->kode_dealer_md; 
				 $items->ssu_total_full       				   			   = $header_regular->tot_ssu + $header_gc->tot_kredit_gc + $header_gc->tot_cash_gc; 
				 $footer_set_nilai_claim_d_kepada_md_array[]               = $items->nilai_claim_d_kepada_md  				   		   = $nilai_claim_d_kepada_md; 
				 $footer_set_total_claim_d_di_approve_array[]			   = $items->total_claim_d_di_approve    				   	   = $total_claim_d_di_approve; 
				 $items->ssu_total_credit       				   		   = $header_regular->tot_ssu_kredit + $header_gc->tot_kredit_gc; 
				 $items->ssu_total_claim_by_dealer_credit       		   = $header_regular->tot_claim_kredit    !== null ? $header_regular->tot_claim_kredit : 0;
				 $items->ssu_total_approve_by_ahm_credit       		       = $header_regular->tot_claim_kredit    !== null ? $header_regular->tot_claim_kredit : 0; 
				 $items->ssu_total_approve_by_dealer_credit       	 	   = $header_regular->tot_approved_kredit !== null ? $header_regular->tot_approved_kredit : 0;
				 $items->ssu_total_reject_by_dealer_credit       		   = $header_regular->tot_rejected_kredit !== null ? $header_regular->tot_rejected_kredit : 0; 
				 $items->ssu_total_reject_reason_credit       			   = $header_regular->tot_rejected_kredit !== null ? $header_regular->tot_rejected_kredit : 0; 
				 $footer_set_ssu_total_nilai_claim_credit[]				   = $items->ssu_total_nilai_claim_credit       			   = $set_nilai_claim_kredit; 
				 $items->ssu_total_cash       				   			   = $header_regular->tot_ssu_cash + $header_gc->tot_cash_gc; 
				 $items->ssu_total_claim_by_dealer_cash       			   = $header_regular->tot_approved_cash !== null ? $header_regular->tot_approved_cash : 0; 
				 $items->ssu_total_approve_by_ahm_cash       		       = $header_regular->tot_approved_cash !== null ? $header_regular->tot_approved_cash : 0;
				 $items->ssu_total_approve_by_dealer_cash       		   = $header_regular->tot_approved_cash !== null ? $header_regular->tot_approved_cash : 0;
				 $items->ssu_total_reject_by_dealer_cash       		       = $header_regular->tot_rejected_cash !== null ? $header_regular->tot_rejected_cash : 0; 
				 $items->ssu_total_reject_reason_cash       			   = $header_regular->tot_rejected_cash !== null ? $header_regular->tot_rejected_cash : 0;  
				 $footer_set_ssu_total_nilai_claim_cash[]				   = $items->ssu_total_nilai_claim_cash       			   	   = $set_nilai_claim_cash; 

				$syarat  = array(
					'id_program_md'  => $program,
					'kode_dealer_md' => $row->kode_dealer_md,
				);
				
				$footer_set_syarat_kredit[]  = $syarat_kredit  =  $this->m_auto_claim_payment->syarat_ketentuan($syarat,'kredit'); 
				$footer_set_syarat_cash[]    = $syarat_cash    =  $this->m_auto_claim_payment->syarat_ketentuan($syarat,'cash'); 


				$syarat_gc  = array(
					'kode_samsat'      => $item->kode_samsat,
					'id_program_md'  => $program,
				);
				
				$syarat_kredit_gc   =  $this->m_auto_claim_payment->syarat_ketentuan($syarat_gc,'kredit'); 
				$syarat_cash_gc     =  $this->m_auto_claim_payment->syarat_ketentuan($syarat_gc,'cash'); 

				$items->syarat_yang_direject_kredit      	               =  $syarat_kredit;
				$items->syarat_yang_direject_cash   					   =  $syarat_cash; 
				
				$temp_dealer['header'][] = $items;
				}
				}
				}
				
				$filter_footer = array(
					'id_program_md'  => $program,
					'start_periode'  =>  $set_sales_program->periode_awal,
					'end_periode'    =>  $set_sales_program->periode_akhir,
					'tipe_kendaraan' => $tipe,
					'kode_administrasi' => $item->kode_samsat,
				);

				$footer_regular   = $this->m_auto_claim_payment->get_dealer($filter_footer)->row();
				$footer_gc        = $this->m_auto_claim_payment->get_dealer_gc($filter_footer)->row();
		
				$footer = new stdclass();
				$footer->kabupaten       				   		= $item->kabupaten; 
				$set_total_ssu_total_full[] 					= $footer->ssu_total_full       				   		   = $footer_regular->tot_ssu + $footer_gc->tot_kredit_gc + $footer_gc->tot_cash_gc; 
				$set_total_nilai_claim_d_kepada_md[] 	        = $footer->nilai_claim_d_kepada_md  				       = array_sum($footer_set_nilai_claim_d_kepada_md_array);
				$set_total_total_claim_d_di_approve[] 	        = $footer->total_claim_d_di_approve    			           = array_sum($footer_set_total_claim_d_di_approve_array); 
				$set_total_ssu_total_credit[] 					= $footer->ssu_total_credit       				   		   = $footer_regular->tot_ssu_kredit + $footer_gc->tot_kredit_gc; 
				$set_total_ssu_total_claim_by_dealer_credit[] 	= $footer->ssu_total_claim_by_dealer_credit       		   = $footer_regular->tot_claim_kredit    !== null ? $footer_regular->tot_claim_kredit : 0;
				$set_total_ssu_total_approve_by_ahm_credit[] 	= $footer->ssu_total_approve_by_ahm_credit       		   = $footer_regular->tot_claim_kredit    !== null ? $footer_regular->tot_claim_kredit  : 0 ; 
				$set_total_ssu_total_approve_by_dealer_credit[] = $footer->ssu_total_approve_by_dealer_credit       	   = $footer_regular->tot_approved_kredit !== null ? $footer_regular->tot_approved_kredit : 0; 
				$set_total_ssu_total_reject_by_dealer_credit[] 	= $footer->ssu_total_reject_by_dealer_credit       		   = $footer_regular->tot_rejected_kredit !== null ? $footer_regular->tot_rejected_kredit : 0; 
				$set_total_ssu_total_reject_reason_credit[] 	= $footer->ssu_total_reject_reason_credit       		   = NULL;
				$set_total_ssu_total_nilai_claim_credit[] 		= $footer->ssu_total_nilai_claim_credit       			   = array_sum($footer_set_ssu_total_nilai_claim_credit); 
				$set_total_ssu_total_cash[] 					= $footer->ssu_total_cash       			   			   = $footer_regular->tot_ssu_cash + $footer_gc->tot_cash_gc; 
				$set_total_ssu_total_claim_by_dealer[] 			= $footer->ssu_total_claim_by_dealer       				   = $footer_regular->tot_approved_cash  !== null ? $footer_regular->tot_approved_cash : 0; 
				$set_total_ssu_total_approve_by_ahm_cash[]  	= $footer->ssu_total_approve_by_ahm_cash       		       = $footer_regular->tot_approved_cash  !== null ? $footer_regular->tot_approved_cash : 0; 
				$set_total_ssu_total_approve_by_dealer_cash[]   = $footer->ssu_total_approve_by_dealer_cash       		   = $footer_regular->tot_approved_cash  !== null ? $footer_regular->tot_approved_cash : 0; 
				$set_total_ssu_total_reject_by_dealer_cash[]    = $footer->ssu_total_reject_by_dealer_cash     			   = $footer_regular->tot_rejected_cash  !== null ? $footer_regular->tot_rejected_cash : 0; 
				$set_total_ssu_total_reject_reason_cash[] 	    = $footer->ssu_total_reject_reason_cash       			   = NULL;
				$set_total_ssu_total_nilai_claim_cash[] 	    = $footer->ssu_total_nilai_claim_cash       		   	   = array_sum($footer_set_ssu_total_nilai_claim_cash); 

				$syarat_footer  = array(
					'kode_samsat'      => $item->kode_samsat,
					'id_program_md'    =>  $program,
				);
		
				$syarat_kredit_footer[]   			   			     =  $footer->syarat_kredit_footer  =  $this->m_auto_claim_payment->syarat_ketentuan($syarat_footer,'kredit'); 
				$syarat_cash_footer[]                                =  $footer->syarat_cash_footer  = $this->m_auto_claim_payment->syarat_ketentuan($syarat_footer,'cash'); 
				$temp_footer[] = $footer;
				$temp[] = $temp_dealer;
		}

		$re_data_credit = $this->re_data($syarat_kredit_footer);
		$re_data_cash   = $this->re_data($syarat_cash_footer);

		$jumlah = array(
			'set_total_ssu_total_full'  => array_sum($set_total_ssu_total_full),
			'set_total_ssu_total_credit' => array_sum($set_total_ssu_total_credit),
			'set_total_ssu_total_claim_by_dealer_credit' => array_sum($set_total_ssu_total_claim_by_dealer_credit),
			'set_total_ssu_total_approve_by_ahm_credit' => array_sum($set_total_ssu_total_approve_by_ahm_credit),
			'set_total_ssu_total_approve_by_dealer_credit' => array_sum($set_total_ssu_total_approve_by_dealer_credit),
			'set_total_ssu_total_reject_by_dealer_credit' => array_sum($set_total_ssu_total_reject_by_dealer_credit),
			'set_total_ssu_total_reject_reason_credit' => array_sum($set_total_ssu_total_reject_reason_credit),
			'set_total_ssu_total_nilai_claim_credit' => array_sum($set_total_ssu_total_nilai_claim_credit),
			'set_total_ssu_total_cash' => array_sum($set_total_ssu_total_cash),
			'set_total_ssu_total_claim_by_dealer' => array_sum($set_total_ssu_total_claim_by_dealer),
			'set_total_ssu_total_approve_by_ahm_cash' => array_sum($set_total_ssu_total_approve_by_ahm_cash),
			'set_total_ssu_total_approve_by_dealer_cash' => array_sum($set_total_ssu_total_approve_by_dealer_cash),
			'set_total_ssu_total_reject_by_dealer_cash' => array_sum($set_total_ssu_total_reject_by_dealer_cash),
			'set_total_ssu_total_reject_reason_cash' => array_sum($set_total_ssu_total_reject_reason_cash),
			'set_total_ssu_total_nilai_claim_cash' => array_sum($set_total_ssu_total_nilai_claim_cash),
			'set_total_total_claim_d_di_approve' => array_sum($set_total_total_claim_d_di_approve),
			'set_total_ssu_total_credit' => array_sum($set_total_ssu_total_credit),
			'set_total_syarat_yang_direject_kredit' =>  array_sum($footer_set_nilai_claim_d_kepada_md_array),
			'set_total_syarat_yang_direject_cash' =>  array_sum($footer_set_total_claim_d_di_approve_array),
			'set_total_nilai_claim_d_kepada_md' =>  array_sum($set_total_nilai_claim_d_kepada_md),
			'set_total_syarat_kredit_footer' => $re_data_credit,
			'set_total_syarat_cash_footer'   => $re_data_cash,
		);

		$data['total']   = $jumlah;
		$data['value']   = $temp;
		$data['footers'] = $temp_footer;

		$this->load->view('h1/report/template/temp_auto_claim_report_md_obj',$data);
	}


	function re_data($data = NULL){
		$groupedData = array();
		foreach ($data as $innerArray) {
			foreach ($innerArray as $object) {
				$syarat_ketentuan = $object->syarat_ketentuan;
				if (!isset($groupedData[$syarat_ketentuan])) {
					$groupedData[$syarat_ketentuan] = 0; 
				}
				$groupedData[$syarat_ketentuan] += intval($object->jumlah); 
			}
		}
		$transformedData = array();
		foreach ($groupedData as $syarat_ketentuan => $jumlah) {
			$obj = new stdClass();
			$obj->syarat_ketentuan = $syarat_ketentuan;
			$obj->jumlah = $jumlah;
			$transformedData[] = $obj;
		}
		return $transformedData;
	}

	public function index()
	{						
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']		= "view";		
		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
		$data['dt_group_dealer'] = $this->db->query("SELECT  kode_dealer_md, nama_dealer,COUNT(1)  from ms_dealer where h1 ='1' and active ='1' group by kode_dealer_ahm order by nama_dealer asc ");
		$data['dt_ahm'] = $this->db->query("SELECT DISTINCT(id_program_ahm) AS id_program_ahm FROM tr_sales_program");
		$data['dt_md'] = $this->db->query("SELECT DISTINCT(id_program_md) AS id_program_md FROM tr_sales_program");


		$this->template($data);		    	    
	}	

	public function add()
	{						
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']		= "add";		
		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
		$data['dt_group_dealer'] = $this->db->query("SELECT  kode_dealer_md, nama_dealer,COUNT(1)  from ms_dealer where h1 ='1' and active ='1' group by kode_dealer_ahm order by nama_dealer asc ");
		$data['dt_ahm'] = $this->db->query("SELECT DISTINCT(id_program_ahm) AS id_program_ahm FROM tr_sales_program");
		$data['dt_md'] = $this->db->query("SELECT DISTINCT(id_program_md) AS id_program_md FROM tr_sales_program");
		$this->template($data);		    	    
	}	
	

	public function download()
	{					
		$filter['id_program_ahm'] = $data['id_program_ahm'] = $this->input->post('id_program_ahm');
		$filter['id_program_md']  = $data['id_program_md'] 	= $this->input->post('id_program_md');				
		$filter['id_dealer'] 	  = $data['id_dealer'] 	    = $this->input->post('id_dealer');
		$filter['dealer_group']   = $data['dealer_group']   = $this->input->post('dealer_group');
		$filter['start_periode'] 	= $this->input->post('start_periode');	
		$filter['end_periode']   	= $this->input->post('end_periode');	
		$button_check =$this->input->post('process');

		if ($button_check == 'scp_dg'){
			$this->download_exel_md($filter);
		}else if ($button_check == 'scp_dg_dev'){
			$this->download_exel_md_dev($filter);
		}else if ($button_check == 'd'){
			$this->download_exel_d($filter);
		}else if ($button_check == 'finance_rp'){
			$this->download_exel_report_finance($filter);
		}else{
			$this->load->view('h1/report/template/temp_sales_program',$data);
		}
	}

	public function download_exel_d($filter)
	{
		$data['start_periode']  = $filter['start_periode'];
		$data['end_periode']    = $filter['end_periode'];
		$data['id_program_ahm'] = $filter['id_program_ahm'];
		$data['dealer_group']   = $filter['dealer_group'];
		$data['id_dealer'] = $filter['id_dealer'];
		$sales_program = $this->m_auto_claim_payment->get_sales_program_initial($data)->result();
		$data['auto_claim_dealer'] =array();

		$no = 0;
		foreach ($sales_program as $key => $field) {
			$program = $field->id_program_md;
			$tipe = $this->m_auto_claim_payment->get_tipe_kendaraan($program);
			$no++;

			$filters = array(
				'id_program_md' => $program,
				'id_dealer'     => $data['id_dealer'],
				'dealer_group'     => $data['dealer_group'],
				'start_periode' => $data['start_periode'],
				'end_periode'   => $data['end_periode'],
				'tipe_kendaraan' => $tipe
			);

			$rows = $this->m_auto_claim_payment->get_dealer($filters)->row();
			$row_gc = $this->m_auto_claim_payment->get_dealer_gc($filters)->row();

			$items = new stdclass();	
			$items->id_program_md       			   =  $field->id_program_md;
			$items->id_dealer       				   =  $field->id_dealer;
			$items->judul_kegiatan       			   =  $field->kegiatan;
			$items->series       				       =  $field->series;
			$items->tot_ssu       				       =  (int) $field->tot_ssu +    (int) $row_gc->tot_kredit_gc + (int) $row_gc->tot_cash_gc;
			$items->tot_ssu       				       =  (int) $field->tot_ssu ;
			$items->tot_ssu_credit       			   =  (int)$rows->tot_ssu_kredit + (int) $row_gc->tot_kredit_gc;
			$items->tot_ssu_cash       				   =  (int) $rows->tot_ssu_cash   + (int) $row_gc->tot_cash_gc;
			$items->claim_credit       				   =  $rows->tot_claim_kredit;
			$items->approve_kredit       			   =  $rows->tot_approved_kredit;
			$items->rejected_kredit       			   =  $rows->tot_rejected_kredit;
			$items->claim_cash       				   =  $rows->tot_claim_cash;
			$items->approve_cash       				   =  $rows->tot_approved_cash;
			$items->rejected_cash       			   =  $rows->tot_rejected_cash;
			$data['auto_claim_dealer'][$key] = $items;
		}	
		$this->load->view('h1/report/template/temp_auto_claim_report_dealers',$data);
	}



	public function testing()
	{
		$data['auto_claim_dealer'] = NULL;
		// $data['kategori'] = $this->db->query("SELECT id_kategori,kategori  from ms_kategori  WHERE id_kategori_int  in ('4','2','3') ")->result();
		$data['kategori'] = $this->db->query("SELECT id_kategori,kategori  from ms_kategori  WHERE id_kategori_int  in ('2') ")->result();

		$program = "230200018-SP-001";
		$data['tipe_kendaraan'] = $tipe = $this->m_auto_claim_payment->get_tipe_kendaraan($program);

		var_dump($tipe );
		die();
		
		$data['table'] = array();
		foreach ($data['kategori']  as $key => $field) {
			$segment_kredit = $this->m_auto_claim_payment->get_segment_kendaraan_finance($field->id_kategori)->result();	
			$segment_kredit_set = 'lala';
			$obj = new stdclass();
			$obj->kategori = $field->kategori;
			$obj->id_kategori = $field->id_kategori;
			$obj->data_tipe = $segment_kredit_set;

			$jenis_beli['cash']   = array('cash');
			$jenis_beli['kredit'] = array('kredit');

			$obj->jenis_beli = $jenis_beli;
			$obj->total = 0;
			$data['table'][$key] = $obj;
		}


		$this->load->view('h1/report/template/temp_auto_claim_report_finance',$data);
	}

	public function monitoring_dashboard()
	{
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']		= "monitor";		
		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
		$data['dt_ahm'] = $this->db->query("SELECT DISTINCT(id_program_ahm) AS id_program_ahm FROM tr_sales_program");
		$data['dt_md'] = $this->db->query("SELECT DISTINCT(id_program_md) AS id_program_md FROM tr_sales_program");
		$this->template($data);		
	}

	public function aksis()
	{
		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();
		// Mendefinisikan lebar kolom
		$col1Width = 80;
		$col2Width = 50;

		// Mengisi data tabel
		$deskripsi1 = "Bukti Pengeluran Kas/Bank :";
		$jumlah1 = "Rp 1.000.000,-";

		$deskripsi2 = "Dibayarkan Kepada :";
		$jumlah2 = "Rp 500.000,-";

		$deskripsi3 = "Penggantian Claim Program Penjualan Periode :";
		$jumlah3 = "Rp 750.000,-";

		// Membuat tabel dengan border
		$pdf->Cell($col1Width, 6, $deskripsi1, 'LTRB', 0, 'L');
		$pdf->Cell($col2Width, 6, $jumlah1, 'LTRB', 1, 'L');

		$pdf->Cell($col1Width, 6, $deskripsi2, 'LTRB', 0, 'L');
		$pdf->Cell($col2Width, 6, $jumlah2, 'LTRB', 1, 'L');

		$pdf->Cell($col1Width, 6, $deskripsi3, 'LTRB', 0, 'L');
		$pdf->Cell($col2Width, 6, $jumlah3, 'LTRB', 1, 'L');
		$pdf->Output(); 

	}


	public function aksi()
	{
		$this->load->helper("terbilang");
	    $tgl = date('d F Y', strtotime(date('y-m-d'))); 

		$terbilang = number_to_words("10080000");


		$id_dealer = '103';

		$tgl1 ='2023-03-01';
		
		$tgl2 ='2023-03-31';

		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();
		
        
        $dealer = $this->db->query("SELECT ms_dealer.nama_dealer,ms_kabupaten.kabupaten FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan 
            INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
            INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
            WHERE ms_dealer.id_dealer = '$id_dealer'")->row();
        
        $pdf->SetFont('TIMES','',12);
		$pdf->Cell(35,6,'Tanggal : '.$tgl,0,1,'L');
		$pdf->Cell(35,6,'',0,1,'L');
		
		$pdf->Cell(35,6,'Tanggal Entry : '.$tgl,0,1,'L');
		$pdf->Cell(35,6,'Bank : ' ,0,1,'L');
		$pdf->Cell(35,6,'Tanggal Bayar :',0,1,'L');


		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Cell(35,6,'Bukti Pengeluran Kas/Bank :',0,1,'');

		$pdf->Cell(196,6,'Dibayarkan Kepada :','LTRB',1,'C');
		$pdf->Cell(196,6,'Penggantian Claim Program Penjualan Periode :','LTRB',1,'L');

		$pdf->Cell(40,6,'No. Juklak ','LTRB',0,'L');
		$pdf->Cell(30,6,'Type ','LTRB',0,'L');
		$pdf->Cell(30,6,'Unit Apporove ','LTRB',0,'L');
		$pdf->Cell(30,6,'Kontribusi Unit ','LTRB',0,'L');
		$pdf->Cell(30,6,'Nilai Kontribusi','LTRB',0,'L');
		$pdf->Cell(36,6,'Total','LTRB',1,'L');

		$pdf->Cell(196,10,'Terbilang : '.$terbilang.' Rupiah','LTR',1,'');

		$pdf->Cell(61,6,'Keterangan ','LTR',0,'C');
		$pdf->Cell(45,6,'Disetujui ','LTR',0,'C');
		$pdf->Cell(45,6,'Dibayar ','LTR',0,'C');
		$pdf->Cell(45,6,'Diterima ','LTR',1,'C');

		$pdf->Cell(61,30,' ','LRB',0,'C');
		$pdf->Cell(45,30,' ','LRB',0,'C');
		$pdf->Cell(45,30,' ','LRB',0,'C');
		$pdf->Cell(45,30,' ','LRB',0,'C');

		$pdf->Cell(35,30,'',0,1,'L');
		$pdf->Ln(4);
		
		$pdf->setX(10);
		$pdf->Ln(4);
		
		$pdf->Cell(63.3,6,'Hormat Kami',0,0,'C');
		$pdf->Ln(30);
		$pdf->Cell(63.3,6,'FEBRIANA',0,1,'C');
		$pdf->Cell(63.3,6,'Finance Head',0,0,'C');
		$pdf->Output(); 
	}

}