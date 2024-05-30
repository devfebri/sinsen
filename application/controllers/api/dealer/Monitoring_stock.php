<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_stock extends CI_Controller
{

    private $id_dealer;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('kuantitas_model', 'kuantitas');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');

        $this->id_dealer = $this->m_admin->cari_dealer();
    }

    public function index()
    {
        $this->make_datatables();
        $this->select_for_datatable();
        $this->join_for_datatable();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['stock_on_hand'] = $this->dealer_stock->qty_on_hand($this->id_dealer, $row['id_part'], null, null);
            $row['qty_book'] = $this->dealer_stock->qty_book($this->id_dealer, $row['id_part'], null, null);
            $row['qty_book_hotline'] = $this->dealer_stock->qty_book_hotline($this->id_dealer, $row['id_part'], null, null);
            $row['qty_avs'] = $this->dealer_stock->qty_avs($this->id_dealer, $row['id_part'], null, null);
            $row['stock_in_transit'] = $this->dealer_stock->stock_in_transit($this->id_dealer, $row['id_part']);
            $row['qty_sim_part'] = $this->dealer_stock->qty_sim_part($this->id_dealer, $row['id_part_int']);

            $row['action'] = $this->load->view('additional/action_monitoring_stock', [
                'id' => $row['id_part'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;

            $index++;
        }

        $output = array(
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'search' => trim($this->input->post('search')['value'])
        );

        send_json($output);
    }

    public function make_query()
    {
        $id_part_yang_ada_stock_onhand = $this->db
            ->select('DISTINCT(ds.id_part) as id_part')
            ->from('ms_h3_dealer_stock as ds')
            ->where('ds.id_dealer', $this->m_admin->cari_dealer())
            ->where('ds.stock >', 0)
            ->get_compiled_select();

        $stock_in_transit = $this->dealer_stock->stock_in_transit($this->id_dealer, 'p.id_part', true);

        $tanggal = date("Y-m-d");
        $this->db
            ->from('ms_part as p')
            ->group_start()
            ->where("p.id_part in ({$id_part_yang_ada_stock_onhand})", null, false)
            // ->or_where("({$stock_in_transit}) > 0", null, false)
            ->group_end();
            if($tanggal >='2023-08-06' && $tanggal <='2023-08-12'){
             $this->db->where('p.kelompok_part !=','FED OIL');
            }
    }

    public function select_for_datatable()
    {
        $this->db
            ->select('p.id_part_int')
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('p.harga_dealer_user')
            ->select('p.nama_part_bahasa')
            ->select('ar.rank')
            ->select('p.status')
            ->select('IFNULL(dmp.min_stok, 0) as min_stok')
            ->select('IFNULL(dmp.maks_stok, 0) as maks_stok');
    }

    public function join_for_datatable()
    {
        $this->db
            ->join('ms_h3_analisis_ranking as ar', "(ar.id_part = p.id_part and ar.id_dealer = '{$this->id_dealer}')", 'left')
            ->join('ms_h3_dealer_master_part as dmp', "(dmp.id_part = p.id_part and dmp.id_dealer = '{$this->id_dealer}')", 'left');
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('status') != null) {
            $this->db->where('p.status', $this->input->post('status'));
        }

        if ($this->input->post('rank') != null) {
            $this->db->where('ar.rank', $this->input->post('rank'));
        }

        if ($this->input->post('kelompok_part') != null) {
            $this->db->where('p.kelompok_part', $this->input->post('kelompok_part'));
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->or_like('p.nama_part_bahasa', $search);
            $this->db->group_end();
        }

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
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        $this->join_for_datatable();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    private function list_part_sim_part()
    {
        $id_dealer = $this->id_dealer;
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

    public function get_nilai_stock()
    {
        $stock_on_hand = $this->dealer_stock->qty_on_hand($this->id_dealer, 'p.id_part', null, null, true);

        $this->make_query();

        $this->db
            ->select("SUM( (IFNULL(($stock_on_hand), 0) * p.harga_dealer_user) ) as nilai_stock");

        $data = $this->db->get()->row_array();

        echo $data['nilai_stock'];
        die;
    }

    public function get_nilai_stock_sim_part()
    {
        $stock_on_hand = $this->dealer_stock->qty_on_hand($this->id_dealer, 'p.id_part', null, null, true);
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
        $stock_on_hand = $this->dealer_stock->qty_on_hand($this->id_dealer, 'p.id_part', null, null, true);
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
        $stock_on_hand = $this->dealer_stock->qty_on_hand($this->id_dealer, 'p.id_part', null, null, true);

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
        $stock_on_hand = $this->dealer_stock->qty_on_hand($this->id_dealer, 'p.id_part', null, null, true);
        $list_sim_part = $this->list_part_sim_part();

        $this->make_query();

        $this->db
            ->select("IFNULL(
            COUNT(p.id_part)
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
        $stock_on_hand = $this->dealer_stock->qty_on_hand($this->id_dealer, 'p.id_part', null, null, true);

        $this->make_query();

        $this->db
            ->select("IFNULL(
            COUNT(p.id_part)
            , 0
        ) as jumlah_item", false);

        $data = $this->db->get()->row_array();

        echo $data['jumlah_item'];
        die;
    }
}
