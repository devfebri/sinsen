<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rak_outbound_form_pos extends CI_Controller
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
            $row['action'] = $this->load->view('additional/action_rak_outbound_form', [
                'data' => json_encode($row)
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
        $kode_dealer_ahm = $this->db->select('kode_dealer_ahm')
                                    ->from('ms_dealer')
                                    ->where('id_dealer',$this->m_admin->cari_dealer())
                                    ->get()->row();

        $this->db
        ->select('r.*')
        ->from('ms_lokasi_rak_bin as r')
        ->join('ms_dealer md','md.id_dealer=r.id_dealer')
        ->where('md.kode_dealer_ahm', $kode_dealer_ahm->kode_dealer_ahm)
        ->where('r.id_dealer !=', $this->m_admin->cari_dealer())
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('r.id_rak', $search);
            $this->db->or_like('r.id_gudang', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('r.id_rak', 'ASC');
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
