<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_filter_validasi_picking_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();
        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_lokasi_filter_validasi_picking_list', [
                'data' => json_encode($row),
                'id' => $row['id']
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
        $lokasi_rak = $this->db
        ->select('plp.id_lokasi_rak')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->where('plp.id_picking_list', $this->input->post('id_picking_list'))
        ->group_by('plp.id_lokasi_rak')
        ->get_compiled_select()
        ;

        $this->db
        ->select('lr.id')
        ->select('lr.kode_lokasi_rak')
        ->select('lr.deskripsi')
        ->from('ms_h3_md_lokasi_rak as lr')
        ->join('ms_h3_md_gudang as g', 'g.id = lr.id_gudang')
        ->where("lr.id in ({$lokasi_rak})")
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('lr.kode_lokasi_rak', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('lr.kode_lokasi_rak', 'asc');
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
        return $this->db->count_all_results();
    }
}
