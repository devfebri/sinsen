<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Print_receipt extends CI_Controller
{

	var $tables = "tr_invoice_pelunasan";
	var $folder = "dealer";
	var $page   = "print_receipt";
	var $title  = "Print Receipt";

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
		$this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
		$this->load->model('m_h1_dealer_spk', 'm_spk');

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
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index";
		$this->template($data);
	}

	public function fetch_tjs()
	{
		$fetch_data = $this->make_query_tjs();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array     = array();
			$button = '';
			$btn_cetak = "<a data-toggle='tooltip' title='Cetak Kwitansi TJS' href='dealer/print_receipt/cetak_tjs?id=$rs->id_invoice'><button class='btn btn-flat btn-xs btn-success'><i class='fa fa-print'></i></button></a>";
			$btn_proses = "<a data-toggle='tooltip' title='Proses Receipt TJS' href='dealer/print_receipt/add_tjs?id=$rs->id_invoice' class='btn btn-flat btn-xs btn-info'><i class='fa fa-spinner'></i></a>";
			$status = '';
			if ($rs->status == 'input') {
				$status = '<label class="label label-primary">Input</label>';
				// if (can_access($this->page, 'can_update'))  
				$button .= $btn_proses;
			}
			if ($rs->status == 'close') {
				$status = '<label class="label label-warning">Closed</label>';
				if (can_access($this->page, 'can_print'))  $button .= $btn_cetak;
			}

			$sub_array[] = "<a data-toggle='tooltip' href='dealer/print_receipt/detail_tjs?id=$rs->id_invoice'>$rs->id_invoice</a>";
			$filter = [
				'jenis_invoice' => 'tjs',
				'id_invoice' => $rs->id_invoice,
				'select' => ''
			];
			$kwitansi = $this->m_bayar->getDealerInvoiceReceipt($filter);
			$no_kwitansi = $kwitansi->num_rows() > 0 ? $kwitansi->row()->id_kwitansi : '';
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $no_kwitansi;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->jenis_beli;
			$sub_array[] = mata_uang_rp($rs->tanda_jadi);
			$sub_array[] = $rs->created_at;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_tjs(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function make_query_tjs($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'recipt',
			'search' => $this->input->post('search')['value'],
			'select' => 'view_invoice_tjs'
		];
		if ($recordsFiltered == true) {
			return $this->m_bayar->getInvoiceTJS($filter)->num_rows();
		} else {
			return $this->m_bayar->getInvoiceTJS($filter)->result();
		}
	}

	public function add_tjs()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title . ' Tanda Jadi Sementara';
		$data['mode']  = 'insert';
		$data['set']   = "form";
		$id_invoice = $this->input->get('id');
		$filter = [
			'id_invoice_tjs' => $id_invoice,
			'status_in' => "'input'"
		];
		$get_data = $this->m_bayar->getInvoiceTJS($filter);
		if ($get_data->num_rows() > 0) {
			$data['jenis_invoice'] = 'tjs';
			$row = $data['row'] = $get_data->row();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];

			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);

			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data sudah dilakukan proses lain !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt'>";
		}
	}

	public function save_tjs()
	{
		$post        = $this->input->post();
		$id_kwitansi = $this->m_bayar->get_id_kwitansi($post['jenis_invoice']);
		$id_dealer   = $this->m_admin->cari_dealer();
		$dt_bayar    = $this->input->post('dt_bayar');
		$id_user     = user()->id_user;
		$cara_bayar  = [];
		$amount      = 0;
		// send_json($post);
		foreach ($dt_bayar as $rs) {
			$cara_bayar[] = $rs['metode_penerimaan'];
			$amount += $rs['nominal'];
			$details_bayar[] = [
				'id_kwitansi'        => $id_kwitansi,
				'metode_penerimaan' => $rs['metode_penerimaan'],
				'nominal'           => $rs['nominal'],
				'no_bg_cek'         => $rs['no_bg_cek'] == '' ? null : $rs['no_bg_cek'],
				'id_bank'           => $rs['id_bank']   == '' ? null : $rs['id_bank'],
				'tgl_terima'        => $rs['tgl_terima'],
			];
		}
		$nominal_lebih = isset($post['nominal_lebih']) ? $post['nominal_lebih'] : 0;
		$amount        = $amount - $nominal_lebih;
		$data 	= [
			'id_invoice'       => $post['id_invoice'],
			'id_kwitansi'      => $id_kwitansi,
			'jenis_invoice'    => $post['jenis_invoice'],
			'no_spk'           => $post['no_spk'],
			'id_dealer'        => $id_dealer,
			'tgl_pembayaran'   => get_ymd(),
			'cara_bayar'       => implode(', ', $cara_bayar),
			'amount'           => $amount,
			'nominal_lebih'    => isset($post['nominal_lebih']) ? $post['nominal_lebih']      : NULL,
			'kode_coa'         => isset($post['kode_coa']) ? $post['kode_coa']                : NULL,
			'keterangan_lebih' => isset($post['keterangan_lebih']) ? $post['keterangan_lebih'] : NULL,
			'note'             => $this->input->post('note'),
			'created_at'       => waktu_full(),
			'created_by'       => $id_user
		];
		$upd_tjs = ['status' => 'close', 'closed_at' => waktu_full(), 'closed_by' => $id_user];
		// $tes = ['data' => $data, 'details' => $details_bayar];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_h1_dealer_invoice_receipt', $data);
		if (isset($details_bayar)) {
			$this->db->insert_batch('tr_h1_dealer_invoice_receipt_pembayaran', $details_bayar);
		}
		$this->db->update('tr_invoice_tjs', $upd_tjs, ['id_invoice' => $post['id_invoice']]);
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
				'link' => base_url('dealer/print_receipt')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function detail_tjs()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title . ' Tanda Jadi Sementara';
		$data['mode']  = 'detail_tjs';
		$data['set']   = "form";
		$id_invoice = $this->input->get('id');
		$filter = [
			'id_invoice_tjs' => $id_invoice
		];
		$get_data = $this->m_bayar->getInvoiceTJS($filter);
		if ($get_data->num_rows() > 0) {
			$data['jenis_invoice'] = 'tjs';
			$row = $data['row'] = $get_data->row();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];

			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);
			$filter = ['id_invoice' => $id_invoice];
			$data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data sudah dilakukan proses lain !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt'>";
		}
	}

	public function cetak_tjs()
	{

		$id_user  = $this->session->userdata('id_user');
		$id_invoice_tjs = $this->input->get('id');
		$this->load->library('mpdf_l');
		$filter = [
			'id_invoice_tjs' => $id_invoice_tjs,
			'status_in' => "'close'"
		];
		$get_data = $this->m_bayar->getInvoiceTJS($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => waktu_full(),
				'print_by' => $id_user,
			];
			$this->db->update('tr_h1_dealer_invoice_receipt', $upd, ['id_kwitansi' => $row->id_kwitansi]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			// Kirim Indent
			$this->db->join("tr_prospek prospek", "prospek.id_customer=spk.id_customer");
			$this->db->join("tr_po_dealer_indent indent", "indent.id_spk=spk.no_spk");
			$this->db->where('spk.no_spk', $row->no_spk);
			$this->db->where("spk.send_ce_indent_at IS NULL");
			$cek_spk = $this->db->get("tr_spk spk")->row();
			if ($cek_spk != null) {
				$this->db_crm = $this->load->database('db_crm', true);
				$get_leads = $this->db_crm->get_where("leads", ['idProspek' => $cek_spk->id_prospek])->row();

				$this->load->model('mokita_model');
				$last_status_ce_apps = $this->mokita_model->last_tracking($row->no_spk);
				$array_post = [
					'AppsOrderNumber'   => $get_leads->sourceRefID,
					'DmsOrderNumber'    => $get_leads->batchID,
					'CustomerPhoneNumber' => $cek_spk->no_hp,
					'CreditStatus' => $last_status_ce_apps ? $last_status_ce_apps->CreditStatus : '',
					'IndentStatus' => 'Motor dalam proses indent',
					'DeliveryStatus' => '',
					'EstimatedDeliveryDate' => $cek_spk->tgl_pengiriman,
					'EngineNumber' => '',
					'StnkStatus' => '',
					'BpkbStatus' => '',
					'VehicleNumber' => '',
				];

				$this->mokita_model->set_tracking($row->no_spk, $array_post);
				$this->db->update("tr_spk", ['send_ce_indent_at' => waktu_full()], ['no_spk' => $cek_spk->no_spk]);
				// send_json($array_post);
				if ($cek_spk->input_from == 'sinsengo') {
					$this->load->library("mokita");
					$this->mokita->h1_credit_approval_indent_delivery_stnk_bpkb($array_post);
				}
			}

			$data['set'] = 'print_tjs';
			$data['row'] = $row;
			$filter = ['id_kwitansi' => $row->id_kwitansi];
			$data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];
			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);
			// send_json($data);
			$html = $this->load->view('dealer/print_receipt_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt'>";
		}
	}

	public function dp()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index_dp";
		$this->template($data);
	}

	public function fetch_dp()
	{
		$fetch_data = $this->make_query_dp();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array     = array();
			$button = '';
			$btn_proses = "<a data-toggle='tooltip' title='Proses Receipt Credit' href='dealer/print_receipt/add_dp?id=$rs->id_invoice_dp' class='btn btn-flat btn-xs btn-info'><i class='fa fa-spinner'></i></a>";
			$btn_riwayat = "<button data-toggle='tooltip' title='Riwayat Penerimaan' class='btn btn-flat btn-xs btn-success' onclick=\"showModalRiwayatPenerimaanPembayaran('$rs->no_spk')\"><i class='fa fa-list'></i></button>";
			$status = '';
			if ($rs->status == 'input') {
				$status = '<label class="label label-primary">Input</label>';
				// if (can_access($this->page, 'can_update'))  
				if ($rs->sisa_pelunasan > 0) {
					$button .= $btn_proses;
				}
			}
			if ($rs->status == 'close') {
				$status = '<label class="label label-warning">Closed</label>';
				// if (can_access($this->page, 'can_print'))  $button .= $btn_cetak;
			}
			$button .= ' ' . $btn_riwayat;

			$sub_array[] = "<a data-toggle='tooltip' href='dealer/print_receipt/detail_dp?id=$rs->id_invoice_dp'>$rs->id_invoice_dp</a>";
			$sub_array[] = $rs->id_sales_order;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = mata_uang_rp($rs->amount_dp);
			$sub_array[] = mata_uang_rp($rs->total_bayar);
			$sub_array[] = mata_uang_rp($rs->summary_terima);
			$sub_array[] = mata_uang_rp($rs->sisa_pelunasan);
			$sub_array[] = $rs->created_at;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_dp(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function make_query_dp($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'recipt',
			'search' => $this->input->post('search')['value'],
		];
		if (isset($_POST['summary_terima'])) {
			$filter['summary_terima'] = $_POST['summary_terima'];
		}
		if ($recordsFiltered == true) {
			$filter['select'] = 'count_no_spk';
			return $this->m_bayar->getInvoiceDP($filter)->row()->count;
			// return $this->m_bayar->getInvoiceDP($filter)->num_rows();
			// return 1000;
		} else {
			return $this->m_bayar->getInvoiceDP_v2($filter)->result();
		}
	}

	public function add_dp()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title . ' Credit';
		$data['mode']  = 'insert';
		$data['set']   = "form";
		$id_invoice = $this->input->get('id');
		$filter = [
			'id_invoice_dp' => $id_invoice,
			'status_in' => "'input'",
			'summary_terima' => true,
			'sisa_lebih_dari_nol' => true
		];
		$get_data = $this->m_bayar->getInvoiceDP($filter);
		if ($get_data->num_rows() > 0) {
			$data['jenis_invoice'] = 'dp';
			$row = $data['row'] = $get_data->row();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];
			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data sudah dilakukan proses lain !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt'>";
		}
	}

	public function save_dp()
	{
		$post        = $this->input->post();
		// send_json($post);
		$id_kwitansi = $this->m_bayar->get_id_kwitansi($post['jenis_invoice']);
		$id_dealer   = $this->m_admin->cari_dealer();
		$dt_bayar    = $this->input->post('dt_bayar');
		$id_user     = user()->id_user;
		$cara_bayar  = [];
		$amount      = 0;
		foreach ($dt_bayar as $rs) {
			$cara_bayar[] = $rs['metode_penerimaan'];
			$amount += $rs['nominal'];
			$details_bayar[] = [
				'id_kwitansi'        => $id_kwitansi,
				'metode_penerimaan' => $rs['metode_penerimaan'],
				'nominal'           => $rs['nominal'],
				'no_bg_cek'         => $rs['no_bg_cek'] == '' ? null : $rs['no_bg_cek'],
				'id_bank'           => $rs['id_bank']   == '' ? null : $rs['id_bank'],
				'tgl_terima'        => $rs['tgl_terima'],
			];
		}

		$nominal_lebih = isset($post['nominal_lebih']) ? $post['nominal_lebih'] : NULL;
		$nominal_lebih = $nominal_lebih > 0 ? $nominal_lebih : NULL;

		$amount_nett = $amount - $nominal_lebih;
		$data 	= [
			'id_invoice'       => $post['id_invoice'],
			'id_kwitansi'      => $id_kwitansi,
			'jenis_invoice'    => $post['jenis_invoice'],
			'no_spk'           => $post['no_spk'],
			'id_dealer'        => $id_dealer,
			'tgl_pembayaran'   => get_ymd(),
			'cara_bayar'       => implode(', ', $cara_bayar),
			'amount'           => $amount_nett,
			'note'             => $this->input->post('note'),
			'created_at'       => waktu_full(),
			'created_by'       => $id_user,
			'nominal_lebih'    => $nominal_lebih,
			'kode_coa'         => isset($post['kode_coa']) ? $post['kode_coa']                : NULL,
			'keterangan_lebih' => isset($post['keterangan_lebih']) ? $post['keterangan_lebih'] : NULL,
		];
		if ($post['sisa'] <= $amount_nett) {
			$upd_dp = ['status' => 'close', 'closed_at' => waktu_full(), 'closed_by' => $id_user];
		}

		// $tes = ['data' => $data, 'details' => $details_bayar, 'upd_dp' => isset($upd_dp) ? $upd_dp : NULL];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_h1_dealer_invoice_receipt', $data);
		if (isset($details_bayar)) {
			$this->db->insert_batch('tr_h1_dealer_invoice_receipt_pembayaran', $details_bayar);
		}
		if (isset($upd_dp)) {
			$this->db->update('tr_invoice_dp', $upd_dp, ['id_invoice_dp' => $post['id_invoice']]);
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
				'link' => base_url('dealer/print_receipt/dp')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function detail_dp()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title . ' Credit';
		$data['mode']  = 'detail_dp';
		$data['set']   = "form";
		$id_invoice = $this->input->get('id');
		$filter = [
			'id_invoice_dp' => $id_invoice
		];
		$get_data = $this->m_bayar->getInvoiceDP($filter);
		if ($get_data->num_rows() > 0) {
			$data['jenis_invoice'] = 'dp';
			$row = $data['row'] = $get_data->row();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];

			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);

			$filter = ['id_invoice' => $id_invoice];
			// $data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data sudah dilakukan proses lain !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt'>";
		}
	}



	public function cetak_dp()
	{
		$this->load->library('mpdf_l');
		$login_id  = $this->session->userdata('id_user');
		$id_kwitansi = $this->input->get('id');

		$filter = [
			'id_kwitansi' => $id_kwitansi
		];
		$get_data = $this->m_bayar->getDealerInvoiceReceipt($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => waktu_full(),
				'print_by' => $login_id,
			];
			$upd_so = [
				'is_paid' => 1,
				'paid_at' => waktu_full(),
				'paid_by' => $login_id,
			];
			$this->db->trans_begin();
			$this->db->update('tr_h1_dealer_invoice_receipt', $upd, ['id_kwitansi' => $id_kwitansi]);
			$this->db->update('tr_sales_order', $upd_so, ['no_spk' => $row->no_spk]);
			$this->db->update('tr_sales_order_gc', $upd_so, ['no_spk_gc' => $row->no_spk]);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
			} else {
				$this->db->trans_commit();

				$mpdf                           = $this->mpdf_l->load();
				$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
				$mpdf->charset_in               = 'UTF-8';
				$mpdf->autoLangToFont           = true;

				$data['set'] = 'print_dp';
				$data['row'] = $row;
				$filter = ['id_kwitansi' => $id_kwitansi];
				$data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();
				$filter['no_spk'] = $row->no_spk;
				$data['dt_no_mesin'] = $this->m_bayar->getSODetailNoMesin($filter);
				$data['dp'] = $this->m_bayar->getInvoiceDP($filter)->row();
				// send_json($data);
				$html = $this->load->view('dealer/print_receipt_cetak', $data, true);
				// render the view into HTML
				$mpdf->WriteHTML($html);
				// write the HTML into the mpdf
				$output = 'cetak_.pdf';
				$mpdf->Output("$output", 'I');
			}
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
		}
	}

	public function pelunasan()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index_pelunasan";
		$this->template($data);
	}

	public function fetch_pelunasan()
	{
		$fetch_data = $this->make_query_pelunasan();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array     = array();
			$button = '';
			$btn_riwayat = "<button data-toggle='tooltip' title='Riwayat Penerimaan' class='btn btn-flat btn-xs btn-success' onclick=\"showModalRiwayatPenerimaanPembayaran('$rs->no_spk')\"><i class='fa fa-list'></i></button>";
			$btn_proses = "<a data-toggle='tooltip' title='Proses Receipt Pelunasan' href='dealer/print_receipt/add_pelunasan?id=$rs->id_inv_pelunasan' class='btn btn-flat btn-xs btn-info'><i class='fa fa-spinner'></i></a>";
			$status = '';
			if ($rs->status == 'input') {
				$status = '<label class="label label-primary">Input</label>';
				// if (can_access($this->page, 'can_update'))  
				$button .= $btn_proses;
			}
			if ($rs->status == 'close') {
				$status = '<label class="label label-warning">Closed</label>';
				// if (can_access($this->page, 'can_print'))  $button .= $btn_cetak;
			}
			$button .= ' ' . $btn_riwayat;

			$sub_array[] = "<a data-toggle='tooltip' href='dealer/print_receipt/detail_pelunasan?id=$rs->id_inv_pelunasan'>$rs->id_inv_pelunasan</a>";
			$sub_array[] = $rs->id_sales_order;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = mata_uang_rp($rs->total_bayar + $rs->diskon);
			$sub_array[] = mata_uang_rp($rs->summary_terima);
			$sub_array[] = mata_uang_rp($rs->sisa_pelunasan);
			$sub_array[] = $rs->created_at;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_pelunasan(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function make_query_pelunasan($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'recipt',
			'search' => $this->input->post('search')['value'],
		];
		if (isset($_POST['summary_terima'])) {
			$filter['summary_terima'] = $_POST['summary_terima'];
		}
		if ($recordsFiltered == true) {
			// return $this->m_bayar->getInvoicePelunasan($filter)->num_rows();
			$filter['select'] = 'count_no_spk';
			return $this->m_bayar->getInvoicePelunasan($filter)->row()->count;
		} else {
			return $this->m_bayar->getInvoicePelunasan_v2($filter)->result();
		}
	}

	public function add_pelunasan()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title . ' Cash';
		$data['mode']  = 'insert';
		$data['set']   = "form";
		$id_invoice = $this->input->get('id');
		$filter = [
			'id_inv_pelunasan' => $id_invoice,
			'status_in' => "'input'",
			'summary_terima' => true,
			'sisa_lebih_dari_nol' => true
		];
		$get_data = $this->m_bayar->getInvoicePelunasan($filter);
		if ($get_data->num_rows() > 0) {
			$data['jenis_invoice'] = 'pelunasan';
			$row = $data['row'] = $get_data->row();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];
			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data sudah dilakukan proses lain !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/pelunasan'>";
		}
	}

	public function save_pelunasan()
	{
		$post        = $this->input->post();
		// send_json($post);
		$id_kwitansi = $this->m_bayar->get_id_kwitansi($post['jenis_invoice']);
		$id_dealer   = $this->m_admin->cari_dealer();
		$dt_bayar    = $this->input->post('dt_bayar');
		$id_user     = user()->id_user;
		$cara_bayar  = [];
		$amount      = 0;
		foreach ($dt_bayar as $rs) {
			$cara_bayar[] = $rs['metode_penerimaan'];
			$amount += $rs['nominal'];
			$details_bayar[] = [
				'id_kwitansi'        => $id_kwitansi,
				'metode_penerimaan' => $rs['metode_penerimaan'],
				'nominal'           => $rs['nominal'],
				'no_bg_cek'         => $rs['no_bg_cek'] == '' ? null : $rs['no_bg_cek'],
				'id_bank'           => $rs['id_bank']   == '' ? null : $rs['id_bank'],
				'tgl_terima'        => $rs['tgl_terima'],
			];
		}

		$nominal_lebih = isset($post['nominal_lebih']) ? $post['nominal_lebih'] : NULL;
		$nominal_lebih = $nominal_lebih > 0 ? $nominal_lebih : NULL;

		$amount_nett = $amount - $nominal_lebih;
		$data 	= [
			'id_invoice'       => $post['id_invoice'],
			'id_kwitansi'      => $id_kwitansi,
			'jenis_invoice'    => $post['jenis_invoice'],
			'no_spk'           => $post['no_spk'],
			'id_dealer'        => $id_dealer,
			'tgl_pembayaran'   => get_ymd(),
			'cara_bayar'       => implode(', ', $cara_bayar),
			'amount'           => $amount_nett,
			'note'             => $this->input->post('note'),
			'created_at'       => waktu_full(),
			'created_by'       => $id_user,
			'nominal_lebih'    => $nominal_lebih,
			'kode_coa'         => isset($post['kode_coa']) ? $post['kode_coa']                : NULL,
			'keterangan_lebih' => isset($post['keterangan_lebih']) ? $post['keterangan_lebih'] : NULL,
		];
		if ($post['sisa'] <= $amount_nett) {
			$upd_dp = ['status' => 'close', 'closed_at' => waktu_full(), 'closed_by' => $id_user];
		}

		// $tes = ['data' => $data, 'details' => $details_bayar, 'upd_dp' => isset($upd_dp) ? $upd_dp : NULL];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_h1_dealer_invoice_receipt', $data);
		if (isset($details_bayar)) {
			$this->db->insert_batch('tr_h1_dealer_invoice_receipt_pembayaran', $details_bayar);
		}
		if (isset($upd_dp)) {
			$this->db->update('tr_invoice_pelunasan', $upd_dp, ['id_inv_pelunasan' => $post['id_invoice']]);
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
				'link' => base_url('dealer/print_receipt/pelunasan')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function detail_pelunasan()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title . ' Cash';
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_invoice = $this->input->get('id');
		$filter = [
			'id_inv_pelunasan' => $id_invoice,
			'summary_terima' => true,
		];
		$get_data = $this->m_bayar->getInvoicePelunasan($filter);
		if ($get_data->num_rows() > 0) {
			$data['jenis_invoice'] = 'pelunasan';
			$row = $data['row'] = $get_data->row();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];
			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data sudah dilakukan proses lain !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/pelunasan'>";
		}
	}

	public function cetak_pelunasan()
	{
		$this->load->library('mpdf_l');
		$login_id  = $this->session->userdata('id_user');
		$id_kwitansi = $this->input->get('id');

		$filter = [
			'id_kwitansi' => $id_kwitansi
		];
		$get_data = $this->m_bayar->getDealerInvoiceReceipt($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => waktu_full(),
				'print_by' => $login_id,
			];
			$upd_so = [
				'is_paid' => 1,
				'paid_at' => waktu_full(),
				'paid_by' => $login_id,
			];
			$this->db->trans_begin();
			$this->db->update('tr_h1_dealer_invoice_receipt', $upd, ['id_kwitansi' => $id_kwitansi]);
			$this->db->update('tr_sales_order', $upd_so, ['no_spk' => $row->no_spk]);
			$this->db->update('tr_sales_order_gc', $upd_so, ['no_spk_gc' => $row->no_spk]);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
			} else {
				$this->db->trans_commit();
				$mpdf                           = $this->mpdf_l->load();
				$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
				$mpdf->charset_in               = 'UTF-8';
				$mpdf->autoLangToFont           = true;

				$data['set'] = 'print_pelunasan';
				$data['row'] = $row;
				$filter = ['id_kwitansi' => $id_kwitansi];
				$data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();

				$filter['no_spk'] = $row->no_spk;
				$data['dt_no_mesin'] = $this->m_bayar->getSODetailNoMesin($filter);
				$data['lunas'] = $this->m_bayar->getInvoicePelunasan($filter)->row();
				// send_json($data);
				// if($id_kwitansi=='KWT/00888/23/03483' || $id_kwitansi=='KWT/00888/23/03505'){
				// 	$html = $this->load->view('dealer/print_receipt_cetak_edit', $data, true);
				// }else{
				$html = $this->load->view('dealer/print_receipt_cetak', $data, true);
				// }
				// render the view into HTML
				$mpdf->WriteHTML($html);
				// write the HTML into the mpdf
				$output = 'cetak_.pdf';
				$mpdf->Output("$output", 'I');
			}
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
		}
	}

	function inject_pelunasan()
	{
		$search = $this->db->query("SELECT ln.id_inv_pelunasan,ln.id_spk
		FROM tr_invoice_pelunasan ln
		WHERE ln.nama_konsumen IS NULL
		-- ORDER BY ln.id_spk DESC
		-- LIMIT 100
		");
		$no = 1;
		foreach ($search->result() as $rs) {
			$filter = ['no_spk' => $rs->id_spk];
			$spk = $this->m_spk->getSPK($filter);
			$so = $this->db->get_where('tr_sales_order', ['no_spk' => $rs->id_spk]);
			$upd[] = [
				'id_inv_pelunasan' => $rs->id_inv_pelunasan,
				'nama_konsumen' => $spk->num_rows() > 0 ? $spk->row()->nama_konsumen : null,
				'id_karyawan_dealer' => $spk->num_rows() > 0 ? $spk->row()->id_karyawan_dealer : null,
				'jenis_beli' => $spk->num_rows() > 0 ? $spk->row()->jenis_beli : null,
				'jenis_spk' => 'individu',
				'no_hp' => $spk->num_rows() > 0 ? $spk->row()->no_hp : null,
				'no_ktp' => $spk->num_rows() > 0 ? $spk->row()->no_ktp : null,
				'tgl_spk' => $spk->num_rows() > 0 ? $spk->row()->tgl_spk : null,
				'alamat' => $spk->num_rows() > 0 ? $spk->row()->alamat : null,
				'amount_tjs' => $spk->num_rows() > 0 ? $spk->row()->tanda_jadi : null,
				'amount_dp' => $spk->num_rows() > 0 ? $spk->row()->dp_stor : null,
				'amount_pelunasan' => $spk->num_rows() > 0 ? $spk->row()->total_bayar : null,
				'diskon' => $spk->num_rows() > 0 ? $spk->row()->diskon : null,
				'id_sales_order' => $so->num_rows() > 0 ? $so->row()->id_sales_order : null,
				'id_customer' => $spk->num_rows() > 0 ? $spk->row()->id_customer : null,
				'id_spk' => $rs->id_spk
			];
			$no++;
		}
		// $this->db->update_batch('tr_invoice_pelunasan', $upd, 'id_inv_pelunasan');
		// echo $no;
		send_json($upd);
	}
	function inject_tjs($limit, $exec = null)
	{
		$limit = $limit > 0 ? "LIMIT $limit" : '';
		$search = $this->db->query("SELECT tjs.id_invoice,tjs.id_spk
		FROM tr_invoice_tjs tjs
		WHERE tjs.nama_konsumen IS NULL
		-- ORDER BY ln.id_spk DESC
		$limit
		");
		$no = 1;
		foreach ($search->result() as $rs) {
			$filter = ['no_spk' => $rs->id_spk];
			$spk = $this->m_spk->getSPK($filter);
			$so = $this->db->get_where('tr_sales_order', ['no_spk' => $rs->id_spk]);
			$upd[] = [
				'id_invoice' => $rs->id_invoice,
				'nama_konsumen' => $spk->num_rows() > 0 ? $spk->row()->nama_konsumen : null,
				'id_karyawan_dealer' => $spk->num_rows() > 0 ? $spk->row()->id_karyawan_dealer : null,
				'id_customer' => $spk->num_rows() > 0 ? $spk->row()->id_customer : null,
				'jenis_beli' => $spk->num_rows() > 0 ? $spk->row()->jenis_beli : null,
				'jenis_spk' => 'individu',
				'no_hp' => $spk->num_rows() > 0 ? $spk->row()->no_hp : null,
				'no_ktp' => $spk->num_rows() > 0 ? $spk->row()->no_ktp : null,
				'tgl_spk' => $spk->num_rows() > 0 ? $spk->row()->tgl_spk : null,
				'alamat' => $spk->num_rows() > 0 ? $spk->row()->alamat : null,
				'amount' => $spk->num_rows() > 0 ? $spk->row()->tanda_jadi : null,
				'total_bayar' => $spk->num_rows() > 0 ? $spk->row()->total_bayar : null,
				'diskon' => $spk->num_rows() > 0 ? $spk->row()->diskon : null
			];
			$no++;
		}
		if ($exec != NULL) {
			// $this->db->update_batch('tr_invoice_tjs', $upd, 'id_invoice');
			echo $no;
		} else {
			send_json($upd);
		}
	}
	function inject_dp($limit, $exec = null)
	{
		$limit = $limit > 0 ? "LIMIT $limit" : '';
		$search = $this->db->query("SELECT dp.id_invoice_dp,dp.id_spk
		FROM tr_invoice_dp dp
		WHERE dp.nama_konsumen IS NULL
		-- ORDER BY ln.id_spk DESC
		$limit
		");
		$no = 1;
		foreach ($search->result() as $rs) {
			$filter = ['no_spk' => $rs->id_spk];
			$spk = $this->m_spk->getSPK($filter);
			$so = $this->db->get_where('tr_sales_order', ['no_spk' => $rs->id_spk]);
			$upd[] = [
				'id_invoice_dp' => $rs->id_invoice_dp,
				'nama_konsumen' => $spk->num_rows() > 0 ? $spk->row()->nama_konsumen : null,
				'id_karyawan_dealer' => $spk->num_rows() > 0 ? $spk->row()->id_karyawan_dealer : null,
				'id_customer' => $spk->num_rows() > 0 ? $spk->row()->id_customer : null,
				'id_sales_order' => $so->num_rows() > 0 ? $so->row()->id_sales_order : null,
				'jenis_beli' => $spk->num_rows() > 0 ? $spk->row()->jenis_beli : null,
				'jenis_spk' => 'individu',
				'no_hp' => $spk->num_rows() > 0 ? $spk->row()->no_hp : null,
				'no_ktp' => $spk->num_rows() > 0 ? $spk->row()->no_ktp : null,
				'tgl_spk' => $spk->num_rows() > 0 ? $spk->row()->tgl_spk : null,
				'alamat' => $spk->num_rows() > 0 ? $spk->row()->alamat : null,
				'amount_tjs' => $spk->num_rows() > 0 ? $spk->row()->tanda_jadi : null,
				'amount_dp' => $spk->num_rows() > 0 ? $spk->row()->dp_stor : null,
				'total_bayar' => $spk->num_rows() > 0 ? $spk->row()->total_bayar : null,
				'diskon' => $spk->num_rows() > 0 ? $spk->row()->diskon : null,
				'id_spk' => $rs->id_spk
			];
			$no++;
		}
		if ($exec != NULL) {
			// $this->db->update_batch('tr_invoice_dp', $upd, 'id_invoice_dp');
			echo $no;
		} else {
			send_json($upd);
		}
	}
	// function inject_so($jenis_beli, $limit, $exec = null)
	// {
	// 	$limit = $limit > 0 ? "LIMIT $limit" : '';
	// 	$cek = $this->db->query("SELECT so.id_sales_order,ln.id_sales_order so_ln,dp.id_sales_order so_dp,is_paid FROM tr_sales_order so
	// 	LEFT JOIN tr_invoice_pelunasan ln ON so.id_sales_order=ln.id_sales_order
	// 	LEFT JOIN tr_invoice_dp dp ON so.id_sales_order=dp.id_sales_order
	// 	WHERE is_paid=0 AND (dp.jenis_beli='$jenis_beli' OR ln.jenis_beli='$jenis_beli')
	// 	$limit
	// 	");
	// 	$no = 0;
	// 	foreach ($cek->result() as $rs) {
	// 		$upd[] = [
	// 			'id_sales_order' => $rs->id_sales_order,
	// 			'is_paid' => 1
	// 		];
	// 		$no++;
	// 	}
	// 	if ($exec != NULL) {
	// 		// $this->db->update_batch('tr_sales_order', $upd, 'id_sales_order');
	// 		echo $no;
	// 	} else {
	// 		send_json($upd);
	// 	}
	// }
	function cek_dp()
	{
		$filter = [
			'status_in' => "'input'",
			'summary_terima' => true,
			'sisa_lebih_dari_nol' => true
		];
		$get_data = $this->m_bayar->getInvoiceDP($filter);
		foreach ($get_data->result() as $rs) {
			$filter_detail = [
				'no_spk' => $rs->no_spk,
				'jenis_spk' => $rs->jenis_spk,
			];
			$spk = $this->m_spk->getSPKAllDetail($filter_detail)->row();
			if ($spk->total_bayar != $rs->total_bayar) {
				$result[] = [
					'id_invoice_dp' => $rs->id_invoice_dp,
					'no_spk' => $rs->no_spk,
					'tot_bayar_di_dp' => $rs->total_bayar,
					'tot_bayar_di_spk' => $spk->total_bayar,
				];
			}
		}
		send_json(($result));
	}
	function cek_harga()
	{
		$get = $this->input->get();
		$dp = $this->db->query("SELECT total_bayar,id_spk 
		from tr_invoice_dp 
		WHERE LEFT(created_at,7)='{$get['bulan']}' 
		ORDER BY created_at DESC
		LIMIT 500
		");
		$kosong = 0;
		foreach ($dp->result() as $d) {
			// send_json($d);
			$spk = $this->m_spk->getSPK(['no_spk' => $d->id_spk]);
			if ($spk->num_rows() > 0) {
				$spk = $spk->row();
				if ($d->total_bayar != $spk->total_bayar) {
					$show[] = ['no_spk' => $d->id_spk, 'dp_tot' => $d->total_bayar, 'spk_tot' => $spk->total_bayar];
				}
			} else {
				$kosong++;
			}
		}
		$show = ['kosong' => $kosong, 'show' => isset($show) ? $show : NULL];
		send_json($show);
	}


	public function cetak_tjs_test()
	{

		$id_user  = $this->session->userdata('id_user');
		$id_invoice_tjs = $this->input->get('id');
		$this->load->library('mpdf_l');
		$filter = [
			'id_invoice_tjs' => $id_invoice_tjs,
			'status_in' => "'close'"
		];
		$get_data = $this->m_bayar->getInvoiceTJS($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => waktu_full(),
				'print_by' => $id_user,
			];
			// $this->db->update('tr_h1_dealer_invoice_receipt', $upd, ['id_kwitansi' => $row->id_kwitansi]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print_tjs';
			$data['row'] = $row;
			$filter = ['id_kwitansi' => $row->id_kwitansi];
			$data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();
			$filter_detail = [
				'no_spk' => $row->no_spk,
				'jenis_spk' => $row->jenis_spk,
			];
			$data['details'] = $this->m_spk->getSPKAllDetail($filter_detail);
			// send_json($data);
			$html = $this->load->view('dealer/print_receipt_cetak1', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt'>";
		}
	}

	public function cetak_dp_test()
	{
		$this->load->library('mpdf_l');
		$login_id  = $this->session->userdata('id_user');
		$id_kwitansi = $this->input->get('id');

		$filter = [
			'id_kwitansi' => $id_kwitansi
		];
		$get_data = $this->m_bayar->getDealerInvoiceReceipt($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => waktu_full(),
				'print_by' => $login_id,
			];
			$upd_so = [
				'is_paid' => 1,
				'paid_at' => waktu_full(),
				'paid_by' => $login_id,
			];
			$this->db->trans_begin();
			// $this->db->update('tr_h1_dealer_invoice_receipt', $upd, ['id_kwitansi' => $id_kwitansi]);
			// $this->db->update('tr_sales_order', $upd_so, ['no_spk' => $row->no_spk]);
			// $this->db->update('tr_sales_order_gc', $upd_so, ['no_spk_gc' => $row->no_spk]);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
			} else {
				$this->db->trans_commit();

				$mpdf                           = $this->mpdf_l->load();
				$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
				$mpdf->charset_in               = 'UTF-8';
				$mpdf->autoLangToFont           = true;

				$data['set'] = 'print_dp';
				$data['row'] = $row;
				$filter = ['id_kwitansi' => $id_kwitansi];
				$data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();
				$filter['no_spk'] = $row->no_spk;
				$data['dt_no_mesin'] = $this->m_bayar->getSODetailNoMesin($filter);
				$data['dp'] = $this->m_bayar->getInvoiceDP($filter)->row();
				// send_json($data);
				$html = $this->load->view('dealer/print_receipt_cetak1', $data, true);
				// render the view into HTML
				$mpdf->WriteHTML($html);
				// write the HTML into the mpdf
				$output = 'cetak_.pdf';
				$mpdf->Output("$output", 'I');
			}
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
		}
	}

	public function cetak_pelunasan_test()
	{
		$this->load->library('mpdf_l');
		$login_id  = $this->session->userdata('id_user');
		$id_kwitansi = $this->input->get('id');

		$filter = [
			'id_kwitansi' => $id_kwitansi
		];
		$get_data = $this->m_bayar->getDealerInvoiceReceipt($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => waktu_full(),
				'print_by' => $login_id,
			];
			$upd_so = [
				'is_paid' => 1,
				'paid_at' => waktu_full(),
				'paid_by' => $login_id,
			];
			$this->db->trans_begin();
			// $this->db->update('tr_h1_dealer_invoice_receipt', $upd, ['id_kwitansi' => $id_kwitansi]);
			// $this->db->update('tr_sales_order', $upd_so, ['no_spk' => $row->no_spk]);
			// $this->db->update('tr_sales_order_gc', $upd_so, ['no_spk_gc' => $row->no_spk]);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
			} else {
				$this->db->trans_commit();
				$mpdf                           = $this->mpdf_l->load();
				$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
				$mpdf->charset_in               = 'UTF-8';
				$mpdf->autoLangToFont           = true;

				$data['set'] = 'print_pelunasan';
				$data['row'] = $row;
				$filter = ['id_kwitansi' => $id_kwitansi];
				$data['dt_bayar'] = $this->m_bayar->getDetailBayarInvoicePenjualan($filter)->result();

				$filter['no_spk'] = $row->no_spk;
				$data['dt_no_mesin'] = $this->m_bayar->getSODetailNoMesin($filter);
				$data['lunas'] = $this->m_bayar->getInvoicePelunasan($filter)->row();
				// send_json($data);
				$html = $this->load->view('dealer/print_receipt_cetak1', $data, true);
				// render the view into HTML
				$mpdf->WriteHTML($html);
				// write the HTML into the mpdf
				$output = 'cetak_.pdf';
				$mpdf->Output("$output", 'I');
			}
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt/dp'>";
		}
	}
}
