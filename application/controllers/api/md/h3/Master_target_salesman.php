<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_target_salesman extends CI_Controller
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
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_target_salesman', [
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
        ->select('k.nama_lengkap')
        ->select('ts.start_date')
        ->select('ts.end_date')
        ->select('ts.jenis_target_salesman')
        ->select('ts.target_salesman_global')
        ->select('ts.target_salesman_channel')
        ->from('ms_h3_md_target_salesman as ts')
        ->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman');

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
            $this->db->where('YEAR(ts.start_date) !=', Mcarbon::now()->format('Y'));
            $this->db->or_where('ts.end_date <=','2023-09-31');
            $this->db->group_end();
        }else{
            $this->db->group_start();
            $this->db->where('YEAR(ts.start_date) =', Mcarbon::now()->format('Y'));
            $this->db->where('ts.start_date >=','2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->input->post('filter_produk') != null) $this->db->where('ts.jenis_target_salesman', $this->input->post('filter_produk'));

        if(count($this->input->post('filter_salesman')) > 0){
            $this->db->where_in('ts.id_salesman', $this->input->post('filter_salesman'));
        }

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.nama_lengkap', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ts.created_at', 'desc');
            $this->db->order_by('k.nama_lengkap', 'desc');
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
