<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_claim_dealer extends CI_Controller {

    var $tables =   "tr_sales_program";	
	var $folder =   "dealer";
	var $page	=	"monitoring_claim_dealer";
    var $pk     =   "id_sales_program";
    var $title  =   "Monitoring Claim Dealer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_claim_md_internal_payment_datatables');	
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

	public function indexs()
    {
      $id_dealer    = $this->m_admin->cari_dealer();
	  
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['folder'] = $this->folder;
      $data['set']    = "dev";

      $this->template($data);
    }



    public function index()
    {
      $id_dealer    = $this->m_admin->cari_dealer();
	  
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['folder'] = $this->folder;
      $data['set']    = "view";

	  $get_year = date('Y');
	  $string_date = $get_year."-m-30";
	  $get_today =  date($string_date);
	  $get_past_month = date($get_year.'-m-01', strtotime(' - 7 months'));

      $data['pembayaran_claim_dealer'] =  $this->db->query("SELECT ms_dealer.nama_dealer, ms_dealer.kode_dealer_ahm, ms_jenis_sales_program.jenis_sales_program as tipe_program ,tr_claim_dealer.id_program_md, tr_claim_dealer.id_claim, ms_dealer.id_dealer,
            tr_sales_program_tipe.id_tipe_kendaraan,tr_sales_program.no_juklak_md,tr_sales_program.series_motor,ms_dealer.id_dealer as dealer_detail,tr_claim_sales_program.status,
            tr_sales_program.periode_awal,tr_sales_program.periode_akhir,tr_sales_program.id_program_md,tr_sales_program.no_juklak_md,
              sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS total_approve,
              sum(Case When tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS total_reject,
			  sum(Case When tr_claim_dealer.Status = 'ajukan' or tr_claim_dealer.status ='' Then 1 Else 0 End) AS total_gantung,
       		  (case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * ahm_cash) 											                         WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_kredit) else  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_kredit) end) as total_kontribusi_ahm ,
              (case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_cash) 											 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_kredit) else  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_kredit) end) as total_kontribusi_md ,
              (case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)	 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit)) else (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit)) end) as total_kontribusi_d
			  FROM tr_claim_dealer 
              left join tr_claim_sales_program_detail on tr_claim_sales_program_detail.id_claim_dealer =  tr_claim_dealer.id_claim
              left JOIN ms_dealer on tr_claim_dealer.id_dealer = ms_dealer.id_dealer 
              JOIN tr_sales_program on  tr_sales_program.id_program_md  = tr_claim_dealer.id_program_md 
              left join tr_sales_order on tr_sales_order.id_sales_order = tr_claim_dealer.id_sales_order 
              left JOIN ms_jenis_sales_program on tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program 
              left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk 
              left JOIN tr_sales_program_tipe ON tr_sales_program_tipe.id_program_md = tr_claim_dealer.id_program_md and tr_sales_program_tipe.id_tipe_kendaraan = tr_spk.id_tipe_kendaraan 
			  left join tr_claim_sales_program on tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and  tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp   
			  join ms_group_dealer_detail on ms_dealer.id_dealer = ms_group_dealer_detail.id_dealer
			  WHERE  1=1 and ms_dealer.id_dealer= '$id_dealer' 
			  AND  tr_claim_sales_program.status = 'close'
              GROUP by ms_dealer.id_dealer, tr_sales_program.id_program_md
			  order by tr_sales_program.periode_awal DESC 
           ")->result();
     
     
      $this->template($data);
    }


	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']    = "history";
		$this->template($data);			
	}

	public function fetch_data_claim_md_internal_payment()
	{
		$list = $this->m_claim_md_internal_payment_datatables->get_datatables();
		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {       

			  if (!empty($row->id_program_md)) {
				$button_id_program =" <a href='h1/monitoring_claim_md_internal_payment/detail?id=$row->id_program_md'>$row->id_program_md </a>";

			}else{
				$button_id_program = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $button_id_program;
			$rows[] = $row->judul_kegiatan;
			$rows[] = $row->periode_awal;
			$rows[] = $row->periode_akhir;
			$rows[] = $row->status_approved;
			$rows[] = $row->status_reject;
			$rows[] = $row->jumlahnya_pendding	;
			$rows[] = number_format($row->kontribusi_ahm);
			$rows[] = number_format($row->kontribusi_md);
			$rows[] = number_format($row->kontribusi_dealer);
			$rows[] = number_format($row->kontribusi_dealer);
			$data[] = $rows;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_claim_md_internal_payment_datatables->count_all(),
			"recordsFiltered" => $this->m_claim_md_internal_payment_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function fetch_data_claim_md_internal_payment_history()
	{

		// var_dump($_POST);
		// die();
		$list = $this->m_claim_md_internal_payment_datatables->get_datatables_history();
		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {       

			  if (!empty($row->id_program_md)) {
				$button_id_program =" <a href='h1/monitoring_claim_md_internal_payment/detail?id=$row->id_program_md'>$row->id_program_md </a>";

			}else{
				$button_id_program = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $button_id_program;
			$rows[] = $row->judul_kegiatan;
			$rows[] = $row->periode_awal;
			$rows[] = $row->periode_akhir;
			$rows[] = $row->status_approved;
			$rows[] = $row->status_reject;
			$rows[] = $row->jumlahnya_pendding	;
			$rows[] = number_format($row->kontribusi_ahm);
			$rows[] = number_format($row->kontribusi_md);
			$rows[] = number_format($row->kontribusi_dealer);
			$rows[] = number_format($row->kontribusi_dealer);
			$data[] = $rows;
		}
		
		$output = array(
			"draw" 			  => $_POST['draw'],
			"recordsTotal"    => $this->m_claim_md_internal_payment_datatables->count_all_history(),
			"recordsFiltered" => $this->m_claim_md_internal_payment_datatables->count_filtered_history(),
			"data"            => $data,
		);
		echo json_encode($output);
	}


	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";
		$id_program_md = $this->input->get('id');
		$data['temp_data']  = $this->db->query("	
		select  ms_dealer.id_dealer,ms_dealer.nama_dealer,ms_jenis_sales_program.jenis_sales_program,tr_claim_dealer.id_program_md,tr_sales_program.kuota_program,tr_sales_program.no_juklak_md,tr_sales_program.series_motor,
		sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS status_approved,
		sum(Case When tr_claim_dealer.Status = 'ajukan' or tr_claim_dealer.status ='' Then 1 Else 0 End) AS status_ajukan,
		sum(Case When tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.ahm_cash) 											 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.ahm_kredit) else  (tr_sales_program_tipe.ahm_kredit) end) as kontribusi_ahm ,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.md_cash + tr_sales_program_tipe.add_md_cash) 			 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit) else  (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit)   end) as kontribusi_md,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)	 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) else (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) end) as kontribusi_dealer
		,tr_claim_sales_program.status as status_validasi,tr_claim_sales_program.id_claim_sp
		FROM tr_claim_dealer 
		left join tr_claim_sales_program_detail on tr_claim_sales_program_detail.id_claim_dealer =  tr_claim_dealer.id_claim
		left JOIN ms_dealer on tr_claim_dealer.id_dealer = ms_dealer.id_dealer 
		 JOIN tr_sales_program on  tr_sales_program.id_program_md  = tr_claim_dealer.id_program_md 
		left join tr_sales_order on tr_sales_order.id_sales_order = tr_claim_dealer.id_sales_order 
		left JOIN ms_jenis_sales_program on tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program 
		left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk 
		left JOIN tr_sales_program_tipe ON tr_sales_program_tipe.id_program_md = tr_claim_dealer.id_program_md and tr_sales_program_tipe.id_tipe_kendaraan = tr_spk.id_tipe_kendaraan 
		join ms_group_dealer_detail on ms_dealer.id_dealer = ms_group_dealer_detail.id_dealer 
		join ms_group_dealer on ms_group_dealer.id_group_dealer = ms_group_dealer_detail.id_group_dealer 
		left join tr_claim_sales_program on tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md  and tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp 
		  WHERE tr_claim_dealer.id_program_md ='$id_program_md'
		  group by ms_dealer.id_dealer
		  order by ms_dealer.kode_dealer_md asc
			")->result();

		$this->template($data);		
		
		
	}



	public function approve_status()
	{		
		$generate= $this->input->get('id_claim');
		$sales_program_md= $this->input->get('sales_program');
		$tabel	= 'tr_claim_sales_program';	
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;															
		$data['set']		= "view";
		$pk                 = "id_claim_sp";
		$update['status'] = 'close';
		$update['closed_at'] = date('Y-m-d h:i:s');
		$this->m_admin->update($tabel,$update,$pk,$generate);
   		 echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitoring_claim_md_internal_payment/detail?id=".$sales_program_md."'>";
	}


	
	public function reject_status()
	{		
		$generate= $this->input->get('id_claim');
				$sales_program_md= $this->input->get('sales_program');
  		$tabel	= 'tr_claim_sales_program_detail';	
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;															
		$data['set']		= "view";
		$pk     = "id_claim_dealer";
		$update['status'] = 'reject';
		$this->m_admin->update($tabel,$update,$pk,$generate);
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitoring_claim_md_internal_payment/detail?id=".$sales_program_md."'>";
	}






}