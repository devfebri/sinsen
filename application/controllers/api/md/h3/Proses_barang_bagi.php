<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Proses_barang_bagi extends CI_Controller

{
    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_index_h3_md_proses_barang_bagi', [
                'id' => $each->id
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
        $this->db
        ->select('pbb.id')
        ->select('pbb.id_proses_barang_bagi')
        ->select('date_format(pbb.start_date, "%d-%m-%Y") start_date')
        ->select('date_format(pbb.end_date, "%d-%m-%Y") end_date')
        ->select('
            concat(
                pbb.fix,
                " %"
            ) as fix
        ')
        ->select('
            concat(
                pbb.reguler,
                " %"
            ) as reguler
        ')
        ->select('
            concat(
                pbb.hotline,
                " %"
            ) as hotline
        ')
        ->select('
            concat(
                pbb.urgent,
                " %"
            ) as urgent
        ')
        ->select('
            concat(
                pbb.umum,
                " %"
            ) as umum
        ')
        ->from('tr_h3_proses_barang_bagi as pbb')
        ;
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('left(pbb.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('left(pbb.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            // $this->db->like('d.nama_dealer', $search);
            // $this->db->or_like('d.kode_dealer_md', $search);
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pbb.created_at', 'desc');
        }

        if ($this->input->post('length') != - 1) {
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
