<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Booking_reference_sales_order extends CI_Controller
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
            $row['action'] = $this->load->view('additional/action_booking_reference_sales_order', [
                'data' => json_encode($row)
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
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
        $kuantitas = $this->db
        ->select('SUM(rdp.kuantitas) as kuantitas', false)
        ->from('tr_h3_dealer_request_document_parts as rdp')
        ->where('rdp.id_booking = rd.id_booking', null, false)
        ->get_compiled_select();

        $kuantitas_diterima = $this->db
        ->select('SUM(sop.kuantitas-sop.kuantitas_return) as kuantitas', false)
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->where('so.status !=', 'Canceled')
        ->where('so.booking_id_reference IS NOT NULL', null, false)
        ->where('so.booking_id_reference = po.id_booking', null, false)
        ->where('so.id_dealer = rd.id_dealer', null, false)
        ->get_compiled_select();

        $this->db
        ->select('rd.*')
        ->select('c.id_customer_int')
        ->select('c.nama_customer')
        ->select('c.alamat')
        ->select('c.no_hp')
        ->select('c.no_mesin')
        ->select('c.no_rangka')
        //->select("IFNULL(({$kuantitas}), 0) as kuantitas", false)
        //->select("IFNULL(({$kuantitas_diterima}), 0) as kuantitas_diterima", false)
        ->from('tr_h3_dealer_request_document as rd')
        ->join('ms_customer_h23 as c', 'c.id_customer=rd.id_customer')
        ->join('tr_h3_dealer_purchase_order as po', '(po.id_booking = rd.id_booking AND po.po_type = "HLO")')
        ->where('rd.id_dealer', $this->m_admin->cari_dealer())
        ->where("IFNULL(({$kuantitas}), 0) > IFNULL(({$kuantitas_diterima}), 0)")
        ;
    }

    public function make_datatables()
    {
        $this->make_query();
        
        $search = $this->input->post('search');
        $search2 = $this->input->post('search2');

        if($search != ''){
            $this->db->group_start();
            $this->db->like('rd.id_booking', $search);
            $this->db->group_end();
        }

        if ($search2 != '') {
            $this->db->group_start();
            $this->db->like('c.nama_customer', $search2);
            $this->db->group_end();
        }

        if($this->input->post('id_customer') != null){
            $this->db->where('c.id_customer', $this->input->post('id_customer'));
        }

        // $search = trim($this->input->post('search')['value']);
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('rd.id_booking', $search);
        //     $this->db->or_like('rd.id_customer', $search);
        //     $this->db->or_like('c.nama_customer', $search);
        //     $this->db->group_end();
        // }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('rd.created_at', 'desc');
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
        return $this->db->get()->num_rows();
    }
}