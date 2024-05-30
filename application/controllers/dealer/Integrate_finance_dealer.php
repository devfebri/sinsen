<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Integrate_finance_dealer extends CI_Controller {

	var $tables = "tr_invoice_pelunasan";	
	var $folder = "dealer";
	var $page   = "integrate_finance_dealer";
	var $title  = "Integrate Finance Dealer";

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
			$sub_array     = array();
			$button = '';
			// $btn_cetak = "<a data-toggle='tooltip' title='Print' href='dealer/invoice_pelunasan/cetak?id=$rs->id_inv_pelunasan'><button class='btn btn-flat btn-xs btn-primary'>Print</button></a>";
			// $button = $btn_cetak;
			// $sub_array[] = "<a data-toggle='tooltip' href='dealer/invoice_pelunasan/detail?id=$rs->id_inv_pelunasan'>$rs->id_inv_pelunasan</a>";
			$sub_array[] = $rs->no_spk;
			$sub_array[] = $rs->id_tipe_kendaraan;
			$sub_array[] = $rs->id_warna;
			$tot_harga = $rs->harga_on_road - $rs->diskon;
			$sub_array[] = mata_uang_rp($tot_harga);
			$sub_array[] = $rs->id_invoice_tjs;
			$sub_array[] = $rs->id_invoice_dp;
			$sub_array[] = $rs->id_inv_pelunasan;
			$sub_array[] = $rs->id_prospek;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->no_ktp;
			$sub_array[] = $rs->created_at;
			$sub_array[] = mata_uang_rp($rs->tanda_jadi);
			$sub_array[] = mata_uang_rp($rs->dp_stor);
			$sub_array[] = mata_uang_rp($tot_harga - $rs->tanda_jadi);
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
		$order_column = array('id_spk',null,null,null,null,null,null,null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_spk.created_at DESC';
		$search       = $this->input->post('search')['value'];
		
		$id_dealer     = $this->m_admin->cari_dealer();
		$searchs      = "WHERE tr_invoice_pelunasan.id_dealer=$id_dealer";

		if ($search!='') {
	      $searchs .= " AND (tr_spk.nama_konsumen LIKE '%$search%' 
	          OR no_spk LIKE '%$search%'
	          OR tr_invoice_pelunasan.id_inv_pelunasan LIKE '%$search%'
	          OR tr_invoice_pelunasan.id_spk LIKE '%$search%'
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

   		return $this->db->query("SELECT tr_spk.*,
   			(SELECT id_invoice FROM tr_invoice_tjs WHERE id_spk =no_spk ORDER BY created_at LIMIT 1)AS id_invoice_tjs,
   			(SELECT id_invoice_dp FROM tr_invoice_dp WHERE id_spk =no_spk ORDER BY created_at LIMIT 1)AS id_invoice_dp,
   			(SELECT id_inv_pelunasan FROM tr_invoice_pelunasan WHERE id_spk =no_spk ORDER BY created_at LIMIT 1)AS id_inv_pelunasan,
   			(SELECT id_prospek FROM tr_prospek WHERE id_customer =tr_spk.id_customer ORDER BY created_at LIMIT 1)AS id_prospek
			FROM tr_invoice_pelunasan
   			JOIN tr_spk ON tr_invoice_pelunasan.id_spk=tr_spk.no_spk
   			$searchs $order $limit ");
   	}

   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	}

		// public function add()
		// {				
		// 	$data['isi']   = $this->page;		
		// 	$data['title'] = $this->title;		
		// 	$data['mode']  = 'insert';
		// 	$data['set']   = "form";
		// 	$id_dealer     = $this->m_admin->cari_dealer();
		// 	$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
		// 		(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
		// 			case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran	
		// 		FROM tr_spk 
		// 			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
		// 			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
		// 			WHERE tr_spk.id_dealer=$id_dealer ORDER BY tr_spk.created_at DESC");
		// 	// $data['spk'] = $this->db->get('tr_spk');
		// 	$this->template($data);	
		// }
		
		// public function get_id_invoice()
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
		// 	$get_data  = $this->db->query("SELECT * FROM tr_invoice_pelunasan
		// 		WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
		// 		ORDER BY created_at DESC LIMIT 0,1");
		//    		if ($get_data->num_rows()>0) {
		// 			$row        = $get_data->row();
		// 			$id_inv_pelunasan = substr($row->id_inv_pelunasan, -5);
		// 			$new_kode   = 'FP/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.05d",$id_inv_pelunasan+1);
		// 			$i=0;
		// 			while ($i<1) {
		// 				$cek = $this->db->get_where('tr_invoice_pelunasan',['id_inv_pelunasan'=>$new_kode])->num_rows();
		// 			    if ($cek>0) {
		// 					$neww     = substr($new_kode, -5);
		// 					$new_kode = 'FP/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.05d",$id_inv_pelunasan+1);
		// 					$i        = 0;
		// 			    }else{
		// 			    	$i++;
		// 			    }
		// 			}
		//    		}else{
		// 			$new_kode = 'FP/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.'00001';
		//    		}
	 //   		return strtoupper($new_kode);
		// }	

		// public function x()
		// {
		// 	echo gmdate("y-m-d H:i:s", time()+60*60*7);
		// 	echo '</br>';
		// 	echo gmdate("y-m-d", time()+60*60*7);
		// }

		// public function save()
		// {		
		// 	$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
		// 	$login_id  = $this->session->userdata('id_user');
		// 	$id_dealer = $this->m_admin->cari_dealer();
			
		// 	$id_invoice = $data['id_inv_pelunasan'] = $this->get_id_invoice();
		// 	$data['id_spk']             = $this->input->post('id_spk');
		// 	$data['id_dealer']          = $id_dealer;
		// 	$data['created_at']         = $waktu;		
		// 	$data['created_by']         = $login_id;

		// 	// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// 	// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
		// 	// $email          = array();
		// 	// foreach ($get_notif_grup->result() as $rd) {
		// 	// 	$get_email = $this->db->query("SELECT email FROM ms_karyawan 
		// 	// 			WHERE id_karyawan IN(
		// 	// 				SELECT id_karyawan_dealer FROM ms_user 
		// 	// 				WHERE jenis_user='Main Dealer' 
		// 	// 				AND active=1 
		// 	// 				AND id_user_group=(
		// 	// 					SELECT id_user_group FROM ms_user_group 
		// 	// 					WHERE code='$rd->code_user_group'
		// 	// 				)
		// 	// 			)
		// 	// 	")->result();
		// 	// 	foreach ($get_email as $usr) {
		// 	// 		$email[] = $usr->email;
		// 	// 	}
		// 	// }

		// 	// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 	// 			'id_referensi' => $kode_event,
		// 	// 			'judul'        => "Event Baru Dari Dealer",
		// 	// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 	// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 	// 			'status'       =>'baru',
		// 	// 			'created_at'   => $waktu,
		// 	// 			'created_by'   => $login_id
		// 	// 		 ];
		// 	$this->db->trans_begin();
		// 		$this->db->insert('tr_invoice_pelunasan',$data);
		// 		// $this->db->insert('tr_notifikasi',$notif);
							
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

	 //        	$_SESSION['pesan'] 	= "Data has been saved successfully";
		// 		$_SESSION['tipe'] 	= "success";
		// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/invoice_pelunasan'>";
	 //      	}
	 //    }

	public function cetak()
	{
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_invoice = $this->input->get('id');				
  		
  		$get_data = $this->db->query("SELECT tr_spk.*,tr_invoice_pelunasan.id_inv_pelunasan,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,tr_invoice_pelunasan.created_at FROM tr_invoice_pelunasan
   			JOIN tr_spk ON tr_invoice_pelunasan.id_spk=tr_spk.no_spk
   			WHERE id_inv_pelunasan='$id_invoice' ");
  		if ($get_data->num_rows()>0) {
  			$row = $data['row'] = $get_data->row();
  			
  			$upd = ['print_ke'=> $row->print_ke+1,
  					'print_at'=> $waktu,
  					'print_by'=> $login_id,
  				   ];
  			$this->db->update('tr_invoice_pelunasan',$upd,['id_inv_pelunasan'=>$id_invoice]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print';
			$data['row'] = $row;
        	
        	$html = $this->load->view('dealer/invoice_pelunasan_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/invoice_pelunasan'>";		
        }
        
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_invoice = $this->input->get('id');
		$row = $this->db->query("SELECT tr_spk.*,id_spk,tr_invoice_pelunasan.id_inv_pelunasan,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
				case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran,
			CONCAT(tr_spk.id_tipe_kendaraan,' | ',tipe_ahm) as tipe,
			CONCAT(tr_spk.id_warna,' | ',warna) as warna	
			FROM tr_invoice_pelunasan
   			JOIN tr_spk ON tr_invoice_pelunasan.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			WHERE tr_invoice_pelunasan.id_inv_pelunasan='$id_invoice'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
			$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran	
			FROM tr_spk 
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				WHERE tr_spk.id_dealer=$id_dealer ORDER BY tr_spk.created_at DESC");
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/Invoice_tjs'>";		
		}
		$this->template($data);	
	}
}