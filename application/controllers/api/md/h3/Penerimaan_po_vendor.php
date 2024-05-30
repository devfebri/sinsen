<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_po_vendor extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_penerimaan_po_vendor_datatable', [
                'id_penerimaan_po_vendor' => $row['id_penerimaan_po_vendor'],
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
        ->select('date_format(ppv.tanggal, "%d-%m-%Y") as tanggal')
        ->select('ppv.id_penerimaan_po_vendor')
        ->select('ppv.id_po_vendor')
        ->select('v.vendor_name')
        ->select('date_format(ppv.tgl_surat_jalan_ekspedisi, "%d-%m-%Y") as tgl_surat_jalan_ekspedisi')
        ->select('ppv.surat_jalan_ekspedisi')
        ->select('e.nama_ekspedisi')
        ->select('ppv.no_plat')
        ->select('ppv.status')
        ->from('tr_h3_md_penerimaan_po_vendor as ppv')
        ->join('tr_h3_md_po_vendor as pv', 'pv.id_po_vendor = ppv.id_po_vendor')
        ->join('ms_vendor as v', 'v.id_vendor = pv.id_vendor')
        ->join('ms_h3_md_ekspedisi as e', 'e.id = ppv.id_ekspedisi')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ppv.id_penerimaan_po_vendor', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ppv.created_at', 'desc');
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
