<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_claim_waranty extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"rekap_claim_waranty";
	var $title  =   "Rekap Claim Warranty";

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
		$data['dt_result'] = $this->db->query("SELECT tr_rekap_claim_waranty.*,no_mesin,no_rangka,part_utama,tgl_pembelian,nama_dealer,kode_dealer_md FROM tr_rekap_claim_waranty 
			JOIN ms_dealer ON tr_rekap_claim_waranty.id_dealer=ms_dealer.id_dealer
			JOIN tr_lkh ON tr_lkh.id_lkh=tr_rekap_claim_waranty.no_lkh
			ORDER BY tr_lkh.tgl_lkh  DESC");
		$this->template($data);
	}


	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		//$data['dt_lokasi'] = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang WHERE ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");		
		$data['set']		= "form";
		$data['mode']		= "insert";
		$this->template($data);
	}

	function get_id_rekap_claim()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$ymd 	  = date('Y-m-d');
		$ymd2 	  = date('ymd');
		$get_data  = $this->db->query("SELECT * FROM tr_rekap_claim_waranty
			WHERE LEFT(created_at,7)='$th_bln' 
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$id_rekap_claim = substr($row->id_rekap_claim, -4);
			$new_kode   = 'RKC/' . $th_bln . '/' . sprintf("%'.04d", $id_rekap_claim + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_rekap_claim_waranty', ['id_rekap_claim' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -4);
					$new_kode = 'RKC/' . $th_bln . '/' . sprintf("%'.04d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode   = 'RKC/' . $th_bln . '/0001';
		}
		return strtoupper($new_kode);
	}

	public function save()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_rekap_claim  = $this->get_id_rekap_claim();
		$id_dealer  = $this->input->post('id_dealer');
		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		// $get_detail = $this->generateTagihan($id_dealer, $start_date, $end_date);

		// foreach ($get_detail['upd_claim'] as $rs) {
		// 	$upd_claim[] = ['id_detail'=>$rs->id_detail,'id_rekap_claim'=>$id_rekap_claim];
		// }
		$dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $this->input->post('id_dealer')])->row();

		$data 	= [
			'id_rekap_claim' => $id_rekap_claim,
			'tgl_pengajuan'           => date_ymd($this->input->post('tgl_pengajuan')),
			'no_registrasi'           => $this->input->post('no_registrasi'),
			'no_lkh'                  => $this->input->post('no_lkh'),
			'ktg_claim'               => $this->input->post('ktg_claim'),
			'sub_ktg_claim'           => $this->input->post('sub_ktg_claim'),
			'kelompok_pengajuan'      => $this->input->post('kelompok_pengajuan'),
			'id_dealer'               => $this->input->post('id_dealer'),
			'pkp_dealer'               => $dealer->pkp == 'Ya' ? 1 : 0,
			// 'no_rangka'               => $this->input->post('no_rangka'),
			// 'no_mesin'                => $this->input->post('no_mesin'),
			// 'tgl_pembelian'           => $this->input->post('tgl_pembelian'),
			'tgl_kerusakan'           => date_ymd($this->input->post('tgl_kerusakan')),
			'km_kerusakan'            => $this->input->post('km_kerusakan'),
			'alamat'                  => $this->input->post('alamat'),
			'id_kelurahan'            => $this->input->post('id_kelurahan'),
			'kode_pos'            => $this->input->post('kode_pos'),
			'no_telepon'              => $this->input->post('no_telepon'),
			'kode_area'               => $this->input->post('kode_area'),
			'tgl_perbaikan'           => date_ymd($this->input->post('tgl_perbaikan')),
			'tgl_selesai_perbaikan'   => date_ymd($this->input->post('tgl_selesai_perbaikan')),
			'km_perbaikan'            => $this->input->post('km_perbaikan'),
			'uraian_gejala_kerusakan' => $this->input->post('uraian_gejala_kerusakan'),
			// 'part_utama'              => $this->input->post('part_utama'),
			// 'kode_kerusakan'          => $this->input->post('kode_kerusakan'),
			// 'kerusakan'               => $this->input->post('kerusakan'),
			'id_symptom'            => $this->input->post('id_symptom'),
			'rank'                    => $this->input->post('rank'),
			'status'     => 'input',
			'created_at' => $waktu,
			'created_by' => $login_id
		];
		$detail = $this->input->post('details');
		foreach ($detail as $key => $val) {
			$details[] = [
				'id_rekap_claim'   => $id_rekap_claim,
				'id_part'          => $val['id_part'],
				'harga'            => $val['harga'],
				'status_part'      => $val['status_part'],
				'qty'              => $val['qty'],
				'tipe_penggantian' => $val['tipe_penggantian'],
				'ongkos'           => $val['ongkos'],
			];
		}
		// $tes = ['data' => $data, 'details' => $details];
		// send_json($tes);

		$this->db->trans_begin();
		$this->db->insert('tr_rekap_claim_waranty', $data);
		if (isset($details)) {
			$this->db->insert_batch('tr_rekap_claim_waranty_detail', $details);
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
				'link' => base_url('h2/rekap_claim_waranty')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h2/rekap_claim_waranty'>";
		}
		echo json_encode($rsp);
	}

	public function approve()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_rekap_claim = $this->input->get('id');
		$filter['id_rekap_claim'] = $id_rekap_claim;
		$row = $this->m_claim->getRekapClaimWarranty($filter);
