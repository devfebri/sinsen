<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_d_md extends CI_Controller {

    var $tables =   "tr_retur_dealer";	
		var $folder =   "dealer";
		var $page		=		"retur_d_md";
    var $pk     =   "no_retur_dealer";
    var $title  =   "Retur Unit Dealer ke Main Dealer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('PDF_HTML');
		$this->load->library('mpdf_l');		
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
		$data['set']		= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_retur'] = $this->db->query("SELECT * FROM tr_retur_dealer WHERE id_dealer = '$id_dealer' ORDER BY no_retur_dealer DESC");						
		$this->template($data);	
	}
	public function cari_id(){		
		$tahun 					= date("Y");
		$id_dealer 			= $this->m_admin->cari_dealer();
		//$id_dealer 			= '18';
		$get_d = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();	
		
		if($tahun > '2021'){
			$pr_num 				= $this->db->query("SELECT * FROM tr_retur_dealer WHERE id_dealer = '$id_dealer'  and left(created_at,4) = '$tahun' ORDER BY id_retur DESC LIMIT 0,1");						
		}else{	
			$pr_num 				= $this->db->query("SELECT * FROM tr_retur_dealer WHERE id_dealer = '$id_dealer' ORDER BY id_retur DESC LIMIT 0,1");						
		}
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pisah = explode("/", $row->no_retur_dealer);
			$id = $pisah[0] + 1;
			$kode = $id."/RETMD/".$get_d->kode_dealer_md."/".$tahun;						
		}else{
			$kode = "1/RETMD/".$get_d->kode_dealer_md."/".$tahun;						
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
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;		
		$data['set']		= "detail";					
		$id = $this->input->get("id");
		$data['dt_retur'] = $this->db->query("SELECT * FROM tr_retur_dealer WHERE no_retur_dealer = '$id'");						
		$this->template($data);										
	}
	public function t_data(){
		$id = $this->input->post('no_retur_d');
		// $dq = "SELECT tr_retur_konsumen.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_retur_dealer_detail.* FROM tr_retur_dealer_detail INNER JOIN tr_retur_konsumen ON tr_retur_dealer_detail.no_retur_konsumen=tr_retur_konsumen.no_retur_konsumen 
		// 	INNER JOIN ms_tipe_kendaraan ON tr_retur_konsumen.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 	INNER JOIN ms_warna ON tr_retur_konsumen.id_warna = ms_warna.id_warna
		// 	WHERE tr_retur_dealer_detail.no_retur_dealer = '$id'";
		$dq = "SELECT tr_scan_barcode.no_rangka,tr_scan_barcode.no_mesin,tr_scan_barcode.id_item,tr_fkb.tahun_produksi, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_retur_dealer_detail.* FROM tr_retur_dealer_detail
			LEFT JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
			LEFT JOIN tr_fkb ON tr_scan_barcode.no_mesin = tr_fkb.no_mesin_spasi
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			WHERE tr_retur_dealer_detail.no_retur_dealer = '$id'";
		$data['dt_data'] = $this->db->query($dq);		
		$this->load->view('dealer/t_retur_dealer',$data);
	}

	
	public function cek_nosin()
	{		
		$no_mesin	= $this->input->post('no_mesin');	
		// $dt_so		= $this->db->query("SELECT * FROM tr_retur_konsumen 
		// 		INNER JOIN ms_tipe_kendaraan ON tr_retur_konsumen.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 		INNER JOIN ms_warna ON tr_retur_konsumen.id_warna = ms_warna.id_warna WHERE tr_retur_konsumen.no_mesin = '$no_mesin'");								
		$dt_so = $this->db->query("SELECT tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna,tr_penerimaan_unit_dealer.tgl_penerimaan,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail
            INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
            INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
            INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan             
            INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$no_mesin'");
		if($dt_so->num_rows() > 0){
			$da = $dt_so->row();			
			$cek_tahun = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$no_mesin);
				if($cek_tahun->num_rows() > 0){
					$tahun_produksi = $cek_tahun->row()->tahun_produksi;
				}else{
					$tahun_produksi = "";
				}
				
			echo "ok|".$da->no_rangka."|".$da->tipe_ahm."|".$da->warna."|".$tahun_produksi."|".$da->tgl_penerimaan."|".$da->id_item."|";		
		}else{
			echo "Data tidak ditemukan";
		}		
	}
	public function save_data(){
		//$no_retur_konsumen						= $this->input->post('no_retur_k');			
		$no_retur_dealer							= $this->input->post('no_retur_d');				
		$data['keterangan']						= $this->input->post('keterangan');					
		$data['no_retur_dealer']			= $this->input->post('no_retur_d');					
		$data['no_mesin']  = $no_mesin			= $this->input->post('no_mesin');					
		//$data['no_retur_konsumen']		= $no_retur_konsumen;					
		$c = $this->db->query("SELECT * FROM tr_retur_dealer_detail WHERE no_retur_dealer = '$no_retur_dealer' AND no_mesin = '$no_mesin'");
		if($c->num_rows() > 0){
			$r = $c->row();
			$cek2 = $this->m_admin->update("tr_retur_dealer_detail",$data,"no_retur_dealer_detail",$r->no_retur_dealer_detail);						
		}else{
			$cek2 = $this->m_admin->insert("tr_retur_dealer_detail",$data);						
			echo "nihil";
		}							
	}
	public function delete_data(){
		$id = $this->input->post('id_retur_dealer_detail');		
		$this->db->query("DELETE FROM tr_retur_dealer_detail WHERE id_retur_dealer_detail = '$id'");			
		echo "nihil";
	}
	
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_dealer'] 					= $this->m_admin->cari_dealer();
			$data['no_retur_dealer'] 		= $this->input->post('no_retur_d');
			$data['status_retur_d'] 		= "input";			
			$data['tgl_retur'] 					= $tgl;			
			$data['created_at']					= $waktu;		
			$data['created_by']					= $login_id;	
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/retur_d_md/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	public function cetak(){
		$id 				= $this->input->get('id');				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$dt_retur 	= $this->db->query("SELECT * FROM tr_retur_dealer INNER JOIN tr_retur_dealer_detail ON tr_retur_dealer_detail.no_retur_dealer = tr_retur_dealer.no_retur_dealer
					INNER JOIN ms_dealer ON tr_retur_dealer.id_dealer = ms_dealer.id_dealer
					WHERE tr_retur_dealer.no_retur_dealer = '$id'");
		if ($dt_retur->num_rows()>0) {			
			$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] = 'cetak_retur';    	    	
    	$data['isi_file'] = $dt_retur->row();
    	$data['tanggal'] = date('Y-m-d');
    	$html = $this->load->view('dealer/cetak_retur', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_.pdf';
      $mpdf->Output("$output", 'I');
		}
  } 

}