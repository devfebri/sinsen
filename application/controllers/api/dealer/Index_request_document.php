<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Index_request_document extends CI_Controller
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
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_index_request_document', [
                'id' => $row['id_booking']
            ], true);
            $data[] = $row;
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
        ->select('rd.id_booking')
        ->select('c.nama_customer')
        ->select('c.id_customer')
        ->select('c.alamat')
        ->select('c.no_hp')
        ->select('tk.deskripsi_ahm as tipe_motor')
        ->select('c.no_polisi')
        ->select('c.email')
        ->from('tr_h3_dealer_request_document as rd')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->where('rd.id_dealer', $this->m_admin->cari_dealer())
        
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->input->post('filter_request_document_date') != null){
            $this->db->group_start();
            $this->db->where("date_format(rd.created_at, '%Y-%m-%d') >= '{$this->input->post('start_date')}'");
            $this->db->where("date_format(rd.created_at, '%Y-%m-%d') <= '{$this->input->post('end_date')}'");
            $this->db->group_end();
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('rd.id_booking', $search);
            $this->db->or_like('rd.id_sa_form', $search);
            $this->db->or_like('c.id_customer', $search);
            // $this->db->or_like('kabupaten.kabupaten', $search);
            // $this->db->or_like('provinsi.provinsi', $search);
            $this->db->or_like('c.no_identitas', $search);
            $this->db->or_like('c.nama_customer', $search);
            $this->db->or_like('c.no_polisi', $search);
            $this->db->or_like('c.email', $search);
            $this->db->or_like('c.no_hp', $search);
            $this->db->group_end();
        }

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
