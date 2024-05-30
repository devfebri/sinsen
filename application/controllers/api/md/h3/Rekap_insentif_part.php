<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_insentif_part extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';

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
        $jumlah_faktur = $this->db
        ->select('COUNT(bapi.no_faktur) as no_faktur', false)
        ->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi')
        ->where('bapi.no_bap = bap.no_bap', null, false)
        ->get_compiled_select();

        $jumlah_faktur_dikembalikan = $this->db
        ->select('COUNT(bapi.no_faktur) as no_faktur', false)
        ->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi')
        ->where('bapi.no_bap = bap.no_bap', null, false)
        ->where('bapi.dikembalikan', 1)
        ->get_compiled_select();

        $nominal_dikembalikan = $this->db
        ->select('SUM( IFNULL(bapi.cash, 0) + IFNULL(bapi.transfer, 0) + IFNULL(bapi.amount_bg, 0) ) as nominal_dikembalikan', false)
        ->from('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi')
        ->where('bapi.no_bap = bap.no_bap', null, false)
        ->where('bapi.dikembalikan', 1)
        ->get_compiled_select();

        $this->db
        ->select('bap.created_at')
        ->select("IFNULL(({$jumlah_faktur}), 0) AS jumlah_faktur")
        ->select("IFNULL(({$jumlah_faktur_dikembalikan}), 0) AS jumlah_faktur_dikembalikan")
        ->select("IFNULL(({$nominal_dikembalikan}), 0) AS nominal_dikembalikan")
        ->from('tr_h3_md_berita_acara_penyerahan_faktur as bap')
        ;

        $this->db->group_start();
        $this->db->where(
            sprintf('bap.created_at between "%s" AND "%s"', $this->input->post('periode_filter_start'), $this->input->post('periode_filter_end'))
            , null, false);
        $this->db->group_end();

        $this->db->where('bap.id_debt_collector', $this->input->post('id_collector'));
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = $this->input->post('search') ['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('p.no_surat', $search);
        //     $this->db->group_end();
        // }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('bap.created_at', 'desc');
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
