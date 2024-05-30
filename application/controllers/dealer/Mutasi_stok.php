<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_stok extends CI_Controller {

	var $tables =   "tr_mutasi";	
	var $folder =   "dealer";
	var $page   =		"mutasi_stok";
	var $pk     =   "id_mutasi";
	var $title  =   "Mutasi Stok Dealer Pos";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
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
		$data['dt_mutasi'] = $this->db->query("SELECT * FROM tr_mutasi WHERE id_dealer = '$id_dealer' ORDER BY tgl_mutasi ASC");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	
	public function add()
	{				
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;		
		$data['set']      = "form";					
		$data['mode']     = "insert";			
		$data['event']   = $this->db->get('ms_event');		
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$this->template($data);										
	}

	public function detail()
	{				
		$id_mutasi = $this->input->get('id');
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;		
		$data['set']      = "form";					
		$data['mode']     = "detail";			
		$data['event']   = $this->db->get('ms_event');		
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");
		$row      = $this->db->query("SELECT * FROM tr_mutasi WHERE id_mutasi='$id_mutasi'");				
		if ($row->num_rows()>0) {
			$data['hdr'] = $row->row();
			$details = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna
			 FROM tr_mutasi_detail 
			 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
			 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			 WHERE id_mutasi='$id_mutasi'")->result();
			foreach ($details as $key=> $dt) {
				$getKSU = $this->db->query("SELECT tr_mutasi_ksu_detail.*,(SELECT ksu FROM ms_ksu WHERE id_ksu=tr_mutasi_ksu_detail.id_ksu) as ksu FROM tr_mutasi_ksu_detail WHERE no_mesin='$dt->no_mesin' AND id_mutasi='$id_mutasi'")->result();
				foreach ($getKSU as $ks) {
					$ksu[] = ['id_ksu'=>$ks->id_ksu,
							  'ksu'=> $ks->ksu,
							  'cek' => $ks->cek=='true'?1:0
							 ];
				}

				$details[$key] = ['no_mesin'=> $dt->no_mesin,
								'no_rangka' => $dt->no_rangka,
								'tipe_ahm'  => $dt->tipe_ahm,
								'warna'     => $dt->warna,
								'id_item'   => $dt->id_item,
								'ksu'       => isset($ksu)?$ksu:''
						];
				$ksu = array();
			}
			$data['details'] = $details;
			$this->template($data);															
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";
		}				
	}

	public function t_data(){
		$id 			= $this->input->post('id_mutasi');		
		$data['dt_data'] = $this->db->query("SELECT tr_mutasi_detail.*,tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_mutasi_detail INNER JOIN tr_scan_barcode ON tr_mutasi_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_mutasi_detail.id_mutasi = '$id'");		 					
		$data['mode'] = "new";
		$this->load->view('dealer/t_mutasi',$data);				
	}

	public function cari_id(){			
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$id_dealer  = $this->m_admin->cari_dealer();
		$pr_num 			= $this->db->query("SELECT * FROM tr_mutasi WHERE id_dealer = '$id_dealer' ORDER BY id_mutasi DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_mutasi)-11;
			$id 	= substr($row->id_mutasi,$pan,11)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."0000".$id;          
		    }elseif($id > 9 && $id <= 99){
				$kode1 = $th.$bln.$tgl."000".$id;                    
		    }elseif($id > 99 && $id <= 999){
				$kode1 = $th.$bln.$tgl."00".$id;          					          
		    }elseif($id > 999){
				$kode1 = $th.$bln.$tgl."0".$id;                    
		    }
			$kode = $id_dealer.$kode1;
		}else{
			$kode = $id_dealer.$th.$bln.$tgl."00001";
		} 	
		
		echo $kode;
	}
	public function cek_data()
	{		
		$no_mesin	= $this->input->post('no_mesin');	
		$dt_eks		= $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_scan_barcode 
            INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$no_mesin'");									
		if($dt_eks->num_rows() > 0){
			$r = $dt_eks->row();
			$rsp['status']    = 'ok';
			$rsp['no_mesin']  = $r->no_mesin;
			$rsp['no_rangka'] = $r->no_rangka;
			$rsp['tipe_ahm']  = $r->tipe_ahm;
			$rsp['warna']     = $r->warna;
			$rsp['id_item']   = $r->id_item;
			$getKSU = $this->db->query("SELECT ms_koneksi_ksu_detail.id_ksu,(SELECT ksu FROM ms_ksu WHERE id_ksu=ms_koneksi_ksu_detail.id_ksu) as ksu, 'true' AS cek FROM `ms_koneksi_ksu_detail` JOIN ms_koneksi_ksu ON
					ms_koneksi_ksu_detail.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
					WHERE id_tipe_kendaraan='$r->tipe_motor'");
			$rsp['ksu'] = $getKSU->result();
			// echo "ok|".$r->no_mesin."|".$r->no_rangka."|".$r->tipe_ahm."|".$r->warna."|".$r->id_item;
		}else{
			$rsp['status'] ='Data tidak tersedia';
		}
		echo json_encode($rsp);
	}

	public function save_data(){
		$id_mutasi		= $this->input->post('id_mutasi');			
		$no_mesin			= $this->input->post('no_mesin');			
		$data['id_mutasi']		= $this->input->post('id_mutasi');			
		$data['no_mesin']			= $this->input->post('no_mesin');
		$c = $this->db->query("SELECT * FROM tr_mutasi_detail WHERE id_mutasi = '$id_mutasi' AND no_mesin = '$no_mesin'");
		if($c->num_rows() > 0){
			$r = $c->row();
			$cek2 = $this->m_admin->update("tr_mutasi_detail",$data,"id_mutasi_detail",$r->id_mutasi_detail);						
		}else{
			$cek2 = $this->m_admin->insert("tr_mutasi_detail",$data);									
		}					
		echo "nihil";
	}	
	public function delete_data(){
		$id = $this->input->post('id_mutasi_detail');		
		$this->db->query("DELETE FROM tr_mutasi_detail WHERE id_mutasi_detail = '$id'");			
		echo "nihil";
	}	

	public function get_id_mutasi()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('y/m');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		$get_data  = $this->db->query("SELECT * FROM tr_mutasi
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$th_bln'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$id_old   = substr($row->id_mutasi, -4);
				$new_kode = $dealer->kode_dealer_md.'/TRF/'.$thbln.'/'.sprintf("%'.04d",$id_old+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('tr_mutasi',['id_mutasi'=>$new_kode])->num_rows();
				    if ($cek>0) {
				    	$id_old    = substr($new_kode, -4);
						$new_kode = $dealer->kode_dealer_md.'/TRF/'.$thbln.'/'.sprintf("%'.04d",$id_old+1);
				    	$i=0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = $dealer->kode_dealer_md.'/TRF/'.$thbln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}	
	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;
		$id       = $this->get_id_mutasi();
		$cek      = $this->m_admin->getByID($tabel,$pk,$id)->num_rows();

		if($cek == 0){
			$tipe_stok_trf         = $this->input->post('tipe_stok_trf');
			$id_mutasi = $data['id_mutasi']     = $id;
			$data['tgl_mutasi']    = $tgl;
			$id_dealer = $data['id_dealer']     = $this->m_admin->cari_dealer();	
			$data['keterangan']    = $this->input->post('keterangan');
			$data['tipe_stok_trf'] = $tipe_stok_trf;

			$asal_mutasi         = explode('|', $this->input->post('asal_mutasi'));	
			$data['asal_mutasi'] = $asal_mutasi[0];
			if ($tipe_stok_trf=='event') {
				$data['id_event'] = $this->input->post('id_event');
				$event            = $this->db->get_where('ms_event',['id_event'=>$data['id_event']])->row();
				$tujuan           = $event->nama_event;
				$status           ='open';
			}else{
				$asal_mutasi         = explode('|', $this->input->post('asal_mutasi'));	
				$data['asal_mutasi'] = $asal_mutasi[0];
				$tujuan              = $data['tujuan_mutasi'] = $this->input->post('tujuan_mutasi');	
				$id_dealer           = $this->m_admin->cari_dealer();
				$id_gudang           = $asal_mutasi[1];
				$dt_gudang           = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1 AND id_gudang_dealer='$id_gudang'");
	            if ($dt_gudang->num_rows()>0) {
	            	$gd = $dt_gudang->row();
	        		if ($gd->jenis=='POS') {
	        			$status = 'approve';
	        		}elseif ($gd->jenis=='Dealer') {
	        			$status='open';
	        		}else{
	        			$status='open';
	        		}
	        	}	
	           	else{
	            	$status='open';
	           	}
			}
			$data['status_mutasi'] = $status;						
			$data['created_at']    = $waktu;		
			$data['created_by']    = $login_id;

			$details          = $this->input->post('details');
			foreach ($details as $key => $val) {
				$dt_detail[] = ['id_mutasi'=> $id_mutasi,
						  	  	'no_mesin' => $val['no_mesin']
						 	 ];
				foreach ($val['ksu'] as $ksu) {
					$dt_ksu[] = ['id_mutasi'=>$id_mutasi,
								'no_mesin' => $val['no_mesin'],
								'id_ksu'   => $ksu['id_ksu'],
								'cek'      => $ksu['cek']
								];
				}
			}
			$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>9])->row();
			$get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>9]);
			$email          = array();
			foreach ($get_notif_grup->result() as $rd) {
				$get_email = $this->db->query("SELECT email FROM ms_karyawan_dealer 
				WHERE id_karyawan_dealer IN(
					SELECT id_karyawan_dealer FROM ms_user 
					WHERE active=1 
					AND id_user_group=(
						SELECT id_user_group FROM ms_user_group 
						WHERE code='$rd->code_user_group'
					)
				)
				AND id_dealer=$id_dealer
				")->result();
				foreach ($get_email as $usr) {
					$email[] = $usr->email;
				}
			}
			$asal_mutasi = $asal_mutasi[0];
			$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
						'id_referensi' => $id_mutasi,
						'judul'        => "Outbound Form. ID Type = $id_mutasi",
						'pesan'        => "Outbound form $id_mutasi dibuat oleh [Sales Admin] untuk transfer unit dari $asal_mutasi ke $tujuan",
						'link'         => $ktg_notif->link.'/detail?id='.$id_mutasi,
						'status'       =>'baru',
						'id_dealer'    => $id_dealer,
						'created_at'   => $waktu,
						'created_by'   => $login_id
					 ];

			$this->db->trans_begin();
				$this->m_admin->insert($tabel,$data);
				$this->db->insert_batch('tr_mutasi_detail',$dt_detail);
				$this->db->insert_batch('tr_mutasi_ksu_detail',$dt_ksu);
				$this->db->insert('tr_notifikasi',$notif);
				$this->email_notif_mutasi($email,$id_mutasi,$asal_mutasi,$tujuan);
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$rsp = ['status'=> 'error',
						'pesan'=> ' Something went wrong'
					   ];
				// $_SESSION['pesan'] 	= "Something went wrong";
				// $_SESSION['tipe'] 	= "danger";
				// echo "<script>history.go(-1)</script>";
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
	        	$rsp = ['status'=> 'sukses',
						'link'=>base_url('dealer/mutasi_stok')
					   ];
	        	$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
	      	}
	      	echo json_encode($rsp);
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function email_notif_mutasi($email,$id_mutasi,$asal,$tujuan) { 
		$from = $this->db->get_where('ms_email_md',['email_for'=>'notification'])->row(); 
		$to_email   = $email; 

		$config = Array(
          'protocol'  => 'smtp',
          'smtp_host' => 'ssl://mail.monju.id',
          'smtp_port' => 465,
          'smtp_user' => $from->email,
          'smtp_pass' => $from->pass,
          'mailtype'  => 'html', 
          'charset'   => 'iso-8859-1');
        // $config = config_email($from_email);

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");   

		$this->email->from($from->email, 'SINARSENTOSA'); 
		$this->email->to($to_email);
		$this->email->subject('[SINARSENTOSA] Transfer Posting'); 
		$file_logo         = base_url('assets/panel/images/logo_sinsen.jpg');
		$data['logo']      = $file_logo;
		$data['id_mutasi'] = $id_mutasi;
		$data['asal']      = $asal;
		$data['tujuan']    = $tujuan;
		$this->email->message($this->load->view('dealer/mutasi_stok_email', $data, true)); 

         //Send mail 
         if($this->email->send()){
			return 'sukses';
         }else {
			return 'gagal';
         } 
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

	public function close()
	{				
		$id_mutasi = $this->input->get('id');
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;		
		$data['set']      = "form";					
		$data['mode']     = "close";			
		$data['event']   = $this->db->get('ms_event');		
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");
		$row      = $this->db->query("SELECT * FROM tr_mutasi WHERE id_mutasi='$id_mutasi' AND status_mutasi='intransit'");				
		if ($row->num_rows()>0) {
			$row = $data['hdr'] = $row->row();
			//Cek Jenis Transfer
			if ($row->tipe_stok_trf=='event') {
				$cek_inbound = $this->db->get_where('tr_inbound',['no_sj_outbound'=>$row->no_sj,'status'=>'close'])->num_rows();
				if ($cek_inbound==0) {
					$_SESSION['pesan'] 	= "Belum ada Inbound for Unit Return untuk mutasi stok ini !";
					$_SESSION['tipe'] 	= "danger";
					redirect('dealer/mutasi_stok','refresh');
				}
			}
			$details = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna,close,id_mutasi_detail
			 FROM tr_mutasi_detail 
			 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
			 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			 WHERE id_mutasi='$id_mutasi'")->result();
			foreach ($details as $key=> $dt) {
				$details[$key] = ['id_mutasi_detail'=>$dt->id_mutasi_detail,
								'no_mesin'  => $dt->no_mesin,
								'no_rangka' => $dt->no_rangka,
								'tipe_ahm'  => $dt->tipe_ahm,
								'warna'     => $dt->warna,
								'id_item'   => $dt->id_item,
								'close'     => (int)$dt->close
						];
			}
			$data['details'] = $details;
			$this->template($data);															
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";
		}				
	}	

	public function save_close()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;
		$id       = $this->input->post($pk);
		$mutasi      = $this->m_admin->getByID($tabel,$pk,$id)->row();

		$details          = $this->input->post('details');
		$cek_semua_close  = 0;
		if (count($details)>0) {
			foreach ($details as $key => $val) {
				if($val['close']=='true' || $val['close']==1){$cek = 1; }
				else{ $cek=0; $cek_semua_close++; }

				$dt_detail[] = ['id_mutasi_detail' => $val['id_mutasi_detail'],
						  	  	'close' => $cek
						 	   ];
			}
		}
		$status = $cek_semua_close==0?'close':'intransit';
		$data['status_mutasi'] = $status;						
		$data['closed_at']     = $waktu;		
		$data['closed_by']     = $login_id;
		$inbound = $this->db->get_where('tr_inbound',['no_sj_outbound'=>$mutasi->no_sj,'status'=>'close']);
		if ($inbound->num_rows()>0) {
			$upd_inbound = ['status'=>'close',
						 'close_at' => $waktu,
						 'close_by' => $login_id
						];
		}
		$this->db->trans_begin();
			$this->db->update('tr_mutasi',$data,['id_mutasi'=>$id]);
			if (isset($dt_detail)) {
				$this->db->update_batch('tr_mutasi_detail',$dt_detail,'id_mutasi_detail');
			}
			if (isset($upd_inbound)) {
				$this->db->update('tr_inbound',$upd_inbound,['no_sj_outbound'=>$mutasi->no_sj]);
			}
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
			// $_SESSION['pesan'] 	= "Something went wrong";
			// $_SESSION['tipe'] 	= "danger";
			// echo "<script>history.go(-1)</script>";
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('dealer/mutasi_stok')
				   ];
        	$_SESSION['pesan'] 	= "Data has been closed successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	public function print_list_unit_trf(){
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_mutasi = $this->input->get('id');				
  
  		$get_data = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit']);
  		if ($get_data->num_rows()>0) {
  			$row = $get_data->row();
  
  			$upd = ['print_list_ke'=> $row->print_list_ke+1,
  					'print_list_at'=> $waktu,
  					'print_list_by'=> $login_id,
  				   ];
  			$this->db->update('tr_mutasi',$upd,['id_mutasi'=>$id_mutasi]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'list_unit_trf';
			$data['row'] = $row;

			$data['dealer'] = $this->db->get_where('ms_dealer',['id_dealer'=>$row->id_dealer])->row()->nama_dealer;
        	$data['details'] = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna,close,id_mutasi_detail
			 FROM tr_mutasi_detail 
			 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
			 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			 WHERE id_mutasi='$id_mutasi'")->result();        	
        	$html = $this->load->view('dealer/mutasi_stok_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";		
        }
        
	}
	public function get_sj()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		$get_data  = $this->db->query("SELECT * FROM tr_mutasi
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$th_bln'
			AND no_sj IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$no_sj    = substr($row->no_sj, -4);
				$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('tr_mutasi',['no_sj'=>$new_kode])->num_rows();
				    if ($cek>0) {
				    	$no_sj    = substr($new_kode, -4);
						$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
				    	$i=0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}

	public function print_sj(){
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_mutasi = $this->input->get('id');				
  
  		$get_data = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event']);
  		if ($get_data->num_rows()>0) {
  			$row = $get_data->row();
  			$no_sj = $row->no_sj;
  			if ($row->no_sj==null)$no_sj=$this->get_sj();

  			$upd = ['print_sj_ke'=> $row->print_sj_ke+1,
  					'print_sj_at'=> $waktu,
  					'print_sj_by'=> $login_id,
  					'no_sj' => $no_sj
  				   ];

  			$this->db->update('tr_mutasi',$upd,['id_mutasi'=>$id_mutasi]);
			
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set']    = 'print_sj';
			$row            = $data['row'] = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event'])->row();
			$data['event']  = $this->db->get_where('ms_event',['id_event'=>$row->id_event])->row();
			$data['dealer'] = $this->db->get_where('ms_dealer',['id_dealer'=>$row->id_dealer])->row()->nama_dealer;
        	$data['details'] = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna,close,id_mutasi_detail
			 FROM tr_mutasi_detail 
			 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
			 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			 WHERE id_mutasi='$id_mutasi'")->result();

        	$html = $this->load->view('dealer/mutasi_stok_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";		
        }
        
	}	
}