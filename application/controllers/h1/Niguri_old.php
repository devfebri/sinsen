<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Niguri_old extends CI_Controller {

    var $tables =   "tr_niguri";	
		var $folder =   "h1";
		var $page		=		"niguri_old";
    var $pk     =   "id_niguri";
    var $title  =   "Niguri";

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

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$data['dt_niguri'] = $this->m_admin->getAll($this->tables);		
		$data['dt_tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN ms_item ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan 
					WHERE ms_tipe_kendaraan.active = 1");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function tampil_niguri(){

		$dt_niguri = $this->m_admin->getAll($this->tables);				
		echo $dt_niguri;
	}
	public function t_niguri(){
		$id = $this->input->post('id_niguri');
		$dq = "SELECT tr_niguri_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm FROM tr_niguri_detail INNER JOIN ms_tipe_kendaraan
						ON tr_niguri_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						WHERE tr_niguri_detail.id_niguri = '$id'";
		$data['dt_niguri'] = $this->db->query($dq);		
		$this->load->view('h1/t_niguri',$data);
	}
	public function t_niguri_total(){
		$id 	= $this->input->post('id_niguri');
		$data['sql'] 	= $this->db->query("SELECT SUM(a_m1) AS jum_m1,SUM(a_m) AS jum_m,SUM(a_fix) AS jum_fix,SUM(a_t1) AS jum_t1,SUM(a_t2) AS jum_t2,
						SUM(b_m1) AS um_m1,SUM(b_m) AS um_m,SUM(b_fix) AS um_fix,SUM(b_t1) AS um_t1,SUM(b_t2) AS um_t2,
						SUM(c_m1) AS ju_m1,SUM(c_m) AS ju_m,SUM(c_fix) AS ju_fix,SUM(c_t1) AS ju_t1,SUM(c_t2) AS ju_t2,
						SUM(d_m1) AS j_m1,SUM(d_m) AS j_m,SUM(d_fix) AS j_fix,SUM(d_t1) AS j_t1,SUM(d_t2) AS j_t2 FROM tr_niguri_detail
						WHERE tr_niguri_detail.id_niguri = '$id'");
		//$data['dt_niguri'] = $this->db->query($dq);				
		$this->load->view('h1/t_niguri_total',$data);
	}
	public function t_niguri_v(){
		$id = $this->input->post('id_niguri');
		$dq = "SELECT tr_niguri_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm FROM tr_niguri_detail INNER JOIN ms_tipe_kendaraan
						ON tr_niguri_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						WHERE tr_niguri_detail.id_niguri = '$id'";
		$data['dt_niguri'] = $this->db->query($dq);		
		$this->load->view('h1/t_niguri_v',$data);
	}
	public function t_niguri_total_v(){
		$id 	= $this->input->post('id_niguri');
		$data['sql'] 	= $this->db->query("SELECT SUM(a_m1) AS jum_m1,SUM(a_m) AS jum_m,SUM(a_fix) AS jum_fix,SUM(a_t1) AS jum_t1,SUM(a_t2) AS jum_t2,
						SUM(b_m1) AS um_m1,SUM(b_m) AS um_m,SUM(b_fix) AS um_fix,SUM(b_t1) AS um_t1,SUM(b_t2) AS um_t2,
						SUM(c_m1) AS ju_m1,SUM(c_m) AS ju_m,SUM(c_fix) AS ju_fix,SUM(c_t1) AS ju_t1,SUM(c_t2) AS ju_t2,
						SUM(d_m1) AS j_m1,SUM(d_m) AS j_m,SUM(d_fix) AS j_fix,SUM(d_t1) AS j_t1,SUM(d_t2) AS j_t2 FROM tr_niguri_detail
						WHERE tr_niguri_detail.id_niguri = '$id'");
		//$data['dt_niguri'] = $this->db->query($dq);				
		$this->load->view('h1/t_niguri_total_v',$data);
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";			
		$data['dt_tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN ms_item ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan 
					WHERE ms_tipe_kendaraan.active = 1");						
		$th 						= date("Y");
		$bln 						= date("m");		
		//$pr_num 				= $this->db->query("SELECT * FROM tr_niguri WHERE bulan = '$bln' AND tahun = '$th'")->num_rows();
		$pr_num 				= 0;
		if($pr_num > 0){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri'>";			
		}else{
			$this->template($data);	
		}								
	}
	public function cari_niguri(){
		$id					= $this->input->post('id');
		$result 	= $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN ms_item ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan 
					WHERE ms_tipe_kendaraan.active = 1 AND ms_tipe_kendaraan.id_tipe_kendaraan='$id'");
   	echo json_encode($result);
	}
	public function cari_id(){
		$niguri					= $this->input->post('niguri');
		$th 						= date("Y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_niguri ORDER BY id_niguri DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_niguri)-5;
			$id 	= substr($row->id_niguri,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th.$bln."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th.$bln."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $th.$bln."00".$id;          					          
      }elseif($id>999){
					$kode1 = $th.$bln."0".$id;                    
      }
			$kode = $kode1;
		}else{
			$kode = $th.$bln."00001";
		} 	
		echo $kode;
	}
	public function cek_item()
	{		
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE ms_tipe_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();
			$retail = "90";
			echo "ok"."|".$dt_ve->id_tipe_kendaraan."|".$dt_ve->tipe_ahm."|".strip_tags($dt_ve->deskripsi_ahm)."|".$retail;
		}else{
			echo "There is no data found!";
		}
	}
	public function cek_isi()
	{		
		$a1 = $this->input->get('lm1');
		$a2 = $this->input->get('lm');
		$a3 = $this->input->get('lfix');
		$a4 = $this->input->get('lt1');
		$a5 = $this->input->get('lt2');
		$a6 = $this->input->get('lt2') + 1;
		$a7 = $this->input->get('lt2') + 2;
		$th = $this->input->get('tahun');
		// $a1 = $this->input->post('lm1');
		// $a2 = $this->input->post('lm');
		// $a3 = $this->input->post('lfix');
		// $a4 = $this->input->post('lt1');
		// $a5 = $this->input->post('lt2');
		// $a6 = $this->input->post('lt2') + 1;
		// $a7 = $this->input->post('lt2') + 2;
		// $th = $this->input->post('tahun');
		
		$sql1 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
							ON tr_niguri.id_niguri=tr_niguri_detail.id_niguri 
		 					WHERE tr_niguri.bulan = '$a1' and tr_niguri.tahun = '$th'");
		$sql2 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
							ON tr_niguri.id_niguri=tr_niguri_detail.id_niguri 
		 					WHERE tr_niguri.bulan = '$a2' and tr_niguri.tahun = '$th'");
		$sql3 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
							ON tr_niguri.id_niguri=tr_niguri_detail.id_niguri 
		 					WHERE tr_niguri.bulan = '$a3' and tr_niguri.tahun = '$th'");
		$sql4 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
							ON tr_niguri.id_niguri=tr_niguri_detail.id_niguri 
		 					WHERE tr_niguri.bulan = '$a4' and tr_niguri.tahun = '$th'");
		$sql5 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
							ON tr_niguri.id_niguri=tr_niguri_detail.id_niguri 
		 					WHERE tr_niguri.bulan = '$a5' and tr_niguri.tahun = '$th'");
		$sql6 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
							ON tr_niguri.id_niguri=tr_niguri_detail.id_niguri 
		 					WHERE tr_niguri.bulan = '$a6' and tr_niguri.tahun = '$th'");
		$sql7 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
							ON tr_niguri.id_niguri=tr_niguri_detail.id_niguri 
		 					WHERE tr_niguri.bulan = '$a7' and tr_niguri.tahun = '$th'");
		//echo "ok|".$a1;				
		//$retail_sales5 = "90";
		if($sql6->num_rows() == 0){			
			$retail_sales6 = 0;			
		}else{
			$dt6 = $sql6->row();
			$retail_sales6 = $dt6->retail_sales6;
		}

		if($sql7->num_rows() == 0){			
			$retail_sales7 = 0;			
		}else{
			$dt7 = $sql7->row();
			$retail_sales7 = $dt7->retail_sales7;
		}

		if($sql1->num_rows() == 0){
			$ahm_dist1 = 0;
			$retail_sales1 = 0;
			$total_stock1 = 0;
			$total_stock_day1 = 0;
		}else{
			$dt1 = $sql1->row();		 	
		 	$ahm_dist1 				= $dt1->ahm_dist;
		 	$retail_sales1 		= $dt1->retail_sales;
		 	$total_stock1 		= $dt1->total_stock;
		 	$total_stock_day1 = $dt1->total_stock_day;
		}				

		if($sql2->num_rows() == 0){
			$ahm_dist2 = 0;
			$retail_sales2 = 0;
			$total_stock2 = 0;
			$total_stock_day2 = 0;
		}else{
		 	$dt2 = $sql2->row();		 	
		 	$ahm_dist2 				= $dt2->ahm_dist;
		 	$retail_sales2 		= $dt2->retail_sales;
		 	//$total_stock2 		= $dt2->total_stock;
		 	$total_stock2 		= $total_stock1 + $ahm_dist2 - $retail_sales2;
		 	//$total_stock_day2 = $dt2->total_stock_day;
		}

		if($sql3->num_rows() == 0){
			$ahm_dist3 = 0;
			$retail_sales3 = 0;
			$total_stock3 = 0;
			$total_stock_day3 = 0;
		}else{
		 	$dt3 = $sql3->row();		 	
		 	$ahm_dist3 				= $dt3->ahm_dist;
		 	$retail_sales3 		= $dt3->retail_sales;
		 	//$total_stock3 		= $dt3->total_stock;
		 	$total_stock3 		= $total_stock2 + $ahm_dist3 - $retail_sales3;
		 	//$total_stock_day3 = $dt3->total_stock_day;
		}

	 	if($sql4->num_rows() == 0){
			$ahm_dist4 = 0;
			$retail_sales4 = 0;
			$total_stock4 = 0;
			$total_stock_day4 = 0;
		}else{
		 	$dt4 = $sql4->row();		 	
		 	$ahm_dist4 				= $dt4->ahm_dist;
		 	$retail_sales4 		= $dt4->retail_sales;
		 	//$total_stock4 		= $dt4->total_stock;
		 	$total_stock4 		= $total_stock3 + $ahm_dist4 - $retail_sales4;
		 	//$total_stock_day4 = $dt4->total_stock_day;
		}

		if($sql5->num_rows() == 0){
			$ahm_dist5 = 0;
			$retail_sales5 = 0;
			$total_stock5 = 0;
			$total_stock_day5 = 0;
		}else{
		 	$dt5 = $sql5->row();		 	
		 	$ahm_dist5 				= $dt5->ahm_dist;
		 	$retail_sales5 		= $dt5->retail_sales;
		 	$total_stock5 		= $total_stock4 + $ahm_dist5 - $retail_sales5;
		 	//$total_stock_day5 = $dt5->total_stock_day;
		}

	 	$isi2 = $total_stock2 / (($retail_sales2 + $retail_sales3 + $retail_sales4) / 3) + 1 * 30;
	 	$total_stock_day2 = ceil($isi2);
	 	$isi3 = $total_stock3 / (($retail_sales3 + $retail_sales4 + $retail_sales5) / 3) * 30;
	 	$total_stock_day3 = ceil($isi3);
	 	$isi4 = $total_stock4 / (($retail_sales4 + $retail_sales5 + $retail_sales6) / 3) * 30;
	 	$total_stock_day4 = ceil($isi4);
	 	$isi5 = $total_stock5 / (($retail_sales5 + $retail_sales6 + $retail_sales7) / 3) * 30;
	 	$total_stock_day5 = ceil($isi5);



	 	echo "ok"
	 			."|".$ahm_dist1."|".$retail_sales1."|".$total_stock1."|".$total_stock_day1
	 			."|".$ahm_dist2."|".$retail_sales2."|".$total_stock2."|".$total_stock_day2
	 			."|".$ahm_dist3."|".$retail_sales3."|".$total_stock3."|".$total_stock_day3
	 			."|".$ahm_dist4."|".$retail_sales4."|".$total_stock4."|".$total_stock_day4
	 			."|".$ahm_dist5."|".$retail_sales5."|".$total_stock5."|".$total_stock_day5;		
		
		//echo "ok|".$a1."|12|32|123|33|90|12|32|123|33|90|12|32|123|33|90|12|32|123|33|90|12|32|123|33";

	}
	
	public function cek_niguri_fix(){
		$id_niguri						= $this->input->post('id_niguri');			
		$id_tipe_kendaraan		= $this->input->post('id_tipe_kendaraan');		
		$th 	= $this->input->post('tahun');
		$bl 	= $this->input->post('bulan');
		$bl1 	= $bl+1;
		$bl2 	= $bl+2;
		$bl_1 = $bl-1;
		$bl_2 = $bl-2;			

		//echo "ok|".$id_niguri."|".$id_tipe_kendaraan."|".$th."|".$bl."|".$bl1."|".$bl2."|".$bl_1."|".$bl_2;

		$cek_bulan_1 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
						ON tr_niguri_detail.id_niguri=tr_niguri.id_niguri WHERE tr_niguri.bulan = '$bl_1' AND tr_niguri.tahun = '$th'
						AND tr_niguri_detail.id_tipe_kendaraan = '$id_tipe_kendaraan'");

		$cek_bulan_n = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail
						ON tr_niguri_detail.id_niguri=tr_niguri.id_niguri WHERE tr_niguri.bulan = '$bl' AND tr_niguri.tahun = '$th'
						AND tr_niguri_detail.id_tipe_kendaraan = '$id_tipe_kendaraan'");

		if($cek_bulan_n->num_rows() > 0){
			$sq 		= $cek_bulan_1->row();
			$sq2 		= $cek_bulan_n->row();
			$a_m1 	= $sq2->a_m1;
			$a_m 		= $sq2->a_m;
			$a_fix 	= $sq2->a_fix;
			$a_t1 	= $sq2->a_t1;
			$a_t2 	= $sq2->a_t2;

			$b_m1 	= $sq2->b_m1;
			$b_m 		= $sq2->b_m;
			$b_fix 	= $sq2->b_fix;
			$b_t1 	= $sq2->b_t1;
			$b_t2 	= $sq2->b_t2;

			$c_m1 	= $sq->c_m + $a_m1 - $b_m1;
			$c_m 		= $c_m1 + $a_m - $b_m;
			$c_fix 	= $c_m + $a_fix - $b_fix;
			$c_t1 	= $c_fix + $a_t1 - $b_t1;
			$c_t2 	= $c_t1 + $a_t2 - $b_t2;

			$b 			= ($b_m + $b_m1)/2;
			$b1 		= $c_m1 / $b;
			$b2 		= $b1 * 30;
			$d_m1 	= floor($b2);
			
			$c 			= ($b_m + $b_fix)/2;
			$c1 		= $c_m / $c;
			$c2 		= $c1 * 30;
			$d_m 		= floor($c2);
			
			$d 			= ($b_fix + $b_t1)/2;
			$d1 		= $c_fix / $d;
			$d2 		= $d1 * 30;
			$d_fix 	= floor($d2);

			$e 			= ($b_t1 + $b_t2)/2;
			$e1 		= $c_t1 / $e;
			$e2 		= $e1 * 30;
			$d_t1 	= floor($e2);

			$f 			= $b_t2/1;
			$f1 		= $c_t2 / $f;
			$f2 		= $f1 * 30;
			$d_t2 	= floor($f2);

		}elseif($cek_bulan_n->num_rows() == 0 AND $cek_bulan_1->num_rows() > 0){			
			$sq 		= $cek_bulan_1->row();
			//$sq2 		= $cek_bulan_n->row();
			$a_m1 	= $sq->a_m;
			$a_m 		= $sq->a_fix;
			$a_fix 	= $sq->a_t1;
			$a_t1 	= $sq->a_t2;
			$a_t2 	= 0;

			$b_m1 	= $sq->b_m;
			$b_m 		= $sq->b_fix;
			$b_fix 	= $sq->b_t1;
			$b_t1 	= $sq->b_t2;
			$b_t2 	= 0;

			$c_m1 	= $sq->c_m + $a_m1 - $b_m1;
			$c_m 		= $c_m1 + $a_m - $b_m;
			$c_fix 	= $c_m + $a_fix - $b_fix;
			$c_t1 	= $c_fix + $a_t1 - $b_t1;
			$c_t2 	= $c_t1 + $a_t2 - $b_t2;

			$b 			= ($b_m + $b_m1)/2;
			$b1 		= $c_m1 / $b;
			$b2 		= $b1 * 30;
			$d_m1 	= floor($b2);
			
			$c 			= ($b_m + $b_fix)/2;
			$c1 		= $c_m / $c;
			$c2 		= $c1 * 30;
			$d_m 		= floor($c2);
			
			$d 			= ($b_fix + $b_t1)/2;
			$d1 		= $c_fix / $d;
			$d2 		= $d1 * 30;
			$d_fix 	= floor($d2);

			$e 			= ($b_t1 + $b_t2)/2;
			$e1 		= $c_t1 / $e;
			$e2 		= $e1 * 30;
			$d_t1 	= floor($e2);

			$d_t2 	= 0;

		}else{
			$a_m1 	= 0;
			$a_m 		= 0;
			$a_fix 	= 0;
			$a_t1 	= 0;
			$a_t2 	= 0;
			$b_m1 	= 0;
			$b_m 		= 0;
			$b_fix 	= 0;
			$b_t1 	= 0;
			$b_t2 	= 0;
			$c_m1 	= 0;
			$c_m 		= 0;
			$c_fix 	= 0;
			$c_t1 	= 0;
			$c_t2 	= 0;
			$d_m1 	= 0;
			$d_m 		= 0;
			$d_fix 	= 0;
			$d_t1 	= 0;
			$d_t2 	= 0;
		}		
		echo "ok|".$a_m1."|".$a_m."|".$a_fix."|".$a_t1."|".$a_t2."|".$b_m1."|".$b_m."|".$b_fix."|".$b_t1."|".$b_t2."|".$c_m1."|".$c_m."|".$c_fix."|".$c_t1."|".$c_t2."|".$d_m1."|".$d_m."|".$d_fix."|".$d_t1."|".$d_t2;	

	}
	public function save_niguri(){
		$id_niguri							= $this->input->post('id_niguri');			
		$id_tipe_kendaraan				= $this->input->post('id_tipe_kendaraan');

		//echo $id_niguri;
		$data['id_niguri']			= $this->input->post('id_niguri');			
		$data['id_tipe_kendaraan']				= $this->input->post('id_tipe_kendaraan');			
		$data['a_m1']			= $this->input->post('a_m1');			
		$data['a_m']			= $this->input->post('a_m');			
		$data['a_fix']		= $this->input->post('a_fix');					
		$data['a_t1']			= $this->input->post('a_t1');					
		$data['a_t2']			= $this->input->post('a_t2');					
		$data['b_m1']			= $this->input->post('b_m1');			
		$data['b_m']			= $this->input->post('b_m');			
		$data['b_fix']		= $this->input->post('b_fix');					
		$data['b_t1']			= $this->input->post('b_t1');					
		$data['b_t2']			= $this->input->post('b_t2');					
		$data['c_m1']			= $this->input->post('c_m1');			
		$data['c_m']			= $this->input->post('c_m');			
		$data['c_fix']		= $this->input->post('c_fix');					
		$data['c_t1']			= $this->input->post('c_t1');					
		$data['c_t2']			= $this->input->post('c_t2');					
		$data['d_m1']			= $this->input->post('d_m1');			
		$data['d_m']			= $this->input->post('d_m');			
		$data['d_fix']		= $this->input->post('d_fix');					
		$data['d_t1']			= $this->input->post('d_t1');					
		$data['d_t2']			= $this->input->post('d_t2');	


		//$cek2 = $this->m_admin->insert("tr_niguri_detail",$data);			

		$cek = $this->db->query("SELECT * FROM tr_niguri_detail WHERE id_niguri='$id_niguri' AND id_tipe_kendaraan = '$id_tipe_kendaraan'");
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_niguri_detail;
			$cek2 = $this->m_admin->update("tr_niguri_detail",$data,"id_niguri_detail",$id);						
		}else{
			$cek2 = $this->m_admin->insert("tr_niguri_detail",$data);						
		}
		echo "nihil";
		
	}
	public function delete_niguri(){
		$id_product = $this->input->post('id_tipe_kendaraan');
		$id_niguri_detail 	= $this->input->post('id_niguri_detail');
		$this->db->query("DELETE FROM tr_niguri_detail WHERE id_niguri_detail = '$id_niguri_detail'");			
		echo "nihil";
	}
	public function cancel_niguri(){
		$id_niguri			= $this->input->post('id_niguri');			
		$this->m_admin->delete("tr_niguri","id_niguri",$id_niguri);
		$this->m_admin->delete("tr_niguri_detail","id_niguri",$id_niguri);
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
			$data['id_niguri'] 	= $this->input->post('id_niguri');
			$data['ket'] 		= $this->input->post('ket');	
			$data['bulan'] 	= $this->input->post('bulan');	
			$data['tahun'] 	= $this->input->post('tahun');	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');		
		$this->db->trans_begin();			
		$this->db->delete($tabel,array($pk=>$id));
		$this->db->trans_commit();			
		$result = 'Success';									
		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$this->m_admin->delete("tr_niguri_detail","id_niguri",$id);
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri'>";
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
	public function detail()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_niguri'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");
		$data['isi']    = $this->page;		
		$data['dt_tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN ms_item ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan 
					WHERE ms_tipe_kendaraan.active = 1");						
		$data['title']	= $this->title;		
		$data['set']	= "detail";									
		$this->template($data);	
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_niguri'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");
		$data['isi']    = $this->page;		
		$data['dt_tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN ms_item ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan 
					WHERE ms_tipe_kendaraan.active = 1");						
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
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
			$data['id_niguri'] 	= $this->input->post('id_niguri');
			$data['ket'] 		= $this->input->post('ket');	
			$data['bulan'] 	= $this->input->post('bulan');	
			$data['tahun'] 	= $this->input->post('tahun');	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}