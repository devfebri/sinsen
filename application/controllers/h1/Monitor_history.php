<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_history extends CI_Controller {

    var $tables =   "tr_penerimaan_unit";	
		var $folder =   "h1";
		var $page		=		"monitor_history";
    var $pk     =   "id_penerimaan_unit";
    var $title  =   "Monitor History Unit";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_monitor_history');		
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
		$data['set']	= "view_fix";	
		$this->template($data);			
	}

	
	public function log()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Log ".$this->title;		
		$data['set']		= "log";			
		$id = $this->input->get("id");							
		$data['dt_nosin'] = $this->m_admin->getByID("tr_log","no_mesin",$id);									
		$this->template($data);										
	}
	public function ajax_list()
	{
		$list = $this->m_monitor_history->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$s = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$isi->id_modell' AND id_warna = '$isi->id_warna' AND bundling <> 'ya'");          
      $t = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'");
      $do = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list=tr_picking_list.no_picking_list 
              WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' AND tr_picking_list_view.konfirmasi = 'ya'");
      $sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_mesin = '$isi->no_mesin'");
      $sj2 = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
              INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
              WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' AND tr_surat_jalan_detail.terima = 'ya'");
      if($t->num_rows() > 0){
        $e = $t->row();
        $fifo = $e->fifo;
        $id_item = $e->id_item;
        $s_sale = $e->tipe;
        if($e->status == '1'){
          $status = "<span class='label label-default'>Received</span>";  
        }elseif($e->status == '2'){
          $status = "<span class='label label-warning'>Unfill</span>";  
        }elseif($e->status == '3'){
          $status = "<span class='label label-primary'>Intransit Dealer</span>";  
        }elseif($e->status == '4'){
          $status = "<span class='label label-primary'>Received by Dealer</span>";  
        }elseif($e->status == '5'){
          $status = "<span class='label label-success'>Sale to Customer</span>";  
        }  
        
        if($e->status != 5){
          $lokasi = $e->lokasi;
          $slot = $e->slot;  
        }else{
          $lokasi = "";
          $slot = "";
        }              
        
        if($do->num_rows() > 0){
          $isi_do = $do->row();
          $no_do = $isi_do->no_do;
        }else{
          $no_do = "";
        }

        if($sj->num_rows() > 0){
          $isi_sj = $sj->row();
          $no_sj = $isi_sj->no_surat_jalan;
        }else{
          $no_sj = "";
        }

        if($sj2->num_rows() > 0){
          $isi_sj2 = $sj2->row();
          $no_sj2 = $isi_sj2->kode_dealer_md;
          $no_sj3 = $isi_sj2->nama_dealer;
        }else{
          $no_sj2 = "";
          $no_sj3 = "";
        }

      }else{
        $fifo = "";
        $s_sale = "";
        $status = "<span class='label label-danger'>Intransit AHM</span>";
        $no_do = "";
        $no_sj = "";
        $no_sj2 = "";
        $no_sj3 = "";
        $lokasi = "";
        $slot = "";
        $id_item = "";
        if($s->num_rows() > 0){
          $is = $s->row();
          $id_item = $is->id_item;
        }else{
          $id_item = "";
        }
        
      }          

      $get_so = $this->db->query("SELECT * FROM tr_sales_order WHERE no_mesin = '$isi->no_mesin' ");
      if ($get_so->num_rows() > 0) {
        $get_so = $get_so->row();
        if ($get_so->status_so =='so_invoice') {
          
          $status =  "<span class='label label-success'>Sale to Customer</span>";
        }
      }           

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = "<a data-toggle='tooltip' title='View Log' href='h1/monitor_history/log?id=$isi->no_mesin'>
                  $isi->no_mesin
                </a>";
			$row[] = $isi->no_rangka;
			$row[] = $id_item;			
			$row[] = $status;			
			$row[] = $s_sale;			
			$row[] = $lokasi." - ".$slot;			
			$row[] = $fifo;						
			$row[] = $no_do;						
			$row[] = $no_sj;						
			$row[] = $no_sj2;						
			$row[] = $no_sj3;						
			$row[] = "";						
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_monitor_history->count_all(),
						"recordsFiltered" => $this->m_monitor_history->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
}