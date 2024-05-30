<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

    var $tables =   "tr_quotation";	
		var $folder =   "h1";
		var $page		=		"quotation";
    var $pk     =   "no_quotation";
    var $title  =   "Quotation";

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
		$data['set']		= "view";				
		// $data['dt_quo'] = $this->db->query("SELECT * FROM tr_quotation INNER JOIN tr_quotation_bulan ON tr_quotation.no_quotation=tr_quotation_bulan.no_quotation
		// 		INNER JOIN tr_quotation_tipe ON tr_quotation.no_quotation = tr_quotation_tipe.no_quotation");	
		$data['dt_quo'] = $this->db->query("SELECT tr_quotation_tipe.no_quotation,sum(nilai)as nilai,group_concat(id_tipe_kendaraan separator ', ')as tipe_kendaraan,tr_quotation_bulan.bulan FROM `tr_quotation_tipe` 
JOIN tr_quotation_bulan ON tr_quotation_tipe.no_quotation=tr_quotation_bulan.no_quotation
GROUP BY tr_quotation_tipe.no_quotation");		
		$id_user = $this->session->userdata("id_user");
		$cek = $this->db->query("SELECT * FROM tr_quotation_bulan WHERE id_user = '$id_user'");
		foreach ($cek->result() as $isi){
			$cek2 = $this->db->query("SELECT * FROM tr_quotation WHERE no_quotation = '$isi->no_quotation'");
			if($cek2->num_rows() == 0){
				$this->db->query("DELETE FROM tr_quotation_bulan WHERE no_quotation = '$isi->no_quotation'");
			}
		}
		$cek3 = $this->db->query("SELECT * FROM tr_quotation_tipe WHERE id_user = '$id_user'");
		foreach ($cek3->result() as $isi) {
			$cek4 = $this->db->query("SELECT * FROM tr_quotation WHERE no_quotation = '$isi->no_quotation'");
			if($cek4->num_rows() == 0){
				$this->db->query("DELETE FROM tr_quotation_tipe WHERE no_quotation = '$isi->no_quotation'");
			}
		}		
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";					
		$this->template($data);			
	}
	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "history";
		$id = $this->input->get('id');
		$id_dealer = $this->input->post('id_dealer');
		$row = $this->db->query("SELECT * FROM tr_quotation WHERE no_quotation='$id'");
		if ($row->num_rows()>0) {
			$data['row']       = $row->row();
			$data['id_dealer'] =$id_dealer;
			$where_dealer = $id_dealer!=''?"AND tr_do_po.id_dealer=$id_dealer":'';
			$this->db->order_by("nama_dealer", "ASC");
			$data['dealer'] = $this->db->get_where('ms_dealer',['h1'=>1,'active'=>1]);

			$quo = $this->db->query("SELECT * FROM tr_quotation_tipe WHERE no_quotation='$id'");

			$data['detail'] = $this->db->query("SELECT tr_do_po_detail.*,LEFT(tgl_do,7) as th_bln_do,
				(SELECT bulan FROM tr_quotation_bulan WHERE no_quotation='$id')as bulan,
				(SELECT tahun FROM tr_quotation_bulan WHERE no_quotation='$id')as tahun,
				LEFT(id_item,3)as id_tipe_kendaraan, tr_do_po.tgl_do,ms_dealer.nama_dealer FROM tr_do_po_detail 
				JOIN tr_do_po ON tr_do_po_detail.no_do=tr_do_po.no_do 
				JOIN ms_dealer ON ms_dealer.id_dealer=tr_do_po.id_dealer
				-- WHERE LEFT(tgl_do,7)=(SELECT CONCAT(tahun,'-',bulan) FROM tr_quotation_bulan WHERE no_quotation='$id')
				WHERE LEFT(id_item,3) IN (SELECT id_tipe_kendaraan FROM tr_quotation_tipe WHERE no_quotation='$id')
				AND disc>0
				AND tr_do_po.status='approved'
				$where_dealer
				");

			$data['detail_inv'] = $this->db->query("SELECT tr_invoice_dealer_detail.*,LEFT(tgl_faktur,7) as th_bln_do,(tr_invoice_dealer_detail.potongan/qty_do) as disc,
				(SELECT bulan FROM tr_quotation_bulan WHERE no_quotation='$id')as bulan,
				(SELECT tahun FROM tr_quotation_bulan WHERE no_quotation='$id')as tahun,
				LEFT(id_item,3)as id_tipe_kendaraan, tr_invoice_dealer.tgl_faktur,ms_dealer.nama_dealer FROM tr_invoice_dealer_detail 
				JOIN tr_invoice_dealer ON tr_invoice_dealer_detail.no_do=tr_invoice_dealer.no_do 
				JOIN tr_do_po ON tr_invoice_dealer.no_do=tr_do_po.no_do
				JOIN ms_dealer ON ms_dealer.id_dealer=tr_do_po.id_dealer
				-- WHERE LEFT(tgl_faktur,7)=(SELECT CONCAT(tahun,'-',bulan) FROM tr_quotation_bulan WHERE no_quotation='$id')
				WHERE LEFT(id_item,3) IN (SELECT id_tipe_kendaraan FROM tr_quotation_tipe WHERE no_quotation='$id')
				AND potongan>0
				AND (tr_invoice_dealer.status_invoice='approved' OR tr_invoice_dealer.status_invoice='printable')
				$where_dealer
				");
			$this->template($data);			
		}else{
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/quotation'>";
		}
	}
	public function cari_id(){		
		$th 						= "QUOT";
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_quotation ORDER BY no_quotation DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_quotation)-5;
			$id 	= substr($row->no_quotation,$pan,5)+1;	
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


		$cek  = $this->db->query("SELECT SUM(tr_claim_sales_program_detail.nilai_potongan) as jum FROM tr_claim_sales_program INNER JOIN tr_claim_sales_program_detail ON tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp
        INNER JOIN tr_sales_program_tipe ON tr_claim_sales_program.id_program_md = tr_sales_program_tipe.id_program_md
        WHERE tr_claim_sales_program_detail.perlu_revisi = 0 AND tr_sales_program_tipe.jenis_bayar_dibelakang = 'Quotation'");
    if($cek->num_rows() > 0){
      $t = $cek->row();
      $total_hutang = $t->jum;
    }else{
      $total_hutang = 0;
    }
    $cek3  = $this->db->query("SELECT SUM(tr_do_po_detail.disc * tr_do_po_detail.qty_do) AS jum FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do");
    if($cek3->num_rows() > 0){
      $t = $cek3->row();
      $total_piutang2 = $t->jum;
    }else{
      $total_piutang2 = 0;
    }
    $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan * tr_invoice_dealer_detail.qty_do) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po ON tr_invoice_dealer_detail.no_do = tr_do_po.no_do");
    if($cek2->num_rows() > 0){
      $t = $cek2->row();
      $total_piutang = $t->jum + $total_piutang2;
    }else{
      $total_piutang = 0 + $total_piutang2;
    }

    if($total_hutang >= $total_piutang){
      $hasil = $total_hutang - $total_piutang;
      $hasil2 = 0;
    }else{
      $hasil = 0;
      $hasil2 = $total_piutang - $total_hutang;
    }
		
		echo $kode."|".$hasil."|".$hasil2;
	}
	public function save_bulan(){
		$no_quotation			= $this->input->post('no_quotation');			
		$bulan						= $this->input->post('bulan');		
		$tahun						= $this->input->post('tahun');		
		$data['no_quotation'] = $no_quotation;
		$data['tahun'] = $tahun;
		$data['bulan'] = $bulan;
		$data['id_user'] = $this->session->userdata("id_user");
		$cek = $this->db->query("SELECT * FROM tr_quotation_bulan WHERE no_quotation='$no_quotation' AND bulan='$bulan'");
		if($cek->num_rows() > 0){
			$sq = $cek->row();			
			$this->m_admin->update("tr_quotation_bulan",$data,"id_quot_bulan",$sq->id_quot_bulan);			
		}else{
			$this->m_admin->insert("tr_quotation_bulan",$data);			
		}
		echo "nihil";
	}
	public function delete_bulan(){
		$id_quot_bulan = $this->input->post('id_quot_bulan');		
		$this->db->query("DELETE FROM tr_quotation_bulan WHERE id_quot_bulan = '$id_quot_bulan'");			
		echo "nihil";
	}
	public function t_quot_bulan(){
		$id = $this->input->post('no_quotation');
		$dq = "SELECT * FROM tr_quotation_bulan 
						WHERE tr_quotation_bulan.no_quotation = '$id'";
		$data['dt_quot'] = $this->db->query($dq);		
		$this->load->view('h1/t_quotation',$data);
	}
	public function save_tipe(){
		$no_quotation			= $this->input->post('no_quotation');			
		$id_tipe_kendaraan						= $this->input->post('id_tipe_kendaraan');				
		$nilai						= $this->m_admin->ubah_rupiah($this->input->post('nilai'));								
		$data['no_quotation'] = $no_quotation;
		$data['id_tipe_kendaraan'] = $id_tipe_kendaraan;
		$data['nilai'] = $nilai;
		$data['id_user'] = $this->session->userdata("id_user");
		$cek = $this->db->query("SELECT * FROM tr_quotation_tipe WHERE no_quotation='$no_quotation' AND id_tipe_kendaraan = '$id_tipe_kendaraan'");
		if($cek->num_rows() > 0){
			$sq = $cek->row();			
			$this->m_admin->update("tr_quotation_tipe",$data,"id_quot_tipe",$sq->id_quot_tipe);			
		}else{
			$this->m_admin->insert("tr_quotation_tipe",$data);			
		}
		echo "nihil";
	}
	public function delete_tipe(){
		$id_quot_tipe = $this->input->post('id_quot_tipe');		
		$this->db->query("DELETE FROM tr_quotation_tipe WHERE id_quot_tipe = '$id_quot_tipe'");			
		echo "nihil";
	}
	public function t_quot_tipe(){
		$id = $this->input->post('no_quotation');
		$dq = "SELECT * FROM tr_quotation_tipe 
						WHERE tr_quotation_tipe.no_quotation = '$id'";
		$data['dt_quot2'] = $this->db->query($dq);		
		$this->load->view('h1/t_quotation2',$data);
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
		if($cek == 0){			
			$data['no_quotation'] 	= $this->input->post('no_quotation');
			$data['tgl_quotation'] 	= $tgl;			
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$this->m_admin->insert($tabel,$data);
			//$this->download_file($id_po);

			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/quotation/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}