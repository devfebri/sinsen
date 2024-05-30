<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_utilisasi_h23 extends CI_Controller
{

  var $folder =   "h2";
  var $page    =    "monitoring_utilisasi_h23";
  var $title  =   "Monitoring Utilisasi H23";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
    //===== Load Library =====		

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "") {
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
      $data['title']  = "Monitoring Utilisasi H23";
      $data['set']    = "view";
      $this->template($data);
  }

	public function download(){
		$start_date = $this->input->post('started');
		$end_date = $this->input->post('ended');

		if($start_date =='' and $end_date==''){
			$start_date = date('Y-m-01');
			$end_date = date('Y-m-t');
		}

		// $data['list_data'] = $this->m_lap->getDataUtilisasi_Tablet($start_date, $end_date);
		if($_POST['generate']=='export_sc'){
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$data['list_dealer'] = $this->db->query("Select id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer  from ms_dealer where active = 1 and (h1=1 or h2=1) and pos !='ya' and id_dealer in ('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101','102','103','104','105','106','107','714', '5','10','28','56','69','715','93','741','742') order by nama_dealer asc")->result();
			//$data['list_dealer'] = $this->db->query("Select kode_dealer_md, kode_dealer_ahm, nama_dealer from ms_dealer where active = 1 and h1=1 and pos !='ya' order by nama_dealer asc")->result();
			$this->load->view('h2/laporan/laporan_utilisasih23_sc',$data);
		}else if($_POST['generate']=='export_dgi'){
      		$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			  
			$data['dealer']  = $this->db->query("select kode_dealer_md, nama_dealer from ms_dealer where kode_dealer_md in ('13382', '13384', '07628', '12142', '12143', '13387', '13867', '13388','13759', '12144', '12598','13381','12797','13621') ORDER BY nama_dealer asc")->result();

			$data['monitoring']  = $this->db->query("select d.kode_dealer_md , d.nama_dealer, a.kategori , a.pinpoint ,count(1) as hit, sum(a.data_count) as total_data from dgi_activity_log a 
			join ms_dgi_api_key b on a.api_key =b.api_key 
			join ms_dealer c on b.id_dealer = c.kode_dealer_md 
			join ms_dealer d on c.kode_dealer_ahm = d.kode_dealer_md 
			where pinpoint is not null and 
			DATE_FORMAT(FROM_UNIXTIME(request_time), '%Y-%m-%d') >= DATE_FORMAT( now(),'$start_date')
			and DATE_FORMAT(FROM_UNIXTIME(request_time), '%Y-%m-%d') <= DATE_FORMAT( now(),'$end_date')
			and status = 1 group by d.kode_dealer_md , d.nama_dealer, a.kategori , a.pinpoint")->result();

			$data['temp_data'] = array();
			foreach ($data['dealer'] as $key => $field) {
				$obj = new stdclass();
				$obj->kode = $field->kode_dealer_md;
				$obj->nama = $field->nama_dealer;
				$obj->kategori = NULL;
				$obj->prsp = NULL;
				$obj->spk = NULL;
				$obj->lsng = NULL; 	
				$obj->inv1 = NULL;
				$obj->bast = NULL;
				$obj->doch = NULL;
				$obj->uinb = NULL;
				$obj->inv2 = NULL;
				$obj->prsl = NULL;
				$obj->pinb = NULL;
				$obj->pkb = NULL;
				$data['temp_data'][$field->kode_dealer_md] = $obj;
			}


			foreach ($data['monitoring'] as $mon) {
				if ($mon->pinpoint == 'lsng' ) {
					$data['temp_data'][$mon->kode_dealer_md]->lsng = $mon->hit; 	
				} else if ($mon->pinpoint == 'prsp' ){
					$data['temp_data'][$mon->kode_dealer_md]->prsp = $mon->hit; 	
				} else if ($mon->pinpoint == 'spk' ){
					$data['temp_data'][$mon->kode_dealer_md]->spk = $mon->hit; 	
				} else if ($mon->pinpoint == 'inv1' ){
					$data['temp_data'][$mon->kode_dealer_md]->inv1 = $mon->hit; 	
				} else if ($mon->pinpoint == 'bast' ){
					$data['temp_data'][$mon->kode_dealer_md]->bast = $mon->hit; 	
				} else if ($mon->pinpoint == 'doch' ){
					$data['temp_data'][$mon->kode_dealer_md]->doch = $mon->hit; 	
				} else if ($mon->pinpoint == 'uinb' ){
					$data['temp_data'][$mon->kode_dealer_md]->uinb = $mon->hit; 	
				}else if ($mon->pinpoint == 'pkb' ){
					$data['temp_data'][$mon->kode_dealer_md]->pkb = $mon->hit; 	
				} else if ($mon->pinpoint == 'inv2' ){
					$data['temp_data'][$mon->kode_dealer_md]->inv2 = $mon->hit; 	
				} else if ($mon->pinpoint == 'prsl' ){
					$data['temp_data'][$mon->kode_dealer_md]->prsl = $mon->hit; 	
				}else if ($mon->pinpoint == 'pinb' ){
					$data['temp_data'][$mon->kode_dealer_md]->pinb = $mon->hit; 	
				}

				$data['temp_data'][$mon->kode_dealer_md]->kategori = $mon->kategori; 	
			}

			$this->load->view('h1/laporan/laporan_dgi_monitoring', $data);
			// echo 'Under Development';die;	
			//$this->load->view('h1/report/template/temp_laporan_bbn_csv',$data);
		}else if($_POST['generate']=='export_ahm'){
			echo 'Under Development';die;	
      // $this->load->view('h2/laporan/laporan_utilisasih23_sc_ahm',$data);
		}else if($_POST['generate']=='export_md'){
			$start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
			$end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
			ini_set('date.timezone', 'Asia/Jakarta');
			$date = date("H:i");
			$tglMulai = date("d-m-Y",strtotime($start_date));
			$tglAkhir = date("d-m-Y",strtotime($end));
			$data['subjudul'] = "Laporan Utilisasi H23 Periode $tglMulai s/d $tglAkhir - $date WIB";		
			$data['util']=$this->db->query("SELECT a.kode_dealer_ahm as kode_dealer, a.nama_dealer as nama_dealer, 
				(SELECT COUNT(*) from tr_h2_wo_dealer b where b.id_dealer=a.id_dealer and status ='closed' and input_from ='sc' and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end') as wosc,
				(SELECT COUNT(*) from tr_h2_wo_dealer b where b.id_dealer=a.id_dealer and status ='closed' and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end') as wo,
				(SELECT COUNT(*) from tr_h2_wo_dealer b WHERE b.id_dealer=a.id_dealer and status ='closed' and no_njb is not NULL and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end') as bil,
				(SELECT COUNT(*) from tr_h3_dealer_sales_order c WHERE c.id_dealer=a.id_dealer and status ='Closed' and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end') as part,
				(SELECT COUNT(*) from tr_h3_dealer_good_receipt d WHERE d.id_dealer=a.id_dealer and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end') as inbound
				FROM ms_dealer a WHERE a.id_dealer in ('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101','102','103','104','105','106','107','714', '5','10','28','56','69','715','93','741','742') 
				and active ='1' ORDER BY a.nama_dealer asc")->result();
				
			$this->load->view('h2/laporan/temp_laporan_utilisasi23_excel.php',$data);
		}else if($_POST['generate']=='export_ks'){
			$start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
			$end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
			ini_set('date.timezone', 'Asia/Jakarta');
			$date = date("H:i");
			$tglMulai = date("d-m-Y",strtotime($start_date));
			$tglAkhir = date("d-m-Y",strtotime($end));
			$data['subjudul'] = "Laporan Konsistensi H23 Periode $tglMulai s/d $tglAkhir - $date WIB";	
		
			$data['start_date'] = $start_date;
			$data['end_date'] = $end;
			$data['list_dealer'] = $this->db->query("Select id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer  from ms_dealer where active = 1 and (h1=1 or h2=1) and pos !='ya' and id_dealer in ('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101','102','103','104','105','106','107','714', '5','10','28','56','69','715','93','741','742') order by nama_dealer asc")->result();
			$this->load->view('h2/laporan/temp_laporan_konsistensi23_excel.php',$data);
		}else if($_POST['generate']=='export_ue'){
			$start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
			$end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
			ini_set('date.timezone', 'Asia/Jakarta');
			$date = date("H:i");
			$tglMulai = date("d-m-Y",strtotime($start_date));
			$tglAkhir = date("d-m-Y",strtotime($end));
			$data['subjudul'] = "Laporan Unit Entry H23 Periode $tglMulai s/d $tglAkhir - $date WIB";	
		
			$data['start_date'] = $start_date;
			$data['end_date'] = $end;
			$data['list_dealer'] = $this->db->query("Select id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer  from ms_dealer where active = 1 and (h1=1 or h2=1) and pos !='ya' and id_dealer in ('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101','102','103','104','105','106','107','714', '5','10','28','56','69','715','93','741','742') order by nama_dealer asc")->result();
			$this->load->view('h2/laporan/temp_laporan_unit_entry_excel.php',$data);
		}else if($_POST['generate']=='export_sl_pb'){
			$start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
			$end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
			ini_set('date.timezone', 'Asia/Jakarta');
			$date = date("H:i");
			$tglMulai = date("d-m-Y",strtotime($start_date));
			$tglAkhir = date("d-m-Y",strtotime($end));
			$data['subjudul'] = "Laporan Penerimaan Part AHASS Periode $tglMulai s/d $tglAkhir - $date WIB";	
		
			$data['start_date'] = $start_date;
			$data['end_date'] = $end;
			$data['data'] = $this->db->query("
				select DISTINCT b.kode_dealer_md , b.nama_dealer , a.id_reference , date(a.created_at) as tgl
				from tr_h3_dealer_good_receipt a
				join ms_dealer b on a.id_dealer = b.id_dealer 
				where a.created_at >='$start_date' and a.created_at <'$end' and a.nomor_po !='' 
				order by a.created_at asc
			")->result();
			
			$this->load->view('h2/laporan/temp_laporan_part_inbound_ahass_excel.php',$data);
		}
	}

}
