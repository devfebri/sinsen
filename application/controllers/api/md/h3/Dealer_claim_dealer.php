<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_claim_dealer extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_dealer_claim_dealer', [
                'data' => json_encode($row),
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
        $packing_sheet_sudah_diclaim = $this->db
        ->select('cd.id_packing_sheet')
        ->from('tr_h3_md_claim_dealer as cd')
        ->group_start()
        ->where('cd.status !=', 'Rejected')
        ->where('cd.status !=', 'Canceled')
        ->group_end()
        ->get_compiled_select();

        $dealer_terdapat_packing_sheet = $this->db
        ->select('DISTINCT(pl.id_dealer)')
        ->from('tr_h3_md_packing_sheet as ps')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        // ->where("ps.id_packing_sheet NOT IN ({$packing_sheet_sudah_diclaim})", null, false)
        ->where('ps.id_packing_sheet !=', null)
        ->where('so.kategori_po !=', 'KPB')
        ->get_compiled_select();

        $this->db
        ->select('d.id_dealer')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.alamat')
        ->from('ms_dealer as d')
        ->where("d.id_dealer in ({$dealer_terdapat_packing_sheet})", null, false)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
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

    public function get_total_data(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
