<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemenuhan_po_urgent extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"pemenuhan_po_urgent";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "Pemenuhan PO Urgent dari Dealer";

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
		$data['dt_pemenuhan_po'] = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent LEFT JOIN ms_dealer ON tr_pemenuhan_po_urgent.id_dealer = ms_dealer.id_dealer 
			WHERE status_pemenuhan = 'input'");			
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->template($data);	
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "detail";			
		$id 						= $this->input->get("id");
		$data['dt_sql'] = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent LEFT JOIN ms_dealer ON tr_pemenuhan_po_urgent.id_dealer = ms_dealer.id_dealer 
			WHERE id_pemenuhan_po_urgent = '$id'");	
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->template($data);	
	}
	public function t_detail(){		
		$data['isi'] 		= "tes";
		$id_dealer = $this->input->post('id_dealer');
		$data['sql'] = $this->db->query("SELECT tr_part_request_nrfs.*, ms_part.id_part, SUM(tr_dokumen_nrfs_part.qty_part) AS qty_part, ms_part.nama_part,ms_part.harga_md_dealer,ms_part.harga_dealer_user FROM tr_part_request_nrfs LEFT JOIN tr_dokumen_nrfs_part ON tr_part_request_nrfs.dokumen_nrfs_id = tr_dokumen_nrfs_part.dokumen_nrfs_id 
							LEFT JOIN ms_part ON tr_dokumen_nrfs_part.id_part = ms_part.id_part 
							WHERE id_dealer = '$id_dealer' AND status_request = 'closed'
							GROUP BY tr_dokumen_nrfs_part.id_part");
		$this->load->view('h3/t_pemenuhan_po_urgent',$data);
	}
	public function cari_dealer(){
		$id_dealer = $this->input->post('id_dealer');
		$cek = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer);
		if($cek->num_rows() > 0){
			$rt = $cek->row();
			$id_dealer = $rt->id_dealer;
			$kode_dealer_md = $rt->kode_dealer_md;
			$alamat = $rt->alamat;
		}else{			
			$id_dealer = "";
			$kode_dealer_md = "";
			$alamat = "";
		}
		echo $id_dealer."|".$kode_dealer_md."|".$alamat;
	}
	public function detail_popup()
	{				
		$id_part = $this->input->post("id_part");
		$request_id = $this->input->post("request_id");
		$data['isi']    = $this->page;	
		$data['dt_sql']	= $this->db->query("SELECT tr_part_request_nrfs.*, ms_part.id_part, tr_dokumen_nrfs_part.qty_part AS qty_part, ms_part.nama_part,ms_part.harga_md_dealer,ms_part.harga_dealer_user FROM tr_part_request_nrfs LEFT JOIN tr_dokumen_nrfs_part ON tr_part_request_nrfs.dokumen_nrfs_id = tr_dokumen_nrfs_part.dokumen_nrfs_id 
							LEFT JOIN ms_part ON tr_dokumen_nrfs_part.id_part = ms_part.id_part
							WHERE ms_part.id_part = '$id_part' AND tr_part_request_nrfs.request_id = '$request_id'");
		$data['title']	= $this->title;						
		$data['request_id']	= $request_id;						
		$this->load->view("h3/t_pemenuhan_detail_popup.php",$data);		
	}

	public function pemenuhan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "pemenuhan";			
		$id 						= $this->input->get('id');
		$data['dt_paket'] = $this->db->query("SELECT * FROM tr_po_aksesoris_detail INNER JOIN ms_part ON tr_po_aksesoris_detail.id_part=ms_part.id_part 
				WHERE no_po_aksesoris = '$id'");									
		$data['dt_pemenuhan_po'] = $this->db->query("SELECT * FROM tr_po_aksesoris WHERE no_po_aksesoris = '$id'");									
		$this->template($data);	
	}	
	public function cari_id(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent ORDER BY id_pemenuhan_po_urgent DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_pemenuhan_po_urgent)-3;
			$id 	= substr($row->id_pemenuhan_po_urgent,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "PODU/".$kode1;
		}else{
			$kode = "PODU/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}
	public function cari_no_sim(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_so_part ORDER BY no_so_part DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_so_part)-3;
			$id 	= substr($row->no_so_part,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "SOSM/".$kode1;
		}else{
			$kode = "SOSM/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}
	public function cari_no_spare(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_so_spare ORDER BY no_so_spare DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_so_spare)-3;
			$id 	= substr($row->no_so_spare,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "SOSP/".$kode1;
		}else{
			$kode = "SOSP/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}
	public function cari_no_oil(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_so_oil ORDER BY no_so_oil DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_so_oil)-3;
			$id 	= substr($row->no_so_oil,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "SOIL/".$kode1;
		}else{
			$kode = "SOIL/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		//$id_pemenuhan_po_urgent 				= "PODU/190715/001";
		$id_pemenuhan_po_urgent 				= $this->cari_id();
		$da['id_pemenuhan_po_urgent'] 	= $id_pemenuhan_po_urgent;
		$da['tgl_pemenuhan'] 					= $tgl;				
		$da['id_dealer']	= $this->input->post("id_dealer");				
		$da['status_pemenuhan'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 			
			$id_part 						= $_POST["id_part_".$i];			
			$data['id_pemenuhan_po_urgent'] 		= $id_pemenuhan_po_urgent;
			$data['id_part'] 		= $id_part;				
			$data['qty'] 		= $_POST["qty_".$i];												
			$data['harga'] 		= $_POST["harga_".$i];												
			$data['dokumen_nrfs_id'] 			= $_POST["dokumen_nrfs_id_".$i];						

			$cek = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent_detail WHERE id_part = '$id_part' AND id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent'");
			if($cek->num_rows() > 0){						
				$t = $cek->row();
				$this->m_admin->update("tr_pemenuhan_po_urgent_detail",$data,"id_pemenuhan_po_urgent",$t->id_pemenuhan_po_urgent);								
			}else{
				$this->m_admin->insert("tr_pemenuhan_po_urgent_detail",$data);								
			}						

		}
		

		$ce = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent WHERE id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_pemenuhan_po_urgent",$da,"id_pemenuhan_po_urgent",$id_pemenuhan_po_urgent);								
		}else{
			$this->m_admin->insert("tr_pemenuhan_po_urgent",$da);								
		}
		
		$cek_sim = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent_detail INNER JOIN ms_sim_part ON tr_pemenuhan_po_urgent_detail.id_part = ms_sim_part.id_part
			WHERE tr_pemenuhan_po_urgent_detail.id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent' AND (tr_pemenuhan_po_urgent_detail.create_so <> 'ya' OR tr_pemenuhan_po_urgent_detail.create_so IS NULL)");
		$id_dealer = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent WHERE id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent'")->row()->id_dealer;
		if($cek_sim->num_rows() > 0){
			$no_so_part_sim = $this->cari_no_sim();
			$ds2['no_so_part'] = $ds['no_so_part'] = $no_so_part_sim;
			foreach ($cek_sim->result() as $sim) {
				$ds['id_part'] = $sim->id_part;
				$ds['het'] = $sim->harga;
				$ds['qty_order'] = $sim->qty;
				$this->m_admin->insert("tr_so_part_detail",$ds);
				$this->db->query("UPDATE tr_pemenuhan_po_urgent_detail SET create_so = 'ya' WHERE id_part = '$sim->id_part' AND id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent'");
			}			
			$ds2['tipe_po'] = 'Urgent';
			$ds2['tgl_so'] 	= $tgl;
			$ds2['masa_berlaku'] 	= "30";
			$ds2['id_dealer'] 	= $id_dealer;
			$ds2['status_so'] 	= "input";
			$ds2['created_at'] 			= $waktu;		
			$ds2['created_by'] 			= $login_id;		
			$this->m_admin->insert("tr_so_part",$ds2);
		}

		$cek_oil = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent_detail INNER JOIN ms_part ON tr_pemenuhan_po_urgent_detail.id_part = ms_part.id_part
			WHERE tr_pemenuhan_po_urgent_detail.id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent' AND ms_part.kelompok_part = 'OIL' AND (tr_pemenuhan_po_urgent_detail.create_so <> 'ya' OR tr_pemenuhan_po_urgent_detail.create_so IS NULL)");		
		if($cek_oil->num_rows() > 0){
			$no_so_oil = $this->cari_no_oil();
			$do2['no_so_oil'] = $do['no_so_oil'] = $no_so_oil;
			foreach ($cek_oil->result() as $oil) {
				$do['id_part'] = $oil->id_part;
				$do['het'] = $oil->harga;
				$do['qty_order'] = $oil->qty;
				$this->m_admin->insert("tr_so_oil_detail",$do);
				$this->db->query("UPDATE tr_pemenuhan_po_urgent_detail SET create_so = 'ya' WHERE id_part = '$oil->id_part' AND id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent'");
			}			
			$do2['tipe_po'] = 'Reguler';
			$do2['tgl_so'] 	= $tgl;
			$do2['masa_berlaku'] 	= "30";
			$do2['id_dealer'] 	= $id_dealer;
			$do2['status_so'] 	= "input";
			$do2['created_at'] 			= $waktu;		
			$do2['created_by'] 			= $login_id;		
			$this->m_admin->insert("tr_so_oil",$do2);
		}

		$cek_non = $this->db->query("SELECT * FROM tr_pemenuhan_po_urgent_detail INNER JOIN ms_part ON tr_pemenuhan_po_urgent_detail.id_part = ms_part.id_part
			WHERE tr_pemenuhan_po_urgent_detail.id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent' AND ms_part.kelompok_part <> 'OIL' 
			AND (tr_pemenuhan_po_urgent_detail.create_so <> 'ya' OR tr_pemenuhan_po_urgent_detail.create_so IS NULL)	
			AND tr_pemenuhan_po_urgent_detail.id_part NOT IN (SELECT id_part FROM ms_sim_part)");		
		if($cek_non->num_rows() > 0){
			$no_so_spare = $this->cari_no_spare();
			$dn2['no_so_spare'] = $dn['no_so_spare'] = $no_so_spare;
			foreach ($cek_non->result() as $non) {
				$dn['id_part'] = $non->id_part;
				$dn['het'] = $non->harga;
				$dn['qty_order'] = $non->qty;
				$this->m_admin->insert("tr_so_spare_detail",$dn);
				$this->db->query("UPDATE tr_pemenuhan_po_urgent_detail SET create_so = 'ya' WHERE id_part = '$non->id_part' AND id_pemenuhan_po_urgent = '$id_pemenuhan_po_urgent'");
			}			
			$dn2['tipe_po'] = 'Urgent';
			$dn2['tgl_so'] 	= $tgl;
			$dn2['masa_berlaku'] 	= "30";
			$dn2['id_dealer'] 	= $id_dealer;
			$dn2['status_so'] 	= "input";
			$dn2['created_at'] 			= $waktu;		
			$dn2['created_by'] 			= $login_id;		
			$this->m_admin->insert("tr_so_spare",$dn2);
		}	
		



		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/pemenuhan_po_urgent'>";
	}



	public function approve()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->get("id");		
		// $data['status_pem'] 			= "approved";			
		// $data['updated_at']				= $waktu;		
		// $data['updated_by']				= $login_id;		
		// $this->m_admin->update($tabel,$data,$pk,$id);

		$this->db->query("UPDATE tr_pemenuhan_po SET status_pem = 'approved' WHERE no_po_aksesoris = '$id'");
		$this->db->query("UPDATE tr_po_aksesoris SET status_po = 'terpenuhi' WHERE no_po_aksesoris = '$id'");
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/pemenuhan_po'>";		
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/pemenuhan_po'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	public function cek_no_sj(){		
		$th     = date("Y");
		$bln    = date("m");
		$th_bln = date("Y-m");
		$waktu  = gmdate("Y-m-d H:i:s", time()+60*60*7);				
		$pr_num = $this->db->query("SELECT *,LEFT(tgl_po,7) as tgl_po_alias FROM tr_po_aksesoris WHERE LEFT(tgl_po,7) = '$th_bln' ORDER BY no_surat_jalan DESC LIMIT 0,1");
			$row 	= $pr_num->row();				

		if ($pr_num->num_rows() > 0) {
			$row=$pr_num->row();
			if ($th_bln != $row->tgl_po_alias) {
				$kode = 'SJ/POACC/'.$th.$bln.'001';
			}else{
				$kode = 'SJ/POACC/'.$th.$bln.'001';
				$old_numb = substr($row->no_surat_jalan, -3);
				$kode = 'SJ/POACC/'.$th.$bln.sprintf("%03d", $old_numb+1);
			}
		}else{
			$kode = 'SJ/POACC/'.$th.$bln.'001';
		}
		return $kode;
	}

	function cetak_sj()
	{
		$this->load->library('mpdf_l');
		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
		$id = $this->input->get('id');
		$cek_sj = $this->db->get_where('tr_po_aksesoris',['no_po_aksesoris'=>$id]);
		if ($cek_sj->num_rows()==0) {
			redirect('h3/pemenuhan_po');
		}
		$row = $cek_sj->row();
		if ($row->no_surat_jalan==null) {
			$dt_upd['no_surat_jalan'] = $this->cek_no_sj();
			$dt_upd['cetak_sj_ke']    = 1;		
			$dt_upd['tgl_cetak_sj']   = $waktu;			
			$this->db->update('tr_po_aksesoris',$dt_upd,['no_po_aksesoris'=>$id]);
		}else{
			$dt_upd['cetak_sj_ke']    = $row->cetak_sj_ke+1;		
			$dt_upd['tgl_cetak_sj']   = $waktu;			
			$this->db->update('tr_po_aksesoris',$dt_upd,['no_po_aksesoris'=>$id]);
		}
		
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion =true;  // Set by default to TRUE
		$mpdf->charset_in               ='UTF-8';
		$mpdf->autoLangToFont           = true;
		$data['cetak']					= 'cetak_sj';
		$data['po']						= $cek_sj->row();
		$html                           = $this->load->view('h3/pemenuhan_po_cetak', $data, true);
        // render the view into HTML
        $mpdf->WriteHTML($html);
        // write the HTML into the mpdf
        $output = 'cetak_surat_jalan.pdf';
        $mpdf->Output("$output", 'I');
	}


}