<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Back_order_ahm extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_back_order_ahm', [
                'id' => $row['id_purchase_order'],
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
        ->select('po.id_purchase_order')
        ->select('po.jenis_po')
        ->select('po.total_amount')
        ->select('po.status')
		->select('bo.no_bo_ahm')
		->select('po.sudah_back_order')
        ->from('tr_h3_md_purchase_order as po')
		->join('tr_h3_md_back_order_ahm_upload as bo', 'bo.no_po = po.id_purchase_order', 'left')
        ->where('po.status', 'Approved')
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->or_where('left(po.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->group_start();
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
            $this->db->like('po.id_purchase_order', $search);
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
        return $this->db->get()->num_rows();
    }
}
