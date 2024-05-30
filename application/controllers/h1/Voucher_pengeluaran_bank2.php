<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher_pengeluaran_bank extends CI_Controller
{

	var $tables = "tr_voucher_bank";
	var $folder = "h1";
	var $page   = "voucher_pengeluaran_bank";
	var $isi    = "bank_kas";
	var $pk     = "id_voucher_bank";
	var $title  = "Voucher Pengeluaran Bank";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->library('mpdf_l');
		$this->load->helper('terbilang');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('CustomFPDF2');


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
	public function mata_uang2($a){
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
			return number_format($a, 0, ',', '.');
		}
	public function index()
	{
		$data['isi']    = $this->isi;
		$data['title']	= $this->title;
		$data['page']   = $this->page;
		$data['set']		= "view";
		$data['dt_voucher']	= $this->db->query("SELECT * FROM tr_voucher_bank");
		$this->template($data);
	}
	public function add()
	{
		$data['isi']    = $this->isi;
		$data['page']   = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$this->template($data);
	}
	public function view()
	{
		$data['isi']    = $this->isi;
		$data['page']   = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "detail";
		$this->template($data);
	}
	public function cari_total()
	{
		$id = $this->input->post('id_voucher_bank');
		$pr_num	= $this->db->query("SELECT SUM(nominal_bg) as jum FROM tr_voucher_bank_bg WHERE id_voucher_bank = '$id'");
		if ($pr_num->num_rows() > 0) {
			$row = $pr_num->row();
			$sum =	$row->jum;
		} else {
			$sum = 0;
		}
		$pr_num2	= $this->db->query("SELECT SUM(nominal_transfer) as jum FROM tr_voucher_bank_transfer WHERE id_voucher_bank = '$id'");
		if ($pr_num2->num_rows() > 0) {
			$row2 = $pr_num2->row();
			$sum2 =	$row2->jum;
		} else {
			$sum2 = 0;
		}
		echo $sum + $sum2;
	}
	public function cari_coa()
	{
		$kode_coa = $this->input->post('kode_coa');
		$pr_num	= $this->db->query("SELECT * FROM ms_coa WHERE kode_coa = '$kode_coa'");
		if ($pr_num->num_rows() > 0) {
			$row = $pr_num->row();
			$coa =	$row->coa;
		} else {
			$coa = "";
		}
		echo $coa;
	}
	public function cetak_ulang()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl 				= gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		//$id = "2000042";
		$id = $this->input->get("id");
		$query			= "SELECT * FROM tr_voucher_bank INNER JOIN ms_rek_md	ON tr_voucher_bank.account = ms_rek_md.no_rekening	
										LEFT JOIN tr_voucher_bank_bg	ON tr_voucher_bank.id_voucher_bank = tr_voucher_bank_bg.id_voucher_bank
										LEFT JOIN tr_voucher_bank_transfer ON tr_voucher_bank.id_voucher_bank = tr_voucher_bank_transfer.id_voucher_bank
										LEFT JOIN ms_vendor ON tr_voucher_bank.dibayar = ms_vendor.id_vendor
										WHERE tr_voucher_bank.id_voucher_bank	= '$id'";
		$data['query'] = $this->db->query($query);
		$data['tgl'] = $tgl;
		$data['id_voucher_bank'] = $id;
		$sql2 = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id'")->row();
		$data['terbilang'] = ucwords(number_to_words($sql2->jum));
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;
		$data['cetak'] = 'voucher';
		$html = $this->load->view('h1/cetak_voucher_pengeluaran', $data, true);
		$mpdf->WriteHTML($html);
		$output = 'cetak_.pdf';
		$mpdf->Output("$output", 'I');
	}
	public function cetak()
	{
		//$pdf = new CustomFPDF();


		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$tgl 				= gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get("id");

		$row				= $this->db->query("SELECT * FROM tr_voucher_bank INNER JOIN ms_rek_md	ON tr_voucher_bank.account = ms_rek_md.no_rekening	
										LEFT JOIN tr_voucher_bank_bg	ON tr_voucher_bank.id_voucher_bank = tr_voucher_bank_bg.id_voucher_bank
										LEFT JOIN tr_voucher_bank_transfer ON tr_voucher_bank.id_voucher_bank = tr_voucher_bank_transfer.id_voucher_bank
										LEFT JOIN ms_vendor ON tr_voucher_bank.dibayar = ms_vendor.id_vendor
										WHERE tr_voucher_bank.id_voucher_bank	= '$id'")->row();

		$cari_bank = $this->m_admin->getByID('ms_rek_md','no_rekening',$row->account);
		$bank = ($cari_bank->num_rows()>0) ? $cari_bank->row()->bank : "" ;
		$account = ($cari_bank->num_rows()>0) ? $cari_bank->row()->no_rekening : "" ;

		$cari_bank2 = $this->m_admin->getByID('ms_rek_md','no_rekening',$row->no_rekening);
		$bank2 = ($cari_bank2->num_rows()>0) ? $cari_bank2->row()->bank : "" ;

		if($row->via_bayar == 'BG'){
			$tgl_bayar_a  = $row->tgl_bg;
		}else{
			$tgl_bayar_a  = $row->tgl_transfer;
		}

		$totNominal = 0;
		$sql2 = $this->db->query("SELECT * FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id'");
		foreach ($sql2->result() as $isi) {				
			if($isi->kode_coa == '2.01.21062.00' OR $isi->kode_coa == '2.01.21063.00' OR $isi->kode_coa == '2.01.21064.00' OR $isi->kode_coa == '5.01.5107.01' OR $isi->kode_coa == '5.01.5107.03' OR $isi->kode_coa == '5.01.5107.05' OR $isi->kode_coa == '6.01.6013.01' OR $isi->kode_coa == '6.02.6010.02' OR $isi->kode_coa == '6.03.6010.03' OR $isi->kode_coa == '6.03.6011.03'){      
				$totNominal-= $isi->nominal;      
			}else{  
				$totNominal+= $isi->nominal;
			}
		}
		$sq = $this->db->query("SELECT coa,SUM(nominal) AS nominal FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id' GROUP BY kode_coa");		
		$customer2 = $customer = $row->dibayar;
    $rek = $row->bank;
    $no_rek = $row->no_rekening;
    if ($row->tipe_customer == 'Dealer') {
      $cek_customer = $this->db->get_where('ms_dealer', ['id_dealer' => $row->dibayar]);
      $customer = $cek_customer = $cek_customer->num_rows() > 0 ? $cek_customer->row()->nama_dealer : '';
      $id_dealer = $row->dibayar;

      $cek_bank = $this->db->query("SELECT * FROM ms_norek_dealer 
      	LEFT JOIN ms_norek_dealer_detail ON ms_norek_dealer_detail.id_norek_dealer = ms_norek_dealer.id_norek_dealer
      	LEFT JOIN ms_bank ON ms_norek_dealer_detail.id_bank = ms_bank.id_bank
      	WHERE ms_norek_dealer.id_dealer = '$id_dealer' ORDER BY ms_norek_dealer_detail.id_norek_dealer_detail DESC LIMIT 0,1");
      $rek = $cek_bank->num_rows() > 0 ? $cek_bank->row()->bank : '';
      $no_rek = $cek_bank->num_rows() > 0 ? $cek_bank->row()->no_rek : '';

    }
    if ($row->tipe_customer == 'Vendor') {
      $cek_customer = $this->db->get_where('ms_vendor', ['id_vendor' => $row->dibayar]);
      $customer = $cek_customer->num_rows() > 0 ? $cek_customer->row()->vendor_name : '';
      $rek = $cek_customer->num_rows() > 0 ? $cek_customer->row()->nama_rekening : '';
      $customer2 = "";
    }

    if ($row->tipe_customer == 'Lain-lain') {      
      $customer2 = "";
    }

		global $tgl_entry,$bank,$no_bg,$tgl_bayar,$dealer,$terbilang,$nom,$coa,$bank2,$jum,$qq;
		$pdf = $this->customfpdf2->getInstance();
		$tgl_entry 		= tgl_indo($tgl);
		$bank 				= $row->bank." a/c ".$account;
		$no_bg 				= $row->no_bg;
		$tgl_bayar 		= tgl_indo($tgl_bayar_a);		
		$dealer 			= $customer." ".$no_rek. " ".$rek;
		$terbilang 		= number_to_words($totNominal);
		$nom 					= $this->mata_uang2($totNominal);		
		$bank2 				= $row->bank;
		$jum 					= $sq->num_rows();
		$qq 					= $sq->result();
		$coa = "ok";

		


		$pdf->AliasNbPages();
		$pdf->AddPage('l', array(165, 210));
		$pdf->SetAutoPageBreak(true, 48);		
		$x = $pdf->x;
    $y = $pdf->y;
    $push_right = 0;
    $pdf->SetFont('TIMES', '', 10);        
    $sambung = ($customer2!='') ? " - " : "" ;
		$pdf->SetWidths(array(155,30));
		$pdf->SetAligns(array('L','R'));				
    $pdf->Row(array($customer2.$sambung.str_replace('&nbsp;','',$row->deskripsi),"Rp. ".$this->mata_uang2($totNominal)));    								    
		
		$pdf->SetFont('times', '', 8);	
		$pdf->SetWidths(array(122,10,20));		
		$sql2 = $this->db->query("SELECT * FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id'");
		foreach ($sql2->result() as $isi) {	
			if($isi->kode_coa == '2.01.21062.00' OR $isi->kode_coa == '2.01.21063.00' OR $isi->kode_coa == '2.01.21064.00' OR $isi->kode_coa == '5.01.5107.01' OR $isi->kode_coa == '5.01.5107.03' OR $isi->kode_coa == '5.01.5107.05' OR $isi->kode_coa == '6.01.6013.01' OR $isi->kode_coa == '6.02.6010.02' OR $isi->kode_coa == '6.03.6010.03' OR $isi->kode_coa == '6.03.6011.03'){      																			
				$nominal = "(".$this->mata_uang2($isi->nominal).")";
			}else{
				$nominal = $this->mata_uang2($isi->nominal);
			}			
    	$sambung2 = ($customer2!='') ? " ( " : "" ;
    	$sambung3 = ($customer2!='') ? " ) " : "" ;

			$pdf->Row(array($isi->referensi.$sambung2.$isi->keterangan.$sambung3,": Rp.",$nominal));    								
		}		




		$pdf->Output();
	}
	public function cari_ref()
	{
		$referensi = $this->input->post('referensi');
		$total = $this->sisaRef($referensi);
		echo mata_uang_rp($total);
	}

	public function cari_id()
	{
		$th 						= date("y");
		$bln 						= date("m");
		$pr_num 				= $this->db->query("SELECT * FROM tr_voucher_bank ORDER BY id_voucher_bank DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$id 	= substr($row->id_voucher_bank, 2, 5);
			$kode = $th . sprintf("%05d", $id + 1);
		} else {
			$kode = $th . "00001";
		}
		echo $kode;
	}
	public function t_bg()
	{
		$id = $this->input->post('id_voucher_bank');
		$dq = "SELECT * FROM tr_voucher_bank_bg WHERE id_voucher_bank = '$id'";
		$data['dt_bg'] = $this->db->query($dq);
		$this->load->view('h1/t_voucher_bg', $data);
	}
	public function delete_bg()
	{
		$id 		= $this->input->post('id_voucher_bank_bg');
		$da 		= "DELETE FROM tr_voucher_bank_bg WHERE id_voucher_bank_bg = '$id'";
		$this->db->query($da);
		echo "nihil";
	}
	public function save_bg()
	{
		$id_voucher_bank			= $this->input->post('id_voucher_bank');
		$no_bg					= $this->input->post('no_bg');
		$c 			= $this->db->query("SELECT * FROM tr_voucher_bank_bg WHERE id_voucher_bank ='$id_voucher_bank' AND no_bg = '$no_bg'");
		$data['id_voucher_bank']		= $this->input->post('id_voucher_bank');
		$data['no_bg']				= $this->input->post('no_bg');
		$data['tgl_bg']				= $this->input->post('tgl_bg');
		$data['nominal_bg']		= $this->input->post('nominal_bg');
		if ($c->num_rows() == 0) {
			$this->m_admin->insert('tr_voucher_bank_bg', $data);
		} else {
			$rt = $c->row();
			$this->m_admin->update('tr_voucher_bank_bg', $data, "id_voucher_bank_bg", $rt->id_voucher_bank_bg);
		}
		echo "nihil";
	}
	public function t_transfer()
	{
		$id = $this->input->post('id_voucher_bank');
		$dq = "SELECT * FROM tr_voucher_bank_transfer WHERE id_voucher_bank = '$id'";
		$data['dt_transfer'] = $this->db->query($dq);
		$data['tipe'] = "voucher_pengeluaran_bank";
		$this->load->view('h1/t_transfer_voucher', $data);
	}
	public function delete_transfer()
	{
		$id 		= $this->input->post('id_voucher_bank_transfer');
		$da 		= "DELETE FROM tr_voucher_bank_transfer WHERE id_voucher_bank_transfer = '$id'";
		$this->db->query($da);
		echo "nihil";
	}
	public function save_transfer()
	{
		$data['id_voucher_bank']		= $this->input->post('id_voucher_bank');
		$data['tgl_transfer']				= $this->input->post('tgl_transfer');
		$data['nominal_transfer']		= $this->input->post('nominal_transfer');
		$this->m_admin->insert('tr_voucher_bank_transfer', $data);
		echo "nihil";
	}
	public function t_detail()
	{
		$id = $this->input->post('id_voucher_bank');
		$dq = "SELECT * FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id'";
		$data['dt_detail'] = $this->db->query($dq);
		$this->load->view('h1/t_voucher_detail', $data);
	}
	public function save_detail()
	{
		$id_voucher_bank	= $this->input->post('id_voucher_bank');
		$kode_coa					= $this->input->post('kode_coa');
		$c 			= $this->db->query("SELECT * FROM tr_voucher_bank_detail WHERE id_voucher_bank ='$id_voucher_bank' AND kode_coa = '$kode_coa'");
		$data['id_voucher_bank'] = $this->input->post('id_voucher_bank');
		$data['kode_coa']        = $this->input->post('kode_coa');
		$data['coa']             = $this->input->post('coa');
		$data['referensi']       = $this->input->post('referensi');
		$data['nominal']         = $this->m_admin->ubah_rupiah($this->input->post('nominal'));
		$data['sisa_hutang']     = $this->m_admin->ubah_rupiah($this->input->post('sisa_hutang'));
		$data['keterangan']      = $this->input->post('keterangan');
		$this->m_admin->insert('tr_voucher_bank_detail', $data);

		// if($c->num_rows()==0){
		// 	$this->m_admin->insert('tr_voucher_bank_detail',$data);										
		// }else{
		// 	$rt = $c->row();
		// 	$this->m_admin->update('tr_voucher_bank_detail',$data,"id_voucher_bank_detail",$rt->id_voucher_bank_detail);										
		// }
		echo "nihil";
	}
	public function delete_detail()
	{
		$id 		= $this->input->post('id_voucher_bank_detail');
		$da 		= "DELETE FROM tr_voucher_bank_detail WHERE id_voucher_bank_detail = '$id'";
		$this->db->query($da);
		echo "nihil";
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

			$count_rekap = isset($_POST['count_rekap']) ? $_POST['count_rekap'] : -1;
			$tot_rekap   = 0;
			if ($count_rekap > -1) {
				for ($i = 0; $i <= $count_rekap; $i++) {
					if (isset($_POST['chk_rekap_' . $i])) {
						$rekap[$i]['id_voucher_bank']	 = $this->input->post('id_voucher_bank');
						$rekap[$i]['no_rekap']        	 = $this->input->post('no_rekap_' . $i);
						$nominal = $rekap[$i]['nominal'] = $this->input->post('nominal_' . $i);
						$tot_rekap += $nominal;
					}
				}
			}

			$data['account']         = $this->input->post('account');
			$data['id_voucher_bank'] = $this->input->post('id_voucher_bank');
			$data['tgl_entry']       = $this->input->post('tgl_entry');
			$data['tipe_customer']   = $this->input->post('tipe_customer');
			$data['deskripsi']   		 = strip_tags($this->input->post('deskripsi'));
			$tipe_customer           = $this->input->post('tipe_customer');
			if ($tipe_customer == "Vendor") {
				$data['dibayar'] 				= $this->input->post('dibayar_v');
				$data['rekening_tujuan'] 	= $this->input->post('rekening_tujuan_v');
			} elseif ($tipe_customer == "Dealer") {
				$data['dibayar'] 				= $this->input->post('dibayar_d');
				$data['rekening_tujuan'] 	= $this->input->post('rekening_tujuan_d');
			} else {
				$data['dibayar'] 				= $this->input->post('dibayar_l');
				$data['rekening_tujuan'] 	= $this->input->post('rekening_tujuan_l');
			}
			$data['pph']              = $this->input->post('pph');
			$data['jenis_bayar']      = $this->input->post('jenis_bayar');
			$data['total_pembayaran'] = $this->input->post('total_pembayaran') - $tot_rekap;
			$data['via_bayar']        = $this->input->post('via_bayar');
			$data['status']           = "input";
			$data['created_at']       = $waktu;
			$data['created_by']       = $login_id;
			$this->m_admin->insert($tabel, $data);
			if (isset($rekap)) {
				$this->db->insert_batch('tr_voucher_bank_rekap', $rekap);
			}
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/voucher_pengeluaran_bank/add'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function batal()
	{
		$waktu              = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id           = $this->session->userdata('id_user');
		$tabel              = $this->tables;
		$pk                 = $this->pk;
		$no_do              = $this->input->get('id');
		$data['status']     = "batal";
		$data['updated_at'] = $waktu;
		$data['updated_by'] = $login_id;
		$this->m_admin->update($tabel, $data, $pk, $no_do);
		$_SESSION['pesan'] 	= "Data has been rejected successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/voucher_pengeluaran_bank'>";
	}

	public function getRekTujuan()
	{
		$tipe_customer = $this->input->post('tipe_customer');
		$id_dealer     = $this->input->post('id_dealer');
		$id_vendor     = $this->input->post('id_vendor');
		if ($tipe_customer == 'Dealer') {
			$get_rek_dealer = $this->db->query("SELECT ms_norek_dealer_detail.* FROM ms_norek_dealer_detail
				JOIN ms_norek_dealer ON ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
				JOIN ms_bank ON ms_norek_dealer_detail.id_bank=ms_bank.id_bank
				WHERE id_dealer=$id_dealer ORDER BY nama_rek ASC
			");
			if ($get_rek_dealer->num_rows() > 0) {
				foreach ($get_rek_dealer->result() as $rs) {
					echo "<option value='$rs->id_norek_dealer_detail'>$rs->nama_rek | $rs->no_rek</option>";
				}
			}
		}
		if ($tipe_customer == 'Vendor') {
			$get_rek_vendor = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor='$id_vendor'
			");
			if ($get_rek_vendor->num_rows() > 0) {
				foreach ($get_rek_vendor->result() as $rs) {
					echo "<option value='$rs->no_rekening'>$rs->nama_rekening | $rs->no_rekening</option>";
				}
			}
		}
	}
	public function cekNoBG()
	{
		$account = $this->input->post('account');
		$bank = $this->db->query("SELECT bank FROM ms_rek_md WHERE no_rekening='$account'")->row()->bank;
		$no_bg = $this->db->query("SELECT * FROM ms_cek_giro 
			JOIN ms_bank ON ms_cek_giro.bank=ms_bank.id_bank
			WHERE ms_bank.bank LIKE '%$bank%'
			AND ms_cek_giro.active=1
			AND kode_giro NOT IN (SELECT no_bg FROM tr_voucher_bank_bg WHERE no_bg IS NOT NULL)
			 ");
		if ($no_bg->num_rows() > 0) {
			foreach ($no_bg->result() as $rs) {
				echo "<option value='$rs->kode_giro'>$rs->kode_giro</option>";
			}
		}
	}

	public function tes()
	{
		$referensi = 'A/000501/2020';
		$cek31	= $this->db->query("SELECT * FROM tr_invoice WHERE no_faktur = 'A/000501/2020'");
		//echo $cek31->row()->jum;

		$ppn = 0;
		$pph = 0;
		$amount = 0;
		foreach ($cek31->result() as $isi) {
			$ppn    += $isi->ppn;
			$pph    += $isi->pph;
			$amount += $isi->harga;
		}
		$total = $ppn + $pph + $amount;

		$total2 = $this->m_admin->cekOnlyVoucherBank($referensi, $total);
		echo $total;
	}

	public function sisaRef($referensi)
	{
		//$referensi = $this->input->get("id");
		$cek1	= $this->db->query("SELECT total FROM tr_invoice_ekspedisi WHERE no_invoice_program = '$referensi'");
		$cek2	= $this->db->query("SELECT total FROM tr_invoice_penerimaan WHERE no_invoice_penerimaan = '$referensi'");
		$cek3	= $this->db->query("SELECT * FROM tr_invoice WHERE no_faktur = '$referensi'");
		//$cek31	= $this->db->query("SELECT SUM(harga+ppn+pph) AS jum FROM tr_invoice WHERE no_faktur = '$referensi'");						       		
		$cek4	= $this->db->query("SELECT total as total FROM tr_adm_bbn WHERE id_adm_bbn = '$referensi'");
		$cek5	= $this->db->query("SELECT total as total FROM tr_adm_bpkb WHERE id_adm_bpkb = '$referensi'");
		$cek6	= $this->db->query("SELECT total as total FROM tr_adm_stnk WHERE id_adm_stnk = '$referensi'");
		$cek7	= $this->db->query("SELECT total_bayar as total FROM tr_rekap_asuransi WHERE id_rekap_asuransi = '$referensi'");
		$cek8	= $this->db->query("SELECT *,(SELECT sum(harga) FROM tr_tagihan_lain_detail WHERE id_tagihan_lain=tr_tagihan_lain.id_tagihan_lain)as total FROM tr_tagihan_lain WHERE id_tagihan_lain='$referensi'");
		$cek9 = $this->db->query("SELECT IFNULL(SUM(nilai_potongan),0) AS jum FROM tr_claim_sales_program_detail 
							INNER JOIN tr_claim_dealer ON tr_claim_sales_program_detail.id_claim_dealer=tr_claim_dealer.id_claim
							WHERE id_claim_sp = '$referensi' 
							AND (tr_claim_dealer.status='approved' OR tr_claim_dealer.status='ulang' OR tr_claim_dealer.status='ajukan')");
		$cek10 = $this->db->query("SELECT SUM(total) AS jum FROM tr_rekap_tagihan_detail 
			JOIN tr_invoice_ekspedisi ON tr_rekap_tagihan_detail.id_penerimaan_unit=tr_invoice_ekspedisi.no_penerimaan
			WHERE id_rekap_tagihan='$referensi'");
		$cek11	= $this->db->query("SELECT total as total FROM tr_adm_jual WHERE id_adm_jual = '$referensi'");

		$cek12 = $this->db->query("SELECT no_mesin FROM tr_retur_dealer_detail WHERE no_retur_dealer = '$referensi'");




		if ($cek1->num_rows() > 0) {
			$row 		= $cek1->row();
			$total 	=	$row->total;
		} elseif ($cek2->num_rows() > 0) {
			$row2 	= $cek2->row();
			$total 	=	$row2->total;
		} elseif ($cek3->num_rows() > 0) {
			$ppn = 0;
			$pph = 0;
			$amount = 0;
			foreach ($cek3->result() as $isi) {
				$ppn    += $isi->ppn;
				$pph    += $isi->pph;
				$amount += $isi->harga;
			}
			$total = $ppn + $pph + $amount;
		} elseif ($cek4->num_rows() > 0) {
			$row4 	= $cek4->row();
			$total 	=	$row4->total;			
		} elseif ($cek5->num_rows() > 0) {
			$row5 	= $cek5->row();
			$total 	=	$row5->total;
		} elseif ($cek6->num_rows() > 0) {
			$row6 	= $cek6->row();
			$total 	=	$row6->total;
		} elseif ($cek11->num_rows() > 0) {
			$row11 	= $cek11->row();
			$total 	=	$row11->total;
		} elseif ($cek12->num_rows() > 0) {
			$np = "";
			$total_semua = 0;
			$harga = 0;
			$ppn = 0;
			foreach ($cek12->result() as $isi) {
				$cari_no_do = $this->db->query("SELECT tr_picking_list_view.id_item, tr_picking_list.no_do FROM tr_picking_list_view JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
					WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' ORDER BY tr_picking_list.no_do ASC LIMIT 0,1");
				$no_do = ($cari_no_do->num_rows() > 0) ? $cari_no_do->row()->no_do : "";
				$id_item = ($cari_no_do->num_rows() > 0) ? $cari_no_do->row()->id_item : "";

				$dt_do_reg = $this->db->query("SELECT * FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$no_do' AND qty_do>0 AND tr_do_po_detail.id_item = '$id_item'");
				$harga_asli = ($dt_do_reg->num_rows() > 0) ? $dt_do_reg->row()->harga : 0;
				$harga += $harga_asli;
				$ppn += $harga_asli * 0.1;

				$total_semua = ($harga + $ppn);
			}
			$total = $total_semua;
		} elseif ($cek7->num_rows() > 0) {
			$row7 	= $cek7->row();
			$total 	=	$row7->total;
		} elseif ($cek10->num_rows() > 0) {
			$total = $cek10->row()->jum;			
		} elseif ($cek8->num_rows() > 0) {
			$total = $cek8->row()->total;
		} elseif ($cek9->num_rows() > 0) {
			$total = $cek9->row()->jum;
		} else {
			$total = 0;
		}
		return $total = $this->m_admin->cekOnlyVoucherBank($referensi, $total);
		//echo $total = $this->m_admin->cekOnlyVoucherBank($referensi,$total);				
	}

	public function getRef()
	{
		// $id_dealer     = $this->input->get('id_dealer');
		// $id_vendor     = $this->input->get('id_vendor');
		// $tipe_customer = $this->input->get('tipe_customer');
		// $jenis_bayar   = $this->input->get('jenis_bayar');
		$id_dealer     = $this->input->post('id_dealer');
		$id_vendor     = $this->input->post('id_vendor');
		$tipe_customer = $this->input->post('tipe_customer');
		$jenis_bayar   = $this->input->post('jenis_bayar');
		$data = array();
		echo '<option value="">- choose -</option>';
		if ($tipe_customer == 'Dealer') {
			$item9 = $this->db->query("SELECT * FROM tr_claim_sales_program
							LEFT JOIN tr_sales_program on tr_claim_sales_program.id_program_md = tr_sales_program.id_program_md
							LEFT JOIN ms_dealer on tr_claim_sales_program.id_dealer=ms_dealer.id_dealer
					 		ORDER BY id_claim_sp DESC");
			echo 'Line 1';
			foreach ($item9->result() as $isi9) {
				$cek = $this->db->query("SELECT sum(perlu_revisi) as sum FROM tr_claim_sales_program_detail WHERE id_claim_sp='$isi9->id_claim_sp'");
				if ($cek->num_rows() > 0) {
					$cek = $cek->row();
					if ($cek->sum == 0) {
						if ($this->sisaRef($isi9->id_claim_sp) > 0) {
							echo "<option value='$isi9->id_claim_sp'>$isi9->id_program_md</option>";
						}
					}
				}
			}

			$item10 = $this->db->query("SELECT tr_retur_unit.*,ms_dealer.nama_dealer FROM tr_retur_unit LEFT JOIN tr_retur_dealer ON tr_retur_unit.no_retur_dealer = tr_retur_dealer.no_retur_dealer 
				LEFT JOIN ms_dealer ON tr_retur_dealer.id_dealer = ms_dealer.id_dealer
				WHERE tr_retur_dealer.status_retur_d = 'approved' AND tr_retur_dealer.id_dealer = '$id_dealer' ORDER BY no_retur_unit DESC");
			echo 'Line 1a';
			foreach ($item10->result() as $isi10) {
				if ($this->sisaRef($isi10->no_retur_dealer) > 0) {
					echo "<option value='$isi10->no_retur_dealer'>$isi10->no_retur_dealer</option>";
				}
			}
		}
		if ($tipe_customer == 'Lain-lain') {
			$item8 = $this->db->query("SELECT *,(SELECT sum(harga) FROM tr_tagihan_lain_detail WHERE id_tagihan_lain=tr_tagihan_lain.id_tagihan_lain)as tot FROM tr_tagihan_lain ORDER BY id_tagihan_lain ASC");
			echo 'Line 2';
			foreach ($item8->result() as $isi8) {
				if ($this->sisaRef($isi8->id_tagihan_lain) > 0) {
					echo "<option value='$isi8->id_tagihan_lain'>$isi8->id_tagihan_lain</option>";
				}
			}
		}

		if ($tipe_customer == 'Vendor') {
			// $item = $this->db->query("SELECT * FROM tr_invoice_ekspedisi ORDER BY no_invoice_program ASC");
			//     foreach ($item->result() as $isi) {
			//       echo "<option value='$isi->no_invoice_program'>$isi->no_invoice_program</option>";
			//     }
			// $item2 = $this->db->query("SELECT * FROM tr_invoice_penerimaan ORDER BY no_invoice_penerimaan ASC");
			//      foreach ($item2->result() as $isi2) {
			//        echo "<option value='$isi2->no_invoice_penerimaan'>$isi2->no_invoice_penerimaan</option>";
			//      }

			if ($id_vendor == 'AHM') {
				$item3 = $this->db->query("SELECT * FROM tr_invoice GROUP BY no_faktur ORDER BY no_faktur ASC");
				echo 'Line 3';
				foreach ($item3->result() as $isi3) {
					if ($this->sisaRef($isi3->no_faktur) > 0) {
						echo "<option value='$isi3->no_faktur'>$isi3->no_faktur</option>";
					}
				}
			} else {
				if ($jenis_bayar == 'Unit') {
					$item7 = $this->db->query("SELECT * FROM tr_rekap_asuransi WHERE id_vendor='$id_vendor' ORDER BY id_rekap_asuransi ASC");
					echo 'Line 4';
					foreach ($item7->result() as $isi7) {
						if ($this->sisaRef($isi7->id_rekap_asuransi) > 0) {
							echo "<option value='$isi7->id_rekap_asuransi'>$isi7->id_rekap_asuransi</option>";
						}
					}
					$item4 = $this->db->query("SELECT * FROM tr_adm_bbn WHERE nama_biro_jasa='$id_vendor' ORDER BY id_adm_bbn ASC");
					echo 'Line 5';
					//echo $this->sisaRef('201911/ABB/00003');
					foreach ($item4->result() as $isi4) {
						if ($this->sisaRef($isi4->id_adm_bbn) > 0) {
							echo "<option value='$isi4->id_adm_bbn'>$isi4->id_adm_bbn</option>";
						}
					}
					$item5 = $this->db->query("SELECT * FROM tr_adm_bpkb WHERE nama_biro_jasa='$id_vendor' ORDER BY id_adm_bpkb ASC");
					echo 'Line 6';
					foreach ($item5->result() as $isi5) {
						if ($this->sisaRef($isi5->id_adm_bpkb) > 0) {
							echo "<option value='$isi5->id_adm_bpkb'>$isi5->id_adm_bpkb</option>";
						}
					}
					$item6 = $this->db->query("SELECT * FROM tr_adm_stnk WHERE nama_biro_jasa='$id_vendor' ORDER BY id_adm_stnk ASC");
					echo 'Line 7';
					foreach ($item6->result() as $isi6) {
						if ($this->sisaRef($isi6->id_adm_stnk) > 0) {
							echo "<option value='$isi6->id_adm_stnk'>$isi6->id_adm_stnk</option>";
						}
					}
					$item8 = $this->db->query("SELECT * FROM tr_adm_jual WHERE nama_biro_jasa='$id_vendor' ORDER BY id_adm_jual ASC");
					echo 'Line 9';
					foreach ($item8->result() as $isi8) {
						if ($this->sisaRef($isi8->id_adm_jual) > 0) {
							echo "<option value='$isi8->id_adm_jual'>$isi8->id_adm_jual</option>";
						}
					}
				} else {
					echo 'Line 8';
					$item7 = $this->db->query("SELECT * FROM tr_rekap_tagihan WHERE id_vendor='$id_vendor' ORDER BY id_rekap_tagihan ASC");
					foreach ($item7->result() as $isi7) {
						echo "<option value='$isi7->id_rekap_tagihan'>$isi7->id_rekap_tagihan</option>";
					}
				} // Else Unit
			} // Else cek id vendor
		}
	}

	public function getRekap()
	{
		$tipe_customer    = $this->input->post('tipe_customer');
		$id_dealer        = $this->input->post('id_dealer');
		$id_vendor        = $this->input->post('id_vendor');
		$jenis_bayar      = $this->input->post('jenis_bayar');
		$data['set_page'] = 'rekap';
		$rekap            = array();
		$dt_rekap = $this->db->query("SELECT * FROM tr_rekap_ekspedisi 
			INNER JOIN ms_vendor ON tr_rekap_ekspedisi.id_vendor = ms_vendor.id_vendor
			 WHERE tr_rekap_ekspedisi.id_vendor='$id_vendor'
			 AND id_rekap_ekspedisi NOT IN(SELECT no_rekap FROM tr_voucher_bank_rekap JOIN tr_voucher_bank ON tr_voucher_bank_rekap.id_voucher_bank=tr_voucher_bank.id_voucher_bank WHERE (status!='batal' OR status='input'))
								ORDER BY tr_rekap_ekspedisi.id_rekap_ekspedisi DESC");
		foreach ($dt_rekap->result() as $row) {
			$tr = $this->db->query("SELECT SUM(total) as jum FROM tr_rekap_ekspedisi_detail WHERE id_rekap_ekspedisi = '$row->id_rekap_ekspedisi'")->row();
			$cek = $tr->jum;
			$cek = $this->m_admin->cekPembayaran($row->id_rekap_ekspedisi, $tr->jum);
			if ($cek > 0) {
				$rekap[] = [
					'no_rekap' => $row->id_rekap_ekspedisi,
					'nominal' => $cek
				];
			}
		}
		$data['rekap']   = $rekap;
		$this->load->view('h1/t_voucher_detail', $data);
	}

	public function edit()
	{
		$id_voucher_bank = $this->input->get('id');
		$cek = $this->db->get_where('tr_voucher_bank', ['id_voucher_bank' => $id_voucher_bank]);
		if ($cek->num_rows() > 0) {
			$row = $data['row'] = $cek->row();
			$data['isi']   = $this->isi;
			$data['page']  = $this->page;
			$data['title'] = $this->title;
			$data['mode']  = 'edit';
			$data['title'] = $this->title;
			$data['set']   = "form";
			$data['details'] = $this->db->query("SELECT * FROM tr_voucher_bank_detail WHERE id_voucher_bank='$id_voucher_bank'")->result();
			if ($row->via_bayar == 'Transfer') {
				$data['transfers'] = $this->db->query("SELECT * FROM tr_voucher_bank_transfer WHERE id_voucher_bank='$id_voucher_bank'")->result();
			}
			if ($row->via_bayar == 'BG') {
				$data['bg_'] = $this->db->query("SELECT * FROM tr_voucher_bank_bg WHERE id_voucher_bank='$id_voucher_bank'")->result();
			}
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/voucher_pengeluaran_bank'>";
		}
	}
	public function getRekTujuanEdit()
	{
		$tipe_customer   = $this->input->post('tipe_customer');
		$id_dealer       = $this->input->post('id_dealer');
		$id_vendor       = $this->input->post('id_vendor');
		$rekening_tujuan = $this->input->post('rekening_tujuan');
		if ($tipe_customer == 'Dealer') {
			$get_rek_dealer = $this->db->query("SELECT ms_norek_dealer_detail.* FROM ms_norek_dealer_detail
				JOIN ms_norek_dealer ON ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
				JOIN ms_bank ON ms_norek_dealer_detail.id_bank=ms_bank.id_bank
				WHERE id_dealer=$id_dealer ORDER BY nama_rek ASC
			");
			if ($get_rek_dealer->num_rows() > 0) {
				echo '<option value="">--choose--</option>';
				foreach ($get_rek_dealer->result() as $rs) {
					$select = $rs->id_norek_dealer_detail == $rekening_tujuan ? 'selected' : '';
					echo "<option value='$rs->id_norek_dealer_detail' $select>$rs->nama_rek | $rs->no_rek</option>";
				}
			}
		}
		if ($tipe_customer == 'Vendor') {
			$get_rek_vendor = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor='$id_vendor'
			");
			if ($get_rek_vendor->num_rows() > 0) {
				echo '<option value="">--choose--</option>';
				foreach ($get_rek_vendor->result() as $rs) {
					$select = $rs->no_rekening == $rekening_tujuan ? 'selected' : '';
					echo "<option value='$rs->no_rekening' $select>$rs->nama_rekening | $rs->no_rekening</option>";
				}
			}
		}
	}

	public function save_edit()
	{
		$waktu    = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$count_rekap = isset($_POST['count_rekap']) ? $_POST['count_rekap'] : -1;
		$tot_rekap   = 0;
		if ($count_rekap > -1) {
			for ($i = 0; $i <= $count_rekap; $i++) {
				if (isset($_POST['chk_rekap_' . $i])) {
					$rekap[$i]['id_voucher_bank']	 = $this->input->post('id_voucher_bank');
					$rekap[$i]['no_rekap']        	 = $this->input->post('no_rekap_' . $i);
					$nominal = $rekap[$i]['nominal'] = $this->input->post('nominal_' . $i);
					$tot_rekap += $nominal;
				}
			}
		}

		$id_voucher_bank = $this->input->post('id_voucher_bank');
		$data['account']         = $this->input->post('account');
		$data['tgl_entry']       = $this->input->post('tgl_entry');
		$tipe_customer = $data['tipe_customer']   = $this->input->post('tipe_customer');
		if ($tipe_customer == 'Vendor') {
			$data['dibayar']         = $this->input->post('dibayar_v');
			$data['rekening_tujuan'] = $this->input->post('rekening_tujuan_v');
		} elseif ($tipe_customer == "Dealer") {
			$data['dibayar']         = $this->input->post('dibayar_d');
			$data['rekening_tujuan'] = $this->input->post('rekening_tujuan_d');
		} else {
			$data['dibayar']         = $this->input->post('dibayar_l');
			$data['rekening_tujuan'] = $this->input->post('rekening_tujuan_l');
		}
		$data['pph']              = $this->input->post('pph');
		$data['jenis_bayar']      = $this->input->post('jenis_bayar');
		$data['deskripsi']   		 	= strip_tags($this->input->post('deskripsi'));
		$data['total_pembayaran'] = $this->input->post('total_pembayaran') - $tot_rekap;
		$via_bayar = $data['via_bayar']        = $this->input->post('via_bayar');
		if ($via_bayar == 'Transfer') {
			$transfers = $this->input->post('transfers');
			foreach ($transfers as $val) {
				$ins_transfer[] = [
					'id_voucher_bank' => $id_voucher_bank,
					'tgl_transfer' => $val['tgl_transfer'],
					'nominal_transfer' => (int) $val['nominal_transfer']
				];
			}
		}
		if ($via_bayar == 'BG') {
			$bg_ = $this->input->post('bg_');
			foreach ($bg_ as $val) {
				$ins_bg[] = [
					'id_voucher_bank' => $id_voucher_bank,
					'no_bg' => $val['no_bg'],
					'tgl_bg' => $val['tgl_bg'],
					'nominal_bg' => (int) $val['nominal_bg']
				];
			}
		}
		$details          = $this->input->post('details');
		foreach ($details as $key => $val) {
			$ins_detail[] = [
				'id_voucher_bank' => $id_voucher_bank,
				'kode_coa'    => $val['kode_coa'],
				'coa'         => $val['coa'],
				'referensi'   => $val['referensi'],
				'nominal'     => $val['nominal'],
				'sisa_hutang' => $val['sisa_hutang'],
				'keterangan'  => $val['keterangan']
			];
		}
		$data['status']           = "input";
		$data['updated_at']       = $waktu;
		$data['updated_by']       = $login_id;
		// echo json_encode($data);
		// die();
		$this->db->trans_begin();
		$this->db->update('tr_voucher_bank', $data, ['id_voucher_bank' => $id_voucher_bank]);
		$this->db->delete('tr_voucher_bank_detail', ['id_voucher_bank' => $id_voucher_bank]);
		if (isset($ins_detail)) {
			$this->db->insert_batch('tr_voucher_bank_detail', $ins_detail);
		}
		$this->db->delete('tr_voucher_bank_transfer', ['id_voucher_bank' => $id_voucher_bank]);
		if (isset($ins_transfer)) {
			$this->db->insert_batch('tr_voucher_bank_transfer', $ins_transfer);
		}
		$this->db->delete('tr_voucher_bank_bg', ['id_voucher_bank' => $id_voucher_bank]);
		if (isset($ins_bg)) {
			$this->db->insert_batch('tr_voucher_bank_bg', $ins_bg);
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
				'link' => base_url('h1/voucher_pengeluaran_bank')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}
}
