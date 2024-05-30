<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_program extends CI_Controller {

    var $tables =   "tr_sales_program";	
		var $folder =   "h1";
		var $page		=		"sales_program";
    var $pk     =   "id_sales_program";
    var $title  =   "Sales Program";

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
		$data['dt_sales']	= $this->m_admin->getAll($this->tables);
		$this->template($data);			
	}


	public function add()
	{				
		$login_id	= $this->session->userdata('id_user');	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";		
		$this->db->trans_begin();
				$this->db->query("DELETE FROM tr_sales_program_gabungan WHERE status='new' AND created_by='$login_id'");
				$this->db->query("DELETE FROM tr_sales_program_tipe WHERE status='new' AND created_by='$login_id'");
				$this->db->query("DELETE FROM tr_sales_program_syarat WHERE status='new' AND created_by='$login_id'");
			if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
            }
            else
            {
                $this->db->trans_commit();
				$this->template($data);			
            }
	}

	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";
		$id_program_md = $this->input->get('id');
		$dt_sales	= $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md='$id_program_md'");
		if ($dt_sales->num_rows() > 0) {
			$data['row'] = $dt_sales->row();
		}else{
			//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
		}
		$this->template($data);			
	}
	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;															
		$data['set']		= "edit";
		$id_program_md = $this->input->get('id');
		$data['dt_sales']	= $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md='$id_program_md'");		
		$this->template($data);			
	}


	public function cari_id_lama(){		
		$id_p = $this->input->post('id_jenis_sales_program');

		$th 						= date("y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_sales_program ORDER BY id_program_md DESC LIMIT 0,1");						
       
       if($pr_num->num_rows()>0){
       	$row 	= $pr_num->row();		
       	$id = substr($row->id_program_md,4,5); 
        $kode = $th.$bln.sprintf("%05d", $id+1).'-'.$id_p;
		}
		/*if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();		
			echo $row->id_program_md;		
			 $pan  = strlen($row->id_program_md)-5;
			$id 	= substr($row->id_program_md,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th.$bln."0000".$id."-".$id_p;          
	  }elseif($id>9 && $id<=99){
					$kode1 = $th.$bln."000".$id."-".$id_p;                    
	  }elseif($id>99 && $id<=999){
					$kode1 = $th.$bln."00".$id."-".$id_p;          					          
	  }elseif($id>999){
					$kode1 = $th.$bln."0".$id."-".$id_p;                    
	  }
			$kode = $kode1;
		}*/

		else{
			$kode = $th.$bln."00001-".$id_p;
		} 
		echo $kode;
	}

	public function cari_id(){			
		$id_p = $this->input->post('id_jenis_sales_program');

		$th 						= date("y");
		$thn 						= date("Y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_sales_program where left(created_at,4) = '$thn' ORDER BY id_program_md DESC LIMIT 0,1");						

		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();		
			$pan  = strlen($row->id_program_md)-5;
			$id 	= substr($row->id_program_md,4,5)+1;	

			if($id < 10){
					$kode1 = $th.$bln."0000".$id."-".$id_p;          
	  		}elseif($id>9 && $id<=99){
					$kode1 = $th.$bln."000".$id."-".$id_p;                    
	  		}elseif($id>99 && $id<=999){
					$kode1 = $th.$bln."00".$id."-".$id_p;          					          
	  		}elseif($id>999){
					$kode1 = $th.$bln."0".$id."-".$id_p;                    
	  		}
			$kode = $kode1;
		}else{
			$kode = $th.$bln."00001-".$id_p;
		} 
		echo $kode;

	}

	public function t_tipe(){
		$id = $this->input->post('id_program_md');
		$dq = "SELECT * FROM tr_sales_program_tipe INNER JOIN ms_tipe_kendaraan ON tr_sales_program_tipe.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_sales_program_tipe.id_warna=ms_warna.id_warna
						WHERE tr_sales_program_tipe.id_program_md = '$id'";
		$data['dt_tipe'] = $this->db->query($dq);		
		$this->load->view('h1/t_sales_program_tipe',$data);
	}

	public function getJenisProgram(){
		$data['jenis_sales_program'] = $this->input->post('jenis_sales_program');
		$data['id_program_md'] = $this->input->post('id_program_md');
		$this->load->view('h1/t_sales_program_tipe_new',$data);
	}
	public function getJenisProgram_edit(){
		$data['id_jenis_sales_program'] = $this->input->post('id_jenis_sales_program');
		$data['jenis_sales_program'] = $this->input->post('jenis_sales_program');
		$data['id_program_md'] = $this->input->post('id_program_md');
		$this->load->view('h1/t_sales_program_tipe_ubah',$data);
	}

	public function editDetailKendaraan(){
		$id_sales_program_tipe = $this->input->post('id_sales_program_tipe');
		$data['get_tipe'] = $this->db->query("SELECT * FROM tr_sales_program_tipe 
			left join ms_tipe_kendaraan on tr_sales_program_tipe.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			WHERE id_sales_program_tipe = '$id_sales_program_tipe' ");
		$data['id_sales_program_tipe'] = $id_sales_program_tipe;
		$tipe = $data['get_tipe']->row()->id_tipe_kendaraan;
		$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$tipe'
				";
		$dt_warna = $this->db->query($dq);		
		if ($dt_warna->num_rows() > 0) {
			$data['dt_warna'] = $dt_warna->result();
		}

		$this->load->view('h1/t_sales_program_tipe_edit',$data);
	}

	public function save_tipe(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$id_warna				= $this->input->post('id_warna');
		$id_warna = explode(',', $id_warna)	;				
		if (in_array('semua_warna', $id_warna)) {
			$id_tipe_kendaraan = $this->input->post('kode_type');
			$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
					inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
					WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
					";
			$dt_warna = $this->db->query($dq);		
				if ($dt_warna->num_rows() > 0) {
					foreach ($dt_warna->result() as $key=>$res) {
						$warna[$key] = $res->id_warna;
					}
				}
				$warna = implode(',', $warna);
		}else{
			$warna = implode(',', $id_warna);
		}

		if ($this->input->post('tahun_produksi')=='') {
			$tahun_produksi = 'Semua Tahun';
		}else{
			$tahun_produksi = $this->input->post('tahun_produksi');
		}

		$data['id_program_md']			= $this->input->post('id_program_md');			
		$data['id_tipe_kendaraan']		= $this->input->post('kode_type');			
		$data['ahm_cash']				= $this->input->post('ahm_cash');					
		$data['ahm_kredit']				= $this->input->post('ahm_kredit');					
		$data['id_warna']				= $warna;					
		$data['md_cash']				= $this->input->post('md_cash');					
		$data['md_kredit']				= $this->input->post('md_kredit');					
		$data['dealer_cash']			= $this->input->post('dealer_cash');					
		$data['dealer_kredit']			= $this->input->post('dealer_kredit');					
		$data['other_cash']				= $this->input->post('other_cash');					
		$data['other_kredit']			= $this->input->post('other_kredit');					
		$data['metode_pembayaran']		= $this->input->post('metode_pembayaran');			
		$data['jenis_bayar_dibelakang']	= $this->input->post('jenis_bayar_dibelakang');	
		$data['tahun_produksi']			= $tahun_produksi;					
		$data['jenis_barang']			= $this->input->post('jenis_barang');					
		$data['qty_minimum']			= $this->input->post('qty_minimum');					
		$data['status']					= 'new';					
		$data['created_by']					= $login_id;					
		$data['created_at']					= $waktu;					
		
		$this->m_admin->insert("tr_sales_program_tipe",$data);		
		echo "nihil";
	}

	public function saveEditDetail(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$id_warna				= $this->input->post('id_warna');
		$id_sales_program_tipe				= $this->input->post('id_sales_program_tipe');
		$id_warna = explode(',', $id_warna)	;				
		if (in_array('semua_warna', $id_warna)) {
			$id_tipe_kendaraan = $this->input->post('kode_type');
			$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
					inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
					WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
					";
			$dt_warna = $this->db->query($dq);		
				if ($dt_warna->num_rows() > 0) {
					foreach ($dt_warna->result() as $key=>$res) {
						$warna[$key] = $res->id_warna;
					}
				}
				$warna = implode(',', $warna);
		}else{
			$warna = implode(',', $id_warna);
		}

		if ($this->input->post('tahun_produksi')=='') {
			$tahun_produksi = 'Semua Tahun';
		}else{
			$tahun_produksi = $this->input->post('tahun_produksi');
		}

		$id_program_md = $data['id_program_md']			= $this->input->post('id_program_md');			
		$data['id_tipe_kendaraan']		= $this->input->post('kode_type');			
		$data['ahm_cash']				= $this->input->post('ahm_cash');					
		$data['ahm_kredit']				= $this->input->post('ahm_kredit');					
		$data['id_warna']				= $warna;					
		$data['md_cash']				= $this->input->post('md_cash');					
		$data['md_kredit']				= $this->input->post('md_kredit');					
		$data['dealer_cash']			= $this->input->post('dealer_cash');					
		$data['dealer_kredit']			= $this->input->post('dealer_kredit');					
		$data['other_cash']				= $this->input->post('other_cash');					
		$data['other_kredit']			= $this->input->post('other_kredit');					
		$data['metode_pembayaran']		= $this->input->post('metode_pembayaran');			
		$data['jenis_bayar_dibelakang']	= $this->input->post('jenis_bayar_dibelakang');	
		$data['tahun_produksi']			= $tahun_produksi;					
		$data['jenis_barang']			= $this->input->post('jenis_barang');					
		$data['qty_minimum']			= $this->input->post('qty_minimum');					
		$data['status']					= 'input';					
		$data['updated_by']					= $login_id;					
		$data['updated_at']					= $waktu;					
		
		$this->m_admin->update("tr_sales_program_tipe",$data,"id_sales_program_tipe",$id_sales_program_tipe);		
		echo "nihil|".$id_program_md;
	}

	public function delete_tipe(){
		$id_sales_program_tipe = $this->input->post('id_sales_program_tipe');		
		$this->db->query("DELETE FROM tr_sales_program_tipe WHERE id_sales_program_tipe = '$id_sales_program_tipe'");			
		echo "nihil";
	}

	public function save_gabungan(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$data['id_program_md']			= $this->input->post('id_program_md');			
		$data['id_program_md_gabungan']			= $this->input->post('id_program_md_gabungan');					
		$data['status']					= 'new';					
		$data['created_by']					= $login_id;					
		$data['created_at']					= $waktu;					
		$this->m_admin->insert("tr_sales_program_gabungan",$data);		
		echo "nihil";
	}

	public function delete_gabungan(){
		$id = $this->input->post('id');		
		$this->db->query("DELETE FROM tr_sales_program_gabungan WHERE id = '$id'");			
		echo "nihil";
	}

	public function save_syarat(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$data['syarat_ketentuan']			= $this->input->post('syarat_ketentuan');	
		$data['id_program_md']			= $this->input->post('id_program_md');	
		$data['status']					= 'new';					
		$data['created_by']					= $login_id;					
		$data['created_at']					= $waktu;					
		$this->m_admin->insert("tr_sales_program_syarat",$data);		
		echo "nihil";
	}

	public function delete_syarat(){
		$id = $this->input->post('id');		
		$this->db->query("DELETE FROM tr_sales_program_syarat WHERE id = '$id'");			
		echo "nihil";
	}


	public function t_dealer(){
		$id = $this->input->post('id_program_md');
		$dq = "SELECT * FROM tr_sales_program_dealer INNER JOIN ms_dealer ON tr_sales_program_dealer.id_dealer=ms_dealer.id_dealer						
						WHERE tr_sales_program_dealer.id_program_md = '$id'";
		$data['dt_dealer'] = $this->db->query($dq);		
		$this->load->view('h1/t_sales_program_dealer',$data);
	}

	public function getWarna(){
		$id_tipe_kendaraan = $this->input->post('kode_type');
		$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
				";
		$dt_warna = $this->db->query($dq);		
		if ($dt_warna->num_rows() > 0) {
			echo "<option value='semua_warna'>Select All</option>";
			foreach ($dt_warna->result() as $res) {
				echo "<option value='$res->id_warna' >$res->warna</option>";
			}
		}
	}


	public function getGabungan(){
		$login_id      = $this->session->userdata('id_user');		
		$periode_awal  = $this->input->post("periode_awal");
		$periode_akhir = $this->input->post("periode_akhir");
		$mode          = $this->input->post("mode");
		$id_program_md = $this->input->post("id_program_md");

		$dq = "SELECT *  from tr_sales_program 
				JOIN tr_sales_program_tipe ON tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md
				WHERE tr_sales_program.id_program_md NOT IN (SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md_gabungan IS NOT NULL) 
				AND tr_sales_program.id_program_md NOT IN (SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md IS NOT NULL)
				AND tr_sales_program_tipe.id_tipe_kendaraan IN (SELECT id_tipe_kendaraan FROM tr_sales_program_tipe 
							 WHERE tr_sales_program_tipe.status='new' 
							 AND tr_sales_program_tipe.created_by='$login_id')
				AND periode_awal='$periode_awal' AND periode_akhir='$periode_akhir'
				ORDER BY tr_sales_program.created_by DESC ";

		$dq = "SELECT *  from tr_sales_program 
				JOIN tr_sales_program_tipe ON tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md
				-- WHERE tr_sales_program.id_program_md NOT IN (SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md_gabungan IS NOT NULL) 
				-- AND tr_sales_program.id_program_md NOT IN (SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md IS NOT NULL)
				WHERE tr_sales_program_tipe.id_tipe_kendaraan IN (SELECT id_tipe_kendaraan FROM tr_sales_program_tipe 
							 WHERE tr_sales_program_tipe.status='new' 
							 AND tr_sales_program_tipe.created_by='$login_id')
				AND periode_awal='$periode_awal' AND periode_akhir='$periode_akhir'
				ORDER BY tr_sales_program.created_by DESC ";
				
		$dq_edit = "SELECT *  from tr_sales_program 
				JOIN tr_sales_program_tipe ON tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md
				-- WHERE tr_sales_program.id_program_md NOT IN (SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md_gabungan IS NOT NULL) 
				-- AND tr_sales_program.id_program_md NOT IN (SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md IS NOT NULL)
				WHERE tr_sales_program_tipe.id_tipe_kendaraan IN (SELECT id_tipe_kendaraan FROM tr_sales_program_tipe 
							 WHERE tr_sales_program_tipe.id_program_md='$id_program_md')
				AND periode_awal='$periode_awal' AND periode_akhir='$periode_akhir'
				AND tr_sales_program.id_program_md!='$id_program_md'
				GROUP BY tr_sales_program.id_program_md
				ORDER BY tr_sales_program.created_by DESC ";
		if($mode == 'edit'){
			$data['sp_gab'] = $this->db->query("SELECT * FROM tr_sales_program_gabungan 
						left join tr_sales_program on tr_sales_program_gabungan.id_program_md_gabungan = tr_sales_program.id_program_md
						WHERE tr_sales_program_gabungan.id_program_md='$id_program_md'");
		$data['dt_sp'] = $this->db->query($dq_edit);			

		}else{		
		$data['dt_sp'] = $this->db->query($dq);			
			$data['sp_gab'] = $this->db->query("
						SELECT * FROM tr_sales_program_gabungan 
						left join tr_sales_program on tr_sales_program_gabungan.id_program_md_gabungan = tr_sales_program.id_program_md
						WHERE tr_sales_program_gabungan.created_by='$login_id' 
						AND tr_sales_program_gabungan.status='new'
						");
		}
		$this->load->view('h1/t_sales_program_gabungan', $data);		
	}
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
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									
			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{
				$this->m_admin->delete("tr_sales_program_tipe","id_program_md",$id);
				$this->m_admin->delete("tr_sales_program_syarat","id_program_md",$id);
				$this->m_admin->delete("tr_sales_program_gabungan","id_program_md",$id);
				$this->m_admin->delete("tr_sales_program_dealer","id_program_md",$id);
				$this->m_admin->delete("tr_sales_program","id_program_md",$id);
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";
		}
	}

	public function getSyarat(){
		$login_id	= $this->session->userdata('id_user');		
		$mode = $this->input->post("mode");
		$id_program_md = $this->input->post("id_program_md");		
		$data['id_program_md'] = $this->input->post("id_program_md");		
		if($mode == 'edit'){			
			$data['sp_syarat'] = $this->db->query("SELECT * FROM tr_sales_program_syarat 
				WHERE tr_sales_program_syarat.id_program_md='$id_program_md'");	
		}else{
			$data['sp_syarat'] = $this->db->query("SELECT * FROM tr_sales_program_syarat 
				WHERE tr_sales_program_syarat.created_by='$login_id' AND tr_sales_program_syarat.status='new' ");	
		}
		$this->load->view('h1/t_sales_program_syarat', $data);
	}

	public function save_dealer(){
		$id_program_md			= $this->input->post('id_program_md');			
		$id_dealer					= $this->input->post('id_dealer');
		$data['id_program_md']		= $this->input->post('id_program_md');					
		$data['kuota']						= $this->input->post('kuota');							
		$id_dealer								= $this->input->post('id_dealer');

		if($id_dealer == 'semua'){
			$dealer = $this->m_admin->getSortCond("ms_dealer","id_dealer","ASC");	
			foreach ($dealer->result() as $dl) {
				$data['id_dealer'] = $dl->id_dealer;
				$cek = $this->db->get_where("tr_sales_program_dealer",array("id_program_md"=>$id_program_md,"id_dealer"=>$dl->id_dealer));
				if($cek->num_rows() > 0){
					$sq = $cek->row();
					$id = $sq->id_sales_program_dealer;
					$this->m_admin->update("tr_sales_program_dealer",$data,"id_sales_program_dealer",$id);			
				}else{
					$this->m_admin->insert("tr_sales_program_dealer",$data);			
				}
			}
		}else{
			$data['id_dealer']				= $this->input->post('id_dealer');	
			$data['ikut'] = "";

			$cek = $this->db->get_where("tr_sales_program_dealer",array("id_program_md"=>$id_program_md,"id_dealer"=>$id_dealer));
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_sales_program_dealer;
				$this->m_admin->update("tr_sales_program_dealer",$data,"id_sales_program_dealer",$id);			
			}else{
				$this->m_admin->insert("tr_sales_program_dealer",$data);			
			}
		}

		echo "nihil";
	}
	public function delete_dealer(){
		$id_sales_program_dealer = $this->input->post('id_sales_program_dealer');		
		$this->db->query("DELETE FROM tr_sales_program_dealer WHERE id_sales_program_dealer = '$id_sales_program_dealer'");			
		echo "nihil";
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$config['upload_path'] 		= './assets/panel/files/';
		$config['allowed_types'] 	= 'doc|docx|pdf|jpg|png|jpeg|bmp|gif';
		$config['max_size']				= '5000';		
				
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('draft_jutlak')){
			$draft_jutlak = "";
		}else{
			$draft_jutlak = $this->upload->file_name;
		}

		$this->upload->initialize($config);
		if(!$this->upload->do_upload('final_jutlak')){
			$final_jutlak = "";
		}else{
			$final_jutlak = $this->upload->file_name;
		}

		$id_program_md 		 			= $this->input->post('id_program_md');		
		$data['id_program_md'] 			= $this->input->post('id_program_md');		
		$data['id_program_ahm'] 		= $this->input->post('id_program_ahm');		
		$data['draft_jutlak'] 			= $draft_jutlak;
		$data['id_jenis_sales_program'] = $this->input->post('id_jenis_sales_program');		
		$data['jenis'] = $this->input->post('jenis');		
		//$data['jenis_bayar'] 				= $this->input->post('jenis_bayar');		
		$data['periode_awal'] 			= $this->input->post('periode_awal');		
		$data['periode_akhir'] 			= $this->input->post('periode_akhir');
		$data['judul_kegiatan'] 		= $this->input->post('judul_kegiatan');
		$data['kuota_program'] 		= $this->input->post('kuota_program');
		$data['syarat_ketentuan'] 		= $this->input->post('syarat_ketentuan');
		$data['tanggal_maks_po'] 		= $this->input->post('tanggal_maks_po');
		$data['tanggal_maks_bastk'] 		= $this->input->post('tanggal_maks_bastk');
		//$data['ahm'] 								= $this->input->post('ahm');
		//$data['md'] 								= $this->input->post('md');
		//$data['dealer'] 						= $this->input->post('dealer');
		//$data['other'] 							= $this->input->post('other');		
		//$data['target_penjualan'] 							= $this->input->post('target_penjualan');
		//$data['jenis_scp'] 							= $this->input->post('jenis_scp');
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;


		$this->db->trans_begin();
				$this->m_admin->insert($tabel,$data);
				$this->db->query("UPDATE tr_sales_program_gabungan set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md = '$id_program_md' ");
				$this->db->query("UPDATE tr_sales_program_syarat set status='input', id_program_md = '$id_program_md', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
				$this->db->query("UPDATE tr_sales_program_tipe set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md='$id_program_md' ");
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program/add'>";	
            }
            else
            {
                    $this->db->trans_commit();
                   $_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program/add'>";	
            }
			
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$config['upload_path'] 		= './assets/panel/files/';
		$config['allowed_types'] 	= 'doc|docx|pdf|jpg|png|jpeg|bmp|gif';
		$config['max_size']				= '5000';		
		
		$id 		 					= $this->input->post('id_program_md');		
		
		$this->upload->initialize($config);
		if($this->upload->do_upload('draft_jutlak')){
			$data['draft_jutlak']=$this->upload->file_name;
			
			$one = $this->m_admin->getByID($tabel,"id_program_md",$id)->row();
			if(isset($one->draft_jutlak) AND $one->draft_jutlak != ""){
				unlink("assets/panel/files/".$one->draft_jutlak); //Hapus Gambar
			}
		}

		$this->upload->initialize($config);
		if($this->upload->do_upload('final_jutlak')){
			$data['final_jutlak']=$this->upload->file_name;
			
			$one = $this->m_admin->getByID($tabel,"id_program_md",$id)->row();
			if(isset($one->final_jutlak) AND $one->final_jutlak != ""){
				unlink("assets/panel/files/".$one->final_jutlak); //Hapus Gambar
			}
		}
		
		

		$id_program_md 		 					= $this->input->post('id_program_md');		
		$data['id_program_md'] 			= $this->input->post('id_program_md');		
		$data['id_program_ahm'] 		= $this->input->post('id_program_ahm');		
		$data['id_jenis_sales_program'] = $this->input->post('id_jenis_sales_program');		
		$data['jenis'] = $this->input->post('jenis');				
		$data['periode_awal'] 			= $this->input->post('periode_awal');		
		$data['periode_akhir'] 			= $this->input->post('periode_akhir');
		$data['judul_kegiatan'] 		= $this->input->post('judul_kegiatan');
		$data['kuota_program'] 		= $this->input->post('kuota_program');
		$data['syarat_ketentuan'] 		= $this->input->post('syarat_ketentuan');
		$data['tanggal_maks_po'] 		= $this->input->post('tanggal_maks_po');
		$data['tanggal_maks_bastk'] 		= $this->input->post('tanggal_maks_bastk');			
		$data['updated_at']					= $waktu;		
		$data['updated_by']					= $login_id;


		$this->db->trans_begin();
				$this->m_admin->update($tabel,$data,"id_program_md",$id_program_md);
				$this->db->query("UPDATE tr_sales_program_gabungan set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md = '$id_program_md' ");
				$this->db->query("UPDATE tr_sales_program_syarat set status='input', id_program_md = '$id_program_md', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
				$this->db->query("UPDATE tr_sales_program_tipe set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md='$id_program_md' ");
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
            }
            else
            {
                    $this->db->trans_commit();
                   $_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
            }
			
	}
}