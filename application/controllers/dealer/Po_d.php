<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_d_ extends CI_Controller {

    var $tables =   "tr_po_dealer";	
		var $folder =   "dealer";
		var $page		=		"po_d";
    var $pk     =   "id_po";
    var $title  =   "Purchase Order (PO)";

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
		$id_user = $this->session->userdata("id_user");
		$sql = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_karyawan_dealer ON ms_user.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
              INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
              WHERE ms_user.id_user = '$id_user'")->row();
		$data['dt_po'] = $this->db->query("SELECT * FROM tr_po_dealer 
						WHERE tr_po_dealer.id_dealer = '$sql->id_dealer' ORDER BY tr_po_dealer.created_at DESC limit 100");	

		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND ms_tipe_kendaraan.active = 1");	
		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	public function t_po_reg(){
		$id = $this->input->post('id_po');
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.id_tipe_kendaraan, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan					
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_detail.id_po = '$id'";
		$dd = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.id_tipe_kendaraan, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan					
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_detail.id_po = '$id' GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan";

		$data['dt_po_reg'] = $this->db->query($dq);
		$data['tipe_reg'] = $this->db->query($dd);
		$data['mode']		= $this->input->post('mode');
		$data['id_dealer'] 			= $this->m_admin->cari_dealer();
		$this->load->view('dealer/t_po_reg',$data);
	}

	public function t_po_add(){
		$id = $this->input->post('id_po');
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_detail.id_po = '$id'";
		$data['dt_po_add'] = $this->db->query($dq);		
		$data['mode']		= $this->input->post('mode');
		$data['no_po'] = $this->cari_id_fix();
		$this->load->view('dealer/t_po_add',$data);
	}
	
	public function add()
	{				
		//$this->m_admin->reset_tmp("tr_po_dealer","tr_po_dealer_detail","id_po");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND ms_tipe_kendaraan.active = 1");						
		$th 						= date("Y");
		$bln 						= date("m");					
		$tgl 						= date("d");					
		$id_dealer 			= $this->m_admin->cari_dealer();
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln' AND tahun = '$th' AND id_dealer = '$id_dealer'");
		if($cek->num_rows() == 0 AND $tgl >= 1 AND $tgl <= 5){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$this->template($data);	
	}
	public function cari_id(){
		$kode = $this->m_admin->get_token(20);

		$kode2="nihil";
		$id_user = $this->session->userdata("id_user");
		$cek = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE id_user = '$id_user' AND id_po NOT IN (SELECT id_po FROM tr_po_dealer WHERE id_po IS NOT NULL)")->row();		
		if(isset($cek->id_po)){			
			$kode2 = $cek->id_po;						
		}

		echo $kode."|".$kode2;		
	}
	public function cari_id_fix(){			
		$th 						= date("Y");
		$bln 						= date("m");		
		$id_dealer = $this->m_admin->cari_dealer();
	 	$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");	
	 	if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		}else{
			$get_dealer ='';
			$panjang = '';
		}

		$pr_num 				= $this->db->query("SELECT * FROM tr_po_dealer WHERE RIGHT(id_po,$panjang) = '$get_dealer' and tahun ='$th' ORDER BY id_po DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_po)-($panjang+6);
			$id 	= substr($row->id_po,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th.$bln."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th.$bln."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $th.$bln."00".$id;          					          
      }elseif($id>999){
					$kode1 = $th.$bln."0".$id;                    
      }
			$kode = $kode1."-".$get_dealer;
		}else{
			$kode = $th.$bln."00001-".$get_dealer;
		}
		return $kode;
	}
	public function cek_item()
	{		
		$id_item = $this->input->post('id_item');
		$id_dealer = $this->m_admin->cari_dealer();
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$sql = $this->db->query("SELECT ms_item.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM ms_item INNER JOIN ms_tipe_kendaraan
							ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
							ON ms_item.id_warna=ms_warna.id_warna WHERE ms_item.id_item = '$id_item'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();
			$stok = $this->m_admin->getById("tr_stock","id_item",$id_item);
			if($stok->num_rows() > 0){
				$isi = $stok->row();
				$stok_isi = $isi->total_fix;
			}else{
				$stok_isi = 0;
			}
			
			$cek 	= $this->db->query("SELECT * FROM tr_niguri_dealer INNER JOIN tr_niguri_dealer_detail ON tr_niguri_dealer.id_niguri_dealer = tr_niguri_dealer_detail.id_niguri_dealer
										INNER JOIN ms_tipe_kendaraan ON tr_niguri_dealer_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_item ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
										WHERE tr_niguri_dealer.bulan = '$bulan' AND tr_niguri_dealer.tahun = '$tahun' AND ms_item.id_item = '$id_item'
										AND tr_niguri_dealer.id_dealer = '$id_dealer'");
			if($cek->num_rows() > 0){
				$is = $cek->row();
				$niguri = $is->a_fix;
			}else{
				$niguri = 0;
			}

			$bulan_1 = $bulan - 1;
			$po = $this->db->query("SELECT * FROM tr_po_dealer INNER JOIN tr_po_dealer_detail ON tr_po_dealer.id_po = tr_po_dealer_detail.id_po 
							WHERE  tr_po_dealer.bulan = '$bulan_1' AND tr_po_dealer.tahun = '$tahun' AND tr_po_dealer.id_dealer = '$id_dealer'");
			if($po->num_rows() > 0){
				$isi_po = $po->row();
				$po_fix = $isi_po->qty_po_t1;
				$po_t1 	= $isi_po->qty_po_t2;
				$po_t2 	= 0;				
			}else{
				$po_fix = 0;
				$po_t1 	= 0;
				$po_t2 	= 0;
			}
			
			echo "ok"."|".$dt_ve->id_item."|".$dt_ve->tipe_ahm."|".$dt_ve->warna."|".$stok_isi."|".$niguri."|".$po_fix."|".$po_t1."|".$po_t2;
		}else{
			echo "There is no data found!";
		}
	}
	public function cari_jenis()
	{				
		$th 						= $this->input->post('tahun');
		$bln 						= $this->input->post('bulan');
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			echo "PO Reguler"; 
		}else{
			echo "PO Additional"; 
		}
	}

	function download_file($id){
		
		//mkdir("downloads/hy",0777);
		$k = $this->session->userdata('id_karyawan_dealer');
		$bulan 		= gmdate("mY", time()+60*60*7);		
		$folder 	= "downloads/po/";
		$filename = $folder.$bulan;
		if (!file_exists($filename)) {
		  mkdir($folder.$bulan, 0777);		
		}
		if($id == ""){
			$sql = $this->db->query("SELECT * FROM tr_po_dealer ORDER BY id_po DESC LIMIT 0,1")->row();
		}else{
			$sql = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_po = '$id' ORDER BY id_po ASC")->row();			
		}		
		if($sql->jenis_po == 'PO Reguler'){
			$j = "F";
		}else{
			$j = "A";
		}
		$data['no'] = "AHM-E20-".$sql->id_po."-".$j;		
		$data['id'] = $id;		
		$data['k'] 	= $k;		
		
		//echo "<meta http-equiv='refresh' content='0; url=".base_url()."assets/get_data/upo.php?id=".$id."&k=".$k."&n=".$no."'>";			  
		$this->load->view("dealer/file_upo",$data);

	  //download_remote_file(base_url()."assets/get_data/upo.php?id=".$sql->id_po."&k=".$k, realpath("./".$filename)."/".$no.".UPO");	
	  //file_get_contents("C:\xampp\htdocs\web_honda\downloads\po\112017\AHM-E20-20171100001.UPO", base_url()."assets/get_data/upo.php?id=".$sql->id_po."&k=".$k);	
	}


	public function download()
	{
		$id = $this->input->get('id');		
		$this->download_file($id);
		
	}


	// public function save_po_reg(){
	// 	$id_po					= $this->input->post('id_po');			
	// 	$id_item				= $this->input->post('id_item');
	// 	$bulan 					= $this->input->post('bulan');
	// 	$tahun 					= $this->input->post('tahun');
	// 	$qty_po_fix							= $this->input->post('qty_po_fix');
	// 	$qty_po_t1							= $this->input->post('qty_po_t1');
	// 	$qty_po_t2							= $this->input->post('qty_po_t2');

	// 	$data['id_po']					= $this->input->post('id_po');			
	// 	$data['id_item']				= $this->input->post('id_item');			
	// 	$data['qty_po_t1']			= $this->input->post('qty_po_t1');			
	// 	$data['qty_po_t2']			= $this->input->post('qty_po_t2');							
	// 	$data['qty_po_fix']			= $this->input->post('qty_po_fix');					
	// 	$data['id_user']				= $this->session->userdata('id_user');

		

	// 	// if($qty_po_fix <= 22){
	// 	// 	if($qty_po_t1 <= $isi_t1_30){
	// 		$cek = $this->db->get_where("tr_po_dealer_detail",array("id_item"=>$id_item,"id_po"=>$id_po));
	// 		if($cek->num_rows() > 0){
	// 			$sq = $cek->row();
	// 			$id = $sq->id_po_detail;
	// 			$this->m_admin->update("tr_po_dealer_detail",$data,"id_po_detail",$id);			
	// 			echo "nihil";
	// 		}else{
	// 			$this->m_admin->insert("tr_po_dealer_detail",$data);			
	// 			echo "nihil";			
	// 		}
	// 	// 	}else{
	// 	// 		echo "po_t1";
	// 	// 	}
	// 	// }else{
	// 	// 	echo "po_fix";
	// 	// }
		
	// }

	public function save_po_reg(){
		$id_po					= $this->input->post('id_po');			
		$id_item				= $this->input->post('id_item');
		$bulan 					= $this->input->post('bulan');
		$tahun 					= $this->input->post('tahun');
		// $qty_po_fix							= $this->input->post('qty_po_fix');
		// $qty_po_t1							= $this->input->post('qty_po_t1');
		// $qty_po_t2							= $this->input->post('qty_po_t2');

		// $data['id_po']					= $this->input->post('id_po');			
		// $data['id_item']				= $this->input->post('id_item');			
		// $data['qty_po_t1']			= $this->input->post('qty_po_t1');			
		// $data['qty_po_t2']			= $this->input->post('qty_po_t2');							
		// $data['qty_po_fix']			= $this->input->post('qty_po_fix');					
		// $data['id_user']				= $this->session->userdata('id_user');
		$x 							= $this->input->post('qty');
		for ($i=0; $i < $x; $i++) { 
			$id_po = $data['id_po'] 			= $this->input->post('id_po');
			$id_item = $data['id_item'] 			= $this->input->post('id_item_'.$i);
			$data['qty_po_fix'] 			= $this->input->post('qty_po_fix_'.$i);
			$data['id_po'] 						= $this->input->post('id_po');
			$data['id_user']					= $this->session->userdata('id_user');

			$cek = $this->db->get_where("tr_po_dealer_detail",array("id_item"=>$id_item,"id_po"=>$id_po));
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_po_detail;
				$this->m_admin->update("tr_po_dealer_detail",$data,"id_po_detail",$id);			
			}else{
				$this->m_admin->insert("tr_po_dealer_detail",$data);			
			}
		}
		echo "nihil";
		

		// if($qty_po_fix <= 22){
		// 	if($qty_po_t1 <= $isi_t1_30){
			
		// 	}else{
		// 		echo "po_t1";
		// 	}
		// }else{
		// 	echo "po_fix";
		// }
		
	}


	public function save_po_add(){
		$id_po			= $this->input->post('id_po');			
		$id_item				= $this->input->post('id_item');
		$data['id_po']			= $this->input->post('id_po');			
		$data['id_item']				= $this->input->post('id_item');			
		$data['qty_order']			= $this->input->post('qty_order');		
		$data['id_user']				= $this->session->userdata('id_user');			
		$cek = $this->db->get_where("tr_po_dealer_detail",array("id_item"=>$id_item,"id_po"=>$id_po));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_po_detail;
			$this->m_admin->update("tr_po_dealer_detail",$data,"id_po_detail",$id);			
		}else{
			$this->m_admin->insert("tr_po_dealer_detail",$data);			
		}
		echo "nihil";
	}
	public function delete_po(){
		$id_item = $this->input->post('id_item');
		$id_po_detail 	= $this->input->post('id_po_detail');
		$this->db->query("DELETE FROM tr_po_dealer_detail WHERE id_po_detail = '$id_po_detail'");			
		echo "nihil";
	}
	public function cancel_po(){
		$id_po			= $this->input->post('id_po');			
		$this->m_admin->delete("tr_po_dealer","id_po",$id_po);
		$this->m_admin->delete("tr_po_dealer_detail","id_po",$id_po);
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
		$id_po 			= $this->input->post('id_po');
		$bulan 			= $this->input->post('bulan');	
		$tahun 			= $this->input->post('tahun');	
		$id_dealer 	= $this->input->post('id_dealer');
		$jenis_po 	= $this->input->post('jenis_po');
		$cek2				= $this->m_admin->getByID("tr_po_dealer_detail","id_po",$id_po)->num_rows();
		
		if($jenis_po == 'PO Additional'){
			$cek3 = $cek2;
		}else{			
			$cek3				= $this->db->query("SELECT COUNT(id_tipe_kendaraan) AS jum FROM tr_analisis_proyeksi_order INNER JOIN tr_analisis_proyeksi_order_detail 
					ON tr_analisis_proyeksi_order.id_analisis = tr_analisis_proyeksi_order_detail.id_analisis WHERE tr_analisis_proyeksi_order.bulan = '$bulan'
					AND tr_analisis_proyeksi_order.tahun = '$tahun' AND tr_analisis_proyeksi_order.id_dealer = '$id_dealer'")->rows()->jum;
		}


		if($cek == 0){
			if($cek2 > 0){
				if($cek3 == $cek2){
					$data['ket'] 		= $this->input->post('ket');	
					$data['bulan'] 	= $this->input->post('bulan');	
					$data['tahun'] 	= $this->input->post('tahun');	
					$data['jenis_po'] 	= $this->input->post('jenis_po');	
					$data['id_dealer'] 	= $this->input->post('id_dealer');	
					$data['id_pos_dealer'] 	= $this->m_admin->cari_pos_dealer();
					$data['status'] 	= "stay";	
					$data['tgl'] 		= $tgl;	
					if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
						else $data['active'] 		= "";					
					$data['created_at']				= $waktu;		
					$data['created_by']				= $login_id;	
					$id_po_fix			= $this->cari_id_fix();
					$id_po 					= $this->input->post('id_po');
					$data['id_po'] 	= $id_po_fix;
					$this->m_admin->insert($tabel,$data);

					$cek_detail = $this->m_admin->getByID("tr_po_dealer_detail","id_po",$id_po);
					foreach ($cek_detail->result() as $isi) {
						$this->db->query("UPDATE tr_po_dealer_detail SET id_po = '$id_po_fix' WHERE id_po = '$id_po'");
					}
					//$this->download_file($id_po);
					$_SESSION['pesan'] 	= "Data has been saved successfully";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/po_d'>";
				}else{
					$_SESSION['pesan'] 	= "Detail Tipe Kendaraan harus sesuai dengan Analisis Proyeksi MD";
					$_SESSION['tipe'] 	= "danger";
					echo "<script>history.go(-1)</script>";	
				}
			}else{
				$_SESSION['pesan'] 	= "Detail Tipe Kendaraan harus dipilih dulu";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');		
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									
			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";					
			}else{
				$this->m_admin->delete("tr_po_dealer_detail","id_po",$id);
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/po_d'>";				
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND ms_tipe_kendaraan.active = 1");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function detail()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND ms_tipe_kendaraan.active = 1");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']	= "detail";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_po'] 	= $this->input->post('id_po');
			$data['ket'] 		= $this->input->post('ket');	
			$data['bulan'] 	= $this->input->post('bulan');	
			$data['tahun'] 	= $this->input->post('tahun');				
			$data['jenis_po'] 	= $this->input->post('jenis_po');	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$id);
			//$this->download_file($id);

			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/po_d'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function send()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");
		$id_				= $this->input->get($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);
		if(($cek == 0 or $id == $id_) AND $cek_approval == 'aman'){					
			$data['status'] 	= "input";				
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$id);			

			$_SESSION['pesan'] 	= "Data has been sent successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/po_d'>";			
		}else{
			$_SESSION['pesan'] 	= "Failed, contact your administrator";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/po_d'>";
		}
	}

	public function detail_popup()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->post('id_po');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND ms_tipe_kendaraan.active = 1");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']	= "detail";									
		$this->load->view('dealer/t_po_detail', $data);	
	}

	public function getInputPoReg()
	{
		$data['id_dealer'] 			= $this->m_admin->cari_dealer();
		$data['id_tipe_kendaraan'] 	= $this->input->post('id_tipe_kendaraan');
		$this->load->view('dealer/t_po_reg_input',$data);
	}
}
