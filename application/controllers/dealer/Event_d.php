<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_d extends CI_Controller {

	var $tables = "ms_event";	
	var $folder = "dealer";
	var $page   = "event_d";
	var $title  = "Master Event";
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
		$data['event']    = $this->db->query("SELECT * FROM ms_event JOIN ms_jenis_event ON ms_event.id_jenis_event=ms_jenis_event.id_jenis_event 
			WHERE (id_dealer='$id_dealer' OR sumber='E20')
			ORDER BY created_at DESC");					
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
	
	public function get_kode_event($tipe)
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		// $id_dealer = $this->m_admin->cari_dealer();
		$id_sumber='E20';
		// if ($id_dealer!=null) {
		// 	$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		// 	$id_sumber = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM ms_event
			WHERE LEFT(created_at,7)='$th_bln' 
			AND kode_event IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$kode_event = substr($row->kode_event, -4);
				$new_kode   = 'EV/'.$tipe.'/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.04d",$kode_event+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('ms_event',['kode_event'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -4);
						$new_kode = 'EV/'.$tipe.'/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.04d",$neww+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode   = 'EV/'.$tipe.'/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}

	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		$id_jenis_event               = $data['id_jenis_event'] = $this->input->post('id_jenis_event');
		$getjenis                     = $this->db->get_where('ms_jenis_event',['id_jenis_event'=>$id_jenis_event])->row();
		$kode_event = $data['kode_event']           = $this->get_kode_event($getjenis->jenis_event);
		$data['nama_event']           = $this->input->post('nama_event');
		$data['revenue_event_target'] = $this->input->post('revenue_event_target');
		$data['start_date']           = $this->input->post('start_date');
		$data['end_date']             = $this->input->post('end_date');
		$data['description']          = $this->input->post('description');
		$data['unit_event_target']    = $this->input->post('unit_event_target');
		$data['location']             = $this->input->post('location');
		$data['tipe']                 = $getjenis->jenis_event;
		$data['sumber']               = $id_dealer;
		$data['id_dealer']               = $id_dealer;
		
		$status = strtolower($getjenis->require_md_approval)=='yes'?'waiting_approval':'approved';
		$data['status']               = $status;						
		$data['created_at']           = $waktu;		
		$data['created_by']           = $login_id;

		$dealers          = $this->input->post('dealers');
		foreach ($dealers as $key => $val) {
			$dt_dealer[] = ['kode_event'=> $kode_event,
							'id_dealer' => $val['id_dealer']
					 	 ];	
		}

		$budgets          = $this->input->post('budgets');
		foreach ($budgets as $key => $val) {
			$dt_budget[] = ['kode_event'=> $kode_event,
							'kategori' => $val['kategori'],
							'nama'     => $val['nama'],
							'nominal'  => $val['nominal']
					 	 ];	
		}

		$units          = $this->input->post('units');
		foreach ($units as $key => $val) {
			$dt_unit[] = ['kode_event'=> $kode_event,
							'id_tipe_kendaraan' => $val['id_tipe_kendaraan'],
							'id_warna'     => $val['id_warna']
					 	 ];	
		}

		$karyawans          = $this->input->post('karyawans');
		foreach ($karyawans as $key => $val) {
			$dt_karyawan[] = ['kode_event'=> $kode_event,
							'id_karyawan_dealer' => $val['id_karyawan_dealer']
					 	 ];	
		}

		$parts          = $this->input->post('parts');
		foreach ($parts as $key => $val) {
			$dt_part[] = ['kode_event'=> $kode_event,
						  'id_part'  => $val['id_part'],
						  'qty_part' => $val['qty_part']
					 	 ];	
		}

		$jobs          = $this->input->post('jobs');
		foreach ($jobs as $key => $val) {
			$dt_job[] = ['kode_event'=> $kode_event,
						 'id_jasa_servis'  => $val['id_jasa_servis']
					 	 ];	
		}
		if ($status=='waiting_approval') {
			$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>12])->row();
			$get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>12]);
			$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
						'id_referensi' => $kode_event,
						'judul'        => "Event Baru Dari Dealer",
						'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
						'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
						'status'       =>'baru',
						'created_at'   => $waktu,
						'created_by'   => $login_id
					 ];
			$email = array();
				foreach ($get_notif_grup->result() as $rd) {
					$get_email = $this->db->query("SELECT email FROM ms_karyawan 
					WHERE id_karyawan IN(
						SELECT id_karyawan_dealer FROM ms_user 
						WHERE jenis_user='Main Dealer' 
						AND active=1 
						AND id_user_group=(
							SELECT id_user_group FROM ms_user_group 
							WHERE code='$rd->code_user_group'
						)
					)")->result();
					foreach ($get_email as $usr) {
						$email[] = $usr->email;
					}
				}
		}
		$this->db->trans_begin();
			$this->db->insert('ms_event',$data);
			if (isset($notif)) {
				$this->db->insert('tr_notifikasi',$notif);
			}
			if (isset($dt_dealer)) {
				$this->db->insert_batch('ms_event_dealer',$dt_dealer);
			}
			if (isset($dt_budget)) {
				$this->db->insert_batch('ms_event_budget',$dt_budget);
			}
			if (isset($dt_unit)) {
				$this->db->insert_batch('ms_event_unit_display',$dt_unit);
			}
			if (isset($dt_karyawan)) {
				$this->db->insert_batch('ms_event_karyawan',$dt_karyawan);
			}
			if (isset($dt_part)) {
				$this->db->insert_batch('ms_event_part',$dt_part);
			}
			if (isset($dt_job)) {
				$this->db->insert_batch('ms_event_job',$dt_job);
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
        	if (isset($notif)) {
        		$this->email_event($email,$kode_event);
        	}
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('dealer/event_d')
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
          'smtp_host' => 'ssl://mail.sinarsentosaprimatama.com',
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
		$id_event = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;		
		$data['mode']      = 'detail';
		$data['set']       = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$data['dealer']    = $this->db->get('ms_dealer');
		$data['jenis']     = $this->db->query("SELECT * FROM ms_jenis_event ORDER BY jenis_event DESC");	
		$row    = $this->db->query("SELECT * FROM ms_event JOIN ms_jenis_event ON ms_event.id_jenis_event=ms_jenis_event.id_jenis_event WHERE ms_event.id_event=$id_event");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$kode_event = $row->kode_event;
			$data['dealers'] = $this->db->query("SELECT ms_event_dealer.*,nama_dealer FROM ms_event_dealer LEFT JOIN ms_dealer ON ms_dealer.id_dealer=ms_event_dealer.id_dealer WHERE kode_event='$kode_event'")->result();
			$data['budgets'] = $this->db->query("SELECT * FROM ms_event_budget WHERE kode_event='$kode_event'")->result();
			$data['units'] = $this->db->query("SELECT ms_event_unit_display.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_event_unit_display
											   JOIN ms_tipe_kendaraan ON ms_event_unit_display.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
											   JOIN ms_warna ON ms_warna.id_warna=ms_event_unit_display.id_warna
											   WHERE kode_event='$kode_event'")->result();
			$data['karyawans'] = $this->db->query("SELECT ms_event_karyawan.*,nama_lengkap,jabatan FROM ms_event_karyawan 
										JOIN ms_karyawan_dealer ON ms_event_karyawan.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
										JOIN ms_jabatan ON ms_jabatan.id_jabatan=ms_karyawan_dealer.id_jabatan
										WHERE kode_event='$kode_event'")->result();
			$data['parts'] = $this->db->query("SELECT ms_event_part.*,nama_part FROM ms_event_part LEFT JOIN ms_part ON ms_part.id_part=ms_event_part.id_part WHERE kode_event='$kode_event'")->result();
			$data['jobs'] = $this->db->query("SELECT ms_event_job.*,nama_jasa FROM ms_event_job LEFT JOIN ms_jasa_servis ON ms_jasa_servis.id_jasa_servis=ms_event_job.id_jasa_servis WHERE kode_event='$kode_event'")->result();

			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/event_d'>";
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
		$id              = $this->input->get('id');
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'edit';
		$data['dealer']  = $this->db->get('ms_dealer');
		$data['row'] 	 = $this->db->get_where('ms_event',['id_event'=>$id])->row();
		$data['set']     = "form";									
		$this->template($data);	
	}

	public function save_edit()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

			$id_event = $data['id_event'] = $this->input->post('id_event');
			$data['nama_event'] = $this->input->post('nama_event');
			$data['sumber']     = $this->input->post('sumber');
			$id_dealer = $data['id_dealer']  = isset($_POST['id_dealer'])?$_POST['id_dealer']:null;
			$tipe      = $data['tipe']       = $this->input->post('tipe',$id_dealer);
			$data['kode_event'] = $this->get_kode_event($tipe, $id_dealer);
			$data['updated_at'] = $waktu;		
			$data['updated_by'] = $login_id;

			$this->db->trans_begin();
				$this->db->update('ms_event',$data,['id_event'=>$id_event]);
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
	        	$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/event_d'>";			
	      	}							
	}

	public function fetch_part()
   {
		$fetch_data = $this->make_datatables();  
		$data = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array   = array();
			$sub_array[] = $rs->id_part;
			$sub_array[] = $rs->nama_part;
			$sub_array[] = $rs->kelompok_vendor;
			$row         = json_encode($rs);
			$link        ='<button data-dismiss=\'modal\' onClick=\'return pilihPart('.$row.')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   

   function make_query()  
   {  
     $this->db->select('*');  
     $this->db->from('ms_part');
     // $this->db->join('ms_link', 'ms_link.kode_btn = ms_link.kode_btn');

     $search = $this->input->post('search')['value'];
	  if ($search!='') {
	      $searchs = "(id_part LIKE '%$search%' 
	          OR nama_part LIKE '%$search%'
	      )";
	      $this->db->where("$searchs", NULL, false);
	  }
     if(isset($_POST["order"]))  
     {  
          $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
     }  
     else  
     {  
          $this->db->order_by('id_part', 'ASC');  
     }  
   }  
   function make_datatables(){  
		$this->make_query();  
		if($_POST["length"] != -1)  
		{  
			$this->db->limit($_POST['length'], $_POST['start']);  
		}  
		$query = $this->db->get();  
		return $query->result();  
   }  
   function get_filtered_data(){  
		$this->make_query();  
		$query = $this->db->get();  
		return $query->num_rows();  
   }  
}