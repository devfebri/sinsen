<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_ksu extends CI_Controller {

    var $tables =   "tr_penerimaan_unit";	
		var $folder =   "h1";
		var $page		=		"penerimaan_ksu";
    var $pk     =   "id_penerimaan_unit";
    var $title  =   "Penerimaan KSU";

    // status scan barcode
    // 1 = input
    // 2 = booked do dealer

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('ev_model');		
		//===== Load Library =====
		$this->load->library('upload');

		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang_helper');
		$this->load->library('mpdf_l');

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

	public function index2()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;																
		$data['set']	= "view";

		// $data['dt_penerimaan_unit'] = $this->db->query("SELECT pu.*,
		// (select COUNT(oem.no_shipping_list) as no_shipping_list  from tr_penerimaan_unit_detail pud left join tr_shipping_list_ev_accoem oem on pud.no_shipping_list= oem.no_shipping_list 
		// where pud.id_penerimaan_unit = pu.id_penerimaan_unit ) as is_ev
		// FROM tr_penerimaan_unit pu WHERE pu.status <> 'close' AND pu.status = 'close scan' ORDER BY pu.id_penerimaan_unit DESC");		

		$data['dt_penerimaan_unit'] = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE status <> 'close' AND status = 'close scan' ORDER BY id_penerimaan_unit DESC");
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
            tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		$this->template($data);		
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;																
		$data['set']	= "view_serverside";
		$data['mode']	= "view";
		$this->template($data);		
	}

	public function history()
	{
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;																
		$data['set']	= "view_serverside";
		$data['mode']	= "history";

		$this->template($data);			
	}

	public function scan_oem()
	{
		$data['isi']    = 'penerimaan_oem';
		$data['title']	= "Penerimaan OEM";
		$data['set']	= "scan_oem";
		$this->template($data);
	}

	
	public function fetch_penerimaan_oem()
	{
		$fetch_data = $this->make_query_oem();
		$data       = array();
	
		$no = 1;
		foreach ($fetch_data->result() as $rs) {

			$button ='';
			$cek = $this->m_admin->getByID("tr_penerimaan_ksu","id_penerimaan_unit",$rs->id_penerimaan_unit);

            $st = "";
            if($cek->num_rows() > 0){
              $st = "<br><span class='label label-success'>saved</span>";
            }

			$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$rs->ekspedisi'");          
            if($s->num_rows() > 0){
              $r = $s->row();
              $vendor_name = $r->vendor_name;
            }else{
              $vendor_name = "";
            }

			if($rs->status == 'close scan'){
				$button .= "<a href='h1/penerimaan_ksu/ksu?id={$rs->id_penerimaan_unit}'>
				<button class='btn btn-flat btn-xs btn-success'><i class='fa fa-suitcase'></i> Penerimaan KSU</button>              
			  </a>";

				$button .= "<a href='h1/penerimaan_ksu/close_ksu?id={$rs->id_penerimaan_unit}'>
								<button onclick=\"return confirm('Are you sure?')\" class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close KSU</button>              
							</a>";
              }elseif($rs->status == 'close'){
				$button ='closed';
			}

				$this->db->select('pud.no_shipping_list');
				$this->db->from('tr_shipping_list_ev_accoem oem');
				$this->db->join('tr_penerimaan_unit_detail pud', 'oem.no_shipping_list = pud.no_shipping_list', 'left');
				$this->db->where('pud.id_penerimaan_unit', $rs->id_penerimaan_unit);
				$query = $this->db->get();
				$id_penerimaan = $rs->id_penerimaan_unit .'   '.  $st;

			if ($query->num_rows() > 0) {

				$mode  = $this->input->post('mode');
				if($mode !== 'history'){
					$button .= "<a href='h1/penerimaan_ksu/scan_oem?id={$rs->id_penerimaan_unit}'>
					<button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-bolt' aria-hidden='true'></i> Penerimaan OEM</button>
					</a>";
	
					$button .= "<a href='h1/penerimaan_ksu/close_oem?id={$rs->id_penerimaan_unit}'>
					<button onclick=\"return confirm('Are you sure?')\" class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close OEM</button>              
					</a>";
				}

				$this->db->select('pb.id_penerimaan_battery');
				$this->db->from('tr_penerimaan_battery pb');
				$this->db->where('pb.id_penerimaan_battery', $rs->id_penerimaan_unit);
				$this->db->where('pb.status IS NULL');
				$cek_ev = $this->db->get();
	
				if($cek_ev->num_rows() > 0){
					$button .= "<a href='h1/penerimaan_ksu/sticker_oem?id={$rs->id_penerimaan_unit}'>
					<button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print' aria-hidden='true'></i> Cetak Ulang Sticker OEM</button>
					</a>";
				}
				
				$st .= "<span class='label label-primary'>Ev</span>";
				$id_penerimaans = "<a href='h1/penerimaan_ksu/scan_oem?id={$rs->id_penerimaan_unit}'>{$rs->id_penerimaan_unit}</a>";
				$id_penerimaan = $id_penerimaans .'   '.  $st;
			}

			$sub_array   = array();
			$sub_array[] = $no++;
			$sub_array[] =  $id_penerimaan;
			$sub_array[] = $rs->no_antrian;
			$sub_array[] = $rs->tgl_surat_jalan;
			$sub_array[] = $vendor_name;
			$sub_array[] = $rs->no_polisi;
			$sub_array[] = $rs->nama_driver;
			$sub_array[] = $rs->tgl_penerimaan;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data_oem(),
			"data"            => $data
		);
		echo json_encode($output);
	}

	public function make_query_oem()
	{
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$page    = $this->input->post('page');
		$limit  = "LIMIT $start, $length";
		$mode  = $this->input->post('mode');

		$search = $this->input->post('search')['value'];
		$where = "WHERE 1=1 ";

		if($mode =='view'){
			$where .= "AND pu.status <> 'close' AND pu.status = 'close scan'";
		}else{
			$where .= "AND pu.status ='close' ";
		}

		if ($search != '') {
			$where .= " AND (pu.id_penerimaan_unit LIKE '%$search%'
					OR pu.no_antrian LIKE '%$search%'
					OR pu.no_surat_jalan LIKE '%$search%'
					OR pu.ekspedisi LIKE '%$search%'
					OR pu.nama_driver LIKE '%$search%'
					OR pu.tgl_penerimaan LIKE '%$search%'
				) 
			";
		}

		$order_column = array('pu.id_penerimaan_unit', 'pu.no_antrian', 'pu.no_surat_jalan', 'pu.ekspedisi', 'pu.no_polisi','pu.nama_driver','pu.tgl_penerimaan', null);
		$set_order = "ORDER BY pu.id_penerimaan_unit DESC";

		if (isset($_POST['order'])) {
			$order = $_POST['order'];
			$order_clm  = $order_column[$order['0']['column']];
			$order_by   = $order['0']['dir'];
			$set_order = " ORDER BY $order_clm $order_by ";
		}

		return  $this->db->query("SELECT * FROM tr_penerimaan_unit pu 
		$where 
		$set_order 
		$limit
		");
	}

	function get_filtered_data_oem()
	{
		return $this->make_query_oem('y')->num_rows();
	}

	public function sticker_oem()
	{
		$id  			= $this->input->get('id');
		$data['isi']    = 'cetak_sticker_oem';
		$data['title']	= "Cetak Ulang Stiker";	
		$data['set']	= "sticker_oem";

		$this->db->select('*');
		$this->db->from('tr_penerimaan_battery_detail pdb');
		$this->db->join('tr_stock_battery sb', 'pdb.serial_number = sb.serial_number', 'left');
		$this->db->join('tr_penerimaan_unit_detail pud', 'pud.no_shipping_list = pdb.no_shipping_list', 'left');
		$this->db->where('sb.status', '1');
		$this->db->where('pud.id_penerimaan_unit', $id);
		$data['cetak_sticker'] = $this->db->get();

		$this->template($data);
	}
		
	public function print_stiker_oem()
	{
		$id  = $this->input->get('id');
		$serial_number       = $id;		
		$query = $this->db->query("SELECT * FROM tr_shipping_list_ev_accoem WHERE serial_number = '$serial_number'");

		if($query ->num_rows() > 0){
			$row=$query->row();
		$pdf = new PDF_HTML('p', 'mm', array(90, 88,40,40));

		$pdf->SetMargins(5, 5, 5);
		$pdf->AddPage();

		$pdf->SetFont('Arial', '', 8);
		// Header row
		$pdf->Cell(30, 5,'TYPE', 0, 0);
		$pdf->Cell(2, 5,':', 0, 0);
		$pdf->Cell(49, 5,'B', 0, 1);
		$pdf->Cell(30, 5,'NO FIFO', 0, 0);
		$pdf->Cell(2, 5,':', 0, 0);

		$pdf->Cell(49, 5,1, 0, 1);
		$pdf->Cell(30, 5,'PART ID', 0, 0);
		$pdf->Cell(2, 5,':', 0, 0);
		$pdf->Cell(49, 5,$row->part_id, 0, 1);
		$pdf->Cell(30, 5, 'PART DESC', 0, 0);
		$pdf->Cell(2, 5,':', 0, 0);
		$pdf->Cell(49, 5,$row->part_desc, 0, 1);
		$pdf->Cell(30, 5,'SERIAL NUMBER', 0, 0); 
		$pdf->Cell(2, 5,':', 0, 0);
		$pdf->Cell(49, 5,$row->serial_number, 0, 1); 
		$pdf->Ln();
		$pdf->Code128(5, 30,  $row->serial_number, 80, 20);
		// $pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$row->serial_number&chs=77x77", 155, 46, 40, 0, 'PNG');
		$pdf->Output();

		}
	}


	public function detail_scan_ev()
	{		
		$penerimaan_oem = $this->input->post('penerimaan_oem');
		$where = "WHERE 1=1 ";
		if (isset($penerimaan_oem)) {
			$where .= " AND pu.id_penerimaan_unit ='$penerimaan_oem'";
			$where .= " AND ev_oem.status_scan ='0'";
		}
		$data['dt_shipping_list']	= $this->db->query("SELECT ev_oem.* from tr_shipping_list_ev_accoem ev_oem 
		left join tr_penerimaan_unit_detail pu on pu.no_shipping_list=ev_oem.no_shipping_list
		$where ");
		// $data['dt_shipping_list']	= $this->db->query("SELECT * from tr_shipping_list_ev_accoem ev_oem $where ");
		$this->load->view("h1/t_scan_ev",$data);		
	}

	
	public function show_scan()
	{	
		$year = date('Y');
		$this->load->model('ev_model');
		$waktu 			    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		    = $this->session->userdata('id_user');
		$ready_sale = $this->input->post('ready_sale');

		$penerimaan_oem = $this->input->post('penerimaan_oem');
		$serial_number  = $this->input->post('id');

		$where      = "WHERE 1=1 ";
		$wheresjson = "WHERE 1=1 ";

		if (isset($serial_number)) {
			$where .= " AND status_scan ='0'";
			$where .= " AND serial_number ='$serial_number'";
		}

		if (isset($penerimaan_oem)) {
			$wheresjson .= " AND pu.id_penerimaan_unit ='$penerimaan_oem'";
			$wheresjson .= " AND ev_oem.status='1'";
		}

		if($serial_number !== NULL){
		$querys = $this->db->query("SELECT * from tr_shipping_list_ev_accoem $where")->row();

		$set_id_part          = $querys->part_id;
		$set_part_desc        = $querys->part_desc;
		$set_shipping_list    = $querys->no_shipping_list;
		$tgl_shipping_list    = $querys->tgl_shipping_list;
		$status = NULL;


		if (count($querys) > 0) {
			$this->db->trans_begin();	
			$set_scan = array(
				'status_scan'=> 1,
			);

				$this->db->where('serial_number', $serial_number);
				$this->db->update('tr_shipping_list_ev_accoem', $set_scan);
	
				if (!$this->db->trans_status()) {
					$this->db->trans_rollback();
					$status = 0;
				} else {
					$status = 1;
					$this->db->trans_commit();
					$id_user = $this->session->userdata('id_user');

		
				$fifo = $this->m_admin->cari_fifo_oem($year);

				$set_acc = array(
					'acc' => '2',
					'accType' =>'B',
					'part_id' =>$set_id_part,
					'part_desc' =>$set_part_desc,
					'serial_number' => $serial_number,
					'user' => $id_user,
					'fifo' => $fifo,
					'tgl_shipping_list' => $tgl_shipping_list,
					'no_shipping_list' => $set_shipping_list,
					'ready_for_sale'=>$ready_sale,
					'status'=>1
				);


				$stock = array(
					'acc_status' => '2',
					'tipe' =>'B',
					'part_id' =>$set_id_part,
					'part_desc' =>$set_part_desc,
					'serial_number' => $serial_number,
					'fifo' => $fifo,
					'tgl_shipping_list' => $tgl_shipping_list,
					'no_shipping_list' => $set_shipping_list,
					'ready_for_sale'=>$ready_sale,
					'tanggal_terima_md' =>$waktu,
					'status'=>1
				);

				$this->db->insert('tr_stock_battery', $stock);
				$this->ev_model->InsertAcc($set_acc);

				$oem = $this->db->query("SELECT * , COUNT(1) as jumlah  from tr_penerimaan_battery pd where pd.id_penerimaan_battery ='$penerimaan_oem'")->row();
				$check_juml=intval($oem->jumlah);
			
	
				if ($check_juml > 0) {
					$qty = 1;
					$qty_adjust = (int)$oem->qty + $qty;

					$qty_oem = $this->db->query("SELECT COUNT(1) as jumlah_ahm  from tr_shipping_list_ev_accoem oem left join tr_penerimaan_unit_detail pu 
					on oem.no_shipping_list = pu.no_shipping_list 
					where pu.id_penerimaan_unit ='$penerimaan_oem' 
					group by pu.id_penerimaan_unit ")->row();
					$qty_ahm = $qty_oem->jumlah_ahm;
	
	
					$result = array(
						'id_penerimaan_battery' => $penerimaan_oem,
						'qty' =>    $qty_adjust,
						'qty_ahm' =>    $qty_ahm,
						'updated_by' => $login_id,
						'updated_at' => $waktu,
					);
	
					$this->db->where('id_penerimaan_battery_int', $oem->id_penerimaan_battery_int);
					$this->db->update('tr_penerimaan_battery', $result);
	
					$login_id = $this->session->userdata('id_user');
		
					$detail_oem = array(
						'id_penerimaan_battery' =>$penerimaan_oem,  
						'id_part' 			=>$querys->part_id,    
						'serial_number' 	=>$querys->serial_number, 
						'status_md'			=>$ready_sale,     
						'id_user' 			=>$login_id,
						'no_shipping_list' => $set_shipping_list,      
					);
					$this->db->insert('tr_penerimaan_battery_detail', $detail_oem);
	
				} else {
	
					$qty = 1;
					$qty_adjust = $qty;

					$qty_oem = $this->db->query("SELECT COUNT(1) as jumlah_ahm  from tr_shipping_list_ev_accoem oem left join tr_penerimaan_unit_detail pu 
					on oem.no_shipping_list = pu.no_shipping_list 
					where pu.id_penerimaan_unit ='$penerimaan_oem' 
					group by pu.id_penerimaan_unit ")->row();
	
					$qty_ahm = $qty_oem->jumlah_ahm;
	
					$result = array(
						'id_penerimaan_battery_int' => NULL,
						'id_penerimaan_battery' => $penerimaan_oem,
						'qty' =>$qty_adjust,
						'qty_ahm' => $qty_ahm,
						'no_sl'=>$querys->no_shipping_list,
						'status'=> 'input',
						'created_by'=>  $login_id,
						'created_at' =>  $waktu,
					);
	
					$this->db->insert('tr_penerimaan_battery', $result);
	
					$login_id = $this->session->userdata('id_user');
					$fifo = $this->m_admin->cari_fifo_oem($year);
		
					$detail_oem = array(
						'id_penerimaan_battery' =>$penerimaan_oem,  
						'id_part' 			=>$querys->part_id,    
						'serial_number' 	=>$querys->serial_number, 
						'status_md'			=>$ready_sale,     
						'id_user' 			=>$login_id,    
						'no_shipping_list' => $set_shipping_list,      
					);
					$this->db->insert('tr_penerimaan_battery_detail', $detail_oem);
				}
			}
		}
	}

		$query_result = $this->db->query("SELECT 
		ev_oem.part_id,
		ev_oem.part_desc,
		ev_oem.serial_number,
		ev_oem.no_shipping_list  as no_shipping_list,
		ev_oem.tgl_shipping_list as tgl_shipping_list,
		CONCAT('E20') as kode_dealer_md,
		ev_oem.tanggal_terima_md as created_at,
		poem.status_md as status_ready,
		ev_oem.fifo
		from tr_stock_battery ev_oem 
		left join tr_penerimaan_unit_detail pu on pu.no_shipping_list=ev_oem.no_shipping_list
		left join tr_penerimaan_battery_detail poem on ev_oem.serial_number =  poem.serial_number
		left join tr_penerimaan_battery pb on pb.id_penerimaan_battery = poem.id_penerimaan_battery
		$wheresjson group by poem.serial_number");

		$result = $query_result->result_array(); 
		$status = 1;
        $response = array(
			'status' => $status,
			'data'   => $result,
		);

        header('Content-Type: application/json');
        echo json_encode($response);
	}

	
	public function ksu()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Penerimaan KSU";	
		$id 						= $this->input->get("id");	
		$data['set']		= "ksu";		
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
			ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");						
		$dq = "SELECT DISTINCT(tr_scan_barcode.tipe_motor),tr_scan_barcode.no_shipping_list,tr_scan_barcode.tipe_motor,ms_tipe_kendaraan.`tipe_ahm`,ms_warna.`warna`,ms_warna.`id_warna`,tr_scan_barcode.`id_item` 
					FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.`no_shipping_list`		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' 
					-- AND tr_scan_barcode.status = '5'
					ORDER BY tr_scan_barcode.no_shipping_list ASC";
		$data['dt_rfs'] = $this->db->query($dq);		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}


	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_ksu 		= $this->input->post('id_ksu');		
		$id_pu 			= $this->input->post('id_pu');		
		$cek = 0;
		foreach($id_ksu AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$id_tipe_kendaraan = $_POST['tipe_motor'][$key];
			$id_warna = $_POST['id_warna'][$key];
			$total_unit  	= $_POST['total_unit'][$key];
		 	$qty  	= $_POST['qty'][$key];
			$no_sl = $_POST['no_sl'][$key];
		 	$result[] = array(
				"id_penerimaan_unit"  => $id_pu,
				"id_ksu"  => $_POST['id_ksu'][$key],
				"qty"  => $_POST['qty'][$key],
				"qty_ahm"  => $_POST['qty_ahm'][$key],
				"qty_eks"  => $_POST['qty_eks'][$key],
				"id_tipe_kendaraan"  => $_POST['tipe_motor'][$key],
				"id_warna"  => $_POST['id_warna'][$key],
				"no_sl"  => $_POST['no_sl'][$key],
				"created_at"  => $waktu,
				"created_by"  => $login_id,
				"status"  => "1"
		 	); 
		 	
		 	$this->m_admin->update_ksu($id_ksu,$qty,"+");
		 	if($total_unit < $qty){
		 		$cek = $cek + 1;		 		
		 	}

		 	$rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$id_ksu' AND id_warna = '$id_warna' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND no_sl = '$no_sl' AND id_penerimaan_unit = '$id_pu'");
			if($rty->num_rows() > 0){
				$e = $rty->row();      	
				$this->db->query("DELETE FROM tr_penerimaan_ksu WHERE id_penerimaan_ksu = '$e->id_penerimaan_ksu'");
			}
      	//$cek_ksu_gudang = $this->
		}

		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty KSU tidak boleh lebih dari jumlah unit yg disediakan";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/ksu?id=".$id_pu."'>";
		}else{
			$test2 = $this->db->insert_batch('tr_penerimaan_ksu', $result);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/ksu?id=".$id_pu."'>";
		}		
	}	

	public function close_ksu(){
		$id_pu 			= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		

		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$data['status']						= "close ksu";	
		$this->m_admin->update($tabel,$data,$pk,$id_pu);
		//$this->db->query("UPDATE tr_penerimaan_unit SET status = 'close scan' WHERE id_penerimaan_unit = '$id_pu'");
		$_SESSION['pesan'] 	= "Status has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/'>";
	}	

	public function close_oem(){
		$id_pu 			= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= 'tr_penerimaan_battery';
		$pk				= 'id_penerimaan_battery';		

		$oem = $this->db->query("SELECT * FROM tr_penerimaan_battery WHERE qty = qty_ahm and id_penerimaan_battery ='$id_pu'");

		if($oem->num_rows() >0){
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$data['status']					= "close oem";	
		
		$this->m_admin->update($tabel,$data,$pk,$id_pu);

			$_SESSION['pesan'] 	= "Status has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/'>";

		}else{
			$_SESSION['pesan'] 	= "Mohon Periksa Qty | Qty Tidak Sama ";
			$_SESSION['tipe'] 	= "error";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_ksu/'>";
		}


	}	



}