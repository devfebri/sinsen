<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_credit_funneling extends CI_Controller
{
	var $tables = "tr_voucher_bank";
	var $folder = "dealer/laporan";
	var $page   = "dealer_credit_funneling";
	var $isi    = "Credit dealer_credit_funneling";
	var $pk     = "id_voucher_bank";
	var $title  = "Report SLA Fincoy";
	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H1_md_report_sla_fincoy_model');	
		//===== Load Library =====
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}
	

	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}


	public function index()
	{
		$data['isi']       = $this->isi;
		$data['title']	   = $this->title;
		$data['page']  	   = $this->page;
		$data['set']	   = "view";
		$data['kecamatan'] = $this->H1_md_report_sla_fincoy_model->get_kecamatan();
		$data['segment']   = $this->H1_md_report_sla_fincoy_model->get_segment();
		// $data['series']    = $this->H1_md_report_sla_fincoy_model->get_series();
		$data['fincoy']    = $this->H1_md_report_sla_fincoy_model->get_fincoy();
		$this->template($data);
	}


	public function get_data_series(){
			$id_series=$this->input->get('segment'); 
			$series  = $this->H1_md_report_sla_fincoy_model->get_series($id_series);
			$seriesloop = NULL;
			$seriesloop.= "<option value='' >- choose -</option>";
			foreach ($series->result() as $data ){
			$seriesloop.= "<option value='$data->id_series'>$data->id_series</option>";
			}
			echo $seriesloop;
	}

	

	public function get_data_tipe(){
		$id_series=$this->input->get('tipe'); 
		$series  = $this->H1_md_report_sla_fincoy_model->get_data_tipe($id_series);

		$seriesloop = NULL;
		$seriesloop.= "<option value='' >- choose -</option>";
		foreach ($series->result() as $data ){
		$seriesloop.= "<option value='$data->id_tipe_kendaraan'>$data->tipe_ahm</option>";
		}
		echo $seriesloop;
}


	public function get_credit_funneling(){

		$search =  $this->input->get('search');
		$segment = $this->input->get('segment');
		$series = $this->input->get('series');
		$tipe = $this->input->get('tipe');
		$dealer =  $this->input->get('dealer');
		$fincoy =  $this->input->get('fincoy');
		$kecamatan =  $this->input->get('kecamatan');
		$keterangan =  $this->input->get('keterangan');
		$tanggal_awal =  $this->input->get('tanggal_awal');
		$tanggal_akhir =  $this->input->get('tanggal_akhir');
		$dp =  $this->input->get('dp');
		$series =  $this->input->get('series');

		$dealer    = $this->m_admin->cari_dealer();
		$get_funneling =  $this->H1_md_report_sla_fincoy_model->get_credit_funneling($search,$segment,$series,$tipe,$dealer,$fincoy,$kecamatan,$keterangan,$tanggal_awal,$tanggal_akhir,$dp);


		$output=NULL;

		 if ( !empty($get_funneling) ) {
           	$urut = 0;
           	$order = 0;
           	$approve = 0;
           	$rejected = 0;
           	$on_going = 0;
           	$pending = 0;
			$delivery = 0;
			$disbursed = 0;
			$not_disbursed = 0;
			$schedule = 0;
			$not_invoice_send = 0;
			$invoice_send = 0;
			// $success_rate = 0;
			// $rejection_rate = 0;
			$total = 0;
			$count_order= 0;
			$count_approved = 0;
			$count_rejected = 0;
			$count_on_going = 0;
			$count_delivery = 0;
			$count_schedule = 0;
			$count_pending  = 0;
			$count_invoice_send   = 0;
			$count_not_invoice_send  = 0;
			$count_disbursed      = 0;
			$count_not_disbursed  = 0;
			
			$count_total  = 0;

			foreach ($data['detail']=$get_funneling->result() as $row) {

				$check_status = $row->status_survey;
				$count_total = ++$total;

				if($row->status_credit =='Going' ){
					$status_credit = "<span class='label' style='background-color: #fb4e4e'>On Going</span>";
					$status_todo = "Check / Delivery";
					$status_tanggal_outstanding =  NULL ;
					
				}else if ($row->status_credit =='Rejected'){
					$status_credit = "<span class='label' style='background-color: #ef0707'>Rejected</span>";
					$status_todo =  "-";
					$status_tanggal_outstanding =  NULL ;
				}else if ($row->status_credit =='Pending' ){
					$status_credit = "<span class='label' style='background-color: #e9d85e'>Pending</span>";
					$status_todo =  "-";
					$status_tanggal_outstanding = $row->tgl_cetak_invoice ;
				}else if ($row->status_credit =='Approved' ){
					$status_credit = "<span class='label' style='background-color: #870404'>Approved</span>";
					$status_todo =  "<span class='label' style='background-color: #fb4e4e'>On Going</span>";
					$status_tanggal_outstanding = NULL ;
				}else if ($row->status_credit =='Invoice Send' ){
					$status_credit = "<span class='label' style='background-color: #4ac742'>Invoice Send</span>";
						if ($row->id_finance_company =='FC00000003'){
							$status_todo =  "<span class='label' style='background-color: #fb4e4e'>Not yet Disbursed</span>";
							$status_tanggal_outstanding = $row->tgl_cetak_invoice ;
							$status_tanggal_outstanding = NULL ;
						}
						$status_todo =  "Disburst";
				}else if ($row->status_credit =='Disburst'){
					$status_credit = "<span class='label' style='background-color: #4ac742'>Disburst</span>";
						if ($row->id_finance_company =='FC00000003'){
							$status_todo =  "<span class='label' style='background-color: #fb4e4e'>Disburst</span>";
							$status_tanggal_outstanding = $row->tgl_cetak_invoice ;
						}
						$status_todo =  "-";
				}
				
				else if ($row->status_credit =='-' ){
					$status_credit = "-";
					$status_todo =  "-";
					$status_tanggal_outstanding = NULL;
				}
				

				if($status_tanggal_outstanding !==NULL){

					$date = new DateTime($status_tanggal_outstanding);
					$date = $date->format('Y-m-d');
	
					$date_sekarang = new DateTime($tanggal_akhir);
					$date_sekarang = $date_sekarang->format('Y-m-d');


					$timestamp1 = strtotime($date_sekarang);
					$timestamp2 = strtotime($date);

					$difference = $timestamp2 - $timestamp1;
					$daysDifference = floor($difference / (60 * 60 * 24));

					$days = abs($daysDifference)." Day";
				}else{
					$days ="-";
				}

				if (!empty($row->orders ))   		{ $count_order=++$order;}
				if (!empty($row->approved )) 		{ $count_approved=++$approve ;}
				if (!empty($row->rejected ))		{ $count_rejected=++$rejected ;}
				if (!empty($row->ongoing ))  		{ $count_on_going=++$on_going ;}
				if (!empty($row->delivered ))		{ $count_delivery=++$delivery ;}
				if (!empty($row->scheduled ))		{ $count_schedule=++$schedule ;}
				if (!empty($row->pending ) )		{ $count_pending=++$pending ;}
				if (!empty($row->invoice_send)) 	{ $count_invoice_send=++$invoice_send ;}
				if (!empty($row->not_invoice_send)) { $count_not_invoice_send=++$not_invoice_send;}
				if (!empty($row->disbursed ))		{ $count_disbursed=++$disbursed ;}
				if (!empty($row->not_disbursed)) 	{ $count_not_disbursed=++$not_disbursed;}
				

				$tgl1 = new DateTime("2020-01-01");
				$tgl2 = new DateTime($tanggal_akhir);
				$jarak = $tgl2->diff($tgl1);


				if ($row->id_finance_company =='FC00000003'){
					$cetak_invoice = $row->tgl_cetak_invoice2;
				}else{
					$cetak_invoice = $row->tgl_cetak_invoice;
				}

				$output.="<tr>";
				$output.="<td>".++$urut. "</td>"; 
				$output.="<td>".$row->no_order_survey."</td>"; 
				$output.="<td>".$row->no_spk. "</td>"; 
				$output.="<td>".$row->nama_konsumen."</td>"; 
				$output.="<td>".$row->finance_company."</td>"; 
				$output.="<td>".$row->id_tipe_kendaraan." - ".$row->tipe_ahm."</td>"; 
				$output.="<td>".$row->create_order_survey."</td>"; 
				$output.="<td>".$row->create_hasil_survey."</td>"; 
				$output.="<td>".$row->tgl_pengiriman."</td>"; 
				$output.="<td>".$cetak_invoice."</td>"; 
				$output.="<td>".$row->no_invoice."</td>"; 
				$output.="<td>".$days."</td>"; 
				$output.="<td>".$status_credit."</td>"; 	
				$output.="<td>".$status_todo."</td>"; 
				$output.="<td>".$row->jumlah."%</td>"; 
				$output.="</tr>";
			}
        } else {
			$output.="<tr>";
			$output.="<td>Data tidak Ditemukan, Mohon periksa kembali inputan Data</td>"; 
			$output.="</tr>";
        }

		if (!empty($count_total)) {
			$success_rate = ($count_approved / $count_total)*100; 
			$rejection_rate = ($count_rejected / $count_total)*100; 
		}else{
			$success_rate = 0;
			$rejection_rate = 0;
		}


		$cek['order']      =$count_order;
		$cek['approved']   =$count_approved;
		$cek['rejected']   =$count_rejected;
		$cek['on_going']   =$count_on_going;
		$cek['delivered']  =$count_delivery;
		$cek['schedule']   =$count_schedule;
		$cek['pending']    =$count_pending;
		$cek['inv_send']   =$count_invoice_send;
		$cek['inv_send_not'] =$count_not_invoice_send;
		$cek['disbursed']    =$count_disbursed;
		$cek['disbursed_not_yet'] =$count_not_disbursed;
		$cek['sucess_rate']     =round($success_rate, 2)."%";
		$cek['rejection_rate']  =round($rejection_rate,2)."%";
		$cek['output'] =$output;
		
		echo json_encode($cek);
	}


	
}
