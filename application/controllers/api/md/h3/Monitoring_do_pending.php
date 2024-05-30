<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_do_pending extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['id_do_sales_order'] = $this->load->view('additional/md/h3/id_do_sales_order_monitoring_do_pending', [
                'id_do_sales_order' => $row['id_do_sales_order']
            ], true);

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
    
    public function make_query()
    {
        $this->db
        ->select('so.created_at')
        ->select('do.id_do_sales_order')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('do.status')
        ->from('tr_h3_md_do_sales_order as do')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->where_not_in('do.status', [
            'Create Faktur', 'Packing Sheet',
            'Shipping List', 'Canceled'
        ]);
        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(do.created_at,10) <=', '2023-09-30');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(do.created_at,10) >', '2023-10-01');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('so.id_sales_order', $search);
            $this->db->group_end();
        }

        if ( $this->input->post('status_filter') != null and count($this->input->post('status_filter')) > 0 ) {
            $this->db->where_in('do.status', $this->input->post('status_filter'));
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
