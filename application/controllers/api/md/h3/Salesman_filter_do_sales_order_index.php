<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Salesman_filter_do_sales_order_index extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_salesman_filter_do_sales_order_index', [
                'data' => json_encode($row),
                'selected' => $row->selected
            ], true);
            $data[] = $sub_array;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('k.id_karyawan')
        ->select('k.nama_lengkap')
        ->select('j.jabatan')
        ->select("k.id_karyawan = '{$this->input->post('id_salesman_filter')}' as selected")
        ->from('ms_karyawan as k')
        ->join('ms_jabatan as j', 'j.id_jabatan = k.id_jabatan')
        ->where('j.jabatan', 'Salesman')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.nama_lengkap', $search);
            $this->db->or_like('j.jabatan', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.nama_lengkap', 'asc');
        }

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
