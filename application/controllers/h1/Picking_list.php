<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Picking_list extends CI_Controller {

    var $tables =   "tr_picking_list";	
		var $folder =   "h1";
		var $page		=		"picking_list";
    var $pk     =   "no_picking_list";
    var $title  =   "Picking List (PL)";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_picking_list');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');

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
	/*
	public function index_old()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";		
		$data['dt_pl'] = $this->db->query("SELECT tr_picking_list.*,tr_do_po.no_do,ms_dealer.id_dealer,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_picking_list INNER JOIN tr_do_po ON tr_picking_list.no_do=tr_do_po.no_do
									INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer order by no_picking_list DESC");	
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");					
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	*/

	public function battery_stok()
	{	
		$no_pl = $this->input->post('id');
		$wheresjson = "WHERE 1=1 ";
		$wheresjson .= "AND plb.no_picking_list ='$no_pl' ";
		$query_result = $this->db->query("SELECT * from  tr_picking_list_battery plb 
		left join  tr_picking_list pl  on pl.no_picking_list =plb.no_picking_list and pl.no_do = plb.no_do 
		left join tr_stock_battery sb on sb.serial_number= plb.serial_number
		$wheresjson");
		$result = $query_result->result_array(); 

		if (!empty($result) && count($result) > 0) {
			$status = 1;
		} else {
			$status = 0;
		}

        $response = array(
			'status' => $status,
			'data'   => $result,
		);

        header('Content-Type: application/json');
        echo json_encode($response);
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	public function fetch_data()
	{
		$list = $this->m_picking_list->get_datatables();
		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");


        foreach($list as $row) {                   
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            if($row->status=='input'){
              $status = "<span class='label label-danger'>Waiting Open</span>";            
              $t1 = "<a data-toggle=\"tooltip\" $print target=\"_blank\" title=\"Cetak PL\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/picking_list/cetak?id=$row->no_picking_list\"><i class=\"fa fa-print\"></i></a>";
              $t2 = "";
              // $link = "";
              $link = " href='h1/picking_list/detail?id=$row->no_picking_list'";
              
            }elseif($row->status=='proses'){
              $status = "<span class='label label-primary'>Proses</span>";
              $t1 = "";
              $link = " href='h1/picking_list/detail?id=$row->no_picking_list'";
              $t2 = "<a data-toggle=\"tooltip\" title=\"Konfirmasi PL\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/picking_list/konfirmasi?k=konfirm&id=$row->no_picking_list\"><i class=\"fa fa-check\"></i></a>";
            }elseif($row->status=='close'){
              $status = "<span class='label label-success'>Close</span>";
              $t1 = "<a data-toggle=\"tooltip\" $print target=\"_blank\" title=\"Cetak PL\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/picking_list/cetak?id=$row->no_picking_list\"><i class=\"fa fa-print\"></i></a>";
              $t2 = "";
              $link = " href='h1/picking_list/detail?id=$row->no_picking_list'";
            }           

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = "<a title='View Data'". $link.">".$row->no_picking_list."</a>";
			$rows[] = $row->tgl_pl;
			$rows[] = $row->no_do;
			$rows[] = $row->nama_dealer;
			$rows[] = $status;
			$rows[] = $t1.''.$t2;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_picking_list->count_all(),
			"recordsFiltered" => $this->m_picking_list->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function t_pl(){
		$no_do = $this->input->post('no_do');
		$k = $this->input->post('k');
		$data['dt_pl'] = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do'");		
		$data['no_pl'] = $this->input->post('no_pl');
		$data['no_do'] = $this->input->post('no_do');
		$data['isi_nosin'] 	= "";		
		if($k=='konfirm'){
			$this->load->view('h1/t_pl_k',$data);
		}else{
			$this->load->view('h1/t_pl',$data);
		}
	}
	public function save_nosin(){
		$scan_nosin 	= $this->input->post('scan_nosin');		
		$data["scan"] = "ya";
		$this->m_admin->update("tr_picking_list_view",$data,"no_mesin",$scan_nosin);
		echo "ok";
	}

	public function detail()
	{				
		$no_pl = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "detail";			
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");				

		$data['dt_pl'] = $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_do_po
						ON tr_picking_list.no_do = tr_do_po.no_do INNER JOIN ms_gudang
						ON tr_do_po.id_gudang = ms_gudang.id_gudang INNER JOIN ms_dealer
						ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_picking_list.no_picking_list = '$no_pl'");	
		
		$data['is_ev'] = $this->db->query("SELECT pkb.*,sbt.fifo from tr_picking_list_view plv left join tr_scan_barcode sb on plv.id_item = sb.id_item 
		left join ms_tipe_kendaraan tk on sb.tipe_motor = tk.id_tipe_kendaraan  
		left join tr_picking_list_battery pkb on pkb.no_picking_list =  plv.no_picking_list 
		left join tr_stock_battery sbt on sbt.serial_number = pkb.serial_number 
		where tk.id_kategori ='EV' and plv.no_picking_list ='$no_pl' and pkb.retur ='0'
		group by sbt.serial_number
		");	

		$this->template($data);	
	}
	public function cari_do(){
		$no_do = $this->input->post('no_do');
		$rt = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
						INNER JOIN ms_gudang ON tr_do_po.id_gudang=ms_gudang.id_gudang
						WHERE tr_do_po.no_do = '$no_do'")->row();
		echo $rt->id_dealer."|".$rt->nama_dealer."|".$rt->gudang."|".$rt->tgl_do."|".$rt->biaya_pdi;
	}
	public function cari_id(){
		$jenis_do				= $this->input->post('jenis_do');
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
		
			$kd = "PL-";
			
			// harusnya berdasarkan no_picking list tpi bisa jalan dengan id = 00001
			$pr_num = $this->db->query("SELECT * FROM tr_do_indent  WHERE LEFT(tgl_do,4) = '$th'  ORDER BY no_do DESC LIMIT 0,1");
			
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
		 	
		echo $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_picking_list		= $this->input->post("no_pl");
		$id_item						= $this->input->post("id_item");

		foreach($id_item AS $key => $val){
			$result[] = array(
				"no_picking_list"  	=> $no_picking_list,
				"id_item"  		=> $_POST['id_item'][$key],
				"no_mesin"  	=> $_POST['no_mesin'][$key],
				"lokasi" 			=> $_POST['lokasi'][$key],
				"slot"  			=> $_POST['slot'][$key],
				"status"  		=> "input"
			);
		}		
		$testb= $this->db->insert_batch('tr_picking_list_view', $result);

		
		$data['status'] 					= "open";			
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$this->m_admin->update("tr_picking_list",$data,"no_picking_list",$no_picking_list);
		//$this->download_file($id_do);

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/picking_list'>";
	}

	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{		
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									
			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{
				$this->m_admin->delete("tr_picking_list","no_picking_list",$id);
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/picking_list'>";
		}
	}
	public function konfirmasi()
	{				
		$no_pl = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "konfirmasi";			
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");		
						
		$data['dt_pl'] = $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_do_po
						ON tr_picking_list.no_do = tr_do_po.no_do INNER JOIN ms_gudang
						ON tr_do_po.id_gudang = ms_gudang.id_gudang INNER JOIN ms_dealer
						ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_picking_list.no_picking_list = '$no_pl'");		

		$is_ev = $this->db->query("SELECT pk.no_picking_list 
				FROM tr_picking_list pk left join tr_picking_list_battery pkd on pkd.no_picking_list = pk.no_picking_list
				WHERE pk.no_picking_list = '$no_pl' and pkd.no_picking_list_battery is not null");

		if($is_ev->num_rows() > 0){
			$_SESSION['pesan']   	= "Picking Terdapat UNIT EV | Mohon Pastikan Serial Number Saat Picking Battery";
			$_SESSION['tipe'] 	    = "warning";
			$data['is_ev'] = 1;
		}else{
			$data['is_ev'] = 0;
			// $_SESSION['pesan']   	= "Picking battre Kosong | Mohon Hubungi IT untuk pengecekan Data";
			// $_SESSION['tipe'] 	    = "error";
		}

		// testing ernesto
		$this->template($data);	
	}


	public function cetak(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$no_pl 			= $this->input->get('id');			

		//$data['title'] 				= "Cetak Picking List";
		$data['updated_at']		= $waktu;		
		$data['updated_by']		= $login_id;	
		$data['cetak_at']		= $waktu;		
		$data['cetak_by']		= $login_id;				
		$pl = $this->db->get_where('tr_picking_list',['no_picking_list'=>$no_pl])->row();
		if($pl->status!='close'){
			$data['status'] = "proses";
		}
		$data['cetak_ke'] = $pl->cetak_ke+1;
		$this->m_admin->update($tabel,$data,$pk,$no_pl);		
		

		$get_pl 	= $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do 
				INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
				WHERE tr_picking_list.no_picking_list = '$no_pl'")->row();        
		$cek_tmp 	= $this->db->query("SELECT * FROM tr_picking_list_view WHERE no_picking_list = '$no_pl'");        
    if($cek_tmp->num_rows() > 0){
      foreach ($cek_tmp->result() as $amb){                	
        	$dt['cetak'] 						= "ya";
        	if ($pl->cetak_ke==0) {
        		$this->m_admin->update("tr_picking_list_view",$dt,"no_mesin",$amb->no_mesin);
        	}

        	//$this->m_admin->delete("tr_pl_tmp","nosin",$cek_pik->no_mesin);
      }
    }


		$pdf = new FPDF('p','mm','A4');
    $pdf->AddPage();
       // head
	  $pdf->SetFont('TIMES','',20);
	  $pdf->Cell(190, 5, 'PICKING LIST UNIT', 0, 1, 'C');
	  $pdf->Ln(5);
	  $pdf->SetFont('TIMES','',12);
	  $pdf->Cell(50, 5, 'Main Dealer: PT.Sinar Sentosa Primatama', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Telp: 0741-61551', 0, 1, 'L');
	  $pdf->Line(11, 36, 200, 36);
	   
	  $pdf->Image(base_url().'/assets/panel/images/LOGO-C.png', 150, 10, 50);
	   
	  $pdf->SetFont('TIMES','',12);
	  $pdf->Cell(1,2,'',0,1);
	  $pdf->Cell(30, 5, 'No Picking List', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$no_pl.'', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'No DO', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$get_pl->no_do.'', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'Tgl DO', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$get_pl->tgl_do.'', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'Nama Dealer', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$get_pl->nama_dealer.'', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'Keterangan', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$get_pl->ket.'', 0, 1, 'L');	
	  $pdf->SetFont('TIMES','B',10);
	   // buat tabel disini
	  $pdf->SetFont('TIMES','B',10);
	   
	   // kasi jarak
	  $pdf->Cell(2,5,'',10,10);	  
	   
	  $pdf->Cell(10, 5, 'No', 1, 0);
	  $pdf->Cell(30, 5, 'No.Mesin', 1, 0);
	  $pdf->Cell(65, 5, 'Tipe Kendaraan', 1, 0);
	  $pdf->Cell(45, 5, 'Warna', 1, 0);
	  $pdf->Cell(25, 5, 'Lokasi', 1, 0);
	  $pdf->Cell(15, 5, 'Picked', 1, 1);	  

	  $pdf->SetFont('times','',10);
	  $get_nosin 	= $this->db->query("SELECT * FROM tr_picking_list_view WHERE no_picking_list = '$no_pl'");
	  $i=1;	  

	  $count_ev = array();
	  foreach ($get_nosin->result() as $r)
	  {
	  	$cek_pik = $this->db->query("SELECT ms_tipe_kendaraan.id_kategori,tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_scan_barcode INNER JOIN ms_item 
          ON tr_scan_barcode.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
          ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
          ON ms_item.id_warna=ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$r->no_mesin'")->row();     

		if ($cek_pik->id_kategori == 'EV'){
			$count_ev[] = '1';
		}

	    $pdf->Cell(10, 5, $i, 1, 0);
	    $pdf->Cell(30, 5, strtoupper($r->no_mesin), 1, 0);
	    $pdf->Cell(65, 5, $cek_pik->id_item.'-'.$cek_pik->tipe_ahm, 1, 0);
	    $pdf->Cell(45, 5, $cek_pik->warna, 1, 0);    
	    $pdf->Cell(25, 5, $cek_pik->lokasi."-".$cek_pik->slot, 1, 0);
	    $pdf->Cell(15, 5, "", 1, 1); 	    
	  	$i++; 	   		    
	  }

	  if (count($count_ev)>0) {
		$get_ev_oem 	= $this->db->query("SELECT sbt.* from tr_picking_list_battery pkb left join tr_stock_battery sbt on sbt.serial_number = pkb.serial_number  WHERE  pkb.no_picking_list='$no_pl'");
		// tabel EV
		$pdf->Cell(2, 5, '', 5, 10);
		$pdf->Cell(2, 3, '', 5, 10);
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(195, 1, 'Kelengkapan EV', 0, 1, 'C');
		// buat tabel disini
		$pdf->SetFont('TIMES', 'B', 10);
		$pdf->Cell(2, 5, '', 5, 10);
		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(10, 5, 'Tipe', 1, 0);
		$pdf->Cell(38, 5, 'Kode Part', 1, 0);
		$pdf->Cell(38, 5, 'Nama Part', 1, 0);
		$pdf->Cell(50, 5, 'Serial Number', 1, 0);
		$pdf->Cell(30, 5, 'Fifo', 1, 0);
		$pdf->Cell(15, 5, 'Picked', 1, 1);

		$no=1;
		$pdf->SetFont('times', '', 10);
		foreach ($get_ev_oem->result() as $oem) {
			$pdf->Cell(10, 5, $no, 1, 0);
			$pdf->Cell(10, 5, "B", 1, 0);
			$pdf->Cell(38, 5, $oem->part_id, 1, 0);
			$pdf->Cell(38, 5, $oem->part_desc, 1, 0);
			$pdf->Cell(50, 5, $oem->serial_number, 1, 0);
			$pdf->Cell(30, 5, $oem->fifo, 1, 0);
			$pdf->Cell(15, 5, '', 1, 1);

			$no++;
		}
	}
	   
	
	  $pdf->SetFont('TIMES','',12);	  
	  $pdf->Cell(10, 5, '', 0, 1);
	  $pdf->Cell(10, 15, '', 0, 0);
	  $pdf->Cell(80, 10, 'Diperiksa Oleh', 0, 1, 'C');	  
	  $pdf->Cell(10, 8, '', 0, 0);
	  $pdf->Cell(20, 10, 'Sebelum                                            Sesudah', 0, 1, 'L');
	  $pdf->Cell(10, 10, '', 0, 0);
	  $pdf->Cell(10, 10, '', 0, 1);
	  $pdf->Cell(10, 5, '', 0, 0);
	  $pdf->Cell(10, 5, '(Kepala Gudang)                             (Kepala Gudang)       ', 0, 1);
	  $pdf->SetFont('TIMES','',10);	  
	  $pdf->Cell(10, 5, 'Note', 0, 1,'L');
	  $pdf->Cell(10, 5, '* Centang Unit yang sudah diambil pada kolom Picked', 0, 1,'L');
	  $pdf->Output(); 
		
	}


	public function save_konfirmasi()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_item		= $this->input->post("id_item");
		$no_picking_list		= $this->input->post("no_pl");
		$jum				= $this->input->post("jum");
		$oem				= $this->input->post("oem");
		$cek = 0;

			for ($i=1; $i <= $jum; $i++) { 			
				$no_picking_list_view = $_POST["no_picking_list_view_".$i];
				$no_mesin = $_POST["no_mesin_".$i];
				$id_item = $_POST["id_item_".$i];
				if(isset($_POST["check_pl_".$i])){
					$data["konfirmasi"] = "ya";
					if(isset($_POST["check_pdi_".$i])){
						$data["pdi"] = "ya";	
					}else{
						$data["pdi"] = "tidak";
					}
					$this->m_admin->update("tr_picking_list_view",$data,"no_picking_list_view",$no_picking_list_view);				
					$da['status'] 	= "3";					
					$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);

					$ambil = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();
					$this->m_admin->update_stock($id_item,"RFS",'-','1');

					$this->db->query("UPDATE ms_lokasi_unit SET isi = isi-1 WHERE id_lokasi_unit = '$ambil->lokasi'");										
				}else{
					$data["konfirmasi"] = "tidak";
					if(isset($_POST["check_pdi_".$i])){
						$data["pdi"] = "ya";	
					}
					$this->m_admin->update("tr_picking_list_view",$data,"no_picking_list_view",$no_picking_list_view);				
					$da['status'] 	= "1";					
					$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);
				}						
			}	

			if(isset($oem)){
				foreach ($oem['no_picking_list_battery'] as $key => $values) {
					// $id_picking_battre = $oem['no_picking_list_battery'][$key];
					$serial_number     = $oem['serial_number'][$key];
					$konfirmasi= NULL;

					if(isset($oem['konfirmasi'][$key])){
						$konfirmasi = 'ya';
					}
						$konfirmasi = 'ya';

					$array = array(
						'no_picking_list' => $no_picking_list,
						'id_part' => $oem['part'][$key],
						'serial_number' => $serial_number ,
						'ceklist' => $konfirmasi,
						'scan' => 'ya',
						'retur' => '0',
					);
				
					$this->m_admin->update("tr_picking_list_battery",$array,"serial_number",$serial_number);
				}
			}
				

		$dat['status'] 				    = "close";			
		$dat['updated_at']				= $waktu;		
		$dat['updated_by']				= $login_id;	
		$this->m_admin->update("tr_picking_list",$dat,"no_picking_list",$no_picking_list);


		//$this->download_file($id_do);

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/picking_list'>";
	}	

	
	public function save_konfirmasi_backup()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_item		= $this->input->post("id_item");
		$no_picking_list		= $this->input->post("no_pl");
		$jum				= $this->input->post("jum");
		$cek = 0;
			for ($i=1; $i <= $jum; $i++) { 			
				$no_picking_list_view = $_POST["no_picking_list_view_".$i];
				$no_mesin = $_POST["no_mesin_".$i];
				$id_item = $_POST["id_item_".$i];
				if(isset($_POST["check_pl_".$i])){
					$data["konfirmasi"] = "ya";
					if(isset($_POST["check_pdi_".$i])){
						$data["pdi"] = "ya";	
					}else{
						$data["pdi"] = "tidak";
					}
					$this->m_admin->update("tr_picking_list_view",$data,"no_picking_list_view",$no_picking_list_view);				
					$da['status'] 	= "3";					
					$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);

					$ambil = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();
					$this->m_admin->update_stock($id_item,"RFS",'-','1');

					$this->db->query("UPDATE ms_lokasi_unit SET isi = isi-1 WHERE id_lokasi_unit = '$ambil->lokasi'");										
				}else{
					$data["konfirmasi"] = "tidak";
					if(isset($_POST["check_pdi_".$i])){
						$data["pdi"] = "ya";	
					}
					$this->m_admin->update("tr_picking_list_view",$data,"no_picking_list_view",$no_picking_list_view);				
					$da['status'] 	= "1";					
					$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);
				}						
			}		

				

		$dat['status'] 						= "close";			
		$dat['updated_at']				= $waktu;		
		$dat['updated_by']				= $login_id;	
		$this->m_admin->update("tr_picking_list",$dat,"no_picking_list",$no_picking_list);


		//$this->download_file($id_do);

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/picking_list'>";
	}
}