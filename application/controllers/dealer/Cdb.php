<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cdb extends CI_Controller {

  var $tables =   "tr_cdb";	
	var $folder =   "dealer";
	var $page		=		"cdb";
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
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}


	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
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
		$data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE no_spk NOT IN (SELECT no_spk FROM tr_cdb)  ORDER BY no_spk ASC");						
		$this->template($data);	
	}

	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,ms_kelurahan.id_kelurahan FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan=ms_kelurahan.id_kelurahan WHERE id_dealer='$id_dealer' AND no_spk NOT IN (SELECT no_spk FROM tr_cdb)
																							ORDER BY ms_kelurahan.kelurahan ASC");				
		$data['dt_agama'] 		= $this->m_admin->getSortCond("ms_agama","agama","ASC");												
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan","pendidikan","ASC");								
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
			$nama_konsumen 		= $isi->nama_konsumen;
			$tempat_lahir 		= $isi->tempat_lahir;
			$tgl_lahir 				= $isi->tgl_lahir;
			$jenis_wn 				= $isi->jenis_wn;
			$no_kk 						= $isi->no_kk;
			$npwp 						= $isi->npwp;
			$id_kelurahan 		= $isi->id_kelurahan;
			$id_kelurahan2 		= $isi->id_kelurahan2;
			$alamat 					= $isi->alamat;
			$alamat2 					= $isi->alamat2;
			$kodepos 					= $isi->kodepos;
			$denah_lokasi 		= $isi->denah_lokasi;
			$alamat_sama 			= $isi->alamat_sama;
			$status_rumah 		= $isi->status_rumah;
			$lama_tinggal 		= $isi->lama_tinggal;
			$pekerjaan 				= $isi->pekerjaan;
			$lama_kerja 			= $isi->lama_kerja;
			$jabatan 					= $isi->jabatan;
			$penghasilan= $isi->penghasilan;
			$pengeluaran_bulan= $isi->pengeluaran_bulan;
			$no_hp 					= $isi->no_hp;
			$no_hp_2 					= $isi->no_hp_2;
			$status_hp 			= $isi->status_hp;
			$status_hp_2 			= $isi->status_hp_2;
			$no_telp 					= $isi->no_telp;
			$email 						= $isi->email;
			$refferal_id 			= $isi->refferal_id;
			$robd_id 					= $isi->robd_id;
			$nama_ibu 				= $isi->nama_ibu;
			$tgl_ibu 					= $isi->tgl_ibu;
			$keterangan 			= $isi->keterangan;
			$no_ktp 					= $isi->no_ktp;
		}else{
			$no_ktp 					= "";
			$nama_konsumen 		= "";
			$tempat_lahir 		= "";
			$tgl_lahir 				= "";
			$jenis_wn 				= "";
			$no_kk 						= "";
			$npwp 						= "";
			$id_kelurahan 		= "";
			$id_kelurahan2 		= "";
			$alamat 					= "";
			$alamat2 					= "";
			$kodepos 					= "";
			$denah_lokasi 		= "";
			$alamat_sama 			= "";
			$status_rumah 		= "";
			$lama_tinggal 		= "";
			$pekerjaan 				= "";
			$lama_kerja 			= "";
			$jabatan 					= "";
			$penghasilan= "";
			$pengeluaran_bulan= "";
			$no_hp 					= "";
			$no_hp_2 					= "";
			$status_hp 			= "";
			$status_hp_2 			= "";
			$no_telp 					= "";
			$email 						= "";
			$refferal_id 			= "";
			$robd_id 					= "";
			$nama_ibu 				= "";
			$tgl_ibu 					= "";
			$keterangan 			= "";
		}  	
  	echo $no_spk."|".$nama_konsumen."|".$tempat_lahir."|".$tgl_lahir."|".$jenis_wn."|".$no_kk."|".$npwp."|".$id_kelurahan."|".$id_kelurahan2."|".$alamat."|".$alamat2."|".$kodepos."|".$denah_lokasi."|".$alamat_sama."|".$status_rumah."|".$lama_tinggal."|".$pekerjaan."|".$lama_kerja."|".$jabatan."|".$penghasilan."|".$pengeluaran_bulan."|".$no_hp."|".$no_hp_2."|".$status_hp."|".$status_hp_2."|".$no_telp."|".$email."|".$refferal_id."|".$robd_id."|".$nama_ibu."|".$tgl_ibu."|".$keterangan."|".$no_ktp;
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
			$data['id_dealer']				= $this->m_admin->cari_dealer();		
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;				
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/cdb/add'>";
			
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}	

	function cek_stok()
	{
		$dealer = $this->db->query("SELECT * FROM ms_dealer WHERE h1=1");
		echo '<table border=1>
			<tr>
			<td>Kode Dealer MD</>
			<td>Dealer</>
			<td>Unfill</>
			<td>Total Stok</>
		';
		foreach ($dealer->result() as $rs) {
			$stok = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_penerimaan_unit_dealer.id_dealer = '$rs->id_dealer' 
                AND tr_scan_barcode.status = '4'")->row();
			$unfil = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                        LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                        AND tr_do_po.id_dealer = '$rs->id_dealer'")->row();
			echo "
			<tr>
			<td>$rs->kode_dealer_md</td>
			<td>$rs->nama_dealer</td>
			<td>$unfil->jum</td>
			<td>$stok->jum</td>
			</tr>
			";
		}
		echo '</table>';
	}
}