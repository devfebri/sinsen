<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Qty_po_pemenuhan_po_dealer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_referensi_po_hotline_purchase_order', [
                'data' => json_encode($row),
            ], true);
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
        $etd = $this->db
        ->select('hewh.etd')
        ->from('tr_h3_md_history_estimasi_waktu_hotline as hewh')
        ->where('hewh.id_purchase_order = po.id_purchase_order')
        ->where('hewh.id_part = pop.id_part')
        ->where('hewh.source', 'setting_master')
        ->limit(1)
        ->order_by('hewh.created_at', 'desc')
        ->get_compiled_select();

        $etd_revisi = $this->db
        ->select('hewh.etd')
        ->from('tr_h3_md_history_estimasi_waktu_hotline as hewh')
        ->where('hewh.id_purchase_order = po.id_purchase_order')
        ->where('hewh.id_part = pop.id_part')
        ->where('hewh.source', 'upload_revisi')
        ->limit(1)
        ->order_by('hewh.created_at', 'desc')
        ->get_compiled_select();

        $qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_penerimaan_barang_items as pbi')
		->where('pbi.no_po = po.id_purchase_order', null, false)
		->where('pbi.id_part = pop.id_part', null, false)
		->get_compiled_select();

        $this->db
        ->select('po.id_purchase_order')
        ->select('date_format(po.tanggal_po, "%d/%m/%Y") as tanggal_po')
        ->select('po.jenis_po')
        ->select('pop.qty_order')
        ->select("({$etd}) as etd")
        ->select("({$etd_revisi}) as etd_revisi")
        ->from('tr_h3_md_purchase_order as po')
        ->join('tr_h3_md_purchase_order_parts as pop', 'pop.id_purchase_order = po.id_purchase_order')
        ->group_start()
        ->where('po.referensi_po_hotline', $this->input->post('po_id'))
        ->or_where('pop.referensi', $this->input->post('po_id'))
        ->group_end()
        ->where('pop.id_part', $this->input->post('id_part'))
        ->where('po.status', 'Approved')
        ->where("( pop.qty_order - IFNULL(({$qty_penerimaan}), 0) ) > 0", null, false)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.id_purchase_order', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'desc');
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