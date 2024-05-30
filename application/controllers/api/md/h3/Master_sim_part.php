<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_sim_part extends CI_Controller

{
    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_sim_part', [
                'id_sim_part' => $each->id_sim_part
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
        ->select('sp.*')
        ->select(' (CASE WHEN sp.kategori_sim_part = "pit" then 
            concat(
                sp.batas_bawah_jumlah_pit, 
                " s/d ",
                sp.batas_atas_jumlah_pit
            ) 
         WHEN sp.kategori_sim_part = "ue" then concat(
            sp.batas_bawah_jumlah_ue, 
            " s/d ",
            sp.batas_atas_jumlah_ue
        ) end) as kategori_sim_part' , false)
        ->from('ms_h3_md_sim_part as sp')
        ->order_by('sp.batas_bawah_jumlah_pit', 'asc')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('sp.id_sim_part', $search);
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('sp.created_at', 'desc');
        }

        if ($this->input->post('length') != - 1) {
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
