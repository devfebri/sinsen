<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Klaim_proposal extends CI_Controller
{

	var $tables = "tr_sales_order";
	var $folder = "dealer";
	var $page   = "klaim_proposal";
	var $title  = "Klaim Proposal";

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
		// $this->load->library('upload');
		$this->load->library('mpdf_l');
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

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$sub_array       = array();
			$button          = "";
			$status_proposal = "";
			$btn_ajukan = "<a data-toggle='tooltip' title='Ajukan' href='dealer/klaim_proposal/ajukan?id=$rs->id_sales_order&jp=$rs->jenis_program&ip_md=$rs->id_program_md'><button class='btn btn-flat btn-xs btn-info'>Ajukan</button></a>";
			$btn_cancel = "<a data-toggle='tooltip' onclick=\"return confirm('Apakah anda yakin untuk membatalkan klaim untuk data ini ?')\" title='Cancel' href='dealer/klaim_proposal/cancel?id=$rs->id_sales_order'><button class='btn btn-flat btn-xs btn-warning'>Cancel</button></a>";
			$btn_apr = "<a data-toggle='tooltip' title='Approved/Submit' href='dealer/klaim_proposal/approve?id=$rs->id_claim&jp=$rs->jenis_program''><button class='btn btn-flat btn-xs btn-primary'>Approved/Submit</button></a>";
			$btn_reject = "<a data-toggle='tooltip' title='Reject' href='dealer/klaim_proposal/reject?id=$rs->id_claim&jp=$rs->jenis_program''><button class='btn btn-flat btn-xs btn-danger'>Reject</button></a>";
			// $btn_print = "<a data-toggle='tooltip' href='dealer/generate_list_unit_delivery/print_list?id=$rs->id_generate'><button class='btn btn-flat btn-xs btn-success'>Cetak Ulang</button></a>";
			// $button = $btn_print.' '.$btn_assign;
			// // $sub_array[] = "<a data-toggle='tooltip' href='dealer/pesan_d/detail?id=$rs->id_pesan'>$rs->id_pesan</a>";
			// $sub_array[] = $btn_del = "<a href='dealer/generate_list_unit_delivery/detail?id=$rs->id_generate'>$rs->tgl_pengiriman</a>";;
			$tipe_ahm = $this->db->get_where("ms_tipe_kendaraan", ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan]);
			$tipe_ahm = $tipe_ahm->num_rows() > 0 ? $tipe_ahm->row()->tipe_ahm : '';
			$program  = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_program_md='$rs->id_program_md' AND id_tipe_kendaraan='$rs->id_tipe_kendaraan' AND id_warna LIKE('%$rs->id_warna%')");

			

			$ahm = 0;
			$md = 0;
			$dealer = 0;
			if ($rs->jenis_beli == 'Kredit') {
				$promo = $rs->voucher_2;
				// if(substr($rs->id_program_md,-6) == 'SP-002')$promo = 0;
				if ($program->num_rows() > 0) {
					$pr     = $program->row();
					$ahm    = $pr->ahm_kredit;
					$md     = $pr->md_kredit;
					$dealer = $pr->dealer_kredit;
				}
			} else {
				$promo = $rs->voucher_1;
				// if(substr($rs->id_program_md,-6) == 'SP-002')$promo = 0;
				if ($program->num_rows() > 0) {
					$pr     = $program->row();
					$ahm    = $pr->ahm_cash;
					$md     = $pr->md_cash;
					$dealer = $pr->dealer_cash;
				}
			}

			if ($rs->status_proposal == null) {
				$button = $btn_ajukan . ' ' . $btn_cancel;
				// $status_proposal = "<label class='label label-info'>"
			}
			if ($rs->status_proposal == 'draft') {
				if ($rs->alasan_reject == null) {
					$button = $btn_apr . ' ' . $btn_reject;
					$status_proposal = "<label class='label label-info'>Draft</label>";
				} else {
					$button = $btn_ajukan . ' ' . $btn_cancel;
				}
			}
			if ($rs->status_proposal == 'cancel') {
				// $button = $btn_ajukan;
				$status_proposal = "<label class='label label-warning'>Cancel</label>";
			}
			if ($rs->status_proposal == 'submitted') {
				// $button = $btn_ajukan;
				$status_proposal = "<label class='label label-success'>Submitted</label>";
			}

			

			$sub_array[] = $rs->id_program_md;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->id_sales_order;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = mata_uang_rp($rs->harga_on_road);
			$sub_array[] = mata_uang_rp($promo);
			$sub_array[] = mata_uang_rp($ahm);
			$sub_array[] = mata_uang_rp($md);
			$sub_array[] = mata_uang_rp($dealer);
			$sub_array[] = $status_proposal;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	function make_query($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_program_md', 'no_spk', 'id_sales_order', 'no_mesin', 'no_rangka', null, null, null, null, null, null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE 1=1 ";

		if ($search != '') {
			$searchs .= " AND (no_spk LIKE '%$search%' 
	          OR id_program_md LIKE '%$search%'
	          OR id_sales_order LIKE '%$search%'
	          OR no_mesin LIKE '%$search%'
	          OR no_rangka LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("
   			SELECT * FROM (
   				SELECT 
   					program_umum AS id_program_md, tr_spk.no_spk,so.id_sales_order, so.no_mesin,so.no_rangka,harga_on_road,(case when right(program_umum,6)= 'SP-002' then 0 else voucher_1 end) as voucher_1 , (case when right(program_umum,6)= 'SP-002' then 0 else voucher_2 end) as voucher_2 ,so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'umum' AS jenis_program,id_claim,tr_claim_dealer.alasan_reject
   					FROM tr_sales_order AS so
		   			JOIN tr_spk ON so.no_spk=tr_spk.no_spk
		   			JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=so.no_mesin
		   			LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_umum=tr_claim_dealer.id_program_md
		   			WHERE so.id_dealer=$id_dealer 
		   			AND no_invoice IS NOT NULL AND so.tgl_cetak_invoice is not null
		   			AND program_umum IS NOT NULL 
		   			AND tr_spk.program_umum!=''
		   		UNION 
		   		SELECT 
   					program_gabungan AS id_program_md, tr_spk.no_spk,so.id_sales_order,so.no_mesin,so.no_rangka,harga_on_road,(case when right(program_gabungan,6)= 'SP-002' then 0 else voucher_1 end) as voucher_1 , (case when right(program_gabungan,6)= 'SP-002' then 0 else voucher_2 end) as voucher_2, so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'gabungan' AS jenis_program,id_claim,tr_claim_dealer.alasan_reject
   					FROM tr_sales_order AS so
		   			JOIN tr_spk ON so.no_spk=tr_spk.no_spk
		   			JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=so.no_mesin
		   			LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_gabungan=tr_claim_dealer.id_program_md
		   			WHERE so.id_dealer=$id_dealer AND no_invoice IS NOT NULL AND so.tgl_cetak_invoice is not null AND program_gabungan IS NOT NULL AND tr_spk.program_gabungan!=''
   			) AS table_union
   		 $searchs $order $limit ");
	}
	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}

	public function cari_id()
	{
		$th					= date("Y");
		$bln					= date("m");
		$tgl_					= date("d");
		$tgl					= date("Y-m-d");
		$pr_num 				= $this->db->query("SELECT * FROM tr_claim_dealer WHERE LEFT(created_at,10)='$tgl' ORDER BY id_claim DESC LIMIT 0,1");

		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$id = substr($row->id_claim, 9, 4);
			$kode = $th . $bln . $tgl_ . sprintf("%04d", $id + 1);
		} else {
			$kode = $th . $bln . $tgl_ . '0001';
		}
		return $kode;
	}

	public function ajukan()
	{
		$waktu          = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl            = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();
		//Save
		if (!$this->input->post('submit')) {
			$id_sales_order = $this->input->get('id');
			$id_program_md = $this->input->get('ip_md');
			$jenis_program = $this->input->get('jp');
			if ($jenis_program == 'umum') {
				$where_program = "AND program_umum NOT IN (SELECT id_program_md FROM tr_claim_dealer WHERE id_dealer=$id_dealer AND id_sales_order=tr_sales_order.id_sales_order)";
				$where = "program_umum='$id_program_md'";
			} elseif ($jenis_program == 'gabungan') {
				$where_program = "AND program_gabungan NOT IN (SELECT id_program_md FROM tr_claim_dealer WHERE id_dealer=$id_dealer AND id_sales_order=tr_sales_order.id_sales_order)";
				$where = "program_gabungan='$id_program_md'";
			} else {
				redirect('dealer/klaim_proposal', 'refresh');
			}

			$cek_so = $this->db->query("SELECT *,tr_sales_order.no_mesin,
				(SELECT CONCAT(id_tipe_kendaraan,' | ',tipe_ahm) FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
				(SELECT CONCAT(id_warna,' | ',warna) FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna 
				FROM tr_sales_order 
				JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
				WHERE id_sales_order='$id_sales_order' AND $where AND tr_sales_order.id_dealer=$id_dealer AND 
				(id_sales_order NOT IN(SELECT id_sales_order FROM tr_claim_dealer WHERE id_program_md='$id_program_md') $where_program )");
			if ($cek_so->num_rows() == 0) {
				$cek_claim = $this->db->query("SELECT * FROM tr_claim_dealer WHERE id_sales_order='$id_sales_order' AND id_program_md='$id_program_md'");
				if ($cek_claim->num_rows() == 0) {
					redirect('dealer/klaim_proposal', 'refresh');
				} else {
					$cek_so = $this->db->query("SELECT *,tr_sales_order.no_mesin,
				(SELECT CONCAT(id_tipe_kendaraan,' | ',tipe_ahm) FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
				(SELECT CONCAT(id_warna,' | ',warna) FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna 
				FROM tr_sales_order 
				JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
				WHERE id_sales_order='$id_sales_order' AND (program_umum='$id_program_md' OR program_gabungan='$id_program_md')");
					$data['id_claim'] = $cek_claim->row()->id_claim;
				}
			} else {
				$so = $cek_so->row();
				$id_program_md         = $data['id_program_md'] = $jenis_program == 'umum' ? $so->program_umum : $so->program_gabungan;
			}
			$data['isi']           = $this->page;
			$data['title']         = $this->title;
			$data['mode']          = 'ajukan';
			$data['set']           = "form";
			$data['jenis_program'] = $jenis_program;
			$data['id_program_md'] = $id_program_md;
			$data['get_syarat'] = $this->db->query("SELECT *,'' AS checklist_dealer FROM tr_sales_program_syarat WHERE id_program_md='$id_program_md'");
			$data['row']        = $cek_so->row();
			$this->template($data);
		} else {
			$id_claim = $this->input->post('id_claim');
			$claim    = $this->db->query("SELECT * FROM tr_claim_dealer WHERE id_claim='$id_claim' AND id_dealer=$id_dealer");
			if ($claim->num_rows() == 0) {
				$id_claim       = $this->cari_id();
			}
			$jenis_program  = $this->input->post('jenis_program');
			$id_sales_order = $this->input->post('id_sales_order');
			$id_program_md  = $this->input->post('id_program_md');

			$insert['id_program_md']   = $id_program_md;
			$insert['id_claim']        = $id_claim;
			$insert['id_sales_order']  = $id_sales_order;
			$insert['id_dealer']       = $id_dealer;
			$insert['created_at']      = $waktu;
			$insert['status_proposal'] = 'draft';
			$insert['created_by']      = $login_id;
			$insert['alasan_reject']      = null;

			$get_syarat = $this->db->query("SELECT * FROM tr_sales_program_syarat WHERE id_program_md='$id_program_md' ");
			$id = $this->input->post('cek');
			if ($get_syarat->num_rows() > 0) {
				foreach ($get_syarat->result() as $key => $rs) {
					if (in_array($rs->id, $id)) {
						$dt_detail[$key]['checklist_dealer'] = 1;
					} else {
						$dt_detail[$key]['checklist_dealer'] = 0;
					}
					$dt_detail[$key]['id_syarat_ketentuan'] = $rs->id;
					$dt_detail[$key]['id_claim'] = $id_claim;
				}
			}

			$this->db->trans_begin();
			if ($claim->num_rows() == 0) {
				$this->db->insert('tr_claim_dealer', $insert);
				if (isset($dt_detail)) {
					$this->db->insert_batch('tr_claim_dealer_syarat', $dt_detail);
				}
			} else {
				$this->db->update('tr_claim_dealer', $insert, ['id_claim' => $id_claim]);
				$this->db->delete('tr_claim_dealer_syarat', ['id_claim' => $id_claim]);
				if (isset($dt_detail)) {
					$this->db->insert_batch('tr_claim_dealer_syarat', $dt_detail);
				}
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something when Wrong";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
			} else {
				$this->db->trans_commit();
				$_SESSION['pesan'] 	= "Data has been processed successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
			}
		}
	}

	public function cancel()
	{
		$waktu          = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl            = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();
		$id_sales_order = $this->input->get('id');
		$cek_so = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order='$id_sales_order' AND id_dealer=$id_dealer AND id_sales_order NOT IN(SELECT id_sales_order FROM tr_klaim_proposal)");
		if ($cek_so->num_rows() == 0) {
			redirect('dealer/klaim_proposal', 'refresh');
		}

		$so = $cek_so->row();
		$data['id_sales_order'] = $id_sales_order;
		$data['no_mesin']       = $so->no_mesin;
		$data['id_dealer']      = $id_dealer;
		$data['status_klaim']   = 'cancel';
		$data['created_at']     = $waktu;
		$data['created_by']     = $login_id;
		$data['cancel_at']      = $waktu;
		$data['cancel_by']      = $login_id;

		$this->db->trans_begin();
		$this->db->insert('tr_klaim_proposal', $data);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been canceled successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
		}
	}

	public function approve()
	{
		$waktu          = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl            = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();

		if (!$this->input->post('submit')) {
			$data['isi']        = $this->page;
			$data['title']      = $this->title;
			$data['mode']       = 'approve';
			$data['set']        = "form";
			$id_claim           = $this->input->get('id');

			$row = $this->db->query("SELECT *,tr_sales_order.no_mesin, 
				(SELECT CONCAT(id_tipe_kendaraan,' | ',tipe_ahm) FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
				(SELECT CONCAT(id_warna,' | ',warna) FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna 
				FROM tr_claim_dealer 
				JOIN tr_sales_order ON tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order
				JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
				WHERE tr_claim_dealer.id_dealer=$id_dealer AND id_claim='$id_claim' AND status_proposal='draft'");
			if ($row->num_rows() == 0) redirect('dealer/klaim_proposal', 'refresh');
			$data['row']        = $row->row();
			$data['get_syarat'] = $this->db->query("SELECT * FROM tr_claim_dealer_syarat 
				JOIN tr_sales_program_syarat AS sps ON tr_claim_dealer_syarat.id_syarat_ketentuan=sps.id
				WHERE id_claim='$id_claim'");
			$data['jenis_program'] = $this->input->get('jenis_program');
			$id_program_md = $data['id_program_md'] = $row->row()->id_program_md;
			$data['id_claim']      = $id_claim;
			$this->template($data);
		} else {
			$id_claim                   = $this->input->post('id_claim');
			$data['status_proposal']       = 'submitted';
			$data['tgl_approve_reject_md'] = $waktu;
			$data['tgl_ajukan_claim']      = date('Y-m-d');
			$data['status']                = 'ajukan';

			$row = $this->db->query("SELECT *,tr_sales_order.no_mesin, 
				(SELECT CONCAT(id_tipe_kendaraan,' | ',tipe_ahm) FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
				(SELECT CONCAT(id_warna,' | ',warna) FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna 
				FROM tr_claim_dealer 
				JOIN tr_sales_order ON tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order
				JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
				WHERE tr_claim_dealer.id_dealer=$id_dealer AND id_claim='$id_claim' AND status_proposal='draft'")->row();

			$this->db->trans_begin();
			$this->db->update('tr_claim_dealer', $data, ['id_claim' => $id_claim]);
			$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['kode_notif' => 'approve_klaim_prop'])->row();
			$notif = [
				'id_notif_kat' => $ktg_notif->id_notif_kat,
				'id_referensi' => $row->id_sales_order,
				'judul'        => "Approved Klaim Proposal",
				'pesan'        => "Telah dilakukan approve untuk klaim proposal (ID Sales Order=$row->id_sales_order)",
				'link'         => $ktg_notif->link . '/?id=' . $row->id_sales_order . '&ip_md=' . $row->id_program_md,
				'status'       => 'baru',
				'id_dealer'	   => $id_dealer,
				'created_at'   => $waktu,
				'created_by'   => $login_id
			];
			$this->db->insert('tr_notifikasi', $notif);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something when Wrong";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
			} else {
				$this->db->trans_commit();
				$_SESSION['pesan'] 	= "Data has been approved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
			}
		}
	}

	public function reject()
	{
		$waktu          = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl            = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();

		if (!$this->input->post('submit')) {
			$data['isi']        = $this->page;
			$data['title']      = $this->title;
			$data['mode']       = 'reject';
			$data['set']        = "form";
			$id_claim           = $this->input->get('id');

			$row = $this->db->query("SELECT *,tr_sales_order.no_mesin, 
				(SELECT CONCAT(id_tipe_kendaraan,' | ',tipe_ahm) FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
				(SELECT CONCAT(id_warna,' | ',warna) FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna 
				FROM tr_claim_dealer 
				JOIN tr_sales_order ON tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order
				JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
				WHERE tr_claim_dealer.id_dealer=$id_dealer AND id_claim='$id_claim' AND status_proposal='draft'");
			if ($row->num_rows() == 0) redirect('dealer/klaim_proposal', 'refresh');
			$data['row']        = $row->row();
			$data['get_syarat'] = $this->db->query("SELECT * FROM tr_claim_dealer_syarat 
				JOIN tr_sales_program_syarat AS sps ON tr_claim_dealer_syarat.id_syarat_ketentuan=sps.id
				WHERE id_claim='$id_claim'");
			$data['jenis_program'] = $this->input->get('jenis_program');
			$data['id_program_md'] = $row->row()->id_program_md;
			$data['id_claim']      = $id_claim;
			$this->template($data);
		} else {
			$id_claim                      = $this->input->post('id_claim');
			$data['status_proposal']       = null;
			$data['tgl_approve_reject_md'] = null;
			$data['tgl_ajukan_claim']      = null;
			$data['status']                = null;
			$alasan_reject = $data['alasan_reject']         = $this->input->post('reject');

			$this->db->trans_begin();
			$this->db->update('tr_claim_dealer', $data, ['id_claim' => $id_claim]);
			$claim = $this->db->query("SELECT * FROM tr_claim_dealer WHERE id_claim='$id_claim'")->row();
			$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['kode_notif' => 'rjct_klaim_prop'])->row();
			$notif = [
				'id_notif_kat' => $ktg_notif->id_notif_kat,
				'id_referensi' => $claim->id_sales_order,
				'judul'        => "Reject Klaim Proposal",
				'pesan'        => "Telah dilakukan reject untuk klaim proposal (ID Sales Order=$claim->id_sales_order) dengan alasan $alasan_reject ",
				'link'         => $ktg_notif->link . '/detail?id=' . $claim->id_sales_order . '&ip_md=' . $claim->id_program_md,
				'status'       => 'baru',
				'id_dealer'	   => $id_dealer,
				'created_at'   => $waktu,
				'created_by'   => $login_id
			];
			$this->db->insert('tr_notifikasi', $notif);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something when Wrong";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
			} else {
				$this->db->trans_commit();
				$_SESSION['pesan'] 	= "Data has been rejected successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/klaim_proposal'>";
			}
		}
	}


	//    public function reject()
	// {		
	// 	$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$tgl            = gmdate("y-m-d", time()+60*60*7);
	// 	$login_id       = $this->session->userdata('id_user');
	// 	$id_dealer      = $this->m_admin->cari_dealer();
	// 	$id_sales_order = $this->input->get('id');
	// 	$alasan_reject = $this->input->get('ar');
	// 	$cek_klaim = $this->db->query("SELECT * FROM tr_klaim_proposal WHERE id_sales_order = '$id_sales_order' AND status_klaim='draft' AND id_dealer=$id_dealer");
	// 	if ($cek_klaim->num_rows()==0) { redirect('dealer/klaim_proposal','refresh'); }
	// 	$klaim = $cek_klaim->row();
	// 	$data['status_klaim']  = 'draft';
	// 	$data['alasan_reject'] = $alasan_reject;
	// 	$data['approved_at']   = $waktu;		
	// 	$data['approved_by']   = $login_id;

	// 	$this->db->trans_begin();
	// 		$this->db->update('tr_klaim_proposal',$data,['id_sales_order'=>$id_sales_order]);			
	// 		$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['kode_notif'=>'rjct_klaim_prop'])->row();
	// 		$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
	// 					'id_referensi' => $id_sales_order,
	// 					'judul'        => "Reject Klaim Proposal",
	// 					'pesan'        => "Telah dilakukan reject untuk klaim proposal (ID Sales Order=$klaim->id_sales_order) dengan alasan $alasan_reject ",
	// 					'link'         => $ktg_notif->link.'/detail?id='.$id_sales_order,
	// 					'status'       =>'baru',
	// 					'id_dealer'	   => $id_dealer,
	// 					'created_at'   => $waktu,
	// 					'created_by'   => $login_id
	// 				 ];

	// 		$this->db->insert('tr_notifikasi',$notif);
	// 	if ($this->db->trans_status() === FALSE)
	//      	{
	// 		$this->db->trans_rollback();
	// 		$_SESSION['pesan'] 	= "Something when Wrong";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
	//      	}
	//      	else
	//      	{
	//        	$this->db->trans_commit();
	//        	$_SESSION['pesan'] 	= "Data has been approved successfully";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
	//      	}
	//    }

	// public function add()
	// {				
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['mode']  = 'insert';
	// 	$data['set']   = "form";
	// 	$id_dealer     = $this->m_admin->cari_dealer();
	// 	$data['hasil'] = $this->db->query("SELECT tr_hasil_survey.*,tipe_ahm,warna,tr_spk.*,(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=tr_spk.id_finance_company) AS finance_company
	// 		FROM tr_hasil_survey 
	// 		JOIN tr_spk ON tr_hasil_survey.no_spk=tr_spk.no_spk
	// 		JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
	// 		JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
	// 		AND id_dealer=$id_dealer
	// 		AND tr_hasil_survey.status_approval='approved'
	// 		ORDER BY tr_hasil_survey.created_at DESC");
	// 	// $data['spk'] = $this->db->get('tr_spk');
	// 	$this->template($data);	
	// }

	// function get_unit()
	// {
	// 	$tgl_pengiriman = $this->input->post('tgl_pengiriman');
	// 	$so = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 		(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 		(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 		(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 		(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir,
	// 		(SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
	// 			JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
	// 			JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
	// 			WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS ksu
	// 		FROM tr_sales_order AS so
	// 		JOIN tr_spk ON so.no_spk=tr_spk.no_spk
	// 		WHERE so.tgl_pengiriman='$tgl_pengiriman'")->result();
	// 	echo json_encode($so);
	// }

	// public function get_()
	// {
	// 	$th       = date('Y');
	// 	$bln      = date('m');
	// 	$th_bln   = date('Y-m');
	// 	$th_kecil = date('y');
	// 	$id_dealer = $this->m_admin->cari_dealer();
	// 	// $id_sumber='E20';
	// 	// if ($id_dealer!=null) {
	// 		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
	// 		$id_sumber = $dealer->kode_dealer_md;
	// 	// }
	// 	$get_data  = $this->db->query("SELECT * FROM ms_pesan
	// 		WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
	// 		ORDER BY created_at DESC LIMIT 0,1");
	//    		if ($get_data->num_rows()>0) {
	// 			$row      = $get_data->row();
	// 			$id_pesan = substr($row->id_pesan, -5);
	// 			$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
	// 			$i=0;
	// 			while ($i<1) {
	// 				$cek = $this->db->get_where('ms_pesan',['id_pesan'=>$new_kode])->num_rows();
	// 			    if ($cek>0) {
	// 					$neww     = substr($new_kode, -5);
	// 					$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
	// 					$i        = 0;
	// 			    }else{
	// 			    	$i++;
	// 			    }
	// 			}
	//    		}else{
	// 			$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.'00001';
	//    		}
	//   		return strtoupper($new_kode);
	// }	

	//    public function delete()
	// {		
	// 	$tabel			= $this->tables;
	// 	$pk 			= 'id_pesan';
	// 	$id 			= $this->input->get('id');		
	// 	$this->db->trans_begin();			
	// 		$this->db->delete($tabel,array($pk=>$id));
	// 	$this->db->trans_commit();			
	// 	$result = 'Success';									

	// 	if($this->db->trans_status() === FALSE){
	// 		$result = 'You can not delete this data because it already used by the other tables';										
	// 		$_SESSION['tipe'] 	= "danger";			
	// 	}else{
	// 		$result = 'Data has been deleted succesfully';										
	// 		$_SESSION['tipe'] 	= "success";			
	// 	}
	// 	$_SESSION['pesan'] 	= $result;
	// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pesan_d'>";
	// }

	// public function print_list()
	// {
	// 	$tgl         = gmdate("y-m-d", time()+60*60*7);
	// 	$waktu       = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$login_id    = $this->session->userdata('id_user');
	// 	$id_generate = $this->input->get('id');				

	//  		$get_data = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery AS glud
	//   			WHERE id_generate='$id_generate' ");
	//  		if ($get_data->num_rows()>0) {
	//  			$row = $data['row'] = $get_data->row();
	//  			$data['units'] = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 			(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 			(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 			(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 			(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir
	// 			FROM tr_generate_list_unit_delivery_detail AS gludd
	// 			JOIN tr_sales_order AS so ON gludd.id_sales_order=so.id_sales_order
	// 			JOIN tr_spk ON tr_spk.no_spk=so.no_spk
	// 			WHERE id_generate=$id_generate
	// 			")->result();

	//  			$upd = ['print_ke'=> $row->print_ke+1,
	//  					'print_at'=> $waktu,
	//  					'print_by'=> $login_id,
	//  				   ];
	//  			$this->db->update('tr_generate_list_unit_delivery',$upd,['id_generate'=>$id_generate]);
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set'] = 'print';

	//        	$html = $this->load->view('dealer/generate_list_unit_delivery_cetak', $data, true);
	//        	// render the view into HTML
	//         $mpdf->WriteHTML($html);
	//         // write the HTML into the mpdf
	//         $output = 'cetak_.pdf';
	//         $mpdf->Output("$output", 'I');	        
	//        }else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";		
	//        }

	// }

	// public function detail()
	// {				
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['mode']  = 'detail';
	// 	$data['set']   = "form";
	// 	$id_dealer     = $this->m_admin->cari_dealer();
	// 	$id_generate = $this->input->get('id');
	// 	$row = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery WHERE id_generate='$id_generate' AND id_dealer=$id_dealer");
	// 	if ($row->num_rows()>0) {
	// 		$data['row'] = $row->row();
	// 		$data['units'] = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 			(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 			(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 			(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 			(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir,
	// 			(SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
	// 			JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
	// 			JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
	// 			WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS ksu
	// 			FROM tr_generate_list_unit_delivery_detail AS gludd
	// 			JOIN tr_sales_order AS so ON gludd.id_sales_order=so.id_sales_order
	// 			JOIN tr_spk ON tr_spk.no_spk=so.no_spk
	// 			WHERE id_generate=$id_generate
	// 			")->result();
	// 		$this->template($data);	
	// 	}else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";
	// 	}
	// }
	// public function assign_supir()
	// {				
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['mode']  = 'assign_supir';
	// 	$data['set']   = "form";
	// 	$id_dealer     = $this->m_admin->cari_dealer();
	// 	$id_generate = $this->input->get('id');
	// 	$row = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery WHERE id_generate='$id_generate' AND id_dealer=$id_dealer");
	// 	if ($row->num_rows()>0) {
	// 		$data['row'] = $row->row();
	// 		$data['units'] = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 			(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 			(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 			(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 			(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir
	// 			FROM tr_generate_list_unit_delivery_detail AS gludd
	// 			JOIN tr_sales_order AS so ON gludd.id_sales_order=so.id_sales_order
	// 			JOIN tr_spk ON tr_spk.no_spk=so.no_spk
	// 			WHERE id_generate=$id_generate
	// 			")->result();
	// 		$this->template($data);	
	// 	}else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";
	// 	}
	// }

	// public function save_assign()
	// {		
	// 	$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
	// 	$login_id  = $this->session->userdata('id_user');
	// 	$id_dealer = $this->m_admin->cari_dealer();

	// 	$nama_supir     = $this->input->post('nama_supir');
	// 	$id_sales_order = $this->input->post('id_sales_order');
	// 	$id_generate    = $this->input->post('id_generate');
	// 	$data['proses_pdi']       = isset($_POST['proses_pdi'])?1:null;
	// 	$data['manual_book']      = isset($_POST['manual_book'])?1:null;
	// 	$data['standard_toolkit'] = isset($_POST['standard_toolkit'])?1:null;
	// 	$data['helmet']           = isset($_POST['helmet'])?1:null;
	// 	$data['spion']            = isset($_POST['spion'])?1:null;
	// 	$data['bppgs']            = isset($_POST['bppgs'])?1:null;
	// 	$data['aksesoris']        = isset($_POST['aksesoris'])?1:null;

	// 	foreach ($nama_supir as $key =>$sp) {
	// 		$upd_sopir[] = ['id_sales_order'=>$id_sales_order[$key],'id_master_plat'=>$sp];
	// 	}

	// 	$this->db->trans_begin();
	// 		$this->db->update('tr_generate_list_unit_delivery',$data,['id_generate'=>$id_generate]);
	// 		$this->db->update_batch('tr_sales_order',$upd_sopir,'id_sales_order');			
	// 	if ($this->db->trans_status() === FALSE)
	//      	{
	// 		$this->db->trans_rollback();
	// 		$_SESSION['pesan'] 	= "Something when Wrong";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<script>history.go(-1)</script>";
	//      	}
	//      	else
	//      	{
	//        	$this->db->trans_commit();
	//        	$_SESSION['pesan'] 	= "Data has been processed successfully";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";
	//      	}
	//    }
}
