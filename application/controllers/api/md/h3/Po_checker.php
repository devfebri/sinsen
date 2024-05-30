<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Po_checker extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_index_po_checker_datatable', [
                'id_checker' => $row['id_checker']
            ], true);

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
        $jumlah_kode_part = $this->db
        ->select('COUNT( DISTINCT(cd.id_part) ) as jumlah_part', false)
        ->from('tr_checker_detail as cd')
        ->where('cd.id_checker = c.id_checker', null, false)
        ->get_compiled_select();

        $jumlah_order = $this->db
        ->select('SUM( cd.qty_order ) as qty_order', false)
        ->from('tr_checker_detail as cd')
        ->where('cd.id_checker = c.id_checker', null, false)
        ->get_compiled_select();

        $this->db
        ->select('c.id_checker')
        ->select('scan_barcode.no_shipping_list')
        ->select('c.tgl_checker')
        ->select('c.no_mesin')
        ->select('c.keterangan')
        ->select('scan_barcode.no_rangka')
        ->select('scan_barcode.tipe_motor')
        ->select('c.status_checker')
        ->select("IFNULL(({$jumlah_kode_part}), 0) as jumlah_kode_part", false)
        ->select("IFNULL(({$jumlah_order}), 0) as jumlah_order", false)
        ->from('tr_checker as c')
        ->join('tr_scan_barcode as scan_barcode', 'scan_barcode.no_mesin = c.no_mesin')
        ->group_start()
        ->where('c.sumber_kerusakan', 'Warehouse')
        ->or_where('c.sumber_kerusakan', 'Ekspedisi')
        ->group_end()
        ->where("IFNULL(({$jumlah_order}), 0) > 0", null, false)
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('c.status_checker', 'close');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('c.status_checker', null);
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('no_checker_filter') != null) {
            $this->db->like('c.id_checker', trim($this->input->post('no_checker_filter')));
        }

        if ($this->input->post('no_shipping_list_filter') != null) {
            $this->db->like('scan_barcode.no_shipping_list', trim($this->input->post('no_shipping_list_filter')));
        }

        if ($this->input->post('no_mesin_filter') != null) {
            $this->db->like('c.no_mesin', trim($this->input->post('no_mesin_filter')));
        }

        if ($this->input->post('no_rangka_filter') != null) {
            $this->db->like('scan_barcode.no_rangka', trim($this->input->post('no_rangka_filter')));
        }

        if ($this->input->post('kode_tipe_unit_filter') != null) {
            $this->db->like('scan_barcode.tipe_motor', trim($this->input->post('kode_tipe_unit_filter')));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('c.created_at', 'desc');
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
