<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bantuan_bbn extends CI_Controller {

    var $tables =   "tr_bantuan_bbn";	
		var $folder =   "h1";
		var $page		=		"bantuan_bbn";
    var $pk     =   "id_bantuan_bbn";
    var $title  =   "Bantuan BBN";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_kelurahan');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

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
		$data['dt_bantuan'] = $this->db->query("SELECT tr_bantuan_bbn.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_bantuan_bbn INNER JOIN ms_tipe_kendaraan ON tr_bantuan_bbn.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
							INNER JOIN ms_warna ON tr_bantuan_bbn.id_warna=ms_warna.id_warna ORDER BY tr_bantuan_bbn.no_faktur ASC");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";			
		$data['dt_pekerjaan'] = $this->m_admin->getSort("ms_pekerjaan","pekerjaan","ASC");											
		$this->template($data);			
	}		
	public function ajax_list()
	{				
		$list = $this->m_kelurahan->get_datatables();		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan","id_kecamatan",$isi->id_kecamatan);
			if($cek->num_rows() > 0){
				$t = $cek->row();
				$kecamatan = $t->kecamatan;
			}else{
				$kecamatan = "";
			}
			$no++;
			$row = array();
			$row[] = $no;			
			$row[] = $isi->kelurahan;			
			$row[] = $kecamatan;			
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_kelurahan')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_kelurahan->count_all(),
						"recordsFiltered" => $this->m_kelurahan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function take_kec()
	{		
		$id_kelurahan	= $this->input->post('id_kelurahan');	
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
		$kecamatan 		= $dt_kec->kecamatan;
		$id_kabupaten = $dt_kec->id_kabupaten;
		$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
		$kabupaten  	= $dt_kab->kabupaten;
		$id_provinsi  = $dt_kab->id_provinsi;
		$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
		$provinsi  		= $dt_pro->provinsi;

		
		echo $id_kecamatan."|".$kecamatan."|".$id_kabupaten."|".$kabupaten."|".$id_provinsi."|".$provinsi."|".$kelurahan;
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel		= $this->tables;		
		$data['no_faktur'] 					= $this->input->post('no_faktur');		
		$data['tgl_faktur'] 				= $this->input->post('tgl_faktur');		
		$data['pemohon'] 						= $this->input->post('pemohon');		
		$data['no_mesin'] 					= $this->input->post('no_mesin');		
		$data['no_rangka'] 					= $this->input->post('no_rangka');		
		$data['id_tipe_kendaraan'] 	= $this->input->post('id_tipe_kendaraan');		
		$data['id_warna'] 					= $this->input->post('id_warna');		
		$data['tahun_produksi'] 		= $this->input->post('tahun_produksi');
		$data['nama_konsumen'] 			= $this->input->post('nama_konsumen');
		$data['no_ktp'] 						= $this->input->post('no_ktp');
		$data['alamat'] 						= $this->input->post('alamat');
		$data['no_telp'] 						= $this->input->post('no_telp');
		$data['nama_ibu'] 					= $this->input->post('nama_ibu');
		$data['pemenang'] 					= $this->input->post('pemenang');
		$data['pemenang_dari'] 			= $this->input->post('pemenang_dari');
		$data['tagih_ke'] 					= $this->input->post('tagih_ke');
		$data['biaya_adm'] 					= $this->input->post('biaya_adm');
		$data['biaya_bbn'] 					= $this->input->post('biaya_bbn');
		$data['total'] 							= $this->input->post('total');
		$data['tgl_samsat'] 				= $this->input->post('tgl_samsat');
		$data['id_kelurahan'] 			= $this->input->post('id_kelurahan');
		$da['kelurahan'] 					= $this->input->post("kelurahan");				
		$da['kecamatan'] 					= $this->input->post("kecamatan");				
		$da['kabupaten'] 					= $this->input->post("kabupaten");				
		$da['provinsi'] 					= $this->input->post("provinsi");				
		$data['tgl_ibu'] 						= $this->input->post('tgl_ibu');
		$data['pekerjaan'] 					= $this->input->post('pekerjaan');
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn/add'>";		
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_bantuan'] = $this->m_admin->kondisi($tabel,$d);		
		$data['isi']    = $this->page;				
		$data['dt_pekerjaan'] = $this->m_admin->getSort("ms_pekerjaan","pekerjaan","ASC");											
		$data['title']	= $this->title;		
		$data['set']		= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['no_faktur'] 					= $this->input->post('no_faktur');		
			$data['tgl_faktur'] 				= $this->input->post('tgl_faktur');		
			$data['pemohon'] 						= $this->input->post('pemohon');		
			$data['no_mesin'] 					= $this->input->post('no_mesin');		
			$data['no_rangka'] 					= $this->input->post('no_rangka');		
			$data['id_tipe_kendaraan'] 	= $this->input->post('id_tipe_kendaraan');		
			$data['id_warna'] 					= $this->input->post('id_warna');		
			$data['tahun_produksi'] 		= $this->input->post('tahun_produksi');
			$data['nama_konsumen'] 			= $this->input->post('nama_konsumen');
			$data['alamat'] 						= $this->input->post('alamat');
			$data['no_telp'] 						= $this->input->post('no_telp');
			$data['nama_ibu'] 					= $this->input->post('nama_ibu');
			$data['pemenang'] 					= $this->input->post('pemenang');
			$data['pemenang_dari'] 			= $this->input->post('pemenang_dari');
			$data['tagih_ke'] 					= $this->input->post('tagih_ke');
			$data['biaya_adm'] 					= $this->input->post('biaya_adm');
			$data['biaya_bbn'] 					= $this->input->post('biaya_bbn');
			$data['total'] 							= $this->input->post('total');
			$data['tgl_samsat'] 				= $this->input->post('tgl_samsat');		
			$data['id_kelurahan'] 			= $this->input->post('id_kelurahan');
			$da['kelurahan'] 					= $this->input->post("kelurahan");				
		$da['kecamatan'] 					= $this->input->post("kecamatan");				
		$da['kabupaten'] 					= $this->input->post("kabupaten");				
		$da['provinsi'] 					= $this->input->post("provinsi");				
			$data['tgl_ibu'] 						= $this->input->post('tgl_ibu');
			$data['pekerjaan'] 					= $this->input->post('pekerjaan');
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function approve(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");							
		$data['status'] 					= "approved";
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;
		$this->m_admin->update($tabel,$data,$pk,$id);


		$sql = $this->m_admin->getByID("tr_bantuan_bbn","id_bantuan_bbn",$id)->row();
		$no_faktur 	= $sql->no_faktur;
		$dr['no_faktur']  = $sql->no_faktur;
		$dr['nama_konsumen'] 	= $sql->nama_konsumen;
		$dr['pemohon'] 		= $sql->pemohon;
		$dr['tagih_ke'] 	= $sql->tagih_ke;		
		$dr['tipe'] 			= $sql->id_tipe_kendaraan;		
		$dr['warna'] 			= $sql->id_warna;		
		$dr['total'] 			= $sql->total;		
		$dr['status_mon']	= "input";		
		$cek = $this->m_admin->getByID("tr_monout_bantuan_bbn","no_faktur",$no_faktur);
		if($cek->num_rows() > 0){
			$f = $cek->row();
			$dr['updated_at'] 					= $waktu;
			$dr['updated_by'] 					= $login_id;
			$this->m_admin->update("tr_monout_bantuan_bbn",$dr,"no_faktur",$f->no_faktur);
		}else{
			$dr['created_at'] 					= $waktu;
			$dr['created_by'] 					= $login_id;
			$this->m_admin->insert("tr_monout_bantuan_bbn",$dr);
		}

		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";		
	}
	public function reject(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");							
		$data['status'] 					= "rejected";
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";		
	}	
	public function cari_bbn(){		
		$id_tipe_kendaraan 	= $this->input->post('id_tipe_kendaraan');		
		$cek 								= $this->db->query("SELECT * FROM ms_bbn_dealer WHERE id_tipe_kendaraan = '$id_tipe_kendaraan'");						
		if($cek->num_rows()>0){
			$io = $cek->row();
			$biaya = $io->biaya_bbn;
		}else{
			$biaya = 0;
		}		
		echo $biaya;
	}

	public function cari_id($tipe){		
		$th 						= date("y");		
		$bulan 					= date("m");		
		$tipe_k 				= strtolower($tipe);
		$pr_num 				= $this->db->query("SELECT * FROM tr_bantuan_bbn ORDER BY no_tanda_terima_$tipe_k DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_tanda_terima)-8;
			$id 	= substr($row->no_tanda_terima,$pan,3)+1;	
			
			$kode1 	= sprintf("%'.03d",$id);		
			$kode = "BANTUAN/".$th."/".$bulan."/".$kode1.$tipe;
		}else{
			$kode = "BANTUAN/".$th."/".$bulan."/001".$tipe;			
		}
		return $kode;
	}
	public function cetak_st_bpkb()
	{
    $id = $this->input->get('id');
    $sql = $this->m_admin->getByID("tr_bantuan_bbn","id_bantuan_bbn",$id);
    $tgl = date("Y-m-d");
    if ($sql->num_rows()>0) {
      $mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] 	= 'st_bpkb';
    	$data['row'] 		= $sql->row();    	
    	$cek = $this->db->query("SELECT * FROM tr_bantuan_bbn WHERE id_bantuan_bbn = '$id' AND no_tanda_terima_bpkb <> ''");
    	if($cek->num_rows() > 0){
	    	$ambil = $cek->row();
    		$data['tgl_terima'] = $ambil->tgl_tanda_terima_bpkb;
	    	$data['no_tanda_terima'] = $ambil->no_tanda_terima_bpkb;
    	}else{	
    		$data['tgl_terima'] = $tgl;
	    	$no_tanda_terima = $this->cari_id("BPKB");
	    	$data['no_tanda_terima'] = $no_tanda_terima;
	    	$this->db->query("UPDATE tr_bantuan_bbn SET tgl_tanda_terima_bpkb = '$tgl',no_tanda_terima_bpkb='$no_tanda_terima' WHERE id_bantuan_bbn = '$id'");
    	}
    	$html = $this->load->view('h1/bantuan_bbn_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_st_bpkb.pdf';
      $mpdf->Output("$output", 'I');
    }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";		
    }       
	}
	public function cetak_st_stnk()
	{
    $id = $this->input->get('id');
    $sql = $this->m_admin->getByID("tr_bantuan_bbn","id_bantuan_bbn",$id);
    $tgl = date("Y-m-d");
    if ($sql->num_rows()>0) {
      $mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] 	= 'st_stnk';
    	$data['row'] 		= $sql->row();    	
    	$cek = $this->db->query("SELECT * FROM tr_bantuan_bbn WHERE id_bantuan_bbn = '$id' AND no_tanda_terima_stnk <> ''");
    	if($cek->num_rows() > 0){
	    	$ambil = $cek->row();
    		$data['tgl_terima'] = $ambil->tgl_tanda_terima_stnk;
	    	$data['no_tanda_terima'] = $ambil->no_tanda_terima_stnk;
    	}else{	
    		$data['tgl_terima'] = $tgl;
	    	$no_tanda_terima = $this->cari_id("STNK");
	    	$data['no_tanda_terima'] = $no_tanda_terima;
	    	$this->db->query("UPDATE tr_bantuan_bbn SET tgl_tanda_terima_stnk = '$tgl',no_tanda_terima_stnk = '$no_tanda_terima' WHERE id_bantuan_bbn = '$id'");
    	}
    	$html = $this->load->view('h1/bantuan_bbn_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_st_stnk.pdf';
      $mpdf->Output("$output", 'I');
    }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";		
    }       
	}	
	public function cetak_st_plat()
	{
    $id = $this->input->get('id');
    $sql = $this->m_admin->getByID("tr_bantuan_bbn","id_bantuan_bbn",$id);
    $tgl = date("Y-m-d");
    if ($sql->num_rows()>0) {
      $mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] 	= 'st_plat';
    	$data['row'] 		= $sql->row();    	
    	$cek = $this->db->query("SELECT * FROM tr_bantuan_bbn WHERE id_bantuan_bbn = '$id' AND no_tanda_terima_plat <> ''");
    	if($cek->num_rows() > 0){
	    	$ambil = $cek->row();
    		$data['tgl_terima'] = $ambil->tgl_tanda_terima_plat;
	    	$data['no_tanda_terima'] = $ambil->no_tanda_terima_plat;
    	}else{	
    		$data['tgl_terima'] = $tgl;
	    	$no_tanda_terima = $this->cari_id("PLAT");
	    	$data['no_tanda_terima'] = $no_tanda_terima;
	    	$this->db->query("UPDATE tr_bantuan_bbn SET tgl_tanda_terima_plat = '$tgl',no_tanda_terima_plat = '$no_tanda_terima' WHERE id_bantuan_bbn = '$id'");
    	}
    	$html = $this->load->view('h1/bantuan_bbn_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_st_plat.pdf';
      $mpdf->Output("$output", 'I');
    }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";		
    }       
	}	
	public function cetak_syarat_bpkb(){
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');				
  	
		$data['cetak'] 				= "ya";		
		$data['tgl_cetak']		= $tgl;						
		//$this->m_admin->update("tr_pengajuan_bbn_detail",$data,"no_mesin",$id);	

		$r = $this->m_admin->getByID("tr_bantuan_bbn","id_bantuan_bbn",$id)->row();

	$pdf = new FPDF('p','mm',array(209.8,296));
	  $pdf->AddPage();
       // head	  
	//  $pdf->Image(base_url().'/assets/panel/images/Scan_BPKB.jpg', 0, 0, 209.6);
	  $pdf->SetFont('COURIER','B',11); 
	   $pdf->SetXY(64,66);
	  $pdf->Cell(100, 4.5, $r->nama_konsumen, 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->MultiCell(90, 5, $r->alamat, 0, 1);
	  $pdf->SetXY(64,84.3);
	  $pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 5, $r->no_telp, 0, 1, 'L');
	  $pdf->SetXY(64,99);
	  $pdf->Cell(100, 5, 'Honda', 0, 1, 'L');
	  $pdf->SetX(64);
	  $cek_des=$this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$r->id_tipe_kendaraan'");
	  $utc = $this->db->query("SELECT * FROM tr_utc WHERE id_tipe_kendaraan='$r->id_tipe_kendaraan'");
	  if($utc->num_rows() > 0) $utc_hasil = $utc->row()->cc_motor;
	  	else $utc_hasil = "";
	  $deskripsi_samsat = $cek_des->num_rows()>0?$cek_des->row()->deskripsi_samsat:'';
	  $pdf->Cell(100, 5, $deskripsi_samsat, 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 5, 'Sepeda Motor', 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 5, 'Solo', 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 5, $r->tahun_produksi, 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 5, $utc_hasil, 0, 1, 'L');
	  $pdf->Cell(100, 4.2, '', 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 5, 'MH1'.$r->no_rangka, 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 5, $r->no_mesin, 0, 1, 'L');
	  
	  $warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$r->id_warna' ");
	  if ($warna->num_rows()>0) {
	  	$warna = $warna->row()->warna_samsat;
	  }else{
	  	$warna='';
	  }
	  $pdf->SetXY(157,99.5);
	  $pdf->Cell(100, 5, $warna, 0, 1, 'L');
	  $pdf->SetXY(157,105);
	  $pdf->Cell(100, 5, 'Bensin', 0, 1, 'L');
	  $pdf->SetXY(64,167);
	  $pdf->Cell(100, 4.5, $r->no_faktur, 0, 1, 'L');
	  $pdf->SetX(64);
	  $pdf->Cell(100, 4, 'Astra Honda Motor', 0, 1, 'L');
	  $pdf->Output();
	}	

	public function cetak_syarat_stnk(){
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');

		$dt_bantuan = $this->db->query("SELECT tr_bantuan_bbn.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_warna.warna_samsat, ms_tipe_kendaraan.deskripsi_samsat   FROM tr_bantuan_bbn 
			INNER JOIN ms_tipe_kendaraan ON tr_bantuan_bbn.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
							INNER JOIN ms_warna ON tr_bantuan_bbn.id_warna=ms_warna.id_warna 
				
							WHERE tr_bantuan_bbn.id_bantuan_bbn='$id' ");
		if ($dt_bantuan->num_rows()>0) {
			$r = $dt_bantuan->row();
			$utc = $this->db->query("SELECT * FROM tr_utc WHERE id_tipe_kendaraan='$r->id_tipe_kendaraan'")->row()->cc_motor;
			$pdf = new FPDF('p','mm',array(209.6,296));
			  $pdf->AddPage();
		       // head	  
			  // $pdf->Image(base_url().'/assets/panel/images/Scan_STNK.jpg', 0, 0, 209.6);
			  $pdf->SetFont('COURIER','',12);
			  $pdf->SetXY(12,55);
			  //$pdf->Cell(190, 5, $r->nama_dealer, 0, 1, 'C');
			  $pdf->SetFont('COURIER','',11); 

			  $pdf->SetXY(74,54);
			  $pdf->Cell(100, 6, $r->nama_konsumen, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->MultiCell(90, 5, $r->alamat, 0, 1);
			  $pdf->SetXY(74,70);
			  $pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, $r->no_telp.'/'.$r->no_telp, 0, 1, 'L');
			  $pdf->SetXY(74,86);
			  $pdf->Cell(100, 4, 'Honda', 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, $r->deskripsi_samsat, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 6, 'Sepeda Motor', 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, 'Solo', 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, $r->tahun_produksi, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, $utc, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, 'MH1'.$r->no_rangka, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, $r->no_mesin, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, $r->warna_samsat, 0, 1, 'L');
			  $pdf->SetX(74);
			  $pdf->Cell(100, 5, 'Bensin', 0, 1, 'L');
			  $pdf->Output();
		}else{
			$_SESSION['pesan'] 	= "Data not found";
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";		
		}
	}

	public function cetak_faktur(){
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');				
  	
		$r = $this->m_admin->getByID("tr_bantuan_bbn","id_bantuan_bbn",$id)->row();
		
		$pdf = new FPDF('p','mm',array(202.5,279.4));
    $pdf->AddPage();
       // head	  
	 // $pdf->Image(base_url().'/assets/panel/images/Scan_Faktur.jpg', 0, 0, 202.5);
	  $pdf->SetFont('COURIER','',12);
	  $pdf->SetXY(12,55);
	  $pdf->Cell(190, 5, $r->pemohon, 0, 1, 'C');
	  $pdf->SetFont('COURIER','',11); 
	  $pdf->SetXY(24,61);
	  $pdf->Cell(80, 5, $r->no_faktur, 0, 1, 'C');
	  $pdf->SetXY(136,61);
	  $pdf->Cell(80, 5, date('d-m-Y', strtotime($r->tgl_samsat)), 0, 1, 'C');

	  $pdf->SetXY(85,78);
	  $pdf->Cell(100, 5, $r->nama_konsumen, 0, 1, 'L');
	  $pdf->SetXY(85,86);
	   //$pdf->MultiCell(110, 5, $r->alamat, 0, 1);
	  $pdf->Cell(170, 5, $r->alamat, 0, 1, 'L');
	  $pdf->SetX(85);
	  $getKel = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan='$r->id_kelurahan'");
	  if ($getKel->num_rows()>0) {
	  	$getKel=$getKel->row();
	  	$kelurahan=$getKel->kelurahan;
	  	$id_kec=$getKel->id_kecamatan;
	  }else{
	  	$kelurahan='';
	  }

	  $getKec = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan='$id_kec'");
	  if ($getKec->num_rows()>0) {
	  	$getKec=$getKec->row();
	  	$kecamatan=$getKec->kecamatan;
	  	$id_kab=$getKec->id_kabupaten;
	  }else{
	  	$kecamatan='';
	  }

	  $getKab = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten='$id_kab'");
	  if ($getKab->num_rows()>0) {
	  	$getKab=$getKab->row();
	  	$kabupaten=$getKab->kabupaten;
	  }else{
	  	$kabupaten='';
	  }

	  $pdf->Cell(170, 5, 'Kel. '.$kelurahan.', Kec.'.$kecamatan, 0, 1, 'L');
	  $pdf->SetX(85);
	  $pdf->Cell(170, 5, $kabupaten, 0, 1, 'L');

	  //$pdf->Multicell(100, 4, $r->alamat.'wfrewg regreg grreg gregre greger grehjrgre ggreger ergreg regergre gregew ', 0, 1, 'L');
	  $pdf->SetXY(85,103);
	  $pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
	  $pdf->Output(); 
	}

	public function cetak_tagihan()
	{
        $id = $this->input->get('id');
        $sql = $this->m_admin->getByID("tr_bantuan_bbn","id_bantuan_bbn",$id);
        if ($sql->num_rows()>0) {
        	$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
	        $mpdf->charset_in='UTF-8';
	        $mpdf->autoLangToFont = true;
        	$data['cetak'] = 'syarat_tagihan';
        	$data['row'] = $sql->row();
        	$data['jml'] = $sql->num_rows();
        	$html = $this->load->view('h1/bantuan_bbn_cetak', $data, true);
	        // render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_st_.pdf';
	        $mpdf->Output("$output", 'I');
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/bantuan_bbn'>";		
        }
        
	}
}