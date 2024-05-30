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
		$this->load->model('m_invoice');		
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
	function mata_uang2($a){
        if(is_numeric($a) AND $a != 0 AND $a != ""){
          return number_format($a, 0, ',', '.');
        }else{
          return $a;
        }        
    }

	public function index()
	{				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "view";			
		$data['dt_invoice']	= $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
			WHERE tr_invoice_dealer.status_invoice <> 'printable' AND tr_invoice_dealer.status_invoice <> 'reject finance' and tr_do_po.status <> 'rejected' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");
		$this->template($data);			
	}
	public function normalisasi($no_do, $bunga_bank = 0){				
		$nosin = $this->m_admin->get_detail_inv_dealer($no_do,$bunga_bank);
		return $nosin['total_bayar'];    
	}
	public function normalisasi_new(){				
		$act = $this->input->get('act');
		// $nosin = $this->m_admin->get_detail_inv_dealer($no_do);
		// $tot = $nosin['total_bayar'];
		//echo $nosin['total_bayar'];
		$no=1;
		$sql = $this->db->query("SELECT * FROM tr_invoice_dealer WHERE status_bayar <> 'lunas' AND no_faktur <> '-' ORDER BY no_faktur ASC");		
		foreach ($sql->result() as $isi) {			
			$nosin = $this->m_admin->get_detail_inv_dealer($isi->no_do);
			$tot = $nosin['total_bayar'];
			//if($tot <> $isi->total_bayar){
				$cek1 = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail INNER JOIN tr_penerimaan_bank 
					ON tr_penerimaan_bank.id_penerimaan_bank = tr_penerimaan_bank_detail.id_penerimaan_bank 
					WHERE tr_penerimaan_bank_detail.referensi = '$isi->no_faktur' AND tr_penerimaan_bank.status = 'approved'")->row();
				if(!is_null($cek1->jum) AND $cek1->jum == $tot){
					$tot_asli = $cek1->jum;
					if(isset($act)){
						$this->db->query("UPDATE tr_invoice_dealer SET total_bayar = '$tot_asli', status_bayar = 'lunas' WHERE no_do = '$isi->no_do'");
					}
					echo $no.";".$isi->no_faktur.";".$isi->no_do.";".$isi->total_bayar.";".$tot."<br>";
					$no++;

				}
			//}
		}
    // kalau benar excelnya adalah lunas semua, maka ubah status bayar jadi lunas
	}
	public function normalisasi_cek(){					
		$no=1;
		$sql = $this->db->query("SELECT * FROM tr_invoice_dealer WHERE status_bayar <> 'lunas' AND no_faktur <> '-' ORDER BY no_faktur ASC");		
		foreach ($sql->result() as $isi) {			
			$nosin = $this->m_admin->get_detail_inv_dealer($isi->no_do);
			$tot = $nosin['total_bayar'];			
			$cek1 = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail INNER JOIN tr_penerimaan_bank 
				ON tr_penerimaan_bank.id_penerimaan_bank = tr_penerimaan_bank_detail.id_penerimaan_bank 
				WHERE tr_penerimaan_bank_detail.referensi = '$isi->no_faktur' AND tr_penerimaan_bank.status = 'approved'")->row();
			if(!is_null($cek1->jum) AND $cek1->jum == $tot){
				$tot_asli = $cek1->jum;
				if(isset($act)){
					$this->db->query("UPDATE tr_invoice_dealer SET total_bayar = '$tot_asli', status_bayar = 'lunas' WHERE no_do = '$isi->no_do'");
				}				
				$no++;
			}			
		}
		if($no == 1){
			return "nihil";
		}else{
			return "more";
		}
    // kalau benar excelnya adalah lunas semua, maka ubah status bayar jadi lunas
	}
	public function normalisasi_lagi(){				
		$no_do = $this->input->get('do');
		$nosin = $this->m_admin->get_detail_inv_dealer($no_do);		
		echo $nosin['total_bayar'];		
	}
	public function tes_lagi(){
		$no_do = $this->input->get('do');		
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
      // $disc_tambahan = $this->input->post('disc_tambahan_'.$isi->id_item);
     	// $upd_do_detail_set_disc[] = ['id_do_po_detail'=> $isi->id_do_po_detail,
      // 							  	'disc_tambahan'=> $disc_tambahan,
      // 							  	'disc_scp'=> $disc_scp,
  				// 				  ];
      //echo $isi->id_item."|".$no_do."|".$disc_scp."|".$disc_tambahan."<br>";
			$this->db->query("UPDATE tr_do_po_detail SET disc_scp = '$disc_scp' WHERE id_do_po_detail = '$isi->id_do_po_detail'");
    }
	}
	public function history_old(){				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "history";			
		$data['dt_invoice']	= $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
				WHERE tr_invoice_dealer.status_invoice = 'printable' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");
		$this->template($data);			
	}
	public function history(){				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "history_ulang";					
		$this->template($data);			
	}
	public function ajax_list()
	{
		$list = $this->m_invoice->get_datatables();
		$data = array();
		$no = $_POST['start'];
		$summary=0;
		//$id_dealer = 43;
		foreach ($list as $isi) {		
			$id_menu = $this->m_admin->getMenu($this->page);				
			$group = $this->session->userdata("group");
			$print = $this->m_admin->set_tombol($id_menu,$group,'print');
      if($isi->status_bayar == 'lunas'){
        $status_bayar = "<span class='label label-success'>Lunas</span>";
      }else{
        $status_bayar = "";
      }
      $approval = "";
      if($isi->status_invoice == 'waiting approval'){
        $status = "<span class='label label-warning'>$isi->status_invoice</span>";
        //$tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/approve?id=$row->no_do\">Approve</a>";
        $tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" href=\"h1/invoice_dealer_unit/view?id=$isi->no_do\" class=\"btn btn-success btn-xs btn-flat\">Approve</a>";
        $tampil2 = "<a $approval data-toggle=\"tooltip\" title=\"Reject Data\" onclick=\"return confirm('Are you sure to reject this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/reject?id=$isi->no_do\">Reject</a>";
        $tampil3 = "";            
        $tampil4 = "";
        $tampil5 = "";
      }elseif($isi->status_invoice=='rejected' OR $isi->status_invoice=='approved'){
        $status = "<span class='label label-danger'>$isi->status_invoice</span>";
        $tampil2 = "";              
        $tampil4 = "<button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
            onclick=\"input_tgl('$isi->no_do')\">Tgl Cair</button>";                            
        $tampil = "";
        $tampil3 = "";
        $tampil5 = "";
      }elseif($isi->status_invoice=='printable'){
        $status = "<span class='label label-success'>$isi->status_invoice</span>";
        $tampil2 = "";
        $tampil = "";
        $tampil4 = "";
        $tampil3 = "
        <button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
            onclick=\"input_tgl('$isi->no_do')\">Tgl Cair</button>

        <a $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$isi->id_invoice_dealer\">Print</a>
        ";
      }

      $tampil5 = "";

      if ($isi->status_bayar != 'lunas') {
      	$tampil5 = '
      	<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#mdl_'.$isi->id_invoice_dealer.'">Tgl Jatuh Tempo</button>

		  <div class="modal fade" id="mdl_'.$isi->id_invoice_dealer.'" role="dialog">
		    <div class="modal-dialog">
		    <form action="'.base_url().'h1/invoice_dealer_unit/edit_tgl_overdue/'.$isi->id_invoice_dealer.'">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Ubah Tanggal Jatuh Tempo</h4>
		        </div>
		        <div class="modal-body">
		        	<div class="form-group">
						<label>Tanggal Overdue</label>
						<input type="date" name="tgl_overdue" class="form-control" value="'.$isi->tgl_overdue.'">
					</div>
		        </div>
		        <div class="modal-footer">
		          <button type="submit" class="btn btn-info">Update</button>
		          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        </div>
		        </form>
		      </div>
		      
		    </div>
		  </div>

      	';
      }


			$row = $this->m_admin->getByID("tr_do_po","no_do",$isi->no_do)->row();
      $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row(); 
			$nosin = $this->m_admin->get_detail_inv_dealer($isi->no_do,0);
			$total_bayar2 = $nosin['total_bayar'];
			if($isi->status_invoice == 'printable'){				   
				$no++;
				$row = array();		
				$row[] = $no;				
				$row[] = $isi->no_faktur." ".$status_bayar;				
				$row[] = $isi->tgl_faktur;				
				$row[] = "<a href='h1/invoice_dealer_unit/view?id=$isi->no_do'>
                    $isi->no_do
                  </a>";				
				$row[] = $rt->nama_dealer;				
				$row[] = $this->mata_uang2($total_bayar2);				
				$row[] = $isi->tgl_cair;				
				$row[] = $status;				
				$row[] = $tampil." ".$tampil2." ".$tampil3." ".$tampil4." ".$tampil5;								
				$data[] = $row;
			}										
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_invoice->count_all(),
						"recordsFiltered" => $this->m_invoice->count_filtered(),
						"data" => $data,
						"summary" =>$summary
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit_tgl_overdue($id_invoice_dealer)
	{
		if ($_GET) {
			$tgl_overdue = $this->input->get('tgl_overdue');
			$this->db->where('id_invoice_dealer', $id_invoice_dealer);
			$this->db->update('tr_invoice_dealer', array('tgl_overdue'=>$tgl_overdue));
			?>
			<script type="text/javascript">
				alert("Tanggal Jatuh tempo berhasil diubah!");
				window.location="<?php echo base_url() ?>h1/invoice_dealer_unit/history";
			</script>
			<?php
		}
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
		 		WHERE tr_invoice_dealer.no_do = '$no_do'");
		$inv = $inv->row();

		if($inv->tgl_faktur =='0000-00-00'){
			$tgl_transaksi = date('Y-m-d');
		}else{
			$tgl_transaksi = $inv->tgl_faktur;
		}
		$persen =getPPN(false,$tgl_transaksi);

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
	        $th = date("Y");
	        $cek_rfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '1' AND tipe='RFS'");
	        $rfs	 = ($cek_rfs->num_rows() > 0) ? $cek_rfs->row()->jum : 0 ;	        
	        $disc_scp= $isi->disc_scp==null?disc_scp($date,$item->id_tipe_kendaraan,$item->id_warna):$isi->disc_scp;
	        $details[] =['id_item'=>$isi->id_item,
							'deskripsi_ahm' =>strip_tags($item->deskripsi_ahm),
							'warna'         =>$item->warna,
							'qty_do'        =>$isi->qty_do,
							'qty_rfs'       =>$rfs,
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
		$data['bunga_bank_awal']  = $bunga_bank;
		$data['status_invoice']   = $inv->status_invoice;
		$data['top_unit']         = $top_unit;
		$data['dealer_financing'] = $dealer_financing;
		$data['persen_ppn'] = $persen;

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

		if(isset($_POST['toleran'])){
			$toleran = "yes";
		}else{
			$toleran = "";
		}
		//$this->m_admin->update($tabel,$data,"no_do",$no_do);

		$rt = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
					INNER JOIN ms_gudang ON tr_do_po.id_gudang=ms_gudang.id_gudang
					WHERE tr_do_po.no_do = '$no_do'")->row();
		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");		
		$kd = "PL-";
		$pr_num = $this->db->query("SELECT * FROM tr_picking_list where left(created_at,4) = '$th' ORDER BY no_picking_list DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_picking_list)-5;
			$id 	= substr($row->no_picking_list,$pan,6)+1;	
			if($id < 10){
				$kode1 = $kd.$th.$bln."0000".$id;          
			}elseif($id>9 && $id<=99){
				$kode1 = $kd.$th.$bln."000".$id;                    
			}elseif($id>99 && $id<=999){
				$kode1 = $kd.$th.$bln."00".$id;          					          
			}elseif($id>999 && $id<=9999){
				$kode1 = $kd.$th.$bln."0".$id;                    
			}elseif($id>9999){
				$kode1 = $kd.$th.$bln."".$id;  
			}
			$kode = $kode1;
		}else{
			$kode = $kd.$th.$bln."00001";
		}		

		$dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
		ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
		ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
		ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do' AND qty_do>0");

		$to=0;$po=0;$do=0;$jmlh_potongan=0;

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
			$jmlh_potongan += $disc_tambahan;

			$upd_do_detail_set_disc[] = ['id_do_po_detail'=> $isi->id_do_po_detail,
				'disc_tambahan'=> $disc_tambahan,
				'disc_scp'=> $disc_scp,
				];
		}

		$id_r				= $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();
		$cek_plafon = $this->m_admin->getByID("ms_dealer","id_dealer",$id_r->id_dealer)->row();

		$id_dealers =$this->m_admin->cari_dealer($id_r->id_dealer);


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

		if($toleran == "yes"){      	
			$status = "approved";
		}

		if($no_do =='AD-20210807298'){
			echo $status;
		}	

		// $status='approved';
		if($status == 'approved'){
			if (isset($upd_do_detail_set_disc)) {
				$this->db->update_batch('tr_do_po_detail',$upd_do_detail_set_disc,'id_do_po_detail');
			}

			// hitung total Invoice 
			$total_harga = 0;
		
			// $d1['no_faktur'] 				= rand(1111,9999);				
			$d1['no_faktur'] 				= $this->get_no_faktur();
			$tgl_faktur = $d1['tgl_faktur'] 			= gmdate("y-m-d", time()+60*60*7);

			$total_bayar_fix = $this->normalisasi($no_do,$this->input->post('bunga_bank'));			

			if($toleran == "yes"){      	
				$top 	= 1;
			}else{
				$top 	= $rt->top_unit;
			}
			$tgl_baru = date("y-m-d", strtotime("+".$top." days", strtotime($tgl_faktur))); 
			$d1['tgl_overdue'] 			= $tgl_baru;
			$d1['total_bayar'] 			= $total_bayar_fix;

			$cek_pik = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
					ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
					ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
					ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do'");
			foreach($cek_pik->result() as $ui){
				if($ui->qty_do > 0){
					for ($i=0;$i <= $ui->qty_do-1;$i++) {                       
						$th = date("Y");
						$th1 = date("Y")+1;
						$cek = $this->db->query("SELECT * FROM tr_scan_barcode WHERE id_item ='$ui->id_item' AND status = 1 AND tipe = 'RFS' AND (LEFT(fifo,4) <= '$th' OR LEFT(fifo,4) <= '$th1') ORDER BY fifo ASC LIMIT $i,1");
						if($cek->num_rows() > 0){
							foreach ($cek->result() as $isi) {                                                        
								$dw['no_picking_list'] 	= $kode;
								$dw['no_mesin'] 				= $isi->no_mesin;
								$dw['id_item'] 					= $isi->id_item;
								$dw['lokasi'] 					= $isi->lokasi;
								$dw['slot'] 						= $isi->slot;	                
								$this->m_admin->insert('tr_picking_list_view',$dw);

								$this->db->select('tk.id_tipe_kendaraan, sb.qty');
								$this->db->from('ms_tipe_kendaraan tk');
								$this->db->join('ms_setting_part_battery_ev sb', 'tk.id_tipe_kendaraan = sb.id_tipe_kendaraan', 'left');
								$this->db->where('tk.id_tipe_kendaraan', $isi->tipe_motor);
								$this->db->where('tk.id_kategori', 'EV');
								$if_tipe_ev = $this->db->get();
			
								if($if_tipe_ev->num_rows() > 0){
									$qty_battery = $if_tipe_ev->row();
									$counter = 0;
									$set = $qty_battery;
									while ($counter < $set) {
										$this->db->select('*');
										$this->db->from('tr_stock_battery');
										$this->db->where('acc_status', '2');
										$this->db->where('ready_for_sale', 'rfs');
										$this->db->where('is_booking IS NULL');
										$this->db->where('no_surat_jalan IS NULL');
										$this->db->order_by('fifo', 'ASC');
										$this->db->limit(1);
										$querys = $this->db->get();
										$battery = $querys->row();
										$battery_in['no_picking_list'] 	        =$kode;
										$battery_in['id_part'] 				    =$battery->part_id;	
										$battery_in['nama_part'] 				=$battery->part_desc;	
										$battery_in['serial_number'] 			=$battery->serial_number;	
										$battery_in['no_do'] 			        =$no_do;
										$battery_in['status'] 			        ='input finance';
										$this->m_admin->insert("tr_picking_list_battery",$battery_in);
										$update_stock = array(
											'is_booking'=> '1',	
											'id_picking_list'=> $kode,
											'no_do'=> $no_do,
											'id_dealer'=>$id_dealers,
										);
										$this->m_admin->update("tr_stock_battery",$update_stock,"serial_number",$battery->serial_number);
									$counter++;
								}
								}
							}
						}
					}
				}	

				$tgl_pl 							= gmdate("y-m-d", time()+60*60*7);
				$da['no_do'] 						= $no_do;
				$da['no_picking_list'] 		        = $kode;
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
					$ubah_mode 	= "yes";
				}	      	     

				$cek_tmp = $this->m_admin->getByID("tr_picking_list_view","no_picking_list",$kode);	      
				foreach ($cek_tmp->result() as $ub) {					
					$ubah = $this->db->query("UPDATE tr_scan_barcode SET status = 2 WHERE no_mesin = '$ub->no_mesin'");	      
				}
			}			

			
			$d1['status_invoice'] = $status;			
			$d1['created_at']     = $waktu;		
			$d1['created_by']     = $login_id;
			$d1['bank']           = $this->input->post('bank');
			$d1['bunga_bank']     = $this->input->post('bunga_bank');
			$cek_invoice = $this->m_admin->getByID("tr_invoice_dealer","no_do",$no_do);
			if($cek_invoice->num_rows() > 0){
				$ir =$cek_invoice->row();
				$id_invoice_dealer = $ir->id_invoice_dealer;
				$this->m_admin->update("tr_invoice_dealer",$d1,"id_invoice_dealer",$id_invoice_dealer);
			}else{
				$this->m_admin->insert("tr_invoice_dealer",$d1);	
			}
		}// END STATUS APPROVED

		if($ubah_mode == "yes" OR $toleran == "yes"){      	
			$cek_indent = $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();
			if($cek_indent->source == 'po_indent'){
				$no_po = $cek_indent->no_po;
				$amb_spk = $this->m_admin->getByID("tr_po_dealer","id_po",$no_po)->row()->po_from;
				$this->db->query("UPDATE tr_po_dealer_indent SET status = 'closed' WHERE id_spk = '$amb_spk'");
			}

			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice_dealer_unit'>";	     
		}elseif($ubah_mode == "overdue" AND $toleran == ""){		  		
			$_SESSION['pesan'] 	= "Maaf tidak bisa ter-approve, No Invoice ".$no_overdue." yang telah melewati tanggal jatuh tempo";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";								     	
		}else{
			$_SESSION['pesan'] 	= "Plafon tidak boleh minus";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";								
		}			
	}

	public function inject(){
		$battery = $this->db->query("SELECT * FROM tr_stock_battery WHERE acc_status = '2' AND ready_for_sale = 'rfs' and is_booking is null and no_surat_jalan is null order by fifo ASC limit 1 ")->row();
		$battery_in['no_picking_list'] 	        ='PL-20240201276';
		$battery_in['id_part'] 				    =$battery->part_id;	
		$battery_in['nama_part'] 				=$battery->part_desc;	
		$battery_in['serial_number'] 			=$battery->serial_number;	
		$battery_in['no_do'] 			        ='AD-20240201300';
		$battery_in['status'] 			        ='input finance';
	
		$this->m_admin->insert("tr_picking_list_battery",$battery_in);

		$update_stock = array(
			'is_booking'=> '1',	
			'id_picking_list'=> 'PL-20240201276',
			'no_do'=> 'AD-20240201300',
			'id_dealer'=>'103',
		);

		$this->m_admin->update("tr_stock_battery",$update_stock,"serial_number",$battery->serial_number);
	}

	public function approve_tes(){	
		$waktu                  = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl_skrg               = gmdate("y-m-d", time()+60*60*7);
		$login_id               = $this->session->userdata('id_user');
		$ubah_mode              = "";
		$tabel                  = $this->tables;
		$pk                     = $this->pk;			
		$no_do                  = $this->input->get('no_do');			
		
		$rt = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
					INNER JOIN ms_gudang ON tr_do_po.id_gudang=ms_gudang.id_gudang
					WHERE tr_do_po.no_do = '$no_do'")->row();
		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");		
		$kd = "PL-";
		$pr_num = $this->db->query("SELECT * FROM tr_picking_list where left(created_at,4) = '$th' ORDER BY no_picking_list DESC LIMIT 0,1");							
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
		}

			
    $status = "approved";      
		if($status == 'approved'){
				$total_harga = 0;           
				$total_bayar_fix = $this->normalisasi($no_do);			
				
				$d1['total_bayar'] 			= $total_bayar_fix;

		}			
								
		$cek_invoice = $this->m_admin->getByID("tr_invoice_dealer","no_do",$no_do);
		
		if($cek_invoice->num_rows() > 0){
			$ir =$cek_invoice->row();
			$id_invoice_dealer = $ir->id_invoice_dealer;
			$this->m_admin->update("tr_invoice_dealer",$d1,"id_invoice_dealer",$id_invoice_dealer);
		}else{
			$this->m_admin->insert("tr_invoice_dealer",$d1);	
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
		if($get_d->pos == 'Ya'){
			$cek_pos = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$get_d->id_dealer_induk'");					
			$kode_dealer_md = ($cek_pos->num_rows() > 0) ? $cek_pos->row()->kode_dealer_md : "" ;
			$nama_dealer = ($cek_pos->num_rows() > 0) ? $cek_pos->row()->nama_dealer." (".$get_d->nama_dealer.")": "" ;
		}else{
			$kode_dealer_md = $get_d->kode_dealer_md;
			$nama_dealer = $get_d->nama_dealer;
		}

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

	  $getXcol1 = $pdf->GetX();
	  $getYcol1 = $pdf->GetY();
	  $pdf->Cell(30, 5, 'Main Dealer ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$md->nama_perusahaan.'', 0, 0);

	  $getXcol2 = $pdf->GetX();
	  $getYcol2 = $pdf->GetY();

	  $pdf->Cell(30, 5, 'Kode Pelanggan ', 0, 0);
	  $pdf->Cell(3, 5, ': ', 0, 0);		  	  
	  $pdf->Cell(10, 5, $kode_dealer_md, 0, 1);	
		
	  $pdf->setX($getXcol1+5);
	  $pdf->setY($getYcol1+5);
     
	  $pdf->Cell(30, 5, 'Alamat ', 0, 0);	  
	  $pdf->Cell(70, 5, ': Jl.Kolonel Abunjani No.09 Jambi', 0, 1);	
	  $pdf->Cell(30, 5, 'NPWP', 0, 0);	  
	  $pdf->Cell(70, 5, ': 03.074.830.5-331.000', 0, 1);  
	  $pdf->Cell(30, 5, 'No Pengukuhan', 0, 0);	  
	  $pdf->Cell(70, 5, ': No. PKP 03.074.830.5-331.000', 0, 1);  
	  $pdf->Cell(30, 5, 'Tgl Pengukuhan', 0, 0);	  
	  $pdf->Cell(70, 5, ': 30 Nopember 2010', 0, 1);
  	  $pdf->Cell(30, 5, 'No Faktur ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$get_d->no_faktur.'', 0, 1);	
  	  $pdf->Cell(30, 5, 'Tgl Faktur ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.date("d F Y",strtotime($get_d->tgl_faktur)).'', 0, 1);
	  $pdf->Cell(30, 5, 'Jatuh Tempo ', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.date("d F Y",strtotime($tgl2)).'', 0, 1);	 

          $pdf->SetXY($getXcol2,  $getYcol2+5);
	  $pdf->Cell(30, 5, 'Kpd. Nama ', 0, 0);
	  $pdf->Cell(3, 5, ': ', 0, 0);	
    	  $pdf->MultiCell(65, 5, $nama_dealer, 0, "L");

          $getYcol2 = $pdf->GetY();  	 
	  $pdf->ln();           
	  $pdf->SetXY($getXcol2,  $getYcol2);	  
	  $pdf->Cell(30, 5, 'Alamat ', 0, 0);
	  $pdf->Cell(3, 5, ': ', 0, 0);	
	  $pdf->MultiCell(67, 5, $get_d->alamat, 0, "L");

	  $getYcol2 = $pdf->GetY();  	 
	  $pdf->ln();           
	  $pdf->SetXY($getXcol2,  $getYcol2);	
 	  $pdf->Cell(30, 5, 'NPWP ', 0, 0);
	  $pdf->Cell(3, 5, ': ', 0, 0);		  	  
	  $pdf->Cell(10, 5, $get_d->npwp, 0, 1);

	  $getYcol2 = $pdf->GetY();  	 
	  $pdf->ln();           
	  $pdf->SetXY($getXcol2,  $getYcol2);	
	  $pdf->Cell(30, 5, 'No Pesanan ', 0, 0);
	  $pdf->Cell(3, 5, ': ', 0, 0);			  
	  $pdf->Cell(70, 5, $get_d->no_do, 0, 1);

	  $getYcol2 = $pdf->GetY();  	 
	  $pdf->ln();           
	  $pdf->SetXY($getXcol2,  $getYcol2);	
	  $pdf->Cell(30, 5, 'Tgl Pesanan ', 0, 0);
	  $pdf->Cell(3, 5, ': ', 0, 0);			  
	  $pdf->Cell(70, 5, date("d F Y",strtotime($get_d->tgl_do)), 0, 1);	
	  $pdf->ln();
  	  
	  //$pdf->MultiCell(186,5,"Pembayaran dengan Cek/Bilyet Giro/Transfer dianggap sah apabila telah diterima di rekening : ",0,"L");
	 	  
	  // $pdf->Cell(30, 5, 'Pembayaran ', 0, 0);
	  // $pdf->Cell(70, 5, ':', 0, 1);	  	  	  

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
	  $nosin = $this->m_admin->get_detail_inv_dealer($get_d->no_do,0);
	  $i=1;
	  foreach ($nosin['detail'] as $dtl) {
	  	$cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                      WHERE tr_do_po_detail.no_do = '$get_d->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$dtl[id_item]'");
      if($cek2->num_rows() > 0){
        $d = $cek2->row();
        $potongan = $d->jum;
      }else{
        $potongan = 0;
      }

      $dsc = $dtl['diskon_satuan'] + $potongan;
	  	$pdf->Cell(10, 5, $i, 0, 0);
	    $pdf->Cell(20, 5, $dtl['id_item'], 0, 0);
	    $pdf->Cell(70, 5, strip_tags($dtl['deskripsi_ahm'])." / ".$dtl['warna'], 0, 0);
	    $pdf->Cell(15, 5, $dtl['qty_do'], 0, 0,'R');    
	    $pdf->Cell(25, 5, number_format($dtl['harga'], 0, ',', '.'), 0, 0,'R');
	    $pdf->Cell(20, 5, number_format($dsc, 0, ',', '.'), 0, 0,'R');
	    $pdf->Cell(30, 5, number_format($dtl['subtotal'], 0, ',', '.'), 0, 1,'R');
	    $i++;
	  }
	  $pdf->Cell(100, 5, 'Total', 0, 0,'R');
	  $pdf->Cell(15, 5, number_format($nosin['total_qty'], 0, ',', '.'), 0, 0,'R');	  
	  $pdf->Cell(45, 5, "", 0, 0);	  
	  //$pdf->Cell(25, 5, "", 1, 0);	  
	  $pdf->Cell(30, 5, number_format($nosin['total_kotor'], 0, ',', '.'), 0, 1,'R');

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
		  $pdf->Cell(30, 5, number_format($nosin['total_diskon'], 0, ',', '.'), 0, 1,'R');

	  	$pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Diskon TOP", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	 
		  $pdf->Cell(30, 5, number_format($nosin['diskon_top'], 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "DPP", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($nosin['dpp'], 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "PPN", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($nosin['ppn'], 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Total Bayar", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($nosin['total_bayar'], 0, ',', '.'), 0, 1,'R');
	  }else{  	
		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Potongan", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($nosin['total_diskon'], 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "DPP", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($nosin['dpp'], 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "PPN", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($nosin['ppn'], 0, ',', '.'), 0, 1,'R');

		  $pdf->Cell(120, 5, '', 0, 0,'R');
		  $pdf->Cell(15, 5, '', 0, 0);	  
		  $pdf->Cell(25, 5, "Total Bayar", 0, 0);	  
		  //$pdf->Cell(25, 5, "", 0, 0);	  
		  $pdf->Cell(30, 5, number_format($nosin['total_bayar'], 0, ',', '.'), 0, 1,'R');

	  }

	  if ($get_d->dealer_financing=='Ya') {
	  	$pdf->SetY(230);
	  	$pdf->Cell(100, 5, 'Mohon dibuka giro,', 0, 1);
	  	$tgl = date("d-m-Y", strtotime("+14 days", strtotime($get_d->tgl_faktur)));
	  	$pdf->Cell(27, 5, 'Tgl Jatuh Tempo', 0, 0);
	  	$pdf->Cell(100, 5, ": ".date("d F Y",strtotime($tgl)), 0, 1);
	  	$pdf->Cell(27, 5, 'Atas Nama', 0, 0);
	  	$pdf->Cell(100, 5, ": $get_d->bank", 0, 1);
	  	$pdf->Cell(27, 5, 'Sebesar', 0, 0);
	  	// $sebesar = number_format($hs + $d + $diskon_top, 0, ',', '.');
	  	$sebesar = number_format($nosin['total_bayar'], 0, ',', '.');
	  	$pdf->Cell(100, 5, ": $sebesar", 0, 1,'L');
	  }
	  
	  $pdf->SetY(255);
	  $pdf->Cell(27, 5, 'KETENTUAN', 0, 1);
	  $pdf->Cell(27, 5, '1. Jika anda melakukan pembayaran dengan cara pemindah-bukuan ke Rekening bank kami, harap mencantumkan Nomor Faktur ini', 0, 1);
	  $pdf->Cell(27, 5, '2. Faktur ini bukan Bukti Pembayaran', 0, 1);
	  $pdf->AliasNbPages();	  
	  $pdf->Cell(194, 5, 'Hal '.$pdf->PageNo().' dari {nb}', 0, 1,'R');		
  
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


	function download_file_df_danamon(){
		$tgl = date("dmY");
		$data['no'] = "SSP-DF-".$tgl;		
		$data['tgl_faktur'] = $this->input->post('tgl_faktur');		
		$this->load->view("h1/file_df_danamon",$data);
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

	public function get_detail_piutang_dealer(){
		$no_do = $this->input->post('no_do');
		$id_dealer = $this->input->post('id_dealer');
		$tot_piutang = 0;    
	
		$dt_invoice = $this->db->query("SELECT tr_invoice_dealer.no_faktur, tr_invoice_dealer.tgl_overdue, tr_invoice_dealer.no_do, tr_do_po.id_dealer
			FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
			WHERE (tr_invoice_dealer.status_invoice = 'printable' OR tr_invoice_dealer.status_invoice = 'approved') AND  
			tr_invoice_dealer.status_bayar <> 'lunas' 
			AND tr_do_po.id_dealer=$id_dealer
			ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");

		if($dt_invoice->num_rows()>0){
			foreach($dt_invoice->result() as $row) {                                                     
				$rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
				$total_harga = 0;
				$total_harga = 0;
				$dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
					ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
					ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
					ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");

				$to=0;$po=0;$do=0;
				$get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
					INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
					INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
					WHERE tr_invoice_dealer.no_do = '$row->no_do'");

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

				if($get_d->row()->tgl_faktur =='0000-00-00'){
					$tgl_transaksi = date('Y-m-d');
				}else{
					$tgl_transaksi = $get_d->row()->tgl_faktur;
				}
					
				$persen_ppn = getPPN(false,$tgl_transaksi);

				foreach($dt_do_reg->result() as $isi){
					$total_harga = $isi->harga * $isi->qty_do;

					$pot = $isi->disc * $isi->qty_do;                    
					$to = $to + $total_harga;                    
					$po = $po + $pot;                    
					$do = $do + $isi->qty_do;                    
				}        

				$d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+(( (($persen_ppn/100)+1) *$bunga_bank/360)*$top_unit));
				$diskon_top = ($to-$po)-$d;
				if($dealer_financing=='Ya') {
					$y = $d * ($persen_ppn/100);
					$total_bayar = $d + $y;
				}else{
					$y = $d * ($persen_ppn/100);
					$total_bayar = $d + $y;
				}  

				$cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar);
				if ($cek>0) {
					echo "          
						<tr>               
							<td>$row->no_faktur</td>                            
							<td>$row->tgl_overdue</td>                            
							<td>$rt->nama_dealer</td>
							<td align='right'>".$this->mata_uang2($cek)."</td>             
						</tr>";
					$tot_piutang = $tot_piutang + $cek;
				}    
			}
		}

		$dt_rekap = $this->db->query("SELECT tr_monout_piutang_bbn.*,tr_pengajuan_bbn.id_dealer
		FROM tr_monout_piutang_bbn 
		INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
		JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
		WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved' and tr_faktur_stnk.status_bayar !='lunas'
		AND tr_pengajuan_bbn.id_dealer=$id_dealer
		");

		if($dt_rekap->num_rows()>0){
			foreach($dt_rekap->result() as $row) {                                         
				$dealer = $this->db->get_where('ms_dealer', ['id_dealer'=>$row->id_dealer])->row();
				$cek = $this->m_admin->cekPembayaran($row->no_bastd,$row->total);
				if ($cek>0){
					echo "          
					<tr>                                                 
						<td>$row->no_bastd</td>                            
						<td>$row->tgl_rekap</td>
						<td>$dealer->nama_dealer</td>
						<td align='right'>".$this->mata_uang2($cek)."</td>    
					</tr>                                      
					";
				$tot_piutang = $tot_piutang + $cek;  
				} 
			}
		}

		if($tot_piutang > 0){
			echo "          
			<tr>
				<td colspan=\"3\"></td>
				<td align='right'>".$this->mata_uang2($tot_piutang)."</td>
			</tr>                                     
			";

		}else{
			echo "          
				<tr>                                                 
					<td colspan=\"4\">Tidak ada data.</td>
				</tr>                                      
			";
		}
	}
}