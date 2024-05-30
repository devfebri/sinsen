<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_proses_jawaban_claim_dealer extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_dealer_proses_jawaban_claim_dealer', [
                'data' => json_encode($row)
            ], true);
            $row['index'] = $this->input->post('start') + $index;
            $data[] = $row;

            $index++;
        }
        
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $dealers = $this->db
        ->select('DISTINCT(cd.id_dealer) as id_dealer')
        ->from('tr_h3_md_jawaban_claim_dealer_parts as jcdp')
        ->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = jcdp.id_claim_dealer')
        ->where('jcdp.id_jawaban_claim_dealer', $this->input->post('id_jawaban_claim_dealer'))
        ->where('
            case
                when jcdp.barang_checklist = 1 then jcdp.proses_ganti_barang = 0
                when jcdp.uang_checklist = 1 then jcdp.proses_ganti_uang = 0
                when jcdp.tolak_checklist = 1 then jcdp.proses_tolak = 0
            end 
        ', null, false)
        ->get()->result_array();
        $dealers = array_map(function($dealer){
            return $dealer['id_dealer'];
        }, $dealers);

        $this->db
        ->select('d.id_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->from('ms_dealer as d');

        if(count($dealers)){
            $this->db->where_in('d.id_dealer', $dealers);
        }else{
            $this->db->where('true = false', null, false);
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.kode_dealer_md', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.kode_dealer_md', 'asc');
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
