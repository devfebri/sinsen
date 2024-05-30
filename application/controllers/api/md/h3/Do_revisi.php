<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Do_revisi extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_do_revisi_datatable', [
                'id' => $row['id'],
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
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
        ->select('dr.id')
        ->select('dr.source')
        ->select('so.id_sales_order')
        ->select('so.tanggal_order as tanggal_so')
        ->select('CONCAT(do.id_do_sales_order, "-REV") as id_do_sales_order')
        ->select('do.tanggal as tanggal_do')
        ->select('dr.created_at as tanggal_scan')
        ->select('d.id_dealer')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.alamat')
        ->select('dr.sub_total_revisi as total')
        ->select('dr.status')
        ->from('tr_h3_md_do_revisi as dr')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->where('left(dr.created_at,10) <=', '2023-12-15');
            $this->db->or_group_start();
            $this->db->where('dr.status', 'Approved');
            $this->db->or_where('dr.status', 'Rejected');
            $this->db->group_end();
        }else{
            $this->db->where('dr.status', 'Open');
            $this->db->where('left(dr.created_at,10) >', '2023-12-15');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->input->post('filter_customer') != null and count($this->input->post('filter_customer')) > 0){
            $this->db->where_in('so.id_dealer', $this->input->post('filter_customer'));
        }

        if ($this->input->post('so_filter') != null and count($this->input->post('so_filter')) > 0) {
            $this->db->where_in('so.id_sales_order', $this->input->post('so_filter'));
        }

        if ($this->input->post('filter_do') != null and count($this->input->post('filter_do')) > 0) {
            $this->db->where_in('do.id_do_sales_order', $this->input->post('filter_do'));
        }

        if($this->input->post('periode_filter_start') != null and $this->input->post('periode_filter_end') != null){
            $this->db->group_start();
            $this->db->where('date_format(dr.created_at, "%Y-%m-%d") >=', $this->input->post('periode_filter_start'));
            $this->db->where('date_format(dr.created_at, "%Y-%m-%d") <=', $this->input->post('periode_filter_end'));
            $this->db->group_end();
        }

        if($this->input->post('tipe_penjualan_filter') != null and count($this->input->post('tipe_penjualan_filter')) > 0){
            $this->db->where_in('so.po_type', $this->input->post('tipe_penjualan_filter'));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dr.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
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
