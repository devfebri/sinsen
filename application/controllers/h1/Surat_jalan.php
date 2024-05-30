<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surat_jalan extends CI_Controller 
{

	var $tables =   "tr_surat_jalan";
	var $folder =   "h1";
	var $page		=		"surat_jalan";
	var $pk     =   "no_surat_jalan";
	var $title  =   "Surat Jalan (SJ)";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_surat_jalan_datatables');
		$this->load->model('m_surat_jalan_history_datatables');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('CustomFPDF');			
		$this->load->library('cfpdf');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}

	public function battery_stok()
	{	
		$no_surat_sppm   = $this->input->post('id');
		$no_picking_list = $this->input->post('no_pl');
		$wheresjson = "WHERE 1=1 ";
		$wheresjson .= "AND tr_sppm.no_surat_sppm ='$no_surat_sppm' ";
		$query_result = $this->db->select('sb.tipe, sb.part_id, sb.part_desc, sb.serial_number, sb.fifo')
						->from('tr_picking_list_battery AS plb')
						->join('tr_picking_list AS pl', 'pl.no_picking_list = plb.no_picking_list', 'left')
						->join('tr_stock_battery AS sb', 'plb.serial_number = sb.serial_number', 'left')
						->where('pl.no_picking_list', $no_picking_list)
						->get();
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

	


	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view";

		$this->db->select('tr_surat_jalan.no_picking_list, tr_surat_jalan.status, tr_surat_jalan.no_surat_jalan,
						ms_dealer.nama_dealer, tr_surat_jalan.tgl_surat');
		$this->db->from('tr_surat_jalan');
		$this->db->join('ms_dealer', 'tr_surat_jalan.id_dealer = ms_dealer.id_dealer');
		$this->db->where('tr_surat_jalan.created_at >', '2022-10-01');
		$this->db->where_not_in('tr_surat_jalan.status', array('close', 'cancel'));
		$this->db->order_by('tr_surat_jalan.no_surat_jalan', 'DESC');
		$query = $this->db->get();
		$data['dt_sj'] = $query;

		// $data['dt_sj'] = $this->db->query("SELECT tr_surat_jalan.no_picking_list,tr_surat_jalan.status,tr_surat_jalan.no_surat_jalan,
		// 	ms_dealer.nama_dealer,tr_surat_jalan.tgl_surat FROM tr_surat_jalan INNER JOIN ms_dealer 
		// 	ON tr_surat_jalan.id_dealer=ms_dealer.id_dealer
		// 	WHERE tr_surat_jalan.created_at >'2022-10-01' AND tr_surat_jalan.status not in ('close','cancel') ORDER BY tr_surat_jalan.no_surat_jalan DESC");
		$this->template($data);
	}

	public function index2()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "view_new";		
		$this->template($data);
	}
	
	public function cek_history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "history";
		$data['dt_sj'] = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer=ms_dealer.id_dealer WHERE tr_surat_jalan.status in ('close','cancel') LIMIT 1");
		$this->template($data);
	}
	
	public function fetch_data_datatables()
	{
		$list = $this->m_surat_jalan_datatables->get_datatables();
		$data = array();
		$no = $_POST['start'];
        foreach($list as $row) {       
			$cari = $this->db->query("SELECT tgl_pl,no_do FROM tr_picking_list WHERE no_picking_list = '$row->no_picking_list'");
            if($cari->num_rows() > 0){
              $t = $cari->row();
              $tgl = $t->tgl_pl;
              $no_do = $t->no_do;
            }else{
              $tgl = "";
              $no_do = "";
            }

            $print='';

            if($row->status=='input'){
              $status = "<span class='label label-danger'>Input</span>";            
             // $t1 = "<a data-toggle=\"tooltip\" title=\"Edit\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/surat_jalan/edit?k=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-edit\"></i></a>";
              $t1='';
              $t2 = "<a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
              $t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
            }elseif($row->status=='proses'){
              $status = "<span class='label label-primary'>Proses</span>";
              $t1 = "";
              $t2 = "<a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
              $t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
            }elseif($row->status=='close'){
              $status = "<span class='label label-success'>Close</span>";
              $t1 = "";
              $t3 = "";
              $t2 = "";
            }

            $cek = $this->db->query("SELECT no_surat_jalan FROM tr_surat_jalan_ksu WHERE no_surat_jalan = '$row->no_surat_jalan'");
            if($cek->num_rows() == 0){
              $t2 = "";
            }

            $link = " href='h1/picking_list/detail?id=$row->no_picking_list'";

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = "<a title='View Data'". $link.">".$row->no_picking_list."</a>";
			$rows[] = $tgl;
			$rows[] = $row->no_surat_jalan;
			$rows[] = $row->tgl_surat;
			$rows[] = $row->no_do;
			$rows[] = $row->nama_dealer;
			$rows[] = $status;
			$rows[] = $t1.$t2.$t3;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_surat_jalan_datatables->count_all(),
			"recordsFiltered" => $this->m_surat_jalan_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function fetch_data_cek_history_datatables()
	{
		$list = $this->m_surat_jalan_history_datatables->get_datatables();
		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");
		$print = $this->m_admin->set_tombol($id_menu,$group,'print');

        foreach($list as $row) {       

			if (!empty($row->no_surat_jalan )) {
				$link_surat_jalan=" <a href='h1/surat_jalan/detail?id=$row->no_surat_jalan' >
				$row->no_surat_jalan
			  </a>";
			}else{
				$link_surat_jalan = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

  
			  $cek = $this->db->query("SELECT no_surat_jalan FROM tr_surat_jalan_ksu WHERE no_surat_jalan = '$row->no_surat_jalan'");
			  if($cek->num_rows() == 0){
				$button_cetak_surat_jalan = "";
			  }

			  
			  if (!empty($row->status)) {
				$button_cetak_surat_jalan=" <a href='h1/surat_jalan/detail?id=$row->no_surat_jalan'  $print ><a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
				if($row->status=='input'){
					$t1='';
					$button_cetak_surat_jalan=" <a href='h1/surat_jalan/detail?id=$row->no_surat_jalan'  $print ><a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
					$t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
				  }elseif($row->status=='proses'){
					$status = "<span class='label label-primary'>Proses</span>";
					$button_cetak_surat_jalan=" <a href='h1/surat_jalan/detail?id=$row->no_surat_jalan'  $print ><a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
					$t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
				  }elseif($row->status=='close'){
					$t1 = "";
					$button_cetak_surat_jalan=" <a href='h1/surat_jalan/detail?id=$row->no_surat_jalan'  $print ><a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak_ulang?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
					$t2 = "";
				  }
			
			}else{
				$link_surat_jalan = "<span class='label label-danger'>Tidak Ditemukan</span>";
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $row->no_picking_list;
			$rows[] = $row->tgl_pl;
			$rows[] = $link_surat_jalan;
			$rows[] = $row->tgl_surat;
			$rows[] = $row->no_do;
			$rows[] = $row->nama_dealer;
			$rows[] = $button_cetak_surat_jalan;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_surat_jalan_history_datatables->count_all(),
			"recordsFiltered" => $this->m_surat_jalan_history_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function detail()
	{
		$id = $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "detail";
		$this->db->select('*');
		$this->db->from('tr_surat_jalan');
		$this->db->join('tr_surat_jalan_detail', 'tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan');
		$this->db->join('tr_picking_list', 'tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list');
		$this->db->join('ms_dealer', 'tr_surat_jalan.id_dealer = ms_dealer.id_dealer');
		$this->db->where('tr_surat_jalan.no_surat_jalan', $id);
		$data['dt_sj'] = $this->db->get();
		
		$this->template($data);
	}

	public function save_nosin()
	{
		$scan_nosin 	= $this->input->post('scan_nosin');
		$no_do 				= $this->input->post('no_do');
		$data["scan"] = "ya";
		$data["no_mesin"]  			= $scan_nosin;
		$cek_nosin = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 
	      WHERE tr_picking_list.no_do ='$no_do' AND tr_picking_list_view.status = 'input' AND tr_picking_list_view.konfirmasi = 'ya' AND tr_picking_list_view.no_mesin = '$scan_nosin'
	      AND tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)");
		//$cek = $this->m_admin->getByID("tr_surat_jalan_detail","no_mesin",$scan_nosin);
		$cek = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_mesin = '$scan_nosin' AND retur = 0");
		if ($cek->num_rows() == 0) {
			$this->m_admin->insert("tr_surat_jalan_detail", $data);
		}

		echo "ok";
	}
	public function t_sj()
	{
		$id = $this->input->post('no_surat_sppm');
		$k 	= $this->input->post('k');
		$dq = "SELECT * FROM tr_sppm_detail INNER JOIN tr_sppm ON tr_sppm_detail.no_surat_sppm = tr_sppm.no_surat_sppm
						WHERE tr_sppm.no_surat_sppm = '$id'";
		$data['dt_sj'] = $this->db->query($dq);
		$data['no_surat_sppm'] = $id;

		if ($k == 'konfirm') {
			$this->load->view('h1/t_sj_k', $data);
		} else {
			$this->load->view('h1/t_sj', $data);
		}
	}

	public function t_sj_nosin()
	{
		$no_do 	= $this->input->post('no_do');
		$dq 		= $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 						
						WHERE tr_picking_list.no_do = '$no_do'");
		$data['dt_sj'] 	= $dq;
		$data['no_sj']	= $this->input->post('no_sj');
		$data['id']			= $this->input->post('id');
		$this->load->view('h1/t_sj_nosin', $data);
	}
	public function t_sj_ksu()
	{
		$no_pl          = $this->input->post('no_pl');
		$data['no_sj'] = $no_surat_jalan = $this->input->post('no_surat_jalan');
		// $dq 		= $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_picking_list_detail ON tr_picking_list.no_picking_list = tr_picking_list_detail.no_picking_list						
		// 				WHERE tr_picking_list.no_picking_list = '$no_pl' AND tr_picking_list_detail.qty_do > 0");
		$dq = $this->db->query("SELECT * FROM tr_sppm_detail 
							INNER JOIN tr_surat_jalan ON tr_sppm_detail.no_surat_sppm=tr_surat_jalan.no_surat_sppm
							INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list=tr_picking_list.no_picking_list
							WHERE no_surat_jalan='$no_surat_jalan'");
		$data['dt_sj'] 	= $dq;
		$data['no_pl']	= $this->input->post('no_pl');
		$this->load->view('h1/t_sj_ksu', $data);
	}

	public function cari_lain()
	{
		$no_surat_sppm = $this->input->post('no_surat_sppm');
		$dq = $this->db->query("SELECT * FROM tr_sppm INNER JOIN tr_do_po ON tr_sppm.no_do=tr_do_po.no_do
						INNER JOIN tr_picking_list ON tr_picking_list.no_do=tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer=ms_dealer.id_dealer
						WHERE tr_sppm.no_surat_sppm = '$no_surat_sppm'")->row();
		echo "ok|" . $dq->no_picking_list . "|" . $dq->no_do . "|" . $dq->nama_dealer . "|" . $dq->id_dealer;
	}
	
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$this->template($data);
	}

	public function cari_id_sj($tgl_surat, $no_pl)
	{
		$tgl_surat  = explode('-', $tgl_surat);
		$th 				= date("Y-m");

		$cek_gudang = $this->db->query("SELECT * from tr_picking_list inner join tr_do_po on tr_picking_list.no_do = tr_do_po.no_do where tr_picking_list.no_picking_list ='$no_pl' ")->row();
		$pr_num 			= $this->db->query("SELECT * FROM tr_surat_jalan WHERE LEFT(tgl_surat,7) = '$th' ORDER BY no_surat_jalan DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->no_surat_jalan) - 24;
			$id 	= substr($row->no_surat_jalan, $pan, 5) + 1;
			$new_sj = sprintf("%05d", $id) . '/' . 'SJ-E20-' . $cek_gudang->id_gudang . '/' . $tgl_surat[1] . '/' . $tgl_surat[0];
		} else {
			$new_sj = sprintf("%05d", 1) . '/' . 'SJ-E20-' . $cek_gudang->id_gudang . '/' . $tgl_surat[1] . '/' . $tgl_surat[0];
		}
		return $new_sj;
	}
	// public function tes_pl(){
	// 	$no_surat_jalan = $this->input->get('no');
	// 	$sql = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
	// 		WHERE tr_surat_jalan.no_surat_jalan = '$no_surat_jalan'");
	// 	foreach ($sql->result() as $isi) {			
	// 		$no_sppm = $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_sppm ON tr_picking_list.no_do = tr_sppm.no_do 
	// 			WHERE tr_picking_list.no_picking_list = '$isi->no_picking_list'")->row()->no_surat_sppm;
	// 		if($isi->ceklist == 'tidak'){
	// 			$jumo = $this->db->query("SELECT * FROM tr_sppm_detail WHERE no_surat_sppm = '$no_sppm' AND id_item = '$isi->id_item'")->row();
	// 			if($jumo->qty_ambil < $jumo->qty_do){
	// 				$hasil = $jumo->qty_ambil + 1;
	// 			}
	// 			$this->db->query("UPDATE tr_sppm_detail SET qty_ambil = '$hasil' WHERE no_surat_sppm = '$no_sppm' AND id_item = '$isi->id_item'");
	// 		}
	// 	}
	// }


	public function save()
	{
		$waktu 			= date("Y-m-d H:i:s");
		// $waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$id_item		= $this->input->post("id_item");
		$no_pl 			= $this->input->post('no_pl');
		$tgl_surat 		= $this->input->post('tgl_surat');
		$oem 			= $this->input->post('oem');

		if($no_pl ==''){
			$_SESSION['pesan'] 	= "Data tidak lengkap !. Silahkan refresh halaman.";
			$_SESSION['tipe'] 	= "warning";
			echo "<script>history.go(-1)</script>";
			exit;
		}
		$new_sj = $this->cari_id_sj($tgl_surat, $no_pl);
		$jum = $this->input->post("jum");	

		// initial EV 
		$is_ev = array(); 
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["check_sj_".$i])){
				$check 	= "ya";
			}else{
				$check 	= "tidak";
			}
			$nosin = $_POST["no_mesin_".$i];
			$id_item = $_POST["id_item_".$i];

			$result = array(
				"no_surat_jalan" 	=> $new_sj,
				"no_mesin"  			=> $nosin,
				"id_item"  				=> $id_item,
				"ceklist"  				=> $check,
			);

			// check tipe EV
			$is_ev[]=substr($id_item,0,3);

			$no_sppm = $this->db->query("SELECT tr_sppm.no_surat_sppm, tr_picking_list.no_do FROM tr_picking_list INNER JOIN tr_sppm ON tr_picking_list.no_do = tr_sppm.no_do 
				WHERE tr_picking_list.no_picking_list = '$no_pl'")->row();
			
			if ($check == 'tidak') {
				$jumo = $this->db->query("SELECT * FROM tr_sppm_detail WHERE no_surat_sppm = '$no_sppm->no_surat_sppm' AND id_item = '$id_item'")->row();
				if ($jumo->qty_ambil <= $jumo->qty_do and $jumo->qty_ambil > 0) {
					$hasil = $jumo->qty_ambil - 1;
				}else{
					$hasil = $jumo->qty_ambil;
				}
				$this->db->query("UPDATE tr_sppm_detail SET qty_ambil = '$hasil' WHERE no_surat_sppm = '$no_sppm' AND id_item = '$id_item'");
			}

			//$cek = $this->m_admin->getByID("tr_surat_jalan_detail","no_mesin",$nosin);
			$cek = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_mesin = '$nosin' AND retur = 0");
			if ($cek->num_rows() == 0) {
				$dt_ins[] = $result;
			} else {
				$dt_upd[] = $result;
			}
		}


		if (empty($dt_ins) && empty($dt_upd)) {
			$_SESSION['pesan'] 	= "Data tidak bisa disimpan karena detail masih kosong !. Silahkan lakukan generate data kembali.";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
			exit;
		} else {
			if (isset($dt_ins)) {
				$testb = $this->db->insert_batch('tr_surat_jalan_detail', $dt_ins);
			}
			if (isset($dt_upd)) {
				$testb = $this->db->update_batch('tr_surat_jalan_detail', $dt_upd, 'no_mesin');
			}
		}

		$data['no_surat_jalan'] 	= $new_sj;
		$data['tgl_surat'] 				= $this->input->post('tgl_surat');
		$data['id_dealer'] 				= $this->input->post('id_dealer');
		$data['no_surat_sppm'] 		= $this->input->post('no_surat_sppm');
		$data['no_picking_list'] 	= $this->input->post('no_pl');
		$data['cara_ambil'] 			= $this->input->post('cara_ambil');
		$data['ket'] 							= $this->input->post('ket');
		$data['status'] 					= "input";
		$data['created_at']				= $waktu;
		$data['created_by']				= $login_id;
		$this->m_admin->insert("tr_surat_jalan", $data);

		$id_dealer_set = $this->input->post('id_dealer');
		$kode_dealer_md	= $this->m_admin->cari_kode_dealer($id_dealer_set);

		$this->db->select('id_kategori,id_tipe_kendaraan');
		$this->db->from('ms_tipe_kendaraan');
		$this->db->where_in('id_tipe_kendaraan', $is_ev);
		$this->db->where('id_kategori', 'ev');
		$check_ev_unit = $this->db->get();

		if($check_ev_unit->num_rows() > 0){
			foreach ($oem['no_picking_list_battery'] as $key => $values) {
				$serial_number = $oem['serial_number'][$key];
				$id_part = $oem['part'][$key];
				$checklist = NULL;

				if(isset($oem['konfirmasi'][$key])){
					$checklist = 'ya';
				}

				$checklist = 'ya';
				$detail_sj_battery = array(
					'no_surat_jalan' =>  $new_sj,
					'id_part' => $id_part,
					'serial_number' => $oem['serial_number'][$key],
					'ceklist' =>$checklist,
					'no_do' =>$no_sppm->no_do,
					'terima' => NULL,
					'scan' => 'ya',
					'retur' => 0,
				);
		
				$this->db->insert('tr_surat_jalan_battery_detail', $detail_sj_battery);

				$update = array(
					'id_dealer' =>$data['id_dealer'],
					'no_surat_jalan' =>$new_sj,
					'acc_status' =>3,
					'tgl_surat_jalan' => $waktu 
					// 'id_picking_list' =>$ev_no_picking_list,
				);

				$this->m_admin->update("tr_stock_battery", $update, "serial_number",$serial_number);
				$set_acc = array(
					'acc' => '3',
					'accType' => 'B',
					'serial_number' => $serial_number,
					'mdSLNo' =>$new_sj,
					'mdSLDate' => $waktu,
					'dealerCode' =>$kode_dealer_md,
					'user' => $login_id,
				);
				$this->load->model('ev_model');
				$this->ev_model->InsertAcc($set_acc);
			}
		}

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/surat_jalan'>";
	}


	public function edit()
	{
		$no_sj = $this->input->get('id');
		$data['isi']    = $this->page;
		$data['title']	= "Edit Surat Jalan";
		$data['set']		= "edit";
		$data['dt_sj'] = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan 
						INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list=tr_picking_list.no_picking_list
						INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer=ms_dealer.id_dealer
						WHERE tr_surat_jalan.no_surat_jalan = '$no_sj'");
		$this->template($data);
	}
	public function edit_nosin()
	{
		$no_sj 					= $this->input->get('no');
		$id_sj_detail 	= $this->input->get('id');
		$data['isi']    = $this->page;
		$data['title']	= "Edit Surat Jalan";
		$data['set']		= "edit_nosin";
		$data['no_sj']	= $no_sj;
		$data['dt_sj'] 	= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan 
						INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list=tr_picking_list.no_picking_list
						INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer=ms_dealer.id_dealer
						WHERE tr_surat_jalan.no_surat_jalan = '$no_sj'");
		$this->template($data);
	}
	public function ubah_nosin()
	{
		$id				= $this->input->get("id");
		$no_sj		= $this->input->get("no_sj");

		$dat['pengganti'] 			= $this->input->get("nosin");
		$dat['no_do'] 					= $this->input->get("no_do");
		$dat['status_nosin'] 		= "waiting";
		$this->m_admin->update("tr_surat_jalan_detail", $dat, "id_surat_jalan_detail", $id);

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/surat_jalan/edit?k=konfirm&id=" . $no_sj . "'>";
	}
	public function approval()
	{
		$no_sj 					= $this->input->get('id');
		$data['isi']    = $this->page;
		$data['title']	= "Approval Surat Jalan";
		$data['set']		= "approval";
		$data['no_sj']	= $no_sj;
		$data['dt_sj'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan = '$no_sj' AND status_nosin = 'waiting'");
		$this->template($data);
	}
	public function save_approval()
	{
		$id_surat_jalan_detail		= $this->input->post("id_surat_jalan_detail");
		$no_sj		= $this->input->post("no_sj");

		foreach ($id_surat_jalan_detail as $key => $val) {
			$id_surat_jalan_detail	= $_POST['id_surat_jalan_detail'][$key];
			$data["no_mesin"] 		= $_POST['no_mesin'][$key];
			$data["pengganti"] 		= $_POST['pengganti'][$key];
			$data["status_nosin"] = "approved";
			$this->m_admin->update("tr_surat_jalan_detail", $data, "id_surat_jalan_detail", $id_surat_jalan_detail);
		}

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/surat_jalan/edit?k=konfirm&id=" . $no_sj . "'>";
	}
	public function update()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_surat_jalan	= $this->input->post("no_surat_jalan");

		$data['no_surat_jalan'] 	= $no_surat_jalan;
		$data['tgl_surat'] 				= $this->input->post('tgl_surat');
		$data['cara_ambil'] 			= $this->input->post('cara_ambil');
		$data['ket'] 							= $this->input->post('ket');
		$data['updated_at']				= $waktu;
		$data['updated_by']				= $login_id;
		$this->m_admin->update("tr_surat_jalan", $data, "no_surat_jalan", $no_surat_jalan);

		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/surat_jalan'>";
	}

	public function ksu()
	{
		$no_sj 					= $this->input->get('id');
		$data['isi']    = $this->page;
		$data['title']	= "Picking List KSU";
		$data['set']		= "ksu";
		$data['no_sj']	= $no_sj;
		$this->db->select('tr_surat_jalan.*, tr_do_po.no_do, tr_do_po.tgl_do, tr_picking_list.tgl_pl, ms_gudang.gudang, ms_dealer.kode_dealer_md, ms_dealer.nama_dealer');
		$this->db->from('tr_surat_jalan');
		$this->db->join('tr_surat_jalan_detail', 'tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan', 'left');
		$this->db->join('tr_picking_list', 'tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list', 'left');
		$this->db->join('ms_dealer', 'tr_surat_jalan.id_dealer = ms_dealer.id_dealer', 'left');
		$this->db->join('tr_do_po', 'tr_picking_list.no_do = tr_do_po.no_do', 'left');
		$this->db->join('ms_gudang', 'tr_do_po.id_gudang = ms_gudang.id_gudang', 'left');
		$this->db->where('tr_surat_jalan.no_surat_jalan', $no_sj);
		$data['dt_sj'] =  $this->db->get();
		$this->template($data);
	}
	public function save_ksu()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_sj			= $this->input->post("no_sj");
		$no_do			= $this->input->post("no_do");
		$id_ksu			= $this->input->post("id_ksu");
		$isian			= $this->input->post("isian");
		$data['no_pl_ksu'] 		= $this->m_admin->cari_id("tr_surat_jalan_ksu_pl", "id_surat_jalan_ksu_pl");
		$data['tgl_pl_ksu'] 	= $tgl;
		$data['no_do'] 		 	= $no_do;
		$data['no_surat_jalan'] = $no_sj;
		$data['created_at'] 	= $waktu;
		$data['created_by'] 	= $login_id;

		$xx = $this->input->post('xx');
		$cek = 0;
		for ($i = 0; $i <= $xx; $i++) {
			$result[$i] = array(
				"no_do" 	=> $this->input->post('no_do_' . $i),
				"no_surat_jalan" 	=> $no_sj,
				"id_ksu"  => $this->input->post('id_ksu_add_' . $i),
				"id_item"  				=>  $this->input->post('id_item_add_' . $i),
				"qty_do"  				=>  $this->input->post('qty_do_add_' . $i),
				"qty"  		=>  $this->input->post('qty_add_' . $i),
			);
			$id_ksu = $this->input->post('id_ksu_add_' . $i);
			$qty = $this->input->post('qty_add_' . $i);
			if ($this->input->post('id_ksu_add_' . $i) == null) {
				$cek++;
			} else {
				$this->m_admin->update_ksu($id_ksu, $qty, "-");
			}
		}
		// echo $cek;
		// var_dump($result);
		if ($cek >= 0) {
			$testb = $this->db->insert_batch('tr_surat_jalan_ksu', $result);
			$this->m_admin->insert("tr_surat_jalan_ksu_pl", $data);
		}

		$x = $this->input->post('x');
		for ($i = 0; $i <= $x; $i++) {
			$dtt[$i]['id_surat_jalan_ksu']				= $this->input->post('id_surat_jalan_ksu_' . $i);
			$dtt[$i]['qty'] 					= $this->input->post('qty_' . $i);
		}
		$this->db->update_batch("tr_surat_jalan_ksu", $dtt, 'id_surat_jalan_ksu');


		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/surat_jalan'>";
	}

	
	public function cetak()
 	 {    
		$waktu 					= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id				= $this->session->userdata('id_user');
		$tabel					= $this->tables;
		$pk 					= $this->pk;
		$no_surat_jalan 		= $this->input->get('id');
		$data['status'] 		= "proses";
		$data['updated_at']		= $waktu;
		$data['updated_by']		= $login_id;

		$this->m_admin->update($tabel, $data, $pk, $no_surat_jalan);

		$this->db->select('*');
		$this->db->from('tr_surat_jalan');
		$this->db->join('tr_picking_list', 'tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list');
		$this->db->join('tr_do_po', 'tr_picking_list.no_do = tr_do_po.no_do', 'left');
		$this->db->where('tr_surat_jalan.no_surat_jalan', $no_surat_jalan);
		$get_sj = $this->db->get()->row();

		$this->db->select('tr_surat_jalan.*, ms_dealer.nama_dealer, ms_dealer.alamat, ms_gudang.gudang, tr_do_po.no_do, tr_do_po.source, tr_do_po.tgl_do, tr_invoice_dealer.no_faktur');
		$this->db->from('tr_surat_jalan');
		$this->db->join('tr_picking_list', 'tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list');
		$this->db->join('ms_dealer', 'tr_surat_jalan.id_dealer = ms_dealer.id_dealer');
		$this->db->join('tr_do_po', 'tr_picking_list.no_do = tr_picking_list.no_do');
		$this->db->join('ms_gudang', 'tr_do_po.id_gudang = ms_gudang.id_gudang');
		$this->db->join('tr_invoice_dealer', 'tr_do_po.no_do = tr_invoice_dealer.no_do');
		$this->db->where('tr_surat_jalan.no_surat_jalan', $no_surat_jalan);
		$this->db->where('tr_do_po.no_do', $get_sj->no_do);
		$get_sl = $this->db->get()->row();

		$this->db->select('ms_plat_dealer.no_plat, tr_sppm.driver');
		$this->db->from('tr_sppm');
		$this->db->join('ms_plat_dealer', 'tr_sppm.no_pol = ms_plat_dealer.id_master_plat', 'left');
		$this->db->where('tr_sppm.no_surat_sppm', $get_sj->no_surat_sppm);
		$get_sppm = $this->db->get()->row();

		global $no_surat_jalan,$tgl_surat,$no_do,$tgl_do,$gudang,$source,$driver,$nama_dealer,$no_plat,$alamat,$ket,$no_faktur;
  		  $pdf = $this->customfpdf->getInstance();        

		$no_surat_jalan 			= $this->input->get('id');
		$no_do = $get_sj->no_do;
		$tgl_surat = $get_sl->tgl_surat;
		$nama_dealer = $get_sl->nama_dealer;
		$gudang = $get_sl->gudang;
		$alamat = $get_sl->alamat;
		$ket = $get_sl->ket;
		$tgl_do = $get_sj->tgl_do;
		$source = $get_sj->source;
		$driver = $get_sppm->driver;
		$no_plat = $get_sppm->no_plat;
		$no_faktur = $get_sl->no_faktur;
		
		$pdf->AliasNbPages();
		$pdf->AddPage('p','A4');
		$pdf->SetAutoPageBreak(true, 80);
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(195, 10, 'UNIT', 0, 1, 'C');
		$pdf->SetFont('TIMES', 'B', 10);
		$pdf->Cell(2, 5, '', 5, 10);
		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(20, 5, 'Kode Item', 1, 0);
		$pdf->Cell(50, 5, 'Nama', 1, 0);
		$pdf->Cell(40, 5, 'Warna', 1, 0);
		$pdf->Cell(35, 5, 'No Mesin', 1, 0);
		$pdf->Cell(35, 5, 'No Rangka', 1, 1);

		$pdf->SetFont('times', '', 10);

		$this->db->select('*');
		$this->db->from('tr_surat_jalan_detail');
		$this->db->where('no_surat_jalan', $no_surat_jalan);
		$this->db->where('ceklist', 'ya');
		$get_nosin = $this->db->get();

		$i = 1;

		$count_ev = array();
		foreach ($get_nosin->result() as $r) {

				$this->db->select('ms_tipe_kendaraan.id_kategori, tr_scan_barcode.*, ms_tipe_kendaraan.tipe_ahm, ms_tipe_kendaraan.id_tipe_kendaraan, ms_warna.id_warna, ms_warna.warna');
				$this->db->from('tr_scan_barcode');
				$this->db->join('ms_item', 'tr_scan_barcode.id_item = ms_item.id_item');
				$this->db->join('ms_tipe_kendaraan', 'ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan');
				$this->db->join('ms_warna', 'ms_item.id_warna = ms_warna.id_warna');
				$this->db->where('tr_scan_barcode.no_mesin', $r->no_mesin);
				$cek_pik = $this->db->get();

			if ($cek_pik->num_rows() > 0) {
				$cek_pik = $cek_pik->row();
				if ($cek_pik->id_kategori == 'EV'){
					$count_ev[] = '1';
				}

				$pdf->Cell(10, 5, $i, 1, 0);
				$pdf->Cell(20, 5, $cek_pik->id_item, 1, 0);
				$pdf->Cell(50, 5, $cek_pik->tipe_ahm, 1, 0);
				$pdf->Cell(40, 5, $cek_pik->warna, 1, 0);
				$pdf->Cell(35, 5, strtoupper($r->no_mesin), 1, 0);
				$pdf->Cell(35, 5, $cek_pik->no_rangka, 1, 1);
			}
			$i++;
		}


		if (count($count_ev)>0) {
			// tabel EV
			$pdf->Cell(2, 5, '', 5, 10);
			$pdf->Cell(2, 3, '', 5, 10);
			$pdf->SetFont('TIMES', '', 12);
			$pdf->Cell(195, 1, 'KELENGKAPAN EV', 0, 1, 'C');
	
			$pdf->SetFont('TIMES', 'B', 10);
			$pdf->Cell(2, 5, '', 5, 10);
	
			$pdf->Cell(10, 5, 'No', 1, 0);
			$pdf->Cell(20, 5, 'Type', 1, 0);
			$pdf->Cell(50, 5, 'Kode Part', 1, 0);
			$pdf->Cell(50, 5, 'Nama Part', 1, 0);
			$pdf->Cell(60, 5, 'Serial Number', 1, 1);
			// $pdf->Cell(2, 5, '', 5, 10);
			
			$this->db->select('*');
			$this->db->from('tr_surat_jalan_battery_detail sbd');
			$this->db->join('tr_stock_battery sb', 'sb.serial_number = sbd.serial_number', 'left');
			$this->db->where('sbd.no_surat_jalan', $no_surat_jalan);
			$get_ev_oem = $this->db->get();

			$no=1;
				$pdf->SetFont('times', '', 10);
				foreach ($get_ev_oem->result() as $oem) {
					$pdf->Cell(10, 5, $no, 1, 0);
					$pdf->Cell(20, 5,$oem->tipe, 1, 0);
					$pdf->Cell(50, 5, $oem->part_id, 1, 0);
					$pdf->Cell(50, 5, $oem->part_desc, 1, 0);
					$pdf->Cell(60, 5, $oem->serial_number, 1, 1);
					$no++;
				}
				$pdf->Cell(2, 5, '', 5, 10);
			} 

		$k = $get_nosin->num_rows();
		$pdf->Cell(2, 3, '', 5, 10);
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(195, 1, 'AKSESORIS', 0, 1, 'C');
		$pdf->SetFont('TIMES', 'B', 10);
		$pdf->Cell(2, 5, '', 5, 10);
		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(80, 5, 'Nama Aksesoris', 1, 0);
		$pdf->Cell(20, 5, 'Qty', 1, 1);

		$pdf->SetFont('times', '', 10);
		$this->db->select('SUM(tr_surat_jalan_ksu.qty) as jum, ms_ksu.ksu');
		$this->db->from('tr_surat_jalan_ksu');
		$this->db->join('ms_ksu', 'tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu');
		$this->db->where('no_surat_jalan', $no_surat_jalan);
		$this->db->where('no_do', $get_sj->no_do);
		$this->db->where('tr_surat_jalan_ksu.qty >', 0);
		$this->db->group_by('tr_surat_jalan_ksu.id_ksu');
		$this->db->order_by('ms_ksu.ksu', 'asc');
		$get_ksu = $this->db->get();

		$h = 1;
		$j = 0;
		foreach ($get_ksu->result() as $r) {
			$pdf->Cell(10, 5, $h, 1, 0);
			$pdf->Cell(80, 5, $r->ksu, 1, 0);
			$pdf->Cell(20, 5, $r->jum, 1, 1);
			$h++;
			$j = $j + $r->jum;
		}

		$pdf->Cell(90, 5, 'Total', 1, 0, 'R');
		$pdf->Cell(20, 5, $j, 1, 2);
		$pdf->Cell(10, 5, '', 0, 1);
		$pdf->Cell(90, 5, 'Blangko Cek Fisik', 1, 0, 'L');
		$pdf->Cell(20, 5, $k . ' set', 1, 2);		
		$pdf->Output();
	}


	public function cetak_ulang()
  {    
    //$pdf = new CustomFPDF();

		
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$no_surat_jalan 			= $this->input->get('id');		


		$get_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list	 		
				LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_surat_jalan.no_surat_jalan ='$no_surat_jalan'")->row();


		$get_sl 	= $this->db->query("SELECT tr_surat_jalan.*,ms_dealer.nama_dealer,ms_dealer.alamat,ms_gudang.gudang,tr_do_po.no_do,tr_do_po.source,tr_do_po.tgl_do, tr_invoice_dealer.no_faktur
			 	FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
		 		INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
		 		INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_picking_list.no_do
		 		INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
				INNER JOIN tr_invoice_dealer ON tr_do_po.no_do = tr_invoice_dealer.no_do
		 		WHERE tr_surat_jalan.no_surat_jalan = '$no_surat_jalan' AND tr_do_po.no_do ='$get_sj->no_do'")->row();



		$get_sppm = $this->db->query("SELECT ms_plat_dealer.no_plat, tr_sppm.driver FROM tr_sppm 
										left join ms_plat_dealer on tr_sppm.no_pol = ms_plat_dealer.id_master_plat 
										WHERE tr_sppm.no_surat_sppm = '$get_sj->no_surat_sppm'")->row();

		global $no_surat_jalan,$tgl_surat,$no_do,$tgl_do,$gudang,$source,$driver,$nama_dealer,$no_plat,$alamat,$ket,$no_faktur;
    $pdf = $this->customfpdf->getInstance();        

		$no_surat_jalan 			= $this->input->get('id');
		$no_do = $get_sj->no_do;
		$tgl_surat = $get_sl->tgl_surat;
		$nama_dealer = $get_sl->nama_dealer;
		$gudang = $get_sl->gudang;
		$alamat = $get_sl->alamat;
		$ket = $get_sl->ket;
		$tgl_do = $get_sj->tgl_do;
		$source = $get_sj->source;
		$driver = $get_sppm->driver;
		$no_plat = $get_sppm->no_plat;
		$no_faktur = $get_sl->no_faktur;
		
		$pdf->AliasNbPages();
		$pdf->AddPage('p','A4');
		$pdf->SetAutoPageBreak(true, 80);
		//$pdf->SetMargins(10, 10);
		// $pdf->Line(11, 80, 200, 80);	
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(195, 10, 'UNIT', 0, 1, 'C');
		// buat tabel disini
		$pdf->SetFont('TIMES', 'B', 10);

		// kasi jarak
		$pdf->Cell(2, 5, '', 5, 10);

		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(20, 5, 'Kode Item', 1, 0);
		$pdf->Cell(50, 5, 'Nama', 1, 0);
		$pdf->Cell(40, 5, 'Warna', 1, 0);
		$pdf->Cell(35, 5, 'No Mesin', 1, 0);
		$pdf->Cell(35, 5, 'No Rangka', 1, 1);

		$pdf->SetFont('times', '', 10);
		$get_nosin 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan = '$no_surat_jalan' AND ceklist = 'ya'");
		$i = 1;
		foreach ($get_nosin->result() as $r) {
			$cek_pik = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_scan_barcode INNER JOIN ms_item 
          ON tr_scan_barcode.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
          ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
          ON ms_item.id_warna=ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$r->no_mesin'");
			if ($cek_pik->num_rows() > 0) {
				$cek_pik = $cek_pik->row();
				$pdf->Cell(10, 5, $i, 1, 0);
				$pdf->Cell(20, 5, $cek_pik->id_item, 1, 0);
				$pdf->Cell(50, 5, $cek_pik->tipe_ahm, 1, 0);
				$pdf->Cell(40, 5, $cek_pik->warna, 1, 0);
				$pdf->Cell(35, 5, strtoupper($r->no_mesin), 1, 0);
				$pdf->Cell(35, 5, $cek_pik->no_rangka, 1, 1);
			}
			$i++;
		}

		$k = $get_nosin->num_rows();


		// tabel kedua
		$pdf->Cell(2, 3, '', 5, 10);
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(195, 1, 'AKSESORIS', 0, 1, 'C');
		// buat tabel disini
		$pdf->SetFont('TIMES', 'B', 10);
		$pdf->Cell(2, 5, '', 5, 10);

		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(80, 5, 'Nama Aksesoris', 1, 0);
		$pdf->Cell(20, 5, 'Qty', 1, 1);

		$pdf->SetFont('times', '', 10);
		$get_ksu 	= $this->db->query("SELECT SUM(tr_surat_jalan_ksu.qty) as jum,ms_ksu.ksu FROM tr_surat_jalan_ksu 
	  		INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu WHERE no_surat_jalan = '$no_surat_jalan' 
	  		AND no_do='$get_sj->no_do' AND tr_surat_jalan_ksu.qty > 0 GROUP BY tr_surat_jalan_ksu.id_ksu order by ms_ksu.ksu asc");
		$h = 1;
		$j = 0;
		foreach ($get_ksu->result() as $r) {
			$pdf->Cell(10, 5, $h, 1, 0);
			$pdf->Cell(80, 5, $r->ksu, 1, 0);
			$pdf->Cell(20, 5, $r->jum, 1, 1);
			$h++;
			$j = $j + $r->jum;
		}

		$pdf->Cell(90, 5, 'Total', 1, 0, 'R');
		$pdf->Cell(20, 5, $j, 1, 2);

		$pdf->Cell(10, 5, '', 0, 1);
		$pdf->Cell(90, 5, 'Blangko Cek Fisik', 1, 0, 'L');
		$pdf->Cell(20, 5, $k . ' set', 1, 2);		
		// for ($i=0; $i < 60; $i++) { 			
		// 	$pdf->Cell(20, 5, $k . ' <br>', 1, 2);		
		// }
		$pdf->Output();
	}
	public function cetak_old()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$no_surat_jalan 			= $this->input->get('id');
		$data['status'] 			= "proses";
		//$data['title'] 				= "Cetak Picking List";
		$data['updated_at']		= $waktu;
		$data['updated_by']		= $login_id;

		$this->m_admin->update($tabel, $data, $pk, $no_surat_jalan);


		$get_sl 	= $this->db->query("SELECT tr_surat_jalan.*,ms_dealer.nama_dealer,ms_dealer.alamat,ms_gudang.gudang,tr_do_po.no_do,tr_do_po.source,tr_do_po.tgl_do
			 	FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
		 		INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
		 		INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_picking_list.no_do
		 		INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
		 		WHERE tr_surat_jalan.no_surat_jalan = '$no_surat_jalan'")->row();
		$get_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list	 		
				LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_surat_jalan.no_surat_jalan ='$no_surat_jalan'")->row();
		$get_sppm = $this->db->query("SELECT ms_plat_dealer.no_plat, tr_sppm.driver FROM tr_sppm 
										left join ms_plat_dealer on tr_sppm.no_pol = ms_plat_dealer.id_master_plat 
										WHERE tr_sppm.no_surat_sppm = '$get_sj->no_surat_sppm'")->row();


		$pdf = new FPDF('p', 'mm', 'A4');
		$pdf->AddPage();
		// head
		$pdf->SetFont('TIMES', '', 20);
		$pdf->Cell(190, 5, 'SURAT JALAN', 0, 1, 'C');
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(50, 5, 'Main Dealer: PT.Sinar Sentosa Primatama', 0, 1, 'L');
		$pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
		$pdf->Cell(50, 5, 'Telp: 0741-61551', 0, 1, 'L');
		$pdf->Line(11, 31, 200, 31);

		//$pdf->Image(base_url().'/assets/panel/images/logo_sinsen.jpg', 150, 15, 50);

		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(1, 2, '', 0, 1);
		$pdf->Cell(30, 5, 'No SJ ', 0, 0);
		$pdf->Cell(70, 5, ': ' . $no_surat_jalan . '', 0, 0);

		$pdf->Cell(30, 5, 'No DO ', 0, 0);
		$pdf->Cell(10, 5, ': ' . $get_sj->no_do . '', 0, 1);

		$pdf->Cell(30, 5, 'Tgl SJ', 0, 0);
		$pdf->Cell(70, 5, ': ' . $get_sl->tgl_surat . '', 0, 0);

		$pdf->Cell(30, 5, 'Tgl DO ', 0, 0);
		$pdf->Cell(10, 5, ': ' . $get_sj->tgl_do . '', 0, 1);

		$pdf->Cell(30, 5, 'Gudang ', 0, 0);
		$pdf->Cell(70, 5, ': ' . $get_sl->gudang . '', 0, 0);

		$pdf->Cell(30, 5, 'Tipe PO ', 0, 0);
		$pdf->Cell(10, 5, ': ' . strtoupper(str_replace("_", " ", $get_sj->source)) . '', 0, 1);

		$pdf->Cell(100, 5, '', 0, 0);

		$pdf->Cell(30, 5, 'Nama Driver ', 0, 0);
		$pdf->Cell(20, 5, ': ' . $get_sppm->driver, 0, 1);

		$pdf->Cell(100, 5, '', 0, 0);

		$pdf->Cell(30, 5, 'Penerima ', 0, 0);
		$pdf->MultiCell(70, 5, ': ' . $get_sl->nama_dealer . '', 0, "L");


		$pdf->Cell(30, 5, 'No Polisi ', 0, 0);
		$pdf->Cell(10, 5, ': ' . $get_sppm->no_plat, 0, 1);

		$pdf->Cell(100, 5, 'Alamat Penerima :' . $get_sl->alamat . '', 0, 1);

		$pdf->Cell(30, 5, 'Keterangan ', 0, 0);
		$pdf->Cell(100, 5, ': ' . $get_sl->ket . '', 0, 1);
		$pdf->Cell(190, 2, '', 'B', 1);
		// $pdf->Line(11, 80, 200, 80);	
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(195, 10, 'UNIT', 0, 1, 'C');
		// buat tabel disini
		$pdf->SetFont('TIMES', 'B', 10);

		// kasi jarak
		$pdf->Cell(2, 5, '', 5, 10);

		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(20, 5, 'Kode Item', 1, 0);
		$pdf->Cell(50, 5, 'Nama', 1, 0);
		$pdf->Cell(40, 5, 'Warna', 1, 0);
		$pdf->Cell(35, 5, 'No Mesin', 1, 0);
		$pdf->Cell(35, 5, 'No Rangka', 1, 1);

		$pdf->SetFont('times', '', 10);
		$get_nosin 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan = '$no_surat_jalan' AND ceklist = 'ya'");
		$i = 1;
		foreach ($get_nosin->result() as $r) {
			$cek_pik = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_scan_barcode INNER JOIN ms_item 
          ON tr_scan_barcode.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
          ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
          ON ms_item.id_warna=ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$r->no_mesin'");
			if ($cek_pik->num_rows() > 0) {
				$cek_pik = $cek_pik->row();
				$pdf->Cell(10, 5, $i, 1, 0);
				$pdf->Cell(20, 5, $cek_pik->id_item, 1, 0);
				$pdf->Cell(50, 5, $cek_pik->tipe_ahm, 1, 0);
				$pdf->Cell(40, 5, $cek_pik->warna, 1, 0);
				$pdf->Cell(35, 5, strtoupper($r->no_mesin), 1, 0);
				$pdf->Cell(35, 5, $cek_pik->no_rangka, 1, 1);
			}
			$i++;
		}

		$k = $get_nosin->num_rows();


		// tabel kedua
		$pdf->Cell(2, 3, '', 5, 10);
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(195, 1, 'AKSESORIS', 0, 1, 'C');
		// buat tabel disini
		$pdf->SetFont('TIMES', 'B', 10);
		$pdf->Cell(2, 5, '', 5, 10);

		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(80, 5, 'Nama Aksesoris', 1, 0);
		$pdf->Cell(20, 5, 'Qty', 1, 1);

		$pdf->SetFont('times', '', 10);
		$get_ksu 	= $this->db->query("SELECT SUM(tr_surat_jalan_ksu.qty) as jum,ms_ksu.ksu FROM tr_surat_jalan_ksu 
	  		INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu WHERE no_surat_jalan = '$no_surat_jalan' 
	  		AND no_do='$get_sj->no_do' AND tr_surat_jalan_ksu.qty > 0 GROUP BY tr_surat_jalan_ksu.id_ksu");
		$h = 1;
		$j = 0;
		foreach ($get_ksu->result() as $r) {
			$pdf->Cell(10, 5, $h, 1, 0);
			$pdf->Cell(80, 5, $r->ksu, 1, 0);
			$pdf->Cell(20, 5, $r->jum, 1, 1);
			$h++;
			$j = $j + $r->jum;
		}

		$pdf->Cell(90, 5, 'Total', 1, 0, 'R');
		$pdf->Cell(20, 5, $j, 1, 2);

		$pdf->Cell(10, 5, '', 0, 1);
		$pdf->Cell(90, 5, 'Blangko Cek Fisik', 1, 0, 'L');
		$pdf->Cell(20, 5, $k . ' set', 1, 2);

		// tanda tangan
		$pdf->Cell(9, 3, '', 5, 10);
		$pdf->SetFont('TIMES', '', 12);
		$pdf->Cell(10, 5, '', 0, 1);
		$pdf->Cell(70, 5, 'Diserahkan Oleh', 0, 0, 'C');
		$pdf->Cell(40, 5, 'Driver', 0, 0, 'C');
		$pdf->Cell(40, 5, 'Diperiksa Oleh', 0, 0, 'C');
		$pdf->Cell(40, 5, 'Diterima Oleh', 0, 1, 'C');
		$pdf->Cell(10, 15, '', 0, 1);
		$pdf->Cell(35, 5, '(Kepala Logistik)', 0, 0, 'C');
		$pdf->Cell(35, 5, '(Admin Warehouse)', 0, 0, 'C');
		$pdf->Cell(40, 5, '(                            )', 0, 0, 'C');
		$pdf->Cell(40, 5, '(      Security      )', 0, 0, 'C');
		$pdf->Cell(40, 5, '(                            )', 0, 1, 'C');
		// $pdf->Cell(35, 5, 'Kepala Gudang', 0, 0,'C');	  
		// $pdf->Cell(35, 5, 'Admin', 0, 0,'C');	  
		// $pdf->Cell(40, 5, 'Driver', 0, 0,'C');	  
		// $pdf->Cell(40, 5, 'Security', 0, 0,'C');	  	  
		// $pdf->Cell(40, 5, '', 0, 1,'C');


		$pdf->Cell(10, 5, '', 0, 1);

		$pdf->SetFont('TIMES', '', 9);
		$pdf->Cell(10, 3, 'Catatan', 0, 1, 'L');
		$pdf->Cell(10, 3, '* Bubuhkan Nama dan Tanda Tangan yang jelas', 0, 1, 'L');
		$pdf->Cell(10, 3, '* Dikirim dalam keadaan baik, lengkap, dan baru', 0, 1, 'L');
		$pdf->Cell(10, 3, '* Barang yang telah diperiksa dan diterima, menjadi tanggung jawab Penerima apabila ada kerusakan atau kehilangan.', 0, 1, 'L');
		$pdf->Cell(10, 3, '* Driver wajib memeriksa dan menerima barang dalam kondisi baik dan lengkap.', 0, 1, 'L');

		$pdf->Output();
	}

	public function penerimaan_dealer()
	{
		$no_sj = $this->input->get("no_sj");
		$data['isi']   = $this->page;
		$data['title'] = 'Penerimaan Dealer';
		$data['set']   = "penerimaan_dealer";
		$row = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,ms_dealer.nama_dealer FROM tr_penerimaan_unit_dealer 
				JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer=ms_dealer.id_dealer
				WHERE no_surat_jalan='$no_sj' ");
		if ($row->num_rows() > 0) {
			$data['row'] = $row;
			$row = $row->row();
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
					 WHERE no_surat_jalan=tr_surat_jalan_detail.no_surat_jalan
					 AND tr_penerimaan_unit_dealer_detail.retur = 0)
				AND no_surat_jalan='$row->no_surat_jalan'
			");
			$this->template($data);
		}
	}
}
