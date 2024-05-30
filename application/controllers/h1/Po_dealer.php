<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_dealer extends CI_Controller {

    var $tables =   "tr_po_dealer";	
		var $folder =   "h1";
		var $page		=		"po_dealer";
    var $pk     =   "id_po";
    var $title  =   "Purchase Order (PO) Dealer";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tgl_indo_helper');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_po_dealer_datatables');		
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
		$id_user = $this->session->userdata("id_user");

		// $sql = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_karyawan_dealer ON ms_user.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        //       INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
        //       WHERE ms_user.id_user = '$id_user'")->row();

		// $data['dt_po'] = $this->db->query("SELECT *,tr_po_dealer.created_at 
		// 	FROM tr_po_dealer 
		// 	INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer 
		// 	WHERE (tr_po_dealer.status = 'proses' OR tr_po_dealer.status = 'input' OR tr_po_dealer.status = 'approved' OR tr_po_dealer.status = 'submitted' OR tr_po_dealer.status = 'returned_po' OR tr_po_dealer.status = 'processed') and tr_po_dealer.jenis_po!='PO Indent' ORDER BY tr_po_dealer.created_at DESC");	

		// $data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
		// 				ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
		// 				ON ms_item.id_warna=ms_warna.id_warna
		// 				WHERE ms_item.active = 1");	

		// $data['po_dealer_new']	= $this->db->query("SELECT tr_po_dealer_new.*,ms_dealer.nama_dealer FROM tr_po_dealer_new 
		// 		JOIN ms_dealer ON tr_po_dealer_new.id_dealer=ms_dealer.id_dealer
		// 	WHERE (tr_po_dealer_new.status='submitted' OR tr_po_dealer_new.status='returned_po' OR tr_po_dealer_new.status='processed')");
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	public function fetch_data_po_dealer_datatables()
	{
		$getpo=$_GET['jenispo'];
		$list = $this->m_po_dealer_datatables->get_datatables($getpo);
		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");
	

		// $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
		// $delete = $this->m_admin->set_tombol($id_menu,$group,'delete'); 

        foreach($list as $row) {       

			$status = $row->status;
			$po_id = $row->id_po;
			$gabung = $row->nama_dealer  . "[".$row->kode_dealer_md."]";

            $tombol='';
			$edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');

            $button1      = '<a onclick="return confirm(\'Are you sure to Returned this PO ?\')" href='.base_url('h1/po_dealer/return_po?id='.$row->id_po).' class="btn btn-warning btn-xs btn-flat mb-10"><i class="fa fa-reply-all"></i> Returned PO</a></br>';
            $button2      = '<a onclick="return confirm(\'Are you sure to Processed this PO ?\')" href='.base_url('h1/po_dealer/save_processed?id='.$row->id_po).' class="btn btn-primary btn-xs btn-flat mb-10">Process</a></br>';

			if($row->status=='input'){
				$status = "<span class='label label-primary'>$row->status</span>";
				$tombol = "<a $delete data-toggle=\"tooltip\" title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/po_dealer/delete?id=$row->id_po\"><i class=\"fa fa-trash-o\"></i></a>
						  <a $edit data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/po_dealer/edit?id=$row->id_po\"><i class=\"fa fa-edit\"></i></a>" ;
			  }elseif($row->status=='rejected'){ 
				$status = "<span class='label label-danger'>$row->status</span>";
				$tombol = "";
			  }elseif($row->status=='approved' or $row->status=='proses'){ 
				$status = "<span class='label label-success'>Processed</span>";
				$tombol = "";
			  }elseif($row->status=='waiting'){ 
				$status = "<span class='label label-warnig'>$row->status</span>";
				$tombol = "";
			  }elseif($row->status=='submitted'){ 
				$status = "<span class='label label-info'>Submitted</span>";
				$tombol = $button1.$button2;
			  }elseif($row->status=='returned_po'){ 
				$status = "<span class='label label-warning'>Returned PO</span>";
				$tombol = $button1.$button2;
			  }  

			  if (!empty($po_id)) {
				$id_pos = "<a href='h1/po_dealer/detail?id=$po_id'>$po_id</a>";
			  }else{
				$id_pos = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $gabung;
			$rows[] = $id_pos;
			$rows[] = $row->jenis_po;
			$rows[] = $row->bulan;
			$rows[] = $row->tahun;
			$rows[] = $status;
			$rows[] = $tombol;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_po_dealer_datatables->count_all($getpo),
			"recordsFiltered" => $this->m_po_dealer_datatables->count_filtered($getpo),
			"data" => $data,
		);
		echo json_encode($output);
	}
	
	public function fetch_data_po_dealer_datatables2()
	{
		$list = $this->m_po_dealer_datatables->get_datatables();
		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");
		// $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
		// $delete = $this->m_admin->set_tombol($id_menu,$group,'delete'); 

        foreach($list as $row) {       

			$status = $row->status;
			$po_id = $row->id_po;
			$gabung = $row->nama_dealer  . "[".$row->kode_dealer_md."]";

            $tombol='';
			$edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');

            $button1      = '<a onclick="return confirm(\'Are you sure to Returned this PO ?\')" href='.base_url('h1/po_dealer/return_po?id='.$row->id_po).' class="btn btn-warning btn-xs btn-flat mb-10"><i class="fa fa-reply-all"></i> Returned PO</a></br>';
            $button2      = '<a onclick="return confirm(\'Are you sure to Processed this PO ?\')" href='.base_url('h1/po_dealer/save_processed?id='.$row->id_po).' class="btn btn-primary btn-xs btn-flat mb-10">Process</a></br>';

			if($row->status=='input'){
				$status = "<span class='label label-primary'>$row->status</span>";
				$tombol = "<a $delete data-toggle=\"tooltip\" title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/po_dealer/delete?id=$row->id_po\"><i class=\"fa fa-trash-o\"></i></a>
						  <a $edit data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/po_dealer/edit?id=$row->id_po\"><i class=\"fa fa-edit\"></i></a>" ;
			  }elseif($row->status=='rejected'){ 
				$status = "<span class='label label-danger'>$row->status</span>";
				$tombol = "";
			  }elseif($row->status=='approved' or $row->status=='proses'){ 
				$status = "<span class='label label-success'>Processed</span>";
				$tombol = "";
			  }elseif($row->status=='waiting'){ 
				$status = "<span class='label label-warnig'>$row->status</span>";
				$tombol = "";
			  }elseif($row->status=='submitted'){ 
				$status = "<span class='label label-info'>Submitted</span>";
				$tombol = $button1.$button2;
			  }elseif($row->status=='returned_po'){ 
				$status = "<span class='label label-warning'>Returned PO</span>";
				$tombol = $button1.$button2;
			  }  

			  if (!empty($po_id)) {
				$id_pos = "<a href='h1/po_dealer/detail?id=$po_id'>$po_id</a>";
			  }else{
				$id_pos = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $gabung;
			$rows[] = $id_pos;
			$rows[] = $row->jenis_po;
			$rows[] = $row->bulan;
			$rows[] = $row->tahun;
			$rows[] = $status;
			$rows[] = $tombol;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_po_dealer_datatables->count_all(),
			"recordsFiltered" => $this->m_po_dealer_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function t_po_reg(){
		$id = $this->input->post('id_po');
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_detail.id_po = '$id'";
		$data['dt_po_reg'] = $this->db->query($dq);		
		$data['mode'] = $this->input->post('mode');		
		$this->load->view('h1/t_po_d_reg',$data);
	}

	public function t_po_add(){
		$id = $this->input->post('id_po');
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_detail.id_po = '$id'";
		$data['dt_po_add'] = $this->db->query($dq);		
		$data['mode'] = $this->input->post('mode');		
		$this->load->view('h1/t_po_d_add',$data);
	}
	
	// public function add()
	// {				
	// 	$data['isi']    = $this->page;		
	// 	$data['title']	= $this->title;		
	// 	$data['set']	= "insert";			
	// 	$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
	// 					ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
	// 					ON ms_item.id_warna=ms_warna.id_warna
	// 					WHERE ms_item.active = 1");						
	// 	$th 						= date("Y");
	// 	$bln 						= date("m");					
	// 	$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
	// 	if($cek->num_rows() == 0){
	// 		$data['jenis'] = "PO Reguler"; 
	// 	}else{
	// 		$data['jenis'] = "PO Additional"; 
	// 	}
	// 	$this->template($data);	
	// }

	public function add()
	{				
		$po_type = $data['po_type'] = $this->input->get('type');
		if ($po_type!='add') {
			redirect('h1/po_dealer');
		}elseif($po_type=='add'){
			$data['isi']       = $this->page;		
			$data['title']     = $this->title;
			$data['set']       = "form";
			$data['mode']      = "insert";
			// $data['po_number'] = $this->newPO_ID($po_type);
			$data['po_number'] = '';
			$data['tipe_unit'] = $this->db->get_where('ms_tipe_kendaraan',['active'=>1]);
			$data['set_md']    = $this->db->get('ms_setting_h1')->row();
			// $id_dealer         = $this->m_admin->cari_dealer();
			$this->template($data);	
		}else{
			redirect('h1/po_dealer');
		}
	}

	public function cari_id(){
		$kode = $this->m_admin->get_token(20);

		$kode2="nihil";
		$id_user = $this->session->userdata("id_user");
		$cek = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE id_user = '$id_user' AND id_po NOT IN (SELECT id_po FROM tr_po_dealer WHERE id_po IS NOT NULL)")->row();		
		if(isset($cek->id_po)){			
			$kode2 = $cek->id_po;						
		}

		echo $kode."|".$kode2;		
	}
	public function cari_id_fix($id_dealer){		
		$th 						= date("Y");
		$bln 						= date("m");				
	 	$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");	
	 	if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		}else{
			$get_dealer ='';
			$panjang = '';
		}

		$pr_num 				= $this->db->query("SELECT * FROM tr_po_dealer WHERE RIGHT(id_po,$panjang) = '$get_dealer' and tahun = '$th' ORDER BY id_po DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_po)-($panjang+6);
			$id 	= substr($row->id_po,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th.$bln."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th.$bln."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $th.$bln."00".$id;          					          
      }elseif($id>999){
					$kode1 = $th.$bln."0".$id;                    
      }
			$kode = $kode1."-".$get_dealer;
		}else{
			$kode = $th.$bln."00001-".$get_dealer;
		}
		return $kode;
	}
	public function cek_item()
	{		
		$id_item = $this->input->post('id_item');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$id_dealer = $this->input->post('id_dealer');
		$sql = $this->db->query("SELECT ms_item.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM ms_item INNER JOIN ms_tipe_kendaraan
							ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
							ON ms_item.id_warna=ms_warna.id_warna WHERE ms_item.id_item = '$id_item'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();
			$stok = $this->db->query("SELECT * FROM tr_real_stock WHERE id_tipe_kendaraan = '$dt_ve->id_tipe_kendaraan' AND id_warna = '$dt_ve->id_warna'");
			if($stok->num_rows() > 0){
				$isi = $stok->row();
				$stok_isi = $isi->stok_rfs + $isi->stok_nrfs;
			}else{
				$stok_isi = 0;
			}
			
			$cek 	= $this->db->query("SELECT * FROM tr_niguri_dealer INNER JOIN tr_niguri_dealer_detail ON tr_niguri_dealer.id_niguri_dealer = tr_niguri_dealer_detail.id_niguri_dealer
										INNER JOIN ms_tipe_kendaraan ON tr_niguri_dealer_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_item ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
										WHERE tr_niguri_dealer.bulan = '$bulan' AND tr_niguri_dealer.tahun = '$tahun' AND ms_item.id_item = '$id_item'
										AND tr_niguri_dealer.id_dealer = '$id_dealer'");
			if($cek->num_rows() > 0){
				$is = $cek->row();
				$niguri = $is->a_fix;
			}else{
				$niguri = 0;
			}

			$bulan_1 = $bulan - 1;
			$po = $this->db->query("SELECT * FROM tr_po_dealer INNER JOIN tr_po_dealer_detail ON tr_po_dealer.id_po = tr_po_dealer_detail.id_po 
							WHERE  tr_po_dealer.bulan = '$bulan_1' AND tr_po_dealer.tahun = '$tahun'
							AND tr_po_dealer.id_dealer = '$id_dealer'");
			if($po->num_rows() > 0){
				$isi_po = $po->row();
				$po_fix = $isi_po->qty_po_t1;
				$po_t1 	= $isi_po->qty_po_t2;
				$po_t2 	= 0;				
			}else{
				$po_fix = 0;
				$po_t1 	= 0;
				$po_t2 	= 0;
			}
			
			echo "ok"."|".$dt_ve->id_item."|".$dt_ve->tipe_ahm."|".$dt_ve->warna."|".$stok_isi."|".$niguri."|".$po_fix."|".$po_t1."|".$po_t2;
		}else{
			echo "There is no data found!";
		}
	}

	public function cari_jenis()
	{				
		$th 						= $this->input->post('tahun');
		$bln 						= $this->input->post('bulan');
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			echo "PO Reguler"; 
		}else{
			echo "PO Additional"; 
		}
	}

	function download_file($id){
		
		//mkdir("downloads/hy",0777);
		$k = $this->session->userdata('id_karyawan_dealer');
		$bulan 		= gmdate("mY", time()+60*60*7);		
		$folder 	= "downloads/po/";
		$filename = $folder.$bulan;
		if (!file_exists($filename)) {
		  mkdir($folder.$bulan, 0777);		
		}
		if($id == ""){
			$sql = $this->db->query("SELECT * FROM tr_po_dealer ORDER BY id_po DESC LIMIT 0,1")->row();
		}else{
			$sql = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_po = '$id' ORDER BY id_po ASC")->row();			
		}		
		if($sql->jenis_po == 'PO Reguler'){
			$j = "F";
		}else{
			$j = "A";
		}
		$data['no'] = "AHM-E20-".$sql->id_po."-".$j;		
		$data['id'] = $id;		
		$data['k'] 	= $k;		
		
		//echo "<meta http-equiv='refresh' content='0; url=".base_url()."assets/get_data/upo.php?id=".$id."&k=".$k."&n=".$no."'>";			  
		$this->load->view("h1/file_upo",$data);

	  //download_remote_file(base_url()."assets/get_data/upo.php?id=".$sql->id_po."&k=".$k, realpath("./".$filename)."/".$no.".UPO");	
	  //file_get_contents("C:\xampp\htdocs\web_honda\downloads\po\112017\AHM-E20-20171100001.UPO", base_url()."assets/get_data/upo.php?id=".$sql->id_po."&k=".$k);	
	}


	public function download()
	{
		$id = $this->input->get('id');		
		$this->download_file($id);
		
	}


	public function save_po_reg(){
		$id_po					= $this->input->post('id_po');			
		$id_item				= $this->input->post('id_item');
		$bulan 					= $this->input->post('bulan');
		$tahun 					= $this->input->post('tahun');
		$qty_po_fix							= $this->input->post('qty_po_fix');
		$qty_po_t1							= $this->input->post('qty_po_t1');
		$qty_po_t2							= $this->input->post('qty_po_t2');

		$data['id_po']					= $this->input->post('id_po');			
		$data['id_item']				= $this->input->post('id_item');			
		$data['qty_po_t1']			= $this->input->post('qty_po_t1');			
		$data['qty_po_t2']			= $this->input->post('qty_po_t2');							
		$data['qty_po_fix']			= $this->input->post('qty_po_fix');			
		$data['id_user']				= $this->session->userdata('id_user');		

		

		// if($qty_po_fix <= 22){
		// 	if($qty_po_t1 <= $isi_t1_30){
			$cek = $this->db->get_where("tr_po_dealer_detail",array("id_item"=>$id_item,"id_po"=>$id_po));
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_po_detail;
				$this->m_admin->update("tr_po_dealer_detail",$data,"id_po_detail",$id);			
				echo "nihil";
			}else{
				$this->m_admin->insert("tr_po_dealer_detail",$data);			
				echo "nihil";			
			}
		// 	}else{
		// 		echo "po_t1";
		// 	}
		// }else{
		// 	echo "po_fix";
		// }
		
	}
	public function save_po_add(){
		$id_po			= $this->input->post('id_po');			
		$id_item				= $this->input->post('id_item');
		$data['id_po']			= $this->input->post('id_po');			
		$data['id_item']				= $this->input->post('id_item');			
		$data['qty_order']			= $this->input->post('qty_order');					
		$data['id_user']				= $this->session->userdata('id_user');
		$cek = $this->db->get_where("tr_po_dealer_detail",array("id_item"=>$id_item,"id_po"=>$id_po));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_po_detail;
			$this->m_admin->update("tr_po_dealer_detail",$data,"id_po_detail",$id);			
		}else{
			$this->m_admin->insert("tr_po_dealer_detail",$data);			
		}
		echo "nihil";
	}
	public function delete_po(){
		$id_item = $this->input->post('id_item');
		$id_po_detail 	= $this->input->post('id_po_detail');
		$this->db->query("DELETE FROM tr_po_dealer_detail WHERE id_po_detail = '$id_po_detail'");			
		echo "nihil";
	}
	public function cancel_po(){
		$id_po			= $this->input->post('id_po');			
		$this->m_admin->delete("tr_po_dealer","id_po",$id_po);
		$this->m_admin->delete("tr_po_dealer_detail","id_po",$id_po);
	}
	// public function save()
	// {		
	// 	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$tgl 				= gmdate("y-m-d", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;
	// 	$pk					= $this->pk;
	// 	$id  				= $this->input->post($pk);
	// 	$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
	// 	$id_po 			= $this->input->post('id_po');
	// 	$bulan 			= $this->input->post('bulan');	
	// 	$tahun 			= $this->input->post('tahun');	
	// 	$id_dealer 	= $this->input->post('id_dealer');
	// 	$jenis_po 	= $this->input->post('jenis_po');
	// 	$cek2				= $this->m_admin->getByID("tr_po_dealer_detail","id_po",$id_po)->num_rows();
		
	// 	if($jenis_po == 'PO Additional'){
	// 		$cek3 = $cek2;
	// 	}else{			
	// 		$cek3				= $this->db->query("SELECT COUNT(id_tipe_kendaraan) AS jum FROM tr_analisis_proyeksi_order INNER JOIN tr_analisis_proyeksi_order_detail 
	// 				ON tr_analisis_proyeksi_order.id_analisis = tr_analisis_proyeksi_order_detail.id_analisis WHERE tr_analisis_proyeksi_order.bulan = '$bulan'
	// 				AND tr_analisis_proyeksi_order.tahun = '$tahun' AND tr_analisis_proyeksi_order.id_dealer = '$id_dealer'")->num_rows();
	// 	}


	// 	if($cek == 0){
	// 		if($cek2 > 0){
	// 			if($cek3 == $cek2){
	// 				$data['ket'] 		= $this->input->post('ket');	
	// 				$data['bulan'] 	= $this->input->post('bulan');	
	// 				$data['tahun'] 	= $this->input->post('tahun');	
	// 				$data['jenis_po'] 	= $this->input->post('jenis_po');	
	// 				$id_dealer 					= $this->input->post('id_dealer');	
	// 				$data['id_dealer'] 	= $id_dealer;	
	// 				$data['id_pos_dealer'] 	= $this->m_admin->cari_pos_dealer();
	// 				$data['status'] 	= "input";	
	// 				$data['tgl'] 		= $tgl;	
	// 				if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
	// 					else $data['active'] 		= "";					
	// 				$data['created_at']				= $waktu;		
	// 				$data['created_by']				= $login_id;	
	// 				$id_po_fix			= $this->cari_id_fix($id_dealer);
	// 				$id_po 					= $this->input->post('id_po');
	// 				$data['id_po'] 	= $id_po_fix;
	// 				$this->m_admin->insert($tabel,$data);

	// 				$cek_detail = $this->m_admin->getByID("tr_po_dealer_detail","id_po",$id_po);
	// 				foreach ($cek_detail->result() as $isi) {
	// 					$this->db->query("UPDATE tr_po_dealer_detail SET id_po = '$id_po_fix' WHERE id_po = '$id_po'");
	// 				}
	// 				//$this->download_file($id_po);
	// 				$_SESSION['pesan'] 	= "Data has been saved successfully";
	// 				$_SESSION['tipe'] 	= "success";
	// 				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_dealer'>";
	// 			}else{
	// 				$_SESSION['pesan'] 	= "Detail Tipe Kendaraan harus sesuai dengan Analisis Proyeksi MD";
	// 				$_SESSION['tipe'] 	= "danger";
	// 				echo "<script>history.go(-1)</script>";	
	// 			}
	// 		}else{
	// 			$_SESSION['pesan'] 	= "Detail Tipe Kendaraan harus dipilih dulu";
	// 			$_SESSION['tipe'] 	= "danger";
	// 			echo "<script>history.go(-1)</script>";
	// 		}
	// 	}else{
	// 		$_SESSION['pesan'] 	= "Duplicate entry for primary key";
	// 		$_SESSION['tipe'] 	= "danger";
	// 		echo "<script>history.go(-1)</script>";
	// 	}
	// }
	public function delete()
	{		
		$tabel			= $this->tables;
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
				$this->m_admin->delete("tr_po_dealer_detail","id_po",$id);
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_dealer'>";
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
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function detail()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_po'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$th 						= date("Y");
		$bln 						= date("m");					
		$cek 	= $this->db->query("SELECT * FROM tr_po_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			$data['jenis'] = "PO Reguler"; 
		}else{
			$data['jenis'] = "PO Additional"; 
		}
		$data['set']	= "detail";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_po'] 	= $this->input->post('id_po');
			$data['ket'] 		= $this->input->post('ket');	
			$data['bulan'] 	= $this->input->post('bulan');	
			$data['tahun'] 	= $this->input->post('tahun');				
			$data['jenis_po'] 	= $this->input->post('jenis_po');	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$id);
			//$this->download_file($id);

			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_dealer'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cari_po_reg(){
		$id		= $this->input->post('id');
		$row 	= $this->db->query("SELECT tr_po_dealer_detail.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan FROM tr_po_dealer_detail INNER JOIN ms_item ON tr_po_dealer_detail.id_item=ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_po_dealer_detail.id_po_detail = '$id'")->row();		
		$cek = $this->db->query("SELECT * FROM tr_po_dealer_detail INNER JOIN tr_po_dealer ON tr_po_dealer.id_po = tr_po_dealer_detail.id_po 
				WHERE tr_po_dealer_detail.id_po_detail = '$id'");
		if($cek->num_rows() > 0){
			$mode = "edit";
		}else{
			$mode = "add";
		}
		echo $row->id_item."|".$row->tipe_ahm."|".$row->warna."|".$row->qty_po_fix."|".$row->qty_po_t1."|".$row->qty_po_t2."|".$row->id_po_detail."|".$row->id_po."|".$mode;
	}
	public function cari_po_add(){
		$id		= $this->input->post('id');
		$row 	= $this->db->query("SELECT tr_po_dealer_detail.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan FROM tr_po_dealer_detail INNER JOIN ms_item ON tr_po_dealer_detail.id_item=ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_po_dealer_detail.id_po_detail = '$id'")->row();		
		$cek = $this->db->query("SELECT * FROM tr_po_dealer_detail INNER JOIN tr_po_dealer ON tr_po_dealer.id_po = tr_po_dealer_detail.id_po 
				WHERE tr_po_dealer_detail.id_po_detail = '$id'");
		if($cek->num_rows() > 0){
			$mode = "edit";
		}else{
			$mode = "add";
		}
		echo $row->id_item."|".$row->tipe_ahm."|".$row->warna."|".$row->qty_order."|".$row->id_po_detail."|".$row->id_po."|".$mode;
	}
	public function edit_po_reg(){
		$id							= $this->input->post('id_po_detail');			
		$id_po					= $this->input->post('id_po');			
		$id_item				= $this->input->post('id_item');
		$bulan 					= $this->input->post('bulan');
		$tahun 					= $this->input->post('tahun');
		$qty_po_fix							= $this->input->post('qty_po_fix');
		$qty_po_t1							= $this->input->post('qty_po_t1');
		$qty_po_t2							= $this->input->post('qty_po_t2');

		$data['id_po']					= $this->input->post('id_po');			
		$data['id_item']				= $this->input->post('id_item');			
		$data['qty_po_t1']			= $this->input->post('qty_po_t1');			
		$data['qty_po_t2']			= $this->input->post('qty_po_t2');							
		$data['qty_po_fix']			= $this->input->post('qty_po_fix');					
		$this->m_admin->update("tr_po_dealer_detail",$data,"id_po_detail",$id);			
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_dealer/edit?id=".$id_po."'>";							
			
	}
	public function edit_po_add(){
		$id									= $this->input->post('id_po_detail');			
		$id_po							= $this->input->post('id_po');			
		$id_item						= $this->input->post('id_item');
		$data['id_po']			= $this->input->post('id_po');			
		$data['id_item']		= $this->input->post('id_item');			
		$data['qty_order']	= $this->input->post('qty_order');							
		$mode								= $this->input->post('mode');							
		
		$this->m_admin->update("tr_po_dealer_detail",$data,"id_po_detail",$id);			
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		if($mode == 'edit'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_dealer/edit?id=".$id_po."'>";							
		}else{			
			//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_dealer/add'>";							
			echo "<script>history.go(-1)</script>";
		}		
	}

	// public function detail_po_new()
	// {
	// 	$po_number     = $this->input->get('id');
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['set']   = "detail_po_new";	
	// 	$cek           = $this->db->get_where('tr_po_dealer_new',['po_number'=>$po_number]);
	// 	if ($cek->num_rows()>0) {
	// 		$data['row']    = $cek->row();
	// 		$data['detail'] = $this->db->query("SELECT * FROM tr_po_dealer_detail_new WHERE po_number='$po_number'");
	// 		$this->template($data);	
	// 	}else {
	// 		redirect(base_url('h1/po_dealer'),'refresh');
	// 	}

	// }

	public function detail_po_new()
	{				
		$po_number = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;
		$data['set']       = "detail_po_new";
		$data['mode']      = "detail";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$data['set_md']    = $this->db->get('ms_setting_h1')->row();
		$id_dealer         = $this->m_admin->cari_dealer();
		$cek_data 		   = $this->db->get_where('tr_po_dealer_new',['po_number'=>$po_number]);
		if ($cek_data->num_rows()>0) {
			$data['po_number'] = $po_number;
			$data['row']       = $cek_data->row();
			$data['details']   = $this->db->query("SELECT *,CONCAT_WS('-',type_code,color_code)as id_item, type_code as id_tipe_kendaraan,unit_description as tipe_unit, color_code as id_warna, unit_color AS warna FROM tr_po_dealer_detail_new 
			WHERE po_number='$po_number'
			")->result();
			$this->template($data);	
		}else{
			redirect(base_url('h1/po_dealer'),'refresh');
		}
	}

	public function processed()
	{				
		$po_number = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;
		$data['set']       = "processed";
		$cek_data 		   = $this->db->get_where('tr_po_dealer_new',['po_number'=>$po_number,'status'=>'submitted']);
		if ($cek_data->num_rows()>0) {
			$data['po_number'] = $po_number;
			$data['row']       = $cek_data->row();
			$data['details']   = $this->db->query("SELECT *,CONCAT_WS('-',type_code,color_code)as id_item, type_code as id_tipe_kendaraan,unit_description as tipe_unit, color_code as id_warna, unit_color AS warna FROM tr_po_dealer_detail_new 
			WHERE po_number='$po_number'
			")->result();
			$this->template($data);	
		}else{
			redirect(base_url('h1/po_dealer'),'refresh');
		}
	}

	public function save_processed()
	{
		$id_po = $this->input->get('id');
		$cek_data  = $this->db->get_where('tr_po_dealer',['id_po'=>$id_po,'status'=>'submitted']);
		if ($cek_data->num_rows()>0) {
			$data[0]['id_po'] = $id_po;
			$data[0]['status']    = 'approved';
			$this->db->update_batch('tr_po_dealer',$data,'id_po');
		}
		$_SESSION['pesan'] = "Data has been returned successfully";
		$_SESSION['tipe']  = "success";
		redirect(base_url('h1/po_dealer'),'refresh');
	}

	function return_po()
	{	
		$id_po = $this->input->get('id');
		$cek_data  = $this->db->get_where('tr_po_dealer',['id_po'=>$id_po,'status'=>'submitted']);
		if ($cek_data->num_rows()>0) {
			$data[0]['id_po'] = $id_po;
			$data[0]['status']    = 'returned_po';
			$this->db->update_batch('tr_po_dealer',$data,'id_po');
		}
		$_SESSION['pesan'] = "Data has been returned successfully";
		$_SESSION['tipe']  = "success";
		redirect(base_url('h1/po_dealer'),'refresh');
	}

	public function newPO_ID($po_type,$id_dealer)
	{		
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('Ym');
		// $id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		if ($po_type=='reg') {
			$get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
	   			if ($th_bln==$thbln_po) {
					$id       = substr($row->id_po,-4);
					$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id+1);
		   			$i = 0;
					while ($i<1) {
						$cek = $this->db->get_where('tr_po_dealer',['id_po'=>$new_kode])->num_rows();
					    if ($cek>0) {
							$neww     = substr($new_kode, -4);
							$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$neww+1);
							$i        = 0;
					    }else{
					    	$i++;
					    }
					}
	   			}else{
	   				$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   			}
	   		}else{
	   			$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
		}elseif ($po_type=='add') { 
			$get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				// $thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
	   			// if ($th_bln==$thbln_po) {
					$id       = substr($row->id_po,-4);
					$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id+1);
		   			$i = 0;
					while ($i<1) {
						$cek = $this->db->get_where('tr_po_dealer',['id_po'=>$new_kode])->num_rows();
					    if ($cek>0) {
							$neww     = substr($new_kode, -4);
							$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$neww+1);
							$i        = 0;
					    }else{
					    	$i++;
					    }
					}
	   			// }else{
	   			// 	$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   			// }
	   		}else{
	   			$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
		}else{
			$get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				// $thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
	   			// if ($th_bln==$thbln_po) {
					$id       = substr($row->id_po,-4);
					$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id+1);
		   			$i = 0;
					while ($i<1) {
						$cek = $this->db->get_where('tr_po_dealer',['id_po'=>$new_kode])->num_rows();
					    if ($cek>0) {
							$neww     = substr($new_kode, -4);
							$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$neww+1);
							$i        = 0;
					    }else{
					    	$i++;
					    }
					}
	   			// }else{
	   			// 	$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   			// }
	   		}else{
	   			$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
		}
   		return strtoupper($new_kode);
   		//echo strtoupper($new_kode);
	}

	public function save_po_by_md()
	{		
		$waktu      = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id   = $this->session->userdata('id_user');
		$id_dealer  = $this->input->post('id_dealer');
		// $save_to = $this->input->post('save_to');
		$save_to    = 'approved';

		$po_type_min = $po_type     = $this->input->post('po_type');
		if ($po_type =='reg')$jenis_po = 'PO Reguler';
		if ($po_type =='add')$jenis_po = 'PO Additional';
		$po_type     = $data['jenis_po'] = $jenis_po;
		$id_po       = $data['id_po'] = $this->newPO_ID($po_type_min,$id_dealer);
		$set_md      = $this->db->get('ms_setting_h1')->row();
		if ($po_type_min=='reg') {
			$tgl_po   = date('Y-m-d');
			$tgl      = date('d');
			$deadline = $set_md->deadline_po_dealer;
	      	if ($tgl>$deadline) {
				$bulan =date('m')+2;
				$tahun = date('Y');
		        if ($bulan>12) {
		          	$bulan=2;
		          	$tahun=$tahun+1;
		        }
	      	}else{
	        	$bulan = date('m')+1;
	        	$tahun=date('Y');
	        	if ($bulan>12) {
	          		$bulan=1;
	          		$tahun=$tahun+1;
	        	}
	      	}
		}else{
			$tgl    = explode('-', $this->input->post('tgl'));
			$tgl_po = $this->input->post('tgl');
			$bulan  = $tgl[1];
			$tahun  = $tgl[0];
			$deadline = $tgl[2];
		}
		$data['bulan']               = sprintf("%'.02d",$bulan);
		$data['tahun']               = $tahun;
		$data['tgl']                 = $tgl_po;
		$data['id_dealer']           = $id_dealer;
		$data['created_at']          = $waktu;
		$data['po_from']          = 'md';
		$data['created_by']          = $login_id;
		// $data['status']              = $save_to=='input'?'input':'submitted';
		$data['status']              = $save_to;
		$data['submission_deadline'] = $tahun.'-'.$bulan.'-'.$deadline;
		$data['id_pos_dealer']       = '';

		if ($save_to=='submitted') {
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>1])->row();
			$notif     = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
				'id_referensi' => $id_po,
				'judul'        => "PO $po_type Dealer $dealer->nama_dealer",
				'pesan'        => "Terdapat PO $po_type Unit Dari Dealer $dealer->nama_dealer. PO Number = $id_po",
				'link'         => $ktg_notif->link.'/detail?id='.$id_po,
				'status'       => 'baru',
				'created_at'   => $waktu,
				'created_by'   => $login_id
			 ];
		}
		$details = $this->input->post('detail');
		$unit_qty = 0;
		foreach ($details as $key=>$dtl) {
			$qty_indent        = $po_type_min=='reg'?$dtl['qty_indent']:null;
			$unit_qty          += ($dtl['po_fix']+$qty_indent);
			$id_tipe_kendaraan = $dtl['id_tipe_kendaraan'];
			$id_warna          = $dtl['id_warna'];
			// $dt_details[] = [ 'id_po'=>$id_po,
			// 			'type_code'        => $id_tipe_kendaraan,
			// 			'unit_type'        => $dtl['tipe_unit'],
			// 			'color_code'       => $id_warna,
			// 			'unit_description' => $dtl['tipe_unit'],
			// 			'unit_color'       => $dtl['warna'],
			// 			'current_stock'    => $po_type=='reg'?$dtl['current_stock']:null,
			// 			'monthly_sale'     => $po_type=='reg'?$dtl['monthly_sale']:null,
			// 			'po_t1_last'       => $po_type=='reg'?$dtl['po_t1_last']:null,
			// 			'po_t2_last'       => $po_type=='reg'?$dtl['po_t2_last']:null,
			// 			'po_fix'           => $dtl['po_fix'],
			// 			'qty_po_t1'        => $po_type=='reg'?$dtl['qty_po_t1']:null,
			// 			'qty_po_t2'        => $po_type=='reg'?$dtl['qty_po_t2']:null,
			// 			'qty_indent'       => $qty_indent,
			// 			'max_po_t1'        => $po_type=='reg'?$dtl['max_po_t1']:null,
			// 			'min_po_t1'        => $po_type=='reg'?$dtl['min_po_t1']:null,
			// 			'max_po_fix'       => $po_type=='reg'?$dtl['max_po_fix']:null,
			// 			'min_po_fix'       => $po_type=='reg'?$dtl['min_po_fix']:null,
			// 			'harga'            => $po_type=='reg'?preg_replace("/[^0-9]/", "", $dtl['harga']):null,
			// 			'total_harga'      => $po_type=='reg'?preg_replace("/[^0-9]/", "", $dtl['total_harga']):null,
			// 		  ];

			if ($po_type_min=='reg') {
				$dt_details[] = [ 'id_po'=>$id_po,
						'id_item'       => $id_tipe_kendaraan.'-'.$id_warna,
						'current_stock' => $dtl['current_stock'],
						'monthly_sale'  => $dtl['monthly_sale'],
						'po_t1_last'    => $dtl['po_t1_last'],
						'po_t2_last'    => $dtl['po_t2_last'],
						'qty_po_fix'    => $dtl['po_fix'],
						'qty_po_t1'     => $dtl['qty_po_t1'],
						'qty_po_t2'     => $dtl['qty_po_t2'],
						'qty_order'     => $dtl['po_fix'],
						'max_po_t1'     => $dtl['max_po_t1'],
						'min_po_t1'     => $dtl['min_po_t1'],
						'max_po_fix'    => $dtl['max_po_fix'],
						'min_po_fix'    => $dtl['min_po_fix'],
						'qty_indent'    => $qty_indent,
						'harga'         => preg_replace("/[^0-9]/", "", $dtl['harga']),
						'total_harga'   => preg_replace("/[^0-9]/", "", $dtl['total_harga']),
					  ];
				$indent = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna='$id_warna' AND id_dealer='$id_dealer' AND status='requested' AND status_monitoring IS NULL");
				foreach ($indent->result() as $ind) {
					$upd_ind[] =['id_indent' => $ind->id_indent,
								'po_number'         => $id_po,
								'status_monitoring' => 'po_created',
							];
				}
			}else{
				$dt_details[] = [ 'id_po'=>$id_po,
						'id_item'   => $id_tipe_kendaraan.'-'.$id_warna,
						'qty_order' => $dtl['po_fix'],
					  ];
			}
		}
		// $data['unit_qty'] = $unit_qty;
		 // echo json_encode($data);
		// echo json_encode($dt_details);
		// exit;
		$this->db->trans_begin();
			$this->db->insert('tr_po_dealer',$data);
			$this->db->insert_batch('tr_po_dealer_detail',$dt_details);
			if (isset($notif)) {
	        	$this->db->insert('tr_notifikasi',$notif);
			}
			if (isset($upd_ind)) {
				$this->db->update_batch('tr_po_dealer_indent',$upd_ind,'id_indent');
			}
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$response['status'] ='error';
			$response['msg']    ='Something went wrong';
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			
			$response['status'] ='sukses';
      	}		
      	echo json_encode($response);					
	}	

	public function fetch_item()
   {
		$fetch_data = $this->make_datatables_item();  
		$data = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array   = array();
			$sub_array[] = $rs->id_item;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$row         = json_encode($rs);
			$link        ='<button data-dismiss=\'modal\' onClick=\'return pilihItem('.$row.')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_item(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   

   function make_query_item()  
   {  
     $this->db->select('ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna');  
     $this->db->from('ms_item');
     $this->db->join('ms_tipe_kendaraan', 'ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan');
     $this->db->join('ms_warna', 'ms_item.id_warna = ms_warna.id_warna');
     $searchs ='ms_tipe_kendaraan.active=1';
	 $this->db->where("$searchs", NULL, false);
     $search = $this->input->post('search')['value'];
	  if ($search!='') {
	      $searchs = "(id_item LIKE '%$search%' 
	          OR tipe_ahm LIKE '%$search%'
	          OR warna LIKE '%$search%'
	      )";
	      $this->db->where("$searchs", NULL, false);
	  }
     if(isset($_POST["order"]))  
     {  
          $this->db->order_by($this->order_column_item[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
     }  
     else  
     {  
          $this->db->order_by('id_item', 'ASC');  
     }  
   }  
   function make_datatables_item(){  
		$this->make_query_item();  
		if($_POST["length"] != -1)  
		{  
			$this->db->limit($_POST['length'], $_POST['start']);  
		}  
		$query = $this->db->get();  
		return $query->result();  
   }  
   function get_filtered_data_item(){  
		$this->make_query_item();  
		$query = $this->db->get();  
		return $query->num_rows();  
   }
}