<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reminder_follow_up extends CI_Controller {

	var $tables = "tr_reminder_follow_up";	
	var $folder = "dealer";
	var $page   = "reminder_follow_up";
	var $title  = "Reminder Follow UP";

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
		$this->template($data);	
	}

	public function fetch()
   	{
		$fetch_data = $this->make_query();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array        = array();
			$button           = '';
			$status_aktivitas = '';
			$keterangan       = '';
			// // $btn_del = "<a data-toggle='tooltip' onclick=\"return confirm('Are you sure to delete this data ?')\" title='Delete' href='dealer/pesan_d/delete?id=$rs->id_pesan'><button class='btn btn-flat btn-sm btn-danger'><i class='fa fa-trash'></i></button></a>";
			$btn_update = "<a data-toggle='tooltip' href='dealer/reminder_follow_up/update_fu?id=$rs->id_reminder' class='btn btn-flat btn-xs btn-primary'>Update FU</a>";
			// $btn_print = "<a data-toggle='tooltip' href='dealer/generate_list_unit_delivery/print_list?id=$rs->id_generate'><button class='btn btn-flat btn-xs btn-success'>Cetak Ulang</button></a>";
			// $button = $btn_print.' '.$btn_;
			// // $sub_array[] = "<a data-toggle='tooltip' href='dealer/pesan_d/detail?id=$rs->id_pesan'>$rs->id_pesan</a>";
			if ($rs->status_aktivitas=='to_contact') {
				$status_aktivitas ="<label class='label label-warning'>".ucwords(str_replace('_', ' ', $rs->status_aktivitas))."</label>";
				$button = $btn_update;
			}
			if ($rs->status_aktivitas=='uncontactable') {
				$status_aktivitas ="<label class='label label-warning'>".ucwords(str_replace('_', ' ', $rs->status_aktivitas))."</label>";
				$button = $btn_update;
			}
			if ($rs->status_aktivitas=='contactable') {
				$status_aktivitas ="<label class='label label-success'>".ucwords(str_replace('_', ' ', $rs->status_aktivitas))."</label>";
			}
			$sub_array[] = $btn_del = "<a href='dealer/reminder_follow_up/detail?id=$rs->id_reminder'>$rs->id_reminder</a>";;
			$sub_array[] = $rs->id_customer;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->no_hp;
			$sub_array[] = $status_aktivitas;
			$sub_array[] = $rs->status_uncontactable;
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
		$order        = 'ORDER BY rfu.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE rfu.id_dealer=$id_dealer AND uncontactable_ke<=(SELECT maks_uncontactable_sales_fol_up FROM ms_setting_h1 WHERE id_setting_h1=1)";
		
		if ($search!='') {
	      $searchs .= "AND (tgl_pengiriman LIKE '%$search%' 
	          OR created_at LIKE '%$search%'
	          )
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT rfu.*,id_customer,nama_konsumen,no_hp
   			FROM tr_reminder_follow_up AS rfu
   			JOIN tr_sales_order ON rfu.id_sales_order=tr_sales_order.id_sales_order
   			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
   		 $searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	}  

	public function update_fu()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'update';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_reminder = $this->input->get('id');
		$row = $this->db->query("SELECT rfu.*,no_hp,nama_konsumen,
			(SELECT CONCAT(id_tipe_kendaraan,' | ',tipe_ahm) FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS desc_unit,
			(SELECT CONCAT(id_warna,' | ',warna) FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna,
			(SELECT CONCAT(tr_prospek.id_flp_md,' | ',nama_lengkap) FROM tr_prospek 
				JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
				WHERE id_customer=tr_spk.id_customer ORDER BY tr_prospek.created_at DESC LIMIT 1) AS id_sales
			FROM tr_reminder_follow_up AS rfu 
			JOIN tr_sales_order ON rfu.id_sales_order=tr_sales_order.id_sales_order
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE id_reminder=$id_reminder AND status_aktivitas!='contactable'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/reminder_follow_up'>";
		}
	}

	// function get_unit()
	// {
	// 	$tgl_pengiriman = $this->input->post('tgl_pengiriman');
	// 	$so = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 		(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 		(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 		(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 		(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir,
	// 		(SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
	// 			JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
	// 			JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
	// 			WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS ksu
	// 		FROM tr_sales_order AS so
	// 		JOIN tr_spk ON so.no_spk=tr_spk.no_spk
	// 		WHERE so.tgl_pengiriman='$tgl_pengiriman'")->result();
	// 	echo json_encode($so);
	// }
	
	// // public function get_()
	// // {
	// // 	$th       = date('Y');
	// // 	$bln      = date('m');
	// // 	$th_bln   = date('Y-m');
	// // 	$th_kecil = date('y');
	// // 	$id_dealer = $this->m_admin->cari_dealer();
	// // 	// $id_sumber='E20';
	// // 	// if ($id_dealer!=null) {
	// // 		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
	// // 		$id_sumber = $dealer->kode_dealer_md;
	// // 	// }
	// // 	$get_data  = $this->db->query("SELECT * FROM ms_pesan
	// // 		WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
	// // 		ORDER BY created_at DESC LIMIT 0,1");
	// //    		if ($get_data->num_rows()>0) {
	// // 			$row      = $get_data->row();
	// // 			$id_pesan = substr($row->id_pesan, -5);
	// // 			$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
	// // 			$i=0;
	// // 			while ($i<1) {
	// // 				$cek = $this->db->get_where('ms_pesan',['id_pesan'=>$new_kode])->num_rows();
	// // 			    if ($cek>0) {
	// // 					$neww     = substr($new_kode, -5);
	// // 					$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
	// // 					$i        = 0;
	// // 			    }else{
	// // 			    	$i++;
	// // 			    }
	// // 			}
	// //    		}else{
	// // 			$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.'00001';
	// //    		}
 // //   		return strtoupper($new_kode);
	// // }	
	public function upd()
	{
		$get = $this->db->query("SELECT * FROM tr_reminder_follow_up");
		$count=0;
		foreach ($get->result() as $val) {
			$cek = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order='$val->id_sales_order'");
			if ($cek->num_rows()>0) {
				$cek = $cek->row();
				if ($cek->id_dealer!=$val->id_dealer) {
					$upd['id_reminder'] = $val->id_reminder;
					$upd['id_dealer'] = $cek->id_dealer;
					$upd['status_aktivitas'] = 'to_contact';
					$upd['ucapan_terimakasih'] = null;
					$upd['reminder_kpb'] = null;
					$upd['info_dealer'] = null;
					$upd['updated_at'] = null;
					$upd['updated_by'] = null;
					$upd_[] = $upd;
					$count++;
				}
			}
			
		}
		$this->db->update_batch('tr_reminder_follow_up',$upd_,'id_reminder');
		echo $count;
	}
	public function save_update()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		// $data['id_dealer']            = $id_dealer;
		$data['updated_at']           = $waktu;		
		$data['updated_by']           = $login_id;
		$id_reminder = $this->input->post('id_reminder');
		$data['metode_fol_up']        = $this->input->post('metode_fol_up');
		$status_uncontactable = $this->input->post('status_uncontactable');
		if ($status_uncontactable=='') {
			$data['ucapan_terimakasih']   = $this->input->post('ucapan_terimakasih')=='yes'?1:'';
			$data['info_dealer']          = $this->input->post('info_dealer')=='yes'?1:'';
			$data['reminder_kpb']         = $this->input->post('reminder_kpb')=='yes'?1:'';
			$data['status_aktivitas']     = 'contactable';

		}else{
			$rmd  = $this->db->query("SELECT * FROM tr_reminder_follow_up WHERE id_reminder=$id_reminder");
			if ($rmd->num_rows()==0) {
				redirect('dealer/reminder_follow_up','refresh');
			}else{
				$data['uncontactable_ke']     = $rmd->row()->uncontactable_ke+1;
				$data['status_uncontactable'] = $status_uncontactable;
				$data['status_aktivitas']     = 'uncontactable';
				$data['tgl_fu_berikutnya']    = $this->input->post('tgl_fu_berikutnya');
			}
		}

		$data['metode_fol_up']        = $this->input->post('metode_fol_up');
		// var_dump($data);
		$this->db->trans_begin();
			$this->db->update('tr_reminder_follow_up',$data,['id_reminder'=>$id_reminder]);			
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<script>history.go(-1)</script>";
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/reminder_follow_up'>";
      	}
    }

 //    public function delete()
	// {		
	// 	$tabel			= $this->tables;
	// 	$pk 			= 'id_pesan';
	// 	$id 			= $this->input->get('id');		
	// 	$this->db->trans_begin();			
	// 		$this->db->delete($tabel,array($pk=>$id));
	// 	$this->db->trans_commit();			
	// 	$result = 'Success';									

	// 	if($this->db->trans_status() === FALSE){
	// 		$result = 'You can not delete this data because it already used by the other tables';										
	// 		$_SESSION['tipe'] 	= "danger";			
	// 	}else{
	// 		$result = 'Data has been deleted succesfully';										
	// 		$_SESSION['tipe'] 	= "success";			
	// 	}
	// 	$_SESSION['pesan'] 	= $result;
	// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pesan_d'>";
	// }

	// public function print_list()
	// {
	// 	$tgl         = gmdate("y-m-d", time()+60*60*7);
	// 	$waktu       = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$login_id    = $this->session->userdata('id_user');
	// 	$id_generate = $this->input->get('id');				
  		
 //  		$get_data = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery AS glud
 //   			WHERE id_generate='$id_generate' ");
 //  		if ($get_data->num_rows()>0) {
 //  			$row = $data['row'] = $get_data->row();
 //  			$data['units'] = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 			(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 			(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 			(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 			(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir
	// 			FROM tr_generate_list_unit_delivery_detail AS gludd
	// 			JOIN tr_sales_order AS so ON gludd.id_sales_order=so.id_sales_order
	// 			JOIN tr_spk ON tr_spk.no_spk=so.no_spk
	// 			WHERE id_generate=$id_generate
	// 			")->result();
  			
 //  			$upd = ['print_ke'=> $row->print_ke+1,
 //  					'print_at'=> $waktu,
 //  					'print_by'=> $login_id,
 //  				   ];
 //  			$this->db->update('tr_generate_list_unit_delivery',$upd,['id_generate'=>$id_generate]);
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set'] = 'print';
        	
 //        	$html = $this->load->view('dealer/generate_list_unit_delivery_cetak', $data, true);
 //        	// render the view into HTML
	//         $mpdf->WriteHTML($html);
	//         // write the HTML into the mpdf
	//         $output = 'cetak_.pdf';
	//         $mpdf->Output("$output", 'I');	        
 //        }else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";		
 //        }
        
	// }

	// public function detail()
	// {				
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['mode']  = 'detail';
	// 	$data['set']   = "form";
	// 	$id_dealer     = $this->m_admin->cari_dealer();
	// 	$id_generate = $this->input->get('id');
	// 	$row = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery WHERE id_generate='$id_generate' AND id_dealer=$id_dealer");
	// 	if ($row->num_rows()>0) {
	// 		$data['row'] = $row->row();
	// 		$data['units'] = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 			(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 			(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 			(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 			(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir,
	// 			(SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
	// 			JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
	// 			JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
	// 			WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS ksu
	// 			FROM tr_generate_list_unit_delivery_detail AS gludd
	// 			JOIN tr_sales_order AS so ON gludd.id_sales_order=so.id_sales_order
	// 			JOIN tr_spk ON tr_spk.no_spk=so.no_spk
	// 			WHERE id_generate=$id_generate
	// 			")->result();
	// 		$this->template($data);	
	// 	}else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";
	// 	}
	// }
	// public function assign_supir()
	// {				
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['mode']  = 'assign_supir';
	// 	$data['set']   = "form";
	// 	$id_dealer     = $this->m_admin->cari_dealer();
	// 	$id_generate = $this->input->get('id');
	// 	$row = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery WHERE id_generate='$id_generate' AND id_dealer=$id_dealer");
	// 	if ($row->num_rows()>0) {
	// 		$data['row'] = $row->row();
	// 		$data['units'] = $this->db->query("SELECT so.*,id_tipe_kendaraan,id_warna,
	// 			(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS tipe_ahm,
	// 			(SELECT warna FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna, 
	// 			(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_spk.no_mesin) AS no_rangka2,
	// 			(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir,
	// 			(SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
	// 			JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
	// 			JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
	// 			WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS ksu
	// 			FROM tr_generate_list_unit_delivery_detail AS gludd
	// 			JOIN tr_sales_order AS so ON gludd.id_sales_order=so.id_sales_order
	// 			JOIN tr_spk ON tr_spk.no_spk=so.no_spk
	// 			WHERE id_generate=$id_generate
	// 			")->result();
	// 		$this->template($data);	
	// 	}else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";
	// 	}
	// }

	// public function save_assign()
	// {		
	// 	$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
	// 	$login_id  = $this->session->userdata('id_user');
	// 	$id_dealer = $this->m_admin->cari_dealer();
		
	// 	$nama_supir     = $this->input->post('nama_supir');
	// 	$id_sales_order = $this->input->post('id_sales_order');
	// 	$id_generate    = $this->input->post('id_generate');
	// 	$data['proses_pdi']       = isset($_POST['proses_pdi'])?1:null;
	// 	$data['manual_book']      = isset($_POST['manual_book'])?1:null;
	// 	$data['standard_toolkit'] = isset($_POST['standard_toolkit'])?1:null;
	// 	$data['helmet']           = isset($_POST['helmet'])?1:null;
	// 	$data['spion']            = isset($_POST['spion'])?1:null;
	// 	$data['bppgs']            = isset($_POST['bppgs'])?1:null;
	// 	$data['aksesoris']        = isset($_POST['aksesoris'])?1:null;

	// 	foreach ($nama_supir as $key =>$sp) {
	// 		$upd_sopir[] = ['id_sales_order'=>$id_sales_order[$key],'id_master_plat'=>$sp];
	// 	}

	// 	$this->db->trans_begin();
	// 		$this->db->update('tr_generate_list_unit_delivery',$data,['id_generate'=>$id_generate]);
	// 		$this->db->update_batch('tr_sales_order',$upd_sopir,'id_sales_order');			
	// 	if ($this->db->trans_status() === FALSE)
 //      	{
	// 		$this->db->trans_rollback();
	// 		$_SESSION['pesan'] 	= "Something when Wrong";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<script>history.go(-1)</script>";
 //      	}
 //      	else
 //      	{
 //        	$this->db->trans_commit();
 //        	$_SESSION['pesan'] 	= "Data has been processed successfully";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/generate_list_unit_delivery'>";
 //      	}
 //    }
}