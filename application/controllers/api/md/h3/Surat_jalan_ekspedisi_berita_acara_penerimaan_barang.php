<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_jalan_ekspedisi_berita_acara_penerimaan_barang extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_surat_jalan_ekspedisi_berita_acara_penerimaan_barang', [
                'data' => json_encode($each)
            ], true);
            $data[] = $sub_arr;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $surat_jalan_sudah_terbuat_berita_acara = $this->db
        ->select('no_surat_jalan_ekspedisi')
        ->from('tr_h3_md_berita_acara_penerimaan_barang')
        ->get_compiled_select();
        
        $this->db
        ->select('pb.*')
        ->from('tr_h3_md_penerimaan_barang as pb')
        ->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor')
        ->where('pb.id_vendor', $this->input->post('id_vendor'))
        ->where("pb.no_surat_jalan_ekspedisi not in ({$surat_jalan_sudah_terbuat_berita_acara})")
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pb.no_surat_jalan_ekspedisi', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pb.no_surat_jalan_ekspedisi', 'desc');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function recordsFiltered()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}