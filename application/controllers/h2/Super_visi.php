<?php
defined('BASEPATH') or exit('No direct script access allowed');

//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';
require_once APPPATH . '/third_party/PHPExcel/PHPExcel.php';

//lets Use the Spout Namespaces
use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Super_visi extends CI_Controller
{

	var $tables		= "tr_h2_md_supervisi";
	var $pk    	    = "id_supervisi";
	var $folder     = "h2";
	var $page       = "super_visi";
	var $title      = "Supervisi";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_ahass_network', 'm_ahn');
		//===== Load Library =====
		$this->load->library('upload');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false' or $sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}
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
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view";
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="h2/' . $this->page . '/edit?id=' . $rs->id_supervisi . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
			$btn_hasil = '<a style="margin-top:2px; margin-right:1px;"href="h2/' . $this->page . '/hasil?id=' . $rs->id_supervisi . '" class="btn btn-success btn-xs btn-flat">Hasil</a>';
			$button = $btn_edit . $btn_hasil;
			$sub_array[] = '<a href="h2/' . $this->page . '/detail?id=' . $rs->id_supervisi . '">' . $rs->id_supervisi . '</a>';;
			$sub_array[] = $rs->agenda;
			$sub_array[] = date_dmy($rs->tgl_supervisi);
			$sub_array[] = $rs->tot_dealer;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST['order'] : '',
			'search' => $this->input->post('search')['value'],
		];
		if ($recordsFiltered == true) {
			return $this->m_ahn->getSupervisi($filter)->num_rows();
		} else {
			return $this->m_ahn->getSupervisi($filter)->result();
		}
	}



	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "form";
		$data['mode']		= "insert";
		$this->template($data);
	}

	public function detail()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$id            = $this->input->get('id');
		$filter = ['id_supervisi' => $id];
		$row           = $this->m_ahn->getSupervisi($filter);
		$data['set']   = "form";
		$data['mode']  = 'detail';
		if ($row->num_rows()) {
			$data['row'] = $row->row();
			$data['details'] = $this->m_ahn->getSupervisiDetail($filter)->result();
			$qt = $this->m_ahn->getSupervisiQuartal($filter);
			$data['tgl_quartal'] = $qt->result();
			$data['quartal'] = $qt->row()->quartal;
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url('h2/' . $this->page) . "'>";
		}
	}

	function save()
	{
		$post       = $this->input->post();
		$id_supervisi = $this->m_ahn->get_id_supervisi();

		$insert = [
			'id_supervisi'    => $id_supervisi,
			'tgl_supervisi'          => $post['tgl_supervisi'],
			'agenda'          => $post['agenda'],
			'created_at'        => waktu_full(),
			'created_by'        => user()->id_user,
		];
		foreach ($post['details'] as $pr) {
			$ins_detail[] = [
				'id_supervisi'    => $id_supervisi,
				'id_dealer'       => $pr['id_dealer'],
				'id_kabupaten'    => $pr['id_kabupaten'],
				'nama_pic_dealer' => $pr['nama_pic_dealer'],
				'se'              => $pr['se'],
				'kunjungan'       => $pr['kunjungan']
			];
		}
		foreach ($post['tgl_quartal'] as $pr) {
			$tgl_quartal[] = [
				'id_supervisi' => $id_supervisi,
				// 'quartal' => $pr['quartal'],
				'quartal' => $post['quartal'],
				'start_date' => $pr['start_date'],
				'end_date' => $pr['end_date']
			];
		}
		// $tes = ['insert' => $insert, 'ins_detail' => $ins_detail, 'tgl_quartal' => $tgl_quartal];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_h2_md_supervisi', $insert);
		$this->db->insert_batch('tr_h2_md_supervisi_detail', $ins_detail);
		$this->db->insert_batch('tr_h2_md_supervisi_tgl_quartal', $tgl_quartal);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong !'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('h2/' . $this->page)
			];
			$_SESSION['pesan']   = "Data has been saved successfully";
			$_SESSION['tipe']   = "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		send_json($rsp);
	}

	public function edit()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$id            = $this->input->get('id');
		$filter = ['id_supervisi' => $id];
		$row           = $this->m_ahn->getSupervisi($filter);
		$data['set']   = "form";
		$data['mode']  = 'edit';
		if ($row->num_rows()) {
			$data['row'] = $row->row();
			$data['details'] = $this->m_ahn->getSupervisiDetail($filter)->result();
			$qt = $this->m_ahn->getSupervisiQuartal($filter);
			$data['tgl_quartal'] = $qt->result();
			$data['quartal'] = $qt->row()->quartal;
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url('h2/' . $this->page) . "'>";
		}
	}

	function save_edit()
	{
		$post       = $this->input->post();
		$id_supervisi = $post['id_supervisi'];

		$update = [
			'tgl_supervisi'          => $post['tgl_supervisi'],
			'agenda'          => $post['agenda'],
			'updated_at'        => waktu_full(),
			'updated_by'        => user()->id_user,
		];
		foreach ($post['details'] as $pr) {
			$ins_detail[] = [
				'id_supervisi'    => $id_supervisi,
				'id_dealer'       => $pr['id_dealer'],
				'id_kabupaten'    => $pr['id_kabupaten'],
				'nama_pic_dealer' => $pr['nama_pic_dealer'],
				'se'              => $pr['se'],
				'kunjungan'       => $pr['kunjungan']
			];
		}
		foreach ($post['tgl_quartal'] as $pr) {
			$tgl_quartal[] = [
				'id_supervisi' => $id_supervisi,
				'quartal' => $pr['quartal'],
				'start_date' => $pr['start_date'],
				'end_date' => $pr['end_date']
			];
		}
		// $tes = ['update' => $update, 'ins_detail' => $ins_detail, 'tgl_quartal' => $tgl_quartal];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->update('tr_h2_md_supervisi', $update, ['id_supervisi' => $id_supervisi]);
		$this->db->delete('tr_h2_md_supervisi_detail', ['id_supervisi' => $id_supervisi]);
		$this->db->insert_batch('tr_h2_md_supervisi_detail', $ins_detail);
		$this->db->delete('tr_h2_md_supervisi_tgl_quartal', ['id_supervisi' => $id_supervisi]);
		$this->db->insert_batch('tr_h2_md_supervisi_tgl_quartal', $tgl_quartal);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong !'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('h2/' . $this->page)
			];
			$_SESSION['pesan']   = "Data has been saved successfully";
			$_SESSION['tipe']   = "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		send_json($rsp);
	}

	public function hasil()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$id            = $this->input->get('id');
		$filter = ['id_supervisi' => $id];
		$row           = $this->m_ahn->getSupervisi($filter);
		$data['set']   = "form";
		$data['mode']  = 'hasil';
		if ($row->num_rows()) {
			$data['row'] = $row->row();
			$data['details'] = $this->m_ahn->getSupervisiDetail($filter)->result();
			$qt = $this->m_ahn->getSupervisiQuartal($filter);
			$data['tgl_quartal'] = $qt->result();
			$data['quartal'] = $qt->row()->quartal;
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url('h2/' . $this->page) . "'>";
		}
	}

	function setHasil()
	{
		$post = $this->input->post();
		$filter = [
			'id_supervisi' => $post['id_supervisi'],
			'id_dealer' => $post['id_dealer']
		];
		$result = [
			'status' => 'sukses',
			'row'   => $this->m_ahn->getSupervisi($filter)->row(),
			'details' => $this->m_ahn->getSupervisiHasil($filter)->result(),
			'dokumens'  => $this->m_ahn->getSupervisiHasilDokumen($filter)->result(),
		];
		send_json($result);
	}

	function save_hasil()
	{
		$post         = $this->input->post();
		$id_supervisi = $post['id_supervisi'];
		$id_dealer    = $post['id_dealer'];
		$mode         = $post['mode'];
		$dokumens     = json_decode($post['dokumens']);
		$details      = json_decode($post['details']);
		$spv          = substr($id_supervisi, -4) . '-' . $id_dealer;
		$cek_upload = 0;
		$tot_upload = 0;
		// send_json($details);

		$ym = date('Y/m');
		$y_m = date('y-m');
		$path = "./uploads/supervisi_file/" . $ym;
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}

		$config['upload_path']   = $path;
		$config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
		$config['max_size']      = '500';
		$config['max_width']     = '3000';
		$config['max_height']    = '3000';
		$config['remove_spaces'] = TRUE;
		$config['overwrite'] = TRUE;
		// $config['encrypt_name']  = TRUE;

		$foto_temuan       = count($_FILES['foto_temuan']['name']);
		for ($i = 0; $i < $foto_temuan; $i++) {
			$_FILES['file']['name']     = $_FILES['foto_temuan']['name'][$i];
			$_FILES['file']['type']     = $_FILES['foto_temuan']['type'][$i];
			$_FILES['file']['tmp_name'] = $_FILES['foto_temuan']['tmp_name'][$i];
			$_FILES['file']['error']    = $_FILES['foto_temuan']['error'][$i];
			$_FILES['file']['size']     = $_FILES['foto_temuan']['size'][$i];

			$file_name = $y_m . '-fp_t-' . $spv . '-' . $i;
			$config['file_name'] = $file_name;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('file')) {
				// Uploaded file data
				$fileData   = $this->upload->data();
				$ins_foto_temuan[$i] = $ym . '/' . $fileData['file_name'];
			} else {
				// echo $this->upload->display_errors();
			}
		}

		$foto_perbaikan       = count($_FILES['foto_perbaikan']['name']);
		for ($i = 0; $i < $foto_perbaikan; $i++) {
			$_FILES['file']['name']     = $_FILES['foto_perbaikan']['name'][$i];
			$_FILES['file']['type']     = $_FILES['foto_perbaikan']['type'][$i];
			$_FILES['file']['tmp_name'] = $_FILES['foto_perbaikan']['tmp_name'][$i];
			$_FILES['file']['error']    = $_FILES['foto_perbaikan']['error'][$i];
			$_FILES['file']['size']     = $_FILES['foto_perbaikan']['size'][$i];
			$config['file_name'] = $y_m . '-fp_p-' . $spv . '-' . $i;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('file')) {
				// Uploaded file data
				$fileData   = $this->upload->data();
				$ins_foto_perbaikan[$i] = $ym . '/' . $fileData['file_name'];
			} else {
				// echo $this->upload->display_errors();
			}
		}

		foreach ($details as $key => $val) {
			$tot_upload++;
			$foto_temuan = NULL;
			$foto_perbaikan = NULL;
			if ($mode == 'edit') {
				$foto_temuan    = $val->foto_temuan;
				$foto_perbaikan = $val->foto_perbaikan;
			}
			if (isset($ins_foto_temuan[$key])) $foto_temuan =  $ins_foto_temuan[$key];
			if (isset($ins_foto_perbaikan[$key])) $foto_perbaikan =  $ins_foto_perbaikan[$key];
			if ($foto_perbaikan != NULL) {
				$cek_upload++;
			}
			$ins_hasil[] = [
				'id_supervisi'   => $id_supervisi,
				'id_dealer'      => $id_dealer,
				'temuan_masalah' => $val->temuan_masalah,
				'penyebab'       => $val->penyebab,
				'perbaikan'      => $val->perbaikan,
				'deadline'       => date_ymd($val->deadline),
				'pic'            => $val->pic,
				'foto_temuan'    => $foto_temuan,
				'foto_perbaikan' => $foto_perbaikan
			];
		}

		$config['upload_path']   = $path;
		$config['allowed_types'] = 'doc|pdf|docx|jpg|png|jpeg|bmp|gif';
		$config['max_size']      = '500';
		$config['max_width']     = '3000';
		$config['max_height']    = '3000';
		$config['remove_spaces'] = TRUE;
		$config['overwrite'] = TRUE;
		// $config['encrypt_name']  = TRUE;

		if (count($dokumens) > 0) {
			$file_dokumen       = count($_FILES['file_dokumen']['name']);
			for ($i = 0; $i < $file_dokumen; $i++) {
				$_FILES['file']['name']     = $_FILES['file_dokumen']['name'][$i];
				$_FILES['file']['type']     = $_FILES['file_dokumen']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['file_dokumen']['tmp_name'][$i];
				$_FILES['file']['error']    = $_FILES['file_dokumen']['error'][$i];
				$_FILES['file']['size']     = $_FILES['file_dokumen']['size'][$i];
				$config['file_name'] = $y_m . '-fl_d-' . $spv . '-' . $i;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('file')) {
					// Uploaded file data
					$fileData   = $this->upload->data();
					$ins_file_dokumen[$i] = $ym . '/' . $fileData['file_name'];
				} else {
					// echo $this->upload->display_errors();
				}
			}

			foreach ($dokumens as $key => $val) {
				if ($mode == 'edit') {
					$file_dokumen    = $val->file_dokumen;
				}
				if (isset($ins_file_dokumen[$key])) $file_dokumen =  $ins_file_dokumen[$key];
				$ins_dokumens[] = [
					'id_supervisi'       => $id_supervisi,
					'id_dealer'          => $id_dealer,
					'file_dokumen'       => $file_dokumen,
					'keterangan_dokumen' => $val->keterangan_dokumen,
				];
			}
		}
		$status_perbaikan = $tot_upload == $cek_upload ? 'ok' : 'not_ok';
		$upd_detail = ['status_perbaikan' => $status_perbaikan];

		// $tes = [
		// 	'ins_hasil' => isset($ins_hasil) ? $ins_hasil : '',
		// 	'ins_dokumens' => isset($ins_dokumens) ? $ins_dokumens : ''
		// ];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->update('tr_h2_md_supervisi_detail', $upd_detail, ['id_supervisi' => $id_supervisi, 'id_dealer' => $id_dealer]);
		if ($mode == 'edit') {
			$this->db->delete('tr_h2_md_supervisi_hasil', ['id_supervisi' => $id_supervisi, 'id_dealer' => $id_dealer]);
			$this->db->delete('tr_h2_md_supervisi_hasil_dokumen', ['id_supervisi' => $id_supervisi, 'id_dealer' => $id_dealer]);
		}
		if (isset($ins_hasil)) {
			$this->db->insert_batch('tr_h2_md_supervisi_hasil', $ins_hasil);
		}
		if (isset($ins_dokumens)) {
			$this->db->insert_batch('tr_h2_md_supervisi_hasil_dokumen', $ins_dokumens);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong !'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('h2/' . $this->page . '/hasil?id=' . $id_supervisi)
			];
			$_SESSION['pesan']   = "Data has been saved successfully";
			$_SESSION['tipe']   = "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		send_json($rsp);
	}

	function download_excel()
	{
		$excel = new PHPExcel();
		$excel->getProperties()->setCreator('My Notes Code')
			->setLastModifiedBy('My Notes Code')
			->setTitle("Data Siswa")
			->setSubject("Siswa")
			->setDescription("Laporan Semua Data Siswa")
			->setKeywords("Data Siswa");
		$field = ['no' => 'No.', 'temuan_masalah' => 'Temuan Masalah', 'penyebab' => 'Penyebab', 'perbaikan' => 'Perbaikan', 'pic' => 'PIC', 'deadline' => 'Deadline', 'foto_temuan' => 'Foto Temuan', 'foto_perbaikan' => 'Foto Perbaikan', 'status_perbaikan' => 'Status'];
		$get = $this->input->get();
		// $excel->setActiveSheetIndex(0)->setCellValue('I3', "Status");
		$filter = ['id_supervisi' => $get['id_s'], 'id_dealer' => $get['id']];
		$row = $this->m_ahn->getSupervisiDetail($filter)->row();

		//Header
		$excel->setActiveSheetIndex(0)->setCellValue('B1', 'Nama AHASS : ' . $row->nama_dealer);
		$excel->setActiveSheetIndex(0)->setCellValue('B2', 'Tgl Supervisi : ' . date_dmy($row->tgl_supervisi));

		//Header Tabel
		$start_col = 1;
		$width_h = ['no' => '7', 'temuan_masalah' => '40', 'penyebab' => '40', 'perbaikan' => '40', 'pic' => '30', 'deadline' => '15', 'foto_temuan' => '23', 'foto_perbaikan' => '23', 'status_perbaikan' => '20'];
		foreach ($field as $key => $val) {
			$excel->setActiveSheetIndex(0)->setCellValue(num_to_letters($start_col) . '3', $val);
			$excel->getActiveSheet()->getColumnDimension(num_to_letters($start_col))->setWidth($width_h[$key]);
			$excel->getActiveSheet()->getStyle(num_to_letters($start_col) . '3')->getFont()->setBold(TRUE);
			$excel->getActiveSheet()->getStyle(num_to_letters($start_col) . '3')->applyFromArray(border_row());
			$start_col++;
		}
		$details = $this->m_ahn->getSupervisiHasil($filter);
		$start_row = 4;
		foreach ($details->result() as $key => $rs) {
			$start_col = 1;
			foreach ($field as $key => $val) {
				$isFoto = false;
				if ($key == 'no') {
					$value = $start_row - 3;
				} else {
					if ($key == 'foto_temuan' || $key == 'foto_perbaikan') $isFoto = true;
					$value = $rs->$key;
				}
				if ($isFoto == false) {
					$excel->setActiveSheetIndex(0)->setCellValue(num_to_letters($start_col) . $start_row, $value);
					$excel->getActiveSheet()->getStyle(num_to_letters($start_col) . $start_row)->applyFromArray(border_row());
				} else {
					if ($value != null) {
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('My Image');
						$objDrawing->setDescription('Description');
						$objDrawing->setPath('./uploads/supervisi_file/' . $value);
						$objDrawing->setCoordinates(num_to_letters($start_col) . $start_row);
						$objDrawing->setWidth(115);
						$excel->getActiveSheet()->getRowDimension($start_row)->setRowHeight(120);
						$excel->getActiveSheet()->getStyle(num_to_letters($start_col) . $start_row)->applyFromArray(border_row());
						$objDrawing->setWorksheet($excel->getActiveSheet());
					}
				}
				$start_col++;
			}
			$start_row++;
		}
		// send_json($details);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Laporan Hasil Supervisi");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Hasil Supervisi.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	function download_file_dokumen()
	{
		$this->load->library('zip');
		$get = $this->input->get();
		$filter = ['id_supervisi' => $get['id_s'], 'id_dealer' => $get['id']];
		$doc = $this->m_ahn->getSupervisiHasilDokumen($filter)->result();
		ob_start();
		foreach ($doc as $dc) {
			$files = FCPATH . "./uploads/supervisi_file/" . $dc->file_dokumen;
			$this->zip->read_file($files);
		}
		$this->zip->download('File Dokumen Supervisi-' . waktu_full() . '.zip');
	}
}
