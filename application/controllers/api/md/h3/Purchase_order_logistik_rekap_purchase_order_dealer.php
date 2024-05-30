<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order_logistik_rekap_purchase_order_dealer extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_purchase_order_logistik_rekap_purchase_order_dealer', [
                'data' => json_encode($row),
                'po_id' => $row['po_id']
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
        $po_dengan_pemenuhan = $this->db
        ->select('DISTINCT(ppd.po_id) as po_id')
        ->from('tr_h3_md_pemenuhan_po_dari_dealer as ppd')
        ->where('ppd.qty_pemenuhan >', 0)
        ->get_compiled_select();

        $this->db
        ->select('po.po_id')
        ->select('date_format(po.tanggal_order, "%d/%m/%Y") as tanggal_order')
        ->from('tr_h3_dealer_purchase_order as po')
        ->where('po.id_dealer', $this->input->post('id_dealer'))
        ->where('po.status', 'Processed By MD')
        ->where('po.created_by_md', 0)
        ->where("po.po_id in ({$po_dengan_pemenuhan})", null, false)
        // ->where('po.po_logistik', 0)
        ;

        if($this->input->post('tipe_po') != null){
            $this->db->where('po.po_type', $this->input->post('tipe_po'));
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.po_id', $search);
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
