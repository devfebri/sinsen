<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realtime_stok extends CI_Controller {

    var $tables =   "tr_real_stock";	
		var $folder =   "h1";
		var $page		=		"realtime_stok";
    var $pk     =   "id_real_stok";
    var $title  =   "Real Time Stok MD";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_stok_md');		
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
		$data['set']		= "view_final";				
		$this->template($data);	
	}
	public function akhir()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view_final";		
		$this->template($data);	
	}
	public function ajax_list()
	{
		$list = $this->m_stok_md->get_datatables();
		$data = array();
		$no = $_POST['start'];
		$summary=0;
		foreach ($list as $isi) {
			$dt = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_item.bundling,ms_item.id_item_lama,ms_item.id_warna_lama FROM ms_item 
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 						
						WHERE ms_item.id_item = '$isi->id_item'");
			if($dt->num_rows() > 0){
        $r = $dt->row();
        $tipe_ahm = $r->tipe_ahm;
        $warna = $r->warna;
        $bundling = $r->bundling;
        $id_item_lama = $r->id_item_lama;
        $id_warna_lama = $r->id_warna_lama;
      }else{
        $tipe_ahm="";$warna="";$bundling="";$id_item_lama="";$id_warna_lama="";
      }

			$cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '1' AND tipe='RFS'")->row();
      $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '2'")->row();
      $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '3'")->row();
      $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'NRFS' AND status < 4")->row();
      $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
      $cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                        WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode) 
                        AND id_modell = '$isi->id_tipe_kendaraan' AND id_warna = '$isi->id_warna'")->row();

      //$cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'")->row();
      if($bundling == 'Ya'){
        $id_tipe = $id_item_lama;
        $id_warna = $id_warna_lama;
      }else{
        $id_tipe  = $isi->id_tipe_kendaraan;
        $id_warna = $isi->id_warna;
      }
      $cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE 
          tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'")->row();
      
      if($bundling != 'Ya'){      	      
        $cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.warna = '$isi->id_warna'")->row();      
        $cek_sl2_2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE ms_item.id_item_lama = '$isi->id_tipe_kendaraan' AND ms_item.id_warna_lama = '$isi->id_warna'")->row();      
        if(isset($cek_sl2_2->jum)){
      		$jumlah_sl = $cek_sl2_1->jum + $cek_sl2_2->jum; 
        }else{
        	$jumlah_sl = $cek_sl2_1->jum;
        }
      }else{
        $cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.warna = '$isi->id_warna'")->row();            	
        $jumlah_sl = $cek_sl2->jum;
      }
			//$cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND warna = '$isi->id_warna'")->row();      			

      $cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
      	WHERE tr_sipb.id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tr_sipb.id_warna = '$isi->id_warna'
      	AND ms_item.bundling <> 'Ya'")->row();                
			$cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
			 	WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
			 	AND ms_item.bundling <> 'Ya'")->row();
      $cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$isi->id_item'")->row();
      $sipb = 0;
      $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;
      if($cek_in1->jum - $cek_in2->jum > 0 AND $cek_item->bundling != 'Ya'){
      	$rr = $cek_in1->jum - $cek_in2->jum;
      }else{
      	$rr = 0;
      }

      $cek_sl2_jum=0;$cek_sl1_jum=0;
      if(isset($cek_sl1->jum)) $cek_sl1_jum = $cek_sl1->jum;
      if(isset($jumlah_sl)) $cek_sl2_jum = $jumlah_sl;      
      if($cek_sl1_jum - $cek_sl2_jum >= 0 AND $cek_item->bundling != 'Ya'){            
        $r2 = $cek_sl1_jum - $cek_sl2_jum;     
      }else{
        $r2 = 0;
      }



			$no++;
			$row = array();
			$row[] = $no;
			$row[] = "<a data-toggle=\"tooltip\" title=\"Detail\" class=\"btn bg-maroon btn-sm btn-flat\" href=\"h1/realtime_stok/detail?id=$isi->id_item\"><i class=\"fa fa-eye\"></i></a>";
			$row[] = $isi->id_item;
			$row[] = $tipe_ahm;
			$row[] = $warna;
			$row[] = $cek_ready->jum;
			$row[] = $cek_booking->jum;
			$row[] = $cek_pl->jum;
			$row[] = $cek_nrfs->jum;
			$row[] = $cek_pinjaman->jum;
			$row[] = $rr;
			$row[] = $r2;
			$row[] = $total;	
			$data[] = $row;

			// $row[] = $no;
			// $row[] = "<a data-toggle=\"tooltip\" title=\"Detail\" class=\"btn bg-maroon btn-sm btn-flat\" href=\"h1/realtime_stok/detail?id=$isi->id_item\"><i class=\"fa fa-eye\"></i></a>";
			// $row[] = $isi->id_item;
			// $row[] = $isi->id_tipe_kendaraan;
			// $row[] = $isi->id_warna;
			// $row[] = $cek_ready->jum;
			// $row[] = $cek_booking->jum;
			// $row[] = $cek_pl->jum;
			// $row[] = $cek_nrfs->jum;
			// $row[] = $cek_pinjaman->jum;
			// $row[] = $rr;
			// $row[] = $cek_sl->jum;
			// $row[] = $total;		
			// $data[] = $row;
			$summary += $total;
			
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_stok_md->count_all(),
			"recordsFiltered" => $this->m_stok_md->count_filtered(),
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
		$data['set']		= "detail";		
    $am = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$id'")->row();           	
		$data['dt_real_stock'] = $this->db->query("SELECT tr_real_stock.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_real_stock
											INNER JOIN ms_item ON tr_real_stock.id_item = ms_item.id_item
											INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
											INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
											WHERE tr_real_stock.id_item = '$id'  ORDER BY tr_real_stock.id_tipe_kendaraan DESC");
		$data['dt_scan_barcode'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE id_item = '$id' AND tr_scan_barcode.status < '4' ORDER BY status ASc");
		$this->template($data);	
	}
			
}