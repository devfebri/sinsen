<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Debt_collector_berita_acara_penyerahan_faktur extends CI_Controller {

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_debt_collector_berita_acara_penyerahan_faktur', [
                'data' => json_encode($row),
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
            $this->db->group_start();
            $this->db->like('k.npk', $search);
            $this->db->or_like('k.nama_lengkap', $search);
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
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
