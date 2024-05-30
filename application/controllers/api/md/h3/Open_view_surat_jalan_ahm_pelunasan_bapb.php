<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Open_view_surat_jalan_ahm_pelunasan_bapb extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index;
            $data[] = $row;
            $index++;
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
        ->select('pbsj.surat_jalan_ahm')
        ->from('tr_h3_md_pelunasan_bapb as pbapb')
        ->join('tr_h3_md_berita_acara_penerimaan_barang as bapb', 'bapb.no_bapb = pbapb.no_bapb')
        ->join('tr_h3_md_penerimaan_barang_surat_jalan_ahm as pbsj', 'pbsj.no_surat_jalan_ekspedisi = bapb.no_surat_jalan_ekspedisi')
        ->where('pbapb.no_pelunasan', $this->input->post('no_pelunasan'))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pbsj.surat_jalan_ahm', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pbsj.surat_jalan_ahm', 'asc');
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
        return $this->db->get()->num_rows();
    }
}