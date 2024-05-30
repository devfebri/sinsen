<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po extends CI_Controller {

	var $tables =   "tr_po";	
	var $folder =   "h1";
	var $page		=		"po";
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
		$data['dt_po'] = $this->db->query("SELECT * FROM tr_po order by id_po desc");	
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND bundling IS NULL");	
		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	public function t_po_reg(){
		$id = $this->input->post('id_po');
		$dq = "SELECT tr_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_detail INNER JOIN ms_item 
						ON tr_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_detail.id_po = '$id'";
		$data['dt_po_reg'] = $this->db->query($dq);		
		$data['mode'] = $this->input->post('mode');
		$this->load->view('h1/t_po_reg',$data);
	}

	public function t_po_add(){
		$id = $this->input->post('id_po');
		$dq = "SELECT tr_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_detail INNER JOIN ms_item 
						ON tr_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_detail.id_po = '$id'";
		$data['dt_po_add'] = $this->db->query($dq);		
		$data['mode'] = $this->input->post('mode');
		$this->load->view('h1/t_po_add',$data);
	}
	
	public function add()
	{				
		//$this->m_admin->reset_tmp("tr_po","tr_po_detail","id_po");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";			
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND (bundling IS NULL OR bundling='')");						
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po WHERE bulan = '$bln' AND tahun = '$th'");
		$data['jenis'] = "PO Additional"; 
		// if($cek->num_rows() == 0){
		// 	$data['jenis'] = "PO Reguler"; 
		// }else{
		// }
		$this->template($data);	
	}
	public function cari_id_header(){
		//XXX/PO-E20/YYYY
		$po     = $this->input->post('po');
		$th     = date("Y");
		$bln    = date("m");		
		$pr_num = $this->db->query("SELECT * FROM tr_po ORDER BY id_po DESC LIMIT 0,1");			

		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();							
			$id 	= substr($row->id_po,0,5)+1;	
			if($id < 10){
			$kode1 = "0000".$id."/PO-E20/".$th;          
		}elseif($id>9 && $id<=99){
			$kode1 = "000".$id."/PO-E20/".$th;          
		}elseif($id>99 && $id<=999){
			$kode1 = "00".$id."/PO-E20/".$th;          
		}elseif($id>999){
			$kode1 = "0".$id."/PO-E20/".$th;          
		}
			$kode = $kode1;
		}else{
			$kode = "00001/PO-E20/".$th;			
		} 	
		return $kode;
	}
	public function cari_id()
	{
		/* Status
			1 Baru
			2 Edit
		*/
		$status   = $this->input->post('status');
		$user     = $this->session->userdata('id_user');
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$get_data = $this->db->query("SELECT * FROM tr_po ORDER BY id_po DESC LIMIT 0,1");
   		if ($get_data->num_rows()>0) {
			$row      = $get_data->row();
			$old_kode = sprintf("%'.05d",substr($row->id_po, 0,5)+1);
			$new_kode = $old_kode."/PO-E20/".$th;
			$i=0;
			while ($i<1) {
				$cek = $this->db->get_where('tr_po',['id_po'=>$new_kode])->num_rows();
			    if ($cek>0) {
					$new_kode = sprintf("%'.05d",substr($new_kode, 0,5)+1);
					$new_kode = $new_kode."/PO-E20/".$th;	
					$i        = 0;
			    }else{
			    	$i++;
			    }
			}
   		}else{
			$new_kode = "00001/PO-E20/".$th;
   		}
   		echo strtoupper($new_kode.'-'.$user.'-'.$status);
	}	
	public function cek_item()
	{		
		$id_item = $this->input->post('id_item');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$sql = $this->db->query("SELECT ms_item.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM ms_item INNER JOIN ms_tipe_kendaraan
							ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
							ON ms_item.id_warna=ms_warna.id_warna WHERE ms_item.id_item = '$id_item'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();
			$stok = $this->db->query("SELECT * FROM tr_real_stock WHERE id_tipe_kendaraan = '$dt_ve->id_tipe_kendaraan' AND id_warna = '$dt_ve->id_warna'");
			if($stok->num_rows() > 0){
				$isi = $stok->row();
				$stok_isi = $isi->stok_rfs + $isi->stok_nrfs;
			}else{
				$stok_isi = 0;
			}
			
			$cek 	= $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail ON tr_niguri.id_niguri = tr_niguri_detail.id_niguri
										INNER JOIN ms_item ON tr_niguri_detail.id_item = ms_item.id_item
										INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON ms_item.id_warna = ms_item.id_warna
										WHERE tr_niguri.bulan = '$bulan'AND tr_niguri.tahun = '$tahun' AND ms_item.id_item = '$id_item'
										AND tr_niguri.status_niguri = 'approved'");
			if($cek->num_rows() > 0){
				$is = $cek->row();
				$niguri = $is->a_fix;
			}else{
				$niguri = 0;
			}

			$bulan_1 = $bulan - 1;
			$po = $this->db->query("SELECT * FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
							WHERE  tr_po.bulan = '$bulan_1' AND tr_po.tahun = '$tahun'");
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
	public function cek_item_edit()
	{		
		$id_item = $this->input->post('id_item');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$sql = $this->db->query("SELECT ms_item.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM ms_item INNER JOIN ms_tipe_kendaraan
							ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
							ON ms_item.id_warna=ms_warna.id_warna WHERE ms_item.id_item = '$id_item'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();
			$stok = $this->db->query("SELECT * FROM tr_real_stock WHERE id_tipe_kendaraan = '$dt_ve->id_tipe_kendaraan' AND id_warna = '$dt_ve->id_warna'");
			if($stok->num_rows() > 0){
				$isi = $stok->row();
				$stok_isi = $isi->stok_rfs + $isi->stok_nrfs;
			}else{
				$stok_isi = 0;
			}
			
			$cek 	= $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail ON tr_niguri.id_niguri = tr_niguri_detail.id_niguri
										INNER JOIN ms_item ON tr_niguri_detail.id_item = ms_item.id_item
										INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON ms_item.id_warna = ms_item.id_warna
										WHERE tr_niguri.bulan = '$bulan'AND tr_niguri.tahun = '$tahun' AND ms_item.id_item = '$id_item'
										AND tr_niguri.status_niguri = 'approved'");
			if($cek->num_rows() > 0){
				$is = $cek->row();
				$niguri = $is->a_fix;
			}else{
				$niguri = 0;
			}

			$bulan_1 = $bulan - 1;
			$po = $this->db->query("SELECT * FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
							WHERE  tr_po.bulan = '$bulan_1' AND tr_po.tahun = '$tahun'");
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

			$response = ['on_hand'=>$stok_isi,
						 'qty_niguri_fix'=>$niguri,
						 'qty_po_fix'=>$po_fix,
						 'qty_po_t1'=>$po_t1,
						 'qty_po_t2'=>$po_t2,
						];
		}else{
			$response=[];
		}
		echo json_encode($response);
	}
	public function cari_jenis()
	{				
		$th 						= $this->input->post('tahun');
		$bln 						= $this->input->post('bulan');
		$cek 	= $this->db->query("SELECT * FROM tr_po WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			echo "PO Reguler"; 
		}else{
			echo "PO Additional"; 
		}
	}
	function send_file($id){
		
		//mkdir("downloads/hy",0777);
		$k = $this->session->userdata('id_karyawan_dealer');
		// $bulan 		= gmdate("mY", time()+60*60*7);		
		
		
		if($id == ""){
			$sql = $this->db->query("SELECT *,LEFT(id_po,5) AS id_po_left FROM tr_po ORDER BY id_po DESC LIMIT 0,1")->row();
		}else{
			$sql = $this->db->query("SELECT *,LEFT(id_po,5) AS id_po_left FROM tr_po WHERE id_po = '$id' ORDER BY id_po ASC")->row();			
		}		
		if($sql->jenis_po == 'PO Reguler'){
			$j = "F";
		}else{
			$j = "A";
		}
		$bulan=sprintf("%'.02d",$sql->bulan).$sql->tahun;
		
		$folder 	= "downloads/po/";
		$filename = $folder.$bulan;
		if (!file_exists($filename)) {
		  mkdir($folder.$bulan, 0777);		
		}
		$no = $data['no'] = "AHM-E20-".$sql->id_po."-".$j;		
		$data['id'] = $id;		
		$data['k'] 	= $k;		
		
		//echo "<meta http-equiv='refresh' content='0; url=".base_url()."assets/get_data/upo.php?id=".$id."&k=".$k."&n=".$no."'>";			  
		//$this->load->view("h1/file_upo",$data);
		//move_uploaded_file($file, $folder);
	  //download_remote_file(base_url()."assets/get_data/upo.php?id=".$sql->id_po."&k=".$k, realpath("./".$filename)."/".$no.".UPO");	
	  // move_uploaded_file($folder,"C:\xampp\htdocs\web_honda\downloads\po\102018\AHM-E20-20181000008-A.UPO");	
	$nama_file = "AHM-E20-".$sql->tahun.$sql->bulan.$sql->id_po_left."-".$j;		
	  $fileLocation = getenv("DOCUMENT_ROOT")."/HONDA/downloads/po/".$bulan."/".$nama_file.".UPO";
	  $file = fopen($fileLocation,"w");

	  $sql = $this->db->query("SELECT tr_po_detail.*,tr_po.jenis_po,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.warna,ms_warna.id_warna,bulan,tahun FROM tr_po_detail INNER JOIN ms_item 
						ON tr_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna INNER JOIN tr_po
						On tr_po_detail.id_po=tr_po.id_po
						WHERE tr_po_detail.id_po = '$id'");

	  $isi2 = $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.kode_dealer_md as kode FROM ms_karyawan_dealer INNER JOIN ms_dealer
					ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer WHERE ms_karyawan_dealer.id_karyawan_dealer = '$k'")->row();
	  	$content = '';
	 //  	$mo = date("m")+1;
		// $ye = date("Y");
		foreach ($sql->result() as $isi) {
			if($isi->jenis_po == 'PO Reguler'){
				$id_jenis_po = "F";
			}else{
				$id_jenis_po = "A";
			}
			// $hari_ini  = date("Y-m-d"); 
			// $tgl       = date('t', strtotime($hari_ini));
			// $tgl_awal  = "01".sprintf("%'.02d",$mo).$ye;
			// $tgl_akhir = $tgl.sprintf("%'.02d",$mo).$ye;
			$mo = sprintf("%'.02d",$isi->bulan);
			$ye = sprintf("%'.02d",$isi->tahun);
			$tgl_awal 	= "01".$mo.$ye;
			$tgl_akhir 	= days_in_month($mo,$ye).$mo.$ye;
			if(isset($kode)){
				$kode_r 			= (int)$isi2->kode;
			}else{
				$kode_r = "E20";
			}
			if($isi->jenis_po == 'PO Reguler'){
				$content .= $kode_r.";".$mo.";".$ye.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$isi->qty_po_fix.";".$isi->qty_po_t1.";".$isi->qty_po_t2.";".$isi->id_po.";".$id_jenis_po.";".$tgl_awal.";".$tgl_akhir;
				$content .= "\r\n";	
			}else{
				// $tgl_awal 	= "01".sprintf("%'.02d",$mo).$ye;
				// $tgl_akhir 	= $tgl.sprintf("%'.02d",$mo).$ye;
				$tgl = days_in_month($isi->bulan,$isi->tahun);
				$tgl_awal 	= "01".sprintf("%'.02d",$isi->bulan).$isi->tahun;
				$tgl_akhir 	= $tgl.sprintf("%'.02d",$isi->bulan).$isi->tahun;
				// $content .= $kode_r.";".$mo.";".$ye.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$isi->qty_order.";".$isi->id_po.";".$id_jenis_po.";".$tgl_awal.";".$tgl_akhir;
				$content .= $kode_r.";".$mo.";".$ye.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$isi->qty_order.";0;0".$isi->id_po.";".$id_jenis_po.";".$tgl_awal.";".$tgl_akhir;
				$content .= "\r\n";	
			}	
		}
		fwrite($file,$content);
	  	fclose($file);
	  $dt_upd[]=['submitted'=>1,'id_po'=>$id];
	  $this->db->update_batch('tr_po',$dt_upd,'id_po');
	  $_SESSION['pesan'] 	= "Data has been processed successfully";
	  $_SESSION['tipe'] 	= "success";
	  echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";			  
	}


	public function send()
	{
		$id = $this->input->get('id');		
		$this->send_file($id);
		
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
			$sql = $this->db->query("SELECT * FROM tr_po ORDER BY id_po DESC LIMIT 0,1")->row();
		}else{
			$sql = $this->db->query("SELECT * FROM tr_po WHERE id_po = '$id' ORDER BY id_po ASC")->row();			
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
		$this->load->view("h1/file_upo",$data);

	  //download_remote_file(base_url()."assets/get_data/upo.php?id=".$sql->id_po."&k=".$k, realpath("./".$filename)."/".$no.".UPO");	
	  //file_get_contents("C:\xampp\htdocs\web_honda\downloads\po\112017\AHM-E20-20171100001.UPO", base_url()."assets/get_data/upo.php?id=".$sql->id_po."&k=".$k);	
	}


	public function download()
	{
		$id = $this->input->get('id');		
		$this->download_file($id);
		
	}


	public function save_po_reg(){
		$id_po                  = $this->input->post('id_po');			
		$id_item                = $this->input->post('id_item');
		$bulan                  = $this->input->post('bulan');
		$tahun                  = $this->input->post('tahun');
		$qty_po_fix             = $this->input->post('qty_po_fix');
		$qty_po_t1              = $this->input->post('qty_po_t1');
		$qty_po_t2              = $this->input->post('qty_po_t2');
		$qty_niguri_fix         = $this->input->post('qty_niguri_fix');							
		
		$data['id_po']          = $this->input->post('id_po');			
		$data['id_item']        = $this->input->post('id_item');			
		$data['qty_po_fix']     = $this->input->post('qty_po_fix');								
		$data['qty_po_t1']      = $this->input->post('qty_po_t1');			
		$data['qty_po_t2']      = $this->input->post('qty_po_t2');							
		$data['qty_po_fix']     = $this->input->post('qty_po_fix');					
		$data['qty_niguri_fix'] = $this->input->post('qty_niguri_fix');							
		$data['on_hand']        = $this->input->post('on_hand');
		$data['id_user']        = $this->session->userdata('id_user');									

		$t = $this->m_admin->getById("ms_item","id_item",$id_item)->row();
		$id_tipe_kendaraan = $t->id_tipe_kendaraan;

		//cek jumlah po_fix saat ini
		$jum 	= $this->db->query("SELECT SUM(qty_po_fix) AS po_fix FROM tr_po_detail INNER JOIN ms_item ON ms_item.id_item = tr_po_detail.id_item
										INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan										
										WHERE tr_po_detail.id_po = '$id_po' AND ms_tipe_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan'");
		if($jum->num_rows() > 0){
			$i = $jum->row();
			$po_fix = $i->po_fix;
			$isi_po = $po_fix + $qty_po_fix;
		}else{
			$isi_po = $qty_po_fix;
		}

		//cek jumlah niguri saat ini
		$jum2 	= $this->db->query("SELECT a_fix FROM tr_niguri_detail INNER JOIN tr_niguri ON tr_niguri.id_niguri = tr_niguri_detail.id_niguri										
										WHERE tr_niguri_detail.id_item = '$id_item' AND tr_niguri.bulan = '$bulan' AND tr_niguri.tahun = '$tahun'");
		if($jum2->num_rows() > 0){
			$j = $jum2->row();
			$niguri_fix = $j->a_fix;			
		}else{
			$niguri_fix = 0;
		}

		$po = $this->db->query("SELECT * FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
						WHERE  tr_po.bulan = '$bulan' AND tr_po.tahun = '$tahun'");
		if($po->num_rows() > 0){
			$isi_po = $po->row();
			$po_fix = $isi_po->qty_po_t1;
			$po_t1 	= $isi_po->qty_po_t2;
			$po_f_30 = floor($po_fix * 0.1);
			$po_t1_30 = floor($po_t1 * 0.15);
			$isi_f_30 = $po_fix + $po_f_30;	
			$isi_t1_30 = $po_t1 + $po_t1_30;	
		}else{
			$isi_f_30 = 0;	
			$isi_t1_30 = 0;	
		}

		// if($qty_po_fix <= 22){
		// 	if($qty_po_t1 <= $isi_t1_30){
		if($niguri_fix <= $isi_po){
			$cek = $this->db->get_where("tr_po_detail",array("id_item"=>$id_item,"id_po"=>$id_po));
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_po_detail;
				$this->m_admin->update("tr_po_detail",$data,"id_po_detail",$id);			
				echo "nihil";
			}else{
				$this->m_admin->insert("tr_po_detail",$data);			
				echo "nihil";			
			}
		}else{
			echo "niguri";
		}				
		// 	}else{
		// 		echo "po_t1";
		// 	}
		// }else{
		// 	echo "po_fix";
		// }
		
	}
	public function save_po_add(){
		$id_po             = $this->input->post('id_po');			
		$id_item           = $this->input->post('id_item');
		$data['id_po']     = $this->input->post('id_po');			
		$data['id_item']   = $this->input->post('id_item');			
		$data['qty_order'] = $this->input->post('qty_order');							
		$data['id_user']   = $this->session->userdata('id_user');				
		$cek = $this->db->get_where("tr_po_detail",array("id_item"=>$id_item,"id_po"=>$id_po));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_po_detail;
			$this->m_admin->update("tr_po_detail",$data,"id_po_detail",$id);			
		}else{
			$this->m_admin->insert("tr_po_detail",$data);			
		}
		echo "nihil";
	}
	public function delete_po(){
		$id_item = $this->input->post('id_item');
		$id_po_detail 	= $this->input->post('id_po_detail');
		$this->db->query("DELETE FROM tr_po_detail WHERE id_po_detail = '$id_po_detail'");			
		echo "nihil";
	}
	public function cancel_po(){
		$id_po			= $this->input->post('id_po');			
		$this->m_admin->delete("tr_po","id_po",$id_po);
		$this->m_admin->delete("tr_po_detail","id_po",$id_po);
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
			$id_po_old 	= $this->input->post('id_po');
			$id_po = $this->cari_id_header();
			$data['id_po']      = $id_po;
			$data['ket']        = $this->input->post('ket');	
			$data['bulan']      = $this->input->post('bulan');	
			$data['tahun']      = $this->input->post('tahun');	
			$data['jenis_po']   = $this->input->post('jenis_po');	
			$data['status']     = "input";	
			$data['tgl']        = $tgl;				
			$data['active']     = 1;					
			$data['created_at'] = $waktu;		
			$data['created_by'] = $login_id;	
			$this->m_admin->insert($tabel,$data);
			$this->db->update('tr_po_detail',['id_po'=>$id_po],['id_po'=>$id_po_old]);
			//$this->download_file($id_po);

			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po/add'>";
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
		$this->db->trans_begin();			
		$this->db->delete($tabel,array($pk=>$id));
		$this->db->trans_commit();			
		$result = 'Success';									
		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$this->m_admin->delete("tr_po_detail","id_po",$id);
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";
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
	public function detail()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND bundling IS NULL");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']	= "detail";									
		$data['set2']		= "";									
		$this->template($data);	
	}
	public function approval()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND bundling IS NULL");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']		= "detail";									
		$data['set2']		= "tombol";									
		$this->template($data);	
	}
	public function edit()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND bundling IS NULL");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']	= "edit";									
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
			$data['active'] 		= 1;					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$id);
			//$this->download_file($id);

			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function edit_reg()
	{
		$id = $this->input->get('id');
		$get_data = $this->db->query("SELECT * FROM tr_po WHERE id_po='$id' "); //AND (status='input' OR status='reject_ahm')
		if ($get_data->num_rows()>0) {
			$data['row']     = $get_data->row();
			$data['details']  = $this->db->query("SELECT tr_po_detail.*,wr.warna,tk.tipe_ahm FROM tr_po_detail 
								JOIN ms_item ON tr_po_detail.id_item=ms_item.id_item
								JOIN ms_tipe_kendaraan AS tk ON ms_item.id_tipe_kendaraan=tk.id_tipe_kendaraan
								JOIN ms_warna AS wr ON ms_item.id_warna=wr.id_warna
								WHERE id_po='$id'")->result();
			$data['isi']     = $this->page;		
			$data['title']   = $this->title;		
			$data['set']     = "edit_reg";		
			$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1 AND bundling IS NULL");							
			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";		
		}
	}
	public function save_edit()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

			$id_po = $data['id_po'] = $this->input->post('id_po');
			$data['ket']        = $this->input->post('ket');
			$data['updated_at'] = $waktu;		
			$data['updated_by'] = $login_id;
			$details          = $this->input->post('details');
			foreach ($details as $key => $val) {
				$ins_details[] = ['id_po'=> $id_po,
								'id_item'        => $val['id_item'],
								'on_hand'        => $val['on_hand'],
								'qty_po_fix'     => $val['qty_po_fix'],
								'qty_niguri_fix' => $val['qty_niguri_fix'],
								'qty_po_t1'      => $val['qty_po_t1'],
								'qty_po_t2'      => $val['qty_po_t2']
						 	 ];	
			}
			$this->db->trans_begin();
				$this->db->update('tr_po',$data,['id_po'=>$id_po]);
				$this->db->delete('tr_po_detail',['id_po'=>$id_po]);
				$this->db->insert_batch('tr_po_detail',$ins_details);
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$rsp = ['status'=> 'error',
						'pesan'=> ' Something went wrong'
					   ];
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
	        	$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				$rsp = ['status'=> 'sukses',
						'link'=>base_url('h1/po')
					   ];
				// echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/event'>";		

	      	}			
	     echo json_encode($rsp);				
	}
	public function approve_reg()
	{
		$id_po=$this->input->get('id');
		$cek = $this->db->query("SELECT * FROM tr_po WHERE id_po='$id_po' AND (status='input' OR status='reject_ahm')");
		if ($cek->num_rows()>0) {
			$data['status']='approved';
			$this->db->update('tr_po',$data,['id_po'=>$id_po]);
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
		}
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";		
	}

	public function approve_reg_ahm()
	{
		$id_po=$this->input->get('id');
		$cek = $this->db->query("SELECT * FROM tr_po WHERE id_po='$id_po' AND status='approved' AND submitted='1'");
		if ($cek->num_rows()>0) {
			$data['status']='approved_ahm';
			$this->db->update('tr_po',$data,['id_po'=>$id_po]);
			$_SESSION['pesan'] 	= "Data has been approved by AHM successfully";
			$_SESSION['tipe'] 	= "success";
		}
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";		
	}

	public function reject_reg_ahm()
	{
		$id_po=$this->input->get('id');
		$cek = $this->db->query("SELECT * FROM tr_po WHERE id_po='$id_po' AND status='approved' AND submitted='1'");
		if ($cek->num_rows()>0) {
			$data['status']='reject_ahm';
			$this->db->update('tr_po',$data,['id_po'=>$id_po]);
			$_SESSION['pesan'] 	= "Data has been rejected by AHM successfully";
			$_SESSION['tipe'] 	= "success";
		}
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";		
	}

	public function save_approval()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$approval		= $this->input->post("approval");
		if($approval == 'approve'){
			$this->db->query("UPDATE tr_po SET status = 'approved' WHERE id_po = '$id'");
		}else{
			$this->db->query("UPDATE tr_po SET status = 'rejected' WHERE id_po = '$id'");
		}		
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";		
	}
	public function cari_po_reg(){
		$id		= $this->input->post('id');
		$row 	= $this->db->query("SELECT tr_po_detail.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan FROM tr_po_detail INNER JOIN ms_item ON tr_po_detail.id_item=ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_po_detail.id_po_detail = '$id'")->row();		
		$cek = $this->db->query("SELECT * FROM tr_po_detail INNER JOIN tr_po ON tr_po.id_po = tr_po_detail.id_po WHERE tr_po_detail.id_po_detail = '$id'");
		if($cek->num_rows() > 0){
			$mode = "edit";
		}else{
			$mode = "add";
		}

		echo $row->id_item."|".$row->tipe_ahm."|".$row->warna."|".$row->on_hand."|".$row->qty_niguri_fix."|".$row->qty_po_fix."|".$row->qty_po_t1."|".$row->qty_po_t2."|".$row->id_po_detail."|".$row->id_po."|".$mode;
	}
	public function cari_po_add(){
		$id		= $this->input->post('id');
		$row 	= $this->db->query("SELECT tr_po_detail.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan FROM tr_po_detail INNER JOIN ms_item ON tr_po_detail.id_item=ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_po_detail.id_po_detail = '$id'")->row();		
		$cek = $this->db->query("SELECT * FROM tr_po_detail INNER JOIN tr_po ON tr_po.id_po = tr_po_detail.id_po WHERE tr_po_detail.id_po_detail = '$id'");
		if($cek->num_rows() > 0){
			$mode = "edit";
		}else{
			$mode = "add";
		}
		echo $row->id_item."|".$row->tipe_ahm."|".$row->warna."|".$row->qty_order."|".$row->id_po_detail."|".$row->id_po."|".$mode;
	}
	public function edit_po_add(){
		$id									= $this->input->post('id_po_detail');					
		$id_po							= $this->input->post('id_po');					
		$mode								= $this->input->post('mode');									
		$data['qty_order']	= $this->input->post('qty_order');									
		$this->m_admin->update("tr_po_detail",$data,"id_po_detail",$id);			
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		if($mode == 'edit'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po/edit?id=".$id_po."'>";							
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po/add'>";							
		}
	}
	public function edit_po_reg(){
		$id											= $this->input->post('id_po_detail');					
		$id_po									= $this->input->post('id_po');					
		$mode										= $this->input->post('mode');					
		$id_item								= $this->input->post('id_item');					
		$bulan 									= $this->input->post('bulan');
		$tahun 									= $this->input->post('tahun');
		$qty_po_fix							= $this->input->post('qty_po_fix');
		$qty_po_t1							= $this->input->post('qty_po_t1');
		$qty_po_t2							= $this->input->post('qty_po_t2');
		$qty_niguri_fix					= $this->input->post('qty_niguri_fix');									
		$data['qty_po_fix']			= $this->input->post('qty_po_fix');								
		$data['qty_po_t1']			= $this->input->post('qty_po_t1');			
		$data['qty_po_t2']			= $this->input->post('qty_po_t2');							
		$data['qty_po_fix']			= $this->input->post('qty_po_fix');					
		$data['qty_niguri_fix']	= $this->input->post('qty_niguri_fix');							
		$data['on_hand']				= $this->input->post('on_hand');		

		$t = $this->m_admin->getById("ms_item","id_item",$id_item)->row();
		$id_tipe_kendaraan = $t->id_tipe_kendaraan;

		//cek jumlah po_fix saat ini
		$jum 	= $this->db->query("SELECT SUM(qty_po_fix) AS po_fix FROM tr_po_detail INNER JOIN ms_item ON ms_item.id_item = tr_po_detail.id_item
										INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan										
										WHERE tr_po_detail.id_po = '$id_po' AND ms_tipe_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan'");
		if($jum->num_rows() > 0){
			$i = $jum->row();
			$po_fix = $i->po_fix;
			$isi_po = $po_fix + $qty_po_fix;
		}else{
			$isi_po = $qty_po_fix;
		}

		//cek jumlah niguri saat ini
		$jum2 	= $this->db->query("SELECT a_fix FROM tr_niguri_detail INNER JOIN tr_niguri ON tr_niguri.id_niguri = tr_niguri_detail.id_niguri										
										WHERE tr_niguri_detail.id_item = '$id_item' AND tr_niguri.bulan = '$bulan' AND tr_niguri.tahun = '$tahun'");
		if($jum2->num_rows() > 0){
			$j = $jum2->row();
			$niguri_fix = $j->a_fix;			
		}else{
			$niguri_fix = 0;
		}

		$po = $this->db->query("SELECT * FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
						WHERE  tr_po.bulan = '$bulan' AND tr_po.tahun = '$tahun'");
		if($po->num_rows() > 0){
			$isi_po = $po->row();
			$po_fix = $isi_po->qty_po_t1;
			$po_t1 	= $isi_po->qty_po_t2;
			$po_f_30 = floor($po_fix * 0.1);
			$po_t1_30 = floor($po_t1 * 0.15);
			$isi_f_30 = $po_fix + $po_f_30;	
			$isi_t1_30 = $po_t1 + $po_t1_30;	
		}else{
			$isi_f_30 = 0;	
			$isi_t1_30 = 0;	
		}
		
		if($niguri_fix <= $isi_po){
			$this->m_admin->update("tr_po_detail",$data,"id_po_detail",$id);			
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			if($mode == 'edit'){
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po/edit?id=".$id_po."'>";							
			}else{
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po/add'>";							
			}
		}else{
			$_SESSION['pesan'] 	= "Qty PO Fix item ini melebihi QTY Niguri Fix";
			$_SESSION['tipe'] 	= "danger";
			if($mode == 'edit'){
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po/edit?id=".$id_po."'>";							
			}else{
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po/add'>";							
			}
		}							
	}
}