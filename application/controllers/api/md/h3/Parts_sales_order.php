<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parts_sales_order extends CI_Controller {

    private $id_part_berdasarkan_tipe_kendaraan;

    public function __construct() {
        parent::__construct();

        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
        $this->load->model('H3_md_ms_sim_part_model', 'sim_part');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();
        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            // $row['qty_on_hand'] = $this->stock_int->qty_on_hand($row['id_part_int']);
            $row['qty_on_hand'] = $row['qty'];
            // $row['qty_actual_dealer'] = $this->stock_int->qty_actual_dealer($row['id_part_int'], $this->input->post('id_dealer'));
            $row['qty_actual_dealer'] = 0; 
            // $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']); // Dikomen pada 13/04/2023 12:00
            // $row['qty_intransit'] = $this->stock_int->qty_intransit($row['id_part_int']);
             $row['qty_intransit'] = 0;
            // $row['qty_booking'] = $this->stock_int->qty_booking($row['id_part_int']);
            $row['qty_booking'] = 0;
            $row['qty_booking_db'] = $row['qty_book'];
            // $row['qty_avs'] = $row['qty_on_hand']-$row['qty_booking']-$this->stock_int->qty_claim($row['id_part_int']);
            $row['qty_avs'] = $row['qty']-$row['qty_book']-$this->stock_int->qty_claim($row['id_part_int']);
            // $row['qty_sim_part'] = $this->sim_part->qty_sim_part($this->input->post('id_dealer'), $row['id_part_int']); Dikomen pada 13/04/2023 08.45

            $row['action'] = $this->load->view('additional/md/h3/action_parts_sales_order', [
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
        $this->id_part_berdasarkan_tipe_kendaraan = $this->db
        ->select('pvtm.no_part')
        ->from('ms_tipe_kendaraan as tk')
        ->join('ms_ptm as ptm', 'ptm.tipe_marketing = tk.id_tipe_kendaraan')
        ->join('ms_pvtm as pvtm', 'pvtm.tipe_marketing = ptm.tipe_produksi')
        ->where('tk.id_tipe_kendaraan', $this->input->post('id_tipe_kendaraan_filter'))
        ->get_compiled_select();

        $this->db
        ->select('p.id_part_int')
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.nama_part_bahasa')
        ->select('p.kelompok_part')
        ->select('sps.qty')
        ->select('sps.qty_book')
        ->select('
            CONCAT(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            )
        as harga_dealer_user')
        ->select('p.harga_dealer_user as harga')
        ->select('1 as qty_order')
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
        ->from('ms_part as p')
        ->join('ms_kelompok_vendor as kv', 'kv.id_kelompok_vendor = p.kelompok_vendor')
        ->join('tr_stok_part_summary as sps','sps.id_part_int=p.id_part_int')
        ;

        if($this->config->item('ahm_only')){
            $this->db->where('p.kelompok_part !=','FED OIL');
        }

        if ($this->input->post('produk') != null) {
            if($this->input->post('produk') != 'Other'){
                $this->db->join("ms_h3_md_setting_kelompok_produk as skp", "(skp.id_kelompok_part = p.kelompok_part and skp.produk = '{$this->input->post('produk')}')");
                $this->db->where('kv.id_kelompok_vendor', 'AHM');
            }else{
                $this->db->where('kv.id_kelompok_vendor !=', 'AHM');
                $this->db->where('kv.id_kelompok_vendor !=', '');
            }
        }

        if ($this->input->post('kategori_po') != null) {
            if($this->input->post('kategori_po') == 'SIM Part'){
                $this->db->where('p.sim_part', 1);
            }else if($this->input->post('kategori_po') == 'Non SIM Part'){
                $this->db->where('p.sim_part', 0);
            }
        }

        if ($this->input->post('is_ev') == 1) {
            $this->db->group_start()
                ->where('p.kelompok_part', 'EVBT')
                ->or_where('p.kelompok_part', 'EVCH')
                ->group_end();
        }else{
            $this->db->group_start()
                ->where('p.kelompok_part !=', 'EVBT')
                ->or_where('p.kelompok_part !=', 'EVCH')
                ->group_end();
        }
    }

    public function make_datatables() {
        $this->make_query();

        if ($this->input->post('id_tipe_kendaraan_filter')) {
            $this->db->where("p.id_part in ({$this->id_part_berdasarkan_tipe_kendaraan})");
        }

        // $search = trim($this->input->post('search')['value']);
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('p.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->group_end();
        // }
        $filter_kp = $this->input->post('filter_kp');
        $filter_np = $this->input->post('filter_np');
        $filter_npb = $this->input->post('filter_npb');

        if($filter_kp != ''){
            $this->db->like('p.id_part', $filter_kp);
        }
        if($filter_np != ''){
            $this->db->like('p.nama_part', $filter_np);
        }
        if($filter_npb != ''){
            $this->db->like('p.nama_part_bahasa', $filter_npb);
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
