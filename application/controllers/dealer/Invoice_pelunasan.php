<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_pelunasan extends CI_Controller
{

	var $tables = "tr_invoice_pelunasan";
	var $folder = "dealer";
	var $page   = "invoice_pelunasan";
	var $title  = "ID Cash";

	public function __construct()
	{
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}

		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
		$this->load->model('m_h1_dealer_penjualan', 'm_jual');
		$this->load->model('m_h1_dealer_spk', 'm_spk');

		//===== Load Library =====
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

	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "history";
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array     = array();
			$button = '';
			$btn_cetak = "<a data-toggle='tooltip' title='Print' href='dealer/invoice_pelunasan/cetak?id=$rs->id_inv_pelunasan'><button class='btn btn-flat btn-xs btn-primary'>Print</button></a>";
			$button = $btn_cetak;
			$sub_array[] = "<a data-toggle='tooltip' href='dealer/invoice_pelunasan/detail?id=$rs->id_inv_pelunasan'>$rs->id_inv_pelunasan</a>";
			$filter_sisa = [
				'id_spk' => $rs->no_spk,
				'jenis' => 'lunas'
			];
			$dibayar = $this->m_bayar->sisa_pelunasan($filter_sisa);
			$sisa_pelunasan = $rs->amount_pelunasan - $dibayar;
			$sub_array[] = $rs->id_sales_order;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->id_flp_md;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->no_ktp;
			$sub_array[] = $rs->jenis_beli;
			$sub_array[] = $rs->created_at;
			$sub_array[] = mata_uang_rp($rs->amount_pelunasan);
			$sub_array[] = mata_uang_rp($sisa_pelunasan);
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
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'status_in'  => isset($_POST['status_in']) ? $_POST["status_in"] : '',
			'order_column' => 'tjs',
			'search' => $this->input->post('search')['value'],
			'select' => 'view_invoice_lunas',
		];
		if (isset($_POST['print_ke_diatas_nol'])) {
			$filter['print_ke_diatas_nol'] = $_POST['print_ke_diatas_nol'];
		}
		if (isset($_POST['print_ke_nol'])) {
			$filter['print_ke_nol'] = $_POST['print_ke_nol'];
		}
		if ($recordsFiltered == true) {
			return $this->m_bayar->getInvoicePelunasan($filter)->num_rows();
		} else {
			return $this->m_bayar->getInvoicePelunasan($filter)->result();
		}
	}

	public function add()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'insert';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$this->template($data);
	}


	function tes()
	{
		$filter['no_spk'] = 'GC20/08/27/00014-12797';
		$spk = $this->m_spk->getSPK($filter);
		send_json($spk->row());
	}
	public function save()
	{
		// $waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		// $tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		// $login_id  = $this->session->userdata('id_user');
		// $id_dealer = $this->m_admin->cari_dealer();
		$post = $this->input->post();
		// send_json($post);
		$id_invoice = $this->m_bayar->get_id_inv_pelunasan();
		$filter['no_spk'] = $post['no_spk'];
		$spk = $this->m_spk->getSPK($filter);
		if ($spk->num_rows() > 0) {
			$spk = $spk->row();
		} else {
			$rsp = [
				'status' => 'error',
				'pesan' => 'Data SPK tidak ditemukan !'
			];
			send_json($rsp);
		}
		$insert = [
			'id_inv_pelunasan'   => $id_invoice,
			'id_spk'             => $post['no_spk'],
			'amount_dp'          => $spk->dp_stor,
			'id_sales_order'     => $post['id_sales_order'],
			'amount_tjs'         => $spk->tanda_jadi,
			'id_customer'        => $spk->id_customer,
			'jenis_spk'          => $spk->jenis_spk,
			'id_karyawan_dealer' => $spk->id_karyawan_dealer,
			'nama_konsumen'      => $spk->nama_konsumen,
			'no_ktp'             => $spk->no_ktp,
			'jenis_beli'         => $spk->jenis_beli,
			'tgl_spk'            => $spk->tgl_spk,
			'no_hp'              => $spk->no_hp,
			'diskon'             => $spk->diskon,
			'alamat'             => $spk->alamat,
			'amount_pelunasan'   => $post['total_bayar'],
			'id_dealer'          => dealer()->id_dealer,
			'status'             => 'input',
			'created_at'         => waktu_full(),
			'created_by'         => user()->id_user
		];

		// send_json($insert);
		$this->db->trans_begin();
		$this->db->insert('tr_invoice_pelunasan', $insert);

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
				'link' => base_url('dealer/' . $this->page)
			];
			$_SESSION['pesan']   = "Data has been saved successfully";
			$_SESSION['tipe']   = "success";
		}
		send_json($rsp);
	}

	// public function cetak()
	// {
	// 	$this->load->library('mpdf_l');
	// 	$id_invoice = $this->input->get('id');
	// 	$filter = [
	// 		'id_invoice_dp' => $id_invoice,
	// 		'select' => 'invoice_dp'
	// 	];
	// 	$get_data = $this->m_spk->getSPK($filter);
	// 	if ($get_data->num_rows() > 0) {
	// 		$row = $data['row'] = $get_data->row();
	// 		$upd = [
	// 			'print_ke' => $row->print_ke + 1,
	// 			'print_at' => waktu_full(),
	// 			'print_by' => user()->id_user,
	// 		];
	// 		// $this->db->update('tr_invoice_dp', $upd, ['id_invoice' => $id_invoice]);
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set'] = 'print';
	// 		$data['row'] = $row;
	// 		// send_json($data);
	// 		$html = $this->load->view('dealer/invoice_pelunasan_cetak', $data, true);
	// 		// render the view into HTML
	// 		$mpdf->WriteHTML($html);
	// 		// write the HTML into the mpdf
	// 		$output = 'cetak_.pdf';
	// 		$mpdf->Output("$output", 'I');
	// 	} else {
	// 		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/Invoice_peluinvoice_pelunasan'>";
	// 	}
	// }

	public function detail()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_invoice = $this->input->get('id');
		$filter = [
			'id_inv_pelunasan' => $id_invoice
		];
		$row = $this->m_jual->getSO($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$filter_detail['no_spk'] = $row->no_spk;
			if ($row->jenis_spk == 'gc') {
				$res_details = $this->m_spk->getSPKGCDetail($filter_detail)->result();
			} elseif ($row->jenis_spk == 'individu') {
				$res_details = $this->m_spk->getSPK($filter_detail)->result();
			}
			$data['details'] = $res_details;
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/Invoice_peluinvoice_pelunasan'>";
		}
	}
}
