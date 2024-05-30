<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Diskon_part_tertentu extends CI_Controller {

    public function index() {
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $this->proses_data(),
        ]);
    }

    public function proses_data(){
        $this->make_datatables();
        $this->limit();

        $data = [];
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_diskon_part_tertentu', [
                'id' => $row['id']
            ], true);
            $data[] = $row;
        }
        return $data;
    }
    
    public function make_query() {
        $this->db
        ->select('dpt.id')
        ->select('dpt.id_part')
        ->select('p.nama_part')
        ->select('p.kelompok_part')
        ->select('
            concat(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            ) as het
        ', false)
        ->select("
            case 
                when dpt.active = 1 then 'Active'
                else 'Not Active'
            end as status
        ")
        ->select('dpt.tipe_diskon')
        ->select('dpt.diskon_fixed')
        ->select('dpt.diskon_urgent')
        ->select('dpt.diskon_hotline')
        ->select('dpt.diskon_reguler')
        ->select('dpt.diskon_other')
        ->select('dpt.active')
        ->from('ms_h3_md_diskon_part_tertentu as dpt')
        ->join('ms_part as p', 'p.id_part_int = dpt.id_part_int')
        ;

        if($this->config->item('ahm_only')){
            $this->db->where('p.kelompok_part !=','FED OIL');
        }

        if ($this->input->post('part_filter')) {
            $this->db->group_start();
            $this->db->like('p.id_part', $this->input->post('part_filter'));
            $this->db->or_like('p.nama_part', $this->input->post('part_filter'));
            $this->db->group_end();
        }

        if ($this->input->post('id_kelompok_part_filter')) {
            $this->db->where('p.kelompok_part', $this->input->post('id_kelompok_part_filter'));
        }

        if ($this->input->post('active_filter') != null) {
            $this->db->where('dpt.active', $this->input->post('active_filter'));
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('dpt.id_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dpt.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
