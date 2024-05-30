<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_outstanding_ksu extends CI_Controller {

    var $tables =   "tr_surat_jalan_ksu";	
		var $folder =   "h1";
		var $page		=		"monitor_outstanding_ksu";
    var $pk     =   "id_surat_jalan_ksu";
    var $title  =   "Monitor Outstanding KSU";

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
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$data['set']		= "detail";				
		$id 						= $this->input->get("id");
		$pl 						= $this->input->get("pl");
		$data['pl']			= $pl;				
		$data['sj']			= $id;				
		$data['dt_mo']  = $this->db->query("SELECT tr_surat_jalan_ksu.id_ksu,ms_ksu.ksu,tr_surat_jalan_ksu.qty_do,tr_surat_jalan_ksu.qty
                      FROM tr_surat_jalan_ksu
                      INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
                      WHERE tr_surat_jalan_ksu.no_surat_jalan = '$id' AND tr_surat_jalan_ksu.qty < tr_surat_jalan_ksu.qty_do");
		$this->template($data);			
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
		$data['dt_mo']  = $this->db->query("SELECT tr_mon_ksu_detail.id_ksu,ms_ksu.ksu,tr_mon_ksu_detail.qty_do,tr_mon_ksu_detail.qty_penuh
                      FROM tr_mon_ksu_detail
                      INNER JOIN ms_ksu ON tr_mon_ksu_detail.id_ksu = ms_ksu.id_ksu                      
                      WHERE tr_mon_ksu_detail.no_pl_ksu = '$pl'");
		$this->template($data);			
	}	
	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_ksu 		= $this->input->post('id_ksu');		
		$no_pl_ksu 	= $this->input->post('no_pl_ksu');		
		$sj 				= $this->input->post('sj');		

		$data['no_pl_ksu'] = $no_pl_ksu;
		$data['created_at'] = $waktu;
		$data['created_by'] = $login_id;
		$data['status_mon'] = "input";
		$cek = 0;
		foreach($id_ksu AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$qty_do 	= $_POST['qty_do'][$key];
			$qty_penuh = $_POST['qty_penuh'][$key];			
		 	$result[] = array(
				"no_pl_ksu"  => $no_pl_ksu,
				"id_ksu"  => $_POST['id_ksu'][$key],
				"qty_do"  => $_POST['qty_do'][$key],
				"qty_penuh"  => $_POST['qty_penuh'][$key]				
		 	); 
		 	if($qty_penuh > $qty_do){
		 		$cek = $cek + 1;		 		
		 	}

		 	$rty = $this->db->query("SELECT * FROM tr_mon_ksu_detail WHERE no_pl_ksu = '$no_pl_ksu' AND id_ksu = '$id_ksu'");
      if($rty->num_rows() > 0){
      	$e = $rty->row();      	
      	$this->db->query("DELETE FROM tr_mon_ksu_detail WHERE id_mon_ksu_detail = '$e->id_mon_ksu_detail'");
      }
		}
		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty KSU tidak boleh lebih dari jumlah unit yg disediakan";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";			
			//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_outstanding_ksu/detail?id=".$sj."&pl=".$no_pl_ksu."'>";
		}else{
			$test2 	= $this->db->insert_batch('tr_mon_ksu_detail', $result);
			$test 	= $this->m_admin->insert('tr_mon_ksu', $data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_outstanding_ksu'>";
		}		
	}
	
	public function save_ksu_konfirmasi(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_ksu 		= $this->input->post('id_ksu');		
		$no_pl_ksu 	= $this->input->post('no_pl_ksu');		
		$sj 				= $this->input->post('sj');		

		$data['no_pl_ksu'] 	= $no_pl_ksu;
		$data['updated_at'] = $waktu;
		$data['updated_by'] = $login_id;
		$data['status_mon'] = "konfirmasi";
		$cek = 0;
		foreach($id_ksu AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$qty_do 	= $_POST['qty_do'][$key];
			$qty_penuh = $_POST['qty_penuh'][$key];			
			$qty_konfirmasi = $_POST['qty_konfirmasi'][$key];			

		 	$result[] = array(
				"no_pl_ksu"  => $no_pl_ksu,
				"id_ksu"  => $_POST['id_ksu'][$key],
				"qty_do"  => $_POST['qty_do'][$key],
				"qty_penuh"  => $_POST['qty_penuh'][$key],				
				"qty_konfirmasi"  => $_POST['qty_konfirmasi'][$key]				
		 	); 
		 	if($qty_konfirmasi > $qty_penuh){
		 		$cek = $cek + 1;		 		
		 	}

		 	$rty = $this->db->query("SELECT * FROM tr_mon_ksu_detail WHERE no_pl_ksu = '$no_pl_ksu' AND id_ksu = '$id_ksu'");
      if($rty->num_rows() > 0 AND $qty_konfirmasi <= $qty_penuh){
      	$e = $rty->row();      	
      	$this->db->query("DELETE FROM tr_mon_ksu_detail WHERE id_mon_ksu_detail = '$e->id_mon_ksu_detail'");
      }
		}
		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty KSU tidak boleh lebih dari jumlah unit yg disediakan";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";		
			//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_outstanding_ksu/konfirmasi?id=".$sj."&pl=".$no_pl_ksu."'>";
		}else{
			$test2 	= $this->db->insert_batch('tr_mon_ksu_detail', $result);
			$test 	= $this->m_admin->update('tr_mon_ksu',$data,"no_pl_ksu",$no_pl_ksu);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_outstanding_ksu'>";
		}	
	}
	public function close()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');	
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("pl");		
		$data['updated_at']					= $waktu;		
		$data['updated_by']					= $login_id;	
		$data['status_mon']					= "close";	
		$this->m_admin->update("tr_mon_ksu",$data,"no_pl_ksu",$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_outstanding_ksu'>";		
	}

	public function print_sj()
	{
	  $id = $this->input->get('id');
	  $pl = $this->input->get('pl');
	  $data['isi']    = $this->page;	
	  $data['tgl_sj_outstanding'] = date('Y-m-d');
	  $dt_sj  = $this->db->query("
	  	SELECT * FROM tr_surat_jalan_ksu INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu INNER JOIN tr_do_po ON tr_surat_jalan_ksu.no_do = tr_do_po.no_do INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer inner join tr_surat_jalan_ksu_pl on tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan_ksu_pl.no_surat_jalan WHERE tr_surat_jalan_ksu.no_surat_jalan = '$id' AND tr_surat_jalan_ksu.qty < tr_surat_jalan_ksu.qty_do");
		$dt_sj_row = $dt_sj->row();

		if ($dt_sj_row->no_sj_outstanding_ksu=='' or $dt_sj_row->no_sj_outstanding_ksu==null) {
			$sj_ksu_old = $this->db->query("SELECT * FROM tr_surat_jalan_ksu_pl WHERE no_surat_jalan <> '$id' ORDER BY no_sj_outstanding_ksu DESC limit 0,1")->row()->no_sj_outstanding_ksu;
			if ($sj_ksu_old=='' or $sj_ksu_old==null) {
				$sj_ksu_new = sprintf("%05d", 1);
			}else
			{
				$sj_ksu_new = sprintf("%05d", $sj_ksu_old+1);
			}

			$dt['no_sj_outstanding_ksu'] = $sj_ksu_new;
			$dt['tgl_sj_outstanding_ksu'] = date('Y-m-d');
			$this->m_admin->update('tr_surat_jalan_ksu_pl', $dt, "no_surat_jalan", $id);
		}


		$dt_sj_header  = $this->db->query("
					  SELECT *
	                  FROM tr_surat_jalan_ksu_pl  WHERE tr_surat_jalan_ksu_pl.no_surat_jalan = '$id'");
		$dt_sj_header = $dt_sj_header->row();

		$pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
       // head
	  $pdf->SetFont('ARIAL','B',18);
	  $pdf->Cell(190, 5, 'Surat Jalan Outstanding KSU', 0, 1, 'C');
	  $pdf->SetFont('ARIAL','',11);
	  $pdf->Cell(50, 5, 'Main Dealer: PT.Sinar Sentosa Primatama', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Telp: 0741-61551', 0, 1, 'L');
	  $pdf->Line(11, 31, 200, 31);
	   
	  $pdf->Image(base_url().'/assets/panel/images/logo_sinsen.jpg', 150, 15, 50);
	   
	  $pdf->SetFont('ARIAL','B',10);
	  $pdf->Cell(1,5,'',0,1);
	  //$pdf->Line(10, 58, 200, 58);	
	  $pdf->Cell(28, 5, 'No Surat Jalan', 0, 0);
	  $pdf->SetFont('ARIAL','',10);
	  $pdf->Cell(83, 5, ':  '.$dt_sj_header->no_sj_outstanding_ksu, 0, 0);
	  $pdf->SetFont('ARIAL','B',10);

	  $pdf->Cell(25, 5, 'No DO', 0, 0);
	  $pdf->SetFont('ARIAL','',10);
	  $pdf->Cell(85, 5, ':  '.$dt_sj_row->no_do, 0, 1);
	  $pdf->SetFont('ARIAL','B',10);

	  $pdf->Cell(28, 5, 'Tgl Surat Jalan', 0, 0);
	  $pdf->SetFont('ARIAL','',10);

	  $pdf->Cell(83, 5, ':  '.$dt_sj_header->tgl_sj_outstanding_ksu, 0, 0);
	  $pdf->SetFont('ARIAL','B',10);

	  $pdf->Cell(25, 5, 'Tgl DO', 0, 0);
	  $pdf->SetFont('ARIAL','',10);

	  $pdf->Cell(85, 5, ':  '.$dt_sj_row->tgl_do, 0, 1);
	  $pdf->Cell(1,3,'',0,1);
	  $pdf->SetFont('ARIAL','B',10);

	  $pdf->Cell(28, 5, 'Penerima', 0, 0);
	  $pdf->SetFont('ARIAL','',10);

	  $pdf->Cell(83, 5, ':  '.$dt_sj_row->nama_dealer, 0, 0);
	  $pdf->SetFont('ARIAL','B',10);

	  $pdf->Cell(25, 5, 'No SJ Unit', 0, 0);
	  $pdf->SetFont('ARIAL','',10);

	  $pdf->Cell(83, 5, ':  '.$dt_sj_row->no_surat_jalan, 0, 1);
	  $pdf->SetFont('ARIAL','B',10);

	  $pdf->Cell(28, 5, 'Alamat', 0, 0);
	  
	  $pdf->SetFont('ARIAL','',10);
$pdf->Cell(3, 5, ':', 0, 0);
	  $pdf->Multicell(60, 5,$dt_sj_row->alamat, 0);
	  $pdf->SetFont('ARIAL','B',10);

	  $pdf->Cell(28, 5, 'Tgl SJ Unit', 0, 0);
	  $pdf->SetFont('ARIAL','',10);
	  $tglSJU = $this->db->query("SELECT * FROM tr_surat_jalan WHERE no_surat_jalan='$dt_sj_row->no_surat_jalan' ");
	  if ($tglSJU->num_rows()>0) {
	  	$sj = $tglSJU->row()->tgl_surat;
	  }else{
	  	$sj='';
	  }
	  $pdf->Cell(85, 5, ': '.$sj, 0, 1);

	  $pdf->SetFont('ARIAL','B',10);
	   // buat tabel disini

	  
	   
	   // kasi jarak
	  $pdf->SetFont('ARIAL','B',12);
	  $pdf->Cell(1,1,'',0,1);
	  $pdf->Cell(190, 9, 'Detail KSU', 0, 1,'C');

	  $pdf->SetFont('ARIAL','B',10);
	  $pdf->Cell(20, 7, 'No.', 1, 0,'C');
	  $pdf->Cell(130, 7, 'Nama Aksesoris', 1, 0,'C');
	  $pdf->Cell(40, 7, 'Qty', 1, 1 ,'C');
	  $pdf->SetFont('ARIAL','',10);
	   $tot_qty=0;
	   $no=1;
	   $pl = $this->input->get('pl');
	   $getdetail = $this->db->query("SELECT tr_mon_ksu_detail.id_ksu,ms_ksu.ksu,tr_mon_ksu_detail.qty_do,tr_mon_ksu_detail.qty_penuh
                      FROM tr_mon_ksu_detail
                      INNER JOIN ms_ksu ON tr_mon_ksu_detail.id_ksu = ms_ksu.id_ksu                      
                      WHERE tr_mon_ksu_detail.no_pl_ksu = '$pl'");
	   foreach ($getdetail->result() as $key) {
          $pdf->Cell(20, 6, $no, 1, 0,'C');
	 	  $pdf->Cell(130, 6,$key->ksu, 1, 0,'L');
		  $pdf->Cell(40, 6,$key->qty_penuh, 1, 1 ,'C');
          $no++;
          $tot_qty += $key->qty_penuh;
        }
	  $pdf->SetFont('ARIAL','B',11);
	  $pdf->Cell(150, 8, 'Total     ', 0, 0, 'R');
	  $pdf->Cell(40, 8, $tot_qty, 0, 1, 'C');	  
	   // tanda tangan
	  $pdf->Cell(10, 3, '', 0, 1);

	  $pdf->SetFont('ARIAL','B',11);	  
	  // $pdf->Cell(110, 5, 'Pengirim', 0, 0);
	  // $pdf->Cell(35, 5, 'Ekspedisi', 0, 0,'C');
	  // $pdf->Cell(35, 5, 'Diterima', 0, 1,'C');
	  // $pdf->Cell(10, 16, '', 0, 1);
	  $pdf->SetFont('ARIAL','',11);	   
	  // $pdf->Cell(55, 5, '(Kepala Gudang)', 0, 0);
	  // $pdf->Cell(55, 5, '(                         )', 0, 0);
	  // $pdf->Cell(35, 5, '(                         )', 0, 0,'C');
	  // $pdf->Cell(35, 5, '(                         )', 0, 1,'C');
	  $pdf->Cell(63.3, 5, 'Diserahkan Oleh', 0, 0,'C');
	  $pdf->Cell(63.3, 5, 'Diketahui Oleh', 0, 0,'C');
	  $pdf->Cell(63.3, 5, 'Diterima Oleh', 0, 1,'C');
	  $pdf->Ln(16);
	  $pdf->Cell(63.3, 5, '(                             )', 0, 0,'C');
	  $pdf->Cell(63.3, 5, '(                             )', 0, 0,'C');
	  $pdf->Cell(63.3, 5, '(                             )', 0, 1,'C');



	  $pdf->SetFont('ARIAL','',10);	  
	  $pdf->Cell(10, 7, '', 0, 1,'L');
	  $pdf->Cell(10, 5, 'Note', 0, 1,'L');
	  $pdf->Cell(10, 5, '* Bubuhkan Nama dan Tanda Tangan yang jelas', 0, 1,'L');
	  $pdf->Cell(10, 5, '* Dikirim dalam keadaan baik, lengkap dan baru', 0, 1,'L');
	  //$pdf->Multicell(10, 5, '* Klaim kerusakan / kehilangan barang kiriman dibenarkan bila ada laporan dari petugas kami \n yang ikut menyerahkan barang', 0, 1,'L');
	   $pdf->Multicell(190,5,"* Klaim kerusakan / kehilangan barang kiriman dibenarkan bila ada laporan dari petugas kami yang ikut \n  menyerahkan barang",0,"L");
	  $pdf->Output(); 
	}
}