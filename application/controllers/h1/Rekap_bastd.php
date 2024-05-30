<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_bastd extends CI_Controller {
	var $folder =   "h1";
	var $page		="rekap_bastd";
    var $pk     =   "id_penerimaan_unit";
    var $title  =   "Rekap BASTD";
	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
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

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;																
		$data['set']	= "view";
		$data['mode']   = 'add';	
		$this->template($data);		
	}

	public function edit()
	{				

		$id				= $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['dt_dealer'] = $this->db->query("SELECT nama_dealer, id_dealer,kode_dealer_md from ms_dealer where h1 =1  and active = '1' order by nama_dealer,kode_dealer_md ASC");		
		$data['dt_group'] = $this->db->query("select * from ms_group_dealer order by group_dealer ASC ");	
		$data['mode']   = 'edit';	
		$data['row'] = $this->db->query("SELECT * from tr_rekap_bbn_generate where id_rekap_bbn_generate ='$id' ")->row();	
		$data['set']	= "add";
		$this->template($data);		
	}
	
	public function add()
	{				
		$data['isi']       = $this->page;		
		$data['title']	   = $this->title;		
		$data['dt_dealer'] = $this->db->query("SELECT nama_dealer, id_dealer,kode_dealer_md from ms_dealer where h1 =1  and active = '1' order by nama_dealer,kode_dealer_md ASC");		
		$data['dt_group'] = $this->db->query("select * from ms_group_dealer order by group_dealer ASC ");	
		$data['set']	= "add";
		$this->template($data);		
	}

	function tanggal_indo($tanggal)
		{
			$bulan = array (1 =>   'Januari',
						'Februari',
						'Maret',
						'April',
						'Mei',
						'Juni',
						'Juli',
						'Agustus',
						'September',
						'Oktober',
						'November',
						'Desember'
					);
			$split = explode('-', $tanggal);
			return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
		}

		
	public function generate()
	{			
		$where = "WHERE 1=1 ";
		$limit = "";
		$id_dealer				= $this->input->post("id_dealer");
		$group_dealer			= $this->input->post("group_dealer");
		$start_periode			= $this->input->post("start_periode");
		$end_periode			= $this->input->post("end_periode");

		if(!empty($group_dealer)){
			$this->db->select('ms_dealer.id_dealer');
			$this->db->from('ms_group_dealer_detail');
			$this->db->join('ms_dealer', 'ms_group_dealer_detail.id_dealer = ms_dealer.id_dealer');
			$this->db->where('id_group_dealer', $group_dealer);

			$data       = $this->db->get();
			$id_dealers = array();
			foreach ($data->result() as $row) {
				$id_dealers[] = $row->id_dealer;
			}
			$ids_string = "'" . implode("','", $id_dealers) . "'";
			$where .= " AND fs.id_dealer in ($ids_string)"; 
		}

		if(!empty($id_dealer)){
			$where .= " AND fs.id_dealer = '$id_dealer'"; 
		}

	
		if(!empty($start_periode)){
			$where .= " AND (fs.start_date BETWEEN '$start_periode' AND '$end_periode' OR fs.end_date BETWEEN '$start_periode' AND '$end_periode')";
		}

		$where .= " AND fs.status_bayar !='lunas'"; 
		$where .= " AND fs.status_faktur ='approved'"; 

		$data = $this->db->query("SELECT fs.no_bastd,fs.tgl_bastd, fs.tgl_approval , md.nama_dealer,md.id_dealer,
		sum(case when fsd.no_mesin is not null then 1 else 0 end) as total_unit, 
		sum(fsd.biaya_bbn_md) as total_biaya  from tr_faktur_stnk_detail fsd 
		left join  tr_faktur_stnk fs on fsd.no_bastd = fs.no_bastd  
		left join ms_dealer md on md.id_dealer = fs.id_dealer
		left join tr_rekap_bbn_generate_detail generate on generate.no_bastd = fs.no_bastd
		$where
		AND generate.id_rekap_bbn_generate is null
		group by fs.no_bastd order by md.id_dealer , fs.no_bastd ASC $limit ")->result();

		$json_data = json_encode($data);
		header('Content-Type: application/json');
		echo $json_data;
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