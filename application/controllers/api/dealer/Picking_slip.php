<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Picking_slip extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_index_picking_slip', [
                'id' => $row['id'],
            ], true);
            $row['index'] = $this->input->post('index') . $index . '.';

            $data[] = $row;
            $index++;
        }

        $output = array(
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        );

        send_json($output);
    }

    public function make_query()
    {
        $this->db
            ->select('ps.*')
            ->select("
            case 
                when so.id_work_order is not null then so.id_work_order
                else '-'
            end as id_work_order
        ")
            ->select('so.nama_pembeli')
            ->select('
            case
                when c.no_polisi is not null then c.no_polisi
                else "-"
            end as no_polisi
        ', false)
            ->select('ps.status')
            ->from('tr_h3_dealer_picking_slip as ps')
            ->join('tr_h3_dealer_sales_order as so', 'so.id = ps.nomor_so_int')
            ->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
            ->where('ps.id_dealer', $this->m_admin->cari_dealer());
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('filter_status_picking_slip') != null) {
            $this->db->where('ps.status', $this->input->post('filter_status_picking_slip'));
        }

        if ($this->input->post('filter_picking_date') != null) {
            $this->db->group_start();
            $this->db->where("ps.tanggal_ps >= '{$this->input->post('start_date')}'");
            $this->db->where("ps.tanggal_ps <= '{$this->input->post('end_date')}'");
            $this->db->group_end();
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.nomor_ps', $search);
            $this->db->or_like('so.nomor_so', $search);
            $this->db->or_like('so.id_work_order', $search);
            $this->db->or_like('so.nama_pembeli', $search);
            $this->db->or_like('c.no_polisi', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.created_at', 'desc');
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
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
