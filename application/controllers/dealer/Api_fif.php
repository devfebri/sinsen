<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_fif extends CI_Controller {

	var $tables =   "tr_fif_order";	
	var $folder =   "dealer";
	var $page	=	"api_fif";
	var $title  =   "API FIF";
	// var $baseUrl = "https://portf-api.fifgroup.co.id/fifport/";
	// var $baseUrl_v2 = "https://restapi.fifgroup.co.id/v2/";
	var $baseUrl = "https://restapi.fifgroup.co.id/fifport/s";
	var $baseUrl_inv = "https://restapi.fifgroup.co.id/fifinvoice/s";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_fif');	
// 		$this->load->helper('fif_helper');	
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');

		// //---- cek session -------//		
		// $name = $this->session->userdata('nama');
		// $auth = $this->m_admin->user_auth($this->page,"select");		
		// $sess = $this->m_admin->sess_auth();		
		// if($name=="" OR $auth=='false' OR $sess=='false')
		// {
		// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		// }
	}

	protected function template($data,$view)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($view);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$view = 'dealer/fif/view';
		$this->template($data,$view);	
	}

	public function getData()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
        $id_dealer = $this->m_admin->cari_dealer();
        $dataSpk = $this->m_fif->get_spk($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer);
        $data = array();
        foreach($dataSpk->result() as $rows)
        {
			$btn =  '<a href="dealer/api_fif/order_new?id_spk='.$rows->no_spk.'" class="btn btn-xs btn-info">Submit Order</a>';

			$button_row = $this->db->query("SELECT * from tr_hasil_survey WHERE status_approval ='rejected' and no_spk ='$rows->no_spk'  group by no_spk ");

			if($button_row->num_rows() > 0){
				$btn =  '<a href="dealer/api_fif/order_new?id_spk='.$rows->no_spk.'" class="btn btn-xs btn-warning" title="re send spk">Re - Submit Order</a>';
			}

            $data[]= array(
            	'',
                $rows->no_spk,
                $rows->nama_konsumen,
                $rows->created_at,
                // $status,
				$btn
            );    

        }
        $total_data = $this->m_fif->count_filter_spk($search, $id_dealer);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


	// testing
    public function getDataAllOrder()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
        $id_dealer = $this->m_admin->cari_dealer();
        $dataSpk = $this->m_fif->get_order_fif($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer);
        $data = array();
		
		$no = 1;
        foreach($dataSpk->result() as $rows)
        {
			$status_order_api = "";
			if ($this->m_fif->cek_order($rows->order_uuid)->num_rows() > 0) {
				$d = $this->m_fif->cek_order($rows->order_uuid)->row();
				$status_order_api = $d->order_status;
			} else {
				$status_order_api = cek_status_order($rows->order_uuid);
			}

			if ($status_order_api == 'CANCELLED') {
    			$this->db->where('no_spk', $rows->no_spk);
    			$this->db->where('order_uuid', $rows->order_uuid);
    			$this->db->update('tr_fif_order', array('is_cancel'=>'y'));
    		}

    		if ($status_order_api == 'REJECTED') {
    			$this->db->where('no_spk', $rows->no_spk);
    			$this->db->update('tr_hasil_survey', array(
    				'updated_at' => get_waktu(),
    				'updated_by' => 0,
    				'status_approval'=>'rejected'
    			));
    		}

        	$btn_kirim_dokumen = "";
        	$btn_delivery      = "";
        	$btn_invoice       = "";
        	
			if ($rows->delivery == 't' AND strtotime($rows->created_at) > strtotime('2021-08-03') AND $status_order_api == 'APPROVED') {
				$btn_delivery = '<a onclick=\'ready_to_delivery("dealer/api_fif/delivery/'.$rows->order_uuid.'?id='.$rows->no_spk.'")\' class="btn btn-xs btn-primary">Ready To Delivery</a>';
			
			} else {
				$btn_delivery = "";
			}

			if ( $rows->delivery == 't' AND strtotime($rows->created_at) > strtotime('2021-12-15') AND $status_order_api == 'NEW ORDER' ) {
				$btn_kirim_dokumen = '<a href="dealer/api_fif/dokumen_upload/'.$rows->order_uuid.'/'.$rows->no_spk.'" class="btn btn-xs btn-success">Re-send Document</a>';
			}

			if ($rows->delivery == 'y' AND $rows->kirim_invoice == 't' AND strtotime($rows->created_at) > strtotime('2021-10-31') AND $status_order_api == 'APPROVED') {
				$btn_invoice = '<a href="dealer/api_fif/kirim_invoice?id='.$rows->no_spk.'&order_uuid='.$rows->order_uuid.'" class="btn btn-xs btn-success">Upload Documents</a>';
			}

			if ($rows->delivery == 'y' AND $rows->kirim_invoice == 'y' AND $rows->kirim_dokumen_invoice == 't' AND strtotime($rows->created_at) > strtotime('2021-10-31') AND $status_order_api == 'APPROVED') {
				$btn_invoice = '<a href="dealer/api_fif/upload_dokumen_invoice?id='.$rows->no_spk.'&inv_uuid='.$rows->inv_uuid.'&order_uuid='.$rows->order_uuid.'" class="btn btn-xs btn-success">Upload Dokument</a>';
			}

			if ($rows->kirim_dokumen_invoice == 'y' AND $status_order_api == 'APPROVED') {
				$status_order_api = "e-invoice";
				$btn_invoice = '<a href="dealer/api_fif/upload_dokumen_invoice?id='.$rows->no_spk.'&inv_uuid='.$rows->inv_uuid.'&order_uuid='.$rows->order_uuid.'&judul=Re-upload Dokumen Invoice" class="btn btn-xs btn-success">Re-upload Dokuments</a>';
			}

			$button_row_re = $this->db->query("SELECT os.no_order_survey from tr_hasil_survey hs inner join  tr_order_survey os on hs.no_order_survey = os.no_order_survey 
			and os.no_spk ='$rows->no_spk' and  os.order_uuid ='$rows->order_uuid' ");

			// if ($id_dealer == '103')
			// {
				if($button_row_re->num_rows() == 0){
					if ( $rows->delivery == 't' AND strtotime($rows->created_at) > strtotime('2021-12-15') AND  ( $status_order_api == 'CANCELLED' || $status_order_api == 'REJECTED')  ) {
						$btn_invoice = '<button onclick="confirmAndUpdate(\''.$rows->order_uuid.'\', \''.$rows->no_spk.'\')">Update to History Finco</button>';
					}
				}else{
					// if ( $rows->delivery == 't' AND strtotime($rows->created_at) > strtotime('2021-12-15') AND  ( $status_order_api == 'CANCELLED' || $status_order_api == 'REJECTED')  ) {
					// 	$btn_invoice = '<button onclick="confirmAndUpdate(\''.$rows->order_uuid.'\', \''.$rows->no_spk.'\')">Update to History Finco</button>';
					// }
				}
			// }



            $data[]= array(
            	'',
                $rows->no_spk,
                $rows->nama_konsumen,
                $rows->order_uuid,
				$status_order_api,
                '<a href="dealer/api_fif/order_data/'.$rows->order_uuid.'" class="btn btn-xs btn-info">Detail Order</a>
                 <a href="dealer/api_fif/order_status_one/'.$rows->order_uuid.'" class="btn btn-xs btn-warning">Cek Status PO</a>
                 '.$btn_kirim_dokumen.'
                 '.$btn_delivery.'
                 '.$btn_invoice.'
                '
            );     
        }
		
        $total_data = $this->m_fif->count_filter_order_fif($search, $id_dealer);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
		$no++;
    }

	public function update_history_finco()
	{
		$id  = $this->input->get('id');
		$spk = $this->input->get('spk');
		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');

		$os = $this->db->query("SELECT * from tr_order_survey WHERE no_spk ='$spk'  order by created_at desc limit 1")->row();
		$fif_set        = $this->db->query("SELECT * from tr_fif_order_json WHERE order_uuid ='$id' ");
		
		$return_fif 	= $fif_set->row();
		$keterangan 	= 'Reject Auto From Edit SPK';
		$created_at_fif = $waktu;
		
		if ($fif_set->num_rows() > 0  ){
			$keterangan     = $return_fif->order_arc_sub_reason;
			$created_at_fif = $return_fif->created_at;
		}
		
		$hs_validation  = $this->db->query("SELECT * from tr_hasil_survey hs join tr_order_survey os on hs.no_order_survey = os.no_order_survey WHERE hs.no_spk ='$spk' and os.order_uuid is null order by hs.id_hasil_survey desc limit 1");
	

		if ($hs_validation->num_rows() == 0  ){
				$array_data = array(
					'no_spk'       		    => $spk,	  
					'no_order_survey'		=> $os->no_order_survey,
					'tanda_jadi'            => $os->uang_muka,
					'tenor'                 => $os->tenor,
					'nilai_dp'              => $os->dp_stor,
					'harga_motor'           => $os->harga_tunai,
					'keterangan'     		=> $keterangan,
					'status_approval'  		=> 'rejected',
					'tgl_approval'			=> $created_at_fif,
					'created_at'     		=> $waktu,
					'created_by'     		=> $login_id,
					'updated_at'     		=> $waktu,
					'updated_by'     		=> $login_id,
					'status_spk'     		=> 'lama',
				);

				$this->db->insert('tr_hasil_survey', $array_data );
				$this->db->where('no_spk', $spk );
				$this->db->where('order_uuid IS NULL', null, false);
				$this->db->update('tr_order_survey', array('status_survey'=>'cancel','order_uuid'=>$id));
		
				if ($this->db->affected_rows() > 0) {
					$this->db->where('no_spk', $spk );
					$this->db->update('tr_spk', array('status_spk'=>'booking'));
					$_SESSION['pesan'] 	= "SPK sebelumnya $spk sudah dikembalikan ke status Booking, SPK bisa di update | Hasil Survey auto menjadi Reject";
					$_SESSION['tipe'] 	= "warning";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				}
		}

		if ($hs_validation->num_rows() == 1  ){
				$array_data = array(
					'no_spk'       		    => $spk,	  
					'no_order_survey'		=> $os->no_order_survey,
					'tanda_jadi'            => $os->uang_muka,
					'tenor'                 => $os->tenor,
					'nilai_dp'              => $os->dp_stor,
					'harga_motor'           => $os->harga_tunai,
					'keterangan'     		=> $keterangan,
					'status_approval'  		=> 'rejected',
					'tgl_approval'			=> $created_at_fif,
					'created_at'     		=> $waktu,
					'created_by'     		=> $login_id,
					'updated_at'     		=> $waktu,
					'updated_by'     		=> $login_id,
					'status_spk'     		=> 'lama',
				);

				$this->db->insert('tr_hasil_survey', $array_data );
				$this->db->where('no_spk', $spk );
				$this->db->where('order_uuid IS NULL', null, false);
				$this->db->update('tr_order_survey', array('status_survey'=>'cancel','order_uuid'=>$id));
		
				if ($this->db->affected_rows() > 0) {
					$this->db->where('no_spk', $spk );
					$this->db->update('tr_spk', array('status_spk'=>'booking'));
					$_SESSION['pesan'] 	= "SPK sebelumnya $spk sudah dikembalikan ke status Booking, SPK bisa di update | Hasil Survey auto menjadi Reject";
					$_SESSION['tipe'] 	= "warning";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				}
		}



	}

    public function getDataAllOrderInvoice()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

        $id_dealer = $this->m_admin->cari_dealer();
        $dataSpk = $this->m_fif->get_order_fif_kasir($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer);
        $data = array();
        foreach($dataSpk->result() as $rows)
        {
		$status_order_api = cek_status_order($rows->order_uuid);
		if($status_order_api ==''){
			// jika masuk ke sini, maka ada error di helper api fif utk curl jsondecode nya line 45 s/d 48
			// Penyebab lain bisa juga, karna return api dari fif gagal
			$status_order_api = '';
		}else{
        		if ($status_order_api == 'CANCELLED') {
					$this->db->where('order_uuid', $rows->order_uuid);
        			$this->db->where('no_spk', $rows->no_spk);
        			$this->db->update('tr_fif_order', array('is_cancel'=>'y'));
        		}

        		if ($status_order_api == 'REJECTED') {
        			$this->db->where('no_spk', $rows->no_spk);
        			$this->db->update('tr_hasil_survey', array(
        				'updated_at' => get_waktu(),
        				'updated_by' => 0,
        				'status_approval'=>'rejected'
        			));
        		}
		}

        	$btn_kirim_dokumen = "";
        	$btn_delivery = "";
        	$btn_invoice = "";
        	

	        	if ($rows->delivery == 't' AND strtotime($rows->created_at) > strtotime('2021-08-03') AND $status_order_api == 'APPROVED') {
	        		$btn_delivery = '<a onclick=\'ready_to_delivery("dealer/api_fif/delivery/'.$rows->order_uuid.'?id='.$rows->no_spk.'")\' class="btn btn-xs btn-primary">Ready To Delivery</a>';
	        	} else {
	        		$btn_delivery = "";
	        	}

	        	// if ($id_dealer == '103') {

					// kirim_invoice()
	        		if ($rows->delivery == 'y' AND $rows->kirim_invoice == 't' AND strtotime($rows->created_at) > strtotime('2021-10-31') AND $status_order_api == 'APPROVED') {
		        		$btn_invoice = '<a href="dealer/api_fif/kirim_invoice?id='.$rows->no_spk.'&order_uuid='.$rows->order_uuid.'" class="btn btn-xs btn-success">Upload Documents</a>';
		        	}

					// upload_dokumen_invoice()
		        	if ($rows->delivery == 'y' AND $rows->kirim_invoice == 'y' AND $rows->kirim_dokumen_invoice == 't' AND strtotime($rows->created_at) > strtotime('2021-10-31') AND $status_order_api == 'APPROVED') {
						// status before dibusement
						// upload_dokumen_invoice()
		        		$btn_invoice = '<a href="dealer/api_fif/upload_dokumen_invoice?id='.$rows->no_spk.'&inv_uuid='.$rows->inv_uuid.'&order_uuid='.$rows->order_uuid.'" class="btn btn-xs btn-success">Upload Dokuments Inv</a>';
		        	}

		        	if ($rows->kirim_dokumen_invoice == 'y' AND $status_order_api == 'APPROVED') {
		        		$status_order_api = "e-invoice";
		        		$btn_invoice = '<a href="dealer/api_fif/upload_dokumen_invoice?id='.$rows->no_spk.'&inv_uuid='.$rows->inv_uuid.'&order_uuid='.$rows->order_uuid.'&judul=Re-upload Dokumen Invoice" class="btn btn-xs btn-success">Re-upload Dokuments</a>';
		        	}
	        	// }

		    $get_detail_order_by_nospk = get_detail_order_by_nospk($rows->no_spk);
		    $status_order = json_decode($get_detail_order_by_nospk);


            $data[]= array(
            	'',
                $rows->no_spk,
                $rows->nama_konsumen,
                // number_format($status_order->data[0]->trf_amount,2),
                // $status_order->data[0]->object[0]->inv_no,
                // $status_order->data[0]->object[0]->po_date,
                // $status_order->data[0]->object[0]->inv_paid_date,
                ($status_order->data[0]->trf_amount == null) ? '' : number_format($status_order->data[0]->trf_amount,2),
                ($status_order->data[0]->object[0]->inv_no == null) ? '' : $status_order->data[0]->object[0]->inv_no,
                ($status_order->data[0]->object[0]->po_date == null) ? '' : $status_order->data[0]->object[0]->po_date,
                ($status_order->data[0]->object[0]->inv_paid_date == null) ? '' : $status_order->data[0]->object[0]->inv_paid_date,
				$status_order_api,//cek_status_order($rows->order_uuid),
                '<a href="dealer/api_fif/order_data/'.$rows->order_uuid.'" class="btn btn-xs btn-info">Detail Order</a>
                 <a href="dealer/api_fif/order_status_one/'.$rows->order_uuid.'" class="btn btn-xs btn-warning">Cek Status PO</a>
                 '.$btn_kirim_dokumen.'
                 '.$btn_delivery.'
                 '.$btn_invoice.'
                '
            );     
        }
        $total_data = $this->m_fif->count_filter_order_fif_kasir($search, $id_dealer);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function cek_()
    {
    	$token =  get_token_fif();
    	log_r($token);
    // echo phpinfo();
    // log_r($this->session->userdata('group'));
	    
    }

    public function download_po($order_uuid)
    {
    	$this->load->helper('download');
    	$token =  get_token_fif();

    	$url = $this->baseUrl_inv."v2/po/file/".$order_uuid;

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 
		if (curl_errno($curl) == false) {
		# jika curl berhasil
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($http_code == 200) {
				  # http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
				// log_r("sedang mempersiapkan link download.. ");
				//Decode pdf content
				
				$data = base64_decode($resp);
				header('Content-Type: application/pdf');
				echo $data;

				// log_r($resp);
				// force_download($resp,NULL);
			} else {
				# jika curl error (contoh: request timeout)
				# Daftar kode error : https://curl.haxx.se/libcurl/c/libcurl-errors.html
				// echo "Error while sending request, reason:".curl_error($ch);
				log_r($resp);
				
			}
		} else {
			log_r("ada error tidak terduga ..");
		}
		// echo "<h3>Url :</h3>";
		// log_data($url);

		// echo "<h3>Hasil :</h3>";

		

		curl_close($curl); 
	    
    }

    public function update_no_po()
    {
    	$query = "
    	SELECT
			*
		FROM
			tr_fif_order_json_detail where CHAR_LENGTH( po_no ) = 11
    	";
    	foreach ($this->db->query($query)->result() as $row) {
    		$token =  get_token_fif();
			// Status Order EndPoint
			
			$sql = "SELECT data FROM tr_fif_accept WHERE data LIKE '%$row->order_uuid%' ORDER BY id_accept DESC";
			$resp = $this->db->query($sql)->row()->data;
			
			// log_r($resp);
			$status_order = json_decode($resp);
			$po_no = $status_order->object[0]->po_no; 
			log_data($po_no);
			$this->db->query("UPDATE tr_fif_order_json_detail SET po_no='$po_no' WHERE order_uuid='$row->order_uuid' and po_no is not null ");
			log_data($this->db->last_query());


    	}
    }
    
    

    public function cek_stat()
    {
    	$order_uuid = $this->input->get('id');
    	$token =  get_token_fif();
		// Status Order EndPoint
		
		$url = "https://restapi.fifgroup.co.id/fifport/order/status/order/".$order_uuid;

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 

		curl_close($curl); 
		echo $resp;
    }

	
	public function order_new()
	{
		$token =  get_token_fif();
		// New Order EndPoint
		$url = $this->baseUrl.'order/new';
		$no_spk = $this->input->get('id_spk');
		$m_fif = $this->m_fif->order_new($no_spk)->row();
		// log_r($m_fif);
		$data = array();

		if($this->m_fif->order_new($no_spk)->num_rows()>0){
			$pekerjaan_saat_ini = $m_fif->pekerjaan;
			if($m_fif->sub_pekerjaan !=''){

				if($m_fif->sub_pekerjaan == 69){
					$bpkb_occupation = "KARYAWAN SWASTA - PERTANIAN";
				}else if($m_fif->sub_pekerjaan == 95){
					$bpkb_occupation = "WIRASWASTA - PEDAGANG, PERTANIAN";
				}else{
					$bpkb_occupation =  get_data('ms_sub_pekerjaan','id_sub_pekerjaan',$m_fif->sub_pekerjaan,'sub_pekerjaan');
				}

				$pekerjaan_saat_ini = $m_fif->sub_pekerjaan;
			}else{
				if ($m_fif->pekerjaan == '2a') {
					$bpkb_occupation = "PEGAWAI SWASTA";
				} elseif ($m_fif->pekerjaan == '4a') {
					$bpkb_occupation = "WIRASWASTA/PEDAGANG";
				} else {
					$bpkb_occupation =  get_data('ms_pekerjaan','id_pekerjaan',$m_fif->pekerjaan,'pekerjaan');
				}
				$pekerjaan_saat_ini = $m_fif->pekerjaan;
			}
			
			$data = array(
				"oc_id"=> "SinarSentosa",
				"cust_nik"=> $m_fif->no_ktp,
				"cust_name"=> RemoveSpecialChar($m_fif->nama_konsumen),
				"birth_place"=> $m_fif->tempat_lahir,
				"birth_date"=> date('d/m/Y',strtotime($m_fif->tgl_lahir)),
				"cust_mother"=> RemoveSpecialChar($m_fif->nama_ibu),
				"comments" => $m_fif->keterangan,
				"addr_address"=> $m_fif->alamat,
				"addr_rt"=> $m_fif->rt,
				"addr_rw"=> $m_fif->rw,
				"addr_kel_code"=> $m_fif->id_kelurahan_kk,
				"dom_address"=> $m_fif->alamat,
				"dom_rt"=> $m_fif->rt,
				"dom_rw"=> $m_fif->rw,
				"dom_kel_code"=> $m_fif->id_kelurahan_kk,
				"cust_phone_area"=> "",
				"cust_phone"=> "",
				"cust_offphone_area"=> "",
				"cust_office_phone"=> "",
				"cust_office_ext"=> "",
				"cust_mobile_phone1"=> $m_fif->no_hp,
				"cust_mobile_phone2"=> "",
				"cust_email"=> "false",
				"cust_type"=> "I",
				"emer_mob_phone1"=> (strlen($m_fif->no_hp_2) < 10) ? $m_fif->no_hp : $m_fif->no_hp_2,
				"emer_mob_phone2"=> "",
				"platform_code"=> "K",
				"dealer_code"=> $m_fif->id_dealer,
				"house_stat_code"=> ($m_fif->status_rumah == 'Rumah Sendiri') ? '1' : ($m_fif->status_rumah == 'Rumah Orang Tua') ? '2' : '3',
				"marital_stat_code"=> $m_fif->status_pernikahan, //get_data('tr_cdb_kk','nik',$m_fif->no_ktp,'id_status_pernikahan'),
				"sex_code"=>$m_fif->jenis_kelamin, //($m_fif->jenis_kelamin == 'Wanita') ? 'W' : 'P',
				"occupation_code"=> $pekerjaan_saat_ini,
				"education_code"=> $m_fif->pendidikan,//get_data('tr_cdb_kk','nik',$m_fif->no_ktp,'id_pendidikan'),
				"promo_id"=> "false",
				"tenor"=> $m_fif->tenor,
				"buss_unit"=> "NMC",
				"dependents"=> "2",
				"cust_salary"=> $m_fif->penghasilan,
				"cust_monthly_expense"=> get_data('ms_pengeluaran_bulan','id_pengeluaran_bulan',$m_fif->pengeluaran_bulan,'nilai'),
				"prospect_no"=> $m_fif->id_prospek,
				"object_list" => [
					array(
						"seq_no"=> 1,
						"bpkb_name"=> $m_fif->nama_bpkb,
						"bpkb_nik"=> $m_fif->no_ktp_bpkb,
						"bpkb_occupation"=> $bpkb_occupation,
						"bpkb_addr"=> $m_fif->alamat_ktp_bpkb,
						"bpkb_zip_code"=> $m_fif->kode_pos_kk,
						"obj_code"=> $m_fif->id_tipe_kendaraan,
						"obj_colour"=> get_data('ms_warna','id_warna',$m_fif->id_warna,'warna'),
						"indent_status"=> "N",
						"obj_price"=> $m_fif->total_bayar,
						"obj_installment"=> $m_fif->angsuran,
						"obj_unit_dp"=> $m_fif->uang_muka,
						"obj_desc"=> get_data('ms_tipe_kendaraan','id_tipe_kendaraan',$m_fif->id_tipe_kendaraan,'tipe_ahm'),
						"obj_brand"=> "HONDA",
						"obj_type"=> "",
						"obj_model"=> "",
						"obj_kind"=> "",
						"new_used"=> "N",
						"obj_size"=> 0,
						"obj_year"=> 0,
						"obj_admin"=> 0

					)

				]
			);
			
			// log_r($data);
			$hasil = api_fif($token, 1, $url, $data);

			//simpan log
			$this->db->insert('tr_fif_log', array(
				'request' => json_encode($data),
				'link' => $url,
				'response' => $hasil,
				'status_error' => json_decode($hasil)->error
			));
			
			$hasil = json_decode($hasil);

			if ($hasil->error == false) {
				unset($hasil->data[0]->object);
				$hasil->data[0]->no_spk = $no_spk;

				//cek no uuid
				$cek_uuid = $this->db->get_where('tr_fif_order', array('order_uuid'=>$hasil->data[0]->order_uuid));
				if ($cek_uuid->num_rows() > 0) {
					// redirect('dealer/api_fif/index?page=all_order','refresh');
					$no_spk_i = $cek_uuid->row()->no_spk;
					$no_uuid_i = $cek_uuid->row()->order_uuid;
					$_SESSION['pesan'] 	= "No UUID sudah ada $no_uuid_i dengan SPK sebelumnya $no_spk_i  !";
					$_SESSION['tipe'] 	= "warning";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=push_order'>";
					exit();
				} else {
					$simpan = $this->db->insert('tr_fif_order', $hasil->data[0]);
					if ($simpan) {
						// redirect('dealer/api_fif/index?page=all_order','refresh');
						$_SESSION['pesan'] 	= "Berhasil dikirim !";
						$_SESSION['tipe'] 	= "success";

						$this->dokumen_upload($hasil->data[0]->order_uuid, $no_spk);

						echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
						exit();
					}
				}

				
			} else {
				// log_r($hasil->message);
				$_SESSION['pesan'] 	= $hasil->message;
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=push_order'>";
				exit();
			}
		}else{
			// log_r($hasil->message);
			$_SESSION['pesan'] 	= 'Gagal, Terjadi kesalahan. Silahkan cek kembali data SPK dan order survey leasing.';
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=push_order'>";
			exit();

		}
	}
	
	public function accept_status_order()
	{

// header('WWW-Authenticate: Basic realm="401 Test"');
// header('HTTP/1.1 401 Unauthorized');
// exit;
		$username = "fifport_api";
		$password = "#!3432021FIFap1";
	   // log_r($_SERVER);
	    if($_SERVER['REQUEST_METHOD'] == 'POST') {

	        if(isset($_SERVER['PHP_AUTH_USER'])) {
    	        $username_input = $_SERVER['PHP_AUTH_USER'];
    	        $password_input = $_SERVER['PHP_AUTH_PW'];
    	        if($username_input == $username && $password_input == $password) {
    	            $header = header('Content-Type:application/json');
            		$data = file_get_contents("php://input");
                    // $decoded_data = json_decode($data);
                    if($data != null) {
                        $this->db->insert('tr_fif_accept', array(
                        	'data'=>$data,
                        	'created_at' => get_waktu()
                        ));

                        $data_json = json_decode($data);
                        $this->db->insert('tr_fif_order_json', array(
                        	"order_uuid" => $data_json->order_uuid,
							"appl_no" => $data_json->appl_no,
							"order_status" => $data_json->order_status,
							"order_arc_date" => $data_json->order_arc_date,
							"order_arc_reason" => $data_json->order_arc_reason,
							"order_arc_sub_reason" => $data_json->order_arc_sub_reason,
							"tenor" => $data_json->tenor,
							"branch_id" => $data_json->branch_id,
							"pending_doc" => $data_json->pending_doc,
							"trf_amount" => $data_json->trf_amount
                        ));

                        $id_order_json = $this->db->insert_id();

                        $this->db->insert('tr_fif_order_json_detail', array(
                        	"id_order_json" => $id_order_json,
                        	"order_uuid" => $data_json->order_uuid,
                        	"seq_no" =>  $data_json->object[0]->seq_no,
							"po_no" =>  $data_json->object[0]->po_no,
							"po_seq_no" =>  $data_json->object[0]->po_seq_no,
							"po_status" =>  $data_json->object[0]->po_status,
							"po_cancel_reason" =>  $data_json->object[0]->po_cancel_reason,
							"po_cancel_sub_reason" =>  $data_json->object[0]->po_cancel_sub_reason,
							"po_date" =>  $data_json->object[0]->po_date,
							"inv_no" =>  $data_json->object[0]->inv_no,
							"inv_paid_status" =>  $data_json->object[0]->inv_paid_status,
							"inv_paid_date" =>  $data_json->object[0]->inv_paid_date,
							"delr_inv_no" =>  $data_json->object[0]->delr_inv_no,
							"obj_unit_dp" =>  $data_json->object[0]->obj_unit_dp,
							"obj_installment" =>  $data_json->object[0]->obj_installment
                        ));

                        echo json_encode(['error' => false, 'message' => null]);
                    } else {
                        echo json_encode(['error' => true, 'message' => "data request tidak boleh kosong"]);
                    }
    	        } else {
    	        	header('WWW-Authenticate: Basic realm="My Realm"');
	            	header('HTTP/1.0 401 Unauthorized');
    	            echo json_encode(['error' => true, 'message' => 'credential not found']);
    	        }
    	    } else {
    	    	header('WWW-Authenticate: Basic realm="My Realm"');
	            header('HTTP/1.0 401 Unauthorized');
    	        echo json_encode(['error' => true, 'message' => 'invalid credential']);
    	    }
	        
    		
	    } else {
	    	header("HTTP/1.1 500 Internal Server Error");
	        echo json_encode(['error' => true, 'message' => 'REQUEST_METHOD tidak di bolehkan']);
	    }

	}

	public function order_edit()
	{
		$token =  get_token_fif();

		$url = $this->baseUrl.'order/edit';

		$no_spk = $this->input->get('id_spk');
		$no_uuid = $this->db->get_where('tr_fif_order', array('no_spk'=>$no_spk));
		$m_fif = $this->m_fif->order_new($no_spk)->row();
		// log_r($m_fif);
		$data = array(
			"edit_code" => "",
			"order_uuid" => $no_uuid->row()->order_uuid,
			"new_data" => array(
				"oc_id"=> "SinarSentosa",
				"cust_nik"=> $m_fif->no_ktp,
				"cust_name"=> $m_fif->nama_konsumen,
				"birth_place"=> $m_fif->tempat_lahir,
				"birth_date"=> date('d/m/Y',strtotime($m_fif->tgl_lahir)),
				"cust_mother"=> $m_fif->nama_ibu,
				"addr_address"=> $m_fif->alamat,
				"addr_rt"=> $m_fif->rt,
				"addr_rw"=> $m_fif->rw,
				"addr_kel_code"=> $m_fif->id_kelurahan_kk,
				"dom_address"=> $m_fif->alamat,
				"dom_rt"=> $m_fif->rt,
				"dom_rw"=> $m_fif->rw,
				"dom_kel_code"=> $m_fif->id_kelurahan_kk,
				"cust_phone_area"=> "",
				"cust_phone"=> "",
				"cust_offphone_area"=> "",
				"cust_office_phone"=> "",
				"cust_office_ext"=> "",
				"cust_mobile_phone1"=> $m_fif->no_hp,
				"cust_mobile_phone2"=> "",
				"cust_email"=> "false",
				"cust_type"=> "I",
				"emer_mob_phone1"=> ($m_fif->no_hp_2 == '') ? $m_fif->no_hp : $m_fif->no_hp_2,
				"emer_mob_phone2"=> "",
				"platform_code"=> "K",
				"dealer_code"=> $m_fif->id_dealer,
				"house_stat_code"=> ($m_fif->status_rumah == 'Rumah Sendiri') ? '1' : ($m_fif->status_rumah == 'Rumah Orang Tua') ? '2' : '3',
				"marital_stat_code"=> get_data('tr_cdb_kk','nik',$m_fif->no_ktp,'id_status_pernikahan'),
				"sex_code"=> ($m_fif->jenis_kelamin == 'Wanita') ? 'W' : 'P',
				"occupation_code"=> '2',//$m_fif->pekerjaan,
				"education_code"=> get_data('tr_cdb_kk','nik',$m_fif->no_ktp,'id_pendidikan'),
				"promo_id"=> "false",
				"tenor"=> $m_fif->tenor,
				"buss_unit"=> "NMC",
				"dependents"=> "2",
				"cust_salary"=> '5000000',//$m_fif->penghasilan,
				"cust_monthly_expense"=> $m_fif->pengeluaran_bulan,
				"prospect_no"=> $m_fif->id_prospek,
				"object_list" => [
					array(
						"seq_no"=> 1,
						"bpkb_name"=> $m_fif->nama_bpkb,
						"bpkb_nik"=> $m_fif->no_ktp_bpkb,
						"bpkb_occupation"=> $m_fif->pekerjaan,
						"bpkb_addr"=> $m_fif->alamat_ktp_bpkb,
						"bpkb_zip_code"=> $m_fif->kode_pos_kk,
						"obj_code"=> $m_fif->id_tipe_kendaraan,
						"obj_colour"=> get_data('ms_warna','id_warna',$m_fif->id_warna,'warna'),
						"indent_status"=> "N",
						"obj_price"=> $m_fif->total_bayar,
						"obj_installment"=> $m_fif->angsuran,
						"obj_unit_dp"=> $m_fif->dp_stor,
						"obj_desc"=> get_data('ms_tipe_kendaraan','id_tipe_kendaraan',$m_fif->id_tipe_kendaraan,'tipe_ahm'),
						"obj_brand"=> "HONDA",
						"obj_type"=> "",
						"obj_model"=> "",
						"obj_kind"=> "",
						"new_used"=> "N",
						"obj_size"=> 0,
						"obj_year"=> 0,
						"obj_admin"=> 0

					)

				]
			)
		

		);
		$hasil = api_fif($token, 1, $url, $data);
		//simpan log
		$this->db->insert('tr_fif_log', array(
			'request' => json_encode($data),
			'link' => $url,
			'response' => $hasil,
			'status_error' => json_decode($hasil)->error
		));
		
		$hasil = json_decode($hasil);

		if ($hasil->error == false) {
			log_r($hasil->data[0]);

			unset($hasil->data[0]->object);
			$hasil->data[0]->no_spk = $no_spk;

			//cek no uuid
			$cek_uuid = $this->db->get_where('tr_fif_order', array('order_uuid'=>$hasil->data[0]->order_uuid));
			if ($cek_uuid->num_rows() > 0) {
				
			} else {
				
			}

			
		} else {
			log_r($hasil->message);
		}
	}

	public function dokumen_upload($order_uuid, $no_spk)
	{
		// $no_spk = $this->input->get('id');
		$nm_ktp = get_data('tr_spk','no_spk',$no_spk,'file_foto');
		$nm_kk = get_data('tr_spk','no_spk',$no_spk,'file_kk');
		$nm_ktp_2 = get_data('tr_spk','no_spk',$no_spk,'file_ktp_2');
		$token =  get_token_fif();
		$pathKTP ="./assets/panel/files/$nm_ktp";
		$pathKTP1 ="./assets/panel/files/$nm_ktp_2";
		$pathKK ="./assets/panel/files/$nm_kk";

		$ext_ktp = explode(".", $nm_ktp);
		$ext_ktp2 = explode(".", $nm_ktp_2);
		$ext_kk = explode(".", $nm_kk);

		  $filesFields = array(
		      'files' => array(
		          'content' => file_get_contents($pathKTP) ,
		          'name' =>  basename($pathKTP),
		      ), 
		  );
		  $filesFields2 = array(
		      'files' => array(
		          'content' => file_get_contents($pathKTP1) ,
		          'name' =>  basename($pathKTP1),
		      ), 
		  );
		  $filesFields3 = array(
		      'files' => array(
		          'content' => file_get_contents($pathKK) ,
		          'name' =>  basename($pathKK),
		      ), 
		  );
		  $postFields = array("docCategory"=>"DC1,DC2,DC3","order_uuid" => "$order_uuid");
		  $formData = '';
		  $nl = "\r\n";
		  $boundary =uniqid();
		  $delimiter = '-------------' . $boundary;
		  
		  foreach ($postFields as $name => $content) {
		          $formData .= "--" . $delimiter . $nl
		              . 'Content-Disposition: form-data; name="' . $name . "\"".$nl.$nl
		              . $content . $nl;
		      }

		      foreach ($filesFields as $name => $content) {
		          $formData .= "--" . $delimiter . $nl
		              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
		              . 'Content-Type: image/'.$ext_ktp[1].$nl
		              ;

		          $formData .= $nl;
		          $formData .= $content['content'] . $nl;
		      }

		      foreach ($filesFields2 as $name => $content) {
		          $formData .= "--" . $delimiter . $nl
		              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
		              . 'Content-Type: image/'.$ext_ktp2[1].$nl
		              ;

		          $formData .= $nl;
		          $formData .= $content['content'] . $nl;
		      }


		      foreach ($filesFields3 as $name => $content) {
		          $formData .= "--" . $delimiter . $nl
		              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
		              . 'Content-Type: image/'.$ext_kk[1].$nl
		              ;

		          $formData .= $nl;
		          $formData .= $content['content'] . $nl;
		      }
		    
		  $formData .= "--" . $delimiter . "--\r\n";
		  $ch1 = curl_init();
		  // log_r($formData);

		  curl_setopt($ch1, CURLOPT_URL, 'https://restapi.fifgroup.co.id/fifport/order/document/upload');
		  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($ch1, CURLOPT_POSTFIELDS, $formData);
		  curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
		  curl_setopt($ch1, CURLOPT_ENCODING, "");
		  curl_setopt($ch1, CURLOPT_POST, 1);

		  $headers = array();
		  $headers[] = 'Authorization: Bearer '.$token;
		  $headers[] = "Content-Type: multipart/form-data; boundary=" . $delimiter;
		  curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
		  curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
		  $resp = curl_exec($ch1);


		  # validasi curl request tidak error
	if (curl_errno($ch1) == false) {
		# jika curl berhasil
		$http_code = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
		if ($http_code == 200) {
		  # http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
			

			//simpan log
			$this->db->insert('tr_fif_log', array(
				'request' => $no_spk,
				'link' => "https://restapi.fifgroup.co.id/fifport/order/document/upload",
				'response' => $resp,
				'status_error' => json_decode($resp)->error
			));
			
			$hasil = json_decode($resp);

			if ($hasil->error == false) {

				$this->db->where('no_spk', $no_spk);
    			$this->db->where('order_uuid', $rows->order_uuid);
				$this->db->update('tr_fif_order', ['kirim_dokumen' => 'y']);

				$_SESSION['pesan'] 	= "Dokumen No SPK $no_spk Berhasil dikirim !";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();

				
			} else {
				// log_r($hasil->message);
				$_SESSION['pesan'] 	= $hasil->message;
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=push_order'>";
				exit();
			}

		} else {
		  # selain itu request gagal (contoh: error 404 page not found)
		  // echo 'Error HTTP Code : '.$http_code."\n";
			
			log_data($resp);
		}
	} else {
		# jika curl error (contoh: request timeout)
		# Daftar kode error : https://curl.haxx.se/libcurl/c/libcurl-errors.html
		// echo "Error while sending request, reason:".curl_error($ch);
	}

	# tutup CURL
	curl_close($ch1);


		// // log_r($files->name);

		// // if ($_FILES) {
		// // 	// log_r($_FILES['file_doc	']['tmp_name']);

		// 	$token =  get_token_fif_tes();
		// 	// log_r($token);
		
		// 	// $url = $this->baseUrl.'order/document/upload';
		// 	$url = $this->baseUrl;

		// 	$data = array(
		// 		"docCategory" => "KTP",
		// 		"files" =>	array(
		// 				         'content' =>  $files
		// 				    ),
		// 		"order_uuid" => "909cc05d-6176-4d9c-8e65-4a7947784a7b"
		// 	);

		// 	echo "<h3>Url Dokumen upload:</h3>";
		// 	log_data($url);


		// 	echo "<h3>Parameter data yang dikirim :</h3>";
		// 	log_data($data);

		// 	echo "<h3>Hasil :</h3>";
		// 	log_r(api_fif($token, 1, $url, $data, TRUE));

		// // } else {
		// // 	$this->load->view('upload_doc_fif');
		// // }
	}

	public function order_data($order_uuid)
	{
		$token =  get_token_fif();
		// Status Order EndPoint
		$url = $this->baseUrl."order/dataorder/SinarSentosa/".$order_uuid;

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 
		

		// echo "<h3>Url :</h3>";
		// log_data($url);

		// echo "<h3>Hasil :</h3>";

		curl_close($curl); 
		// echo $resp;
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['hasil']	= $resp;
		$view = 'dealer/fif/detail_order';
		$this->template($data,$view);
	}

	public function order_status($dealer_id='')
	{
		$token =  get_token_fif();
		// Status Order EndPoint
		if ($dealer_id != '') {
			$url = $this->baseUrl."order/status/SinarSentosa?dateFrom=20/06/2020&dateTo=20/07/2020&dealerId=".$dealer_id;
		} else {
			$url = $this->baseUrl."order/status/SinarSentosa?dateFrom=20/06/2020&dateTo=20/07/2020";
		}

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 
		

		echo "<h3>Url :</h3>";
		log_data($url);

		echo "<h3>Hasil :</h3>";

		curl_close($curl); 
		echo $resp;
	}

	
	public function order_status_one($orderUuid)
	{
		$token =  get_token_fif();
		// Status Order EndPoint

		$disburts= $this->db->query("SELECT so.tgl_cetak_invoice2 ,so.created_at as send_invoice from tr_fif_order fif 
		left join tr_sales_order so on so.no_spk = fif.no_spk 
		WHERE fif.order_uuid ='$orderUuid'
		group by fif.no_spk ")->row();

		$data['disburst'] =$disburts->send_invoice;
		
		$url = $this->baseUrl."order/status/order/".$orderUuid;

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 
	

		curl_close($curl); 
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['hasil']	= $resp;
		$data['order_id'] = $orderUuid;
		$view = 'dealer/fif/status_order';
		$this->template($data,$view);
	}





	public function delivery($order_uuid)
	{
		$no_spk = $this->input->get('id');

		$so = $this->m_fif->get_sales_order($no_spk);
		if ($so->num_rows() == 0) {
			$_SESSION['pesan'] 	= "SPK $no_spk ini belum di sales order !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
			exit();
		}

		$token =  get_token_fif();
		// Ready to Deliver Endpoint
		$url = $this->baseUrl."order/ready";
		$data = array(
			"objt_seqn" => [1],
			"oc_id" => "SinarSentosa",
			"order_uuid" => $order_uuid
		);
		
		$hasil = api_fif($token, 1, $url, $data);

		//simpan log
		$this->db->insert('tr_fif_log', array(
			'request' => json_encode($data),
			'link' => $url,
			'response' => $hasil,
			'status_error' => json_decode($hasil)->error
		));
		
		$hasil = json_decode($hasil);

		if ($hasil->error == false) {

			$this->db->where('no_spk', $no_spk);
			$this->db->where('order_uuid', $order_uuid);
			$this->db->update('tr_fif_order', ['delivery' => 'y']);

			// $_SESSION['pesan'] 	= "Delivery No SPK $no_spk Berhasil dikirim !";
			// $_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
			echo $this->alert('success',"Delivery No SPK $no_spk Berhasil dikirim !");
			exit();

			
		} else {
			// log_r($hasil->message);
			// $_SESSION['pesan'] 	= $hasil->message;
			// $_SESSION['tipe'] 	= "warning";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
			echo $this->alert('danger',$hasil->message);
			exit();
		}
	}

	public function order_cancel($order_uuid)
	{
		$token =  get_token_fif();

		$url = $this->baseUrl.'order/cancel';

		$data = array(
			"cancel_code" => "C01",
			"order_uuid" => $order_uuid,

		);

		$hasil = api_fif($token, 1, $url, $data);
		//simpan log
		$this->db->insert('tr_fif_log', array(
			'request' => json_encode($data),
			'link' => $url,
			'response' => $hasil,
			'status_error' => json_decode($hasil)->error
		));

		// echo "<h3>Url :</h3>";
		// log_data($url);


		// echo "<h3>Parameter data yang dikirim :</h3>";
		// log_data($data);

		// echo "<h3>Hasil :</h3>";
		echo $hasil;
	}


	/* Invoice API  */

	// 6.1. Receive Invoice & BASTK (post)

	public function kirim_invoice()
	{
		$order_uuid = $this->input->get('order_uuid');
		$no_spk = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		$so = $this->m_fif->get_sales_order($no_spk)->row();

		$warna = $this->m_fif->get_warna($so->no_mesin)->row()->warna_samsat;

		$status_order = json_decode($this->m_fif->get_detail_order($order_uuid));
// 		log_r($status_order);
		$trf_amount = $status_order->data[0]->trf_amount;
		$po_no = $status_order->data[0]->object[0]->po_no;

		$token =  get_token_fif();
		// Create Invoice EndPoint
		
		$url = $this->baseUrl_inv."v1/upload/data/";
		$data = array(
			"chan_inv_no" => $so->no_invoice,
			"chan_inv_date" => date('d/m/Y',strtotime($so->tgl_cetak_invoice)),
			"chan_inv_amt" => $trf_amount,
			"supp_code" => $id_dealer, // id dealer
			"source_input" => "J",
			"oc_id" => "SinarSentosa",
			"po_no" => $po_no,
			"noka" => "MH1".$so->no_rangka,
			"nosin" => $so->no_mesin,
			"warna" => $warna,
			"obj_tahun" => $so->tahun_produksi,
			"bast_no" => $so->no_bastk,
			"bast_date" => date('d/m/Y',strtotime($so->tgl_bastk))

		);

		$hasil = api_fif($token, 1, $url, $data);

		//simpan log
		$this->db->insert('tr_fif_log', array(
			'request' => json_encode($data),
			'link' => $url,
			'response' => $hasil,
			'status_error' => json_decode($hasil)->error
		));
		
		$hasil = json_decode($hasil);

		if ($hasil->error == false) {

			if ($hasil->inv_uuid == '' OR $hasil->inv_uuid == null) {
				$_SESSION['pesan'] 	= "No Invoice Gagal didapatkan, silahkan ulangi lagi";
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$this->db->where('no_spk', $no_spk);
			$this->db->where('order_uuid', $order_uuid);
			$this->db->update('tr_fif_order', ['kirim_invoice' => 'y', 'inv_uuid'=>$hasil->inv_uuid]);

			$_SESSION['pesan'] 	= "Invoice No SPK $no_spk Berhasil dikirim !";
			$_SESSION['tipe'] 	= "success";
			redirect("dealer/api_fif/upload_dokumen_invoice?id=".$no_spk."&inv_uuid=".$hasil->inv_uuid."&order_uuid=".$order_uuid,'refresh');
			exit();

			
		} else {
			// log_r($hasil->message);
			$_SESSION['pesan'] 	= $hasil->message;
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
			exit();
		}
	}

	// 6.2. Revisi Invoice (post)
	public function revisi_invoice()
	{
		$token =  get_token_fif();
		// Create Invoice EndPoint
		
		$url = $this->baseUrl_inv."v1/upload/data/revise";
		$data = array(
			"inv_data"=> [
				"chan_inv_no" => "1234-2020-D022-IV",
				"chan_inv_date" => "05/10/2021",
				"chan_inv_amt" => 17695000,
				"supp_code" => "77", // id_dealer
				"source_input" => "J",
				"oc_id" => "SinarSentosa",
				"po_no" => "2120021PO00006175",
				"noka" => "MH1JMXX131MKN",
				"nosin" => "JBP1E1851941",
				"warna" => "PUTIH HITAM",
				"obj_tahun" => "2019",
				"bast_no" => "0008/SJ/D022/V/2020",
				"bast_date" => "05/10/2021"
			],
			"inv_uuid"=>"46509663-7077-4d2a-9099-88b4eb804f4e"

		);

		echo "<h3>Url :</h3>";
		log_data($url);


		echo "<h3>Parameter data yang dikirim :</h3>";
		log_data(json_encode($data));

		echo "<h3>Hasil :</h3>";
		echo api_fif($token, 1, $url, $data);
	}

	// 6.4.1.Upload Document Invoice (post)
	public function upload_dokumen_invoice()
	{
		$order_uuid = $this->input->get('order_uuid');
		$inv_uuid = $this->input->get('inv_uuid');
		$no_spk = $this->input->get('id');
		$status_order = json_decode($this->m_fif->get_detail_order($order_uuid));
		$po_no = $status_order->data[0]->object[0]->po_no;
		$id_dealer = $this->m_admin->cari_dealer();


		if ($_FILES) {

			// log_r($_FILES);

			$upload_serah_terima =$this->m_fif->upload_dokument($id_dealer.'_serah_terima', 'foto_serah_terima');
			if ($upload_serah_terima['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_serah_terima['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_bast =$this->m_fif->upload_dokument($id_dealer.'_bast', 'bast');
			if ($upload_bast['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_bast['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_no_rangka =$this->m_fif->upload_dokument($id_dealer.'_no_rangka', 'no_rangka');
			if ($upload_no_rangka['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_no_rangka['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_no_mesin =$this->m_fif->upload_dokument($id_dealer.'_no_mesin', 'no_mesin');
			if ($upload_no_mesin['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_no_mesin['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_ktp_penerima =$this->m_fif->upload_dokument($id_dealer.'_ktp_penerima', 'ktp_penerima_unit');
			if ($upload_ktp_penerima['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_ktp_penerima['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}
			
			$token =  get_token_fif();
			$lokasi = "./uploads/invoice_fif/";
			$serah_terima = $upload_serah_terima['file']['file_name'];
			$bast = $upload_bast['file']['file_name'];
			$no_rangka = $upload_no_rangka['file']['file_name'];
			$no_mesin = $upload_no_mesin['file']['file_name'];
			$ktp_penerima = $upload_ktp_penerima['file']['file_name'];
			

			$ext_serah_terima = $_FILES['foto_serah_terima']['type'];
			$ext_bast = $_FILES['bast']['type'];
			$ext_no_rangka = $_FILES['no_rangka']['type'];
			$ext_no_mesin = $_FILES['no_mesin']['type'];
			$ext_ktp_penerima = $_FILES['ktp_penerima_unit']['type'];

			  $filesFields = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$serah_terima) ,
			          'name' =>  basename($lokasi.$serah_terima),
			      ), 
			  );
			  $filesFields2 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$bast) ,
			          'name' =>  basename($lokasi.$bast),
			      ), 
			  );
			  $filesFields3 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$no_rangka) ,
			          'name' =>  basename($lokasi.$no_rangka),
			      ), 
			  );
			  $filesFields4 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$no_mesin) ,
			          'name' =>  basename($lokasi.$no_mesin),
			      ), 
			  );
			  $filesFields5 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$ktp_penerima) ,
			          'name' =>  basename($lokasi.$ktp_penerima),
			      ), 
			  );
			  $postFields = array(
			  	"doc_category"=>"DO25,DK3,DO13,DO12,DC36",
			  	"inv_uuid" => $inv_uuid,
			  	"oc_id" => "SinarSentosa",
			  	"po_no" => $po_no,
			  	"source_input" => "J",
			  );
			  $formData = '';
			  $nl = "\r\n";
			  $boundary =uniqid();
			  $delimiter = '-------------' . $boundary;
			  
			  foreach ($postFields as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . $name . "\"".$nl.$nl
			              . $content . $nl;
			      }

			      foreach ($filesFields as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_serah_terima[1].$nl
			              . 'Content-Type: '.$ext_serah_terima.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }

			      foreach ($filesFields2 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_bast[1].$nl
			              . 'Content-Type: '.$ext_bast.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }


			      foreach ($filesFields3 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_no_rangka[1].$nl
			              . 'Content-Type: '.$ext_no_rangka.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }

			      foreach ($filesFields4 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_no_mesin[1].$nl
			              . 'Content-Type: '.$ext_no_mesin.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }

			      foreach ($filesFields5 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_ktp_penerima[1].$nl
			              . 'Content-Type: '.$ext_ktp_penerima.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }
			    
			  $formData .= "--" . $delimiter . "--\r\n";
			  $ch1 = curl_init();
			  // log_r($formData);

			  curl_setopt($ch1, CURLOPT_URL, $this->baseUrl_inv.'v1/upload/document/');
			  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			  curl_setopt($ch1, CURLOPT_POSTFIELDS, $formData);
			  curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
			  curl_setopt($ch1, CURLOPT_ENCODING, "");
			  curl_setopt($ch1, CURLOPT_POST, 1);

			  $headers = array();
			  $headers[] = 'Authorization: Bearer '.$token;
			  $headers[] = "Content-Type: multipart/form-data; boundary=" . $delimiter;
			  curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
			  curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
			  $resp = curl_exec($ch1);


			  # validasi curl request tidak error
			if (curl_errno($ch1) == false) {
				# jika curl berhasil
				$http_code = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
				if ($http_code == 200) {
				  # http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
					

					//simpan log
					$this->db->insert('tr_fif_log', array(
						'request' => $no_spk,
						'link' => $this->baseUrl_inv.'v1/upload/document/',
						'response' => $resp,
						'status_error' => json_decode($resp)->error
					));

					// log_r($resp);
					
					$hasil = json_decode($resp);

					if ($hasil->error == false) {

						$this->db->where('no_spk', $no_spk);
						$this->db->where('order_uuid', $order_uuid);
						$this->db->update('tr_fif_order',[
							'kirim_dokumen_invoice' => 'y',
							'tgl_kirim_invoice' => date('Y-m-d H:i:s')  
						]);

						$_SESSION['pesan'] 	= "Dokumen Invoice No SPK $no_spk Berhasil dikirim !";
						$_SESSION['tipe'] 	= "success";
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
						exit();

						
					} else {
						// log_r($hasil->message);
						$_SESSION['pesan'] 	= $hasil->message;
						$_SESSION['tipe'] 	= "warning";
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
						exit();
					}

				} else {
				  # selain itu request gagal (contoh: error 404 page not found)
				  // echo 'Error HTTP Code : '.$http_code."\n";
					
					log_data($resp);
					log_data($postFields);
				}
			} else {
				# jika curl error (contoh: request timeout)
				# Daftar kode error : https://curl.haxx.se/libcurl/c/libcurl-errors.html
				echo "Error while sending request, reason:".curl_error($ch);
			}

			# tutup CURL
			curl_close($ch1);

			// }

		} else {

			$data['isi']    = $this->page;		
			$data['title']	= $this->title;
			$view = 'dealer/fif/upload_dokumen_invoice';
			$this->template($data,$view);
			
		}
		
		
	}


	public function testing_upload_dokumen_invoice()
	{
		$order_uuid = '57d41599-a918-40ba-ba3d-3bf61651770e';
		$inv_uuid ='c37c1728-0a36-42fb-acf9-7d29a66ecd0e';

		$no_spk = $this->input->get('id');
		$status_order = json_decode($this->m_fif->get_detail_order($order_uuid));
		$po_no = $status_order->data[0]->object[0]->po_no;
		$id_dealer = $this->m_admin->cari_dealer();

		var_dump($status_order);
		die();


		if ($_FILES) {
			// log_r($_FILES);
			$upload_serah_terima =$this->m_fif->upload_dokument($id_dealer.'_serah_terima', 'foto_serah_terima');
			if ($upload_serah_terima['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_serah_terima['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_bast =$this->m_fif->upload_dokument($id_dealer.'_bast', 'bast');
			if ($upload_bast['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_bast['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_no_rangka =$this->m_fif->upload_dokument($id_dealer.'_no_rangka', 'no_rangka');
			if ($upload_no_rangka['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_no_rangka['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_no_mesin =$this->m_fif->upload_dokument($id_dealer.'_no_mesin', 'no_mesin');
			if ($upload_no_mesin['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_no_mesin['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}

			$upload_ktp_penerima =$this->m_fif->upload_dokument($id_dealer.'_ktp_penerima', 'ktp_penerima_unit');
			if ($upload_ktp_penerima['result'] == 'failed') {
				$_SESSION['pesan'] 	= $upload_ktp_penerima['error'];
				$_SESSION['tipe'] 	= "warning";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
				exit();
			}
			
			$token =  get_token_fif();
			$lokasi = "./uploads/invoice_fif/";
			$serah_terima = $upload_serah_terima['file']['file_name'];
			$bast = $upload_bast['file']['file_name'];
			$no_rangka = $upload_no_rangka['file']['file_name'];
			$no_mesin = $upload_no_mesin['file']['file_name'];
			$ktp_penerima = $upload_ktp_penerima['file']['file_name'];
			

			$ext_serah_terima = $_FILES['foto_serah_terima']['type'];
			$ext_bast = $_FILES['bast']['type'];
			$ext_no_rangka = $_FILES['no_rangka']['type'];
			$ext_no_mesin = $_FILES['no_mesin']['type'];
			$ext_ktp_penerima = $_FILES['ktp_penerima_unit']['type'];

			  $filesFields = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$serah_terima) ,
			          'name' =>  basename($lokasi.$serah_terima),
			      ), 
			  );
			  $filesFields2 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$bast) ,
			          'name' =>  basename($lokasi.$bast),
			      ), 
			  );
			  $filesFields3 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$no_rangka) ,
			          'name' =>  basename($lokasi.$no_rangka),
			      ), 
			  );
			  $filesFields4 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$no_mesin) ,
			          'name' =>  basename($lokasi.$no_mesin),
			      ), 
			  );
			  $filesFields5 = array(
			      'files' => array(
			          'content' => file_get_contents($lokasi.$ktp_penerima) ,
			          'name' =>  basename($lokasi.$ktp_penerima),
			      ), 
			  );
			  $postFields = array(
			  	"doc_category"=>"DO25,DK3,DO13,DO12,DC36",
			  	"inv_uuid" => $inv_uuid,
			  	"oc_id" => "SinarSentosa",
			  	"po_no" => $po_no,
			  	"source_input" => "J",
			  );
			  $formData = '';
			  $nl = "\r\n";
			  $boundary =uniqid();
			  $delimiter = '-------------' . $boundary;
			  
			  foreach ($postFields as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . $name . "\"".$nl.$nl
			              . $content . $nl;
			      }

			      foreach ($filesFields as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_serah_terima[1].$nl
			              . 'Content-Type: '.$ext_serah_terima.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }

			      foreach ($filesFields2 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_bast[1].$nl
			              . 'Content-Type: '.$ext_bast.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }


			      foreach ($filesFields3 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_no_rangka[1].$nl
			              . 'Content-Type: '.$ext_no_rangka.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }

			      foreach ($filesFields4 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_no_mesin[1].$nl
			              . 'Content-Type: '.$ext_no_mesin.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }

			      foreach ($filesFields5 as $name => $content) {
			          $formData .= "--" . $delimiter . $nl
			              . 'Content-Disposition: form-data; name="' . 'files' . '"; filename=' . $content['name'] . $nl
			              // . 'Content-Type: image/'.$ext_ktp_penerima[1].$nl
			              . 'Content-Type: '.$ext_ktp_penerima.$nl
			              ;

			          $formData .= $nl;
			          $formData .= $content['content'] . $nl;
			      }
			    
			  $formData .= "--" . $delimiter . "--\r\n";
			  $ch1 = curl_init();
			  // log_r($formData);

			  curl_setopt($ch1, CURLOPT_URL, $this->baseUrl_inv.'v1/upload/document/');
			  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			  curl_setopt($ch1, CURLOPT_POSTFIELDS, $formData);
			  curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
			  curl_setopt($ch1, CURLOPT_ENCODING, "");
			  curl_setopt($ch1, CURLOPT_POST, 1);

			  $headers = array();
			  $headers[] = 'Authorization: Bearer '.$token;
			  $headers[] = "Content-Type: multipart/form-data; boundary=" . $delimiter;
			  curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
			  curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
			  $resp = curl_exec($ch1);


			  # validasi curl request tidak error
			if (curl_errno($ch1) == false) {
				# jika curl berhasil
				$http_code = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
				if ($http_code == 200) {
				  # http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
					

					//simpan log
					$this->db->insert('tr_fif_log', array(
						'request' => $no_spk,
						'link' => $this->baseUrl_inv.'v1/upload/document/',
						'response' => $resp,
						'status_error' => json_decode($resp)->error
					));

					// log_r($resp);
					
					$hasil = json_decode($resp);

					if ($hasil->error == false) {

						$this->db->where('no_spk', $no_spk);
						$this->db->update('tr_fif_order', ['kirim_dokumen_invoice' => 'y']);

						$_SESSION['pesan'] 	= "Dokumen Invoice No SPK $no_spk Berhasil dikirim !";
						$_SESSION['tipe'] 	= "success";
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
						exit();

						
					} else {
						// log_r($hasil->message);
						$_SESSION['pesan'] 	= $hasil->message;
						$_SESSION['tipe'] 	= "warning";
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/api_fif/index?page=all_order'>";
						exit();
					}

				} else {
				  # selain itu request gagal (contoh: error 404 page not found)
				  // echo 'Error HTTP Code : '.$http_code."\n";
					
					log_data($resp);
					log_data($postFields);
				}
			} else {
				# jika curl error (contoh: request timeout)
				# Daftar kode error : https://curl.haxx.se/libcurl/c/libcurl-errors.html
				echo "Error while sending request, reason:".curl_error($ch);
			}

			# tutup CURL
			curl_close($ch1);

			// }

		} else {

			$data['isi']    = $this->page;		
			$data['title']	= $this->title;
			$view = 'dealer/fif/upload_dokumen_invoice';
			$this->template($data,$view);
			
		}
		
		
	}


	//6.4.2.Upload Document Invoice single (post)
	public function upload_dokumen_invoice_single()
	{
		// code...
	}

	//6.5. Invoice Status by Invoice UUID (get)
	public function get_status_invoice_byid($inv_uuid)
	{
		$token =  get_token_fif();
		$url = $this->baseUrl_inv.'/v1/status/invoice/'.$inv_uuid;

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 
		

		curl_close($curl); 
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['hasil']	= $resp;
		log_r($resp);
		$view = 'dealer/fif/status_invoice';
		$this->template($data,$view);
	}

	//6.6. Invoice Status by PO Number (get)
	public function get_status_invoice_bypo($po_no)
	{
		// code...
	}


	/* Belum dipakai  */

	public function create_invoice()
	{
		/* Request fifinvoice rest(Disburse Scoope) */

		$token =  get_token_fif();
		// Create Invoice EndPoint
		$baseUrl = "https://portf-api.fifgroup.co.id/fifinvoice/";
		$url = $baseUrl."v1/upload/data";
		$data = array(
			"chan_inv_no" => "1234-2020-D022-IV",
			"chan_inv_date" => "09/05/2020",
			"chan_inv_amt" => 14745000,
			"supp_code" => "A09",
			"source_input" => "J",
			"oc_id" => " SinarSentosa ",
			"po_no" => "1010320PO00090987",
			"noka" => "MH1JMXX131MKN",
			"nosin" => "JM31E342XXV51",
			"warna" => "PUTIH HITAM",
			"bast_no" => "0008/SJ/D022/V/2020",
			"bast_date" => "09/05/2020"

		);

		echo "<h3>Url :</h3>";
		log_data($url);


		echo "<h3>Parameter data yang dikirim :</h3>";
		log_data($data);

		echo "<h3>Hasil :</h3>";
		echo api_fif($token, 1, $url, $data);

	}

	public function status_disburse()
	{
		$token =  get_token_fif();
		// Send Dokumen Invoice ( UPLOAD FILE )
		$baseUrl = "https://portf-api.fifgroup.co.id/fifinvoice/";
		// $url = $baseUrl."v1/upload/document/single";

		// Status Disburse
		$url = $baseUrl."v1/status/J/SinarSentosa?from_date=20/06/2020&to_date=20/07/2020";

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 
		

		echo "<h3>Url :</h3>";
		log_data($url);

		echo "<h3>Hasil :</h3>";

		curl_close($curl); 
		echo $resp;
	}

	public function alert($tipe,$pesan)
	{
		return '
		<div class="alert alert-'.$tipe.' alert-dismissable">
            <strong>'.$pesan.'</strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
		';
	}

	
}