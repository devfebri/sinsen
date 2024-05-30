<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	var $folder =   "dealer";
	var $page	=		"product";
    var $title  =   "Product Explanation";
   	var $order_column = array('tipe_ahm','warna','ms_kelompok_md.id_item',null,null,null,null,null); 

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
		$data['set']   = "index";		
		$id_dealer     = $this->m_admin->cari_dealer();
		$this->template($data);	
	}

	public function view()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;
		$id_item = $this->input->get('id');															
		$data['set']   = "view";		
		// $row = $data['item'] = $this->db->query("SELECT * FROM ms_item WHERE id_item='$id_item'")->row();
		// $data['tipe'] = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$row->id_tipe_kendaraan])->row();
		// $data['wrn'] = $this->db->get_where('ms_warna',['id_warna'=>$row->id_warna])->row();
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		$date      = date('Y-m-d');
	    $row = $data['item'] = $this->db->query("SELECT ms_item.*,warna,tipe_ahm,
	    	(SELECT harga_jual FROM ms_kelompok_md WHERE id_item=ms_item.id_item AND start_date<='$date' AND id_kelompok_harga='A' ORDER BY start_date DESC LIMIT 1 ) AS harga_jual,
	    	(SELECT count(id_prospek) FROM tr_prospek WHERE id_dealer='$id_dealer' AND id_tipe_kendaraan=ms_item.id_tipe_kendaraan AND id_warna=ms_item.id_warna AND (status_prospek='hot' OR status_prospek='Hot Prospek') )AS hot
   			FROM ms_item
   			JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=ms_item.id_warna
			-- JOIN ms_kelompok_md ON ms_kelompok_md.id_item=(SELECT id_item FROM ms_kelompok_md WHERE id_item=ms_item.id_item AND start_date<='$date' ORDER BY created_at DESC LIMIT 1 )
			WHERE ms_item.id_item='$id_item'
			")->row();

		 $data['ksu'] = $this->db->query("SELECT *,(SELECT ksu FROM ms_ksu WHERE id_ksu=ms_koneksi_ksu_detail.id_ksu) AS ksu FROM ms_koneksi_ksu_detail JOIN ms_koneksi_ksu ON ms_koneksi_ksu_detail.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'")->result();
		 $data['sales_program'] = $this->db->query("SELECT * FROM tr_sales_program WHERE '$row->id_tipe_kendaraan' IN(SELECT id_tipe_kendaraan FROM tr_sales_program_tipe WHERE id_program_md=tr_sales_program.id_program_md) ")->result();
		 $data['stok'] = $this->stok($row->id_item);
        $lead = $this->db->get_where("ms_master_lead_detail",['id_dealer'=>$id_dealer]);
        $data['lead'] = $lead->num_rows()>0?$lead->row()->total_lead_time:'-';
		$this->template($data);	
	}


	function stok($id_item)
	{
			$id_dealer = $this->m_admin->cari_dealer();            

		return $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.id_item = '$id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
                AND tr_scan_barcode.status = '4'")->row()->jum; 
	}
	public function fetch()
   {
		$fetch_data = $this->make_query()->result();  
		$data       = array();  
		$id_dealer = $this->m_admin->cari_dealer();            
        $lead = $this->db->get_where("ms_master_lead_detail",['id_dealer'=>$id_dealer]);
        $leads = $lead->num_rows()>0?$lead->row()->total_lead_time:0;
		foreach($fetch_data as $rs)  
		{  
			$button = '';
			$btn_view      = '<a href='.base_url('dealer/product/view?id='.$rs->id_item).' class="btn btn-primary btn-xs btn-flat mb-10">View</a></br>';
			// $btn_video      = '<button type="button" onclick="showVideo(\''.$rs->id_item.'\')" class="btn btn-warning btn-xs btn-flat mb-10">Video</button></br>';
			
            $stok = $this->stok($rs->id_item);
			$button = $btn_view;
			$sub_array = array();
			$sub_array[] = $rs->id_item;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$sub_array[] = mata_uang_rp($rs->harga_jual);
			// $sub_array[] = '';
			$sub_array[] = $stok;
			$sub_array[] = $stok==0?$leads:'-';
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

   function make_query($no_limit=null)  
   	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('kode_item','tipe_ahm','warna','harga_jual',null,null,null,null,null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY ms_item.id_item DESC';
		$search       = $this->input->post('search')['value'];
		$date = date('Y-m-d');
		$searchs      = '';

		if ($search!='') {
	      $searchs = "WHERE ms_item.id_tipe_kendaraan LIKE '%$search%' 
	          OR tipe_ahm LIKE '%$search%'
	          OR ms_item.id_item LIKE '%$search%'
	          OR ms_item.id_warna LIKE '%$search%'
	          OR warna LIKE '%$search%'
	          OR (SELECT harga_jual FROM ms_kelompok_md WHERE id_item=ms_item.id_item AND start_date<='$date' ORDER BY created_at DESC LIMIT 1) LIKE '%$search%'
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

     	$id_dealer     = $this->m_admin->cari_dealer();
      	$dealer = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
   		return $this->db->query("SELECT ms_item.id_item,ms_item.id_warna,warna,tipe_ahm,(SELECT harga_jual FROM ms_kelompok_md WHERE id_item=ms_item.id_item AND start_date<='$date' AND id_kelompok_harga='A' ORDER BY start_date DESC LIMIT 1 ) AS harga_jual
   			FROM ms_item
   			JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=ms_item.id_warna
   			$searchs $order $limit ");
   	}

   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	}   

  //  function make_query()  
  //  {  
	 // $id_dealer     = $this->m_admin->cari_dealer();
  //  	 $dealer = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
  //    $this->db->select('ms_kelompok_md.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna');  
  //    $this->db->from('ms_kelompok_md');
  //    $this->db->join('ms_item', 'ms_kelompok_md.id_item = ms_item.id_item','left');
  //    $this->db->join('ms_tipe_kendaraan', 'ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan','left');
  //    $this->db->join('ms_warna', 'ms_item.id_warna = ms_warna.id_warna','left');
  //    $this->db->where("ms_kelompok_md.id_kelompok_harga='A' ");

  //    $search = $this->input->post('search')['value'];
	 //  if ($search!='') {
	 //      $searchs = "(ms_kelompok_md.id_item LIKE '%$search%' 
	 //          OR tipe_ahm LIKE '%$search%'
	 //          OR warna LIKE '%$search%'
	 //          OR start_date LIKE '%$search%'
	 //          OR harga_jual LIKE '%$search%'
	 //      )";
	 //      $this->db->where("$searchs", NULL, false);
	 //  }
  //    if(isset($_POST["order"]))  
  //    {  
  //         $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
  //    }  
  //    else  
  //    {  
  //         $this->db->order_by('start_date', 'DESC');  
  //    }  
  //  }  
  //  function make_datatables(){  
		// $this->make_query();  
		// if($_POST["length"] != -1)  
		// {  
		// 	$this->db->limit($_POST['length'], $_POST['start']);  
		// }  
		// $query = $this->db->get();  
		// return $query->result();  
  //  }  
  //  function get_filtered_data(){  
		// $this->make_query();  
		// $query = $this->db->get();  
		// return $query->num_rows();  
  //  }  

   	public function cek_nosin()
   	{
   		$data = $this->db->query("SELECT id_sales_order,booking_at,hard_booking_at,jenis_pu,tr_sales_order.created_at,tr_penerimaan_unit_dealer_detail.no_mesin,(SELECT created_at FROM tr_dokumen_nrfs WHERE no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin ORDER BY dokumen_nrfs_id DESC LIMIT 1) AS created_nrfs
FROM tr_penerimaan_unit_dealer_detail 
JOIN tr_sales_order ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_sales_order.no_mesin
where jenis_pu='nrfs'  
ORDER BY `tr_sales_order`.`created_at` DESC");
   		echo '<table border=1>
   			<tr>
   				<td>No</td>
   				<td>Sales Order</td>
   				<td>No. Mesin</td>
   				<td>Hard Booking At</td>
   				<td>Created NRFS At</td>
   				<td>Keterangan</td>
   			</tr>
   		';
   		$no=1;
   		foreach ($data->result() as $rs) {
   			$ket = 'Aman';
   			if (strtotime($rs->hard_booking_at) < strtotime($rs->created_nrfs)) {
   				$ket = 'Masalah';
   			}
   			echo '<tr>';
   			echo '<td>'.$no.'</td>';
   			echo '<td>'.$rs->id_sales_order.'</td>';
   			echo '<td>'.$rs->no_mesin.'</td>';
   			echo '<td>'.$rs->hard_booking_at.'</td>';
   			echo '<td>'.$rs->created_nrfs.'</td>';
   			echo '<td>'.$ket.'</td>';
   			echo '</tr>';
   			$no++;
   		}
   		echo '</table>';
   	}
}