<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pemenuhan_po_kpb extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        $this->benchmark->mark('data_start');
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_pemenuhan_po_kpb', [
                'id_po_kpb' => $row['id_po_kpb']
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }
        $this->benchmark->mark('data_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        ]);
    }

    public function make_query()
    {
        $this->db
            ->select('po.id_po_kpb')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('date_format(po.tgl_po_kpb, "%d/%m/%Y") as tgl_po_kpb')
            ->select('format(po.tot_qty, 0, "ID_id") as tot_qty')
            ->select('po.grand_total')
            ->select('so.id_sales_order')
            ->select('so.tanggal_order')
            ->from('tr_po_kpb as po')
            ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
            ->join('tr_h3_md_sales_order as so', '(so.id_po_kpb = po.id_po_kpb and so.kategori_po = "KPB" AND so.status != "Canceled")', 'left');

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('so.id_sales_order !=', null);
            $this->db->or_where('left(po.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('so.id_sales_order', null);
            $this->db->where('left(po.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.id_po_kpb', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'desc');
        }
    }

    public function limit()
    {
        if ($this->input->post('length') != -1) {
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
