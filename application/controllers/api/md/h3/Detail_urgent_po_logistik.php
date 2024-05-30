<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Detail_urgent_po_logistik extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
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
        ->select('detail_logistik.referensi')
        ->select('c.tgl_checker')
        ->select('scan_barcode.tipe_motor')
        ->select('ptm.deskripsi as deskripsi_unit')
        ->select('warna.warna as deskripsi_warna')
        ->select('scan_barcode.no_mesin')
        ->select('scan_barcode.no_rangka')
        ->select('detail_logistik.kuantitas')
        ->from('tr_h3_md_po_logistik_parts_detail as detail_logistik')
        ->join('tr_checker as c', 'c.id_checker = detail_logistik.referensi')
        ->join('tr_scan_barcode as scan_barcode', 'scan_barcode.no_mesin = c.no_mesin')
		->join('ms_ptm as ptm', 'ptm.tipe_marketing = scan_barcode.tipe_motor', 'left')
		->join('ms_warna as warna', 'warna.id_warna = scan_barcode.warna', 'left')
        ->where('detail_logistik.id_po_logistik', $this->input->post('po_id'))
        ->where('detail_logistik.id_part', $this->input->post('id_part'));
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('detail_logistik.referensi', $search);
            $this->db->group_end();
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