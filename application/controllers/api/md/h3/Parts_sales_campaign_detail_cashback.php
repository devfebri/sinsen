<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parts_sales_campaign_detail_cashback extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_parts_sales_campaign_detail_cashback', [
                'data' => json_encode($row),
                'id_part' => $row->id_part
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
        $kelompok_produk = $this->db
        ->select('id_kelompok_part')
        ->from('ms_h3_md_setting_kelompok_produk')
        ->where('produk', $this->input->post('kategori'))
        ->get_compiled_select();

        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('concat(
            "Rp ",
            format(p.harga_dealer_user, 0, "ID_id")
        ) as het')
        ->select('"" as tipe_diskon')
        ->select('0 as diskon')
        ->select('p.kelompok_part')
        ->select('p.status')
        ->from('ms_part as p')
        ->where("p.kelompok_part in ({$kelompok_produk})")
        ;

        if ($this->input->post('id_kelompok_part_filter') != null) {
            $this->db->where('p.kelompok_part', $this->input->post('id_kelompok_part_filter'));
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.nama_part', $search);
            $this->db->or_like('p.id_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.nama_part', 'ASC');
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
