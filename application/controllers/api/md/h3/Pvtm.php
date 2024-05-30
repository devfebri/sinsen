<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pvtm extends CI_Controller

{
    public function index()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $this->make_datatables();
        $this->limit();
        $records = $this->db->get()->result();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $data[] = $sub_arr;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query2()
    {
        $this->db
        ->select('pv.no_part')
        ->select('pv.tipe_marketing')
        ->select('p.nama_part')
        ->select('
            case 
                when pt.deskripsi is not null then pt.deskripsi
                else "-"
            end as deskripsi
        ', false)
        ->from('ms_pvtm as pv')
        ->join('ms_part as p', 'p.id_part = pv.no_part', 'left')
        ->join('ms_ptm as pt', 'pt.tipe_marketing = pv.tipe_marketing', 'left'); 
    }

    public function make_query()
    {
        $this->db
        ->select('pv.no_part,pv.tipe_marketing,p.nama_part, GROUP_CONCAT(pt.tipe_marketing SEPARATOR ",") as tipe_motor, pt.deskripsi as deskripsi')
        ->from('ms_pvtm as pv')
        ->join('ms_ptm as pt', 'pt.tipe_produksi = pv.tipe_marketing')
        ->join('ms_part as p', 'p.id_part = pv.no_part', 'left')
        ->group_by('pv.no_part,pv.tipe_marketing,p.nama_part');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pv.no_part', $search);
            $this->db->or_like('pv.tipe_marketing', $search);
            $this->db->or_like('pt.deskripsi', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->or_like('pt.tipe_marketing', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pv.tipe_marketing', 'ASC');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        return $this->db->from('ms_pvtm')->count_all_results();
    }
}
