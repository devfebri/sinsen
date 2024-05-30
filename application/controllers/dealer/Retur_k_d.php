<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_k_d extends CI_Controller {

    var $tables =   "tr_retur_konsumen";	
		var $folder =   "dealer";
		var $page		=		"retur_k_d";
    var $pk     =   "no_retur_konsumen";
    var $title  =   "Retur Unit Konsumen ke Dealer";

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
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_retur_k_d'] = $this->db->query("SELECT * FROM tr_retur_konsumen INNER JOIN ms_tipe_kendaraan ON tr_retur_konsumen.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_retur_konsumen.id_warna = ms_warna.id_warna where id_dealer='$id_dealer'");				
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function cari_id(){		
		$tahun 					= date("Y");
		$id_dealer 			= $this->m_admin->cari_dealer();
		$get_d = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();		
		$pr_num 				= $this->db->query("SELECT * FROM tr_retur_konsumen WHERE id_dealer = '$id_dealer' ORDER BY no_retur_konsumen DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pisah = explode("/", $row->no_retur_konsumen);
			$id = $pisah[0] + 1;
			$kode = $id."/RETUR/".$get_d->kode_dealer_md."/".$tahun;						
		}else{
			$kode = "1/RETUR/".$get_d->kode_dealer_md."/".$tahun;						
		} 	
		echo $kode;
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$this->template($data);										
	}
	
	public function cari_nosin()
	{		
		$no_mesin	= $this->input->post('no_mesin');	
		// $dt_so		= $this->db->query("SELECT tr_scan_barcode.no_rangka,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm,
		// 			ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna,ms_warna.warna,tr_spk.tahun_rakitan,tr_sales_order.tgl_cetak_invoice,tr_prospek.nama_konsumen,tr_spk.alamat,tr_spk.no_hp
		// 			FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
		// 			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
		// 			INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
		// 			INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
		// 			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
		// 			WHERE tr_sales_order.no_mesin = '$no_mesin'");		
		$dt_so		= $this->db->query("SELECT tr_scan_barcode.no_rangka,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm,
					ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna,ms_warna.warna,tr_sales_order.tgl_cetak_invoice,tr_prospek.nama_konsumen,tr_spk.alamat,tr_spk.no_hp,tr_fkb.tahun_produksi as tahun_rakitan
					FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
					INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
					INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
					INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
					INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
					INNER JOIN tr_fkb on tr_sales_order.no_mesin=tr_fkb.no_mesin_spasi
					WHERE tr_sales_order.no_mesin = '$no_mesin'");								
		if($dt_so->num_rows() > 0){
			$da = $dt_so->row();	
			echo "ok|".$da->no_rangka."|".$da->id_item."|".$da->id_tipe_kendaraan."|".$da->tipe_ahm."|".$da->id_warna."|".$da->warna."|".$da->tahun_rakitan."|".$da->tgl_cetak_invoice."|".$da->nama_konsumen."|".$da->no_hp."|".$da->alamat;
		}else{
			echo "Data tidak ditemukan";
		}		
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
			$data['id_dealer']         = $this->m_admin->cari_dealer();
			$data['no_retur_konsumen'] = $this->input->post('no_retur_konsumen');
			$data['no_mesin']          = $this->input->post('no_mesin');	
			$data['no_rangka']         = $this->input->post('no_rangka');	
			$data['tgl_retur']         = $this->input->post('tgl_retur');	
			$data['id_item']           = $this->input->post('id_item');	
			$data['id_tipe_kendaraan'] = $this->input->post('id_tipe_kendaraan');	
			$data['id_warna']          = $this->input->post('id_warna');	
			$data['tgl_beli']          = $this->input->post('tgl_beli');	
			$data['tahun_produksi']    = $this->input->post('tahun_produksi');	
			$data['nama_konsumen']     = $this->input->post('nama_konsumen');	
			$data['alamat']            = $this->input->post('alamat');	
			$data['no_hp']             = $this->input->post('no_hp');	
			$data['status_retur_k']    = "input";
			$data['created_at']        = $waktu;		
			$data['created_by']        = $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/retur_k_d/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function approve()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->get("id");
		$id_				= $this->input->get($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['status_retur_k'] 	= "approved";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);

			$r = $this->m_admin->getByID($tabel,$pk,$id)->row();
			$this->db->query("UPDATE tr_sales_order SET status_so = 'retur' WHERE no_mesin = '$r->no_mesin'");
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/retur_k_d'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
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
			$data['status_retur_k'] 	= "rejected";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/retur_k_d'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}