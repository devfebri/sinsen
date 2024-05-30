<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Panel extends CI_Controller {

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
	}
	protected function template($page, $data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$this->load->view('template/header',$data);			
			$this->load->view('template/aside');
			$this->load->view("$page");		
			$this->load->view('template/footer');
		}
	}
	public function index()
	{						
		$config_captcha = array(
	'img_path'  => './captcha/',
	'img_url'  => base_url().'captcha/',
	'img_width'  => '130',
	'img_height' => 30,
	'border' => 0, 
	'expiration' => 7200
   );
  
   // create captcha image
   $cap = create_captcha($config_captcha);
  
   // store image html code in a variable
   $data['img'] = $cap['image'];
  
   // store the captcha word in a session
   $this->session->set_userdata('mycaptcha', $cap['word']);
   
   $this->load->view('login', $data);
	}
	public function home()
	{		
		$jenis_user = $this->session->userdata('jenis_user');
		if($jenis_user == "Main Dealer" || $jenis_user == "Admin" || $jenis_user == "Super Admin"){
			$page						= "index";		
		}else{
			$page						= "index_dealer";		
		}
		$data['title']	= "Dashboard";			
		$data['isi']		= "home";									
		$data['judul']	= "Statistik Web";			
		$this->template($page, $data);	
	}
	public function profil()
	{		
		$page			= "profil";
		$tabel			= "ms_user";
		$data['isi']    = "profil";		
		$data['title']	= "Ubah Profil";			
		$data['judul']	= "Pastikan password anda update berkala demi keamanan sistem.";										
		$data['set']	= "set_update";
		$id = $this->session->userdata('id_user');		
		$ad = array("id_user"=>$id);
		$data['dt_profil'] = $this->m_admin->kondisi($tabel,$ad);							
		$this->template($page, $data);	
	}
	public function update_profil()
	{		
		$tabel				= "ms_user";
		$pk 					= "id_user";
		$id 					= $this->input->post('id');									
		$data['username']	= $this->input->post('username');
		$password			= $this->input->post('password');
		if($password<>''){
			$data['password'] = md5($password);	
		}												
		$config['upload_path'] 		= './assets/panel/images/user/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';

		$this->upload->initialize($config);
		if($this->upload->do_upload('avatar')){
			$data['avatar']=$this->upload->file_name;
			
			$one = $this->m_admin->getByID($tabel,$pk,$id)->row();			
			unlink("assets/panel/images/user/".$one->avatar); //Hapus Gambar
		}
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data berhasil diubah";
		$_SESSION['tipe'] 	= "success";
		$idk = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();		
		$sq = $this->m_admin->getByID("ms_karyawan_dealer","id_karyawan_dealer",$idk->id_karyawan_dealer)->row();
		$ses_loginadmin = array( 'username'  => $idk->username,									 
								 'group' => $idk->id_user_group,										 
								 'nama' => $sq->nama_lengkap,									 
								 'id_karyawan_dealer' => $sq->id_karyawan_dealer,									 
								 'id_user' => $id);
		$this->session->set_userdata($ses_loginadmin);	
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/profil'>";
	}
	public function login()
	{
		$username =	$this->input->post('username'); 
		$password = md5($this->input->post('password'));
		$kode = $this->input->post('kode');
		$mycaptcha 	= $this->session->userdata('mycaptcha');
		$tgl 				= gmdate("Y-m-d h:i:s", time()+60*60*7);		
   
	if($kode == $mycaptcha) {		
			$rs_login = $this->m_admin->login($username, $password);
			$cek_user = $this->m_admin->login_user($username);
			$rs_login_super = $this->m_admin->login_super($username, $password);
			if ($rs_login->num_rows() == 1 )
			{	
				$row = $rs_login->row();			
				$s = $this->m_admin->getByID("ms_karyawan_dealer","id_karyawan_dealer",$row->id_karyawan_dealer);
				$t = $this->m_admin->getByID("ms_karyawan","id_karyawan",$row->id_karyawan_dealer);
				$sr = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group=ms_user_group.id_user_group
						WHERE ms_user.id_user='$row->id_user'")->row();
				if($s->num_rows() > 0){
					$sq = $s->row();
					$nama_lengkap = $sq->nama_lengkap;
					$id_karyawan_dealer = $sq->id_karyawan_dealer;
				}elseif($t->num_rows() > 0){
					$sq = $t->row();
					$nama_lengkap = $sq->nama_lengkap;
					$id_karyawan_dealer = $sq->id_karyawan;
				}else{
					$nama_lengkap = "Admin";
					$id_karyawan_dealer = "0";
				}	

				$token = $this->m_admin->get_token(20);
				$ses_loginadmin = array( 'username'  => $row->username,									 
										 'group' => $row->id_user_group,										 
										 'session_id' => $token,
										 'nama' => $nama_lengkap,									 
										 'jenis_user' => $sr->jenis_user,									 
										 'id_karyawan_dealer' => $id_karyawan_dealer,									 
										 'id_user' => $row->id_user);
				$this->session->set_userdata($ses_loginadmin);			

				$dt['last_login_ip'] 		= $_SERVER['REMOTE_ADDR'];
				$dt['last_login_date'] 	= $tgl;
				$dt['session_id'] 			= $token;
				$dt['last_mac_address'] = exec('getmac');
				$dt['status'] 							= "online";
				$this->m_admin->update("ms_user",$dt,'id_user',$row->id_user);


								
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/home'>";
				//echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/redirect'>";
			}elseif($rs_login_super->num_rows() > 0){
				$row = $rs_login_super->row();
				$sr = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group=ms_user_group.id_user_group
						WHERE ms_user.id_user='$row->id_user'")->row();
				$token = $this->m_admin->get_token(20);
				$ses_loginadmin = array( 'username'  => $row->username,									 
										 'group' => $row->id_user_group,										 
										 'session_id' => $token,
										 'nama' => "Super Admin",									 
										 'jenis_user' => $sr->jenis_user,									 
										 'id_karyawan_dealer' => "0",									 
										 'id_user' => $row->id_user);
				$this->session->set_userdata($ses_loginadmin);			

				$dt['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
				$dt['last_login_date'] = $tgl;
				$dt['session_id'] 			= $token;
				$dt['last_mac_address'] = exec('getmac');
				$dt['status'] 							= "online";
				$this->m_admin->update("ms_user",$dt,'id_user',$row->id_user);


								
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/home'>";
			}elseif($cek_user->num_rows() > 0){
				$_SESSION['pesan'] 	= "Password anda salah!";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
			}else{
				$_SESSION['pesan'] 	= "User tidak terdaftar!";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
			}		
		}else{
			$_SESSION['pesan'] 	= "Captcha's Wrong!";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}
	}
	public function redirect(){
		$this->load->view('v_redirect');
	}
	public function redirects(){
		$this->load->view('v_redirects');
	}
	public function logout(){
		
		$id_user = $this->session->userdata('id_user');		
		if($id_user != ''){
			$sq = $this->m_admin->getByID("ms_user","id_user",$id_user)->row();		
			$tgl1 = $sq->last_login_date;
			$tgl2 = gmdate("Y-m-d h:i:s", time()+60*60*7);		
			$isi = $this->m_admin->cari_waktu($tgl1,$tgl2);

			$dt['last_login_duration'] = $isi['minutes'];
			$dt['status'] = "offline";
			$this->m_admin->update("ms_user",$dt,'id_user',$id_user);
		}
		session_destroy();
		session_unset();
		//echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/redirects'>";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
	}
	public function setting(){
		$page						= "setting";		
		$data['title']	= "Setting";			
		$data['isi']		= "setting";
		$data['setting'] = $this->m_admin->kondisi('tabel_setting',array("id_setting=1"));									
		$data['judul']	= "General";			
		$this->template($page, $data);
	}
	public function save_setting()
	{		
		$tabel				= "tabel_setting";
		$pk 					= "id_setting";
		$id 					= 1;									
		$data['lokasi_download']	= $this->input->post('lokasi_download');
		$data['lokasi_upload']		= $this->input->post('lokasi_upload');													
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data berhasil diubah";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/setting'>";
	}
	public function notification(){
		$page						= "notification";		
		$data['title']	= "Notification";			
		$data['isi']		= "notification";
		$data['setting'] = $this->m_admin->kondisi('tabel_setting',array("id_setting=1"));									
		$data['judul']	= "Notification";			
		$this->template($page, $data);
	}
}
