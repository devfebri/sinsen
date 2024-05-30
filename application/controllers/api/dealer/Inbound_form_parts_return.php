<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inbound_form_parts_return extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_index_inbound_form_parts_return', [
                'id' => $each->nomor_inbound
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
        ->select('ifpr.id_inbound_form_for_parts_return as nomor_inbound')
        ->select('date_format(ifpr.created_at, "%d-%m-%Y") as tanggal_inbound')
        ->select('off.id_outbound_form_for_fulfillment as nomor_outbound')
        ->select('date_format(off.created_at, "%d-%m-%Y") as tanggal_outbound')
        ->select('sj.id_surat_jalan')
        ->select('e.id_event')
        ->select('ifpr.status')
        ->from('tr_h3_dealer_inbound_form_for_parts_return as ifpr')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment off', 'off.id_outbound_form_for_fulfillment = ifpr.id_outbound_form')
        ->join('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj', 'sj.id_outbound_form = ifpr.id_outbound_form')
        ->join('ms_h3_dealer_event_h23 as e', 'e.id_event = off.id_event')
        ->where('ifpr.id_dealer', $this->m_admin->cari_dealer())
        ;

        if($this->input->post('filter_status') != null){
            $this->db->where('ifpr.status', $this->input->post('filter_status'));
        }
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ifpr.id_inbound_form_for_parts_return', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ifpr.created_at', 'DESC');
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
