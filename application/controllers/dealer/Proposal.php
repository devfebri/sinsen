<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Proposal extends CI_Controller {
    var $table_header =   "tr_proposal_dealer";	
    var $table_detail =   "tr_proposal_dealer_rincian";	
		var $folder =   "dealer";
		var $page		= "proposal";
    var $pk     =   "id_proposal";
    var $title  =   "Proposal";
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
		$id_dealer = $this->m_admin->cari_dealer();		
		$data['dt_proposal'] = $this->m_admin->getByID($this->table_header,"id_dealer",$id_dealer);
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		
		$this->template($data);										
	}
	public function edit()
	{				
		$id = $this->input->get('id');
		$data['dt_proposal'] = $this->db->query("SELECT * FROM tr_proposal_dealer INNER JOIN ms_dealer ON tr_proposal_dealer.id_dealer = ms_dealer.id_dealer	
						WHERE tr_proposal_dealer.id_proposal = '$id'");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "edit";				
		$this->template($data);			
	}
	public function detail()
	{	
		$id = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "detail";
		$login_id		= $this->session->userdata('id_user');
		$data['dt_proposal'] = $this->m_admin->getByID('tr_proposal_dealer','id_proposal',$id);	
		$data['show_rincian'] = $this->db->query("SELECT * FROM $this->table_detail where created_by = '$login_id' AND id_proposal='$id' ");
			
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function send()
	{	
		$id = $this->input->get('id');		
		$login_id		= $this->session->userdata('id_user');
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$data['status'] = "waiting";
		$data['updated_at'] = $waktu;
		$data['updated_by'] = $login_id;
		$this->m_admin->update("tr_proposal_dealer",$data,"id_proposal",$id);
			
		$_SESSION['pesan'] 	= "Data has been sent to MD successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/proposal'>";
	}
	public function showRincian()
	{
		$login_id		= $this->session->userdata('id_user');
		$table_detail   = $this->table_detail;
		$data['mode'] 	= $this->input->post('mode');		
		$data['show_rincian'] = $this->db->query("SELECT * FROM $table_detail where created_by = '$login_id' AND status='new' ");
	/*	$data['dt_item'] = $this->db->query("SELECT * from ms_item
											inner join ms_warna on ms_item.id_warna = ms_warna.id_warna
											inner join ms_tipe_kendaraan on ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
												order by id_item DESC");							
*/
		$this->load->view('dealer/t_proposal_rincian', $data);
	}
	public function saveRincian(){
		$data['item']		= $this->input->post('item');
		$data['qty']		= $this->input->post('qty');
		$data['harga']		= $this->input->post('harga');
		$data['keterangan']		= $this->input->post('keterangan');
		$data['ppn']		= $this->input->post('ppn');
		$data['created_by']		= $this->session->userdata('id_user');
		$data['status']		='new';
		$this->m_admin->insert($this->table_detail,$data);
		$this->showRincian();
	}	
	public function cari_id(){
		
		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$dealer 			= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek ORDER BY id_prospek DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_prospek)-11;
			$id 	= substr($row->id_prospek,$pan,11)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."0000".$id;          
		    }elseif($id > 9 && $id <= 99){
				$kode1 = $th.$bln.$tgl."000".$id;                    
		    }elseif($id > 99 && $id <= 999){
				$kode1 = $th.$bln.$tgl."00".$id;          					          
		    }elseif($id > 999){
				$kode1 = $th.$bln.$tgl."0".$id;                    
		    }
			$kode = $kode_dealer.$kode1;
		}else{
			$kode = $kode_dealer.$th.$bln.$tgl."00001";
		} 	
		$rt = rand(1111,9999);
		echo $kode."|".$rt;
	}
	public function delete_rincian(){
		$id = $this->input->post('id_rincian');		
		$this->db->query("DELETE FROM tr_proposal_dealer_rincian WHERE id_rincian = '$id'");			
		echo "nihil";
	}	
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel_header			= $this->table_header;
		$table_detail			= $this->table_detail;
		$pk					= $this->pk;
			$data['id_program'] 	= $this->input->post('id_program');
			$data['nama_program'] 	= $this->input->post('nama_program');
			$data['tema_program'] 					= $this->input->post('tema_program');	
			$data['tgl_mulai'] 			= $this->input->post('tgl_mulai');	
			$data['tgl_selesai'] 			= $this->input->post('tgl_selesai');	
			$data['md'] 						= $this->input->post('md');
			$data['dealer'] 						= $this->input->post('dealer');
			$data['ahm'] 						= $this->input->post('ahm');
			$data['lainnya'] 						= $this->input->post('lainnya');
			if ($this->input->post('leasing')!=null) {
				$leasing = implode(',', $this->input->post('leasing'));
			$data['id_leasing_pendukung'] 						= $leasing;	
			}
			
			//$data['judul'] 						= $this->input->post('judul');	
			$data['latar_belakang'] 					= $this->input->post('latar_belakang');	
			$data['isi'] 							= $this->input->post('isi');	
			$data['penutup'] 							= $this->input->post('penutup');	
			$data['jenis_proposal'] 			= $this->input->post('jenis_proposal');			
			$data['target_penjualan'] 			= $this->input->post('target_penjualan');			
			$data['lokasi_event'] 			= $this->input->post('lokasi_event');			
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$data['status']				= 'input';
			$data['id_dealer'] = $this->m_admin->cari_dealer();		
			$this->m_admin->insert($tabel_header,$data);
			$last_header = $this->db->query("SELECT * FROM $tabel_header where created_by = '$login_id' AND status='input' order by id_proposal DESC limit 0,1");
			$rincian = $this->db->query("SELECT * FROM $table_detail where created_by = '$login_id' AND status='new' AND id_proposal is null ");
			if ($rincian->num_rows() > 0) {
				$last_header = $last_header->row();
				foreach ($rincian->result() as $key => $value) {
					$dt_rincian[$key] = array(
							   'id_rincian' => $value->id_rincian,
							   'id_proposal' => $last_header->id_proposal,
							   'status' => 'input'
				);
				 
				 $this->db->update_batch($table_detail, $dt_rincian, 'id_rincian');
			}
			}
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/proposal/add'>";
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel_header			= $this->table_header;
		$table_detail			= $this->table_detail;
		$pk					= $this->pk;
			$id_proposal 	= $this->input->post('id_proposal');
			$data['id_program'] 	= $this->input->post('id_program');
			$data['nama_program'] 	= $this->input->post('nama_program');
			$data['tema_program'] 					= $this->input->post('tema_program');	
			$data['tgl_mulai'] 			= $this->input->post('tgl_mulai');	
			$data['tgl_selesai'] 			= $this->input->post('tgl_selesai');	
			$data['md'] 						= $this->input->post('md');
			$data['dealer'] 						= $this->input->post('dealer');
			$data['ahm'] 						= $this->input->post('ahm');
			$data['lainnya'] 						= $this->input->post('lainnya');
			if ($this->input->post('leasing')!=null) {
				$leasing = implode(',', $this->input->post('leasing'));
			$data['id_leasing_pendukung'] 						= $leasing;	
			}
			
			//$data['judul'] 						= $this->input->post('judul');	
			$data['latar_belakang'] 					= $this->input->post('latar_belakang');	
			$data['isi'] 							= $this->input->post('isi');	
			$data['penutup'] 							= $this->input->post('penutup');				
			$data['target_penjualan'] 			= $this->input->post('target_penjualan');			
			$data['lokasi_event'] 			= $this->input->post('lokasi_event');			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;				
			
			$this->m_admin->update($tabel_header,$data,"id_proposal",$id_proposal);
			$last_header = $this->db->query("SELECT * FROM $tabel_header where created_by = '$login_id' AND status='input' order by id_proposal DESC limit 0,1");
			$rincian = $this->db->query("SELECT * FROM $table_detail where created_by = '$login_id' AND status='new' AND id_proposal is null ");
			if ($rincian->num_rows() > 0) {
				$last_header = $last_header->row();
				foreach ($rincian->result() as $key => $value) {
					$dt_rincian[$key] = array(
							   'id_rincian' => $value->id_rincian,
							   'id_proposal' => $id_proposal,
							   'status' => 'input'
				);
				 
				 $this->db->update_batch($table_detail, $dt_rincian, 'id_rincian');
			}
			}
			$_SESSION['pesan'] 	= "Data has been update successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/proposal'>";
	}
	public function print_proposal($id)
	{
	  
		$pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
       // head
	  $pdf->SetFont('ARIAL','B',18);
	  $pdf->Cell(190, 5, 'Proposal', 0, 1, 'C');
	  $pdf->SetFont('ARIAL','',11);
	  $pdf->Cell(50, 5, 'Main Dealer: PT.Sinar Sentosa Primatama', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Telp: 0741-61551', 0, 1, 'L');
	  $pdf->Line(11, 31, 200, 31);
	   
	  $pdf->Image(base_url().'/assets/panel/images/logo_sinsen.jpg', 150, 15, 50);
	   
	  
	  $pdf->Output(); 
	}
}