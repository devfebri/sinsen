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
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h1_model_do','m_do');		
		//===== Load Library =====
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
		$data['set']		= "view";		
		// $data['dt_do'] = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer ORDER BY RIGHT(tr_do_po.no_do,11) DESC");	
		$data['dt_do'] = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer ORDER BY tr_do_po.id DESC");	
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");
		$data['dt_po'] = $this->db->query("SELECT * FROM tr_po_dealer INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer WHERE tr_po_dealer.status = 'input'");	
		$this->template($data);	
		$_SESSION['no_do']  = "";
		//$this->load->view('trans/logistik',$data);
	}

	public function tes()
	{				
		$data['isi']    = "do_unit_new";		
		$data['title']	= $this->title;															
		$data['set']		= "view";		
		// $data['dt_do'] = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer ORDER BY RIGHT(tr_do_po.no_do,11) DESC");	
		$data['dt_do'] = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer ORDER BY tr_do_po.id DESC");	
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");
		$data['dt_po'] = $this->db->query("SELECT * FROM tr_po_dealer INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer WHERE tr_po_dealer.status = 'input'");	
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{						
			$data['id_menu'] = $this->m_admin->getMenu("do_unit");
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/do_unit_new");		
			$this->load->view('template/footer');
		}
		$_SESSION['no_do']  = "";
		//$this->load->view('trans/logistik',$data);
	}

	public function getData()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

		if ($search != '') {
			if (strpos("waiting approval", $search) !== false) {
				$search = "input";
			}
		}

        $do = $this->m_do->filter($search, $limit, $start, $order_field, $order_ascdesc);
        // log_r($this->db->last_query());
        $data = array();
        foreach($do->result() as $row)
        {
        	if($row->status=='input'){
              $sts='do';
              $status = "<span class='label label-warning'>waiting approval</span>";
            }elseif($row->status=='approved'){
              $sts='do';
              $status = "<span class='label label-success'>$row->status</span>";
            }elseif($row->status=='rejected' OR $row->status == 'reject finance'){
              $sts = 'inv';
              $status = "<span class='label label-danger'>$row->status</span>";
            }
            if ($row->status=='rejected') {
              $sts='do';
            }
            if ($sts=='do') {
              $cek_inv = $this->db->query("SELECT * FROM tr_invoice_dealer WHERE no_do='$row->no_do' AND (status_invoice='approved' OR status_invoice='printable')");
              if ($cek_inv->num_rows()>0) {
                $status = "<span class='label label-success'>Approved By Finance</span>";
              }
            }
            $c_unit = $this->db->query("SELECT SUM(qty_do) AS jum, SUM(qty_do * harga) AS tot FROM tr_do_po_detail WHERE no_do = '$row->no_do'");
            if($c_unit->num_rows() > 0){
              $i_unit = $c_unit->row();
              $unit = $i_unit->jum;
              $harga = $i_unit->tot;
            }else{
              $unit = 0;
              $harga = 0;
            }

            $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$row->id_dealer'")->row();
            $id_user = $this->session->userdata("id_user");
            $cek = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user_group.id_user_group=ms_user.id_user_group
                WHERE ms_user.id_user = '$id_user'");
            if($cek->num_rows() > 0){
              $g = $cek->row();
              $level = $g->user_group;
            }else{
              $level = "";
            }
            $hasil = "";
            $id_menu = $this->m_admin->getMenu("do_unit");
            $group = $this->session->userdata("group");
            $tom = $this->m_admin->set_tombol($id_menu,$group,"approval");
            $tampil = "<a data-toggle=\"tooltip\" title=\"Approval Data\" $tom class=\"btn btn-success btn-sm btn-flat\" href=\"h1/do_unit/detail?s=approve&id=$row->no_do\"><i class=\"fa fa-check\"></i></a>";
            $btn = "";
            if ($row->status=='input') {
            	
            	if($row->source == 'po_indent'){
            		$btn = "<a data-toggle='tooltip' ".$this->m_admin->set_tombol($id_menu,$group,"delete")." title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/do_unit/delete?id=".$row->no_do."\"><i class=\"fa fa-trash-o\"></i></a>";
            	} else {
            		$btn = "<a data-toggle='tooltip' ".$this->m_admin->set_tombol($id_menu,$group,"update")." title=\"Edit Data\" class='btn btn-primary btn-sm btn-flat' href='h1/do_unit/edit?id=".$row->no_do."'><i class='fa fa-edit'></i></a>
                		<a data-toggle='tooltip' ".$this->m_admin->set_tombol($id_menu,$group,"delete")." title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/do_unit/delete?id=".$row->no_do."\"><i class=\"fa fa-trash-o\"></i></a>";
            	}

            	$btn = $tampil;

            }elseif($row->status=='rejected' AND ($level == 'admin' OR $level == 'Super Admin')){
            	$btn = "<a data-toggle='tooltip' ".$this->m_admin->set_tombol($id_menu,$group,"delete")." title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/do_unit/delete?id=".$row->no_do."\"><i class=\"fa fa-trash-o\"></i></a>
                <a data-toggle='tooltip' ".$this->m_admin->set_tombol($id_menu,$group,"update")." title=\"Edit Data\" class='btn btn-primary btn-sm btn-flat' href='h1/do_unit/edit?id=".$row->no_do."'><i class='fa fa-edit'></i></a>";
            }

            $data[]= array(
            	'',
                "<a href='h1/do_unit/detail?id=$row->no_do'>
                  $row->no_do
                </a>",
                ucwords(str_replace("_"," ",$row->source)),
                $dealer->kode_dealer_md,
                $row->nama_dealer,
                $row->tgl_do,
                number_format($harga,2,',','.'),
                $unit,
                $status,
                $btn,
            );     
        }
        $total_do = $this->m_do->count_filter($search);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_do,
            "recordsFiltered" => $total_do,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

	public function get_slot(){
		$jenis	= $this->input->post('jenis_do');
		$sumber	= $this->input->post('sumber_do');
		$id_dealer	= $this->input->post('id_dealer');
		if($jenis == 'po_reguler'){
			$jenis_r = "PO Reguler";
		}elseif($jenis == 'po_additional'){
			$jenis_r = "PO Additional";
		}elseif($jenis == 'po_indent'){
			$jenis_r = "PO Indent";
		}
		$data='';
		
		if($sumber == 'dealer'){
			$rt = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_pos_dealer = '' AND jenis_po = '$jenis_r' AND status = 'approved' and id_dealer='$id_dealer' ORDER BY id_po ASc");
	    $data .= "<option value=''>- choose -</option>";
	    foreach($rt->result() as $val) {
	      $t = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$val->id_dealer'")->row();      
	      $data .= "<option value='$val->id_po'>$val->id_po | $val->tgl | $t->nama_dealer</option>\n";      
	    }
	   }else{
	   	$rt = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_pos_dealer <> '' AND jenis_po = '$jenis_r' AND status = 'approved' and id_dealer='$id_dealer'ORDER BY id_po ASc");
	    $data .= "<option value=''>- choose -</option>";
	    foreach($rt->result() as $val) {
	      $t = $this->db->query("SELECT * FROM ms_pos_dealer WHERE id_pos_dealer = '$val->id_pos_dealer'")->row();      
	      $data .= "<option value='$val->id_po'>$val->id_po | $val->tgl | $t->nama_pos</option>\n";      
	    }
	   }
	  
    echo $data;
	}

	public function t_do_ind(){
		$id = $this->input->post('no_po');
		$data['id_dealer']  = $id_dealer	= $this->input->post("id_dealer");			
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.*,tr_spk.no_spk,tr_spk.nama_konsumen FROM tr_po_dealer_detail 
						INNER JOIN ms_item ON tr_po_dealer_detail.id_item=ms_item.id_item 
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
						INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
						INNER JOIN tr_po_dealer ON tr_po_dealer_detail.id_po = tr_po_dealer.id_po
						LEFT JOIN tr_spk ON tr_po_dealer.po_from = tr_spk.no_spk
						WHERE tr_po_dealer_detail.qty_order <> 0 AND tr_po_dealer_detail.id_po = '$id'";
		$data['dt_do_reg'] = $this->db->query($dq);
		$data['no_po'] = $id;		
		//$data['no_do'] 		 = $this->input->post('no_do');				
		$data['tanggal'] 		= $this->input->post("tanggal");			
		$this->load->view('h1/t_do_unit_indent',$data);
	}
	public function t_do_ind_edit(){
		$no_do 		 = $this->input->post('no_do');				
		$dq = "SELECT tr_do_po.*,tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail 
                    INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do'";
		$data['dt_do_ind'] = $this->db->query($dq);		
		$data['tanggal'] 		= $this->input->post("tanggal");			
		$data['id_dealer'] 	= $this->input->post("id_dealer");			
		$this->load->view('h1/t_do_unit_indent_edit',$data);
	}
	public function t_do_reg(){
		$id = $this->input->post('no_po');		
		$dq = "SELECT tr_po_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.* FROM tr_po_dealer_detail INNER JOIN ms_item 
						ON tr_po_dealer_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE (tr_po_dealer_detail.qty_po_fix <> 0 OR tr_po_dealer_detail.qty_order <> 0) AND tr_po_dealer_detail.id_po = '$id' AND (tr_po_dealer_detail.cek_do = '' OR tr_po_dealer_detail.cek_do IS NULL)";
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
		$data['tanggal'] 		= $this->input->post("tanggal");			
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
		// $data['dt_po'] = $this->db->query("SELECT * FROM tr_po_dealer INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer WHERE tr_po_dealer.status = 'input'");							
		$data['dt_gudang'] 	= $this->m_admin->getSortCond("ms_gudang","gudang","ASC");			
		// $data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
		$this->db->order_by('nama_dealer','ASC');
		$data['dt_dealer'] 	= $this->db->get_where('ms_dealer',['h1'=>1]);			
		//$_SESSION['no_do']  = "";
		$this->template($data);	
	}
	public function cari_dealer(){
		$id_indent = $this->input->post('id_indent');
		$sumber_do 	= $this->input->post('sumber_do');
		if($sumber_do == 'dealer'){
			$rf = $this->db->query("SELECT ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_po_dealer_indent.ket,ms_dealer.id_dealer FROM tr_po_dealer_indent 
					INNER JOIN ms_dealer ON tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer WHERE id_indent = '$id_indent'")->row();
			$kode_dealer_md = $rf->kode_dealer_md;
			$nama_dealer 		= $rf->nama_dealer;
			$id_dealer 			= $rf->id_dealer;
			$ket 						= $rf->ket;
		}else{
			$rf = $this->db->query("SELECT ms_pos_dealer.id_pos_dealer,ms_pos_dealer.nama_pos,tr_po_dealer.ket FROM tr_po_dealer 
					INNER JOIN ms_pos_dealer ON tr_po_dealer.id_pos_dealer = ms_pos_dealer.id_pos_dealer WHERE id_po = '$no_po'")->row();	
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
			$amb = $this->db->query("SELECT ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_po_dealer.ket,ms_dealer.id_dealer FROM tr_po_dealer INNER JOIN ms_dealer ON tr_po_dealer.id_dealer = ms_dealer.id_dealer WHERE id_po = '$no_po'");
			if($amb->num_rows() > 0){
				$rf = $amb->row();
				$kode_dealer_md = $rf->kode_dealer_md;
				$nama_dealer 		= $rf->nama_dealer;
				$id_dealer 			= $rf->id_dealer;
				$ket 						= $rf->ket;
			}else{
				$kode_dealer_md = "";
				$nama_dealer 		= "";
				$id_dealer 			= "";
				$ket 						= "";
			}
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
		$kode 					= $this->cari_id_real($jenis_do);
		echo $kode;
	}
	public function cari_id_real($jenis_do){		
		//$jenis_do				= $this->input->post('jenis_do');
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
		if($jenis_do == 'po_indent'){
			$kd = "ID-";
			$pr_num = $this->db->query("SELECT * FROM tr_do_po WHERE LEFT(tgl_do,4) = '$th' and source = 'po_additional' ORDER BY no_do DESC LIMIT 0,1");							
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
			$pr_num = $this->db->query("SELECT * FROM tr_do_po WHERE LEFT(tgl_do,4) = '$th' and  source = 'po_additional' ORDER BY no_do DESC LIMIT 0,1");							
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
			$pr_num = $this->db->query("SELECT * FROM tr_do_po WHERE LEFT(tgl_do,4) = '$th' and source = 'po_additional' ORDER BY no_do DESC LIMIT 0,1");							
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
		 	
		return $kode;
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
			$stock = $this->db->query("SELECT * FROM tr_real_stock WHERE id_item = '$id_item'");		
			if($stock->num_rows() > 0){
				$isi = $stock->row();
				$stok_onhand 	= $isi->stok_nrfs;
				$stok_rfs 		= $isi->stok_rfs;				
			}else{
				$stok_onhand = 0;
				$stok_rfs = 0;								
			}			
///---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
		$no_do_suggest = $this->cari_id_real("po_indent");		
		$no_do 			= $no_do_suggest;
		$no_do							= $no_do;
		$id_item             = $this->input->post('id_item');
		$id_indent           = $this->input->post('id_indent');
		$data['no_do']       = $no_do;
		$data['id_item']     = $this->input->post('id_item');			
		$data['id_indent']   = $this->input->post('id_indent');					
		$data['qty_do']      = $qty_do	= $this->input->post('qty_do');					
		$data['qty_on_hand'] = $this->input->post('qty_on_hand');					
		$data['qty_rfs']     = $this->input->post('qty_rfs');					
		$data['harga']       = $this->input->post('harga');	

		$d['updated_at'] =	$waktu; 			
		$d['updated_by'] =	$login_id; 			
		$d['status']     =	"approved"; 			

		if($qty_do > 0){

			$cek = $this->db->get_where("tr_do_indent_detail",array("id_indent"=>$id_indent,"no_do"=>$no_do));
			if($cek->num_rows() > 0){
				$sq = $cek->row();
				$id = $sq->id_do_indent_detail;
				$this->m_admin->update("tr_do_indent_detail",$data,"id_do_indent_detail",$id);			
			}else{
				$this->m_admin->insert("tr_do_indent_detail",$data);			
				// $this->m_admin->update("tr_po_dealer_indent",$d,"id_indent",$id_indent);			
			}

			$ds['no_do'] 				= $no_do;
			$ds['id_item'] 			= $this->input->post('id_item');
			$ds['qty_do'] 			= $this->input->post('qty_do');
			$ds['qty_on_hand'] 	= $this->input->post('qty_on_hand');
			$ds['qty_rfs'] 			= $this->input->post('qty_rfs');
			$ds['harga']	 			= $this->input->post('harga');			
			$ds['no_spk']	 = $no_spk = $this->input->post('no_spk');
			
			$testb = $this->m_admin->insert('tr_do_po_detail', $ds);		
			echo "nihil";
		}else{
			echo "Qty Do tidak boleh kosong";
		}

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
		//if($source_real!='po_indent'){
			$id_dealer 	= $this->input->post('id_dealer');	
		// }else{
		// 	if($t->num_rows() > 0){
		// 		$ty = $t->row();
		// 		$id_dealer 	= $ty->id_dealer;
		// 	}else{
		// 		$id_dealer = "";
		// 	}
		// }		
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
	public function delete()
	{				
		if($this->m_admin->cek_akses() == "aman"){				
			$tabel		= $this->tables;
			$pk 			= $this->pk;
			$id 			= $this->input->get('id');		
			$amb = $this->m_admin->getByID("tr_do_po","no_do",$id)->row();
			$no_po = $amb->no_po;
			$source = $amb->source;
			
		
			$ubah['status'] = "input";
			$cek_do = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_do = '$id'");
			foreach ($cek_do->result() as $ku) {
				$id_item 	= $ku->id_item;
				$cek			= '';
				$this->db->query("UPDATE tr_po_dealer_detail SET cek_do = '$cek' WHERE id_po = '$no_po' AND id_item = '$id_item'");
			}
			$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$no_po);
			
			if($source == 'po_indent'){
				$amb_spk = $this->m_admin->getByID("tr_po_dealer","id_po",$no_po)->row()->po_from;
				$this->db->query("UPDATE tr_po_dealer_indent SET status = 'requested' WHERE id_spk = '$amb_spk'");
			}
			
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
		// $data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->db->order_by('nama_dealer','ASC');
		$data['dt_dealer'] 	= $this->db->get_where('ms_dealer',['h1'=>1]);			
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
			$id_dealer 						= $this->input->post('id_dealer');	
			$data['id_dealer'] 		= $this->input->post('id_dealer');	
			$data['pengambilan'] 	= $this->input->post('pengambilan');	
			$tgl_do 							= $this->input->post('tanggal');	
			$data['tgl_do'] 			= $this->input->post('tanggal');	
			$data['no_po'] 				= $this->input->post('no_po');	
			$data['source'] 			= $this->input->post('jenis_do');			
			$data['status']				= "input";		
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;

			$id_item				= $this->input->post("id_item");

			foreach($id_item AS $key => $val){
				$qty_do 			= $_POST['qty_po'][$key];
				$qty_on_hand 	= $_POST['qty_on_hand'][$key];
				$harga 				= $_POST['harga'][$key];
				
				if($qty_do > 0){
					$id_item = $_POST['id_item'][$key];
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

					$result_1[] = array(
						"no_do"  			=> $no_do,
						"id_item"  		=> $_POST['id_item'][$key],
						"qty_do"  		=> $_POST['qty_po'][$key],
						"qty_on_hand" => $_POST['qty_on_hand'][$key],
						"qty_rfs"  		=> $_POST['qty_rfs'][$key],
						"harga"  			=> $_POST['harga'][$key],
						"disc"  			=> $disc
					);
				}
			}

			$this->db->query("DELETE FROM tr_do_po_detail WHERE no_do = '$no_do'");
			$testb= $this->db->insert_batch('tr_do_po_detail', $result_1);
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
														
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";				
		}
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
			$this->db->trans_begin();			
			
			$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
			$tgl_skrg 	= gmdate("y-m-d", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$ubah_mode  = "";
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->get('no_do');			
			$data['status'] 			= "approved";
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$no_do);
			$do = $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();

			
			$cek_do_qty = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_do = '$no_do'");
			foreach ($cek_do_qty->result() as $ku) {
				$cek_po_qty = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE tr_po_dealer_detail.id_po = '$ku->no_po' AND id_item = '$ku->id_item'")->row();				
				if($ku->source == 'po_reguler'){
					$isi_po_qty = $cek_po_qty->qty_po_fix;
				}elseif($ku->source == 'po_additional' OR $ku->source == 'po_indent'){	
					$isi_po_qty = $cek_po_qty->qty_order;
				}			
				$cek_tot_do = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS tot FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do
	      	WHERE tr_do_po.no_po = '$ku->no_po' AND tr_do_po_detail.id_item = '$ku->id_item'");
	      if($cek_tot_do->num_rows() > 0){
	      	$sisa_qty_do = $cek_tot_do->row();
	      	if($sisa_qty_do->tot == $isi_po_qty){
	        	$p = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do=tr_do_po_detail.no_do WHERE tr_do_po.no_do='$no_do'")->row();		
						$ubah['status'] = "approved";
						$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$p->no_po);
	      	}
	      }			
			}
			
			
			
			$no_do 										= $this->input->get('no_do');			
			$d1['no_do'] 							= $no_do;			
			//$d1['status_invoice'] 		= $status;			
			$d1['status_invoice'] 		= "waiting approval";			
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
			$list_do = $this->m_admin->getByID("tr_do_po_detail","no_do",$no_do);
			$data_do = $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();
			foreach ($list_do->result() as $isi) {
				$item = $this->m_admin->getByID("ms_item","id_item",$isi->id_item)->row();
				if(isset($item->id_tipe_kendaraan) AND $item->id_tipe_kendaraan != ""){
					$id_tipe_kendaraan = $item->id_tipe_kendaraan;
				}else{
					$id_tipe_kendaraan = "";
				}

				$bulan = intval(substr($data_do->tgl_do,5,2));
				$tahun = substr($data_do->tgl_do,0,4);
				$pot = $this->db->query("SELECT * FROM tr_quotation INNER JOIN tr_quotation_bulan ON tr_quotation.no_quotation = tr_quotation_bulan.no_quotation
					INNER JOIN tr_quotation_tipe ON tr_quotation.no_quotation = tr_quotation_tipe.no_quotation
					WHERE tr_quotation_tipe.id_tipe_kendaraan = '$id_tipe_kendaraan'
					AND tr_quotation_bulan.bulan = '$bulan' AND tr_quotation_bulan.tahun = '$tahun'");
				if($pot->num_rows() > 0){
					$y = $pot->row();
					$potongan = $y->nilai;
				}else{
					$potongan = 0;
				}

				$tr['no_do'] 		= $no_do;
				$tr['id_item'] 	= $isi->id_item."-".$bulan."-".$tahun."-".$id_tipe_kendaraan;
				$tr['qty_do'] 	= $isi->qty_do;
				$tr['potongan'] 			= $potongan * $isi->qty_do;
				$tr['tgl_potongan'] 	= $data_do->tgl_do;
				$cek_invoice_detail = $this->db->query("SELECT * FROM tr_invoice_dealer_detail WHERE no_do = '$no_do' AND id_item = '$isi->id_item'");
				if($cek_invoice_detail->num_rows() > 0){
					$ir =$cek_invoice_detail->row();
					$id_invoice_dealer_detail = $ir->id_invoice_dealer_detail;
					$this->m_admin->update("tr_invoice_dealer_detail",$tr,"id_invoice_dealer_detail",$id_invoice_dealer_detail);
				}else{
					$this->m_admin->insert("tr_invoice_dealer_detail",$tr);		
				}
			}


			$this->db->trans_commit();			
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();			
				$_SESSION['pesan'] 	= "Failed with unknown reason";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";	     
			}else{				
				$_SESSION['pesan'] 	= "Data has been approved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";	     
			}

	}
	public function approve_old(){	
			$this->db->trans_begin();			
			
			$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
			$tgl_skrg 	= gmdate("y-m-d", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$ubah_mode  = "";
			$tabel			= $this->tables;
			$pk 				= $this->pk;			
			$no_do 			= $this->input->get('no_do');			
			$data['status'] 			= "approved";
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;			
			$this->m_admin->update($tabel,$data,$pk,$no_do);
			$do = $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();

			if($do->source!="po_indent"){
				$cek_do_qty = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do WHERE tr_do_po.no_do = '$no_do'");
				foreach ($cek_do_qty->result() as $ku) {
					$cek_po_qty = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE tr_po_dealer_detail.id_po = '$ku->no_po' AND id_item = '$ku->id_item'")->row();				
					if($ku->source == 'po_reguler'){
						$isi_po_qty = $cek_po_qty->qty_po_fix;
					}elseif($ku->source == 'po_additional'){	
						$isi_po_qty = $cek_po_qty->qty_order;
					}			
					$cek_tot_do = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS tot FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do
		      	WHERE tr_do_po.no_po = '$ku->no_po' AND tr_do_po_detail.id_item = '$ku->id_item'");
		      if($cek_tot_do->num_rows() > 0){
		      	$sisa_qty_do = $cek_tot_do->row();
		      	if($sisa_qty_do->tot == $isi_po_qty){
		        	$p = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do=tr_do_po_detail.no_do WHERE tr_do_po.no_do='$no_do'")->row();		
							$ubah['status'] = "approved";
							$this->m_admin->update("tr_po_dealer",$ubah,"id_po",$p->no_po);
		      	}
		      }			
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
      	$cek_due = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
      		WHERE tr_do_po.id_dealer = '$id_->id_dealer' AND (tr_invoice_dealer.status_bayar <> 'lunas' OR tr_invoice_dealer.status_bayar IS NULL)
      		AND tr_invoice_dealer.tgl_overdue IS NOT NULL AND tr_invoice_dealer.no_faktur <> '-'
      		AND tr_invoice_dealer.tgl_overdue < '$tgl_skrg'");
      	if($cek_due->num_rows() > 0){
      		$status = "waiting approval";
      	}else{
      		$status = "approved";
      	}
      }

						
			if($status == 'approved'){
				$rt = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
						INNER JOIN ms_gudang ON tr_do_po.id_gudang=ms_gudang.id_gudang
						WHERE tr_do_po.no_do = '$no_do'")->row();
			
				$tgl						= date("d");
				$bln 						= date("m");		
				$th 						= date("Y");		
				$kd = "PL-";
				$pr_num = $this->db->query("SELECT * FROM tr_picking_list where LEFT(tgl_pl,4) = '$th' ORDER BY no_picking_list DESC LIMIT 0,1");							
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
				
				//cari total invoice
				$total_harga = 0;
        $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
            ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
            ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
            ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do'");
        $to=0;$po=0;$do=0;
        foreach($dt_do_reg->result() as $isi){
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

          $pot = $isi->disc * $isi->qty_do;                    
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

				// $d1['no_faktur'] 				= rand(1111,9999);				get_no_faktur
				$d1['no_faktur'] 				= $this->get_no_faktur();				
				$tgl_faktur = $d1['tgl_faktur'] 			= gmdate("y-m-d", time()+60*60*7);
				$top 	= $rt->top_unit;
				$tgl_baru = date("y-m-d", strtotime("+".$top." days", strtotime($tgl_faktur))); 
				$d1['tgl_overdue'] 			= $tgl_baru;
				$d1['total_bayar'] 			 = $total_bayar;

				$cek_pik = $this->db->query("SELECT tr_do_po_detail.*,ms_item.id_item,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                      ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                      ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                      ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do'");
				foreach($cek_pik->result() as $ui){
					if($ui->qty_do > 0){
						///custom API
						$cek_d = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
							WHERE tr_do_po.no_do = '$ui->no_do'")->row()->kode_dealer_md;
						if($cek_d == '00888' OR $cek_d == '12142'){

							$json = file_get_contents('http://www.sinarsentosa.co.id/sharing/update_no_mesin.php?id_item='.$ui->id_item);
							//$json = file_get_contents('http://monju.id/HONDA/assets/get_data/api_nosin.php');						
							$obj 	= json_decode($json,true);							
							$i=0;
							foreach($obj as $array){
								$kode_dealer =  $array['kode_dealer'];
								//$kode_dealer =  $cek_d;
								// $tgl_surat_jalan =  $array['tgl_surat_jalan'];								
								$no_mesin =  $array['no_mesin'];								
								
								if($kode_dealer == $cek_d){
									$isi = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin ='$no_mesin'")->row();
									if($ui->id_item == $isi->id_item){
										$dw['no_picking_list'] 	= $kode;
		                $dw['no_mesin'] 				= $isi->no_mesin;
		                $dw['id_item'] 					= $isi->id_item;
		                $dw['lokasi'] 					= $isi->lokasi;
		                $dw['slot'] 						= $isi->slot;	               
		                $this->m_admin->insert("tr_picking_list_view",$dw);	
		              }
								}
								$i++;
								if ($i == $ui->qty_do) break;
							}								  							
						//end custom API
						}else{							
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
	        }	        
	      }

	      

	      $id_dealer 	= $rt->id_dealer;
	      $plafon 		= $rt->plafon;
	      $cek 				= $this->db->query("SELECT SUM(qty_do * harga) AS total FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$no_do'")->row();
	      $hasil 			= $plafon - $cek->total;
	      if($hasil >= 0){
	      	$ubah_plafon 	= $this->db->query("UPDATE ms_dealer SET plafon = '$hasil' WHERE id_dealer = '$id_dealer'");	      
	      	$ubah_mode 		= "yes";
	      }

				$cek_tmp = $this->m_admin->getByID("tr_picking_list_view","no_picking_list",$kode);	      
				foreach ($cek_tmp->result() as $ub) {					
	      	$ubah = $this->db->query("UPDATE tr_scan_barcode SET status = 2 WHERE no_mesin = '$ub->no_mesin'");	      
				}



			}
			
			$no_do 										= $this->input->get('no_do');			
			$d1['no_do'] 							= $no_do;			
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
			$list_do = $this->m_admin->getByID("tr_do_po_detail","no_do",$no_do);
			$data_do = $this->m_admin->getByID("tr_do_po","no_do",$no_do)->row();
			foreach ($list_do->result() as $isi) {
				$item = $this->m_admin->getByID("ms_item","id_item",$isi->id_item)->row();
				if(isset($item->id_tipe_kendaraan) AND $item->id_tipe_kendaraan != ""){
					$id_tipe_kendaraan = $item->id_tipe_kendaraan;
				}else{
					$id_tipe_kendaraan = "";
				}

				$bulan = intval(substr($data_do->tgl_do,5,2));
				$tahun = substr($data_do->tgl_do,0,4);
				$pot = $this->db->query("SELECT * FROM tr_quotation INNER JOIN tr_quotation_bulan ON tr_quotation.no_quotation = tr_quotation_bulan.no_quotation
					INNER JOIN tr_quotation_tipe ON tr_quotation.no_quotation = tr_quotation_tipe.no_quotation
					WHERE tr_quotation_tipe.id_tipe_kendaraan = '$id_tipe_kendaraan'
					AND tr_quotation_bulan.bulan = '$bulan' AND tr_quotation_bulan.tahun = '$tahun'");
				if($pot->num_rows() > 0){
					$y = $pot->row();
					$potongan = $y->nilai;
				}else{
					$potongan = 0;
				}

				$tr['no_do'] 		= $no_do;
				$tr['id_item'] 	= $isi->id_item."-".$bulan."-".$tahun."-".$id_tipe_kendaraan;
				$tr['qty_do'] 	= $isi->qty_do;
				$tr['potongan'] 			= $potongan * $isi->qty_do;
				$tr['tgl_potongan'] 	= $data_do->tgl_do;
				$cek_invoice_detail = $this->db->query("SELECT * FROM tr_invoice_dealer_detail WHERE no_do = '$no_do' AND id_item = '$isi->id_item'");
				if($cek_invoice_detail->num_rows() > 0){
					$ir =$cek_invoice_detail->row();
					$id_invoice_dealer_detail = $ir->id_invoice_dealer_detail;
					$this->m_admin->update("tr_invoice_dealer_detail",$tr,"id_invoice_dealer_detail",$id_invoice_dealer_detail);
				}else{
					$this->m_admin->insert("tr_invoice_dealer_detail",$tr);		
				}
			}


			$this->db->trans_commit();			
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();			
				$_SESSION['pesan'] 	= "Failed with unknown reason";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";	     
			}else{				
				$_SESSION['pesan'] 	= "Data has been approved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/do_unit'>";	     
			}

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


			$rt = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
						INNER JOIN ms_gudang ON tr_do_po.id_gudang=ms_gudang.id_gudang
						WHERE tr_do_po.no_do = '$no_do'")->row();
		
			//kembalikan status nosin di scan barcode
			$cek 	= $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list=tr_picking_list_view.no_picking_list
				WHERE tr_picking_list.no_do = '$no_do'");
			if($cek->num_rows() > 0){
				foreach ($cek->result() as $ub) {					
	      	$ubah = $this->db->query("UPDATE tr_scan_barcode SET status = 1 WHERE no_mesin = '$ub->nosin'");	      
				}
			}
			
			
      //kembalikan status
			if($rt->source!="po_indent"){      
	      $ubah_status 	= $this->db->query("UPDATE tr_po_dealer SET status = 'input' WHERE id_po = '$rt->no_po'");	            	      		
	      $ubah_cek 		= $this->db->query("UPDATE tr_po_dealer_detail SET cek_do = '' WHERE id_po = '$rt->no_po'");	            	      					
	    }

      //kembalikan qty order
      //$delete_cek 	= $this->db->query("DELETE FROM tr_do_po_detail WHERE no_do = '$no_do'");	            	      					

			$_SESSION['pesan'] 	= "Data has been rejected successfully";
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
		$t = $this->db->query("SELECT * FROM tr_do_indent_detail WHERE id_do_indent_detail = '$id_do'")->row();    

		$this->db->query("DELETE FROM tr_do_po_detail WHERE no_do = '$t->no_do' AND id_item = '$t->id_item'");
		$this->db->query("DELETE FROM tr_do_indent_detail WHERE id_do_indent_detail = '$id_do'");
		
		$data['status'] 			= "sent";
		$data['updated_at']		= $waktu;		
		$data['updated_by']		= $login_id;			
		//$this->m_admin->update("tr_po_dealer_indent",$data,"id_indent",$id_indent);		
		echo "nihil";
	}		
}