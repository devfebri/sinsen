<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_samsat extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"monitoring_samsat";
    var $pk     =   "no_do";
    var $title  =   "Monitoring Status Proses Samsat";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_samsat');		
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

	public function index_old()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$data['dt_mon']	= $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE status_bbn = 'generated' ORDER BY tgl_mohon_samsat ASC");
		$this->template($data);			
	}	
	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view_fix";						
		$this->template($data);			
	}	
	public function ajax_list()
	{
		$list = $this->m_samsat->get_datatables();
		$data = array();
		$no = $_POST['start'];
		$summary=0;
		//$id_dealer = 43;
		foreach ($list as $isi) {	
			
				$cek_dealer = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer=ms_dealer.id_dealer 
	        WHERE no_bastd = '$isi->no_bastd'");
	      if($cek_dealer->num_rows() > 0){
	        $r = $cek_dealer->row();
	        $nama_dealer = $r->nama_dealer;
	      }else{
	        $nama_dealer = "";
	      }
	      $cek_tgl = $this->db->query("SELECT * FROM tr_konfirmasi_map_detail INNER JOIN tr_konfirmasi_map 
	        ON tr_konfirmasi_map.id_generate = tr_konfirmasi_map_detail.id_generate WHERE tr_konfirmasi_map_detail.no_mesin='$isi->no_mesin'");
	      if($cek_tgl->num_rows() > 0){
	        $t = $cek_tgl->row();
	        $tgl = $t->tgl_terima;
	      }else{
	        $tgl = "";
	      }

	      $tipe = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$isi->id_tipe_kendaraan);
	      $tipe_ahm = ($tipe->num_rows() > 0) ? $tipe->row()->tipe_ahm : "" ;
	      $warna = $this->m_admin->getByID("ms_warna","id_warna",$isi->id_warna);
	      $warna_ahm = ($warna->num_rows() > 0) ? $warna->row()->warna : "" ;
	      $c_stnk = $this->m_admin->getByID("tr_terima_bj","no_mesin",$isi->no_mesin);

	      $get_no_faktur = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$isi->no_mesin);
	      $no_faktur = ($get_no_faktur->num_rows() > 0) ? $get_no_faktur->row()->nomor_faktur : "" ;
          $cc_motor = ($tipe->num_rows() > 0 ) ? $tipe->row()->cc_motor : "" ;

	      if($c_stnk->num_rows() > 0){
	        $rr = $c_stnk->row();
	        $cb_stnk = $this->m_admin->getByID("tr_kirim_stnk_detail","no_mesin",$isi->no_mesin);
	        $no_stnk = ($cb_stnk->num_rows() > 0) ? $cb_stnk->row()->no_stnk : "" ;
	        $cb_bpkb = $this->m_admin->getByID("tr_kirim_bpkb_detail","no_mesin",$isi->no_mesin);
	        $no_bpkb = ($cb_bpkb->num_rows() > 0) ? $cb_bpkb->row()->no_bpkb : "" ;
	        $cb_plat = $this->m_admin->getByID("tr_kirim_plat_detail","no_mesin",$isi->no_mesin);
	        $no_plat = ($cb_plat->num_rows() > 0) ? $cb_plat->row()->no_plat : "" ;
	      }else{
	        $no_stnk = "";
	        $no_plat = "";
	        $no_bpkb = "";
	      }		
	      $cek_tgl = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$isi->no_mesin);
	      $tgl_mohon = ($cek_tgl->num_rows() > 0) ? $cek_tgl->row()->tgl_mohon_samsat : "" ;
				$no++;
				$a="";
				$row = array();	
				if($tgl_mohon == ""){
				    $a="";
				} elseif($tgl_mohon=="0000-00-00"){
				    $a="";
				} else {
				    $a=formatTanggal($tgl_mohon);
				}
				$row[] = $no;
				$row[] = $a;
				$row[] = $isi->no_bastd;
				$row[] = $nama_dealer;
				$row[] = $isi->no_mesin;
				$row[] = $isi->no_rangka;				
				$row[] = formatTanggal($isi->tgl_jual);
				$row[] = $no_faktur;				
				$row[] = $tipe_ahm;				
				$row[] = $warna_ahm;
				$row[] = $cc_motor;
				$row[] = $isi->nama_konsumen;
				$row[] = $no_stnk;
				$row[] = $no_plat;				
				$row[] = $no_bpkb;				
								
				// $row[] = "";				
				// $row[] = "";				
				$row[] = "<a href='h1/monitoring_samsat/detail?id=$isi->no_mesin'>
	                  <button type='button' title='Detail' class='btn btn-warning btn-flat btn-xs'><i class='fa fa-eye'></i> View</button>
	                </a>";
				$data[] = $row;
						
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_samsat->count_all(),
						"recordsFiltered" => $this->m_samsat->count_filtered(),
						"data" => $data,
						"summary" =>$summary
				);
		//output to json format
		echo json_encode($output);
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";	
		$id							= $this->input->get('id');	
		$data['dt_mon']	= $this->db->query("SELECT tr_pengajuan_bbn_detail.*,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna 
					FROM tr_pengajuan_bbn_detail INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd=tr_pengajuan_bbn.no_bastd 
					INNER JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
					INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna
					WHERE tr_pengajuan_bbn_detail.no_mesin = '$id'");
		$this->template($data);			
	}	
}