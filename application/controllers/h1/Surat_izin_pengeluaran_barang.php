<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Surat_izin_pengeluaran_barang extends CI_Controller {
    var $tables =   "tr_indent";	
	var $folder =   "h1";
	var $page	=	"indent";
    var $pk     =   "id_indent";
    var $title  =   "Indent";
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
		$this->load->library('csvimport');
		$this->load->library('upload');
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
		$data['dt_indent'] = $this->m_admin->getAll($this->tables);		
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
		$tables = $this->tables;
		$filename = $_FILES["userfile"]["tmp_name"];
    	if($_FILES['userfile']['size'] > 0)
    	{
	        $file = fopen($filename,"r");
	        $is_header_removed = FALSE;
	        while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
	        {
	            if(!$is_header_removed){
	                $is_header_removed = TRUE;
	                continue;
	            }
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
	                'no_sk'         	=>  !empty($importdata[24])?$importdata[24]:''                                
	            );
	            $this->db->trans_begin();
	            $this->db->insert('tr_fkb', $row);
	            if(!$this->db->trans_status()){
	                $this->db->trans_rollback();
	                //$_SESSION['pesan'] 	= "Data gagal diimport";
	                //$_SESSION['pesan'] 	= $row;
	                $_SESSION['pesan'] 	= $importdata[0].",".$importdata[1].",".$importdata[2].",".$importdata[3].",".$importdata[4].",".$importdata[5].",".$importdata[6].",".$importdata[7].",".$importdata[8].",".$importdata[9].",".$importdata[10].",".$importdata[11].",".$importdata[23].",".$importdata[12].",".$importdata[13].",".$importdata[14].",".$importdata[15].",".$importdata[16].",".$importdata[17].",".$importdata[18].",".$importdata[19].",".$importdata[20].",".$importdata[21].",".$importdata[22];                                
					$_SESSION['tipe'] 	= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/fkb'>";	
	                break;
	            }else{
	                $this->db->trans_commit();
	                $_SESSION['pesan'] 	= "Data berhasil diimport";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/fkb'>";	
	            }
	        }
        	fclose($file);
    	}		
  	}
}