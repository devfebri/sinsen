<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_bg_piutang_belum_cair extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            // $row['action'] = $this->load->view('additional/action_monitoring_bg_piutang_belum_cair', [
            //     'id_penerimaan_pembayaran' => $row['id_penerimaan_pembayaran']
            // ], true);

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'post' => $_POST
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('pp.id_penerimaan_pembayaran')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('pp.nama_bank_bg')
        ->select('pp.nomor_bg')
        ->select('pp.tanggal_jatuh_tempo_bg')
        ->select('pp.nominal_bg')
        ->select('d.nama_bank_h3')
        ->select('d.no_rekening_h3')
        ->from('tr_h3_md_penerimaan_pembayaran as pp')
        ->join('ms_dealer as d', 'd.id_dealer = pp.id_dealer', 'left')
        ->where('pp.jenis_pembayaran', 'BG')
        ->where('pp.proses_bg', 0)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pp.tanggal_jatuh_tempo_bg', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
