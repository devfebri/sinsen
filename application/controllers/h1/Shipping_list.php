<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Shipping_list extends CI_Controller
{
	var $tables =   "tr_shipping_list";
	var $folder =   "h1";
	var $page		=		"shipping_list";
	var $pk     =   "no_shipping_list";
	var $title  =   "Shipping List (SL)";
	// $this->load->helper('ev_helper');


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
		$this->load->library('csvimport');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}

	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function log_ev()
	{
   	    $data['folder']    = $this->folder;
		$data['isi']    = $this->page;
		$data['title']	= 'Monitoring API EV';
		$data['set']	= "log";
		$data['log'] = $this->db->query("SELECT * from activity_ev_log order by created_at DESC ")->result();
		$this->template($data);
  }

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view";
		$this->template($data);
	}

	public function ev()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Shipping List (SL) EV";
		$data['set']	= "ev";
		$this->template($data);
	}

	public function oem()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Shipping List (OEM) EV";
		$data['set']	= "oem";
		$data['dt_shipping_list']	= $this->db->query("SELECT * from tr_shipping_list_ev_accoem")->result();
		$this->template($data);
	}

	public function fetch_ev()
	{
		$fetch_data = $this->make_query_ev();
		$data       = array();
		$id_menu    = $this->m_admin->getMenu($this->page);
		$group      = $this->session->userdata("group");
		$edit       = $this->m_admin->set_tombol($id_menu, $group, 'edit');
		foreach ($fetch_data->result() as $rs) {
			$bulan   = substr($rs->tgl_sl, 2,2);
          	$tahun   = substr($rs->tgl_sl, 4,4);
          	$tgl     = substr($rs->tgl_sl, 0,2);
			$tanggal = $tgl."-".$bulan."-".$tahun;

			$sub_array   = array();
			$sub_array[] = $rs->no_shipping_list;
			$sub_array[] = $tanggal;
			$sub_array[] = $rs->no_sipb;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$sub_array[] = $rs->cabang;
			$sub_array[] = $rs->no_pol_eks;
			$sub_array[] = $rs->kode_md_qq;
			$sub_array[] = $rs->kode_md_po;
			$sub_array[] = $rs->no_mesin_lengkap;
			$sub_array[] = $rs->no_frame;
			$sub_array[] = $rs->nama_eks;
			$sub_array[] = $rs->kota_tujuan;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data_ev(),
			"data"            => $data
		);
		echo json_encode($output);
	}

	
	public function make_query_ev($no_limit = null)
	{
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";
		if ($no_limit == 'y') $limit = '';
		$group = "group by tsl.no_mesin"; 
		$search = $this->input->post('search')['value'];
		$where = "WHERE 1=1 ";
		$where .= " AND ms_tipe_kendaraan.id_kategori = 'EV' ";

		if ($search != '') {
			$where .= " AND (tsl.no_mesin LIKE '%$search%'
					OR tsl.no_rangka LIKE '%$search%'
					OR tsl.no_shipping_list LIKE '%$search%'
					OR tsl.no_sipb LIKE '%$search%'
					OR tsl.cabang LIKE '%$search%'
					OR tsl.no_pol_eks LIKE '%$search%'
					OR tsl.kode_md_qq LIKE '%$search%'
					OR tsl.kode_md_po LIKE '%$search%'
					OR tsl.no_mesin_lengkap LIKE '%$search%'
					OR tsl.no_frame LIKE '%$search%'
					OR tsl.nama_eks LIKE '%$search%'
					OR tsl.kota_tujuan LIKE '%$search%'
					OR ms_warna.warna LIKE '%$search%'
					OR ms_tipe_kendaraan.deskripsi_ahm LIKE '%$search%'
					OR ms_tipe_kendaraan.deskripsi_samsat LIKE '%$search%'
					OR ms_tipe_kendaraan.id_tipe_kendaraan LIKE '%$search%'
				) 
			";
		}

		$order_column = array('tsl.no_shipping_list','tsl.tgl_sl', 'tsl.no_mesin', 'tsl.no_rangka', 'nama_dealer', 'tr_fkb.nomor_faktur', 'ms_tipe_kendaraan.deskripsi_ahm', 'ms_warna.warna', null);
		$set_order = "ORDER BY tsl.no_sipb DESC";
		if (isset($_POST['order'])) {
			$order = $_POST['order'];
			$order_clm  = $order_column[$order['0']['column']];
			$order_by   = $order['0']['dir'];
			$set_order = " ORDER BY $order_clm $order_by ";
		}

		return $this->db->query("SELECT tsl.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_shipping_list AS tsl
		LEFT JOIN ms_tipe_kendaraan ON tsl.id_modell = ms_tipe_kendaraan.id_tipe_kendaraan
		LEFT JOIN ms_warna ON tsl.id_warna = ms_warna.id_warna
		left join tr_shipping_list_ev_accoem slbo on slbo.no_shipping_list = tsl.no_shipping_list  
		$where $group  $set_order $limit
		");
	}

	public function fetch_api()
	{
		$fetch_data = $this->make_query_api();
		$data       = array();
		$no = 1;
		foreach ($fetch_data->result() as $rs) {

			$sub_array   = array();
			$sub_array[] = $no++;
			$sub_array[] = $rs->no_shipping_list;
			$sub_array[] = $rs->tgl_shipping_list;
			$sub_array[] = 'E20';
			$sub_array[] = 'B';
			$sub_array[] = $rs->part_id;
			$sub_array[] = $rs->part_desc;
			$sub_array[] = $rs->serial_number;
			$sub_array[] = 1;
			$sub_array[] = $rs->created_at;
			$sub_array[] = $rs->penerimaan;
			$sub_array[] = $rs->id_penerimaan_battery;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data_ev(),
			"data"            => $data
		);
		echo json_encode($output);
	}

	function get_filtered_data_ev()
	{
		return $this->make_query_ev('y')->num_rows();
	}


	public function make_query_api($no_limit = null)
	{
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";
		if ($no_limit == 'y') $limit = '';

		$search = $this->input->post('search')['value'];
		$where = "WHERE 1=1 ";

		if ($search != '') {
			$where .= " AND (sloem.no_shipping_list LIKE '%$search%'
					OR sloem.tgl_shipping_list LIKE '%$search%'
					OR sloem.part_desc LIKE '%$search%'
				) 
			";
		}

		$order_column = array('sloem.no_shipping_list','sloem.tgl_shipping_list', 'sloem.part_desc', 'sloem.serial_number');
		$set_order = "ORDER BY sloem.no_shipping_list DESC";
		if (isset($_POST['order'])) {
			$order = $_POST['order'];
			$order_clm  = $order_column[$order['0']['column']];
			$order_by   = $order['0']['dir'];
			$set_order = " ORDER BY $order_clm $order_by ";
		}

		return $this->db->query("SELECT sloem.*,pb.created_at as penerimaan ,pb.id_penerimaan_battery  from tr_shipping_list_ev_accoem sloem left join tr_penerimaan_battery_detail pbd 
		on pbd.serial_number = sloem.serial_number 
		left join tr_penerimaan_battery pb on pb.id_penerimaan_battery = pbd.id_penerimaan_battery 
		-- left join tr_shipping_list sl on sl.no_shipping_list = sloem.no_shipping_list  
		$where $set_order $limit
		");
	}

	

	public function fetch_api_ev()
	{
	  $fetch_data = $this->make_query_api_ev();
	  $no = $_POST['start'];
    
	  $data = array();
	  foreach ($fetch_data as $rs) {

      if($rs->pinpoint=='accrem'){
        $api_send='API 2';
      }else if ($rs->pinpoint=='accoem'){
        $api_send='API 1';
      }else{
        $api_send='API 3';
      }
      
      $post_data = "<button type='button' class='btn btn-sm btn-primary show-details' data-toggle='modal' data-target='#myModal' data-status='$rs->status' data-message='$rs->post_data'>Show</button>";
      $message   = "<button type='button' class='btn btn-sm btn-marron show-result' data-toggle='modal' data-target='#myModal'   data-status='$rs->status' data-message='$rs->message'>Result</button>";

      $no++;
      $sub_array = array();
      $sub_array[] =  $no;
      $sub_array[] = $rs->created_at;
      $sub_array[] = $api_send;
      // $sub_array[] = $rs->api_key;
      $sub_array[] = $rs->endpoint;
      $sub_array[] = $post_data;
      $sub_array[] = $rs->status;
      $sub_array[] = $message;
      $sub_array[] = $rs->ip_address;
      $data[]      = $sub_array;
	  }

	  $count= $this->make_query_api_ev(true);
	  $output = array(
		"draw"            => intval($_POST["draw"]),
		"recordsFiltered" => $count,
		"recordsTotal"    => $count,
		"data"            => $data
	  );
	  echo json_encode($output);
	}
	

	public function make_query_api_ev($recordsFiltered = null)
	{
	  $start        = $this->input->post('start');
	  $length       = $this->input->post('length');
	  $limit        = "LIMIT $start, $length";
	  $where        = "WHERE 1=1 ";

	  if ($recordsFiltered == true) $limit = '';
  
	  $filter = [
		'limit'  => $limit,
		'order'  => isset($_POST['order']) ? $_POST['order'] : '',
		'search' => $this->input->post('search')['value'],
		'order_column' => 'view',
		'deleted' => false,
	  ];
    
	  if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND  (ev.api_key LIKE'%{$filter['search']}%'
                            OR ev.status LIKE'%{$filter['search']}%'
                            OR ev.created_at LIKE'%{$filter['search']}%'
                            OR ev.ip_address LIKE'%{$filter['search']}%'
          )";
        }
      }
	  
	  if (isset($filter['order'])) {
		$order = $filter['order'];
		if ($order != '') {
		  if ($filter['order_column'] == 'view') {
			$order_column = ['ev.api_key', 'ev.created_at', 'ev.ip_address'];
		  }
		  $order_clm  = $order_column[$order['0']['column']];
		  $order_by   = $order['0']['dir'];
		  $order = " ORDER BY $order_clm $order_by ";
		} else {
		  $order = " ORDER BY ev.created_at DESC  ";
		}
	  } else {
		$order = '';
	  }
	  $group = '';

	  if ($recordsFiltered == true) {
		return $this->db->query("SELECT * from activity_ev_log ev $where $group $order $limit")->num_rows();
	  } else {
		return $this->db->query("SELECT * from activity_ev_log ev $where $group $order $limit")->result();
	  }
	}


	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data       = array();
		$id_menu    = $this->m_admin->getMenu($this->page);
		$group      = $this->session->userdata("group");
		$edit       = $this->m_admin->set_tombol($id_menu, $group, 'edit');
		foreach ($fetch_data->result() as $rs) {
			$bulan   = substr($rs->tgl_sl, 2,2);
          	$tahun   = substr($rs->tgl_sl, 4,4);
          	$tgl     = substr($rs->tgl_sl, 0,2);
			$tanggal = $tgl."-".$bulan."-".$tahun;
			$sub_array   = array();
			$sub_array[] = $rs->no_shipping_list;
			$sub_array[] = $tanggal;
			$sub_array[] = $rs->no_sipb;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$sub_array[] = $rs->cabang;
			$sub_array[] = $rs->no_pol_eks;
			$sub_array[] = $rs->kode_md_qq;
			$sub_array[] = $rs->kode_md_po;
			$sub_array[] = $rs->no_mesin_lengkap;
			$sub_array[] = $rs->no_frame;
			$sub_array[] = $rs->nama_eks;
			$sub_array[] = $rs->kota_tujuan;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data(),
			"data"            => $data
		);
		echo json_encode($output);
	}



	public function make_query($no_limit = null)
	{
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";
		if ($no_limit == 'y') $limit = '';

		$search = $this->input->post('search')['value'];
		$where = "WHERE 1=1 ";

		$where .= " AND ms_tipe_kendaraan.id_kategori !='EV'";

		if ($search != '') {
			$where .= " AND (tsl.no_mesin LIKE '%$search%'
					OR tsl.no_rangka LIKE '%$search%'
					OR tsl.no_shipping_list LIKE '%$search%'
					OR tsl.no_sipb LIKE '%$search%'
					OR tsl.cabang LIKE '%$search%'
					OR tsl.no_pol_eks LIKE '%$search%'
					OR tsl.kode_md_qq LIKE '%$search%'
					OR tsl.kode_md_po LIKE '%$search%'
					OR tsl.no_mesin_lengkap LIKE '%$search%'
					OR tsl.no_frame LIKE '%$search%'
					OR tsl.nama_eks LIKE '%$search%'
					OR tsl.kota_tujuan LIKE '%$search%'
					OR ms_warna.warna LIKE '%$search%'
					OR ms_tipe_kendaraan.deskripsi_ahm LIKE '%$search%'
					OR ms_tipe_kendaraan.deskripsi_samsat LIKE '%$search%'
					OR ms_tipe_kendaraan.id_tipe_kendaraan LIKE '%$search%'
				) 
			";
		}

		$order_column = array('tsl.no_shipping_list','tsl.tgl_sl', 'tsl.no_mesin', 'tsl.no_rangka', 'nama_dealer', 'tr_fkb.nomor_faktur', 'ms_tipe_kendaraan.deskripsi_ahm', 'ms_warna.warna', null);
		$set_order = "ORDER BY tsl.no_sipb DESC";
		if (isset($_POST['order'])) {
			$order = $_POST['order'];
			$order_clm  = $order_column[$order['0']['column']];
			$order_by   = $order['0']['dir'];
			$set_order = " ORDER BY $order_clm $order_by ";
		}

		return $this->db->query("SELECT tsl.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_shipping_list AS tsl
		LEFT JOIN ms_tipe_kendaraan ON tsl.id_modell = ms_tipe_kendaraan.id_tipe_kendaraan
		LEFT JOIN ms_warna ON tsl.id_warna = ms_warna.id_warna 
		$where $set_order $limit
		");
	}

	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}

	public function upload()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "upload";
		$this->template($data);
	}
	function import_db()
	{
		$filename = $_FILES["userfile"]["tmp_name"];
		$name 		= $_FILES["userfile"]["name"];
		$type 		= $_FILES["userfile"]["type"];
		$size 		= $_FILES["userfile"]["size"];
		$name_r   = explode('.', $name);
		if ($size > 0 and $name_r[1] == 'SL') {
			$file = fopen($filename, "r");
			$is_header_removed = FALSE;
			$no = array();
			$no1 = 0;
			$no2 = 0;
			$jum = 0;
			$jum1 = 1;
			$isi = "";
			$isi_pb = "";
			$no3 = 0;
			while (($importdata = fgetcsv($file, 10000, ";")) !== FALSE) {
				// if(!$is_header_removed){
				//     $is_header_removed = TRUE;
				//     continue;
				// }
				$no_mesin = str_replace(' ', '', $importdata[0]);
				$row = array(
					'no_mesin'    		=>  $no_mesin,
					'no_rangka'     =>  !empty($importdata[1]) ? $importdata[1] : '',
					'id_modell'         =>  !empty($importdata[2]) ? $importdata[2] : '',
					'id_warna'        =>  !empty($importdata[3]) ? $importdata[3] : '',
					'cabang'       =>  !empty($importdata[4]) ? $importdata[4] : '',
					'no_sipb'       =>  !empty($importdata[5]) ? $importdata[5] : '',
					'no_shipping_list'       =>  !empty($importdata[6]) ? $importdata[6] : '',
					'tgl_sl'       =>  !empty($importdata[7]) ? $importdata[7] : '',
					'no_pol_eks'       =>  !empty($importdata[8]) ? $importdata[8] : '',
					'kode_md_qq'       =>  !empty($importdata[9]) ? $importdata[9] : '',
					'kode_md_po'       =>  !empty($importdata[10]) ? $importdata[10] : '',
					'no_mesin_lengkap'       =>  !empty($importdata[11]) ? $importdata[11] : '',
					'no_frame'       =>  !empty($importdata[12]) ? $importdata[12] : '',
					'nama_eks'       =>  !empty($importdata[13]) ? $importdata[13] : '',
					'kota_tujuan'       =>  !empty($importdata[14]) ? $importdata[14] : ''
				);
				$cek_sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$importdata[5]'");
				if ($cek_sipb->num_rows() > 0) {
					$cek = $this->db->query("SELECT * FROM tr_shipping_list WHERE no_mesin = '$no_mesin' AND no_rangka = '$importdata[1]'");
					if ($cek->num_rows() == 0) {
						$this->db->trans_begin();
						$this->db->insert('tr_shipping_list', $row);
						if (!$this->db->trans_status()) {
							$this->db->trans_rollback();
						} else {
							$this->db->trans_commit();
						}
						$no2++;
					} else {
						if ($isi == "") {
							$isi = $jum1;
						} else {
							$isi = $isi . "," . $jum1;
						}
						$no1++;
					}
				} else {
					if ($isi_pb == "") {
						$isi_pb = $jum1;
					} else {
						$isi_pb = $isi_pb . "," . $jum1;
					}
					$no3++;
				}
				$jum++;
				$jum1++;
			}
			fclose($file);
			$_SESSION['pesan'] 	= $jum . " Data yang anda import. Berhasil = " . $no2 . " data. Gagal = " . $no1 . " data (" . $isi . "). Tidak ditemukan SIPB = " . $no3 . " data (" . $isi_pb . ") ";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/shipping_list'>";
		} elseif ($size > 0 and $name_r[1] == 'KSL') {
			$file = fopen($filename, "r");
			$is_header_removed = FALSE;
			$no = array();
			$no1 = 0;
			$no2 = 0;
			$jum = 0;
			$jum1 = 1;
			$isi = "";
			$isi_pb = "";
			$no3 = 0;
			while (($importdata = fgetcsv($file, 10000, ";")) !== FALSE) {
				// if(!$is_header_removed){
				//     $is_header_removed = TRUE;
				//     continue;
				// }
				$no_mesin = str_replace(' ', '', $importdata[0]);
				$row = array(
					'no_mesin'    		=>  $no_mesin,
					'no_rangka'     =>  !empty($importdata[1]) ? $importdata[1] : '',
					'id_modell'         =>  !empty($importdata[2]) ? $importdata[2] : '',
					'id_warna'        =>  !empty($importdata[3]) ? $importdata[3] : '',
					'cabang'       =>  !empty($importdata[4]) ? $importdata[4] : '',
					'no_sipb'       =>  !empty($importdata[5]) ? $importdata[5] : '',
					'no_shipping_list'       =>  !empty($importdata[6]) ? $importdata[6] : '',
					'tgl_sl'       =>  !empty($importdata[7]) ? $importdata[7] : '',
					'no_pol_eks'       =>  !empty($importdata[8]) ? $importdata[8] : '',
					'kode_md_qq'       =>  !empty($importdata[9]) ? $importdata[9] : '',
					'kode_md_po'       =>  !empty($importdata[10]) ? $importdata[10] : '',
					'no_mesin_lengkap'       =>  !empty($importdata[11]) ? $importdata[11] : '',
					'no_frame'       =>  !empty($importdata[12]) ? $importdata[12] : '',
					'nama_eks'       =>  !empty($importdata[13]) ? $importdata[13] : '',
					'kota_tujuan'       =>  !empty($importdata[14]) ? $importdata[14] : ''
				);
				$cek_sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$importdata[5]'");
				if ($cek_sipb->num_rows() > 0) {
					$cek = $this->db->query("SELECT * FROM tr_shipping_list WHERE no_mesin = '$no_mesin' AND no_rangka = '$importdata[1]'");
					if ($cek->num_rows() == 0) {
						$this->db->trans_begin();

						//$this->db->insert('tr_shipping_list', $row);
						$this->db->where('no_mesin', $no_mesin);
						$this->db->where('no_rangka', $importdata[1]);
						$this->db->update('tr_shipping_list', $row);

						if (!$this->db->trans_status()) {
							$this->db->trans_rollback();
						} else {
							$this->db->trans_commit();
						}
						$no2++;
					} else {
						if ($isi == "") {
							$isi = $jum1;
						} else {
							$isi = $isi . "," . $jum1;
						}
						$no1++;
					}
				} else {
					if ($isi_pb == "") {
						$isi_pb = $jum1;
					} else {
						$isi_pb = $isi_pb . "," . $jum1;
					}
					$no3++;
				}
				$jum++;
				$jum1++;
			}
			fclose($file);
			$_SESSION['pesan'] 	= $jum . " Data yang anda import. Berhasil = " . $no2 . " data. Gagal = " . $no1 . " data (" . $isi . "). Tidak ditemukan SIPB = " . $no3 . " data (" . $isi_pb . ") ";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/shipping_list'>";
		} else {
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/shipping_list'>";
		}
	}
}
