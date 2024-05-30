<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nrfs extends CI_Controller
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
            $row['action'] = $this->load->view('additional/action_nrfs_datatable', [
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
        $nrfs_sudah_dibuat_po = $this->db
        ->select('po.dokumen_nrfs_id')
        ->from('tr_h3_dealer_purchase_order as po')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->where('po.po_type', 'URG')
        ->get_compiled_select();
        
        $this->db
        ->select('n.*')
        ->select('date_format(n.tgl_dokumen, "%d-%m-%Y") as tgl_dokumen')
        ->from('tr_dokumen_nrfs as n')
        ->where('n.id_dealer', $this->m_admin->cari_dealer())
        ->where("n.dokumen_nrfs_id not in ({$nrfs_sudah_dibuat_po})")
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('n.dokumen_nrfs_id', $search);
            $this->db->or_like('n.no_shiping_list', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('n.tgl_dokumen', 'ASC');
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
