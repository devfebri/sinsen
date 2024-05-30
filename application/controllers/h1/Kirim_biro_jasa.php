<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kirim_biro_jasa extends CI_Controller {

    var $tables =   "tr_kirim_biro";	
		var $folder =   "h1";
		var $page		=		"kirim_biro_jasa";
    var $pk     =   "id_kirim_biro";
    var $title  =   "Kirim Biro Jasa";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
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
		$data['set']		= "view";
		$data['dt_biro']	= $this->m_admin->getAll($this->tables);
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";		
		$id_generate = $this->input->get('id');
		$detail = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail
				inner join tr_faktur_stnk on tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			 	WHERE tr_pengajuan_bbn_detail.id_generate = '$id_generate' AND tr_pengajuan_bbn_detail.status_bbn='generated'");	
		$detail2 = $this->db->query("SELECT * FROM tr_bantuan_bbn WHERE tr_bantuan_bbn.id_generate = '$id_generate' 
				AND tr_bantuan_bbn.status = 'generated'");			
		$data['detail'] = $detail;		
		$data['detail2'] = $detail2;			
		$this->template($data);			
	}

	// public function cetak_tanda_terima(){
	// 	$tgl 				= gmdate("y-m-d", time()+60*60*7);
	// 	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;
	// 	$pk 				= $this->pk;		
	// 	$id 				= $this->input->get('id');				
  	
	// 	$data['no_tanda_terima'] 	= $this->m_admin->cari_id("tr_kirim_biro","no_tanda_terima");
	// 	$data['tgl_terima']				= $tgl;						
	// 	$r = $this->m_admin->getByID("tr_kirim_biro","id_kirim_biro",$id)->row();
	// 	if($r->no_tanda_terima == ""){
	// 		$this->m_admin->update("tr_kirim_biro",$data,"id_kirim_biro",$id);									
	// 	}
		
	
	// 	$pdf = new FPDF('p','mm','A4');
 //    $pdf->AddPage();
 //       // head	  
	//   $pdf->SetFont('TIMES','',12);
	//   $pdf->Cell(150, 5, 'Cetak Tanda Terima Map', 0, 1, 'C');
	//   $pdf->SetFont('TIMES','',12);	  
	//   $pdf->Cell(100, 5, "Nama Biro Jasa :".$r->nama_biro_jasa, 0, 1, 'L');
	//   $pdf->Cell(100, 5, "Tgl Mohon Samsat :".$r->tgl_mohon_samsat, 0, 1, 'L');	  
	//   $pdf->Output(); 
	// }


	public function getData()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

		if ($search != '') {
        	$this->db->like('tgl_mohon_samsat', $search, 'BOTH');
        	$this->db->or_like('id_generate', $search, 'BOTH');
        	$this->db->or_like('nama_biro_jasa', $search, 'BOTH');
        }
		$this->db->order_by($order_field, $order_ascdesc); // Untuk menambahkan query ORDER BY
		$this->db->limit($limit, $start);
		$kirim_biro_jasa = $this->db->get('tr_kirim_biro');

        $data = array();
        $biaya = $this->db->query("SELECT * FROM ms_setting_h1")->row();
        foreach($kirim_biro_jasa->result() as $row)
        {
        	
        	$cek = $this->m_admin->getByID("tr_pengajuan_bbn_detail","id_generate",$row->id_generate);
          if($cek->num_rows() > 0){
            $bbn_detail1 = $this->db->query("SELECT SUM(biaya_bbn_md_bj) as total,COUNT(biaya_bbn_md_bj)as jml FROM tr_pengajuan_bbn_detail WHERE id_generate = '$row->id_generate'")->row();
            $bbn = $this->db->query("SELECT *, biaya_bbn_md_bj AS jml FROM tr_pengajuan_bbn_detail WHERE id_generate = '$row->id_generate'");
            $tot1=0;
            foreach ($bbn->result() as $rs) {
             $tot1+= $rs->biaya_bbn_md_bj;
            }
            $jum1 = $bbn_detail1->jml;
          }else{
            $tot1 = 0;
            $jum1 = 0;
          }

          $cek2 = $this->m_admin->getByID("tr_bantuan_bbn","id_generate",$row->id_generate);
          if($cek2->num_rows() > 0){
            $bbn_detail2 = $this->db->query("SELECT SUM(biaya_bbn_md_bj) as total,COUNT(biaya_bbn_md_bj)as jml FROM tr_bantuan_bbn WHERE id_generate = '$row->id_generate'")->row();
            $bbn = $this->db->query("SELECT *, biaya_bbn_md_bj AS jml FROM tr_bantuan_bbn WHERE id_generate = '$row->id_generate'");
            $tot2=0;
            foreach ($bbn->result() as $rs) {
             $tot2+= $rs->biaya_bbn_md_bj;
            }
            $jum2 = $bbn_detail2->jml;
          }else{
            $tot2 = 0;
            $jum2 = 0;
          }
          
          $total1 = (($biaya->biaya_bpkb+ $biaya->biaya_stnk+$biaya->biaya_plat)*$jum1)+$tot1; 
          $total2 = (($biaya->biaya_bpkb+ $biaya->biaya_stnk+$biaya->biaya_plat)*$jum2)+$tot2; 
          $print = $this->m_admin->set_tombol($this->m_admin->getMenu($this->page),$this->session->userdata("group"),'print');
          $t_jum = $jum1 + $jum2;

            $data[]= array(
            	'',
                $row->tgl_mohon_samsat,
                "<a href='h1/kirim_biro_jasa/detail?id=$row->id_generate'>$row->id_generate</a> ",
                $row->nama_biro_jasa,
                $t_jum,
                number_format($total1 + $total2, 0, ',', '.'),
                $row->tgl_terima,
                "<a $print href='h1/kirim_biro_jasa/cetak_surat?id=$row->id_generate' class='btn btn-primary btn-flat btn-xs'>Cetak Surat Pengantar</a>   
                <a $print href='h1/kirim_biro_jasa/cetak_pembayaran_bbn?id=$row->id_generate' class='btn btn-success btn-flat btn-xs'>Cetak Pembayaran BBN</a> ",

            );     
        }
        if ($search != '') {
        	$this->db->like('tgl_mohon_samsat', $search, 'BOTH');
        	$this->db->or_like('id_generate', $search, 'BOTH');
        	$this->db->or_like('nama_biro_jasa', $search, 'BOTH');
        	$total = $this->db->get('tr_kirim_biro')->num_rows();
        } else {
        	$total = $this->db->count_all('tr_kirim_biro');
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


	public function cek_no_tt()
	{
		 $tgl 						= date("d");
		 $cek_tgl					= date("Y-m");
		 $th 						= date("Y");
		 $bln 						= date("m");	
		 $id_dealer = $this->m_admin->cari_dealer();
		 $get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer' ");	
		 if ($get_dealer->num_rows() > 0) {
				$get_dealer = $get_dealer->row()->kode_dealer_md;
				}else{
					$get_dealer ='';
				}

		 $pr_num = $this->db->query("SELECT *,mid(tgl_cetak_invoice2,6,2)as bln FROM tr_sales_order WHERE LEFT(tgl_cetak_invoice2,7) = '$cek_tgl' ORDER BY tgl_cetak_invoice2 DESC LIMIT 0,1");						
		 if($pr_num->num_rows()>0){
			
			
		 	$row 	= $pr_num->row();
		 	$id = explode('/', $row->no_invoice);
		 	if (count($id) > 1) {
		 		if ($bln == $row->bln) {
		 			$isi 	= $th.'/'.$bln.'/'.$get_dealer.'/INU/'.sprintf("%'.05d",$id[4]+1);
		 		}else{
			 		$isi = $th.'/'.$bln.'/'.$get_dealer.'/INU/00001';

		 		}
		 	}else{
		 		$isi = $th.'/'.$bln.'/'.$get_dealer.'/INU/00001';
		 	}				
		 	$kode = $isi;
		 }else{
		 		$kode = $th.'/'.$bln.'/'.$get_dealer.'/INU/00001';
		 } 			
		 return $kode;
	}

	public function cetak_tanda_terima(){
		$waktu 					= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 					= gmdate("y-m-d", time()+60*60*7);
		$login_id				= $this->session->userdata('id_user');
		$tabel					= $this->tables;
		$pk 					= $this->pk;		
		$id_generate		= $this->input->get('id');			

		// $data['updated_at']		= $waktu;		
		// $data['tgl_cetak']		= date('Y-m-d');		
		// $data['updated_by']		= $login_id;			
		//$this->m_admin->update("tr_faktur_stnk",$data,"no_bastd",$no_bastd);						
		$data['no_tanda_terima'] 	= $this->m_admin->cari_id("tr_kirim_biro","no_tanda_terima");
		$data['tgl_terima']				= $tgl;						
		$r = $this->m_admin->getByID("tr_kirim_biro","id_generate",$id_generate)->row();
		if($r->no_tanda_terima == ""){
			$this->m_admin->update("tr_kirim_biro",$data,"id_generate",$id_generate);									
		}
		
		 $detail = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail
			inner join tr_faktur_stnk on tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
			inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			 WHERE tr_pengajuan_bbn_detail.id_generate = '$id_generate' AND tr_pengajuan_bbn_detail.status_bbn='generated'");
		 if ($detail->num_rows()>0) {
		 	$row = $detail->row();
		 $jum = $detail->num_rows();
		 $r = $this->m_admin->getByID("tr_kirim_biro","id_generate",$id_generate)->row();
		$pdf = new FPDF('p','mm','A4');
    $pdf->AddPage();
       // head	  
	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(50, 5, 'Jambi, '.date("d-m-Y", strtotime($r->tgl_terima)).'', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Kepada Yth,', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'PT. SINAR SENTOSA PRIMATAMA', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
	  $pdf->Line(11, 31, 200, 31);
	   	  

	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(1,2,'',0,1);
	  $pdf->Cell(30, 5, 'Nomor', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$r->no_tanda_terima.'', 0, 1);	  

	  $pdf->Cell(30, 5, 'Perihal ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': Map Berkas untuk BBN', 0, 1);	  	  

	  $pdf->Cell(30,5, 'Dengan Hormat ', 0, 1);	  
	  $pdf->MultiCell(190,5, 'Bersama dengan surat ini kami dari '.$row->nama_dealer.' mengirimkan map untuk proses BBN sebanyak '.$jum.' unit dengan perincian sebagai berikut  :', 0, 1);	  
	  
	  $pdf->Cell(2,3,'',5,10);	  
	  $pdf->SetFont('TIMES','',12);
	   // buat tabel disini
	  $pdf->SetFont('TIMES','B',10);
	   
	   // kasi jarak
	  $pdf->Cell(2,5,'',5,10);	  
	   
	  $pdf->Cell(10, 5, 'No', 1, 0);
	  $pdf->Cell(50, 5, 'Nama', 1, 0);
	  $pdf->Cell(28, 5, 'No Mesin', 1, 0);
	  $pdf->Cell(73, 5, 'Kode Tipe', 1, 0);
	  $pdf->Cell(28, 5, 'Biaya BBN (Rp)', 1, 1);	  

	  $pdf->SetFont('times','',10);
	  $id_generate = $this->input->get('id');
	 

	  $i=1;$to=0;
	  $biaya = $this->db->query("SELECT * FROM ms_setting_h1")->row();	  
	  foreach ($detail->result() as $r)
	  {
	  	 $total = $biaya->biaya_bpkb+ $biaya->biaya_stnk+$biaya->biaya_plat+$row->biaya_bbn_md_bj;   
          $tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row()->tipe_ahm;                              
	    $pdf->Cell(10, 5, $i, 1, 0);
	    $pdf->Cell(50, 5, $r->nama_konsumen, 1, 0);
	    $pdf->Cell(28, 5, $r->no_mesin, 1, 0);
	    $pdf->Cell(73, 5, $tipe, 1, 0);    	    
	    $pdf->Cell(28, 5, number_format($total, 0, ',', '.'), 1, 1);	    
	  	$i++; 	   		    
	  	$to+=$total;
	  }
	  	$pdf->Cell(10, 5, '', 1, 0);
	    $pdf->Cell(50, 5, '', 1, 0);
	    $pdf->Cell(28, 5, '', 1, 0);
	    $pdf->Cell(73, 5, 'Total Biaya BBN', 1, 0);    	    
	    $pdf->Cell(28, 5, number_format($to, 0, ',', '.'), 1, 1);	
	   
	  $pdf->Cell(9,3,'',5,10);	  
	  $pdf->SetFont('TIMES','',10);	  
	  $pdf->Cell(10, 5, '', 0, 1);
	  $pdf->Cell(10, 15, '', 0, 0);
	  $pdf->Cell(30, 5, 'Pembayaran Biaya BBN tersebut di atas telah kami transfer ke rekening :', 0, 1,'L');	  
	  
	  $pdf->Cell(30, 5, 'Atas Nama ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': PT. Sinar Sentosa Primatama', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'No. Rekening ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'Nama Bank ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'Tanggal Transfer ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  

	  $pdf->MultiCell(190,5, 'Demikian surat pengantar ini kami buat untuk pemrosesan BBN. Atas perhatian dan kerjasamanya kami ucapkan terima kasih', 0, 1);	
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	  $pdf->Cell(50, 5, 'Dibuat :', 0, 0,'C');	   
	  $pdf->Cell(50, 5, 'Diketahui:', 0, 1,'C');	  	  
	  $pdf->Cell(10, 8, '', 0, 0);	  	  
	  $pdf->Cell(10, 10, '', 0, 1);
	  $pdf->Cell(10, 5, '', 0, 1);	  
	  $pdf->SetFont('TIMES','',8);	  
	  $pdf->Cell(10, 3, 'Catatan :', 0, 1,'L');
	  $pdf->Cell(10, 3, '1. Pengisian daftar map harus diurutkan sesuai Tipe Motor', 0, 1,'L');
	  $pdf->Cell(10, 3, '2. Ujung kanan map harus dibuat nomor sesuai dengan nama dalam surat', 0, 1,'L');
	  $pdf->Cell(10, 3, '3. Map yang dikirim harus telah lengkap sesuai dengan persyaratan yang berlaku', 0, 1,'L');
	  $pdf->Cell(10, 3, '4. Fotocopy bukti transfer harus dilampirkan', 0, 1,'L');
	  
	  $pdf->Cell(70, 5, '=======================================================================================================================', 0, 1);	  	  
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	  
	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(195, 1, 'Map telah diterima oleh pihak PT. Sinar Sentosa Primatama', 0, 1, 'C');	
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
		  $pdf->SetX(7); 
	  $pdf->Cell(85, 5, '1. Bagian Keuangan', 0, 0,'L');	

	  $pdf->Cell(50, 5, '2. Bagian Faktur', 0, 1,'L');	  	  
	  $pdf->Cell(15, 5, 'Nama', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ______________________', 0, 0);
	 $pdf->Cell(15, 5, 'Nama', 0, 0);	  	  
	  $pdf->Cell(60, 5, ': ______________________', 0, 1);

	  $pdf->Cell(15, 5, 'Tanggal', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': _________________Jam :________WIB', 0, 0);
	 $pdf->Cell(15, 5, 'Tanggal', 0, 0);	  	  
	  $pdf->Cell(60, 5, ': _________________Jam :_________WIB', 0, 1);
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	 
	   $pdf->Cell(15, 5, 'TTD', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ______________________', 0, 0);
	 $pdf->Cell(15, 5, 'TTD', 0, 0);	  	  
	  $pdf->Cell(60, 5, ': ______________________', 0, 1);

	  $pdf->Output(); 

		}else{
			$_SESSION['pesan'] 	= "Data tidak tersedia";
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/kirim_biro_jasa'>";				
		}
	}

	// public function cek_no_surat($id_gen=null, $no_surat=null)
	// // {
	// // 	 $tgl 						= date("d");
	// // 	 $cek_tgl					= date("Y-m");
	// // 	 $th 						= date("Y");
	// 	 $bln 						= date("m");	
	// 	 $pr_num 				= $this->db->query("SELECT *, left(tgl_surat_pengantar,7) as bln FROM tr_kirim_biro WHERE left(tgl_surat_pengantar,7)='$cek_tgl' ORDER BY no_surat_pengantar DESC LIMIT 0,1");						
	// 	 if($pr_num->num_rows()>0)
	// 	 {		
	// 	 	$row 	= $pr_num->row();
	// 	 	$id = explode('/', $row->no_bastk);
	// 	 	if (count($id) > 1) {
	//  			$kode 	= sprintf("%'.03d",$id[0]+1).'/'.$bln.'/'.$th;
	//  		}else{
	// 	 		$kode = '001/'.$bln.'/'.$th;

	//  		}
	// 	 	}else{
	// 	 		$kode = '001/'.$bln.'/'.$th;
	// 	 	}				
	// 	 }else{
	// 	 		$kode = '001/'.$bln.'/'.$th;
	// 	 } 			
	// 	 return $kode;
	// }

	public function cetak_surat()
	{
    $id_generate = $this->input->get('id');
    $tgl = date('Y-m-d');
    $sql = $this->db->query("SELECT *, count(tr_pengajuan_bbn_detail.no_mesin) as jum FROM tr_pengajuan_bbn_detail
				inner join tr_faktur_stnk on tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			 	WHERE tr_pengajuan_bbn_detail.id_generate = '$id_generate' AND tr_pengajuan_bbn_detail.status_bbn='generated' GROUP BY tr_faktur_stnk.id_dealer");
    $sql2 = $this->db->query("SELECT * FROM tr_bantuan_bbn	WHERE tr_bantuan_bbn.id_generate = '$id_generate' 
   			AND tr_bantuan_bbn.status = 'generated'");
    if ($sql->num_rows()>0 OR $sql2->num_rows() > 0) {    
    	$isi_sql = $sql->row(); 
      $data['no_tanda_terima'] 	= $this->m_admin->cari_id("tr_kirim_biro","no_tanda_terima");
			$data['tgl_terima']				= $tgl;						
			$r = $this->m_admin->getByID("tr_kirim_biro","id_generate",$id_generate)->row();
			if($r->no_tanda_terima == ""){
				$this->m_admin->update("tr_kirim_biro",$data,"id_generate",$id_generate);									
			}
    	$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] = 'surat';
    	$data['jenis'] = 'pengajuan';
    	$data['sql'] = $sql;
    	$data['id_generate'] = $id_generate;
    	$data['tgl_samsat'] = $isi_sql->tgl_mohon_samsat;
    	$data['nama_dealer'] = $isi_sql->nama_dealer;

    	$isi_sql2 = $sql2->row(); 
      $data['no_tanda_terima'] 	= $this->m_admin->cari_id("tr_kirim_biro","no_tanda_terima");
			$data['tgl_terima']				= $tgl;						
			$r = $this->m_admin->getByID("tr_kirim_biro","id_generate",$id_generate)->row();
			if($r->no_tanda_terima == ""){
				$this->m_admin->update("tr_kirim_biro",$data,"id_generate",$id_generate);									
			}
    	
    	$data['sql2'] = $sql2;
    	//$data['tgl_samsat'] = $isi_sql2->tgl_samsat;
    	$data['nama_dealer'] = $isi_sql2->nama_konsumen;
    	$html = $this->load->view('h1/kirim_biro_jasa_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_biro_jasa_.pdf';
      $mpdf->Output("$output", 'I');
    }else{
    	$_SESSION['pesan'] 	= "Data tidak tersedia";
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/kirim_biro_jasa'>";		
    }
        
	}

	public function cetak_pembayaran_bbn(){
    $id_generate = $this->input->get('id');     
    $tgl = date("Y-m-d");
    $sql = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail
				inner join tr_faktur_stnk on tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			 	WHERE tr_pengajuan_bbn_detail.id_generate = '$id_generate' AND tr_pengajuan_bbn_detail.status_bbn='generated'");
    $sql2 = $this->db->query("SELECT * FROM tr_bantuan_bbn				
			 	WHERE tr_bantuan_bbn.id_generate = '$id_generate' AND tr_bantuan_bbn.status = 'generated'");
   	$sql_group = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail
				inner join tr_faktur_stnk on tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			 	WHERE tr_pengajuan_bbn_detail.id_generate = '$id_generate' AND tr_pengajuan_bbn_detail.status_bbn='generated' GROUP BY tr_faktur_stnk.id_dealer");

    $data['no_tanda_terima'] 	= $this->m_admin->cari_id("tr_kirim_biro","no_tanda_terima");
		$data['tgl_terima']				= $tgl;						
    if ($sql->num_rows()>0) {        	
			$r = $this->m_admin->getByID("tr_kirim_biro","id_generate",$id_generate)->row();
			if($r->no_tanda_terima == ""){
				$this->m_admin->update("tr_kirim_biro",$data,"id_generate",$id_generate);									
			}
      $mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] = 'pembayaran_bbn_1';
    	$data['sql'] = $sql;
    	$data['sql_group'] = $sql_group;
    	$data['tgl_samsat'] = $r->tgl_mohon_samsat;
    	$html = $this->load->view('h1/kirim_biro_jasa_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak.pdf';
      $mpdf->Output("$output", 'I');
    }elseif($sql2->num_rows()>0) {        	
      $data['no_tanda_terima'] 	= $this->m_admin->cari_id("tr_kirim_biro","no_tanda_terima");
			$data['tgl_terima']				= $tgl;						
			$r = $this->m_admin->getByID("tr_kirim_biro","id_generate",$id_generate)->row();
			if($r->no_tanda_terima == ""){
				$this->m_admin->update("tr_kirim_biro",$data,"id_generate",$id_generate);									
			}
      $mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] = 'pembayaran_bbn_2';
    	$data['sql'] = $sql2;
    	$data['sql_group'] = $sql_group;
    	$data['tgl_samsat'] = $tgl;
    	$html = $this->load->view('h1/kirim_biro_jasa_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak.pdf';
      $mpdf->Output("$output", 'I');
    }else{
    	$_SESSION['pesan'] 	= "Data tidak tersedia";
			$_SESSION['tipe'] 	= "warning";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/kirim_biro_jasa'>";		
    }      
	}
}