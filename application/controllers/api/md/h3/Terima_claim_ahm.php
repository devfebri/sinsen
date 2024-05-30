<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Terima_claim_ahm extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_terima_claim_datatable', [
                'id_terima_claim_ahm' => $row['id_terima_claim_ahm']
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
        $this->db
        ->select('tca.id_terima_claim_ahm')
        ->select('date_format(tca.tanggal_surat_jawaban, "%d/%m/%Y") as tanggal_surat_jawaban')
        ->select('date_format(tca.created_at, "%d/%m/%Y") as created_at')
        ->select('tca.status')
        ->from('tr_h3_md_terima_claim_ahm as tca')
        ;

        if ($this->input->post('history') == 1) {
            $this->db->where('tca.status', 'Processed');
        }else{
            $this->db->where('tca.status !=', 'Processed');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('tca.id_claim', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tca.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
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
