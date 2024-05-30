<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indent extends CI_Controller {

    var $tables =   "tr_po_dealer_indent";	
		var $folder =   "h1";
		var $page		=		"indent";
    var $pk     =   "id_spk";
    var $title  =   "Indent Fullfillment";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('csvimport');
		$this->load->library('upload');

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
		$data['set']	= "view";
		/*
		$data['dt_indent'] = $this->db->query("SELECT * FROM tr_po_dealer_indent INNER JOIN ms_tipe_kendaraan 
								ON tr_po_dealer_indent.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
								INNER JOIN ms_warna ON tr_po_dealer_indent.id_warna = ms_warna.id_warna 
								inner join ms_dealer on tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer
								LEFT JOIN tr_spk ON tr_po_dealer_indent.id_spk = tr_spk.no_spk
								WHERE (tr_po_dealer_indent.status = 'requested' OR tr_po_dealer_indent.status = 'proses') ORDER BY tr_po_dealer_indent.tgl DESC");						
				*/
		$data['dt_indent'] = $this->db->query("
select tr_po_dealer_indent.tgl, tr_po_dealer_indent.id_spk, tr_po_dealer_indent.id_indent, tr_po_dealer_indent.nama_konsumen , tr_po_dealer_indent.no_ktp , tr_po_dealer_indent.id_tipe_kendaraan , tr_po_dealer_indent.id_warna ,
ms_tipe_kendaraan.tipe_ahm , ms_warna.warna, ifnull(tr_spk.tanda_jadi,0) as tanda_jadi , ms_dealer.nama_dealer, tr_po_dealer_indent.status, tr_po_dealer_indent.send_ahm, x.id_kwitansi,
(case when x.id_kwitansi is not null then 'Sudah Lunas' else 'Belum Lunas' end) as status_bayar
FROM tr_po_dealer_indent 
INNER JOIN ms_tipe_kendaraan ON tr_po_dealer_indent.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
INNER JOIN ms_warna ON tr_po_dealer_indent.id_warna = ms_warna.id_warna 
inner join ms_dealer on tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer
LEFT JOIN tr_spk ON tr_po_dealer_indent.id_spk = tr_spk.no_spk
left join (
	select a.id_kwitansi, a.amount, b.id_spk from tr_h1_dealer_invoice_receipt a
	join tr_invoice_tjs b on a.no_spk =b.id_spk 
	where jenis_invoice ='tjs' 
) x on  x.id_spk  = tr_spk.no_spk 
WHERE (tr_po_dealer_indent.status = 'requested' OR tr_po_dealer_indent.status = 'proses') and  x.id_kwitansi is not NULL ORDER BY tr_po_dealer_indent.tgl DESC
		");
		

		
		$this->template($data);	
	}

	public function konfirmasi()
	{
		$id_tipe = $this->input->get('id_tipe');
		$id_warna = $this->input->get('id_warna');
		$id_indent = $this->input->get('id_indent');
		// cek di UTD
		$cek_utd = $this->db->get_where('ms_utd', array('kode_type_actual'=>$id_tipe,'kode_warna_actual'=>$id_warna));
		if ($cek_utd->num_rows() == 0) {
			?>
			<script type="text/javascript">
				alert("Kode Type ini tidak tersedia untuk indent !");
				window.location="<?php echo base_url() ?>h1/indent";
			</script>
			<?php
			exit();
		} else {
			$cek = $this->db->get_where('ms_master_lead_detail', array('id_tipe_kendaraan'=>$id_tipe,'warna'=>$id_warna,'active'=>1));
			if ($cek->num_rows() == 0) {
				?>
				<script type="text/javascript">
					alert("Lead Time Belum di SET !");
					window.location="<?php echo base_url() ?>h1/indent";
				</script>
				<?php
				exit();
			} 
		}

		date_default_timezone_set('Asia/Jakarta');
		// update send_ahm
		$this->db->where('id_indent', $id_indent);
		$update = $this->db->update('tr_po_dealer_indent', array('send_ahm'=>'1','status_kirim'=>'1', 'date_konfirmasi'=>date('Y-m-d H:i:s')));
		if ($update) {
			?>
			<script type="text/javascript">
				alert("Berhasil di konfirmasi !");
				window.location="<?php echo base_url() ?>h1/indent";
			</script>
			<?php
		}
	}

	public function detail()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_indent'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_tipe'] 	= $this->m_admin->getSortCond("ms_tipe_kendaraan","id_tipe_kendaraan","ASC");			
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "detail";									
		$this->template($data);	
	}
	public function history()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "history";			
		$this->template($data);	
	}
	public function approve()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id					= $this->input->get("id");		
		$data['status'] 					= "approved";
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/indent'>";
	}
	public function fullfilled()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id					= $this->input->get("id");		
		$data['status'] 					= "proses";
		$data2['status'] 					= "approved";
		$data2['updated_at'] = $data['updated_at']				= $waktu;		
		$data2['updated_at'] = $data['updated_by']				= $login_id;		
		//$cek = $this->save_do_real($id);
		$cek = "aman";
		if($cek=='aman'){
			$this->m_admin->update($tabel,$data,$pk,$id);		
			$this->m_admin->update("tr_po_dealer",$data2,"po_from",$id);
			$_SESSION['pesan'] 	= "Data has been fullfilled successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/indent'>";
		}else{
			$_SESSION['pesan'] 	= "Failed";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/indent'>";
		}
	}
	public function cari_id_real(){				
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");		
		$kd = "ID-";
		$pr_num = $this->db->query("SELECT * FROM tr_do_po WHERE source = 'po_indent' ORDER BY no_do DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_do)-5;
			$id 	= substr($row->no_do,$pan,5)+1;	
			if($id < 10){
					$kode1 = $kd.$th.$bln."0000".$id;          
      }elseif($id>9 && $id<=99){
					$kode1 = $kd.$th.$bln."000".$id;                    
      }elseif($id>99 && $id<=999){
					$kode1 = $kd.$th.$bln."00".$id;          					          
      }elseif($id>999){
					$kode1 = $kd.$th.$bln."0".$id;                    
      }
			$kode = $kode1;
		}else{
			$kode = $kd.$th.$bln."00001";
		}				 	
		return $kode;
	}
	public function save_do_real($id)
	{
		$waktu             = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl               = gmdate("y-m-d", time()+60*60*7);
		$login_id          = $this->session->userdata('id_user');
		$tabel             = "tr_do_po";				
		$po = $this->m_admin->getByID("tr_po_dealer","po_from",$id);
		if($po->num_rows() > 0){
			$row = $po->row();
			$data['source'] 	= $source = "po_indent";			
			$data['no_do']	 	= $no_do_suggest = $no_do = $this->cari_id_real();						
			$data['no_po']   = $no_po            = $row->id_po;
			$data['id_gudang'] = 'GD1';					
			$data['id_dealer']		= $id_dealer = $row->id_dealer;
			$data['pengambilan'] 	= "Diambil";			
			$data['tgl_do'] 			= $tgl;			
			$data['status'] 			= "input";			
			$data['created_at']		= $waktu;		
			$data['created_by']		= $login_id;	
			

			return 'aman';
		}else{
			return 'tidak aman';
		}
	}
	public function save_do()
	{		
		$waktu             = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl               = gmdate("y-m-d", time()+60*60*7);
		$login_id          = $this->session->userdata('id_user');
		$tabel             = "tr_do_po";		
		//$source          = "po_inde"
		$source            = $this->input->post('jenis_do');	
		$source_real       = $this->input->post('jenis_do_real');	
		$no_do_suggest     = $this->cari_id_real($source_real);		
		$no_do             = $no_do_suggest;
		$data['source']    = $this->input->post('jenis_do_real');	
		$data['no_do']     = $no_do;		
		$no_po             = $this->input->post('no_po');
		$data['ket']       = $this->input->post('ket');	
		$data['id_gudang'] = $this->input->post('id_gudang');	
		$t = $this->db->query("SELECT * FROM tr_po_dealer_indent INNER JOIN tr_do_indent_detail ON tr_po_dealer_indent.id_indent=tr_do_indent_detail.id_indent 
					WHERE tr_do_indent_detail.no_do = '$no_do'");		
		$id_dealer 	= $this->input->post('id_dealer');			
		$data['id_dealer']		= $id_dealer;
		$data['pengambilan'] 	= $this->input->post('pengambilan');	
		$tgl_do 							= $this->input->post('tanggal');	
		$data['tgl_do'] 			= $this->input->post('tanggal');	
		$data['no_po'] 				= $this->input->post('no_po');	
		$data['status'] 			= "input";			
		$data['created_at']		= $waktu;		
		$data['created_by']		= $login_id;	
			
		
		if($source_real == 'po_reguler' OR $source_real == 'po_additional' OR $source_real == 'po_indent'){
			$id_item	= $this->input->post("id_item");
			$jumlah	= $this->input->post("jumlah");			
      

      ///cari diskoooon	      
			$isi_do = 0;
			for ($i=1; $i <=$jumlah ; $i++) { 
				$id_item = $this->input->post('id_item_'.$i);
				$bulan  = explode('-', $tgl_do);
	      $bl=$bulan[1];$th=$bulan[0];

	      $tipe = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna FROM ms_tipe_kendaraan 
	      							INNER JOIN ms_item ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
	      							INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
	      							WHERE ms_item.id_item = '$id_item'")->row();

	      $cek_quo = $this->db->query("SELECT * FROM tr_quotation INNER JOIN tr_quotation_bulan ON tr_quotation.no_quotation = tr_quotation_bulan.no_quotation
	        INNER JOIN tr_quotation_tipe ON tr_quotation.no_quotation = tr_quotation_tipe.no_quotation
	        WHERE tr_quotation_bulan.bulan = '$bl' AND tr_quotation_bulan.tahun = '$th'
	        AND tr_quotation_tipe.id_tipe_kendaraan = '$tipe->id_tipe_kendaraan'");
	      if($cek_quo->num_rows() > 0){
	        $d = $cek_quo->row();
	        $disc1 = $d->nilai;
	      }else{
	        $disc1 = 0;
	      }

	      $cek_scp = $this->db->query("SELECT ahm_kredit+md_kredit AS nilai FROM tr_sales_program INNER JOIN tr_sales_program_dealer ON tr_sales_program.id_program_md = tr_sales_program_dealer.id_program_md
	        INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program
	        INNER JOIN tr_sales_program_tipe ON tr_sales_program.id_program_md = tr_sales_program_tipe.id_program_md                      
	        WHERE '$tgl_do' BETWEEN tr_sales_program.periode_awal AND tr_sales_program.periode_akhir
	        AND ms_jenis_sales_program.jenis_sales_program = 'SCP' AND tr_sales_program_dealer.id_dealer = '$id_dealer'
	        AND tr_sales_program_tipe.metode_pembayaran = 'Bayar Didepan(Potong DO)'
	        AND tr_sales_program_tipe.id_tipe_kendaraan = '$tipe->id_tipe_kendaraan' AND FIND_IN_SET('$tipe->id_warna',tr_sales_program_tipe.id_warna)");
	      $n=0;
	      foreach ($cek_scp->result() as $isi) {
	        $n = $n + $isi->nilai;
	      }

	      $disc = $disc1 + $n;

				$da[$i]['no_do'] = $no_do;
				$da[$i]['id_item'] = $this->input->post('id_item_'.$i);
				$da[$i]['qty_do'] = $this->input->post('qty_do_'.$i);
				$da[$i]['qty_on_hand'] = $this->input->post('qty_on_'.$i);
				$da[$i]['qty_rfs'] = $this->input->post('qty_rfs_'.$i);
				$da[$i]['harga'] = $this->input->post('harga_'.$i);			
				$da[$i]['disc'] = $disc;

				$isi_do = $isi_do + $this->input->post('qty_do_'.$i);
			}	


			if($isi_do == 0){
				$_SESSION['pesan'] 	= "Qty DO tidak boleh 0";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['no_do'] 	= $this->input->post('no_po');	
				echo "<script>history.go(-1)</script>";								
				exit;
			}

			$this->m_admin->insert($tabel,$data);
			$testb = $this->db->insert_batch('tr_do_po_detail', $da);		
			$cek_do_qty = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_po = '$no_po'");
			foreach ($cek_do_qty->result() as $ku) {
				$cek_po_qty = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE tr_po_dealer_detail.id_po = '$no_po' AND id_item = '$ku->id_item'");
				if($ku->source == 'po_reguler'){
					if($cek_po_qty->num_rows() > 0){
						$td = $cek_po_qty->row();
						$isi_po_qty = $td->qty_po_fix;
					}else{
						$isi_po_qty = 0;
					}
				}elseif($ku->source == 'po_additional' OR $ku->source == 'po_indent'){	
					if($cek_po_qty->num_rows() > 0){
						$td = $cek_po_qty->row();
						$isi_po_qty = $td->qty_order;
					}else{
						$isi_po_qty = 0;
					}
				}			
				$cek_tot_do = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS tot FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do
	      	WHERE tr_do_po.no_po = '$no_po' AND tr_do_po_detail.id_item = '$ku->id_item'");
	      if($cek_tot_do->num_rows() > 0){
	      	$sisa_qty_do = $cek_tot_do->row();
	      	if($sisa_qty_do->tot == $isi_po_qty){
	        	$id_item 	= $ku->id_item;
						$cek	= 'done';
						$this->db->query("UPDATE tr_po_dealer_detail SET cek_do = '$cek' WHERE id_po = '$no_po' AND id_item = '$id_item'");		
	      	}
	      }			
			}
			

			$cek_do_isi = $this->db->query("SELECT SUM(qty_do) as jum_do FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_po = '$no_po'")->row();
			$cek_po_isi = $this->db->query("SELECT SUM(qty_po_fix) as jum_po FROM tr_po_dealer_detail WHERE tr_po_dealer_detail.id_po = '$no_po'")->row();
			$jum_do = $cek_do_isi->jum_do;
			$jum_po = $cek_po_isi->jum_po;
			if($jum_po == $jum_do){
				$ubah['status'] = "waiting";
				$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$no_po);			
			}							

		//}elseif($source == 'po_indent'){
		}else{
			$amb_do = $this->db->query("SELECT * FROM tr_do_po_detail WHERE no_do = '$no_do'");
			if($amb_do->num_rows() > 0){
				$t = $this->db->query("SELECT * FROM tr_po_dealer_indent INNER JOIN tr_do_indent_detail ON tr_po_dealer_indent.id_indent=tr_do_indent_detail.id_indent 
						WHERE tr_do_indent_detail.no_do = '$no_do'")->row();    
				$ds['no_do'] 				= $no_do;				
				$ds['id_gudang'] 		= $this->input->post('id_gudang');	
				$ds['source'] 			= $this->input->post('jenis_do');	
				$ds['pengambilan'] 	= $this->input->post('pengambilan');	
				$ds['ket'] 					= $this->input->post('ket');	
				$ds['id_dealer'] = $id_dealer	= $t->id_dealer;	
				$ds['tgl_do'] = $tgl_do = $this->input->post('tanggal');			
				$ds['status'] 			= "input";			
				$ds['created_at']		= $waktu;		
				$ds['created_by']		= $login_id;	
				$this->m_admin->insert("tr_do_indent",$ds);

	      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$ambil_do = $this->m_admin->getByID("tr_do_po_detail","no_do",$no_do);

				///bersihin do
				$amb_do = $this->db->query("SELECT * FROM tr_do_po_detail LEFT JOIN tr_po_dealer_indent ON tr_do_po_detail.no_spk = tr_po_dealer_indent.id_spk 
				 							WHERE tr_do_po_detail.no_do = '$no_do' ORDER BY tr_do_po_detail.id_do_po_detail DESC LIMIT 0,1");
				if($amb_do->num_rows() > 0){
					$df = $amb_do->row();
					$dealer = $df->id_dealer;
					$amb_do = $this->db->query("SELECT * FROM tr_do_po_detail WHERE no_do = '$no_do'");
					foreach ($amb_do->result() as $key) {
						$amb_do2 = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_spk = '$key->no_spk'")->row();
						if($dealer != $amb_do2->id_dealer){
							$this->db->query("UPDATE tr_po_dealer_indent SET status = 'sent' WHERE id_indent = '$amb_do2->id_indent'");																		
							$this->m_admin->delete("tr_do_po_detail","id_do_po_detail",$key->id_do_po_detail);
							$this->m_admin->delete("tr_do_indent_detail","id_indent",$amb_do2->id_indent);						
						}
					}
				}
				////

				$jumlah	= $ambil_do->num_rows();
	      $this->m_admin->insert($tabel,$data);

				foreach ($ambil_do->result() as $data_do) {								
					$id_item	= $data_do->id_item;

					$bulan  = explode('-', $tgl_do);
		      $bl=$bulan[1];$th=$bulan[0];

		      $tipe = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna FROM ms_tipe_kendaraan 
		      							INNER JOIN ms_item ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
		      							INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
		      							WHERE ms_item.id_item = '$id_item'")->row();

		      $cek_quo = $this->db->query("SELECT * FROM tr_quotation INNER JOIN tr_quotation_bulan ON tr_quotation.no_quotation = tr_quotation_bulan.no_quotation
		        INNER JOIN tr_quotation_tipe ON tr_quotation.no_quotation = tr_quotation_tipe.no_quotation
		        WHERE tr_quotation_bulan.bulan = '$bl' AND tr_quotation_bulan.tahun = '$th'
		        AND tr_quotation_tipe.id_tipe_kendaraan = '$tipe->id_tipe_kendaraan'");
		      if($cek_quo->num_rows() > 0){
		        $d = $cek_quo->row();
		        $disc1 = $d->nilai;
		      }else{
		        $disc1 = 0;
		      }

		      $cek_scp = $this->db->query("SELECT ahm_kredit+md_kredit AS nilai FROM tr_sales_program INNER JOIN tr_sales_program_dealer ON tr_sales_program.id_program_md = tr_sales_program_dealer.id_program_md
		        INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program
		        INNER JOIN tr_sales_program_tipe ON tr_sales_program.id_program_md = tr_sales_program_tipe.id_program_md                      
		        WHERE '$tgl_do' BETWEEN tr_sales_program.periode_awal AND tr_sales_program.periode_akhir
		        AND ms_jenis_sales_program.jenis_sales_program = 'SCP' AND tr_sales_program_dealer.id_dealer = '$id_dealer'
		        AND tr_sales_program_tipe.metode_pembayaran = 'Bayar Didepan(Potong DO)'
		        AND tr_sales_program_tipe.id_tipe_kendaraan = '$tipe->id_tipe_kendaraan' AND FIND_IN_SET('$tipe->id_warna',tr_sales_program_tipe.id_warna)");
		      $n=0;
		      foreach ($cek_scp->result() as $isi) {
		        $n = $n + $isi->nilai;
		      }

		      $disc = $disc1 + $n;
					
					$da['disc'] = $disc;
					$testb = $this->m_admin->update('tr_do_po_detail',$da,"id_do_po_detail",$data_do->id_do_po_detail);		
				}
			}else{
				$_SESSION['pesan'] 	= "Detail Indent tidak boleh kosong";
				$_SESSION['tipe'] 	= "danger";				
				echo "<script>history.go(-1)</script>";								
				exit;
			}							


			
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		$_SESSION['no_do'] = "";
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";				
	}
	public function reject()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id					= $this->input->get("id");		
		$data['status'] 					= "rejected";
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been rejected successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/indent'>";		
	}
}