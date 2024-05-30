<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Claim_kpb extends CI_Controller
{

	var $table_head =   "tr_claim_kpb";
	var $pk_head    =   "id_";
	var $table_det  =   "tr_";
	var $pk_det     =   "id_";
	var $folder     =   "h2";
	var $page       =	"claim_kpb";
	var $title      =   "Claim KPB";
	var $order_column_ahass = array("kode_dealer_md", "nama_dealer", null);
	var $order_column_nosin = array("no_mesin", "no_rangka", "tipe_ahm", "warna", null);

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
		$data['set']	= "view";
		// $data['dt_result'] = $this->db->query("SELECT *,LEFT(created_at,10) as created,tr_claim_kpb.id_claim_kpb,
		// 		(SELECT status FROM tr_claim_kpb_generate_detail 
		// 		 WHERE tr_claim_kpb_generate_detail.id_claim_kpb=tr_claim_kpb.id_claim_kpb
		// 		 AND status='approved'
		// 		) as status
		// 	FROM tr_claim_kpb ORDER by created_at DESC");
		$this->template($data);
	}
	public function fetch_claim_kpb()
	{
		$fetch_data = $this->make_query_fetch_claim_kpb();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$button1 = "<a class='btn btn-warning btn-xs' href=\"" . base_url('h2/claim_kpb/editPerNosin?id=' . $rs->id_claim_kpb) . "\"><i class='fa fa-edit'></i></a>";

			if ($rs->status == NULL) {
				$status = '<label class="label label-warning">Input</label>';
				$button .= $button1;
			}
			$cek_generate = $this->db->get_where('tr_claim_kpb_generate_detail', ['id_claim_kpb' => $rs->id_claim_kpb]);
			if ($cek_generate->num_rows() > 0) {
				$gen = $cek_generate->row();
				$status = '<label class="label label-primary">SKPB</label>';
				$button = '';
				if ($gen->status == 'approved') {
					$status = '<label class="label label-success">Approved By AHM</label>';
				}else if($gen->status =='reject'){
					$status = '<label class="label label-danger">Rejected By AHM</label>';
				}
			}

			$sub_array[] = $rs->kode_dealer_md;
			$sub_array[] = $rs->nama_dealer;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->no_kpb;
			$sub_array[] = $rs->id_tipe_kendaraan.' - '.$rs->tipe_ahm;
			$sub_array[] = $rs->kpb_ke;
			$sub_array[] = $rs->tgl_beli_smh;
			$sub_array[] = $rs->km_service;
			$sub_array[] = $rs->tgl_service;
			$sub_array[] = $rs->created_at;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_fetch_claim_kpb(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query_fetch_claim_kpb($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'view_claim_kpb',
			'search' => $this->input->post('search')['value']
		];
		if (isset($_POST['id_dealer'])) {
			$filter['id_dealer'] = $_POST['id_dealer'];
		}
		if (isset($_POST['no_mesin'])) {
			$filter['no_mesin'] = $_POST['no_mesin'];
		}
		if (isset($_POST['kpb_ke'])) {
			$filter['kpb_ke'] = $_POST['kpb_ke'];
		}
		if (isset($_POST['tgl_service_awal'])) {
			$filter['tgl_service_awal'] = $_POST['tgl_service_awal'];
		}
		if (isset($_POST['tgl_service_akhir'])) {
			$filter['tgl_service_akhir'] = $_POST['tgl_service_akhir'];
		}
		if (isset($_POST['no_mesin_5'])) {
			$filter['no_mesin_5'] = $_POST['no_mesin_5'];
		}
		if (isset($_POST['status'])) {
			$filter['status'] = $_POST['status'];
		}
		if ($recordsFiltered == true) {
			return $this->m_claim->getDataClaimKPBMD($filter)->num_rows();
		} else {
			return $this->m_claim->getDataClaimKPBMD($filter)->result();
		}
	}



	public function add()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'insert';
		$data['set']   = "insert";
		$this->db->order_by('id_periode', 'DESC');
		$data['periode'] = $this->db->get('ms_periode_claim_kpb');
		$this->template($data);
	}

	public function generate_skpb()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . ' - Generate SKPB';
		$data['set']	= "generate_skpb";
		$data['no_mesin'] = $this->db->query("SELECT DISTINCT(no_mesin) FROM ms_tipe_kendaraan
			WHERE (no_mesin IS NOT NULL OR no_mesin!='')
			ORDER BY no_mesin ASC");
		//$data['dt_result'] = $this->m_admin->getAll($this->tables);			
		$this->template($data);
	}

	public function generate_skpb_reject()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "generate_skpb_reject";
		$data['surat'] = $this->db->query("SELECT DISTINCT(no_surat_claim),nama_file FROM tr_claim_kpb_generate_detail 
			JOIN tr_claim_kpb_generate ON tr_claim_kpb_generate_detail.no_generate=tr_claim_kpb_generate.no_generate
			WHERE tr_claim_kpb_generate_detail.chk_reject=1 AND sudah_proses_ulang=0
			ORDER BY tr_claim_kpb_generate.created_at DESC
			");
		//$data['dt_result'] = $this->m_admin->getAll($this->tables);				
		$this->template($data);
	}

	public function verifikasi()
	{
		$data['isi']    = $this->page;
		$data['title']	= 'Verifikasi ' . $this->title;
		// $data['result'] = $this->db->query("SELECT * FROM tr_claim_kpb_generate ORDER BY created_at DESC");
		$data['set']	= "verifikasi";
		$this->template($data);
	}

	public function detail_verifikasi()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Detail Verifikasi ' . $this->title;
		$id            = $this->input->get('id');
		$th            = $this->input->get('th');
		$row           = $this->db->get_where('tr_claim_kpb_generate', ['no_generate' => $id, 'tahun' => $th]);
		if ($row->num_rows() > 0) {
			$data['result'] = $this->db->query("SELECT *,tr_claim_kpb_generate_detail.status FROM tr_claim_kpb_generate_detail 
						JOIN tr_claim_kpb ON tr_claim_kpb_generate_detail.id_claim_kpb = tr_claim_kpb.id_claim_kpb
						WHERE no_generate='$id' AND tahun='$th'");
			$data['row'] = $row->row();
		}
		$data['set']    = "detail_verifikasi";
		$this->template($data);
	}

	public function approve_ahm()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Approve ' . $this->title;
		$id            = $this->input->get('id');
		$th            = $this->input->get('th');
		$row           = $this->db->get_where('tr_claim_kpb_generate', ['no_generate' => $id, 'status' => 'input', 'tahun' => $th]);
		if ($row->num_rows() > 0) {
			$data['result'] = $this->db->query("SELECT * FROM tr_claim_kpb_generate_detail 
						JOIN tr_claim_kpb ON tr_claim_kpb_generate_detail.id_claim_kpb = tr_claim_kpb.id_claim_kpb
						WHERE no_generate='$id' AND tahun='$th'");
			$data['row'] = $row->row();
		}
		$data['set']    = "approve_ahm";
		$this->template($data);
	}

	public function reject_ahm()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Reject ' . $this->title;
		$id            = $this->input->get('id');
		$th            = $this->input->get('th');
		$row           = $this->db->get_where('tr_claim_kpb_generate', ['no_generate' => $id, 'tahun' => $th]);
		if ($row->num_rows() > 0) {
			$data['result'] = $this->db->query("SELECT * FROM tr_claim_kpb_generate_detail 
						JOIN tr_claim_kpb ON tr_claim_kpb_generate_detail.id_claim_kpb = tr_claim_kpb.id_claim_kpb
						WHERE no_generate='$id' AND tahun='$th' ");
			$data['row'] = $row->row();
			$data['set']    = "reject_ahm";
			$this->template($data);
		}
	}

	public function fetch_ahass()
	{
		$fetch_data = $this->make_datatables();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array   = array();
			$sub_array[] = $rs->kode_dealer_md;
			$sub_array[] = $rs->nama_dealer;
			$row         = json_encode($rs);
			$link        = '<button data-dismiss=\'modal\' onClick=\'return pilihAHASS(' . $row . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[] = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}



	function make_query()
	{
		$this->db->select('ms_dealer.id_dealer,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,ms_dealer.pkp');
		$this->db->from('ms_dealer');
		// $this->db->join('ms_link', 'ms_link.kode_btn = ms_link.kode_btn');
		$this->db->where('ms_dealer.h2=1');

		$search = $this->input->post('search')['value'];
		if ($search != '') {
			$searchs = "(nama_dealer LIKE '%$search%' 
	          OR kode_dealer_md LIKE '%$search%'
	      )";
			$this->db->where("$searchs", NULL, false);
		}
		if (isset($_POST["order"])) {
			$this->db->order_by($this->order_column_ahass[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
			$this->db->order_by('kode_dealer_md', 'ASC');
		}
	}
	function make_datatables()
	{
		$this->make_query();
		if ($_POST["length"] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function get_filtered_data()
	{
		$this->make_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function fetch_nosin()
	{
		$fetch_data = $this->make_query_nosin();
		$data = array();
		foreach ($fetch_data as $rs) {
			$link2 = '';
			$query= $this->db->query("SELECT no_mesin_spasi FROM tr_fkb WHERE no_mesin_spasi='$rs->no_mesin' AND no_rangka = '$rs->no_rangka' AND kode_tipe='$rs->id_tipe_kendaraan'")->row();

			if(isset($query)){
				// $link2 = '&nbsp;&nbsp;&nbsp;';
				$link2 = '';
				$rs->icon = '';
			}else{
				// $link2 = '<button class="warningNosin btn btn-warning btn-xs"><i class="fa fa-info"></i></button>';
				$link2 = '&nbsp;&nbsp;<span class="fa fa-exclamation-triangle" data-toggle="tooltip" data-original-title="Tidak ada di DB MD SinSen"></span>';
				$rs->icon = '&#x26A0;';
			}

			$sub_array   = array();
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->id_tipe_kendaraan . ' | ' . $rs->tipe_ahm;
			$sub_array[] = 0;
			$sub_array[] = $rs->kpb_ke;
			$sub_array[] = $rs->tgl_pembelian_indo;
			$sub_array[] = $rs->km_terakhir;
			$sub_array[] = $rs->tgl_servis_indo;
			$row         = json_encode($rs);
			$link        = '<button onClick=\'return pilihNosin(this, ' . $row . ')\' class="btnPilihNosin btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link.$link2;
			$data[] = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_nosin(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	function make_query_nosin($recordsFiltered = null)
	{
		$id_dealer  = $_POST['id_dealer'];
		// $id_periode = $_POST['id_periode'];
		// $bulan      = $_POST['bulan'];
		// $tahun      = $_POST['tahun'];
		// $get_periode = $this->db->query("SELECT * FROM ms_periode_claim_kpb WHERE id_periode='$id_periode'");

		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST['order'] : '',
			'order_column' => ['no_mesin', 'no_rangka', 'id_tipe_kendaraan', 'id_warna', 'tgl_invoice', null],
			'id_dealer' => $id_dealer,
			// 'bulan' => $bulan,
			// 'tahun' => $tahun,
			'start_date' => isset($_POST['start_date']) ? $_POST['start_date'] : '',
			'end_date' => isset($_POST['end_date']) ? $_POST['end_date'] : '',
			'periode' => true,
			'search' => $this->input->post('search')['value'],
		];
		if (isset($_POST['id_work_order'])) {
			$filter['id_work_order'] = $_POST['id_work_order'];
		}
		if ($recordsFiltered == true) {
			return $this->m_claim->getNosinClaimKPB($filter)->num_rows();
		} else {
			return $this->m_claim->getNosinClaimKPB($filter)->result();
		}
	}

	function save()
	{
		$this->load->model('m_h2_master','m_h2');
		$post = $this->input->post();
		if (isset($_POST['details'])) {
			$details = $this->input->post('details');
			foreach ($details as $val) {
				$harga_material = 0;
				$harga_jasa = 0;
				$id_part = null;
				$filter = ['no_mesin' => $val['no_mesin'], 'id_type' => 'ASS' . $val['kpb_ke']];
				$get_harga = $this->m_claim->getPekerjaanWOClaimKPB($filter);
				if ($get_harga->num_rows() > 0) {
					$hrg = $get_harga->row();
					// $harga_material = $hrg->harga_material;
					$harga_jasa = $hrg->harga_jasa == NULL ? 0 : $hrg->harga_jasa;
					// $id_part = $hrg->id_part;
				}
				$wo = $this->db->query("SELECT wo.id_sa_form,sa.km_terakhir FROM tr_h2_wo_dealer wo
							JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
							WHERE id_work_order='{$val['id_work_order']}'")->row();
				$datas[] = [
					'id_tipe_kendaraan' => $val['id_tipe_kendaraan'],
					'no_mesin'          => $val['no_mesin'],
					'no_rangka'         => $val['no_rangka'],
					'no_kpb'            => $val['no_kpb'],
					'kpb_ke'            => $val['kpb_ke'],
					'tgl_beli_smh'      => date_ymd($val['tgl_beli_smh_indo']),
					'km_service'        => $val['km_service'],
					'tgl_service'       => date_ymd($val['tgl_service_indo']),
					'created_at'        => waktu_full(),
					'created_by'        => user()->id_user,
					'id_dealer'         => $post['id_dealer'],
					'start_date'        => $post['start_date'],
					'end_date'        => $post['end_date'],
					'harga_material'    => $harga_material == NULL ? 0 : $harga_material,
					'harga_jasa'    => $harga_jasa == NULL ? 0 : $harga_jasa,
					'id_part'           => $id_part
				];
				$params = [
					'kpb_ke'               => 'ASS'.$val['kpb_ke'],
					'id_tipe_kendaraan'    => $val['id_tipe_kendaraan'],
					'no_mesin'             => $val['no_mesin'],
					'tgl_pembelian'        => date_ymd($val['tgl_beli_smh_indo']),
					'km_terakhir'          => $val['km_service'],
					'tgl_service'          => date_ymd($val['tgl_service_indo']),
					'cek_md'							 => true,
				];
				$result = $this->m_h2->cekKPB($params);
				if ($result['status']!='oke') {
					$response=['status'=>'error','pesan'=>$result['msg']];
					send_json($response);
				}
				if (isset($val['parts'])) {
					if (count($val['parts']) > 0) {
						foreach ($val['parts'] as $prt) {
							$ins_parts[] = [
								'no_mesin' => $val['no_mesin'],
								'kpb_ke' => $val['kpb_ke'],
								'id_work_order' => $prt['id_work_order'],
								'harga' => $prt['harga_oli_kpb'],
								'het' => $prt['harga'],
								'qty' => $prt['qty'],
								'id_part' => $prt['id_part'],
							];
						}
					}
				}
				if ($wo != NULL) {
					if ($wo->km_terakhir != $val['km_service']) {
						$upd_sa_form[] = [
							'id_sa_form' => $wo->id_sa_form,
							'km_terakhir' => $val['km_service'],
							'updated_sa_form_at'        => waktu_full(),
							'updated_sa_form_by'        => user()->id_user,
						];
					}
				}
			}
		} else {
			$result = ['status' => 'error', 'pesan' => 'Detail masih kosong !'];
			send_json($result);
		}

		$tes = [
			'datas'     => $datas,
			'ins_parts' => isset($ins_parts) ? $ins_parts : NULL,
			'upd_sa_form' => isset($upd_sa_form) ? $upd_sa_form : NULL,
		];
		// send_json($tes);

		$this->db->trans_begin();
		$this->db->insert_batch('tr_claim_kpb', $datas);
		if (isset($ins_parts)) {
			$this->db->insert_batch('tr_claim_kpb_oli', $ins_parts);
		}
		if (isset($upd_sa_form)) {
			$this->db->update_batch('tr_h2_sa_form', $upd_sa_form, 'id_sa_form');
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
				'link' => base_url('h2/' . $this->page)
			];
			$_SESSION['pesan'] 	= "Data berhasil disimpan";
			$_SESSION['tipe'] 	= "success";
		}
		send_json($rsp);
	}

	function getNosin()
	{
		$post = $this->input->post();

		$filter = [
			'start_date'     => $post['start_date'],
			'end_date'     => $post['end_date'],
			'id_dealer' => $post['id_dealer'],
			'periode' => true,
		];
		if (isset($post['no_mesin'])) {
			$filter['no_mesin'] = $post['no_mesin'];
		} elseif (isset($post['no_rangka'])) {
			$filter['no_rangka'] = $post['no_rangka'];
		}
		$get_data = $this->m_claim->getNosinClaimKPB($filter);
		$result = $get_data->row();
		$oli = [];
		if ($result != NULL) {
			$oli = $this->getPartOliKPB($result->id_work_order)['data'];
		}
		$response = ['tot' => $get_data->num_rows(), 'nosin' => $result, 'oli' => $oli];
		send_json($response);
	}

	function getKPB()
	{
		if (isset($_POST['no_mesin_5'])) {
			$no_mesin_5 = $this->input->post('no_mesin_5');
			// $id_tipe_kendaraan = $this->db->query("SELECT id_tipe_kendaraan FROM ms_tipe_kendaraan WHERE no_mesin='$no_mesin_5' ORDER BY id_tipe_kendaraan ASC LIMIT 0,1")->row()->id_tipe_kendaraan;
			// $id_tipe_kendaraan ='GF2';
			$kpb = $this->db->query("SELECT * FROM ms_kpb_detail 
	   								 JOIN ms_kpb ON ms_kpb.id_tipe_kendaraan=ms_kpb_detail.id_tipe_kendaraan
	   								 WHERE EXISTS (SELECT id_tipe_kendaraan FROM ms_tipe_kendaraan WHERE no_mesin='$no_mesin_5' AND id_tipe_kendaraan=ms_kpb_detail.id_tipe_kendaraan) AND status=1 GROUP BY kpb_ke
									 ORDER BY kpb_ke ASC
	   		");
			if ($kpb->num_rows() > 0) {
				echo '<option value="">--choose--</option>';
				foreach ($kpb->result() as $rs) {
					$dt = json_encode($rs);
					echo '<option data=\'' . $dt . '\' value="' . $rs->kpb_ke . '">' . $rs->kpb_ke . '</option>';
				}
			} else {
				echo 'kosong';
			}
		} else {
			$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
			$no_mesin          = $this->input->post('no_mesin');
			$kpb               = $this->db->query("SELECT * FROM ms_kpb_detail 
	   								 JOIN ms_kpb ON ms_kpb.id_tipe_kendaraan=ms_kpb_detail.id_tipe_kendaraan
	   								 WHERE ms_kpb_detail.id_tipe_kendaraan='$id_tipe_kendaraan' AND ms_kpb.status=1
									 AND kpb_ke NOT IN (SELECT kpb_ke FROM tr_claim_kpb WHERE no_mesin='$no_mesin')
									 ORDER BY kpb_ke ASC
	   		");
			if ($kpb->num_rows() > 0) {
				echo '<option value="">--choose--</option>';
				foreach ($kpb->result() as $rs) {
					$dt = json_encode($rs);
					echo '<option data=\'' . $dt . '\' value="' . $rs->kpb_ke . '">' . $rs->kpb_ke . '</option>';
				}
			} else {
				echo 'kosong';
			}
		}
	}

	public function editPerNosin()
	{
		$id_claim_kpb  = $this->input->get('id');
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "edit_pernosin";
		$dt_result     = $this->db->query("SELECT tr_claim_kpb.*,ms_tipe_kendaraan.tipe_ahm FROM tr_claim_kpb 
			JOIN ms_tipe_kendaraan ON tr_claim_kpb.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			WHERE id_claim_kpb='$id_claim_kpb' ");
		if ($dt_result->num_rows() > 0) {
			$row = $dt_result->row();
			$data['dt_result'] = $dt_result->result();
			$data['kpb_detail'] = $this->db->query("SELECT * FROM ms_kpb_detail WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan' AND kpb_ke=$row->kpb_ke ")->row();
			$this->template($data);
		}
	}

	public function save_edit()
	{
		$data['id_claim_kpb'] = $this->input->post('id_claim_kpb');
		$data['km_service']   = $this->input->post('km_service');
		$data['tgl_service']  = $this->input->post('tgl_service');
		$data['no_kpb']       = $this->input->post('no_kpb');
		$upd_data[] 		  = $data;
		$this->db->update_batch('tr_claim_kpb', $upd_data, 'id_claim_kpb');
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/claim_kpb'>";
	}

	public function generate()
	{
		$no_mesin_5 = $this->input->post('no_mesin_5');
		$service_ke = $this->input->post('service_ke');
		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');
		$result = $this->db->query("SELECT tr_claim_kpb.id_claim_kpb,tr_claim_kpb.no_mesin,tr_claim_kpb.no_rangka,tr_claim_kpb.id_tipe_kendaraan,tipe_ahm,no_kpb,tgl_beli_smh,km_service,tgl_service 
   			FROM tr_claim_kpb 
   			JOIN ms_tipe_kendaraan ON tr_claim_kpb.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
   			WHERE LEFT(tr_claim_kpb.no_mesin,5)='$no_mesin_5' 
   			AND kpb_ke='$service_ke'
   			AND tgl_service BETWEEN '$start_date' AND '$end_date'
   			AND tr_claim_kpb.id_claim_kpb NOT IN (SELECT id_claim_kpb FROM tr_claim_kpb_generate_detail )
   			");
		$response['total'] = $result->num_rows();
		$response['details'] = $result->result();
		echo json_encode($response);
	}

	function generateSkpbReject()
	{
		$no_surat_claim = $this->input->post('no_surat_claim');
		$filter = ['no_surat_claim' => $no_surat_claim, 'chk_reject' => 1];
		$result = $this->m_claim->getClaimGenerated($filter);
		$response['total'] = $result->num_rows();
		$response['details'] = $result->result();
		echo json_encode($response);
	}

	public function cekNoUrutGenerate($is_reject = null)
	{
		$tahun = year();
		if ($is_reject != null) {
			$get_data = $this->db->query("SELECT no_generate,urutan_reject FROM tr_claim_kpb_generate WHERE no_generate='$is_reject' AND LEFT(tahun,4)='$tahun' ORDER BY created_at DESC LIMIT 0,1");
			if ($get_data->num_rows() > 0) {
				$row           = $get_data->row();
				$urutan_reject_new = $row->urutan_reject + 1;
				$urutan_reject = int_ke_huruf($urutan_reject_new);
				$no_generate   = explode('-', $row->no_generate);
				$new_kode      = $no_generate[0] . '-' . $urutan_reject;
			}
		} else {
			$get_data = $this->db->query("SELECT no_generate,urutan_reject FROM tr_claim_kpb_generate WHERE LEFT(tahun,4)='$tahun' and urutan_reject is null ORDER BY created_at DESC LIMIT 0,1");
			if ($get_data->num_rows() > 0) {
				$row = $get_data->row();
				$id       = preg_replace("/[^0-9]/", "", $row->no_generate);
				$new_kode = sprintf("%'.04d", $id + 1);
			} else {
				$new_kode = '0001';
			}
		}

		return $new_kode;
	}
	public function save_generate()
	{
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');
		$tahun 		= date('Y');
		$bulan 		= date('m');
		$no_mesin_5 = $this->input->post('no_mesin_5');
		$service_ke = $this->input->post('service_ke');
		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		$kode_ahm               = 'AHM-E20-SSP';
		$no_generate            = $data['no_generate']  = $this->cekNoUrutGenerate();
		$data['start_date']     = $start_date;
		$data['end_date']       = $end_date;
		$no_mesin_5             = $data['no_mesin_5']   = $this->input->post('no_mesin_5');
		$service_ke             = $data['service_ke']   = $this->input->post('service_ke');
		$data['tgl_generate']   = gmdate('Y-m-d');
		$waktu_gabung           = $data['waktu_gabung'] = gmdate('mdYHis', time() + 60 * 60 * 7);
		$data['tahun']     			= year();
		$data['created_at']     = $waktu;
		$data['created_by']     = $login_id;
		$data['status']         = 'input';
		$data['nama_file']      = $kode_ahm . '-' . $no_generate . '-' . $no_mesin_5 . '-' . $service_ke . '-' . $waktu_gabung . '.SKPB';
		$data['no_surat_claim'] = $no_generate . '/' . getBulanRomawi($bulan) . '-A' . '/HSC-FIN/' . $tahun . '-' . int_ke_huruf($service_ke);

		$id_claim_kpb = $this->input->post('id_claim_kpb');
		if (count($id_claim_kpb) > 0) {
			for ($i = 0; $i < count($id_claim_kpb); $i++) {
				$detail['no_generate']  = $no_generate;
				$detail['id_claim_kpb'] = $id_claim_kpb[$i];
				$detail['tahun'] 				= year();
				$details[]              = $detail;
			}
		}

		$tes = ['data' => $data, 'details' => $details];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_claim_kpb_generate', $data);
		if (isset($details)) {
			$this->db->insert_batch('tr_claim_kpb_generate_detail', $details);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			// send_json($data);
			$this->load->view('h2/file_skpb', $data);
		}
	}

	public function download_skpb(){
		// $data['nama_file'] = 'AHM-E20-SSP-1482-JM81E-1-11262022153436.SKPB';
		// $data['no_generate'] ='1482';
		// $data['tahun'] = '2022';

		$data['nama_file'] = $this->input->get("fl");
		$data['no_generate'] = $this->input->get("id");
		$data['tahun'] = $this->input->get("th");
		$this->load->view('h2/file_skpb', $data);
	}

	public function save_generate_reject()
	{
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');
		$tahun 		= date('Y');
		$bulan 		= date('m');
		$no_surat_claim = $this->input->post('no_surat_claim');
		$claim = $this->db->get_where("tr_claim_kpb_generate", ['no_surat_claim' => $no_surat_claim])->row();

		$kode_ahm               = 'AHM-E20-SSP';
		$no_generate            = $data['no_generate']  = $this->cekNoUrutGenerate($claim->no_generate);
		$no_mesin_5             = $data['no_mesin_5']   = $claim->no_mesin_5;
		$service_ke             = $data['service_ke']   = $claim->service_ke;
		$data['tgl_generate']   = gmdate('Y-m-d');
		$waktu_gabung           = $data['waktu_gabung'] = gmdate('mdYHis', time() + 60 * 60 * 7);
		$data['created_at']     = $waktu;
		$data['urutan_reject']  = $claim->urutan_reject + 1;
		$data['created_by']     = $login_id;
		$data['status']         = 'input';
		$data['tahun']         = $tahun;
		$data['nama_file']      = $kode_ahm . '-' . $no_generate . '-' . $no_mesin_5 . '-' . $service_ke . '-' . $waktu_gabung . '.SKPB';
		$data['no_surat_claim'] = $no_generate . '/' . getBulanRomawi($bulan) . '-A' . '/HSC-FIN/' . $tahun . '-' . int_ke_huruf($service_ke);

		$id_claim_kpb = $this->input->post('id_claim_kpb');
		if (count($id_claim_kpb) > 0) {
			$post = $this->input->post();
			for ($i = 0; $i < count($id_claim_kpb); $i++) {
				$detail['no_generate']  = $no_generate;
				$detail['id_claim_kpb'] = $id_claim_kpb[$i];
				$detail['tahun'] = $tahun;
				$details[]              = $detail;
				$upd_claim[] = [
					'id_claim_kpb' => $post['id_claim_kpb'][$i],
					'no_kpb' => $post['no_kpb'][$i],
					'kpb_ke' => $post['kpb_ke'][$i],
					'tgl_beli_smh' => $post['tgl_beli_smh'][$i],
					'km_service' => $post['km_service'][$i],
					'tgl_service' => $post['tgl_service'][$i],
				];
			}
		}
		// send_json($upd_claim);

		$upd_yang_lama = ['sudah_proses_ulang' => 1];
		$this->db->trans_begin();
		$this->db->update(
			'tr_claim_kpb_generate',
			$upd_yang_lama,
			['no_surat_claim' => $this->input->post('no_surat_claim')]
		);
		$this->db->insert('tr_claim_kpb_generate', $data);
		if (isset($details)) {
			$this->db->insert_batch('tr_claim_kpb_generate_detail', $details);
			$this->db->update_batch('tr_claim_kpb', $upd_claim, 'id_claim_kpb');
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			//      	$_SESSION['pesan'] 	= "Data has been saved successfully";
			// $_SESSION['tipe'] 	= "success";
			$this->load->view('h2/file_skpb', $data);
		}
	}

	public function save_approve()
	{
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');

		$no_generate              = $this->input->post('no_generate');
		$dt_header['no_generate'] = $no_generate;
		$dt_header['status']      = 'approved';
		$dt_header['approved_at'] = $waktu;
		$dt_header['approved_by'] = $login_id;

		$data['no_generate'] = $no_generate;
		$data['status']      = 'approved';

		$this->db->trans_begin();
		$this->db->update('tr_claim_kpb_generate', $dt_header, ['no_generate' => $no_generate]);
		$this->db->update('tr_claim_kpb_generate_detail', $data, ['no_generate' => $no_generate]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/claim_kpb/verifikasi'>";
		}
	}
	public function save_reject()
	{
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');

		$no_generate         = $this->input->post('no_generate');
		$data['no_generate'] = $no_generate;
		$chk_rejects          = $this->input->post('chk_reject');
		$id_detail          = $this->input->post('id_detail');
		if (count($id_detail) > 0) {
			$cek_direject = 0;
			for ($i = 0; $i < count($id_detail); $i++) {
				$chk_reject = null;
				$status     = 'approved';
				if (count($chk_rejects) > 0) {
					if (in_array($id_detail[$i], $chk_rejects)) {
						$chk_reject = 1;
						$status     = 'reject';
						$cek_direject++;
					}
				}
				$details[] = [
					'chk_reject' => $chk_reject,
					'status'    => $status,
					'id_detail' => $id_detail[$i]
				];
			}
		}
		$status = $cek_direject == 0 ? 'approved' : 'reject';
		$data['status']      = $status;

		$this->db->trans_begin();
		$this->db->update('tr_claim_kpb_generate', $data, ['no_generate' => $no_generate]);
		if (isset($details)) {
			$this->db->update_batch('tr_claim_kpb_generate_detail', $details, 'id_detail');
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/claim_kpb/verifikasi'>";
		}
	}

	public function create_tagihan_dealer()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Create Tagihan Dealer';
		$data['mode']  = 'insert';
		$data['set']   = "tagihan_dealer";
		$this->template($data);
	}

	function generateTagihan($id_dealer = null, $start_date = null, $end_date = null)
	{
		$this->load->model('m_h2_md_laporan','m_lap');
		if ($id_dealer == null && $start_date == null && $end_date == null) {
			$null = 'ya';
			$start_date = $this->input->post('start_date');
			$end_date    = $this->input->post('end_date');
			$id_dealer  = $this->input->post('id_dealer');
		}

		$dt_result = $this->db->query("SELECT ckg.id_detail,cko.harga,cko.qty,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_claim_kpb.harga_jasa,tr_claim_kpb.diskon_material,tr_claim_kpb.harga_jasa,tr_claim_kpb.no_mesin,tr_claim_kpb.kpb_ke,tr_claim_kpb.id_tipe_kendaraan
			FROM tr_claim_kpb_generate_detail AS ckg
			JOIN tr_claim_kpb ON ckg.id_claim_kpb=tr_claim_kpb.id_claim_kpb
			LEFT JOIN tr_claim_kpb_oli cko ON cko.no_mesin=tr_claim_kpb.no_mesin AND cko.kpb_ke=tr_claim_kpb.kpb_ke
			JOIN ms_dealer ON ms_dealer.id_dealer=tr_claim_kpb.id_dealer
			WHERE tr_claim_kpb.id_dealer='$id_dealer' 
			AND id_tagihan_dealer IS NULL 
			AND chk_reject IS NULL
			AND tr_claim_kpb.tgl_service BETWEEN '$start_date' AND '$end_date' AND ckg.status='approved'
			-- GROUP BY tr_claim_kpb.no_mesin,tr_claim_kpb.kpb_ke
			");

		$data = array();
		if ($dt_result->num_rows() > 0) {
			$set_jasa = [];
			$tot_jasa = 0;
			$tot_oli = 0;
			$tot_ppn = 0;
			$tot_pph = 0;
			$tot_qty = count($dt_result->result());
			foreach ($dt_result->result() as $rs) {
				$insentif = $this->db->query("SELECT insentif_oli FROM ms_kpb WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan'")->row()->insentif_oli;
				$kode_dealer_md  = $rs->kode_dealer_md;
				$nama_dealer     = $rs->nama_dealer;
				$tot_oli        += ($rs->harga + $insentif - $rs->diskon_material) * $rs->qty;
			}

			$fclgen = [
				'periode_servis'    => true,
				'tgl_awal'          => $start_date,
				'tgl_akhir'         => $end_date,
				'id_dealer'         => $this->input->post('id_dealer'),
				'return'            => 'total_all_jasa_insentif_oli'
			];
			$tot_jasa = $this->m_lap->getLaporanTandaTerimaKPB($fclgen)['total_jasa'];

			$cek_dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
			$tot_pph = $tot_jasa * 0.02;
			// send_json(strtolower($cek_dealer->pkp));
			$is_pkp = 0;
			if (strtolower($cek_dealer->pkp) == 'ya') {
				$tot_ppn = (int) ($tot_jasa + $tot_oli) * getPPN(0.1,false);
				$is_pkp = 1;
			}
			$skip_dealer = ['00888', '12203', '05529', '05391', '05545'];
			if (in_array($cek_dealer->kode_dealer_md, $skip_dealer)) {
				$is_pkp = 0;
				$tot_ppn = 0;
			}
			$is_npwp = 1;
			if ($cek_dealer->npwp == '' || $cek_dealer->npwp == NULL) {
				$tot_pph = $tot_jasa * 0.04;
				$is_npwp = 0;
			}
			$total = ($tot_jasa + $tot_oli + $tot_ppn) - $tot_pph;
			$data[] = [
				'kode_dealer_md' => $kode_dealer_md,
				'nama_dealer' => $nama_dealer,
				'tot_jasa'    => $tot_jasa,
				'tot_oli'     => $tot_oli,
				'tot_jasa'    => $tot_jasa,
				'tot_ppn'     => $tot_ppn,
				'tot_pph'     => $tot_pph,
				'total'       => $total,
				'tot_qty'     => $tot_qty
			];
		}

		if (isset($null)) {
			$insentif = "SELECT insentif_oli FROM ms_kpb WHERE id_tipe_kendaraan=tr_claim_kpb.id_tipe_kendaraan";
			$no_mesin_5 = $this->db->query("SELECT no_mesin_5,(cko.harga+IFNULL(($insentif),0)) harga_material
				FROM tr_claim_kpb_generate_detail AS ckg
				JOIN tr_claim_kpb_generate ON ckg.no_generate=tr_claim_kpb_generate.no_generate AND ckg.tahun=tr_claim_kpb_generate.tahun
				JOIN tr_claim_kpb ON ckg.id_claim_kpb=tr_claim_kpb.id_claim_kpb
				LEFT JOIN tr_claim_kpb_oli cko ON cko.no_mesin = tr_claim_kpb.no_mesin AND cko.kpb_ke=tr_claim_kpb.kpb_ke
				WHERE id_dealer='$id_dealer'
				AND tr_claim_kpb.kpb_ke=1
				AND chk_reject IS NULL
				AND tr_claim_kpb.tgl_service BETWEEN '$start_date' AND '$end_date' AND ckg.status='approved'
				GROUP BY tr_claim_kpb_generate.no_mesin_5,cko.harga
				")->result();

			$response = ['no_mesin_5' => $no_mesin_5, 'tagihan' => $data];
			send_json($response);
		} else {
			return $data = [
				'data_real' => $data,
				'upd_claim' => $dt_result->result(),
				'is_pkp' => $is_pkp,
				'is_npwp' => $is_npwp,
			];
		}
	}

	function get_id_tagihan_dealer()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$ymd 	  = date('Y-m-d');
		$ymd2 	  = date('ymd');
		$get_data  = $this->db->query("SELECT * FROM tr_tagihan_dealer_h2
			WHERE LEFT(created_at,7)='$th_bln' 
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$id_tagihan_dealer = substr($row->id_tagihan_dealer, -4);
			$new_kode   = 'TGD/' . $th_bln . '/' . sprintf("%'.04d", $id_tagihan_dealer + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_tagihan_dealer_h2', ['id_tagihan_dealer' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -4);
					$new_kode = 'TGD/' . $th_bln . '/' . sprintf("%'.04d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode   = 'TGD/' . $th_bln . '/0001';
		}
		return strtoupper($new_kode);
	}

	public function save_tagihan_dealer()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_tagihan_dealer  = $this->get_id_tagihan_dealer();
		$id_dealer  = $this->input->post('id_dealer');
		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		$get_detail = $this->generateTagihan($id_dealer, $start_date, $end_date);
		foreach ($get_detail['upd_claim'] as $rs) {
			$upd_claim[] = ['id_detail' => $rs->id_detail, 'id_tagihan_dealer' => $id_tagihan_dealer];
		}

		$data 	= [
			'id_tagihan_dealer' => $id_tagihan_dealer,
			'start_date' => $start_date,
			'end_date'   => $end_date,
			'id_dealer'  => $id_dealer,
			'tanggal'	 => $tgl,
			'tot_jasa'   => $get_detail['data_real'][0]['tot_jasa'],
			'tot_oli'    => $get_detail['data_real'][0]['tot_oli'],
			'tot_ppn'    => $get_detail['data_real'][0]['tot_ppn'],
			'tot_pph'    => $get_detail['data_real'][0]['tot_pph'],
			'tot_qty'    => $get_detail['data_real'][0]['tot_qty'],
			'total'      => $get_detail['data_real'][0]['total'],
			'status'     => 'input',
			'created_at' => $waktu,
			'created_by' => $login_id,
			'is_pkp' => $get_detail['is_pkp'],
			'is_npwp' => $get_detail['is_npwp'],
		];

		$tes = [
			'dt_detail' => isset($dt_detail) ? $dt_detail : '',
			'upd_claim' => isset($upd_claim) ? $upd_claim : '',
			'data' => isset($data) ? $data : '',
		];
		// send_json($tes);

		$this->db->trans_begin();
		$this->db->insert('tr_tagihan_dealer_h2', $data);
		if (isset($upd_claim)) {
			$this->db->update_batch('tr_claim_kpb_generate_detail', $upd_claim, 'id_detail');
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
				'link' => base_url('h2/claim_kpb')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	function getPartOliKPB($id_work_order = NULL)
	{
		$this->load->model('m_h2_work_order', 'm_wo');
		if ($id_work_order == NULL) {
			$id_work_order = $this->input->post('id_work_order');
		} else {
			$set_return = true;
		}
		$fwo = [
			'id_work_order' => $id_work_order,
			'type_jasa_in' => "'ASS1'"
		];
		$cek_kpb1 = $this->m_wo->getWOPekerjaan($fwo);
		$result = [];
		if ($cek_kpb1->num_rows() > 0) {
			$filter = [
				'id_work_order' => $id_work_order,
				'kelompok_part_in' => "'OIL'",
				'select' => 'wo_parts',
				'select_add' => 'harga_oli_kpb',
				'qty_lebih_dari'=>0,
				'join_ass1' => 'claim_oli_kpb1'
			];
			$result = $this->m_wo->getWOParts($filter)->result();
		}
		$response = ['status' => 'sukses', 'data' => $result];
		if (isset($set_return)) {
			return $response;
		} else {
			send_json($response);
		}
	}

	public function fetch_verifikasi()
	{
		$fetch_data = $this->make_query_fetch_verifikasi();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '<a href="' . base_url('h2/claim_kpb/download_skpb?id=' . $rs->no_generate . '&th=' . $rs->tahun. '&fl=' . $rs->nama_file) . '" class="btn btn-success btn-xs">Download SKPB</a>';
			$button1 = '<a href="' . base_url('h2/claim_kpb/approve_ahm?id=' . $rs->no_generate . '&th=' . $rs->tahun) . '" class="btn btn-primary btn-xs">Approve AHM</a>';
			$button2 = '<a href="' . base_url('h2/claim_kpb/reject_ahm?id=' . $rs->no_generate . '&th=' . $rs->tahun) . '" class="btn btn-primary btn-xs">Update Status Claim KPB AHM</a>';
			if ($rs->status == 'input') {
				$status = '<label class="label label-warning">' . strtoupper($rs->status) . '</label>';
				$button .= $button1;
			}
			// if ($rs->status == 'approved') {
			// 	$status = '<label class="label label-success">' . strtoupper($rs->status) . '</label>';
			// }
			// if ($rs->status == 'reject') {
			// 	$status = '<label class="label label-danger">' . strtoupper($rs->status) . '</label>';
			// }

			$sub_array[] = '<a href="' . base_url('h2/claim_kpb/detail_verifikasi?id=' . $rs->no_generate . '&th=' . $rs->tahun) . '">' . $rs->nama_file . '</a>';
			$sub_array[] = $rs->no_surat_claim;
			$sub_array[] = $rs->tgl_generate;
			$sub_array[] = $rs->no_mesin_5;
			$sub_array[] = $rs->service_ke;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_fetch_verifikasi(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query_fetch_verifikasi($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'view_verifikasi',
			'search' => $this->input->post('search')['value']
		];
		if (isset($_POST['id_dealer'])) {
			$filter['id_dealer'] = $_POST['id_dealer'];
		}
		if (isset($_POST['no_mesin'])) {
			$filter['no_mesin'] = $_POST['no_mesin'];
		}
		if (isset($_POST['kpb_ke'])) {
			$filter['kpb_ke'] = $_POST['kpb_ke'];
		}
		if ($recordsFiltered == true) {
			return $this->m_claim->getClaimGenerateHeader($filter)->num_rows();
		} else {
			return $this->m_claim->getClaimGenerateHeader($filter)->result();
		}
	}
}
