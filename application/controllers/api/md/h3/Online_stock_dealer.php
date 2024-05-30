<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Online_stock_dealer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
    }

    public function index()
    {
        $this->benchmark->mark('data_start');
        $id_dealer = $this->input->post('id_customer_filter');

        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->select('IFNULL( SUM(sop.kuantitas), 0) as kuantitas', false)
                ->from('tr_h3_dealer_sales_order_parts as sop')
                ->join('tr_h3_dealer_sales_order as so', 'so.id = sop.nomor_so_int')
                ->where('sop.id_part_int', $row['id_part_int'])
                ->where('so.id_dealer', $id_dealer)
                ->where('so.status', 'Closed')
                ->group_by('sop.id_part');

            $periode_sales_filter_start = $this->input->post('periode_sales_filter_start');
            $periode_sales_filter_end = $this->input->post('periode_sales_filter_end');
            if ($periode_sales_filter_start != null and $periode_sales_filter_end != null) {
                $this->db->where(sprintf("so.tanggal_so BETWEEN '%s' AND '%s'", $periode_sales_filter_start, $periode_sales_filter_end), null, false);
            } else {
                $this->db->where('1 = 0', null, false);
            }
            $qty_sales = $this->db->get()->row_array();

            $row['qty_sales'] = $qty_sales['kuantitas'];

            $kuantitas_sales_order_closed = $this->db
                ->select('SUM(sop.kuantitas) as kuantitas', false)
                ->from('tr_h3_dealer_sales_order as so')
                ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so_int = so.id')
                ->where('so.id_dealer', $id_dealer)
                ->where('sop.id_part = pop.id_part', null, false)
                ->where('so.booking_id_reference = po.id_booking', null, false)
                ->where('so.status', 'Closed')
                ->get_compiled_select();

            $qty_order_fulfillment = $this->db
                ->select('SUM(of.qty_fulfillment) as qty_fulfillment', false)
                ->from('tr_h3_dealer_order_fulfillment as of')
                ->where('of.po_id_int = po.id', null, false)
                ->where('of.id_part_int = pop.id_part_int', null, false)
                ->get_compiled_select();

            $hotline_belum_diserahkan = $this->db
                ->select("SUM( (IFNULL(({$qty_order_fulfillment}), 0) - IFNULL(({$kuantitas_sales_order_closed}), 0)) ) as kuantitas_belum_terpenuhi", false)
                ->from('tr_h3_dealer_purchase_order as po')
                ->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id_int = po.id')
                ->where('po.id_dealer', $id_dealer)
                ->where('pop.id_part_int', $row['id_part_int'])
                ->get()->row_array();
            $row['hotline_belum_diserahkan'] = $hotline_belum_diserahkan['kuantitas_belum_terpenuhi'];

            $row['tipe_motor'] = $this->load->view('additional/action_view_tipe_motor_online_stock_dealer', [
                'id_part' => $row['id_part'],
            ], true);

            $row['qty_book_hotline'] = $this->dealer_stock->qty_book_hotline($this->input->post('id_customer_filter'), $row['id_part'], null, null);
            $row['qty_avs'] = $this->dealer_stock->qty_avs($this->input->post('id_customer_filter'), $row['id_part'], null, null);
            $row['qty_sim_part'] = $this->dealer_stock->qty_sim_part($this->input->post('id_customer_filter'), $row['id_part_int'], null, null);

            $row['qty_hotline'] = $this->load->view('additional/action_view_qty_hotline_online_stock_dealer', [
                'id_part' => $row['id_part'],
                'qty_hotline' => $row['qty_book_hotline']
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            $data[] = $row;
        }
        $this->benchmark->mark('data_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal' => $this->recordsTotal(),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data' => $data,
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        ]);
    }

    public function make_query()
    {
        $id_dealer = $this->input->post('id_customer_filter');

        $this->db
            ->select('IFNULL( SUM(sop.kuantitas), 0)', false)
            ->from('tr_h3_dealer_sales_order_parts as sop')
            ->join('tr_h3_dealer_sales_order as so', 'so.id = sop.nomor_so_int')
            ->where('sop.id_part_int = p.id_part_int', null, false)
            ->where('so.id_dealer', $id_dealer)
            ->where('so.status', 'Closed')
            ->group_by('sop.id_part');

        $periode_sales_filter_start = $this->input->post('periode_sales_filter_start');
        $periode_sales_filter_end = $this->input->post('periode_sales_filter_end');
        if ($periode_sales_filter_start != null and $periode_sales_filter_end != null) {
            $this->db->where(sprintf("so.tanggal_so BETWEEN '%s' AND '%s'", $periode_sales_filter_start, $periode_sales_filter_end), null, false);
        } else {
            $this->db->where('1 = 0', null, false);
        }
        $qty_sales = $this->db->get_compiled_select();


        $kuantitas_sales_order_closed = $this->db
            ->select('SUM(sop.kuantitas) as kuantitas', false)
            ->from('tr_h3_dealer_sales_order as so')
            ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so_int = so.id')
            ->where('so.id_dealer', $id_dealer)
            ->where('sop.id_part_int = pop.id_part_int', null, false)
            ->where('so.booking_id_reference = po.id_booking', null, false)
            ->where('so.status', 'Closed')
            ->get_compiled_select();

        $qty_order_fulfillment = $this->db
            ->select('SUM(of.qty_fulfillment) as qty_fulfillment', false)
            ->from('tr_h3_dealer_order_fulfillment as of')
            ->where('of.po_id_int = po.id', null, false)
            ->where('of.id_part_int = pop.id_part_int', null, false)
            ->get_compiled_select();

        $hotline_belum_diserahkan = $this->db
            // ->select('po.po_id')
            // ->select('po.tanggal_order')
            // ->select('po.id_booking')
            // ->select('po.order_to')
            // ->select('pop.id_part')
            // ->select('pop.kuantitas')
            // // ->select("IFNULL(({$good_receipt}), 0) as good_receipt", false)
            // ->select("IFNULL(({$kuantitas_sales_order}), 0) as kuantitas_sales_order", false)
            // ->select("IFNULL(({$kuantitas_sales_order_closed}), 0) as kuantitas_sales_order_closed", false)
            // ->select("(pop.kuantitas - IFNULL(({$kuantitas_sales_order_closed}), 0)) as kuantitas_belum_terpenuhi", false)
            // ->select('po.penyerahan_customer')
            // ->select('po.status')
            ->select("SUM( (IFNULL(({$qty_order_fulfillment}), 0) - IFNULL(({$kuantitas_sales_order_closed}), 0)) ) as kuantitas_belum_terpenuhi", false)
            ->from('tr_h3_dealer_purchase_order as po')
            ->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id_int = po.id')
            ->where('po.id_dealer', $id_dealer)
            ->where('pop.id_part_int = ds.id_part_int', null, false)
            ->get_compiled_select();

        $this->db
            ->select('p.id_part_int')
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('p.harga_dealer_user')
            ->select('SUM(ds.stock) as qty_onhand', false)
            // ->select("IFNULL( ({$qty_sales}), 0) as qty_sales", false)
            // ->select("IFNULL( ({$hotline_belum_diserahkan}), 0) as hotline_belum_diserahkan", false)
            ->from('ms_part as p')
            ->join('ms_h3_dealer_stock as ds', 'ds.id_part_int = p.id_part_int')
            ->where('ds.id_dealer', $id_dealer)
            ->group_by('p.id_part_int');
    }

    public function make_datatables()
    {
        $this->make_query();
        if($this->config->item('ahm_only')){
            $this->db->where('p.kelompok_part !=','FED OIL');
        }

        if ($this->input->post('id_kelompok_part_filter') != null) {
            $this->db->where('p.kelompok_part', $this->input->post('id_kelompok_part_filter'));
        }

        // if ($this->input->post('id_part_filter') != null) {
        //     $this->db->where('p.id_part', $this->input->post('id_part_filter'));
        // }

        if($this->input->post('kode_part_filter') != null){
            $this->db->like('p.id_part', trim($this->input->post('kode_part_filter')));
        }

        if($this->input->post('nama_part_filter') != null){
            $this->db->like('p.nama_part', trim($this->input->post('nama_part_filter')));
        }

        // if ($this->input->post('id_simpart_filter') != null) {
        //     $this->db->where('p.id_part', $this->input->post('id_simpart_filter'));
        // }

        if($this->input->post('sim_part') == 'sim'){
            $this->db->where('p.sim_part', 1);
        }elseif($this->input->post('sim_part') == 'non'){
            $this->db->where('p.sim_part', 0);
        }

        // $search = $this->input->post('search') ['value'];
        // if ($search != '') {
        //     $this->db->like('pl.id_picking_list', $search);
        //     $this->db->or_like('pl.id_ref', $search);
        //     $this->db->or_like('d.nama_dealer', $search);
        // }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->benchmark->mark('recordsFiltered_start');
        $this->make_datatables();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsFiltered_end');

        return $record;
    }

    public function recordsTotal()
    {
        $this->benchmark->mark('recordsTotal_start');
        $this->make_query();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsTotal_end');

        return $record;
    }

    public function get_nilai_stock()
    {
        $dealer = $this->input->post('id_customer_filter');
        $stock_on_hand = $this->dealer_stock->qty_on_hand2($dealer, 'p.id_part_int', null, null, true);

        $this->make_query();

        if($this->config->item('ahm_only')){
            $data['nilai_stock'] = 0;
        }else{
            $this->db
            ->select("SUM( (IFNULL(($stock_on_hand), 0) * p.harga_dealer_user) ) as nilai_stock");

            $data = $this->db->get()->row_array();
        }

        

        // $data= $dealer;
        echo $data['nilai_stock'];
        die;
    }

    private function list_part_sim_part()
    {
        $id_dealer = $this->input->post('id_customer_filter');
        $jumlah_pit = 0;
        $data_jumlah_pit = $this->db
            ->select('jumlah_pit')
            ->from('ms_h3_md_jumlah_pit')
            ->where('id_dealer', $id_dealer)
            ->limit(1)
            ->get()->row_array();
        if ($data_jumlah_pit != null) $jumlah_pit = $data_jumlah_pit['jumlah_pit'];

        $this->db
            ->select('spi.id_part_int')
            ->from('ms_h3_md_sim_part as sp')
            ->join('ms_h3_md_sim_part_item as spi', 'spi.id_sim_part_int = sp.id')
            ->group_start()
            ->where('sp.batas_bawah_jumlah_pit <=', $jumlah_pit)
            ->where('sp.batas_atas_jumlah_pit >=', $jumlah_pit)
            ->group_end();

        $list_part = array_map(function ($part) {
            return $part['id_part_int'];
        }, $this->db->get()->result_array());

        return array_unique($list_part);
    }

    public function get_nilai_stock_sim_part()
    {
        $id_dealer = $this->input->post('id_customer_filter');
        $stock_on_hand = $this->dealer_stock->qty_on_hand2($id_dealer, 'p.id_part_int', null, null, true);
        $list_sim_part = $this->list_part_sim_part();

        $this->make_query();

        $this->db
            ->select("IFNULL(
            SUM( (IFNULL(($stock_on_hand), 0) * p.harga_dealer_user) )
        , 0) as nilai_stock");

        if (count($list_sim_part) > 0) {
            $this->db->where_in('p.id_part_int', $list_sim_part);
        }

        $data = $this->db->get()->row_array();

        echo $data['nilai_stock'];
        die;
    }


    public function get_qty_stock_sim_part()
    {
        $id_dealer = $this->input->post('id_customer_filter');
        $stock_on_hand = $this->dealer_stock->qty_on_hand2($id_dealer, 'p.id_part_int', null, null, true);
        $list_sim_part = $this->list_part_sim_part();

        $this->make_query();

        $this->db
            ->select("IFNULL(
            SUM( IFNULL(({$stock_on_hand}), 0) )
            , 0
        ) as stock", false);

        if (count($list_sim_part) > 0) {
            $this->db->where_in('p.id_part_int', $list_sim_part);
        }

        $data = $this->db->get()->row_array();

        echo $data['stock'];
        die;
    }

    public function get_qty_stock()
    {
        $id_dealer = $this->input->post('id_customer_filter');
        $stock_on_hand = $this->dealer_stock->qty_on_hand2($id_dealer, 'p.id_part_int', null, null, true);

        $this->make_query();

        $this->db
            ->select("IFNULL(
            SUM( IFNULL(({$stock_on_hand}), 0) )
            , 0
        ) as stock", false);

        $data = $this->db->get()->row_array();

        echo $data['stock'];
        die;
    }

    public function get_item_stock_sim_part()
    {
        $id_dealer = $this->input->post('id_customer_filter');
        $list_sim_part = $this->list_part_sim_part();

        $this->make_query();

        $this->db
            ->select("IFNULL(
            COUNT(p.id_part_int)
            , 0
        ) as jumlah_item", false);

        if (count($list_sim_part) > 0) {
            $this->db->where_in('p.id_part_int', $list_sim_part);
        }

        $data = $this->db->get()->row_array();

        echo $data['jumlah_item'];
        die;
    }

    public function get_item_stock()
    {
        $id_dealer = $this->input->post('id_customer_filter');

        $this->make_query();

        if($this->config->item('ahm_only')){
            $data['jumlah_item'] = 0;
        }else{
            $this->db
            ->select("IFNULL(
            COUNT(p.id_part_int)
            , 0
            ) as jumlah_item", false);

            
            $data = $this->db->get()->row_array();
        }

        

        echo $data['jumlah_item'];
        die;
    }
}
