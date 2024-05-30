<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skema_kredit extends CI_Controller
{

	var $tables = "ms_diskon";
	var $folder = "dealer";
	var $page   = "skema_kredit";
	var $title  = "Skema Kredit";
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

		$data['event'] = $this->db->query("SELECT tr_prospek.*,tr_skema_kredit.*,tr_prospek.id_prospek,
			(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=id_finco) AS finco FROM tr_skema_kredit 
			JOIN tr_prospek ON tr_prospek.id_prospek=tr_skema_kredit.id_prospek
			WHERE tr_skema_kredit.id_dealer='$id_dealer'
			AND tr_skema_kredit.id_prospek NOT IN (SELECT tr_prospek.id_prospek FROM tr_sales_order
									JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
									JOIN tr_prospek ON tr_spk.id_customer=tr_prospek.id_customer
									WHERE tr_sales_order.id_dealer='$id_dealer'
									)
			and tr_skema_kredit.created_at > '2023-06-01'						
			ORDER BY tr_skema_kredit.created_at DESC");
		$this->template($data);
	}

	public function history()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "history";
		$id_dealer       = $this->m_admin->cari_dealer();

		$data['event'] = $this->db->query("SELECT tr_prospek.*,tr_skema_kredit.*,tr_prospek.id_prospek,
			(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=id_finco) AS finco FROM tr_skema_kredit 
			JOIN tr_prospek ON tr_prospek.id_prospek=tr_skema_kredit.id_prospek
			WHERE tr_skema_kredit.id_dealer='$id_dealer' 
			AND tr_skema_kredit.id_prospek IN (SELECT tr_prospek.id_prospek FROM tr_sales_order
									JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
									JOIN tr_prospek ON tr_spk.id_customer=tr_prospek.id_customer
									WHERE tr_sales_order.id_dealer='$id_dealer'
									)
			ORDER BY tr_skema_kredit.created_at DESC");
		$this->template($data);
	}

	public function add()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$id_dealer       = $this->m_admin->cari_dealer();

		$data['prospek'] = $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_dealer='$id_dealer' AND tr_prospek.rencana_pembayaran='kredit' AND id_prospek NOT IN (SELECT id_prospek FROM tr_skema_kredit WHERE id_dealer='$id_dealer')
			ORDER BY tr_prospek.created_at DESC");
		$data['finco'] = $this->db->query("SELECT id_finance_company,finance_company,alamat,no_telp,email FROM ms_finance_company WHERE active=1 ORDER BY finance_company");
		// $data['prospek'] = $this->db->get_where('tr_prospek',['id_dealer'=>$id_dealer]);
		$this->template($data);
	}

	public function save()
	{
		//validasi simpan
		$pesan_error = "";
		$otr = $this->input->post('harga_off_road') + $this->input->post('biaya_bbn');
		$hrg_persen = ($otr * 0.05);
		$dp_lainnya = preg_replace('/[^0-9\  ]/', '', $this->input->post('dp_lainnya'));
		$tenor_lainnya = preg_replace('/[^0-9\  ]/', '', $this->input->post('tenor_lainnya'));
		$angsuran = preg_replace('/[^0-9\  ]/', '', $this->input->post('angsuran'));
		if ($dp_lainnya < $hrg_persen || $dp_lainnya > $otr) {
			$pesan_error = "- Nominal DP Tidak Sesuai atau Harga OTR Motor Belum Ada.<br>";
		}

		if ($tenor_lainnya > 50) {
			$pesan_error .= "- Tenor angsuran tidak sesuai.<br>";
		}

		if ($angsuran < 100000) {
			$pesan_error .= "- Nominal angsuran tidak sesuai.<br>";
		}

		if ($pesan_error != '') {
			$pesan_error.='Silahkan coba kembali.';
			
			$_SESSION['pesan'] 	= $pesan_error;
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/skema_kredit/add'>";
			exit();
		}

		//
		
		$waktu    = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$post = $this->input->post();
		// send_json($post);
		$id_prospek = $data['id_prospek']   = $this->input->post('id_prospek');
		$prospek    = $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_prospek='$id_prospek'
		")->row();
		$harga = $this->getharga('save', $prospek->id_tipe_kendaraan, $prospek->id_warna);
		$data['id_finco']       = $this->input->post('id_finco');
		$data['harga_on_road']  = $harga['harga_on'];
		$data['harga_off_road'] = $harga['harga_jual'];
		$data['bbn']            = $harga['biaya_bbn'];
		$data['angsuran']       = preg_replace('/[^0-9\  ]/', '', $this->input->post('angsuran'));
		$dp = $this->input->post('dp');
		$tenor = $this->input->post('tenor');
		if ($dp == 'lainnya') {
			$dp = preg_replace('/[^0-9\  ]/', '', $this->input->post('dp_lainnya'));
			$tenor = $this->input->post('tenor_lainnya');
		}
		if ($tenor == 'lainnya') {
			$tenor = $this->input->post('tenor_lainnya');
		}
		$data['dp']             = $dp;
		$data['tenor']          = $tenor;
		// $data['dp_cek']         = $this->input->post('dp_cek');
		$data['id_dealer']      = $id_dealer;
		$data['created_at']     = $waktu;
		$data['created_by']     = $login_id;
		// send_json($data);

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
		$this->db->insert('tr_skema_kredit', $data);
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
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/skema_kredit'>";
		}
	}

	// public function email_event($email,$kode_event) { 
	// 	$from = $this->db->get_where('ms_email_md',['email_for'=>'notification'])->row(); 
	// 	$to_email   = $email; 

	// 	$config = Array(
	//          'protocol'  => 'smtp',
	//          'smtp_host' => 'ssl://mail.sinarsentosaprimatama.com',
	//          'smtp_port' => 465,
	//          'smtp_user' => $from->email,
	//          'smtp_pass' => $from->pass,
	//          'mailtype'  => 'html', 
	//          'charset'   => 'iso-8859-1');
	//        // $config = config_email($from_email);

	// 	$this->load->library('email', $config);
	// 	$this->email->set_newline("\r\n");   

	// 	$this->email->from($from->email, 'SINARSENTOSA'); 
	// 	$this->email->to($to_email);
	// 	$this->email->subject('[SINARSENTOSA] Approve Event Dealer'); 
	// 	$file_logo         = base_url('assets/panel/images/logo_sinsen.jpg');
	// 	$data['logo']      = $file_logo;
	// 	$data['kode_event'] = $kode_event;
	// 	$this->email->message($this->load->view('dealer/event_email', $data, true)); 

	//         //Send mail 
	//         if($this->email->send()){
	// 		return 'sukses';
	//         }else {
	// 		return 'gagal';
	//         } 
	// }

	public function detail()
	{
		$id_skema      = $this->input->get('id');

		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "form";
		$data['mode']  = 'detail';
		$id_dealer     = $this->m_admin->cari_dealer();

		$row = $data['row'] = $this->db->query("SELECT *,(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=id_finco) AS finco,tr_skema_kredit.tenor,tr_prospek.id_prospek,bbn as biaya_bbn FROM tr_skema_kredit
			LEFT JOIN tr_prospek ON tr_prospek.id_prospek=tr_skema_kredit.id_prospek
			WHERE id_skema=$id_skema ")->row();

		$data['prospek'] = $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna,tr_prospek.id_prospek FROM tr_prospek 
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_dealer='$id_dealer'  AND tr_prospek.rencana_pembayaran='kredit'
			ORDER BY tr_prospek.created_at DESC");

		$row_ = $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna,tr_prospek.id_prospek FROM tr_prospek 
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_prospek='$row->id_prospek' ")->row();
		$data['unit'][0] = [
			'id_tipe_kendaraan' => $row_->id_tipe_kendaraan,
			'tipe_ahm' => $row_->tipe_ahm,
			'id_warna' => $row_->id_warna,
			'warna' => $row_->warna,
		];
		$data['finco'] = $this->db->query("SELECT id_finance_company,finance_company,alamat,no_telp,email FROM ms_finance_company WHERE active=1 ORDER BY finance_company");
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
		$id_skema      = $this->input->get('id');

		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "form";
		$data['mode']  = 'edit';
		$id_dealer     = $this->m_admin->cari_dealer();

		$row = $data['row'] = $this->db->query("SELECT *,(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=id_finco) AS finco,tr_skema_kredit.tenor,bbn as biaya_bbn FROM tr_skema_kredit
			LEFT JOIN tr_prospek ON tr_prospek.id_prospek=tr_skema_kredit.id_prospek
			WHERE id_skema=$id_skema ")->row();

		$data['prospek'] = $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna,tr_prospek.id_prospek FROM tr_prospek 
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_dealer='$id_dealer'  AND tr_prospek.rencana_pembayaran='kredit'
			ORDER BY tr_prospek.created_at DESC");

		$row_ = $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna,tr_prospek.id_prospek FROM tr_prospek 
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_prospek='$row->id_prospek' ")->row();
		$data['unit'][0] = [
			'id_tipe_kendaraan' => $row_->id_tipe_kendaraan,
			'tipe_ahm' => $row_->tipe_ahm,
			'id_warna' => $row_->id_warna,
			'warna' => $row_->warna,
		];
		$data['finco'] = $this->db->query("SELECT id_finance_company,finance_company,alamat,no_telp,email FROM ms_finance_company WHERE active=1 ORDER BY finance_company");
		$this->template($data);
	}
	public function getharga($save = null, $id_tipe_kendaraan = null, $id_warna = null)
	{

		if ($id_tipe_kendaraan == null) {
			$id_tipe_kendaraan 	= $this->input->post("id_tipe_kendaraan");
			$id_warna	 					= $this->input->post("id_warna");
		}

		$tipe 							= "Customer Umum";

		$cek_bbn = $this->db->query("SELECT biaya_bbn FROM ms_bbn_dealer WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' and active ='1' order by created_at desc");

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
		$date = date('Y-m-d');

		/*
		$cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md 
			INNER JOIN ms_kelompok_harga ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
			WHERE ms_kelompok_md.id_item = '$id_item' 
			AND ms_kelompok_harga.target_market = '$tipe' 
			AND start_date <='$date'
			ORDER BY start_date DESC LIMIT 0,1");
		*/

		$cek_harga = $this->db->query("select a.tgl_approve , a.start_date , b.id_item , b.harga_jual , a.status from ms_kelompok_md_harga a
			join ms_kelompok_md_harga_detail b on a.id_kel  = b.id_kel 
			where b.id_item ='$id_item' and a.start_date  <= '$date' and a.id_kelompok_harga ='A'
			order by a.tgl_approve DESC LIMIT 0,1");


		if ($cek_harga->num_rows() > 0) {

			$ts = $cek_harga->row();

			$harga_jual = $ts->harga_jual;
		} else {

			$harga_jual = 0;
		}



		$harga 		= floor($harga_jual / 1.1);

		$ppn 			= floor(0.1 * $harga);

		$harga_on = $harga_jual + $biaya_bbn;

		$harga_tunai = $harga_on;


		$response = [
			'biaya_bbn' => $biaya_bbn,
			'harga_on' => $harga_on,
			'harga_jual' => $harga_jual,
			'ppn' => $ppn,
			'harga' => $harga,
			'harga_tunai' => $harga_tunai
		];
		if ($save != null) {
			return $response;
		} else {
			echo json_encode($response);
		}
		// echo $biaya_bbn."|".$harga_on."|".$harga_jual."|".$ppn."|".$harga."|".$harga_tunai;

	}

	public function save_edit()
	{
		$waktu    = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();

		$id_skema   = $this->input->post('id_skema');
		$id_prospek = $data['id_prospek']   = $this->input->post('id_prospek');
		$prospek    = $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_prospek='$id_prospek'
		")->row();
		$harga                  = $this->getharga('save', $prospek->id_tipe_kendaraan, $prospek->id_warna);
		$data['id_finco']       = $this->input->post('id_finco');
		$data['harga_on_road']  = $harga['harga_on'];
		$data['harga_off_road'] = $harga['harga_jual'];
		$data['bbn']            = $harga['biaya_bbn'];

		$dp    = $this->input->post('dp');
		$tenor = $this->input->post('tenor');
		if ($dp == 'lainnya') {
			$dp    = preg_replace('/[^0-9\  ]/', '', $this->input->post('dp_lainnya'));
			$tenor = $this->input->post('tenor_lainnya');
		}
		if ($tenor == 'lainnya') {
			$tenor = $this->input->post('tenor_lainnya');
		}
		$angsuran = preg_replace('/[^0-9\  ]/', '', $this->input->post('angsuran'));

		$data['tenor']             = $tenor;
		$data['angsuran']          = $angsuran;
		$data['dp']                = $dp;
		// $data['dp_cek']         = $this->input->post('dp_cek');
		$data['id_dealer']      = $id_dealer;
		$data['updated_at']     = $waktu;
		$data['updated_by']     = $login_id;
		$this->db->order_by('created_at', 'DESC');
		$cek_spk = $this->db->get_where('tr_spk', ['id_customer' => $prospek->id_customer]);
		if ($cek_spk->num_rows() > 0) {
			$spk    = $cek_spk->row();
			$no_spk = $spk->no_spk;
			$upd_spk = [
				'tenor' => $tenor,
				'angsuran' => $angsuran,
				'uang_muka' => $dp
			];
		}
		$this->db->trans_begin();
		$this->db->update('tr_skema_kredit', $data, ['id_skema' => $id_skema]);
		if (isset($upd_spk)) {
			$this->db->update('tr_spk', $upd_spk, ['no_spk' => $no_spk]);
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
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('dealer/diskon')
			//   ];
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/skema_kredit'>";
		}
	}

	public function getSimulasiKredit()
	{
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$simulasi = $this->db->query("SELECT * FROM ms_simulasi_kredit_detail AS skd 
			JOIN ms_simulasi_kredit ON skd.id_simulasi=ms_simulasi_kredit.id_simulasi
			WHERE id_tipe_kendaraan='$id_tipe_kendaraan' ORDER BY created_at DESC, uang_muka ASC")->result();
		echo json_encode($simulasi);
	}
	public function getTenorAngsuran()
	{
		$cukup_bayar = $this->input->post('cukup_bayar');
		$id_simulasi = $this->input->post('id_simulasi');
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna='$id_warna'")->row();
		$bundling = 'tidak';
		if ($cek_item->bundling == 'ya') {
			$bundling = 'ya';
		}

		$simulasi = $this->db->query("SELECT skda.id_simulasi,tenor,CASE WHEN '$bundling'='ya' THEN angsuran_bundling
			ELSE angsuran
			END AS angsuran
			FROM ms_simulasi_kredit_detail_angsuran AS skda
			JOIN ms_simulasi_kredit_detail AS skd ON skda.id_detail=skd.id_detail
			WHERE skda.id_simulasi='$id_simulasi' AND cukup_bayar='$cukup_bayar' ORDER BY tenor ASC")->result();
		echo json_encode($simulasi);
	}
}
