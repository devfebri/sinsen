<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_file_ahm extends CI_Controller {

	var $folder = "h3";
	var $page	= "generate_file_ahm";
	var $title  = "File AHM";
	// var $tables =   "tr_cdb_generate";	
	// var $pk     =   "id_cdb_generate";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		// $this->load->library('upload');		
		// $this->load->library('zip');		
		// $this->load->library('csvimport');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth("H3_md_file_ahm","select");		
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
		$data['set']		= "generate";		
		$this->template($data);		
	}

	public function download(){	
		$start_date           = $this->input->post('start_date');
		// $end_date             = $this->input->post('end_date');		
		$ext_file             = $this->input->post('ext_file');		

		echo "<iframe style='display:none;' src='".base_url()."h3/H3_md_file_ahm/unduh?start_date=".$start_date."&ext_file=".$ext_file."'></iframe>";
	}

	public function tes_sto(){
		$date = date('Y-m-d');
		$created_date = date('Y-m-d H:i:s');

		$cek_date_stok = $this->db->query("
			select * from tr_h3_md_sto where tgl_sto ='$date' 
		");

		if($cek_date_stok->num_rows()>0){
			echo 'Tgl STO sdh pernah dibuat';
		}else{
			$data = $this->db->query("						
				select '$date' as tgl_sto, b.id_part_int , b.id_part, b.harga_md_dealer , b.harga_dealer_user , sum(a.qty) as qty, '$created_date' as created_at
				from tr_stok_part a
				join ms_part b on a.id_part_int  = b.id_part_int 
				group by b.id_part, b.id_part_int , b.harga_md_dealer , b.harga_dealer_user 
				order by b.id_part ASC 
				limit 10
			");

			if($data->num_rows() > 0 ){
				$data_stok = $data->result_array();
				// $this->db->insert_batch('tr_h3_md_sto', $data_stok); 
			}else{
				echo 'no data.';
			}
		}
	}

	public function unduh(){	
		$start_date           = $_GET['start_date'];	
		$ext_file             = $_GET['ext_file'];	
		
		if($ext_file=='REC'){
			$nama_file = 'E20'.date('dmy',strtotime($start_date)).'.REC'; // E20050224.rec
			
			$get_data = $this->db->query("
				select 'E20' as kode_md, a.no_penerimaan_barang , a.tanggal_penerimaan , a.start_penerimaan, a.end_penerimaan, b.packing_sheet_number , 'AHM' as supplier, b.no_po , b.id_part , b.qty_diterima 
				from tr_h3_md_penerimaan_barang a 
				join tr_h3_md_penerimaan_barang_items b on a.no_penerimaan_barang = b.no_penerimaan_barang 
				where a.start_penerimaan >'$start_date 00:00:00' and a.start_penerimaan <'$start_date 23:59:59'
			")->result();

			$data['data'] = $get_data;
		}else if($ext_file=='STO'){
			$nama_file = 'E20'.date('dmY',strtotime($start_date)).'.STO'; // E2007022024.sto
			$new_date=date('Y-m-d', strtotime($start_date. ' + 1 days'));
			
			// get real stok
			
			// 	select 'E20' as kode_md , '' as tgl_transaksi, b.id_part , a.qty , b.harga_md_dealer , 0 as nilai
			// 	from tr_stok_part a
			// 	join ms_part b on a.id_part_int  = b.id_part_int 

			$get_data = $this->db->query("
				select 'E20' as kode_md , '' as tgl_transaksi, id_part , qty , harga_md_dealer , 0 as nilai
				from tr_h3_md_sto 				
				where tgl_sto = '$new_date'
			")->result();

			// $get_data = $this->db->query("								
			// 	select 'E20' as kode_md , '' as tgl_transaksi, b.id_part , a.stock_akhir as qty, b.harga_md_dealer , 0 as nilai 
			// 	from tr_h3_md_kartu_stock a
			// 	join ms_part b on a.id_part_int  = b.id_part_int 
			// 	where a.created_at <'$new_date' and a.stock_value !=0
			// 	group by id_part 
			// 	order by a.created_at DESC 
			// ")->result();

			$data['data'] = $get_data;
			$data['start_date'] = $start_date;
		}else if($ext_file=='POD'){
			$nama_file = 'E20'.date('dm',strtotime($start_date)).'.POD'; // E200702.pod
			
			$get_dealer_exist = $this->db->query("
				select 'E20' as kode_md, f.kode_dealer_ahm_link kode_dealer_ahm , f.kode_dealer_md, g.po_id , f.nama_dealer 
				from tr_h3_md_packing_sheet a
				join tr_h3_md_picking_list  b on a.id_picking_list  = b.id_picking_list 
				join tr_h3_md_do_sales_order c on c.id_do_sales_order  = b.id_ref 
				join tr_h3_md_sales_order d on d.id_sales_order  = c.id_sales_order 
				join ms_dealer f on b.id_dealer = f.id_dealer 
				join tr_h3_dealer_purchase_order g on d.id_ref = g.po_id 
				join tr_h3_dealer_purchase_order_parts h on h.po_id =g.po_id 
				join tr_h3_md_sales_order_parts i on i.id_sales_order = c.id_sales_order  and i.id_part_int = h.id_part_int 
				where a.tgl_faktur >'$start_date 00:00:00' and a.tgl_faktur < '$start_date 23:59:59' and f.kode_dealer_ahm_link is null
			");

			if($get_dealer_exist->num_rows() > 0 ){
				$data['valid_data'] = 0;
				$data['data'] = $get_dealer_exist->result();
			}else{
				$data['valid_data'] = 1;
				$get_data = $this->db->query("
					select 'E20' as kode_md, f.kode_dealer_ahm_link kode_dealer_ahm , g.po_id , g.submit_at as tanggal_order , h.id_part , h.kuantitas , left(g.po_type,1) as type, g.po_type 
					from tr_h3_md_packing_sheet a
					join tr_h3_md_picking_list  b on a.id_picking_list  = b.id_picking_list 
					join tr_h3_md_do_sales_order c on c.id_do_sales_order  = b.id_ref 
					join tr_h3_md_sales_order d on d.id_sales_order  = c.id_sales_order 
					join ms_dealer f on b.id_dealer = f.id_dealer 
					join tr_h3_dealer_purchase_order g on d.id_ref = g.po_id 
					join tr_h3_dealer_purchase_order_parts h on h.po_id =g.po_id 
					join tr_h3_md_sales_order_parts i on i.id_sales_order = c.id_sales_order  and i.id_part_int = h.id_part_int 
					where a.tgl_faktur >'$start_date 00:00:00' and a.tgl_faktur < '$start_date 23:59:59'
				")->result();
				$data['data'] = $get_data;
			}
		}else if($ext_file=='SAL'){
			$nama_file = 'E20'.date('dm',strtotime($start_date)).'.SAL'; // E200702.sal
			
			$get_dealer_exist = $this->db->query("
				select 'E20' as kode_md, a.no_faktur , a.tgl_faktur , f.kode_dealer_ahm_link kode_dealer_ahm, a.id_packing_sheet, f.kode_dealer_md, f.nama_dealer
				from tr_h3_md_packing_sheet a
				join tr_h3_md_picking_list  b on a.id_picking_list  = b.id_picking_list 
				join tr_h3_md_do_sales_order c on c.id_do_sales_order  = b.id_ref 
				join tr_h3_md_sales_order d on d.id_sales_order  = c.id_sales_order 
				join ms_dealer f on b.id_dealer = f.id_dealer 
				join tr_h3_md_sales_order_parts g on g.id_sales_order = d.id_sales_order 
				where a.tgl_faktur >'$start_date 00:00:00' and a.tgl_faktur < '$start_date 23:59:59' and f.kode_dealer_ahm_link is null
			");

			if($get_dealer_exist->num_rows() > 0){
				$data['valid_data'] = 0;
				$data['data'] = $get_dealer_exist->result();
			}else{
				$data['valid_data'] = 1;
				$get_data = $this->db->query("
					select 'E20' as kode_md, a.no_faktur , a.tgl_faktur , f.kode_dealer_ahm_link kode_dealer_ahm, a.id_packing_sheet, concat(concat(RIGHT(d.id_ref,5),'/PL/'), f.kode_dealer_ahm_link) as short_id, d.id_ref, g.id_part , g.qty_pemenuhan ,  
					(g.qty_pemenuhan * cast(g.harga as DECIMAL) ) as harga , 
					(g.qty_pemenuhan * cast(g.harga_setelah_diskon as DECIMAL)) as harga_setelah_diskon ,
					(g.qty_pemenuhan * cast(g.hpp as DECIMAL)) as hpp 
					from tr_h3_md_packing_sheet a
					join tr_h3_md_picking_list  b on a.id_picking_list  = b.id_picking_list 
					join tr_h3_md_do_sales_order c on c.id_do_sales_order  = b.id_ref 
					join tr_h3_md_sales_order d on d.id_sales_order  = c.id_sales_order 
					join ms_dealer f on b.id_dealer = f.id_dealer 
					join tr_h3_md_sales_order_parts g on g.id_sales_order = d.id_sales_order 
					where a.tgl_faktur >'$start_date 00:00:00' and a.tgl_faktur < '$start_date 23:59:59'
				")->result();

				$data['data'] = $get_data;
			}
		}else{
			$nama_file = 'default.txt';
		}

		$data['nama_file'] = $nama_file;
		$data['ext_file'] = $ext_file;
		$this->load->view("h3/download_file_ahm",$data); 
	}
}