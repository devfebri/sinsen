<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_checker extends CI_Controller {

	var $tables_header =   "tr_po_checker";	
	var $folder =   "h3";
	var $page		=		"po_checker";
	var $pk     =   "no_po";
	var $title  =   "PO Checker";

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
		$data['set']	= "view";
		$data['dt'] = $this->db->query("SELECT tr_po_checker.no_po,tr_po_checker.tgl_po,tr_po_checker.tgl_sj,tr_po_checker.no_sj,tr_po_checker.status , tr_checker.tgl_checker FROM `tr_checker` left join tr_po_checker on tr_checker.tgl_checker = tr_po_checker.tgl_checker group by tr_checker.tgl_checker");
		//$data['dt'] = "dfdf";
		$this->template($data);			
	}

	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_paket'] = $this->db->query("SELECT * FROM ms_paket_bundling WHERE active = 1");									
		$this->template($data);	
	}
	public function pemenuhan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "pemenuhan";			
		$tgl 			= $this->input->get('tgl');
		$data['tgl'] 	= $this->input->get('tgl');
		$cek_tr_po_checker = $this->db->query("SELECT max(no_po) as maxid from tr_po_checker")->row()->maxid;
		if ($cek_tr_po_checker == null) {
			$data['id_tr_po_checker'] = "0001";
		}else{
			$data['id_tr_po_checker'] =sprintf("%04d", $cek_tr_po_checker+1);
		}
		$data['dt_detail'] = $this->db->query("SELECT *,sum(tr_checker_detail.qty_order)as jum from tr_checker_detail 
							inner join tr_checker on tr_checker_detail.id_checker = tr_checker.id_checker
							left join ms_part on tr_checker_detail.id_part = ms_part.id_part
							where tr_checker.tgl_checker = '$tgl' 
							group by tr_checker_detail.id_part ");																	
		$this->template($data);	
	}	

	public function cari_id(){
		// $po					= $this->input->post('po');
		// $th 						= date("Y");
		// $bln 						= date("m");		
		// $pr_num 				= $this->db->query("SELECT * FROM tr_po ORDER BY id_po DESC LIMIT 0,1");						
		// if($pr_num->num_rows()>0){
		// 	$row 	= $pr_num->row();				
		// 	$pan  = strlen($row->id_po)-5;
		// 	$id 	= substr($row->id_po,$pan,5)+1;	
		// 	if($id < 10){
		// 			$kode1 = $th.$bln."0000".$id;          
	 //  }elseif($id>9 && $id<=99){
		// 			$kode1 = $th.$bln."000".$id;                    
	 //  }elseif($id>99 && $id<=999){
		// 			$kode1 = $th.$bln."00".$id;          					          
	 //  }elseif($id>999){
		// 			$kode1 = $th.$bln."0".$id;                    
	 //  }
		// 	$kode = $kode1;
		// }else{
		// 	$kode = $th.$bln."00001";
		// } 	
		$kode = $this->m_admin->cari_id("tr_po_checker","no");
		return $kode;
	}


	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');				
		$no_po			= $this->input->post("no_po");		
		$da_header['no_po']				= $this->input->post("no_po");		
		$da_header['tgl_po']				= $this->input->post("tgl_po");				
		$da_header['tgl_checker']		= $this->input->post("tgl_checker");		
		$da_header['status'] 			= "input";		
		$da_header['created_at'] 			= $waktu;		
		$da_header['created_by'] 			= $login_id;		
		
		$no 			= $this->input->post("no");		
		for ($i=1; $i <= $no; $i++) { 			
			$id_part 						= $_POST["id_part_".$i];	
			$data['id_part'] 		= $id_part;				
			$data['qty_order'] 		= $_POST["qty_order_".$i];			
			$data['qty_pemenuhan'] 		= $_POST["qty_pemenuhan_".$i];			
			$qty_pemenuhan 			= $_POST["qty_pemenuhan_".$i];			
			$data['no_po'] 			= $no_po;											

			//$cek = $this->db->query("SELECT * FROM tr_po_checker_detail WHERE id_part = '$id_part' AND no_po = '$no_po'");
				$this->m_admin->insert("tr_po_checker_detail",$data);								
		}
			$this->m_admin->insert("tr_po_checker",$da_header);								

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/po_checker'>";
	}


	public function approve()
	{		
		if (!!$this->input->post('submit_approve')) {
			$no_po 			= $this->input->post("no_po");	

			$no 			= $this->input->post("no");		
			for ($i=1; $i <= $no; $i++) { 			
				$id_part 				= $_POST["id_part_".$i];	
				$data_upd[$i]['id_part'] 		= $id_part;				
				$data_upd[$i]['id_detail'] 		= $_POST["id_detail_".$i];			
				$data_upd[$i]['qty_order'] 		= $_POST["qty_order_".$i];			
				$data_upd[$i]['qty_pemenuhan'] 		= $_POST["qty_pemenuhan_".$i];			
				$data_upd[$i]['no_po'] 			= $no_po;																		
			}
			
			$this->db->query("UPDATE tr_po_checker SET status = 'approved' WHERE no_po = '$no_po'");
			$this->db->update_batch('tr_po_checker_detail', $data_upd, 'id_detail');

			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/po_checker'>";		
		}
		else 
		{
			$data['isi']    = $this->page;		
			$data['title']	= $this->title;		
			$data['set']	= "approve";	
			$no_po 			= $this->input->get('po');
			$data['detail'] = $this->db->query("SELECT * from tr_po_checker_detail 
												left join ms_part on tr_po_checker_detail.id_part = ms_part.id_part
												where tr_po_checker_detail.no_po = '$no_po'");	
			$data['header'] = $this->db->query("SELECT * from tr_po_checker where no_po = '$no_po'");	
			$this->template($data);	
		}
	}

	public function reject()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->get("id");
		$id_				= $this->input->get($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['status_po'] 	= "rejected";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/po_checker'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	public function cetak_sj($no_po)
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables_header;
		$pk 				= $this->pk;
		
		$cek 				= $this->m_admin->getByID($tabel,$pk,$no_po)->num_rows();
		if($cek > 0){
			$data['update_at']				= $waktu;		
			$data['update_by']				= $login_id;		
			$data['no_sj']				= "no_sj-$no_po";		
			$data['tgl_sj']				= date('Y-m-d');					
			$this->db->update($this->tables_header, $data, "no_po = $no_po");
		//	$_SESSION['pesan'] 	= "Data has been updated successfully";
		//	$_SESSION['tipe'] 	= "success";

			$pdf = new FPDF('p','mm','A4');			
    $pdf->AddPage();
       // head	  
	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(50, 5, 'Cetak Surat Jalan', 0, 1, 'C');
	  $pdf->Cell(50, 5, 'Jambi, '.date('d/m/Y').'', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Kepada Yth,', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'PT. SINAR SENTOSA PRIMATAMA', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
	  $pdf->Line(11, 31, 200, 31);	   	


	  $pdf->Output(); 
			//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/po_checker'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}



}