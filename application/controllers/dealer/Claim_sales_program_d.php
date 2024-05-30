<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Claim_sales_program_d extends CI_Controller {
    var $tables =   "tr_prospek";	
	var $folder =   "dealer";
	var $page	=	"claim_sales_program_d";
    var $pk     =   "id_prospek";
    var $title  =   "Claim Sales Program";
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
		$data['dt_prospek'] = $this->m_admin->getAll($this->tables);		
        $id_dealer = $data['id_dealer'] = $this->m_admin->cari_dealer();
		//$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		// $data['dt_so'] = $this->db->query("SELECT * FROM tr_sales_order 
		// 			inner join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
		// 			inner join tr_scan_barcode on tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
		// 			inner join tr_sales_program on tr_sales_order.program_umum = tr_sales_program.id_sales_program
		// 	WHERE status_so = 'so_invoice' and tr_sales_order.program_umum is not null and tr_spk.id_dealer='$id_dealer' ");						
		$data['dt_so']=$this->db->query("SELECT *,tr_spk.program_umum FROM tr_sales_order 
					inner join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
		 			inner join tr_scan_barcode on tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
					WHERE (no_bastk is not null OR no_bastk<>'') AND (program_umum is not null OR program_umum<>'') AND tr_spk.id_dealer='$id_dealer'
					UNION
					SELECT *,tr_spk.program_gabungan as program_umum FROM tr_sales_order 
					inner join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
		 			inner join tr_scan_barcode on tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
					WHERE (no_bastk is not null OR no_bastk<>'') AND (program_gabungan is not null) AND tr_spk.id_dealer='$id_dealer'
					");
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function cari_id(){		
		$th					= date("Y");		
		$bln					= date("m");		
		$tgl_					= date("d");		
		$tgl					= date("Y-m-d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_claim_dealer WHERE LEFT(created_at,10)='$tgl' ORDER BY id_claim DESC LIMIT 0,1");						
       
       if($pr_num->num_rows()>0){
       	$row 	= $pr_num->row();		
       	$id = substr($row->id_claim,9,4); 
        $kode = $th.$bln.$tgl_.sprintf("%04d", $id+1);
		}
		else{
			$kode = $th.$bln.$tgl_.'0001';
		} 
		return $kode;
	}
	
	// public function ajukan()
	// {				
	// 	if (!$this->input->post('submit')) {
	// 		$data['isi']    = $this->page;
	// 	$data['title']	= $this->title;															
	// 		$data['set']		= "ajukan";					
	// 		$no_spk 			= $this->input->get('id');
	// 		$id_program_md 			= $this->input->get('id_program_md');
	// 		$dt_so=$this->db->query("SELECT tr_spk.*,tr_sales_order.*,tr_scan_barcode.*, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,
	// 			(CASE
 //    				WHEN program_umum='$id_program_md' THEN 
	// 				    program_umum
	// 				    ELSE program_gabungan
	// 				END) as program_umum
	// 		 FROM tr_sales_order 
	// 					inner join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
	// 		 			inner join tr_scan_barcode on tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
	// 		 			left join ms_tipe_kendaraan on tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	// 		 			left join ms_warna on tr_spk.id_warna = ms_warna.id_warna
	// 		 			WHERE tr_sales_order.no_spk = '$no_spk' 
	// 		 			");	
	// 		if ($dt_so->num_rows()>0) {
	// 			$data['dt_so'] = $dt_so;
	// 			$dt_so 			= $dt_so->row();
	// 			if ($dt_so->program_umum!='' OR $dt_so->program_umum!=null) {
	// 				$this->template($data);	
	// 			}
	// 			else{
	// 				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";
	// 			}
	// 		}else{
	// 			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";
	// 		}
	// 	}else{
	// 	$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id	= $this->session->userdata('id_user');	
	// 	$id_dealer	= $this->input->post('id_dealer');
	// 	$id	= $this->input->post('id');
	// 	$data[0]['id_claim']	 = $this->cari_id();
	// 	$data[0]['id_dealer']	 = $id_dealer;
	// 	$id_program_md = $data[0]['id_program_md']	 = $this->input->post('id_program_md');
	// 	$data[0]['id_sales_order']	 = $this->input->post('id_sales_order');
	// 	$data[0]['created_by']	 = $login_id;
	// 	$data[0]['created_at']	 = $waktu;
	// 	$data[0]['tgl_ajukan_claim']	 = date('Y-m-d');
	// 	$data[0]['status']	 	 = 'ajukan';
	// 	//$id_program_md = '181000024-SP-001'; //coba
	// 	$get_syarat = $this->db->query("SELECT * FROM tr_sales_program_syarat WHERE id_program_md='$id_program_md' ");
	// 	$id = $this->input->post('cek');
	// 	if ($get_syarat->num_rows()>0) {
	// 		foreach ($get_syarat->result() as$key=> $rs) {
	// 			if (in_array($rs->id, $id)){
	// 				$dt_detail[$key]['checklist_dealer'] =1;
	// 			}else{
	// 				$dt_detail[$key]['checklist_dealer'] =0;
	// 			}
	// 			$dt_detail[$key]['id_syarat_ketentuan'] = $rs->id;
	// 			$dt_detail[$key]['id_claim'] = $data[0]['id_claim'];
	// 		}
	// 	}
	// 	$this->db->trans_begin();
	// 	$this->db->insert_batch('tr_claim_dealer',$data); 
	// 	if (isset($dt_detail)) {
	// 		$this->db->insert_batch('tr_claim_dealer_syarat',$dt_detail); 
	// 	}
	// 	if ($this->db->trans_status() === FALSE)
 //            {
 //                    $this->db->trans_rollback();
 //                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
	// 				$_SESSION['tipe'] 		= "danger";
	// 				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";	
 //            }
 //        else
 //            {
 //                $this->db->trans_commit();
 //                $_SESSION['pesan'] 		= "Data has been saved successfully";
	// 			$_SESSION['tipe'] 		= "success";
	// 			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";	
 //            }
	// 	 	}
	// }
	public function ulang()
	{				
		if (!$this->input->post('submit')) {
			$data['isi']    = $this->page;
		$data['title']	= $this->title;															
			$data['set']		= "ulang";					
			$no_spk 			= $this->input->get('id');
			$data['id_program_md'] 			= $this->input->get('id_program_md');
			$dt_so=$this->db->query("SELECT tr_spk.*,tr_sales_order.*,tr_scan_barcode.*, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_sales_order 
						inner join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
			 			inner join tr_scan_barcode on tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			 			left join ms_tipe_kendaraan on tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			 			left join ms_warna on tr_spk.id_warna = ms_warna.id_warna
			 			WHERE tr_sales_order.no_spk = '$no_spk'
			 			");	
			if ($dt_so->num_rows()>0) {
				$data['dt_so'] = $dt_so;
				$dt_so 			= $dt_so->row();
				if ($dt_so->program_umum!='' OR $dt_so->program_umum!=null) {
					$data['syarat'] = $this->db->query("SELECT * FROM tr_claim_dealer_syarat 
						inner join tr_claim_dealer on tr_claim_dealer_syarat.id_claim=tr_claim_dealer.id_claim
						inner join tr_sales_program_syarat on tr_claim_dealer_syarat.id_syarat_ketentuan=tr_sales_program_syarat.id
						WHERE id_sales_order='$dt_so->id_sales_order'
						");
					$this->template($data);	
				}
				else{
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";
				}
			}else{
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";
			}
		}else{
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	
		$id_dealer	= $this->input->post('id_dealer');
		//$id	= $this->input->post('id');
		$id = $data['id_claim']	 = $this->input->post('id_claim');
		$data['updated_by']	 = $login_id;
		$data['updated_at']	 = $waktu;
		$data['tgl_ajukan_claim']	 = date('Y-m-d');
		$data['status']	 	 = 'ulang';
		$this->db->trans_begin();
		$this->db->update('tr_claim_dealer', $data, array('id_claim' => $id));
		if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";	
            }
        else
            {
                $this->db->trans_commit();
                $_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim_sales_program_d'>";	
            }
		 	}
	}
	
	public function save_pu(){
		$id_penerimaan_unit		= $this->input->post('id_penerimaan_unit');			
		$no_shipping_list			= $this->input->post('no_shipping_list');			
		$data['id_penerimaan_unit']		= $this->input->post('id_penerimaan_unit');			
		$data['no_shipping_list']			= $this->input->post('no_shipping_list');
		$c = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id_penerimaan_unit' AND no_shipping_list = '$no_shipping_list'");
		if($c->num_rows() > 0){
			echo "no";
		}else{
			$cek2 = $this->m_admin->insert("tr_penerimaan_unit_detail",$data);						
			echo "ok";
		}							
	}	
	public function delete_pu(){
		$id = $this->input->post('id_penerimaan_unit_detail');		
		$this->db->query("DELETE FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit_detail = '$id'");			
		echo "nihil";
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
			$data['id_penerimaan_unit'] 	= $this->input->post('id_penerimaan_unit');
			$data['no_antrian'] 					= $this->input->post('no_antrian');	
			$data['no_surat_jalan'] 			= $this->input->post('no_surat_jalan');	
			$data['tgl_surat_jalan'] 			= $this->input->post('tgl_surat_jalan');	
			$data['ekspedisi'] 						= $this->input->post('ekspedisi');	
			$data['no_polisi'] 						= $this->input->post('no_polisi');	
			$data['nama_driver'] 					= $this->input->post('nama_driver');	
			$data['no_telp'] 							= $this->input->post('no_telp');	
			$data['gudang'] 							= $this->input->post('gudang');	
			$data['tgl_penerimaan'] 			= $this->input->post('tgl_penerimaan');	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/penerimaan_unit/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cetak_striker()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak Ulang Stiker";	
		$no_shipping_list 	= $this->input->get("id");	
		$data['set']		= "cetak";
		$data['dt_shipping_list'] = $this->db->query("SELECT * FROM tr_shipping_list INNER JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna 
					WHERE tr_shipping_list.no_shipping_list = '$no_shipping_list'");				
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");								
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function list_ksu(){
		$data['isi']    = $this->page;		
		$data['title']	= "List KSU";															
		$data['set']	= "list_ksu";
		//$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);										
	}

// 	function cek_spk()
// 	{
// 		$result = $this->db->query("SELECT tr_sales_order.id_sales_order,kode_dealer_md,nama_dealer,no_mesin,tgl_cetak_invoice,tgl_create_ssu,RIGHT(tgl_create_ssu,9) AS jam_create_ssu,cetak_invoice_ke 
// FROM tr_sales_order 
// JOIN ms_dealer ON ms_dealer.`id_dealer`=tr_sales_order.id_dealer
// WHERE LEFT(tgl_create_ssu,10)<>tgl_cetak_invoice AND LEFT(tgl_cetak_invoice,7)='2019-12'
// ")->result();
// 		foreach ($result as $rs) {
// 			$upd[]=['id_sales_order'=>$rs->id_sales_order,
// 					// 'tgl_cetak_invoice'=>$rs->tgl_cetak_invoice,
// 					// 'xx'=>$rs->tgl_create_ssu,
// 					'tgl_create_ssu'=>$rs->tgl_cetak_invoice.' '.$rs->jam_create_ssu,
// 				   ];
// 		}
// 		$this->db->update_batch('tr_sales_order',$upd,'id_sales_order');
// 	}
}