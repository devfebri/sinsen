<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_klaim_promosi extends CI_Controller {

	var $tables = "tr_sales_order";	
	var $folder = "dealer";
	var $page   = "monitoring_klaim_promosi";
	var $title  = "Monitoring Klaim Promosi";

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
			$status_proposal     = "";
			$status_proses_md = '';	
			$alasan_reject ='';	
			$tipe_ahm = $this->db->get_where("ms_tipe_kendaraan",['id_tipe_kendaraan'=>$rs->id_tipe_kendaraan]);
			$tipe_ahm = $tipe_ahm->num_rows()>0?$tipe_ahm->row()->tipe_ahm:'';
			$program  = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_program_md='$rs->id_program_md' AND id_tipe_kendaraan='$rs->id_tipe_kendaraan' AND id_warna LIKE('%$rs->id_warna%')");
			$ahm=0;$md=0;$dealer=0;
			if ($rs->jenis_beli=='Kredit') {
				$promo = $rs->voucher_2;
				if ($program->num_rows()>0) {
					$pr     = $program->row();
					$ahm    = $pr->ahm_kredit;
					$md     = $pr->md_kredit;
					$dealer = $pr->dealer_kredit;
				}
			}else{
				$promo = $rs->voucher_1;
				if ($program->num_rows()>0) {
					$pr     = $program->row();
					$ahm    = $pr->ahm_cash;
					$md     = $pr->md_cash;
					$dealer = $pr->dealer_cash;
				}
			}

			if ($rs->status_proposal=='draft') {
				$status_proposal = "<label class='label label-info'>Draft</label>";
			}
			if ($rs->status_proposal=='cancel') {
				$status_proposal = "<label class='label label-warning'>Cancel</label>";
			}
			if ($rs->status_proposal=='submitted') {
				$status_proposal = "<label class='label label-success'>Submitted</label>";
			}
			// if ($rs->status_proses_md==null) {
			// 	$status_proses_md = "<label class='label label-warning'>Pending</label>";
			// }
			// if ($rs->status_proses_md=='reject') {
			// 	$status_proses_md = "<label class='label label-danger'>Rejected By MD</label>";
			// }
			if ($rs->status_proposal=='completed_by_md') {
				
				if ($rs->status_claim=='rejected') {
					$status_proposal = "<label class='label label-danger'>Rejected By MD</label>";
					$get_alasan = $this->db->query("SELECT * FROM ms_alasan_reject WHERE id_alasan_reject IN(SELECT alasan_reject FROM tr_claim_dealer_syarat WHERE id_claim='$rs->id_claim' AND alasan_reject IS NOT NULL)")->result();
					$alasan_reject ='';
					foreach ($get_alasan as $value) {
						$alasan_reject.="- $value->alasan_reject </br>";
					}
				}else{
					$status_proposal = "<label class='label label-success'>Completed By MD</label>";

				}
			}
			$sub_array[] = $rs->id_program_md;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->id_sales_order;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = mata_uang_rp($rs->harga_on_road);
			$sub_array[] = mata_uang_rp($promo);
			$sub_array[] = mata_uang_rp($ahm);
			$sub_array[] = mata_uang_rp($md);
			$sub_array[] = mata_uang_rp($dealer);
			$sub_array[] = $status_proposal;
			$sub_array[] = $alasan_reject;
			$promo = 0;
			
			// $sub_array[] = $button;
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
		$order_column = array('program_umum','no_spk','id_sales_order','id_customer','nama_konsumen','id_tipe_kendaraan','id_warna','no_mesin','no_rangka',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE status_proposal IS NOT NULL ";
		
		if ($search!='') {
	      $searchs .= " AND (no_spk LIKE '%$search%' 
	          OR id_sales_order LIKE '%$search%'
	          OR id_tipe_kendaraan LIKE '%$search%'
	          OR id_warna LIKE '%$search%'
	          OR no_mesin LIKE '%$search%'
	          OR no_rangka LIKE '%$search%'
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

   		return $this->db->query("
   			SELECT * FROM (
   				SELECT 
   					program_umum AS id_program_md, tr_spk.no_spk,so.id_sales_order, so.no_mesin,no_rangka,harga_on_road,voucher_1, voucher_2,so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'umum' AS jenis_program,id_claim,tr_claim_dealer.status AS status_claim
   					FROM tr_sales_order AS so
		   			JOIN tr_spk ON so.no_spk=tr_spk.no_spk
		   			LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_umum=tr_claim_dealer.id_program_md
		   			WHERE so.id_dealer=$id_dealer AND no_invoice IS NOT NULL AND program_umum IS NOT NULL AND tr_spk.program_umum!=''
		   		UNION 
		   		SELECT 
   					program_gabungan AS id_program_md, tr_spk.no_spk,so.id_sales_order,so.no_mesin,no_rangka,harga_on_road,voucher_tambahan_1 AS voucher_1,voucher_tambahan_2 AS voucher_2, so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'gabungan' AS jenis_program,id_claim,tr_claim_dealer.status AS status_claim
   					FROM tr_sales_order AS so
		   			JOIN tr_spk ON so.no_spk=tr_spk.no_spk
		   			LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_gabungan=tr_claim_dealer.id_program_md
		   			WHERE so.id_dealer=$id_dealer AND no_invoice IS NOT NULL AND program_gabungan IS NOT NULL AND tr_spk.program_gabungan!=''
   			) AS table_union
   		 $searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	} 
}