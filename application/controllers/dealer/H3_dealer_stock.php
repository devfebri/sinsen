<?php

defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_stock extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_stock";
    public $title  = "Stock Dealer";

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
        $this->load->library('form_validation');
        $this->load->model('ms_part_model', 'ms_part');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
    }

    public function index()
    {
        $data['set']	= "index";
        
        // $data['kelompok_parts'] = $this->db
        // ->select('kp.kelompok_part')
        // ->from('ms_kelompok_part as kp')
        // ->order_by('kp.kelompok_part', 'asc')
        // ->get()->result_array();

        $tanggal = date("Y-m-d");
        $data['kelompok_parts'] = 
        $this->db
            ->select('kp.kelompok_part')
            ->from('ms_kelompok_part as kp'); 

        // if ($tanggal >= '2023-08-06' && $tanggal <= '2023-08-12') {
        //     $this->db->where('kp.kelompok_part !=', 'FED OIL');
        // }
        
        if($this->config->item('ahm_d_only')){
            $this->db->where('kp.kelompok_part !=', 'FED OIL');
        }

        $data['kelompok_parts'] = $this->db
            ->order_by('kp.kelompok_part', 'asc')
            ->get()
            ->result_array();

        $this->template($data);
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $data['part'] = $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->from('ms_part as p')
        ->where('p.id_part', $this->input->get('id'))
        ->get()->row_array();
        $data['part']['stock_on_hand'] = $this->dealer_stock->qty_on_hand($this->m_admin->cari_dealer(), $data['part']['id_part'], null, null);
        $data['part']['qty_book'] = $this->dealer_stock->qty_book($this->m_admin->cari_dealer(), $data['part']['id_part'], null, null);
        $data['part']['stock_avs'] = $this->dealer_stock->qty_avs($this->m_admin->cari_dealer(), $data['part']['id_part'], null, null);
        $data['part']['intransit'] = $this->dealer_stock->stock_in_transit($this->m_admin->cari_dealer(), $data['part']['id_part']);

        $gudang = $this->db
        ->select('g.id_gudang')
        ->from('ms_gudang_h23 as g')
        ->where('g.id_dealer', $this->m_admin->cari_dealer())
        ->get()->result_array();

        foreach ($gudang as $each_gudang) {
            $sub_array = $each_gudang;
            $sub_array['rak'] = $this->db
            ->select('ds.id_part')
            ->select('r.id_rak')
            ->select('r.id_gudang')
            ->from('ms_lokasi_rak_bin as r')
            ->join('ms_h3_dealer_stock as ds', "(ds.id_part = '{$this->input->get('id')}' and ds.id_rak = r.id_rak and ds.id_gudang = r.id_gudang and ds.id_dealer = r.id_dealer)")
            ->where('r.id_gudang', $each_gudang['id_gudang'])
            ->where('r.id_dealer', $this->m_admin->cari_dealer())
            ->get()->result_array();

            $sub_array['rak'] = array_map(function($row){
                $row['stock_on_hand'] = $this->dealer_stock->qty_on_hand($this->m_admin->cari_dealer(), $row['id_part'], $row['id_gudang'], $row['id_rak']);
                $row['qty_book'] = $this->dealer_stock->qty_book($this->m_admin->cari_dealer(), $row['id_part'], $row['id_gudang'], $row['id_rak']);
                $row['stock_avs'] = $this->dealer_stock->qty_avs($this->m_admin->cari_dealer(), $row['id_part'], $row['id_gudang'], $row['id_rak']);
                $row['intransit_part_transfer'] = $this->dealer_stock->qty_intransit_part_transfer($this->m_admin->cari_dealer(), $row['id_part']);
                $row['intransit_event'] = $this->dealer_stock->qty_intransit_event($this->m_admin->cari_dealer(), $row['id_part']);
                $row['intransit_md'] = $this->dealer_stock->qty_intransit_md($this->m_admin->cari_dealer(), $row['id_part']);
                return $row;
            }, $sub_array['rak']);

            if(count($sub_array['rak']) > 0){
                $data['gudang'][] = $sub_array;
            }
        }

        $this->template($data);
    }

    public function generate(){
        
        $part = $this->db->from('ms_part')->order_by('rand()')->limit(1)->get()->row();

        $rak = $this->db->from('ms_lokasi_rak_bin')
        ->order_by('rand()')
        ->limit(1)->get()->row();

        $data = [
            'id_part' => $part->id_part,
            'id_gudang' => $rak->id_gudang,
            'id_rak' => $rak->id_rak,
            'stock' => rand(50, 200),
            'id_dealer' => $this->m_admin->cari_dealer()
        ];

        $this->db->insert('ms_h3_dealer_stock', $data);

        send_json($data);
    }

}
