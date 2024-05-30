<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tagihan_lain extends CI_Controller {

    var $tables =   "tr_tagihan_lain";	
		var $folder =   "h1";
		var $page		=		"tagihan_lain";
		var $isi		=		"invoice_terima";
    var $pk     =   "id_tagihan_lain";
    var $title  =   "Tagihan Lain-lain";

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
		$data['dt_rekap']	= $this->db->query("SELECT tr_tagihan_lain.*,ms_karyawan.nama_lengkap FROM tr_tagihan_lain
			LEFT JOIN ms_user ON tr_tagihan_lain.created_by=ms_user.id_user
			LEFT JOIN ms_karyawan ON ms_user.id_karyawan_dealer=ms_karyawan.id_karyawan
		 ORDER BY id_tagihan_lain DESC");
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
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
		$token = $this->m_admin->get_tmp();		
		$pr_num = $this->db->query("SELECT * FROM tr_tagihan_lain ORDER BY id_tagihan_lain DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_tagihan_lain)-3;
			$id 	= substr($row->id_tagihan_lain,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/THL/".$isi.$token;
		}else{
			$kode = $th.$bln."/THL/00001".$token;
		}						
		echo $kode;
	}
	public function cari_id_real(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");		
		$pr_num = $this->db->query("SELECT * FROM tr_tagihan_lain ORDER BY id_tagihan_lain DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_tagihan_lain)-3;
			$id 	= substr($row->id_tagihan_lain,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/THL/".$isi;
		}else{
			$kode = $th.$bln."/THL/00001";
		}						
		return $kode;
	}
	public function ambil_tipe(){
		$tipe = $this->input->post("tipe");
		$kode_customer = $this->input->post("kode_customer");
		$data='';
		if($tipe == 'Vendor'){
			$rt = $this->db->query("SELECT * FROM ms_vendor WHERE active = '1' ORDER BY vendor_name ASC");
	    $data .= "<option value=''>- choose -</option>";
	    foreach($rt->result() as $val) {	     
	      $select = $kode_customer==$val->vendor_name?'selected':'';
	      $data .= "<option value='$val->vendor_name' $select>$val->vendor_name</option>\n";      
	    }
		}else{
			$rt = $this->db->query("SELECT * FROM ms_dealer WHERE active = '1' ORDER BY nama_dealer ASC");
	    $data .= "<option value=''>- choose -</option>";
	    foreach($rt->result() as $val) {
	      $select = $kode_customer==$val->nama_dealer?'selected':'';	      
	      $data .= "<option value='$val->nama_dealer' $select>$val->nama_dealer</option>\n";      
	    }
		}		
		echo $data;
	}
	public function t_data(){
		$id_tagihan_lain = $this->input->post('id_tagihan_lain');		
		$data['dt_rekap'] = $this->db->query("SELECT * FROM tr_tagihan_lain_detail
				WHERE tr_tagihan_lain_detail.id_tagihan_lain = '$id_tagihan_lain'");						
		$this->load->view('h1/t_tagihan_lain',$data);
	}
	public function save_data(){				
		$id_tagihan_lain			= $this->input->post('id_tagihan_lain');			
		$data['id_tagihan_lain']	= $this->input->post('id_tagihan_lain');			
		$data['no_po']				= $this->input->post('no_po');			
		$data['tgl_po']				= $this->input->post('tgl_po');					
		$data['no_kwitansi']	= $this->input->post('no_kwitansi');					
		$data['tgl_kwitansi']	= $this->input->post('tgl_kwitansi');					
		$data['no_bast']			= $this->input->post('no_bast');					
		$data['tgl_bast']			= $this->input->post('tgl_bast');	
		$data['due_datetime']	= $this->input->post('due_datetime');	
		$data['harga']			= $this->input->post('harga');	
		$this->m_admin->insert("tr_tagihan_lain_detail",$data);								
		echo "nihil";
	}
	public function delete_data(){
		$id = $this->input->post('id_tagihan_lain_detail');				
		$this->db->query("DELETE FROM tr_tagihan_lain_detail WHERE id_tagihan_lain_detail = '$id'");					
		echo "nihil";
	}
	public function save(){
		$waktu                   = gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl                     = gmdate("y-m-d", time()+60*60*7);
		$login_id                = $this->session->userdata('id_user');
		$id_tagihan_lain         = $this->cari_id_real();				
		$id_old                  = $this->input->post('id_tagihan_lain');		
		$data['id_tagihan_lain'] = $id_tagihan_lain;
		$data['tipe_customer']   = $this->input->post('tipe_customer');			
		$data['kode_customer']   = $this->input->post('kode_customer');					
		$data['tgl_tagih']       = $this->input->post('tgl_tagih');					
		$data['nama_divisi']     = $this->input->post('nama_divisi');					
		$data['status_tagihan']  = "input";
		$data['created_at']      =	$waktu; 			
		$data['created_by']      =	$login_id; 					

		$cek = $this->db->get_where("tr_tagihan_lain_detail",array("id_tagihan_lain"=>$id_old));
		foreach ($cek->result() as $isi) {
			$this->db->query("UPDATE tr_tagihan_lain_detail SET id_tagihan_lain = '$id_tagihan_lain' WHERE id_tagihan_lain = '$id_old'");			
		}
		$this->m_admin->insert("tr_tagihan_lain",$data);			
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_lain'>";				
	}	
	public function reject(){	
			$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->get('id');			
			$data['status_tagihan'] 			= "rejected";
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$no_do);		            	      					

			$_SESSION['pesan'] 	= "Data has been rejected successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_lain'>";		
	}
	public function approve(){	
			$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->get('id');			
			$data['status_tagihan'] 			= "approved";
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$no_do);		            	      					

			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_lain'>";		
	}
	public function kelengkapan()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "kelengkapan";				
		$this->template($data);			
	}

	public function edit()
	{				
		$id            = $this->input->get('id');
		$data['isi']   = $this->isi;		
		$data['page']  = $this->page;		
		$data['title'] = $this->title;															
		$data['set']   = "edit";		
		$row = $this->db->get_where('tr_tagihan_lain',['id_tagihan_lain'=>$id]);
		if ($row->num_rows()>0) {
			$data['row']     = $row->row();
			$data['details'] = $this->db->get_where('tr_tagihan_lain_detail',['id_tagihan_lain'=>$id])->result();
			$this->template($data);			
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_lain'>";		

		}
	}
	public function save_edit(){
		$waktu                   = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl                     = gmdate("y-m-d", time()+60*60*7);
		$login_id                = $this->session->userdata('id_user');				
		$id_tagihan_lain         = $this->input->post('id_tagihan_lain');		
		$data['id_tagihan_lain'] = $id_tagihan_lain;
		$data['tipe_customer']   = $this->input->post('tipe_customer');			
		$data['kode_customer']   = $this->input->post('kode_customer');					
		$data['tgl_tagih']       = $this->input->post('tgl_tagih');					
		$data['nama_divisi']     = $this->input->post('nama_divisi');					
		$data['status_tagihan']  = "input";
		$data['updated_at']      =	$waktu; 			
		$data['updated_by']      =	$login_id; 					

		$no_po        = $this->input->post('no_po');
		$tgl_po       = $this->input->post('tgl_po');
		$no_kwitansi  = $this->input->post('no_kwitansi');
		$tgl_kwitansi = $this->input->post('tgl_kwitansi');
		$no_bast      = $this->input->post('no_bast');
		$tgl_bast     = $this->input->post('tgl_bast');
		$due_datetime = $this->input->post('due_datetime');
		$harga        = $this->input->post('harga');
		foreach ($no_po as $key=>$po) {
			$dt_detail[$key] =[ 'id_tagihan_lain' => $id_tagihan_lain,
								'no_po'        =>$no_po[$key],
								'tgl_po'       =>$tgl_po[$key],
								'no_kwitansi'  =>$no_kwitansi[$key],
								'tgl_kwitansi' =>$tgl_kwitansi[$key],
								'no_bast'      =>$no_bast[$key],
								'tgl_bast'     =>$tgl_bast[$key],
								'due_datetime' =>$due_datetime[$key],
								'harga'        =>$this->m_admin->ubah_rupiah($harga[$key])
			] ;
		}
		$this->db->trans_begin();
			$this->db->update('tr_tagihan_lain',$data,['id_tagihan_lain'=>$id_tagihan_lain]);
			$this->db->delete('tr_tagihan_lain_detail',['id_tagihan_lain'=>$id_tagihan_lain]);
			if (isset($dt_detail)) {
				$this->db->insert_batch('tr_tagihan_lain_detail',$dt_detail);
			}
		if ($this->db->trans_status() === FALSE)
        {
        	$this->db->trans_rollback();
            $_SESSION['pesan'] 	= "Something Went Wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_lain'>";		
        }
        else
        {
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_lain'>";			
        }
	}	
}