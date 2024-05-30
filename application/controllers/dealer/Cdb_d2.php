<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Cdb_d extends CI_Controller {



  var $tables =   "tr_cdb";	

	var $folder =   "dealer";

	var $page		=		"cdb_d";

  var $pk     =   "id_cdb";

  var $title  =   "CDB (Customer Database) ";



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

		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_cdb'] = $this->db->query("SELECT * FROM tr_cdb INNER JOIn tr_spk ON tr_cdb.no_spk = tr_spk.no_spk WHERE tr_cdb.id_dealer = '$id_dealer' ORDER BY tr_cdb.created_at ASC");		

		$data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE no_spk NOT IN (SELECT no_spk FROM tr_cdb WHERE no_spk is not null) AND tr_spk.status_spk<>'closed'  ORDER BY no_spk ASC");						

		$this->template($data);	

	}

	public function gc()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title." Group Customer";															

		$data['set']		= "view_gc";

		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_cdb'] = $this->db->query("SELECT * FROM tr_cdb_gc INNER JOIn tr_spk_gc ON tr_cdb_gc.no_spk_gc = tr_spk_gc.no_spk_gc WHERE tr_cdb_gc.id_dealer = '$id_dealer' ORDER BY tr_cdb_gc.created_at ASC");		

		$data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE no_spk NOT IN (SELECT no_spk FROM tr_cdb WHERE no_spk is not null) AND tr_spk.status_spk<>'closed'  ORDER BY no_spk ASC");						

		$this->template($data);	

	}



	public function add()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title;		

		$data['set']		= "insert";			

		$id_dealer = $this->m_admin->cari_dealer();



		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,ms_kelurahan.id_kelurahan FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan=ms_kelurahan.id_kelurahan WHERE id_dealer='$id_dealer' AND no_spk NOT IN (SELECT no_spk FROM tr_cdb WHERE no_spk is not null)  AND tr_spk.status_spk<>'closed'

																							ORDER BY ms_kelurahan.kelurahan ASC");				

		$data['dt_agama'] 		= $this->m_admin->getSortCond("ms_agama","id_agama","ASC");												

		$data['dt_pendidikan'] = $this->m_admin->getActData("ms_pendidikan","id_pendidikan","ASC");								

		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");								

		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");								

		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");															

		$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi","hobi","ASC");									

		$this->template($data);										

	}

	public function add_gc()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title." Group Customer";		

		$data['set']		= "insert_gc";			

		$id_dealer = $this->m_admin->cari_dealer();



		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,ms_kelurahan.id_kelurahan FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan=ms_kelurahan.id_kelurahan WHERE id_dealer='$id_dealer' AND no_spk NOT IN (SELECT no_spk FROM tr_cdb WHERE no_spk is not null)  AND tr_spk.status_spk<>'closed'

																							ORDER BY ms_kelurahan.kelurahan ASC");				

		$data['dt_agama'] 		= $this->m_admin->getSortCond("ms_agama","id_agama","ASC");												

		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan","id_pendidikan","ASC");								

		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");								

		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");								

		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");															

		$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi","hobi","ASC");									

		$this->template($data);										

	}

	public function cari_id(){		

		$th 				= date("y");

		$bln 				= date("m");

		$tgl 				= date("d");

		$dealer 		= $this->session->userdata("id_karyawan_dealer");

		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 

								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();

		$kode_dealer 	= $isi->kode_dealer_md;

		$pr_num 			= $this->db->query("SELECT * FROM tr_spk ORDER BY no_spk DESC LIMIT 0,1");						

		if($pr_num->num_rows()>0){

			$row 	= $pr_num->row();				

			$pan  = strlen($row->no_spk)-9;

			$id 	= substr($row->no_spk,$pan,5)+1;	

			if($id < 10){

				$kode1 = $th."/".$bln."/".$tgl."/0000".$id;          

		    }elseif($id > 9 && $id <= 99){

				$kode1 = $th."/".$bln."/".$tgl."/000".$id;                    

		    }elseif($id > 99 && $id <= 999){

				$kode1 = $th."/".$bln."/".$tgl."/00".$id;          					          

		    }elseif($id > 999){

				$kode1 = $th."/".$bln."/".$tgl."/0".$id;                    

		    }

			$kode = $kode1."-".$kode_dealer;

		}else{

			$kode = $th."/".$bln."/".$tgl."/00001-".$kode_dealer;

		} 	



		$rt = rand(1111,9999);

		echo $kode."|".$rt;		

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

	public function take_spk(){

		$no_spk 		= $this->input->get("no_spk");		

		$cek 				= $this->db->query("SELECT * FROM tr_spk WHERE no_spk = '$no_spk'");

		if($cek->num_rows() > 0){

			$isi = $cek->row();
			$prospek = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer='$isi->id_customer' ORDER bY created_at DESC LIMIT 1")->row();


			$kerja				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$isi->pekerjaan'");

		  	if($kerja->num_rows() > 0){

		  		$tr = $kerja->row();

		  		$pekerjaan = $tr->pekerjaan;

		  	}else{

		  		$pekerjaan = "-";

		  	}



		  	$keluar				= $this->db->query("SELECT * FROM ms_pengeluaran_bulan WHERE id_pengeluaran_bulan = '$isi->pengeluaran_bulan'");

		  	if($keluar->num_rows() > 0){

		  		$tr = $keluar->row();

		  		$pengeluaran_bulan = $tr->pengeluaran;

		  	}else{

		  		$pengeluaran_bulan = "-";

		  	}

	  	

		  	$status				= $this->db->query("SELECT * FROM ms_status_hp WHERE id_status_hp = '$isi->status_hp'");

		  	if($status->num_rows() > 0){

		  		$tr = $status->row();

		  		$status_hp = $tr->status_hp;

		  	}else{

		  		$status_hp = "-";

		  	}



		  	$status2				= $this->db->query("SELECT * FROM ms_status_hp WHERE id_status_hp = '$isi->status_hp_2'");

		  	if($status2->num_rows() > 0){
		  		$tr = $status2->row();
		  		$status_hp_2 = $tr->status_hp;
		  	}else{
		  		$status_hp_2 = "-";
		   	}

			$nama_konsumen     = $isi->nama_konsumen;			
			$tempat_lahir      = $isi->tempat_lahir;			
			$tgl_lahir         = $isi->tgl_lahir;			
			$jenis_wn          = $isi->jenis_wn;			
			$no_kk             = $isi->no_kk;			
			$npwp              = $isi->npwp;			
			$id_kelurahan      = $isi->id_kelurahan;			
			$id_kelurahan2     = $isi->id_kelurahan2;			
			$alamat            = $isi->alamat;			
			$alamat2           = $isi->alamat2;			
			$kodepos           = $isi->kodepos;			
			$kodepos2          = $isi->kodepos2;			
			$denah_lokasi      = $isi->denah_lokasi;			
			$alamat_sama       = $isi->alamat_sama;			
			$status_rumah      = $isi->status_rumah;			
			$lama_tinggal      = $isi->lama_tinggal;			
			$pekerjaan         = $pekerjaan;			
			$lama_kerja        = $isi->lama_kerja;			
			$jabatan           = $isi->jabatan;			
			$penghasilan       = $isi->penghasilan;			
			$pengeluaran_bulan = $pengeluaran_bulan;			
			$no_hp             = $isi->no_hp;			
			$no_hp_2           = $isi->no_hp_2;			
			$status_hp         = $status_hp;			
			$status_hp_2       = $status_hp_2;			
			$no_telp           = $isi->no_telp;			
			$email             = $isi->email;			
			$refferal_id       = $isi->refferal_id;			
			$robd_id           = $isi->robd_id;			
			$nama_ibu          = $isi->nama_ibu;			
			$tgl_ibu           = $isi->tgl_ibu;			
			$keterangan        = $isi->keterangan;			
			$no_ktp            = $isi->no_ktp;
			$agama             = $prospek->agama;
			$jenis_sebelumnya  = $prospek->jenis_sebelumnya;
			$merk_sebelumnya   = $prospek->merk_sebelumnya;
			$menggunakan       = $prospek->pemakai_motor;

			$sub_pekerjaan='';
			$nama_tempat_usaha='';
			$alamat_instansi='';
			$kelurahan_instansi='';
			$kecamatan_instansi='';
			$kabupaten_instansi='';
			$provinsi_instansi='';
			if($prospek->sub_pekerjaan!=''){
				$sub_pekerjaan=$this->db->query("SELECT sub_pekerjaan FROM ms_sub_pekerjaan WHERE id_sub_pekerjaan = '$prospek->sub_pekerjaan'")->row()->sub_pekerjaan;
			}
			
			if($prospek->id_kelurahan_kantor!=''){
				$nama_tempat_usaha=$prospek->nama_tempat_usaha;
				$alamat_instansi=$prospek->alamat_kantor;
				$temp_id_kelurahan	= $prospek->id_kelurahan_kantor;
				$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$temp_id_kelurahan'")->row();
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
				$kelurahan_instansi=$kelurahan;
				$kecamatan_instansi=$kecamatan;
				$kabupaten_instansi=$kabupaten;
				$provinsi_instansi=$provinsi;
			}

			$pekerjaan_lain=$prospek->pekerjaan_lain;
		}else{

			$no_ktp            = "";			
			$nama_konsumen     = "";			
			$tempat_lahir      = "";			
			$tgl_lahir         = "";			
			$jenis_wn          = "";			
			$no_kk             = "";			
			$npwp              = "";			
			$id_kelurahan      = "";			
			$id_kelurahan2     = "";			
			$alamat            = "";			
			$alamat2           = "";			
			$kodepos           = "";			
			$kodepos2          = "";			
			$denah_lokasi      = "";			
			$alamat_sama       = "";			
			$status_rumah      = "";			
			$lama_tinggal      = "";			
			$pekerjaan         = "";			
			$lama_kerja        = "";			
			$jabatan           = "";			
			$penghasilan       = "";			
			$pengeluaran_bulan = "";			
			$no_hp             = "";			
			$no_hp_2           = "";			
			$status_hp         = "";			
			$status_hp_2       = "";			
			$no_telp           = "";			
			$email             = "";			
			$refferal_id       = "";			
			$robd_id           = "";			
			$nama_ibu          = "";
			$tgl_ibu           = "";
			$keterangan        = "";
			$agama             = '';
			$menggunakan       = '';
			$jenis_sebelumnya = '';
			$merk_sebelumnya = '';
			$sub_pekerjaan='';
			$pekerjaan_lain='';
			$nama_tempat_usaha='';
			$alamat_instansi='';
			$kelurahan_instansi='';
			$kecamatan_instansi='';
			$kabupaten_instansi='';
			$provinsi_instansi='';
		}  	

  	// echo $no_spk."|".$nama_konsumen."|".$tempat_lahir."|".$tgl_lahir."|".$jenis_wn."|".$no_kk."|".$npwp."|".$id_kelurahan."|".$id_kelurahan2."|".$alamat."|".$alamat2."|".$kodepos."|".$denah_lokasi."|".$alamat_sama."|".$status_rumah."|".$lama_tinggal."|".$pekerjaan."|".$lama_kerja."|".$jabatan."|".$penghasilan."|".$pengeluaran_bulan."|".$no_hp."|".$no_hp_2."|".$status_hp."|".$status_hp_2."|".$no_telp."|".$email."|".$refferal_id."|".$robd_id."|".$nama_ibu."|".$tgl_ibu."|".$keterangan."|".$no_ktp; 
  	$data_arr = ['no_spk'=>$no_spk,
  				'nama_konsumen'=>$nama_konsumen,
  				'tempat_lahir'=>$tempat_lahir,
  				'tgl_lahir'=>$tgl_lahir,
  				'jenis_wn'=>$jenis_wn,
  				'no_kk'=>$no_kk,
  				'npwp'=>$npwp,
  				'id_kelurahan'=>$id_kelurahan,
  				'id_kelurahan2'=>$id_kelurahan2,
  				'alamat'=>$alamat,
  				'alamat2'=>$alamat2,
  				'kodepos'=>$kodepos,
  				'kodepos2'=>$kodepos2,
  				'denah_lokasi'=>$denah_lokasi,
  				'tanya'=>$alamat_sama,
  				'status_rumah'=>$status_rumah,
  				'lama_tinggal'=>$lama_tinggal,
  				'pekerjaan'=>$pekerjaan,
  				'lama_kerja'=>$lama_kerja,
  				'jabatan'=>$jabatan,
  				'penghasilan'=>$penghasilan,
  				'pengeluaran_bulan'=>$pengeluaran_bulan,
  				'no_hp'=>$no_hp,
  				'no_hp_2'=>$no_hp_2,
  				'status_hp'=>$status_hp,
  				'status_hp_2'=>$status_hp_2,
  				'no_telp'=>$no_telp,
  				'email'=>$email,
  				'refferal_id'=>$refferal_id,
  				'robd_id'=>$robd_id,
  				'nama_ibu'=>$nama_ibu,
  				'tgl_ibu'=>$tgl_ibu,
  				'keterangan'=>$keterangan,
  				'no_ktp'=>$no_ktp,
  				'menggunakan'=>$menggunakan,
  				'jenis_sebelumnya'=>$jenis_sebelumnya,
  				'merk_sebelumnya'=>$merk_sebelumnya,
  				'agama' => $agama,
  				'sub_pekerjaan' => $sub_pekerjaan,
  				'pekerjaan_lain' => $pekerjaan_lain,
  				'nama_tempat_usaha' => $nama_tempat_usaha,
  				'alamat_instansi' => $alamat_instansi,
  				'kelurahan_instansi' => $kelurahan_instansi,
  				'kecamatan_instansi' => $kecamatan_instansi,
  				'kabupaten_instansi' => $kabupaten_instansi,
  				'provinsi_instansi' => $provinsi_instansi
  				];
  				echo json_encode($data_arr);
	}	

	public function save()

	{		

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= $this->tables;

		$pk					= $this->pk;

		$id  				= $this->input->post($pk);

		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();		



		if($cek == 0){				



			$data['jenis_beli']            = $this->input->post('jenis_beli');
			
			$data['agama']                 = $this->input->post('agama');
			
			$data['hobi']                  = $this->input->post('hobi');
			
			$data['pendidikan']            = $this->input->post('pendidikan');
			
			$data['sedia_hub']             = $this->input->post('sedia_hub');
			
			$data['jenis_sebelumnya']      = $this->input->post('jenis_sebelumnya');
			
			$data['merk_sebelumnya']       = $this->input->post('merk_sebelumnya');
			
			$data['digunakan']             = $this->input->post('digunakan');
			$data['menggunakan']           = $this->input->post('menggunakan');
			$data['facebook']              = $this->input->post('facebook');
			$data['twitter']               = $this->input->post('twitter');
			$data['instagram']             = $this->input->post('instagram');
			$data['youtube']               = $this->input->post('youtube');
			$no_spk = $data['no_spk']                = $this->input->post('no_spk');
			$data['nama_instansi']         = $this->input->post('nama_instansi');
			$data['alamat_instansi']       = $this->input->post('alamat_instansi');
			$data['id_kecamatan_instansi'] = $this->input->post('id_kecamatan_instansi');
			$data['aktivitas_penjualan']   = $this->input->post('aktivitas_penjualan');

			$data['id_dealer']				= $this->m_admin->cari_dealer();		

			$data['created_at']				= $waktu;		

			$data['created_by']				= $login_id;				
			$kk_nik                  = $this->input->post('kk_nik');
			$kk_nama_lengkap         = $this->input->post('kk_nama_lengkap');
			$kk_id_jk                = $this->input->post('kk_id_jk');
			$kk_tempat_lahir         = $this->input->post('kk_tempat_lahir');
			$kk_tgl_lahir            = $this->input->post('kk_tgl_lahir');
			$kk_id_agama             = $this->input->post('kk_id_agama');
			$kk_id_pendidikan        = $this->input->post('kk_id_pendidikan');
			$kk_id_pekerjaan         = $this->input->post('kk_id_pekerjaan');
			$kk_id_status_pernikahan = $this->input->post('kk_id_status_pernikahan');
			$kk_id_hub_keluarga      = $this->input->post('kk_id_hub_keluarga');
			$kk_jenis_wn             = $this->input->post('kk_jenis_wn');
			$kk_pekerjaan_lain       = $this->input->post('kk_pekerjaan_lain');
			
			$this->db->trans_begin();
				$this->m_admin->insert($tabel,$data);
				$id_cdb = $this->db->insert_id();
				foreach ($kk_nik as $key=>$val) {
					$ins_kk[] = ['id_cdb'=> $id_cdb,
								'no_spk'               => $no_spk,
								'nik'                  => $val,
								'jk'                   => $kk_id_jk[$key],
								'nama_lengkap'         => $kk_nama_lengkap[$key],
								'tempat_lahir'         => $kk_tempat_lahir[$key],
								'tgl_lahir'            => $kk_tgl_lahir[$key],
								'id_agama'             => $kk_id_agama[$key],
								'id_pendidikan'        => $kk_id_pendidikan[$key],
								'id_pekerjaan'         => $kk_id_pekerjaan[$key],
								'id_status_pernikahan' => $kk_id_status_pernikahan[$key],
								'id_hub_keluarga'      => $kk_id_hub_keluarga[$key],
								'jenis_wn'             => $kk_jenis_wn[$key],
								'pekerjaan_lain'       => $kk_pekerjaan_lain[$key]
							  ];
				}
				if (isset($ins_kk)) {
					$this->db->insert_batch('tr_cdb_kk',$ins_kk);
				}
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
	      	}else {
	      		$this->db->trans_commit();
	      		$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/cdb_d'>";
	      	}			

		}else{

			$_SESSION['pesan'] 	= "Duplicate entry for primary key";

			$_SESSION['tipe'] 	= "danger";

			echo "<script>history.go(-1)</script>";

		}

	}	

	public function cek_spk()

	{		

		$no_spk_gc = $this->input->post('no_spk_gc');

		$sql = $this->db->query("SELECT * FROM tr_spk_gc WHERE no_spk_gc = '$no_spk_gc'");

		if($sql->num_rows() > 0){

			$dt_ve = $sql->row();						

			echo "ok"."|".$dt_ve->nama_npwp."|".$dt_ve->no_npwp."|".$dt_ve->alamat."|".$dt_ve->id_kelurahan."|".$dt_ve->jenis_gc."|".$dt_ve->no_telp."|".$dt_ve->tgl_berdiri."|".$dt_ve->nama_penanggung_jawab."|".$dt_ve->email."|".$dt_ve->no_hp."|".$dt_ve->status_nohp."|".$dt_ve->kodepos;

		}else{

			echo "There is no data found!";

		}

	}

	public function save_gc()

	{		

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= "tr_cdb_gc";

		$pk					= "id_cdb_gc";

		$id  				= $this->input->post($pk);

		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();		



		if($cek == 0){						

			$data['sedia_hub'] 				= $this->input->post('sedia_hub');			

			$data['facebook'] 				= $this->input->post('facebook');

			$data['twitter'] 					= $this->input->post('twitter');

			$data['instagram'] 				= $this->input->post('instagram');

			$data['youtube'] 					= $this->input->post('youtube');

			$data['refferal_id'] 			= $this->input->post('refferal_id');

			$data['robd_id'] 					= $this->input->post('robd_id');			

			$data['no_spk_gc'] 				= $this->input->post('no_spk_gc');			

			$data['id_dealer']				= $this->m_admin->cari_dealer();		

			$data['created_at']				= $waktu;		

			$data['created_by']				= $login_id;				

			$this->m_admin->insert($tabel,$data);

			$_SESSION['pesan'] 	= "Data has been saved successfully";

			$_SESSION['tipe'] 	= "success";

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/cdb_d/add_gc'>";

			

		}else{

			$_SESSION['pesan'] 	= "Duplicate entry for primary key";

			$_SESSION['tipe'] 	= "danger";

			echo "<script>history.go(-1)</script>";

		}

	}	

	public function edit()

	{		

		$tabel		= $this->tables;

		$pk 			= $this->pk;		

		$id 			= $this->input->get('id');

		$d 				= array($pk=>$id);		

		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_cdb'] = $this->m_admin->kondisi($tabel,$d);		

		$data['isi']    = $this->page;		

		$data['title']	= "Edit ".$this->title;				

		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,ms_kelurahan.id_kelurahan FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan=ms_kelurahan.id_kelurahan WHERE id_dealer='$id_dealer' AND no_spk NOT IN (SELECT no_spk FROM tr_cdb)

																							ORDER BY ms_kelurahan.kelurahan ASC");				

		$data['dt_agama'] 		= $this->m_admin->getSortCond("ms_agama","id_agama","ASC");												

		$data['dt_pendidikan'] = $this->m_admin->getActData("ms_pendidikan","id_pendidikan","ASC");								

		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");								

		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");								

		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");															

		$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi","hobi","ASC");									

		$data['set']		= "edit";											

		$this->template($data);	

	}

	public function edit_gc()

	{		

		$tabel		= "tr_cdb_gc";

		$pk 			= "id_cdb_gc";		

		$id 			= $this->input->get('id_c');

		$d 				= array($pk=>$id);		

		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_cdb'] = $this->db->query("SELECT * FROM tr_cdb_gc INNER JOIN tr_spk_gc ON tr_cdb_gc.no_spk_gc = tr_spk_gc.no_spk_gc

			WHERE tr_cdb_gc.id_cdb_gc = '$id'");		

		$data['isi']    = $this->page;		

		$data['title']	= "Edit ".$this->title." Group Customer";				

		$data['dt_spk'] = $this->db->query("SELECT tr_spk_gc.*,ms_kelurahan.id_kelurahan FROM tr_spk_gc LEFT JOIN ms_kelurahan ON tr_spk_gc.id_kelurahan=ms_kelurahan.id_kelurahan WHERE id_dealer='$id_dealer' AND no_spk_gc NOT IN (SELECT no_spk_gc FROM tr_cdb_gc)

																							ORDER BY ms_kelurahan.kelurahan ASC");						

		$data['set']		= "edit_gc";											

		$this->template($data);	

	}

	public function update()

	{		

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= $this->tables;

		$pk					= $this->pk;

		$id_cdb  		= $this->input->post("id_cdb");

		



			$data['jenis_beli'] 			= $this->input->post('jenis_beli');

			$data['agama'] 						= $this->input->post('agama');

			$data['hobi'] 						= $this->input->post('hobi');

			$data['pendidikan']				= $this->input->post('pendidikan');

			$data['sedia_hub'] 				= $this->input->post('sedia_hub');

			$data['jenis_sebelumnya'] = $this->input->post('jenis_sebelumnya');

			$data['merk_sebelumnya'] 	= $this->input->post('merk_sebelumnya');

			$data['digunakan'] 				= $this->input->post('digunakan');

			$data['menggunakan'] 			= $this->input->post('menggunakan');

			$data['facebook'] 				= $this->input->post('facebook');

			$data['twitter'] 					= $this->input->post('twitter');

			$data['instagram'] 				= $this->input->post('instagram');

			$data['youtube'] 					= $this->input->post('youtube');

			$data['no_spk'] 					= $this->input->post('no_spk');
			$data['nama_instansi']         = $this->input->post('nama_instansi');
			$data['alamat_instansi']       = $this->input->post('alamat_instansi');
			$data['id_kecamatan_instansi'] = $this->input->post('id_kecamatan_instansi');
			$data['aktivitas_penjualan']   = $this->input->post('aktivitas_penjualan');

			$data['id_dealer']				= $this->m_admin->cari_dealer();		

			$data['updated_at']				= $waktu;		

			$data['updated_by']				= $login_id;				

			$kk_nik                  = $this->input->post('kk_nik');
			$kk_nama_lengkap         = $this->input->post('kk_nama_lengkap');
			$kk_id_jk                = $this->input->post('kk_id_jk');
			$kk_tempat_lahir         = $this->input->post('kk_tempat_lahir');
			$kk_tgl_lahir            = $this->input->post('kk_tgl_lahir');
			$kk_id_agama             = $this->input->post('kk_id_agama');
			$kk_id_pendidikan        = $this->input->post('kk_id_pendidikan');
			$kk_id_pekerjaan         = $this->input->post('kk_id_pekerjaan');
			$kk_id_status_pernikahan = $this->input->post('kk_id_status_pernikahan');
			$kk_id_hub_keluarga      = $this->input->post('kk_id_hub_keluarga');
			$kk_jenis_wn             = $this->input->post('kk_jenis_wn');
			$kk_pekerjaan_lain       = $this->input->post('kk_pekerjaan_lain');

			$cdb = $this->db->get_where('tr_cdb',['id_cdb'=>$id_cdb])->row();
			
			$this->db->trans_begin();
				$this->m_admin->update($tabel,$data,"id_cdb",$id_cdb);
				$this->db->delete('tr_cdb_kk',['id_cdb'=>$id_cdb]);
				foreach ($kk_nik as $key=>$val) {
					$ins_kk[] = ['id_cdb'=> $id_cdb,
								'no_spk'               => $cdb->no_spk,
								'nik'                  => $val,
								'jk'                   => $kk_id_jk[$key],
								'nama_lengkap'         => $kk_nama_lengkap[$key],
								'tempat_lahir'         => $kk_tempat_lahir[$key],
								'tgl_lahir'            => $kk_tgl_lahir[$key],
								'id_agama'             => $kk_id_agama[$key],
								'id_pendidikan'        => $kk_id_pendidikan[$key],
								'id_pekerjaan'         => $kk_id_pekerjaan[$key],
								'id_status_pernikahan' => $kk_id_status_pernikahan[$key],
								'id_hub_keluarga'      => $kk_id_hub_keluarga[$key],
								'jenis_wn'             => $kk_jenis_wn[$key],
								'pekerjaan_lain'       => $kk_pekerjaan_lain[$key]
							  ];
				}
				if (isset($ins_kk)) {
					$this->db->insert_batch('tr_cdb_kk',$ins_kk);
				}
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
	      	}else {
	      		$this->db->trans_commit();
	      		$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/cdb_d'>";
	      	}		

			$_SESSION['pesan'] 	= "Data has been updated successfully";

			$_SESSION['tipe'] 	= "success";

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/cdb_d'>";

					

	}	

	public function update_gc()

	{		

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= "tr_cdb_gc";

		$pk					= "id_cdb_gc";

		$id  				= $this->input->post($pk);

		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();		



						

		$data['sedia_hub'] 				= $this->input->post('sedia_hub');			

		$data['facebook'] 				= $this->input->post('facebook');

		$data['twitter'] 					= $this->input->post('twitter');

		$data['instagram'] 				= $this->input->post('instagram');

		$data['youtube'] 					= $this->input->post('youtube');

		$data['refferal_id'] 			= $this->input->post('refferal_id');

		$data['robd_id'] 					= $this->input->post('robd_id');					

		$data['updated_at']				= $waktu;		

		$data['updated_by']				= $login_id;				

		$this->m_admin->update($tabel,$data,"id_cdb_gc",$id);

		$_SESSION['pesan'] 	= "Data has been updated successfully";

		$_SESSION['tipe'] 	= "success";

		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/cdb_d/gc'>";

					

	}	

	public function fetch_kecamatan()
   {
		$fetch_data = $this->make_query_kecamatan()->result();  
		$data = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array   = array();
			$sub_array[] = $rs->id_kecamatan;
			$sub_array[] = $rs->kecamatan;
			$sub_array[] = $rs->kabupaten;
			$sub_array[] = $rs->provinsi;
			$row         = json_encode($rs);
			$link        ='<button data-dismiss=\'modal\' onClick=\'return pilihKecamatan('.$row.')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_kec(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   function make_query_kecamatan($no_limit=null)  
   	{  
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_kecamatan','kecamatan','kabupaten','provinsi',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY kecamatan ASC';
		$search       = $this->input->post('search')['value'];
		$searchs = '';
		if ($search!='') {
	      $searchs .= "AND (id_kecamatan LIKE '%$search%' 
	          OR kecamatan LIKE '%$search%'
	          OR kabupaten LIKE '%$search%'
	          OR provinsi LIKE '%$search%'
	          )
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT * FROM ms_kecamatan
			JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten=ms_kabupaten.id_kabupaten
			JOIN ms_provinsi ON ms_kabupaten.id_provinsi=ms_provinsi.id_provinsi
   		 	$searchs $order $limit ");
   	}  
   	function get_filtered_data_kec(){  
		return $this->make_query_kecamatan('y')->num_rows();  
   	}

   	function getKecamatanInstansi()
   	{
   		$id_kecamatan = $this->input->post('id_kecamatan');
   		$kec = $this->db->query("SELECT id_kecamatan,kecamatan,kabupaten,provinsi FROM ms_kecamatan
			JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten=ms_kabupaten.id_kabupaten
			JOIN ms_provinsi ON ms_kabupaten.id_provinsi=ms_provinsi.id_provinsi
   		 	WHERE id_kecamatan='$id_kecamatan'")->row();
   		echo json_encode($kec);
   	}
}