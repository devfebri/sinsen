<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Generate_list_unit_delivery extends CI_Controller
{
	var $folder = "dealer";
	var $page   = "generate_list_unit_delivery";
	var $title  = "Generate List Unit Delivery";

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
		$this->load->model('m_h1_dealer_generate_unit', 'm_generate');
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
		$this->db_crm = $this->load->database('db_crm', true);
		$this->load->model('mokita_model');
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

	public function schedule()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "schedule";
		$data['id_dealer'] = $this->m_admin->cari_dealer();
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$sub_array     = array();
			$button = '';
			// $btn_del = "<a data-toggle='tooltip' onclick=\"return confirm('Are you sure to delete this data ?')\" title='Delete' href='dealer/pesan_d/delete?id=$rs->id_generate'><button class='btn btn-flat btn-sm btn-danger'><i class='fa fa-trash'></i></button></a>";
			$btn_assign = "<a data-toggle='tooltip' title='Checklist Kebutuhan Pengiriman' href='dealer/generate_list_unit_delivery/assign_supir?id=$rs->id_generate' class='btn btn-flat btn-xs btn-primary'><i class='fa fa-check'>Checklist</i></a>";
			$btn_print = '';
			if ($rs->total_ceklist > 0) {
				$btn_print = "<a data-toggle='tooltip' href='dealer/generate_list_unit_delivery/print_list?id=$rs->id_generate'><button class='btn btn-flat btn-xs btn-success'><i class='fa fa-print'></i>  Print</button></a>";
			}
			$button = $btn_assign . ' ' . $btn_print;
			// $sub_array[] = "<a data-toggle='tooltip' href='dealer/pesan_d/detail?id=$rs->id_generate'>$rs->id_generate</a>";
			$sub_array[] = $btn_del = "<a href='dealer/generate_list_unit_delivery/detail?id=$rs->id_generate'>$rs->id_generate</a>";
			$sub_array[] = $rs->tgl_pengiriman;
			$sub_array[] = $rs->driver;
			$sub_array[] = $rs->tot_unit;
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
		$order_column = array('id_generate', 'tipe_pesan', 'konten', 'start_date', 'end_date', null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY glud.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE glud.id_dealer=$id_dealer";

		if ($search != '') {
			$searchs .= " AND (tgl_pengiriman LIKE '%$search%' 
	          OR glud.created_at LIKE '%$search%'
	          OR glud.id_generate LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("SELECT *,(SELECT count(id_generate)
		FROM tr_generate_list_unit_delivery_detail 
		WHERE id_generate=glud.id_generate) AS tot_unit, driver, (IFNULL(glud.proses_pdi, 0) + IFNULL(glud.manual_book, 0) + IFNULL(glud.standard_toolkit, 0) + IFNULL(glud.helmet, 0) + IFNULL(glud.spion, 0) + IFNULL(glud.bppgs, 0) + IFNULL(glud.aksesoris, 0) ) as total_ceklist
		FROM tr_generate_list_unit_delivery  AS glud
		LEFT JOIN ms_plat_dealer pd ON pd.id_master_plat=glud.id_master_plat

   		 $searchs $order $limit ");
	}
	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
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

	function get_unit($tgl_pengiriman_pr = null, $id_master_plat = null)
	{
		if ($tgl_pengiriman_pr != null) {
			$tgl_pengiriman = $tgl_pengiriman_pr;
		} else {
			$tgl_pengiriman = $this->input->post('tgl_pengiriman');
			$id_master_plat = $this->input->post('id_master_plat');
		}
		$filter = [
			'tgl_pengiriman' => $tgl_pengiriman,
			'id_master_plat' => $id_master_plat,
			'ready_delivery' => true
		];
		$so = $this->m_generate->getUnitSalesDelivery($filter);
		if ($tgl_pengiriman_pr == null) {
			echo json_encode($so->result());
		} else {
			return $so;
		}
	}

	public function save()
	{
		$waktu     = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();

		$tgl_pengiriman         = $data['tgl_pengiriman'] = $this->input->post('tgl_pengiriman');
		$id_master_plat         = $this->input->post('id_master_plat');
		$id_generate            = $this->m_generate->get_id_generate();
		$data['id_generate']    = $id_generate;
		$data['id_dealer']      = $id_dealer;
		$data['id_master_plat'] = $this->input->post('id_master_plat');
		$data['status']         = 'ready';
		$data['created_at']     = $waktu;
		$data['created_by']     = $login_id;
		$so = $this->get_unit($tgl_pengiriman, $id_master_plat);
		$tot = $so->num_rows();
		foreach ($so->result() as $val) {
			$ins_detail[] = [
				'id_generate' => $id_generate,
				'no_mesin' => $val->no_mesin,
				'id_sales_order' => $val->id_sales_order
			];
		}
		// $tes = ['data' => $data, 'ins_detail' => $ins_detail];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_generate_list_unit_delivery', $data);
		// $id_generate = $this->db->insert_id();
		$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['id_notif_kat' => 19])->row();
		$notif = [
			'id_notif_kat' => $ktg_notif->id_notif_kat,
			'id_referensi' => $id_generate,
			'judul'        => "Informasi Pengiriman Unit",
			'pesan'        => "Informasi pengiriman unit pada tanggal $tgl_pengiriman, sejumlah $tot unit.",
			'link'         => $ktg_notif->link . '/detail?id=' . $id_generate,
			'status'       => 'baru',
			'id_dealer'	   => $id_dealer,
			'created_at'   => $waktu,
			'created_by'   => $login_id
		];
		$this->db->insert('tr_notifikasi', $notif);
		if (isset($ins_detail)) {
			$this->db->insert_batch('tr_generate_list_unit_delivery_detail', $ins_detail);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data berhasil disimpan.";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/generate_list_unit_delivery'>";
		}
	}

	public function print_list()
	{
		$tgl         = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$waktu       = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id    = $this->session->userdata('id_user');
		$id_generate = $this->input->get('id');

		$get_data = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery AS glud
			JOIN ms_plat_dealer pd ON pd.id_master_plat=glud.id_master_plat
			JOIN ms_dealer dl ON dl.id_dealer=glud.id_dealer
   			WHERE id_generate='$id_generate' ");
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();
			$filter['id_generate'] = $id_generate;
			$data['units'] = $this->m_generate->getUnitSalesDelivery($filter)->result();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => $waktu,
				'print_by' => $login_id,
			];
			// if ($row->print_ke==0) {

			// }
			$this->db->update('tr_generate_list_unit_delivery', $upd, ['id_generate' => $id_generate]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print';
			// send_json($data);
			$html = $this->load->view('dealer/generate_list_unit_delivery_cetak', $data, true);
			$mpdf->WriteHTML($html);
			// if ($cek_so->num_rows() > 1) {
			// 	$mpdf->AddPage();
			// }
			// }

			$output = 'cetak_.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/generate_list_unit_delivery'>";
		}
	}
	function get_list($id_generate)
	{
		$id_dealer     = $this->m_admin->cari_dealer();
		return $this->db->query("SELECT * FROM tr_generate_list_unit_delivery glud
		LEFT JOIN ms_plat_dealer pd ON pd.id_master_plat=glud.id_master_plat
		WHERE id_generate='$id_generate' AND glud.id_dealer=$id_dealer");
	}
	public function detail()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_generate = $this->input->get('id');
		$row = $this->get_list($id_generate);
		if ($row->num_rows() > 0) {
			$data['row'] = $row->row();
			/*
			$data['units'] = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
				(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
				(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
				(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=so.no_mesin) AS no_rangka2,
				(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir,
				(SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
				JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
				JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
				WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS ksu,
				(SELECT CONCAT(tr_prospek.id_flp_md,' - ',nama_lengkap)as sales FROM tr_prospek 
				JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
				WHERE id_customer=tr_spk.id_customer ORDER BY tr_prospek.created_at DESC LIMIT 1) AS sales,nama_konsumen
				FROM tr_generate_list_unit_delivery_detail AS gludd
				JOIN tr_sales_order AS so ON gludd.id_sales_order=so.id_sales_order
				JOIN tr_spk ON tr_spk.no_spk=so.no_spk
				WHERE id_generate='$id_generate'
				-- AND gludd.id_dealer='$id_dealer'
				")->result();
			*/

			$filter['id_generate'] = $id_generate;
			$data['units'] = $this->m_generate->getUnitSalesDelivery($filter)->result();
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/generate_list_unit_delivery'>";
		}
	}
	public function assign_supir()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'assign_supir';
		$data['set']   = "form";

		$id_generate = $this->input->get('id');
		$row = $this->get_list($id_generate);
		if ($row->num_rows() > 0) {
			$data['row'] = $row->row();
			$filter['id_generate'] = $id_generate;
			$data['units'] = $this->m_generate->getUnitSalesDelivery($filter)->result();
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/generate_list_unit_delivery'>";
		}
	}

	public function save_assign()
	{
		$waktu     = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl       = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();

		$id_generate    = $this->input->post('id_generate');
		$data['proses_pdi']       = isset($_POST['proses_pdi']) ? 1      : null;
		$data['manual_book']      = isset($_POST['manual_book']) ? 1     : null;
		$data['standard_toolkit'] = isset($_POST['standard_toolkit']) ? 1 : null;
		$data['helmet']           = isset($_POST['helmet']) ? 1          : null;
		$data['spion']            = isset($_POST['spion']) ? 1           : null;
		$data['bppgs']            = isset($_POST['bppgs']) ? 1           : null;
		$data['aksesoris']        = isset($_POST['aksesoris']) ? 1       : null;
		$data['direct_gift']        = isset($_POST['direct_gift']) ? 1       : null;
		$data['assign_at']        = $waktu;
		$data['assign_by']        = $login_id;
		$data['status']        = 'in_progress';
		$ymd = date('Y-m-d');
		$pesan_sms = 'Keterangan pengiriman SMS kepada konsumen : </br>';
		$filter['id_generate'] = $id_generate;
		$details = $this->m_generate->getUnitSalesDelivery($filter);
		foreach ($details->result() as $so) {
			if ($so->notif_sms_bastk_status == NULL) {
				$sms_pesan = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Reminder BASTK' AND id_dealer='$id_dealer'  AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1 ");
				if ($sms_pesan->num_rows() > 0) {
					$pesan  = $sms_pesan->row()->konten;
					$id_get = [
						'IdSalesOrder' => $so->id_sales_order,
						'NamaDealer' => $id_dealer,
						'TanggalPengirimanUnit' => $so->id_sales_order,
						'WaktuPengirimanUnit' => $so->id_sales_order,
						'NamaCustomer' => $so->nama_konsumen,
						'TipeUnit' => $so->id_tipe_kendaraan,
						'Warna' => $so->id_warna
					];
					$status = sms_zenziva($so->no_hp, pesan($pesan, $id_get));
					if ($status['status'] == 0) {
						$pesan_sms .= '- ' . $so->nama_konsumen . ' ( No SPK : ' . $so->no_spk . ') Berhasil';
					} elseif ($status['status'] == 1) {
						$pesan_sms .= '- ' . $so->nama_konsumen . ' ( No SPK : ' . $so->no_spk . ') No tujuan tidak valid';
					}
					$pesan_sms .= "</br>";
				}
				if ($so->jenis_so == 'gc') {
					$upd_so_gc[] = [
						'no_mesin'               => $so->no_mesin,
						'notif_sms_bastk_status' => isset($status) ? $status['status'] : null,
						'notif_sms_bastk_at'     => isset($status) ? $waktu : null,
						'notif_sms_bastk_by'     => isset($status) ? $login_id : null,
						'status_delivery'        => 'in_progress'
					];
				} else {
					$upd_so_id[] = [
						'id_sales_order'         => $so->id_sales_order,
						'notif_sms_bastk_status' => isset($status) ? $status['status'] : null,
						'notif_sms_bastk_at'     => isset($status) ? $waktu : null,
						'notif_sms_bastk_by'     => isset($status) ? $login_id : null,
						'status_delivery'        => 'in_progress'
					];
					$kirim_ce_apps[] = $so;
				}
			}
		}
		if (isset($kirim_ce_apps)) {
			foreach ($kirim_ce_apps as $so) {
				$spk = $this->db->get_where('tr_spk', ['no_spk' => $so->no_spk])->row();
				$last_status_ce_apps = $this->mokita_model->last_tracking($so->no_spk);
				$array_post = [
					'AppsOrderNumber'   => '',
					'DmsOrderNumber'    => '',
					'CustomerPhoneNumber' => $spk->no_hp,
					'CreditStatus' => $last_status_ce_apps ? $last_status_ce_apps->CreditStatus : '',
					'IndentStatus' => $last_status_ce_apps ? $last_status_ce_apps->IndentStatus : '',
					'DeliveryStatus' => 'Motor dalam proses pengiriman',
					'EstimatedDeliveryDate' => $so->tgl_pengiriman,
					'EngineNumber' => $so->no_mesin,
					'StnkStatus' => '',
					'BpkbStatus' => '',
					'VehicleNumber' => '',
				];
				
				$get_leads = $this->mokita_model->cek_sales_order(['no_spk' => $so->no_spk]);
				if ($get_leads) {
					$leads = $this->db_crm->get_where("leads", ['leads_id' => $get_leads->leads_id])->row();
					$array_post['AppsOrderNumber'] = $leads->sourceRefID;
					$array_post['DmsOrderNumber'] = $leads->batchID;
					$this->load->library("mokita");
					$this->mokita->h1_credit_approval_indent_delivery_stnk_bpkb($array_post);
				}
				$this->mokita_model->set_tracking($so->no_spk, $array_post);
			}
		}
		// $tes = [
		// 	'upd' => $data,
		// 	'upd_so_gc' => isset($upd_so_gc) ? $upd_so_gc : null,
		// 	'upd_so_id' => isset($upd_so_id) ? $upd_so_id : null,
		// ];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->update('tr_generate_list_unit_delivery', $data, ['id_generate' => $id_generate]);
		if (isset($upd_so_id)) {
			$this->db->update_batch('tr_sales_order', $upd_so_id, 'id_sales_order');
		} elseif (isset($upd_so_gc)) {
			$this->db->update_batch('tr_sales_order_gc_nosin', $upd_so_gc, 'no_mesin');
		} else {
			$pesan_sms = '';
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data sudah berhasil diproses. $pesan_sms";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/generate_list_unit_delivery'>";
		}
	}

	// public function ceknosin()
	// {
	// 	$nosin_gen = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery_detail");
	// 	$kosong = 0;
	// 	$beda = 0;
	// 	$tot_sama = 0;
	// 	$dt_kosong = array();
	// 	$data_sama = array();
	// 	$data_beda = array();
	// 	foreach ($nosin_gen->result() as $ns) {
	// 		$so = $this->db->get_where('tr_sales_order',['id_sales_order'=>$ns->id_sales_order]);
	// 		if ($so->num_rows()>0) {
	// 			$so = $so->row();
	// 			$sama = $ns->no_mesin==$so->no_mesin?1:'';

	// 			if ($sama==1) {
	// 				$so_sama[] = ['id_sales_order'=>$so->id_sales_order,
	// 					  'no_mesin_so'=>$so->no_mesin,
	// 					  'no_mesin_gn'=>$ns->no_mesin,
	// 					  'sama' =>$sama
	// 					];
	// 				$tot_sama++;
	// 			}else{
	// 				$so_beda[] = ['no_mesin'=>$so->no_mesin,
	// 					  // 'id_generate'=>$ns->id_generate,
	// 					  'id_detail'=>$ns->id_detail,
	// 					];
	// 				$beda++;
	// 			}
	// 		}else{
	// 			$dt_kosong[] = ['id_sales_order'=>$ns->id_sales_order];
	// 			$kosong++;
	// 		}
	// 	}
	// 	$result = ['kosong'=>$kosong,
	// 				'sama'=>$tot_sama,
	// 				'beda'=>$beda,
	// 				// 'data_sama'=>$so_sama,
	// 				'data_beda'=>isset($so_beda)?$so_beda:'',
	// 				'data_kosong'=>$dt_kosong
	// 		];
	// 	// $this->db->update_batch('tr_generate_list_unit_delivery_detail',$so_beda,'id_detail');
	// 	echo json_encode($result);
	// }
}
