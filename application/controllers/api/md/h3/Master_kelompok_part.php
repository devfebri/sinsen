<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_kelompok_part extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';

            if($row['active'] == 1){
                $row['active'] = "<i class='glyphicon glyphicon-ok'></i>";
            }else{
                $row['active'] = "<i class='glyphicon glyphicon-remove'></i>";
            }

            $row['action'] = $this->load->view('additional/action_index_master_kelompok_part', [
                'id' => $row['id']
            ], true);

            $index++;
            $data[] = $row;
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
        ->select('kp.*')
        ->select('
            case
                when s.satuan is not null then s.satuan
                else "-"
            end as satuan
        ', false)
        ->from('ms_kelompok_part as kp')
        ->join('ms_satuan_item as si', 'si.id_kelompok_part = kp.id_kelompok_part', 'left')
        ->join('ms_satuan as s', 's.id_satuan = si.id_satuan', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->config->item('ahm_only')){
            $this->db->where('kp.kelompok_part !=','FED OIL');
        }

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kp.id_kelompok_part', $search);
            $this->db->or_like('kp.kelompok_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kp.id_kelompok_part', 'ASC');
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
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
