<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parts_sales_campaign_detail_poin extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_parts_sales_campaign_detail_poin', [
                'data' => json_encode($row),
                'id_part' => $row['id_part']
            ], true);
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $kelompok_produk = $this->db
        ->select('skp.id_kelompok_part')
        ->from('ms_h3_md_setting_kelompok_produk as skp')
        ->where('skp.produk', $this->input->post('kategori'))
        ->get_compiled_select();

        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('concat(
            "Rp ",
            format(p.harga_dealer_user, 0, "ID_id")
        ) as het')
        ->select('p.kelompok_part')
        ->select('p.status')
        ->select('0 as poin')
        ->from('ms_part as p')
        ->where("p.kelompok_part in ({$kelompok_produk})")
        ;

        if ($this->input->post('id_kelompok_part_filter') != null) {
            $this->db->where('p.kelompok_part', $this->input->post('id_kelompok_part_filter'));
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.nama_part', $search);
            $this->db->or_like('p.id_part', $search);
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

    public function get_total_data(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
