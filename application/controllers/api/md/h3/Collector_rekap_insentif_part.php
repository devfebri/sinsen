<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Collector_rekap_insentif_part extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_collector_rekap_insentif_part', [
                'row' => $row,
            ], true);

            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('dc.id_karyawan')
        ->select('k.nama_lengkap')
        ->select('k.npk')
        ->select('j.jabatan')
        ->from('ms_h3_md_debt_collector as dc')
        ->join('ms_karyawan as k', 'k.id_karyawan = dc.id_karyawan')
        ->join('ms_jabatan as j', 'j.id_jabatan = k.id_jabatan')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            // $this->db->group_start();
            // $this->db->like('v.id_vendor', $search);
            // $this->db->or_like('v.vendor_name', $search);
            // $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            // $this->db->order_by('v.id_vendor', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsTotal() {
        $this->make_query();

        return $this->db->get()->num_rows();
    }

    public function recordsFiltered() {
        $this->make_datatables();

        return $this->db->get()->num_rows();
    }
}
