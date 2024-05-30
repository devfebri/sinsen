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
		// $get_data  = $this->db->query("");
		$this->template($data);			
	}	

	public function fetch_detail()
	{				
		$id = $this->input->get('postData');	

		var_dump($id);
		die();

		// $data['isi']    = $this->page;		
		// $data['isi']    = $this->page;		
		// $data['title']	= $this->title;
		// $data['sales_force_target']		= $this->db->query("SELECT * from tr_target_sales_force_md where no_register_target_sales='$id'")->row();
		// $data['sales_force_dealer']    = $this->db->query("SELECT sfd.id_dealer,md.nama_dealer,sf.status, sum(jumlah) as jumlah
		// from tr_target_sales_force_md_detail sfd 
		// left join tr_target_sales_force_md sf on sfd.no_register_target_sales = sf.no_register_target_sales 
		// left join ms_dealer md on md.kode_dealer_md = sfd.id_dealer
		// where sf.no_register_target_sales='$id'
		// group by sfd.id_dealer ")->result();

		$data['set'] = "detail";
		$this->template($data);	
	}	

	public function detail_dealer(){
		$id = $this->input->get('id');	
		$data['isi']    = $this->page;		
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		
		$data['sales_force_target']		= $this->db->query("SELECT * from tr_target_sales_force_md where no_register_target_sales='$id'")->row();
		
		$data['sales_force_dealer']    = $this->db->query("SELECT sfd.id_dealer,md.nama_dealer,sf.status, sum(jumlah) as jumlah
		from tr_target_sales_force_md_detail sfd 
		left join tr_target_sales_force_md sf on sfd.no_register_target_sales = sf.no_register_target_sales 
		left join ms_dealer md on md.kode_dealer_md = sfd.id_dealer
		where sf.no_register_target_sales='$id'
		group by sfd.id_dealer ")->result();

		$data['sales_force_detail']		   = $this->db->query("SELECT * from tr_target_sales_force_md_detail where no_register_target_sales='$id' order by id_dealer desc")->result();
		
		
		$data['set'] = "detail";
		$this->template($data);	
	
	}

	public function detail(){

		$id = $this->input->get('id');	
		$data['isi']    = $this->page;		
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		
		$data['sales_force_target']		= $this->db->query("SELECT * from tr_target_sales_force_md where no_register_target_sales='$id'")->row();
		// $data['sales_force_header_in'] = $this->db->query("SELECT GROUP_CONCAT(DISTINCT(CONCAT('\'', id_dealer, '\'')) ORDER BY id_dealer ASC) AS concatenated_dealers FROM tr_target_sales_force_md_detail")->row();
		$data['sales_force_header']    = $this->db->query("SELECT id_dealer from tr_target_sales_force_md_detail  group by id_dealer ")->result();
		// $data['sales_force_kendaraan'] = $this->db->query("SELECT id_tipe_kendaraan from tr_target_sales_force_md_detail GROUP BY id_tipe_kendaraan order by id_tipe_kendaraan asc")->result();
		// $data['sales_force']		   = $this->db->query("SELECT * from tr_target_sales_force_md_detail ")->result();
		$data['sales_force']		   = $this->db->query("SELECT * from tr_target_sales_force_md_detail group by id_tipe_kendaraan ")->result();

			$temp = array();
			foreach($data['sales_force'] as $row){ 
				$temp [$row->id_tipe_kendaraan]=$row->id_tipe_kendaraan;
			}



		// $temp = array();
		// 	foreach($data['sales_force_kendaraan'] as $row){ 
		// 		foreach($data['sales_force_header'] as $deal){ 
		// 			foreach($data['sales_force'] as $set){
		// 				if ($row->id_tipe_kendaraan ==  $set->id_tipe_kendaraan){
		// 					if ($deal->id_dealer ==  $set->id_dealer){
		// 						$temp[$row->id_tipe_kendaraan][]=  $set->jumlah."-".$set->id_dealer;
		// 					}
		// 					// else{
		// 					// 	$temp[$row->id_tipe_kendaraan][]=  0;
		// 					// }
		// 				}
		// 			}	
		// 		} 
		// 	}

			
// $temp = [];

// // Iterate over each vehicle type
// foreach ($data['sales_force_kendaraan'] as $vehicleType) {
//     // Initialize an array for the current vehicle type
//     $temp[$vehicleType->id_tipe_kendaraan] = [];

//     // Iterate over each dealer
//     foreach ($data['sales_force_header'] as $dealer) {
//         // Find the matching data in sales_force
//         $matchingData = array_filter($data['sales_force'], function ($set) use ($vehicleType, $dealer) {
//             return $set->id_tipe_kendaraan == $vehicleType->id_tipe_kendaraan && $set->id_dealer == $dealer->id_dealer;
//         });

//         // Calculate the total for the current dealer and vehicle type
//         $total = count($matchingData) > 0 ? array_sum(array_column($matchingData, 'jumlah')) : 0;

//         // Add the total to the array
//         $temp[$vehicleType->id_tipe_kendaraan][] = $total;
//     }
// }


		// var_dump($temp);
		// die();
		

		$data['set'] = "detail";
		$this->template($data);	
	}


	public function upload_sales_force(){

		$month_headre = $this->input->post('month');	


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
				$dealer = explode(';', $cleanedString);
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

					$column1 = $row[0];
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
			'priode_target' => $currentMonth,
			'status' => 'created_by_md',
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
			$cek_dealer = $this->db->query("SELECT id_dealer from ms_dealer where kode_dealer_md = '$modifiedStrings' AND active=1")->num_rows();
			if ($cek_dealer == 0) {
		
			  $error[$initial][] = "ID Dealer {$modifiedStrings} tidak ditemukan";
			}

			$set = $initial++;
			foreach ($userDetailsArray as $detailString) {

				$userDetails = explode(";", $detailString);
				$tipe_kendaraan = substr(trim($userDetails[0], '"'), 0, 3);
				$numericValue = trim($userDetails[$set], '"');

				$cek_tipe_kendaraan = $this->db->query("SELECT id_tipe_kendaraan from ms_tipe_kendaraan where id_tipe_kendaraan = '$tipe_kendaraan' AND active=1")->num_rows();
				if ($cek_tipe_kendaraan == 0) {
				  $error[$initial][] = "Tipe Kendaraan {$tipe_kendaraan} tidak ditemukan";
				}

				if ($numericValue == 0 || $numericValue == '' ) {
					$error[$initial][] = "Jumlah {$numericValue} tidak boleh kosong";
				}

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