<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H2_md_report_h23 extends CI_Controller{
	
	var $folder ="h2/laporan";
	var $page   ="h2_md_report_h23";	
	var $isi    ="Reporting H23";	
	var $title  ="Reporting H23";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('H2_md_report_h23_model');	
		//===== Load Library =====		
		// $this->load->library('pdf');		
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

	protected function template($data){
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
	
	public function index(){	
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$data['dt_dealer'] = $this->H2_md_report_h23_model->getDataDealer();
		$this->template($data);		    	    
	}	

	public function downloadExcel(){
		$data['id_dealer'] = $id_dealer	= $this->input->post('id_dealer');
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['type']      = $type	    = $this->input->post('type');

		if($type=='all_ahass'){
			$data['all_ahass'] = $all_ahass = $this->H2_md_report_h23_model->downloadReportAhass($id_dealer,$start_date,$end_date, $type);
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

			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_excel",$data);
			}
		}elseif($type=='general_report'){
			$data['dealer']     = $this->H2_md_report_h23_model->getDataAhass($id_dealer);
			$data['hariKerja'] = $hariKerja = $this->H2_md_report_h23_model->hariKerja($id_dealer,$start_date,$end_date);
			$data['jumlahUE'] = $jumlahUE = $this->H2_md_report_h23_model->jumlahUE($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_general_report_excel",$data);
			}
		}elseif($type=='type_motor'){
			$data['type_motor']= $type_motor = $this->H2_md_report_h23_model->downloadReportMotor($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_type_motor_excel",$data);
			}
		}elseif($type=='tgl_trx'){
			$data['tgl_trx'] = $tgl_trx = $this->H2_md_report_h23_model->downloadReportTrx($id_dealer,$start_date,$end_date);
			$data['ue_jam'] = $ue_jam = $this->H2_md_report_h23_model->dataUEperJam($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_tgl_trx_excel",$data);
			}
		}elseif($type=='trx'){
			$data['transaksi'] = $transaksi = $this->H2_md_report_h23_model->downloadReportTransaksional($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_transaksi_excel",$data);
			}
		}elseif($type=='mekanik'){
			$data['mekanik'] = $mekanik = $this->H2_md_report_h23_model->downloadReportMekanik($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_mekanik_excel",$data);
			}
		}elseif($type=='sa'){
			$data['sa'] = $sa = $this->H2_md_report_h23_model->downloadReportSA($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_sa_excel",$data);
			}
		}elseif($type=='all'){
			$data['type_motor']= $type_motor = $this->H2_md_report_h23_model->downloadReportMotor($id_dealer,$start_date,$end_date);
			$data['tgl_trx'] = $tgl_trx = $this->H2_md_report_h23_model->downloadReportTrx($id_dealer,$start_date,$end_date);
			$data['mekanik'] = $mekanik = $this->H2_md_report_h23_model->downloadReportMekanik($id_dealer,$start_date,$end_date);
			$data['sa'] = $sa = $this->H2_md_report_h23_model->downloadReportSA($id_dealer,$start_date,$end_date);
			$data['partRevSales'] = $partRevSales = $this->H2_md_report_h23_model->partRevenue($id_dealer,$start_date,$end_date)->row();
			$data['jasaRev'] = $jasaRev = $this->H2_md_report_h23_model->JasaRevenue($id_dealer,$start_date,$end_date)->row();
			$data['alasanDatang'] = $alasanDatang = $this->H2_md_report_h23_model->alasanDatangKeAhass($id_dealer,$start_date,$end_date);
			$data['activityPromotion'] = $activityPromotion = $this->H2_md_report_h23_model->activityPromotion($id_dealer,$start_date,$end_date);
			$data['activityCapacity'] = $activityCapacity = $this->H2_md_report_h23_model->activityCapacity($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_all_excel",$data);
			}
		}elseif($type=='toj'){
			$data['toj'] = $toj = $this->H2_md_report_h23_model->dataTOJ($id_dealer,$start_date,$end_date);
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
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_toj_excel",$data);
			}
		}elseif($type=='toj11'){
			$data['toj11'] = $toj11 = $this->H2_md_report_h23_model->dataTOJ11($id_dealer,$start_date,$end_date);
			// $data_ue = array();
			// foreach($data['toj11']->result() as $row){
			// 		$ue_query = $this->db->query("SELECT SUM(CASE WHEN sa.id_type NOT IN ('JR','OTHER','C1','QS','PUD','PL') then 1 ELSE 0 end) as ue
			// 			from tr_h2_sa_form sa
			// 			join tr_h2_wo_dealer wo on wo.id_sa_form=sa.id_sa_form 
			// 			where wo.status='Closed' and wo.created_njb_at>='$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59' and wo.id_dealer = $row->id_dealer
			// 			GROUP BY wo.id_dealer 
			// 		")->row();
			// 		if($ue_query->ue == '' or $ue_query->ue == NULL){
			// 			$ue_query = $this->db->query("SELECT '' as ue
			// 			")->row();
			// 		} 
			// 		$data_ue[] = $ue_query;
			// }
			// $data['data_ue'] = $data_ue;
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_toj11_excel",$data);
			}
		}elseif($type=='jasa_rev'){
			$data['jasaRev'] = $jasaRev = $this->H2_md_report_h23_model->JasaRevenue($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_jasa_rev_excel",$data);
			}
		}elseif($type=='part_rev'){
			$data['partRevSales'] = $partRevSales = $this->H2_md_report_h23_model->partRevenue($id_dealer,$start_date,$end_date);
			$data['partCount'] = $partCount = $this->H2_md_report_h23_model->partCount($id_dealer,$start_date,$end_date);
			$data['salesIn'] = $salesIn = $this->H2_md_report_h23_model->salesIn($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_part_rev_excel",$data);
			}
		}elseif($type=='pos_rev'){
			$data['posAHASSWO'] = $posService = $this->H2_md_report_h23_model->posAHASSRevenueWO($id_dealer,$start_date,$end_date,$type);
			$data['posAHASSTanpaWO'] = $posService = $this->H2_md_report_h23_model->posAHASSRevenueTanpaWO($id_dealer,$start_date,$end_date,$type);
			$data['posAHASSJasa'] = $posService = $this->H2_md_report_h23_model->posAHASSJasa($id_dealer,$start_date,$end_date,$type);
			$data['mekanikposService'] = $mekanikposService = $this->H2_md_report_h23_model->mekanikPosServiceRev($id_dealer,$start_date,$end_date,$type);
			$data['saposService'] = $saposService = $this->H2_md_report_h23_model->saPosServiceRev($id_dealer,$start_date,$end_date,$type);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_pos_rev_excel",$data);
			}
		}elseif($type=='ahass_rev'){
			$data['dealer']     = $this->H2_md_report_h23_model->getDataAhass($id_dealer);
			$data['posAHASSWO'] = $posService = $this->H2_md_report_h23_model->posAHASSRevenueWO($id_dealer,$start_date,$end_date,$type);
			$data['posAHASSTanpaWO'] = $posService = $this->H2_md_report_h23_model->posAHASSRevenueTanpaWO($id_dealer,$start_date,$end_date,$type);
			$data['posAHASSJasa'] = $posService = $this->H2_md_report_h23_model->posAHASSJasa($id_dealer,$start_date,$end_date,$type);
			$data['mekanikposService'] = $mekanikposService = $this->H2_md_report_h23_model->mekanikPosServiceRev($id_dealer,$start_date,$end_date,$type);
			$data['saposService'] = $saposService = $this->H2_md_report_h23_model->saPosServiceRev($id_dealer,$start_date,$end_date,$type);
			$data['apRev'] = $apRev = $this->H2_md_report_h23_model->activityPromotionRev($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_ahass_rev_excel",$data);
			}
		}elseif($type=='ap_rev'){
			// $data['apRev'] = $apRev = $this->H2_md_report_h23_model->activityPromotionRev($id_dealer,$start_date,$end_date);
			$data['apRev2'] = $apRev2 = $this->H2_md_report_h23_model->activityPromotionRev2($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_ap_rev_excel",$data);
			}
		}elseif($type=='mekanik_detail'){
			$data['dataMekanik'] = $dataMekanik = $this->H2_md_report_h23_model->dataMekanik($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_mekanik_detail_excel",$data);
			}
		}elseif($type=='ue_alasan'){
			$data['alasankeAHASS'] = $alasankeAHASS = $this->H2_md_report_h23_model->dataAHASSperAlasanDatang($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_ue_alasan_excel",$data);
			}
		}elseif($type=='ue_ap'){
			$data['ue_ap'] = $ue_ap = $this->H2_md_report_h23_model->dataAHASSperActivityPromotion($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_ue_ap_excel",$data);
			}
		}elseif($type=='ue_ac'){
			$data['ue_ac'] = $ue_ac = $this->H2_md_report_h23_model->dataAHASSperActivityCapacity($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_ue_ac_excel",$data);
			}
		}elseif($type=='ue_motor'){
			$data['ue_segment3'] = $ue_segment3 = $this->H2_md_report_h23_model->dataSegmentMotor3($id_dealer,$start_date,$end_date);
			$data['ue_segment4'] = $ue_segment4 = $this->H2_md_report_h23_model->dataSegmentMotor4($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_ue_motor_excel",$data);
			}
		}elseif($type=='ue_jam'){
			$data['ue_jam'] = $ue_jam = $this->H2_md_report_h23_model->dataUEperJam($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_ue_jam_excel",$data);
			}
		}elseif($type=='peformance_fd_sa'){
			$data['peformance_fd_sa'] = $this->H2_md_report_h23_model->get_peformance_fd_sa($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_report_h23_peformance_fd_sa_excel",$data);
			}
		}elseif($type=='report_picking_slip'){
			$data['report_picking_slip'] = $this->H2_md_report_h23_model->report_picking_slip($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_h2_md_report_picking_slip",$data);
			}
		}elseif($type=='report_wo_gantung'){
			$data['report_wo_gantung'] = $this->H2_md_report_h23_model->report_wo_gantung($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/temp_h2_md_report_wo_gantung",$data);
			}
		}elseif($type=='insentif_mekanik'){
			$data['title'] = "Laporan Insentif Per Mekanik";
			$data['pit'] = $this->db->query("select b.id_pit,GROUP_CONCAT('\'',a.id_karyawan_dealer,'\'' SEPARATOR ',') as id_on_pit,GROUP_CONCAT(a.nama_lengkap SEPARATOR '<br>') as nama_lengkap from ms_h2_pit_mekanik b 
				join ms_karyawan_dealer a on a.id_karyawan_dealer=b.id_karyawan_dealer
				where b.id_dealer ='$id_dealer' 
				GROUP BY b.id_pit
			")->result();

			if($_POST['process']=='excel'){
				$this->load->view("h2/laporan/laporan_insentif_mekanik",$data);
			}			
		}
	}
	
	public function xmlFile(){
		$tgl = date('Ydm');
		$file_nama = 'AHM-E20-'.$tgl.'-'.$tgl.'.SDUE';
		$filePath = $file_nama;

		$dom     = new DOMDocument('1.0', 'utf-8'); 
		$root      = $dom->createElement('dataReq');
		$root->setAttribute('fileType', 'SDUE');

		$data_part_oli = $this->db->query("select a.id_dealer, d.nama_dealer,d.kode_dealer_ahm, SUM(CASE WHEN c.kelompok_part in('ACB','ACC','ACG','AH','AHM','BB','BBIST','BM1','BR','BRNG',
				  'BRNG2','BRNG3','BS','BT','CABLE','CB','CCKIT','CD','CDKGP','CDKIT','CH','CHAIN','COMP','COOL','CRKIT',
				  'DIHVL','DISK','ELECT','EP','EPHVL','EPMTI','ET','FKT','FLUID','GS','GSA','GSB','GST','HAH','HM','HNMTI',
				  'HPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC',
				  'OINS','OISTC','OKGD','OMTI','ORPL','OSEAL','OTHER','OTHR','PA','PAINT','PS','PSKIT','PSTN','PT','RBR','RIMWH',
				  'RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SA','SAOIL','SD','SDN','SDN2','SDT','SE','SHOCK','SP2','SPGUI',
				  'SPOKE','STR','TAS','TDI','TR','VALVE','VV') and a.referensi='sales' then a.tot_nsc ELSE 0 END) AS spart_tanpa_wo,
				   SUM(CASE WHEN c.kelompok_part in('ACB','ACC','ACG','AH','AHM','BB','BBIST','BM1','BR','BRNG',
				  'BRNG2','BRNG3','BS','BT','CABLE','CB','CCKIT','CD','CDKGP','CDKIT','CH','CHAIN','COMP','COOL','CRKIT',
				  'DIHVL','DISK','ELECT','EP','EPHVL','EPMTI','ET','FKT','FLUID','GS','GSA','GSB','GST','HAH','HM','HNMTI',
				  'HPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC',
				  'OINS','OISTC','OKGD','OMTI','ORPL','OSEAL','OTHER','OTHR','PA','PAINT','PS','PSKIT','PSTN','PT','RBR','RIMWH',
				  'RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SA','SAOIL','SD','SDN','SDN2','SDT','SE','SHOCK','SP2','SPGUI',
				  'SPOKE','STR','TAS','TDI','TR','VALVE','VV') and a.referensi='work_order' then a.tot_nsc ELSE 0 END) AS spart_wo,
					SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='sales' then a.tot_nsc ELSE 0 END) AS oil_tanpa_wo,
					SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='work_order' then a.tot_nsc ELSE 0 END) AS oil_wo
					from tr_h23_nsc a
					join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc 
					join ms_part c on c.id_part_int=b.id_part_int 
					join ms_dealer d on d.id_dealer = a.id_dealer 
					where left(a.created_at ,10)>='2022-06-02' and left(a.created_at,10)<='2022-06-02'
				  	group by a.id_dealer")->result_array();

		for($i=0; $i<count($data_part_oli); $i++){
			$id_dealer= $data_part_oli[$i]['id_dealer'];

			$mekanik = $this->db->query("SELECT count(case when b.id_karyawan_dealer !=null then 1 else 0 end) as mekanik
					from tr_h2_absen_mekanik a
					left join tr_h2_absen_mekanik_detail b on a.id_absen=b.id_absen 
					where left(a.created_at,10) ='2022-06-02' and b.aktif='1' and a.id_dealer='$id_dealer'
					group by a.id_dealer")->row_array();

			$pit = $this->db->query("SELECT count(case when a.id_pit != null then 1 else 0 end) as pit
					from ms_h2_pit a
					where a.id_dealer='$id_dealer' and a.active='1'
					group by a.id_dealer")->row_array();

			
			$salesIn = $this->db->query("SELECT SUM(CASE WHEN f.kelompok_part in('ACB','ACC','ACG','AH','AHM','BB','BBIST','BM1','BR','BRNG','BRNG2','BRNG3','BS','BT','CABLE','CB','CCKIT','CD','CDKGP','CDKIT','CH','CHAIN','COMP','COOL','CRKIT','DIHVL','DISK','ELECT','EP','EPHVL','EPMTI','ET','FKT','FLUID','GS','GSA','GSB','GST','HAH','HM','HNMTI','HPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC','OINS','OISTC','OKGD','OMTI','ORPL','OSEAL','OTHER','OTHR','PA','PAINT','PS','PSKIT','PSTN','PT','RBR','RIMWH','RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SA','SAOIL','SD','SDN','SDN2','SDT','SE','SHOCK','SP2','SPGUI','SPOKE','STR','TAS','TDI','TR','VALVE','VV') 
			THEN e.harga_setelah_diskon*e.qty_supply ELSE 0 END) as part,
					SUM(CASE WHEN f.kelompok_part in('GMO','OIL') then e.harga_setelah_diskon*e.qty_supply ELSE 0 END) as oli
					from tr_h3_md_packing_sheet a
					join tr_h3_md_picking_list b on a.id_picking_list=b.id_picking_list 
					join tr_h3_md_do_sales_order c on c.id_do_sales_order=b.id_ref
					left join tr_h3_md_do_sales_order_parts e on e.id_do_sales_order=c.id_do_sales_order 
					join ms_dealer d on d.id_dealer = b.id_dealer
					join ms_part f on f.id_part_int=e.id_part_int 
					where left(a.tgl_cetak_faktur ,10)=CURRENT_DATE() and b.id_dealer='$id_dealer'
					group by b.id_dealer")->row_array();

			$toj = $this->db->query("SELECT 
					SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
					SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
					SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
					SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
					SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
					SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
					SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
					SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
					SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
					SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
					SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
					SUM(CASE WHEN c.id_type='PUD' then 1 ELSE 0 end )as total_pud,
					SUM(CASE WHEN c.id_type='PL' then 1 ELSE 0 end )as total_pl,
					SUM(CASE WHEN c.id_type ='OTHER' then 1 ELSE 0 end )as total_other
					FROM ms_dealer a 
					JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
					JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
					LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
					JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
					WHERE b.status='closed' and left(b.closed_at,10)='2022-06-02' and b.id_dealer='$id_dealer'
					GROUP BY b.id_dealer")->row_array();

			$toj11 = $this->db->query("SELECT 
					SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end)as total_ass1,
					SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end)as total_ass2,
					SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end)as total_ass3,
					SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end)as total_ass4,
					SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end)as total_cs,
					SUM(CASE WHEN c.id_type in ('LS','OTHER','PL') then 1 ELSE 0 end)as total_ls_other_pl,
					SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end)as total_or,
					SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end)as total_lr,
					SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end)as total_hr,
					SUM(CASE WHEN c.id_type in ('C2','PUD') then 1 ELSE 0 end)as total_claim_pud
					FROM ms_dealer a 
					JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
					JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
					LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
					JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
					WHERE b.status='closed' and left(b.closed_at,10)='2022-06-02' and b.id_dealer='$id_dealer'
					GROUP BY b.id_dealer")->row_array();

			$ap = $this->db->query("SELECT  
					SUM(CASE WHEN a.kode ='PEOT' THEN 1 ELSE 0 END) AS pit_express_outdoor,
					SUM(CASE WHEN a.kode ='PEIN' THEN 1 ELSE 0 END) AS pit_express_indoor,
					SUM(CASE WHEN a.kode ='RM' THEN 1 ELSE 0 END) AS reminder,
					SUM(CASE WHEN a.kode in ('AE01','AE02','AE03') THEN 1 ELSE 0 END) AS ahass_event,
					SUM(CASE WHEN a.kode in ('SVAH','SVHC','SVJD','SVGC','SVPA','SVER') THEN 1 ELSE 0 END) AS service_visit,
					SUM(CASE WHEN a.kode ='SVPS' THEN 1 ELSE 0 END) AS pos_service
					from tr_h2_sa_form c 
					join tr_h2_wo_dealer b on c.id_sa_form=b.id_sa_form 
					join dms_ms_activity_promotion a on c.activity_promotion_id = a.id 
					where left(b.closed_at ,10)='2022-06-02' and b.status='closed' and b.id_dealer='$id_dealer'
					group by b.id_dealer")->row_array();

			$ac = $this->db->query("SELECT SUM(CASE WHEN a.kode='BS' THEN 1 ELSE 0 END) AS booking_service
					from tr_h2_sa_form c
					join tr_h2_wo_dealer b on c.id_sa_form=b.id_sa_form 
					join dms_ms_activity_capacity a on c.activity_capacity_id = a.id 
					where left(b.closed_at ,10)='2022-06-02' and b.status='closed' and b.id_dealer='$id_dealer'
					group by b.id_dealer");

			$jasaRev = $this->db->query("SELECT 
					SUM(CASE WHEN A.id_type in ('ASS1','ASS2','ASS3','ASS4') THEN B.harga ELSE 0 END) AS ass,
					SUM(CASE WHEN A.id_type ='ASS1' THEN B.harga ELSE 0 END) AS ass1,
					SUM(CASE WHEN A.id_type ='ASS2' THEN B.harga ELSE 0 END) AS ass2,
					SUM(CASE WHEN A.id_type ='ASS3' THEN B.harga ELSE 0 END) AS ass3,
					SUM(CASE WHEN A.id_type ='ASS4' THEN B.harga ELSE 0 END) AS ass4,
					SUM(CASE WHEN A.id_type ='C2' THEN B.harga ELSE 0 END) AS claim,
					SUM(CASE WHEN A.id_type ='LS' THEN B.harga ELSE 0 END) AS ls,
					SUM(CASE WHEN A.id_type ='HR' THEN B.harga ELSE 0 END) AS hr,
					SUM(CASE WHEN A.id_type ='CS' THEN B.harga ELSE 0 END) AS cs,
					SUM(CASE WHEN A.id_type ='LR' THEN B.harga ELSE 0 END) AS lr,
					SUM(CASE WHEN A.id_type ='OR+' THEN B.harga ELSE 0 END) AS or_plus,
					SUM(CASE WHEN A.id_type ='JR' THEN B.harga ELSE 0 END) AS jr,
					SUM(CASE WHEN A.id_type ='PUD' THEN B.harga ELSE 0 END) AS pud,
					SUM(CASE WHEN A.id_type = 'PL' THEN B.harga ELSE 0 END) AS pl,
					SUM(CASE WHEN A.id_type ='OTHER' THEN B.harga ELSE 0 END) AS other
					FROM tr_h2_wo_dealer_pekerjaan B 
					JOIN ms_h2_jasa A ON A.id_jasa=B.id_jasa 
					JOIN tr_h2_wo_dealer C ON B.id_work_order=C.id_work_order
					WHERE C.status='closed'
					AND left(C.closed_at,10)='2022-06-02' and C.id_dealer ='$id_dealer'
					group by C.id_dealer ")->row_array();

			$mdCode     =  	'E20';
			$dealerCode =  	$data_part_oli[$i]['kode_dealer_ahm'];
			$trans 		=	$tgl;
			$oilWO		=	$data_part_oli[$i]['oil_wo'];
			$oilNonWO	=	$data_part_oli[$i]['oil_tanpa_wo'];
			$hgpWO		=	$data_part_oli[$i]['spart_wo'];
			$hgpNonWO	=	$data_part_oli[$i]['spart_tanpa_wo'];
			$mechProd	=	$mekanik['mekanik'];
			$salesInPart=	$salesIn['part'];
			$salesInOil	=	$salesIn['oli'];
			$pit		=	$pit['pit'];
			$toj101		=	$toj['total_ass1'];
			$toj102		=	$toj['total_ass2'];
			$toj103		=	$toj['total_ass3'];
			$toj104		=	$toj['total_ass4'];
			$toj105		=	$toj['total_claim'];
			$toj106		=	$toj['total_cs'];
			$toj107		=	$toj['total_ls'];
			$toj108		=	$toj['total_or'];
			$toj109		=	$toj['total_lr'];
			$toj110		=	$toj['total_hr'];
			$toj111		=	$toj['total_pud'];
			$toj112		=	$toj['total_other'];
			$toj113		=	$toj['total_pl'];
			$toj114		=	$toj['total_jr'];

			$toj201		=	$toj11['total_ass1'];
			$toj202		=	$toj11['total_ass2'];
			$toj203		=	$toj11['total_ass3'];
			$toj204		=	$toj11['total_ass4'];
			$toj205		=	$toj11['total_claim_pud'];
			$toj206		=	$toj11['total_cs'];
			$toj207		=	$toj11['total_ls_other_pl'];
			$toj208		=	$toj11['total_or'];
			$toj209		=	$toj11['total_lr'];
			$toj210		=	$toj11['total_hr'];

			$peOutdoor	=	$ap['pit_express_outdoor'];
			$peIndoor	=	$ap['pit_express_indoor'];
			$reminder	=	$ap['reminder'];
			$ahassEvent	=	$ap['ahass_event'];
			$serviceVisit=	$ap['service_visit'];
			$posService	=	$ap['pos_service'];

			$bookingService	=	$ac['booking_service'];

			$ass1		=	$jasaRev['ass1'];
			$ass2		=	$jasaRev['ass2'];
			$ass3		=	$jasaRev['ass3'];
			$ass4		=	$jasaRev['ass4'];
			$claim		=	$jasaRev['claim'];
			$cs			=	$jasaRev['cs'];
			$ls			=	$jasaRev['ls'];
			$or_plus	=	$jasaRev['or_plus'];
			$lr			=	$jasaRev['lr'];
			$hr			=	$jasaRev['hr'];
			$pud		=	$jasaRev['pud'];
			$other		=	$jasaRev['other'];
			$pl			=	$jasaRev['pl'];
			$jr			=	$jasaRev['jr'];
			

			$row = $dom->createElement('row');

			$mdCode2    = $dom->createElement('mdCode', $mdCode); 
		  	$row->appendChild($mdCode2); 
			$dealerCode2= $dom->createElement('dealerCode', $dealerCode); 
		  	$row->appendChild($dealerCode2); 
			$trans2     = $dom->createElement('trans', $trans); 
		  	$row->appendChild($trans2);
			$salesInPart2= $dom->createElement('salesInPart', $salesInPart); 
			$row->appendChild($salesInPart2); 
			$salesInOil2= $dom->createElement('salesInOil', $salesInOil); 
			$row->appendChild($salesInOil2);  
			$hgpWO2    	= $dom->createElement('hgpWO', $hgpWO); 
		  	$row->appendChild($hgpWO2); 
			$hgpNonWO2  = $dom->createElement('hgpNonWO', $hgpNonWO); 
		  	$row->appendChild($hgpNonWO2); 
			$oilWO2  	= $dom->createElement('oilWO', $oilWO); 
			$row->appendChild($oilWO2); 
			$oilNonWO2	= $dom->createElement('oilNonWO', $oilNonWO); 
			$row->appendChild($oilNonWO2); 
			$mechProd2	= $dom->createElement('mechProd', $mechProd); 
			$row->appendChild($mechProd2); 	 
			$pit2		= $dom->createElement('pit', $pit); 
			$row->appendChild($pit2);
			
			$details = $dom->createElement('details');
			$details->setAttribute('detailId', 'sdueDetail');

			// 1:>
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '101'); 
					$dtl->appendChild($seq);
					$toj101_2	= $dom->createElement('amount', $toj101); 
					$dtl->appendChild($toj101_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '102'); 
					$dtl->appendChild($seq);
					$toj102_2	= $dom->createElement('amount', $toj102); 
					$dtl->appendChild($toj102_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '103'); 
					$dtl->appendChild($seq);
					$toj103_2	= $dom->createElement('amount', $toj103); 
					$dtl->appendChild($toj103_2);
				$details->appendChild($dtl);
				
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '104'); 
					$dtl->appendChild($seq);
					$toj104_2	= $dom->createElement('amount', $toj104); 
					$dtl->appendChild($toj104_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '105'); 
					$dtl->appendChild($seq);
					$toj105_2	= $dom->createElement('amount', $toj105); 
					$dtl->appendChild($toj105_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '106'); 
					$dtl->appendChild($seq);
					$toj106_2	= $dom->createElement('amount', $toj106); 
					$dtl->appendChild($toj106_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '107'); 
					$dtl->appendChild($seq);
					$toj107_2	= $dom->createElement('amount', $toj107); 
					$dtl->appendChild($toj107_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '108'); 
					$dtl->appendChild($seq);
					$toj108_2	= $dom->createElement('amount', $toj108); 
					$dtl->appendChild($toj108_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '109'); 
					$dtl->appendChild($seq);
					$toj109_2	= $dom->createElement('amount', $toj109); 
					$dtl->appendChild($toj109_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '110'); 
					$dtl->appendChild($seq);
					$toj110_2	= $dom->createElement('amount', $toj110); 
					$dtl->appendChild($toj110_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '111'); 
					$dtl->appendChild($seq);
					$toj111_2	= $dom->createElement('amount', $toj111); 
					$dtl->appendChild($toj111_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '112'); 
					$dtl->appendChild($seq);
					$toj112_2	= $dom->createElement('amount', $toj112); 
					$dtl->appendChild($toj112_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '113'); 
					$dtl->appendChild($seq);
					$toj113_2	= $dom->createElement('amount', $toj113); 
					$dtl->appendChild($toj113_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '114'); 
					$dtl->appendChild($seq);
					$toj114_2	= $dom->createElement('amount', $toj114); 
					$dtl->appendChild($toj114_2);
				$details->appendChild($dtl);
			// Batas ToJ 1:>
			
			// ToJ 1:1
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '201'); 
					$dtl->appendChild($seq);
					$toj201_2	= $dom->createElement('amount', $toj201); 
					$dtl->appendChild($toj201_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '202'); 
					$dtl->appendChild($seq);
					$toj202_2	= $dom->createElement('amount', $toj202); 
					$dtl->appendChild($toj202_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '203'); 
					$dtl->appendChild($seq);
					$toj203_2	= $dom->createElement('amount', $toj203); 
					$dtl->appendChild($toj203_2);
				$details->appendChild($dtl);
				
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '204'); 
					$dtl->appendChild($seq);
					$toj204_2	= $dom->createElement('amount', $toj204); 
					$dtl->appendChild($toj204_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '205'); 
					$dtl->appendChild($seq);
					$toj205_2	= $dom->createElement('amount', $toj205); 
					$dtl->appendChild($toj205_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '206'); 
					$dtl->appendChild($seq);
					$toj206_2	= $dom->createElement('amount', $toj206); 
					$dtl->appendChild($toj206_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '207'); 
					$dtl->appendChild($seq);
					$toj207_2	= $dom->createElement('amount', $toj207); 
					$dtl->appendChild($toj207_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '208'); 
					$dtl->appendChild($seq);
					$toj208_2	= $dom->createElement('amount', $toj208); 
					$dtl->appendChild($toj208_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '209'); 
					$dtl->appendChild($seq);
					$toj209_2	= $dom->createElement('amount', $toj209); 
					$dtl->appendChild($toj209_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '210'); 
					$dtl->appendChild($seq);
					$toj210_2	= $dom->createElement('amount', $toj210); 
					$dtl->appendChild($toj210_2);
				$details->appendChild($dtl);
			// Batas ToJ 1:1

			// Actitity Promotion
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '301'); 
					$dtl->appendChild($seq);
					$peOutdoor2	= $dom->createElement('amount', $peOutdoor); 
					$dtl->appendChild($peOutdoor2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '302'); 
					$dtl->appendChild($seq);
					$peIndoor2	= $dom->createElement('amount', $peIndoor); 
					$dtl->appendChild($peIndoor2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '303'); 
					$dtl->appendChild($seq);
					$reminder2	= $dom->createElement('amount', $reminder); 
					$dtl->appendChild($reminder2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '304'); 
					$dtl->appendChild($seq);
					$ahassEvent2	= $dom->createElement('amount', $ahassEvent); 
					$dtl->appendChild($ahassEvent2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '305'); 
					$dtl->appendChild($seq);
					$serviceVisit2	= $dom->createElement('amount', $serviceVisit); 
					$dtl->appendChild($serviceVisit2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '306'); 
					$dtl->appendChild($seq);
					$posService2	= $dom->createElement('amount', $posService); 
					$dtl->appendChild($posService2);
				$details->appendChild($dtl);

			// Batas Actitity Promotion

			// Revenue TOJ
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '401'); 
					$dtl->appendChild($seq);
					$ass1_2	= $dom->createElement('amount', $ass1); 
					$dtl->appendChild($ass1_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '402'); 
					$dtl->appendChild($seq);
					$ass2_2	= $dom->createElement('amount', $ass2); 
					$dtl->appendChild($ass2_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '403'); 
					$dtl->appendChild($seq);
					$ass3_2	= $dom->createElement('amount', $ass3); 
					$dtl->appendChild($ass3_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '404'); 
					$dtl->appendChild($seq);
					$ass4_2	= $dom->createElement('amount', $ass4); 
					$dtl->appendChild($ass4_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '405'); 
					$dtl->appendChild($seq);
					$claim_2	= $dom->createElement('amount', $claim); 
					$dtl->appendChild($claim_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '406'); 
					$dtl->appendChild($seq);
					$cs_2	= $dom->createElement('amount', $cs); 
					$dtl->appendChild($cs_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '407'); 
					$dtl->appendChild($seq);
					$ls_2	= $dom->createElement('amount', $ls); 
					$dtl->appendChild($ls_2);
				$details->appendChild($dtl);


				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '408'); 
					$dtl->appendChild($seq);
					$or_plus2	= $dom->createElement('amount', $or_plus); 
					$dtl->appendChild($or_plus2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '409'); 
					$dtl->appendChild($seq);
					$lr_2	= $dom->createElement('amount', $lr); 
					$dtl->appendChild($lr_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '410'); 
					$dtl->appendChild($seq);
					$hr_2	= $dom->createElement('amount', $hr); 
					$dtl->appendChild($hr_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '411'); 
					$dtl->appendChild($seq);
					$pud_2	= $dom->createElement('amount', $pud); 
					$dtl->appendChild($pud_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '412'); 
					$dtl->appendChild($seq);
					$other_2	= $dom->createElement('amount', $other); 
					$dtl->appendChild($other_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '413'); 
					$dtl->appendChild($seq);
					$pl_2	= $dom->createElement('amount', $pl); 
					$dtl->appendChild($pl_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '414'); 
					$dtl->appendChild($seq);
					$jr_2	= $dom->createElement('amount', $jr); 
					$dtl->appendChild($jr_2);
				$details->appendChild($dtl);
			// Batas Revenue ToJ

			//Activity Capacity
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ACAP'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '501'); 
					$dtl->appendChild($seq);
					$bs	= $dom->createElement('amount', $bookingService); 
					$dtl->appendChild($bs);
				$details->appendChild($dtl);
			//Batas Activity Capacity

			$row->appendChild($details);
			$root->appendChild($row);
		} 
		$dom->appendChild($root); 
		$dom->save($filePath); 
	}
}
?>