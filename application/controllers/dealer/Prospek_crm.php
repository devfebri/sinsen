<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';
//lets Use the Spout Namespaces
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
class Prospek_crm extends CI_Controller
{
	var $tables        = "tr_prospek";
	var $folder        = "dealer";
	var $page          = "prospek_crm";
	var $pk            = "id_prospek";
	var $title         = "Data Prospek";
	var $group_code    = '';
	var $is_sales      = false;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_kelurahan');
		$this->load->model('m_h1_dealer_prospek', 'm_prospek');
		$this->load->model('Api_crm_post_model', 'api_crm');
		$this->load->model('CRM_wilayah', 'crm_wilayah');

		//===== Load Library =====
		$this->load->library('upload');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
		$this->db_crm       = $this->load->database('db_crm', true);
		$this->group_code   = $this->db->get_where('ms_user_group',['id_user_group'=>$_SESSION['group']])->row()->code;
		if ($this->group_code=='sales') {
			$this->is_sales = true;
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
			if ($data['set'] == 'follow_up') {
				$this->page = 'prospek_follow_up';
			} elseif ($data['set'] == 'assign_salespeople') {
				$this->page = 'prospek_assign_salespeople';
			}
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}


	function getFollowUpID()
	{
	  $dmy = gmdate("dmY", time() + 60 * 60 * 7);
	  $ymd = tanggal();
	  $get_data  = $this->db_crm->query("SELECT RIGHT(followUpID,3) followUpID 
					FROM leads_follow_up WHERE LEFT(created_at,10)='$ymd'
					ORDER BY followUpID,created_at DESC LIMIT 0,1");
	  if ($get_data->num_rows() > 0) {
		$row = $get_data->row();
		$new_kode = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", $row->followUpID + 1);
		$i = 0;
		while ($i < 1) {
		  $cek = $this->db_crm->get_where('leads_follow_up', ['followUpID' => $new_kode])->num_rows();
		  if ($cek > 0) {
			$new_kode   = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
			$i = 0;
		  } else {
			$i++;
		  }
		}
	  } else {
		$new_kode   = 'FOLUP/' . $dmy . '/001';
		$i = 0;
		while ($i < 1) {
		  $cek = $this->db_crm->get_where('leads_follow_up', ['followUpID' => $new_kode])->num_rows();
		  if ($cek > 0) {
			$new_kode   = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
			$i = 0;
		  } else {
			$i++;
		  }
		}
	  }
	  return strtoupper($new_kode);
	}



	public function get_sales_people($filter)
	{
		$id_dealer = $filter['id_dealer'];
		$flp_dealer = $filter['id_karyawan'];
		$query= $this->db->query("SELECT * from ms_karyawan_dealer WHERE id_karyawan_dealer  ='$flp_dealer' AND id_dealer = '$id_dealer' ")->row();
		return $query;
	}



	public function upload_to_api2()
	{

	  $this->load->model('CRM_leads_model_create', 'lda_m');
	  $this->load->library('upload');

	  $d = date('d');
	  $ym = date('Y/m');
	  $y_m = date('y-m');

	  $dealer_initial=$this->m_admin->cari_dealer();
	  $dealer_set=$this->m_admin->cari_kode_dealer($dealer_initial);
	//   $path = "./uploads/to_api2/" . $ym."/".  $d ."/". $dealer_set;

	  $path = "./uploads/to_api2/" . $ym;

	  if (!is_dir($path)) {
		mkdir($path, 0777, true);
	  }

	  $config['upload_path']   = $path;
	  $config['allowed_types'] = '*';
	  $config['max_size']      = '10024';
	  $config['remove_spaces'] = TRUE;
	  $config['overwrite']     = TRUE;
	  $this->upload->initialize($config);
	  if ($this->upload->do_upload('file_upload')) {
		$new_path = substr($path, 2, 40);
		$filename = $this->upload->file_name;
		$path_file = $new_path . '/' . $filename;
	  } else {
		$err = clear_removed_html($this->security->xss_clean($this->upload->display_errors()));
		$response = ['icon' => 'error', 'title' => 'Peringatan', 'pesan' => $err];
		send_json($response);
	  }
	  $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
	  $reader->open($path_file); //open file xlsx
	  //siapkan variabel array kosong untuk menampung variabel array data
  
	  foreach ($reader->getSheetIterator() as $sheet) {
		$numRow = 0;
		if ($sheet->getIndex() === 0) {
		  //looping pembacaan row dalam sheet
		  $baris = 1;
		  foreach ($sheet->getRowIterator() as $row) {
			if ($numRow > 0) {
			  if ($row[0] == '') break;
			  // send_json(($row));
			  $post[] = [
				'nama' => $row[0],
				'noHP' => $row[1],
				'email' => $row[2],
				'customerType' => $row[3],
				'eventCodeInvitation' => $row[4],
				'customerActionDate' => $row[5],
				'kabupaten' => $row[6],
				'provinsi' => $row[7],
				'cmsSource' => $row[8],
				'segmentMotor' => $row[9],
				'seriesMotor' => $row[10],
				'deskripsiEvent' => $row[11],
				'kodeTypeUnit' => $row[12],
				'kodeWarnaUnit' => $row[13],
				'minatRidingTest' => $row[14],
				'jadwalRidingTest' => $row[15],
				'sourceData' => $row[16],
				'platformData' => $row[17],
				'noTelp' => isset($row[18]) ? $row[18] : '',
				'assignedDealer' => isset($row[19]) ? $row[19] : '',
				'sourceRefID' => isset($row[20]) ? $row[20] : '',
				'kelurahan' => isset($row[21]) ? $row[21] : '',
				'kecamatan' => isset($row[22]) ? $row[22] : '',
				'noFramePembelianSebelumnya' => isset($row[23]) ? $row[23] : '',
				'kodeDealerSebelumnya' => isset($row[24]) ? $row[24] : '',
				'keterangan' => isset($row[25]) ? $row[25] : '',
				'promoUnit' => isset($row[26]) ? $row[26] : '',
				'facebook' => isset($row[27]) ? $row[27] : '',
				'instagram' => isset($row[28]) ? $row[28] : '',
				'twitter' => isset($row[29]) ? $row[29] : '',
				'assignSalesPeople' => isset($row[30]) ? $row[30] : '',
				'sumber_data'=>'ldd'
			  ];

			  $baris++;
			}
			$numRow++;
		  }
		}
	  }

	$reader->close();
	$insert_st = $this->lda_m->insertStagingTables($post);
	$check = count($insert_st);

	if ($check !== 0 ){
		$response = [
			'status' => 1,
				'pesan' => "Terjadi kesalahan validasi",
				'data' =>$insert_st,
		];
    }else{
		$response = [
			'status' => 0,
		  ];
    }

	  send_json($response);
	}


	public function followupcount()
	{
		$login_id = $this->session->userdata('id_user');
		$query_user = $this->db->query("SELECT username from ms_user WHERE id_user ='$login_id'")->row();
		$set_flp =$query_user->username;
		$id_dealer = $this->m_admin->cari_dealer();
		$query= $this->db->query("SELECT 
		sum(case when fol.id_prospek is null then 1 else 0 end) as jumlah
		from tr_prospek pro 
		left join tr_prospek_fol_up fol on pro.id_prospek = fol.id_prospek 
		WHERE  fol.id_prospek is null and pro.id_dealer ='$id_dealer' and pro.input_from ='ldd'
		and id_flp_md ='$set_flp'")->row();
		return $query;
	}


	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= 'view';
		$filter_needs_first_follow_up = [
			'select' => 'count',
			'ada_sales' => 0,
			'tot_folup' => 0,
			'id_dealer' => $this->m_admin->cari_dealer(),
			'id_customer_in_spk' => false
		];
		if ($this->is_sales) {
			 $filter_needs_first_follow_up['id_karyawan_dealer']=$_SESSION['id_karyawan_dealer'];
		}
		$filter_lewat_sla = [
			'select' => 'count',
			'ada_leads' => 1,
			'ontimeSLA2_desc'=>'Overdue',
			'kodeHasilStatusFollowUpNot'=>4,
			'id_dealer' => $this->m_admin->cari_dealer(),
			'id_customer_in_spk' => false
		];
		if ($this->is_sales) {
			$filter_lewat_sla['id_karyawan_dealer']=$_SESSION['id_karyawan_dealer'];
		}
		$filter_multi_interaction = [
			'select' => 'count',
			'tot_interaksi_lebih_dari' => 1
		];
		if ($this->is_sales) {
			$filter_multi_interaction['id_karyawan_dealer']=$_SESSION['id_karyawan_dealer'];
		}

		// BATCH 1
		// $myArrayDealer = array(103,4,77,47,46,1,96,86,70,80);
		// BATCH 2
		// $myArrayDealer = array(70,101,1,98,80,22,51,18,4,2,82,105,85,39,104,13,96,86,25,46,47,4,103,78,77);
		// ALL OPEN 3
		$myArrayDealer = array(1,2,3,4,8,13,18,22,25,37,39,40,41,43,44,45,46,47,51,65,66,70,71,74,77,78,80,81,82,83,84,85,86,90,93,94,95,96,97,98,100,101,102,103,104,105,106,107);

		if (in_array($this->m_admin->cari_dealer(), $myArrayDealer)) { 
			$count_followup =$this->followupcount();
			$needs_first_follow_up =(int) $count_followup->jumlah + ($this->m_prospek->getProspek($filter_needs_first_follow_up)->row()->count);
			$lewat_sla_dealer = $this->m_prospek->getProspek($filter_lewat_sla)->row()->count;
			$leads_multi_interaction = $this->m_prospek->getProspek($filter_multi_interaction)->row()->count;
		}else{
			$needs_first_follow_up = 0;
			$lewat_sla_dealer = 0;
			$leads_multi_interaction = 0;
		}

		$data['button_lead'] = $this->group_code   = $this->db->get_where('ms_user_group',['id_user_group'=>$_SESSION['group']])->row()->code;
		
		$data['mo'] = [
			'needs_first_follow_up' =>$needs_first_follow_up,
			'lewat_sla_dealer' => $lewat_sla_dealer,
			'leads_multi_interaction' => $leads_multi_interaction,
		];

		$this->template($data);
	}
	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= 'history';
		$filter = ['id_customer_in_spk_or_no_deal' => true];
		$data['dt_prospek'] = $this->m_prospek->getProspek($filter);
		$this->template($data);
	}
	public function gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= 'view_gc';
		$id_dealer      = $this->m_admin->cari_dealer();
		$data['dt_prospek'] = $this->db->query("SELECT tr_prospek_gc.*,ms_karyawan_dealer.nama_lengkap FROM tr_prospek_gc LEFT JOIN ms_dealer ON tr_prospek_gc.id_dealer=ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
			LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan
			LEFT JOIN ms_kecamatan ON tr_prospek_gc.id_kecamatan=ms_kecamatan.id_kecamatan
			LEFT JOIN ms_kabupaten ON tr_prospek_gc.id_kabupaten=ms_kabupaten.id_kabupaten
			LEFT JOIN ms_provinsi ON tr_prospek_gc.id_provinsi=ms_provinsi.id_provinsi
			WHERE tr_prospek_gc.active = '1' AND tr_prospek_gc.id_dealer = '$id_dealer' ORDER BY tr_prospek_gc.atur_tgl DESC");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}
	public function getDetail()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id 		= $this->input->post('id');
		if ($id == null or $id == '') {
			$data['detail'] = $this->db->query("SELECT tr_prospek_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_prospek_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_prospek_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_prospek_gc_kendaraan.id_warna = ms_warna.id_warna
			 	WHERE tr_prospek_gc_kendaraan.status='new' AND tr_prospek_gc_kendaraan.created_by='$login_id'");
		} else {
			$data['detail'] = $this->db->query("SELECT tr_prospek_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_prospek_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_prospek_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_prospek_gc_kendaraan.id_warna = ms_warna.id_warna
				WHERE id_prospek_gc='$id'");
		}
		$data['id'] = $id == null ? 0 : $id;
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_prospek_gc', $data);
	}
	public function addDetail()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id_prospek_gc = $this->input->post('id_prospek_gc');
		if ($id_prospek_gc <> '') {
			$data['id_prospek_gc']			= $this->input->post('id_prospek_gc');
			$data['status']					= 'input';
		} else {
			$data['status']					= 'new';
		}
		$data['id_tipe_kendaraan']	= $id_tipe_kendaraan		= $this->input->post('id_tipe_kendaraan');
		$data['id_warna'] = $id_warna			= $this->input->post('id_warna');
		$data['qty']				= $this->input->post('qty');
		$data['tahun']				= $this->input->post('tahun');
		$data['created_by']				= $login_id;
		$data['created_at']				= $waktu;
		$cek = $this->db->query("SELECT * From tr_prospek_gc_kendaraan WHERE created_by='$login_id' AND status='new' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
		if ($cek->num_rows() > 0) {
			echo "Tipe dan Warna ini sudah dipilih sebelumnya";
		} else {
			$this->m_admin->insert("tr_prospek_gc_kendaraan", $data);
			echo "nihil";
		}
	}
	public function saveEdit()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$data['id_tipe_kendaraan']	= $id_tipe_kendaraan		= $this->input->post('id_tipe_kendaraan');
		$data['id_warna'] = $id_warna			= $this->input->post('id_warna');
		$data['qty']				= $this->input->post('qty');
		$data['tahun']				= $this->input->post('tahun');
		$id				= $this->input->post('id_gc');
		$this->m_admin->update("tr_prospek_gc_kendaraan", $data, "id_prospek_gc_kendaraan", $id);
		echo "nihil";
		// $cek = $this->db->query("SELECT * FROM tr_prospek_gc_kendaraan WHERE created_by='$login_id' AND status='new' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
		// if($cek->num_rows() == 1){
		// 	echo "Tipe dan Warna ini sudah dipilih sebelumnya";
		// }else{
		// }
	}
	public function delDetail()
	{
		$id			= $this->input->post('id');
		$this->m_admin->delete("tr_prospek_gc_kendaraan", 'id_prospek_gc_kendaraan', $id);
		echo "nihil";
	}
	public function edit_popup()
	{
		$id_gc = $this->input->post("id_gc");
		$data['isi']    = $this->page;
		$data['dt_gc']	= $this->db->query("SELECT * FROM tr_prospek_gc_kendaraan LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_prospek_gc_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_prospek_gc_kendaraan.id_warna = ms_warna.id_warna 
			WHERE tr_prospek_gc_kendaraan.id_prospek_gc_kendaraan = '$id_gc'")->row();
		$data['title']	= $this->title;
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view("dealer/t_prospek_gc_edit.php", $data);
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$data['mode']		= "insert";
		// $data['dt_prospek'] = $this->db->query("SELECT tr_prospek.*,ms_karyawan_dealer.id_karyawan_dealer FROM tr_prospek LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.nama_dealer FROM ms_karyawan_dealer 
			LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
			WHERE ms_karyawan_dealer.id_dealer = '$id_dealer'
			 AND id_flp_md <> '' AND ms_karyawan_dealer.active='1' 
			 AND ms_karyawan_dealer.id_jabatan IN('JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099','JBT-113')
		ORDER BY nama_lengkap ASC");
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "id_pendidikan", "ASC");
		// $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') ORDER BY id_pekerjaan ASC ");

		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		/* $data['dt_no_mesin'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
						INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND tr_scan_barcode.status = '4'");
		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC"); */
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$fsumber=['id_platform_data'=>'D'];
		$data['set_sumber_prospek'] = $this->m_prospek->getSumberProspek($fsumber)->result();
		$this->template($data);
	}
	public function add_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= "insert_gc";
		$data['mode']		= "insert";
		// $data['dt_prospek'] = $this->db->query("SELECT tr_prospek.*,ms_karyawan_dealer.id_karyawan_dealer FROM tr_prospek LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND id_flp_md <> ''  AND ms_karyawan_dealer.active='1' AND (ms_karyawan_dealer.id_jabatan IN('JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099') or id_flp_md ='127250') ORDER BY nama_lengkap ASC");
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "id_pendidikan", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		/* $data['dt_no_mesin'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
						INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND tr_scan_barcode.status = '4'");
		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC"); */
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}
	public function cari_id()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$tahun = date("Y");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT ms_dealer.id_dealer , ms_dealer.kode_dealer_md FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 	= $isi->kode_dealer_md;
		$id_dealer 		= $isi->id_dealer;
		$pr_num 			= $this->db->query("SELECT id_prospek FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4)='$tahun' ORDER BY id_prospek DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek) - 5;
			$id 	= substr($row->id_prospek, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		$rt = rand(1111, 9999);
		$tgl						= $this->input->post('tgl');
		$th 						= date("y");
		$waktu 					= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$id_dealer 			= $this->m_admin->cari_dealer();
		$pr_num 				= $this->db->query("SELECT id_list_appointment FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4)='$tahun' ORDER BY id_list_appointment DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_list_appointment) - 5;
			$id 	= substr($row->id_list_appointment, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "00" . $id;
			} elseif ($id > 999 && $id <= 9999) {
				$kode1 = $th . "0" . $id;
			} else{
				$kode1 = $th . "" . $id;
			}
			$kode2 = "PR" . $kode1;
		} else {
			$kode2 = "PR" . $th . "00001";
		}

		echo $kode . "|" . $rt . "|" . $kode2;
	}
	public function ajax_list()
	{
		$list = $this->m_kelurahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan", "id_kecamatan", $isi->id_kecamatan);
			$kabupaten = '';
			if ($cek->num_rows() > 0) {
				$t = $cek->row();
				$kecamatan = $t->kecamatan;
				$kab = $this->db->get_where('ms_kabupaten', ['id_kabupaten' => $t->id_kabupaten]);
				$kabupaten = $kab->num_rows() > 0 ? $kab->row()->kabupaten : '';
			} else {
				$kecamatan = "";
			}
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->id_kelurahan;
			$row[] = $isi->kelurahan;
			$row[] = $kecamatan;
			$row[] = $kabupaten;
			$row[] = $isi->kode_pos;
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_kelurahan')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_kelurahan->count_all(),
			"recordsFiltered" => $this->m_kelurahan->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function ajax_list2()
	{
		$list = $this->m_kelurahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan", "id_kecamatan", $isi->id_kecamatan);
			if ($cek->num_rows() > 0) {
				$t = $cek->row();
				$kecamatan = $t->kecamatan;
			} else {
				$kecamatan = "";
			}
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->kelurahan;
			$row[] = $kecamatan;
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_kelurahan')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_kelurahan->count_all(),
			"recordsFiltered" => $this->m_kelurahan->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cari_id_real()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$tahun = date("Y");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$id_dealer 		= $this->m_admin->cari_dealer();
		$isi 				= $this->db->query("SELECT ms_dealer.kode_dealer_md FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT id_prospek FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4) = '$tahun' ORDER BY id_prospek DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek) - 5;
			$id 	= substr($row->id_prospek, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		//$rt = rand(1111,9999);
		$rt = $this->m_admin->get_customer();
		$id_dealer      = $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		} else {
			$get_dealer = '';
			$panjang = '';
		}
		$tgl						= $this->input->post('tgl');
		$th 						= date("y");
		$waktu 					= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$pr_num 				= $this->db->query("SELECT id_list_appointment FROM tr_prospek WHERE RIGHT(id_list_appointment,$panjang) = '$get_dealer' and left(created_at,4) = '$tahun' ORDER BY id_list_appointment DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_list_appointment) - ($panjang + 6);
			$id 	= substr($row->id_list_appointment, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "00" . $id;
			} elseif ($id > 999 && $id <= 9999) {
				$kode1 = $th . "0" . $id;
			} else {
				$kode1 = $th . "" . $id;
			}
			$kode2 = "PR" . $kode1 . "-" . $get_dealer;
		} else {
			$kode2 = "PR" . $th . "00001-" . $get_dealer;
		}

		return array('kode' => $kode, 'rt' => $rt, 'kode2' => $kode2);
		//echo $kode."|".$rt."|".$kode2;
	}
	public function cari_id_gc()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$tahun = date("Y");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$id_dealer 		= $this->m_admin->cari_dealer();
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md . "GC";
		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek_gc WHERE id_dealer = '$id_dealer' and left(created_at,4) = '$tahun' ORDER BY id_prospek_gc DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek_gc) - 5;
			$id 	= substr($row->id_prospek_gc, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		return $kode;
	}
	public function take_sales()
	{
		$id_karyawan_dealer	= anti_injection($this->input->post('id_karyawan_dealer'));
		$dt_eks				= $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer = '$id_karyawan_dealer'");
		if ($dt_eks->num_rows() > 0) {
			$da = $dt_eks->row();
			$kode = $da->id_flp_md;
			$nama = $da->nama_lengkap;
		} else {
			$kode = "";
			$nama = "";
		}
		echo $kode . "|" . $nama;
	}
	public function take_kec()
	{
		$id_kelurahan	= $this->input->post('id_kelurahan');
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kelurahan 		= $dt_kel->kelurahan;
		$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
		$kecamatan 		= $dt_kec->kecamatan;
		$id_kabupaten = $dt_kec->id_kabupaten;
		$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
		$kabupaten  	= $dt_kab->kabupaten;
		$id_provinsi  = $dt_kab->id_provinsi;
		$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
		$provinsi  		= $dt_pro->provinsi;

		echo $id_kecamatan . "|" . $kecamatan . "|" . $id_kabupaten . "|" . $kabupaten . "|" . $id_provinsi . "|" . $provinsi . "|" . $dt_kel->kode_pos . "|" . $kelurahan;
	}
	public function save()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {
			$r = "";
			$isi_ktp = $this->input->post('no_ktp');
			$ktp = strlen($isi_ktp);
			if ($ktp < 16) {
				$jum = 16 - $ktp;
				for ($i = 1; $i <= $jum; $i++) {
					$r = $r . "0";
				}
				$ktp_f = $r . $isi_ktp;
			} else {
				$ktp_f = $isi_ktp;
			}
			$isi													= $this->cari_id_real();
			$data['id_dealer'] 						= $this->m_admin->cari_dealer();
			$data['id_karyawan_dealer'] 	= $this->input->post('id_karyawan_dealer');
			$data['id_flp_md'] 						= $this->input->post('id_flp_md');
			$data['id_customer'] 					= $isi['rt'];
			$data['nama_konsumen'] 				= $this->input->post('nama_konsumen');
			$data['no_ktp'] 							= $ktp_f;
			$data['no_npwp'] 							= $this->input->post('no_npwp');
			$data['no_kk'] 								= $this->input->post('no_kk');
			$data['jenis_wn'] 						= $this->input->post('jenis_wn');
			$data['jenis_kelamin'] 				= $this->input->post('jenis_kelamin');
			$data['tempat_lahir']					= $this->input->post('tempat_lahir');
			$data['tgl_lahir'] 						= $this->input->post('tgl_lahir');
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 				= $region[1];
			$data['id_kabupaten'] 				= $region[2];
			$data['id_provinsi'] 					= $region[3];
			$data['kodepos'] 							= $this->input->post('kodepos');
			$data['alamat'] 							= $this->input->post('alamat');
			$data['agama'] 								= $this->input->post('agama');
			//$data['penanggung_jawab'] 		= $this->input->post('penanggung_jawab');	
			$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
			$data['no_telp'] 							= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_telp'));
			$data['merk_sebelumnya'] 			= $this->input->post('merk_sebelumnya');
			$data['jenis_sebelumnya'] 		= $this->input->post('jenis_sebelumnya');
			$data['tipe_sebelumnya'] 		= $this->input->post('tipe_sebelumnya');
			$data['pemakai_motor'] 				= $this->input->post('pemakai_motor');
			$data['email'] 								= $this->input->post('email');
			$data['status_nohp'] 					= $this->input->post('status_nohp');
			$status_prospek='';
			if ($this->input->post('status_prospek_header')!='') {
				$status_prospek=$this->input->post('status_prospek_header');
			}
			$data['status_prospek'] 			= $status_prospek;
			$data['id_tipe_kendaraan'] 		= $this->input->post('id_tipe_kendaraan');
			$data['id_warna'] 						= $this->input->post('id_warna');
			$data['atur_tgl'] 						= $this->input->post('atur_tgl');
			$data['facebook'] 						= $this->input->post('facebook');
			$data['youtube'] 							= $this->input->post('youtube');
			$data['twitter'] 							= $this->input->post('twitter');
			$data['instagram'] 						= $this->input->post('instagram');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
			$data['id_kelurahan_kantor'] 	= $this->input->post('id_kelurahan_kantor');
			$cek_req_instansi = $this->db->get_where('ms_sub_pekerjaan', array('id_sub_pekerjaan' => $this->input->post('sub_pekerjaan')));
			if ($cek_req_instansi->num_rows() > 0) {
				$req_instansi = $cek_req_instansi->row()->required_instansi;
				if ($req_instansi == '1') {
					if ($this->input->post('id_kelurahan_kantor') == '') {
						$_SESSION['pesan'] 	= "kelurahan Kantor tidak boleh kosong";
						$_SESSION['tipe'] 	= "danger";
						echo "<script>history.go(-1)</script>";
						exit();
					}
				}
			}

			$data['prioritas_prospek'] 		= (int)$this->input->post('prioritas_prospek');
			$data['program_umum'] 				= $this->input->post('program_utama') != '' ? $this->input->post('program_utama') : NULL;
			$data['program_gabungan'] 		= $this->input->post('program_gabungan') != '' ? $this->input->post('program_gabungan') : NULL;
			$data['id_list_appointment']  = $isi['kode2'];
			$data['created_at']						= $waktu;
			$data['created_by']						= $login_id;
			// $data['jenis_pembelian'] 			= $this->input->post('jenis_pembelian');	
			$data['pekerjaan'] 						= $this->input->post('pekerjaan');
			$data['sub_pekerjaan'] 						= $this->input->post('sub_pekerjaan');
			$data['pekerjaan_lain'] 						= $this->input->post('lain');
			$data['nama_tempat_usaha'] 						= $this->input->post('nama_usaha');
			// $data['pengeluaran_bulan'] 		= $this->input->post('pengeluaran_bulan');	
			// $data['pendidikan'] 					= $this->input->post('pendidikan');	
			// $data['digunakan'] 						= $this->input->post('digunakan');	
			// $data['sedia_hub'] 						= $this->input->post('sedia_hub');	
			// $data['status_rumah'] 				= $this->input->post('status_rumah');	
			// $data['no_mesin'] 						= $this->input->post('no_mesin');	
			// $data['no_rangka'] 						= $this->input->post('no_rangka');	
			// $data['tahun_rakit'] 					= $this->input->post('tahun_rakit');	

			if (strtolower($status_prospek) == 'hot' || strtolower($status_prospek) == 'low') {
				$data['status_aktifitas'] = 'In Progress';
			} elseif (strtolower($status_prospek) == 'deal' || strtolower($status_prospek) == 'not deal') {
				$data['status_aktifitas'] = 'Completed';
			} else {
				$data['status_aktifitas'] = 'Not Started';
			}
			$sumber_prospek = $this->input->post('sumber_prospek');
			$id_prospek = $data['id_prospek'] 					= $this->m_prospek->getIDProspek($sumber_prospek);
			$data['tgl_prospek']           = $this->input->post('tgl_prospek');
			$data['longitude']             = $this->input->post('longitude');
			$data['latitude']              = $this->input->post('latitude');
			$data['alamat_kantor']         = $this->input->post('alamat_kantor');
			$data['no_telp_kantor']        = $this->input->post('no_telp_kantor');
			$data['id_kelurahan_kantor']   = $this->input->post('id_kelurahan_kantor');
			$data['jenis_customer']        = $this->input->post('jenis_customer');
			$data['sumber_prospek']        = $sumber_prospek;
			$data['test_ride_preference']  = $this->input->post('test_ride_preference');
			$data['rencana_pembayaran']    = $this->input->post('rencana_pembayaran');
			$data['catatan']               = $this->input->post('catatan');
			$data['keterangan_not_deal']   = $this->input->post('keterangan_not_deal');
			$data['id_event']              = $this->input->post('id_event');
			$data['input_from'] = 'crm';

			$details = json_decode($this->input->post('details'), true);
			foreach ($details as $key => $dt) {
				$fol = [
					'id_prospek'                       => $id_prospek,
					'tgl_fol_up'                       => $dt['tgl_fol_up'],
					'waktu_fol_up'                     => $dt['waktu_fol_up'],
					'metode_fol_up'                    => $dt['metode_fol_up'],
					'keterangan'                       => $dt['keterangan'],
					'tgl_next_fol_up'                  => $dt['tgl_next_fol_up'] == '' ? null : $dt['tgl_next_fol_up'],
					'keterangan_next_fu'               => $dt['keterangan_next_fu'],
					'id_status_fu'                     => $dt['id_status_fu'],
					'id_kategori_status_komunikasi'    => $dt['id_kategori_status_komunikasi'],
					'kodeHasilStatusFollowUp'          => $dt['kodeHasilStatusFollowUp'],
					'kodeAlasanNotProspectNotDeal'     => $dt['kodeAlasanNotProspectNotDeal'] == '' ? null : $dt['kodeAlasanNotProspectNotDeal'],
					'alasanNotProspectNotDealLainnya'  => $dt['alasanNotProspectNotDealLainnya'] == '' ? null : $dt['alasanNotProspectNotDealLainnya'],
					'status_prospek'                   => $dt['status_prospek'],
					'id_karyawan_dealer'               => $this->input->post('id_karyawan_dealer')
				];
				$dt_fol_up[] = $fol;
			}
			// send_json($data);
			$this->m_admin->insert($tabel, $data);
			if (isset($dt_fol_up)) {
				$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up);
			}
			$pesan = $_SESSION['pesan'] 	= "Data has been saved successfully";
			$tipe = $_SESSION['tipe'] 	= "success";
			$status = 'sukses';
		} else {
			$pesan = $_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$status = 'error';
		}
		$rsp = [
			'status' => $status,
			'link' => base_url('dealer/prospek_crm'),
			'pesan' => $pesan
		];
		send_json($rsp);
	}

