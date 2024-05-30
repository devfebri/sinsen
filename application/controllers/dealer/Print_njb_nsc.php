<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Print_njb_nsc extends CI_Controller
{

	var $folder = "dealer";
	var $page   = "print_njb_nsc";
	var $title  = "Print NJB & NSC";

	public function __construct()
	{
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_master', 'm_h2');
		$this->load->model('m_h2_dealer_laporan', 'm_h2_lap');
		$this->load->model('m_h2_billing', 'm_bil');
		$this->load->model('m_h2_work_order', 'm_wo');


		//===== Load Library =====
		$this->load->library('upload');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$page = $this->page;
			if (isset($data['mode'])) {
				if ($data['mode'] == 'detail_wo') {
					$page = 'sa_form';
				}
				if ($data['mode'] == 'detail_njb') {
					$page = 'njb';
				}
				if ($data['mode'] == 'detail_nsc') {
					$page = 'nsc';
				}
			}
			$this->load->view($this->folder . "/" . $page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "index";
		$data['all_print'] = 0;

		// $data['result']    = $this->m_bil->get_njb_nsc_print()->result();
		// send_json($data);
		$this->template($data);
	}
	public function history()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "index";
		$data['all_print'] = 1;
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$button = '';
			if ($rs->id_work_order == null) {
				$ref_nsc = $rs->no_nsc;
			} else {
				$ref_nsc = $rs->id_work_order;
			}

			$njb = '<a target="_blank" style="margin-bottom:1px" href="dealer/print_njb_nsc/cetak_njb?id=' . $rs->no_njb . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> NJB</a> ';
			$nsc = '<a target="_blank" style="margin-bottom:1px" href="dealer/print_njb_nsc/cetak_nsc?id=' . $ref_nsc . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> NSC</a> ';
			$njb_nsc = '<a target="_blank" href="dealer/print_njb_nsc/cetak_gab?id=' . $rs->id_work_order . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> NJB & NSC</a>';
			if ($rs->no_nsc == null) {
				$no_nsc = '-';
			} else {
				$no_nsc = $rs->no_nsc;
			}
			if ($rs->no_njb != '-') {
				if (can_access($this->page, 'can_print')) $button  .= $njb;
			}
			if ($rs->no_nsc != null) {
				if (can_access($this->page, 'can_print')) $button  .= $nsc;
			}
			if ($rs->no_nsc != null && $rs->no_njb != '-') {
				if (can_access($this->page, 'can_print')) $button  .= $njb_nsc;
			}
			$sub_array[] = '<a href="dealer/' . $this->page . '/detail_wo?id=' . $rs->id_work_order . '">' . $rs->id_work_order . '</a>';
			$sub_array[] = '<a href="dealer/' . $this->page . '/detail_njb?id=' . $rs->no_njb . '">' . $rs->no_njb . '</a>';
			$sub_array[] = '<a href="dealer/' . $this->page . '/detail_nsc?id=' . $rs->no_nsc . '">' . $rs->no_nsc . '</a>';
			$sub_array[] = $rs->tgl_njb == '' ? $rs->tgl_nsc : $rs->tgl_njb;
			$sub_array[] = $rs->no_polisi;
			$sub_array[] = $rs->nama_customer;
			$sub_array[] = $rs->tipe_ahm;
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
			'sisa_lebih_besar'  => isset($_POST['sisa_lebih_besar']) ? $_POST['sisa_lebih_besar'] : '',
			'sisa_0'  => isset($_POST['sisa_0']) ? $_POST['sisa_0'] : '',
			'search' => $this->input->post('search')['value'],
		];
		if ($recordsFiltered == true) {
			return $this->m_bil->get_njb_nsc_print($filter)->num_rows();
		} else {
			return $this->m_bil->get_njb_nsc_print($filter)->result();
		}
	}

	public function detail_wo()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Detail Work Order';
		$data['mode']  = 'detail_wo';
		$data['set']   = "form";
		$id_work_order    = $this->input->get('id');

		$filter['id_work_order'] = $id_work_order;
		$sa_form = $this->m_wo->get_sa_form($filter);
		if ($sa_form->num_rows() > 0) {
			$row                     = $data['row_wo'] = $sa_form->row();
			$data['tipe_coming']     = explode(',', $row->tipe_coming);
			$data['pkp']     = $row->pkp;
			$data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
			// $filter['id_work_order'] = $id_work_order;
			// $data['details']         = $this->m_h2->wo_detail($filter);
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
		}
	}

	public function detail_njb()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Detail NJB';
		$data['mode']  = 'detail_njb';
		$data['set']   = "form";
		$no_njb = $this->input->get('id');

		$filter = ['no_njb' => $no_njb];
		$get_wo = $this->m_wo->get_sa_form($filter);

		if ($get_wo->num_rows() > 0) {
			$row = $data['row'] = $get_wo->row();
			$data['pkp'] = $row->pkp_njb;
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_njb_nsc'>";
		}
	}


	function get_select_wo()
	{
		$search = null;
		if (isset($_POST['searchTerm'])) {
			$search = $_POST['searchTerm'];
		}
		$mode = $this->input->post('mode');

		$wo = $this->m_h2->get_select_wo($search, $mode);
		echo json_encode($wo);
	}

	function showDataBilling()
	{
		$id_work_order = $this->input->post('id_work_order');
		$mode = $this->input->post('mode');
		$data          = $this->m_wo->get_sa_form($id_work_order);
		if ($data->num_rows() > 0) {
			$result_data            = $data->row();
			if ($mode == 'create_njb' || $mode == 'detail_njb') {
				$result_data->pekerjaan = $this->m_h2->getPekerjaanWO($id_work_order)->result();
			} elseif ($mode == 'create_nsc' || $mode == 'detail_nsc') {
				$result_data->parts = $this->m_h2->getPartsWO($id_work_order)->result();
				$result_data->parts = $this->m_h2->getPartsWO($id_work_order)->result();
			}
			$result                 = ['status' => 'sukses', 'data' => $result_data];
		} else {
			$result = ['status' => 'error', 'pesan' => 'Data tidak ditemukan !'];
		}
		echo json_encode($result);
	}
	function saveNJB()
	{
		$waktu     = waktu_full();
		$login_id  = $this->session->userdata('id_user');
		$id_work_order = $this->input->post('id_work_order');
		$upd = [
			'no_njb' => $this->get_no_njb(),
			'waktu_njb' => $waktu,
			'created_njb_at' => $waktu,
			'created_njb_by' => $login_id,
		];
		// echo json_encode($upd);
		// exit();
		$this->db->trans_begin();
		$this->db->update('tr_h2_wo_dealer', $upd, ['id_work_order' => $id_work_order]);
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
				'link' => base_url('dealer/print_njb_nsc')
			];
			$_SESSION['pesan'] 	= "Pembuatan nota jasa bengkel (NJB) berhasil";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}
	public function get_no_njb()
	{
		$th        = date('y');
		$bln       = date('m');
		$tgl       = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$get_data  = $this->db->query("SELECT no_njb FROM tr_h2_wo_dealer
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_njb_at,7)='$tgl'
			ORDER BY created_njb_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$last_number = substr($row->no_njb, -4);
			$new_kode   = 'NJB/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . sprintf("%'.04d", $last_number + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_h2_wo_dealer', ['no_njb' => $new_kode])->num_rows();
				if ($cek > 0) {
					$gen_number    = substr($new_kode, -4);
					$new_kode = 'NJB/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . sprintf("%'.04d", $gen_number + 1);
					$i = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = 'NJB/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/0001';
		}
		return strtoupper($new_kode);
	}

	public function create_nsc()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'create_nsc';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$this->template($data);
	}

	function saveNSC()
	{
		$waktu         = waktu_full();
		$login_id      = $this->session->userdata('id_user');
		$id_work_order = $this->input->post('id_work_order');

		$upd = [
			'no_nsc' => $this->get_no_nsc(),
			'waktu_nsc' => $waktu,
			'created_nsc_at' => $waktu,
			'created_nsc_by' => $login_id,
		];
		// echo json_encode($upd);
		// exit();
		$this->db->trans_begin();
		$this->db->update('tr_h2_wo_dealer', $upd, ['id_work_order' => $id_work_order]);
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
				'link' => base_url('dealer/print_njb_nsc')
			];
			$_SESSION['pesan'] 	= "Pembuatan nota suku cadang (NSC) berhasil";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}
	public function get_no_nsc()
	{
		$th        = date('y');
		$bln       = date('m');
		$tgl       = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$get_data  = $this->db->query("SELECT no_nsc FROM tr_h2_wo_dealer
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_nsc_at,7)='$tgl'
			ORDER BY created_nsc_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$last_number = substr($row->no_nsc, -4);
			$new_kode   = 'NSC/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . sprintf("%'.04d", $last_number + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_h2_wo_dealer', ['no_nsc' => $new_kode])->num_rows();
				if ($cek > 0) {
					$gen_number    = substr($new_kode, -4);
					$new_kode = 'NSC/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . sprintf("%'.04d", $gen_number + 1);
					$i = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = 'NSC/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/0001';
		}
		return strtoupper($new_kode);
	}
	public function cetak_njb()
	{
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu     = waktu_full();
		$login_id  = $this->session->userdata('id_user');
		$no_njb    = $this->input->get('id');
		// $id_dealer = $this->m_admin->cari_dealer();

		$filter = ['no_njb' => $no_njb];
		$get_wo = $this->m_wo->get_sa_form($filter);

		if ($get_wo->num_rows() > 0) {
			$row = $data['row'] = $get_wo->row();

			if($row->cetak_njb_ke==0){
				$upd = [
					'cetak_njb_ke' => $row->cetak_njb_ke + 1,
					'cetak_njb_at' => $waktu,
					'cetak_njb_by' => $login_id,
				];
			}else{
				$upd = [
					'cetak_njb_ke' => $row->cetak_njb_ke + 1
				];
			}

			$this->db->trans_begin();
			$this->db->update('tr_h2_wo_dealer', $upd, ['no_njb' => $no_njb]);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			} else {
				$this->db->trans_commit();
				$this->load->library('mpdf_l');
				$mpdf                           = $this->mpdf_l->load();
				$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
				$mpdf->charset_in               = 'UTF-8';
				$mpdf->autoLangToFont           = true;

				$data['set'] = 'cetak_njb';
				$data['row']    = $row;
				$data['detail'] = $this->m_h2_lap->detailNJB($row->id_work_order);

				// send_json($data);
				$html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
				// render the view into HTML
				$mpdf->WriteHTML($html);
				// write the HTML into the mpdf
				$output = 'cetak_njb.pdf';
				$mpdf->Output("$output", 'I');
			}
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_njb_nsc'>";
		}
	}

	public function detail_nsc()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Detail NSC';
		$data['mode']  = 'detail_nsc';
		$data['set']   = "form";
		$id = $this->input->get('id');

		$filter = ['no_nsc_or_id_wo' => $id];
		$get_nsc = $this->m_bil->getNSC($filter);

		if ($get_nsc->num_rows() > 0) {
			$nsc = $get_nsc->row();
			$filter = ['id_work_order' => $nsc->id_referensi];
			$wo = $this->m_wo->get_sa_form($filter)->row();
			$nsc->tgl_servis         = $wo->tgl_servis;
			$nsc->id_karyawan_dealer = $wo->id_karyawan_dealer;
			$nsc->nama_lengkap       = $wo->nama_lengkap;
			$nsc->kd_dealer_so       = $wo->kode_dealer_md;
			$nsc->dealer_so          = $wo->nama_dealer;
			$nsc->tipe_ahm           = $wo->tipe_ahm;
			$nsc->no_polisi          = $wo->no_polisi;
			$filter = ['no_nsc' => $nsc->no_nsc];
			$nsc->parts = $this->m_bil->getNSCParts($filter)->result();
			$data['row'] = $nsc;
			$data['pkp'] = $nsc->pkp;
			// $data['tampil_ppn'] = $nsc->tampil_ppn;
			$data['tampil_ppn'] = 0;
			// send_json($nsc);
			$this->template($data);
		} else {
			$_SESSION['pesan']   = "Data not found !";
			$_SESSION['tipe']   = "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_njb_nsc'>";
		}
	}

	public function cetak_nsc()
	{
		$tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu      = waktu_full();
		$login_id   = $this->session->userdata('id_user');
		$id = $this->input->get('id');

		$filter = ['no_nsc_or_id_wo' => $id];
		$get_wo = $this->m_bil->getNSC($filter);
		// send_json($get_wo->row());
		if ($get_wo->num_rows() > 0) {
			$row = $data['row'] = $get_wo->row();

			if($row->cetak_nsc_ke==0){
				$upd = [
					'cetak_nsc_ke' => $row->cetak_nsc_ke + 1,
					'cetak_nsc_at' => $waktu,
					'cetak_nsc_by' => $login_id,
				];
			}else{
				$upd = [
					'cetak_nsc_ke' => $row->cetak_nsc_ke + 1
				];
			}
			$this->db->update('tr_h23_nsc', $upd, ['no_nsc' => $row->no_nsc]);

			$this->load->library('mpdf_l');
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'cetak_nsc';
			$data['row']    = $row;
			$filter['no_nsc'] = $row->no_nsc;
			$filter['cetakan'] = 'new';
			$data['detail'] = $this->m_h2_lap->detailNSC($filter);
			// send_json($data);
			if ($row->id_work_order != NULL) {
				$wo = $this->m_wo->get_sa_form(['id_work_order' => $row->id_work_order]);
				if ($wo->num_rows() > 0) {
					$data['wo'] = $wo->row();
				}
			}
			if($row->id_work_order != NULL){
				$data['parts_ev'] = $this->db->select('dss.serial_number')
				->select('dss.id_part')
				->select('mp.nama_part')
				->from('tr_h23_nsc as nsc')
				->join('tr_h3_dealer_sales_order as so','so.id_work_order = nsc.id_referensi')
				->join('tr_h3_dealer_sales_order_serial_ev as dss','dss.nomor_so = so.nomor_so')
				->join('ms_part as mp','mp.id_part_int = dss.id_part_int')
				->where('dss.is_return', 0)
				->where('nsc.no_nsc', $id)
				->get()->result();
			}else{
				$data['parts_ev'] = $this->db->select('dss.serial_number')
				->select('dss.id_part')
				->select('mp.nama_part')
				->from('tr_h23_nsc as nsc')
				->join('tr_h3_dealer_sales_order_serial_ev as dss','dss.nomor_so = nsc.id_referensi')
				->join('ms_part as mp','mp.id_part_int = dss.id_part_int')
				->where('dss.is_return', 0)
				->where('nsc.no_nsc', $id)
				->get()->result();
			}
			$html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_nsc.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_njb_nsc'>";
		}
	}

	public function cetak_gab()
	{
		$tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu      = waktu_full();
		$login_id   = $this->session->userdata('id_user');
		$id_work_order = $this->input->get('id');

		$filter = ['id_work_order' => $id_work_order];
		$get_wo = $this->m_wo->get_sa_form($filter);

		if ($get_wo->num_rows() > 0) {
			$row = $data['row'] = $get_wo->row();
			// 2023-09-07 13:33
			if($row->cetak_gab_ke==0){
				$upd = [
					'cetak_gab_ke' => $row->cetak_gab_ke + 1,
					'cetak_gab_at' => $waktu,
					'cetak_gab_by' => $login_id,
				];
			}else{
				$upd = [
					'cetak_gab_ke' => $row->cetak_gab_ke + 1
				];
			}
			$this->db->update('tr_h2_wo_dealer', $upd, ['id_work_order' => $id_work_order]);

			$this->load->library('mpdf_l');
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'cetak_gabungan';
			$data['row']    = $row;
			$filter_nsc = ['id_work_order' => $row->id_work_order];
			$data['nsc'] = $this->m_h2_lap->detailNSC($filter_nsc);
			$data['row_nsc'] = $this->m_bil->getNSC($filter_nsc)->row();
			$data['njb'] = $this->m_h2_lap->detailNJB($row->id_work_order);

			$data['parts_ev'] = $this->db->select('dss.serial_number')
				->select('dss.id_part')
				->select('mp.nama_part')
				->from('tr_h23_nsc as nsc')
				->join('tr_h3_dealer_sales_order as so','so.id_work_order = nsc.id_referensi')
				->join('tr_h3_dealer_sales_order_serial_ev as dss','dss.nomor_so = so.nomor_so')
				->join('ms_part as mp','mp.id_part_int = dss.id_part_int')
				->where('dss.is_return', 0)
				->where('nsc.no_nsc', $id_work_order)
				->get()->result();

			// send_json($data);
			$html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_njb_dan_nsc.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_njb_nsc'>";
		}
	}
}
