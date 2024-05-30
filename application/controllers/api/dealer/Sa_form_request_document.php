<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sa_form_request_document extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index(){
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_sa_form_request_document', [
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

    public function make_query(){
        $this->db
        ->select('sa.id_sa_form')
        ->select('sa.no_buku_claim_c2')
        ->select('sa.no_claim_c2')
        ->from('tr_h2_sa_form as sa')
        ->where('sa.id_sa_form !=', null)
        ->where('sa.id_dealer', $this->m_admin->cari_dealer())
        ;
    }



    public function make_datatables(){
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('sa.id_sa_form', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('sa.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) $this->db->limit($_POST['length'], $_POST['start']);
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();

    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }

}
