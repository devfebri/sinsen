<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panel extends CI_Controller
{
	var $folder = "";
	var $page   = "dashboard_kpb";
	var $title  = "Dashboard KPB";


	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url', 'string');
		// $this->load->helper('session');		
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_d_stok');
		//$this->load->model('m_dashboard_stok');				
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->helper('tgl_indo');
	}
	protected function template($page, $data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view("$page");
			$this->load->view('template/footer');
		}
	}

	
	public function index()
	{
		$config_captcha = array(
			'img_path'  => './captcha/',
			'img_url'  => base_url() . 'captcha/',
			'img_width'  => '130',
			'img_height' => 30,
			'border' => 0,
			'pool'   => '1234567890',
			'expiration' => 7200,
			'word_length' => 4 
		);

		// create captcha image
		$cap = create_captcha($config_captcha);

		// store image html code in a variable
		$data['captchaImg'] = $cap['image'];
		//$data['captchaImg'] = "as";

		// store the captcha word in a session
		$this->session->set_userdata('mycaptcha', $cap['word']);

		$this->load->view('login', $data);
	}

	public function refresh()
	{
		// Captcha configuration
		$config = array(
			'img_path'  => './captcha/',
			'img_url'  => base_url() . 'captcha/',
			'img_width'  => '130',
			'img_height' => 30,
			'border' => 0,
			'pool'   => '1234567890',
			'expiration' => 7200,
			'word_length' => 4 

		);
		$captcha = create_captcha($config);
		// Unset previous captcha and set new captcha word
		$this->session->unset_userdata('mycaptcha');
		$this->session->set_userdata('mycaptcha', $captcha['word']);
		// Display captcha image
		echo $captcha['image'];
	}

	public function index_new()
	{
		$config_captcha = array(
			'img_path'  => './captcha/',
			'img_url'  => base_url() . 'captcha/',
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

		$this->load->view('login_new', $data);
	}
	public function home()
	{
		$jenis_user = $this->session->userdata('jenis_user');
		if ($jenis_user == "Main Dealer" || $jenis_user == "Admin" || $jenis_user == "Super Admin") {
			$page						= "index_md";
			// $page						= "index_md_kosong";		
		} else {
			$page						= "index_dealer_new";
			// $page						= "index_dealer";		
		}
		$data['title']	= "Dashboard";
		$data['isi']		= "home";
		$data['judul']	= "Statistik Web";
		$this->template($page, $data);
	}

	public function dashboard_power_bi()
	{
		$data['folder'] = $this->folder;
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index";
		$page			= "dashboard_kpb";

		$id_dealer = $this->m_admin->cari_dealer();

		$jenis_user = $this->session->userdata('jenis_user');

		if ($jenis_user == "Main Dealer" || $jenis_user == "Admin" || $jenis_user == "Super Admin") {
			$id_dealer=000;
		}
		
		switch ($id_dealer) {
			case 44:
				$href ='https://app.powerbi.com/view?r=eyJrIjoiYzZhMTFjNzYtOTA3ZS00MWZjLTk1NGYtZmY3YjJmNDgyNzFiIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 84:
				$href ='https://app.powerbi.com/view?r=eyJrIjoiNDZlZTA2ZTUtNDU2YS00NDliLTg4MGUtMGY5YjQxMzAwM2M0IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 103:
				 $href = 'https://app.powerbi.com/view?r=eyJrIjoiMWMzZTM4NzEtMDBhOC00NWYzLWI0ZjctMWU5YTUwOWYxYzZmIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 105:
				$href ='https://app.powerbi.com/view?r=eyJrIjoiNGVjMTIyODktYjZmNi00MTVhLTlmODAtN2FmMWFkODliYTM5IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 82:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMzFjYjMzOGItOWU5Yi00N2Y5LThmMWQtZTBiM2QxMjc4ZWNhIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 97:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMTIzNmM4ZjAtM2Q5NC00Y2ExLTg2OGEtYjNhOTI4YzhlNGNkIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 106:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZmFhNDlkZWItMTRiMC00OTY1LWE5NTgtNWQyNjUxNzdjNTZjIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 39:
				$href='https://app.powerbi.com/view?r=eyJrIjoiNGJmZDUzNjktYzczZC00MDE3LWI5MGUtYjkxNWFmZmFjZDNjIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 104:
				$href='https://app.powerbi.com/view?r=eyJrIjoiOTBhYzAxN2MtNzQxYi00M2NhLTg0YjEtZmQ4NGRiZGFiMmQ1IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 102:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZmFhNDlkZWItMTRiMC00OTY1LWE5NTgtNWQyNjUxNzdjNTZjIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 13:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYWY3YWMzMDItZTlmMC00MTQ2LThmZmItNGMyNzcxZTYzNDg5IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 2:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZmM4MGY2OTEtMWM5OC00ZjE5LTllMzUtZmU5NjgyYTliZWQ5IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 51:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYWRmY2NmNjEtNTllZi00YTAxLWIyZDEtZmM1NjBhMzdiNjc4IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 43:
				$href='https://app.powerbi.com/view?r=eyJrIjoiNDg3NWZkY2UtNWZkMi00NzYzLWI5MDktOTg3YTYyNGZhZDVkIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 25:
				$href='https://app.powerbi.com/view?r=eyJrIjoiOWEwZWY3YmEtZmE1Zi00ZjA2LWIxMzgtZDdlYzlkYmU4ZTRiIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 43:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZmM0ZTMzOTQtYTU0MC00ZGJjLWIxYmYtNWI0YWQzYmE0ZGFmIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 25:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYWE1ZmNiYzQtOTM0Zi00NzE3LWJmYmItY2I5ZWEzNTY3MmUyIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 40:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYmM0ZjQ0OTQtNTc2Yy00OTkwLWJjNTQtMmJlYjgwNzFjNTRjIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 101:
				$href='https://app.powerbi.com/view?r=eyJrIjoiY2VkYWI4MjMtNTdmZS00ZGEyLWJhNGYtY2YzYzdlNjczMzAwIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 46:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMTIzYjRjM2YtZjU1Yi00ZTVhLWJkYTgtMzgxNTU4Njk2MWU4IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 85:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYmI5YTI5MTMtMDk1NC00NGYzLWJiNGYtYjhkNWM1NWVjZjVhIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 18:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMGRiMjEwZTEtYzY3ZS00YzFjLTgwYjYtMjBiMTEwYWU2NWFmIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 94:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZjI5N2RiYTAtOTE0YS00MzUyLWFlZmItNDk1NjY1MDVhMTE4IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 78:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZThhMWYxZTktYTY1ZC00MWZkLWJmNTYtNjMwOWIwOGMxMjAxIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 65:
				$href='https://app.powerbi.com/view?r=eyJrIjoiODhhMDQ2YTctYTE2ZS00OGIxLWI2NGItYmQxYjBlM2Q1ZjlhIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 80:
				$href='https://app.powerbi.com/view?r=eyJrIjoiOTkwNGRlOGMtNWNiMC00ZWUzLTg4N2ItY2ZmMDU2MTgyNmNlIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 47:
				$href='https://app.powerbi.com/view?r=eyJrIjoiNzlhYTQ1MGQtODc2OS00OGM0LThmYTgtMmE0NGYzYTRkMzM4IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 90:
				$href='https://app.powerbi.com/view?r=eyJrIjoiODdjYjQ5MGMtZTQ4Ny00MjUxLTkwZWUtNDE0YzIxZTNhNTVlIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 96:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYTk3MDRlN2ItNTI4NS00MzdmLWFkNzAtNDNhZTg2YzczZWUwIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 77:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZmY4Y2FhNGYtYTVmNC00YjkyLTg4ZWItY2M1ODQyYzFjMWQyIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 1:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYzZlOTY2YjEtOTNhNS00ZWRhLWE4ZTMtZjQ4OTMzNjZkOGUzIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 4:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYWRhMWRkNWUtM2E4Ni00ZTQzLWFlMDYtOTc4NzNiZTAyYmQ5IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 8:
				$href='https://app.powerbi.com/view?r=eyJrIjoiY2MyMWE1N2QtOTcyOS00ZGM0LWE0MjItMzYxNmY5NTcwM2UxIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 41:
				$href='https://app.powerbi.com/view?r=eyJrIjoiNDBmMDI2YWItNTVlYi00YzUzLWEwODMtMDc4ZGZhZTQ0MGQ2IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 70:
				$href='https://app.powerbi.com/view?r=eyJrIjoiOWQ2ZTYxYWMtNGNmNC00OTM3LThhOWYtNjc5NzI0MmMwZmZlIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 98:
				$href='https://app.powerbi.com/view?r=eyJrIjoiZmJhYzdmYTQtM2JmMi00ZTEzLWE0MzktMTRiYTc4MzYwYTg3IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 74:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMWYwNWZhZTUtNWRmMy00ZDI5LTk5NzktMDM0YTU1M2I5YjQ1IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 71:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMTlmZTE0Y2YtMjhhYi00ZjA4LWEyMmQtNTg3OGMyNGZiOTM3IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 81:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMDQ5YjJkOWUtNjAyZi00M2RjLThiYzctMzVjYzJkOTYxYWE0IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 83:
				$href='https://app.powerbi.com/view?r=eyJrIjoiMDZiMzU5ZDctYmFiMS00MWUyLThhMWQtYzM4ODE4MzdkOTQzIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 86:
				$href='https://app.powerbi.com/view?r=eyJrIjoiNGExM2E2NDktOWJlNi00ZjY4LTlmNTYtYzBjMTU3OTgwOWMwIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 107:
				$href='https://app.powerbi.com/view?r=eyJrIjoiYzk0NzAxMDUtMGFkMy00YmZkLTg0M2MtZTM3ZDcwZjNiMjA0IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			case 000:
				$href='https://app.powerbi.com/view?r=eyJrIjoiODUyMzAyODgtZDllMi00NmMzLTk1MjctNTBmNDhhMjgwYTIyIiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D';
				break;
			default:
				echo "Number not found id daeler";
				break;
		}

	
		$data['href'] =$href;

		$this->template($page, $data);



	}

	public function welcome()
	{
		$page	= "blank_page";
		$data['title']	= "Welcome";
		$data['isi']		= "welcome";
		$data['judul']	= "Statistik Web";
		$jenis_user = $this->session->userdata('jenis_user');
		$jenis_user_bagian = $this->session->userdata('jenis_user_bagian');
		
		if($jenis_user =="Admin" or $jenis_user == "Super Admin" or $jenis_user == "Main Dealer"){
			$role = "1"; // MD
		}else if ($jenis_user == "Dealer"){
			$role = "2"; // Dealer
			if($jenis_user_bagian !='h23' && $jenis_user_bagian !='H23'){
				$id_dealer = $this->m_admin->cari_dealer();
				/*
				$data['is_aging_indent_dealer'] = $this->db->query("
				select datediff(now(), created_at) as aging, id_dealer 
				from tr_po_dealer_indent tpdi 
				where status not in ('completed','canceled') and id_reasons is NULL and id_dealer = $id_dealer
				having aging > 120
				order by created_at asc 
				")->num_rows();
				*/
				
				$data['is_aging_indent_dealer'] = $this->db->query("
				SELECT
					id_indent
					FROM
						(
						SELECT
							b.tgl_spk,
							b.no_spk,
							d.kode_dealer_md,
							d.nama_dealer,
							a.po_dari_finco,
							tr_po_dealer_indent.updated_at,
							CASE WHEN tr_po_dealer_indent.status ='requested' THEN 'Open' ELSE 'Close' END as status,
							datediff(current_date(), b.tgl_spk) as selisih_hari,
							b.jenis_beli,
							b.id_dealer,
							tr_po_dealer_indent.send_ahm,
							tr_po_dealer_indent.id_indent
						FROM
							tr_spk AS b
							LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
							LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
							INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
							INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
							INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
							INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
						WHERE
							tr_po_dealer_indent.STATUS = 'requested'
							AND ( tr_po_dealer_indent.id_reasons IS NULL OR tr_po_dealer_indent.id_reasons = '' ) 
							AND x.id_kwitansi IS NOT NULL 
							AND b.status_spk in ('approved','booking')
							AND b.tanda_jadi > 0 
						ORDER BY
							b.tgl_spk DESC 
						) AS table1 
					WHERE
						( ( table1.jenis_beli = 'Kredit' AND table1.po_dari_finco != '' ) 
						OR table1.jenis_beli = 'Cash' )
						and selisih_hari > 60 and id_dealer = '$id_dealer'
				")->num_rows();
			}
		}
		$data['announcement'] = $this->m_admin->get_announcement($role);

		$this->template($page, $data);
	}

	public function home_h23()
	{
		$jenis_user = $this->session->userdata('jenis_user');
		if ($jenis_user == "Main Dealer" || $jenis_user == "Admin" || $jenis_user == "Super Admin") {
			$page						= "index_h23_md";
			// $page						= "index_md_kosong";		
		} else {
			$page						= "index_dealer_new2";
			// $page						= "index_dealer";		
		}
		$data['title']	= "Dashboard";
		$data['isi']		= "home";
		$data['judul']	= "Statistik Web";
		$this->template($page, $data);
	}
	public function dummy()
	{
		$page						= "index_dealer_new";
		$data['title']	= "Dashboard";
		$data['isi']		= "home";
		$data['judul']	= "Statistik Web";
		$this->template($page, $data);
	}

	public function coba()
	{
		$testing = $this->db->query("select * from tr_spk ts order by no_spk_int DESC limit 1 ")->row();
		var_dump($testing );
		die();
	}

	public function tes_home()
	{
		$jenis_user = $this->session->userdata('jenis_user');
		if ($jenis_user == "Main Dealer" || $jenis_user == "Admin" || $jenis_user == "Super Admin") {
			$page						= "index_md_tes";
		} else {
			$page						= "index_dealer";
		}
		$data['title']	= "Dashboard";
		$data['isi']		= "home";
		$data['judul']	= "Statistik Web";
		$this->template($page, $data);
	}

	public function update_pass()
	{
		if ($_POST) {
			$password = $this->input->post('password');
			$this->db->where('username', $this->session->userdata('username'));
			$update = $this->db->update('ms_user', array(
				'password' => md5($password),
				'admin_password' => $password,
				'update_date' => get_waktu()
			));

			if ($update) {
				redirect('panel/logout', 'refresh');
			}
		}
	}

	public function cek_ganti_password()
	{
		$this->db->where('username', $this->session->userdata('username'));
		$cek_user = $this->db->get('ms_user')->row();
		$old_pass = $cek_user->admin_password;

		$is_update_password = 0;
		$tahun = new DateTime($cek_user->update_date);
		$get_tahun = $tahun->format("Y");

		if ($cek_user->update_date == '' || $get_tahun != date('Y')) {
			$is_update_password = 1;
		}else{
			$tgl2 = new DateTime($cek_user->update_date);
			$tgl1 = new DateTime(date('Y-m-d'));
			$jarak = $tgl2->diff($tgl1)->m;

			if($jarak >= 3){
				$is_update_password = 1;
			}
		}

		// setting utk memunculkan modal pop up pemberitahuan
		$tgl_end ='2024-01-01'; 
		$tgl_end =''; 
		$tgl_nonaktif = '';
		
		$psn = "
		<p>Kepada Yth,
			<br>Semua Pengguna Aplikasi SEEDS (MD &amp; Dealer/AHASS)
			<br>
			<br>Salam Satu HATI,
			<br>
			<br>Melalui ini, Kami ingin menyampaikan bahwa akan dilakukan maintenance pembaharuan password yang dilaksanakan pada setiap 3 bulan sekali. Oleh sebab itu, pada kesempatan ini juga, kami meminta pengguna Aplikasi SEEDS dapat melakukan proses pembaharuan password.
			<br>
			<br>Untuk melakukan proses pembaharuan password, Pengguna Aplikasi SEEDS bisa mengakses menu profile yang berada di sebelah kanan atas layar (Sebelah Tombol Sign Out).
			<br>
			<br>Jika pengguna tidak melakukan proses pembaharuan password, maka mulai tanggal <b>". tgl_indo($tgl_end) ."</b> sistem akan &#39;memaksa&#39; pengguna untuk melakukan proses update password.
			<br>
			<br>Catatan:
			<br>- Setelah melakukan pergantian password, kami meminta untuk tidak membagikan informasi apapun kepada pihak yang tidak berkepentingan / tidak bertanggung jawab.
			<br>- Jika ada terjadi kendala selama melakukan proses update password bisa menghubungi Tim IT.
			<br>
			<br>Demikian informasi yang dapat kami sampaikan, Atas perhatian dan kerjasamanya kami ucapkan banyak terima kasih.
			<br>
			<br>Hormat Kami,
			<br>
			<br>
			<br>IT Department
			<br>
		</p>
		";

		$form = '
		<form id="sbt_change_pw" action="' . base_url() . 'panel/update_pass" method="POST">
			<div class="form-group">
				<label for="old-inputPassword3" class="col-sm-3 control-label">Old Password</label>
				<div class="col-sm-5">
					<input type="password" class="form-control" id="old-inputPassword3" placeholder="Silahkan isi password lama" name="old-password" onkeyup="handleKeyUp()">
				</div>
				<div class="col-sm-4">
					<a class="btn btn-success btn-flat" type="button" onclick="showing(0);" id="btnShow0"><i class="fa fa-eye"></i></a>
				</div>
			</div><br><br>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">New Password</label>
				<div class="col-sm-5">
					<input type="password" class="form-control" id="inputPassword3" placeholder="Silahkan isi password baru" name="password" onkeyup="handleKeyUp()">
				</div>
				<div class="col-sm-4">
					<a class="btn btn-success btn-flat" type="button" onclick="showing(1);" id="btnShow1"><i class="fa fa-eye"></i></a>
					<a class="btn btn-info btn-flat" type="button" onclick="generate(8);"><i class="fa fa-refresh"></i> </a>
					<a class="btn btn-default" type="button" onclick="CopyToClipboard()"><i class="fa fa-copy"></i></a>
				</div>
			</div><br><br>
			<div class="form-group">
				<label for="re-inputPassword3" class="col-sm-3 control-label">Confirm New Password</label>
				<div class="col-sm-5">
					<input type="password" class="form-control" id="re-inputPassword3" placeholder="Silahkan isi kembali password baru" name="re-password" onkeyup="handleKeyUp()">
				</div>
			</div>   
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-2 control-label"></label>
				<div class="col-sm-12">
					<p id="textValidation" style="color:#DD4B39;font-weight:bold"></p>
				</div>
			</div>
			<div class="form-group">
				<br><br>
				<center>
					<button type="submit" class="btn btn-primary" id="myBtn">Update</button>
					<a href="' . base_url() . 'panel/logout" class="btn btn-warning">Log Out</a>
				</center>
			</div> 
        </form>
     
        <script>
			function CopyToClipboard() {
				// Get the text field
				var copyText = document.getElementById("inputPassword3");
			  
				// Select the text field
				copyText.select();
				copyText.setSelectionRange(0, 99999); // For mobile devices
			  
				// Copy the text inside the text field
				navigator.clipboard.writeText(copyText.value);
				
				// Alert the copied text
				alert("Teks berhasil disalin");
			}

		    function generate(Length){
				var result           = \'\';
				var besar            = \'ABCDEFGHJKLMNPQRSTUVWXYZ\';
				var kecil            = \'abcdefghijkmnpqrstuvwxyz\';
				var angka            = \'0123456789\';
				var simbol           = "^,?!&@*+#";
				var besarLength = besar.length;
				var kecilLength = kecil.length;
				var angkaLength = angka.length;
				var simbolLength = besar.length;

				for ( var i = 0; i < 2; i++ ) {
					result += besar.charAt(Math.floor(Math.random() * besarLength));
				}
				for ( var i = 0; i < 2; i++ ) {
					result += kecil.charAt(Math.floor(Math.random() * kecilLength));
				}
				for ( var i = 0; i <= 1; i++ ) {
					result += simbol.charAt(Math.floor(Math.random() * simbolLength));
				}
				for ( var i = 0; i < 2; i++ ) {
					result += angka.charAt(Math.floor(Math.random() * angkaLength));
				}
				for ( var i = 0; i < 2; i++ ) {
					result += kecil.charAt(Math.floor(Math.random() * kecilLength));
				}
				for ( var i = 0; i < 1; i++ ) {
					result += simbol.charAt(Math.floor(Math.random() * simbolLength));
				}
				
				$("#inputPassword3").val((result)); 
		        //  return result;
		    }

			$("#myBtn").on("click",function(e){ 
				e.preventDefault(); 
				var new_pw = $("#inputPassword3").val();
				var old_pw = $("#old-inputPassword3").val();
				var con_pw = $("#re-inputPassword3").val();

				if(new_pw == "" || old_pw =="" || con_pw ==""){
					alert("Silahkan lengkapi data yang diminta!");
				}else{
					if(old_pw != "'.$old_pass.'"){
						alert("Password lama salah, Silahkan cek kembali!");
					}else if(new_pw != con_pw){
						alert("Password baru tidak sama, Silahkan cek kembali!");
					}else{
						$("#sbt_change_pw").submit();
						return false;
					}
				}
			});
		   
		    function generated(){
				document.getElementById("inputPassword3").value=generate(10);
				valid();
		    }
		    
		    function valid(){
		        var passLength = document.getElementById("inputPassword3").value;
		        var textValidation = document.getElementById("textValidation");
		        
		        if(document.getElementById(\'inputPassword3\').value.length < 8 ){
		            document.getElementById("myBtn").disabled = true; 
		            toastr.error("Not Complete.... <br>Silahkan klik tombol <b>Generate</b> kembali");
		        }else if(document.getElementById(\'inputPassword3\').value.length >= 8 ){
					document.getElementById("myBtn").disabled = false; 
					toastr.success("Complete.... <br>Password kamu memenuhi kriteria.");
		        }else if(document.getElementById(\'inputPassword3\').value != document.getElementById(\'re-inputPassword3\').value){
					toastr.success("Complete.... <br>Password tidak cocok.");
				}
		    }
		    
		    function resetValidasi(){
				document.getElementById("inputPassword3").value=\'\';
				validationText ="";
				textValidation.style.color = "#DD4B39";
				textValidation.innerHTML  = validationText;
		    }
		    
		    function showing(id){
				if(id == 1){
					var x = document.getElementById("inputPassword3");
					var z = document.getElementById("btnShow1");
				}else{
					var x = document.getElementById("old-inputPassword3");
					var z = document.getElementById("btnShow0");
				}
				var caption ="";
				if (x.type === "password") {
					x.type = "text";
					z.style.backgroundColor="#222B34";
					caption ="<i class=\'fa fa-eye-slash\'></i>";
					z.innerHTML = caption;
				} else {
					x.type = "password";
					z.style.backgroundColor="#008D4C";
					caption ="<i class=\'fa fa-eye\'></i>";
					z.innerHTML = caption;
				}
		    }
		    
		    function handleKeyUp(){
				var validationText="";
				var myInput = document.getElementById("inputPassword3");
				var textValidation = document.getElementById("textValidation");
				var isValid=false;
				
				// Validate lowercase letters
				var lowerCaseLetters = /[a-z]/g;
				if(myInput.value.match(lowerCaseLetters)) {  
					
				} else {
					validationText +="Harus memiliki minimal 1 huruf kecil !<br>";
					textValidation.style.color = "#DD4B39";
					textValidation.innerHTML  =validationText;
					document.getElementById("myBtn").disabled = true; 
		        }
		          
				// Validate capital letters
				var upperCaseLetters = /[A-Z]/g;
				if(myInput.value.match(upperCaseLetters)) {  
				} else {					
					validationText +="Harus memiliki minimal 1 huruf besar !<br>";
					textValidation.style.color = "#DD4B39";
					textValidation.innerHTML  =validationText;
					document.getElementById("myBtn").disabled = true; 
				}
		    
				// Validate numbers
				var numbers = /[0-9]/g;
				if(myInput.value.match(numbers)) {  
		              
				} else {
				    validationText +="Harus memiliki minimal 1 angka !<br>";
		            textValidation.style.color = "#DD4B39";
		            textValidation.innerHTML  =validationText;
		            document.getElementById("myBtn").disabled = true; 
		        }
		          
		        // Validate symbols
		        var simbol = "[!&%$@*+]";
		        if(myInput.value.match(simbol)) { 
		              
		        } else {
		            validationText +="Harus memiliki minimal 1 simbol !<br>";
		            textValidation.style.color = "#DD4B39";
		            textValidation.innerHTML  =validationText;
		            document.getElementById("myBtn").disabled = true; 
		        }
		         
				// Validate length
				if(myInput.value.length < 8) {
					validationText +="Jumlah karakter minimal 8 digit !<br>";
					textValidation.style.color = "#DD4B39";
					textValidation.innerHTML  = validationText;
					document.getElementById("myBtn").disabled = true; 
				} else if(myInput.value.length >= 8 && myInput.value.match(simbol) && myInput.value.match(numbers) && myInput.value.match(lowerCaseLetters) && myInput.value.match(upperCaseLetters)) {
					validationText ="Complete.... <br>Password kamu memenuhi kriteria.";
					textValidation.style.color = "green";
					textValidation.innerHTML  = validationText;
					
					isValid=true;
					if(isValid==true){
					document.getElementById("myBtn").disabled = false; 
					}else{
					document.getElementById("myBtn").disabled = true; 
					}
				}
				
				if(myInput.value.length == 0){
					document.getElementById("myBtn").disabled = false;
					validationText ="";
					textValidation.style.color = "#DD4B39";
					textValidation.innerHTML  = validationText;
				}
		    }
		</script>
		';

		if ($cek_user->update_date == '' || $is_update_password == 1) {
			echo json_encode(array(
				'status' => '2',
				'pesan' => $form
			));
		}else if($tgl_end!=''){
			if (strtotime(date('Y-m-d')) < strtotime($tgl_end)) {
				echo json_encode(array(
					'status' => '1',
					'pesan' => $psn
				));
			}
		}

		// if (strtotime(date('Y-m-d')) >= strtotime($tgl_nonaktif)) {
		// 	$this->db->where('username', $this->session->userdata('username'));
		// 	$this->db->update('ms_user', array('active' => 0));
		// 	$form = '<a href="' . base_url() . 'panel/logout" class="btn btn-warning">Log Out</a>';
		// }
	}

	public function cek_ganti_password_old2()
	{
		$tgl_start = '2021-08-18';
		$tgl_end = '2021-08-25'; 
		$tgl_nonaktif = '2021-09-01';

		// $tgl_start = '2021-01-20';
		// $tgl_end = '2021-02-01';
		// $tgl_nonaktif = '2021-02-08';
		$psn = "

		<p>Kepada Yth,
			<br>Semua Pengguna Aplikasi SEEDS (MD &amp; Dealer/AHASS)
			<br>
			<br>Salam Satu HATI,
			<br>
			<br>Melalui ini, Kami ingin menyampaikan bahwa akan dilakukan maintenance pembaharuan password yang dilaksanakan pada setiap bulannya. Oleh sebab itu, pada kesempatan ini juga, kami meminta pengguna Aplikasi SEEDS dapat melakukan proses pembaharuan password sebelum tanggal <br><b>". tgl_indo($tgl_nonaktif) ."</b>.
			<br>
			<br>Untuk melakukan proses pembaharuan password, Pengguna Aplikasi SEEDS bisa mengakses menu profile yang berada di sebelah kanan atas layar (Sebelah Tombol Sign Out).
			<br>
			<br>Jika pengguna tidak melakukan proses pembaharuan password, maka pada tanggal <b>". tgl_indo($tgl_end) ."</b> sistem akan &#39;memaksa&#39; pengguna untuk melakukan proses update password.
			<br>Apabila sudah tanggal <b>". tgl_indo($tgl_nonaktif)."</b> belum dilakukan pembaharuan password, maka akun user tersebut akan dinonaktifkan secara otomatis.
			<br>
			<br>Catatan:
			<br>- Setelah melakukan pergantian password, kami meminta untuk tidak membagikan informasi apapun kepada pihak yang tidak berkepentingan / tidak bertanggung jawab.
			<br>- Jika ada terjadi kendala selama melakukan proses update password bisa menghubungi Tim IT.
			<br>
			<br>Demikian informasi yang dapat kami sampaikan, Atas perhatian dan kerjasamanya kami ucapkan banyak terima kasih.
			<br>
			<br>Hormat Kami,
			<br>
			<br>
			<br>IT Department
			<br>
		</p>
		";
		$form = '
		<form action="' . base_url() . 'panel/update_pass" method="POST">
		<div class="form-group">
                   
          <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

          <div class="col-sm-6">
            <input type="password" class="form-control" id="inputPassword3" placeholder="Silahkan isi password baru" name="password" onkeyup="handleKeyUp()">
           
          </div>
           <div class="col-sm-4">
           
            <a class="btn btn-success btn-flat" type="button" onclick="showing();" id="btnShow"><i class="fa fa-eye"></i> Show Password </a>
          </div>
          

        </div> 
         </div>   
                 <div class="form-group">

                  <label for="inputPassword3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-10">

                  <p id="textValidation" style="color:#DD4B39;font-weight:bold"></p>
                     
                  </div>

                </div>
        <div class="form-group">
        	<br><br>
        	<center>
        		<button type="submit" class="btn btn-primary" id="myBtn">Update</button>
        		<a href="' . base_url() . 'panel/logout" class="btn btn-warning">Log Out</a>
        	</center>
        </div> 
        
        </form>
     
        <br><br><br><br>
        <script>
		    function generate(Length){
		         var result           = \'\';
		         var besar            = \'ABCDEFGHIJKLMNOPQRSTUVWXYZ\';
		         var kecil            = \'abcdefghijklmnopqrstuvwxyz\';
		         var angka            = \'0123456789\';
		         var simbol           = "[|\\/~^:,;?!&%$@*+]";
		         var besarLength = besar.length;
		         var kecilLength = kecil.length;
		         var angkaLength = angka.length;
		         var simbolLength = besar.length;
		            for ( var i = 0; i < 2; i++ ) {
		                result += besar.charAt(Math.floor(Math.random() * besarLength));
		            }
		            for ( var i = 0; i < 2; i++ ) {
		                result += kecil.charAt(Math.floor(Math.random() * kecilLength));
		            }
		            for ( var i = 0; i < 1; i++ ) {
		                result += simbol.charAt(Math.floor(Math.random() * simbolLength));
		            }
		            for ( var i = 0; i < 2; i++ ) {
		                result += angka.charAt(Math.floor(Math.random() * angkaLength));
		            }
		             for ( var i = 0; i < 1; i++ ) {
		                result += simbol.charAt(Math.floor(Math.random() * simbolLength));
		            }
		            // while(result.length < 8){
		            //   result = result;
		            // }
		         return result;
		    }
		   
		    function generated(){
		      document.getElementById("inputPassword3").value=generate(10);
		      valid();
		    }
		    
		    function valid(){
		      
		        var passLength = document.getElementById("inputPassword3").value;
		        var textValidation = document.getElementById("textValidation");
		        
		        if(document.getElementById(\'inputPassword3\').value.length < 8 ){
		            document.getElementById("myBtn").disabled = true; 
		            toastr.error("Not Complete.... <br>Silahkan klik tombol <b>Generate</b> kembali");
		        }else if(document.getElementById(\'inputPassword3\').value.length >= 8 ){
		              document.getElementById("myBtn").disabled = false; 
		              toastr.success("Complete.... <br>Password kamu memenuhi kriteria.");
		        }
		    }
		    
		    function resetValidasi(){
		              document.getElementById("inputPassword3").value=\'\';
		             validationText ="";
		             textValidation.style.color = "#DD4B39";
		             textValidation.innerHTML  = validationText;
		    }
		    
		    function showing(){
		         var x = document.getElementById("inputPassword3");
		         var z = document.getElementById("btnShow");
		         var caption ="";
		            if (x.type === "password") {
		                 x.type = "text";
		                 z.style.backgroundColor="#222B34";
		                 caption ="<i class=\'fa fa-eye-slash\'></i> Hide Password";
		                 z.innerHTML = caption;
		            } else {
		                 x.type = "password";
		                   z.style.backgroundColor="#008D4C";
		                 caption ="<i class=\'fa fa-eye\'></i> Show Password";
		                 z.innerHTML = caption;
		            }
		    }
		    
		    
		    function handleKeyUp(){
		       
		          var validationText="";
		          var myInput = document.getElementById("inputPassword3");
		          var textValidation = document.getElementById("textValidation");
		          var isValid=false;
		           
		          
		          // Validate lowercase letters
		          var lowerCaseLetters = /[a-z]/g;
		          if(myInput.value.match(lowerCaseLetters)) {  
		              
		          } else {
		              
		            validationText +="Harus memiliki minimal 1 huruf kecil !<br>";
		            textValidation.style.color = "#DD4B39";
		            textValidation.innerHTML  =validationText;
		            document.getElementById("myBtn").disabled = true; 
		            
		          }
		          
		          // Validate capital letters
		          var upperCaseLetters = /[A-Z]/g;
		          if(myInput.value.match(upperCaseLetters)) {  
		           
		          } else {
		              
		            validationText +="Harus memiliki minimal 1 huruf besar !<br>";
		            textValidation.style.color = "#DD4B39";
		            textValidation.innerHTML  =validationText;
		            document.getElementById("myBtn").disabled = true; 
		            
		          }
		          
		          // Validate numbers
		          var numbers = /[0-9]/g;
		          if(myInput.value.match(numbers)) {  
		              
		            } else {
		            
		            validationText +="Harus memiliki minimal 1 angka !<br>";
		            textValidation.style.color = "#DD4B39";
		            textValidation.innerHTML  =validationText;
		            document.getElementById("myBtn").disabled = true; 
		            
		          }
		          
		           // Validate symbols
		          var simbol = "[!&%$@*+]";
		          if(myInput.value.match(simbol)) { 
		              
		          } else {
		              
		            validationText +="Harus memiliki minimal 1 simbol !<br>";
		            textValidation.style.color = "#DD4B39";
		            textValidation.innerHTML  =validationText;
		            document.getElementById("myBtn").disabled = true; 
		            
		          }
		         
		          // Validate length
		          if(myInput.value.length < 8) {
		            
		            validationText +="Jumlah karakter minimal 8 digit !<br>";
		            textValidation.style.color = "#DD4B39";
		            textValidation.innerHTML  = validationText;
		            document.getElementById("myBtn").disabled = true; 
		          
		          } else if(myInput.value.length >= 8 && myInput.value.match(simbol) && myInput.value.match(numbers) && myInput.value.match(lowerCaseLetters) && myInput.value.match(upperCaseLetters)) {
		             
		             validationText ="Complete.... <br>Password kamu memenuhi kriteria.";
		             textValidation.style.color = "green";
		             textValidation.innerHTML  = validationText;
		             
		             isValid=true;
		             if(isValid==true){
		               document.getElementById("myBtn").disabled = false; 
		             }else{
		               document.getElementById("myBtn").disabled = true; 
		             }
		          }
		          
		          if(myInput.value.length == 0){
		              
		             document.getElementById("myBtn").disabled = false;
		             validationText ="";
		             textValidation.style.color = "#DD4B39";
		             textValidation.innerHTML  = validationText;
		          }
		        
		    }
		</script>
		';
		// $psn = "<h2>Silahkan Ganti Password kamu sebelum tanggal ".tgl_indo($tgl_end)."</h2>";



		$this->db->where('username', $this->session->userdata('username'));

		
		// $this->db->where('active', '1');
		// $this->db->where("jenis_user_bagian !='h23");
		// $this->db->where('jenis_user_bagian IS NULL', null, false);
		$cek_user = $this->db->get('ms_user')->row();
		if ($cek_user->update_date == '' or strtotime($cek_user->update_date) < strtotime($tgl_start)) {
			if (strtotime(date('Y-m-d')) >= strtotime($tgl_nonaktif)) {
				$this->db->where('username', $this->session->userdata('username'));
				$this->db->update('ms_user', array('active' => 0));
				$form = '<a href="' . base_url() . 'panel/logout" class="btn btn-warning">Log Out</a>';
			}

			if (strtotime(date('Y-m-d')) < strtotime($tgl_end)) {
				echo json_encode(array(
					'status' => '1',
					'pesan' => $psn
				));
			} else {
				echo json_encode(array(
					'status' => '2',
					'pesan' => $form
				));
			}
		}
	}


	public function cobas()
	{
		$id =
		$result = $this->db->query("SELECT * from ms_user limit 1")->row();
		 var_dump($result);
		 die();

	}



	public function ajax_list()
	{
		$list = $this->m_d_stok->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$tipe_1 = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $isi->id_tipe_kendaraan);
			if ($tipe_1->num_rows() > 0) {
				$t = $tipe_1->row();
				$tipe = $t->tipe_ahm;
			} else {
				$tipe = "";
			}

			$cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '1' AND tipe='RFS'")->row();
			$cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '2'")->row();
			$cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '3'")->row();
			$cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'NRFS' AND status < 4")->row();
			$cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
			$cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                        WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode WHERE no_shipping_list IS NOT NULL) 
                        AND id_modell = '$isi->id_tipe_kendaraan' AND id_warna = '$isi->id_warna'")->row();

			$cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
        WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
        AND ms_item.bundling <> 'Ya'")->row();
			$cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.tipe_motor = ms_item.id_tipe_kendaraan AND tr_scan_barcode.warna = ms_item.id_warna 
        WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND warna = '$isi->id_warna'
        AND ms_item.bundling <> 'Ya'")->row();
			$cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
        WHERE tr_sipb.id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tr_sipb.id_warna = '$isi->id_warna'
        AND ms_item.bundling <> 'Ya'")->row();
			$cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
        WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
        AND ms_item.bundling <> 'Ya'")->row();
			$cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$isi->id_item'")->row();
			$sipb = 0;
			$total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;
			if ($cek_in1->jum - $cek_in2->jum > 0 and $cek_item->bundling != 'Ya') {
				$rr = $cek_in1->jum - $cek_in2->jum;
			} else {
				$rr = 0;
			}

			if ($cek_sl1->jum - $cek_sl2->jum > 0 and $cek_item->bundling != 'Ya') {
				$r2 = $cek_sl1->jum - $cek_sl2->jum;
			} else {
				$r2 = 0;
			}
			$stok_md = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum + $cek_pinjaman->jum;

			$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
          LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
          LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
          LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
          LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
          LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
          WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_scan_barcode.status = '4'")->row();
			$cek_unfill = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum FROM tr_do_po 
              LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
              LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
              WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
              AND tr_do_po_detail.id_item = '$isi->id_item'")->row();
			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
              WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
              AND tr_surat_jalan_detail.id_item = '$isi->id_item'")->row();
			$total_stock = $r2 + $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;
			$stock_market = $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;

			$cek_sales = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              WHERE tr_scan_barcode.id_item = '$isi->id_item'")->row();
			if ($cek_sales->jum != 0) {
				$stock_days = ceil(($stok_md / $cek_sales->jum) * 30);
			} else {
				$stock_days = ceil(($stok_md) * 30);
			}

			if ($total_stock > 0) {
				$no++;
				$row = array();
				$row[] = $isi->id_item;
				$row[] = $tipe;
				$row[] = $rr;
				$row[] = $r2;
				$row[] = $stok_md;
				$row[] = $cek_unfill->jum;
				$row[] = $cek_in->jum;
				$row[] = $cek_qty->jum;
				$row[] = $total_stock;
				$row[] = $stock_market;
				$row[] = $cek_sales->jum;
				$row[] = $stock_days;
				$data[] = $row;
			}
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $no,
			"recordsFiltered" => $no,
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
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
		$ad = array("id_user" => $id);
		$data['dt_profil'] = $this->m_admin->kondisi($tabel, $ad);
		$_SESSION['warn'] 	= "Demi keamanan data, mohon untuk melakukan perubahan Password secara berkala setiap 6 bulan sekali ! <br> Silahkan klik tombol <b>Generate</b> jika Password ingin di Generate kan oleh System, jika tidak silahkan ketik password yang anda inginkan pada kolom password.";
		$_SESSION['type'] 	= "danger";
		$this->template($page, $data);
	}
	public function update_profil()
	{
		$tabel				= "ms_user";
		$pk 					= "id_user";
		$id 					= $this->db->escape_str($this->input->post('id')); //$this->db->escape_str($this->input->post('id'));
		$data['username']	= $username = $this->db->escape_str($this->input->post('username')); //$this->db->escape_str($this->input->post('username'));
		$password			= $this->input->post('password');
		$data['admin_password'] = $this->input->post('password');
		// if ($password <> '') {
		// 	$data['password'] = md5($password);
		// 	$data['admin_password'] = $password;
		// 	$data['update_date'] = get_waktu();
		// }
		// $one = $this->m_admin->getByID($tabel, $pk, $id)->row();
		// if ($password == "") {
		// 	$data['admin_password'] = $one->admin_password;
		// }
		$config['upload_path'] 		= './assets/panel/images/user/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';

		$type_foto 		= $_FILES["avatar"]["type"];

		if ($type_foto == 'image/jpeg' or $type_foto == 'image/png' or $type_foto == 'image/jpg' or $type_foto == 'image/gif' or $type_foto == 'image/bmp') {
			$file_foto1 = "ok";
		} else {
			$file_foto1 = "format";
		}

		$this->upload->initialize($config);
		if ($_FILES['avatar']['size'] > $config['max_size'] * 2000) {
			$_SESSION['pesan'] 	= "Gambar Profile melebihi kapasitas ukuran";
			$_SESSION['tipe'] 	= "danger";
			// exit();
			// echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel/profil'>";
			redirect('/panel/profil','refresh');
		}
		
		if ($this->upload->do_upload('avatar')) {
			$data['avatar'] = $this->upload->file_name;

			$one = $this->m_admin->getByID($tabel, $pk, $id)->row();
			$file_path = "assets/panel/images/user/" . $one->avatar;
			if (!($one->avatar == NULL or $one->avatar == '')) {
				if (file_exists(FCPATH . $file_path)) {
					unlink($file_path); //Hapus Gambar
				}
			}
		} else {
			$file_foto = "besar";
			$one = $this->m_admin->getByID($tabel, $pk, $id)->row();
			$data['avatar'] = $one->avatar;
		}
		
		$destroy=0;
		if ($password <> '') {
			$data['password'] = md5($password);
			$data['admin_password'] = $this->db->escape_str($password);
			$data['update_date'] = get_waktu();
			$destroy = 1;
		}
		$one = $this->m_admin->getByID($tabel, $pk, $id)->row();
		if ($password == "") {
			$data['admin_password'] = $one->admin_password;
		}

		$id_session	= $this->input->post('sess');
		$idk = $this->db->query("SELECT id_karyawan_dealer, session_id FROM ms_user WHERE id_user = '$id' and username ='$username' and session_id ='$id_session'");

		if($idk->num_rows() > 0){
			if($id_session != $idk->row()->session_id){
				session_destroy();
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "'>";
			}else{
				$this->m_admin->update($tabel, $data, $pk, $id);
				if($destroy){
					sleep(2);
					session_destroy();
					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "'>";
				}else{
					$_SESSION['pesan'] 	= "Data berhasil diubah!";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel/profil?id=$id'>";
				}
			}
		}else{
			session_destroy();
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "'>";
		}

		/*
			$this->m_admin->update($tabel, $data, $pk, $id);
			// $_SESSION['pesan'] 	= "Data berhasil diubah";
			// $_SESSION['tipe'] 	= "success";
			$idk = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();
			$sq = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $idk->id_karyawan_dealer)->row();
			sleep(2);
			// $this->session->sess_destroy();
			session_destroy();
			// echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel/profil'>";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		*/
	}
	public function tes_login()
	{
		$username =	$this->db->escape($this->input->post('username'));
		$password = $this->db->escape($this->input->post('password'));
		$_SESSION['pesan'] 	= $username . "-" . $password;
		$_SESSION['tipe'] 	= "danger";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
	}
	public function login()
	{

		$username =	$this->input->post('username');
		$password = md5($this->input->post('password'));
		$password_normal = $this->input->post('password');
		$kode = $this->input->post('kode');
		$mycaptcha 	= $this->session->userdata('mycaptcha');
		$tgl 				= gmdate("Y-m-d h:i:s", time() + 60 * 60 * 7);

		if(date('Y-m-d H:i') >='2024-01-01 01:00' && date('Y-m-d H:i') <='2024-01-01 11:00' && $username!='admin'){    
			$_SESSION['pesan'] 	= "Username & Password anda salah";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}else{
			if ($kode == $mycaptcha) {
				$rs_login = $this->m_admin->login($username, $password, $password_normal);
				$cek_user = $this->m_admin->login_user($username);
				$rs_login_super = $this->m_admin->login_super($username, $password);
				if ($rs_login->num_rows() == 1) {
					$row = $rs_login->row();
					// echo $row->status;		
					// if ($row->status=='online') {
					// 	$_SESSION['pesan'] 	= "Akun anda sedang online !";
					// 	$_SESSION['tipe'] 	= "danger";
					// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
					// }else{
					$s = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $row->id_karyawan_dealer);
					$t = $this->m_admin->getByID("ms_karyawan", "id_karyawan", $row->id_karyawan_dealer);
					$sr = $this->db->query("SELECT ms_user.jenis_user, ms_user.jenis_user_bagian FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group=ms_user_group.id_user_group
								WHERE ms_user.id_user='$row->id_user'")->row();
					if ($s->num_rows() > 0 && $sr->jenis_user =='Dealer') {
						$sq = $s->row();
						$nama_lengkap = $sq->nama_lengkap;
						$id_karyawan_dealer = $sq->id_karyawan_dealer;
					} elseif ($t->num_rows() > 0 && $sr->jenis_user =='Main Dealer') {
						$sq = $t->row();
						$nama_lengkap = $sq->nama_lengkap;
						$id_karyawan_dealer = $sq->id_karyawan;
					} else {
						$nama_lengkap = "Admin";
						$id_karyawan_dealer = "0";
					}
					$jenis_user_bagian=$sr->jenis_user_bagian;

					$token = $this->m_admin->get_token(20);
					$ses_loginadmin = array(
						'username'  => $row->username,
						'group' => $row->id_user_group,
						'session_id' => $token,
						'nama' => $nama_lengkap,
						'jenis_user' => $sr->jenis_user,
						'last_timestamp' => time(),
						'id_karyawan_dealer' => $id_karyawan_dealer,
						'jenis_user_bagian' => $jenis_user_bagian,
						'id_user' => $row->id_user
					);
					$this->session->set_userdata($ses_loginadmin);

					$dt['last_login_ip'] 		= $_SERVER['REMOTE_ADDR'];
					$dt['last_login_date'] 	= $tgl;
					$dt['session_id'] 			= $token;
					//$dt['last_mac_address'] = exec('getmac');
					$dt['status'] 							= "online";
					$this->m_admin->update("ms_user", $dt, 'id_user', $row->id_user);



					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel/welcome'>";
					//echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/redirect'>";
					//}
				} elseif ($rs_login_super->num_rows() > 0) {
					$row = $rs_login_super->row();
					$sr = $this->db->query("SELECT ms_user.jenis_user FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group=ms_user_group.id_user_group
							WHERE ms_user.id_user='$row->id_user'")->row();
					$token = $this->m_admin->get_token(20);
					$ses_loginadmin = array(
						'username'  => $row->username,
						'group' => $row->id_user_group,
						'session_id' => $token,
						'nama' => "Super Admin",
						'jenis_user' => $sr->jenis_user,
						'last_timestamp' => time(),
						'id_karyawan_dealer' => "0",
						'id_user' => $row->id_user
					);
					$this->session->set_userdata($ses_loginadmin);

					$dt['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
					$dt['last_login_date'] = $tgl;
					$dt['session_id'] 			= $token;
					//$dt['last_mac_address'] = exec('getmac');
					$dt['status'] 							= "online";
					$this->m_admin->update("ms_user", $dt, 'id_user', $row->id_user);



					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel/welcome'>";
				} elseif ($cek_user->num_rows() > 0) {
					// $_SESSION['pesan'] 	= "Password anda salah!";
					$_SESSION['pesan'] 	= "Username & Password anda salah!";
					$_SESSION['tipe'] 	= "danger";
					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
				} else {
					// $_SESSION['pesan'] 	= "User tidak terdaftar!";
					$_SESSION['pesan'] 	= "Username & Password anda salah!";
					$_SESSION['tipe'] 	= "danger";
					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
				}
			} else {
				$_SESSION['pesan'] 	= "Captcha's Wrong!";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
			}
		}
	}
	public function redirect()
	{
		$this->load->view('v_redirect');
	}
	public function redirects()
	{
		$this->load->view('v_redirects');
	}
	public function logout()
	{

		$id_user = $this->session->userdata('id_user');
		if ($id_user != '') {
			$sq = $this->m_admin->getByID("ms_user", "id_user", $id_user)->row();
			$tgl1 = $sq->last_login_date;
			$tgl2 = gmdate("Y-m-d h:i:s", time() + 60 * 60 * 7);
			$isi = $this->m_admin->cari_waktu($tgl1, $tgl2);

			$dt['last_login_duration'] = $isi['minutes'];
			$dt['status'] = "offline";
			$this->m_admin->update("ms_user", $dt, 'id_user', $id_user);
		}
		session_destroy();
		session_unset();
		//echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/redirects'>";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
	}
	public function setting()
	{
		$page						= "setting";
		$data['title']	= "Setting";
		$data['isi']		= "setting";
		$data['setting'] = $this->m_admin->kondisi('tabel_setting', array("id_setting=1"));
		$data['judul']	= "General";
		$this->template($page, $data);
	}
	public function denied()
	{
		$page						= "denied";
		$data['title']	= "Access Denied";
		$data['isi']		= "";
		$data['judul']	= "Maaf, Anda tidak Memiliki hak akses untuk menu ini!";
		$this->template($page, $data);
	}
	public function save_setting()
	{
		$tabel				= "tabel_setting";
		$pk 					= "id_setting";
		$id 					= 1;
		$data['lokasi_download']	= $this->input->post('lokasi_download');
		$data['lokasi_upload']		= $this->input->post('lokasi_upload');
		$this->m_admin->update($tabel, $data, $pk, $id);
		$_SESSION['pesan'] 	= "Data berhasil diubah";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel/setting'>";
	}
	public function notification()
	{
		$page						= "notification";
		$data['title']	= "Notification";
		$data['isi']		= "notification";
		$data['setting'] = $this->m_admin->kondisi('tabel_setting', array("id_setting=1"));
		$data['judul']	= "Notification";
		$this->template($page, $data);
	}

	public function get_notif()
	{
		$filter['limit'] = "LIMIT 0,5";
		$filter['status'] = "baru";
		$login_id = $this->session->userdata('id_user');
		$get_notif = $this->_getNotifikasi($filter);
		$data = array();
		if ($get_notif->num_rows() > 0) {
			foreach ($get_notif->result() as $nt) {
				// if ($nt->id_user != null) {
				// 	$id_user = explode(',', $nt->id_user);
				// 	if (count($id_user) > 0) {
				// 		if (!in_array($login_id, $id_user)) {
				// 			break;
				// 		}
				// 	}
				// }
				$data[] = [
					'id_referensi' => $nt->id_referensi,
					'judul' => $nt->judul,
					'link' => $nt->link,
					'pesan' => $nt->pesan,
					'kategori' => $nt->nama_kategori,
					'popup' => $nt->popup,
					'status' => $nt->status,
					'id_notifikasi' => $nt->id_notifikasi
				];
			}
		}

		unset($filter['limit']);
		$filter['select'] = 'count';
		$tot_notif = $this->_getNotifikasi($filter)->row()->count;

		$result = [
			'tot_notif' => $tot_notif,
			'data' => $data,
			'sess' => $this->session->userdata()
		];
		echo json_encode($result);
	}
	public function upd_notif_status()
	{
		$data[] = [
			'id_notifikasi' => $this->input->post('id_notifikasi'),
			'status' => $this->input->post('status')
		];
		$this->db->update_batch('tr_notifikasi', $data, 'id_notifikasi');
		echo json_encode('sukses');
	}

	public function all_notif()
	{
		$page            = "all_notif";
		$data['title']   = "All Notification";
		$data['isi']     = "notification";
		$data['judul']   = "All Notification";
		$this->template($page, $data);
	}

	public function fetch_all_notif()
	{
		$fetch_data = $this->make_query_all_notif();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_show = "<a class='btn btn-primary btn-xs btn-flat' href=\"" . base_url($rs->link) . "\">Show</a>";

			$button .= $btn_show;

			$sub_array[] = $rs->pesan;
			$sub_array[] = $rs->created_at;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_all_notif(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query_all_notif($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'view',
			'search' => $this->input->post('search')['value']
		];
		if ($recordsFiltered == true) {
			return $this->_getNotifikasi($filter)->num_rows();
		} else {
			return $this->_getNotifikasi($filter)->result();
		}
	}

	function _getNotifikasi($filter)
	{
		$group     = $this->session->userdata('group');
		$code_user = $this->db->get_where('ms_user_group', ['id_user_group' => $group])->row();
		$id_dealer = $this->m_admin->cari_dealer();
		$where = "WHERE 1=1 ";

		if (isset($filter['status'])) {
			$where .= " AND tr_notifikasi.status='{$filter['status']}'";
		}

		if (isset($filter['search'])) {
			$search = $filter['search'];
			if ($search != '') {
				$where .= " AND (tr_notifikasi.judul LIKE '%$search%'
              OR tr_notifikasi.pesan LIKE '%$search%'
              OR tr_notifikasi.id_referensi LIKE '%$search%'
              ) 
        ";
			}
		}

		if (isset($filter['order'])) {
			$order = $filter['order'];
			if ($order != '') {
				if ($filter['order_column'] == 'view') {
					$order_column = ['pesan', 'tr_notifikasi.created_at', NULL];
				}
				$order_clm  = $order_column[$order['0']['column']];
				$order_by   = $order['0']['dir'];
				$order = " ORDER BY $order_clm $order_by ";
			} else {
				$order = " ORDER BY tr_notifikasi.created_at DESC ";
			}
		} else {
			$order = '';
		}

		$limit = '';
		if (isset($filter['limit'])) {
			$limit = $filter['limit'];
		}

		if ($id_dealer != '') {
			$where .= " AND tr_notifikasi.id_dealer='$id_dealer'";
		}

		$select = "tr_notifikasi.*,ms_notifikasi_kategori.popup,ms_notifikasi_kategori.nama_kategori";
		if (isset($filter['select'])) {
			$select = "COUNT(tr_notifikasi.id_notifikasi) AS count";
		}
		if ($code_user == NULL) {
			$code_user = '';
		} else {
			$code_user = $code_user->code;
		}
		return $this->db->query("SELECT $select
		FROM tr_notifikasi 
		JOIN ms_notifikasi_kategori ON tr_notifikasi.id_notif_kat=ms_notifikasi_kategori.id_notif_kat
		JOIN ms_notifikasi_grup ng ON ng.id_notif_kat=ms_notifikasi_kategori.id_notif_kat AND ng.code_user_group='$code_user'
		$where $order $limit");
	}

	public function del_notif()
	{
		$id_notifikasi = $this->input->get('id');
		$this->db->trans_begin();
		$this->db->delete('tr_notifikasi', ['id_notifikasi' => $id_notifikasi]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response['status'] = 'error';
			$response['msg']    = 'Something went wrong';
		} else {
			$this->db->trans_commit();
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel/all_notif'>";
		}
	}
	public function tes_bagi()
	{
		$stock_days = round(890.55233, 2);
		$pecah  = explode(".", $stock_days);
		if (isset($pecah[1])) {
			if ($pecah[1] / 100 > 0.5) {
				$stock_days_r = ceil($stock_days);
			} else {
				$stock_days_r = floor($stock_days);
			}
		} else {
			$stock_days_r = $stock_days;
		}
		echo $pecah[0] . "<br>";
		echo $pecah[1] . "<br>";
		echo $stock_days . "<br>";
		echo $stock_days_r . "<br>";
	}
	function set_access_dev_tools()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		// send_json($_SERVER);
		$data = [
			'REQUEST_TIME' => $_SERVER['REQUEST_TIME_FLOAT'],
			'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
			'HTTP_COOKIE' => $_SERVER['HTTP_COOKIE'],
			'IP_ADDRESS' => $ipaddress,
		];
		$this->db->insert('log_access_dev_tools', $data);
	}
}
