<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Oli_part_diskon_oli_reguler extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $this->proses_data(),
        ]);
    }

    public function proses_data(){
        $record = $this->make_datatables();
        $data = [];
        foreach ($record as $each) {
            $sub_array = (array) $each;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_oli_part_diskon_oli_reguler', [
                'data' => json_encode($each)
            ], true);
            $data[] = $sub_array;
        }
        return $data;
    }
    
    public function make_query() {
        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.harga_dealer_user as het')
        ->select('p.qty_dus as qty_botol')
        ->select('1 as qty_dus')
        ->from('ms_part as p')
        ->where('p.part_oli', 'Oli')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function recordsFiltered() {
        $this->make_query();

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function recordsTotal() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
