<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realtime_stok_d extends CI_Controller {

    var $tables =   "tr_penerimaan_unit_dealer_detail";	
		var $folder =   "h1";
		var $page		=		"realtime_stok_d";
    var $pk     =   "id_penerimaan_unit_dealer_detail";
    var $title  =   "Real Time Stok Dealer";

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
		$data['set']		= "view_fix";				
		// $data['dt_list'] = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),ms_dealer.kode_dealer_md,tr_penerimaan_unit_dealer.id_dealer,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_penerimaan_unit_dealer_detail 
		// 						INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
  //               LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
		// 						LEFT JOIN `tr_picking_list_view` ON tr_picking_list_view.`no_mesin` = `tr_scan_barcode`.`no_mesin`
		// 						LEFT JOIN `tr_picking_list` ON `tr_picking_list_view`.`no_picking_list` = `tr_picking_list`.`no_picking_list`
		// 						LEFT JOIN `tr_do_po` ON `tr_picking_list`.`no_do` = `tr_do_po`.`no_do`
  //               LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
  //               LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
  //               LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer                                
  //               GROUP BY tr_scan_barcode.id_item,tr_penerimaan_unit_dealer.id_dealer
  //               ORDER BY tr_scan_barcode.id_item ASC");					


		/* $data['dt_list'] = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),ms_dealer.kode_dealer_md,tr_do_po.id_dealer,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_scan_barcode 
                LEFT JOIN tr_picking_list_view ON tr_scan_barcode.no_mesin = tr_picking_list_view.no_mesin 
                LEFT JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 
								LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do                
                LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer                                
                GROUP BY tr_scan_barcode.id_item,ms_dealer.id_dealer
                ORDER BY tr_scan_barcode.id_item ASC");	*/
		$this->template($data);	
	}
	public function ajax_list()
	{
		$list = $this->m_stok_d->get_datatables();
		$data = array();
		$no = $_POST['start'];
		$summary=0;
		//$id_dealer = 43;
		foreach ($list as $isi) {
			$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
          LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
          LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
          LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
          LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
          LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
          WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' 
          AND tr_penerimaan_unit_dealer_detail.retur = 0
          AND tr_scan_barcode.status = '4'")->row();                
      $cek_unfill = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
                  LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                  LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                  WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                  AND tr_do_po_detail.id_item = '$isi->id_item' AND tr_do_po.id_dealer = '$isi->id_dealer'")->row();
      $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                  WHERE tr_surat_jalan.tr_surat_jalan.status ='proses'
                  AND tr_surat_jalan_detail.id_item = '$isi->id_item' AND tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.retur = 0")->row();
      $dr = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->id_dealer);
      if($dr->num_rows() > 0){
        $t = $dr->row();
        $nama_dealer = $t->nama_dealer;
        $kode_dealer_md = $t->kode_dealer_md;
      }else{
        $nama_dealer = "";
        $kode_dealer_md = "";
      }
      //if($cek_qty->jum>0 OR $cek_unfill->jum>0 OR $cek_in->jum>0){
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = "<a href='h1/realtime_stok_d/detail?id=$isi->id_item&d=$isi->id_dealer'>
                    <button type='button' title='Detail' class='btn bg-maroon btn-flat btn-sm'><i class='fa fa-eye'></i> Detail</button>
                  </a>";
				$row[] = $isi->id_item;
				$row[] = $kode_dealer_md;
				$row[] = $nama_dealer;
				$row[] = $isi->tipe_ahm;				
				$row[] = $isi->warna;				
				$row[] = $cek_qty->jum;
				$row[] = $cek_unfill->jum;
				$row[] = $cek_in->jum;				
				$data[] = $row;
			//}						
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_stok_d->count_all(),
						"recordsFiltered" => $this->m_stok_d->count_filtered(),
						"data" => $data,
						"summary" =>$summary
				);
		//output to json format
		echo json_encode($output);
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$id							= $this->input->get('id');															
		$id_dealer			= $this->input->get('d');															
		$data['set']		= "detail";		
    $am = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$id'")->row();           	
		$data['dt_list'] = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),tr_penerimaan_unit_dealer.id_dealer,COUNT(tr_penerimaan_unit_dealer_detail.no_mesin) AS jum,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
                WHERE tr_scan_barcode.id_item = '$id' AND tr_penerimaan_unit_dealer_detail.retur = 0
                AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
                ORDER BY tr_scan_barcode.id_item ASC");			
		$data['dt_scan_barcode'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode 
								ON tr_scan_barcode.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin
								WHERE tr_scan_barcode.id_item = '$id' AND tr_scan_barcode.status <> '4' ORDER BY status ASc");
		$data['dt_pu'] 	= $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_penerimaan_unit_dealer.id_dealer,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe,tr_scan_barcode.status 
											FROM tr_penerimaan_unit_dealer INNER JOIN tr_penerimaan_unit_dealer_detail ON 
											tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
											INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
											WHERE tr_scan_barcode.id_item = '$id' AND tr_penerimaan_unit_dealer.id_dealer	= '$id_dealer' 
											AND tr_penerimaan_unit_dealer_detail.retur = 0
											ORDER BY tr_scan_barcode.status ASC");
		$this->template($data);	
	}
			
}