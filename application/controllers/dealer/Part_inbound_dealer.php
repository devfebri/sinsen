<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class part_inbound_dealer extends CI_Controller {

	var $tables = "tr_entry_po_leasing";	
	var $folder = "dealer";
	var $page   = "part_inbound_dealer";
	var $title  = "Part Inbound Dealer";

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
		// $this->load->library('upload');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

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
		$data['dt']		= $this->get_data();
		$this->template($data);	
	}

	// public function fetch()
 //   	{
	// 	$fetch_data = $this->make_query();  
	// 	$data = array();  
	// 	foreach($fetch_data->result() as $rs)  
	// 	{  
	// 		$sub_array     = array();
	// 		$button = '';
	// 		// $btn_del = "<a data-toggle='tooltip' onclick=\"return confirm('Are you sure to delete this data ?')\" title='Delete' href='dealer/pesan_d/delete?id=$rs->id_generate'><button class='btn btn-flat btn-sm btn-danger'><i class='fa fa-trash'></i></button></a>";
	// 		// $btn_assign = "<a data-toggle='tooltip' href='dealer/generate_list_unit_delivery/assign_supir?id=$rs->id_generate'><button class='btn btn-flat btn-xs btn-primary'>Assign Supir & Checklist Kebutuhan Pengiriman</button></a>";
	// 		// $btn_print = "<a data-toggle='tooltip' href='dealer/generate_list_unit_delivery/print_list?id=$rs->id_generate'><button class='btn btn-flat btn-xs btn-success'><i class='fa fa-print'></i>  Print</button></a>";
	// 		// $button = $btn_print.' '.$btn_assign;
	// 		// $sub_array[] = $btn_del = "<a href='dealer/generate_list_unit_delivery/detail?id=$rs->id_generate'>$rs->id_generate</a>";
	// 		// $sub_array[] = $btn_del = "<a href='dealer/generate_list_unit_delivery/detail?id=$rs->id_generate'>$rs->tgl_pengiriman</a>";
	// 		$sub_array[] = $rs->no_do_sparepart;
	// 		$sub_array[] = $rs->tgl_do;
	// 		$sub_array[] = 0;
	// 		$sub_array[] = '$button';
	// 		$data[]      = $sub_array;  
	// 	}  
	// 	$output = array(  
 //          "draw"            =>     intval($_POST["draw"]),  
 //          "recordsFiltered" =>     $this->get_filtered_data(),  
 //          "data"            =>     $data  
	// 	);  
	// 	echo json_encode($output);  
 //   	}

 //   	function make_query($no_limit=null)  
 //   	{  
	// 	$start        = $this->input->post('start');
	// 	$length       = $this->input->post('length');
	// 	$order_column = array('no_do_sparepart','tgl_do',null,null); 
	// 	$limit        = "LIMIT $start,$length";
	// 	$order        = 'ORDER BY tr_so_spare.created_at DESC';
	// 	$search       = $this->input->post('search')['value'];
	// 	$id_dealer    = $this->m_admin->cari_dealer();
	// 	$searchs      = "WHERE tr_so_spare.id_dealer=$id_dealer";
		
	// 	if ($search!='') {
	//       $searchs .= "AND (tgl_pengiriman LIKE '%$search%' 
	//           OR created_at LIKE '%$search%'
	//           )
	//       ";
	//   	}
     	
 //     	if(isset($_POST["order"]))  
	// 	{	
	// 		$order_clm = $order_column[$_POST['order']['0']['column']];
	// 		$order_by  = $_POST['order']['0']['dir'];
	// 		$order     = "ORDER BY $order_clm $order_by";
 //     	}
     	
 //     	if ($no_limit=='y')$limit='';

 //   		return $this->db->query("SELECT tr_create_do_spare.* FROM tr_create_do_spare 
 //   				INNER JOIN tr_so_spare ON tr_create_do_spare.no_so_spare = tr_so_spare.no_so_spare		 	
	// 			INNER JOIN ms_dealer ON tr_so_spare.id_dealer = ms_dealer.id_dealer
 //   		 $searchs $order $limit ");
 //   	}  
 //   	function get_filtered_data(){  
	// 	return $this->make_query('y')->num_rows();  
 //   	}  
	public function get_data($id=null)
	{
		$filter = '';
		if ($id!=null) {
			$filter = "AND tr_create_do_spare.no_do_spare_part='$id'";
		}
		$id_dealer    = $this->m_admin->cari_dealer();
		return $dt = $this->db->query("SELECT tr_create_do_spare.*,0 AS qty_actual,id_good_receipt_part,tgl_penerimaan 
			FROM tr_create_do_spare 
          INNER JOIN tr_so_spare ON tr_create_do_spare.no_so_spare = tr_so_spare.no_so_spare      
        INNER JOIN ms_dealer ON tr_so_spare.id_dealer = ms_dealer.id_dealer 
        LEFT JOIN tr_penerimaan_part_dealer ON tr_penerimaan_part_dealer.no_do_spare_part=tr_create_do_spare.no_do_spare_part
        WHERE tr_so_spare.id_dealer='$id_dealer' $filter");
	}
	public function received()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'received';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id = $this->input->get('id');
		$data['row']   = $this->get_data($id)->row();
		$data['dt_parts'] = $this->db->query("SELECT tr_create_do_spare_detail.id_part,qty_supply,nama_part FROM tr_create_do_spare_detail LEFT JOIn ms_part ON tr_create_do_spare_detail.id_part = ms_part.id_part 			
			WHERE tr_create_do_spare_detail.no_do_spare_part = '$id'")->result();
		$this->template($data);	
	}

	function get_id_good_receipt_part()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$ymd 	  = date('Y-m-d');
		$ymd2 	  = date('ymd');
		$id_dealer = $this->m_admin->cari_dealer();

		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		$get_data  = $this->db->query("SELECT * FROM tr_penerimaan_part_dealer
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$id_good_receipt_part  = substr($row->id_good_receipt_part, -5);
				$new_kode = 'GRP/'.$dealer->kode_dealer_md.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.05d",$id_good_receipt_part+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('tr_penerimaan_part_dealer',['id_good_receipt_part'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -5);
						$new_kode = 'GRP/'.$dealer->kode_dealer_md.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.05d",$neww+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode   = 'GRP/'.$dealer->kode_dealer_md.'/'.$th_kecil.'/'.$bln.'/00001';
	   		}
   		return strtoupper($new_kode);
	}
	
	public function save_received()
	{		
		$waktu    = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("Y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		
		$id_good_receipt_part    = $this->get_id_good_receipt_part();
		$id_dealer = $this->m_admin->cari_dealer();

		$get_detail = $this->input->post('dt_parts');
		foreach ($get_detail as $rs) {
			$ins_detail[] = ['id_part'=>$rs['id_part'],
							 'id_good_receipt_part'=>$id_good_receipt_part,
							 'qty_supply'=>$rs['qty_supply'],
							 'qty_actual'=>$rs['qty_actual'],
							];
		}

		$data 	= ['id_good_receipt_part'=>$id_good_receipt_part,
				'id_dealer'        => $id_dealer,
				'no_do_spare_part' => $this->input->post('no_do_spare_part'),
				'tgl_penerimaan' => $this->input->post('tgl_penerimaan'),
				'status'           => 'received',
				'created_at'       => $waktu,
				'created_by'       => $login_id
			 ];

		// echo json_encode($ins_detail);
		// echo json_encode($upd_claim);
		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
			$this->db->insert('tr_penerimaan_part_dealer',$data);
			if (isset($ins_detail)) {
				$this->db->insert_batch('tr_penerimaan_part_dealer_detail',$ins_detail);
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
					'link'=>base_url('dealer/part_inbound_dealer')
				   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id = $this->input->get('id');
		$data['row']   = $this->get_data($id)->row();
		$data['dt_parts'] = $this->db->query("SELECT tr_create_do_spare_detail.id_part,qty_supply,nama_part,(SELECT qty_actual FROM tr_penerimaan_part_dealer_detail
								   JOIN tr_penerimaan_part_dealer ON tr_penerimaan_part_dealer.id_good_receipt_part=tr_penerimaan_part_dealer_detail.id_good_receipt_part
								   WHERE tr_penerimaan_part_dealer.no_do_spare_part=tr_create_do_spare_detail.no_do_spare_part
								   AND tr_penerimaan_part_dealer_detail.id_part=tr_create_do_spare_detail.id_part
			) AS qty_actual 
			FROM tr_create_do_spare_detail 
			LEFT JOIn ms_part ON tr_create_do_spare_detail.id_part = ms_part.id_part
			WHERE tr_create_do_spare_detail.no_do_spare_part = '$id'")->result();
		$this->template($data);	
	}

}