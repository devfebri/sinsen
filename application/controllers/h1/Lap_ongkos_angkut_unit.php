<?php



defined('BASEPATH') OR exit('No direct script access allowed');







class Lap_ongkos_angkut_unit extends CI_Controller {



	



	var $folder 	=   "h1/laporan";



	var $page		=	"laporan_ongkos_angkut_unit";



	var $title  	=   "Laporan Ongkos Angkut Unit";







	public function __construct()



	{		



		parent::__construct();



		



		//===== Load Database =====



		$this->load->database();
        $this->db->reconnect();


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



                $tgl1 = date('mY',strtotime($tgl1));

                $tgl2 =date('mY',strtotime($tgl2));



				$data['query'] = $this->db->query("



				select b.*, c.vendor_name, d.ongkos_ahm from (	

                    select DISTINCT a.no_faktur, a.tgl_faktur, a.no_sl, b.no_mesin_lengkap, b.no_rangka, b.id_modell, b.id_warna, c.no_polisi, c.ekspedisi as id_vendor

                    from tr_invoice a

                    join tr_shipping_list b on a.no_sl = b.no_shipping_list

                    left join tr_penerimaan_unit_detail d on a.no_sl = d.no_shipping_list

                    left join tr_penerimaan_unit c on d.id_penerimaan_unit = c.id_penerimaan_unit

                    where tgl_faktur LIKE '%$tgl1' or tgl_faktur like '%$tgl2'

                ) b 

                left join ms_vendor c on b.id_vendor=c.id_vendor

                left join (

                    select a.id_group_angkut, a.id_group_ongkos,  a.nama_group, a.id_vendor, a.ongkos_ahm, a.ongkos_md, b.id_tipe_kendaraan 

                    from ms_group_ongkos a

                    join ms_group_angkut_detail b on a.id_group_angkut = b.id_group_angkut

                    order by a.nama_group ASC, b.id_tipe_kendaraan ASC

                ) d on d.id_tipe_kendaraan = b.id_modell and d.id_vendor = b.id_vendor



				");

				

				



				$html = $this->load->view('h1/laporan/laporan_ongkos_angkut_unit', $data, true);

                

				// render the view into HTML

                $mpdf->AddPage("L","","","","","5","5","15","5","","","","","","","","","","","","A1");

				$mpdf->WriteHTML($html);



				// write the HTML into the mpdf



				$date_buat = date("dmY-hi", strtotime(get_waktu()));



				$output = "lap_ongkos_angkut_unit-$date_buat.pdf";



				$mpdf->Output("$output", 'I');



			} elseif ($_GET['cetak'] == 'export_excel') {



				ini_set('memory_limit', '-1');



				ini_set('max_execution_time', 900);



				$data['set']                   	= 'export_excel';            



				$data['tanggal1']              	= $this->input->get('tanggal1');      



				$data['tanggal2']              	= $this->input->get('tanggal2');



				$data['date_create']			= get_waktu();



                $tgl1 = date('mY',strtotime($tgl1));

                $tgl2 =date('mY',strtotime($tgl2));



				$data['query'] = $this->db->query("



				select b.*, c.vendor_name, d.ongkos_ahm from (	

                    select DISTINCT a.no_faktur, a.tgl_faktur, a.no_sl, b.no_mesin_lengkap, b.no_rangka, b.id_modell, b.id_warna, c.no_polisi, c.ekspedisi as id_vendor

                    from tr_invoice a

                    join tr_shipping_list b on a.no_sl = b.no_shipping_list

                    left join tr_penerimaan_unit_detail d on a.no_sl = d.no_shipping_list

                    left join tr_penerimaan_unit c on d.id_penerimaan_unit = c.id_penerimaan_unit

                    where tgl_faktur LIKE '%$tgl1' or tgl_faktur like '%$tgl2'

                ) b 

                left join ms_vendor c on b.id_vendor=c.id_vendor

                left join (

                    select a.id_group_angkut, a.id_group_ongkos,  a.nama_group, a.id_vendor, a.ongkos_ahm, a.ongkos_md, b.id_tipe_kendaraan 

                    from ms_group_ongkos a

                    join ms_group_angkut_detail b on a.id_group_angkut = b.id_group_angkut

                    order by a.nama_group ASC, b.id_tipe_kendaraan ASC

                ) d on d.id_tipe_kendaraan = b.id_modell and d.id_vendor = b.id_vendor



				");



			

				



				$this->load->view('h1/laporan/laporan_ongkos_angkut_unit', $data);



		    }



		} else {



			$data['isi']    = $this->page;		



			$data['title']	= $this->title;															



			$data['set']		= "view";			



			$this->template($data);	



		}	



		







	}	











}