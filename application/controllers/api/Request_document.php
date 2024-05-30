<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Request_document extends CI_Controller
{
    public $table = "tr_h3_dealer_request_document";

    public function all()
    {
        $fetch_data = $this->make_datatables();
        $data = array();
        foreach ($fetch_data as $rs) {
            $sub_array = (array) $rs;
            $row = json_encode($rs);
            $sub_array['action'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRequestDocument(' . $row . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]) ,
            "recordsFiltered" => $this->get_filtered_data() ,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function make_query()
    {
        $this->db
        ->select('rd.*')
        ->select('c.nama_customer')
        ->from('tr_h3_dealer_request_document as rd')
        ->join('ms_customer_h23 as c', 'c.id_customer=rd.id_customer')
        ;

        if($this->input->post('id_customer') != null){
            $this->db->where('c.id_customer', $this->input->post('id_customer'));
        }

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('rd.id_booking', $search);
            $this->db->or_like('rd.id_customer', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('rd.created_at', 'desc');
        }
    }

    public function make_datatables()
    {
        $this->make_query();
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();

        return $query->num_rows();
    }
}
