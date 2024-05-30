<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jasa_h2 extends CI_Controller
{

	var $folder =   "master";
	var $page		=		"jasa_h2";

	var $title  =   "Master Jasa";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('Number_model');
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->helper('tgl_indo');
		$this->load->model('m_h2_jasa', 'm_jasa');
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']        = $this->page;
		$data['title']      = $this->title;
		$data['set']        = "view";
		$this->template($data);
	}
	public function loadData()
	{

		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$sub_array = array();
			$button    = '';
			$btn_edit = "<a data-toggle='tooltip' href='master/jasa_h2/edit?id=$rs->id_jasa'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
			$btn_delete = "<a onclick=\"return confirm('Apakah Anda yakin ?')\" data-toggle='tooltip' href='master/jasa_h2/delete?id=$rs->id_jasa'><button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-trash'></i></button></a>";
			$button = $btn_edit . ' ' . $btn_delete;
			$active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';

			$sub_array[] = "<a href='master/jasa_h2/detail?id=$rs->id_jasa'>$rs->id_jasa</a>";
			$sub_array[] = $rs->deskripsi;
			$sub_array[] = $rs->desk_tipe;
			$sub_array[] = $rs->kategori;
			$sub_array[] = $rs->tipe_motor;
			$sub_array[] = mata_uang_rp($rs->harga);
			$sub_array[] = mata_uang_rp($rs->batas_bawah) . ' - ' . mata_uang_rp($rs->batas_atas);
			$sub_array[] = $rs->waktu . ' menit';
			$sub_array[] = $active;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	function make_query($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_jasa', 'deskripsi', 'id_type', 'kategori', 'harga', 'waktu', 'ms_h2_jasa.active', null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY ms_h2_jasa.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$searchs      = "WHERE deleted_at IS NULL and ms_h2_jasa.active ='1'";

		if ($search != '') {
			$searchs .= "AND (ms_h2_jasa.id_jasa LIKE '%$search%' 
	          OR ms_h2_jasa.created_at LIKE '%$search%'
	          OR ms_h2_jasa.deskripsi LIKE '%$search%'
	          OR ms_h2_jasa_type.deskripsi LIKE '%$search%'
	          OR ms_h2_jasa.id_type LIKE '%$search%'
	          OR ms_h2_jasa.kategori LIKE '%$search%'
	          OR ms_h2_jasa.harga LIKE '%$search%'
	          OR ms_h2_jasa.waktu LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("SELECT ms_h2_jasa.*,ms_h2_jasa_type.deskripsi AS desk_tipe
   		 FROM ms_h2_jasa
   		 JOIN ms_h2_jasa_type ON ms_h2_jasa_type.id_type=ms_h2_jasa.id_type
   		 $searchs $order $limit ");
	}
	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}

	public function loadData_dealer()
	{

		$fetch_data = $this->make_query_dealer();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$dealer = $this->db->query("select id_dealer from ms_dealer where kode_dealer_md='$rs->kode_dealer_ahm' limit 1")->row();
			$sub_array = array();
			$button    = '';
			$btn_edit = "<a data-toggle='tooltip' href='master/jasa_h2/edit_per_dealer?id=$rs->id_jasa&dealer=$dealer->id_dealer'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
			$button = $btn_edit;
			$sub_array[] = $rs->kode_dealer_ahm;
			$sub_array[] = $rs->nama_dealer;
			$sub_array[] = $rs->id_jasa;
			$sub_array[] = $rs->tipe;
			$sub_array[] = $rs->deskripsi;
			$sub_array[] = mata_uang_rp($rs->harga_dealer);
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data_dealer(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	function make_query_dealer($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('b.id_jasa', 'd.deskripsi', 'a.nama_dealer', 'c.deskripsi', null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY a.nama_dealer ASC';
		$search       = $this->input->post('search')['value'];
		$searchs      = "WHERE c.active ='1' ";
		if ($search != '') {
			$searchs .= "AND (b.id_jasa LIKE '%$search%' 
	          OR a.nama_dealer LIKE '%$search%'
	          OR a.kode_dealer_ahm LIKE '%$search%'
	          OR d.deskripsi LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("select a.kode_dealer_ahm,a.nama_dealer,b.id_jasa,b.harga_dealer,c.deskripsi,d.deskripsi as tipe from ms_dealer a join ms_h2_jasa_dealer b on a.id_dealer=b.id_dealer
				join ms_h2_jasa c on b.id_jasa=c.id_jasa join ms_h2_jasa_type d on c.id_type=d.id_type $searchs
   		$order $limit ");
	}


	function get_filtered_data_dealer()
	{
		return $this->make_query_dealer('y')->num_rows();
	}


	// upload master jasa terbaru
	public function jasa_aksi()
	{
		date_default_timezone_set('Asia/Jakarta');
		$filename = $_FILES['filename']['name'];


		$this->load->library('upload');
		$nmfile = "home" . time();
		$config['upload_path']   = './excel/';
		$config['overwrite']     = true;
		$config['allowed_types'] = 'xlsx';
		$config['file_name'] = $_FILES['filename']['name'];

		$this->upload->initialize($config);

		if ($_FILES['filename']['name']) {
			if ($this->upload->do_upload('filename')) {
				$gbr = $this->upload->data();
				include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

				$excelreader = new PHPExcel_Reader_Excel2007();
				$loadexcel = $excelreader->load('excel/' . $filename . '');
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
				unset($sheet[1]);

				$no1 = 0;
				$urutan = 0;
				$activity_capacity = "";
				$activity_promotion = "";
				$d = "";
				foreach ($sheet as $rows) {
					if ($rows['U'] == '1') {
						$activity_capacity = "BS";
					} elseif ($rows['U'] == '2') {
						$activity_capacity = "HH";
					} else {
						$activity_capacity = "LL";
					}

					if ($rows['V'] == '1') {
						$activity_promotion = "SVPS";
					} elseif ($rows['V'] == '2') {
						$activity_promotion = "SVJD";
					} elseif ($rows['V'] == '3') {
						$activity_promotion = "SVGC";
					} elseif ($rows['V'] == '4') {
						$activity_promotion = "SVPA";
					} elseif ($rows['V'] == '5') {
						$activity_promotion = "SVER";
					} elseif ($rows['V'] == '6') {
						$activity_promotion = "PE";
					} elseif ($rows['V'] == '7') {
						$activity_promotion = "RM";
					} elseif ($rows['V'] == '8') {
						$activity_promotion = "AE01";
					} elseif ($rows['V'] == '9') {
						$activity_promotion = "AE02";
					} elseif ($rows['V'] == '10') {
						$activity_promotion = "AE03";
					} elseif ($rows['V'] == '11') {
						$activity_promotion = "NP";
					}



					if ($rows['W'] == '1' or $rows['W'] == '01') {
						$d = "01";
					} elseif ($rows['W'] == '2' or $rows['W'] == '02') {
						$d = "02";
					} elseif ($rows['W'] == '3' or $rows['W'] == '03') {
						$d = "03";
					} elseif ($rows['W'] == '4' or $rows['W'] == '04') {
						$d = "04";
					} elseif ($rows['W'] == '5' or $rows['W'] == '05') {
						$d = "05";
					} elseif ($rows['W'] == '6' or $rows['W'] == '06') {
						$d = "06";
					} elseif ($rows['W'] == '7' or $rows['W'] == '07') {
						$d = "07";
					} elseif ($rows['W'] == '8' or $rows['W'] == '08') {
						$d = "08";
					} elseif ($rows['W'] == '9' or $rows['W'] == '09') {
						$d = "09";
					} elseif ($rows['W'] == '10' or $rows['W'] == '10') {
						$d = "10";
					} elseif ($rows['W'] == '11' or $rows['W'] == '11') {
						$d = "11";
					} elseif ($rows['W'] == '12' or $rows['W'] == '12') {
						$d = "12";
					} elseif ($rows['W'] == '13' or $rows['W'] == '13') {
						$d = "13";
					} elseif ($rows['W'] == '14' or $rows['W'] == '14') {
						$d = "14";
					}

					$kode_jasa_ahm = $rows['H'] . $d . $rows['X'] . $activity_capacity . $activity_promotion;

					$no1++;
					$kode = $rows['C'];
					if ($kode == "") {
						$idJasa = $this->Number_model->generateKodeJasa();
					} else {
						$idJasa = $rows['C'];
					}
					$data = array(
						"id_jasa" => $idJasa,
						"id_jasa2" => $rows['D'],
						"deskripsi" => strtoupper($rows['E']),
						"id_type" => $rows['F'],
						"kategori" => $rows['G'],
						"tipe_motor" => $rows['H'],
						"harga" => $rows['I'],
						"batas_atas" => $rows['J'],
						"batas_bawah" => $rows['K'],
						"waktu" => $rows['L'],
						"active" => $rows['M'],
						"created_at" => date('Y-m-d H:i:s'),
						"created_by" => 1,
						"updated_at" => NULL,
						"updated_by" => NULL,
						"deleted_at" => NULL,
						"deleted_by" => NULL,
						"is_favorite" => NULL,
						"activity_capacity" => $activity_capacity,
						"activity_promotion" => $activity_promotion,
						"kode_jenis_pekerjaan" => $d,
						"kode_kategori_pekerjaan" => $rows['X'],
						"kode_jasa_ahm" => $kode_jasa_ahm
					);
					$cek = $this->db->query("SELECT id_jasa from ms_h2_jasa where id_jasa='$idJasa'")->num_rows();
					if ($cek <= 0) {
						$insert =  $this->db->insert('ms_h2_jasa', $data);
					} else {
						$this->db->where("id_jasa", $kode);
						$update = $this->db->update('ms_h2_jasa', $data);
					}
				}

				if ($insert) {
				}

				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2'>";
			}
		}
	}
	// 


	public function jasa_aksi_dealer()
	{
		date_default_timezone_set('Asia/Jakarta');
		$filename = $_FILES['filename']['name'];


		$this->load->library('upload');
		$nmfile = "home" . time();
		$config['upload_path']   = './excel/';
		$config['overwrite']     = true;
		$config['allowed_types'] = 'xlsx';
		$config['file_name'] = $_FILES['filename']['name'];

		$this->upload->initialize($config);

		if ($_FILES['filename']['name']) {
			if ($this->upload->do_upload('filename')) {
				$gbr = $this->upload->data();
				include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

				$excelreader = new PHPExcel_Reader_Excel2007();
				$loadexcel = $excelreader->load('excel/' . $filename . '');
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
				unset($sheet[1]);

				$no1 = 0;
				$urutan = 0;

				foreach ($sheet as $rows) {
					$no1++;
					$kode = $rows['A'];
					$dealer = $rows['B'];
					$data = array(
						"id_jasa" => $kode,
						"id_dealer" => $dealer,
						"harga_dealer" => $rows['C'],
						"active" => "1",
						"created_at" => date('Y-m-d H:i:s'),
						"created_by" => 1,
						"updated_at" => NULL,
						"updated_by" => NULL,
						"deleted_at" => NULL,
						"deleted_by" => NULL,
					);
					$cek = $this->db->query("SELECT * from ms_h2_jasa_dealer where id_jasa='$kode' and id_dealer='$dealer'")->num_rows();
					if ($cek <= 0) {
						$this->db->insert('ms_h2_jasa_dealer', $data);
					} else {
						$this->db->where("id_jasa", $kode);
						$this->db->where("id_dealer", $dealer);
						$this->db->update('ms_h2_jasa_dealer', $data);
					}
				}

				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2/jasa_dealer'>";
			}
		}
	}


	// fungsi download jasa
	public function download()
	{
		$this->load->view('jasa_h2_download');
	}
	public function download_template()
	{
		$this->load->view('jasa_h2_download_template');
	}
	// 

	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "form";
		$data['mode']		= "insert";
		$this->template($data);
	}

	public function upload()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "up";
		$data['mode']		= "up";
		$this->template($data);
	}

	public function upload_dealer()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Upload Master Jasa Dealer";
		$data['set']		= "up_dealer";
		$data['mode']		= "up_dealer";
		$this->template($data);
	}

	public function jasa_dealer()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Master Jasa Dealer";
		$data['set']		= "jsd";
		$data['mode']		= "jsd";
		$this->template($data);
	}

	public function generateID()
	{
		$th     = date('Y');
		$thn     = date('y');
		// $query = $this->db
		// 	->select('id_jasa')
		// 	->from("ms_h2_jasa")
		// 	->limit(1)
		// 	->order_by('created_at', 'DESC')
		// 	->get();

		$get_data  = $this->db->query("SELECT created_at,id_jasa FROM ms_h2_jasa
			WHERE LEFT(created_at,4)='$th' 
      		ORDER BY created_at DESC LIMIT 0,1");

		if ($get_data->num_rows() > 0) {
			$row = $get_data->row();
			$id_jasa = substr($row->id_jasa, 0, 5);
			$id_jasa = sprintf("%'.05d", $id_jasa + 1);
			$id = "JS/" . $thn ."/" .$id_jasa;
			$i = 0;
            while ($i < 1) {
                $cek = $this->db->select('id_jasa')->get_where('ms_h2_jasa', ['id_jasa' => $id])->num_rows();
                if ($cek > 0) {
                    $gen_number    = substr($id_jasa, -5);
					$id_jasa = sprintf("%'.05d", $gen_number + 1);
					$id = "JS/" . $thn ."/" .$id_jasa;
                    $i = 0;
                } else {
                    $i++;
                }
            }
		} else {
			$id = "JS/" . $thn ."/00001" ;
		}
		return strtoupper($id);
	}

	public function save()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_jasa  = $this->generateID();
		// $cek_id_jasa = $this->db->get_where('ms_h2_jasa', ['id_jasa' => $id_jasa]);
		// if ($cek_id_jasa->num_rows() > 0) {
		// 	$rsp = [
		// 		'status' => 'error',
		// 		'pesan' => 'ID Jasa ' . $id_jasa . ' sudah ada !'
		// 	];
		// 	echo json_encode($rsp);
		// 	exit;
		// }

		$data 	= [
			'id_jasa' => $id_jasa,
			'id_jasa2'     => $this->input->post('id_jasa2'),
			'nama_jasa'       => $this->input->post('nama_jasa'),
			'deskripsi'   => $this->input->post('deskripsi'),
			'id_type'     => $this->input->post('id_type'),
			'tipe_motor'  => $this->input->post('tipe_motor'),
			'harga'       => $this->input->post('harga'),
			'batas_atas'  => $this->input->post('batas_atas'),
			'batas_bawah' => $this->input->post('batas_bawah'),
			'waktu'       => $this->input->post('waktu'),
			'kategori'    => $this->input->post('kategori'),
			'active'      => isset($_POST['active']) ? 1 : 0,
			'is_favorite'      => isset($_POST['is_favorite']) ? 1 : 0,
			'created_at'  => $waktu,
			'created_by'  => $login_id
		];
		foreach ($this->input->post('work_lists') as $key => $wl) {
			$ins_work_lists[] = [
				'id_jasa'       => $id_jasa,
				'kode_detail'   => $wl['kode_detail']
			];
		}

		foreach ($this->input->post('spareparts') as $key => $prt) {
			$ins_spareparts[] = [
				'id_jasa'   => $id_jasa,
				'id_part'   => $prt['id_part']
			];
		}
		// send_json([$ins_work_lists, $ins_spareparts, $data]);
		$this->db->trans_begin();
		$this->db->insert('ms_h2_jasa', $data);
		if (isset($ins_work_lists)) {
			$this->db->insert_batch('ms_h2_jasa_detail_work_list', $ins_work_lists);
		}
		if (isset($ins_spareparts)) {
			$this->db->insert_batch('ms_h2_jasa_spareparts', $ins_spareparts);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('master/jasa_h2')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function detail()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_jasa = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_h2_jasa WHERE id_jasa='$id_jasa'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "detail";
			$data['work_lists'] = $this->m_jasa->get_detail_work_lists_jasa($id_jasa);
			$data['spareparts'] = $this->m_jasa->get_spareparts_jasa($id_jasa);
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2'>";
		}
	}

	public function edit()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_jasa = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_h2_jasa WHERE id_jasa='$id_jasa'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "edit";
			$data['work_lists'] = $this->m_jasa->get_detail_work_lists_jasa($id_jasa);
			$data['spareparts'] = $this->m_jasa->get_spareparts_jasa($id_jasa);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2'>";
		}
	}

	public function edit_per_dealer()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Edit Jasa Dealer";
		$id_jasa = $this->input->get('id');
		$dealer = $this->input->get('dealer');
		$row = $this->db->query("SELECT a.*,b.deskripsi,b.tipe_motor FROM ms_h2_jasa_dealer a join ms_h2_jasa b on a.id_jasa=b.id_jasa WHERE a.id_jasa='$id_jasa' and a.id_dealer='$dealer'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "edit_dealer";
			$data['mode']		= "edit_dealer";
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2/jasa_dealer'>";
		}
	}

	public function save_edit_dealer()
	{
		$id_jasa = $this->input->post('id_jasa');
		$harga = $this->input->post('harga');
		$dealer = $this->input->post('id_dealer');

		$data = array(
			"harga_dealer" => $harga,
		);
		$this->db->where('id_jasa', $id_jasa);
		$this->db->where('id_dealer', $dealer);
		$this->db->update('ms_h2_jasa_dealer', $data);

		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2/jasa_dealer'>";
	}

	public function save_edit()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_jasa  = $this->input->post('id_jasa');

		$data 	= [
			'id_jasa' => $id_jasa,
			'deskripsi'   => $this->input->post('deskripsi'),
			'id_type'     => $this->input->post('id_type'),
			'tipe_motor'  => $this->input->post('tipe_motor'),
			'harga'       => $this->input->post('harga'),
			'waktu'       => $this->input->post('waktu'),
			'kategori'    => $this->input->post('kategori'),
			'batas_atas'  => $this->input->post('batas_atas'),
			'batas_bawah' => $this->input->post('batas_bawah'),
			'active'      => isset($_POST['active']) ? 1 : 0,
			'is_favorite' => isset($_POST['is_favorite']) ? 1 : 0,
			'updated_at'  => $waktu,
			'updated_by'  => $login_id,
			'nama_jasa'      => $this->input->post('nama_jasa'),
		];

		foreach ($this->input->post('work_lists') as $key => $wl) {
			$ins_work_lists[] = [
				'id_jasa'       => $id_jasa,
				'kode_detail'   => $wl['kode_detail']
			];
		}

		foreach ($this->input->post('spareparts') as $key => $prt) {
			$ins_spareparts[] = [
				'id_jasa'   => $id_jasa,
				'id_part'   => $prt['id_part']
			];
		}
		// send_json([$ins_work_lists, $ins_spareparts, $data]);

		$this->db->trans_begin();
		$this->db->update('ms_h2_jasa', $data, ['id_jasa' => $id_jasa]);

		$this->db->delete('ms_h2_jasa_detail_work_list', ['id_jasa' => $id_jasa, 'id_dealer' => null]);
		if (isset($ins_work_lists)) {
			$this->db->insert_batch('ms_h2_jasa_detail_work_list', $ins_work_lists);
		}

		$this->db->delete('ms_h2_jasa_spareparts', ['id_jasa' => $id_jasa, 'id_dealer' => null]);
		if (isset($ins_spareparts)) {
			$this->db->insert_batch('ms_h2_jasa_spareparts', $ins_spareparts);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('master/jasa_h2')
			];
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
		}
		echo json_encode($rsp);
	}
	public function delete()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_jasa  = $this->input->get('id');
		$this->db->delete('ms_h2_jasa', ['id_jasa' => $id_jasa]);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2'>";
	}
}
