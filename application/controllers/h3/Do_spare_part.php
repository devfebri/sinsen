<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Do_spare_part extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"do_spare_part";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "DO Spare Part";

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
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML');
		$this->load->library('mpdf_l');		

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
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$data['dt_do_spare_part'] = $this->db->query("SELECT * FROM tr_create_do_spare INNER JOIN tr_so_spare ON tr_create_do_spare.no_so_spare = tr_so_spare.no_so_spare		 				
				INNER JOIN ms_dealer ON tr_so_spare.id_dealer = ms_dealer.id_dealer WHERE tr_create_do_spare.status_do <> 'rejected'");			
		$this->template($data);			
	}	
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "detail";					
		$id = $this->input->get("id");
		$data['dt_sql'] 	= $this->db->query("SELECT * FROM tr_create_do_spare INNER JOIN tr_so_spare ON tr_create_do_spare.no_so_spare = tr_so_spare.no_so_spare		 				
				INNER JOIN ms_dealer ON tr_so_spare.id_dealer = ms_dealer.id_dealer
				LEFT JOIN ms_karyawan_dealer ON tr_so_spare.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
		 		WHERE tr_create_do_spare.no_do_spare_part = '$id'");			
		$this->template($data);	
	}	
	public function t_detail(){				
		$no_do_spare_part = $this->input->post('no_do_spare_part');		
		$data['sql'] = $this->db->query("SELECT * FROM tr_create_do_spare_detail LEFT JOIn ms_part ON tr_create_do_spare_detail.id_part = ms_part.id_part 			
			WHERE tr_create_do_spare_detail.no_do_spare_part = '$no_do_spare_part'");
		$this->load->view('h3/t_do_spare_part',$data);
	}	
	public function detail_popup()
	{				
		$data['no_do_spare_part'] = $this->input->post("no_do_spare_part");		
		$data['isi']    = $this->page;			
		$data['title']	= $this->title;								
		$this->load->view("h3/t_do_spare_popup",$data);		
	}		
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');										
		$save 	= $this->input->post("save");		
		if($save == 'approve'){
			$da['no_do_spare_part'] = $no_do_spare_part = $this->input->post("no_do_spare_part");		
			$da['diskon_cashback'] 	= $this->input->post("diskon_cashback");		
			$da['diskon_insentif'] 	= $this->input->post("diskon_insentif");		
			$da['status_do']				= "approved";						
			$da['updated_at'] 			= $waktu;		
			$da['updated_by'] 			= $login_id;					

			$so = $this->db->query("SELECT * FROM tr_create_do_spare INNER JOIN tr_so_spare ON tr_create_do_spare.no_so_spare = tr_so_spare.no_so_spare 
				WHERE tr_create_do_spare.no_do_spare_part = '$no_do_spare_part'")->row();			
			$no_pl_part = $this->cari_id_pl();
			$ds['no_pl_part'] = $no_pl_part; 
			$ds['tgl_pl'] 		= $tgl;
			$ds['jenis_po'] 	= $so->tipe_po;
			$ds['no_do_part'] = $so->no_do_spare_part;
			$ds['id_dealer'] 	= $so->id_dealer;
			$ds['status_pl']				= "input";						
			$ds['created_at'] 			= $waktu;		
			$ds['created_by'] 			= $login_id;				
			$sq = $this->db->query("SELECT * FROM tr_create_do_spare_detail WHERE no_do_spare_part = '$no_do_spare_part'");
			foreach ($sq->result() as $isi) {
				$dsa['no_pl_part'] = $no_pl_part;
				$dsa['id_part'] = $id_part = $isi->id_part;
				$dsa['qty_supply'] = $isi->qty_supply;
				$dsa['amount'] = $isi->amount;

				$cek1 = $this->db->query("SELECT * FROM tr_pl_part_detail WHERE no_pl_part = '$no_pl_part' AND id_part = '$id_part'");
				if($cek1->num_rows() > 0){
					$this->m_admin->update("tr_pl_part_detail",$dsa,"id_detail",$cek1->row()->id_detail);
				}else{					
					$this->m_admin->insert("tr_pl_part_detail",$dsa);	
				}
			}
			$cek = $this->m_admin->getByID("tr_pl_part","no_do_part",$no_do_spare_part);
			if($cek->num_rows() > 0){
				$this->m_admin->update("tr_pl_part",$ds,"no_pl_part",$cek->row()->no_pl_part);
			}else{
				$this->m_admin->insert("tr_pl_part",$ds);
			}

		}else{
			$no_do_spare_part = $this->input->post("no_do_spare_part");					
			$da['alasan_reject'] 	= $this->input->post("alasan_reject");		
			$da['status_do']				= "rejected";						
			$da['updated_at'] 			= $waktu;		
			$da['updated_by'] 			= $login_id;					
		}
		
		$this->m_admin->update("tr_create_do_spare",$da,"no_do_spare_part",$no_do_spare_part);													

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/do_spare_part'>";
	}	
	public function cetak(){
		$data['tanggal'] = $tgl 				= gmdate("d/m/Y", time()+60*60*7);
		$data['no_do_spare_part'] = $id = $this->input->get("id");		
		$data['sql'] 	= $this->db->query("SELECT * FROM tr_create_do_spare INNER JOIN tr_so_spare ON tr_create_do_spare.no_so_spare = tr_so_spare.no_so_spare		 				
				INNER JOIN ms_dealer ON tr_so_spare.id_dealer = ms_dealer.id_dealer
		 		WHERE tr_create_do_spare.no_do_spare_part = '$id'")->row();			
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in='UTF-8';
    $mpdf->autoLangToFont = true;  	
  	$html = $this->load->view('h3/cetak_do_spare', $data, true);    
    $mpdf->WriteHTML($html);    
    $output = 'cetak_.pdf';
    $mpdf->Output("$output", 'I');
	}
	public function cari_id_pl(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_pl_part ORDER BY no_pl_part DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_pl_part)-3;
			$id 	= substr($row->no_pl_part,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "PLP/".$kode1;
		}else{
			$kode = "PLP/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}
}