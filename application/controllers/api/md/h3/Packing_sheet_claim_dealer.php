<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet_claim_dealer extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_packing_sheet_claim_dealer', [
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
        $packing_sheet_sudah_diclaim = $this->db
        ->select('cd.id_packing_sheet')
        ->from('tr_h3_md_claim_dealer as cd')
        ->group_start()
        ->where('cd.status !=', 'Rejected')
        ->where('cd.status !=', 'Canceled')
        ->group_end()
        ->get_compiled_select();

        $jumlah_koli = $this->db
        ->distinct()
        ->select('count(no_dus)')
        ->from('tr_h3_md_scan_picking_list_parts as splp')
        ->where('splp.id_picking_list = ps.id_picking_list')
        ->get_compiled_select();

        $this->db->select('ps.*')
        ->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
        ->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
        ->select('md.nama_dealer as nama_customer')
        ->select('do.id_do_sales_order as nomor_do')
        ->select("($jumlah_koli) as jumlah_koli")
        ->select('md.nama_dealer')
        ->select('md.alamat')
        ->select('md.id_dealer')
        ->from("tr_h3_md_packing_sheet as ps")
        ->join("tr_h3_md_picking_list as pl", 'ps.id_picking_list = pl.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
        ->join('ms_dealer as md', 'md.id_dealer = po.id_dealer')
        ->where('ps.id_packing_sheet !=', null)
        ->where('so.kategori_po !=', 'KPB')
        // ->where("ps.id_packing_sheet NOT IN ({$packing_sheet_sudah_diclaim})", null, false)
        ;

        if($this->input->post('id_dealer') != null){
            $this->db->where('md.id_dealer', $this->input->post('id_dealer'));
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.id_packing_sheet', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.id_packing_sheet', 'ASC');
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
