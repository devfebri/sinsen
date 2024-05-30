<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_jumlah_pit extends CI_Controller

{
    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            if($each->active == 1){
                $sub_arr['active'] = "<i class='glyphicon glyphicon-ok'></i>";
            }else{
                $sub_arr['active'] = "<i class='glyphicon glyphicon-remove'></i>";
            }

            $sub_arr['action'] = $this->load->view('additional/action_parts_jumlah_pit', [
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
        ->select('p.*')
        ->select('s.satuan')
        ->select('1 as qty_sim_part')
        ->from('ms_part as p')
        ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'ASC');
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
