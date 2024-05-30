<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Suggested_order_portal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('H3_md_ms_sim_part_model', 'sim_part');
    }

    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $on_order = $this->db
                ->select('SUM( pop.kuantitas - opt.qty_bill ) as kuantitas', false)
                ->from('tr_h3_dealer_purchase_order_parts as pop')
                ->join('tr_h3_dealer_purchase_order as po', 'po.id = pop.po_id_int')
                ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id_int = po.id and opt.id_part_int = pop.id_part_int)')
                ->where('pop.id_part_int', $row['id_part_int'])
                ->where('po.status', 'Processed by MD')
                ->where('po.kategori_po !=', 'KPB')
                ->where('po.id_dealer', $row['id_dealer'])
                ->limit(1)
                ->get()->row_array();

            $row['on_order'] = $on_order['kuantitas'];

            $stock = $this->db
                ->select('IFNULL(sum(ds.stock), 0) AS kuantitas')
                ->from('ms_h3_dealer_stock as ds')
                ->where('ds.id_part_int', $row['id_part_int'])
                ->where('ds.id_dealer', $row['id_dealer'])
                ->limit(1)
                ->get()->row_array();

            $row['stock'] = $stock['kuantitas'];

            $in_transit = $this->db
                ->select('IFNULL(SUM( opt.qty_bill ), 0) as kuantitas', false)
                ->from('tr_h3_dealer_purchase_order_parts as pop')
                ->join('tr_h3_dealer_purchase_order as po', 'po.id = pop.po_id_int')
                ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id_int = po.id and opt.id_part_int = pop.id_part_int)')
                ->where('pop.id_part_int', $row['id_part_int'])
                ->where('po.status', 'Processed by MD')
                ->where('po.kategori_po !=', 'KPB')
                ->where('po.id_dealer', $row['id_dealer'])
                ->limit(1)
                ->get()->row_array();

            $row['in_transit'] = $in_transit['kuantitas'];

            $row['adjust_order'] = $this->load->view('additional/suggested_order_adjust_order_view', [
                "loop" => $index,
                'id_part' => $row['id_part'],
                'adjusted_order' => $row['adjusted_order'],
            ], true);

            $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            $index++;
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
        $sim_part = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), 'ar.id_part_int', true);

        $this->db
            ->select('ar.id_dealer')
            ->select('mp.id_part')
            ->select('IFNULL(ar.avg_six_weeks, 0) as avg_six_weeks')
            ->select('IFNULL(ar.akumulasi_qty, 0) as akumulasi_qty')
            ->select('IFNULL(ar.akumulasi_persen, 0) as akumulasi_persen')
            ->select('IFNULL(ar.rank, "-") as rank')
            ->select('IFNULL(ar.w1, 0) as w1')
            ->select('IFNULL(ar.w2, 0) as w2')
            ->select('IFNULL(ar.w3, 0) as w3')
            ->select('IFNULL(ar.w4, 0) as w4')
            ->select('IFNULL(ar.w5, 0) as w5')
            ->select('IFNULL(ar.w6, 0) as w6')
            ->select("IFNULL(({$sim_part}), 0) as min_stok")
            ->select('IFNULL(ar.stock_days, 0) as stock_days')
            ->select('IFNULL(ar.suggested_order, 0) as suggested_order')
            ->select('IFNULL(ar.adjusted_order, 0) as adjusted_order')
            ->select('mp.id_part_int')
            ->select('mp.nama_part')
            ->from('ms_h3_analisis_ranking as ar')
            ->join('ms_part as mp', 'mp.id_part_int = ar.id_part_int')
            ->where('ar.id_dealer', $this->m_admin->cari_dealer())
            ->limit(1);
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('kelompok_part') != null) {
            $this->db->where_in('mp.kelompok_part', $this->input->post('kelompok_part'));
        }

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('mp.nama_part', $search);
            $this->db->or_like('mp.id_part', $search);
            $this->db->group_end();
        }

        if ($this->input->post('filter_order') != null) {
            if ($this->input->post('filter_order') == 'sim_part') $this->db->order_by('min_stok', 'desc');
            if ($this->input->post('filter_order') == 'avg_six_weeks') $this->db->order_by('ar.avg_six_weeks', 'desc');
        } else {
            $this->db->order_by('min_stok', 'desc');
        }

        $this->db->order_by('min_stok', 'desc');
        $this->db->order_by('ar.avg_six_weeks', 'DESC');
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
}
