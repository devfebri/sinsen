<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checker extends CI_Controller {

    var $tables =   "tr_checker";	
		var $folder =   "h1";
		var $page		=		"checker";
    var $pk     =   "id_checker";
    var $title  =   "Checker";  

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h1_model_nrfs','m_nrfs');	
		$this->load->model('m_part');		
		//===== Load Library =====
		$this->load->library('upload');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
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
		//$data['dt_checker'] = $this->m_admin->getSortCond("tr_checker","tgl_checker","DESC");
		$data['dt_checker'] = $this->db->query("SELECT * FROM tr_checker order by tgl_checker DESC, id_checker DESC");
		$this->m_nrfs->d_check();
		$cek = $this->db->query("SELECT * FROM tr_checker_detail");
		foreach ($cek->result() as $isi) {
			$cek2 = $this->db->query("SELECT * FROM tr_checker WHERE id_checker = '$isi->id_checker'");
			if($cek2->num_rows() == 0){
				$this->db->query("DELETE FROM tr_checker_detail WHERE id_checker = '$isi->id_checker'");
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
	public function ajax_list()
	{				
		$list = $this->m_part->get_datatables();		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$ss = $this->m_admin->getByID("ms_satuan","id_satuan",$isi->id_satuan);
			if($ss->num_rows() > 0){
				$rt = $ss->row();
				$satuan = $rt->satuan;
			}else{
				$satuan = "";
			}
			$no++;
			$row = array();
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"choosepart('$isi->id_part')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$row[] = $isi->id_part;			
			$row[] = $isi->nama_part;			
			$row[] = $satuan;			
			$row[] = $isi->harga_dealer_user;			
			$data[] = $row;			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_part->count_all(),
						"recordsFiltered" => $this->m_part->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "edit";	
		$id_checker = $this->input->get("id");		
		$id = $id_checker;
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$is_rekap =  $this->db->query("SELECT id_checker FROM tr_rekap_ekspedisi_detail WHERE id_checker = '$id'");	

		if($is_rekap->num_rows()==0){
			if(isset($id)){
				$data['dt_checker'] = $this->db->query("SELECT * FROM tr_checker_detail WHERE id_checker = '$id'");		 
			}else{
				$data['dt_checker'] = $this->db->query("SELECT * FROM tr_checker_detail WHERE created_by = '$login_id' AND status = 0");		 			
			}
			$data['checker']	= $this->db->query("SELECT * FROM tr_checker WHERE id_checker='$id_checker'")->row();	
			$data['checker_detail']	= $this->db->query("SELECT * FROM tr_checker_detail WHERE id_checker='$id_checker'");				
			$this->template($data);		
		}else{
			$_SESSION['pesan'] 		= "Data Checker ".$id." can't updated, Please contact Finance MD!";
			$_SESSION['tipe'] 		= "warning";
			redirect(base_url('h1/checker'),'refresh');
		}	
	}

	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";
		$id_checker = $this->input->get("id");		
		$data['checker']	= $this->db->query("SELECT * FROM tr_checker WHERE id_checker='$id_checker'")->row();	
		$data['checker_detail']	= $this->db->query("SELECT * FROM tr_checker_detail WHERE id_checker='$id_checker'");	
		$this->template($data);			
	}

	public function delete()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";
		$id_checker = $this->input->get("id");	
        $this->db->trans_begin();
			$this->db->query("DELETE FROM tr_checker WHERE id_checker='$id_checker'");	
			$this->db->query("DELETE FROM tr_checker_detail WHERE id_checker='$id_checker'");	
			$this->db->query("DELETE FROM tr_wo WHERE id_checker='$id_checker'");	
		  if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $_SESSION['pesan'] 		= "Data failed to delete";
					$_SESSION['tipe'] 		= "danger";
					redirect(base_url('h1/checker'),'refresh');
            }
            else
            {
                    $this->db->trans_commit();
                    $_SESSION['pesan'] 		= "Data has been deleted successfully";
					$_SESSION['tipe'] 		= "success";
					redirect(base_url('h1/checker'),'refresh');
            }
             	$_SESSION['pesan'] 		= "Data has been deleted successfully";
				$_SESSION['tipe'] 		= "success";
				redirect(base_url('h1/checker'),'refresh');

	}

	public function cek_nosin()
	{		
		$no_mesin = $this->input->post('no_mesin');
		$sql = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
				INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
				INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
				WHERE tr_scan_barcode.no_mesin = '$no_mesin'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();			
			echo "ok"."|".$dt_ve->id_item."|".$dt_ve->tipe_ahm."|".$dt_ve->warna."|".$dt_ve->ekspedisi."|".$dt_ve->no_polisi;
		}else{
			echo "There is no data found!";
		}
	}
	public function cari_id(){
		$kode = $this->m_admin->cari_id("tr_checker","id_checker");
		 $tgl 						= date("Y-m");
		 $th 						= date("Y");
		 $bln 						= date("m");		
		 $pr_num 				= $this->db->query("SELECT * FROM tr_checker WHERE LEFT(created_at,4) = '$th' ORDER BY id_checker DESC LIMIT 0,1");						
		 if($pr_num->num_rows()>0){
		 	$row 	= $pr_num->row();
		 	$id = explode('/', $row->id_checker);
		 	if (count($id) > 1) {
		 		$isi 	= $th.'/'.sprintf("%'.05d",$id[1]+1).'/CHK';			
		 	}else{
		 		$isi = $th.'/00001/CHK';
		 	}				
		 	//$pan  = strlen($row->id_checker)-5;
		// 	$id 	= substr($row->id_checker,$pan,5)+1;					
		// 	$isi 	= sprintf("%'.05d",$id);		
		// 	$kode = $th."/".$bln."/POPP/".$isi;
		 		$kode = $isi;
		 }else{
		  	$kode = $th."/00001/CHK";
		 } 			
		 return $kode;
	}
	public function cek_part(){
		$id 	= $this->input->post("id_part");
		$kode = $this->m_admin->getByID("ms_part","id_part",$id)->row();
		$kode2 = $this->m_admin->getByID("ms_ongkos_kerja","id_part",$id)->row();
		if(isset($kode->nama_part)){
			$nama_part = $kode->nama_part;
		}else{
			$nama_part = "";
		}
		if(isset($kode2->ongkos_kerja)){
			$ongkos_kerja = $kode2->ongkos_kerja;
		}else{	
			$ongkos_kerja = "0";
		}
		echo $nama_part."|".$ongkos_kerja;
	}
	public function t_checker(){
		$id = $this->input->post('id_checker');
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		if(isset($id)){
			$data['dt_checker'] = $this->db->query("SELECT * FROM tr_checker_detail WHERE id_checker = '$id'");		 
		}else{
			$data['dt_checker'] = $this->db->query("SELECT * FROM tr_checker_detail WHERE created_by = '$login_id' AND status = 0");		 			
		}
		//$data['dt_checker'] = $this->db->query("SELECT * FROM tr_checker_detail WHERE id_checker = '$id'");		 
		$this->load->view('h1/t_checker',$data);
	}	
	public function save_checker(){
		
		//$id_checker		= $this->input->post('id_checker');					
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$id_part			= $this->input->post('id_part');					
		$set									= $this->input->post('set');			
		$data['id_part']			= $this->input->post('id_part');
		$data['no_po_urgent']	= $this->input->post('no_po_urgent');			
		$data['id_checker']	= $id_checker	= $this->input->post('id_checker');		
		$data['deskripsi']		= $this->input->post('deskripsi');		
		$data['gejala']				= $this->input->post('gejala');		
		$data['no_mesin']			= $this->input->post('no_mesin');		
		$data['penyebab']			= $this->input->post('penyebab');		
		$data['pengatasan']		= $this->input->post('pengatasan');		
		$data['qty_order']		= $this->input->post('qty_order');		
		$data['ongkos_kerja']	= $this->input->post('ongkos_kerja');		
		$data['ket']					= $this->input->post('ket');	
		$data['created_at']		= $waktu;
		$data['created_by']		= $login_id;
		$data['status']				= 0;		
		
		if($set == 'new'){
			$cek = $this->db->get_where("tr_checker_detail",array("id_part"=>$id_part,"created_by"=>$login_id,"status"=>0));
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_checker_detail;
				$this->m_admin->update("tr_checker_detail",$data,"id_checker_detail",$id);			
			}else{
				$this->m_admin->insert("tr_checker_detail",$data);			
			}
		}else{
			$cek = $this->db->get_where("tr_checker_detail",array("id_checker"=>$id_checker,"id_part"=>$id_part));
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_checker_detail;
				$this->m_admin->update("tr_checker_detail",$data,"id_checker_detail",$id);			
			}else{
				$this->m_admin->insert("tr_checker_detail",$data);			
			}
		}
		echo "nihil";
	}	
	public function delete_checker(){
		$id_checker_detail = $this->input->post('id_checker_detail');		
		$this->db->query("DELETE FROM tr_checker_detail WHERE id_checker_detail = '$id_checker_detail'");			
		echo "nihil";
	}
	public function save()
	{	

		// log_r($_POST);	
		$tgl 			= gmdate("y-m-d", time()+60*60*7);
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$id_checker = $data['id_checker'] 				= $this->cari_id();
		$data['tgl_checker'] 				= $this->input->post('tgl_checker');		
		$no_mesin = $data['no_mesin'] 					= $this->input->post('no_mesin');		
		$data['sumber_kerusakan'] 	= $this->input->post('sumber_kerusakan');		
		$data['keterangan'] 				= $this->input->post('keterangan');						
		$data['no_polisi'] 					= $this->input->post('no_polisi');						
		$data['ekspedisi'] 					= $this->input->post('ekspedisi');						
		$data['harga_jasa'] 				= $this->input->post('harga_jasa');

		$data['estimasi_tgl_selesai']	= $this->input->post('estimasi_tgl_selesai');		
		$data['nama_pemeriksa']	= $this->input->post('nama_pemeriksa');		
		// $data['no_po_urgent']	= $this->input->post('no_po_urgent');	

		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;

		$checker_detail = array();
		$id_part = $this->input->post('id_part');
		$deskripsi = $this->input->post('deskripsi');
		$no_po_urgent = $this->input->post('no_po_urgent');
		$gejala = $this->input->post('gejala');
		$penyebab = $this->input->post('penyebab');
		$pengatasan = $this->input->post('pengatasan');
		$qty_order = $this->input->post('qty_order');
		$ongkos_kerja = $this->input->post('ongkos_kerja');
		$ket = $this->input->post('ket');

		if ($id_part != null) {
			foreach ($id_part as $key => $value) {
				array_push($checker_detail, array(
					"id_checker" => $id_checker,
					"id_part" => $id_part[$key],
					"no_po_urgent" => $no_po_urgent[$key],
					"no_mesin" => $no_mesin,
					"deskripsi" => $deskripsi[$key],
					"gejala" => $gejala[$key],
					"penyebab" => $penyebab[$key],
					"ongkos_kerja" => $ongkos_kerja[$key],
					"pengatasan" => $pengatasan[$key],
					"qty_order" => $qty_order[$key],
					"ket" => $ket[$key],
					"created_at" => $waktu,
					"created_by" => $login_id,
					"status" => 0
				));
			}

			 //$last_no_wo							= $this->m_admin->cari_id("tr_wo","no_wo");
			// $tgl 						= date("Y-m");
			 $th 						= date("Y");
			 $bln 						= date("m");		
			 $pr_num 				= $this->db->query("SELECT * FROM tr_wo WHERE LEFT(no_wo,4)='$th' ORDER BY tgl_wo DESC, no_wo DESC LIMIT 0,1");						
			 if($pr_num->num_rows()>0){
			 	$row 	= $pr_num->row();
			 	$id = explode('/', $row->no_wo);
			 	if (count($id) > 1) {
			 		$da['no_wo'] 	= $th.'/'.sprintf("%'.05d",$id[1]+1).'/WO';			
			 	}else{
			 		$da['no_wo'] = $th.'/00001/WO';
			 	}	
			 }else{
			  	$da['no_wo'] = $th."/00001/WO";
			 } 			

			$cek = $this->db->get_where("tr_checker_detail",array("created_by"=>$login_id,"status"=>0,"no_mesin"=>$no_mesin));
			foreach ($cek->result() as $isi) {
				$this->db->query("UPDATE tr_checker_detail SET status = 1, id_checker = '$id_checker' WHERE id_checker_detail = '$isi->id_checker_detail'");
			}


			$da['id_checker'] 				= $id_checker;		
			$da['tgl_wo'] 						= $tgl;
			$da['status_wo'] 					= "input";
			$da['created_at']					= $waktu;		
			$da['created_by']					= $login_id;

			//start transaction
			$this->db->trans_start();

			$this->m_admin->insert($tabel,$data);
			// simpan tabel WO h1
			$this->m_admin->insert("tr_wo",$da);

			// simpan ke detail tr_checker_detail
			$this->db->insert_batch('tr_checker_detail', $checker_detail);

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
				$_SESSION['pesan'] 		= "Data Gagal di simpan";
				$_SESSION['tipe'] 		= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/checker/add'>";
			} else {
				$_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/checker/add'>";
			}
		} else {
			

			 //$last_no_wo							= $this->m_admin->cari_id("tr_wo","no_wo");
			// $tgl 						= date("Y-m");
			 $th 						= date("Y");
			 $bln 						= date("m");		
			 $pr_num 				= $this->db->query("SELECT * FROM tr_wo ORDER BY tgl_wo DESC, no_wo DESC LIMIT 0,1");						
			 if($pr_num->num_rows()>0){
			 	$row 	= $pr_num->row();
			 	$id = explode('/', $row->no_wo);
			 	if (count($id) > 1) {
			 		$da['no_wo'] 	= $th.'/'.sprintf("%'.05d",$id[1]+1).'/WO';			
			 	}else{
			 		$da['no_wo'] = $th.'/00001/WO';
			 	}	
			 }else{
			  	$da['no_wo'] = $th."/00001/WO";
			 } 			
			$da['id_checker'] 				= $id_checker;		
			$da['tgl_wo'] 						= $tgl;
			$da['status_wo'] 					= "input";
			$da['created_at']					= $waktu;		
			$da['created_by']					= $login_id;

			//start transaction
			$this->db->trans_start();

			$this->m_admin->insert($tabel,$data);
			// simpan tabel WO h1
			$this->m_admin->insert("tr_wo",$da);

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
				$_SESSION['pesan'] 		= "Data Gagal di simpan";
				$_SESSION['tipe'] 		= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/checker/add'>";
			} else {
				$_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/checker/add'>";
			}
		}

		

				
	}

	public function save_edit()
	{		
		$tgl 			= gmdate("y-m-d", time()+60*60*7);
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$id_checker				= $this->input->post('id_checker');		
		$data['tgl_checker'] 				= $this->input->post('tgl_checker');		
		$data['no_mesin'] 					= $this->input->post('no_mesin');		
		$data['sumber_kerusakan'] 	= $this->input->post('sumber_kerusakan');		
		$data['keterangan'] 				= $this->input->post('keterangan');	

		$data['estimasi_tgl_selesai']	= $this->input->post('estimasi_tgl_selesai');		
		$data['nama_pemeriksa']	= $this->input->post('nama_pemeriksa');		
		// $data['no_po_urgent']	= $this->input->post('no_po_urgent');	
									
		$data['harga_jasa'] 				= $this->input->post('harga_jasa');						
		$data['updated_at']					= $waktu;		
		$data['updated_by']					= $login_id;

		$checker_detail = array();
		$id_part = $this->input->post('id_part');
		$deskripsi = $this->input->post('deskripsi');
		$no_po_urgent = $this->input->post('no_po_urgent');
		$gejala = $this->input->post('gejala');
		$penyebab = $this->input->post('penyebab');
		$pengatasan = $this->input->post('pengatasan');
		$qty_order = $this->input->post('qty_order');
		$ongkos_kerja = $this->input->post('ongkos_kerja');
		$ket = $this->input->post('ket');

		if ($id_part != null) {
			foreach ($id_part as $key => $value) {
				array_push($checker_detail, array(
					"id_checker" => $id_checker,
					"id_part" => $id_part[$key],
					"no_po_urgent" => $no_po_urgent[$key],
					"no_mesin" => $this->input->post('no_mesin'),
					"deskripsi" => $deskripsi[$key],
					"gejala" => $gejala[$key],
					"penyebab" => $penyebab[$key],
					"ongkos_kerja" => $ongkos_kerja[$key],
					"pengatasan" => $pengatasan[$key],
					"qty_order" => $qty_order[$key],
					"ket" => $ket[$key],
					"created_at" => $waktu,
					"created_by" => $login_id,
					"status" => 0
				));
			}

			//start transaction
			$this->db->trans_start();

			$this->m_admin->update($tabel,$data,'id_checker',$id_checker);

			// update ke detail tr_checker_detail
			$this->db->where('id_checker', $id_checker	);
			$this->db->delete('tr_checker_detail');
			
			$this->db->insert_batch('tr_checker_detail', $checker_detail);

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
				$_SESSION['pesan'] 		= "Data Gagal di update";
				$_SESSION['tipe'] 		= "success";
				$link = "h1/checker/edit?id=$id_checker";
				redirect(base_url($link),'refresh');
			
			} else {
				$_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				$link = "h1/checker/edit?id=$id_checker";
				redirect(base_url($link),'refresh');
			}
		} else {


			//start transaction
			$this->db->trans_start();

			// update ke detail tr_checker_detail
			$this->db->where('id_checker', $id_checker);
			$this->db->delete('tr_checker_detail');

			$this->m_admin->update($tabel,$data,'id_checker',$id_checker);

			
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
				$_SESSION['pesan'] 		= "Data Gagal di update";
				$_SESSION['tipe'] 		= "success";
				$link = "h1/checker/edit?id=$id_checker";
				redirect(base_url($link),'refresh');
			
			} else {
				$_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				$link = "h1/checker/edit?id=$id_checker";
				redirect(base_url($link),'refresh');
			}
		}

		

	}
}