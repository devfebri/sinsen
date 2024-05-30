<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Hasil_survey extends CI_Controller
{

	var $tables =   "tr_hasil_survey";

	var $folder =   "dealer";

	var $page   =		"hasil_survey";

	var $pk     =   "id_hasil_survey";

	var $title  =   "Hasil Survey Finance Company";



	public function __construct()

	{

		parent::__construct();



		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');
		$this->load->model('m_dealer_hasil_survey_datatables');
		$this->db_crm = $this->load->database('db_crm', true);

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
		$data['set']		= "view_new";
		// $id_dealer = $this->m_admin->cari_dealer();
		// $data['dt_hasil_survey'] = $this->db->query("SELECT * FROM tr_spk WHERE tr_spk.id_dealer = '$id_dealer'	 AND tr_spk.jenis_beli = 'Kredit' AND status_survey='baru' AND tr_spk.status_spk<>'closed' ORDER BY updated_at DESC");
		$this->template($data);
	}


	public function fetch_data_spk_datatables()
	{
		$id_dealer = $this->m_admin->cari_dealer();

		$list = $this->m_dealer_hasil_survey_datatables->get_datatables($id_dealer);

		$data = array();
		$no = $_POST['start'];

		foreach ($list as $row) {
			$cek = $this->db->query("SELECT tgl_approval,status_approval FROM tr_hasil_survey WHERE no_spk = '$row->no_spk' AND status_spk <> 'lama'");
			if ($cek->num_rows() > 0) {
				$rt = $cek->row();

				$tgl_approval = $rt->tgl_approval;
				$status = $rt->status_approval;
				$tombol = "";
			} else {
				$tgl_approval = "";
				$status = "input";
				$tombol = "<a href='dealer/hasil_survey/approve?id=$row->no_spk'>
							<button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Approve</button>
		  				</a>

		  				<a href='dealer/hasil_survey/reject?id=$row->no_spk'>
							<button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Reject</button>
		  				</a>";
			}

			if ($status == 'approved') {
				$status2 = "<span class='label label-success'>Approved</span>";
			} elseif ($status == 'rejected') {
				$status2 = "<span class='label label-danger'>Rejected</span>";
			} else {
				$status2 = "<span class='label label-primary'>Proses</span>";
			}

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $row->no_spk;
			$rows[] = $row->nama_konsumen;
			$rows[] = $row->alamat;
			$rows[] = $row->finance_company;
			$rows[] = $row->harga_tunai;
			$rows[] = $row->tanda_jadi;
			$rows[] = $row->uang_muka;
			$rows[] = $row->tenor;
			$rows[] = $tgl_approval;
			$rows[] = $status2;
			$rows[] = $tombol;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_dealer_hasil_survey_datatables->count_all($id_dealer),
			"recordsFiltered" => $this->m_dealer_hasil_survey_datatables->count_filtered($id_dealer),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function fetchData()
	{

		$fetch_data = $this->make_query();


		$data = array();
		$no = $this->input->post('start') + 1;

		foreach ($fetch_data as $rs) {

			if ($rs->id_goods_receipt == 0) {
				$status_sl                 = '<label class="label label-info">Received</label>';
			} else {
				$status_sl                 = '<label class="label label-warning">Draft</label>';
			}

			$tombol = "                                
		<a href='dealer/konfirmasi_pu/view?id=$rs->id_penerimaan_unit_dealer'>
		  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-eye'></i> Detail</button>
		</a>";

			$sub_array   = array();
			$sub_array[] = $no;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->alamat;
			$sub_array[] = "";
			$sub_array[] = "";
			$sub_array[] = "";
			$sub_array[] = "";
			$sub_array[] = "";
			$sub_array[] = "";
			$sub_array[] = $tombol;
			$data[]      = $sub_array;
			$no++;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->make_query(true),
			"recordsTotal" => $this->make_query(true),
			"data"            => $data
		);
		echo json_encode($output);
	}


	public function make_query($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";
		$where        = "WHERE 1=1 ";

		$id_dealer    = $this->m_admin->cari_dealer();

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
				$where .= " AND  (tr_hasil_survey.no_order_survey LIKE'%{$filter['search']}%'
                            OR tr_hasil_survey.tanda_jadi LIKE'%{$filter['search']}%'
          )";
			}
		}

		if (isset($filter['order'])) {
			$order = $filter['order'];
			if ($order != '') {
				if ($filter['order_column'] == 'view') {
					$order_column = ['tr_hasil_survey.no_order_survey', 'tr_hasil_survey.tanda_jadi'];
				}
				$order_clm  = $order_column[$order['0']['column']];
				$order_by   = $order['0']['dir'];
				$order = " ORDER BY $order_clm $order_by ";
			} else {
				$order = " ORDER BY tr_hasil_survey.updated_at ASC ";
			}
		} else {
			$order = '';
		}
		$group = '';


		if ($recordsFiltered == true) {
			return $this->db->query("SELECT * FROM tr_hasil_survey INNER JOIN tr_order_survey ON tr_hasil_survey.no_order_survey = tr_order_survey.no_order_survey
		$where AND tr_order_survey.id_dealer = '$id_dealer' AND tr_hasil_survey.status_spk = 'lama' 
		$group 
		$order 
		$limit")->num_rows();
		} else {
			return $this->db->query("SELECT * FROM tr_hasil_survey INNER JOIN tr_order_survey ON tr_hasil_survey.no_order_survey = tr_order_survey.no_order_survey
		$where AND tr_order_survey.id_dealer = '$id_dealer' AND tr_hasil_survey.status_spk = 'lama' 
		$group 
		$order 
		$limit")->result();
		}
	}


	public function gc()
	{
		$data['isi']    = $this->page;

		$data['title']	= $this->title . " Group Customer";

		$data['set']		= "view_gc";

		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_hasil_survey'] = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.id_dealer = '$id_dealer' AND tr_spk_gc.jenis_beli = 'Kredit' AND status_survey='baru' AND tr_spk_gc.status<>'closed' AND tr_spk_gc.status = 'approved' ORDER BY updated_at DESC");

		$this->template($data);
	}

	public function history()
	{

		$data['isi']    = $this->page;
		$data['title']	= "History " . $this->title;
		$data['set']		= "history";
		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_hasil_survey'] = $this->db->query("SELECT * FROM tr_hasil_survey INNER JOIN tr_order_survey ON tr_hasil_survey.no_order_survey = tr_order_survey.no_order_survey
				WHERE tr_order_survey.id_dealer = '$id_dealer' AND tr_hasil_survey.status_spk = 'lama' ORDER BY tr_hasil_survey.updated_at ASC");
		$this->template($data);
	}


	public function history_serverside()
	{
		$data['isi']    = $this->page;
		$data['title']	= "History " . $this->title;
		$data['set']		= "history_serverside";
		$this->template($data);
	}



	public function history_gc()

	{

		$data['isi']    = $this->page;

		$data['title']	= "History " . $this->title . " Group Customer";

		$data['set']		= "history_gc";

		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_hasil_survey'] = $this->db->query("SELECT * FROM tr_hasil_survey_gc INNER JOIN tr_order_survey_gc ON tr_hasil_survey_gc.no_order_survey_gc = tr_order_survey_gc.no_order_survey_gc

				WHERE tr_order_survey_gc.id_dealer = '$id_dealer' AND tr_hasil_survey_gc.status_spk_gc = 'lama' ORDER BY tr_hasil_survey_gc.updated_at ASC");

		$this->template($data);
	}





	public function t_pu()
	{

		$id = $this->input->post('id_penerimaan_unit');

		$dq = "SELECT * FROM tr_penerimaan_unit_detail

						WHERE id_penerimaan_unit = '$id'";

		$data['dt_pu'] = $this->db->query($dq);

		$this->load->view('dealer/t_pu', $data);
	}





	public function add()

	{

		$data['isi']    = $this->page;

		$data['title']	= $this->title;

		$data['set']		= "insert";

		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer", "jenis_customer", "ASC");

		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");

		$data['dt_no_mesin'] = $this->m_admin->getSort("tr_scan_barcode", "no_mesin", "ASC");

		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC");

		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");

		$this->template($data);
	}

	public function approve()

	{

		$id = $this->input->get("id");

		$data['dt_hasil'] = $this->db->query("SELECT tr_spk.*,ms_tipe_kendaraan.*,ms_warna.*,fc.finance_company  FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
						INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
						inner join ms_finance_company fc on  tr_spk.id_finance_company = fc.id_finance_company  
						INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						WHERE tr_spk.no_spk = '$id'");

		$data['isi']    = $this->page;

		$data['title']	= "Input Hasil Survey";

		$data['set']	= "approve";

		$this->template($data);
	}

	public function approve_gc()

	{

		$id = $this->input->get("id");

		$data['dt_hasil'] = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$id'");

		$data['isi']    = $this->page;

		$data['title']	= "Input Hasil Survey Group Customer";

		$data['set']		= "approve_gc";

		$this->template($data);
	}

	public function reject_gc()

	{

		$id = $this->input->get("id");

		$data['dt_hasil'] = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$id'");

		$data['isi']    = $this->page;

		$data['title']	= "Input Hasil Survey Group Customer";

		$data['set']		= "reject_gc";

		$this->template($data);
	}

	public function reject()
	{

		$id = $this->input->get("id");
		$data['dt_hasil'] = $this->db->query("SELECT tr_spk.*,ms_warna.*,ms_tipe_kendaraan.*,fc.finance_company FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
						inner join ms_finance_company fc on  tr_spk.id_finance_company = fc.id_finance_company  
						INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
						INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

						WHERE tr_spk.no_spk = '$id'");

		$data['isi']    = $this->page;

		$data['title']	= "Reject Survey Leasing";

		$data['set']		= "reject";

		$this->template($data);
	}

	public function get_id_hasil_survey($finco)
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$id_dealer = $this->m_admin->cari_dealer();
		// $id_sumber='E20';
		// if ($id_dealer!=null) {
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$id_sumber = $finco . '/' . $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM tr_hasil_survey
			JOIN tr_spk ON tr_hasil_survey.no_spk=tr_spk.no_spk
			WHERE LEFT(tr_hasil_survey.created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY tr_hasil_survey.created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			$id_hasil_survey = substr($row->id_hasil_survey, -5);
			$new_kode = $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $id_hasil_survey + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_hasil_survey', ['id_hasil_survey' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -5);
					$new_kode = $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $id_hasil_survey + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . '00001';
		}
		return strtoupper($new_kode);
	}

	public function tes_id()
	{
		$ambil = $this->db->query("SELECT * FROM tr_order_survey WHERE no_spk = '19/01/19/00284-PSB' ORDER BY no_order_survey DESC LIMIT 0,1")->row();

		echo $this->get_id_hasil_survey($ambil->id_finance_company);;
	}

	public function save_approve()

	{

		// 	$id_dealer = $this->m_admin->cari_dealer();

		// $waktu    = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);

		if (date("Y-m-d") >= '2023-06-16') {
			$waktu = date("Y-m-d H:i:s");
		} else {
			$waktu     = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		}

		$login_id = $this->session->userdata('id_user');

		$tabel    = $this->tables;

		$pk       = $this->pk;

		$id       = $this->input->post($pk);

		$cek      = $this->m_admin->getByID($tabel, $pk, $id)->num_rows();

		$no_spk 								= $this->input->post('no_spk');

		$this->db->select("prospek.leads_id,spk.tgl_pengiriman,spk.no_hp");
		$this->db->join("tr_prospek prospek", "prospek.id_customer=spk.id_customer");
		$this->db->where("spk.no_spk", $no_spk);
		$this->db->where("prospek.input_from", 'sinsengo');
		$cek_spk = $this->db->get("tr_spk spk")->row();
		if ($cek_spk != null) {
			$get_leads = $this->db_crm->get_where("leads", ['leads_id' => $cek_spk->leads_id])->row();
			$array_post = [
				'AppsOrderNumber'   => $get_leads->sourceRefID,
				'DmsOrderNumber'    => $get_leads->batchID,
				'CustomerPhoneNumber' => $cek_spk->no_hp,
				'CreditStatus' => 'Kredit diterima',
				'IndentStatus' => '',
				'DeliveryStatus' => '',
				'EstimatedDeliveryDate' => $cek_spk->tgl_pengiriman,
				'EngineNumber' => '',
				'StnkStatus' => '',
				'BpkbStatus' => '',
				'VehicleNumber' => '',
			];
			// send_json($array_post);
			$this->load->library("mokita");
			$this->mokita->h1_credit_approval_indent_delivery_stnk_bpkb($array_post);
		}

		if ($cek == 0) {

			$no_spk 								= $this->input->post('no_spk');
			// cari uuid by no_spk
			$this->db->where('no_spk', $no_spk);
			$this->db->where('is_cancel', 't');
			$order_fif = $this->db->get('tr_fif_order');
			if ($order_fif->num_rows() > 0) {

				$order_uuid = $order_fif->row()->order_uuid;
				//cek no po
				// $this->db->where('order_uuid', $order_uuid);
				// $this->db->order_by('id_order_json', 'desc');
				// $detail_po = $this->db->get('tr_fif_order_json_detail');

				$detail_po = $this->db->query(
					"select a.order_uuid , a.po_no , b.order_status 
					from tr_fif_order_json_detail a join tr_fif_order_json b on a.order_uuid = b.order_uuid 
					where a.order_uuid ='$order_uuid' and b.order_status not in ('REJECTED','CANCELLED') order by a.id_order_json desc"
				);

				if ($detail_po->num_rows() > 0) {
					$po = $detail_po->row();

					if ($po->po_no == "" or $po->po_no == null) {
						// jika po kosong
						// tidak di eksekusi
						$_SESSION['pesan'] 	= "Data gagal disimpan! No PO FIF masih kosong / belum disetujui oleh FIF. <br>Silahkan hubungi tim FIF untuk diproses lebih lanjut!";
						$_SESSION['tipe'] 	= "danger";

						echo "<script>history.go(-1)</script>";
						exit;
					}
				}
			}

			$ambil = $this->db->query("SELECT * FROM tr_order_survey WHERE no_spk = '$no_spk' ORDER BY no_order_survey DESC LIMIT 0,1")->row();

			$cek_hasil = $this->db->query("SELECT no_order_survey FROM tr_hasil_survey WHERE no_order_survey = '$ambil->no_order_survey' ORDER BY no_order_survey DESC LIMIT 0,1")->num_rows();

			if ($cek_hasil == 0) {
				$data['no_order_survey'] = $ambil->no_order_survey;
				$data['id_hasil_survey'] = $this->get_id_hasil_survey($ambil->id_finance_company);
				$data['no_spk']          = $this->input->post('no_spk');

				$data['tanda_jadi']      = $this->input->post('tanda_jadi');

				$data['tenor']           = $this->input->post('tenor');

				$data['nilai_dp']        = $this->input->post('nilai_dp');

				$data['harga_motor']     = $this->input->post('harga_motor');

				$date_time_approval =  $this->input->post('tgl_approval');
				$formatted_datetime = date('Y-m-d H:i:s', strtotime($date_time_approval));
				$data['tgl_approval'] = substr($formatted_datetime, 0, 19);

				$data['status_approval'] = "approved";

				$data['status_spk']      = "lama";

				$data['created_at']      = $waktu;

				$data['created_by']      = $login_id;

				$this->m_admin->insert($tabel, $data);

				$this->m_admin->update("tr_order_survey", array('status_survey' => 'approved'), "no_order_survey", $ambil->no_order_survey);

				$data2['status_survey']    = "approved";

				$data2['tenor'] 				= $this->input->post('tenor');

				$data2['dp_stor'] 			= $this->input->post('nilai_dp');

				$data2['harga_tunai']		= $this->input->post('harga_motor');



				$this->m_admin->update("tr_spk", $data2, "no_spk", $no_spk);
				$ins_manage = [
					'no_spk'          => $no_spk,
					'created_at'      => $waktu,
					'kategori'        => 'Reminder change cash/kredit',
					'status'          => 'Not Started',
					'detail_activity' => "Follow UP – Aplikasi kredit telah disetujui",
					'id_dealer'       => dealer()->id_dealer,
					'created_by'      => $login_id
				];
				$this->db->insert('tr_manage_activity_after_dealing', $ins_manage);

				$psn_tambahan = "";
				// cari uuid by no_spk
				$this->db->where('no_spk', $no_spk);
				$order_fif = $this->db->get('tr_fif_order');
				if ($order_fif->num_rows() > 0) {

					$order_uuid = $order_fif->row()->order_uuid;
					// cari nospk by order_uuid
					$id_dealer = get_data('tr_spk', 'no_spk', $no_spk, 'id_dealer');

					// cek order survey 
					$this->db->where('no_spk', $no_spk);
					$this->db->order_by('id_hasil_survey', 'desc');
					$hasil_survey = $this->db->get('tr_hasil_survey');
					if ($hasil_survey->num_rows() > 0) {
						$id_hasil_survey = $hasil_survey->row()->id_hasil_survey;


						//cek no po
						$this->db->where('order_uuid', $order_uuid);
						$this->db->order_by('id_order_json', 'desc');
						$detail_po = $this->db->get('tr_fif_order_json_detail');
						if ($detail_po->num_rows() > 0) {
							$po = $detail_po->row();

							//cek entry po leasing
							$this->db->where('id_hasil_survey', $id_hasil_survey);
							$this->db->where('no_spk', $no_spk);
							$entry_po = $this->db->get('tr_entry_po_leasing');
							if ($po->po_no == "" or $po->po_no == null) {
								// jika po kosong
								// tidak di eksekusi
								$psn_tambahan = ", Silahkan input PO Leasing, karna po saat ini PO masih kosong dari FIF";
							} else {
								$dt_entry_po = array(
									'id_hasil_survey' => $id_hasil_survey,
									'no_spk' => $no_spk,
									'po_dari_finco' => $po->po_no,
									'tgl_pembuatan_po' => date('Y-m-d'),
									'tgl_pengiriman_po' => date('Y-m-d'),
									'id_dealer' => $id_dealer,
									'created_at' => get_waktu(),
									'created_by' => 0
								);
								$this->db->insert('tr_entry_po_leasing', $dt_entry_po);
							}
						}
					}
				}


				$_SESSION['pesan'] 	= "Data has been saved successfully " . $psn_tambahan;

				$_SESSION['tipe'] 	= "success";

				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/hasil_survey'>";
			} else {
				$_SESSION['pesan'] 	= "Data sudah pernah disimpan, Silahkan refresh kembali!";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
		} else {

			$_SESSION['pesan'] 	= "Duplicate entry for primary key";

			$_SESSION['tipe'] 	= "danger";

			echo "<script>history.go(-1)</script>";
		}
	}

	public function save_approve_gc()

	{

		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= "tr_hasil_survey_gc";

		$pk					= "id_hasil_survey_gc";

		$id  				= $this->input->post($pk);

		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();

		if ($cek == 0) {

			$no_spk_gc 								= $this->input->post('no_spk_gc');

			$ambil = $this->db->query("SELECT * FROM tr_order_survey_gc WHERE no_spk_gc = '$no_spk_gc' ORDER BY no_order_survey_gc DESC LIMIT 0,1")->row();

			$data['id_hasil_survey_gc'] = $this->get_id_hasil_survey($ambil->id_finance_company);

			$data['no_order_survey_gc'] = $ambil->no_order_survey_gc;

			$data['no_spk_gc']          = $this->input->post('no_spk_gc');

			// $data['tanda_jadi'] 		= $this->input->post('tanda_jadi');	

			// $data['tenor'] 					= $this->input->post('tenor');	

			// $data['nilai_dp'] 			= $this->input->post('nilai_dp');	

			// $data['harga_motor']		= $this->input->post('harga_motor');	

			$data['tgl_approval'] 	= $this->input->post('tgl_approval');

			$data['keterangan'] 	= $this->input->post('keterangan');

			$data['status_approval'] = "approved";

			$data['status_spk_gc']	= "lama";

			$data['created_at']			= $waktu;

			$data['created_by']			= $login_id;

			$this->m_admin->insert($tabel, $data);



			$data2['status_survey']	= "approved";





			// $data2['tenor'] 				= $this->input->post('tenor');	

			// $data2['dp_stor'] 			= $this->input->post('nilai_dp');	

			// $data2['harga_tunai']		= $this->input->post('harga_motor');	

			$ins_manage = [
				'no_spk'          => $no_spk_gc,
				'created_at'      => $waktu,
				'kategori'        => 'Reminder change cash/kredit',
				'status'          => 'Not Started',
				'detail_activity' => "Follow UP – Aplikasi kredit telah disetujui",
				'id_dealer'       => dealer()->id_dealer,
				'created_by'      => $login_id
			];
			$this->db->insert('tr_manage_activity_after_dealing', $ins_manage);

			$this->m_admin->update("tr_spk_gc", $data2, "no_spk_gc", $no_spk_gc);

			$_SESSION['pesan'] 	= "Data has been saved successfully";

			$_SESSION['tipe'] 	= "success";

			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/hasil_survey/gc'>";
		} else {

			$_SESSION['pesan'] 	= "Duplicate entry for primary key";

			$_SESSION['tipe'] 	= "danger";

			echo "<script>history.go(-1)</script>";
		}
	}

	public function save_reject_gc()

	{

		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= "tr_hasil_survey_gc";

		$pk					= "id_hasil_survey_gc";

		$id  				= $this->input->post($pk);

		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();

		if ($cek == 0) {

			$no_spk_gc 								= $this->input->post('no_spk_gc');

			$ambil = $this->db->query("SELECT * FROM tr_order_survey_gc WHERE no_spk_gc = '$no_spk_gc' ORDER BY no_order_survey_gc DESC LIMIT 0,1")->row();

			$data['no_order_survey_gc'] = $ambil->no_order_survey_gc;
			$data['id_hasil_survey_gc'] = $this->get_id_hasil_survey($ambil->id_finance_company);

			$data['no_spk_gc'] 				= $this->input->post('no_spk_gc');

			// $data['tanda_jadi'] 		= $this->input->post('tanda_jadi');	

			// $data['tenor'] 					= $this->input->post('tenor');	

			// $data['nilai_dp'] 			= $this->input->post('nilai_dp');	

			// $data['harga_motor']		= $this->input->post('harga_motor');	

			$data['tgl_approval'] 	= $this->input->post('tgl_approval');

			$data['keterangan'] 	= $this->input->post('keterangan');

			$data['status_approval'] = "rejected";

			$data['status_spk_gc']	= "lama";

			$data['created_at']			= $waktu;

			$data['created_by']			= $login_id;



			$this->m_admin->insert($tabel, $data);

			$data2['status_survey']	= "rejected";


			// $data2['tenor'] 				= $this->input->post('tenor');	

			// $data2['dp_stor'] 			= $this->input->post('nilai_dp');	

			// $data2['harga_tunai']		= $this->input->post('harga_motor');	



			$this->m_admin->update("tr_spk_gc", $data2, "no_spk_gc", $no_spk_gc);
			$ins_manage = [
				'no_spk'          => $no_spk_gc,
				'created_at'      => $waktu,
				'kategori'        => 'Reminder change cash/kredit',
				'status'          => 'Not Started',
				'detail_activity' => "Follow UP – Aplikasi kredit ditolak,mohon hubungi customer untuk menambah DP/Tenor/Mengganti Tipe Pembayaran/Batal Pembelian",
				'id_dealer'       => dealer()->id_dealer,
				'created_by'      => $login_id
			];
			$this->db->insert('tr_manage_activity_after_dealing', $ins_manage);


			$_SESSION['pesan'] 	= "Data has been saved successfully";

			$_SESSION['tipe'] 	= "success";

			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/hasil_survey/gc'>";
		} else {

			$_SESSION['pesan'] 	= "Duplicate entry for primary key";

			$_SESSION['tipe'] 	= "danger";

			echo "<script>history.go(-1)</script>";
		}
	}

	public function tes()
	{
		echo $this->get_id_hasil_survey('FC00000005');
		// echo 'ds';
	}

	public function save_reject()
	{
		if (date("Y-m-d") >= '2023-06-16') {
			$waktu = date("Y-m-d H:i:s");
		} else {
			$waktu     = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		}

		$login_id  = $this->session->userdata('id_user');

		$tabel     = $this->tables;

		$pk        = $this->pk;

		$id        = $this->input->post($pk);
		$no_spk    = $this->input->post('no_spk');
		$cek       = $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		$id_dealer = $this->m_admin->cari_dealer();

		$this->db->join("tr_prospek prospek", "prospek.id_customer=spk.id_customer");
		$this->db->where("spk.no_spk",$no_spk);
		$this->db->where("prospek.input_from","sinsengo");
		$cek_spk = $this->db->get("tr_spk spk")->row();
		// send_json($cek_spk);
		if ($cek_spk != null) {
			$get_leads = $this->db_crm->get_where("leads", ['leads_id' => $cek_spk->leads_id])->row();
			// send_json($get_leads);
			$array_post = [
				'AppsOrderNumber'   => $get_leads->sourceRefID,
				'DmsOrderNumber'    => $get_leads->batchID,
				'CustomerPhoneNumber' => $cek_spk->no_hp,
				'CreditStatus' => 'Kredit ditolak',
				'IndentStatus' => '',
				'DeliveryStatus' => '',
				'EstimatedDeliveryDate' => $cek_spk->tgl_pengiriman,
				'EngineNumber' => '',
				'StnkStatus' => '',
				'BpkbStatus' => '',
				'VehicleNumber' => '',
			];
			// send_json($array_post);
			$this->load->library("mokita");
			$this->mokita->h1_credit_approval_indent_delivery_stnk_bpkb($array_post);
		}

		if ($cek == 0) {

			$no_spk                  = $this->input->post('no_spk');
			$ambil                   = $this->db->query("SELECT * FROM tr_order_survey WHERE no_spk = '$no_spk' ORDER BY no_order_survey DESC LIMIT 1")->row();

			$cek_hasil = $this->db->query("SELECT no_order_survey FROM tr_hasil_survey WHERE no_order_survey = '$ambil->no_order_survey' ORDER BY no_order_survey DESC LIMIT 0,1")->num_rows();

			if ($cek_hasil == 0) {
				$data['no_order_survey'] = $ambil->no_order_survey;
				$id_hasil_survey         = $data['id_hasil_survey'] = $this->get_id_hasil_survey($ambil->id_finance_company);
				// $id_hasil_survey         = $data['id_hasil_survey'] = 'fffffff';
				$data['no_spk']          = $this->input->post('no_spk');
				$alasan                  = $data['keterangan'] 		= $this->input->post('alasan');
				$data['status_approval'] = "rejected";

				// testing
				$date_time_approval =  $this->input->post('tgl_approval');
				$formatted_datetime = date('Y-m-d H:i:s', strtotime($date_time_approval));
				$data['tgl_approval'] = substr($formatted_datetime, 0, 19);

				$data['created_at']      = $waktu;
				$data['created_by']      = $login_id;
				$data['status_spk']      = "lama";
				$this->m_admin->insert($tabel, $data);

				$this->m_admin->update("tr_order_survey", array('status_survey' => 'rejected'), "no_order_survey", $ambil->no_order_survey);

				$data2['status_survey']	= "rejected";

				$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['id_notif_kat' => 18])->row();
				$get_notif_grup = $this->db->get_where('ms_notifikasi_grup', ['id_notif_kat' => 18]);
				$email          = array();
				$id_dealer      = $this->m_admin->cari_dealer();

				foreach ($get_notif_grup->result() as $rd) {
					// $get_email = $this->db->query("SELECT email FROM ms_karyawan_dealer 
					// 	WHERE active=1 
					// 	AND id_user_group=(
					// 		SELECT id_user_group FROM ms_user_group 
					// 		WHERE code='$rd->code_user_group'
					// 	)
					// 	AND id_dealer=$id_dealer
					// ")->result();
					$get_email = $this->db->query("SELECT email FROM ms_karyawan_dealer 
						WHERE id_karyawan_dealer IN(
							SELECT id_karyawan_dealer FROM ms_user 
							WHERE active=1 
							AND id_user_group=(
								SELECT id_user_group FROM ms_user_group 
								WHERE code='$rd->code_user_group'
							)
						) AND id_dealer=$id_dealer
						")->result();

					foreach ($get_email as $usr) {
						$email[] = $usr->email;
					}
				}
				$notif = [
					'id_notif_kat' => $ktg_notif->id_notif_kat,
					'id_referensi' => $id_hasil_survey,
					'id_dealer' => $id_dealer,
					'judul'        => "Reject Hasil Survey",
					'pesan'        => "Aplikasi kredit a.n. $ambil->nama_konsumen di tolak karena $alasan. Mohon untuk dilakukan penyesuaian pada DP dan tenor.",
					'link'         => $ktg_notif->link . '?id=' . $id_hasil_survey,
					'status'       => 'baru',
					'created_at'   => $waktu,
					'created_by'   => $login_id
				];
				$pesan = '';
				$email_reject = $this->email_reject($email, $id_hasil_survey);
				if ($email_reject == 'sukses') {
					$pesan = ', and email has been sent';
				} else {
					$pesan = ', and email has been failed to send';
				}
				$ins_manage = [
					'no_spk'          => $no_spk,
					'created_at'      => $waktu,
					'kategori'        => 'Reminder change cash/kredit',
					'status'          => 'Not Started',
					'detail_activity' => "Follow UP – Aplikasi kredit ditolak,mohon hubungi customer untuk menambah DP/Tenor/Mengganti Tipe Pembayaran/Batal Pembelian",
					'id_dealer'       => $id_dealer,
					'created_by'      => $login_id
				];
				$this->m_admin->update("tr_spk", $data2, "no_spk", $no_spk);
				$this->db->insert('tr_notifikasi', $notif);
				$this->db->insert('tr_manage_activity_after_dealing', $ins_manage);

				$_SESSION['pesan'] 	= "Data has been saved successfully $pesan";

				$_SESSION['tipe'] 	= "success";

				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/hasil_survey'>";
			} else {
				$_SESSION['pesan'] 	= "Data sudah pernah disimpan, Silahkan refresh kembali!";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
		} else {

			$_SESSION['pesan'] 	= "Duplicate entry for primary key";

			$_SESSION['tipe'] 	= "danger";

			echo "<script>history.go(-1)</script>";
		}
	}

	public function email_reject($email_to, $id_hasil_survey)
	{
		$from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl//mail.sinarsentosaprimatama.com',
			'smtp_port' => 465,
			'smtp_user' => $from->email,
			'smtp_pass' => $from->pass,
			'mailtype'  => 'html',
			'charset'   => 'iso-8859-1'
		);
		// $config = config_email($from_email);

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		$this->email->from($from->email, 'SINARSENTOSA');
		$this->email->to($email_to);
		$this->email->subject('[SINARSENTOSA] Reject Hasil Survey');

		// $data['set']         = 'selisih';
		$data['row']		 = $this->db->query("SELECT * 
			FROM tr_hasil_survey 
			JOIN tr_spk ON tr_hasil_survey.no_spk=tr_spk.no_spk
			WHERE id_hasil_survey='$id_hasil_survey'")->row();
		$file_logo           = base_url('assets/panel/images/logo_sinsen.jpg');
		$data['logo']        = $file_logo;
		$this->email->message($this->load->view('dealer/hasil_survey_email', $data, true));

		//Send mail 
		if ($this->email->send()) {
			return 'sukses';
		} else {
			return 'gagal';
		}
	}

	public function notif_reject()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "notif_reject";
		$id_hasil_survey = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		$hasil = $this->db->query("SELECT tr_hasil_survey.*,tr_spk.*,tipe_ahm,warna,tr_hasil_survey.keterangan FROM tr_hasil_survey 
			JOIN tr_spk ON tr_spk.no_spk=tr_hasil_survey.no_spk
			JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
			WHERE id_hasil_survey='$id_hasil_survey' AND status_approval='rejected' AND id_dealer=$id_dealer");
		if ($hasil->num_rows() > 0) {
			$row = $data['row'] = $hasil->row();
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/hasil_survey'>";
		}
	}

	public function send_manage()

	{

		$waktu     = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);

		$login_id  = $this->session->userdata('id_user');

		$tabel     = $this->tables;

		$pk        = $this->pk;

		$id        = $this->input->post($pk);

		$cek       = $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		$id_dealer = $this->m_admin->cari_dealer();
		$no_spk                  = $this->input->post('no_spk');
		$notif = [
			'id_notif_kat' => $ktg_notif->id_notif_kat,
			'id_referensi' => $id_hasil_survey,
			'id_dealer' => $id_dealer,
			'judul'        => "Reject Hasil Survey",
			'pesan'        => "Aplikasi kredit a.n. $ambil->nama_konsumen di tolak karena $alasan. Mohon untuk dilakukan penyesuaian pada DP dan tenor.",
			'link'         => $ktg_notif->link . '?id=' . $id_hasil_survey,
			'status'       => 'baru',
			'created_at'   => $waktu,
			'created_by'   => $login_id
		];
		$ins_manage = [
			'no_spk'          => $no_spk,
			'created_at'      => $waktu,
			'kategori'        => 'Reminder change cash/kredit',
			'status'          => 'Not Started',
			'detail_activity' => "Follow UP – Aplikasi kredit ditolak,mohon hubungi customer untuk menambah DP/Tenor/Mengganti Tipe Pembayaran/Batal Pembelian",
			'id_dealer'       => $id_dealer,
			'created_by'      => $login_id
		];
		$this->db->insert('tr_manage_activity_after_dealing', $ins_manage);

		$_SESSION['pesan'] 	= "Data has been processed successfully";

		$_SESSION['tipe'] 	= "success";

		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/hasil_survey'>";
	}
}
