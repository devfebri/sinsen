<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Qty_penerimaan_pemenuhan_po_dealer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index;
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
        ->select('pb.no_penerimaan_barang')
        ->select('pb.tanggal_penerimaan')
        ->select('pbi.no_po')
        ->select('pbi.surat_jalan_ahm')
        ->select('pbi.packing_sheet_number')
        ->select('pbi.nomor_karton')
        ->select('pbi.qty_diterima')
		->from('tr_h3_md_purchase_order as po_md')
		->join('tr_h3_md_purchase_order_parts as pop_md', '(po_md.id_purchase_order = pop_md.id_purchase_order)')
		->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.no_po = po_md.id_purchase_order and pbi.id_part = pop_md.id_part)')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = pbi.no_penerimaan_barang')
		// ->where('po_md.referensi_po_hotline', $this->input->post('po_id'))
        ->where("
            case
                when po_md.jenis_po = 'HTL' then po_md.referensi_po_hotline = '{$this->input->post('po_id')}'
                when po_md.jenis_po = 'URG' then pop_md.referensi = '{$this->input->post('po_id')}'
            end
        ")
		->where('pbi.id_part', $this->input->post('id_part'))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pb.no_penerimaan_barang', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pb.created_at', 'desc');
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