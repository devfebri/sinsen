<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reasons extends CI_Controller {

	var $tables = "ms_reasons";	
	var $folder = "master";
	var $page   = "reasons";
	var $title  = "Master Reasons";
   	// var $order_column_part = array("id_part","nama_part",'kelompok_vendor',null); 

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
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
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
		$data['set']	= "index";	
		$data['reasons']    = $this->db->query("SELECT * FROM ms_reasons ORDER BY created_at DESC");					
		$this->template($data);	
	}

	public function add()
	{				
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);	
	}
	
	public function get_id_diskon()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$get_data  = $this->db->query("SELECT * FROM ms_reasons ORDER BY id_reasons DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$id_reasons = substr($row->id_reasons, -5);
				$new_kode   = 'RE_'.sprintf("%'.05d",$id_reasons+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('ms_reasons',['id_reasons'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -5);
						$new_kode = 'RE_'.sprintf("%'.05d",$id_reasons+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = 'RE_00001';
	   		}
   		return strtoupper($new_kode);
	}

	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
	
		$id_reasons = $data['id_reasons']           = $this->get_id_diskon();
		$data['deskripsi']  = $this->input->post('deskripsi');
		$data['fungsi']     = $this->input->post('fungsi');
		$data['created_at'] = $waktu;		
		$data['created_by'] = $login_id;

		$this->db->trans_begin();
			$this->db->insert('ms_reasons',$data);
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
      	}
      	else
      	{
        	$this->db->trans_commit();
     //    	$rsp = ['status'=> 'sukses',
					// 'link'=>base_url('master/reasons')
				 //   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/reasons'>";
      	}
	}

	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= 'id_reasons';
		$id 			= $this->input->get('id');		
		$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
		$this->db->trans_commit();			
		$result = 'Success';									

		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/reasons'>";
	}

	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function edit()
	{				
		$id              = $this->input->get('id');
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'edit';
		$data['row'] 	 = $this->db->get_where('ms_reasons',['id_reasons'=>$id])->row();
		$data['set']     = "form";									
		$this->template($data);	
	}

	public function save_edit()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
	
		$id_reasons = $data['id_reasons']  = $this->input->post('id_reasons');
		$data['deskripsi']  = $this->input->post('deskripsi');
		$data['fungsi']     = $this->input->post('fungsi');
		$data['updated_at'] = $waktu;		
		$data['updated_by'] = $login_id;

		$this->db->trans_begin();
			$this->db->update('ms_reasons',$data,['id_reasons'=>$id_reasons]);
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
      	}
      	else
      	{
        	$this->db->trans_commit();
     //    	$rsp = ['status'=> 'sukses',
					// 'link'=>base_url('master/reasons')
				 //   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/reasons'>";
      	}
	}
}