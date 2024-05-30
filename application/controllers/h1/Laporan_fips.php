<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_fips extends CI_Controller {
	
	var $folder =   "h1/laporan";
	var $page		=		"laporan_fips";
	var $title  =   "Laporan Finance Company Indent Priority System (FIPS)";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tgl_indo');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
		$this->load->library('pdf');		

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

		$tgl1 = $this->input->get('tanggal1');

		$tgl2 = $this->input->get('tanggal2');

		if (isset($_GET['cetak'])) {

			if ($_GET['cetak'] == 'cetak') {

				ini_set('memory_limit', '-1');

				ini_set('max_execution_time', 900);

				$mpdf                           = $this->pdf->load();

				$mpdf->allow_charset_conversion =true;  // Set by default to TRUE

				$mpdf->charset_in               ='UTF-8';

				$mpdf->autoLangToFont           = true;

				$data['set']                   	= 'cetak';            

				$data['tanggal1']              	= $this->input->get('tanggal1');      

				$data['tanggal2']              	= $this->input->get('tanggal2');

				$data['date_create']			= get_waktu();



				// $data['query'] = $this->db->query("

				// SELECT
				// 	b.tgl_spk,
				// 	b.no_spk,
				// 	d.nama_dealer,
				// 	b.nama_konsumen,
				// 	e.tipe_ahm,
				// 	b.id_tipe_kendaraan,
				// 	b.id_warna,
				// 	b.jenis_beli,
				// 	c.finance_company,
				// 	a.tgl_pembuatan_po,
				// 	a.po_dari_finco 
				// FROM
				// 	tr_spk AS b
				// 	LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
				// 	LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
				// 	INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
				// 	INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
				// 	INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
				// 	INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
				// WHERE
				// 	b.tgl_spk BETWEEN '$tgl1' 
				// 	AND '$tgl2' 
				// 	AND ( tr_po_dealer_indent.status = 'requested' OR tr_po_dealer_indent.status = 'proses' ) 
				// 	and (tr_po_dealer_indent.id_reasons is null or tr_po_dealer_indent.id_reasons='')
				// 	AND x.id_kwitansi IS NOT NULL 
				// 	AND b.no_spk NOT IN (
				// 	SELECT
				// 		no_spk 
				// 	FROM
				// 		tr_sales_order 
				// 	WHERE
				// 		tgl_create_ssu != '' 
				// 	OR tgl_create_ssu IS NOT NULL 
				// 	)
	
	

				// ");

				$data['query'] = $this->db->query("
				SELECT
					b.tgl_spk,
					b.no_spk,
					d.nama_dealer,
					b.nama_konsumen,
					e.tipe_ahm,
					b.id_tipe_kendaraan,
					b.id_warna,
					b.jenis_beli,
					c.finance_company,
					a.tgl_pembuatan_po,
					a.po_dari_finco,
					z.id_sales_order
				FROM
					tr_spk AS b
					LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
					LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
					LEFT JOIN tr_sales_order as z ON b.no_spk = z.no_spk
					INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
					INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
					INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
					INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
				WHERE
				( tr_po_dealer_indent.status = 'requested' OR tr_po_dealer_indent.status = 'proses' ) 
					and (a.po_dari_finco is not null or a.po_dari_finco !='')
					and (tr_po_dealer_indent.id_reasons is null or tr_po_dealer_indent.id_reasons='')
					AND x.id_kwitansi IS NOT NULL 
					AND z.id_sales_order is null
					
	
	

				");




				$html = $this->load->view('h1/laporan/laporan_fips', $data, true);
                
				// render the view into HTML
                $mpdf->AddPage("L","","","","","5","5","15","5","","","","","","","","","","","","A1");
				$mpdf->WriteHTML($html);

				// write the HTML into the mpdf

				$date_buat = date("dmY-hi", strtotime(get_waktu()));

				$output = "laporan_fips-$date_buat.pdf";

				$mpdf->Output("$output", 'I');

			} elseif ($_GET['cetak'] == 'export_excel') {

				ini_set('memory_limit', '-1');

				ini_set('max_execution_time', 900);

				$data['set']                   	= 'export_excel';            

				$data['tanggal1']              	= $this->input->get('tanggal1');      

				$data['tanggal2']              	= $this->input->get('tanggal2');

				$data['date_create']			= get_waktu();



				$data['query'] = $this->db->query("

				SELECT
					b.tgl_spk,
					b.no_spk,
					d.nama_dealer,
					b.nama_konsumen,
					e.tipe_ahm,
					b.id_tipe_kendaraan,
					b.id_warna,
					b.jenis_beli,
					c.finance_company,
					a.tgl_pembuatan_po,
					a.po_dari_finco,
					z.id_sales_order,
					tr_po_dealer_indent.status as status_indent
				FROM
					tr_spk AS b
					LEFT JOIN tr_entry_po_leasing AS a ON a.no_spk = b.no_spk
					LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
					LEFT JOIN tr_sales_order as z ON b.no_spk = z.no_spk
					INNER JOIN ms_dealer AS d ON b.id_dealer = d.id_dealer
					INNER JOIN ms_tipe_kendaraan AS e ON e.id_tipe_kendaraan = b.id_tipe_kendaraan
					INNER JOIN tr_po_dealer_indent ON tr_po_dealer_indent.id_spk = b.no_spk
					INNER JOIN ( SELECT a.id_kwitansi, a.amount, b.id_spk FROM tr_h1_dealer_invoice_receipt a JOIN tr_invoice_tjs b ON a.no_spk = b.id_spk WHERE jenis_invoice = 'tjs' ) x ON x.id_spk = b.no_spk 
				WHERE
				( tr_po_dealer_indent.status = 'requested' OR tr_po_dealer_indent.status = 'proses' ) 
					and (tr_po_dealer_indent.id_reasons is null or tr_po_dealer_indent.id_reasons='')
					AND x.id_kwitansi IS NOT NULL 
					AND z.id_sales_order is null
					

				");

				$this->load->view('h1/laporan/laporan_fips', $data);

		    } elseif ($_GET['cetak'] == 'export_csv') {
		    	$sql = "
						SELECT 
							b.tgl_spk,
							b.no_spk,
							d.nama_dealer,
							b.nama_konsumen,
							e.tipe_ahm,
							b.id_tipe_kendaraan,
							b.id_warna,
							b.jenis_beli,
							c.finance_company,
							a.tgl_pembuatan_po,
							a.po_dari_finco
						FROM
							tr_spk AS b
							LEFT JOIN tr_entry_po_leasing  AS a ON a.no_spk = b.no_spk
							LEFT JOIN ms_finance_company AS c ON b.id_finance_company = c.id_finance_company
							INNER JOIN ms_dealer as d ON b.id_dealer=d.id_dealer
							INNER JOIN ms_tipe_kendaraan as e ON e.id_tipe_kendaraan=b.id_tipe_kendaraan
							WHERE b.tgl_spk BETWEEN '$tgl1' and '$tgl2'
							and b.status_spk='approved'
							and b.no_spk NOT IN (SELECT no_spk FROM tr_sales_order where tgl_create_ssu !='' )
				";


				// Fetch records from database 
				$query = $this->db->query($sql);
				 
				if($query->num_rows() > 0){ 
				    $delimiter = ","; 
				    $filename = "fips-data_" . date('Y-m-d') . ".csv"; 
				     
				    // Create a file pointer 
				    $f = fopen('php://memory', 'w'); 
				     
				    // Set column headers 
				    $fields = array('NO', 'TGL SPK', 'NO SPK', 'NAMA DEALER', 'NAMA KONSUMEN', 'DESKRIPSI TYPE', 'TYPE', 'WARNA', 'NAMA FINCO', 'TGL PO', 'NO PO'); 
				    fputcsv($f, $fields, $delimiter); 
				    $no = 1;
				    // Output each row of the data, format line as csv and write to file pointer 
				    foreach ($query->result() as $dw) {
				    	if (($dw->jenis_beli == 'Kredit' and $dw->po_dari_finco!='') OR ($dw->jenis_beli == 'Cash')) {
				    		$lineData = [
					     		$no,
					     		$dw->tgl_spk,
					     		$dw->no_spk,
					     		$dw->nama_dealer,
					     		$dw->nama_konsumen,
					     		$dw->tipe_ahm,
					     		$dw->id_tipe_kendaraan,
					     		$dw->id_warna,
					     		$dw->finance_company,
					     		$dw->tgl_pembuatan_po,
					     		$dw->po_dari_finco,

					     	];
					        fputcsv($f, $lineData, $delimiter); 
					        $no++;
				    	}
				     	
				     } 
				     
				    // Move back to beginning of file 
				    fseek($f, 0); 
				     
				    // Set headers to download file rather than displayed 
				    header('Content-Type: text/csv'); 
				    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
				     
				    //output all remaining data on a file pointer 
				    fpassthru($f); 
				} 
				exit; 
		    }

		} else {

			$data['isi']    = $this->page;		

			$data['title']	= $this->title;															

			$data['set']		= "view";			

			$this->template($data);	

		}	


	}	

	public function to_csv()
	{
		
	}


	
}