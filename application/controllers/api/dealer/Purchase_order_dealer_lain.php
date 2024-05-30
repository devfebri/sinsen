<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Purchase_order_dealer_lain extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $output = array(
            "draw" => intval($this->input->post('draw')), 
            "recordsFiltered" => $this->get_filtered_data(), 
            'recordsTotal' => $this->all_records(),
            "data" => $this->process_data()
        );
        echo json_encode($output);
    }

    public function process_data(){
        $fetch_data = $this->get_result();
        $data = array();
        $akumulasi = 0;
        $index = 1;
        foreach ($fetch_data as $each) {
            $sub_array = (array) $each;
            $sub_array['aksi'] = $this->load->view('additional/action_purchase_order_dealer_lain', [
                'po_id' => $each->po_id,
            ], true);

            $sub_array['action_modal'] = $this->load->view('additional/action_modal_purchase_order_dealer_lain', [
                'data' => json_encode($each)
            ], true);
            $data[] = $sub_array;
            $index++;
        }
        return $data;
    }

    public function query() {
        /* 
        $quantity_unit = $quantity_item =0;
       
        
        $quantity_unit = $this->db->select('sum(dpop.kuantitas)')
		->from('tr_h3_dealer_purchase_order_parts as dpop')
		->where('dpop.po_id = dpo.po_id')
		->group_by('dpop.po_id')
        ->get_compiled_select();

        $quantity_item = $this->db->select('count(dpop.id_part)')
		->from('tr_h3_dealer_purchase_order_parts as dpop')
		->where('dpop.po_id = dpo.po_id')
        ->get_compiled_select();
 */
        $reference_terbuat_so = $this->db
        ->select('so.booking_id_reference')
        ->from('tr_h3_dealer_sales_order as so')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->where('so.booking_id_reference !=', null)
        ->get_compiled_select();

        //20 Feb 2023: perlu ubah koding
        $this->db->select('dpo.*')
        ->select('date_format(dpo.tanggal_order, "%b-%d") as periode')
        ->select('date_format(dpo.tanggal_order, "%d-%m-%Y") as tanggal_order')
        ->select('upper(dpo.po_type) as po_type')
        ->select("
            case 
                when dpo.tanggal_selesai IS NULL then '---'
                else date_format('dpo.tanggal_selesai', '%d-%m-%Y')
            end as tanggal_selesai
        ")
        ->select('d.nama_dealer as dealer')
        ->select('d.no_telp')
        ->select('d.alamat')
		->select("0 as unit_qty")
		->select("0 as items_qty")
        ->from('tr_h3_dealer_purchase_order as dpo')
        ->join('ms_dealer as d', 'd.id_dealer=dpo.id_dealer')
        ->where('dpo.order_to', $this->m_admin->cari_dealer())
        ->where('dpo.po_type', 'hlo')
        ->where("dpo.id_booking not in ({$reference_terbuat_so})")
        ->group_start()
        ->where('dpo.status !=', 'Rejected')
        ->where('dpo.status', 'Submitted')
        ->group_end()
        ;

        if($this->input->post('filter_status') != null){
            $this->db->where('dpo.status', $this->input->post('filter_status'));
        }

        $search_po_id = $this->input->post('cari_po_id');

        if($search_po_id != ''){
            $this->db->group_start();
            $this->db->like('dpo.po_id', $search_po_id);
            $this->db->group_end();
        }

        // $search = $this->input->post('search')['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('dpo.po_id', $search);
        //     $this->db->group_end();
        // }
        $this->db->order_by('dpo.po_id', 'DESC');
    }

    public function get_result() {
        $this->query();
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function all_records(){
        $this->query();
        return $this->db->count_all_results();
    }
}