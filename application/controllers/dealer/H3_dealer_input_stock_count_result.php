<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_input_stock_count_result extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_input_stock_count_result";
    public $title  = "Input Stock Count Result";

    public function __construct()
    {
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
        $this->load->model('h3_dealer_stock_model', 'stock');
        $this->load->model('ms_part_model', 'part');
        $this->load->model('h3_dealer_stock_opname_model', 'stock_opname');
        $this->load->model('h3_dealer_stock_opname_parts_model', 'stock_opname_parts');
        $this->load->model('h3_dealer_member_stock_opname_model', 'member_stock_opname');
    }

    

    public function index()
    {
        $data['set']	= "index";
        $data['stock_opname'] = $this->stock_opname->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->template($data);
    }

    public function add()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";

        $this->template($data);
    }

    public function detail()
    {
        $data['set']	= "form";
        $data['mode']  = 'detail';
        $stock_opname = $this->db
        ->select('so.*')
        ->select('date_format(so.created_at, "%d-%m-%Y") as created_at')
        ->from('tr_h3_dealer_stock_opname as so')
        ->where('so.id_stock_opname', $this->input->get('id'))
        ->get()->row();
        $data['gudang'] = $this->db->from('ms_gudang_h23 as g')->where('g.id_gudang', $stock_opname->id_gudang)->get()->row();
        $data['pic'] = $this->db->from('ms_karyawan_dealer as k')->where('k.id_karyawan_dealer', $stock_opname->id_pic)->get()->row();

        $data['parts'] = $this->db
        ->select('sop.*')
        ->select('r.unit')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_member_stock_opname as mso')
        ->join('tr_h3_dealer_stock_opname_parts as sop', 'sop.id_stock_opname = mso.id_stock_opname')
        ->join('ms_lokasi_rak_bin as r', '(r.id_rak = sop.id_rak and r.id_gudang = sop.id_gudang)')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->where('mso.id_member', $this->session->userdata('id_karyawan_dealer'))
        ->where('mso.id_stock_opname', $this->input->get('id'))
        ->where('r.unit between mso.dari and mso.sampai')
        ->order_by('r.unit', 'asc')
        ->get()->result();

        $data['stock_opname'] = $stock_opname;

        $this->template($data);
    }

    public function input_stock_aktual(){
        $this->db->trans_start();
        $this->stock_opname_parts->update($this->input->post(['stock_aktual']), $this->input->post([
            'id_part', 'id_rak', 'id_gudang', 'id_stock_opname'
        ]));
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $this->output->set_status_header(200);
        }else{
          $this->output->set_status_header(500);
        }
    }



    public function proses()

    {
        $this->db->trans_start();
        $items_data = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['id_stock_opname']));
        $this->stock_opname_parts->update_batch($items_data, $this->input->post(['id_stock_opname']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->stock_opname->get($this->input->post(['id_stock_opname']), true);
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }

    
}
