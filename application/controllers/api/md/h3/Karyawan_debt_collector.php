<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan_debt_collector extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_karyawan_debt_collector', [
                'data' => json_encode($row),
                'selected' => $row->selected,
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
        ->select('k.npk')
        ->select('k.alamat')
        ->select('k.no_telp')
        ->select('date_format(k.tgl_masuk, "%d-%m-%Y") as tgl_masuk')
        ->select('
            case
                when dc.id is null then 0
                else 1
            end as selected
        ', false)
        ->select('1 as active')
        ->from('ms_karyawan as k')
        ->join('ms_h3_md_debt_collector as dc', 'dc.id_karyawan = k.id_karyawan', 'left')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.npk', $search);
            $this->db->or_like('k.nama_lengkap', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.npk', 'asc');
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
