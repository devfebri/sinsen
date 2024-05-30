<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi_penyimpanan extends CI_Controller {

    var $tables =   "tr_penerimaan_unit_dealer";	
		var $folder =   "dealer";
		var $page		=		"lokasi_penyimpanan";
    var $pk     =   "id_penerimaan_unit_dealer";
    var $title  =   "Data Lokasi Penyimpanan";

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
		$this->load->library('cart');


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
		$this->gudang_show();
	}
	
	public function get_kode_gudang()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		$get_data  = $this->db->query("SELECT * FROM ms_gudang_dealer
			WHERE id_dealer='$id_dealer'
			AND kode_gudang IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$kode_gudang = substr($row->kode_gudang, -3);
				$new_kode   = $dealer->kode_dealer_md.'/WHS-'.sprintf("%'.03d",$kode_gudang+1);
	   		}else{
				$new_kode   = $dealer->kode_dealer_md.'/WHS-001';
	   		}
   		return strtoupper($new_kode);
	}

	public function gudang_show()
	{				
		$data['isi']    	= $this->page;		
		$data['title']		= "Data Lokasi Penyimpanan";		
		$data['set']	   	= "gudang_show_on_menu_lokasi_penyimpanan";							
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);	
	}
	public function gudang_save()
	{		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		
		$id_s                = $this->input->post('id');
		$data['gudang']      = $this->input->post('gudang');
		$data['kapasitas']   = $this->input->post('kapasitas');	
		$data['jenis']       = $this->input->post('jenis');	
		$data['kode_gudang'] = $this->get_kode_gudang();	
		$data['kode_rak']    = $this->input->post('kode_rak');	
		$data['kode_bin']    = $this->input->post('kode_bin');	
		$data['id_dealer'] = $this->m_admin->cari_dealer();				
		if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
			else $data['active'] 		= "";					
		$data['created_at']				= $waktu;		
		$data['created_by']				= $login_id;	
		$this->m_admin->insert("ms_gudang_dealer",$data);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpanan'>";		
	}
	public function gudang_edit()
	{		
		$tabel		= "ms_gudang_dealer";
		$pk 			= "id_gudang_dealer";		
		$id 			= $this->input->get('id');
		$idg 			= $this->input->get('idg');		
		$data['dt_gudang'] = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_gudang_dealer = '$idg'");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "gudang_edit";									
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);	
	}
	public function gudang_update()
	{		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->post("idg");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$id_s                = $this->input->post('id');
			$data['gudang']      = $this->input->post('gudang');
			$data['kapasitas']   = $this->input->post('kapasitas');
			$data['jenis']       = $this->input->post('jenis');	
			// $data['kode_gudang'] = $this->input->post('kode_gudang');	
			$data['kode_rak']    = $this->input->post('kode_rak');	
			$data['kode_bin']    = $this->input->post('kode_bin');	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update("ms_gudang_dealer",$data,"id_gudang_dealer",$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpanan'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function gudang_delete()
	{		
		$tabel		= "ms_gudang_dealer";
		$pk 			= "id_gudang_dealer";
		$id_s 		= $this->input->get('id');		
		$idg 			= $this->input->get('idg');		
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$idg);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$idg));
			$this->db->trans_commit();			
			$result = 'Success';									

			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpananu/gudang?id=".$id_s."'>";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpanan'>";
		}
	}	
	public function unit()
	{						
		$id								= $this->input->get('id');		
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan Unit";		
		$data['set']	   	= "unit";					
		$data['dt_item'] 	= $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$data['dt_pu']		= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
						INNER JOIN tr_do_po ON tr_sppm.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_surat_jalan.id_surat_jalan = '$id'");		    
    $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");    
		$this->template($data);										
	}
	public function list_data()
	{				
		$id								= $this->input->get('id');		
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan Unit";		
		$data['set']	   	= "list";					
		$data['dt_item'] 	= $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$data['dt_pu']		= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
						INNER JOIN tr_do_po ON tr_sppm.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_surat_jalan.id_surat_jalan = '$id'");		
    
    $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);										
	}
	public function cari_id(){
		$no_sj					= $this->input->post('no_sj');
		$th 						= date("Y");
		$waktu 					= gmdate("Y-m-d h:i:s", time()+60*60*7);		
		$t 							= gmdate("Y-m-d", time()+60*60*7);				
		$pr_num 				= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer ORDER BY id_penerimaan_unit_dealer DESC LIMIT 0,1");						
		
		$id_user 				= $this->session->userdata('id_user');
		$id_tok 				= $this->db->query("SELECT left(session_id,5) as token FROM ms_user WHERE id_user = '$id_user'");
		if($id_tok->num_rows() > 0){
			$tok = $id_tok->row();
			$token 					= $tok->token;
		}else{
			$token 					= "xxxxx";
		}
		
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_penerimaan_unit_dealer)-5;
			$id 	= substr($row->id_penerimaan_unit_dealer,$pan,10)+1;	
			if($id < 10){
					$kode1 = $th."0000".$id.$token;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th."000".$id.$token;          
      }elseif($id>99 && $id<=999){
					$koath."00".$id.$token;          
      }elseif($id>999){
					$kode1 = $th."0".$id.$token;          
      }
			$kode = "KU".$kode1;
		}else{
			$kode = "KU".$th."00001".$token;          
		} 	

		//cek transaksi sebelumnya
		$ambil = $this->db->query("SELECT * FROM tr_surat_jalan WHERE id_surat_jalan = '$no_sj'")->row();
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$ambil->no_surat_jalan'")->row();
		$cek2 = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$kode'")->row();
		if(isset($cek->id_penerimaan_unit_dealer)){			
			$kode3 = $cek->id_penerimaan_unit_dealer;			
			$kode2 = "nihil";
		}else{			
			if(isset($cek2->id_penerimaan_unit_dealer)){			
				$kode2 = $cek2->id_penerimaan_unit_dealer;
			}else{
				$kode2 = "nihil";
			}			
			$kode3 = "nihil";
		}

		//$kode3 = "ok";
		echo $kode."|".$kode2."|".$kode3;
	}
	public function t_data(){
		$id 			= $this->input->post('id_pu');
		$jenis_pu = $this->input->post('jenis_pu');					
		$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
		$data['jenis']  = $this->input->post('jenis_pu');		
		$data['no_sj']  = $this->input->post('no_sj');		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
    				WHERE tr_penerimaan_unit_dealer.no_surat_jalan = '$id'");
    if($cek->num_rows() > 0){
    	$tt = $cek->row();
    	$data['mode'] = 'view';
    }else{
    	$data['mode'] = 'input';
    }
		$this->load->view('dealer/t_lokasi_penyimpananu',$data);				
	}
	public function t_data_list(){
		$id 			= $this->input->post('id_pu');
		$jenis_pu = $this->input->post('jenis_pu');		
		$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
		$data['mode']  = "edit";			
		$data['jenis']  = $this->input->post('jenis_pu');		
		$data['no_sj']  = $this->input->post('no_sj');		
		$this->load->view('dealer/t_lokasi_penyimpananu',$data);				
	}
	public function cari_id_real($no_sj){		
		if(!empty($no_sj)){
			$sj = $no_sj;
		}else{
			$sj = "";
		}		
		$th 						= date("Y");
		$waktu 					= gmdate("Y-m-d h:i:s", time()+60*60*7);		
		$t 							= gmdate("Y-m-d", time()+60*60*7);				
		$cek 						= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$sj'");									
		$pr_num 				= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer ORDER BY id_penerimaan_unit_dealer DESC LIMIT 0,1");									
		
		if($cek->num_rows() == 0){
			if($pr_num->num_rows()>0){
				$row 	= $pr_num->row();				
				$pan  = strlen($row->id_penerimaan_unit_dealer)-5;
				$id 	= substr($row->id_penerimaan_unit_dealer,$pan,10)+1;	
				if($id < 10){
						$kode1 = $th."0000".$id;          
	      }elseif($id>9 && $id<=99){
						$kode1 = $th."000".$id;          
	      }elseif($id>99 && $id<=999){
						$koath."00".$id;          
	      }elseif($id>999){
						$kode1 = $th."0".$id;          
	      }
				$kode = "KU".$kode1;
			}else{
				$kode = "KU".$th."00001";          
			} 	
		}else{
			$r = $cek->row();
			$kode = $r->id_penerimaan_unit_dealer;
		}
		return $kode;
	}	
	public function save_nosin(){
		$no_mesin		= $this->input->post('no_mesin');
		$id_pu			= $this->input->post('id_pu');
		$waktu 			= date("y-m-d");
		$nosin_spasi  = substr_replace($no_mesin," ", 5, -strlen($no_mesin));
    $cek_th       = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin = '$nosin_spasi'");
    if($cek_th->num_rows() > 0){
      $amb_th       = $cek_th->row();
      $th_produksi  = $amb_th->tahun_produksi;
    }else{
      $th_produksi  = date('Y');
    }
    $fifo = $this->m_admin->cari_fifo_d($th_produksi);
    //$fifo = "918767822";


		$data['id_penerimaan_unit_dealer']	= $id_pu;
		$data['no_mesin']										= $no_mesin;
		$data['jenis_pu']										= $this->input->post("jenis_pu");
		$data['id_user']										= $this->session->userdata("id_user");
		$jenis_pu														= strtoupper($this->input->post("jenis_pu"));
		$data['fifo']												= $fifo;		
		$data['status_dealer']							= "input";		
		$data['id_user']										= $this->session->userdata("id_user");		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin='$no_mesin' AND id_penerimaan_unit_dealer = '$id_pu'");
		if($cek->num_rows() > 0){
			echo "sudah";
		}else{
			$this->m_admin->insert("tr_penerimaan_unit_dealer_detail",$data);									
			echo "ok";
		}		
		//$this->m_admin->update_stock($row->id_modell,$row->id_warna,"RFS",'+','1');
		
		
	}
	public function delete_data(){
		$id_pu 				= $this->input->post('id_pu');				
		$no_mesin 		= $this->input->post('no_mesin');				
		$mode 				= $this->input->post('mode');				
		
		$rt = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail","id_penerimaan_unit_dealer_detail",$id_pu)->row();			
		$jenis_pu = strtoupper($rt->jenis_pu);
		$this->db->query("UPDATE tr_surat_jalan_detail SET terima = '' WHERE no_mesin = '$no_mesin'");				
		$rs = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();			
		$id_item 	= $rs->id_item;
		$this->m_admin->update_stock_dealer($id_item,$jenis_pu,"-",1);		
		$this->db->query("DELETE FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer_detail = '$id_pu'");			
		
		
		echo "nihil";
	}
	public function hapus_auto(){
		$id = $this->input->post('id_p');		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id'");			
		foreach ($cek->result() as $val) {
			$this->db->query("UPDATE tr_surat_jalan_detail SET terima = '' WHERE no_mesin = '$val->no_mesin'");				
			$rt = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail","id_penerimaan_unit_dealer_detail",$id_pu)->row();			
			$rs = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$val->no_mesin)->row();			
			$jenis_pu = strtoupper($rt->jenis_pu);
			$id_item 	= $rs->id_item;
			$this->m_admin->update_stock_dealer($id_item,$jenis_pu,"-",1);
		}
	$cek = $this->db->query("DELETE FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id'");			
		echo "nihil";
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$id_dealer  = $this->m_admin->cari_dealer();
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		
			$no_sj 															= $this->input->post('no_surat_jalan');	
			$id_penerimaan_unit_dealer 					= $this->cari_id_real($no_sj);
			$id_pu 															= $this->input->post('id_penerimaan_unit_dealer');
			$data['id_penerimaan_unit_dealer'] 	= $id_penerimaan_unit_dealer;
			$data['no_surat_jalan'] 						= $no_sj;
			$data['tgl_surat_jalan'] 						= $this->input->post('tgl_surat');	
			$data['no_do'] 											= $this->input->post('no_do');	
			$data['id_dealer'] 									= $id_dealer;	
			$data['id_gudang_dealer'] 					= $this->input->post('id_gudang_dealer');	
			$data['tgl_penerimaan'] 						= $this->input->post('tgl_penerimaan');				
			$data['status']											= "input";			
			$data['created_at']									= $waktu;		
			$data['created_by']									= $login_id;	
			$mode																= $this->input->post("mode");

			
			$cek_tmp = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id_pu'");
			if($cek_tmp->num_rows() > 0){
				foreach ($cek_tmp->result() as $amb) {					
					$this->db->query("UPDATE tr_penerimaan_unit_dealer_detail SET id_penerimaan_unit_dealer='$id_penerimaan_unit_dealer' WHERE id_penerimaan_unit_dealer = '$id_pu'");
					$this->db->query("UPDATE tr_surat_jalan_detail SET terima = 'ya' WHERE no_mesin = '$amb->no_mesin'");				
					$this->db->query("UPDATE tr_scan_barcode SET status = '4' WHERE no_mesin = '$amb->no_mesin'");				
					$r = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$amb->no_mesin)->row();
					$this->m_admin->update_stock_dealer($r->id_item,$amb->jenis_pu,"+",1);										
				}
			}

			if($mode == 'new'){
				$this->m_admin->insert($tabel,$data);		
			}else{			
				$this->m_admin->update("tr_penerimaan_unit_dealer",$data,"id_penerimaan_unit_dealer",$id_penerimaan_unit_dealer);						
			}
				
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpananu'>";
		// }else{
		// 	$_SESSION['pesan'] 	= "Duplicate entry for primary key";
		// 	$_SESSION['tipe'] 	= "danger";
		// 	echo "<script>history.go(-1)</script>";
		// }
	}
	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_surat_jalan 		= $this->input->post('no_sj');				
		$id_sj 		= $this->input->post('id_sj');				
		$id_penerimaan_unit_dealer 		= $this->input->post('id_penerimaan_unit_dealer');				
		$cek = 0;
		foreach($no_surat_jalan AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$no_sj 		= $_POST['no_sj'][$key];
			//$id_item 	= $_POST['id_item'][$key];
			$qty_terima  	= $_POST['qty_terima'][$key];
		 	$qty_md  	= $_POST['qty_md'][$key];
			// $no_sl = $_POST['no_sl'][$key];
		 	$result[] = array(
				"id_penerimaan_unit_dealer"  => $id_penerimaan_unit_dealer,
				"id_ksu"  => $_POST['id_ksu'][$key],
				//"id_item"  => $_POST['id_item'][$key],
				"no_surat_jalan"  => $no_sj,
				"qty_md"  => $_POST['qty_md'][$key],
				"qty_terima"  => $_POST['qty_terima'][$key],
				"created_at"  => $waktu,
				"created_by"  => $login_id
		 	); 
		 	if($qty_md < $qty_terima){
		 		$cek = $cek + 1;		 		
		 	}

		 	$rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_ksu = '$id_ksu' AND no_surat_jalan = '$no_sj' AND id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer'");
      if($rty->num_rows() > 0){
      	$e = $rty->row();      	
      	$this->db->query("DELETE FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_ksu_dealer = '$e->id_penerimaan_ksu_dealer'");
      }

		}
		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty Penerimaan KSU tidak boleh lebih dari jumlah KSU yg di-supply oleh MD";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpananu/ksu?id=".$id_sj."'>";
		}else{			
      $test2 = $this->db->insert_batch('tr_penerimaan_ksu_dealer', $result);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpananu/ksu?id=".$id_sj."'>";
		}		
	}
	public function close(){
		$id_pu 			= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_surat_jalan";
		$pk					= "id_surat_jalan";		

		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$data['status']						= "close";	
		$this->m_admin->update($tabel,$data,$pk,$id_pu);
		//$this->db->query("UPDATE tr_penerimaan_unit SET status = 'close scan' WHERE id_penerimaan_unit = '$id_pu'");
		$_SESSION['pesan'] 	= "Status has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/lokasi_penyimpananu/'>";
	}
		
}