<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Outbound_form_for_fulfillment extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_index_outbound_fulfillment', [
                'id' => $each->id_outbound_form_for_fulfillment
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
        ->select('of.*')
        ->select('e.nama as nama_event')
        ->select("
            case 
            when sj.id_surat_jalan is null then '---'
            else sj.id_surat_jalan
            end as id_surat_jalan
        ")
        ->select('date_format(of.created_at, "%d-%m-%Y") as created_at')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as of')
        ->join('ms_h3_dealer_event_h23 as e', 'e.id_event = of.id_event')
        ->join('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj', 'sj.id_outbound_form = of.id_outbound_form_for_fulfillment', 'left')
        ->where('of.id_dealer', $this->m_admin->cari_dealer())
        ;
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('of.id_outbound_form_for_fulfillment', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('of.created_at', 'DESC');
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
