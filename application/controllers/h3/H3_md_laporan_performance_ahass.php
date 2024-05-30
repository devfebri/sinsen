<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_laporan_performance_ahass extends CI_Controller{
	
	var $folder ="h3";
	var $page   ="h3_md_laporan_performance_ahass";	
	var $isi    ="Laporan Performance AHASS";	
	var $title  ="Laporan Performance AHASS";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('h3_md_laporan_performance_ahass_model','laporan_performance');	
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
		$data['dealer'] = $this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer WHERE active=1 ORDER BY nama_dealer ASC")->result();
		$this->template($data);		    	    
	}	

	public function downloadReport()
	{
		$data['type']      = $type	    = $this->input->post('type');
		$data['id_dealer'] = $id_dealer	= $this->input->post('dealer');
		$data['start_date'] = $start_date = $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['dealer'] = $this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer WHERE active=1 ORDER BY nama_dealer ASC")->result();
		if ($type == 'laporan_performance_ahass') {
			$data['report'] = $report = $this->laporan_performance->laporan_performance_ahass($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_performance_ahass", $data);
			}
		}elseif($type == 'laporan_penjualan_part_selling'){
			$data['laporan_penjualan_part_selling'] = $laporan_penjualan_part_selling = $this->laporan_performance->laporan_penjualan_part_selling($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_penjualan_part_selling", $data);
			}
		}elseif($type == 'laporan_penjualan_oil_amount'){
			$data['laporan_penjualan_oil_amount'] = $laporan_penjualan_oil_amount = $this->laporan_performance->laporan_penjualan_oil_amount($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_penjualan_oil_amount", $data);
			}
		}elseif($type == 'laporan_penjualan_oil_botol'){
			$data['laporan_penjualan_oil_botol'] = $laporan_penjualan_oil_botol = $this->laporan_performance->laporan_penjualan_oil_botol($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_penjualan_oil_botol", $data);
			}
		}elseif($type == 'laporan_performance_sales_parts'){
			$data['laporan_performance_sales_parts'] = $laporan_performance_sales_parts = $this->laporan_performance->laporan_performance_sales_parts($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_performance_sales_parts", $data);
			}
		}elseif($type == 'laporan_sales_by_channel'){
			$data['laporan_sales_by_channel_cost_price_oil_m'] = $laporan_sales_by_channel_cost_price_oil_m = $this->laporan_performance->laporan_sales_by_channel_cost_price_oil_m($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_cost_price_oil_m1'] = $laporan_sales_by_channel_cost_price_oil_m1 = $this->laporan_performance->laporan_sales_by_channel_cost_price_oil_m1($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_selling_price_oil_m'] = $laporan_sales_by_channel_selling_price_oil_m = $this->laporan_performance->laporan_sales_by_channel_selling_price_oil_m($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_selling_price_oil_m1'] = $laporan_sales_by_channel_selling_price_oil_m1 = $this->laporan_performance->laporan_sales_by_channel_selling_price_oil_m1($id_dealer, $start_date, $end_date, $type);

			$data['laporan_sales_by_channel_cost_price_part_m'] = $laporan_sales_by_channel_cost_price_part_m = $this->laporan_performance->laporan_sales_by_channel_cost_price_part_m($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_cost_price_part_m1'] = $laporan_sales_by_channel_cost_price_part_m1 = $this->laporan_performance->laporan_sales_by_channel_cost_price_part_m1($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_selling_price_part_m'] = $laporan_sales_by_channel_selling_price_part_m = $this->laporan_performance->laporan_sales_by_channel_selling_price_part_m($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_selling_price_part_m1'] = $laporan_sales_by_channel_selling_price_part_m1 = $this->laporan_performance->laporan_sales_by_channel_selling_price_part_m1($id_dealer, $start_date, $end_date, $type);


			$data['laporan_sales_by_channel_target_part_m'] = $laporan_sales_by_channel_target_part_m = $this->laporan_performance->laporan_sales_by_channel_target_part_m($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_target_part_m1'] = $laporan_sales_by_channel_target_part_m1 = $this->laporan_performance->laporan_sales_by_channel_target_part_m1($id_dealer, $start_date, $end_date, $type);

			$data['laporan_sales_by_channel_target_oli_m'] = $laporan_sales_by_channel_target_oli_m = $this->laporan_performance->laporan_sales_by_channel_target_oli_m($id_dealer, $start_date, $end_date, $type);
			$data['laporan_sales_by_channel_target_oli_m1'] = $laporan_sales_by_channel_target_oli_m1 = $this->laporan_performance->laporan_sales_by_channel_target_oli_m1($id_dealer, $start_date, $end_date, $type);



			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_sales_by_channel", $data);
			}
		}elseif($type == 'laporan_performance_sales_oil'){
			$data['laporan_performance_sales_oil'] = $laporan_performance_sales_oil = $this->laporan_performance->laporan_performance_sales_oil($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_performance_sales_oil", $data);
			}
		}elseif($type == 'laporan_penjualan_hga'){
			$data['laporan_penjualan_hga'] = $laporan_penjualan_hga = $this->laporan_performance->laporan_penjualan_hga($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_penjualan_hga", $data);
			}
		}elseif($type == 'laporan_product_value'){
			$data['laporan_product_value'] = $laporan_product_value = $this->laporan_performance->laporan_product_value($id_dealer, $start_date, $end_date, $type);
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_product_value", $data);
			}
		}elseif($type == 'laporan_hlo_dealer'){
			$data['laporan_hlo_dealer'] = $laporan_hlo_dealer = $this->laporan_performance->laporan_hlo_dealer($id_dealer, $start_date, $end_date, $type);
			$data_po = array();
			$data_gr = array();
			foreach($data['laporan_hlo_dealer']->result() as $row){
					if($row->proses_ahm == '1'){
						$customer_query = $this->db->query("SELECT DATE_FORMAT(hewh.tgl_ps ,'%d/%m/%Y') as tgl_packing_sheet_ahm,
						DATE_FORMAT(hewh.tgl_penerimaan_md ,'%d/%m/%Y') as tgl_parts_diterima_md, 
						hewh.id_purchase_order as no_po_md,DATE_FORMAT(hewh.updated_at,'%d/%m/%Y') as tgl_info_eta_revisi_ke_jaringan
						FROM tr_h3_md_history_estimasi_waktu_hotline hewh
						WHERE hewh.po_id='$row->no_po_dealer' and hewh.id_part='$row->id_part'
						")->row();
						$data_po[] = $customer_query;
					}else{
						$customer_query = $this->db->query("SELECT '' as tgl_packing_sheet_ahm, '' as tgl_parts_diterima_md, '' as no_po_md, '' as tgl_info_eta_revisi_ke_jaringan
						")->row();
						$data_po[] = $customer_query;
					}

					$gr_query = $this->db->query("SELECT id_referensi,DATE_FORMAT(created_at,'%d/%m/%Y') as tgl_dealer_terima_barang, qty_fulfillment
					FROM tr_h3_dealer_order_fulfillment dof
					WHERE po_id_int='$row->po_id_int' and id_part_int='$row->id_part_int'
					and qty_fulfillment > 0
					")->row();
					if($gr_query->id_referensi == '' or $gr_query->id_referensi == NULL){
						$gr_query = $this->db->query("SELECT '' as id_referensi, '' as tgl_dealer_terima_barang, '' as qty_fulfillment
						")->row();
					} 
					$data_gr[] = $gr_query;
			}
			$data['data_po'] = $data_po;
			$data['data_gr'] = $data_gr;
			if ($_POST['process'] == 'excel') {
				$this->load->view("h3/laporan/temp_laporan_hlo_dealer", $data);
			}
		}
	}
}
