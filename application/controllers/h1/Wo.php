<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wo extends CI_Controller {

    var $tables =   "tr_wo";	
		var $folder =   "h1";
		var $page		=		"wo";
    var $pk     =   "no_mesin";
    var $title  =   "Work Order (WO)";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');


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
		$data['dt_wo'] = $this->db->query("SELECT tr_checker.*,tr_wo.status_wo,tr_wo.no_wo,tr_wo.tgl_wo FROM tr_checker LEFT JOIN tr_wo ON tr_checker.id_checker = tr_wo.id_checker			
			ORDER BY tr_checker.id_checker DESC");		
		$this->template($data);			
	}

	public function detail()
	{				
		$id = $this->input->get("id");
		$data['isi']    = $this->page;	
		$data['dt_wo']	= $this->db->query("SELECT tr_checker.*,tr_wo.*,ms_item.*,tr_scan_barcode.no_mesin,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_checker LEFT JOIN tr_wo ON tr_checker.id_checker = tr_wo.id_checker
			INNER JOIN tr_scan_barcode ON tr_checker.no_mesin = tr_scan_barcode.no_mesin 
			INNER JOIN ms_item ON ms_item.id_item = tr_scan_barcode.id_item
			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			WHERE tr_checker.id_checker = '$id'
			ORDER BY tr_checker.id_checker ASC");
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$this->template($data);			
	}

	public function detail_popup()
	{				
		$id = $this->input->post("id_checker");
		$data['isi']    = $this->page;	
		$data['dt_wo']	= $this->db->query("SELECT tr_checker.*,tr_wo.*,ms_item.id_tipe_kendaraan,ms_item.id_item,tr_scan_barcode.no_mesin,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_checker.id_checker as id_checker FROM tr_checker LEFT JOIN tr_wo ON tr_checker.id_checker = tr_wo.id_checker
			INNER JOIN tr_scan_barcode ON tr_checker.no_mesin = tr_scan_barcode.no_mesin 
			INNER JOIN ms_item ON ms_item.id_item = tr_scan_barcode.id_item
			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			WHERE tr_checker.id_checker = '$id'
			ORDER BY tr_checker.id_checker ASC");
		$data['title']	= $this->title;						
		$this->load->view("h1/t_wo_detail_popup.php",$data);		
	}
	

	public function close()
	{				
		$id = $this->input->get("id");
		$data['no_mesin'] = $this->input->get("id");
		$data['no_wo'] 	= $this->input->get("d");
		$data['isi']    = $this->page;	
		$data['dt_wo']	= $this->db->query("SELECT tr_checker.*,tr_wo.*,ms_item.*,tr_scan_barcode.no_mesin,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_checker LEFT JOIN tr_wo ON tr_checker.id_checker = tr_wo.id_checker
			INNER JOIN tr_scan_barcode ON tr_checker.no_mesin = tr_scan_barcode.no_mesin 
			INNER JOIN ms_item ON ms_item.id_item = tr_scan_barcode.id_item
			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			WHERE tr_checker.id_checker = '$id'
			ORDER BY tr_checker.id_checker ASC");
		$data['title']	= $this->title;															
		$data['set']		= "close";				
		$data['dt_lokasi'] = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1'
							AND ms_lokasi_unit.status_unit = 'RFS' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");							
		$this->template($data);			
	}
	public function get_slot(){
		$id_lokasi	= $this->input->post('lokasi_baru');

		$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$id_lokasi'")->row();		
		$data .= "<option value=''>- choose -</option>";
		for ($i=1; $i <= $cek_maks->qty; $i++) { 
			if($i < 10){
				$isi = "0".$i;				
			}else{
				$isi= $i;
			}
			$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$id_lokasi' AND slot = '$isi' AND status = '1'");
			if($cek_slot->num_rows() == 0){
				$data .= "<option value='$isi'>$isi</option>\n";	
			}			
		}			
		echo $data;
	}
		public function cek_nosin(){
		$no_mesin		= $this->input->post('no_mesin');							
		$c = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'")->row();				

		$cek 	= $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'");

		$row = $cek->row();
		$cek_gudang = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_penerimaan_unit 
						ON tr_penerimaan_unit.id_penerimaan_unit=tr_penerimaan_unit_detail.id_penerimaan_unit 
						WHERE tr_penerimaan_unit_detail.no_shipping_list = '$row->no_shipping_list'")->row();

		//cek status, gudang dan tipe dedicated
		$cek1 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.tipe_dedicated = '$row->tipe_motor' AND ms_lokasi_unit.status_unit = 'RFS' 
						AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang' 
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");	

		
		//cek gudang, tipe kendaraan dan warna SAMA
		$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
						INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
						INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' AND tr_scan_barcode.warna = '$row->warna'
						AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
				
		//cek status, gudang dan tanpa dedicated
		$cek3 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
					  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi = 0
					  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
						AND ms_lokasi_unit.isi = ''
					  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

		//cek status, gudang dan tanpa dedicated
		$cek3_a = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi = 0
						  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
						  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

	
		//cek gudang, tipe kendaraan SAMA dan warna BEDA
		$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
						INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
						INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.status_unit = '$row->tipe' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND tr_scan_barcode.tipe_motor = 'RFS' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");								

		//cek gudang, tipe kendaraan BEDA dan warna BEDA
		$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
						tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang   
						WHERE ms_lokasi_unit.status_unit = 'RSF' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' and ms_lokasi_unit.tipe_dedicated = ''
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

		if($cek1->num_rows() > 0){
			$amb = $cek1->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;				
		}elseif($cek2->num_rows() > 0){
			$amb = $cek2->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;			
		}elseif($cek3->num_rows() > 0){
			$amb = $cek3->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;			
		}elseif($cek3_a->num_rows() > 0){
			$amb = $cek3_a->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;							
		}elseif($cek4->num_rows() > 0){
			$amb = $cek4->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;			
		}elseif($cek5->num_rows() > 0){
			$amb = $cek5->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;						
		}
		else{
			$isi_lokasi = "";
		}			

		//cek slot

		if($isi_lokasi != ""){
			$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();				
			if($cek_maks->isi < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 
						if($i < 10){
							$sl = "0".$i;
						}else{
							$sl = $i;
						}
						$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND slot = '$sl' AND (status = 1 OR status = 2) ORDER BY slot ASC");
						if($cek_slot2->num_rows() == 0){
							$isi_slot2 = $cek_slot2->row();
							$slot_baru = $sl;
							break;								
						}					

						
				}
				
			}else{
				$slot_baru = "";
			}			
		}else{
			$slot_baru = "";
		}			

		$lokasi_baru = $isi_lokasi."-".$slot_baru;


		echo $c->no_mesin."|".$c->id_item."|".$c->tipe_motor."|".$c->warna."|".$c->lokasi."-".$c->slot."|".$lokasi_baru;								
	}	
	public function get_slot_new(){
		$lokasi_s	= $this->input->post('lokasi_s');
		$rt = explode("-", $lokasi_s);		
		$cek = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang WHERE ms_lokasi_unit.id_lokasi_unit = '$rt[0]'")->row();							
		$data .= "<option value='$rt[0]'>$rt[0] - $cek->gudang</option>\n";			
		$dt_lokasi = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");							
    foreach($dt_lokasi->result() as $val) {
      $data .= "<option value='$val->id_lokasi_unit'>$val->id_lokasi_unit - $val->gudang</option>\n";
    }                      
		echo $data;
	}
	public function get_slot_new2(){
		$lokasi_s	= $this->input->post('lokasi_s');
		$rt = explode("-", $lokasi_s);				
		$data = "<option value='$rt[1]'>$rt[1]</option>\n";					
		echo $data;
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		
		$no_mesin 								= $this->input->post('no_mesin');			
		$no_wo 										= $this->input->post('no_wo');			
		$data['lokasi_baru'] 			= $this->input->post('lokasi_baru')."-".$this->input->post('slot');	
		$lokasi_baru 							= $this->input->post('lokasi_baru')."-".$this->input->post('slot');	
		$data['status_wo'] 				= "closed";
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	

		$lokasi_lama							= $this->input->post('lokasi_lama');				
		$lokasi_baru							= $this->input->post('lokasi_baru');	

		$da['lokasi']							= $this->input->post('lokasi_baru');	
		$da['slot']								= $this->input->post('slot');	
		$da['tipe']								= "RFS";
		$da['status']							= 1;

		$t = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();			
		$s = $this->m_admin->getByID("tr_checker","no_mesin",$no_mesin)->row();			
		$this->db->query("UPDATE ms_lokasi_unit SET isi = isi-1 WHERE id_lokasi_unit = '$t->lokasi'");				
		$this->db->query("UPDATE ms_lokasi_unit SET isi = isi+1 WHERE id_lokasi_unit = '$lokasi_baru'");				
		$this->db->query("UPDATE tr_checker SET status_checker = 'close' WHERE no_mesin = '$no_mesin'");				
		$this->m_admin->set_log($no_mesin,"NRFS",$lokasi_baru);
		$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);
		$this->m_admin->update($tabel,$data,"no_wo",$no_wo);						

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/wo'>";		
	}
	
	public function cetak_s(){
		$id 				= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$data['dt_stiker'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$id'");
		$this->load->view('h1/cetak_stiker',$data);
		
	}

	
	public function cetak_kwitansi()
	{
    $id = $this->input->get('id');
    $sql = $this->m_admin->getByID("tr_penerimaan_bank","id_penerimaan_bank",$id);
    if ($sql->num_rows()>0) {
    	$tgl 				= gmdate("y-m-d", time()+60*60*7);
			$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;										
      
      $mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
	    $mpdf->charset_in='UTF-8';
	    $mpdf->autoLangToFont = true;
      $data['cetak'] = 'cetak_kwitansi';
      $data['id_penerimaan_bank'] = $id;
            
      //$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();
			$data['header'] = $sql->row();
      $html = $this->load->view('h1/cetak_entry_penerimaan', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_.pdf';
      $mpdf->Output("$output", 'I');
    }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_penerimaan_bank'>";		
    }       
	}
	public function print_repair_tag_pdf()
	{
		// //header('Content-type: application/pdf');
		$id = $this->input->get('id');
		$tgl_checker = $this->input->get('tgl');
		$data['isi']    = $this->page;	
		$dt_wo	= $this->db->query("SELECT tr_checker.*,tr_wo.*,ms_item.*,tr_scan_barcode.no_mesin,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_checker LEFT JOIN tr_wo ON tr_checker.id_checker = tr_wo.id_checker
			INNER JOIN tr_scan_barcode ON tr_checker.no_mesin = tr_scan_barcode.no_mesin 
			INNER JOIN ms_item ON ms_item.id_item = tr_scan_barcode.id_item
			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			WHERE tr_checker.id_checker = '$id'
			ORDER BY tr_checker.id_checker ASC")->row();

			$pdf = new FPDF('p','mm',array(74,105));
			$pdf->SetMargins(3,3);
			$pdf->SetRightMargin(3);
    	$pdf->AddPage();

		  $pdf->SetFont('ARIAL','',11);
		  $pdf->Cell(68, 3, 'REPAIR TAG', 0, 1, 'C');
		  $pdf->Line(3, 8, 71, 8);
		  $pdf->SetFont('ARIAL','',9);
		  $pdf->Cell(20, 10, 'Tgl. Masuk ', 0, 0);	  	  
		  $pdf->Cell(20, 10, ': '.$tgl_checker.'', 0, 1);	
		  $pdf->Cell(20, 0, 'No. Polisi ', 0, 0);	  	  
		  $pdf->Cell(20, 0, ': '.$dt_wo->no_polisi.'', 0, 1);	
		  $pdf->Cell(20, 8, 'Ekspedisi ', 0, 0);	  	  
		  $pdf->Cell(20, 8, ': '.$dt_wo->ekspedisi.'', 0, 1);	
		  $pdf->Cell(20, 0, 'No Urut ', 0, 0);	  	  
		  $pdf->Cell(20, 0, ': '.$dt_wo->id_checker.'', 0, 1);	
		  $pdf->Cell(20, 8, 'No Mesin ', 0, 0);	  	  
		  $pdf->Cell(20, 8, ': '.$dt_wo->no_mesin.'', 0, 1);	
		  		 
	   	// buat tabel disini
	  	$pdf->SetFont('TIMES','',8);
	   
		   // kasi jarak
		  $pdf->Cell(2,5,'',5,10);	  
		   
		  $pdf->Cell(20, 5, '[kode part]', 1, 0);
		  $pdf->Cell(25, 5, '[deskripsi]', 1, 0);
		  $pdf->Cell(20, 5, '[gejala]', 1, 1);		  		  
		  $get 	= $this->db->query("SELECT * FROM tr_checker_detail INNER JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
		  		WHERE tr_checker_detail.id_checker = '$id'");		  
		  foreach($get->result() as $r)
		  {
		  	$pdf->Cell(20, 5, $r->id_part, 1, 0);
		    $pdf->Cell(25, 5, $r->deskripsi, 1, 0);
		    $pdf->Cell(20, 5, $r->gejala, 1, 1);		    		  	
		  }
		  //$pdf->Image(base_url().'/assets/panel/images/logo_sinsen.jpg', 150, 15, 50);
	 	  $pdf->Output(); 
	}
	public function print_tag()
	{
    $id = $this->input->get('id');    
		$tgl_checker = $this->input->get('tgl');	
		$sql	= $this->db->query("SELECT tr_checker.*,tr_wo.*,ms_item.*,tr_scan_barcode.no_mesin,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_checker LEFT JOIN tr_wo ON tr_checker.id_checker = tr_wo.id_checker
			INNER JOIN tr_scan_barcode ON tr_checker.no_mesin = tr_scan_barcode.no_mesin 
			INNER JOIN ms_item ON ms_item.id_item = tr_scan_barcode.id_item
			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			WHERE tr_checker.id_checker = '$id'
			ORDER BY tr_checker.id_checker ASC");
    
    $mpdf = $this->mpdf_l->load();    
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in='UTF-8';
    $mpdf->autoLangToFont = true;
    $data['cetak'] = 'cetak_tag';
    $data['id_checker'] = $id;
    $data['tgl_checker'] = $tgl_checker;
          
    //$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();
		$data['header'] = $sql->row();
    $html = $this->load->view('h1/cetak_tag', $data, true);
    // render the view into HTML
    $mpdf->WriteHTML($html);
    // write the HTML into the mpdf
    $output = 'cetak_.pdf';
    $mpdf->Output("$output", 'I');
       
	}
}