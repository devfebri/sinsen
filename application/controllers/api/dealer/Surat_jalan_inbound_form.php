<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_jalan_inbound_form extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_surat_jalan_inbound_form', [
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
        $outbound_belum_proses = $this->db
        ->select('ipr.id_outbound_form')
        ->from('tr_h3_dealer_inbound_form_for_parts_return as ipr')
        ->get_compiled_select();

        $this->db
        ->select('sj.id_surat_jalan')
        ->select('date_format(sj.created_at, "%d-%m-%Y") as tanggal_surat_jalan')
        ->select('sj.id_outbound_form')
        ->select('date_format(f.created_at, "%d-%m-%Y") as tanggal_outbound')
        ->select('e.nama as nama_event')
        ->select('e.id_event')
        ->from('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment as f', 'f.id_outbound_form_for_fulfillment = sj.id_outbound_form')
        ->join('ms_h3_dealer_event_h23 as e', 'e.id_event = f.id_event')
        ->where('f.id_dealer', $this->m_admin->cari_dealer())
        ->where("f.id_outbound_form_for_fulfillment not in ({$outbound_belum_proses})")
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('sj.id_surat_jalan', $search);
            $this->db->or_like('f.id_outbound_form_for_fulfillment', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('sj.id_surat_jalan', 'ASC');
        }

        if ($_POST["length"] != - 1) {
            $this
                ->db
                ->limit($_POST['length'], $_POST['start']);
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
