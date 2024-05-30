<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tipe_kendaraan_diskon_oli_kpb extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_tipe_kendaraan_diskon_oli_kpb', [
                'data' => json_encode($row),
                'selected' => $row['selected']
            ], true);
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
        $tipe_kendaraan_sudah_terpakai = $this->db
        ->select('dok.tipe_produksi')
        ->from('ms_h3_md_diskon_oli_kpb as dok')
        ->where('dok.id_tipe_kendaraan = ptm.tipe_marketing')
        ->where('dok.id_part = pvtm.no_part')
        ->get_compiled_select();

        $this->db
        ->select('ptm.tipe_produksi')
        ->select('ptm.tipe_marketing as id_tipe_kendaraan')
        ->select('ptm.deskripsi as tipe_ahm')
        ->select('date_format(tk.tgl_awal, "%d-%m-%Y") as tgl_awal')
        ->select("
            case
                when ({$tipe_kendaraan_sudah_terpakai}) is null then 0
                else 1
            end as selected
        ")
        ->from('ms_pvtm as pvtm')
        ->join('ms_ptm as ptm', 'ptm.tipe_produksi = pvtm.tipe_marketing')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = ptm.tipe_marketing', 'left')
        ->where('pvtm.no_part', $this->input->post('id_part'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ptm.deskripsi', $search);
            $this->db->or_like('ptm.tipe_marketing', $search);
            $this->db->or_like('ptm.tipe_produksi', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ptm.tipe_marketing', 'asc');
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
