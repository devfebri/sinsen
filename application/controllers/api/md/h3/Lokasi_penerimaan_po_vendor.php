<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_penerimaan_po_vendor extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_lokasi_penerimaan_part_vendor', [
                'data' => json_encode($row)
            ], true);

            $row['view_stock'] = $this->load->view('additional/md/h3/action_view_stock_penerimaan_part_vendor', [
                'id' => $row['id']
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }
        
        send_json(
            array(
                'draw' => intval($this->input->post('draw')), 
                'recordsFiltered' => $this->get_filtered_data(), 
                'recordsTotal' => $this->get_total_data(), 
                'data' => $data
            )
        );
    }
    
    public function make_query() {
        $qty_maks_terpakai = $this->db
        ->select('sp.qty')
        ->from('tr_stok_part as sp')
        ->where('sp.id_lokasi_rak = lr.id')
        ->where('sp.id_part', $this->input->post('id_part'))
        ->get_compiled_select();

        $qty_maks_terpakai_penerimaan_lain = $this->db
		->select('SUM(ppvp_sq.qty_diterima)')
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp_sq')
		->join('tr_h3_md_penerimaan_po_vendor as ppv_sq', 'ppv_sq.id_penerimaan_po_vendor = ppvp_sq.id_penerimaan_po_vendor')
		->where('ppv_sq.status !=', 'Processed')
		->where('ppvp_sq.id_part', $this->input->post('id_part'))
		->get_compiled_select();

        $this->db
        ->select('lr.id')
        ->select('lr.kode_lokasi_rak')
        ->select('lr.deskripsi')
        ->select('lr.kapasitas')
        ->select('lr.kapasitas_terpakai')
        ->select('(lr.kapasitas - lr.kapasitas_terpakai) as kapasitas_tersedia')
        // ->select("IFNULL(lrp.qty_maks, 0) - ( IFNULL( ({$qty_maks_terpakai}), 0) + IFNULL(({$qty_maks_terpakai_penerimaan_lain}), 0) ) as qty_maks")
        ->select('g.nama_gudang')
        ->from('ms_h3_md_lokasi_rak as lr')
        ->join('ms_h3_md_lokasi_rak_parts as lrp', "(lrp.id_lokasi_rak = lr.id and lrp.id_part = '{$this->input->post('id_part')}')", 'left')
        ->join('ms_h3_md_gudang as g', 'g.id = lr.id_gudang')
        ->where('(lr.kapasitas - lr.kapasitas_terpakai) > 0', null, false)
        ->where('lr.active', 1)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('lr.kode_lokasi_rak', $search);
            $this->db->or_like('lr.deskripsi', $search);
            $this->db->or_like('g.nama_gudang', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('lr.kode_lokasi_rak', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
