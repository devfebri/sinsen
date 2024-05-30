<?php
defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_master_part extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_master_part";
    public $title  = "Master Part";

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
        $this->load->model('h3_dealer_master_part_model', 'master_part');
    }

    public function index()
    {
        $data['set']	= "index";
        $this->template($data);
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $this->db
        ->select('ar.*')
        ->select('mp.nama_part')
        ->select('dmp.min_stok, dmp.maks_stok, dmp.safety_stock, dmp.min_sales')
        ->from('ms_h3_analisis_ranking as ar')
        ->join('ms_part as mp', 'mp.id_part = ar.id_part')
        ->join('ms_h3_dealer_master_part as dmp', 'dmp.id_part = ar.id_part', 'left')
        ->where('ar.id_part', $this->input->get('id_part'));
        $data['master_part'] = $this->db->get()->row();

        $this->template($data);
    }

    public function edit()
    {
        $data['set']	= "form";
        $data['mode']  = 'edit';
        $this->db
        ->select('ar.*')
        ->select('mp.nama_part')
        ->select('dmp.min_stok, dmp.maks_stok, dmp.safety_stock, dmp.min_sales')
        ->from('ms_h3_analisis_ranking as ar')
        ->join('ms_part as mp', 'mp.id_part = ar.id_part')
        ->join('ms_h3_dealer_master_part as dmp', 'dmp.id_part = ar.id_part', 'left')
        ->where('ar.id_part', $this->input->get('id_part'));
        $data['master_part'] = $this->db->get()->row();
        $this->template($data);
    }

    public function update()
    {
        $this->db->trans_start();

        if($this->input->post('setting_part_group') != null){
            $kelompok_part = $this->db->select('kelompok_part')->from('ms_part')->where('id_part', $this->input->post('id_part'))->limit(1)->get_compiled_select();
            $part_dalam_satu_kelompok = $this->db
            ->from('ms_part as p')
            ->where("p.kelompok_part = ({$kelompok_part})")
            ->get()->result();

            foreach ($part_dalam_satu_kelompok as $each) {
                $this->proses_update($each->id_part);
            }
        }else{
            $this->proses_update($this->input->post('id_part'));
        }
        

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $_SESSION['pesan'] 	= "Data berhasil diperbarui.";
            $_SESSION['tipe'] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id_part={$this->input->post('id_part')}'>";
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function proses_update($id_part){
        $rowExist = $this->master_part->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
            'id_part' => $id_part
        ], true) != null;

        if($rowExist){
            $condition = [
                'id_dealer' => $this->m_admin->cari_dealer(),
                'id_part' => $id_part
            ];
            $this->master_part->update($this->input->post(['min_stok','maks_stok']), $condition);
        }else{
            $master_part_data = array_merge($this->input->post(['min_stok','maks_stok']), [
                'id_dealer' => $this->m_admin->cari_dealer(),
                'id_part' => $id_part
            ]);

            $this->master_part->insert($master_part_data);
        }
    }
}
