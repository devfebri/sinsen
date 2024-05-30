<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pemenuhan_po_dealer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_pemenuhan_po_dealer', [
                'id' => $row['po_id']
            ], true);
            
            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            
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
        ->select('po.po_id')
        // ->select('po.tanggal_order')
        ->select('(CASE WHEN po.revised_dealer_at is not null then po.revised_dealer_at else po.tanggal_order end) as tanggal_order')
        ->select('po.tanggal_po_md')
        ->select('po.tanggal_po_ahm')
        ->select('c.nama_customer')
        ->select('c.no_hp')
        ->select('d.nama_dealer')
        ->select('po.status_md')
        ->select('po.total_amount')
        ->select('po.amount_supply_md')
        ->select('( (po.amount_supply_md/po.total_amount) * 100 ) as service_rate')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id = po.id_booking_int')
        ->join('ms_customer_h23 as c', 'c.id_customer_int = rd.id_customer_int')
        ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        ->where('po.po_type', 'HLO')
        ->where('po.created_by_md', 0)
        ;

        if($this->input->post('tanggal_po_filter_start') != null and $this->input->post('tanggal_po_filter_end') != null){            
            $this->db->group_start();
            $this->db->where("po.tanggal_order between '{$this->input->post('tanggal_po_filter_start')}' AND '{$this->input->post('tanggal_po_filter_end')}'");
            $this->db->group_end();
        }

        if($this->input->post('dealer_filter') != null AND count($this->input->post('dealer_filter')) > 0){
            $this->db->where_in('d.id_dealer', $this->input->post('dealer_filter'));
        }

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('po.status', 'Closed');
            $this->db->group_end();
            $this->db->or_where('left(po.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        }else{
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('po.status', 'Processed by MD');
            $this->db->or_where('po.status', 'Submit & Approve Revisi');
            $this->db->group_end();
            $this->db->where('left(po.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->or_like('po.po_id', $search);
            $this->db->or_like('c.no_hp', $search);
            $this->db->or_like('c.nama_customer', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.tanggal_order', 'desc');
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
        return $this->db->count_all_results();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
