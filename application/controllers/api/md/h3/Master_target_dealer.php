<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_target_dealer extends CI_Controller
{
    public function __construct(){
        parent::__construct();

        $this->load->library('Mcarbon');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_target_dealer', [
                'id' => $row['id']
            ], true);

            $row['index'] = $this->input->post('start') . $index . '.';
            $data[] = $row;
            $index++;
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
        ->select('ts.id')
        ->select('ts.start_date')
        ->select('ts.end_date')
        ->select('ts.produk')
        ->select('ts.target_global')
        ->from('ms_h3_md_target_sales_out_dealer as ts');
        // ->join('ms_h3_md_target_sales_out_dealer_detail as tsd', 'ts.id = tsd.id_target_sales_out_dealer');

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->where('YEAR(ts.start_date) !=', Mcarbon::now()->format('Y'));
        }else{
            $this->db->where('YEAR(ts.start_date) =', Mcarbon::now()->format('Y'));
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->input->post('filter_produk') != null) $this->db->where('ts.produk', $this->input->post('filter_produk'));

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            // $this->db->like('k.nama_lengkap', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ts.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
