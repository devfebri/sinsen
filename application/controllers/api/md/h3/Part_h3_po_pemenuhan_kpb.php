<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_h3_po_pemenuhan_kpb extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
    }

    public function index()
    {
        $this->benchmark->mark('data_start');

        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['qty_avs'] = $this->stock->qty_avs($row['id_part']);
            $row['action'] = $this->load->view('additional/md/h3/action_parts_h3_pemenuhan_po_kpb', [
                'data' => json_encode($row),
                'id_part' => $row['id_part'],
                'id_detail' => $this->input->post('id_detail'),
            ], true);

            $data[] = $row;
            $index++;
        }
        $this->benchmark->mark('data_end');

        $output = array(
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal' => $this->recordsTotal(),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data' => $data,
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        );

        send_json($output);
    }

    public function make_query()
    {
        $qty_avs = $this->stock_int->qty_avs('p.id_part_int', [], true);

        $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select("IFNULL(({$qty_avs}), 0) as qty_avs", false)
            ->from('ms_part as p')
            ->join('ms_pvtm as pv', 'pv.no_part = p.id_part')
            ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
            ->where('pv.tipe_marketing', $this->input->post('tipe_produksi'))
            ->where('skp.produk', 'Oil');
    }

    public function make_datatables()
    {
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
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);
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
