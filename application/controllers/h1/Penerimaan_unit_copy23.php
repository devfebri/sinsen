<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_unit extends CI_Controller {

    var $tables =   "tr_penerimaan_unit";	
	var $folder =   "h1";
	var $page	=	"penerimaan_unit";
    var $pk     =   "id_penerimaan_unit";
    var $title  =   "Penerimaan Unit";

    // status scan barcode
    // 1 = input
    // 2 = booked do dealer

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_penyerahan_penerimaan_unit_datatables');	
		$this->load->model('m_penyerahan_penerimaan_unit_history_datatables');	
		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('PDF_HTML');

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
		// echo 'Maintenance! Silahkan hubungi IT apabila urgent.';die;
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		// $data['dt_penerimaan_unit'] = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE status <> 'close' ORDER BY id_penerimaan_unit DESC");		
		// $data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
		// 	ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
        //           tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		
		$this->template($data);		
	}	

	public function fetch_data_penerimaan_unit_datatables()
	{
		$list = $this->m_penyerahan_penerimaan_unit_datatables->get_datatables();

		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");

		$edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
		$print = $this->m_admin->set_tombol($id_menu,$group,'print');
		// $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');
		// $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');

        foreach($list as $row) {       

			$print = $this->m_admin->set_tombol($id_menu,$group,'print');   

			  if (!empty($row->id_penerimaan_unit)) {

				  $link_id_penerimaan_unit   ="<a href='h1/penerimaan_unit/view?id=$row->id_penerimaan_unit'>$row->id_penerimaan_unit</a>";
				 
				  if($row->status == 'input'){
					$inputbutton=" 
					<a href='h1/penerimaan_unit/scan?id=$row->id_penerimaan_unit'>
                    <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-tags'></i> Scan/Entry No Mesin</button>
                 	</a>
					 <a href='h1/penerimaan_unit/cetak_stiker?id=$row->id_penerimaan_unit'>
                    <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Ulang Stiker</button>                
					</a>                
					<a href='h1/penerimaan_unit/edit?id=$row->id_penerimaan_unit'>
						<button $edit class='btn btn-flat btn-xs bg-maroon'><i class='fa fa-edit'></i> Edit</button>
					</a>
					<a href='h1/penerimaan_unit/close_scan?id=$row->id_penerimaan_unit'>
						<button onclick='return confirm('Are you sure?')' class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close Scan</button>              
					</a>
				  ";

				  } elseif($row->status == 'close scan'){
					$inputbutton=" <a href='h1/penerimaan_unit/cetak_stiker?id=$row->id_penerimaan_unit'>
                    <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Ulang Stiker</button>                
                  </a>";
				
				  }elseif($row->status == 'close ksu'){
					$inputbutton="
					<a href='h1/penerimaan_unit/cetak_stiker?id=$row->id_penerimaan_unit'>
                    <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Ulang Striker</button>                
                  </a>
				  <a href='h1/penerimaan_unit/close?id=$row->id_penerimaan_unit'>
                    <button onclick='return confirm('Are you sure?')' class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close</button>              
                  </a>
					";

			  	  } elseif($row->status == 'close'){
					$inputbutton="closed";
			  	  } 
			}

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $link_id_penerimaan_unit;
			$rows[] = $row->no_antrian ;
			$rows[] = $row->tgl_surat_jalan ;
			$rows[] = $row->vendor_name;
			$rows[] = $row->no_polisi;
			$rows[] = $row->nama_driver;
			$rows[] = $row->tgl_penerimaan;
			$rows[] = $inputbutton;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_penyerahan_penerimaan_unit_datatables->count_all(),
			"recordsFiltered" => $this->m_penyerahan_penerimaan_unit_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "History ".$this->title;															
		$data['set']		= "history";
		// $data['dt_penerimaan_unit'] = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE status = 'close' ORDER BY id_penerimaan_unit ASC");		
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
									ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC limit 100");						
		$this->template($data);			
	}


	public function fetch_data_penerimaan_unit_history_datatables()
	{
		$list = $this->m_penyerahan_penerimaan_unit_history_datatables->get_datatables();

		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");

        foreach($list as $row) {       

			$print = $this->m_admin->set_tombol($id_menu,$group,'print');   

			$link_id_penerimaan_unit   ="<a href='h1/penerimaan_unit/view?h=1&id=$row->id_penerimaan_unit'>$row->id_penerimaan_unit</a>";

			$items = array();
			$r = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$row->id_penerimaan_unit'");
            $rt = $r->row();
			foreach ($r->result() as $k) {
			$items[] = $k->no_shipping_list;
			}

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $link_id_penerimaan_unit;
			$rows[] = $row->no_antrian ;
			$rows[] = $row->gudang ;
			$rows[] = $row->vendor_name;
			$rows[] = $row->no_surat_jalan ;
			$rows[] = $items;
			$rows[] = $row->no_polisi;
			$rows[] = $row->nama_driver;
			$rows[] = $row->tgl_penerimaan;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_penyerahan_penerimaan_unit_history_datatables->count_all(),
			"recordsFiltered" => $this->m_penyerahan_penerimaan_unit_history_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function cari_nosin()
	{
		$id		= $this->input->post('id');
		$dt_item = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
      tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		echo $dt_item;		
	}
	public function cek_sisa()
	{
		$id		= $this->input->post('id_pu');
		$dt_item = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
                  tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND
                  tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode WHERE no_rangka IS NOT NULL)");						
		if($dt_item->num_rows() > 0){
			$sisa = $dt_item->num_rows();
		}else{
			$sisa = 0;
		}		
		echo $sisa." Unit";
	}
	public function cek_gudang()
	{
		$id		= $this->input->post('gudang');
		$dt_g = $this->db->query("SELECT SUM(ms_lokasi_unit.qty) AS jum, SUM(ms_lokasi_unit.isi) AS isi FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
				WHERE ms_gudang.gudang = '$id' AND ms_lokasi_unit.active=1");
		if($dt_g->num_rows() > 0){
			$r = $dt_g->row();
			$qty = $r->jum - $r->isi;
		}else{
			$qty = 0;
		}
		echo $qty;		
	}

	public function t_pu(){
		$id = $this->input->post('id_penerimaan_unit');
		$dq = "SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id'";
		$data['dt_pu'] = $this->db->query($dq);		
		$this->load->view('h1/t_pu',$data);
	}	
	
	public function add()
	{				
		//$this->m_admin->reset_tmp("tr_penerimaan_unit","tr_penerimaan_unit_detail");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_gudang'] = $this->m_admin->getSortCond("ms_gudang","gudang","ASC");							
		$data['dt_driver'] = $this->m_admin->getSortCond("ms_driver","nama_driver","ASC");							
		// $data['dt_vendor'] = $this->db->query("SELECT DISTINCT(ms_vendor.vendor_name),ms_vendor.id_vendor FROM ms_unit_transporter INNER JOIN ms_vendor ON ms_unit_transporter.id_vendor = ms_vendor.id_vendor ORDER BY ms_vendor.id_vendor ASC");
		$data['dt_vendor'] = $this->db->query("SELECT DISTINCT(ms_vendor.vendor_name),ms_vendor.id_vendor FROM ms_unit_transporter INNER JOIN ms_vendor ON ms_unit_transporter.id_vendor = ms_vendor.id_vendor WHERE ms_unit_transporter.active = 1 ORDER BY ms_vendor.id_vendor ASC");

		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");												
		$th 						= date("Y");
		$bln 						= date("m");				
		$this->template($data);										
	}
	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "edit";			
		$data['dt_gudang'] = $this->m_admin->getSortCond("ms_gudang","gudang","ASC");							
		$data['dt_driver'] = $this->m_admin->getSortCond("ms_driver","nama_driver","ASC");							
		$data['dt_vendor'] = $this->db->query("SELECT * FROM ms_unit_transporter INNER JOIN ms_vendor ON ms_unit_transporter.id_vendor = ms_vendor.id_vendor ORDER BY ms_vendor.id_vendor ASC");
		// $data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
		// 	ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
        //           tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");							
		
		
		$id_penerimaan_unit	= $this->input->get('id');		
		$data['dt_penerimaan_unit'] = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE id_penerimaan_unit = '$id_penerimaan_unit'");					
		$th 						= date("Y");
		$bln 						= date("m");				
		$this->template($data);										
	}	
	public function cari_id(){
		$tgl						= $this->input->post('tgl');
		$th 						= date("Y");
		$waktu 					= gmdate("Y-m-d h:i:s", time()+60*60*7);				
		$pr_num 				= $this->db->query("SELECT id_penerimaan_unit FROM tr_penerimaan_unit where left(created_at,4) ='$th' ORDER BY id_penerimaan_unit DESC LIMIT 0,1");						
		
		$id_user 				= $this->session->userdata('id_user');
		$id_tok 				= $this->db->query("SELECT left(session_id,5) as token FROM ms_user WHERE id_user = '$id_user'")->row();
		if(isset($id_tok->token)){			
			$token 				= $id_tok->token;
		}else{
			$token 				= "xxxxx";
		}

		//no id penerimaan
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_penerimaan_unit)-5;
			$id 	= substr($row->id_penerimaan_unit,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $th."00".$id;          					          
      }elseif($id>999){
					$kode1 = $th."0".$id;                    
      }
			$kode = "PU".$kode1.$token;
		}else{
			$kode = "PU".$th."00001".$token;
		} 	

		
		//cek transaksi sebelumnya
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$kode'")->row();
		if(isset($cek->id_penerimaan_unit)){
			$cek2 = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE id_penerimaan_unit = '$kode'")->row();
				if(isset($cek2->id_penerimaan_unit)){			
					$kode3 = "ada";
				}else{
					$kode3 = "nihil";
				}
		}else{
			$kode3 = "ada";
		}

		echo $kode."|".$kode3;
	}
	function cek_id(){
		$tgl						= $this->input->post('tgl');
		$th 						= date("Y");
		$waktu 					= gmdate("Y-m-d h:i:s", time()+60*60*7);				
		$pr_num 				= $this->db->query("SELECT * FROM tr_penerimaan_unit ORDER BY id_penerimaan_unit DESC LIMIT 0,1");						
		
		//no id penerimaan
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_penerimaan_unit)-5;
			$id 	= substr($row->id_penerimaan_unit,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $th."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $th."00".$id;          					          
      }elseif($id>999){
					$kode1 = $th."0".$id;                    
      }
			$kode = "PU".$kode1;
		}else{
			$kode = "PU".$th."00001";
		} 	
		return $kode;
	}
	function cek_antrian($ekspedisi,$tgl_penerimaan){
		//$ekspedisi = "RJTM";
		$amb = $this->m_admin->getByID("ms_vendor","id_vendor",$ekspedisi)->row();
		$eks = $amb->alias;
		$bln = gmdate("m", time()+60*60*7);						
		$thn = gmdate("Y", time()+60*60*7);						
		$t 	 = gmdate("Y-m", time()+60*60*7);						
		$bln_penerimaan = substr($tgl_penerimaan, 0,7);
		$no_antrian 		= $this->db->query("SELECT no_antrian FROM tr_penerimaan_unit WHERE LEFT(tgl_penerimaan,7) = '$bln_penerimaan' AND ekspedisi = '$ekspedisi' ORDER BY no_antrian DESC LIMIT 0,1");						
		//no antrian
		if($no_antrian->num_rows()>0){
			$row 	= $no_antrian->row();				
			$jum  = strlen($ekspedisi);
			$pan  = strlen($row->no_antrian)-$jum;
			$id 	= substr($row->no_antrian,-11,3)+1;			
			$isi 	= sprintf("%'.03d",$id);		
			$kode2 = $eks."/".$isi."/".$bln."/".$thn;
		}else{	
			$kode2 = $eks."/001/".$bln."/".$thn;
		}
		return $kode2;

	}
	function cek_antrian_coba(){
		$ekspedisi = "TBMA";
		$tgl_penerimaan = "2021-08-31";
		$amb = $this->m_admin->getByID("ms_vendor","id_vendor",$ekspedisi)->row();
		$eks = $amb->alias;
		$bln = gmdate("m", time()+60*60*7);						
		$thn = gmdate("Y", time()+60*60*7);						
		$t 	 = gmdate("Y-m", time()+60*60*7);						
		$bln_penerimaan = substr($tgl_penerimaan, 0,7);
		$no_antrian 		= $this->db->query("SELECT no_antrian FROM tr_penerimaan_unit WHERE LEFT(tgl_penerimaan,7) = '$bln_penerimaan' AND ekspedisi = '$ekspedisi' ORDER BY no_antrian DESC LIMIT 0,1");						
		//no antrian
		if($no_antrian->num_rows()>0){
			$row 	= $no_antrian->row();				
			$jum  = strlen($ekspedisi);
			$pan  = strlen($row->no_antrian)-$jum;
			$id 	= substr($row->no_antrian,-11,3)+1;			
			$isi 	= sprintf("%'.03d",$id);		
			$kode2 = $eks."/".$isi."/".$bln."/".$thn;
		}else{	
			$kode2 = $eks."/001/".$bln."/".$thn;
		}
		echo $kode2."|".$id."|".$isi."|".$jum."|".$bln_penerimaan."|".$pan;

	}
	public function hapus_auto(){
		$id = $this->input->post('id_p');		
		$this->db->query("DELETE FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id'");			
		echo "nihil";
	}
	public function take_eks()
	{		
		$ekspedisi	= $this->input->post('ekspedisi');	
		$dt_eks			= $this->db->query("SELECT * FROM ms_unit_transporter WHERE id_vendor = '$ekspedisi'");								
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_eks->result() as $row) {
			$data .= "<option>$row->no_polisi</option>\n";
		}
		echo $data;
	}
	public function take_driver()
	{		
		$ekspedisi	= $this->input->post('ekspedisi');	
		$dt_dri			= $this->db->query("SELECT * FROM ms_driver WHERE id_vendor = '$ekspedisi'");								
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_dri->result() as $row) {
			$data .= "<option>$row->nama_driver</option>\n";
		}
		echo $data;
	}
	public function take_no()
	{		
		$nama_driver	= $this->input->post('nama_driver');	
		$dt_dri			= $this->db->query("SELECT * FROM ms_driver WHERE nama_driver = '$nama_driver'")->row();								
		if(isset($dt_dri->no_hp)){
			echo $dt_dri->no_hp;
		}else{
			echo "";
		}
		
	}
	public function save_pu(){
		$id_penerimaan_unit		= $this->input->post('id_penerimaan_unit');			
		$no_shipping_list			= $this->input->post('no_shipping_list');			
		$data['id_penerimaan_unit']		= $this->input->post('id_penerimaan_unit');			
		$data['no_shipping_list']			= $this->input->post('no_shipping_list');
		$data['id_user']							= $this->session->userdata("id_user");
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
			$id_penerimaan_unit 					= $this->input->post("id_penerimaan_unit");
			$id_penerimaan_unit_real 			= $this->cek_id();
			$data['id_penerimaan_unit'] 	= $id_penerimaan_unit_real;
			$data['ekspedisi'] 						= $this->input->post('ekspedisi');	
			$ekspedisi 										= $this->input->post('ekspedisi');	
			$data['no_surat_jalan'] 			= $this->input->post('no_surat_jalan');	
			$data['tgl_surat_jalan'] 			= $this->input->post('tgl_surat_jalan');	
			$data['no_polisi'] 						= $this->input->post('no_polisi');	
			$data['nama_driver'] 					= $this->input->post('nama_driver');	
			$nama_driver 									= $this->input->post('nama_driver');	
			$data['no_telp'] 							= $this->input->post('no_telp');	
			$no_hp 												= $this->input->post('no_telp');	
			$data['gudang'] 							= $this->input->post('gudang');	
			$data['tgl_penerimaan'] = $tgl_penerimaan = $this->input->post('tgl_penerimaan');	
			$data['no_antrian'] 					= $this->cek_antrian($ekspedisi,$tgl_penerimaan);
			$data['status']								= "input";			
			$data['created_at']						= $waktu;		
			$data['created_by']						= $login_id;	

			$cek	= $this->db->query("SELECT * FROM ms_driver WHERE nama_driver = '$nama_driver' AND no_hp = '$no_hp'");
			if($cek->num_rows == 0){
				$this->db->query("UPDATE ms_driver SET no_hp = '$no_hp' WHERE nama_driver = '$nama_driver'");
			}

			$detail	= $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id_penerimaan_unit'");
			foreach ($detail->result() as $isi) {
				$this->db->query("UPDATE tr_penerimaan_unit_detail SET id_penerimaan_unit = '$id_penerimaan_unit_real' WHERE id_penerimaan_unit = '$id_penerimaan_unit'");
			}

			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_unit'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
	
		$data['no_surat_jalan'] 			= $this->input->post('no_surat_jalan');	
		$data['tgl_surat_jalan'] 			= $this->input->post('tgl_surat_jalan');	
		$data['ekspedisi'] 						= $this->input->post('ekspedisi');	
		$data['no_polisi'] 						= $this->input->post('no_polisi');	
		$data['nama_driver'] 					= $this->input->post('nama_driver');	
		$data['no_telp'] 							= $this->input->post('no_telp');	
		$data['gudang'] 							= $this->input->post('gudang');	
		$data['tgl_penerimaan'] 			= $this->input->post('tgl_penerimaan');				
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_unit'>";		
	}
	
	public function scan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Scan No.Mesin";	
		$data['id_pu'] 	= $this->input->get("id");	
		$data['set']		= "scan";		
		// $data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
		// 	ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
        //           tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		
		// $this->m_admin->update_isi();
		$this->template($data);	

		//$this->load->view('trans/logistik',$data);
	}
	public function t_rfs(){
		$id = $this->input->post('id_pu');
		$dq = "SELECT tr_scan_barcode.no_shipping_list, tr_scan_barcode.slot, tr_scan_barcode.fifo, tr_scan_barcode.lokasi, tr_scan_barcode.no_mesin, tr_scan_barcode.no_rangka, tr_scan_barcode.id_item, ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,(SELECT vendor_name FROM tr_penerimaan_unit 
			JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi=ms_vendor.id_vendor
			WHERE id_penerimaan_unit='$id') AS nama_ekspedisi
				FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND tr_scan_barcode.tipe = 'RFS' AND tr_scan_barcode.status = '1'";
		$data['dt_rfs'] = $this->db->query($dq);
		$data['jenis']  = 'RFS';		
		$this->load->view('h1/t_rfs',$data);
	}
	public function t_nrfs(){
		$id = $this->input->post('id_pu');
		$dq = "SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,(SELECT vendor_name FROM tr_penerimaan_unit 
			JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi=ms_vendor.id_vendor
			WHERE id_penerimaan_unit='$id') AS nama_ekspedisi 
			FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND tr_scan_barcode.tipe = 'NRFS' AND tr_scan_barcode.status = '1'";
		$data['dt_rfs'] = $this->db->query($dq);		
		$data['jenis']  = 'NRFS';
		$this->load->view('h1/t_rfs',$data);
	}
	public function save_rfs(){
		$rfs_text		= $this->input->post('rfs_text');
		$id_pu			= $this->input->post('id_pu');
		$waktu 			= date("y-m-d");
		$isi_lokasi = '';
		$slot_baru = '';
		$jum =0;
		$tgl = date('Y-m-d H:i:s');
		
		$cek 	= $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
						tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
						WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu' AND tr_shipping_list.no_mesin = '$rfs_text'");
		
		$cek_gudang = $this->db->query("SELECT gudang FROM tr_penerimaan_unit WHERE id_penerimaan_unit = '$id_pu'")->row();

		if($cek->num_rows() > 0){
			$row = $cek->row();
			
			//cek status, gudang dan tipe dedicated
			$cek1 = $this->db->query("SELECT ms_lokasi_unit.id_lokasi_unit, ms_lokasi_unit.isi 
							FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.tipe_dedicated = '$row->id_modell' AND ms_lokasi_unit.status_unit = 'RFS' 
							AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang' 
							AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

			if($cek1->num_rows() > 0){
				$amb = $cek1->row();
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;				
			}else {
				//cek gudang, tipe kendaraan dan warna SAMA
				$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
					tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
					WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
					AND tr_scan_barcode.tipe_motor = '$row->id_modell' AND tr_scan_barcode.warna = '$row->id_warna' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
					ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");		

				if($cek2->num_rows() > 0){
					$amb = $cek2->row();				
					$isi_lokasi = $amb->id_lokasi_unit;
					$jum = $amb->isi + 1;			
				}else{
					//cek status, gudang dan tanpa dedicated dan lokasi kosong
					$cek3 = $this->db->query("SELECT ms_lokasi_unit.id_lokasi_unit, ms_lokasi_unit.isi FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi = 0
						AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
						AND ms_lokasi_unit.isi = ''
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

					if($cek3->num_rows() > 0){
						$amb = $cek3->row();				
						$isi_lokasi = $amb->id_lokasi_unit;
						$jum = $amb->isi + 1;			
					}else{
						//cek status, gudang dan tanpa dedicated
						$cek3_a = $this->db->query("SELECT ms_lokasi_unit.id_lokasi_unit, ms_lokasi_unit.isi FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
							WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi = 0
							AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

						if($cek3_a->num_rows() > 0){
							$amb = $cek3_a->row();				
							$isi_lokasi = $amb->id_lokasi_unit;
							$jum = $amb->isi + 1;							
						}else{
							//cek gudang, tipe kendaraan SAMA dan warna BEDA
							$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
								tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
								WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
								AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND tr_scan_barcode.tipe_motor = '$row->id_modell' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
							
							if($cek4->num_rows() > 0){
								$amb = $cek4->row();				
								$isi_lokasi = $amb->id_lokasi_unit;
								$jum = $amb->isi + 1;			
							}else{
								//cek gudang, tipe kendaraan BEDA dan warna BEDA
								$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
									tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang  
									WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
									AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' and ms_lokasi_unit.tipe_dedicated = ''
									ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");		
								
								if($cek5->num_rows() > 0){
									$amb = $cek5->row();				
									$isi_lokasi = $amb->id_lokasi_unit;
									$jum = $amb->isi + 1;						
								}else{
									$isi_lokasi = "";
								}	
							}
						}
					}
				}
			}		

			//cek slot
			if($isi_lokasi != ""){
				$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();				
				$cek_isi = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND (status = 1 OR status = 2)");				
				if($cek_isi->num_rows() > 0){
					$t = $cek_isi->row();

					if($t->jum < 0){
						$isi_scan = 0;
					}else{
						$isi_scan = $t->jum;
					}

					//	update isi lokasi
					$this->db->query("UPDATE ms_lokasi_unit SET isi = '$isi_scan', updated_at = '$tgl' WHERE id_lokasi_unit = '$isi_lokasi'");
				}else{
					$isi_scan = 0;
				}

				// bisa diimprove lagi supaya bisa langsung dapat no slot yang kosong (tanpa ada looping)
				if($isi_scan < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 							
						$sl = $i;
						$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND slot = '$sl' AND (status = 1 OR status = 2) ORDER BY slot ASC");
						if($cek_slot2->num_rows() == 0){
							$isi_slot2 = $cek_slot2->row();
							$slot_baru = $sl; 
							break;								
						}											
					}					
				}else{
					$slot_baru = "";
				}			
			}else{
				$slot_baru = "";
			}

			$nosin_spasi = substr_replace($row->no_mesin," ", 5, -strlen($row->no_mesin));
			$cek_th = $this->db->query("SELECT tahun_produksi FROM tr_fkb WHERE no_mesin = '$nosin_spasi'");
			if($cek_th->num_rows() > 0){
				$amb_th = $cek_th->row();
				$th_produksi = $amb_th->tahun_produksi;
				if($th_produksi==''){
					$th_produksi = date('Y');	
				}
			}else{
				$th_produksi = date('Y');
			}

			$data['no_mesin']						= $row->no_mesin;
			$no_mesin								= $row->no_mesin;
			$data['no_rangka']						= $row->no_rangka;
			$data['no_shipping_list']				= $row->no_shipping_list;
			$data['nama_ekspedisi']					= $row->nama_eks;			
			$data['tipe_motor']						= $row->id_modell;
			$data['warna']							= $row->id_warna;
			// $data['id_item']						= $this->m_admin->get_item($row->id_modell,$row->id_warna);
			// $id_item								= $this->m_admin->get_item($row->id_modell,$row->id_warna);
			$id_item								= $row->id_modell.'-'.$row->id_warna;
			$data['id_item']						= $id_item;
			$data['tipe']							= "RFS";
			$data['status']							= "1";			
			$data['lokasi']							= $isi_lokasi;
			$data['slot']							= $slot_baru; // kenapa bisa isi nya 0?
			$data['tgl_penerimaan']					= $waktu;
			$data['fifo']							= $this->m_admin->cari_fifo($th_produksi);

			$c = $this->db->query("SELECT no_mesin as penerimaan_unit FROM tr_scan_barcode WHERE no_mesin = '$rfs_text'");
			if($c->num_rows() > 0){
				// sdh masuk stok			
				echo "no";
			}else{
				if($cek_th->num_rows() > 0){				
					// $cek_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$row->id_modell'");
					// if($cek_tipe->num_rows() > 0){
					// 	$cek_warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna = '$row->id_warna'");
					// 	if($cek_warna->num_rows() > 0){
							$cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_warna = '$row->id_warna' AND id_tipe_kendaraan = '$row->id_modell'");
							if($cek_item->num_rows() > 0){
								$cek_ksu = $this->db->query("SELECT id_koneksi_ksu FROM ms_koneksi_ksu WHERE id_tipe_kendaraan = '$row->id_modell'");
								if($cek_ksu->num_rows() > 0){
									if($isi_lokasi !="" OR $slot_baru != ""){
										$this->m_admin->insert("tr_scan_barcode",$data);						
										$this->m_admin->update_stock($id_item,"RFS",'+','1');
										$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum', updated_at = '$tgl' WHERE id_lokasi_unit = '$isi_lokasi'");				
										$this->m_admin->set_log($rfs_text,"RFS",$isi_lokasi."-".$slot_baru);

										$cek_srut = $this->m_admin->getByID("tr_srut_gagal","no_mesin",$row->no_mesin);										
										if($cek_srut->num_rows() > 0){
											$is = $cek_srut->row();
											$no_sin = $is->no_mesin;
											$ds['no_mesin'] = $no_sin;
											$ds['no_rangka'] = $is->no_rangka;
											$ds['no_srut'] = $is->no_srut;
											$ds['no_srut_pemohon'] = $is->no_srut_pemohon;
											$ds['tahun_pembuatan'] = $is->tahun_pembuatan;
											$ds['tgl_upload'] = $is->tgl_upload;
											$cek_srut2 = $this->m_admin->getByID("tr_srut","no_mesin",$row->no_mesin);										
											if($cek_srut2->num_rows() > 0){
												$this->m_admin->update("tr_srut",$ds,"no_mesin",$no_sin);
											}else{
												$this->m_admin->insert("tr_srut",$ds);												
											}
											$this->db->query("DELETE FROM tr_srut_gagal WHERE no_mesin = '$no_sin'");
										}
										echo "ok";
									}else{
										echo "lokasi";
									}
								}else{
									echo "ksu";
								}
							}else{
								echo "item";
							}
					// 	}else{
					// 		echo "warna";
					// 	}
					// }else{
					// 	echo "tipe";
					// }
				}else{
					echo "FM";
				}				
			}
		}else{
			$trd = $this->m_admin->getByID("tr_shipping_list","no_mesin",$rfs_text);
			if($trd->num_rows() > 0){
				$t = $trd->row();
				$no_shipping_list = $t->no_shipping_list;
			}else{
				$no_shipping_list = "";
			}
			echo "none|".$no_shipping_list;
		}									
	}

	
	public function save_nrfs(){
		$nrfs_text		= $this->input->post('nrfs_text');
		$id_pu				= $this->input->post('id_pu');
		$waktu 			= date("y-m-d");		
		$tgl = date('Y-m-d H:i:s');

		$cek 	= $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
						tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
						WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu' AND tr_shipping_list.no_mesin = '$nrfs_text'");
		$cek_gudang = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE id_penerimaan_unit = '$id_pu'")->row();		
		if($cek->num_rows() > 0){
			$row = $cek->row();

			//cek status, gudang dan tipe dedicated
			$cek1 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.tipe_dedicated = '$row->id_modell' AND ms_lokasi_unit.status_unit = 'NRFS' 
							AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang' 
							AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");
			
			//cek gudang, tipe kendaraan dan warna SAMA
			$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
							INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
							INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
							AND tr_scan_barcode.tipe_motor = '$row->id_modell' AND tr_scan_barcode.warna = '$row->id_warna'
							AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND ms_gudang.gudang = '$cek_gudang->gudang'
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
		
			//cek status, gudang dan tanpa dedicated dan lokasi kosong
			$cek3 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						  AND ms_lokasi_unit.isi = ''
						  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

			//cek status, gudang dan tanpa dedicated dan lokasi tdk kosong
			$cek3_a = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");


			//cek gudang, tipe kendaraan SAMA dan warna BEDA
			$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
							INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
							INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
							AND tr_scan_barcode.tipe_motor = '$row->id_modell' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

			//cek gudang, tipe kendaraan BEDA dan warna BEDA
			$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
							tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 							
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
							AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND ms_lokasi_unit.tipe_dedicated = '' 
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

			if($cek1->num_rows() > 0){
				$amb = $cek1->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;				
			}elseif($cek2->num_rows() > 0){
				$amb = $cek2->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}elseif($cek3->num_rows() > 0){
				$amb = $cek3->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;						
			}elseif($cek3_a->num_rows() > 0){
				$amb = $cek3_a->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}elseif($cek4->num_rows() > 0){
				$amb = $cek4->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}elseif($cek5->num_rows() > 0){
				$amb = $cek5->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}
			else{
				$isi_lokasi = "";
			}			

			if($isi_lokasi != ""){
				$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();				
				$cek_isi = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND (status = 1 OR status = 2)");				
				if($cek_isi->num_rows() > 0){
					$t = $cek_isi->row();
					
					if($t->jum < 0){
						$isi_scan = 0;
					}else{
						$isi_scan = $t->jum;
					}

					//	update isi lokasi
					$this->db->query("UPDATE ms_lokasi_unit SET isi = '$isi_scan', updated_at = '$tgl' WHERE id_lokasi_unit = '$isi_lokasi'");
				}else{
					$isi_scan = 0;
				}
				if($isi_scan < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 							
						$sl = $i;
						$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND slot = '$sl' AND (status = 1 OR status = 2) ORDER BY slot ASC");
						if($cek_slot2->num_rows() == 0){
							$isi_slot2 = $cek_slot2->row();
							$slot_baru = $sl;
							break;								
						}																			
					}					
				}else{
					$slot_baru = "";
				}			
			}else{
				$slot_baru = "";
			}

			//cek slot lama
			// if($isi_lokasi != ""){
			// 	$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();
			// 	$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND status = 1 ORDER BY slot DESC LIMIT 0,1");
			// 	if($cek_slot->num_rows() > 0){
			// 		$isi_slot = $cek_slot->row();
			// 		if($cek_maks->qty > $isi_slot->slot){
			// 			$slot = $isi_slot->slot + 1;
			// 			if($slot < 10){
			// 				$slot_baru = "0".$slot;
			// 			}else{
			// 				$slot_baru= $slot;
			// 			}	
			// 		}else{
			// 			$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND status = 1 ORDER BY slot ASC LIMIT 0,1");
			// 			$isi_slot2 = $cek_slot2->row();
			// 			if($isi_slot2->slot > 1){
			// 				$slot2 = $isi_slot2->slot - 1;
			// 				$slot_baru = "0".$slot2;							
			// 			}	
			// 		}
					
			// 	}else{
			// 		$slot_baru = "01";
			// 	}
			// }else{
			// 	$slot_baru = "";
			// }			

			

			$nosin_spasi = substr_replace($row->no_mesin," ", 5, -strlen($row->no_mesin));
			$cek_th = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin = '$nosin_spasi'");
			if($cek_th->num_rows() > 0){
				$amb_th = $cek_th->row();
				$th_produksi = $amb_th->tahun_produksi;
				if($th_produksi==''){
					$th_produksi = date('Y');	
				}
			}else{
				$th_produksi = date('Y');
			}

			$data['no_mesin']							= $row->no_mesin;
			$data['no_rangka']						= $row->no_rangka;
			$data['no_shipping_list']			= $row->no_shipping_list;
			$data['nama_ekspedisi']				= $row->nama_eks;			
			$data['tipe_motor']						= $row->id_modell;
			$data['warna']								= $row->id_warna;
			$data['id_item']							= $this->m_admin->get_item($row->id_modell,$row->id_warna);
			$id_item											= $this->m_admin->get_item($row->id_modell,$row->id_warna);
			$data['tipe']									= "NRFS";
			$data['status']								= "1";
			$data['lokasi']								= $isi_lokasi;
			$data['slot']									= $slot_baru;
			$data['tgl_penerimaan']				= $waktu;			
			$data['fifo']									= $this->m_admin->cari_fifo($th_produksi);
			
			$c = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$nrfs_text'");
			if($c->num_rows() > 0){
				//$cek2 = $this->m_admin->update("tr_scan_barcode",$data,"no_mesin",$nrfs_text);	
				//$this->m_admin->update_stock($row->id_modell,$row->id_warna,"RFS",'-','1');	
				//$this->m_admin->update_stock($row->id_modell,$row->id_warna,"NRFS",'+','1');																		
				echo "Gagal Simpan, No Mesin ini sudah di-scan sebelumnya";
			}else{
				if($cek_th->num_rows() > 0){
					$cek_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$row->id_modell'");
					if($cek_tipe->num_rows() > 0){
						$cek_warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna = '$row->id_warna'");
						if($cek_warna->num_rows() > 0){
							$cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_warna = '$row->id_warna' AND id_tipe_kendaraan = '$row->id_modell'");
							if($cek_item->num_rows() > 0){
								$cek_ksu = $this->db->query("SELECT * FROM ms_koneksi_ksu WHERE id_tipe_kendaraan = '$row->id_modell'");					
								if($cek_ksu->num_rows() > 0){
									if($cek2->num_rows() > 0 OR $cek3->num_rows() > 0 OR $cek1->num_rows() > 0 OR $cek4->num_rows() > 0 OR $cek5->num_rows() > 0 OR $slot_baru != ""){
										$this->m_admin->insert("tr_scan_barcode",$data);						
										$this->m_admin->update_stock($id_item,"NRFS",'+','1');
										$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum' WHERE id_lokasi_unit = '$isi_lokasi'");				
										$this->m_admin->set_log($nrfs_text,"NRFS",$isi_lokasi."-".$slot_baru);
										$cek_srut = $this->m_admin->getByID("tr_srut_gagal","no_mesin",$row->no_mesin);										
										if($cek_srut->num_rows() > 0){
											$is = $cek_srut->row();
											$no_sin = $is->no_mesin;
											$ds['no_mesin'] = $no_sin;
											$ds['no_rangka'] = $is->no_rangka;
											$ds['no_srut'] = $is->no_srut;
											$ds['no_srut_pemohon'] = $is->no_srut_pemohon;
											$ds['tahun_pembuatan'] = $is->tahun_pembuatan;
											$ds['tgl_upload'] = $is->tgl_upload;
											$cek_srut2 = $this->m_admin->getByID("tr_srut","no_mesin",$row->no_mesin);										
											if($cek_srut2->num_rows() > 0){
												$this->m_admin->update("tr_srut",$ds,"no_mesin",$no_sin);
											}else{
												$this->m_admin->insert("tr_srut",$ds);												
											}
											$this->db->query("DELETE FROM tr_srut_gagal WHERE no_mesin = '$no_sin'");
										}
										echo "ok";
									}else{
										echo "lokasi";
									}
								}else{
									echo "ksu";
								}
							}else{
								echo "item";
							}
						}else{
							echo "warna";
						}
					}else{
						echo "tipe";
					}
				}else{
					echo "FM";
				}	
			}
		}else{
			$trd = $this->m_admin->getByID("tr_shipping_list","no_mesin",$nrfs_text);
			if($trd->num_rows() > 0){
				$t = $trd->row();
				$no_shipping_list = $t->no_shipping_list;
			}else{
				$no_shipping_list = "";
			}
			echo "none|".$no_shipping_list;
		}									
	}	
	// public function delete_scan(){
	// 	$id 		= $this->input->post('id_scan_barcode');		
	// 	$jenis 	= $this->input->post('jenis');		
	// 	$am = $this->m_admin->getByID("tr_scan_barcode","id_scan_barcode",$id)->row();
	// 	$this->m_admin->update_stock($am->id_item,$jenis,'-','1');

	// 	$isi_lokasi = $am->lokasi;		
	// 	$amb = $this->m_admin->getByID("ms_lokasi_unit","id_lokasi_unit",$isi_lokasi)->row();		
	// 	if($amb->isi > 0){
	// 		$jum = $amb->isi - 1;
	// 	}else{
	// 		$jum = 0;			
	// 	}
	// 	$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum' WHERE id_lokasi_unit = '$isi_lokasi'");			
	// 	$this->db->query("DELETE FROM tr_scan_barcode WHERE id_scan_barcode = '$id'");			
		
	// 	echo "nihil";
	// }	
	public function view()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail Penerimaan Unit";	
		$id =$data['id_penerimaan_unit']	= $this->input->get("id");	
		$data['set']		= "detail";		
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		$dq = "SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.deskripsi_ahm FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND tr_scan_barcode.tipe = 'RFS'";
		$data['dt_rfs'] = $this->db->query($dq);

		$dqe = "SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.deskripsi_ahm FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND (tr_scan_barcode.tipe = 'NRFS' OR tr_scan_barcode.tipe='PINJAMAN') ";
		$data['dt_nrfs'] = $this->db->query($dqe);		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}


	public function cari_id_inv(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_invoice_ekspedisi ORDER BY no_invoice_program DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_invoice_program)-3;
			$id 	= substr($row->no_invoice_program,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/IKS/".$isi;
		}else{
			$kode = $th.$bln."/IKS/00001";
		}						
		return $kode;
	}
	public function cari_id_inv2(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_invoice_penerimaan ORDER BY no_invoice_penerimaan DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_invoice_penerimaan)-3;
			$id 	= substr($row->no_invoice_penerimaan,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/PEN/".$isi;
		}else{
			$kode = $th.$bln."/PEN/00001";
		}						
		return $kode;
	}
	public function close_scan(){
		$id_pu 			= $this->input->get('id');		
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		

		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$data['status']						= "close scan";	
		
		$qty = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail 
				ON tr_scan_barcode.no_shipping_list=tr_penerimaan_unit_detail.no_shipping_list
				INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit
				WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu'");
		$tot=0;

		$set_ongkos_tipe_motor = array();
		foreach ($qty->result() as $key) {		
			$ongkos = $this->db->query("SELECT * FROM ms_group_ongkos INNER JOIN ms_group_angkut ON ms_group_ongkos.id_group_angkut = ms_group_angkut.id_group_angkut
					INNER JOIN ms_group_angkut_detail ON ms_group_angkut.id_group_angkut = ms_group_angkut_detail.id_group_angkut
					WHERE ms_group_angkut_detail.id_tipe_kendaraan = '$key->tipe_motor' AND ms_group_ongkos.id_vendor = '$key->ekspedisi'");
			if($ongkos->num_rows() > 0){
				$yi = $ongkos->row();
				$biaya = $yi->ongkos_ahm;
			}else{
				$biaya = 0;
				array_push($set_ongkos_tipe_motor, $key->tipe_motor);
			}
			$tot = $tot + $biaya;
		}

		$set_ongkos_tipe_motor = array_unique($set_ongkos_tipe_motor);

		if(count($set_ongkos_tipe_motor) == 0){
			$jum = $qty->num_rows();
			$r = $this->m_admin->getByID("tr_penerimaan_unit","id_penerimaan_unit",$id_pu)->row();
			$ds['no_invoice_program'] 	= $this->cari_id_inv();
			$ds['tgl_invoice_program'] 	= $tgl;
			$ds['no_penerimaan'] 				= $id_pu;
			$ds['tgl_penerimaan'] 			= $r->tgl_penerimaan;
			$ds['qty_terima'] 					= $jum;
			$ds['total'] 								= $tot;		
			$cek = $this->m_admin->getByID("tr_invoice_ekspedisi","no_penerimaan",$id_pu);
			if($cek->num_rows() > 0){
				$f = $cek->row();
				$ds['updated_at'] 					= $waktu;
				$ds['updated_by'] 					= $login_id;
				$this->m_admin->update("tr_invoice_ekspedisi",$ds,"no_penerimaan",$f->no_penerimaan);
			}else{
				$ds['created_at'] 					= $waktu;
				$ds['created_by'] 					= $login_id;
				$this->m_admin->insert("tr_invoice_ekspedisi",$ds);
			}


			$dr['tgl_invoice_penerimaan'] 	= $tgl;
			$dr['no_penerimaan'] 				= $id_pu;
			$dr['tgl_penerimaan'] 			= $r->tgl_penerimaan;
			$dr['qty_terima'] 					= $jum;
			$dr['total'] 								= $tot;		
			$cek = $this->m_admin->getByID("tr_invoice_penerimaan","no_penerimaan",$id_pu);
			if($cek->num_rows() > 0){
				$f = $cek->row();
				$dr['updated_at'] 					= $waktu;
				$dr['updated_by'] 					= $login_id;
				$this->m_admin->update("tr_invoice_penerimaan",$dr,"no_penerimaan",$f->no_penerimaan);
			}else{
				$dr['no_invoice_penerimaan'] 	= $this->cari_id_inv2();
				$dr['created_at'] 					= $waktu;
				$dr['created_by'] 					= $login_id;
				$this->m_admin->insert("tr_invoice_penerimaan",$dr);
			}

			$this->m_admin->update($tabel,$data,$pk,$id_pu);
			//$this->db->query("UPDATE tr_penerimaan_unit SET status = 'close scan' WHERE id_penerimaan_unit = '$id_pu'");

			$_SESSION['pesan'] 	= "Status has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_unit/'>";
		}else{
			$text_tipe = implode(', ', $set_ongkos_tipe_motor);
			$_SESSION['pesan'] 	= "Gagal, Tipe motor: '". $text_tipe ."' belum disetting master ongkos angkut.";
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_unit/'>";
		}
	}	
	public function close(){
		$id_pu 			= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		

		// $sql = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id_pu'");
		// foreach ($sql->result() as $isi) {
		// 	$this->db->query("UPDATE tr_scan_barcode SET status = 1 WHERE no_shipping_list = '$isi->no_shipping_list'");
		// }

		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$data['status']						= "close";	
		$this->m_admin->update($tabel,$data,$pk,$id_pu);
		//$this->db->query("UPDATE tr_penerimaan_unit SET status = 'close scan' WHERE id_penerimaan_unit = '$id_pu'");
		$_SESSION['pesan'] 	= "Status has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_unit/'>";
	}
	public function cetak_stiker()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak Ulang Stiker";	
		$id_pu 					= $this->input->get("id");	
		$data['set']		= "cetak";
		$data['dt_shipping_list'] = $this->db->query("SELECT tr_scan_barcode.no_mesin,tr_scan_barcode.tipe, tr_scan_barcode.fifo,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm 
		FROM tr_penerimaan_unit_detail INNER JOIN tr_scan_barcode ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu' order by tr_scan_barcode.fifo asc");				
		// $data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
		// 	ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
        //           tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		// 
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	// public function cetak_s(){
	// 	$id 				= $this->input->get('id');		
	// 	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;
	// 	$pk					= $this->pk;		
	// 	$data['dt_stiker'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$id'");
	// 	$this->load->view('h1/cetak_stiker',$data);
		
	// }
	public function cetak_s(){
		$id       = $this->input->post('id');		
		$waktu    = gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;		
		$data = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$id'");
		if ($data->num_rows()>0) {
			$row = $data->row();
			echo "
				<html>
					<table style=\"font-family: 'consolas';
							   width:100%;
							   letter-spacing:-1px;
							   line-height: 0.8em;
							   font-size:16pt;
							   font-weight:bold;
						   \">
					    <tr>
							<td colspan=2>TYPE :  $row->tipe_motor</br>
							WARNA : $row->warna</br>
							NO. FIFO : $row->fifo </br>
							</td>
					    </tr>
					    <tr style='line-height:10px'>
							<td colspan=2>&nbsp;</td>
					    </tr>
					    <tr>
							<td>NO.ENGINE</td><td>:$row->no_mesin</td>
					    </tr>
					    <tr>
							<td>NO.RANGKA</td><td>:$row->no_rangka</td>
					    </tr>
					
					    <tr style='font-size:58pt;line-height: 0.8em;line-spacing:-2px;'>
							<td colspan=2>$row->lokasi-$row->slot</td>
					    </tr>
					</table>
				</html>
				";
		}
	}
	public function cetak_auto($no_mesin=NULL){			
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);		
		$data['dt_stiker'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'");
		$this->load->view('h1/cetak_stiker',$data);
		
	}

	public function cetak_sx(){
		$id 				= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$dt_stiker= $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$id'");
		if ($dt_stiker->num_rows()>0) {
			$row=$dt_stiker->row();
		  $pdf = new FPDF('L','inch',array(8.4,5.2));
		  $pdf->SetAutoPageBreak(false);
	      $pdf->AddPage();
	       // head	  
		  $pdf->SetFont('ARIAL','B',13);
		  $pdf->Cell(30, 6, 'TYPE', 0, 0, 'L');$pdf->Cell(30, 6, $row->tipe_motor, 0, 0, 'L');
		  $pdf->SetFont('ARIAL','',10);
	 	  $pdf->Output(); 
		}
	}
	public function detail_scan3()
	{				
		$id = $this->input->post("id_penerimaan_unit");
		$data['isi']    = $this->page;	
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
									ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		$data['title']	= $this->title;						
		$this->load->view("h1/t_sl",$data);		
	}
	public function detail_scwan3()
	{				
		$id = $this->input->post("id_penerimaan_unit");
		$data['isi']    = $this->page;	
		$data['dt_pu']	= $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
                  tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND
                  tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode WHERE no_rangka IS NOT NULL)");
		$data['title']	= $this->title;						
		$this->load->view("h1/t_scan",$data);		
	}
	public function detail_scan()
	{				
		$id = $this->input->post("id_penerimaan_unit");
		$data['isi']    = $this->page;	
		$data['dt_pu']	= $this->db->query("SELECT tr_shipping_list.no_mesin, tr_shipping_list.no_rangka, tr_shipping_list.id_modell, tr_shipping_list.id_warna 
					FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
					tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND
					tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode WHERE no_rangka IS NOT NULL )");
		$data['title']	= $this->title;						
		$this->load->view("h1/t_scan",$data);		
	}

	public function detail_scan2()
	{				
		$id = $this->input->post("id_penerimaan_unit");
		$data['isi']    = $this->page;	
		$data['dt_pu']	= $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
                  tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND
                  tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode WHERE no_rangka IS NOT NULL)");
		$data['title']	= $this->title;						
		$this->load->view("h1/t_scan2",$data);		
	}
	public function cek_fifo(){
		echo $this->m_admin->cari_fifo("2019");
	}
}
