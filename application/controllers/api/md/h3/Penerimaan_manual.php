<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_manual extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_penerimaan_manual_datatable', [
                'id_penerimaan_manual' => $row['id_penerimaan_manual']
            ], true);
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
        ->select('pm.id_penerimaan_manual')
        ->select('date_format(pm.tanggal_penerimaan_manual, "%d/%m/%Y") as tanggal_penerimaan_manual')
        ->select('v.vendor_name')
        ->select('ifnull(e.nama_ekspedisi, "-") as nama_ekspedisi')
        ->select('pm.status')
        ->from('tr_h3_md_penerimaan_manual as pm')
        ->join('ms_vendor as v', 'v.id_vendor = pm.id_vendor', 'left')
		->join('ms_h3_md_ekspedisi as e', 'e.id = pm.id_ekspedisi', 'left');
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->or_where('left(pm.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('left(pm.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pm.id_penerimaan_manual', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pm.created_at', 'desc');
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
        return $this->db->count_all_results();
    }
}
