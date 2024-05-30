<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_srbu extends CI_Controller {

	var $folder = "h2";
	var $page   = "unit_srbu";
	var $title  = "Unit SRBU";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tgl_indo');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_h2');		
		//===== Load Library =====
		// $this->load->library('upload');

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
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
      	$data['group']  = $this->session->userdata("group");
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
		$this->template($data);	
	}
	
	public function fetch()
  {
    $fetch_data = $this->make_query();  
    $data       = array();  
    $id_menu    = $this->db->escape_str($this->input->post('id_menu'));
    $group      = $this->db->escape_str($this->input->post('group'));
    $edit       = $this->m_admin->set_tombol($id_menu,$group,'edit');
    foreach($fetch_data->result() as $rs)  
    {  
      $status = '<span class="label label-warning">Proses</span>';
		$button      = '';
		$btn_hapus   = "<a $edit href='h2/unit_srbu/delete?id=$rs->no_mesin' class='btn btn-flat btn-danger btn-xs' onclick=\"return confirm('Apakah anda yakin ingin menghapus data ini ?')\"><i class='fa fa-trash'></i></a>";
		$button .= $btn_hapus;
		$sub_array   = array();
		$sub_array[] = $rs->no_mesin;
		$sub_array[] = $rs->no_rangka;
		$sub_array[] = $rs->tipe_ahm;
		$sub_array[] = $rs->warna;
		$sub_array[] = $button;
		$data[]      = $sub_array;  
    }  

    $output = array(  
		"draw"            => intval($_POST["draw"]),  
		"recordsFiltered" => $this->get_filtered_data(),  
		"data"            => $data  
    );
    echo json_encode($output);  
  }
  public function make_query($no_limit=null)
  {
    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start, $length";
    if ($no_limit=='y')$limit='';

    $filter =['search'=> $this->db->escape_str($this->input->post('search')['value']),
			  'limit' => $limit,
			  'order' => isset($_POST['order'])?$_POST["order"]:''
			 ];
    return $this->m_h2->fetch_unit_srbu($filter);
  } 

  function get_filtered_data(){  
    return $this->make_query('y')->num_rows();  
  }

  public function add()
  	{       
		$data['isi']   = $this->page;
		$data['title'] = 'Tambah '.$this->title;
		$data['set']   = "form";
		$data['mode']  = "insert";

		$this->template($data);     
  	}

  	function save()
  	{
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$no_mesin = $this->input->post('no_mesin');
		$cek_nosin = $this->db->get_where('tr_scan_barcode',['no_mesin'=>$no_mesin]);
		if ($cek_nosin->num_rows()==0) {
			$result = ['status'=>'error','pesan'=>'No. Mesin tidak ditemukan dalam database !'];
			echo json_encode($result);
			die();
		}
		$data     = ['no_mesin'=>$no_mesin,
						'created_at' => $waktu,
						'created_by' => $login_id
						];
		$this->db->trans_begin();
			$this->db->insert('tr_h2_unit_srbu',$data);
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
					'link'=>base_url('h2/unit_srbu')
				   ];
	     	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
   	}
   	echo json_encode($rsp);
  	}
  	public function delete()
	{		
		$id 			= $this->input->get('id');		
		$this->db->trans_begin();			
			$this->db->delete('tr_h2_unit_srbu',['no_mesin'=>$id]);
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
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h2/unit_srbu'>";
	}
}