<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order_fix_reguler extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_purchase', [
                'id' => $row['id_purchase_order'],
                'jenis_po' => $row['jenis_po'],
                'status' => $row['status']
            ], true);
            $row['index'] = $this->input->post('start') + $index;
            $data[] = $row;
            $index++;
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
        $this->db
        ->select('date_format(po.tanggal_po, "%d/%m/%Y") as tanggal_po')
        ->select('
        case
            when po.bulan is null then "-"
            else date_format(po.bulan, "%m")
        end as bulan
        ', false)
        ->select('po.jenis_po')
        ->select('po.id_purchase_order')
        ->select('po.total_amount')
        ->select('po.status')
        ->select('po.produk')
        ->from('tr_h3_md_purchase_order as po')
        ->group_start()
        ->where('po.jenis_po', 'FIX')
        ->or_where('po.jenis_po', 'REG')
        ->group_end()
        ;
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('po.status', 'Closed');
            $this->db->or_where('left(po.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        }else{
            $this->db->group_start();
            $this->db->where('po.status !=', 'Closed');
            $this->db->where('left(po.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.id_purchase_order', $search);
            $this->db->group_end();
        }

       

        if($this->input->post('periode_purchase_filter_start') != null and $this->input->post('periode_purchase_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('po.tanggal_po >=', $this->input->post('periode_purchase_filter_start'));
            $this->db->where('po.tanggal_po <=', $this->input->post('periode_purchase_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
        return $this->db->count_all_results();
    }
}
