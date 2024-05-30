<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Po_bundling extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_h3_md_po_bundling', [
                'no_po_aksesoris' => $row['no_po_aksesoris'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    private function make_query()
    {
        $this->db
        ->select('po.no_po_aksesoris')
        ->select('po.tgl_po')
        ->select('po.id_paket_bundling')
        ->select('po.qty_paket')
        ->select('po.keterangan')
        ->select('po.no_surat_jalan')
        ->select('(CASE WHEN po.status_po = "approved" then "New PO" else po.status_po end) as status_po')
        ->select('so.id_sales_order')
        ->from('tr_po_aksesoris as po')
		->join('tr_h3_md_sales_order as so', '(so.referensi_po_bundling = po.no_po_aksesoris AND so.status != "Canceled")', 'left')
        ->where('po.active', 1)
        ->group_start()
        ->where('po.status_po !=', 'input')
        ->where('po.status_po !=', 'rejected')
        ->group_end();

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('so.id_sales_order is not null', null, false);
        }else{
            $this->db->where('so.id_sales_order is null', null, false);
        }
    }

    private function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.no_po_aksesoris', $search);
            $this->db->or_like('po.id_paket_bundling', $search);
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

    private function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    private function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    private function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
