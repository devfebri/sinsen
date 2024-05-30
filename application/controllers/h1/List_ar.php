<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class List_ar extends CI_Controller {
    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"list_ar";
		var $isi		=		"list_ap_ar";
    var $pk     =   "no_do";
    var $title  =   "List AR";
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
	

	
	public function index()
	{				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "view";	
		$data['dt_invoice'] = $this->db->query("SELECT tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur,tr_do_po.no_do,ms_dealer.nama_dealer, tr_invoice_dealer.bunga_bank 
						FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
      					INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
      					WHERE tr_invoice_dealer.status_invoice = 'printable' AND  
      					tr_invoice_dealer.status_bayar <> 'lunas' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");					
		// $data['dt_rekap_old'] 	= $this->db->query("SELECT tr_monout_piutang_bbn.*,ms_dealer.nama_dealer FROM tr_monout_piutang_bbn 
        // 				INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
	    //   				INNER JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
	    //   				LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
	    //   				WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'");
		
		$data['dt_rekap'] 	= $this->db->query("SELECT tr_monout_piutang_bbn.no_bastd,tr_monout_piutang_bbn.tgl_rekap,tr_monout_piutang_bbn.total,ms_dealer.nama_dealer,SUM(tr_penerimaan_bank_detail.nominal) AS tot 
								FROM tr_monout_piutang_bbn 
								INNER JOIN tr_penerimaan_bank_detail ON tr_monout_piutang_bbn.no_bastd = tr_penerimaan_bank_detail.referensi
								INNER JOIN tr_penerimaan_bank ON tr_penerimaan_bank_detail.id_penerimaan_bank = tr_penerimaan_bank.id_penerimaan_bank
        				INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
	      				INNER JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
	      				LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
	      				WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'
	      				AND tr_penerimaan_bank.status = 'approved' GROUP BY tr_penerimaan_bank_detail.referensi
	      				HAVING total - tot > 0");		
		$this->template($data);			
	}



	public function all()
	{				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "all";				
		$data['download']		= "";				
		$this->template($data);			
	}
	public function download()
	{				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;				
		$data['dt_invoice'] = $this->db->query("SELECT tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur,tr_do_po.no_do,ms_dealer.nama_dealer FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
      					INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
      					WHERE tr_invoice_dealer.status_invoice = 'printable' AND  
      					tr_invoice_dealer.status_bayar <> 'lunas' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");					
		$data['dt_rekap_old'] 	= $this->db->query("SELECT tr_monout_piutang_bbn.*,ms_dealer.nama_dealer FROM tr_monout_piutang_bbn 
        				INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
	      				INNER JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
	      				LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
	      				WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'");
		$data['dt_rekap'] 	= $this->db->query("SELECT tr_monout_piutang_bbn.no_bastd,tr_monout_piutang_bbn.tgl_rekap,tr_monout_piutang_bbn.total,ms_dealer.nama_dealer,SUM(tr_penerimaan_bank_detail.nominal) AS tot 
								FROM tr_monout_piutang_bbn 
								INNER JOIN tr_penerimaan_bank_detail ON tr_monout_piutang_bbn.no_bastd = tr_penerimaan_bank_detail.referensi
								INNER JOIN tr_penerimaan_bank ON tr_penerimaan_bank_detail.id_penerimaan_bank = tr_penerimaan_bank.id_penerimaan_bank
        				INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
	      				INNER JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
	      				LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
	      				WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'
	      				AND tr_penerimaan_bank.status = 'approved' GROUP BY tr_penerimaan_bank_detail.referensi
	      				HAVING total - tot > 0");
		$this->load->view("h1/file_list_ar",$data);
	}
	public function history()
	{				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "history";				
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function view()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$this->template($data);			
	}	
}