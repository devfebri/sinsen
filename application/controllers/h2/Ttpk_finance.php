<?php
defined('BASEPATH') or exit('No direct script access allowed');

//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
// use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Ttpk_finance extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"ttpk_finance";
	var $title  =   "TTPK Finance";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2');
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
			$data['group'] 	 = $this->session->userdata("group");
			$data['folder']  = $this->folder;

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

	public function add()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Create Kwitansi TTPK (MD Ke AHM)';
		$data['set']   = "form";
		$data['mode']  = "insert";
		$this->template($data);
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

			if ($rs->status == NULL) {
				$status = '<label class="label label-primary">Upload</label>';
			} elseif ($rs->status == 'finish') {
				$status = '<label class="label label-success">Finish</label>';
			}
			// $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->no_ttpk . '">' . $rs->no_ttpk . '</a>';
			$sub_array[] = $rs->no_ttpk;
			$sub_array[] = $rs->no_surat_claim;
			$sub_array[] = $rs->tgl_ttpk;
			$sub_array[] = mata_uang_rp($rs->amount_material);
			$sub_array[] = mata_uang_rp($rs->amount_jasa);
			$sub_array[] = mata_uang_rp($rs->amount_pokok);
			$sub_array[] = mata_uang_rp($rs->ppn);
			$sub_array[] = mata_uang_rp($rs->nilai_pokok_ppn);
			$sub_array[] = mata_uang_rp($rs->nilai_pph);
			$sub_array[] = mata_uang_rp($rs->total_dibayar);
			$sub_array[] = $status;
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
			$filter['status_ttpk_finance'] = $_POST['status'];
		}
		if ($recordsFiltered == true) {
			return $this->m_claim->getTTPKFinance($filter)->num_rows();
		} else {
			return $this->m_claim->getTTPKFinance($filter)->result();
		}
	}

	function generateData($start_date = null, $end_date = null)
	{
		if ($start_date == null and $end_date == null) {
			$null = 'ya';
			$start_date = $this->input->post('start_date');
			$end_date   = $this->input->post('end_date');
		}

		$result = $this->db->query("SELECT tr_ttpk_finance.*,tr_claim_kpb_generate.no_surat_claim FROM tr_ttpk_finance
			JOIN tr_claim_kpb_generate ON tr_ttpk_finance.nama_file_skpb=tr_claim_kpb_generate.nama_file
			WHERE tgl_ttpk BETWEEN '$start_date' AND '$end_date'
			AND tr_ttpk_finance.id_kwitansi IS NULL
			")->result();
		if (isset($null)) {
			$dt_result = array();
			foreach ($result as $rs) {
				$dtl = $this->m_h2->get_ttpk_detail($rs->no_surat_claim);
				$dt_result[] = [
					'no_ttpk'           => $rs->no_ttpk,
					'no_surat_claim'   => $rs->no_surat_claim,
					'tgl_ttpk'         => $rs->tgl_ttpk,
					'amount_material'  => (int) $dtl['amount_material'],
					'amount_jasa'      => (int) $dtl['amount_jasa'],
					'amount_pokok'     => (int) $dtl['amount_pokok'],
					'ppn'              => (int) $dtl['ppn'],
					'amount_pokok_ppn' => (int) $dtl['nilai_pokok_ppn'],
					'pph'              => (int) $dtl['pph'],
					'total_bayar'      => (int) $dtl['total_dibayar']
				];
			}
			echo json_encode($dt_result);
		} else {
			return $result;
		}
	}
	function get_id_kwitansi()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$ymd 	  = date('Y-m-d');
		$ymd2 	  = date('ymd');
		$get_data  = $this->db->query("SELECT * FROM tr_ttpk_kwitansi
			WHERE LEFT(created_at,7)='$th_bln' 
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$id_kwitansi = substr($row->id_kwitansi, -4);
			$new_kode   = 'TTPK-KWT/' . $th_bln . '/' . sprintf("%'.04d", $id_kwitansi + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_ttpk_kwitansi', ['id_kwitansi' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -4);
					$new_kode = 'TTPK-KWT/' . $th_bln . '/' . sprintf("%'.04d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode   = 'TTPK-KWT/' . $th_bln . '/0001';
		}
		return strtoupper($new_kode);
	}

	public function save()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_kwitansi = $this->get_id_kwitansi();
		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');
		$data 		= [
			'id_kwitansi' => $id_kwitansi,
			'start_date' => $start_date,
			'end_date'   => $end_date,
			'status'     => 'input',
			'created_at' => $waktu,
			'created_by' => $login_id
		];

		$details = $this->generateData($start_date, $end_date);
		foreach ($details as $rs) {
			$upd_ttpk[] = ['no_ttpk' => $rs->no_ttpk, 'id_kwitansi' => $id_kwitansi];
			$upd_ttpk_finance[] = ['no_ttpk' => $rs->no_ttpk, 'id_kwitansi' => $id_kwitansi, 'status' => 'finish'];
		}
		// $tes = ['data' => $data, 'upd_ttpk' => $upd_ttpk, 'upd_ttpk_finance' => $upd_ttpk_finance];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_ttpk_kwitansi', $data);
		if (isset($upd_ttpk)) {
			$this->db->update_batch('tr_ttpk', $upd_ttpk, 'no_ttpk');
		}
		if (isset($upd_ttpk_finance)) {
			$this->db->update_batch('tr_ttpk_finance', $upd_ttpk_finance, 'no_ttpk');
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
				'link' => base_url('h2/ttpk_finance')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function upload()
	{
		if (isset($_POST['submit'])) {

			$config['upload_path']      = './uploads/temp_ttpk_finance/'; //siapkan path untuk upload file
			$config['allowed_types']    = 'xlsx|xls'; //siapkan format file
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('file_ttpk_finance')) {
				$file_name = $this->upload->file_name;

				//fetch data upload
				$file   = $this->upload->data();
				$reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
				$reader->open('uploads/temp_ttpk_finance/' . $file['file_name']); //open file xlsx

				foreach ($reader->getSheetIterator() as $sheet) {
					$numRow = 1;

					//siapkan variabel array kosong untuk menampung variabel array data
					$save   = array();
					if ($sheet->getIndex() === 0) {
						//looping pembacaan row dalam sheet
						foreach ($sheet->getRowIterator() as $row) {
							// if (in_array($row[1], $cek_skpb)) continue;
							if ($numRow > 1) {
								$data = array(
									'no_ttpk'               => $row[1],
									'nama_file_skpb'               => $row[2],
									'tipe_kendaraan'       => $row[3],
									'no_surat_claim' => $row[4],
									'tgl_kirim_file_skpb' => $row[5]->format('Y-m-d'),
									// 'tgl_kirim_file_skpb' => $row[5],
									'tgl_ttpk' => $row[6]->format('Y-m-d'),
									// 'tgl_ttpk' => $row[6],
									'kode_md' => $row[7],
									'amount_material' => $row[8],
									'amount_jasa' => $row[9],
									'amount_pokok' => $row[11],
									'ppn' => $row[12],
									'nilai_pokok_ppn' => $row[13],
									'nilai_pph' => $row[14],
									'total_dibayar' => $row[15],
									'service_id' => $row[16],
									'jml_kpb_approve' => $row[17],
									'tot_jml_kpb' => $row[18],
									'jml_kpb_ditolak' => $row[19],
									'jml_kpb_diproses_ttpk' => $row[20],
									'jml_kpb_diterima' => $row[21],
									'jml_kpb_tidak_dibayar' => $row[22],
									'status_verifikasi' => $row[23],
									'upload_at'             => waktu(),
									'upload_by'             => user()->id_user
								);
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
					$this->db->insert_batch('tr_ttpk_finance', $save);
				}
			} else {
				$_SESSION['pesan'] 	= "Something went wrong !";
				$_SESSION['tipe'] 	= "danger";
				redirect('h2/ttpk_finance', 'refresh');
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
				if (file_exists(FCPATH . "uploads/temp_ttpk_finance/" . $file_name)) {
					unlink("uploads/temp_ttpk_finance/" . $file_name); //Hapus Gambar
				}
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/ttpk_finance'>";
			}
		} else {
			$data['isi']    = $this->page;
			$data['title']	= 'Upload File ' . $this->title;
			$data['set']		= "upload";
			$this->template($data);
		}
	}
}
