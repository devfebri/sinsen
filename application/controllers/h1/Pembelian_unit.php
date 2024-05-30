<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_unit extends CI_Controller {

	var $tables =   "tr_invoice";	
	var $folder =   "h1";
	var $isi		=		"invoice_terima";	
	var $page		=		"pembelian_unit";
	var $pk     =   "no_faktur";
	var $title  =   "Pembelian Unit";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');		
		$this->load->library('csvimport');
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
		$data['set']		= "view";				
		$data['dt_invoice'] = $this->db->query("SELECT DISTINCT(no_faktur),tgl_faktur,tgl_pokok,tgl_ppn,tgl_pph,status FROM tr_invoice ORDER BY tr_invoice.no_faktur DESC");			
		$this->template($data);		
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";
		$no_faktur = $this->input->get('id');
		$data['dt_invoice'] = $this->db->query("SELECT * FROM tr_invoice WHERE tr_invoice.no_faktur = '$no_faktur' ORDER BY tr_invoice.no_sl ASC");			
		$this->template($data);		
	}
	public function upload()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "upload";		
		$this->template($data);		
	}
	function import_db(){
		$filename = $_FILES["userfile"]["tmp_name"];
		if($_FILES['userfile']['size'] > 0)
		{
			$file = fopen($filename,"r");
			$is_header_removed = FALSE;
			$no = array();$no1 = 0;$no2 = 0;$jum = 0;$jum2 = 1;$isi = "";$cek_sipb = "";$no3 = 0;
			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				// if(!$is_header_removed){
				// 	$is_header_removed = TRUE;
				// 	continue;
				// }

				$row = array(
					'no_faktur'    =>  !empty($importdata[0])?$importdata[0]:'',
					'tgl_faktur'     =>  !empty($importdata[1])?$importdata[1]:'',
					'tgl_pokok'         =>  !empty($importdata[2])?$importdata[2]:'',
					'tgl_ppn'        =>  !empty($importdata[3])?$importdata[3]:'',
					'tgl_pph'       =>  !empty($importdata[4])?$importdata[4]:'',
					'no_sl'       =>  !empty($importdata[5])?$importdata[5]:'',
					'no_sipb'       =>  !empty($importdata[6])?$importdata[6]:'',
					'id_tipe_kendaraan'       =>  !empty($importdata[7])?$importdata[7]:'',
					'id_warna'       =>  !empty($importdata[8])?$importdata[8]:'',
					'qty'       =>  !empty($importdata[9])?$importdata[9]:'',
					'harga'       =>  !empty($importdata[10])?$importdata[10]:'',
					'ppn'       =>  !empty($importdata[11])?$importdata[11]:'',
					'pph'       =>  !empty($importdata[12])?$importdata[12]:'',
					'disc_quo'       =>  !empty($importdata[13])?$importdata[13]:'',
					'disc_type'       =>  !empty($importdata[14])?$importdata[14]:'',
					'disc_other'       =>  !empty($importdata[15])?$importdata[15]:''					
				);

				$cek = $this->db->query("SELECT * FROM tr_invoice WHERE no_sl = '$importdata[5]' AND no_faktur = '$importdata[0]' AND id_tipe_kendaraan = '$importdata[7]' AND id_warna = '$importdata[8]'");
				if($cek->num_rows() == 0){
					$sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$importdata[6]'");
					if($sipb->num_rows() > 0){
						$this->db->trans_begin();
						$this->db->insert('tr_invoice', $row);
						if(!$this->db->trans_status()){
							$this->db->trans_rollback();
						}else{
							$this->db->trans_commit();
						}
						$no2++;
					}else{
						if($cek_sipb==""){
							$cek_sipb = $jum2;
						}else{
							$cek_sipb = $cek_sipb.",".$jum2;
						}
						$no3++;
					}
				}else{
					if($isi==""){
						$isi = $jum;
					}else{
						$isi = $isi.",".$jum;
					}
					$no1++;
				}
				$jum++;
				$jum2++;
			}
			fclose($file);
			$_SESSION['pesan'] 	= $jum." Data yang anda import. Berhasil = ".$no2." data. Gagal = ".$no1." data (".$isi."). Tidak ditemukan No SIPB = ".$no3." data (".$cek_sipb.")";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice'>";	
		}				
  }
  public function approve(){
  	$id = $this->input->get('id');
  	$this->db->query("UPDATE tr_invoice SET status = 'approve' WHERE no_faktur = '$id'");
  	$_SESSION['pesan'] 	= "Data berhasil diubah";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pembelian_unit'>";	
  }
  public function reject(){
  	$id = $this->input->get('id');
  	$this->db->query("UPDATE tr_invoice SET status = 'reject' WHERE no_faktur = '$id'");
  	$_SESSION['pesan'] 	= "Data berhasil diubah";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pembelian_unit'>";	
  }
}