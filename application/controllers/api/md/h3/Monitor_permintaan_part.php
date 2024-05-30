<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitor_permintaan_part extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['view_sales_order'] = $this->load->view('additional/md/h3/action_view_sales_order_monitor_permintaan_part', [
                'po_id' => $row['po_id']
            ], true);

            $row['po_id'] = $this->load->view('additional/md/h3/action_view_modal_purchase_order_monitor_permintaan_part', [
                'po_id' => $row['po_id']
            ], true);
            
            $data[] = $row;
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
        ->select('
        case
            when sales.nama_lengkap is null then "-"
            else sales.nama_lengkap
        end as nama_salesman', false)
        ->select('po.po_type')
        ->select('date_format(po.tanggal_order, "%d/%m/%Y") as tanggal_order')
        ->select('po.po_id')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('
        concat(
            "Rp ",
            format(po.total_amount, 0, "ID_id")
        )
        as total_amount', false)
        ->select('po.status')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        ->join('ms_karyawan as sales', 'sales.id_karyawan = po.id_salesman', 'left')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(po.created_at,10) <=', '2023-09-30');
                $this->db->or_where('po.status', 'Closed');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(po.created_at,10) >', '2023-10-01');
                $this->db->where('po.status !=', 'Closed');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = $this->input->post('search') ['value'];
        // if ($search != '') {
        //     $this->db->like('d.kode_dealer_md', $search);
        //     $this->db->or_like('d.nama_dealer', $search);
        // }

        if ($this->input->post('tipe_penjualan_filter') != null and count($this->input->post('tipe_penjualan_filter')) > 0) {
            $this->db->where_in('po.po_type', $this->input->post('tipe_penjualan_filter'));
        }

        if ($this->input->post('filter_purchase_order') != null and count($this->input->post('filter_purchase_order')) > 0) {
            $this->db->where_in('po.po_id', $this->input->post('filter_purchase_order'));
        }

        if ($this->input->post('filter_customer') != null and count($this->input->post('filter_customer')) > 0) {
            $this->db->where_in('po.id_dealer', $this->input->post('filter_customer'));
        }

        if ($this->input->post('status_filter') != null and count($this->input->post('status_filter')) > 0) {
            $this->db->where_in('po.status', $this->input->post('status_filter'));
        }

        if ($this->input->post('salesman_filter') != null and count($this->input->post('salesman_filter')) > 0) {
            $this->db->where_in('po.id_salesman', $this->input->post('salesman_filter'));
        }

        if($this->input->post('periode_purchase_order_filter_start') != null and $this->input->post('periode_purchase_order_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('po.tanggal_order >=', $this->input->post('periode_purchase_order_filter_start'));
            $this->db->where('po.tanggal_order <=', $this->input->post('periode_purchase_order_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'desc');
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
