<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan_diskon extends CI_Controller
{

	var $tables = "ms_diskon";
	var $folder = "dealer";
	var $page   = "pengajuan_diskon";
	var $title  = "Pengajuan Diskon";
	// var $order_column_part = array("id_part","nama_part",'kelompok_vendor',null); 

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
		$this->load->model('m_h1_dealer_prospek', 'm_prospek');
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
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "index";
		$id_dealer       = $this->m_admin->cari_dealer();

		$data['event'] = $this->db->query("SELECT tr_pengajuan_diskon.*,no_hp,nama_konsumen,alamat,tipe_pembayaran, (SELECT byk_jatah FROM ms_diskon WHERE id_diskon=tr_pengajuan_diskon.id_diskon) AS jatah
						FROM tr_pengajuan_diskon 
						JOIN tr_prospek ON tr_prospek.id_prospek=tr_pengajuan_diskon.id_prospek
						WHERE tr_pengajuan_diskon.id_dealer='$id_dealer'
						ORDER BY tr_pengajuan_diskon.created_at DESC");
		$this->template($data);
	}

	public function add()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$id_dealer       = $this->m_admin->cari_dealer();

		$filter['id_dealer'] = $id_dealer;
		$data['prospek'] = $this->m_prospek->getProspekSkemaKredit($filter);
		$data['finco'] = $this->db->query("SELECT * FROM ms_finance_company ORDER BY finance_company");
		// $data['prospek'] = $this->db->get_where('tr_prospek',['id_dealer'=>$id_dealer]);
		$this->template($data);
	}


	public function save()
	{
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();

		$id_prospek = $data['id_prospek']   = $this->input->post('id_prospek');
		$prospek    = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
			WHERE tr_prospek.id_prospek='$id_prospek'
		")->row();
		$get_diskon = $this->db->query("SELECT * FROM ms_diskon WHERE '$prospek->id_tipe_kendaraan' 
																		IN(SELECT id_tipe_kendaraan FROM ms_diskon_kendaraan WHERE id_diskon=ms_diskon.id_diskon) 
																		AND '$prospek->id_warna' IN(SELECT id_warna FROM ms_diskon_kendaraan WHERE id_diskon=ms_diskon.id_diskon)
																		AND '$prospek->id_karyawan_dealer' IN(SELECT id_karyawan_dealer FROM ms_diskon_assignment WHERE id_diskon=ms_diskon.id_diskon)
																		");
		$data['id_tipe_kendaraan']  = $prospek->id_tipe_kendaraan;
		$data['id_warna']           = $prospek->id_warna;
		$data['id_karyawan_dealer'] = $prospek->id_karyawan_dealer;
		$nominal_diskon             = $data['nominal_diskon']     = preg_replace("/[^0-9]/", "", $this->input->post('nominal_diskon'));
		$data['tipe_pembayaran']    = $this->input->post('tipe_pembayaran');
		$data['keterangan']         = $this->input->post('keterangan');
		$status                     = '';
		$id_diskon = null;
		if ($get_diskon->num_rows() > 0) {
			$get_diskon = $get_diskon->row();
			$id_diskon  = $get_diskon->id_diskon;
			if ($nominal_diskon > $get_diskon->value) {
				$status = 'Waiting Approval Disc';
			} else {
				$status = 'Approved Disc';
			}
		} else {
			$status = 'Waiting Approval Disc';
		}

		$data['id_diskon'] = $id_diskon;
		$data['status']     = $status;
		$data['id_dealer']  = $id_dealer;
		$data['created_at'] = $waktu;
		$data['created_by'] = $login_id;

		// $units          = $this->input->post('units');
		// foreach ($units as $key => $val) {
		// 	$dt_unit[] = ['id_diskon'=> $id_diskon,
		// 					'id_tipe_kendaraan' => $val['id_tipe_kendaraan'],
		// 					'id_warna'     => $val['id_warna']
		// 			 	 ];	
		// }

		// $karyawans          = $this->input->post('karyawans');
		// foreach ($karyawans as $key => $val) {
		// 	$dt_karyawan[] = ['id_diskon'=> $id_diskon,
		// 					'id_karyawan_dealer' => $val['id_karyawan_dealer']
		// 			 	 ];	
		// }

		// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
		// $email          = array();
		// foreach ($get_notif_grup->result() as $rd) {
		// 	$get_email = $this->db->query("SELECT email FROM ms_karyawan 
		// 			WHERE id_karyawan IN(
		// 				SELECT id_karyawan_dealer FROM ms_user 
		// 				WHERE jenis_user='Main Dealer' 
		// 				AND active=1 
		// 				AND id_user_group=(
		// 					SELECT id_user_group FROM ms_user_group 
		// 					WHERE code='$rd->code_user_group'
		// 				)
		// 			)
		// 	")->result();
		// 	foreach ($get_email as $usr) {
		// 		$email[] = $usr->email;
		// 	}
		// }

		// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 			'id_referensi' => $kode_event,
		// 			'judul'        => "Event Baru Dari Dealer",
		// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 			'status'       =>'baru',
		// 			'created_at'   => $waktu,
		// 			'created_by'   => $login_id
		// 		 ];
		$this->db->trans_begin();
		$this->db->insert('tr_pengajuan_diskon', $data);
		$id_diskon      = $this->db->insert_id();

		// if ($status == 'Waiting Approval Disc') {
		// 	$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>14])->row();
		// 	$get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>14]);
		// 	$notif          = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 				'id_referensi' => $id_diskon,
		// 				'id_dealer'    =>$id_dealer,
		// 				'judul'        => "Pengajuan Diskon Perlu Approval",
		// 				'pesan'        => 'Pengajuan Diskon Perlu Approval',
		// 				'link'         => $ktg_notif->link.'?id='.$id_diskon,
		// 				'id_user' 	   => $login_id,
		// 				'status'       =>'baru',
		// 				'created_at'   => $waktu,
		// 				'created_by'   => $login_id
		// 			 ];
		// 	$this->db->insert('tr_notifikasi',$notif);
		// }
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			// $this->email_event($email,$kode_event);
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('dealer/diskon')
			//   ];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pengajuan_diskon'>";
		}
	}

	public function email_event($email, $kode_event)
	{
		$from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
		$to_email   = $email;

		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://mail.sinarsentosaprimatama.com',
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
		$this->email->to($to_email);
		$this->email->subject('[SINARSENTOSA] Approve Event Dealer');
		$file_logo         = base_url('assets/panel/images/logo_sinsen.jpg');
		$data['logo']      = $file_logo;
		$data['kode_event'] = $kode_event;
		$this->email->message($this->load->view('dealer/event_email', $data, true));

		//Send mail 
		if ($this->email->send()) {
			return 'sukses';
		} else {
			return 'gagal';
		}
	}

	public function detail()
	{
		$id_pengajuan      = $this->input->get('id');

		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "form";
		$data['mode']  = 'detail';
		$id_dealer     = $this->m_admin->cari_dealer();

		$row = $data['row'] = $this->db->query("SELECT *,tr_pengajuan_diskon.keterangan 
			FROM tr_pengajuan_diskon 
			LEFT JOIN tr_skema_kredit ON tr_skema_kredit.id_prospek=tr_pengajuan_diskon.id_prospek
			LEFT JOIN tr_prospek ON tr_prospek.id_prospek=tr_pengajuan_diskon.id_prospek
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			WHERE id_pengajuan=$id_pengajuan ")->row();

		$filter['id_dealer'] = $id_dealer;
		$data['prospek'] = $this->m_prospek->getProspekSkemaKredit($filter);

		$row_ = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
			WHERE tr_prospek.id_prospek='$row->id_prospek' ")->row();
		$data['unit'][0] = [
			'id_tipe_kendaraan' => $row_->id_tipe_kendaraan,
			'tipe_ahm' => $row_->tipe_ahm,
			'id_warna' => $row_->id_warna,
			'warna' => $row_->warna,
		];
		$data['finco'] = $this->db->query("SELECT * FROM ms_finance_company ORDER BY finance_company");
		$this->template($data);
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
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel, $pk, $id);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function edit()
	{
		$id_diskon = $this->input->get('id');
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['mode']      = 'edit';
		$data['set']       = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$data['dealer']    = $this->db->get('ms_dealer');
		$id_dealer = $this->m_admin->cari_dealer();
		$row    = $this->db->query("SELECT * FROM ms_diskon WHERE id_diskon='$id_diskon' AND id_dealer=$id_dealer");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$id_diskon = $row->id_diskon;
			$data['units'] = $this->db->query("SELECT ms_diskon_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_diskon_kendaraan
											   JOIN ms_tipe_kendaraan ON ms_diskon_kendaraan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
											   JOIN ms_warna ON ms_warna.id_warna=ms_diskon_kendaraan.id_warna
											   WHERE id_diskon='$id_diskon'")->result();
			$data['karyawans'] = $this->db->query("SELECT ms_diskon_assignment.*,nama_lengkap,jabatan FROM ms_diskon_assignment 
										JOIN ms_karyawan_dealer ON ms_diskon_assignment.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
										JOIN ms_jabatan ON ms_jabatan.id_jabatan=ms_karyawan_dealer.id_jabatan
										WHERE id_diskon='$id_diskon'")->result();
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/event_d'>";
		}
	}

	public function save_edit()
	{
		$waktu    = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();

		$id_diskon = $data['id_diskon'] = $this->input->post('id_diskon');
		$data['jatah_approval'] = $this->input->post('jatah_approval');
		$data['tipe_diskon'] = $this->input->post('tipe_diskon');

		$data['byk_jatah']      = $this->input->post('byk_jatah');
		$data['start_date']     = $this->input->post('start_date');
		$data['end_date']       = $this->input->post('end_date');
		$data['value']          = $this->input->post('value');
		$data['id_dealer']      = $id_dealer;
		$data['updated_at']     = $waktu;
		$data['updated_by']     = $login_id;

		$units          = $this->input->post('units');
		foreach ($units as $key => $val) {
			$dt_unit[] = [
				'id_diskon' => $id_diskon,
				'id_tipe_kendaraan' => $val['id_tipe_kendaraan'],
				'id_warna'     => $val['id_warna']
			];
		}

		$karyawans          = $this->input->post('karyawans');
		foreach ($karyawans as $key => $val) {
			$dt_karyawan[] = [
				'id_diskon' => $id_diskon,
				'id_karyawan_dealer' => $val['id_karyawan_dealer']
			];
		}

		// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
		// $email          = array();
		// foreach ($get_notif_grup->result() as $rd) {
		// 	$get_email = $this->db->query("SELECT email FROM ms_karyawan 
		// 			WHERE id_karyawan IN(
		// 				SELECT id_karyawan_dealer FROM ms_user 
		// 				WHERE jenis_user='Main Dealer' 
		// 				AND active=1 
		// 				AND id_user_group=(
		// 					SELECT id_user_group FROM ms_user_group 
		// 					WHERE code='$rd->code_user_group'
		// 				)
		// 			)
		// 	")->result();
		// 	foreach ($get_email as $usr) {
		// 		$email[] = $usr->email;
		// 	}
		// }

		// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 			'id_referensi' => $kode_event,
		// 			'judul'        => "Event Baru Dari Dealer",
		// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 			'status'       =>'baru',
		// 			'created_at'   => $waktu,
		// 			'created_by'   => $login_id
		// 		 ];
		$this->db->trans_begin();
		$this->db->update('ms_diskon', $data, ['id_diskon' => $id_diskon]);
		// $this->db->insert('tr_notifikasi',$notif);
		$this->db->delete('ms_diskon_kendaraan', ['id_diskon' => $id_diskon]);
		$this->db->delete('ms_diskon_assignment', ['id_diskon' => $id_diskon]);
		if (isset($dt_unit)) {
			$this->db->insert_batch('ms_diskon_kendaraan', $dt_unit);
		}
		if (isset($dt_karyawan)) {
			$this->db->insert_batch('ms_diskon_assignment', $dt_karyawan);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			// $this->email_event($email,$kode_event);
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/diskon')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/skema_kredit/add'>";
		}
		echo json_encode($rsp);
	}
	public function getProspek()
	{
		$id_prospek = $this->input->post('id_prospek');
		$prp = $this->db->query("SELECT * FROM tr_prospek WHERE id_prospek='$id_prospek'")->row();
		$spk = $this->db->query("SELECT * FROM tr_spk WHERE id_customer='$prp->id_customer' ORDER BY created_at DESC LIMIT 1");
		$skema = $this->db->query("SELECT * FROM tr_skema_kredit WHERE id_prospek='$id_prospek' ORDER BY created_at DESC");
		$tenor = 0;
		$dp = 0;
		$angsuran = 0;
		if ($skema->num_rows() > 0) {
			$skema    = $skema->row();
			$tenor    = $skema->tenor;
			$dp       = $skema->dp;
			$angsuran = $skema->angsuran;
		}

		if ($spk->num_rows() > 0) {
			$spk = $spk->row();
			if ($spk->uang_muka == 0) {
				$tipe_pembayaran = 'cash';
			} else {
				$tipe_pembayaran = 'kredit';
			}
		} else {
			$tipe_pembayaran = null;
		}

		$id_tipe_kendaraan = $prp->id_tipe_kendaraan;
		$id_warna = $prp->id_warna;
		$tipe 							= "Customer Umum";

		$cek_bbn = $this->db->query("SELECT * FROM ms_bbn_dealer WHERE id_tipe_kendaraan = '$id_tipe_kendaraan'");

		if ($cek_bbn->num_rows() > 0) {

			$te = $cek_bbn->row();

			$biaya_bbn = $te->biaya_bbn;
		} else {

			$biaya_bbn = 0;
		}



		$item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");

		if ($item->num_rows() > 0) {

			$ty = $item->row();

			$id_item = $ty->id_item;
		} else {

			$id_item = "";
		}



		$cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md 

			INNER JOIN ms_kelompok_harga ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 

			WHERE ms_kelompok_md.id_item = '$id_item' AND ms_kelompok_harga.target_market = '$tipe' ORDER BY start_date DESC LIMIT 0,1");

		if ($cek_harga->num_rows() > 0) {

			$ts = $cek_harga->row();

			$harga_jual = $ts->harga_jual;
		} else {

			$harga_jual = 0;
		}



		$harga 		= floor($harga_jual / getPPN(1.1));

		$ppn 			= floor(getPPN(0.1) * $harga);

		$harga_on = $harga_jual + $biaya_bbn;

		$harga_tunai = $harga_on;


		$data = [
			'tenor' => $tenor,
			'angsuran'        => $angsuran,
			'dp'              => $dp,
			'tipe_pembayaran' => $tipe_pembayaran,
			'biaya_bbn' => $biaya_bbn,
			'harga_on_road' => $harga_on,
			'harga_off_road' => $harga_jual,
		];
		echo json_encode($data);
	}

	public function approve()
	{
		$id_pengajuan      = $this->input->get('id');

		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "form";
		$data['mode']  = 'approve';
		$id_dealer     = $this->m_admin->cari_dealer();

		$row = $data['row'] = $this->db->query("SELECT *,tr_pengajuan_diskon.keterangan FROM tr_pengajuan_diskon 
			LEFT JOIN tr_skema_kredit ON tr_skema_kredit.id_prospek=tr_pengajuan_diskon.id_prospek
			LEFT JOIN tr_prospek ON tr_prospek.id_prospek=tr_pengajuan_diskon.id_prospek
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			WHERE id_pengajuan=$id_pengajuan ")->row();

		$data['prospek'] = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_dealer=$id_dealer
			ORDER BY tr_prospek.created_at DESC");

		$row_ = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
			WHERE tr_prospek.id_prospek='$row->id_prospek' ")->row();
		$data['unit'][0] = [
			'id_tipe_kendaraan' => $row_->id_tipe_kendaraan,
			'tipe_ahm' => $row_->tipe_ahm,
			'id_warna' => $row_->id_warna,
			'warna' => $row_->warna,
		];
		$data['finco'] = $this->db->query("SELECT * FROM ms_finance_company ORDER BY finance_company");
		$this->template($data);
	}
	public function save_approve()
	{
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');

		$id_pengajuan = $this->input->post('id_pengajuan');
		$pngjn = $this->db->query("SELECT * FROM tr_pengajuan_diskon WHERE id_pengajuan='$id_pengajuan'")->row();
		$jatah_approve_terpakai = $pngjn->jatah_approve_terpakai + 1;
		$data['jatah_approve_terpakai'] = $jatah_approve_terpakai;
		$data['status']                 = 'Approved Disc';
		$data['approved_at']            = $waktu;
		$data['approved_by']            = $login_id;

		$this->db->trans_begin();
		$this->db->update('tr_pengajuan_diskon', $data, ['id_pengajuan' => $id_pengajuan]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			// $this->email_event($email,$kode_event);
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('dealer/diskon')
			//   ];
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pengajuan_diskon'>";
		}
	}

	public function reject()
	{
		$id_pengajuan      = $this->input->get('id');

		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "form";
		$data['mode']  = 'reject';
		$id_dealer     = $this->m_admin->cari_dealer();

		$row = $data['row'] = $this->db->query("SELECT *,tr_pengajuan_diskon.keterangan FROM tr_pengajuan_diskon 
			LEFT JOIN tr_skema_kredit ON tr_skema_kredit.id_prospek=tr_pengajuan_diskon.id_prospek
			LEFT JOIN tr_prospek ON tr_prospek.id_prospek=tr_pengajuan_diskon.id_prospek
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			WHERE id_pengajuan=$id_pengajuan ")->row();

		$data['prospek'] = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_dealer=$id_dealer
			ORDER BY tr_prospek.created_at DESC");

		$row_ = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
			WHERE tr_prospek.id_prospek='$row->id_prospek' ")->row();
		$data['unit'][0] = [
			'id_tipe_kendaraan' => $row_->id_tipe_kendaraan,
			'tipe_ahm' => $row_->tipe_ahm,
			'id_warna' => $row_->id_warna,
			'warna' => $row_->warna,
		];
		$data['finco'] = $this->db->query("SELECT * FROM ms_finance_company ORDER BY finance_company");
		$this->template($data);
	}
	public function save_reject()
	{
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');

		$id_pengajuan = $this->input->post('id_pengajuan');
		$pngjn = $this->db->query("SELECT * FROM tr_pengajuan_diskon WHERE id_pengajuan='$id_pengajuan'")->row();
		$jatah_approve_terpakai = $pngjn->jatah_approve_terpakai + 1;
		$data['jatah_approve_terpakai'] = $jatah_approve_terpakai;
		$data['status']                 = 'Reject Disc';
		$data['approved_at']            = $waktu;
		$data['approved_by']            = $login_id;

		$this->db->trans_begin();
		$this->db->update('tr_pengajuan_diskon', $data, ['id_pengajuan' => $id_pengajuan]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			// $this->email_event($email,$kode_event);
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('dealer/diskon')
			//   ];
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pengajuan_diskon'>";
		}
	}
}
