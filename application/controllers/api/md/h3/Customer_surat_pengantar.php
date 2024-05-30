<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer_surat_pengantar extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_customer_surat_pengantar', [
                'data' => json_encode($row),
            ], true);
            $data[] = $row;
        }
        $output = array(
            'draw' => intval($this->input->post('draw')), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->get_record_total(), 
            'data' => $data
        );
        send_json($output);
    }
    
    public function make_query() {
        $packing_sheet_sudah_ada_surat_pengantar = $this->db
		->select('spi.id_packing_sheet')
		->from('tr_h3_md_surat_pengantar_items as spi')
		->get_compiled_select()
		;

        $dealer_ready_for_ship = $this->db
        ->select('d.id_dealer')
        ->from('tr_h3_md_packing_sheet as ps')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        ->where('ps.id_packing_sheet != ', null)
		->where("ps.id_packing_sheet not in ({$packing_sheet_sudah_ada_surat_pengantar})")
        ->get_compiled_select();
        ;

        $this->db
        ->select('d.id_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->from('ms_dealer as d')
        ->where("d.id_dealer in ({$dealer_ready_for_ship})")
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
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

    public function get_record_total() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
