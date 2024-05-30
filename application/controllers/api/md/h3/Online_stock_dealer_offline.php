<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Online_stock_dealer_offline extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_online_stok_dealer_offline_model', 'online_stok_dealer_offline');
        $this->load->model('H3_md_ms_sim_part_model', 'sim_part');
        $this->load->library('Mcarbon');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['tipe_motor'] = $this->load->view('additional/action_view_tipe_motor_online_stock_dealer', [
                'id_part' => $row['id_part'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';
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

    public function make_query()
    {
        $id_dealer = $this->input->post('id_customer_filter');

        $periode_sales_filter_start = $this->input->post('periode_sales_filter_start');
        if($periode_sales_filter_start != null){
            $periode_sales_filter_start = Mcarbon::parse($periode_sales_filter_start);
            $this->db
            ->select('SUM(sales.qty_penjualan) as qty_penjualan', false)
            ->from('tr_h3_md_online_stok_dealer_offline_sales as sales')
            ->where('sales.id_dealer = stok.id_dealer')
            ->where('sales.id_part = p.id_part');

            $this->db->where('sales.bulan', $periode_sales_filter_start->format('n'));

            $qty_sales = $this->db->get_compiled_select();

            $this->db->select("IFNULL( ({$qty_sales}), 0) as qty_sales");
        }else{
            $this->db->select('0 as qty_sales');
        }

        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.harga_dealer_user')
        ->select('IFNULL(stok.stok_onhand, 0) as qty_onhand')
        ->select('IFNULL(stok.stok_avs, 0) as qty_avs')
        ->from('tr_h3_md_online_stok_dealer_offline as stok')
        ->join('ms_part as p', 'p.id_part = stok.id_part')
        ->where('stok.id_dealer', $this->input->post('id_customer_filter'))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = $this->input->post('search') ['value'];
        // if ($search != '') {
        //     $this->db->like('pl.id_picking_list', $search);
        //     $this->db->or_like('pl.id_ref', $search);
        //     $this->db->or_like('d.nama_dealer', $search);
        // }

        if($this->input->post('id_kelompok_part_filter') != null){
            $this->db->where('p.kelompok_part', $this->input->post('id_kelompok_part_filter'));
        }

        if ($this->input->post('id_part_filter') != null) {
            $this->db->where('p.id_part', $this->input->post('id_part_filter'));
        }

        if ($this->input->post('id_simpart_filter') != null) {
            $this->db->where('p.id_part', $this->input->post('id_simpart_filter'));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        if($this->input->post('id_customer_filter') == null) return 0;

        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        if($this->input->post('id_customer_filter') == null) return 0;

        $this->make_query();
        return $this->db->count_all_results();
    }
}
