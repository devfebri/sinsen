<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parts_do_sales_order extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_ms_sim_part_model', 'sim_part');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();
        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['qty_onhand'] = $this->stock->qty_on_hand($row['id_part']);
            $row['qty_actual_dealer'] = $this->stock->qty_actual_dealer($row['id_part'], $this->input->post('id_dealer'));
            $row['qty_avs'] = $this->stock->qty_avs($row['id_part']);
            $row['qty_intransit'] = $this->stock->qty_intransit($row['id_part']);
            $row['qty_booking'] = $this->stock->qty_booking($row['id_part']);
            $row['qty_sim_part'] = $this->sim_part->qty_sim_part($this->input->post('id_dealer'), $row['id_part_int']);

            $row['action'] = $this->load->view('additional/md/h3/action_parts_do_sales_order', [
                'data' => json_encode($row),
                'id_part' => $row['id_part']
            ], true);

            $row['view_tipe_motor'] = $this->load->view('additional/md/h3/view_tipe_motor_parts_sales_order', [
                'id_part' => $row['id_part']
            ], true);
            
            $data[] = $row;
        }
        $output = array(
            'draw' => intval($this->input->post('draw')), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->get_total_data(), 
            'data' => $data
        );
        send_json($output);
    }
    
    public function make_query() {
        $this->db
        ->select('p.id_part_int')
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.kelompok_part')
        ->select('p.harga_dealer_user as harga_jual')
        ->select('p.harga_md_dealer as harga_beli')
        ->select('1 as qty_supply')
        ->select('sop.qty_order')
        ->select("0 as diskon_value")
        ->select("'' as tipe_diskon")
        ->select("0 as diskon_value_campaign")
        ->select("'' as tipe_diskon_campaign")
        ->select('
            CASE
                WHEN p.status = "A" THEN "Active"
                WHEN p.status = "D" THEN "Discontinue"
            END as status
        ', false)
        ->from('tr_h3_md_sales_order_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_kelompok_vendor as kv', 'kv.id_kelompok_vendor = p.kelompok_vendor')
        ->where('sop.id_sales_order', $this->input->post('id_sales_order'))
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

    public function get_total_data(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
