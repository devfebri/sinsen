<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_dealer_new extends CI_Controller {

	var $tables = "tr_penerimaan_ksu_dealer";	
	var $folder = "dealer";
	var $page   = "po_dealer_new";
	var $pk     = "id_penerimaan_ksu_dealer";
	var $title  = "PO Dealer";
   	var $order_column_order = array('po_number',null,'unit_qty','po_type','submission_deadline','ket',null); 
   	var $order_column_item = array('id_item','tipe_ahm','warna',null); 


	public function __construct()
	{		
		parent::__construct();		

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tgl_indo');

		//===== Load Model =====
		$this->load->model('m_admin');		

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();		
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}


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
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;															
		$data['set']   = "view";
		$id_dealer     = $this->m_admin->cari_dealer();
		$this->template($data);			
	}

	public function tes_tgl()
	{
		$date1=date('Y-m-d');
		  $date2='2019-07-22';
		  $datetime1 = new DateTime($date1);
		  $datetime2 = new DateTime($date2);
		  $difference = $datetime1->diff($datetime2);
		  echo $difference->days;
	}
	public function fetch_order()
   {
		$fetch_data = $this->make_datatables_order();  
		$data       = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array      = array();
			$sub_array[]    = '<a href='.base_url('dealer/po_dealer_new/detail?id='.$rs->id_po).'>'.$rs->id_po.'</a>';
			$tot_unit = $this->db->query("SELECT SUM(qty_order) AS c FROM tr_po_dealer_detail WHERE id_po='$rs->id_po'")->row()->c;
			$sub_array[]    = medium_bulan($rs->bulan).' '.$rs->tahun;
			$sub_array[]    = $tot_unit;
			$sub_array[]    = strtoupper($rs->jenis_po);
			$sub_array[]    = $rs->submission_deadline;

			// Remarks
			$datetime1   = new DateTime(date('Y-m-d'));
			$datetime2   = new DateTime($rs->submission_deadline);
			$difference  = $datetime1->diff($datetime2);
			$sub_array[] = $difference->days.' days';

			$button         = '';
			$button3        = '<a href='.base_url('dealer/po_dealer_new/edit?id='.$rs->id_po).' class="btn btn-warning btn-flat btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
			$button1        = '<a onclick="return confirm(\'Are you sure Send To MD ?\')" href='.base_url('dealer/po_dealer_new/submit_md?id='.$rs->id_po).' class="btn btn-primary btn-xs btn-flat mb-10"><i class="fa fa-send"></i> Send To MD</a></br>';
			$button2        = '<a onclick="return confirm(\'Are you sure to cancel this data ?\')" href='.base_url('dealer/po_dealer_new/cancel_by_dealer?id='.$rs->id_po).' class="btn btn-danger btn-xs btn-flat mb-10"><i class="fa fa-close"></i> Cancelled by Dealer</a></br>';
			if ($rs->status =='input') {
				$button = $button1.$button2.$button3;
			}
			if ($rs->status=='returned_po') {
				$button = $button3;
			}
			if ($rs->status=='submitted') {
				$button = $button2;
			}
			$sub_array[] = $button;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_order(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   public function fetch_tracking()
   {
		$fetch_data = $this->make_datatables_order();  
		$data       = array();  
		foreach($fetch_data as $rs)  
		{  
			$tot_unit = $this->db->query("SELECT SUM(qty_order) AS c FROM tr_po_dealer_detail WHERE id_po='$rs->id_po'")->row()->c;
			$sub_array = array();
			$sub_array[] = '<a href='.base_url('dealer/po_dealer_new/detail?id='.$rs->id_po).'>'.$rs->id_po.'</a>';
			$sub_array[] = medium_bulan($rs->bulan).' '.$rs->tahun;
			$sub_array[] = $tot_unit;
			$sub_array[] = strtoupper($rs->jenis_po);
			$sub_array[] = $rs->tgl;
			$input='';$submitted='';$processed='';$closed='';$returned_po='';$rejected='';$cancel='';
			if ($rs->status=='input') {
				$input='active';
			}
			if ($rs->status=='submitted') {
				$input='active';$submitted='active';
			}
			if ($rs->status=='approved') {
				$input='active';$submitted='active';$processed='active';
			}
			if ($rs->status=='closed') {
				$input='active';$submitted='active';$processed='active';$closed='active';
			}
			if ($rs->status=='returned_po') {
				$returned_po='warning';
			}
			if ($rs->status=='rejected') {
				$rejected='danger';
			}
			if ($rs->status=='Cancelled by Dealer') {
				$cancel='warning';
			}
			$button		 = '<div class="col-md-10" style="padding:0;width:55%">
					        <ul class="progress-indicator" style="margin-bottom: 0px;">
					        <li class="'.$input.'"><span class="bubble"></span>
					            Draft
					        </li>
					        <li class="'.$submitted.'"><span class="bubble"></span>Submitted</li>
					        <li class="'.$processed.'"><span class="bubble"></span>Processed</li>
					        <li class="'.$closed.'"><span class="bubble"></span>Closed</li>
					      </ul>
					      </div>
					      <div class="col-md-1" style="padding:0;width:15%">
					        <ul class="progress-indicator_one" style="margin-bottom: 0px;">
						        <li class="'.$returned_po.'"><span class="bubble"></span>Returned</li>
						      </ul>
					      </div>
					      <div class="col-md-1" style="padding:0;width:15%">
					        <ul class="progress-indicator_one" style="margin-bottom: 0px;">
					        <li class="'.$rejected.'"><span class="bubble"></span>Rejected</li>
					      </ul>
					      </div>
						  <div class="col-md-1" style="padding:0;width:15%">
					        <ul class="progress-indicator_one" style="margin-bottom: 0px;">
					        <li class="'.$cancel.'"><span class="bubble"></span>Cancelled</li>
					      </ul>
					      </div>
					      ';
			$sub_array[] = $button;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_order(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   

   function make_query_order()  
   {  
	 $id_dealer     = $this->m_admin->cari_dealer();
     $this->db->select('*');  
     $this->db->from('tr_po_dealer');
     // $this->db->join('ms_item', 'ms_kelompok_md.id_item = ms_item.id_item','left');

     // if($_POST["start_date"] != '' && $_POST["end_date"] != ''){
     //      $start_date = $this->input->post('start_date');
     //      $end_date   = $this->input->post('end_date');
     //      // $this->db->where('tr_purchase_request.purchase_date>=',$start_date);
     //      // $this->db->where('tr_purchase_request.purchase_date<=',$end_date);
     //      $searchs ='';

     //      if($this->input->post('search')['value'] !='')
     //      {   
     //          $search = $this->input->post('search')[' value'];
     //          if ($search!='') {
     //              $searchs = "AND (sq_number LIKE '%$search%' 
     //                  OR ms_customer.name_cust LIKE '%$search%'
     //                  OR sr.tanggal LIKE '%$search%'
     //              )";
     //          }
     //          // $this->db->or_like("sq_number", $_POST["search"]["value"]);  
     //          // $this->db->or_like("name_cust", $_POST["search"]["value"]);   
     //       }

     //      $this->db->where("(sr.tanggal BETWEEN '$start_date' AND '$end_date') $searchs", NULL, false);
     //  }
     $this->db->where("id_dealer='$id_dealer' ");

     $search = $this->input->post('search')['value'];
	  if ($search!='') {
	      $searchs = "(id_po LIKE '%$search%' 
	          OR unit_qty LIKE '%$search%'
	          OR po_type LIKE '%$search%'
	          OR submission_deadline LIKE '%$search%'
	          OR ket LIKE '%$search%'
	      )";
	      $this->db->where("$searchs", NULL, false);
	  }
	 if(isset($_POST["status"])){  
	 	if ($_POST['status']!='') {
	 		$this->db->where('status', $_POST["status"]);
	 	}
	 }
	 if(isset($_POST["period"])){  
	 	if ($_POST['period']!='') {
	 		$period = explode('-', $_POST["period"]);
	 		$this->db->where('bulan', $period[1]);
	 		$this->db->where('tahun', $period[0]);
	 	}
	 }
	 if(isset($_POST["po_type"])){  
	 	if ($_POST['po_type']!='') {
	 		$this->db->where('po_type', $_POST["po_type"]);
	 	}
	 }
	 if(isset($_POST["tgl_order"])){  
	 	if ($_POST['tgl_order']!='') {
	 		$this->db->where('tgl_order', $_POST["tgl_order"]);
	 	}
	 }
     if(isset($_POST["order"]))  
     {  
          $this->db->order_by($this->order_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
     }  
     else  
     {  
          $this->db->order_by('created_at', 'DESC');  
     }  
   }  
   function make_datatables_order(){
		$this->make_query_order();  
		if($_POST["length"] != -1)  
		{  
			$this->db->limit($_POST['length'], $_POST['start']);  
		}  
		$query = $this->db->get();  
		return $query->result();  
   }  
   function get_filtered_data_order(){  
		$this->make_query_order();  
		$query = $this->db->get();  
		return $query->num_rows();  
   }  

	public function tracking()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;															
		$data['set']   = "tracking";
		$id_dealer     = $this->m_admin->cari_dealer();
		$this->template($data);			
	}

	public function add()
	{				
		$po_type = $data['po_type'] = $this->input->get('type');
		if ($po_type=='' || $po_type==null) {
			redirect('dealer/po_dealer_new');
		}elseif($po_type=='reg'|| $po_type=='add'){
			$data['isi']       = $this->page;		
			$data['title']     = $this->title;
			$data['set']       = "form";
			$data['mode']      = "insert";
			// $data['po_number'] = $this->newPO_ID($po_type);
			$data['po_number'] = '';
			$data['tipe_unit'] = $this->db->get_where('ms_tipe_kendaraan',['active'=>1]);
			$data['set_md']    = $this->db->get('ms_setting_h1')->row();
			$id_dealer         = $this->m_admin->cari_dealer();
			$this->template($data);	
		}else{
			redirect('dealer/po_dealer_new');
		}
	}

	public function detail()
	{				
		$po_number = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;
		$data['set']       = "form";
		$data['mode']      = "detail";
		$data['tipe_unit'] = $this->db->get_where('ms_tipe_kendaraan',['active'=>1]);
		$data['set_md']    = $this->db->get('ms_setting_h1')->row();
		$id_dealer         = $this->m_admin->cari_dealer();
		$cek_data 		   = $this->db->query("SELECT *,CASE WHEN jenis_po='PO Additional' THEN 'add' ELSE 'reg' END as po_type FROM tr_po_dealer WHERE id_po='$po_number' AND id_dealer=$id_dealer ");
		if ($cek_data->num_rows()>0) {
			$data['po_number'] = $po_number;
			$data['row']       = $cek_data->row();
			$data['details']   = $this->db->query("SELECT tpodd.*, qty_order AS po_fix,ms_item.id_tipe_kendaraan,ms_item.id_warna,warna,tipe_ahm AS tipe_unit 
				FROM tr_po_dealer_detail AS tpodd
				LEFT JOIN ms_item ON tpodd.id_item=ms_item.id_item 
				LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON ms_warna.id_warna=ms_item.id_warna
			WHERE id_po='$po_number'
			")->result();
			$this->template($data);	
		}else{
			redirect('dealer/po_dealer_new');
		}
	}

	public function edit()
	{				
		$id_po = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;
		$data['set']       = "form";
		$data['mode']      = "edit";
		$data['tipe_unit'] = $this->db->get_where('ms_tipe_kendaraan',['active'=>1]);
		$data['set_md']    = $this->db->get('ms_setting_h1')->row();
		$id_dealer         = $this->m_admin->cari_dealer();
		$cek_data          = $this->db->query("SELECT *,CASE WHEN jenis_po='PO Additional' THEN 'add' ELSE 'reg' END as po_type FROM tr_po_dealer WHERE id_po='$id_po' AND id_dealer='$id_dealer' AND (status='input' OR status='returned_po') ");
		if ($cek_data->num_rows()>0) {
			$data['po_number'] = $id_po;
			$data['row']       = $cek_data->row();
			$get_detail   = $this->db->query("SELECT tr_po_dealer_detail.*,tipe_ahm,warna,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna FROM tr_po_dealer_detail 
				JOIN ms_item ON ms_item.id_item=tr_po_dealer_detail.id_item
				JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=ms_item.id_warna
			WHERE id_po='$id_po'
			");
			// $set_md   = $this->db->get('ms_setting_h1')->row();
			foreach ($get_detail->result() as $dt) {
				// $max_po_fix = floor($dt->po_t1_last + ($dt->po_t1_last * ($set_md->po_fix_dealer/100)));
				// $min_po_fix = ceil($dt->po_t1_last - ($dt->po_t1_last * ($set_md->po_fix_dealer/100)));
				// $max_po_t1  = floor($dt->po_t2_last + ($dt->po_t2_last * ($set_md->po_t1_dealer/100)));
				// $min_po_t1  = ceil($dt->po_t2_last - ($dt->po_t2_last * ($set_md->po_t1_dealer/100)));
				$details[] = [
				          'id_item' =>$dt->id_item,
				          'id_tipe_kendaraan' =>$dt->id_tipe_kendaraan,
				          'tipe_unit' => $dt->tipe_ahm,
				          'id_warna' => $dt->id_warna,
				          'warna' => $dt->warna,
				          'current_stock' => $dt->current_stock,
				          'monthly_sale' => $dt->monthly_sale,
				          'po_t1_last' => $dt->po_t1_last,
				          'po_t2_last' => $dt->po_t2_last,
				          'po_fix' => $dt->qty_order,
				          'qty_po_t1' => $dt->qty_po_t1,
				          'qty_po_t2' => $dt->qty_po_t2,
				          'qty_indent' => $dt->qty_indent,
				          'harga' => $dt->harga,
				          'total_harga' => $dt->total_harga,
				          'min_po_fix' => $dt->min_po_fix,
				          'max_po_fix' => $dt->max_po_fix,
				          'min_po_t1' => $dt->min_po_t1,
				          'max_po_t1' => $dt->max_po_t1
				        ];
			}
			$data['details'] = $details;
			$this->template($data);	
		}else{
			redirect('dealer/po_dealer_new');
		}
	}

	public function submit_md()
	{				
		$waktu     = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		
		$id_po = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		$cek_data  = $this->db->get_where('tr_po_dealer',['id_po'=>$id_po,'id_dealer'=>$id_dealer,'status'=>'input']);
		if ($cek_data->num_rows()>0) {
			$data['status'] = 'submitted';

			$dealer            = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$row = $cek_data->row();
			// $po_type = $row->po_type=='reg'?'Regular':'Additional';
			
			$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>1])->row();
			$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
					'id_referensi' => $id_po,
					'judul'        => "$row->jenis_po Dealer $dealer->nama_dealer",
					'pesan'        => "Terdapat $row->jenis_po Unit Dari Dealer $dealer->nama_dealer. PO Number = $id_po",
					'link'         => $ktg_notif->link.'/detail?id='.$id_po,
					'status'       =>'baru',
					'created_at'   => $waktu,
					'created_by'   => $login_id
    			 ];

			$this->db->trans_begin();
	        	$this->db->update('tr_po_dealer',$data,['id_po'=>$id_po]);
	        	$this->db->insert('tr_notifikasi',$notif);
	        	// $this->m_admin->saveNotifikasi($notif,1);
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$response['status'] ='error';
				$response['msg']    ='Something went wrong';
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
				$_SESSION['pesan'] 	= "Data has been sent successfully";
				$_SESSION['tipe'] 	= "success";
				redirect('dealer/po_dealer_new');
	      	}	
		}else{
			redirect('dealer/po_dealer_new');
		}
	}

	public function cancel_by_dealer()
	{				
		$po_number = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		// $cek_data  = $this->db->get_where('tr_po_dealer_new',['po_number'=>$po_number,'id_dealer'=>$id_dealer,'status'=>'input']);
		$cek_data = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_po='$po_number' AND id_dealer='$id_dealer' AND (status='input' OR status='submitted') ");
		if ($cek_data->num_rows()>0) {
			$data['status'] = 'Cancelled by Dealer';
			$this->db->update('tr_po_dealer',$data,['id_po'=>$po_number]);
			$_SESSION['pesan'] 	= "Data has been cancelled";
			$_SESSION['tipe'] 	= "success";
			redirect('dealer/po_dealer_new');
		}else{
			redirect('dealer/po_dealer_new');
		}
	}

	public function getDetail()
	{	
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna          = $this->input->post('id_warna');
		$id_item           = $id_tipe_kendaraan.'-'.$id_warna;
		$id_dealer         = $this->m_admin->cari_dealer();
		$dealer            = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		$id_item           = $id_tipe_kendaraan.'-'.$id_warna;

		$current_stock = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.id_item = '$id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
                AND tr_scan_barcode.status = '4'")->row()->jum;

		$po_last = $this->db->query("SELECT tr_po_dealer_detail.* FROM tr_po_dealer_detail 
			JOIN tr_po_dealer ON tr_po_dealer_detail.id_po=tr_po_dealer.id_po
			WHERE jenis_po='PO Reguler' 
			AND id_dealer='$id_dealer'
			AND id_item='$id_item'
			AND (status!='input' OR status!='Cancelled by Dealer' OR status!='submitted' OR status!='rejected')
			ORDER BY id_po DESC LIMIT 0,1
			");
		$po_t1_last =0;
		$po_t2_last =0;
		if ($po_last->num_rows()>0) {
			$po_last = $po_last->row();
			$po_t1_last = $po_last->qty_po_t1;
			$po_t2_last = $po_last->qty_po_t2;
		}

		$harga = 0;
		$date  =date('Y-m-d');
		$cek_harga = $this->db->query("SELECT harga_jual FROM ms_kelompok_md WHERE id_item='$id_item' AND id_kelompok_harga='$dealer->id_kelompok_harga' AND start_date<='$date' ORDER BY start_date DESC LIMIT 0,1 ");
		if ($cek_harga->num_rows()>0) {
			$harga = $cek_harga->row()->harga_jual;
		}
		$qty_indent = $this->db->query("SELECT COUNT(id_indent) AS tot FROM tr_po_dealer_indent WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna='$id_warna' AND id_dealer='$id_dealer' AND status='requested' ")->row()->tot;
		$response = ['current_stock' => (int)$current_stock,
					 'monthly_sale'  => 0,
					 'po_t1_last'    => (int)$po_t1_last,
					 'po_t2_last'    => (int)$po_t2_last,
					 'qty_indent'    => (int)$qty_indent,
					 'harga'         => (int)$harga
					];
		echo json_encode($response);
	}
	public function tes_id()
	{
		echo $this->newPO_ID('add');
	}
	public function newPO_ID($po_type)
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('Ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		if ($po_type=='reg') {
			$get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
	   			if ($th_bln==$thbln_po) {
					$id       = substr($row->id_po,-4);
					$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id+1);
		   			$i = 0;
					while ($i<1) {
						$cek = $this->db->get_where('tr_po_dealer',['id_po'=>$new_kode])->num_rows();
					    if ($cek>0) {
							$neww     = substr($new_kode, -4);
							$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$neww+1);
							$i        = 0;
					    }else{
					    	$i++;
					    }
					}
	   			}else{
	   				$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   			}
	   		}else{
	   			$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
		}else {
			$get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				// $thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
	   			// if ($th_bln==$thbln_po) {
					$id       = substr($row->id_po,-4);
					$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id+1);
		   			$i = 0;
					while ($i<1) {
						$cek = $this->db->get_where('tr_po_dealer',['id_po'=>$new_kode])->num_rows();
					    if ($cek>0) {
							$neww     = substr($new_kode, -4);
							$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$neww+1);
							$i        = 0;
					    }else{
					    	$i++;
					    }
					}
	   			// }else{
	   			// 	$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   			// }
	   		}else{
	   			$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
		}
   		return strtoupper($new_kode);
	}

	public function save()
	{		
		$waktu       = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id    = $this->session->userdata('id_user');
		
		$id_dealer   = $this->m_admin->cari_dealer();
		$save_to     = $this->input->post('save_to');
		$po_type_min = $po_type     = $this->input->post('po_type');
		if ($po_type =='reg')$jenis_po = 'PO Reguler';
		if ($po_type =='add')$jenis_po = 'PO Additional';
		$po_type     = $data['jenis_po'] = $jenis_po;
		$id_po       = $data['id_po'] = $this->newPO_ID($po_type_min);
		$set_md      = $this->db->get('ms_setting_h1')->row();
		if ($po_type_min=='reg') {
			$tgl_po = date('Y-m-d');
			$tgl         = date('d');
			$deadline    = $set_md->deadline_po_dealer;
	      	if ($tgl>$deadline) {
				$bulan=date('m')+2;
	        	$tahun = date('Y');
		        if ($bulan>12) {
		          	$bulan=2;
		          	$tahun=$tahun+1;
		        }
	      	}else{
	        	$bulan = date('m')+1;
	        	$tahun=date('Y');
	        	if ($bulan>12) {
	          		$bulan=1;
	          		$tahun=$tahun+1;
	        	}
	      	}
		}else{
			$tgl    = explode('-', $this->input->post('tgl'));
			$tgl_po = $this->input->post('tgl');
			$bulan  = $tgl[1];
			$tahun  = $tgl[0];
			$deadline = $tgl[2];
		}
		$data['bulan']               = sprintf("%'.02d",$bulan);
		$data['tahun']               = $tahun;
		$data['tgl']                 = $tgl_po;
		$data['id_dealer']           = $id_dealer;
		$data['created_at']          = $waktu;
		$data['created_by']          = $login_id;
		$data['status']              = $save_to=='input'?'input':'submitted';
		$data['submission_deadline'] = $tahun.'-'.$bulan.'-'.$deadline;
		$data['id_pos_dealer']       = '';

		if ($save_to=='submitted') {
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>1])->row();
			$notif     = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
				'id_referensi' => $id_po,
				'judul'        => "PO $po_type Dealer $dealer->nama_dealer",
				'pesan'        => "Terdapat PO $po_type Unit Dari Dealer $dealer->nama_dealer. PO Number = $id_po",
				'link'         => $ktg_notif->link.'/detail?id='.$id_po,
				'status'       => 'baru',
				'created_at'   => $waktu,
				'created_by'   => $login_id
			 ];
		}
		$details = $this->input->post('detail');
		$unit_qty = 0;
		foreach ($details as $key=>$dtl) {
			$qty_indent        = $po_type_min=='reg'?$dtl['qty_indent']:null;
			$unit_qty          += ($dtl['po_fix']+$qty_indent);
			$id_tipe_kendaraan = $dtl['id_tipe_kendaraan'];
			$id_warna          = $dtl['id_warna'];
			// $dt_details[] = [ 'id_po'=>$id_po,
			// 			'type_code'        => $id_tipe_kendaraan,
			// 			'unit_type'        => $dtl['tipe_unit'],
			// 			'color_code'       => $id_warna,
			// 			'unit_description' => $dtl['tipe_unit'],
			// 			'unit_color'       => $dtl['warna'],
			// 			'current_stock'    => $po_type=='reg'?$dtl['current_stock']:null,
			// 			'monthly_sale'     => $po_type=='reg'?$dtl['monthly_sale']:null,
			// 			'po_t1_last'       => $po_type=='reg'?$dtl['po_t1_last']:null,
			// 			'po_t2_last'       => $po_type=='reg'?$dtl['po_t2_last']:null,
			// 			'po_fix'           => $dtl['po_fix'],
			// 			'qty_po_t1'        => $po_type=='reg'?$dtl['qty_po_t1']:null,
			// 			'qty_po_t2'        => $po_type=='reg'?$dtl['qty_po_t2']:null,
			// 			'qty_indent'       => $qty_indent,
			// 			'max_po_t1'        => $po_type=='reg'?$dtl['max_po_t1']:null,
			// 			'min_po_t1'        => $po_type=='reg'?$dtl['min_po_t1']:null,
			// 			'max_po_fix'       => $po_type=='reg'?$dtl['max_po_fix']:null,
			// 			'min_po_fix'       => $po_type=='reg'?$dtl['min_po_fix']:null,
			// 			'harga'            => $po_type=='reg'?preg_replace("/[^0-9]/", "", $dtl['harga']):null,
			// 			'total_harga'      => $po_type=='reg'?preg_replace("/[^0-9]/", "", $dtl['total_harga']):null,
			// 		  ];

			if ($po_type_min=='reg') {
				$dt_details[] = [ 'id_po'=>$id_po,
						'id_item'       => $id_tipe_kendaraan.'-'.$id_warna,
						'current_stock' => $dtl['current_stock'],
						'monthly_sale'  => $dtl['monthly_sale'],
						'po_t1_last'    => $dtl['po_t1_last'],
						'po_t2_last'    => $dtl['po_t2_last'],
						'qty_po_fix'    => $dtl['po_fix'],
						'qty_po_t1'     => $dtl['qty_po_t1'],
						'qty_po_t2'     => $dtl['qty_po_t2'],
						'qty_order'     => $dtl['po_fix'],
						'max_po_t1'     => $dtl['max_po_t1'],
						'min_po_t1'     => $dtl['min_po_t1'],
						'max_po_fix'    => $dtl['max_po_fix'],
						'min_po_fix'    => $dtl['min_po_fix'],
						'qty_indent'    => $qty_indent,
						'harga'         => preg_replace("/[^0-9]/", "", $dtl['harga']),
						'total_harga'   => preg_replace("/[^0-9]/", "", $dtl['total_harga']),
					  ];
				$indent = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna='$id_warna' AND id_dealer='$id_dealer' AND status='requested' AND status_monitoring IS NULL");
				foreach ($indent->result() as $ind) {
					$upd_ind[] =['id_indent' => $ind->id_indent,
								'po_number'         => $id_po,
								'status_monitoring' => 'po_created',
							];
				}
			}else{
				$dt_details[] = [ 'id_po'=>$id_po,
						'id_item'   => $id_tipe_kendaraan.'-'.$id_warna,
						'qty_order' => $dtl['po_fix'],
					  ];
			}
		}
		// $data['unit_qty'] = $unit_qty;
		$this->db->trans_begin();
			$this->db->insert('tr_po_dealer',$data);
			$this->db->insert_batch('tr_po_dealer_detail',$dt_details);
			if (isset($notif)) {
	        	$this->db->insert('tr_notifikasi',$notif);
			}
			if (isset($upd_ind)) {
				$this->db->update_batch('tr_po_dealer_indent',$upd_ind,'id_indent');
			}
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$response['status'] ='error';
			$response['msg']    ='Something went wrong';
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			
			$response['status'] ='sukses';
      	}		
      	echo json_encode($response);					
	}	

	public function save_edit()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$date      = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();
		$set_md    = $this->db->get('ms_setting_h1')->row();
		
		$save_to   = $this->input->post('save_to');
		$po_number = $this->input->post('po_number');

		$po_type_min = $po_type          = $this->input->post('po_type');
		if ($po_type =='reg')$jenis_po   = 'PO Reguler';
		if ($po_type =='add')$jenis_po   = 'PO Additional';
		$po_type     = $data['jenis_po'] = $jenis_po;
		if ($po_type_min=='add') {
			$tgl                         = explode('-', $this->input->post('tgl'));
			$tgl_po                      = $this->input->post('tgl');
			$bulan                       = $tgl[1];
			$tahun                       = $tgl[0];
			$deadline                    = $tgl[2];
			$data['bulan']               = sprintf("%'.02d",$bulan);
			$data['tahun']               = $tahun;
			$data['tgl']                 = $tgl_po;
			$data['submission_deadline'] = $tahun.'-'.$bulan.'-'.$deadline;
		}
		$data['id_dealer']           = $id_dealer;
		$data['updated_at']          = $waktu;
		$data['updated_by']          = $login_id;
		$data['status']              = $save_to=='input'?'input':'submitted';
		
		if ($save_to=='submitted') {
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>1])->row();
			$notif     = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
				'id_referensi' => $id_po,
				'judul'        => "PO $po_type Dealer $dealer->nama_dealer",
				'pesan'        => "Terdapat PO $po_type Unit Dari Dealer $dealer->nama_dealer. PO Number = $id_po",
				'link'         => $ktg_notif->link.'/detail?id='.$id_po,
				'status'       => 'baru',
				'created_at'   => $waktu,
				'created_by'   => $login_id
			 ];
		}
		$details = $this->input->post('detail');
		$unit_qty = 0;
		foreach ($details as $key=>$dtl) {
			$qty_indent        = $po_type_min=='reg'?$dtl['qty_indent']:null;
			$unit_qty          += ($dtl['po_fix']+$qty_indent);
			$id_tipe_kendaraan = $dtl['id_tipe_kendaraan'];
			$id_warna          = $dtl['id_warna'];
			// $dt_details[] = [ 'id_po'=>$id_po,
			// 			'type_code'        => $id_tipe_kendaraan,
			// 			'unit_type'        => $dtl['tipe_unit'],
			// 			'color_code'       => $id_warna,
			// 			'unit_description' => $dtl['tipe_unit'],
			// 			'unit_color'       => $dtl['warna'],
			// 			'current_stock'    => $po_type=='reg'?$dtl['current_stock']:null,
			// 			'monthly_sale'     => $po_type=='reg'?$dtl['monthly_sale']:null,
			// 			'po_t1_last'       => $po_type=='reg'?$dtl['po_t1_last']:null,
			// 			'po_t2_last'       => $po_type=='reg'?$dtl['po_t2_last']:null,
			// 			'po_fix'           => $dtl['po_fix'],
			// 			'qty_po_t1'        => $po_type=='reg'?$dtl['qty_po_t1']:null,
			// 			'qty_po_t2'        => $po_type=='reg'?$dtl['qty_po_t2']:null,
			// 			'qty_indent'       => $qty_indent,
			// 			'max_po_t1'        => $po_type=='reg'?$dtl['max_po_t1']:null,
			// 			'min_po_t1'        => $po_type=='reg'?$dtl['min_po_t1']:null,
			// 			'max_po_fix'       => $po_type=='reg'?$dtl['max_po_fix']:null,
			// 			'min_po_fix'       => $po_type=='reg'?$dtl['min_po_fix']:null,
			// 			'harga'            => $po_type=='reg'?preg_replace("/[^0-9]/", "", $dtl['harga']):null,
			// 			'total_harga'      => $po_type=='reg'?preg_replace("/[^0-9]/", "", $dtl['total_harga']):null,
			// 		  ];

			if ($po_type_min=='reg') {
				$dt_details[] = [ 'id_po'=>$po_number,
						'id_item'       => $id_tipe_kendaraan.'-'.$id_warna,
						'current_stock' => $dtl['current_stock'],
						'monthly_sale'  => $dtl['monthly_sale'],
						'po_t1_last'    => $dtl['po_t1_last'],
						'po_t2_last'    => $dtl['po_t2_last'],
						'qty_po_fix'    => $dtl['po_fix'],
						'qty_po_t1'     => $dtl['qty_po_t1'],
						'qty_po_t2'     => $dtl['qty_po_t2'],
						'qty_order'     => $dtl['po_fix'],
						'max_po_t1'     => $dtl['max_po_t1'],
						'min_po_t1'     => $dtl['min_po_t1'],
						'max_po_fix'    => $dtl['max_po_fix'],
						'min_po_fix'    => $dtl['min_po_fix'],
						'qty_indent'    => $qty_indent,
						'harga'         => preg_replace("/[^0-9]/", "", $dtl['harga']),
						'total_harga'   => preg_replace("/[^0-9]/", "", $dtl['total_harga']),
					  ];
				$indent = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna='$id_warna' AND id_dealer='$id_dealer' AND status='requested' AND status_monitoring IS NULL");
				foreach ($indent->result() as $ind) {
					$upd_ind[] =['id_indent' => $ind->id_indent,
								'po_number'         => $po_number,
								'status_monitoring' => 'po_created',
							];
				}
			}else{
				$dt_details[] = [ 'id_po'=>$po_number,
						'id_item'   => $id_tipe_kendaraan.'-'.$id_warna,
						'qty_order' => $dtl['po_fix'],
					  ];
			}
		}

		$this->db->trans_begin();
			$this->db->update('tr_po_dealer_indent',['po_number'=>null,'status_monitoring'=>null],['po_number'=>$po_number]);
			$this->db->update('tr_po_dealer',$data,['id_po'=>$po_number]);
			$this->db->delete('tr_po_dealer_detail',['id_po'=>$po_number]);
			$this->db->insert_batch('tr_po_dealer_detail',$dt_details);
			if (isset($upd_ind)) {
				$this->db->update_batch('tr_po_dealer_indent',$upd_ind,'id_indent');
			}
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$response['status'] ='error';
			$response['msg']    ='Something went wrong';
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			
			$response['status'] ='sukses';
      	}		
      	echo json_encode($response);					
	}	

	public function fetch_item()
   {
		$fetch_data = $this->make_datatables_item();  
		$data = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array   = array();
			$sub_array[] = $rs->id_item;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$row         = json_encode($rs);
			$link        ='<button data-dismiss=\'modal\' onClick=\'return pilihItem('.$row.')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_item(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   

   function make_query_item()  
   {  
     $this->db->select('ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna');  
     $this->db->from('ms_item');
     $this->db->join('ms_tipe_kendaraan', 'ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan');
     $this->db->join('ms_warna', 'ms_item.id_warna = ms_warna.id_warna');
     $searchs ='ms_tipe_kendaraan.active=1';
	 $this->db->where("$searchs", NULL, false);
     $search = $this->input->post('search')['value'];
	  if ($search!='') {
	      $searchs = "(id_item LIKE '%$search%' 
	          OR tipe_ahm LIKE '%$search%'
	          OR warna LIKE '%$search%'
	      )";
	      $this->db->where("$searchs", NULL, false);
	  }
     if(isset($_POST["order"]))  
     {  
          $this->db->order_by($this->order_column_item[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
     }  
     else  
     {  
          $this->db->order_by('id_item', 'ASC');  
     }  
   }  
   function make_datatables_item(){  
		$this->make_query_item();  
		if($_POST["length"] != -1)  
		{  
			$this->db->limit($_POST['length'], $_POST['start']);  
		}  
		$query = $this->db->get();  
		return $query->result();  
   }  
   function get_filtered_data_item(){  
		$this->make_query_item();  
		$query = $this->db->get();  
		return $query->num_rows();  
   }
}