<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Salesman_plafon extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_salesman_plafon', [
                'data' => json_encode($row)
            ], true);
            $data[] = $row;
        }
        
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $id_salesman = $this->db
        ->distinct()
        ->select('ts.id_salesman')
        ->from('ms_h3_md_target_salesman as ts')
        ->join('ms_h3_md_target_salesman_acc as tsa', 'ts.id = tsa.id_target_salesman', 'left')
        ->join('ms_h3_md_target_salesman_oil as tso', 'ts.id = tso.id_target_salesman', 'left')
        ->join('ms_h3_md_target_salesman_parts as tsp', 'ts.id = tsp.id_target_salesman', 'left')
        ->group_start()
        ->where('tsa.id_dealer', $this->input->post('id_dealer'))
        ->or_where('tso.id_dealer', $this->input->post('id_dealer'))
        ->or_where('tsp.id_dealer', $this->input->post('id_dealer'))
        ->group_end()
        ->get()->result_array();

        $id_salesman = array_map(function($data){
            return $data['id_salesman'];
        }, $id_salesman);

        $this->db
        ->select('k.id_karyawan as id_salesman')
        ->select('k.nama_lengkap')
        ->select('j.jabatan')
        ->from('ms_karyawan as k')
        ->join('ms_jabatan as j', 'j.id_jabatan = k.id_jabatan')
        ;

        if(count($id_salesman) > 0){
            $this->db->where_in('k.id_karyawan', $id_salesman);
        }else{
            $this->db->where(false);
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.nama_lengkap', $search);
            $this->db->or_like('j.jabatan', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.nama_lengkap', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }
    
    public function get_total_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
