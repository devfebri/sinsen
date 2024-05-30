<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ksu_hilang extends CI_Controller {

    var $tables =   "tr_penerimaan_ksu_dealer";	
		var $folder =   "dealer";
		var $page		=		"ksu_hilang";
    var $pk     =   "id_penerimaan_ksu_dealer";
    var $title  =   "Laporan Stok KSU Hilang";

	public function __construct()
	{		
		parent::__construct();		

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tgl_indo');

		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('PDF_HTML');

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
		$data['set']		= "view";
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_ksu'] = $this->db->query("SELECT DISTINCT(tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer) AS ID FROM tr_penerimaan_ksu_dealer INNER JOIN tr_penerimaan_unit_dealer
					ON tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer 
					WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND  tr_penerimaan_ksu_dealer.qty_md > tr_penerimaan_ksu_dealer.qty_terima");						
		$this->template($data);			
	}
	public function detail(){
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;
		$data['set']		= "detail";
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_ksu'] = $this->db->query("SELECT DISTINCT(tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer) AS ID FROM tr_penerimaan_ksu_dealer INNER JOIN tr_penerimaan_unit_dealer
					ON tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer 
					WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND  tr_penerimaan_ksu_dealer.qty_md > tr_penerimaan_ksu_dealer.qty_terima");						
		$data['id_penerimaan_unit_dealer'] = $this->input->get('id');
		$id_penerimaan_unit_dealer 					= $this->input->get('id');
    $data['sj'] 		= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer 
    		LEFT JOIN tr_surat_jalan on tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan
    		LEFT JOIN tr_sppm on tr_surat_jalan.no_surat_sppm=tr_sppm.no_surat_sppm
    	WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer'")->row();
		$this->template($data);										
	}
	public function terima(){
		$data['isi']    = $this->page;		
		$data['title']	= "Penerimaan ".$this->title;
		$data['set']		= "terima";
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_ksu'] = $this->db->query("SELECT DISTINCT(tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer) AS ID FROM tr_penerimaan_ksu_dealer INNER JOIN tr_penerimaan_unit_dealer
					ON tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer 
					WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND  tr_penerimaan_ksu_dealer.qty_md > tr_penerimaan_ksu_dealer.qty_terima");						
		$data['id_penerimaan_unit_dealer'] = $this->input->get('id');
		$id_penerimaan_unit_dealer 					= $this->input->get('id');
    $data['sj'] 		= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer'")->row();
    $data['v_ksu'] 	= $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer INNER JOIN ms_ksu ON tr_penerimaan_ksu_dealer.id_ksu=ms_ksu.id_ksu 
                WHERE tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' AND tr_penerimaan_ksu_dealer.qty_md > tr_penerimaan_ksu_dealer.qty_terima");
		$this->template($data);										
	}
	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');				
		$id_pu 			= $this->input->post('id_pu');				
		$no 				= $this->input->post('no');				
		$no_sj 			= $this->input->post('no_sj');				
		$id_ksu_d 	= $this->input->post('id_ksu_d');				
		$cek = 0;
		foreach($id_pu AS $key => $val){
			$qty_md=0;$qty_terima=0;
		 	$id_ksu  	= $_POST['id_ksu'][$key];			
			$id_item 	= $_POST['id_item'][$key];
			$qty_terima  	= $_POST['qty_terima'][$key];
		 	$qty_md  	= $_POST['qty_md'][$key];
		 	$id_pu  	= $_POST['id_pu'][$key];
		 	$no_sj  	= $_POST['no_sj'][$key];
			
		 	$result[] = array(
				"id_penerimaan_unit_dealer"  => $id_pu,
				"id_ksu"  => $_POST['id_ksu'][$key],
				"id_item"  => $_POST['id_item'][$key],
				"qty_terima"  => $qty_terima,
				"created_at"  => $waktu,
				"created_by"  => $login_id
		 	); 
		 	if($qty_md < $qty_terima){
		 		$cek = $cek + 1;		 		
		 	}else{
		 		$rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_ksu = '$id_ksu' AND no_surat_jalan = '$no_sj' AND id_penerimaan_unit_dealer = '$id_pu'");
	      if($rty->num_rows() > 0){
	      	$e = $rty->row();      	
	      	$ty = $e->qty_terima + $qty_terima;
	      	$this->db->query("UPDATE tr_penerimaan_ksu_dealer SET qty_terima = '$ty' WHERE id_penerimaan_ksu_dealer = '$e->id_penerimaan_ksu_dealer'");
	      }
		 	}		 	

		}
		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty Penerimaan KSU tidak boleh lebih dari jumlah KSU yg di-supply oleh MD";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/ksu_hilang'>";
		}else{			
      $test2 = $this->db->insert_batch('tr_kekurangan_ksu_dealer', $result);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/ksu_hilang'>";
		}		
	}

	public function cetak()
	{
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;				
		$id_dealer 			= $this->m_admin->cari_dealer();					
		$id_penerimaan_unit_dealer 					= $this->input->get('id');
   		$dt_sj 		= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer 
   					LEFT JOIN tr_surat_jalan on tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan
   					left join tr_sppm  on tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
   			left join ms_dealer on tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer  WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' ")->row();
   		$dt_ksu = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer INNER JOIN ms_ksu ON tr_penerimaan_ksu_dealer.id_ksu=ms_ksu.id_ksu 
                WHERE tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' AND tr_penerimaan_ksu_dealer.qty_md > tr_penerimaan_ksu_dealer.qty_terima");

		$pdf = new PDF_HTML('p','mm','A4');
		  $pdf->SetMargins(10, 20, 10);
	      $pdf->AddPage();
	       // head	  
		  $pdf->SetFont('ARIAL','B',12);
		  $pdf->Cell(190, 7,'SURAT PERNYATAAN SUPIR', 0, 1, 'C');
		  $pdf->Ln(7);
		  $pdf->SetFont('ARIAL','',11); 
		  $tgl_indo = tgl_indo(date('Y-m-d'),' ');
		  $hari = nama_hari(date('Y-m-d'));
		  $pdf->Cell(190, 6,"Pada hari ini, $hari, $tgl_indo, saya yang bertanda tangan dibawah ini :", 0, 1, 'L');
		  $pdf->Cell(30, 6,"Nama ", 0, 0, 'L'); $pdf->Cell(150, 6,": $dt_sj->driver", 0, 1, 'L');
		  $pdf->Cell(30, 6,"Jabatan ", 0, 0, 'L'); $pdf->Cell(150, 6,": __________________________________", 0, 1, 'L');
		  $pdf->Cell(30, 6,"NIK ", 0, 0, 'L'); $pdf->Cell(150, 6,": __________________________________", 0, 1, 'L');
		  $pdf->Cell(30, 6,"Dealer ", 0, 0, 'L'); $pdf->Cell(150, 6,": $dt_sj->nama_dealer", 0, 1, 'L');
		  $pdf->Ln(3);
		  $pdf->Cell(190, 6,"Melalui surat ini menyatakan bertanggung jawab atas kekurangan KSU sebagai berikut : ", 0, 1, 'L');
		  $pdf->SetX(15);
		  	$no=1;
		  	foreach ($dt_ksu->result() as $rs) {
		  		$pdf->SetX(15);
		  		$hilang = $rs->qty_md - $rs->qty_terima;
		  		$pdf->Cell(70, 6,"$no. $rs->ksu ", 0, 0, 'L');$pdf->Cell(90, 6,": $hilang", 0, 1, 'L');	
		  		$no++;
		  	}
		  	
		  $pdf->Ln(3);

		  $pdf->Cell(190, 6,"Dengan total nominal harga Rp. _______________________.", 0, 1, 'L');	
		  $pdf->Multicell(190, 6,"melalui surat ini juga saya menyatakan memberikan kuasa kepada HRD untuk melakukan pemotongan gaji saya senilai tersebut diatas.", 0, 1);
		  $pdf->Ln(3);

		  $pdf->Multicell(190, 6,"Demikian surat pernyataan ini saya buat tanpa adanya paksaan dari pihak manapun dan untuk dipergunakan sebgaimana mestinya.", 0, 1);
		  $pdf->Ln(15);
		  $pdf->Cell(190, 6,"Hormat Saya", 0, 1, 'L');

		  $pdf->Ln(20);
		  $pdf->Cell(190, 6,"$dt_sj->driver", 0, 1, 'L');



		  $pdf->Output();
	}


	
}