<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Report extends CI_Controller {



   var $tables =   "tr_report_proposal";
   var $table_detail =   "tr_report_proposal_detail";

	var $folder =   "dealer";

	var $page	=	"report";

    var $pk     =   "id_prospek";

    var $title  =   "Report";



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

		$data['set']	= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_report'] = $this->db->query("SELECT * FROM $this->tables join tr_proposal_dealer on tr_report_proposal.id_proposal = tr_proposal_dealer.id_proposal where tr_report_proposal.active=1 AND tr_report_proposal.id_dealer = '$id_dealer'");						

		$this->template($data);	

		//$this->load->view('trans/logistik',$data);

	}

	

	

	public function add()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title;		

		$data['set']		= "insert";					
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_proposal'] = $this->db->query("SELECT * FROM tr_proposal_dealer where id_dealer = '$id_dealer' AND id_proposal not in(select id_proposal from $this->tables)");														

		$this->template($data);										

	}

	public function detail()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title;		

		$data['set']		= "detail";					
		$id = $this->input->get('id');
		$data['dt_report'] = $this->db->query("SELECT * FROM tr_report_proposal
		inner join tr_proposal_dealer on tr_report_proposal.id_proposal = tr_proposal_dealer.id_proposal 
			where id_report_proposal = '$id' ")->row();					
		$this->template($data);										

	}

	public function cari_id(){

		

		//$tgl				= $this->input->post('tgl');

		$th 				= date("y");

		$bln 				= date("m");

		$tgl 				= date("d");

		$dealer 			= $this->session->userdata("id_karyawan_dealer");

		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 

								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();

		$kode_dealer 		= $isi->kode_dealer_md;

		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek ORDER BY id_prospek DESC LIMIT 0,1");						

		if($pr_num->num_rows()>0){

			$row 	= $pr_num->row();				

			$pan  = strlen($row->id_prospek)-11;

			$id 	= substr($row->id_prospek,$pan,11)+1;	

			if($id < 10){

				$kode1 = $th.$bln.$tgl."0000".$id;          

		    }elseif($id > 9 && $id <= 99){

				$kode1 = $th.$bln.$tgl."000".$id;                    

		    }elseif($id > 99 && $id <= 999){

				$kode1 = $th.$bln.$tgl."00".$id;          					          

		    }elseif($id > 999){

				$kode1 = $th.$bln.$tgl."0".$id;                    

		    }

			$kode = $kode_dealer.$kode1;

		}else{

			$kode = $kode_dealer.$th.$bln.$tgl."00001";

		} 	



		$rt = rand(1111,9999);

		echo $kode."|".$rt;

	}

	public function save()

	{		

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= $this->tables;

		$data['id_proposal'] 	= $this->input->post('id_proposal');

		$data['jml_orang'] 					= $this->input->post('jml_orang');	

		$data['deskripsi	'] 			= $this->input->post('deskripsi');	

		$data['total_biaya'] 			= $this->input->post('total_biaya');
		$data['active'] 		= 1;					
		$data['status'] 		= 'input';
		$data['id_dealer'] = $this->m_admin->cari_dealer();					

		$data['created_at']				= $waktu;		

		$data['created_by']				= $login_id;	


		$this->m_admin->insert($tabel,$data);

		$last_report = $this->db->query("SELECT * FROM tr_report_proposal where created_by='$login_id' and status='input' and active=1 order by id_report_proposal desc limit 0,1");

		if ($last_report->num_rows() > 0) {
			$rep= $last_report->row();

			$config['upload_path'] 		= './assets/panel/images/report/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
			$config['max_size']				= '1000';
			$config['max_width']  		= '2000';
			$config['max_height']  		= '1024';
			$this->upload->initialize($config);

			for($i=1; $i <=4 ; $i++) { 
		    	if(!empty($_FILES['foto'.$i]['name'])){
		    		if(!$this->upload->do_upload('foto'.$i)){
		    			$this->upload->display_errors();
		    			$data_attach[$i]['filename']='';	
		    		}else{
		    			$data_attach[$i]['filename']=$this->upload->file_name;
		    		}
		    	}else{
		    		$data_attach[$i]['filename']='';	
		    	}
		    	$data_attach[$i]['id_report_proposal'] = $rep->id_report_proposal;	
    			$data_attach[$i]['id_proposal'] = $rep->id_proposal;
    			$data_attach[$i]['created_by'] = $rep->created_by;	
		    }

        	$this->db->insert_batch('tr_report_proposal_attachment',$data_attach);

		}


		$_SESSION['pesan'] 	= "Data has been saved successfully";

		$_SESSION['tipe'] 	= "success";

		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/report/add'>";

	}



}