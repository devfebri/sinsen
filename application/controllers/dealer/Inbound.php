<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbound extends CI_Controller {

	var $tables = "tr_inbound";	
	var $folder = "dealer";
	var $page   = "inbound";
	var $pk     = "no_sj_outbound";
	var $title  = "Inbound for Unit Return";

	public function __construct()
	{		
		parent::__construct();
		
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

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}


	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
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
		$data['set']	= "view";		
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_inbound'] = $this->db->query("SELECT *,tr_inbound.status FROM tr_inbound 
			LEFT JOIN ms_event ON ms_event.id_event=tr_inbound.id_event
			WHERE tr_inbound.id_dealer = '$id_dealer' ORDER BY tr_inbound.created_at DESC");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	
	public function add()
	{				
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;		
		$data['set']      = "form";					
		$data['mode']     = "insert";	
		$id_dealer  = $this->m_admin->cari_dealer();		
		$data['sj']   = $this->db->query("SELECT tr_mutasi.*,LEFT(print_sj_at,10) AS tgl_sj,ms_event.kode_event,ms_event.nama_event FROM tr_mutasi 
			LEFT JOIN ms_event ON ms_event.id_event=tr_mutasi.id_event
			WHERE no_sj IS NOT NULL AND tr_mutasi.id_dealer=$id_dealer ORDER BY no_sj DESC");					
		$this->template($data);										
	}
	function arrayKeObject($array)
	{
		$object = new stdClass();
		if (is_array($array))
		{
			foreach ($array as $kolom=>$isi)
			{
				$kolom = strtolower(trim($kolom));
				$object->$kolom = $isi;
			}
		}
		return $object;
	}
	public function getDetail()
	{
		$no_sj = $this->input->post('no_sj');
		$row      = $this->db->query("SELECT * FROM tr_mutasi WHERE no_sj='$no_sj'");				
		if ($row->num_rows()>0) {
			$row = $row->row();
			$id_mutasi = $row->id_mutasi;
			$details = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna
			 FROM tr_mutasi_detail 
			 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
			 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			 WHERE id_mutasi='$id_mutasi'")->result();
			foreach ($details as $key=> $dt) {
				$getKSU = $this->db->query("SELECT tr_mutasi_ksu_detail.*,(SELECT ksu FROM ms_ksu WHERE id_ksu=tr_mutasi_ksu_detail.id_ksu) as ksu FROM tr_mutasi_ksu_detail WHERE no_mesin='$dt->no_mesin' AND id_mutasi='$id_mutasi' AND cek='true'")->result();
				foreach ($getKSU as $ks) {
					$ksu[] = ['id_ksu'=>$ks->id_ksu,
							  'ksu'=> $ks->ksu,
							  'cek' => $ks->cek=='true'?1:0
							 ];
				}
				$part = ['id_part'=>'','qty_part'=>''];
				$details[$key] = ['no_mesin'=> $dt->no_mesin,
								'no_rangka' => $dt->no_rangka,
								'tipe_ahm'  => $dt->tipe_ahm,
								'warna'     => $dt->warna,
								'id_item'   => $dt->id_item,
								'ksu'       => isset($ksu)?$ksu:'',
								'alasan'    => '',
								'status'	=> '',
								'need_parts'=>'', 
								'part'      => $part,
								'parts'     => []
						];
				$ksu = array();
			}
			echo json_encode($details);													
		}
	}
	public function detail()
	{				
		$no_sj_outbound = $this->input->get('id');
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;		
		$data['set']      = "form";					
		$data['mode']     = "detail";			
		$id_dealer  = $this->m_admin->cari_dealer();		
		$data['sj']   = $this->db->query("SELECT tr_mutasi.*,LEFT(print_sj_at,10) AS tgl_sj,ms_event.kode_event,ms_event.nama_event FROM tr_mutasi 
			LEFT JOIN ms_event ON ms_event.id_event=tr_mutasi.id_event
			WHERE no_sj IS NOT NULL AND tr_mutasi.id_dealer=$id_dealer ORDER BY no_sj DESC");
		$row = $this->db->query("SELECT * FROM tr_inbound WHERE id_dealer='$id_dealer' AND no_sj_outbound='$no_sj_outbound'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
			$details = $this->db->query("SELECT tr_inbound_detail.*,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna FROM tr_inbound_detail
				JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_inbound_detail.no_mesin
				INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
				WHERE no_sj_outbound='$no_sj_outbound'")->result();
			foreach ($details as $key=> $dt) {
				$getKSU = $this->db->query("SELECT tr_inbound_ksu.*,(SELECT ksu FROM ms_ksu WHERE id_ksu=tr_inbound_ksu.id_ksu) as ksu FROM tr_inbound_ksu WHERE no_mesin='$dt->no_mesin' AND no_sj_outbound='$no_sj_outbound'")->result();
				foreach ($getKSU as $ks) {
					$ksu[] = ['id_ksu'=>$ks->id_ksu,
							  'ksu'=> $ks->ksu,
							  'cek' => $ks->cek
							 ];
				}
				$parts = array();
				$getPart = $this->db->query("SELECT tr_inbound_part.*,(SELECT nama_part FROM ms_part WHERE id_part=tr_inbound_part.id_part) as nama_part FROM tr_inbound_part WHERE no_mesin='$dt->no_mesin' AND no_sj_outbound='$no_sj_outbound'")->result();
				foreach ($getPart as $ks) {
					$parts[] = ['id_part'=>$ks->id_part,
							  'qty_part'=> $ks->qty_part
							 ];
				}
				$part = ['id_part'=>'','qty_part'=>''];
				$details[$key] = ['no_mesin'=> $dt->no_mesin,
								'no_rangka' => $dt->no_rangka,
								'tipe_ahm'  => $dt->tipe_ahm,
								'warna'     => $dt->warna,
								'id_item'   => $dt->id_item,
								'alasan'   => $dt->alasan,
								'status'   => $dt->status,
								'ksu'       => isset($ksu)?$ksu:'',
								'part'			=> $part,
								'parts'			=> $parts
						];
				$ksu = array();
			}
			$data['details'] = $details;
			$this->template($data);															
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/inbound'>";
		}				
	}

	public function close()
	{				
		$no_sj_outbound = $this->input->get('id');
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;		
		$data['set']      = "form";					
		$data['mode']     = "close";			
		$id_dealer  = $this->m_admin->cari_dealer();		
		$data['sj']   = $this->db->query("SELECT tr_mutasi.*,LEFT(print_sj_at,10) AS tgl_sj,ms_event.kode_event,ms_event.nama_event FROM tr_mutasi 
			LEFT JOIN ms_event ON ms_event.id_event=tr_mutasi.id_event
			WHERE no_sj IS NOT NULL AND tr_mutasi.id_dealer=$id_dealer ORDER BY no_sj DESC");
		$row = $this->db->query("SELECT * FROM tr_inbound WHERE id_dealer='$id_dealer' AND no_sj_outbound='$no_sj_outbound'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
			$details = $this->db->query("SELECT tr_inbound_detail.*,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna FROM tr_inbound_detail
				JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_inbound_detail.no_mesin
				INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
				WHERE no_sj_outbound='$no_sj_outbound'")->result();
			foreach ($details as $key=> $dt) {
				$getKSU = $this->db->query("SELECT tr_inbound_ksu.*,(SELECT ksu FROM ms_ksu WHERE id_ksu=tr_inbound_ksu.id_ksu) as ksu FROM tr_inbound_ksu WHERE no_mesin='$dt->no_mesin' AND no_sj_outbound='$no_sj_outbound'")->result();
				foreach ($getKSU as $ks) {
					$ksu[] = ['id_ksu'=>$ks->id_ksu,
							  'ksu'=> $ks->ksu,
							  'cek' => $ks->cek
							 ];
				}
				$parts = array();
				$getPart = $this->db->query("SELECT tr_inbound_part.*,(SELECT nama_part FROM ms_part WHERE id_part=tr_inbound_part.id_part) as nama_part FROM tr_inbound_part WHERE no_mesin='$dt->no_mesin' AND no_sj_outbound='$no_sj_outbound'")->result();
				foreach ($getPart as $ks) {
					$parts[] = ['id_part'=>$ks->id_part,
							  'qty_part'=> $ks->qty_part
							 ];
				}
				$part = ['id_part'=>'','qty_part'=>''];
				$details[$key] = ['no_mesin'=> $dt->no_mesin,
								'no_rangka' => $dt->no_rangka,
								'tipe_ahm'  => $dt->tipe_ahm,
								'warna'     => $dt->warna,
								'id_item'   => $dt->id_item,
								'alasan'   => $dt->alasan,
								'status'   => $dt->status,
								'ksu'       => isset($ksu)?$ksu:'',
								'part'			=> $part,
								'parts'			=> $parts
						];
				$ksu = array();
			}
			$data['details'] = $details;
			$this->template($data);															
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/inbound'>";
		}				
	}

	function get_last_dokumen_nrfs_id($dokumen_nrfs_id=null)
   	{
   		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();

   		if ($dokumen_nrfs_id==null) {
   			$get_data = $this->db->query("SELECT * FROM tr_dokumen_nrfs WHERE id_dealer=$id_dealer AND LEFT(tgl_dokumen,7)='$th_bln' ORDER BY dokumen_nrfs_id DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$new_kode = $get_data->row()->dokumen_nrfs_id;
	   		}else{
	   			$new_kode = 'kosong';
	   		}
   		}else{
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			if ($dokumen_nrfs_id=='kosong') {
				$new_kode = 'NRFS/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
			}else{
				$dokumen_nrfs_id = substr($dokumen_nrfs_id, -4);
				$new_kode        = 'NRFS/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$dokumen_nrfs_id+1);
			}
   		}
   		return $new_kode;
   	}
	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;
		$id       = $this->input->post($pk);
		$cek      = $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		$id_dealer = $this->m_admin->cari_dealer();

		if($cek == 0){
			$no_sj_outbound         = $this->input->post('no_sj_outbound');
			$get_mutasi             = $this->db->get_where('tr_mutasi',['no_sj'=>$no_sj_outbound])->row();
			
			$data['tgl_sj']         = $this->input->post('tgl_sj');
			$data['id_dealer']      = $id_dealer;
			$data['id_event']       = $get_mutasi->id_event;
			$data['gudang_asal']    = $this->input->post('gudang_asal');
			$data['gudang_tujuan']  = $this->input->post('gudang_tujuan');
			$data['no_sj_outbound'] = $no_sj_outbound;
			
			$data['status']         = 'input';						
			$data['created_at']     = $waktu;		
			$data['created_by']     = $login_id;

			$details          = $this->input->post('details');
			$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['kode_notif'=>'ntf_parts_nrfs'])->row();

			foreach ($details as $key => $val) {
				$dt_detail[] = ['no_sj_outbound'=> $no_sj_outbound,
								'no_mesin' => $val['no_mesin'],
								'alasan'   => $val['alasan'],
								'status'   => $val['status'],
						 	 ];
				foreach ($val['ksu'] as $ksu) {
					if ($ksu['cek']=='true' OR $ksu['cek']==1) {
						$cek=1;
					}else{
						$cek=0;
					}
					$dt_ksu[] = ['no_sj_outbound'=>$no_sj_outbound,
								'no_mesin' => $val['no_mesin'],
								'id_ksu'   => $ksu['id_ksu'],
								'cek'      => $cek
								];
				}
				$last_dokumen_nrfs_id = $this->get_last_dokumen_nrfs_id();
				if ($val['status']=='nrfs') {
					$dok_add_part ='';
					$last_dokumen_nrfs_id = $this->get_last_dokumen_nrfs_id($last_dokumen_nrfs_id);
					if (count($val['parts'])>0) {
						$notif_parts = array();
						foreach ($val['parts'] as $prt) {
							$dt_part[] = ['no_sj_outbound'=>$no_sj_outbound,
										'no_mesin' => $val['no_mesin'],
										'id_part'  => $prt['id_part'],
										'qty_part' => $prt['qty_part'],
										];
							$get_part = $this->db->get_where('ms_part',['id_part'=>$prt['id_part']])->row();
							$dok_add_part[]=['dokumen_nrfs_id'=>$last_dokumen_nrfs_id,
												'id_part'  =>$prt['id_part'],
												'nama_part'=>$get_part->nama_part,
												'qty_part' =>$prt['qty_part']
												];
							$notif_parts[] = $prt['id_part'].'('.$prt['qty_part'].')';
						}
						if ($dok_add_part!='') {
							$this->db->insert_batch('tr_dokumen_nrfs_part',$dok_add_part);
						}
					}
					$no_mesin   = $val['no_mesin'];
					$penerimaan = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer WHERE no_mesin='$no_mesin'")->row();
					$r          = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();
					$tipe       = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$r->tipe_motor])->row();
					$wrn        = $this->db->get_where('ms_warna',['id_warna'=>$r->warna])->row();
					$dokumen    =['dokumen_nrfs_id'=> $last_dokumen_nrfs_id,
									'tgl_dokumen'     => date('Y-m-d'),
									'id_dealer'       => $id_dealer,
									'no_shiping_list' => $penerimaan->no_surat_jalan,
									'type_code'       => $r->tipe_motor,
									'deskripsi_unit'  => $tipe->tipe_ahm,
									'color_code'      => $wrn->id_warna,
									'deskripsi_warna' => $wrn->warna,
									'no_mesin'        => $no_mesin,
									'no_rangka'       => $r->no_rangka,
									// 'need_parts'      => $dok_add_part!=''?'Yes':'No',
									'need_parts'      => $val['need_parts'],
									'sumber_rfs_nrfs' => 'dealer',
									'status_nrfs'     => 'NRFS',
									'dokumen_nrfs_from'=>'inbound',
									'created_at'      => $waktu,
									'created_by'      => $login_id,
									'status'		  => 'open'
								   ];
					$this->db->insert('tr_dokumen_nrfs',$dokumen);	
					if (count($notif_parts)>0) {
						$parts = implode(', ', $notif_parts);
						$pesan = "Telah terjadi perubahan unit RFS ke NRFS dengan detail: <br> 
								  Kode Tipe Unit = $r->tipe_motor <br>
								  Kode Warna = $wrn->id_warna <br>
								  No Mesin = $no_mesin <br>
								  No Rangka = $r->no_rangka <br>
								  Parts = $parts <br>
								  Mohon untuk melakukan pengecekan ketersediaan Parts didalam sistem
								";
						$notifikasi_parts[] = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
								'id_referensi' => $last_dokumen_nrfs_id,
								'judul'        => "Notifikasi Kebutuhan Parts",
								'pesan'        => $pesan,
								'link'         => $ktg_notif->link.'?id='.$last_dokumen_nrfs_id,
								'status'       =>'baru',
								// 'id_dealer'    => $id_dealer,
								'created_at'   => $waktu,
								'created_by'   => $login_id
							 ];
					}
				}
			}

			$this->db->trans_begin();
				$this->db->insert('tr_inbound',$data);
				$this->db->insert_batch('tr_inbound_detail',$dt_detail);
				if (isset($dt_ksu)) {
					$this->db->insert_batch('tr_inbound_ksu',$dt_ksu);
				}
				if (isset($dt_part)) {
					$this->db->insert_batch('tr_inbound_part',$dt_part);
				}
				if (isset($notifikasi_parts)) {
					$this->db->insert_batch('tr_notifikasi',$notifikasi_parts);
				}
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$rsp = ['status'=> 'error',
						'pesan'=> ' Something went wrong'
					   ];
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
	        	$rsp = ['status'=> 'sukses',
						'link'=>base_url('dealer/inbound')
					   ];
	        	$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
	      	}
	      	echo json_encode($rsp);
		}else{
			// $_SESSION['pesan'] 	= "Duplicate entry for primary key";
			// $_SESSION['tipe'] 	= "danger";
			// echo "<script>history.go(-1)</script>";
			$rsp = ['status'=> 'gagal',
					'pesan'=> 'No surat jalan ini sudah ada !',
					'link'=>base_url('dealer/inbound')
				   ];
	      	echo json_encode($rsp);
		}
	}

	public function save_close()
	{
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id       = $this->session->userdata('id_user');
		
		$no_sj_outbound = $this->input->post('no_sj_outbound');

		$upd_inbound = ['status'=>'close',
						 'close_at' => $waktu,
						 'close_by' => $login_id
				];
		// $dt_mutasi = ['status_mutasi'=>'close',
		// 			  'closed_at' => $waktu,
		// 		 	  'closed_by' => $login_id
		// 			 ];
		$this->db->trans_begin();
			$this->db->update('tr_inbound',$upd_inbound,['no_sj_outbound'=>$no_sj_outbound]);
			// $this->db->update('tr_mutasi',$dt_mutasi,['no_sj'=>$no_sj_outbound]);
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
      	}
      	else
      	{
        	$this->db->trans_commit();
			$rsp = ['status'=> 'sukses',
					'link'=>base_url('dealer/inbound')
				   ];
			$_SESSION['pesan'] = "Data has been closed successfully";
			$_SESSION['tipe']  = "success";
		}
	    echo json_encode($rsp);
	}
	public function konfirmasi_transfer()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$id_mutasi= $this->input->get('id');
		$cek = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'open'])->num_rows();
		if ($cek>0) {
			$data['status_mutasi'] = 'intransit';						
			$data['confirm_at']    = $waktu;		
			$data['confirm_by']    = $login_id;	

			$this->db->update('tr_mutasi',$data,['id_mutasi'=>$id_mutasi]);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";
		}else{
			$_SESSION['pesan'] 	= "Data sudah diproses";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";
		}
	}

	// public function print_list_unit_trf(){
	// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
	// 	$waktu     = gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id  = $this->session->userdata('id_user');
	// 	$id_mutasi = $this->input->get('id');				
  
 //  		$get_data = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit']);
 //  		if ($get_data->num_rows()>0) {
 //  			$row = $get_data->row();
  
 //  			$upd = ['print_list_ke'=> $row->print_list_ke+1,
 //  					'print_list_at'=> $waktu,
 //  					'print_list_by'=> $login_id,
 //  				   ];
 //  			$this->db->update('tr_mutasi',$upd,['id_mutasi'=>$id_mutasi]);
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set'] = 'list_unit_trf';
	// 		$data['row'] = $row;
        	
 //        	$html = $this->load->view('dealer/mutasi_stok_cetak', $data, true);
 //        	// render the view into HTML
	//         $mpdf->WriteHTML($html);
	//         // write the HTML into the mpdf
	//         $output = 'cetak_.pdf';
	//         $mpdf->Output("$output", 'I');	        
 //        }else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";		
 //        }
        
	// }

	// public function get_sj()
	// {
	// 	$th        = date('Y');
	// 	$bln       = date('m');
	// 	$th_bln    = date('Y-m');
	// 	$thbln     = date('ym');
	// 	$id_dealer = $this->m_admin->cari_dealer();
	// 	$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
	// 	$get_data  = $this->db->query("SELECT * FROM tr_mutasi
	// 		WHERE id_dealer='$id_dealer'
	// 		AND LEFT(created_at,7)='$th_bln'
	// 		AND no_sj IS NOT NULL
	// 		ORDER BY created_at DESC LIMIT 0,1");
	//    		if ($get_data->num_rows()>0) {
	// 			$row      = $get_data->row();
	// 			$no_sj    = substr($row->no_sj, -4);
	// 			$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
	// 			$i=0;
	// 			while ($i<1) {
	// 				$cek = $this->db->get_where('tr_mutasi',['no_sj'=>$new_kode])->num_rows();
	// 			    if ($cek>0) {
	// 			    	$no_sj    = substr($new_kode, -4);
	// 					$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
	// 			    	$i=0;
	// 			    }else{
	// 			    	$i++;
	// 			    }
	// 			}
	//    		}else{
	// 			$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	//    		}
 //   		return strtoupper($new_kode);
	// }

	// public function print_sj(){
	// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
	// 	$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$login_id  = $this->session->userdata('id_user');
	// 	$id_mutasi = $this->input->get('id');				
  
 //  		$get_data = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event']);
 //  		if ($get_data->num_rows()>0) {
 //  			$row = $get_data->row();
 //  			$no_sj = $row->no_sj;
 //  			if ($row->no_sj==null)$no_sj=$this->get_sj();

 //  			$upd = ['print_sj_ke'=> $row->print_sj_ke+1,
 //  					'print_sj_at'=> $waktu,
 //  					'print_sj_by'=> $login_id,
 //  					'no_sj' => $no_sj
 //  				   ];

 //  			$this->db->update('tr_mutasi',$upd,['id_mutasi'=>$id_mutasi]);
			
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set']    = 'print_sj';
	// 		$row            = $data['row'] = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event'])->row();
	// 		$data['event']  = $this->db->get_where('ms_event',['id_event'=>$row->id_event])->row();
	// 		$data['dealer'] = $this->db->get_where('ms_dealer',['id_dealer'=>$row->id_dealer])->row()->nama_dealer;
 //        	$data['details'] = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna,close,id_mutasi_detail
	// 		 FROM tr_mutasi_detail 
	// 		 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
	// 		 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
 //            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 //            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
	// 		 WHERE id_mutasi='$id_mutasi'")->result();

 //        	$html = $this->load->view('dealer/mutasi_stok_cetak', $data, true);
 //        	// render the view into HTML
	//         $mpdf->WriteHTML($html);
	//         // write the HTML into the mpdf
	//         $output = 'cetak_.pdf';
	//         $mpdf->Output("$output", 'I');	        
 //        }else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";		
 //        }
        
	// }
}