<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_ksu extends CI_Controller {

    var $tables =   "tr_penerimaan_unit";	
		var $folder =   "h1";
		var $page		=		"penerimaan_ksu";
    var $pk     =   "id_penerimaan_unit";
    var $title  =   "Penerimaan KSU";

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
		$this->load->model('ev_model');		
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

		// $data['dt_penerimaan_unit'] = $this->db->query("SELECT pu.*,
		// (select COUNT(oem.no_shipping_list) as no_shipping_list  from tr_penerimaan_unit_detail pud left join tr_shipping_list_ev_accoem oem on pud.no_shipping_list= oem.no_shipping_list 
		// where pud.id_penerimaan_unit = pu.id_penerimaan_unit ) as is_ev
		// FROM tr_penerimaan_unit pu WHERE pu.status <> 'close' AND pu.status = 'close scan' ORDER BY pu.id_penerimaan_unit DESC");		


		$data['dt_penerimaan_unit'] = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE status <> 'close' AND status = 'close scan' ORDER BY id_penerimaan_unit DESC");		
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		$this->template($data);		
	}
	
	public function ksu()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Penerimaan KSU";	
		$id 						= $this->input->get("id");	
		$data['set']		= "ksu";		
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		$dq = "SELECT DISTINCT(tr_scan_barcode.tipe_motor),tr_scan_barcode.no_shipping_list,tr_scan_barcode.tipe_motor,ms_tipe_kendaraan.`tipe_ahm`,ms_warna.`warna`,ms_warna.`id_warna`,tr_scan_barcode.`id_item` 
					FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND tr_scan_barcode.status = '1'
					ORDER BY tr_scan_barcode.no_shipping_list ASC";
		$data['dt_rfs'] = $this->db->query($dq);		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	public function scan_oem()
	{
		$id  = $this->input->get('id');
		$data['isi']    = 'scan_oem';
		$data['title']	= "SCAN BATTERY OEM";
		$data['set']	= "scan_oem";
		$data['dt_shipping_list'] = $this->db->query("SELECT * from tr_shipping_list_ev_accoem where no_shipping_list ='$id' ")->result();
		$this->template($data);
	}

	public function penerimaan_oem()
	{
		$data['isi']    = 'Penerimaan KSU - OEM';
		$data['title']	= "Penerimaan KSU - OEM";
		$data['set']	= "detail_oem";
		$data['dt_shipping_list']	= $this->db->query("SELECT oem.*,acc.mdReceiveDate as tgl_penerimaan from tr_shipping_list_ev_accoem oem  left join tr_status_ev_acc acc on oem.serial_number  = acc.serialNo")->result();
		$this->template($data);
	}
		
	public function detail_oem()
	{
		$data['isi']    = 'Penerimaan KSU - OEM';
		$data['title']	= "Penerimaan KSU - OEM";
		$data['set']	= "detail_oem";
		$data['dt_shipping_list']	= $this->db->query("SELECT oem.*,acc.mdReceiveDate as tgl_penerimaan from tr_shipping_list_ev_accoem oem  left join tr_status_ev_acc acc on oem.serial_number  = acc.serialNo")->result();
		$this->template($data);
	}

	public function oem()
	{
		$id  = $this->input->get('id');
		$data['isi']    = 'penerimaan_oem';
		$data['title']	= "Penerimaan OEM";
		$data['set']	= "oem";
		$data['dt_shipping_list'] = $this->db->query("SELECT * from tr_shipping_list_ev_accoem where no_shipping_list ='$id' ")->result();
		$this->template($data);
	}

	public function detail_scan_ev()
	{		
		$penerimaan_oem = $this->input->post('penerimaan_oem');
		$where = "WHERE 1=1 ";

		if (isset($penerimaan_oem)) {
			$where .= " AND pu.id_penerimaan_unit ='$penerimaan_oem'";
			$where .= " AND ev_oem.status_scan ='0'";
		}

		$data['isi']    = $this->page;	
		$data['dt_shipping_list']	= $this->db->query("SELECT ev_oem.*,acc.accStatus_2_processed_at as accStatus_2 from tr_shipping_list_ev_accoem ev_oem left join tr_status_ev_acc acc on acc.serialNo = ev_oem.serial_number 
		left join tr_penerimaan_unit_detail pu on pu.no_shipping_list=ev_oem.no_shipping_list 
		$where 
		");
		$this->load->view("h1/t_scan_ev",$data);		
	}

	public function show_scan()
	{	
		$year = date('Y');
		$this->load->model('ev_model');
		$waktu 			    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		    = $this->session->userdata('id_user');
		$ready_sale     = $this->input->post('ready_sale');
		$penerimaan_oem = $this->input->post('penerimaan_oem');
		$serial_number  = $this->input->post('id');

		$where      = "WHERE 1=1 ";
		$wheresjson = "WHERE 1=1 ";

		if (isset($serial_number)) {
			$where .= " AND status_scan ='0'";
			$where .= " AND serial_number ='$serial_number'";
		}

		if (isset($penerimaan_oem)) {
			$wheresjson .= " AND pu.id_penerimaan_unit ='$penerimaan_oem'";
			$wheresjson .= " AND ev_oem.status_scan ='1'";
		}


		if($serial_number !== NULL){
					$querys = $this->db->query("SELECT * from tr_shipping_list_ev_accoem $where")->row();
					$set_id_part    = $querys->part_id;
					$status = NULL;


					if (count($querys) > 0) {
						$this->db->trans_begin();	

						$set_scan = array(
							'status_scan'=> 1,
						);

							$this->db->where('serial_number', $serial_number);
							$this->db->update('tr_shipping_list_ev_accoem', $set_scan);
				
							if (!$this->db->trans_status()) {
								$this->db->trans_rollback();
								$status = 0;
							} else {
								$status = 1;
								$this->db->trans_commit();
								$id_user = $this->session->userdata('id_user');
				
							$set_acc = array(
								'acc' => '2',
								'accType' =>'B',
								'serial_number' => $serial_number,
								'user' => $id_user,
								'api_from' => $id_user,
							);

							$this->ev_model->UpdateAcc($set_acc);
						}

						$oem = $this->db->query("SELECT * , COUNT(1) as jumlah  from tr_penerimaan_oem pd where pd.id_penerimaan_oem ='$penerimaan_oem'")->row();
						$check_juml=intval($oem->jumlah);


						if ($check_juml > 0) {
							$qty = 1;
							$qty_adjust = (int)$oem->qty + $qty;

							$result = array(
								'id_penerimaan_oem' => $penerimaan_oem,
								'qty' =>    $qty_adjust,
								'updated_by' => $login_id,
								'updated_at' => $waktu,
							);

							$this->db->where('id_penerimaan_oem_int', $oem->id_penerimaan_oem_int);
							$this->db->update('tr_penerimaan_oem', $result);

							$login_id = $this->session->userdata('id_user');
							$fifo     = $this->m_admin->cari_fifo_oem($year);
				
							$detail_oem = array(
								'id_penerimaan_oem' =>$penerimaan_oem,  
								'id_part' 			=>$querys->part_id,    
								'serial_number' 	=>$querys->serial_number, 
								'fifo' 				=>$fifo,  
								'status_md'			=>$ready_sale,     
								'id_user' 			=>$login_id,      
								'id_sj' 			=>NULL,    
							);
							$this->db->insert('tr_penerimaan_oem_detail', $detail_oem);

						} else {

							$qty = 1;
							$qty_adjust = $qty;

							$qty_oem = $this->db->query("SELECT COUNT(1) as jumlah_ahm  from tr_shipping_list_ev_accoem oem left join tr_penerimaan_unit_detail pu 
							on oem.no_shipping_list = pu.no_shipping_list 
							where pu.id_penerimaan_unit ='$penerimaan_oem' 
							group by pu.id_penerimaan_unit ")->row();

							$qty_ahm = $qty_oem->jumlah_ahm;
							$qty_eks = NULL;

							$result = array(
								'id_penerimaan_oem_int' => NULL,
								'id_penerimaan_oem' => $penerimaan_oem,
								'qty' =>$qty_adjust,
								'qty_eks' => $qty_eks,
								'qty_ahm' => $qty_ahm,
								'no_sl'=>$querys->no_shipping_list,
								'status'=> 1,
								'created_by'=>  $login_id,
								'created_at' =>  $waktu,
							);

							$this->db->insert('tr_penerimaan_oem', $result);

							$login_id = $this->session->userdata('id_user');
							$fifo = $this->m_admin->cari_fifo_oem($year);
				
							$detail_oem = array(
								'id_penerimaan_oem' =>$penerimaan_oem,  
								'id_part' 			=>$querys->part_id,    
								'serial_number' 	=>$querys->serial_number, 
								'fifo' 				=>$fifo,  
								'status_md'			=>$ready_sale,     
								'id_user' 			=>$login_id,      
								'id_sj' 			=>NULL,    
							);
							$this->db->insert('tr_penerimaan_oem_detail', $detail_oem);
						}
			}
	}



		$query_result = $this->db->query("SELECT 
		ev_oem.part_id,
		ev_oem.part_desc,
		ev_oem.serial_number,
		ev_oem.no_shipping_list,
		ev_oem.tgl_shipping_list,
		ev_oem.kode_dealer_md,
		ev_oem.created_at,
		acc.accStatus_2_processed_at,
		poem.fifo,
		poem.status_md as status_ready
		from tr_shipping_list_ev_accoem ev_oem left join tr_status_ev_acc acc on acc.serialNo  = ev_oem.serial_number 
		left join tr_penerimaan_unit_detail pu on pu.no_shipping_list=ev_oem.no_shipping_list
		left join tr_penerimaan_oem_detail poem on ev_oem.serial_number =  poem.serial_number
		$wheresjson");

		$result = $query_result->result_array(); 
		
		$status = 1;
        $response = array(
			'status' => $status,
			'data'   => $result,
		);

        header('Content-Type: application/json');
        echo json_encode($response);
	}


	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_ksu 		= $this->input->post('id_ksu');		
		$id_pu 			= $this->input->post('id_pu');		
		$cek = 0;
		foreach($id_ksu AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$id_tipe_kendaraan = $_POST['tipe_motor'][$key];
			$id_warna = $_POST['id_warna'][$key];
			$total_unit  	= $_POST['total_unit'][$key];
		 	$qty  	= $_POST['qty'][$key];
			$no_sl = $_POST['no_sl'][$key];
		 	$result[] = array(
				"id_penerimaan_unit"  => $id_pu,
				"id_ksu"  => $_POST['id_ksu'][$key],
				"qty"  => $_POST['qty'][$key],
				"qty_ahm"  => $_POST['qty_ahm'][$key],
				"qty_eks"  => $_POST['qty_eks'][$key],
				"id_tipe_kendaraan"  => $_POST['tipe_motor'][$key],
				"id_warna"  => $_POST['id_warna'][$key],
				"no_sl"  => $_POST['no_sl'][$key],
				"created_at"  => $waktu,
				"created_by"  => $login_id,
				"status"  => "1"
		 	); 
		 	
		 	$this->m_admin->update_ksu($id_ksu,$qty,"+");

		 	if($total_unit < $qty){
		 		$cek = $cek + 1;		 		
		 	}

		 	$rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$id_ksu' AND id_warna = '$id_warna' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND no_sl = '$no_sl' AND id_penerimaan_unit = '$id_pu'");
      if($rty->num_rows() > 0){
      	$e = $rty->row();      	
      	$this->db->query("DELETE FROM tr_penerimaan_ksu WHERE id_penerimaan_ksu = '$e->id_penerimaan_ksu'");
      }
      	//$cek_ksu_gudang = $this->
		}

		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty KSU tidak boleh lebih dari jumlah unit yg disediakan";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/ksu?id=".$id_pu."'>";
		}else{
			$test2 = $this->db->insert_batch('tr_penerimaan_ksu', $result);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/ksu?id=".$id_pu."'>";
		}		
	}	
	public function close_ksu(){
		$id_pu 			= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		

		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$data['status']						= "close ksu";	
		$this->m_admin->update($tabel,$data,$pk,$id_pu);
		//$this->db->query("UPDATE tr_penerimaan_unit SET status = 'close scan' WHERE id_penerimaan_unit = '$id_pu'");
		$_SESSION['pesan'] 	= "Status has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/'>";
	}	
}