<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indent_fullfilment extends CI_Controller {

	var $tables = "ms_pesan";	
	var $folder = "dealer";
	var $page   = "indent_fullfilment";
	var $title  = "Indent Fulfillment List";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		// $this->load->library('upload');
		// $this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group']   = $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
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
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array     = array();
			$button = '';
			if ($rs->no_mesin!=null) {
				$alert = "return confirm('Apakah anda yakin ingin melepaskan unit dengan nomor mesin $rs->no_mesin ke No Indent $rs->id_indent atas nama $rs->nama_konsumen ?')";
			}else{
				$alert = "alert('No Mesin belum tersedia !');return false";
			}
			$btn_konfir = "<a data-toggle='tooltip' onclick=\"$alert\" title='Delete' href='dealer/indent_fullfilment/save_konfirmasi?id=$rs->id_indent'><button class='btn btn-flat btn-xs btn-success'>Konfirmasi</button></a>";
			$btn_edit = "<a data-toggle='tooltip' title='Edit' href='dealer/indent_fullfilment/edit?id=$rs->id_indent'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
			$btn_approve = "<a data-toggle='tooltip' title='Approve' onclick=\"return confirm('Are you sure to approve this data ?')\" href='dealer/indent_fullfilment/approve?id=$rs->id_indent'><button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Approve</button></a>";
			$btn_reject = "<a data-toggle='tooltip' title='Reject' href='dealer/indent_fullfilment/approve?id=$rs->id_indent'><button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-cross'></i> Reject</button> </a>";
			$button = $btn_konfir;
			// $button = $btn_edit.$btn_konfir;
			$cek_nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka FROM tr_penerimaan_unit_dealer_detail 
					JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin
					WHERE id_indent='$rs->id_indent'");
			$nosin='';$no_rangka='';
			if ($cek_nosin->num_rows()>0) {
				$cs        = $cek_nosin->row();
				$nosin     = $cs->no_mesin;
				$no_rangka = $cs->no_rangka;
				$button    = '';
			}
			if ($rs->approved=='waiting_approval') {
				$button .=$btn_approve;
			}
			// $sub_array[] = "<a data-toggle='tooltip' href='dealer/indent_fullfilment/detail?id=$rs->id_pesan'>$rs->id_pesan</a>";
			$status_apr='';
			if ($rs->approved=='waiting_approval') {
				$status_apr = '<label class="label label-warning">Waiting Approval</label>';
			}
			if ($rs->approved=='approved') {
				$status_apr = '<label class="label label-success">Approved</label>';
			}
			$sub_array[] = $rs->id_indent;
			$sub_array[] = $rs->id_spk;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->id_tipe_kendaraan.' | '.$rs->tipe_ahm;
			$sub_array[] = $rs->id_warna.' | '.$rs->warna;
			$sub_array[] = $nosin;
			$sub_array[] = $no_rangka;
			$sub_array[] = $rs->sales;
			$sub_array[] = $status_apr;
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

   	function make_query($no_limit=null)  
   	{  
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_pesan','tipe_pesan','konten','start_date','end_date',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY created_at ASC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		// $searchs = '';
		$searchs      = "WHERE tpi.id_dealer=$id_dealer AND status='requested' AND status_monitoring='po_created'";
		
		if ($search!='') {
	      $searchs .= "AND (nama_konsumen LIKE '%$search%' 
	          OR id_spk LIKE '%$search%'
	          OR id_tipe_kendaraan LIKE '%$search%'
	          OR id_warna LIKE '%$search%'
	          OR warna LIKE '%$search%'
	          OR tipe_ahm LIKE '%$search%')
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT tpi.*,
   				(SELECT nama_lengkap FROM ms_karyawan_dealer 
   				WHERE id_karyawan_dealer=(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)) AS sales,warna,tipe_ahm,
   				(SELECT tpudd.no_mesin FROM tr_penerimaan_unit_dealer_detail AS tpudd
   				 JOIN tr_penerimaan_unit_dealer AS tpud ON tpudd.id_penerimaan_unit_dealer=tpud.id_penerimaan_unit_dealer
   				 JOIN tr_scan_barcode ON tpudd.no_mesin=tr_scan_barcode.no_mesin AND tr_scan_barcode.status=4
   				 WHERE po_indent='ya' 
   				 AND id_dealer=tpi.id_dealer 
   				 AND tipe_motor=tpi.id_tipe_kendaraan AND warna=tpi.id_warna 
   				 AND tpudd.id_indent IS NULL
   				 AND tpudd.jenis_pu='rfs'
   				 AND tpud.status='close'
   				 ORDER BY id_penerimaan_unit_dealer_detail ASC LIMIT 1) AS no_mesin
   			FROM tr_po_dealer_indent AS tpi 
   			LEFT JOIN tr_spk ON tpi.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tpi.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
   			JOIN ms_warna ON tpi.id_warna=ms_warna.id_warna
   			$searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	}  

	// public function add()
	// {				
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['mode']  = 'insert';
	// 	$data['set']   = "form";
	// 	$id_dealer     = $this->m_admin->cari_dealer();
	// 	$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
	// 		(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
	// 			case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran	
	// 		FROM tr_spk 
	// 			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
	// 			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
	// 			WHERE tr_spk.id_dealer=$id_dealer ORDER BY tr_spk.created_at DESC");
	// 	// $data['spk'] = $this->db->get('tr_spk');
	// 	$this->template($data);	
	// }
	
	// public function get_id_pesan()
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

	public function save_konfirmasi()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$id_indent = $this->input->get('id');
		$indent = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_indent='$id_indent'");
		$notif_sms = '';
		if ($indent->num_rows()>0) {
			$idt = $indent->row();
			$cek_indent = $this->db->query("SELECT tpi.*
	   			FROM tr_po_dealer_indent AS tpi 
	   			LEFT JOIN tr_spk ON tpi.id_spk=tr_spk.no_spk
	   			WHERE tpi.id_tipe_kendaraan='$idt->id_tipe_kendaraan' AND tpi.id_warna='$idt->id_warna' 
				AND status='requested' AND status_monitoring='po_created'
	   			ORDER BY id_indent ASC LIMIT 1
	   			")->row();
			$get_nosin = $this->db->query("SELECT tpudd.no_mesin,tpudd.id_penerimaan_unit_dealer_detail 
				 FROM tr_penerimaan_unit_dealer_detail AS tpudd
   				 JOIN tr_penerimaan_unit_dealer AS tpud ON tpudd.id_penerimaan_unit_dealer=tpud.id_penerimaan_unit_dealer
   				 JOIN tr_scan_barcode ON tpudd.no_mesin=tr_scan_barcode.no_mesin AND tr_scan_barcode.status=4
   				 WHERE po_indent='ya' 
   				 AND id_dealer=$id_dealer  
   				 AND tipe_motor='$idt->id_tipe_kendaraan' 
   				 AND warna='$idt->id_warna' 
   				 AND tpudd.id_indent IS NULL
   				 AND tpudd.jenis_pu='rfs'
   				 AND tpud.status='close'
   				 ORDER BY id_penerimaan_unit_dealer_detail ASC LIMIT 1");
			 $approved = $cek_indent->id_indent==$idt->id_indent?'approved':'waiting_approval';
			if ($get_nosin->num_rows()>0) {
				$ns = $get_nosin->row();
				$spk = $this->db->query("SELECT tr_spk.* FROM tr_spk
					WHERE no_spk='$idt->id_spk'
					LIMIT 1
					")->row();
				$warna          = $this->db->get_where('ms_warna',['id_warna'=>$spk->id_warna])->row();
				$tipe_kendaraan = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$spk->id_tipe_kendaraan])->row();
				$ins_manage = ['id_indent'=>$idt->id_indent,
								'no_spk'          =>$idt->id_spk,
								'created_at'      => $waktu,
								'kategori'        => 'indent',
								'status'          => 'Not Started',
								'detail_activity' => "Follow UP - Pesanan indent unit pelanggan $spk->nama_konsumen (No Indent : $idt->id_indent, No HP : $spk->no_hp, Tipe-Warna :$tipe_kendaraan->tipe_ahm-$warna->warna) telah tersedia",
								'id_dealer'       => $id_dealer,
								'created_by'      => $login_id
							 ];
				$upd_terima[] = ['id_penerimaan_unit_dealer_detail'=>$ns->id_penerimaan_unit_dealer_detail,
								'id_indent' => $id_indent,
								'no_spk'    => $idt->id_spk,
								'status_on_spk' => 'booking'
							  ];
				if ($idt->notif_sms_indent_status==NULL) {
					$ymd=date('Y-m-d');
					$pesan_sms = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Reminder Indent' AND id_dealer='$id_dealer' AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1");
					if ($pesan_sms->num_rows()>0) {
						$pesan  = $pesan_sms->row()->konten;
						$id_get = ['KodeIndent'=>$id_indent,
								   'NamaDealer'=> $id_dealer,
								   'TipeUnit'=> $spk->id_tipe_kendaraan,
								   'Warna'=> $spk->id_warna,
								   'NamaCustomer'=> $spk->nama_konsumen
								  ];
						$status = sms_zenziva($spk->no_hp, pesan($pesan, $id_get));
						if ($status['status']==0) {
							$notif_sms = 'SMS berhasil dikirim.';
						}else{
							$notif_sms = 'SMS gagal dikirim. Nomor tujuan tidak valid !';
						}
						$notif_sms_indent_status = $status['status'];
						$notif_sms_indent_at     = $waktu;
						$notif_sms_indent_by     = $login_id;
					}
				}
				$upd_indent[] = ['id_indent'=>$idt->id_indent,
								'status_monitoring' => 'close',
								'notif_sms_indent_status' => isset($notif_sms_indent_status)?$notif_sms_indent_status:NULL,
								'notif_sms_indent_at' => isset($notif_sms_indent_at)?$notif_sms_indent_at:NULL,
								'notif_sms_indent_by' => isset($notif_sms_indent_by)?$notif_sms_indent_by:NULL,
								'approved'     => $approved
							];
			}else{
				$_SESSION['pesan'] 	= "No Mesin Sudah Di Buat Indent !";
				$_SESSION['tipe'] 	= "danger";
				redirect('dealer/indent_fullfilment','refresh');
			}
			// exit;
			$this->db->trans_begin();
				if (isset($ins_manage)) {
					$this->db->insert('tr_manage_activity_after_dealing',$ins_manage);
				}
				$this->db->update_batch('tr_penerimaan_unit_dealer_detail',$upd_terima,'id_penerimaan_unit_dealer_detail');
				$this->db->update_batch('tr_po_dealer_indent',$upd_indent,'id_indent');
							
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something when Wrong";
				$_SESSION['tipe'] 	= "success";
				echo "<script>history.go(-1)</script>";
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();

	        	$_SESSION['pesan'] 	= "Data berhasil diproses. $notif_sms";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";
	      	}	
		}else{
			$_SESSION['pesan'] 	= "Data Not Found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";
		}
    }
   
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
	// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";
	// }

	// public function cetak()
	// {
	// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
	// 	$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$login_id  = $this->session->userdata('id_user');
	// 	$id_invoice = $this->input->get('id');				
  		
 //  		$get_data = $this->db->query("SELECT tr_spk.*,tr_invoice_dp.id_invoice_dp,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,tr_invoice_dp.created_at FROM tr_invoice_dp
 //   			JOIN tr_spk ON tr_invoice_dp.id_spk=tr_spk.no_spk
 //   			WHERE id_invoice_dp='$id_invoice' ");
 //  		if ($get_data->num_rows()>0) {
 //  			$row = $data['row'] = $get_data->row();
  			
 //  			$upd = ['print_ke'=> $row->print_ke+1,
 //  					'print_at'=> $waktu,
 //  					'print_by'=> $login_id,
 //  				   ];
 //  			$this->db->update('tr_invoice_dp',$upd,['id_invoice_dp'=>$id_invoice]);
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set'] = 'print';
	// 		$data['row'] = $row;
        	
 //        	$html = $this->load->view('dealer/indent_fullfilment_cetak', $data, true);
 //        	// render the view into HTML
	//         $mpdf->WriteHTML($html);
	//         // write the HTML into the mpdf
	//         $output = 'cetak_.pdf';
	//         $mpdf->Output("$output", 'I');	        
 //        }else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";		
 //        }
        
	// }

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_pesan = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_pesan WHERE id_pesan='$id_pesan'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";		
		}
		$this->template($data);	
	}

	public function edit()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'edit';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_indent     = $this->input->get('id');
		$row           = $this->db->query("SELECT * FROM tr_po_dealer_indent 
			JOIN tr_spk ON tr_po_dealer_indent.id_spk=tr_spk.no_spk
			WHERE id_indent='$id_indent' AND tr_po_dealer_indent.id_dealer=$id_dealer");
		if ($row->num_rows()>0) {
			$row =$data['row']     = $row->row();
			$data['dt_spk'] = $this->m_admin->getByID('tr_spk','no_spk',$row->id_spk);	
			if ($data['dt_spk']->num_rows()>0) {

				$row=$data['dt_spk']->row();

			}				
		$data['dt_agama']            = $this->m_admin->getSortCond("ms_agama","id_agama","ASC");				
		$data['dt_pekerjaan']        = $this->m_admin->getSortCond("ms_pekerjaan","id_pekerjaan","ASC");		
		$data['dt_pengeluaran']      = $this->m_admin->getSortCond("ms_pengeluaran_bulan","id_pengeluaran_bulan","ASC");
		$data['dt_tipe']             = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");		
		$data['dt_warna']            = $this->m_admin->getSortCond("ms_warna","warna","ASC");					
		$data['dt_customer']         = $this->m_admin->getSortCond("ms_customer","id_customer","nama","ASC");
		$data['dt_finance']          = $this->m_admin->getSortCond("ms_finance_company","finance_company","ASC");
		$data['dt_status_hp']        = $this->m_admin->getSortCond("ms_status_hp","status_hp","ASC");			
		$data['dt_prospek']          = $this->m_admin->getSortCond("tr_prospek","no_hp","id_tipe_kendaraan","alamat","ASC");
		$data['dt_pendidikan']       = $this->m_admin->getSortCond("ms_pendidikan","id_pendidikan","ASC");
		$data['dt_merk_sebelumnya']  = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");
		$data['dt_digunakan']        = $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");			
		$data['dt_hobi']             = $this->m_admin->getSortCond("ms_hobi","hobi","ASC");		
		$data['event']               = $this->db->query("SELECT * FROM ms_event ORDER BY created_at DESC");


		$id_dealer = $this->m_admin->cari_dealer();

		$data['dt_customer'] = $this->db->query("SELECT * FROM tr_prospek LEFT JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 

							LEFT JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 

							WHERE tr_prospek.id_dealer = '$id_dealer'

							ORDER BY tr_prospek.id_customer ASC");						

		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 

							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'

							ORDER BY tr_prospek_gc.nama_npwp ASC");	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";		
		}
		$this->template($data);	
	}

	// public function save_edit()
	// {		
	// 	$waktu     = gmdate("Y-m-d H:i:s", time()+60*60*7);
	// 	$tgl       = gmdate("Y-m-d", time()+60*60*7);
	// 	$login_id  = $this->session->userdata('id_user');
	// 	$id_dealer = $this->m_admin->cari_dealer();
	// 	$id_indent = $this->input->get('id');
	// 	// $indent = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_indent='$id_indent'");
	// 	// if ($indent->num_rows()>0) {
	// 	// 	$idt = $indent->row();
	// 	// 	$get_nosin = $this->db->query("SELECT tpudd.no_mesin,tpudd.id_penerimaan_unit_dealer_detail FROM tr_penerimaan_unit_dealer_detail AS tpudd
 //  //  				 JOIN tr_penerimaan_unit_dealer AS tpud ON tpudd.id_penerimaan_unit_dealer=tpud.id_penerimaan_unit_dealer
 //  //  				 JOIN tr_scan_barcode ON tpudd.no_mesin=tr_scan_barcode.no_mesin AND tr_scan_barcode.status=4
 //  //  				 WHERE po_indent='ya' 
 //  //  				 AND id_dealer='$id_dealer'  
 //  //  				 AND tipe_motor='$idt->id_tipe_kendaraan'  AND warna='$idt->id_warna' 
 //  //  				 AND tpudd.id_indent IS NULL
 //  //  				 ORDER BY id_penerimaan_unit_dealer_detail ASC LIMIT 1");
	// 	// 	if ($get_nosin->num_rows()>0) {
	// 	// 		$ns = $get_nosin->row();
	// 	// 		$spk = $this->db->get_where('tr_spk',['no_spk'=>$idt->id_spk])->row();
	// 	// 		$ins_manage = ['id_indent'=>$idt->id_indent,
	// 	// 					   'no_spk'=>$idt->id_spk,
	// 	// 					   'created_at' => $waktu,
	// 	// 					   'kategori' => 'indent',
	// 	// 					   'status'=> 'Not Started',
	// 	// 					   'detail_activity' => "Follow UP - Pesanan indent unit pelanggan $spk->nama_konsumen telah tersedia",
	// 	// 					   'id_dealer' => $id_dealer,
	// 	// 					   'created_by' => $login_id
	// 	// 					 ];
	// 	// 		$upd_terima[] = ['id_penerimaan_unit_dealer_detail'=>$ns->id_penerimaan_unit_dealer_detail,
	// 	// 					   'id_indent'=>$id_indent,
	// 	// 					   'no_spk' => $idt->id_spk
	// 	// 					  ];
	// 	// 	}else{
	// 	// 		$_SESSION['pesan'] 	= "No Mesin Sudah Di Buat Indent !";
	// 	// 	$_SESSION['tipe'] 	= "danger";
	// 	// 		redirect('dealer/indent_fullfilment','refresh');
	// 	// 	}
			
	// 		$this->db->trans_begin();
	// 			if (isset($ins_manage)) {
	// 				$this->db->insert('tr_manage_activity_after_dealing',$ins_manage);
	// 			}
	// 			$this->db->update_batch('tr_penerimaan_unit_dealer_detail',$upd_terima,'id_penerimaan_unit_dealer_detail');
							
	// 		if ($this->db->trans_status() === FALSE)
	//       	{
	// 			$this->db->trans_rollback();
	// 			$_SESSION['pesan'] 	= "Something when Wrong";
	// 			$_SESSION['tipe'] 	= "success";
	// 			echo "<script>history.go(-1)</script>";
	//       	}
	//       	else
	//       	{
	//         	$this->db->trans_commit();

	//         	$_SESSION['pesan'] 	= "Data has been saved successfully";
	// 			$_SESSION['tipe'] 	= "success";
	// 			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";
	//       	}	
	// 	}else{
	// 		$_SESSION['pesan'] 	= "Data Not Found !";
	// 		$_SESSION['tipe'] 	= "danger";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";
	// 	}
 //    }
	public function save_edit()
	{		

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

			

		$config['upload_path'] 			= './assets/panel/files/';

		$config['allowed_types'] 		= 'jpg|jpeg|png';

		$config['max_size']					= '100';				



		$config2['upload_path'] 		= './assets/panel/files/';

		$config2['allowed_types'] 		= 'jpg|jpeg|png';

		$config2['max_size']				= '500';



		$type_ktp1 		= $_FILES["file_foto"]["type"];

		$type_kk 			= $_FILES["file_kk"]["type"];

		$type_ktp2		= $_FILES["file_ktp_2"]["type"];



		$format_kk="";$format_ktp_2="";$format_foto="";

		$file_foto="";$file_kk="";$file_ktp_2="";

		if(isset($_POST['file_foto'])){

			if($type_ktp1 == 'image/jpeg' OR $type_ktp1 == 'image/png' OR $type_ktp1 == 'image/jpg'){

				$format_foto = "ok";

			}else{

				$format_foto = "salah";

			}

		}

		if(isset($_POST['file_kk'])){

			if($type_kk == 'image/jpeg' OR $type_kk == 'image/png' OR $type_kk == 'image/jpg'){

				$format_kk = "ok";

			}else{

				$format_kk = "salah";

			}

		}

		if(isset($_POST['file_ktp_2'])){

			if($type_ktp2 == 'image/jpeg' OR $type_ktp2 == 'image/png' OR $type_ktp2 == 'image/jpg'){

				$format_ktp_2 = "ok";

			}else{

				$format_ktp_2 = "salah";

			}

		}



		$tabel			= $this->tables;

		$pk 				= $this->pk;

		$id					= $this->input->post("id");

		$id_				= $this->input->post($pk);

		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();

		if($id == $id_){



			

			$this->upload->initialize($config);

			if($this->upload->do_upload('file_foto')){

				$da['file_foto']=$data['file_foto']=$this->upload->file_name;					

				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();							

			}else{

				$file_foto = "besar";

			}



			$this->upload->initialize($config2);

			if($this->upload->do_upload('file_kk')){

				$da['file_kk']=$data['file_kk']=$this->upload->file_name;					

				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();							

			}else{

				$file_kk = "besar";

			}



			$this->upload->initialize($config);

			if($this->upload->do_upload('file_ktp_2')){

				$da['file_ktp_2']=$data['file_ktp_2']=$this->upload->file_name;					

				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();							

			}else{

				$file_ktp_2 = "besar";

			}

						



			$isi_ktp = $this->input->post('no_ktp');

			$ktp = strlen($isi_ktp);

			if($ktp < 16){

				$jum = 16 - $ktp;

				for ($i=1; $i <= $jum; $i++) { 

					$r = $r."0";

				}

				$ktp_f = $r.$isi_ktp;

			}else{

				$ktp_f = $isi_ktp;

			}



			$r2 = "";			

			$isi_ktp2 = $this->input->post('no_ktp_penjamin');

			$ktp2 = strlen($isi_ktp2);

			if($ktp2 < 16){

				$jum = 16 - $ktp2;

				for ($i=1; $i <= $jum; $i++) { 

					$r2 = $r2."0";

				}

				$ktp_p = $r2.$isi_ktp2;

			}else{

				$ktp_p = $isi_ktp2;

			}



			$data['no_spk']=$da['no_spk'] 						= $this->input->post('no_spk');

			$data['tgl_spk']=$da['tgl_spk'] 						= $this->input->post('tgl_spk');	

			$data['id_customer']=$da['id_customer'] 				= $this->input->post('id_customer');				

			$data['nama_konsumen']=$da['nama_konsumen']				= $this->input->post('nama_konsumen');				

			$data['tempat_lahir']=$da['tempat_lahir'] 			= $this->input->post('tempat_lahir');	

			$data['tgl_lahir']=$da['tgl_lahir'] 					= $this->input->post('tgl_lahir');				

			$data['jenis_wn']=$da['jenis_wn'] 					= $this->input->post('jenis_wn');	

			$data['no_ktp']=$da['no_ktp'] 						= $ktp_f;

			$data['no_kk']=$da['no_kk'] 							= $this->input->post('no_kk');	

			$data['npwp']=$da['npwp'] 							= $this->input->post('npwp');				

			$data['id_kelurahan']=$da['id_kelurahan'] 			= $this->input->post('id_kelurahan');	

			$id_kelurahan 							= $this->input->post('id_kelurahan');	

			$region = explode("-",$this->m_admin->getRegion($id_kelurahan));             			

			$data['id_kecamatan']=$da['id_kecamatan'] 			= $region[1];

			$data['id_kabupaten']=$da['id_kabupaten'] 			= $region[2];

			$data['id_provinsi']=$da['id_provinsi'] 				= $region[3];

			$data['alamat']=$da['alamat'] 						= $this->input->post('alamat');	

			$data['kodepos']=$da['kodepos'] 						= $this->input->post('kodepos');			

			$d_lokasi = $this->input->post('denah_lokasi');				

			if($d_lokasi != ""){

				$p_lokasi = explode(',', $d_lokasi);			

				if (is_array($p_lokasi) AND count($p_lokasi) == 2) {

					$latitude = str_replace(' ', '', $p_lokasi[0]);

					$longitude = str_replace(' ', '', $p_lokasi[1]);					

					if($latitude != "" AND $longitude != ""){					

						$denah_lokasi 			= $d_lokasi;					

					}else{

						$denah_lokasi = "-1.613510, 103.594603";

					}

				}else{

					$denah_lokasi = "-1.613510, 103.594603";

				}				

			}else{

				$denah_lokasi = "-1.613510, 103.594603";

			}			

			$data['denah_lokasi']=$da['denah_lokasi'] = $denah_lokasi;

			$data['alamat_sama']=$da['alamat_sama']           = $this->input->post('tanya');	

			$data['id_kelurahan2']=$da['id_kelurahan2']         = $this->input->post('id_kelurahan2');	

			

			$id_kelurahan2 							= $this->input->post('id_kelurahan2');	

			$region = explode("-",$this->m_admin->getRegion($id_kelurahan2));             			

			

			$data['id_kecamatan2']=$da['id_kecamatan2']         = $region[1];

			$data['id_kabupaten2']=$da['id_kabupaten2']         = $region[2];

			$data['id_provinsi2']=$da['id_provinsi2']          = $region[3];

			$data['alamat2']=$da['alamat2']               = $this->input->post('alamat2');	

			$data['kodepos2']=$da['kodepos2']              = $this->input->post('kodepos2');	

			$data['status_rumah']=$da['status_rumah']          = $this->input->post('status_rumah');	

			$data['lama_tinggal']=$da['lama_tinggal']          = $this->input->post('lama_tinggal');			

			$data['pekerjaan']=$da['pekerjaan']             = $this->input->post('pekerjaan');	

			$data['lama_kerja']=$da['lama_kerja']            = $this->input->post('lama_kerja');	

			$data['jabatan']=$da['jabatan']               = $this->input->post('jabatan');	

			$data['pengeluaran_bulan']=$da['pengeluaran_bulan']     = $this->input->post('pengeluaran_bulan');

			$data['penghasilan']=$da['penghasilan']           = preg_replace('/[^0-9\  ]/', '', $this->input->post('penghasilan'));												

			$data['no_hp']=$da['no_hp']                 = $this->input->post('no_hp');	

			$data['status_hp']=$da['status_hp']             = $this->input->post('status_hp');	

			$data['no_hp_2']=$da['no_hp_2']               = $this->input->post('no_hp_2');	

			$data['status_hp_2']=$da['status_hp_2']           = $this->input->post('status_hp_2');	

			$data['no_telp']=$da['no_telp']               = $this->input->post('no_telp');	

			$data['email']=$da['email']                 = $this->input->post('email');	

			$data['refferal_id']=$da['refferal_id']           = $this->input->post('refferal_id');	

			$data['robd_id']=$da['robd_id']               = $this->input->post('robd_id');	

			$data['keterangan']=$da['keterangan']            = $this->input->post('keterangan');											

			$data['nama_ibu']=$da['nama_ibu']              = $this->input->post('nama_ibu');	

			$data['tgl_ibu']=$da['tgl_ibu']               = $this->input->post('tgl_ibu');																					

			$data['id_tipe_kendaraan']=$da['id_tipe_kendaraan']     = $this->input->post('id_tipe_kendaraan');	

			$data['id_warna']=$da['id_warna']              = $this->input->post('id_warna');	

			$data['harga']=$da['harga']                 = $this->input->post('harga');	

			$data['ppn']=$da['ppn']                   = $this->input->post('ppn');	

			$data['harga_off_road']=$da['harga_off_road']        = $this->input->post('harga_off');	

			$data['harga_on_road']=$da['harga_on_road']         = $this->input->post('harga_on');	

			$data['biaya_bbn']=$da['biaya_bbn']             = $this->input->post('biaya_bbn');	

			$jenis_beli            				 = $this->input->post('jenis_beli');

			$data['jenis_beli']=$da['jenis_beli']            = $jenis_beli;

			$data['the_road']=$da['the_road']              = $this->input->post('the_road');				

			$data['harga_tunai']=$da['harga_tunai']           = $this->input->post('harga_tunai');	

			$data['program_khusus_1']=$da['program_khusus_1']      = $this->input->post('program_khusus_1');

			$data['program_umum']=$da['program_umum']          = $this->input->post('program_umum');

			$data['voucher_1']=$da['voucher_1']             = $this->input->post('voucher_1');			

			$data['voucher_tambahan_1']=$da['voucher_tambahan_1']    = $this->input->post('voucher_tambahan_1');			

			$data['total_bayar']=$da['total_bayar']           = $this->input->post('total_bayar');	

			$id_finance_company = $data['id_finance_company']=$da['id_finance_company']    = $this->input->post('id_finance_company');	

			$uang_muka = $data['uang_muka']=$da['uang_muka']             = $this->input->post('uang_muka');	

			$data['program_khusus_2']=$da['program_khusus_2']      = $this->input->post('program_khusus_2');

			$data['voucher_2']=$da['voucher_2']             = $this->input->post('voucher_2');	

			$data['voucher_2']=$da['voucher_2']           	 = preg_replace('/[^0-9\  ]/', '', $this->input->post('nilai_voucher2'));												

			$data['voucher_tambahan_2']=$da['voucher_tambahan_2']    = $this->input->post('voucher_tambahan_2');			

			$tenor = $data['tenor']=$da['tenor']                 = $this->input->post('tenor');											

			$data['dp_stor']=$da['dp_stor']               = $this->input->post('dp_stor');	

			$angsuran = $data['angsuran']=$da['angsuran']              = $this->input->post('angsuran');

			$data['nama_penjamin']=$da['nama_penjamin'] = $nama_penjamin  = $this->input->post('nama_penjamin');	

			$data['hub_penjamin']=$da['hub_penjamin']          = $this->input->post('hub_penjamin');	

			$no_ktp_penjamin = $data['no_ktp_penjamin']=$da['no_ktp_penjamin']       = $ktp_p;

			$data['no_hp_penjamin']=$da['no_hp_penjamin']        = $this->input->post('no_hp_penjamin');	

			$data['alamat_penjamin']=$da['alamat_penjamin']       = $this->input->post('alamat_penjamin');	

			$data['tempat_lahir_penjamin']=$da['tempat_lahir_penjamin'] = $tempat_lahir_penjamin = $this->input->post('tempat_lahir_penjamin');	

			$data['tgl_lahir_penjamin']=$da['tgl_lahir_penjamin']   = $tgl_lahir_penjamin = $this->input->post('tgl_lahir_penjamin');	

			$da['pekerjaan_penjamin']=$data['pekerjaan_penjamin']    = $this->input->post('pekerjaan_penjamin');	

			$da['penghasilan_penjamin']=$data['penghasilan_penjamin']  = $this->input->post('penghasilan_penjamin');	

			$da['nama_bpkb']=$data['nama_bpkb']             = $this->input->post('nama_bpkb');						

			$da['id_dealer']=$data['id_dealer']             = $this->m_admin->cari_dealer();		

			$da['status_survey']=$data['status_survey']				 = "baru";	

			$da['updated_at']=$data['updated_at']            = $waktu;		

			$da['updated_by']=$data['updated_by']            = $login_id;	

			$da['created_at']            = $waktu;		

			$da['updated_by']            = $login_id;	

			

			

			if($format_kk == 'salah'){			

				$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($format_foto == 'salah'){			

				$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($format_ktp_2 == 'salah'){			

				$_SESSION['pesan'] 	= "Format file KTP/KK Penjamin yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($file_foto == 'gagal'){							

				$_SESSION['pesan'] 	= "Ukuran file KTP/KK yg diupload terlalu besar!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($file_kk == 'gagal'){			

				$_SESSION['pesan'] 	= "Ukuran file KTP/KK yg diupload terlalu besar!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($file_ktp_2 == 'gagal'){			

				$_SESSION['pesan'] 	= "Ukuran file KTP/KK Penjamin yg diupload terlalu besar!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($id_finance_company == '' AND $jenis_beli == 'Kredit'){			

				$_SESSION['pesan'] 	= "Tentukan dulu Finance Company!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($no_ktp_penjamin == '' AND $jenis_beli == 'Kredit'){			

				$_SESSION['pesan'] 	= "No KTP Penjamin harus diisi!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($nama_penjamin == '' AND $jenis_beli == 'Kredit'){			

				$_SESSION['pesan'] 	= "Nama Penjamin harus diisi!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($ktp < 16){			

				$_SESSION['pesan'] 	= "Panjang Karakter No KTP Pemohon tidak sesuai!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($ktp2 < 16 AND $jenis_beli == 'Kredit'){			

				$_SESSION['pesan'] 	= "Panjang Karakter No KTP Penjamin tidak sesuai!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($tempat_lahir_penjamin == '' AND $jenis_beli == 'Kredit'){			

				$_SESSION['pesan'] 	= "Tempat Lahir Penjamin harus diisi!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}elseif($tgl_lahir_penjamin == '' AND $jenis_beli == 'Kredit'){			

				$_SESSION['pesan'] 	= "Tgl Lahir Penjamin harus diisi!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";	

			}elseif($jenis_beli == 'Kredit' AND ($angsuran == '' OR $angsuran == 0 OR $tenor == '' OR $tenor == 0 OR $uang_muka == '' OR $uang_muka == 0)){			

				$_SESSION['pesan'] 	= "DP Gross, Tenor dan Angsuran harus diisi dan tidak boleh 0!";

				$_SESSION['tipe'] 	= "danger";

				$_SESSION['id_warna'] 	= $this->input->post("id_warna");

				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

				echo "<script>history.go(-1)</script>";				

			}else{			

				$spk = $this->m_admin->getByID("tr_spk","no_spk",$id)->row();

				if($jenis_beli=='Kredit'){					

					$da['no_order_survey'] 	= $this->m_admin->cari_id("tr_order_survey","no_order_survey");			

					$this->m_admin->insert("tr_order_survey",$da);					

				}

				$this->m_admin->update($tabel,$data,$pk,$id);

				$_SESSION['pesan'] 	= "Data has been updated successfully";

				$_SESSION['tipe'] 	= "success";

				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/spk'>";					

			}

		}else{

			$_SESSION['pesan'] 	= "Duplicate entry for primary key";

			$_SESSION['tipe'] 	= "danger";

			$_SESSION['id_warna'] 	= $this->input->post("id_warna");

			$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

			echo "<script>history.go(-1)</script>";

		}

	}

	public function approve()
	{
		$id_indent = $this->input->get('id');
		$indent = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_indent='$id_indent' AND approved='waiting_approval'");
		if ($indent->num_rows()>0) {
			$this->db->update('tr_po_dealer_indent',['approved'=>'approved'],['id_indent'=>$id_indent]);
			$_SESSION['pesan'] 	= "Data Succesfully Approved";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/indent_fullfilment'>";
		}
	}
}