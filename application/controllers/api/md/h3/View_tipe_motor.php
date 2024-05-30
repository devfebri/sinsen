<?php
defined('BASEPATH') or exit('No direct script access allowed');

class View_tipe_motor extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $fetch_data = $this->make_datatables();
        $output = array("draw" => intval($_POST["draw"]), "recordsFiltered" => $this->get_filtered_data(), "data" => $fetch_data);
        echo json_encode($output);
    }
    
    public function make_query() {
        $this->db
        ->select('ptm.tipe_produksi')
        ->select('ptm.tipe_marketing')
        ->select('ptm.deskripsi')
        ->from('ms_part as p')
        ->join('ms_pvtm as pvtm', 'pvtm.no_part = p.id_part')
        ->join('ms_ptm as ptm', 'ptm.tipe_produksi = pvtm.tipe_marketing')
        ->where('p.id_part', $this->input->post('id_part_untuk_view_tipe_motor'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        // $search = $this->input->post('search')['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('p.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->group_end();
        // }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ptm.deskripsi', 'ASC');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
