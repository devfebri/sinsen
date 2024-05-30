<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Open_view_qty_intransit extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $this->db->get()->result_array(),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('psp.packing_sheet_number')
        ->select('psp.no_doos')
        ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
        ->select('psp.packing_sheet_quantity')
        ->select('pbi.qty_diterima')
        ->from('tr_h3_md_ps_parts as psp')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = psp.packing_sheet_number')
        ->join('tr_h3_md_psl_items as psl', 'psl.packing_sheet_number = psp.packing_sheet_number')
        ->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part = psp.id_part and pbi.packing_sheet_number = psp.packing_sheet_number and pbi.nomor_karton = psp.no_doos)', 'left')
        ->where('psp.id_part', $this->input->post('id_part_open_view_qty_intransit'))
        ->where('pbi.id', null)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.packing_sheet_number', $search);
            $this->db->or_like('psp.no_doos', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.packing_sheet_date', 'asc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_record_total(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
