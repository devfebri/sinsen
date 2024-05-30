<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pembatalan_registrasi_bg extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_pembatalan_registrasi_bg', [
                'id_penerimaan_pembayaran' => $row['id_penerimaan_pembayaran']
            ], true);
            $data[] = $row;
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
        $this->db
        ->select('pb.id_penerimaan_pembayaran')
        ->select('pb.nama_bank_bg')
        ->select('pb.nomor_bg')
        ->select('rek.no_rekening as no_rekening_tujuan')
        ->select('ifnull(pb.keterangan_bg, "-") as keterangan_bg')
        ->from('tr_h3_md_penerimaan_pembayaran as pb')
        ->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
        ->join('ms_rek_md as rek', 'rek.id_rek_md = pb.id_rekening_md_bg', 'left')
        ->where('pb.jenis_pembayaran', 'BG')
        ->where('pb.status_bg', 'Tolak');
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('nama_bank_bg_filter') != null) {
            $this->db->like('pb.nama_bank_bg', trim($this->input->post('nama_bank_bg_filter')));
        }

        if ($this->input->post('no_giro_filter') != null) {
            $this->db->like('pb.nomor_bg', trim($this->input->post('no_giro_filter')));
        }

        if ($this->input->post('nama_bank_bg_filter') == null) {
            $this->db->like('pb.nama_bank_bg', trim($this->input->post('nama_bank_bg_filter')));
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pb.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
