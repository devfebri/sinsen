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
		$data['dt_sales'] = $this->db->query(
			"select a.id_program_ahm , no_juklak_md, a.id_program_md, a.kategori_program, a.judul_kegiatan , b.subProgram , a.periode_awal , a.periode_akhir , a.jenis, a.kuota_program , a.unique_customer , a.kk_validation, b.statusJuklak , a.created_at , a.updated_at , a.send_dealer
			from tr_sales_program a
			left join ms_juklak_ahm b on a.id_program_ahm = b.juklakNo order by created_at desc"
		);
		// $data['dt_sales']	= $this->m_admin->getAll($this->tables);
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
		// $dt_sales	= $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md='$id_program_md'");
		$dt_sales = $this->db->query(
			"select a.series_motor, a.no_juklak_md, (case when a.target_penjualan !='' then a.target_penjualan else 0 end) target_penjualan, a.id_program_ahm , b.descJuklak, a.kategori_program, a.tanggal_maks_bastk, a.tanggal_maks_po, a.draft_jutlak, a.id_jenis_sales_program, a.segment, a.id_program_md, a.judul_kegiatan , b.subProgram , a.periode_awal , a.periode_akhir , a.jenis, a.kuota_program , a.unique_customer , a.kk_validation, b.statusJuklak , a.created_at , a.updated_at , a.send_dealer
			from tr_sales_program a
			left join ms_juklak_ahm b on a.id_program_ahm = b.juklakNo WHERE id_program_md='$id_program_md' order by created_at desc"
		);
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
		$data['dt_sales']	= $this->db->query("select a.series_motor, a.no_juklak_md, (case when a.target_penjualan !='' then a.target_penjualan else 0 end) target_penjualan , a.id_program_ahm , b.descJuklak, a.kategori_program, a.tanggal_maks_bastk, a.tanggal_maks_po, a.draft_jutlak, a.id_jenis_sales_program, a.segment, a.id_program_md, a.judul_kegiatan , b.subProgram , a.periode_awal , a.periode_akhir , a.jenis, a.kuota_program , a.unique_customer , a.kk_validation, b.statusJuklak , a.created_at , a.updated_at , a.send_dealer
		from tr_sales_program a
		left join ms_juklak_ahm b on a.id_program_ahm = b.juklakNo WHERE a.id_program_md='$id_program_md'");	
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
		
		$data['add_md_cash']			= $this->input->post('add_md_cash');					
		$data['add_md_kredit']			= $this->input->post('add_md_kredit');					
		$data['add_dealer_cash']		= $this->input->post('add_dealer_cash');					
		$data['add_dealer_kredit']		= $this->input->post('add_dealer_kredit');				

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
		$data['add_md_cash']			= $this->input->post('add_md_cash');					
		$data['add_md_kredit']			= $this->input->post('add_md_kredit');					
		$data['add_dealer_cash']		= $this->input->post('add_dealer_cash');					
		$data['add_dealer_kredit']		= $this->input->post('add_dealer_kredit');		
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
		$data['dokument_syarat'] = $this->db->query("SELECT id, short_name FROM ms_kelengkapan_document WHERE active ='1' ");	
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
		$waktu 		= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$unique_customer=0;
		$kk_validation=0;

		if($this->input->post('unique_customer')=='on'){
			$unique_customer=1;
		}

		if($this->input->post('kk_validation')=='on'){
			$kk_validation=1;
		}
		
		$data['draft_jutlak']='';
		$data['final_jutlak']='';
		
		if($_FILES["draft_jutlak"]["name"] !=''){
			$data['file_name'] = $_FILES["draft_jutlak"]["name"];
			$data['draft_jutlak'] = base64_encode((file_get_contents($_FILES["draft_jutlak"]["tmp_name"])));
		}

		/*
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
		}*/

		$id_program_md 		 			= $this->input->post('id_program_md');		
		$id_program_ahm			 		= $this->input->post('id_program_ahm');		
		$data['id_program_md'] 			= $this->input->post('id_program_md');		
		$data['id_program_ahm'] 		= $this->input->post('id_program_ahm');	
		$data['no_juklak_md'] 			= $this->input->post('no_juklak_md');		
		// $data['draft_jutlak'] 			= $draft_jutlak; // save dengan base64 code
		$data['id_jenis_sales_program'] = $this->input->post('id_jenis_sales_program');		
		$data['jenis'] = $this->input->post('jenis');		
		//$data['jenis_bayar'] 				= $this->input->post('jenis_bayar');		
		$data['periode_awal'] 			= $this->input->post('periode_awal');		
		$data['periode_akhir'] 			= $this->input->post('periode_akhir');
		$data['judul_kegiatan'] 		= $this->input->post('judul_kegiatan');
		$data['kuota_program'] 		= $this->input->post('kuota_program');
		$data['kk_validation'] 		= $kk_validation;
		$data['segment'] 		= $this->input->post('segment');
		$data['kategori_program'] 		= $this->input->post('kategori_program');
		$data['unique_customer'] 		= $unique_customer;
		$data['syarat_ketentuan'] 		= $this->input->post('syarat_ketentuan');
		$data['tanggal_maks_po'] 		= $this->input->post('tanggal_maks_po');
		$data['tanggal_maks_bastk'] 		= $this->input->post('tanggal_maks_bastk');
		$data['series_motor'] 		= $this->input->post('series_motor');
		//$data['ahm'] 								= $this->input->post('ahm');
		//$data['md'] 								= $this->input->post('md');
		//$data['dealer'] 						= $this->input->post('dealer');
		//$data['other'] 							= $this->input->post('other');		
		$data['target_penjualan'] 							= $this->input->post('target_penjualan');
		//$data['jenis_scp'] 							= $this->input->post('jenis_scp');
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;

		$this->db->trans_begin();
			$this->m_admin->insert($tabel,$data);
			if($id_program_ahm!=''){
				$this->db->query("UPDATE ms_juklak_ahm set statusJuklak='1' WHERE juklakNo = '$id_program_ahm' "); // update juklak ahm jika berhasil diupload menjadi status =1 (jika ada revisi tetep ubah ke status awal)
			}
			$this->db->query("UPDATE tr_sales_program_gabungan set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md = '$id_program_md' ");
			$this->db->query("UPDATE tr_sales_program_syarat set status='input', id_program_md = '$id_program_md', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
			$this->db->query("UPDATE tr_sales_program_tipe set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md='$id_program_md' ");
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$_SESSION['pesan'] 		= "Something Wen't Wrong";
				$_SESSION['tipe'] 		= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program/add'>";	
            }else{
				$this->db->trans_commit();
				$_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program/add'>";	
            }
			
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$config['upload_path'] 		= './assets/panel/files/';
		$config['allowed_types'] 	= 'doc|docx|pdf|jpg|png|jpeg|bmp|gif';
		$config['max_size']				= '5000';		
		
		$id 		 					= $this->input->post('id_program_md');	
		$juklakNo		 					= $this->input->post('id_program_ahm');	
		
		$unique_customer=0;
		$kk_validation=0;

		if($this->input->post('unique_customer')=='on'){
			$unique_customer=1;
		}

		if($this->input->post('kk_validation')=='on'){
			$kk_validation=1;
		}
		
		$data['draft_jutlak']='';
		$data['final_jutlak']='';
		
		if($_FILES["draft_jutlak"]["name"] !=''){
			$data['file_name'] = $_FILES["draft_jutlak"]["name"];
			$data['draft_jutlak'] = base64_encode((file_get_contents($_FILES["draft_jutlak"]["tmp_name"])));
		}
		
		/*
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
		}*/
		
		$id_program_md 		 					= $id;		
		// $data['id_program_md'] 			= $this->input->post('id_program_md');		
		// $data['id_program_ahm'] 		= $this->input->post('id_program_ahm');		
		// $data['id_jenis_sales_program'] = $this->input->post('id_jenis_sales_program');		
		// $data['jenis'] = $this->input->post('jenis');		
		$data['no_juklak_md'] 			= $this->input->post('no_juklak_md');			
		$data['judul_kegiatan'] 		= $this->input->post('judul_kegiatan');
		$data['series_motor'] 		= $this->input->post('series_motor');
		$data['kuota_program'] 		= $this->input->post('kuota_program');
		$data['periode_awal'] 			= $this->input->post('periode_awal');		
		$data['periode_akhir'] 			= $this->input->post('periode_akhir');
		$data['target_penjualan'] 		= $this->input->post('target_penjualan');
		$data['tanggal_maks_po'] 		= $this->input->post('tanggal_maks_po');
		$data['tanggal_maks_bastk'] 		= $this->input->post('tanggal_maks_bastk');		
		$data['unique_customer'] 			= $unique_customer;
		$data['kk_validation'] 				= $kk_validation;			
		$data['updated_at']					= $waktu;		
		$data['updated_by']					= $login_id;

		$this->db->trans_begin();
			if($juklakNo!=''){
				// update set statusJuklak dan send dealer jika perlu?
				$this->db->query("UPDATE ms_juklak_ahm set statusJuklak='1' WHERE juklakNo = '$juklakNo' "); // update juklak ahm jika berhasil diupload menjadi status =1 (jika ada revisi tetep ubah ke status awal)
			}

			$this->m_admin->update($tabel,$data,"id_program_md",$id_program_md);
			$this->db->query("UPDATE tr_sales_program_gabungan set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md = '$id_program_md' ");
			$this->db->query("UPDATE tr_sales_program_syarat set status='input', id_program_md = '$id_program_md', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
			$this->db->query("UPDATE tr_sales_program_tipe set status='input', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id' AND id_program_md='$id_program_md' ");
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$_SESSION['pesan'] 		= "Something Wen't Wrong";
			$_SESSION['tipe'] 		= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
		}else{
			$this->db->trans_commit();
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
		}
			
	}

	public function get_juklak_ahm(){
		$juklakNo = $this->input->post('id');		
		$get_data = $this->db->query("Select descJuklak, endPeriod, juklakNo, programCategory, quota, segment, startPeriod, subProgram, uniqueCustomer FROM ms_juklak_ahm WHERE juklakNo = '$juklakNo'")->row();	
		
		if(count($get_data)>0){
			$data['juklak'] = $get_data;
			$data['status'] = 1;
		}else{
			$data['status'] = 0;
		}
		
		echo json_encode($data);
	}

	public function send_to_dealer(){
		$id = $this->input->get('id');		
		
		$this->db->trans_begin();
		$this->db->query("UPDATE tr_sales_program set send_dealer= '1' WHERE no_juklak_md = '$id' ");
		
		$this->send_email_to_dealer($id);

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$_SESSION['pesan'] 		= "Something Wen't Wrong";
			$_SESSION['tipe'] 		= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
		}else{
			$this->db->trans_commit();
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sales_program'>";	
		}
	}

	public function get_kategori_program(){
		$id = $this->input->post('id');		
		$get_data = $this->db->query("
			select c.description from ms_jenis_sales_program a
			join ms_program_subcategory b on b.id_subcategory = a.id_sub_category
			join ms_program_category c on b.id_kategory = c.id	
			where a.id_jenis_sales_program = '$id'
		")->row();	
		
		if(count($get_data)>0){	
			echo $get_data->description;
		}else{
			echo 'Not found';
		}
	}

	public function download_file(){
		$id_program_md = $this->input->get('id_program_md');
		$get_data = $this->db->query("select draft_jutlak, file_name from tr_sales_program where id_program_md='$id_program_md'")->row();

		$filename='no_file';
		if(count($get_data) > 0){
			$b64 = $get_data->draft_jutlak;
			$ext = explode('.',$get_data->file_name)[1];
			$filename = $get_data->file_name;
		}

		$data['b64'] = $b64;
		$data['ext'] = $ext;
		$data['filename'] = $filename;
		$this->load->view('h1/t_download_juklak', $data);
	}

	public function send_email_to_dealer($id_program = null)
	{
		$cfg  = $this->db->get('setup_smtp_email')->row();
        $from = $this->db->get_where('ms_email_md', array('email_for' => 'auto_claim'))->row();

        $config = array(
            'protocol' 	=> 'smtp',
			'smtp_host' => 'mail.sinarsentosaprimatama.com',
			'smtp_port' => '587',
            'smtp_user' => $from->email,
            'smtp_pass' => $from->pass,
            'mailtype'  => 'html',
			'smtp_crypto' => 'none',
			'_encoding' => 'base64',
            'charset'   => 'iso-8859-1'
        );
		
		// get data sales program 
		// $id_program = '085/SSP.HD/SCP.MKT/IX/2022';
		// $id_program = '034/SSP.HD/SCP.MKT/IV/2022';
		$jmlh_batch = 1;
		$batch = 1; // mesti diganti atau di parsing parameter
		$email_per_batch = 31; // hanya email to saja, dengan catatan tidak boleh lebih dari 50 email per batch (perjumlahan antara email to dan cc)
		$data = array();
		$id = '[No Juklak MD]';
		$list_to_email = array();
		$temp_list_email = array();
		$list_cc_mail = array();
		$data['header'] = array();
		$data['detail'] = array();

		$filename = 'sample.pdf';
		$b64 ="JVBERi0xLjMNCiXi48/TDQoNCjEgMCBvYmoNCjw8DQovVHlwZSAvQ2F0YWxvZw0KL091dGxpbmVzIDIgMCBSDQovUGFnZXMgMyAwIFINCj4+DQplbmRvYmoNCg0KMiAwIG9iag0KPDwNCi9UeXBlIC9PdXRsaW5lcw0KL0NvdW50IDANCj4+DQplbmRvYmoNCg0KMyAwIG9iag0KPDwNCi9UeXBlIC9QYWdlcw0KL0NvdW50IDINCi9LaWRzIFsgNCAwIFIgNiAwIFIgXSANCj4+DQplbmRvYmoNCg0KNCAwIG9iag0KPDwNCi9UeXBlIC9QYWdlDQovUGFyZW50IDMgMCBSDQovUmVzb3VyY2VzIDw8DQovRm9udCA8PA0KL0YxIDkgMCBSIA0KPj4NCi9Qcm9jU2V0IDggMCBSDQo+Pg0KL01lZGlhQm94IFswIDAgNjEyLjAwMDAgNzkyLjAwMDBdDQovQ29udGVudHMgNSAwIFINCj4+DQplbmRvYmoNCg0KNSAwIG9iag0KPDwgL0xlbmd0aCAxMDc0ID4+DQpzdHJlYW0NCjIgSg0KQlQNCjAgMCAwIHJnDQovRjEgMDAyNyBUZg0KNTcuMzc1MCA3MjIuMjgwMCBUZA0KKCBBIFNpbXBsZSBQREYgRmlsZSApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY4OC42MDgwIFRkDQooIFRoaXMgaXMgYSBzbWFsbCBkZW1vbnN0cmF0aW9uIC5wZGYgZmlsZSAtICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjY0LjcwNDAgVGQNCigganVzdCBmb3IgdXNlIGluIHRoZSBWaXJ0dWFsIE1lY2hhbmljcyB0dXRvcmlhbHMuIE1vcmUgdGV4dC4gQW5kIG1vcmUgKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NTIuNzUyMCBUZA0KKCB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDYyOC44NDgwIFRkDQooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjE2Ljg5NjAgVGQNCiggdGV4dC4gQW5kIG1vcmUgdGV4dC4gQm9yaW5nLCB6enp6ei4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjA0Ljk0NDAgVGQNCiggbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDU5Mi45OTIwIFRkDQooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNTY5LjA4ODAgVGQNCiggQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA1NTcuMTM2MCBUZA0KKCB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBFdmVuIG1vcmUuIENvbnRpbnVlZCBvbiBwYWdlIDIgLi4uKSBUag0KRVQNCmVuZHN0cmVhbQ0KZW5kb2JqDQoNCjYgMCBvYmoNCjw8DQovVHlwZSAvUGFnZQ0KL1BhcmVudCAzIDAgUg0KL1Jlc291cmNlcyA8PA0KL0ZvbnQgPDwNCi9GMSA5IDAgUiANCj4+DQovUHJvY1NldCA4IDAgUg0KPj4NCi9NZWRpYUJveCBbMCAwIDYxMi4wMDAwIDc5Mi4wMDAwXQ0KL0NvbnRlbnRzIDcgMCBSDQo+Pg0KZW5kb2JqDQoNCjcgMCBvYmoNCjw8IC9MZW5ndGggNjc2ID4+DQpzdHJlYW0NCjIgSg0KQlQNCjAgMCAwIHJnDQovRjEgMDAyNyBUZg0KNTcuMzc1MCA3MjIuMjgwMCBUZA0KKCBTaW1wbGUgUERGIEZpbGUgMiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY4OC42MDgwIFRkDQooIC4uLmNvbnRpbnVlZCBmcm9tIHBhZ2UgMS4gWWV0IG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NzYuNjU2MCBUZA0KKCBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY2NC43MDQwIFRkDQooIHRleHQuIE9oLCBob3cgYm9yaW5nIHR5cGluZyB0aGlzIHN0dWZmLiBCdXQgbm90IGFzIGJvcmluZyBhcyB3YXRjaGluZyApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY1Mi43NTIwIFRkDQooIHBhaW50IGRyeS4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NDAuODAwMCBUZA0KKCBCb3JpbmcuICBNb3JlLCBhIGxpdHRsZSBtb3JlIHRleHQuIFRoZSBlbmQsIGFuZCBqdXN0IGFzIHdlbGwuICkgVGoNCkVUDQplbmRzdHJlYW0NCmVuZG9iag0KDQo4IDAgb2JqDQpbL1BERiAvVGV4dF0NCmVuZG9iag0KDQo5IDAgb2JqDQo8PA0KL1R5cGUgL0ZvbnQNCi9TdWJ0eXBlIC9UeXBlMQ0KL05hbWUgL0YxDQovQmFzZUZvbnQgL0hlbHZldGljYQ0KL0VuY29kaW5nIC9XaW5BbnNpRW5jb2RpbmcNCj4+DQplbmRvYmoNCg0KMTAgMCBvYmoNCjw8DQovQ3JlYXRvciAoUmF2ZSBcKGh0dHA6Ly93d3cubmV2cm9uYS5jb20vcmF2ZVwpKQ0KL1Byb2R1Y2VyIChOZXZyb25hIERlc2lnbnMpDQovQ3JlYXRpb25EYXRlIChEOjIwMDYwMzAxMDcyODI2KQ0KPj4NCmVuZG9iag0KDQp4cmVmDQowIDExDQowMDAwMDAwMDAwIDY1NTM1IGYNCjAwMDAwMDAwMTkgMDAwMDAgbg0KMDAwMDAwMDA5MyAwMDAwMCBuDQowMDAwMDAwMTQ3IDAwMDAwIG4NCjAwMDAwMDAyMjIgMDAwMDAgbg0KMDAwMDAwMDM5MCAwMDAwMCBuDQowMDAwMDAxNTIyIDAwMDAwIG4NCjAwMDAwMDE2OTAgMDAwMDAgbg0KMDAwMDAwMjQyMyAwMDAwMCBuDQowMDAwMDAyNDU2IDAwMDAwIG4NCjAwMDAwMDI1NzQgMDAwMDAgbg0KDQp0cmFpbGVyDQo8PA0KL1NpemUgMTENCi9Sb290IDEgMCBSDQovSW5mbyAxMCAwIFINCj4+DQoNCnN0YXJ0eHJlZg0KMjcxNA0KJSVFT0YNCg==";
		
		$total_batch = $this->db->query("select batch from ms_send_to_email mste where active =1 and module ='auto_claim' group by batch having count(DISTINCT email) > 0")->num_rows();
		$get_data = $this->db->query("select a.*, b.jenis_sales_program from tr_sales_program a join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program where a.no_juklak_md ='$id_program'");
		// $get_data = $this->db->query("select a.*, b.jenis_sales_program from tr_sales_program a join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program where a.no_juklak_md ='$id_program' or a.no_juklak_md in ('035/SSP.HD/SCP.MKT/IV/2022','061/SSP.HD/SCP.MKT/VII/2022','033/SSP.HD/SCP.MKT/IV/2022','072/SSP.HD/SCP.MKT/VIII/2022','071/SSP.HD/SCP.MKT/VIII/2022')");
		
		if($get_data->num_rows()>0){;
			// foreach utk kirim email dengan isi berdasarkan no juklak MD
			foreach($get_data->result() as $row_data){
				if($row_data->no_juklak_md !=''){
					$id = $row_data->no_juklak_md;
					$filename = $row_data->file_name;
					$b64 = $row_data->draft_jutlak;
				}

				if($row_data->kuota_program !='*'){
					// send all dealer
					$auto_send_batch = 1; // 1= pengiriman email berdasarkan batch email di database
					for($i=0; $i<$total_batch; $i++){
						$batch = $i+1;
						$temp_list_email = array();
						$get_to_email = $this->db->query("select email, nickname from ms_send_to_email where active = '1' and module ='auto_claim' and batch = '$batch'");
						if($get_to_email->num_rows()>0){
							foreach ($get_to_email->result() as $row){
								array_push($temp_list_email, $row->email);
							}

							$list_to_email[$i] = $temp_list_email;
							// $partition = ceil(count(array_unique($temp_list_email)) / ceil(count(array_unique($temp_list_email)) / $email_per_batch) );
							$jmlh_batch = $total_batch;
						}
					}
				}else if($row_data->kuota_program =='*'){
					$auto_send_batch = 0; // 0 = pengiriman email tidak berdasarkan batch email di database
					// send berdasarkan dealer yg didaftarkan
					$list_id_dealer = '';
					$temp_id_dealer = array();
					$get_data_program_dealer = $this->db->query("select id_dealer from tr_sales_program_dealer where id_program_md = '$row_data->id_program_md'");
					if($get_data_program_dealer->num_rows() > 0){
						foreach($get_data_program_dealer->result() as $row_dealer){
							array_push($temp_id_dealer, $row_dealer->id_dealer);
						}
						$list_id_dealer = implode(', ',$temp_id_dealer);

						$get_to_email = $this->db->query("select email, nickname from ms_send_to_email where active = '1' and module ='auto_claim' and id_dealer in ($list_id_dealer)");
						if($get_to_email->num_rows()>0){
							foreach ($get_to_email->result() as $row){
								array_push($list_to_email, $row->email);
							}
						}

						// pengecekan total list email, jika melebihi 31 email dealer (excl cc email) maka dibagi ke bbrp batch pengiriman email
						$partition = ceil(count(array_unique($list_to_email)) / ceil(count(array_unique($list_to_email)) / $email_per_batch) );
						$jmlh_batch = count(array_chunk(array_unique($list_to_email), $partition));
					}
				}
			}
		
			// looping utk pengiriman email berdasarkan jumlah batch
			$temp_list_email = $list_to_email;
			for($i=0;$i<$jmlh_batch;$i++){
				if($auto_send_batch ==0){
					$list_to_email = implode(', ',array_chunk(array_unique($temp_list_email), $partition)[$i]);
					// echo 'Batch ke '.($i+1).' dengan '.count(array_chunk(array_unique($temp_list_email), $partition)[$i]).' list unik email: <br>';print_r($list_to_email); echo '<br><br>';			
					// echo '<br><br>';
				}else if($auto_send_batch==1){
					if(isset($temp_list_email[$i])){
						$list_to_email = implode(', ', array_unique($temp_list_email[$i]));
						// print_r($list_to_email);
						// echo '<br><br>';
						// echo count(array_unique($temp_list_email[$i]));
						// echo '<br><br>';
					}
				}
				// echo 'tidak bisa dilakukan lgsg dengan banyak batch';die;

				// send email()
				if($list_to_email!=''){	
					echo 'Batch: '.$i.'<br>';		
					$this->load->library('email', $config);
					$this->email->clear(true);	
				
					$this->email->from($from->email,'SEEDS Notification System'); 
					// $this->email->from('no-reply@sinarsentosaprimatama.com'); 
				
					$this->email->to($list_to_email);
					// $this->email->to('michael.chandra@sinarsentosa.co.id');

					// get all data email cc
					$list_cc_mail = array();
					$get_cc_mail = $this->db->query("select * from ms_cc_email where module = 'auto_claim' and active = 1");
					if($get_cc_mail->num_rows()> 0 ){
						foreach($get_cc_mail->result() as $row_cc){
							array_push($list_cc_mail, $row_cc->email_cc);
						}
						$list_cc_mail = implode(', ',$list_cc_mail);
						$this->email->cc($list_cc_mail);
					}

					// $this->email->cc('linda@sinarsentosa.co.id, michael.chandra@sinarsentosa.co.id');
					// $this->email->cc('linda@sinarsentosa.co.id, tuminah@sinarsentosa.co.id, siti.aisah@sinarsentosa.co.id, arum.nurjanah@sinarsentosa.co.id,michael.chandra@sinarsentosa.co.id');

					$subject = $id;
					$data['id_program'] = $id;
					$data['filename'] = $filename;

					$get_data_detail = $this->db->query("select a.id_program_md , a.judul_kegiatan , b.jenis_sales_program, a.periode_awal , a.periode_akhir , c.* , d.tipe_ahm 
					from tr_sales_program a
					join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program
					join tr_sales_program_tipe c on a.id_program_md =c.id_program_md 
					join ms_tipe_kendaraan d on c.id_tipe_kendaraan  = d.id_tipe_kendaraan 
					where a.no_juklak_md ='$id_program' order by a.id_program_md asc");
			
					$data['detail'] = $get_data_detail->result();

					$this->email->subject("$subject"); // no juklak dan deskprsi program
					$this->email->message($this->load->view('h1/email_notification_auto_claim', $data, true)); 
					$bin = base64_decode($b64, true);
					$this->email->attach($bin,'attachment',$filename,'application/pdf');

					/**/
					// sleep sending email for 15 seconds after batch 1
					if($i > 0){
						sleep(10);
					}

					if($this->email->send()){
						// send_json([
						// 	'message' => 'Email berhasil dikirim.'
						// ]);
					}else{
						echo 'Something error!<br>';
						$this->output->set_status_header(400);
						// send_json([
						// 	'message' => 'Email gagal dikirim.'
						// ]);
					}
				}
			}
		}else{
			echo 'Not Found';
		}
	}

	public function send_email_to_dealer_v1($id_program = null)
	{
		$cfg  = $this->db->get('setup_smtp_email')->row();
        $from = $this->db->get_where('ms_email_md', array('email_for' => 'auto_claim'))->row();

		
		// 'smtp_host' => $cfg->smtp_host,
		// 'smtp_port' => $cfg->smtp_port,

        $config = array(
            'protocol' 	=> 'smtp',
			'smtp_host' => 'mail.sinarsentosaprimatama.com',
			'smtp_port' => '587',
            'smtp_user' => $from->email,
            'smtp_pass' => $from->pass,
            'mailtype'  => 'html',
			'smtp_crypto' => 'none',
			'_encoding' => 'base64',
            'charset'   => 'iso-8859-1'
        );

		$this->load->library('email', $config);
		$this->email->from($from->email); 
		// $this->email->from('no-reply@sinarsentosaprimatama.com'); 
		
		// get data sales program 
		// $id_program = '220300003-SP-001';
		// $id_program = '001';
		$data = array();
		$id = '[No Juklak MD]';
		$list_to_email = array();
		$list_cc_mail = array();
		$data['header'] = array();
		$data['detail'] = array();

		$filename = 'sample.pdf';
		$b64 ="JVBERi0xLjMNCiXi48/TDQoNCjEgMCBvYmoNCjw8DQovVHlwZSAvQ2F0YWxvZw0KL091dGxpbmVzIDIgMCBSDQovUGFnZXMgMyAwIFINCj4+DQplbmRvYmoNCg0KMiAwIG9iag0KPDwNCi9UeXBlIC9PdXRsaW5lcw0KL0NvdW50IDANCj4+DQplbmRvYmoNCg0KMyAwIG9iag0KPDwNCi9UeXBlIC9QYWdlcw0KL0NvdW50IDINCi9LaWRzIFsgNCAwIFIgNiAwIFIgXSANCj4+DQplbmRvYmoNCg0KNCAwIG9iag0KPDwNCi9UeXBlIC9QYWdlDQovUGFyZW50IDMgMCBSDQovUmVzb3VyY2VzIDw8DQovRm9udCA8PA0KL0YxIDkgMCBSIA0KPj4NCi9Qcm9jU2V0IDggMCBSDQo+Pg0KL01lZGlhQm94IFswIDAgNjEyLjAwMDAgNzkyLjAwMDBdDQovQ29udGVudHMgNSAwIFINCj4+DQplbmRvYmoNCg0KNSAwIG9iag0KPDwgL0xlbmd0aCAxMDc0ID4+DQpzdHJlYW0NCjIgSg0KQlQNCjAgMCAwIHJnDQovRjEgMDAyNyBUZg0KNTcuMzc1MCA3MjIuMjgwMCBUZA0KKCBBIFNpbXBsZSBQREYgRmlsZSApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY4OC42MDgwIFRkDQooIFRoaXMgaXMgYSBzbWFsbCBkZW1vbnN0cmF0aW9uIC5wZGYgZmlsZSAtICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjY0LjcwNDAgVGQNCigganVzdCBmb3IgdXNlIGluIHRoZSBWaXJ0dWFsIE1lY2hhbmljcyB0dXRvcmlhbHMuIE1vcmUgdGV4dC4gQW5kIG1vcmUgKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NTIuNzUyMCBUZA0KKCB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDYyOC44NDgwIFRkDQooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjE2Ljg5NjAgVGQNCiggdGV4dC4gQW5kIG1vcmUgdGV4dC4gQm9yaW5nLCB6enp6ei4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjA0Ljk0NDAgVGQNCiggbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDU5Mi45OTIwIFRkDQooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNTY5LjA4ODAgVGQNCiggQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA1NTcuMTM2MCBUZA0KKCB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBFdmVuIG1vcmUuIENvbnRpbnVlZCBvbiBwYWdlIDIgLi4uKSBUag0KRVQNCmVuZHN0cmVhbQ0KZW5kb2JqDQoNCjYgMCBvYmoNCjw8DQovVHlwZSAvUGFnZQ0KL1BhcmVudCAzIDAgUg0KL1Jlc291cmNlcyA8PA0KL0ZvbnQgPDwNCi9GMSA5IDAgUiANCj4+DQovUHJvY1NldCA4IDAgUg0KPj4NCi9NZWRpYUJveCBbMCAwIDYxMi4wMDAwIDc5Mi4wMDAwXQ0KL0NvbnRlbnRzIDcgMCBSDQo+Pg0KZW5kb2JqDQoNCjcgMCBvYmoNCjw8IC9MZW5ndGggNjc2ID4+DQpzdHJlYW0NCjIgSg0KQlQNCjAgMCAwIHJnDQovRjEgMDAyNyBUZg0KNTcuMzc1MCA3MjIuMjgwMCBUZA0KKCBTaW1wbGUgUERGIEZpbGUgMiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY4OC42MDgwIFRkDQooIC4uLmNvbnRpbnVlZCBmcm9tIHBhZ2UgMS4gWWV0IG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NzYuNjU2MCBUZA0KKCBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY2NC43MDQwIFRkDQooIHRleHQuIE9oLCBob3cgYm9yaW5nIHR5cGluZyB0aGlzIHN0dWZmLiBCdXQgbm90IGFzIGJvcmluZyBhcyB3YXRjaGluZyApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY1Mi43NTIwIFRkDQooIHBhaW50IGRyeS4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NDAuODAwMCBUZA0KKCBCb3JpbmcuICBNb3JlLCBhIGxpdHRsZSBtb3JlIHRleHQuIFRoZSBlbmQsIGFuZCBqdXN0IGFzIHdlbGwuICkgVGoNCkVUDQplbmRzdHJlYW0NCmVuZG9iag0KDQo4IDAgb2JqDQpbL1BERiAvVGV4dF0NCmVuZG9iag0KDQo5IDAgb2JqDQo8PA0KL1R5cGUgL0ZvbnQNCi9TdWJ0eXBlIC9UeXBlMQ0KL05hbWUgL0YxDQovQmFzZUZvbnQgL0hlbHZldGljYQ0KL0VuY29kaW5nIC9XaW5BbnNpRW5jb2RpbmcNCj4+DQplbmRvYmoNCg0KMTAgMCBvYmoNCjw8DQovQ3JlYXRvciAoUmF2ZSBcKGh0dHA6Ly93d3cubmV2cm9uYS5jb20vcmF2ZVwpKQ0KL1Byb2R1Y2VyIChOZXZyb25hIERlc2lnbnMpDQovQ3JlYXRpb25EYXRlIChEOjIwMDYwMzAxMDcyODI2KQ0KPj4NCmVuZG9iag0KDQp4cmVmDQowIDExDQowMDAwMDAwMDAwIDY1NTM1IGYNCjAwMDAwMDAwMTkgMDAwMDAgbg0KMDAwMDAwMDA5MyAwMDAwMCBuDQowMDAwMDAwMTQ3IDAwMDAwIG4NCjAwMDAwMDAyMjIgMDAwMDAgbg0KMDAwMDAwMDM5MCAwMDAwMCBuDQowMDAwMDAxNTIyIDAwMDAwIG4NCjAwMDAwMDE2OTAgMDAwMDAgbg0KMDAwMDAwMjQyMyAwMDAwMCBuDQowMDAwMDAyNDU2IDAwMDAwIG4NCjAwMDAwMDI1NzQgMDAwMDAgbg0KDQp0cmFpbGVyDQo8PA0KL1NpemUgMTENCi9Sb290IDEgMCBSDQovSW5mbyAxMCAwIFINCj4+DQoNCnN0YXJ0eHJlZg0KMjcxNA0KJSVFT0YNCg==";
		
		$get_data = $this->db->query("select a.*, b.jenis_sales_program from tr_sales_program a join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program where a.no_juklak_md ='$id_program'");
		if($get_data->num_rows()>0){;
			// foreach utk kirim email dengan isi berdasarkan no juklak MD
			foreach($get_data->result() as $row_data){
				if($row_data->no_juklak_md !=''){
					$id = $row_data->no_juklak_md;
					$filename = $row_data->file_name;
					$b64 = $row_data->draft_jutlak;
				}

				if($row_data->kuota_program !='*'){
					// send all dealer
					$get_to_email = $this->db->query("select email, nickname from ms_send_to_email where active = '1' and module ='auto_claim'");
					if($get_to_email->num_rows()>0){
						foreach ($get_to_email->result() as $row){
							array_push($list_to_email, $row->email);
						}
					}
				}else if($row_data->kuota_program =='*'){
					// send berdasarkan dealer yg didaftarkan
					$list_id_dealer = '';
					$temp_id_dealer = array();
					$get_data_program_dealer = $this->db->query("select id_dealer from tr_sales_program_dealer where id_program_md = '$row_data->id_program_md'");
					if($get_data_program_dealer->num_rows() > 0){
						foreach($get_data_program_dealer->result() as $row_dealer){
							array_push($temp_id_dealer, $row_dealer->id_dealer);
						}
						$list_id_dealer = implode(', ',$temp_id_dealer);

						$get_to_email = $this->db->query("select email, nickname from ms_send_to_email where active = '1' and module ='auto_claim' and id_dealer in ($list_id_dealer)");
						if($get_to_email->num_rows()>0){
							foreach ($get_to_email->result() as $row){
								array_push($list_to_email, $row->email);
							}
						}
					}
				}
			}
		
			$list_to_email = implode(', ',array_unique($list_to_email));
			// echo $list_to_email; die;

			$this->email->to($list_to_email);
			// $this->email->to('michael.chandra@sinarsentosa.co.id');

			// $this->email->to('ckis2@sinarsentosa.co.id, muftinz@gmail.com, erwin.susanto@sinarsentosa.co.id, direct.sales@sinarsentosa.co.id');

			// get all data email cc
			$get_cc_mail = $this->db->query("select * from ms_cc_email where module = 'auto_claim' and active = 1");
			if($get_cc_mail->num_rows()> 0 ){
				foreach($get_cc_mail->result() as $row_cc){
					array_push($list_cc_mail, $row_cc->email_cc);
				}
				$list_cc_mail = implode(', ',$list_cc_mail);
				$this->email->cc($list_cc_mail);
			}

			// $this->email->cc('linda@sinarsentosa.co.id, michael.chandra@sinarsentosa.co.id');
			// $this->email->cc('linda@sinarsentosa.co.id, tuminah@sinarsentosa.co.id, siti.aisah@sinarsentosa.co.id, arum.nurjanah@sinarsentosa.co.id,michael.chandra@sinarsentosa.co.id');

			$subject = $id;
			$data['id_program'] = $id;
			$data['filename'] = $filename;

			$get_data_detail = $this->db->query("select a.id_program_md , a.judul_kegiatan , b.jenis_sales_program, a.periode_awal , a.periode_akhir , c.* , d.tipe_ahm 
			from tr_sales_program a
			join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program
			join tr_sales_program_tipe c on a.id_program_md =c.id_program_md 
			join ms_tipe_kendaraan d on c.id_tipe_kendaraan  = d.id_tipe_kendaraan 
			where a.no_juklak_md ='$id_program'");
	
			$data['detail'] = $get_data_detail->result();

			$this->email->subject("$subject"); // no juklak dan deskprsi program
			$this->email->message($this->load->view('h1/email_notification_auto_claim', $data, true)); 
			$bin = base64_decode($b64, true);
			$this->email->attach($bin,'attachment',$filename,'application/pdf');

			if($this->email->send()){
				// send_json([
				// 	'message' => 'Email berhasil dikirim.'
				// ]);
			}else{
				echo 'Something error!';
				$this->output->set_status_header(400);
				// send_json([
				// 	'message' => 'Email gagal dikirim.'
				// ]);
			}
		}else{
			echo 'Not Found';
			// $this->output->set_status_header(400);
			// send_json([
			// 	'message' => 'Email gagal dikirim.'
			// ]);
		}
	}
}