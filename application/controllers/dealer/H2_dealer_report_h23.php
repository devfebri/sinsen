<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_report_h23 extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "h2_dealer_report_h23";
  var $title  =   "Reporting H23 Dealer";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_dealer_report_h23_model');
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
    ini_set('display_errors', 0);
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
  }

  public function downloadReport(){
    $data['id_dealer'] = $id_dealer = $this->m_admin->cari_dealer();
    $data['start_date']= $start_date= $this->db->escape_str($this->input->post('tgl1'));
	$data['end_date']  = $end_date	= $this->db->escape_str($this->input->post('tgl2'));
	$data['type']      = $type	    = $this->db->escape_str($this->input->post('type'));
    $jenis_process = $this->db->escape_str($this->input->post('process'));

    if($type=='all_ahass'){
        $data['all_ahass'] = $all_ahass = $this->H2_dealer_report_h23_model->downloadReportAhass($id_dealer,$start_date,$end_date, $type);
        $data_ue = array();
			foreach($data['all_ahass']->result() as $row){
					$ue_query = $this->db->query("SELECT SUM(CASE WHEN sa.id_type NOT IN ('JR','C1','QS','PUD','PL') then 1 ELSE 0 end) as ue
						from tr_h2_sa_form sa
						join tr_h2_wo_dealer wo on wo.id_sa_form=sa.id_sa_form 
						where wo.status='Closed' and wo.created_njb_at>='$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59' and wo.id_dealer = $row->id_dealer
						GROUP BY wo.id_dealer 
					")->row();
					if($ue_query->ue == '' or $ue_query->ue == NULL){
						$ue_query = $this->db->query("SELECT '' as ue
						")->row();
					} 
					$data_ue[] = $ue_query;
			}
			$data['data_ue'] = $data_ue;
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ahass",$data);
        }
    }elseif($type=='general_report'){
        $data['dealer']     = $this->H2_dealer_report_h23_model->getDataAhass($id_dealer);
        $data['hariKerja'] = $hariKerja = $this->H2_dealer_report_h23_model->hariKerja($id_dealer,$start_date,$end_date);
        $data['jumlahUE'] = $jumlahUE = $this->H2_dealer_report_h23_model->jumlahUE($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_general_report",$data);
        }
    }elseif($type=='type_motor'){
        $data['type_motor']= $type_motor = $this->H2_dealer_report_h23_model->downloadReportMotor($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_type_motor",$data);
        }
    }elseif($type=='tgl_trx'){
        $data['tgl_trx'] = $tgl_trx = $this->H2_dealer_report_h23_model->downloadReportTrx($id_dealer,$start_date,$end_date);
        $data['ue_jam'] = $ue_jam = $this->H2_dealer_report_h23_model->dataUEperJam($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_tgl_trx",$data);
        }
    }elseif($type=='trx'){
        $data['transaksi'] = $transaksi = $this->H2_dealer_report_h23_model->downloadReportTransaksional($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_trx",$data);
        }
    }elseif($type=='mekanik'){
        $data['mekanik'] = $mekanik = $this->H2_dealer_report_h23_model->downloadReportMekanik($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_mekanik",$data);
        }
    }elseif($type=='sa'){
        $data['sa'] = $sa = $this->H2_dealer_report_h23_model->downloadReportSA($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_sa",$data);
        }
    }elseif($type=='all'){
        $data['type_motor']= $type_motor = $this->H2_dealer_report_h23_model->downloadReportMotor($id_dealer,$start_date,$end_date);
        $data['tgl_trx'] = $tgl_trx = $this->H2_dealer_report_h23_model->downloadReportTrx($id_dealer,$start_date,$end_date);
        $data['mekanik'] = $mekanik = $this->H2_dealer_report_h23_model->downloadReportMekanik($id_dealer,$start_date,$end_date);
        $data['sa'] = $sa = $this->H2_dealer_report_h23_model->downloadReportSA($id_dealer,$start_date,$end_date);
        $data['partRevSales'] = $partRevSales = $this->H2_dealer_report_h23_model->partRevenue($id_dealer,$start_date,$end_date)->row();
        $data['jasaRev'] = $jasaRev = $this->H2_dealer_report_h23_model->JasaRevenue($id_dealer,$start_date,$end_date)->row();
        $data['alasanDatang'] = $alasanDatang = $this->H2_dealer_report_h23_model->alasanDatangKeAhass($id_dealer,$start_date,$end_date);
        $data['activityPromotion'] = $activityPromotion = $this->H2_dealer_report_h23_model->activityPromotion($id_dealer,$start_date,$end_date);
        $data['activityCapacity'] = $activityCapacity = $this->H2_dealer_report_h23_model->activityCapacity($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_all",$data);
        }
    }elseif($type=='toj'){
        $data['toj'] = $toj = $this->H2_dealer_report_h23_model->dataTOJ($id_dealer,$start_date,$end_date);
        $data_ue = array();
			foreach($data['toj']->result() as $row){
					$ue_query = $this->db->query("SELECT SUM(CASE WHEN sa.id_type NOT IN ('JR','C1','QS') then 1 ELSE 0 end) as ue
						from tr_h2_sa_form sa
						join tr_h2_wo_dealer wo on wo.id_sa_form=sa.id_sa_form 
						where wo.status='Closed' and wo.created_njb_at>='$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59' and wo.id_dealer = $row->id_dealer
						GROUP BY wo.id_dealer 
					")->row();
					if($ue_query->ue == '' or $ue_query->ue == NULL){
						$ue_query = $this->db->query("SELECT '' as ue
						")->row();
					} 
					$data_ue[] = $ue_query;
			}
			$data['data_ue'] = $data_ue;

        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_toj",$data);
        }
    }elseif($type=='toj11'){
        $data['toj11'] = $toj11 = $this->H2_dealer_report_h23_model->dataTOJ11($id_dealer,$start_date,$end_date);
        // $data_ue = array();
		// 	foreach($data['toj11']->result() as $row){
		// 			$ue_query = $this->db->query("SELECT SUM(CASE WHEN sa.id_type NOT IN ('JR','OTHER','C1','QS','PUD','PL') then 1 ELSE 0 end) as ue
		// 				from tr_h2_sa_form sa
		// 				join tr_h2_wo_dealer wo on wo.id_sa_form=sa.id_sa_form 
		// 				where wo.status='Closed' and wo.created_njb_at>='$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59' and wo.id_dealer = $row->id_dealer
		// 				GROUP BY wo.id_dealer 
		// 			")->row();
		// 			if($ue_query->ue == '' or $ue_query->ue == NULL){
		// 				$ue_query = $this->db->query("SELECT '' as ue
		// 				")->row();
		// 			} 
		// 			$data_ue[] = $ue_query;
		// 	}
		// 	$data['data_ue'] = $data_ue;
            
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_toj11",$data);
        }
    }elseif($type=='jasa_rev'){
        $data['jasaRev'] = $jasaRev = $this->H2_dealer_report_h23_model->JasaRevenue($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_jasa_rev",$data);
        }
    }elseif($type=='part_rev'){
        $data['partRevSales'] = $partRevSales = $this->H2_dealer_report_h23_model->partRevenue($id_dealer,$start_date,$end_date);
        $data['partCount'] = $partCount = $this->H2_dealer_report_h23_model->partCount($id_dealer,$start_date,$end_date);
        $data['salesIn'] = $salesIn = $this->H2_dealer_report_h23_model->salesIn($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_part_rev",$data);
        }
    }elseif($type=='pos_rev'){
        $data['posAHASSWO'] = $posService = $this->H2_dealer_report_h23_model->posAHASSRevenueWO($id_dealer,$start_date,$end_date,$type);
        $data['posAHASSTanpaWO'] = $posService = $this->H2_dealer_report_h23_model->posAHASSRevenueTanpaWO($id_dealer,$start_date,$end_date,$type);
        $data['posAHASSJasa'] = $posService = $this->H2_dealer_report_h23_model->posAHASSJasa($id_dealer,$start_date,$end_date,$type);
        $data['mekanikposService'] = $mekanikposService = $this->H2_dealer_report_h23_model->mekanikPosServiceRev($id_dealer,$start_date,$end_date,$type);
        $data['saposService'] = $saposService = $this->H2_dealer_report_h23_model->saPosServiceRev($id_dealer,$start_date,$end_date,$type);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_pos_rev",$data);
        }
    }elseif($type=='ahass_rev'){
        $data['dealer']     = $this->H2_dealer_report_h23_model->getDataAhass($id_dealer);
		$data['posAHASSWO'] = $posService = $this->H2_dealer_report_h23_model->posAHASSRevenueWO($id_dealer,$start_date,$end_date,$type);
		$data['posAHASSTanpaWO'] = $posService = $this->H2_dealer_report_h23_model->posAHASSRevenueTanpaWO($id_dealer,$start_date,$end_date,$type);
		$data['posAHASSJasa'] = $posService = $this->H2_dealer_report_h23_model->posAHASSJasa($id_dealer,$start_date,$end_date,$type);
		$data['mekanikposService'] = $mekanikposService = $this->H2_dealer_report_h23_model->mekanikPosServiceRev($id_dealer,$start_date,$end_date,$type);
		$data['saposService'] = $saposService = $this->H2_dealer_report_h23_model->saPosServiceRev($id_dealer,$start_date,$end_date,$type);
		$data['apRev'] = $apRev = $this->H2_dealer_report_h23_model->activityPromotionRev($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ahass_rev",$data);
        }
    }elseif($type=='ap_rev'){
        // $data['apRev'] = $apRev = $this->H2_md_report_h23_model->activityPromotionRev($id_dealer,$start_date,$end_date);
        $data['apRev2'] = $apRev2 = $this->H2_dealer_report_h23_model->activityPromotionRev2($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ap_rev",$data);
        }
    }elseif($type=='mekanik_detail'){
        $data['dataMekanik'] = $dataMekanik = $this->H2_dealer_report_h23_model->dataMekanik($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_mekanik_detail",$data);
        }
    }elseif($type=='ue_alasan'){
        $data['alasankeAHASS'] = $alasankeAHASS = $this->H2_dealer_report_h23_model->dataAHASSperAlasanDatang($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ue_alasan",$data);
        }
    }elseif($type=='ue_ap'){
        $data['ue_ap'] = $ue_ap = $this->H2_dealer_report_h23_model->dataAHASSperActivityPromotion($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ue_ap",$data);
        }
    }elseif($type=='ue_ac'){
        $data['ue_ac'] = $ue_ac = $this->H2_dealer_report_h23_model->dataAHASSperActivityCapacity($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ue_ac",$data);
        }
    }elseif($type=='ue_motor'){
        $data['ue_segment3'] = $ue_segment3 = $this->H2_dealer_report_h23_model->dataSegmentMotor3($id_dealer,$start_date,$end_date);
        $data['ue_segment4'] = $ue_segment4 = $this->H2_dealer_report_h23_model->dataSegmentMotor4($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ue_motor",$data);
        }
    }elseif($type=='ue_jam'){
        $data['ue_jam'] = $ue_jam = $this->H2_dealer_report_h23_model->dataUEperJam($id_dealer,$start_date,$end_date);
        if($jenis_process =='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_h23_ue_jam",$data);
        }
    }elseif($type=='report_picking_slip'){
        $data['report_picking_slip'] = $report_picking_slip = $this->H2_dealer_report_h23_model->report_picking_slip($id_dealer,$start_date,$end_date);
        if($_POST['process']=='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_picking_slip",$data);
        }
    }elseif($type=='report_wo_gantung'){
        $data['report_wo_gantung'] = $report_wo_gantung = $this->H2_dealer_report_h23_model->report_wo_gantung($id_dealer,$start_date,$end_date);
        if($_POST['process']=='excel'){
            $this->load->view("dealer/laporan/temp_h2_dealer_report_wo_gantung",$data);
        }
    }


    
  }
}
