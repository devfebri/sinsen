<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengangkatan_dealer extends CI_Controller {

    var $tables =   "ms_pengangkatan_dealer";	
		var $folder =   "master";
		var $page		=		"pengangkatan_dealer";
    var $pk     =   "no_sp3d";
    var $title  =   "Master Data Pengangkatan Dealer";
    

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
		$data['dt_pengangkatan_dealer'] = $this->db->query("SELECT ms_pengangkatan_dealer.*,ms_provinsi.provinsi,ms_kabupaten.kabupaten,ms_kecamatan.kecamatan, 
																ms_kelurahan.kelurahan FROM ms_pengangkatan_dealer INNER JOIN ms_provinsi
																ON ms_pengangkatan_dealer.id_provinsi=ms_provinsi.id_provinsi INNER JOIN ms_kabupaten
																ON ms_pengangkatan_dealer.id_kabupaten=ms_kabupaten.id_kabupaten INNER JOIN ms_kecamatan
																ON ms_pengangkatan_dealer.id_kecamatan=ms_kecamatan.id_kecamatan INNER JOIN ms_kelurahan
																ON ms_pengangkatan_dealer.id_kelurahan=ms_kelurahan.id_kelurahan ORDER BY no_sp3d ASC");
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['dt_dealer'] = 	$this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");	
		$data['dt_provinsi'] = 	$this->m_admin->getSort("ms_provinsi","provinsi","ASC");	
		$data['dt_kabupaten'] = 	$this->m_admin->getSort("ms_kabupaten","kabupaten","ASC");	
		$data['dt_kecamatan'] = 	$this->m_admin->getSort("ms_kecamatan","kecamatan","ASC");	
		// $data['dt_kelurahan'] = 	$this->m_admin->getSort("ms_kelurahan","kelurahan","ASC");	
		$data['set']		= "insert";									
		$this->template($data);	
	}
	public function get_provinsi(){
		$id_provinsi		= $this->input->post('id_provinsi');	
		$dt_kabupaten		= $this->m_admin->getByID("ms_kabupaten","id_provinsi",$id_provinsi);								
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_kabupaten->result() as $row) {
			$data .= "<option value='$row->id_kabupaten'>$row->kabupaten</option>\n";
		}
		echo $data;
	}
	public function get_kabupaten(){
		$id_kabupaten		= $this->input->post('id_kabupaten');	
		$dt_kecamatan		= $this->m_admin->getByID("ms_kecamatan","id_kabupaten",$id_kabupaten);								
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_kecamatan->result() as $row) {
			$data .= "<option value='$row->id_kecamatan'>$row->kecamatan</option>\n";
		}
		echo $data;
	}
	public function get_kecamatan(){
		$id_kecamatan		= $this->input->post('id_kecamatan');	
		$dt_kelurahan		= $this->m_admin->getByID("ms_kelurahan","id_kecamatan",$id_kecamatan);								
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_kelurahan->result() as $row) {
			$data .= "<option value='$row->id_kelurahan'>$row->kelurahan</option>\n";
		}
		echo $data;
	}
	public function get_kelurahan(){
		$id_kelurahan		= $this->input->post('id_kelurahan');	
		$dt_all		= $this->db->query("SELECT * FROM ms_kecamatan INNER JOIN ms_kelurahan 
											ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan INNER JOIN ms_kabupaten
											ON ms_kecamatan.id_kabupaten=ms_kabupaten.id_kabupaten INNER JOIN ms_provinsi
											ON ms_kabupaten.id_provinsi=ms_provinsi.id_provinsi
											WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");								
		$row = $dt_all->row();
		echo "ok"."|".$row->id_kecamatan."|".$row->kecamatan."|".$row->id_kabupaten."|".$row->kabupaten."|".$row->id_provinsi."|".$row->provinsi;			
		// $data .= "<option value=''>- choose -</option>";
		// foreach ($dt_all->result() as $row) {
		// 	$data .= "<option value='$row->id_kecamatan'>$row->kecamatan</option>\n";
		// }
		// echo $data;
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['no_sp3d'] 			= $this->input->post('no_sp3d');		
			$data['id_dealer'] 			= $this->input->post('id_dealer');		
			$data['tgl_diangkat'] 	= $this->input->post('tgl_diangkat');		
			$data['tgl_mulai'] 	= $this->input->post('tgl_mulai');		
			$data['tgl_selesai'] 	= $this->input->post('tgl_selesai');		
			if($this->input->post('h1') == '1') $data['h1'] = $this->input->post('h1');		
				else $data['h1'] = "";							
			if($this->input->post('h2') == '1') $data['h2'] = $this->input->post('h2');		
				else $data['h2'] = "";							
			if($this->input->post('h3') == '1') $data['h3'] = $this->input->post('h3');		
				else $data['h3'] = "";							
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] = "";
			$data['pemilik'] 				= $this->input->post('pemilik');				
			$data['penanggung_jawab'] 				= $this->input->post('penanggung_jawab');				
			$data['alamat1'] 				= $this->input->post('alamat1');				
			$data['alamat2'] 				= $this->input->post('alamat2');				
			$data['id_provinsi'] 		= $this->input->post('id_provinsi');				
			$data['id_kabupaten'] 	= $this->input->post('id_kabupaten');				
			$data['id_kecamatan'] 	= $this->input->post('id_kecamatan');				
			$data['id_kelurahan'] 	= $this->input->post('id_kelurahan');				
			$data['kode_pos'] 			= $this->input->post('kode_pos');				
			$data['no_telp'] 				= $this->input->post('no_telp');				
			$data['no_fax'] 				= $this->input->post('no_fax');				
			$data['mail_pribadi'] 	= $this->input->post('mail_pribadi');				
			$data['mail_heps'] 			= $this->input->post('mail_heps');				
			$data['mail_acs'] 			= $this->input->post('mail_acs');				
			$data['mail_uskm'] 			= $this->input->post('mail_uskm');				
			$data['mail_asmd'] 			= $this->input->post('mail_asmd');				
			$data['mail_antena'] 		= $this->input->post('mail_antena');				
			$data['status_bintang'] = $this->input->post('status_bintang');				
			$data['no_surat'] 			= $this->input->post('no_surat');				
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/pengangkatan_dealer/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
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
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/pengangkatan_dealer'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_pengangkatan_dealer'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_dealer'] = 	$this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");	
		$data['dt_provinsi'] = 	$this->m_admin->getSort("ms_provinsi","provinsi","ASC");	
		$data['dt_kabupaten'] = 	$this->m_admin->getSort("ms_kabupaten","kabupaten","ASC");	
		$data['dt_kecamatan'] = 	$this->m_admin->getSort("ms_kecamatan","kecamatan","ASC");	
		$data['dt_kelurahan'] = 	$this->m_admin->getSort("ms_kelurahan","kelurahan","ASC");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');

		$tabel					= $this->tables;
		$pk 						= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['no_sp3d'] 			= $this->input->post('no_sp3d');		
			$data['id_dealer'] 			= $this->input->post('id_dealer');		
			$data['tgl_diangkat'] 	= $this->input->post('tgl_diangkat');		
			$data['tgl_mulai'] 	= $this->input->post('tgl_mulai');		
			$data['tgl_selesai'] 	= $this->input->post('tgl_selesai');		
			if($this->input->post('h1') == '1') $data['h1'] = $this->input->post('h1');		
				else $data['h1'] = "";							
			if($this->input->post('h2') == '1') $data['h2'] = $this->input->post('h2');		
				else $data['h2'] = "";							
			if($this->input->post('h3') == '1') $data['h3'] = $this->input->post('h3');		
				else $data['h3'] = "";							
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] = "";
			$data['pemilik'] 				= $this->input->post('pemilik');				
			$data['penanggung_jawab'] 				= $this->input->post('penanggung_jawab');				
			$data['alamat1'] 				= $this->input->post('alamat1');				
			$data['alamat2'] 				= $this->input->post('alamat2');				
			$data['id_provinsi'] 		= $this->input->post('id_provinsi');				
			$data['id_kabupaten'] 	= $this->input->post('id_kabupaten');				
			$data['id_kecamatan'] 	= $this->input->post('id_kecamatan');				
			$data['id_kelurahan'] 	= $this->input->post('id_kelurahan');				
			$data['kode_pos'] 			= $this->input->post('kode_pos');				
			$data['no_telp'] 				= $this->input->post('no_telp');				
			$data['no_fax'] 				= $this->input->post('no_fax');				
			$data['mail_pribadi'] 	= $this->input->post('mail_pribadi');				
			$data['mail_heps'] 			= $this->input->post('mail_heps');				
			$data['mail_acs'] 			= $this->input->post('mail_acs');				
			$data['mail_uskm'] 			= $this->input->post('mail_uskm');				
			$data['mail_asmd'] 			= $this->input->post('mail_asmd');				
			$data['mail_antena'] 		= $this->input->post('mail_antena');				
			$data['status_bintang'] = $this->input->post('status_bintang');				
			$data['no_surat'] 			= $this->input->post('no_surat');						
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/pengangkatan_dealer'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function view()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$page				= $this->page;
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);			
		$data['dt_dealer'] = 	$this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");	
		$data['dt_provinsi'] = 	$this->m_admin->getSort("ms_provinsi","provinsi","ASC");	
		$data['dt_kabupaten'] = 	$this->m_admin->getSort("ms_kabupaten","kabupaten","ASC");	
		$data['dt_kecamatan'] = 	$this->m_admin->getSort("ms_kecamatan","kecamatan","ASC");	
		$data['dt_kelurahan'] = 	$this->m_admin->getSort("ms_kelurahan","kelurahan","ASC");
		$data['dt_pengangkatan_dealer'] = $this->m_admin->getByID($tabel,$pk,$id);
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['set']		= "detail";									
		$this->template($data);
		
	}

	public function fetch_kelurahan()
   {
		$fetch_data = $this->make_query_keluarahan()->result();  
		$data = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array   = array();
			$sub_array[] = $rs->kelurahan;
			$sub_array[] = $rs->kecamatan;
			$sub_array[] = $rs->kabupaten;
			$sub_array[] = $rs->provinsi;
			$row         = json_encode($rs);
			$link        ='<button data-dismiss=\'modal\' onClick=\'return pilihKelurahan('.$row.')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_kec(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   function make_query_keluarahan($no_limit=null)  
   	{  
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('kelurahan','kecamatan','kabupaten','provinsi',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY kecamatan ASC';
		$search       = $this->input->post('search')['value'];
		$searchs = '';
		if ($search!='') {
	      $searchs .= "AND (kelurahan LIKE '%$search%' 
	          OR kecamatan LIKE '%$search%'
	          OR kabupaten LIKE '%$search%'
	          OR provinsi LIKE '%$search%'
	          )
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT * FROM ms_kelurahan
			JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan=ms_kecamatan.id_kecamatan
			JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten=ms_kabupaten.id_kabupaten
			JOIN ms_provinsi ON ms_kabupaten.id_provinsi=ms_provinsi.id_provinsi
   		 	$searchs $order $limit ");
   	}  
   	function get_filtered_data_kec(){  
		return $this->make_query_keluarahan('y')->num_rows();  
   	}
}