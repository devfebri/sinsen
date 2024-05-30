<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_pengantar extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_surat_pengantar_datatable', [
                'id' => $row['id_surat_pengantar']
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
        ->select('sp.id_surat_pengantar')
        ->select('date_format(sp.tanggal, "%d/%m/%Y") as tanggal')
        ->select('d.nama_dealer')
        ->select('e.nama_ekspedisi')
        ->from('tr_h3_md_surat_pengantar as sp')
        ->join('ms_dealer as d', 'd.id_dealer = sp.id_dealer')
        ->join('ms_h3_md_ekspedisi as e', 'e.id = sp.id_ekspedisi')
        ;
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('left(sp.created_at,10) <=', '2023-09-30');
            $this->db->or_where('sp.close_sl', 1);
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('left(sp.created_at,10) >', '2023-10-01');
            $this->db->where('sp.close_sl', 0);
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('sp.id_surat_pengantar', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('sp.created_at', 'desc');
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
