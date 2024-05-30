<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_outstanding_ksu extends CI_Controller {

    var $tables =   "tr_prospek";	
	var $folder =   "dealer";
	var $page	=	"monitoring_outstanding_ksu";
    var $pk     =   "id_prospek";
    var $title  =   "Pemenuhan Outstanding KSU Dari MD";

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
		$login_id		= $this->session->userdata('id_user');
		$id_dealer 		= $this->db->query("
						SELECT id_dealer FROM ms_user INNER JOIN ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = ms_user.id_karyawan_dealer WHERE id_user='$login_id'")->row()->id_dealer;
		$data['dt_prospek'] = $this->m_admin->getAll($this->tables);		
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");
		$data['dt_monitoring_outstanding_ksu'] =$this->db->query("SELECT DISTINCT(tr_surat_jalan_ksu.no_surat_jalan) AS no_sj,tr_surat_jalan_ksu_pl.no_pl_ksu, 
                      tr_surat_jalan_ksu_pl.tgl_pl_ksu,tr_surat_jalan_ksu.no_do,tr_do_po.tgl_do,ms_dealer.nama_dealer
                      FROM tr_surat_jalan_ksu_pl 
                      INNER JOIN tr_surat_jalan_ksu ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan_ksu_pl.no_surat_jalan                      
                      INNER JOIN tr_do_po ON tr_surat_jalan_ksu.no_do = tr_do_po.no_do
                      INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
                      WHERE tr_surat_jalan_ksu.qty < tr_surat_jalan_ksu.qty_do	
                      AND tr_do_po.id_dealer = '$id_dealer'

                      ");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	public function konfirmasi()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Konfirmasi ".$this->title;															
		$data['set']		= "konfirmasi";				
		$id 						= $this->input->get("id");
		$pl 						= $this->input->get("pl");
		$data['pl']			= $pl;				
		$data['sj']			= $id;				
		$data['tgl_sj']			= $this->input->get("tgl");				
		$data['dt_mo']  = $this->db->query("SELECT tr_mon_ksu_detail.id_ksu,ms_ksu.ksu,tr_mon_ksu_detail.qty_do,tr_mon_ksu_detail.qty_penuh,tr_mon_ksu_detail.qty_konfirmasi
                      FROM tr_mon_ksu_detail
                      INNER JOIN ms_ksu ON tr_mon_ksu_detail.id_ksu = ms_ksu.id_ksu                      
                      WHERE tr_mon_ksu_detail.no_pl_ksu = '$pl'");
		$this->template($data);			
	}	

	public function save_ksu_konfirmasi_penerimaan(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_ksu 		= $this->input->post('id_ksu');		
		$no_pl_ksu 	= $this->input->post('no_pl_ksu');		

		$data['no_pl_ksu'] 	= $no_pl_ksu;
		$data['updated_at'] = $waktu;
		$data['updated_by'] = $login_id;
		$data['status_mon'] = "diterima";
		$this->m_admin->update('tr_mon_ksu',$data,"no_pl_ksu",$no_pl_ksu);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/monitoring_outstanding_ksu'>";
	
	}

	public function t_pu(){
		$id = $this->input->post('id_penerimaan_unit');
		$dq = "SELECT * FROM tr_penerimaan_unit_detail
						WHERE id_penerimaan_unit = '$id'";
		$data['dt_pu'] = $this->db->query($dq);		
		$this->load->view('dealer/t_pu',$data);
	}
	
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_karyawan'] = $this->m_admin->getSortCond("ms_karyawan_dealer","nama_lengkap","ASC");								
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan","kelurahan","ASC");								
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama","id_agama","ASC");								
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan","id_pendidikan","ASC");								
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan","id_pekerjaan","ASC");								
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan","pengeluaran","ASC");								
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");								
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");								
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");								
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp","status_hp","ASC");								
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");								
		$data['dt_no_mesin'] = $this->m_admin->getSort("tr_scan_barcode","no_mesin","ASC");								
		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode","no_rangka","ASC");								
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$this->template($data);										
	}
	public function cari_id(){
		
		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$dealer 			= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek ORDER BY id_prospek DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_prospek)-11;
			$id 	= substr($row->id_prospek,$pan,11)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."0000".$id;          
		    }elseif($id > 9 && $id <= 99){
				$kode1 = $th.$bln.$tgl."000".$id;                    
		    }elseif($id > 99 && $id <= 999){
				$kode1 = $th.$bln.$tgl."00".$id;          					          
		    }elseif($id > 999){
				$kode1 = $th.$bln.$tgl."0".$id;                    
		    }
			$kode = $kode_dealer.$kode1;
		}else{
			$kode = $kode_dealer.$th.$bln.$tgl."00001";
		} 	

		$rt = rand(1111,9999);
		echo $kode."|".$rt;
	}
	public function take_sales()
	{		
		$id_karyawan_dealer	= $this->input->post('id_karyawan_dealer');	
		$dt_eks				= $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer = '$id_karyawan_dealer'");								
		if($dt_eks->num_rows() > 0){
			$da = $dt_eks->row();
			$kode = $da->id_flp_md;
			$nama = $da->nama_lengkap;
		}else{
			$kode = "";
			$nama = "";
		}
		echo $kode."|".$nama;
	}
	public function save_pu(){
		$id_penerimaan_unit		= $this->input->post('id_penerimaan_unit');			
		$no_shipping_list			= $this->input->post('no_shipping_list');			
		$data['id_penerimaan_unit']		= $this->input->post('id_penerimaan_unit');			
		$data['no_shipping_list']			= $this->input->post('no_shipping_list');
		$c = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id_penerimaan_unit' AND no_shipping_list = '$no_shipping_list'");
		if($c->num_rows() > 0){
			echo "no";
		}else{
			$cek2 = $this->m_admin->insert("tr_penerimaan_unit_detail",$data);						
			echo "ok";
		}							
	}	
	public function delete_pu(){
		$id = $this->input->post('id_penerimaan_unit_detail');		
		$this->db->query("DELETE FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit_detail = '$id'");			
		echo "nihil";
	}	
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_penerimaan_unit'] 	= $this->input->post('id_penerimaan_unit');
			$data['no_antrian'] 					= $this->input->post('no_antrian');	
			$data['no_surat_jalan'] 			= $this->input->post('no_surat_jalan');	
			$data['tgl_surat_jalan'] 			= $this->input->post('tgl_surat_jalan');	
			$data['ekspedisi'] 						= $this->input->post('ekspedisi');	
			$data['no_polisi'] 						= $this->input->post('no_polisi');	
			$data['nama_driver'] 					= $this->input->post('nama_driver');	
			$data['no_telp'] 							= $this->input->post('no_telp');	
			$data['gudang'] 							= $this->input->post('gudang');	
			$data['tgl_penerimaan'] 			= $this->input->post('tgl_penerimaan');	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/penerimaan_unit/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cetak_striker()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak Ulang Stiker";	
		$no_shipping_list 	= $this->input->get("id");	
		$data['set']		= "cetak";
		$data['dt_shipping_list'] = $this->db->query("SELECT * FROM tr_shipping_list INNER JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna 
					WHERE tr_shipping_list.no_shipping_list = '$no_shipping_list'");				
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");								
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function list_ksu(){
		$data['isi']    = $this->page;		
		$data['title']	= "List KSU";															
		$data['set']	= "list_ksu";
		//$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);										

	}
}