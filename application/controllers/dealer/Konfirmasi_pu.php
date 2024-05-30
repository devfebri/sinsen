<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfirmasi_pu extends CI_Controller {

    var $tables =   "tr_penerimaan_unit_dealer";	
	var $folder =   "dealer";
	var $page	=	"konfirmasi_pu";
    var $pk     =   "id_penerimaan_unit_dealer";
    var $title  =   "Konfirmasi Penerimaan Unit";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_h1_dealer_konfirmasi_pu');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('PDF_HTML');
		$this->load->library('mpdf_l');		
		$this->load->library('cart');

		$this->load->library("udp_cart");//load library 
		$this->part_add    = new Udp_cart("part_add");

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

	public function oem_scan()
	{
		$id  = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_pu']  = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
						INNER JOIN tr_do_po ON tr_sppm.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_surat_jalan.no_surat_jalan = '$id' and tr_surat_jalan.id_dealer='$id_dealer' ");
		$data['isi']    = 'peneriamaan_oem';
		$data['title']	= "Penerimaan OEM";
		$data['set']	= "oem_scan";
		$_SESSION['pesan']              = "Mohon cek kodisi Battery sebelum Proses Scan Battery | Proses scan memilih RFS atau NRFS";
		$_SESSION['tipe']               = "warning";
		$data['dt_shipping_list'] = $this->db->query("SELECT * from tr_stock_battery where no_shipping_list ='$id' and acc_status > 3 ")->result();
		$this->template($data);
	}

	
	public function show_scan_battery()
	{		
		$this->load->model('ev_model');
		$no_surat_jalan = $this->input->post('no_surat_jalan');
		$sumber_kerusakan_ev = $this->input->post('sumber_kerusakan_ev');
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$id_dealer = $this->m_admin->cari_dealer();
		
		$where      = "WHERE 1=1 ";
		$wheresjson = "WHERE 1=1 ";
		$serial_number = $this->input->post('id');

		if (isset($no_surat_jalan)) {
			$wheresjson .= " AND sj.no_surat_jalan ='$no_surat_jalan'";
			$wheresjson .= " AND sj.id_dealer='$id_dealer'";
			$wheresjson .= " AND sb.acc_status > '3'";
		}
			$retur = 0;
			$val_sumber_kerusakan = NULL;
			$fs ='rfs';

		if (isset($val_sumber_kerusakan)) {
			$retur = 1;
			$val_sumber_kerusakan = $sumber_kerusakan_ev;
			$fs ='nrfs';
		}

		if (isset($serial_number)) {
			$id_user = $this->session->userdata('id_user');
		
			$get_battery = $this->db->query("SELECT * from tr_stock_battery where serial_number ='$serial_number' and acc_status = '3'")->row();
			$penerimaan  = $this->db->query("SELECT id_penerimaan_unit_dealer from tr_penerimaan_unit_dealer where no_surat_jalan ='$no_surat_jalan' ")->row();

			$battery_penerimaan = array(
			'id_penerimaan_battery_dealer_detail' =>NULL,
			'id_penerimaan_battery_dealer'		  =>$penerimaan->id_penerimaan_unit_dealer,    
			'serial_number'     =>$get_battery->serial_number,            
			'jenis_pu'          =>'B', 
			'id_dealer'         =>$id_dealer, 
			'fifo'      		=>$get_battery->fifo,            
			'status_dealer'     =>'input',              
			'no_surat_jalan'    =>$no_surat_jalan,             
			'sumber_kerusakan'  =>$val_sumber_kerusakan,            
			'retur'          	=>$retur,    
			'scan'          	=>1,    
			'ready_sale'        =>$fs,    
			'created_at'        =>$waktu,  
			'created_by'        =>$id_user  
			);

			$battery= array(
			'acc_status'=> 4,
			'tanggal_terima_dealer' =>$waktu
			);

			$this->m_admin->update("tr_stock_battery",$battery,"serial_number",$get_battery->serial_number);
			$this->m_admin->insert("tr_penerimaan_battery_dealer_detail",$battery_penerimaan);
	
			if (!$this->db->trans_status()) {
				$this->db->trans_rollback();
				$status = 0;
			} else {
				$status = 1;
				$this->db->trans_commit();

				$set_acc = array(
					'acc' => '4',
					'serial_number' => $serial_number,
					'user' => $id_user,
				);

				$update = array(
					'terima' => 'ya',
					'retur' => $retur
				);

				$this->m_admin->update("tr_surat_jalan_battery_detail", $update, "serial_number",$serial_number);
				$this->ev_model->InsertAcc($set_acc);
			}		
		}

		$query_result = $this->db->query("SELECT  sb.* 
		from tr_penerimaan_battery_dealer_detail pbd 
		left join tr_surat_jalan_battery_detail sjd on sjd.serial_number =pbd.serial_number 
		left join tr_surat_jalan sj on sj.no_surat_jalan  = sjd.no_surat_jalan 
		left join tr_stock_battery sb on sb.serial_number = sjd.serial_number
		$wheresjson 
		group by sb.serial_number
		");

		$result = $query_result->result_array(); 
        $response = array(
			'status' => $status,
			'data'   => $result
		);

        header('Content-Type: application/json');
        echo json_encode($response);
	}

	public function detail_scan_ev()
	{		
		$no_surat_jalan = $this->input->post('id');
		$data['isi']    = $this->page;	

		$where = "WHERE 1=1 ";
		
		if (isset($no_surat_jalan)) {
			$where .= " AND sjb.no_surat_jalan ='$no_surat_jalan'";
		}
		
		$data['dt_shipping_list']= $this->db->query("SELECT * from tr_surat_jalan_battery_detail sjb left join tr_stock_battery sb on sb.serial_number = sjb.serial_number
		$where and sb.acc_status ='3'");
		
		$this->load->view("dealer/t_scan_ev",$data);		
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;							
		$data['set']	= "view";
		$id_dealer = $this->m_admin->cari_dealer();

		if ($id_dealer == NULL){
			// $data['dt_sj'] = $this->db->query("SELECT tr_surat_jalan.*,tr_do_po.no_do,tr_do_po.tgl_do FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list=tr_picking_list.no_picking_list 
			// INNER JOIN tr_do_po ON tr_picking_list.no_do=tr_do_po.no_do
			// WHERE tr_surat_jalan.no_surat_jalan = '00486/SJ-E20-GD1/03/2024' ORDER BY tr_surat_jalan.id_surat_jalan DESC");	
		}else{
			$data['dt_sj'] = $this->db->query("SELECT tr_surat_jalan.*,tr_do_po.no_do,tr_do_po.tgl_do FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list=tr_picking_list.no_picking_list 
			INNER JOIN tr_do_po ON tr_picking_list.no_do=tr_do_po.no_do
			WHERE tr_surat_jalan.status = 'proses' AND tr_surat_jalan.id_dealer = '$id_dealer' ORDER BY tr_surat_jalan.id_surat_jalan DESC");	
		}

				
		/*
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL  AND retur = 0)");		
		*/
		//$this->normalisasi();
		$this->template($data);			
	}


	public function getData()
	{
		$search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
        $listData = $this->query_konfirmasi_pu($search, $limit, $start, $order_field, $order_ascdesc);
        $id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");
        $data = array();
        foreach($listData->result() as $row)
        {
            $data[]= array(
            		'',
                $row->no_spk,
                $row->nama_konsumen,
                $row->alamat,
                $row->no_hp,
                $row->no_ktp,
                "<a data-toggle='tooltip' ".$this->m_admin->set_tombol($id_menu,$group,"edit")." title='Edit Data' class='btn btn-primary btn-sm btn-flat' href='dealer/cdb_d/edit?id=".$row->id_cdb."'><i class='fa fa-edit'></i></a>"
            );     
        }
        $total_data = $this->count_filter($search);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
	}


	private function query_konfirmasi_pu($search, $limit, $start, $order_field, $order_ascdesc)
	{

		$cari = '';
		if ($search != '') {
			$cari = " AND ( 
				tr_cdb.no_spk LIKE '%$search%'
			 OR tr_spk.nama_konsumen LIKE '%$search%' 
			 OR tr_spk.alamat LIKE '%$search%' 
			 OR tr_spk.no_ktp LIKE '%$search%' 
			 OR tr_spk.no_hp LIKE '%$search%')";
		}

		$id_dealer = $this->m_admin->cari_dealer();
		$sql = "SELECT tr_penerimaan_unit_dealer.*,tr_surat_jalan.id_surat_jalan,tr_sppm.no_do,tr_surat_jalan.tgl_surat,tr_surat_jalan.no_surat_jalan,tr_sppm.tgl_do
							  FROM tr_penerimaan_unit_dealer LEFT JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan								
							  LEFT JOIN tr_sppm ON tr_sppm.no_surat_sppm = tr_surat_jalan.no_surat_sppm
								WHERE tr_surat_jalan.status = 'close' AND tr_surat_jalan.id_dealer = '$id_dealer'
								$cari
								ORDER BY $order_field $order_ascdesc
								LIMIT $start,$limit
		";

		return $this->db->query($sql);
	}

	private function count_filter($search)
	{
		$cari = '';
		if ($search != '') {
			$cari = "AND ( 
				tr_cdb.no_spk LIKE '%$search%'
			 OR tr_spk.nama_konsumen LIKE '%$search%' 
			 OR tr_spk.alamat LIKE '%$search%' 
			 OR tr_spk.no_ktp LIKE '%$search%' 
			 OR tr_spk.no_hp LIKE '%$search%') ";
		}
		$id_dealer = $this->m_admin->cari_dealer();
		$sql = "SELECT tr_cdb.id_cdb, tr_cdb.no_spk, tr_spk.nama_konsumen, tr_spk.alamat, tr_spk.no_hp, tr_spk.no_ktp
		 FROM tr_cdb INNER JOIn tr_spk ON tr_cdb.no_spk = tr_spk.no_spk WHERE tr_cdb.id_dealer = '$id_dealer' 
		$cari
		";
		return $this->db->query($sql)->num_rows();
	}



	public function normalisasi(){
		$id_dealer = $this->m_admin->cari_dealer();
		$cek = $this->db->query("SELECT *,tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer AS id_ku FROM tr_penerimaan_unit_dealer_detail 
				INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer_detail.id_sj = tr_surat_jalan.id_surat_jalan
				INNER JOIN tr_penerimaan_unit_dealer ON tr_surat_jalan.no_surat_jalan = tr_penerimaan_unit_dealer.no_surat_jalan
				WHERE tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer <> tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
				AND tr_penerimaan_unit_dealer_detail.id_sj <> 0 AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'");
		foreach ($cek->result() as $isi) {
			$this->db->query("UPDATE tr_penerimaan_unit_dealer_detail SET id_penerimaan_unit_dealer ='$isi->id_ku' WHERE id_sj = '$isi->id_sj'");
			$this->db->query("UPDATE tr_surat_jalan_detail SET terima = 'ya' WHERE no_mesin = '$isi->no_mesin'");				
			$this->db->query("UPDATE tr_scan_barcode SET status = '4' WHERE no_mesin = '$isi->no_mesin'");							
			$cek_ksu = $this->m_admin->getByID("tr_penerimaan_ksu_dealer","no_surat_jalan",$isi->no_surat_jalan);
			if($cek_ksu->num_rows() > 0){
				$this->db->query("UPDATE tr_penerimaan_ksu_dealer SET id_penerimaan_unit_dealer ='$isi->id_ku' WHERE no_surat_jalan = '$isi->no_surat_jalan'");	
			}			
		}
	}


	public function history_backup()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "history";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_sj'] = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,tr_surat_jalan.id_surat_jalan,tr_sppm.no_do,tr_surat_jalan.tgl_surat,tr_surat_jalan.no_surat_jalan,tr_sppm.tgl_do
							  FROM tr_penerimaan_unit_dealer LEFT JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan								
							  LEFT JOIN tr_sppm ON tr_sppm.no_surat_sppm = tr_surat_jalan.no_surat_sppm
								WHERE tr_surat_jalan.status = 'close' AND tr_surat_jalan.id_dealer = '$id_dealer' order by tr_penerimaan_unit_dealer.created_at desc limit 300");				
		/*
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		*/
		$this->template($data);			
	}


	
	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "history_server_side";


		$this->template($data);			
	}

	public function battery_history()
	{	
		$id 				= $this->input->get('id');	
		$data['isi']    = $this->page;		
		$data['title']	= 'History OEM Battery ';	
		$data['set']    = "history_battery";	
		$id_dealer = $this->m_admin->cari_dealer();
		$data['penerimaan_oem']= $this->db->query("SELECT pb.*,md.nama_dealer,md.kode_dealer_md,sb.part_desc from tr_penerimaan_battery_dealer_detail pb left join ms_dealer md on md.id_dealer = pb.id_dealer 
		left join tr_stock_battery sb on sb.serial_number = pb.serial_number
		where pb.id_dealer = '$id_dealer' and id_penerimaan_battery_dealer='$id' group by pb.serial_number ");
		$this->template($data);	
	}
	
	public function fetchData()
	{
	  $fetch_data = $this->_makeQuery();
	  $data = array();
	  $no = $this->input->post('start') + 1;
	  foreach ($fetch_data as $rs) {
		if ($rs->id_goods_receipt == 0) {
		  $status_sl                 = '<label class="label label-info">Received</label>';
		} else {
		  $status_sl                 = '<label class="label label-warning">Draft</label>';
		}

		$tombol =NULL;

		$tombol .="                                
		<a href='dealer/konfirmasi_pu/view?id=$rs->id_penerimaan_unit_dealer'>
		  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-eye'></i> Detail</button>
		</a>";

		$ev = $this->m_admin->getByID("tr_penerimaan_battery_dealer_detail", "id_penerimaan_battery_dealer", $rs->id_penerimaan_unit_dealer);

		if($ev->num_rows() > 0) {
			$tombol .="                                
			<a href='dealer/konfirmasi_pu/battery_history?id=$rs->id_penerimaan_unit_dealer'>
			  <button class='btn btn-flat btn-xs btn-default'><i class='fa fa-battery-full'></i> Battery</button>
			</a>";
		} 

		$href = "<a href='dealer/konfirmasi_pu/view?id=$rs->id_penerimaan_unit_dealer&p=n'>$rs->id_penerimaan_unit_dealer</a>";

		$inv = $this->m_admin->getByID("tr_invoice_dealer", "no_do", $rs->no_do);

		 if($inv->num_rows() > 0) {
			$id_                       = $inv->row();
			$invoice = $id_->no_faktur;
		  } 

		$sub_array   = array();
		$sub_array[] = $no;
		$sub_array[] = $href;
		$sub_array[] = $rs->id_goods_receipt;
		$sub_array[] = $rs->tgl_penerimaan;
		$sub_array[] = $rs->no_surat_jalan;
		$sub_array[] = $rs->tgl_surat_jalan;
		$sub_array[] = $invoice;
		$sub_array[] = $status_sl;
		$sub_array[] = $tombol;
		$data[]      = $sub_array;
		$no++;
	  }

	  $output = array(
		"draw"            => intval($_POST["draw"]),
		"recordsFiltered" => $this->_makeQuery(true),
		"recordsTotal" => $this->_makeQuery(true),
		"data"            => $data
	  );
	  echo json_encode($output);
	}

	function _makeQuery($recordsFiltered = false)
	{	
	  $start  = $this->input->post('start');
	  $length = $this->input->post('length');
	  $limit  = "LIMIT $start, $length";
	  if ($recordsFiltered == true) $limit = '';

	  $filter = [
		'limit'  => $limit,
		'order'  => isset($_POST['order']) ? $_POST['order'] : '',
		'search' => $this->input->post('search')['value'],
		'order_column' => 'view',
		'deleted' => false,
		'id_dealer' => $this->m_admin->cari_dealer(),
	  ];


	  if ($recordsFiltered == true) {
		return $this->m_h1_dealer_konfirmasi_pu->get($filter)->num_rows();
	  } else {
		return $this->m_h1_dealer_konfirmasi_pu->get($filter)->result();
	  }

	}


	public function view()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail Konfirmasi Penerimaan Unit";	
		$id 						= $this->input->get("id");	
		$cek_approval   = $this->m_admin->cek_approval($this->tables,$this->pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$data['set']		= "detail";	
			$data['dt_pu']	= $this->m_admin->getByID("tr_penerimaan_unit_dealer","id_penerimaan_unit_dealer",$id);

			$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
	    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
	            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
	            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");		
			$dq = "SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna FROM tr_penerimaan_unit_dealer_detail 
								INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin							
								INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
								INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
								WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' 
								AND tr_penerimaan_unit_dealer_detail.jenis_pu = 'rfs'";
			$data['dt_rfs'] = $this->db->query($dq);

			$dqe = "SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna FROM tr_penerimaan_unit_dealer_detail 
								INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin							
								INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
								INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
								WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' 
								AND tr_penerimaan_unit_dealer_detail.jenis_pu = 'nrfs'";
			$data['dt_nrfs'] = $this->db->query($dqe);		
			$this->template($data);	
		}		
	}
	
	public function gudang()
	{				
		$data['isi']    	= $this->page;		
		$data['title']		= "Data Penyimpanan";		
		$data['set']	   	= "gudang";							
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		$this->template($data);	
	}

	public function gudang_show()
	{				
		$data['isi']    	= $this->page;		
		$data['title']		= "Data Lokasi Penyimpanan";		
		$data['set']	   	= "gudang_show_on_menu_lokasi_penyimpanan";							
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		$this->template($data);	
	}
	public function ksu()
	{				
		$id = $this->input->get('id');
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan KSU";		
		$data['set']	   	= "ksu";	
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		$data['dt_ksu'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin
				INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
				INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan
				WHERE tr_surat_jalan.id_surat_jalan = '$id'");		
		// $data['v_ksu'] = $this->db->query("SELECT * FROM tr_surat_jalan_ksu INNER JOIN tr_surat_jalan ON tr_surat_jalan_ksu.no_surat_jalan=tr_surat_jalan.no_surat_jalan
		// 	INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
		// 	INNER JOIN ms_tipe_kendaraan ON tr_surat_jalan_ksu.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 	INNER JOIN ms_warna ON tr_surat_jalan_ksu.id_warna = ms_warna.id_warna			
		// 	WHERE tr_surat_jalan.id_surat_jalan = '$id'");
		$data['id_surat_jalan'] = $id;
		$data['ksu_d'] = $this->db->query("SELECT SUM(tr_surat_jalan_ksu.qty) as jum,ms_ksu.id_ksu,ms_ksu.ksu FROM tr_surat_jalan_ksu INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
			INNER JOIN tr_surat_jalan ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan.no_surat_jalan WHERE tr_surat_jalan.id_surat_jalan = '$id' GROUP BY tr_surat_jalan_ksu.id_ksu");
		$this->template($data);	
	}
	public function cetak_accu()
	{				
		$id = $this->input->get('id');
		$data['isi']    	= $this->page;		
		$data['title']		= "Cetak ACCU";		
		$data['set']	   	= "cetak_accu";	
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		$data['dt_ksu'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin
							INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
							INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan
							WHERE tr_surat_jalan.id_surat_jalan = '$id'");				
		$data['id_surat_jalan'] = $id;
		$data['ksu_d'] = $this->db->query("SELECT SUM(tr_surat_jalan_ksu.qty) as jum,ms_ksu.id_ksu,ms_ksu.ksu FROM tr_surat_jalan_ksu INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
							INNER JOIN tr_surat_jalan ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan.no_surat_jalan WHERE tr_surat_jalan.id_surat_jalan = '$id' GROUP BY tr_surat_jalan_ksu.id_ksu");
		$this->template($data);	
	}
	public function cetak_act(){
		$id 				= $this->input->get('id');		
		$id_p 			= $this->input->get('id_p');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$dt_stiker 	= $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
			INNER JOIN ms_ksu ON tr_penerimaan_ksu_dealer.id_ksu = ms_ksu.id_ksu
		 	WHERE tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = '$id_p' AND tr_penerimaan_ksu_dealer.id_ksu = '$id'");
		if ($dt_stiker->num_rows()>0) {			
			$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] = 'cetak_accu';    	    	
    	$data['isi_file'] = $dt_stiker->row();
    	$html = $this->load->view('dealer/cetak_accu', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_.pdf';
      $mpdf->Output("$output", 'I');


		 //  $pdf = new PDF_HTML('L','cm','A5');
		 //  //$pdf = new FPDF('L','cm',array(8.4,5.2));
		 //  $pdf->SetAutoPageBreak(false);
	     //   $pdf->AddPage();	       

		 //  $pdf->SetFont('ARIAL','',8);		  
		 //  $tgl_mohon 	= date("d F Y", strtotime($row->tgl_penerimaan));		
		 //  $pdf->Cell(0, 1, 'Tgl Penerimaan', 0, 0, 'L');$pdf->Cell(10, 1, ': '.strtoupper($tgl_mohon), 0, 1, 'L');
		 // 	$pdf->Cell(0, 1, 'Kode KSU', 0, 0, 'L');$pdf->Cell(0, 1, ': '.strtoupper($row->id_ksu), 0, 1, 'L');
			// $pdf->Cell(0, 1, 'Nama KSU', 0, 0, 'L');$pdf->Cell(0, 1, ': '.strtoupper($row->ksu), 0, 1, 'L');					 
	 	//   $pdf->Output(); 
		}
	}
	public function cetak_cover()
	{
		$pdf = new FPDF('L','inch',array(8.4,5.2));
	      $pdf->AddPage();
	      $pdf->SetAutoPageBreak(false);
		 //$pdf->SetMargins(8, 8, 8);
	      $id = $this->input->get('id');
	     $dt_so = $this->db->query("SELECT tr_sales_order.*,tr_spk.nama_bpkb as nama_bpkb1, tr_sales_order.no_mesin as no_mesinalias, 
	  				tr_spk.*,tr_prospek.*,tr_scan_barcode.id_item,
	  				ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm, ms_warna.warna,tr_scan_barcode.no_rangka, ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_sales_order.no_mesin,ms_karyawan_dealer.nama_lengkap as nama_sales,tr_spk.id_kelurahan,tr_spk.alamat FROM tr_sales_order 
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			
			LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer on tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE tr_sales_order.id_sales_order = '$id'
			");

	    if ($dt_so->num_rows() > 0) {
	    	$so=$dt_so->row();
	    	$fkb = $this->db->query("SELECT nomor_faktur from tr_fkb WHERE no_mesin_spasi='$so->no_mesin'");
			if ($fkb->num_rows() > 0) {
				$fkb = $fkb->row()->nomor_faktur;
			}else{
				$fkb='';
			}	


			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
		  		WHERE ms_kelurahan.id_kelurahan = '$so->id_kelurahan'")->row();

			$kelurahan 		= $dt_kel->kelurahan;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$kecamatan 		= $dt_kel->kecamatan;
			$id_kabupaten = $dt_kel->id_kabupaten;
			$kabupaten  	= $dt_kel->kabupaten;
			$id_provinsi  = $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;

	    $pdf->SetFont('ARIAL','B',13);
			$pdf->Cell(120, 5, 'No Faktur : '.$fkb, 0, 1, 'L');
			$pdf->Ln(6);

		    $pdf->SetFont('ARIAL','B',20);
			$pdf->Cell(190, 5, $so->nama_dealer, 0, 1, 'C');
			$pdf->Ln(9);
			$pdf->SetFont('ARIAL','B',18);
			$pdf->Cell(65, 9, 'NAMA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->nama_bpkb), 0, 1, 'L');
			$pdf->Cell(65, 9, 'ALAMAT', 0, 0, 'L');$pdf->Cell(3, 9, ':', 0, 0, 'L');
			$pdf->MultiCell(142, 9, strtoupper($so->alamat),0, 1);
			$pdf->Cell(65, 9, 'KELURAHAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($kelurahan), 0, 1, 'L');
			$pdf->Cell(65, 9, 'KECAMATAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($kecamatan), 0, 1, 'L');
			$pdf->Cell(65, 9, 'KOTA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($kabupaten), 0, 1, 'L');
			$pdf->Cell(65, 9, 'TYPE', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->tipe_ahm), 0, 1, 'L');
			$pdf->Cell(65, 9, 'DESK AHM', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->deskripsi_ahm), 0, 1, 'L');
			$pdf->Cell(65, 9, 'WARNA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->warna), 0, 1, 'L');
			$thn = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$so->no_mesin)->row();
			$pdf->Cell(65, 9, 'THN PRODUKSI', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($thn->tahun_produksi), 0, 1, 'L');
			$pdf->Cell(65, 9, 'NO. RANGKA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->no_rangka), 0, 1, 'L');
			$pdf->Cell(65, 9, 'NO. MESIN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->no_mesin), 0, 1, 'L');
	   	$tgl = date('d-m-Y', strtotime($so->tgl_cetak_so));
			$pdf->Cell(65, 9, 'TGL PEMBELIAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.$tgl, 0, 1, 'L');			
			if($so->jenis_beli == 'Kredit'){
				$ft = $this->m_admin->getByID("ms_finance_company","id_finance_company",$so->id_finance_company)->row();
				$fc = "( ".$ft->finance_company." )";
			}else{
				$fc = "";
			}
			$pdf->Cell(65, 9, 'PEMBAYARAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->jenis_beli).$fc, 0, 1, 'L');			
			$pdf->Cell(65, 9, 'SALES PEOPLE', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->nama_sales), 0, 1, 'L');
			//$pdf->Line(5,148.5,5,0);
			$pdf->Output(); 
	    }
	}
	public function gudang_save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		
		$id_s                           = $this->input->post('id');
		$data['gudang']                 = $this->input->post('gudang');
		$data['kapasitas']              = $this->input->post('kapasitas');	
		$data['id_dealer']              = $this->m_admin->cari_dealer();				
		if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
		else $data['active']            = "";					
		$data['created_at']             = $waktu;		
		$data['created_by']             = $login_id;	
		$this->m_admin->insert("ms_gudang_dealer",$data);
		$_SESSION['pesan']              = "Data has been saved successfully";
		$_SESSION['tipe']               = "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/gudang?id=".$id_s."'>";		
	}
	public function gudang_edit()
	{		
		$tabel             = "ms_gudang_dealer";
		$pk                = "id_gudang_dealer";		
		$id                = $this->input->get('id');
		$idg               = $this->input->get('idg');		
		$data['dt_gudang'] = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_gudang_dealer = '$idg'");
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;		
		$data['set']       = "gudang_edit";									
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		$this->template($data);	
	}
	public function gudang_update()
	{		
		$waktu    = gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;
		
		$id       = $this->input->post("idg");
		$id_      = $this->input->post($pk);
		$cek      = $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$id_s 								= $this->input->post('id');
			$data['gudang'] 			= $this->input->post('gudang');
			$data['kapasitas'] 		= $this->input->post('kapasitas');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update("ms_gudang_dealer",$data,"id_gudang_dealer",$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/gudang?id=".$id_s."'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function gudang_delete()
	{		
		$tabel = "ms_gudang_dealer";
		$pk    = "id_gudang_dealer";
		$id_s  = $this->input->get('id');		
		$idg   = $this->input->get('idg');		
		$this->db->trans_begin();			
		$this->db->delete($tabel,array($pk=>$idg));
		$this->db->trans_commit();			
		$result = 'success';									

		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/gudang?id=".$id_s."'>";
	}	
	public function unit()
	{						
		$id								= $this->input->get('id');		
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan Unit";		
		$data['set']	   	= "unit";					
		$data['dt_item'] 	= $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$data['dt_pu']		= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
						INNER JOIN tr_do_po ON tr_sppm.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_surat_jalan.id_surat_jalan = '$id'");		    
    $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");    
		$this->template($data);										
	}
	public function list_data()
	{				
		$id								= $this->input->get('id');		
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan Unit";		
		$data['set']	   	= "list";					
		$data['dt_item'] 	= $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$data['dt_pu']		= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
						INNER JOIN tr_do_po ON tr_sppm.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_surat_jalan.id_surat_jalan = '$id'");		
    
    $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		$this->template($data);										
	}
	public function cari_id(){
		$no_sj					= $this->input->get('no_sj');		
		$kode = $this->m_admin->get_token(20);
		echo $kode;
	
		$kode2="nihil";$kode3="nihil";
		$ambil = $this->db->query("SELECT * FROM tr_surat_jalan WHERE id_surat_jalan = '$no_sj'")->row();
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$ambil->no_surat_jalan'")->row();		
		$cek2 = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_sj = '$ambil->id_surat_jalan'")->row();
		if(isset($cek->id_penerimaan_unit_dealer)){			
			$kode3 = $cek->id_penerimaan_unit_dealer;						
		}elseif(isset($cek2->id_penerimaan_unit_dealer)){	
			$kode2 = $cek2->id_penerimaan_unit_dealer;						
		}		
		//$kode3 = "ok";
		echo $kode."|".$kode2."|".$kode3;
	}
	public function t_data(){
		$id 			= $this->input->post('id_pu');
		$jenis_pu = $this->input->post('jenis_pu');					
		$id_sj 		= $this->input->post('id_sj');							
		$data['jenis']  = $this->input->post('jenis_pu');		
		$data['no_sj']  = $this->input->post('no_sj');		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
    				WHERE tr_penerimaan_unit_dealer.no_surat_jalan = '$id'");
		$cek_2 = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer_detail.id_sj = tr_surat_jalan.id_surat_jalan 
    				WHERE tr_penerimaan_unit_dealer_detail.id_sj = '$id_sj'");
    if($cek->num_rows() > 0){
    	$tt = $cek->row();
    	$data['mode'] = 'view';
    	$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
    }elseif($cek_2->num_rows() > 0){
    	$tt = $cek->row();
    	$data['mode'] = 'view';    	
    	$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_sj = '$id_sj' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
    }else{
    	$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
    	$data['mode'] = 'input';
    }
		$this->load->view('dealer/t_konfirmasi_pu',$data);				
	}
	public function t_data_list(){
		$id 			= $this->input->post('id_pu');
		$jenis_pu = $this->input->post('jenis_pu');		
		$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
		$data['mode']  = "edit";			
		$data['jenis']  = $this->input->post('jenis_pu');		
		$data['no_sj']  = $this->input->post('no_sj');		
		$this->load->view('dealer/t_konfirmasi_pu',$data);				
	}
	public function cari_id_real($no_sj){		
		
		if(!empty($no_sj)){
			$sj = $no_sj;
		}else{
			$sj = "";
		}		
		$th 						= date("Y");
		$waktu 					= gmdate("Y-m-d h:i:s", time()+60*60*7);		
		$t 							= gmdate("Y-m-d", time()+60*60*7);				

		$id_dealer = $this->m_admin->cari_dealer();
	 	$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");	
	 	if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		}else{
			$get_dealer ='';
			$panjang = '';
		}

		$cek 						= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$sj'");									
		$pr_num 				= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE RIGHT(id_penerimaan_unit_dealer,$panjang) = '$get_dealer' AND LEFT(id_penerimaan_unit_dealer,6) = 'KU$th' ORDER BY id_penerimaan_unit_dealer DESC LIMIT 0,1");									
		
		if($cek->num_rows() == 0){
			if($pr_num->num_rows()>0){
				$row 	= $pr_num->row();				
				$pan  = strlen($row->id_penerimaan_unit_dealer)-($panjang + 6);
				$id 	= substr($row->id_penerimaan_unit_dealer,$pan,10)+1;	
				if($id < 10){
						$kode1 = $th."0000".$id;          
	      }elseif($id>9 && $id<=99){
						$kode1 = $th."000".$id;          
	      }elseif($id>99 && $id<=999){
						$kode1 = $th."00".$id;          
	      }elseif($id>999){
						$kode1 = $th."0".$id;          
	      }
				$kode = "KU".$kode1."-".$get_dealer;
			}else{
				$kode = "KU".$th."00001-".$get_dealer;          
			} 	
		}else{
			$r = $cek->row();
			$kode = $r->id_penerimaan_unit_dealer;
		}			
		return $kode;
	}	
	public function save_nosin(){
		$no_mesin		= $this->input->post('no_mesin');
		$id_pu			= $this->input->post('id_pu');
		$id_sj			= $this->input->post('id_sj');
		$waktu 			= date("y-m-d");
		$nosin_spasi  = substr_replace($no_mesin," ", 5, -strlen($no_mesin));
    $cek_th       = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin = '$nosin_spasi'");
    if($cek_th->num_rows() > 0){
      $amb_th       = $cek_th->row();
      $th_produksi  = $amb_th->tahun_produksi;
    }else{
      $th_produksi  = date('Y');
    }
    $fifo = $this->m_admin->cari_fifo_d($th_produksi);
    //$fifo = "918767822";


		$id_penerimaan_unit_dealer = $data['id_penerimaan_unit_dealer'] = $id_pu;
		$data['no_mesin']                  = $no_mesin;
		$data['jenis_pu']                  = $this->input->post("jenis_pu");
		$data['id_sj']      = $id_sj              = $this->input->post("id_sj");
		$data['sumber_kerusakan']          = $this->input->post("sumber_kerusakan");
		$data['id_user']                   = $this->session->userdata("id_user");
		$jenis_pu                          = strtoupper($this->input->post("jenis_pu"));
		$data['fifo']                      = $fifo;		
		$data['status_dealer']             = "input";		
		$data['id_user']                   = $this->session->userdata("id_user");	
		$get_sj = $this->db->query("SELECT * FROM tr_surat_jalan WHERE id_surat_jalan='$id_sj'");
		$no_do = $this->input->post('no_do');
		$is_do_indent = $this->db->query("SELECT COUNT(no_do) AS c FROM tr_do_po WHERE no_do='$no_do' AND source='po_indent'");	
		if ($is_do_indent->num_rows()>0){
			if ($is_do_indent->row()->c>0) {
				$data['po_indent'] = 'ya';	
			}
		}
		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin='$no_mesin' AND id_penerimaan_unit_dealer = '$id_pu' AND retur = 0");
		if($cek->num_rows() > 0){
			echo "No Mesin tersebut sudah pernah di scan sebelumnya";
		}else{
			$dt_scan 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id_sj' AND tr_surat_jalan_detail.no_mesin = '$no_mesin'");    
			if($dt_scan->num_rows() > 0){
				$this->m_admin->insert("tr_penerimaan_unit_dealer_detail",$data);									
				echo "ok";
			}else{
				echo "No Mesin tersebut tidak terdaftar di surat jalan";
			}
		}		
		//$this->m_admin->update_stock($row->id_modell,$row->id_warna,"RFS",'+','1');
		
		
	}

	public function delete_nosin_double()
	{
		if ($_GET) {
			$no_mesin = $this->input->get('no_mesin');

			$this->db->where('no_mesin', $no_mesin);
			$this->db->delete('tr_penerimaan_unit_dealer_detail');

			?>
			<script type="text/javascript">
				alert("No mesin double berhasil dihapus");
				window.location = "<?php echo base_url() ?>dealer/konfirmasi_pu/unit?id=<?php echo $_GET['id'] ?>";
			</script>
			<?php
		}
	}

	public function delete_data(){
		$id_pu 				= $this->input->post('id_pu');				
		$no_mesin 		= $this->input->post('no_mesin');				
		$mode 				= $this->input->post('mode');				
		
		$rt = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail","id_penerimaan_unit_dealer_detail",$id_pu)->row();			
		$jenis_pu = strtoupper($rt->jenis_pu);
		$this->db->query("UPDATE tr_surat_jalan_detail SET terima = '' WHERE no_mesin = '$no_mesin'");				
		$rs = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();			
		$id_item 	= $rs->id_item;
		$this->m_admin->update_stock_dealer($id_item,$jenis_pu,"-",1);		
		$this->db->query("DELETE FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer_detail = '$id_pu'");			
		
		
		echo "nihil";
	}
	public function hapus_auto(){
		$id = $this->input->post('id_p');		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id'");			
		foreach ($cek->result() as $val) {
			$this->db->query("UPDATE tr_surat_jalan_detail SET terima = '' WHERE no_mesin = '$val->no_mesin'");				
			$rt = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail","id_penerimaan_unit_dealer_detail",$id_pu)->row();			
			$rs = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$val->no_mesin)->row();			
			$jenis_pu = strtoupper($rt->jenis_pu);
			$id_item 	= $rs->id_item;
			$this->m_admin->update_stock_dealer($id_item,$jenis_pu,"-",1);
		}
	$cek = $this->db->query("DELETE FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id'");			
		echo "nihil";
	}

	function get_last_dokumen_nrfs_id($dokumen_nrfs_id=null)
   	{
   		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();

   		if ($dokumen_nrfs_id==null) {
   			$get_data = $this->db->query("SELECT * FROM tr_dokumen_nrfs WHERE id_dealer=$id_dealer AND LEFT(tgl_dokumen,7)='$th_bln' ORDER BY dokumen_nrfs_id DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$new_kode = $get_data->row()->dokumen_nrfs_id;
	   		}else{
	   			$new_kode = 'kosong';
	   		}
   		}else{
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			if ($dokumen_nrfs_id=='kosong') {
				$new_kode = 'NRFS/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
			}else{
				$dokumen_nrfs_id = substr($dokumen_nrfs_id, -4);
				$new_kode        = 'NRFS/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$dokumen_nrfs_id+1);
			}
   		}
   		return $new_kode;
   	}

   	function get_id_goods_receipt()
   	{
   		$th        = date('Y');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();

		$get_data = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer 
				WHERE id_dealer=$id_dealer 
				AND LEFT(tgl_penerimaan,4)='$th' 
				AND id_goods_receipt IS NOT NULL
				ORDER BY created_at DESC LIMIT 0,1");
   		if ($get_data->num_rows()>0) {
			$id       = explode('/', $get_data->row()->id_goods_receipt);
			$new_kode = 'GR/'.$dealer->kode_dealer_md.'/E20/'.sprintf("%'.04d",$id[3]+1);
   		}else{
   			$new_kode = 'GR/'.$dealer->kode_dealer_md.'/E20/0001';
   		}

   		return $new_kode;
   	}

	public function save()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$tabel     = $this->tables;
		$pk        = $this->pk;
		$id        = $this->input->post($pk);
		$id_dealer = $this->m_admin->cari_dealer();
		$cek       = $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		
			$data['tgl_surat_jalan']           = $this->input->post('tgl_surat');	
			$no_do = $data['no_do']                     = $this->input->post('no_do');	
			$data['id_dealer']                 = $id_dealer;	
			$id_gudang_dealer = $data['id_gudang_dealer']          = $this->input->post('id_gudang_dealer');	
			$data['tgl_penerimaan']            = $this->input->post('tgl_penerimaan');				
			$data['status']                    = "input";			
			$data['created_at']                = $waktu;		
			$data['created_by']                = $login_id;	
			$mode                              = $this->input->post("mode");
			$no_sj                             = $this->input->post('no_surat_jalan');	
			$id_penerimaan_unit_dealer         = $this->cari_id_real($no_sj);
			$data['no_surat_jalan']            = $no_sj;
			$id_pu                             = $this->input->post('id_penerimaan_unit_dealer');
			$data['id_penerimaan_unit_dealer'] = $id_penerimaan_unit_dealer;

			$cek_tmp = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id_pu'");

			if($cek_tmp->num_rows() > 0){
				// $last_dokumen_nrfs_id = $this->get_last_dokumen_nrfs_id();
				foreach ($cek_tmp->result() as $amb) {		
					$po_indent = '';			
					// $is_do_indent = $this->db->query("SELECT COUNT(no_do) AS c FROM 
					// 	tr_do_po WHERE no_do='$no_do' AND source='po_indent'")->row()->c;	
					// if ($is_do_indent>0){
					// 	$cek_tot_indent = $this->db->query("SELECT SUM(qty_do) AS c FROM tr_do_indent_detail WHERE no_do='$no_do'")->row()->c;
					// 	$cek_sudah_input = $this->db->query("SELECT count(no_mesin) AS c 
					// 		FROM tr_penerimaan_unit_dealer_detail 
					// 		JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
					// 		WHERE no_do='$no_do' AND po_indent='ya'")->row()->c;
						
					// 	if ($cek_tot_indent<=$cek_sudah_input) {
					// 		$po_indent = ", po_indent='ya'";	
					// 	}
					// }
					$this->db->query("UPDATE tr_penerimaan_unit_dealer_detail SET id_penerimaan_unit_dealer='$id_penerimaan_unit_dealer',id_gudang_dealer='$id_gudang_dealer' $po_indent WHERE id_penerimaan_unit_dealer = '$id_pu'");
					$this->db->query("UPDATE tr_surat_jalan_detail SET terima = 'ya' WHERE no_mesin = '$amb->no_mesin'");				
					$this->db->query("UPDATE tr_scan_barcode SET status = '4' WHERE no_mesin = '$amb->no_mesin'");				
					$r = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$amb->no_mesin)->row();
					// $this->m_admin->update_stock_dealer($r->id_item,$amb->jenis_pu,"+",1);
					// if ($amb->jenis_pu=='nrfs') {
					// 	$last_dokumen_nrfs_id = $this->get_last_dokumen_nrfs_id($last_dokumen_nrfs_id);
					// 	$tipe = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$r->tipe_motor])->row();
					// 	$wrn  = $this->db->get_where('ms_warna',['id_warna'=>$r->warna])->row();
					// 	$dok_add_part ='';
					// 	if($part = $this->part_add->get_content()){
					// 		foreach($part as $key => $val){
					// 			$dt_part = $this->db->get_where('ms_part',['id_part'=>$val['id_part']])->row();
					// 			if ($val['no_mesin']==$amb->no_mesin) {
					// 				$dok_add_part[]=['dokumen_nrfs_id'=>$last_dokumen_nrfs_id,
					// 						'id_part'  =>$val['id_part'],
					// 						'nama_part'=>$dt_part->nama_part,
					// 						'qty_part' =>$val['qty']
					// 						];
					// 			}
					// 		}
					// 		if ($dok_add_part!='') {
					// 			$this->db->insert_batch('tr_dokumen_nrfs_part',$dok_add_part);
					// 		}
					// 	}		
					// 	$dokumen=['dokumen_nrfs_id'=> $last_dokumen_nrfs_id,
					// 				'tgl_dokumen'     => date('Y-m-d'),
					// 				'id_dealer'       => $id_dealer,
					// 				'no_shiping_list' => $no_sj,
					// 				'type_code'       => $r->tipe_motor,
					// 				'deskripsi_unit'  => $tipe->tipe_ahm,
					// 				'color_code'      => $wrn->id_warna,
					// 				'deskripsi_warna' => $wrn->warna,
					// 				'no_mesin'        => $amb->no_mesin,
					// 				'no_rangka'       => $r->no_rangka,
					// 				'need_parts'      => count($dok_add_part),
					// 				'sumber_rfs_nrfs' => $amb->sumber_kerusakan,
					// 				'status_nrfs'     => 'NRFS',
					// 				'created_at'      => $waktu,
					// 				'created_by'      => $login_id,
					// 			   ];
					// 	$this->db->insert('tr_dokumen_nrfs',$dokumen);						
					// }					
				}
			}

			if($mode == 'new'){
				$data['id_goods_receipt'] = $this->get_id_goods_receipt();
				$data['qty_terima'] 	  = $cek_tmp->num_rows();
				$this->m_admin->insert($tabel,$data);
				
				//Get Part
				$cek_part = $this->db->get_where('tr_penerimaan_unit_dealer_part',['id_penerimaan_unit_dealer'=>$id_penerimaan_unit_dealer]);
				if ($cek_part->num_rows()>0) {
					$this->db->delete('tr_penerimaan_unit_dealer_part',['id_penerimaan_unit_dealer'=>$id_penerimaan_unit_dealer]);
				}
				if($part = $this->part_add->get_content()){
					foreach($part as $key => $val){
						$add_part[]=['id_penerimaan_unit_dealer'=>$id_penerimaan_unit_dealer,
									'no_mesin' =>$val['no_mesin'],
									'id_part'  =>$val['id_part'],
									'qty_part' =>$val['qty']
									];
					}
					if (isset($add_part)) {
						$this->db->insert_batch('tr_penerimaan_unit_dealer_part',$add_part);
					}
				}			

				$get_detail = $this->db->get_where('tr_penerimaan_unit_dealer_detail',['id_penerimaan_unit_dealer'=>$id_penerimaan_unit_dealer])->result();
				$last_dokumen_nrfs_id = $this->get_last_dokumen_nrfs_id();
				$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['kode_notif'=>'ntf_parts_nrfs'])->row();
				foreach ($get_detail as $rsd) {
					if ($rsd->jenis_pu=='nrfs') {
						$r = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$rsd->no_mesin)->row();
						$last_dokumen_nrfs_id = $this->get_last_dokumen_nrfs_id($last_dokumen_nrfs_id);
						$tipe                 = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$r->tipe_motor])->row();
						$wrn                  = $this->db->get_where('ms_warna',['id_warna'=>$r->warna])->row();
						$dok_add_part         ='';
						if($part = $this->part_add->get_content()){
							foreach($part as $key => $val){
								$notif_parts = array();
								$dt_part = $this->db->get_where('ms_part',['id_part'=>$val['id_part']])->row();
								if ($val['no_mesin']==$rsd->no_mesin) {
									$dok_add_part[]=['dokumen_nrfs_id'=>$last_dokumen_nrfs_id,
											'id_part'  =>$val['id_part'],
											'nama_part'=>$dt_part->nama_part,
											'qty_part' =>$val['qty']
											];
									$notif_parts[] = $val['id_part'].'('.$val['qty'].')';
								}
							}
							if ($dok_add_part!='') {
								$this->db->insert_batch('tr_dokumen_nrfs_part',$dok_add_part);
							}
						}		
						$dokumen=['dokumen_nrfs_id'=> $last_dokumen_nrfs_id,
									'tgl_dokumen'     => date('Y-m-d'),
									'id_dealer'       => $id_dealer,
									'no_shiping_list' => $no_sj,
									'type_code'       => $r->tipe_motor,
									'deskripsi_unit'  => $tipe->tipe_ahm,
									'color_code'      => $wrn->id_warna,
									'deskripsi_warna' => $wrn->warna,
									'no_mesin'        => $rsd->no_mesin,
									'no_rangka'       => $r->no_rangka,
									// 'need_parts'   => $dok_add_part!=''?'Yes':'No',
									// 'need_parts'      => $this->input->post('need_parts_'.$rsd->no_mesin),
									'need_parts'      => $this->session->userdata($rsd->no_mesin),
									'sumber_rfs_nrfs' => $rsd->sumber_kerusakan,
									'status_nrfs'     => 'NRFS',
									'created_at'      => $waktu,
									'created_by'      => $login_id,
									'status'          => 'open'
								   ];
						$this->db->insert('tr_dokumen_nrfs',$dokumen);		

						if (count($notif_parts)>0) {
							$parts = implode(', ', $notif_parts);
							$pesan = "Telah terjadi perubahan unit RFS ke NRFS dengan detail: <br>Kode Tipe Unit = $r->tipe_motor <br>Kode Warna = $wrn->id_warna <br>No Mesin = $rsd->no_mesin <br>No Rangka = $r->no_rangka <br>Parts = $parts <br>Mohon untuk melakukan pengecekan ketersediaan Parts didalam sistem
									";
							$notifikasi_parts[] = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
									'id_referensi' => $last_dokumen_nrfs_id,
									'judul'        => "Notifikasi Kebutuhan Parts",
									'pesan'        => $pesan,
									'link'         => $ktg_notif->link.'?id='.$last_dokumen_nrfs_id,
									'status'       =>'baru',
									// 'id_dealer'    => $id_dealer,
									'created_at'   => $waktu,
									'created_by'   => $login_id
								 ];
						}				
					}
				}
			}else{			
				$data['qty_terima'] 	  = $cek_tmp->num_rows();
				$this->m_admin->update("tr_penerimaan_unit_dealer",$data,"id_penerimaan_unit_dealer",$id_penerimaan_unit_dealer);						
			}
			if (isset($notifikasi_parts)) {
				$this->db->insert_batch('tr_notifikasi',$notifikasi_parts);
			}
	          $this->part_add->destroy();	
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu'>";
		// }else{
		// 	$_SESSION['pesan'] 	= "Duplicate entry for primary key";
		// 	$_SESSION['tipe'] 	= "danger";
		// 	echo "<script>history.go(-1)</script>";
		// }
	}

	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_surat_jalan 		= $this->input->post('no_sj');				
		$id_sj 		= $this->input->post('id_sj');				
		$id_penerimaan_unit_dealer 		= $this->input->post('id_penerimaan_unit_dealer');				
		$cek = 0;
		foreach($no_surat_jalan AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$no_sj 		= $_POST['no_sj'][$key];
			//$id_item 	= $_POST['id_item'][$key];
			$qty_terima  	= $_POST['qty_terima'][$key];
		 	$qty_md  	= $_POST['qty_md'][$key];
			// $no_sl = $_POST['no_sl'][$key];
		 	$result[] = array(
				"id_penerimaan_unit_dealer"  => $id_penerimaan_unit_dealer,
				"id_ksu"  => $_POST['id_ksu'][$key],
				//"id_item"  => $_POST['id_item'][$key],
				"no_surat_jalan"  => $no_sj,
				"qty_md"  => $_POST['qty_md'][$key],
				"qty_terima"  => $_POST['qty_terima'][$key],
				"created_at"  => $waktu,
				"created_by"  => $login_id
		 	); 
		 	if($qty_md < $qty_terima){
		 		$cek = $cek + 1;		 		
		 	}

		 	$rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_ksu = '$id_ksu' AND no_surat_jalan = '$no_sj' AND id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer'");
      if($rty->num_rows() > 0){
      	$e = $rty->row();      	
      	$this->db->query("DELETE FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_ksu_dealer = '$e->id_penerimaan_ksu_dealer'");
      }

		}
		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty Penerimaan KSU tidak boleh lebih dari jumlah KSU yg di-supply oleh MD";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
			//echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/ksu?id=".$id_sj."'>";
		}else{			
      $test2 = $this->db->insert_batch('tr_penerimaan_ksu_dealer', $result);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/ksu?id=".$id_sj."'>";
		}		
	}
	
	public function close(){
		$id_dealer    = $this->m_admin->cari_dealer();
		$id_pu        = $this->input->get('id');		
		$pr           = $this->input->get('id_pu');		
		$cek_approval = $this->m_admin->cek_approval($this->tables,$this->pk,$pr);		


		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{



			$cek_status_pu = $this->db->query("select id_penerimaan_unit_dealer , status from tr_penerimaan_unit_dealer WHERE status='close' and id_penerimaan_unit_dealer = '$pr'");
			$id_dealer    = $this->m_admin->cari_dealer();

	
			if ($cek_status_pu->num_rows()>0) {

			// 	if ($id_dealer =='101'){
			// 		var_dump($cek_status_pu->num_rows());
			// 		die();
			// }
			

				$_SESSION['pesan']  = 'Gagal! Penerimaan ini sudah selesai diterima.';										
				$_SESSION['tipe'] 	= "warning";			
				echo "<script>history.go(-1)</script>";
			}else{
				$waktu              = gmdate("y-m-d H:i:s", time()+60*60*7);
				$login_id           = $this->session->userdata('id_user');
				$tabel              = "tr_surat_jalan";
				$pk                 = "id_surat_jalan";		
				
				$data['updated_at'] = $waktu;		
				$data['updated_by'] = $login_id;	
				$data['status']     = "close";
				$this->m_admin->update($tabel,$data,$pk,$id_pu);
				//Close PO
				$get_sj = $this->db->get_where('tr_surat_jalan',['id_surat_jalan'=>$id_pu])->row();
				$po = $this->db->query("SELECT no_po FROM `tr_penerimaan_unit_dealer`
								JOIN tr_do_po ON tr_penerimaan_unit_dealer.no_do=tr_do_po.no_do
								where no_surat_jalan ='$get_sj->no_surat_jalan'");
				if ($po->num_rows()>0) {
					$po     = $po->row();
					$upd_po = ['status'=>'closed'];
					$this->db->update('tr_po_dealer',$upd_po,['id_po'=>$po->no_po]);
				}
				$this->db->query("UPDATE tr_penerimaan_unit_dealer SET status = 'close', updated_at = '$waktu', updated_by='$login_id' WHERE id_penerimaan_unit_dealer = '$pr'");

				//Update Detail Penerimaan Sesuaikan Dengan Header Berdasarkan ID Surat Jalan, Update Scan Barcode, Update surat jalan detail
				$get_dt = $this->db->get_where('tr_penerimaan_unit_dealer_detail',['id_sj'=>$id_pu])->result();
				$this->db->query("UPDATE tr_penerimaan_unit_dealer_detail SET id_penerimaan_unit_dealer='$pr' WHERE id_sj='$id_pu'");
				foreach ($get_dt as $rs) {
					$this->db->query("UPDATE tr_surat_jalan_detail SET terima = 'ya' WHERE no_mesin = '$rs->no_mesin'");				
					$this->db->query("UPDATE tr_scan_barcode SET status = '4' WHERE no_mesin = '$rs->no_mesin'");
				}				

				$pu         = $this->db->get_where('tr_penerimaan_unit_dealer',['id_penerimaan_unit_dealer'=>$pr])->row();
				$gudang     = $pu->id_gudang_dealer;
				$dealer     = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
				
				$qty_terima = $this->db->query("SELECT COUNT(no_mesin) AS c FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer='$pr'")->row()->c;
				$qty_kirim  =$this->db->query("SELECT count(no_mesin) as c FROM tr_surat_jalan_detail WHERE no_surat_jalan='$pu->no_surat_jalan'")->row()->c;

				if ($qty_terima==$qty_kirim) {
					$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>7])->row();
					$get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>7]);
					$email = array();
					foreach ($get_notif_grup->result() as $rd) {
						$get_email = $this->db->query("SELECT email FROM ms_karyawan_dealer 
						WHERE id_karyawan_dealer IN(
							SELECT id_karyawan_dealer FROM ms_user 
							WHERE active=1 
							AND id_user_group=(
								SELECT id_user_group FROM ms_user_group 
								WHERE code='$rd->code_user_group'
							)
						)")->result();
						foreach ($get_email as $usr) {
							$email[] = $usr->email;
						}
					}

					$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
							'id_referensi' => $pr,
							'judul'        => "Goods Receipt pada gudang $gudang",
							'pesan'        => "Telah terjadi transaksi Goods Receipt pada gudang $gudang, Silahkan Click pada pesan ini untuk melihat detail transaksi.",
							'link'         => $ktg_notif->link.'/detail?id='.$pr,
							'status'       =>'baru',
							'id_dealer'    => $id_dealer,
							'created_at'   => $waktu,
							'created_by'   => $login_id
						];
					$email_status = $this->email_selesai_transaksi($pu->id_penerimaan_unit_dealer,$email,$gudang,$pu->no_surat_jalan);
					$this->db->insert('tr_notifikasi',$notif);
				}else{
					$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>8])->row();
					$get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>8]);
					$email = array();
					foreach ($get_notif_grup->result() as $rd) {
						$get_email = $this->db->query("SELECT email FROM ms_karyawan 
						WHERE id_karyawan IN(
							SELECT id_karyawan_dealer FROM ms_user 
							WHERE jenis_user='Main Dealer' 
							AND active=1 
							AND id_user_group=(
								SELECT id_user_group FROM ms_user_group 
								WHERE code='$rd->code_user_group'
							)
						)")->result();
						foreach ($get_email as $usr) {
							$email[] = $usr->email;
						}
					}
					$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
							'id_referensi' => $pu->no_surat_jalan,
							'judul'        => "Selisih Goods Receipt pada gudang $gudang",
							'pesan'        => "Telah terjadi selisih transaksi Goods Receipt pada gudang $gudang, Silahkan Click pada pesan ini untuk melihat detail transaksi.",
							'link'         => $ktg_notif->link.'/detail?no_sj='.$pu->no_surat_jalan,
							'status'       =>'baru',
							'created_at'   => $waktu,
							'created_by'   => $login_id
						];
					$email_status = $this->email_selisih_transaksi($pu->no_surat_jalan,$email,$gudang);
					$this->db->insert('tr_notifikasi',$notif);
				}
				if ($email_status=='sukses') {
					$pesan = 'Status berhasil diubah dan E-Mail berhasil dikirim';
				}else{
					$pesan = 'Status berhasil diubah dan E-Mail gagal dikirim !';
				}
				// $get_indent = $this->db->query("SELECT tr_scan_barcode.*,COUNT(id_item) as jml, tipe_ahm,ms_warna.warna 
				// 	FROM tr_penerimaan_unit_dealer_detail AS tpud
				// 	JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tpud.no_mesin
				// 	JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
				// 	JOIN ms_warna ON tr_scan_barcode.warna=ms_warna.id_warna
				// 	WHERE id_penerimaan_unit_dealer='$pu->id_penerimaan_unit_dealer' AND po_indent='ya'
				// 	GROUP BY id_item
				// ");
				$get_indent = $this->db->query("SELECT COUNT(id_item) AS jml,tipe_ahm,ms_warna.warna,id_warna,id_tipe_kendaraan
					FROM tr_scan_barcode
					JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
					JOIN ms_warna ON tr_scan_barcode.warna=ms_warna.id_warna
					WHERE tr_scan_barcode.no_mesin IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer='$pu->id_penerimaan_unit_dealer' AND po_indent='ya')
					GROUP BY id_item
					");
				if ($get_indent->num_rows()>0) {
					$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>13])->row();
					$get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>13]);
					$pesan_notif='Telah tersedia ';
					foreach ($get_indent->result() as $rs) {
						$pesan_notif .= "$rs->jml Unit $rs->tipe_ahm, warna $rs->warna </br>";
					}
						$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
								'id_referensi' => $pu->id_penerimaan_unit_dealer,
								'judul'        => "Tersedia Indent Unit",
								'pesan'        => $pesan_notif,
								'id_dealer'    => $id_dealer,
								'link'         => $ktg_notif->link.'?id='.$pu->id_penerimaan_unit_dealer,
								'status'       =>'baru',
								'created_at'   => $waktu,
								'created_by'   => $login_id
							];
					$this->db->insert('tr_notifikasi',$notif);
				}
				$_SESSION['pesan'] 	= $pesan;
				$_SESSION['tipe'] 	= "success";
				redirect(base_url('dealer/konfirmasi_pu'),'refresh');
				// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/'>";
			}
		}
	}
		
	public function getScanModal()
	{	
		 $id = $this->input->post('id');
		 $id_dealer = $this->m_admin->cari_dealer();
		 // $data['dt_scan_old'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
   //  					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
   //          	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
   //          	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan.id_surat_jalan = '$id' 
   //          	AND tr_surat_jalan_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");    

		 $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan.id_surat_jalan = '$id' 
            	AND (tr_surat_jalan_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0))");
		$data['scan'] =	$this->input->post('scan');
		$this->load->view('dealer/t_konfirmasi_pu',$data);
	}

	function addPart()
	{
		$data['no_mesin'] = $this->input->post('no_mesin');
		$data['id_part']  = $this->input->post('id_part');
		$data['qty']      = $this->input->post('qty_part');
		$data['price']    = 1;
		$data['id']       = rand(1,9999);
		if ($this->part_add->insert($data)) {
			echo 'sukses';
		}else{
			echo 'gagal';
		}
	}
	function isi_part()
	{	
		$id_pu = $this->input->post('id_pu');
		$cek_isi = count($this->part_add->get_content());
		$cek_part = $this->db->get_where('tr_penerimaan_unit_dealer_part',['id_penerimaan_unit_dealer'=>$id_pu]);
	    if ($cek_part->num_rows()>0) {
	    	if ($cek_isi!=$cek_part->num_rows()) {
	    		$this->destroy();
	    		foreach ($cek_part->result() as $cp) {
		    		$add_part=['no_mesin'=>$cp->no_mesin,
		    				   'qty'=>$cp->qty_part,
		    				   'id_part'=>$cp->id_part,
		    				   'price'=>1,
		    				   'id'=> rand(1,9999)
		    				  ];
		    		$this->part_add->insert($add_part);
		    	}
	    	}
	    } 
	}

	public function cek()
	{
		if($part_add = $this->part_add->get_content())
		{
			foreach ($part_add as $prt) {
				echo $prt['id_part'].'--'.$prt['no_mesin'].'</br>';
			}
		}
	}
	public function destroy()
	{
		$this->part_add->destroy();	
	}
	function delPart()
	{
		$rowid=$this->input->post('rowid');
		if($this->part_add->remove_item($rowid)){
			echo 'sukses';
		}else{
			echo 'gagal';
		}
	}

	public function getTerima()
	{
		$id_pu = $this->input->post('id_pu');
		echo $this->db->get_where('tr_penerimaan_unit_dealer_detail',['id_penerimaan_unit_dealer'=>$id_pu])->num_rows();
	}

	public function penerimaan()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = "Data Penerimaan";		
		$data['set']   = "penerimaan";	
		$id            = $this->input->get("id");	
		$data['dt_pu'] = $this->m_admin->getByID("tr_penerimaan_unit_dealer","id_penerimaan_unit_dealer",$id);					
		if ($data['dt_pu']->num_rows()>0) {
			$pu = $data['dt_pu']->row();

			$data['detail'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,no_rangka,tipe_ahm,ms_warna.warna,id_warna,id_tipe_kendaraan,id_item  FROM tr_penerimaan_unit_dealer_detail 
				JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin
				LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
				LEFT JOIN ms_warna ON ms_warna.id_warna = tr_scan_barcode.warna
				WHERE id_penerimaan_unit_dealer='$id' ");
			$data['ksu'] = $this->db->query("SELECT tr_penerimaan_ksu_dealer.*,ksu FROM tr_penerimaan_ksu_dealer 
						JOIN ms_ksu ON ms_ksu.id_ksu=tr_penerimaan_ksu_dealer.id_ksu
						WHERE id_penerimaan_unit_dealer='$id'
					");
			$this->template($data);	
		}
	}
	
	public function email_selisih_transaksi($no_sj,$email,$gudang) { 
		$from = $this->db->get_where('ms_email_md',['email_for'=>'notification'])->row(); 
		$to_email   = $email; 

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
		$this->email->to($to_email);
		$this->email->subject('[SINARSENTOSA] Selisih Transaksi'); 

		$row = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,ms_dealer.nama_dealer FROM tr_penerimaan_unit_dealer 
				JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer=ms_dealer.id_dealer
				WHERE no_surat_jalan='$no_sj' ");
		$data['set']            = 'selisih';
		$file_logo              = base_url('assets/panel/images/logo_sinsen.jpg');
		$data['logo']           = $file_logo;
		$data['gudang']         = $gudang;
		$row                    = $row->row();
		$data['tgl_penerimaan'] = $row->tgl_penerimaan;

		
		$data['detail'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.* FROM tr_penerimaan_unit_dealer_detail
		 JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
		 WHERE no_surat_jalan='$row->no_surat_jalan' AND jenis_pu ='nrfs'");

		$data['tidak_diterima'] = $this->db->query("SELECT tr_surat_jalan_detail.*, ms_item.id_tipe_kendaraan,ms_warna.warna,ms_warna.id_warna,ms_tipe_kendaraan.tipe_ahm,
			(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_surat_jalan_detail.no_mesin) AS no_rangka 
			FROM tr_surat_jalan_detail 
			LEFT JOIN ms_item ON ms_item.id_item=tr_surat_jalan_detail.id_item
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=ms_item.id_warna
			WHERE tr_surat_jalan_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail
				 JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
				 WHERE no_surat_jalan=tr_surat_jalan_detail.no_surat_jalan AND tr_penerimaan_unit_dealer_detail.retur = 0)
			AND no_surat_jalan='$row->no_surat_jalan'
				");
		// $this->load->view('dealer/konfirmasi_pu_email',$data);
		$this->email->message($this->load->view('dealer/konfirmasi_pu_email', $data, true)); 

         //Send mail 
         if($this->email->send()){
			return 'sukses';	
         }else {
			return 'gagal';
         } 
	}

	public function email_selesai_transaksi($id_pu,$email,$gudang,$no_sj) { 
		$from = $this->db->get_where('ms_email_md',['email_for'=>'notification'])->row(); 
		$to_email   = $email; 

		$config = Array(
          'protocol'  => 'smtp',
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
		$this->email->to($to_email);
		$this->email->subject('[SINARSENTOSA] Penerimaan Unit Dealer'); 
		$file_logo              = base_url('assets/panel/images/logo_sinsen.jpg');
		$data['logo']           = $file_logo;
		$data['gudang']         = $gudang;
		$data['no_surat_jalan'] = $no_sj;
		$data['set']            = "penerimaan";	
		$data['dt_pu']          = $this->m_admin->getByID("tr_penerimaan_unit_dealer","id_penerimaan_unit_dealer",$id_pu);					
		if ($data['dt_pu']->num_rows()>0) {
			$pu                     = $data['dt_pu']->row();
			$data['tgl_penerimaan'] = $pu->tgl_penerimaan;
			$data['detail']         = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,no_rangka,tipe_ahm,ms_warna.warna,id_warna,id_tipe_kendaraan,id_item  FROM tr_penerimaan_unit_dealer_detail 
				JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin
				LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
				LEFT JOIN ms_warna ON ms_warna.id_warna = tr_scan_barcode.warna
				WHERE id_penerimaan_unit_dealer='$id_pu' ");
			$data['ksu'] = $this->db->query("SELECT tr_penerimaan_ksu_dealer.*,ksu FROM tr_penerimaan_ksu_dealer 
						JOIN ms_ksu ON ms_ksu.id_ksu=tr_penerimaan_ksu_dealer.id_ksu
						WHERE id_penerimaan_unit_dealer='$id_pu'
					");
			// $this->template($data);	
		}
		// $this->load->view('dealer/konfirmasi_pu_email',$data);
		$this->email->message($this->load->view('dealer/konfirmasi_pu_email', $data, true)); 

         //Send mail 
         if($this->email->send()){
			return 'sukses';
         }else {
			return 'gagal';
         } 
	}
		function setNeedParts()
	{
		$this->session->set_userdata($this->input->post('no_mesin'), $this->input->post('need_parts'));
		// echo $this->session->userdata($this->input->post('no_mesin'));
		echo json_encode('sukses');
	}
}