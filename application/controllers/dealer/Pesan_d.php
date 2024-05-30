<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan_d extends CI_Controller {

	var $tables = "ms_pesan";	
	var $folder = "dealer";
	var $page   = "pesan_d";
	var $title  = "Master Pesan";

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
		// $this->load->library('mpdf_l');
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
			$btn_del = "<a data-toggle='tooltip' onclick=\"return confirm('Are you sure to delete this data ?')\" title='Delete' href='dealer/pesan_d/delete?id=$rs->id_pesan'><button class='btn btn-flat btn-sm btn-danger'><i class='fa fa-trash'></i></button></a>";
			$button = $btn_del;
			$sub_array[] = "<a data-toggle='tooltip' href='dealer/pesan_d/detail?id=$rs->id_pesan'>$rs->id_pesan</a>";
			$sub_array[] = $rs->tipe_pesan;
			$sub_array[] = $rs->konten;
			$sub_array[] = $rs->start_date;
			$sub_array[] = $rs->end_date;
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
		$order        = 'ORDER BY created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE id_dealer=$id_dealer";
		
		if ($search!='') {
	      $searchs .= "AND (nama_konsumen LIKE '%$search%' 
	          OR nama_kelompok LIKE '%$search%'
	          OR tgl_bayar LIKE '%$search%')
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT * FROM ms_pesan $searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	}  

	public function add()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'insert';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$data['spk'] = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
			(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
				case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran	
			FROM tr_spk 
				JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				WHERE tr_spk.id_dealer=$id_dealer ORDER BY tr_spk.created_at DESC");
		// $data['spk'] = $this->db->get('tr_spk');
		$this->template($data);	
	}
	
	public function get_id_pesan()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$id_dealer = $this->m_admin->cari_dealer();
		// $id_sumber='E20';
		// if ($id_dealer!=null) {
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$id_sumber = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM ms_pesan
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$id_pesan = substr($row->id_pesan, -5);
				$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('ms_pesan',['id_pesan'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -5);
						$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.sprintf("%'.05d",$id_pesan+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = $id_sumber.'/'.$th_kecil.'/'.$bln.'/MSG/'.'00001';
	   		}
   		return strtoupper($new_kode);
	}	

	public function save()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
		$data['id_pesan']   = $this->get_id_pesan();
		$data['tipe_pesan'] = $this->input->post('tipe_pesan');
		$data['konten']     = $this->input->post('konten');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date']   = $this->input->post('end_date');
		$data['id_dealer']  = $id_dealer;
		$data['created_at'] = $waktu;		
		$data['created_by'] = $login_id;

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
			$this->db->insert('ms_pesan',$data);
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pesan_d'>";
      	}
    }

    public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= 'id_pesan';
		$id 			= $this->input->get('id');		
		$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
		$this->db->trans_commit();			
		$result = 'Success';									

		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pesan_d'>";
	}

	public function cetak()
	{
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_invoice = $this->input->get('id');				
  		
  		$get_data = $this->db->query("SELECT tr_spk.*,tr_invoice_dp.id_invoice_dp,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,tr_invoice_dp.created_at FROM tr_invoice_dp
   			JOIN tr_spk ON tr_invoice_dp.id_spk=tr_spk.no_spk
   			WHERE id_invoice_dp='$id_invoice' ");
  		if ($get_data->num_rows()>0) {
  			$row = $data['row'] = $get_data->row();
  			
  			$upd = ['print_ke'=> $row->print_ke+1,
  					'print_at'=> $waktu,
  					'print_by'=> $login_id,
  				   ];
  			$this->db->update('tr_invoice_dp',$upd,['id_invoice_dp'=>$id_invoice]);
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print';
			$data['row'] = $row;
        	
        	$html = $this->load->view('dealer/pesan_d_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pesan_d'>";		
        }
        
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_pesan = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_pesan WHERE id_pesan='$id_pesan'");
		if ($row->num_rows()>0) {
			$data['row'] = $row->row();
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pesan_d'>";		
		}
		$this->template($data);	
	}

	// public function make_pesan()
	// {
	// 	$dealer= $this->db->query("SELECT * FROM ms_dealer WHERE h1=1 AND active=1");
	// 	$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$tgl       = gmdate("y-m-d", time()+60*60*7);
	// 	foreach ($dealer->result() as $dl) {
	// 		// Indent
	// 		$id_pesan_idt = $dl->kode_dealer_md.'/19/08/MSG/00001';
	// 		$ins_pesan[] = ['id_pesan'=>$id_pesan_idt,
	// 					  'konten'=>'Pelanggan terhormat, saat ini kami informasikan bahwa Kode Indent : [KodeIndent] atas unit [TipeUnit], [Warna] yang anda pesan sudah tersedia. Selanjutnya bapak/ibu diharapkan untuk datang ke [NamaDealer] untuk transaksi lebih lanjut.',
	// 					  'tipe_pesan'=>'Reminder Indent',
	// 					  'start_date'=>'2019-08-01',
	// 					  'end_date'=>'2019-08-31',
	// 					  'id_dealer'=>$dl->id_dealer,
	// 					  'created_at'=> $waktu,
	// 					  'created_by'=>8
	// 					];
	// 		// Reminder STNK (Kredit)
	// 		$id_pesan_sk = $dl->kode_dealer_md.'/19/08/MSG/00002';
	// 		$ins_pesan[] = ['id_pesan'=>$id_pesan_sk,
	// 					  'konten'=>'Pelanggan yang terhormat, STNK/ Plat No motor anda sudah tersedia. Silahkan datang ke [NamaDealer] untuk pengambilannya. Terimakasih',
	// 					  'tipe_pesan'=>'Reminder STNK (Kredit)',
	// 					  'start_date'=>'2019-08-01',
	// 					  'end_date'=>'2019-08-31',
	// 					  'id_dealer'=>$dl->id_dealer,
	// 					  'created_at'=> $waktu,
	// 					  'created_by'=>8
	// 					];
	// 		// Reminder STNK (Cash)
	// 		$id_pesan_sc = $dl->kode_dealer_md.'/19/08/MSG/00003';
	// 		$ins_pesan[] = ['id_pesan'=>$id_pesan_sc,
	// 					  'konten'=>'Pelanggan yang terhormat, STNK/BPKB/Plat No /SRUT anda sudah tersedia. Silahkan datang ke [NamaDealer] untuk pengambilannya. Terimakasih',
	// 					  'tipe_pesan'=>'Reminder STNK (Cash)',
	// 					  'start_date'=>'2019-08-01',
	// 					  'end_date'=>'2019-08-31',
	// 					  'id_dealer'=>$dl->id_dealer,
	// 					  'created_at'=> $waktu,
	// 					  'created_by'=>8
	// 					];

	// 		// Reminder BASTK
	// 		$id_pesan_ba = $dl->kode_dealer_md.'/19/08/MSG/00004';
	// 		$ins_pesan[] = ['id_pesan'=>$id_pesan_ba,
	// 					  'konten'=>'Pelanggan yang terhormat, akan dilakukan pengiriman unit pada tanggal : [TanggalPengirimanUnit], Waktu : [WaktuPengirimanUnit]',
	// 					  'tipe_pesan'=>'Reminder BASTK',
	// 					  'start_date'=>'2019-08-01',
	// 					  'end_date'=>'2019-08-31',
	// 					  'id_dealer'=>$dl->id_dealer,
	// 					  'created_at'=> $waktu,
	// 					  'created_by'=>8
	// 					];
	// 		// Ucapan Selamat Ulang Tahun
	// 		$id_pesan_ut = $dl->kode_dealer_md.'/19/08/MSG/00005';
	// 		$ins_pesan[] = ['id_pesan'=>$id_pesan_ut,
	// 					  'konten'=>'[NamaDealer]. Selamat ulang tahun untuk pelanggan kami [NamaCustomer].',
	// 					  'tipe_pesan'=>'Ucapan Selamat Ulang Tahun',
	// 					  'start_date'=>'2019-08-01',
	// 					  'end_date'=>'2019-08-31',
	// 					  'id_dealer'=>$dl->id_dealer,
	// 					  'created_at'=> $waktu,
	// 					  'created_by'=>8
	// 					];
	// 		// Ucapan Selamat Tahun Baru Masehi
	// 		$id_pesan_ = $dl->kode_dealer_md.'/19/08/MSG/00006';
	// 		$ins_pesan[] = ['id_pesan'=>$id_pesan_,
	// 					  'konten'=>'Selamat Tahun Baru [tahun] untuk pelanggan kami [NamaCustomer]',
	// 					  'tipe_pesan'=>'Ucapan Selamat Tahun Baru Masehi',
	// 					  'start_date'=>'2019-08-01',
	// 					  'end_date'=>'2019-08-31',
	// 					  'id_dealer'=>$dl->id_dealer,
	// 					  'created_at'=> $waktu,
	// 					  'created_by'=>8
	// 					];
	// 	}
	// 	$this->db->insert_batch('ms_pesan',$ins_pesan);
	// 	echo $this->db->affected_rows();
	// }
}