<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Good_receipt extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/action_index_good_receipt', [
                'id' => $each->id_good_receipt
            ], true);
            $data[] = $sub_arr;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('gr.id_good_receipt')
        ->select('date_format(gr.tanggal_receipt, "%d-%m-%Y") as tanggal_receipt')
        ->select('
        case 
            when gr.nomor_po is not null then gr.nomor_po
            else "-"
        end as nomor_po
        ', false)
        ->select('
        case 
            when gr.nomor_so is not null then gr.nomor_so
            else "-"
        end as nomor_so
        ', false)
        ->select('
        case 
            when supplier.nama_dealer is not null then supplier.nama_dealer
            else "-"
        end as nama_supplier', false)
        ->select('
        case
            when po.tanggal_order is not null then date_format(po.tanggal_order, "%d-%m-%Y")
            else "-"
        end as tanggal_po', false)
        ->select('gr.id_reference')
        ->from('tr_h3_dealer_good_receipt as gr')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = gr.nomor_po', 'left')
        ->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = gr.nomor_so', 'left')
        ->join('ms_dealer as supplier', 'supplier.id_dealer = so.id_dealer', 'left')
        ->where('gr.id_dealer', $this->m_admin->cari_dealer())
        ->where('gr.ref_type !=', 'packing_sheet_shipping_list')
        ;

        if($this->input->post('tipe') != null){
            if($this->input->post('tipe') == 'part_sales'){
                $this->db->where('gr.ref_type', 'part_sales_work_order');
            }else if($this->input->post('tipe') == 'work_order'){
                $this->db->where('gr.ref_type', 'part_sales_work_order');
                $this->db->where('so.id_work_order !=', null);
            }else{
                $this->db->where('gr.ref_type', $this->input->post('tipe'));
            }
        }
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('gr.id_good_receipt', $search);
            $this->db->or_like('gr.nomor_po', $search);
            $this->db->or_like('supplier.nama_dealer', $search);
            $this->db->or_like('gr.nomor_so', $search);
            $this->db->or_like('gr.id_reference', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('gr.created_at', 'DESC');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        return $this->db->get()->result();
    }

    public function recordsFiltered()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
