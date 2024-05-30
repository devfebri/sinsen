<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sppm extends CI_Controller {

    var $tables =   "tr_sppm";	
		var $folder =   "dealer";
		var $page		=		"sppm";
    var $pk     =   "id_sppm";
    var $title  =   "Surat Perintah Pengambilan Motor";

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
		$this->load->library('cfpdf');


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
	public function bulan($bulan){	  
	  switch($bulan)
	  {
	    case"1":$bulan="Januari"; break;
	    case"2":$bulan="Februari"; break;
	    case"3":$bulan="Maret"; break;
	    case"4":$bulan="April"; break;
	    case"5":$bulan="Mei"; break;
	    case"6":$bulan="Juni"; break;
	    case"7":$bulan="Juli"; break;
	    case"8":$bulan="Agustus"; break;
	    case"9":$bulan="September"; break;
	    case"10":$bulan="Oktober"; break;
	    case"11":$bulan="November"; break;
	    case"12":$bulan="Desember"; break;
	  }
	  $bln = $bulan;
	  return $bln;
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
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_sppm'] = $this->db->query("SELECT *,tr_sppm.driver as namadriver FROM tr_sppm INNER JOIN tr_do_po ON tr_sppm.no_do=tr_do_po.no_do
		inner join ms_plat_dealer on tr_sppm.no_pol = ms_plat_dealer.id_master_plat 
			WHERE tr_sppm.id_dealer = '$id_dealer' ORDER BY no_surat_sppm DESC");												
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function cari_id(){
		$niguri					= $this->input->post('niguri');
		$tahun 					= date("Y");
		$id_dealer 			= $this->m_admin->cari_dealer();
		$get_d = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();
		$bulan 					= date("m");		
		$bln 						= strtoupper($this->bulan(date("m")));				
		$pr_num 				= $this->db->query("SELECT * FROM tr_sppm WHERE MID(created_at,6,2)='$bulan' AND id_dealer = '$id_dealer' ORDER BY id_sppm DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pisah = explode("/", $row->no_surat_sppm);
			$id = $pisah[0] + 1;
			$kode = $id."/ADM/".$get_d->nama_dealer."/".$bln."/".$tahun;						
		}else{
			$kode = "1/ADM/".$get_d->nama_dealer."/".$bln."/".$tahun;
		} 	
		return $kode;
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$id_dealer 			= $this->m_admin->cari_dealer();
		// $data['dt_do'] 	= $this->db->query("SELECT * FROM tr_do_po WHERE id_dealer = '$id_dealer' AND status ='approved'
		// 	AND tr_do_po.no_do NOT IN (SELECT no_do FROM tr_sppm)");	
		$data['dt_do'] 	= $this->db->query("SELECT DISTINCT(tr_do_po.no_do),no_po FROM tr_do_po 
				INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do 
				INNER JOIN tr_picking_list ON tr_do_po.no_do=tr_picking_list.no_do
				WHERE tr_do_po.id_dealer = '$id_dealer' AND tr_do_po.status ='approved' AND tr_do_po_detail.qty_do > 0 AND tr_picking_list.status='close' ORDER BY tr_do_po.tgl_do DESC");											
		$this->template($data);										
	}

	public function t_sppm(){
		$no_do = $this->input->post('no_do');		
		$data['dt_pl'] = $this->db->query("SELECT tr_picking_list_detail.*,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_picking_list_detail 
			INNER JOIN ms_item 
                    ON tr_picking_list_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_picking_list_detail.no_do = '$no_do'
                    AND tr_picking_list_detail.qty_do > 0");				
		$this->load->view('dealer/t_sppm',$data);
		
	}
	public function cari_lain()
	{		
		$no_do	= $this->input->post('no_do');	
		$dt_do	= $this->db->query("SELECT * FROM tr_do_po WHERE no_do = '$no_do'");								
		if($dt_do->num_rows() > 0){
			$da = $dt_do->row();
			$tgl_do = $da->tgl_do;
			$ket = $da->ket;
		}else{
			$tgl_do = "";
			$ket = "";
		}
		echo "ok"."|".$tgl_do."|".$ket;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_do			= $this->input->post("no_do");
		$no_surat		= $this->cari_id();
		$id_item		= $this->input->post("id_item");
		$jum				= $this->input->post("jum");

		$qty=0;$ambil=0;
		// foreach($id_item AS $key => $val){
		// 	$qty_do 		= $_POST['qty_do'][$key];
		// 	$qty 				= $qty + $qty_do;
		// 	$qty_ambil 	= $_POST['qty_ambil'][$key];
		// 	$ambil 			= $ambil + $qty_ambil;
		// 	$result[] = array(				
		// 		"no_surat_sppm" => $no_surat,
		// 		"id_item"  			=> $_POST['id_item'][$key],
		// 		"qty_do"  			=> $_POST['qty_do'][$key],
		// 		"qty_ambil" 		=> $_POST['qty_ambil'][$key]
		// 	);
		// }

		for ($i=1; $i <= $jum; $i++) {			
			$qty_do 		= $_POST['qty_do_'.$i];
			$qty 				= $qty + $qty_do;
			$qty_ambil 	= $_POST['qty_ambil_'.$i];
			$ambil 			= $ambil + $qty_ambil;
			$result[] = array(				
				"no_surat_sppm" => $no_surat,
				"id_item"  			=> $_POST['id_item_'.$i],
				"qty_do"  			=> $_POST['qty_do_'.$i],
				"qty_ambil" 		=> $_POST['qty_ambil_'.$i]
			);
		}		
		$id_dealer = $this->m_admin->cari_dealer();
		$cek_jumlah = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();
		$maks_penitipan_unit = $cek_jumlah->maks_penitipan_unit;
		if($qty >= $ambil){
			$sisa = $qty - $ambil;
			if($sisa > $maks_penitipan_unit){
				$_SESSION['pesan'] 	= "Maaf, jumlah unit yang anda titipkan ke MD melebihi ketentuan";
				$_SESSION['tipe'] 	= "danger";		
				echo "<script>history.go(-1);</script>";
			}else{
				$testb= $this->db->insert_batch('tr_sppm_detail', $result);
				$driver = $this->input->post('driver');
				$no_pol = $this->input->post('no_pol');

				$data['no_do'] 						= $no_do;
				$data['tgl_do'] 					= $this->input->post('tgl_do');
				$data['no_surat_sppm'] 		= $no_surat;
				$data['tgl_surat'] 				= $this->input->post('tgl_surat');
				$data['ket'] 							= $this->input->post('ket');
				$cek = $this->db->query("SELECT * FROM ms_plat_dealer WHERE id_master_plat ='$no_pol' AND driver = '$driver' ")->num_rows();
				if ($cek == 0) {
					$this->db->query("UPDATE ms_plat_dealer set driver='$driver' WHERE id_master_plat = '$no_pol' ");
				}
				$data['no_pol'] 					= $this->input->post('no_pol');
				$data['driver'] 					= $this->input->post('driver');
				$data['id_dealer'] 				= $id_dealer;
				$data['status'] 					= "input"; 			  	
				$data['created_at']				= $waktu;		
				$data['created_by']				= $login_id;	
				$this->m_admin->insert("tr_sppm",$data);		
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sppm'>";
			}
		}else{
			$_SESSION['pesan'] 	= "Maaf, jumlah unit yang anda titipkan melebihi jumlah pengambilan";
			$_SESSION['tipe'] 	= "danger";		
			echo "<script>history.go(-1);</script>";
		}
		
	}
	//-----------------------------------------------------------------------------------


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

	public function cetak_sppm()
	{
	  $id_sppm = $this->input->get("id");
	  $tgl = date('d-m-Y');
	  $this->db->query("UPDATE tr_sppm set tgl_sppm ='$tgl' WHERE id_sppm ='$id_sppm'");

	  $sppm =  $this->db->query("SELECT *,tr_sppm.driver as namadriver,tr_sppm.ket  FROM tr_sppm 
	  							 inner join tr_sppm_detail on tr_sppm.no_surat_sppm = tr_sppm_detail.no_surat_sppm
	  							inner join ms_plat_dealer on tr_sppm.no_pol = ms_plat_dealer.id_master_plat 
	  							INNER JOIN ms_item 
                    ON tr_sppm_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna
	  							WHERE tr_sppm.id_sppm = '$id_sppm'
	  							");
	  $row=$sppm->row();
	  $pdf = new FPDF('p','mm','A4');
	  $pdf->SetMargins(10, 10, 10);
      $pdf->AddPage();
       // head	  
	  $pdf->SetFont('ARIAL','B',12);
	  $pdf->Cell(190, 7, 'Surat Perintah Pengambilan Motor', 1, 1, 'C');
	  $pdf->Cell(30, 7, '', 0, 1, 'L');
	  $pdf->SetFont('ARIAL','',11);
	  $pdf->Cell(30, 5, 'Tanggal SPPM', 0, 0, 'L');
	  $pdf->Cell(90, 5, ': '.date('d-m-Y'), 0, 1, 'L');
	  $pdf->Cell(30, 5, 'No Surat', 0, 0, 'L');
	  $pdf->Cell(90, 5, ': '.$row->no_surat_sppm, 0, 1, 'L');
	  $pdf->Cell(30, 5, 'No DO', 0, 0, 'L');
	  $pdf->Cell(90, 5, ': '.$row->no_do, 0, 1, 'L');
	  $pdf->Cell(30, 5, 'Tanggal DO', 0, 0, 'L');
	   $tgl = date('d-m-Y', strtotime($row->tgl_do));
	  $pdf->Cell(90, 5, ': '.$tgl, 0, 1, 'L');
	  $pdf->Cell(30, 7, '', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'Kepada YTH :', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'Bapak Thomas Satyamitta ', 0, 1, 'L');
	  $pdf->Cell(100, 5, 'Kepala Logistik Gudang Paal VI', 0, 1, 'L');
	  $pdf->Cell(100, 5, 'PT. Sinar Sentosa Primatama - Jambi', 0, 1, 'L');
	  $pdf->Cell(30, 7, '', 0, 1, 'L');
	  $pdf->Cell(100, 5, 'Dengan Hormat, ', 0, 1, 'L');
	  $pdf->Multicell(190,5,"Bersama surat ini, kami mohon dapat diserahkan unit seperti tertera dibawah ini kepada pengemudi : $row->namadriver dengan kendaraan ber-nopol : $row->no_plat pada tanggal : ______________________.",0,"L");
	  $pdf->SetFont('ARIAL','B',11); 
	  $pdf->Cell(30, 4, '', 0, 1, 'L');

	  $pdf->Cell(190, 5, 'Detail Unit', 1, 1, 'C');
	  $pdf->Cell(15, 5, 'No', 1, 0, 'C');
	  $pdf->Cell(25, 5, 'Kode Tipe', 1, 0, 'C');
	  $pdf->Cell(70, 5, 'Nama Tipe', 1, 0, 'C');
	  $pdf->Cell(50, 5, 'Warna', 1, 0, 'C');
	  $pdf->Cell(30, 5, 'Jumlah', 1, 1, 'C');
	  $pdf->SetFont('ARIAL','',11); 

	  $no=1;
	  foreach ($sppm->result() as $sppm) {
		  $pdf->Cell(15, 5, $no, 1, 0, 'C');
		  $pdf->Cell(25, 5, $sppm->id_tipe_kendaraan, 1, 0, 'C');
		  $pdf->Cell(70, 5, $sppm->tipe_ahm, 1, 0, 'C');
		  $pdf->Cell(50, 5, $sppm->warna, 1, 0, 'C');
		  $pdf->Cell(30, 5, $sppm->qty_ambil, 1, 1, 'C');
		  $no++;
	  }
	  $pdf->Cell(30, 2, '', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'Keterangan : '.$row->ket, 0, 1, 'L');
	  $pdf->Cell(30, 7, '', 0, 1, 'L');
	  $pdf->Cell(63, 5, 'DIBUAT OLEH', 0, 0, 'C');
	  $pdf->Cell(63, 5, 'DISETUJUI OLEH', 0, 0, 'C');
	  $pdf->Cell(63, 5, 'DRIVER', 0, 0, 'C');
	 // $pdf->Cell(63, 5, 'MENGETAHUI', 0, 1, 'C');
	  $pdf->Cell(4, 19, '', 0, 1, 'C');
	  $pdf->Ln(7);
	  $pdf->Cell(63, 5, '(                          )', 0, 0, 'C');
	  $pdf->Cell(63, 5, '(                          )', 0, 0, 'C');
	  $pdf->Cell(63, 5, '( '.$row->namadriver.' )', 0, 0, 'C');
	  //$pdf->Cell(47.5, 5, '(                          )', 0, 1, 'C');

//	  $pdf->Line(11, 31, 200, 31);	   	


	  $pdf->Output(); 
	}
}