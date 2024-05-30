<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Sales_stok_unit extends CI_Controller {



		var $folder =   "h1";

		var $page		=		"sales_stok_unit";

    var $title  =   "Sales & Stok Unit";



	public function __construct()

	{		

		parent::__construct();

		

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');		

		$this->load->model('m_stok_d');		

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
		$data['total'] = $this->m_admin->getTotalStok();	

		$this->template($data);	
	}

	public function getData()
    {
    	$this->load->model('m_admin');

        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

        $dataStok = $this->m_admin->getStok($search, $limit, $start, $order_field, $order_ascdesc);
        $data = array();
        foreach($dataStok->result() as $rows)
        {

            $data[]= array(
            	'',
                $rows->id_tipe_kendaraan,
                $rows->tipe_ahm,
                $rows->unfill_md,
                $rows->intransit_md,
                $rows->stok_md,
                $rows->unfill_dealer,
                $rows->intransit_dealer,
                $rows->stok_dealer,
                $rows->total_stok,
                $rows->stok_market,
                $rows->sales,
                $rows->stok_day,
            );     
        }
        $total_data = $this->m_admin->count_filter($search);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


	public function index_old()

	{	

		$this->load->model('m_admin');

        $this->load->library('pagination');



        $config['per_page'] = 10;  //show record per halaman

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;



        if ($_GET) {

        	$tipe_ahm = $this->input->get('tipe_ahm');



        	$total = $this->m_admin->getCariTipeStok($tipe_ahm)->num_rows();

        	$query = $this->m_admin->getCariTipeStok($tipe_ahm);

        } else {
        	$this->db->where('active', 1);
			$total = $this->db->get('ms_tipe_kendaraan')->num_rows();

        	$query = $this->m_admin->getStok($config["per_page"], $page, 'total_stok','asc');

        }

              //konfigurasi pagination

          $config['base_url'] = base_url('h1/sales_stok_unit/index'); //site url

          $config['total_rows'] = $total; //total row

          

          $config["uri_segment"] = 4;  // uri parameter

          $choice = $config["total_rows"] / $config["per_page"];

          $config["num_links"] = 2;//floor($choice);

   

         $config['next_link'] = 'Selanjutnya';

		$config['prev_link'] = 'Sebelumnya';

		$config['first_link'] = 'Awal';

		$config['last_link'] = 'Akhir';

		$config['full_tag_open'] = '<ul class="pagination">';

		$config['full_tag_close'] = '</ul>';

		$config['num_tag_open'] = '<li>';

		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="#">';

		$config['cur_tag_close'] = '</a></li>';

		$config['prev_tag_open'] = '<li>';

		$config['prev_tag_close'] = '</li>';

		$config['next_tag_open'] = '<li>';

		$config['next_tag_close'] = '</li>';

		$config['last_tag_open'] = '<li>';

		$config['last_tag_open'] = '<li>';

		$config['first_tag_open'] = '<li>';

		$config['first_tag_open'] = '<li>';

   

        $this->pagination->initialize($config);

        $data['query'] = $query;

        $data['total'] = $this->m_admin->getTotalStok();

        $data['pagination'] = $this->pagination->create_links();



		$data['isi']    = $this->page;		

		$data['title']	= $this->title;															

		$data['set']		= "view_fix";

		$this->template($data);	

	}



	

}