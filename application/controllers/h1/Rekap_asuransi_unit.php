<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_asuransi_unit extends CI_Controller {

    var $tables =   "tr_rekap_asuransi";	
		var $folder =   "h1";
		var $page		=		"rekap_asuransi_unit";
		var $isi		=		"invoice_terima";
    var $pk     =   "no_rekap_asuransi";
    var $title  =   "Rekap Asuransi Unit";

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
		$data['set']		= "view";				
		$data['dt_rekap']	= $this->db->query("SELECT * FROM tr_rekap_asuransi INNER JOIN ms_vendor ON tr_rekap_asuransi.id_vendor = ms_vendor.id_vendor
				ORDER BY tr_rekap_asuransi.id_rekap_asuransi DESC");	
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
		$id = $this->input->get("id");
		$data['dt_rekap']	= $this->db->query("SELECT * FROM tr_rekap_asuransi INNER JOIN ms_vendor ON tr_rekap_asuransi.id_vendor = ms_vendor.id_vendor
				WHERE tr_rekap_asuransi.id_rekap_asuransi = '$id'");	
		$data['dt_rekap2']	= $this->db->query("SELECT * FROM tr_rekap_asuransi_detail INNER JOIN ms_tipe_kendaraan ON tr_rekap_asuransi_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				WHERE tr_rekap_asuransi_detail.id_rekap_asuransi = '$id'");	
		$this->template($data);			
	}	
	public function t_data(){
		$id_vendor 	= $this->input->post('id_vendor');
		$tanggal 		= $this->input->post('tanggal');
		$tanggal1 	= $this->input->post('tanggal1');
		$data['presentase'] = $this->input->post('presentase');
		$data['id_vendor'] = $this->input->post('id_vendor');
		// $data['dt_rekap'] 	= $this->db->query("SELECT tr_invoice.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_invoice.harga,tr_invoice.qty,SUM(tr_invoice.qty * tr_invoice.harga) AS total
		// 						FROM tr_invoice 
		// 						LEFT JOIN ms_tipe_kendaraan ON tr_invoice.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 						LEFT JOIN tr_penerimaan_unit_detail ON tr_invoice.no_sl = tr_penerimaan_unit_detail.no_shipping_list
		// 						LEFT JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
		// 						WHERE tr_penerimaan_unit.tgl_penerimaan BETWEEN '$tanggal' AND '$tanggal1'
		// 						AND tr_penerimaan_unit.ekspedisi = '$id_vendor' AND tr_penerimaan_unit.status <> 'input'");		



		// $data['dt_rekap'] 	= $this->db->query("SELECT tr_invoice.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_invoice.harga,sum(tr_invoice.qty) as qty,SUM(tr_invoice.qty * tr_invoice.harga) AS total
		// 						FROM tr_invoice 
		// 						LEFT JOIN ms_tipe_kendaraan ON tr_invoice.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 						LEFT JOIN tr_penerimaan_unit_detail ON tr_invoice.no_sl = tr_penerimaan_unit_detail.no_shipping_list
		// 						LEFT JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
		// 						WHERE tr_penerimaan_unit.tgl_penerimaan BETWEEN '$tanggal' AND '$tanggal1'
		// 						AND tr_penerimaan_unit.status <> 'input' GROUP BY tr_invoice.id_tipe_kendaraan");		




		$data['dt_rekap'] 	= $this->db->query("
SELECT tr_invoice.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(tr_invoice.harga / tr_invoice.qty) AS harga,sum(tr_invoice.qty) as qty,SUM(tr_invoice.qty * tr_invoice.harga)as total  FROM tr_invoice LEFT JOIN ms_tipe_kendaraan ON tr_invoice.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan WHERE date_format(str_to_date(tr_invoice.tgl_faktur, '%d%m%Y'), '%Y%-%m%-%d') BETWEEN '$tanggal' AND '$tanggal1'GROUP BY tr_invoice.id_tipe_kendaraan


								");	

		$this->load->view('h1/t_rekap_asuransi',$data);
	}	
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_rekap_asuransi ORDER BY id_rekap_asuransi DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_rekap_asuransi)-3;
			$id 	= substr($row->id_rekap_asuransi,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/RKA/".$isi;
		}else{
			$kode = $th.$bln."/RKA/00001";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$id_rekap_asuransi 			= $this->cari_id();
		$da['id_rekap_asuransi'] = $id_rekap_asuransi;
		$da['id_vendor'] 				= $this->input->post("id_vendor");
		$da['tgl_rekap'] 				= $tgl;
		$da['tgl_awal'] 				= $this->input->post("periode_awal");
		$da['tgl_akhir'] 				= $this->input->post("periode_akhir");
		$da['presentase']				= $this->input->post("presentase");
		$da['total'] 						= $this->input->post("total");
		$da['premi_asuransi'] 	= $this->input->post("premi_asuransi");
		$da['biaya_polis'] 			= $this->input->post("biaya_polis");
		$da['biaya_materai'] 		= $this->input->post("biaya_materai");
		$da['total_bayar'] 			= $this->input->post("total_bayar");
		$da['status_rekap']			= "input";
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 			
			$id_tipe_kendaraan 					= $_POST["id_tipe_kendaraan_".$i];			
			$data['id_tipe_kendaraan'] 	= $id_tipe_kendaraan;
			$data['id_rekap_asuransi'] 	= $id_rekap_asuransi;
			$data['harga_satuan'] 			= $_POST["harga_satuan_".$i];			
			$data['qty'] 								= $_POST["qty_".$i];			
			$data['total'] 							= $_POST["total_".$i];			
			$data['qty_asuransi'] 			= $_POST["qty_asuransi_".$i];			
			$data['total_asuransi'] 		= $_POST["total_asuransi_".$i];			

			//$this->db->query("UPDATE tr_penerimaan_unit SET rekap = 'ya' WHERE id_penerimaan_unit = '$id_penerimaan_unit'");										

			$cek = $this->db->query("SELECT * FROM tr_rekap_asuransi_detail WHERE id_rekap_asuransi = '$id_rekap_asuransi' AND id_tipe_kendaraan = '$id_tipe_kendaraan'");
			if($cek->num_rows() > 0){						
				$t = $cek->row();
				$this->m_admin->update("tr_rekap_asuransi_detail",$data,"id_rekap_asuransi_detail",$t->id_rekap_asurasi_detail);								
			}else{
				$this->m_admin->insert("tr_rekap_asuransi_detail",$data);								
			}			
		}
			
		$ce = $this->db->query("SELECT * FROM tr_rekap_asuransi WHERE id_rekap_asuransi = '$id_rekap_asuransi'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_rekap_asuransi",$da,"id_rekap_asuransi",$id_rekap_asuransi);								
		}else{
			$this->m_admin->insert("tr_rekap_asuransi",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rekap_asuransi_unit'>";
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
}