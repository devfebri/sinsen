<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Referensi_good_receipt extends CI_Controller
{

    private $id_dealer;

    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->helper('query_execution_time');

        $this->id_dealer = $this->m_admin->cari_dealer();
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();
        
        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_referensi_good_receipt', [
                'data' => json_encode($row)
            ], true);
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'meta' => query_execution_time()
        ]);
    }

    public function make_query()
    {
        $nsc_sudah_terpakai = $this->db
        ->select('gr.id_reference')
        ->from('tr_h3_dealer_good_receipt as gr')
        ->where('gr.ref_type', 'part_sales_work_order')
        ->where('gr.id_dealer', $this->id_dealer)
        ->get_compiled_select();

        $this->db
        ->select('nsc.no_nsc as id_referensi')
        ->select('date_format(nsc.created_at, "%d-%m-%Y") as tanggal')
        ->select('"nsc" as tipe_referensi')
        ->select('so.nomor_so')
        ->select('po.po_id as nomor_po')
        ->select('rd.id_sa_form as nomor_sa_form')
        ->select('wo.id_work_order as nomor_wo')
        ->from('tr_h23_nsc as nsc')
        ->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = nsc.id_referensi')
        ->join('tr_h3_dealer_purchase_order as po', 'po.id_booking = so.booking_id_reference_int', 'left')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id = so.booking_id_reference_int', 'left')
        ->join('tr_h2_wo_dealer as wo', 'rd.id_sa_form = wo.id_sa_form', 'left')
        ->where('nsc.id_dealer_pembeli', $this->id_dealer)
        ->where("nsc.no_nsc not in ({$nsc_sudah_terpakai}) and nsc.status is null")
        ;

        $tipe_referensi = $this->input->post('tipe_referensi');
        if($tipe_referensi == 'part_sales_work_order'){
            $this->db->where('nsc.referensi', 'sales');
        }else{
            $this->db->where('1 = 0', null, false);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('nsc.no_nsc', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('nsc.created_at', 'desc');
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