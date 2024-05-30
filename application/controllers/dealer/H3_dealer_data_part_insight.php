<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_data_part_insight extends CI_Controller{
	
	var $folder ="dealer/";
	var $page   ="h3_dealer_data_part_insight";	
	var $isi    ="Data Part Insight";	
	var $title  ="Data Part Insight";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
        $this->load->model('h3_dealer_data_part_insight_model','data_part');	
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
		$this->template($data);		    	    
	}	

	public function downloadExcel(){
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['type']      = $type	    = $this->input->post('type');
		$data['id_dealer'] = $id_dealer = $this->m_admin->cari_dealer();

		if($type=='ps_channel'){
			$data['ps_channel'] = $this->data_part->ps_channel($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("dealer/laporan/temp_part_insight_ps_channel_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("dealer/laporan/temp_part_insight_ps_channel_csv",$data);
            }
	    }elseif($type=='ps_avg_grouping_part'){
			$data['ps_avg_grouping_part'] = $this->data_part->ps_avg_grouping_part($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("dealer/laporan/temp_part_insight_ps_avg_grouping_part_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("dealer/laporan/temp_part_insight_ps_avg_grouping_part_csv",$data);
            }
	    }elseif($type=='ps_details'){
			$data['ps_details'] = $this->data_part->ps_details($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("dealer/laporan/temp_part_insight_ps_details_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("dealer/laporan/temp_part_insight_ps_details_csv",$data);
            }
	    }elseif($type=='sl_details'){
			$data['sl_details'] = $this->data_part->sl_details($id_dealer,$start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("dealer/laporan/temp_part_insight_sl_details_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("dealer/laporan/temp_part_insight_sl_details_csv",$data);
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
				$this->load->view("dealer/laporan/temp_part_insight_sl_dealer_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("dealer/laporan/temp_part_insight_sl_dealer_csv",$data);
            }
	    }elseif($type=='hlo_dealer'){
			$data['hlo_dealer'] = $this->data_part->hlo_dealer($id_dealer,$id_dealer,$start_date,$end_date);
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
				$this->load->view("dealer/laporan/temp_part_insight_hlo_dealer_excel",$data);
			}elseif($_POST['process']=='csv'){
                $this->load->view("dealer/laporan/temp_part_insight_hlo_dealer_csv",$data);
            }
		}
    }
}
?>