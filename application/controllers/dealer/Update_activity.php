<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_activity extends CI_Controller {

	var $folder = "dealer";
	var $page   = "update_activity";
	var $title  = "Update Activity After Dealing";

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
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

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
		$data['set']	= "index";		
		$id_dealer = $this->m_admin->cari_dealer();					
		$this->template($data);	
	}

	public function fetch()
   	{
		$fetch_data = $this->make_query();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array     = array();
			$button = '';
			// if ($rs->no_mesin!=null) {
			// 	$alert = "return confirm('Apakah anda yakin ingin melepaskan unit dengan nomor mesin $rs->no_mesin ke No Indent $rs->id_indent atas nama $rs->nama_konsumen ?')";
			// }else{
			// 	$alert = "alert('No Mesin belum tersedia !');return false";
			// }
			$btn_update = "<a data-toggle='tooltip' title='Update' href='dealer/update_activity/update?id=$rs->id_manage'><button class='btn btn-flat btn-xs btn-warning'>Update</button></a>";
			// $button = $btn_konfir;
			// $cek_nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka FROM tr_penerimaan_unit_dealer_detail 
			// 		JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin
			// 		WHERE id_indent='$rs->id_indent'");
			// $nosin='';$no_rangka='';
			// if ($cek_nosin->num_rows()>0) {
			// 	$cs        = $cek_nosin->row();
			// 	$nosin     = $cs->no_mesin;
			// 	$no_rangka = $cs->no_rangka;
			// 	$button    = '';
			// }
			$button = $btn_update;
			// $sub_array[] = "<a data-toggle='tooltip' href='dealer/indent_fullfilment/detail?id=$rs->id_pesan'>$rs->id_pesan</a>";
			$sub_array[] = $rs->kategori;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->detail_activity;
			$sub_array[] = $rs->status;
			$sub_array[] = $rs->generate_at;
			$sub_array[] = $button;
			$data[]      = $sub_array;  
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
		$order_column = array('id_pesan','tipe_pesan','konten','start_date','end_date',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY maad.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		// $searchs = '';
		$date_now = date('Y-m-d');
		$searchs      = "WHERE maad.id_dealer=$id_dealer AND updated IS NULL AND maad.status='In Progress'";
		
		if ($search!='') {
	      $searchs .= "AND (nama_konsumen LIKE '%$search%' 
	          OR id_spk LIKE '%$search%'
	          OR id_tipe_kendaraan LIKE '%$search%'
	          OR id_warna LIKE '%$search%'
	          OR warna LIKE '%$search%'
	          OR tipe_ahm LIKE '%$search%')
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT maad.*,
   			(SELECT nama_lengkap FROM ms_karyawan_dealer WHERE id_karyawan_dealer=(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1))as sales,maad.status,tr_spk.nama_konsumen,LEFT(generate_at,10) AS generate_at
   			FROM tr_manage_activity_after_dealing AS maad
   			LEFT JOIN tr_po_dealer_indent ON maad.id_indent=tr_po_dealer_indent.id_indent
   			JOIN tr_spk ON maad.no_spk=tr_spk.no_spk
   			$searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	}  

   	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "history";		
		$id_dealer = $this->m_admin->cari_dealer();					
		$this->template($data);	
	}

	public function fetch_history()
   	{
		$fetch_data = $this->make_query_history();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array     = array();
			$button = '';
			// if ($rs->no_mesin!=null) {
			// 	$alert = "return confirm('Apakah anda yakin ingin melepaskan unit dengan nomor mesin $rs->no_mesin ke No Indent $rs->id_indent atas nama $rs->nama_konsumen ?')";
			// }else{
			// 	$alert = "alert('No Mesin belum tersedia !');return false";
			// }
			// $btn_konfir = "<a data-toggle='tooltip' onclick=\"$alert\" title='Delete' href='dealer/indent_fullfilment/save_konfirmasi?id=$rs->id_indent'><button class='btn btn-flat btn-xs btn-success'>Konfirmasi</button></a>";
			// $button = $btn_konfir;
			// $cek_nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka FROM tr_penerimaan_unit_dealer_detail 
			// 		JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin
			// 		WHERE id_indent='$rs->id_indent'");
			// $nosin='';$no_rangka='';
			// if ($cek_nosin->num_rows()>0) {
			// 	$cs        = $cek_nosin->row();
			// 	$nosin     = $cs->no_mesin;
			// 	$no_rangka = $cs->no_rangka;
			// 	$button    = '';
			// }
			// $sub_array[] = "<a data-toggle='tooltip' href='dealer/indent_fullfilment/detail?id=$rs->id_pesan'>$rs->id_pesan</a>";
			$sub_array[] = $rs->id_manage;
			$sub_array[] = $rs->kategori;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->detail_activity;
			$sub_array[] = $rs->sales;
			$sub_array[] = $rs->status;
			$sub_array[] = $rs->keterangan;
			$data[]      = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_history(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   	}

   	function make_query_history($no_limit=null)  
   	{  
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_pesan','tipe_pesan','konten','start_date','end_date',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY maad.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		// $searchs = '';
		$date_now = date('Y-m-d');
		$searchs      = "WHERE maad.id_dealer=$id_dealer AND LEFT(generate_at,10)<'$date_now'";
		
		if ($search!='') {
	      $searchs .= "AND (nama_konsumen LIKE '%$search%' 
	          OR id_spk LIKE '%$search%'
	          OR id_tipe_kendaraan LIKE '%$search%'
	          OR id_warna LIKE '%$search%'
	          OR warna LIKE '%$search%'
	          OR tipe_ahm LIKE '%$search%')
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT maad.*,
   			(SELECT nama_lengkap FROM ms_karyawan_dealer WHERE id_karyawan_dealer=(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1))as sales,maad.status,tr_spk.nama_konsumen
   			FROM tr_manage_activity_after_dealing AS maad
   			LEFT JOIN tr_po_dealer_indent ON maad.id_indent=tr_po_dealer_indent.id_indent
   			JOIN tr_spk ON maad.no_spk=tr_spk.no_spk
   			$searchs $order $limit ");
   	}  
   	function get_filtered_data_history(){  
		return $this->make_query('y')->num_rows();  
   	}  
	
	public function update()
	{				
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;		
		$data['set']      = "form";					
		$data['mode']     = "insert";	
		$id_manage = $this->input->get('id');
		$row = $this->db->query("SELECT tr_manage_activity_after_dealing.*,nama_konsumen FROM tr_manage_activity_after_dealing 
			JOIN tr_spk ON tr_manage_activity_after_dealing.no_spk=tr_spk.no_spk
			WHERE id_manage='$id_manage'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();	
		}else{

		}
		$this->template($data);										
	}

	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');

		$id_dealer = $this->m_admin->cari_dealer();
		
		$id_manage         = $this->input->post('id_manage');
		$upd['follow_up']  = $this->input->post('follow_up');
		$upd['keterangan'] = $this->input->post('keterangan');
		$upd['updated']    = 1;
		$upd['status']    = 'completed';
		$upd['updated_at'] = $waktu;
		$upd['updated_by'] = $login_id;
		// var_dump($upd);
			$this->db->trans_begin();
				$this->db->update('tr_manage_activity_after_dealing',$upd,['id_manage'=>$id_manage]);
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
		
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
	        	$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/update_activity'>";
	      	}
	      	// echo json_encode($rsp);
	}


	// public function get_sj()
	// {
	// 	$th        = date('Y');
	// 	$bln       = date('m');
	// 	$th_bln    = date('Y-m');
	// 	$thbln     = date('ym');
	// 	$id_dealer = $this->m_admin->cari_dealer();
	// 	$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
	// 	$get_data  = $this->db->query("SELECT * FROM tr_mutasi
	// 		WHERE id_dealer='$id_dealer'
	// 		AND LEFT(created_at,7)='$th_bln'
	// 		AND no_sj IS NOT NULL
	// 		ORDER BY created_at DESC LIMIT 0,1");
	//    		if ($get_data->num_rows()>0) {
	// 			$row      = $get_data->row();
	// 			$no_sj    = substr($row->no_sj, -4);
	// 			$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
	// 			$i=0;
	// 			while ($i<1) {
	// 				$cek = $this->db->get_where('tr_mutasi',['no_sj'=>$new_kode])->num_rows();
	// 			    if ($cek>0) {
	// 			    	$no_sj    = substr($new_kode, -4);
	// 					$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
	// 			    	$i=0;
	// 			    }else{
	// 			    	$i++;
	// 			    }
	// 			}
	//    		}else{
	// 			$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	//    		}
 //   		return strtoupper($new_kode);
	// }

	// public function print_sj(){
	// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
	// 	$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$login_id  = $this->session->userdata('id_user');
	// 	$id_mutasi = $this->input->get('id');				
  
 //  		$get_data = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event']);
 //  		if ($get_data->num_rows()>0) {
 //  			$row = $get_data->row();
 //  			$no_sj = $row->no_sj;
 //  			if ($row->no_sj==null)$no_sj=$this->get_sj();

 //  			$upd = ['print_sj_ke'=> $row->print_sj_ke+1,
 //  					'print_sj_at'=> $waktu,
 //  					'print_sj_by'=> $login_id,
 //  					'no_sj' => $no_sj
 //  				   ];

 //  			$this->db->update('tr_mutasi',$upd,['id_mutasi'=>$id_mutasi]);
			
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set']    = 'print_sj';
	// 		$row            = $data['row'] = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event'])->row();
	// 		$data['event']  = $this->db->get_where('ms_event',['id_event'=>$row->id_event])->row();
	// 		$data['dealer'] = $this->db->get_where('ms_dealer',['id_dealer'=>$row->id_dealer])->row()->nama_dealer;
 //        	$data['details'] = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna,close,id_mutasi_detail
	// 		 FROM tr_mutasi_detail 
	// 		 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
	// 		 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
 //            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 //            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
	// 		 WHERE id_mutasi='$id_mutasi'")->result();

 //        	$html = $this->load->view('dealer/mutasi_stok_cetak', $data, true);
 //        	// render the view into HTML
	//         $mpdf->WriteHTML($html);
	//         // write the HTML into the mpdf
	//         $output = 'cetak_.pdf';
	//         $mpdf->Output("$output", 'I');	        
 //        }else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";		
 //        }
        
	// }
}