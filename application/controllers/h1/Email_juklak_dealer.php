<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_juklak_dealer extends CI_Controller {

    var $tables =   "ms_send_to_email";	
	var $folder =   "h1";
	var $page	=   "email_juklak_dealer";
    var $pk     =   "id";
    var $title  =   "Email Juklak Dealer";

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
		$data['set']		= "view";		
		$data['count']      =  $this->db->query("SELECT id FROM ms_cc_email WHERE module = 'auto_claim' and active='1'")->num_rows();
		$data['count_email_dealer']      =  $this->db->query("SELECT id FROM ms_send_to_email WHERE module = 'auto_claim' and active='1'")->num_rows();
		$data['batch_satu']      =  $this->db->query("SELECT count(DISTINCT (email)) as jumlah  FROM ms_send_to_email WHERE module = 'auto_claim' and active='1' and batch= '1'")->row()->jumlah;
		$data['batch_dua']       =  $this->db->query("SELECT count(DISTINCT (email)) as jumlah  FROM ms_send_to_email WHERE module = 'auto_claim' and active='1' and batch= '2'")->row()->jumlah;
		$data['batch_tiga']      =  $this->db->query("SELECT count(DISTINCT (email)) as jumlah  FROM ms_send_to_email WHERE module = 'auto_claim' and active='1' and batch= '3'")->row()->jumlah;
		$data['batch_empat']     =  $this->db->query("SELECT count(DISTINCT (email)) as jumlah  FROM ms_send_to_email WHERE module = 'auto_claim' and active='1' and batch= '4'")->row()->jumlah;
		$data['batch_lima']      =  $this->db->query("SELECT count(DISTINCT (email)) as jumlah  FROM ms_send_to_email WHERE module = 'auto_claim' and active='1' and batch= '5'")->row()->jumlah;
		$data['batch_enam']      =  $this->db->query("SELECT count(DISTINCT (email)) as jumlah  FROM ms_send_to_email WHERE module = 'auto_claim' and active='1' and batch= '6'")->row()->jumlah;
		$data['dt_dealer']       =  $this->db->query("SELECT DISTINCT (ms_dealer.nama_dealer), ms_dealer.kode_dealer_md , ms_dealer.id_dealer, ms_send_to_email.batch, ms_send_to_email.id_dealer  FROM `ms_send_to_email` inner join ms_dealer on ms_send_to_email.id_dealer=ms_dealer.id_dealer where ms_send_to_email.module = 'auto_claim'");						
		$this->template($data);			
	}


	public function email_juklak()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$id_dealer 		= $this->input->get("id");	
		$data['set']	   = "detail";				
		$data['dt_dealer_show'] = $this->db->query("SELECT id,email,id_dealer,active from ms_send_to_email WHERE id_dealer='$id_dealer'");	
		$data['dt_dealer_header'] = $this->db->query("SELECT nama_dealer,kode_dealer_md,id_dealer  from ms_dealer WHERE id_dealer='$id_dealer' limit 1")->row();	
		$this->template($data);			
	}


	public function add()
	{				
		$tabel			= $this->tables;
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$email 		= $this->input->post("email_juklak");	
		$dealer 	= $this->input->post("dealer");	
		$status 		= 1;	

		$hasil = $this->db->query("SELECT email FROM $tabel WHERE email='$email' and id_dealer='$dealer'")->row();

		if (!empty($hasil)) {
		$_SESSION['pesan'] 		= "Email has been used in dealer";
		$_SESSION['tipe'] 		= "danger";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer/email_juklak?id=$dealer'>";
		  }else if (empty($hasil)) {
		
			$params = [
            'email' =>$email,
			'nickname' => NULL,
            'id_dealer' => $dealer,
            'module' => 'auto_claim',
            'active' => $status,
        	];

			$this->db->insert('ms_send_to_email', $params);
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer/email_juklak?id=$dealer'>";
		  }
	
	}


	public function edit()
	{		
		$tabel			  = $this->tables;
		$pk 			  = 'id';		
		$status			  = $this->input->post('status_active');
		$datanya['email'] = $this->input->post('email_juklak');
		$emailcheck 	  = $datanya['email'] ;
		$idpost	          = $this->input->post("id_email");	
		$redirect		  = $this->input->post('dealer');
		if ($status == 'on') {
			$datanya['active']= 1;
		}else {
			$datanya['active']= $status;
		}

		$hasil = $this->db->query("SELECT email FROM $tabel WHERE email='$emailcheck' and id_dealer='$redirect'")->row();

		if (!empty($hasil)) {

			$data['dt_email'] = $this->m_admin->update($tabel, $datanya, $pk, $idpost);
			$data['isi']      = $this->page;		
			$data['title']    = $this->title;	
			$data['set']	  = "detail";	
			
			$_SESSION['pesan'] 		= "Data has been update successfully";
			$_SESSION['tipe'] 		= "success";
			
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer/email_juklak?id=$redirect'>";		
			

		}else if (empty($hasil)) {

			$data['dt_email'] = $this->m_admin->update($tabel, $datanya, $pk, $idpost);
			$data['isi']      = $this->page;		
			$data['title']    = $this->title;	
			$data['set']	  = "detail";	
			
			$_SESSION['pesan'] 		= "Data has been update with new Email";
			$_SESSION['tipe'] 		= "success";
			
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer/email_juklak?id=$redirect'>";		
		}
	}

	public function setbatch()
	{		
		$tabel			  = $this->tables;
		$pk				  = 'id_delaer';
		$idpost			  = $this->input->post('dealer_id_email');
		$batch			   = $this->input->post('batch');

		$data = array(
			'batch' => $batch,
		);
	
		$this->db->where('id_dealer', $idpost);
		$this->db->update($tabel, $data);
		
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;	
		$data['set']	  = "detail";	
		
		$_SESSION['pesan'] 		= "Data has been update successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer'>";						
	}

	public function reset()
	{		
		$tabel			  = $this->tables;

		$this->db->query("Update $tabel	 set batch = NULL");
		
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;	
		$data['set']	  = "detail";	
		
		$_SESSION['pesan'] 		= "Data has been Reset successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer'>";						
	}



	public function detail()
	{				
		$data['isi']    		    =  $this->page;		
		$data['title']			    = "Detail ".$this->title;	
		$id_mutasi 					= $this->input->get("id");	
		$data['set']				= "detail";
		$data['dt_data'] = $this->db->query("SELECT tr_mutasi_detail.*,tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_mutasi_detail INNER JOIN tr_scan_barcode ON tr_mutasi_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_mutasi_detail.id_mutasi = '$id_mutasi'");	
		$data['dt_isi']			= $this->db->query("SELECT * FROM tr_mutasi WHERE id_mutasi = '$id_mutasi'");										 					
		$this->template($data);			
	}	


	public function delete()
	{		
		$tabel			    = $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$redirect 				= $this->input->get('dealer');


		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{		
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer/email_juklak?id=$redirect'>";
		}
	}

	public function send_email_to_dealer($id_program = null)
	{
		$cfg  = $this->db->get('setup_smtp_email')->row();
        $from = $this->db->get_where('ms_email_md', array('email_for' => 'auto_claim'))->row();

        $config = array(
            'protocol' 	=> 'smtp',
			'smtp_host' => 'mail.sinarsentosaprimatama.com',
			'smtp_port' => '587',
            'smtp_user' => $from->email,
            'smtp_pass' => $from->pass,
            'mailtype'  => 'html',
			'smtp_crypto' => 'none',
			'_encoding' => 'base64',
            'charset'   => 'iso-8859-1'
        );
		
		// get data sales program 
		$id_program = '085/SSP.HD/SCP.MKT/IX/2022';
		// $id_program = '034/SSP.HD/SCP.MKT/IV/2022';
		$jmlh_batch = 1;
		$batch = 1; // mesti diganti atau di parsing parameter
		$email_per_batch = 31; // hanya email to saja, dengan catatan tidak boleh lebih dari 50 email per batch (perjumlahan antara email to dan cc)
		$data = array();
		$id = '[No Juklak MD]';
		$list_to_email = array();
		$temp_list_email = array();
		$list_cc_mail = array();
		$data['header'] = array();
		$data['detail'] = array();

		$filename = 'sample.pdf';
		$b64 ="JVBERi0xLjMNCiXi48/TDQoNCjEgMCBvYmoNCjw8DQovVHlwZSAvQ2F0YWxvZw0KL091dGxpbmVzIDIgMCBSDQovUGFnZXMgMyAwIFINCj4+DQplbmRvYmoNCg0KMiAwIG9iag0KPDwNCi9UeXBlIC9PdXRsaW5lcw0KL0NvdW50IDANCj4+DQplbmRvYmoNCg0KMyAwIG9iag0KPDwNCi9UeXBlIC9QYWdlcw0KL0NvdW50IDINCi9LaWRzIFsgNCAwIFIgNiAwIFIgXSANCj4+DQplbmRvYmoNCg0KNCAwIG9iag0KPDwNCi9UeXBlIC9QYWdlDQovUGFyZW50IDMgMCBSDQovUmVzb3VyY2VzIDw8DQovRm9udCA8PA0KL0YxIDkgMCBSIA0KPj4NCi9Qcm9jU2V0IDggMCBSDQo+Pg0KL01lZGlhQm94IFswIDAgNjEyLjAwMDAgNzkyLjAwMDBdDQovQ29udGVudHMgNSAwIFINCj4+DQplbmRvYmoNCg0KNSAwIG9iag0KPDwgL0xlbmd0aCAxMDc0ID4+DQpzdHJlYW0NCjIgSg0KQlQNCjAgMCAwIHJnDQovRjEgMDAyNyBUZg0KNTcuMzc1MCA3MjIuMjgwMCBUZA0KKCBBIFNpbXBsZSBQREYgRmlsZSApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY4OC42MDgwIFRkDQooIFRoaXMgaXMgYSBzbWFsbCBkZW1vbnN0cmF0aW9uIC5wZGYgZmlsZSAtICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjY0LjcwNDAgVGQNCigganVzdCBmb3IgdXNlIGluIHRoZSBWaXJ0dWFsIE1lY2hhbmljcyB0dXRvcmlhbHMuIE1vcmUgdGV4dC4gQW5kIG1vcmUgKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NTIuNzUyMCBUZA0KKCB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDYyOC44NDgwIFRkDQooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjE2Ljg5NjAgVGQNCiggdGV4dC4gQW5kIG1vcmUgdGV4dC4gQm9yaW5nLCB6enp6ei4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNjA0Ljk0NDAgVGQNCiggbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDU5Mi45OTIwIFRkDQooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuICkgVGoNCkVUDQpCVA0KL0YxIDAwMTAgVGYNCjY5LjI1MDAgNTY5LjA4ODAgVGQNCiggQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA1NTcuMTM2MCBUZA0KKCB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBFdmVuIG1vcmUuIENvbnRpbnVlZCBvbiBwYWdlIDIgLi4uKSBUag0KRVQNCmVuZHN0cmVhbQ0KZW5kb2JqDQoNCjYgMCBvYmoNCjw8DQovVHlwZSAvUGFnZQ0KL1BhcmVudCAzIDAgUg0KL1Jlc291cmNlcyA8PA0KL0ZvbnQgPDwNCi9GMSA5IDAgUiANCj4+DQovUHJvY1NldCA4IDAgUg0KPj4NCi9NZWRpYUJveCBbMCAwIDYxMi4wMDAwIDc5Mi4wMDAwXQ0KL0NvbnRlbnRzIDcgMCBSDQo+Pg0KZW5kb2JqDQoNCjcgMCBvYmoNCjw8IC9MZW5ndGggNjc2ID4+DQpzdHJlYW0NCjIgSg0KQlQNCjAgMCAwIHJnDQovRjEgMDAyNyBUZg0KNTcuMzc1MCA3MjIuMjgwMCBUZA0KKCBTaW1wbGUgUERGIEZpbGUgMiApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY4OC42MDgwIFRkDQooIC4uLmNvbnRpbnVlZCBmcm9tIHBhZ2UgMS4gWWV0IG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NzYuNjU2MCBUZA0KKCBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY2NC43MDQwIFRkDQooIHRleHQuIE9oLCBob3cgYm9yaW5nIHR5cGluZyB0aGlzIHN0dWZmLiBCdXQgbm90IGFzIGJvcmluZyBhcyB3YXRjaGluZyApIFRqDQpFVA0KQlQNCi9GMSAwMDEwIFRmDQo2OS4yNTAwIDY1Mi43NTIwIFRkDQooIHBhaW50IGRyeS4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gQW5kIG1vcmUgdGV4dC4gKSBUag0KRVQNCkJUDQovRjEgMDAxMCBUZg0KNjkuMjUwMCA2NDAuODAwMCBUZA0KKCBCb3JpbmcuICBNb3JlLCBhIGxpdHRsZSBtb3JlIHRleHQuIFRoZSBlbmQsIGFuZCBqdXN0IGFzIHdlbGwuICkgVGoNCkVUDQplbmRzdHJlYW0NCmVuZG9iag0KDQo4IDAgb2JqDQpbL1BERiAvVGV4dF0NCmVuZG9iag0KDQo5IDAgb2JqDQo8PA0KL1R5cGUgL0ZvbnQNCi9TdWJ0eXBlIC9UeXBlMQ0KL05hbWUgL0YxDQovQmFzZUZvbnQgL0hlbHZldGljYQ0KL0VuY29kaW5nIC9XaW5BbnNpRW5jb2RpbmcNCj4+DQplbmRvYmoNCg0KMTAgMCBvYmoNCjw8DQovQ3JlYXRvciAoUmF2ZSBcKGh0dHA6Ly93d3cubmV2cm9uYS5jb20vcmF2ZVwpKQ0KL1Byb2R1Y2VyIChOZXZyb25hIERlc2lnbnMpDQovQ3JlYXRpb25EYXRlIChEOjIwMDYwMzAxMDcyODI2KQ0KPj4NCmVuZG9iag0KDQp4cmVmDQowIDExDQowMDAwMDAwMDAwIDY1NTM1IGYNCjAwMDAwMDAwMTkgMDAwMDAgbg0KMDAwMDAwMDA5MyAwMDAwMCBuDQowMDAwMDAwMTQ3IDAwMDAwIG4NCjAwMDAwMDAyMjIgMDAwMDAgbg0KMDAwMDAwMDM5MCAwMDAwMCBuDQowMDAwMDAxNTIyIDAwMDAwIG4NCjAwMDAwMDE2OTAgMDAwMDAgbg0KMDAwMDAwMjQyMyAwMDAwMCBuDQowMDAwMDAyNDU2IDAwMDAwIG4NCjAwMDAwMDI1NzQgMDAwMDAgbg0KDQp0cmFpbGVyDQo8PA0KL1NpemUgMTENCi9Sb290IDEgMCBSDQovSW5mbyAxMCAwIFINCj4+DQoNCnN0YXJ0eHJlZg0KMjcxNA0KJSVFT0YNCg==";
		
		$total_batch = $this->db->query("select batch from ms_send_to_email mste where active =1 and module ='auto_claim' group by batch having count(DISTINCT email) > 0")->num_rows();
		$get_data = $this->db->query("select a.*, b.jenis_sales_program from tr_sales_program a join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program where a.no_juklak_md ='$id_program'");
		// $get_data = $this->db->query("select a.*, b.jenis_sales_program from tr_sales_program a join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program where a.no_juklak_md ='$id_program' or a.no_juklak_md in ('035/SSP.HD/SCP.MKT/IV/2022','061/SSP.HD/SCP.MKT/VII/2022','033/SSP.HD/SCP.MKT/IV/2022','072/SSP.HD/SCP.MKT/VIII/2022','071/SSP.HD/SCP.MKT/VIII/2022')");
		
		if($get_data->num_rows()>0){;
			// foreach utk kirim email dengan isi berdasarkan no juklak MD
			foreach($get_data->result() as $row_data){
				if($row_data->no_juklak_md !=''){
					$id = $row_data->no_juklak_md;
					$filename = $row_data->file_name;
					$b64 = $row_data->draft_jutlak;
				}

				if($row_data->kuota_program !='*'){
					// send all dealer
					$auto_send_batch = 1; // 1= pengiriman email berdasarkan batch email di database
					for($i=0; $i<$total_batch; $i++){
						$batch = $i+1;
						$temp_list_email = array();
						$get_to_email = $this->db->query("select email, nickname from ms_send_to_email where active = '1' and module ='auto_claim' and batch = '$batch'");
						if($get_to_email->num_rows()>0){
							foreach ($get_to_email->result() as $row){
								array_push($temp_list_email, $row->email);
							}

							$list_to_email[$i] = $temp_list_email;
							// $partition = ceil(count(array_unique($temp_list_email)) / ceil(count(array_unique($temp_list_email)) / $email_per_batch) );
							$jmlh_batch = $total_batch;
						}
					}
				}else if($row_data->kuota_program =='*'){
					$auto_send_batch = 0; // 0 = pengiriman email tidak berdasarkan batch email di database
					// send berdasarkan dealer yg didaftarkan
					$list_id_dealer = '';
					$temp_id_dealer = array();
					$get_data_program_dealer = $this->db->query("select id_dealer from tr_sales_program_dealer where id_program_md = '$row_data->id_program_md'");
					if($get_data_program_dealer->num_rows() > 0){
						foreach($get_data_program_dealer->result() as $row_dealer){
							array_push($temp_id_dealer, $row_dealer->id_dealer);
						}
						$list_id_dealer = implode(', ',$temp_id_dealer);

						$get_to_email = $this->db->query("select email, nickname from ms_send_to_email where active = '1' and module ='auto_claim' and id_dealer in ($list_id_dealer)");
						if($get_to_email->num_rows()>0){
							foreach ($get_to_email->result() as $row){
								array_push($list_to_email, $row->email);
							}
						}

						// pengecekan total list email, jika melebihi 31 email dealer (excl cc email) maka dibagi ke bbrp batch pengiriman email
						$partition = ceil(count(array_unique($list_to_email)) / ceil(count(array_unique($list_to_email)) / $email_per_batch) );
						$jmlh_batch = count(array_chunk(array_unique($list_to_email), $partition));
					}
				}
			}
		
			// looping utk pengiriman email berdasarkan jumlah batch
			$temp_list_email = $list_to_email;
			for($i=0;$i<$jmlh_batch;$i++){
				if($auto_send_batch ==0){
					$list_to_email = implode(', ',array_chunk(array_unique($temp_list_email), $partition)[$i]);
					// echo 'Batch ke '.($i+1).' dengan '.count(array_chunk(array_unique($temp_list_email), $partition)[$i]).' list unik email: <br>';print_r($list_to_email); echo '<br><br>';			
					// echo '<br><br>';
				}else if($auto_send_batch==1){
					if(isset($temp_list_email[$i])){
						$list_to_email = implode(', ', array_unique($temp_list_email[$i]));
						// print_r($list_to_email);
						// echo '<br><br>';
						// echo count(array_unique($temp_list_email[$i]));
						// echo '<br><br>';
					}
				}
				echo 'Percobaan terakhir utk dikirim di akhir bulan';die;

				// send email()
				if($list_to_email!=''){	
					echo 'Batch: '.$i.'<br>';		
					$this->load->library('email', $config);
					$this->email->clear(true);	
				
					$this->email->from($from->email,'SEEDS Notification System'); 
					// $this->email->from('no-reply@sinarsentosaprimatama.com'); 
				
					$this->email->to($list_to_email);
					// $this->email->to('michael.chandra@sinarsentosa.co.id');

					// get all data email cc
					$list_cc_mail = array();
					$get_cc_mail = $this->db->query("select * from ms_cc_email where module = 'auto_claim' and active = 1");
					if($get_cc_mail->num_rows()> 0 ){
						foreach($get_cc_mail->result() as $row_cc){
							array_push($list_cc_mail, $row_cc->email_cc);
						}
						$list_cc_mail = implode(', ',$list_cc_mail);
						$this->email->cc($list_cc_mail);
					}

					// $this->email->cc('linda@sinarsentosa.co.id, michael.chandra@sinarsentosa.co.id');
					// $this->email->cc('linda@sinarsentosa.co.id, tuminah@sinarsentosa.co.id, siti.aisah@sinarsentosa.co.id, arum.nurjanah@sinarsentosa.co.id,michael.chandra@sinarsentosa.co.id');

					$subject = '[Testing] '.$id;
					$data['id_program'] = $id;
					$data['filename'] = $filename;

					$get_data_detail = $this->db->query("select a.id_program_md , a.judul_kegiatan , b.jenis_sales_program, a.periode_awal , a.periode_akhir , c.* , d.tipe_ahm 
					from tr_sales_program a
					join ms_jenis_sales_program b on a.id_jenis_sales_program = b.id_jenis_sales_program
					join tr_sales_program_tipe c on a.id_program_md =c.id_program_md 
					join ms_tipe_kendaraan d on c.id_tipe_kendaraan  = d.id_tipe_kendaraan 
					where a.no_juklak_md ='$id_program'");
			
					$data['detail'] = $get_data_detail->result();

					$this->email->subject("$subject"); // no juklak dan deskprsi program
					$this->email->message($this->load->view('h1/email_notification_auto_claim', $data, true)); 
					$bin = base64_decode($b64, true);
					$this->email->attach($bin,'attachment',$filename,'application/pdf');

					/*
					// sleep sending email for 15 seconds after batch 1
					if($i > 0){
						sleep(15);
					}

					if($this->email->send()){
						// send_json([
						// 	'message' => 'Email berhasil dikirim.'
						// ]);
					}else{
						echo 'Something error!<br>';
						$this->output->set_status_header(400);
						// send_json([
						// 	'message' => 'Email gagal dikirim.'
						// ]);
					}
					*/
				}
			}
		}else{
			echo 'Not Found';
		}
	}

	public function update_id_int()
	{	
		$id_dealer = 13;
		$i=0;
		$data_stok = $this->db->query("select * from ms_h3_dealer_stock where id_dealer = $id_dealer and id_part_int is null");
		if($data_stok->num_rows()> 0 ){
			foreach($data_stok->result() as $row){
				$i++;
				echo '<br>'.$row->id_part.' '.$row->stock.'<br>';
				$id_part_int = $this->db->query("select id_part , id_part_int from ms_part where id_part = '$row->id_part'")->row()->id_part_int;
				$update_log = "UPDATE sinarsen_honda.ms_h3_dealer_stock SET id_part_int= $id_part_int WHERE id_dealer = $id_dealer and id_part='$row->id_part';";
				echo $update_log.'<br>';
				// $this->db->query("$update_log");
			}
		}
	}

	public function update_non_stok_13()
	{	
		$tgl_created='2022-10-27 09:00:00';
		$id_dealer = 13;
		
		$i = 0;
		$list_part ="
		'070MZ0010300',
		'07600BCR0000',
		'077460030100',
		'07965GC70100IN',
		'07HMDMR70100',
		'07RMCKCW0100IN',
		'07XMZMBW0101',
		'082342MBK0LZ9',
		'082322MAK0LN9',
		'082322MAK1LN9',
		'082322MAK8LN9',
		'082342MBK0LZ0',
		'08234M99K0LN9',
		'082342MAK1LZ0',
		'082342MAK8LZ0',
		'082322MAU0JN3',
		'082322MAU1JN3',
		'08294M99K8LN9',
		'08234M99K8LZ0'";

		$limit = " limit 200";
		// $limit = " ";
		$tgl_created='2022-10-27 09:00:00';

		$baru =0;
		$lama =0;

		$data_stok = $this->db->query("select * from ms_h3_dealer_stock where id_dealer = $id_dealer and id_part not in (select `key` from ar_internal_metadata order by 'key' asc) and id_part not in ($list_part) order by id_part asc $limit");
		if($data_stok->num_rows()> 0 ){
			foreach($data_stok->result() as $row){
				$i++;
				echo '<br>'.$row->id_part.' '.$row->stock.'<br>';
				
				$data_transaksi = $this->db->query("select * from ms_h3_dealer_transaksi_stok where id_dealer = $id_dealer and id_part = '$row->id_part' and id_rak = '$row->id_rak' and id_gudang = '$row->id_gudang' order by created_at desc limit 1");
				if($data_transaksi->num_rows()> 0 ){
					foreach($data_transaksi->result() as $row_detail){
						$lama++;
						echo '=> '. $row_detail->id_part.' '. $row_detail->id_rak.' ='.$row_detail->stok_akhir.'<br>';

						if($row->stock >0){
							$history = "INSERT INTO sinarsen_honda.ms_h3_dealer_transaksi_stok (id_part,id_gudang,id_rak,id_dealer,tipe_transaksi,sumber_transaksi,referensi,stok_awal,stok_value,stok_akhir,created_at)
								VALUES ('$row->id_part','$row->id_gudang','$row->id_rak',$id_dealer,'-','opname','-',$row->stock,$row->stock,0,'$tgl_created');";
							echo $history.'<br>';
							// $this->db->query("$history");
						}

						$delete = "DELETE FROM sinarsen_honda.ms_h3_dealer_stock WHERE id_dealer = $id_dealer and id_part = '$row->id_part' and id_rak = '$row->id_rak' and id_gudang='$row->id_gudang';";
						echo $delete.'<br>';
						// $this->db->query("$delete");
					}
				}else if($data_transaksi->num_rows()==0){
					echo "stok >= 0, hapus stok karena tidak ada history".'<br>';
					$baru++;
					$delete = "DELETE FROM sinarsen_honda.ms_h3_dealer_stock WHERE id_dealer = $id_dealer and id_part = '$row->id_part' and id_rak = '$row->id_rak' and id_gudang='$row->id_gudang';";
					echo $delete.'<br>';
					// $this->db->query("$delete");
				}
			}
		}

		echo "Total: ".$i.' No History: '.$baru.' History: ',$lama;
	}

	public function update_stok_13()
	{	
		$i = 0;
		$ok = 0;
		$kurang = 0;
		$baru = 0;
		$rak_2_lebih =0;
		$tgl_created='2022-10-27 09:00:00';
		$id_dealer = 13;

		$data_stok = $this->db->query("select * from ar_internal_metadata where status in (3) order by 'key' asc");
		if($data_stok->num_rows()> 0 ){
			foreach($data_stok->result() as $row_stok){
				echo '<br>New: '.$row_stok->key.' '.$row_stok->value.'<br>';

				$data = $this->db->query("select * from ms_h3_dealer_stock where id_dealer = $id_dealer and id_part ='$row_stok->key' order by id_part asc");
				// jika baris ke 1 == stok maka baris ke 2 di set 0 dan dikasih log opname sesuai dengan stok di baris ke 2 dst
				if($data->num_rows()==1){
					$stok = 0;
					foreach($data->result() as $row){
						$i++;
						echo 'Stok: '. $row->id_part.' '. $row->id_gudang.' '.$row->id_rak.' ='. $row->stock. '<br>';
						$stok+= $row->stock;
						echo 'Total: '.$stok.'<br>';
						
						$is_update = 1;
						if($row_stok->value < $stok){
							$counter = '-';
							$stok_awal = $stok;
							$stok_value = $stok - $row_stok->value;
							$stok_akhir = $row_stok->value;
							echo $stok_awal. ' '. $counter .' '. $stok_value. '='. $stok_akhir. '<br>';
						}else if($row_stok->value > $stok){
							$counter = '+';
							$stok_awal =  $stok;
							$stok_value = $row_stok->value - $stok;
							$stok_akhir = $row_stok->value;
							echo $stok_awal. ' '. $counter .' '. $stok_value. '='. $stok_akhir. '<br>';
						}else{
							$is_update = 0;
							$ok++;
							echo 'COCOK<br>';
							
							$update_log = "UPDATE sinarsen_honda.ar_internal_metadata SET updated_at = '$tgl_created', status = 4 WHERE `key` ='$row->id_part';";
							echo $update_log.'<br>';
							// $this->db->query("$update_log");
						}
						
						if($is_update){
							$id_part = $row->id_part;
							$id_gudang = $row->id_gudang;
							$id_rak = $row->id_rak;

							$update = "UPDATE sinarsen_honda.ms_h3_dealer_stock SET stock= $stok_akhir WHERE id_dealer = $id_dealer and id_part ='$id_part' and id_gudang = '$id_gudang' and id_rak ='$id_rak';";
							echo $update.'<br>';
							// $this->db->query("$update");

							$history = "INSERT INTO sinarsen_honda.ms_h3_dealer_transaksi_stok (id_part,id_gudang,id_rak,id_dealer,tipe_transaksi,sumber_transaksi,referensi,stok_awal,stok_value,stok_akhir,created_at)
								VALUES ('$id_part','$id_gudang','$id_rak',$id_dealer,'$counter','opname','-',$stok_awal,$stok_value,$stok_akhir,'$tgl_created');";
							echo $history.'<br>';
							// $this->db->query("$history");
					
							$update_log = "UPDATE sinarsen_honda.ar_internal_metadata SET updated_at = '$tgl_created', status = 1 WHERE `key` ='$id_part';";
							echo $update_log.'<br>';
							// $this->db->query("$update_log");
						}

						$data_transaksi = $this->db->query("select * from ms_h3_dealer_transaksi_stok where id_dealer = $id_dealer and id_part = '$row->id_part' and id_rak = '$row->id_rak' order by created_at desc limit 1");
						if($data_transaksi->num_rows()> 0 ){
							foreach($data_transaksi->result() as $row_detail){
								echo '=> '. $row_detail->id_part.' '. $row_detail->id_rak.' ='.$row_detail->stok_akhir.'<br>';
							}
						}
						
					}
				}else if($data->num_rows()==0){
					$baru++;
					$update_log = "UPDATE sinarsen_honda.ar_internal_metadata SET status = 2 WHERE `key` ='$row_stok->key';";
					// $this->db->query("$update_log");
					echo $row_stok->key.'<br>';

					$id_part_int = $this->db->query("select id_part, id_part_int from ms_part where id_part = '$row_stok->key'")->row()->id_part_int;
					
					$id_dealer = 13;
					$id_gudang = '04692/WHS-001';
					$id_gudang_int ='34';
					$id_rak = '001';
					$id_rak_int = '12260';
					$id_part = $row_stok->key;
					$stok_part=$row_stok->value;
					$counter = '+';
					$stok_awal = 0;
					$stok_value= $stok_part;
					$stok_akhir = $stok_value;

					$insert_part = "
					INSERT INTO sinarsen_honda.ms_h3_dealer_stock (id_dealer,id_part_int,id_part,id_gudang_int,id_gudang,id_rak_int,id_rak,stock,freeze)
						VALUES ($id_dealer,$id_part_int,'$id_part',$id_gudang_int,'$id_gudang',$id_rak_int,'$id_rak',$stok_part,0);
					";
					echo $insert_part.'<br>';
					// $this->db->query("$insert_part");
					
					$history = "INSERT INTO sinarsen_honda.ms_h3_dealer_transaksi_stok (id_part,id_gudang,id_rak,id_dealer,tipe_transaksi,sumber_transaksi,referensi,stok_awal,stok_value,stok_akhir,created_at)
					VALUES ('$id_part','$id_gudang','$id_rak',$id_dealer,'$counter','opname','-',$stok_awal,$stok_value,$stok_akhir,'$tgl_created');";
					echo $history.'<br>';
					// $this->db->query("$history");
					
					$data_transaksi = $this->db->query("select * from ms_h3_dealer_transaksi_stok where id_dealer = $id_dealer and id_part = '$id_part' and id_rak = '$id_rak' order by created_at desc limit 1");
					if($data_transaksi->num_rows()> 0 ){
						foreach($data_transaksi->result() as $row_detail){
							echo '=> '. $row_detail->id_part.' '. $row_detail->id_rak.' ='.$row_detail->stok_akhir.'<br>';
						}
					}
				}else if($data->num_rows()>1){
					// echo 'lebih dari 1 rak <br>';
					$kurang++;
					$update_log = "UPDATE sinarsen_honda.ar_internal_metadata SET status = 3 WHERE `key` ='$row_stok->key';";
					// echo $update_log.'<br>';
					// $this->db->query("$update_log");

					$stok = 0;
					$rak_ke = 0;
					if($data->num_rows()==2){
						$is_update = 0;
						foreach($data->result() as $row){
							$rak_ke++;
							echo 'Stok: '. $row->id_part.' '. $row->id_gudang.' '.$row->id_rak.' ='. $row->stock. '<br>';
							$stok+= $row->stock;
							$id_rak = $row->id_rak;
							$id_gudang = $row->id_gudang;
							$id_part = $row->id_part;

							/* posisinya di mana? di dalam transaksi line 587 atau disini?*/
							if($row_stok->value < $stok){
								$counter = '-';
								$stok_awal = $stok;
								$stok_value = $stok - $row_stok->value;
								$stok_akhir = $row_stok->value;
								echo $stok_awal. ' '. $counter .' '. $stok_value. '='. $stok_akhir. '<br>';
								$is_update = 1;
							}else if($row_stok->value > $stok){
								$counter = '+';
								$stok_awal =  $stok;
								$stok_value = $row_stok->value - $stok;
								$stok_akhir = $row_stok->value;
								echo $stok_awal. ' '. $counter .' '. $stok_value. '='. $stok_akhir. '<br>';
								$is_update = 1;
							}else{
								$is_update = 0;
								$ok++;
								echo 'COCOK<br>';
								
								$update_log = "UPDATE sinarsen_honda.ar_internal_metadata SET updated_at = '$tgl_created', status = 4 WHERE `key` ='$row->id_part';";
								echo $update_log.'<br>';
								// $this->db->query("$update_log");
							}
							
							
							$data_transaksi = $this->db->query("select * from ms_h3_dealer_transaksi_stok where id_dealer = $id_dealer and id_part = '$id_part' and id_gudang ='$id_gudang' and id_rak = '$id_rak' order by created_at desc limit 1");
							if($data_transaksi->num_rows()> 0 ){
								foreach($data_transaksi->result() as $row_detail){
									echo '=> '. $row_detail->id_part.' '. $row_detail->id_gudang.' '.$row_detail->id_rak.' ='.$row_detail->stok_akhir.'<br>';
									// update stok dan insert history
									if($rak_ke==1){
										echo "rak 1, stok >= 0, update stok dan insert history".'<br>';
										if($is_update){
											$update = "UPDATE sinarsen_honda.ms_h3_dealer_stock SET stock= $stok_akhir WHERE id_dealer = $id_dealer and id_part ='$id_part' and id_gudang = '$id_gudang' and id_rak ='$id_rak';";
											echo $update.'<br>';
											// $this->db->query("$update");

											$history = "INSERT INTO sinarsen_honda.ms_h3_dealer_transaksi_stok (id_part,id_gudang,id_rak,id_dealer,tipe_transaksi,sumber_transaksi,referensi,stok_awal,stok_value,stok_akhir,created_at)
												VALUES ('$id_part','$id_gudang','$id_rak',$id_dealer,'$counter','opname','-',$stok_awal,$stok_value,$stok_akhir,'$tgl_created');";
											echo $history.'<br>';
											// $this->db->query("$history");
										}
									}else if($rak_ke==2){
										echo "rak 2, stok >= 0, hapus stok dan insert history set = 0".'<br>';

										$delete = "DELETE FROM sinarsen_honda.ms_h3_dealer_stock WHERE id_dealer = $id_dealer and id_part = '$id_part' and id_rak = '$id_rak' and id_gudang='$id_gudang';";
										echo $delete.'<br>';
										// $this->db->query("$delete");

										$history = "INSERT INTO sinarsen_honda.ms_h3_dealer_transaksi_stok (id_part,id_gudang,id_rak,id_dealer,tipe_transaksi,sumber_transaksi,referensi,stok_awal,stok_value,stok_akhir,created_at)
											VALUES ('$id_part','$id_gudang','$id_rak',$id_dealer,'-','opname','-',$stok_awal,$stok_awal,0,'$tgl_created');";
										echo $history.'<br>';
										// $this->db->query("$history");
									}
									// $is_update=1;
								}
							}else if($data_transaksi->num_rows()==0){
								if($rak_ke==1){
									echo "rak 1*, stok >= 0, update stok dan insert history".'<br>';
									if($is_update){
										$update = "UPDATE sinarsen_honda.ms_h3_dealer_stock SET stock= $stok_akhir WHERE id_dealer = $id_dealer and id_part ='$id_part' and id_gudang = '$id_gudang' and id_rak ='$id_rak';";
										echo $update.'<br>';
										// $this->db->query("$update");

										$history = "INSERT INTO sinarsen_honda.ms_h3_dealer_transaksi_stok (id_part,id_gudang,id_rak,id_dealer,tipe_transaksi,sumber_transaksi,referensi,stok_awal,stok_value,stok_akhir,created_at)
											VALUES ('$id_part','$id_gudang','$id_rak',$id_dealer,'$counter','opname','-',$stok_awal,$stok_value,$stok_akhir,'$tgl_created');";
										echo $history.'<br>';
										// $this->db->query("$history");
									}
								}else if($rak_ke==2){
									echo "rak 2*, stok >= 0, hapus stok karena tidak ada history".'<br>';

									$delete = "DELETE FROM sinarsen_honda.ms_h3_dealer_stock WHERE id_dealer = $id_dealer and id_part = '$id_part' and id_rak = '$id_rak' and id_gudang='$id_gudang';";
									echo $delete.'<br>';
									// $this->db->query("$delete");
								}
								// $is_update=1;
							}
							echo 'Total: '.$stok.'<br>';
						}

						if($is_update){		
							$update_log = "UPDATE sinarsen_honda.ar_internal_metadata SET status = 5 WHERE `key` ='$row_stok->key';";
							echo $update_log.'<br>';
							// $this->db->query("$update_log");
						}
					}else{
						// lebih dari 2 rak
						$rak_2_lebih++;
					}
				}
			}
			// 1 = update, 2= baru, 3= kurang , 4=cocok, 5 = hapus stok
			echo '<br>Total: '.$i.'<br>Cocok: '.$ok.'<br>Beda: '.$kurang.'('.$rak_2_lebih.')'.'<br>Stok Baru: '.$baru;

			echo '<br>cek part yang di luar dari data opname stok';
		}else{
			echo 'data kosong<br>';
		}
	}
}