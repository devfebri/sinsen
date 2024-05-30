<?php
defined('BASEPATH') or exit('No direct script access allowed');

class View_kode_part_po_logistik extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('polpd.id_part')
        ->select('p.nama_part')
        ->select('pr_nrfs.request_id')
        ->select('date_format(pr_nrfs.tgl_request, "%d/%m/%Y") as tgl_request')
        ->select('nrfs_part.qty_part')
        ->select('polpd.qty_supply')
        ->select('polpd.qty_book')
        ->select('polpd.qty_po_ahm')
        ->from('tr_h3_md_po_logistik_parts_detail as polpd')
        ->join('ms_part as p', 'p.id_part = polpd.id_part')
        ->join('tr_dokumen_nrfs as nrfs', '(nrfs.dokumen_nrfs_id = polpd.dokumen_nrfs_id)')
        ->join('tr_dokumen_nrfs_part as nrfs_part', '(nrfs_part.dokumen_nrfs_id = nrfs.dokumen_nrfs_id and nrfs_part.id_part = polpd.id_part)')
        ->join('tr_part_request_nrfs as pr_nrfs', 'pr_nrfs.dokumen_nrfs_id = nrfs.dokumen_nrfs_id', 'left')
        ->where('polpd.id_po_logistik', $this->input->post('id_po_logistik'))
        ->where('polpd.id_part', $this->input->post('id_part'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('polpd.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }
        
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('polpd.id_part', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
