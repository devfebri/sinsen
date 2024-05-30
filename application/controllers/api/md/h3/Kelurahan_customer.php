<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelurahan_customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_kelurahan_customer', [
                'data' => json_encode($row)
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
        ->select('kelurahan.kelurahan')
        ->select('kelurahan.id_kelurahan')
        ->select('kecamatan.kecamatan')
        ->select('kecamatan.id_kecamatan')
        ->select('kabupaten.kabupaten')
        ->select('kabupaten.id_kabupaten')
        ->select('provinsi.provinsi')
        ->select('provinsi.id_provinsi')
        ->from('ms_kelurahan as kelurahan')
        ->join('ms_kecamatan as kecamatan', 'kecamatan.id_kecamatan = kelurahan.id_kecamatan')
        ->join('ms_kabupaten as kabupaten', 'kabupaten.id_kabupaten = kecamatan.id_kabupaten')
        ->join('ms_provinsi as provinsi', 'provinsi.id_provinsi = kabupaten.id_provinsi')
        ;

        
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kelurahan.kelurahan', $search);
            $this->db->or_like('kecamatan.kecamatan', $search);
            $this->db->or_like('kabupaten.kabupaten', $search);
            $this->db->or_like('provinsi.provinsi', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('provinsi.provinsi', 'ASC');
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
