<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guest_book extends CI_Controller {

    var $tables =   "tr_guest_book";	
		var $folder =   "dealer";
		var $page	=	"guest_book";
    var $pk     =   "id_guest_book";
    var $title  =   "Guest Book / Monitoring Prospek";

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

	public function getWarna(){
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna          = $this->input->post('id_warna');
		$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC
				";
		$dt_warna = $this->db->query($dq);		
		if ($dt_warna->num_rows() > 0) {
			echo "<option value=''>- choose -</option>";
			foreach ($dt_warna->result() as $res) {
				$selected = $res->id_warna==$id_warna?'selected':'';
				echo "<option value='$res->id_warna' $selected>$res->id_warna | $res->warna</option>";
			}
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$id_dealer = $this->m_admin->cari_dealer();

		if (isset($_GET['mode'])) {
			$mode = $_GET['mode'];
			$data['mode'] = 'history';
			if ($mode=='history') {
				$data['dt_guest_book'] = $this->db->query("SELECT *,tr_guest_book.id_guest_book as guest FROM tr_guest_book 						
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna
						WHERE tr_guest_book.id_dealer = '$id_dealer' AND (SELECT count(status_fu) FROM tr_guest_book_detail WHERE id_guest_book=tr_guest_book.id_guest_book AND (status_fu='Loss' OR status_fu='Closing' )) =1
						ORDER BY tr_guest_book.id_list_appointment ASC");
			}

		}else{
			$data['dt_guest_book'] = $this->db->query("SELECT * FROM tr_guest_book 						
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna
						WHERE tr_guest_book.id_dealer = '$id_dealer' AND (SELECT count(status_fu) FROM tr_guest_book_detail WHERE id_guest_book=tr_guest_book.id_guest_book AND (status_fu='Loss' OR status_fu='Closing') ) <1
						ORDER BY tr_guest_book.id_list_appointment ASC ");	
		}		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer","jenis_customer","ASC");										
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$data['dt_status'] = $this->m_admin->getSortCond("ms_status","status","ASC");								
		$data['dt_permohonan'] = $this->m_admin->getSortCond("tr_prospek","id_list_appointment","ASC");								
		$this->template($data);										
	}
	public function t_data(){
		$id 			= $this->input->post('id_guest_book');		
		$data['dt_data'] = $this->db->query("SELECT * FROM tr_guest_book_detail
										WHERE id_guest_book = '$id' ORDER BY id_guest_book_detail DESC");		 							
		$this->load->view('dealer/t_guest_book',$data);				
	}	
	public function save_data(){
		$id_guest_book						= $this->input->post('id_guest_book');
		$tgl_fu										= $this->input->post('tgl_fu');			
		$bulan 	= substr($tgl_fu, 3,2);
    $tahun 	= substr($tgl_fu, 6,4);
    $tgl 		= substr($tgl_fu, 0,2);
    $tanggal_fu = $tahun."-".$bulan."-".$tgl;

    $n_fu		= $this->input->post('next_fu');			
		$bul 		= substr($n_fu, 3,2);
    $tah 		= substr($n_fu, 6,4);
    $tg 		= substr($n_fu, 0,2);
    $next_fu = $tah."-".$bul."-".$tg;
            			
		$data['id_guest_book']		= $this->input->post('id_guest_book');			
		$data['hasil_fu']					= $this->input->post('hasil_fu');			
		$data['tgl_fu']						= $tgl_fu;
		$data['next_fu']					= $n_fu;
		$data['status_fu']				= $this->input->post('status_fu');							
		$cek2 = $this->m_admin->insert("tr_guest_book_detail",$data);											
		echo "nihil";
	}	
	public function delete_data(){
		$id = $this->input->post('id_guest_book_detail');		
		$this->db->query("DELETE FROM tr_guest_book_detail WHERE id_guest_book_detail = '$id'");			
		echo "nihil";
	}	
	public function take_idlist()
	{		
		$id	= $this->input->post('id_list_appointment');	
			$id_dealer = $this->m_admin->cari_dealer();

	//	$dt_list = $this->m_admin->getByID("tr_prospek","id_list_appointment",$id)->row();											
		$dt_list = $this->db->query("SELECT * FROM tr_prospek WHERE id_list_appointment = '$id' AND status_prospek <> 'Loss' AND id_dealer='$id_dealer' ")->row();									
		echo $dt_list->no_hp."|".$dt_list->nama_konsumen."|".$dt_list->alamat."|".$dt_list->id_tipe_kendaraan."|".$dt_list->id_warna;
	}	

	public function take_appointment()
	{		
		$id	= $this->input->post('id_sales');	
		$id_dealer = $this->m_admin->cari_dealer();	
		$dt_list = $this->db->query("SELECT * FROM tr_prospek WHERE id_karyawan_dealer = '$id' AND status_prospek <> 'Loss' AND id_dealer='$id_dealer'");		
		if ($dt_list->num_rows()>0) {
			echo "<option value=''>- choose -</option>";				
			foreach ($dt_list->result() as $lst) {
				echo "<option value='".$lst->id_list_appointment."'>".$lst->id_list_appointment."|".$lst->nama_konsumen."|".$lst->no_hp."</option>";
			}
		}	
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
			$id 			= $this->input->post('id_list_appointment');						
			$id_guest_book 										= $this->m_admin->cari_id('tr_guest_book','id_guest_book');						
			$data['id_guest_book'] 						= $id_guest_book;
			$data['id_list_appointment'] 			= $this->input->post('id_list_appointment');						
			$data['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');	
			$data['id_warna'] 								= $this->input->post('id_warna');	
			$data['alamat2'] 									= $this->input->post('alamat');	
			$data['deskripsi_warna'] 					= $this->input->post('deskripsi_warna');	
			$data['deskripsi_mkt'] 						= $this->input->post('deskripsi_mkt');	
			$data['rencana_bayar'] 						= $this->input->post('rencana_bayar');	
			$data['id_jenis_customer'] 				= $this->input->post('id_jenis_customer');			
			$da['id_guest_book']					= $id_guest_book;
			$da['status_fu']							= $this->input->post('status_fu');	
			$da['tgl_fu'] 								= $this->input->post('tgl_fu');	
			$da['hasil_fu'] 							= $this->input->post('hasil_fu');
			$da['next_fu'] 								= $this->input->post('next_fu');				

			$id_dealer = $this->m_admin->cari_dealer();
			$data['id_dealer']				 				= $id_dealer;
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$this->m_admin->insert($tabel,$data);

			$cek2 = $this->m_admin->insert("tr_guest_book_detail",$da);											

			$ds['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');	
			$ds['id_warna'] 								= $this->input->post('id_warna');	
			$ds['alamat'] 									= $this->input->post('alamat');	
			$this->m_admin->update("tr_prospek",$ds,"id_list_appointment",$id);


			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/guest_book/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function detail()
	{				
		$id 			= $this->input->get("id");
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$data['set']		= "detail";
		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer","jenis_customer","ASC");										
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$data['dt_status'] = $this->m_admin->getSortCond("ms_status","status","ASC");								
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_permohonan'] = $this->m_admin->getSortCond("tr_prospek","id_list_appointment","ASC");								
		$data['dt_guest_book'] = $this->db->query("SELECT *,tr_guest_book.* FROM tr_guest_book 
						INNER JOIN tr_prospek ON tr_guest_book.id_list_appointment=tr_prospek.id_list_appointment
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna
						WHERE tr_guest_book.id_guest_book = '$id' AND tr_prospek.id_dealer='$id_dealer'
						ORDER BY tr_prospek.id_list_appointment ASC");	
		$this->template($data);	
	}		
	public function edit()
	{		
		$id 			= $this->input->get("id");
		$id_dealer = $this->m_admin->cari_dealer();
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;															
		$data['set']		= "edit";
		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer","jenis_customer","ASC");										
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$data['dt_status'] = $this->m_admin->getSortCond("ms_status","status","ASC");								
		$data['dt_permohonan'] = $this->m_admin->getSortCond("tr_prospek","id_list_appointment","ASC");								
		$data['dt_guest_book'] = $this->db->query("SELECT * FROM tr_guest_book 
						INNER JOIN tr_prospek ON tr_guest_book.id_list_appointment=tr_prospek.id_list_appointment
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna
						WHERE tr_guest_book.id_guest_book = '$id'  AND tr_prospek.id_dealer='$id_dealer'
						ORDER BY tr_prospek.id_list_appointment ASC");	
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
			$id 															= $this->input->post('id_list_appointment');						
			$data['id_list_appointment'] 			= $this->input->post('id_list_appointment');						
			$data['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');	
			$data['id_warna'] 								= $this->input->post('id_warna');	
			$data['alamat2'] 									= $this->input->post('alamat');	
			$data['deskripsi_warna'] 					= $this->input->post('deskripsi_warna');	
			$data['deskripsi_mkt'] 						= $this->input->post('deskripsi_mkt');	
			$data['rencana_bayar'] 						= $this->input->post('rencana_bayar');	
			$data['id_jenis_customer'] 				= $this->input->post('id_jenis_customer');				
					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;	
			$this->m_admin->update($tabel,$data,$pk,$id);

			
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/guest_book'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}		
}