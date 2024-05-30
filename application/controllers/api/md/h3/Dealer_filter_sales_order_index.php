<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_filter_sales_order_index extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = [];
        foreach ($this->db->get()->result_array() as $row) {
            $row = html_escape($row);
            $row['action'] = $this->load->view('additional/md/h3/action_dealer_filter_sales_order_index', [
                'data' => json_encode($row),
                'id_dealer' => $row['id_dealer']
            ], true);

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
        $this->db
            ->select('d.id_dealer')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('d.status_dealer')
            ->from('ms_dealer as d');
    }

    private function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.nama_dealer', $search);
            $this->db->or_like('d.kode_dealer_md', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'ASC');

        }
    }

    private function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->benchmark->mark('recordsFiltered_start');
        $this->make_datatables();
        $record = $this->db->get()->num_rows();
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
