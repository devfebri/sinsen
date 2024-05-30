<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokumen_nrfs extends CI_Controller {

	var $tables = "tr_dokumen_nrfs";	
	var $folder = "dealer";
	var $page   = "dokumen_nrfs";
	var $title  = "Dokumen NRFS";

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
		// $this->load->library('upload');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

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
		$this->template($data);	
	}

	public function fetch()
   	{
		$fetch_data = $this->make_query();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array        = array();
			$button           = '';
			$status='';
			// $status_proposal     = "";
			// $status_proses_md = '';		
			// $tipe_ahm = $this->db->get_where("ms_tipe_kendaraan",['id_tipe_kendaraan'=>$rs->id_tipe_kendaraan]);
			// $tipe_ahm = $tipe_ahm->num_rows()>0?$tipe_ahm->row()->tipe_ahm:'';
			// $program  = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_program_md='$rs->id_program_md' AND id_tipe_kendaraan='$rs->id_tipe_kendaraan' AND id_warna LIKE('%$rs->id_warna%')");
			// $ahm=0;$md=0;$dealer=0;
			// if ($rs->jenis_beli=='Kredit') {
			// 	$promo = $rs->voucher_2;
			// 	if ($program->num_rows()>0) {
			// 		$pr     = $program->row();
			// 		$ahm    = $pr->ahm_kredit;
			// 		$md     = $pr->md_kredit;
			// 		$dealer = $pr->dealer_kredit;
			// 	}
			// }else{
			// 	$promo = $rs->voucher_1;
			// 	if ($program->num_rows()>0) {
			// 		$pr     = $program->row();
			// 		$ahm    = $pr->ahm_cash;
			// 		$md     = $pr->md_cash;
			// 		$dealer = $pr->dealer_cash;
			// 	}
			// }
			$button_ready = "<a data-toggle='tooltip' onclick=\"return confirm('Apakah anda yakin mengubah status menjadi Ready To Repair ?')\" href='dealer/dokumen_nrfs/set_ready_to_repair?id=$rs->dokumen_nrfs_id'><button class='btn btn-flat btn-xs btn-primary'>Ready To Repair</button></a>";
			if ($rs->status=='open') {
				$status = "<label class='label label-warning'>Open</label>";
				$button = $button_ready;
			}
			if ($rs->status=='ready_to_repair') {
				$status = "<label class='label label-info'>Ready To Repair</label>";
			}
			if ($rs->status=='resolved') {
				$status = "<label class='label label-success'>Resolved</label>";
			}

			$sub_array[] = $rs->dokumen_nrfs_id;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->type_code.'-'.$rs->color_code;
			$sub_array[] = $rs->deskripsi_unit;
			$sub_array[] = $rs->deskripsi_warna;
			$sub_array[] = $status;
			$promo = 0;
			
			$sub_array[] = $button;
			$data[]      = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   	}

   function make_query($no_limit=null)  
   	{  
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('dokumen_nrfs_id','no_mesin','no_rangka','type_code','deskripsi_unit','deskripsi_warna','status'); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE id_dealer='$id_dealer'";
		
		if ($search!='') {
	      $searchs .= " AND (dokumen_nrfs_id LIKE '%$search%' 
	          OR no_mesin LIKE '%$search%'
	          OR no_rangka LIKE '%$search%'
	          OR type_code LIKE '%$search%'
	          OR color_code LIKE '%$search%'
	          OR deskripsi_warna LIKE '%$search%'
	          OR deskripsi_unit LIKE '%$search%'
	          OR status LIKE '%$search%'
	          )
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT * FROM tr_dokumen_nrfs
   		 $searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	} 

   	public function set_ready_to_repair()
   	{
   		$id = $this->input->get('id');
		$id_dealer    = $this->m_admin->cari_dealer();

   		$cek = $this->db->query("SELECT * FROM tr_dokumen_nrfs WHERE dokumen_nrfs_id='$id' AND id_dealer=$id_dealer AND status='open'");
   		if ($cek->num_rows()>0) {
			$waktu                   = gmdate("y-m-d H:i:s", time()+60*60*7);
			$login_id                = $this->session->userdata('id_user');
			$data['ready_repair_by'] = $login_id;
			$data['ready_repair_at'] = $waktu;
			$data['status'] = 'ready_to_repair';
   			$this->db->update('tr_dokumen_nrfs',$data,['dokumen_nrfs_id'=>$id]);

   			$_SESSION['pesan'] 	= "Data has been processed successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/dokumen_nrfs'>";
   		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/dokumen_nrfs'>";

   		}
   	}
}