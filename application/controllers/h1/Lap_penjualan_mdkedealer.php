<?php



defined('BASEPATH') OR exit('No direct script access allowed');







class Lap_penjualan_mdkedealer extends CI_Controller {



	



	var $folder 	=   "h1/laporan";



	var $page		=	"laporan_penjualan_mdkedealer";



	var $title  	=   "Laporan Penjualan MD Ke Dealer";







	public function __construct()



	{		



		parent::__construct();



		



		//===== Load Database =====



		$this->load->database();



		$this->load->helper('url');



		//===== Load Model =====



		$this->load->model('m_admin');		



		//===== Load Library =====		



		$this->load->library('pdf');		





	}



	protected function template($data)



	{



		$name = $this->session->userdata('nama');



		if($name=="")



		{



			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";



		}else{



			$data['id_menu'] = $this->m_admin->getMenu('lap_ongkos_angkut_unit');



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



				$data['query'] = $this->db->query("



				SELECT d.tgl_faktur,  d.no_faktur, h.nama_dealer, a.no_mesin, a.no_rangka, c.no_do, 

                b.no_picking_list, i.no_surat_jalan, e.harga, e.disc_scp, f.deskripsi_ahm, a.tipe_motor, a.warna, j.retur

                FROM tr_scan_barcode a

                join tr_picking_list_view b on a.no_mesin = b.no_mesin

                join tr_picking_list c on b.no_picking_list = c.no_picking_list

                join tr_invoice_dealer d on c.no_do = d.no_do

                join tr_do_po_detail e on e.no_do = c.no_do and e.id_item = a.id_item

                join ms_tipe_kendaraan f on f.id_tipe_kendaraan = a.tipe_motor

                join tr_do_po g on g.no_do = c.no_do

                join ms_dealer h on h.id_dealer = g.id_dealer

                left join tr_surat_jalan_detail i on i.no_mesin = a.no_mesin

                left join tr_penerimaan_unit_dealer_detail j on a.no_mesin = j.no_mesin

                where d.tgl_faktur BETWEEN '$tgl1' AND '$tgl2' 

                group by a.no_mesin, h.nama_dealer

                order by d.tgl_faktur ASC, d.no_faktur asc, a.no_mesin ASC



                ");

				

				



				$html = $this->load->view('h1/laporan/laporan_penjualan_mdkedealer', $data, true);

                

				// render the view into HTML

                $mpdf->AddPage("L","","","","","5","5","15","5","","","","","","","","","","","","A1");

				$mpdf->WriteHTML($html);



				// write the HTML into the mpdf



				$date_buat = date("dmY-hi", strtotime(get_waktu()));



				$output = "lap_penjualan_md_ke_dealer-$date_buat.pdf";



				$mpdf->Output("$output", 'I');



			} elseif ($_GET['cetak'] == 'export_excel') {



				ini_set('memory_limit', '-1');



				ini_set('max_execution_time', 900);



				$data['set']                   	= 'export_excel';            



				$data['tanggal1']              	= $this->input->get('tanggal1');      



				$data['tanggal2']              	= $this->input->get('tanggal2');



				$data['date_create']			= get_waktu();





				$data['query'] = $this->db->query("



				SELECT d.tgl_faktur,  d.no_faktur, h.nama_dealer, a.no_mesin, a.no_rangka, c.no_do, 

                b.no_picking_list, i.no_surat_jalan, e.harga, e.disc_scp, f.deskripsi_ahm, a.tipe_motor, a.warna, j.retur

                FROM tr_scan_barcode a

                join tr_picking_list_view b on a.no_mesin = b.no_mesin

                join tr_picking_list c on b.no_picking_list = c.no_picking_list

                join tr_invoice_dealer d on c.no_do = d.no_do

                join tr_do_po_detail e on e.no_do = c.no_do and e.id_item = a.id_item

                join ms_tipe_kendaraan f on f.id_tipe_kendaraan = a.tipe_motor

                join tr_do_po g on g.no_do = c.no_do

                join ms_dealer h on h.id_dealer = g.id_dealer

                left join tr_surat_jalan_detail i on i.no_mesin = a.no_mesin

                left join tr_penerimaan_unit_dealer_detail j on a.no_mesin = j.no_mesin

                where d.tgl_faktur BETWEEN '$tgl1' AND '$tgl2' 

                group by a.no_mesin, h.nama_dealer

                order by d.tgl_faktur ASC, d.no_faktur asc, a.no_mesin ASC



				");



			

				



				$this->load->view('h1/laporan/laporan_penjualan_mdkedealer', $data);



		    }



		} else {



			$data['isi']    = $this->page;		



			$data['title']	= $this->title;															



			$data['set']		= "view";			



			$this->template($data);	



		}	



		







	}	











}