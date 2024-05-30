<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Monitoring_utilisasi extends CI_Controller {

	

	var $folder =   "h1/laporan";

	var $page		="monitoring_utilisasi";	

	var $isi		="laporan_1";	

	var $title  =   "Monitoring Utilisasi H1";



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

		$this->load->library('pdf');		


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

		$data['isi']    = $this->isi;		

		$data['title']	= $this->title;															

		$data['set']		= "view";

		$this->template($data);		    	    

	}		

	
	public function download(){
		$start_date = $this->input->post('started');
		$end_date = $this->input->post('ended');

		if($start_date =='' and $end_date==''){
			$start_date = date('Y-m-01');
			$end_date = date('Y-m-t');
		}

		// $data['list_data'] = $this->m_lap->getDataUtilisasi_Prospek($start_date, $end_date);
		if($_POST['generate']=='export_ahm'){
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$data['list_dealer'] = $this->db->query("Select kode_dealer_md, kode_dealer_ahm, nama_dealer from ms_dealer where h1=1 and active =1 and pos !='ya' order by nama_dealer asc")->result();
			$this->load->view('h1/laporan/laporan_utilisasih1_sc',$data);
		}else if($_POST['generate']=='export_md'){
			echo 'Under Development';die;	
			//$this->load->view('h1/report/template/temp_laporan_bbn_csv',$data);
		}else if($_POST['generate']=='export_dgi_monitoring') {

			// $data['start_date'] = $this->input->post('started');
			// $data['end_date']   = $this->input->post('ended');

			// if ($start_date == '' and $end_date == '') {
			// 	$start_date = date('Y-m-01');
			// 	$end_date = date('Y-m-t');
			// 	$data['start_date'] = $start_date;
			// 	$data['end_date']   = $end_date;
			// }
			
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			  
			$data['dealer']  = $this->db->query("select kode_dealer_md, nama_dealer from ms_dealer where kode_dealer_md in ('13382', '13384',  '18494','18493', '07628', '12142', '12143', '13387', '13867', '13388','13759', '12144', '12598','13381','12797','13621') ORDER BY nama_dealer asc")->result();

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
		}else if($_POST['generate']=='export_prsp_spk'){
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$data['temp_data'] = $this->db->query("
				select kode_dealer_md, kode_dealer_ahm, nama_dealer, sum(total_prospek) as total_prospek, sum(total_spk) as total_spk 
				from (
					select b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer, count(a.id_prospek) as total_prospek, 0 as total_spk, 'prospek' as tipe
					from tr_prospek a
					join ms_dealer b on a.id_dealer = b.id_dealer
					where a.created_at >='$start_date' and a.created_at <'$end_date 23:59:59'
					group by b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer
					union
					select b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer, count(a.id_prospek_gc) as total_prospek, 0 as total_spk, 'prospek' as tipe
					from tr_prospek_gc a
					join ms_dealer b on a.id_dealer = b.id_dealer
					where a.created_at >='$start_date' and a.created_at <'$end_date 23:59:59'
					group by b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer
					union
					select b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer, 0 as total_prospek, count(a.no_spk) as total_spk, 'spk' as tipe
					from tr_spk a join ms_dealer b on a.id_dealer =b.id_dealer 
					where a.created_at >='$start_date' and a.created_at <'$end_date 23:59:59' and a.status_spk in ('approved','close')
					group by b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer
					union
					select b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer, 0 as total_prospek, count(a.no_spk_gc) as total_spk, 'spk' as tipe
					from tr_spk_gc a join ms_dealer b on a.id_dealer =b.id_dealer 
					where a.created_at >='$start_date' and a.created_at <'$end_date 23:59:59' and a.status in ('approved')
					group by b.kode_dealer_md, b.kode_dealer_ahm, b.nama_dealer
				)x
				group by kode_dealer_md, kode_dealer_ahm, nama_dealer
			")->result();

			$this->load->view('h1/laporan/prospek_spk_nms', $data);
		}else if($_POST['generate']=='export_util_cons'){
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['dealer']  = $this->db->query("select id_dealer, kode_dealer_md, nama_dealer from ms_dealer where active = 1 and h1= 1 and pos!='ya' ORDER BY kode_dealer_ahm asc")->result();
			
			$UINB = array();
			$PRSP = array();
			$SPK = array();
			$INV = array();
			$LSNG = array();
			$BAST = array();
			$DOCH = array();

			foreach($data['dealer'] as $rows){
				$unitInbound = $this->db->query("select count(a.id_penerimaan_unit_dealer) as jmlh				
				from tr_penerimaan_unit_dealer a 
				join tr_do_po b on a.no_do =b.no_do
				join ms_dealer c on a.id_dealer = c.id_dealer
				where date(a.created_at) BETWEEN '$start_date' and '$end_date' and a.id_goods_receipt is not null and id_goods_receipt !='' and a.status = 'close' and a.id_dealer ='$rows->id_dealer'
				order by a.created_at asc");

				$prospect = $this->db->query("select
					count(a.id_dealer) as jmlh
				from
					tr_prospek a
				join ms_dealer b on
					a.id_dealer = b.id_dealer
				where
					date(a.created_at) BETWEEN '$start_date' and '$end_date'
					and a.id_list_appointment is not null
					and a.id_list_appointment != ''
					and b.id_dealer='$rows->id_dealer'
					union 
					select
						count(gc.id_dealer) as jmlh
					from
						tr_prospek_gc gc
					join ms_dealer b on
						gc.id_dealer = b.id_dealer
					where
						date(gc.created_at) BETWEEN '$start_date' and '$end_date'
						and b.id_dealer='$rows->id_dealer'");

				$dealingProcess = $this->db->query("select count(a.id_dealer) as jmlh
				from
					tr_spk a
				join tr_prospek b on
					a.id_customer = b.id_customer
				join ms_dealer c on
					a.id_dealer = c.id_dealer
				where
					date(a.created_at)  BETWEEN '$start_date' and '$end_date'
					and a.no_spk is not null
					and a.no_spk != ''
					and (a.status_spk ='approved' or a.status_spk = 'close')
					and a.id_dealer='$rows->id_dealer'
				
				union 
				select
					count(c.id_dealer) as jmlh
				from
					tr_spk_gc gca
				join tr_prospek_gc gcb on
					gca.id_prospek_gc = gcb.id_prospek_gc
				join ms_dealer c on
					gca.id_dealer = c.id_dealer
				where
					date(gca.created_at) BETWEEN '$start_date' and '$end_date'
					and gca.no_spk_gc is not null
					and gca.no_spk_gc != ''
					and c.id_dealer='$rows->id_dealer'
					and gca.status = 'approved'
				");

				$billingProcess = $this->db->query("select
					count(a.id_dealer) as jmlh
				from
					tr_sales_order a
				join ms_dealer b on
					a.id_dealer = b.id_dealer
				where
					date(a.created_at) BETWEEN '$start_date' and '$end_date'
					and a.no_invoice is not null
					and a.no_invoice != ''
					and a.id_dealer='$rows->id_dealer'
				union 
				select
				count(b.id_dealer) as jmlh
				from
					tr_sales_order_gc gca
				join ms_dealer b on
					gca.id_dealer = b.id_dealer
				where
					date(gca.created_at) BETWEEN '$start_date' and '$end_date'
					and gca.no_invoice is not null
					and gca.no_invoice != '' 
					and b.id_dealer='$rows->id_dealer'
				");

				$handleLeasing = $this->db->query("select count(a.id_dealer) as jmlh
				from
					tr_order_survey a
				join ms_dealer b on
					a.id_dealer = b.id_dealer
				join tr_spk c on
					a.no_spk = c.no_spk
				where
					date(a.created_at) BETWEEN '$start_date' and '$end_date'
					and a.no_order_survey is not null
					and a.no_order_survey != ''
					and c.status_survey = 'approved'
					and a.id_dealer='$rows->id_dealer'
				
				UNION select
					count(b.id_dealer) as jmlh
				from
					tr_order_survey_gc gca
				join ms_dealer b on
					gca.id_dealer = b.id_dealer
				join tr_spk_gc gcc on
					gca.no_spk_gc = gcc.no_spk_gc
				where
					date(gca.created_at) BETWEEN '$start_date' and '$end_date'
					and gca.no_order_survey_gc is not null
					and gca.no_order_survey_gc != ''
						and b.id_dealer='$rows->id_dealer'
					and gcc.status_survey = 'approved' ");

					
				$delivery = $this->db->query("select count(a.id_dealer) as jmlh 
				from tr_sales_order a
				join ms_dealer b on a.id_dealer = b.id_dealer
				where date(a.created_at) BETWEEN '$start_date' and '$end_date' and a.delivery_document_id is not null and a.delivery_document_id !='' 
				and a.id_dealer ='$rows->id_dealer'
				union
				select count(c.id_dealer) as jmlh 
				from tr_sales_order_gc_nosin a
				join tr_sales_order_gc c on a.id_sales_order_gc = c.id_sales_order_gc 
				join ms_dealer b on c.id_dealer = b.id_dealer
				where date(c.created_at) BETWEEN '$start_date' and '$end_date' and a.delivery_document_id is not null and a.delivery_document_id !=''
				and c.id_dealer ='$rows->id_dealer'
				");
				
				$documentHandling = $this->db->query("select
					count(distinct(c.id_sales_order)) as jmlh
				from
					tr_faktur_stnk_detail c
				left join tr_tandaterima_stnk_konsumen_detail b on
					c.no_mesin = b.no_mesin
				left join tr_tandaterima_stnk_konsumen d on
					b.kd_stnk_konsumen = d.kd_stnk_konsumen
				join ms_dealer a on
					a.id_dealer = d.id_dealer
				where
						date(d.tgl_terima_stnk) BETWEEN '$start_date' and '$end_date' and d.jenis_cetak = 'stnk'
						and c.id_sales_order is not null
						and c.id_sales_order != '' and d.tgl_terima_stnk is not NULL and d.tgl_terima_stnk !=''
						and a.id_dealer ='$rows->id_dealer'
				");
		
						
				$UINB[$rows->id_dealer] =  $unitInbound->row()->jmlh;
				$PRSP[$rows->id_dealer] =  $prospect->row()->jmlh;
				$SPK[$rows->id_dealer] =  $dealingProcess->row()->jmlh;
				$INV[$rows->id_dealer] =  $billingProcess->row()->jmlh;
				$LSNG[$rows->id_dealer] =  $handleLeasing->row()->jmlh;
				$BAST[$rows->id_dealer] =  $delivery->row()->jmlh;
				$DOCH[$rows->id_dealer] =  $documentHandling->row()->jmlh;

			}

			$data['penerimaan_unit'] = $UINB;
			$data['prospek'] = $PRSP;
			$data['spk'] = $SPK;
			$data['billing'] = $INV;
			$data['leasing'] = $LSNG;
			$data['delivery'] = $BAST;
			$data['document'] = $DOCH;

			
			
			/*
			$prospect_apps = $this->db->query("select
					count(1) as jmlh
				from
					tr_prospek a
				join ms_dealer b on
					a.id_dealer = b.id_dealer
				where
					date(a.created_at) BETWEEN '$start' and '$end'
					and b.id_dealer='$rows->id_dealer' and input_from = 'sc'
				");		
			
			*/

			$this->load->view('h1/laporan/utilisasi_konsistensi_h1', $data);
		}else if($_POST['generate']=='export_util_sl'){
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['dealer']  = $this->db->query("select id_dealer, kode_dealer_ahm, nama_dealer from ms_dealer where active = 1 and h1= 1 and pos!='ya' ORDER BY kode_dealer_ahm asc")->result();
			
			$list = array();

			foreach($data['dealer'] as $rows){
				$get_shipping_list_md = $this->db->query("
					select kode_dealer_ahm,  count(tgl) as jmlh_util , GROUP_CONCAT(tgl) as list_tgl_sl
					from(
						select a.id_dealer, b.kode_dealer_ahm, day(tgl_surat) as tgl
						from tr_surat_jalan a
						join ms_dealer b on a.id_dealer  = b.id_dealer 
						where date(a.created_at) between '$start_date' and '$end_date' and a.status ='close' and b.kode_dealer_ahm ='$rows->kode_dealer_ahm'
						group by tgl_surat 
					)z
				");

				if($get_shipping_list_md->num_rows() > 0){
					foreach($get_shipping_list_md->result() as $isi){
						$list[$isi->kode_dealer_ahm] = $isi->list_tgl_sl;
					}
				}
				$data['list_data'] = $list;
			}

			$this->load->view('h1/laporan/utilisasi_sl_h1', $data);
		}
	}


}