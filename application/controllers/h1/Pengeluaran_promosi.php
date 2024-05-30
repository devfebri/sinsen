<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_promosi extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"pengeluaran_promosi";
    var $pk     =   "no_do";
    var $title  =   "Pengeluaran Promosi";

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
		$data['data']	= $this->db->query("SELECT * FROM tr_pengeluaran_promosi 
			LEFT join ms_dealer on tr_pengeluaran_promosi.id_dealer=ms_dealer.id_dealer
			ORDER BY id_pengeluaran_promosi DESC");	
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}

	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "edit";		
		$id 			= $this->input->get('id');		
		$dt_pengeluaran	= $this->db->query("SELECT * FROM tr_pengeluaran_promosi WHERE id_pengeluaran_promosi='$id'");	
		if ($dt_pengeluaran->num_rows()>0) {
			$data['row']=$dt_pengeluaran->row();
			$this->template($data);	
		}else{
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengeluaran_promosi'>";	

		}
	}
	public function konfirmasi()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Konfirmasi ".$this->title;															
		$data['set']		= "konfirmasi";				
		$this->template($data);			
	}

	public function addDetail(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$id_pengeluaran_promosi = $this->input->post('id_pengeluaran_promosi');	
		if ($id_pengeluaran_promosi>0) {
			$data['id_pengeluaran_promosi']			= $this->input->post('id_pengeluaran_promosi');	
		}
		$data['item_barang']			= $this->input->post('item_barang');			
		$data['kategori_item']			= $this->input->post('kategori_item');			
		$data['qty_on_hand']			= $this->input->post('qty_on_hand');			
		$data['qty_kirim']				= $this->input->post('qty_kirim');			
		$data['item_barang']			= $this->input->post('item_barang');			
		$data['item_barang']			= $this->input->post('item_barang');			
		$data['status']					= 'new';					
		$data['created_by']				= $login_id;					
		$data['created_at']				= $waktu;					
		$this->m_admin->insert("tr_pengeluaran_promosi_detail",$data);		
		echo "nihil";
	}

	public function delDetail(){
		$id			= $this->input->post('id');			
		$this->m_admin->delete("tr_pengeluaran_promosi_detail",'id',$id);		
		echo "nihil";
	}

	public function getDetail()
	{
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	
		$id 		= $this->input->post('id');
		if ($id==null or $id==0) {
			$data['detail']=$this->db->query("SELECT tr_pengeluaran_promosi_detail.*,ms_item_promosi.item_promosi FROM tr_pengeluaran_promosi_detail
					LEFT JOIN ms_item_promosi on tr_pengeluaran_promosi_detail.item_barang=ms_item_promosi.id_item_promosi
			 WHERE tr_pengeluaran_promosi_detail.status='new' AND tr_pengeluaran_promosi_detail.created_by='$login_id' AND tr_pengeluaran_promosi_detail.id_pengeluaran_promosi is null");
		}else{
			$data['detail']=$this->db->query("SELECT  tr_pengeluaran_promosi_detail.*,ms_item_promosi.item_promosi FROM tr_pengeluaran_promosi_detail 
				LEFT JOIN ms_item_promosi on tr_pengeluaran_promosi_detail.item_barang=ms_item_promosi.id_item_promosi
				WHERE id_pengeluaran_promosi='$id'");
		}
		$data['id'] =$id==null?0:$id;
		$this->load->view('h1/t_pengeluaran_promosi',$data);
	}

	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	

		$data['no_do'] 			= $this->input->post('no_do');		
		$data['tanggal_do'] 			= $this->input->post('tanggal_do');		
		$data['no_surat_jalan'] 		= $this->input->post('no_surat_jalan');	
		$data['tanggal_surat_jalan'] = $this->input->post('tanggal_surat_jalan');		
		$data['no_invoice'] = $this->input->post('no_invoice');		
		$data['tanggal_invoice'] = $this->input->post('tanggal_invoice');		
		$data['id_dealer'] = $this->input->post('id_dealer');		
		$data['nama_pembuat_marketing'] = $this->input->post('nama_pembuat_marketing');		
		$data['nama_penerima'] 			= $this->input->post('nama_penerima');		
		$data['nama_penyerah'] 			= $this->input->post('nama_penyerah');
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;


		$this->db->trans_begin();
				$this->m_admin->insert('tr_pengeluaran_promosi',$data);
				$lastHeader=$this->db->query("SELECT id_pengeluaran_promosi From tr_pengeluaran_promosi WHERE created_by='$login_id' AND status='input'")->row()->id_pengeluaran_promosi;

				$this->db->query("UPDATE tr_pengeluaran_promosi_detail set status='input', id_pengeluaran_promosi = '$lastHeader', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengeluaran_promosi/add'>";	
            }
            else
            {
                    $this->db->trans_commit();
                   $_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengeluaran_promosi/add'>";	
            }
			
	}
	public function save_edit()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	

		$data['id_pengeluaran_promosi'] 			= $this->input->post('id_pengeluaran_promosi');		
		$data['no_do'] 			= $this->input->post('no_do');		
		$data['tanggal_do'] 			= $this->input->post('tanggal_do');		
		$data['no_surat_jalan'] 		= $this->input->post('no_surat_jalan');	
		$data['tanggal_surat_jalan'] = $this->input->post('tanggal_surat_jalan');		
		$data['no_invoice'] = $this->input->post('no_invoice');		
		$data['tanggal_invoice'] = $this->input->post('tanggal_invoice');		
		$data['id_dealer'] = $this->input->post('id_dealer');		
		$data['nama_pembuat_marketing'] = $this->input->post('nama_pembuat_marketing');		
		$data['nama_penerima'] 			= $this->input->post('nama_penerima');		
		$data['nama_penyerah'] 			= $this->input->post('nama_penyerah');
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;

		$this->m_admin->update('tr_pengeluaran_promosi',$data,'id_pengeluaran_promosi',$this->input->post('id_pengeluaran_promosi'));
        $_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengeluaran_promosi/edit?id=".$this->input->post('id_pengeluaran_promosi')."'>";				
	}
}