// 		send_json($row);
		if ($row->num_rows() > 0) {
// 			send_json($data);
			$row = $data['row'] = $row->row();
			$data['details'] = $this->m_claim->getRekapClaimWarrantyParts($filter)->result();
			$data['set']		= "form";
			$data['mode']		= "approve";
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}

	public function save_approve()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_rekap_claim  = $this->input->post('id_rekap_claim');
		$data 	= [
			'status'     => 'approve',
			'approved_at' => $waktu,
			'approved_by' => $login_id
		];
		$this->db->trans_begin();
		$this->db->update('tr_rekap_claim_waranty', $data, ['id_rekap_claim' => $id_rekap_claim]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('h2/rekap_claim_waranty')
			//   ];
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}

	public function reject()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_rekap_claim = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM tr_rekap_claim_waranty WHERE id_rekap_claim='$id_rekap_claim' AND status='input'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['details'] = $this->db->query("SELECT tr_rekap_claim_waranty_detail.*,nama_part FROM tr_rekap_claim_waranty_detail
				JOIN ms_part ON tr_rekap_claim_waranty_detail.id_part=ms_part.id_part
				WHERE id_rekap_claim='$id_rekap_claim'
				")->result();
			$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$row->id_dealer'")->row();
			$data['lkh'] = $this->db->query("SELECT * FROM tr_lkh WHERE id_lkh='$row->no_lkh'")->row();
			$data['kelurahan'] = $this->getKelurahan($row->id_kelurahan);
			$data['set']		= "form";
			$data['mode']		= "reject";
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}

	public function save_reject()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_rekap_claim  = $this->input->post('id_rekap_claim');
		$data 	= [
			'status'     => 'reject',
			'alasan_reject' => $this->input->post('alasan_reject'),
			'approved_at' => $waktu,
			'approved_by' => $login_id
		];
		$this->db->trans_begin();
		$this->db->update('tr_rekap_claim_waranty', $data, ['id_rekap_claim' => $id_rekap_claim]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('h2/rekap_claim_waranty')
			//   ];
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}

	public function download_excel()
	{
		$data['set'] = 'download_excel';
		$data['rekap'] = $this->db->query("SELECT tr_rekap_claim_waranty.*,nama_dealer,kode_dealer_md,lkh.no_mesin,LEFT(so.tgl_cetak_invoice,10) AS tgl_pembelian,sbr.no_rangka 
		FROM tr_rekap_claim_waranty 
			JOIN ms_dealer ON tr_rekap_claim_waranty.id_dealer=ms_dealer.id_dealer
			JOIN tr_lkh lkh ON lkh.id_lkh=tr_rekap_claim_waranty.no_lkh
			LEFT JOIN tr_sales_order so ON so.no_mesin=lkh.no_mesin
			JOIN tr_scan_barcode sbr ON sbr.no_mesin=lkh.no_mesin
			WHERE tr_rekap_claim_waranty.status='approve'
			ORDER BY tr_rekap_claim_waranty.created_at DESC");
		// send_json($data);
		$this->load->view('h2/rekap_claim_waranty', $data);
	}

	function getKelurahan($id_kelurahan)
	{
		return $this->db->query("SELECT kel.*,kecamatan,ms_kecamatan.id_kecamatan,kabupaten,ms_kabupaten.id_kabupaten,provinsi,ms_provinsi.id_provinsi
   			FROM ms_kelurahan  AS kel
   			JOIN ms_kecamatan ON kel.id_kecamatan=ms_kecamatan.id_kecamatan
   			JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
   			JOIN ms_provinsi ON ms_provinsi.id_provinsi=ms_kabupaten.id_provinsi WHERE id_kelurahan='$id_kelurahan'")->row();
	}

	public function detail()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_rekap_claim = $this->input->get('id');
		$filter['id_rekap_claim'] = $id_rekap_claim;
		$row = $this->m_claim->getRekapClaimWarranty($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['details'] = $this->m_claim->getRekapClaimWarrantyParts($filter)->result();
			$data['set']		= "form";
			$data['mode']		= "detail";
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}
	public function perbaikan()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_rekap_claim = $this->input->get('id');
		$filter['id_rekap_claim'] = $id_rekap_claim;
		$row = $this->m_claim->getRekapClaimWarranty($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['details'] = $this->m_claim->getRekapClaimWarrantyParts($filter)->result();
			$data['set']		= "form";
			$data['mode']		= "perbaikan";
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}
	public function save_perbaikan()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_rekap_claim  = $this->input->post('id_rekap_claim');
		$data 	= [
			'status'     => 'perbaikan',
			'alasan_perbaikan' => $this->input->post('alasan_perbaikan'),
			'approved_at' => $waktu,
			'approved_by' => $login_id
		];
		$this->db->trans_begin();
		$this->db->update('tr_rekap_claim_waranty', $data, ['id_rekap_claim' => $id_rekap_claim]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('h2/rekap_claim_waranty')
			//   ];
			$_SESSION['pesan'] 	= "Data has been modified successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}

	public function getSymptom()
	{
		$kode_kerusakan = $this->input->post('kode_kerusakan');
		$data = $this->db->query("SELECT * FROM ms_symptom
			WHERE id_kelompok_symptom='$kode_kerusakan'")->result();
		echo json_encode($data);
	}
}
