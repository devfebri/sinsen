<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order_bundling_rekap_po_bundling extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_purchase_order_bundling_rekap_po_bundling', [
                'data' => json_encode($row),
                'no_po_aksesoris' => $row['no_po_aksesoris']
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
        $this->db
        ->select('po.no_po_aksesoris')
        ->select('date_format(po.tgl_po,"%d-%m-%Y") as tgl_po')
        ->select('po.id_paket_bundling')
        ->select('po.qty_paket')
        ->select('po.keterangan')
        ->select('po.no_surat_jalan')
        ->select('po.status_po')
        ->select('so.id_sales_order')
        ->from('tr_po_aksesoris as po')
		->join('tr_h3_md_sales_order as so', '(so.referensi_po_bundling = po.no_po_aksesoris AND so.status != "Canceled")', 'left')
        ->where('po.active', 1)
        ->where('po.is_rekap', 0)
        ->group_start()
        ->where('po.status_po !=', 'input')
        ->where('po.status_po !=', 'rejected')
        ->where('so.id_sales_order is null', null, false)
        ->group_end();
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.no_po_aksesoris', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'desc');
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
