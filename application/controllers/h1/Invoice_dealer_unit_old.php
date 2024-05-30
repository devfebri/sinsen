<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_dealer_unit extends CI_Controller {

    var $tables =   "tr_invoice_dealer";	
		var $folder =   "h1";
		var $page		=		"invoice_dealer_unit";
		var $isi		=		"invoice_keluar";
    var $pk     =   "id_invoice_dealer";
    var $title  =   "Invoice Dealer Unit";

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
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "view";			
		$data['dt_invoice']	= $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
			WHERE tr_invoice_dealer.status_invoice <> 'printable' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");
		//$this->normalisasi();
		$this->template($data);			
	}
	public function normalisasi(){
		$tr = $this->m_admin->getAll("tr_invoice_dealer");
		foreach ($tr->result() as $dr) {			
		 $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
          ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
          ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
          ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$dr->no_do'");
      $to=0;$po=0;$do=0;
      foreach($dt_do_reg->result() as $isi){
        $total_harga = $isi->harga * $isi->qty_do;
				$get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
			      INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
			      INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
			      WHERE tr_invoice_dealer.no_do = '$isi->no_do'");
			    if($get_d->num_rows() > 0){
			      $g = $get_d->row();
			      $bunga_bank = $g->bunga_bank/100;
			      $top_unit = $g->top_unit;
			      $dealer_financing = $g->dealer_financing;
			    }else{
			      $bunga_bank = "";
			      $top_unit = "";
			      $dealer_financing = "";
			    }
			    $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                  WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
          if($cek2->num_rows() > 0){
            $d = $cek2->row();
            $potongan = $d->jum;
          }else{
            $potongan = 0;
          }

			    $pot = ($potongan + $isi->disc) * $isi->qty_do;                    
			    $to = $to + $total_harga;                    
			    $po = $po + $pot;                    
			    $do = $do + $isi->qty_do;
			}                     		  
		  $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+((1.1*$bunga_bank/360)*$top_unit));
		  $diskon_top = ($to-$po)-$d;
		  if($dealer_financing=='Ya') {
		    $y = $d * 0.1;
		    $total_bayar = $d + $y;
		  }else{
		    $y = $d * 0.1;
		    $total_bayar = $d + $y;
		  }

			$tgl_baru = date("y-m-d", strtotime("+".$top_unit." days", strtotime($dr->tgl_faktur))); 
		  $dt['total_bayar'] = $total_bayar;
		  $dt['tgl_overdue'] = $tgl_baru;

		  $cek = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE referensi = '$dr->no_faktur'")->row()->jum;
		  if($cek == $total_bayar){
		  	$dt['status_bayar'] = 'lunas';
		  }else{
		  	$dt['status_bayar'] = "";
		  }
		  $this->m_admin->update("tr_invoice_dealer",$dt,"id_invoice_dealer",$dr->id_invoice_dealer);
		}		
	}
	public function history(){				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "history";			
		$data['dt_invoice']	= $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
				WHERE tr_invoice_dealer.status_invoice = 'printable' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	// public function view()
	// {				
	// 	$data['isi']    = $this->isi;		
	// 	$data['page']   = $this->page;		
	// 	$id 						= $this->input->get('id');			
	// 	$data['title']	= "Detail ".$this->title;															
	// 	$data['set']		= "detail";				
	// 	$data['dt_invoice']	= $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
	// 			INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
	// 			WHERE tr_invoice_dealer.no_do = '$id'");
	// 	$this->template($data);			
	// }	
	public function tes()
	{
		echo disc_scp('2019-09-06','GB2','BB');
	}
	public function view()
	{				
		$data['isi']   = $this->isi;		
		$data['page']  = $this->page;		
		$id            = $this->input->get('id');			
		$data['title'] = "Detail ".$this->title;															
		$data['set']   = "detail";			
		$no_do    = $this->input->get('id');							

		$inv = $data['dt_invoice']	= $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
				INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
				INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
		 		WHERE tr_invoice_dealer.no_do = '$no_do'");
		$inv = $inv->row();
		$dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.deskripsi_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$inv->no_do' AND qty_do>0");
                  $to=0;$po=0;$do=0;
        foreach($dt_do_reg->result() as $isi){
        	$total_harga = $isi->harga * $isi->qty_do;
            $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
              INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
              INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
              WHERE tr_invoice_dealer.no_do = '$isi->no_do'");
            if($get_d->num_rows() > 0){
              $g = $get_d->row();
              $bunga_bank = $g->bunga_bank/100;
              $top_unit = $g->top_unit;
              $dealer_financing = $g->dealer_financing;
            }else{
              $bunga_bank = "";
              $top_unit = "";
              $dealer_financing = "";
            }

            $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                          WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
	        if($cek2->num_rows() > 0){
	          $d = $cek2->row();
	          $potongan = $d->jum;
	        }else{
	          $potongan = 0;
	        }
	        $date = date('Y-m-d');
	        $item = $this->db->query("SELECT ms_item.*,deskripsi_ahm,warna FROM ms_item
	        	JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
				JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
				WHERE id_item='$isi->id_item'
			")->row();
	        $disc_scp= $isi->disc_scp==null?disc_scp($date,$item->id_tipe_kendaraan,$item->id_warna):$isi->disc_scp;

	        $details[] =['id_item'=>$isi->id_item,
							'deskripsi_ahm' =>strip_tags($item->deskripsi_ahm),
							'warna'         =>$item->warna,
							'qty_do'        =>$isi->qty_do,
							'harga'         =>$isi->harga,
							'diskon_unit'   =>$potongan+$isi->disc,
							'disc_tambahan' => $isi->disc_tambahan,
							'disc_scp'      =>$disc_scp,
	    				];
	        // $to = $to + $total_harga;                    
         //    $po = $po + $pot;                    
         //    $do = $do + $isi->qty_do; 
        }
		$data['details']          = $details;
		$data['bunga_bank']       = $bunga_bank;
		$data['status_invoice']       = $inv->status_invoice;
		$data['top_unit']         = $top_unit;
		$data['dealer_financing'] = $dealer_financing;
		$this->template($data);			
	}

	public function get_no_faktur()
	{
		 $tgl 						= date("d");
		 $cek_tgl					= date("Y-m-d");
		 $th 						= date("Y");
		 $bln 						= date("m");	
		 $hr 						= date("d");	
		 
		 $pr_num = $this->db->query("SELECT *,LEFT(tgl_faktur,4)as tgl FROM tr_invoice_dealer WHERE LEFT(tgl_faktur,4)='$th' ORDER BY no_faktur DESC LIMIT 0,1");	
		 $kd_md = 'E20';					
		 if($pr_num->num_rows()>0){		
		 	$row 	= $pr_num->row();
		 	$id = explode('/', $row->no_faktur);
		 	if (count($id) > 1) {
		 		$kode 	= sprintf("%'.05d",$id[0]+1).'/FAK-'.$kd_md.'/'.$th;
		 	}else{
		 		$kode 	= '00001/FAK-'.$kd_md.'/'.$th;
		 	}				
		 }else{
		 		$kode 	= '00001/FAK-'.$kd_md.'/'.$th;
		 } 			
		 return $kode;
	}

	public function approve(){	
			$waktu                  = gmdate("y-m-d H:i:s", time()+60*60*7);
			$tgl_skrg               = gmdate("y-m-d", time()+60*60*7);
			$login_id               = $this->session->userdata('id_user');
			$ubah_mode              = "";
			$tabel                  = $this->tables;
			$pk                     = $this->pk;			
			$no_do                  = $this->input->post('no_do');			
			$data['status_invoice'] = "approved";
			$data['updated_at']     = $waktu;		
			$data['updated_by']     = $login_id;			
			//$this->m_admin->update($tabel,$data,"no_do",$no_do);

			$rt = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
						INNER JOIN ms_gudang ON tr_do_po.id_gudang=ms_gudang.id_gudang
						WHERE tr_do_po.no_do = '$no_do'")->row();
			
			$tgl						= date("d");
			$bln 						= date("m");		
			$th 						= date("Y");		
			$kd = "PL-";
			$pr_num = $this->db->query("SELECT * FROM tr_picking_list ORDER BY no_picking_list DESC LIMIT 0,1");							
			if($pr_num->num_rows()>0){
				$row 	= $pr_num->row();				
				$pan  = strlen($row->no_picking_list)-5;
				$id 	= substr($row->no_picking_list,$pan,5)+1;	
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


			
			$id_r				= $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();
			$cek_plafon = $this->m_admin->getByID("ms_dealer","id_dealer",$id_r->id_dealer)->row();
      $plafon 		= $cek_plafon->plafon;
      $cek_isi 		= $this->db->query("SELECT SUM(qty_do * harga) AS total FROM tr_do_po_detail WHERE no_do = '$no_do'")->row();
      $isi = $cek_isi->total;
      if($isi >= $plafon){
        $status = "waiting approval";        
      }else{
      	$cek_due = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
      		WHERE tr_do_po.id_dealer = '$id_r->id_dealer' AND (tr_invoice_dealer.status_bayar <> 'lunas' OR tr_invoice_dealer.status_bayar IS NULL)
      		AND tr_invoice_dealer.tgl_overdue IS NOT NULL AND tr_invoice_dealer.no_faktur <> '-'
      		AND tr_invoice_dealer.tgl_overdue < '$tgl_skrg'
      		");
      	if($cek_due->num_rows() > 0){
      		$status = "waiting approval";
      		$ubah_mode = "overdue";
      		$no_overdue = "";$op=1;$jum=$cek_due->num_rows();
      		foreach ($cek_due->result() as $key) {
      			$no_overdue .= $key->no_faktur;
      			$op++;
      			if($op <= $jum){
	            $no_overdue .= ",";
	          }
      		}
      	}else{
      		$status = "approved";
      	}
      }	
      	// $status='approved';
			if($status == 'approved'){

				// hitung total Invoice 
				$total_harga = 0;
        $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
            ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
            ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
            ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do' AND qty_do>0");
        $to=0;$po=0;$do=0;
        foreach($dt_do_reg->result() as $key => $isi){

          //Menambahkan Diskon SCP & tambahan
        	$item = $this->db->query("SELECT ms_item.*,deskripsi_ahm,warna FROM ms_item
	        	JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
				JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
				WHERE id_item='$isi->id_item'
	        							")->row();
        	$tgl = date('Y-m-d');
	        $disc_scp= disc_scp($tgl,$item->id_tipe_kendaraan,$item->id_warna);
	        $disc_tambahan = $this->input->post('disc_tambahan_'.$isi->id_item);
         	$upd_do_detail_set_disc[] = ['id_do_po_detail'=> $isi->id_do_po_detail,
          							  	'disc_tambahan'=> $disc_tambahan,
          							  	'disc_scp'=> $disc_scp,
      								  ];
          

          $total_harga = $isi->harga * $isi->qty_do;

          $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
            INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
            WHERE tr_invoice_dealer.no_do = '$no_do'");
          if($get_d->num_rows() > 0){
            $g = $get_d->row();
            $bunga_bank = $g->bunga_bank/100;
            $top_unit = $g->top_unit;
            $dealer_financing = $g->dealer_financing;
          }else{
            $bunga_bank = "";
            $top_unit = "";
            $dealer_financing = "";
          }

          $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
          if($cek2->num_rows() > 0){
            $d = $cek2->row();
            $potongan = $d->jum;
          }else{
            $potongan = 0;
          }

          $pot = ($potongan + $isi->disc) * $isi->qty_do;                    
          $to = $to + $total_harga;                    
          $po = $po + $pot;                    
          $do = $do + $isi->qty_do;                    
        }                  
        $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+((1.1*$bunga_bank/360)*$top_unit));
        $diskon_top = ($to-$po)-$d;
        if($dealer_financing=='Ya') {
          $y = $d * 0.1;
          $total_bayar = $d + $y;
        }else{
          $y = $d * 0.1;
          $total_bayar = $d + $y;
        }         

				// $d1['no_faktur'] 				= rand(1111,9999);				
				$d1['no_faktur'] 				= $this->get_no_faktur();
				$tgl_faktur = $d1['tgl_faktur'] 			= gmdate("y-m-d", time()+60*60*7);


				$top 	= $rt->top_unit;
				$tgl_baru = date("y-m-d", strtotime("+".$top." days", strtotime($tgl_faktur))); 
				$d1['tgl_overdue'] 			= $tgl_baru;
				$d1['total_bayar'] 			= $total_bayar;

				$cek_pik = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                      ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                      ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                      ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do'");
				foreach($cek_pik->result() as $ui){
					if($ui->qty_do > 0){
	          for ($i=0;$i <= $ui->qty_do-1;$i++) {                       
	            $th = date("Y");	            
	            $cek = $this->db->query("SELECT * FROM tr_scan_barcode WHERE id_item ='$ui->id_item' AND status = 1 AND tipe = 'RFS' AND LEFT(fifo,4) <= '$th' ORDER BY fifo ASC LIMIT $i,1");
	            if($cek->num_rows() > 0){
	              foreach ($cek->result() as $isi) {                                                        
	                $dw['no_picking_list'] 	= $kode;
	                $dw['no_mesin'] 				= $isi->no_mesin;
	                $dw['id_item'] 					= $isi->id_item;
	                $dw['lokasi'] 					= $isi->lokasi;
	                $dw['slot'] 						= $isi->slot;
	                //$this->m_admin->insert("tr_pl_tmp",$dw);
	                $this->m_admin->insert("tr_picking_list_view",$dw);
	              }
	            }
	          }
	        }	

	        $tgl_pl 									= gmdate("y-m-d", time()+60*60*7);
					$da['no_do'] 							= $no_do;
					$da['no_picking_list'] 		= $kode;
					$da['tgl_pl'] 						= $tgl_pl;
					$da['biaya_pdi'] 					= $rt->biaya_pdi;
					$da['status'] 						= "input";			
					$da['created_at']					= $waktu;		
					$da['created_by']					= $login_id;
					$cek = $this->m_admin->getByID("tr_picking_list","no_do",$no_do);
					if($cek->num_rows() == 0){
						$this->m_admin->insert("tr_picking_list",$da);
						$cek_pl = $this->m_admin->getByID("tr_do_po_detail","no_do",$no_do);
						$cek_pik = $this->m_admin->getByID("tr_picking_list_detail","no_do",$no_do);
						if($cek_pik->num_rows() > 0){
							$this->db->query("DELETE FROM tr_picking_list_detail WHERE no_do = '$no_do'");
						}
						foreach ($cek_pl->result() as $isi) {
							$dt['no_picking_list'] = $kode;
							$dt['no_do'] = $no_do;
							$dt['id_item'] = $isi->id_item;
							$dt['qty_do'] = $isi->qty_do;
							$dt['harga'] = $isi->harga;
							$this->m_admin->insert("tr_picking_list_detail",$dt);
						}						
					} 

					$id_dealer 	= $rt->id_dealer;
		      $plafon 		= $rt->plafon;
		      $cek 				= $this->db->query("SELECT SUM(qty_do * harga) AS total FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$no_do'")->row();
		      $hasil 			= $plafon - $cek->total;
					if($hasil >= 0){
						$ubah_plafon = $this->db->query("UPDATE ms_dealer SET plafon = '$hasil' WHERE id_dealer = '$id_dealer'");
						$ubah_mode = "yes";
					}	      	     

					$cek_tmp = $this->m_admin->getByID("tr_picking_list_view","no_picking_list",$kode);	      
					foreach ($cek_tmp->result() as $ub) {					
		      	$ubah = $this->db->query("UPDATE tr_scan_barcode SET status = 2 WHERE no_mesin = '$ub->no_mesin'");	      
					}



				}			

				
				$d1['status_invoice'] 		= $status;			
				$d1['created_at']					= $waktu;		
				$d1['created_by']					= $login_id;
				$cek_invoice = $this->m_admin->getByID("tr_invoice_dealer","no_do",$no_do);
				if (isset($upd_do_detail_set_disc)) {
					$this->db->update_batch('tr_do_po_detail',$upd_do_detail_set_disc,'id_do_po_detail');
				}
				if($cek_invoice->num_rows() > 0){
					$ir =$cek_invoice->row();
					$id_invoice_dealer = $ir->id_invoice_dealer;
					$this->m_admin->update("tr_invoice_dealer",$d1,"id_invoice_dealer",$id_invoice_dealer);
				}else{
					$this->m_admin->insert("tr_invoice_dealer",$d1);	
				}
	      }

	      
			
			if($ubah_mode == "yes"){      	
      	$_SESSION['pesan'] 	= "Data has been approved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice_dealer_unit'>";	     
      }elseif($ubah_mode == "overdue"){
      	$_SESSION['pesan'] 	= "Maaf tidak bisa ter-approve, No Invoice ".$no_overdue." yang telah melewati tanggal jatuh tempo";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";								
      }else{
      	$_SESSION['pesan'] 	= "Plafon tidak boleh minus";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";								
      }
					
	}
	public function reject(){	
			$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->get('id');			
      //$this->m_admin->delete("tr_invoice_dealer","no_do",$no_do);
			$data['status_invoice'] = "reject finance";
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,"no_do",$no_do);

			$this->db->query("UPDATE tr_do_po SET status = 'reject finance' WHERE no_do = '$no_do'");

			$_SESSION['pesan'] 	= "Data has been rejected successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice_dealer_unit'>";		
	}
	public function save_tagih(){	
			$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->post('no_do');			
			$data['bank'] 				= $this->input->post('bank');			
			$data['bunga_bank'] 	= $this->input->post('bunga_bank');			
			$data['tgl_cair'] 		= $this->input->post('tgl_cair');			
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,"no_do",$no_do);		

			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice_dealer_unit'>";		
	}

	public function cetak(){
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$no_invoice 					= $this->input->get('id');							

		$get_d 	= $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
				INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
				INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
		 		WHERE tr_invoice_dealer.id_invoice_dealer = '$no_invoice'")->row();        
		
    if($get_d->tgl_faktur != "0000-00-00"){
      $tgl1 = $get_d->tgl_faktur;// pendefinisian tanggal awal
      $top 	= $get_d->top_unit;
      $bunga_bank 	= $get_d->bunga_bank / 100;
      $tgl2 = date("Y-m-d", strtotime("+".$top." days", strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari                    
    }else{
      $tgl2 = "";
      $top = "";
      $bunga_bank = "";
    }
				
		$this->db->query("UPDATE tr_invoice_dealer SET status_invoice = 'printable' WHERE id_invoice_dealer = '$no_invoice'");					

		$md = $this->m_admin->getByID("ms_setting","id_setting",1)->row();                 

		$pdf = new FPDF('p','mm','A4');
    $pdf->AddPage();
       // head
	  $pdf->SetFont('TIMES','',17);
	  $pdf->Cell(190, 10, 'FAKTUR PENJUALAN', 0, 1, 'C');
	  // $pdf->SetFont('TIMES','',12);
	  // $pdf->Cell(50, 5, 'Main Dealer: PT.Sinar Sentosa Primatama', 0, 1, 'L');
	  // $pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
	  // $pdf->Cell(50, 5, 'Telp: 0741-61551', 0, 1, 'L');
	  // $pdf->Line(11, 31, 200, 31);
	   
	  //$pdf->Image(base_url().'/assets/panel/images/logo_sinsen.jpg', 150, 15, 50);
	   
	  $pdf->SetFont('TIMES','',12);
	  $pdf->Cell(1,2,'',0,1);
	  
	  $pdf->Cell(30, 5, 'Main Dealer ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$md->nama_perusahaan.'', 0, 0);	  
	  $pdf->Cell(30, 5, 'Hal ', 0, 0);	  	  
	  $pdf->Cell(10, 5, ': 1 dari 1', 0, 1);	  	  

	  $pdf->Cell(30, 5, 'Alamat ', 0, 0);	  
	  $pdf->Cell(70, 5, ': Jl.Kolonel Abunjani No.09 Jambi', 0, 0);	  
	  $pdf->Cell(30, 5, 'Tgl & Waktu ', 0, 0);	  	  
	  $pdf->Cell(10, 5, ': '.$get_d->tgl_do.'', 0, 1);	  	  

	  $pdf->Cell(30, 5, 'No Faktur ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$get_d->no_faktur.'', 0, 0);	  
	  $pdf->Cell(30, 5, 'Lokasi Waktu ', 0, 0);	  	  
	  $pdf->Cell(10, 5, ': '.$get_d->gudang.'', 0, 1);	  	  

	  $pdf->Cell(30, 5, 'Tgl Faktur ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$get_d->tgl_faktur.'', 0, 0);	  
	  $pdf->Cell(30, 5, 'Kode Cust ', 0, 0);	  	  
	  $pdf->Cell(10, 5, ': '.$get_d->kode_dealer_md.'', 0, 1);	  	  

	  $pdf->Cell(30, 5, 'Jatuh Tempo ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$tgl2.'', 0, 0);	  
	  $pdf->Cell(30, 5, 'NPWP ', 0, 0);	  	  
	  $pdf->Cell(10, 5, ': '.$get_d->npwp.'', 0, 1);	  	  

	  $pdf->Cell(30, 5, 'No Pesanan ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$get_d->no_do.'', 0, 0);	  
	  $pdf->Cell(30, 5, 'Kpd. Nama ', 0, 0);	  	  
	  $pdf->MultiCell(70, 5, ': '.$get_d->nama_dealer.'', 0, "L");	

	  $pdf->Cell(30, 5, 'Tgl Pesanan ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$get_d->tgl_do.'', 0, 0);	  
	  $pdf->Cell(30, 5, 'Alamat ', 0, 0);	  	  
	  //$pdf->MultiCell(186,5,"Pembayaran dengan Cek/Bilyet Giro/Transfer dianggap sah apabila telah diterima di rekening : ",0,"L");
	  $pdf->MultiCell(70, 5, ': '.$get_d->alamat.'', 0, "L");	  	  
	  	  
	  $pdf->Cell(30, 5, 'Pembayaran ', 0, 0);
	  $pdf->Cell(70, 5, ':', 0, 1);	  	  	  

	  // $pdf->Line(11, 68, 200, 68);	
	  $pdf->Cell(2,3,'',5,10);	  
	  $pdf->SetFont('TIMES','',12);
	   // buat tabel disini
	  $pdf->SetFont('TIMES','B',10);
	   
	   // kasi jarak
	  $pdf->Cell(2,5,'',5,10);	  
	   
	  $pdf->Cell(10, 5, 'No', 1, 0);
	  $pdf->Cell(20, 5, 'Item Motor', 1, 0);
	  $pdf->Cell(70, 5, 'Nama', 1, 0);
	  $pdf->Cell(15, 5, 'Jumlah', 1, 0);
	  $pdf->Cell(25, 5, 'Harga Kosong', 1, 0);
	  $pdf->Cell(20, 5, 'Diskon', 1, 0);
	  //$pdf->Cell(25, 5, 'Potongan', 1, 0);	  
	  $pdf->Cell(33, 5, 'Total Harga Satuan', 1, 1);	  

	  $pdf->SetFont('times','',10);
	  $get_nosin 	= $this->db->query("SELECT * FROM tr_do_po_detail INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
	  		INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  		INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
	  		WHERE tr_do_po_detail.no_do = '$get_d->no_do' AND tr_do_po_detail.qty_do > 0");
	  $i=1;$qt=0;$t=0;$p=0;$potongan=0;	  
	  $potongan=0;
	  foreach ($get_nosin->result() as $r)
	  {
	  	$cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
            WHERE tr_do_po_detail.no_do = '$get_d->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$r->id_item'");
	      if($cek2->num_rows() > 0){
	        $d = $cek2->row();
	        $po = $d->jum;
	      }else{
	        $po = 0;
	      }
	  	$tot_diskon = (($r->disc + $po + $r->disc_scp) * $r->qty_do) + $r->disc_tambahan;
	  	$pdf->Cell(10, 5, $i, 0, 0);
	    $pdf->Cell(20, 5, $r->id_item, 0, 0);
	    $pdf->Cell(70, 5, strip_tags($r->deskripsi_ahm)." / ".$r->warna, 0, 0);
	    $pdf->Cell(15, 5, $r->qty_do, 0, 0,'R');    
	    $pdf->Cell(25, 5, number_format($r->harga, 0, ',', '.'), 0, 0,'R');
	    $pdf->Cell(20, 5, number_format($tot_diskon, 0, ',', '.'), 0, 0,'R');
	    //$pdf->Cell(25, 5, $potongan, 1, 0); 
	    $pdf->Cell(30, 5, number_format($to = $r->harga * $r->qty_do, 0, ',', '.'), 0, 1,'R');
	  	$i++; 	   		    
	  	$qt = $qt + $r->qty_do;
	  	$t = $t + $to;

	  	$potongan = $potongan + $tot_diskon;
	  	//$p = $p + $potongan;
	  }
	   
	  $k = $get_nosin->num_rows();
	  $pdf->Cell(100, 5, 'Total', 0, 0,'R');
	  $pdf->Cell(15, 5, number_format($qt, 0, ',', '.'), 0, 0,'R');	  
	  $pdf->Cell(45, 5, "", 0, 0);	  
	  //$pdf->Cell(25, 5, "", 1, 0);	  
	  $pdf->Cell(30, 5, number_format($t, 0, ',', '.'), 0, 1,'R');

	  // $pdf->Cell(85, 5, '', 0, 0,'R');
	  // $pdf->Cell(15, 5, '', 0, 0);	  
	  // $pdf->Cell(25, 5, "Potongan", 0, 0);	  
	  // $pdf->Cell(25, 5, "", 0, 0);	  
	  // $pdf->Cell(30, 5, $p, 0, 1);
	  $pdf->Ln(2);

	  if ($get_d->dealer_financing=='Ya') {
	  //if ($gt_d->dealer_financing=='Tidak') {
	  	$pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Potongan", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($pot = $potongan, 0, ',', '.'), 0, 1,'R');

	  	$pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Diskon TOP", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	 
		  $d = (($t-$pot)-($bunga_bank/360*$top))/(1+((1.1*$bunga_bank/360)*$top));
		  $diskon_top = ($t-$pot)-$d;
		  $pdf->Cell(30, 5, number_format($diskon_top, 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "DPP", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($d, 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "PPN", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($hs = $d * 0.1, 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Total Bayar", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($hs + $d, 0, ',', '.'), 0, 1,'R');
	  }else{  	
		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Potongan", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($pot = $potongan, 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "DPP", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($d = $t - $potongan, 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "PPN", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($hs = $d * 0.1, 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Total Bayar", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($hs + $d, 0, ',', '.'), 0, 1,'R');

	  }
	  if ($get_d->dealer_financing=='Ya') {
	  	$pdf->SetY(200);
	  	$pdf->Cell(100, 5, 'Mohon dibuka giro,', 0, 1);
	  	$tgl = date("d-m-Y", strtotime("+14 days", strtotime($get_d->tgl_faktur)));
	  	$pdf->Cell(27, 5, 'Tgl Jatuh Tempo', 0, 0);
	  	$pdf->Cell(100, 5, ": $tgl", 0, 1);
	  	$pdf->Cell(27, 5, 'Atas Nama', 0, 0);
	  	$pdf->Cell(100, 5, ": $get_d->bank", 0, 1);
	  	$pdf->Cell(27, 5, 'Sebesar', 0, 0);
	  	// $sebesar = number_format($hs + $d + $diskon_top, 0, ',', '.');
	  	$sebesar = number_format($hs + $d, 0, ',', '.');
	  	$pdf->Cell(100, 5, ": $sebesar", 0, 1,'L');
	  }
	  
	   
	  $pdf->Output(); 
	}
	function download_file(){
				
		$k = $this->session->userdata('id_karyawan_dealer');
		$tgl 			= gmdate("dmY", time()+60*60*7);		
		$bulan 		= gmdate("mY", time()+60*60*7);		
		$folder 	= "downloads/sj/";
		$filename = $folder.$bulan;
		if (!file_exists($filename)) {
		  mkdir($folder.$bulan, 0777);		
		}
	        
	
		$data['no'] = "SSP-INV-".$tgl;		
		$data['tgl_cair'] = $this->input->post('tgl_cair');		
		$this->load->view("h1/file_inv",$data);
	}

	public function download()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$id 						= $this->input->get('id');			
		$data['title']	= "Download ".$this->title;															
		$data['set']		= "download";
		$this->template($data);			
	}	

	// public function download()
	// {
	// 	//$id = $this->input->get('id');		
	// 	$this->download_file();
		
	// }
	public function cari_data(){
		$id		= $this->input->post('id');
		$row = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
			INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
			WHERE tr_invoice_dealer.no_do = '$id'")->row();		
		echo $row->no_faktur."|".$row->nama_dealer."|".$row->no_do."|".$row->tgl_cair."|".$row->bank."|".$row->bunga_bank;
	}
}