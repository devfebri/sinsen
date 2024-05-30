<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Request_document extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/action_booking_reference_purchase_order', [
                'data' => json_encode($each)
            ], true);
            $data[] = $sub_arr;
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
        $used_booking_reference =  $this->db
        ->select('po.id_booking')
        ->from('tr_h3_dealer_purchase_order as po')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->where('po.id_booking !=', null)
        ->where('po.status !=', 'Canceled')
        ->get_compiled_select();

        $this->db
        ->select('rd.*')
        ->select('c.nama_customer')
        ->select('order_to.nama_dealer as nama_dealer_terdekat')
        ->from('tr_h3_dealer_request_document as rd')
        ->join('ms_dealer as order_to', 'order_to.id_dealer = rd.order_to', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->where('rd.id_dealer', $this->m_admin->cari_dealer())
        ->where("rd.id_booking not in (({$used_booking_reference}))")
        ->where('rd.status !=', 'Canceled')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('rd.id_booking', $search);
            $this->db->or_like('rd.id_customer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('rd.created_at', 'desc');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        return $this->db->get()->result();
    }

    public function recordsFiltered()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}