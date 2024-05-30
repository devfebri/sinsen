<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Aut0pr0s3s extends CI_Controller
{

	public function __construct()

	{
		parent::__construct();

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');
		$this->load->model('H1_model_nrfs','m_nrfs');
	}

	// public function tables()
	// {
	// 	$query = $this->db->query("SHOW TABLES");
	// 	echo "
	// 		<table>
	// 		<tr><td>Tabel</td</tr>
	// 	";
	// 	foreach ($query->result() as $val) {
	// 		// echo "<tr><td>$val->Tables_in_newmonju_honda_fix</td></tr>";
	// 		echo "<tr><td>$val->Tables_in_sinarsen_honda</td></tr>";
	// 	}
	// 	echo "</table>";
	// }

	// public function tables_field()
	// {
	// 	$query = $this->db->query("SHOW TABLES");
	// 	echo "
	// 		<table border=1>
	// 	";
	// 	// foreach ($query->result() as $val) {
	// 	// 	echo "<tr><td>$val->Tables_in_newmonju_honda_fix</td>";
	// 	// 	if ($val->Tables_in_newmonju_honda_fix!='TABLE 381') {
	// 	// 		if ($val->Tables_in_newmonju_honda_fix!='TABLE 382') {
	// 	// 			$field = $this->db->query("SHOW COLUMNS FROM $val->Tables_in_newmonju_honda_fix")->result();
	// 	// 			foreach ($field as $fl) {
	// 	// 				echo "<td>$fl->Field</td>";
	// 	// 			}
	// 	// 		}
	// 	// 	}
	// 	// 	echo '</tr>';
	// 	// }
	// 	foreach ($query->result() as $val) {
	// 		echo "<tr><td>$val->Tables_in_sinarsen_honda</td>";
	// 		if ($val->Tables_in_sinarsen_honda!='TABLE 381') {
	// 			if ($val->Tables_in_sinarsen_honda!='TABLE 382') {
	// 				$field = $this->db->query("SHOW COLUMNS FROM $val->Tables_in_sinarsen_honda")->result();
	// 				foreach ($field as $fl) {
	// 					echo "<td>$fl->Field</td>";
	// 				}
	// 			}
	// 		}
	// 		echo '</tr>';
	// 	}
	// 	echo "</table>";
	// }
	// function cek_stok()
	// {
	// 	$dealer = $this->db->query("SELECT * FROM ms_dealer WHERE h1=1 ORDER BY kode_dealer_md ASC");
	// 	echo '<table border=1>
	// 		<tr>
	// 		<td>Kode Dealer MD</>
	// 		<td>Dealer</>
	// 		<td>Unfill</>
	// 		<td>Total Stok</>
	// 	';
	// 	foreach ($dealer->result() as $rs) {
	// 		$stok = $this->db->query("SELECT IFNULL(COUNT(tr_scan_barcode.no_mesin),0) AS jum FROM tr_penerimaan_unit_dealer_detail
	//                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
	//                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
	//                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
	//                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
	//                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
	//                WHERE tr_penerimaan_unit_dealer.id_dealer = '$rs->id_dealer' 
	//                AND tr_scan_barcode.status = '4'")->row();
	// 		$unfil = $this->db->query("SELECT IFNULL(SUM(tr_do_po_detail.qty_do),0) AS jum FROM tr_do_po 
	//                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
	//                        LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
	//                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
	//                        AND tr_do_po.id_dealer = '$rs->id_dealer'")->row();
	// 		echo "
	// 		<tr>
	// 		<td>$rs->kode_dealer_md</td>
	// 		<td>$rs->nama_dealer</td>
	// 		<td>$unfil->jum</td>
	// 		<td>$stok->jum</td>
	// 		</tr>
	// 		";
	// 	}
	// 	echo '</table>';
	// }
	// public function po()
	// {
	// 	$po = $this->db->query("SELECT no_po
	// 							FROM tr_penerimaan_unit_dealer
	// 							JOIN tr_do_po ON tr_penerimaan_unit_dealer.no_do=tr_do_po.no_do
	// 							JOIN tr_po_dealer ON tr_do_po.no_po=tr_po_dealer.id_po
	// 							WHERE tr_penerimaan_unit_dealer.status='close'");
	// 	$count=0;
	// 	foreach ($po->result() as $rs) {
	// 		$upd_po = ['status'=>'closed'];
	// 		$this->db->update('tr_po_dealer',$upd_po,['id_po'=>$rs->no_po]);
	// 		$count++;
	// 	}
	// 	echo $count;
	// }

	public function nrfs()
	{
		$tgl1 = date('Y-m-d 01:01:00');
    	$tgl2 = date('Y-m-d H:i:s');
    	$data = $this->m_nrfs->generate_file($tgl1, $tgl2);

    	$content = "";
    	foreach ($data->result() as $rw) {
			$rw->no_rangka= 'MH1'.$rw->no_rangka;
			$content .= "$rw->md_code;$rw->date_at;$rw->nama_pemeriksa;$rw->id_part;$rw->gejala;$rw->penyebab;$rw->no_mesin;$rw->no_rangka;$rw->tanggal_penerimaan;$rw->perbaikan_gudang;$rw->id_ekspedisi;$rw->no_polisi;$rw->nama_kapal;$rw->butuh_po;$rw->no_po_urgent;$rw->estimasi_tgl_selesai;$rw->actual_tgl_selesai; \r\n";
		}
		$name_file = "AHM-E20-".date('ymd')."-".date('ymdhis').".NRFS";

		// jika mau disimpan

		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/downloads/nrfs/' . $name_file,"wb");
		fwrite($fp,$content);
		fclose($fp);

	}
	
	public function indent()
	{
		$data  = $this->db->query("
			SELECT
				tpdi.id_indent,
				'E20' AS kode_md,
				md.kode_dealer_ahm,
				tpdi.no_ktp,
				tpdi.id_tipe_kendaraan AS kode_varian,
				tpdi.id_warna AS kode_warna,
				'' AS kode_dummy_varian,
				'' AS kode_dummy_warna,
				ts.jenis_beli AS jenis_pembayaran,
				tpdi.date_konfirmasi AS tgl_unpaid,
				'' AS catatan,
				tpdi.status_kirim,
				ts.status_spk AS status_spk,
				ts.updated_at AS tgl_cancel_spk,
				ts.no_spk AS id_spk,
				tpdi.id_reasons AS id_reasons,
				tpdi.id_dealer 
			FROM
				tr_po_dealer_indent tpdi
				INNER JOIN ms_dealer md ON tpdi.id_dealer = md.id_dealer
				INNER JOIN tr_spk ts ON tpdi.id_spk = ts.no_spk 
				AND tpdi.send_ahm = '1' 
				AND tpdi.status_kirim NOT IN ( '0', '3', '4' )

			");

    	$content = "";
    	foreach ($data->result() as $rw) {

    		$flag_status = '1';
    		$jenis_pembayaran = '';
    		$tgl_paid = '';
    		$tgl_cancel = '';
    		$tgl_tgl_fulfillment = '';
    		$alasan_cancel_unpaid = '';
    		$alasan_cancel_paid = '';
    		$tgl_unpaid = '';
    		$kode_warna_final = '';
    		$eta_awal = '';
    		$eta_final = '';
    		$tgl_fulfillment = '';
    		$no_mesin = '';
    		$no_rangka = '';

    		

    		// jenis pembayaran
    		if ($rw->jenis_pembayaran == 'Cash') {
    			$jenis_pembayaran = '1';
    		} else {
    			$jenis_pembayaran = '2';
    		}

    		//tgl_paid
    		$cek_tjs_receipt = $this->db->get_where('tr_invoice_tjs_receipt', 
    			array('id_spk'=>$rw->id_spk));
    		if ($cek_tjs_receipt->num_rows() > 0) {
    			$tgl_paid = date('YmdHis',strtotime($cek_tjs_receipt->row()->tgl_pembayaran));
    			//jika tgl paid lbih kecil dari tgl unpaid
    			if (strtotime($tgl_paid) < strtotime($rw->tgl_unpaid)) {
    				$tgl_paid = date('YmdHis',strtotime($rw->tgl_unpaid));
    			}
    		} else {
    			$tgl_paid = '';
    		}
    		

    		// alasan cancel ketika paid
    		if ($tgl_paid != null && $rw->status_spk == 'canceled') {
    			$alasan_cancel_paid = $rw->id_reasons;
    		}

    		// alasan cancel ketika unpaid
    		if ($tgl_paid == null && $rw->status_spk == 'canceled') {
    			$alasan_cancel_unpaid = $rw->id_reasons;
    		}

    		// cek warna final
    		$this->db->select('id_warna');
    		$this->db->from('tr_spk ts');
    		$this->db->join('tr_sales_order tso', 'tso.no_spk = ts.no_spk', 'inner');
    		$this->db->where('tso.no_spk', $rw->id_spk);
    		$cek_warna_so = $this->db->get();
    		if ($cek_warna_so->num_rows() > 0) {
    			$kode_warna_final = $cek_warna_so->row()->id_warna;
    		}

    		//ambil tgl ETA awal
    		$tot_hari_eta_wal = $this->db->get_where('ms_master_lead_detail', array('id_tipe_kendaraan'=>$rw->kode_varian,'warna'=>$rw->kode_warna,'active'=>1));
    		if ($tot_hari_eta_wal->num_rows() > 0) {
    			$eta_awal = date('Ymd',strtotime('+'.$tot_hari_eta_wal->row()->total_lead_time.' days',strtotime($rw->tgl_unpaid)));
    		}

    		// ambil ETA final
    		$cek_so = $this->db->get_where('tr_sales_order', array('no_spk'=>$rw->id_spk));
    		if ($cek_so->num_rows() > 0) {
    			//tgl_tgl_fulfillment
    			$tgl_fulfillment = date('Ymd',strtotime($cek_so->row()->tgl_cetak_invoice));
    			$no_mesin = $cek_so->row()->no_mesin;
    			$no_rangka = 'MH1'.$cek_so->row()->no_rangka;
    			$eta_final = date('Ymd',strtotime($cek_so->row()->tgl_cetak_invoice));
    			// cek ETA FINAL harus  ETA AWAL > ETA FINAL
    			if (strtotime($eta_awal) < strtotime($eta_final)) {
    				$eta_awal = $eta_final;
    			}
    		}

    		//flag_status
    		if ($tgl_paid != null) {
    			$flag_status = '2';
    		}
    		if ($rw->status_spk == 'canceled') {
    			$flag_status = '3';
    			$tgl_cancel = date('YmdHis',strtotime($rw->tgl_cancel_spk));
    		} 

    		// sebelumnya
    		// if ($tgl_paid != null and $kode_warna_final != '' and $eta_final != '') {
    		if ( $kode_warna_final != '' and $eta_final != '') {
    			$flag_status = '4';
    		}

    		if ($rw->tgl_unpaid != null) {
    			$tgl_unpaid = date('YmdHis',strtotime($rw->tgl_unpaid));
    		}

    		//kirim jika flag status di atas status kmren
    		if ($rw->status_kirim < $flag_status or $rw->status_kirim == '1') {
    			$content .= "$rw->id_indent;$rw->kode_md;$rw->kode_dealer_ahm;$rw->no_ktp;$rw->kode_varian;$rw->kode_warna;$rw->kode_dummy_varian;$rw->kode_dummy_warna;$flag_status;$jenis_pembayaran;$tgl_unpaid;$tgl_paid;$tgl_cancel;$tgl_fulfillment;$no_rangka;$no_mesin;$alasan_cancel_unpaid;$alasan_cancel_paid;$kode_warna_final;$eta_awal;$eta_final;$rw->catatan; \r\n";
				// update status
				$this->db->where('id_indent', $rw->id_indent);
	    		$this->db->update('tr_po_dealer_indent', array('status_kirim'=>$flag_status));
    		}
			
		}
		$name_file = "AHM-E20-".date('ymd')."-".date('ymdhis').".UIND";

		// echo $content;

		// jika mau disimpan

		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/downloads/uind/' . $name_file,"wb");
		fwrite($fp,$content);
		fclose($fp);

		// $this->load->helper('download');
		// // auto download
		// force_download($name_file, $kon);
	}

	public function last_date()
	{
		$date = new DateTime('now');
		$date->modify('last day of this month');
		return $date->format('Y-m-d');
	}
	public function reminder()
	{
		if (function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
		$setting_h1        = $this->db->get('ms_setting_h1')->row();

		//Reminder SPK Unpaid
		$set_hari_spk      = $setting_h1->reminder_spk;
		$date_spk_search   = date_create(gmdate("Y-m-d"));
		date_add($date_spk_search, date_interval_create_from_date_string("-$set_hari_spk days"));
		$date_spk_reminder = date_format($date_spk_search, 'Y-m-d');

		$waktu 		= gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$spk_unpaid = $this->db->query("SELECT * FROM tr_spk WHERE status_spk='booking' AND tgl_spk<='$date_spk_reminder' AND no_spk NOT IN(SELECT no_spk FROM tr_manage_activity_after_dealing WHERE no_spk IS NOT NULL)")->result();
		$tot_spk_unpaid = 0;
		foreach ($spk_unpaid as $val) {
			$ins_manage[] = [
				'no_spk' => $val->no_spk,
				'created_at'      => $waktu,
				'kategori'        => 'reminder pembayaran ke customer',
				'status'          => 'Not Started',
				'id_dealer'       => $val->id_dealer,
				'detail_activity' => "Follow UP - Reminder untk customer $val->nama_konsumen agar segera melakukan pembayaran",
				'created_by'      => 0
			];
			$tot_spk_unpaid++;
		}

		//Reminder Service
		$set_hari_service        = $setting_h1->reminder_service;
		$date_tgl_kirim          = date_create(gmdate("Y-m-d"));
		date_add($date_tgl_kirim, date_interval_create_from_date_string("-$set_hari_service days"));
		$date_tgl_kirim_reminder = date_format($date_tgl_kirim, 'Y-m-d');

		$so_reminder_service = $this->db->query("SELECT tr_sales_order.*,nama_konsumen FROM tr_sales_order 
			JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
			WHERE tr_sales_order.tgl_pengiriman<='$date_tgl_kirim_reminder' AND status_delivery='delivered' 
			AND tr_sales_order.no_spk NOT IN (SELECT no_spk FROM tr_manage_activity_after_dealing WHERE no_spk IS NOT NULL)")->result();
		$tot_service = 0;
		foreach ($so_reminder_service as $val) {
			$ins_manage[] = [
				'no_spk' => $val->no_spk,
				'created_at'      => $waktu,
				'kategori'        => 'reminder service',
				'status'          => 'Not Started',
				'id_dealer'       => $val->id_dealer,
				'detail_activity' => "Follow Up – Reminder service untuk konsumen $val->nama_konsumen",
				'created_by'      => 0
			];
			$tot_service++;
		}
		if (isset($ins_manage)) {
			$this->db->insert_batch('tr_manage_activity_after_dealing', $ins_manage);
		}

		//Reminder Sales Fol UP
		$set_hari_sales_folup    = $setting_h1->reminder_sales_follow_up;
		$date_tgl_kirim          = date_create(gmdate("Y-m-d"));
		date_add($date_tgl_kirim, date_interval_create_from_date_string("-$set_hari_sales_folup days"));
		$date_tgl_kirim_reminder = date_format($date_tgl_kirim, 'Y-m-d');

		$so_reminder_folup = $this->db->query("SELECT tr_sales_order.*,nama_konsumen 
			FROM tr_sales_order 
			JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
			WHERE tr_sales_order.tgl_pengiriman<='$date_tgl_kirim_reminder' AND status_delivery='delivered' 
			AND tr_sales_order.id_sales_order NOT IN (SELECT id_sales_order FROM tr_reminder_follow_up WHERE id_sales_order IS NOT NULL)")->result();
		$tot_folup = 0;
		foreach ($so_reminder_folup as $fol) {
			$ins_folup[] = [
				'id_sales_order' => $fol->id_sales_order,
				'created_at'      => $waktu,
				'kategori'        => 'reminder service',
				'status_aktivitas'          => 'to_contact',
				'id_dealer'       => $fol->id_dealer,
				'tgl_reminder'	  => $waktu,
				'created_by'      => 0
			];
			$tot_folup++;
		}
		if (isset($ins_folup)) {
			$this->db->insert_batch('tr_reminder_follow_up', $ins_folup);
		}
		// SMS Notif Selamat Ulang Tahun
		$date = date('m-d');
		$ymd = date('Y-m-d');
		$kons = $this->db->query("SELECT * FROM tr_spk WHERE (status_spk!='closed' OR status_spk!='close') AND RIGHT(tgl_lahir,5)='$date' AND no_hp IS NOT NULL");
		$tot_ultah_sukses = 0;
		$tot_ultah_gagal = 0;
		if ($kons->num_rows() > 0) {
			foreach ($kons->result() as $rs) {
				// $pesan = "SINAR SENTOSA. Selamat Ulang Tahun $rs->nama_konsumen.";
				// $status = sms_zenziva($rs->no_hp,$pesan);
				$pesan_sms = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Ucapan Selamat Ulang Tahun' AND id_dealer='$rs->id_dealer'  AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1");
				if ($pesan_sms->num_rows() > 0) {
					$pesan  = $pesan_sms->row()->konten;
					$id_get = [
						'NamaDealer' => $rs->id_dealer,
						'NamaCustomer' => $rs->no_spk
					];
					$status_ultah = sms_zenziva($rs->no_hp, pesan($pesan, $id_get));
					// $notif_sms_indent_status = $status_ultah['status'];
					// $notif_sms_indent_at     = $waktu;
					// $notif_sms_indent_by     = $login_id;
					if ($status_ultah['status'] == 0) {
						$tot_ultah_sukses++;
					} elseif ($status_ultah['status'] == 1) {
						$tot_ultah_gagal++;
					}
				}
			}
		}

		// SMS Tahun Baru
		$msg_tahun_baru = '';
		if ($date == '01-01') {
			$tahun = date('Y');
			$kons = $this->db->query("SELECT * FROM tr_spk WHERE (status_spk!='closed' OR status_spk!='close') AND no_hp IS NOT NULL ");
			$tot_tahun_baru_sukses = 0;
			$tot_tahun_baru_gagal = 0;
			if ($kons->num_rows() > 0) {
				foreach ($kons->result() as $rs) {
					// $pesan = "SINAR SENTOSA. Selamat Tahun Baru $tahun untuk pelanggan kami $rs->nama_konsumen";
					// $status = sms_zenziva($rs->no_hp,$pesan);
					$pesan_sms = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Ucapan Selamat Tahun Baru Masehi' AND id_dealer='$rs->id_dealer'  AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1 ");
					if ($pesan_sms->num_rows() > 0) {
						$pesan  = $pesan_sms->row()->konten;
						$id_get = [
							'NamaDealer' => $rs->id_dealer,
							'NamaCustomer' => $rs->no_spk
						];
						$status_th_baru = sms_zenziva($rs->no_hp, pesan($pesan, $id_get));
						// $notif_sms_indent_status = $status_th_baru['status'];
						// $notif_sms_indent_at     = $waktu;
						// $notif_sms_indent_by     = $login_id;
						if ($status_th_baru['status'] == 0) {
							$tot_tahun_baru_sukses++;
						} elseif ($status_th_baru['status'] == 1) {
							$tot_tahun_baru_gagal++;
						}
					}
				}
			}
			$msg_tahun_baru = "Notif Tahun baru Berhasil Dikirim = $tot_tahun_baru_sukses, Notif Tahun baru gagal Dikirim = $tot_tahun_baru_gagal";
		}
		//Close PO
		$tot_po_close = 0;
		$msg_po_close = '';
		$last_date = $this->last_date();
		if ($ymd == $last_date) {
			$po = $this->db->query("UPDATE tr_po_dealer SET status='closed', updated_at='$waktu' 
				WHERE id_po IN(SELECT id_po FROM tr_po_dealer WHERE status!='closed' AND id_po NOT IN(SELECT no_po FROM tr_do_po))");
			$tot_po_close = $this->db->affected_rows();
			$msg_po_close = ', PO Close = ' . $tot_po_close;
		}
		echo 'unpaid = ' . $tot_spk_unpaid . ', service = ' . $tot_service . ', Sales Fol Up = ' . $tot_folup . ', Notif Ulang Tahun Berhasil Dikirim = ' . $tot_ultah_sukses . ' Notif Ulang Tahun Gagal Dikirim = ' . $tot_ultah_gagal . ' ' . $msg_tahun_baru . $msg_po_close;
	}
	// public function tes()
	// {
	//   $fileLocation = getenv("DOCUMENT_ROOT") . "/download/myfile.txt";
	//   $file = fopen($fileLocation,"w");
	//   $content = "Your text here";
	//   fwrite($file,$content);
	//   fclose($file);
	// }
	public function cari_id()
	{
		$th 						= date("y");
		$bln 						= date("m");
		$pr_num 				= $this->db->query("SELECT * FROM tr_ssu ORDER BY id_ssu DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$id 	= substr($row->id_ssu, 2, 5);
			$kode = $th . sprintf("%05d", $id + 1);
		} else {
			$kode = $th . "00001";
		}
		return $kode;
	}
	// public function tes_ssu($id_ssu)
	// {
	// 	$ssu = $this->db->get_where('tr_ssu',['id_ssu'=>$id_ssu]);
	// 	if ($ssu->num_rows()==0) {
	// 		echo 'ID SSU tidak ditemukan !';
	// 		exit;
	// 	}else{
	// 		$ssu = $ssu->row();
	// 	}
	// 	$nama_file = date("dmY", strtotime($ssu->start_date));
	// 	$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/ssu/".$no.".SSU";
	//   //$fileLocation = getenv("DOCUMENT_ROOT") . "/web_honda/downloads/ssu/".$no.".SSU";
	//   $file = fopen($fileLocation,"w");		
	//   $content = "";

	// 	$sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
	// 			INNER JOIN tr_sales_order ON tr_ssu_detail.no_mesin = tr_sales_order.no_mesin
	// 			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE tr_ssu.id_ssu = '$id_ssu'");
	// 	foreach ($sql->result() as $isi) {	
	// 		$scan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);	
	// 		if($scan->num_rows() > 0){
	// 			$r = $scan->row();
	// 			$tipe = $r->tipe;
	// 			if($tipe == 'PINJAMAN') $tipe = "NRFS";
	// 			$tgl_penerimaan = $r->tgl_penerimaan;
	// 			$tanggal_p = date("dmY", strtotime($tgl_penerimaan));    
	// 		}else{	
	// 			$tipe = "";	
	// 			$tanggal_p = "";
	// 		}
	// 		$dealer = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
	// 						INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
	// 						INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
	// 						WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin'");	
	// 		if($dealer->num_rows() > 0){
	// 			$d = $dealer->row();
	// 			$kode_dealer_md = $d->kode_dealer_md;		
	// 			if($kode_dealer_md=='PSB'){
	// 				$kode_dealer_md = '13384';
	// 			}
	// 			$tgl_terima = date("dmY", strtotime($d->tgl_penerimaan));    
	// 		}else{
	// 			$kode_dealer_md = "";
	// 			$tgl_terima = "";
	// 		}

	// 		$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
	// 						INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
	// 						WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
	// 		if($cek_md->num_rows() > 0){
	// 			$y = $cek_md->row();		
	// 			$tgl_md = date("dmY", strtotime($y->tgl_faktur));
	// 		}else{
	// 			$tgl_md = "";
	// 		}

	// 		if($tgl_md==""){
	// 			if ($scan->num_rows()>0) {
	// 				$tgl_md = date("dmY", strtotime($scan->row()->tgl_faktur_invoice));
	// 			}else{
	// 				$tgl_md = '';
	// 			}
	// 		}
	// 		if ($tgl_md=='') {
	// 			// $content .= 'not_found:'.$isi->no_mesin;
	// 			// $content .='</br>';
	// 			continue;
	// 		}

	// 		$waktu								= gmdate("Y-m-d H:i:s", time()+60*60*7);    
	// 		$login_id							= $this->session->userdata('id_user');

	// 		$dat['generate_ssu']	= $login_id;
	// 		$dat['generate_date']	= $waktu;
	// 		// $cek3 = $this->m_admin->update("tr_sales_order",$dat,"no_mesin",$isi->no_mesin);											

	// 		$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));		
	// 		$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
	// 		$id_kelurahan = $isi->id_kelurahan2;
	// 		$prov = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
	// 			INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
	// 			INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
	// 			WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
	// 		if($prov->num_rows() > 0){
	// 			$pro = $prov->row();
	// 			$id_provinsi = $pro->id_provinsi;
	// 			$id_kabupaten = $pro->id_kabupaten;
	// 			$id_kecamatan = $pro->id_kecamatan;
	// 			$id_kelurahan = $pro->id_kelurahan;
	// 		}else{
	// 			$id_provinsi = "";$id_kelurahan="";$id_kecamatan="";$id_provinsi="";
	// 		}

	// 		$tr_prospek = $this->m_admin->getByID("tr_prospek","id_customer",$isi->id_customer);
	// 		if($tr_prospek->num_rows() > 0){
	// 			$r = $tr_prospek->row();
	// 			$id_flp = $r->id_flp_md;
	// 		}else{
	// 			$id_flp = "";
	// 		}

	// 		if($isi->jenis_beli == 'Cash'){
	// 			$jenis_beli = 1;
	// 			$dp_stor = "";
	// 			$tenor = "";
	// 			$angsuran = "";
	// 			$id_finance_company = '';
	// 		}else{
	// 			$dp_stor = $isi->dp_stor;
	// 			$tenor = $isi->tenor;
	// 			if($isi->id_finance_company != '' OR $isi->id_finance_company != '- Choose -' OR $isi->id_finance_company != ' - Choose - '){
	// 				$id_finance_company = $isi->id_finance_company;
	// 			}else{
	// 				$id_finance_company = '';
	// 			}
	// 			$angsuran = $isi->angsuran;
	// 			$jenis_beli = 2;
	// 		}

	// 		$sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
	// 			WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'");
	// 		$tgl_sj = "";
	// 		if($sj->num_rows() > 0){
	// 			$tgl_sj = date("dmY", strtotime($sj->row()->tgl_surat));				
	// 		}

	// 		$tgl_spes_md = "";
	// 		$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
	// 			WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
	// 		if($tgl_sp->num_rows() > 0){
	// 			// $tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));		
	// 			$tgl_spes_md =$tgl_sp->row()->tgl_spes;
	// 		}

	// 		$content .= "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";".$tgl_sj.";".$kode_dealer_md.";".$tgl_spes_md.";".$tgl_md.";".$tgl_create_ssu.";".$tgl_cetak_invoice.";".$tgl_cetak_invoice.";".$jenis_beli.";".$id_finance_company.";".$dp_stor.";".$tenor.";".$angsuran.";".$tgl_terima.";I;".$id_provinsi.";".$id_kabupaten.";".$id_kecamatan.";".$id_kelurahan.";".$id_flp.";;";
	// 		$content .= "\r\n";		
	// 		// $content .= "<br>";
	// 	}
	// 	// echo $content;
	// 	fwrite($file,$content);
	//   	fclose($file);	
	// }
	public function ssu()
	{
		$tgl        = gmdate("dmY", time() + 60 * 60 * 7);
		//$tgl        = '29122019';//testing			

		$hari_ini   = gmdate("Y-m-d", time() + 60 * 60 * 7);
		//$hari_ini   = '2019-12-29';//Testing
		if (isset($_GET['tanggal'])) {
			$hari_ini = $_GET['tanggal'];
			$tgl = date('dmY', strtotime($hari_ini));
		}
		$id_ssu     = $this->cari_id();
		$nama_file  = "E20-" . $tgl;
		$start_date = $hari_ini;
		$end_date   = $hari_ini;			
		$tanggal    = gmdate("Y-m-d", time() + 60 * 60 * 7);
		if (isset($_GET['tanggal'])) {
			$tanggal = date('Y-m-d', strtotime($hari_ini));
		}

		$cek_bulan 	= explode("-", $tanggal);
 		$amb_bulan 	= $cek_bulan[1];
 		$amb_tahun 	= $cek_bulan[0];

		$waktu      = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id      = $this->session->userdata('id_user');
		$sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
			WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.generate_ssu IS NULL OR tr_sales_order.generate_ssu = '' OR tr_sales_order.generate_date IS NULL)
			AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)");
		foreach ($sql->result() as $isi) {
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek  = $this->m_admin->getByID("tr_ssu_detail", "no_mesin", $isi->no_mesin);
			if ($cek->num_rows() == 0) {
				$cek1 = $this->m_admin->insert("tr_ssu_detail", $da);
			}
		}
		$sql_gc = $this->db->query("SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.no_mesin,tr_spk_gc.nama_npwp AS nama_konsumen,tr_scan_barcode.no_rangka,tr_spk_gc.no_ktp,tr_spk_gc.alamat FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
			INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order_gc.generate_ssu IS NULL OR tr_sales_order_gc.generate_ssu = '' OR tr_sales_order_gc.generate_date IS NULL)
			AND (tr_sales_order_gc.status_so = 'so_invoice' OR tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL OR tr_sales_order_gc.tgl_cetak_invoice2 IS NOT NULL)");
		foreach ($sql_gc->result() as $isi) {
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek  = $this->m_admin->getByID("tr_ssu_detail", "no_mesin", $isi->no_mesin);
			if ($cek->num_rows() == 0) {
				$cek1 = $this->m_admin->insert("tr_ssu_detail", $da);
			}
		}
		$data['start_date'] = $start_date;
		$data['end_date']   = $end_date;
		$data['nama_file']  = $nama_file . ".SSU";
		$cek_dulu  = $this->m_admin->getByID("tr_ssu", "nama_file", $nama_file);
		if ($cek_dulu->num_rows() > 0) {
			$cek2 = $this->m_admin->update("tr_ssu", $data, "id_ssu", $cek_dulu->row()->id_ssu);
		} else {
			$data['id_ssu']				= $id_ssu;
			$cek2 = $this->m_admin->insert("tr_ssu", $data);
		}

		$no 		= $nama_file;
		$id_ssu = $id_ssu;
		//$this->load->view("h1/file_ssu",$dt);	


		$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/ssu/" . $no . ".SSU";
		//$fileLocation = getenv("DOCUMENT_ROOT") . "/web_honda/downloads/ssu/".$no.".SSU";
		$file = fopen($fileLocation, "w");
		$content = "";

		// $sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
		// 		INNER JOIN tr_sales_order ON tr_ssu_detail.no_mesin = tr_sales_order.no_mesin
		// 		INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE tr_ssu.id_ssu = '$id_ssu'");
		$sql = $this->db->query("SELECT *,tr_sales_order.tgl_create_ssu FROM tr_ssu_detail
			JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
			JOIN tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE '$hari_ini' BETWEEN start_date AND end_date AND tgl_cetak_invoice='$hari_ini'
			GROUP BY tr_ssu_detail.no_mesin");
		// $content .= "Penjualan";
		// $content .= "\r\n";		
		$tot = 0;
		$no = 0;
		foreach ($sql->result() as $isi) {
			$scan = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin);
			if ($scan->num_rows() > 0) {
				$r = $scan->row();
				$tipe = $r->tipe;
				if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
				$tgl_penerimaan = $r->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));
			} else {
				$tipe = "";
				$tanggal_p = "";
			}
			$dealer = $this->db->query("SELECT *,tr_penerimaan_unit_dealer.tgl_penerimaan AS tgl_terima FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
				INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
				WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin' ORDER BY tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer_detail DESC LIMIT 0,1");
			if ($dealer->num_rows() > 0) {
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
				if ($kode_dealer_md == 'PSB') {
					$kode_dealer_md = '13384';
				}
				if ($d->pos == 'Ya') {
					$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $d->id_dealer_induk);
					$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
				}
				$tgl_terima = date("dmY", strtotime($d->tgl_terima));
			} else {
				$kode_dealer_md = "";
				$tgl_terima = "";
			}

			$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
				INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
				WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC LIMIT 0,1");
			if ($cek_md->num_rows() > 0) {
				$y = $cek_md->row();
				$tgl_md = date("dmY", strtotime($y->tgl_faktur));
			} else {
				$tgl_md = "";
			}

			if ($tgl_md == "") {
				$tgl_faktur_invoice = (isset($scan->row()->tgl_faktur_invoice)) ? $scan->row()->tgl_faktur_invoice : "";
				$tgl_md = date("dmY", strtotime($tgl_faktur_invoice));
			}

			$waktu								= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id							= $this->session->userdata('id_user');

			$dat['generate_ssu']	= $login_id;
			$dat['generate_date']	= $waktu;
			$cek3 = $this->m_admin->update("tr_sales_order", $dat, "no_mesin", $isi->no_mesin);

			$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));
			$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
			$id_kelurahan = $isi->id_kelurahan2;
			$prov = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
				INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
				WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
			if ($prov->num_rows() > 0) {
				$pro = $prov->row();
				$id_provinsi = $pro->id_provinsi;
				$id_kabupaten = $pro->id_kabupaten;
				$id_kecamatan = $pro->id_kecamatan;
				$id_kelurahan = $pro->id_kelurahan;
			} else {
				$id_provinsi = "";
				$id_kelurahan = "";
				$id_kecamatan = "";
				$id_provinsi = "";
			}

			$tr_prospek = $this->m_admin->getByID("tr_prospek", "id_customer", $isi->id_customer);
			if ($tr_prospek->num_rows() > 0) {
				$r = $tr_prospek->row();
				$id_flp = $r->id_flp_md;
			} else {
				$id_flp = "";
			}

			if ($isi->jenis_beli == 'Cash') {
				$jenis_beli = 1;
				$dp_stor = "";
				$tenor = "";
				$angsuran = "";
				$id_finance_company = '';
			} else {
				$dp_stor = $isi->dp_stor;
				$tenor = $isi->tenor;
				if ($isi->id_finance_company != '' or $isi->id_finance_company != '- Choose -' or $isi->id_finance_company != ' - Choose - ') {
					$id_finance_company = $isi->id_finance_company;
				} else {
					$id_finance_company = '';
				}
				$angsuran = $isi->angsuran;
				$jenis_beli = 2;
			}

			$sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
				WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' ORDER BY id_surat_jalan DESC LIMIT 0,1");
			$tgl_sj = "";
			if ($sj->num_rows() > 0) {
				$tgl_sj = date("dmY", strtotime($sj->row()->tgl_surat));
			}

			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
				WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
			if ($tgl_sp->num_rows() > 0) {
				// $tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));
				$tgl_spes_md = $tgl_sp->row()->tgl_spes;
			}


			if ($tanggal_p == '30112019') {
				$t1 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($t1));
			}
			if ($tgl_sj == '30112019') {
				$t2 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
				$tgl_sj = date("dmY", strtotime($t2));
			}
			if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
				$t3 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
				$tgl_spes_md = date("dmY", strtotime($t3));
			}
			if ($tgl_md == '30112019') {
				$t4 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
				$tgl_md = date("dmY", strtotime($t4));
			}
			if ($tgl_terima == '30112019') {
				$t5 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
				$tgl_terima = date("dmY", strtotime($t5));
			}

			$content .= "E20;" . $isi->no_mesin . ";" . $isi->no_rangka . ";" . $tipe . ";" . $tanggal_p . ";" . $tgl_sj . ";" . $kode_dealer_md . ";" . $tgl_spes_md . ";" . $tgl_md . ";" . $tgl_create_ssu . ";" . $tgl_cetak_invoice . ";" . $tgl_cetak_invoice . ";" . $jenis_beli . ";" . $id_finance_company . ";" . $dp_stor . ";" . $tenor . ";" . $angsuran . ";" . $tgl_terima . ";I;" . $id_provinsi . ";" . $id_kabupaten . ";" . $id_kecamatan . ";" . $id_kelurahan . ";" . $id_flp . ";;";
			$content .= "\r\n";
			//echo "<br>";
			$no++;
		}
		echo 'Penjualan Individu : ' . $no . '</br>';
		$tot += $no;
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// $sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
		// 		INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
		// 		INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		// 		INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
		// 		INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
		// 		INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
		// 		WHERE tr_ssu.id_ssu = '$id_ssu'");
		$sql = $this->db->query("SELECT *,tr_sales_order_gc.tgl_create_ssu FROM tr_ssu_detail
			JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
			INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
			INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
			INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
			WHERE '$hari_ini' BETWEEN start_date AND end_date 
			AND tgl_cetak_invoice='$hari_ini'
			GROUP BY tr_ssu_detail.no_mesin");
		$no = 0;
		foreach ($sql->result() as $isi) {
			$scan = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin);
			if ($scan->num_rows() > 0) {
				$r = $scan->row();
				$tipe = $r->tipe;
				if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
				$tgl_penerimaan = $r->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));
			} else {
				$tipe = "";
				$tanggal_p = "";
			}
			$dealer = $this->db->query("SELECT *,tr_penerimaan_unit_dealer.tgl_penerimaan AS tgl_terima FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
				INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
				WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin' ORDER BY tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer_detail DESC LIMIT 0,1");
			if ($dealer->num_rows() > 0) {
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
				if ($kode_dealer_md == 'PSB') {
					$kode_dealer_md = '13384';
				}
				if ($d->pos == 'Ya') {
					$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $d->id_dealer_induk);
					$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
				}
				$tgl_terima = date("dmY", strtotime($d->tgl_terima));
			} else {
				$kode_dealer_md = "";
				$tgl_terima = "";
			}

			$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
				INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
				WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC LIMIT 0,1");
			if ($cek_md->num_rows() > 0) {
				$y = $cek_md->row();
				$tgl_md = date("dmY", strtotime($y->tgl_faktur));
			} else {
				$tgl_md = "";
			}

			if ($tgl_md == "") {
				$tgl_md = date("dmY", strtotime($scan->row()->tgl_faktur_invoice));
			}

			$waktu								= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id							= $this->session->userdata('id_user');

			$dat['generate_ssu']	= $login_id;
			$dat['generate_date']	= $waktu;
			$cek3 = $this->m_admin->update("tr_sales_order_gc", $dat, "id_sales_order_gc", $isi->id_sales_order_gc);

			$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));
			$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
			$id_kelurahan = $isi->id_kelurahan2;
			$prov = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
				INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
				WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
			if ($prov->num_rows() > 0) {
				$pro = $prov->row();
				$id_provinsi = $pro->id_provinsi;
				$id_kabupaten = $pro->id_kabupaten;
				$id_kecamatan = $pro->id_kecamatan;
				$id_kelurahan = $pro->id_kelurahan;
			} else {
				$id_provinsi = "";
				$id_kelurahan = "";
				$id_kecamatan = "";
				$id_provinsi = "";
			}

			$tr_prospek = $this->m_admin->getByID("tr_prospek_gc", "id_prospek_gc", $isi->id_prospek_gc);
			if ($tr_prospek->num_rows() > 0) {
				$r = $tr_prospek->row();
				$id_flp = $r->id_flp_md;
			} else {
				$id_flp = "";
			}

			if ($isi->jenis_beli == 'Cash') {
				$jenis_beli = 1;
				$dp_stor = "";
				$tenor = "";
				$angsuran = "";
				$id_finance_company = '';
			} else {
				$dp_stor = $isi->dp_stor;
				$tenor = $isi->tenor;
				if ($isi->id_finance_company != '' or $isi->id_finance_company != '- Choose -' or $isi->id_finance_company != ' - Choose - ') {
					$id_finance_company = $isi->id_finance_company;
				} else {
					$id_finance_company = '';
				}
				$angsuran = $isi->angsuran;
				$jenis_beli = 2;
			}

			$sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
				WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' ORDER BY tr_surat_jalan.id_surat_jalan DESC LIMIT 0,1");
			$tgl_sj = "";
			if ($sj->num_rows() > 0) {
				$tgl_sj = date("dmY", strtotime($sj->row()->tgl_surat));
			}

			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
				WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
			if ($tgl_sp->num_rows() > 0) {
				// $tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));
				$tgl_spes_md = $tgl_sp->row()->tgl_spes;
			}

			if ($tanggal_p == '30112019') {
				$t1 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($t1));
			}
			if ($tgl_sj == '30112019') {
				$t2 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
				$tgl_sj = date("dmY", strtotime($t2));
			}
			if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
				$t3 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
				$tgl_spes_md = date("dmY", strtotime($t3));
			}
			if ($tgl_md == '30112019') {
				$t4 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
				$tgl_md = date("dmY", strtotime($t4));
			}
			if ($tgl_terima == '30112019') {
				$t5 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
				$tgl_terima = date("dmY", strtotime($t5));
			}

			$content .= "E20;" . $isi->no_mesin . ";" . $isi->no_rangka . ";" . $tipe . ";" . $tanggal_p . ";" . $tgl_sj . ";" . $kode_dealer_md . ";" . $tgl_spes_md . ";" . $tgl_md . ";" . $tgl_create_ssu . ";" . $tgl_cetak_invoice . ";" . $tgl_cetak_invoice . ";" . $jenis_beli . ";" . $id_finance_company . ";" . $dp_stor . ";" . $tenor . ";" . $angsuran . ";" . $tgl_terima . ";G;" . $id_provinsi . ";" . $id_kabupaten . ";" . $id_kecamatan . ";" . $id_kelurahan . ";" . $id_flp . ";;";
			$content .= "\r\n";
			//echo "<br>";
			$no++;
		}
		echo 'Penjualan GC : ' . $no . '</br>';
		$tot += $no;
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// $content .= "???";
		// $content .= "\r\n";		
		// $tr_scan = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status BETWEEN 1 AND 3");
		// foreach ($tr_scan->result() as $isi) {	
		// 	$md = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'");	
		// 	if($md->num_rows() > 0){
		// 		$d = $md->row();
		// 		$tipe = $d->tipe;
		// 		if($tipe == 'PINJAMAN') $tipe = "NRFS";
		// 		$tgl_penerimaan = $d->tgl_penerimaan;
		// 		$tanggal_p = date("dmY", strtotime($tgl_penerimaan));		
		// 	}else{
		// 		$tipe = "";
		// 		$tgl_penerimaan = "";
		// 		$tanggal_p = "";
		// 	}
		// 	$tgl_spes_md = "";
		// 	$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
		// 		WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
		// 	if($tgl_sp->num_rows() > 0){
		// 		// $tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));
		// 		$tgl_spes_md = $tgl_sp->row()->tgl_spes;				
		// 	}

		// 	$content .= "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";;;;".$tgl_spes_md.";;;;;;;;;;;;;;;;;";
		// 	$content .= "\r\n";		
		// 	//echo "<br>";		
		// }

		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// $content .= "Stok Dealer RFS/NRFS";
		// $content .= "\r\n";		

		//Stok Dealer RFS/NRFS
		$tr_scan_old = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = 4");
		$tr_scan = $this->db->query("SELECT tr_scan_barcode.* FROM tr_penerimaan_unit_dealer 
			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
			WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0 AND tr_scan_barcode.status = 4 GROUP BY tr_scan_barcode.no_mesin");
		$no = 0;
		foreach ($tr_scan->result() as $isi) {
			$tgl_md = '';
			$dealer = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,ms_dealer.*,tr_scan_barcode.tipe FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
				INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
				WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin' ORDER BY tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer_detail DESC LIMIT 0,1");
			if ($dealer->num_rows() > 0) {
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
				if ($kode_dealer_md == 'PSB') {
					$kode_dealer_md = '13384';
				}
				if ($d->pos == 'Ya') {
					$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $d->id_dealer_induk);
					$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
				}
				$tgl_surat = $d->tgl_surat_jalan;
				$tgl_md_out = date("dmY", strtotime($tgl_surat));
				$tgl_pm = $d->tgl_penerimaan;
				$tgl_dealer = date("dmY", strtotime($tgl_pm));
			} else {
				$kode_dealer_md = "";
				$tanggal_p = "";
				$tgl_md_out = "";
				$tgl_dealer = "";
			}

			$tipe = $isi->tipe;
			if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
			$tgl_penerimaan = $isi->tgl_penerimaan;
			$tanggal_p = date("dmY", strtotime($tgl_penerimaan));

			$cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
				WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' ORDER BY tr_surat_jalan.id_surat_jalan DESC LIMIT 0,1");
			if ($cek_sj->num_rows() > 0) {
				$t = $cek_sj->row();
			} else {
			}
			$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
				INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
				WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC LIMIT 0,1");
			if ($cek_md->num_rows() > 0) {
				$y = $cek_md->row();
				$tgl_md = date("dmY", strtotime($y->tgl_faktur));
			} else {
				$tgl_md = "";
			}
			if ($tgl_md == "") {
				$tgl_md = date("dmY", strtotime($isi->tgl_faktur_invoice));
			}
			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
				WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
			if ($tgl_sp->num_rows() > 0) {
				// $tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));
				$tgl_spes_md = $tgl_sp->row()->tgl_spes;
			}



			if ($tanggal_p == '30112019') {
				$t1 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($t1));
			}
			if ($tgl_md_out == '30112019') {
				$t2 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
				$tgl_md_out = date("dmY", strtotime($t2));
			}
			if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
				$t3 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
				$tgl_spes_md = date("dmY", strtotime($t3));
			}
			if ($tgl_md == '30112019') {
				$t4 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
				$tgl_md = date("dmY", strtotime($t4));
			}
			if ($tgl_dealer == '30112019') {
				$t5 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
				$tgl_dealer = date("dmY", strtotime($t5));
			}

			$content .= "E20;" . $isi->no_mesin . ";" . $isi->no_rangka . ";" . $tipe . ";" . $tanggal_p . ";" . $tgl_md_out . ";" . $kode_dealer_md . ";" . $tgl_spes_md . ";" . $tgl_md . ";;;;;;;;;" . $tgl_dealer . ";;;;;;;;";
			$content .= "\r\n";
			$no++;

			$cek_akhir_bulan = $this->m_admin->akhirBulan($amb_tahun,$amb_bulan);
			if($tanggal == $cek_akhir_bulan){
				$datu['no_mesin'] = $isi->no_mesin;
				$datu['kode_dealer_md'] = $kode_dealer_md;
				$datu['tanggal'] = $tanggal;				
	 			$this->m_admin->insert("tr_stok_ssu_tmp",$datu);
			}
		}
		echo 'Stok Dealer RFS/NRFS :' . $no . '</br>';
		$tot += $no;

		//Stok Unfill Dealer
		$cek_unfill = $this->db->query("SELECT no_mesin,no_picking_list FROM tr_picking_list_view 
			INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
			WHERE tr_picking_list_view.no_mesin NOT IN 
			(SELECT no_mesin FROM tr_surat_jalan_detail 										
			WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
			AND tr_picking_list_view.retur = 0");

		// $content .= "Stok Unfill & Intransit Dealer";
		// $content .= "\r\n";	
		$no = 0;
		foreach ($cek_unfill->result() as $isi) {
			$row       = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row();
			$row2       = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail", "no_mesin", $isi->no_mesin)->row();
			$tanggal_p = date("dmY", strtotime($row->tgl_penerimaan));
			if (isset($row->status) and $row->status < 4) {
				$kode_dealer_md = $this->db->query("SELECT kode_dealer_md FROM tr_picking_list
					JOIN tr_do_po ON tr_do_po.no_do=tr_picking_list.`no_do`
					JOIN ms_dealer ON ms_dealer.id_dealer=tr_do_po.id_dealer
					WHERE no_picking_list='$isi->no_picking_list'")->row()->kode_dealer_md;

				$pos = $this->m_admin->getByID("ms_dealer", "kode_dealer_md", $kode_dealer_md);
				if ($pos->row()->pos == 'Ya') {
					$id_dealer_induk = ($pos->row()->id_dealer_induk != '') ? $pos->row()->id_dealer_induk : "";
					$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer_induk);
					$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
				}
				$content .= "E20;" . $row->no_mesin . ";" . $row->no_rangka . ";" . $row->tipe . ";" . $tanggal_p . ";;" . $kode_dealer_md . ";;;;;;;;;;;;;;;;;;;";
				$content .= "\r\n";
				$no++;

				$cek_akhir_bulan = $this->m_admin->akhirBulan($amb_tahun,$amb_bulan);
				if($tanggal == $cek_akhir_bulan){
					$datu['no_mesin'] = $row->no_mesin;
					$datu['kode_dealer_md'] = $kode_dealer_md;
					$datu['tanggal'] = $tanggal;				
		 			$this->m_admin->insert("tr_stok_ssu_tmp",$datu);
				}

			}
		}
		echo 'Stok Dealer Unfill :' . $no . '</br>';
		$tot += $no;

		//Stok Intransit Dealer
		$cek_in_old = $this->db->query("SELECT *  FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
			INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
			WHERE tr_surat_jalan_detail.ceklist='ya' AND tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL AND status='close')");		

		$cek_in = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       		      				
			INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
			WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL AND status = 'close')
			AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'");

		$no = 0;
		foreach ($cek_in->result() as $row) {
			$tgl_surat_jln = $row->tgl_surat;
			if ($row->status < 4) {
				$kode_dealer_md = $this->db->get_where('ms_dealer', ['id_dealer' => $row->id_dealer])->row()->kode_dealer_md;
				$pos = $this->m_admin->getByID("ms_dealer", "kode_dealer_md", $kode_dealer_md);
				if ($pos->row()->pos == 'Ya') {
					$id_dealer_induk = ($pos->row()->id_dealer_induk != '') ? $pos->row()->id_dealer_induk : "";
					$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer_induk);
					$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
				}

				$row       = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $row->no_mesin)->row();

				$tgl_unit_out = '';
				$cek_tgl_unit_out = $this->db->query('SELECT * FROM tr_penerimaan_unit_dealer_detail', array('no_mesin'=>$row->no_mesin));
				if ($cek_tgl_unit_out->num_rows() > 0) {
					$tgl_unit_out = date("dmY", strtotime($tgl_surat_jln));
				} else {
					$tgl_unit_out = date("dmY", strtotime($tgl_surat_jln));
				}

				$tanggal_p = date("dmY", strtotime($row->tgl_penerimaan));
				if (isset($row->status) and $row->status < 4) {
					$content .= "E20;" . $row->no_mesin . ";" . $row->no_rangka . ";" . $row->tipe . ";" . $tanggal_p . ";".$tgl_unit_out.";" . $kode_dealer_md . ";;;;;;;;;;;;;;;;;;;";
					$content .= "\r\n";
					$no++;

					$cek_akhir_bulan = $this->m_admin->akhirBulan($amb_tahun,$amb_bulan);
					if($tanggal == $cek_akhir_bulan){
						$datu['no_mesin'] = $row->no_mesin;
						$datu['kode_dealer_md'] = $kode_dealer_md;
						$datu['tanggal'] = $tanggal;				
			 			$this->m_admin->insert("tr_stok_ssu_tmp",$datu);
					}
				}
			}
		}
		echo 'Stok Dealer Intransit :' . $no . '</br>';
		$tot += $no;

		//Stok MD Ready

		// $tr_scan = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = 1 AND (tipe = 'RFS' OR tipe = 'NRFS')");

		// // $content .= "Stok MD Ready";
		// // $content .= "\r\n";		

		// foreach ($tr_scan->result() as $isi) {
		// 	$tgl_md='';			

		// 	$tipe = $isi->tipe;
		// 	if($tipe == 'PINJAMAN') $tipe = 'NRFS';		
		// 	$tgl_penerimaan = $isi->tgl_penerimaan;
		// 	$tanggal_p = date("dmY", strtotime($tgl_penerimaan));		



		// 	$content .= "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";;;;;;;;;;;;;;;;;;;;;";
		// 	$content .= "\r\n";					
		// }

		$tr_scan = $this->db->query("SELECT *,tr_scan_barcode.no_mesin AS nosin, tr_scan_barcode.status AS statuss FROM tr_scan_barcode 
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			WHERE tr_scan_barcode.status = 1");		
		$no = 0;
		foreach ($tr_scan->result() as $isi) {
			$md = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->nosin'");
			if ($md->num_rows() > 0) {
				$d = $md->row();
				$tipe = $d->tipe;
				if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
				$tgl_penerimaan = $d->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));
			} else {
				$tipe = "";
				$tgl_penerimaan = "";
				$tanggal_p = "";
			}
			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
				WHERE tr_shipping_list.no_mesin = '$isi->nosin'");
			if ($tgl_sp->num_rows() > 0) {
				// $tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));
				$tgl_spes_md = $tgl_sp->row()->tgl_spes;
			}

			if ($tanggal_p == '30112019') {
				$t1 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->nosin)->row()->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($t1));
			}
			if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
				$t3 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->nosin)->row()->tgl_distribusi_md;
				$tgl_spes_md = date("dmY", strtotime($t3));
			}

			$content .= "E20;" . $isi->nosin . ";" . $isi->no_rangka . ";" . $tipe . ";" . $tanggal_p . ";;;;" . $tgl_spes_md . ";;;;;;;;;;;;;;;;;";
			$content .= "\r\n";
			$no++;
			//echo "<br>";		
		}
		echo 'Stok MD RFS/NRFS :' . $no . '</br>';
		$tot += $no;
		echo 'Total :' . $tot;

		fwrite($file, $content);
		fclose($file);
	}
	public function ssu_detail()
	{			
		if(isset($_GET['stok_dealer'])){				
			$tr_scan = $this->db->query("SELECT tr_scan_barcode.* 
				FROM tr_penerimaan_unit_dealer 
				INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
				INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
				INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
				WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0 
				AND tr_scan_barcode.status = 4 GROUP BY tr_scan_barcode.no_mesin");
			$no = 0;
			foreach ($tr_scan->result() as $isi) {
				$tgl_md = '';
				$dealer = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,ms_dealer.*,tr_scan_barcode.tipe FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
					INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
					INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
					WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin' ORDER BY tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer_detail DESC LIMIT 0,1");
				if ($dealer->num_rows() > 0) {
					$d = $dealer->row();
					$kode_dealer_md = $d->kode_dealer_md;
					if ($kode_dealer_md == 'PSB') {
						$kode_dealer_md = '13384';
					}
					if ($d->pos == 'Ya') {
						$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $d->id_dealer_induk);
						$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
					}
					$tgl_surat = $d->tgl_surat_jalan;
					$tgl_md_out = date("dmY", strtotime($tgl_surat));
					$tgl_pm = $d->tgl_penerimaan;
					$tgl_dealer = date("dmY", strtotime($tgl_pm));
				} else {
					$kode_dealer_md = "";
					$tanggal_p = "";
					$tgl_md_out = "";
					$tgl_dealer = "";
				}

				$tipe = $isi->tipe;
				if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
				$tgl_penerimaan = $isi->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));

				$cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
					WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' ORDER BY tr_surat_jalan.id_surat_jalan DESC LIMIT 0,1");
				if ($cek_sj->num_rows() > 0) {
					$t = $cek_sj->row();
				} else {
				}
				$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
					INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
					WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC LIMIT 0,1");
				if ($cek_md->num_rows() > 0) {
					$y = $cek_md->row();
					$tgl_md = date("dmY", strtotime($y->tgl_faktur));
				} else {
					$tgl_md = "";
				}
				if ($tgl_md == "") {
					$tgl_md = date("dmY", strtotime($isi->tgl_faktur_invoice));
				}
				$tgl_spes_md = "";
				$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
					WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
				if ($tgl_sp->num_rows() > 0) {
					// $tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));
					$tgl_spes_md = $tgl_sp->row()->tgl_spes;
				}



				if ($tanggal_p == '30112019') {
					$t1 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan;
					$tanggal_p = date("dmY", strtotime($t1));
				}
				if ($tgl_md_out == '30112019') {
					$t2 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
					$tgl_md_out = date("dmY", strtotime($t2));
				}
				if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
					$t3 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_distribusi_md;
					$tgl_spes_md = date("dmY", strtotime($t3));
				}
				if ($tgl_md == '30112019') {
					$t4 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
					$tgl_md = date("dmY", strtotime($t4));
				}
				if ($tgl_dealer == '30112019') {
					$t5 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row()->tgl_penerimaan_dealer;
					$tgl_dealer = date("dmY", strtotime($t5));
				}				
				$no++;
				echo $no.";".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$kode_dealer_md."<br>";								
			}
			echo 'Stok Dealer RFS/NRFS :' . $no . '</br>';
			echo "<hr>";
		}

		//Stok Unfill Dealer
		if(isset($_GET['unfill_dealer'])){	

			$cek_unfill = $this->db->query("SELECT no_mesin,no_picking_list FROM tr_picking_list_view 
				INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
				WHERE tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_surat_jalan_detail WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
				AND tr_picking_list_view.retur = 0");			
			$no = 0;
			foreach ($cek_unfill->result() as $isi) {
				$row       = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row();
				$row2       = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail", "no_mesin", $isi->no_mesin)->row();
				$tanggal_p = date("dmY", strtotime($row->tgl_penerimaan));
				if (isset($row->status) and $row->status < 4) {
					$kode_dealer_md = $this->db->query("SELECT kode_dealer_md FROM tr_picking_list
						JOIN tr_do_po ON tr_do_po.no_do=tr_picking_list.`no_do`
						JOIN ms_dealer ON ms_dealer.id_dealer=tr_do_po.id_dealer
						WHERE no_picking_list='$isi->no_picking_list'")->row()->kode_dealer_md;

					$pos = $this->m_admin->getByID("ms_dealer", "kode_dealer_md", $kode_dealer_md);
					if ($pos->row()->pos == 'Ya') {
						$id_dealer_induk = ($pos->row()->id_dealer_induk != '') ? $pos->row()->id_dealer_induk : "";
						$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer_induk);
						$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
					}				
					$no++;
					echo $no.";".$row->no_mesin.";".$row->no_rangka.";".$row->tipe.";".$kode_dealer_md."<br>";								
				}
			}
			echo 'Stok Dealer Unfill :' . $no . '</br>';
		}

		//Stok Intransit Dealer
		if(isset($_GET['intransit_dealer'])){	
			$cek_in = $this->db->query("SELECT *  FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
				INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL AND status = 'close') 
				AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'");					

			$no = 0;
			foreach ($cek_in->result() as $row) {
				$tgl_surat_jln = $row->tgl_surat;
				if ($row->status < 4) {
					$kode_dealer_md = $this->db->get_where('ms_dealer', ['id_dealer' => $row->id_dealer])->row()->kode_dealer_md;
					$pos = $this->m_admin->getByID("ms_dealer", "kode_dealer_md", $kode_dealer_md);
					if ($pos->row()->pos == 'Ya') {
						$id_dealer_induk = ($pos->row()->id_dealer_induk != '') ? $pos->row()->id_dealer_induk : "";
						$cek_de = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer_induk);
						$kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "";
					}

					$row       = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $row->no_mesin)->row();

					$tgl_unit_out = '';
					$cek_tgl_unit_out = $this->db->get_where('tr_penerimaan_unit_dealer_detail', array('no_mesin'=>$row->no_mesin));
					if ($cek_tgl_unit_out->num_rows() > 0) {
						
					} else {
						$tgl_unit_out = date("dmY", strtotime($tgl_surat_jln));
					}

					$tanggal_p = date("dmY", strtotime($row->tgl_penerimaan));
					if (isset($row->status) and $row->status < 4) {
						$no++;
						echo $no.";".$row->no_mesin.";".$row->no_rangka.";".$row->tipe.";".$kode_dealer_md."<br>";								
					}
				}
			}
			echo 'Stok Dealer Intransit :' . $no . '</br>';
			echo "<hr>";
		}
		
		if(isset($_GET['stok_md'])){
			$tr_scan = $this->db->query("SELECT *,tr_scan_barcode.no_mesin AS nosin, tr_scan_barcode.status AS statuss FROM tr_scan_barcode 
				INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				WHERE tr_scan_barcode.status = 1");		
			$no = 0;			
			foreach ($tr_scan->result() as $isi) {
				$md = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->nosin'");
				if ($md->num_rows() > 0) {
					$d = $md->row();
					$tipe = $d->tipe;
					if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
					$tgl_penerimaan = $d->tgl_penerimaan;
					$tanggal_p = date("dmY", strtotime($tgl_penerimaan));
				} else {
					$tipe = "";
					$tgl_penerimaan = "";
					$tanggal_p = "";
				}
				$tgl_spes_md = "";
				$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
					WHERE tr_shipping_list.no_mesin = '$isi->nosin'");
				if ($tgl_sp->num_rows() > 0) {				
					$tgl_spes_md = $tgl_sp->row()->tgl_spes;
				}

				if ($tanggal_p == '30112019') {
					$t1 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->nosin)->row()->tgl_penerimaan;
					$tanggal_p = date("dmY", strtotime($t1));
				}
				if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
					$t3 = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->nosin)->row()->tgl_distribusi_md;
					$tgl_spes_md = date("dmY", strtotime($t3));
				}

				$no++;
				echo $no.";".$isi->nosin . ";" . $isi->no_rangka . ";" . $tipe."<br>";			
			}
			echo 'Stok MD RFS/NRFS :' . $no . '</br>';	
			echo "<hr>";
		}
	}
	public function cari_id_cdb()
	{
		$th 						= date("y");
		$bln 						= date("m");
		$pr_num 				= $this->db->query("SELECT * FROM tr_cdb_generate ORDER BY id_cdb_generate DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$id 	= substr($row->id_cdb_generate, 2, 5);
			$kode = $th . sprintf("%05d", $id + 1);
		} else {
			$kode = $th . "00001";
		}
		return $kode;
	}
	public function cari_id_ustk()
	{
		$th 						= date("y");
		$bln 						= date("m");
		$pr_num 				= $this->db->query("SELECT * FROM tr_ustk ORDER BY id_ustk DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$id 	= substr($row->id_ustk, 2, 5);
			$kode = $th . sprintf("%05d", $id + 1);
		} else {
			$kode = $th . "00001";
		}
		return $kode;
	}

	public function ubah_hari($date, $hari, $opr)
	{
		// Mengurang hari di php
		if (function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
		$date = date_create($date);
		date_add($date, date_interval_create_from_date_string("$opr$hari days"));
		return date_format($date, 'Y-m-d');
	}
	// public function tess()
	// {
	// 	$t = gmdate("Y-m-d", time()+60*60*7);
	// 	echo $this->ubah_hari($t,1,'-');			

	// }

	public function kk(){
		$tanggal         = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$tgl_1		 = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
		$tgl2            = gmdate("Ymd", time() + 60 * 60 * 7);
		$tgl3            = gmdate("YmdHi", time() + 60 * 60 * 7);
		$nama_file_kk    = 'AHM-E20-' . $tgl2 . '-' . $tgl2 . '0800.KK';

		$fileLocation_kk = getenv("DOCUMENT_ROOT") . "/downloads/kk/" . $nama_file_kk;
		$file_kk = fopen($fileLocation_kk, "w");
		$sql = $this->db->query("SELECT tr_spk.no_spk, tr_sales_order.no_mesin, tr_ssu.start_date, tr_ssu.end_date
				FROM tr_ssu_detail
				JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
				JOIN tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
				JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
				WHERE start_date = '$tgl_1'
				GROUP BY tr_ssu_detail.no_mesin");

		$no = 0;

		if($sql->num_rows() > 0){		
			foreach ($sql->result() as $isi) {

				$get_kk = $this->db->query("SELECT tr_cdb_kk.*,no_kk FROM tr_cdb_kk 
					JOIN tr_spk ON tr_cdb_kk.no_spk=tr_spk.no_spk
					WHERE tr_cdb_kk.no_spk='$isi->no_spk'
					ORDER BY tr_spk.no_spk ASC
				");

				if($get_kk->num_rows() > 0){	
					foreach ($get_kk->result() as $rs) {
						$tgl_lahir = date('dmY', strtotime($rs->tgl_lahir));
						$content_kk .= $rs->no_kk . ';' . $rs->nik . ';' . $rs->nama_lengkap . ';' . $rs->jk . ';' . $rs->tempat_lahir . ';' . $tgl_lahir . ';' . $rs->id_agama . ';' . $rs->id_pendidikan . ';' . $rs->id_pekerjaan . ';' . $rs->id_status_pernikahan . ';' . $rs->id_hub_keluarga . ';' . $rs->jenis_wn . ';';
						$content_kk .= "\r\n";

					}
				}
			}
		}

		//Buat teks file KK
		fwrite($file_kk, $content_kk);
		fclose($file_kk);
	}

	public function cdb()
	{
		$tgl             = gmdate("dmY", time() + 60 * 60 * 7);
		$tgl2            = gmdate("Ymd", time() + 60 * 60 * 7);
		$id_cdb_generate = $this->cari_id_cdb();
		$start_date      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$start_date      = $this->ubah_hari($start_date, 1, '-');
		$end_date        = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$end_date        = $this->ubah_hari($end_date, 1, '-');
		$m               = gmdate("hi", time() + 60 * 60 * 7);
		$nama_file_2     = "AHM-E20-" . $tgl . "-" . $tgl2 . $m;
		$tgl_hari_ini = $tanggal         = gmdate("Y-m-d", time() + 60 * 60 * 7);
		// $login_id     = $this->session->userdata('id_user');

		$tgl3            = gmdate("YmdHi", time() + 60 * 60 * 7);
		$nama_file_kk    = 'AHM-E20-' . $tgl2 . '-' . $tgl3 . '.KK';
		$waktu           = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);

		// $tanggal = $tgl_hari_ini =  '2021-01-01';
		$tgl_1			 = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));

		$dq = "SELECT * FROM tr_sales_order 
		INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
		WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date'
		AND (tr_sales_order.create_cdb_by IS NULL OR tr_sales_order.create_cdb_by = 0 OR tr_sales_order.create_cdb_by IS NULL)
		AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)
		";
		$sql = $this->db->query($dq);

		foreach ($sql->result() as $isi) {
			$da_cdb['no_mesin']        = $isi->no_mesin;
			$da_cdb['id_cdb_generate'] = $id_cdb_generate;
			$cek1                      = $this->m_admin->insert("tr_cdb_generate_detail", $da_cdb);
			$dat['create_cdb_by']      = $end_date;
			$dat['tgl_cetak_cdb']      = $waktu;

			$cek3 = $this->m_admin->update("tr_sales_order", $dat, "no_mesin", $isi->no_mesin);
		}
		$dw = "SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.no_mesin,tr_spk_gc.nama_npwp AS nama_konsumen,tr_scan_barcode.no_rangka,tr_spk_gc.no_ktp,tr_spk_gc.alamat FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
		INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
		WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order_gc.create_cdb_by IS NULL OR tr_sales_order_gc.create_cdb_by = 0 OR tr_sales_order_gc.create_cdb_by IS NULL)
		AND (tr_sales_order_gc.status_so = 'so_invoice' OR tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL OR tr_sales_order_gc.tgl_cetak_invoice2 IS NOT NULL)
		ORDER BY tr_spk_gc.no_spk_gc ASC
		";
		$sql_gc = $this->db->query($dw);
		foreach ($sql_gc->result() as $isi) {
			$da_cdb['no_mesin']	= $isi->no_mesin;
			$da_cdb['id_cdb_generate']		= $id_cdb_generate;
			$cek1 = $this->m_admin->insert("tr_cdb_generate_detail", $da_cdb);
			$dat['create_cdb_by']	= $end_date;
			$dat['tgl_cetak_cdb'] = $waktu;

			$cek3 = $this->m_admin->update("tr_sales_order_gc", $dat, "id_sales_order_gc", $isi->id_sales_order_gc);
		}

		$data_cdb['id_cdb_generate'] = $id_cdb_generate;
		$data_cdb['start_date']      = $start_date;
		$data_cdb['end_date']        = $end_date;
		$data_cdb['nama_file']       = $nama_file_2;
		$data_cdb['nama_file_kk']    = $nama_file_kk;
		$data_cdb['created_at']		 = $waktu;
		$cek2 = $this->m_admin->insert("tr_cdb_generate", $data_cdb);




		$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/cdb/" . $nama_file_2 . ".CDB";
		//$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/cdb/".$nama_file_2.".CDB";
		//$fileLocation = getenv("DOCUMENT_ROOT") . "/web_honda/downloads/cdb/".$nama_file_2.".CDB";
		$file = fopen($fileLocation, "w");
		$content = "";

		// $fileLocation_kk = getenv("DOCUMENT_ROOT") . "/downloads/kk/" . $nama_file_kk;
		// //$fileLocation_kk = getenv("DOCUMENT_ROOT") . "/downloads/cdb/".$nama_file_2.".CDB";
		// //$fileLocation_kk = getenv("DOCUMENT_ROOT") . "/web_honda/downloads/cdb/".$nama_file_2.".CDB";
		// $file_kk = fopen($fileLocation_kk, "w");
		// $content_kk = "";

		// $sql = $this->db->query("SELECT * FROM tr_cdb_generate INNER JOIN tr_cdb_generate_detail ON tr_cdb_generate.id_cdb_generate = tr_cdb_generate_detail.id_cdb_generate
		// 		INNER JOIN tr_sales_order ON tr_cdb_generate_detail.no_mesin = tr_sales_order.no_mesin
		// 		WHERE tr_cdb_generate.id_cdb_generate = '$id_cdb_generate'
		// 		ORDER BY tr_sales_order.no_spk ASC
		// 		");
		$sql = $this->db->query("SELECT * FROM tr_ssu_detail
			JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
			JOIN tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE '$tgl_1' BETWEEN start_date AND end_date
			GROUP BY tr_ssu_detail.no_mesin");
		$no = 0;
		foreach ($sql->result() as $isi) {

			// Data Teksfile .KK
			$get_kk = $this->db->query("SELECT tr_cdb_kk.*,no_kk FROM tr_cdb_kk 
				JOIN tr_spk ON tr_cdb_kk.no_spk=tr_spk.no_spk
				WHERE tr_cdb_kk.no_spk='$isi->no_spk'
				ORDER BY tr_spk.no_spk ASC
				");
			// $get_cdb = $this->db->query("SELECT * FROM tr_cdb WHERE no_spk='$isi->no_spk'")->row();
			// $spk_kons = $this->db->get_where('tr_spk',['no_spk'=>$isi->no_spk])->row();
			// $jk_kons = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer = '$isi->id_customer'");
			// if ($jk_kons->num_rows()>0) {
			// 	$jk_kons = $jk_kons->row();
			// 	$jk = $jk_kons->jenis_kelamin;
			// 	if($jk == 'Pria'){
			// 		$jk = "1";
			// 	}elseif($jk == 'Wanita'){
			// 		$jk = "2";
			// 	}
			// }
			// $content_kk .= $isi->no_kk.';'.$isi->no_ktp.';'.$isi->nama_konsumen.';'.$jk.';'.$isi->tempat_lahir.';'.$isi->tgl_lahir.';'.$jk_kons->agama.';'.$jk_kons->pendidikan.';'.$jk_kons->pekerjaan.';'.$isi->id_status_pernikahan.';'.$isi->id_status_pernikahan.';'.
			foreach ($get_kk->result() as $rs) {
				$tgl_lahir = date('dmY', strtotime($rs->tgl_lahir));
				$content_kk .= $rs->no_kk . ';' . $rs->nik . ';' . $rs->nama_lengkap . ';' . $rs->jk . ';' . $rs->tempat_lahir . ';' . $tgl_lahir . ';' . $rs->id_agama . ';' . $rs->id_pendidikan . ';' . $rs->id_pekerjaan . ';' . $rs->id_status_pernikahan . ';' . $rs->id_hub_keluarga . ';' . $rs->jenis_wn . ';';
				$content_kk .= "\r\n";
			}

			$nosin5 = substr($isi->no_mesin, 0, 5);
			$nosin7 = substr($isi->no_mesin, 5, 7);
			$tgl = $this->db->query("SELECT * FROM tr_permohonan_stnk WHERE no_mesin = '$isi->no_mesin'");
			if ($tgl->num_rows() > 0) {
				$r = $tgl->row();
				$tgl_mohon = $r->tgl_permohonan;
			} else {
				$tgl_mohon = "";
			}
			$asal = $this->db->query("SELECT * FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan = ms_kelurahan.id_kelurahan
				LEFT JOIN ms_kecamatan ON tr_spk.id_kecamatan = ms_kecamatan.id_kecamatan
				LEFT JOIN ms_kabupaten ON tr_spk.id_kabupaten = ms_kabupaten.id_kabupaten
				LEFT JOIN ms_provinsi ON tr_spk.id_provinsi = ms_provinsi.id_provinsi
				INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
				WHERE tr_sales_order.no_mesin = '$isi->no_mesin'");
			if ($asal->num_rows() > 0) {
				$a = $asal->row();
				$kelurahan = $a->id_kelurahan;
				$region = explode("-", $this->m_admin->getRegion($kelurahan));

				$kecamatan    = $region[1];
				$kabupaten    = $region[2];
				$provinsi     = $region[3];
				$kodepos      = $a->kodepos;
				$jenis_beli   = $a->jenis_beli;
				$no_ktp       = $a->no_ktp;
				$id_customer  = $a->id_customer;
				$alamat       = $a->alamat;
				$tgl_lahir    = $a->tgl_lahir;
				$tempat_lahir = $a->tempat_lahir;

				$bulan = substr($tgl_lahir, 5, 2);
				$tahun = substr($tgl_lahir, 0, 4);
				$tgl = substr($tgl_lahir, 8, 2);
				$tanggal = $tgl . $bulan . $tahun;
				$pk = $a->pekerjaan;
				$pengeluaran = $a->pengeluaran_bulan;
				$no_hp = $a->no_hp;
				$no_telp = ($a->no_telp != '') ? $a->no_telp : "0";
				$email = $a->email;
				$status_rumah = $a->status_rumah;
				if ($status_rumah == 'Rumah Sendiri') {
					$status_rumah = "1";
				} elseif ($status_rumah == 'Rumah Orang Tua') {
					$status_rumah = "2";
				} elseif ($status_rumah == 'Rumah Sewa') {
					$status_rumah = "3";
				}
				$penanggung = "N";
				$status_hp = $a->status_hp;
				$ket = $a->keterangan;
				$ref = $a->refferal_id;
				$robd = $a->robd_id;
				$jenis_wn = $a->jenis_wn;
				if ($jenis_wn == 'WNI') {
					$jenis_wn = "1";
				} else {
					$jenis_wn = "2";
				}
				$no_kk = $a->no_kk;
			} else {
				$kelurahan = "";
				$kecamatan = "";
				$kabupaten = "";
				$provinsi = "";
				$kodepos = "";
				$jenis_beli = "";
				$no_ktp = "";
				$id_customer = "";
				$tgl_lahir = "";
				$alamat = "";
				$pk = "";
				$pengeluaran = "";
				$no_hp = 0;
				$no_telp = 0;
				$status_hp = "";
				$status_rumah = "";
				$email = "";
				$ket = "N";
				$ref = "";
				$jenis_wn = "";
				$no_kk = "";
				$robd = "";
				$tempat_lahir = '';
			}


			$cdb = $this->db->query("SELECT * FROM tr_cdb WHERE no_spk = '$isi->no_spk'");
			if ($cdb->num_rows() > 0) {
				$am = $cdb->row();
				$ag = $am->agama;
				$pendidikan = $am->pendidikan;
				$sedia_hub 	= $am->sedia_hub;
				if ($sedia_hub == 'Ya') {
					$sedia_hub = "Y";
				} else {
					$sedia_hub = "N";
				}
				$merk_sebelumnya = $am->merk_sebelumnya;
				$jenis_sebelumnya = $am->jenis_sebelumnya;
				$digunakan = $am->digunakan;
				$pemakai_motor = $am->menggunakan;
				if ($pemakai_motor == 'Saya Sendiri') {
					$pemakai_motor = "1";
				} elseif ($pemakai_motor == 'Anak') {
					$pemakai_motor = "2";
				} elseif ($pemakai_motor == 'Pasangan Suami/Istri') {
					$pemakai_motor = "3";
				}
				$facebook  = ($am->facebook != '' && $am->facebook!='-' && $am->facebook!='--' && $am->facebook!='0') ? $am->facebook : "N";
				$twitter   = ($am->twitter != '' && $am->twitter !='-'  && $am->twitter !='--' && $am->twitter!='0') ? $am->twitter : "N";
				$instagram = ($am->instagram != '' && $am->instagram !='-'  && $am->instagram !='--' && $am->instagram!='0') ? $am->instagram : "N";
				$youtube   = ($am->youtube != '' && $am->youtube !='-'  && $am->youtube !='--' && $am->youtube!='0') ? $am->youtube : "N";
				$hobi      = $am->hobi;
				// $id_kecamatan_instansi      = $am->id_kecamatan_instansi;
				$id_kelurahan_instansi = ($isi->id_kelurahan_kantor != '') ? $isi->id_kelurahan_kantor : "";
				$kec_instansi  = '';							
				$kab_instansi = '';
				$prov_instansi ='';
				$nama_instansi ='';
				$alamat_instansi ='';
				
				$aktivitas_penjualan = ($am->aktivitas_penjualan != '') ? $am->aktivitas_penjualan : "";
			} else {
				$ag = "";
				$pendidikan = "";
				$sedia_hub = "";
				$merk_sebelumnya = "";
				$jenis_sebelumnya = "";
				$digunakan = "";
				$pemakai_motor = "";
				$facebook = "N";
				$twitter = "N";
				$youtube = "N";
				$instagram = "N";
				$hobi = "";
			}
			$jk = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer = '$id_customer'");
			$sub_pekerjaan='';
			if ($jk->num_rows() > 0) {
				$j = $jk->row();
				$jn = $j->jenis_kelamin;
				$id_karyawan_dealer = $j->id_karyawan_dealer;

				$is_required_instansi = '';	
				if($j->sub_pekerjaan == '101'){
					$pk = '11';
					$sub_pekerjaan = $j->pekerjaan_lain;
				}else{
					$get_sub_pekerjaan = $this->db->query("SELECT id_pekerjaan, sub_pekerjaan, required_instansi FROM ms_sub_pekerjaan WHERE id_sub_pekerjaan = '$j->sub_pekerjaan'");
					if($get_sub_pekerjaan->num_rows()>0){
						$pk= $get_sub_pekerjaan->row()->id_pekerjaan;
						$is_required_instansi = $get_sub_pekerjaan->row()->required_instansi;
					}
				}
			
				if($is_required_instansi == '1'){
					$nama_instansi       = ($j->nama_tempat_usaha != '') ? $j->nama_tempat_usaha : "";
					$alamat_instansi     = ($j->alamat_kantor != '') ? $j->alamat_kantor : "";
					$dmg_instansi = $this->db->query("SELECT ms_kelurahan.id_kecamatan, ms_kecamatan.id_kabupaten, ms_kabupaten.id_provinsi
						FROM  ms_kelurahan
						LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
						LEFT JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
						LEFT JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
						WHERE ms_kelurahan.id_kelurahan='$j->id_kelurahan_kantor'");
					if($dmg_instansi->num_rows() > 0){
						$kec_instansi  = $dmg_instansi->row()->id_kecamatan;
						$kab_instansi  = $dmg_instansi->row()->id_kabupaten;
						$prov_instansi = $dmg_instansi->row()->id_provinsi;
					}
				}		
			} else {
				$jn = "";
				$id_karyawan_dealer = "";
			}

			if ($jn == 'Pria') {
				$jn = "1";
			} elseif ($jn == 'Wanita') {
				$jn = "2";
			}
			$dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $isi->id_dealer);
			if ($dealer->num_rows() > 0) {
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
			} else {
				$kode_dealer_md = "";
			}

			$sales = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $id_karyawan_dealer);
			if ($sales->num_rows() > 0) {
				$s = $sales->row();
				$kode_sales = $s->id_flp_md;
			} else {
				$kode_sales = "";
			}

			$content .= $nosin5 . " ;" . $nosin7 . ";" . $no_ktp . ";I;" . $jn . ";" . $tanggal . ";" . $alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $ag . ";" . $pk . ";" . $pengeluaran . ";" . $pendidikan . ";" . $penanggung . ";" . $no_hp . ";" . $no_telp . ";" . $sedia_hub . ";" . $merk_sebelumnya . ";" . $jenis_sebelumnya . ";" . $digunakan . ";" . $pemakai_motor . ";" . $kode_sales . ";" . $email . ";" . $status_rumah . ";" . $status_hp . ";1;" . $facebook . ";" . $twitter . ";" . $instagram . ";" . $youtube . ";" . $hobi . ";" . $ket . ";" . $jenis_wn . ";" . $no_kk . ";" . $ref . ";" . $robd . ";" . $tempat_lahir . ";" . $nama_instansi . ";" . $alamat_instansi . ";" . $kec_instansi . ";" . $kab_instansi . ";" . $prov_instansi . ";" . $aktivitas_penjualan . ";" . $sub_pekerjaan;
			// $content .= $nosin5 . " ;" . $nosin7 . ";" . $no_ktp . ";I;" . $jn . ";" . $tanggal . ";" . $alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $ag . ";" . $pk . ";" . $pengeluaran . ";" . $pendidikan . ";" . $penanggung . ";" . $no_hp . ";" . $no_telp . ";" . $sedia_hub . ";" . $merk_sebelumnya . ";" . $jenis_sebelumnya . ";" . $digunakan . ";" . $pemakai_motor . ";" . $kode_sales . ";" . $email . ";" . $status_rumah . ";" . $status_hp . ";1;" . $facebook . ";" . $twitter . ";" . $instagram . ";" . $youtube . ";" . $hobi . ";" . $ket . ";" . $jenis_wn . ";" . $no_kk . ";" . $ref . ";" . $robd . ";";
			$content .= "\r\n";
			$no++;
		}
		echo 'Data Yang Diambil Per Tanggal :' . $tgl_1 . ', Tgl Pengambilan Data :' . $tgl_hari_ini;
		echo 'CDB Individu : ' . $no . ', ';

		$sql = $this->db->query("SELECT * FROM tr_ssu_detail
			JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
			INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
			INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
			INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
			WHERE '$tgl_1' BETWEEN start_date AND end_date
			GROUP BY tr_ssu_detail.no_mesin");
		$no = 0;
		foreach ($sql->result() as $isi) {
			$nosin5 = substr($isi->no_mesin, 0, 5);
			$nosin7 = substr($isi->no_mesin, 5, 7);
			$tgl = $this->db->query("SELECT * FROM tr_permohonan_stnk WHERE no_mesin = '$isi->no_mesin'");
			if ($tgl->num_rows() > 0) {
				$r = $tgl->row();
				$tgl_mohon = $r->tgl_permohonan;
			} else {
				$tgl_mohon = "";
			}

			$kelurahan = $isi->id_kelurahan;
			$region = explode("-", $this->m_admin->getRegion($kelurahan));

			$kecamatan = $region[1];
			$kabupaten = $region[2];
			$provinsi  = $region[3];
			$kodepos 	 = $isi->kodepos;
			$jenis_beli 	 = $isi->jenis_beli;
			$no_ktp 	 = $isi->no_npwp;
			$id_prospek_gc = $isi->id_prospek_gc;
			$alamat = $isi->alamat;
			$tgl_lahir = $isi->tgl_berdiri;

			$bulan   = substr($tgl_lahir, 5, 2);
			$tahun   = substr($tgl_lahir, 0, 4);
			$tgl     = substr($tgl_lahir, 8, 2);
			$tanggal = $tgl . $bulan . $tahun;
			$pk = "N";	
			$pengeluaran = "N";
			$no_hp = $isi->no_hp;
			$no_telp = ($isi->no_telp != '') ? $isi->no_telp : "0";
			$email = $isi->email;
			$status_rumah = "N";
			$penanggung = $isi->nama_penanggung_jawab;
			if (isset($isi->status_nohp)) {
				$status_hp = $isi->status_nohp;
			} else {
				$status_hp = "";
			}
			$ket = "N";
			if (isset($isi->refferal_id)) {
				$ref = $isi->refferal_id;
			} else {
				$ref = "";
			}
			if (isset($isi->robd_id)) {
				$robd = $isi->robd_id;
			} else {
				$robd = "";
			}
			// $jenis_wn = $isi->jenis_wn;
			// if($jenis_wn == 'WNI'){
			// 	$jenis_wn = "1";
			// }else{
			$jenis_wn = "1";
			//}
			$no_kk = "";

			$cdb = $this->db->query("SELECT * FROM tr_cdb_gc WHERE no_spk_gc = '$isi->no_spk_gc'");
			if ($cdb->num_rows() > 0) {
				$am = $cdb->row();
				$ag = "N";
				$pendidikan = "N";
				$sedia_hub 	= $am->sedia_hub;
				if ($sedia_hub == 'Ya') {
					$sedia_hub = "Y";
				} else {
					$sedia_hub = "N";
				}
				$merk_sebelumnya = "N";
				$jenis_sebelumnya = 'N';
				$digunakan = "N";
				$pemakai_motor = "N";
				$facebook = ($am->facebook != '') ? $am->facebook : "N";
				$twitter = ($am->twitter != '') ? $am->twitter : "N";
				$instagram = ($am->instagram != '') ? $am->instagram : "N";
				$youtube = ($am->youtube != '') ? $am->youtube : "N";
				$hobi = "N";
			} else {
				$ag = "N";
				$pendidikan = "N";
				$sedia_hub = "";
				$merk_sebelumnya = "N";
				$jenis_sebelumnya = "N";
				$digunakan = "N";
				$pemakai_motor = "N";
				$facebook = "N";
				$twitter = "N";
				$youtube = "N";
				$instagram = "N";
				$hobi = "N";
			}
			$akt ='N';
			$jk = $this->db->query("SELECT * FROM tr_prospek_gc WHERE id_prospek_gc = '$isi->id_prospek_gc'");
			if ($jk->num_rows() > 0) {
				$j = $jk->row();
				$jn = "";
				$id_karyawan_dealer = $j->id_karyawan_dealer;
			
				if($j->sumber_prospek !=''){
					$akt = $this->m_admin->getByID("ms_sumber_prospek", "id_dms", $j->sumber_prospek);
					if ($akt->num_rows() > 0) {
						$akt = $akt->row()->id_cdb;
					}
				}
			} else {
				$jn = "";
				$id_karyawan_dealer = "";
			}

			$jn = "";
			$dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $isi->id_dealer);
			if ($dealer->num_rows() > 0) {
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
			} else {
				$kode_dealer_md = "";
			}

			$sales = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $id_karyawan_dealer);
			if ($sales->num_rows() > 0) {
				$s = $sales->row();
				$kode_sales = $s->id_flp_md;
			} else {
				$kode_sales = "";
			}
			


			$content .= $nosin5 . " ;" . $nosin7 . ";" . $no_ktp . ";G;N;" . $tanggal . ";" . $alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $ag . ";" . $pk . ";" . $pengeluaran . ";" . $pendidikan . ";" . $penanggung . ";" . $no_hp . ";" . $no_telp . ";" . $sedia_hub . ";" . $merk_sebelumnya . ";" . $jenis_sebelumnya . ";" . $digunakan . ";" . $pemakai_motor . ";" . $kode_sales . ";" . $email . ";" . $status_rumah . ";" . $status_hp . ";1;" . $facebook . ";" . $twitter . ";" . $instagram . ";" . $youtube . ";" . $hobi . ";" . $ket . ";" . $jenis_wn . ";" . $no_kk . ";" . $ref . ";" . $robd . ";N;;;;;;".$akt.";";
			// $content .= $nosin5 . " ;" . $nosin7 . ";" . $no_ktp . ";G;N;" . $tanggal . ";" . $alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $ag . ";" . $pk . ";" . $pengeluaran . ";" . $pendidikan . ";" . $penanggung . ";" . $no_hp . ";" . $no_telp . ";" . $sedia_hub . ";" . $merk_sebelumnya . ";" . $jenis_sebelumnya . ";" . $digunakan . ";" . $pemakai_motor . ";" . $kode_sales . ";" . $email . ";" . $status_rumah . ";" . $status_hp . ";1;" . $facebook . ";" . $twitter . ";" . $instagram . ";" . $youtube . ";" . $hobi . ";" . $ket . ";" . $jenis_wn . ";" . $no_kk . ";" . $ref . ";" . $robd . ";";
			$content .= "\r\n";
			$no++;
		}
		echo 'CDB GC : ' . $no;
		// echo json_encode($content);
		fwrite($file, $content);
		fclose($file);

		//Buat teks file KK
		fwrite($file_kk, $content_kk);
		fclose($file_kk);
	}

	function get_data_generate($start_date, $end_date)
	{
		$so_in = $this->db->query("SELECT tr_sales_order.no_mesin,tr_sales_order.id_sales_order,tr_scan_barcode.no_rangka,tr_spk.nama_konsumen,tr_spk.no_ktp,tr_spk.alamat,tr_spk.no_spk,tr_sales_order.id_dealer,tgl_cetak_invoice,id_kelurahan2
			FROM tr_ssu_detail
			JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
			JOIN tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
			JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_ssu_detail.no_mesin
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE start_date BETWEEN '$start_date' AND '$end_date'
			AND tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date'
			GROUP BY tr_ssu_detail.no_mesin");
		$so_gc = $this->db->query("SELECT tr_sales_order_gc_nosin.id_sales_order_gc,tr_sales_order_gc_nosin.no_mesin,tr_scan_barcode.no_rangka,nama_npwp AS nama_konsumen,tr_spk_gc.no_ktp,tr_spk_gc.alamat,tr_spk_gc.no_spk_gc,tr_sales_order_gc.id_dealer,tgl_cetak_invoice,tr_spk_gc.id_kelurahan,tr_spk_gc.kodepos,jenis_beli,tr_spk_gc.no_npwp,tr_spk_gc.id_prospek_gc,tgl_berdiri,tr_spk_gc.no_hp,tr_spk_gc.no_telp,tr_spk_gc.email,tr_spk_gc.nama_penanggung_jawab,id_kelurahan2,nama_npwp FROM tr_ssu_detail
			JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
			INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
			INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
			INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
			WHERE start_date BETWEEN '$start_date' AND '$end_date'
			AND tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date'
			GROUP BY tr_ssu_detail.no_mesin");
		return ['so_in' => $so_in, 'so_gc' => $so_gc];
	}

	public function ustk()
	{
		$tgl         = gmdate("dmY", time() + 60 * 60 * 7);
		$tgl2        = gmdate("Ymd", time() + 60 * 60 * 7);
		$id_ustk     = $this->cari_id_ustk();
		$start_date  = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$start_date  = $this->ubah_hari($start_date, 1, '-'); //Kurang 1 Hari
		$end_date    = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$end_date    = $this->ubah_hari($end_date, 1, '-'); // Kurang 1 Hari
		$m           = gmdate("hi", time() + 60 * 60 * 7);
		$nama_file_3 = "AHM-E20-" . $tgl . "-" . $tgl2 . $m;
		$tanggal     = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id    = $this->session->userdata('id_user');
		// $sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
		// 		WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' 
		// 		AND (tr_sales_order.create_ustk_by IS NULL OR tr_sales_order.create_ustk_by = 0 OR tr_sales_order.create_ustk_by='')
		// 		AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)
		// 		ORDER BY tr_spk.no_spk ASC
		// 		");
		$dt_from_ssu = $this->get_data_generate($start_date, $end_date);
		foreach ($dt_from_ssu['so_in']->result() as $isi) {
			$cek_ustk = $this->db->query("SELECT count(no_mesin) AS count FROM tr_ustk_detail WHERE no_mesin='$isi->no_mesin'")->row();
			if ($cek_ustk->count == 0) {

				$da_ustk['no_mesin']		= $isi->no_mesin;
				$da_ustk['id_ustk']			= $id_ustk;
				$cek1 = $this->m_admin->insert("tr_ustk_detail", $da_ustk);
				$dat['create_ustk_by']	= $end_date;
				$dat['tgl_create_ustk'] = $login_id;
				$cek3 = $this->m_admin->update("tr_sales_order", $dat, "no_mesin", $isi->no_mesin);
			}
		}

		// $sql_gc = $this->db->query("SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.no_mesin,tr_spk_gc.nama_npwp AS nama_konsumen,tr_scan_barcode.no_rangka,tr_spk_gc.no_ktp,tr_spk_gc.alamat FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		//  	 	INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
		//  	 	INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
		// 		WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order_gc.create_cdb_by IS NULL OR tr_sales_order_gc.create_cdb_by = 0 OR tr_sales_order_gc.create_cdb_by IS NULL)
		// 		AND (tr_sales_order_gc.status_so = 'so_invoice' OR tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL OR tr_sales_order_gc.tgl_cetak_invoice2 IS NOT NULL)
		// 		ORDER BY tr_spk_gc.no_spk_gc ASC
		// 		");
		foreach ($dt_from_ssu['so_gc']->result() as $isi) {
			$cek_ustk = $this->db->query("SELECT count(no_mesin) AS count FROM tr_ustk_detail WHERE no_mesin='$isi->no_mesin'")->row();
			if ($cek_ustk->count == 0) {
				$da_ustk['no_mesin']		= $isi->no_mesin;
				$da_ustk['id_ustk']			= $id_ustk;
				$cek1 = $this->m_admin->insert("tr_ustk_detail", $da_ustk);

				$dat['create_ustk_by']	= $end_date;
				$dat['tgl_create_ustk'] = $login_id;
				$cek3 = $this->m_admin->update("tr_sales_order_gc", $dat, "id_sales_order_gc", $isi->id_sales_order_gc);
			}
		}
		$data_ustk['id_ustk']    = $id_ustk;
		$data_ustk['start_date'] = $start_date;
		$data_ustk['end_date']   = $end_date;
		$data_ustk['nama_file']  = $nama_file_3;
		$cek2 = $this->m_admin->insert("tr_ustk", $data_ustk);



		// $tgl_1			 = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
		$tgl_1			 = $tanggal;

		$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/ustk/" . $nama_file_3 . ".USTK";
		//$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/ustk/".$nama_file_3.".CDB";
		//$fileLocation = getenv("DOCUMENT_ROOT") . "/web_honda/downloads/ustk/".$nama_file_3.".CDB";
		$file = fopen($fileLocation, "w");
		$content = "";
		// $sql = $this->db->query("SELECT * FROM tr_ustk INNER JOIN tr_ustk_detail ON tr_ustk.id_ustk = tr_ustk_detail.id_ustk
		// 	INNER JOIN tr_sales_order ON tr_ustk_detail.no_mesin = tr_sales_order.no_mesin			
		// 	INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk		
		// 	WHERE tr_ustk.id_ustk = '$id_ustk'
		// 	ORDER BY tr_spk.no_spk ASC
		// 	");
		// $sql=$this->db->query("SELECT * FROM tr_ssu_detail
		// JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
		// JOIN tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
		// JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
		// JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
		// WHERE '$tgl_1' BETWEEN start_date AND end_date
		// GROUP BY tr_ssu_detail.no_mesin");
		$isi_data_fix_3 = "";
		$no = 0;
		foreach ($dt_from_ssu['so_in']->result() as $isi) {
			$nosin5 = substr($isi->no_mesin, 0, 5);
			$nosin7 = substr($isi->no_mesin, 5, 7);

			if ($isi->tgl_cetak_invoice != "" or $isi->tgl_cetak_invoice != NULL) {
				$tgl_ 			= $isi->tgl_cetak_invoice;
				$tgl_mohon 	= date("dmY", strtotime($tgl_));
				$tgl_up  		= date('dmY', strtotime('+7 days', strtotime($tgl_)));
			} else {
				$tgl_mohon = "";
				$tgl_up = "";
			}
			$asal = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
				WHERE tr_sales_order.no_mesin = '$isi->no_mesin'");
			if ($asal->num_rows() > 0) {
				$a = $asal->row();
				$kelurahan = $a->id_kelurahan2;
				$region = explode("-", $this->m_admin->getRegion($kelurahan));

				$kecamatan = $region[1];
				$kabupaten = $region[2];
				$provinsi  = $region[3];
				$kodepos 	 = $a->kodepos;
				$jenis_beli 	 = $a->jenis_beli;
				if ($jenis_beli == 'Cash') {
					$jenis_beli = 1;
				} else {
					$jenis_beli = 2;
				}
				$no_ktp 	 	= $a->no_ktp;
			} else {
				$kelurahan = "";
				$kecamatan = "";
				$kabupaten = "";
				$provinsi  = "";
				$kodepos 	 = "";
				$jenis_beli = "";
				$no_ktp = "";
			}
			$dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $isi->id_dealer);
			if ($dealer->num_rows() > 0) {
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_ahm;
				if ($d->id_dealer_induk != 0) {
					$kode_dealer_md = $d->kode_dealer_ahm;
				}
				if ($kode_dealer_md == 'PSB') {
					$kode_dealer_md = '13384';
				}
			} else {
				$kode_dealer_md = "";
			}

			$fm = $this->m_admin->getByID("tr_fkb", "no_mesin_spasi", $isi->no_mesin);
			if ($fm->num_rows() > 0) {
				$f = $fm->row();
				$no_faktur = $f->nomor_faktur;
				if (strlen($no_faktur) < 13) {
					$no_faktur_exp = explode(' ', $no_faktur);
					$tot       = strlen($no_faktur_exp[0]) + strlen($no_faktur_exp[1]);
					$kurang    = strlen($no_faktur) - $tot;
					$tbh_spasi = '';
					for ($i = 0; $i <= $kurang; $i++) {
						$tbh_spasi .= " ";
					}
					$no_faktur = $no_faktur_exp[0] . $tbh_spasi . $no_faktur_exp[1];
				}
			} else {
				$no_faktur = "";
			}

			$no_ktp = $isi->no_ktp;
			$content .= $no_faktur . ";" . $isi->no_rangka . ";" . $nosin5 . " ;" . $nosin7 . ";" . $tgl_up . ";" . $tgl_mohon . ";" . $isi->nama_konsumen . ";" . $isi->alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $jenis_beli . ";" . $kode_dealer_md . ";" . $no_ktp . ";";
			$content .= "\r\n";
			$no++;
			//echo "<br>";		
		}
		echo 'Data Yang Diambil Per Tanggal :' . $start_date . ', Tgl Pengambilan Data :' . $tanggal;
		echo 'USTK Individu : ' . $no . ', ';

		// $sql_gc = $this->db->query("SELECT * FROM tr_ustk INNER JOIN tr_ustk_detail ON tr_ustk.id_ustk = tr_ustk_detail.id_ustk
		// 		INNER JOIN tr_sales_order_gc_nosin ON tr_ustk_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin			
		// 		INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		// 		INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
		// 		INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc		
		// 		WHERE tr_ustk.id_ustk = '$id_ustk'
		// 		ORDER BY tr_spk_gc.no_spk_gc ASC
		// 		");
		// $sql_gc = $this->db->query("SELECT * FROM tr_ssu_detail
		// 		JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
		// 		INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
		// 		INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		// 		INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
		// 		INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
		// 		INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
		// 		WHERE '$tgl_1' BETWEEN start_date AND end_date
		// 		GROUP BY tr_ssu_detail.no_mesin");
		$isi_data_fix_3 = "";
		foreach ($dt_from_ssu['so_gc']->result() as $isi) {
			$nosin5 = substr($isi->no_mesin, 0, 5);
			$nosin7 = substr($isi->no_mesin, 5, 7);

			if ($isi->tgl_cetak_invoice != "" or $isi->tgl_cetak_invoice != NULL) {
				$tgl_ 			= $isi->tgl_cetak_invoice;
				$tgl_mohon 	= date("dmY", strtotime($tgl_));
				$tgl_up  		= date('dmY', strtotime('+7 days', strtotime($tgl_)));
			} else {
				$tgl_mohon = "";
				$tgl_up = "";
			}

			$kelurahan = $isi->id_kelurahan2;
			$region = explode("-", $this->m_admin->getRegion($kelurahan));

			$kecamatan = $region[1];
			$kabupaten = $region[2];
			$provinsi  = $region[3];
			$kodepos 	 = $isi->kodepos;
			$jenis_beli 	 = $isi->jenis_beli;
			if ($jenis_beli == 'Cash') {
				$jenis_beli = 1;
			} else {
				$jenis_beli = 2;
			}
			$no_ktp 	 	= "";

			$dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $isi->id_dealer);
			if ($dealer->num_rows() > 0) {
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
				if ($d->id_dealer_induk != 0) {
					$kode_dealer_md = $d->kode_dealer_ahm;
				}
				if ($kode_dealer_md == 'PSB') {
					$kode_dealer_md = '13384';
				}
			} else {
				$kode_dealer_md = "";
			}

			$fm = $this->m_admin->getByID("tr_fkb", "no_mesin_spasi", $isi->no_mesin);
			if ($fm->num_rows() > 0) {
				$f = $fm->row();
				$no_faktur = $f->nomor_faktur;
			} else {
				$no_faktur = "";
			}

			$no_ktp = "";
			$content .= $no_faktur . ";" . $isi->no_rangka . ";" . $nosin5 . " ;" . $nosin7 . ";" . $tgl_up . ";" . $tgl_mohon . ";" . $isi->nama_npwp . ";" . $isi->alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $jenis_beli . ";" . $kode_dealer_md . ";" . $no_ktp . ";";
			$content .= "\r\n";
			//echo "<br>";		
			$no++;
		}
		echo 'USTK GC : ' . $no . ', ';

		fwrite($file, $content);
		fclose($file);
		// $this->cdb();
	}

	public function cek_nsn()
	{
		$no_sj = $this->input->get('no_sj');
		$filter_sj = $no_sj == '' ? "" : "AND no_surat_jalan='$no_sj'";
		$get = $this->db->query("SELECT *,
			(SELECT COUNT(no_mesin) FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer) AS tot 
			FROM tr_penerimaan_unit_dealer 
			WHERE (SELECT COUNT(no_mesin) FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer)=0
			$filter_sj;
			");
		$tot = 0;
		foreach ($get->result() as $rs) {
			// $dtl = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan='$rs->no_surat_jalan'");
			$dtl = $this->db->query("SELECT *,(SELECT no_surat_jalan FROM tr_surat_jalan WHERE id_surat_jalan=id_sj) AS no_sj,
				(SELECT status FROM tr_scan_barcode WHERE no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin) AS stts
				FROM tr_penerimaan_unit_dealer_detail WHERE id_sj=(SELECT id_surat_jalan FROM tr_surat_jalan WHERE no_surat_jalan='$rs->no_surat_jalan')");
			$rs->detail = $dtl->result();
			$tot += $dtl->num_rows();
			$result[] = $rs;
			if ($no_sj != '') {
				foreach ($dtl->result() as $dtl_) {
					$upd_terima[] = ['no_mesin' => $dtl_->no_mesin, 'id_penerimaan_unit_dealer' => $rs->id_penerimaan_unit_dealer];
					$upd_scan[] = [
						'no_mesin' => $dtl_->no_mesin,
						'status' => 4
					];
					$upd_surat[] = [
						'no_mesin' => $dtl_->no_mesin,
						'terima' => 'ya'
					];
				}
			}
		}
		if ($no_sj != '') {
			$this->db->trans_begin();
			if (isset($upd_terima)) {
				$this->db->update_batch('tr_penerimaan_unit_dealer_detail', $upd_terima, 'no_mesin');
			}
			if (isset($upd_scan)) {
				$this->db->update_batch('tr_scan_barcode', $upd_scan, 'no_mesin');
			}
			if (isset($upd_surat)) {
				$this->db->update_batch('tr_surat_jalan_detail', $upd_surat, 'no_mesin');
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo count($upd_terima);
			} else {
				$this->db->trans_commit();
				echo "Sukses : " . count($upd_terima) . '-' . count($upd_surat) . '-' . count($upd_scan);
			}
		} else {
			$result['total'] = $tot;
			echo json_encode($result);
		}
	}
	public function cek_nosin()
	{
		$sj = $this->input->get('sj');
		$get = $this->db->query("SELECT *,(SELECT no_surat_jalan FROM tr_surat_jalan WHERE id_surat_jalan=id_sj) AS no_surat_jalan,
			(SELECT status FROM tr_scan_barcode WHERE no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin) AS stts_bc 
			FROM tr_penerimaan_unit_dealer_detail
			WHERE (SELECT no_surat_jalan FROM tr_surat_jalan WHERE id_surat_jalan=id_sj)='$sj'
			");
		$no = 1;
		foreach ($get->result() as $rs) {
			$get_terima = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan='$rs->no_surat_jalan' AND status='close'");
			if ($get_terima->num_rows() > 0) {
				$id_terima = $get_terima->row()->id_penerimaan_unit_dealer;
				$cek_sj_d = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_mesin='$rs->no_mesin' AND no_surat_jalan='$rs->no_surat_jalan'");
				if ($cek_sj_d->num_rows() > 0) {
					$cek = 'SAMA';
				} else {
					$cek = 'TIDAK';
				}
				if ($id_terima == null) {
					continue;
				}
				$data[] = [
					'no' => $no,
					'no_mesin' => $rs->no_mesin,
					'no_surat_jalan' => $rs->no_surat_jalan,
					'id_sj' => $rs->id_sj,
					'cek' => $cek,
					'id_terima' => $id_terima,
					'stts_bc' => $rs->stts_bc,
				];
				$no++;
				$upd_terima[] = [
					'no_mesin' => $rs->no_mesin,
					'id_penerimaan_unit_dealer' => $id_terima
				];
				$upd_scan[] = [
					'no_mesin' => $rs->no_mesin,
					'status' => 4
				];
				$upd_surat[] = [
					'no_mesin' => $rs->no_mesin,
					'terima' => 'ya'
				];
			}
		}
		echo json_encode($data);
		exit;
		// $this->db->trans_begin();
		// 	if (isset($upd_terima)) {
		// 		$this->db->update_batch('tr_penerimaan_unit_dealer_detail', $upd_terima,'no_mesin');
		// 	}
		// 	if (isset($upd_scan)) {
		// 		$this->db->update_batch('tr_scan_barcode', $upd_scan, 'no_mesin');
		// 	}
		// 	if (isset($upd_surat)) {
		// 		$this->db->update_batch('tr_surat_jalan_detail', $upd_surat, 'no_mesin');
		// 	}
		// if ($this->db->trans_status() === FALSE)
		//     	{
		// 	$this->db->trans_rollback();
		// 	echo count($upd_terima);
		//     	}
		//     	else
		//     	{
		//       	$this->db->trans_commit();
		//     	echo "Sukses : ".count($upd_terima);
		//     	}
	}
	function spk_expired()
	{
		//Individu
		$spk_exp = $this->db->query("SELECT no_spk FROM tr_spk AS spk WHERE NOT EXISTS (SELECT no_spk FROM tr_sales_order WHERE no_spk=spk.no_spk) and expired!=2")->result();
		$spk_gc_exp = $this->db->query("SELECT no_spk_gc FROM tr_spk_gc AS spk WHERE NOT EXISTS (SELECT no_spk_gc FROM tr_sales_order_gc WHERE no_spk_gc=spk.no_spk_gc) and expired!=2")->result();
		$tgl 		= gmdate("Y-m-d", time() + 60 * 60 * 7);
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		if ($tgl == $this->last_date()) {
			foreach ($spk_exp as $rs) {
				$upd_spk[] = [
					'expired' => 1,
					'expired_at' => $waktu,
					'no_spk' => $rs->no_spk
				];
			}
			foreach ($spk_gc_exp as $rs) {
				$upd_spk_gc[] = [
					'expired' => 1,
					'expired_at' => $waktu,
					'no_spk_gc' => $rs->no_spk_gc
				];
			}
			$this->db->update_batch('tr_spk', $upd_spk, 'no_spk');
			$this->db->update_batch('tr_spk_gc', $upd_spk_gc, 'no_spk_gc');
			echo 'Individu : ' . count($upd_spk) . ' GC : ' . count($upd_spk_gc);
		} else {
			echo 0;
		}
	}
	public function norm(){				
		$act = $this->input->get('act');		
		$no=1;
		$sql = $this->db->query("SELECT * FROM tr_invoice_dealer WHERE status_bayar <> 'lunas' AND no_faktur <> '-' ORDER BY no_faktur ASC");		
		foreach ($sql->result() as $isi) {			
			$nosin = $this->m_admin->get_detail_inv_dealer($isi->no_do);
			$tot = $nosin['total_bayar'];
			//if($tot <> $isi->total_bayar){
			$cek1 = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail INNER JOIN tr_penerimaan_bank 
				ON tr_penerimaan_bank.id_penerimaan_bank = tr_penerimaan_bank_detail.id_penerimaan_bank 
				WHERE tr_penerimaan_bank_detail.referensi = '$isi->no_faktur' AND tr_penerimaan_bank.status = 'approved'")->row();
			if(!is_null($cek1->jum) AND $cek1->jum == $tot){
				$tot_asli = $cek1->jum;
				if(isset($act)){
					$this->db->query("UPDATE tr_invoice_dealer SET total_bayar = '$tot_asli', status_bayar = 'lunas' WHERE no_do = '$isi->no_do'");
				}
				echo $no.";".$isi->no_faktur.";".$isi->no_do.";".$isi->total_bayar.";".$tot."<br>";
				$no++;

			}
			//}
		}

		$cek = $this->db->query("SELECT id_penerimaan_unit_dealer_detail, no_mesin,COUNT(no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail WHERE retur = 0 GROUP BY no_mesin HAVING jum > 1");
		foreach ($cek->result() as $key) {
			echo $key->no_mesin."(".$key->id_penerimaan_unit_dealer_detail.")|";
			$cek_lagi = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin = '$key->no_mesin'");
			if($cek_lagi->num_rows() > 0){
				$cek_lagi2 = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin = '$key->no_mesin' ORDER BY id_penerimaan_unit_dealer_detail DESC LIMIT 0,1");
				$isi = $cek_lagi2->row();			
				$unit = $this->input->get('unit');		
				if(isset($unit)){					
					$this->db->query("DELETE FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer_detail = '$isi->id_penerimaan_unit_dealer_detail'");
				}
			}
		}

		$cek2 = $this->db->query("SELECT no_picking_list_view, no_mesin, COUNT(no_mesin) AS jum FROM tr_picking_list_view WHERE retur = 0 GROUP BY no_mesin HAVING jum > 1");		
		foreach ($cek2->result() as $key) {
			echo $key->no_mesin."(".$key->no_picking_list_view.")|";
			$cek_lagi = $this->db->query("SELECT * FROM tr_picking_list_view WHERE no_mesin = '$key->no_mesin'");
			if($cek_lagi->num_rows() > 0){
				$cek_lagi2 = $this->db->query("SELECT * FROM tr_picking_list_view WHERE no_mesin = '$key->no_mesin' ORDER BY no_picking_list_view ASC LIMIT 0,1");
				$isi = $cek_lagi2->row();			
				$unit = $this->input->get('pl');		
				if(isset($unit)){					
					$this->db->query("UPDATE tr_picking_list_view SET retur = 1 WHERE no_picking_list_view = '$isi->no_picking_list_view'");
				}
			}
		}
    // kalau benar excelnya adalah lunas semua, maka ubah status bayar jadi lunas
	}
	public function perbandingan(){
		//dashboard
		$sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode 
			WHERE tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan AND status = '1' AND tipe='RFS') AS ready FROM ms_tipe_kendaraan");
		$gr=0;$jum_dealer=0;$int_dealer=0;$ready=0;$booking=0;$pinjaman=0;$nrfs=0;$jum_unfill=0;$jum_unfill2=0;
		foreach ($sql->result() as $row) {	
			$cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND status = '2'")->row();
			$cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'NRFS' AND status < 4")->row();
			$cek_booking2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'BOOKING' AND status = 1")->row();
			$cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'PINJAMAN' AND status < 4")->row();
			$total = $row->ready + $cek_nrfs->jum  + $cek_pinjaman->jum + $cek_booking2->jum;
			$gr += $total;
			$ready += $row->ready;
			$booking += $cek_booking2->jum;
			$nrfs += $cek_nrfs->jum;
			$pinjaman += $cek_pinjaman->jum;
			

			//stok dealer dashboard
			$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer 
				INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
				INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
				INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
				WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0
				AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = 4")->row();			

			$jum_dealer += $cek_qty->jum;

			$cek_in2 = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
				WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
				AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();
			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
				INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
				AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'
				AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();
			$int_dealer += $cek_in->jum;


			//cek unfill dashboard			

			$cek_unfill2 = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
				INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
				INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list AND tr_do_po_detail.id_item = tr_picking_list_view.id_item
				INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
				WHERE tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_surat_jalan_detail WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
				AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'
				AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'");
			$jum_unfill2 += $cek_unfill2->row()->jum;

		}
		echo "Stok MD Dashboard: $gr (R:$ready , P:$pinjaman , NRFS:$nrfs , B:$booking) <br>";


		//stok md report
		$sql2 = $this->db->query("SELECT *,tr_scan_barcode.no_mesin AS nosin, tr_scan_barcode.status AS statuss FROM tr_scan_barcode 
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan WHERE tr_scan_barcode.status = 1");
		$jum = $sql2->num_rows();
		echo "Stok MD Report: $jum";

		echo "<hr>";

		echo "Stok Ready Dealer Dashboard : $jum_dealer <br>";


		//stok dealer report
		$dt_pu = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.warna, tipe_motor,tipe_ahm,kode_dealer_md,nama_dealer,tr_penerimaan_unit_dealer.id_dealer,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe,tr_scan_barcode.status,tr_penerimaan_unit_dealer_detail.fifo AS fifo_terima_dealer 
			FROM tr_penerimaan_unit_dealer 
			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
			WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0 GROUP BY tr_scan_barcode.no_mesin
			ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC");           
		$ready=0;$intransit=0;
		foreach($dt_pu->result() as $row) {                       
			if ($row->status != 5) {
				if($row->status == 4){
					$status = "Ready";
					$ready++;
					if ($row->status_on_spk=='booking') {
						$status = "Soft Booking";
					}
					if ($row->status_on_spk=='hard_book') {
						$status = "Hard Booking";
					}
				}elseif($row->status == 5){
					$status = "Booking";
				}elseif($row->status == 6){
					$status = "Retur to Dealer";
				}elseif($row->status == 7){
					$status = "Retur to MD";
				}elseif($row->status == 3){
					$status = "Intransit";
					$intransit++;
				}else{
					$status = $row->status;
				}
			}
		}
		$cek_in = $this->db->query("SELECT *  FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
			INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
			WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL) 
			AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'");	  
		$intransit2=0;
		foreach($cek_in->result() as $row) {                       
			if($row->status < 4) {        
				$intransit2++;
			}
		}
		echo "Stok Ready Dealer Report: $ready";

		echo "<hr>";

		echo "Stok Intransit Dealer Dashboard: ".$int_dealer." <br>";
		echo "Stok Intransit Dealer Report: $intransit + $intransit2 = ".$intransit + $intransit2;
		echo "<hr>";

		
		//CEK UNFILL REPORT
		$cek_unfill = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
			INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
			INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list AND tr_do_po_detail.id_item = tr_picking_list_view.id_item
			WHERE tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_surat_jalan_detail WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
			AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'");
		$unfill=0;              
		foreach($cek_unfill->result() as $isi) {     
			$row = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin)->row();
			if(isset($row->status) AND $row->status != 4) {           
				$unfill++;
			}
		}
		echo "Stok Unfill Dealer Dashboard: $jum_unfill2 <br>";
		echo "Stok Unfill Dealer Report: $unfill ";
		echo "<hr>";







	}
	public function convert_ssu_stok_real(){
		$no=1;
		$sql = $this->db->query("SELECT ms_dealer.id_dealer,tr_scan_barcode.tipe_motor, count(tr_stok_ssu_tmp.no_mesin) AS jum FROM tr_stok_ssu_tmp 
				INNER JOIN tr_scan_barcode ON tr_stok_ssu_tmp.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN ms_dealer ON tr_stok_ssu_tmp.kode_dealer_md = ms_dealer.kode_dealer_md 
				WHERE tr_scan_barcode.tipe_motor = 'HV0' AND ms_dealer.id_dealer = 1");
		foreach ($sql->result() as $amb) {
			echo $no.";".$amb->id_dealer.";".$amb->tipe_motor.";".$amb->jum."<br>";			
			$no++;
		}
	}
	public function convert_ssu_stok(){
		$no=1;
		$sql = $this->m_admin->getAll("tr_stok_ssu_tmp");
		foreach ($sql->result() as $isi) {
			$no_mesin = $isi->no_mesin;
			$kode_dealer_md = $isi->kode_dealer_md;
			$id_dealer = $this->m_admin->getByID("ms_dealer","kode_dealer_md",$kode_dealer_md)->row()->id_dealer;
			$id_tipe_kendaraan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row()->tipe_motor;			
			echo $no.";".$id_dealer.";".$id_tipe_kendaraan."<br>";			
			$no++;
		}		
	}
}
