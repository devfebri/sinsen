<?php

defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_ms_dealer_terdekat extends Honda_Controller{

    public $folder = "dealer";
    public $page   = "h3_dealer_ms_dealer_terdekat";
    public $title  = "Dealer Terdekat";

    public function __construct(){
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang_h23');
        $this->load->model('h3_dealer_dealer_terdekat_model', 'dealer_terdekat');
        $this->load->model('dealer_model', 'dealer');
    }

    public function index(){
        $data['set']	= "index";
        $this->template($data);
    }

    public function add()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $this->template($data);
    }

    public function save(){
        $insert_data = array_merge($this->input->post(['id_dealer_terdekat']), [
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);
        $insert = $this->dealer_terdekat->insert($insert_data);
        $id = $this->db->insert_id();
        if ($insert) {
			$_SESSION['pesan_' .  $this->page] 	= 'Data berhasil ditambahkan';
			$_SESSION['tipe_' .  $this->page] 	= 'info';
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k=$id'>";
            die;
		}else{
			$_SESSION['pesan_' .  $this->page] 	= 'Data tidak berhasil ditambahkan';
			$_SESSION['tipe_' .  $this->page] 	= 'danger';
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
            die;
		}
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";
        $dealer_terdekat = $this->dealer_terdekat->find($this->input->get('k'));
        $data['dealer_terdekat'] = $this->db
        ->select('dtd.id')
        ->select('dtd.id_dealer_terdekat')
        ->select('dt.nama_dealer as nama_dealer_terdekat')
        ->select('dt.kode_dealer_md as kode_dealer_md_terdekat')
        ->select('dt.alamat as alamat_terdekat')
        ->select('dt.no_telp as no_telp_terdekat')
        ->from('ms_h3_dealer_terdekat as dtd')
        ->join('ms_dealer as dt', 'dt.id_dealer = dtd.id_dealer_terdekat')
        ->where('dtd.id', $this->input->get('k'))
        ->limit(1)
        ->get()->row_array();

        $this->template($data);
    }

    public function delete(){
        $delete = $this->gudang_h23->delete($this->input->get('k'));
        if ($delete) {
            $_SESSION['pesan_' . $this->page] 	= "Data berhasil dihapus.";
            $_SESSION['tipe_' . $this->page] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
            die;
        } else {
            $_SESSION['pesan_' . $this->page] 	= "Data tidak berhasil dihapus";
            $_SESSION['tipe_' . $this->page] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
            die;
        }
    }
}
