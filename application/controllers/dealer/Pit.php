<?php
defined('BASEPATH') or exit('No direct script access allowed');

class pit extends CI_Controller
{

	var $tables = "ms_h2_pit";
	var $folder = "dealer";
	var $page   = "pit";
	var $title  = "Master PIT";

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
		$this->load->model('m_h2');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
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
		$data['set']	= "index";
		$data['pit']    = $this->m_h2->get_pit();
		$this->template($data);
	}

	public function add($for = '')
	{
		if ($for == '') {
			$data['isi']     = $this->page;
			$data['title']   = $this->title;
			$data['mode']    = 'insert';
			$data['set']     = "form";
			$this->template($data);
		} elseif ($for == 'atur_jam') {
			$id_dealer = $this->m_admin->cari_dealer();
			$set         = $this->db->get('ms_h2_setting_jadwal')->row();
			$interval    = DateInterval::createFromDateString("$set->selisih_waktu min");
			$begin       = new DateTime($set->waktu_mulai);
			date_add($begin, date_interval_create_from_date_string("-$set->selisih_waktu min"));
			$end         = new DateTime($set->waktu_selesai);
			$times       = new DatePeriod($begin, $interval, $end);

			$data['isi']     = $this->page;
			$data['title']   = $this->title;
			$data['set']     = "form_atur_jam";
			foreach ($this->_list_hari() as $key => $val) {
				$list_jam = [];
				foreach ($times as $time) {
					$jam = $time->add($interval)->format('H:i');
					$is_happy_hour = 0;
					$diskon_happy_hour = 0;
					$active = 0;
					$cek = $this->db->query("SELECT * FROM ms_h2_pit_atur_jam WHERE id_dealer=$id_dealer AND day='$key' AND LEFT(jam,5)='$jam'")->row();
					if ($cek != null) {
						$is_happy_hour = $cek->is_happy_hour;
						$diskon_happy_hour = $cek->diskon_happy_hour;
						$active = $cek->active;
					}
					$list_jam[] = [
						'jam'                 => $jam,
						'is_happy_hour'       => $is_happy_hour,
						'diskon_happy_hour'   => $diskon_happy_hour,
						'active'              => $active,
					];
				}
				$list[] = ['id' => $key, 'hari' => $val, 'list_jam' => $list_jam];
			}
			// send_json($list);
			$data['list'] = $list;
			$this->template($data);
		}
	}

	function save_atur_jam()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$login_id  = $this->session->userdata('id_user');
		$list_data = $this->input->post('list');
		foreach ($list_data as $ld) {
			foreach ($ld['list_jam'] as $lj) {
				$ins_data[] = [
					'id_dealer'            => $id_dealer,
					'day'                  => $ld['id'],
					'jam'                  => $lj['jam'],
					'diskon_happy_hour'    => $lj['diskon_happy_hour'],
					'active'               => $lj['active'],
					'is_happy_hour'        => $lj['is_happy_hour'],
					'last_updated_at'      => waktu_full(),
					'last_updated_by'      => $login_id,
				];
			}
		}
		// send_json($ins_data);
		$this->db->trans_begin();
		$this->db->delete('ms_h2_pit_atur_jam', ['id_dealer' => $id_dealer]);
		$this->db->insert_batch('ms_h2_pit_atur_jam', $ins_data);
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
				'link' => base_url('dealer/pit')
			];
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function get_id_pit()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$id_dealer = $this->m_admin->cari_dealer();
		// $id_sumber='E20';
		// if ($id_dealer!=null) {
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$id_sumber = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM ms_h2_pit
			WHERE id_dealer=$id_dealer
			AND id_pit IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			$i = 0;
			$cek_last = $this->db->query("SELECT RIGHT(id_pit,3) id_pit FROM ms_h2_pit WHERE id_dealer='$id_dealer'");
			$pit_cek = 1;
			$tot_pit = $cek_last->num_rows();
			foreach ($cek_last->result() as $pt) {
				$pit_cek += 1;
				$next_pit = $pt->id_pit + 1;
				if ($next_pit > $pit_cek) {
					$new_kode = 'P' . sprintf("%'.03d", $pit_cek - 1);
					break;
				}
			}
			if (isset($new_kode)) {
				while ($i < 1) {
					$cek = $this->db->get_where('ms_h2_pit', ['id_pit' => $new_kode, 'id_dealer' => $id_dealer])->num_rows();
					if ($cek > 0) {
						$neww     = substr($new_kode, -3);
						$new_kode = 'P' . sprintf("%'.03d", $neww + 1);
						$i        = 0;
					} else {
						$i++;
					}
				}
			} else {
				$id_pit = substr($row->id_pit, 3) + 1;
				$new_kode = 'P' . sprintf("%'.03d", $id_pit + 1);
				while ($i < 1) {
					$cek = $this->db->get_where('ms_h2_pit', ['id_pit' => $new_kode, 'id_dealer' => $id_dealer])->num_rows();
					if ($cek > 0) {
						$neww     = substr($new_kode, -3);
						$new_kode = 'P' . sprintf("%'.03d", $neww + 1);
						$i        = 0;
					} else {
						$i++;
					}
				}
			}
		} else {
			$new_kode = 'P001';
		}
		return strtoupper($new_kode);
	}

	public function save()
	{
		$waktu     = waktu_full();
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$id_pit    = $this->get_id_pit();

		$data['id_pit']     = $id_pit;
		$jenis_pit = $data['jenis_pit']  = $this->input->post('jenis_pit');
		$data['active']     = isset($_POST['active']) ? 1 : 0;
		$data['booking']    = isset($_POST['booking']) ? 1 : 0;
		$data['id_dealer']  = $id_dealer;
		$data['created_at'] = $waktu;
		$data['created_by'] = $login_id;
		if (isset($_POST['mekanik'])) {
			$mekanik = $this->input->post('mekanik');
			foreach ($mekanik as  $mk) {
				$add_mekanik[] = [
					'id_pit' => $id_pit,
					'id_dealer' => $id_dealer,
					'jenis_pit' => $jenis_pit,
					'id_karyawan_dealer' => $mk['id_karyawan_dealer']
				];
			}
		}
		// send_json($data);
		$this->db->trans_begin();
		$this->db->insert('ms_h2_pit', $data);
		if (isset($add_mekanik)) {
			$this->db->insert_batch('ms_h2_pit_mekanik', $add_mekanik);
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
				'link' => base_url('dealer/pit')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function delete()
	{
		$tabel			= $this->tables;
		$pk 			= 'id_event';
		$id 			= $this->input->get('id');
		$this->db->trans_begin();
		$this->db->delete($tabel, array($pk => $id));
		$this->db->trans_commit();
		$result = 'Success';

		if ($this->db->trans_status() === FALSE) {
			$result = 'You can not delete this data because it already used by the other tables';
			$_SESSION['tipe'] 	= "danger";
		} else {
			$result = 'Data has been deleted succesfully';
			$_SESSION['tipe'] 	= "success";
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/event_d'>";
	}

	public function edit()
	{
		$id_pit = $this->input->get('id');
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['mode']      = 'edit';
		$data['set']       = "form";
		$id_dealer = $this->m_admin->cari_dealer();
		$row    = $this->db->query("SELECT * FROM ms_h2_pit WHERE id_pit='$id_pit' AND id_dealer=$id_dealer");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pit'>";
		}
	}

	public function save_edit()
	{
		$waktu     = waktu_full();
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();

		$id_pit  = $this->input->post('id_pit');

		$jenis_pit = $data['jenis_pit']  = $this->input->post('jenis_pit');
		$data['active']     = isset($_POST['active']) ? 1 : 0;
		$data['booking']    = isset($_POST['booking']) ? 1 : 0;
		$data['id_dealer']  = $id_dealer;
		$data['updated_at'] = $waktu;
		$data['updated_by'] = $login_id;

		if (isset($_POST['mekanik'])) {
			$mekanik = $this->input->post('mekanik');
			foreach ($mekanik as  $mk) {
				$add_mekanik[] = [
					'id_pit' => $id_pit,
					'id_dealer'          => $id_dealer,
					'jenis_pit'          => $jenis_pit,
					'id_karyawan_dealer' => $mk['id_karyawan_dealer']
				];
			}
		}

		$this->db->trans_begin();
		$this->db->update('ms_h2_pit', $data, ['id_pit' => $id_pit, 'id_dealer' => $id_dealer, 'jenis_pit' => $jenis_pit]);

		$this->db->where('id_dealer', $id_dealer);
		// $this->db->where('jenis_pit', $jenis_pit);
		$this->db->delete('ms_h2_pit_mekanik', ['id_pit' => $id_pit]);
		if (isset($add_mekanik)) {
			$this->db->insert_batch('ms_h2_pit_mekanik', $add_mekanik);
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
				'link' => base_url('dealer/pit')
			];
			$_SESSION['pesan'] = "Data has been updated successfully";
			$_SESSION['tipe']  = "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function fetch_kry()
	{
		$fetch_data = $this->make_query();
		$data       = array();
		foreach ($fetch_data->result() as $rs) {
			$status = '<span class="label label-warning">Proses</span>';
			$link        = '<button data-dismiss=\'modal\' onClick=\'return pilihKaryawan(' . json_encode($rs) . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array   = array();
			$sub_array[] = $rs->id_karyawan_dealer;
			$sub_array[] = $rs->honda_id;
			$sub_array[] = $rs->nama_lengkap;
			$sub_array[] = $link;
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

		$filter = [
			'search' => $this->input->post('search')['value'],
			'limit' => $limit,
			'order' => isset($_POST['order']) ? $_POST["order"] : ''
		];
		return $this->m_h2->fetch_mekanik($filter);
	}

	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}
	public function loadDetail()
	{
		$id_pit    = $this->input->post('id_pit');
		$id_dealer = $this->m_admin->cari_dealer();

		$get_mekanik = $this->db->query("SELECT pm.id_karyawan_dealer,honda_id,nama_lengkap FROM 
					ms_h2_pit_mekanik AS pm
					JOIN ms_karyawan_dealer AS kd ON kd.id_karyawan_dealer=pm.id_karyawan_dealer
					WHERE id_pit='$id_pit' AND pm.id_dealer='$id_dealer'")->result();
		$result = ['status' => 'sukses', 'mekanik' => $get_mekanik];
		echo json_encode($result);
	}
	public function detail()
	{
		$id_pit        = $this->input->get('id');
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer = $this->m_admin->cari_dealer();
		$row    = $this->db->query("SELECT * FROM ms_h2_pit WHERE id_pit='$id_pit' AND id_dealer=$id_dealer");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pit'>";
		}
	}

	function _list_hari()
	{
		return [
			'monday'       => 'Senin',
			'tuesday'      => 'Selasa',
			'wednesday'    => 'Rabu',
			'thursday'     => 'Kamis',
			'friday'       => "Jum`at",
			'saturday'     => "Sabtu",
			'sunday'       => 'Minggu',
		];
	}
}
