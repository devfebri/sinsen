<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class Ttm extends CI_Controller {
	var $folder =   "h1";
	var $page		="ttm";
    var $pk     =   "ttm";
    var $title  =   "TTM";
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
		//===== Load Library =====
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
		$data['title']	= 'Dashboard Territory ';																
		$data['set']	= "view";

		$data['contribution'] = $this->db->query("SELECT 
		spk.id_dealer, spk.id_kelurahan , kel.kelurahan ,
		count(1) jum
		from tr_sales_order so left join tr_spk spk on spk.no_spk =so.no_spk 
		left join ms_kelurahan kel on kel.id_kelurahan = spk.id_kelurahan 
		WHERE left(so.tgl_cetak_invoice,4) = '2021' and so.id_dealer ='103'
		group by spk.id_kelurahan ")->row();

		$data['dealer']     = $this->db->query("SELECT id_dealer , nama_dealer,kode_dealer_md from ms_dealer WHERE h1='1' and active ='1' order by nama_dealer asc ")->result();
		$data['dealer_set'] = $this->db->query("SELECT id_dealer , nama_dealer,kode_dealer_md from ms_dealer WHERE h1='1' and active ='1' order by nama_dealer asc ")->result();
		$data['kabupaten']  = $this->db->query("SELECT kec.kecamatan, kab.kabupaten , kec.id_kecamatan
		FROM ms_kabupaten kab 
		LEFT JOIN ms_kecamatan kec ON kec.id_kabupaten = kab.id_kabupaten 
		WHERE kab.id_provinsi = '1500' 
		ORDER BY kab.kabupaten ASC, kec.kecamatan ASC ")->result();

		foreach($data['kabupaten'] as $key =>  $row){
			$dealer_s = $this->db->query("SELECT md.id_dealer , md.nama_dealer, md.kode_dealer_md,
			(select case when id_ring is null then 0 else id_ring end tot from ms_ttm_dealer_ring_detail ttms WHERE id_kecamatan ='$row->id_kecamatan' and id_dealer =md.id_dealer) as tot
			from ms_dealer md
			WHERE md.h1='1' and md.active ='1' 
			group by  md.id_dealer")->result();

				$set[$key]= array(
					'id_kecamatan' => $row->id_kecamatan,
					'kecamatan'    => $row->kecamatan,
					'kabupaten'    => $row->kabupaten,
					'data' 		   => $dealer_s,
				);
		}

		// var_dump($set);
		// die();

		$data['ring'] = $set;

		// $temp = array();
		// $where_gc  = "WHERE 1=1 ";
		// $where_regular  = "WHERE 1=1 ";
		// $where_gc .=" AND spk.id_dealer ='$row->id_dealer'";
		// $where_regular .=" AND spk.id_dealer ='$row->id_dealer'";
		// $where_gc .=" AND ring.id_ring ='$row->id_ring'";
		// $where_regular .=" AND ring.id_ring ='$row->id_ring'";
		// $where_gc .=" AND left(so.tgl_cetak_invoice,12) between '$priode_awal' and '$priode_akhir' ";
		// $where_regular .=" AND left(so.tgl_cetak_invoice,12) between '$priode_awal' and '$priode_akhir' ";
		// $set='detail';
		// $set_value_gc      = $this->get_sales_order_gc($where_gc, $row,$set);
		// $set_value_regular = $this->get_sales_order_regular($where_regular, $row,$set);
		

		// $array = array();
		// foreach($data['kabupaten'] as $key =>  $row){
		// 		foreach($data['dealer_set'] as $innerKey => $keys){

		// 			$jumlah_regular = $this->db->query("SELECT COUNT(1) as jumlah_regular  from ms_ttm_dealer_ring_detail ring join tr_spk spk on spk.id_kecamatan =  ring.id_kecamatan 
		// 			and ring.id_dealer = spk.id_dealer
		// 			left join tr_sales_order so on so.no_spk = spk.no_spk
		// 			WHERE spk.id_dealer ='$keys->id_dealer' and spk.id_kecamatan ='$row->id_kecamatan' ")->row();

		// 			$array[$key]['sales_data'][$innerKey] = [
		// 				'id_dealer' => $keys->id_dealer,
		// 				'jumlah'    => $jumlah_regular->jumlah_regular,
		// 			];

		// 		}
		// }

		// var_dump(	$array);
		// die();

		$this->template($data);		
	}

	public function table_process()
	{

		var_dump($_POST);
		die();

	}
	


	public function add()
	{
		$data['isi']    = $this->page;		
		$data['title']	= 'Add Master Dealer Territory Target Management ';																
		$data['set']	= "add";
		$this->template($data);	
	}

	public function insert()
	{
		$data['isi']    = $this->page;		
		$data['title']	= 'Add Master Dealer Territory Target Management ';																
		$data['set']	= "add";
		$this->template($data);	
	}


	public function target()
	{
		$data['isi']    = $this->page;		
		$data['title']	= 'Add Territory Target Management ';																
		$data['set']	= "target";
		$data['sales_force_territory']	= $this->m_admin->getAll('tr_target_sales_force_md_territory');
		$data['sales_force']	= $this->m_admin->getAll('tr_target_sales_force_md');
		$this->template($data);	
	}



	public function detail_target()
	{
		$id 						    = $this->input->get('id');
		$data['isi']    				= $this->page;		
		$data['title']					= 'Territory Target Management from MD ';																
		$data['set']					= "detail_target";

		$sales_force_territory	= $this->db->query("SELECT ter.*,md.nama_dealer,md.id_dealer,md.kode_dealer_md,kec.kecamatan,ring.id_ring   
		from tr_target_sales_force_md_territory_detail ter join ms_dealer md on md.id_dealer = ter.id_dealer
		join ms_kecamatan kec on kec.id_kecamatan = ter.id_kecamatan 
		join ms_ttm_dealer_ring_detail ring on ring.id_kecamatan =ter.id_kecamatan  
		WHERE ter.id_sales_territory_generate = '$id'
		group by ter.id_sales_territory_int
		order by md.id_dealer,ring.id_ring asc ");


		foreach($sales_force_territory->result() as $row){
			$set_value_gc = 0;

			$month = 4;
			$currentYear = date('Y');
			$previousMonth = ($month == 1) ? 12 : ($month - 1);
			$previousYear = ($month == 1) ? ($currentYear - 1) : $currentYear;
			$numberOfDaysInPreviousMonth = cal_days_in_month(CAL_GREGORIAN, $previousMonth, $previousYear);
			$startDatePreviousMonth = date('Y-m-d', strtotime("$previousYear-$previousMonth-01"));
			$endDatePreviousMonth = date('Y-m-d', strtotime("$previousYear-$previousMonth-$numberOfDaysInPreviousMonth"));
			$priode_awal  = $startDatePreviousMonth;
			$priode_akhir = $endDatePreviousMonth;

			$where_gc  = "WHERE 1=1 ";
			$where_regular  = "WHERE 1=1 ";
			$where_gc .=" AND spk.id_dealer ='$row->id_dealer'";
			$where_regular .=" AND spk.id_dealer ='$row->id_dealer'";
			$where_gc .=" AND ring.id_ring ='$row->id_ring'";
			$where_regular .=" AND ring.id_ring ='$row->id_ring'";
			$where_gc .=" AND left(so.tgl_cetak_invoice,12) between '$priode_awal' and '$priode_akhir' ";
			$where_regular .=" AND left(so.tgl_cetak_invoice,12) between '$priode_awal' and '$priode_akhir' ";
			
			$set='detail';
			$set_value_gc      = $this->get_sales_order_gc($where_gc, $row,$set);
			$set_value_regular = $this->get_sales_order_regular($where_regular, $row,$set);
		
			$jumlah_regular = $set_value_regular->jumlah_regular;
			$jumlah_gc      = $set_value_gc->jumlah_gc ;
			$total_ssu 		= (int)$jumlah_regular + (int)$jumlah_gc;

			$temp []= array(
				'nama_dealer' => $row->nama_dealer ,
				'kode_dealer_md' => $row->kode_dealer_md ,
				'kecamatan' => $row->kecamatan ,
				'id_kecamatan'=> $row->id_kecamatan ,
				'id_ring' => $row->id_ring ,
				'ssu'     => $total_ssu,
				'jumlah' =>  $row->jumlah 
			);
			
		}
		$data['sales_force_territory'] = $temp;


		$this->template($data);	
	}




	public function download_target_set()
	{
		$id 						    = $this->input->get('id');
		$data['actual'] = $this->db->query("SELECT ter.*,md.nama_dealer,md.kode_dealer_md,kec.kecamatan,ring.id_ring   
		from tr_target_sales_force_md_territory_detail ter join ms_dealer md on md.id_dealer = ter.id_dealer
		join ms_kecamatan kec on kec.id_kecamatan = ter.id_kecamatan 
		join ms_ttm_dealer_ring_detail ring on ring.id_kecamatan =ter.id_kecamatan  
		WHERE ter.id_sales_territory_generate = '$id'
		group by ter.id_sales_territory_int
		 order by md.id_dealer,ring.id_ring asc 
		")->result();
		$this->load->view('h1/report/template/temp_ttm_set',$data);
	}


	public function download_target()
	{

	}
	
	public function upload_excel_territory()
	{
	  $this->load->library('upload');
	  $ym = date('Y/m');
	  $path = "./uploads/sales_force_territory/" . $ym;
	  
	  if (!is_dir($path)) {
		mkdir($path, 0777, true);
	  }

	  $config['upload_path']   = $path;
	  $config['allowed_types'] = '*';
	  $config['max_size']      = '10024';
	  $config['remove_spaces'] = TRUE;
	  $config['overwrite']     = TRUE;
	  
	  $this->upload->initialize($config);

	  if ($this->upload->do_upload('file_upload')) {
		$new_path = substr($path, 2, 40);
		$filename = $this->upload->file_name;
		$path_file = './'.$new_path . '/' . $filename;
	  } else {
		$err = clear_removed_html($this->security->xss_clean($this->upload->display_errors()));
		$response = ['icon' => 'error', 'title' => 'Peringatan', 'pesan' => $err];
		send_json($response);
	  }

	  $reader = ReaderFactory::create(Type::XLSX); 
	  $reader->open($path_file); 

	  foreach ($reader->getSheetIterator() as $sheet) {
		$numRow = 0;
		if ($sheet->getIndex() === 0) {
			$baris = 1;

		  foreach ($sheet->getRowIterator() as $row) {
			if ($numRow > 0) {
			  if ($row[0] == '') break;

			  $post[] = [
				'wilayah' => $row[0],
				'dealer' => $row[1],
				'jumlah' => $row[2],
				'activity' => $row[3],
			  ];
			  $baris++;
			}
			$numRow++;
		  }
		}
	  }

	$reader->close();
	$temp_array_territory			 = array();
	$temp_array_territory_validation = array();
    foreach ($post as $key => $territory) {

		if (isset($territory['dealer'])) {
			$kode_dealer_md = $territory['dealer'] ;

			if ($territory['dealer'] !== '') {
				$validate_kode_dealer 			 =  $this->db->query("select id_dealer from ms_dealer WHERE kode_dealer_md = '$kode_dealer_md'")->row();
				if (!is_null($validate_kode_dealer)) {
						$array_territory['id_dealer']     = $validate_kode_dealer->id_dealer ;
				  } else {
					  $temp_array_territory_validation['dealer'] = 'Dealer '.$territory['dealer'].' tidak ditemukan';
				  }
			  }
        }
		
		if (isset($territory['jumlah'])) {
			$array_territory['jumlah']     = $territory['jumlah'];
        }else{
			$temp_array_territory_validation['jumlah'] = 'Jumlah '.$territory['jumlah'].' tidak ditemukan';
		}
			
		if (isset($territory['wilayah'])) {
            $set_wilayah = $territory['wilayah']; 
			$v_wilayah =  $this->db->query("SELECT kec.id_kecamatan  ,kec.kecamatan , COUNT(1) as jumlah  from ms_kabupaten kab left join ms_kecamatan kec on kec.id_kabupaten = kab.id_kabupaten 
			WHERE kab.id_provinsi ='1500' and kec.kecamatan='$set_wilayah' group by kec.id_kecamatan ");
			
			if ($v_wilayah->num_rows() > 0 ){
				$test = $v_wilayah->row();
				$array_territory['id_kecamatan'] = $test->id_kecamatan;
			}else{
				$temp_array_territory_validation['id_kecamatan'] = 'Wilayah '.$set_wilayah.' tidak ditemukan';
			}
        }

		if (isset($territory['activity'])) {
			if ($territory['activity'] !== '') {
				$array_territory['activity'] =  $territory['activity'];
			}
		}

		$date = date('Y/m/d');
		$code = "SFMDT/$date"; 
		$array_territory['id_sales_territory_generate'] = $code;
		$temp_array_territory[]				 			= $array_territory;
	}


	$month = $this->input->post('month');
	if(count($temp_array_territory_validation) == 0){
		$waktu 			    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		    = $this->session->userdata('id_user');
		$header = array(
			'id_sales_territory_generate' => $code,
			'created_at'   				  => $waktu,               
			'bulan'   				  	  => $month,               
			'created_by'   				  => $login_id,              
			'status'					  => 1,                    
		);


		$this->db->insert("tr_target_sales_force_md_territory", $header);
		$this->db->insert_batch("tr_target_sales_force_md_territory_detail", $temp_array_territory);
		$response = [
			'status' => 1,
				'pesan' => "Berhasil di simpan",
		];
	}else{
		$response = [
			'status' => 0,
				'pesan' => "Terjadi kesalahan validasi",
		];
	}
	  send_json($response);
	}

	public function make_report()
	{
		$set = 'repor_all';
		$set = 'ring';
		$jenis_report = $this->input->post('report');

		if($jenis_report == 'konsumen_ro'){
		}



		if($set == 'ring'){
			return $this->report_ring();
		}else if ($set == 'repor_all'){
			return $this->report_all();
		}
	}

	

	public function report_ring()
	{
		$where = "WHERE 1=1 ";
		$where_gc = "WHERE 1=1 ";
		if (isset($_POST['wilayah'])) {
			$set_wilayah = $_POST['wilayah'] ;
			if($_POST['wilayah']!=='all'){
				if ($_POST['wilayah'] !==''){
				$where .=" AND spk.id_kabupaten ='$set_wilayah'";
				$where_gc .=" AND spk.id_kabupaten ='$set_wilayah'";
				}
			}
		}

		if (isset($_POST['dealer'])) {
			$dealer = $_POST['dealer'] ;
			if($_POST['dealer'] !=='all'){
				if ($_POST['dealer'] !==''){
					$where	  .=" AND spk.id_dealer ='$dealer'";
					$where_gc .=" AND spk.id_dealer ='$dealer'";
				}
			}
		}
		
		if (isset($_POST['ring'])) {
			$ring = $_POST['ring'] ;
			if($ring !=='all'){
				if ($_POST['ring'] !==''){
				$where .=" AND ring.id_ring ='$ring'";
				$where_gc .=" AND ring.id_ring ='$ring'";
				}
			}
		}

		if (isset($_POST['start_periode'])) {
			$priode_awal  = $_POST['start_periode'];
			$priode_akhir = $_POST['end_periode'];
			$where .=" AND left(so.tgl_cetak_invoice,12) between '$priode_awal' and '$priode_akhir' ";
			$where_gc .=" AND left(so.tgl_cetak_invoice,12) between '$priode_awal' and '$priode_akhir' ";
		}

		// $data['actual'] = $this->db->query("SELECT md.id_dealer, md.nama_dealer , md.kode_dealer_md ,ring.id_kecamatan,ring.kecamatan , spk.id_kecamatan,ring.id_ring , 
		// COUNT(1) as jumlah from ms_ttm_dealer_ring_detail ring 
		// left join tr_spk spk on spk.id_kecamatan = ring.id_kecamatan and ring.id_dealer = spk.id_dealer 
		// left join tr_sales_order so on so.no_spk = spk.no_spk
		// left join ms_dealer md on md.id_dealer = spk.id_dealer 
		// $where
		// AND so.id_sales_order is not null
		// GROUP by ring.id_kecamatan ,ring.id_ring
		// order by ring.id_dealer,ring.id_ring,ring.id_kecamatan ASC 
		// ")->result();

	
		$set_actual = $this->db->query("SELECT  
		case when ring.id_ring is null then 'All Dealer Out from Ring ' else md.nama_dealer end nama_dealer,
		case when ring.id_ring is null then '' else md.kode_dealer_md end kode_dealer_md,
		case when ring.id_ring is null then '' else md.id_dealer end id_dealer,
		case when ring.id_ring is null then '' else ring.kecamatan end kecamatan,
		case when ring.id_ring is null then '' else ring.id_kecamatan end id_kecamatan,
		case when ring.id_ring is null then 'Out from Ring' else ring.id_ring end id_ring,
		COUNT(1)  as jumlah
		from tr_sales_order so join tr_spk spk on spk.no_spk= so.no_spk 
		left join ms_ttm_dealer_ring_detail ring on ring.id_kecamatan = spk.id_kecamatan and ring.id_dealer = spk.id_dealer 
		join ms_dealer md on md.id_dealer =so.id_dealer 
		$where
				AND so.id_sales_order is not null
				GROUP by ring.id_kecamatan ,ring.id_ring
				order by ring.id_dealer,ring.id_ring,ring.id_kecamatan ASC 
		")->result();


		$set= 'detail';
		foreach($set_actual as $row){
			$set_value_gc = 0;
			$set_value_gc = $this->get_sales_order_gc($where_gc, $row, $set);

			$jumlah_regular = $row->jumlah;
			$jumlah_gc      = $set_value_gc->jumlah_gc ;
			$total_ssu 		= (int)$jumlah_regular + (int)$jumlah_gc;
	
			$temp []= array(
				'nama_dealer' 	 => $row->nama_dealer,
				'kode_dealer_md' => $row->kode_dealer_md,
				'kecamatan'   => $row->kecamatan,
				'id_kecamatan'=> $row->id_kecamatan,
				'ssu' 		  => $total_ssu,
				'id_ring' 	  => $row->id_ring,
				'jumlah' 	  => $row->jumlah 
			);
		}

		$data['actual'] = $temp;
		$dealer = $this->db->query("SELECT  id_dealer from ms_dealer WHERE h1  ='1' and active ='1' ")->result();
		$this->load->view('h1/report/template/temp_ttm',$data);
	}

	public function get_sales_order_gc($where,$row,$set)
	{
		$join ='';
		if($set == 'detail'){
			$join = 'left join ms_ttm_dealer_ring_detail ring on ring.id_kecamatan = spk.id_kecamatan';

		}else{

		$where_set = "";
		$where_set .="AND so.id_dealer = '$row->id_dealer'";
			if($row->id_kecamatan =='' ){
				$where_set .="AND spk.id_kecamatan not in (select id_kecamatan from  ms_ttm_dealer_ring_detail  group by id_kecamatan )";
			}else{
				$where_set .="AND spk.id_kecamatan = '$row->id_kecamatan'";
			}
		}

		$dealer = $this->db->query("SELECT COUNT(1) as jumlah_gc  from tr_sales_order_gc so 
		left join tr_sales_order_gc_nosin sos on sos.id_sales_order_gc = so.id_sales_order_gc 
		join tr_spk_gc spk on spk.no_spk_gc = so.no_spk_gc 
		$join
		$where
		$where_set
		")->row();
		return $dealer;
	}


	public function get_sales_order_regular($where,$row,$set)
	{
		$dealer = $this->db->query("SELECT COUNT(1) as jumlah_regular  from ms_ttm_dealer_ring_detail ring join tr_spk spk on spk.id_kecamatan =  ring.id_kecamatan 
		and ring.id_dealer = spk.id_dealer
		left join tr_sales_order so on so.no_spk = spk.no_spk 
		$where
		")->row();
		return $dealer;
	}

	public function report_all()
	{
		$select = 'ttm.id_ring ,COUNT(1) as jumlah';
		$select = 'ttm.id_ring ,COUNT(1) as jumlah';

		$awal  = "2024-03-01";
		$akhir = "2024-03-31";

		$where = 'WHERE 1=1 ';
		$where .= " and left(so.tgl_cetak_invoice,12) BETWEEN '$awal' and '$akhir'";

		$group ='group by ttm.id_ring';
		$get = $this->db->query("SELECT $select from tr_sales_order so join tr_spk spk on spk.no_spk = so.no_spk 
		left join ms_ttm_dealer_ring_detail ttm on ttm.id_kecamatan  = spk.id_kecamatan and  so.id_dealer = ttm.id_dealers 
		$where
		$group
		")->result();

		$dealer = $this->db->query("SELECT  * from ms_dealer WHERE h1  ='1' active ='1' ")->result();
		
		$temp = array();
		foreach($dealer as $row){

		}
		var_dump($get);
		die();

	}


	public function master()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= 'Master TTM Dealer';																
		$data['set']	= "master";
		$data['dealer'] = $this->db->query("SELECT  
			md.id_dealer, 
			md.kode_dealer_md, 
			md.nama_dealer, 
			kec.kecamatan,
			CASE 
				WHEN EXISTS (SELECT id_dealer FROM ms_ttm_dealer_ring_detail WHERE id_dealer = md.id_dealer AND id_ring='0') THEN 'set' 
				ELSE 'belum set' 
			END AS status
		FROM 
			ms_dealer md 
			JOIN ms_kelurahan kel ON kel.id_kelurahan = md.id_kelurahan 
			JOIN ms_kecamatan kec ON kec.id_kecamatan = kel.id_kecamatan 
		WHERE 
			md.h1='1' AND md.active ='1' 
		ORDER BY 
			md.nama_dealer, status ASC ;
		")->result();

		$this->template($data);		
	}

	public function generate()
	{
		$id 		  = $this->input->post('id_dealer');
		$priode_awal  = $this->input->post('priode_awal');
		$priode_akhir = $this->input->post('priode_akhir');

		$ring = $this->db->query("SELECT ttmd.id_ring, spk.id_kecamatan, kec.kecamatan, COUNT(1) as jumlah 
		FROM tr_sales_order so 
		JOIN tr_spk spk ON spk.no_spk = so.no_spk 
		JOIN ms_kecamatan kec ON kec.id_kecamatan = spk.id_kecamatan 
		LEFT JOIN ms_ttm_dealer_ring_detail ttmd ON ttmd.id_kecamatan = spk.id_kecamatan 
		WHERE spk.id_dealer = '$id' 
		AND LEFT(so.tgl_cetak_invoice, 12) BETWEEN '$priode_awal'  AND '$priode_akhir' 
		AND ttmd.id_ring IS NOT NULL 
		GROUP BY ttmd.id_ring");
	

		$totalring = $this->db->query("SELECT COUNT(1) as jumlah 
		FROM tr_sales_order so 
		JOIN tr_spk spk ON spk.no_spk = so.no_spk 
		JOIN ms_kecamatan kec ON kec.id_kecamatan = spk.id_kecamatan 
		LEFT JOIN ms_ttm_dealer_ring_detail ttmd ON ttmd.id_kecamatan = spk.id_kecamatan 
		WHERE spk.id_dealer = '$id ' 
		AND LEFT(so.tgl_cetak_invoice, 12) BETWEEN '$priode_awal' AND '$priode_akhir'  
		AND ttmd.id_ring IS NOT NULL 
		");

		$temp = array();
		foreach($ring->result() as $row) {
			$jumlah  =  $row->jumlah / $totalring->row()->jumlah * 100;

			$status = 'good';
			if ($jumlah > 20){
				$status = 'hight';
			}

			$temp[] = array(
				'id_ring'        =>  $row->id_ring,
				'id_kecamatan'   =>  $row->id_kecamatan,
				'kecamatan'      =>  $row->kecamatan,
				'jumlah'         => $jumlah,
				'status_jumlah'  => $status,
			);
		}

		$json_data = json_encode($temp);
		header('Content-Type: application/json');
		echo $json_data;


	}

	public function show()
	{				
		$id = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= $this->title.' - Wilayah Kerja';																
		$data['set']	= "show";
		$data['ring'] = $this->db->query("SELECT ttmd.*,kab.kabupaten from ms_ttm_dealer_ring_detail ttmd 
		left join ms_kecamatan kec on kec.id_kecamatan = ttmd.id_kecamatan  
		left join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten 
		where ttmd.id_dealer = '$id' order by id_ring asc ")->result();
		$this->template($data);		
	}

	public function edit()
	{				
		$id = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= $this->title.' - Wilayah Kerja';																
		$data['set']	= "show";
		$data['ring'] = $this->db->query("SELECT ttmd.*,kab.kabupaten from ms_ttm_dealer_ring_detail ttmd 
		left join ms_kecamatan kec on kec.id_kecamatan = ttmd.id_kecamatan  
		left join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten 
		where ttmd.id_dealer = '$id' order by id_ring asc ")->result();
		$this->template($data);		
	}


	public function report()
	{
		$id = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= 'Report TTM';																
		$data['set']	= "report";
		$data['dt_wilayah']= $this->db->query("SELECT  * from ms_kabupaten WHERE id_provinsi ='1500' order by kabupaten asc ");
		$data['dealer']= $this->db->query("SELECT  id_dealer, nama_dealer from ms_dealer WHERE active ='1' and h1='1' order by nama_dealer asc ");
		$data['ring'] = $this->db->query("SELECT ttmd.*,kab.kabupaten from ms_ttm_dealer_ring_detail ttmd 
		left join ms_kecamatan kec on kec.id_kecamatan = ttmd.id_kecamatan  
		left join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten 
		where ttmd.id_dealer = '$id' order by id_ring asc ")->result();
		$this->template($data);		
	}

	
	public function inject_make_master_report()
	{
		die();
		$data['isi']    = $this->page;		
		$data['title']	= $this->title.' - Wilayah Kerja';																
		$data['set']	= "excel";

		$dealer =  $this->db->query("SELECT id_dealer,nama_dealer from ms_dealer where active ='1' and h1='1' order by id_dealer asc ")->result();
		$array= array();
		foreach ($dealer as $data){
			$set = $data->id_dealer ;
			$wilayah = $this->db->query("SELECT
			CONCAT('$set') AS dealer,
			kec.id_kecamatan,
			CONCAT('') AS ring,
			kec.kecamatan 
			FROM ms_provinsi pro 
			LEFT JOIN ms_kabupaten kab ON kab.id_provinsi = pro.id_provinsi 
			LEFT JOIN ms_kecamatan kec ON kec.id_kabupaten = kab.id_kabupaten 
			LEFT JOIN ms_kelurahan kel ON kel.id_kecamatan = kec.id_kecamatan 
			WHERE pro.id_provinsi = '1500' 
			AND kec.id_kabupaten = (
				SELECT kec.id_kabupaten 
				FROM ms_provinsi pro 
				LEFT JOIN ms_kabupaten kab ON kab.id_provinsi = pro.id_provinsi 
				LEFT JOIN ms_kecamatan kec ON kec.id_kabupaten = kab.id_kabupaten 
				LEFT JOIN ms_kelurahan kel ON kel.id_kecamatan = kec.id_kecamatan 
				LEFT JOIN ms_dealer md ON md.id_kelurahan = kel.id_kelurahan 
				WHERE pro.id_provinsi = '1500' 
				AND md.id_dealer = '$set'
				AND md.h1 = '1' 
				AND active = '1'
				GROUP BY kec.id_kecamatan
			)
			GROUP BY kec.id_kecamatan 
			ORDER BY kec.kecamatan ASC;")->result();
			foreach ($wilayah as $row){
		
				$set = array(
					'id_ttm_detail_int' => NULL,
					'id_dealer' =>  $row->dealer,
					'id_kecamatan' => $row->id_kecamatan,
					'kecamatan' =>  $row->kecamatan,
				);

				// $this->db->insert('ms_ttm_dealer_ring_detail', $set);
			}

			// $array[$set]= $wilayah ;
		}


	}



	public function fetch_bastd()
	{
		$fetch_data = $this->make_query();
		$data       = array();
		$id_menu    = $this->m_admin->getMenu($this->page);
		$group      = $this->session->userdata("group");
		$edit       = $this->m_admin->set_tombol($id_menu, $group, 'edit');
		$no = 1;
		$button = '';

		foreach ($fetch_data->result() as $rs) {
			$button  ='';
			$button .= '<a href="h1/rekap_bastd/edit?id='.$rs->id_rekap_bbn_generate.'" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>';
			$button .= '<a href="h1/rekap_bastd/cetak?id='.$rs->id_rekap_bbn_generate.'" class="btn btn-sm btn-info"><i class="fa fa-file-text"></i> Cetak Surat</a>';
			$button .= '<a href="h1/rekap_bastd/cetak_kwitansi?id='.$rs->id_rekap_bbn_generate.'" class="btn btn-sm btn-success"><i class="fa fa-file-text"></i> Cetak Kwitansi</a>';

			if(!empty($rs->nama_dealer)){
				$dealer = $rs->nama_dealer;
			}else{
				$dealer = $rs->group_dealer;
			}

			$tgl_rekap = date("Y-m-d", strtotime($rs->created_at));
			$sub_array   = array();
			$sub_array[] = $no++;
			$sub_array[] = $rs->id_rekap_bbn_generate;
			$sub_array[] = $rs->no_surat;
			$sub_array[] = $tgl_rekap;
			$sub_array[] = $rs->jenis_rekap;
			$sub_array[] = $dealer;
			$sub_array[] = $rs->tgl_awal.' - '. $rs->tgl_akhir;
			$sub_array[] = $rs->tgl_jatuh_tempo;
			$sub_array[] = $rs->jumlah_unit;
			$sub_array[] = 'Rp. ' .number_format($rs->biaya_bbn);
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data(),
			"data"            => $data
		);
		echo json_encode($output);
	}

	public function make_query($no_limit = null)
	{
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";

		$group ="group by id_rekap_bbn_generate";

		if ($no_limit == 'y') $limit = '';

		$search = $this->input->post('search')['value'];
		$where = "WHERE 1=1 ";
		$where = "AND bbng.id_rekap_bbn_generate is not null";

		if ($search != '') {
			$where .= " AND (bbng.tgl_rekap LIKE '%$search%'
					OR bbng.tgl_awal LIKE '%$search%'
					OR bbng.tgl_akhir LIKE '%$search%'
					OR bbng.tgl_jatuh_tempo LIKE '%$search%'
				) 
			";
		}

		$order_column = array('bbng.tgl_rekap', 'bbng.tgl_awal', 'bbng.tgl_akhir', 'bbng.tgl_jatuh_tempo', null);
		$set_order = "ORDER BY bbng.tgl_rekap DESC";

		if (isset($_POST['order'])) {
			$order = $_POST['order'];
			$order_clm  = $order_column[$order['0']['column']];
			$order_by   = $order['0']['dir'];
			$set_order = " ORDER BY $order_clm $order_by ";
		}

		return  $this->db->query("SELECT
		bbng.id_rekap_bbn_generate,
		sum(case when bbnd.total_unit  is not null then total_unit else 0 end) as jumlah_unit, 
		sum(bbnd.jumlah) as biaya_bbn,
		bbng.created_at,
		bbng.no_surat,
		md.nama_dealer,
		bbng.jenis_rekap,
		bbng.group_dealer,
		bbng.tgl_awal, bbng.tgl_akhir,
		bbng.tgl_jatuh_tempo,
		fk.tgl_bastd
		from tr_rekap_bbn_generate bbng left join tr_rekap_bbn_generate_detail bbnd on bbng.id_rekap_bbn_generate =bbnd.id_rekap_bbn_generate 
		left join tr_faktur_stnk fk on fk.no_bastd = bbng.id_rekap_bbn_generate 
		left join ms_dealer md on md.id_dealer = bbng.id_dealer
		$where 
		$group 
		$set_order 
		$limit
		");
	}

	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}

	function delete_target(){

		$tabel			= 'tr_target_sales_force_md_territory_detail';
		$tabel2			= 'tr_target_sales_force_md_territory';
		$pk 			= 'id_sales_territory_generate';
		$id 			=  $this->input->get('id');		

			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();	

			$this->db->trans_begin();			
			$this->db->delete($tabel2,array($pk=>$id));
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/ttm/target'>";


	}

	function cetak(){
		$id	    = $this->input->get("id");
		$this->db->select('bbn.*,kab.*');
		$this->db->from('tr_rekap_bbn_generate bbn');
		$this->db->join('ms_dealer md', 'md.id_dealer = bbn.id_dealer', 'left');
		$this->db->join('ms_kelurahan kel', 'md.id_kelurahan = kel.id_kelurahan', 'left');
		$this->db->join('ms_kecamatan kec', 'kel.id_kecamatan = kec.id_kecamatan', 'left');
		$this->db->join('ms_kabupaten kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left');
		$this->db->where('bbn.id_rekap_bbn_generate', $id);
		$header = $this->db->get()->row();
			
		$pdf = new PDF_HTML('p', 'mm', 'A4');
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetAutoPageBreak(false);
		$pdf->AddPage();

		// $now = $this->tanggal_indo(date('Y-m-d', strtotime($header->created_at)));
		$now = $this->tanggal_indo(date('Y-m-d')) ;
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(190, 7, 'Jambi, ' .$now, 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(190, 7, 'No. Surat : ' .$header->no_surat, 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(190, 5, 'Kepada Yth : ', 0, 1, 'L');

		if($header->jenis_rekap =='dealer'){
		$pdf->Cell(190, 5, $header->nama_dealer, 0, 1, 'L');
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(190, 5, 'Alamat : ' .$header->alamat, 0, 1, 'L');
		$pdf->Cell(190, 5, ucfirst(strtolower($header->kabupaten)), 0, 1, 'L');
		$pdf->Cell(190, 5, $header->pic, 0, 1, 'L');
		}else{

		$pdf->Cell(190, 5, $header->qq_kwitansi, 0, 1, 'L');
		// $this->db->select('md.id_dealer, md.nama_dealer,md.alamat');
		// $this->db->from('tr_rekap_bbn_generate_detail bbnd');
		// $this->db->join('ms_dealer md', 'bbnd.id_dealer = md.id_dealer', 'left');
		// $this->db->group_by('bbnd.id_dealer');
		// $this->db->order_by('md.nama_dealer', 'ASC');
		// $dealer = $this->db->get();

			// foreach($dealer->result() as $val) {
		// 	$pdf->SetFont('ARIAL', '', 7);
		// 	$pdf->Cell(190, 4, $val->nama_dealer, 0, 1, 'L');
		// }
		// }
		}


		$pdf->Cell(190, 10, 'Perihal : ' . 'Tagihan BBN/BPKB periode tanggal '.date('d', strtotime($header->tgl_awal)).' - ' .$this->tanggal_indo($header->tgl_akhir). '', 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(190, 5, 'Dengan Hormat,', 0, 1, 'L');
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(190, 5, 'Melalui surat ini kami kirimkan tagihan BBN dan BPKB untuk periode tanggal '.date('d', strtotime($header->tgl_awal)).' - '.$this->tanggal_indo($header->tgl_akhir). '', 0, 1, 'L');
		$pdf->Cell(190, 5, 'Dengan perincian tagihan sebagai berikut', 0, 1, 'L');
		$pdf->Cell(190, 5, '', 0, 1, 'L');

		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(10, 5, 'No', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(50, 5, 'No Surat', 1, 0, 'C');
		$pdf->Cell(20, 5, 'Total | Unit', 1, 0, 'C');
		$pdf->Cell(50, 5, 'Total BBN', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Delaer', 1, 1, 'C');
		$this->db->select('SUM(rbg.total_unit) as jumlah');
		$this->db->select('SUM(rbg.jumlah) as biaya_unit');
		$this->db->select('(SELECT ms_dealer.nama_dealer FROM tr_faktur_stnk LEFT JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer WHERE tr_faktur_stnk.no_bastd = rbg.no_bastd) as nama_dealer', FALSE);
		$this->db->select('md.id_dealer');
		$this->db->select('md.kode_dealer_md');
		$this->db->select('rbg.tgl_bastd');
		$this->db->select('rbg.no_bastd');
		$this->db->select('rbg.id_rekap_bbn_generate');
		$this->db->from('tr_rekap_bbn_generate_detail rbg');
		$this->db->join('tr_rekap_bbn_generate rb', 'rbg.id_rekap_bbn_generate = rb.id_rekap_bbn_generate', 'left');
		$this->db->join('ms_dealer md', 'md.id_dealer = rb.id_dealer', 'left');
		$this->db->where('rbg.id_rekap_bbn_generate', $id);
		$this->db->group_by('rbg.no_bastd');
		$this->db->order_by('md.id_dealer','rbg.no_bastd', 'asc');
		
		// $this->db->limit(16);
		$bbn = $this->db->get();


		$no = 1;
		$total_biaya_bbn = 0;
		$total_unit = 0;
		foreach($bbn->result() as $val) {
			
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(10, 5, $no++, 1, 0, 'C');
		$pdf->Cell(30, 5, date('d-m-Y', strtotime($val->tgl_bastd)), 1, 0, 'C');
		$pdf->Cell(50, 5, $val->no_bastd, 1, 0, 'C');
		$pdf->Cell(20, 5, $val->jumlah, 1, 0, 'C');
		$pdf->Cell(50, 5,  'Rp. '.number_format($val->biaya_unit, 0, ',', '.'), 1, 0, 'C');
		$pdf->SetFont('ARIAL', 'B', 4);
		$pdf->Cell(30, 5, $val->nama_dealer, 1, 1, 'C');

		$pdf->SetFont('ARIAL', 'B', 9);


		$total_biaya_bbn += $val->biaya_unit;
		$total_unit += $val->jumlah;

		if( $no ==41){
			$pdf->AddPage();
			$pdf->Cell(190, 10, '', 0, 1, 'L');
		}

		if( $no ==91){
			$pdf->AddPage();
			$pdf->Cell(190, 10, '', 0, 1, 'L');
		}

		if( $no ==141){
			$pdf->AddPage();
			$pdf->Cell(190, 10, '', 0, 1, 'L');
		}
		if( $no ==191){
			$pdf->AddPage();
			$pdf->Cell(190, 10, '', 0, 1, 'L');
		}

		if( $no ==241){
			$pdf->AddPage();
			$pdf->Cell(190, 10, '', 0, 1, 'L');
		}

		}

		$this->db->select('*');
		$this->db->from('tr_rekap_bbn_generate_detail_tambahan bbnt');
		$this->db->where('id_rekap_bbn_generate', $id);
		$manual = $this->db->get();

		$jumlah_manual =0;
		foreach($manual->result() as $val) {
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(10, 5, $no++, 1, 0, 'C');
		$pdf->Cell(30, 5, '', 1, 0, 'C');
		$pdf->Cell(50, 5, $val->nama_biaya, 1, 0, 'C');
		$pdf->Cell(20, 5, '', 1, 0, 'C');
		$pdf->Cell(50, 5,  'Rp. '.number_format($val->jumlah, 0, ',', '.'), 1, 1, 'C');
		$jumlah_manual +=$val->jumlah; 
		}

		$total_biaya = intval($total_biaya_bbn) + intval($jumlah_manual);
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(90, 5, 'Total', 1, 0, 'C');
		$pdf->Cell(20, 5, $total_unit, 1, 0, 'C');
		$pdf->Cell(50, 5,  'Rp. '. number_format($total_biaya, 0, ',', '.'), 1, 1, 'C');
		$pdf->SetFont('ARIAL', '', 9);
		$tot_terbilang = ucwords(number_to_words($total_biaya));
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->Cell(190, 12, 'Terbilang : '.$tot_terbilang ." Rupiah" , 0, 1, 'L');
		$pdf->SetFont('ARIAL', '', 9);

		if( $bbn->num_rows() >16){
			$pdf->AddPage();
			$pdf->Cell(190, 10, '', 0, 1, 'L');
		}

		$pdf->Cell(190, 5, 'Besar harapan kami agar tagihan tersebut dapat diselesaikan sesuai dengan perincian yang terlampir. Ke rekening kami sbb:', 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(190, 1, '', 0, 1, 'L');
		$pdf->Cell(20, 5, 'Atas Nama', 0, 0, 'L');
		$pdf->Cell(190, 5, ': PT. SINAR SENTOSA PRIMATAMA', 0, 1, 'L');

		$pdf->Cell(20, 5, 'Bank', 0, 0, 'L');
		$pdf->Cell(190, 5, ': BCA Cab. Jambi', 0, 1, 'L');

		$pdf->Cell(20, 5, 'A/C', 0, 0, 'L');
		$pdf->Cell(190, 5, ': 7870900800', 0, 1, 'L');
		$pdf->SetFont('ARIAL', '', 9);

		$pdf->Cell(190, 1, '', 0, 1, 'L');
		$pdf->Cell(190, 5, 'Apabila tagihan tersebut telah Bapak/Ibu transfer, Mohon dapat menginformasikan ke kami ', 0, 1, 'L');
		$pdf->Cell(190, 5, 'di No. Telp 0741-61551 Ext. 611 dengan bagian Finance.', 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(190, 10, 'NB : Jatuh tempo pada tanggal '. $this->tanggal_indo($header->tgl_jatuh_tempo), 0, 1, 'L');
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(190, 5, 'Demikianlah surat ini kami sampaikan. Atas bantuan dan kerjasamanya kami ucapkan Terima Kasih.', 0, 1, 'L');

		$pdf->Cell(30, 10, '', 0, 1, 'L');
		$pdf->Cell(30, 5, 'Hormat Kami', 0, 1, 'L');
		$pdf->Cell(30, 18, '', 0, 1, 'L');
		$pdf->Cell(30, 5, 'Herman', 0, 1, 'L');
		$pdf->Cell(30, 5, 'Head Finance,', 0, 1, 'L');
		$pdf->Cell(190, 3, '', 0, 1, 'C');
		$pdf->Output();
	}

	function cetak_kwitansi(){

		$id				= $this->input->get("id");
		$this->db->select('*');
		$this->db->from('tr_rekap_bbn_generate bbn');
		$this->db->join('ms_dealer md', 'md.id_dealer = bbn.id_dealer', 'left');
		$this->db->where('bbn.id_rekap_bbn_generate', $id);
		$query = $this->db->get();
		$header = $query->row();

		$this->db->select('SUM(jumlah) as jumlah_manual');
		$this->db->from('tr_rekap_bbn_generate_detail_tambahan');
		$this->db->where('id_rekap_bbn_generate', $id);
		$query = $this->db->get();
		$manual = $query->row()->jumlah_manual;

		$this->db->select('COUNT(1) as jumlah, SUM(fsd.biaya_bbn) as biaya_unit, fk.id_dealer, fk.tgl_bastd');
		$this->db->select('(SELECT CONCAT(tgl_awal, " - ", tgl_akhir) FROM tr_rekap_bbn_generate WHERE id_rekap_bbn_generate = rbg.id_rekap_bbn_generate) as priode', FALSE); // FALSE to prevent escaping
		$this->db->from('tr_rekap_bbn_generate_detail rbg');
		$this->db->join('tr_faktur_stnk_detail fsd', 'rbg.no_bastd = fsd.no_bastd', 'left');
		$this->db->join('tr_faktur_stnk fk', 'fk.no_bastd = fsd.no_bastd', 'left');
		$this->db->join('ms_dealer md', 'md.id_dealer = fk.id_dealer', 'left');
		$this->db->where('rbg.id_rekap_bbn_generate', $id);
		$this->db->order_by('fsd.no_bastd, md.nama_dealer');
		
		$query = $this->db->get();
		$bbn = $query->row();

		$pdf = new PDF_HTML('p', 'mm', array(210, 297)); // Custom height for A4 size
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetAutoPageBreak(false);
		$pdf->AddPage();

		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(190, 8, 'No. '.$header->no_surat, 0, 1, 'L');
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(190, 1, '', 0, 1, 'L');
		$pdf->Cell(40, 5, 'Telah Terima dari', 0, 0, 'L');
		$nama_dealer = $header->nama_dealer;
	
		$tot = intval($bbn->biaya_unit) + intval($manual);
		$tot_terbilang = ucwords(number_to_words($tot));
		list($startDateString, $endDateString) = explode(" - ", $bbn->priode);
		$startDate = new DateTime($startDateString);
		$endDate = new DateTime($endDateString);
		$formattedStartDate = $startDate->format('d');
		$formattedEndDate = $endDate->format('d F Y');
		$formattedDateRange = $formattedStartDate . ' - ' . $formattedEndDate;

		$pembayaran = 'Tagihan BBN Periode tanggal ' .$formattedDateRange;
		$pdf->Cell(190, 5, ':  '.$nama_dealer, 0, 1, 'L');
		$pdf->Cell(40, 5, 'Uang Sejumlah', 0, 0, 'L');
		$pdf->Cell(190, 5, ':  '.$tot_terbilang ." Rupiah", 0, 1, 'L');
		$pdf->Cell(40, 5, 'Untuk Pembayaran', 0, 0, 'L');
		$pdf->Cell(190, 5, ':  '.$pembayaran, 0, 1, 'L');
		$pdf->Cell(190, 10, 'Jambi, '.$this->tanggal_indo(date('Y-m-d')), 0, 1, 'R');
		$pdf->Cell(190, 15, '', 0, 1, 'R');

		$pdf->SetFont('ARIAL', 'B', 12);
		$pdf->Cell(20, 5, 'Rp. ' . number_format($tot, 0, ',', '.'), "", 0, 'L');

		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(170, 5, 'Herman', 0, 1, 'R');
		$pdf->Cell(190, 5, 'Head Finance,', 0, 1, 'R');
		$pdf->Cell(190, 3, '', 0, 1, 'C');
		$pdf->Output();
	}


	function save(){
		$postData 			= $this->input->post('data');
        $generate 			= json_decode($postData, true);
		$postDataManual 	= $this->input->post('data_manual');
        $generate_manual	= json_decode($postDataManual, true);
		$waktu 			    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		    = $this->session->userdata('id_user');
		$query 				= $this->db->select('COUNT(1) as total')->get('tr_rekap_bbn_generate');
		$row   			    = $query->row();
		$year  				= date('Y');
		$month 				= date('n');
		$totalCount 				   = sprintf("%03d", $row->total + 1);
		$kode 						   = 'FINC-SSP';
		$romawi 					   = $this->convertToRoman($month);
		$string_generate               = sprintf("%s/%s/%s/%d", $totalCount, $kode, $romawi, $year);
		$index = 0;
		$indexmanual = 0;
		$temp       =array();
		$tempManual =array();

		foreach ($generate as $row) {
			if(isset($generate[$index])){
				$datas = $generate[$index];
				if(count($datas) !== 0 ){
					$insert_batch = array(
						'no_bastd' 				=>  $datas['no_bastd'],
						'id_rekap_bbn_generate' =>  $string_generate,
						'status'	   =>  'input',
						'jumlah'       =>  $datas['biaya_bbn_md'],
						'id_dealer'    =>  $datas['id_dealer'],
						'total_unit'   =>  $datas['total_unit'],
						'tgl_bastd'    =>  $datas['tgl_bastd'],
					);
					$temp[] =$insert_batch; 
				}
			}
			$index ++;
		}

		foreach ($generate_manual as $row) {
			if(isset($generate_manual[$indexmanual])){
				$datamanual = $generate_manual[$indexmanual];
				if($generate_manual[$indexmanual]['biaya_lainya'] !== ''){
					$insert_batch_manual = array(
						'id_rekap_bbn_generate_detail_tambahan' => '',
						'id_rekap_bbn_generate' 				=>  $string_generate,
						'nama_biaya'            				=>  $datamanual['biaya_lainya'],
						'jumlah'                				=>  $datamanual['harga_lainya'],
					);
					$tempManual[] = $insert_batch_manual; 
				}
			}
			$indexmanual ++;
		}

		$is_group_dealer =  $this->input->post("group_dealer");
		$is_id_dealer    =  $this->input->post("id_dealer");

		$jenis_rekap = 'dealer';
		if(!empty($is_group_dealer)){
			$jenis_rekap = 'group'; 
		}

		$date = date('Y-m-d');
		$insert_header = array(
			'id_rekap_bbn_generate'	=>  $string_generate,
			'tgl_rekap' 			=>   $date,
			'group_dealer' 			=>   $is_group_dealer ,
			'qq_kwitansi' 			=>   $this->input->post("kwitansi"),
			'id_dealer' 			=>   $is_id_dealer,
			'tgl_awal' 				=>   $this->input->post("start_periode"),
			'qq_kwitansi' 			=>   $this->input->post("kwitansi"),
			'tgl_akhir' 			=>   $this->input->post("end_periode"),
			'tgl_jatuh_tempo' 		=>   $this->input->post("tgl_jatuh_tempo"),
			'no_surat' 				=>   $this->input->post("no_surat"),
			'created_at' 			=>   $waktu,
			'created_by' 			=>   $login_id,
			'status_pelunasan' 		=>   0,
			'jenis_rekap' 		    =>   $jenis_rekap,
		);

		$this->db->insert('tr_rekap_bbn_generate', $insert_header);
		$this->db->insert_batch('tr_rekap_bbn_generate_detail', $temp);	
		$this->db->insert_batch('tr_rekap_bbn_generate_detail_tambahan', $tempManual);
	}

	function convertToRoman($num) {
		$roman = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
		return $roman[$num];
	}

	function kwitansi_qq() {
		$id_dealer 			= $this->input->post('id_dealer');
		$group_dealer 		= $this->input->post('group_dealer');
		$where 				= '';

		if (!empty($id_dealer)) {
			$where .= "mgdd.id_dealer = '$id_dealer' ";
		}
		
		if (!empty($group_dealer)) {
			$where .= "mgd.id_group_dealer = '$group_dealer' ";
		}

		if (!empty($where)) {
			$where = rtrim($where, ' AND ');
		}
	
		$this->db->select('mgd.qq_kwitansi');
		$this->db->from('ms_group_dealer mgd');
		$this->db->join('ms_group_dealer_detail mgdd', 'mgd.id_group_dealer = mgdd.id_group_dealer', 'left');
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();

		$header = $query->row()->qq_kwitansi;
		echo $header;
	}

}