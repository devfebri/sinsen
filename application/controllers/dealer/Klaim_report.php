<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Klaim_report extends CI_Controller {

	var $tables = "tr_sales_order";	
	var $folder = "dealer";
	var $page   = "klaim_report";
	var $title  = "Claim Report";

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
			$status_claim     = "";
			$status_proses_md = '';		
			$tipe_ahm = $this->db->get_where("ms_tipe_kendaraan",['id_tipe_kendaraan'=>$rs->id_tipe_kendaraan]);
			$tipe_ahm = $tipe_ahm->num_rows()>0?$tipe_ahm->row()->tipe_ahm:'';
			$program  = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_program_md='$rs->id_program_md' AND id_tipe_kendaraan='$rs->id_tipe_kendaraan' AND id_warna LIKE('%$rs->id_warna%')");
			$ahm=0;$md=0;$dealer=0;
			if ($rs->jenis_beli=='Kredit') {
				$promo = $rs->voucher_2;
				if ($program->num_rows()>0) {
					$pr     = $program->row();
					$ahm    = $pr->ahm_kredit;
					$md     = $pr->md_kredit;
					$dealer = $pr->dealer_kredit;
				}
			}else{
				$promo = $rs->voucher_1;
				if ($program->num_rows()>0) {
					$pr     = $program->row();
					$ahm    = $pr->ahm_cash;
					$md     = $pr->md_cash;
					$dealer = $pr->dealer_cash;
				}
			}

			if ($rs->status_proposal=='draft') {
				$status_proposal = "<label class='label label-info'>Draft</label>";
			}
			if ($rs->status_proposal=='cancel') {
				$status_proposal = "<label class='label label-warning'>Cancel</label>";
			}
			if ($rs->status_proposal=='submitted') {
				$status_proposal = "<label class='label label-success'>Submitted</label>";
			}
			// if ($rs->status_proses_md==null) {
			// 	$status_proses_md = "<label class='label label-warning'>Pending</label>";
			// }
			// if ($rs->status_proses_md=='reject') {
			// 	$status_proses_md = "<label class='label label-danger'>Rejected By MD</label>";
			// }
			if ($rs->status_proposal=='completed_by_md') {
				
				if ($rs->status_claim=='rejected') {
					$status_proposal = "<label class='label label-danger'>Rejected By MD</label>";
					$get_alasan = $this->db->query("SELECT * FROM ms_alasan_reject WHERE id_alasan_reject IN(SELECT alasan_reject FROM tr_claim_dealer_syarat WHERE id_claim='$rs->id_claim' AND alasan_reject IS NOT NULL)")->result();
					$alasan_reject ='';
					foreach ($get_alasan as $value) {
						$alasan_reject.="- $value->alasan_reject </br>";
					}
				}else{
					$status_proposal = "<label class='label label-success'>Completed By MD</label>";

				}
			}
			// if ($rs->status_proses_md==null) {
			// 	$status_proses_md = "<label class='label label-warning'>Pending</label>";
			// }
			// if ($rs->status_proses_md=='reject') {
			// 	$status_proses_md = "<label class='label label-danger'>Rejected By MD</label>";
			// }
			// if ($rs->status_proses_md=='completed') {
			// 	$status_proses_md = "<label class='label label-success'>Completed By MD</label>";
			// }
			$sub_array[] = $rs->id_program_md;
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->id_sales_order;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = mata_uang_rp($rs->harga_on_road);
			$sub_array[] = mata_uang_rp($promo);
			$sub_array[] = mata_uang_rp($ahm);
			$sub_array[] = mata_uang_rp($md);
			$sub_array[] = mata_uang_rp($dealer);
			$sub_array[] = $status_proposal;
			$sub_array[] = $status_proses_md;
			$promo = 0;
			
			// $sub_array[] = $button;
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
		$order_column = array('program_umum','tr_sales_order.no_spk','id_sales_order','id_customer','nama_konsumen','id_tipe_kendaraan','id_warna','tr_sales_order.no_mesin','tr_sales_order.no_rangka',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE status_proposal='completed_by_md'";
		
		if ($search!='') {
	      $searchs .= "AND (no_spk LIKE '%$search%' 
	          OR id_sales_order LIKE '%$search%'
	          OR id_customer LIKE '%$search%'
	          OR nama_konsumen LIKE '%$search%'
	          OR id_tipe_kendaraan LIKE '%$search%'
	          OR id_warna LIKE '%$search%'
	          OR tr_sales_order.no_mesin LIKE '%$search%'
	          OR no_rangka LIKE '%$search%'
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

   		return $this->db->query("
   			SELECT * FROM (
   				SELECT 
   					program_umum AS id_program_md, tr_spk.no_spk,so.id_sales_order, so.no_mesin,no_rangka,harga_on_road,voucher_1, voucher_2,so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'umum' AS jenis_program,id_claim,tr_claim_dealer.status AS status_claim
   					FROM tr_sales_order AS so
		   			JOIN tr_spk ON so.no_spk=tr_spk.no_spk
		   			LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_umum=tr_claim_dealer.id_program_md
		   			WHERE so.id_dealer=$id_dealer AND no_invoice IS NOT NULL AND program_umum IS NOT NULL AND tr_spk.program_umum!=''
		   			AND status_proposal='completed_by_md'
		   		UNION 
		   		SELECT 
   					program_gabungan AS id_program_md, tr_spk.no_spk,so.id_sales_order,so.no_mesin,no_rangka,harga_on_road,voucher_tambahan_1 AS voucher_1,voucher_tambahan_2 AS voucher_2, so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'gabungan' AS jenis_program,id_claim,tr_claim_dealer.status AS status_claim
   					FROM tr_sales_order AS so
		   			JOIN tr_spk ON so.no_spk=tr_spk.no_spk
		   			LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_gabungan=tr_claim_dealer.id_program_md
		   			WHERE so.id_dealer=$id_dealer AND no_invoice IS NOT NULL AND program_gabungan IS NOT NULL AND tr_spk.program_gabungan!=''
		   			AND status_proposal='completed_by_md'
   			) AS table_union
   		 $searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	} 

   	public function ajukan()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl            = gmdate("y-m-d", time()+60*60*7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();
		$id_sales_order = $this->input->get('id');
		$cek_so = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order='$id_sales_order' AND id_dealer=$id_dealer AND id_sales_order NOT IN(SELECT id_sales_order FROM tr_klaim_proposal)");
		if ($cek_so->num_rows()==0) { redirect('dealer/klaim_proposal','refresh'); }
		$so = $cek_so->row();
		$data['id_sales_order'] = $id_sales_order;
		$data['no_mesin']       = $so->no_mesin;
		$data['id_dealer']      = $id_dealer;
		$data['status_klaim']   = 'draft';
		$data['created_at']     = $waktu;		
		$data['created_by']     = $login_id;

		$this->db->trans_begin();
			$this->db->insert('tr_klaim_proposal',$data);
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been processed successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
    }

    public function cancel()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl            = gmdate("y-m-d", time()+60*60*7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();
		$id_sales_order = $this->input->get('id');
		$cek_so = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order='$id_sales_order' AND id_dealer=$id_dealer AND id_sales_order NOT IN(SELECT id_sales_order FROM tr_klaim_proposal)");
		if ($cek_so->num_rows()==0) { redirect('dealer/klaim_proposal','refresh'); }
		
		$so = $cek_so->row();
		$data['id_sales_order'] = $id_sales_order;
		$data['no_mesin']       = $so->no_mesin;
		$data['id_dealer']      = $id_dealer;
		$data['status_klaim']   = 'cancel';
		$data['created_at']     = $waktu;		
		$data['created_by']     = $login_id;
		$data['cancel_at']      = $waktu;		
		$data['cancel_by']      = $login_id;

		$this->db->trans_begin();
			$this->db->insert('tr_klaim_proposal',$data);			
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been canceled successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
    }

    public function approve()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl            = gmdate("y-m-d", time()+60*60*7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();
		$id_sales_order = $this->input->get('id');
		$cek_klaim = $this->db->query("SELECT * FROM tr_klaim_proposal WHERE id_sales_order = '$id_sales_order' AND status_klaim='draft' AND id_dealer=$id_dealer");
		if ($cek_klaim->num_rows()==0) { redirect('dealer/klaim_proposal','refresh'); }
		
		$data['status_klaim']   = 'submitted';
		$data['approved_at']    = $waktu;		
		$data['approved_by']    = $login_id;

		$this->db->trans_begin();
			$this->db->update('tr_klaim_proposal',$data,['id_sales_order'=>$id_sales_order]);			
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
    }

    public function reject()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl            = gmdate("y-m-d", time()+60*60*7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();
		$id_sales_order = $this->input->get('id');
		$alasan_reject = $this->input->get('ar');
		$cek_klaim = $this->db->query("SELECT * FROM tr_klaim_proposal WHERE id_sales_order = '$id_sales_order' AND status_klaim='draft' AND id_dealer=$id_dealer");
		if ($cek_klaim->num_rows()==0) { redirect('dealer/klaim_proposal','refresh'); }
		$klaim = $cek_klaim->row();
		$data['status_klaim']  = 'draft';
		$data['alasan_reject'] = $alasan_reject;
		$data['approved_at']   = $waktu;		
		$data['approved_by']   = $login_id;

		$this->db->trans_begin();
			$this->db->update('tr_klaim_proposal',$data,['id_sales_order'=>$id_sales_order]);			
			$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['kode_notif'=>'rjct_klaim_prop'])->row();
			$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
						'id_referensi' => $id_sales_order,
						'judul'        => "Reject Klaim Proposal",
						'pesan'        => "Telah dilakukan reject untuk klaim proposal (ID Sales Order=$klaim->id_sales_order) dengan alasan $alasan_reject ",
						'link'         => $ktg_notif->link.'/detail?id='.$id_sales_order,
						'status'       =>'baru',
						'id_dealer'	   => $id_dealer,
						'created_at'   => $waktu,
						'created_by'   => $login_id
					 ];

			$this->db->insert('tr_notifikasi',$notif);
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something when Wrong";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/klaim_proposal'>";
      	}
    }

	// public function add()
	// {				
	// 	$data['isi']   = $this->page;		
	// 	$data['title'] = $this->title;		
	// 	$data['mode']  = 'insert';
	// 	$data['set']   = "form";
	// 	$id_dealer     = $this->m_admin->cari_dealer();
	// 	$data['hasil'] = $this->db->query("SELECT tr_hasil_survey.*,tipe_ahm,warna,tr_spk.*,(SELECT finance_company FROM ms_finance_company WHERE id_finance_company=tr_spk.id_finance_company) AS finance_company
	// 		FROM tr_hasil_survey 
	// 		JOIN tr_spk ON tr_hasil_survey.no_spk=tr_spk.no_spk
	// 		JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
	// 		JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
	// 		AND id_dealer=$id_dealer
	// 		AND tr_hasil_survey.status_approval='approved'
	// 		ORDER BY tr_hasil_survey.created_at DESC");
	// 	// $data['spk'] = $this->db->get('tr_spk');
	// 	$this->template($data);	
	// }

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
	
	// public function get_()
	// {
	// 	$th       = date('Y');
	// 	$bln      = date('m');
	// 	$th_bln   = date('Y-m');
	// 	$th_kecil = date('y');
	// 	$id_dealer = $this->m_admin->cari_dealer();
	// 	// $id_sumber='E20';
	// 	// if ($id_dealer!=null) {
	// 		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
	// 		$id_sumber = $dealer->kode_dealer_md;
	// 	// }
	// 	$get_data  = $this->db->query("SELECT * FROM ms_pesan
	// 		WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
	// 		ORDER BY created_at DESC LIMIT 0,1");
	//    		if ($get_data->num_rows()>0) {
	// 			$row      = $get_data->row();
	// 			$id_pesan = substr($row->id_pesan, -5);
	// 			$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
	// 			$i=0;
	// 			while ($i<1) {
	// 				$cek = $this->db->get_where('ms_pesan',['id_pesan'=>$new_kode])->num_rows();
	// 			    if ($cek>0) {
	// 					$neww     = substr($new_kode, -5);
	// 					$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
	// 					$i        = 0;
	// 			    }else{
	// 			    	$i++;
	// 			    }
	// 			}
	//    		}else{
	// 			$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.'00001';
	//    		}
 //   		return strtoupper($new_kode);
	// }	

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
	// 			(SELECT driver FROM ms_plat_dealer WHERE id_master_plat=so.id_master_plat) AS nama_supir
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