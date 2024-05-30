<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyerahan_stnk extends CI_Controller {

    var $tables =   "tr_penyerahan_stnk";	
		var $folder =   "h1";
		var $page		=		"penyerahan_stnk";
    var $pk     =   "no_serah_stnk";
    var $title  =   "Penyerahan STNK Ke Dealer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_penyerahan_stnk_datatables');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('CustomPenyerahanSTNK');		


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
		/*$data['dt_stnk']	= $this->db->query("SELECT * FROM tr_penyerahan_stnk INNER JOIN ms_dealer ON tr_penyerahan_stnk.id_dealer = ms_dealer.id_dealer 
				where tr_penyerahan_stnk.created_at > '2022-01-01' ORDER BY tr_penyerahan_stnk.no_serah_stnk DESC");
		*/
		$this->template($data);			
	}	
	public function fetch_data_penyerahan_stnk_datatables()
	{
		$list = $this->m_penyerahan_stnk_datatables->get_datatables();

		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");
	

        foreach($list as $row) {       
			


			$print = $this->m_admin->set_tombol($id_menu,$group,'print');   

			  if (!empty($row->no_serah_stnk)) {
				$tombol_id_serah_stnk ="<a $print href='h1/penyerahan_stnk/cetak?id=$row->no_serah_stnk' class='btn btn-primary btn-flat btn-xs' target='_blank'>Cetak Tanda Terima</a> ";            
				$link_id_serah_stnk =" <a href='h1/penyerahan_stnk/detail?id=$row->no_serah_stnk'>
				$row->no_serah_stnk
			  </a>";
			}else{
				$no_serah_stnk = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $link_id_serah_stnk;
			$rows[] = $row->tgl_serah_terima;
			$rows[] = $row->nama_dealer;
			$rows[] = $row->alamat;
			$rows[] = $tombol_id_serah_stnk;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_penyerahan_stnk_datatables->count_all(),
			"recordsFiltered" => $this->m_penyerahan_stnk_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		// $data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
		$data['dt_dealer'] = $this->db->query("select id_dealer,kode_dealer_md,nama_dealer from ms_dealer where active = 1 and h1=1 order by nama_dealer asc");
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";		
		$id = $this->input->get('id');
		$data['dt_stnk'] = $this->m_admin->getByID("tr_penyerahan_stnk","no_serah_stnk",$id);
		$this->template($data);			
	}		
	public function t_stnk(){
		$id_dealer = $this->input->post('id_dealer');		
		$data['dt_stnk'] = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
				tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.deskripsi_ahm FROM tr_terima_bj 
				INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
				INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
				INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				WHERE tr_pengajuan_bbn.id_dealer = '$id_dealer' AND tr_terima_bj.status_bj = 'input'  AND tr_terima_bj.serah_stnk IS NULL and tgl_terima_stnk is not null
				GROUP BY tr_pengajuan_bbn_detail.no_mesin");		 		
		$this->load->view('h1/t_penyerahan_stnk',$data);
	}
	public function cari_alamat(){
		$id_dealer = $this->input->post('id_dealer');		
		$cari = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();		 		
		echo $cari->alamat;
	}
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_penyerahan_stnk where left(created_at,4) ='$th' ORDER BY created_at DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_serah_stnk)-9;
			$id 	= substr($row->no_serah_stnk,$pan,6)+1;	

			if($id < 10){
					$kode1 = $th."/"."0000".$id."/SRS";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/SRS";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/SRS";          					
      }elseif($id>999 && $id <= 10000){
					$kode1 = $th."/"."0".$id."/SRS";          					
      }elseif($id>10000){
		$kode1 = $th."/".$id."/SRS";   
	}
			$kode = $kode1;
		}else{
			$kode = $th."/00001/SRS";
		}		
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_serah_stnk 					= $this->cari_id();
		$da['no_serah_stnk'] 		= $no_serah_stnk;
		$da['tgl_serah_terima'] = $tgl;				
		$da['id_dealer'] 				= $this->input->post("id_dealer");
		$da['status_stnk'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_nosin_".$i])){
				$nosin 								= $_POST["no_mesin_".$i];			
				$data['no_serah_stnk'] 		= $no_serah_stnk;
				$data['no_mesin'] 		= $nosin;
				$data["status_nosin"] = "input";
				$this->db->query("UPDATE tr_terima_bj SET serah_stnk = 'ya' WHERE no_mesin = '$nosin'");										

				$cek = $this->db->query("SELECT * FROM tr_penyerahan_stnk_detail WHERE no_mesin = '$nosin'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_penyerahan_stnk_detail",$data,"no_mesin",$nosin);								
				}else{
					$this->m_admin->insert("tr_penyerahan_stnk_detail",$data);								
				}
			}			
		}
			
		$ce = $this->db->query("SELECT * FROM tr_penyerahan_stnk WHERE no_serah_stnk = '$no_serah_stnk'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_penyerahan_stnk",$da,"no_serah_stnk",$no_serah_stnk);								
		}else{
			$this->m_admin->insert("tr_penyerahan_stnk",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penyerahan_stnk'>";
	}
	public function cetak_tenda_terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "cetak_terima";				
		$this->template($data);			
	}	

	public function cetak()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$tgl 				= gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get("id");
		$dt_stnk 		= $this->m_admin->getByID("tr_penyerahan_stnk","no_serah_stnk",$id)->row();
		$dealer 		= $this->m_admin->getByID("ms_dealer","id_dealer",$dt_stnk->id_dealer)->row();

		$detail = $this->db->query("SELECT * FROM tr_penyerahan_stnk_detail INNER JOIN tr_pengajuan_bbn_detail
        ON tr_penyerahan_stnk_detail.no_mesin = tr_pengajuan_bbn_detail.no_mesin 
        WHERE tr_penyerahan_stnk_detail.no_serah_stnk='$id' ORDER BY tr_pengajuan_bbn_detail.nama_konsumen ASC");		
		$jum = $detail->num_rows();

		global $nomor,$lamp,$nama_dealer,$alamat,$tanggal,$ambilY;
		$pdf = $this->custompenyerahanstnk->getInstance();
		$nomor 				= $dt_stnk->no_serah_stnk;
		$lamp 				= $jum;
		$nama_dealer 	= $dealer->nama_dealer;
		$alamat 			= $dealer->alamat;
		$tanggal 			= date_dmy($dt_stnk->tgl_serah_terima);				
	
		$pdf->AliasNbPages();
		$pdf->AddPage('P', 'A4');
		$pdf->SetAutoPageBreak(true, 52);				

		$pdf->SetFont('times', '', 11);			
		$pdf->SetWidths(array(10,70,30,27,32,28));
		srand(microtime()*1000000);
		if ($detail->num_rows()>0) {
			$no=1;
			foreach ($detail->result() as $key => $rs) {
				 $rt = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
                            tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm,tr_pengajuan_bbn_detail.no_faktur FROM tr_terima_bj 
                            INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                            INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                            INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            WHERE tr_terima_bj.no_mesin = '$rs->no_mesin'")->row();
				 $rw = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$rs->no_mesin);
				 if($rw->num_rows() > 0){
					 if($rw->row()->no_pol != ""){
					 	$no_plat = $rw->row()->no_pol;
					 }else if($rt->no_plat != ""){
						$no_plat = $rt->no_plat;
					 }else{
					 	$no_plat = "";
					 }
					}else{
						$no_plat = "";
					}

				 if($rw->num_rows() > 0){
					 if($rt->no_stnk != ""){
					 	$no_stnk = $rt->no_stnk;
					 }elseif($rw->row()->no_stnk != ""){
					 	$no_stnk = $rw->row()->no_stnk;
					 }else{
					 	$no_stnk = "";
					 }
					}else{
						$no_stnk = "";
					}
				$pdf->Row(array($no,$rs->nama_konsumen,$rs->no_mesin,$no_plat,$no_stnk,$rt->no_faktur));    								
				$no++;
			}
		}	
		$pdf->Output();
	}

	public function cetak_ulang()
	{
		$id = $this->input->get('id');
		$dt_stnk = $this->m_admin->getByID("tr_penyerahan_stnk","no_serah_stnk",$id)->row();
		$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$dt_stnk->id_dealer)->row();

		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();
        $detail = $this->db->query("SELECT tr_penyerahan_stnk_detail.no_mesin FROM tr_penyerahan_stnk_detail 
        	INNER JOIN tr_entry_stnk ON tr_penyerahan_stnk_detail.no_mesin = tr_entry_stnk.no_mesin
        	WHERE tr_penyerahan_stnk_detail.no_serah_stnk='$id' ORDER BY tr_entry_stnk.nama_konsumen ASC");
        $jum = $detail->num_rows();
        $pdf->SetFont('TIMES','',11);
		$pdf->Cell(35,6,'Nomor',0,0,'L');
		$pdf->Cell(80,6,': '.$dt_stnk->no_serah_stnk,0,0,'L');
		$pdf->Cell(45,6,'JAMBI',0,1,'L');
		$pdf->Cell(35,6,'LAMPIRAN',0,0,'L');
		$pdf->Cell(80,6,": $jum Lembar",0,0,'L');
		$pdf->Cell(45,6,'Kepada Yth.',0,1,'L');
		$pdf->Cell(35,6,'Perihal',0,0,'L');
		$pdf->Cell(80,6,": PENYERAHAN STNK + NOTICE HONDA",0,0,'L');
		$pdf->Cell(33,6,$dealer->nama_dealer,0,1,'L');
		$pdf->Cell(115,6,'',0,0,'L');
		$pdf->Multicell(95,6,$dealer->alamat,0,1);
		$pdf->Ln(7);
		$pdf->Cell(35,6,'Dengan Hormat,',0,1,'L');

		$pdf->Multicell(190,6,"Dengan ini kami serahkan kepada Bapak/Ibu, STNK + NOTICE HONDA penjualan $dealer->nama_dealer sebanyak $jum lembar dengan keterangan sbb :",0);
		$pdf->Ln(3);
		$pdf->Cell(10,6,'NO.',1,0,'C');
		$pdf->Cell(70,6,'NAMA KONSUMEN',1,0,'C');
		$pdf->Cell(28,6,'NO. MESIN',1,0,'C');
		$pdf->Cell(27,6,'NO. POLISI',1,0,'C');
		$pdf->Cell(32,6,'NO. STNK',1,0,'C');
		$pdf->Cell(28,6,'NO. FAKTUR',1,1,'C');
        $pdf->SetFont('TIMES','',11);
		
		if ($detail->num_rows()>0) {
			$no=1;
			foreach ($detail->result() as $key => $rs) {
				 $rt = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
                            tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm,tr_pengajuan_bbn_detail.no_faktur FROM tr_terima_bj 
                            INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                            INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                            INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            WHERE tr_terima_bj.no_mesin = '$rs->no_mesin'")->row();
				 $rw = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$rs->no_mesin);
				 if($rw->num_rows() > 0){
					 if($rt->no_plat != ""){
					 	$no_plat = $rt->no_plat;
					 }elseif($rw->row()->no_pol != ""){
					 	$no_plat = $rw->row()->no_pol;
					 }else{
					 	$no_plat = "";
					 }
					}else{
						$no_plat = "";
					}

				 if($rw->num_rows() > 0){
					 if($rt->no_stnk != ""){
					 	$no_stnk = $rt->no_stnk;
					 }elseif($rw->row()->no_stnk != ""){
					 	$no_stnk = $rw->row()->no_stnk;
					 }else{
					 	$no_stnk = "";
					 }
					}else{
						$no_stnk = "";
					}
				$pdf->Cell(10,6,$no,1,0,'C');
				$pdf->Cell(70,6,' '.$rt->nama_konsumen,1,0,'L');
				$pdf->Cell(28,6,$rs->no_mesin,1,0,'C');
				$pdf->Cell(27,6,$no_plat,1,0,'C');
				$pdf->Cell(32,6,$no_stnk,1,0,'C');
				$pdf->Cell(28,6,$rt->no_faktur,1,1,'C');
				$no++;
			}
		}
		$pdf->Ln(4);
		$pdf->setX(140);
		$tgl = date('d-m-Y', strtotime(date('y-m-d'))); 
		$pdf->Cell(130,6,'Jambi, '.$tgl,0,1,'L');
		$pdf->setX(10);
		// $pdf->Cell(130,6,'YANG MENERIMA',0,0,'L');
		// $pdf->Cell(97.5,6,'YANG MENGIRIM',0,0,'L');
		$pdf->Ln(4);
		
		$pdf->Cell(63.3,6,'YANG MENYETUJUI',0,0,'C');
		$pdf->Cell(63.3,6,'YANG MENGIRIM',0,0,'C');
		$pdf->Cell(63.3,6,'YANG MENERIMA',0,0,'C');
		$pdf->Ln(30);
		$pdf->Cell(63.3,6,'(Drs. Tony Attan, SH)',0,0,'C');
		$pdf->Cell(63.3,6,'(Admin STNK)',0,0,'C');
		$pdf->Cell(63.3,6,"($dealer->nama_dealer)",0,0,'C');
		$pdf->Output(); 
	}

}