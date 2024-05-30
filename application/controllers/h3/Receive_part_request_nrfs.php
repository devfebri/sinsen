<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receive_part_request_nrfs extends CI_Controller {

	var $tables = "tr_penerimaan_ksu_dealer";	
	var $folder = "h3";
	var $page   = "receive_part_request_nrfs";
	var $pk     = "id_penerimaan_ksu_dealer";
	var $title  = "Receive Part Request NRFS";
   	var $order_column_data = array('request_id','tr_part_request_nrfs.dokumen_nrfs_id','no_shiping_list','no_mesin','no_rangka','type_code','sumber_rfs_nrfs','status_request',null); 
   	var $order_column_order = array('request_id','dokumen_nrfs_id','no_mesin','type_code',null); 

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
	public function fetch_data()
   {
		$fetch_data = $this->make_datatables_data();  
		$data       = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array = array();
			$button  = '';
			$status  = '';
			$button1 = '<a onclick="return confirm(\'Are you sure to approve this data ?\')" href='.base_url('h3/receive_part_request_nrfs/approve?id='.$rs->request_id).' class="btn btn-primary btn-xs btn-flat mb-10"><i class="fa fa-check"></i> Approve MD</a></br>';
			$button2 = '<a onclick="return confirm(\'Are you sure to reject this data ?\')" href='.base_url('h3/receive_part_request_nrfs/rejected?id='.$rs->request_id).' class="btn btn-danger btn-xs btn-flat mb-10"><i class="fa fa-close"></i> Reject MD</a></br>';
			if ($rs->status_request=='submitted') {
				$button = $button1.$button2;
				$status = '<label class="label label-info">'.ucwords($rs->status_request).'</label>';
			}
			if ($rs->status_request=='closed') {
				$status = '<label class="label label-primary">'.ucwords($rs->status_request).'</label>';
			}
			if ($rs->status_request=='rejected') {
				$status = '<label class="label label-danger">'.ucwords($rs->status_request).'</label>';
			}

			$sub_array[] = '<a href='.base_url('h3/receive_part_request_nrfs/detail?id='.$rs->request_id).'>'.$rs->request_id.'</a>';
			$sub_array[] = $rs->dokumen_nrfs_id;
			$sub_array[] = $rs->no_shiping_list;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->type_code;
			$sub_array[] = $rs->sumber_rfs_nrfs;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[] = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   function make_query_data()  
   {  
	 $id_dealer     = $this->m_admin->cari_dealer();
     $this->db->select('*');  
     $this->db->from('tr_part_request_nrfs');
     $this->db->join('tr_dokumen_nrfs', 'tr_part_request_nrfs.dokumen_nrfs_id = tr_dokumen_nrfs.dokumen_nrfs_id');
     $search = $this->input->post('search')['value'];
	  if ($search!='') {
	      $searchs = "(tr_part_request_nrfs.dokumen_nrfs_id LIKE '%$search%' 
	          OR request_id LIKE '%$search%'
	          OR no_shiping_list LIKE '%$search%'
	          OR no_mesin LIKE '%$search%'
	          OR no_rangka LIKE '%$search%'
	          OR type_code LIKE '%$search%'
	          OR sumber_rfs_nrfs LIKE '%$search%'
	          OR status_request LIKE '%$search%'
	      )";
	      $this->db->where("$searchs", NULL, false);
	  }
     // $this->db->where('tr_part_request_nrfs.status_request', 'submitted');
	   $this->db->where("(tr_part_request_nrfs.status_request='submitted' OR tr_part_request_nrfs.status_request='closed' OR tr_part_request_nrfs.status_request='rejected')", NULL, false);
     if(isset($_POST["order"]))  
     {  
          $this->db->order_by($this->order_column_data[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
     }  
     else  
     {  
          $this->db->order_by('tr_part_request_nrfs.created_at', 'DESC');  
     }  
   }  
   function make_datatables_data(){  
		$this->make_query_data();  
		if($_POST["length"] != -1)  
		{  
			$this->db->limit($_POST['length'], $_POST['start']);  
		}  
		$query = $this->db->get();  
		return $query->result();  
   }  
   function get_filtered_data(){  
		$this->make_query_data();  
		$query = $this->db->get();  
		return $query->num_rows();  
   }  


	public function add()
	{				

		$data['isi']       = $this->page;		
		$data['title']     = $this->title;
		$data['set']       = "form";
		$data['mode']      = "insert";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$data['set_md']    = $this->db->get('ms_setting_h1')->row();
		$id_dealer         = $this->m_admin->cari_dealer();
		$this->template($data);	
	}

	public function detail()
	{				
		$request_id = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;
		$data['set']       = "form";
		$data['mode']      = "detail";
		$id_dealer         = $this->m_admin->cari_dealer();
		$cek_data 		   = $this->db->query("SELECT tr_dokumen_nrfs.*,tr_part_request_nrfs.request_id,tr_part_request_nrfs.status_request FROM tr_part_request_nrfs
				JOIN tr_dokumen_nrfs ON tr_part_request_nrfs.dokumen_nrfs_id=tr_dokumen_nrfs.dokumen_nrfs_id
				WHERE request_id='$request_id'
			");
		if ($cek_data->num_rows()>0) {
			$row = $data['row']['dokumen']       = $cek_data->row();
			$data['row']['request_id']       = $cek_data->row()->request_id;
			$data['details']   = $this->db->query("SELECT * FROM tr_dokumen_nrfs_part WHERE dokumen_nrfs_id='$row->dokumen_nrfs_id' 
			")->result();
			$this->template($data);	
		}else{
			redirect(base_url('h3/receive_part_request_nrfs'));
		}
	}

	public function edit()
	{				
		$request_id = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;
		$data['set']       = "form";
		$data['mode']      = "edit";
		$id_dealer         = $this->m_admin->cari_dealer();
		$cek_data 		   = $this->db->query("SELECT tr_dokumen_nrfs.*,tr_part_request_nrfs.request_id,tr_part_request_nrfs.status_request FROM tr_part_request_nrfs
				JOIN tr_dokumen_nrfs ON tr_part_request_nrfs.dokumen_nrfs_id=tr_dokumen_nrfs.dokumen_nrfs_id
				WHERE request_id='$request_id' AND tr_part_request_nrfs.id_dealer=$id_dealer
			");
		if ($cek_data->num_rows()>0) {
			$row = $data['row']['dokumen']       = $cek_data->row();
			$data['row']['request_id']       = $cek_data->row()->request_id;
			$data['details']   = $this->db->query("SELECT * FROM tr_dokumen_nrfs_part WHERE dokumen_nrfs_id='$row->dokumen_nrfs_id' 
			")->result();
			$this->template($data);	
		}else{
			redirect(base_url('h3/receive_part_request_nrfs'));
		}
	}

	public function approve()
	{				
		$waktu      = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id   = $this->session->userdata('id_user');
		$request_id = $this->input->get('id');
		$cek_data  = $this->db->get_where('tr_part_request_nrfs',['request_id'=>$request_id,'status_request'=>'submitted']);
		if ($cek_data->num_rows()>0) {
			$row = $cek_data->row();
			$data['status_request'] = 'closed';
			$this->db->update('tr_part_request_nrfs',$data,['request_id'=>$request_id]);
			$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>4])->row();
			$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
					'id_referensi' => $request_id,
					'judul'        => "Update Status Part Request Approve",
					'pesan'        => "Terdapat update status untuk PO $request_id bahwa PO Approved oleh Main Dealer, buka pesan ini untuk melihat detail status.",
					'link'         => $ktg_notif->link.'/detail?id='.$request_id,
					'status'       =>'baru',
					'id_dealer'    => $row->id_dealer,
					'created_at'   => $waktu,
					'created_by'   => $login_id
    			 ];
	        $this->db->insert('tr_notifikasi',$notif);

			$_SESSION['pesan'] 	= "Data has been sent successfully";
			$_SESSION['tipe'] 	= "success";
			redirect(base_url('h3/receive_part_request_nrfs'));
		}else{
			redirect(base_url('h3/receive_part_request_nrfs'));
		}
	}

	public function rejected()
	{				
		$waktu      = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id   = $this->session->userdata('id_user');
		$request_id = $this->input->get('id');
		$cek_data  = $this->db->get_where('tr_part_request_nrfs',['request_id'=>$request_id,'status_request'=>'submitted']);
		if ($cek_data->num_rows()>0) {
			$row = $cek_data->row();
			$data['status_request'] = 'rejected';
			$this->db->update('tr_part_request_nrfs',$data,['request_id'=>$request_id]);
			$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>4])->row();
			$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
					'id_referensi' => $request_id,
					'judul'        => "Update Status Part Request Rejected",
					'pesan'        => "Terdapat update status untuk PO $request_id bahwa PO Rejected oleh Main Dealer, buka pesan ini untuk melihat detail status.",
					'link'         => $ktg_notif->link.'/detail?id='.$request_id,
					'status'       =>'baru',
					'id_dealer'    => $row->id_dealer,
					'created_at'   => $waktu,
					'created_by'   => $login_id
    			 ];
	        $this->db->insert('tr_notifikasi',$notif);

			$_SESSION['pesan'] 	= "Data has been rejected successfully";
			$_SESSION['tipe'] 	= "success";
			redirect(base_url('h3/receive_part_request_nrfs'));
		}else{
			redirect(base_url('h3/receive_part_request_nrfs'));
		}
	}

	public function getDetail()
	{	
		$dokumen_nrfs_id = $this->input->post('dokumen_nrfs_id');
		$response = $this->db->get_where('tr_dokumen_nrfs_part',['dokumen_nrfs_id'=>$dokumen_nrfs_id])->result();
		echo json_encode($response);
	}

	public function get_request_id()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		$get_data  = $this->db->query("SELECT * FROM tr_part_request_nrfs
			WHERE id_dealer='$id_dealer'
			AND LEFT(tgl_request,7)='$th_bln'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$request_id = substr($row->request_id, -4);
				$new_kode   = 'PARTSREQ/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$request_id+1);
	   		}else{
				$new_kode   = 'PARTSREQ/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}

	public function save()
	{		
		$waktu           = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id        = $this->session->userdata('id_user');
		$id_dealer       = $this->m_admin->cari_dealer();
		$dokumen_nrfs_id = $this->input->post('dokumen_nrfs_id');
		$request_id      = $this->get_request_id();

		$data['request_id']      = $request_id;
		$data['dokumen_nrfs_id'] = $dokumen_nrfs_id;
		$data['tgl_request']     = date('Y-m-d');
		$data['id_dealer']       = $id_dealer;
		$data['created_at']      = $waktu;
		$data['created_by']      = $login_id;
		$data['status_request']  = 'draft';

		$this->db->trans_begin();
			$this->db->insert('tr_part_request_nrfs',$data);
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
		$waktu      = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id   = $this->session->userdata('id_user');
		$id_dealer  = $this->m_admin->cari_dealer();
		$request_id = $this->input->post('request_id');
		
		$data['dokumen_nrfs_id'] = $this->input->post('dokumen_nrfs_id');
		$data['id_dealer']       = $id_dealer;
		$data['updated_at']      = $waktu;
		$data['updated_by']      = $login_id;
		
		$this->db->trans_begin();
			$this->db->update('tr_part_request_nrfs',$data,['request_id'=>$request_id]);
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

	public function fetch_dokumen()
   {
		$fetch_data = $this->make_datatables_dokumen();  
		$data = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array   = array();
			$sub_array[] = $rs->dokumen_nrfs_id;
			$sub_array[] = $rs->deskripsi_unit;
			$sub_array[] = $rs->deskripsi_warna;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;

			$row['dokumen']    = $rs;
			$row['request_id'] = $this->get_request_id();
			$link              ='<button data-dismiss=\'modal\' onClick=\'return pilihDokumen('.json_encode($row).')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[]       = $link;
			$data[]            = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_dokumen(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   }

   

   function make_query_dokumen()  
   {  
     $this->db->select('*');  
     $this->db->from('tr_dokumen_nrfs');
     // $this->db->join('ms_tipe_kendaraan', 'tr_dokumen_nrfs.type_code = ms_tipe_kendaraan.id_tipe_kendaraan');
     // $this->db->join('ms_warna', 'tr_dokumen_nrfs.color_code = ms_warna.id_warna');

     $search = $this->input->post('search')['value'];
	  if ($search!='') {
	      $searchs = "(dokumen_nrfs_id LIKE '%$search%' 
	          OR type_code LIKE '%$search%'
	          OR deskripsi_unit LIKE '%$search%'
	          OR color_code LIKE '%$search%'
	          OR deskripsi_warna LIKE '%$search%'
	          OR no_rangka LIKE '%$search%'
	          OR no_mesin LIKE '%$search%'
	      )";
	      $this->db->where("$searchs", NULL, false);
	  }
	      $this->db->where("sumber_rfs_nrfs='MD'");
	      $this->db->where("dokumen_nrfs_id NOT IN(SELECT dokumen_nrfs_id FROM tr_part_request_nrfs WHERE status_request='submitted')", NULL, false);
     if(isset($_POST["order"]))  
     {  
          $this->db->order_by($this->order_column_dokumen[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
     }  
     else  
     {  
          $this->db->order_by('dokumen_nrfs_id', 'DESC');  
     }  
   }  
   function make_datatables_dokumen(){  
		$this->make_query_dokumen();  
		if($_POST["length"] != -1)  
		{  
			$this->db->limit($_POST['length'], $_POST['start']);  
		}  
		$query = $this->db->get();  
		return $query->result();  
   }  
   function get_filtered_data_dokumen(){  
		$this->make_query_dokumen();  
		$query = $this->db->get();  
		return $query->num_rows();  
   }

   public function fetch_tracking()
   {
		$fetch_data = $this->make_datatables_order();  
		$data       = array();  
		foreach($fetch_data as $rs)  
		{  
			$sub_array = array();
			$sub_array[] = '<a href='.base_url('h3/receive_part_request_nrfs/detail?id='.$rs->request_id).'>'.$rs->request_id.'</a>';
			$sub_array[] = $rs->dokumen_nrfs_id;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->type_code;
			$draft='';$submitted='';$processed='';$closed='';$returned_po='';$rejected='';$cancel='';
			if ($rs->status_request=='draft') {
				$draft='active';
			}
			if ($rs->status_request=='submitted') {
				$draft='active';$submitted='active';
			}
			if ($rs->status_request=='processed') {
				$draft='active';$submitted='active';$processed='active';
			}
			if ($rs->status_request=='closed') {
				$draft='active';$submitted='active';$processed='active';$closed='active';
			}
			if ($rs->status_request=='returned_po') {
				$returned_po='warning';
			}
			if ($rs->status_request=='rejected') {
				$rejected='danger';
			}
			if ($rs->status_request=='Cancelled by Dealer') {
				$cancel='warning';
			}
			$button		 = '<div class="col-md-10" style="padding:0;width:55%">
					        <ul class="progress-indicator" style="margin-bottom: 0px;">
					        <li class="'.$draft.'"><span class="bubble"></span>
					            Draft
					        </li>
					        <li class="'.$submitted.'"><span class="bubble"></span>Submitted</li>
					        <li class="'.$closed.'"><span class="bubble"></span>Closed</li>
					      </ul>
					      </div>
					      <div class="col-md-1" style="padding:0;width:15%">
					        <ul class="progress-indicator_one" style="margin-bottom: 0px;">
					        <li style="padding-right:15px;" class="'.$rejected.'"><span class="bubble"></span>Rejected</li>
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
     $this->db->from('tr_part_request_nrfs');
    	$this->db->join('tr_dokumen_nrfs', 'tr_part_request_nrfs.dokumen_nrfs_id = tr_dokumen_nrfs.dokumen_nrfs_id');
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
     $this->db->where("tr_part_request_nrfs.id_dealer='$id_dealer' ");

     $search = $this->input->post('search')['value'];
	  if ($search!='') {
	      $searchs = "(po_number LIKE '%$search%' 
	          OR unit_qty LIKE '%$search%'
	          OR po_type LIKE '%$search%'
	          OR submission_deadline LIKE '%$search%'
	          OR remarks LIKE '%$search%'
	      )";
	      $this->db->where("$searchs", NULL, false);
	  }
     if(isset($_POST["order"]))  
     {  
          $this->db->order_by($this->order_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
     }  
     else  
     {  
          $this->db->order_by('tr_part_request_nrfs.created_at', 'DESC');  
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
}