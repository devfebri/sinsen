<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_purchase_reguler_and_fix extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
        $this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
        $this->load->model('H3_md_niguri_header_model', 'niguri_header');

        $this->load->library('Mcarbon');
        $this->load->helper('query_execution_time');
    }

    public function index() 
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;

        $tanggal_order = $this->input->post('tanggal_order') == null ? Mcarbon::now() : Mcarbon::parse($this->input->post('tanggal_order'));
        $tanggal_order = $tanggal_order->startOfMonth();
        $perbedaan_bulan = 0;
        $pesan_untuk_bulan = null;
        if($this->input->post('bulan') != null and $this->input->post('tahun') != null){
            $bulan = Mcarbon::parse($this->input->post('bulan'))->format('m');
            $tahun = Mcarbon::parse($this->input->post('tahun'))->format('Y');
            $pesan_untuk_bulan = "{$tahun}-{$bulan}-01";
            $pesan_untuk_bulan = Mcarbon::parse($pesan_untuk_bulan);
            $pesan_untuk_bulan = $pesan_untuk_bulan->startOfMonth();

            $perbedaan_bulan = $tanggal_order->diffInMonths($pesan_untuk_bulan);
        }else{
            $pesan_untuk_bulan = Mcarbon::now();
        }

        if($this->input->post('tanggal_po') != null){
            $bulan_berjalan = Mcarbon::parse($this->input->post('tanggal_po'));
        }else{
            $bulan_berjalan = Mcarbon::now();
        }

        foreach ($this->db->get()->result_array() as $row) {
            // $row['qty_on_hand'] = $this->stock_int->qty_on_hand($row['id_part_int']);
            // $row['qty_on_hand'] = $row['qty_on_hand'];
            // $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
            $row['qty_avs'] =$row['qty_on_hand']-$row['qty_book']-$this->stock_int->qty_claim($row['id_part_int']);
            // $row['qty_in_transit'] = $this->stock_int->qty_intransit($row['id_part_int']);
            // $row['qty_in_transit'] = 0;
            $row['fix_bulan_lalu'] = $this->purchase_order_parts->qty_fix_bulan_lalu($row['id_part_int'], $pesan_untuk_bulan);
            $row['avg_sales'] = round($this->do_sales_order->qty_avg_sales($row['id_part_int'], 'id_part_int'));
            $row['qty_bo'] = $this->purchase_order_parts->qty_bo_ahm($row['id_part_int'], $this->input->post('jenis_po'), $bulan_berjalan, $pesan_untuk_bulan);
            $row['qty_bo_dealer'] = $this->purchase_order_parts->qty_bo_dealer($row['id_part'], $this->input->post('jenis_po'), $bulan_berjalan, $pesan_untuk_bulan);
            $row['qty_order'] = 1;

            $qty_suggest = $this->niguri_header->qty_suggest($row['id_part'], $this->input->post('jenis_po'), $tanggal_order);
            if($qty_suggest != null){
                $row['qty_suggest'] = $qty_suggest['qty_suggest'];

                if($perbedaan_bulan != 0 AND in_array($perbedaan_bulan, range(1,5)) AND $this->input->post('jenis_po') == 'FIX'){
                    $row['_n_key'] = "fix_order_n_{$perbedaan_bulan}";
                    $qty_order = $qty_suggest["fix_order_n_{$perbedaan_bulan}"] == 0 ? $qty_suggest['qty_suggest'] : $qty_suggest["fix_order_n_{$perbedaan_bulan}"];
                    $row['qty_order'] = $qty_order;
                }
            }else{
                $row['qty_suggest'] = 0;
            }

            if($this->input->post('jenis_po') == 'REG' AND floatval($row['qty_order']) == 1 AND floatval($row['qty_order']) < floatval($row['qty_suggest']) ){
                $row['qty_order'] = $row['qty_suggest'];
            }

            $row['action'] = $this->load->view('additional/md/h3/action_parts_purchase_reguler_and_fix', [
                'data' => json_encode($row),
                'id_part' => $row['id_part']
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            // $row['_tanggal_order'] = $tanggal_order->format('Y-m-d');
            // if($pesan_untuk_bulan != null){
            //     $row['_pesan_untuk_bulan'] = $pesan_untuk_bulan->format('Y-m-d');
            // }else{
            //     $row['_pesan_untuk_bulan'] = null;
            // }
            $row['_perbedaan_bulan'] = $perbedaan_bulan;

            $data[] = $row;

            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            // 'query' => query_execution_time()
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('p.id_part_int')
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('kp.kelompok_part')
        ->select('p.minimal_order as qty_min_order')
        // ->select('0 as qty_in_transit')
        ->select('0 as qty_bo')
        ->select('0 as avg_sales')
        ->select('0 as qty_bo_dealer')
        ->select('0 as fix_bulan_lalu')
        // ->select('0 as qty_on_hand')
        ->select('0 as qty_suggest')
        ->select('1 as qty_order')
        ->select('sps.qty as qty_on_hand')
        ->select('sps.qty_book')
        ->select('sps.qty_intransit as qty_in_transit')
        ->select('p.harga_md_dealer as harga')
        ->select('p.harga_dealer_user')
        ->select('1 as checked')
        ->from('ms_part as p')
        ->join('ms_kelompok_part as kp', 'kp.id = p.kelompok_part_int')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part_int = p.kelompok_part_int')
        ->join('tr_stok_part_summary as sps','sps.id_part_int=p.id_part_int')
        ->where('p.kelompok_vendor', 'AHM')
        ->where('skp.produk', $this->input->post('produk'))
        ;

        if($this->input->post('id_kelompok_part') != null){
            $this->db->where('kp.id_kelompok_part', $this->input->post('id_kelompok_part'));
        }

        if ($this->input->post('jenis_po') == 'FIX') {
            $this->db->where('p.fix', 1);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search') ['value']);
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('p.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->group_end();
        // }

        $filter_kp = $this->input->post('filter_kp');
        $filter_np = $this->input->post('filter_np');

        if($filter_kp != ''){
            $this->db->like('p.id_part', $filter_kp);
        }
        if($filter_np != ''){
            $this->db->like('p.nama_part', $filter_np);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'ASC');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}