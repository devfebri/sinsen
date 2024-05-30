<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_pengantar_claim_c3 extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_surat_pengantar_claim_c3', [
                'id_surat_pengantar' => $row['id_surat_pengantar']
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
        ->select('spcd.id_surat_pengantar')
        ->select('date_format(spcd.tanggal, "%d/%m/%Y") as tanggal')
        ->select('spcd.id_jawaban_claim_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->from('tr_h3_md_surat_pengantar_claim_c3_dealer as spcd')
        // ->join('tr_h3_md_jawaban_claim_dealer as jcd', 'jcd.id_jawaban_claim_dealer = spcd.id_jawaban_claim_dealer')
        ->join('ms_dealer as d', 'd.id_dealer = spcd.id_dealer')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('spcd.id_surat_pengantar', $search);
            $this->db->group_end();
        }
        
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('spcd.created_at', 'desc');
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
