<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print_receipt extends CI_Controller {

	var $tables = "tr_invoice_pelunasan";	
	var $folder = "dealer";
	var $page   = "print_receipt";
	var $title  = "Print Receipt";

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

	public function fetch_tjs()
   	{
		$fetch_data = $this->make_query_tjs();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array     = array();
			$button = '';
			$btn_cetak = "<a data-toggle='tooltip' title='Cetak Kwitansi' href='dealer/print_receipt/cetak_tjs?id=$rs->id_invoice_tjs'><button class='btn btn-flat btn-xs btn-primary'>Cetak Kwitansi</button></a>";
			$button = $btn_cetak;
			$sub_array[] = "<a data-toggle='tooltip' href='dealer/print_receipt/detail_tjs?id=$rs->id_invoice_tjs'>$rs->id_invoice_tjs</a>";
			$cara_bayar = ucwords(str_replace('_', ' ', $rs->cara_bayar));
			$sub_array[] = $rs->id_spk;
			$sub_array[] = $rs->id_tipe_kendaraan.' | '.$rs->tipe_ahm;
			$sub_array[] = $rs->id_warna.' | '.$rs->warna;
			$sub_array[] = mata_uang_rp($rs->amount);
			$sub_array[] = $cara_bayar;
			$sub_array[] = $rs->note;
			$sub_array[] = $rs->created_at;
			$sub_array[] = $button;
			$data[]      = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_tjs(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   	}

   	function make_query_tjs($no_limit=null)  
   	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_inv_pelunasan','id_spk',null,null,null,null,null,null,null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_invoice_tjs_receipt.created_at DESC';
		$search       = $this->input->post('search')['value'];

		$id_dealer     = $this->m_admin->cari_dealer();
		$searchs      = "WHERE tr_invoice_tjs_receipt.id_dealer=$id_dealer";

		if ($search!='') {
	      $searchs .= " AND (nama_konsumen LIKE '%$search%' 
	          OR no_spk LIKE '%$search%'
	          OR tr_invoice_tjs_receipt.id_invoice_tjs LIKE '%$search%'
	          OR tr_invoice_tjs_receipt.id_spk LIKE '%$search%'
	          OR ms_tipe_kendaraan.tipe_ahm LIKE '%$search%'
	          OR ms_tipe_kendaraan.id_tipe_kendaraan LIKE '%$search%'
	          OR ms_warna.id_warna LIKE '%$search%'
	          OR ms_warna.warna LIKE '%$search%'
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

   		return $this->db->query("SELECT tr_invoice_tjs_receipt.*,tr_spk.id_tipe_kendaraan,tr_spk.id_warna,warna,tipe_ahm
   			FROM tr_invoice_tjs_receipt
   			JOIN tr_spk ON tr_invoice_tjs_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			$searchs $order $limit ");
   	}

   	function get_filtered_data_tjs(){  
		return $this->make_query_tjs('y')->num_rows();  
   	}

	public function add_tjs()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title.' Tanda Jadi Sementara';		
		$data['mode']  = 'insert';
		$data['set']   = "form_tjs";
		$id_dealer     = $this->m_admin->cari_dealer();
		$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				jenis_beli as tipe_pembayaran,id_invoice
			-- (SELECT id_invoice FROM tr_invoice_tjs WHERE id_spk=no_spk) AS id_invoice
			FROM tr_spk 
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				JOIN tr_invoice_tjs ON tr_spk.no_spk=tr_invoice_tjs.id_spk
				WHERE tr_spk.id_dealer=$id_dealer 
				AND tr_spk.no_spk NOT IN(SELECT id_spk FROM tr_invoice_tjs_receipt)
				ORDER BY tr_spk.created_at DESC");
		// $data['spk'] = $this->db->get('tr_spk');
		$this->template($data);	
	}
	
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
	public function save_tjs()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		$id_spk = $data['id_spk']         = $this->input->post('id_spk');
		$data['id_invoice_tjs'] = $this->input->post('id_invoice');
		$data['cara_bayar']    = $this->input->post('cara_bayar');
		$data['note']          = $this->input->post('note');
		$data['amount']     = preg_replace("/[^0-9]/", "", $this->input->post('amount'));
		$data['id_dealer']     = $id_dealer;
		// $data['status'] = 'close';
		$data['created_at']    = $waktu;		
		$data['created_by']    = $login_id;

		// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
		// $email          = array();
		// foreach ($get_notif_grup->result() as $rd) {
		// 	$get_email = $this->db->query("SELECT email FROM ms_karyawan 
		// 			WHERE id_karyawan IN(
		// 				SELECT id_karyawan_dealer FROM ms_user 
		// 				WHERE jenis_user='Main Dealer' 
		// 				AND active=1 
		// 				AND id_user_group=(
		// 					SELECT id_user_group FROM ms_user_group 
		// 					WHERE code='$rd->code_user_group'
		// 				)
		// 			)
		// 	")->result();
		// 	foreach ($get_email as $usr) {
		// 		$email[] = $usr->email;
		// 	}
		// }

		// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 			'id_referensi' => $kode_event,
		// 			'judul'        => "Event Baru Dari Dealer",
		// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 			'status'       =>'baru',
		// 			'created_at'   => $waktu,
		// 			'created_by'   => $login_id
		// 		 ];
		$this->db->trans_begin();
			$this->db->insert('tr_invoice_tjs_receipt',$data);
			// $this->db->update('tr_spk',['status_spk'=>'paid'],['no_spk'=>$id_spk]);
			// $this->db->insert('tr_notifikasi',$notif);
						
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

        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt'>";
      	}
    }

    public function detail_tjs()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title.' Tanda Jadi Sementara';		
		$data['mode']  = 'detail';
		$data['set']   = "form_tjs";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_invoice = $this->input->get('id');
		$row = $this->db->query("SELECT tr_spk.*,id_spk,tr_invoice_tjs_receipt.id_invoice_tjs,tr_invoice_tjs_receipt.created_at,cara_bayar,note,
			CONCAT(tr_spk.id_tipe_kendaraan,' | ',tipe_ahm) as tipe,
			CONCAT(tr_spk.id_warna,' | ',warna) as warna	
			FROM tr_invoice_tjs_receipt
   			JOIN tr_spk ON tr_invoice_tjs_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			WHERE tr_invoice_tjs_receipt.id_invoice_tjs='$id_invoice'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
			$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				jenis_beli as tipe_pembayaran,
			(SELECT id_invoice FROM tr_invoice_tjs WHERE id_spk=no_spk) AS id_invoice
			FROM tr_spk 
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				WHERE tr_spk.id_dealer=$id_dealer ORDER BY tr_spk.created_at DESC");
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt'>";		
		}
		$this->template($data);	
	}

	public function cetak_tjs()
	{
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_invoice = $this->input->get('id');				
  		
  		$get_data = $this->db->query("SELECT tr_spk.*,tr_invoice_tjs_receipt.id_invoice_tjs, tr_invoice_tjs_receipt.created_at,warna,tipe_ahm FROM tr_invoice_tjs_receipt
   			JOIN tr_spk ON tr_invoice_tjs_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
            JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			WHERE id_invoice_tjs='$id_invoice' ");
  		if ($get_data->num_rows()>0) {
  			$row = $data['row'] = $get_data->row();
  			
  			$upd = ['print_ke'=> $row->print_ke+1,
  					'print_at'=> $waktu,
  					'print_by'=> $login_id,
  				   ];
  			$this->db->update('tr_invoice_tjs_receipt',$upd,['id_invoice_tjs'=>$id_invoice]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print_tjs';
			$data['row'] = $row;
        	
        	$html = $this->load->view('dealer/print_receipt_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt'>";		
        }
        
	}

	public function dp()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index_dp";	
		$this->template($data);	
	}

	public function fetch_dp()
   	{
		$fetch_data = $this->make_query_dp();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array     = array();
			$button = '';
			$btn_cetak = "<a data-toggle='tooltip' title='Cetak Kwitansi' href='dealer/print_receipt/cetak_dp?id=$rs->id_invoice_dp'><button class='btn btn-flat btn-xs btn-primary'>Cetak Kwitansi</button></a>";
			$button = $btn_cetak;
			$sub_array[] = "<a data-toggle='tooltip' href='dealer/print_receipt/detail_dp?id=$rs->id_invoice_dp'>$rs->id_invoice_dp</a>";
			$cara_bayar = ucwords(str_replace('_', ' ', $rs->cara_bayar));
			$sub_array[] = $rs->id_spk;
			$sub_array[] = $rs->id_tipe_kendaraan.' | '.$rs->tipe_ahm;
			$sub_array[] = $rs->id_warna.' | '.$rs->warna;
			$sub_array[] = mata_uang_rp($rs->amount_dp);
			$sub_array[] = $cara_bayar;
			$sub_array[] = $rs->note;
			$sub_array[] = $rs->created_at;
			$sub_array[] = $rs->status;
			$sub_array[] = $button;
			$data[]      = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_dp(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   	}

   	function make_query_dp($no_limit=null)  
   	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_invoice_dp','id_spk','id_tipe_kendaraan','id_warna','amount_dp','cara_bayar','note','created_at','status',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_invoice_dp_receipt.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer     = $this->m_admin->cari_dealer();
		$searchs      = "WHERE tr_invoice_dp_receipt.id_dealer=$id_dealer";

		if ($search!='') {
	      $searchs .= " AND (nama_konsumen LIKE '%$search%' 
	          OR no_spk LIKE '%$search%'
	          OR tr_invoice_dp_receipt.id_invoice_dp LIKE '%$search%'
	          OR tr_invoice_dp_receipt.id_spk LIKE '%$search%'
	          OR ms_tipe_kendaraan.tipe_ahm LIKE '%$search%'
	          OR ms_tipe_kendaraan.id_tipe_kendaraan LIKE '%$search%'
	          OR ms_warna.id_warna LIKE '%$search%'
	          OR ms_warna.warna LIKE '%$search%'
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

   		return $this->db->query("SELECT tr_invoice_dp_receipt.*,tr_spk.id_tipe_kendaraan,tr_spk.id_warna,warna,tipe_ahm
   			FROM tr_invoice_dp_receipt
   			JOIN tr_spk ON tr_invoice_dp_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			$searchs $order $limit ");
   	}

   	function get_filtered_data_dp(){  
		return $this->make_query_dp('y')->num_rows();  
   	}

   	public function add_dp()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title.' Down Payment (DP)';		
		$data['mode']  = 'insert';
		$data['set']   = "form_dp";
		$id_dealer     = $this->m_admin->cari_dealer();
		$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				jenis_beli as tipe_pembayaran,id_invoice_dp
			-- (SELECT id_invoice_dp FROM tr_invoice_dp WHERE id_spk=no_spk 	ORDER BY created_at DESC LIMIT 1) AS id_invoice_dp
			FROM tr_spk 
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				JOIN tr_invoice_dp ON tr_invoice_dp.id_spk=tr_spk.no_spk
				WHERE tr_spk.id_dealer=$id_dealer AND jenis_beli='Kredit' 
				AND no_spk NOT IN(SELECT id_spk FROM tr_invoice_dp_receipt)
				ORDER BY tr_spk.created_at DESC");
		// $data['spk'] = $this->db->get('tr_spk');
		$this->template($data);	
	}

	public function save_dp()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		$id_spk = $data['id_spk']         = $this->input->post('id_spk');
		$data['id_invoice_dp'] = $this->input->post('id_invoice_dp');
		$data['cara_bayar']    = $this->input->post('cara_bayar');
		$data['note']          = $this->input->post('note');
		$data['amount_dp']     = preg_replace("/[^0-9]/", "", $this->input->post('amount_dp'));
		$data['id_dealer']     = $id_dealer;
		$data['status'] = 'close';
		$data['created_at']    = $waktu;		
		$data['created_by']    = $login_id;

		// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
		// $email          = array();
		// foreach ($get_notif_grup->result() as $rd) {
		// 	$get_email = $this->db->query("SELECT email FROM ms_karyawan 
		// 			WHERE id_karyawan IN(
		// 				SELECT id_karyawan_dealer FROM ms_user 
		// 				WHERE jenis_user='Main Dealer' 
		// 				AND active=1 
		// 				AND id_user_group=(
		// 					SELECT id_user_group FROM ms_user_group 
		// 					WHERE code='$rd->code_user_group'
		// 				)
		// 			)
		// 	")->result();
		// 	foreach ($get_email as $usr) {
		// 		$email[] = $usr->email;
		// 	}
		// }

		// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 			'id_referensi' => $kode_event,
		// 			'judul'        => "Event Baru Dari Dealer",
		// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 			'status'       =>'baru',
		// 			'created_at'   => $waktu,
		// 			'created_by'   => $login_id
		// 		 ];
		$this->db->trans_begin();
			$this->db->insert('tr_invoice_dp_receipt',$data);
			$this->db->update('tr_spk',['status_spk'=>'paid'],['no_spk'=>$id_spk]);
			// $this->db->insert('tr_notifikasi',$notif);
						
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

        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt/dp'>";
      	}
    }

    public function detail_dp()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title.' Down Payment (DP)';
		$data['mode']  = 'detail';
		$data['set']   = "form_dp";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_invoice = $this->input->get('id');
		$row = $this->db->query("SELECT tr_spk.*,id_spk,tr_invoice_dp_receipt.id_invoice_dp,tr_invoice_dp_receipt.created_at,cara_bayar,note,amount_dp,
			CONCAT(tr_spk.id_tipe_kendaraan,' | ',tipe_ahm) as tipe,
			CONCAT(tr_spk.id_warna,' | ',warna) as warna	
			FROM tr_invoice_dp_receipt
   			JOIN tr_spk ON tr_invoice_dp_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			WHERE tr_invoice_dp_receipt.id_invoice_dp='$id_invoice'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
			$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran,
			(SELECT id_invoice_dp FROM tr_invoice_dp WHERE id_spk=no_spk ORDER BY created_at DESC LIMIT 1) AS id_invoice_dp
			FROM tr_spk 
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				WHERE tr_spk.id_dealer=$id_dealer ORDER BY tr_spk.created_at DESC");
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt/detail_dp'>";		
		}
		$this->template($data);	
	}

	public function cetak_dp()
	{
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_invoice = $this->input->get('id');				
  		
  		$get_data = $this->db->query("SELECT tr_spk.*,tr_invoice_dp_receipt.id_invoice_dp, tr_invoice_dp_receipt.created_at,warna,tipe_ahm FROM tr_invoice_dp_receipt
   			JOIN tr_spk ON tr_invoice_dp_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
            JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			WHERE id_invoice_dp='$id_invoice' ");
  		if ($get_data->num_rows()>0) {
  			$row = $data['row'] = $get_data->row();
  			
  			$upd = ['print_ke'=> $row->print_ke+1,
  					'print_at'=> $waktu,
  					'print_by'=> $login_id,
  				   ];
  			$this->db->update('tr_invoice_dp_receipt',$upd,['id_invoice_dp'=>$id_invoice]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print_dp';
			$data['row'] = $row;
        	
        	$html = $this->load->view('dealer/print_receipt_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt/dp'>";		
        }
        
	}

	public function pelunasan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index_pelunasan";	
		$this->template($data);	
	}

	public function fetch_pelunasan()
   	{
		$fetch_data = $this->make_query_pelunasan();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array     = array();
			$button = '';
			$btn_cetak = "<a data-toggle='tooltip' title='Cetak Kwitansi' href='dealer/print_receipt/cetak_pelunasan?id=$rs->id_inv_pelunasan'><button class='btn btn-flat btn-xs btn-primary'>Cetak Kwitansi</button></a>";
			$button = $btn_cetak;
			$sub_array[] = "<a data-toggle='tooltip' href='dealer/print_receipt/detail_pelunasan?id=$rs->id_inv_pelunasan'>$rs->id_inv_pelunasan</a>";
			$cara_bayar = ucwords(str_replace('_', ' ', $rs->cara_bayar));
			$sub_array[] = $rs->id_spk;
			$sub_array[] = $rs->id_tipe_kendaraan.' | '.$rs->tipe_ahm;
			$sub_array[] = $rs->id_warna.' | '.$rs->warna;
			$sub_array[] = mata_uang_rp($rs->amount_pelunasan);
			$sub_array[] = $cara_bayar;
			$sub_array[] = $rs->note;
			$sub_array[] = $rs->created_at;
			$sub_array[] = $rs->status;
			$sub_array[] = $button;
			$data[]      = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_pelunasan(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);
   	}

   	function make_query_pelunasan($no_limit=null)  
   	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_inv_pelunasan','id_spk','id_tipe_kendaraan','id_warna','amount_pelunasan','cara_bayar','note','created_at','status',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_invoice_pelunasan_receipt.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer     = $this->m_admin->cari_dealer();
		$searchs      = "WHERE tr_invoice_pelunasan_receipt.id_dealer=$id_dealer";

		if ($search!='') {
	      $searchs .= " AND (nama_konsumen LIKE '%$search%' 
	          OR no_spk LIKE '%$search%'
	          OR tr_invoice_pelunasan_receipt.id_inv_pelunasan LIKE '%$search%'
	          OR tr_invoice_pelunasan_receipt.id_spk LIKE '%$search%'
	          OR ms_tipe_kendaraan.tipe_ahm LIKE '%$search%'
	          OR ms_tipe_kendaraan.id_tipe_kendaraan LIKE '%$search%'
	          OR ms_warna.id_warna LIKE '%$search%'
	          OR ms_warna.warna LIKE '%$search%'
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

   		return $this->db->query("SELECT tr_invoice_pelunasan_receipt.*,tr_spk.id_tipe_kendaraan,tr_spk.id_warna,warna,tipe_ahm
   			FROM tr_invoice_pelunasan_receipt
   			JOIN tr_spk ON tr_invoice_pelunasan_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			$searchs $order $limit ");
   	}

   	function get_filtered_data_pelunasan(){  
		return $this->make_query_pelunasan('y')->num_rows();  
   	}

   	public function add_pelunasan()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title.' Invoice Pelunasan';		
		$data['mode']  = 'insert';
		$data['set']   = "form_pelunasan";
		$id_dealer     = $this->m_admin->cari_dealer();
		$data['rek_dealer'] = $this->db->query("SELECT ms_norek_dealer_detail.*,bank FROM ms_norek_dealer_detail
			JOIN ms_norek_dealer ON ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
			JOIN ms_bank ON ms_bank.id_bank=ms_norek_dealer_detail.id_bank
			WHERE id_dealer=$id_dealer");
		$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				jenis_beli as tipe_pembayaran,id_inv_pelunasan
			-- (SELECT id_inv_pelunasan FROM tr_invoice_pelunasan WHERE id_spk=no_spk 	ORDER BY created_at DESC LIMIT 1) AS id_inv_pelunasan
			FROM tr_spk 
				JOIN tr_invoice_pelunasan ON tr_invoice_pelunasan.id_spk=tr_spk.no_spk
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				WHERE tr_spk.id_dealer=$id_dealer AND jenis_beli='Cash'
				AND no_spk NOT IN (SELECT id_spk FROM tr_invoice_pelunasan_receipt)
				 ORDER BY tr_spk.created_at DESC");
		// $data['spk'] = $this->db->get('tr_spk');
		$this->template($data);	
	}

	public function save_pelunasan()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		$id_spk = $data['id_spk']         = $this->input->post('id_spk');
		$data['id_inv_pelunasan'] = $this->input->post('id_inv_pelunasan');
		$cara_bayar = $data['cara_bayar']       = $this->input->post('cara_bayar');
		$data['note']             = $this->input->post('note');
		
		$data['id_dealer']        = $id_dealer;
		$data['status']           = 'close';
		$data['created_at']       = $waktu;		
		$data['created_by']       = $login_id;

		// $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>11])->row();
		// $get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>11]);
		// $email          = array();
		// foreach ($get_notif_grup->result() as $rd) {
		// 	$get_email = $this->db->query("SELECT email FROM ms_karyawan 
		// 			WHERE id_karyawan IN(
		// 				SELECT id_karyawan_dealer FROM ms_user 
		// 				WHERE jenis_user='Main Dealer' 
		// 				AND active=1 
		// 				AND id_user_group=(
		// 					SELECT id_user_group FROM ms_user_group 
		// 					WHERE code='$rd->code_user_group'
		// 				)
		// 			)
		// 	")->result();
		// 	foreach ($get_email as $usr) {
		// 		$email[] = $usr->email;
		// 	}
		// }

		// $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
		// 			'id_referensi' => $kode_event,
		// 			'judul'        => "Event Baru Dari Dealer",
		// 			'pesan'        => "Silahkan lakukan approve/reject Event $kode_event yang telah diinisiasi oleh Dealer.",
		// 			'link'         => $ktg_notif->link.'/detail?nt=y&id='.$kode_event,
		// 			'status'       =>'baru',
		// 			'created_at'   => $waktu,
		// 			'created_by'   => $login_id
		// 		 ];
		$this->db->trans_begin();
			$this->db->insert('tr_invoice_pelunasan_receipt',$data);
			$id_receipt_pelunasan = $this->db->insert_id();

			if ($cara_bayar=='transfer' || $cara_bayar=='kartu_kredit') {
				$tgl_transfer           = $this->input->post('tgl_transfer');
				$nilai                  = $this->input->post('nilai');
				$id_norek_dealer_detail = $this->input->post('id_norek_dealer_detail');
				for ($i = 0; $i < count($tgl_transfer); $i++) {
					$dtl[] = ['id_receipt_pelunasan'=>$id_receipt_pelunasan,
						'tgl_transfer'           => $tgl_transfer[$i],
						'nilai'                  => $nilai[$i],
						'id_norek_dealer_detail' => $id_norek_dealer_detail[$i]
					];
				}
			}

			if ($cara_bayar=='cek_giro') {
				$tgl_cek_giro           = $this->input->post('tgl_cek_giro');
				$no_cek_giro           = $this->input->post('no_cek_giro');
				$nilai                  = $this->input->post('nilai');
				$bank_konsumen          = $this->input->post('bank_konsumen');
				$id_norek_dealer_detail = $this->input->post('id_norek_dealer_detail');
				for ($i = 0; $i < count($tgl_cek_giro); $i++) {
					$dtl[] = ['id_receipt_pelunasan'=>$id_receipt_pelunasan,
						'no_cek_giro'           => $no_cek_giro[$i],
						'tgl_cek_giro'           => $tgl_cek_giro[$i],
						'nilai'                  => $nilai[$i],
						'bank_konsumen'          => $bank_konsumen[$i],
						'id_norek_dealer_detail' => $id_norek_dealer_detail[$i],
					];
				}
			}
			if (isset($dtl)) {
				$this->db->insert_batch('tr_invoice_pelunasan_receipt_detail',$dtl);
			}
			$this->db->update('tr_spk',['status_spk'=>'paid'],['no_spk'=>$id_spk]);
			// $this->db->insert('tr_notifikasi',$notif);
						
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

        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt/pelunasan'>";
      	}
    }

    public function detail_pelunasan()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title.' Invoice Pelunasan';		
		$data['mode']  = 'detail';
		$data['set']   = "form_pelunasan";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_invoice = $this->input->get('id');
		$row = $this->db->query("SELECT tr_spk.*,id_spk,tr_invoice_pelunasan_receipt.id_inv_pelunasan,tr_invoice_pelunasan_receipt.created_at,cara_bayar,note,amount_pelunasan,
			CONCAT(tr_spk.id_tipe_kendaraan,' | ',tipe_ahm) as tipe,
			CONCAT(tr_spk.id_warna,' | ',warna) as warna,id_receipt_pelunasan	
			FROM tr_invoice_pelunasan_receipt
   			JOIN tr_spk ON tr_invoice_pelunasan_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			WHERE tr_invoice_pelunasan_receipt.id_inv_pelunasan='$id_invoice'");
		if ($row->num_rows()>0) {
			$row=$data['row'] = $row->row();
			$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran,
			(SELECT id_inv_pelunasan FROM tr_invoice_pelunasan WHERE id_spk=no_spk ORDER BY created_at DESC LIMIT 1) AS id_inv_pelunasan
			FROM tr_spk 
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				WHERE tr_spk.id_dealer=$id_dealer ORDER BY tr_spk.created_at DESC");
			
			$data['rek_dealer'] = $this->db->query("SELECT ms_norek_dealer_detail.*,bank FROM ms_norek_dealer_detail
			JOIN ms_norek_dealer ON ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
			JOIN ms_bank ON ms_bank.id_bank=ms_norek_dealer_detail.id_bank
			WHERE id_dealer=$id_dealer");

			$detail_bayar = $this->db->query("SELECT tiprd.*,bank,ndd.* 
				FROM tr_invoice_pelunasan_receipt_detail AS tiprd
				JOIN ms_norek_dealer_detail AS ndd ON ndd.id_norek_dealer_detail=tiprd.id_norek_dealer_detail
				JOIN ms_bank ON ms_bank.id_bank=ndd.id_bank
				WHERE id_receipt_pelunasan=$row->id_receipt_pelunasan")->result();
			if ($row->cara_bayar=='cek_giro') {
				$data['cek_giros'] = $detail_bayar;
			}
			if ($row->cara_bayar=='transfer' || $row->cara_bayar=='kartu_kredit') {
				$data['transfers'] = $detail_bayar;
			}
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt/detail_pelunasan'>";		
		}
		$this->template($data);	
	}

	public function cetak_pelunasan()
	{
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_invoice = $this->input->get('id');				
  		
  		$get_data = $this->db->query("SELECT tr_spk.*,tr_invoice_pelunasan_receipt.id_inv_pelunasan, tr_invoice_pelunasan_receipt.created_at,warna,tipe_ahm,print_ke,id_receipt_pelunasan FROM tr_invoice_pelunasan_receipt
   			JOIN tr_spk ON tr_invoice_pelunasan_receipt.id_spk=tr_spk.no_spk
   			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
            JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
   			WHERE id_inv_pelunasan='$id_invoice' ");
  		if ($get_data->num_rows()>0) {
  			$row = $data['row'] = $get_data->row();
  			
  			$upd = ['print_ke'=> $row->print_ke+1,
  					'print_at'=> $waktu,
  					'print_by'=> $login_id,
  				   ];
  			$this->db->update('tr_invoice_pelunasan_receipt',$upd,['id_inv_pelunasan'=>$id_invoice]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print_pelunasan';
			$data['row'] = $row;
        	$detail_bayar = $this->db->query("SELECT tiprd.*,bank,ndd.* 
				FROM tr_invoice_pelunasan_receipt_detail AS tiprd
				JOIN ms_norek_dealer_detail AS ndd ON ndd.id_norek_dealer_detail=tiprd.id_norek_dealer_detail
				JOIN ms_bank ON ms_bank.id_bank=ndd.id_bank
				WHERE id_receipt_pelunasan=$row->id_receipt_pelunasan")->result();
			if ($row->cara_bayar=='cek_giro') {
				$data['cek_giros'] = $detail_bayar;
			}
			if ($row->cara_bayar=='transfer' || $row->cara_bayar=='kartu_kredit') {
				$data['transfers'] = $detail_bayar;
			}

        	$html = $this->load->view('dealer/print_receipt_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/print_receipt/pelunasan'>";		
        }
        
	}
}