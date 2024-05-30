<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Training extends CI_Controller {

	var $folder =   "master";
	var $page		=		"training";
    var $title  =   "Training";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->helper('tgl_indo');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();		
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}


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
		$data['isi']       = $this->page;
		$data['title']     = $this->title;															
		$data['set']       = "view";
		$data['dt_result'] = $this->db->query("SELECT * FROM ms_training
			ORDER BY training ASC");
		$this->template($data);	
	}

	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "form";					
		$data['mode']		= "insert";					
		$this->template($data);										
	}

	public function save()
	{		
		$waktu    = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("Y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');

		$id_training  = $this->input->post('id_training');
		$cek_id_training = $this->db->get_where('ms_training',['id_training'=>$id_training]);
		if ($cek_id_training->num_rows()>0) {
			$rsp = ['status'=> 'error',
					'pesan'=> 'ID Training sudah ada !'
				   ];
      		echo json_encode($rsp);
			exit;
		}

		$data 	= ['id_training'=>$id_training,
				'training'                => $this->input->post('training'),
				'created_at'               => $waktu,
				'created_by'               => $login_id
			 ];

		// echo json_encode($dt_detail);
		// echo json_encode($upd_claim);
		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
			$this->db->insert('ms_training',$data);
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
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('master/training')
				   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$id_training = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_training WHERE id_training='$id_training'");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "detail";
			$this->template($data);												
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/training'>";
		}
	}

	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$id_training = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_training WHERE id_training='$id_training'");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "edit";
			$this->template($data);												
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/training'>";
		}
	}

	public function save_edit()
	{		
		$waktu    = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("Y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');

		$id_training  = $this->input->post('id_training');

		$data 	= ['id_training'                 => $id_training,
				'training'                => $this->input->post('training'),
				'updated_at'               => $waktu,
				'updated_by'               => $login_id
			 ];

		// echo json_encode($dt_detail);
		// echo json_encode($upd_claim);
		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
			$this->db->update('ms_training',$data,['id_training'=>$id_training]);
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
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('master/training')
				   ];
        	$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

}