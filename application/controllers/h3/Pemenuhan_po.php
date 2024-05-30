<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemenuhan_po extends CI_Controller {

	var $tables 	=   "tr_pemenuhan_po";	
	var $folder 		=   "h3";
	var $page		=		"pemenuhan_po";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "PO Permintaan Aksesoris";

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
		$data['set']	= "view";
		$data['dt_pemenuhan_po'] = $this->db->query("SELECT * FROM tr_po_aksesoris WHERE status_po = 'approved' OR status_po = 'terpenuhi' ORDER BY tr_po_aksesoris.tgl_po DESC");	
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");	
		
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_paket'] = $this->db->query("SELECT * FROM ms_paket_bundling WHERE active = 1");									
		$this->template($data);	
	}
	public function pemenuhan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "pemenuhan";			
		$id 						= $this->input->get('id');
		$data['dt_paket'] = $this->db->query("SELECT * FROM tr_po_aksesoris_detail INNER JOIN ms_part ON tr_po_aksesoris_detail.id_part=ms_part.id_part 
				WHERE no_po_aksesoris = '$id'");									
		$data['dt_pemenuhan_po'] = $this->db->query("SELECT * FROM tr_po_aksesoris WHERE no_po_aksesoris = '$id'");									
		$this->template($data);	
	}	
	public function cari_id(){
		// $po					= $this->input->post('po');
		// $th 						= date("Y");
		// $bln 						= date("m");		
		// $pr_num 				= $this->db->query("SELECT * FROM tr_po ORDER BY id_po DESC LIMIT 0,1");						
		// if($pr_num->num_rows()>0){
		// 	$row 	= $pr_num->row();				
		// 	$pan  = strlen($row->id_po)-5;
		// 	$id 	= substr($row->id_po,$pan,5)+1;	
		// 	if($id < 10){
		// 			$kode1 = $th.$bln."0000".$id;          
	 //  }elseif($id>9 && $id<=99){
		// 			$kode1 = $th.$bln."000".$id;                    
	 //  }elseif($id>99 && $id<=999){
		// 			$kode1 = $th.$bln."00".$id;          					          
	 //  }elseif($id>999){
		// 			$kode1 = $th.$bln."0".$id;                    
	 //  }
		// 	$kode = $kode1;
		// }else{
		// 	$kode = $th.$bln."00001";
		// } 	
		$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_pemenuhan_po 				= $this->cari_id();
		$da['no_pemenuhan_po'] 	= $no_pemenuhan_po;
		$da['tgl_po'] 					= $tgl;				
		$da['no_po_aksesoris']	= $this->input->post("no_po_aksesoris");		
		$no_po_aksesoris				= $this->input->post("no_po_aksesoris");		
		$da['status_pem'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 			
			$id_part 						= $_POST["id_part_".$i];			
			$data['no_pemenuhan_po'] 		= $no_pemenuhan_po;
			$data['id_part'] 		= $id_part;				
			$data['qty_po'] 		= $_POST["qty_po_".$i];			
			$data['qty_pemenuhan'] 		= $_POST["qty_pemenuhan_".$i];			
			$qty_pemenuhan 			= $_POST["qty_pemenuhan_".$i];			
			$data['harga'] 			= $_POST["harga_".$i];			
			//$this->db->query("UPDATE tr_terima_bj SET serah_bpkb = 'ya' WHERE no_mesin = '$nosin'");										

			$cek = $this->db->query("SELECT * FROM tr_pemenuhan_po_detail WHERE id_part = '$id_part' AND no_pemenuhan_po = '$no_pemenuhan_po'");
			if($cek->num_rows() > 0){						
				$t = $cek->row();
				$this->m_admin->update("tr_pemenuhan_po_detail",$data,"id_pemenuhan_po_detail",$t->id_pemenuhan_po_detail);								
			}else{
				$this->m_admin->insert("tr_pemenuhan_po_detail",$data);								
			}			

			$this->db->query("UPDATE tr_po_aksesoris_detail SET pemenuhan = '$qty_pemenuhan' WHERE no_po_aksesoris = '$no_po_aksesoris' AND id_part = '$id_part'");

		}
			
		$ce = $this->db->query("SELECT * FROM tr_pemenuhan_po WHERE no_pemenuhan_po = '$no_pemenuhan_po'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_pemenuhan_po",$da,"no_pemenuhan_po",$no_pemenuhan_po);								
		}else{
			$this->m_admin->insert("tr_pemenuhan_po",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/pemenuhan_po'>";
	}
	public function approve()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->get("id");		
		// $data['status_pem'] 			= "approved";			
		// $data['updated_at']				= $waktu;		
		// $data['updated_by']				= $login_id;		
		// $this->m_admin->update($tabel,$data,$pk,$id);

		$this->db->query("UPDATE tr_pemenuhan_po SET status_pem = 'approved' WHERE no_po_aksesoris = '$id'");
		$this->db->query("UPDATE tr_po_aksesoris SET status_po = 'terpenuhi' WHERE no_po_aksesoris = '$id'");
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/pemenuhan_po'>";		
	}
	public function reject()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->get("id");
		$id_				= $this->input->get($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['status_po'] 	= "rejected";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/pemenuhan_po'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	public function cek_no_sj(){		
		$th     = date("Y");
		$bln    = date("m");
		$th_bln = date("Y-m");
		$waktu  = gmdate("Y-m-d H:i:s", time()+60*60*7);				
		$pr_num = $this->db->query("SELECT *,LEFT(tgl_po,7) as tgl_po_alias FROM tr_po_aksesoris WHERE LEFT(tgl_po,7) = '$th_bln' ORDER BY no_surat_jalan DESC LIMIT 0,1");
			$row 	= $pr_num->row();				

		if ($pr_num->num_rows() > 0) {
			$row=$pr_num->row();
			if ($th_bln != $row->tgl_po_alias) {
				$kode = 'SJ/POACC/'.$th.$bln.'001';
			}else{
				$kode = 'SJ/POACC/'.$th.$bln.'001';
				$old_numb = substr($row->no_surat_jalan, -3);
				$kode = 'SJ/POACC/'.$th.$bln.sprintf("%03d", $old_numb+1);
			}
		}else{
			$kode = 'SJ/POACC/'.$th.$bln.'001';
		}
		return $kode;
	}

	function cetak_sj()
	{
		$this->load->library('mpdf_l');
		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
		$id = $this->input->get('id');
		$cek_sj = $this->db->get_where('tr_po_aksesoris',['no_po_aksesoris'=>$id]);
		if ($cek_sj->num_rows()==0) {
			redirect('h3/pemenuhan_po');
		}
		$row = $cek_sj->row();
		if ($row->no_surat_jalan==null) {
			$dt_upd['no_surat_jalan'] = $this->cek_no_sj();
			$dt_upd['cetak_sj_ke']    = 1;		
			$dt_upd['tgl_cetak_sj']   = $waktu;			
			$this->db->update('tr_po_aksesoris',$dt_upd,['no_po_aksesoris'=>$id]);
		}else{
			$dt_upd['cetak_sj_ke']    = $row->cetak_sj_ke+1;		
			$dt_upd['tgl_cetak_sj']   = $waktu;			
			$this->db->update('tr_po_aksesoris',$dt_upd,['no_po_aksesoris'=>$id]);
		}
		
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion =true;  // Set by default to TRUE
		$mpdf->charset_in               ='UTF-8';
		$mpdf->autoLangToFont           = true;
		$data['cetak']					= 'cetak_sj';
		$data['po']						= $cek_sj->row();
		$html                           = $this->load->view('h3/pemenuhan_po_cetak', $data, true);
        // render the view into HTML
        $mpdf->WriteHTML($html);
        // write the HTML into the mpdf
        $output = 'cetak_surat_jalan.pdf';
        $mpdf->Output("$output", 'I');
	}


}