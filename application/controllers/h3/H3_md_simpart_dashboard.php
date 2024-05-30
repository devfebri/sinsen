<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_simpart_dashboard extends CI_Controller{
	
	var $folder ="h3";
	var $page   ="h3_md_simpart_dashboard";	
	var $isi    ="SIM Part Dashboard";	
	var $title  ="SIM Part Dashboard";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
        $this->load->model('h3_md_simpart_dashboard_model','simpart_dashboard');	
		//===== Load Library =====		
		// $this->load->library('pdf');		
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

	protected function template($data){
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
	
	public function index(){	
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$data['dealer'] = $this->simpart_dashboard->getDataDealer();
		$this->template($data);		    	    
	}	

	public function downloadExcel(){
		$data['id_dealer'] = $id_dealer	= $this->input->post('id_dealer');
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['type']      = $type	    = $this->input->post('type');

		if($type=='master_ahass'){
			$data['master_ahass'] = $this->simpart_dashboard->master_ahass();
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/simpart_master_dealer_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/simpart_master_dealer_csv",$data);
            }
	    }elseif($type=='master_sim_part'){
			$data['master_sim_part'] = $this->simpart_dashboard->master_sim_part($id_dealer);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/simpart_master_sim_part_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/simpart_master_sim_part_csv",$data);
            }
	    }elseif($type=='parts_number'){
			$data['parts_number'] = $this->simpart_dashboard->parts_number($id_dealer);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/simpart_master_parts_number_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/simpart_master_parts_number_csv",$data);
            }
	    }elseif($type=='master_by_qty'){
			$data['master_by_qty'] = $this->simpart_dashboard->master_by_qty($id_dealer);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/simpart_master_by_qty_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/simpart_master_by_qty_csv",$data);
            }
	    }elseif($type=='master_by_item'){
			$data['master_by_item'] = $this->simpart_dashboard->master_by_item($id_dealer);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/simpart_master_by_item_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/simpart_master_by_item_csv",$data);
            }
	    }elseif($type=='master_status_by_qty'){
			$data['master_status_by_qty'] = $this->simpart_dashboard->master_status_by_qty($id_dealer);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/simpart_master_status_by_qty_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/simpart_master_status_by_qty_csv",$data);
            }
	    }elseif($type=='master_kelompok_part'){
			$data['master_kelompok_part'] = $this->simpart_dashboard->master_kelompok_part($id_dealer);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/simpart_master_kelompok_part_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/simpart_master_kelompok_part_csv",$data);
            }
	    }
    }
}
?>