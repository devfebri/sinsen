<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Monitor_nrfs extends CI_Controller {

	

	var $folder 	=   "h1";

	var $page		=	"monitoring_nrfs";

	var $title  	=   "Monitoring NRFS";



	public function __construct()

	{		

		parent::__construct();

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('h1_model_nrfs','m_nrfs');		
		$this->load->model('m_admin');		



	}

	protected function template($data)

	{

		$name = $this->session->userdata('nama');

		if($name=="")

		{

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		}else{

			$data['id_menu'] = $this->m_admin->getMenu('lap_rekap_bbn_biro');

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

		if (isset($_GET['set'])) {

			if ($_GET['set'] == 'lihat') {
				
				$data['isi']    = $this->page;		

				$data['title']	= $this->title;															

				$data['set']		= "lihat";			

				$this->template($data);	
			

			} elseif ($_GET['set'] == 'download') {

				

		    }

		} else {

			$data['isi']    = $this->page;		

			$data['title']	= $this->title;															

			$data['set']		= "view";			

			$this->template($data);	

		}	

		

	}	



	public function getData()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

        $nrfs = $this->m_nrfs->filter($search, $limit, $start, $order_field, $order_ascdesc, $_GET['tanggal1'], $_GET['tanggal2']);
        $data = array();
        foreach($nrfs->result() as $rows)
        {
        	$gejala = $this->db->get_where('ms_gejala', array('id_gejala'=>$rows->gejala))->row()->gejala;
		    $penyebab = $this->db->get_where('ms_penyebab', array('id_penyebab'=>$rows->penyebab))->row()->penyebab;
		    $perbaikan_gudang = $this->db->get_where('ms_pengatasan', array('id_pengatasan'=>$rows->perbaikan_gudang))->row()->nama_pengatasan;

            $data[]= array(
            	'',
                $rows->date_at,
                $rows->nama_pemeriksa,
                $rows->id_part,
                $gejala,
                $penyebab,
                $rows->no_mesin,
                $rows->no_rangka,
                $rows->tanggal_penerimaan,
                $perbaikan_gudang,
                $rows->id_ekspedisi,
                $rows->no_polisi,
                $rows->nama_kapal,
                $rows->butuh_po,
                $rows->no_po_urgent,
                $rows->estimasi_tgl_selesai,
                $rows->actual_tgl_selesai
            );     
        }
        $total_nrfs = $this->m_nrfs->count_filter($search,$_GET['tanggal1'], $_GET['tanggal2']);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_nrfs,
            "recordsFiltered" => $total_nrfs,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function generate()
    {
    	$tgl1 = $_GET['tanggal1'];
    	$tgl2 = $_GET['tanggal2'];
    	$data = $this->m_nrfs->generate_file($tgl1, $tgl2);

    	$content = "";
    	foreach ($data->result() as $rw) {
			$content .= "$rw->md_code;$rw->date_at;$rw->nama_pemeriksa;$rw->id_part;$rw->gejala;$rw->penyebab;$rw->no_mesin;$rw->no_rangka;$rw->tanggal_penerimaan;$rw->perbaikan_gudang;$rw->id_ekspedisi;$rw->no_polisi;$rw->nama_kapal;$rw->butuh_po;$rw->no_po_urgent;$rw->estimasi_tgl_selesai;$rw->actual_tgl_selesai; \r\n";
		}
		$name_file = "AHM-E20-".date('ymd')."-".date('ymdhis').".NRFS";

		// jika mau disimpan

		// $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/dms_old/uploads/AHM/nrfs/' . $name_file,"wb");
		// fwrite($fp,$content);
		// fclose($fp);

		$this->load->helper('download');
		// auto download
		force_download($name_file, $content);
		
    }






}