	public function save_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_prospek_gc";
		$pk					= "id_prospek_gc";
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {

			$data['id_dealer'] 						= $this->m_admin->cari_dealer();
			$data['id_karyawan_dealer'] 	= $this->input->post('id_karyawan_dealer');
			$data['id_flp_md'] 						= $this->input->post('id_flp_md');
			$data['jenis'] 								= $this->input->post('jenis');
			$data['id_kelompok_harga']		= $this->input->post('kelompok_harga');
			$data['nama_npwp']						= $this->input->post('nama_npwp');
			$data['no_npwp'] 							= $this->input->post('no_npwp');
			$data['no_telp'] 							= $this->input->post('no_telp');
			$data['tgl_berdiri']					= $this->input->post('tgl_berdiri');
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 				= $region[1];
			$data['id_kabupaten'] 				= $region[2];
			$data['id_provinsi'] 					= $region[3];
			$data['kodepos'] 							= $this->input->post('kodepos');
			$data['alamat'] 							= $this->input->post('alamat');
			$status_prospek = $data['status_prospek'] 			= $this->input->post('status_prospek');
			$data['nama_penanggung_jawab'] = $this->input->post('nama_penanggung_jawab');
			$data['email'] 								= $this->input->post('email');
			$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
			$data['status_nohp'] 					= $this->input->post('status_nohp');
			$data['atur_tgl'] 						= $this->input->post('atur_tgl');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
			$data['created_at']						= $waktu;
			$data['created_by']						= $login_id;
			$data['status']								= "input";
			$data['id_prospek_gc'] = $id_prospek_gc = $this->cari_id_gc();

			$data['tgl_prospek'] 			= $this->input->post('tgl_prospek');
			$data['id_pekerjaan'] 			= $this->input->post('id_pekerjaan');
			$data['longitude'] 			= $this->input->post('longitude');
			$data['latitude'] 			= $this->input->post('latitude');
			$data['test_ride_preference'] 			= $this->input->post('test_ride_preference');
			if (strtolower($status_prospek) == 'hot' || strtolower($status_prospek) == 'low') {
				$data['status_aktifitas'] = 'In Progress';
			} elseif (strtolower($status_prospek) == 'deal' || strtolower($status_prospek) == 'not deal') {
				$data['status_aktifitas'] = 'Completed';
			} else {
				$data['status_aktifitas'] = 'Not Started';
			}
			$data['id_event'] 			 = $this->input->post('id_event');
			$data['program_umum'] = $this->input->post('program_umum');
			$data['id_kelurahan_kantor'] = $this->input->post('id_kelurahan_kantor');

			$data['alamat_kantor'] = $this->input->post('alamat_kantor');
			$data['no_telp_kantor'] = $this->input->post('no_telp_kantor');
			$data['prioritas_prospek'] = (int)$this->input->post('prioritas_prospek');
			$data['sumber_prospek'] = $this->input->post('sumber_prospek');
			$tgl_fol_up    = $this->input->post('tgl_fol_up');
			$waktu_fol_up  = $this->input->post('waktu_fol_up');
			$metode_fol_up = $this->input->post('metode_fol_up');
			$keterangan    = $this->input->post('keterangan');
			if (count($tgl_fol_up)) {
				foreach ($tgl_fol_up as $key => $val) {
					$dt_fol_up[] = [
						'id_prospek' => $id_prospek_gc,
						'tgl_fol_up' => $val,
						'waktu_fol_up'  => $waktu_fol_up[$key],
						'metode_fol_up' => $metode_fol_up[$key],
						'keterangan'    => $keterangan[$key],
					];
				}
			}


			$lastHeader = $this->db->query("SELECT * From tr_prospek_gc_kendaraan WHERE created_by='$login_id' AND status='new'");
			if ($lastHeader->num_rows() > 0) {
				$this->m_admin->insert("tr_prospek_gc", $data);
				if (isset($dt_fol_up)) {
					$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up);
				}
				$this->db->query("UPDATE tr_prospek_gc_kendaraan set status='input', id_prospek_gc = '$id_prospek_gc', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek_crm/add_gc'>";
			} else {
				$_SESSION['pesan'] 	= "Detail Tipe Kendaraan tidak boleh kosong";
				$_SESSION['id_kelurahan'] 	= $this->input->post('id_kelurahan');
				$_SESSION['atur_tgl'] 	= $this->input->post('atur_tgl');
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function update_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id_prospek_gc");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0 or $id == $id_) {

			$data['id_dealer'] 						= $this->m_admin->cari_dealer();
			$data['id_karyawan_dealer'] 	= $this->input->post('id_karyawan_dealer');
			$data['id_flp_md'] 						= $this->input->post('id_flp_md');
			$data['jenis'] 								= $this->input->post('jenis');
			$data['id_kelompok_harga']		= $this->input->post('kelompok_harga');
			$data['nama_npwp']						= $this->input->post('nama_npwp');
			$data['no_npwp'] 							= $this->input->post('no_npwp');
			$data['no_telp'] 							= $this->input->post('no_telp');
			$data['tgl_berdiri']					= $this->input->post('tgl_berdiri');
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 				= $region[1];
			$data['id_kabupaten'] 				= $region[2];
			$data['id_provinsi'] 					= $region[3];
			$data['kodepos'] 							= $this->input->post('kodepos');
			$data['alamat'] 							= $this->input->post('alamat');
			$status_prospek = $data['status_prospek'] 			= $this->input->post('status_prospek');
			$data['nama_penanggung_jawab'] = $this->input->post('nama_penanggung_jawab');
			$data['email'] 								= $this->input->post('email');
			$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
			$data['status_nohp'] 					= $this->input->post('status_nohp');
			$data['atur_tgl'] 						= $this->input->post('atur_tgl');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
			$data['updated_at']						= $waktu;
			$data['updated_by']						= $login_id;
			$data['tgl_prospek'] 			= $this->input->post('tgl_prospek');
			$data['id_pekerjaan'] 			= $this->input->post('id_pekerjaan');
			$data['longitude'] 			= $this->input->post('longitude');
			$data['latitude'] 			= $this->input->post('latitude');
			$data['test_ride_preference'] 			= $this->input->post('test_ride_preference');
			if (strtolower($status_prospek) == 'hot' || strtolower($status_prospek) == 'low') {
				$data['status_aktifitas'] = 'In Progress';
			} elseif (strtolower($status_prospek) == 'deal' || strtolower($status_prospek) == 'not deal') {
				$data['status_aktifitas'] = 'Completed';
			} else {
				$data['status_aktifitas'] = 'Not Started';
			}
			$data['id_event'] 			 = $this->input->post('id_event');
			$data['program_umum'] = $this->input->post('program_umum');
			$data['id_kelurahan_kantor'] = $this->input->post('id_kelurahan_kantor');

			$data['alamat_kantor'] = $this->input->post('alamat_kantor');
			$data['no_telp_kantor'] = $this->input->post('no_telp_kantor');
			$data['prioritas_prospek'] = (int)$this->input->post('prioritas_prospek');
			$data['sumber_prospek'] = $this->input->post('sumber_prospek');
			$tgl_fol_up    = $this->input->post('tgl_fol_up');
			$waktu_fol_up  = $this->input->post('waktu_fol_up');
			$metode_fol_up = $this->input->post('metode_fol_up');
			$keterangan    = $this->input->post('keterangan');
			if (count($tgl_fol_up)) {
				foreach ($tgl_fol_up as $key => $val) {
					$dt_fol_up[] = [
						'id_prospek' => $id,
						'tgl_fol_up' => $val,
						'waktu_fol_up'  => $waktu_fol_up[$key],
						'metode_fol_up' => $metode_fol_up[$key],
						'keterangan'    => $keterangan[$key],
					];
				}
			}

			$this->m_admin->update("tr_prospek_gc", $data, "id_prospek_gc", $id);

			$this->db->query("UPDATE tr_prospek_gc_kendaraan set status='input', created_at='$waktu',created_by='$login_id' WHERE id_prospek_gc='$id'");
			$this->db->delete('tr_prospek_fol_up', ['id_prospek' => $id]);

			if (isset($dt_fol_up)) {
				$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up);
			}
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek_crm/gc'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	// public function edit()
	// {
	// 	$tabel		= $this->tables;
	// 	$pk 			= $this->pk;
	// 	$id 			= $this->input->get('id');
	// 	$d 				= array($pk => $id);
	// 	$id_dealer = $this->m_admin->cari_dealer();
	// 	$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer FROM ms_karyawan_dealer
	// 		LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
	// 	 WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> '' AND ms_karyawan_dealer.id_jabatan IN('JBT-099','JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103')  AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");
	// 	$filter = ['id_prospek' => $id];
	// 	$dt_prospek = $this->m_prospek->getProspek($filter);
	// 	if ($dt_prospek->num_rows() > 0) {
	// 		$row = $dt_prospek->row();
	// 		$cek_sblm = $this->m_prospek->getPembelianSebelumnyaByNoRangka($row->noFramePembelianSebelumnya)->row();
	// 		$row->nama_sales_sebelumnya = $cek_sblm == null ? null : $cek_sblm->nama_sales_sebelumnya;
	// 		$row->id_flp_md_sebelumnya = $cek_sblm == null ? null : $cek_sblm->id_flp_md;
	// 		$row->finance_company = $cek_sblm == null ? null : $cek_sblm->finance_company;
	// 		$data['row']  = $row;
	// 		// send_json($data['row']);
	// 		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
	// 		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
	// 		// $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
	// 		$data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') ORDER BY id_pekerjaan ASC ");

	// 		$data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
	// 		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
	// 		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
	// 		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
	// 		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
	// 		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
	// 		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
	// 		$data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.warna from ms_item 
	// 			inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
	// 			WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
	// 			GROUP BY ms_item.id_warna
	// 			ORDER BY ms_warna.warna ASC");
	// 		$data['isi']    = $this->page;
	// 		$data['title']	= "Edit " . $this->title;
	// 		$data['set']		= "insert";
	// 		$data['mode']		= "edit";
	// 		$data['details'] = $this->db->query("SELECT * FROM tr_prospek_fol_up WHERE id_prospek='$id'")->result();
	// 		$fsumber = ['id_platform_data' => $row->platformData];
	// 		$data['set_sumber_prospek'] = $this->m_prospek->getSumberProspek($fsumber)->result();

	// 		$this->template($data);
	// 	}
	// }
	public function detail()
	{
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= anti_injection($this->input->get('id'));
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer FROM ms_karyawan_dealer
			LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
		 WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> ''  AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");
		$filter = ['id_prospek' => $id];
		$dt_prospek = $this->m_prospek->getProspek($filter);
		if ($dt_prospek->num_rows() > 0) {
			$row = $dt_prospek->row();
			$data['row']  = $row;
			$cek_sblm = $this->m_prospek->getPembelianSebelumnyaByNoRangka($row->noFramePembelianSebelumnya)->row();
			$row->nama_sales_sebelumnya = $cek_sblm == null ? null : $cek_sblm->nama_sales_sebelumnya;
			$row->id_flp_md_sebelumnya = $cek_sblm == null ? null : $cek_sblm->id_flp_md;
			$row->finance_company = $cek_sblm == null ? null : $cek_sblm->finance_company;
		}
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.warna from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title;
		$data['set']		= "insert";
		$data['mode']		= "detail";
		$data['details'] = $this->m_prospek->getProspekFollowUpCrm($id);
		$data['interaksi'] = $this->m_prospek->getProspekInteraksi($id, 'LIMIT 5');
		$fsumber = ['id_platform_data' => $row->platformData];
		$data['set_sumber_prospek'] = $this->m_prospek->getSumberProspek($fsumber)->result();
		// send_json($data['row']);
		$this->template($data);
	}
	public function edit_gc()
	{
		$tabel		= "tr_prospek_gc";
		$pk 			= "id_prospek_gc";
		$id 	= $data['id']		= $this->input->get('id');
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND id_flp_md <> ''  AND ms_karyawan_dealer.active='1' AND ms_karyawan_dealer.id_jabatan IN('JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103') ORDER BY nama_lengkap ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$filter['id_prospek_gc'] = $id;
		$data['dt_prospek_gc'] = $this->m_prospek->getProspekGC($filter);
		if ($data['dt_prospek_gc']->num_rows() > 0) {
			$row = $data['dt_prospek_gc']->row();
		}

		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$data['details'] = $this->db->query("SELECT * FROM tr_prospek_fol_up WHERE id_prospek='$id'")->result();

		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title . " Group Customer";
		$data['set']		= "edit_gc";
		$data['mode']		= "edit";
		$this->template($data);
	}
	public function createSPK()
	{

		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$prospek = $this->db->get_where('tr_prospek', ['id_prospek' => $id]);

		if($prospek->num_rows()> 0){
			if($prospek->row()->leads_id !=''){
				// pengecekan data list followup leads di crm utk membuat stageId 8x
				$leads = $prospek->row()->leads_id;
				$cek_stageId = $this->db_crm->query("select stageId from leads_history_stage where leads_id ='$leads' and stageId = 8");

				if($cek_stageId->num_rows() == 0){
					$_SESSION['pesan'] = "Data Leads ".$leads."' belum dibuat follow up dengan status Prospek! ";
					$_SESSION['tipe']  = "warning";
					// echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek_crm'>";
							
					$rsp = [
						'status' => 'sukses',
						'link' => base_url($this->folder . '/prospek_crm')
					];
					send_json($rsp);
				}
			}
		}

		$r = "";
		$isi_ktp = $this->input->post('no_ktp');
		$ktp = strlen($isi_ktp);
		if ($ktp < 16) {
			$jum = 16 - $ktp;
			for ($i = 1; $i <= $jum; $i++) {
				$r = $r . "0";
			}
			$ktp_f = $r . $isi_ktp;
		} else {
			$ktp_f = $isi_ktp;
		}
		$data['no_ktp'] 							= $ktp_f;
		$data['no_kk'] 								= $this->input->post('no_kk');
		$data['jenis_wn'] 						= $this->input->post('jenis_wn');
		$data['jenis_pembelian'] 			= $this->input->post('jenis_pembelian');
		$id_dealer = $data['id_dealer'] 						= $this->m_admin->cari_dealer();
		$data['nama_konsumen'] 				= $this->input->post('nama_konsumen');
		$data['no_npwp'] 							= $this->input->post('no_npwp');
		$data['jenis_kelamin'] 				= $this->input->post('jenis_kelamin');
		$data['tgl_lahir'] 						= $this->input->post('tgl_lahir');
		$data['tempat_lahir']					= $this->input->post('tempat_lahir');
		$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
		$id_kelurahan 							= $this->input->post('id_kelurahan');
		$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
		$data['id_kecamatan'] 				= $region[1];
		$data['id_kabupaten'] 				= $region[2];
		$data['id_provinsi'] 					= $region[3];
		$data['kodepos'] 							= $this->input->post('kodepos');
		$data['alamat'] 							= $this->input->post('alamat');
		$data['agama'] 								= $this->input->post('agama');
		$data['pekerjaan'] 						= $this->input->post('pekerjaan');
		$data['pengeluaran_bulan'] 		= $this->input->post('pengeluaran_bulan');
		$data['pendidikan'] 					= $this->input->post('pendidikan');
		//$data['penanggung_jawab'] 		= $this->input->post('penanggung_jawab');	
		$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
		$data['no_telp'] 							= $this->input->post('no_telp');
		$data['sedia_hub'] 						= $this->input->post('sedia_hub');
		$data['merk_sebelumnya'] 			= $this->input->post('merk_sebelumnya');
		$data['jenis_sebelumnya'] 		= $this->input->post('jenis_sebelumnya');
		$data['digunakan'] 						= $this->input->post('digunakan');
		$data['pemakai_motor'] 				= $this->input->post('pemakai_motor');
		$data['email'] 								= $this->input->post('email');
		$data['status_rumah'] 				= $this->input->post('status_rumah');
		$data['status_nohp'] 					= $this->input->post('status_nohp');
		$data['status_prospek'] 			= 'Deal';
		$data['id_tipe_kendaraan'] 		= $this->input->post('id_tipe_kendaraan');
		$data['no_mesin'] 						= $this->input->post('no_mesin');
		$data['no_rangka'] 						= $this->input->post('no_rangka');
		$data['id_warna'] 						= $this->input->post('id_warna');
		$data['tahun_rakit'] 					= $this->input->post('tahun_rakit');
		$data['atur_tgl'] 						= $this->input->post('atur_tgl');
		$data['atur_jam'] 						= $this->input->post('atur_jam');
		// $data['facebook'] 						= $this->input->post('facebook');
		// $data['youtube'] 							= $this->input->post('youtube');
		// $data['twitter'] 							= $this->input->post('twitter');
		// $data['instagram'] 						= $this->input->post('instagram');
		$data['atur_jam'] 						= $this->input->post('atur_jam');
		$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
		//$data['id_list_appointment']  = $this->input->post('id_list_appointment');	
		$data['id_kelurahan_kantor'] 	= $this->input->post('id_kelurahan_kantor');
		$cek_req_instansi = $this->db->get_where('ms_sub_pekerjaan', array('id_sub_pekerjaan' => $this->input->post('sub_pekerjaan')));
		if ($cek_req_instansi->num_rows() > 0) {
			$req_instansi = $cek_req_instansi->row()->required_instansi;
			if ($req_instansi == '1') {
				if ($this->input->post('id_kelurahan_kantor') == '') {
					$response = ['status' => 'error', 'pesan' => 'Kelurahan Kantor tidak boleh kosong'];
					send_json($response);
				}
			}
		}
		$data['prioritas_prospek'] 		= (int)$this->input->post('prioritas_prospek');
		$data['program_umum'] 				= $this->input->post('program_utama') != '' ? $this->input->post('program_utama') : NULL;
		$data['program_gabungan'] 		= $this->input->post('program_gabungan') != '' ? $this->input->post('program_gabungan') : NULL;
		$data['updated_at']						= $waktu;
		$data['updated_by']						= $login_id;
		$data['status_aktifitas'] = 'Completed';
		$data['tgl_prospek']          = $this->input->post('tgl_prospek');
		$data['longitude']            = $this->input->post('longitude');
		$data['latitude']             = $this->input->post('latitude');
		$data['alamat_kantor']        = $this->input->post('alamat_kantor');
		$data['no_telp_kantor']       = $this->input->post('no_telp_kantor');
		$data['jenis_customer']       = $this->input->post('jenis_customer');
		if ($this->input->post('sumber_prospek') != null) {
			// $data['sumber_prospek']       = $this->input->post('sumber_prospek');
		}
		$data['test_ride_preference'] = $this->input->post('test_ride_preference');
		$data['rencana_pembayaran']   = $this->input->post('rencana_pembayaran');
		$data['catatan']              = $this->input->post('catatan');
		$data['keterangan_not_deal']  = $this->input->post('keterangan_not_deal');
		$data['sub_pekerjaan']              = $this->input->post('sub_pekerjaan');
		$data['pekerjaan_lain']  = $this->input->post('lain');
		$data['nama_tempat_usaha']              = $this->input->post('nama_usaha');
		$data['id_event']  = $this->input->post('id_event');

		// send_json($data);
		$this->db->trans_begin();
		$this->m_admin->update($tabel, $data, $pk, $id);
		if (!$this->db->trans_status()) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			$pesan = "Data Prospek : $id sudah bisa dibuat SPK. ";
			$_SESSION['pesan'] 	=  $pesan;
			$_SESSION['tipe'] 	= "success";
			$rsp = [
				'status' => 'sukses',
				'link' => base_url($this->folder . '/spk/add')
			];
			send_json($rsp);
		}
	}
	public function view()
	{
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->m_admin->getSortDealer("ms_karyawan_dealer", "nama_lengkap", "ASC", $id_dealer);
		$data['dt_prospek'] = $this->m_admin->kondisi($tabel, $d);
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan", "kelurahan", "ASC");
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_no_mesin'] = $this->m_admin->getSort("tr_scan_barcode", "no_mesin", "ASC");
		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title;
		$data['set']		= "detail";
		$this->template($data);
	}
	public function getWarna()
	{
		$id_tipe_kendaraan = anti_injection($this->input->post('id_tipe_kendaraan'));
		$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC
				";
		$dt_warna = $this->db->query($dq);
		if ($dt_warna->num_rows() > 0) {
			echo "<option value=''>- choose -</option>";
			foreach ($dt_warna->result() as $res) {
				echo "<option value='$res->id_warna' >$res->id_warna | $res->warna</option>";
			}
		}
	}
	public function getWarnaEdit()
	{
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC
				";
		$dt_warna = $this->db->query($dq);
		if ($dt_warna->num_rows() > 0) {
			echo "<option value=''>- choose -</option>";
			foreach ($dt_warna->result() as $res) {
				if ($res->id_warna == $id_warna) {
					$select = 'selected';
				} else {
					$select = '';
				}
				echo "<option value='$res->id_warna' $select >$res->id_warna | $res->warna</option>";
			}
		}
	}
	public function cancel_prospek()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "cancel_prospek";
		$data['mode'] = 'cancel';
		$id_dealer       = $this->m_admin->cari_dealer();
		$data['reasons'] = $this->db->get_where('ms_reasons', ['fungsi' => 'Cancel Prospek']);
		$data['prospek'] = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_dealer=$id_dealer
			ORDER BY tr_prospek.created_at DESC");
		$this->template($data);
	}
	function save_cancel()
	{
		$waktu                       = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id                    = $this->session->userdata('id_user');
		$id_prospek                  = $this->input->post('id_prospek');

		$data['id_reasons']          = $this->input->post('id_reasons');
		$data['keterangan_not_deal'] = $this->input->post('keterangan_not_deal');
		$data['id_prospek']          = $this->input->post('id_prospek');
		$data['cancel_at']           = $waktu;
		$data['cancel_by']           = $login_id;
		$data['status_aktifitas']    = 'Completed';
		$data['status_prospek']    = 'Not Deal';
		$this->db->update('tr_prospek', $data, ['id_prospek' => $id_prospek]);
		$_SESSION['pesan'] = "Data has been canceled successfully";
		$_SESSION['tipe']  = "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek_crm'>";
	}
	public function notif_outstanding()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Notifikasi List Outstanding Prospek';
		$data['set']   = "notif_outstanding";
		$date          = $this->input->get('tgl');
		$id_dealer     = $this->m_admin->cari_dealer();
		$login_id      = $this->session->userdata('id_user');
		$data['reasons'] = $this->db->get_where('ms_reasons', ['fungsi' => 'Cancel Prospek']);

		$data['prospek'] = $this->db->query("SELECT * FROM tr_prospek 
			WHERE (status_prospek='Hot Prospect' OR status_prospek='Hot Prospek' OR status_prospek='hot' OR status_prospek='low') 
			AND (SELECT COUNT(id_prospek) FROM tr_prospek_fol_up 
			WHERE tr_prospek.id_prospek=id_prospek )>0 
			AND tr_prospek.id_dealer=$id_dealer AND id_karyawan_dealer=(SELECT id_karyawan_dealer FROM ms_user WHERE id_user=$login_id)
			ORDER BY created_at DESC ");
		$this->template($data);
	}
	function getProgramUmum()
	{
		$filter = [
			'id_tipe_kendaraan' => anti_injection($this->input->post('id_tipe_kendaraan')),
			'id_warna' => anti_injection($this->input->post('id_warna')),
			'jenis_beli' => anti_injection($this->input->post('jenis_beli')),
		];
		$result = $this->m_prospek->getProgramUmum($filter);
		if ($result == FALSE) {
			$result = [];
		}
		send_json($result);
	}

	public function getProgramGabungan()
	{
		$id_program_md = $this->input->post('program_utama');
		$jenis_beli = $this->input->post('jenis_beli');
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$dt = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();
		// send_json($this->input->post());

		$cek_program = $this->db->get_where('tr_sales_program', ['id_program_md' => $id_program_md]);
		if ($cek_program->num_rows() == 0) {
			$result = [];
			send_json($result);
		} else {
			$cek_program = $cek_program->row();
		}
		//Semua Dealer
		if ($cek_program->kuota_program == 0 || $cek_program->kuota_program == NULL) {
			$cek_dealer = $this->db->get_where('tr_sales_program_dealer', ['id_program_md' => $cek_program->id_program_md]);
			if ($cek_dealer->num_rows() == 0) {
				$tersedia = 1;
			} else {
				$dealer = $this->db->get_where('tr_sales_program_dealer', ['id_program_md' => $id_program_md, 'id_dealer' => $id_dealer]);
				if ($dealer->num_rows() > 0) {
					$dealer = $dealer->row();
					// $cek_terjual=$this->db->query("SELECT * FROM tr_sales_order
					// 							   LEFT JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
					// 							   WHERE tr_spk.program_umum='$id_program_md' 
					// 							   AND tr_sales_order.status_so='so_invoice' 
					// 							   AND tr_sales_order.id_dealer='$id_dealer'
					// 		")->num_rows();
					$cek_terjual = $this->db->query("SELECT * FROM tr_spk
		 									   WHERE tr_spk.program_umum='$id_program_md' 
		 									   -- AND tr_sales_order.status_so='so_invoice' 
		 									   AND tr_spk.id_dealer='$id_dealer'
		 									   AND status_spk<>'closed'
		 				")->num_rows();
					$tersedia = $cek_terjual <= $dealer->kuota ? $dealer->kuota - $cek_terjual : 0;
				} else {
					$tersedia = 0;
				}
			}
		} else {
			// $cek_terjual=$this->db->query("SELECT * FROM tr_sales_order
			// 									   LEFT JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
			// 									   WHERE tr_spk.program_umum='$id_program_md' AND tr_sales_order.status_so='so_invoice'
			// 				")->num_rows();
			$cek_terjual = $this->db->query("SELECT * FROM tr_spk
		 									   WHERE tr_spk.program_umum='$id_program_md' 
		 									   AND status_spk<>'closed'
		 				")->num_rows();
			$tersedia = $cek_terjual <= $cek_program->kuota_program ? $cek_program->kuota_program - $cek_terjual : 0;
		}
		// if ($cek_ketersediaan->num_rows()>0)
		// {
		// 	$cek_k = $cek_ketersediaan->row();
		// 	if ($cek_k->kuota>0) {
		// 		$cek_terjual=$this->db->query("SELECT * FROM tr_sales_order
		// 									   LEFT JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
		// 									   WHERE tr_spk.program_umum='$id_program_md' AND tr_sales_order.status_so='so_invoice'
		// 				")->num_rows();
		// 				//$cek_terjual=11;
		// 		if ($cek_terjual <= $cek_k->kuota) {
		// 			$tersedia=$cek_k->kuota - $cek_terjual;
		// 		}else{
		// 			$tersedia=0;
		// 		}
		// 	}else{
		// 		$tersedia=1;
		// 	}
		// }else{
		// 	$tersedia='kosong';
		// }
		if ($tersedia > 0) {
			$nilai_voucher_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir  AND tr_sales_program.id_program_md='$id_program_md' ");
			if ($nilai_voucher_program->num_rows() > 0) {
				$program = $nilai_voucher_program->row();
				if (strtolower($jenis_beli) == 'cash') {
					$nilai = $nilai_voucher_program->row();
					$nilai_voucher_program = $nilai->tot_cash;
				} elseif ($jenis_beli == 'kredit') {
					$nilai = $nilai_voucher_program->row();
					$nilai_voucher_program = $nilai->tot_kredit;
				}
				if ($program->id_jenis_sales_program == 'SP-002') {
					$nilai_voucher_program = 0;
				}
			} else {
				$nilai_voucher_program = '';
			}
			// echo $nilai_voucher_program . '##';
			// $cek_program = $this->db->query("SELECT * FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan");
			$cek_program = $this->db->query("SELECT DISTINCT(id_program_md)as id_program_md_gabungan FROM(
				SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan='$id_program_md'
				UNION
				SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md IN (SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md_gabungan='$id_program_md') OR id_program_md='$id_program_md' ) as tbl_gabungan
				WHERE id_program_md<>'$id_program_md'
			");
			if (strtolower($jenis_beli) == 'cash') {
				// echo "<option> - choose- </option>";
				$x = 0;
				foreach ($cek_program->result() as $rs) {
					$cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md 
					WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' 
					AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' 
					AND id_warna LIKE '%$id_warna%' 
					AND tr_sales_program_tipe.status<>'new' 
					AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir
					");
					if ($cek_program->num_rows() > 0) {
						$ck = $cek_program->row();
						if ($ck->tot_cash > 0) {
							$result[] = ['id_program_md' => $ck->id_program_md, 'judul_kegiatan' => $ck->judul_kegiatan];
							$x++;
						}
					}
				}
				// echo "##$x";
			} elseif (strtolower($jenis_beli) == 'kredit') {
				// echo "<option> - choose- </option>";
				$xx = 0;
				foreach ($cek_program->result() as $rs) {
					$cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir");
					if ($cek_program->num_rows() > 0) {
						$ck = $cek_program->row();
						if ($ck->tot_kredit > 0) {
							// echo "<option value='$ck->id_program_md'>$ck->id_program_md | $ck->judul_kegiatan</option>";
							$result[] = ['id_program_md' => $ck->id_program_md, 'judul_kegiatan' => $ck->judul_kegiatan];
						}
					}
					$xx++;
				}
				// echo "##$xx";
			}
			// $program_tipe = $this->db->get_where('tr_sales_program_tipe', ['id_program_md' => $id_program_md])->row();
			// echo "##$program->jenis_barang";
		}
		if (isset($result)) {
			send_json($result);
		} else {
			$result = [];
			send_json($result);
		}
	}

	function getOptionsStatusFollowUp()
	{
		$fl = $this->input->post();
		$fl['select'] = 'dropdown';
		$result = $this->m_prospek->getStatusKomunikasiFollowUp($fl);
		send_json($result);
	}

	public function edit()
	{
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer FROM ms_karyawan_dealer
			LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
		 WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> '' AND ms_karyawan_dealer.id_jabatan IN('JBT-099','JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103')  AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");
		$filter = ['id_prospek' => $id];
		$dt_prospek = $this->m_prospek->getProspek($filter);
		if ($dt_prospek->num_rows() > 0) {
			$row = $dt_prospek->row();
			$data['row']  = $row;
			$cek_sblm = $this->m_prospek->getPembelianSebelumnyaByNoRangka($row->noFramePembelianSebelumnya)->row();
			$row->nama_sales_sebelumnya = $cek_sblm == null ? null : $cek_sblm->nama_sales_sebelumnya;
			$row->id_flp_md_sebelumnya = $cek_sblm == null ? null : $cek_sblm->id_flp_md;
			$row->finance_company = $cek_sblm == null ? null : $cek_sblm->finance_company;
		}
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		// $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') ORDER BY id_pekerjaan ASC ");

		$data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		$data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.warna from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC");
		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title;
		$data['set']		= "follow_up";
		$data['mode']		= "edit";
		$data['details'] = $this->m_prospek->getProspekFollowUpCrm($id);
		$data['interaksi'] = $this->m_prospek->getProspekInteraksi($id, 'LIMIT 5');
		$fsumber = ['id_platform_data' => $row->platformData];
		$data['set_sumber_prospek'] = $this->m_prospek->getSumberProspek($fsumber)->result();
		$data['is_sales']=$this->is_sales;
		// send_json($data);
		$this->template($data);
	}


	
	public function edit_testing()
	{
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		// $data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer FROM ms_karyawan_dealer
		// 	LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
		//  WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> '' AND ms_karyawan_dealer.id_jabatan IN('JBT-099','JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103')  AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");
		// $filter = ['id_prospek' => $id];
		// $dt_prospek = $this->m_prospek->getProspek($filter);
		// if ($dt_prospek->num_rows() > 0) {
		// 	$row = $dt_prospek->row();
		// 	$data['row']  = $row;
		// 	$cek_sblm = $this->m_prospek->getPembelianSebelumnyaByNoRangka($row->noFramePembelianSebelumnya)->row();
		// 	$row->nama_sales_sebelumnya = $cek_sblm == null ? null : $cek_sblm->nama_sales_sebelumnya;
		// 	$row->id_flp_md_sebelumnya = $cek_sblm == null ? null : $cek_sblm->id_flp_md;
		// 	$row->finance_company = $cek_sblm == null ? null : $cek_sblm->finance_company;
		// }
		// $data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		// $data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		// // $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		// $data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') ORDER BY id_pekerjaan ASC ");

		// $data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
		// $data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		// $data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		// $data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		// $data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		// $data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// // $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		// $data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		// $data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.warna from ms_item 
		// 		inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
		// 		WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
		// 		GROUP BY ms_item.id_warna
		// 		ORDER BY ms_warna.warna ASC");
		$data['isi']    = $this->page;


		$data['title']	= "Edit " . $this->title;
		$data['set']		= "follow_up";
		$data['mode']		= "edit";
		$data['details'] = $this->m_prospek->getProspekFollowUpCrm($id);
		$data['interaksi'] = $this->m_prospek->getProspekInteraksi($id, 'LIMIT 5');
		$fsumber = ['id_platform_data' => $row->platformData];
		$data['set_sumber_prospek'] = $this->m_prospek->getSumberProspek($fsumber)->result();
		$data['is_sales']=$this->is_sales;

		var_dump($data );
		die();
		// send_json($data);
		$this->template($data);
	}



	

	function updateFollowUpWithCRM($post_init,$status_prospek)
	{

		$id_prospek_id = $post_init['id'] ;
		$leads_id_get = $post_init['leads_id'];

		$wilayah['kelurahan']  = $post_init['kelurahan'];
		$wilayah['kecamatan']  = $post_init['kecamatan'];
		$wilayah['kabupaten']  = $post_init['kabupaten'];
		$wilayah['provinsi']   = $post_init['provinsi'];
  
		$wilayah_validasi = $this->crm_wilayah->get_provinsi($wilayah);

		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);

		if (isset($post_init['id_warna'])) {
			if ($post_init['id_warna']!= '') {
				$warna_post = $post_init['id_warna'];
			}
		}else{
			$warna_post = '';
		}

		if (isset($post_init['id_warna'])) {
			if ($post_init['id_warna']!= '') {
				$warna_post = $post_init['id_warna'];
			}
		}else{
			$warna_post = '';
		}
		
		if (isset($post_init['sumber_prospek'])) {
			$update_prospek_with_crm['sumber_prospek']= $post_init['sumber_prospek'];
		}


		$update_prospek_with_crm = [
			'agama' =>			$agama_  =	$post_init['agama'],
			'alamat'=> 			$post_init['alamat'],
			'alamat_kantor'=> 	$post_init['alamat_kantor'],
			'catatan'=>			 $post_init['catatan'],
			'email' => 		$email_ = $post_init['email'] == '' ? NULL : $post_init['email'], 
			'id_kabupaten'=> $wilayah_validasi->id_kabupaten,
			'id_kecamatan'=> $wilayah_validasi->id_kecamatan,
			'id_kelurahan'=> $wilayah_validasi->id_kelurahan ,
			'id_kelurahan_kantor'=> $post_init['id_kelurahan_kantor'],
			'id_provinsi' => $wilayah_validasi->id_provinsi,
			'id_tipe_kendaraan'=> $tipe_kendaraan_ = $post_init['id_tipe_kendaraan'],
			'id_warna' =>  $warna_ =  $warna_post, 
			'jenis_customer' => $post_init['jenis_customer'],
			'jenis_kelamin' => $post_init['jenis_kelamin'],
			'jenis_sebelumnya'=> $post_init['jenis_sebelumnya'],
			'jenis_wn'=> $post_init['jenis_wn'],
			'kodepos' => $post_init['kodepos'],
			'latitude' => $post_init['latitude'] == '-' ? 0 : $post_init['latitude'],
			'longitude' => $post_init['longitude'] == '-' ? 0 : $post_init['longitude'],
			'merk_sebelumnya' => $post_init['merk_sebelumnya'],
			'nama_konsumen' => $nama_ =  $post_init['nama_konsumen'],
			'nama_tempat_usaha' => $post_init['nama_usaha'],
			'no_hp' => $no_hp_ = $post_init['no_hp'],
			'no_kk' => $post_init['no_kk'],
			'no_ktp'=> $no_ktp_ = $post_init['no_ktp'],
			'no_npwp' => $post_init['no_npwp'],
			'no_telp' => $post_init['no_telp'],
			'no_telp_kantor' => $post_init['no_telp_kantor'],
			'pekerjaan' => $pekerjaan_ = $post_init['pekerjaan'],
			'pemakai_motor' => $post_init['pemakai_motor'],
			'pemakai_motor' => $post_init['pemakai_motor'],
			'prioritas_prospek' => (int)$post_init['prioritas_prospek'],
			'rencana_pembayaran' => $post_init['rencana_pembayaran'],
			'status_nohp' => $post_init['status_nohp'],
			'sub_pekerjaan' => $sub_pekerjaan_ = (int)$post_init['sub_pekerjaan'],
			'tempat_lahir'=> $post_init['tempat_lahir'],
			'test_ride_preference' => $post_init['test_ride_preference'],
			'tgl_lahir' => $post_init['tgl_lahir'],
			'tgl_prospek' => $post_init['tgl_prospek'],
			'status_prospek'=> $status_prospek,
			'updated_at '=>$waktu,
			'TestRideStatus' => $post_init['TestRideStatus'],
			'TestRideNote' => $post_init['TestRideNote'],
			// $sumber_prospek_post
			// 'sumber_prospek'=> $sumber_prospek_post,
		];

		$check_deskripsi_pekerjaan = $this->db->query("SELECT pekerjaan  from ms_pekerjaan WHERE id_pekerjaan='$pekerjaan_' ")->row();

		if (count($check_deskripsi_pekerjaan) > 0){
			$deskripsi_pekerjaan_=$check_deskripsi_pekerjaan->pekerjaan;
		}else{
			$deskripsi_pekerjaan_=NULL;
		}

		$this->db->query("SET FOREIGN_KEY_CHECKS=0"); 
		$this->db->where('id_prospek', $id_prospek_id);
		$this->db->update('tr_prospek', $update_prospek_with_crm);
   		$this->db->query("SET FOREIGN_KEY_CHECKS=1");

		   if($email_ == 'N' ){  
			$email_leads='N';
		   }else{
			 if($email_ == '' ){
				$email_leads= NULL;
			 }else{
				 $email_leads= $post_init['email'];
			 }
			}


		   $update_leads = [
			'kodePekerjaan'=>$pekerjaan_ ,
			'KodePekerjaanKTP'=>$sub_pekerjaan_ ,
			'idAgama'=>$agama_ ,
			'nama' =>$nama_ ,
			'noHP' => $no_hp_,
			'noKtp' => $no_ktp_,
			'email' => $email_leads,
			'kelurahan' => $wilayah_validasi->id_kelurahan,
			'kabupaten' =>$wilayah_validasi->id_kabupaten,
			'kecamatan' => $wilayah_validasi->id_kecamatan,
			'provinsi' =>  $wilayah_validasi->id_provinsi,
			'deskripsiPekerjaan' =>strtoupper($deskripsi_pekerjaan_),
			'updated_at' => $waktu,
			'TestRideStatus' => $post_init['TestRideStatus'],
			'TestRideNote' => $post_init['TestRideNote'],
		  ];

		  $this->db_crm->query('SET FOREIGN_KEY_CHECKS=0'); 
		  $this->db_crm->where('leads_id', $leads_id_get);
		  $this->db_crm->update('leads', $update_leads);
		  $this->db_crm->query('SET FOREIGN_KEY_CHECKS=1');
		  $this->db_crm->trans_commit();

		  if ($this->db_crm->trans_status() === FALSE) {
			$this->db_crm->trans_rollback();
			
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrongsss !'
			];

		} else {
			
			$_SESSION['pesan'] = "Data has been saved successfully.";
			$_SESSION['tipe']  = "success";

			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/prospek_crm/'),
			];
		}

		return $rsp;

	}


	public function updateFollowUp()
	{

		$this->load->model('CRM_wilayah', 'crm_wilayah');

		$check_ddl = $this->input->post("input_from_crm");
		$waktu     = waktu_full();
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$tabel     = $this->tables;
		$pk        = $this->pk;
		$id        = $this->input->post("id");
		$pesan = "";
	
		$prospek   = $this->db->get_where($tabel, ['id_prospek' => $id])->row();
		$leads = $this->db_crm->get_where("leads",['idProspek'=>$id])->row();

		$status_prospek='';
		if ($this->input->post('status_prospek_header')!='') {
			$status_prospek=$this->input->post('status_prospek_header');
		}

		$post_init = $_POST;
		$details = json_decode($this->input->post('details'), true);

		$fp = [
			'id_prospek' => $id,
			'id_dealer' => $id_dealer
		];

		$tot_fu = $this->m_prospek->getProspekFollowUp($fp)->num_rows();


		if ( $check_ddl == 'ldd' ){
			$this->updateFollowUpWithCRM($post_init,$status_prospek);
		}
		
		$update = [
			'status_prospek'    => $status_prospek,
			'alamat'            => $this->input->post('alamat'),
			'tgl_prospek'       => $this->input->post('tgl_prospek'),
			'prioritas_prospek' => (int)$this->input->post('prioritas_prospek'),
			'nama_konsumen'     => $this->input->post('nama_konsumen'),
			'no_hp'             => $this->input->post('no_hp'),
			'status_nohp'       => $this->input->post('status_nohp'),
			'no_telp'           => $this->input->post('no_telp'),
			'email'             => $this->input->post('email'),
			'jenis_kelamin'     => $this->input->post('jenis_kelamin'),
			'pekerjaan'         => $this->input->post('pekerjaan'),
			'sub_pekerjaan'     => (int)$this->input->post('sub_pekerjaan'),
			'jenis_wn'          => $this->input->post('jenis_wn'),
			'no_ktp'            => $this->input->post('no_ktp'),
			'no_kk'             => $this->input->post('no_kk'),
			'no_npwp'           => $this->input->post('no_npwp'),
			'tempat_lahir'      => $this->input->post('tempat_lahir'),
			'tgl_lahir'         => $this->input->post('tgl_lahir'),
			'id_kelurahan'      => $this->input->post('id_kelurahan'),
			'id_kecamatan'      => $this->input->post('id_kecamatan'),
			'id_kabupaten'      => $this->input->post('id_kabupaten'),
			'id_provinsi'       => $this->input->post('id_provinsi'),
			'alamat'            => $this->input->post('alamat'),
			'kodepos'           => $this->input->post('kodepos'),
			'agama'             => $this->input->post('agama'),
			'jenis_sebelumnya'  => $this->input->post('jenis_sebelumnya'),
			'merk_sebelumnya'   => $this->input->post('merk_sebelumnya'),
			'pemakai_motor'     => $this->input->post('pemakai_motor'),
			'pemakai_motor'     => $this->input->post('pemakai_motor'),
			'longitude' => $this->input->post('longitude') == '-' ? 0 : $this->input->post('longitude'),
			'latitude' => $this->input->post('latitude') == '-' ? 0 : $this->input->post('latitude'),
			'nama_tempat_usaha'           => $this->input->post('nama_usaha'),
			'alamat_kantor'        => $this->input->post('alamat_kantor'),
			'no_telp_kantor'       => $this->input->post('no_telp_kantor'),
			'id_kelurahan_kantor'  => $this->input->post('id_kelurahan_kantor'),
			'jenis_customer'       => $this->input->post('jenis_customer'),
			'test_ride_preference' => $this->input->post('test_ride_preference'),
			'rencana_pembayaran'   => $this->input->post('rencana_pembayaran'),
			'catatan'              => $this->input->post('catatan'),
			'id_tipe_kendaraan'    => $this->input->post('id_tipe_kendaraan'),
			'id_warna'             => $this->input->post('id_warna'),
			'TestRideStatus'   => $this->input->post('TestRideStatus'),
			'TestRideNote'     => $this->input->post('TestRideNote'),
			'updated_at'	=> $waktu,
			'updated_by' => $login_id
		];


		if ((string)$this->input->post('sumber_prospek') != '') {
		}else{
			// $update['sumber_prospek'] = $this->input->post('sumber_prospek');
		}


		$ada_prospek=0;
		
		foreach ($details as $dt) {
			if ($dt['kodeHasilStatusFollowUp']==1) {
				$ada_prospek++;
			}
		}

		if ($ada_prospek==0) {
			$rsp = [
				'status' =>'error',
				'pesan' => "Hasil status follow up = PROSPECT belum ditentukan"
			];
			send_json($rsp);
		}


		$status = 'sukses';
		$pesan = 'Data has been saved successfully.';

		// Cek Test Ride & Trade In CE Apps
		// send_json($leads);
	
		if ($leads!=null) {
			if ((string)$leads->TestRideID!='') {

				if ((string)$this->input->post('TestRideStatus')=='') {
					$pesan = "Silahkan tentukan Test Ride Status";
					$rsp = [
						'status'    => 'error',
						'tipe'      => 'danger',
						'pesan'     => $pesan
					];
					send_json($rsp);
				}
				
				if ((string)$prospek->TestRideStatus!=(string)$this->input->post('TestRideStatus')) {
					if ((string)$this->input->post('TestRideNote')=='') {
						$pesan = "Silahkan tentukan Test Ride Note";
						$rsp = [
							'status'    => 'error',
							'tipe'      => 'danger',
							'pesan'     => $pesan
						];
						send_json($rsp);
					}
					$mokita_apps_update_status_test_ride=[
						'AppsOrderNumber'   => $leads->sourceRefID,
						'DmsOrderNumber'    => $leads->batchID,
						'TestRideID'        => $leads->TestRideID,
						'TestRideStatus'    => $this->input->post('TestRideStatus',true),
						'TestRideNote'      => $this->input->post('TestRideNote',true),
					];
					$this->load->library('mokita');
					$response_mokita = json_decode($this->mokita->h1_update_status_test_ride($mokita_apps_update_status_test_ride));
					if ($response_mokita->status==1) {
						$update_riding_test =[
							'TestRideStatus'   => $this->input->post('TestRideStatus'),
							'TestRideNote'     => $this->input->post('TestRideNote'),
						];
						$this->db->update($tabel, $update_riding_test, [$pk => $id]);
						$this->db_crm->update('leads', $update_riding_test,['leads_id'=>$leads->leads_id]);
						$pesan_ce = ". Berhasil mengirim Test Ride ke CE Apps.";
					}else{
						$mokita_message = isset($response_mokita->message) ? $response_mokita->message : $response_mokita->Message;
						$pesan_ce = ". Gagal mengirim Test Ride ke CE Apps. ($mokita_message)";
					}
				}
			}

			if ((string)$leads->TradeInID!='') {

				if ((string)$this->input->post('TradeInStatus')=='') {
					$pesan = "Silahkan tentukan Trade In Status";
					$rsp = [
						'status'    => 'error',
						'tipe'      => 'danger',
						'pesan'     => $pesan
					];
					send_json($rsp);
				}

				$set_trade_in = false;
				if ((string)$prospek->TradeInStatus!=(string)$this->input->post('TradeInStatus')) {
					$set_trade_in = true;
				}
				if ((string)$prospek->DealPrice!=(string)$this->input->post('DealPrice')) {
					$set_trade_in = true;
				}
				
				if ($set_trade_in) {
					if ((string)$this->input->post('TradeInNote')=='') {
						$pesan = "Silahkan tentukan Trade In Note";
						$rsp = [
							'status'    => 'error',
							'tipe'      => 'danger',
							'pesan'     => $pesan
						];
						send_json($rsp);
					}
					if ((string)$this->input->post('DealPrice')=='' || (int) $this->input->post('DealPrice')==0) {
						$pesan = "Silahkan tentukan Deal Price";
						$rsp = [
							'status'    => 'error',
							'tipe'      => 'danger',
							'pesan'     => $pesan
						];
						send_json($rsp);
					}
					$mokita_apps_update_status_trade_in=[
						'AppsOrderNumber'   => $leads->sourceRefID,
						'DmsOrderNumber'    => $leads->batchID,
						'TradeInID'        => $leads->TradeInID,
						'TradeInStatus'    => $this->input->post('TradeInStatus',true),
						'TradeInNote'      => $this->input->post('TradeInNote',true),
						'DealPrice'      => $this->input->post('DealPrice',true),
					];
					// send_json($mokita_apps_update_status_trade_in);
					$this->load->library('mokita');
					$response_mokita = json_decode($this->mokita->h1_update_status_trade_in($mokita_apps_update_status_trade_in));
					// send_json($response_mokita);
					if ($response_mokita->status==1) {
						$update_trade_in =[
							'TradeInStatus'   => $this->input->post('TradeInStatus'),
							'TradeInNote'     => $this->input->post('TradeInNote'),
							'DealPrice'      => $this->input->post('DealPrice',true),
						];
						$update_trade_in_leads =[
							'TradeInStatus'   => $this->input->post('TradeInStatus'),
							'TradeInNote'     => $this->input->post('TradeInNote'),
						];
						$this->db->update($tabel, $update_trade_in, [$pk => $id]);
						$this->db_crm->update('leads', $update_trade_in_leads,['leads_id'=>$leads->leads_id]);
						$pesan_ce = ". Berhasil mengirim Trade In ke CE Apps.";
					}else{
						$mokita_message = isset($response_mokita->message) ? $response_mokita->message : $response_mokita->Message;
						$pesan_ce = ". Gagal mengirim Trade In ke CE Apps. ($mokita_message)";
					}
				}
			}
		}

		if (count($details) > $tot_fu) {
			// $id =='E20/00888/22/09/PSP/0022/00140'
			// multiple insert followup based on input

			foreach($details as $dt){
				$dt_fol_up = [];
				if(isset($dt['tgl_next_fol_up'])==''){ $dt['tgl_next_fol_up']=''; } 
				if(isset($dt['keterangan_next_fu'])==''){ $dt['keterangan_next_fu']=''; } 
				if(isset($dt['kodeAlasanNotProspectNotDeal'])==''){ $dt['kodeAlasanNotProspectNotDeal']=''; } 
				if(isset($dt['alasanNotProspectNotDealLainnya'])==''){ $dt['alasanNotProspectNotDealLainnya']=''; } 

				$fol = [
					'id_prospek'                      => $id,
					'tgl_fol_up'                      => $dt['tgl_fol_up'],
					'waktu_fol_up'                    => $dt['waktu_fol_up'],
					'metode_fol_up'                   => $dt['metode_fol_up'],
					'keterangan'                      => $dt['keterangan'],
					'tgl_next_fol_up'                 => $dt['tgl_next_fol_up'] == '' ? null : $dt['tgl_next_fol_up'],
					'keterangan_next_fu'              => $dt['keterangan_next_fu'] == '' ? null : $dt['keterangan_next_fu'],
					'id_status_fu'                    => $dt['id_status_fu'],
					'id_kategori_status_komunikasi'   => $dt['id_kategori_status_komunikasi'],
					'kodeHasilStatusFollowUp'         => $dt['kodeHasilStatusFollowUp'],
					'kodeAlasanNotProspectNotDeal'    => $dt['kodeAlasanNotProspectNotDeal'] == '' ? null : $dt['kodeAlasanNotProspectNotDeal'],
					'alasanNotProspectNotDealLainnya'    => $dt['alasanNotProspectNotDealLainnya'] == '' ? null : $dt['alasanNotProspectNotDealLainnya'],
					'status_prospek'        => $dt['status_prospek'],
					'id_karyawan_dealer'    => $prospek->id_karyawan_dealer
				];


				if ($fol['kodeAlasanNotProspectNotDeal']==9 || $fol['kodeAlasanNotProspectNotDeal']==3) {
					$wajib_isi_tipe_warna=true;
				}
				
				if (isset($wajib_isi_tipe_warna)) {
					$message='';
					if ($update['id_tipe_kendaraan']=='') {
						$message.="Tipe kendaraan wajib diisi";
					}
					if ($update['id_warna']=='') {
						$message.=", dan wajib diisi.";
					}
					if ($message!='') {
						$rsp = [
							'status' =>'error',
							'pesan' => $message
						];
						send_json($rsp);
					}
				}
				
				$dt_fol_up[] = $fol;

				$pesan = "Failed to save and sending follow up to CRM Apps.";
				$tipe = 'danger';
				$status = 'error';
			
				$this->db->query("SET foreign_key_checks = 0;");		
				$this->db->trans_begin();
				
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
				} else {
					// truee
					if ((string)$prospek->leads_id != NULL) {
						$dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
						$kry = $this->db->query("SELECT id_flp_md FROM ms_karyawan_dealer WHERE id_karyawan_dealer='$prospek->id_karyawan_dealer'")->row();
						$id_flp_md ='';
						if ($kry!=null) {
							$id_flp_md = $kry->id_flp_md;
						}
						
						if ($fol['kodeHasilStatusFollowUp'] == 3 || $fol['kodeHasilStatusFollowUp'] == 4) {
							$update['status_aktifitas'] = 'Completed';
						}else if($fol['kodeHasilStatusFollowUp'] == 1){
							if($fol['status_prospek'] == ''){
								$fol['status_prospek'] = 'Hot'; 
							}

							if($fol['tgl_next_fol_up']=='' || $fol['tgl_next_fol_up']=='0000-00-00'){
								$fol['tgl_next_fol_up'] = $fol['tgl_fol_up'];
							}
						}

						$send_api_4 = [
							'idProspek' => $id,
							'leads_id' => $prospek->leads_id,
							'pic' => $id_flp_md,
							'tglFollowUp' => $fol['tgl_fol_up'] . ' ' . $fol['waktu_fol_up'],
							'keteranganFollowUp' => $fol['keterangan'] == '' ? '' : $fol['keterangan'],
							'tglNextFollowUp' => $fol['tgl_next_fol_up'] == '' ? '' : $fol['tgl_next_fol_up'],
							'keteranganNextFollowUp' => $fol['keterangan_next_fu'] == '' ? '' : $fol['keterangan_next_fu'],
							'id_media_kontak_fu' => $fol['metode_fol_up'] == '' ? NULL : $fol['metode_fol_up'],
							'id_status_fu' => $fol['id_status_fu'] == '' ? NULL : $fol['id_status_fu'],
							'id_kategori_status_komunikasi' => $fol['id_kategori_status_komunikasi'] == '' ? NULL : $fol['id_kategori_status_komunikasi'],
							'assignedDealer' => $dealer->kode_dealer_md,
							'kodeHasilStatusFollowUp' => $fol['kodeHasilStatusFollowUp'] == '' ? NULL : $fol['kodeHasilStatusFollowUp'],
							'kodeAlasanNotProspectNotDeal' => $fol['kodeAlasanNotProspectNotDeal'] == '' ? NULL : $fol['kodeAlasanNotProspectNotDeal'],
							'keteranganLainnyaNotProspectNotDeal' => $fol['alasanNotProspectNotDealLainnya'] == '' ? NULL : $fol['alasanNotProspectNotDealLainnya'],
							'keteranganLainnyaNotProspectNotDeal' => $fol['alasanNotProspectNotDealLainnya'] == '' ? NULL : $fol['alasanNotProspectNotDealLainnya'],
							'statusProspek' => $fol['status_prospek'],
						];

						if (isset($send_api_4)) {
							$res_api_crm = $this->api_crm->api_4_stageId_7_8_9($send_api_4);
								
							if ($res_api_crm['status'] == 1) { // kadang tidak ada return status
								if ($res_api_crm['data'] != null) {
									if (isset($res_api_crm['data']['ontimeSLA2'])) {
										$update['ontimeSLA2'] = $res_api_crm['data']['ontimeSLA2'];
									}
								}
								$this->db->update($tabel, $update, [$pk => $id]);

								$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up); 
								$this->db->trans_commit();
								$pesan = 'Data has been saved successfully and Sending Follow Up To CRM Apps';
								if (isset($pesan_ce)) {
									$pesan.=$pesan_ce;
								}
								$tipe = 'success';
								$status = 'sukses';
							} else {
								$status = 'error';
								// $this->db->trans_rollback();
								$pesan .= 'Msg. API4 : ' . $res_api_crm['message'];
								if (isset($pesan_ce)) {
									$pesan.=$pesan_ce;
								}
							}
						} else{
							$pesan = "Failed to save and sending follow up to CRM Apps. ";
							if (isset($pesan_ce)) {
								$pesan.=$pesan_ce;
							}
							$tipe = 'danger';
							$status = 'error';
						}
					}else {
						if (isset($dt_fol_up)) {
							$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up);
						}
						$this->db->trans_commit();
						$pesan = 'Data has been saved successfully.';
						if (isset($pesan_ce)) {
							$pesan.=$pesan_ce;
						}
						$tipe = 'success';
						$_SESSION['pesan'] 	=  $pesan;
						$_SESSION['tipe'] 	= $tipe;
					}
				}
				if (isset($pesan_ce)) {
					$pesan.=$pesan_ce;
				}
				$_SESSION['pesan'] = $pesan;
				$_SESSION['tipe']  = $tipe;
				$this->db->query("SET foreign_key_checks = 1;");
			}
		}else{
			// $this->db->update($tabel, $update, [$pk => $id]);
		}
		$rsp = [
			'status' => $status,
			'link' => base_url('dealer/prospek_crm'),
			'pesan' => $pesan
		];
	
		send_json($rsp);
	}


	function getInteraksi()
	{
		$id_prospek = $this->input->post('id_prospek');
		$res_data = $this->m_prospek->getProspekInteraksi($id_prospek);
		$data = [];
		$no = 1;
		foreach ($res_data as $dt) {
			$dt = [
				$no,
				$dt->leads_id,
				$dt->interaksi_id,
				$dt->nama,
				$dt->noHP,
				'',
				$dt->email,
				$dt->customerTypeDesc,
				$dt->eventCodeInvitation,
				$dt->customerActionDate,
				$dt->deskripsiCmsSource,
				$dt->segmentMotor,
				$dt->seriesMotor,
				$dt->concat_desc_tipe_warna,
				$dt->deskripsiEvent,
				$dt->minatRidingTest == 1 ? 'Ya' : 'Tidak',
				$dt->jadwalRidingTest,
				$dt->descSourceLeads,
				$dt->descPlatformData,
				$dt->provinsi,
				$dt->kabupaten,
				$dt->kecamatan,
				$dt->kelurahan,
				$dt->assignedDealer,
				$dt->frameNoPembelianSebelumnya,
				$dt->keterangan,
				$dt->promoUnit,
				$dt->sourceRefID,
				$dt->facebook,
				$dt->instagram,
				$dt->twitter,
			];
			$data[] = $dt;
			$no++;
		}
		send_json(['status' => 1, 'data' => $data]);
	}

	public function fetchData()
	{
		$fetch_data = $this->_makeQuery();
		$data = array();
		$user = user();
		$urut = $this->input->post('start') + 1;
		$id_menu = $this->m_admin->getMenu($this->page);
		$group = $this->session->userdata("group");
		foreach ($fetch_data as $rs) {
			$params      = [
				'get'   => "id=$rs->leads_id"
			];

			if ($rs->input_from == 'ldd'){
				$set_status_followup = '';
			}else{
				$set_status_followup=$rs->id_status_fu;
			}

			$no = $rs->no_hp;
			$set_awal_no_hp = $rs->no_hp;
			if(substr($no,0,2)=='08'){
				$no = str_replace(substr($no,0,2),"628",$no);
			}elseif(substr($no,0,3)=='+62'){
				$no = str_replace(substr($no,0,3),"62",$no);
			}elseif(substr($no,0,1)=='8'){
				$no = str_replace(substr($no,0,1),"628",$no);
			}

			$link_no="https://wa.me/".$no;
			$wa = '<a href="'.$link_no.'" target="_blank"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
			<path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
		  	</svg> </a>';

			$keterangan =  $rs->id_tipe_kendaraan.''. $rs->id_warna;

			$sub_array   = array();
			$sub_array[] = $urut;
			$sub_array[] = $rs->id_prospek;
			// $sub_array[] = $rs->id_list_appointment;
			$sub_array[] = $rs->leads_id;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->nama_lengkap;
			$sub_array[] = $rs->created_at;
			$sub_array[] = $set_awal_no_hp.' '.$wa;
			// $sub_array[] = $keterangan;
			$sub_array[] = $rs->platform_data;
			$sub_array[] = $rs->sumber_prospek_name;
			$sub_array[] = $rs->nama_event;
			$sub_array[] = $rs->start_date_event.' s/d '.$rs->end_date_event;
			$sub_array[] = $set_status_followup;
			$sub_array[] = $rs->pernahTerhubung;
			$sub_array[] = $rs->hasil_fu;
			$sub_array[] = $rs->jml_fu;
			$sub_array[] = $rs->tgl_next_fol_up;
			$sub_array[] = $rs->updated_at;
			$sub_array[] = $rs->ontimeSLA2_desc;
			$set = $this->m_admin->set_tombol($id_menu, $group, "update");
			$button = "
			<a href='dealer/prospek_crm/assign_salespeople?id=$rs->id_prospek' $set class='btn btn-info btn-xs btn-primary btn-flat' style='margin-bottom:1px'>Assign Sales People</a>";
			if ($this->is_sales) {
				$button = '';
			}
			// Tombol Follow Up
			if ($rs->nama_lengkap != '') {
				$button .= "
					<a href='dealer/prospek_crm/edit?id=$rs->id_prospek' $set>
						<button $set class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button>
					</a>";
			}
			$sub_array[] = $button;
			$data[]      = $sub_array;
			$urut++;
		}
		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->_makeQuery(true),
			"data"            => $data
		);
		echo json_encode($output);
	}

	

	function _makeQuery($recordsFiltered = false)
	{
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";
		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST['order'] : '',
			'search' => $this->input->post('search')['value'],
			'order_column' => 'view',
			'id_customer_in_spk' => false,
			'select' => 'select_view'
		];


		if ($this->input->post('belum_assign_sales_people')) {

			$array=$this->input->post('belum_assign_sales_people');

			if (in_array("0", $array)) {
				$filter['belum_assign_sales_people'] = 1 ;
			} else {
				$filter['belum_assign_sales_people'] = $this->input->post('belum_assign_sales_people');
			}
		}

		if ($this->input->post('id_platform_data_multi')) {
			$filter['platformDataIn'] = $this->input->post('id_platform_data_multi');
		}
		if ($this->input->post('id_source_leads_multi')) {
			$filter['sourceLeadsIn'] = $this->input->post('id_source_leads_multi');
		}
		if ($this->input->post('kode_dealer_sebelumnya_multi')) {
			$filter['kodeDealerSebelumnyaIn'] = $this->input->post('kode_dealer_sebelumnya_multi');
		}
		if ($this->input->post('assigned_dealer_multi')) {
			$filter['assignedDealerIn'] = $this->input->post('assigned_dealer_multi');
		}
		if ($this->input->post('no_hp')) {
			$filter['no_hp'] = $this->input->post('no_hp');
		}
		if ($this->input->post('leads_id_multi')) {
			$filter['leads_idIn'] = $this->input->post('leads_id_multi');
		}
		if ($this->input->post('deskripsi_event_multi')) {
			$filter['deskripsiEventIn'] = $this->input->post('deskripsi_event_multi');
		}
		if ($this->input->post('id_status_fu_multi')) {
			$filter['id_status_fu_in'] = $this->input->post('id_status_fu_multi');
		}
		if ($this->input->post('id_tipe_kendaraan_multi')) {
			$filter['idTipeKendaraanIn'] = $this->input->post('id_tipe_kendaraan_multi');
		}
		if ($this->input->post('jumlah_fu')) {

			$filter['jumlah_fu'] = $this->input->post('jumlah_fu');
		}
		if ($this->input->post('kodeHasilStatusFollowUpMulti')) {
			$filter['kodeHasilStatusFollowUpIn'] = $this->input->post('kodeHasilStatusFollowUpMulti');
		}
		if ($this->input->post('ontimeSLA2_multi')) {
			$filter['ontimeSLA2_multi'] = $this->input->post('ontimeSLA2_multi');
		}
		if ($this->input->post('start_next_fu') && $this->input->post('end_next_fu')) {
			$filter['periode_next_fu'] = [$this->input->post('start_next_fu'), $this->input->post('end_next_fu')];
		}
		if ($this->input->post('start_periode_event') && $this->input->post('end_periode_event')) {
			$filter['periode_event'] = [$this->input->post('start_periode_event'), $this->input->post('end_periode_event')];
		}

		if ($this->input->post('filterBelumFUMD') == 'true') {
			$filter['jumlah_fu_md'] = 0;
			$filter['need_fu_md'] = 1;
			$filter['assignedDealerIsNULL'] = true;
		}

		if ($this->input->post('leadsNeedFU') == 'true') {
			$need_fu['kodeHasilStatusFollowUpNotIn'] = "3, 4";
			$need_fu['not_contacted'] = true;
			$need_fu['select'] = true;
			$cek = $this->ld_m->getCountLeadsVsFollowUp($need_fu)->result();
			$leads_need = [];
			foreach ($cek as $ck) {
				$leads_need[] = $ck->leads_id;
			}
			$filter['leads_id_in'] = $leads_need;
		}

		if ($this->input->post('belumAssignDealer') == 'true') {

			$leads_need = $this->ld_m->getLeadsBelumAssignDealer(true);
			$filter['leads_id_in'] = $leads_need;
		}

		if ($this->input->post('melewatiSLAMD') == 'true') {
			$filter['ontimeSLA1'] = 0;
			$filter['jumlah_fu_md'] = 0;
		}

		if ($this->input->post('melewatiSLADealer') == 'true') {
			$filter['ontimeSLA2'] = 0;
			$filter['jumlah_fu_d'] = 0;
		}

		if ($this->input->post('leadsMultiInteraction') == 'true') {
			$filter['interaksi_lebih_dari'] = 1;
		}
		$filter['kodeHasilStatusFollowUpNot']=4;

		$filter['show_hasil_fu_not_deal'] = $this->input->post('show_hasil_fu_not_deal');
		if ($filter['show_hasil_fu_not_deal']==1) {
			unset($filter['kodeHasilStatusFollowUpNot']);
		}

		if (isset($filter['kodeHasilStatusFollowUpIn'])) {
		if (in_array(4,$filter['kodeHasilStatusFollowUpIn'])) {
			$filter['show_hasil_fu_not_deal'] = 1;
		}
		}

		if ($this->is_sales) {
			$filter['id_karyawan_dealer'] = $_SESSION['id_karyawan_dealer'];
		}

		if ($recordsFiltered == true) {
			return $this->m_prospek->getProspek($filter)->num_rows();
		} else {
			return $this->m_prospek->getProspek($filter)->result();
		}
	}

	public function assign_salespeople()
	{
		$id 			 = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer 
					FROM ms_karyawan_dealer
					LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
					WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> '' AND ms_karyawan_dealer.id_jabatan IN('JBT-099','JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103')  AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");
		
		$filter = ['id_prospek' => $id];

		$dt_prospek = $this->m_prospek->getProspek($filter);
		if ($dt_prospek->num_rows() > 0) {
			$row = $dt_prospek->row();
			$data['row']  = $row;
			$cek_sblm = $this->m_prospek->getPembelianSebelumnyaByNoRangka($row->noFramePembelianSebelumnya)->row();
			$row->nama_sales_sebelumnya = $cek_sblm == null ? null : $cek_sblm->nama_sales_sebelumnya;
			$row->id_flp_md_sebelumnya = $cek_sblm == null ? null : $cek_sblm->id_flp_md;
			$row->finance_company = $cek_sblm == null ? null : $cek_sblm->finance_company;
		}
		$this->load->model('m_sc_master','scm');
		$data['interaksi'] = $this->m_prospek->getProspekInteraksi($id, 'LIMIT 5');
		$data['history_sales'] = $this->m_prospek->getProspekHistoryAssigned($id);
		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title;
		$data['set']		= "assign_salespeople";
		$data['mode']		= "edit";
		// send_json($data);
		$this->template($data);
	}

	function saveAssignSalespeople()
	{
		$login_id = $this->session->userdata('id_user');
		$id_prospek=$this->input->post('id');
		$prp = $this->db->get_where('tr_prospek',['id_prospek'=>$id_prospek])->row();
		$assign = [
			'id_karyawan_dealer' => $this->input->post('id_karyawan_dealer'),
			'id_flp_md'          => $this->input->post('id_flp_md'),
			'updated_at'         => waktu_full(),
			'updated_by'         => $login_id
		];
		if ((string)$prp->id_karyawan_dealer!='') {
			if ($prp->id_karyawan_dealer!=$assign['id_karyawan_dealer']) {
				$insert = [
					'id_prospek' => $prp->id_prospek,
					'id_karyawan_dealer_lama' => $prp->id_karyawan_dealer,
					'id_karyawan_dealer_baru' => $assign['id_karyawan_dealer'],
					'alasan_reassign_id' => $this->input->post('alasan_reassign_id'),
					'catatan' => $this->input->post('catatan'),
					'first' => 0,
					'last' => 1,
					'created_at' => waktu_full(),
					'created_by' => $login_id
				];
			}
		}
		$this->db->trans_begin();

		$this->db->update('tr_prospek', $assign, ['id_prospek' => $id_prospek]);
		if (isset($insert)) {
			$upd = ['last' => 0];
			$this->db->update('tr_prospek_history_reassign', $upd, ['id_prospek' => $prp->id_prospek]);
			$this->db->insert('tr_prospek_history_reassign', $insert);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong !'
			];
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] = "Berhasil melakukan Assign Salespeople";
			$_SESSION['tipe']  = "success";
			$rsp = [
				'status' => 'sukses',
				'link' => base_url($this->folder . '/' . $this->page)
			];
		}
		send_json($rsp);
	}

	function testings()
	{
		$data = $this->db->query("SELECT *,pro.id_prospek, count(prod.id_prospek) as JumlahFollowupDealer,dl.kode_dealer_md  as assignedDealer
		FROM tr_prospek pro left join tr_prospek_fol_up prod on prod.id_prospek = pro.id_prospek 
		left join ms_dealer dl on dl.id_dealer = pro.id_dealer 
		left join ms_sumber_prospek msp on msp.id_dms = pro.sumber_prospek 
		WHERE pro.input_from ='ldd' and pro.id_dealer ='103' 
		AND pro.tgl_prospek BETWEEN '2023-05-01' AND '2023-08-30'
		group by pro.id_prospek
		")->result();

			foreach ($get_data as $key => $val) {
				$fol[] = [
					'leads_id' => $val->leads_id,
					'nama' => $val->nama_konsumen,
					'noHP' => $val->no_hp,
					'platformData' => $val->platformData,
					'deskripsiEvent' => $val->deskripsiEvent,
					'sourceData' => $val->status_prospek,
					'cmsSource' => 5,
					'customerActionDate' => $val->leads_id,
					'assignedDealer' => $val->tgl_prospek,
					'tanggalAssignDealer' => $val->tgl_prospek,
					'JumlahFollowupDealer' => $val->tot,
					'lastFollowup' => $val->updated_at,
					'status_prospek' => $val->status_prospek,
					'tanggalNextFoll' => $val->tanggalNextFU,
					'statusProspek' => $val->status_prospek,
					'kodeTypeUnitDeal' => $val->id_tipe_kendaraan,
					'leads_sla' => $val->leads_id,
					'ontimeSLA2' => 1,
					'kodeAlasanNotProspectNotDeal' => $val->leads_id,
					'frameNo' => $val->noFramePembelianSebelumnya,
				];
			}

			var_dump($fol);
			die();

			$this->load->view('dealer/laporan/temp_crm_follow_up',$data);

	}


	function export_leads_set()
	{
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail_claim";

		$id_dealer = $this->m_admin->cari_dealer();
		$array['bulan_awal']    = $start_periode 		= $this->input->post('start_periode');
		$array['bulan_akhir']   = $end_periode 		    = $this->input->post('end_periode');

		$this->group_code   = $this->db->get_where('ms_user_group',['id_user_group'=>$_SESSION['group']])->row()->code;

		$where ="";
		if ($this->group_code=='sales') {
			$karyawan = $_SESSION['id_karyawan_dealer'];
			$where .=" AND pro.id_karyawan_dealer = '$karyawan'";
		}

		$data['prospek_crm'] = $this->db->query("SELECT *,pro.id_prospek, count(prod.id_prospek) as JumlahFollowupDealer,dl.kode_dealer_md  as assignedDealer,
		prod.id_karyawan_dealer as karyawan_last_fol, prod.tgl_fol_up  as last_fol, prod.tgl_next_fol_up as next_fol, pro.created_at as tgl_assign
		FROM tr_prospek pro 
		left join tr_prospek_fol_up prod on prod.id_prospek = pro.id_prospek 
		left join ms_dealer dl on dl.id_dealer = pro.id_dealer 
		left join ms_sumber_prospek msp on msp.id_dms = pro.sumber_prospek 
		WHERE pro.input_from !='sc' and pro.id_dealer ='$id_dealer' 
		AND pro.tgl_prospek BETWEEN '$start_periode' AND '$end_periode' $where
		group by pro.id_prospek
		")->result();

			// foreach ($get_data as $key => $val) {
			// 	$fol[] = [
			// 		'leads_id' => $val->leads_id,
			// 		'nama' => $val->nama_konsumen,
			// 		'noHP' => $val->no_hp,
			// 		'platformData' => $val->platformData,
			// 		'deskripsiEvent' => $val->deskripsiEvent,
			// 		'sourceData' => $val->status_prospek,
			// 		'cmsSource' => 5,
			// 		'customerActionDate' => $val->leads_id,
			// 		'assignedDealer' => $val->tgl_prospek,
			// 		'tanggalAssignDealer' => $val->tgl_prospek,
			// 		'JumlahFollowupDealer' => $val->tot,
			// 		'lastFollowup' => $val->updated_at,
			// 		'status_prospek' => $val->status_prospek,
			// 		'tanggalNextFoll' => $val->tanggalNextFU,
			// 		'statusProspek' => $val->status_prospek,
			// 		'kodeTypeUnitDeal' => $val->id_tipe_kendaraan,
			// 		'leads_sla' => $val->leads_id,
			// 		'ontimeSLA2' => 1,
			// 		'kodeAlasanNotProspectNotDeal' => $val->leads_id,
			// 		'frameNo' => $val->noFramePembelianSebelumnya,
			// 	];
			// }

			// $data['check']= $fol;s

			$this->load->view('dealer/laporan/temp_crm_follow_up',$data);

	
	}

}
