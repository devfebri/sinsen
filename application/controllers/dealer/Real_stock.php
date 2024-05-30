<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Real_stock extends CI_Controller {

    var $tables =   "tr_real_stock_dealer";	
		var $folder =   "dealer";
		var $page		=		"real_stock";
    var $pk     =   "id_real_stock_dealer";
    var $title  =   "Stock Dealer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_stok_d');		
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

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_list'] = $this->db->query("SELECT ms_item.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item
                LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna                      
                ORDER BY ms_item.id_item ASC");			
		$this->template($data);	
	}
	public function ajax_list()
	{
		//$list = $this->m_stok_d->get_datatables();
		$id_dealer = $this->m_admin->cari_dealer();
		$list = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),COUNT(tr_penerimaan_unit_dealer_detail.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
                WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
                GROUP BY tr_scan_barcode.id_item
                ORDER BY tr_scan_barcode.id_item");
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = "<a data-toggle=\"tooltip\" title=\"Detail\" class=\"btn bg-maroon btn-sm btn-flat\" href=\"h1/realtime_stok/detail?id=$isi->id_item\"><i class=\"fa fa-eye\"></i></a>";
			$row[] = $isi->id_item;
			$row[] = $isi->id_item;
			$row[] = $isi->id_item;
			$row[] = $isi->id_item;
			$row[] = $isi->id_item;
			$row[] = $isi->id_item;
			$row[] = $isi->id_item;
			$row[] = $isi->id_item;			
			$data[] = $row;

			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_stok_d->count_all(),
						"recordsFiltered" => $this->m_stok_d->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$id							= $this->input->get('id');															
		$id_dealer			= $this->m_admin->cari_dealer();															
		$data['set']		= "detail";		
    $am = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$id'")->row();           	
		$data['dt_list'] = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),tr_penerimaan_unit_dealer.id_dealer,COUNT(tr_penerimaan_unit_dealer_detail.no_mesin) AS jum,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
                WHERE tr_scan_barcode.id_item = '$id' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
                AND tr_penerimaan_unit_dealer.status = 'close'
                AND (tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL OR tr_penerimaan_unit_dealer_detail.status_on_spk = '(NULL)')
                ORDER BY tr_scan_barcode.id_item ASC");			
		$data['dt_scan_barcode'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode 
								ON tr_scan_barcode.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin
								WHERE tr_scan_barcode.id_item = '$id' AND tr_scan_barcode.status <> '4' ORDER BY status ASc");
		$data['dt_pu'] 	= $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_penerimaan_unit_dealer.id_dealer,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe,tr_scan_barcode.status,tr_penerimaan_unit_dealer_detail.fifo AS fifo_terima_dealer 
											FROM tr_penerimaan_unit_dealer INNER JOIN tr_penerimaan_unit_dealer_detail ON 
											tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
											INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
											WHERE tr_scan_barcode.id_item = '$id' AND tr_penerimaan_unit_dealer.id_dealer	= '$id_dealer' 
											AND tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = '0' and tr_scan_barcode.status !=5
											-- AND (tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL OR tr_penerimaan_unit_dealer_detail.status_on_spk = '(NULL)')
											ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC");

		$data['id_item'] = $id;
		$data['id_dealer'] = $id_dealer;
		$this->template($data);	
	}
			
}