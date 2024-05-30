<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fkb extends CI_Controller {
	var $tables =   "tr_fkb";	
	var $folder =   "h1";
	var $page		=		"fkb";
	var $pk     =   "no_mesin";
	var $title  =   "Faktur Kendaraan Bermotor dan Sertifikat NIK";
	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_fkb');		
		//===== Load Library =====
		$this->load->library('csvimport');
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
		$data['set']	= "view_fix";
		// $data['dt_penerimaan_unit'] = $this->m_admin->getAll($this->tables);		
		// $data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function ajax_list()
	{				
		$list = $this->m_fkb->get_datatables();		
		$data = array();
		//$no = $_POST['start'];
		foreach ($list as $isi) {
			
      $s = $this->db->query("SELECT * FROM tr_shipping_list WHERE no_shipping_list = '$isi->no_shipping_list'");
      if ($s->num_rows()>0)
      {
          $rs=$s->row();
      }
      $bulan = isset($rs)?substr($rs->tgl_sl, 2,2):'';
      $tahun = isset($rs)?substr($rs->tgl_sl, 4,4):'';
      $tgl = isset($rs)?substr($rs->tgl_sl, 0,2):'';      
      $tanggal = $tgl."-".$bulan."-".$tahun;			                


			//$row[] = $no;
			$row[] = $isi->no_mesin;
			$row[] = $isi->no_rangka;
			$row[] = $isi->kode_tipe;
			$row[] = $isi->nomor_faktur;
			$row[] = $isi->tahun_produksi;
			$row[] = $isi->harga_beli;
			$row[] = $isi->nama_kapal;
			$row[] = $isi->no_sipb;			
			$row[] = $isi->no_shipping_list;			
			$row[] = $tanggal;			
			$row[] = $isi->modell;			
			$row[] = $isi->isi_silinder;			
			$row[] = $isi->bahan_bakar;			
			$data[] = $row;			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_fkb->count_all(),
						"recordsFiltered" => $this->m_fkb->count_filtered(),
						"data" => $data,
				);
		//output to json format
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
		$tables = $this->tables;
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$filename = $_FILES["userfile"]["tmp_name"];
		$name 		= $_FILES["userfile"]["name"];
		$type 		= $_FILES["userfile"]["type"];
		$size 		= $_FILES["userfile"]["size"];
		$name_r   = explode('.', $name);
		if($size > 0 AND $name_r[1] == 'FM')
		{
			$file = fopen($filename,"r");
			$is_header_removed = FALSE;
			$no = array();$no1 = 0;$no2 = 0;$jum = 0;$jum1 = 1;$isi="";$no3=0;$isi_sl="";	        
			$isian = "";
			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
			{
	            // if(!$is_header_removed){
	            //     $is_header_removed = TRUE;
	            //     continue;
	            // }
				$row = array(
					'no_mesin'    		=>  !empty($importdata[0])?$importdata[0]:'',
					'no_rangka'    		=>  !empty($importdata[1])?$importdata[1]:'',
					'kode_tipe'         =>  !empty($importdata[2])?$importdata[2]:'',
					'kode_warna'        =>  !empty($importdata[3])?$importdata[3]:'',
					'kode_md'       	=>  !empty($importdata[4])?$importdata[4]:'',
					'nomor_faktur'      =>  !empty($importdata[5])?$importdata[5]:'',
					'tahun_produksi'    =>  !empty($importdata[6])?$importdata[6]:'',
					'harga_beli'        =>  !empty($importdata[7])?$importdata[7]:'',
					'no_ppud'       	=>  !empty($importdata[8])?$importdata[8]:'',
					'nama_kapal'       	=>  !empty($importdata[9])?$importdata[9]:'',
					'no_sipb'       	=>  !empty($importdata[10])?$importdata[10]:'',
					'no_shipping_list'  =>  !empty($importdata[11])?$importdata[11]:'',
					'no_pol_ekpedisi'   =>  !empty($importdata[12])?$importdata[12]:'',
					'kosong'    		=>  !empty($importdata[13])?$importdata[13]:'',
					'no_mesin_spasi'    =>  !empty($importdata[14])?$importdata[14]:'',
					'merk'       		=>  !empty($importdata[15])?$importdata[15]:'',                
					'jenis'       		=>  !empty($importdata[16])?$importdata[16]:'',                
					'modell'       		=>  !empty($importdata[17])?$importdata[17]:'',                
					'isi_silinder'      =>  !empty($importdata[18])?$importdata[18]:'',                
					'jum_silinder'      =>  !empty($importdata[19])?$importdata[19]:'',                
					'bahan_bakar'       =>  !empty($importdata[20])?$importdata[20]:'',                
					'no_form_a'         =>  !empty($importdata[21])?$importdata[21]:'',                
					'tgl_form_a'        =>  !empty($importdata[22])?$importdata[22]:'',                                
					'no_surat'          =>  !empty($importdata[23])?$importdata[23]:'',                                
					'no_sk'         	=>  !empty($importdata[24])?$importdata[24]:'',                                
					'file_name'         	=>  $name,
					'tgl_upload'         	=>  $waktu                                
				);
				
				if(isset($importdata[11])){
					$cek_sl = $this->db->query("SELECT * FROM tr_shipping_list WHERE no_shipping_list = '$importdata[11]'");
					if($cek_sl->num_rows() > 0){
						$cek = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin = '$importdata[0]' AND no_rangka = '$importdata[1]' AND kode_tipe = '$importdata[2]'");
						if($cek->num_rows() == 0){
							$this->db->trans_begin();
							$this->db->insert('tr_fkb', $row);
							if(!$this->db->trans_status()){
								$this->db->trans_rollback();
							}else{
								$this->db->trans_commit();
							}
							
							$no2++;
						}else{
							if($isi==""){
								$isi = $jum1;
							}else{
								$isi = $isi.",".$jum1;
							}
							$no1++;
						}
					}else{
						if($isi_sl == ""){
							$isi_sl = $name;
							$real = explode("_", $isi_sl);		
							$amb = explode(".", $real[1]);				
							$isian = $real[0]."".$amb[0].".SL";
						}else{
							$isian = $isian;
						}
						$no3++;
					}
				}
				$jum++;
				$jum1++;				
			}
			fclose($file);
			$_SESSION['pesan'] 	= $jum." Data yang anda import.<br> Berhasil = ".$no2." data,<br>
			Gagal = ".$no1." data (".$isi.").<br> 
			Tidak ditemukan SL = ".$no3." data (".$isian.") ";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/fkb'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/fkb'>";	
		}				
	}
}