<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon extends CI_Controller {

	var $tables = "ms_diskon";	
	var $folder = "dealer";
	var $page   = "diskon";
	var $title  = "Master Diskon";
   	// var $order_column_part = array("id_part","nama_part",'kelompok_vendor',null); 

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
		$this->load->library('upload');
		$this->load->library('form_validation');

	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
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
		$id_dealer = $this->m_admin->cari_dealer();
		
		$data['event']    = $this->db->query("SELECT * FROM ms_diskon WHERE id_dealer='$id_dealer' ORDER BY created_at DESC");					
		$this->template($data);	
	}

	public function add()
	{				
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$data['dealer']  = $this->db->get('ms_dealer');
		$data['jenis']    = $this->db->query("SELECT * FROM ms_jenis_event ORDER BY jenis_event DESC");				
		$this->template($data);	
	}
	
	public function get_id_diskon()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$id_dealer = $this->m_admin->cari_dealer();
		// $id_sumber='E20';
		// if ($id_dealer!=null) {
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$id_sumber = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM ms_diskon
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			AND id_diskon IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$id_diskon = substr($row->id_diskon, -5);
				$new_kode   = $id_sumber.'/'.$th_kecil.'/'.$bln.'/DISC/'.sprintf("%'.05d",$id_diskon+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('ms_diskon',['id_diskon'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -5);
						$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/DISC/'.sprintf("%'.05d",$id_diskon+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/DISC/00001';
	   		}
   		return strtoupper($new_kode);
	}

	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		$id_diskon = $data['id_diskon'] = $this->get_id_diskon();
		$data['jatah_approval'] = $this->input->post('jatah_approval');
		$data['tipe_diskon'] = $this->input->post('tipe_diskon');
		$data['byk_jatah']      = $this->input->post('byk_jatah');
		$data['start_date']     = $this->input->post('start_date');
		$data['end_date']       = $this->input->post('end_date');
		$data['value']          = $this->input->post('value');
		$data['id_dealer']      = $id_dealer;
		$data['created_at']     = $waktu;		
		$data['created_by']     = $login_id;

		$units          = $this->input->post('units');
		foreach ($units as $key => $val) {
			$dt_unit[] = ['id_diskon'=> $id_diskon,
							'id_tipe_kendaraan' => $val['id_tipe_kendaraan'],
							'id_warna'     => $val['id_warna']
					 	 ];	
		}

		$karyawans          = $this->input->post('karyawans');
		foreach ($karyawans as $key => $val) {
			$dt_karyawan[] = ['id_diskon'=> $id_diskon,
							'id_karyawan_dealer' => $val['id_karyawan_dealer']
					 	 ];	
		}

		// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
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

		// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 			'id_referensi' => $kode_event,
		// 			'judul'        => "Event Baru Dari Dealer",
		// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 			'status'       =>'baru',
		// 			'created_at'   => $waktu,
		// 			'created_by'   => $login_id
		// 		 ];
		$this->db->trans_begin();
			$this->db->insert('ms_diskon',$data);
			// $this->db->insert('tr_notifikasi',$notif);
			
			if (isset($dt_unit)) {
				$this->db->insert_batch('ms_diskon_kendaraan',$dt_unit);
			}
			if (isset($dt_karyawan)) {
				$this->db->insert_batch('ms_diskon_assignment',$dt_karyawan);
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
        	// $this->email_event($email,$kode_event);
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('dealer/diskon')
				   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	public function email_event($email,$kode_event) { 
		$from = $this->db->get_where('ms_email_md',['email_for'=>'notification'])->row(); 
		$to_email   = $email; 

		$config = Array(
          'protocol'  => 'smtp',
          'smtp_host' => 'mail.sinarsentosaprimatama.com',
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
		$this->email->subject('[SINARSENTOSA] Approve Event Dealer'); 
		$file_logo         = base_url('assets/panel/images/logo_sinsen.jpg');
		$data['logo']      = $file_logo;
		$data['kode_event'] = $kode_event;
		$this->email->message($this->load->view('dealer/event_email', $data, true)); 

         //Send mail 
         if($this->email->send()){
			return 'sukses';
         }else {
			return 'gagal';
         } 
	}

	public function detail()
	{				
		$id_diskon = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;		
		$data['mode']      = 'detail';
		$data['set']       = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$data['dealer']    = $this->db->get('ms_dealer');
		$id_dealer = $this->m_admin->cari_dealer();
		$row    = $this->db->query("SELECT * FROM ms_diskon WHERE id_diskon='$id_diskon' AND id_dealer=$id_dealer");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$id_diskon = $row->id_diskon;
			$data['units'] = $this->db->query("SELECT ms_diskon_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_diskon_kendaraan
											   JOIN ms_tipe_kendaraan ON ms_diskon_kendaraan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
											   JOIN ms_warna ON ms_warna.id_warna=ms_diskon_kendaraan.id_warna
											   WHERE id_diskon='$id_diskon'")->result();
			$data['karyawans'] = $this->db->query("SELECT ms_diskon_assignment.*,nama_lengkap,jabatan FROM ms_diskon_assignment 
										JOIN ms_karyawan_dealer ON ms_diskon_assignment.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
										JOIN ms_jabatan ON ms_jabatan.id_jabatan=ms_karyawan_dealer.id_jabatan
										WHERE id_diskon='$id_diskon'")->result();
			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/diskon'>";
		}
	}

	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= 'id_event';
		$id 			= $this->input->get('id');		
		$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
		$this->db->trans_commit();			
		$result = 'Success';									

		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/event_d'>";
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function edit()
	{				
		$id_diskon = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;		
		$data['mode']      = 'edit';
		$data['set']       = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$data['dealer']    = $this->db->get('ms_dealer');
		$id_dealer = $this->m_admin->cari_dealer();
		$row    = $this->db->query("SELECT * FROM ms_diskon WHERE id_diskon='$id_diskon' AND id_dealer=$id_dealer");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$id_diskon = $row->id_diskon;
			$data['units'] = $this->db->query("SELECT ms_diskon_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_diskon_kendaraan
											   JOIN ms_tipe_kendaraan ON ms_diskon_kendaraan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
											   JOIN ms_warna ON ms_warna.id_warna=ms_diskon_kendaraan.id_warna
											   WHERE id_diskon='$id_diskon'")->result();
			$data['karyawans'] = $this->db->query("SELECT ms_diskon_assignment.*,nama_lengkap,jabatan FROM ms_diskon_assignment 
										JOIN ms_karyawan_dealer ON ms_diskon_assignment.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
										JOIN ms_jabatan ON ms_jabatan.id_jabatan=ms_karyawan_dealer.id_jabatan
										WHERE id_diskon='$id_diskon'")->result();
			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/event_d'>";
		}
	}

	public function save_edit()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		$id_diskon = $data['id_diskon'] =$this->input->post('id_diskon');
		$data['jatah_approval'] = $this->input->post('jatah_approval');
		$data['tipe_diskon'] = $this->input->post('tipe_diskon');
		
		$data['byk_jatah']      = $this->input->post('byk_jatah');
		$data['start_date']     = $this->input->post('start_date');
		$data['end_date']       = $this->input->post('end_date');
		$data['value']          = $this->input->post('value');
		$data['id_dealer']      = $id_dealer;
		$data['updated_at']     = $waktu;		
		$data['updated_by']     = $login_id;

		$units          = $this->input->post('units');
		foreach ($units as $key => $val) {
			$dt_unit[] = ['id_diskon'=> $id_diskon,
							'id_tipe_kendaraan' => $val['id_tipe_kendaraan'],
							'id_warna'     => $val['id_warna']
					 	 ];	
		}

		$karyawans          = $this->input->post('karyawans');
		foreach ($karyawans as $key => $val) {
			$dt_karyawan[] = ['id_diskon'=> $id_diskon,
							'id_karyawan_dealer' => $val['id_karyawan_dealer']
					 	 ];	
		}

		// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
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

		// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 			'id_referensi' => $kode_event,
		// 			'judul'        => "Event Baru Dari Dealer",
		// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 			'status'       =>'baru',
		// 			'created_at'   => $waktu,
		// 			'created_by'   => $login_id
		// 		 ];
		$this->db->trans_begin();
			$this->db->update('ms_diskon',$data,['id_diskon'=>$id_diskon]);
			// $this->db->insert('tr_notifikasi',$notif);
			$this->db->delete('ms_diskon_kendaraan',['id_diskon'=>$id_diskon]);
			$this->db->delete('ms_diskon_assignment',['id_diskon'=>$id_diskon]);
			if (isset($dt_unit)) {
				$this->db->insert_batch('ms_diskon_kendaraan',$dt_unit);
			}
			if (isset($dt_karyawan)) {
				$this->db->insert_batch('ms_diskon_assignment',$dt_karyawan);
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
        	// $this->email_event($email,$kode_event);
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('dealer/diskon')
				   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}
}