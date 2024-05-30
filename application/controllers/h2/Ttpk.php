<?php
defined('BASEPATH') or exit('No direct script access allowed');

//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
// use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Ttpk extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"ttpk";
	var $title  =   "TTPK";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_md_claim', 'm_claim');
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
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$data['folder'] 	= $this->folder;
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view";
		$this->template($data);
	}

	public function upload()
	{
		if (isset($_POST['submit'])) {

			// echo 'ss';
			$config['upload_path']      = './uploads/temp_ttpk/'; //siapkan path untuk upload file
			$config['allowed_types']    = 'xlsx|xls'; //siapkan format file
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			// echo json_encode($this->upload->display_errors());
			// exit();

			if ($this->upload->do_upload('file_ttpk')) {
				$file_name = $this->upload->file_name;
				$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
				$login_id = $this->session->userdata('id_user');

				//Cek SKPB sudah ada TTPK
				$cek_skpb = array();
				$skpb = $this->db->query("SELECT no_ttpk FROM tr_ttpk ")->result();
				foreach ($skpb as $sk) {
					$cek_skpb[] = $sk->no_ttpk;
				}
				//fetch data upload
				$file   = $this->upload->data();
				$reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
				$reader->open('uploads/temp_ttpk/' . $file['file_name']); //open file xlsx

				foreach ($reader->getSheetIterator() as $sheet) {
					$numRow = 1;

					//siapkan variabel array kosong untuk menampung variabel array data
					$save   = array();
					if ($sheet->getIndex() === 0) {
						//looping pembacaan row dalam sheet
						foreach ($sheet->getRowIterator() as $row) {
							if (in_array($row[1], $cek_skpb)) continue;
							if ($numRow > 1) {
								$data = array(
									'no_ttpk'               => $row[20],
									'kode_md'               => $row[1],
									'kode_dealer_md'               => sprintf("%'.05d", $row[3]),
									'tipe_kendaraan'       => $row[5],
									'no_rangka' => $row[6],
									'no_mesin' => $row[7],
									'payment_request' => $row[8],
									'kpb' => $row[9],
									'buy_date' => $row[10]->format('Y-m-d'),
									'service_id' => $row[11],
									'usage_km' => $row[12],
									'service_date' => $row[13]->format('Y-m-d'),
									'claim_letter' => $row[14],
									'received_date' => $row[15]->format('Y-m-d'),
									'upload_date' => $row[16]->format('Y-m-d'),
									'due_date' => $row[17]->format('Y-m-d'),
									'delay' => $row[18],
									'ttpk_date' => $row[19]->format('Y-m-d'),
									'status_description' => $row[21],
									'unpaid_reason' => $row[22],
									'dispensation' => $row[23],
									'dispensation_reason' => $row[24],
									'original_claim_letter' => $row[25],
									// 'received_file_date' => $row[26]->format('Y-m-d'),
									'received_file_date' => $row[26],
									'original_ttpk' => $row[27],
									'original_ttpk_date' => $row[28],
									'original_unpaid_reason' => $row[29],
									'upload_at'             => $waktu,
									'upload_by'             => $login_id
								);
								$upd_skpb[] = [
									'nama_file' => $row[2],
									'status' => 'terima_ttpk'
								];
								//tambahkan array $data ke $save
								array_push($save, $data);
								// array_push($save, $row);
							}

							$numRow++;
						}
					}
				}
				$reader->close();
			} else {
				$error = ($this->upload->display_errors());
				$_SESSION['pesan'] 	= "$error";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
			// send_json($save);
			$this->db->trans_begin();
			// $this->db->insert('tr_so_kpb',$data);
			if (isset($save)) {
				if (count($save) > 0) {
					$this->db->insert_batch('tr_ttpk', $save);
				}
			} else {
				$_SESSION['pesan'] 	= "Something went wrong !";
				$_SESSION['tipe'] 	= "danger";
				redirect('h2/ttpk/upload', 'refresh');
			}
			if (isset($upd_skpb)) {
				$this->db->update_batch('tr_claim_kpb_generate', $upd_skpb, 'nama_file');
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something went wrong !";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			} else {
				$this->db->trans_commit();
				$tot_upl = count($save);
				$_SESSION['pesan'] 	= "$tot_upl record berhasil diupload.";
				$_SESSION['tipe'] 	= "success";
				if (file_exists(FCPATH . "uploads/temp_ttpk/" . $file_name)) {
					unlink("uploads/temp_ttpk/" . $file_name); //Hapus Gambar
				}
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/ttpk'>";
			}
		} else {
			$data['isi']    = $this->page;
			$data['title']	= 'Upload File ' . $this->title;
			$data['set']		= "upload";
			$this->template($data);
		}
	}

	public function fetch()
	{
		$fetch_data = $this->make_query_fetch();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			// $btn_approval = "<a class='btn btn-success btn-xs btn-flat' href=\"" . base_url('h2/' . $this->page . '/approved?id=' . $rs->id_po_kpb) . "\">Approval</a>";

			// if ($rs->status == 'input') {
			// 	$status = '<label class="label label-primary">Input</label>';
			// 	// if (can_access($this->page, 'can_update'))  
			// 	// $button .= $btn_approval;
			// } elseif ($rs->status == 'approved') {
			// 	$status = '<label class="label label-success">Approved</label>';
			// } elseif ($rs->status == 'rejected') {
			// 	$status = '<label class="label label-danger">Rejected</label>';
			// }

			// $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->no_ttpk . '">' . $rs->no_ttpk . '</a>';
			$sub_array[] = $rs->no_ttpk;
			$sub_array[] = $rs->ttpk_date;
			$sub_array[] = $rs->kode_dealer_md;
			$sub_array[] = $rs->nama_dealer;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->claim_letter;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_fetch(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query_fetch($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'view',
			'search' => $this->input->post('search')['value']
		];
		if (isset($_POST['id_dealer'])) {
			$filter['id_dealer'] = $_POST['id_dealer'];
		}
		if (isset($_POST['tgl_ttpk'])) {
			$filter['tgl_ttpk'] = $_POST['tgl_ttpk'];
		}
		if (isset($_POST['status'])) {
			$filter['status'] = $_POST['status'];
		}
		if ($recordsFiltered == true) {
			return $this->m_claim->getTTPK($filter)->num_rows();
		} else {
			return $this->m_claim->getTTPK($filter)->result();
		}
	}
}
