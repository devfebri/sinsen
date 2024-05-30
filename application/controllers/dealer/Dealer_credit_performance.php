
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_credit_performance extends CI_Controller
{
	var $tables = "";
	var $folder = "dealer/laporan";
	var $page   = "dealer_credit_performance";
	var $isi    = "dealer_credit_performance";
	var $pk     = "";
	var $title  = "SLA Fincoy - Performance";

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


	public function dashboard()
	{
		$data['isi']       = $this->isi;
		$data['title']	   = $this->title;
		$data['page']  	   = $this->page;
		$data['set']	   = "test";
		$this->template($data);
	}

	public function index()
	{
			$data['isi']       = $this->isi;
			$data['title']	   = $this->title;
			$data['page']  	   = $this->page;
			$data['set']	   = "view";
			$data['kecamatan'] = $this->H1_md_report_sla_fincoy_model->get_kecamatan();
			$data['segment']   = $this->H1_md_report_sla_fincoy_model->get_segment();
			$data['fincoy']    = $this->H1_md_report_sla_fincoy_model->get_fincoy();
			$this->template($data);

			// $dealer    = $this->m_admin->cari_dealer();
			// if ($dealer == 1 || $dealer == 103){
			// 	$data['isi']       = $this->isi;
			// 	$data['title']	   = $this->title;
			// 	$data['page']  	   = $this->page;
			// 	$data['set']	   = "view";
			// 	$data['kecamatan'] = $this->H1_md_report_sla_fincoy_model->get_kecamatan();
			// 	$data['segment']   = $this->H1_md_report_sla_fincoy_model->get_segment();
			// 	$data['fincoy']    = $this->H1_md_report_sla_fincoy_model->get_fincoy();
			// 	$this->template($data);

			// }else{
			// 	die();
			// }
	
	}

	public function get_data_series(){
		$id_series=$this->input->get('series'); 
		$series  = $this->H1_md_report_sla_fincoy_model->get_series($id_series);

			$seriesloop= "<option value='all'>- All -</option>";
			foreach ($series->result() as $data ){
			$seriesloop.= "<option value='$data->id_series'>$data->id_series</option>";
			}
			echo $seriesloop;
	}
	
	public function cash_vs_credit(){
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');

		$kecamatan =  $this->input->post('kecamatan');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();
		$datas['cash_vs_credit'] = $this->H1_md_report_sla_fincoy_model->get_data_cash_vs_credit($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);
		echo json_encode($datas);
	}

		
	public function reject_vs_approval()
	{
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$kecamatan =  $this->input->post('kecamatan');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();
		$datas['reject_vs_approval']   = $this->H1_md_report_sla_fincoy_model->get_data_reject_vs_approval($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);
		echo json_encode($datas);
		}


	public function reject_by_oc()
	{
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$kecamatan =  $this->input->post('kecamatan');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
			$dealer    = $this->m_admin->cari_dealer();
		$datas['reject_by_oc']   = $this->H1_md_report_sla_fincoy_model->get_data_reject_by_oc($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);

		if (count($datas['reject_by_oc']) == 0 ){
			$pekerjaans=  0;
			$jum_pekerjaans = 0;
		}else{

		$pekerjaans = new stdclass();
		$jum_pekerjaans = new stdclass();
		
		$temp = array();
		foreach ($datas['reject_by_oc'] as $mon) {
			$array_pekerjaans[]        =  (string) $mon->nama_pekerjaan;
			$array_jum_pekerjaans[]    =  (int) $mon->jumlah_pekerjaan;
		}
		  $pekerjaans=  $array_pekerjaans;
		  $jum_pekerjaans =  $array_jum_pekerjaans;
		}

		  $temp[0] = $pekerjaans;
		  $temp[1] = $jum_pekerjaans;
		  $json['data']=$pekerjaans;
		  $json['categories']=$jum_pekerjaans;

		echo json_encode($json);
	}

	
	public function credit_share()
	{
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$kecamatan =  $this->input->post('kecamatan');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();

		$datas['credit_share']   = $this->H1_md_report_sla_fincoy_model->get_data_credit_share($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);
		$datas['fincoy']         = $this->H1_md_report_sla_fincoy_model->get_fincoy();

		$temp = array();
		foreach ($datas['fincoy'] as $key => $field) {
		$obj = new stdclass();
		$obj->name = $field->finance_company;
		$obj->data = NULL;
		$temp[$key] = $obj;
		foreach ($datas['credit_share'] as $mon) {
			if ($mon->fincoy == $field->finance_company ) {
				$cek1 = $datas[$key]->data[$key] = $mon->m1; 	
				$cek2=  $datas[$key]->data[$key] = $mon->mtd; 	
			}
				$temp[$key]->data = [$cek1,$cek2] ; 	
			}
		}
	echo json_encode($temp);
	}

	public function sum_check_avg($diff_m1){

		$hari = floor($diff_m1 / (60 * 60 * 24));
		$jam   = floor(($diff_m1 - ($hari * 60 * 60 * 24)) / (60 * 60));
		$menit = $diff_m1 - (($hari * 60 * 60 * 24) + ($jam * (60 * 60)));
		$detik = $diff_m1 % 60;
		$waktu = ' ' . $hari . ' days , ' . $jam .  ' hours , ' . floor( $menit / 60 ) . ' minutes ' ;
		return $waktu ;
	}


	public function lead_time_mounth()
	{
		$output='';
		$segment = $this->input->POST('segment');
		$series = $this->input->POST('series');
		$tipe = $this->input->POST('tipe');
		$kecamatan =  $this->input->POST('kecamatan');
		$fincoy =  $this->input->POST('fincoy');
		$dp =  $this->input->POST('dp');
		$tanggal_akhir =  $this->input->POST('tanggal_akhir');
		$tanggal_awal =  $this->input->POST('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();

		$bar['lead_time']   = $this->H1_md_report_sla_fincoy_model->get_lead_month($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);

		$bar['fincoy']      = $this->H1_md_report_sla_fincoy_model->get_fincoy();

		$output   ='';	
		$urut     = 1;
		$int_urut = (int)$urut;
		$set_no_avg  = 0;  

		foreach ($bar['lead_time'] as $val) {
			$set_no_avg++;

			if ($val->finance_company== 'FIFASTRA'){
				$disburst = $this->H1_md_report_sla_fincoy_model->get_disburst($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)->row();

				$disburst_m1 = $this->sum_check_avg($disburst->avg_time_difference_delivery_to_disburst_m1);
				$disburst_m  = $this->sum_check_avg($disburst->avg_time_difference_delivery_to_disburst_m);

				$array_disburst_m[]  = $set_avg_buttom_disburs_m  = $disburst->avg_time_difference_delivery_to_disburst_m1;
				$array_disburst_m1[] = $set_avg_buttom_disburs_m1 = $disburst->avg_time_difference_delivery_to_disburst_m;

				$array_avg_buttom_disburs_m  = $disburst->delivery_to_disburst_m;
				$array_avg_buttom_disburs_m1 = $disburst->delivery_to_disburst_m1;
				$set_avg_left = 3;
					
				$set_with_disburst_m1 =  ((int)$val->avg_time_difference_order_to_survey_m1+(int)$val->avg_time_difference_po_to_delivery_m1 + (int)$disburst->avg_time_difference_delivery_to_disburst_m1) /$set_avg_left;
				$set_avg_with_disburts_m1 = $this->sum_check_avg( ((int)$val->avg_time_difference_order_to_survey_m1+(int)$val->avg_time_difference_po_to_delivery_m1+ (int)$disburst->avg_time_difference_delivery_to_disburst_m1)/$set_avg_left);
				
				$set_with_disburst_m =  ((int)$val->avg_time_difference_order_to_survey_m+(int)$val->avg_time_difference_po_to_delivery_m + (int)$disburst->avg_time_difference_delivery_to_disburst_m) /$set_avg_left;
				$set_avg_with_disburts_m = $this->sum_check_avg( ((int)$val->avg_time_difference_order_to_survey_m+(int)$val->avg_time_difference_po_to_delivery_m+ (int)$disburst->avg_time_difference_delivery_to_disburst_m)/$set_avg_left);
				

			
			}else{
				$set_avg_left = 2;
				$set_with_disburst_m1 =  ((int)$val->avg_time_difference_order_to_survey_m1+(int)$val->avg_time_difference_po_to_delivery_m1 ) /$set_avg_left;
				$set_avg_with_disburts_m1 = $this->sum_check_avg( ((int)$val->avg_time_difference_order_to_survey_m1+(int)$val->avg_time_difference_po_to_delivery_m1)/$set_avg_left);
				
				$set_with_disburst_m =  ((int)$val->avg_time_difference_order_to_survey_m+(int)$val->avg_time_difference_po_to_delivery_m) /$set_avg_left;
				$set_avg_with_disburts_m = $this->sum_check_avg( ((int)$val->avg_time_difference_order_to_survey_m+(int)$val->avg_time_difference_po_to_delivery_m)/$set_avg_left);

				$disburst_m  = "-";
				$disburst_m1 = "-";

				$set_avg_buttom_disburs_m1  = "-";
				$set_avg_buttom_disburs_m  = "-";
	
			}



			if ($val->avg_time_difference_order_to_survey_m1!==NULL){
				$array_order_hasil_m1[]  = (int)$val->avg_time_difference_order_to_survey_m1;
				$array_order_hasil_m[]   = (int)$val->avg_time_difference_order_to_survey_m;

				$array_avg_left_m1[] =  $set_with_disburst_m1;
				$set_avgm1 = $set_avg_with_disburts_m1;

				//  $array_avg_left_m1[] =  ((int)$val->avg_time_difference_order_to_survey_m1+(int)$val->avg_time_difference_po_to_delivery_m1)/$set_avg_left;
				//  $set_avgm1 = $this->sum_check_avg( ((int)$val->avg_time_difference_order_to_survey_m1+(int)$val->avg_time_difference_po_to_delivery_m1)/$set_avg_left);
			}else{
				$set_avgm1 ="-";
			}
			
			if ($val->avg_time_difference_po_to_delivery_m1!==NULL){
				$array_po_delivery_m[]  = (int)$val->avg_time_difference_po_to_delivery_m;
				$array_po_delivery_m1[] = (int)$val->avg_time_difference_po_to_delivery_m1;
		   }

			if ($val->avg_time_difference_order_to_survey_m!==NULL){


				$array_avg_left_m[] = $set_with_disburst_m;
				$set_avgm 			= $set_avg_with_disburts_m;

				// $array_avg_left_m[] =  ((int)$val->avg_time_difference_order_to_survey_m1+(int)$val->avg_time_difference_po_to_delivery_m1)/$set_avg_left;
				// $set_avgm = $this->sum_check_avg( ((int)$val->avg_time_difference_order_to_survey_m +(int)$val->avg_time_difference_po_to_delivery_m )/$set_avg_left);

			}else{
				$set_avgm ="-";
			}

					$output.="<tr>";
					$output.="<td>".$int_urut ++."</td>"; 
					$output.="<td>".$val->finance_company."</td>"; 
					$output.="<td><b>" .$set_avgm1. "</b></td>"; 
					$output.="<td>".$val->order_to_survey_m1."</td>"; 
					$output.="<td>".$val->po_to_delivery_m1."</td>"; 
					$output.="<td>".$disburst_m1."</td>"; 
					$output.="<td><b>".$set_avgm."</b></td>"; 
					$output.="<td>".$val->order_to_survey_m."</td>"; 
					$output.="<td>".$val->po_to_delivery_m."</td>"; 
					$output.="<td>".$disburst_m."</td>"; 
			$output.="</tr>";
			}


			$initial_m1 =array_sum($array_avg_left_m1);
			$initial_m  =array_sum($array_avg_left_m);

			$buttom_avg_m1 = $initial_m1/$set_no_avg;
			$buttom_avg_m  = $initial_m/$set_no_avg;

			$initial_m1_order_hasil =array_sum($array_order_hasil_m1);
			$initial_m_order_hasil  =array_sum($array_order_hasil_m);

			$buttom_avg_m1_po_delivery_buttom = $initial_m1_order_hasil/$set_no_avg;
			$buttom_avg_m_po_delivery_buttom  = $initial_m_order_hasil/$set_no_avg;
			
			$buttom_m1_total_average= $this->sum_check_avg($buttom_avg_m1);
			$buttom_m_total_average= $this->sum_check_avg($buttom_avg_m);
			
			$buttom_m1_total_average_order_approval	= $this->sum_check_avg($buttom_avg_m1_po_delivery_buttom);
			$buttom_m_total_average_order_approval	= $this->sum_check_avg($buttom_avg_m_po_delivery_buttom);

			// total po delivery

			$initial_m1_po_delivery =array_sum($array_po_delivery_m1);
			$initial_m_po_delivery  =array_sum($array_po_delivery_m);

			$buttom_avg_m1_po_delivery = $initial_m1_po_delivery/$set_no_avg;
			$buttom_avg_m_po_delivery  = $initial_m_po_delivery/$set_no_avg;
			
			$buttom_m1_total_average_po_delivery	= $this->sum_check_avg($buttom_avg_m1_po_delivery);
			$buttom_m_total_average_po_delivery		= $this->sum_check_avg($buttom_avg_m_po_delivery);

			$initial_m1_disburst =array_sum($array_disburst_m);
			$initial_m_disburst  =array_sum($array_disburst_m1);
			
			$buttom_avg_m1_disburst = $initial_m1_disburst/$set_no_avg;
			$buttom_avg_m_disburst  = $initial_m_disburst/$set_no_avg;
			
			// $buttom_m1_total_average_disburst = $this->sum_check_avg($set_avg_buttom_disburs_m1);
			// $buttom_m_total_average_disburst  = $this->sum_check_avg($set_avg_buttom_disburs_m);

			// $buttom_m1_total_average_disburst = $disburst_m1;
			// $buttom_m_total_average_disburst  = $disburst_m;

			$s = $buttom_avg_m1_disburst; 	
			$s1  = $buttom_avg_m_disburst;

			$buttom_m1_total_average_disburst = $this->sum_check_avg($s);
			$buttom_m_total_average_disburst  = $this->sum_check_avg($s1);

			$output.="<tr style='background-color:lightgrey'>";
			$output.="<td></td>"; 
			$output.="<td><b>AVERAGE DEALER</b></td>"; 
			$output.="<td><b>".$buttom_m1_total_average."</b></td>"; 
			$output.="<td><b>".$buttom_m1_total_average_order_approval."</b></td>"; 
			$output.="<td><b>".$buttom_m1_total_average_po_delivery."</b></td>"; 
			$output.="<td><b>".$buttom_m1_total_average_disburst."</b></td>"; 
			$output.="<td><b>".$buttom_m_total_average."</b></td>"; 
			$output.="<td><b>".$buttom_m_total_average_order_approval."</b></td>"; 
			$output.="<td><b>".$buttom_m_total_average_po_delivery."</b></td>"; 
			$output.="<td><b>".$buttom_m_total_average_disburst."</b></td>"; 
			$output.="</tr>";
			echo $output;
	}


	function reject_by_oc_daily()
	{
		$dealer    = $this->m_admin->cari_dealer();
		$segment = $this->input->POST('segment');
		$series = $this->input->POST('series');
		$tipe = $this->input->POST('tipe');
		$fincoy =  $this->input->POST('fincoy');
		$dp =  $this->input->POST('dp');
		$kecamatan =  $this->input->POST('kecamatan');
		$tanggal_akhir =  $this->input->POST('tanggal_akhir');
		$tanggal_awal =  $this->input->POST('tanggal_awal');
		$datas['reject_by_oc_daily']   = $this->H1_md_report_sla_fincoy_model->get_data_reject_by_oc_daily($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);
		$order_sales = new stdclass();
		$order_sales->name = 'Order Sales';
		$rejected_rate = new stdclass();
		$rejected_rate->name = 'Rejected Rate';
		$total = count($datas['reject_by_oc_daily']);

		$temp = array();
		foreach ($datas['reject_by_oc_daily'] as $mon) {
				$reject_persen=  ((int) $mon->rejected_rate/$total) *100;
				$array_rejected_rate[]  = number_format($reject_persen, 1) . '%';
				// $array_rejected_rate[]  =  (int) $mon->rejected_rate;

				$order_persen  =  ((int) $mon->order_sales/$total) *100;
				$array_order_sales[]  = number_format($order_persen, 1) . '%';
				// $array_order_sales[]    =  (int) $mon->order_sales;
				$array_date[]           =   $mon->tgl_spk;
		}

		  $order_sales->data   =  $array_order_sales;
		  $rejected_rate->data =  $array_rejected_rate;

		  $temp[0] = $order_sales;
		  $temp[1] = $rejected_rate;

		  $json['date']=$array_date;
		  $json['day']=$total ;
		  $json['bar']=$temp;
		
		echo json_encode($json);
	}



	function down_payment_comparrison()
	{
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$kecamatan =  $this->input->post('kecamatan');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();

		$datas['down_payment_comparrison']   = $this->H1_md_report_sla_fincoy_model->get_data_down_payment_comparrison($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)->result();
		
		$temp = array();
		$obj10 = new stdclass();
		$obj10->name = 'DP < =10 %';
		$obj10->data = NULL;
		
		$obj1015 = new stdclass();
		$obj1015->name = '10 < DP <=15 % ';
		$obj1015->data = NULL;

		$obj1520 = new stdclass();
		$obj1520->name = '15 < DP <=20 % ';
		$obj1520->data = NULL;
		
		$obj20 = new stdclass();
		$obj20->name = 'DP > 20 % ';
		$obj20->data = NULL;

					foreach ($datas['down_payment_comparrison'] as $mon) {
						if ($mon->Time == 'M1') {
							$array_mp1010[] =  (int) $mon->p1010;
							$array_p1015[]  =  (int) $mon->p1015;
							$array_p1520[]  =  (int) $mon->p1520;
							$array_p2020[]  =  (int) $mon->p2020;
						}else {
							$array_mp1010[] =  (int) $mon->p1010;
							$array_p1015[]  =  (int) $mon->p1015;
							$array_p1520[]  =  (int) $mon->p1520;
							$array_p2020[]  =  (int) $mon->p2020;
						}
		  			}

		  $obj10->data   =  $array_mp1010;
		  $obj1015->data =  $array_p1015;
		  $obj1520->data =  $array_p1520;
		  $obj20->data   =  $array_p2020;

		  $temp[0] = $obj10;
		  $temp[1] = $obj1015;
		  $temp[2] = $obj1520;
		  $temp[3] = $obj20;

		  $json['bar']=$temp;

		  echo json_encode($temp);
	}



	function down_payment_comparrison_occuptation_mtd()
	{
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$kecamatan =  $this->input->post('kecamatan');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
			$dealer    = $this->m_admin->cari_dealer();

		$datas['down_payment_comparrison_occuptation_mtd'] = $this->H1_md_report_sla_fincoy_model->down_payment_comparrison_occuptation_mtd($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);
		$pekerjaan = $this->H1_md_report_sla_fincoy_model->get_pekerjaan();
		
		$data['pekerjaan'] = $this->H1_md_report_sla_fincoy_model->get_pekerjaan();

		$obj10 = new stdclass();
		$obj10->name = 'DP < =10 %';
		$obj10->data = NULL;
		
		$obj1015 = new stdclass();
		$obj1015->name = '10 < DP <=15 % ';
		$obj1015->data = NULL;

		$obj1520 = new stdclass();
		$obj1520->name = '15 < DP <=20 % ';
		$obj1520->data = NULL;
		
		$obj20 = new stdclass();
		$obj20->name = 'DP > 20 % ';
		$obj20->data = NULL;


		$temp = array();
		foreach ($data['pekerjaan']  as $key => $value) {
					foreach ($datas['down_payment_comparrison_occuptation_mtd'] as $mon) {
						if ($mon->pekerjaan == $value->id_pekerjaan) {
							$array_10[]    =  (int) $mon->p1010;
							$array_1015[]  =  (int) $mon->p1015;
							$array_1520[]  =  (int) $mon->p1520;
							$array_2020[]  =  (int) $mon->p2020;
						}
		  	}
		}

		  $obj10->data   =  $array_10;
		  $obj1015->data =  $array_1015;
		  $obj1520->data =  $array_1520;
		  $obj20->data   =  $array_2020;

		  $temp[0] = $obj10;
		  $temp[1] = $obj1015;
		  $temp[2] = $obj1520;
		  $temp[3] = $obj20;

		  $json['bar']=$temp;
		  $json['pekerjaan']= $pekerjaan;

		echo json_encode($json);
	}
	

	function down_payment_per_fincoy()
	{
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$kecamatan =  $this->input->post('kecamatan');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();

		$bar['fincoys'] = $this->H1_md_report_sla_fincoy_model->get_fincoy_time();
		$bar['fincoy'] = $this->H1_md_report_sla_fincoy_model->get_fincoy_id();
		$datas['down_payment_per_fincoy'] = $this->H1_md_report_sla_fincoy_model->down_payment_per_fincoy($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)->result();

		$obj10 = new stdclass();
		$obj10->name = 'DP < =10 %';
		$obj10->data = NULL;
		
		$obj1015 = new stdclass();
		$obj1015->name = '10 < DP <=15 % ';
		$obj1015->data = NULL;

		$obj1520 = new stdclass();
		$obj1520->name = '15 < DP <=20 % ';
		$obj1520->data = NULL;
		
		$obj20 = new stdclass();
		$obj20->name = 'DP > 20 % ';
		$obj20->data = NULL;


		$temp = array();
		foreach ($bar['fincoy']   as $key => $value) {
				foreach ($datas['down_payment_per_fincoy'] as $mon) {
					if ($value->id_finance_company == $mon->id_finance_company) {
						if  ($mon->Times == 'm1' && $value->id_finance_company  ==  $value->id_finance_company  ){
						
							$array_10[]    =  (int) $mon->p1010;
							$array_1015[]  =  (int) $mon->p1015;
							$array_1520[]  =  (int) $mon->p1520;
							$array_2020[]  =  (int) $mon->p2020;
						}else if  ($mon->Times == 'mtd' &&  $value->id_finance_company  ==  $value->id_finance_company ){
							$array_10[]    =  (int) $mon->p1010;
							$array_1015[]  =  (int) $mon->p1015;
							$array_1520[]  =  (int) $mon->p1520;
							$array_2020[]  =  (int) $mon->p2020;
						
						}
					}
		  	}
		}

		  $obj10->data   =  $array_10;
		  $obj1015->data =  $array_1015;
		  $obj1520->data =  $array_1520;
		  $obj20->data   =  $array_2020;

		  $temp[0] = $obj10;
		  $temp[1] = $obj1015;
		  $temp[2] = $obj1520;
		  $temp[3] = $obj20;

		  
		  $fincoyss = new stdclass();
		  foreach ($bar['fincoys']  as $value) {
			  $fin[]  =  $value->finance_company. "(".$value->Time.")";
			}
			
		  $fincoyss= $fin;

		  $valuesToInsert = array("", "", "", "", "", "","");
		  $positionsToInsert = array(2, 4, 6, 8, 10, 12,14);
		  
		  foreach ($positionsToInsert as $index => $position) {
			  array_splice($fincoyss, $position + $index, 0, $valuesToInsert[$index]);
		  }
  
		  foreach ($temp as $object) {
			  foreach ($positionsToInsert as $index => $position) {
				  array_splice($object->data, $position + $index, 0, $valuesToInsert[$index]);
			  }
		  }

		  $json['bar']=$temp;
		  $json['fincoy']=$fincoyss;
		echo json_encode($json);
	}

	
	function reject_by_down_perfincoy_mtd()
	{
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$kecamatan =  $this->input->post('kecamatan');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();

		$bar['fincoys'] = $this->H1_md_report_sla_fincoy_model->get_fincoy_time();
		$bar['fincoy'] = $this->H1_md_report_sla_fincoy_model->get_fincoy_id();
		$datas['down_payment_per_fincoy'] = $this->H1_md_report_sla_fincoy_model->reject_by_down_perfincoy_mtd($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)->result();

		$obj10 = new stdclass();
		$obj10->name = 'DP < =10 %';
		$obj10->data = NULL;
		
		$obj1015 = new stdclass();
		$obj1015->name = '10 < DP <=15 % ';
		$obj1015->data = NULL;

		$obj1520 = new stdclass();
		$obj1520->name = '15 < DP <=20 % ';
		$obj1520->data = NULL;
		
		$obj20 = new stdclass();
		$obj20->name = 'DP > 20 % ';
		$obj20->data = NULL;

		$obj1015 = new stdclass();
		$obj1015->name = '10 < DP <=15 % ';
		$obj1015->data = NULL;

		$temp = array();
		foreach ($bar['fincoy']   as $key => $value) {
					foreach ($datas['down_payment_per_fincoy'] as $mon) {
						if ($value->id_finance_company == $mon->id_finance_company  ) {
							if ($mon->Times =='m1' && $value->id_finance_company == $mon->id_finance_company   ){
								$array_10[]    =  (int) $mon->p1010;
								$array_1015[]  =  (int) $mon->p1015;
								$array_1520[]  =  (int) $mon->p1520;
								$array_2020[]  =  (int) $mon->p2020;
							}else if ($mon->Times =='mtd' && $value->id_finance_company == $mon->id_finance_company){
								$array_10[]    =  (int) $mon->p1010;
								$array_1015[]  =  (int) $mon->p1015;
								$array_1520[]  =  (int) $mon->p1520;
								$array_2020[]  =  (int) $mon->p2020;
							}
						}
		  			}
			}

		  $obj10->data   =  $array_10;
		  $obj1015->data =  $array_1015;
		  $obj1520->data =  $array_1520;
		  $obj20->data   =  $array_2020;

		  $temp[0] = $obj10;
		  $temp[1] = $obj1015;
		  $temp[2] = $obj1520;
		  $temp[3] = $obj20;

		  $fincoyss = new stdclass();
		  foreach ($bar['fincoys']   as $value) {
				  $fin[]  =  $value->finance_company."".$value->Time;
		  }

		  $fincoyss= $fin;

		  $valuesToInsert = array("", "", "", "", "", "","");
		  $positionsToInsert = array(2, 4, 6, 8, 10, 12,14);
		  
		  foreach ($positionsToInsert as $index => $position) {
			  array_splice($fincoyss, $position + $index, 0, $valuesToInsert[$index]);
		  }
  
		  foreach ($temp as $object) {
			  foreach ($positionsToInsert as $index => $position) {
				  array_splice($object->data, $position + $index, 0, $valuesToInsert[$index]);
			  }
		  }
  

		  $json['bar']=$temp;
		  $json['fincoy']=$fincoyss;

		echo json_encode($json);
	
	}

	public function avg_reject_approval_per_fincoy(){
		$segment = $this->input->post('segment');
		$series = $this->input->post('series');
		$tipe = $this->input->post('tipe');
		$kecamatan =  $this->input->post('kecamatan');
		$fincoy =  $this->input->post('fincoy');
		$dp =  $this->input->post('dp');
		$tanggal_akhir =  $this->input->post('tanggal_akhir');
		$tanggal_awal =  $this->input->post('tanggal_awal');
		$dealer    = $this->m_admin->cari_dealer();

		$bar['fincoys'] = $this->H1_md_report_sla_fincoy_model->get_fincoy_time();
		$bar['fincoy'] = $this->H1_md_report_sla_fincoy_model->get_fincoy_id();
		$datas['avg_reject_approval_per_fincoy'] = $this->H1_md_report_sla_fincoy_model->avg_reject_approval_per_fincoy($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)->result();

		$obj = new stdclass();
		$obj->name = 'Approve';
		
		$obj2 = new stdclass();
		$obj2->name = 'Reject';

		$temp = array();
		foreach ($bar['fincoy']   as $key => $value) {
			foreach ($datas['avg_reject_approval_per_fincoy'] as $mon) {
				if ($value->id_finance_company == $mon->id_finance_company) {
					if ($mon->Times =='m1'&& $value->id_finance_company  ==  $value->id_finance_company){
						$buah_approve[] =  (string) $mon->m1_approve;
						$buah_reject[]  =  (string) $mon->m1_rejected;
					}else if ($mon->Times =='mtd'&& $value->id_finance_company  ==  $value->id_finance_company){
						$buah_approve[] =  (string) $mon->mtd_approve;
						$buah_reject[]  =  (string) $mon->mtd_rejected;
					}
				}
			  }
		}
		
		$obj->data =   $buah_approve;
		$obj2->data =  $buah_reject;
		$temp[0] = $obj;
		$temp[1] = $obj2;

		
		$fincoyss = new stdclass();
		foreach ($bar['fincoys']   as $value) {
				$fin[]  =  $value->finance_company."".$value->Time;
		}

		$fincoyss= $fin;

		$valuesToInsert = array("", "", "", "", "", "","");
		$positionsToInsert = array(2, 4, 6, 8, 10, 12,14);
		
		foreach ($positionsToInsert as $index => $position) {
			array_splice($fincoyss, $position + $index, 0, $valuesToInsert[$index]);
		}

		foreach ($temp as $object) {
			foreach ($positionsToInsert as $index => $position) {
				array_splice($object->data, $position + $index, 0, $valuesToInsert[$index]);
			}
		}

		$json['bar']=$temp;
		$json['fincoy']=$fincoyss;
		echo json_encode($json);
	
	}

	




}

