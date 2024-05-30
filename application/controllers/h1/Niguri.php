<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Niguri extends CI_Controller {

    var $tables =   "tr_niguri";	
		var $folder =   "h1";
		var $page		=		"niguri";
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
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");	
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function tampil_niguri(){

		$dt_niguri = $this->m_admin->getAll($this->tables);				
		echo $dt_niguri;
	}
	public function t_niguri(){
		$id = $this->input->post('id_niguri');
		$dq = "SELECT tr_niguri_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_niguri_detail INNER JOIN ms_item
				 		ON tr_niguri_detail.id_item = ms_item.id_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna = ms_warna.id_warna WHERE tr_niguri_detail.id_niguri = '$id'";
		$data['dt_niguri'] = $this->db->query($dq);		
		$this->load->view('h1/t_niguri',$data);
	}
	public function t_hitungan_niguri(){
		$id = $this->input->post('id_niguri');
		$data['dbulan'] = $this->input->post('bulan');
		$data['dtahun'] = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
	  $a1 = $bulan - 2;
	  $a2 = $bulan - 1;
	  $a3 = $bulan;
	  $a4 = $bulan + 1;
	  $a5 = $bulan + 2;
	  if($a1 == "-1"){
	    $a1 = "11";
	  }elseif($a1 == "0"){
	    $a1 = "12";
	  }
	  if($a2 == "0"){
	    $a2 = "12";
	  }
	  if($a5 == "14"){
	    $a5 = "2";
	  }elseif($a5 == "13"){
	    $a5 = "1";
	  }
	  if($a4 == "13"){
	    $a4 = "1";
	  }

	  $data["lm1"] 	= $a1; 
	  $data["lm"] 	= $a2; 
	  $data["lfix"] = $a3; 
	  $data["lt1"] 	= $a4; 
	  $data["lt2"] 	= $a5; 
		
		$this->load->view('h1/t_hitungan_niguri',$data);
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
		$dq = "SELECT tr_niguri_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_niguri_detail INNER JOIN ms_item
				 		ON tr_niguri_detail.id_item = ms_item.id_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna = ms_warna.id_warna WHERE tr_niguri_detail.id_niguri = '$id'";
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
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");	
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
		$niguri = $this->input->post('niguri');
		$th     = date("Y");
		//Jika lewat tgl 5 maka masuk bulan berikutnya :
		$b      = date('d')<=5?date("m")+1:date("m")+2;
		// $b   = date("m");		
		$bln    = sprintf("%'.02d",$b);				
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
		$tgl 			= date("dmY");
		$id_item 	= $this->input->post('id_item');
		$bul 			= $this->input->post('bulan');
		$tahun 		= $this->input->post('tahun');
		$bulan 		= $bul - 1;		
		$bulan2 	= $bul - 2;			
		
		if($bulan == "-1"){
    	$bln = "11";
    	$th = $tahun-1;
	  }elseif($bulan == "0"){
	    $bln = "12";
	    $th = $tahun-1;
	  }else{
	  	$bln = $bulan;
	  	$th = $tahun;
	  }

	  if($bulan2 == "-1"){
    	$bln2 = "11";
    	$th = $tahun-1;
	  }elseif($bulan2 == "0"){
	    $bln2 = "12";
	    $th = $tahun-1;
	  }else{
	  	$bln2 = $bulan2;
	  	$th = $tahun;
	  }

	  $isi_bln 		= $bln;		
	  $isi_bln2 	= $bln2;		
	  $isi_bln3 	= $bul;		

	  $isi_bl_1 	= sprintf("%'.02d",$bln);		
		$isi_bl_2 	= sprintf("%'.02d",$bln2);		

	  $r_m     		= $th."-".$isi_bln;
	  $r_m1     	= $th."-".$isi_bln2;
	  $bln_thn  	= $isi_bln.$th;	
	  $bln_thn_ds = $isi_bl_1.$th;	
	  $bln_thn_sl = $isi_bl_2.$th;	
	  
		$sql = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_item.id_item = '$id_item'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();
			$id_tipe_kendaraan = $dt_ve->id_tipe_kendaraan;
			$id_warna = $dt_ve->id_warna;
			
			$cari_m1 = $this->db->query("SELECT count(no_mesin) as jum FROM tr_shipping_list WHERE id_modell = '$id_tipe_kendaraan' 
						AND id_warna = '$id_warna' AND RIGHT(tgl_sl,6) = '$bln_thn_sl'");			
			if($cari_m1->num_rows() > 0){
				$ty = $cari_m1->row();
				$a_m1 = $ty->jum;
			}else{
				$a_m1 = 0;
			}			

			$cari_m2 = $this->db->query("SELECT SUM(qty_plan) as jum FROM tr_displan WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' 
						AND id_warna = '$id_warna' AND RIGHT(tanggal,6) = '$bln_thn_ds'");
			if($cari_m2->num_rows() > 0){
				$op = $cari_m2->row();
				$a_m2 = $op->jum;
			}else{
				$a_m2 = 0;
			}			

			$cari_fix1 = $this->db->query("SELECT * FROM tr_niguri_detail INNER JOIN tr_niguri
				ON tr_niguri_detail.id_niguri = tr_niguri.id_niguri 
				WHERE tr_niguri_detail.id_item = '$id_item' AND tr_niguri.bulan = '$isi_bln'
				AND tr_niguri.tahun = '$tahun'");
			if($cari_fix1->num_rows() > 0){
				$rr = $cari_fix1->row();
				$data_fix_1 = $rr->a_t1;
				$data_t1 = $rr->a_t2;
				$data_t2 = 0;
				$datb_fix_1 = 0;
				$datb_t1 = 0;
				$datb_t2 = 0;
			}else{
				$data_fix_1 = 0;
				$data_t1 = 0;
				$data_t2 = 0;
				$datb_fix_1 = 0;
				$datb_t1 = 0;
				$datb_t2 = 0;
			}						
			
			$cek_retail = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
					WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$r_m1' AND tr_sales_order.status_so = 'so_invoice'
					AND tr_scan_barcode.id_item = '$id_item'");
			if($cek_retail->num_rows() > 0){
				$ty = $cek_retail->row();
				$data_rm1 = $ty->jum;
			}else{
				$data_rm1 = 0;
			}

			$cek_retail2 = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
					WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$r_m' AND tr_sales_order.status_so = 'so_invoice'
					AND tr_scan_barcode.id_item = '$id_item'");
			if($cek_retail2->num_rows() > 0){
				$t = $cek_retail2->row();
				$data_rm = $t->jum;
			}else{
				$data_rm = 0;
			}
			echo "ok"."|".$dt_ve->id_item."|".$dt_ve->tipe_ahm."|".$dt_ve->warna."|".$a_m1."|".$a_m2."|".$data_fix_1."|".$data_t1."|".$data_t2."|".$data_rm1."|".$data_rm."|".$datb_fix_1."|".$datb_t1."|".$datb_t2;
		}else{
			echo "There is no data found!";
		}
	}

	
	public function cek_niguri_fix(){			
		$id_niguri	 = $this->input->post('id_niguri');			
		$id_item		 = $this->input->post('id_item');		
		$a_m1        = $this->input->post("a_m1");   
	  $a_m         = $this->input->post("a_m");   
	  $a_fix       = $this->input->post("a_fix");   
	  $a_t1        = $this->input->post("a_t1");   
	  $a_t2        = $this->input->post("a_t2");   
	  $b_m1        = $this->input->post("b_m1");   
	  $b_m         = $this->input->post("b_m");   
	  $b_fix       = $this->input->post("b_fix");   
	  $b_t1        = $this->input->post("b_t1");   
	  $b_t2        = $this->input->post("b_t2");   
		$th 	= $this->input->post('tahun');
		$bl 	= $this->input->post('bulan');
		
		$bl2 	= $bl+2;
		$bl_1 = $bl-1;
		$bl_2 = $bl-2;			

		$isi_bl_1 	= sprintf("%'.02d",$bl_1);		
		$isi_bl_2 	= sprintf("%'.02d",$bl_2);		


		$cek_bulan_1 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail 
						ON tr_niguri_detail.id_niguri=tr_niguri.id_niguri WHERE tr_niguri.bulan = '$isi_bl_1' AND tr_niguri.tahun = '$th'
						AND tr_niguri_detail.id_item = '$id_item'");

		$cek_bulan_2 = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail
						ON tr_niguri_detail.id_niguri=tr_niguri.id_niguri WHERE tr_niguri.bulan = '$isi_bl_2' AND tr_niguri.tahun = '$th'
						AND tr_niguri_detail.id_item = '$id_item'");	

    $cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$id_item'")->row();
		$cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND status = '1' AND tipe='RFS'")->row();
    $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND status = '2'")->row();
    $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND status = '3'")->row();
    $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND tipe = 'NRFS' AND status < 4")->row();
    $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
    $cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                      WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode WHERE no_shipping_list IS NOT NULL) 
                      AND id_modell = '$cek_item->id_tipe_kendaraan' AND id_warna = '$cek_item->id_warna'")->row();

    $cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
     	WHERE tr_shipping_list.id_modell = '$cek_item->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$cek_item->id_warna'
     	AND ms_item.bundling <> 'Ya'")->row();
		$cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.tipe_motor = ms_item.id_tipe_kendaraan AND tr_scan_barcode.warna = ms_item.id_warna 
			WHERE tipe_motor = '$cek_item->id_tipe_kendaraan' AND warna = '$cek_item->id_warna'
			AND ms_item.bundling <> 'Ya'")->row();      
    $cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
    	WHERE tr_sipb.id_tipe_kendaraan = '$cek_item->id_tipe_kendaraan' AND tr_sipb.id_warna = '$cek_item->id_warna'
    	AND ms_item.bundling <> 'Ya'")->row();                
		$cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
		 	WHERE tr_shipping_list.id_modell = '$cek_item->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$cek_item->id_warna'
		 	AND ms_item.bundling <> 'Ya'")->row();
    $sipb = 0;
    if($cek_in1->jum - $cek_in2->jum > 0 AND $cek_item->bundling != 'Ya'){
    	$rr = $cek_in1->jum - $cek_in2->jum;
    }else{
    	$rr = 0;
    }

    if($cek_sl1->jum - $cek_sl2->jum > 0 AND $cek_item->bundling != 'Ya'){
    	$r2 = $cek_sl1->jum - $cek_sl2->jum;
    }else{
    	$r2 = 0;
    }
    
    $cek_dealer = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.tipe_motor = ms_item.id_tipe_kendaraan 
    	AND tr_scan_barcode.warna=ms_item.id_warna
		 	WHERE tr_scan_barcode.tipe_motor = '$cek_item->id_tipe_kendaraan' AND tr_scan_barcode.warna = '$cek_item->id_warna'
		 	AND tr_scan_barcode.status = 4
		 	AND ms_item.bundling <> 'Ya'")->row();

    $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum + $r2 + $rr + $cek_dealer->jum;

		if($cek_bulan_1->num_rows() > 0){
			$sq1 		= $cek_bulan_1->row();
			$m_bulan_lalu = $sq1->c_m;
		}else{
			$m_bulan_lalu = 0;
		}

		if($cek_bulan_2->num_rows() > 0){
			$sq2 		= $cek_bulan_2->row();
			$m_bulan_lalu_2 = $sq2->b_m;
		}else{
			$m_bulan_lalu_2 = 0;
		}		

		//$c_m1 	= $m_bulan_lalu + $a_m1 - $b_m1;
		$c_m1 	= $total;
		$c_m 		= $c_m1 + $a_m - $b_m;
		$c_fix 	= $c_m + $a_fix - $b_fix;
		$c_t1 	= $c_fix + $a_t1 - $b_t1;
		$c_t2 	= $c_t1 + $a_t2 - $b_t2;
		

		//$b 			= ($m_bulan_lalu_2!=0)?($m_bulan_lalu_2 + $b_m1 + $b_m) / 3:1;
		$b 			= ($m_bulan_lalu_2 + $b_m1 + $b_m) / 3;	
		$b1 		= @($c_m1 / $b) * 30;		
		$d_m1 	= floor($b1);

		$c 			= ($b_m1 + $b_m + $b_fix) / 3;
		$c1 		= @($c_m / $c) * 30;		
		$d_m 		= floor($c1);

		$d 			= ($b_m + $b_fix + $b_t1) / 3;
		$d1 		= @($c_fix / $d) * 30;		
		$d_fix	= floor($d1);
		
		$e 			= ($b_fix + $b_t1 + $b_t2) / 3;
		$e1 		= @($c_t1 / $e) * 30;		
		$d_t1		= floor($e1);

		$f 			= ($b_t1 + $b_t2) / 2;
		$f1 		= @($c_t2 / $f) * 30;		
		$d_t2		= floor($f1);

		$a_fix		= $this->input->post('a_fix');					
		$a_t1			= $this->input->post('a_t1');	

		$bulan 		= $bl - 1;						
		if($bulan == "-1"){
    	$bln = "11";
    	$ta = $th-1;
	  }elseif($bulan == "0"){
	    $bln = "12";
	    $ta = $th-1;
	  }else{
	  	$bln = $bulan;
	  	$ta = $th;
	  }	 
	  $isi_bln 		= $bln;			  

		$cari_fix = $this->db->query("SELECT * FROM tr_niguri_detail INNER JOIN tr_niguri
				ON tr_niguri_detail.id_niguri = tr_niguri.id_niguri 
				WHERE tr_niguri_detail.id_item = '$id_item' AND tr_niguri.bulan = '$isi_bln'
				AND tr_niguri.tahun = '$ta'");
		if($cari_fix->num_rows() > 0){
			$rr = $cari_fix->row();
			$data_fix = $rr->a_t1;
			$data_t1 = $rr->a_t2;		
			$ada_pesan = 1;	
		}else{
			$data_fix = 0;
			$data_t1 = 0;			
			$ada_pesan = 0;
		}						

		$sql = $this->m_admin->getByID("ms_setting_h1","id_setting_h1",1)->row();
		$p_t1 = $sql->presentase_t1;
		$p_t2 = $sql->presentase_t2;
		$persen_t1 = $data_fix * $p_t1 / 100;
		$persen_t2 = $data_t1 * $p_t2 / 100;

		$batas_atas_t1 = floor($data_fix + $persen_t1);
		$batas_bawah_t1 = ceil($data_fix - $persen_t1);

		$batas_atas_t2 = floor($data_t1 + $persen_t2);
		$batas_bawah_t2 = ceil($data_t1 - $persen_t2);

		//if(($a_fix <= $batas_atas_t1 AND $a_fix >= $batas_bawah_t1 AND $a_t1 <= $batas_atas_t2 AND $a_t1 >= $batas_bawah_t2) OR ($ada_pesan == 0 AND $a_m == 0) OR ($ada_pesan == 0 AND $a_m1 == 0)){						
		if($a_m == 0 AND $a_m1 == 0){
			echo "ok|".$c_m1."|".$c_m."|".$c_fix."|".$c_t1."|".$c_t2."|".$d_m1."|".$d_m."|".$d_fix."|".$d_t1."|".$d_t2;	
		}elseif($a_m != 0 OR $a_m1 != 0){
			if($a_fix <= $batas_atas_t1 AND $a_fix >= $batas_bawah_t1 AND $a_t1 <= $batas_atas_t2 AND $a_t1 >= $batas_bawah_t2){						
				echo "ok|".$c_m1."|".$c_m."|".$c_fix."|".$c_t1."|".$c_t2."|".$d_m1."|".$d_m."|".$d_fix."|".$d_t1."|".$d_t2;	
			}else{
				echo "Nilai Fix & T1 tidak sesuai dengan batas yg telah ditentukan";	
			}
		}else{
			echo "Nilai Fix & T1 tidak sesuai dengan batas yg telah ditentukan";
		}

	}
	public function save_niguri(){
		$bul 			= $this->input->post('bulan');
		$tahun 		= $this->input->post('tahun');
		$bulan 		= $bul - 1;						
		if($bulan == "-1"){
    	$bln = "11";
    	$th = $tahun-1;
	  }elseif($bulan == "0"){
	    $bln = "12";
	    $th = $tahun-1;
	  }else{
	  	$bln = $bulan;
	  	$th = $tahun;
	  }	 
	  $isi_bln 		= $bln;			  


		$id_niguri							= $this->input->post('id_niguri');			
		$id_item								= $this->input->post('id_item');

		//echo $id_niguri;
		$data['id_niguri']			= $this->input->post('id_niguri');			
		$data['id_item']				= $this->input->post('id_item');			
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

		

		$cek_tgl = $this->db->query("SELECT * FROM tr_niguri WHERE bulan = '$bul' AND tahun = '$tahun'");
		if($cek_tgl->num_rows() > 0){
			$hasil_tgl = "none";
		}else{
			$hasil_tgl = "ok";
		}
		
		if($hasil_tgl == 'ok'){
			$cek = $this->db->query("SELECT * FROM tr_niguri_detail WHERE id_niguri='$id_niguri' AND id_item = '$id_item'");
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_niguri_detail;
				$cek2 = $this->m_admin->update("tr_niguri_detail",$data,"id_niguri_detail",$id);												
			}else{
				$cek2 = $this->m_admin->insert("tr_niguri_detail",$data);						
			}
			echo "nihil";
		}else{
			echo "Data niguri ".$bulan."-".$tahun." sudah dibuat";	
		}					
	}
	public function download()
	{
		$id = $this->input->get('id');		
		$this->download_file($id);		
	}
	function download_file($id){	
		$k = $this->session->userdata('id_karyawan_dealer');
		$bulan 		= gmdate("mY", time()+60*60*7);					
		
		$data['no'] = "NIGURI-".$id."-".$bulan;		
		$data['id'] = $id;								
		$this->load->view("h1/file_niguri",$data);
	}
	public function edit_niguri(){
		$id_niguri				= $this->input->post('id_niguri');							
		$yy = $this->m_admin->getByID("tr_niguri","id_niguri",$id_niguri)->row();
		$bul 			= $yy->bulan;
		$tahun 		= $yy->tahun;		
		$bulan 		= $bul - 1;						
		if($bulan == "-1"){
    	$bln = "11";
    	$th = $tahun-1;
	  }elseif($bulan == "0"){
	    $bln = "12";
	    $th = $tahun-1;
	  }else{
	  	$bln = $bulan;
	  	$th = $tahun;
	  }	 
	  $isi_bln 		= $bln;			  

		$id_niguri_detail	= $this->input->post('id_niguri_detail');							
		$id_item					= $this->input->post('id_item');			
		$data['id_item']	= $this->input->post('id_item');			
		$a_m1 = $data['a_m1']			= $this->input->post('am1');			
		$a_m 	= $data['a_m']			= $this->input->post('am');			
		$data['a_fix']		= $this->input->post('afix');					
		$data['a_t1']			= $this->input->post('at1');					
		$data['a_t2']			= $this->input->post('at2');					
		$data['b_m1']			= $this->input->post('bm1');			
		$data['b_m']			= $this->input->post('bm');			
		$data['b_fix']		= $this->input->post('bfix');					
		$data['b_t1']			= $this->input->post('bt1');					
		$data['b_t2']			= $this->input->post('bt2');					
		$data['c_m1']			= $this->input->post('cm1');			
		$data['c_m']			= $this->input->post('cm');			
		$data['c_fix']		= $this->input->post('cfix');					
		$data['c_t1']			= $this->input->post('ct1');					
		$data['c_t2']			= $this->input->post('ct2');					
		$data['d_m1']			= $this->input->post('dm1');			
		$data['d_m']			= $this->input->post('dm');			
		$data['d_fix']		= $this->input->post('dfix');					
		$data['d_t1']			= $this->input->post('dt1');					
		$data['d_t2']			= $this->input->post('dt2');	

		$a_fix		= $this->input->post('afix');					
		$a_t1			= $this->input->post('at1');					

		// $a_fix		= 30;
		// $a_t1			= 50;

		$cari_fix = $this->db->query("SELECT * FROM tr_niguri_detail INNER JOIN tr_niguri
				ON tr_niguri_detail.id_niguri = tr_niguri.id_niguri 
				WHERE tr_niguri_detail.id_item = '$id_item' AND tr_niguri.bulan = '$isi_bln'
				AND tr_niguri.tahun = '$tahun'");
		if($cari_fix->num_rows() > 0){
			$rr = $cari_fix->row();
			$data_fix = $rr->a_t1;
			$data_t1 = $rr->a_t2;
			$ada_pesan = 1;			
		}else{
			$ada_pesan = 0;
			$data_fix = 0;
			$data_t1 = 0;			
		}						

		$sql = $this->m_admin->getByID("ms_setting_h1","id_setting_h1",1)->row();
		$p_t1 = $sql->presentase_t1;
		$p_t2 = $sql->presentase_t2;
		$persen_t1 = $data_fix * $p_t1 / 100;
		$persen_t2 = $data_t1 * $p_t2 / 100;

		$batas_atas_t1 = floor($data_fix + $persen_t1);
		$batas_bawah_t1 = ceil($data_fix - $persen_t1);

		$batas_atas_t2 = floor($data_t1 + $persen_t2);
		$batas_bawah_t2 = ceil($data_t1 - $persen_t2);

		if(($a_fix <= $batas_atas_t1 AND $a_fix >= $batas_bawah_t1 AND $a_t1 <= $batas_atas_t2 AND $a_t1 >= $batas_bawah_t2) OR ($ada_pesan == 0 AND $a_m == 0) OR ($ada_pesan == 0 AND $a_m1 == 0)){						
			$cek2 = $this->m_admin->update("tr_niguri_detail",$data,"id_niguri_detail",$id_niguri_detail);						
			$cek = $this->db->query("SELECT * FROM tr_niguri_detail 
					INNER JOIN tr_po ON tr_niguri_detail.id_niguri = tr_po.id_niguri 
					WHERE tr_niguri_detail.id_niguri = '$id_niguri'");
			foreach ($cek->result() as $isi) {
				$id_po					= $isi->id_po;
				$id_item				= $isi->id_item;
				$qty_po_fix			= $isi->a_fix;
				$qty_po_t1			= $isi->a_t1;
				$qty_po_t2			= $isi->a_t2;
				$qty_niguri_fix	= $isi->a_fix;
				$on_hand				= "";			
				$this->db->query("UPDATE tr_po_detail SET id_item = '$id_item',qty_po_fix='$qty_po_fix',qty_po_t1='$qty_po_t1',
							qty_po_t2='$qty_po_t2',qty_niguri_fix='$qty_niguri_fix',on_hand='$on_hand' WHERE id_po = '$id_po' AND id_item = '$id_item'");
			}				
			$cece = $this->m_admin->getByID("tr_niguri","id_niguri",$id_niguri);		
			if($cece->num_rows() > 0){
				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri/edit?id=".$id_niguri."&v=e'>";
			}else{
				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri/add'>";
			}
		}else{
			$cece = $this->m_admin->getByID("tr_niguri","id_niguri",$id_niguri);		
			if($cece->num_rows() > 0){
				$_SESSION['pesan'] 	= "Nilai Fix & T1 tidak sesuai dengan batas yg telah ditentukan";
				//.$a_fix."".$a_t1."".$batas_atas_t1."".$batas_bawah_t1."".$batas_atas_t1."".$batas_bawah_t2."".$isi_bln."".$tahun."".$id_item;
				$_SESSION['tipe'] 	= "danger";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri/edit?id=".$id_niguri."&v=e'>";
			}else{
				$_SESSION['pesan'] 	= "Nilai Fix & T1 tidak sesuai dengan batas yg telah ditentukan";
				$_SESSION['tipe'] 	= "danger";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri/add'>";
			}			
		}	

		
	}
	public function delete_niguri(){
		$id_product = $this->input->post('id_item');
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
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		$bul 		= $this->input->post('bulan');	
		$tahun 	= $this->input->post('tahun');	

		$bulan 			= $bul - 1;						
		if($bulan == "-1"){
    	$bln = "11";
    	$th = $tahun-1;
	  }elseif($bulan == "0"){
	    $bln = "12";
	    $th = $tahun-1;
	  }else{
	  	$bln = $bulan;
	  	$th = $tahun;
	  }	
	  	  	  
		if($cek == 0){						
		  $isi_bln 								= $bln;			  
			$id_niguri							= $this->input->post('id_niguri');			
			$id_item								= $this->input->post('id_item');
			$data['id_niguri']			= $this->input->post('id_niguri');			
			$jumlah 								= $this->input->post('jumlah');			
			for ($i=1; $i <= $jumlah ; $i++) { 				
				$da[$i]['id_niguri']	= $this->input->post("id_niguri");			
				$da[$i]['id_item']		= $this->input->post("id_item_".$i);			
				$da[$i]['a_m1']			= $this->input->post("a_m1_".$i);			
				$da[$i]['a_m']			= $this->input->post("a_m_".$i);			
				$da[$i]['a_fix']		= $this->input->post("a_fix_".$i);			
				$da[$i]['a_t1']			= $this->input->post("a_t1_".$i);					
				$da[$i]['a_t2']			= $this->input->post("a_t2_".$i);					
				$da[$i]['b_m1']			= $this->input->post("b_m1_".$i);					
				$da[$i]['b_m']			= $this->input->post("b_m_".$i);			
				$da[$i]['b_fix']		= $this->input->post("b_fix_".$i);					
				$da[$i]['b_t1']			= $this->input->post("b_t1_".$i);					
				$da[$i]['b_t2']			= $this->input->post("b_t2_".$i);					
				$da[$i]['c_m1']			= $this->input->post("c_m1_".$i);			
				$da[$i]['c_m']			= $this->input->post("c_m_".$i);			
				$da[$i]['c_fix']		= $this->input->post("c_fix_".$i);					
				$da[$i]['c_t1']			= $this->input->post("c_t1_".$i);					
				$da[$i]['c_t2']			= $this->input->post("c_t2_".$i);					
				$da[$i]['d_m1']			= $this->input->post("d_m1_".$i);			
				$da[$i]['d_m']			= $this->input->post("d_m_".$i);			
				$da[$i]['d_fix']		= $this->input->post("d_fix_".$i);					
				$da[$i]['d_t1']			= $this->input->post("d_t1_".$i);					
				$da[$i]['d_t2']			= $this->input->post("d_t2_".$i);	

				// $a_fix		= $this->input->post("a_fix_".$i."");			
				// $a_t1			= $this->input->post("a_t1_".$i."");					

				// $cari_fix = $this->db->query("SELECT * FROM tr_niguri_detail INNER JOIN tr_niguri
				// 		ON tr_niguri_detail.id_niguri = tr_niguri.id_niguri 
				// 		WHERE tr_niguri_detail.id_item = '$id_item' AND tr_niguri.bulan = '$isi_bln'
				// 		AND tr_niguri.tahun = '$th'");
				// if($cari_fix->num_rows() > 0){
				// 	$rr = $cari_fix->row();
				// 	$data_fix = $rr->a_t1;
				// 	$data_t1 = $rr->a_t2;			
				// }else{
				// 	$data_fix = 0;
				// 	$data_t1 = 0;			
				// }						

				// // $sql = $this->m_admin->getByID("ms_setting_h1","id_setting_h1",1)->row();
				// // $p_t1 = $sql->presentase_t1;
				// // $p_t2 = $sql->presentase_t2;
				// $persen_t1 = $data_fix * $p_t1 / 100;
				// $persen_t2 = $data_t1 * $p_t2 / 100;

				// $batas_atas_t1 = floor($data_fix + $persen_t1);
				// $batas_bawah_t1 = ceil($data_fix - $persen_t1);

				// $batas_atas_t2 = floor($data_t1 + $persen_t2);
				// $batas_bawah_t2 = ceil($data_t1 - $persen_t2);

				
				
			}				
			//cek niguri bulan lalu
			$cek_tgl = $this->db->query("SELECT * FROM tr_niguri WHERE bulan = '$bul' AND tahun = '$tahun'");
			if($cek_tgl->num_rows() > 0){				
				$_SESSION['pesan2'] 	= "Data niguri ".$bul."-".$tahun." sudah dibuat";
			}else{	
		//	var_dump($da);			
			//echo json_encode($da);
				$testb= $this->db->insert_batch('tr_niguri_detail', $da);
			}				


			$id_niguri 	= $this->input->post('id_niguri');
			$data['id_niguri'] 	= $id_niguri;
			$data['ket'] 		= $this->input->post('ket');	
			$data['bulan'] 	= $this->input->post('bulan');	
			$data['tahun'] 	= $this->input->post('tahun');	
			$bulan 	= $this->input->post('bulan');	
			$tahun 	= $this->input->post('tahun');	
			$data['status_niguri'] 	= "input";	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	


			$cek_tgl = $this->db->query("SELECT * FROM tr_niguri WHERE bulan = '$bulan' AND tahun = '$tahun'");
			$cek_all = $this->db->query("SELECT * FROM tr_niguri");
			$cek_lalu = $this->db->query("SELECT * FROM tr_niguri WHERE bulan = '$bln' AND tahun = '$th'");
			if($cek_tgl->num_rows() == 0){			
				if($cek_lalu->num_rows() == 1 OR $cek_all->num_rows() == 0){				
					$this->m_admin->insert($tabel,$data);
					$_SESSION['pesan'] 	= "Data has been saved successfully";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri/add'>";
				}else{
					$_SESSION['pesan'] 	= "Niguri bulan sebelumnya harus dibuat dulu";
					$_SESSION['tipe'] 	= "danger";
					echo "<script>history.go(-1)</script>";	
				}
			}else{
				$_SESSION['pesan'] 	= "Niguri bulan tersebut sudah dibuat sebelumnya";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cari_id_po(){
		//XXX/PO-E20/YYYY
		//$po					= $this->input->post('po');
		$th 						= date("Y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_po WHERE tahun = '$th' ORDER BY id_po DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();							
			$id 	= substr($row->id_po,0,5)+1;	
			if($id < 10){
					$kode1 = "0000".$id."/PO-E20/".$th;          
	  }elseif($id>9 && $id<=99){
					$kode1 = "000".$id."/PO-E20/".$th;          
	  }elseif($id>99 && $id<=999){
					$kode1 = "00".$id."/PO-E20/".$th;          
	  }elseif($id>999){
					$kode1 = "0".$id."/PO-E20/".$th;          
	  }
			$kode = $kode1;
		}else{
			$kode = "00001/PO-E20/".$th;			
		} 	
		return $kode;
	}
	// public function cari_id_po(){		
	// 	$th 						= date("Y");
	// 	$bln 						= date("m");				
	// 	$pr_num 				= $this->db->query("SELECT * FROM tr_po ORDER BY id_po DESC LIMIT 0,1");						
	// 	if($pr_num->num_rows()>0){
	// 		$row 	= $pr_num->row();				
	// 		$pan  = strlen($row->id_po)-5;
	// 		$id 	= substr($row->id_po,$pan,5)+1;	
	// 		if($id < 10){
	// 				$kode1 = $th.$bln."0000".$id;          
	//   }elseif($id>9 && $id<=99){
	// 				$kode1 = $th.$bln."000".$id;                    
	//   }elseif($id>99 && $id<=999){
	// 				$kode1 = $th.$bln."00".$id;          					          
	//   }elseif($id>999){
	// 				$kode1 = $th.$bln."0".$id;                    
	//   }
	// 		$kode = $kode1;
	// 	}else{
	// 		$kode = $th.$bln."00001";
	// 	} 	
	// 	return $kode;
	// }
	public function delete()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');				
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$this->m_admin->delete("tr_niguri_detail","id_niguri",$id);
			$this->m_admin->delete("tr_niguri","id_niguri",$id);
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";					
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri'>";
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
		$data['title']	= $this->title;		
		$data['set']	= "detail";									
		$this->template($data);	
	}
	public function approval()
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
		$data['title']	= $this->title;		
		$data['set']	= "approval";									
		$this->template($data);	
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_niguri'] = $this->m_admin->kondisi($tabel,$d);		
		$data['isi']    = $this->page;		
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");	
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
	public function save_approval()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$set				= $this->input->post("process");
		if($set=='approve'){			
			$data['status_niguri']				= "approved";		
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
		}else{
			$data['status_niguri']				= "rejected";		
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
		}

		$ce = $this->db->query("SELECT * FROM tr_niguri WHERE id_niguri = '$id'")->row();
		$id_po = $this->cari_id_po();
		$da['id_po'] = $id_po;
		$da['id_niguri'] = $id;
		$da['jenis_po'] = "PO Reguler";
		$da['bulan'] 	= $ce->bulan;	
		$da['tahun'] 	= $ce->tahun;
		$da['tgl'] = $tgl;
		$da['ket'] = "";
		$da['created_at']		= $waktu;		
		$da['created_by']		= $login_id;	
		$da['active']				= 1;	
		$da['status']				= "approved";	
		$this->m_admin->insert("tr_po",$da);

		$cek = $this->db->query("SELECT * FROM tr_niguri_detail WHERE id_niguri = '$id'");
		foreach ($cek->result() as $isi) {
			$ta['id_po']					= $id_po;
			$ta['id_item']				= $isi->id_item;
			$ta['qty_po_fix']			= $isi->a_fix;
			$ta['qty_po_t1']			= $isi->a_t1;
			$ta['qty_po_t2']			= $isi->a_t2;
			$ta['qty_niguri_fix']	= $isi->a_fix;
			$ta['on_hand']				= "";
			$ta['id_user']				= $this->session->userdata('id_user');		
			$this->m_admin->insert("tr_po_detail",$ta);
		}			


		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/niguri'>";		
	}
	public function cari_data(){
		$id		= $this->input->post('id');
		$row = $this->db->query("SELECT tr_niguri_detail.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan FROM tr_niguri_detail INNER JOIN ms_item ON tr_niguri_detail.id_item=ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_niguri_detail.id_niguri_detail = '$id'")->row();		
		echo $row->id_item."|".$row->tipe_ahm."|".$row->warna."|".$row->a_m1."|".$row->a_m."|".$row->a_fix."|".$row->a_t1."|".$row->a_t2."|".$row->b_m1."|".$row->b_m."|".$row->b_fix."|".$row->b_t1."|".$row->b_t2."|".$row->c_m1."|".$row->c_m."|".$row->c_fix."|".$row->c_t1."|".$row->c_t2."|".$row->d_m1."|".$row->d_m."|".$row->d_fix."|".$row->d_t1."|".$row->d_t2."|".$row->id_niguri_detail."|".$row->id_niguri;		
	}
}