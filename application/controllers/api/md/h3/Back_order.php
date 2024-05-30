<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Back_order extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_back_order_datatable', [
                'id_sales_order' => $row['nomor_so']
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
        $qty_delivery_order = $this->db
        ->select('SUM(dop.qty_supply) as qty_supply')
        ->from('tr_h3_md_do_sales_order as do')
        ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
        ->where('dop.id_part = sop.id_part')
        ->where('do.id_sales_order = sop.id_sales_order')
        ->get_compiled_select();

        $amount_back_order = $this->db
        ->select("SUM( (round(sop.harga_setelah_diskon) * ( sop.qty_order - IFNULL(({$qty_delivery_order}), 0) )) ) as amount", false)
        ->from('tr_h3_md_sales_order_parts as sop')
        ->where('sop.id_sales_order = so.id_sales_order')
        ->get_compiled_select();


        $this->db
        ->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_so')
        ->select('so.id_sales_order as nomor_so')
        ->select('d.kode_dealer_md as kode_customer')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('so.po_type')
        ->select('so.status')
        ->select("IFNULL(({$amount_back_order}), 0) as amount_back_order")
        ->from('tr_h3_md_sales_order as so')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        // ->group_start()
        // ->where('so.po_type', 'REG')
        // ->or_where('so.po_type', 'FIX')
        // ->group_end()
        ->where('so.back_order', 1)
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(so.created_at,10) <=', '2023-09-30');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(so.created_at,10) >', '2023-10-01');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('no_so_filter') != null) {
            $this->db->like('so.id_sales_order', $this->input->post('no_so_filter'));
        }

        if($this->input->post('filter_customer') != null and count($this->input->post('filter_customer')) > 0){
            $this->db->where_in('so.id_dealer', $this->input->post('filter_customer'));
        }

        if($this->input->post('filter_tipe_penjualan') != null and count($this->input->post('filter_tipe_penjualan')) >0){
            $this->db->where_in('so.po_type', $this->input->post('filter_tipe_penjualan'));
        } 

        if($this->input->post('periode_sales_order_filter_start') != null and $this->input->post('periode_sales_order_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_sales_order_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_sales_order_filter_end'));
            $this->db->group_end();
        }

        

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.created_at', 'desc');
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
