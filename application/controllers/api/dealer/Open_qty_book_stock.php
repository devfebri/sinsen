<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Open_qty_book_stock extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_view_sales_order', [
                'row' => $row
            ], true);
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
        $sa_form = $this->db 
        ->select('sa_form.id_sa_form as referensi')
        ->select('"SA Form" as tipe_referensi')
        ->select('sa_form_parts.qty as kuantitas')
        ->select('sa_form.created_at')
        ->select('sa_form.status_form as status')
        ->from('tr_h2_sa_form as sa_form')
        ->join('tr_h2_sa_form_parts as sa_form_parts', 'sa_form_parts.id_sa_form = sa_form.id_sa_form')
        ->where('sa_form.status_form', 'open')
        ->where('sa_form.id_dealer', $this->m_admin->cari_dealer())
        ->where('sa_form_parts.id_part', $this->input->post('id_part_open_qty_book_stock'))
        ->get_compiled_select();

        $work_order = $this->db 
        ->select('wo.id_work_order as referensi')
        ->select('"Work Order" as tipe_referensi')
        ->select('wop.qty as kuantitas')
        ->select('wo.created_at')
        ->select('wo.status')
        ->from('tr_h2_wo_dealer_parts as wop')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = wop.id_work_order')
        ->group_start()
        ->where('wo.status !=', 'cancel')
        ->where('wo.status !=', 'canceled')
        ->where('wo.status !=', 'closed')
        ->group_end()
        ->where('wop.nomor_so is null', null, false)
        ->where('wop.pekerjaan_batal', 0)
        ->where('wo.id_dealer', $this->m_admin->cari_dealer())
        ->where('wop.id_part', $this->input->post('id_part_open_qty_book_stock'))
        ->get_compiled_select();

        $sales_order = $this->db
        ->select('so.nomor_so as referensi')
        ->select('"Sales Order" as tipe_referensi')
        ->select('(sop.kuantitas - sop.kuantitas_return) as kuantitas')
        ->select('so.created_at')
        ->select('so.status')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'so.nomor_so = sop.nomor_so')
        ->where('so.status !=', 'Closed')
        ->where('so.status !=', 'Canceled')
        ->where('so.id_inbound_form_for_parts_return', null)
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->where('sop.id_part', $this->input->post('id_part_open_qty_book_stock'))
        ->having('kuantitas >', 0)
        ->get_compiled_select();

        $outbound_fullfillment = $this->db
        ->select('off.id_outbound_form_for_fulfillment as referensi')
        ->select('"Outbound Fulfillment" as tipe_referensi')
        ->select('offp.kuantitas')
        ->select('off.created_at')
        ->select('off.status')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as off')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment_parts as offp', 'off.id_outbound_form_for_fulfillment = offp.id_outbound_form_for_fulfillment')
        ->where('off.status', 'Open')
        ->where('off.id_dealer', $this->m_admin->cari_dealer())
        ->where('offp.id_part', $this->input->post('id_part_open_qty_book_stock'))
        ->get_compiled_select();

        $hotline_sudah_dibuatkan_sales_order = $this->db
        ->select('SUM(sop.kuantitas) as kuantitas')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->where('so.booking_id_reference = po.id_booking')
        ->where('sop.id_part = of.id_part')
        ->where('so.status !=', 'Canceled')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->get_compiled_select();

        $purchase_order_hotline = $this->db
        ->select('po.po_id as referensi')
        ->select('"Purchase Order Hotline" as tipe_referensi')
        ->select("(of.qty_fulfillment - IFNULL( ({$hotline_sudah_dibuatkan_sales_order}), 0) ) as kuantitas")
        ->select('po.created_at')
        ->select('po.status')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h3_dealer_order_fulfillment as of', 'of.po_id = po.po_id')
        ->where('wo.id_work_order_int IS NULL', null, false)
        ->where('po.po_rekap', 0)
        ->where('po.po_type', 'HLO')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->where('of.id_part', $this->input->post('id_part_open_qty_book_stock'))
        ->having('kuantitas > 0')
        ->get_compiled_select();

        $this->db
        ->from("
            (
                ({$sales_order})
                UNION
                ({$outbound_fullfillment})
                UNION
                ({$purchase_order_hotline})
                UNION
                ({$sa_form})
                UNION
                ({$work_order})
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
            $this->db->order_by('created_at', 'asc');
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
        return $this->db->get()->num_rows();
    }
}
