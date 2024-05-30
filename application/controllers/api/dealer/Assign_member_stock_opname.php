<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Assign_member_stock_opname extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_assign_member_stock_opname_datatable', [
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
        ->select('k.*')
        ->select('j.jabatan')
        ->select('"" as dari')
        ->select('"" as sampai')
        ->from('ms_karyawan_dealer as k')
        ->join('ms_jabatan as j', 'j.id_jabatan = k.id_jabatan')
        ->where('k.id_dealer', $this->m_admin->cari_dealer())
        ->where('k.active','1')
        ;
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.id_karyawan_dealer', $search);
            $this->db->or_like('k.nama_lengkap', $search);
            $this->db->or_like('k.nik', $search);
            $this->db->or_like('j.jabatan', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.nik', 'ASC');
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
