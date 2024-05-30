<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_claim_dealer extends CI_Controller {

    var $tables =   "tr_claim_sales_program_payment_generate";	
	var $tabel2 = 	"tr_claim_sales_program_payment_generate_detail";
	var $folder =   "h1";
	var $page	=	"pembayaran_claim_dealer";
    var $pk     =   "id_claim_generate_payment";
    var $title  =   "Pembayaran Claim Dealer";
	

	public function __construct()
	{		 
		
		parent::__construct();
		 
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('pembayaran_claim_dealer_helper');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_pembayaran_claim_dealer');	
		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');

		$this->load->library('pdf');		

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
		$data['isi']      				 = $this->page;		
		$data['title']	   				 = $this->title;															
		$data['set']					 = "view";
		$status = '';
		$data['pembayaran_claim_dealer'] =  $this->m_pembayaran_claim_dealer->get_pembayaran_claim_dealer($status)->result();
		$this->template($data);			
	}


	public function fetch_data_claim_md_internal_payment()
	{
		$list = $this->m_claim_md_internal_payment_datatables->get_datatables();
		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {       

			  if (!empty($row->id_program_md)) {
				$button_id_program =" <a href='h1/claim_md_internal_payment/detail?id=$row->id_program_md' class='btn btn-primary btn-sm btn-flat'><i class='fa fa-eye'></i></a>";
			}else{
				$button_id_program = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }
			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $row->id_program_md;
			$rows[] = $row->judul_kegiatan;
			$rows[] = $row->periode_awal;
			$rows[] = $row->periode_akhir;
			$rows[] = $row->total_approve;
			$rows[] = $row->kontribusi_ahm;
			$rows[] = $row->kontribusi_md;
			$rows[] = $row->kontribusi_dealer;
			$rows[] = $button_id_program;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_claim_md_internal_payment_datatables->count_all(),
			"recordsFiltered" => $this->m_claim_md_internal_payment_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function add()
	{				
		$data['isi']        = $this->page;		
		$data['title']	    = $this->title;															
		$data['set']		= "add";
		$data['jenis_sales_program_manual'] 	=  $this->db->query("SELECT ps.id_kategory ,jsp.id_jenis_sales_program ,jsp.jenis_sales_program  from ms_jenis_sales_program jsp inner join ms_program_subcategory ps on jsp.id_sub_category =ps.id_subcategory ")->result();
		// $data['dealer'] 						=  $this->db->query("SELECT id_dealer, kode_dealer_ahm, nama_dealer from ms_dealer md WHERE active='1' and h1 ='1' group by kode_dealer_ahm order by nama_dealer ASC")->result();
		$data['dealer'] 						=  $this->db->query("SELECT DISTINCT cd.id_dealer,md.kode_dealer_ahm, md.nama_dealer  from tr_claim_dealer cd  
		inner join ms_dealer md on cd.id_dealer = md.id_dealer 
		WHERE cd.send_dealer is not null order by md.nama_dealer ASC")->result();
		
	
		$data['juklak'] 						=  $this->db->query("SELECT DISTINCT no_juklak_md FROM tr_sales_program")->result();
		$data['sales_program'] 					=  $this->db->query("SELECT sp.id_program_md,sp.id_program_ahm, sp.judul_kegiatan,jsp.jenis_sales_program,sp.periode_awal ,sp.periode_akhir from tr_sales_program sp left join ms_jenis_sales_program  jsp on sp.id_jenis_sales_program=jsp.id_jenis_sales_program
		join tr_claim_dealer cd on cd.id_program_md = sp.id_program_md 
		where cd.send_dealer_status is not null
		and cd.approve_from_dealer is not null
		group by sp.id_program_md ")->result();

		$data['bank'] 							=  $this->db->query("SELECT id_bank, bank  FROM ms_bank mb  order by bank asc")->result();
		$generated= $this->input->post('generate');
		
		if(!empty($generated)){
			
			$get_year = date('Y');
			$string_date = $get_year."-m-30";
			$get_today =  date($string_date);
			$get_past_month = date($get_year.'-m-01', strtotime(' - 10 months'));
			$data['sales_program_modal'] 			= $this->m_pembayaran_claim_dealer->get_sales_program_modal($get_past_month,$get_today)->result();
			$dealer        = $data['id_dealer'] = $this->input->post('id_dealer');
			$no_sales_id   = $data['no_sales_id'] = $this->input->post('no_sales_id');
			$tipe_program  = $data['tipe_program'] = $this->input->post('tipe_program');
			$awal          = $data['tanggal_awal'] = $this->input->post('tanggal_awal');
			$akhir   	   = $data['tanggal_akhir'] = $this->input->post('tanggal_akhir');
			$juklak        = $data['no_juklak'] = $this->input->post('no_juklak');
			$id_bank       = $data['id_bank'] = $this->input->post('id_bank');
			$data['setting_ppn']  = $this->input->post('setting_ppn');
			$data['select_ppn_table'] = $this->m_pembayaran_claim_dealer->get_select_ppn_table($tipe_program)->row();
			$data['temp_data'] = $this->m_pembayaran_claim_dealer->get($dealer,$no_sales_id,$tipe_program,$awal,$akhir,$juklak);
			$data['set_generate'] = 'set_generate';
		}
	
		$this->template($data);			
	}

	public function get_manual_ajax(){

		$query_dealer = $this->input->get('dealer');
		$query_sales = $this->input->get('sales');
		$ppn_check = $this->input->get('ppn');

		$data['detail'] =  $this->m_pembayaran_claim_dealer->get_manual_ajax($query_dealer,$query_sales,$ppn_check)->result();
			$output=NULL;
			foreach ($data['detail'] as $row) {

				if ($ppn_check == 1){
					$ppn = 'Include PPN';
					$ppn_value = 1;

					$get_kontribusi_ahm  = $row->kontribusi_ahm; 
					$get_kontribusi_md	 = $row->kontribusi_md; 
					$get_kontribusi_d    = $row->kontribusi_dealer;
					$get_total_kontribusi_ahm    = $row->total_kontribusi_ahm;
					$get_total_kontribusi_md    = $row->total_kontribusi_md; 
					$get_total_kontribusi_d    = $row->total_kontribusi_d; 

				}else if ($ppn_check == 0){
					$ppn = 'Not Include PPN';
					$ppn_value = 0;
					$check_program = getPPN(1.1, $row->ppn_date_check);  
					$get_kontribusi_ahm  = $row->kontribusi_ahm / $check_program; 
					$get_kontribusi_md	 = $row->kontribusi_md / $check_program; 
					$get_kontribusi_d    = $row->kontribusi_dealer / $check_program; 
					$get_total_kontribusi_ahm    = $row->total_kontribusi_ahm / $check_program; 
					$get_total_kontribusi_md    = $row->total_kontribusi_md / $check_program; 
					$get_total_kontribusi_d    = $row->total_kontribusi_d / $check_program; 
				}

				$generate=substr($row->id_program_md,0,9);
				$total_reject	= ($get_kontribusi_ahm + $get_kontribusi_md + $get_kontribusi_d ) * ($row->status_reject) ;
				$total_bayar_dg = ($get_total_kontribusi_d + $total_reject);

				$output.="<tr id='".$generate."'>";
				// $output.="<td><input type='checkbox' name='nama_dealer[]' 			value='$row->kode_dealer_ahm' class='hiden-checkbox' checked  >" .$row->nama_dealer. "</td>"; 
				$output.="<td><input type='checkbox' name='nama_dealer_detail[]' 			value='$row->id_dealer'					class='hiden-checkbox' checked >" .$row->nama_dealer. "</td>"; 
				$output.="<td><input type='checkbox' name='no_juklak[]'						value='$row->no_juklak_md'				class='hiden-checkbox' checked > $row->no_juklak_md</td>"; 
				$output.="<td><input type='checkbox' name='jenis_sales_program[]' 			value='$row->jenis_sales_program' 		class='hiden-checkbox' checked>$row->jenis_sales_program</td>"; 
				$output.="<td><input type='checkbox' name='id_program_md[]' 				value='$row->id_program_md'				class='hiden-checkbox' checked>$row->id_program_md</td>"; 
				$output.="<td><input type='checkbox' name='approve_name[]' 	 	    		value='$row->status_approved' 			class='hiden-checkbox approve_name_manual' checked >$row->status_approved</td>"; 
				$output.="<td><input type='checkbox' name='reject_name[]' 					value='$row->status_reject' 			class='hiden-checkbox reject_name_manual' checked>$row->status_reject</td>"; 
				$output.="<td><input type='checkbox' name='sisa_stock[]' 					value='$row->status_ajukan' 			class='hiden-checkbox gantung_name_manual' checked>$row->status_ajukan</td>"; 
				$output.="<td><input type='checkbox' name='kontribusi_ahm[]' 				value='$get_kontribusi_ahm' 			class='hiden-checkbox kontribusi_ahm_manual' checked >" .number_format($get_kontribusi_ahm). "</td>"; 
				$output.="<td><input type='checkbox' name='kontribusi_md[]' 				value='$get_kontribusi_md' 				class='hiden-checkbox kontribusi_md_manual' checked >".number_format($get_kontribusi_md)."</td>"; 
				$output.="<td><input type='checkbox' name='kontribusi_dealer[]' 			value='$get_kontribusi_d' 				class='hiden-checkbox kontribusi_dealer_manual' checked >".number_format($get_kontribusi_d)."</td>"; 
				$output.="<td><input type='checkbox' name='ful_total_kontribusi_ahm[]' 		value='$get_total_kontribusi_ahm' 		class='hiden-checkbox ful_total_kontribusi_ahm_name_manual' checked>".number_format($get_total_kontribusi_ahm)."</td>"; 
				$output.="<td><input type='checkbox' name='ful_total_kontribusi_md_name[]' 	value='$get_total_kontribusi_md'		class='hiden-checkbox ful_total_kontribusi_md_name_manual' checked>".number_format($get_total_kontribusi_md)."</td>"; 
				$output.="<td><input type='checkbox' name='ful_total_kontribusi_d_name[]' 	value='$get_total_kontribusi_d' 		class='hiden-checkbox ful_total_kontribusi_d_name_manual' checked>".number_format($get_total_kontribusi_d)."</td>"; 
				$output.="<td><input type='checkbox' name='sum_total_reject[]'  	        value='$total_reject' 					class='hiden-checkbox ful_total_reject_manual' checked>".number_format($total_reject)."</td>"; 
				$output.="<td><input type='checkbox' name='total_pembayaran[]'  	        value='$total_bayar_dg' 				class='hiden-checkbox ful_total_bayar_dg_manual' checked>".number_format($total_bayar_dg)."</td>"; 
				$output.="<td> <input type='checkbox' class='hiden-checkbox' value='dg' name='jenis_pembayaran[]' checked/> <input type='checkbox' name='include_ppn[]' 	value='$ppn_value' 	class='hiden-checkbox' checked>".$ppn."</td>"; 
				$output.="<td><a  class='btn btn-danger btn-sm btn-flat'  onclick='remove($generate)'><i class='fa fa-trash'></i></a></td>"; 
				$output.="</tr>";
			}
			
		echo ($output);
	}

	
	public function save(){

		$count= $this->db->query("select count(*) as jumlah from tr_claim_sales_program_payment_generate ")->row(); 
		$asc_number = $count->jumlah + 1;
		if($count->jumlah <= 10){
			$kode = '0000'.$asc_number;
		}else if ($count->jumlah >= 11){
			$kode = '00'.$asc_number;
		}else if ($count->jumlah >= 100){
			$kode = '0'.$asc_number;
		}

		$id = "PCSP"."/";
		$d = date('y') ;
		$mnth = date('m');
		$day = date('d');
		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tabel			= $this->tables;
		$tabel2 		= "tr_claim_sales_program_payment_generate_detail";
		$idgenerate = $id.$d.$mnth.$day.'/'.$kode;
		
		$awal = $this->input->post('footer_tanggal_awal');
		$akhir= $this->input->post('footer_tanggal_akhir');
		$check_id_program_md 		= $_POST['id_program_md'];

		$total['status'] 			= 'input';
		$total['priode_program'] 	= $awal . ' - ' . $akhir;
		$total['created_at'] 		= $waktu ;
		$total['include_ppn'] 		= $this->input->post('footer_setting_ppn');
		$total['id_bank'] 			= $this->input->post('footer_id_bank');
		
		$total['tgl_transaksi_claim'] 		= date("Y-m-d");
		$total['id_dealer'] 				= $this->input->post('kode_dealer');
		$total['id_claim_generate_payment']	= $idgenerate;

		$footer_total_tot_approve		 	= $this->input->post('footer_total_tot_approve');
		$footer_total_tot_reject  			= $this->input->post('footer_total_tot_reject');
		$footer_total_sisa_stock_pending	= $this->input->post('footer_total_sisa_stock_pending');
		$footer_total_ful_tot_ahm			= $this->input->post('footer_total_ful_tot_ahm');
		$footer_total_ful_tot_md			= $this->input->post('footer_total_ful_tot_md');
		$footer_total_ful_tot_d				= $this->input->post('footer_total_ful_tot_d');
		$footer_total_ful_tot_reject	 	= $this->input->post('footer_total_ful_tot_reject');
		$footer_total_ful_tot_pembayaran 	= $this->input->post('footer_total_tot_pembayaran');

		$footer_total_tot_approve_manual 		= $this->input->post('footer_total_tot_approve_manual');
		$footer_total_tot_reject_manual 		= $this->input->post('footer_total_tot_reject_manual');
		$footer_total_sisa_stock_pending_manual = NULL;
		$footer_total_ful_tot_ahm_manual 	 	= $this->input->post('footer_total_ful_tot_ahm_manual');
		$footer_total_ful_tot_md_manual 		= $this->input->post('footer_total_ful_tot_md_manual');
		$footer_total_ful_tot_d_manual  		= $this->input->post('footer_total_ful_tot_d_manual');
		$footer_total_ful_tot_reject_manual     = $this->input->post('footer_total_ful_tot_reject_manual');
		$footer_total_ful_bayar_manual   		= $this->input->post('footer_total_bayar_manual');

		$total['total_approve'] 		= $footer_total_tot_approve+$footer_total_tot_approve_manual;
		$total['total_reject'] 			= $footer_total_tot_reject+$footer_total_tot_reject_manual;
		$total['total_sisa_stock']		= $footer_total_sisa_stock_pending+$footer_total_sisa_stock_pending_manual;
		$total['total_kontribusi_ahm']  = $footer_total_ful_tot_ahm+$footer_total_ful_tot_ahm_manual;
		$total['total_kontribusi_md']   = $footer_total_ful_tot_md+$footer_total_ful_tot_md_manual;
		$total['total_kontribusi_d']    = $footer_total_ful_tot_d+$footer_total_ful_tot_d_manual;
		$total['total_full_reject']     = $footer_total_ful_tot_reject+$footer_total_ful_tot_reject_manual;
		$total['total_pembayaran']      = $footer_total_ful_tot_pembayaran+$footer_total_ful_bayar_manual;


		$body['id_claim_generate_payment'] 	=  $idgenerate;
		$body['id_claim'] 					= $this->input->post('id_claim');
		$body['jenis_sales_program'] 		= $this->input->post('jenis_sales_program');
		$body['nama_dealer_detail'] 		= $this->input->post('nama_dealer_detail');
		$body['approve_name'] 				= $this->input->post('approve_name');
		$body['reject_name'] 				= $this->input->post('reject_name');
		$body['kontribusi_ahm'] 			= $this->input->post('kontribusi_ahm');
		$body['kontribusi_md'] 				= $this->input->post('kontribusi_md');
		$body['kontribusi_dealer'] 			= $this->input->post('kontribusi_dealer');
		$body['ful_total_kontribusi_ahm'] 	= $this->input->post('ful_total_kontribusi_ahm');
		$body['ful_total_kontribusi_d_name']= $this->input->post('ful_total_kontribusi_d_name');
		$body['totalbayar'] 				= $this->input->post('totalbayar');
		$body['sum_total_reject'] 			= $this->input->post('sum_total_reject');
		$body['kontribusi_other'] 			= NULL;
		$body['id_program_md'] 				= $this->input->post('id_program_md');
		$body['no_juklak'] 					= $this->input->post('no_juklak');
		$body['include_ppn'] 				= $this->input->post('include_ppn');



		

		$result = array();
		foreach($check_id_program_md AS $key => $val){
			$payment_to_dealer = $this->set_claim_dealer_payment_id($_POST,$key,$idgenerate);
			$result[] = array(
				'id_claim_generate_payment'   => $idgenerate,
				'sub_kategori_program' 		 => $_POST['jenis_sales_program'][$key],
				'id_dealer' 				 => $_POST['nama_dealer_detail'][$key],
				'tot_approve'   			 => $_POST['approve_name'][$key],
				'tot_reject'   				 => $_POST['reject_name'][$key],
				'tot_pending'    			 => $_POST['sisa_stock'][$key],
				'kontribusi_ahm' 			 => $_POST['kontribusi_ahm'][$key],
				'kontribusi_md'  			 => $_POST['kontribusi_md'][$key],
				'kontribusi_d'   			 => $_POST['kontribusi_dealer'][$key],
				'total_kontribusi_ahm'		 => $_POST['ful_total_kontribusi_ahm'][$key],
				'total_kontribusi_md' 		 => $_POST['ful_total_kontribusi_md_name'][$key],
				'total_kontribusi_d' 		 => $_POST['ful_total_kontribusi_d_name'][$key],
				'total_pembayaran'        	 => $_POST['total_pembayaran'][$key],
				'jenis_pembayaran'        	 => $_POST['jenis_pembayaran'][$key],
				'total_reject_detail' 		 => $_POST['sum_total_reject'][$key],
				'kontribusi_other'			 => NULL,
				'id_program_md'   			 => $_POST['id_program_md'][$key],
				'no_juklak'   				 => $_POST['no_juklak'][$key],
				'include_ppn'   				 => $_POST['include_ppn'][$key],
			);
		}


		$this->m_admin->insert($tabel,$total);
		$this->db->insert_batch($tabel2, $result);
		
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		redirect('h1/pembayaran_claim_dealer/add',$header);
	}

	public function set_claim_dealer_payment_id($data,$key,$idgenerate)
	{	
		$id_dealer = '103';
		// $id_dealer = $data['id_dealer'][$key];
		$sales_program_md = $data['id_program_md'][$key];

		$claim_approve_dealer = $this->db->query("SELECT tcd.id_claim ,count(1) as total ,tcd.id_sales_order, tcd.status from tr_claim_dealer tcd WHERE tcd.id_program_md ='$sales_program_md' 
		and tcd.id_dealer ='$id_dealer'
		group by tcd.id_claim")->result();


		foreach($claim_approve_dealer AS $val){
			$result[] = array(
				'id_claim' 			   			=> $val->id_claim,
				'payment_status'    			=> 0,
				'id_claim_generate_payment'     => $idgenerate,
				);
		}

		$table_claim_dealer 		= "tr_claim_dealer";
		$this->db->update_batch($table_claim_dealer, $result, 'id_claim');
	
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data berhasil diproses ";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/monitoring_claim_dealer'>";
		}


	}




	public function detail_claim()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail_claim";
		$id = $this->input->get('id');

		$data['header_claim'] = $this->m_pembayaran_claim_dealer->get_header_claim($id)->row();
		$detail_claim =  $this->m_pembayaran_claim_dealer->get_detail_claim($id);
		
		if ($detail_claim->num_rows() > 0) {
			$data['detail_claim'] = $detail_claim->result();
		}else{
			$data['detail_claim'] = '';
			$data['header_claim']= '';
		}

		$this->template($data);			
	}


	public function claim_approve()
	{		
		$generate= $this->input->get('id_generate');
  		$tabel	= $this->tables;	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$pk     = "id_claim_generate_payment";
		$update['status'] = 'approved';
		$update['approve_payment_dealer_created'] = gmdate("y-m-d h:i:s", time()+60*60*7);
		$this->m_admin->update($tabel,$update,$pk,$generate);
   		 echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pembayaran_claim_dealer'>";
	}


		
	public function process_claim()
	{		
	  $generate= $this->input->get('id_generate');
	  $tabel	= $this->tables;	
	  $data['isi']    = $this->page;		
	  $data['title']	= $this->title;															
	  $data['set']		= "view";
	  $pk     = "id_claim_generate_payment";
	  $update['status'] = 'close';
	  $update['approve_payment_dealer_created'] = gmdate("y-m-d h:i:s", time()+60*60*7);
	  $this->m_admin->update($tabel,$update,$pk,$generate);
		  echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pembayaran_claim_dealer'>";
	}

	
	public function send_notify()
	{	
		$tabel			= $this->tables;
		$pk     =   "id_claim_generate_payment";
		$generate= $this->input->get('id_generate');
		$data['status'] = 'send';
		$data['send_payment_created'] = gmdate("y-m-d h:i:s", time()+60*60*7);
		$this->m_admin->update($tabel,$data,$pk,$generate);
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pembayaran_claim_dealer'>";		

	}

	public function aksi()
	{
		$this->load->helper("terbilang");
	    $tgl = date('d F Y', strtotime(date('y-m-d'))); 

		$terbilang = number_to_words("10080000");

		$id = $this->input->get('id');
		// var_dump(number_to_words("10080000"));
		// die();

		// $id_dealer = $this->input->post('id_dealer');
		// $tgl1 = $this->input->post('tgl1');
		// $tgl2 = $this->input->post('tgl2');

		$id_dealer = '103';

		$tgl1 ='2023-03-01';
		
		$tgl2 ='2023-03-31';

		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();

        $dealer = $this->db->query("SELECT ms_dealer.nama_dealer,ms_kabupaten.kabupaten FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan 
            INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
            INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
            WHERE ms_dealer.id_dealer = '$id'")->row();

		$sales_program_header = $this->db->query("SELECT * from tr_claim_sales_program_payment_generate csg left join ms_bank bk on bk.id_bank = csg.id_bank WHERE csg.id_claim_generate_payment ='$id'")->row();
		$terbilang = number_to_words($sales_program_header->total_pembayaran);

        $pdf->SetFont('TIMES','',12);
		$pdf->Cell(35,6,'Tanggal : '.$tgl,0,1,'L');
		$pdf->Cell(35,6,'',0,1,'L');
		
		$pdf->Cell(35,6,'Tanggal Entry : '.$tgl,0,1,'L');
		$pdf->Cell(35,6,'Bank: '.$sales_program_header->bank,0,1,'L');
		// $pdf->Cell(35,6,'Tanggal Bayar :',0,1,'L');
		$pdf->Cell(35,6,'ID : '.$sales_program_header->id_claim_generate_payment,0,1,'L');


		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Cell(35,6,'Bukti Pengeluran Kas/Bank :',0,1,'');
		$pdf->Cell(196,6,'Ditagihkan Kepada :'.$dealer->nama_dealer,'LTRB',1,'C');
		$pdf->Cell(196,6,'Penggantian Claim Program Penjualan Periode : '.$sales_program_header->priode_program ,'LTRB',1,'L');


		$pdf->Cell(60,6,'No. Juklak ','LTRB',0,'L');
		$pdf->Cell(20,6,'Type ','LTRB',0,'L');
		$pdf->Cell(30,6,'Unit Apporove ','LTRB',0,'L');
		$pdf->Cell(30,6,'Kontribusi Unit ','LTRB',0,'L');
		$pdf->Cell(40,6,'Total Pembayaran ','LTRB',1,'L');

		$sales_program = $this->db->query("SELECT * from tr_claim_sales_program_payment_generate csg join tr_claim_sales_program_payment_generate_detail csgd
		on csg.id_claim_generate_payment = csgd.id_claim_generate_payment
		WHERE csg.id_claim_generate_payment ='$id'")->result();


		foreach($sales_program as $row) {    
			$pdf->Cell(60,6,$row->no_juklak,'LTRB',0,'L');
			$pdf->Cell(20,6,$row->jenis_pembayaran,'LTRB',0,'L');
			$pdf->Cell(30,6,$row->tot_approve,'LTRB',0,'L');
			$pdf->Cell(30,6,$row->tot_reject,'LTRB',0,'L');
			$pdf->Cell(40,6,number_format($row->total_pembayaran),'LTRB',1,'L');
		}

		$pdf->Cell(60,6,'Total','LTB',0,'L');
		$pdf->Cell(20,6,'','TB',0,'L');
		$pdf->Cell(30,6,$sales_program_header->total_approve,'LTRB',0,'L');
		$pdf->Cell(30,6,$sales_program_header->total_reject,'LTRB',0,'L');
		$pdf->Cell(40,6,number_format($sales_program_header->total_pembayaran),'LTRB',1,'L');


		$pdf->Cell(196,10,'Terbilang : '.$terbilang.' Rupiah','LTRB',1,'');

		// $pdf->Cell(61,6,'Keterangan ','LTR',0,'C');
		// $pdf->Cell(45,6,'Disetujui ','LTR',0,'C');
		// $pdf->Cell(45,6,'Dibayar ','LTR',0,'C');
		// $pdf->Cell(45,6,'Diterima ','LTR',1,'C');

		// $pdf->Cell(61,30,' ','LRB',0,'C');
		// $pdf->Cell(45,30,' ','LRB',0,'C');
		// $pdf->Cell(45,30,' ','LRB',0,'C');
		// $pdf->Cell(45,30,' ','LRB',0,'C');



		$pdf->Cell(35,30,'',0,1,'L');
		$pdf->Ln(4);
		
		$pdf->setX(10);
		$pdf->Ln(4);
		
		$pdf->Cell(63.3,6,'Hormat Kami',0,0,'C');
		$pdf->Ln(30);
		$pdf->Cell(63.3,6,'FEBRIANA',0,1,'C');
		$pdf->Cell(63.3,6,'Finance Head',0,0,'C');
		$pdf->Output(); 
	}


	public function monitoring_from_dealer()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= 'Monitoring Approve Claim From Dealer';															
		$data['set']	= "monitoring";
		$data['pembayaran_claim_dealer'] =  $this->m_pembayaran_claim_dealer->get_claim_approve_from_dealer()->result();
		$this->template($data);			
	}

	
	public function monitoring_payment()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= 'Monitoring Payment';															
		$data['set']	= "monitoring_payment";
		$data['pembayaran_claim_dealer'] =  $this->m_pembayaran_claim_dealer->get_claim_approve_from_dealer()->result();
		$this->template($data);			
	}




}