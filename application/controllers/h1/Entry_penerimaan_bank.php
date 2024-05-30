
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry_penerimaan_bank extends CI_Controller {

    var $tables =   "tr_penerimaan_bank";	
		var $folder =   "h1";
		var $page		=		"entry_penerimaan_bank";
		var $isi		=		"bank_kas";
    var $pk     =   "id_penerimaan_bank";
    var $title  =   "Entry Penerimaan Bank";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h1_entry_penerimaan_bank');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->library("udp_cart");//load library 
		$this->item   			= new Udp_cart("item");
		$this->item_bg   		= new Udp_cart("item_bg");
		$this->item_tf   		= new Udp_cart("item_tf");

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
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "view";				
		$data['dt_penerimaan']	= $this->db->query("SELECT * FROM tr_penerimaan_bank ORDER BY tgl_entry DESC,id_penerimaan_bank DESC");		
		$this->template($data);			
	}

	public function getData()
	{
		$search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

        $dataEntry = $this->h1_entry_penerimaan_bank->data_entry_penerimaan_bank($search, $limit, $start, $order_field, $order_ascdesc);

        $data = array();
        foreach($dataEntry->result() as $row)
        {

        	if($row->status=='input'){
            	$tom = "<a onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-flat btn-xs btn-primary\" href=\"h1/entry_penerimaan_bank/approve?id=$row->id_penerimaan_bank\">Approve</a>
                <a class=\"btn btn-flat btn-xs btn-danger\" href=\"h1/entry_penerimaan_bank/edit?id=$row->id_penerimaan_bank\">Edit</a>";
              $status = "<span class='label label-warning'>$row->status</span>";
            }else{
            	$tom = "<a href='h1/entry_penerimaan_bank/cetak_kwitansi?id=$row->id_penerimaan_bank' class='btn btn-flat btn-xs btn-success'>Cetak Kwitansi</a>";
              $status = "<span class='label label-success'>$row->status</span>";
            }      
            // $dt = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$row->id_penerimaan_bank'")->row();                  
            // if($row->tipe_customer == 'Dealer'){
            //   $isi_dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$row->dibayar);
            //   $isi = ($isi_dealer->num_rows() > 0) ? $isi_dealer->row()->nama_dealer : "" ;
            // }elseif($row->tipe_customer == 'Vendor'){
            //   $isi_vendor = $this->m_admin->getByID("ms_vendor","id_vendor",$row->dibayar);
            //   $isi = ($isi_vendor->num_rows() > 0) ? $isi_vendor->row()->vendor_name : "" ;
            // }else{
            //   $isi = "";
            // }

            $data[]= array(
            	'',
                "<a href='h1/entry_penerimaan_bank/detail?id=$row->id_penerimaan_bank'>
                  $row->id_penerimaan_bank
                </a>",
                $row->tgl_entry,
                number_format($row->total),//number_format($dt->jum),
                $row->nama_dealer,//$isi,
                $status,
                $tom
            );     
        }
        $total_data = $this->h1_entry_penerimaan_bank->count_filter($search);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
	}
	
	public function add()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;														
		$id = $this->input->get('id');
		$data['dt_penerimaan']	= $this->db->query("SELECT * FROM tr_penerimaan_bank LEFT JOIN tr_penerimaan_bank_detail 
				ON tr_penerimaan_bank_detail.id_penerimaan_bank = tr_penerimaan_bank.id_penerimaan_bank WHERE tr_penerimaan_bank.id_penerimaan_bank = '$id'");						       
		$data['set']		= "detail";				
		$this->template($data);			
	}
	public function view()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$this->template($data);			
	}	
	public function get_slot(){
		$id_dealer	= $this->input->post('id_dealer');				
		$data = "";

		/*
		//monitor piutang
		$item = $this->db->query("SELECT * FROM tr_monout_piutang_bbn INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd = tr_pengajuan_bbn.no_bastd 
			WHERE tr_pengajuan_bbn.id_dealer = '$id_dealer' ORDER BY tr_monout_piutang_bbn.no_rekap ASC");
    foreach ($item->result() as $isi) {
      $amb = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
        WHERE tr_faktur_stnk.no_bastd = '$isi->no_bastd'")->row();
      $cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$isi->no_rekap'")->row();    
      if($isi->total > $cek_ref->jum){            
        // $data .= "<option value='$isi->no_rekap'>$amb->kode_dealer_md | $amb->nama_dealer | $isi->no_rekap</option>\n";
        $data .= "<option value='$isi->no_rekap'>$isi->no_rekap</option>\n";
      }
    }

    //invoice dealer
    $item2 = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
     	WHERE tr_do_po.id_dealer = '$id_dealer' AND (tr_invoice_dealer.status_bayar IS NULL OR tr_invoice_dealer.status_bayar = '') ORDER BY tr_invoice_dealer.no_faktur ASC");    
    foreach ($item2->result() as $isi) {
      $amb = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
        WHERE tr_do_po.no_do = '$isi->no_do'")->row();
      $cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$isi->no_faktur'")->row();    
      if($isi->total_bayar > $cek_ref->jum){            
        // $data .= "<option value='$isi->no_faktur'>$amb->kode_dealer_md | $amb->nama_dealer | $isi->no_faktur</option>\n";
        $data .= "<option value='$isi->no_faktur'>$isi->no_faktur</option>\n";
      }
    }

    //bantuan bbn
    $item3 = $this->db->query("SELECT * FROM tr_faktur_stnk WHERE id_dealer = '$id_dealer' 
    	AND (tr_faktur_stnk.status_bayar IS NULL OR tr_faktur_stnk.status_bayar = '') ORDER BY tr_faktur_stnk.no_bastd ASC");    
    foreach ($item3->result() as $isi) {
      $amb = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$isi->id_dealer'")->row();
      $cek_bbn = $this->db->query("SELECT SUM(biaya_bbn) AS jum FROM tr_faktur_stnk_detail WHERE no_bastd = '$isi->no_bastd'")->row();    
      // $cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$isi->no_faktur'")->row();    
      // if($cek_bbn->jum > $cek_ref->jum){            
        $data .= "<option value='$isi->no_bastd'>$isi->no_bastd</option>\n";
      // }
    }
	*/

		echo $data;
	}
	public function cari_total(){				
		// $id = preg_replace('/\s+/', '', $this->input->post('id_penerimaan_bank'));
		// $pr_num	= $this->db->query("SELECT SUM(nominal) as jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$id'");						       
	 //  if($pr_num->num_rows()>0){
	 //   	$row = $pr_num->row();			   	
	 //    $sum =	$row->jum;
		// }else{
		// 	$sum = 0;
		// }
		$sum=0;
		if($item = $this->item->get_content()) {
    	foreach ($item as $row){ 	
    		$sum += $row['nominal'];
    	}
    }
		echo $sum;
	}
	public function cari_coa(){				
		$kode_coa = $this->input->post('kode_coa');
		$pr_num	= $this->db->query("SELECT * FROM ms_coa WHERE kode_coa = '$kode_coa'");						       
	  if($pr_num->num_rows()>0){
	   	$row = $pr_num->row();			   	
	    $coa =	$row->coa;
		}else{
			$coa = "";
		}		
		echo $coa;
	}
	public function cari_ref(){				
		$referensi = $this->input->post('referensi');
		$cek1	= $this->db->query("SELECT total FROM tr_invoice_ekspedisi WHERE no_invoice_program = '$referensi'");						       
		$cek2	= $this->db->query("SELECT total FROM tr_invoice_ekspedisi WHERE no_penerimaan = '$referensi'");						       		
		$cek3	= $this->db->query("SELECT total_bayar FROM tr_invoice_dealer WHERE no_faktur = '$referensi'");						       		
		$cek4	= $this->db->query("SELECT SUM(biaya_bbn) AS total FROM tr_faktur_stnk_detail WHERE no_bastd = '$referensi'");						       		
	  if($cek1->num_rows()>0){
	   	$row 		= $cek1->row();			 	   	
	   	$cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$referensi'")->row();  	
	    $total 	= $row->total - $cek_ref->jum;
		}elseif($cek2->num_rows()>0){
			$row2 	= $cek2->row();			   	
			$cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$referensi'")->row();  	
	    $total 	=	$row2->total - $cek_ref->jum;
	  }elseif($cek3->num_rows()>0){
			$row3 	= $cek3->row();			 
			$cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$referensi'")->row();  	
	    $total 	=	$row3->total_bayar - $cek_ref->jum;
	  }elseif($cek4->num_rows()>0){
			$row4 	= $cek4->row();			 
			$cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$referensi'")->row();  	
	    $total 	=	$row4->total - $cek_ref->jum;
		}else{
			$total = 0;
		}		
		echo $total;
	}
	public function cari_id(){				
		$th 						= date("y");
		$bln 						= date("m");
		$thn 						= date("Y");
		
		if($thn > '2021'){
			$pr_num 				= $this->db->query("SELECT * FROM tr_penerimaan_bank where left(created_at,4) = '$thn' ORDER BY id_penerimaan_bank DESC LIMIT 0,1");	
		}else{
			$pr_num 				= $this->db->query("SELECT * FROM tr_penerimaan_bank ORDER BY id_penerimaan_bank DESC LIMIT 0,1");	
		}					       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_penerimaan_bank,2,5); 
	    $kode = $th.sprintf("%05d", $id+1);
		}else{
			$kode = $th."00001";
		}
		//echo $kode;
		return $kode;
	}
	public function t_bg(){
		$id = $this->input->post('id_penerimaan_bank');
		$dq = "SELECT * FROM tr_penerimaan_bank_bg WHERE id_penerimaan_bank = '$id'";
		$data['dt_bg'] = $this->db->query($dq);
		$this->load->view('h1/t_bg',$data);
	}
	public function delete_bg(){
		// $id 		= $this->input->post('id_penerimaan_bank_bg');		
		// $da 		= "DELETE FROM tr_penerimaan_bank_bg WHERE id_penerimaan_bank_bg = '$id'";
		// $this->db->query($da);			
		// echo "nihil";

		$rowid=$this->input->post('id');
		if($this->item_bg->remove_item($rowid)){
			$this->t_bg();
		}else{
			echo "failed";
		}
	}
	public function save_bg(){
		// $id_penerimaan_bank =$data['id_penerimaan_bank']		= preg_replace('/\s+/', '', $this->input->post('id_penerimaan_bank'));
		// $no_bg					= $this->input->post('no_bg');		
		// $c 			= $this->db->query("SELECT * FROM tr_penerimaan_bank_bg WHERE id_penerimaan_bank ='$id_penerimaan_bank' AND no_bg = '$no_bg'");
		// $data['no_bg']              = $this->input->post('no_bg');
		// $data['tgl_bg']             = $this->input->post('tgl_bg');
		// $data['nominal_bg']         = $this->m_admin->ubah_rupiah($this->input->post('nominal_bg'));			

		// if($c->num_rows()==0){
		// 	$this->m_admin->insert('tr_penerimaan_bank_bg',$data);										
		// }else{
		// 	$rt = $c->row();
		// 	$this->m_admin->update('tr_penerimaan_bank_bg',$data,"id_penerimaan_bank_bg",$rt->id_penerimaan_bank_bg);										
		// }
		// echo "nihil";

		$data['id'] = rand(1,9999);$data['qty'] = rand(1,9999);$data['price'] = rand(1,9999);				
		$data['no_bg'] = $no_bg = $this->input->post('no_bg');
		$data['tgl_bg'] = $this->input->post('tgl_bg');		
		$data['nominal_bg'] = $this->m_admin->ubah_rupiah($this->input->post('nominal_bg'));					
		$no=1;
		if($item_bg = $this->item_bg->get_content()) {
			foreach ($item_bg as $res){
				if($no_bg == $res['no_bg']){
					$no++;
				}				
			}
		}
		if($no==1){
			$this->item_bg->insert($data);
			$data['set']      = 'item';									
			$this->t_bg();
		}else{
			echo "failed";
		}

	}
	public function t_transfer(){
		$id = preg_replace('/\s+/', '', $this->input->post('id_penerimaan_bank'));
		$dq = "SELECT * FROM tr_penerimaan_bank_transfer WHERE id_penerimaan_bank = '$id'";
		$data['dt_transfer'] = $this->db->query($dq);
		$data['tipe'] = "penerimaan_bank";
		$this->load->view('h1/t_transfer',$data);
	}
	public function delete_transfer(){
		// $id 		= $this->input->post('id_penerimaan_bank_transfer');		
		// $da 		= "DELETE FROM tr_penerimaan_bank_transfer WHERE id_penerimaan_bank_transfer = '$id'";
		// $this->db->query($da);			
		// echo "nihil";

		$rowid=$this->input->post('id');
		if($this->item_tf->remove_item($rowid)){
			$this->t_transfer();
		}else{
			echo "failed";
		}
	}
	public function save_transfer(){		
		// $data['id_penerimaan_bank']		= preg_replace('/\s+/', '', $this->input->post('id_penerimaan_bank'));				
		// $data['tgl_transfer']				= $this->input->post('tgl_transfer');
		// $data['nominal_transfer']		= $this->m_admin->ubah_rupiah($this->input->post('nominal_transfer'));		
		// var_dump($data);	
		// $this->m_admin->insert('tr_penerimaan_bank_transfer',$data);												
		// echo "nihil";
		$data['id'] = rand(1,9999);$data['qty'] = rand(1,9999);$data['price'] = rand(1,9999);				
		$data['tgl_transfer'] = $this->input->post('tgl_transfer');		
		$data['nominal_transfer'] = $this->m_admin->ubah_rupiah($this->input->post('nominal_transfer'));							
		if($this->item_tf->insert($data)){
			$data['set']      = 'item';									
			$this->t_transfer();
		}else{
			echo "failed";
		}
	}
	public function t_detail(){
		$id = preg_replace('/\s+/', '', $this->input->post('id_penerimaan_bank'));
		$dq = "SELECT * FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$id'";
		$data['dt_detail'] = $this->db->query($dq);
		$this->load->view('h1/t_penerimaan_detail',$data);
	}
	public function save_detail(){
		// $id_penerimaan_bank = $data['id_penerimaan_bank']		= preg_replace('/\s+/', '', $this->input->post('id_penerimaan_bank'));
		// $kode_coa					= $this->input->post('kode_coa');		
		// $referensi				= $this->input->post('referensi');			
		// $c 			= $this->db->query("SELECT * FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank ='$id_penerimaan_bank' AND referensi = '$referensi'");
		// $data['kode_coa']				= $this->input->post('kode_coa');
		// $data['coa']						= $this->input->post('coa');
		// $data['nominal']				= $this->m_admin->ubah_rupiah($this->input->post('nominal'));			
		// $data['referensi']			= $this->input->post('referensi');			
		// $data['sisa_hutang']		= $this->input->post('sisa_hutang');			
		// $data['keterangan']			= $this->input->post('keterangan');			
		// if($c->num_rows()==0){
		// 	$this->m_admin->insert('tr_penerimaan_bank_detail',$data);										
		// }else{
		// 	$rt = $c->row();
		// 	$this->m_admin->update('tr_penerimaan_bank_detail',$data,"id_penerimaan_bank_detail",$rt->id_penerimaan_bank_detail);										
		// }
		// echo "nihil";

		$data['id'] = rand(1,9999);				
		$data['qty'] = rand(1,9999);				
		$data['price'] = rand(1,9999);				
		$data['kode_coa'] = $kode_coa = $this->input->post('kode_coa');
		$data['coa'] = $this->input->post('coa');
		$data['referensi'] = $referensi = $this->input->post('referensi');				
		$data['nominal'] = $this->m_admin->ubah_rupiah($this->input->post('nominal'));			
		$data['sisa_hutang'] = $this->input->post('sisa_hutang');				
		$data['keterangan'] = $this->input->post('keterangan');				
		$no=1;
		if($item = $this->item->get_content()) {
			foreach ($item as $res){
				if($kode_coa == $res['kode_coa'] AND $referensi == $res['referensi']){
					$no++;
				}				
			}
		}
		if($no==1){
			$this->item->insert($data);
			$data['set']      = 'item';						
			//$this->load->view("h1/t_penerimaan_detail",$data);			
			$this->t_detail();
		}else{
			echo "failed";
		}

	}
	public function delete_detail(){
		// $id 		= $this->input->post('id_penerimaan_bank_detail');		
		// $da 		= "DELETE FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank_detail = '$id'";
		// $this->db->query($da);			
		// echo "nihil";

		$rowid=$this->input->post('id');
		if($this->item->remove_item($rowid)){
			$this->t_detail();
		}else{
			echo "failed";
		}
	}
	
	public function save()
	{		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['account'] 					= $this->input->post('account');
			//$id_penerimaan = $data['id_penerimaan_bank'] 	= $this->input->post('id_penerimaan_bank');
			$data['tgl_entry'] 				= $this->input->post('tgl_entry');					
			$data['tipe_customer'] 		= $this->input->post('tipe_customer');					
			$tipe_customer 						= $this->input->post('tipe_customer');					
			if($tipe_customer=="Vendor"){
				$data['dibayar'] 				= $this->input->post('dibayar_v');					
			}elseif($tipe_customer=="Dealer"){
				$data['dibayar'] 				= $this->input->post('dibayar_d');					
			}else{
				$data['dibayar'] 				= $this->input->post('dibayar_l');					
			}			
			$data['pph'] 							= $this->input->post('pph');					
			$data['jenis_bayar'] 			= $this->input->post('jenis_bayar');					
			$data['total_pembayaran'] = $this->input->post('total_pembayaran');					
			$data['rekening_tujuan'] 	= $this->input->post('rekening_tujuan');					
			$data['via_bayar'] 				= $this->input->post('via_bayar');					
			$data['status']						= "input";		
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$id_penerimaan_bank = $data['id_penerimaan_bank'] 	= $this->cari_id();
			$this->m_admin->insert($tabel,$data);			

			if($item = $this->item->get_content())
			{
				foreach($item as $key => $val){
					$detail[$key]['id_penerimaan_bank'] = $id_penerimaan_bank;
					$detail[$key]['kode_coa']   = $val['kode_coa'];
					$detail[$key]['coa']   = $val['coa'];
					$detail[$key]['referensi']   = $val['referensi'];				
					$detail[$key]['keterangan']   = $val['keterangan'];				
					$detail[$key]['nominal']   = $val['nominal'];				
					$detail[$key]['sisa_hutang']   = $val['sisa_hutang'];				
				}
			}else{
				$_SESSION['pesan'] 	= "Data item masih kosong";
				$_SESSION['tipe'] 	= "danger";				
				echo "<script>history.go(-1)</script>";
			}
			$this->db->insert_batch('tr_penerimaan_bank_detail',$detail);
			
			if($item_bg = $this->item_bg->get_content()){
				foreach($item_bg as $key => $val){
					$detail2[$key]['id_penerimaan_bank'] = $id_penerimaan_bank;
					$detail2[$key]['no_bg']   = $val['no_bg'];
					$detail2[$key]['tgl_bg']   = $val['tgl_bg'];
					$detail2[$key]['nominal_bg']   = $val['nominal_bg'];									
				}			
				$this->db->insert_batch('tr_penerimaan_bank_bg',$detail2);
			}
			
			if($item_tf = $this->item_tf->get_content()){
				foreach($item_tf as $key => $val){
					$detail3[$key]['id_penerimaan_bank'] = $id_penerimaan_bank;
					$detail3[$key]['tgl_transfer']   = $val['tgl_transfer'];				
					$detail3[$key]['nominal_transfer']   = $val['nominal_transfer'];									
				}
				$this->db->insert_batch('tr_penerimaan_bank_transfer',$detail3);
			}			

			if ($this->db->trans_status() === FALSE){
	    	$this->db->trans_rollback();	      
	    }else{
	      $this->db->trans_commit();
	      $this->item->destroy();	      			
	      $this->item_bg->destroy();	      			
	      $this->item_tf->destroy();	      			
	    }			


			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_penerimaan_bank/add'>";			
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}		
	}

	public function approve()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

		$pk					= $this->pk;
		$id  				= $this->input->get('id');
		$data['status']						= "approved";		
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;

		$get = $this->m_admin->getByID("tr_penerimaan_bank_detail","id_penerimaan_bank",$id);
		$cek_transfer = $this->m_admin->getByID("tr_penerimaan_bank_transfer","id_penerimaan_bank",$id);
		if($cek_transfer->num_rows() > 0){

		$this->m_admin->update($tabel,$data,$pk,$id);

		foreach ($get->result() as $isi) {							
			$cek1 = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail INNER JOIN tr_penerimaan_bank 
					ON tr_penerimaan_bank.id_penerimaan_bank = tr_penerimaan_bank_detail.id_penerimaan_bank 
					WHERE tr_penerimaan_bank_detail.referensi = '$isi->referensi' AND tr_penerimaan_bank.status = 'approved'")->row()->jum;
			$cek2 = $this->db->query("SELECT total_bayar FROM tr_invoice_dealer WHERE no_faktur = '$isi->referensi'");
			$cek3 = $this->db->query("SELECT total FROM tr_monout_piutang_bbn WHERE no_rekap = '$isi->referensi'");
			$cek4 = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_bastd = '$isi->referensi'");
		  
		  if($cek2->num_rows() > 0){
		  	$is = $cek2->row();
			  if($cek1 == $is->total_bayar){
			  	$dt['status_bayar'] = 'lunas';
					$this->simpan_rekap($id,$cek1);			  				  	
			  }else{
			  	$dt['status_bayar'] = "";
			  }
			  $this->m_admin->update("tr_invoice_dealer",$dt,"no_faktur",$isi->referensi);
			}elseif($cek3->num_rows() > 0){
				$ir = $cek3->row();
			  if($cek1 == $ir->total){
			  	$ds['status_mon'] = 'lunas';
			  	$this->simpan_rekap($id,$cek1);			  				  	
			  }else{
			  	$ds['status_mon'] = "";
			  }
			  $this->m_admin->update("tr_monout_piutang_bbn",$ds,"no_bastd",$isi->referensi);
			}elseif($cek4->num_rows() > 0){
				$iw = $cek4->row();
				$sql = $this->db->query("SELECT SUM(biaya_bbn) AS jum FROM tr_faktur_stnk_detail WHERE no_bastd = '$isi->referensi'")->row()->jum;
			  if($cek1 == $sql){
			  	$ds['status_bayar'] = 'lunas';
			  	$this->simpan_rekap($id,$cek1);			  				  	
			  }else{
			  	$ds['status_bayar'] = '';
			  }
			  $this->m_admin->update("tr_faktur_stnk",$ds,"no_bastd",$isi->referensi);
			}
		}
				
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_penerimaan_bank'>";
		}else{
		$_SESSION['pesan'] 	= "Gagal! Silahkan cek kembali tanggal penerimaan dan nominal transfer.";
		$_SESSION['tipe'] 	= "danger";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_penerimaan_bank'>";
		}						
	}
	public function cari_id_bbn(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
		if($th > '2021'){
			$pr_num = $this->db->query("SELECT * FROM tr_monout_piutang_lunas where left(created_at,4) ='$th' ORDER BY no_rekap DESC LIMIT 0,1");	
		}else{		
			$pr_num = $this->db->query("SELECT * FROM tr_monout_piutang_lunas ORDER BY no_rekap DESC LIMIT 0,1");	
		}						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_rekap)-3;
			$id 	= substr($row->no_rekap,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/PIL/".$isi;
		}else{
			$kode = $th.$bln."/PIL/00001";
		}						
		return $kode;
	}
	public function simpan_rekap($no_transaksi,$total){	
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');

		$dr['no_rekap'] 	= $this->cari_id_bbn();
		$dr['tgl_rekap'] 	= $tgl;
		$ambil = $this->m_admin->getByID("tr_penerimaan_bank_detail","id_penerimaan_bank",$no_transaksi);
		$isi_ref = "";
		foreach ($ambil->result() as $isi) {
			if($isi_ref == ""){
				$isi_ref = $isi->referensi;
			}else{
				$isi_ref = $isi_ref.",".$isi->referensi;
			}
		}
		$dr['referensi'] 	= $isi_ref;
		$dr['total'] 			= $total;		
		$dr['status_mon']	= "input";	
		$dr['no_transaksi'] = $no_transaksi;	
		$cek = $this->m_admin->getByID("tr_monout_piutang_lunas","no_transaksi",$no_transaksi);
		if($cek->num_rows() > 0){
			$f = $cek->row();
			$dr['updated_at'] 					= $waktu;
			$dr['updated_by'] 					= $login_id;
			$this->m_admin->update("tr_monout_piutang_lunas",$dr,"no_transaksi",$f->no_transaksi);
		}else{
			$dr['created_at'] 					= $waktu;
			$dr['created_by'] 					= $login_id;
			$this->m_admin->insert("tr_monout_piutang_lunas",$dr);
		}
	}
	public function cetak_kwitansi()
	{
    $id = $this->input->get('id');
    $sql = $this->m_admin->getByID("tr_penerimaan_bank","id_penerimaan_bank",$id);
    if ($sql->num_rows()>0) {
    	$tgl 				= gmdate("y-m-d", time()+60*60*7);
			$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;										
      
      $mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
	    $mpdf->charset_in='UTF-8';
	    $mpdf->autoLangToFont = true;
      $data['cetak'] = 'cetak_kwitansi';
      $data['id_penerimaan_bank'] = $id;
            
      //$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();
			$data['header'] = $sql->row();
      $html = $this->load->view('h1/cetak_entry_penerimaan', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_.pdf';
      $mpdf->Output("$output", 'I');
    }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_penerimaan_bank'>";		
    }       
	}

	public function modal_ref()
	{
		$id_dealer = $this->input->post('id_dealer');
		$id_vendor = $this->input->post('id_vendor');
		$tipe_customer = $this->input->post('tipe_customer');
		$data = array();
		if ($tipe_customer=='Dealer') {		
	    //invoice dealer
	   /* $item2 = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
		    WHERE tr_do_po.id_dealer = '$id_dealer' 
		    AND (tr_invoice_dealer.status_bayar IS NULL OR tr_invoice_dealer.status_bayar = '') 
				AND status_invoice='printable' ORDER BY tr_invoice_dealer.no_faktur ASC");  
		  foreach ($item2->result() as $itm) {
		    $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$itm->id_dealer)->row();
        $total_harga = 0;
        $total_harga = 0;
        $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
          ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
          ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
          ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$itm->no_do'");
        $to=0;$po=0;$do=0;$bunga_bank=0;$top_unit=0;
        foreach($dt_do_reg->result() as $isi){
          $total_harga = $isi->harga * $isi->qty_do;
          $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
            INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
            WHERE tr_invoice_dealer.no_do = '$isi->no_do'");
          if($get_d->num_rows() > 0){
            $g = $get_d->row();
            $bunga_bank = $g->bunga_bank/100;
            $top_unit = $g->top_unit;
            $dealer_financing = $g->dealer_financing;
          }else{
            $bunga_bank = "";
            $top_unit = "";
            $dealer_financing = "";
          }

          $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
          if($cek2->num_rows() > 0){
            $d = $cek2->row();
            $potongan = $d->jum;
          }else{
            $potongan = 0;
          }
          
          $pot = ($potongan + $isi->disc + $isi->disc_scp) * $isi->qty_do + $isi->disc_tambahan;              
          $to = $to + $total_harga;                    
          $po = $po + $pot;                    
          $do = $do + $isi->qty_do;                    
        }                  
        $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+((1.1*$bunga_bank/360)*$top_unit));
        $diskon_top = ($to-$po)-$d;
        if($dealer_financing=='Ya') {
          $y = $d * 0.1;
          $total_bayar = $d + $y;
        }else{
          $y = $d * 0.1;
          $total_bayar = $d + $y;
        }  
				$cek = $this->m_admin->cekPembayaran($itm->no_faktur,$total_bayar);
		    $amb = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
		      WHERE tr_do_po.no_do = '$itm->no_do'")->row();
		    $cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$itm->no_faktur'")->row();    
		    //if($cek > $cek_ref->jum){            		    
		    if($total_bayar > $cek){            		    
		      $data[]=['referensi'=>$itm->no_faktur,
		        		 	 'tgl_jatuh_tempo'=>$itm->tgl_overdue,
		        		   'nominal'=>$cek
		    					];
		    }
		  }
		  */
$dt_invoice = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
              WHERE tr_do_po.id_dealer = '$id_dealer'  AND tr_invoice_dealer.status_invoice = 'printable' AND  

              tr_invoice_dealer.status_bayar <> 'lunas' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");

          foreach($dt_invoice->result() as $row) {                                                     

            $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();

            // $total_harga = 0;

            //     $total_harga = 0;

            //     $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 

            //         ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           

            //         ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna

            //         ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");

            //       $to=0;$po=0;$do=0;

            //       foreach($dt_do_reg->result() as $isi){

            //         $total_harga = $isi->harga * $isi->qty_do;



            //         $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 

            //           INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer

            //           INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang

            //           WHERE tr_invoice_dealer.no_do = '$isi->no_do'");

            //         if($get_d->num_rows() > 0){

            //           $g = $get_d->row();

            //           $bunga_bank = $g->bunga_bank/100;

            //           $top_unit = $g->top_unit;

            //           $dealer_financing = $g->dealer_financing;

            //         }else{

            //           $bunga_bank = "";

            //           $top_unit = "";

            //           $dealer_financing = "";

            //         }

            //         $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
            //               WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
            //         if($cek2->num_rows() > 0){
            //           $d = $cek2->row();
            //           $potongan = $d->jum;
            //         }else{
            //           $potongan = 0;
            //         }

            //         // $pot = $isi->disc * $isi->qty_do; 
            //         $pot = ($potongan + $isi->disc + $isi->disc_scp) * $isi->qty_do + $isi->disc_tambahan;                   

            //         $to = $to + $total_harga;                    

            //         $po = $po + $pot;                    

            //         $do = $do + $isi->qty_do;                    

            //       }                  

            //       $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+((1.1*$bunga_bank/360)*$top_unit));

            //       $diskon_top = ($to-$po)-$d;

            //       if($dealer_financing=='Ya') {

            //         $y = $d * 0.1;

            //         $total_bayar = $d + $y;

            //       }else{

            //         $y = $d * 0.1;

            //         $total_bayar = $d + $y;

            //       }  
          // $total_bayar = $this->m_admin->get_detail_inv_dealer($row->no_do, 0);
          // $cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar['total_bayar']);
          
          $total_bayar = get_data('tr_invoice_dealer', 'no_do',$row->no_do,'total_bayar' );
          $cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar);

            if ($cek>0) {

               $data[]=['referensi'=>$row->no_faktur,
    		 	 'tgl_jatuh_tempo'=>$row->tgl_overdue,
    		   'nominal'=>$cek
					];

            }                                                

          }
		  $dt_rekap = $this->db->query("SELECT tr_monout_piutang_bbn.*,tr_pengajuan_bbn.id_dealer FROM tr_monout_piutang_bbn 
		  	INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd 
		  	JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
      	WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') and tr_faktur_stnk.status_bayar !='lunas'
      	AND tr_faktur_stnk.status_faktur='approved' AND tr_pengajuan_bbn.id_dealer='$id_dealer'");

		  
			// $dt_rekap = $this->db->query("
			// 	SELECT tr_monout_piutang_bbn.no_rekap, tgl_rekap , tr_monout_piutang_bbn.no_bastd , total, status_mon ,
			// 	tr_pengajuan_bbn.id_dealer, sum(tr_penerimaan_bank_detail.nominal) as pembayaran
			// 	FROM tr_monout_piutang_bbn 
			// 	INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
			// 	JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
			// 	join tr_penerimaan_bank_detail on tr_penerimaan_bank_detail.referensi = tr_faktur_stnk.no_bastd 
			// 	join tr_penerimaan_bank on tr_penerimaan_bank.id_penerimaan_bank = tr_penerimaan_bank_detail.id_penerimaan_bank  and tr_penerimaan_bank.status ='approved'
			// 	WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'
			// 	AND tr_pengajuan_bbn.id_dealer=$id_dealer
			// 	group by tr_monout_piutang_bbn.no_rekap, tgl_rekap , tr_monout_piutang_bbn.no_bastd , total, status_mon
			// 	having total > pembayaran"
			// );
		
			foreach ($dt_rekap->result() as $isi) {		 

				$cek = $this->m_admin->cekPembayaran($isi->no_bastd,$isi->total);
				if($cek>0){            
					$data[]=['referensi'=>$isi->no_bastd,
							'tgl_jatuh_tempo'=>'-',
							'nominal'=>$cek
							];
				}
		  	}
		}//dealer
		if ($tipe_customer=='Lain-lain') {
			$data_monout = $this->db->query("SELECT * FROM tr_monout_bantuan_bbn LEFT JOIN ms_tipe_kendaraan ON tr_monout_bantuan_bbn.tipe = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON ms_warna.id_warna = tr_monout_bantuan_bbn.warna WHERE tr_monout_bantuan_bbn.status_mon <> 'Lunas'");
			foreach ($data_monout->result() as $mo) {
				$cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$mo->no_faktur'")->row();  
          		$cek = $this->m_admin->cekPembayaran($mo->no_faktur,$mo->total);

				if($cek>$cek_ref->jum){            
		        $data[]=['referensi'=>$mo->no_faktur,
		        		 'tgl_jatuh_tempo'=>'-',
		        		 'nominal'=>$cek
		    			];
		      }
			}
		}
		if ($tipe_customer=='Vendor') {
			// $dt_vendor = $this->db->query("SELECT * FROM tr_rekap_ekspedisi INNER JOIN ms_vendor ON tr_rekap_ekspedisi.id_vendor = ms_vendor.id_vendor
			// 	WHERE tr_rekap_ekspedisi.id_vendor='$id_vendor'
   //              ORDER BY tr_rekap_ekspedisi.id_rekap_ekspedisi DESC");    
   //        	foreach($dt_vendor->result() as $row) {                                         
   //          	$tr = $this->db->query("SELECT SUM(total) as jum FROM tr_rekap_ekspedisi_detail WHERE id_rekap_ekspedisi = '$row->id_rekap_ekspedisi'")->row();
   //          	$cek_ref = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$row->id_rekap_ekspedisi'")->row();
   //          	if($tr->jum > $cek_ref->jum){            
			//     $data[]=['referensi'=>$row->id_rekap_ekspedisi,
			//         		 'tgl_jatuh_tempo'=>'-',
			//         		 'nominal'=>$tr->jum-$cek_ref->jum
			//     			];
			//       }
   //         	}
			$dt_checker = $this->db->query("SELECT * FROM tr_checker INNER JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker
        INNER JOIN tr_shipping_list ON tr_checker_detail.no_mesin = tr_shipping_list.no_mesin
        LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
        WHERE tr_checker.status_checker = 'close' AND tr_checker.ekspedisi = '$id_vendor'");          
      foreach($dt_checker->result() as $row) {                                                     
        $harga_jasa = ($row->harga_jasa != "") ? $row->harga_jasa : "0" ;
        $biaya_pasang = $harga_jasa + $row->ongkos_kerja;
        $total = $biaya_pasang + $row->harga_md_dealer;							
        $cek = $this->m_admin->cekPembayaran($row->id_checker,$total);
				if($cek>0){            
		        $data[]=['referensi'=>$row->id_checker,
		        		 'tgl_jatuh_tempo'=>'-',
		        		 'nominal'=>$cek
		    			];
		      }
			}
		}
		$dt['modal']= 'referensi';
		$dt['data'] = $data;
		$this->load->view('h1/t_penerimaan_detail',$dt);
	}

	public function edit()
	{				
		$id            = $this->input->get('id');
		$data['isi']   = $this->isi;		
		$data['page']  = $this->page;		
		$data['title'] = $this->title;															
		$data['set']   = "edit";		
		$row = $this->db->get_where('tr_penerimaan_bank',['id_penerimaan_bank'=>$id]);
		if ($row->num_rows()>0) {
			$data['row']     = $row->row();
			$data['details'] = $this->db->query("SELECT *,tr_penerimaan_bank_detail.nominal as sisa_hutang FROM tr_penerimaan_bank_detail 
				JOIN ms_coa ON tr_penerimaan_bank_detail.kode_coa=ms_coa.kode_coa
				WHERE id_penerimaan_bank='$id'")->result();
			$data['bg_'] = $this->db->get_where('tr_penerimaan_bank_bg',['id_penerimaan_bank'=>$id]);
			$data['transfers'] = $this->db->get_where('tr_penerimaan_bank_transfer',['id_penerimaan_bank'=>$id]);
			$this->template($data);			
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_penerimaan_bank'>";		

		}
	}

	public function save_edit()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		
		$pk       = $this->pk;
		$id       = $this->input->post($pk);
			
		$data['account'] = $this->input->post('account');
		$id_penerimaan   = $data['id_penerimaan_bank'] 	= $this->input->post('id_penerimaan_bank');
		$data['tgl_entry']     = $this->input->post('tgl_entry');					
		$data['tipe_customer'] = $this->input->post('tipe_customer');					
		$tipe_customer         = $this->input->post('tipe_customer');					
		if($tipe_customer=="Vendor"){
			$data['dibayar'] 				= $this->input->post('dibayar_v');					
		}elseif($tipe_customer=="Dealer"){
			$data['dibayar'] 				= $this->input->post('dibayar_d');					
		}else{
			$data['dibayar'] 				= $this->input->post('dibayar_l');					
		}			
		$data['pph']              = $this->input->post('pph');					
		$data['jenis_bayar']      = $this->input->post('jenis_bayar');					
		$data['total_pembayaran'] = $this->input->post('total_pembayaran');					
		$data['rekening_tujuan']  = $this->input->post('rekening_tujuan');					
		$via_bayar = $data['via_bayar']        = $this->input->post('via_bayar');

		// Detail
		$kode_coa    = $this->input->post('kode_coa');
		$coa         = $this->input->post('coa');
		$referensi   = $this->input->post('referensi');
		$nominal     = $this->input->post('nominal');
		$sisa_hutang = $this->input->post('sisa_hutang');
		$keterangan  = $this->input->post('keterangan');
		foreach ($kode_coa as $key=> $val) {
			$details[$key] =['id_penerimaan_bank'   =>$id_penerimaan,
							'kode_coa'    =>$kode_coa[$key],
							'coa'         =>$coa[$key],
							'referensi'   =>$referensi[$key],
							'nominal'     =>preg_replace("/[^0-9]/", "", $nominal[$key]),
							'sisa_hutang' =>preg_replace("/[^0-9]/", "", $sisa_hutang[$key]),
							'keterangan'  =>$keterangan[$key]
			] ;
		}

		if ($via_bayar=='BG') {
			$no_bg      = $this->input->post('no_bg');
			$tgl_bg     = $this->input->post('tgl_bg');
			$nominal_bg = $this->input->post('nominal_bg');
			foreach ($no_bg as $key=> $val) {
				$bg_[$key] =['id_penerimaan_bank'   =>$id_penerimaan,
					'no_bg'      =>$no_bg[$key],
					'tgl_bg'     =>$tgl_bg[$key],
					'nominal_bg' =>preg_replace("/[^0-9]/", "", $nominal_bg[$key])
				] ;			
			}
		}	
		if ($via_bayar=='Transfer') {
			$tgl_transfer     = $this->input->post('tgl_transfer');
			$nominal_transfer = $this->input->post('nominal_transfer');
			foreach ($tgl_transfer as $key=> $val) {
				$transfers[$key] =['id_penerimaan_bank'   =>$id_penerimaan,
					'tgl_transfer'     =>$tgl_transfer[$key],
					'nominal_transfer' =>preg_replace("/[^0-9]/", "", $nominal_transfer[$key])
				] ;
			}			
		}		

		$data['status']           = "input";		
		$data['updated_at']       = $waktu;		
		$data['updated_by']       = $login_id;
		
		$this->db->trans_begin();
			$this->db->update('tr_penerimaan_bank',$data,['id_penerimaan_bank'=>$id_penerimaan]);
			$this->db->delete('tr_penerimaan_bank_detail',['id_penerimaan_bank'=>$id_penerimaan]);
			$this->db->delete('tr_penerimaan_bank_bg',['id_penerimaan_bank'=>$id_penerimaan]);
			$this->db->delete('tr_penerimaan_bank_transfer',['id_penerimaan_bank'=>$id_penerimaan]);
			$this->db->insert_batch('tr_penerimaan_bank_detail',$details);
			if (isset($bg_)) {
				$this->db->insert_batch('tr_penerimaan_bank_bg',$bg_);
			}
			if (isset($transfers)) {
				$this->db->insert_batch('tr_penerimaan_bank_transfer',$transfers);
			}
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_penerimaan_bank/edit?id=".$id_penerimaan."'>";			
      }		
	}
}