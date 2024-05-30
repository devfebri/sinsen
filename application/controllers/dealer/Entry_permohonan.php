<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry_permohonan extends CI_Controller {

	var $tables = "tr_permohonan_konsumen";	
	var $folder = "dealer";
	var $page   = "entry_permohonan";
	var $pk     = "id_list_appointment";
	var $title  = "Entry Permohonan Konsumen (Dealer HO)";

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
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_permohonan_konsumen'] = $this->db->query("SELECT * FROM tr_permohonan_konsumen LEFT JOIN ms_agama ON tr_permohonan_konsumen.id_agama=ms_agama.id_agama WHERE tr_permohonan_konsumen.id_dealer = '$id_dealer' ORDER BY tr_permohonan_konsumen.id_list_appointment DESC");		
		// $data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	public function add()
	{				
		$this->m_admin->reset_tmp("tr_permohonan_konsumen","tr_permohonan_keluarga","id_list_appointment");
		$this->m_admin->reset_tmp("tr_permohonan_konsumen","tr_permohonan_kendaraan","id_list_appointment");
		$id_dealer = $this->m_admin->cari_dealer();
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	   = "add";							
		$data['dt_item'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = '1' ORDER BY id_scan_barcode ASC");						
		$data['dt_jenis'] = $this->m_admin->getSortCond("ms_jenis_pembelian","jenis_pembelian","ASC");							
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan","id_pekerjaan","ASC");							
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan","id_pendidikan","ASC");							
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan","pengeluaran","ASC");									
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp","status_hp","ASC");							
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");							
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");	
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama","id_agama","ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");	
		$data['dt_karyawan_dealer'] = $this->m_admin->getSortDealer("ms_karyawan_dealer","id_karyawan_dealer","ASC",$id_dealer);	
		$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi","hobi","ASC");							
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
	public function t_kendaraan(){
		$id = $this->input->post('id_list_appointment');
		$data['dt_kendaraan'] = $this->db->query("SELECT * FROM tr_permohonan_kendaraan WHERE id_list_appointment = '$id'");		
		$this->load->view('dealer/t_entry_kendaraan',$data);
	}
	public function t_keluarga(){
		$id = $this->input->post('id_list_appointment');
		$data['dt_keluarga'] = $this->db->query("SELECT * FROM tr_permohonan_keluarga WHERE id_list_appointment = '$id'");		
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan","pekerjaan","ASC");							
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan","pendidikan","ASC");							
		$this->load->view('dealer/t_entry_keluarga',$data);
	}
	
	public function cari_id_fix(){		
		$th 						= date("y");
		$waktu 					= gmdate("y-m-d h:i:s", time()+60*60*7);				
		$id_dealer  		= $this->m_admin->cari_dealer();		
		$pr_num 				= $this->db->query("SELECT * FROM tr_permohonan_konsumen WHERE id_dealer = '$id_dealer' ORDER BY id_list_appointment DESC LIMIT 0,1");
			$row 	= $pr_num->row();				

		if ($pr_num->num_rows() > 0) {
			return $old_kode = substr($row->id_list_appointment,4);
			$old_th = substr($row->id_list_appointment,2,4);
			if ($th != $old_th) {
				$kode='KO'.$th.'00001';
			}else{
				$kode = 'KO'.$th.sprintf("%05d", $old_kode+1);
			}
		}else{
			$kode = 'KO'.$th.'00001';
		}
		/*					
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_list_appointment)-5;
			$id 	= substr($row->id_list_appointment,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $th."00".$id;          					          
      }elseif($id>999){
					$kode1 = $th."0".$id;                    
      }
			$kode = "KO".$kode1;
		}else{
			$kode = "KO".$th."00001";
		} 	
*/
		return $kode;
	}
	public function cari_id(){		
		$th 						= date("y");
		$waktu 					= gmdate("y-m-d h:i:s", time()+60*60*7);				
		$id_dealer 			= $this->m_admin->cari_dealer();
		$pr_num 				= $this->db->query("SELECT * FROM tr_permohonan_konsumen WHERE id_dealer = '$id_dealer' ORDER BY id_list_appointment DESC LIMIT 0,1");						
		$token 					= $this->m_admin->get_sess();
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_list_appointment)-5;
			$id 	= substr($row->id_list_appointment,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $th."00".$id;          					          
      }elseif($id>999){
					$kode1 = $th."0".$id;                    
      }
			$kode = "KO".$kode1.$token;
		}else{
			$kode = "KO".$th."00001".$token;
		} 	

		echo $kode;
	}
	public function save_kendaraan(){
		
		$no_mesin		= $this->input->post('no_mesin');					
		$id_list_appointment		= $this->input->post('id_list_appointment');					
		$data['id_list_appointment']			= $this->input->post('id_list_appointment');			
		$data['no_mesin']							= $this->input->post('no_mesin');		
		$data['id_user']							= $this->session->userdata('id_user');		
		
		$c = $this->db->query("SELECT * FROM tr_permohonan_kendaraan WHERE id_list_appointment = '$id_list_appointment' AND no_mesin = '$no_mesin'");
		if($c->num_rows() > 0){
			echo "no";
		}else{
			$cek2 = $this->m_admin->insert("tr_permohonan_kendaraan",$data);						
			echo "ok";
		}							
	}	
	public function delete_kendaraan(){
		$id = $this->input->post('id_permohonan_kendaraan');		
		$this->db->query("DELETE FROM tr_permohonan_kendaraan WHERE id_permohonan_kendaraan = '$id'");			
		echo "nihil";
	}
	public function cek_kecamatan(){
		$id_kelurahan		= $this->input->post('id_kelurahan');
		$kel 				= $this->db->query("SELECT * FROM ms_kelurahan WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'")->row();						
		$kelurahan 	= $kel->kelurahan;

		$kec 				= $this->db->query("SELECT * FROM ms_kecamatan INNER JOIN ms_kelurahan ON ms_kelurahan.id_kecamatan=ms_kecamatan.id_kecamatan 
												WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'")->row();						
		$kecamatan 	= $kec->kecamatan;
		$id_kecamatan 	= $kec->id_kecamatan;

		$kab 				= $this->db->query("SELECT * FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten=ms_kabupaten.id_kabupaten 
												WHERE ms_kecamatan.id_kecamatan = '$id_kecamatan'")->row();						
		$kabupaten 	= $kab->kabupaten;
		$id_kabupaten 	= $kab->id_kabupaten;

		$prov 				= $this->db->query("SELECT * FROM ms_provinsi INNER JOIN ms_kabupaten ON ms_kabupaten.id_provinsi=ms_provinsi.id_provinsi 
												WHERE ms_kabupaten.id_kabupaten = '$id_kabupaten'")->row();						
		$provinsi 	= $prov->provinsi;
		$id_provinsi 	= $prov->id_provinsi;		 	

		echo $id_kecamatan."|".$kecamatan."|".$id_kabupaten."|".$kabupaten."|".$id_provinsi."|".$provinsi."|".$kelurahan;
	}
	public function save_keluarga(){
		$id_list_appointment	= $this->input->post('id_list_appointment');			
		$nik		= $this->input->post('nik');
		$data['id_list_appointment']		= $this->input->post('id_list_appointment');			
		$data['nik']		= $this->input->post('nik');							
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');							
		$data['nama_keluarga']		= $this->input->post('nama_keluarga');							
		$data['tgl_lahir']		= $this->input->post('tanggal1');							
		$data['status_kawin']		= $this->input->post('status_kawin');							
		$data['posisi_keluarga']		= $this->input->post('posisi_keluarga');							
		$data['pekerjaan']		= $this->input->post('pekerjaan');							
		$data['pendidikan']		= $this->input->post('pendidikan');							
		$data['no_hp']		= $this->input->post('no_hp');
		$data['id_user']							= $this->session->userdata('id_user');																	
		$cek = $this->db->get_where("tr_permohonan_keluarga",array("id_list_appointment"=>$id_list_appointment,"nik"=>$nik));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_permohonan_keluarga;
			$this->m_admin->update("tr_permohonan_keluarga",$data,"id_permohonan_keluarga",$id);			
		}else{
			$this->m_admin->insert("tr_permohonan_keluarga",$data);			
		}
		echo "nihil";
	}
	public function delete_keluarga(){
		$id = $this->input->post('id_permohonan_keluarga');		
		$this->db->query("DELETE FROM tr_permohonan_keluarga WHERE id_permohonan_keluarga = '$id'");			
		echo "nihil";
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
			$isi_ktp = $this->input->post('id_ktp');
			$ktp = strlen($isi_ktp);
			if($ktp < 16){
				$jum = 16 - $ktp;
				$r = "";
				for ($i=1; $i <= $jum; $i++) { 
					$r = $r."0";
				}
				$ktp_f = $r.$isi_ktp;
			}else{
				$ktp_f = $isi_ktp;
			}

			$id_list_appointment							= $this->cari_id_fix();
			$id_old														= $this->input->post('id_list_appointment');		
			$data['id_list_appointment']			= $id_list_appointment;
			$data['id_ktp'] 									= $ktp_f;
			$data['no_kk'] 										= $this->input->post('no_kk');	
			$data['jenis_wn'] 								= $this->input->post('jenis_wn');	
			$data['jenis_beli'] 							= $this->input->post('jenis_pembelian');
			$data['pekerjaan']								= $this->input->post('pekerjaan');
			$data['pendidikan']								= $this->input->post('pendidikan');
			$data['no_hp']										= $this->input->post('no_hp');
			$data['tgl_lahir']								= $this->input->post('tgl_lahir');
			$data['tipe_customer']						= $this->input->post('tipe_customer');
			$data['jenis_pembelian']					= $this->input->post('jenis_pembelian');
			$data['tgl_beli']					= $this->input->post('tgl_beli');
			$data['ro']												= $this->input->post('ro');			
			$data['pengeluaran_bulan']				= $this->input->post('pengeluaran_bulan');
			$data['nama_pemilik']							= $this->input->post('nama_pemilik');
			$data['jenis_kelamin']				 		= $this->input->post('jenis_kelamin');			
			$data['no_telp']									= $this->input->post('no_telp');
			$data['id_kelurahan']				 			= $this->input->post('id_kelurahan');
			$data['id_kecamatan']				 			= $this->input->post('id_kecamatan');
			$data['id_kabupaten']				 			= $this->input->post('id_kabupaten');
			$data['id_provinsi']				 			= $this->input->post('id_provinsi');
			$data['status_hp']				 				= $this->input->post('status_hp');
			$data['id_kecamatan']							= $this->input->post('id_kecamatan');
			$data['bersedia_informasi']				= $this->input->post('bersedia_informasi');
			$data['id_kabupaten']							= $this->input->post('id_kabupaten');
			$data['merk_sebelumnya']				 	= $this->input->post('merk_sebelumnya');			
			$data['jenis_sebelumnya']				 	= $this->input->post('jenis_sebelumnya');
			$data['id_agama']									= $this->input->post('id_agama');
			$data['digunakan_untuk']					= $this->input->post('digunakan_untuk');
			$data['email']										= $this->input->post('email');
			$data['menggunakan_motor']				= $this->input->post('menggunakan_motor');
			$data['alamat_koresponden']				= $this->input->post('alamat_koresponden');
			$data['status_rumah']							= $this->input->post('status_rumah');
			$data['jenis_penjualan']					= $this->input->post('jenis_penjualan');
			$data['facebook']				 					= $this->input->post('facebook');
			$data['id_karyawan_dealer']				= $this->input->post('id_karyawan_dealer');
			$data['instagram']								= $this->input->post('instagram');
			$data['twitter']				 					= $this->input->post('twitter');
			$data['hobi']											= $this->input->post('hobi');
			$data['youtube']									= $this->input->post('youtube');
			$data['karakteristik']				 		= $this->input->post('karakteristik');
			$id_dealer = $this->m_admin->cari_dealer();
			$data['id_dealer']				 				= $id_dealer;
			
			if($this->input->post('dokumen_ktp') == '1') $data['dokumen_ktp'] = $this->input->post('dokumen_ktp');		
				else $data['dokumen_ktp'] 		= "";			
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;

			$cek = $this->m_admin->getByID("tr_permohonan_kendaraan","id_list_appointment",$id_old);
			foreach ($cek->result() as $isi) {
				$this->db->query("UPDATE tr_permohonan_kendaraan SET id_list_appointment = '$id_list_appointment' WHERE id_list_appointment = '$id_old'");
			}
			$cek2 = $this->m_admin->getByID("tr_permohonan_konsumen","id_list_appointment",$id_old);
			foreach ($cek2->result() as $isi) {
				$this->db->query("UPDATE tr_permohonan_konsumen SET id_list_appointment = '$id_list_appointment' WHERE id_list_appointment = '$id_old'");
			}
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/entry_permohonan/add'>"; 
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function edit(){
		$id = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;
		$data['set']		= "edit";
		$dt_permohonan = $data['dt_permohonan'] = $this->m_admin->getByID("tr_permohonan_konsumen","id_list_appointment",$id);
		if ($dt_permohonan->num_rows()==0) {
			$_SESSION['pesan'] 	= "Data dengan Kode $id tidak ditemukan !";
			$_SESSION['tipe'] 	= "danger";
			redirect(base_url('dealer/entry_permohonan'),'refresh');	
		}
		$data['dt_item'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = '1' ORDER BY id_scan_barcode ASC");						
		$data['dt_jenis'] = $this->m_admin->getSortCond("ms_jenis_pembelian","jenis_pembelian","ASC");							
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan","pekerjaan","ASC");							
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan","pendidikan","ASC");							
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan","pengeluaran","ASC");													
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp","status_hp","ASC");							
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");							
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");	
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama","agama","ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");	
		$data['dt_karyawan_dealer'] = $this->m_admin->getSortCond("ms_karyawan_dealer","id_karyawan_dealer","ASC");	
		$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi","hobi","ASC");							
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
			$isi_ktp = $this->input->post('id_ktp');
			$ktp = strlen($isi_ktp);
			if($ktp < 16){
				$jum = 16 - $ktp;
				$r = "";
				for ($i=1; $i <= $jum; $i++) { 
					$r = $r."0";
				}
				$ktp_f = $r.$isi_ktp;
			}else{
				$ktp_f = $isi_ktp;
			}

			$data['id_ktp'] 									= $ktp_f;
			$data['no_kk'] 										= $this->input->post('no_kk');	
			$data['jenis_wn'] 								= $this->input->post('jenis_wn');	
			$data['jenis_beli'] 							= $this->input->post('jenis_pembelian');
			$data['id_list_appointment']			= $this->input->post('id_list_appointment');		
			$data['pekerjaan']								= $this->input->post('pekerjaan');
			$data['pendidikan']								= $this->input->post('pendidikan');
			$data['no_hp']										= $this->input->post('no_hp');
			$data['tgl_lahir']								= $this->input->post('tgl_lahir');
			$data['tipe_customer']						= $this->input->post('tipe_customer');
			$data['jenis_pembelian']					= $this->input->post('jenis_pembelian');
			$data['tgl_beli']					= $this->input->post('tgl_beli');
			$data['ro']												= $this->input->post('ro');			
			$data['pengeluaran_bulan']				= $this->input->post('pengeluaran_bulan');
			$data['nama_pemilik']							= $this->input->post('nama_pemilik');
			$data['jenis_kelamin']				 		= $this->input->post('jenis_kelamin');			
			$data['no_telp']									= $this->input->post('no_telp');
			$data['id_kelurahan']				 			= $this->input->post('id_kelurahan');
			$data['id_kecamatan']				 			= $this->input->post('id_kecamatan');
			$data['id_kabupaten']				 			= $this->input->post('id_kabupaten');
			$data['id_provinsi']				 			= $this->input->post('id_provinsi');
			$data['status_hp']				 				= $this->input->post('status_hp');
			$data['id_kecamatan']							= $this->input->post('id_kecamatan');
			$data['bersedia_informasi']				= $this->input->post('bersedia_informasi');
			$data['id_kabupaten']							= $this->input->post('id_kabupaten');
			$data['merk_sebelumnya']				 	= $this->input->post('merk_sebelumnya');			
			$data['jenis_sebelumnya']				 	= $this->input->post('jenis_sebelumnya');
			$data['id_agama']									= $this->input->post('id_agama');
			$data['digunakan_untuk']					= $this->input->post('digunakan_untuk');
			$data['email']										= $this->input->post('email');
			$data['menggunakan_motor']				= $this->input->post('menggunakan_motor');
			$data['alamat_koresponden']				= $this->input->post('alamat_koresponden');
			$data['status_rumah']							= $this->input->post('status_rumah');
			$data['jenis_penjualan']					= $this->input->post('jenis_penjualan');
			$data['facebook']				 					= $this->input->post('facebook');
			$data['id_karyawan_dealer']				= $this->input->post('id_karyawan_dealer');
			$data['instagram']								= $this->input->post('instagram');
			$data['twitter']				 					= $this->input->post('twitter');
			$data['hobi']											= $this->input->post('hobi');
			$data['youtube']									= $this->input->post('youtube');
			$data['karakteristik']				 		= $this->input->post('karakteristik');
			
			if($this->input->post('dokumen_ktp') == '1') $data['dokumen_ktp'] = $this->input->post('dokumen_ktp');		
				else $data['dokumen_ktp'] 		= "";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;	
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/entry_permohonan'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}