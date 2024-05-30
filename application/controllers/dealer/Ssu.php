<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ssu extends CI_Controller {

	var $tables =   "tr_ssu";	
	var $folder =   "h1";
	var $page		=		"ssu";
	var $pk     =   "id_ssu";
	var $title  =   "SSU";

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
		$this->load->library('zip');
		$this->load->library('csvimport');

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
		$data['set']		= "generate";
		$data['dt_ssu'] = $this->m_admin->getAll($this->tables);			
		$this->template($data);		
	}
	public function generate()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "generate";		
		$this->template($data);		
	}	
	public function t_detail(){
		$start_date 	= $this->input->post('start_date');
		$end_date 		= $this->input->post('end_date');
		$dq = "SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
				WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.create_ssu_by IS NULL OR tr_sales_order.create_ssu_by = 0 OR tr_sales_order.create_ssu_by IS NULL)
				AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)";
		$data['dt_detail'] = $this->db->query($dq);
		$this->load->view('h1/t_ssu',$data);
	}
	public function cari_id(){				
		$th 						= date("y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_ssu ORDER BY id_ssu DESC LIMIT 0,1");						       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_ssu,2,5); 
	    $kode = $th.sprintf("%05d", $id+1);
		}else{
			$kode = $th."00001";
		}
		return $kode;
	}
	function create(){					

		//Create SSU

		$tgl 		= gmdate("dmY", time()+60*60*7);				
		$id_ssu								= $this->cari_id();
		$nama_file						= "E20-".$tgl;
		$start_date						= $this->input->post('start_date');
		$end_date							= $this->input->post('end_date');					
		$tanggal							= gmdate("Y-m-d", time()+60*60*7);    
		$login_id							= $this->session->userdata('id_user');
		$sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
			WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.generate_ssu IS NULL OR tr_sales_order.create_ssu_by = 0)
			AND tr_sales_order.status_so = 'so_invoice'");
		foreach ($sql->result() as $isi) {						
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek1 = $this->m_admin->insert("tr_ssu_detail",$da);											

			$dat['create_ssu_by']	= $end_date;
			$dat['tgl_create_ssu']= $login_id;
			$cek3 = $this->m_admin->update("tr_sales_order",$dat,"no_mesin",$isi->no_mesin);											
		}

		$data['id_ssu']				= $id_ssu;
		$data['start_date']		= $start_date;
		$data['end_date']			= $end_date;
		$data['nama_file']		= $nama_file.".SSU";
		$cek2 = $this->m_admin->insert("tr_ssu",$data);											

		$dt['no'] 		= $nama_file;
		$dt['id_ssu'] = $id_ssu;				
		$this->load->view("h1/file_ssu",$dt);

		//Create CDB

		$tgl 		= gmdate("dmY", time()+60*60*7);				
		$tgl2		= gmdate("Ymd", time()+60*60*7);				
		$id_cdb_generate			= $this->cari_id();
		$nama_file						= "AHM-E20-".$tgl."-".$tgl2;
		$start_date						= $this->input->post('start_date');
		$end_date							= $this->input->post('end_date');					
		$tanggal							= gmdate("Y-m-d", time()+60*60*7);    
		$login_id							= $this->session->userdata('id_user');
		$sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
			WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND tr_sales_order.create_cdb_by IS NULL
			AND tr_sales_order.status_so = 'so_invoice'");
		foreach ($sql->result() as $isi) {						
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_cdb_generate']		= $id_cdb_generate;
			$cek1 = $this->m_admin->insert("tr_cdb_generate_detail",$da);											

			$dat['create_cdb_by']	= $end_date;
			$dat['tgl_create_cdb']= $login_id;
			//$cek3 = $this->m_admin->update("tr_sales_order",$dat,"no_mesin",$isi->no_mesin);											
		}

		$data['id_cdb_generate']		= $id_cdb_generate;
		$data['start_date']		= $start_date;
		$data['end_date']			= $end_date;
		$data['nama_file']		= $nama_file.".CDB";
		$cek2 = $this->m_admin->insert("tr_cdb_generate",$data);											

		$dt['no'] 		= $nama_file;
		$dt['id_cdb_generate'] = $id_cdb_generate;				
		$this->load->view("h1/file_cdb",$dt);
	}
	public function cari_id_cdb(){				
		$th 						= date("y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_cdb_generate ORDER BY id_cdb_generate DESC LIMIT 0,1");						       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_cdb_generate,2,5); 
	    $kode = $th.sprintf("%05d", $id+1);
		}else{
			$kode = $th."00001";
		}
		return $kode;
	}
	public function cari_id_ustk(){				
		$th 						= date("y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_ustk ORDER BY id_ustk DESC LIMIT 0,1");						       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_ustk,2,5); 
	    $kode = $th.sprintf("%05d", $id+1);
		}else{
			$kode = $th."00001";
		}
		return $kode;
	}
	public function create_fix(){		

				

			$tgl 		= gmdate("dmY", time()+60*60*7);					
			$tgl2		= gmdate("Ymd", time()+60*60*7);						
			$id_ssu								= $this->cari_id();
			$id_cdb_generate			= $this->cari_id_cdb();
			$id_ustk							= $this->cari_id_ustk();
			$nama_file_1					= "E20-".$tgl.".SSU";
			$nama_file_2					= "AHM-E20-".$tgl."-".$tgl2.".CDB";
			$nama_file_3					= "AHM-E20-".$tgl."-".$tgl2.".USTK";
			$start_date						= $this->input->post('start_date');
			$end_date							= $this->input->post('end_date');					
			$tanggal							= gmdate("Y-m-d", time()+60*60*7);    
			$login_id							= $this->session->userdata('id_user');
			$sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
				WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.create_ssu_by IS NULL OR tr_sales_order.create_ssu_by = 0 OR tr_sales_order.create_ssu_by IS NULL)
				AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)");
			foreach ($sql->result() as $isi) {						
				$da['no_mesin']	= $isi->no_mesin;
				$da['id_ssu']		= $id_ssu;
				$cek1 = $this->m_admin->insert("tr_ssu_detail",$da);											

				$da_cdb['no_mesin']	= $isi->no_mesin;
				$da_cdb['id_cdb_generate']		= $id_cdb_generate;
				$cek1 = $this->m_admin->insert("tr_cdb_generate_detail",$da_cdb);															

				$da_ustk['no_mesin']		= $isi->no_mesin;
				$da_ustk['id_ustk']			= $id_ustk;
				$cek1 = $this->m_admin->insert("tr_ustk_detail",$da_ustk);											

				$dat['create_cdb_by']	= $end_date;
				$dat['tgl_create_cdb']= $login_id;
				
				$dat['create_ssu_by']	= $end_date;
				$dat['tgl_create_ssu']= $login_id;

				$dat['create_ustk_by']	= $end_date;
				$dat['tgl_create_ustk'] = $login_id;
				//$cek3 = $this->m_admin->update("tr_sales_order",$dat,"no_mesin",$isi->no_mesin);
			}

			$data['id_ssu']				= $id_ssu;
			$data['start_date']		= $start_date;
			$data['end_date']			= $end_date;
			$data['nama_file']		= $nama_file_1;
			$cek2 = $this->m_admin->insert("tr_ssu",$data);	
			
					
			//CREATE SSU FILE---------------------------------------------------------------------------------------------------------------------------
			$sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
					INNER JOIN tr_sales_order ON tr_ssu_detail.no_mesin = tr_sales_order.no_mesin
					INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE tr_ssu.id_ssu = '$id_ssu'");
			$isi_data_fix_1 = "";
			foreach ($sql->result() as $isi) {	
				$scan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);	
				if($scan->num_rows() > 0){
					$r = $scan->row();
					$tipe = $r->tipe;
					$tgl_penerimaan = $r->tgl_penerimaan;
				}else{	
					$tipe = "";
					$tgl_penerimaan = "";
				}
				$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->id_dealer);	
				if($dealer->num_rows() > 0){
					$d = $dealer->row();
					$kode_dealer_md = $d->kode_dealer_md;
				}else{
					$kode_dealer_md = "";
				}

				$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
								INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
								WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
				if($cek_md->num_rows() > 0){
					$y = $cek_md->row();
					$tgl_md = $y->tgl_faktur;
				}else{
					$tgl_md = "";
				}
				

				$isi_data_1 = "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tgl_penerimaan.";".$isi->tgl_cetak_invoice.";".$kode_dealer_md.";;".$tgl_md.";".$isi->tgl_create_ssu.";".$isi->tgl_cetak_invoice.";".$isi->jenis_beli.";".$isi->id_finance_company.";;".$isi->dp_stor.";".$isi->tenor.";".$isi->angsuran.";;".$isi->id_customer;
				$isi_data_fix_1	.=	$isi_data_1."\r\n";			
			}



			//CREATE FILE CDB---------------------------------------------------------------------------------------------------------------------------
			$data_cdb['id_cdb_generate']				= $id_cdb_generate;
			$data_cdb['start_date']		= $start_date;
			$data_cdb['end_date']			= $end_date;
			$data_cdb['nama_file']		= $nama_file_2;
			$cek2 = $this->m_admin->insert("tr_cdb_generate",$data_cdb);											
					

			$sql = $this->db->query("SELECT * FROM tr_cdb_generate INNER JOIN tr_cdb_generate_detail ON tr_cdb_generate.id_cdb_generate = tr_cdb_generate_detail.id_cdb_generate
					INNER JOIN tr_sales_order ON tr_cdb_generate_detail.no_mesin = tr_sales_order.no_mesin					
					WHERE tr_cdb_generate.id_cdb_generate = '$id_cdb_generate'");
			$isi_data_fix_2 = "";
			foreach ($sql->result() as $isi) {	
				$nosin5 = substr($isi->no_mesin, 0,5);    
				$nosin7 = substr($isi->no_mesin, 5,7);
				$tgl = $this->db->query("SELECT * FROM tr_permohonan_stnk WHERE no_mesin = '$isi->no_mesin'");
				if($tgl->num_rows() > 0){
					$r = $tgl->row();
					$tgl_mohon = $r->tgl_permohonan;
				}else{
					$tgl_mohon = "";
				}    
				$asal = $this->db->query("SELECT * FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan = ms_kelurahan.id_kelurahan
					LEFT JOIN ms_kecamatan ON tr_spk.id_kecamatan = ms_kecamatan.id_kecamatan
					LEFT JOIN ms_kabupaten ON tr_spk.id_kabupaten = ms_kabupaten.id_kabupaten
					LEFT JOIN ms_provinsi ON tr_spk.id_provinsi = ms_provinsi.id_provinsi
					INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
					WHERE tr_sales_order.no_mesin = '$isi->no_mesin'");
				if($asal->num_rows() > 0){
					$a = $asal->row();
					$kelurahan = $a->kelurahan;
					$kecamatan = $a->kecamatan;
					$kabupaten = $a->kabupaten;
					$provinsi  = $a->provinsi;
					$kodepos 	 = $a->kodepos;
					$jenis_beli 	 = $a->jenis_beli;		
					$no_ktp 	 = $a->no_ktp;		
					$id_customer = $a->id_customer;
					$alamat = $a->alamat;
					$tgl_lahir = $a->tgl_lahir;

					$bulan = substr($tgl_lahir, 5,2);
			    $tahun = substr($tgl_lahir, 0,4);
			    $tgl = substr($tgl_lahir, 8,2);
			    $tanggal = $tgl.$bulan.$tahun;
			    $pk = $a->pekerjaan;
					$pengeluaran = $a->pengeluaran_bulan;
					$no_hp = $a->no_hp;
					$no_telp = $a->no_telp;
					$email = $a->email;
					$status_rumah = $a->status_rumah;
					$status_hp = $a->status_hp;
				}else{
					$kelurahan="";$kecamatan="";$kabupaten="";$provinsi="";$kodepos="";$jenis_beli="";$no_ktp="";$id_customer="";$tgl_lahir="";
					$alamat="";$pk="";$pengeluaran="";$no_hp="";$no_telp="";$status_hp="";$status_rumah="";$email="";
				}
				$jk = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer = '$id_customer'");
				if($jk->num_rows() > 0){
					$j = $jk->row();
					$jn = $j->jenis_kelamin; 		
					$ag = $j->agama;
					$pendidikan = $j->pendidikan;
					$penanggung = $j->penanggung_jawab;
					$sedia_hub = $j->sedia_hub;
					$merk_sebelumnya = $j->merk_sebelumnya;
					$jenis_sebelumnya = $j->jenis_sebelumnya;
					$digunakan = $j->digunakan;
					$pemakai_motor = $j->pemakai_motor;
					$id_karyawan_dealer = $j->id_karyawan_dealer;
				}else{
					$jn="";$ag="";$penanggung="";$pendidikan="";$sedia_hub="";$merk_sebelumnya="";$jenis_sebelumnya="";$digunakan="";$pemakai_motor="";
					$id_karyawan_dealer="";
				}
				$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->id_dealer);	
				if($dealer->num_rows() > 0){
					$d = $dealer->row();
					$kode_dealer_md = $d->kode_dealer_md;
				}else{
					$kode_dealer_md = "";
				}

				$sales = $this->m_admin->getByID("ms_karyawan_dealer","id_karyawan_dealer",$id_karyawan_dealer);	
				if($sales->num_rows() > 0){
					$s = $sales->row();
					$kode_sales = $s->id_flp_md;
				}else{
					$kode_sales = "";
				}


				$isi_data_2 = $nosin5.";".$nosin7.";".$no_ktp.";".$id_customer.";".$jn.";".$tanggal.";".$alamat.";".$kelurahan.";".$kecamatan.";".$kabupaten.";".$kodepos.";".$provinsi.";".$ag.";".$pk.";".$pengeluaran.";".$pendidikan.";".$penanggung.";".$no_hp.";".$no_telp.";".$sedia_hub.";".$merk_sebelumnya.";".$jenis_sebelumnya.";".$digunakan.";".$pemakai_motor.";".$kode_sales.";".$email.";".$status_rumah.";".$status_hp.";";
				$isi_data_fix_2	.=	$isi_data_2."\r\n";			
			}


			//CREATE FILE USTK---------------------------------------------------------------------------------------------------------------------------
			$data_ustk['id_ustk']				= $id_ustk;
			$data_ustk['start_date']		= $start_date;
			$data_ustk['end_date']			= $end_date;
			$data_ustk['nama_file']			= $nama_file_3;
			$cek2 = $this->m_admin->insert("tr_ustk",$data_ustk);

			$sql = $this->db->query("SELECT * FROM tr_ustk INNER JOIN tr_ustk_detail ON tr_ustk.id_ustk = tr_ustk_detail.id_ustk
					INNER JOIN tr_sales_order ON tr_ustk_detail.no_mesin = tr_sales_order.no_mesin			
					INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk		
					WHERE tr_ustk.id_ustk = '$id_ustk'");
			$isi_data_fix_3 = "";
			foreach ($sql->result() as $isi) {	
				$nosin5 = substr($isi->no_mesin, 0,5);    
				$nosin7 = substr($isi->no_mesin, 5,7);
				$tgl = $this->db->query("SELECT * FROM tr_permohonan_stnk WHERE no_mesin = '$isi->no_mesin'");
				if($tgl->num_rows() > 0){
					$r = $tgl->row();
					$tgl_mohon = $r->tgl_permohonan;
				}else{
					$tgl_mohon = "";
				}    
				$asal = $this->db->query("SELECT * FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan = ms_kelurahan.id_kelurahan
					LEFT JOIN ms_kecamatan ON tr_spk.id_kecamatan = ms_kecamatan.id_kecamatan
					LEFT JOIN ms_kabupaten ON tr_spk.id_kabupaten = ms_kabupaten.id_kabupaten
					LEFT JOIN ms_provinsi ON tr_spk.id_provinsi = ms_provinsi.id_provinsi
					INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
					WHERE tr_sales_order.no_mesin = '$isi->no_mesin'");
				if($asal->num_rows() > 0){
					$a = $asal->row();
					$kelurahan = $a->kelurahan;
					$kecamatan = $a->kecamatan;
					$kabupaten = $a->kabupaten;
					$provinsi  = $a->provinsi;
					$kodepos 	 = $a->kodepos;
					$jenis_beli 	 = $a->jenis_beli;		
					$no_ktp 	 	= $a->no_ktp;		
				}else{
					$kelurahan = "";
					$kecamatan = "";
					$kabupaten = "";
					$provinsi  = "";
					$kodepos 	 = "";
					$jenis_beli = "";		
					$no_ktp = "";		
				}
				$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->id_dealer);	
				if($dealer->num_rows() > 0){
					$d = $dealer->row();
					$kode_dealer_md = $d->kode_dealer_md;
				}else{
					$kode_dealer_md = "";
				}

				$fm = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$isi->no_mesin);	
				if($fm->num_rows() > 0){
					$f = $fm->row();
					$no_faktur = $f->no_faktur;
					$tgl_up = $f->tgl_upload;
				}else{
					$no_faktur = "";$tgl_up="";
				}
				$isi_data_3 = $no_faktur.";".$isi->no_rangka.";".$nosin5.";".$nosin7.";".$tgl_up.";".$tgl_mohon.";".$isi->nama_konsumen.";".$isi->alamat.";".$kelurahan.";".$kecamatan.";".$kabupaten.";".$kodepos.";".$provinsi.";".$jenis_beli.";".$kode_dealer_md.";".$no_ktp;
				$isi_data_fix_3	.= $isi_data_3."\r\n";			
			}

			$name = $nama_file_1;
			$data = $isi_data_fix_1;

			$name2	= $nama_file_2;
			$data2 	= $isi_data_fix_2;

			$name3	= $nama_file_3;
			$data3 	= $isi_data_fix_3;

			$this->zip->add_data($name, $data);
			$this->zip->add_data($name2, $data2);			
			$this->zip->add_data($name3, $data3);			
			$isi_data_fix_1 = "";
			$isi_data_fix_2 = "";
			$isi_data_fix_3 = "";
		
		// Download the file to your desktop. Name it "my_backup.zip"
		$this->zip->download('FILE-SSU-CDB-USTK.zip');
	}
	public function tes_exp(){
		$this->load->view("h1/file_tes1");		
	}
	public function download()
	{					
		$id_ssu				= $this->input->get('id');
		$tr = $this->m_admin->getByID("tr_ssu","id_ssu",$id_ssu)->row();
		$dt['no'] 		= $tr->nama_file;
		$dt['id_ssu'] = $id_ssu;				
		$this->load->view("h1/file_ssu",$dt);
		
	}
}