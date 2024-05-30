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
		// return false;
		
		$this->load->model('H1_model_nrfs','m_nrfs');
		
		$tgl1 = date('Y-m-d 01:01:00');
    	$tgl2 = date('Y-m-d H:i:s');
    	$data = $this->m_nrfs->generate_file($tgl1, $tgl2);

    	$content = "";
    	foreach ($data->result() as $rw) {
			$rw->no_rangka= 'MH1'.$rw->no_rangka;
			if($rw->qty_order > 0 && $rw->ket != 'REPAINTING'){
				$content .= "$rw->md_code;$rw->date_at;$rw->nama_pemeriksa;$rw->id_part;$rw->gejala;$rw->penyebab;$rw->no_mesin;$rw->no_rangka;$rw->tanggal_penerimaan;$rw->perbaikan_gudang;$rw->id_ekspedisi;$rw->no_polisi;$rw->nama_kapal;$rw->butuh_po;$rw->no_po_urgent;$rw->estimasi_tgl_selesai;$rw->actual_tgl_selesai; \r\n";
			}
		}
		$name_file = "AHM-E20-".date('ymd')."-".date('ymdhis').".NRFS";

		// jika mau disimpan

		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/downloads/nrfs/' . $name_file,"wb");
		fwrite($fp,$content);
		fclose($fp);

	}
	
	public function indent_off()
	{
		return false;
	}

	public function indent()
	{
		date_default_timezone_set('Asia/Jakarta');
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
				tpdi.status,
				ts.status_spk AS status_spk,
				ts.updated_at AS tgl_cancel_spk,
				ts.no_spk AS id_spk,
				tpdi.id_reasons AS id_reasons,
				tpdi.id_dealer,
				tpdi.date_prospek,
				tpdi.date_deal,
				tpdi.date_cancel,
				tpdi.date_sales

			FROM
				tr_po_dealer_indent tpdi
				INNER JOIN ms_dealer md ON tpdi.id_dealer = md.id_dealer
				INNER JOIN tr_spk ts ON tpdi.id_spk = ts.no_spk 
				AND tpdi.send_ahm = '1' 
				AND tpdi.status_kirim NOT IN ('3', '4')
			");
			
    	$content = "";
    	foreach ($data->result() as $rw) {

    		$flag_status = '1';
    		$jenis_pembayaran = '';
    		$tgl_paid = '';
    		$tgl_cancel = '';
    		$alasan_cancel_unpaid = '';
    		$alasan_cancel_paid = '';
    		$tgl_unpaid = '';
    		$kode_warna_final = '';
    		$eta_awal = '';
    		$eta_final = '';
    		$tgl_fulfillment = '';
    		$no_mesin = '';
    		$no_rangka = '';
			$tipe_motor_final='';

    		// jenis pembayaran
    		if ($rw->jenis_pembayaran == 'Cash') {
    			$jenis_pembayaran = '1';
    		} else {
    			$jenis_pembayaran = '2';
    		}

    		// tgl unpaid / prospek
    		if ($rw->tgl_unpaid != null) {
    			$tgl_unpaid = date('YmdHis',strtotime($rw->tgl_unpaid));

    			// cek tgl_unpaid jika di bawah tgl hari ini 
	    		if ($tgl_unpaid != '' and (strtotime($tgl_unpaid) < strtotime(date('YmdHis'))) ) {
					$tgl_unpaid = date('YmdHis');
				}
    		}

    		if ($rw->date_prospek != '') {
    			$tgl_unpaid = date('YmdHis', strtotime($rw->date_prospek));
    		}

    		

    		//tgl_paid
    		$this->db->where('no_spk', $rw->id_spk);
    		$this->db->limit(1);
    		$this->db->order_by('print_at', 'asc');
    		$cek_invoice = $this->db->get_where('tr_h1_dealer_invoice_receipt');
    		if ($cek_invoice->num_rows() > 0) {
    			$tgl_paid = date('YmdHis',strtotime($cek_invoice->row()->print_at));
    			//jika tgl paid lbih kecil dari tgl unpaid
    			if (strtotime($tgl_paid) < strtotime($tgl_unpaid)) {
    				$tgl_paid = date('YmdHis',strtotime($tgl_unpaid));
    			}
    		} else {
    			$tgl_paid = '';
    		}
    		if ($rw->date_deal != '') {
    			$tgl_paid = date('YmdHis', strtotime($rw->date_deal));
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

    		$this->db->select('id_warna, id_tipe_kendaraan, no_mesin');
    		$this->db->from('tr_spk ts');
    		$this->db->join('tr_sales_order tso', 'tso.no_spk = ts.no_spk', 'inner');
    		// $this->db->join('tr_fkb tsb', 'tsb.no_mesin_spasi = tso.no_mesin', 'inner');
    		$this->db->where('tso.no_spk', $rw->id_spk);
    		$cek_warna_so = $this->db->get();

    		if ($cek_warna_so->num_rows() > 0) {
    			$kode_warna_final = $cek_warna_so->row()->id_warna;
			$tipe_motor_final = $cek_warna_so->row()->id_tipe_kendaraan;
			// tidak perlu lakukan pengecekan kode warna dan tipe karena sudah ada di bawah dan kalo beda sudah langsung diset cancel indent dgn alasan ganti varian/tipe
    		}


    		//ambil tgl ETA awal
    		$tot_hari_eta_wal = $this->db->get_where('ms_master_lead_detail', array('id_tipe_kendaraan'=>$rw->kode_varian,'warna'=>$rw->kode_warna,'active'=>1));
    		if ($tot_hari_eta_wal->num_rows() > 0) {
    			$eta_awal = date('Ymd',strtotime('+'.$tot_hari_eta_wal->row()->total_lead_time.' days',strtotime($tgl_unpaid)));
    		}

    		// ambil ETA final
    		$cek_so = $this->db->get_where('tr_sales_order', array('no_spk'=>$rw->id_spk));
    		if ($cek_so->num_rows() > 0 && $rw->status != 'canceled') {
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
    		if ($tgl_unpaid != null) {
    			$flag_status = '1';
    		}
    		if ($tgl_paid != null) {
    			$flag_status = '2';
    		}
    		if ($rw->status_spk == 'canceled') {
    			$flag_status = '3';
    			$tgl_cancel = date('YmdHis',strtotime($rw->tgl_cancel_spk));
			$tgl_fulfillment = ''; $no_rangka =''; $no_mesin =''; $alasan_cancel_unpaid =''; $kode_warna_final=''; $eta_final ='';
    		} 


    		// sebelumnya
    		// if ($tgl_paid != null and $kode_warna_final != '' and $eta_final != '') {
    		if ( $kode_warna_final != '' and $eta_final != '') {
    			$flag_status = '4';
    		}


    		// set cancel manual
    		if ($rw->status == 'canceled') {
    			$flag_status = '3';
    			$tgl_fulfillment = '';
    			$tgl_cancel = date('YmdHis',strtotime(date('Y-m-d H:i:s')));
    			// alasan cancel ketika paid
	    		if ($tgl_paid != null) {
	    			$alasan_cancel_paid = $rw->id_reasons;
	    		}

	    		// alasan cancel ketika unpaid
	    		if ($tgl_paid == null) {
	    			$alasan_cancel_unpaid = $rw->id_reasons;
	    		}
    		} 

    		//kirim jika flag status di atas status kmren
    		if ($rw->status_kirim < $flag_status or $rw->status_kirim == '1') {
    			

    			// jika status kirim adalah deal (2), maka tgl boleh di isi hanya tgl paid, tgl fullfilment dan tgl eta final wajib dikosongkan
    			if ($flag_status == '2') {
    				$tgl_fulfillment = '';
    				$eta_final = '';
    			}

    			// cek tgl cancel jika di bawah tgl hari ini 
    			if ($flag_status == '3') {
    				if ($tgl_cancel != '' and (strtotime($tgl_cancel) < strtotime(date('YmdHis'))) ) {
						$tgl_cancel = date('YmdHis');
					}  
    			}

    			// cek tgl paid/deal jika di bawah tgl hari ini 
    			if ($flag_status == '2') {
    				if ($tgl_paid != '' and (strtotime($tgl_paid) < strtotime(date('YmdHis'))) ) {
						$tgl_paid = date('YmdHis');
					}  
    			}
	    		  		

				if ($flag_status == '4') {
				 	// cek tgl fulfil jika di bawah tgl hari ini 
		    		if ($tgl_fulfillment != '' and (strtotime($tgl_fulfillment) < strtotime(date('YmdHis'))) ) {
						$tgl_fulfillment = date('YmdHis');
					}  
					// cek tgl eta final jika di bawah tgl hari ini 
		    		if ($eta_final != '' and (strtotime($eta_final) < strtotime(date('Ymd'))) ) {
						$eta_final = date('Ymd');
					}
				 } 

				// cek tgl eta awal jika di bawah tgl hari ini 
	    		if ($eta_awal != '' and (strtotime($eta_awal) < strtotime(date('Ymd'))) ) {
					$eta_awal = date('Ymd');
				}

				// cek tgl eta final jika di bawah tgl hari ini 
	    		if ($eta_final != '' and (strtotime($eta_final) < strtotime(date('Ymd'))) ) {
					$eta_final = date('Ymd');
				}

				// jika status kirim sebelum nya masih 1 belum menjadi 2
    			// dan flag berstatus 3 (cancel) maka status flag ubah jadi 2
    			if ($rw->status_kirim == '1' and $flag_status == '3' and $tgl_paid != null) {
    				$flag_status = '2';
    				$tgl_cancel = '';
    				$tgl_fulfillment = '';
    				$no_rangka= '';
    				$no_mesin = '';
    				$alasan_cancel_unpaid = '';
    				$alasan_cancel_paid = '';
    				$kode_warna_final = '';
    				$eta_final = '';
    			}


				// jika status kirim sebelum nya masih 1 belum menjadi 2
    			// dan flag berstatus 4 (sales) maka status flag ubah jadi 2
    			if ($rw->status_kirim == '1' and $flag_status == '4') {
    				$flag_status = '2';
    				$tgl_cancel = '';
    				$tgl_fulfillment = '';
    				$no_rangka= '';
    				$no_mesin = '';
    				$alasan_cancel_unpaid = '';
    				$alasan_cancel_paid = '';
    				$kode_warna_final = '';
    				$eta_final = '';
    			}

    			if ($rw->status_kirim == '0' and $flag_status == '4') {
    				$flag_status = '1';
    				$tgl_cancel = '';
    				$tgl_fulfillment = '';
    				$no_rangka= '';
    				$no_mesin = '';
    				$alasan_cancel_unpaid = '';
    				$alasan_cancel_paid = '';
    				$kode_warna_final = '';
    				$eta_final = '';
    			}
		
    			// update status
			$update_po_ind = array(
	    			'date_prospek' => $tgl_unpaid != '' ? date('Y-m-d H:i:s', strtotime($tgl_unpaid)) : null,
	    			'date_deal'=>$tgl_paid != '' ? date('Y-m-d H:i:s', strtotime($tgl_paid)) : null,
	    			'date_cancel' => $tgl_cancel != '' ? date('Y-m-d H:i:s', strtotime($tgl_cancel)) : null,
	    			'date_sales' => $eta_final != '' ? date('Y-m-d H:i:s', strtotime($eta_final)) : null,
	    			'eta_awal' => $eta_awal != '' ? date('Y-m-d', strtotime($eta_awal)) : null,
	    			'eta_final' => $eta_final != '' ? date('Y-m-d', strtotime($eta_final)) : null,
	    			'status_kirim'=>$flag_status);

				// log_data($rw->id_indent);
				// log_data($update_po_ind);
			$this->db->where('id_indent', $rw->id_indent);
	    		$this->db->update('tr_po_dealer_indent', $update_po_ind);

			/* 13 Agustus 2021:
				1.	Ketika dilaporkan sebagai Prospect (Flag Status = 1), maka ETA Awal tidak mandatory diisi
				2.	Ketika dilaporkan sebagai Deal/Paid (Flag Status = 2), maka Tgl ETA Awal >= Tgl Deal
				3.	Ketika dilaporkan sebagai Cancel (Flag Status = 3)
   					a. Ketika cancel dimana Flag Status terakhir = Prospect, maka Tgl ETA Awal tidak divalidasi
   					b. Ketika cancel dimana Flag Status terakhir = Deal, maka Tgl ETA Awal mandatory terisi
				4.	Ketika dilaporkan sebagai Sales/Fulfillment (Flag Status = 4), maka Tgl ETA Awal >= Tgl Deal dan Tgl ETA Final >= Tgl Deal
			*/
			if($flag_status =='1'){
				$eta_awal = '';
			}	
			if($flag_status == '3' and $tgl_paid == null){
				$eta_awal = '';
			}

			// tambahkan validasi ketika unit indent beda dengan hasil ssu, jika beda dibuat cancel dan diberi reason cancel = 12
			if($tipe_motor_final !='' and $kode_warna_final !='' and ($rw->kode_varian != $tipe_motor_final or $rw->kode_warna != $kode_warna_final)){
				$flag_status = '3';
    				$tgl_cancel = date('YmdHis',strtotime(date('Y-m-d H:i:s')));
    				
	    			if ($tgl_paid != null) {
	    				$alasan_cancel_paid = 12;
	    			}
				$tgl_fulfillment = ''; $no_rangka =''; $no_mesin =''; $alasan_cancel_unpaid =''; $kode_warna_final=''; $eta_final ='';
			}

			$content .= "$rw->id_indent;$rw->kode_md;$rw->kode_dealer_ahm;$rw->no_ktp;$rw->kode_varian;$rw->kode_warna;$rw->kode_dummy_varian;$rw->kode_dummy_warna;$flag_status;$jenis_pembayaran;$tgl_unpaid;$tgl_paid;$tgl_cancel;$tgl_fulfillment;$no_rangka;$no_mesin;$alasan_cancel_unpaid;$alasan_cancel_paid;$kode_warna_final;$eta_awal;$eta_final;$rw->catatan; \r\n";
			
    		}
			
		}
		$name_file = "AHM-E20-".date('ymd')."-".date('ymdhis').".UIND";

		// echo $content;

		// jika mau disimpan

		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/downloads/uind/' . $name_file,"wb");
		fwrite($fp,$content);
		fclose($fp);

// 		$this->load->helper('download');
// 		// auto download
// 		force_download($name_file, $content);
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

	public function dummy_ssu(){
		$tgl        = gmdate("dmYHis", time() + 60 * 60 * 7);
		$hari_ini   = gmdate("Y-m-d", time() + 60 * 60 * 7);
		
		if (isset($_GET['tanggal'])) {
			$hari_ini = $_GET['tanggal'];
			$tgl = date('dmY', strtotime($hari_ini));
		}

		$nama_file  = "E20-" . $tgl;
		$start_date = $hari_ini;
		$end_date   = $hari_ini;			
		$tanggal    = gmdate("Y-m-d", time() + 60 * 60 * 7);
		if (isset($_GET['tanggal'])) {
			$tanggal = date('Y-m-d', strtotime($hari_ini));
		}
		$no 		= $nama_file;
		$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/dummy/" . $no . ".SSU";
		$file = fopen($fileLocation, "w");
		$content = "";
		for($num=0; $num <800; $num++){
			$content .="$num;";
			$content .= "\r\n";
		}

		// $content .= "E20;" . $isi->no_mesin . ";" . $isi->no_rangka . ";" . $tipe . ";" . $tanggal_p . ";" . $tgl_sj . ";" . $kode_dealer_md . ";" . $tgl_spes_md . ";" . $tgl_md . ";" . $tgl_create_ssu . ";" . $tgl_cetak_invoice . ";" . $tgl_cetak_invoice . ";" . $jenis_beli . ";" . $id_finance_company . ";" . $dp_stor . ";" . $tenor . ";" . $angsuran . ";" . $tgl_terima . ";I;" . $id_provinsi . ";" . $id_kabupaten . ";" . $id_kecamatan . ";" . $id_kelurahan . ";" . $id_flp . ";;";
		$content .= "E20;1;2;3;4;5;6;7;";	
		$content .= "\r\n";
		$no++;
		fwrite($file, $content);
		fclose($file);		
	}

	public function ssu_off(){
		// 2021-07-07 = functionn ssu() sementara dibackup / matikan dengan nama function ssu_off()
		// echo "2021-07-07 07:25 : dioffkan sementara<br>";
		echo "2023-10-26 08:25 : diaktifkan kembali<br>";
		return 0;
		die;
		
		/*
		$tgl = date('dmYHi', strtotime($hari_ini));
		$nama_file  = "E20-" . $tgl;
		$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/ssu/" . $nama_file . ".SSU";
		$file = fopen($fileLocation, "w");
		$content = 'test';
		fwrite($file, $content);
		fclose($file);
		*/

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
		
		/*
		$sql = $this->db->query("SELECT tr_sales_order.no_mesin FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
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
		
		$sql_gc = $this->db->query("SELECT tr_sales_order_gc_nosin.no_mesin
			FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
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
		*/

		$no 		= $nama_file;
		$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/" . $no . ".SSU";
		$file = fopen($fileLocation, "w");
		$content = "";

		/*
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
		*/

		$sql = $this->db->query("SELECT tr_sales_order.tgl_cetak_invoice, tr_sales_order.no_rangka, tr_sales_order.tgl_create_ssu , tr_sales_order.no_mesin , tr_sales_order.id_dealer, tr_spk.id_kelurahan2, tr_spk.id_customer, tr_spk.tenor, tr_spk.id_finance_company, tr_spk.angsuran, tr_spk.dp_stor, tr_spk.jenis_beli
			FROM tr_sales_order
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE tgl_cetak_invoice='$hari_ini'
			GROUP BY tr_sales_order.no_mesin");

		// $content .= "Penjualan";
		// $content .= "\r\n";		
		
		$tot = 0;
		$no = 0;
		foreach ($sql->result() as $isi) {
			/*
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek  = $this->m_admin->getByID("tr_ssu_detail", "no_mesin", $isi->no_mesin);
			if ($cek->num_rows() == 0) {
				$cek1 = $this->m_admin->insert("tr_ssu_detail", $da);
			}
			
			// $waktu								= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			// $login_id							= $this->session->userdata('id_user');

			$dat['generate_ssu']	= $login_id;
			$dat['generate_date']	= $waktu;
			
			$cek3 = $this->m_admin->update("tr_sales_order", $dat, "no_mesin", $isi->no_mesin);
			*/

			// tidak bisa diganti dengan query get data info dealer berdasarkan $isi->id_dealer karna ada tgl terima unit
			$dealer = $this->db->query("SELECT ms_dealer.kode_dealer_md, ms_dealer.pos, ms_dealer.id_dealer_induk, tr_penerimaan_unit_dealer.tgl_penerimaan AS tgl_terima 
				FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
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

			$cek_md = $this->db->query("SELECT tr_invoice_dealer.tgl_faktur FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
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

			$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));
			$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
			$id_kelurahan = $isi->id_kelurahan2;
			$prov = $this->db->query("SELECT ms_provinsi.id_provinsi, ms_kabupaten.id_kabupaten, ms_kecamatan.id_kecamatan, ms_kelurahan.id_kelurahan
				FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
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

			if ($isi->jenis_beli == 'Cash' || $isi->id_finance_company == 'FC000008') {
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

			$scan = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin);
			if ($scan->num_rows() > 0) {
				$r = $scan->row();
				$tipe = $r->tipe;
				if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
				$tgl_penerimaan = $r->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));
	
				$sj = $this->db->query("SELECT tr_surat_jalan_detail.no_surat_jalan, tr_surat_jalan_detail.no_mesin, tr_surat_jalan.tgl_surat FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
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
					$t1 = $r->tgl_penerimaan;
					$tanggal_p = date("dmY", strtotime($t1));
				}
				if ($tgl_sj == '30112019') {
					$t2 = $r->tgl_distribusi_md;
					$tgl_sj = date("dmY", strtotime($t2));
				}
				if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
					$t3 = $r->tgl_distribusi_md;
					$tgl_spes_md = date("dmY", strtotime($t3));
				}
				if ($tgl_md == '30112019') {
					$t4 = $r->tgl_penerimaan_dealer;
					$tgl_md = date("dmY", strtotime($t4));
				}
				if ($tgl_terima == '30112019') {
					$t5 = $r->tgl_penerimaan_dealer;
					$tgl_terima = date("dmY", strtotime($t5));
				}				
			} else {
				$tipe = "";
				$tanggal_p = "";
				$tgl_sj = "";
				$tgl_spes_md = "";
			}

			$content .= "E20;" . $isi->no_mesin . ";" . $isi->no_rangka . ";" . $tipe . ";" . $tanggal_p . ";" . $tgl_sj . ";" . $kode_dealer_md . ";" . $tgl_spes_md . ";" . $tgl_md . ";" . $tgl_create_ssu . ";" . $tgl_cetak_invoice . ";" . $tgl_cetak_invoice . ";" . $jenis_beli . ";" . $id_finance_company . ";" . $dp_stor . ";" . $tenor . ";" . $angsuran . ";" . $tgl_terima . ";I;" . $id_provinsi . ";" . $id_kabupaten . ";" . $id_kecamatan . ";" . $id_kelurahan . ";" . $id_flp . ";;";
			$content .= "\r\n";
			//echo "<br>";
			$no++;
		}
		echo 'Penjualan Individu : ' . $no . '</br>';
		$tot += $no;

		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$sql = $this->db->query("select tr_sales_order_gc_nosin.no_mesin, tr_scan_barcode.no_rangka, tr_sales_order_gc_nosin.id_sales_order_gc, tr_sales_order_gc.tgl_cetak_invoice, tr_sales_order_gc.tgl_create_ssu, tr_spk_gc.id_kelurahan2, tr_spk_gc.id_prospek_gc, tr_spk_gc.jenis_beli, tr_spk_gc_detail.dp_stor, tr_spk_gc_detail.tenor, tr_spk_gc.id_finance_company, tr_spk_gc_detail.angsuran 
		FROM tr_sales_order_gc_nosin 
		INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
		INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
		INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
		WHERE tgl_cetak_invoice='$hari_ini'
		GROUP BY tr_sales_order_gc_nosin.no_mesin");
		$no = 0;
		foreach ($sql->result() as $isi) {
			/*
			$waktu								= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id							= $this->session->userdata('id_user');

			$dat['generate_ssu']	= $login_id;
			$dat['generate_date']	= $waktu;
			$cek3 = $this->m_admin->update("tr_sales_order_gc", $dat, "id_sales_order_gc", $isi->id_sales_order_gc);
			*/

			$dealer = $this->db->query("SELECT ms_dealer.kode_dealer_md, ms_dealer.pos, ms_dealer.id_dealer_induk, tr_penerimaan_unit_dealer.tgl_penerimaan AS tgl_terima 
				FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
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

			$cek_md = $this->db->query("SELECT tr_invoice_dealer.tgl_faktur FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
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

			$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));
			$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
			$id_kelurahan = $isi->id_kelurahan2;
			$prov = $this->db->query("SELECT ms_provinsi.id_provinsi, ms_kabupaten.id_kabupaten, ms_kecamatan.id_kecamatan, ms_kelurahan.id_kelurahan
				FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
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

			if ($isi->jenis_beli == 'Cash' || $isi->id_finance_company == 'FC000008') {
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
			
			$scan = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin);
			if ($scan->num_rows() > 0) {
				$r = $scan->row();
				$tipe = $r->tipe;
				if ($tipe == 'PINJAMAN' or $tipe == 'BOOKING') $tipe = "NRFS";
				$tgl_penerimaan = $r->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));
	
				$sj = $this->db->query("SELECT tr_surat_jalan_detail.no_surat_jalan, tr_surat_jalan_detail.no_mesin, tr_surat_jalan.tgl_surat FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
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
					$t1 = $r->tgl_penerimaan;
					$tanggal_p = date("dmY", strtotime($t1));
				}
				if ($tgl_sj == '30112019') {
					$t2 = $r->tgl_distribusi_md;
					$tgl_sj = date("dmY", strtotime($t2));
				}
				if ($tgl_spes_md == '30112019' or $tgl_spes_md == "") {
					$t3 = $r->tgl_distribusi_md;
					$tgl_spes_md = date("dmY", strtotime($t3));
				}
				if ($tgl_md == '30112019') {
					$t4 = $r->tgl_penerimaan_dealer;
					$tgl_md = date("dmY", strtotime($t4));
				}
				if ($tgl_terima == '30112019') {
					$t5 = $r->tgl_penerimaan_dealer;
					$tgl_terima = date("dmY", strtotime($t5));
				}				
			} else {
				$tipe = "";
				$tanggal_p = "";
				$tgl_sj = "";
				$tgl_spes_md = "";
			}

			$content .= "E20;" . $isi->no_mesin . ";" . $isi->no_rangka . ";" . $tipe . ";" . $tanggal_p . ";" . $tgl_sj . ";" . $kode_dealer_md . ";" . $tgl_spes_md . ";" . $tgl_md . ";" . $tgl_create_ssu . ";" . $tgl_cetak_invoice . ";" . $tgl_cetak_invoice . ";" . $jenis_beli . ";" . $id_finance_company . ";" . $dp_stor . ";" . $tenor . ";" . $angsuran . ";" . $tgl_terima . ";G;" . $id_provinsi . ";" . $id_kabupaten . ";" . $id_kecamatan . ";" . $id_kelurahan . ";" . $id_flp . ";;";
			$content .= "\r\n";
			//echo "<br>";
			$no++;
		}
		echo 'Penjualan GC : ' . $no . '</br>';
		$tot += $no;


		echo 'Total :' . $tot;
		// fwrite($file, $content);
		// fclose($file);
	}
	
	public function ssu()
	{
		$tgl        = gmdate("dmY", time() + 60 * 60 * 7);
		//$tgl        = '29122019';//testing			
		
		$awal = date('Y-m-d H:i:s');
		echo $awal.'<br>';
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
		/*  off tgl 14 feb 2023
		$sql = $this->db->query("SELECT tr_sales_order.no_mesin FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
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
		$sql_gc = $this->db->query("SELECT tr_sales_order_gc_nosin.no_mesin
			FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
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
		*/
		$data['start_date'] = $start_date;
		$data['end_date']   = $end_date;
		$data['nama_file']  = $nama_file . ".SSU";

		/* off tgl 14 feb 2023
		$cek_dulu  = $this->m_admin->getByID("tr_ssu", "nama_file", $nama_file);
		if ($cek_dulu->num_rows() > 0) {
			$cek2 = $this->m_admin->update("tr_ssu", $data, "id_ssu", $cek_dulu->row()->id_ssu);
		} else {
			$data['id_ssu']				= $id_ssu;
			$cek2 = $this->m_admin->insert("tr_ssu", $data);
		}
		*/

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
		
		/*$sql = $this->db->query("SELECT *,tr_sales_order.tgl_create_ssu 
			FROM tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE tgl_cetak_invoice='$hari_ini'
			GROUP BY tr_ssu_detail.no_mesin");
		*/

		$sql = $this->db->query("SELECT tr_sales_order.tgl_cetak_invoice, tr_sales_order.no_rangka, tr_sales_order.tgl_create_ssu , tr_sales_order.no_mesin , tr_sales_order.id_dealer, tr_spk.id_kelurahan2, tr_spk.id_customer, tr_spk.tenor, tr_spk.id_finance_company, tr_spk.angsuran, tr_spk.dp_stor, tr_spk.jenis_beli
			FROM tr_sales_order
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE tgl_cetak_invoice='$hari_ini'
			GROUP BY tr_sales_order.no_mesin");


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

			// bisa diganti dengan query get data info dealer berdasarkan $isi->id_dealer
			$dealer = $this->db->query("SELECT ms_dealer.kode_dealer_md, ms_dealer.pos, ms_dealer.id_dealer_induk, tr_penerimaan_unit_dealer.tgl_penerimaan AS tgl_terima 
				FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
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

			$cek_md = $this->db->query("SELECT tr_invoice_dealer.tgl_faktur FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
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

			if ($isi->jenis_beli == 'Cash' || $isi->id_finance_company == 'FC000008') {
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

			$sj = $this->db->query("SELECT tr_surat_jalan_detail.no_surat_jalan, tr_surat_jalan_detail.no_mesin, tr_surat_jalan.tgl_surat FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
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

		$sql = $this->db->query("select tr_sales_order_gc_nosin.no_mesin, tr_scan_barcode.no_rangka, tr_sales_order_gc_nosin.id_sales_order_gc, tr_sales_order_gc.tgl_cetak_invoice, tr_sales_order_gc.tgl_create_ssu, tr_spk_gc.id_kelurahan2, tr_spk_gc.id_prospek_gc, tr_spk_gc.jenis_beli, tr_spk_gc_detail.dp_stor, tr_spk_gc_detail.tenor, tr_spk_gc.id_finance_company, tr_spk_gc_detail.angsuran 
			FROM tr_sales_order_gc_nosin
			INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
			INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
			WHERE tgl_cetak_invoice='$hari_ini'
			GROUP BY tr_sales_order_gc_nosin.no_mesin");
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
			$dealer = $this->db->query("SELECT ms_dealer.kode_dealer_md, ms_dealer.pos, ms_dealer.id_dealer_induk, tr_penerimaan_unit_dealer.tgl_penerimaan AS tgl_terima 
				FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
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

			$cek_md = $this->db->query("SELECT tr_invoice_dealer.tgl_faktur FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
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

			if ($isi->jenis_beli == 'Cash' || $isi->id_finance_company == 'FC000008') {
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

			$sj = $this->db->query("SELECT tr_surat_jalan_detail.no_surat_jalan, tr_surat_jalan_detail.no_mesin, tr_surat_jalan.tgl_surat FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
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

		// $tr_scan_old = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = 4");
	
		$tr_scan = $this->db->query("SELECT tr_scan_barcode.no_mesin, tr_scan_barcode.no_rangka, tr_scan_barcode.tipe, tr_scan_barcode.tgl_penerimaan, tr_scan_barcode.tgl_faktur_invoice 
			FROM tr_penerimaan_unit_dealer 
			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
			WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0 AND tr_scan_barcode.status = 4 GROUP BY tr_scan_barcode.no_mesin");
		$no = 0;
		foreach ($tr_scan->result() as $isi) {
			$tgl_md = '';
			$dealer = $this->db->query("SELECT ms_dealer.kode_dealer_md, ms_dealer.pos, ms_dealer.id_dealer_induk, tr_penerimaan_unit_dealer.tgl_penerimaan, tr_penerimaan_unit_dealer.tgl_surat_jalan 
				FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
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

			$cek_md = $this->db->query("SELECT tr_invoice_dealer.tgl_faktur FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
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

		$cek_in = $this->db->query("SELECT tr_surat_jalan_detail.no_mesin, tr_surat_jalan.tgl_surat, tr_surat_jalan.id_dealer, tr_scan_barcode.status 
			FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       		      				
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
				$cek_tgl_unit_out = $this->db->query('SELECT id_penerimaan_unit_dealer, no_mesin, retur FROM tr_penerimaan_unit_dealer_detail', array('no_mesin'=>$row->no_mesin));
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

		$tr_scan = $this->db->query("SELECT tr_scan_barcode.no_mesin AS nosin, tr_scan_barcode.no_rangka, tr_scan_barcode.status AS statuss FROM tr_scan_barcode 
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			WHERE tr_scan_barcode.status = 1");		
		$no = 0;
		foreach ($tr_scan->result() as $isi) {
			$md = $this->db->query("SELECT tipe, tgl_penerimaan FROM tr_scan_barcode WHERE no_mesin = '$isi->nosin'");
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
		$akhir = date('Y-m-d H:i:s');
		echo '<br>'.$akhir;
				
		$tgl1 = strtotime($awal); 
		$tgl2 = strtotime($akhir); 

		$jarak = round(($tgl2 - $tgl1)/60,2);
		echo '<br>Processing Time: '.$jarak.' menit';

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
		$pr_num 				= $this->db->query("SELECT id_cdb_generate FROM tr_cdb_generate ORDER BY id_cdb_generate DESC LIMIT 0,1");
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
		// $sql = $this->db->query("SELECT tr_spk.no_spk, tr_sales_order.no_mesin, tr_ssu.start_date, tr_ssu.end_date
		// 		FROM tr_ssu_detail
		// 		JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
		// 		JOIN tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
		// 		JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
		// 		WHERE start_date = '$tgl_1'
		// 		GROUP BY tr_ssu_detail.no_mesin");

		$sql = $this->db->query("SELECT tr_spk.no_spk, tr_sales_order.no_mesin, tr_sales_order.tgl_cetak_invoice
			From tr_sales_order
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE tgl_cetak_invoice = '$tgl_1'
			GROUP BY tr_sales_order.no_mesin");

		$no = 0;
		$content_kk ='';
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

		/*
		$dq = "SELECT tr_sales_order.no_mesin FROM tr_sales_order 
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
		*/

		//		SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.no_mesin,tr_spk_gc.nama_npwp AS nama_konsumen,tr_scan_barcode.no_rangka,tr_spk_gc.no_ktp,tr_spk_gc.alamat 
		
		/*
		$dw = "
		select tr_sales_order_gc_nosin.no_mesin
		FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
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
		*/

		$data_cdb['id_cdb_generate'] = $id_cdb_generate;
		$data_cdb['start_date']      = $start_date;
		$data_cdb['end_date']        = $end_date;
		$data_cdb['nama_file']       = $nama_file_2;
		$data_cdb['nama_file_kk']    = $nama_file_kk;
		$data_cdb['created_at']		 = $waktu;
		$cek2 = $this->m_admin->insert("tr_cdb_generate", $data_cdb);

		$fileLocation = getenv("DOCUMENT_ROOT") . "/downloads/cdb/" . $nama_file_2 . ".CDB";
		$file = fopen($fileLocation, "w");
		$content = "";
		$content_kk = "";

		$sql = $this->db->query("SELECT tr_sales_order.no_spk, tr_sales_order.no_mesin, tr_sales_order.id_dealer FROM tr_sales_order 
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			where tgl_cetak_invoice BETWEEN '$tgl_1' AND '$tgl_1' and tgl_cetak_invoice !='' and tgl_cetak_invoice is not null
			GROUP BY tr_sales_order.no_mesin");
		$no = 0;

		foreach ($sql->result() as $isi) {
			// script utk insert data di tbl cdb dan update sales order utk no generate cdb
			$da_cdb['no_mesin']        = $isi->no_mesin;
			$da_cdb['id_cdb_generate'] = $id_cdb_generate;
			$cek1                      = $this->m_admin->insert("tr_cdb_generate_detail", $da_cdb);
			$dat['create_cdb_by']      = $end_date;
			$dat['tgl_cetak_cdb']      = $waktu;
			$cek3 = $this->m_admin->update("tr_sales_order", $dat, "no_mesin", $isi->no_mesin);
			// end script

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
				if ($status_rumah == 'Rumah Sendiri' || $status_rumah == 'Rumah sendiri' || $status_rumah == 'Own House') {
					$status_rumah = "1";
				} elseif ($status_rumah == 'Rumah Orang Tua' || $status_rumah == 'Rumah orang tua') {
					$status_rumah = "2";
				} elseif ($status_rumah == 'Rumah Sewa' || $status_rumah == 'Sewa') {
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
				if ($pemakai_motor == 'Saya Sendiri' || $pemakai_motor == 'Saya sendiri' || $pemakai_motor == 'Sendiri' || $pemakai_motor == 'Myself' ) {
					$pemakai_motor = "1";
				} elseif ($pemakai_motor == 'Anak' || $pemakai_motor == 'Putra') {
					$pemakai_motor = "2";
				} elseif ($pemakai_motor == 'Pasangan Suami/Istri' || $pemakai_motor == 'Pasangan suami/istri' || $pemakai_motor == 'Suami istri' || $pemakai_motor == 'Suami/Istri') {
					$pemakai_motor = "3";
				}
				
				if($am->id_dealer !='4'){
					$facebook  = ($am->facebook != '' && $am->facebook!='-' && $am->facebook!='--' && $am->facebook!='0') ? $am->facebook : "N";
					$twitter   = ($am->twitter != '' && $am->twitter !='-'  && $am->twitter !='--' && $am->twitter!='0') ? $am->twitter : "N";
					$instagram = ($am->instagram != '' && $am->instagram !='-'  && $am->instagram !='--' && $am->instagram!='0') ? $am->instagram : "N";
					$youtube   = ($am->youtube != '' && $am->youtube !='-'  && $am->youtube !='--' && $am->youtube!='0') ? $am->youtube : "N";
				}else{
					$facebook  = "N";
					$twitter   = "N";
					$instagram = "N";
					$youtube   = "N";
				}

				$hobi      = $am->hobi;
				// $id_kecamatan_instansi      = $am->id_kecamatan_instansi;
				// $id_kelurahan_instansi = ($isi->id_kelurahan_kantor != '') ? $isi->id_kelurahan_kantor : "";
				$id_kelurahan_instansi = '';	
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
						
						if($j->sub_pekerjaan == '102'){
							$pk = '11';
							$sub_pekerjaan =  $get_sub_pekerjaan->row()->sub_pekerjaan;
						}
					}					
				}
			
				if($is_required_instansi == '1'){
					$temp_instansi = str_replace(" ","",$j->nama_tempat_usaha);
					$temp_alamat_instansi = str_replace(" ","",$j->alamat_kantor);

					$nama_instansi       = ($temp_instansi != '') ? $j->nama_tempat_usaha : "-";
					$alamat_instansi     = ($temp_alamat_instansi != '') ? $j->alamat_kantor : "-";
					
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

		$sql = $this->db->query("SELECT * FROM tr_sales_order_gc_nosin
			INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
			INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
			WHERE tgl_cetak_invoice BETWEEN '$tgl_1' AND '$tgl_1'
			GROUP BY tr_sales_order_gc_nosin.no_mesin");
		$no = 0;
		foreach ($sql->result() as $isi) {
			// script utk insert data cdb dan update sales order gc utk info no generate cdb
			$da_cdb['no_mesin']	= $isi->no_mesin;
			$da_cdb['id_cdb_generate']		= $id_cdb_generate;
			$cek1 = $this->m_admin->insert("tr_cdb_generate_detail", $da_cdb);
			$dat['create_cdb_by']	= $end_date;
			$dat['tgl_cetak_cdb'] = $waktu;
			$cek3 = $this->m_admin->update("tr_sales_order_gc", $dat, "id_sales_order_gc", $isi->id_sales_order_gc);
			// end script

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

				if ($youtube == '-' || $youtube == 0) $youtube = 'N';
				if ($twitter == '--' || $twitter == '-' || $twitter == 0) $twitter = 'N';
				if ($facebook == '--' || $facebook == '-' || $facebook == 0) $facebook = 'N';
				if ($instagram == '--' || $instagram == '-' || $instagram == 0) $instagram = 'N';
				
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
		// fwrite($file_kk, $content_kk);
		// fclose($file_kk);
	}

	function get_data_generate($start_date, $end_date)
	{
		$so_in = $this->db->query("SELECT tr_sales_order.no_mesin,tr_sales_order.id_sales_order,tr_scan_barcode.no_rangka,tr_spk.nama_konsumen,tr_spk.no_ktp,tr_spk.alamat,tr_spk.no_spk,tr_sales_order.id_dealer,tgl_cetak_invoice,id_kelurahan2
			FROM tr_sales_order
			JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date'
			GROUP BY tr_sales_order.no_mesin");
		$so_gc = $this->db->query("SELECT tr_sales_order_gc_nosin.id_sales_order_gc,tr_sales_order_gc_nosin.no_mesin,tr_scan_barcode.no_rangka,nama_npwp AS nama_konsumen,tr_spk_gc.no_ktp,tr_spk_gc.alamat,tr_spk_gc.no_spk_gc,tr_sales_order_gc.id_dealer,tgl_cetak_invoice,tr_spk_gc.id_kelurahan,tr_spk_gc.kodepos,jenis_beli,tr_spk_gc.no_npwp,tr_spk_gc.id_prospek_gc,tgl_berdiri,tr_spk_gc.no_hp,tr_spk_gc.no_telp,tr_spk_gc.email,tr_spk_gc.nama_penanggung_jawab,id_kelurahan2,nama_npwp 
			FROM tr_sales_order_gc_nosin
			INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
			INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
			WHERE tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date'
			GROUP BY tr_sales_order_gc_nosin.no_mesin");
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

	public function udh(){
		// if(date('Y') == '2021'){ 
		if( date('Y-m-d') < '2024-04-01' ){
			// tahun awal live file UDH
			$tgl_awal = '2023-01-01';
			$tgl_akhir = date('Y-m-01');	
			// jika mau diambil dari tahun 2021, maka udh akan membutuhkan penarikan data yang lama dan membebani server
		}else{
			// tambahkan logic utk awal / pergantian tahun
			// dispensi pergantian tahun utk pengiriman udh yaitu 2 bulan
			if(date('Y-m-d') == date('Y-01-01') || date('Y-m-d') == date('Y-02-01')){
				$thn = date('Y', strtotime('-2 month', strtotime(date('Y-m-d'))));
				$tgl_awal = $thn.'-01-01'; // 2022-01-01
				$tgl_akhir = date('Y-m-01'); // 2023-01-01 & 2023-02-01
			}else{
				$tgl_awal = date('Y-01-01'); // 2023-01-01
				$tgl_akhir = date('Y-m-01'); // 2023-03-01
			}
		}

		$content = '';
		$sql = $this->m_admin->generate_udh(false, $tgl_awal, $tgl_akhir); // perlu diimprove

		if($sql != false){
			foreach ($sql->result() as $rw) {
				$id_customer = preg_replace('/[-.]/', '', $rw->id_customer);
				if($rw->id_unit_delivery==''){
					$id_unit_delivery = 'DDoc/'.$rw->id_sales_order;
					$id_unit_delivery = 'N';
				}else{
					$id_unit_delivery = $rw->id_unit_delivery;
				}
				$id_unit_delivery = 'N';

				$log = $this->m_admin->log_udh($rw->no_mesin);
				
				if($log!=false){
					if($log->row()->stnk == 1 and $log->row()->bpkb == 0){
						if($rw->tgl_serah_terima_bpkb !=''){
							$content .= "$id_unit_delivery;$id_customer;$rw->id_sales_order;$rw->honda_id;$rw->id_tipe_kendaraan;$rw->id_warna;$rw->no_rangka;$rw->no_mesin;$rw->no_stnk;$rw->no_pol;$rw->tgl_serah_terima_stnk;$rw->nama_penerima_stnk;$rw->biro_jasa;$rw->no_bpkb;$rw->tgl_serah_terima_bpkb;$rw->nama_penerima_bpkb;$rw->biro_jasa; \r\n";
						
							// update bpkb = 1
							$data = array(
								'bpkb' => 1,
								'tgl_udh_bpkb' => date('Y-m-d')
							);

							$this->db->where('no_mesin', $rw->no_mesin);
							$this->db->update('log_udh', $data);
						}
					}
				}else{
					if($rw->tgl_serah_terima_bpkb ==''){
						$content .= "$id_unit_delivery;$id_customer;$rw->id_sales_order;$rw->honda_id;$rw->id_tipe_kendaraan;$rw->id_warna;$rw->no_rangka;$rw->no_mesin;$rw->no_stnk;$rw->no_pol;$rw->tgl_serah_terima_stnk;$rw->nama_penerima_stnk;$rw->biro_jasa;;;;; \r\n";
										
						// insert stnk =1 dan bpkb = 0
						$array = array(
							'no_mesin' => $rw->no_mesin,
							'stnk' => 1,
							'bpkb' => 0,
							'tgl_udh_stnk' => date('Y-m-d')
						);

						$this->db->set($array);
						$this->db->insert('log_udh');
					}else{
						$content .= "$id_unit_delivery;$id_customer;$rw->id_sales_order;$rw->honda_id;$rw->id_tipe_kendaraan;$rw->id_warna;$rw->no_rangka;$rw->no_mesin;$rw->no_stnk;$rw->no_pol;$rw->tgl_serah_terima_stnk;$rw->nama_penerima_stnk;$rw->biro_jasa;$rw->no_bpkb;$rw->tgl_serah_terima_bpkb;$rw->nama_penerima_bpkb;$rw->biro_jasa; \r\n";
						
						// insert stnk =1 dan bpkb = 1
						$array = array(
							'no_mesin' => $rw->no_mesin,
							'stnk' => 1,
							'bpkb' => 1,
							'tgl_udh_stnk' => date('Y-m-d'),
							'tgl_udh_bpkb' => date('Y-m-d')
						);

						$this->db->set($array);
						$this->db->insert('log_udh');
					}
				}
			}
		}

		$name_file = "AHM-E20-".date('ymdHis').".UDH";

		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/downloads/udh/' . $name_file,"wb");
		fwrite($fp,$content);
		fclose($fp);		

	}

	public function indent_gmanual()
	{
		date_default_timezone_set('Asia/Jakarta');
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
				tpdi.status,
				ts.status_spk AS status_spk,
				ts.updated_at AS tgl_cancel_spk,
				ts.no_spk AS id_spk,
				tpdi.id_reasons AS id_reasons,
				tpdi.id_dealer,
				tpdi.date_prospek,
				tpdi.date_deal,
				tpdi.date_cancel,
				tpdi.date_sales

			FROM
				tr_po_dealer_indent tpdi
				INNER JOIN ms_dealer md ON tpdi.id_dealer = md.id_dealer
				INNER JOIN tr_spk ts ON tpdi.id_spk = ts.no_spk 
				AND tpdi.send_ahm = '1' 
				AND tpdi.status_kirim  IN ( '3' )
				AND tpdi.date_cancel  = '2021-12-31 22:00:00' 
				Order by tpdi.status asc, tpdi.date_prospek asc			
		");

    	$content = "";
		
    	foreach ($data->result() as $rw) {
    		$flag_status = '1';
    		$jenis_pembayaran = '';
    		$tgl_paid = '';
    		$tgl_cancel = '';
    		$alasan_cancel_unpaid = '';
    		$alasan_cancel_paid = '';
    		$tgl_unpaid = '';
    		$kode_warna_final = '';
    		$eta_awal = '';
    		$eta_final = '';
    		$tgl_fulfillment = '';
    		$no_mesin = '';
    		$no_rangka = '';
		$tipe_motor_final='';

    		// jenis pembayaran
    		if ($rw->jenis_pembayaran == 'Cash') {
    			$jenis_pembayaran = '1';
    		} else {
    			$jenis_pembayaran = '2';
    		}

    		// tgl unpaid / prospek
    		if ($rw->tgl_unpaid != null) {
    			$tgl_unpaid = date('YmdHis',strtotime($rw->tgl_unpaid));

    			// cek tgl_unpaid jika di bawah tgl hari ini 
	    		if ($tgl_unpaid != '' and (strtotime($tgl_unpaid) < strtotime(date('YmdHis'))) ) {
				$tgl_unpaid = date('YmdHis');
			}
    		}

    		if ($rw->date_prospek != '') {
    			$tgl_unpaid = date('YmdHis', strtotime($rw->date_prospek));
    		}

    		//tgl_paid
    		$this->db->where('no_spk', $rw->id_spk);
    		$this->db->limit(1);
    		$this->db->order_by('print_at', 'asc');
    		$cek_invoice = $this->db->get_where('tr_h1_dealer_invoice_receipt');
    		if ($cek_invoice->num_rows() > 0) {
    			$tgl_paid = date('YmdHis',strtotime($cek_invoice->row()->print_at));
    			//jika tgl paid lbih kecil dari tgl unpaid
    			if (strtotime($tgl_paid) < strtotime($tgl_unpaid)) {
    				$tgl_paid = date('YmdHis',strtotime($tgl_unpaid));
    			}
    		} else {
    			$tgl_paid = '';
    		}
			
    		if ($rw->date_deal != '') {
    			$tgl_paid = date('YmdHis', strtotime($rw->date_deal));
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
    		$this->db->select('id_warna, id_tipe_kendaraan, no_mesin');
    		$this->db->from('tr_spk ts');
    		$this->db->join('tr_sales_order tso', 'tso.no_spk = ts.no_spk', 'inner');
    		// $this->db->join('tr_fkb tsb', 'tsb.no_mesin_spasi = tso.no_mesin', 'inner');
    		$this->db->where('tso.no_spk', $rw->id_spk);
    		$cek_warna_so = $this->db->get();

    		if ($cek_warna_so->num_rows() > 0) {
    			$kode_warna_final = $cek_warna_so->row()->id_warna;
				$tipe_motor_final = $cek_warna_so->row()->id_tipe_kendaraan;
				// tidak perlu lakukan pengecekan kode warna dan tipe karena sudah ada di bawah dan kalo beda sudah langsung diset cancel indent dgn alasan ganti varian/tipe
    		}

    		//ambil tgl ETA awal
    		$tot_hari_eta_wal = $this->db->get_where('ms_master_lead_detail', array('id_tipe_kendaraan'=>$rw->kode_varian,'warna'=>$rw->kode_warna,'active'=>1));
    		if ($tot_hari_eta_wal->num_rows() > 0) {
    			$eta_awal = date('Ymd',strtotime('+'.$tot_hari_eta_wal->row()->total_lead_time.' days',strtotime($tgl_unpaid)));
    		}

    		// ambil ETA final
    		$cek_so = $this->db->get_where('tr_sales_order', array('no_spk'=>$rw->id_spk));
    		if ($cek_so->num_rows() > 0 && $rw->status != 'canceled') {
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
    		if ($tgl_unpaid != null) {
    			$flag_status = '1';
    		}
    		if ($tgl_paid != null) {
    			$flag_status = '2';
    		}
    		if ($rw->status_spk == 'canceled') {
    			$flag_status = '3';
    			$tgl_cancel = date('YmdHis',strtotime($rw->tgl_cancel_spk));
				$tgl_fulfillment = ''; $no_rangka =''; $no_mesin =''; $alasan_cancel_unpaid =''; $kode_warna_final=''; $eta_final ='';
    		} 

    		// sebelumnya
    		// if ($tgl_paid != null and $kode_warna_final != '' and $eta_final != '') {
    		if ( $kode_warna_final != '' and $eta_final != '') {
    			$flag_status = '4';
    		}

    		// set cancel manual
    		if ($rw->status == 'canceled') {
    			$flag_status = '3';
    			$tgl_fulfillment = '';
    			$tgl_cancel = date('YmdHis',strtotime(date('Y-m-d H:i:s')));
    			// alasan cancel ketika paid
	    		if ($tgl_paid != null) {
	    			$alasan_cancel_paid = $rw->id_reasons;
	    		}

	    		// alasan cancel ketika unpaid
	    		if ($tgl_paid == null) {
	    			$alasan_cancel_unpaid = $rw->id_reasons;
	    		}
    		} 
    	
			// jika status kirim adalah deal (2), maka tgl boleh di isi hanya tgl paid, tgl fullfilment dan tgl eta final wajib dikosongkan
			if ($flag_status == '2') {
				$tgl_fulfillment = '';
				$eta_final = '';
			}

			// cek tgl cancel jika di bawah tgl hari ini 
			if ($flag_status == '3') {
				if ($tgl_cancel != '' and (strtotime($tgl_cancel) < strtotime(date('YmdHis'))) ) {
					$tgl_cancel = date('YmdHis');
				}  
			}

			// cek tgl paid/deal jika di bawah tgl hari ini 
			if ($flag_status == '2') {
				if ($tgl_paid != '' and (strtotime($tgl_paid) < strtotime(date('YmdHis'))) ) {
					$tgl_paid = date('YmdHis');
				}  
			}
					
			if ($flag_status == '4') {
				// cek tgl fulfil jika di bawah tgl hari ini 
				if ($tgl_fulfillment != '' and (strtotime($tgl_fulfillment) < strtotime(date('YmdHis'))) ) {
					$tgl_fulfillment = date('YmdHis');
				}  
				// cek tgl eta final jika di bawah tgl hari ini 
				if ($eta_final != '' and (strtotime($eta_final) < strtotime(date('Ymd'))) ) {
					$eta_final = date('Ymd');
				}
			 } 

			// cek tgl eta awal jika di bawah tgl hari ini 
			if ($eta_awal != '' and (strtotime($eta_awal) < strtotime(date('Ymd'))) ) {
				$eta_awal = date('Ymd');
			}

			// cek tgl eta final jika di bawah tgl hari ini 
			if ($eta_final != '' and (strtotime($eta_final) < strtotime(date('Ymd'))) ) {
				$eta_final = date('Ymd');
			}

			// jika status kirim sebelum nya masih 1 belum menjadi 2
			// dan flag berstatus 3 (cancel) maka status flag ubah jadi 2
			if ($rw->status_kirim == '1' and $flag_status == '3' and $tgl_paid != null) {
				$flag_status = '2';
				$tgl_cancel = '';
				$tgl_fulfillment = '';
				$no_rangka= '';
				$no_mesin = '';
				$alasan_cancel_unpaid = '';
				$alasan_cancel_paid = '';
				$kode_warna_final = '';
				$eta_final = '';
			}

			// jika status kirim sebelum nya masih 1 belum menjadi 2
			// dan flag berstatus 4 (sales) maka status flag ubah jadi 2
			if ($rw->status_kirim == '1' and $flag_status == '4') {
				$flag_status = '2';
				$tgl_cancel = '';
				$tgl_fulfillment = '';
				$no_rangka= '';
				$no_mesin = '';
				$alasan_cancel_unpaid = '';
				$alasan_cancel_paid = '';
				$kode_warna_final = '';
				$eta_final = '';
			}

			if ($rw->status_kirim == '0' and $flag_status == '4') {
				$flag_status = '1';
				$tgl_cancel = '';
				$tgl_fulfillment = '';
				$no_rangka= '';
				$no_mesin = '';
				$alasan_cancel_unpaid = '';
				$alasan_cancel_paid = '';
				$kode_warna_final = '';
				$eta_final = '';
			}
		
    			// update status
			$update_po_ind = array(
	    			'date_prospek' => $tgl_unpaid != '' ? date('Y-m-d H:i:s', strtotime($tgl_unpaid)) : null,
	    			'date_deal'=>$tgl_paid != '' ? date('Y-m-d H:i:s', strtotime($tgl_paid)) : null,
	    			'date_cancel' => $tgl_cancel != '' ? date('Y-m-d H:i:s', strtotime($tgl_cancel)) : null,
	    			'date_sales' => $eta_final != '' ? date('Y-m-d H:i:s', strtotime($eta_final)) : null,
	    			'eta_awal' => $eta_awal != '' ? date('Y-m-d', strtotime($eta_awal)) : null,
	    			'eta_final' => $eta_final != '' ? date('Y-m-d', strtotime($eta_final)) : null,
	    			'status_kirim'=>$flag_status);

			// $this->db->where('id_indent', $rw->id_indent);
	    		// $this->db->update('tr_po_dealer_indent', $update_po_ind);

			/* 13 Agustus 2021:
				1.	Ketika dilaporkan sebagai Prospect (Flag Status = 1), maka ETA Awal tidak mandatory diisi
				2.	Ketika dilaporkan sebagai Deal/Paid (Flag Status = 2), maka Tgl ETA Awal >= Tgl Deal
				3.	Ketika dilaporkan sebagai Cancel (Flag Status = 3)
   					a. Ketika cancel dimana Flag Status terakhir = Prospect, maka Tgl ETA Awal tidak divalidasi
   					b. Ketika cancel dimana Flag Status terakhir = Deal, maka Tgl ETA Awal mandatory terisi
				4.	Ketika dilaporkan sebagai Sales/Fulfillment (Flag Status = 4), maka Tgl ETA Awal >= Tgl Deal dan Tgl ETA Final >= Tgl Deal
			*/
			if($flag_status =='1'){
				$eta_awal = '';
			}	
			if($flag_status == '3' and $tgl_paid == null){
				$eta_awal = '';
			}

			// tambahkan validasi ketika unit indent beda dengan hasil ssu, jika beda dibuat cancel dan diberi reason cancel = 12
			if($tipe_motor_final !='' and $kode_warna_final !='' and ($rw->kode_varian != $tipe_motor_final or $rw->kode_warna != $kode_warna_final)){
				$flag_status = '3';
    				$tgl_cancel = date('YmdHis',strtotime(date('Y-m-d H:i:s')));
    				
	    			if ($tgl_paid != null) {
	    				$alasan_cancel_paid = 12;
	    			}
				$tgl_fulfillment = ''; $no_rangka =''; $no_mesin =''; $alasan_cancel_unpaid =''; $kode_warna_final=''; $eta_final ='';
			}

			$content .= "$rw->id_indent;$rw->kode_md;$rw->kode_dealer_ahm;$rw->no_ktp;$rw->kode_varian;$rw->kode_warna;$rw->kode_dummy_varian;$rw->kode_dummy_warna;$flag_status;$jenis_pembayaran;$tgl_unpaid;$tgl_paid;$tgl_cancel;$tgl_fulfillment;$no_rangka;$no_mesin;$alasan_cancel_unpaid;$alasan_cancel_paid;$kode_warna_final;$eta_awal;$eta_final;$rw->catatan; \r\n";

		}
		
		$name_file = "AHM-E20-".date('ymd')."-".date('ymdhis').".UIND";
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/downloads/' . $name_file,"wb");
		fwrite($fp,$content);
		fclose($fp);
	}

	public function download_udh(){
		$content = '';
		$tgl = date("Y-m-01");
		$log = $this->m_admin->log_udh(false, $tgl); 
		$name_file = "AHM-E20-".date('ymdHis').".UDH";
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/downloads/udh/' . $name_file,"wb");

		if($log != false){
			foreach ($log->result() as $data) {
				
				$rw = $this->m_admin->generate_udh($data->no_mesin,false,false)->row(); 
				$id_customer = preg_replace('/[-.]/', '', $rw->id_customer);
				if($rw->id_unit_delivery==''){
					$id_unit_delivery = 'DDoc/'.$rw->id_sales_order;
					$id_unit_delivery = 'N';
				}else{
					$id_unit_delivery = $rw->id_unit_delivery;
				}
				$id_unit_delivery = 'N';

				if($log!=false){
					if($data->stnk == 1 and $data->bpkb == 0){
						$content = "$id_unit_delivery;$id_customer;$rw->id_sales_order;$rw->honda_id;$rw->id_tipe_kendaraan;$rw->id_warna;$rw->no_rangka;$rw->no_mesin;$rw->no_stnk;$rw->no_pol;$rw->tgl_serah_terima_stnk;$rw->nama_penerima_stnk;$rw->biro_jasa;;;;; \r\n";
					}else{
						if($rw->tgl_serah_terima_bpkb !=''){
							$content = "$id_unit_delivery;$id_customer;$rw->id_sales_order;$rw->honda_id;$rw->id_tipe_kendaraan;$rw->id_warna;$rw->no_rangka;$rw->no_mesin;$rw->no_stnk;$rw->no_pol;$rw->tgl_serah_terima_stnk;$rw->nama_penerima_stnk;$rw->biro_jasa;$rw->no_bpkb;$rw->tgl_serah_terima_bpkb;$rw->nama_penerima_bpkb;$rw->biro_jasa; \r\n";
						}
					}
					
					fwrite($fp,$content);
				}
			}
		}
		fclose($fp);	
	}

	public function SDUE_xmlFile(){
		$tgl_awal = '2023-02-01';
		$tgl_akhir = '2023-02-06';

		// perlu diganti sesuai dengan tgl filter (awal dan akhir)
		if (isset($_GET['tanggal'])) {
			$tgl_awal = $_GET['tanggal'];
			$tgl_akhir = $_GET['tanggal'];
			$tgl = date('Ymd', strtotime($tgl_awal));
			$tgl1 = date('ymd', strtotime($tgl_awal)).date("Hi");
			// $tgl = date('dmY', strtotime($hari_ini));
		}else{
			$tgl = date('Ymd'); 	
			$tgl1 = date("ymdHi");
			// $tgl_1		 = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
			$tgl_awal = date('Y-m-d');
			$tgl_akhir = date('Y-m-d');
		}
		

		$jam = date('Hi');
		// $tgl1 = date("ymdHi");
		$trans = date('Ymd', strtotime($tgl_awal));
		// $trans = date('Ymd', $tgl_awal);
		$file_nama = 'AHM-E20-'.$tgl.'-'.$tgl1.'.SDUE';
		// $filePath = $file_nama;
		$filePath = getenv("DOCUMENT_ROOT") . "/downloads/sdue/" . $file_nama;

		$dom     = new DOMDocument('1.0', 'utf-8'); 
		$dom->preserveWhiteSpace = true; 
		$dom->formatOutput = true; 
		$root      = $dom->createElement('dataReq');
		$root->setAttribute('fileType', 'SDUE');
		
		// $data_part_oli = $this->db->query("select nama_dealer, kode_dealer_ahm2,SUM(spart_tanpa_wo) as spart_tanpa_wo2,sum(spart_wo) as spart_wo2 ,sum(oil_tanpa_wo) as oil_tanpa_wo2, sum(oil_wo) as oil_wo2
		//  			from (
		// 			select a.id_dealer, d.nama_dealer, case when d.kode_dealer_ahm = '07781' then '03573' else d.kode_dealer_ahm end kode_dealer_ahm2, 
		// 			SUM(CASE WHEN c.kelompok_part in('ACB','ACC','ACG','AH','AHM','BB','BBIST','BM1','BR','BRNG',
		// 			'BRNG2','BRNG3','BS','BT','CABLE','CB','CCKIT','CD','CDKGP','CDKIT','CH','CHAIN','COMP','COOL','CRKIT',
		// 			'DIHVL','DISK','ELECT','EP','EPHVL','EPMTI','ET','FKT','FLUID','GS','GSA','GSB','GST','HAH','HM','HNMTI',
		// 			'HPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC',
		// 			'OINS','OISTC','OKGD','OMTI','ORPL','OSEAL','OTHER','OTHR','PA','PAINT','PS','PSKIT','PSTN','PT','RBR','RIMWH',
		// 			'RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SA','SAOIL','SD','SDN','SDN2','SDT','SE','SHOCK','SP2','SPGUI',
		// 			'SPOKE','STR','TAS','TDI','TR','VALVE','VV') and a.referensi='sales' then a.tot_nsc ELSE 0 END) AS spart_tanpa_wo,
		// 			SUM(CASE WHEN c.kelompok_part in('ACB','ACC','ACG','AH','AHM','BB','BBIST','BM1','BR','BRNG',
		// 			'BRNG2','BRNG3','BS','BT','CABLE','CB','CCKIT','CD','CDKGP','CDKIT','CH','CHAIN','COMP','COOL','CRKIT',
		// 			'DIHVL','DISK','ELECT','EP','EPHVL','EPMTI','ET','FKT','FLUID','GS','GSA','GSB','GST','HAH','HM','HNMTI',
		// 			'HPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC',
		// 			'OINS','OISTC','OKGD','OMTI','ORPL','OSEAL','OTHER','OTHR','PA','PAINT','PS','PSKIT','PSTN','PT','RBR','RIMWH',
		// 			'RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SA','SAOIL','SD','SDN','SDN2','SDT','SE','SHOCK','SP2','SPGUI',
		// 			'SPOKE','STR','TAS','TDI','TR','VALVE','VV') and a.referensi='work_order' then a.tot_nsc ELSE 0 END) AS spart_wo,
		// 			SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='sales' then a.tot_nsc ELSE 0 END) AS oil_tanpa_wo,
		// 			SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='work_order' then a.tot_nsc ELSE 0 END) AS oil_wo
		// 			from tr_h23_nsc a
		// 			join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc 
		// 			join ms_part c on c.id_part_int=b.id_part_int 
		// 			join ms_dealer d on d.id_dealer = a.id_dealer 
		// 			where left(a.created_at ,10)>='$tgl_awal' and left(a.created_at,10)<='$tgl_akhir'
		// 			group by d.kode_dealer_ahm
		// 			)z
		// 			group by z.kode_dealer_ahm2")->result_array();

		$data_dealer = $this->db->query("SELECT id_dealer,kode_dealer_md ,kode_dealer_ahm from ms_dealer where h2=1 and active=1 and LENGTH (kode_dealer_ahm)=5 and kode_dealer_ahm != '07781' GROUP BY kode_dealer_ahm ")->result_array();
								

		for($i=0; $i<count($data_dealer); $i++){
			// $id_dealer= $data_part_oli[$i]['id_dealer'];
			$kode_dealer_ahm= $data_dealer[$i]['kode_dealer_ahm'];
			if($kode_dealer_ahm == '03573'){
				$data_part_oli = $this->db->query("SELECT REPLACE(FORMAT(SUM(CASE WHEN skp.produk='Parts' and a.referensi='sales' then (CASE WHEN b.tipe_diskon='Percentage' 
					then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS spart_tanpa_wo,
					REPLACE(FORMAT(SUM(CASE WHEN skp.produk='Parts' and a.referensi='work_order' then (CASE WHEN b.tipe_diskon='Percentage' 
					then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS spart_wo,
					REPLACE(FORMAT(SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='sales' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS oil_tanpa_wo,
					REPLACE(FORMAT(SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='work_order' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS oil_wo
					from tr_h23_nsc a
					join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc 
					join ms_part c on c.id_part_int=b.id_part_int 
					join ms_dealer d on d.id_dealer = a.id_dealer 
					join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part= c.kelompok_part
					-- where left(a.created_at ,10)='$tgl_awal'
					where a.created_at between '$tgl_awal' and '$tgl_awal 23:59:59' 
					and a.id_dealer IN ('104','728') ")->row_array();

				$mekanik = $this->db->query("SELECT count(case when b.id_karyawan_dealer !=null then 1 else 0 end) as mekanik
					from tr_h2_absen_mekanik a
					left join tr_h2_absen_mekanik_detail b on a.id_absen=b.id_absen 
					join ms_dealer d on d.id_dealer = a.id_dealer
					-- where left(a.created_at,10) ='$tgl_awal'
					where a.created_at between '$tgl_awal' and '$tgl_awal 23:59:59' and b.aktif='1' 
					and a.id_dealer IN ('104','728')
					-- and d.kode_dealer_ahm='$kode_dealer_ahm'
					-- group by d.kode_dealer_ahm
					")->row_array();

				$pit = $this->db->query("SELECT count(case when a.id_pit != null then 1 else 0 end) as pit
						from ms_h2_pit a
						join ms_dealer d on d.id_dealer = a.id_dealer
						where 
						a.id_dealer IN ('104','728')
						-- a.id_dealer='$id_dealer' 
						-- and 
						-- d.kode_dealer_ahm='$kode_dealer_ahm' 
						and a.active='1'
						-- group by d.kode_dealer_ahm
						")->row_array();

				$salesIn = $this->db->query("SELECT SUM(case when so.produk='Parts' then dso.total else 0 end) as part, SUM(case when so.produk='Oil' then dso.total else 0 end) as oli  
						FROM tr_h3_md_sales_order so
						JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
						JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
						JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
						JOIN ms_dealer md on md.id_dealer=so.id_dealer 
						-- WHERE left(ps.tgl_faktur,10) ='$tgl_awal' 
						where ps.tgl_faktur between '$tgl_awal' and '$tgl_awal 23:59:59' 
						and so.id_dealer IN ('104','728')
						-- GROUP BY so.id_dealer 
						")->row_array();

				$toj = $this->db->query("SELECT 
						SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
						SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
						SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
						SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
						SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
						SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
						SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
						SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
						SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
						SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
						SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
						SUM(CASE WHEN c.id_type='PUD' then 1 ELSE 0 end )as total_pud,
						SUM(CASE WHEN c.id_type='PL' then 1 ELSE 0 end )as total_pl,
						SUM(CASE WHEN c.id_type ='OTHER' then 1 ELSE 0 end )as total_other
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						WHERE b.status='closed' 
						-- and left(b.created_njb_at,10)='$tgl_awal' 
						and b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59' 
						-- and b.id_dealer='$id_dealer' 
						-- and a.kode_dealer_ahm ='$kode_dealer_ahm'
						and e.pekerjaan_batal=0
						and b.id_dealer IN ('104','728')
						and b.no_njb is not null 
						-- GROUP BY a.kode_dealer_ahm
						")->row_array();

				$toj11 = $this->db->query("SELECT 
						SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end)as total_ass1,
						SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end)as total_ass2,
						SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end)as total_ass3,
						SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end)as total_ass4,
						SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end)as total_cs,
						SUM(CASE WHEN c.id_type in ('LS','OTHER','PL') then 1 ELSE 0 end)as total_ls_other_pl,
						SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end)as total_or,
						SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end)as total_lr,
						SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end)as total_hr,
						SUM(CASE WHEN c.id_type in ('C2','PUD') then 1 ELSE 0 end)as total_claim_pud
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						-- LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						-- JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						WHERE b.status='closed' 
						-- and left(b.created_njb_at,10)='$tgl_awal' 
						and b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59' 
						-- and b.id_dealer='$id_dealer' 
						-- and a.kode_dealer_ahm ='$kode_dealer_ahm'
						and b.id_dealer IN ('104','728')
						and b.no_njb is not null 
						-- GROUP BY a.kode_dealer_ahm
						")->row_array();

				$ap = $this->db->query("SELECT  
						SUM(CASE WHEN a.kode ='PEOT' THEN 1 ELSE 0 END) AS pit_express_outdoor,
						SUM(CASE WHEN a.kode ='PEIN' THEN 1 ELSE 0 END) AS pit_express_indoor,
						SUM(CASE WHEN a.kode ='RM' THEN 1 ELSE 0 END) AS reminder,
						SUM(CASE WHEN a.kode in ('AE01','AE02','AE03') THEN 1 ELSE 0 END) AS ahass_event,
						SUM(CASE WHEN a.kode in ('SVAH','SVHC','SVJD','SVGC','SVPA','SVER') THEN 1 ELSE 0 END) AS service_visit,
						SUM(CASE WHEN a.kode ='SVPS' THEN 1 ELSE 0 END) AS pos_service
						from tr_h2_sa_form c 
						join tr_h2_wo_dealer b on c.id_sa_form=b.id_sa_form 
						join dms_ms_activity_promotion a on c.activity_promotion_id = a.id 
						join ms_dealer d on d.id_dealer = b.id_dealer 
						-- where left(b.created_njb_at,10)='$tgl_awal' 
						where b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59' and b.status='closed' 
						-- and b.id_dealer='$id_dealer' 
						-- and d.kode_dealer_ahm ='$kode_dealer_ahm'
						and b.id_dealer IN ('104','728')
						and b.no_njb is not null 
						-- group by d.kode_dealer_ahm
						")->row_array();

				$ac = $this->db->query("SELECT SUM(CASE WHEN a.kode='BS' THEN 1 ELSE 0 END) AS booking_service
						from tr_h2_sa_form c
						join tr_h2_wo_dealer b on c.id_sa_form=b.id_sa_form 
						join dms_ms_activity_capacity a on c.activity_capacity_id = a.id 
						join ms_dealer d on d.id_dealer = b.id_dealer 
						-- where left(b.created_njb_at,10)='$tgl_awal' 
						where b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59' and b.status='closed' 
						-- and b.id_dealer='$id_dealer' 
						-- and d.kode_dealer_ahm ='$kode_dealer_ahm'
						and b.id_dealer IN ('104','728')
						and b.no_njb is not null 
						-- group by d.kode_dealer_ahm
						")->row_array();

				$jasaRev = $this->db->query("SELECT 
						SUM(CASE WHEN A.id_type in ('ASS1','ASS2','ASS3','ASS4') THEN B.harga ELSE 0 END) AS ass,
						SUM(CASE WHEN A.id_type ='ASS1' THEN B.harga ELSE 0 END) AS ass1,
						SUM(CASE WHEN A.id_type ='ASS2' THEN B.harga ELSE 0 END) AS ass2,
						SUM(CASE WHEN A.id_type ='ASS3' THEN B.harga ELSE 0 END) AS ass3,
						SUM(CASE WHEN A.id_type ='ASS4' THEN B.harga ELSE 0 END) AS ass4,
						SUM(CASE WHEN A.id_type ='C2' THEN B.harga ELSE 0 END) AS claim,
						SUM(CASE WHEN A.id_type ='LS' THEN B.harga ELSE 0 END) AS ls,
						SUM(CASE WHEN A.id_type ='HR' THEN B.harga ELSE 0 END) AS hr,
						SUM(CASE WHEN A.id_type ='CS' THEN B.harga ELSE 0 END) AS cs,
						SUM(CASE WHEN A.id_type ='LR' THEN B.harga ELSE 0 END) AS lr,
						SUM(CASE WHEN A.id_type ='OR+' THEN B.harga ELSE 0 END) AS or_plus,
						SUM(CASE WHEN A.id_type ='JR' THEN B.harga ELSE 0 END) AS jr,
						SUM(CASE WHEN A.id_type ='PUD' THEN B.harga ELSE 0 END) AS pud,
						SUM(CASE WHEN A.id_type = 'PL' THEN B.harga ELSE 0 END) AS pl,
						SUM(CASE WHEN A.id_type ='OTHER' THEN B.harga ELSE 0 END) AS other
						FROM tr_h2_wo_dealer_pekerjaan B 
						JOIN ms_h2_jasa A ON A.id_jasa=B.id_jasa 
						JOIN tr_h2_wo_dealer C ON B.id_work_order=C.id_work_order
						join ms_dealer d on d.id_dealer = C.id_dealer
						WHERE C.status='closed'
						-- AND left(C.created_njb_at,10)='$tgl_awal' 
						and C.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59'
						-- and C.id_dealer ='$id_dealer'
						-- and d.kode_dealer_ahm ='$kode_dealer_ahm'
						and B.pekerjaan_batal=0 
						and C.id_dealer IN ('104','728')
						and C.no_njb is not null 
						-- group by d.kode_dealer_ahm 
						")->row_array();
			}else{
				$data_part_oli = $this->db->query("SELECT REPLACE(FORMAT(SUM(CASE WHEN skp.produk='Parts' and a.referensi='sales' then (CASE WHEN b.tipe_diskon='Percentage' 
					then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS spart_tanpa_wo,
					REPLACE(FORMAT(SUM(CASE WHEN skp.produk='Parts' and a.referensi='work_order' then (CASE WHEN b.tipe_diskon='Percentage' 
					then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS spart_wo,
					REPLACE(FORMAT(SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='sales' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS oil_tanpa_wo,
					REPLACE(FORMAT(SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='work_order' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
					WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END),0),',','') AS oil_wo
					from tr_h23_nsc a
					join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc 
					join ms_part c on c.id_part_int=b.id_part_int 
					join ms_dealer d on d.id_dealer = a.id_dealer 
					join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part= c.kelompok_part
					-- where left(a.created_at ,10)='$tgl_awal' 
					where a.created_at between '$tgl_awal' and '$tgl_awal 23:59:59'
					and d.kode_dealer_ahm='$kode_dealer_ahm' 
					group by d.kode_dealer_ahm")->row_array();

				$mekanik = $this->db->query("SELECT count(case when b.id_karyawan_dealer !=null then 1 else 0 end) as mekanik
						from tr_h2_absen_mekanik a
						left join tr_h2_absen_mekanik_detail b on a.id_absen=b.id_absen 
						join ms_dealer d on d.id_dealer = a.id_dealer
						-- where left(a.created_at,10) ='$tgl_awal' 
						where a.created_at between '$tgl_awal' and '$tgl_awal 23:59:59' and b.aktif='1' 
						-- and a.id_dealer='$id_dealer'
						and d.kode_dealer_ahm='$kode_dealer_ahm'
						group by d.kode_dealer_ahm")->row_array();

				$pit = $this->db->query("SELECT count(case when a.id_pit != null then 1 else 0 end) as pit
						from ms_h2_pit a
						join ms_dealer d on d.id_dealer = a.id_dealer
						where 
						-- a.id_dealer='$id_dealer' 
						-- and 
						d.kode_dealer_ahm='$kode_dealer_ahm' and a.active='1'
						group by d.kode_dealer_ahm")->row_array();

				
				$salesIn = $this->db->query("SELECT SUM(case when so.produk='Parts' then dso.total else 0 end) as part, SUM(case when so.produk='Oil' then dso.total else 0 end) as oli  
							FROM tr_h3_md_sales_order so
							JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
							JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
							JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
							JOIN ms_dealer md on md.id_dealer=so.id_dealer 
							-- WHERE left(ps.tgl_faktur,10) ='$tgl_awal' 
							where ps.tgl_faktur between '$tgl_awal' and '$tgl_awal 23:59:59'
							and md.kode_dealer_ahm='$kode_dealer_ahm'
							GROUP by md.kode_dealer_ahm")->row_array();

				$toj = $this->db->query("SELECT 
						SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
						SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
						SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
						SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
						SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
						SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
						SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
						SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
						SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
						SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
						SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
						SUM(CASE WHEN c.id_type='PUD' then 1 ELSE 0 end )as total_pud,
						SUM(CASE WHEN c.id_type='PL' then 1 ELSE 0 end )as total_pl,
						SUM(CASE WHEN c.id_type ='OTHER' then 1 ELSE 0 end )as total_other
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						WHERE b.status='closed' 
						-- and left(b.created_njb_at,10)='$tgl_awal' 
						and b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59'
						-- and b.id_dealer='$id_dealer' 
						and a.kode_dealer_ahm ='$kode_dealer_ahm'
						and e.pekerjaan_batal=0
						and b.no_njb is not null 
						GROUP BY a.kode_dealer_ahm")->row_array();

				$toj11 = $this->db->query("SELECT 
						SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end)as total_ass1,
						SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end)as total_ass2,
						SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end)as total_ass3,
						SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end)as total_ass4,
						SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end)as total_cs,
						SUM(CASE WHEN c.id_type in ('LS','OTHER','PL') then 1 ELSE 0 end)as total_ls_other_pl,
						SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end)as total_or,
						SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end)as total_lr,
						SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end)as total_hr,
						SUM(CASE WHEN c.id_type in ('C2','PUD') then 1 ELSE 0 end)as total_claim_pud
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						-- LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						-- JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						WHERE b.status='closed' 
						-- and left(b.created_njb_at,10)='$tgl_awal' 
						and b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59'
						-- and b.id_dealer='$id_dealer' 
						and a.kode_dealer_ahm ='$kode_dealer_ahm'
						and b.no_njb is not null 
						GROUP BY a.kode_dealer_ahm")->row_array();

				$ap = $this->db->query("SELECT  
						SUM(CASE WHEN a.kode ='PEOT' THEN 1 ELSE 0 END) AS pit_express_outdoor,
						SUM(CASE WHEN a.kode ='PEIN' THEN 1 ELSE 0 END) AS pit_express_indoor,
						SUM(CASE WHEN a.kode ='RM' THEN 1 ELSE 0 END) AS reminder,
						SUM(CASE WHEN a.kode in ('AE01','AE02','AE03') THEN 1 ELSE 0 END) AS ahass_event,
						SUM(CASE WHEN a.kode in ('SVAH','SVHC','SVJD','SVGC','SVPA','SVER') THEN 1 ELSE 0 END) AS service_visit,
						SUM(CASE WHEN a.kode ='SVPS' THEN 1 ELSE 0 END) AS pos_service
						from tr_h2_sa_form c 
						join tr_h2_wo_dealer b on c.id_sa_form=b.id_sa_form 
						join dms_ms_activity_promotion a on c.activity_promotion_id = a.id 
						join ms_dealer d on d.id_dealer = b.id_dealer 
						-- where left(b.created_njb_at,10)='$tgl_awal'
						where b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59'
						 and b.status='closed' 
						-- and b.id_dealer='$id_dealer' 
						and d.kode_dealer_ahm ='$kode_dealer_ahm'
						and b.no_njb is not null 
						group by d.kode_dealer_ahm")->row_array();

				$ac = $this->db->query("SELECT SUM(CASE WHEN a.kode='BS' THEN 1 ELSE 0 END) AS booking_service
						from tr_h2_sa_form c
						join tr_h2_wo_dealer b on c.id_sa_form=b.id_sa_form 
						join dms_ms_activity_capacity a on c.activity_capacity_id = a.id 
						join ms_dealer d on d.id_dealer = b.id_dealer 
						-- where left(b.created_njb_at,10)='$tgl_awal' 
						where b.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59'
						and b.status='closed' 
						-- and b.id_dealer='$id_dealer' 
						and d.kode_dealer_ahm ='$kode_dealer_ahm'
						and b.no_njb is not null 
						group by d.kode_dealer_ahm")->row_array();

				$jasaRev = $this->db->query("SELECT 
						SUM(CASE WHEN A.id_type in ('ASS1','ASS2','ASS3','ASS4') THEN B.harga ELSE 0 END) AS ass,
						SUM(CASE WHEN A.id_type ='ASS1' THEN B.harga ELSE 0 END) AS ass1,
						SUM(CASE WHEN A.id_type ='ASS2' THEN B.harga ELSE 0 END) AS ass2,
						SUM(CASE WHEN A.id_type ='ASS3' THEN B.harga ELSE 0 END) AS ass3,
						SUM(CASE WHEN A.id_type ='ASS4' THEN B.harga ELSE 0 END) AS ass4,
						SUM(CASE WHEN A.id_type ='C2' THEN B.harga ELSE 0 END) AS claim,
						SUM(CASE WHEN A.id_type ='LS' THEN B.harga ELSE 0 END) AS ls,
						SUM(CASE WHEN A.id_type ='HR' THEN B.harga ELSE 0 END) AS hr,
						SUM(CASE WHEN A.id_type ='CS' THEN B.harga ELSE 0 END) AS cs,
						SUM(CASE WHEN A.id_type ='LR' THEN B.harga ELSE 0 END) AS lr,
						SUM(CASE WHEN A.id_type ='OR+' THEN B.harga ELSE 0 END) AS or_plus,
						SUM(CASE WHEN A.id_type ='JR' THEN B.harga ELSE 0 END) AS jr,
						SUM(CASE WHEN A.id_type ='PUD' THEN B.harga ELSE 0 END) AS pud,
						SUM(CASE WHEN A.id_type = 'PL' THEN B.harga ELSE 0 END) AS pl,
						SUM(CASE WHEN A.id_type ='OTHER' THEN B.harga ELSE 0 END) AS other
						FROM tr_h2_wo_dealer_pekerjaan B 
						JOIN ms_h2_jasa A ON A.id_jasa=B.id_jasa 
						JOIN tr_h2_wo_dealer C ON B.id_work_order=C.id_work_order
						join ms_dealer d on d.id_dealer = C.id_dealer
						WHERE C.status='closed'
						-- AND left(C.created_njb_at,10)='$tgl_awal' 
						and C.created_njb_at between '$tgl_awal' and '$tgl_awal 23:59:59'
						-- and C.id_dealer ='$id_dealer'
						and d.kode_dealer_ahm ='$kode_dealer_ahm' 
						and B.pekerjaan_batal=0
						and C.no_njb is not null 
						group by d.kode_dealer_ahm ")->row_array();
			}
			$mdCode     =  	'E20';
			$dealerCode =  	$data_dealer[$i]['kode_dealer_ahm']?: 0;
			$oilWO		=	$data_part_oli['oil_wo']?: 0;
			$oilNonWO	=	$data_part_oli['oil_tanpa_wo']?: 0;
			$hgpWO		=	$data_part_oli['spart_wo']?: 0;
			$hgpNonWO	=	$data_part_oli['spart_tanpa_wo']?: 0;
			$mechProd	=	$mekanik['mekanik'] ?: 0;
			$salesInPart=	$salesIn['part'] ?: 0;
			$salesInOil	=	$salesIn['oli']?: 0;
			$pit		=	$pit['pit']?: 0;
			$pit_start	=	'20220101';
			$pit_end	=	'21000101';
			$toj101		=	$toj['total_ass1']?: 0;
			$toj102		=	$toj['total_ass2']?: 0;
			$toj103		=	$toj['total_ass3']?: 0;
			$toj104		=	$toj['total_ass4']?: 0;
			$toj105		=	$toj['total_claim']?: 0;
			$toj106		=	$toj['total_cs']?: 0;
			$toj107		=	$toj['total_ls']?: 0;
			$toj108		=	$toj['total_or']?: 0;
			$toj109		=	$toj['total_lr']?: 0;
			$toj110		=	$toj['total_hr']?: 0;
			$toj111		=	$toj['total_pud']?: 0;
			$toj112		=	$toj['total_other']?: 0;
			$toj113		=	$toj['total_pl']?: 0;
			$toj114		=	$toj['total_jr']?: 0;

			$toj201		=	$toj11['total_ass1']?: 0;
			$toj202		=	$toj11['total_ass2']?: 0;
			$toj203		=	$toj11['total_ass3']?: 0;
			$toj204		=	$toj11['total_ass4']?: 0;
			$toj205		=	$toj11['total_claim_pud']?: 0;
			$toj206		=	$toj11['total_cs']?: 0;
			$toj207		=	$toj11['total_ls_other_pl']?: 0;
			$toj208		=	$toj11['total_or']?: 0;
			$toj209		=	$toj11['total_lr']?: 0;
			$toj210		=	$toj11['total_hr']?: 0;

			$peOutdoor	=	$ap['pit_express_outdoor']?: 0;
			$peIndoor	=	$ap['pit_express_indoor']?: 0;
			$reminder	=	$ap['reminder']?: 0;
			$ahassEvent	=	$ap['ahass_event']?: 0;
			$serviceVisit=	$ap['service_visit']?: 0;
			$posService	=	$ap['pos_service']?: 0;

			$bookingService	=	$ac['booking_service']?: 0;

			$ass1		=	$jasaRev['ass1']?: 0;
			$ass2		=	$jasaRev['ass2']?: 0;
			$ass3		=	$jasaRev['ass3']?: 0;
			$ass4		=	$jasaRev['ass4']?: 0;
			$claim		=	$jasaRev['claim']?: 0;
			$cs			=	$jasaRev['cs']?: 0;
			$ls			=	$jasaRev['ls']?: 0;
			$or_plus	=	$jasaRev['or_plus']?: 0;
			$lr			=	$jasaRev['lr']?: 0;
			$hr			=	$jasaRev['hr']?: 0;
			$pud		=	$jasaRev['pud']?: 0;
			$other		=	$jasaRev['other']?: 0;
			$pl			=	$jasaRev['pl']?: 0;
			$jr			=	$jasaRev['jr']?: 0;
		
			

			$row = $dom->createElement('row');

			$mdCode2    = $dom->createElement('mdCode', $mdCode); 
		  	$row->appendChild($mdCode2); 
			$dealerCode2= $dom->createElement('dealerCode', $dealerCode); 
		  	$row->appendChild($dealerCode2); 
			$trans2     = $dom->createElement('trans', $trans); 
		  	$row->appendChild($trans2);
			$salesInPart2= $dom->createElement('salesInPart', $salesInPart); 
			$row->appendChild($salesInPart2); 
			$salesInOil2= $dom->createElement('salesInOil', $salesInOil); 
			$row->appendChild($salesInOil2);  
			$hgpWO2    	= $dom->createElement('hgpWO', $hgpWO); 
		  	$row->appendChild($hgpWO2); 
			$hgpNonWO2  = $dom->createElement('hgpNonWO', $hgpNonWO); 
		  	$row->appendChild($hgpNonWO2); 
			$oilWO2  	= $dom->createElement('oilWO', $oilWO); 
			$row->appendChild($oilWO2); 
			$oilNonWO2	= $dom->createElement('oilNonWO', $oilNonWO); 
			$row->appendChild($oilNonWO2); 
			$mechProd2	= $dom->createElement('mechProd', $mechProd); 
			$row->appendChild($mechProd2); 	 
			$pit2		= $dom->createElement('pit', $pit); 
			$row->appendChild($pit2);
			$pit_start2		= $dom->createElement('pitStartEff', $pit_start); 
			$row->appendChild($pit_start2);
			$pit_end2		= $dom->createElement('pitEndEff', $pit_end); 
			$row->appendChild($pit_end2);
			
			$details = $dom->createElement('details');
			$details->setAttribute('detailId', 'sdueDetail');

			// 1:>
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '101'); 
					$dtl->appendChild($seq);
					$toj101_2	= $dom->createElement('amount', $toj101); 
					$dtl->appendChild($toj101_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '102'); 
					$dtl->appendChild($seq);
					$toj102_2	= $dom->createElement('amount', $toj102); 
					$dtl->appendChild($toj102_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '103'); 
					$dtl->appendChild($seq);
					$toj103_2	= $dom->createElement('amount', $toj103); 
					$dtl->appendChild($toj103_2);
				$details->appendChild($dtl);
				
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '104'); 
					$dtl->appendChild($seq);
					$toj104_2	= $dom->createElement('amount', $toj104); 
					$dtl->appendChild($toj104_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '105'); 
					$dtl->appendChild($seq);
					$toj105_2	= $dom->createElement('amount', $toj105); 
					$dtl->appendChild($toj105_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '106'); 
					$dtl->appendChild($seq);
					$toj106_2	= $dom->createElement('amount', $toj106); 
					$dtl->appendChild($toj106_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '107'); 
					$dtl->appendChild($seq);
					$toj107_2	= $dom->createElement('amount', $toj107); 
					$dtl->appendChild($toj107_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '108'); 
					$dtl->appendChild($seq);
					$toj108_2	= $dom->createElement('amount', $toj108); 
					$dtl->appendChild($toj108_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '109'); 
					$dtl->appendChild($seq);
					$toj109_2	= $dom->createElement('amount', $toj109); 
					$dtl->appendChild($toj109_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '110'); 
					$dtl->appendChild($seq);
					$toj110_2	= $dom->createElement('amount', $toj110); 
					$dtl->appendChild($toj110_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '111'); 
					$dtl->appendChild($seq);
					$toj111_2	= $dom->createElement('amount', $toj111); 
					$dtl->appendChild($toj111_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '112'); 
					$dtl->appendChild($seq);
					$toj112_2	= $dom->createElement('amount', $toj112); 
					$dtl->appendChild($toj112_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '113'); 
					$dtl->appendChild($seq);
					$toj113_2	= $dom->createElement('amount', $toj113); 
					$dtl->appendChild($toj113_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '114'); 
					$dtl->appendChild($seq);
					$toj114_2	= $dom->createElement('amount', $toj114); 
					$dtl->appendChild($toj114_2);
				$details->appendChild($dtl);
			// Batas ToJ 1:>
			
			// ToJ 1:1
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '201'); 
					$dtl->appendChild($seq);
					$toj201_2	= $dom->createElement('amount', $toj201); 
					$dtl->appendChild($toj201_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '202'); 
					$dtl->appendChild($seq);
					$toj202_2	= $dom->createElement('amount', $toj202); 
					$dtl->appendChild($toj202_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '203'); 
					$dtl->appendChild($seq);
					$toj203_2	= $dom->createElement('amount', $toj203); 
					$dtl->appendChild($toj203_2);
				$details->appendChild($dtl);
				
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '204'); 
					$dtl->appendChild($seq);
					$toj204_2	= $dom->createElement('amount', $toj204); 
					$dtl->appendChild($toj204_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '205'); 
					$dtl->appendChild($seq);
					$toj205_2	= $dom->createElement('amount', $toj205); 
					$dtl->appendChild($toj205_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '206'); 
					$dtl->appendChild($seq);
					$toj206_2	= $dom->createElement('amount', $toj206); 
					$dtl->appendChild($toj206_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '207'); 
					$dtl->appendChild($seq);
					$toj207_2	= $dom->createElement('amount', $toj207); 
					$dtl->appendChild($toj207_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '208'); 
					$dtl->appendChild($seq);
					$toj208_2	= $dom->createElement('amount', $toj208); 
					$dtl->appendChild($toj208_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '209'); 
					$dtl->appendChild($seq);
					$toj209_2	= $dom->createElement('amount', $toj209); 
					$dtl->appendChild($toj209_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ToJ11'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '210'); 
					$dtl->appendChild($seq);
					$toj210_2	= $dom->createElement('amount', $toj210); 
					$dtl->appendChild($toj210_2);
				$details->appendChild($dtl);
			// Batas ToJ 1:1

			// Actitity Promotion
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '301'); 
					$dtl->appendChild($seq);
					$peOutdoor2	= $dom->createElement('amount', $peOutdoor); 
					$dtl->appendChild($peOutdoor2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '302'); 
					$dtl->appendChild($seq);
					$peIndoor2	= $dom->createElement('amount', $peIndoor); 
					$dtl->appendChild($peIndoor2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '303'); 
					$dtl->appendChild($seq);
					$reminder2	= $dom->createElement('amount', $reminder); 
					$dtl->appendChild($reminder2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '304'); 
					$dtl->appendChild($seq);
					$ahassEvent2	= $dom->createElement('amount', $ahassEvent); 
					$dtl->appendChild($ahassEvent2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '305'); 
					$dtl->appendChild($seq);
					$serviceVisit2	= $dom->createElement('amount', $serviceVisit); 
					$dtl->appendChild($serviceVisit2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'APROM'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '306'); 
					$dtl->appendChild($seq);
					$posService2	= $dom->createElement('amount', $posService); 
					$dtl->appendChild($posService2);
				$details->appendChild($dtl);

			// Batas Actitity Promotion

			// Revenue TOJ
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '401'); 
					$dtl->appendChild($seq);
					$ass1_2	= $dom->createElement('amount', $ass1); 
					$dtl->appendChild($ass1_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '402'); 
					$dtl->appendChild($seq);
					$ass2_2	= $dom->createElement('amount', $ass2); 
					$dtl->appendChild($ass2_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '403'); 
					$dtl->appendChild($seq);
					$ass3_2	= $dom->createElement('amount', $ass3); 
					$dtl->appendChild($ass3_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '404'); 
					$dtl->appendChild($seq);
					$ass4_2	= $dom->createElement('amount', $ass4); 
					$dtl->appendChild($ass4_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '405'); 
					$dtl->appendChild($seq);
					$claim_2	= $dom->createElement('amount', $claim); 
					$dtl->appendChild($claim_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '406'); 
					$dtl->appendChild($seq);
					$cs_2	= $dom->createElement('amount', $cs); 
					$dtl->appendChild($cs_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '407'); 
					$dtl->appendChild($seq);
					$ls_2	= $dom->createElement('amount', $ls); 
					$dtl->appendChild($ls_2);
				$details->appendChild($dtl);


				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '408'); 
					$dtl->appendChild($seq);
					$or_plus2	= $dom->createElement('amount', $or_plus); 
					$dtl->appendChild($or_plus2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '409'); 
					$dtl->appendChild($seq);
					$lr_2	= $dom->createElement('amount', $lr); 
					$dtl->appendChild($lr_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '410'); 
					$dtl->appendChild($seq);
					$hr_2	= $dom->createElement('amount', $hr); 
					$dtl->appendChild($hr_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '411'); 
					$dtl->appendChild($seq);
					$pud_2	= $dom->createElement('amount', $pud); 
					$dtl->appendChild($pud_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '412'); 
					$dtl->appendChild($seq);
					$other_2	= $dom->createElement('amount', $other); 
					$dtl->appendChild($other_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '413'); 
					$dtl->appendChild($seq);
					$pl_2	= $dom->createElement('amount', $pl); 
					$dtl->appendChild($pl_2);
				$details->appendChild($dtl);

				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'REVENUE'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '414'); 
					$dtl->appendChild($seq);
					$jr_2	= $dom->createElement('amount', $jr); 
					$dtl->appendChild($jr_2);
				$details->appendChild($dtl);
			// Batas Revenue ToJ

			//Activity Capacity
				$dtl = $dom->createElement('dtl');
					$dataID	= $dom->createElement('dataId', 'ACAP'); 
					$dtl->appendChild($dataID);
					$seq	= $dom->createElement('seq', '501'); 
					$dtl->appendChild($seq);
					$bs	= $dom->createElement('amount', $bookingService); 
					$dtl->appendChild($bs);
				$details->appendChild($dtl);
			//Batas Activity Capacity

			$row->appendChild($details);
			$root->appendChild($row);
		} 
		$dom->appendChild($root); 
		$dom->save($filePath); 
	}

	function file_dms(){
		date_default_timezone_set("Asia/Jakarta");
		
		// can be set a number of generate files/ days
		/*	
		$tgl = '2023-06-13';
		$start_date = '13';
		$end_date = '13';
		$month = '06';
		$year = '2023';
		$tahun = '23';
		*/

		if (isset($_GET['tanggal'])) {
			$tgl = $_GET['tanggal'];
		}else{
			// set tanggal utk bisa ambil H-1
			$tgl = date("Y-m-d");
			$tgl = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
		}

		$time = strtotime($tgl);
		$start_date = date("d",$time);
		$end_date = date("d",$time);
		$month = date("m",$time);
		$year = date("Y",$time);
		$tahun = date("y",$time);
		
		for($i=$start_date; $i<= $end_date; $i++){
			$line = 0;
			$no = $i;
			
			if(strlen($i)<2){
				$no = '0'.$i; 
			}		
			$tgl = $year.'-'.$month.'-'.$no;
			
			$id_dealer = array();

			$nama_file = "AHM-E20-".date($tahun.$month.$no)."-".date($tahun.$month.$no.'Hi').".DMS";
			
			$location = getenv("DOCUMENT_ROOT") . "/downloads/dms/";
			$myfile = fopen($location.$nama_file, "w") or die("Unable to open file!");
								
			$list_penerimaan_unit = "
					select a.id_penerimaan_unit_dealer, a.tgl_penerimaan, a.id_goods_receipt,a.created_at, a.updated_at, b.no_po, b.no_do,
					(case when c.kode_dealer_ahm = 'TA' then c.head_office else c.kode_dealer_ahm end ) as kode_dealer_ahm				
					from tr_penerimaan_unit_dealer a 
					join tr_do_po b on a.no_do =b.no_do
					join ms_dealer c on a.id_dealer = c.id_dealer
					where date(a.created_at) = '$tgl' and a.id_goods_receipt is not null and id_goods_receipt !=''
					order by a.created_at asc, a.updated_at asc
				";
			$list_prospek = "
					select
					'H1;B;',
					(case
						when b.kode_dealer_ahm = 'TA' then b.head_office
						else b.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					a.created_at,
					a.updated_at,
					a.id_prospek,
					a.id_list_appointment,
					';;'
				from
					tr_prospek a
				join ms_dealer b on
					a.id_dealer = b.id_dealer
				where
					date(a.created_at) = '$tgl'
				union 
				select
					'H1;B;',
					(case
						when b.kode_dealer_ahm = 'TA' then b.head_office
						else b.kode_dealer_ahm
					end) as kode_dealer_ahm,
					gc.created_at,
					gc.updated_at,
					gc.id_prospek_gc as id_prospek,
					'',
					';;'
				from
					tr_prospek_gc gc
				join ms_dealer b on
					gc.id_dealer = b.id_dealer
				where
					date(gc.created_at) = '$tgl'
			"; 
			//and a.status_prospek = 'Deal'
			
			$list_spk = "
				select
					'H1,C;',
					(case
						when c.kode_dealer_ahm = 'TA' then c.head_office
						else c.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					a.created_at,
					a.updated_at,
					a.no_spk,
					b.id_prospek,
					b.id_list_appointment,
					';'
				from
					tr_spk a
				join tr_prospek b on
					a.id_customer = b.id_customer
				join ms_dealer c on
					a.id_dealer = c.id_dealer
				where
					date(a.created_at) = '$tgl'
					and a.no_spk is not null
					and a.no_spk != ''
					and (a.status_spk ='approved' or a.status_spk = 'close')
				union 
				select
					'H1,C;',
					(case
						when c.kode_dealer_ahm = 'TA' then c.head_office
						else c.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					gca.created_at,
					gca.updated_at,
					gca.no_spk_gc,
					gcb.id_prospek_gc as id_prospek,
					'',
					';'
				from
					tr_spk_gc gca
				join tr_prospek_gc gcb on
					gca.id_prospek_gc = gcb.id_prospek_gc
				join ms_dealer c on
					gca.id_dealer = c.id_dealer
				where
					date(gca.created_at) = '$tgl'
					and gca.no_spk_gc is not null
					and gca.no_spk_gc != ''
					and gca.status = 'approved'
				";
			$list_inv = "
				select
					'H1;D;',
					(case
						when b.kode_dealer_ahm = 'TA' then b.head_office
						else b.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					a.created_at,
					a.updated_at,
					a.no_invoice,
					a.id_sales_order,
					a.no_spk,
					';'
				from
					tr_sales_order a
				join ms_dealer b on
					a.id_dealer = b.id_dealer
				where
					date(a.created_at) = '$tgl'
					and a.no_invoice is not null
					and a.no_invoice != ''
				union 
				select
					'H1;D;',
					(case
						when b.kode_dealer_ahm = 'TA' then b.head_office
						else b.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					gca.created_at,
					gca.updated_at,
					gca.no_invoice,
					gca.id_sales_order_gc,
					gca.no_spk_gc,
					';'
				from
					tr_sales_order_gc gca
				join ms_dealer b on
					gca.id_dealer = b.id_dealer
				where
					date(gca.created_at) = '$tgl'
					and gca.no_invoice is not null
					and gca.no_invoice != ''
			";
			$list_handle_leasing = "
					select
					'H1;E;',
					(case
						when b.kode_dealer_ahm = 'TA' then b.head_office
						else b.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					a.created_at,
					a.updated_at,
					a.no_order_survey,
					a.no_spk,
					';'
				from
					tr_order_survey a
				join ms_dealer b on
					a.id_dealer = b.id_dealer
				join tr_spk c on
					a.no_spk = c.no_spk
				where
					date(a.created_at) = '$tgl'
					and a.no_order_survey is not null
					and a.no_order_survey != ''
					and c.status_survey = 'approved'
				UNION select
					'H1;E;',
					(case
						when b.kode_dealer_ahm = 'TA' then b.head_office
						else b.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					gca.created_at,
					gca.updated_at,
					gca.no_order_survey_gc,
					gca.no_spk_gc,
					';'
				from
					tr_order_survey_gc gca
				join ms_dealer b on
					gca.id_dealer = b.id_dealer
				join tr_spk_gc gcc on
					gca.no_spk_gc = gcc.no_spk_gc
				where
					date(gca.created_at) = '$tgl'
					and gca.no_order_survey_gc is not null
					and gca.no_order_survey_gc != ''
					and gcc.status_survey = 'approved'
			";
			
			$list_doch = "			
				select
					'H1;F;',
					(case
						when a.kode_dealer_ahm = 'TA' then a.head_office
						else a.kode_dealer_ahm
					end ) as kode_dealer_ahm,
					d.created_at,
					d.updated_at,
					c.id_sales_order,
					c.no_spk,
					d.tgl_terima_stnk as tgl_serah_terima
				from
					tr_faktur_stnk_detail c
				left join tr_tandaterima_stnk_konsumen_detail b on
					c.no_mesin = b.no_mesin
				left join tr_tandaterima_stnk_konsumen d on
					b.kd_stnk_konsumen = d.kd_stnk_konsumen
				join ms_dealer a on
					a.id_dealer = d.id_dealer
				where
					date(d.tgl_terima_stnk)= '$tgl' and d.jenis_cetak = 'stnk'
					and c.id_sales_order is not null
					and c.id_sales_order != '' and d.tgl_terima_stnk is not NULL and d.tgl_terima_stnk !=''
				group by c.id_sales_order, c.no_spk
			";
			
			// belum dapat relasi utk bastk gc
			$list_6_reqular = "						
					select 'H1;G;', (case when b.kode_dealer_ahm = 'TA' then b.head_office else b.kode_dealer_ahm end ) as kode_dealer_ahm, a.created_at, a.updated_at, a.delivery_document_id, a.no_spk, ';'  
					from tr_sales_order a
					join ms_dealer b on a.id_dealer = b.id_dealer
					where date(a.created_at) = '$tgl' and a.delivery_document_id is not null and a.delivery_document_id !=''
					order by a.created_at asc, a.updated_at asc
			";
			
			$list_6 = "						
					select 'H1;G;', (case when b.kode_dealer_ahm = 'TA' then b.head_office else b.kode_dealer_ahm end ) as kode_dealer_ahm, a.created_at, a.updated_at, a.delivery_document_id, a.no_spk, ';'  
					from tr_sales_order a
					join ms_dealer b on a.id_dealer = b.id_dealer
					where date(a.created_at) = '$tgl' and a.delivery_document_id is not null and a.delivery_document_id !=''
					union		
					select 'H1;G;', (case when b.kode_dealer_ahm = 'TA' then b.head_office else b.kode_dealer_ahm end ) as kode_dealer_ahm, c.created_at, c.updated_at, a.delivery_document_id, a.no_spk_gc,';'
					from tr_sales_order_gc_nosin a
					join tr_sales_order_gc c on a.id_sales_order_gc = c.id_sales_order_gc 
					join ms_dealer b on c.id_dealer = b.id_dealer
					where date(c.created_at) = '$tgl' and a.delivery_document_id is not null and a.delivery_document_id !=''
			";
				
			$id_dealerh23= "'19','9','23','54','88','91','105','66','104','2','8','38','76','78','81','97','102','106','107','4','13','39','74','83','131','44','83','41','1','39','103','51','66','22','43','25','40','64','101','46','18','78','38','80','47','96','77','70','98','58','86','71','90','2','84','97','82','30','66','48','85','39','106','74','13','714'";

			$h23_wo="
					SELECT
						'H23;A;',
						(case
							when a.kode_dealer_ahm = 'TA' then a.head_office
							else a.kode_dealer_ahm
						end ) as kode_dealer_ahm,
						b.created_at,
						b.id_work_order,
						';;'
					from
						tr_h2_wo_dealer b
					join ms_dealer a on
						b.id_dealer = a.id_dealer
					where
						date(b.closed_at)= '$tgl' 
						and b.status='closed'
			";
			
			$h23BillingProcess="
					SELECT
						'H23;B;',
						(case
							when a.kode_dealer_ahm = 'TA' then a.head_office
							else a.kode_dealer_ahm
						end ) as kode_dealer_ahm,
						b.created_njb_at as created_at,
						b.no_njb,
						b.id_work_order,
						';'
					from
						tr_h2_wo_dealer b
					join ms_dealer a on
						b.id_dealer = a.id_dealer
					where
						date(b.created_njb_at)= '$tgl' and b.status = 'closed'
			";
			
			$h23SalesOrder="
					SELECT
						'H23;D;',
						(case
							when a.kode_dealer_ahm = 'TA' then a.head_office
							else a.kode_dealer_ahm
						end ) as kode_dealer_ahm,
						b.created_at,
						case 
							when b.referensi = 'work_order' then b.no_nsc
							when b.referensi = 'sales' then b.id_referensi
						end as referensi,
						';;'
					from
						tr_h23_nsc b
					join ms_dealer a on
						b.id_dealer = a.id_dealer
					where
						date(b.created_at)= '$tgl'
			";
			
			$h23PartInbound="
					SELECT
						'H23;C;',
						(case
							when a.kode_dealer_ahm = 'TA' then a.head_office
							else a.kode_dealer_ahm
						end ) as kode_dealer_ahm,
						b.created_at,
						b.id_good_receipt,
						b.nomor_po,
						';'
					from
						tr_h3_dealer_good_receipt b
					join ms_dealer a on
						b.id_dealer = a.id_dealer
					where
						date(b.created_at)= '$tgl' 
			";
			
			$get_data_pu = $this->db->query($list_penerimaan_unit); // penerimaan unit
			$get_data_1 = $this->db->query($list_prospek); // prospek
			$get_data_2 = $this->db->query($list_spk); // spk
			$get_data_3 = $this->db->query($list_inv); // invoice
			$get_data_4 = $this->db->query($list_handle_leasing); // pengajuan ke leasing
			$get_data_5 = $this->db->query($list_doch); // serah stnk ke konsumen
			$get_data_6 = $this->db->query($list_6); // delivery unit ke konsumen
			$get_data_wo= $this->db->query($h23_wo); // get data work order h23
			$get_data_njb= $this->db->query($h23BillingProcess); // get data billing process h23
			$get_data_so= $this->db->query($h23SalesOrder); // get data sales order h23
			$get_data_pi= $this->db->query($h23PartInbound); // get data sales order h23
			
			if ($get_data_pu->num_rows() > 0) {
				foreach($get_data_pu->result_array() as $row) {
					// print_r($row);die;
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H1;A;".$row['kode_dealer_ahm'].";".$wkt.";".$row['id_goods_receipt'].";".$row['no_po'].";"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_1->num_rows() > 0) {					
				foreach($get_data_1->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H1;B;".$row['kode_dealer_ahm'].";".$wkt.";".$row['id_prospek'].";;"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_2->num_rows() > 0) {
				foreach($get_data_2->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H1;C;".$row['kode_dealer_ahm'].";".$wkt.";".$row['no_spk'].";".$row['id_prospek'].";"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_3->num_rows() > 0) {
				foreach($get_data_3->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H1;D;".$row['kode_dealer_ahm'].";".$wkt.";".$row['no_invoice'].";".$row['no_spk'].";"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_4->num_rows() > 0) {
				foreach($get_data_4->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H1;E;".$row['kode_dealer_ahm'].";".$wkt.";".$row['no_order_survey'].";".$row['no_spk'].";"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_5->num_rows() > 0) {
				foreach($get_data_5->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$tgl_serah_terima = date_format(date_create($row['tgl_serah_terima']),'YmdHi');
					//$tgl_serah_terima = date_format(date_create($row['created_at']),'YmdHi');
					$txt = "H1;F;".$row['kode_dealer_ahm'].";".$wkt.";".$row['id_sales_order'].";".$row['no_spk'].";".$tgl_serah_terima."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
				
			if ($get_data_6->num_rows() > 0) {
				foreach($get_data_6->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H1;G;".$row['kode_dealer_ahm'].";".$wkt.";".$row['delivery_document_id'].";".$row['no_spk'].";"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_wo->num_rows() > 0) {
				foreach($get_data_wo->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H23;A;".$row['kode_dealer_ahm'].";".$wkt.";".$row['id_work_order'].";;"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_njb->num_rows() > 0) {
				foreach($get_data_njb->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H23;B;".$row['kode_dealer_ahm'].";".$wkt.";".$row['no_njb'].";".$row['id_work_order'].";"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_pi->num_rows() > 0) {
				foreach($get_data_pi->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H23;C;".$row['kode_dealer_ahm'].";".$wkt.";".$row['id_good_receipt'].";".$row['nomor_po'].";"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			if ($get_data_so->num_rows() > 0) {
				foreach($get_data_so->result_array() as $row) {
					$line++;
					$wkt = $row['created_at'];
					/*if($row['updated_at']!=false || $row['updated_at']!=''){
						$wkt = $row['updated_at'];
					}*/
					$wkt = date_format(date_create($wkt),'YmdHi');
					$txt = "H23;D;".$row['kode_dealer_ahm'].";".$wkt.";".$row['referensi'].";;"."\r\n";
					fwrite($myfile, $txt);
					//echo $txt.'<br>';
				}
			}
			
			fclose($myfile);
			
			echo 'Berhasil Generate! <br>Pada tgl:'.$nama_file.' dengan Jumlah data: '.$line.' baris.<br>';
		}
	}

	public function api3_ev()
	{
		$this->load->helper('ev_helper');	
		$get_date = gmdate("y-m-d H:i:s", time()+60*60*7);

		$token =  get_token_ev();

		$this->db->select('acc.*, acc_hs.send_to_ahm, acc_hs.accStatus as update_acc');
		$this->db->from('ev_log_send_api_3 acc_hs');
		// $this->db->join('tr_status_ev_acc acc', 'acc_hs.serialNo = acc.serialNo', 'left');
		$this->db->join('tr_status_ev_acc acc', 'acc_hs.serialNo = acc.serialNo and acc_hs.accStatus = acc.accStatus');
		$this->db->where('acc_hs.send_to_ahm', null);
		$get = $this->db->get()->result();

		$temp = array();
		$history = array();

		foreach ($get as $item) 
		{
			$history[]=array(
				'serialNo'            => $item->serialNo,
				'accStatus'           => $item->accStatus,
				'send_to_ahm'         => $get_date
			);
		
			$temp[]=array(
			'serialNo'            => $item->serialNo,
			'accType'             => $item->accType,
			'accStatus'           => $item->update_acc,
			'mdReceiveDate'       => $item->mdReceiveDate,
			'mdSLDate'            => isset($item->mdSLDate) ? $item->mdSLDate : '',
			'mdSLNo'              => isset($item->mdSLNo) ? $item->mdSLNo : '',
			'dealerCode'          => isset($item->dealerCode) ? $item->dealerCode : '',
			'dealerReceiveDate'   => isset($item->dealerReceiveDate) ? $item->dealerReceiveDate : '',
			'bastNo'              => isset($item->bastNo) ? $item->bastNo : '',
			'bastDate'            => isset($item->bastDate) ? $item->bastDate : '',
			'frameNo'             => isset($item->frameNo) ? 'MH1'.$item->frameNo : '',
			'engineNo'            => isset($item->engineNo) ? $item->engineNo : '',
			'phoneNo'             => isset($item->phoneNo) ? $item->phoneNo : '',
			'custName'            => isset($item->custName) ? $item->custName : '',
			'invDirectSalesDate'  => isset($item->invDirectSalesDate) ? $item->invDirectSalesDate : '',
			'invDirectSalesNo'    => isset($item->invDirectSalesNo) ? $item->invDirectSalesNo : ''
			);
		}

		// $url = 'https://portaldev.ahm.co.id/jx05/ahmsvsdeve000-pst/rest/sd/eve012/acc-update-status';
		$url = 'https://portal2.ahm.co.id/jx05/ahmsvsdeve000-pst/rest/sd/eve012/acc-update-status/s';

		$data = api_ev($token['jxid'], $token['txid'], $url, $temp);

		// var_dump($data); 
		
		$jsonData = json_encode($temp);
        $responseData = json_decode($data);

		// print_r($responseData);echo '<br><br>';
		 die;

        $status = $responseData->status;
        $message = json_encode($responseData->message);
		$err = json_encode($responseData->data);
		$transactionId = json_encode($responseData->message->transactionId);

		// perlu ganti ke update batch
		foreach ($get as $item) 
		{	
			$update=array(
				'send_to_ahm' => $get_date,
				'api_response' =>  200,
				'transaction_id'=>$transactionId 
			);

			$this->db->where('serialNo', $item->serialNo);
			$this->db->where('accStatus', $item->accStatus);
			$this->db->update('ev_log_send_api_3', $update);
		}


        // Processing data section
		/*		
		$error   =array();
		$success =array();

        foreach ($data as $item) {
			$errorMsg = count($item['errorMsg']);
			if ($errorMsg != 0) {
				$error[] = array (
					'serialNumber'    => $item['serialNo'],
					'response' => json_encode($item['errorMsg']),
					'transactionId'   => $item['transactionId'],
				);
				
			}else{
				$success[] = array (
					'serialNumber'    => $item['serialNo'],
					'response' => 'berhasil',
					'transactionId'   => $item['transactionId'],
				);
			}
        }

		foreach ($error as $item ) {
			$update=array(
				'send_to_ahm' => $get_date,
				'api_response' =>  200,
				'response' =>  $item['response_reject'],
				'transaction_id'=>$message['transactionId']
			);

			$item['serialNo'] =  $item['serialNumber'];
			$this->db->where('serialNo', $item['serialNo']);
			$this->db->update('ev_log_send_api_3', $update);
		}

		foreach ($success as $item ) {
			$update=array(
				'send_to_ahm' => $get_date,
				'api_response' =>  200,
				'response' => $responseData,
				'transaction_id'=>$message['transactionId']
			);

			$item['serialNo'] =  $item['serialNumber'];
			$this->db->where('serialNo', $item['serialNo']);
			$this->db->update('ev_log_send_api_3', $update);
		}
		*/

		$ins_log['post_data'] = $jsonData;
		$ins_log['created_at'] = date("Y-m-d H:i:s");
		$ins_log['api_key'] = isset($token['jxid']) ? $token['jxid'] : '';
		$ins_log['endpoint']   = $url;
		$ins_log['pinpoint']   = "acc-update-status";
		$ins_log['ip_address']   = $_SERVER['REMOTE_ADDR'];
		
		if($status == 1){
			$ins_log['http_response_code']       = "200";
			$ins_log['message']   = $message;
		}else{
			$ins_log['http_response_code']       = "404";
			if(count($history)>0){
				$ins_log['message']   = $err;
			}
		}

		$ins_log['kategori']   = "ALL";
		$ins_log['type']       = "push";
		$ins_log['data_count'] = count($history);
		$ins_log['status'] =  isset($responseData->status) ? $responseData->status : '';
		
		if(count($history)>0){
			$this->db->insert('activity_ev_log', $ins_log);
		}
	}
}
