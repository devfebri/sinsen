<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Umtc extends CI_Controller {



	var $folder =   "h1";

	var $page	=   "umtc";

	var $isi	=   "umtc";

    var $title  =   "UMTC";



	public function __construct()

	{		

		parent::__construct();

		

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');	

		$this->load->model('m_umtc');	

		//===== Load Library =====

		$this->load->library('upload');

		

        $name = $this->session->userdata('nama');

		$auth = $this->m_admin->user_auth($this->page,"select");		

		$sess = $this->m_admin->sess_auth();						

		if($name=="" OR $auth=='false'){

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";

		}elseif($sess=='false'){

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";

		}



	}

	

	protected function template($data)

	{

		$name = $this->session->userdata('nama');

		if($name==""){

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

		$this->template($data);	

	}

	

	public function generate_file()	{
		$data['list_data'] = $this->m_umtc->get_data();

		if($_POST['process']=='generate'){
			$this->load->view('h1/report/template/temp_umtc',$data);
		}
	}

}