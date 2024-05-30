<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H2_laporan_tarikan_data_nms extends CI_Controller {

	var $folder = "h2/laporan";
	var $page   ="h2_laporan_tarikan_data_nms";
	var $isi ="Laporan Tarikan Data NMS";
	var $title  = "Laporan Tarikan Data NMS";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('H2_laporan_tarikan_data_nms_model');	
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
		$data['isi'] = $this->isi;	

		$data['title'] = $this->title;													

		$data['set']	= "view";

		$data['dt_dealer'] = $this->H2_laporan_tarikan_data_nms_model->getDataTarikanDealer();

		$this->template($data);		
	}
	
	public function	download(){				

		$data['tgl1'] = $tgl1		= $this->input->post('tgl1');
		$data['tgl2'] = $tgl2		= $this->input->post('tgl2');				
		$data['id_dealer'] = $id_dealer		= $this->input->post('id_dealer');
		
		$tgl2 = date_format(date_add(date_create($tgl2),date_interval_create_from_date_string("1 days")),"Y-m-d");

		$filter_dealer = '';
          	if ($id_dealer!='all') {
           			$filter_dealer = " AND b.id_dealer='$id_dealer'";
         	}

		if($_POST['process']=='excel'){
			$this->load->view("h2/laporan/temp_h2_laporan_tarikan_data_nms",$data);
		}else if($_POST['process']=='csv'){
			$this->load->view("h2/laporan/temp_h2_laporan_tarikan_data_nms_csv",$data);
		}else if($_POST['process']=='excel_v2'){
			$data['v2'] = $v2 = $this->db->query("SELECT distinct a.kode_dealer_ahm, a.nama_dealer ,a.kode_dealer_md, b.id_work_order, DATE_FORMAT(b.created_at,'%d-%b-%Y')as created_at, DATE_FORMAT(b.start_at,'%d-%b-%Y') as start_at, b.total_jasa as biaya_jasa, b.total_part as biaya_part, j.deskripsi, upper(replace((case when i.no_mesin is not null then i.no_mesin else g.no_mesin end),' ','')) as no_mesin,
			upper((case when i.no_rangka is not null then i.no_rangka else g.no_rangka end)) as no_rangka,
			(case when i.tgl_cetak_invoice is not null then i.tgl_cetak_invoice else g.tgl_pembelian end) as tgl_pembelian,l.name as activity_promotion		 	
							FROM tr_h2_wo_dealer AS b
							JOIN ms_dealer AS a ON a.id_dealer = b.id_dealer
							JOIN tr_h2_wo_dealer_pekerjaan AS h ON b.id_work_order = h.id_work_order and h.pekerjaan_batal = 0
							JOIN ms_h2_jasa AS d ON d.id_jasa  = h.id_jasa 
							LEFT JOIN tr_h2_wo_dealer_parts AS c ON h.id_work_order = c.id_work_order and h.id_jasa = c.id_jasa
							LEFT JOIN ms_part AS e ON e.id_part  = c.id_part and e.kelompok_vendor ='AHM'
							JOIN tr_h2_sa_form AS f ON f.id_sa_form = b.id_sa_form
							JOIN ms_customer_h23 AS g ON g.id_customer = f.id_customer
							LEFT JOIN tr_sales_order AS i ON i.no_mesin = g.no_mesin
							LEFT JOIN ms_h2_jasa_type AS j On j.id_type = f.id_type	
							LEFT JOIN dms_ms_activity_promotion l on l.id = f.activity_promotion_id			
							WHERE b.created_at >= '$tgl1' AND b.created_at <= '$tgl2' and b.status <> 'cancel' $filter_dealer 
							order by a.nama_dealer asc, id_work_order asc, b.created_at asc, j.deskripsi asc
			");
			
			$this->load->view("h2/laporan/temp_h2_laporan_tarikan_data_nms_2",$data);
		}else if($_POST['process']=='excel_v3'){
			$this->load->view("h2/laporan/temp_h2_laporan_tarikan_data_nms_v3",$data);
		}else if($_POST['process']=='excel_nota'){
			$this->load->view("h2/laporan/temp_h2_laporan_tarikan_data_nms_nota",$data);
		}
	}	
}