<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Picking_list extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        // $this->db->get()->result_array();
        // echo $this->db->last_query();die;

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_index_picking_list_datatable', [
                'id' => $row['id_picking_list']
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
        /*
        $total_item = $this->db
        ->select('count(plp.id_part)')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->where('plp.id_picking_list = pl.id_picking_list')
        ->get_compiled_select();


        $total_pcs = $this->db
        ->select('sum(plp.qty_supply)')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->where('plp.id_picking_list = pl.id_picking_list')
        ->get_compiled_select();
        */

        $total_item = 0;
        $total_pcs = 0;

        $this->db
        ->select('pl.id_picking_list')
        ->select('pl.id_ref')
        ->select('pl.tanggal')
        ->select('pl.status')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('pl.id_picker')
        ->select('
        case
            when pl.id_picker is null then "-"
            else k.nama_lengkap
        end as nama_picker
        ', false)
        ->select("({$total_item}) as total_item")
        ->select("({$total_pcs}) as total_pcs")
        // ->select('count(plp.id_part) as total_item')
        // ->select('sum(plp.qty_supply) as total_pcs')
        ->from('tr_h3_md_picking_list as pl')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        ->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left');
        // ->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list = pl.id_picking_list', 'left')
        // ->group_by("pl.id_picking_list, pl.id_ref,pl.tanggal, pl.status, d.nama_dealer,d.alamat,pl.id_picker");

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(pl.created_at,10) <=', '2023-09-30');
                $this->db->or_where('pl.status', 'Packing Sheet');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(pl.created_at,10) >', '2023-10-01');
                $this->db->where('pl.status !=', 'Packing Sheet');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('pl.id_picking_list', $search);
            $this->db->or_like('pl.id_ref', $search);
            $this->db->or_like('d.nama_dealer', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.created_at', 'desc');
        }
        
        $this->limit();
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        // $this->make_datatables();
        
        $this->db
        ->select('pl.id_picking_list')
        ->select('pl.id_ref')
        ->select('pl.tanggal')
        ->select('pl.status')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('pl.id_picker')
        ->from('tr_h3_md_picking_list as pl')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        ->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left');

        
        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(pl.created_at,10) <=', '2023-09-08');
                $this->db->or_where('pl.status', 'Packing Sheet');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(pl.created_at,10) >', '2023-09-08');
                $this->db->where('pl.status !=', 'Packing Sheet');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }
        
        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('pl.id_picking_list', $search);
            $this->db->or_like('pl.id_ref', $search);
            $this->db->or_like('d.nama_dealer', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.created_at', 'desc');
        }

        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        // $this->make_query();
        
        $this->db
        ->select('pl.id_picking_list')
        ->select('pl.id_ref')
        ->select('pl.tanggal')
        ->select('pl.status')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('pl.id_picker')
        ->from('tr_h3_md_picking_list as pl')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        ->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left');

        
        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(pl.created_at,10) <=', '2023-09-08');
                $this->db->or_where('pl.status', 'Packing Sheet');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(pl.created_at,10) >', '2023-09-08');
                $this->db->where('pl.status !=', 'Packing Sheet');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }
        
        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('pl.id_picking_list', $search);
            $this->db->or_like('pl.id_ref', $search);
            $this->db->or_like('d.nama_dealer', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.created_at', 'desc');
        }

        return $this->db->count_all_results();
    }
}
