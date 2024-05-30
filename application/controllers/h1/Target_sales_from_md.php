<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';


class Target_sales_from_md extends CI_Controller {

    var $tables =   "tr_target_sales_force_md";	
    var $tables_detail =   "tr_target_sales_force_md_detail";	
		var $folder =   "h1";
		var $page		=		"target_sales_from_md";
    var $pk     =   "no_register_target_sales";
    var $title  =   "Target Sales Force MD";

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
		$data['sales_force']	= $this->m_admin->getAll($this->tables);
		$this->template($data);			
	}	


	public function update_urut_target_tipe_kendaraan(){
	
		$tipe_kendaraan = $this->input->post('tipe_kendaraan');	
		$tems = array();

		foreach ($tipe_kendaraan as $key => $item) {
			
			$key = $key +1;
			$temp = array(
				'id_tipe_kendaraan' => $item,  
				'no_urut' => $key,
			);
			$tems[] = $temp;
			$this->db->where('id_tipe_kendaraan', $item); 
			$this->db->update('ms_tipe_kendaraan_urut', $temp);
		}

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";	

		$redirect_url = '/h1/target_sales_from_md/setting_urut_tipe_kendaraan';
		redirect($redirect_url);

	}
	

	public function detail_dealer()
	{

		$no_register_target_sales = $this->input->get('id');	

		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		
		//  $this->db->query("SELECT * from tr_target_sales_force_md where no_register_target_sales='$no_register_target_sales'")->row();
		// $data['sales_force_dealer']    = $this->db->query("SELECT sfd.id_dealer,md.nama_dealer,sf.status, sum(jumlah) as jumlah
		// from tr_target_sales_force_md_detail sfd 
		// left join tr_target_sales_force_md sf on sfd.no_register_target_sales = sf.no_register_target_sales 
		// left join ms_dealer md on md.kode_dealer_md = sfd.id_dealer
		// where sf.no_register_target_sales='$no_register_target_sales' group by sfd.id_dealer ")->result();
		// $data['flp_sales_tipe_kendaraan'] = $this->db->query("SELECT   
		// sfc.id_tipe_kendaraan , tku.no_urut ,
		// CASE WHEN tku.no_urut IS NULL THEN 99 ELSE tku.no_urut END as no_uruts
		// from  tr_target_sales_force_md_detail sfc 
		// LEFT JOIN ms_tipe_kendaraan_urut tku ON sfc.id_tipe_kendaraan = tku.id_tipe_kendaraan
		// where
		// sfc.no_register_target_sales = '$no_register_target_sales'
		// group by 
		// sfc.id_tipe_kendaraan  
		// order by no_uruts  asc")->result();
		// $data['flp_sales_kode_dealer'] = $this->db->query("SELECT   
		// sfc.id_dealer, md.nama_dealer 
		// from  tr_target_sales_force_md_detail sfc 
		// left join ms_dealer md on sfc.id_dealer = md.kode_dealer_md 
		// where
		// sfc.no_register_target_sales = '$no_register_target_sales'
		// group by 
		// sfc.id_dealer  
		// order by sfc.id_dealer asc")->result();
		
		$this->db->select('*');
		$this->db->from('tr_target_sales_force_md');
		$this->db->where('no_register_target_sales', $no_register_target_sales);
		$data['sales_force_target'] = $this->db->get()->row();

		$this->db->select('sfd.id_dealer, md.nama_dealer, sf.status, SUM(sfd.jumlah) as jumlah');
		$this->db->from('tr_target_sales_force_md_detail sfd');
		$this->db->join('tr_target_sales_force_md sf', 'sfd.no_register_target_sales = sf.no_register_target_sales', 'left');
		$this->db->join('ms_dealer md', 'md.kode_dealer_md = sfd.id_dealer', 'left');
		$this->db->where('sf.no_register_target_sales', $no_register_target_sales);
		$this->db->group_by('sfd.id_dealer');
		$data['sales_force_dealer'] = $this->db->get()->result();

		$this->db->select('sfc.id_tipe_kendaraan, tku.no_urut');
		$this->db->select('(CASE WHEN tku.no_urut IS NULL THEN 99 ELSE tku.no_urut END) as no_uruts', FALSE);
		$this->db->from('tr_target_sales_force_md_detail sfc');
		$this->db->join('ms_tipe_kendaraan_urut tku', 'sfc.id_tipe_kendaraan = tku.id_tipe_kendaraan', 'LEFT');
		$this->db->where('sfc.no_register_target_sales', $no_register_target_sales);
		$this->db->group_by('sfc.id_tipe_kendaraan');
		$this->db->order_by('no_uruts', 'ASC');
		$data['flp_sales_tipe_kendaraan'] = $this->db->get()->result();

		$this->db->select('sfc.id_dealer, md.nama_dealer');
		$this->db->from('tr_target_sales_force_md_detail sfc');
		$this->db->join('ms_dealer md', 'sfc.id_dealer = md.kode_dealer_md', 'left');
		$this->db->where('sfc.no_register_target_sales', $no_register_target_sales);
		$this->db->group_by('sfc.id_dealer');
		$this->db->order_by('sfc.id_dealer', 'asc');
		$data['flp_sales_kode_dealer'] = $this->db->get()->result();

		$array       = [];
		$array_total = [];

		foreach ($data['flp_sales_kode_dealer'] as $key => $item ){

			$array[$key]['id_dealer'] = $item->id_dealer;
			$array[$key]['nama_dealer'] = $item->nama_dealer;

			foreach ($data['flp_sales_tipe_kendaraan'] as $innerKey => $keys) {

				$filter = [
					'flp'       => $keys->id_flp_md,
					'id_dealer' => $item->id_dealer,
					'no_register_target_sales' => $no_register_target_sales,
					'tipe_kendaraan'           => $keys->id_tipe_kendaraan
				];

				$sets = $this->get_kendaraan_sales($filter);
				$array_total[$keys->id_tipe_kendaraan][] =  $sets->jumlah;
				$array[$key]['sales_data'][$innerKey] = [
					'tipe_kendaraan' => $keys->id_tipe_kendaraan,
					'jumlah' => $sets->jumlah,
				];
			}
		}

		$data['sales_force_detail']= $array;
		$data['sales_force_detail_footer']= $array_total;

		$data['set'] = "detail_dealer";
		$this->template($data);	
	}

	public function download_target_tipe_kendaraan()
	{
		$no_register_target_sales = $this->input->get('id');

		// $data['flp_sales_tipe_kendaraan'] = $this->db->query("SELECT   
		// sfc.id_tipe_kendaraan,
		// CASE WHEN urut.no_urut IS NULL THEN 99 ELSE urut.no_urut END as no_urut
		// from  tr_target_sales_force_md_detail sfc 
		// left join ms_tipe_kendaraan_urut urut on  sfc.id_tipe_kendaraan = urut.id_tipe_kendaraan 
		// where
		// sfc.no_register_target_sales = '$no_register_target_sales'
		// group by 
		// sfc.id_tipe_kendaraan  
		// order by  no_urut asc")->result();
		// $data['flp_sales_kode_dealer'] = $this->db->query("SELECT   
		// sfc.id_dealer, md.nama_dealer 
		// from  tr_target_sales_force_md_detail sfc 
		// left join ms_dealer md on sfc.id_dealer = md.kode_dealer_md 
		// where
		// sfc.no_register_target_sales = '$no_register_target_sales'
		// group by 
		// sfc.id_dealer  
		// order by sfc.id_dealer asc")->result();

		$this->db->select('sfc.id_tipe_kendaraan');
		$this->db->select('(CASE WHEN urut.no_urut IS NULL THEN 99 ELSE urut.no_urut END) as no_urut', FALSE);
		$this->db->from('tr_target_sales_force_md_detail sfc');
		$this->db->join('ms_tipe_kendaraan_urut urut', 'sfc.id_tipe_kendaraan = urut.id_tipe_kendaraan', 'left');
		$this->db->where('sfc.no_register_target_sales', $no_register_target_sales);
		$this->db->group_by('sfc.id_tipe_kendaraan');
		$this->db->order_by('no_urut', 'asc');
		$data['flp_sales_tipe_kendaraan'] = $this->db->get()->result();

		$this->db->select('sfc.id_dealer, md.nama_dealer');
		$this->db->from('tr_target_sales_force_md_detail sfc');
		$this->db->join('ms_dealer md', 'sfc.id_dealer = md.kode_dealer_md', 'left');
		$this->db->where('sfc.no_register_target_sales', $no_register_target_sales);
		$this->db->group_by('sfc.id_dealer');
		$this->db->order_by('sfc.id_dealer', 'asc');
		$data['flp_sales_kode_dealer'] = $this->db->get()->result();

		$array       = [];
		$array_total = [];

		foreach ($data['flp_sales_kode_dealer'] as $key => $item ){
			$array[$key]['id_dealer'] = $item->id_dealer;
			$array[$key]['nama_dealer'] = $item->nama_dealer;
			foreach ($data['flp_sales_tipe_kendaraan'] as $innerKey => $keys) {
				$filter = [
					'flp'       => $keys->id_flp_md,
					'id_dealer' => $item->id_dealer,
					'no_register_target_sales' => $no_register_target_sales,
					'tipe_kendaraan'           => $keys->id_tipe_kendaraan
				];

				$sets = $this->get_kendaraan_sales($filter);

				$array_total[$keys->id_tipe_kendaraan][] =  $sets->jumlah;
				$array[$key]['sales_data'][$innerKey] = [
					'tipe_kendaraan' => $keys->id_tipe_kendaraan,
					'jumlah' => $sets->jumlah,
				];
			}
		}

		$data['sales_force_detail']= $array;
		$data['sales_force_detail_footer']= $array_total;
		$this->load->view('h1/report/template/temp_target_sales_from_md_tipe_kendaraan',$data);

	}

	public function updated_target_tipe()
	{
		$id = $_POST['no_register'];
		$tipe_kendaraan = $_POST['sales']['tipe_kendaraan'];
		$kode_dealer = $_POST['sales']['kode_dealer'];
		$jumlah = $_POST['sales']['jumlah'];
	    $waktu 	= gmdate("y-m-d h:i:s", time()+60*60*7);

		for ($i = 0; $i < count($tipe_kendaraan); $i++) {
			$set[] = array(
				'tipe_kendaraan' => $tipe_kendaraan[$i],
				'kode_dealer' => $kode_dealer[$i],
				'jumlah' => $jumlah[$i]
			);
		}

		if ($_POST['submit_button'] == 'approve' ){
			$data_header = array(
				'approve_md_created_at' => $waktu,
				'approve_md_by' => 1, 
				'status' => 'approve', 
			);
			$this->db->where('no_register_target_sales', $id); 
			$this->db->update('tr_target_sales_force_md', $data_header);
		}


		foreach($set as  $row){
			$data = array(
				'jumlah' => $row['jumlah'],
			);
			$this->db->where('no_register_target_sales', $id); 
			$this->db->where('id_dealer', $row['kode_dealer']); 
			$this->db->where('id_tipe_kendaraan', $row['tipe_kendaraan']); 
			$this->db->update('tr_target_sales_force_md_detail', $data);
		} 

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('pesan', 'Data has been updated successfully');
			$this->session->set_flashdata('tipe', 'success');
		} 

		$redirect_url = '/h1/target_sales_from_md/detail_dealer?id='.$id;
		redirect($redirect_url);
	}

	public function setting_urut_tipe_kendaraan(){

		$data['isi']    = "setting_urut_tipe_kendaraan";		
		$data['title']	= "Setting Urut Tipe Kendaraan";
		$data['set']	= "setting_urut_tipe_kendaraan";
		$this->db->select('tk.id_tipe_kendaraan, tk.tipe_ahm, tku.no_urut, tk.active');
		$this->db->select('(CASE WHEN tku.no_urut IS NULL THEN 99 ELSE tku.no_urut END) as no_urut', FALSE);
		$this->db->from('ms_tipe_kendaraan tk');
		$this->db->join('ms_tipe_kendaraan_urut tku', 'tk.id_tipe_kendaraan = tku.id_tipe_kendaraan', 'LEFT');
		$this->db->where('tk.active', '1');
		$this->db->order_by('no_urut', 'asc');
		$data['tipe_kendaraan'] = $this->db->get()->result();
		$this->template($data);	
	}
	

	public function get_kendaraan_sales($filter){

		$no_register = $filter['no_register_target_sales'];
		$tipe_kendaraan = $filter['tipe_kendaraan'];
		$id_dealer = $filter['id_dealer'];

		$query = $this->db->query("SELECT   
		sfc.id_dealer ,sfc.id_tipe_kendaraan,sfc.jumlah
		from  tr_target_sales_force_md_detail sfc 
		where
		sfc.no_register_target_sales = '$no_register' and sfc.id_tipe_kendaraan ='$tipe_kendaraan' and sfc.id_dealer ='$id_dealer'
		group by 
		sfc.id_dealer  
		order by sfc.id_dealer asc")->row();
		return $query;

	}


	public function detail(){

		$id = $this->input->get('id');	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		
		$data['sales_force_target']		= $this->db->query("SELECT * from tr_target_sales_force_md where no_register_target_sales='$id'")->row();
		$data['sales_force_header']    = $this->db->query("SELECT id_dealer from tr_target_sales_force_md_detail  group by id_dealer ")->result();
		$data['sales_force']		   = $this->db->query("SELECT * from tr_target_sales_force_md_detail group by id_tipe_kendaraan ")->result();

		$temp = array();
		foreach($data['sales_force'] as $row){ 
			$temp [$row->id_tipe_kendaraan]=$row->id_tipe_kendaraan;
		}

		$data['set'] = "detail";
		$this->template($data);	
	}


	public function upload_sales_force(){

		$month_header = $this->input->post('inputValue');	
		// $month_header = 4;	
		$ym = date('Y/m');
		$path = "./uploads/sales_force_from_md/";
		$config['upload_path']   = $path;
		$config['allowed_types'] = '*';
		$config['max_size']      = '10024';
		$config['remove_spaces'] = TRUE;
		$config['overwrite']     = TRUE;
		$this->upload->initialize($config);
		$error = array();

		if ($this->upload->do_upload('file_upload')) {
		  $new_path = substr($path, 2, 40);
		  $filename = $this->upload->file_name;
		  $path_file = $new_path . '/' . $filename;
		} else {
		  $error = clear_removed_html($this->security->xss_clean($this->upload->display_errors()));
		}

		$csv_file = $path_file;

		if (file_exists($csv_file)) {
			$file_handle = fopen($csv_file, 'r');
			if ($file_handle !== false) {
				$header_line = fgets($file_handle);
				$cleanedString = str_replace(' ', '', $header_line);

				if (strpos($cleanedString, ',') !== false) {
					$newString = str_replace(',', ';', $cleanedString);
					$dealer = explode(';', $newString);
				} else {
					$dealer = explode(';', $cleanedString);
				}

				unset($dealer[0]);
				$userArray = array_values($dealer);
				fclose($header_line);
			} 
		} 

		$modifiedArray = array_map(function($value) {
			return str_replace(' ', '', $value);
		}, $userArray);

		
		$userDetailsArray = array();
		$no = 0;
		if (file_exists($csv_file)) {
			$file_handle = fopen($csv_file, 'r');

			if ($file_handle !== false) {
				while (($row = fgetcsv($file_handle)) !== false) {
					$no++;
					if ($no == 1) continue;
					// check semicolon or comma
					if(count($row) >1 ){
						$resultString = implode(';', $row);
						$column1 =$resultString;
					}else{
						$column1 = $row[0];
					}
					$userDetailsArray[]=$column1;
				}
				fclose($file_handle);
			}
		} 

		$currentMonth = date('m');
        $currentYear = date('Y');
        $this->db->select('COUNT(1) as max_code');
        $query = $this->db->get('tr_target_sales_force_md');
        $row = $query->row();
        $maxCode = $row->max_code;
        $numericPart = substr($maxCode, -4);
        $nextNumericPart = str_pad((int)$numericPart + 1, 4, '0', STR_PAD_LEFT);
        $newCode = "SFMD/{$currentMonth}/{$currentYear}/{$nextNumericPart}";
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		

		$header_set_array = array(
			'no_register_target_sales' => $newCode,
			'priode_target' => $month_header,
			'status' => 'draft',
			'created_by' => $login_id,
			'created_at' => $waktu
		);

		$result = array();
		$initial = 1;

		foreach ($modifiedArray  as $id_dealer) {
			$check_space_dealer= str_replace(' ', '', $id_dealer);
			$charactersToRemove = array(' ', '\r', '\n');
			$modifiedString = str_replace($charactersToRemove, '', $check_space_dealer);
			$modifiedStrings=trim($modifiedString);
			$cek_dealer = $this->db->select('id_dealer')
			->from('ms_dealer')
			->where('kode_dealer_md', $modifiedStrings)
			->where('active', 1)
			->get()
			->num_rows();

			if ($cek_dealer == 0) {
			  $error[$initial][] = "ID Dealer {$modifiedStrings} tidak ditemukan";
			}
			$set = $initial++;

			foreach ($userDetailsArray as $detailString) {

				if (strpos($detailString, ';') !== false) {
					$userDetails = explode(';', $detailString);
				} else {
					$dealer = explode(';', $cleanedString);
				}

				$tipe_kendaraan = substr(trim($userDetails[0], '"'), 0, 3);
				$numericValue = trim($userDetails[$set], '"');
				$cek_tipe_kendaraan = $this->db->select('id_tipe_kendaraan')
                                ->from('ms_tipe_kendaraan')
                                ->where('id_tipe_kendaraan', $tipe_kendaraan)
                                ->get()
                                ->num_rows();
				if ($cek_tipe_kendaraan == 0) {
				  $error[$initial][] = "Tipe Kendaraan {$tipe_kendaraan} tidak ditemukan";
				}

				// if ($numericValue == 0 || $numericValue == '' ) {
				// 	$error[$initial][] = "Jumlah {$numericValue} tidak boleh kosong";
				// }

				$set_array = array(
					'id_dealer'      		   => $modifiedStrings,
					'id_tipe_kendaraan' 	   => $tipe_kendaraan,
					'jumlah'         		   => $numericValue,
					'no_register_target_sales' => $newCode,
				);
				$result[] = $set_array;
			}
		}

		if (count($error)>0){
			$check = 0;
		}else{
			$this->db->insert_batch('tr_target_sales_force_md', [$header_set_array]);
			if ($this->db->affected_rows() > 0) {
				$this->db->insert_batch('tr_target_sales_force_md_detail', $result);
				$_SESSION['pesan'] = "Data has been saved successfully";
				$_SESSION['tipe'] = "success";

				$check = 1;
			} else {
				$_SESSION['pesan'] = "Detail Tipe Kendaraan tidak boleh kosong";
				$_SESSION['tipe'] = "danger";
				$redirect_url = '/h1/target_sales_from_md/';
				redirect($redirect_url);
			}
		}


		if ($check == 0 ){
			$response = [
				'status' => 1,
					'pesan' => "Terjadi kesalahan validasi",
					'data' =>$error,
			];
		}else{
			$response = [
				'status' => 0,
			  ];
		}
		  send_json($response);

}

public function delete(){
	$id = $this->input->get('id');	
	$_SESSION['pesan'] = "Data has been delete successfully";
	$_SESSION['tipe'] = "success";
	$this->db->delete($this->tables,['no_register_target_sales'=>$id]);
	$this->db->delete($this->tables_detail,['no_register_target_sales'=>$id]);
	return 	header("Location: " . base_url() . "h1/target_sales_from_md/");
}


public function approve_from_md(){
	
	$id = $this->input->get('id'); 
	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

	$data = array(
		'approve_md_created_at' => $waktu,
		'approve_md_by' => 1, 
		'status' => 'approve_by_md', 
	);

	$this->db->where('no_register_target_sales', $id); 
	$this->db->update('tr_target_sales_force_md', $data);
	
	if ($this->db->affected_rows() > 0) {
		$this->session->set_flashdata('pesan', 'Data has been updated successfully');
		$this->session->set_flashdata('tipe', 'success');
	} else {
		$this->session->set_flashdata('pesan', 'No records were updated');
		$this->session->set_flashdata('tipe', 'error');
	}

	$redirect_url = '/h1/target_sales_from_md/detail_dealer?id='.$id;
	redirect($redirect_url);

}


public function reject_from_md(){
	$id = $this->input->get('id'); 
	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

	$data = array(
		'status' => 'reject_by_md'
	);

	$this->db->where('no_register_target_sales', $id); 
	$this->db->update('tr_target_sales_force_md', $data);
	
	if ($this->db->affected_rows() > 0) {
		$this->session->set_flashdata('pesan', 'Data has been updated successfully');
		$this->session->set_flashdata('tipe', 'success');
	} else {
		$this->session->set_flashdata('pesan', 'No records were updated');
		$this->session->set_flashdata('tipe', 'error');
	}

	$redirect_url = '/h1/target_sales_from_md/detail_dealer?id='.$id;
	redirect($redirect_url);

}


			
}