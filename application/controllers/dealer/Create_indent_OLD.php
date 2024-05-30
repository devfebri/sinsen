<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Create_indent extends CI_Controller
{

	var $tables =   "tr_po_dealer_indent";
	var $folder =   "dealer";
	var $page   =		"create_indent";
	var $pk     =   "id_spk";
	var $title  =   "Indent Dealer";

	function mata_uang($a)
	{
		if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		return number_format($a, 0, ',', '.');
	}

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h1_dealer_pemesanan', 'm_pesan');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->helper('terbilang');



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
		$data['set']	= "view";
		$filter = [
			'status_in' => "'requested','proses','input','sent','approved','rejected'"
		];
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_indent'] = $this->m_pesan->getIndent($filter)->result();
		
		$this->template($data);
	}
	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "history";
		$filter = [
			'status_not_in' => "'requested','proses','input','sent','approved','rejected'",
			'no_mesin_null' => true
		];
		$data['dt_indent'] = $this->m_pesan->getIndent($filter)->result();
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}

	public function take_spk()
	{
		$id_spk = $this->input->post('id_spk');
		$sql = $this->db->query("SELECT tr_spk.*,tr_prospek.nama_konsumen,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk 
				INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer 
				INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
				WHERE tr_spk.no_spk = '$id_spk'");
		if ($sql->num_rows() > 0) {
			$dt_ve = $sql->row();
			echo "ok" . "|" . $dt_ve->nama_konsumen . "|" . $dt_ve->alamat . "|" . $dt_ve->no_ktp . "|" . $dt_ve->no_hp . "|" . $dt_ve->email . "|" . $dt_ve->id_tipe_kendaraan . "|" . $dt_ve->tipe_ahm . "|" . $dt_ve->id_warna . "|" . $dt_ve->warna;
		} else {
			echo "There is no data found!";
		}
	}
	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {
			$data['id_spk']            = $this->input->post('id_spk');
			$data['nama_konsumen']     = $this->input->post('nama_konsumen');
			$data['alamat']            = $this->input->post('alamat');
			$data['no_ktp']            = $this->input->post('no_ktp');
			$data['no_telp']           = $this->input->post('no_telp');
			$data['email']             = $this->input->post('email');
			$data['id_tipe_kendaraan'] = $this->input->post('id_tipe_kendaraan');
			$data['id_warna']          = $this->input->post('id_warna');
			$data['nilai_dp']          = $this->input->post('nilai_dp');
			$data['ket']               = $this->input->post('ket');
			$data['id_dealer']         = $this->m_admin->cari_dealer();
			$data['tgl']               = $this->input->post('tgl');
			$data['status']            = "input";
			$data['created_at']        = $waktu;
			$data['created_by']        = $login_id;
			$this->m_admin->insert($tabel, $data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/create_indent/add'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function detail()
	{
		$tabel             = $this->tables;
		$pk                = $this->pk;
		$id                = $this->input->get('id');
		$d                 = array($pk => $id);
		$data['mode']      = 'detail';
		$data['dt_indent'] = $this->m_admin->kondisi($tabel, $d);
		$data['dt_tipe']   = $this->m_admin->getSortCond("ms_tipe_kendaraan", "id_tipe_kendaraan", "ASC");
		$data['dt_warna']  = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['set']       = "detail";
		$this->template($data);
	}
	public function input_nilai()
	{
		$tabel             = $this->tables;
		$pk                = $this->pk;
		$id                = $this->input->get('id');
		$d                 = array($pk => $id);
		$data['mode']      = 'edit';
		$data['dt_indent'] = $this->m_admin->kondisi($tabel, $d);
		$data['dt_tipe']   = $this->m_admin->getSortCond("ms_tipe_kendaraan", "id_tipe_kendaraan", "ASC");
		$data['dt_warna']  = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['set']       = "detail";
		$this->template($data);
	}

	public function save_nilai()
	{
		$id_indent = $this->input->post('id_indent');
		$data['nilai_dp'] = $this->input->post('nilai_dp');
		$data['tgl']      = $this->input->post('tgl');
		$this->db->update('tr_po_dealer_indent', $data, ['id_indent' => $id_indent]);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/create_indent'>";
	}

	public function send()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");
		$data['status'] 					= "sent";
		$data['updated_at']				= $waktu;
		$data['updated_by']				= $login_id;
		$this->m_admin->update($tabel, $data, $pk, $id);
		$_SESSION['pesan'] 	= "Data has been sent successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/create_indent'>";
	}
	public function cancel()
	{
		// $waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		// $login_id		= $this->session->userdata('id_user');
		// $tabel			= $this->tables;
		// $pk 				= $this->pk;
		// $id					= $this->input->get("id");
		// $data['status'] 					= "cancel";
		// $data['updated_at']				= $waktu;
		// $data['updated_by']				= $login_id;
		// $this->m_admin->update($tabel, $data, $pk, $id);
		// $_SESSION['pesan'] 	= "Data has been cancel successfully";
		// $_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/cancel?id=".$this->input->get("id")."'>";
	}

	public function cetak_kwitansi()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");
		$get_indent = $this->db->query("SELECT * FROM $tabel WHERE id_spk = '$id' ");
		if ($get_indent->num_rows() > 0) {
			$get_indent = $get_indent->row()->cetak_kwitansi_ke + 1;
		} else {
			$get_indent = 1;
		}
		$data['status_cetak'] 					= "cetak_kwitansi";
		$data['cetak_kwitansi_ke'] 					= $get_indent;
		$data['updated_at']				= $waktu;
		$data['updated_by']				= $login_id;
		$this->m_admin->update($tabel, $data, $pk, $id);

		$row = $this->db->query("SELECT * FROM $tabel WHERE id_spk = '$id' ")->row();

		$pdf = new FPDF('p', 'mm', 'A4');
		$pdf->AddPage();
		// head
		$pdf->SetFont('ARIAL', 'B', 18);
		$pdf->Cell(190, 5, 'Cetak Kwitansi', 0, 1, 'C');
		$pdf->SetFont('ARIAL', 'B', 12);
		$pdf->Cell(190, 5, 'Cetakan Kwitansi Ke : ' . $row->cetak_kwitansi_ke, 0, 1, 'C');

		$pdf->Output();
	}


	public function cek_no_tt()
	{
		$tgl 						= date("d");
		$cek_tgl					= date("Y-m");
		$th 						= date("Y");
		$bln 						= date("m");
		$id_dealer = $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer' ");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
		} else {
			$get_dealer = '';
		}

		$pr_num 				= $this->db->query("SELECT *,mid(tgl_cetak_kwitansi,6,2)as bln FROM tr_po_dealer_indent WHERE LEFT(tgl_cetak_kwitansi,7) = '$cek_tgl' ORDER BY no_kwitansi DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {


			$row 	= $pr_num->row();
			$id = explode('/', $row->no_kwitansi);
			if (count($id) > 1) {
				if ($bln == $row->bln) {
					$kode 	= 'TTINDENDT/' . $get_dealer . '/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $id[4] + 1);
				} else {
					$kode 	= 'TTINDENDT/' . $get_dealer . '/' . $th . '/' . $bln . '/0001';
				}
			} else {
				$kode 	= 'TTINDENDT/' . $get_dealer . '/' . $th . '/' . $bln . '/0001';
			}
		} else {
			$kode 	= 'TTINDENDT/' . $get_dealer . '/' . $th . '/' . $bln . '/0001';
		}
		return $kode;
	}

	public function cetak_tandaterima()
	{


		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");

		$get_indent = $this->db->query("SELECT * FROM $tabel WHERE id_spk = '$id' ");
		if ($get_indent->num_rows() > 0) {
			$get = $get_indent->row();
			$get_indent = $get_indent->row()->cetak_kwitansi_ke + 1;
		} else {
			$get_indent = 1;
		}

		if ($get->no_kwitansi == null or $get->no_kwitansi == '') {
			$no_tt = $this->cek_no_tt();
			$data['no_kwitansi'] = $no_tt;
			$data['status_cetak'] 					= "cetak_kwitansi";
			$data['tgl_cetak_kwitansi'] 					= date('Y-m-d');
			$data['cetak_kwitansi_ke'] 					= $get_indent;
			$data['updated_at']				= $waktu;
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel, $data, $pk, $id);
		} else {
			$no_tt = $get->no_kwitansi;
		}

		$row = $this->db->query("SELECT tr_spk.*,tr_po_dealer_indent.*, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_dealer_indent
				INNER JOIN tr_spk on tr_po_dealer_indent.id_spk = tr_spk.no_spk
				LEFT JOIN ms_tipe_kendaraan ON tr_po_dealer_indent.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_po_dealer_indent.id_warna = ms_warna.id_warna
				WHERE tr_po_dealer_indent.id_spk = '$id'

							   ");
		if ($row->num_rows() > 0) {
			$row = $row->row();
			$pdf = new PDF_HTML('p', 'mm', 'A4');
			$pdf->AddPage();
			// head
			$pdf->SetFont('ARIAL', 'B', 13);
			$pdf->Cell(190, 5, 'Tanda Terima Pembayaran Indent', 0, 1, 'C');
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->SetX(54);
			$pdf->Cell(32, 5, "No. Tanda Terima", 0, 0, 'L');
			$pdf->Cell(30, 5, ": $no_tt", 0, 1, 'L');
			$pdf->SetX(54);
			$tgl_cetak = date('d-m-Y', strtotime($row->tgl_cetak_kwitansi));
			$pdf->Cell(32, 5, "Tanggal", 0, 0, 'L');
			$pdf->Cell(30, 5, ": $tgl_cetak", 0, 1, 'L');
			$pdf->Ln(9);
			$pdf->SetFont('ARIAL', '', 11);
			$pdf->Cell(35, 6, "No. SPK", 0, 0, 'L');
			$pdf->Cell(155, 6, ": $row->id_spk", 0, 1, 'L');
			$pdf->Cell(35, 6, "Nama Konsumen", 0, 0, 'L');
			$pdf->Cell(155, 6, ": $row->nama_konsumen", 0, 1, 'L');
			$pdf->Cell(35, 6, "No. HP", 0, 0, 'L');
			$pdf->Cell(155, 6, ": $row->no_hp", 0, 1, 'L');
			$pdf->Cell(35, 6, "Type Motor", 0, 0, 'L');
			$pdf->Cell(155, 6, ": $row->tipe_ahm", 0, 1, 'L');
			$pdf->Cell(35, 6, "Warna", 0, 0, 'L');
			$pdf->Cell(155, 6, ": $row->warna", 0, 1, 'L');
			$pdf->Cell(35, 6, "Sistem Pembelian", 0, 0, 'L');
			$pdf->Cell(155, 6, ": $row->jenis_beli", 0, 1, 'L');
			$terbilang_nominal_indent = ucwords(number_to_words($row->nilai_dp));
			$pdf->Cell(35, 6, "Nominal Indent", 0, 0, 'L');
			$pdf->Cell(155, 6, ": Rp. " . $this->mata_uang($row->nilai_dp), 0, 1, 'L');
			$terbilang_nominal_indent = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $terbilang_nominal_indent);
			$pdf->cell(35, 6, "Terbilang", 0, 0, 'L');
			$pdf->cell(2, 6, ": ", 0, 0, 'L');
			$pdf->Multicell(150, 6, "$terbilang_nominal_indent Rupiah", 0, 1);
			//  $pdf->Multicell(35, 6, "Perkiraan Waktu Pemenuhan Indent", 0, 1);
			// $y = $pdf->GetY();
			// $x = $pdf->GetX();
			// $width=35;
			//  $pdf->SetXY($x + $width, $y-12);
			$tgl_perkiraan = date('d-m-Y', strtotime($row->tgl));
			$pdf->cell(65, 6, "Perkiraan Waktu Pemenuhan Indent", 0, 0, 'L');
			$pdf->Cell(140, 6, ": " . $tgl_perkiraan, 0, 1, 'L');
			$pdf->Cell(120, 6, "(Waktu tersebut adalah waktu perkiraan)", 0, 1, 'L');

			$pdf->Output();
		} else {
			$_SESSION['pesan'] 	= 'Data Not Found';
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/create_indent'>";
		}
	}

	public function delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$cek_approval   = $this->m_admin->cek_approval($this->tables, $this->pk, $id);
		if ($cek_approval == 'salah') {
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
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
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/create_indent'>";
		}
	}

	public function notif_indent()
	{
		$tabel             = $this->tables;
		$pk                = $this->pk;
		$id                = $this->input->get('id');
		$row = $this->db->query("SELECT COUNT(id_item) AS jml,tipe_ahm,ms_warna.warna,id_warna,id_tipe_kendaraan
				FROM tr_scan_barcode
				JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON tr_scan_barcode.warna=ms_warna.id_warna
				WHERE tr_scan_barcode.no_mesin IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer='$id' AND po_indent='ya')
				GROUP BY id_item
				");
		if ($row->num_rows() > 0) {
			$row = $data['indent'] = $row->result();
			$data['row'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE id_penerimaan_unit_dealer='$id'
				")->row();
		}
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['set']       = "notif_indent";
		$this->template($data);
	}

	public function get_kode_indent()
	{
		$th               = date('Y');
		$bln              = date('m');
		$th_bln           = date('Y-m');
		$th_kecil         = date('y');
		$dmy              = date('dmy');
		$id_dealer        = $this->m_admin->cari_dealer();
		// $id_sumber     ='E20';
		// if ($id_dealer !=null) {
		$dealer           = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$id_sumber        = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM tr_po_dealer_indent
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$id_indent = substr($row->id_indent, -4);
			$new_kode   = 'INDENT/' . $id_sumber . '/' . $dmy . '/' . sprintf("%'.04d", $id_indent + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_po_dealer_indent', ['id_indent' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -5);
					$new_kode = 'INDENT/' . $id_sumber . '/' . $dmy . '/' . sprintf("%'.04d", $id_indent + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = 'INDENT/' . $id_sumber . '/' . $dmy . '/00001';
		}
		return strtoupper($new_kode);
	}

	public function newPO_ID($po_type, $id_dealer)
	{
		// $po_type = $this->input->get('type');
		// $id_dealer = $this->input->get('id_dealer');
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('Ym');
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

		$get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
		WHERE id_dealer='$id_dealer'
		ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			// $thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
			// if ($th_bln==$thbln_po) {
			$id       = substr($row->id_po, -4);
			$new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.04d", $id + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_po_dealer', ['id_po' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -4);
					$new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.04d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
			// }else{
			// 	$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
			// }
		} else {
			$new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/0001';
		}
		return strtoupper($new_kode);
		//echo strtoupper($new_kode);
	}

	function inject_indent()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$tanggal = get_ymd();
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}
		$no_spk = $this->input->get('no_spk');
		$cek_spk  = $this->db->query("SELECT * 
								FROM tr_spk spk
								JOIN tr_po_dealer_indent pod ON pod.id_spk=spk.no_spk
								WHERE no_spk='$no_spk'");
		if ($cek_spk->num_rows() == 0) {
			$spk = $this->db->query("SELECT * FROm tr_spk where no_spk='$no_spk' AND id_dealer='$id_dealer'");
			if ($spk->num_rows() > 0) {
				$spk  = $spk->row();

				//Set Indent
				$indent = [
					'id_indent' 				    => $this->get_kode_indent(),
					'id_spk'            => $spk->no_spk,
					'id_dealer'         => $spk->id_dealer,
					'nama_konsumen'     => $spk->nama_konsumen,
					'alamat'            => $spk->alamat,
					'no_ktp'            => $spk->no_ktp,
					'no_telp'           => $spk->no_hp,
					'email'             => $spk->email,
					'id_tipe_kendaraan' => $spk->id_tipe_kendaraan,
					'id_warna'          => $spk->id_warna,
					'nilai_dp'          => $spk->dp_stor,
					'ket'               => $spk->keterangan,
					'qty'               => 1,
					'status'			      => 'requested',
					'tgl'               => date('Y-m-d'),
					'created_at'        => waktu_full(),
					'created_by'        => user()->id_user
				];

				//Set PO
				$cek_po = $this->db->query("SELECT * FROM tr_po_dealer WHERE po_from='$spk->no_spk'");
				if ($cek_po->num_rows() == 0) {
					// send_json('No. SPK sudah ada di PO');
					$id_po = $this->newPO_ID('indent', $spk->id_dealer);

					$item = $this->db->query("SELECT id_item FROM ms_item WHERE id_tipe_kendaraan = '$spk->id_tipe_kendaraan' AND id_warna = '$spk->id_warna'");
					$id_item = ($item->num_rows() > 0) ? $item->row()->id_item : "";
					$bulan  = date("m");
					$tahun  = date("Y");
					$po_indent = [
						'id_po' 				      => $id_po,
						'bulan'               => $bulan,
						'tahun'               => $tahun,
						'tgl'     			      => $tanggal,
						'id_dealer'           => $spk->id_dealer,
						'created_at'          => waktu_full(),
						'created_by'          => user()->id_user,
						'po_from'             => $no_spk,
						'status' 				      => 'input',
						'jenis_po' 				    => 'PO Indent',
						'submission_deadline' => $tanggal,
						'id_pos_dealer'       => ''
					];
					$po_indent_detail = [
						'id_po' 				=> $id_po,
						'id_item'         => $id_item,
						'qty_order'         => 1,
						'qty_po_fix'         => 1
					];
				}


				// $tes = [
				// 	'indent' => $indent,
				// 	'po' => isset($po_indent) ? $po_indent : NULL,
				// 	'po_detail' => isset($po_indent_detail) ? $po_indent_detail : NULL
				// ];
				// send_json($tes);

				$this->db->trans_begin();
				if (isset($indent)) {
					$this->db->insert('tr_po_dealer_indent', $indent);
				}
				if (isset($po_indent)) {
					$this->db->insert('tr_po_dealer', $po_indent);
				}
				if (isset($po_indent_detail)) {
					$this->db->insert('tr_po_dealer_detail', $po_indent_detail);
				}
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					send_json('Telah terjadi kesalahan');
				} else {
					$this->db->trans_commit();
					$msg = 'Sukses melakukan inject indent';
					if (isset($po_indent)) {
						$msg .= ' dan PO';
					}
					send_json($msg);
				}
			} else {
				send_json('no spk tidak ditemukan');
			}
		} else {
			send_json('spk sudah masuk ke indent');
		}
	}
}
