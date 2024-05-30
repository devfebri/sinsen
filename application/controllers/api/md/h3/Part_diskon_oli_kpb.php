<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_diskon_oli_kpb extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_part_diskon_oli_kpb', [
                'data' => json_encode($row)
            ], true);
            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.harga_dealer_user')
        ->select('
            concat(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            )
        as het')
        ->from('ms_part as p')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
        ->where('skp.produk', 'Oil')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
