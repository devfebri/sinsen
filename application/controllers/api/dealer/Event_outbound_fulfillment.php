<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Event_outbound_fulfillment extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_event_outbound_fulfillment', [
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
        $sudah_terpakai = $this->db
        ->select('off.id_event')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as off')
        ->get_compiled_select();

        $this->db
        ->select('e.*')
        ->select('date_format(e.start_date, "%d-%m-%Y") as start_date')
        ->select('date_format(e.end_date, "%d-%m-%Y") as end_date')
        ->select('kd.nama_lengkap as nama_pic')
        ->from('ms_h3_dealer_event_h23 as e')
        ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = e.pic', 'left')
        ->join('ms_jabatan as j', 'j.id_jabatan = kd.id_jabatan', 'left')
        ->where('e.id_dealer', $this->m_admin->cari_dealer())
        ->where("e.id_event not in ({$sudah_terpakai})")
        ->where('e.status', 'Approved')
        ->where('e.start_date >=', date('Y-m-d', time()))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('e.id_event', $search);
            $this->db->or_like('e.nama', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('e.created_at', 'DESC');
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
