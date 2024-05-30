<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_fulfillment extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_index_order_fulfillment', [
                'id' => $row['po_id'],
            ], true);

            // $row['indikator'] = $this->load->view('additional/status_indikator_order_fulfillment', [
            //     'data' => $row,
            // ], true);
            $data[] = $row;
        }

        $output = array(
            "draw" => intval($this->input->post('draw')), 
            "recordsFiltered" => $this->recordsFiltered(), 
            'recordsTotal' => $this->recordsTotal(),
            "data" => $data
        );
        send_json($output);
    }

    public function make_query() {
        // $quantity_terpenuhi = $this->db
		// ->select('IFNULL(
		// 	SUM(of.qty_fulfillment), 0
		// ) AS qty_order_fulfillment')
		// ->from('tr_h3_dealer_order_fulfillment as of')
		// ->where('of.po_id = po.po_id')
        // ->get_compiled_select();

        // $qty_order = $this->db
        // ->select('sum(kuantitas)')
        // ->from('tr_h3_dealer_purchase_order_parts as pop')
        // ->where('pop.po_id = po.po_id')
        // ->get_compiled_select();

        // $eta_terlama = $this->db
        // ->select('pop.eta_terlama')
        // ->from('tr_h3_dealer_purchase_order_parts as pop')
        // ->where('pop.po_id = po.po_id')
        // ->where('pop.eta_terlama !=', null)
        // ->limit(1)
        // ->order_by('pop.eta_terlama', 'desc')
        // ->get_compiled_select();

        $this->db
        ->select('po.po_id')
        ->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_po')
        // ->select("({$qty_order}) as qty_order")
        ->select("'-' as qty_order")
        // ->select("date_format(({$eta_terlama}), '%d-%m-%Y') as eta_terlama")
        ->select("
            (case
                when rd.id_data_pemesan IS NULL OR rd.id_data_pemesan = 0 then c.nama_customer
                else prh.nama
            end) as nama_customer
        ")
        ->select("
            (case
                when rd.id_data_pemesan IS NULL OR rd.id_data_pemesan = 0 then c.no_hp
                else prh.no_hp
            end) as kontak_customer
        ")
        // ->select("ifnull(({$quantity_terpenuhi}), 0) as qty_terpenuhi")
        // ->select("({$qty_order}) - ifnull(({$quantity_terpenuhi}), 0) as qty_belum_terpenuhi")
        // ->select("
        // concat(
        //     format(
        //     ( ifnull(({$quantity_terpenuhi}), 0) / ({$qty_order}) ) * 100 
        //     , 0),
        //     '%'
        // )
        // as fulfillment_rate")
        // ->select("
        //     case 
        //         when po.penyerahan_customer = 1 then 'Closed'
        //         else 'Open'
        //     end as status
        // ")
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->where('po.po_type', 'hlo')
        ->where('po.id_booking !=', null)
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->where('po.status !=', 'Rejected')
        ->group_start()
		->where('po.order_to', null)
		->or_where('po.order_to', 0)
        ->group_end()
        ->where_in('po.status', ['Submitted', 'Processed by MD'])
        ;
    }

    public function make_datatables() {
        $this->make_query();

        if($this->input->post('filter_order_fulfillment_date') != null){
            $this->db->group_start();
            $this->db->where("po.tanggal_order >= '{$this->input->post('start_date')}'");
            $this->db->where("po.tanggal_order <= '{$this->input->post('end_date')}'");
            $this->db->group_end();
        }

        if($this->input->post('filter_status') != null){
            if($this->input->post('filter_status') == 'Open'){
                $this->db->where('po.penyerahan_customer', 0);
            }else{
                $this->db->where('po.penyerahan_customer', 1);
            }
        }

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.po_id', $search);
            $this->db->or_where("
            case
                when rd.id_data_pemesan = 0 then c.no_hp LIKE '%{$search}%'
                else prh.no_hp LIKE '%{$search}%'
            end
            ", null, false);
            $this->db->or_where("
            case
                when rd.id_data_pemesan = 0 then c.nama_customer LIKE '%{$search}%'
                else prh.nama LIKE '%{$search}%'
            end
            ", null, false);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.po_id', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}