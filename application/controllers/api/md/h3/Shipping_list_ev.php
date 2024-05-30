<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Shipping_list_ev extends CI_Controller
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
        ->select('rem.no_shipping_list ')
        ->select('DATE_FORMAT(rem.tgl_shipping_list,"%d-%m-%Y %h:%i:%s") as tgl_shipping_list')
        ->select('rem.kode_dealer_md')
        ->select('rem.box_id')
        ->select('rem.packing_id')
        ->select('rem.carton_id')
        ->select('rem.acc_tipe')
        ->select('rem.part_id')
        ->select('rem.serial_number')
        ->select('"API 2" as data_from ')
        ->select('DATE_FORMAT(tr.created_at_penerimaan_md,"%d-%m-%Y") as created_at_penerimaan_md')
        ->select('DATE_FORMAT(rem.created_at,"%d-%m-%Y %h:%i:%s") as tgl_dicreate_ahm')
        ->select('tr.no_penerimaan_barang_md')
        ->from('tr_shipping_list_ev_accrem as rem')
        ->join('tr_h3_serial_ev_tracking as tr', 'rem.part_id=tr.id_part and rem.serial_number = tr.serial_number and tr.no_shipping_list and rem.no_shipping_list','LEFT');
    }

    public function make_datatables()
    {
        $this->make_query();
        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('rem.box_id', $search);
            $this->db->or_like('rem.packing_id', $search);
            $this->db->or_like('rem.carton_id', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('rem.created_at', 'ASC');
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