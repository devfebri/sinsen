<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_tagihan extends CI_Controller {

    var $tables =   "tr_rekap_tagihan";	
		var $folder =   "h1";
		var $page		=		"rekap_tagihan";
		var $isi		=		"invoice_terima";
    var $pk     =   "id_rekap_tagihan";
    var $title  =   "Rekap Tagihan";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_rekap_tagihan_datatables');	
		
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
		$data['set']		= "view";			
		// $data['dt_rekap']	= $this->db->query("SELECT * FROM tr_rekap_tagihan INNER JOIN ms_vendor ON tr_rekap_tagihan.id_vendor = ms_vendor.id_vendor
		// 		ORDER BY tr_rekap_tagihan.id_rekap_tagihan DESC limit 50");	
		$this->template($data);			
	}

	function mata_uang2($a){
        if(is_numeric($a) AND $a != 0 AND $a != ""){
          return number_format($a, 0, ',', '.');
        }else{
          return $a;
        }        
    }

	public function fetch_data_rekap_tagihan()
	{

		$list = $this->m_rekap_tagihan_datatables->get_datatables();
		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {       
			if (!empty($row->id_rekap_tagihan)) {
				$button_id_program ="<a href='h1/rekap_tagihan/detail?id=$row->id_rekap_tagihan' >$row->id_rekap_tagihan</a>";
			}

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[]  =$button_id_program;
			$rows[] = $row->tgl_rekap;
			$rows[] = $row->vendor_name;
			$rows[] = $row->tgl_awal." s/d ".$row->tgl_akhir;
			$rows[] = $this->mata_uang2($row->total);
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_rekap_tagihan_datatables->count_all(),
			"recordsFiltered" => $this->m_rekap_tagihan_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
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
		$id_rekap_tagihan = $this->input->get('id');
		$data['dt_rekap'] = $this->db->query("SELECT * FROM tr_rekap_tagihan INNER JOIN ms_vendor ON tr_rekap_tagihan.id_vendor=ms_vendor.id_vendor 
				WHERE tr_rekap_tagihan.id_rekap_tagihan = '$id_rekap_tagihan'");					
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
		$data['dt_rekap'] = $this->db->query("SELECT * FROM tr_penerimaan_unit 
				LEFT JOIN tr_invoice_penerimaan ON tr_penerimaan_unit.id_penerimaan_unit = tr_invoice_penerimaan.no_penerimaan
				WHERE tr_penerimaan_unit.ekspedisi = '$id_vendor' AND tr_penerimaan_unit.tgl_penerimaan BETWEEN '$tanggal' AND '$tanggal1'
				AND tr_penerimaan_unit.status = 'close'
				AND (tr_penerimaan_unit.rekap IS NULL OR tr_penerimaan_unit.rekap <> 'ya')");		 
		$this->load->view('h1/t_rekap_tagihan',$data);
	}	
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_rekap_tagihan ORDER BY id_rekap_tagihan DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_rekap_tagihan)-3;
			$id 	= substr($row->id_rekap_tagihan,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/RKP/".$isi;
		}else{
			$kode = $th.$bln."/RKP/00001";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$id_rekap_tagihan 			= $this->cari_id();
		$da['id_rekap_tagihan'] = $id_rekap_tagihan;
		$da['id_vendor'] 				= $this->input->post("id_vendor");
		$da['tgl_rekap'] 				= $tgl;
		$da['tgl_awal'] 				= $this->input->post("periode_awal");
		$da['tgl_akhir'] 				= $this->input->post("periode_akhir");
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_".$i])){
				$id_penerimaan_unit 			= $_POST["id_penerimaan_unit_".$i];			
				$data['id_penerimaan_unit'] = $id_penerimaan_unit;
				$data['id_rekap_tagihan'] 		= $id_rekap_tagihan;

				$this->db->query("UPDATE tr_penerimaan_unit SET rekap = 'ya' WHERE id_penerimaan_unit = '$id_penerimaan_unit'");										

				$cek = $this->db->query("SELECT * FROM tr_rekap_tagihan_detail WHERE id_rekap_tagihan = '$id_rekap_tagihan' AND id_penerimaan_unit = '$id_penerimaan_unit'");
				if($cek->num_rows() > 0){						
					$t = $cek->row();
					$this->m_admin->update("tr_rekap_tagihan_detail",$data,"id_rekap_tagihan_detail",$t->id_rekap_tagihan_detail);								
				}else{
					$this->m_admin->insert("tr_rekap_tagihan_detail",$data);								
				}
			}			
		}
			
		$ce = $this->db->query("SELECT * FROM tr_rekap_tagihan WHERE id_rekap_tagihan = '$id_rekap_tagihan'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_rekap_tagihan",$da,"id_rekap_tagihan",$id_rekap_tagihan);								
		}else{
			$this->m_admin->insert("tr_rekap_tagihan",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rekap_tagihan'>";
	}
	public function delete(){
		$id = $this->input->get("id");
		$dt = $this->m_admin->getByID("tr_rekap_tagihan_detail","id_rekap_tagihan",$id);
		foreach ($dt->result() as $row) {
			$this->db->query("UPDATE tr_penerimaan_unit SET rekap = '' WHERE id_penerimaan_unit = '$row->id_penerimaan_unit'");										
		}
		$this->db->query("DELETE FROM tr_rekap_tagihan WHERE id_rekap_tagihan = '$id'");			
		$this->db->query("DELETE FROM tr_rekap_tagihan_detail WHERE id_rekap_tagihan = '$id'");					
		$_SESSION['pesan'] 	= "Data has been deleted successfully";
		$_SESSION['tipe'] 	= "danger";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rekap_tagihan'>";
	}	

	public function penerimaan_unit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail Penerimaan Unit";	
		$data['page']   = $this->page;		
		
		$id =$data['id_penerimaan_unit']	= $this->input->get("id");	
		$data['set']		= "penerimaan_unit";		
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		$dq = "SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.deskripsi_ahm FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND tr_scan_barcode.status = '1' AND tr_scan_barcode.tipe = 'RFS'";
		$data['dt_rfs'] = $this->db->query($dq);

		$dqe = "SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.deskripsi_ahm FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND tr_scan_barcode.status = '1' AND (tr_scan_barcode.tipe = 'NRFS' OR tr_scan_barcode.tipe='PINJAMAN') ";
		$data['dt_nrfs'] = $this->db->query($dqe);		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

}