<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_invoice_ahm extends CI_Controller
{
    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/action_index_rekap_invoice_ahm', [
                'id' => $each->id_rekap_invoice
            ], true);
            $data[] = $sub_arr;
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
        ->select('ria.id_rekap_invoice')
        ->select('date_format(ria.tgl_jatuh_tempo, "%d-%m-%Y") as tgl_jatuh_tempo')
        ->select('
            concat(
                "Rp ",
                format(
                    ria.total_dpp,
                    0,
                    "ID_id"
                )
            ) as total_dpp
        ', false)
        ->select('
            concat(
                "Rp ",
                format(
                    ria.total_ppn,
                    0,
                    "ID_id"
                )
            ) as total_ppn
        ', false)
        ->select('
            concat(
                "Rp ",
                format(
                    (ria.total_dpp + ria.total_ppn),
                    0,
                    "ID_id"
                )
            ) as amount
        ', false)
        ->select('ria.status')
        ->from('tr_h3_rekap_invoice_ahm as ria');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('ria.id_rekap_invoice', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ria.created_at', 'desc');
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
