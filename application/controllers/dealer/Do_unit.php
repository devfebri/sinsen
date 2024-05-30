<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Do_unit extends CI_Controller {

    var $tables =   "tr_do_po";	
		var $folder =   "h1";
		var $page		=		"do_unit";
    var $pk     =   "no_do";
    var $title  =   "Delivery Order (DO)";

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
		$this->load->library('upload');

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
		$data['dt_do'] = $this->db->query("SELECT *,tr_do_po.created_at FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer ORDER BY tr_do_po.created_at DESC");	
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");
		$data['dt_po'] = $this->db->query("SELECT * FROM tr_po_dealer INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer WHERE tr_po_dealer.status = 'input'");	
		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function get_slot(){
		$jenis	= $this->input->post('jenis_do');
		$sumber	= $this->input->post('sumber_do');
		if($jenis == 'po_reguler'){
			$jenis_r = "PO Reguler";
		}elseif($jenis == 'po_additional'){
			$jenis_r = "PO Additional";
		}elseif($jenis == 'po_indent'){
			$jenis_r = "PO Indent";
		}



		if($jenis != 'po_indent'){
			if($sumber == 'dealer'){
				$rt = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_pos_dealer = '' AND jenis_po = '$jenis_r' AND status = 'input' ORDER BY id_po ASc");
		    $data .= "<option value=''>- choose -</option>";
		    foreach($rt->result() as $val) {
		      $t = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$val->id_dealer'")->row();      
		      $data .= "<option value='$val->id_po'>$val->id_po | $val->tgl | $t->nama_dealer</option>\n";      
		    }
		   }else{
		   	$rt = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_pos_dealer <> '' AND jenis_po = '$jenis_r' AND status = 'input' ORDER BY id_po ASc");
		    $data .= "<option value=''>- choose -</option>";
		    foreach($rt->result() as $val) {
		      $t = $this->db->query("SELECT * FROM ms_pos_dealer WHERE id_pos_dealer = '$val->id_pos_dealer'")->row();      
		      $data .= "<option value='$val->id_po'>$val->id_po | $val->tgl | $t->nama_pos</option>\n";      
		    }
		   }
	  }else{
	  	$rt = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE status = 'sent' ORDER BY id_indent ASc");
	    $data .= "<option value=''>- choose -</option>";
	    foreach($rt->result() as $val) {
	      $t = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$val->id_dealer'")->row();      
	      $data .= "<option value='$val->id_indent'>$val->id_spk | $val->tgl | $t->nama_dealer</option>\n";      
	    }
	  }
    echo $data;
	}

	public function t_do_ind(){
		$id = $this->input->post('id_indent');
		$dq = "SELECT tr_po_dealer_indent.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_dealer_indent INNER JOIN ms_tipe_kendaraan						
						ON tr_po_dealer_indent.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIn ms_warna
						ON tr_po_dealer_indent.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_indent.id_indent = '$id'";
		$data['dt_do_ind'] = $this->db->query($dq);
		$data['id_indent'] = $id;		
		$data['no_do'] 		 = $this->input->post('no_do');		
		$this->load->view('h1/t_do_unit_ind',$data);
	}
	public function t_do_reg(){
		$id = $this->input->post('no_po');		
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.* FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_detail.id_po = '$id' AND (tr_po_dealer_detail.cek_do = '' OR tr_po_dealer_detail.cek_do IS NULL)";
		$data['dt_do_reg'] 	= $this->db->query($dq);			
		$data['id_dealer'] 	= $this->input->post("id_dealer");			
		$data['jenis_do'] 	= $this->input->post("jenis_do");			
		$data['no_po'] 			= $this->input->post("no_po");			
		$data['tanggal'] 		= $this->input->post("tanggal");			
		$this->load->view('h1/t_do_unit_reg',$data);					
	}

	public function t_do_reg_edit(){
		$id = $this->input->post('no_do');		
		$dq = "SELECT tr_do_po.*,tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail 
                    INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$id'";
		$data['dt_do_reg'] = $this->db->query($dq);				
		$data['id_dealer'] 	= $this->input->post("id_dealer");			
		$data['jenis_do'] 	= $this->input->post("jenis_do");			
		$this->load->view('h1/t_do_unit_reg_edit',$data);		
	}

	public function t_do_add(){
		$id = $this->input->post('no_po');		
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.* FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_dealer_detail.id_po = '$id'";
		$data['dt_do_add'] = $this->db->query($dq);			
		$this->load->view('h1/t_do_unit_add',$data);	
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");
		$data['dt_po'] = $this->db->query("SELECT * FROM tr_po_dealer INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer WHERE tr_po_dealer.status = 'input'");							
		$data['dt_gudang'] 	= $this->m_admin->getSortCond("ms_gudang","gudang","ASC");			
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->template($data);	
	}
	public function cari_dealer(){
		$id_indent = $this->input->post('id_indent');
		$sumber_do 	= $this->input->post('sumber_do');
		if($sumber_do == 'dealer'){
			$rf = $this->db->query("SELECT ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_po_dealer_indent.ket,ms_dealer.id_dealer FROM tr_po_dealer_indent INNER JOIN ms_dealer ON tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer WHERE id_indent = '$id_indent'")->row();
			$kode_dealer_md = $rf->kode_dealer_md;
			$nama_dealer 		= $rf->nama_dealer;
			$id_dealer 			= $rf->id_dealer;
			$ket 						= $rf->ket;
		}else{
			$rf = $this->db->query("SELECT ms_pos_dealer.id_pos_dealer,ms_pos_dealer.nama_pos,tr_po_dealer.ket FROM tr_po_dealer INNER JOIN ms_pos_dealer ON tr_po_dealer.id_pos_dealer = ms_pos_dealer.id_pos_dealer WHERE id_po = '$no_po'")->row();	
			$kode_dealer_md = $rf->id_pos_dealer;
			$nama_dealer 		= $rf->nama_pos;
			$id_dealer 			= $rf->id_pos_dealer;
			$ket 						= $rf->ket;
		}	
		echo $kode_dealer_md."|".$nama_dealer."|".$ket."|".$id_dealer;		
	}
	public function cari_dealer2(){
		$no_po 			= $this->input->post('no_po');
		$sumber_do 	= $this->input->post('sumber_do');
		if($sumber_do == 'dealer'){
			$rf = $this->db->query("SELECT ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_po_dealer.ket,ms_dealer.id_dealer FROM tr_po_dealer INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer WHERE id_po = '$no_po'")->row();	
			$kode_dealer_md = $rf->kode_dealer_md;
			$nama_dealer 		= $rf->nama_dealer;
			$id_dealer 			= $rf->id_dealer;
			$ket 						= $rf->ket;
		}else{
			$rf = $this->db->query("SELECT ms_pos_dealer.id_pos_dealer,ms_pos_dealer.nama_pos,tr_po_dealer.ket FROM tr_po_dealer INNER JOIN ms_pos_dealer ON tr_po_dealer.id_pos_dealer = ms_pos_dealer.id_pos_dealer WHERE id_po = '$no_po'")->row();	
			$kode_dealer_md = $rf->id_pos_dealer;
			$nama_dealer 		= $rf->nama_pos;
			$id_dealer 			= $rf->id_pos_dealer;
			$ket 						= $rf->ket;
		}
		
		echo $kode_dealer_md."|".$nama_dealer."|".$ket."|".$id_dealer;
	}
	public function cari_id(){
		$jenis_do				= $this->input->post('jenis_do');
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
		if($jenis_do == 'po_indent'){
			$kd = "ID-";
			$pr_num = $this->db->query("SELECT * FROM tr_do_indent ORDER BY no_do DESC LIMIT 0,1");							
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
		}elseif($jenis_do == 'po_reguler'){
			$kd = "RG-";
			$pr_num = $this->db->query("SELECT * FROM tr_do_po WHERE source = 'po_reguler' ORDER BY no_do DESC LIMIT 0,1");							
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
		}elseif($jenis_do == 'po_additional'){
			$kd = "AD-";
			$pr_num = $this->db->query("SELECT * FROM tr_do_po WHERE source = 'po_additional' ORDER BY no_do DESC LIMIT 0,1");							
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
		}else{
			$kode = "";
		}		
		 	
		echo $kode;
	}

	public function cek_item()
	{		
		$id_item = $this->input->post('id_item');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$sql = $this->db->query("SELECT ms_item.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM ms_item INNER JOIN ms_tipe_kendaraan
							ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
							ON ms_item.id_warna=ms_warna.id_warna WHERE ms_item.id_item = '$id_item'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();	
			$stock = $this->db->query("SELECT * FROM tr_real_stock WHERE id_tipe_kendaraan = '$dt_ve->id_tipe_kendaraan' AND id_warna = '$dt_ve->id_warna'");		
			if($stock->num_rows() > 0){
				$isi = $stock->row();
				$stok_onhand 	= $isi->stok_nrfs;
				$stok_rfs 		= $isi->stok_rfs;				
			}else{
				$stok_onhand = 0;
				$stok_rfs = 0;								
			}			
			$cek_harga = $this->db->query("SELECT * FROM tr_sipb WHERE id_tipe_kendaraan = '$dt_ve->id_tipe_kendaraan' AND id_warna = '$dt_ve->id_warna' ORDER BY tgl_sipb DESC");		
			if($cek_harga->num_rows() > 0){
				$isi2 = $cek_harga->row();
				$harga = $isi2->harga;
			}else{
				$harga = 0;
			}

			echo "ok"."|".$dt_ve->id_item."|".$dt_ve->tipe_ahm."|".$dt_ve->warna."|".$stok_onhand."|".$stok_rfs."|".$harga;			
		}else{
			echo "There is no data found!";
		}
	}
	public function cari_jenis()
	{				
		$th 						= $this->input->post('tahun');
		$bln 						= $this->input->post('bulan');
		$cek 	= $this->db->query("SELECT * FROM tr_do_dealer WHERE bulan = '$bln'AND tahun = '$th'");
		if($cek->num_rows() == 0){
			echo "PO Reguler"; 
		}else{
			echo "PO Additional"; 
		}
	}
	

	public function save_indent(){
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');

		$no_do							= $this->input->post('no_do');			
		$id_item						= $this->input->post('id_item');
		$id_indent					= $this->input->post('id_indent');
		$data['no_do']			= $this->input->post('no_do');			
		$data['id_item']		= $this->input->post('id_item');			
		$data['id_indent']	= $this->input->post('id_indent');					
		$data['qty_do']			= $this->input->post('qty_do');					
		$data['qty_on_hand']= $this->input->post('qty_on_hand');					
		$data['qty_rfs']		= $this->input->post('qty_rfs');					
		$data['harga']			= $this->input->post('harga');	

		$d['updated_at']		=	$waktu; 			
		$d['updated_by']		=	$login_id; 			
		$d['status']				=	"approved"; 			

		$cek = $this->db->get_where("tr_do_indent_detail",array("id_indent"=>$id_indent,"no_do"=>$no_do));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_do_indent_detail;
			$this->m_admin->update("tr_do_indent_detail",$data,"id_do_indent_detail",$id);			
		}else{
			$this->m_admin->insert("tr_do_indent_detail",$data);			

			$this->m_admin->update("tr_po_dealer_indent",$d,"id_indent",$id_indent);			
		}
		echo "nihil";
	}
	public function delete_do(){
		$jenis = $this->input->post('jenis');
		$id_do_detail 	= $this->input->post('id_do_detail');
		if($jenis == 'do_reg'){
			$this->db->query("DELETE FROM tr_do_po_detail WHERE id_do_po_detail = '$id_do_detail'");			
		}
		echo "nihil";
	}
	public function cancel_do(){
		$id_do			= $this->input->post('id_do');			
		$this->m_admin->delete("tr_do_dealer","id_do",$id_do);
		$this->m_admin->delete("tr_do_dealer_detail","id_do",$id_do);
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_do_po";		
		$no_do 			= $this->input->post('no_do');
		$data['no_do'] 	= $this->input->post('no_do');
		$no_do 					= $this->input->post('no_do');
		$no_po 					= $this->input->post('no_po');
		$data['ket'] 		= $this->input->post('ket');	
		$data['id_gudang'] 		= $this->input->post('id_gudang');	
		$data['id_dealer'] 		= $this->input->post('id_dealer');	
		$data['pengambilan'] 	= $this->input->post('pengambilan');	
		$data['tgl_do'] 			= $this->input->post('tanggal');	
		$data['no_po'] 				= $this->input->post('no_po');	
		$data['source'] 			= $this->input->post('jenis_do');	
		$data['status'] 			= "input";			
		$data['created_at']		= $waktu;		
		$data['created_by']		= $login_id;	
		
		$id_item				= $this->input->post("id_item");
		
		foreach($id_item AS $key => $val){
			$qty_do 			= $_POST['qty_po'][$key];
			$qty_on_hand 	= $_POST['qty_on_hand'][$key];
			$harga 				= $_POST['harga'][$key];
			
			if($qty_do > 0){
				$result_1[] = array(
					"no_do"  			=> $no_do,
					"id_item"  		=> $_POST['id_item'][$key],
					"qty_do"  		=> $_POST['qty_po'][$key],
					"qty_on_hand" => $_POST['qty_on_hand'][$key],
					"qty_rfs"  		=> $_POST['qty_rfs'][$key],
					"harga"  			=> $_POST['harga'][$key]
				);
			}
		}

		$testb= $this->db->insert_batch('tr_do_po_detail', $result_1);
		$this->m_admin->insert($tabel,$data);

		$cek_do_qty = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_po = '$no_po'");
		foreach ($cek_do_qty->result() as $ku) {
			$cek_po_qty = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE tr_po_dealer_detail.id_po = '$no_po' AND id_item = '$ku->id_item'")->row();
			if($ku->source == 'po_reguler'){
				$isi_po_qty = $cek_po_qty->qty_po_fix;
			}elseif($ku->source == 'po_additional'){	
				$isi_po_qty = $cek_po_qty->qty_order;
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
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";				
	}
	public function delete()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');		
		$amb = $this->m_admin->getByID("tr_do_po","no_do",$id)->row();
		$no_po = $amb->no_po;
		$ubah['status'] = "input";

		$cek_do = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_do = '$id'");
		foreach ($cek_do->result() as $ku) {
			$id_item 	= $ku->id_item;
			$cek			= '';
			$this->db->query("UPDATE tr_po_dealer_detail SET cek_do = '$cek' WHERE id_po = '$no_po' AND id_item = '$id_item'");
		}

		$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$no_po);
		
		$this->m_admin->delete("tr_do_po","no_do",$id);
		$this->m_admin->delete("tr_do_po_detail","no_do",$id);


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
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_do'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");		
		$data['dt_gudang'] 	= $this->m_admin->getSortCond("ms_gudang","gudang","ASC");			
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;				
		$data['dt_do'] 	= $this->db->query("SELECT * FROM tr_do_po WHERE no_do = '$id'");				
		$data['set']		= "edit";											
		$this->template($data);	
	}
	public function detail()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_do'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;				
		$data['dt_do'] 	= $this->db->query("SELECT * FROM tr_do_po WHERE no_do = '$id'");		
		$data['st']			= $this->input->get('s');									
		$data['set']		= "detail";									
		$this->template($data);	
	}
	public function update()
	{		
		$isi = $this->input->post('save');
		if($isi == 'update'){
			$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;
			$id					= $this->input->post("id");
			$no_do 			= $this->input->post('no_do');
			$no_po 			= $this->input->post('no_po');
			$data['no_do'] 	= $this->input->post('no_do');
			$data['ket'] 		= $this->input->post('ket');	
			$data['id_gudang'] 		= $this->input->post('id_gudang');	
			$data['id_dealer'] 		= $this->input->post('id_dealer');	
			$data['pengambilan'] 	= $this->input->post('pengambilan');	
			$data['tgl_do'] 			= $this->input->post('tanggal');	
			$data['no_po'] 				= $this->input->post('no_po');	
			$data['source'] 			= $this->input->post('jenis_do');			
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;

			$id_item				= $this->input->post("id_item");

			foreach($id_item AS $key => $val){
				$qty_do 			= $_POST['qty_po'][$key];
				$qty_on_hand 	= $_POST['qty_on_hand'][$key];
				$harga 				= $_POST['harga'][$key];
				
				if($qty_do > 0){
					$result_1[] = array(
						"no_do"  			=> $no_do,
						"id_item"  		=> $_POST['id_item'][$key],
						"qty_do"  		=> $_POST['qty_po'][$key],
						"qty_on_hand" => $_POST['qty_on_hand'][$key],
						"qty_rfs"  		=> $_POST['qty_rfs'][$key],
						"harga"  			=> $_POST['harga'][$key]
					);
				}
			}

			$this->db->query("DELETE FROM tr_do_po_detail WHERE no_do = '$no_do'");
			$testb= $this->db->insert_batch('tr_do_po_detail', $result_1);
			$this->m_admin->update($tabel,$data,$pk,$no_do);		

			$cek_do = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_po = '$no_po'");
			foreach ($cek_do->result() as $ku) {
				$id_item 	= $ku->id_item;
				$cek	= 'done';
				$this->db->query("UPDATE tr_po_dealer_detail SET cek_do = '$cek' WHERE id_po = '$no_po' AND id_item = '$id_item'");
			}

			$cek_po = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE tr_po_dealer_detail.id_po = '$no_po'");
			$jum_do = $cek_do->num_rows();
			$jum_po = $cek_po->num_rows();
			if($jum_po == $jum_do){
				$ubah['status'] = "waiting";
				$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$no_po);
				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";				
			}									
		}
	}
	public function approve(){	
			$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->get('no_do');			
			$data['status'] 			= "approved";
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$no_do);

			$cek_do_qty = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_po = '$no_po'");
			foreach ($cek_do_qty->result() as $ku) {
				$cek_po_qty = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE tr_po_dealer_detail.id_po = '$no_po' AND id_item = '$ku->id_item'")->row();
				if($ku->source == 'po_reguler'){
					$isi_po_qty = $cek_po_qty->qty_po_fix;
				}elseif($ku->source == 'po_additional'){	
					$isi_po_qty = $cek_po_qty->qty_order;
				}			
				$cek_tot_do = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS tot FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do
	      WHERE tr_do_po.no_po = '$no_po' AND tr_do_po_detail.id_item = '$ku->id_item'");
	      if($cek_tot_do->num_rows() > 0){
	      	$sisa_qty_do = $cek_tot_do->row();
	      	if($sisa_qty_do->tot == $isi_po_qty){
	        	$p = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do=tr_do_po_detail.no_do WHERE tr_do_po.no_do='$no_do'")->row();		
						$ubah['status'] = "approved";
						$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$p->no_po);
	      	}
	      }			
			}
			


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
			$tgl_pl 									= gmdate("y-m-d", time()+60*60*7);
			$da['no_do'] 							= $this->input->get('no_do');
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
					
			$id_ 				= $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();
			$cek_plafon = $this->m_admin->getByID("ms_dealer","id_dealer",$id_->id_dealer)->row();
      $plafon 		= $cek_plafon->plafon;
      $cek_isi 		= $this->db->query("SELECT SUM(qty_do * harga) AS total FROM tr_do_po_detail WHERE no_do = '$no_do'")->row();
      $isi = $cek_isi->total;
      if($isi >= $plafon){
        $status = "waiting approval";        
      }else{
      	$status = "approved";
      }

						
			if($status == 'approved'){
				$d1['no_faktur'] 				= rand(1111,9999);				
				$d1['tgl_faktur'] 			= gmdate("y-m-d", time()+60*60*7);

				$cek_pik = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                      ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                      ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                      ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do'");
				foreach($cek_pik->result() as $ui){
					if($ui->qty_do > 0){
	          for ($i=0;$i <= $ui->qty_do-1;$i++) {                       
	            $th = date("Y");	            
	            $cek = $this->db->query("SELECT * FROM tr_scan_barcode WHERE tipe_motor ='$ui->id_tipe_kendaraan' AND warna = '$ui->id_warna' AND status = 1 AND tipe = 'RFS' AND LEFT(fifo,4) = '$th' ORDER BY fifo ASC LIMIT $i,1");
	            if($cek->num_rows() > 0){
	              foreach ($cek->result() as $isi) {                                                        
	                $dw['no_pl'] = $kode;
	                $dw['nosin'] = $isi->no_mesin;
	                $this->m_admin->insert("tr_pl_tmp",$dw);
	              }
	            }
	          }
	        }
	      }

	      $id_dealer 	= $rt->id_dealer;
	      $plafon 		= $rt->plafon;
	      $cek 				= $this->db->query("SELECT SUM(qty_do * harga) AS total FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$no_do'")->row();
	      $hasil = $plafon - $cek->total;
	      $ubah_plafon = $this->db->query("UPDATE ms_dealer SET plafon = '$hasil' WHERE id_dealer = '$id_dealer'");	      

				$cek_tmp = $this->m_admin->getByID("tr_pl_tmp","no_pl",$kode);	      
				foreach ($cek_tmp->result() as $ub) {					
	      	$ubah = $this->db->query("UPDATE tr_scan_barcode SET status = 2 WHERE no_mesin = '$ub->nosin'");	      
				}



			}
			$d1['no_do'] 							= $this->input->get('no_do');			
			$d1['status_invoice'] 		= $status;			
			$d1['created_at']					= $waktu;		
			$d1['created_by']					= $login_id;
			$cek_invoice = $this->m_admin->getByID("tr_invoice_dealer","no_do",$no_do);
			if($cek_invoice->num_rows() > 0){
				$ir =$cek_invoice->row();
				$id_invoice_dealer = $ir->id_invoice_dealer;
				$this->m_admin->update("tr_invoice_dealer",$d1,"id_invoice_dealer",$id_invoice_dealer);
			}else{
				$this->m_admin->insert("tr_invoice_dealer",$d1);	
			}
			



			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";			
	}
	public function reject(){	
			$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->get('no_do');			
			$data['status'] 			= "rejected";
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$no_do);		

			$p = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do=tr_do_po_detail.no_do WHERE tr_do_po.no_do='$no_do'")->row();		
			$ubah['status'] = "rejected";
			$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$p->no_po);

			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";		
	}
	public function verify(){
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$no_do 			= $this->input->get('id');			
		$data['status'] 			= "approved";
		$data['updated_at']		= $waktu;		
		$data['updated_by']		= $login_id;			
		$this->m_admin->update($tabel,$data,$pk,$no_do);		
		$_SESSION['pesan'] 	= "Data has been rejected successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";
	}
	public function delete_indent(){		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_do 								= $this->input->post('id_do');			
		$id_indent 						= $this->input->post('id_indent');			

		$this->db->query("DELETE FROM tr_do_indent_detail WHERE id_do_indent_detail = '$id_do'");
		$data['status'] 			= "sent";
		$data['updated_at']		= $waktu;		
		$data['updated_by']		= $login_id;			
		$this->m_admin->update("tr_po_dealer_indent",$data,"id_indent",$id_indent);		
		echo "nihil";
	}	
}