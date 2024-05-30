<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_data_part_insight extends CI_Controller{
	
	var $folder ="h3";
	var $page   ="h3_md_data_part_insight";	
	var $isi    ="Data Part Insight";	
	var $title  ="Data Part Insight";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
        $this->load->model('h3_md_data_part_insight_model','data_part');	
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
		$data['dealer'] = $this->data_part->getDataDealer();
		$this->template($data);		    	    
	}	

	public function downloadExcel(){
		$data['id_dealer'] = $id_dealer	= $this->input->post('id_dealer');
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['type']      = $type	    = $this->input->post('type');

		if($type=='ps_channel'){
			$data['ps_channel'] = $this->data_part->ps_channel($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_channel_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_channel_csv",$data);
            }
		}elseif($type=='ps_service'){
			$data['ps_service'] = $this->data_part->ps_service($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_service_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_service_csv",$data);
            }
	    }elseif($type=='ps_jenis_kelompok'){
			$data['ps_jenis_kelompok'] = $this->data_part->ps_jenis_kelompok($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_jenis_kelompok_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_jenis_kelompok_csv",$data);
            }
	    }elseif($type=='ps_tobpm'){
			$data['ps_tobpm'] = $this->data_part->ps_tobpm($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_tobpm_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_tobpm_csv",$data);
            }
	    }elseif($type=='ps_alert_growth'){
			$data['ps_alert_growth'] = $this->data_part->ps_alert_growth($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_alert_growth_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_alert_growth_csv",$data);
            }
	    }elseif($type=='ps_target_achievement'){
			$data['ps_target_achievement'] = $this->data_part->ps_target_achievement($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_target_achievement_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_target_achievement_csv",$data);
            }
	    }elseif($type=='ps_grouping_part'){
			$data['ps_grouping_part'] = $this->data_part->ps_grouping_part($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_grouping_part_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_grouping_part_csv",$data);
            }
	    }elseif($type=='ps_avg_grouping_part'){
			$data['ps_avg_grouping_part'] = $this->data_part->ps_avg_grouping_part($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_avg_grouping_part_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_avg_grouping_part_csv",$data);
            }
	    }elseif($type=='ps_9_segment'){
			$data['ps_9_segment'] = $this->data_part->ps_9_segment($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_9_segment_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_9_segment_csv",$data);
            }
	    }elseif($type=='ps_details'){
			$data['ps_details'] = $this->data_part->ps_details($id_dealer,$start_date,$end_date);
			$data_fulfillment_dealer = array();
			$data_customer = array();
			foreach($data['ps_details']->result() as $row){
					if($row->id_customer != '' or $row->id_customer != NULL){
						$customer_query = $this->db->query("SELECT ms.segment, DATE_FORMAT(mtk.tgl_awal,'%Y') as production_year, mtk.deskripsi_ahm
						FROM ms_customer_h23 mch 
						JOIN ms_tipe_kendaraan mtk on mch.id_tipe_kendaraan=mtk.id_tipe_kendaraan 
						JOIN ms_segment ms on ms.id_segment=mtk.id_segment 
						WHERE mch.id_customer='$row->id_customer'
						")->row();
						$data_customer[] = $customer_query;
					}else{
						$customer_query = $this->db->query("SELECT '' as segment, '' as production_year, '' as deskripsi_ahm
						")->row();
						$data_customer[] = $customer_query;
					}
			}
			$data['data_customer'] = $data_customer;
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_details_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_details_csv",$data);
            }
	    }elseif($type=='sl_penjualan_dealer'){
			$data['sl_penjualan_dealer'] = $this->data_part->sl_penjualan_dealer($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_sl_penjualan_dealer_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_sl_penjualan_dealer_csv",$data);
            }
	    }elseif($type=='sl_penjualan_grouping_parts'){
			$data['sl_penjualan_grouping_parts'] = $this->data_part->sl_penjualan_grouping_parts($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_sl_penjualan_grouping_parts_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_sl_penjualan_grouping_parts_csv",$data);
            }
	    }elseif($type=='sl_details'){
			$data['sl_details'] = $this->data_part->sl_details($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_sl_details_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_sl_details_csv",$data);
            }
	    }elseif($type=='sl_dealer'){
			$data['sl_dealer'] = $this->data_part->sl_dealer($id_dealer,$start_date,$end_date);
			// foreach($data['sl_dealer']->result() as $isi){
			// 	$start_date_interval = date('Y-m-d', strtotime($end_date . ' - 1 day'));
			// 	$sl_dealer2 = $this->db->query("SELECT IFNULL(SUM(CASE WHEN nscp.tipe_diskon='Percentage' 
			// 		then ((nscp.harga_beli*nscp.qty)-(nscp.harga_beli*nscp.diskon_value/100))
			// 		WHEN nscp.tipe_diskon='Value' then ((nscp.harga_beli*nscp.qty)-nscp.diskon_value) ELSE nscp.harga_beli*nscp.qty END),0) as total_penjualan
			// 		FROM tr_h23_nsc nsc
			// 		JOIN tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc 
			// 		-- where nsc.created_at >= date_sub('$end_date 00:00:00', interval 2 month) and nsc.created_at<='$end_date 23:59:59'
			// 		where nsc.created_at >= '$start_date_interval 00:00:00' and nsc.created_at<='$end_date 23:59:59'
			// 		and nsc.id_dealer = $isi->id_dealer and nscp.id_part_int='$isi->id_part_int'")->row();
			// }
			// $data['$sl_dealer2'] = $sl_dealer2;
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_sl_dealer_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_sl_dealer_csv",$data);
            }
	    }elseif($type=='sl_grouping_parts'){
			$data['sl_grouping_parts'] = $this->data_part->sl_grouping_parts($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_sl_grouping_parts_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_sl_grouping_parts_csv",$data);
            }
	    }elseif($type=='hlo_service_rate'){
			$data['hlo_service_rate'] = $this->data_part->hlo_service_rate($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_service_rate_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_service_rate_csv",$data);
            }
	    }elseif($type=='hlo_kota'){
			$data['hlo_kota'] = $this->data_part->hlo_kota($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_kota_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_kota_csv",$data);
            }
	    }elseif($type=='hlo_dealer'){
			$data['hlo_dealer'] = $this->data_part->hlo_dealer($id_dealer,$start_date,$end_date);
			$data_fulfillment_dealer = array();
			foreach($data['hlo_dealer']->result() as $row){
				$fulfillment_query = $this->db->query("SELECT SUM(qty_fulfillment) as qty_fulfil
				, DATE_FORMAT(created_at,'%d-%m-%Y') as tgl_pemenuhan, DATEDIFF(created_at, '$row->tgl_order') as selisih
				FROM tr_h3_dealer_order_fulfillment dof
				WHERE po_id_int='$row->id' and id_part_int='$row->id_part_int' and qty_fulfillment>0
				-- AND created_at = (SELECT MAX(created_at) FROM tr_h3_dealer_order_fulfillment WHERE po_id_int='$row->id' and id_part_int='$row->id_part_int')
				LIMIT 1
				")->row();

				$data_fulfillment_dealer[] = $fulfillment_query;
			}
			$data['data_fulfillment_dealer'] = $data_fulfillment_dealer;

			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_dealer_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_dealer_csv",$data);
            }
	    }elseif($type=='hlo_status_order'){
			$data['hlo_status_order_fulfilled'] = $this->data_part->hlo_status_order_fulfilled($id_dealer,$start_date,$end_date);
			$data['hlo_status_order_all'] = $this->data_part->hlo_status_order_all($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_status_order_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_status_order_csv",$data);
            }
	    }elseif($type=='hlo_series'){
			$data['hlo_series'] = $this->data_part->hlo_series($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_series_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_series_csv",$data);
            }
	    }elseif($type=='hlo_production_year'){
			$data['hlo_production_year'] = $this->data_part->hlo_production_year($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_production_year_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_production_year_csv",$data);
            }
	    }elseif($type=='hlo_grouping_parts'){
			$data['hlo_grouping_parts'] = $this->data_part->hlo_grouping_parts($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_grouping_parts_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_grouping_parts_csv",$data);
            }
	    }elseif($type=='hlo_lead_time_fulfillment'){
			$data['hlo_lead_time_fulfillment'] = $this->data_part->hlo_lead_time_fulfillment($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_lead_time_fulfillment_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_lead_time_fulfillment_csv",$data);
            }
	    }elseif($type=='hlo_outstanding'){
			$data['hlo_outstanding_belum_dipenuhi_semua'] = $this->data_part->hlo_outstanding_belum_dipenuhi_semua($id_dealer,$start_date,$end_date);
			$data['hlo_outstanding_belum_dipenuhi'] = $this->data_part->hlo_outstanding_belum_dipenuhi($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_outstanding_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_outstanding_csv",$data);
            }
	    }elseif($type=='hlo_outstanding_details'){
			$data['hlo_outstanding_details'] = $this->data_part->hlo_outstanding_details($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_outstanding_details_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_outstanding_details_csv",$data);
            }
	    }elseif($type=='dealer'){
			$data['getDataDealer'] = $this->data_part->getDataDealer($start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_dealer_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_dealer_csv",$data);
            }
	    }elseif($type=='grouping_parts'){
			$data['grouping_parts'] = $this->data_part->grouping_parts();
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_grouping_parts_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_grouping_parts_csv",$data);
            }
	    }elseif($type=='service'){
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_service_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_service_csv",$data);
            }
	    }elseif($type=='hlo_fulfilled'){
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_fulfilled_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_fulfilled_csv",$data);
            }
	    }elseif($type=='ps_production_year'){
			$data['ps_production_year'] = $this->data_part->ps_production_year($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_ps_production_year_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_ps_production_year_csv",$data);
            }
	    }elseif($type=='hlo_production_year'){
			$data['hlo_production_year'] = $this->data_part->hlo_production_year($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_hlo_production_year_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_hlo_production_year_csv",$data);
            }
	    }elseif($type=='tanggal_bulan'){
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_master_tanggal_bulan_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_master_tanggal_bulan_csv",$data);
            }
	    }elseif($type=='ms_target'){
			$data['getDataDealer'] = $this->data_part->getDataDealer($start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/temp_part_insight_target_sales_out_stock_level_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("h3/laporan/temp_part_insight_target_sales_out_stock_level_csv",$data);
            }
	    }

        
    }
}
?>