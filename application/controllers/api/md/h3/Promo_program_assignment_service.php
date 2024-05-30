<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo_program_assignment_service extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $fetch_data = $this->make_datatables();
        $data = array();
        foreach ($fetch_data as $rs) {
            $sub_array = (array)$rs;
            $row = json_encode($rs);
            $link = '<button data-dismiss=\'modal\' onClick=\'return pilih_assignment_service(' . $row . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
            $sub_array['aksi'] = $link;
            $data[] = $sub_array;
        }
        $output = array("draw" => intval($_POST["draw"]), "recordsFiltered" => $this->get_filtered_data(), "data" => $data);
        echo json_encode($output);
    }
    
    public function make_query() {
        $jumlah_koli = $this->db->distinct()->select('count(no_dus)')->from('tr_h3_md_scan_picking_list_parts as splp')->where('splp.id_picking_list = ps.id_picking_list')->get_compiled_select();

        $this->db
        ->from("ms_h2_jasa_type");

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('id_type', $search);
            $this->db->or_like('deksripsi', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_type', 'ASC');
        }
    }

    public function make_datatables() {
        $this->make_query();
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
