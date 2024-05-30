<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Open_view_qty_booking extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $data,
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
        ]);
    }
    
    public function make_query() {
        $booking_dari_do = $this->db
        ->select('do.id_do_sales_order as referensi')
        ->select('do.tanggal')
        ->select('dop.qty_supply as kuantitas')
        ->select('do.status')
        ->from('tr_h3_md_do_sales_order_parts as dop')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
        ->where('dop.id_part', $this->input->post('id_part_open_view_qty_booking'))
        ->where_in('do.status', [
            'On Process', 'Approved', 'Picking List', 'Proses Scan', 'Closed Scan'
        ])
        ->where('dop.qty_supply > 0', null, false)
        ->get_compiled_select();

        $booking_dari_po_dealer = $this->db
        ->select('ppdd.po_id as referensi')
        ->select('po.tanggal_order as tanggal')
        ->select('(ppdd.qty_so + ppdd.qty_pemenuhan) as kuantitas')
        ->select('po.status')
        ->from('tr_h3_md_pemenuhan_po_dari_dealer as ppdd')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ppdd.po_id')
        ->where('ppdd.id_part', $this->input->post('id_part_open_view_qty_booking'))
        ->having('kuantitas > 0')
        ->get_compiled_select();

        $this->db
        ->from("
            (
                ({$booking_dari_do})
                UNION
                ({$booking_dari_po_dealer})
            ) as booking_data
        ");
    }

    public function make_datatables() {
        $this->make_query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tanggal', 'desc');
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
