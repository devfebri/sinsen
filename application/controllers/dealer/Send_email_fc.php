<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_email_fc extends CI_Controller {

	var $folder = "dealer";
	var $page   = "send_email_fc";
	var $title  = "Send Email To FC";

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

	
	public function get_id_invoice()
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
		$get_data  = $this->db->query("SELECT * FROM tr_invoice_pelunasan
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$id_inv_pelunasan = substr($row->id_inv_pelunasan, -5);
				$new_kode   = 'FP/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.05d",$id_inv_pelunasan+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('tr_invoice_pelunasan',['id_inv_pelunasan'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -5);
						$new_kode = 'FP/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.sprintf("%'.05d",$id_inv_pelunasan+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = 'FP/'.$id_sumber.'/'.$th_kecil.'/'.$bln.'/'.'00001';
	   		}
   		return strtoupper($new_kode);
	}	

	public function send_email()
	{
		$id = $this->input->get('id');
		$jenis           = $this->input->get('jn');
		$id_fc           = $this->input->get('fc');
		$id_dealer       = $this->m_admin->cari_dealer();
		$waktu           = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id        = $this->session->userdata('id_user'); 

		$fc                         = $this->db->get_where('ms_finance_company',['id_finance_company'=>$id_fc])->row();
		
		
		$data['id_finance_company'] = $id_fc;
		$data['created_at']         = $waktu;
		$data['created_by']         = $login_id;
		$data['jenis']              = $jenis;

		if ($jenis=='bpkb') {
			$bpkp = $this->db->query("SELECT tpbd.*,tr_spk.id_finance_company,finance_company,ms_finance_company.email,COUNT(no_serah_bpkb) AS tot_berkas
                  FROM tr_penyerahan_bpkb_detail AS tpbd
                  JOIN tr_sales_order ON tpbd.no_mesin=tr_sales_order.no_mesin
                  JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
                  JOIN ms_finance_company ON tr_spk.id_finance_company=ms_finance_company.id_finance_company
                  WHERE status_nosin='terima' AND tr_spk.id_dealer=$id_dealer
                  AND tr_spk.id_finance_company='$id_fc' AND tpbd.no_serah_bpkb='$id'
                ")->row();	

			$data['no_serah_bpkb'] = $bpkp->no_serah_bpkb;
			$data['tot_berkas']    = $bpkp->tot_berkas;
		}

		elseif ($jenis=='srut') {
			$bpkp = $this->db->query("SELECT psd.*,tr_spk.id_finance_company,finance_company,ms_finance_company.email,COUNT(ps.no_serah_terima) AS tot_berkas FROM tr_penyerahan_srut_detail AS psd
                  JOIN tr_penyerahan_srut AS ps ON ps.no_serah_terima=psd.no_serah_terima
                  JOIN tr_sales_order ON psd.no_mesin=tr_sales_order.no_mesin
                  JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
                  JOIN ms_finance_company ON tr_spk.id_finance_company=ms_finance_company.id_finance_company
                  WHERE ps.id_dealer=$id_dealer
                  AND tr_spk.id_finance_company='$id_fc' AND psd.no_serah_terima='$id'
                ")->row();	

			$data['no_serah_srut'] = $bpkp->no_serah_terima;
			$data['tot_berkas']    = $bpkp->tot_berkas;
		}

		$send_email = $this->email($fc->email,$jenis,$data);
		if ($send_email=='sukses') {
			$pesan = "Email Berhasil Dikirim !";
			$tipe  = 'success';
			$this->db->insert('tr_send_email_fc',$data);
		}else{
			$pesan = 'Email Gagal Dikirim !';
			$tipe  = 'danger';
		}

		$_SESSION['pesan'] 	= $pesan;
		$_SESSION['tipe'] 	= $tipe;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/send_email_fc'>";
	}

	public function email($email_to,$jenis,$detail) { 
		$from = $this->db->get_where('ms_email_md',['email_for'=>'notification'])->row(); 
		$config = Array(
          'protocol' => 'smtp',
          'smtp_host' => 'ssl://mail.sinarsentosaprimatama.com',
          'smtp_port' => 465,
          'smtp_user' => $from->email,
          'smtp_pass' => $from->pass,
          'mailtype'  => 'html', 
          'charset'   => 'iso-8859-1');
        // $config = config_email($from_email);

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");   

		$this->email->from($from->email, 'SINARSENTOSA'); 
		$this->email->to($email_to);
		$this->email->subject('[SINARSENTOSA] Informasi Finance Company'); 

		$data['set']         = 'selisih';
		$id_finance_company  = $detail['id_finance_company'];
		$id_dealer           = $this->m_admin->cari_dealer();
		
		$data['finco']       = $this->db->query("SELECT * FROm ms_finance_company WHERE id_finance_company='$id_finance_company'")->row()->finance_company;
		$data['tgl']         = date('Y-m-d');
		$data['tot_berkas']  = $detail['tot_berkas'];
		$data['nama_dealer'] = $this->db->query("SELECT nama_dealer FROM ms_dealer WHERE id_dealer=$id_dealer")->row()->nama_dealer;
		$data['jenis']       = $jenis;
		$file_logo           = base_url('assets/panel/images/logo_sinsen.jpg');
		$data['logo']        = $file_logo;
		// $this->load->view('dealer/konfirmasi_pu_email',$data);
		$this->email->message($this->load->view('dealer/send_email_fc_email', $data, true)); 

         //Send mail 
         if($this->email->send()){
			return 'sukses';	
         }else {
			return 'gagal';
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