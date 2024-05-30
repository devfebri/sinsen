	<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoice extends CI_Controller {
	var $tables =   "tr_invoice";	
	var $folder =   "h1";
	var $page		=		"invoice";
	var $pk     =   "no_faktur";
	var $title  =   "Invoice (INV)";
	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_invoice_datatables');		
		//===== Load Library =====
		$this->load->library('upload');		
		$this->load->library('csvimport');
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
		/*$data['dt_invoice'] = $this->db->query("SELECT tr_invoice.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_invoice 
								LEFT JOIN ms_tipe_kendaraan ON tr_invoice.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
								LEFT JOIN ms_warna ON tr_invoice.id_warna = ms_warna.id_warna where tgl_faktur like '%2022' ORDER BY tr_invoice.no_faktur DESC");			
		*/
		$this->template($data);		
	
	}

	public function fetch_data_invoice_datatables(){

		$list = $this->m_invoice_datatables->get_datatables();

		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {       
	
			$bulan = substr($row->tgl_faktur, 2,2);
			$tahun = substr($row->tgl_faktur, 4,4);
			$tgl = substr($row->tgl_faktur, 0,2);
			$tanggal = $tgl."-".$bulan."-".$tahun;
  
			$bulan1 = substr($row->tgl_pokok, 2,2);
			$tahun1 = substr($row->tgl_pokok, 4,4);
			$tgl1 = substr($row->tgl_pokok, 0,2);
			$tanggal1 = $tgl1."-".$bulan1."-".$tahun1;
  
			$bulan2 = substr($row->tgl_ppn, 2,2);
			$tahun2 = substr($row->tgl_ppn, 4,4);
			$tgl2 = substr($row->tgl_ppn, 0,2);
			$tanggal2 = $tgl2."-".$bulan2."-".$tahun2;
  
			$bulan3 = substr($row->tgl_pph, 2,2);
			$tahun3 = substr($row->tgl_pph, 4,4);
			$tgl3 = substr($row->tgl_pph, 0,2);
			$tanggal3 = $tgl3."-".$bulan3."-".$tahun3;


			$no++;
			$rows = array();
			$rows[] = $no; 	
			$rows[] = $row->no_faktur;
			$rows[] = $tanggal;
			$rows[] = $tanggal1;
			$rows[] = $tanggal2;
			$rows[] = $tanggal3;
			$rows[] = $row->no_sl;
			$rows[] = $row->no_sipb;
			$rows[] = $row->tipe_ahm;
			$rows[] = $row->warna;
			$rows[] = $row->qty;
			$rows[] = $row->pph;
			$rows[] = $row->disc_quo;
			$rows[] = $row->disc_type;
			$rows[] = $row->disc_other;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_invoice_datatables->count_all(),
			"recordsFiltered" => $this->m_invoice_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
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
		$name 		= $_FILES["userfile"]["name"];
		$type 		= $_FILES["userfile"]["type"];
		$size 		= $_FILES["userfile"]["size"];
		$name_r   = explode('.', $name);
    if($size > 0 AND $name_r[1] == 'INV')
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
					'disc_other'       =>  !empty($importdata[15])?$importdata[15]:'',
					'status' => 'waiting'					
				);
				$cek = $this->db->query("SELECT * FROM tr_invoice WHERE no_sl = '$importdata[5]' AND no_faktur = '$importdata[0]' AND id_tipe_kendaraan = '$importdata[7]' AND id_warna = '$importdata[8]'");
				if($cek->num_rows() == 0){
					// $sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$importdata[6]'");
					// if($sipb->num_rows() > 0){
						$this->db->trans_begin();
						$this->db->insert('tr_invoice', $row);
						if(!$this->db->trans_status()){
							$this->db->trans_rollback();
						}else{
							$this->db->trans_commit();
						}
						$no2++;
					// }else{
					// 	if($cek_sipb==""){
					// 		$cek_sipb = $jum2;
					// 	}else{
					// 		$cek_sipb = $cek_sipb.",".$jum2;
					// 	}
					// 	$no3++;
					// }
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
			$_SESSION['pesan'] 	= $jum." Data yang anda import, sebanyak ".$no2." berhasil, ".$no1." gagal import (".$isi.")"; 
			//dan tidak ditemukan No SIPB di database sistem sebanyak ".$no3." item (".$cek_sipb.")";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice'>";	
		}elseif($size > 0 AND $name_r[1] == 'KINV'){
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
					'disc_other'       =>  !empty($importdata[15])?$importdata[15]:'',
					'status' => 'waiting'					
				);
				$cek = $this->db->query("SELECT * FROM tr_invoice WHERE no_sl = '$importdata[5]' AND no_faktur = '$importdata[0]' AND id_tipe_kendaraan = '$importdata[7]' AND id_warna = '$importdata[8]'");
				if($cek->num_rows() == 0){
					// $sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$importdata[6]'");
					// if($sipb->num_rows() > 0){
						$this->db->trans_begin();

						//$this->db->insert('tr_invoice', $row);						
						$this->db->where('no_sl', $importdata[5]);						
						$this->db->where('no_faktur', $importdata[0]);						
						$this->db->where('id_tipe_kendaraan', $importdata[7]);						
						$this->db->where('id_warna', $importdata[8]);						
						$this->db->update('tr_invoice', $row);

						if(!$this->db->trans_status()){
							$this->db->trans_rollback();
						}else{
							$this->db->trans_commit();
						}
						$no2++;
					// }else{
					// 	if($cek_sipb==""){
					// 		$cek_sipb = $jum2;
					// 	}else{
					// 		$cek_sipb = $cek_sipb.",".$jum2;
					// 	}
					// 	$no3++;
					// }
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
			$_SESSION['pesan'] 	= $jum." Data yang anda import, sebanyak ".$no2." berhasil, ".$no1." gagal import (".$isi.")";
			//dan tidak ditemukan No SIPB di database sistem sebanyak ".$no3." item (".$cek_sipb.")";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice'>";	
		}				
  }
}