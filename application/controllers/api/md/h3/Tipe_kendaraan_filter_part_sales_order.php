<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tipe_kendaraan_filter_part_sales_order extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();
        $rows = $this->db->get()->result_array();

        $data = array();
        foreach ($rows as $row) {
            $sub_array = $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_tipe_kendaraan_filter_part_sales_order', [
                'data' => json_encode($row),
                'id_tipe_kendaraan' => $row['id_tipe_kendaraan']
            ], true);
            $data[] = $sub_array;
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
        ->select('tk.*')
        ->select('date_format(tk.tgl_awal, "%d/%m/%Y") as tgl_awal')
        ->from('ms_tipe_kendaraan as tk')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        if($this->input->post('kategori_filter') != null){
            $this->db->where('tk.id_kategori', $this->input->post('kategori_filter'));
        }

        if ($this->input->post('filter_tahun_kendaraan') != null) {
            $this->db->where("left(tk.tgl_awal, 4) = {$this->input->post('filter_tahun_kendaraan')}");
        }

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('tk.id_tipe_kendaraan', $search);
            $this->db->or_like('tk.tipe_ahm', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tk.id_tipe_kendaraan', 'ASC');
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

    public function get_total_data(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
