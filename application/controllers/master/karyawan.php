<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {

    var $tables =   "ms_karyawan";	
		var $folder =   "master";
		var $page		=		"karyawan";
    var $pk     =   "id_karyawan";
    var $title  =   "Master Data Karyawan MD";

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
			$this->load->view('template/footer',$data);
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan.*,ms_jabatan.jabatan,ms_divisi.divisi
																FROM ms_karyawan LEFT JOIN ms_divisi 
																ON ms_karyawan.id_divisi=ms_divisi.id_divisi LEFT JOIN ms_jabatan
																ON ms_karyawan.id_jabatan=ms_jabatan.id_jabatan LEFT JOIN ms_agama
																ON ms_karyawan.id_agama=ms_agama.id_agama LEFT JOIN ms_dealer
																ON ms_karyawan.id_dealer=ms_dealer.id_dealer ORDER BY id_karyawan,nama_lengkap ASC");							
		$this->template($data);	
	}

	public function export_csv()
	{
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		$nama_file = "rekap_karyawan_md-".date('dmY');
		$judul_file = "Rekap Data Karyawan Main Dealer PT. Sinar Sentosa Primatama";
		// Panggil class PHPExcel nya
	    $csv = new PHPExcel();
	    // Settingan awal fil excel
	    $csv->getProperties()->setCreator('PT Sinar Sentosa')
	                 ->setLastModifiedBy('PT Sinar Sentosa')
	                 ->setTitle("$nama_file")
	                 ->setSubject("$judul_file")
	                 ->setDescription("$judul_file")
	                 ->setKeywords("$judul_file");
	    // Buat header tabel nya pada baris ke 1
	    $csv->setActiveSheetIndex(0)->mergeCells('A1:R1');
	    $csv->setActiveSheetIndex(0)->setCellValue('A1', "$judul_file");
	    $csv->setActiveSheetIndex(0)->setCellValue('A2', "Tgl Generate :");
	    $csv->setActiveSheetIndex(0)->setCellValue('B2', date('d-m-Y')); 

	    $csv->setActiveSheetIndex(0)->setCellValue('A4', "No KTP"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('B4', "NPK"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('C4', "Nama Lengkap"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('D4', "Department"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('E4', "Nama Devisi"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('F4', "Jabatan"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('G4', "Tempat Lahir"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('H4', "Tanggal Lahir"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('I4', "Jenis Kelamin"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('J4', "Agama"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('K4', "No Telp"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('L4', "No Hp"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('M4', "Email"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('N4', "Alamat Tempat Tinggal"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('O4', "Tanggal Masuk Kerja"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('P4', "Tanggal Keluar"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('Q4', "Alasan Keluar"); 
	    $csv->setActiveSheetIndex(0)->setCellValue('R4', "Status"); 

	    $no = 1; // Untuk penomoran tabel, di awal set dengan 1
    	$numrow = 5;
	    foreach($this->db->get('ms_karyawan')->result() as $data){ 
	      $csv->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $data->no_ktp);
	      $csv->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data->npk);
	      $csv->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->nama_lengkap);
	      $csv->setActiveSheetIndex(0)->setCellValue('D'.$numrow, get_data('ms_department','id_department',$data->id_department,'department'));
	      $csv->setActiveSheetIndex(0)->setCellValue('E'.$numrow, get_data('ms_divisi','id_divisi',$data->id_divisi,'divisi'));
	      $csv->setActiveSheetIndex(0)->setCellValue('F'.$numrow, get_data('ms_jabatan','id_jabatan',$data->id_jabatan,'jabatan'));
	      $csv->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data->tempat_lahir);
	      $csv->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data->tgl_lahir);
	      $csv->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data->jk);
	      $csv->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $data->id_agama);
	      $csv->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $data->no_telp);
	      $csv->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $data->hp_gsm);
	      $csv->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $data->email);
	      $csv->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $data->alamat);
	      $csv->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $data->tgl_masuk);
	      $csv->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $data->tgl_keluar);
	      $csv->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $data->alasan_keluar);
	      $csv->setActiveSheetIndex(0)->setCellValue('R'.$numrow, ($data->active == '1') ? 'Aktif' : 'Tidak Aktif');
	      
	      $no++; // Tambah 1 setiap kali looping
	      $numrow++; // Tambah 1 setiap kali looping
	    }
	    // Set orientasi kertas jadi LANDSCAPE
	    $csv->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	    // Set judul file excel nya
	    $csv->getActiveSheet(0)->setTitle("Rekap Data Karyawan Main Dealer");
	    $csv->setActiveSheetIndex(0);
	    // Proses file excel
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'.$nama_file.'.csv"'); // Set nama file excel nya
	    header('Cache-Control: max-age=0');
	    $write = new PHPExcel_Writer_CSV($csv);
	    $write->save('php://output');
	  

	}

	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$data['dt_divisi'] = $this->m_admin->getSortCond("ms_divisi","id_divisi","ASC");	
		$data['dt_jabatan'] = $this->m_admin->getSortCond("ms_jabatan","id_jabatan","ASC");	
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama","id_agama","ASC");			
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$data['dt_department'] = $this->m_admin->getSortCond("ms_department","department","ASC");			
		$data['dt_sub_department'] = $this->m_admin->getSortCond("ms_sub_department","sub_department","ASC");			
		$data['set']		= "insert";									
		$this->template($data);	
	}
	public function cari_id(){
		$po							= $this->input->post('po');		
		$pr_num 				= $this->db->query("SELECT * FROM ms_karyawan ORDER BY id_karyawan DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();										
			$kode = $row->id_karyawan + 1;
		}else{
			$kode = "1";
		} 	
		echo $kode;
	}
	public function cari_id_real(){
		$po							= $this->input->post('po');		
		$pr_num 				= $this->db->query("SELECT * FROM ms_karyawan ORDER BY id_karyawan DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();										
			$kode = $row->id_karyawan + 1;
		}else{
			$kode = "1";
		} 	
		return $kode;
	}
	public function get_dep(){
		$id_divisi			= $this->input->post('id_divisi');				
		$dt_department  = $this->m_admin->getByID("ms_department","id_divisi",$id_divisi);	
		
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_department->result() as $row) {
			$data .= "<option value='$row->id_department'>$row->department</option>\n";
		}
		echo $data;
	}
	public function get_sub_dep(){
		$id_department			= $this->input->post('id_department');				
		$dt_sub_department  = $this->m_admin->getByID("ms_sub_department","id_department",$id_department);	
		
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_sub_department->result() as $row) {
			$data .= "<option value='$row->id_sub_department'>$row->sub_department</option>\n";
		}
		echo $data;
	}	
	public function get_karyawan_group(){
		$id_karyawan_group		= $this->input->post('id_karyawan_group');	
		$dt_karyawan_level		= $this->m_admin->getByID("ms_karyawan_level","id_karyawan_group",$id_karyawan_group);								
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_karyawan_level->result() as $row) {
			$data .= "<option value='$row->id_karyawan_level'>$row->karyawan_level</option>\n";
		}
		echo $data;
	}
	public function t_jabatan(){
		$id = $this->input->post('id_karyawan');
		$dq = "SELECT ms_karyawan_detail.*,ms_jabatan.jabatan,ms_dealer.nama_dealer FROM ms_karyawan_detail 
						INNER JOIN ms_jabatan ON ms_karyawan_detail.id_jabatan=ms_jabatan.id_jabatan
						INNER JOIN ms_dealer ON ms_karyawan_detail.id_dealer = ms_dealer.id_dealer
						WHERE ms_karyawan_detail.id_karyawan = '$id'";
		$data['dt_jabatan'] = $this->db->query($dq);
		$this->load->view('master/t_jabatan',$data);
	}
	public function delete_jabatan(){
		$id 		= $this->input->post('id_karyawan_detail');		
		$da 		= "DELETE FROM ms_karyawan_detail WHERE id_karyawan_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}
	public function save_jabatan(){
		$id_karyawan				= $this->input->post('id_karyawan');
		$id_jabatan					= $this->input->post('id_jabatan_r');		
		$id_dealer					= $this->input->post('id_dealer');		
		$c 			= $this->db->query("SELECT * FROM ms_karyawan_detail WHERE id_karyawan ='$id_karyawan' AND id_jabatan = '$id_jabatan' AND id_dealer = '$id_dealer'");
		if($c->num_rows()==0){
			$data['id_karyawan']		= $this->input->post('id_karyawan');			
			$data['id_jabatan']			= $this->input->post('id_jabatan');
			$data['id_dealer']			= $this->input->post('id_dealer');
			$data['tgl_aktif']			= $this->input->post('tgl_aktif');
			$data['tgl_nonaktif']		= $this->input->post('tgl_nonaktif');
			$data['status']					= $this->input->post('status');
			

			$this->m_admin->insert('ms_karyawan_detail',$data);							
			echo "nihil";
		}else{
			echo "nothing";
		}
	}

	public function save()
	{		
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id					= $this->session->userdata('id_user');		

		$config['upload_path'] 		= './assets/panel/images/karyawan/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '300';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';
						
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){

			$this->upload->initialize($config);
			if(!$this->upload->do_upload('foto_karyawan')){
				$foto_karyawan	= "";
			}else{
				$foto_karyawan	= $this->upload->file_name;
			}

			$data['id_karyawan']		= $this->input->post('id_karyawan');		
			$data['id_dealer']			= $this->input->post('id_dealer');		
			$data['no_ktp'] 				= $this->input->post('no_ktp');		
			$data['npk'] 						= $this->input->post('npk');		
			$data['nama_lengkap'] 	= $this->input->post('nama_lengkap');		
			$data['id_divisi'] 			= $this->input->post('id_divisi');		
			$data['id_department'] 	= $this->input->post('id_department');		
			$data['id_sub_department'] 	= $this->input->post('id_sub_department');					
			$data['tempat_lahir'] 	= $this->input->post('tempat_lahir');				
			$data['tgl_lahir'] 			= $this->input->post('tgl_lahir');				
			$data['jk']							= $this->input->post('jk');
			
			$data['id_jabatan'] 		= $this->input->post('id_jabatan_r');		
			$data['id_agama']				= $this->input->post('id_agama');				
			
			$data['email']					= $this->input->post('email');				
			$data['no_telp']				= $this->input->post('no_telp');				
			$data['hp_gsm']					= $this->input->post('hp_gsm');				
			$data['alamat']					= $this->input->post('alamat');				
			$data['tgl_masuk']			= $this->input->post('tgl_masuk');				
			$data['tgl_keluar']			= $this->input->post('tgl_keluar');				
			$data['alasan_keluar']	= $this->input->post('alasan_keluar');				
			if($this->input->post('active') == '1'){
				$data['active']				= $this->input->post('active');		
			}else{
				$data['active'] 			= "";
			}
			$data['foto_karyawan']	= $foto_karyawan;
			$data['created_at']			= $waktu;
			$data['created_by']			= $login_id;				
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');		
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									

			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{
				$this->m_admin->delete("ms_karyawan_detail",$pk,$id);	
				//$this->m_admin->delete("ms_user",$pk,$id);					
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_karyawan'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_divisi'] = $this->m_admin->getSortCond("ms_divisi","id_divisi","ASC");	
		$data['dt_jabatan2'] = $this->m_admin->getSortCond("ms_jabatan","id_jabatan","ASC");				
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama","id_agama","ASC");
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;		
		$data['set']			= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id');
		$waktu 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			= $this->session->userdata('id_user');		

		$config['upload_path'] 		= './assets/panel/images/karyawan/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '300';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';		

		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){

			$this->upload->initialize($config);
			if($this->upload->do_upload('foto_karyawan')){
				$data['foto_karyawan']=$this->upload->file_name;
				
				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();			
				unlink("assets/panel/images/karyawan/".$one->avatar); //Hapus Gambar
			}
			$data['id_dealer']			= "5";
			$data['no_ktp'] 				= $this->input->post('no_ktp');		
			$data['npk'] 						= $this->input->post('npk');		
			$data['nama_lengkap'] 	= $this->input->post('nama_lengkap');		
			$data['id_divisi'] 			= $this->input->post('id_divisi');		
			$data['id_jabatan'] 		= $this->input->post('id_jabatan_r');		
			$data['id_department'] 	= $this->input->post('id_department');		
			$data['id_sub_department'] 	= $this->input->post('id_sub_department');			
			$data['tempat_lahir'] 	= $this->input->post('tempat_lahir');				
			$data['tgl_lahir'] 			= $this->input->post('tgl_lahir');				
			$data['jk']							= $this->input->post('jk');
			$data['id_agama']				= $this->input->post('id_agama');				
			$data['email']					= $this->input->post('email');				
			$data['no_telp']				= $this->input->post('no_telp');				
			$data['hp_gsm']					= $this->input->post('hp_gsm');				
			$data['alamat']					= $this->input->post('alamat');				
			$data['tgl_masuk']			= $this->input->post('tgl_masuk');				
			$data['tgl_keluar']			= $this->input->post('tgl_keluar');				
			$data['alasan_keluar']	= $this->input->post('alasan_keluar');
			$data['updated_at']			= $waktu;
			$data['updated_by']			= $login_id;

			if($this->input->post('active') == '1'){
				$data['active']	= $this->input->post('active');		
			}else{
				$data['active'] 			= "";
			}

			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function view()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$page				= $this->page;
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);			
		$data['dt_divisi'] = $this->m_admin->getSortCond("ms_divisi","id_divisi","ASC");	
		$data['dt_jabatan2'] = $this->m_admin->getSortCond("ms_jabatan","id_jabatan","ASC");	
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama","id_agama","ASC");
		$data['dt_karyawan'] = $this->m_admin->getByID($tabel,$pk,$id);
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['set']		= "detail";									
		$this->template($data);
		
	}
}