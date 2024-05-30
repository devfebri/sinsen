<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index(){
        $this->make_datatables(); $this->limit();
        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['aksi'] = $this->load->view('additional/action_purchase_order', [
                'po_id' => $row['po_id'],
            ], true);

            $row['status'] = $this->load->view('additional/status_purchase_order', [
                'status' => $row['status'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            
            $data[] = $row;
        }

        send_json(
            array(
                'draw' => intval($this->input->post('draw')), 
                'recordsFiltered' => $this->recordsFiltered(), 
                'recordsTotal' => $this->recordsTotal(),
                'data' => $data
            )
            );
    }

    public function make_query() {
        $quantity_unit = $this->db->select('sum(dpop.kuantitas)')
		->from('tr_h3_dealer_purchase_order_parts as dpop')
		->where('dpop.po_id = dpo.po_id')
		->group_by('dpop.po_id')
        ->get_compiled_select();

        $quantity_item = $this->db->select('count(dpop.id_part)')
		->from('tr_h3_dealer_purchase_order_parts as dpop')
		->where('dpop.po_id = dpo.po_id')
        ->get_compiled_select();

        $penerimaan = $this->db
        ->select('sum(pbi.qty_good)')
        ->from('tr_h3_dealer_penerimaan_barang as pb')
        ->join('tr_h3_dealer_penerimaan_barang_items pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
        ->join('tr_h3_md_packing_sheet as ps', 'pb.id_packing_sheet = ps.id_packing_sheet')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
        ->where('po.po_id = dpo.po_id')
        ->get_compiled_select();

        $penerimaan = $this->db
		->select('IFNULL(
			SUM(of.qty_fulfillment), 0
		) AS qty_order_fulfillment')
		->from('tr_h3_dealer_order_fulfillment as of')
		->where('of.po_id = dpo.po_id')
		->get_compiled_select();

        $this->db->select('dpo.*')
        ->select('
            case 
                when dpo.po_type = "FIX" then date_format( STR_TO_DATE(dpo.pesan_untuk_bulan, "%m") , "%b")
                else "-"
            end as periode
        ', false)
        ->select('date_format(dpo.tanggal_order, "%d-%m-%Y") as tanggal_order')
        ->select('upper(dpo.po_type) as po_type')
        ->select("
            case 
                when dpo.tanggal_selesai IS NULL then '---'
                else date_format(dpo.tanggal_selesai, '%d-%m-%Y')
            end as tanggal_selesai
        ")
        ->select('d.nama_dealer as dealer')
		->select("format(($quantity_unit), 0, 'id_ID') as unit_qty")
		->select("format(($quantity_item), 0, 'id_ID') as item_qty")
		->select("format(($penerimaan), 0, 'id_ID') as penerimaan")
        ->select("concat(format(((ifnull(($penerimaan), 0)/ifnull(($quantity_unit), 0)) * 100), 0, 'id_ID'), '%') as fulfillment_rate")
        ->select('c.nama_customer')
        ->from('tr_h3_dealer_purchase_order as dpo')
        ->join('ms_dealer as d', 'd.id_dealer=dpo.id_dealer')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = dpo.id_booking', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
        ->where('dpo.id_dealer', $this->m_admin->cari_dealer())
        ->where('dpo.po_rekap', 0);

    
    }

    public function make_datatables(){
        $this->make_query();

        if($this->input->post('filter_status') != null){
            $this->db->where('dpo.status', $this->input->post('filter_status'));
        }

        if($this->input->post('filter_tipe_po') != null){
            $this->db->where('dpo.po_type', $this->input->post('filter_tipe_po'));
        }

        if($this->input->post('filter_purchase_date') != null){
            $this->db->group_start();
            $this->db->where("dpo.tanggal_order >= '{$this->input->post('start_date')}'");
            $this->db->where("dpo.tanggal_order <= '{$this->input->post('end_date')}'");
            $this->db->group_end();
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('dpo.po_id', $search);
            $this->db->or_like('c.nama_customer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dpo.tanggal_order', 'desc');
        }
    }

    public function limit() {
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}