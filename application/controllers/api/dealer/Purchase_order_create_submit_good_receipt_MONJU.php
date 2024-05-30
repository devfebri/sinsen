<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order_create_submit_good_receipt extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_purchase_good_receipt', [
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
        $this->db
        ->select('po.po_id')
        ->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
        ->select('po.id_booking')
        ->select('
            case 
                when rd.id_data_pemesan = 0 then c.nama_customer
                else prh.nama
            end as nama_customer
        ', false)
        ->select('rd.id_sa_form as id_sa_form')
        ->select('wo.id_work_order as id_work_order')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = rd.id_sa_form', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->where('po.po_type', 'HLO')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ;
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.po_id', $search);
            $this->db->or_like('po.id_booking', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.po_id', 'DESC');
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
