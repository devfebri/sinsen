<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order_create_submit_good_receipt extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_purchase_good_receipt', [
                'data' => json_encode($row)
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['customer'] = $row['id_booking'] == "" ? "-" : $this->db->get_where('ms_customer_h23',array('id_customer'=>$row['id_customer']))->row()->nama_customer; 
            $data[] = $row;
            $index++;
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
        // $kuantitas_po = $this->db
        // ->select('SUM(pop.kuantitas) as kuantitas', false)
        // ->from('tr_h3_dealer_purchase_order_parts as pop')
        // ->where('pop.po_id_int = po.id', null, false)
        // ->get_compiled_select();

        // $kuantitas_sudah_terpenuhi = $this->db
        // ->select('SUM(of.qty_fulfillment) as qty_fulfillment', false)
        // ->from('tr_h3_dealer_order_fulfillment as of')
        // ->where('of.po_id = po.po_id', null, false)
        // ->get_compiled_select();

        $this->db
        ->select('po.po_id')
        ->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
        ->select('po.id_booking')
        ->select('rd.id_customer')
        // ->select('
        //     case 
        //         when rd.id_data_pemesan = 0 then c.nama_customer
        //         else prh.nama
        //     end as nama_customer
        // ', false)
        //->select('rd.id_sa_form as id_sa_form')
        //->select('wo.id_work_order as id_work_order')
        // ->select("IFNULL( ({$kuantitas_po}), 0 ) as kuantitas_po", false)
        // ->select("IFNULL( ({$kuantitas_sudah_terpenuhi}), 0 ) as kuantitas_sudah_terpenuhi", false)
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking', 'left')
        //->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = rd.id_sa_form', 'left')
       // ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
        //->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->group_start()
        ->where('po.order_to', 0)
        ->or_where('po.order_to', null)
        ->group_end()
        // ->group_start()
        // ->where('po.status', 'Submitted')
        // ->or_where('po.status', 'Processed by MD')
        // ->group_end()
        ->group_start()
            ->group_start()
            ->where('po.status', 'Submitted')
            ->or_where('po.status', 'Processed by MD')
            ->group_end()
            ->or_group_start()
            ->where('po.created_by_md', 1)
            ->or_where('po.status_md', 'Closed')
            ->group_end()
        ->group_end()
        ->group_start()
            ->where('po.is_ev', null)
            ->or_where('po.is_ev', 0)
            ->or_where('po.is_ev', '')
        ->group_end()
        // ->where("IFNULL( ({$kuantitas_po}), 0 ) != IFNULL( ({$kuantitas_sudah_terpenuhi}), 0 )", null, false)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();
        
        $search = $this->input->post('search');
        $search2 = $this->input->post('search2');

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.po_id', $search);
            $this->db->group_end();
        }

        if ($search2 != '') {
            $this->db->group_start();
            $this->db->like('po.id_booking', $search2);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'DESC');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
