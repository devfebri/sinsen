<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyerahan_bpkb extends CI_Controller {

    var $tables =   "tr_penyerahan_bpkb";	
		var $folder =   "h1";
		var $page		=		"penyerahan_bpkb";
    var $pk     =   "no_serah_bpkb";
    var $title  =   "Penyerahan BPKB Ke Dealer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_penyerahan_bpkb_datatables');			
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('CustomPenyerahanBPKB');		



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
		/*$data['dt_bpkb']	= $this->db->query("SELECT * FROM tr_penyerahan_bpkb INNER JOIN ms_dealer ON tr_penyerahan_bpkb.id_dealer = ms_dealer.id_dealer 
				where tr_penyerahan_bpkb.created_at > '2022-01-01' ORDER BY tr_penyerahan_bpkb.no_serah_bpkb DESC");*/
		$this->template($data);			
	}	
	public function fetch_data_penyerahan_bpkb_datatables()
	{
		$list = $this->m_penyerahan_bpkb_datatables->get_datatables();
		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");
	

        foreach($list as $row) {       

			// $status = $row->status;
			// $po_id = $row->id_po;

			$print = $this->m_admin->set_tombol($id_menu,$group,'print');   

			  if (!empty($row->no_serah_bpkb)) {
				$tombol_id_serah_bpkb ="<a $print href='h1/penyerahan_bpkb/cetak?id=$row->no_serah_bpkb' class='btn btn-primary btn-flat btn-xs' target='_blank'>Cetak Tanda Terima</a> ";            
				$link_id_serah_bpkb =" <a href='h1/penyerahan_bpkb/detail?id=$row->no_serah_bpkb'>
				$row->no_serah_bpkb
			  </a>";
			}else{
				$tombol_id_serah_bpkb = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $link_id_serah_bpkb;
			$rows[] = $row->tgl_serah_terima;
			$rows[] = $row->nama_dealer;
			$rows[] = $row->alamat;
			$rows[] = $tombol_id_serah_bpkb;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_penyerahan_bpkb_datatables->count_all(),
			"recordsFiltered" => $this->m_penyerahan_bpkb_datatables->count_filtered(),
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
		$data['dt_bpkb'] = $this->m_admin->getByID("tr_penyerahan_bpkb","no_serah_bpkb",$id);
		$this->template($data);			
	}		
	public function t_bpkb(){
		$id_dealer = $this->input->post('id_dealer');		
		$data['dt_bpkb'] = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
				tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.deskripsi_ahm FROM tr_terima_bj 
				INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
				INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
				INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				WHERE tr_pengajuan_bbn.id_dealer = '$id_dealer' AND tr_terima_bj.status_bj = 'input' AND tr_terima_bj.serah_bpkb IS NULL and tgl_terima_bpkb is not null
				GROUP BY tr_terima_bj.no_mesin");		 		
		$this->load->view('h1/t_penyerahan_bpkb',$data);
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
				
		$pr_num = $this->db->query("SELECT * FROM tr_penyerahan_bpkb where left(created_at,4) = '$th' ORDER BY created_at DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_serah_bpkb)-9;
			$id 	= substr($row->no_serah_bpkb,$pan,6)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/SRB";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/SRB";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/SRB";          					
      }elseif($id>999 && $id<=10000){
					$kode1 = $th."/"."0".$id."/SRB";          					
      }elseif($id>10000){
		$kode1 = $th."/".$id."/SRB";
	}
			$kode = $kode1;
		}else{
			$kode = $th."/00001/SRB";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_serah_bpkb 					= $this->cari_id();
		$da['no_serah_bpkb'] 		= $no_serah_bpkb;
		$da['tgl_serah_terima'] = $tgl;				
		$da['id_dealer'] 				= $this->input->post("id_dealer");
		$da['status_bpkb'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_nosin_".$i])){
				$nosin 								= $_POST["no_mesin_".$i];			
				$data['no_serah_bpkb'] 		= $no_serah_bpkb;
				$data['no_mesin'] 		= $nosin;
				$data["status_nosin"] = "input";
				$this->db->query("UPDATE tr_terima_bj SET serah_bpkb = 'ya' WHERE no_mesin = '$nosin'");										

				$cek = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail WHERE no_mesin = '$nosin'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_penyerahan_bpkb_detail",$data,"no_mesin",$nosin);								
				}else{
					$this->m_admin->insert("tr_penyerahan_bpkb_detail",$data);								
				}
			}			
		}
			
		$ce = $this->db->query("SELECT * FROM tr_penyerahan_bpkb WHERE no_serah_bpkb = '$no_serah_bpkb'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_penyerahan_bpkb",$da,"no_serah_bpkb",$no_serah_bpkb);								
		}else{
			$this->m_admin->insert("tr_penyerahan_bpkb",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penyerahan_bpkb'>";
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
		$dt_bpkb = $this->m_admin->getByID("tr_penyerahan_bpkb","no_serah_bpkb",$id)->row();
		$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$dt_bpkb->id_dealer)->row();

		$detail = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail INNER JOIN tr_pengajuan_bbn_detail
        ON tr_penyerahan_bpkb_detail.no_mesin = tr_pengajuan_bbn_detail.no_mesin 
        WHERE tr_penyerahan_bpkb_detail.no_serah_bpkb='$id' ORDER BY tr_pengajuan_bbn_detail.nama_konsumen ASC");		
		$jum = $detail->num_rows();

		global $nomor,$lamp,$nama_dealer,$alamat,$tanggal,$ambilY;
		$pdf = $this->custompenyerahanbpkb->getInstance();
		$nomor 				= $dt_bpkb->no_serah_bpkb;
		$lamp 				= $jum;
		$nama_dealer 	= $dealer->nama_dealer;
		$alamat 			= $dealer->alamat;
		$tanggal 			= date_dmy($dt_bpkb->tgl_serah_terima);			
	
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
				$pdf->Row(array($no,$rs->nama_konsumen,$rs->no_mesin,$rt->no_plat,$rt->no_bpkb,$rt->no_faktur));    								
				$no++;
			}
		}	
		$pdf->Output();
	}
	public function cetak_ulang()
	{
		$id = $this->input->get('id');
		$dt_bpkb = $this->m_admin->getByID("tr_penyerahan_bpkb","no_serah_bpkb",$id)->row();
		$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$dt_bpkb->id_dealer)->row();

		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();
    $detail = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail INNER JOIN tr_pengajuan_bbn_detail
        ON tr_penyerahan_bpkb_detail.no_mesin = tr_pengajuan_bbn_detail.no_mesin 
        WHERE tr_penyerahan_bpkb_detail.no_serah_bpkb='$id' ORDER BY tr_pengajuan_bbn_detail.nama_konsumen ASC");
        $jum = $detail->num_rows();
        $pdf->SetFont('TIMES','',12);
		$pdf->Cell(35,6,'Nomor',0,0,'L');
		$pdf->Cell(65,6,': '.$dt_bpkb->no_serah_bpkb,0,0,'L');
		$pdf->Cell(35,6,'JAMBI',0,1,'L');
		$pdf->Cell(35,6,'LAMPIRAN',0,0,'L');
		$pdf->Cell(65,6,": $jum Buku",0,0,'L');
		$pdf->Cell(35,6,'Kepada Yth.',0,1,'L');
		$pdf->Cell(35,6,'Perihal',0,0,'L');
		$pdf->Cell(65,6,": PENYERAHAN BPKB HONDA",0,0,'L');
		$pdf->Cell(35,6,$dealer->nama_dealer,0,1,'L');
		$pdf->Cell(100,6,'',0,0,'L');
		$pdf->Multicell(95,6,$dealer->alamat,0,1);
		$pdf->Ln(7);
		$pdf->Cell(35,6,'Dengan Hormat,',0,1,'L');

		$pdf->Multicell(190,6,"Dengan ini kami serahkan kepada Bapak/Ibu, BPKB HONDA penjualan $dealer->nama_dealer sebanyak $jum buku dengan keterangan sbb :",0);
		$pdf->Ln(3);
		$pdf->Cell(10,6,'NO.',1,0,'C');
		$pdf->Cell(70,6,'NAMA KONSUMEN',1,0,'C');
		$pdf->Cell(28,6,'NO. MESIN',1,0,'C');
		$pdf->Cell(27,6,'NO. POLISI',1,0,'C');
		$pdf->Cell(32,6,'NO. BPKB',1,0,'C');
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
				$pdf->Cell(10,6,$no,1,0,'C');
				$pdf->Cell(70,6,' '.$rs->nama_konsumen,1,0,'L');
				$pdf->Cell(28,6,$rs->no_mesin,1,0,'C');
				$pdf->Cell(27,6,$rt->no_plat,1,0,'C');
				$pdf->Cell(32,6,$rt->no_bpkb,1,0,'C');
				$pdf->Cell(28,6,$rt->no_faktur,1,1,'C');
				$no++;
			}
		}
		$pdf->Ln(4);
		$pdf->setX(140);
		$tgl = date('d-m-Y', strtotime(date('y-m-d'))); 
		$pdf->Cell(130,6,'Jambi, '.$tgl,0,1,'L');
		$pdf->setX(10);
		$pdf->Ln(4);
		
		$pdf->Cell(63.3,6,'YANG MENYETUJUI',0,0,'C');
		$pdf->Cell(63.3,6,'YANG MENGIRIM',0,0,'C');
		$pdf->Cell(63.3,6,'YANG MENERIMA',0,0,'C');
		$pdf->Ln(30);
		$pdf->Cell(63.3,6,'(Drs. Tony Attan, SH)',0,0,'C');
		$pdf->Cell(63.3,6,'(Admin BPKB)',0,0,'C');
		$pdf->Cell(63.3,6,"($dealer->nama_dealer)",0,0,'C');

		// $pdf->Cell(130,6,'YANG MENERIMA',0,0,'L');
		// $pdf->Cell(97.5,6,'YANG MENGIRIM',0,0,'L');
		$pdf->Output(); 
	}	
}

