<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry_pengeluaran_bank extends CI_Controller {

    var $tables =   "tr_pengeluaran_bank";	
		var $folder =   "h1";
		var $page		=		"entry_pengeluaran_bank";
		var $isi		=		"bank_kas";
    var $pk     =   "id_pengeluaran_bank";
    var $title  =   "Entry Pengeluaran Bank";

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
		$data['dt_pengeluaran'] = $this->db->query("SELECT * FROM tr_pengeluaran_bank WHERE status <> 'approved'");
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']   = $this->isi;		
		$data['page']  = $this->page;		
		$data['title'] = $this->title;															
		$data['set']   = "form";				
		$data['mode']  = "insert";				
		$this->template($data);			
	}
	public function history()
	{				
		$data['isi']   = $this->isi;		
		$data['page']  = $this->page;		
		$data['title'] = $this->title;															
		$data['set']   = "history";						
		$data['dt_pengeluaran'] = $this->db->query("SELECT * FROM tr_pengeluaran_bank WHERE status = 'approved'");
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
	public function t_detail(){
		$id = $this->input->get('no_voucher');
		$dq = "SELECT * FROM tr_voucher_bank_detail 
				LEFT JOIN tr_voucher_bank ON tr_voucher_bank_detail.id_voucher_bank = tr_voucher_bank.id_voucher_bank 							
				WHERE tr_voucher_bank_detail.id_voucher_bank = '$id'";
		$data['dt_detail'] = $this->db->query($dq);
		$this->load->view('h1/t_pengeluaran_detail',$data);
	}
	public function cari_total(){				
		$id = $this->input->post('no_voucher');
		$pr_num	= $this->db->query("SELECT SUM(nominal) as jum FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id'");						       
	  if($pr_num->num_rows()>0){
	   	$row = $pr_num->row();			   	
	    $sum =	$row->jum;
		}else{
			$sum = 0;
		}		
		echo $sum;
	}
	
	public function cari_id(){				
		$th 						= date("y");
		$bln 						= date("m");	
		$thn 						= date("Y");	
		
		if($thn > '2020'){
			$pr_num 				= $this->db->query("SELECT * FROM tr_pengeluaran_bank where left(created_at,4)= '$thn' ORDER BY id_pengeluaran_bank DESC LIMIT 0,1");	
		}else{
			$pr_num 				= $this->db->query("SELECT * FROM tr_pengeluaran_bank ORDER BY id_pengeluaran_bank DESC LIMIT 0,1");	
		}					       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_pengeluaran_bank,2,5); 
	    $kode = $th.sprintf("%05d", $id+1);
		}else{
			$kode = $th."00001";
		}
		return $kode;
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		// $cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		// if($cek == 0){
			$id_pengeluaran_bank = $this->cari_id();
			$no_bg               = $this->input->post('no_bg');
			$tgl_cair            = $this->input->post('tgl_cair');
			$jenis               = $this->input->post('jenis');
			$no_voucher          = $this->input->post('no_voucher');
			for ($i = 0; $i < count($no_bg); $i++) {
				$no_bg_ = $no_bg[$i];
				if ($tgl_cair[$i]!='') {
					if ($jenis[$i]=='Transfer') {
						$total = $this->db->query("SELECT nominal_transfer FROM tr_voucher_bank_transfer WHERE id_voucher_bank_transfer='$no_bg_'")->row()->nominal_transfer;
						$detail['id_voucher_bank_transfer'] = $no_bg[$i];
						$detail['jenis']                    = 'Transfer';
					}else{
						$total = $this->db->query("SELECT nominal_bg FROM tr_voucher_bank_bg WHERE no_bg='$no_bg_'")->row()->nominal_bg;
						$detail['no_bg'] = $no_bg[$i];
						$detail['jenis'] = 'BG';
					}
					$detail['id_pengeluaran_bank'] = $i!=0?$id_pengeluaran_bank:$id_pengeluaran_bank++;
					$detail['tgl_entry']           = $tgl;
					$detail['tgl_cair']            = $tgl_cair[$i];
					$detail['total']               = (int)$total;
					$detail['no_voucher']          = $no_voucher;
					$detail['status']              = "input";		
					$detail['created_at']          = $waktu;		
					$detail['created_by']          = $login_id;
					$details[]                     = $detail;
				}
			}
			// var_dump($details);
			$this->db->insert_batch('tr_pengeluaran_bank',$details);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_pengeluaran_bank'>";			
		// }else{
		// 	$_SESSION['pesan'] 	= "Duplicate entry for primary key";
		// 	$_SESSION['tipe'] 	= "danger";
		// 	echo "<script>history.go(-1)</script>";
		// }		
	}
	public function approve()
	{		
		$waktu              = gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl                = gmdate("y-m-d", time()+60*60*7);
		$login_id           = $this->session->userdata('id_user');
		$tabel              = $this->tables;
		
		$pk                 = $this->pk;
		$id                 = $this->input->get('id');		
		$data['status']     = "approved";		
		$data['updated_at'] = $waktu;		
		$data['updated_by'] = $login_id;
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_pengeluaran_bank'>";						
	}

	public function generate()
	{
		$no_voucher = $this->input->POST('no_voucher');
		//$cek_voucher2 = $this->m_admin->getByID('tr_voucher_bank','id_voucher_bank',$no_voucher);		
		$cek_voucher = $this->db->query("SELECT * FROM tr_voucher_bank WHERE id_voucher_bank = '$no_voucher'");				
		if ($cek_voucher->num_rows()>0) {
			$row = $cek_voucher->row();
			if ($row->via_bayar=='BG') {
				$details    = $this->db->query("SELECT *,'BG / Cek' as jenis FROM tr_voucher_bank_bg WHERE id_voucher_bank='$no_voucher' AND no_bg NOT IN(SELECT no_bg FROM tr_pengeluaran_bank WHERE status='approved' AND no_bg IS NOT NULL)")->result();		
			}else{
				$details    = $this->db->query("SELECT 'Transfer' AS jenis, id_voucher_bank_transfer as no_bg, nominal_transfer AS nominal_bg,tgl_transfer as tgl_bg FROM tr_voucher_bank_transfer WHERE id_voucher_bank='$no_voucher' AND id_voucher_bank_transfer NOT IN (SELECT id_voucher_bank_transfer FROM tr_pengeluaran_bank WHERE status='approved' AND id_voucher_bank_transfer IS NOT NULL)")->result();		
			}
		}
		echo json_encode($details);
	}
}