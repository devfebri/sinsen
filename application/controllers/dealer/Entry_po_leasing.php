<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entry_po_leasing extends CI_Controller
{

	var $tables = "tr_entry_po_leasing";
	var $folder = "dealer";
	var $page   = "entry_po_leasing";
	var $title  = "Entry PO Finance Company";

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
		$this->load->model('m_fif');
		//===== Load Library =====
		// $this->load->library('upload');
		// $this->load->library('mpdf_l');
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
			$sub_array     = array();
			$button = '';
			// // $btn_del = "<a data-toggle='tooltip' onclick=\"return confirm('Are you sure to delete this data ?')\" title='Delete' href='dealer/pesan_d/delete?id=$rs->id_pesan'><button class='btn btn-flat btn-sm btn-danger'><i class='fa fa-trash'></i></button></a>";
			// $button = $btn_del;
			// $sub_array[] = "<a data-toggle='tooltip' href='dealer/pesan_d/detail?id=$rs->id_pesan'>$rs->id_pesan</a>";
			// $sub_array[] = $rs->id_hasil_survey;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->finance_company;
			$sub_array[] = $rs->po_dari_finco;
			$sub_array[] = $rs->tgl_pembuatan_po;
			$sub_array[] = $rs->tgl_pengiriman_po;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->no_ktp;
			$sub_array[] = ($rs->created_at);
			// $sub_array[] = $button;
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
		$order_column = array('id_pesan', 'tipe_pesan', 'konten', 'start_date', 'end_date', null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_entry_po_leasing.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE tr_entry_po_leasing.id_dealer=$id_dealer ";
		$finco = "SELECT finance_company FROM ms_finance_company WHERE id_finance_company=tr_spk.id_finance_company";

		if ($search != '') {
			$searchs .= "AND (nama_konsumen LIKE '%$search%' 
	          OR ($finco) LIKE '%$search%'
	          OR tr_entry_po_leasing.no_spk LIKE '%$search%'
	          OR tr_spk.no_ktp LIKE '%$search%'
	          OR tr_spk_gc.no_npwp LIKE '%$search%'
	          OR nama_npwp LIKE '%$search%'
	          OR po_dari_finco LIKE '%$search%'
	          OR tgl_pembuatan_po LIKE '%$search%'
	          OR tgl_pengiriman_po LIKE '%$search%'
						)
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("SELECT tr_entry_po_leasing.*,
			CASE 
				WHEN tr_spk.no_spk IS NOT NULL THEN tr_spk.no_spk
				ELSE tr_spk_gc.no_spk_gc
			END AS no_spk,
			CASE 
				WHEN tr_spk.nama_konsumen IS NOT NULL THEN tr_spk.nama_konsumen
				ELSE tr_spk_gc.nama_npwp
			END AS nama_konsumen,
			CASE 
				WHEN tr_spk.no_ktp IS NOT NULL THEN tr_spk.no_ktp
				ELSE tr_spk_gc.no_npwp
			END AS no_ktp,
			($finco) AS finance_company 
			FROM tr_entry_po_leasing 
   		LEFT JOIN tr_spk ON tr_entry_po_leasing.no_spk=tr_spk.no_spk
   		LEFT JOIN tr_spk_gc ON tr_entry_po_leasing.no_spk=tr_spk_gc.no_spk_gc
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
		$data['hasil'] = $this->db->query("SELECT tr_hasil_survey.*,tipe_ahm,warna,tr_spk.*,(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=tr_spk.id_finance_company) AS finance_company FROM tr_hasil_survey 
			JOIN tr_spk ON tr_hasil_survey.no_spk=tr_spk.no_spk
			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
			AND id_dealer=$id_dealer
			AND tr_hasil_survey.status_approval='approved' and tr_spk.status_spk = 'approved'
			ORDER BY tr_hasil_survey.created_at DESC");

		$data['hasil2'] = $this->db->query("SELECT tr_hasil_survey_gc.*,tipe_ahm,warna,tr_spk_gc.*,tr_spk_gc_kendaraan.id_tipe_kendaraan,tr_spk_gc_kendaraan.id_warna,
			(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=tr_spk_gc.id_finance_company) AS finance_company 
			FROM tr_hasil_survey_gc 
			JOIN tr_spk_gc ON tr_hasil_survey_gc.no_spk_gc=tr_spk_gc.no_spk_gc
			JOIN tr_spk_gc_kendaraan ON tr_spk_gc.no_spk_gc = tr_spk_gc_kendaraan.no_spk_gc
			JOIN ms_tipe_kendaraan ON tr_spk_gc_kendaraan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk_gc_kendaraan.id_warna
			AND tr_spk_gc.id_dealer='$id_dealer'
			AND tr_hasil_survey_gc.status_approval='approved'
			ORDER BY tr_hasil_survey_gc.created_at DESC");
		// $data['spk'] = $this->db->get('tr_spk');
		$this->template($data);
	}

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

	public function save()
	{
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();

		// $data['id_pesan']   = $this->get_();
		$id_hasil_survey           = $data['id_hasil_survey'] = $this->input->post('id_hasil_survey');
		$no_spk                    = $data['no_spk']     = $this->input->post('no_spk');
		$po                        = $data['po_dari_finco'] = $this->input->post('po_dari_finco');
		$data['tgl_pembuatan_po']  = $this->input->post('tgl_pembuatan_po');
		$data['tgl_pengiriman_po'] = $this->input->post('tgl_pengiriman_po');
		$data['id_dealer']         = $id_dealer;
		$data['created_at']        = $waktu;
		$data['created_by']        = $login_id;
		$spk = $this->db->get_where('tr_spk', ['no_spk' => $no_spk]);
		$spk_gc = $this->db->get_where('tr_spk_gc', ['no_spk_gc' => $no_spk]);
		if ($spk->num_rows() > 0) {
			$nama_konsumen = $spk->row()->nama_konsumen;
		} elseif ($spk_gc->num_rows() > 0) {
			$nama_konsumen = $spk->row()->nama_npwp;
		} else {
			$nama_konsumen = "";
		}
		$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['id_notif_kat' => 17])->row();
		$get_notif_grup = $this->db->get_where('ms_notifikasi_grup', ['id_notif_kat' => 17]);
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

		$notif = [
			'id_notif_kat' => $ktg_notif->id_notif_kat,
			'id_referensi' => $id_hasil_survey,
			'judul'        => "Entry PO Leasing",
			'pesan'        => "Aplikasi kredit a.n. $nama_konsumen telah disetujui dan PO dengan nomor $po telah diterima. Silahkan untuk memproses transaksi penjualan unit motor yang tertera pada PO.",
			'link'         => $ktg_notif->link . '/detail?id=' . $id_hasil_survey,
			'status'       => 'baru',
			'created_at'   => $waktu,
			'created_by'   => $login_id
		];
		$ins_manage = [
			'no_spk'          => $no_spk,
			'created_at'      => $waktu,
			'kategori'        => 'reminder po leasing',
			'status'          => 'Not Started',
			'detail_activity' => "Follow UP - Reminder PO Leasing Approved By Leasing ($nama_konsumen)",
			'id_dealer'       => $id_dealer,
			'created_by'      => $login_id
		];
		$this->db->trans_begin();
		$this->db->insert('tr_entry_po_leasing', $data);
		$this->db->insert('tr_notifikasi', $notif);
		if (isset($ins_manage)) {
			$this->db->insert('tr_manage_activity_after_dealing', $ins_manage);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/entry_po_leasing'>";
		}
	}

	public function delete()
	{
		$tabel			= $this->tables;
		$pk 			= 'id_pesan';
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
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pesan_d'>";
	}

	public function cetak()
	{
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_invoice = $this->input->get('id');

		$get_data = $this->db->query("SELECT tr_spk.*,tr_invoice_dp.id_invoice_dp,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,tr_invoice_dp.created_at FROM tr_invoice_dp
   			JOIN tr_spk ON tr_invoice_dp.id_spk=tr_spk.no_spk
   			WHERE id_invoice_dp='$id_invoice' ");
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();

			$upd = [
				'print_ke' => $row->print_ke + 1,
				'print_at' => $waktu,
				'print_by' => $login_id,
			];
			$this->db->update('tr_invoice_dp', $upd, ['id_invoice_dp' => $id_invoice]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print';
			$data['row'] = $row;

			$html = $this->load->view('dealer/pesan_d_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pesan_d'>";
		}
	}

	public function detail()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_pesan = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_pesan WHERE id_pesan='$id_pesan'");
		if ($row->num_rows() > 0) {
			$data['row'] = $row->row();
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pesan_d'>";
		}
		$this->template($data);
	}

	public function cek_entry_hasil()
	{
		$id_hasil_survey = $this->input->post('id_hasil_survey');
		$no_spk = $this->input->post('id_spk');
		$id_finco = $this->input->post('id_finco');

/*
		if ($id_finco == 'FC00000003') {
		  $get_detail_order_by_nospk = get_detail_order_by_nospk($id_spk);
		  if ($get_detail_order_by_nospk == '') {
			// code...
		  } else {
			$status_order = json_decode($get_detail_order_by_nospk);
			$po_no = $status_order->data[0]->object[0]->po_no;
			$date_po = $status_order->data[0]->object[0]->po_date;
			$date = str_replace("/","-",$date_po);
			$po_date = date('Y-m-d',strtotime($date));
		
			// kasih if apabila kosong utk no_po dan po_date karena array 0 rentan tidak ada data
		  }
		}
*/

		$this->db->where('id_hasil_survey' , $id_hasil_survey);
		$cek = $this->db->get("tr_entry_po_leasing");
		if ($cek->num_rows() > 0) {
			// echo json_encode('ada');
			$output = array(
				"msg" => 'ada',
				"no_po" => null,
				"po_date" => null
			);
			echo json_encode($output);
		} else {
			
			if ($id_finco == 'FC00000003') {
				$status_order = $this->m_fif->get_detail_order_fromdb_by_nospk($no_spk);
			    if ($status_order == FALSE) {

			    	// jika finco FIF tapi GC atau belum submit
					$output = array(
						"msg" => 'kosong',
						"no_po" => null,
						"po_date" => null				
					);
					echo json_encode($output);
			    } else {
			    	if ($status_order->num_rows() > 0) {
				    	$row = $status_order->row();
				    	$po_no = $row->po_no;
			            $date_po = $row->po_date;
			            $date = str_replace("/","-",$date_po);
			            $po_date = date('Y-m-d',strtotime($date));
				    } else {
				    	$get_detail_order_by_nospk = get_detail_order_by_nospk($no_spk);
				    	$status_order = json_decode($get_detail_order_by_nospk);
			            $po_no = $status_order->data[0]->object[0]->po_no;
			            $date_po = $status_order->data[0]->object[0]->po_date;
			            $date = str_replace("/","-",$date_po);
			            $po_date = date('Y-m-d',strtotime($date));
				    }
				    if (empty($po_no)) {
				    	$output = array(
							"msg" => 'kosong',
							"no_po" => null,
							"po_date" => null				
						);
						echo json_encode($output);
				    } else {
				    	$output = array(
							"msg" => 'kosong',
							"no_po" => $po_no,
							"po_date" => $po_date				
						);
						echo json_encode($output);
				    }
					
			    }
			} else {
				$output = array(
					"msg" => 'kosong',
					"no_po" => null,
					"po_date" => null				
				);
				echo json_encode($output);
			}

		}

	    
	    
	}
}
