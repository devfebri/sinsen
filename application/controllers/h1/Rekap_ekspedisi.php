<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_ekspedisi extends CI_Controller {

    var $tables =   "tr_rekap_ekspedisi";	
		var $folder =   "h1";
		var $page		=		"rekap_ekspedisi";
		var $isi		=		"invoice_terima";
    var $pk     =   "id_rekap_ekspedisi";
    var $title  =   "Rekap Estimasi Kerusakan Ekspedisi";

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
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;	
		$data['dt_rekap']	= $this->db->query("SELECT * FROM tr_rekap_ekspedisi INNER JOIN ms_vendor ON tr_rekap_ekspedisi.id_vendor = ms_vendor.id_vendor
				ORDER BY tr_rekap_ekspedisi.id_rekap_ekspedisi DESC");		
		$data['set']		= "view";				
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$data['id'] = $id	= $this->input->get('id');			
		$data['dt_rekap']	= $this->db->query("SELECT * FROM tr_rekap_ekspedisi 
				LEFT JOIN ms_vendor ON tr_rekap_ekspedisi.id_vendor = ms_vendor.id_vendor				
				WHERE tr_rekap_ekspedisi.id_rekap_ekspedisi = '$id'");		
		$this->template($data);			
	}
	public function cek_vendor()
	{		
		$id_vendor	= $this->input->post('id_vendor');	
		$dt_dri			= $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$id_vendor'")->row();								
		if(isset($dt_dri->vendor_name)){
			echo $dt_dri->vendor_name;
		}else{
			echo "";
		}		
	}
	public function t_data(){
		$id_vendor 	= $this->input->post('id_vendor');
		$tanggal 		= $this->input->post('tanggal');
		$tanggal1 	= $this->input->post('tanggal1');
		$data['dt_rekap'] = $this->db->query("SELECT * FROM tr_checker_detail
				INNER JOIN tr_checker ON tr_checker_detail.id_checker = tr_checker.id_checker
				LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part 				
				INNER JOIN tr_scan_barcode ON tr_checker.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_checker.ekspedisi = '$id_vendor' AND tr_scan_barcode.tgl_penerimaan BETWEEN '$tanggal' AND '$tanggal1'
				AND tr_checker.sumber_kerusakan = 'Ekspedisi'
				AND (tr_checker.rekap IS NULL OR tr_checker.rekap <> 'ya')");		 
		// $data['dt_rekap'] = $this->db->query("SELECT * FROM tr_checker 				
		// 		INNER JOIN tr_scan_barcode ON tr_checker.no_mesin = tr_scan_barcode.no_mesin
		// 		WHERE tr_checker.ekspedisi = '$id_vendor' AND tr_scan_barcode.tgl_penerimaan BETWEEN '$tanggal' AND '$tanggal1'
		// 		AND tr_checker.sumber_kerusakan = 'Ekspedisi'
		// 		AND (tr_checker.rekap IS NULL OR tr_checker.rekap <> 'ya')");		 
		$this->load->view('h1/t_rekap_ekspedisi',$data);
	}	
	public function detail2()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$this->template($data);			
	}	
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_rekap_ekspedisi ORDER BY id_rekap_ekspedisi DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_rekap_ekspedisi)-3;
			$id 	= substr($row->id_rekap_ekspedisi,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/RKC/".$isi;
		}else{
			$kode = $th.$bln."/RKC/00001";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$id_rekap_ekspedisi 			= $this->cari_id();
		$da['id_rekap_ekspedisi'] = $id_rekap_ekspedisi;
		$da['id_vendor'] 				= $this->input->post("id_vendor");
		$da['tgl_rekap'] 				= $tgl;
		$da['tgl_awal'] 				= $this->input->post("periode_awal");
		$da['tgl_akhir'] 				= $this->input->post("periode_akhir");
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");	
		$cek=0;
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_".$i])){
				$cek = $cek + 1;
			}
		}



		if($cek > 0){
			for ($i=1; $i <= $jum; $i++) { 
				if(isset($_POST["cek_".$i])){
					$id_checker 			= $_POST["id_checker_".$i];			
					$data['id_checker'] = $id_checker;
					$data['id_rekap_ekspedisi'] 		= $id_rekap_ekspedisi;
					$data['total'] 		= $_POST["tot_".$i];			

					$this->db->query("UPDATE tr_checker SET rekap = 'ya' WHERE id_checker = '$id_checker'");										
					$this->m_admin->insert("tr_rekap_ekspedisi_detail",$data);								

					// $cek = $this->db->query("SELECT * FROM tr_rekap_ekspedisi_detail WHERE id_rekap_ekspedisi = '$id_rekap_ekspedisi'");
					// if($cek->num_rows() > 0){						
					// 	$t = $cek->row();
					// 	$this->m_admin->update("tr_rekap_ekspedisi_detail",$data,"id_rekap_ekspedisi_detail",$t->id_rekap_ekspedisi_detail);								
					// }else{
					// }
				}			
			}
			$ce = $this->db->query("SELECT * FROM tr_rekap_ekspedisi WHERE id_rekap_ekspedisi = '$id_rekap_ekspedisi'");
			if($ce->num_rows() > 0){						
				$this->m_admin->update("tr_rekap_ekspedisi",$da,"id_rekap_ekspedisi",$id_rekap_ekspedisi);								
			}else{
				$this->m_admin->insert("tr_rekap_ekspedisi",$da);								
			}
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rekap_ekspedisi'>";
		}else{
			$_SESSION['pesan'] 	= "Pilih dulu detailnya";
			$_SESSION['tipe'] 	= "danger";		
			echo "<script>history.go(-1)</script>";
		}
			
	}
	public function delete(){
		$id = $this->input->get('id');
		$dt = $this->m_admin->getByID("tr_rekap_ekspedisi_detail","id_rekap_ekspedisi",$id);
		foreach ($dt->result() as $row) {
			$this->db->query("UPDATE tr_checker SET rekap = '' WHERE id_checker = '$row->id_checker'");										
		}
		$this->db->query("DELETE FROM tr_rekap_ekspedisi WHERE id_rekap_ekspedisi = '$id'");			
		$this->db->query("DELETE FROM tr_rekap_ekspedisi_detail WHERE id_rekap_ekspedisi = '$id'");					
		$_SESSION['pesan'] 	= "Data has been deleted successfully";
		$_SESSION['tipe'] 	= "danger";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rekap_ekspedisi'>";
	}
}