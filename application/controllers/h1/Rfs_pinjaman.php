<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rfs_pinjaman extends CI_Controller {
    var $tables_header =   "tr_rfs_pinjaman";	
    var $tables_detail =   "tr_rfs_pinjaman_detail";	
    var $detail_ksu =   "tr_rfs_pinjaman_detail_ksu";	
		var $folder =   "h1";
		var $page		=		"rfs_pinjaman";
    var $pk     =   "id_scan_ubah";
    var $title  =   "Ubah Status RFS ke Pinjaman";
	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url','string');
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
		$data['dt_scan_ubah'] = $this->db->query("SELECT DISTINCT(id_scan_ubah) FROM tr_scan_ubah WHERE jenis_ubah = 'NRFS-RFS' ORDER BY id_scan_ubah DESC");	
		$data['rfs_pinjaman'] = $this->db->query("SELECT * FROM $this->tables_header ORDER BY id_rfs_pinjaman DESC");					
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";		
		$this->template($data);	
	}	
	public function t_detail()
	{
		$id_gudang = $this->input->post("id_gudang");
	/*	$data['mesin'] = $this->db->query("select tr_scan_barcode.id_scan_barcode, tr_scan_barcode.no_mesin, tr_scan_barcode.lokasi, tr_scan_barcode.id_item,tr_scan_barcode.tipe_motor, tr_scan_barcode.warna as id_warna, ms_warna.warna,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm 
			from tr_scan_barcode
left join ms_lokasi_unit on tr_scan_barcode.lokasi=ms_lokasi_unit.id_lokasi_unit
left join ms_gudang on ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor =ms_tipe_kendaraan.id_tipe_kendaraan
where tr_scan_barcode.status=1 and tr_scan_barcode.tipe='RFS'"); */
		$data['mesin'] = $this->db->query("select tr_scan_barcode.id_scan_barcode, tr_scan_barcode.no_mesin, tr_scan_barcode.lokasi, tr_scan_barcode.id_item,tr_scan_barcode.tipe_motor, tr_scan_barcode.warna as id_warna, ms_warna.warna,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm 
			from tr_scan_barcode
left join ms_lokasi_unit on tr_scan_barcode.lokasi=ms_lokasi_unit.id_lokasi_unit
left join ms_gudang on ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor =ms_tipe_kendaraan.id_tipe_kendaraan
where tr_scan_barcode.status=1 and tr_scan_barcode.tipe='RFS' and ms_lokasi_unit.id_gudang='$id_gudang'");
		$cek_id_rfs_pinjaman = $this->db->query("select max(id_rfs_pinjaman) as max_id from tr_rfs_pinjaman")->row()->max_id;
		if ($cek_id_rfs_pinjaman==null) {
			$id_rfs_pinjaman_new = 1;
		}else
		{
			$id_rfs_pinjaman_new=$cek_id_rfs_pinjaman+1;
		}
	$data['rfs_pinjaman_detail']=$this->db->query("SELECT tr_rfs_pinjaman_detail.id_rfs_pinjaman_detail,tr_rfs_pinjaman_detail.id_rfs_pinjaman,tr_rfs_pinjaman_detail.no_mesin,tr_rfs_pinjaman_detail.warna as id_warna, tr_rfs_pinjaman_detail.id_item, ms_warna.*, tr_rfs_pinjaman_detail.tipe_motor, ms_tipe_kendaraan.tipe_ahm,tr_rfs_pinjaman_detail.keterangan
		 from tr_rfs_pinjaman_detail
			left join ms_warna on tr_rfs_pinjaman_detail.warna = ms_warna.id_warna
			left join ms_tipe_kendaraan on tr_rfs_pinjaman_detail.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan where id_rfs_pinjaman = '$id_rfs_pinjaman_new'
			");
		$this->load->view("h1/t_detail_rfs_pinjaman",$data);
	}
	public function addDetail(){
		$id_gudang = $this->input->post("id_gudang");
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$cek_id_rfs_pinjaman = $this->db->query("select max(id_rfs_pinjaman) as max_id from tr_rfs_pinjaman")->row()->max_id;
		if ($cek_id_rfs_pinjaman==null) {
			$id_rfs_pinjaman_new = 1;
		}else
		{
			$id_rfs_pinjaman_new=$cek_id_rfs_pinjaman+1;
		}
		$no_mesin = $this->input->post('no_mesin');
		$cek_id_rfs_pinjaman_detail_new=$this->db->query("SELECT * FROM tr_rfs_pinjaman_detail WHERE id_rfs_pinjaman='$id_rfs_pinjaman_new' AND no_mesin ='$no_mesin' ");
		$tipe_motor = $this->input->post('tipe_motor');
		$id_warna = $this->input->post('id_warna');
		if ($cek_id_rfs_pinjaman_detail_new->num_rows() ==0 ) {
			$data_insert = array('id_rfs_pinjaman' => $id_rfs_pinjaman_new,
						'id_scan_barcode' => $this->input->post('id_scan_barcode'),
						'no_mesin' => $this->input->post('no_mesin'),
						'lokasi' => $this->input->post('lokasi'),
						'id_item' => $this->input->post('id_item'),
						'warna' => $this->input->post('id_warna'),
						'keterangan' => $this->input->post('keterangan'),
						'tipe_motor' => $tipe_motor,
				);
				$cek = $this->db->query("SELECT id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$tipe_motor'");
                if(count($cek) > 0){
                  $isi = $cek->row();
                  foreach ($cek->result() as $key => $isi) {                    
                    $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                    if(count($cek2) > 0){
                      $rd = $cek2->row();
                      // $rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$rd->id_ksu' AND id_warna = '$id_warna' AND id_tipe_kendaraan = '$tipe_motor'");
                      // if($rty->num_rows() >0 ){
                      // 	$rty->row();
                      		$data_ksu[$key] = array('id_rfs_pinjaman' => $id_rfs_pinjaman_new,
									'no_mesin' => $this->input->post('no_mesin'),
									'id_ksu' => $isi->id_ksu,
									'checked' => 0,
									);
                      //}
                    }
                  }                  
                }
					$this->m_admin->insert($this->tables_detail, $data_insert);
					if (count($data_ksu) > 0) {
							$this->db->insert_batch($this->detail_ksu, $data_ksu);
						}	
						
		}
		$data['mesin'] = $this->db->query("select tr_scan_barcode.id_scan_barcode, tr_scan_barcode.no_mesin, tr_scan_barcode.lokasi, tr_scan_barcode.id_item,tr_scan_barcode.tipe_motor, tr_scan_barcode.warna as id_warna, ms_warna.warna,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm 
			from tr_scan_barcode
left join ms_lokasi_unit on tr_scan_barcode.lokasi=ms_lokasi_unit.id_lokasi_unit
left join ms_gudang on ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor =ms_tipe_kendaraan.id_tipe_kendaraan
where tr_scan_barcode.status=1 and tr_scan_barcode.tipe='RFS'");
		
	$data['rfs_pinjaman_detail']=$this->db->query("SELECT tr_rfs_pinjaman_detail.id_rfs_pinjaman_detail,tr_rfs_pinjaman_detail.id_rfs_pinjaman,tr_rfs_pinjaman_detail.no_mesin,tr_rfs_pinjaman_detail.warna as id_warna, tr_rfs_pinjaman_detail.id_item,tr_rfs_pinjaman_detail.keterangan, ms_warna.*, tr_rfs_pinjaman_detail.tipe_motor, ms_tipe_kendaraan.tipe_ahm
		 from tr_rfs_pinjaman_detail
			left join ms_warna on tr_rfs_pinjaman_detail.warna = ms_warna.id_warna
			left join ms_tipe_kendaraan on tr_rfs_pinjaman_detail.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan where id_rfs_pinjaman = '$id_rfs_pinjaman_new'
			");
		$this->load->view("h1/t_detail_rfs_pinjaman",$data);
	}
	public function deleteDetail(){
		
		$id_rfs_pinjaman_detail = $this->input->post("id_rfs_pinjaman_detail");
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');	
		$cek_id_rfs_pinjaman = $this->db->query("select max(id_rfs_pinjaman) as max_id from tr_rfs_pinjaman")->row()->max_id;
		if ($cek_id_rfs_pinjaman==null) {
			$id_rfs_pinjaman_new = 1;
		}else
		{
			$id_rfs_pinjaman_new=$cek_id_rfs_pinjaman+1;
		}
		$detail =$this->db->query("SELECT * from tr_rfs_pinjaman_detail where id_rfs_pinjaman_detail = '$id_rfs_pinjaman_detail' ")->row();
		$this->m_admin->delete($this->tables_detail, "id_rfs_pinjaman_detail", $id_rfs_pinjaman_detail);
		$this->db->query("DELETE FROM tr_rfs_pinjaman_detail_ksu where id_rfs_pinjaman = '$detail->id_rfs_pinjaman' AND no_mesin='$detail->no_mesin'");
		if (!!$this->input->post("id_gudang")) {
			$id_gudang = $this->input->post("id_gudang");
			$data['mesin'] = $this->db->query("select tr_scan_barcode.id_scan_barcode, tr_scan_barcode.no_mesin, tr_scan_barcode.lokasi, tr_scan_barcode.id_item,tr_scan_barcode.tipe_motor, tr_scan_barcode.warna as id_warna, ms_warna.warna,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm 
			from tr_scan_barcode
left join ms_lokasi_unit on tr_scan_barcode.lokasi=ms_lokasi_unit.id_lokasi_unit
left join ms_gudang on ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor =ms_tipe_kendaraan.id_tipe_kendaraan
where ms_gudang.id_gudang='$id_gudang' and tr_scan_barcode.status=1 and tr_scan_barcode.tipe='RFS'");
		}
		
	$data['rfs_pinjaman_detail']=$this->db->query("SELECT tr_rfs_pinjaman_detail.id_rfs_pinjaman_detail,tr_rfs_pinjaman_detail.id_rfs_pinjaman,tr_rfs_pinjaman_detail.no_mesin,tr_rfs_pinjaman_detail.warna as id_warna, tr_rfs_pinjaman_detail.id_item, ms_warna.*, tr_rfs_pinjaman_detail.tipe_motor, ms_tipe_kendaraan.tipe_ahm,tr_rfs_pinjaman_detail.keterangan
		 from tr_rfs_pinjaman_detail
			left join ms_warna on tr_rfs_pinjaman_detail.warna = ms_warna.id_warna
			left join ms_tipe_kendaraan on tr_rfs_pinjaman_detail.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan where id_rfs_pinjaman = '$id_rfs_pinjaman_new'
			");
		$this->load->view("h1/t_detail_rfs_pinjaman",$data);
	}
	public function checkedRfs()
	{
		$checked = $this->input->post('checked');
		$id_rfs_ksu = $this->input->post('id_rfs_ksu');
		if ($checked=='true') {
			$checked =1;
		}elseif($checked=='false')
		{
			$checked=0;
		}
		$data=array('checked'=> $checked);
		$this->m_admin->update($this->detail_ksu,$data,"id",$id_rfs_ksu);
	}
	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_rfs_pinjaman = $this->input->post('id_rfs_pinjaman');
		$keterangan 	= $this->input->post('keterangan');
		$tgl_pinjaman = $this->input->post('tgl_pinjaman');
		$status = 'Waiting Approval';
		if($this->input->post('ksu') == '1') $ksu = $this->input->post('ksu');		
				else $ksu 		= "";
		$data_insert = array('id_rfs_pinjaman' => $id_rfs_pinjaman,
						'keterangan' => $keterangan,
						'tgl_pinjaman' => $tgl_pinjaman,
						'status' => $status,
						//'ksu' => $ksu,
						'created_at' => $waktu,
						'created_by' => $login_id,
						'status' => $status,
				);
		$this->m_admin->insert($this->tables_header, $data_insert);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_pinjaman/add'>";
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;		
		$data['set']		= "detail";			
		$id 						= $this->input->get('id');		
		$data['dt_pinjaman'] = $this->db->query("SELECT tr_rfs_pinjaman_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.* FROM tr_rfs_pinjaman_detail 
				INNER JOIN ms_tipe_kendaraan ON tr_rfs_pinjaman_detail.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_rfs_pinjaman_detail.warna = ms_warna.id_warna
				WHERE tr_rfs_pinjaman_detail.id_rfs_pinjaman = '$id'");	
		$data['dt_p']  = $this->m_admin->getByID("tr_rfs_pinjaman","id_rfs_pinjaman",$id);						
		$this->template($data);	
	}
	public function pengembalian()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Pengembalian ".$this->title;		
		$data['set']		= "pengembalian";			
		$id 						= $this->input->get('id');		
		$data['dt_pinjaman'] = $this->db->query("SELECT tr_rfs_pinjaman_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.* FROM tr_rfs_pinjaman_detail 
				INNER JOIN ms_tipe_kendaraan ON tr_rfs_pinjaman_detail.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_rfs_pinjaman_detail.warna = ms_warna.id_warna
				WHERE tr_rfs_pinjaman_detail.id_rfs_pinjaman = '$id'");	
		$data['dt_p']  = $this->m_admin->getByID("tr_rfs_pinjaman","id_rfs_pinjaman",$id);						
		$this->template($data);	
	}
	public function save_pengembalian()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables_header;
		$pk 				= "id_rfs_pinjaman";
		$id					= $this->input->post("id");		
		$jum				= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			$no_mesin = $this->input->post('no_mesin_'.$i);
			$terima 	= $this->input->post('terima_'.$i);
			$this->db->query("UPDATE tr_rfs_pinjaman_detail SET terima = '$terima' WHERE no_mesin = '$no_mesin'");
		}		
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_pinjaman'>";		
	}
	public function approve()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables_header;
		$pk 				= "id_rfs_pinjaman";
		$id					= $this->input->get("id");		
		$data['status'] 					= "approved";			
		$data['update_at']				= $waktu;		
		$data['updated_by']				= $login_id;		
		
		$rt = $this->m_admin->getByID("tr_rfs_pinjaman_detail","id_rfs_pinjaman",$id);
		foreach ($rt->result() as $isi) {
			$no_mesin = $isi->no_mesin;
			$this->db->query("UPDATE tr_scan_barcode SET lokasi = '',slot ='',tipe = 'PINJAMAN' WHERE no_mesin = '$no_mesin'");
			$this->m_admin->set_log($no_mesin,"PINJAMAN","-");
		}
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_pinjaman'>";		
	}
	public function reject()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables_header;
		$pk 				= "id_rfs_pinjaman";			
		$id					= $this->input->get("id");			
		$data['status'] 					= "rejected";			
		$data['update_at']				= $waktu;		
		$data['updated_by']				= $login_id;		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_pinjaman'>";		
	}
	public function cetak_pl()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables_header;
		$pk 				= "id_rfs_pinjaman";			
		$id					= $this->input->get("id");			
		$data['status_cetak'] 					= "cetak_pl";			
		$data['update_at']				= $waktu;		
		$data['updated_by']				= $login_id;		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
       // head
	  $pdf->SetFont('ARIAL','B',13);
	  $pdf->Cell(190, 5, 'PICKING LIST PEMINJAMAN UNIT', 0, 1, 'C');
	  $pdf->Ln(5);
	  $pdf->SetFont('ARIAL','',10);
	  $rfs_pinj = $this->db->query("SELECT * FROM tr_rfs_pinjaman WHERE tr_rfs_pinjaman.id_rfs_pinjaman='$id' ");
	  if ($rfs_pinj->num_rows()>0) {
	  	$r=$rfs_pinj->row();
		  $pdf->Cell(50, 5, 'Tanggal Cetak Picking List', 0, 0, 'L');
		  $tgl 	= date('d-m-Y', strtotime(date('Y-m-d')));
		  $pdf->Cell(60, 5, ': '.$tgl, 0, 1, 'L');
		  $pdf->Cell(50, 5, 'Keterangan', 0, 0, 'L');
		  $pdf->Cell(60, 5, ': '.$r->keterangan, 0, 1, 'L');
	  }
	 	$pdf->Ln(4);
	  $pdf->SetFont('ARIAL','B',11);
	  $pdf->Cell(190, 6, 'DETAIL UNIT', 1, 1, 'C');
	  $pdf->SetFont('ARIAL','B',10);
	  $pdf->Cell(28, 6, 'No Mesin', 1, 0, 'C');
	  $pdf->Cell(28, 6, 'No Rangka', 1, 0, 'C');
	  $pdf->Cell(52	, 6, 'Tipe', 1, 0, 'C');
	  $pdf->Cell(42, 6, 'Warna', 1, 0, 'C');
	  $pdf->Cell(20, 6, 'Lokasi', 1, 0, 'C');
	  $pdf->Cell(20, 6, 'Konfirmasi', 1, 1, 'C');
	  $pdf->SetFont('ARIAL','',9);
	  $pinj = $this->db->query("SELECT tr_rfs_pinjaman_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.* FROM tr_rfs_pinjaman_detail 
				INNER JOIN ms_tipe_kendaraan ON tr_rfs_pinjaman_detail.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_rfs_pinjaman_detail.warna = ms_warna.id_warna
				WHERE tr_rfs_pinjaman_detail.id_rfs_pinjaman = '$id'");	
	  if ($pinj->num_rows() >0 ) {
	  	  foreach ($pinj->result() as $key => $rs) {
	  	  	$rangka = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin='$rs->no_mesin' ");
	  	  	if ($rangka->num_rows()>0) {
	  	  		$rangka=$rangka->row()->no_rangka;
	  	  	}else{
	  	  		$rangka='';
	  	  	}
		  	  $pdf->Cell(28, 5, $rs->no_mesin, 1, 0, 'C');
			  $pdf->Cell(28, 5, $rangka, 1, 0, 'C');
			  $pdf->Cell(52	, 5, $rs->tipe_ahm, 1, 0, 'L');
			  $pdf->Cell(42, 5, $rs->warna, 1, 0, 'L');
			  $pdf->Cell(20, 5, $rs->lokasi, 1, 0, 'C');
			  $pdf->Cell(20, 5, strtoupper($rs->konfirmasi), 1, 1, 'C');
	  	  }
	  }
	  $pdf->Ln(7);
	   $pdf->Cell(170, 6, 'DETAIL AKSESORIS', 1, 1, 'C');
	  $pdf->SetFont('ARIAL','B',10);
	  $pdf->Cell(56, 6, 'Kode Aksesoris', 1, 0, 'C');
	  $pdf->Cell(94, 6, 'Nama Aksesoris', 1, 0, 'C');
	  $pdf->Cell(20, 6, 'Qty', 1, 1, 'C');
	  $pdf->SetFont('ARIAL','',9);
	  $ksu = $this->db->query("SELECT count(tr_rfs_pinjaman_detail_ksu.id_ksu) as jum,tr_rfs_pinjaman_detail_ksu.id_ksu,ms_ksu.ksu FROM tr_rfs_pinjaman_detail_ksu 
	  	left join ms_ksu on tr_rfs_pinjaman_detail_ksu.id_ksu=ms_ksu.id_ksu	
	  	WHERE id_rfs_pinjaman='$id' AND tr_rfs_pinjaman_detail_ksu.checked=1 GROUP BY tr_rfs_pinjaman_detail_ksu.id_ksu");
	  if ($ksu->num_rows() > 0) {
	  	foreach ($ksu->result() as $key => $rs_ksu) {
	  	  $pdf->Cell(56, 6, $rs_ksu->id_ksu, 1, 0, 'C');
		  $pdf->Cell(94, 6, $rs_ksu->ksu, 1, 0, 'L');
		  $pdf->Cell(20, 6, $rs_ksu->jum, 1, 1, 'C');
	  	}
	  }
	  $pdf->SetFont('ARIAL','',10);
	  $pdf->Ln(9);
	  $pdf->Cell(150, 6, 'Diperiksa Oleh', 0, 1, 'C');
	  $pdf->Cell(75, 6, 'Sebelum,', 0, 0, 'C');
	  $pdf->Cell(75, 6, 'Sesudah,', 0, 1, 'C');
	  $pdf->Ln(19);
	  $pdf->Cell(75, 6, '(Kepala Gudang)', 0, 0, 'C');
	  $pdf->Cell(75, 6, '(Kepala Gudang)', 0, 1, 'C');
	  $pdf->Output(); 
	  	
	}
	public function konfirmasi()
	{	
		$submit = $this->input->post('submit_konfirmasi');
		if (isset($submit)) {
				$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables_header;
			$pk 				= "id_rfs_pinjaman";	
			$id = $this->input->post('id_rfs_pinjaman');		
			$data['status_cetak'] 					= "konfirmasi";			
			$data['update_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			
			$this->m_admin->update($tabel,$data,$pk,$id);
			$id_rfs_pinjaman_detail = $this->input->post('id_rfs_pinjaman_detail');
			$count = $this->input->post('count');
			for ($i=1; $i <= $count; $i++) {
		$checked = $this->input->post('konfirmasi_'.$i); 
			if (isset($checked)) {
				$konfirmasi = 'ya';
			}else{
				$konfirmasi = 	'tidak';
			}
			$dt['konfirmasi'] = $konfirmasi;
			$this->m_admin->update('tr_rfs_pinjaman_detail',$dt,'id_rfs_pinjaman_detail',$this->input->post('id_rfs_pinjaman_detail_'.$i));
		}
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_pinjaman'>";	
		}else{
		$id					= $this->input->get("id");			
			$data['isi']    = $this->page;		
		$data['title']	= "Konfirmasi ".$this->title;		
		$data['set']		= "konfirmasi";
		$data['dt_pinjaman'] = $this->db->query("SELECT tr_rfs_pinjaman_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.* FROM tr_rfs_pinjaman_detail 
				INNER JOIN ms_tipe_kendaraan ON tr_rfs_pinjaman_detail.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_rfs_pinjaman_detail.warna = ms_warna.id_warna
				WHERE tr_rfs_pinjaman_detail.id_rfs_pinjaman = '$id'");	
		$data['dt_p']  = $this->m_admin->getByID("tr_rfs_pinjaman","id_rfs_pinjaman",$id);	
		$this->template($data);	
		}
			
	}
	public function cetak_sj()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables_header;
		$pk 				= "id_rfs_pinjaman";			
		$id					= $this->input->get("id");	
		$tgl_sj			= date('y-m-d');		
		$cek = $this->db->query("SELECT * FROM tr_rfs_pinjaman WHERE id_rfs_pinjaman  = '$id' ")->row();
		if ($cek->no_sj == null or $cek->no_sj=='') {
		
			$bln = date('m');
			$th = date('Y');
			$cek = $this->db->query("SELECT * FROM tr_rfs_pinjaman order by no_sj desc, tgl_sj desc limit 0,1");
			if ($cek->num_rows() > 0) {
				$cek = $cek->row();
				$no_sj_old = explode('/', $cek->no_sj);
				if (count($no_sj_old) > 1) {
					if ($no_sj_old[3] == $bln) {
		 				$no_sj 	= sprintf("%'.03d",$no_sj_old[0]+1).'/SJ/PINJAM/'.$bln.'/'.$th;			
					}else{
						$no_sj = "001/SJ/PINJAM/$bln/$th";
					}
				}else{
						$no_sj = "001/SJ/PINJAM/$bln/$th";
				}
			}else{
				$no_sj = "001/SJ/PINJAM/$bln/$th";
			}
			$data['no_sj'] 					= $no_sj;			
			$data['tgl_sj'] 					= $tgl_sj;			
			$data['status_cetak'] 					= "cetak_sj";			
			$data['update_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$pdf = new FPDF('p','mm','A4');
			$pdf->AddPage();
	       // head
			$row = $this->db->query("SELECT * FROM tr_rfs_pinjaman WHERE id_rfs_pinjaman = '$id' ")->row();
		  $pdf->SetFont('ARIAL','B',13);
		  $pdf->Cell(190, 5, 'SURAT JALAN PEMINJAMAN UNIT', 0, 1, 'C');
		  $pdf->Cell(190, 8, '', 0, 1, 'C');
		  $pdf->SetFont('ARIAL','',9);
		   $pdf->Cell(40, 5, 'No. Surat Jalan', 0, 0, 'L');
		  $pdf->Cell(90, 5, ': '.$row->no_sj, 0, 1, 'L');
		  $pdf->Cell(40, 5, 'Tanggal Surat Jalan', 0, 0, 'L');
		  $pdf->Cell(90, 5, ': '.$row->tgl_sj, 0, 1, 'L');
		  $pdf->Cell(40, 5, 'Keterangan Pinjaman', 0, 0, 'L');
		  $pdf->Cell(90, 5, ': '.$row->keterangan, 0, 1, 'L');
		  $pdf->Cell(90, 5, '', 0, 1, 'L');
		  $pdf->Cell(190, 5, 'DETAIL UNIT', 1, 1, 'C');
		  $pdf->Cell(10, 5, 'No.', 1, 0, 'C');
		  $pdf->Cell(30, 5, 'No. Mesin', 1, 0, 'C');
		  $pdf->Cell(33, 5, 'No. Rangka', 1, 0, 'C');
		  $pdf->Cell(27, 5, 'Kode Tipe', 1, 0, 'C');
		  $pdf->Cell(30, 5, 'Warna', 1, 0, 'C');
		  $pdf->Cell(25, 5, 'Lokasi', 1, 0, 'C');
		  $pdf->Cell(35, 5, 'Keterangan', 1, 1, 'C');
			$result = $this->db->query("SELECT tr_rfs_pinjaman_detail.no_mesin, tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,tr_rfs_pinjaman_detail.lokasi,ms_warna.warna, tr_rfs_pinjaman_detail.keterangan FROM tr_rfs_pinjaman_detail 
							left join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_rfs_pinjaman_detail.no_mesin
							left join ms_warna on ms_warna.id_warna = tr_scan_barcode.warna
				WHERE id_rfs_pinjaman = '$id' 
				")->result();
			$no=1;
		  foreach ($result as $res) {
		  	$pdf->Cell(10, 5, $no, 1, 0, 'C');
			  $pdf->Cell(30, 5, $res->no_mesin, 1, 0, 'C');
			  $pdf->Cell(33, 5, $res->no_rangka, 1, 0, 'C');
			  $pdf->Cell(27, 5, $res->tipe_motor, 1, 0, 'C');
			  $pdf->Cell(30, 5, $res->warna, 1, 0, 'C');
			  $pdf->Cell(25, 5, $res->lokasi, 1, 0, 'C');
			  $pdf->Cell(35, 5, $res->keterangan, 1, 1, 'C');
			  $no++;
		  }
		  $pdf->Cell(35, 5, '', 0, 1, 'C');
		  $ksu = $this->db->query("SELECT *,count(tr_rfs_pinjaman_detail_ksu.id_ksu) as qty FROM `tr_rfs_pinjaman_detail_ksu` 
		  				inner join ms_ksu on tr_rfs_pinjaman_detail_ksu.id_ksu = ms_ksu.id_ksu
		  	WHERE tr_rfs_pinjaman_detail_ksu.id_rfs_pinjaman='$id' group by tr_rfs_pinjaman_detail_ksu.id_ksu order by tr_rfs_pinjaman_detail_ksu.id_ksu ASC");
		  	  $pdf->Cell(10, 5, 'No.', 1, 0, 'C');
			  $pdf->Cell(30, 5, 'Kode Aksesoris', 1, 0, 'C');
			  $pdf->Cell(60, 5, 'Nama Aksesoris', 1, 0, 'C');
			  $pdf->Cell(27, 5, 'Qty', 1, 1, 'C');
			  $no=1;
			  foreach ($ksu->result() as $ksu) {
			  	$pdf->Cell(10, 5, $no, 1, 0, 'C');
			  $pdf->Cell(30, 5, $ksu->id_ksu, 1, 0, 'C');
			  $pdf->Cell(60, 5, $ksu->ksu, 1, 0, 'C');
			  $pdf->Cell(27, 5, $ksu->qty, 1, 1, 'C');
			  $no++;
			  }
		  $pdf->Cell(35, 5, '', 0, 1, 'C');
		  $pdf->Cell(190, 5, 'Diterima dalam keadaan baik, apabila ada kerusakan akan menjadi tanggung jawab peminjam.', 0, 1, 'L');
		  $pdf->Cell(190, 5, 'Dikembalikan tanggal ________________________.', 0, 1, 'L');
		  $pdf->Cell(190, 8, '', 0, 1, 'L');
		  $pdf->setX(15);
		  $pdf->Cell(60, 5, 'Dibuat Oleh', 0, 0, 'C');$pdf->Cell(60, 5, 'Dikeluarkan Oleh', 0, 0, 'C');$pdf->Cell(60, 5, 'Diterima Oleh', 0, 1, 'C');
		  $pdf->Cell(35, 14, '', 0, 1, 'C');
		  $pdf->setX(15);
		  $pdf->Cell(60, 5, '( Admin )', 0, 0, 'C');$pdf->Cell(60, 5, '(                                               )', 0, 0, 'C');$pdf->Cell(60, 5, '(                                               )', 0, 1, 'C');
		  $pdf->Output();	 
		}
		else{
			$_SESSION['pesan'] 	= "Surat Jalan Sudah Dicetak";
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_pinjaman'>";
		}
	  	
	}
	public function cetak_bast()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 			= gmdate("d/m/Y", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables_header;
		$pk 				= "id_rfs_pinjaman";			
		$id					= $this->input->get("id");	
		$tgl_sj			= date('y-m-d');		
		$cek = $this->db->query("SELECT * FROM tr_rfs_pinjaman WHERE id_rfs_pinjaman  = '$id' ")->row();
		if ($cek->no_sj == null or $cek->no_sj!='') {
		
			$bln = date('m');
			$th = date('Y');
			$cek = $this->db->query("SELECT * FROM tr_rfs_pinjaman order by no_sj desc, tgl_sj desc limit 0,1");
			if ($cek->num_rows() > 0) {
				$cek = $cek->row();
				$no_sj_old = explode('/', $cek->no_sj);
				if (count($no_sj_old) > 1) {
					if ($no_sj_old[3] == $bln) {
		 				$no_sj 	= sprintf("%'.03d",$no_sj_old[0]+1).'/SJ/PINJAM/'.$bln.'/'.$th;			
					}else{
						$no_sj = "001/SJ/PINJAM/$bln/$th";
					}
				}else{
						$no_sj = "001/SJ/PINJAM/$bln/$th";
				}
			}else{
				$no_sj = "001/SJ/PINJAM/$bln/$th";
			}
			$data['no_sj'] 					= $no_sj;			
			$data['tgl_sj'] 					= $tgl_sj;			
			$data['status_cetak'] 					= "cetak_sj";			
			$data['update_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$pdf = new FPDF('p','mm','A4');
			$pdf->AddPage();
	       // head
			$row = $this->db->query("SELECT * FROM tr_rfs_pinjaman WHERE id_rfs_pinjaman = '$id' ")->row();
		  $pdf->SetFont('ARIAL','B',13);
		  $pdf->Cell(190, 5, 'BERITA ACARA SERAH TERIMA', 0, 1, 'C');
		  $pdf->Cell(190, 5, 'UNIT MOTOR PINJAMAN-LOGISTIK MAIN DEALER', 0, 1, 'C');
		  $pdf->Cell(190, 8, '', 0, 1, 'C');
		  $pdf->SetFont('ARIAL','',10);
		  $pdf->MultiCell(190, 5, 'Sesuai dengan surat permohonan peminjaman unit sepeda motor yang diserahkan kepada Logistik Main Dealer pada tanggal '.$tgl.' yang dibuat oleh:', 0, 'L');
		  $pdf->Cell(10, 5, '', 0, 0, 'L');
		  $pdf->Cell(40, 5, 'Nama Peminjam', 0, 0, 'L');
		  $pdf->Cell(90, 5, ': ________________________________', 0, 1, 'L');
		  $pdf->Cell(10, 5, '', 0, 0, 'L');
		  $pdf->Cell(40, 5, 'Departemen / Divisi', 0, 0, 'L');
		  $pdf->Cell(90, 5, ': ________________________________', 0, 1, 'L');
		  $pdf->Cell(10, 5, '', 0, 0, 'L');
		  $pdf->Cell(40, 5, 'Untuk Keperluan', 0, 0, 'L');
		  $pdf->Cell(90, 5, ': ________________________________', 0, 1, 'L');
		  $pdf->Cell(10, 5, '', 0, 0, 'L');
		  $pdf->Cell(40, 5, 'Masa Peminjaman', 0, 0, 'L');
		  $pdf->Cell(90, 5, ': ________________________________', 0, 1, 'L');
		  $pdf->MultiCell(190, 5, 'Maka dari itu harap diterima dengan baik :', 0, 'L');
		  $pdf->Cell(90, 5, '', 0, 1, 'L');
		  $pdf->Cell(190, 5, 'DETAIL UNIT', 1, 1, 'C');
		  $pdf->Cell(10, 5, 'No.', 1, 0, 'C');
		  $pdf->Cell(30, 5, 'Kode Item', 1, 0, 'C');
		  $pdf->Cell(50, 5, 'Tipe', 1, 0, 'C');
		  $pdf->Cell(30, 5, 'Warna', 1, 0, 'C');
		  $pdf->Cell(35, 5, 'No Mesin', 1, 0, 'C');
		  $pdf->Cell(35, 5, 'No Rangka', 1, 1, 'C');
			$result = $this->db->query("SELECT tr_rfs_pinjaman_detail.no_mesin, tr_scan_barcode.id_item, tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,tr_rfs_pinjaman_detail.lokasi,ms_warna.warna, tr_rfs_pinjaman_detail.keterangan FROM tr_rfs_pinjaman_detail 
							left join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_rfs_pinjaman_detail.no_mesin
							left join ms_warna on ms_warna.id_warna = tr_scan_barcode.warna
				WHERE id_rfs_pinjaman = '$id' 
				")->result();
			$no=1;
		  foreach ($result as $res) {
		  	$pdf->Cell(10, 5, $no, 1, 0, 'C');
			  $pdf->Cell(30, 5, $res->id_item, 1, 0, 'C');
			  $pdf->Cell(50, 5, $res->tipe_motor, 1, 0, 'C');
			  $pdf->Cell(30, 5, $res->warna, 1, 0, 'C');
			  $pdf->Cell(35, 5, $res->no_mesin, 1, 0, 'C');
			  $pdf->Cell(35, 5, $res->no_rangka, 1, 1, 'C');
			  $no++;
		  }
		  $pdf->Cell(35, 5, '', 0, 1, 'C');
		  $ksu = $this->db->query("SELECT *,count(tr_rfs_pinjaman_detail_ksu.id_ksu) as qty FROM `tr_rfs_pinjaman_detail_ksu` 
		  				inner join ms_ksu on tr_rfs_pinjaman_detail_ksu.id_ksu = ms_ksu.id_ksu
		  	WHERE tr_rfs_pinjaman_detail_ksu.id_rfs_pinjaman='$id' group by tr_rfs_pinjaman_detail_ksu.id_ksu order by tr_rfs_pinjaman_detail_ksu.id_ksu ASC");
		  	  $pdf->Cell(10, 5, 'No.', 1, 0, 'C');
			  $pdf->Cell(30, 5, 'Kode Aksesoris', 1, 0, 'C');
			  $pdf->Cell(60, 5, 'Nama Aksesoris', 1, 0, 'C');
			  $pdf->Cell(27, 5, 'Qty', 1, 1, 'C');
			  $no=1;
			  foreach ($ksu->result() as $ksu) {
			  	$pdf->Cell(10, 5, $no, 1, 0, 'C');
			  $pdf->Cell(30, 5, $ksu->id_ksu, 1, 0, 'C');
			  $pdf->Cell(60, 5, $ksu->ksu, 1, 0, 'C');
			  $pdf->Cell(27, 5, $ksu->qty, 1, 1, 'C');
			  $no++;
			  }
		  $pdf->Cell(35, 5, '', 0, 1, 'C');
		  $pdf->Cell(190, 8, '', 0, 1, 'L');
		  $pdf->setX(15);
		  $pdf->Cell(90, 5, 'Diserahkan', 0, 0, 'C');$pdf->Cell(90, 5, 'Diperiksa dan Diterima Oleh', 0, 0, 'C');
		  $pdf->Cell(35, 20, '', 0, 1, 'C');
		  $pdf->setX(15);
		  $pdf->Cell(90, 5, '(   Kepala Logistik   )', 0, 0, 'C');$pdf->Cell(90, 5, '(   Peminjam    )', 0, 1, 'C');
		  $pdf->Cell(35, 20, '', 0, 1, 'C');
		  $pdf->SetFont('ARIAL','',8);
		  $pdf->Cell(190, 5, 'Catatan:', 0, 1, 'L');
          $pdf->Cell(190, 5, '* Bubuhkan Nama dan Tanda Tangan yang jelas', 0, 1, 'L');
          $pdf->Cell(190, 5, '* Unit harap diperiksa dan diterima dengan baik tanpa kondisi defect / cacat', 0, 1, 'L');
          $pdf->Cell(190, 5, '* Setelah masa peminjaman selesai maka BAST ini harus dikembalikan beserta dengan unit pinjaman', 0, 1, 'L');
          $pdf->Cell(190, 5, '* Kerusakan, kehilangan, kekurangan unit dan aksesories selama masa peminjaman menjadi tanggung jawab peminjam', 0, 1, 'L');

		  $pdf->Output();	 
		}
		else{
			$_SESSION['pesan'] 	= "Surat Jalan Sudah Dicetak";
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_pinjaman'>";
		}
	  	
	}
	public function tes_pdf()
	{		
		$waktu 		= gmdate("Y-m-d H:i:s", time()+60*60*7);		
		ob_clean();
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);
        $mpdf = new \Mpdf\Mpdf();	
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;  
		$data['set']		= "cetak";					
		$html = $this->load->view('h1/rfs_pinjaman_bast', $data, true);      
		$mpdf->WriteHTML($html);
		$mpdf->Output();  
	}
}