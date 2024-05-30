<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_gift extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"penerimaan_gift";
    var $pk     =   "no_do";
    var $title  =   "Penerimaan Gift";

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
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";	
		$data['show']	= $this->db->query("SELECT * FROM tr_penerimaan_gift ORDER BY id_penerimaan DESC")			;
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function konfirmasi()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Konfirmasi ".$this->title;															
		$data['set']		= "konfirmasi";				
		$this->template($data);			
	}

	public function getDetail()
	{
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	
		$id 		= $this->input->post('id');
		if ($id==null or $id==0) {
			$data['detail']=$this->db->query("SELECT tr_penerimaan_gift_detail.*,ms_apparel.apparel FROM tr_penerimaan_gift_detail
					LEFT JOIN ms_apparel on tr_penerimaan_gift_detail.id_apparel=ms_apparel.id_apparel
			 WHERE tr_penerimaan_gift_detail.status='new' AND tr_penerimaan_gift_detail.created_by='$login_id' AND tr_penerimaan_gift_detail.id_penerimaan is null");
		}else{
			$data['detail']=$this->db->query("SELECT  tr_penerimaan_gift_detail.*,ms_apparel.apparel FROM tr_penerimaan_gift_detail 
				LEFT JOIN ms_apparel on tr_penerimaan_gift_detail.id_apparel=ms_apparel.id_apparel
				WHERE id_penerimaan='$id'");
		}
		$data['id'] =$id==null?0:$id;
		$this->load->view('h1/t_penerimaan_gift',$data);
	}

	public function addDetail(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$id_penerimaan = $this->input->post('id_penerimaan');	
		if ($id_penerimaan>0) {
			$data['id_penerimaan']			= $this->input->post('id_penerimaan');	
		}
		$data['id_apparel']			= $this->input->post('id_apparel');					
		$data['qty_penerimaan']			= $this->input->post('qty_penerimaan');			
		$data['keterangan']				= $this->input->post('keterangan');				
		$data['status']					= 'new';					
		$data['created_by']				= $login_id;					
		$data['created_at']				= $waktu;					
		$this->m_admin->insert("tr_penerimaan_gift_detail",$data);		
		echo "nihil";
	}

	public function delDetail(){
		$id			= $this->input->post('id');			
		$this->m_admin->delete("tr_penerimaan_gift_detail",'id',$id);		
		echo "nihil";
	}

	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	

		$data['no_po'] 			= $this->input->post('no_po');		
		$data['tanggal_po'] 			= $this->input->post('tanggal_po');		
		$data['no_surat_jalan'] 		= $this->input->post('no_surat_jalan');	
		$data['tanggal_surat_jalan'] = $this->input->post('tanggal_surat_jalan');		
		$data['tanggal_penerimaan'] = $this->input->post('tanggal_penerimaan');		
		$data['keterangan'] = $this->input->post('keterangan');		
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;


		$this->db->trans_begin();
				$this->m_admin->insert('tr_penerimaan_gift',$data);
				$lastHeader=$this->db->query("SELECT id_penerimaan From tr_penerimaan_gift WHERE created_by='$login_id' AND status='input'")->row()->id_penerimaan;

				$this->db->query("UPDATE tr_penerimaan_gift_detail set status='input', id_penerimaan = '$lastHeader', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_gift/add'>";	
            }
            else
            {
                    $this->db->trans_commit();
                   $_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_gift/add'>";	
            }
			
	}

}