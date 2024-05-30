<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Parts_po_vendor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['qty_on_hand'] = $this->stock->qty_on_hand($row['id_part']);
            $row['qty_avg_sales'] = $this->do_sales_order->qty_avg_sales($row['id_part']);
            $row['action'] = $this->load->view('additional/md/h3/action_parts_po_vendor', [
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
        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.kelompok_part')
        ->select('
        concat(
            "Rp ",
            format(p.harga_md_dealer, 0, "ID_id")
        )
        as harga_formatted')
        ->select('p.harga_md_dealer as harga')
        ->select('1 as qty_order')
        ->from('ms_part as p')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
        ->where('skp.produk', 'Other')
        ->where('p.active', 1)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
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
        return $this->db->count_all_results();
    }
}
