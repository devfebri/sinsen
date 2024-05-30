<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Salesman_filter_target_salesman extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_salesman_filter_target_salesman', [
                'id_karyawan' => $row['id_karyawan']
            ], true);

            $index++;
            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('k.id_karyawan')
        ->select('k.npk')
        ->select('k.nama_lengkap')
        ->select('j.jabatan')
        ->from('ms_karyawan as k')
        ->join('ms_jabatan as j', 'j.id_jabatan = k.id_jabatan')
        ->where('k.id_jabatan', 'JBT-071');
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.npk', $search);
            $this->db->or_like('k.nama_lengkap', $search);
            $this->db->or_like('j.jabatan', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.nama_lengkap', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
